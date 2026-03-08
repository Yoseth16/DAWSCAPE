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
  USO         : Uso exclusivamente educativo y de demostracion tecnica.
====================================================================
-->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- PWA Meta -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#3b82f6">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">

    <title>@yield('title', 'DAWSCAPE')</title>

    <!-- Fuentes comunes para todo el proyecto -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Share+Tech+Mono&family=Orbitron:wght@400;700;900&display=swap" rel="stylesheet">

    <!-- Stacks para inyectar CSS especifico desde las vistas -->
    @stack('styles')
</head>
<body>

    <!-- Contenido principal de la vista -->
    @yield('content')


    <!-- Stacks para inyectar JS especifico desde las vistas -->
    @stack('scripts')

    <!-- Firma discreta -->
    <div style="position: fixed; bottom: 5px; right: 10px; font-family: monospace; font-size: 0.65rem; color: rgba(255, 255, 255, 0.2); pointer-events: none; z-index: 9999;">
        DAWSCAPE SYSTEM // DAW
    </div>
</body>
</html>