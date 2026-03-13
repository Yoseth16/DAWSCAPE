@extends('layouts.admin')

@section('title', 'Banco de Preguntas | DAWSCAPE')

@push('admin-styles')
    <style>
        /* --- ESTILOS COMPARTIDOS PREMIUM --- */
        .page-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2.5rem; }
        .page-title { margin: 0; font-weight: 700; color: var(--text-main); font-size: 1.8rem; }
        .page-subtitle { color: var(--text-muted); margin-top: 5px; font-size: 0.95rem; }
        
        .glass-panel {
            background: var(--surface-color);
            border-radius: 16px;
            padding: 1.8rem;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 20px rgba(0,0,0,0.02);
            margin-bottom: 1.5rem;
            height: 100%;
        }

        .panel-heading {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 8px;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-color);
        }
        
        .panel-heading i { color: var(--brand-color); font-size: 1.3rem; }

        /* --- FORMULARIO --- */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .form-control {
            width: 100%;
            padding: 0.9rem 1.2rem;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            background: #f8fafc;
            color: var(--text-main);
            font-family: inherit;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--brand-color);
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }

        .btn-submit {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #3b82f6, #6366f1);
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 1rem;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(59, 130, 246, 0.3);
        }

        /* --- TABLA --- */
        .table-responsive { 
            border-radius: 16px; 
            overflow: hidden; 
            border: 1px solid var(--border-color); 
            background: white; 
            box-shadow: 0 4px 20px rgba(0,0,0,0.02); 
        }
        .table-custom { width: 100%; margin: 0; border-collapse: collapse; }
        .table-custom th {
            background-color: #f8fafc; color: var(--text-muted); font-weight: 600; text-transform: uppercase;
            letter-spacing: 0.5px; font-size: 0.75rem; padding: 1.2rem 1.5rem; border-bottom: 1px solid var(--border-color);
            text-align: left;
        }
        .table-custom td { padding: 1.2rem 1.5rem; vertical-align: middle; border-bottom: 1px solid #f1f5f9; color: var(--text-main); }
        .table-custom tr:hover { background-color: #f8fafc; }
        
        .table-custom tr.inactive td {
            opacity: 0.6;
            background-color: #f8fafc;
        }

        /* Acciones y Badges */
        .status-pill {
            display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 20px;
            font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px;
        }
        .status-active { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .status-inactive { background: #f1f5f9; color: #64748b; border: 1px solid #cbd5e1; }

        .btn-action {
            width: 34px; height: 34px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center;
            border: 1px solid transparent; cursor: pointer; transition: all 0.2s; background: #f1f5f9;
        }

        .btn-toggle-on { color: #10b981; }
        .btn-toggle-on:hover { background: #d1fae5; }
        
        .btn-toggle-off { color: #94a3b8; }
        .btn-toggle-off:hover { background: #e2e8f0; color: #64748b; }

        .btn-delete { color: #ef4444; }
        .btn-delete:hover { background: #fee2e2; }

        .answer-text {
            font-family: 'Courier New', Courier, monospace;
            font-weight: 700;
            color: var(--brand-color);
            background: #eff6ff;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 0.9rem;
        }

        /* Contenedor Grid Principal */
        .split-grid {
            display: grid;
            grid-template-columns: 1fr 2.5fr;
            gap: 1.5rem;
        }
    </style>
@endpush

@section('admin-content')
    <div class="page-header">
        <div>
            <h1 class="page-title"><i class="ph ph-exam" style="color:var(--brand-color); margin-right:8px;"></i> Banco de Preguntas</h1>
            <div class="page-subtitle">Añade o modifica las preguntas de cultura general que aparecerán en la Prueba Final (Fase 7).</div>
        </div>
        <div>
            <div class="status-pill status-active" style="font-size: 0.85rem; padding: 8px 16px;">
                <i class="ph-fill ph-database"></i> Total Activas: {{ $preguntas->where('activa', true)->count() }}
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center" style="background:#f0fdf4; border-color:#86efac; color:#166534; border-radius:12px; font-weight:500; margin-bottom: 2rem; padding: 1rem 1.5rem; border: 1px solid #86efac;">
            <i class="ph ph-check-circle" style="font-size:1.5rem; margin-right: 12px;"></i> {{ session('success') }}
        </div>
    @endif

    <div class="split-grid">
        
        <!-- Formulario Izquierda -->
        <div>
            <div class="glass-panel">
                <div class="panel-heading">
                    <i class="ph ph-plus-circle"></i> Añadir Pregunta
                </div>

                <form action="{{ url('/preguntas') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Enunciado o Acertijo</label>
                        <textarea name="enunciado" class="form-control" required placeholder="Ej: ¿En qué año cayó el muro de Berlín?"></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Respuestas válidas</label>
                        <input type="text" name="respuesta_correcta" class="form-control" required placeholder="Ej: París, Paris, PARIS">
                        <small style="color: var(--text-muted); font-size: 0.75rem; margin-top: 6px; display: block; line-height: 1.4;">
                            * Puedes indicar <strong>varias respuestas correctas separadas por comas</strong>. El sistema las comparará sin tildes ni mayúsculas.
                        </small>
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="ph ph-floppy-disk"></i> Guardar en BBDD
                    </button>
                </form>
            </div>
        </div>

        <!-- Tabla Derecha -->
        <div>
            <div class="glass-panel">
                <div class="panel-heading" style="justify-content: space-between;">
                    <div><i class="ph ph-list-bullets"></i> Inventario de Pruebas</div>
                </div>

                <div class="table-responsive">
                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th style="width: 10%">Estado</th>
                                <th style="width: 50%">Enunciado</th>
                                <th style="width: 25%">Respuesta</th>
                                <th style="width: 15%; text-align: center;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($preguntas as $p)
                                <tr class="{{ !$p->activa ? 'inactive' : '' }}">
                                    <td>
                                        @if($p->activa)
                                            <span class="status-pill status-active"><i class="ph-fill ph-check-circle"></i> ACTIVA</span>
                                        @else
                                            <span class="status-pill status-inactive"><i class="ph-fill ph-minus-circle"></i> INACTIVA</span>
                                        @endif
                                    </td>
                                    <td style="line-height: 1.5;">{{ $p->enunciado }}</td>
                                    <td><span class="answer-text">{{ $p->respuesta_correcta }}</span></td>
                                    <td style="text-align: center; white-space: nowrap;">
                                        
                                        <!-- Botón Toggle Estado -->
                                        <form action="{{ url('/preguntas/' . $p->id . '/toggle') }}" method="POST" style="display:inline-block; margin:0;">
                                            @csrf
                                            <button type="submit" class="btn-action {{ $p->activa ? 'btn-toggle-on' : 'btn-toggle-off' }}" title="{{ $p->activa ? 'Desactivar' : 'Activar' }}">
                                                <i class="ph {{ $p->activa ? 'ph-toggle-right' : 'ph-toggle-left' }}" style="font-size:1.4rem;"></i>
                                            </button>
                                        </form>

                                        <!-- Botón Borrar -->
                                        <form action="{{ url('/preguntas/' . $p->id) }}" method="POST" onsubmit="return confirm('¿Borrar definitivamente esta pregunta?');" style="display:inline-block; margin:0; margin-left: 4px;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-delete" title="Eliminar del sistema">
                                                <i class="ph ph-trash" style="font-size:1.2rem;"></i>
                                            </button>
                                        </form>

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" style="text-align:center; padding: 4rem 2rem; color:var(--text-muted);">
                                        <i class="ph ph-database" style="font-size:3rem; opacity:0.3; margin-bottom:1rem; display:block;"></i>
                                        El banco de preguntas está completamente vacío.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection
