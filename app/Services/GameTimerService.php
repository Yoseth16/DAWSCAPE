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

        $tiempoTotal = (int) abs($ahora->diffInSeconds($inicio));
        
        $segundosDesdePausa = 0;
        if ($partida->estado === 'pausado' && $partida->ultima_pausa_at) {
            $segundosDesdePausa = (int) abs($ahora->diffInSeconds(Carbon::parse($partida->ultima_pausa_at)));
        }

        $elapsed = $tiempoTotal - ($partida->tiempo_pausado_segundos ?? 0) - $segundosDesdePausa;
        return max(0, $elapsed) + ($partida->penalizacion_segundos ?? 0);
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
            return $partida->fase_actual === config('fases.fases.timeout.db');
        }

        $transcurrido = $this->obtenerTiempoTranscurrido();
        if ($transcurrido >= self::MAX_TIEMPO_MINUTOS * 60) {
            $partida->update([
                'tiempo_segundos' => $transcurrido,
                'fase_actual' => config('fases.fases.timeout.db')
            ]);
            return true;
        }
        
        return false;
    }

    /**
     * Avanza de fase y registra el hito en la base de datos automáticamente.
     */
    public function avanzarFase(Partida $partida, string $siguienteFase, string $mensajeHito): void
    {
        $partida->update([
            'fase_actual' => $siguienteFase,
        ]);

        \App\Models\HitoFase::create([
            'partida_id' => $partida->id,
            'fase' => $mensajeHito,
            'segundos_transcurridos' => $this->obtenerTiempoTranscurrido(),
            'alcanzado_at' => Carbon::now()
        ]);
    }
}
