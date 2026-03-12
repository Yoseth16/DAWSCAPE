<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckFase
{
    /**
     * Progresión lineal: cada prueba requiere que se haya superado la anterior.
     * La clave es el segmento de la URL (ej: 'prueba3'), el valor es la
     * flag de sesión que debe existir para poder acceder.
     */
    private const REQUISITOS = [
        'prueba2'  => 'prueba1mario_superada',
        'prueba3'  => 'prueba2yoseth_superada',
        'prueba4'  => 'prueba3angel_superada',
        'prueba5'  => 'prueba4arturo_superada',
        'prueba6'  => 'prueba5eloy_superada',
        'prueba7'  => 'prueba6migue_superada',
        'final'    => 'prueba7dinamica_superada',
    ];

    /**
     * Verifica que el jugador tiene una sesión activa y que no se salta fases.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('email')) {
            if ($request->wantsJson() || $request->isMethod('post')) {
                return response()->json(['success' => false, 'message' => 'Sesión no activa. Registra tu equipo primero.'], 403);
            }
            $destino = '/' . ltrim($request->path(), '/');
            $qs = $request->getQueryString();
            if (!empty($qs)) {
                $destino .= '?' . $qs;
            }

            return redirect()
                ->route('inicio', ['redirect_to' => $destino])
                ->with('error', 'Debes registrar tu equipo antes de jugar.');
        }

        // Comprobar progresión lineal (solo en GETs de pruebas)
        if ($request->isMethod('get')) {
            $segmento = $request->segment(1); // prueba1, prueba2, ..., final

            if (isset(self::REQUISITOS[$segmento]) && !session(self::REQUISITOS[$segmento])) {
                // Determinar a qué prueba debería estar el jugador
                $rutaCorrecta = $this->rutaActual();

                if ($request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => 'No puedes saltarte pruebas.'], 403);
                }

                return redirect($rutaCorrecta)
                    ->with('error', 'No puedes saltarte pruebas. Completa las anteriores primero.');
            }
        }

        return $next($request);
    }

    /**
     * Devuelve la ruta de la prueba más avanzada que el jugador puede ver.
     */
    private function rutaActual(): string
    {
        $orden = ['prueba1', 'prueba2', 'prueba3', 'prueba4', 'prueba5', 'prueba6', 'prueba7', 'final'];

        foreach ($orden as $ruta) {
            if (isset(self::REQUISITOS[$ruta]) && !session(self::REQUISITOS[$ruta])) {
                // La anterior es la última desbloqueada
                $idx = array_search($ruta, $orden);
                return '/' . ($idx > 0 ? $orden[$idx - 1] : $orden[0]);
            }
        }

        return '/final';
    }
}
