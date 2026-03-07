<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckFase
{
    /**
     * Verifica que el jugador tiene una sesión activa (email registrado).
     * Bloquea el acceso si no ha iniciado una partida.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('email')) {
            if ($request->wantsJson() || $request->isMethod('post')) {
                return response()->json(['success' => false, 'message' => 'Sesión no activa. Registra tu equipo primero.'], 403);
            }
            return redirect()->route('inicio')->with('error', 'Debes registrar tu equipo antes de jugar.');
        }

        return $next($request);
    }
}
