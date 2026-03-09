<?php

namespace App\Http\Controllers;

use App\Models\Inscripcion;
use Illuminate\Http\Request;

/**
 * InscripcionController
 * 
 * Controla el flujo de inscripción previa de equipos antes de que
 * comience el Escape Room. Evita que se registren equipos duplicados.
 */
class InscripcionController extends Controller
{
    /**
     * Muestra el formulario de inscripción previa.
     */
    public function mostrar()
    {
        return view('inscripcion');
    }

    /**
     * Recibe los datos del formulario, los valida y los guarda en la BD.
     * 
     * Reglas:
     *  - nombre_equipo obligatorio y ÚNICO (no puede haber dos equipos con el mismo nombre)
     *  - correo obligatorio, formato email válido y ÚNICO
     */
    public function guardar(Request $request)
    {
        // 1. VALIDACIÓN
        $request->validate([
            'nombre_equipo' => [
                'required',
                'string',
                'max:60',
                'unique:inscripcions,nombre_equipo',
            ],
            'correo' => [
                'required',
                'email',
                'max:120',
                'unique:inscripcions,correo',
            ],
            'clase' => ['nullable', 'string', 'max:20'],
        ], [
            // Mensajes de error personalizados (en español)
            'nombre_equipo.required' => 'El nombre del equipo es obligatorio.',
            'nombre_equipo.unique'   => 'Ya existe un equipo con ese nombre. Elige otro.',
            'correo.required'        => 'El correo electrónico es obligatorio.',
            'correo.email'           => 'El formato del correo no es válido.',
            'correo.unique'          => 'Ese correo ya está registrado con otro equipo.',
        ]);

        // 2. GUARDADO
        Inscripcion::create([
            'nombre_equipo' => $request->nombre_equipo,
            'correo'        => $request->correo,
            'clase'         => $request->clase,
        ]);

        // 3. REDIRECCIÓN con mensaje de éxito
        return redirect()->route('inscripcion.mostrar')
            ->with('success', '¡Equipo "' . $request->nombre_equipo . '" registrado con éxito! Estad atentos el día del Escape Room.');
    }
}
