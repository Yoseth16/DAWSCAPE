<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Partida;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use App\Events\Top10Updated;

class Top10Controller extends Controller
{
    // Define the sequence of phases and their friendly names for the progress bar
    private $fases_orden = [
        'INICIO' => ['nombre' => 'En el Inicio', 'peso' => 0],
        'Prueba 1 Mario' => ['nombre' => 'Prueba 1 — Criptografía', 'peso' => 1],
        'Prueba 2 Yoseth' => ['nombre' => 'Prueba 2 — Servidor Seguro', 'peso' => 2],
        'Prueba 3 Angel' => ['nombre' => 'Prueba 3 — Máquina de Café', 'peso' => 3],
        'Prueba 4 Arturo' => ['nombre' => 'Prueba 4 — Oasis Snacks', 'peso' => 4],
        'Prueba 5 Eloy' => ['nombre' => 'Prueba 5 — Protocolo Espejo', 'peso' => 5],
        'Prueba 6 Migue' => ['nombre' => 'Prueba 6 — Descifrado Final', 'peso' => 6],
        'FINALIZADO' => ['nombre' => '¡Completado!', 'peso' => 7],
        'FINALIZADO_TIMEOUT' => ['nombre' => '¡Tiempo Agotado!', 'peso' => 7],
    ];

    public function index()
    {
        return view('top10');
    }

    public function apiData()
    {
        // Get ticker message from cache
        $ticker = Cache::get('top10_ticker', '¡Bienvenidos al Escape Room DAWSCAPE! Que gane el mejor equipo.');

        // Get all teams
        $todasPartidas = Partida::all()->map(function ($partida) {
            // Calculate current live time in seconds
            $segsLive = 0;
            if ($partida->fase_actual !== 'FINALIZADO' && $partida->fase_actual !== 'FINALIZADO_TIMEOUT' && $partida->inicio_juego) {
                $segsLive = abs(now()->diffInSeconds(Carbon::parse($partida->inicio_juego)));
            } else {
                $segsLive = (int)$partida->tiempo_segundos;
            }

            // Assign a weight based on the phase for sorting
            $peso = $this->fases_orden[$partida->fase_actual]['peso'] ?? 0;
            $nombre_prueba = $this->fases_orden[$partida->fase_actual]['nombre'] ?? $partida->fase_actual;
            
            // Calculate progress percentage (max weight is 6 before finish)
            $progreso = min(100, round(($peso / 6) * 100));

            return [
                'id' => $partida->id,
                'nombre_equipo' => $partida->nombre_equipo,
                'fase_actual' => $partida->fase_actual,
                'nombre_prueba' => $nombre_prueba,
                'progreso_porcentaje' => $progreso,
                'tiempo_live' => $segsLive,
                'tiempo_formateado' => floor($segsLive / 60) . 'm ' . str_pad($segsLive % 60, 2, '0', STR_PAD_LEFT) . 's',
                'peso' => $peso,
                'terminado' => ($partida->fase_actual === 'FINALIZADO'),
                'abandonado' => ($partida->fase_actual === 'FINALIZADO_TIMEOUT'),
                'hora_fin' => $partida->updated_at ? $partida->updated_at->format('H:i') : '--:--',
                'color_hex' => $partida->color_hex ?? '#3b82f6',
                'icono' => $partida->icono ?? 'ph-user-focus',
                'logros' => $partida->logros ?? []
            ];
        });

        // Split into finished and active
        $finalizados = $todasPartidas->filter(function ($p) {
            return $p['terminado'];
        })->sortBy('tiempo_live')->values(); // Sort by shortest time

        $activos = $todasPartidas->filter(function ($p) {
            return !$p['terminado'];
        })->sort(function ($a, $b) {
            // First by phase weight (descending)
            if ($a['peso'] !== $b['peso']) {
                return $b['peso'] <=> $a['peso'];
            }
            // If same phase, the one with LESS time is winning (ascending)
            return $a['tiempo_live'] <=> $b['tiempo_live'];
        })->values();

        // Get top 10 actives
        $top10Activos = $activos->take(10);
        
        // Calculate gap for podium (2nd vs 1st, 3rd vs 1st)
        if ($top10Activos->count() > 0) {
            $firstTime = $top10Activos[0]['tiempo_live'];
            $top10Activos->transform(function ($item, $key) use ($firstTime) {
                if ($key > 0 && $key < 3) {
                    $diff = $item['tiempo_live'] - $firstTime;
                    $item['gap'] = '+' . floor($diff / 60) . 'm ' . str_pad($diff % 60, 2, '0', STR_PAD_LEFT) . 's sobre el 1º';
                } else {
                    $item['gap'] = null;
                }
                return $item;
            });
        }

        return response()->json([
            'ticker' => $ticker,
            'top10' => $top10Activos,
            'finalizados' => $finalizados,
            'timestamp' => now()->timestamp // Useful for triggering animations on client if needed
        ]);
    }

}
