<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Partida;
use Barryvdh\DomPDF\Facade\Pdf;

class JuegoController extends Controller
{
    const MAX_TIEMPO_MINUTOS = 90;

    // --- LÓGICA PRIVADA BLINDADA ---
    private function obtenerTiempoTranscurrido()
    {
        // 1. Buscamos la partida en BBDD por el email de la sesión
        if (!session()->has('email'))
            return 0;

        $partida = Partida::where('email', session('email'))->first();

        if (!$partida || !$partida->inicio_juego) {
            return 0; // 0 segundos si no ha empezado
        }

        // 2. Cálculo ABSOLUTO desde BBDD (Imposible de manipular)
        $inicio = Carbon::parse($partida->inicio_juego);
        $ahora = Carbon::now();

        // Cuántos segundos han pasado realmente desde que pulsó el botón
        $segundos_transcurridos = abs($ahora->diffInSeconds($inicio));

        return (int)$segundos_transcurridos;
    }

    private function verificarTimeout()
    {
        if (!session()->has('email')) return false;

        $partida = $this->obtenerPartidaActiva();
        if (!$partida || !$partida->inicio_juego) return false;
        
        if (str_starts_with($partida->fase_actual, 'FINALIZADO')) {
            // Ya había finalizado
            return $partida->fase_actual === 'FINALIZADO_TIMEOUT';
        }

        $transcurrido = $this->obtenerTiempoTranscurrido();
        if ($transcurrido >= self::MAX_TIEMPO_MINUTOS * 60) {
            $partida->update([
                'tiempo_segundos' => $transcurrido,
                'fase_actual' => 'FINALIZADO_TIMEOUT'
            ]);
            return true;
        }
        return false;
    }

    // =====================================================
    // Método auxiliar: obtener la partida activa
    // =====================================================
    private function obtenerPartidaActiva()
    {
        if (!session()->has('email')) {
            return null;
        }
        return \App\Models\Partida::where('email', session('email'))->first();
    }

    // --- LOGIN DE EQUIPO ---
    public function empezar(Request $request)
    {
        $request->validate([
            'email' => [
                'required', 'email',
                function ($attribute, $value, $fail) {
            if (!str_ends_with($value, '@estudiante.xtart.com')) {
                $fail('ACCESO DENEGADO: Usar correo corporativo (@estudiante.xtart.com).');
            }
        },
            ],
            'nombre_equipo' => 'required|min:2',
            'clase' => 'required'
        ]);

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
    public function mostrarPrueba1Mario()
    {
        if (!session()->has('email')) return redirect('/');

        if ($this->verificarTimeout()) return redirect('/final')->with('timeout', true);

        $tiempo_transcurrido = $this->obtenerTiempoTranscurrido();
        return view('prueba1mario', compact('tiempo_transcurrido'));
    }

    public function validarPrueba1Mario(Request $request)
    {
        // Validación estricta desde el Backend para evitar bypass
        $request->validate([
            'completed' => 'required|boolean',
            'answers'   => 'required|array|min:6'
        ]);

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
        $partida = $this->obtenerPartidaActiva();

        if ($partida) {
            $partida->update([
                'fase_actual' => 'Prueba 2 Yoseth',
            ]);
        }

        // Devolvemos un OK en formato JSON
        return response()->json(['success' => true]);
    }

    // =====================================================
    // PRUEBA 2: EL CLÚSTER FRAGMENTADO (Expendedoras)
    // =====================================================
    public function mostrarPrueba2Yoseth()
    {
        // Require haber completado la 1
        if (!session()->has('prueba1mario_superada')) {
            return redirect('/prueba1mario');
        }

        if ($this->verificarTimeout()) return redirect('/final')->with('timeout', true);

        $tiempo_transcurrido = $this->obtenerTiempoTranscurrido();
        return view('prueba2yoseth', compact('tiempo_transcurrido'));
    }

    public function validarPrueba2Yoseth(Request $request)
    {
        // Require haber completado la 1
        if (!session()->has('prueba1mario_superada')) {
            return response()->json(['success' => false, 'message' => 'Falta completar pruebas previas'], 403);
        }

        // Validamos la respuesta aquí o lo podemos delegar puramente al check de JS también (como en prueba1).
        // En este caso el front hace el chequeo, el back certifica el tick.
        
        session(['prueba2yoseth_superada' => true]);

        // AHORA SÍ: CÁLCULO FINAL Y CIERRE
        $partida = $this->obtenerPartidaActiva();

        if ($partida && $partida->inicio_juego) {
            $inicio = Carbon::parse($partida->inicio_juego, config('app.timezone'));
            $segundos_totales = (int) abs(Carbon::now()->diffInSeconds($inicio));

            $partida->update([
                'tiempo_segundos' => $segundos_totales,
                'fase_actual'     => 'FINALIZADO',
            ]);
        }

        return response()->json(['success' => true]);
    }

    // --- PANTALLA FINAL ---
    public function mostrarFinal()
    {
        if (!session()->has('prueba2yoseth_superada'))
            return redirect('/prueba2yoseth');

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
            ? $this->obtenerTiempoTranscurrido()
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

    // --- PANEL DE ADMINISTRACIÓN ---
    public function adminPanel()
    {
        // Top 5 finalizados (podio)
        $top5 = Partida::where('fase_actual', 'FINALIZADO')
            ->orderBy('tiempo_segundos', 'asc')
            ->limit(5)
            ->get();

        // Primero los finalizados ordenados por tiempo, luego los timeouts, luego los que siguen jugando
        $partidas = Partida::orderByRaw("
            CASE 
                WHEN fase_actual = 'FINALIZADO' THEN 0 
                WHEN fase_actual = 'FINALIZADO_TIMEOUT' THEN 1
                ELSE 2 
            END")
            ->orderBy('tiempo_segundos', 'asc')
            ->get();
            
        return view('admin', compact('partidas', 'top5'));
    }

    public function eliminarEquipo($id)
    {
        $partida = Partida::find($id);
        if ($partida) {
            $partida->delete();
            // Si el equipo estaba de alguna manera logeado en la sesión de este navegador, 
            // no limpiamos la sesión de admin, sólo el registro.
            return back()->with('success', 'Equipo eliminado correctamente del sistema.');
        }
        return back()->with('error', 'El equipo no existe.');
    }

    public function exportarTop20PDF()
    {
        $top20 = Partida::where('fase_actual', 'FINALIZADO')
            ->orderBy('tiempo_segundos', 'asc')
            ->limit(20)
            ->get();

        $pdf = Pdf::loadView('ranking-pdf', compact('top20'));
        return $pdf->download('Ranking_Top_20_DAWSCAPE.pdf');
    }
}