<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmpezarPartidaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => [
                'required', 
                'email',
                function ($attribute, $value, $fail) {
                    if (!str_ends_with($value, '@estudiante.xtart.com')) {
                        $fail('ACCESO DENEGADO: Usar correo corporativo (@estudiante.xtart.com).');
                    }
                },
            ],
            'nombre_equipo' => 'required|min:2',
            'clase' => 'required'
        ];
    }
}
