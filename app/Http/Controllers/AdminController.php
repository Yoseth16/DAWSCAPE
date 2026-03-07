<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Partida;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    protected $statsService;

    public function __construct(\App\Services\AdminStatsService $statsService)
    {
        $this->statsService = $statsService;
    }

    public function adminPanel()
    {
        // 1. Obtener Top 5
        $top5 = $this->statsService->getTop5();

        // 2. Obtener la lista general
        $partidas = $this->statsService->getPartidasOrdered();
            
        // 3. Obtener Métricas (Ahora optimizadas desde BBDD)
        $metrics = $this->statsService->getMetrics();
        $totalEquipos =      $metrics['total'];
        $equiposFinalizados =$metrics['finalizados'];
        $equiposActivos =    $metrics['activos'];
        $equiposTimeout =    $metrics['timeout'];
        $promedioSegundos =  $metrics['promedio_sec'];

        // 4. Obtener Chart Data
        $chartData = $this->statsService->getChartData();

        // 5. Autodescubrimiento de rutas de pruebas para el menú Admin
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
            'totalEquipos', 'equiposFinalizados', 'equiposActivos', 'equiposTimeout', 'chartData'
        ))->with('tiempoMedioSegundos', $promedioSegundos);
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
        $partidas = $this->statsService->getPartidasOrdered();
        $metrics = $this->statsService->getMetrics();
        $chartData = $this->statsService->getChartData();

        // Limpieza drástica del método, delegando a la capa Controller solo el Response JSON.
        return response()->json([
            'metrics' => [
                'total' =>       $metrics['total'],
                'finalizados' => $metrics['finalizados'],
                'activos' =>     $metrics['activos'],
                'promedio' =>    $metrics['promedio']
            ],
            'chart' => $chartData,
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
