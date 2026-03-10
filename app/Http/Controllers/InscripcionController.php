<?php

namespace App\Http\Controllers;

use App\Models\Inscripcion;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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
        $validated = $request->validate([
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
            'clase' => ['required', 'string', 'max:20'],
            'acepta_terminos' => ['required', 'accepted'],
        ], [
            // Mensajes de error personalizados (en español)
            'nombre_equipo.required' => 'El nombre del equipo es obligatorio.',
            'nombre_equipo.unique'   => 'Ya existe un equipo con ese nombre. Elige otro.',
            'correo.required'        => 'El correo electrónico es obligatorio.',
            'correo.email'           => 'El formato del correo no es válido.',
            'correo.unique'          => 'Ese correo ya está registrado con otro equipo.',
            'clase.required'         => 'La clase / grupo es obligatoria.',
            'acepta_terminos.required' => 'Debes aceptar el aviso legal para continuar.',
            'acepta_terminos.accepted' => 'Debes aceptar el aviso legal para continuar.',
        ]);

        // 2. GUARDADO
        $inscripcion = Inscripcion::create([
            'nombre_equipo' => $validated['nombre_equipo'],
            'correo'        => $validated['correo'],
            'clase'         => $validated['clase'],
        ]);

        // 3. REDIRECCIÓN con mensaje de éxito y token para descargar QR
        return redirect()->route('inscripcion.exito', ['token' => $inscripcion->qr_token])
            ->with('success', '¡Equipo "' . $validated['nombre_equipo'] . '" registrado con éxito!');
    }

    /**
     * Muestra la página de éxito con la opción de descargar el QR.
     */
    public function exito($token)
    {
        $inscripcion = Inscripcion::where('qr_token', $token)->firstOrFail();

        return view('inscripcion-exito', [
            'inscripcion' => $inscripcion,
        ]);
    }

    /**
     * Descarga el código QR en formato PNG.
     */
    public function descargarQr($token)
    {
        $inscripcion = Inscripcion::where('qr_token', $token)->firstOrFail();

        $qrData = url('/inscripcion/validar/' . $inscripcion->qr_token);

        return QrCode::format('png')
            ->size(300)
            ->generate($qrData);
    }

    /**
     * Valida un QR escaneado (endpoint para validación).
     */
    public function validarQr($token)
    {
        $inscripcion = Inscripcion::where('qr_token', $token)->firstOrFail();

        return response()->json([
            'valido' => true,
            'equipo' => $inscripcion->nombre_equipo,
            'correo' => $inscripcion->correo,
            'clase' => $inscripcion->clase,
        ]);
    }
}
