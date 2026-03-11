<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Partida;
use Barryvdh\DomPDF\Facade\Pdf;
use \App\Services\GameTimerService;
use App\Http\Requests\EmpezarPartidaRequest;
use App\Http\Requests\ValidarPrueba1Request;
use App\Http\Requests\ValidarPrueba3Request;
use App\Http\Requests\ValidarPrueba4Request;
use App\Http\Requests\ValidarPrueba5Request;
use App\Http\Requests\ValidarPrueba6Request;
use App\Events\Top10Updated;
use App\Events\AdminDashboardUpdated;
use App\Models\LogJuego;

class JuegoController extends Controller
{
    protected $timerService;

    public function __construct(GameTimerService $timerService)
    {
        $this->timerService = $timerService;
    }

    private function logAccion($partida, $fase, $accion, $detalles = null)
    {
        if ($partida) {
            LogJuego::create([
                'partida_id' => $partida->id,
                'fase' => $fase,
                'accion' => $accion,
                'detalles' => $detalles
            ]);
        }
    }

    private function desbloquearLogro($partida, $logroId)
    {
        if (!$partida) return false;

        $logrosActuales = $partida->logros ?? [];
        if (!in_array($logroId, $logrosActuales)) {
            $logrosActuales[] = $logroId;
            $partida->update(['logros' => $logrosActuales]);

            $this->logAccion($partida, $partida->fase_actual, 'Logro Desbloqueado', ['logro' => $logroId]);

            $pendientes = session('nuevos_logros', []);
            $pendientes[] = $logroId;
            session(['nuevos_logros' => $pendientes]);

            return true;
        }
        return false;
    }


    // --- LOGIN DE EQUIPO ---
    public function empezarPartida(EmpezarPartidaRequest $request)
    {
        $redirDestino = $request->input('redirect_to');

        // ── RECONEXIÓN: el equipo ya existe, solo restauramos la sesión ────
        $partidaExistente = Partida::where('email', $request->email)->first();
        if ($partidaExistente) {
            // Verificamos que el nombre de equipo coincide (seguridad mínima)
            if (strtolower(trim($partidaExistente->nombre_equipo)) !== strtolower(trim($request->nombre_equipo))) {
                return back()->with('error', 'NOMBRE DE EQUIPO NO COINCIDE CON EL REGISTRO EXISTENTE.');
            }

            // Restauramos la sesión con los datos de la partida guardados en BBDD
            session([
                'email'        => $partidaExistente->email,
                'nombre_equipo' => $partidaExistente->nombre_equipo,
                'clase'        => $partidaExistente->clase,
            ]);

            // Redirigimos al QR (o a prueba1 como fallback)
            $destino = ($redirDestino && str_starts_with($redirDestino, '/')) ? $redirDestino : route('prueba1');
            return redirect($destino);
        }

        // ── REGISTRO NUEVO ────────────────────────────────────────────────
        Partida::create([
            'email'          => $request->email,
            'nombre_equipo'  => $request->nombre_equipo,
            'clase'          => $request->clase,
            'color_hex'      => $request->color_hex ?? '#3b82f6',
            'icono'          => $request->icono ?? 'ph-user-focus',
            'fase_actual'    => 'Prueba 1 Mario',
            'tiempo_segundos' => 0,
            'inicio_juego'   => Carbon::now()
        ]);

        session([
            'email'         => $request->email,
            'nombre_equipo' => $request->nombre_equipo,
            'clase'         => $request->clase,
        ]);

        $destino = ($redirDestino && str_starts_with($redirDestino, '/')) ? $redirDestino : route('prueba1');
        return redirect($destino);
    }

    // =====================================================
    // PRUEBA 1 MARIO (Antigua Prueba 3: AULA 115)
    // =====================================================
    public function mostrarPrueba1()
    {

        if ($this->timerService->verificarTimeout()) return redirect()->route('final')->with('timeout', true);

        $tiempo_transcurrido = $this->timerService->obtenerTiempoTranscurrido();
        $this->logAccion($this->timerService->obtenerPartidaActiva(), 'Prueba 1 Mario', 'Entra a la prueba');
        return view('pruebas.prueba1', compact('tiempo_transcurrido'));
    }

