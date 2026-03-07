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

    public function gestionarQrs()
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
        // Paso 1 - Autenticación
        if (!session()->has('email')) {
            return redirect('/')->with('error', 'Inicia sesión con tu equipo para ver esta pista.');
        }

        // Buscamos el QR en la BBDD por su URL final que termina en ese identificador
        $qr = \App\Models\Qr::where('url', 'LIKE', '%/qr/' . $identificador)->first();

        if (!$qr) {
            abort(404, 'Código QR no reconocido en el sistema.');
        }

        $partida = $this->timerService->obtenerPartidaActiva();

        if (!$partida) {
            return redirect('/')->with('error', 'Inicia sesión con tu equipo para ver esta pista.');
        }

        // Paso 2 - Validación de fase (Solo si el QR tiene una fase_requerida)
        if (!empty($qr->fase_requerida)) {
            if (trim(strtolower($partida->fase_actual)) !== trim(strtolower($qr->fase_requerida))) {
                $tiempo_transcurrido = $this->timerService->obtenerTiempoTranscurrido();
                return view('qr.error-fase', [
                    'mensaje' => '¡Eh, listillos! Aún no estáis en esta fase. Resolved la prueba actual primero.',
                    'tiempo_transcurrido' => $tiempo_transcurrido
                ]);
            }
        }

        if ($this->timerService->verificarTimeout()) return redirect('/final')->with('timeout', true);

        // Paso 3 - Éxito
        $tiempo_transcurrido = $this->timerService->obtenerTiempoTranscurrido();
        $titulo_prueba = $qr->nombre;
        
        // Si el identificador mapea a una vista especial (Open/Closed Principle - OCP)
        $identificadorNormalizado = strtolower($identificador);
        $nombreQrNormalizado = strtolower($qr->nombre);
        
        $vistaEspecial = match(true) {
            str_contains($identificadorNormalizado, 'cafe') || str_contains($nombreQrNormalizado, 'cafe') 
                => 'qr.maquina-cafe',
            str_contains($identificadorNormalizado, 'snacks') || str_contains($nombreQrNormalizado, 'oasis') 
                => 'qr.maquina-snacks',
            str_contains($identificadorNormalizado, 'taquilla') || str_contains($nombreQrNormalizado, 'taquilla') 
                => 'qr.taquillas',
            default => 'qr.plantilla-pista'
        };

        return view($vistaEspecial, compact('tiempo_transcurrido', 'titulo_prueba', 'identificador'));
    }
}
