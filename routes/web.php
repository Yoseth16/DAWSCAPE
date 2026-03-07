<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JuegoController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\QrController;
use App\Http\Controllers\ProfileController;

// ==========================================
// ZONA PUBLICA - ESCAPE ROOM (ALUMNOS)
// ==========================================

Route::get('/', function () {
    return view('inicio');
})->name('inicio');

Route::post('/empezar', [JuegoController::class, 'empezarPartida']);

// Grupo protegido para las fases del juego (Asegura progreso lineal)
Route::middleware(['check.fase'])->group(function () {
    Route::get('/prueba1mario', [JuegoController::class, 'mostrarPrueba1'])->name('prueba1');
    Route::post('/prueba1mario', [JuegoController::class, 'validarPrueba1']);

    Route::get('/prueba2yoseth', [JuegoController::class, 'mostrarPrueba2'])->name('prueba2');
    Route::post('/prueba2yoseth', [JuegoController::class, 'validarPrueba2']);

    Route::get('/prueba3angel', [JuegoController::class, 'mostrarPrueba3'])->name('prueba3');
    Route::post('/prueba3angel', [JuegoController::class, 'validarPrueba3']);

    Route::get('/prueba4arturo', [JuegoController::class, 'mostrarPrueba4'])->name('prueba4');
    Route::post('/prueba4arturo', [JuegoController::class, 'validarPrueba4']);

    Route::get('/prueba5eloy', [JuegoController::class, 'mostrarPrueba5'])->name('prueba5');
    Route::post('/prueba5eloy', [JuegoController::class, 'validarPrueba5']);

    Route::get('/prueba6migue', [JuegoController::class, 'mostrarPrueba6'])->name('prueba6');
    Route::post('/prueba6migue', [JuegoController::class, 'validarPrueba6']);

    Route::get('/final', [JuegoController::class, 'mostrarFinal'])->name('final');

    Route::post('/pedir-pista', [JuegoController::class, 'pedirPista'])->name('pedir.pista');
});

// Enrutamiento Inteligente de Escaneo QR
Route::get('/qr/{id}', [QrController::class, 'escanearQr'])->name('qr.scan');


// ==========================================
// ZONA PRIVADA - PANEL DE PROFESORES
// ==========================================

// Envolvemos todo el Dashboard y gestión en el Middleware nativo Auth (Breeze)
Route::middleware(['auth', 'verified'])->group(function () {
    
    // El dashboard principal será nuestra vista /admin
    Route::get('/dashboard', [AdminController::class, 'adminPanel'])->name('dashboard');
    Route::get('/admin', function() { return redirect('/dashboard'); }); // Alias por costumbre

    // Gestión de Partidas y Ticker Live
    Route::get('/admin/api/stats', [AdminController::class, 'apiStats']);
    Route::delete('/admin/equipo/{uuid}', [AdminController::class, 'eliminarEquipo']);
    Route::get('/admin/exportar-top20', [AdminController::class, 'exportarTop20PDF']);
    Route::post('/admin/ticker', [Top10Controller::class, 'updateTicker'])->name('admin.ticker');

    // Gestión de QRs Dinámicos
    Route::get('/admin/qrs', [QrController::class, 'mostrarGestor'])->name('admin.qrs');
    Route::post('/admin/qrs', [QrController::class, 'generarQr']);
    Route::delete('/admin/qrs/{id}', [QrController::class, 'eliminarQr']);

    // Rutas de Testing Directo (Bypass para el profesor)
    Route::get('/prueba/prueba1mario', [JuegoController::class, 'mostrarPrueba1'])->name('admin.prueba.prueba1mario');
    Route::get('/prueba/prueba2yoseth', [JuegoController::class, 'mostrarPrueba2'])->name('admin.prueba.prueba2yoseth');
    Route::get('/prueba/prueba3angel', [JuegoController::class, 'mostrarPrueba3'])->name('admin.prueba.prueba3angel');
    Route::get('/prueba/prueba4arturo', [JuegoController::class, 'mostrarPrueba4'])->name('admin.prueba.prueba4arturo');
    Route::get('/prueba/prueba5eloy', [JuegoController::class, 'mostrarPrueba5'])->name('admin.prueba.prueba5eloy');
    Route::get('/prueba/prueba6migue', [JuegoController::class, 'mostrarPrueba6'])->name('admin.prueba.prueba6migue');

    // Gestión de Perfil de Profesor (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

use App\Http\Controllers\Top10Controller;

// ==========================================
// PANTALLA PÚBLICA TOP 10 (LIVE)
// ==========================================
Route::get('/top10', [Top10Controller::class, 'index'])->name('top10');
Route::get('/api/top10', [Top10Controller::class, 'apiData']);

// Rutas de Laravel Auth
require __DIR__.'/auth.php';