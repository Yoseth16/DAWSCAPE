@extends('layouts.admin')

@section('title', 'Admin Dashboard Premium | DAWSCAPE')

@push('admin-styles')
    @vite(['resources/css/admin.css'])
@endpush

@section('admin-content')
        <div class="page-header">
            <div>
                <h1 class="page-title">Visión General</h1>
                <div class="page-subtitle">Monitorización en tiempo real de la actividad del Escape Room.</div>
            </div>
            <div>
                <a href="/admin/exportar-top20" target="_blank" class="export-btn">
                    <i class="ph ph-file-pdf"></i> Exportar Ranking Top 20
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
                    <h4>Equipos Inscritos</h4>
                    <p>{{ $totalEquipos }}</p>
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
                                    {{ floor((int)$ranking->tiempo_segundos / 60) }}m {{ str_pad((int)$ranking->tiempo_segundos % 60, 2, '0', STR_PAD_LEFT) }}s
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
                        <th style="width: 20%">Estado / Progreso</th>
                        <th style="width: 15%">Tiempo</th>
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
                                <span style="color:{{ $partida->fase_actual === 'FINALIZADO' ? 'var(--success-color)' : 'var(--danger-color)' }}">
                                    {{ floor((int)$partida->tiempo_segundos / 60) }}m {{ str_pad((int)$partida->tiempo_segundos % 60, 2, '0', STR_PAD_LEFT) }}s
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

            } catch (error) {
                console.error("Error refrescando dashboard:", error);
            }
        }

        function updateKPIs(metrics) {
            // Buscamos los nodos por su estructura de KPI
            const cards = document.querySelectorAll('.kpi-card');
            cards[0].querySelector('p').innerText = metrics.total;
            cards[1].querySelector('p').innerText = metrics.activos;
            cards[2].querySelector('p').innerText = metrics.finalizados;
            cards[3].querySelector('p').innerText = metrics.promedio;
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
                        timeHtml = `<span style="color:${color}">${Math.floor(p.tiempo_segundos / 60)}m ${(p.tiempo_segundos % 60).toString().padStart(2, '0')}s</span>`;
                    } else {
                        timeHtml = `<span class="text-live">${Math.floor(p.tiempo_live / 60)}m ${(p.tiempo_live % 60).toString().padStart(2, '0')}s</span>`;
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