<?php

namespace App\Services;

use App\Models\Partida;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AdminStatsService
{
    /**
     * Obtiene el listado de partidas ordenado.
     * @return Collection
     */
    public function getPartidasOrdered()
    {
        return Partida::orderByRaw("
            CASE 
                WHEN fase_actual = 'FINALIZADO' THEN 0 
                WHEN fase_actual = 'FINALIZADO_TIMEOUT' THEN 1
                ELSE 2 
            END")
            ->orderBy('tiempo_segundos', 'asc')
            ->get();
    }

    /**
     * Obtiene el Top 5 de equipos finalizados más rápidos.
     * @return Collection
     */
    public function getTop5()
    {
        return Partida::where('fase_actual', 'FINALIZADO')
            ->orderBy('tiempo_segundos', 'asc')
            ->limit(5)
            ->get();
    }

    /**
     * Calcula los KPIs optimizados con consultas Agregadas directamente en BD.
     * @return array
     */
    public function getMetrics()
    {
        // En lugar de traer todos los registros a memoria para contarlos (O(n)),
        // delegamos el conteo al motor de base de datos (O(1) o indexado).
        $metrics = Partida::select(
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(CASE WHEN fase_actual = "FINALIZADO" THEN 1 ELSE 0 END) as finalizados'),
            DB::raw('SUM(CASE WHEN fase_actual = "FINALIZADO_TIMEOUT" THEN 1 ELSE 0 END) as timeout'),
            DB::raw('AVG(CASE WHEN fase_actual = "FINALIZADO" THEN tiempo_segundos ELSE NULL END) as tiempo_medio')
        )->first();

        $totalEquipos = (int) $metrics->total;
        $equiposFinalizados = (int) $metrics->finalizados;
        $equiposTimeout = (int) $metrics->timeout;
        $equiposActivos = $totalEquipos - $equiposFinalizados - $equiposTimeout;
        $tiempoMedioSegundos = (float) $metrics->tiempo_medio;

        return [
            'total' => $totalEquipos,
            'finalizados' => $equiposFinalizados,
            'timeout' => $equiposTimeout,
            'activos' => $equiposActivos,
            'promedio' => $tiempoMedioSegundos > 0 ? floor($tiempoMedioSegundos / 60) . 'm' : '--',
            'promedio_sec' => $tiempoMedioSegundos
        ];
    }

    /**
     * Obtiene los datos del gráfico de la tarta (Chart.js)
     * Optimizando con un GROUP BY puro en SQL.
     * @return array
     */
    public function getChartData()
    {
        $fases = Partida::select('fase_actual', DB::raw('count(*) as total'))
            ->groupBy('fase_actual')
            ->pluck('total', 'fase_actual');

        return [
            'labels' => $fases->keys()->toArray(),
            'data' => $fases->values()->toArray()
        ];
    }
}
