<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DAWSCAPE | Inscripción de Equipos</title>
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
            padding: 1.5rem;
        }

        .intro-box {
            background: rgba(34, 197, 94, 0.05);
            border-left: 4px solid #22c55e;
            padding: 1.5rem;
            border-radius: 0.5rem;
            margin-bottom: 3rem;
            border-left: 4px solid #22c55e;
            padding: 1.5rem;
            border-radius: 0.5rem;
            margin-bottom: 3rem;
        }

        .intro-box h2 {
            color: #22c55e;
            margin-bottom: 0.75rem;
            font-size: 1.3rem;
        }

        .intro-box p {
            color: #cbd5e1;
            line-height: 1.6;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            color: #22c55e;
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .form-group input[type="text"],
        .form-group input[type="email"] {
            width: 100%;
            background: rgba(30, 41, 59, 0.8);
            border: 1px solid rgba(34, 197, 94, 0.3);
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            color: #e2e8f0;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-group input[type="text"]:focus,
        .form-group input[type="email"]:focus {
            outline: none;
            border-color: #22c55e;
            box-shadow: 0 0 15px rgba(34, 197, 94, 0.3);
            background: rgba(30, 41, 59, 0.9);
        }

        .form-group input::placeholder {
            color: #64748b;
        }

        .error-message {
            color: #ff6b6b;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }

        .alert {
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid;
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.1);
            border-color: rgba(34, 197, 94, 0.3);
            color: #86efac;
        }

        .alert-error {
            background: rgba(255, 107, 107, 0.1);
            border-color: rgba(255, 107, 107, 0.3);
            color: #ff9999;
        }

        .legal-section {
            background: rgba(241, 245, 249, 0.05);
            border: 1px solid rgba(34, 197, 94, 0.2);
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-bottom: 0.75rem;
        }

        .legal-content {
            font-size: 0.85rem;
            color: #cbd5e1;
            line-height: 1.6;
        }

        .legal-content p {
            margin-bottom: 0.75rem;
        }

        .legal-content strong {
            color: #22c55e;
        }

        .checkbox-group {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .checkbox-group input[type="checkbox"] {
            width: 20px;
            height: 20px;
            margin-top: 2px;
            accent-color: #22c55e;
            cursor: pointer;
        }

        .checkbox-group label {
            margin: 0;
            color: #cbd5e1;
            font-weight: 400;
            cursor: pointer;
            display: flex;
            align-items: center;
        }

        .checkbox-group a {
            color: #22c55e;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .checkbox-group a:hover {
            color: #16a34a;
            text-decoration: underline;
        }

        .button-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
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
            display: inline-block;
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

        .btn-primary:disabled {
            background: #64748b;
            color: #334155;
            cursor: not-allowed;
            box-shadow: none;
            transform: none;
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

        @media (max-width: 640px) {
            .card-header h1 {
                font-size: 2.5rem;
            }

            .button-group {
                grid-template-columns: 1fr;
            }

            .legal-section {
                max-height: 250px;
            }
        }

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(30, 41, 59, 0.8);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: #22c55e;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #16a34a;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <!-- Header -->
            <div class="card-header">
                <h1>⟨DAWSCAPE⟩</h1>
                <p>Sistema de Inscripción de Equipos</p>
            </div>

            <!-- Body -->
            <div class="card-body">
                <!-- Description Box -->
                <div class="intro-box">
                    <h2>Bienvenido al Desafío</h2>
                    <p>
                        Estás a punto de entrar en un <strong>Escape Room digital</strong> lleno de misterios.
                        Resuelve acertijos, descifra códigos, escanea códigos QR ocultos en el mundo real y trabaja en equipo.
                        ¿Aceptas el reto?                    </p>
                </div>
                <!-- Success/Error Messages -->
                @if (session('success'))
                    <div class="alert alert-success">
                        ✓ {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-error">
                        <strong>⚠ Se encontraron errores:</strong>
                        <ul style="margin-left: 1.5rem; margin-top: 0.5rem;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Registration Form -->
                <form method="POST" action="{{ route('inscripcion.guardar') }}" class="form">
                    @csrf

                    <!-- Nombre del Equipo -->
                    <div class="form-group">
                        <label for="nombre_equipo">Nombre del Equipo *</label>
                        <input type="text" id="nombre_equipo" name="nombre_equipo"
                               placeholder="Ej: Los Detectives Digitales"
                               value="{{ old('nombre_equipo') }}"
                               required maxlength="60">
                        @error('nombre_equipo')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Correo Electrónico -->
                    <div class="form-group">
                        <label for="correo">Correo Electrónico *</label>
                        <input type="email" id="correo" name="correo"
                               placeholder="ejemplo@estudiante.xtart.com"
                               value="{{ old('correo') }}"
                               required maxlength="120">
                        @error('correo')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Clase / Grupo -->
                    <div class="form-group">
                        <label for="clase">Clase / Grupo *</label>
                        <input type="text" id="clase" name="clase"
                               placeholder="Ej: 2º Marketing Digital"
                               value="{{ old('clase') }}"
                               required maxlength="20">
                        @error('clase')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Checkbox to Accept Terms -->
                    <div class="checkbox-group">
                        <input type="checkbox" id="acepta_terminos" name="acepta_terminos" value="1" required>
                        <label for="acepta_terminos">
                            He leído y acepto el <a href="/aviso-legal" target="_blank">Aviso Legal</a>. Entiendo que participo bajo mi propia responsabilidad.
                        </label>
                    </div>
                    @error('acepta_terminos')
                        <div class="error-message">{{ $message }}</div>
                    @enderror

                    <!-- Buttons -->
                    <div class="button-group">
                        <button type="submit" class="btn btn-primary">
                            Registrar Equipo
                        </button>
                        <a href="{{ route('inicio') }}" class="btn btn-secondary">
                            Volver
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>


</body>
</html>

