<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreguntaDinamica extends Model
{
    protected $table = 'pregunta_dinamicas';
    protected $fillable = ['enunciado', 'respuesta_correcta', 'activa'];
}
