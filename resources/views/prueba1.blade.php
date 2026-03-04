<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prueba 1 | DAWSCAPE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #050505; color: #00ff00; font-family: 'Courier New', Courier, monospace;
        }
        .header {
            border-bottom: 2px solid #00ff00; padding: 15px 30px;
            display: flex; justify-content: space-between; align-items: center;
            background: rgba(0, 20, 0, 0.9); box-shadow: 0 5px 15px rgba(0,255,0,0.2);
        }
        .timer { font-size: 2rem; font-weight: bold; color: #ff003c; text-shadow: 0 0 10px #ff003c; }
        .game-container { max-width: 800px; margin: 40px auto; padding: 20px; border: 1px dashed #00ff00; }
        .input-hacker { background: transparent; border: 1px solid #00ff00; color: #00ff00; width: 100%; padding: 10px; margin-top: 20px;}
        .btn-validar { background: #00ff00; color: black; border: none; padding: 10px 20px; font-weight: bold; margin-top: 10px; width: 100%; cursor: pointer;}
    </style>
</head>
<body>

    <div class="header">
        <div>
            <strong>Agente:</strong> {{ session('jugador') }} <br>
            <small><strong>División:</strong> {{ session('clase') }}</small>
        </div>
        <div class="timer" id="cronometro">30:00</div>
    </div>

    <div class="game-container">
        @if(session('error'))
            <div class="alert text-center mt-3" style="background: rgba(255,0,0,0.2); border: 1px solid red; color: red;">
                >_ ERROR: {{ session('error') }}
            </div>
        @endif
        <h2 class="text-center mb-4">>_ PRUEBA 1: EL ENIGMA DEL PUERTO</h2>
        
        <p class="text-center"><em>"Para navegar por la red mundial, un protocolo debes usar. Oculto en el texto, el número encontrarás. HTTP es tu guía, ¿por qué puerta entrarás?"</em></p>
        
        <div class="text-center mt-4">
            <p>[PISTA: Puerto estándar de navegación web no segura]</p>
        </div>

        <form action="/prueba1" method="POST">
            @csrf
            <input type="text" name="codigo" class="input-hacker" placeholder="Introduce el código de desbloqueo..." required autocomplete="off">
            <button type="submit" class="btn-validar">VALIDAR CÓDIGO</button>
        </form>
    </div>

    <script>
    // Recibimos el tiempo restante real desde el Controlador de Laravel
    let tiempo = {{ $tiempo_restante }}; 
    
    const display = document.getElementById('cronometro');

    setInterval(function() {
        if (tiempo <= 0) {
            tiempo = 0;
            display.textContent = "00:00";
            alert("¡TIEMPO AGOTADO! Misión fallida.");
            // Aquí podrías redirigir a una pantalla de Game Over
            return;
        }

        let minutos = parseInt(tiempo / 60, 10);
        let segundos = parseInt(tiempo % 60, 10);

        minutos = minutos < 10 ? "0" + minutos : minutos;
        segundos = segundos < 10 ? "0" + segundos : segundos;

        display.textContent = minutos + ":" + segundos;
        tiempo--; // Restamos un segundo
    }, 1000);
</script>
</body>
</html>