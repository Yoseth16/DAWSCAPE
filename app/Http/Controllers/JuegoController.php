<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Partida;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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

        // AHORA SÍ: NO VAMOS AL FINAL AÚN. VAMOS A LA PRUEBA 3.
        $partida = $this->obtenerPartidaActiva();

        if ($partida && $partida->inicio_juego) {
            $partida->update([
                'fase_actual'     => 'Prueba 3 Angel',
            ]);
        }

        return response()->json(['success' => true]);
    }

    // =====================================================
    // PRUEBA 3: OPERACIÓN CAFEÍNA
    // =====================================================
    public function mostrarPrueba3Angel()
    {
        // Require haber completado la 2
        if (!session()->has('prueba2yoseth_superada')) {
            return redirect('/prueba2yoseth');
        }

        if ($this->verificarTimeout()) return redirect('/final')->with('timeout', true);

        $tiempo_transcurrido = $this->obtenerTiempoTranscurrido();
        return view('prueba3angel', compact('tiempo_transcurrido'));
    }

    public function validarPrueba3Angel(Request $request)
    {
        // Require haber completado la 2
        if (!session()->has('prueba2yoseth_superada')) {
            return response()->json(['success' => false, 'message' => 'Falta completar pruebas previas'], 403);
        }

        $request->validate([
            'checksum' => 'required|numeric'
        ]);

        $CHECKSUM_CORRECTO = 124; // 19 botones + 110 cents + 5 azucar

        if ((int)$request->checksum !== $CHECKSUM_CORRECTO) {
            return response()->json(['success' => false, 'message' => 'Checksum erróneo']);
        }

        session(['prueba3angel_superada' => true]);

        // Ya no es la última, pasamos a Prueba 4
        $partida = $this->obtenerPartidaActiva();

        if ($partida && $partida->inicio_juego) {
            $partida->update([
                'fase_actual'     => 'Prueba 4 Arturo',
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
    public function mostrarPrueba4Arturo()
    {
        // Require haber completado la 3
        if (!session()->has('prueba3angel_superada')) {
            return redirect('/prueba3angel');
        }

        if ($this->verificarTimeout()) return redirect('/final')->with('timeout', true);

        $tiempo_transcurrido = $this->obtenerTiempoTranscurrido();
        return view('prueba4arturo', compact('tiempo_transcurrido'));
    }

    public function validarPrueba4Arturo(Request $request)
    {
        // Require haber completado la 3
        if (!session()->has('prueba3angel_superada')) {
            return response()->json(['success' => false, 'message' => 'Falta completar pruebas previas'], 403);
        }

        $request->validate([
            'checksum' => 'required|numeric'
        ]);

        $CHECKSUM_OASIS = 141; // Ej: 6 filas + cod 128 cafe + 7 items A1 -> Total manual: 141

        if ((int)$request->checksum !== $CHECKSUM_OASIS) {
            return response()->json(['success' => false, 'message' => 'Checksum erróneo']);
        }

        session(['prueba4arturo_superada' => true]);

        // Ya no es la última, pasamos a Prueba 5
        $partida = $this->obtenerPartidaActiva();

        if ($partida && $partida->inicio_juego) {
            $partida->update([
                'fase_actual'     => 'Prueba 5 Eloy',
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
    public function mostrarPrueba5Eloy()
    {
        // Require haber completado la 4
        if (!session()->has('prueba4arturo_superada')) {
            return redirect('/prueba4arturo');
        }

        if ($this->verificarTimeout()) return redirect('/final')->with('timeout', true);

        $tiempo_transcurrido = $this->obtenerTiempoTranscurrido();
        return view('prueba5eloy', compact('tiempo_transcurrido'));
    }

    public function validarPrueba5Eloy(Request $request)
    {
        // Require haber completado la 4
        if (!session()->has('prueba4arturo_superada')) {
            return response()->json(['success' => false, 'message' => 'Falta completar pruebas previas'], 403);
        }

        $request->validate([
            'checksum' => 'required|string'
        ]);

        $PALABRA_ESPEJO = "zasielx";
        $input_normalizado = strtolower(trim($request->checksum));

        if ($input_normalizado !== $PALABRA_ESPEJO) {
            return response()->json(['success' => false, 'message' => 'Palabra clave errónea']);
        }

        session(['prueba5eloy_superada' => true]);

        // AHORA SÍ, PASAMOS A LA PRUEBA 6 (MÍGUE)
        $partida = $this->obtenerPartidaActiva();

        if ($partida && $partida->inicio_juego) {
            $partida->update([
                'fase_actual'     => 'Prueba 6 Migue',
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
    public function mostrarPrueba6Migue()
    {
        // Require haber completado la 5
        if (!session()->has('prueba5eloy_superada')) {
            return redirect('/prueba5eloy');
        }

        if ($this->verificarTimeout()) return redirect('/final')->with('timeout', true);

        $tiempo_transcurrido = $this->obtenerTiempoTranscurrido();
        return view('prueba6migue', compact('tiempo_transcurrido'));
    }

    public function validarPrueba6Migue(Request $request)
    {
        // Require haber completado la 5
        if (!session()->has('prueba5eloy_superada')) {
            return response()->json(['success' => false, 'message' => 'Falta completar pruebas previas'], 403);
        }

        // La verificación de la secuencia se hace enteramente en el front, 
        // así que si llegamos aquí, el usuario ha resuelto el puzzle.
        session(['prueba6migue_superada' => true]);

        // CÁLCULO FINAL Y CIERRE (Prueba 6 es la última)
        $partida = $this->obtenerPartidaActiva();

        if ($partida && $partida->inicio_juego) {
            $inicio = Carbon::parse($partida->inicio_juego, config('app.timezone'));
            $segundos_totales = (int) abs(Carbon::now()->diffInSeconds($inicio));

            $partida->update([
                'tiempo_segundos' => $segundos_totales,
                'fase_actual'     => 'FINALIZADO',
            ]);
        }

        return response()->json([
            'success' => true,
            'mensaje' => '¡NUCLEO ESTABILIZADO! Has escapado del DAWSCAPE.'
        ]);
    }

    // --- PANTALLA FINAL ---
    public function mostrarFinal()
    {
        if (!session()->has('prueba6migue_superada'))
            return redirect('/prueba6migue');

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

    // =====================================================
    // SISTEMA DE QRs (MÓDULO VERSIÓN BBDD DINÁMICA)
    // =====================================================

    public function gestionarQrs()
    {
        $qrs = \App\Models\Qr::orderBy('created_at', 'desc')->get();
        return view('admin.gestionar-qrs', compact('qrs'));
    }

    public function guardarQr(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'fase_requerida' => 'nullable|string|max:255'
        ]);

        // Generar URL completa forzando la base de APP_URL para evitar fallos de host/proxy en producción
        $baseUrl = rtrim(config('app.url', 'https://dawscape.fun'), '/');
        $slug = \Illuminate\Support\Str::slug($request->nombre) . '-' . uniqid();
        $url = $baseUrl . '/qr/' . $slug;

        \App\Models\Qr::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'fase_requerida' => $request->fase_requerida,
            'url' => $url
        ]);

        return back()->with('success', 'QR "' . $request->nombre . '" generado correctamente.');
    }

    public function eliminarQr($id)
    {
        $qr = \App\Models\Qr::find($id);
        if ($qr) {
            $qr->delete();
            return back()->with('success', 'QR eliminado del sistema.');
        }
        return back()->with('error', 'El QR no existe.');
    }

    public function escanearQR($identificador)
    {
        // Paso 1 - Autenticación
        if (!session()->has('email')) {
            return redirect('/')->with('error', 'Inicia sesión con tu equipo para ver esta pista.');
        }

        // Buscamos el QR en la BBDD por su URL final que termina en ese identificador
        $qr = \App\Models\Qr::where('url', 'LIKE', '%/qr/' . $identificador)->first();

        if (!$qr) {
            abort(404, 'Código QR no reconocido en el sistema.');
        }

        $partida = $this->obtenerPartidaActiva();

        if (!$partida) {
            return redirect('/')->with('error', 'Inicia sesión con tu equipo para ver esta pista.');
        }

        // Paso 2 - Validación de fase (Solo si el QR tiene una fase_requerida)
        if (!empty($qr->fase_requerida)) {
            if (trim(strtolower($partida->fase_actual)) !== trim(strtolower($qr->fase_requerida))) {
                $tiempo_transcurrido = $this->obtenerTiempoTranscurrido();
                return view('qr.error-fase', [
                    'mensaje' => '¡Eh, listillos! Aún no estáis en esta fase. Resolved la prueba actual primero.',
                    'tiempo_transcurrido' => $tiempo_transcurrido
                ]);
            }
        }

        if ($this->verificarTimeout()) return redirect('/final')->with('timeout', true);

        // Paso 3 - Éxito
        $tiempo_transcurrido = $this->obtenerTiempoTranscurrido();
        $titulo_prueba = $qr->nombre;
        
        // Si es el QR especial de la máquina de café (para la prueba 3)
        // Lo verificamos por el identificador o si contiene 'cafe' para más seguridad
        if (str_contains(strtolower($identificador), 'cafe') || str_contains(strtolower($qr->nombre), 'cafe')) {
            return view('qr.maquina-cafe', compact('tiempo_transcurrido', 'titulo_prueba', 'identificador'));
        }

        // Si es el QR especial de la máquina Oasis (para la prueba 4)
        if (str_contains(strtolower($identificador), 'snacks') || str_contains(strtolower($qr->nombre), 'oasis')) {
            return view('qr.maquina-snacks', compact('tiempo_transcurrido', 'titulo_prueba', 'identificador'));
        }

        // Si es el QR especial de las taquillas (para la prueba 5)
        if (str_contains(strtolower($identificador), 'taquilla') || str_contains(strtolower($qr->nombre), 'taquilla')) {
            return view('qr.taquillas', compact('tiempo_transcurrido', 'titulo_prueba', 'identificador'));
        }
        
        // Si no es especial, cargamos la vista de plantilla-pista genérica
        return view('qr.plantilla-pista', compact('tiempo_transcurrido', 'titulo_prueba', 'identificador'));
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
        // Autodescubrimiento de rutas de pruebas para el menú Admin
        $rutasPruebas = collect(\Illuminate\Support\Facades\Route::getRoutes()->getRoutes())
            ->filter(function ($route) {
                return str_starts_with($route->getName(), 'admin.prueba.');
            })
            ->map(function ($route) {
                // Extraer el nombre legible de la ruta (ej: admin.prueba.prueba1mario -> Prueba1mario)
                $nombreSlug = str_replace('admin.prueba.', '', $route->getName());
                // Formatear un poco el nombre
                $nombreLegible = ucfirst(preg_replace('/([a-zA-Z]+)(\d+)(.*)/', '$1 $2 $3', $nombreSlug));
                return [
                    'url' => url('/') . '/' . ltrim($route->uri(), '/'),
                    'nombre' => 'Test: ' . $nombreLegible
                ];
            })
            ->values();

        return view('admin', compact('partidas', 'top5', 'rutasPruebas'));
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