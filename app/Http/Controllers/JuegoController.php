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

    /**
     * Normaliza y valida una respuesta numérica
     * Elimina espacios en blanco y valida el tipo de dato
     *
     * @param mixed $respuesta Respuesta del usuario (puede tener espacios)
     * @return int|null Respuesta normalizada como integer, o null si es inválida
     */
    private function normalizarRespuestaNumerica($respuesta)
    {
        if (is_null($respuesta) || $respuesta === '') {
            return null;
        }

        // Trim para eliminar espacios al inicio y final
        $respuesta = trim((string)$respuesta);

        // Validar que solo contiene dígitos (y opcionalmente signo negativo)
        if (!preg_match('/^-?\d+$/', $respuesta)) {
            return null;
        }

        return (int)$respuesta;
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
    // PRUEBA 7: CORTAFUEGOS DE CONOCIMIENTO (Banco de Preguntas Dinámico)
    // =====================================================
    public function mostrarPrueba7()
    {
        if ($this->timerService->verificarTimeout()) return redirect()->route('final')->with('timeout', true);

        // EXTRAER Y ASIGNAR 5 PREGUNTAS ALEATORIAS SI NO SE HAN ASIGNADO AÚN
        if (!session()->has('prueba7_preguntas')) {
            $preguntas = \App\Models\PreguntaDinamica::where('activa', true)
                            ->inRandomOrder()
                            ->limit(5)
                            ->get();

            if ($preguntas->isEmpty()) {
                // Failsafe: Si el profesor no ha creado ninguna pregunta activa,
                // regalémosles la victoria cerrando la partida aquí automáticamente.
                return redirect()->route('final');
            }

            session(['prueba7_preguntas' => $preguntas]);
        }

        $tiempo_transcurrido = $this->timerService->obtenerTiempoTranscurrido();
        $this->logAccion($this->timerService->obtenerPartidaActiva(), 'Prueba 7 Dinamica', 'Entra a la prueba');

        $preguntasAsignadas = session('prueba7_preguntas');

        return view('pruebas.prueba7', compact('tiempo_transcurrido', 'preguntasAsignadas'));
    }

    public function validarPrueba7(Request $request)
    {
        $partida = $this->timerService->obtenerPartidaActiva();

        if (!$request->completed) {
            $this->logAccion($partida, 'Prueba 7 Dinamica', 'Intento fallido', ['motivo' => 'Rechazado por cliente incompleto']);
            return response()->json(['success' => false, 'message' => 'Validación fallida.'], 400);
        }

        $preguntasAsignadas = session('prueba7_preguntas');

        if (!$preguntasAsignadas || count($request->respuestas_usuario) !== $preguntasAsignadas->count()) {
            $this->logAccion($partida, 'Prueba 7 Dinamica', 'Intento fallido', ['motivo' => 'Manipulación de array de respuestas']);
            return response()->json(['success' => false, 'message' => 'Desajuste de validadores detectado.'], 400);
        }

        // Normalizamos y validamos una a una en servidor para evitar trampas en JS
        foreach ($request->respuestas_usuario as $index => $respuestaEnviada) {
            $correcta = \Illuminate\Support\Str::slug($preguntasAsignadas[$index]->respuesta_correcta, '');
            $enviada = \Illuminate\Support\Str::slug($respuestaEnviada, '');

            if ($correcta !== $enviada) {
                // Si alguien trampea el JS y manda un array con fallos, el backend lo bloquea.
                return response()->json(['success' => false, 'message' => 'Cheating detectado en backend.'], 400);
            }
        }

        session(['prueba7dinamica_superada' => true]);

        // CÁLCULO FINAL Y CIERRE ABSOLUTO (Prueba 7 es la última oficial)
        if ($partida && $partida->inicio_juego) {
            $this->logAccion($partida, 'Prueba 7 Dinamica', 'Prueba superada -> FINALIZAR');
            $segundos_totales = $this->timerService->obtenerTiempoTranscurrido();

            $partida->update([
                'tiempo_segundos' => $segundos_totales,
                'fase_actual'     => 'FINALIZADO',
            ]);

            // REGISTRO DE HITO FINAL
            \App\Models\HitoFase::create([
                'partida_id' => $partida->id,
                'fase' => 'JUEGO FINALIZADO',
                'segundos_transcurridos' => $segundos_totales,
                'alcanzado_at' => Carbon::now()
            ]);

            Top10Updated::dispatch();
            AdminDashboardUpdated::dispatch();
        }

        return response()->json([
            'success' => true,
            'mensaje' => '¡CORTAFUEGOS DERRIBADO! Has escapado del DAWSCAPE.'
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
