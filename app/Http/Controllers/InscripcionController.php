<?php

namespace App\Http\Controllers;

use App\Models\Inscripcion;
use App\Mail\CodigoAccesoInscripcion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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
     * Arreglo de clases disponibles (selector de lista cerrada).
     * Previene inconsistencias de datos en BD.
     */
    private array $clases = [
        '1º DAW',
        '2º DAW',
        '1º ASIR',
        '2º ASIR',
        '1º Marketing Digital',
        '2º Marketing Digital',
        'Otros',
    ];

    /**
     * Muestra el formulario de inscripción previa.
     */
    public function mostrar()
    {
        return view('inscripcion', [
            'clases' => $this->clases,
        ]);
    }

    /**
     * Recibe los datos del formulario, los valida y los guarda en la BD.
     *
     * Reglas:
     *  - nombre_equipo obligatorio y ÚNICO
     *  - correo obligatorio, formato email válido y ÚNICO
     *  - clase debe estar en la lista predefinida
     *
     * Genera:
     *  - código_acceso: 6 caracteres alfanuméricos únicos
     *  - Envía email con Resend
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
                function ($attribute, $value, $fail) {
                    if (!str_ends_with($value, '@estudiante.xtart.com')) {
                        $fail('El correo debe pertenecer al dominio @estudiante.xtart.com del centro.');
                    }
                },
            ],
            'clase' => [
                'required',
                'string',
                'in:' . implode(',', $this->clases),
            ],
            'acepta_terminos' => ['required', 'accepted'],
        ], [
            // Mensajes de error personalizados (en español)
            'nombre_equipo.required' => 'El nombre del equipo es obligatorio.',
            'nombre_equipo.unique'   => 'Ya existe un equipo con ese nombre. Elige otro.',
            'correo.required'        => 'El correo electrónico es obligatorio.',
            'correo.email'           => 'El formato del correo no es válido.',
            'correo.unique'          => 'Ese correo ya está registrado con otro equipo.',
            'clase.required'         => 'Debes seleccionar una clase / grupo.',
            'clase.in'               => 'La clase seleccionada no es válida.',
            'acepta_terminos.required' => 'Debes aceptar el aviso legal para continuar.',
            'acepta_terminos.accepted' => 'Debes aceptar el aviso legal para continuar.',
        ]);

        // 2. GUARDADO
        $inscripcion = Inscripcion::create([
            'nombre_equipo' => $validated['nombre_equipo'],
            'correo'        => $validated['correo'],
            'clase'         => $validated['clase'],
        ]);

        // 3. ENVÍO DE EMAIL CON CÓDIGO DE ACCESO
        $correoEnviado = false;

        try {
            Mail::send(new CodigoAccesoInscripcion($inscripcion));
        } catch (\Throwable $e) {
            \Log::error('Error enviando email de inscripción: ' . $e->getMessage());
            // No fallar si hay error en email, pero loguear
        }

        // 4. REDIRECCIÓN con mensaje de éxito (independientemente del correo)
        return redirect()->route('inscripcion.exito', ['token' => $inscripcion->qr_token])
            ->with('success', '¡Equipo "' . $validated['nombre_equipo'] . '" registrado con éxito!')
            ->with('correo_enviado', $correoEnviado)
            ->with('correo_fallido_codigo', $correoEnviado ? null : $inscripcion->codigo_acceso);

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
