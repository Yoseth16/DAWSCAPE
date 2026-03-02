<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prueba 2 | DAWSCAPE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #050505; color: #00ff00; font-family: 'Courier New', Courier, monospace; }
        .header { border-bottom: 2px solid #00ff00; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; background: rgba(0, 20, 0, 0.9); }
        .timer { font-size: 2rem; font-weight: bold; color: #ff003c; text-shadow: 0 0 10px #ff003c; }
        .game-container { max-width: 800px; margin: 40px auto; padding: 20px; border: 1px dashed #00ff00; }
        .input-hacker { background: transparent; border: 1px solid #00ff00; color: #00ff00; width: 100%; padding: 10px; margin-top: 20px;}
        .btn-validar { background: #00ff00; color: black; border: none; padding: 10px 20px; font-weight: bold; margin-top: 10px; width: 100%; cursor: pointer;}
    </style>
</head>
<body>
    <div class="header">
        <div><strong>Agente:</strong> {{ session('jugador') }} <br><small><strong>División:</strong> {{ session('clase') }}</small></div>
        <div class="timer" id="cronometro">--:--</div>
    </div>

    <div class="game-container">
        @if(session('error'))
            <div class="alert text-center mt-3" style="background: rgba(255,0,0,0.2); border: 1px solid red; color: red;">
                >_ {{ session('error') }}
            </div>
        @endif

        <h2 class="text-center mb-4">>_ PRUEBA 2: INYECCIÓN DE CÓDIGO</h2>
        
        <p class="text-center"><em>"Has llegado al panel de control del servidor, pero necesitas contraseña. ¿Cómo podrías escribir una condición que SIEMPRE sea verdadera para saltarte la seguridad?"</em></p>
        
        <form action="/prueba2" method="POST">
            @csrf
            <input type="text" name="codigo" class="input-hacker" placeholder="SELECT * FROM users WHERE pass = '...' " required autocomplete="off">
            <button type="submit" class="btn-validar">INYECTAR CÓDIGO</button>
        </form>
    </div>

    <script>
        // Un cronómetro visual sencillo para mantener la tensión
        let tiempo = 1500; 
        setInterval(function() {
            let m = parseInt(tiempo / 60, 10); let s = parseInt(tiempo % 60, 10);
            document.getElementById('cronometro').textContent = (m < 10 ? "0"+m : m) + ":" + (s < 10 ? "0"+s : s);
            if (--tiempo < 0) tiempo = 0;
        }, 1000);
    </script>
</body>
</html>