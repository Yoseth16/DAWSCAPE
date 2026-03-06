<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de QRs | DAWSCAPE Admin</title>
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
            max-width: 1000px;
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

        .btn-outline-primary { border-color: #e0e6ed; color: #5c6b7a; font-weight: 500; border-radius: 6px; }
        .btn-outline-primary:hover { background-color: #f8fafc; border-color: #cbd5e1; color: #1e293b; }
        
        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            padding: 0.8rem 1rem;
            font-size: 0.95rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .btn-primary-custom {
            background-color: #3b82f6; 
            color: white; 
            border: none; 
            font-weight: 600;
            padding: 0.8rem 1.5rem; 
            border-radius: 8px;
            transition: all 0.2s;
        }
        .btn-primary-custom:hover { background-color: #2563eb; color: white;}

        .card-qr {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 6px rgba(0,0,0,0.02);
            page-break-inside: avoid;
        }
        
        /* Contenedor específico que se usará para la impresión limpia */
        .print-container {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .qr-info h3 { font-size: 1.2rem; font-weight: 700; color: #1e293b; margin-bottom: 0.3rem; }
        .qr-info p { margin-bottom: 0; color: #64748b; font-size: 0.95rem; }
        .qr-phase { margin-top: 0.5rem; display: inline-block; background: #fef3c7; color: #92400e; padding: 0.2rem 0.6rem; border-radius: 4px; font-size: 0.8rem; font-weight: 600;}

        .btn-print { background: #f1f5f9; color: #334155; border: 1px solid #cbd5e1; padding: 0.5rem 1rem; border-radius: 6px; font-weight: 600; cursor: pointer; transition: all 0.2s; }
        .btn-print:hover { background: #e2e8f0; }

        .btn-delete { background: white; border: 1px solid #fecaca; color: #ef4444; border-radius: 6px; padding: 0.5rem 1rem; font-weight: 600; cursor: pointer; transition: all 0.2s; }
        .btn-delete:hover { background: #fee2e2; border-color: #fca5a5; }

        /* Estilos de impresión */
        @media print {
            body { background: white; padding: 0; margin: 0; }
            .dashboard-container { box-shadow: none; padding: 0; margin: 0; max-width: 100%; border: none;}
            .dashboard-header, form, hr, .alert, .actions, .section-title { display: none !important; }
            
            /* Ocultamos todas las tarjetas excepto la que queremos imprimir */
            .card-qr { display: none; }
            .card-qr.printing {
                display: flex;
                border: 2px dashed #000;
                margin-top: 2cm;
                text-align: center;
                flex-direction: column;
            }
            .card-qr.printing .print-container {
                flex-direction: column;
                justify-content: center;
                align-items: center;
            }
            .card-qr.printing .qr-info {
                text-align: center;
                margin-top: 1rem;
            }
            .card-qr.printing .qr-info h3 { font-size: 1.5rem; }
            .card-qr.printing .qr-info p { font-size: 1.2rem; color: #000; border-left: 4px solid #000; padding-left: 10px; margin-top: 10px; background: #f9f9f9; padding: 15px;}
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1>Gestión de Códigos QR</h1>
            <a href="/admin" class="btn btn-sm btn-outline-primary">← Volver al Panel</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success mt-3 mb-4" style="background:#d1fae5; border-color:#34d399; color:#065f46;">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger mt-3 mb-4" style="background:#fee2e2; border-color:#f87171; color:#991b1b;">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger mb-4">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="/admin/qrs" method="POST" class="mb-5 bg-light p-4 rounded border">
            @csrf
            <h4 class="mb-4 text-dark" style="font-weight: 700;">Generar Nuevo QR</h4>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Nombre del QR / Título</label>
                    <input type="text" name="nombre" class="form-control" placeholder="Ej: Máquina de café planta 1" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Asignar Prueba (Enrutamiento y Anti-trampas)</label>
                    <select name="fase_requerida" class="form-select" required>
                        <option value="" disabled selected>Selecciona la prueba vinculada al QR...</option>
                        <option value="Prueba 3 Angel">Prueba 3 (Máquina de Café)</option>
                        <option value="Prueba 4 Arturo">Prueba 4 (Máquina Oasis Snacks)</option>
                        <option value="Prueba 5 Eloy">Prueba 5 (Protocolo Espejo - Taquillas)</option>
                    </select>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label fw-bold">Función / Instrucción de pegado</label>
                <textarea name="descripcion" class="form-control" rows="2" placeholder="Ej: Pegar en el lateral derecho de la máquina de café de la primera planta" required></textarea>
            </div>
            <button type="submit" class="btn-primary-custom">Generar QR</button>
        </form>

        <h4 class="section-title mb-4" style="font-weight: 700; color: #1e293b;">Historial de QRs</h4>

        @if($qrs->isEmpty())
            <div class="text-center p-5 text-muted border rounded" style="background: #f8fafc;">
                No hay ningún QR generado en el sistema todavía.
            </div>
        @else
            <div>
                @foreach($qrs as $qr)
                <div class="card-qr" id="qr-card-{{ $qr->id }}">
                    <div class="print-container">
                        <div>
                            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(120)->color(0, 0, 0)->margin(1)->generate($qr->url) !!}
                        </div>
                        <div class="qr-info">
                            <h3>{{ $qr->nombre }}</h3>
                            <p>{{ $qr->descripcion }}</p>
                            @if($qr->fase_requerida)
                                <div class="qr-phase">🔒 Requiere fase: {{ $qr->fase_requerida }}</div>
                            @else
                                <div class="qr-phase" style="background: #d1fae5; color: #065f46;">🌐 Acceso Libre</div>
                            @endif
                        </div>
                    </div>
                    <div class="actions" style="display: flex; gap: 0.5rem; flex-direction: column;">
                        <button class="btn-print" onclick="printQr('{{ $qr->id }}')">🖨️ Imprimir</button>
                        <form action="/admin/qrs/{{ $qr->id }}" method="POST" onsubmit="return confirm('¿Eliminar QR? Este código dejará de funcionar permanentemente.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete" style="width: 100%;">🗑️ Eliminar</button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    <script>
        function printQr(id) {
            // Quitamos la clase 'printing' de todos por si acaso
            document.querySelectorAll('.card-qr').forEach(el => el.classList.remove('printing'));
            // Se la añadimos solo al que queremos imprimir
            document.getElementById('qr-card-' + id).classList.add('printing');
            // Lanzamos impresión del navegador
            window.print();
        }
    </script>
</body>
</html>
