<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HitoFase extends Model
{
    protected $table = 'hitos_fases';

    protected $fillable = [
        'partida_id',
        'fase',
        'segundos_transcurridos',
        'alcanzado_at'
    ];

    public function partida()
    {
        return $this->belongsTo(Partida::class);
    }
}
