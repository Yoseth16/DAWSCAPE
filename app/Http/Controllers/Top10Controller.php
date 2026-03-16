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

        $terminadasFases = ['FINALIZADO', 'FINALIZADO_TIMEOUT'];

        // Solo equipos que empezaron juego y ya tienen fase
        $partidas = Partida::whereNotNull('fase_actual')
            ->whereNotNull('inicio_juego')
            ->get();

        // 1) Bloquear posición "para siempre" a equipos que acaban de finalizar/timeout y aún no tienen posición
        //    (persistimos en BD para que sobreviva refrescos / reinicios).
        $ocupadas = $partidas
            ->whereNotNull('ranking_posicion_bloqueada')
            ->pluck('ranking_posicion_bloqueada')
            ->filter()
            ->map(fn ($v) => (int) $v)
            ->values()
            ->all();
        $ocupadasSet = array_fill_keys($ocupadas, true);

        // Ranking "teórico" (sin bloqueo) para decidir dónde fijar a un equipo cuando entra en estado terminado.
        $rankingTeorico = $partidas->map(function ($partida) use ($terminadasFases) {
            $penalizacion = (int)($partida->penalizacion_segundos ?? 0);
            $esTerminada = in_array($partida->fase_actual, $terminadasFases, true);

            // Live time si está jugando; si ya terminó/timeout, usamos tiempo_segundos (que debería ser el final)
            if (!$esTerminada && $partida->inicio_juego) {
                $segs = abs(now()->diffInSeconds(Carbon::parse($partida->inicio_juego))) + $penalizacion;
            } else {
                $segs = (int)($partida->tiempo_segundos ?? 0) + $penalizacion;
            }

            $peso = $this->fases_orden[$partida->fase_actual]['peso'] ?? 0;

            return [
                'id' => $partida->id,
                'peso' => $peso,
                'tiempo' => $segs,
            ];
        })->sort(function ($a, $b) {
            // Fase mayor primero; si empatan, menor tiempo primero
            if ($a['peso'] !== $b['peso']) return $b['peso'] <=> $a['peso'];
            return $a['tiempo'] <=> $b['tiempo'];
        })->values();

        $posicionTeoricaPorId = [];
        foreach ($rankingTeorico as $idx => $row) {
            $posicionTeoricaPorId[$row['id']] = $idx + 1;
        }

        $recienTerminadas = $partidas->filter(function ($p) use ($terminadasFases) {
            return in_array($p->fase_actual, $terminadasFases, true) && $p->ranking_posicion_bloqueada === null;
        });

        foreach ($recienTerminadas as $p) {
            $deseada = (int)($posicionTeoricaPorId[$p->id] ?? 9999);
            $slot = max(1, $deseada);
            while (isset($ocupadasSet[$slot])) $slot++;

            $p->ranking_posicion_bloqueada = $slot;
            $p->save();
            $ocupadasSet[$slot] = true;
        }

        // Recargar para incluir ranking_posicion_bloqueada recién asignada
        $partidas = Partida::whereNotNull('fase_actual')
            ->whereNotNull('inicio_juego')
            ->get();

        $todasPartidas = $partidas->map(function ($partida) use ($terminadasFases) {
            // Calculate current live time in seconds (including penalties)
            $segsLive = 0;
            $penalizacion = (int)($partida->penalizacion_segundos ?? 0);

            $esTerminada = in_array($partida->fase_actual, $terminadasFases, true);
            if (!$esTerminada && $partida->inicio_juego) {
                $segsLive = abs(now()->diffInSeconds(Carbon::parse($partida->inicio_juego))) + $penalizacion;
            } else {
                $segsLive = (int)($partida->tiempo_segundos ?? 0) + $penalizacion;
            }

            // Assign a weight based on the phase for sorting
            $peso = $this->fases_orden[$partida->fase_actual]['peso'] ?? 0;
            $nombre_prueba = $this->fases_orden[$partida->fase_actual]['nombre'] ?? $partida->fase_actual;

            // Calculate progress percentage (max weight is 6 before finish)
            $progreso = min(100, round(($peso / 6) * 100));

            $estado = match ($partida->fase_actual) {
                'FINALIZADO' => 'finalizado',
                'FINALIZADO_TIMEOUT' => 'timeout',
                default => 'jugando',
            };

            return [
                'id' => $partida->id,
                'nombre_equipo' => $partida->nombre_equipo,
                'fase_actual' => $partida->fase_actual,
                'nombre_prueba' => $nombre_prueba,
                'fase_numero' => min(6, max(0, (int)$peso)),
                'progreso_porcentaje' => $progreso,
                'tiempo_segundos' => $segsLive,
                'tiempo_formateado' => floor($segsLive / 60) . 'm ' . str_pad($segsLive % 60, 2, '0', STR_PAD_LEFT) . 's',
                'peso' => $peso,
                'estado' => $estado,
                'hora_fin' => $partida->updated_at ? $partida->updated_at->format('H:i') : '--:--',
                'color_hex' => $partida->color_hex ?? '#3b82f6',
                'icono' => $partida->icono ?? 'ph-user-focus',
                'logros' => $partida->logros ?? [],
                'ranking_posicion_bloqueada' => $partida->ranking_posicion_bloqueada ? (int)$partida->ranking_posicion_bloqueada : null,
            ];
        });

        // 2) Construir el Top10 mixto con "slots" bloqueados
        $bloqueados = $todasPartidas
            ->filter(fn ($p) => $p['ranking_posicion_bloqueada'] !== null)
            ->keyBy('ranking_posicion_bloqueada');

        $vivos = $todasPartidas
            ->filter(fn ($p) => $p['ranking_posicion_bloqueada'] === null)
            ->sort(function ($a, $b) {
                // Fase mayor primero; si empatan, menor tiempo primero
                if ($a['peso'] !== $b['peso']) return $b['peso'] <=> $a['peso'];
                return $a['tiempo_segundos'] <=> $b['tiempo_segundos'];
            })
            ->values();

        $top10 = collect();
        $vivosIdx = 0;
        for ($rank = 1; $rank <= 10; $rank++) {
            if ($bloqueados->has($rank)) {
                $item = $bloqueados->get($rank);
                $item['rank'] = $rank;
                $top10->push($item);
                continue;
            }
            if (!isset($vivos[$vivosIdx])) break;
            $item = $vivos[$vivosIdx];
            $item['rank'] = $rank;
            $top10->push($item);
            $vivosIdx++;
        }

        // Para panel de finalizados (si se usa): solo los que estén dentro del Top10 y estén terminados/timeout
        $finalizados = $top10->filter(fn ($p) => in_array($p['estado'], ['finalizado', 'timeout'], true))->values();

        // Calculate gap for podium (2nd vs 1st, 3rd vs 1st)
        if ($top10->count() > 0) {
            $firstTime = $top10[0]['tiempo_segundos'];
            $top10 = $top10->values()->transform(function ($item, $key) use ($firstTime) {
                if ($key > 0 && $key < 3) {
                    $diff = $item['tiempo_segundos'] - $firstTime;
                    $item['gap'] = '+' . floor($diff / 60) . 'm ' . str_pad($diff % 60, 2, '0', STR_PAD_LEFT) . 's sobre el 1º';
                } else {
                    $item['gap'] = null;
                }
                return $item;
            });
        }

        return response()->json([
            'ticker' => $ticker,
            'top10' => $top10,
            'finalizados' => $finalizados,
            'timestamp' => now()->timestamp // Useful for triggering animations on client if needed
        ]);
    }

}
