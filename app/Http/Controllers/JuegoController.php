<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Partida; // <--- ¡IMPORTANTE! Importamos el modelo de la BBDD

class JuegoController extends Controller
{
    public function empezar(Request $request)
    {
        // 1. VALIDACIÓN ESTRICTA (DOMINIO INSTITUCIONAL)
        $request->validate([
            'email' => [
                'required',
                'email',
                // Aquí está el truco: una función anónima que verifica el final del texto
                function ($attribute, $value, $fail) {
                    if (!str_ends_with($value, '@estudiante.xtart.com')) {
                        $fail('ACCESO DENEGADO: Debes usar tu correo corporativo (@estudiante.xtart.com).');
                    }
                },
            ],
            'nombre' => 'required|min:2',
            'clase'  => 'required'
        ]);

        // 2. SISTEMA ANTI-TRAMPAS (UNICIDAD)
        // Comprobamos si este email ya ganó (buscando en la BBDD)
        $yaJugo = Partida::where('email', $request->email)->exists();

        if ($yaJugo) {
            return back()->with('error', 'ESTE AGENTE YA HA COMPLETADO LA MISIÓN. SOLO SE PERMITE UN INTENTO.');
        }

        // 3. TODO CORRECTO: GUARDAMOS SESIÓN Y ENTRAMOS
        session([
            'email'   => $request->email,
            'jugador' => $request->nombre,
            'clase'   => $request->clase,
            'inicio_juego' => Carbon::now()
        ]);

        return redirect('/prueba1');
    }

    public function validarPrueba1(Request $request)
    {
        $codigo = trim($request->codigo);

        if ($codigo == '80') {
            session(['prueba1_superada' => true]);
            return redirect('/prueba2');
        } else {
            return back()->with('error', 'CÓDIGO INCORRECTO. Acceso denegado.');
        }
    }

    public function validarPrueba2(Request $request)
    {
        $codigo = strtoupper(trim($request->codigo));

        if (str_contains($codigo, "' OR") || str_contains($codigo, "1=1") || str_contains($codigo, "'='")) {
            session(['prueba2_superada' => true]);
            
            $inicio = session('inicio_juego');
            $segundos_totales = Carbon::now()->diffInSeconds($inicio);
            session(['tiempo_total' => $segundos_totales]);

            // --- GUARDAMOS CON EL EMAIL ---
            // Usamos 'create' dentro de un try-catch por si acaso intentan hackear el formulario
            try {
                Partida::create([
                    'email'  => session('email'), // <--- Guardamos el email
                    'nombre' => session('jugador'),
                    'clase'  => session('clase'),
                    'tiempo_segundos' => $segundos_totales
                ]);
            } catch (\Exception $e) {
                // Si falla (por ejemplo, si abrió dos pestañas para jugar dos veces a la vez)
                return redirect('/')->with('error', 'ERROR: Intento duplicado detectado.');
            }

            return redirect('/final');
        } else {
            return back()->with('error', 'ERROR DE SINTAXIS. PISTA: Inyección SQL básica.');
        }
    }

    // --- NUEVA FUNCIÓN: EL PANEL DE ADMIN ---
    public function adminPanel()
    {
        // Sacamos todas las partidas ordenadas por tiempo (los más rápidos primero)
        $partidas = Partida::orderBy('tiempo_segundos', 'asc')->get();
        
        return view('admin', compact('partidas'));
    }
}