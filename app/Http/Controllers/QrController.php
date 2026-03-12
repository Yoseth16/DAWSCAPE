<?php

namespace App\Http\Controllers;

use App\Models\Partida;
use Carbon\Carbon;
use App\Services\GameTimerService;
use App\Http\Requests\GuardarQrRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class QrController extends Controller
{
    protected $timerService;

    public function __construct(GameTimerService $timerService)
    {
        $this->timerService = $timerService;
    }

    // =====================================================
    // VALIDACIÓN DE CÓDIGOS QR (texto plano) Pruebas 3-5
    // Endpoint: POST /validar-codigo-qr (auth + throttle)
    // =====================================================
    public function validar(Request $request): JsonResponse
    {
        $data = $request->validate([
            'codigo' => ['required', 'string'],
            'prueba' => ['required', 'integer', 'between:3,5'],
        ]);

        // Normalizar texto QR: quitar invisibles/espacios y forzar mayúsculas
        $codigoRaw = (string) $data['codigo'];
        $codigo = preg_replace('/[[:^print:]]/u', '', $codigoRaw) ?? $codigoRaw; // quita \r \n etc.
        $codigo = strtoupper(trim($codigo));
        $prueba = (int) $data['prueba'];

        // Anti-salto: alinear con las claves reales de progreso del JuegoController
        // (prueba2yoseth_superada, prueba3angel_superada, etc.)
        $keysSuperada = [
            1 => 'prueba1mario_superada',
            2 => 'prueba2yoseth_superada',
            3 => 'prueba3angel_superada',
            4 => 'prueba4arturo_superada',
            5 => 'prueba5eloy_superada',
        ];

        $keyAnteriorSuperada = $keysSuperada[$prueba - 1] ?? null;
        if ($keyAnteriorSuperada && !session()->get($keyAnteriorSuperada, false)) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes acceso a esta prueba todavía. Completa la prueba anterior.',
            ], 403);
        }

        // Si ya se validó este QR antes, devolvemos éxito idempotente
        $keyActualCompletada = 'prueba_' . $prueba . '_completada';
        if (session()->get($keyActualCompletada, false)) {
            return response()->json([
                'success' => true,
                'redirect_url' => $this->redirectUrlParaPrueba($prueba),
            ]);
        }

        // Códigos secretos válidos (dejamos la estructura por si quieres volver
        // a activar la comprobación exacta más adelante).
        // De momento NO bloqueamos por el valor concreto del código: el QR
        // actúa como disparador físico siempre que la prueba anterior esté superada.
        $codigosValidos = [
            3 => 'ACCESO_FASE_3',
            4 => 'ACCESO_FASE_4',
            5 => 'ACCESO_FASE_5',
        ];

        // Marcar validación del QR en sesión (sin romper el resto del juego)
        // - `prueba_X_completada`: usado por este validador QR
        // - además, por compatibilidad, marcamos "superada" de la prueba anterior si corresponde
        session([$keyActualCompletada => true]);

        // (Opcional) Persistencia en BBDD si existe un modelo/tabla de progreso:
        // - Puedes integrarlo aquí (GameState/Progress) usando auth()->id()

        return response()->json([
            'success' => true,
            'redirect_url' => $this->redirectUrlParaPrueba($prueba),
        ]);
    }

    /**
     * Determina a qué ruta debe ir el alumno tras validar el QR
     * de cada prueba.
     */
    private function redirectUrlParaPrueba(int $prueba): string
    {
        return match ($prueba) {
            3 => route('juego.maquina-cafe'),
            4 => route('juego.maquina-snacks'),
            5 => route('juego.taquillas'),
            default => route('prueba' . ($prueba + 1)),
        };
    }




    // =====================================================
    // SISTEMA DE QRs (MÓDULO VERSIÓN BBDD DINÁMICA)
    // =====================================================

    public function mostrarGestor()
    {
        $qrs = \App\Models\Qr::orderBy('created_at', 'desc')->get();
        return view('admin.gestionar-qrs', compact('qrs'));
    }

    public function guardarQr(GuardarQrRequest $request)
    {
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

    public function editarQr(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $qr = \App\Models\Qr::find($id);
        if ($qr) {
            $qr->update(['nombre' => $request->nombre]);
            return back()->with('success', 'Nombre del QR actualizado correctamente.');
        }
        return back()->with('error', 'El QR no existe.');
    }

    public function escanearQR($identificador)
    {
        // ── GUARD: solo equipos con sesión activa pueden acceder (anti-trampas) ──
        if (!session()->has('email')) {
            return redirect('/')
                ->with('error', '⚠️ Acceso denegado. Escanea el QR con el mismo navegador donde empezasteis a jugar.');
        }

        // Buscamos el QR en la BBDD
        $qr = \App\Models\Qr::where('url', 'LIKE', '%/qr/' . $identificador)->first();

        if (!$qr) {
            abort(404, 'Código QR no reconocido en el sistema.');
        }

        // Recuperamos la partida — tiempo calculado en el servidor (no manipulable)
        $partida = $this->timerService->obtenerPartidaActiva();

        if (!$partida) {
            return redirect('/')->with('error', 'No se encontró una partida activa para este equipo.');
        }

        // ── Validamos que estén en la fase correcta ──
        if (!empty($qr->fase_requerida)) {
            if (trim(strtolower($partida->fase_actual)) !== trim(strtolower($qr->fase_requerida))) {
                $tiempo_transcurrido = $this->timerService->obtenerTiempoTranscurrido();
                return view('qr.error-fase', [
                    'mensaje' => '¡Eh, listillos! Aún no estáis en esta fase. Resolved la prueba actual primero.',
                    'tiempo_transcurrido' => $tiempo_transcurrido
                ]);
            }
        }

        // ── Si el tiempo expiró, cerramos la prueba ──
        if ($this->timerService->verificarTimeout()) {
            return redirect('/final')->with('timeout', true);
        }

        // ── ÉXITO: renderizar el reto QR (igual que el admin tester, pero con tiempo real) ──
        $tiempo_transcurrido = $this->timerService->obtenerTiempoTranscurrido();
        $titulo_prueba = $qr->nombre;

        $fase = strtolower(trim($qr->fase_requerida ?? ''));
        $identificadorNormalizado = strtolower($identificador);
        $nombreQrNormalizado = strtolower($qr->nombre);

        $vistaEspecial = match(true) {
            // Por fase_requerida (el campo más fiable)
            str_contains($fase, 'angel')  || str_contains($fase, 'prueba 3') => 'qr.maquina-cafe',
            str_contains($fase, 'arturo') || str_contains($fase, 'prueba 4') => 'qr.maquina-snacks',
            str_contains($fase, 'eloy')   || str_contains($fase, 'prueba 5') => 'qr.taquillas',
            // Fallback por nombre/slug
            str_contains($identificadorNormalizado, 'cafe')     || str_contains($nombreQrNormalizado, 'cafe')    => 'qr.maquina-cafe',
            str_contains($identificadorNormalizado, 'snacks')   || str_contains($nombreQrNormalizado, 'oasis')   => 'qr.maquina-snacks',
            str_contains($identificadorNormalizado, 'taquilla') || str_contains($nombreQrNormalizado, 'taquilla') => 'qr.taquillas',
            default => 'qr.maquina-cafe'
        };

        return view($vistaEspecial, compact('tiempo_transcurrido', 'titulo_prueba', 'identificador'));
    }

    // =====================================================
    // SISTEMA DE QRS DEL CÓDICE OCULTO (Prueba 7 Dinámica)
    // URLs fijas: /qr/codice-0 ... /qr/codice-9
    // =====================================================

    /**
     * Base de datos de acértijos por dígito.
     * Ref: JuegoController::DIGITOS_CODICE (mantener sincronizado).
     */
    private const ACERTIJOS_CODICE = [
        0 => 'No valgo absolutamente nada si me pones a la izquierda, pero multiplico tu riqueza si me pones a la derecha. Fui el último dígito en inventarse, nacido en la India para representar la nada y el vacío absoluto.',
        1 => 'Soy el único número cuyo cuadrado, cubo y raíz son exactamente idénticos a mí mismo. En la tabla periódica, corono al elemento más ligero y abundante de todo el universo.',
        2 => 'En informática, soy la base fundamental de todo el lenguaje que entienden las máquinas. Soy la excepción a la regla: el único número par que tiene el honor de ser primo.',
        3 => 'Newton necesitó exactamente este número de leyes para explicar el movimiento del universo. Si quieres definir un plano geométrico perfecto en el espacio sin que cojee, me necesitas estrictamente.',
        4 => 'Lato sin descanso en tu pecho gracias a mis cavidades anatómicas. En la genética, soy el total de letras químicas (A, C, G, T) que escriben el libro de la vida.',
        5 => 'En la geometría clásica, represento el total de sólidos platónicos perfectos que existen. En el emblema de los Juegos Olímpicos, doy forma a los anillos que representan las partes habitadas del mundo.',
        6 => 'Soy el primer número perfecto, la suma de mis divisores exactos (1, 2 y 3) te dará mi valor. En química, soy el cimiento de la vida, el número atómico del carbono.',
        7 => 'Químicamente hablando, soy la neutralidad perfecta, el pH del agua pura. Anatómicamente, soy el número de vértebras cervicales que comparte un humano con una jirafa.',
        8 => 'En el mundo digital, formo la unidad básica de memoria (el byte) cuando agrupo a mis pequeños soldados (los bits). Soy el número de planetas reconocidos oficialmente en nuestro sistema solar.',
        9 => 'Tengo una maldición: multiplícame por el entero que quieras; si sumas las cifras del resultado, siempre volverás a mí. Soy el total de círculos del Infierno de Dante.',
    ];

    public function escanearCodicedigito($digito)
    {
        // Forzar entero y validar rango 0-9
        $digito = (int) $digito;
        if ($digito < 0 || $digito > 9) {
            abort(404, 'Código QR no válido.');
        }

        // Guard: requiere sesión activa de equipo
        if (!session()->has('email')) {
            return redirect('/')
                ->with('error', '⚠️ Acceso denegado. Escanea el QR con el mismo navegador donde empezasteis a jugar.');
        }

        // Verificar que el equipo está en la fase correcta
        $partida = $this->timerService->obtenerPartidaActiva();

        if (!$partida) {
            return redirect('/')->with('error', 'No se encontró una partida activa para este equipo.');
        }

        $faseActual = strtolower(trim($partida->fase_actual));
        if (!str_contains($faseActual, 'prueba 7') && !str_contains($faseActual, 'dinamica')) {
            $tiempo_transcurrido = $this->timerService->obtenerTiempoTranscurrido();
            return view('qr.error-fase', [
                'mensaje' => '¡Eh, listillos! Aún no estáis en la Prueba 7. Completad las pruebas anteriores primero.',
                'tiempo_transcurrido' => $tiempo_transcurrido
            ]);
        }

        // Timeout check
        if ($this->timerService->verificarTimeout()) {
            return redirect('/final')->with('timeout', true);
        }

        $acertijo = self::ACERTIJOS_CODICE[$digito];

        return view('qr.codice-digito', compact('digito', 'acertijo'));
    }
}
