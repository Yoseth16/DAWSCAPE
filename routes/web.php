<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JuegoController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\QrController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Top10Controller;
use App\Http\Controllers\InscripcionController;

// ==========================================
// ZONA PUBLICA - ESCAPE ROOM (ALUMNOS)
// ==========================================

Route::get('/', function () {
    return view('inicio');
})->name('inicio');

Route::get('/legal', function () {
    return view('aviso-legal');
})->name('legal');

// ==========================================
// INSCRIPCIÓN PREVIA DE EQUIPOS
// ==========================================
Route::get('/inscripcion', [InscripcionController::class, 'mostrar'])->name('inscripcion.mostrar');
Route::post('/inscripcion', [InscripcionController::class, 'guardar'])->name('inscripcion.guardar');
Route::get('/inscripcion/exito/{token}', [InscripcionController::class, 'exito'])->name('inscripcion.exito');
Route::get('/inscripcion/qr/{token}', [InscripcionController::class, 'descargarQr'])->name('inscripcion.qr');
Route::get('/inscripcion/validar/{token}', [InscripcionController::class, 'validarQr'])->name('inscripcion.validar');

Route::post('/empezar', [JuegoController::class, 'empezarPartida']);

// Grupo protegido para las fases del juego (Asegura progreso lineal)
Route::middleware(['check.fase'])->group(function () {
    Route::get('/prueba1', [JuegoController::class, 'mostrarPrueba1'])->name('prueba1');
    Route::post('/prueba1', [JuegoController::class, 'validarPrueba1']);

    Route::get('/prueba2', [JuegoController::class, 'mostrarPrueba2'])->name('prueba2');
    Route::post('/prueba2', [JuegoController::class, 'validarPrueba2']);

    Route::get('/prueba3', [JuegoController::class, 'mostrarPrueba3'])->name('prueba3');
    Route::post('/prueba3', [JuegoController::class, 'validarPrueba3']);

    Route::get('/prueba4', [JuegoController::class, 'mostrarPrueba4'])->name('prueba4');
    Route::post('/prueba4', [JuegoController::class, 'validarPrueba4']);

    Route::get('/prueba5', [JuegoController::class, 'mostrarPrueba5'])->name('prueba5');
    Route::post('/prueba5', [JuegoController::class, 'validarPrueba5']);

    Route::get('/prueba6', [JuegoController::class, 'mostrarPrueba6'])->name('prueba6');
    Route::post('/prueba6', [JuegoController::class, 'validarPrueba6']);

    Route::get('/prueba7', [JuegoController::class, 'mostrarPrueba7'])->name('prueba7');
    Route::post('/prueba7', [JuegoController::class, 'validarPrueba7']);

    Route::get('/final', [JuegoController::class, 'mostrarFinal'])->name('final');
});

// ==========================================
// QRs DEL CÓDICE OCULTO (Prueba 7 Dinámica)
// Deben ir ANTES del comodín /qr/{id}
// ==========================================
foreach (range(0, 9) as $digito) {
    Route::get('/qr/codice-' . $digito, [QrController::class, 'escanearCodicedigito'])
        ->defaults('digito', $digito)
        ->name('qr.codice.' . $digito);
}

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
    Route::get('/admin/equipo/{uuid}/logs', [AdminController::class, 'mostrarLogs'])->name('admin.equipo.logs');
    Route::get('/admin/exportar-top20', [AdminController::class, 'exportarTop20PDF']);

    // Gestión de QRs Dinámicos
    Route::get('/admin/qrs', [QrController::class, 'mostrarGestor'])->name('admin.qrs');
    Route::post('/admin/qrs', [QrController::class, 'guardarQr']);
    Route::delete('/admin/qrs/{id}', [QrController::class, 'eliminarQr']);

    // Rutas de Testing Directo (Bypass para el profesor)
    Route::get('/prueba/prueba1', [JuegoController::class, 'mostrarPrueba1'])->name('admin.prueba.prueba1');
    Route::get('/prueba/prueba2', [JuegoController::class, 'mostrarPrueba2'])->name('admin.prueba.prueba2');
    Route::get('/prueba/prueba3', function () {
        // Crear sesión temporal para admin con email falso
        session(['email' => 'admin-test@prueba3.test', 'nombre_equipo' => 'ADMIN TEST', 'clase' => 'Pruebas']);
        return view('qr.maquina-cafe', [
            'tiempo_transcurrido' => 0,
            'titulo_prueba' => '[ADMIN TEST] Máquina de Café',
            'identificador' => 'admin-bypass'
        ]);
    })->name('admin.prueba.prueba3');

    Route::get('/prueba/prueba4', function () {
        // Crear sesión temporal para admin con email falso
        session(['email' => 'admin-test@prueba4.test', 'nombre_equipo' => 'ADMIN TEST', 'clase' => 'Pruebas']);
        return view('qr.maquina-snacks', [
            'tiempo_transcurrido' => 0,
            'titulo_prueba' => '[ADMIN TEST] Máquina Oasis',
            'identificador' => 'admin-bypass'
        ]);
    })->name('admin.prueba.prueba4');

    Route::get('/prueba/prueba5', function () {
        // Crear sesión temporal para admin con email falso
        session(['email' => 'admin-test@prueba5.test', 'nombre_equipo' => 'ADMIN TEST', 'clase' => 'Pruebas']);
        return view('qr.taquillas', [
            'tiempo_transcurrido' => 0,
            'titulo_prueba' => '[ADMIN TEST] Protocolo Espejo',
            'identificador' => 'admin-bypass'
        ]);
    })->name('admin.prueba.prueba5');
    Route::get('/prueba/prueba6', [JuegoController::class, 'mostrarPrueba6'])->name('admin.prueba.prueba6');
    Route::get('/prueba/prueba7', [JuegoController::class, 'mostrarPrueba7'])->name('admin.prueba.prueba7');

    Route::get('/prueba/prueba7dinamica', function () {
        // Admin bypass: crear sesión temporal y generar combinación de prueba fija para demostración
        session([
            'email'        => 'admin-test@prueba7dinamica.test',
            'nombre_equipo' => 'ADMIN TEST',
            'clase'        => 'Pruebas'
        ]);
        // Forzamos combinación de test visible para el admin: [3, 7, 1, 9]
        return view('pruebas.prueba7dinamica', [
            'tiempo_transcurrido' => 0,
            'combinacion'         => [3, 7, 1, 9],
            'pistas'              => [
                'Cristal de la biblioteca',
                'Mesa de ping pong del patio',
                'Puerta de dirección',
                'Mostrador de conserjería'
            ],
            'intentos_fallidos'   => 0
        ]);
    })->name('admin.prueba.prueba7dinamica');

    // Gestión de Perfil de Profesor (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ==========================================
    // SISTEMA CREADOR DE PRUEBAS: Panel de Administración
    // ==========================================
    Route::get('/preguntas', [AdminController::class, 'gestionarPreguntas'])->name('admin.preguntas');
    Route::post('/preguntas', [AdminController::class, 'guardarPregunta']);
    Route::post('/preguntas/{id}/toggle', [AdminController::class, 'togglePregunta']);
    Route::delete('/preguntas/{id}', [AdminController::class, 'eliminarPregunta']);
});

// ==========================================
// PANTALLA PÚBLICA TOP 10 (LIVE)
// ==========================================
Route::get('/top10', [Top10Controller::class, 'index'])->name('top10');
Route::get('/api/top10', [Top10Controller::class, 'apiData']);

// Rutas de Laravel Auth
require __DIR__.'/auth.php';
