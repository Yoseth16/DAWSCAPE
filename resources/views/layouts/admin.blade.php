@extends('layouts.app')

@push('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link href="https://fonts.googleapis.com/css2?family=Share+Tech+Mono&family=Orbitron:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 260px;
            --primary-bg: #020c06;
            --surface-color: #041a0e;
            --text-main: #c8ffdf;
            --text-muted: #5a8a6e;
            --border-color: rgba(0, 255, 136, 0.15);
            --brand-color: #00ff88;
            --brand-accent: #00ff88;
            --success-color: #00ff88;
            --warning-color: #ffaa00;
            --danger-color: #ff2244;
            --neon-glow: 0 0 10px rgba(0, 255, 136, 0.4);
        }

        body {
            background-color: var(--primary-bg);
            color: var(--text-main);
            font-family: 'Share Tech Mono', monospace;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
            display: flex;
            min-height: 100vh;
        }

        /* CRT Scanlines */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: repeating-linear-gradient(0deg, transparent, transparent 2px, rgba(0,255,136,0.013) 2px, rgba(0,255,136,0.013) 4px);
            pointer-events: none;
            z-index: 2000;
        }

        /* --- SIDEBAR --- */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #010a04 0%, #041a0e 100%);
            color: var(--text-main);
            position: fixed;
            height: 100vh;
            left: 0;
            top: 0;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            border-right: 1px solid var(--border-color);
            box-shadow: 4px 0 20px rgba(0, 255, 136, 0.05);
        }

        .sidebar-header {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            text-align: center;
        }

        .sidebar-header h2 {
            font-family: 'Orbitron', sans-serif;
            font-weight: 900;
            margin: 0;
            letter-spacing: 3px;
            color: var(--brand-color);
            font-size: 1.5rem;
            text-shadow: 0 0 20px rgba(0, 255, 136, 0.6), 0 0 40px rgba(0, 255, 136, 0.2);
        }

        .sidebar-menu {
            flex: 1;
            padding: 1.5rem 0;
            overflow-y: auto;
        }

        /* Scrollbar cyberpunk */
        .sidebar-menu::-webkit-scrollbar { width: 4px; }
        .sidebar-menu::-webkit-scrollbar-track { background: transparent; }
        .sidebar-menu::-webkit-scrollbar-thumb { background: rgba(0, 255, 136, 0.3); border-radius: 2px; }

        .menu-label {
            padding: 0 1.5rem;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--text-muted);
            margin-bottom: 0.8rem;
            font-weight: 600;
        }

        .nav-item { list-style: none; margin-bottom: 0.25rem; }

        .nav-link {
            display: flex; align-items: center; padding: 0.75rem 1.5rem;
            color: #7ab897; font-weight: 500; transition: all 0.25s;
            text-decoration: none; gap: 12px; position: relative;
            font-size: 0.9rem;
        }

        .nav-link i { font-size: 1.2rem; color: #4a7a60; transition: all 0.25s; }

        .nav-link:hover, .nav-link.active {
            background: rgba(0, 255, 136, 0.06);
            color: var(--brand-color);
            text-shadow: 0 0 8px rgba(0, 255, 136, 0.4);
        }
        .nav-link:hover i, .nav-link.active i {
            color: var(--brand-color);
            filter: drop-shadow(0 0 4px rgba(0, 255, 136, 0.6));
        }

        .nav-link.active::before {
            content: ''; position: absolute; left: 0; top: 0; bottom: 0;
            width: 3px; background: var(--brand-color);
            box-shadow: 0 0 8px var(--brand-color);
        }

        .sidebar-footer {
            padding: 1.5rem;
            border-top: 1px solid var(--border-color);
        }

        .btn-logout {
            display: flex; align-items: center; justify-content: center; gap: 10px;
            width: 100%; padding: 0.8rem; background: rgba(255, 34, 68, 0.08);
            color: #ff6680; border: 1px solid rgba(255, 34, 68, 0.25);
            border-radius: 0; text-decoration: none; font-weight: 600;
            transition: all 0.3s; font-family: inherit;
        }
        .btn-logout:hover {
            background: rgba(255, 34, 68, 0.15);
            color: #ff2244;
            box-shadow: 0 0 12px rgba(255, 34, 68, 0.2);
            border-color: rgba(255, 34, 68, 0.5);
        }

        /* --- MAIN CONTENT --- */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            flex: 1;
            padding: 2.5rem 3rem;
            max-width: calc(100vw - var(--sidebar-width));
        }

        .page-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2.5rem; }
        .page-title {
            margin: 0;
            font-family: 'Orbitron', sans-serif;
            font-weight: 700;
            color: var(--brand-color);
            font-size: 1.6rem;
            text-shadow: 0 0 12px rgba(0, 255, 136, 0.3);
            letter-spacing: 2px;
        }
        .page-subtitle { color: var(--text-muted); margin-top: 5px; font-size: 0.85rem; letter-spacing: 1px; }

        .glass-panel {
            background: var(--surface-color);
            border-radius: 0;
            padding: 1.5rem;
            border: 1px solid var(--border-color);
            box-shadow: 0 0 15px rgba(0, 255, 136, 0.03), inset 0 0 30px rgba(0, 255, 136, 0.01);
            margin-bottom: 1.5rem;
            position: relative;
        }

        /* Bootstrap overrides for cyberpunk */
        .table { color: var(--text-main); border-color: var(--border-color); }
        .table > :not(caption) > * > * {
            background-color: transparent;
            border-bottom-color: var(--border-color);
            color: var(--text-main);
        }
        .table-hover > tbody > tr:hover > * {
            background-color: rgba(0, 255, 136, 0.04);
            color: var(--text-main);
        }
        .table thead th {
            color: var(--brand-color);
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 1.5px;
            border-bottom-color: var(--border-color);
        }

        .btn-primary, .btn-info {
            background: transparent;
            color: var(--brand-color);
            border: 1px solid var(--brand-color);
            border-radius: 0;
            font-family: inherit;
            transition: all 0.2s;
        }
        .btn-primary:hover, .btn-info:hover {
            background: rgba(0, 255, 136, 0.1);
            color: var(--brand-color);
            box-shadow: var(--neon-glow);
            border-color: var(--brand-color);
        }

        .btn-danger {
            background: transparent;
            color: var(--danger-color);
            border: 1px solid var(--danger-color);
            border-radius: 0;
            font-family: inherit;
        }
        .btn-danger:hover {
            background: rgba(255, 34, 68, 0.1);
            box-shadow: 0 0 10px rgba(255, 34, 68, 0.3);
            color: var(--danger-color);
            border-color: var(--danger-color);
        }

        .btn-warning {
            background: transparent;
            color: var(--warning-color);
            border: 1px solid var(--warning-color);
            border-radius: 0;
            font-family: inherit;
        }
        .btn-warning:hover {
            background: rgba(255, 170, 0, 0.1);
            box-shadow: 0 0 10px rgba(255, 170, 0, 0.3);
            color: var(--warning-color);
        }

        .btn-success {
            background: transparent;
            color: var(--success-color);
            border: 1px solid var(--success-color);
            border-radius: 0;
            font-family: inherit;
        }
        .btn-success:hover {
            background: rgba(0, 255, 136, 0.1);
            box-shadow: var(--neon-glow);
            color: var(--success-color);
        }

        .badge {
            border-radius: 0;
            font-family: inherit;
            font-weight: 500;
            letter-spacing: 0.5px;
        }
        .badge.bg-success { background: rgba(0, 255, 136, 0.15) !important; color: #00ff88; border: 1px solid rgba(0, 255, 136, 0.3); }
        .badge.bg-warning { background: rgba(255, 170, 0, 0.15) !important; color: #ffaa00; border: 1px solid rgba(255, 170, 0, 0.3); }
        .badge.bg-danger { background: rgba(255, 34, 68, 0.15) !important; color: #ff6680; border: 1px solid rgba(255, 34, 68, 0.3); }
        .badge.bg-info { background: rgba(0, 136, 255, 0.15) !important; color: #55aaff; border: 1px solid rgba(0, 136, 255, 0.3); }
        .badge.bg-primary { background: rgba(0, 255, 136, 0.1) !important; color: #00ff88; border: 1px solid rgba(0, 255, 136, 0.2); }
        .badge.bg-secondary { background: rgba(100, 116, 139, 0.15) !important; color: #94a3b8; border: 1px solid rgba(100, 116, 139, 0.3); }

        .form-control, .form-select {
            background: #010a04;
            border: 1px solid var(--border-color);
            color: var(--text-main);
            border-radius: 0;
            font-family: inherit;
        }
        .form-control:focus, .form-select:focus {
            background: #010a04;
            border-color: var(--brand-color);
            color: var(--text-main);
            box-shadow: 0 0 8px rgba(0, 255, 136, 0.2);
        }

        .card {
            background: var(--surface-color);
            border: 1px solid var(--border-color);
            border-radius: 0;
            color: var(--text-main);
        }
        .card-header {
            background: rgba(0, 255, 136, 0.04);
            border-bottom: 1px solid var(--border-color);
            color: var(--brand-color);
        }

        .modal-content {
            background: var(--surface-color);
            border: 1px solid var(--border-color);
            border-radius: 0;
            color: var(--text-main);
        }
        .modal-header { border-bottom-color: var(--border-color); }
        .modal-footer { border-top-color: var(--border-color); }
        .btn-close { filter: invert(1) grayscale(100%) brightness(200%); }

        .alert-success { background: rgba(0, 255, 136, 0.08); border-color: rgba(0, 255, 136, 0.25); color: #00ff88; border-radius: 0; }
        .alert-danger { background: rgba(255, 34, 68, 0.08); border-color: rgba(255, 34, 68, 0.25); color: #ff6680; border-radius: 0; }
        .alert-warning { background: rgba(255, 170, 0, 0.08); border-color: rgba(255, 170, 0, 0.25); color: #ffaa00; border-radius: 0; }
        .alert-info { background: rgba(0, 136, 255, 0.08); border-color: rgba(0, 136, 255, 0.25); color: #55aaff; border-radius: 0; }

        a { color: var(--brand-color); }
        a:hover { color: #66ffbb; text-shadow: 0 0 6px rgba(0, 255, 136, 0.4); }

        ::selection { background: rgba(0, 255, 136, 0.3); color: #fff; }

        @media print {
            .sidebar { display: none !important; }
            .main-wrapper { margin-left: 0; max-width: 100%; padding: 0; }
        }
    </style>
    @stack('admin-styles')
@endpush

@section('content')
    <aside class="sidebar">
        <div class="sidebar-header">
            <h2>DAWSCAPE</h2>
            <div style="font-size: 0.7rem; color: var(--text-muted); margin-top: 5px; letter-spacing: 2px;">// ADMIN CONTROL</div>
        </div>

        <div class="sidebar-menu">
            <div class="menu-label">Principal</div>
            <ul style="padding:0; margin:0;">
                <li class="nav-item">
                    <a href="/admin" class="nav-link {{ request()->is('admin') ? 'active' : '' }}">
                        <i class="ph ph-squares-four"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.qrs') }}" class="nav-link {{ request()->routeIs('admin.qrs') ? 'active' : '' }}">
                        <i class="ph ph-qr-code"></i> Gestor de QRs
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.preguntas') }}" class="nav-link {{ request()->routeIs('admin.preguntas') ? 'active' : '' }}">
                        <i class="ph ph-exam"></i> B. Preguntas
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.analitica') }}" class="nav-link {{ request()->routeIs('admin.analitica') ? 'active' : '' }}">
                        <i class="ph ph-chart-bar"></i> Analítica de Pruebas
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.valoraciones') }}" class="nav-link {{ request()->routeIs('admin.valoraciones') ? 'active' : '' }}">
                        <i class="ph ph-star"></i> Valoraciones
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.system') }}" class="nav-link {{ request()->routeIs('admin.system') ? 'active' : '' }}">
                        <i class="ph ph-heartbeat"></i> System Health
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.live') }}" class="nav-link {{ request()->routeIs('admin.live') ? 'active' : '' }}">
                        <i class="ph ph-monitor-play"></i> Live Feed & Control
                    </a>
                </li>
            </ul>

            @if(isset($rutasPruebas) && count($rutasPruebas) > 0)
            <div class="menu-label" style="margin-top: 2rem;">Bypass Testing</div>
            <ul style="padding:0; margin:0;">
                @foreach($rutasPruebas as $prueba)
                    <li class="nav-item">
                        <a href="{{ $prueba['url'] }}" target="_blank" class="nav-link" style="font-size: 0.85rem; padding: 0.5rem 1.5rem;">
                            <i class="ph ph-bug"></i> {{ $prueba['nombre'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
            @endif
        </div>

        <div class="sidebar-footer">
            <div class="mb-3 d-flex align-items-center gap-2" style="padding: 0 0.5rem;">
                <div class="d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 0.8rem; font-weight: bold; border: 1px solid var(--border-color); color: var(--brand-color);">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="overflow-hidden">
                    <div class="text-truncate" style="font-size: 0.85rem; font-weight: 600; color: var(--text-main);">{{ Auth::user()->name }}</div>
                    <div class="text-truncate" style="font-size: 0.7rem; color: var(--text-muted);">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout border-0" style="background: rgba(239, 68, 68, 0.1);">
                    <i class="ph ph-sign-out"></i> Cerrar Sesión
                </button>
            </form>
        </div>
    </aside>

    <main class="main-wrapper">
        @yield('admin-content')
    </main>
@endsection
