<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\Concerns\CreatesGameData;
use Tests\TestCase;

class Top10ApiTest extends TestCase
{
    use CreatesGameData;
    use RefreshDatabase;

    public function test_top10_api_orders_live_teams_and_locks_finished_positions(): void
    {
        Cache::put('top10_ticker', 'Ticker de pruebas');

        $finalizadoBloqueado = $this->createPartida([
            'nombre_equipo' => 'Finalizado Bloqueado',
            'email' => 'finalizado.bloqueado@estudiante.xtart.com',
            'fase_actual' => config('fases.fases.finalizado.db'),
            'tiempo_segundos' => 500,
            'ranking_posicion_bloqueada' => 2,
            'penalizacion_segundos' => 0,
        ]);

        $jugandoAvanzado = $this->createPartida([
            'nombre_equipo' => 'Jugando Avanzado',
            'email' => 'jugando.avanzado@estudiante.xtart.com',
            'fase_actual' => config('fases.fases.prueba8.db'),
            'inicio_juego' => now()->subMinutes(8),
            'penalizacion_segundos' => 0,
            'color_hex' => '#112233',
            'icono' => 'ph-rocket-launch',
            'logros' => ['cerebro_intacto'],
        ]);

        $recienFinalizado = $this->createPartida([
            'nombre_equipo' => 'Recien Finalizado',
            'email' => 'recien.finalizado@estudiante.xtart.com',
            'fase_actual' => config('fases.fases.finalizado.db'),
            'tiempo_segundos' => 700,
            'ranking_posicion_bloqueada' => null,
            'penalizacion_segundos' => 0,
        ]);

        $timeout = $this->createPartida([
            'nombre_equipo' => 'Timeout',
            'email' => 'timeout.top10@estudiante.xtart.com',
            'fase_actual' => config('fases.fases.timeout.db'),
            'tiempo_segundos' => 5400,
            'ranking_posicion_bloqueada' => null,
        ]);

        $jugandoBasico = $this->createPartida([
            'nombre_equipo' => 'Jugando Basico',
            'email' => 'jugando.basico@estudiante.xtart.com',
            'fase_actual' => config('fases.fases.prueba6.db'),
            'inicio_juego' => now()->subMinutes(6),
            'penalizacion_segundos' => 480,
        ]);

        $response = $this->getJson('/api/top10');

        $response->assertOk();
        $response->assertJsonPath('ticker', 'Ticker de pruebas');
        $response->assertJsonCount(5, 'top10');
        $response->assertJsonCount(3, 'finalizados');

        $top10 = $response->json('top10');

        $this->assertSame('Jugando Avanzado', $top10[0]['nombre_equipo']);
        $this->assertSame(1, $top10[0]['rank']);
        $this->assertSame(null, $top10[0]['gap']);
        $this->assertSame('jugando', $top10[0]['estado']);
        $this->assertSame('#112233', $top10[0]['color_hex']);
        $this->assertSame('ph-rocket-launch', $top10[0]['icono']);
        $this->assertSame(['cerebro_intacto'], $top10[0]['logros']);
        $this->assertSame(0, $top10[0]['pistas_usadas']);
        $this->assertSame(89, $top10[0]['progreso_porcentaje']);

        $this->assertSame('Finalizado Bloqueado', $top10[1]['nombre_equipo']);
        $this->assertSame(2, $top10[1]['rank']);
        $this->assertSame('+0m 20s sobre el 1º', $top10[1]['gap']);
        $this->assertSame('finalizado', $top10[1]['estado']);
        $this->assertSame(2, $top10[1]['ranking_posicion_bloqueada']);

        $this->assertSame('Recien Finalizado', $top10[2]['nombre_equipo']);
        $this->assertSame(3, $top10[2]['rank']);
        $this->assertSame('+3m 40s sobre el 1º', $top10[2]['gap']);
        $this->assertSame('finalizado', $top10[2]['estado']);

        $this->assertSame('Timeout', $top10[3]['nombre_equipo']);
        $this->assertSame(4, $top10[3]['rank']);
        $this->assertSame('timeout', $top10[3]['estado']);

        $this->assertSame('Jugando Basico', $top10[4]['nombre_equipo']);
        $this->assertSame(5, $top10[4]['rank']);
        $this->assertSame('jugando', $top10[4]['estado']);

        $recienFinalizado->refresh();
        $timeout->refresh();

        $this->assertSame(3, $recienFinalizado->ranking_posicion_bloqueada);
        $this->assertSame(4, $timeout->ranking_posicion_bloqueada);

        $finalizados = collect($response->json('finalizados'));
        $this->assertSame(
            ['Finalizado Bloqueado', 'Recien Finalizado', 'Timeout'],
            $finalizados->pluck('nombre_equipo')->all()
        );
    }

    public function test_top10_api_uses_penalties_to_calculate_used_hints_and_formats_time(): void
    {
        $partida = $this->createPartida([
            'nombre_equipo' => 'Equipo con pista',
            'email' => 'equipo.pista@estudiante.xtart.com',
            'fase_actual' => config('fases.fases.prueba5.db'),
            'inicio_juego' => now()->subMinutes(10),
            'penalizacion_segundos' => 960,
        ]);

        $response = $this->getJson('/api/top10');

        $response->assertOk();
        $response->assertJsonCount(1, 'top10');
        $response->assertJsonFragment([
            'nombre_equipo' => 'Equipo con pista',
            'estado' => 'jugando',
            'pistas_usadas' => 2,
            'tiempo_formateado' => '26m 00s',
            'fase_numero' => 5,
            'progreso_porcentaje' => 56,
        ]);

        $this->assertSame($partida->id, $response->json('top10.0.id'));
        $this->assertSame(1560, $response->json('top10.0.tiempo_segundos'));
    }
}
