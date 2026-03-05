<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JuegoController;

// 1. PANTALLA DE INICIO (LOGIN)
Route::get('/', function () {
    return view('inicio');
});

// 2. PROCESAR LOGIN DE EQUIPO
Route::post('/empezar', [JuegoController::class , 'empezar']);

// 3. PRUEBA 1 (Con lógica de tiempo real desde BBDD)
Route::get('/prueba1', [JuegoController::class , 'mostrarPrueba1']);

Route::post('/prueba1', [JuegoController::class , 'validarPrueba1']);

// 4. PRUEBA 2 (Con lógica de tiempo real desde BBDD)
Route::get('/prueba2', [JuegoController::class , 'mostrarPrueba2']);
Route::post('/prueba2', [JuegoController::class , 'validarPrueba2']);

// 5. PANTALLA FINAL (Delegada al controlador para pasar datos de BBDD)
Route::get('/final', [JuegoController::class , 'mostrarFinal']);

// 6. PRUEBAS - Subcarpeta escalable (añadir más rutas aquí para nuevas pruebas)
Route::get('/pruebas/aula-115', [JuegoController::class , 'mostrarAula115']);
Route::post('/pruebas/aula-115/completar', [JuegoController::class , 'completarAula115']);

// 7. PANEL DE ADMIN (Protegido con HTTP Basic Auth)
Route::get('/admin', function () {
    $usuario_correcto = '1daw';
    $password_correcto = '1daw';

    if (request()->getUser() != $usuario_correcto || request()->getPassword() != $password_correcto) {
        $headers = ['WWW-Authenticate' => 'Basic'];
        return response('Acceso Denegado: Credenciales incorrectas.', 401, $headers);
    }

    return app(App\Http\Controllers\JuegoController::class)->adminPanel();
});