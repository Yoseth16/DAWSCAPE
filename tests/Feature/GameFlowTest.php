<?php

namespace Tests\Feature;

use App\Models\Inscripcion;
use App\Models\Partida;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GameFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_player_can_start_a_game(): void
    {
        $inscripcion = Inscripcion::create([
            'nombre_equipo' => 'Test Team',
            'correo' => 'migue@estudiante.xtart.com',
            'clase' => '1DAW',
        ]);

        $response = $this->post('/empezar', [
            'correo' => 'migue@estudiante.xtart.com',
            'codigo_acceso' => $inscripcion->codigo_acceso,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('prueba1'));

        $this->assertDatabaseHas('partidas', [
            'email' => 'migue@estudiante.xtart.com',
            'nombre_equipo' => 'Test Team',
        ]);
    }

    public function test_duplicate_email_reconnects(): void
    {
        $inscripcion = Inscripcion::create([
            'nombre_equipo' => 'Original',
            'correo' => 'repetido@estudiante.xtart.com',
            'clase' => '1DAW',
        ]);

        Partida::create([
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'email' => 'repetido@estudiante.xtart.com',
            'nombre_equipo' => 'Original',
            'clase' => '1DAW',
            'fase_actual' => config('fases.fases.prueba1.db'),
            'tiempo_segundos' => 0,
            'inicio_juego' => now(),
            'combinacion_codice' => [0, 1, 2, 3],
            'intentos_codice' => 0,
        ]);

        $response = $this->post('/empezar', [
            'correo' => 'repetido@estudiante.xtart.com',
            'codigo_acceso' => $inscripcion->codigo_acceso,
        ]);

        // Should reconnect (redirect to prueba), not reject
        $response->assertStatus(302);
    }

    public function test_invalid_code_cannot_start_game(): void
    {
        Inscripcion::create([
            'nombre_equipo' => 'Test Team',
            'correo' => 'test@estudiante.xtart.com',
            'clase' => '1DAW',
        ]);

        $response = $this->post('/empezar', [
            'correo' => 'test@estudiante.xtart.com',
            'codigo_acceso' => 'XXXXXX',
        ]);

        // Should fail validation and redirect back
        $response->assertStatus(302);
        $response->assertRedirect('/');
    }
}
