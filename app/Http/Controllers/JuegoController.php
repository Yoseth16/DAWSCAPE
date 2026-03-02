<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon; // La librería de Laravel para manejar fechas y tiempos

class JuegoController extends Controller
{
    public function empezar(Request $request)
    {
        $request->validate([
            'nombre' => 'required|min:2',
            'clase'  => 'required'
        ]);

        session([
            'jugador' => $request->nombre,
            'clase'   => $request->clase,
            'inicio_juego' => Carbon::now() // ¡Guardamos el segundo exacto en el que empieza!
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

    // --- NUEVA FUNCIÓN PARA LA PRUEBA 2 ---
    public function validarPrueba2(Request $request)
    {
        $codigo = strtoupper(trim($request->codigo)); // Lo pasamos a mayúsculas para que sea más fácil acertar

        // Buscamos si el usuario ha puesto el clásico ataque de inyección SQL "' OR 1=1" (o variaciones)
        if (str_contains($codigo, "' OR") || str_contains($codigo, "1=1") || str_contains($codigo, "'='")) {
            session(['prueba2_superada' => true]);
            
            // Calculamos el tiempo total que ha tardado
            $inicio = session('inicio_juego');
            $segundos_totales = Carbon::now()->diffInSeconds($inicio);
            session(['tiempo_total' => $segundos_totales]);

            return redirect('/final');
        } else {
            // El PDF pedía dar una PISTA si fallaban
            return back()->with('error', 'ERROR DE SINTAXIS. PISTA: Piensa en cómo engañar a una consulta SQL usando una comilla simple y un OR lógico (ejemplo: \' OR 1=1).');
        }
    }
}