    public function validarPrueba1(ValidarPrueba1Request $request)
    {
        $partida = $this->timerService->obtenerPartidaActiva();

        if (!$request->completed) {
            $this->logAccion($partida, 'Prueba 1 Mario', 'Intento fallido', ['motivo' => 'Faltan acertijos por resolver']);
            return response()->json(['success' => false, 'message' => 'Validación fallida: Faltan acertijos por resolver'], 400);
        }

        // Respuestas correctas normalizadas esperadas (ordenadas por acertijo)
        $respuestas_correctas = config('escape.respuestas.prueba1');

        foreach ($request->answers as $index => $answer) {
            if (!isset($respuestas_correctas[$index])) {
                $this->logAccion($partida, 'Prueba 1 Mario', 'Intento fallido', ['motivo' => 'Sobran respuestas']);
                return response()->json(['success' => false, 'message' => 'Validación fallida: Sobran respuestas.'], 400);
            }
            if (!in_array(strtolower(trim($answer)), $respuestas_correctas[$index])) {
                $this->logAccion($partida, 'Prueba 1 Mario', 'Intento fallido', ['motivo' => 'Respuesta '.($index + 1).' incorrecta: '.$answer]);
                return response()->json(['success' => false, 'message' => 'Validación fallida: Respuesta '.($index + 1).' incorrecta.'], 400);
            }
        }

        // Esta función recibe el AJAX (fetch) de la validación
        session(['prueba1mario_superada' => true]);

        // SOLO actualizamos la fase_actual, NO paramos el reloj maestro de BBDD.
        if ($partida) {
            $this->logAccion($partida, 'Prueba 1 Mario', 'Prueba superada');

            // LOGRO 1: Speedrunner Ninja (Prueba 1 superada en menos de 2 minutos sin penalizaciones)
            $tiempo = $this->timerService->obtenerTiempoTranscurrido();
            if ($tiempo < 120) {
                $this->desbloquearLogro($partida, 'speedrunner_ninja');
            }

            $this->timerService->avanzarFase($partida, 'Prueba 2 Yoseth', 'Prueba 1 Mario Superada');
            Top10Updated::dispatch();
            AdminDashboardUpdated::dispatch();
        }

        // Devolvemos un OK en formato JSON
        return response()->json(['success' => true]);
    }

    // =====================================================
    // PRUEBA 2: EL CLÚSTER FRAGMENTADO (Expendedoras)
    // =====================================================
    public function mostrarPrueba2()
    {

        if ($this->timerService->verificarTimeout()) return redirect()->route('final')->with('timeout', true);

        $tiempo_transcurrido = $this->timerService->obtenerTiempoTranscurrido();
        $this->logAccion($this->timerService->obtenerPartidaActiva(), 'Prueba 2 Yoseth', 'Entra a la prueba');
        return view('pruebas.prueba2', compact('tiempo_transcurrido'));
    }

    public function validarPrueba2(Request $request)
    {

        // Validamos la respuesta aquí o lo podemos delegar puramente al check de JS también (como en prueba1).
        // En este caso el front hace el chequeo, el back certifica el tick.

        session(['prueba2yoseth_superada' => true]);

        // AHORA SÍ: NO VAMOS AL FINAL AÚN. VAMOS A LA PRUEBA 3.
        $partida = $this->timerService->obtenerPartidaActiva();

        if ($partida && $partida->inicio_juego) {
            $this->logAccion($partida, 'Prueba 2 Yoseth', 'Prueba superada');
            $this->timerService->avanzarFase($partida, 'Prueba 3 Angel', 'Prueba 2 Yoseth Superada');
            Top10Updated::dispatch();
            AdminDashboardUpdated::dispatch();
        }

        return response()->json(['success' => true]);
    }

