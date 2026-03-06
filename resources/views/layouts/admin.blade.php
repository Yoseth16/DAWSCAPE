@extends('layouts.app')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        :root {
            --sidebar-width: 260px;
            --primary-bg: #f8fafc;
            --surface-color: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --brand-color: #3b82f6;
            --brand-accent: #60a5fa;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
        }

        body {
            background-color: var(--primary-bg);
            color: var(--text-main);
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
            display: flex;
            min-height: 100vh;
        }

        /* --- SIDEBAR --- */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
            color: white;
            position: fixed;
            height: 100vh;
            left: 0;
            top: 0;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.05);
        }

        .sidebar-header {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            text-align: center;
        }

        .sidebar-header h2 {
            font-weight: 800;
            margin: 0;
            letter-spacing: 1px;
            color: #f8fafc;
            font-size: 1.5rem;
            background: -webkit-linear-gradient(45deg, #60a5fa, #a78bfa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .sidebar-menu {
            flex: 1;
            padding: 1.5rem 0;
            overflow-y: auto;
        }

        .menu-label {
            padding: 0 1.5rem;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #94a3b8;
            margin-bottom: 0.8rem;
            font-weight: 600;
        }

        .nav-item { list-style: none; margin-bottom: 0.5rem; }

        .nav-link {
            display: flex; align-items: center; padding: 0.8rem 1.5rem;
            color: #cbd5e1; font-weight: 500; transition: all 0.3s;
            text-decoration: none; gap: 12px; position: relative;
        }

        .nav-link i { font-size: 1.25rem; color: #94a3b8; transition: color 0.3s; }

        .nav-link:hover, .nav-link.active { background-color: rgba(255, 255, 255, 0.05); color: white; }
        .nav-link:hover i, .nav-link.active i { color: var(--brand-accent); }

        .nav-link.active::before {
            content: ''; position: absolute; left: 0; top: 0; bottom: 0;
            width: 4px; background-color: var(--brand-accent); border-radius: 0 4px 4px 0;
        }

        .sidebar-footer { padding: 1.5rem; border-top: 1px solid rgba(255,255,255,0.1); }

        .btn-logout {
            display: flex; align-items: center; justify-content: center; gap: 10px;
            width: 100%; padding: 0.8rem; background: rgba(239, 68, 68, 0.1);
            color: #fca5a5; border: 1px solid rgba(239, 68, 68, 0.2);
            border-radius: 8px; text-decoration: none; font-weight: 600; transition: all 0.3s;
        }
        .btn-logout:hover { background: rgba(239, 68, 68, 0.2); color: white; }

        /* --- MAIN CONTENT HOMOGENIZADO --- */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            flex: 1;
            padding: 2.5rem 3rem;
            max-width: calc(100vw - var(--sidebar-width));
        }

        .page-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2.5rem; }
        .page-title { margin: 0; font-weight: 700; color: var(--text-main); font-size: 1.8rem; }
        .page-subtitle { color: var(--text-muted); margin-top: 5px; font-size: 0.95rem; }
        
        .glass-panel {
            background: var(--surface-color);
            border-radius: 16px;
            padding: 1.5rem;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 20px rgba(0,0,0,0.02);
            margin-bottom: 1.5rem;
        }

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
            <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 5px;">Admin Control Center</div>
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
                    <a href="/admin/qrs" class="nav-link {{ request()->is('admin/qrs') ? 'active' : '' }}">
                        <i class="ph ph-qr-code"></i> Gestor de QRs
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
            <a href="/" class="btn-logout">
                <i class="ph ph-sign-out"></i> Salir al Inicio
            </a>
        </div>
    </aside>

    <main class="main-wrapper">
        @yield('admin-content')
    </main>
@endsection
