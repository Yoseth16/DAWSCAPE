<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin | DAWSCAPE</title>
    <!-- Mantenemos Bootstrap pero lo personalizamos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7f6;
            color: #333333;
            font-family: 'Inter', sans-serif;
            padding: 3rem 1rem;
        }

        .dashboard-container {
            max-width: 1100px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.04);
            padding: 2.5rem;
        }

        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #eef2f5;
            padding-bottom: 1.5rem;
            margin-bottom: 2rem;
        }

        h1 {
            font-size: 1.6rem;
            font-weight: 700;
            color: #1a2b3c;
            margin: 0;
            letter-spacing: -0.5px;
        }

        .btn-outline-primary {
            border-color: #e0e6ed;
            color: #5c6b7a;
            font-weight: 500;
            border-radius: 6px;
        }
        .btn-outline-primary:hover {
            background-color: #f8fafc;
            border-color: #cbd5e1;
            color: #1e293b;
        }

        .table-custom {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
        }

        .table-custom th {
            background-color: #f8fafc;
            color: #64748b;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 1rem 1.2rem;
            border-bottom: 1px solid #e2e8f0;
            border-top: 1px solid #e2e8f0;
        }
        .table-custom th:first-child { border-top-left-radius: 8px; border-left: 1px solid #e2e8f0; }
        .table-custom th:last-child { border-top-right-radius: 8px; border-right: 1px solid #e2e8f0; }

        .table-custom td {
            padding: 1.2rem;
            vertical-align: middle;
            color: #334155;
            font-size: 0.95rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .table-custom tbody tr {
            transition: background-color 0.2s;
        }
        .table-custom tbody tr:hover {
            background-color: #f8fafc;
        }

        .badge {
            font-weight: 500;
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
            font-size: 0.75rem;
            letter-spacing: 0.3px;
        }
        
        /* Soft color badges */
        .badge-success { background-color: #d1fae5; color: #065f46; }
        .badge-danger { background-color: #fee2e2; color: #991b1b; }
        .badge-warning { background-color: #fef3c7; color: #92400e; }

        .time-text { font-family: monospace; font-size: 0.95rem; font-weight: 600; }
        .time-success { color: #059669; }
        .time-danger { color: #dc2626; }
        .time-playing { color: #d97706; }
        .time-idle { color: #94a3b8; }
        
        .team-name { font-weight: 600; color: #0f172a; }
        .team-email { color: #64748b; font-size: 0.85rem; }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #64748b;
            background: #f8fafc;
            border-radius: 8px;
            border: 1px dashed #cbd5e1;
        }

        /* Top 5 Podium */
        .podium-section { margin-bottom: 2.5rem; }
        .podium-title { font-size: 1.1rem; font-weight: 700; color: #1e293b; margin-bottom: 1rem; border-left: 4px solid #3b82f6; padding-left: 0.5rem; }
        .podium-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem; }
        .podium-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.2rem; box-shadow: 0 4px 6px rgba(0,0,0,0.02); display: flex; align-items: center; gap: 1rem; transition: transform 0.2s; }
        .podium-card:hover { transform: translateY(-3px); box-shadow: 0 8px 15px rgba(0,0,0,0.05); border-color: #cbd5e1; }
        .pos-badge { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 0.9rem; flex-shrink: 0; }
        .pos-1 { background-color: #fef08a; color: #713f12; border: 2px solid #eab308; }
        .pos-2 { background-color: #e2e8f0; color: #334155; border: 2px solid #94a3b8; }
        .pos-3 { background-color: #fed7aa; color: #7c2d12; border: 2px solid #fdba74; }
        .pos-other { background-color: #f1f5f9; color: #64748b; border: 1px solid #cbd5e1; }
        .podium-info { min-width: 0; }
        .podium-team { font-weight: 600; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-size: 0.95rem; }
        .podium-time { font-family: monospace; color: #059669; font-weight: 600; font-size: 0.9rem; margin-top: 0.2rem; }

        /* Search Bar */
        .search-container { margin-bottom: 1.5rem; position: relative; }
        .search-input { width: 100%; padding: 0.8rem 1rem 0.8rem 2.8rem; border: 1px solid #cbd5e1; border-radius: 8px; outline: none; transition: border-color 0.2s, box-shadow 0.2s; font-size: 0.95rem; }
        .search-input:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }
        .search-icon { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #94a3b8; }

        /* Actions */
        .btn-delete { background: white; border: 1px solid #fecaca; color: #ef4444; border-radius: 6px; padding: 0.35rem 0.7rem; font-size: 0.8rem; font-weight: 600; cursor: pointer; transition: all 0.2s; }
        .btn-delete:hover { background: #fee2e2; border-color: #fca5a5; }
        .export-btn { background-color: #1e293b; color: white; border: none; font-weight: 500; padding: 0.5rem 1rem; border-radius: 6px; text-decoration: none; font-size: 0.9rem; transition: background 0.2s; }
        .export-btn:hover { background-color: #334155; color: white; }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1>Registro Activo de Equipos</h1>
            <div style="display:flex; gap:1rem; align-items:center;">
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="font-weight: 600;">
                        🛠️ Testear Pruebas
                    </button>
                        <li><a class="dropdown-item" href="/admin/prueba/prueba3angel" target="_blank">Test: Operación Cafeína</a></li>
                        <li><a class="dropdown-item" href="/admin/prueba/prueba4arturo" target="_blank">Test: Operación Repuestos</a></li>
                        <li><a class="dropdown-item" href="/admin/prueba/prueba5eloy" target="_blank">Test: Protocolo Espejo</a></li>
                        <li><a class="dropdown-item" href="/admin/prueba/prueba6migue" target="_blank">Test: Sobrecarga del Sistema</a></li>
                    </ul>
                </div>
                <a href="/admin/qrs" class="btn btn-sm btn-outline-primary" style="font-weight: 600;">📱 Gestionar QRs</a>
                <a href="/admin/exportar-top20" class="export-btn" target="_blank">📄 Exportar Top 20 PDF</a>
                <a href="/" class="btn btn-sm btn-outline-primary">← Salir</a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success mt-3 mb-4" style="background:#d1fae5; border-color:#34d399; color:#065f46;">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger mt-3 mb-4" style="background:#fee2e2; border-color:#f87171; color:#991b1b;">{{ session('error') }}</div>
        @endif

        @if($top5->isNotEmpty())
            <div class="podium-section">
                <div class="podium-title">Top 5 Equipos Más Rápidos</div>
                <div class="podium-grid">
                    @foreach($top5 as $index => $ranking)
                        <div class="podium-card">
                            <div class="pos-badge 
                                {{ $index == 0 ? 'pos-1' : ($index == 1 ? 'pos-2' : ($index == 2 ? 'pos-3' : 'pos-other')) }}">
                                {{ $index + 1 }}º
                            </div>
                            <div class="podium-info">
                                <div class="podium-team" title="{{ $ranking->nombre_equipo }}">{{ $ranking->nombre_equipo }}</div>
                                <div class="podium-time">{{ floor((int)$ranking->tiempo_segundos / 60) }}m {{ (int)$ranking->tiempo_segundos % 60 }}s</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if($partidas->isEmpty())
            <div class="empty-state">
                <div style="font-size:2rem; margin-bottom:1rem; opacity:0.5">📊</div>
                <div style="font-weight:500">Aún no hay equipos registrados en el sistema.</div>
                <div style="font-size:0.85rem; margin-top:0.5rem">Los datos aparecerán aquí en tiempo real cuando inicien.</div>
            </div>
        @else
            <div class="search-container">
                <span class="search-icon">🔍</span>
                <input type="text" id="searchInput" class="search-input" placeholder="Buscar equipo por nombre o correo electrónico..." onkeyup="filterTable()">
            </div>

            <div class="table-responsive">
                <table class="table-custom" id="mainTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Información del Equipo</th>
                            <th>Clase</th>
                            <th>Estado Actual</th>
                            <th>Tiempo Registrado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($partidas as $index => $partida)
                        <tr class="team-row">
                            <td class="text-muted" style="font-weight:500">#{{ str_pad($index + 1, 3, '0', STR_PAD_LEFT) }}</td>
                            <td>
                                <div class="team-name">{{ $partida->nombre_equipo }}</div>
                                <div class="team-email">{{ $partida->email }}</div>
                            </td>
                            <td><span style="background:#f1f5f9; padding:0.3rem 0.6rem; border-radius:4px; font-size:0.85rem; font-weight:500">{{ $partida->clase }}</span></td>
                            
                            <td>
                                @if($partida->fase_actual === 'FINALIZADO')
                                    <span class="badge badge-success">● COMPLETADO</span>
                                @elseif($partida->fase_actual === 'FINALIZADO_TIMEOUT')
                                    <span class="badge badge-danger">● TIEMPO AGOTADO</span>
                                @else
                                    <span class="badge badge-warning">● {{ strtoupper($partida->fase_actual) }}</span>
                                @endif
                            </td>

                            <td class="time-text">
                                @if($partida->fase_actual === 'FINALIZADO' || $partida->fase_actual === 'FINALIZADO_TIMEOUT')
                                    <span class="{{ $partida->fase_actual === 'FINALIZADO' ? 'time-success' : 'time-danger' }}">
                                        {{ floor((int)$partida->tiempo_segundos / 60) }}m {{ (int)$partida->tiempo_segundos % 60 }}s
                                    </span>
                                @else
                                    {{-- Tiempo en vivo para equipos que aún están jugando --}}
                                    @if($partida->inicio_juego)
                                        @php
                                            $segundos_jugando = abs(now()->diffInSeconds(\Carbon\Carbon::parse($partida->inicio_juego)));
                                            $minutos_jugando = floor($segundos_jugando / 60);
                                            $segs_jugando = $segundos_jugando % 60;
                                        @endphp
                                        <span class="time-playing">
                                            {{ sprintf('%02d', $minutos_jugando) }}:{{ sprintf('%02d', $segs_jugando) }} (jugando)
                                        </span>
                                    @else
                                        <span class="time-idle">--:-- (sin empezar)</span>
                                    @endif
                                @endif
                            </td>
                            <td>
                                <form action="/admin/equipo/{{ $partida->id }}" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar a {{ $partida->nombre_equipo }}? Esta acción es irreversible.');" style="margin:0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete" title="Borrar Equipo">X Eliminar</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Live Search Script -->
    <script>
        function filterTable() {
            let input = document.getElementById("searchInput");
            let filter = input.value.toLowerCase();
            let rows = document.querySelectorAll(".team-row");

            rows.forEach(row => {
                let teamName = row.querySelector(".team-name").innerText.toLowerCase();
                let teamEmail = row.querySelector(".team-email").innerText.toLowerCase();
                
                if (teamName.includes(filter) || teamEmail.includes(filter)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }
    </script>
    <!-- Add Bootstrap JS for Dropdown -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>