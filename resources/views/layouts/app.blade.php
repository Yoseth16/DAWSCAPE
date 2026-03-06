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

    <!-- MODAL DE PISTAS (FASE 6 V2) -->
    @if(session()->has('email') && !request()->is('final') && !request()->is('admin*'))
    <div id="hint-button" style="position: fixed; bottom: 80px; right: 20px; z-index: 999; cursor: pointer;">
        <div style="background: linear-gradient(45deg, #f59e0b, #d97706); color: white; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(217, 119, 6, 0.4); border: 2px solid rgba(255,255,255,0.2);">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 256 256"><path d="M140,180a12,12,0,1,1-12-12A12,12,0,0,1,140,180ZM128,72c-22.06,0-40,16.15-40,36a8,8,0,0,0,16,0c0-11,10.77-20,24-20s24,9,24,20c0,10.23-7.55,15.92-14.12,20.86C130.81,134,120,142.09,120,156a8,8,0,0,0,16,0c0-7.33,6.33-12.09,13-17.06,7.56-5.67,19-14.28,19-30.94C168,88.15,150.06,72,128,72Zm104,56A104,104,0,1,1,128,24,104.11,104.11,0,0,1,232,128Zm-16,0a88,88,0,1,0-88,88A88.1,88.1,0,0,0,216,128Z"></path></svg>
        </div>
    </div>
    <div id="hint-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 10000; align-items: center; justify-content: center; backdrop-filter: blur(5px);">
        <div style="background: #1e293b; border: 1px solid #f59e0b; border-radius: 16px; padding: 30px; max-width: 400px; width: 90%; text-align: center; color: white;">
            <h3 style="color: #f59e0b; margin-top: 0; font-family: 'Orbitron', sans-serif;">NECESITAS AYUDA?</h3>
            <p style="font-size: 0.9rem; margin-bottom: 20px;">Solicitar una pista aplica una <strong>penalizacion de +3 minutos</strong> a tu tiempo final.</p>
            <div id="hint-content" style="display: none; padding: 15px; background: rgba(0,0,0,0.3); border-radius: 8px; margin-bottom: 20px; font-family: monospace; text-align: left; color: #fbbf24; border: 1px dashed #f59e0b;"></div>
            <div id="hint-actions" style="display: flex; gap: 10px; justify-content: center;">
                <button onclick="closeHintModal()" style="padding: 10px 20px; background: transparent; border: 1px solid #64748b; color: #64748b; border-radius: 8px; cursor: pointer;">CANCELAR</button>
                <button id="btn-request-hint" onclick="requestHint()" style="padding: 10px 20px; background: #f59e0b; border: none; color: white; border-radius: 8px; font-weight: bold; cursor: pointer;">SOLICITAR PISTA</button>
            </div>
            <button id="btn-close-hint" onclick="closeHintModal()" style="display: none; width: 100%; padding: 10px; background: #334155; border: none; color: white; border-radius: 8px; cursor: pointer;">ENTENDIDO</button>
        </div>
    </div>
    <script>
        document.getElementById('hint-button').addEventListener('click', function() { document.getElementById('hint-modal').style.display = 'flex'; });
        function closeHintModal() { document.getElementById('hint-modal').style.display = 'none'; }
        function requestHint() {
            const btn = document.getElementById('btn-request-hint');
            btn.innerHTML = 'TRANSFIRIENDO...'; btn.disabled = true;
            fetch('/pedir-pista', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') } })
            .then(res => res.json()).then(data => { if (data.success) { document.getElementById('hint-content').innerText = data.pista; document.getElementById('hint-content').style.display = 'block'; document.getElementById('hint-actions').style.display = 'none'; document.getElementById('btn-close-hint').style.display = 'block'; } })
            .catch(err => { btn.innerHTML = 'ERROR'; btn.disabled = false; });
        }
    </script>
    @endif

    <!-- Stacks para inyectar JS especifico desde las vistas -->
    @stack('scripts')

    <!-- Firma discreta -->
    <div style="position: fixed; bottom: 5px; right: 10px; font-family: monospace; font-size: 0.65rem; color: rgba(255, 255, 255, 0.2); pointer-events: none; z-index: 9999;">
        DAWSCAPE SYSTEM // DAW
    </div>
</body>
</html>