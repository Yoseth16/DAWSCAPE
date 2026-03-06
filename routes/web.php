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

// 4.1. PRUEBA 3: OPERACIÓN CAFEÍNA (Máquina Café vía QR)
Route::get('/prueba3angel', [JuegoController::class, 'mostrarPrueba3Angel']);
Route::post('/prueba3angel', [JuegoController::class, 'validarPrueba3Angel']);

// 4.1. PRUEBA 4: OPERACIÓN REPUESTOS (Oasis Snacks vía QR)
Route::get('/prueba4arturo', [JuegoController::class, 'mostrarPrueba4Arturo']);
Route::post('/prueba4arturo', [JuegoController::class, 'validarPrueba4Arturo']);

// 4.1. PRUEBA 5: PROTOCOLO ESPEJO (Taquillas vía QR)
Route::get('/prueba5eloy', [JuegoController::class, 'mostrarPrueba5Eloy']);
Route::post('/prueba5eloy', [JuegoController::class, 'validarPrueba5Eloy']);

// 4.1. PRUEBA 6: SOBRECARGA DEL SISTEMA (Mígue - Simon Dice Cooperativo)
Route::get('/prueba6migue', [JuegoController::class, 'mostrarPrueba6Migue']);
Route::post('/prueba6migue', [JuegoController::class, 'validarPrueba6Migue']);

// RUTA PARA TESTEAR LA PRUEBA 6 DIRECTAMENTE (BYPASS)
Route::get('/bypass-prueba6', function() {
    session([
        'email' => 'admin@estudiante.xtart.com',
        'nombre_equipo' => 'Test',
        'clase' => 'Profe',
        'prueba1mario_superada' => true,
        'prueba2yoseth_superada' => true,
        'prueba3angel_superada' => true,
        'prueba4arturo_superada' => true,
        'prueba5eloy_superada' => true,
    ]);
    
    \App\Models\Partida::firstOrCreate(
        ['email' => 'admin@estudiante.xtart.com'],
        [
            'nombre_equipo' => 'Test',
            'clase' => 'Profe',
            'fase_actual' => 'Prueba 6 Migue',
            'tiempo_segundos' => 0,
            'inicio_juego' => \Carbon\Carbon::now()
        ]
    );

    return redirect('/prueba6migue');
});

// 5. PANTALLA FINAL (Delegada al controlador para pasar datos de BBDD)
Route::get('/final', [JuegoController::class , 'mostrarFinal']);

// 6. PRUEBAS - Subcarpeta escalable (añadir más rutas aquí para nuevas pruebas)
Route::get('/pruebas/aula-115', [JuegoController::class , 'mostrarAula115']);
Route::post('/pruebas/aula-115/completar', [JuegoController::class , 'completarAula115']);

// ==========================================
// MÓDULO: SISTEMA DE QRs
// ==========================================
// Ruta para escanear QR (alumnos)
Route::get('/qr/{identificador}', [JuegoController::class, 'escanearQR'])->name('qr.escanear');

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

    // ==========================================
    // SISTEMA DE QRs: Panel de Administración
    // ==========================================
    Route::get('/qrs', function () use ($authMiddleware) {
        return $authMiddleware(request(), function() {
            return app(App\Http\Controllers\JuegoController::class)->gestionarQrs();
        });
    })->name('admin.qrs');

    Route::post('/qrs', function () use ($authMiddleware) {
        return $authMiddleware(request(), function() {
            return app(App\Http\Controllers\JuegoController::class)->guardarQr(request());
        });
    });

    Route::delete('/qrs/{id}', function ($id) use ($authMiddleware) {
        return $authMiddleware(request(), function() use ($id) {
            return app(App\Http\Controllers\JuegoController::class)->eliminarQr($id);
        });
    });

    // ==========================================
    // RUTAS DE TESTEO (BYPASS) PARA EL ADMINISTRADOR
    // ==========================================
    Route::get('/prueba/prueba3angel', function () use ($authMiddleware) {
        return $authMiddleware(request(), function() {
            return view('qr.maquina-cafe', [
                'tiempo_transcurrido' => 0,
                'titulo_prueba' => '[ADMIN TEST] Máquina de Café',
                'identificador' => 'admin-bypass'
            ]);
        });
    });

    Route::get('/prueba/prueba4arturo', function () use ($authMiddleware) {
        return $authMiddleware(request(), function() {
            return view('qr.maquina-snacks', [
                'tiempo_transcurrido' => 0,
                'titulo_prueba' => '[ADMIN TEST] Máquina Oasis',
                'identificador' => 'admin-bypass'
            ]);
        });
    });

    Route::get('/prueba/prueba5eloy', function () use ($authMiddleware) {
        return $authMiddleware(request(), function() {
            return view('qr.taquillas', [
                'tiempo_transcurrido' => 0,
                'titulo_prueba' => '[ADMIN TEST] Protocolo Espejo',
                'identificador' => 'admin-bypass'
            ]);
        });
    });

    Route::get('/prueba/prueba6migue', function () use ($authMiddleware) {
        return $authMiddleware(request(), function() {
            // Pasamos tiempo a 0 y forzamos sesión falsa en caso de no haber sesión de equipo
            session(['nombre_equipo' => '[Modo Administrador]', 'clase' => 'Testing']);
            return view('prueba6migue', [
                'tiempo_transcurrido' => 0
            ]);
        });
    });
});