    // =====================================================
    // PRUEBA 3: OPERACIÓN CAFEÍNA
    // =====================================================
    public function mostrarPrueba3()
    {

        if ($this->timerService->verificarTimeout()) return redirect()->route('final')->with('timeout', true);

        $tiempo_transcurrido = $this->timerService->obtenerTiempoTranscurrido();
        $this->logAccion($this->timerService->obtenerPartidaActiva(), 'Prueba 3 Angel', 'Entra a la prueba');
        return view('pruebas.prueba3', compact('tiempo_transcurrido'));
    }

    public function validarPrueba3(ValidarPrueba3Request $request)
    {

        $CHECKSUM_CORRECTO = config('escape.respuestas.prueba3'); // 18 botones + 80 cents + 5 azucar
        $partida = $this->timerService->obtenerPartidaActiva();
        
        // DEBUG: Log para verificar qué valores se están comparando
        \Log::info('Prueba3 Validation', [
            'input' => $request->checksum,
            'expected' => $CHECKSUM_CORRECTO,
            'input_type' => gettype($request->checksum),
            'expected_type' => gettype($CHECKSUM_CORRECTO),
            'int_input' => (int)$request->checksum
        ]);

        // Normalizar entrada: trim espacios y convertir a integer
        $respuesta_usuario = (int)trim($request->checksum);

        if ($respuesta_usuario !== $CHECKSUM_CORRECTO) {
            $this->logAccion($partida, 'Prueba 3 Angel', 'Intento fallido', ['input' => $request->checksum, 'corregido' => $respuesta_usuario, 'esperado' => $CHECKSUM_CORRECTO]);
            return response()->json(['success' => false, 'message' => 'Checksum erróneo']);
        }

        session(['prueba3angel_superada' => true]);

        // Ya no es la última, pasamos a Prueba 4

        if ($partida && $partida->inicio_juego) {
            $this->logAccion($partida, 'Prueba 3 Angel', 'Prueba superada');
            $this->timerService->avanzarFase($partida, 'Prueba 4 Arturo', 'Prueba 3 Angel Superada');
            Top10Updated::dispatch();
            AdminDashboardUpdated::dispatch();
        }

        // Mensaje final desde Back
        $mensajeFinal = "Has purgado la red de cafeína. El sistema vuelve a fluir, pero el rastro digital\nno termina aquí. La siguiente pieza del puzzle te espera donde los snacks\nson liberados de su encierro.";

        return response()->json([
            'success' => true,
            'mensaje' => $mensajeFinal
        ]);
    }

    // =====================================================
    // PRUEBA 4: OPERACIÓN REPUESTOS (Oasis)
    // =====================================================
    public function mostrarPrueba4()
    {

        if ($this->timerService->verificarTimeout()) return redirect()->route('final')->with('timeout', true);

        $tiempo_transcurrido = $this->timerService->obtenerTiempoTranscurrido();
        $this->logAccion($this->timerService->obtenerPartidaActiva(), 'Prueba 4 Arturo', 'Entra a la prueba');
        return view('pruebas.prueba4', compact('tiempo_transcurrido'));
    }

    public function validarPrueba4(ValidarPrueba4Request $request)
    {

        $CHECKSUM_OASIS = config('escape.respuestas.prueba4'); // Ej: 6 filas + cod 128 cafe + 7 items A1 -> Total manual: 141
        $partida = $this->timerService->obtenerPartidaActiva();

        // Normalizar entrada: trim espacios y convertir a integer
        $respuesta_usuario = (int)trim($request->checksum);

        if ($respuesta_usuario !== $CHECKSUM_OASIS) {
            $this->logAccion($partida, 'Prueba 4 Arturo', 'Intento fallido', ['input' => $request->checksum, 'corregido' => $respuesta_usuario, 'esperado' => $CHECKSUM_OASIS]);
            return response()->json(['success' => false, 'message' => 'Checksum erróneo']);
        }

        session(['prueba4arturo_superada' => true]);

        // Ya no es la última, pasamos a Prueba 5

        if ($partida && $partida->inicio_juego) {
            $this->logAccion($partida, 'Prueba 4 Arturo', 'Prueba superada');
            $this->timerService->avanzarFase($partida, 'Prueba 5 Eloy', 'Prueba 4 Arturo Superada');
            Top10Updated::dispatch();
            AdminDashboardUpdated::dispatch();
        }

        // Mensaje final desde Back
        $mensajeFinal = "El orden natural ha desaparecido. Lo que empieza por A ahora termina en Z.\nEl archivo maestro ha sido fragmentado y ocultado en 64 nodos metálicos.";

        return response()->json([
            'success' => true,
            'mensaje' => $mensajeFinal
        ]);
    }

