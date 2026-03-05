<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JuegoController;

// 1. PANTALLA DE INICIO (LOGIN)
Route::get('/', function () {
    return view('inicio');
});

// 2. PROCESAR LOGIN DE EQUIPO
Route::post('/empezar', [JuegoController::class , 'empezar']);

// 3. PRUEBA 1 MARIO (Antigua: AULA 115)
Route::get('/prueba1mario', [JuegoController::class, 'mostrarPrueba1Mario']);
Route::post('/prueba1mario', [JuegoController::class, 'validarPrueba1Mario']);

// 4. PRUEBA 2: EL CLÚSTER FRAGMENTADO (Expendedoras)
Route::get('/prueba2yoseth', [JuegoController::class, 'mostrarPrueba2Yoseth']);
Route::post('/prueba2yoseth', [JuegoController::class, 'validarPrueba2Yoseth']);

// 5. PANTALLA FINAL (Delegada al controlador para pasar datos de BBDD)
Route::get('/final', [JuegoController::class , 'mostrarFinal']);

// 6. PRUEBAS - Subcarpeta escalable (añadir más rutas aquí para nuevas pruebas)
Route::get('/pruebas/aula-115', [JuegoController::class , 'mostrarAula115']);
Route::post('/pruebas/aula-115/completar', [JuegoController::class , 'completarAula115']);

// 7. PANEL DE ADMIN Y MANEJO (Protegido con HTTP Basic Auth)
Route::middleware('auth.basic')->group(function () {
    // Si necesitas Auth básico nativo añade 'auth.basic' arriba en arr, pero como usan custom, metémolos en un prefijo manual
});

Route::prefix('admin')->group(function () {

    // Middleware custom inline de protección (reaplicado para todas las subrutas de Admin)
    $authMiddleware = function ($request, \Closure $next) {
        $usuario_correcto = '1daw';
        $password_correcto = '1daw';
        if ($request->getUser() != $usuario_correcto || $request->getPassword() != $password_correcto) {
            $headers = ['WWW-Authenticate' => 'Basic'];
            return response('Acceso Denegado: Credenciales incorrectas.', 401, $headers);
        }
        return $next($request);
    };

    Route::get('/', function () use ($authMiddleware) {
        return $authMiddleware(request(), function() {
            return app(App\Http\Controllers\JuegoController::class)->adminPanel();
        });
    });

    Route::delete('/equipo/{id}', function ($id) use ($authMiddleware) {
        return $authMiddleware(request(), function() use ($id) {
            return app(App\Http\Controllers\JuegoController::class)->eliminarEquipo($id);
        });
    });

    Route::get('/exportar-top20', function () use ($authMiddleware) {
        return $authMiddleware(request(), function() {
            return app(App\Http\Controllers\JuegoController::class)->exportarTop20PDF();
        });
    });
});