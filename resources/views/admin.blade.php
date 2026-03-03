<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN PANEL | DAWSCAPE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #000; color: #00ff00; font-family: 'Courier New', monospace; padding: 40px; }
        h1 { border-bottom: 2px solid #00ff00; padding-bottom: 20px; margin-bottom: 30px; }
        .table { color: #00ff00; border-color: #00ff00; }
        .table-hover tbody tr:hover { color: #000; background-color: #00ff00; }
        .badge { font-size: 1rem; }
    </style>
</head>
<body>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <h1>>_ REGISTRO DE AGENTES EXITOSOS</h1>
            <a href="/" class="btn btn-outline-light">VOLVER AL JUEGO</a>
        </div>

        <table class="table table-dark table-hover table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>AGENTE (NOMBRE)</th>
                    <th>DIVISIÓN (CLASE)</th>
                    <th>TIEMPO TOTAL</th>
                    <th>FECHA MISIÓN</th>
                </tr>
            </thead>
            <tbody>
                @foreach($partidas as $partida)
                <tr>
                    <td>#{{ $partida->id }}</td>
                    <td>{{ $partida->nombre }}</td>
                    <td>{{ $partida->clase }}</td>
                    <td>
                        <span class="badge bg-success">
                            {{ floor($partida->tiempo_segundos / 60) }}m {{ $partida->tiempo_segundos % 60 }}s
                        </span>
                    </td>
                    <td>{{ $partida->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($partidas->isEmpty())
            <div class="alert alert-warning text-center">
                AÚN NO HAY AGENTES QUE HAYAN LOGRADO ESCAPAR.
            </div>
        @endif
    </div>
</body>
</html>