    // =====================================================
    // PRUEBA 5: PROTOCOLO ESPEJO (Taquillas)
    // =====================================================
    public function mostrarPrueba5()
    {

        if ($this->timerService->verificarTimeout()) return redirect()->route('final')->with('timeout', true);

        $tiempo_transcurrido = $this->timerService->obtenerTiempoTranscurrido();
        $this->logAccion($this->timerService->obtenerPartidaActiva(), 'Prueba 5 Eloy', 'Entra a la prueba');
        return view('pruebas.prueba5', compact('tiempo_transcurrido'));
    }

    public function validarPrueba5(ValidarPrueba5Request $request)
    {

        $PALABRA_ESPEJO = config('escape.respuestas.prueba5');
        $input_normalizado = strtolower(trim($request->checksum));
        $partida = $this->timerService->obtenerPartidaActiva();

        // LOGRO 3: Hackerman Easter Egg
        if ($input_normalizado === 'sudo rm -rf') {
            $this->desbloquearLogro($partida, 'hackerman');
            $this->logAccion($partida, 'Prueba 5 Eloy', 'Easter Egg Ejecutado', ['input' => $input_normalizado]);
            return response()->json(['success' => false, 'message' => 'Comando peligroso detectado. Permiso denegado. Logro [HACKERMAN] desbloqueado.']);
        }

        if ($input_normalizado !== $PALABRA_ESPEJO) {
            $this->logAccion($partida, 'Prueba 5 Eloy', 'Intento fallido', ['input' => $input_normalizado]);
            return response()->json(['success' => false, 'message' => 'Palabra clave errónea']);
        }

        session(['prueba5eloy_superada' => true]);

        // AHORA SÍ, PASAMOS A LA PRUEBA 6 (MÍGUE)

        if ($partida && $partida->inicio_juego) {
            $this->logAccion($partida, 'Prueba 5 Eloy', 'Prueba superada');
            $this->timerService->avanzarFase($partida, 'Prueba 6 Migue', 'Prueba 5 Eloy Superada');
            Top10Updated::dispatch();
            AdminDashboardUpdated::dispatch();
        }

        // Mensaje final desde Back
        $mensajeFinal = "El orden ha sido restaurado. El sistema vuelve a reconocer el abecedario\noriginal. Pero el núcleo del DAWSCAPE está inestable...\nLa siguiente anomalía te espera en la terminal principal, donde\nse debe calibrar manualmente la sobrecarga.";

        return response()->json([
            'success' => true,
            'mensaje' => $mensajeFinal
        ]);
    }

    // =====================================================
    // PRUEBA 6: SOBRECARGA DEL SISTEMA (Mígue - Simon Dice Cooperativo)
    // =====================================================
    public function mostrarPrueba6()
    {

        if ($this->timerService->verificarTimeout()) return redirect()->route('final')->with('timeout', true);

        // Generar secuencia en el backend (ej: 15 pasos)
        $maxSteps = 15;
        $sequence = [];
        for ($i = 0; $i < $maxSteps; $i++) {
            $sequence[] = rand(0, 3);
        }

        // Guardar secuencia en sesión
        session(['prueba6_sequence' => $sequence]);

        $tiempo_transcurrido = $this->timerService->obtenerTiempoTranscurrido();
        $this->logAccion($this->timerService->obtenerPartidaActiva(), 'Prueba 6 Migue', 'Entra a la prueba');
        return view('pruebas.prueba6', compact('tiempo_transcurrido'));
    }

