<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DAWSCAPE | Escape Room</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #0a0a0a; color: #00ff00; font-family: 'Courier New', Courier, monospace;
            height: 100vh; display: flex; align-items: center; justify-content: center; margin: 0; overflow: hidden;
        }
        .glitch-container {
            text-align: center; padding: 40px; border: 1px solid #00ff00;
            box-shadow: 0 0 15px rgba(0, 255, 0, 0.2); background: rgba(0, 255, 0, 0.05);
        }
        h1 { font-size: 4rem; font-weight: bold; letter-spacing: 5px; text-shadow: 2px 2px 0px #ff003c, -2px -2px 0px #00e6fe; }
        .btn-start {
            margin-top: 30px; background: transparent; color: #00ff00; border: 2px solid #00ff00;
            padding: 10px 30px; font-size: 1.5rem; font-weight: bold; transition: all 0.3s; cursor: not-allowed;
        }
    </style>
</head>
<body>
    <div class="glitch-container">
        <h1>DAWSCAPE</h1>
        <p class="fs-4 mt-3">El instituto está en peligro. ¿Podrás escapar?</p>
        <div class="mt-4 text-white-50 small">
            [ ESTADO DEL SISTEMA: CARGANDO ACERTIJOS... ]<br>
            [ ESPERANDO INSTRUCCIONES DEL EQUIPO DE DISEÑO... ]
        </div>
        <button class="btn btn-start" disabled>INICIAR JUEGO</button>
    </div>
</body>
</html>