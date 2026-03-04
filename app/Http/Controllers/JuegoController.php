<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Partida;

class JuegoController extends Controller
{
    // --- LÓGICA PRIVADA BLINDADA ---
    private function obtenerTiempoRestante()
    {
        // 1. Buscamos la partida en BBDD por el email de la sesión
        if (!session()->has('email')) return 0;
        
        $partida = Partida::where('email', session('email'))->first();
        
        if (!$partida || !$partida->inicio_juego) {
            return 1800; // 30 minutos por defecto si no ha empezado
        }
        
        // 2. Cálculo ABSOLUTO (Imposible que falle)
        $inicio = Carbon::parse($partida->inicio_juego);
        $ahora = Carbon::now();
        
        // Cuántos segundos han pasado realmente desde que pulsó el botón
        $segundos_transcurridos = $ahora->diffInSeconds($inicio);
        
        // 3. Restamos a los 30 minutos (1800 segundos)
        $restante = 1800 - $segundos_transcurridos;
        
        // Si sale negativo (se pasó de tiempo), devolvemos 0
        return $restante > 0 ? $restante : 0;
    }

    // --- LOGIN DE EQUIPO ---
    public function empezar(Request $request)
    {
        $request->validate([
            'email' => [
                'required', 'email',
                function ($attribute, $value, $fail) {
                    if (!str_ends_with($value, '@estudiante.xtart.com')) {
                        $fail('ACCESO DENEGADO: Usar correo corporativo (@estudiante.xtart.com).');
                    }
                },
            ],
            'nombre_equipo' => 'required|min:2',
            'clase'  => 'required'
        ]);

        if (Partida::where('email', $request->email)->exists()) {
            return back()->with('error', 'ESTE EQUIPO YA HA JUGADO.');
        }

        // GUARDAMOS EN BBDD LA HORA EXACTA DE INICIO
        Partida::create([
            'email' => $request->email,
            'nombre_equipo' => $request->nombre_equipo,
            'clase' => $request->clase,
            'fase_actual' => 'Prueba 1',
            'tiempo_segundos' => 0,
            'inicio_juego' => Carbon::now() // <--- RELOJ MAESTRO
        ]);

        // Guardamos sesión solo para identificar al usuario
        session([
            'email' => $request->email,
            'nombre_equipo' => $request->nombre_equipo,
            'clase' => $request->clase
        ]);

        return redirect('/prueba1');
    }

    // --- VISTAS (Usan el cálculo blindado) ---
    public function mostrarPrueba1() {
        if (!session()->has('email')) return redirect('/');
        $tiempo_restante = $this->obtenerTiempoRestante();
        return view('prueba1', compact('tiempo_restante'));
    }

    public function mostrarPrueba2() {
        if (!session()->has('prueba1_superada')) return redirect('/prueba1');
        $tiempo_restante = $this->obtenerTiempoRestante();
        return view('prueba2', compact('tiempo_restante'));
    }

    // --- VALIDACIONES ---
    public function validarPrueba1(Request $request)
    {
        if (trim($request->codigo) == '80') {
            session(['prueba1_superada' => true]);
            Partida::where('email', session('email'))->update(['fase_actual' => 'Prueba 2']);
            return redirect('/prueba2');
        }
        return back()->with('error', 'PUERTO INCORRECTO.');
    }

    public function validarPrueba2(Request $request)
    {
        $codigo = strtoupper(trim($request->codigo));

        if (str_contains($codigo, "' OR") || str_contains($codigo, "1=1")) {
            session(['prueba2_superada' => true]);
            
            // CÁLCULO FINAL PARA GUARDAR EN BBDD
            $partida = Partida::where('email', session('email'))->first();
            $inicio = Carbon::parse($partida->inicio_juego);
            $segundos_totales = Carbon::now()->diffInSeconds($inicio);

            // ACTUALIZAMOS BBDD - ESTO SOLUCIONA EL BUG DEL ADMIN
            $partida->update([
                'tiempo_segundos' => $segundos_totales,
                'fase_actual' => 'FINALIZADO' // Estado clave para el admin
            ]);

            return redirect('/final');
        }
        return back()->with('error', 'SINTAXIS INCORRECTA.');
    }

    public function adminPanel()
    {
        $partidas = Partida::orderBy('tiempo_segundos', 'asc')->get();
        return view('admin', compact('partidas'));
    }
}