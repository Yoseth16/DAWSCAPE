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
        'inicio_juego', 
        'fase_actual',
        'uuid',
        'penalizacion_segundos'
    ];

    /**
     * Boot function from Laravel.
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

    public function hitos()
    {
        return $this->hasMany(HitoFase::class);
    }
}
