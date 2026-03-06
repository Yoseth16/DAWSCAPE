<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $usuario_correcto = env('ADMIN_USER', '1daw');
        $password_correcto = env('ADMIN_PASSWORD', '1daw');

        if ($request->getUser() != $usuario_correcto || $request->getPassword() != $password_correcto) {
            $headers = ['WWW-Authenticate' => 'Basic'];
            return response('Acceso Denegado: Credenciales incorrectas.', 401, $headers);
        }

        return $next($request);
    }
}
