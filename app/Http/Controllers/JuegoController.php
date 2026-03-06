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

class JuegoController extends Controller
{
    protected $timerService;

    public function __construct(GameTimerService $timerService)
    {
        $this->timerService = $timerService;
    }


    // --- LOGIN DE EQUIPO ---
    public function empezarPartida(EmpezarPartidaRequest $request)
    {
        if (Partida::where('email', $request->email)->exists()) {
            return back()->with('error', 'ESTE EQUIPO YA HA JUGADO.');
        }

        // GUARDAMOS EN BBDD LA HORA EXACTA DE INICIO
        Partida::create([
            'email' => $request->email,
            'nombre_equipo' => $request->nombre_equipo,
            'clase' => $request->clase,
            'fase_actual' => 'Prueba 1 Mario',
            'tiempo_segundos' => 0,
            'inicio_juego' => Carbon::now() // <--- RELOJ MAESTRO
        ]);

        // Guardamos sesión solo para identificar al usuario
        session([
            'email' => $request->email,
            'nombre_equipo' => $request->nombre_equipo,
            'clase' => $request->clase
        ]);

        return redirect('/prueba1mario');
    }

    // =====================================================
    // PRUEBA 1 MARIO (Antigua Prueba 3: AULA 115)
    // =====================================================
    public function mostrarPrueba1()
    {

        if ($this->timerService->verificarTimeout()) return redirect('/final')->with('timeout', true);

        $tiempo_transcurrido = $this->timerService->obtenerTiempoTranscurrido();
        return view('prueba1mario', compact('tiempo_transcurrido'));
    }

    public function validarPrueba1(ValidarPrueba1Request $request)
    {
        if (!$request->completed) {
            return response()->json(['success' => false, 'message' => 'Validación fallida: Faltan acertijos por resolver'], 400);
        }

        // Respuestas correctas normalizadas esperadas (ordenadas por acertijo)
        $respuestas_correctas = [
            ['115', 'aula 115'],
            ['16', 'dieciseis'],
            ['32', 'treinta y dos'],
            ['14', 'catorce'],
            ['5x0lh8bx100127t'],
            ['201', 'doscientos uno']
        ];

        foreach ($request->answers as $index => $answer) {
            if (!isset($respuestas_correctas[$index])) {
                return response()->json(['success' => false, 'message' => 'Validación fallida: Sobran respuestas.'], 400);
            }
            if (!in_array(strtolower(trim($answer)), $respuestas_correctas[$index])) {
                return response()->json(['success' => false, 'message' => 'Validación fallida: Respuesta '.($index + 1).' incorrecta.'], 400);
            }
        }

        // Esta función recibe el AJAX (fetch) de la validación
        session(['prueba1mario_superada' => true]);

        // SOLO actualizamos la fase_actual, NO paramos el reloj maestro de BBDD.
        $partida = $this->timerService->obtenerPartidaActiva();

        if ($partida) {
            $partida->update([
                'fase_actual' => 'Prueba 2 Yoseth',
            ]);

            // REGISTRO DE HITO (FASE 6 V2)
            \App\Models\HitoFase::create([
                'partida_id' => $partida->id,
                'fase' => 'Prueba 1 Mario Superada',
                'segundos_transcurridos' => $this->timerService->obtenerTiempoTranscurrido(),
                'alcanzado_at' => Carbon::now()
            ]);
        }

        // Devolvemos un OK en formato JSON
        return response()->json(['success' => true]);
    }

    // =====================================================
    // PRUEBA 2: EL CLÚSTER FRAGMENTADO (Expendedoras)
    // =====================================================
    public function mostrarPrueba2()
    {

        if ($this->timerService->verificarTimeout()) return redirect('/final')->with('timeout', true);

        $tiempo_transcurrido = $this->timerService->obtenerTiempoTranscurrido();
        return view('prueba2yoseth', compact('tiempo_transcurrido'));
    }

    public function validarPrueba2(Request $request)
    {

        // Validamos la respuesta aquí o lo podemos delegar puramente al check de JS también (como en prueba1).
        // En este caso el front hace el chequeo, el back certifica el tick.
        
        session(['prueba2yoseth_superada' => true]);

        // AHORA SÍ: NO VAMOS AL FINAL AÚN. VAMOS A LA PRUEBA 3.
        $partida = $this->timerService->obtenerPartidaActiva();

        if ($partida && $partida->inicio_juego) {
            $partida->update([
                'fase_actual'     => 'Prueba 3 Angel',
            ]);

            // REGISTRO DE HITO (FASE 6 V2)
            \App\Models\HitoFase::create([
                'partida_id' => $partida->id,
                'fase' => 'Prueba 2 Yoseth Superada',
                'segundos_transcurridos' => $this->timerService->obtenerTiempoTranscurrido(),
                'alcanzado_at' => Carbon::now()
            ]);
        }

        return response()->json(['success' => true]);
    }

    // =====================================================
    // PRUEBA 3: OPERACIÓN CAFEÍNA
    // =====================================================
    public function mostrarPrueba3()
    {

        if ($this->timerService->verificarTimeout()) return redirect('/final')->with('timeout', true);

        $tiempo_transcurrido = $this->timerService->obtenerTiempoTranscurrido();
        return view('prueba3angel', compact('tiempo_transcurrido'));
    }