    public function validarPrueba6(ValidarPrueba6Request $request)
    {
        $partida = $this->timerService->obtenerPartidaActiva();

        if (!$request->completed || !is_array($request->expected_sequence)) {
            $this->logAccion($partida, 'Prueba 6 Migue', 'Intento fallido', ['motivo' => 'Datos incompletos']);
            return response()->json(['success' => false, 'message' => 'Validación fallida: Datos incompletos.'], 400);
        }

        $sessionSequence = session('prueba6_sequence', []);

        if (empty($sessionSequence) || $request->expected_sequence !== $sessionSequence) {
            $this->logAccion($partida, 'Prueba 6 Migue', 'Intento fallido', ['motivo' => 'Secuencia incorrecta']);
            return response()->json(['success' => false, 'message' => 'Validación fallida: Secuencia incorrecta o manipulada.'], 400);
        }

        session(['prueba6migue_superada' => true]);

        // FINALIZA LA PRUEBA 6 -> AVANZAMOS A PRUEBA 7
        if ($partida && $partida->inicio_juego) {
            $this->logAccion($partida, 'Prueba 6 Migue', 'Prueba superada');

            // LOGRO 2: Cerebro Intacto (No usar pistas)
            if (($partida->penalizacion_segundos ?? 0) == 0) {
                $this->desbloquearLogro($partida, 'cerebro_intacto');
            }

            $this->timerService->avanzarFase($partida, 'Prueba 7 Dinamica', 'Prueba 6 Migue Superada');
            Top10Updated::dispatch();
            AdminDashboardUpdated::dispatch();
        }

        $mensajeFinal = "Núcleo temporal estabilizado exitosamente. Pero el sistema central ha desplegado un último Cortafuegos de Conocimiento antes de liberar las cierres magnéticos.";

        return response()->json([
            'success' => true,
            'mensaje' => $mensajeFinal
        ]);
    }

    // =====================================================
    // PRUEBA 7: OPERACIÓN EL CÓDICE OCULTO — Cerradura Digital + QRs físicos
    // =====================================================

    /**
     * Base de datos de dígitos: cada dígito tiene una ubicación física y un acertijo.
     * Modifica aquí ubicaciones y acertijos sin tocar la vista.
     */
    private const DIGITOS_CODICE = [
        0 => [
            'ubicacion' => 'Pegado en la máquina de café',
            'acertijo'  => 'No valgo absolutamente nada si me pones a la izquierda, pero multiplico tu riqueza si me pones a la derecha. Fui el último dígito en inventarse, nacido en la India para representar la nada y el vacío absoluto.'
        ],
        1 => [
            'ubicacion' => 'Puerta de dirección',
            'acertijo'  => 'Soy el único número cuyo cuadrado, cubo y raíz son exactamente idénticos a mí mismo. En la tabla periódica, corono al elemento más ligero y abundante de todo el universo.'
        ],
        2 => [
            'ubicacion' => 'Baño de la planta baja',
            'acertijo'  => 'En informática, soy la base fundamental de todo el lenguaje que entienden las máquinas. Soy la excepción a la regla: el único número par que tiene el honor de ser primo.'
        ],
        3 => [
            'ubicacion' => 'Cristal de la biblioteca',
            'acertijo'  => 'Newton necesitó exactamente este número de leyes para explicar el movimiento del universo. Si quieres definir un plano geométrico perfecto en el espacio sin que cojee, me necesitas estrictamente.'
        ],
        4 => [
            'ubicacion' => 'Tablón de anuncios principal',
            'acertijo'  => 'Lato sin descanso en tu pecho gracias a mis cavidades anatómicas. En la genética, soy el total de letras químicas (A, C, G, T) que escriben el libro de la vida.'
        ],
        5 => [
            'ubicacion' => 'Silla del profesor en el taller 1',
            'acertijo'  => 'En la geometría clásica, represento el total de sólidos platónicos perfectos que existen. En el emblema de los Juegos Olímpicos, doy forma a los anillos que representan las partes habitadas del mundo.'
        ],
        6 => [
            'ubicacion' => 'Junto al extintor del pasillo norte',
            'acertijo'  => 'Soy el primer número perfecto, la suma de mis divisores exactos (1, 2 y 3) te dará mi valor. En química, soy el cimiento de la vida, el número atómico del carbono.'
        ],
        7 => [
            'ubicacion' => 'Mesa de ping pong del patio',
            'acertijo'  => 'Químicamente hablando, soy la neutralidad perfecta, el pH del agua pura. Anatómicamente, soy el número de vértebras cervicales que comparte un humano con una jirafa.'
        ],
        8 => [
            'ubicacion' => 'Canasta de baloncesto',
            'acertijo'  => 'En el mundo digital, formo la unidad básica de memoria (el byte) cuando agrupo a mis pequeños soldados (los bits). Soy el número de planetas reconocidos oficialmente en nuestro sistema solar.'
        ],
        9 => [
            'ubicacion' => 'Mostrador de conserjería',
            'acertijo'  => 'Tengo una maldición: multiplícame por el entero que quieras; si sumas las cifras del resultado, siempre volverás a mí. Soy el total de círculos del Infierno de Dante.'
        ],
    ];

