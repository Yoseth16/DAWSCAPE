<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partida extends Model
{
    protected $fillable = [
        'email', 
        'nombre_equipo', 
        'clase', 
        'tiempo_segundos', 
        'inicio_juego', // <--- Añádelo aquí
        'fase_actual'
    ];
}
