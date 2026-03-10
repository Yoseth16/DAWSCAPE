<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Inscripcion extends Model
{
    use HasFactory;

    protected $table = 'inscripcions';

    protected $fillable = [
        'nombre_equipo',
        'correo',
        'clase',
        'qr_token',
    ];

    /**
     * Generar un token QR único antes de crear la inscripción.
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->qr_token)) {
                $model->qr_token = Str::random(32);
            }
        });
    }
}
