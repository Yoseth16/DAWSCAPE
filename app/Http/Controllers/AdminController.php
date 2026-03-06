<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Partida;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    public function adminPanel()
    {
        // Top 5 finalizados (podio)
        $top5 = Partida::where('fase_actual', 'FINALIZADO')
            ->orderBy('tiempo_segundos', 'asc')
            ->limit(5)
            ->get();

        // Orden de la tabla: 1. Finalizados 2. Timeouts 3. Jugando
        $partidas = Partida::orderByRaw("
            CASE 
                WHEN fase_actual = 'FINALIZADO' THEN 0 
                WHEN fase_actual = 'FINALIZADO_TIMEOUT' THEN 1
                ELSE 2 
            END")
            ->orderBy('tiempo_segundos', 'asc')
            ->get();
            
        // KPI METRICS
        $totalEquipos = $partidas->count();
        $equiposFinalizados = $partidas->where('fase_actual', 'FINALIZADO')->count();
        $equiposTimeout = $partidas->where('fase_actual', 'FINALIZADO_TIMEOUT')->count();
        $equiposActivos = $totalEquipos - $equiposFinalizados - $equiposTimeout;
        
        $tiempoMedioSegundos = 0;
        if ($equiposFinalizados > 0) {
            $tiempoMedioSegundos = $partidas->where('fase_actual', 'FINALIZADO')->avg('tiempo_segundos');
        }

        // CHART DATA (Distribución de Fases)
        $fasesGroup = $partidas->groupBy('fase_actual')->map->count();
        $chartData = [
            'labels' => $fasesGroup->keys()->toArray(),
            'data' => $fasesGroup->values()->toArray()
        ];

        // Autodescubrimiento de rutas de pruebas para el menú Admin
        $rutasPruebas = collect(\Illuminate\Support\Facades\Route::getRoutes()->getRoutes())
            ->filter(function ($route) {
                return str_starts_with($route->getName(), 'admin.prueba.');
            })
            ->map(function ($route) {
                $nombreSlug = str_replace('admin.prueba.', '', $route->getName());
                $nombreLegible = ucfirst(preg_replace('/([a-zA-Z]+)(\d+)(.*)/', '$1 $2 $3', $nombreSlug));
                return [
                    'url' => url('/') . '/' . ltrim($route->uri(), '/'),
                    'nombre' => 'Test: ' . $nombreLegible
                ];
            })
            ->values();

        return view('admin', compact(
            'partidas', 'top5', 'rutasPruebas', 
            'totalEquipos', 'equiposFinalizados', 'equiposActivos', 'equiposTimeout', 'tiempoMedioSegundos', 'chartData'
        ));
    }

    public function eliminarEquipo($uuid)
    {
        $partida = Partida::where('uuid', $uuid)->first();
        if ($partida) {
            $partida->delete();
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
    public function apiStats()
    {
        $partidas = Partida::orderByRaw("
            CASE 
                WHEN fase_actual = 'FINALIZADO' THEN 0 
                WHEN fase_actual = 'FINALIZADO_TIMEOUT' THEN 1
                ELSE 2 
            END")
            ->orderBy('tiempo_segundos', 'asc')
            ->get();

        $totalEquipos = $partidas->count();
        $equiposFinalizados = $partidas->where('fase_actual', 'FINALIZADO')->count();
        $equiposTimeout = $partidas->where('fase_actual', 'FINALIZADO_TIMEOUT')->count();
        $equiposActivos = $totalEquipos - $equiposFinalizados - $equiposTimeout;
        
        $tiempoMedioSegundos = 0;
        if ($equiposFinalizados > 0) {
            $tiempoMedioSegundos = $partidas->where('fase_actual', 'FINALIZADO')->avg('tiempo_segundos');
        }

        $fasesGroup = $partidas->groupBy('fase_actual')->map->count();

        // Renderizamos solo la tabla para enviarla como HTML parcial (opcional pero más rápido para el front)
        // O enviamos JSON puro y reconstruimos en JS. Para esta app, JSON es más "Serie A".
        
        return response()->json([
            'metrics' => [
                'total' => $totalEquipos,
                'finalizados' => $equiposFinalizados,
                'activos' => $equiposActivos,
                'promedio' => $tiempoMedioSegundos > 0 ? floor($tiempoMedioSegundos / 60) . 'm' : '--'
            ],
            'chart' => [
                'labels' => $fasesGroup->keys()->toArray(),
                'data' => $fasesGroup->values()->toArray()
            ],
            'partidas' => $partidas->map(function($p) {
                $segsLive = 0;
                if ($p->fase_actual !== 'FINALIZADO' && $p->fase_actual !== 'FINALIZADO_TIMEOUT' && $p->inicio_juego) {
                    $segsLive = abs(now()->diffInSeconds(\Carbon\Carbon::parse($p->inicio_juego)));
                }
                return [
                    'uuid' => $p->uuid,
                    'id' => str_pad($p->id, 3, '0', STR_PAD_LEFT),
                    'nombre_equipo' => $p->nombre_equipo,
                    'email' => $p->email,
                    'clase' => $p->clase,
                    'fase_actual' => $p->fase_actual,
                    'tiempo_segundos' => (int)$p->tiempo_segundos,
                    'tiempo_live' => $segsLive,
                    'avatar' => strtoupper(substr($p->nombre_equipo, 0, 1))
                ];
            })
        ]);
    }
}
