@extends('layouts.admin')

@section('title', 'Admin Dashboard Premium | DAWSCAPE')

@push('admin-styles')
    <!-- Solo estilos específicos de la vista principal -->
    <style>
        /* --- CARDS (KPIs) --- */
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }

        .kpi-card {
            background: var(--surface-color);
            border-radius: 16px;
            padding: 1.5rem;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 20px rgba(0,0,0,0.02);
            display: flex;
            align-items: center;
            gap: 1.2rem;
            transition: transform 0.3s;
        }

        .kpi-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        }

        .kpi-icon-wrap {
            width: 54px;
            height: 54px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.7rem;
            flex-shrink: 0;
        }

        .kpi-blue { background: #eff6ff; color: #3b82f6; }
        .kpi-green { background: #f0fdf4; color: #10b981; }
        .kpi-yellow { background: #fefce8; color: #eab308; }
        .kpi-purple { background: #faf5ff; color: #a855f7; }

        .kpi-info h4 {
            margin: 0;
            font-size: 0.85rem;
            color: var(--text-muted);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .kpi-info p {
            margin: 5px 0 0 0;
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--text-main);
            line-height: 1;
        }

        /* --- DOS COLUMNAS (Chart + Podium) --- */
        .mid-grid {
            display: grid;
            grid-template-columns: 2fr 3fr;
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }

        .panel-heading {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .panel-heading i { color: var(--brand-color); }

        .chart-container { position: relative; height: 250px; width: 100%; display: flex; justify-content: center; }

        /* Podium List */
        .podium-list { display: flex; flex-direction: column; gap: 12px; }
        .podium-item {
            display: flex; align-items: center; padding: 12px 16px; border-radius: 12px;
            background: #f8fafc; border: 1px solid #e2e8f0; transition: all 0.2s;
        }
        .podium-item:hover { border-color: #cbd5e1; background: #fff; transform: scale(1.01); }

        .pos-badge {
            width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center;
            font-weight: bold; font-size: 1.1rem; margin-right: 15px;
        }
        .pos-1 { background: linear-gradient(135deg, #fef08a, #eab308); color: #713f12; box-shadow: 0 4px 10px rgba(234, 179, 8, 0.2); }
        .pos-2 { background: linear-gradient(135deg, #e2e8f0, #94a3b8); color: #334155; }
        .pos-3 { background: linear-gradient(135deg, #fed7aa, #f97316); color: #7c2d12; }
        .pos-other { background: #f1f5f9; color: #64748b; }

        .podium-details { flex: 1; min-width: 0; }
        .podium-name { font-weight: 700; color: var(--text-main); margin-bottom: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .podium-class { font-size: 0.8rem; color: var(--text-muted); }
        .podium-time { font-family: 'Courier New', monospace; font-weight: 700; color: var(--success-color); font-size: 1.1rem; }

        /* --- TABLE --- */
        .table-responsive { border-radius: 16px; overflow: hidden; border: 1px solid var(--border-color); background: white; box-shadow: 0 4px 20px rgba(0,0,0,0.02); }
        .table-custom { width: 100%; margin: 0; border-collapse: collapse; }
        .table-custom th {
            background-color: #f8fafc; color: var(--text-muted); font-weight: 600; text-transform: uppercase;
            letter-spacing: 0.5px; font-size: 0.75rem; padding: 1.2rem 1.5rem; border-bottom: 1px solid var(--border-color);
        }
        .table-custom td { padding: 1.2rem 1.5rem; vertical-align: middle; border-bottom: 1px solid #f1f5f9; color: var(--text-main); }
        .table-custom tr:hover { background-color: #f8fafc; }

        /* Avatares */
        .team-cell { display: flex; align-items: center; gap: 15px; }
        .avatar {
            width: 42px; height: 42px; border-radius: 12px; background: linear-gradient(135deg, #60a5fa, #3b82f6);
            color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.1rem; box-shadow: 0 4px 10px rgba(59, 130, 246, 0.2);
        }
        .team-meta .t-name { font-weight: 600; margin-bottom: 2px; }
        .team-meta .t-email { font-size: 0.8rem; color: var(--text-muted); }

        /* Badges */
        .status-pill {
            display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 20px;
            font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px;
        }
        .status-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .status-danger { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .status-warning { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }

        .time-display { font-family: monospace; font-weight: 600; font-size: 1rem; }
        .text-live { color: var(--warning-color); }

        /* Botones Acción */
        .btn-action {
            width: 34px; height: 34px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center;
            border: 1px solid transparent; cursor: pointer; transition: all 0.2s; color: var(--text-muted); background: #f1f5f9;
        }
        .btn-action:hover { background: #e2e8f0; color: var(--danger-color); }

        .export-btn {
            background: var(--text-main); color: white; border: none; padding: 0.6rem 1.2rem; border-radius: 8px;
            font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s;
        }
        .export-btn:hover { background: #0f172a; color: white; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.15); }

        /* Buscador */
        .search-wrapper { position: relative; margin-bottom: 1.5rem; max-width: 400px; }
        .search-wrapper i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 1.2rem; }
        .search-input { width: 100%; padding: 0.8rem 1rem 0.8rem 2.8rem; border: 1px solid var(--border-color); border-radius: 12px; background: white; transition: all 0.3s; outline: none; }
        .search-input:focus { border-color: var(--brand-accent); box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1); }
    </style>
@endpush

@section('admin-content')
        <div class="page-header">
            <div>
                <h1 class="page-title">Visión General</h1>
                <div class="page-subtitle">Monitorización en tiempo real de la actividad del Escape Room.</div>
            </div>
            <div style="display:flex; gap: 15px; align-items:center;">
                <button onclick="window.location.reload()" class="export-btn" style="background:var(--surface-hover); color:var(--text-main); border: 1px solid var(--border-color);">
                    <i class="ph ph-arrows-clockwise"></i> Recargar
                </button>

                <a href="{{ route('top10') }}" target="_blank" class="export-btn" style="background:#a855f7;">
                    <i class="ph ph-monitor-play"></i> Ver Top 10
                </a>

                <a href="/admin/exportar-top20" target="_blank" class="export-btn">
                    <i class="ph ph-file-pdf"></i> Exportar Top 20
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success d-flex align-items-center" style="background:#f0fdf4; border-color:#86efac; color:#166534; border-radius:12px; font-weight:500;">
                <i class="ph ph-check-circle me-2" style="font-size:1.5rem;"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger d-flex align-items-center" style="background:#fef2f2; border-color:#fca5a5; color:#991b1b; border-radius:12px; font-weight:500;">
                <i class="ph ph-warning me-2" style="font-size:1.5rem;"></i> {{ session('error') }}
            </div>
        @endif

        <!-- TARJETAS DE MÉTRICAS (KPIs) -->
        <div class="kpi-grid">
            <div class="kpi-card">
                <div class="kpi-icon-wrap kpi-blue">
                    <i class="ph ph-users-three"></i>
                </div>
                <div class="kpi-info">
                    <h4>Total Inscritos</h4>
                    <p>{{ $totalInscritos }}</p>
                </div>
            </div>
            <div class="kpi-card">
                <div class="kpi-icon-wrap kpi-blue">
                    <i class="ph ph-clipboard"></i>
                </div>
                <div class="kpi-info">
                    <h4>Pendientes de Inicio</h4>
                    <p>{{ $inscritosNoIniciados }}</p>
                </div>
            </div>
            <div class="kpi-card">
                <div class="kpi-icon-wrap kpi-yellow">
                    <i class="ph ph-activity"></i>
                </div>
                <div class="kpi-info">
                    <h4>En Progreso</h4>
                    <p>{{ $equiposActivos }}</p>
                </div>
            </div>
            <div class="kpi-card">
                <div class="kpi-icon-wrap kpi-green">
                    <i class="ph ph-flag-checkered"></i>
                </div>
                <div class="kpi-info">
                    <h4>Completados</h4>
                    <p>{{ $equiposFinalizados }}</p>
                </div>
            </div>
            <div class="kpi-card">
                <div class="kpi-icon-wrap kpi-purple">
                    <i class="ph ph-timer"></i>
                </div>
                <div class="kpi-info">
                    <h4>Promedio Éxito</h4>
                    <p>
                        @if($tiempoMedioSegundos > 0)
                            {{ floor($tiempoMedioSegundos / 60) }}<span style="font-size:1rem; color:var(--text-muted)">m</span>
                        @else
                            --
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- NUEVA SECCIÓN: INSCRIPCIONES PENDIENTES -->
        @if($inscritosNoIniciados > 0)
        <div class="glass-panel" style="margin-bottom: 2.5rem; border-left: 4px solid #3b82f6;">
            <div class="panel-heading"><i class="ph ph-clipboard-text"></i> Equipos Inscritos (Sin Iniciar)</div>
            <div style="max-height: 400px; overflow-y: auto; border-radius: 0 0 12px 12px;">
                <div class="podium-list" style="margin: 0;">
                    @foreach($inscripcionesPendientes as $insc)
                    <div class="podium-item" style="background: linear-gradient(135deg, #f0f9ff, #ffffff); border-left: 3px solid #3b82f6; display: flex; align-items: center; padding: 12px 16px; border-bottom: 1px solid #e0e7ff; margin: 0;">
                        <div class="pos-badge" style="background: #dbeafe; color: #3b82f6; box-shadow: none;">
                            {{ strtoupper(substr($insc->nombre_equipo, 0, 1)) }}
                        </div>
                        <div style="flex: 1; min-width: 0; margin: 0 12px;">
                            <div style="font-weight: 600; color: var(--text-main); margin-bottom: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $insc->nombre_equipo }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">
                                {{ $insc->correo }} | {{ $insc->clase }}
                            </div>
                        </div>
                        <div style="font-size: 0.75rem; color: #64748b; white-space: nowrap; margin-right: 8px;">
                            {{ $insc->created_at->diffForHumans() }}
                        </div>
                        <form action="/admin/inscripcion/{{ $insc->id }}" method="POST" onsubmit="return confirm('¿Eliminar inscripción de {{ $insc->nombre_equipo }}?');" style="margin:0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-action" title="Eliminar inscripción" style="background: #fee2e2; color: #dc2626;">
                                <i class="ph ph-trash"></i>
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- GRÁFICO Y PODIUM -->
        <div class="mid-grid">
            <!-- Gráfico Donut de Fases -->
            <div class="glass-panel">
                <div class="panel-heading"><i class="ph ph-chart-pie-slice"></i> Distribución por Fases</div>
                <div class="chart-container">
                    @if($totalEquipos > 0)
                        <canvas id="fasesChart"></canvas>
                    @else
                        <div style="display:flex; align-items:center; justify-content:center; height:100%; color:#94a3b8; font-style:italic;">
                            No hay datos suficientes
                        </div>
                    @endif
                </div>
            </div>

            <!-- Top 5 Podium Rápido -->
            <div class="glass-panel">
                <div class="panel-heading"><i class="ph ph-trophy"></i> Salón de la Fama (Top 5)</div>
                @if($top5->isNotEmpty())
                    <div class="podium-list">
                        @foreach($top5 as $index => $ranking)
                            <div class="podium-item">
                                <div class="pos-badge {{ $index == 0 ? 'pos-1' : ($index == 1 ? 'pos-2' : ($index == 2 ? 'pos-3' : 'pos-other')) }}">
                                    {{ $index + 1 }}
                                </div>
                                <div class="podium-details">
                                    <div class="podium-name">{{ $ranking->nombre_equipo }}</div>
                                    <div class="podium-class">{{ $ranking->clase }}</div>
                                </div>
                                <div class="podium-time">
                                    {{ floor((int)($ranking->tiempo_segundos ?? 0) / 60) }}m {{ str_pad((int)($ranking->tiempo_segundos ?? 0) % 60, 2, '0', STR_PAD_LEFT) }}s
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="text-align:center; padding: 2rem; color:#94a3b8;">
                        <i class="ph ph-medal" style="font-size:3rem; opacity:0.5; margin-bottom:1rem;"></i>
                        <p>Aún no hay equipos que hayan escapado del sistema.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- TABLA PRINCIPAL DE EQUIPOS -->
        <h2 style="font-size:1.3rem; font-weight:700; margin-bottom:1.2rem; color:var(--text-main);">Listado Completo de Equipos</h2>

        <div class="search-wrapper">
            <i class="ph ph-magnifying-glass"></i>
            <input type="text" id="searchInput" class="search-input" placeholder="Buscar por nombre, correo o clase..." onkeyup="filterTable()">
        </div>

        <div class="table-responsive">
            <table class="table-custom" id="mainTable">
                <thead>
                    <tr>
                        <th style="width: 5%">ID</th>
                        <th style="width: 35%">Equipo & Contacto</th>
                        <th style="width: 15%">Clase</th>
                        <th style="width: 15%">Estado / Progreso</th>
                        <th style="width: 10%">Tiempo</th>
                        <th style="width: 10%">Pistas</th>
                        <th style="width: 10%; text-align:center;">Borrar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($partidas as $index => $partida)
                    <tr class="team-row">
                        <td style="color:var(--text-muted); font-weight:600;">#{{ str_pad($partida->id, 3, '0', STR_PAD_LEFT) }}</td>
                        <td>
                            <div class="team-cell">
                                <div class="avatar">
                                    {{ strtoupper(substr($partida->nombre_equipo, 0, 1)) }}
                                </div>
                                <div class="team-meta">
                                    <div class="t-name">{{ $partida->nombre_equipo }}</div>
                                    <div class="t-email">{{ $partida->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td><span style="background:#f1f5f9; padding:4px 10px; border-radius:6px; font-size:0.85rem; font-weight:600; color:var(--text-muted);">{{ $partida->clase }}</span></td>
                        <td>
                            @if($partida->fase_actual === 'FINALIZADO')
                                <span class="status-pill status-success"><i class="ph-fill ph-check-circle"></i> COMPLETADO</span>
                            @elseif($partida->fase_actual === 'FINALIZADO_TIMEOUT')
                                <span class="status-pill status-danger"><i class="ph-fill ph-clock"></i> TIMEOUT</span>
                            @else
                                <span class="status-pill status-warning"><i class="ph-fill ph-spinner-gap" style="animation: spin 2s linear infinite;"></i> {{ strtoupper($partida->fase_actual) }}</span>
                            @endif
                        </td>
                        <td class="time-display">
                            @if($partida->fase_actual === 'FINALIZADO' || $partida->fase_actual === 'FINALIZADO_TIMEOUT')
                                @php
                                    $colorClass = $partida->fase_actual === 'FINALIZADO' ? 'text-success' : 'text-danger';
                                @endphp
                                <span class="{{ $colorClass }}">
                                    {{ floor((int)($partida->tiempo_segundos ?? 0) / 60) }}m {{ str_pad((int)($partida->tiempo_segundos ?? 0) % 60, 2, '0', STR_PAD_LEFT) }}s
                                </span>
                            @else
                                @if($partida->inicio_juego)
                                    @php
                                        $segs = abs(now()->diffInSeconds(\Carbon\Carbon::parse($partida->inicio_juego)));
                                    @endphp
                                    <span class="text-live">{{ floor($segs / 60) }}m {{ str_pad($segs % 60, 2, '0', STR_PAD_LEFT) }}s</span>
                                @else
                                    <span style="color:var(--text-muted)">--:--</span>
                                @endif
                            @endif
                        </td>
                        <td style="text-align:center; font-weight:600;">
                            @php $pistasUsadas = \App\Models\PistaUsada::where('partida_id', $partida->id)->count(); @endphp
                            @if($pistasUsadas > 0)
                                <span style="background:#fef08a; color:#854d0e; padding:4px 8px; border-radius:6px; font-size:0.85rem;">💡 {{ $pistasUsadas }}</span>
                            @else
                                <span style="color:var(--text-muted); font-size:0.85rem;">0</span>
                            @endif
                        </td>
                        <td style="text-align:center;">
                            <form action="/admin/equipo/{{ $partida->uuid }}" method="POST" onsubmit="return confirm('¿Borrar permanentemente el progreso de {{ $partida->nombre_equipo }}?');" style="margin:0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action" title="Eliminar del sistema">
                                    <i class="ph ph-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center; padding: 4rem 2rem; color:var(--text-muted);">
                            <i class="ph ph-ghost" style="font-size:3rem; opacity:0.3; margin-bottom:1rem; display:block;"></i>
                            Sistema vacío. Esperando a que los alumnos se registren.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
@push('scripts')
<script>
    // Auto recarga cada 10 segundos
    setTimeout(() => {
        window.location.reload();
    }, 10000);
</script>
@endpush
@endsection

@push('scripts')
    <!-- Chart.js para la Gráfica -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Chart.js para la Gráfica -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        let myChart = null;

        // Buscador reactivo
        function filterTable() {
            let filter = document.getElementById("searchInput").value.toLowerCase();
            let rows = document.querySelectorAll(".team-row");

            rows.forEach(row => {
                let textContent = row.innerText.toLowerCase();
                row.style.display = textContent.includes(filter) ? "" : "none";
            });
        }

        async function refreshDashboard() {
            try {
                const response = await fetch('/admin/api/stats');
                const data = await response.json();

                // 1. Actualizar KPIs
                updateKPIs(data.metrics);

                // 2. Actualizar Tabla
                updateTable(data.partidas);

                // 3. Actualizar Gráfica
                updateChart(data.chart);

                // 4. Actualizar Inscripciones
                const inscripcionesRes = await fetch('/admin/api/inscripciones');
                const inscripcionesData = await inscripcionesRes.json();
                updateKeysInscritos(inscripcionesData);

            } catch (error) {
                console.error("Error refrescando dashboard:", error);
            }
        }

        function updateKeysInscritos(data) {
            // Actualizar KPIs de inscripción
            const cards = document.querySelectorAll('.kpi-card');
            if (cards.length >= 2) {
                cards[0].querySelector('p').innerText = data.total_inscritos;
                cards[1].querySelector('p').innerText = data.no_iniciados;
            }

            // Actualizar panel de inscripciones pendientes
            const inscripcionesPanel = document.querySelector('[style*="border-left: 4px solid"]');
            if (inscripcionesPanel) {
                if (data.no_iniciados > 0) {
                    const listContainer = inscripcionesPanel.querySelector('.podium-list');
                    if (listContainer) {
                        let html = '';
                        data.inscripciones_pendientes.forEach(insc => {
                            const csrfToken = document.querySelector('meta[name=csrf-token]').content;
                            html += `
                            <div class="podium-item" style="background: linear-gradient(135deg, #f0f9ff, #ffffff); border-left: 3px solid #3b82f6; display: flex; align-items: center; padding: 12px 16px; border-bottom: 1px solid #e0e7ff; margin: 0;">
                                <div class="pos-badge" style="background: #dbeafe; color: #3b82f6; box-shadow: none;">
                                    ${insc.avatar}
                                </div>
                                <div style="flex: 1; min-width: 0; margin: 0 12px;">
                                    <div style="font-weight: 600; color: var(--text-main); margin-bottom: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${insc.nombre_equipo}</div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);">
                                        ${insc.correo} | ${insc.clase}
                                    </div>
                                </div>
                                <div style="font-size: 0.75rem; color: #64748b; white-space: nowrap; margin-right: 8px;">
                                    ${insc.tiempo_inscripcion}
                                </div>
                                <form action="/admin/inscripcion/${insc.id}" method="POST" onsubmit="return confirm('¿Eliminar inscripción de ${insc.nombre_equipo}?');" style="margin:0;">
                                    <input type="hidden" name="_token" value="${csrfToken}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn-action" title="Eliminar inscripción" style="background: #fee2e2; color: #dc2626;">
                                        <i class="ph ph-trash"></i>
                                    </button>
                                </form>
                            </div>`;
                        });
                        listContainer.innerHTML = html;
                        inscripcionesPanel.style.display = 'block';
                    }
                } else {
                    // Ocultar panel si no hay inscritos pendientes
                    inscripcionesPanel.style.display = 'none';
                }
            }
        }

        function updateKPIs(metrics) {
            // Buscamos los nodos por su estructura de KPI
            const cards = document.querySelectorAll('.kpi-card');
            // Actualizar solo los que corresponden de las métricas en tiempo real
            if (cards.length >= 4) {
                cards[2].querySelector('p').innerText = metrics.activos;
                cards[3].querySelector('p').innerText = metrics.finalizados;
            }
        }

        function updateTable(partidas) {
            const tbody = document.querySelector('#mainTable tbody');
            const searchVal = document.getElementById("searchInput").value.toLowerCase();

            // Reconstruimos filas (más seguro para mantener el orden dinámico)
            let newHtml = '';

            if (partidas.length === 0) {
                newHtml = `<tr><td colspan="6" style="text-align:center; padding: 4rem 2rem; color:var(--text-muted);"><i class="ph ph-ghost" style="font-size:3rem; opacity:0.3; margin-bottom:1rem; display:block;"></i>Sistema vacío.</td></tr>`;
            } else {
                partidas.forEach(p => {
                    const isVisible = (p.nombre_equipo + p.email + p.clase).toLowerCase().includes(searchVal);
                    const displayStyle = isVisible ? '' : 'style="display:none"';

                    let statusHtml = '';
                    if (p.fase_actual === 'FINALIZADO') {
                        statusHtml = '<span class="status-pill status-success"><i class="ph-fill ph-check-circle"></i> COMPLETADO</span>';
                    } else if (p.fase_actual === 'FINALIZADO_TIMEOUT') {
                        statusHtml = '<span class="status-pill status-danger"><i class="ph-fill ph-clock"></i> TIMEOUT</span>';
                    } else {
                        statusHtml = `<span class="status-pill status-warning"><i class="ph-fill ph-spinner-gap" style="animation: spin 2s linear infinite;"></i> ${p.fase_actual.toUpperCase()}</span>`;
                    }

                    let timeHtml = '';
                    if (p.fase_actual === 'FINALIZADO' || p.fase_actual === 'FINALIZADO_TIMEOUT') {
                        const color = p.fase_actual === 'FINALIZADO' ? 'var(--success-color)' : 'var(--danger-color)';
                        timeHtml = `<span style="color:${color}">${Math.floor(p.tiempo_segundos / 60)}m ${Math.floor(p.tiempo_segundos % 60).toString().padStart(2, '0')}s</span>`;
                    } else {
                        timeHtml = `<span class="text-live">${Math.floor(p.tiempo_live / 60)}m ${Math.floor(p.tiempo_live % 60).toString().padStart(2, '0')}s</span>`;
                    }

                    newHtml += `
                    <tr class="team-row" ${displayStyle}>
                        <td style="color:var(--text-muted); font-weight:600;">#${p.id}</td>
                        <td>
                            <div class="team-cell">
                                <div class="avatar">${p.avatar}</div>
                                <div class="team-meta">
                                    <div class="t-name">${p.nombre_equipo}</div>
                                    <div class="t-email">${p.email}</div>
                                </div>
                            </div>
                        </td>
                        <td><span style="background:#f1f5f9; padding:4px 10px; border-radius:6px; font-size:0.85rem; font-weight:600; color:var(--text-muted);">${p.clase}</span></td>
                        <td>${statusHtml}</td>
                        <td class="time-display">${timeHtml}</td>
                        <td style="text-align:center; font-weight:600;">
                            ${p.pistas_count > 0
                                ? `<span style="background:#fef08a; color:#854d0e; padding:4px 8px; border-radius:6px; font-size:0.85rem;">💡 ${p.pistas_count}</span>`
                                : `<span style="color:var(--text-muted); font-size:0.85rem;">0</span>`}
                        </td>
                        <td style="text-align:center;">
                            <form action="/admin/equipo/${p.uuid}" method="POST" onsubmit="return confirm('¿Borrar permanentemente?');" style="margin:0;">
                                <input type="hidden" name="_token" value="${document.querySelector('meta[name=csrf-token]').content}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn-action"><i class="ph ph-trash"></i></button>
                            </form>
                        </td>
                    </tr>`;
                });
            }
            tbody.innerHTML = newHtml;
        }

        function updateChart(chartData) {
            if (!myChart) {
                const ctx = document.getElementById('fasesChart').getContext('2d');
                myChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: chartData.labels.map(l => l.replace('FINALIZADO_TIMEOUT', 'Timeout').replace('FINALIZADO', 'Éxito')),
                        datasets: [{
                            data: chartData.data,
                            backgroundColor: ['#10b981', '#f59e0b', '#3b82f6', '#8b5cf6', '#ef4444'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { position: 'right', labels: { usePointStyle: true, boxWidth: 8 } } },
                        cutout: '70%'
                    }
                });
            } else {
                myChart.data.labels = chartData.labels.map(l => l.replace('FINALIZADO_TIMEOUT', 'Timeout').replace('FINALIZADO', 'Éxito'));
                myChart.data.datasets[0].data = chartData.data;
                myChart.update();
            }
        }

        // Inicializar y Polling
        document.addEventListener('DOMContentLoaded', () => {
            refreshDashboard(); // Carga inicial
            setInterval(refreshDashboard, 8000); // Cada 8 segundos
        });
    </script>
@endpush
