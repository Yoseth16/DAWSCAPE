<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Inscripcion;

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

                    // Validar que el correo esté inscrito previamente
                    $inscripcion = Inscripcion::where('correo', $value)->first();
                    if (!$inscripcion) {
                        $fail('¡Tu correo no está registrado! Dirígete a /inscripción para registrar tu equipo primero.');
                    }
                },
            ],
            'nombre_equipo' => [
                'required',
                'min:2',
                function ($attribute, $value, $fail) {
                    // Validar que nombre_equipo coincida con inscripción
                    $inscripcion = Inscripcion::where('nombre_equipo', $value)->first();
                    if (!$inscripcion) {
                        $fail('¡El nombre del equipo no está registrado! Debe coincidir con tu inscripción en /inscripción.');
                    }

                    // Validar que el email y nombre_equipo correspondan a la misma inscripción
                    $email = $this->input('email');
                    $inscripcionCorrecta = Inscripcion::where('correo', $email)
                        ->where('nombre_equipo', $value)
                        ->first();

                    if (!$inscripcionCorrecta) {
                        $fail('El correo y nombre del equipo no coinciden con los datos de inscripción.');
                    }
                },
            ],
            'clase' => 'required',
            'color_hex' => 'nullable|string|max:7',
            'icono' => 'nullable|string|max:50'
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'El correo es obligatorio.',
            'nombre_equipo.required' => 'El nombre del equipo es obligatorio.',
            'clase.required' => 'La clase/grupo es obligatoria.',
        ];
    }
}
