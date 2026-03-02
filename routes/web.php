<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JuegoController;

// 1. La pantalla principal (El Login)
Route::get('/', function () {
    return view('inicio');
});

// 2. La ruta que procesa el formulario cuando le dan a "X TART"
Route::post('/empezar', [JuegoController::class, 'empezar']);

// 3. La pantalla de la Prueba 1
Route::get('/prueba1', function () {
    // Seguridad: Si alguien intenta entrar aquí directamente sin registrarse, lo echamos al inicio
    if (!session()->has('jugador')) {
        return redirect('/');
    }
    return view('prueba1');
});
// Ruta que recibe el formulario de la Prueba 1
Route::post('/prueba1', [JuegoController::class, 'validarPrueba1']);

// La pantalla de la Prueba 2
Route::get('/prueba2', function () {
    // Seguridad hacker: Si intentan saltarse la prueba 1 poniendo la URL a mano, los echamos
    if (!session()->has('prueba1_superada')) {
        return redirect('/prueba1');
    }
    return view('prueba2');
});
// Ruta que recibe el formulario de la Prueba 2
Route::post('/prueba2', [JuegoController::class, 'validarPrueba2']);

// La pantalla FINAL (Victoria)
Route::get('/final', function () {
    if (!session()->has('prueba2_superada')) {
        return redirect('/prueba2');
    }
    return view('final');
});