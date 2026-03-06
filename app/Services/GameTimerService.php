<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Partida;

class GameTimerService
{
    const MAX_TIEMPO_MINUTOS = 90;

    /**
     * Obtiene la partida activa actual en base a la sesión.
     */
    public function obtenerPartidaActiva()
    {
        if (!session()->has('email')) {
            return null;
        }
        return Partida::where('email', session('email'))->first();
    }

    /**
     * Calcula los segundos exactos transcurridos desde que el equipo
     * inició el escape room usando Base de Datos (a prueba de manipulación web).
     */
    public function obtenerTiempoTranscurrido(): int
    {
        if (!session()->has('email')) {
            return 0;
        }

        $partida = $this->obtenerPartidaActiva();

        if (!$partida || !$partida->inicio_juego) {
            return 0;
        }

        $inicio = Carbon::parse($partida->inicio_juego);
        $ahora = Carbon::now();

        return (int) abs($ahora->diffInSeconds($inicio));
    }

    /**
     * Verifica si el tiempo límite ha sido superado.
     * Si es superado, marca la finalización de la partida en Base de Datos.
     */
    public function verificarTimeout(): bool
    {
        if (!session()->has('email')) {
            return false;
        }

        $partida = $this->obtenerPartidaActiva();
        if (!$partida || !$partida->inicio_juego) {
            return false;
        }
        
        if (str_starts_with($partida->fase_actual, 'FINALIZADO')) {
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
}
