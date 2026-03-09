<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inscripcion extends Model
{
    use HasFactory;

    protected $table = 'inscripcions';

    protected $fillable = [
        'nombre_equipo',
        'correo',
        'clase',
    ];
}
