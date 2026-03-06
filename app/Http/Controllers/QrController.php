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
}
