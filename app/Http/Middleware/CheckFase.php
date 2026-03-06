<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckFase
{
    /**
     * Handle an incoming request.
     *
     * Permite bloquear el acceso a una ruta si la sesión no contiene la variable
     * de "pruebaX_superada" necesaria.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $sesionRequerida  La clave de sesión requerida (ej. 'prueba1mario_superada')
     * @param  string  $redirectRoute    La URI a donde redirigir si falla (ej. '/prueba1mario')
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $sesionRequerida, $redirectRoute)
    {
        if (!session()->has($sesionRequerida)) {
            // Para peticiones AJAX (como los "fetch" del frontend) respondemos JSON
            if ($request->wantsJson() || $request->isMethod('post')) {
                return response()->json(['success' => false, 'message' => 'Falta completar pruebas previas o hacer login.'], 403);
            }
            
            // Para peticiones de vista GET, redirigimos a la prueba anterior pertinente
            return redirect($redirectRoute)->with('error', 'Debes completar esta prueba primero.');
        }

        return $next($request);
    }
}
