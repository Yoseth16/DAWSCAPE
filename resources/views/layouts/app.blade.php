<!--
====================================================================
   ___    ___   _      __ _____  ____   ___  ___   ____  ____ 
  / _ \  / _ \ | | /\ | |/ ___/ / ___| / _ \| _ \ | __/ |  _ \
 | | | || | | || |/  \| |\___ \ | |   | | | |  _/ | __| | | | |
 | |_| || |_| ||   /\   | __/ / | |___| |_| | |   | |___| |_| |
 \___/  \___/  |__/  \__|/___/   \____|\___/|_|   |____/|____/ 
 
====================================================================
  PROYECTO    : DAWSCAPE - Escape Room Educativo
  DESARROLLO  : Alumnos de Desarrollo de Aplicaciones Web (DAW)
  USO         : Uso exclusivamente educativo y de demostración técnica.
  AVISO LEGAL : Este software educativo ha sido creado como proyecto 
                práctico. Queda prohibida su comercialización sin permiso.
====================================================================
-->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'DAWSCAPE')</title>

    <!-- Fuentes comunes para todo el proyecto (precargadas para mayor velocidad) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Share+Tech+Mono&family=Orbitron:wght@400;700;900&display=swap" rel="stylesheet">

    <!-- Stacks para inyectar CSS específico desde las vistas -->
    @stack('styles')
</head>
<body>

    <!-- Contenido principal de la vista -->
    @yield('content')

    <!-- Stacks para inyectar JS específico desde las vistas -->
    @stack('scripts')

    <!-- Firma discreta de los autores para todas las pantallas -->
    <div style="position: fixed; bottom: 5px; right: 10px; font-family: monospace; font-size: 0.65rem; color: rgba(255, 255, 255, 0.2); pointer-events: none; z-index: 9999;">
        © {{ date('Y') }} DAWSCAPE SYSTEM // Desarrollado por Alumnos DAW
    </div>

    <!-- Mensaje para desarrolladores/jugadores curiosos que inspeccionen la web -->
    <script>
        console.log("%c========================================", "color: #00ff88; font-weight: bold;");
        console.log("%c🔌 DAWSCAPE SYSTEM CORE INICIADO", "color: #00ff88; font-weight: bold; font-size: 14px;");
        console.log("%c========================================", "color: #00ff88; font-weight: bold;");
        console.log("%c⚠️ AVISO LEGAL DE DESARROLLO:", "color: #ffaa00; font-weight: bold;");
        console.log("%cEste sistema ha sido íntegramente diseñado y programado por los alumnos del ciclo de Desarrollo de Aplicaciones Web (DAW).", "color: #ffffff;");
        console.log("%cCualquier intento de sobrecarga no autorizada en el núcleo será registrada.", "color: #ff2244;");
    </script>
</body>
</html>
