<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partida extends Model
{
    protected $fillable = ['email', 'nombre', 'clase', 'tiempo_segundos'];
}
