<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DAWSCAPE | Inscripción Exitosa</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Orbitron:wght@400;700;900&display=swap" rel="stylesheet">

    <!-- Vite CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
            color: #e2e8f0;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        .card {
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(34, 197, 94, 0.2);
            border-radius: 1rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5), 0 0 40px rgba(34, 197, 94, 0.1);
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.1), rgba(16, 185, 129, 0.05));
            border-bottom: 2px solid rgba(34, 197, 94, 0.3);
            padding: 3rem 2rem;
            text-align: center;
        }

        .card-header h1 {
            font-family: 'Orbitron', monospace;
            font-size: 3.5rem;
            font-weight: 900;
            color: #22c55e;
            text-shadow: 0 0 20px rgba(34, 197, 94, 0.5);
            letter-spacing: 2px;
            margin-bottom: 0.5rem;
        }

        .card-header p {
            font-size: 1.1rem;
            color: #86efac;
            font-weight: 500;
        }

        .card-body {
            padding: 2rem;
        }

        .success-banner {
            background: rgba(34, 197, 94, 0.1);
            border: 2px solid #22c55e;
            border-radius: 0.5rem;
            padding: 1.5rem;
            text-align: center;
            margin-bottom: 2rem;
        }

        .success-banner h2 {
            color: #22c55e;
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .success-banner p {
            color: #86efac;
            font-size: 1.05rem;
        }

        .team-info {
            background: rgba(241, 245, 249, 0.05);
            border-left: 4px solid #22c55e;
            padding: 1.5rem;
            border-radius: 0.5rem;
            margin-bottom: 2rem;
        }

        .team-info h3 {
            color: #22c55e;
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid rgba(34, 197, 94, 0.1);
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #cbd5e1;
            font-weight: 600;
        }

        .info-value {
            color: #22c55e;
            font-weight: 700;
        }

        .qr-section {
            background: rgba(241, 245, 249, 0.05);
            border: 2px dashed rgba(34, 197, 94, 0.3);
            border-radius: 0.5rem;
            padding: 2rem;
            text-align: center;
            margin-bottom: 2rem;
        }

        .qr-section h3 {
            color: #22c55e;
            margin-bottom: 1.5rem;
            font-size: 1.2rem;
        }

        .qr-container {
            display: inline-block;
            background: white;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .qr-container img {
            width: 250px;
            height: 250px;
            display: block;
        }

        .qr-description {
            color: #cbd5e1;
            font-size: 0.9rem;
            line-height: 1.6;
            max-width: 500px;
            margin: 0 auto;
        }

        .button-group {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn {
            padding: 0.85rem 1.5rem;
            font-size: 1rem;
            font-weight: 600;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            text-align: center;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: #22c55e;
            color: #0f172a;
            box-shadow: 0 0 20px rgba(34, 197, 94, 0.3);
        }

        .btn-primary:hover {
            background: #16a34a;
            box-shadow: 0 0 30px rgba(34, 197, 94, 0.5);
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: rgba(148, 163, 184, 0.1);
            color: #cbd5e1;
            border: 1px solid rgba(148, 163, 184, 0.3);
        }

        .btn-secondary:hover {
            background: rgba(148, 163, 184, 0.2);
            border-color: rgba(148, 163, 184, 0.5);
            transform: translateY(-2px);
        }

        .instructions {
            background: rgba(59, 130, 246, 0.1);
            border-left: 4px solid #3b82f6;
            padding: 1.5rem;
            border-radius: 0.5rem;
            margin-bottom: 2rem;
        }

        .instructions h3 {
            color: #93c5fd;
            margin-bottom: 1rem;
        }

        .instructions ol {
            margin-left: 1.5rem;
            color: #cbd5e1;
            line-height: 1.8;
        }

        .instructions li {
            margin-bottom: 0.5rem;
        }

        @media (max-width: 640px) {
            .card-header h1 {
                font-size: 2.5rem;
            }

            .button-group {
                grid-template-columns: 1fr;
            }

            .qr-container img {
                width: 200px;
                height: 200px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <!-- Header -->
            <div class="card-header">
                <h1>DAWSCAPE</h1>
                <p>Inscripción Completada</p>
            </div>

            <!-- Body -->
            <div class="card-body">
                <!-- Success Banner -->
                <div class="success-banner">
                    <h2>Equipo Registrado Correctamente</h2>
                    <p>Tu equipo está listo para participar en la actividad</p>
                </div>

                <!-- Team Information -->
                <div class="team-info">
                    <h3>Datos del Equipo</h3>
                    <div class="info-row">
                        <span class="info-label">Nombre del Equipo:</span>
                        <span class="info-value">{{ $inscripcion->nombre_equipo }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Correo Electrónico:</span>
                        <span class="info-value">{{ $inscripcion->correo }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Clase / Grupo:</span>
                        <span class="info-value">{{ $inscripcion->clase }}</span>
                    </div>
                </div>

                <!-- QR Section -->
                <div class="qr-section">
                    <h3>Código QR de Acceso</h3>
                    <div class="qr-container">
                        <img src="{{ route('inscripcion.qr', ['token' => $inscripcion->qr_token]) }}" alt="QR Code">
                    </div>
                    <div class="qr-description">
                        <p>
                            Este código QR es tu <strong>identificador único</strong> para el evento.
                            Guárdalo bien: lo necesitarás durante el Escape Room para validar tu participación.
                        </p>
                    </div>
                </div>

                <!-- Instructions -->
                <div class="instructions">
                    <h3>Instrucciones Importantes</h3>
                    <ol>
                        <li><strong>Descarga tu QR:</strong> Presiona el botón "Descargar QR" para guardar la imagen en tu dispositivo.</li>
                        <li><strong>Guarda tu correo:</strong> Necesitarás tu correo para iniciar sesión en el evento.</li>
                        <li><strong>Atiende al comunicado:</strong> El equipo organizador te contactará con los detalles finales.</li>
                        <li><strong>Presenta tu acreditación:</strong> Muestra tu QR o correo en la entrada del evento.</li>
                        <li><strong>Trabaja en equipo:</strong> La colaboración es fundamental para completar la actividad.</li>
                    </ol>
                </div>

                <!-- Buttons -->
                <div class="button-group">
                    <a href="{{ route('inscripcion.qr', ['token' => $inscripcion->qr_token]) }}"
                       class="btn btn-primary" download="qr-{{ $inscripcion->nombre_equipo }}.png">
                        Descargar QR
                    </a>
                    <a href="{{ route('inicio') }}" class="btn btn-secondary">
                        Volver al Inicio
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
