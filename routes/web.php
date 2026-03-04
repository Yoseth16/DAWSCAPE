<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JuegoController;

// 1. PANTALLA DE INICIO (LOGIN)
Route::get('/', function () {
    return view('inicio');
});

// 2. PROCESAR LOGIN DE EQUIPO
Route::post('/empezar', [JuegoController::class, 'empezar']);

// 3. PRUEBA 1 (Con lógica de tiempo real)
// OJO: Cambiamos 'function()...' por '[JuegoController::class, 'mostrarPrueba1']'
Route::get('/prueba1', [JuegoController::class, 'mostrarPrueba1']); 
Route::post('/prueba1', [JuegoController::class, 'validarPrueba1']);

// 4. PRUEBA 2 (Con lógica de tiempo real)
Route::get('/prueba2', [JuegoController::class, 'mostrarPrueba2']);
Route::post('/prueba2', [JuegoController::class, 'validarPrueba2']);

// 5. PANTALLA FINAL
Route::get('/final', function () {
    // Aquí podemos dejar la seguridad básica o moverla al controlador también
    if (!session()->has('prueba2_superada')) {
        return redirect('/prueba2');
    }
    return view('final');
});

// 6. PANEL DE ADMIN (Protegido)
Route::get('/admin', function () {
    $usuario_correcto = '1daw';
    $password_correcto = '1daw'; 

    if (request()->getUser() != $usuario_correcto || request()->getPassword() != $password_correcto) {
        $headers = ['WWW-Authenticate' => 'Basic'];
        return response('Acceso Denegado: Credenciales incorrectas.', 401, $headers);
    }

    return app(App\Http\Controllers\JuegoController::class)->adminPanel();
});