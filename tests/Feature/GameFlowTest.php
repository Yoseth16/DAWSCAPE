<?php

namespace Tests\Feature;

use App\Models\Partida;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GameFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_player_can_start_a_game(): void
    {
        $response = $this->post('/empezar', [
            'email' => 'migue@estudiante.xtart.com',
            'nombre_equipo' => 'Test Team',
            'clase' => '1DAW'
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/prueba1mario');
        
        $this->assertDatabaseHas('partidas', [
            'email' => 'migue@estudiante.xtart.com',
            'nombre_equipo' => 'Test Team'
        ]);
    }

    public function test_duplicate_email_cannot_start_game(): void
    {
        Partida::create([
            'email' => 'repetido@estudiante.xtart.com',
            'nombre_equipo' => 'Original',
            'clase' => '1DAW',
            'fase_actual' => 'Prueba 1 Mario'
        ]);

        $response = $this->post('/empezar', [
            'email' => 'repetido@estudiante.xtart.com',
            'nombre_equipo' => 'Duplicate',
            'clase' => '1DAW'
        ]);

        $response->assertSessionHas('error', 'ESTE EQUIPO YA HA JUGADO.');
    }
}
