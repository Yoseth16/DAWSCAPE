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
            <h1>REGISTRO DE EQUIPOS</h1>
            <a href="/" class="btn btn-outline-light">VOLVER AL JUEGO</a>
        </div>

        <table class="table table-dark table-hover table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>EQUIPO</th>
                    <th>CORREO</th>
                    <th>CLASE</th>
                    <th>ESTADO</th>
                    <th>TIEMPO TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach($partidas as $index => $partida)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $partida->nombre_equipo }}</td>
                    <td>{{ $partida->email }}</td>
                    <td>{{ $partida->clase }}</td>
                    
                    <td>
                        @if($partida->fase_actual === 'FINALIZADO')
                            <span class="badge bg-success">✅ COMPLETADO</span>
                        @else
                            <span class="badge bg-warning text-dark">⏳ {{ $partida->fase_actual }}</span>
                        @endif
                    </td>

                    <td>
                        @if($partida->fase_actual === 'FINALIZADO')
                            <span class="text-success fw-bold">
                                {{ floor($partida->tiempo_segundos / 60) }}m {{ $partida->tiempo_segundos % 60 }}s
                            </span>
                        @else
                            {{-- Tiempo en vivo para equipos que aún están jugando --}}
                            @if($partida->inicio_juego)
                                @php
                                    $segundos_jugando = abs(now()->diffInSeconds(\Carbon\Carbon::parse($partida->inicio_juego)));
                                    $minutos_jugando = floor($segundos_jugando / 60);
                                    $segs_jugando = $segundos_jugando % 60;
                                @endphp
                                <span class="text-warning">
                                    🕐 {{ $minutos_jugando }}m {{ $segs_jugando }}s (jugando)
                                </span>
                            @else
                                <span class="text-secondary">--:-- (sin empezar)</span>
                            @endif
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($partidas->isEmpty())
            <div class="alert alert-warning text-center">
                AÚN NO HAY EQUIPOS REGISTRADOS.
            </div>
        @endif
    </div>
</body>
</html>