    public function mostrarPrueba7()
    {
        if ($this->timerService->verificarTimeout()) return redirect()->route('final')->with('timeout', true);

        $partida = $this->timerService->obtenerPartidaActiva();

        // Generar y persistir combinación única por equipo si aún no existe
        if ($partida && empty($partida->combinacion_codice)) {
            $digitos = range(0, 9);
            shuffle($digitos);
            $combinacion = array_slice($digitos, 0, 4); // 4 dígitos únicos sin repetir
            $partida->update(['combinacion_codice' => $combinacion, 'intentos_codice' => 0]);
        }

        $combinacion = $partida ? ($partida->combinacion_codice ?? [0,1,2,3]) : [0,1,2,3];

        // Construir array de pistas (ubicaciones) para los 4 dígitos de esta partida
        $pistas = array_map(function($digito) {
            return self::DIGITOS_CODICE[$digito]['ubicacion'];
        }, $combinacion);

        $intentos_fallidos = $partida ? ($partida->intentos_codice ?? 0) : 0;

        $tiempo_transcurrido = $this->timerService->obtenerTiempoTranscurrido();
        $this->logAccion($partida, 'Prueba 7 Dinamica', 'Entra a la prueba (Códice Oculto)');

        return view('pruebas.prueba7dinamica', compact('tiempo_transcurrido', 'combinacion', 'pistas', 'intentos_fallidos'));
    }

