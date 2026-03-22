@extends('layouts.app')

@push('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 260px;
            --primary-bg: #f8fafc;
            --surface-color: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --brand-color: #3b82f6;
            --brand-accent: #2563eb;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --sidebar-bg: #0f172a;
            --sidebar-text: #f8fafc;
            --sidebar-muted: #94a3b8;
            --sidebar-border: rgba(255,255,255,0.1);
        }

        body {
            background-color: var(--primary-bg);
            color: var(--text-main);
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
            display: flex;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        /* --- SIDEBAR --- */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            color: var(--sidebar-text);
            position: fixed;
            height: 100vh;
            left: 0;
            top: 0;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            border-right: 1px solid var(--sidebar-bg);
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.05);
        }

        .sidebar-header {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid var(--sidebar-border);
            text-align: center;
        }

        .sidebar-header h2 {
            font-family: 'Poppins', sans-serif;
            font-weight: 800;
            margin: 0;
            letter-spacing: 0.5px;
            color: #ffffff;
            font-size: 1.5rem;
        }

        .sidebar-menu {
            flex: 1;
            padding: 1.5rem 0;
            overflow-y: auto;
        }

        .sidebar-menu::-webkit-scrollbar { width: 5px; }
        .sidebar-menu::-webkit-scrollbar-track { background: transparent; }
        .sidebar-menu::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.2); border-radius: 4px; }

        .menu-label {
            padding: 0 1.5rem;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--sidebar-muted);
            margin-bottom: 0.8rem;
            font-weight: 600;
        }

        .nav-item { list-style: none; margin-bottom: 0.25rem; }

        .nav-link {
            display: flex; align-items: center; padding: 0.75rem 1.5rem;
            color: #cbd5e1; font-weight: 500; transition: all 0.25s;
            text-decoration: none; gap: 12px; position: relative;
            font-size: 0.95rem;
        }

        .nav-link i { font-size: 1.25rem; color: var(--sidebar-muted); transition: all 0.25s; }

        .nav-link:hover, .nav-link.active {
            background: rgba(255, 255, 255, 0.05);
            color: #ffffff;
        }
        .nav-link:hover i, .nav-link.active i {
            color: var(--brand-color);
        }

        .nav-link.active::before {
            content: ''; position: absolute; left: 0; top: 0; bottom: 0;
            width: 4px; background: var(--brand-color);
            border-radius: 0 4px 4px 0;
        }

        .sidebar-footer {
            padding: 1.5rem;
            border-top: 1px solid var(--sidebar-border);
        }

        .btn-logout {
            display: flex; align-items: center; justify-content: center; gap: 10px;
            width: 100%; padding: 0.8rem; background: rgba(239, 68, 68, 0.1);
            color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2);
            border-radius: 8px; text-decoration: none; font-weight: 600;
            transition: all 0.3s; font-family: inherit;
        }
        .btn-logout:hover {
            background: rgba(239, 68, 68, 0.15);
            color: #dc2626;
            border-color: rgba(239, 68, 68, 0.3);
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
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            color: var(--text-main);
            font-size: 1.8rem;
            letter-spacing: -0.5px;
        }
        .page-subtitle { color: var(--text-muted); margin-top: 5px; font-size: 0.95rem; }

        .glass-panel {
            background: var(--surface-color);
            border-radius: 12px;
            padding: 1.5rem;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
            margin-bottom: 1.5rem;
            position: relative;
        }

        /* Bootstrap overrides */
        .table { color: var(--text-main); border-color: var(--border-color); }
        .table > :not(caption) > * > * {
            background-color: transparent;
            border-bottom-color: var(--border-color);
            color: var(--text-main);
        }
        .table-hover > tbody > tr:hover > * {
            background-color: #f8fafc;
            color: var(--text-main);
        }
        .table thead th {
            color: var(--text-muted);
            text-transform: uppercase;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            border-bottom-color: var(--border-color);
            background-color: #f1f5f9;
        }

        .btn-primary, .btn-info {
            background: var(--brand-color);
            color: #ffffff;
            border: 1px solid var(--brand-accent);
            border-radius: 8px;
            font-family: inherit;
            transition: all 0.2s;
            font-weight: 500;
        }
        .btn-primary:hover, .btn-info:hover {
            background: var(--brand-accent);
            color: #ffffff;
            border-color: var(--brand-accent);
            transform: translateY(-1px);
        }

        .btn-danger {
            background: var(--danger-color);
            color: #ffffff;
            border: 1px solid #dc2626;
            border-radius: 8px;
            font-family: inherit;
            font-weight: 500;
        }
        .btn-danger:hover {
            background: #dc2626;
            color: #ffffff;
        }

        .btn-warning {
            background: var(--warning-color);
            color: #ffffff;
            border: 1px solid #d97706;
            border-radius: 8px;
            font-family: inherit;
            font-weight: 500;
        }
        .btn-warning:hover {
            background: #d97706;
            color: #ffffff;
        }

        .btn-success {
            background: var(--success-color);
            color: #ffffff;
            border: 1px solid #059669;
            border-radius: 8px;
            font-family: inherit;
            font-weight: 500;
        }
        .btn-success:hover {
            background: #059669;
            color: #ffffff;
        }

        .badge {
            border-radius: 6px;
            font-family: inherit;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        .badge.bg-success { background: #dcfce7 !important; color: #15803d; border: 1px solid #bbf7d0; }
        .badge.bg-warning { background: #fef3c7 !important; color: #b45309; border: 1px solid #fde68a; }
        .badge.bg-danger { background: #fee2e2 !important; color: #b91c1c; border: 1px solid #fecaca; }
        .badge.bg-info { background: #e0f2fe !important; color: #0369a1; border: 1px solid #bae6fd; }
        .badge.bg-primary { background: #dbeafe !important; color: #1d4ed8; border: 1px solid #bfdbfe; }
        .badge.bg-secondary { background: #f1f5f9 !important; color: #475569; border: 1px solid #e2e8f0; }

        .form-control, .form-select {
            background: #ffffff;
            border: 1px solid var(--border-color);
            color: var(--text-main);
            border-radius: 8px;
            font-family: inherit;
        }
        .form-control:focus, .form-select:focus {
            background: #ffffff;
            border-color: var(--brand-color);
            color: var(--text-main);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .card {
            background: var(--surface-color);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            color: var(--text-main);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03);
        }
        .card-header {
            background: #f8fafc;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-main);
            font-weight: 600;
        }

        .modal-content {
            background: var(--surface-color);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            color: var(--text-main);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .modal-header { border-bottom-color: var(--border-color); }
        .modal-footer { border-top-color: var(--border-color); }
        .btn-close { filter: none; }

        .alert-success { background: #dcfce7; border-color: #bbf7d0; color: #15803d; border-radius: 8px; }
        .alert-danger { background: #fee2e2; border-color: #fecaca; color: #b91c1c; border-radius: 8px; }
        .alert-warning { background: #fef3c7; border-color: #fde68a; color: #b45309; border-radius: 8px; }
        .alert-info { background: #e0f2fe; border-color: #bae6fd; color: #0369a1; border-radius: 8px; }

        a { color: var(--brand-color); text-decoration: none; }
        a:hover { color: var(--brand-accent); }

        ::selection { background: #bae6fd; color: #0c4a6e; }

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
            <div style="font-size: 0.75rem; color: var(--brand-color); margin-top: 5px; letter-spacing: 1px; font-weight: 500;">Admin Panel</div>
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
            <div class="mb-3 d-flex align-items-center gap-3" style="padding: 0 0.5rem;">
                <div class="d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; font-size: 0.9rem; font-weight: bold; background: var(--brand-color); color: #ffffff; border-radius: 8px;">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="overflow-hidden">
                    <div class="text-truncate" style="font-size: 0.9rem; font-weight: 600; color: #ffffff;">{{ Auth::user()->name }}</div>
                    <div class="text-truncate" style="font-size: 0.75rem; color: #94a3b8;">{{ Auth::user()->email }}</div>
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
