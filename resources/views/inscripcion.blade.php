<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DAWSCAPE | Inscripción de Equipos</title>
    <!-- ICONOS -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Share+Tech+Mono&family=Orbitron:wght@400;700;900&display=swap');

        :root {
            --green: #00ff88;
            --red: #ff2244;
            --bg: #020c06;
            --panel: #041a0e;
            --border: #00ff8840;
            --text: #c8ffdf;
            --accent: #00ffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, var(--bg) 0%, #0a1810 100%);
            color: var(--text);
            font-family: 'Share Tech Mono', monospace;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Efecto de escaneo */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: repeating-linear-gradient(
                0deg,
                transparent,
                transparent 2px,
                rgba(0, 255, 136, 0.013) 2px,
                rgba(0, 255, 136, 0.013) 4px
            );
            pointer-events: none;
            z-index: 1000;
        }

        .screen {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 2rem;
            position: relative;
            z-index: 10;
        }

        .container {
            width: 100%;
            max-width: 600px;
            animation: slideIn 0.6s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .header {
            text-align: center;
            margin-bottom: 2.5rem;
            animation: glitch 2s infinite;
        }

        .logo {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: var(--green);
            text-shadow: 0 0 10px var(--green), 0 0 20px var(--green);
            font-family: 'Orbitron', sans-serif;
            font-weight: 900;
            letter-spacing: 0.2em;
        }

        .title {
            font-size: 1.8rem;
            color: var(--accent);
            text-shadow: 0 0 10px var(--accent);
            font-family: 'Orbitron', sans-serif;
            font-weight: 700;
            margin-bottom: 0.5rem;
            letter-spacing: 0.1em;
        }

        .subtitle {
            font-size: 0.9rem;
            color: var(--green);
            margin-bottom: 1.5rem;
            line-height: 1.6;
            text-shadow: 0 0 5px var(--green);
        }

        /* Caja de descripción */
        .description-box {
            background: rgba(4, 26, 14, 0.8);
            border: 2px solid var(--border);
            border-radius: 5px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 0 15px rgba(0, 255, 136, 0.2);
            animation: pulse 3s infinite;
        }

        @keyframes pulse {
            0%, 100% {
                box-shadow: 0 0 15px rgba(0, 255, 136, 0.2);
            }
            50% {
                box-shadow: 0 0 25px rgba(0, 255, 136, 0.4);
            }
        }

        .description-title {
            color: var(--green);
            font-size: 1.1rem;
            margin-bottom: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .description-text {
            color: var(--text);
            line-height: 1.8;
            font-size: 0.95rem;
        }

        .description-text strong {
            color: var(--accent);
            text-shadow: 0 0 5px var(--accent);
        }

        /* Formulario */
        .form-group {
            margin-bottom: 1.5rem;
            animation: slideIn 0.8s ease-out backwards;
        }

        .form-group:nth-child(1) { animation-delay: 0.1s; }
        .form-group:nth-child(2) { animation-delay: 0.2s; }
        .form-group:nth-child(3) { animation-delay: 0.3s; }

        label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--green);
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 0.05em;
        }

        input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid var(--border);
            background: rgba(0, 0, 0, 0.5);
            color: var(--text);
            font-family: 'Share Tech Mono', monospace;
            font-size: 1rem;
            border-radius: 3px;
            transition: all 0.3s ease;
            box-shadow: 0 0 5px rgba(0, 255, 136, 0.1);
        }

        input:focus {
            outline: none;
            border-color: var(--green);
            box-shadow: 0 0 15px rgba(0, 255, 136, 0.5);
            background: rgba(0, 255, 136, 0.05);
        }

        input::placeholder {
            color: rgba(200, 255, 223, 0.5);
        }

        /* Mensajes de error */
        .errors {
            background: rgba(255, 34, 68, 0.1);
            border: 2px solid var(--red);
            border-radius: 3px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            animation: shake 0.3s ease;
        }

        .errors ul {
            list-style: none;
            margin: 0;
        }

        .errors li {
            color: var(--red);
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
        }

        .errors li:before {
            content: '⚠';
            margin-right: 0.5rem;
            font-weight: bold;
        }

        .errors li:last-child {
            margin-bottom: 0;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        /* Mensaje de éxito */
        .success-message {
            background: rgba(0, 255, 136, 0.1);
            border: 2px solid var(--green);
            border-radius: 3px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            color: var(--green);
            text-align: center;
            animation: slideIn 0.6s ease-out;
            font-weight: 700;
            text-shadow: 0 0 5px var(--green);
        }

        /* Botón */
        .btn-container {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        button, .btn {
            flex: 1;
            padding: 0.9rem 1.5rem;
            border: 2px solid var(--border);
            background: rgba(0, 255, 136, 0.1);
            color: var(--green);
            font-family: 'Share Tech Mono', monospace;
            font-size: 1rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            cursor: pointer;
            border-radius: 3px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            box-shadow: 0 0 10px rgba(0, 255, 136, 0.2);
        }

        button:hover, .btn:hover {
            background: rgba(0, 255, 136, 0.3);
            border-color: var(--green);
            box-shadow: 0 0 20px rgba(0, 255, 136, 0.5);
            transform: scale(1.02);
        }

        button:active {
            transform: scale(0.98);
        }

        .btn-secondary {
            background: rgba(0, 0, 0, 0.3);
            color: var(--text);
            border-color: var(--text);
        }

        .btn-secondary:hover {
            background: rgba(200, 255, 223, 0.15);
            box-shadow: 0 0 20px rgba(200, 255, 223, 0.3);
        }

        /* Responsive */
        @media (max-width: 600px) {
            .screen {
                padding: 1rem;
            }

            .logo {
                font-size: 2rem;
            }

            .title {
                font-size: 1.4rem;
            }

            .subtitle {
                font-size: 0.85rem;
            }

            .description-box {
                padding: 1rem;
            }

            .btn-container {
                flex-direction: column;
            }
        }

        /* Efecto glitch */
        @keyframes glitch {
            0%, 100% {
                text-shadow: 0 0 10px var(--green), 0 0 20px var(--green);
            }
            50% {
                text-shadow: -2px 0 var(--red), 2px 0 var(--accent), 0 0 10px var(--green);
            }
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="screen">
        <div class="container">
            <!-- Header -->
            <div class="header">
                <div class="logo">⟨DAWSCAPE⟩</div>
                <div class="title">SISTEMA DE INSCRIPCIÓN</div>
                <div class="subtitle">Pre-Registro de Equipos</div>
            </div>

            <!-- Descripción de la experiencia -->
            <div class="description-box">
                <div class="description-title">📡 BIENVENIDO A DAWSCAPE</div>
                <div class="description-text">
                    Estáis a punto de entrar en un <strong>Escape Room lleno de misterios</strong> donde tendréis que resolver acertijos, descifrar códigos y trabajar en equipo para escapar a tiempo.
                    <br><br>
                    Cada prueba os acercará más a la salida. ¿Conseguiréis resolver todos los enigmas antes de que se agote el tiempo?
                    <br><br>
                    <strong>⏱️ Tiempo límite: 60 minutos</strong>
                </div>
            </div>

            <!-- Mensajes de éxito -->
            @if (session('success'))
                <div class="success-message">
                    ✓ {{ session('success') }}
                </div>
            @endif

            <!-- Errores de validación -->
            @if ($errors->any())
                <div class="errors">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Formulario -->
            <form method="POST" action="{{ route('inscripcion.guardar') }}" class="form">
                @csrf

                <!-- Nombre del Equipo -->
                <div class="form-group">
                    <label for="nombre_equipo">Nombre del Equipo</label>
                    <input
                        type="text"
                        id="nombre_equipo"
                        name="nombre_equipo"
                        placeholder="Ej: Los Detectives Digitales"
                        value="{{ old('nombre_equipo') }}"
                        required
                        maxlength="60"
                    />
                </div>

                <!-- Correo Electrónico -->
                <div class="form-group">
                    <label for="correo">Correo Electrónico</label>
                    <input
                        type="email"
                        id="correo"
                        name="correo"
                        placeholder="equipo@ejemplo.com"
                        value="{{ old('correo') }}"
                        required
                        maxlength="120"
                    />
                </div>

                <!-- Clase (Opcional) -->
                <div class="form-group">
                    <label for="clase">Clase / Grupo (Opcional)</label>
                    <input
                        type="text"
                        id="clase"
                        name="clase"
                        placeholder="Ej: 2º DAW"
                        value="{{ old('clase') }}"
                        maxlength="20"
                    />
                </div>

                <!-- Botones -->
                <div class="btn-container">
                    <button type="submit">
                        <span>🚀 REGISTRAR EQUIPO</span>
                    </button>
                    <a href="{{ route('inicio') }}" class="btn btn-secondary">
                        VOLVER
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
