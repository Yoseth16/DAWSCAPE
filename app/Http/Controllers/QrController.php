<?php

namespace App\Http\Controllers;

use App\Models\Partida;
use Carbon\Carbon;
use App\Services\GameTimerService;
use App\Http\Requests\GuardarQrRequest;

class QrController extends Controller
{
    protected $timerService;

    public function __construct(GameTimerService $timerService)
    {
        $this->timerService = $timerService;
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
}
