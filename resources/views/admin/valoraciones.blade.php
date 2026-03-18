@extends('layouts.admin')

@section('title', 'Valoraciones | DAWSCAPE')

@push('admin-styles')
    <style>
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
            flex-direction: column;
            gap: 0.5rem;
        }
        .kpi-title {
            font-size: 0.875rem;
            color: var(--text-muted);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .kpi-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-main);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .table-container {
            background: var(--surface-color);
            border-radius: 16px;
            padding: 1.5rem;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 20px rgba(0,0,0,0.02);
            overflow-x: auto;
        }
        .val-table {
            width: 100%;
            border-collapse: collapse;
        }
        .val-table th,
        .val-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }
        .val-table th {
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }
        .val-table tbody tr:hover {
            background: rgba(0,0,0,0.02);
        }

        .stars {
            color: #facc15;
            font-size: 1.25rem;
            letter-spacing: 2px;
        }
        .star-empty {
            color: #4b5563;
        }
    </style>
@endpush

@section('admin-content')
<div class="page-header">
    <div>
        <h1 class="page-title">Valoraciones de la Actividad</h1>
        <p class="page-subtitle">Feedback recibido de los equipos participantes.</p>
    </div>
</div>

<div class="kpi-grid">
    <div class="kpi-card">
        <span class="kpi-title">Total Valoraciones</span>
        <h3 class="kpi-value">{{ $total }}</h3>
    </div>
    <div class="kpi-card">
        <span class="kpi-title">Puntuacion Media</span>
        <h3 class="kpi-value">
            {{ $media }}
            <span style="font-size: 1rem; color: #facc15; margin-left: 8px;">&#9733;</span>
        </h3>
    </div>
</div>

<div class="table-container">
    <table class="val-table">
        <thead>
            <tr>
                <th>Equipo</th>
                <th>Puntuacion</th>
                <th>Comentario</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @forelse($valoraciones as $valoracion)
                <tr>
                    <td style="font-weight: 600; color: var(--brand-color);">
                        {{ $valoracion->nombre_equipo }}
                    </td>
                    <td>
                        <span class="stars">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $valoracion->estrellas)
                                    &#9733;
                                @else
                                    <span class="star-empty">&#9734;</span>
                                @endif
                            @endfor
                        </span>
                    </td>
                    <td>{{ $valoracion->comentario ?? '-' }}</td>
                    <td style="color: var(--text-muted);">
                        {{ $valoracion->created_at->format('d/m/Y H:i') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; padding: 2rem; color: var(--text-muted);">
                        Aun no hay valoraciones registradas.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
