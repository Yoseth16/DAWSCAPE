<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VICTORIA | DAWSCAPE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #050505; color: #00ff00; font-family: 'Courier New', Courier, monospace; text-align: center; padding-top: 50px; }
        .victory-box { border: 2px solid #00ff00; padding: 40px; box-shadow: 0 0 30px rgba(0, 255, 0, 0.5); max-width: 600px; margin: 0 auto; background: rgba(0, 50, 0, 0.2); }
        h1 { font-size: 4rem; text-shadow: 0 0 20px #00ff00; margin-bottom: 20px;}
        .time { font-size: 3rem; color: #fff; text-shadow: 0 0 10px #fff; }
        .ranking { margin-top: 30px; text-align: left; background: rgba(0,0,0,0.5); padding: 20px; border: 1px dotted #00ff00; }
    </style>
</head>
<body>

    <div class="victory-box">
        <h1>¡SISTEMA HACKEADO!</h1>
        <p class="fs-4">Enhorabuena, equipo <strong>{{ $partida->nombre_equipo }}</strong> ({{ $partida->clase }}).</p>
        <p>Has conseguido escapar del instituto a tiempo.</p>
        
        <div class="mt-4">
            <p class="mb-0">Tu tiempo total ha sido:</p>
            <div class="time">{{ floor($partida->tiempo_segundos / 60) }}m {{ $partida->tiempo_segundos % 60 }}s</div>
        </div>

        <div class="ranking">
            <h4 class="text-center">>_ TOP 3 EQUIPOS</h4>
            @if($top3->count() > 0)
                <ol>
                    @foreach($top3 as $equipo)
                        <li @if($equipo->email === $partida->email) style="color: #fff; font-weight: bold;" @endif>
                            {{ $equipo->nombre_equipo }} ({{ $equipo->clase }}) - {{ floor($equipo->tiempo_segundos / 60) }}m {{ $equipo->tiempo_segundos % 60 }}s
                        </li>
                    @endforeach
                </ol>
            @else
                <p class="text-center text-secondary">Aún no hay datos de ranking.</p>
            @endif
        </div>

        <a href="/" class="btn btn-outline-success mt-4">VOLVER AL INICIO</a>
    </div>

</body>
</html>