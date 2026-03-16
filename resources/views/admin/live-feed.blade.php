@extends('layouts.admin')

@push('admin-styles')
<style>
    /* ========== TERMINAL HACKER THEME ========== */
    .main-wrapper {
        background: #0a0a0a !important;
        padding: 1.5rem 2rem !important;
    }

    .terminal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #1a3a1a;
    }

    .terminal-header .title-block {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .terminal-header .title-block h1 {
        font-family: 'JetBrains Mono', 'Fira Code', 'Cascadia Code', monospace;
        color: #00ff41;
        font-size: 1.4rem;
        font-weight: 700;
        margin: 0;
        text-shadow: 0 0 10px rgba(0, 255, 65, 0.3);
    }

    .terminal-header .status-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #00ff41;
        box-shadow: 0 0 8px #00ff41;
        animation: pulse-dot 2s ease-in-out infinite;
    }

    @keyframes pulse-dot {
        0%, 100% { opacity: 1; box-shadow: 0 0 8px #00ff41; }
        50% { opacity: 0.5; box-shadow: 0 0 4px #00ff41; }
    }

    .terminal-header .connection-badge {
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.7rem;
        padding: 4px 10px;
        border-radius: 4px;
        border: 1px solid #333;
        color: #666;
        background: #111;
        transition: all 0.3s;
    }

    .terminal-header .connection-badge.connected {
        border-color: #00ff41;
        color: #00ff41;
        background: rgba(0, 255, 65, 0.05);
    }

    .terminal-header .connection-badge.disconnected {
        border-color: #ff4141;
        color: #ff4141;
        background: rgba(255, 65, 65, 0.05);
    }

    /* ========== LAYOUT GRID ========== */
    .live-grid {
        display: grid;
        grid-template-columns: 1fr 420px;
        gap: 1.5rem;
        height: calc(100vh - 140px);
    }

    /* ========== LOG PANEL ========== */
    .log-panel {
        background: #0d0d0d;
        border: 1px solid #1a3a1a;
        border-radius: 8px;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .panel-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 16px;
        background: #111;
        border-bottom: 1px solid #1a3a1a;
        flex-shrink: 0;
    }

    .panel-bar .bar-title {
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.75rem;
        color: #00ff41;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .panel-bar .bar-title .count-badge {
        background: rgba(0, 255, 65, 0.1);
        color: #00ff41;
        padding: 2px 8px;
        border-radius: 10px;
        font-size: 0.65rem;
        border: 1px solid rgba(0, 255, 65, 0.2);
    }

    .panel-bar .bar-actions {
        display: flex;
        gap: 8px;
    }

    .panel-bar .bar-actions button {
        background: transparent;
        border: 1px solid #333;
        color: #666;
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.65rem;
        padding: 3px 10px;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .panel-bar .bar-actions button:hover {
        border-color: #00ff41;
        color: #00ff41;
    }

    .log-container {
        flex: 1;
        overflow-y: auto;
        padding: 12px 16px;
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.78rem;
        line-height: 1.7;
    }

    .log-container::-webkit-scrollbar { width: 6px; }
    .log-container::-webkit-scrollbar-track { background: #0d0d0d; }
    .log-container::-webkit-scrollbar-thumb { background: #1a3a1a; border-radius: 3px; }

    .log-entry {
        display: flex;
        gap: 10px;
        padding: 4px 0;
        border-bottom: 1px solid rgba(255,255,255,0.02);
        animation: fadeIn 0.3s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-5px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .log-entry .log-time {
        color: #444;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .log-entry .log-tag {
        font-weight: 700;
        white-space: nowrap;
        flex-shrink: 0;
        padding: 0 4px;
        border-radius: 2px;
    }

    .log-entry .log-tag.ok      { color: #00ff41; background: rgba(0,255,65,0.05); }
    .log-entry .log-tag.fail    { color: #ff4141; background: rgba(255,65,65,0.05); }
    .log-entry .log-tag.warn    { color: #ffaa00; background: rgba(255,170,0,0.05); }
    .log-entry .log-tag.info    { color: #41aaff; background: rgba(65,170,255,0.05); }
    .log-entry .log-tag.system  { color: #888; background: rgba(136,136,136,0.05); }

    .log-entry .log-msg { color: #ccc; }
    .log-entry .log-msg .team-name { color: #60a5fa; font-weight: 600; }
    .log-entry .log-msg .phase-name { color: #a78bfa; }
    .log-entry .log-msg .time-val { color: #fbbf24; }

    .log-empty {
        color: #333;
        text-align: center;
        padding: 3rem;
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.8rem;
    }

    .log-empty .cursor-blink {
        animation: blink 1s step-end infinite;
    }

    @keyframes blink {
        50% { opacity: 0; }
    }

    /* ========== CONTROL PANEL ========== */
    .control-panel {
        background: #0d0d0d;
        border: 1px solid #1a3a1a;
        border-radius: 8px;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .teams-list {
        flex: 1;
        overflow-y: auto;
        padding: 8px;
    }

    .teams-list::-webkit-scrollbar { width: 6px; }
    .teams-list::-webkit-scrollbar-track { background: #0d0d0d; }
    .teams-list::-webkit-scrollbar-thumb { background: #1a3a1a; border-radius: 3px; }

    .team-card {
        background: #111;
        border: 1px solid #1a1a1a;
        border-radius: 6px;
        padding: 12px;
        margin-bottom: 8px;
        transition: border-color 0.2s;
    }

    .team-card:hover {
        border-color: #2a4a2a;
    }

    .team-card .team-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 8px;
    }

    .team-card .team-info {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .team-card .team-icon {
        font-size: 1.2rem;
    }

    .team-card .team-name {
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.8rem;
        font-weight: 700;
        color: #e2e8f0;
    }

    .team-card .team-class {
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.6rem;
        color: #555;
        padding: 2px 6px;
        border: 1px solid #222;
        border-radius: 3px;
    }

    .team-card .team-phase {
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.65rem;
        color: #a78bfa;
        margin-bottom: 4px;
    }

    .team-card .team-time {
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.65rem;
        color: #666;
        margin-bottom: 10px;
    }

    .team-card .team-time .elapsed {
        color: #fbbf24;
        font-weight: 600;
    }

    .team-card .team-time .penalty {
        color: #ff4141;
    }

    .team-card .team-actions {
        display: flex;
        gap: 6px;
    }

    .btn-penalty, .btn-bonus {
        flex: 1;
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.68rem;
        font-weight: 600;
        padding: 7px 0;
        border: 1px solid;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s;
        text-align: center;
    }

    .btn-penalty {
        background: rgba(255, 65, 65, 0.05);
        border-color: rgba(255, 65, 65, 0.3);
        color: #ff6b6b;
    }

    .btn-penalty:hover {
        background: rgba(255, 65, 65, 0.15);
        border-color: #ff4141;
        color: #ff4141;
    }

    .btn-bonus {
        background: rgba(0, 255, 65, 0.05);
        border-color: rgba(0, 255, 65, 0.3);
        color: #00cc33;
    }

    .btn-bonus:hover {
        background: rgba(0, 255, 65, 0.15);
        border-color: #00ff41;
        color: #00ff41;
    }

    .btn-penalty:disabled, .btn-bonus:disabled {
        opacity: 0.3;
        cursor: not-allowed;
    }

    .no-teams {
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.75rem;
        color: #333;
        text-align: center;
        padding: 3rem 1rem;
    }

    /* ========== TOAST FEEDBACK ========== */
    .toast-container {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .toast-msg {
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.72rem;
        padding: 10px 16px;
        border-radius: 6px;
        background: #111;
        border: 1px solid #333;
        color: #ccc;
        animation: slideIn 0.3s ease-out, fadeOut 0.3s ease-in 3.7s forwards;
        max-width: 360px;
    }

    .toast-msg.success { border-color: #00ff41; color: #00ff41; }
    .toast-msg.error { border-color: #ff4141; color: #ff4141; }

    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }

    @keyframes fadeOut {
        to { opacity: 0; transform: translateY(10px); }
    }

    /* ========== RESPONSIVE ========== */
    @media (max-width: 1100px) {
        .live-grid {
            grid-template-columns: 1fr;
            grid-template-rows: 1fr 1fr;
        }
    }
</style>
@endpush

@section('admin-content')

<div class="terminal-header">
    <div class="title-block">
        <div class="status-dot" id="statusDot"></div>
        <h1>> LIVE FEED & CONTROL_</h1>
    </div>
    <div class="connection-badge disconnected" id="connectionBadge">CONNECTING...</div>
</div>

<div class="live-grid">
    {{-- ===== LEFT: EVENT LOG ===== --}}
    <div class="log-panel">
        <div class="panel-bar">
            <div class="bar-title">
                <i class="ph ph-terminal-window"></i>
                Event Stream
                <span class="count-badge" id="logCount">0</span>
            </div>
            <div class="bar-actions">
                <button onclick="toggleAutoScroll()" id="btnAutoScroll" title="Auto-scroll">▼ AUTO</button>
                <button onclick="clearLog()" title="Limpiar log">&times; CLEAR</button>
            </div>
        </div>
        <div class="log-container" id="logContainer">
            <div class="log-empty">
                Esperando eventos en tiempo real<span class="cursor-blink">_</span>
            </div>
        </div>
    </div>

    {{-- ===== RIGHT: TEAM CONTROL ===== --}}
    <div class="control-panel">
        <div class="panel-bar">
            <div class="bar-title">
                <i class="ph ph-game-controller"></i>
                Equipos en Progreso
                <span class="count-badge" id="teamsCount">{{ count($equiposActivos) }}</span>
            </div>
            <div class="bar-actions">
                <button onclick="refreshTeams()" title="Refrescar">↻ SYNC</button>
            </div>
        </div>
        <div class="teams-list" id="teamsList">
            @if(count($equiposActivos) === 0)
                <div class="no-teams" id="noTeamsMsg">Sin equipos activos en este momento.</div>
            @endif
            @foreach($equiposActivos as $equipo)
                @include('admin.partials._team-card', ['equipo' => $equipo])
            @endforeach
        </div>
    </div>
</div>

<div class="toast-container" id="toastContainer"></div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function() {
    'use strict';

    // ========== STATE ==========
    let logCount = 0;
    let autoScroll = true;
    const MAX_LOG_ENTRIES = 500;
    const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const fasesMap = @json(collect(config('fases.fases'))->mapWithKeys(fn($v, $k) => [$v['db'] => $v['nombre']]));

    // ========== HELPERS ==========
    function now() {
        const d = new Date();
        return String(d.getHours()).padStart(2,'0') + ':' +
               String(d.getMinutes()).padStart(2,'0') + ':' +
               String(d.getSeconds()).padStart(2,'0');
    }

    function fmtTime(secs) {
        const m = Math.floor(secs / 60);
        const s = secs % 60;
        return m + 'm ' + String(s).padStart(2,'0') + 's';
    }

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    // ========== LOG ==========
    function appendLog(tag, tagClass, message) {
        const container = document.getElementById('logContainer');
        const empty = container.querySelector('.log-empty');
        if (empty) empty.remove();

        const entry = document.createElement('div');
        entry.className = 'log-entry';
        entry.innerHTML =
            '<span class="log-time">[' + now() + ']</span>' +
            '<span class="log-tag ' + tagClass + '">' + escapeHtml(tag) + '</span>' +
            '<span class="log-msg">' + message + '</span>';

        container.appendChild(entry);
        logCount++;
        document.getElementById('logCount').textContent = logCount;

        // Trim old entries
        const entries = container.querySelectorAll('.log-entry');
        if (entries.length > MAX_LOG_ENTRIES) {
            entries[0].remove();
        }

        if (autoScroll) {
            container.scrollTop = container.scrollHeight;
        }
    }

    window.toggleAutoScroll = function() {
        autoScroll = !autoScroll;
        const btn = document.getElementById('btnAutoScroll');
        btn.style.color = autoScroll ? '#00ff41' : '#666';
        btn.style.borderColor = autoScroll ? '#00ff41' : '#333';
    };

    window.clearLog = function() {
        const container = document.getElementById('logContainer');
        container.innerHTML = '<div class="log-empty">Log limpiado. Esperando eventos<span class="cursor-blink">_</span></div>';
        logCount = 0;
        document.getElementById('logCount').textContent = '0';
    };

    // ========== TOAST ==========
    function toast(msg, type) {
        const container = document.getElementById('toastContainer');
        const el = document.createElement('div');
        el.className = 'toast-msg ' + (type || '');
        el.textContent = msg;
        container.appendChild(el);
        setTimeout(() => el.remove(), 4000);
    }

    // ========== TEAM RENDERING ==========
    function renderTeamCard(eq) {
        const faseNombre = fasesMap[eq.fase_actual] || eq.fase_actual;
        const icon = eq.icono || '🎮';
        const color = eq.color_hex || '#3b82f6';
        const elapsed = fmtTime(eq.tiempo_elapsed);
        const penalty = eq.penalizacion_segundos || 0;

        return '<div class="team-card" id="team-' + escapeHtml(eq.uuid) + '" style="border-left: 3px solid ' + escapeHtml(color) + '">' +
            '<div class="team-header">' +
                '<div class="team-info">' +
                    '<span class="team-icon">' + icon + '</span>' +
                    '<span class="team-name">' + escapeHtml(eq.nombre_equipo) + '</span>' +
                '</div>' +
                '<span class="team-class">' + escapeHtml(eq.clase || '—') + '</span>' +
            '</div>' +
            '<div class="team-phase">► ' + escapeHtml(faseNombre) + '</div>' +
            '<div class="team-time">⏱ <span class="elapsed">' + elapsed + '</span>' +
                (penalty > 0 ? '  |  ⚠ <span class="penalty">+' + fmtTime(penalty) + ' pen.</span>' : '') +
            '</div>' +
            '<div class="team-actions">' +
                '<button class="btn-penalty" onclick="ajustarTiempo(\'' + escapeHtml(eq.uuid) + '\', 300, this)" title="Añadir 5 min de penalización">+5m PEN</button>' +
                '<button class="btn-bonus" onclick="ajustarTiempo(\'' + escapeHtml(eq.uuid) + '\', -300, this)" title="Restar 5 min (bonus/pista)">−5m BONUS</button>' +
            '</div>' +
        '</div>';
    }

    window.refreshTeams = function() {
        fetch('/admin/api/equipos-activos', {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
        })
        .then(r => r.json())
        .then(data => {
            const list = document.getElementById('teamsList');
            document.getElementById('teamsCount').textContent = data.length;

            if (data.length === 0) {
                list.innerHTML = '<div class="no-teams">Sin equipos activos en este momento.</div>';
                return;
            }

            list.innerHTML = data.map(renderTeamCard).join('');
        })
        .catch(err => {
            console.error('Error refreshing teams:', err);
            toast('Error actualizando equipos', 'error');
        });
    };

    // ========== AJAX ACTIONS ==========
    window.ajustarTiempo = function(uuid, segundos, btn) {
        if (btn) btn.disabled = true;

        fetch('/admin/live/ajustar-tiempo', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': CSRF
            },
            body: JSON.stringify({ uuid: uuid, segundos: segundos })
        })
        .then(r => r.json())
        .then(data => {
            if (data.ok) {
                const tipo = segundos > 0 ? 'PENALIZACIÓN' : 'BONUS';
                const label = segundos > 0 ? '+' + fmtTime(segundos) : '−' + fmtTime(Math.abs(segundos));
                toast(tipo + ' ' + label + ' → ' + data.equipo, 'success');
                appendLog(tipo, segundos > 0 ? 'warn' : 'ok',
                    '<span class="team-name">' + escapeHtml(data.equipo) + '</span> ' +
                    label + ' (total pen: <span class="time-val">' + fmtTime(data.penalizacion_total) + '</span>)');
                refreshTeams();
            } else {
                toast('Error: no se pudo ajustar', 'error');
            }
        })
        .catch(err => {
            console.error(err);
            toast('Error de red al ajustar tiempo', 'error');
        })
        .finally(() => {
            if (btn) setTimeout(() => btn.disabled = false, 1000);
        });
    };

    // ========== WEBSOCKET CONNECTION ==========
    function initWebSockets() {
        if (typeof window.Echo === 'undefined') {
            document.getElementById('connectionBadge').textContent = 'WS OFFLINE';
            document.getElementById('connectionBadge').className = 'connection-badge disconnected';
            appendLog('SYS', 'system', 'WebSocket (Echo/Reverb) no disponible. Modo polling activado.');
            return;
        }

        // ---- Canal Admin global ----
        window.Echo.channel('admin')
            .listen('.AdminDashboardUpdated', function() {
                appendLog('SYNC', 'info', 'Dashboard actualizado — refrescando equipos...');
                refreshTeams();
            });

        // ---- Canal de actividad de juego (live-feed) ----
        window.Echo.channel('live-feed')
            .listen('.PruebaCompletada', function(e) {
                const faseNombre = fasesMap[e.fase] || e.fase;
                appendLog('OK', 'ok',
                    '<span class="team-name">' + escapeHtml(e.equipo) + '</span> completó ' +
                    '<span class="phase-name">' + escapeHtml(faseNombre) + '</span>' +
                    ' en <span class="time-val">' + fmtTime(e.tiempo || 0) + '</span>');
                refreshTeams();
            })
            .listen('.PruebaFallada', function(e) {
                appendLog('FAIL', 'fail',
                    '<span class="team-name">' + escapeHtml(e.equipo) + '</span> falló en ' +
                    '<span class="phase-name">' + escapeHtml(e.fase || '?') + '</span>' +
                    (e.detalle ? ' — ' + escapeHtml(e.detalle) : ''));
            })
            .listen('.EquipoTimeout', function(e) {
                appendLog('TIMEOUT', 'fail',
                    '<span class="team-name">' + escapeHtml(e.equipo) + '</span> — Tiempo agotado');
                refreshTeams();
            })
            .listen('.EquipoFinalizado', function(e) {
                appendLog('FIN', 'ok',
                    '<span class="team-name">' + escapeHtml(e.equipo) + '</span> ha completado el escape room en ' +
                    '<span class="time-val">' + fmtTime(e.tiempo || 0) + '</span>');
                refreshTeams();
            })
            .listen('.PistaUsada', function(e) {
                appendLog('HINT', 'warn',
                    '<span class="team-name">' + escapeHtml(e.equipo) + '</span> usó una pista en ' +
                    '<span class="phase-name">' + escapeHtml(e.fase || '?') + '</span>');
            })
            .listen('.TiempoAjustado', function(e) {
                const tipo = e.tipo === 'penalizacion' ? 'PEN' : 'BONUS';
                const tagClass = e.tipo === 'penalizacion' ? 'warn' : 'ok';
                appendLog(tipo, tagClass,
                    '<span class="team-name">' + escapeHtml(e.equipo || e.uuid) + '</span> — ' +
                    (e.segundos > 0 ? '+' : '−') + fmtTime(Math.abs(e.segundos)) +
                    ' (total: <span class="time-val">' + fmtTime(e.penalizacion_total) + '</span>)');
            });

        document.getElementById('connectionBadge').textContent = 'WS CONNECTED';
        document.getElementById('connectionBadge').className = 'connection-badge connected';
        appendLog('SYS', 'system', 'Conexión WebSocket establecida — escuchando canales admin + live-feed');
    }

    // ========== POLLING FALLBACK (cada 15s refresca equipos) ==========
    setInterval(function() {
        refreshTeams();
    }, 15000);

    // ========== STARTUP LOG ==========
    appendLog('SYS', 'system', 'DAWSCAPE Live Feed v1.0 inicializado');
    appendLog('SYS', 'system', 'Cargando {{ count($equiposActivos) }} equipo(s) activo(s)...');

    // ========== INIT ==========
    document.addEventListener('DOMContentLoaded', function() {
        initWebSockets();
    });

    // Init auto-scroll button state
    document.getElementById('btnAutoScroll').style.color = '#00ff41';
    document.getElementById('btnAutoScroll').style.borderColor = '#00ff41';

})();
</script>
@endpush

@endsection