    public function validarPrueba7(Request $request)
    {
        $partida = $this->timerService->obtenerPartidaActiva();

        if (!$partida) {
            return response()->json(['success' => false, 'message' => 'Sesión no encontrada.'], 401);
        }

        $combinacionCorrecta = $partida->combinacion_codice;

        if (!$combinacionCorrecta || count($combinacionCorrecta) !== 4) {
            $this->logAccion($partida, 'Prueba 7 Dinamica', 'Error: combinación no inicializada');
            return response()->json(['success' => false, 'message' => 'Error interno: combinación no inicializada.'], 500);
        }

        $digitsInput = $request->input('digits', []);

        // Normalizar: convertir a enteros
        $digitsInput = array_map('intval', array_values((array) $digitsInput));

        if (count($digitsInput) !== 4) {
            return response()->json(['success' => false, 'message' => 'Debes introducir exactamente 4 dígitos.']);
        }

        if ($digitsInput === array_map('intval', $combinacionCorrecta)) {
            // ✅ COMBINACIÓN CORRECTA
            session(['prueba7dinamica_superada' => true]);

            if ($partida->inicio_juego) {
                $this->logAccion($partida, 'Prueba 7 Dinamica', 'Códice Oculto — COMBINACIÓN CORRECTA');
                $segundos_totales = $this->timerService->obtenerTiempoTranscurrido();

                $partida->update([
                    'tiempo_segundos' => $segundos_totales,
                    'fase_actual'     => 'FINALIZADO',
                ]);

                \App\Models\HitoFase::create([
                    'partida_id'            => $partida->id,
                    'fase'                  => 'JUEGO FINALIZADO',
                    'segundos_transcurridos' => $segundos_totales,
                    'alcanzado_at'          => Carbon::now()
                ]);

                Top10Updated::dispatch();
                AdminDashboardUpdated::dispatch();
            }

            return response()->json(['success' => true]);
        }

        // ❌ COMBINACIÓN INCORRECTA — incrementar contador
        $nuevosIntentos = ($partida->intentos_codice ?? 0) + 1;
        $partida->update(['intentos_codice' => $nuevosIntentos]);

        $this->logAccion($partida, 'Prueba 7 Dinamica', 'Intento fallido', [
            'intento'  => $nuevosIntentos,
            'enviado'  => $digitsInput,
            'correcto' => $combinacionCorrecta
        ]);

        return response()->json([
            'success'  => false,
            'intentos' => $nuevosIntentos
        ]);
    }

    // --- PANTALLA FINAL ---
    public function mostrarFinal()
    {
        // Traemos los datos REALES de la BBDD
        $partida = Partida::where('email', session('email'))->first();

        // Top 3 equipos finalizados, ordenados por tiempo
        $top3 = Partida::where('fase_actual', 'FINALIZADO')
            ->orderBy('tiempo_segundos', 'asc')
            ->limit(3)
            ->get();

        // Calcular los deltas de tiempo por cada prueba (Performance breakdown)
        $hitos = [];
        if ($partida) {
            $hitos_db = \App\Models\HitoFase::where('partida_id', $partida->id)
                ->orderBy('segundos_transcurridos', 'asc')
                ->get();

            $last_seconds = 0;
            foreach ($hitos_db as $hito) {
                // El hito representa cuándo se *completó* o se *llegó* a una fase.
                $delta = $hito->segundos_transcurridos - $last_seconds;
                $hitos[] = [
                    'fase' => $hito->fase,
                    'segundos_invertidos' => $delta,
                    'total_acumulado' => $hito->segundos_transcurridos
                ];
                $last_seconds = $hito->segundos_transcurridos;
            }
        }

        return view('final', compact('partida', 'top3', 'hitos'));
    }

    // --- VALIDACIONES ---
    // (Pruedas 1 y 2 fueron eliminadas a petición del cliente)

    // --- PRUEBA: AULA 115 (Ordenadores y número de serie) ---
    public function mostrarAula115()
    {
        // Accesible sin login: si hay sesión usa el timer de BBDD, si no empieza en 0
        $tiempo_transcurrido = session()->has('email')
            ? $this->timerService->obtenerTiempoTranscurrido()
            : 0;

        return view('pruebas.aula-115', compact('tiempo_transcurrido'));
    }

    public function completarAula115(Request $request)
    {
        // Si hay sesión activa, registrar en BBDD
        if (session()->has('email')) {
            $partida = Partida::where('email', session('email'))->first();

            if ($partida) {
                $inicio = Carbon::parse($partida->inicio_juego);
                $segundos_totales = abs(Carbon::now()->diffInSeconds($inicio));

                $partida->update([
                    'tiempo_segundos' => $segundos_totales,
                    'fase_actual' => 'FINALIZADO'
                ]);
            }
        }

        return response()->json(['success' => true, 'message' => 'Escape room completado']);
    }

    // MÉTODOS DE QRs EXTRAÍDOS A QrController

    // MÉTODOS DE ADMIN EXTRAÍDOS A AdminController
}