    public function validarPrueba3(ValidarPrueba3Request $request)
    {

        $CHECKSUM_CORRECTO = 124; // 19 botones + 110 cents + 5 azucar

        if ((int)$request->checksum !== $CHECKSUM_CORRECTO) {
            return response()->json(['success' => false, 'message' => 'Checksum erróneo']);
        }

        session(['prueba3angel_superada' => true]);

        // Ya no es la última, pasamos a Prueba 4
        $partida = $this->timerService->obtenerPartidaActiva();

        if ($partida && $partida->inicio_juego) {
            $partida->update([
                'fase_actual'     => 'Prueba 4 Arturo',
            ]);

            // REGISTRO DE HITO (FASE 6 V2)
            \App\Models\HitoFase::create([
                'partida_id' => $partida->id,
                'fase' => 'Prueba 3 Angel Superada',
                'segundos_transcurridos' => $this->timerService->obtenerTiempoTranscurrido(),
                'alcanzado_at' => Carbon::now()
            ]);
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

        if ($this->timerService->verificarTimeout()) return redirect('/final')->with('timeout', true);

        $tiempo_transcurrido = $this->timerService->obtenerTiempoTranscurrido();
        return view('prueba4arturo', compact('tiempo_transcurrido'));
    }

    public function validarPrueba4(ValidarPrueba4Request $request)
    {

        $CHECKSUM_OASIS = 141; // Ej: 6 filas + cod 128 cafe + 7 items A1 -> Total manual: 141

        if ((int)$request->checksum !== $CHECKSUM_OASIS) {
            return response()->json(['success' => false, 'message' => 'Checksum erróneo']);
        }

        session(['prueba4arturo_superada' => true]);

        // Ya no es la última, pasamos a Prueba 5
        $partida = $this->timerService->obtenerPartidaActiva();

        if ($partida && $partida->inicio_juego) {
            $partida->update([
                'fase_actual'     => 'Prueba 5 Eloy',
            ]);

            // REGISTRO DE HITO (FASE 6 V2)
            \App\Models\HitoFase::create([
                'partida_id' => $partida->id,
                'fase' => 'Prueba 4 Arturo Superada',
                'segundos_transcurridos' => $this->timerService->obtenerTiempoTranscurrido(),
                'alcanzado_at' => Carbon::now()
            ]);
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

        if ($this->timerService->verificarTimeout()) return redirect('/final')->with('timeout', true);

        $tiempo_transcurrido = $this->timerService->obtenerTiempoTranscurrido();
        return view('prueba5eloy', compact('tiempo_transcurrido'));
    }

    public function validarPrueba5(ValidarPrueba5Request $request)
    {

        $PALABRA_ESPEJO = "zasielx";
        $input_normalizado = strtolower(trim($request->checksum));

        if ($input_normalizado !== $PALABRA_ESPEJO) {
            return response()->json(['success' => false, 'message' => 'Palabra clave errónea']);
        }

        session(['prueba5eloy_superada' => true]);

        // AHORA SÍ, PASAMOS A LA PRUEBA 6 (MÍGUE)
        $partida = $this->timerService->obtenerPartidaActiva();

        if ($partida && $partida->inicio_juego) {
            $partida->update([
                'fase_actual'     => 'Prueba 6 Migue',
            ]);

            // REGISTRO DE HITO (FASE 6 V2)
            \App\Models\HitoFase::create([
                'partida_id' => $partida->id,
                'fase' => 'Prueba 5 Eloy Superada',
                'segundos_transcurridos' => $this->timerService->obtenerTiempoTranscurrido(),
                'alcanzado_at' => Carbon::now()
            ]);
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

        if ($this->timerService->verificarTimeout()) return redirect('/final')->with('timeout', true);

        $tiempo_transcurrido = $this->timerService->obtenerTiempoTranscurrido();
        return view('prueba6migue', compact('tiempo_transcurrido'));
    }

    public function validarPrueba6(ValidarPrueba6Request $request)
    {

        if (!$request->completed) {
            return response()->json(['success' => false, 'message' => 'Validación fallida: Secuencia no completada.'], 400);
        }

        session(['prueba6migue_superada' => true]);

        // CÁLCULO FINAL Y CIERRE (Prueba 6 es la última)
        $partida = $this->timerService->obtenerPartidaActiva();

        if ($partida && $partida->inicio_juego) {
            $inicio = Carbon::parse($partida->inicio_juego, config('app.timezone'));
            $segundos_totales = (int) abs(Carbon::now()->diffInSeconds($inicio));

            $partida->update([
                'tiempo_segundos' => $segundos_totales,
                'fase_actual'     => 'FINALIZADO',
            ]);

            // REGISTRO DE HITO FINAL (FASE 6 V2)
            \App\Models\HitoFase::create([
                'partida_id' => $partida->id,
                'fase' => 'JUEGO FINALIZADO',
                'segundos_transcurridos' => $segundos_totales,
                'alcanzado_at' => Carbon::now()
            ]);
        }

        return response()->json([
            'success' => true,
            'mensaje' => '¡NUCLEO ESTABILIZADO! Has escapado del DAWSCAPE.'
        ]);
    }

    public function pedirPista(Request $request)
    {
        $partida = $this->timerService->obtenerPartidaActiva();

        if (!$partida) {
            return response()->json(['success' => false, 'message' => 'Sesión no activa.'], 403);
        }

        $fase = $partida->fase_actual;
        $pista = config("escape.pistas.{$fase}", 'No hay pistas disponibles para esta fase.');
        $penalizacion = config('escape.penalizacion_pista', 180);

        $partida->increment('penalizacion_segundos', $penalizacion);

        // Opcional: Registrar hito de pista solicitada
        \App\Models\HitoFase::create([
            'partida_id' => $partida->id,
            'fase' => "PISTA SOLICITADA: {$fase}",
            'segundos_transcurridos' => $this->timerService->obtenerTiempoTranscurrido(),
            'alcanzado_at' => Carbon::now()
        ]);

        return response()->json([
            'success' => true,
            'pista' => $pista,
            'nueva_penalizacion' => $partida->penalizacion_segundos
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

        return view('final', compact('partida', 'top3'));
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