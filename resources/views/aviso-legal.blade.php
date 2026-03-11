<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DAWSCAPE | Aviso Legal y RGPD</title>
    <link href="https://fonts.googleapis.com/css2?family=Share+Tech+Mono&family=Orbitron:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --green: #00ff88;
            --red: #ff2244;
            --bg: #020c06;
            --panel: #041a0e;
            --border: #00ff8840;
            --text: #c8ffdf;
        }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: 'Share Tech Mono', monospace;
            padding: 2rem;
            line-height: 1.6;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: repeating-linear-gradient(0deg, transparent, transparent 2px, rgba(0,255,136,0.013) 2px, rgba(0,255,136,0.013) 4px);
            pointer-events: none;
            z-index: 1000;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: var(--panel);
            border: 1px solid var(--border);
            padding: 3rem;
            position: relative;
            box-shadow: 0 0 30px rgba(0, 255, 136, 0.05);
        }

        .container::before {
            content: '// DOCUMENTO_LEGAL_CONFIDENCIAL';
            position: absolute;
            top: -0.7rem; left: 1rem;
            background: var(--bg);
            padding: 0 0.5rem;
            color: var(--green);
            font-size: 0.8rem;
            letter-spacing: 0.2em;
        }

        h1 {
            font-family: 'Orbitron', sans-serif;
            color: var(--green);
            font-size: 2rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 2rem;
            border-bottom: 1px solid var(--border);
            padding-bottom: 1rem;
        }

        h2 {
            color: var(--green);
            font-size: 1.2rem;
            margin-top: 2.5rem;
            margin-bottom: 1rem;
            text-transform: uppercase;
        }

        /* MEJORADO: Párrafo con mejor contraste */
        p {
            margin-bottom: 1.2rem;
            color: #b0e0bf;
            text-align: justify;
            text-shadow: 0 0 4px rgba(200, 255, 223, 0.1);
        }

        .alert-box {
            border-left: 4px solid var(--red);
            background: rgba(255, 34, 68, 0.05);
            padding: 1rem 1.5rem;
            margin: 2rem 0;
        }

        .alert-box p {
            color: #ff8899;
            margin: 0;
        }

        .btn-back {
            display: inline-block;
            margin-top: 3rem;
            padding: 0.8rem 2rem;
            border: 1px solid var(--green);
            color: var(--green);
            text-decoration: none;
            text-transform: uppercase;
            font-weight: bold;
            transition: all 0.3s;
        }

        .btn-back:hover {
            background: var(--green);
            color: var(--bg);
            box-shadow: 0 0 15px var(--green);
        }

        @media (max-width: 600px) {
            .container { padding: 1.5rem; }
            h1 { font-size: 1.5rem; }
            body { padding: 1rem; }
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Aviso Legal y Política de Privacidad</h1>

    <h2>1. Información General y Autoría</h2>
    <p>
        El presente proyecto web, denominado <strong>DAWSCAPE</strong> (en adelante, la "Plataforma"),
        ha sido desarrollado íntegramente por los alumnos del Ciclo Formativo de Grado Superior en Desarrollo de Aplicaciones Web (DAW) del <strong>XTART</strong>.
    </p>
    <p>
        Esta plataforma tiene una finalidad estrictamente educativa, académica y de demostración técnica de los conocimientos adquiridos durante el ciclo formativo. No persigue fines comerciales ni de lucro.
    </p>

    <div class="alert-box">
        <p><strong>RESTRICTED ACCESS_</strong> // Queda terminantemente prohibida la copia, reproducción, distribución, comercialización o modificación no autorizada del código fuente, diseño, mecánicas de juego o cualquier elemento visual que compone esta Plataforma. Todo el contenido es propiedad intelectual de sus creadores (alumnos DAW).</p>
    </div>

    <h2>2. Protección de Datos (RGPD)</h2>
    <p>
        En cumplimiento del <em>Reglamento (UE) 2016/679 General de Protección de Datos (RGPD)</em> y la <em>Ley Orgánica 3/2018 de Protección de Datos Personales y garantía de los derechos digitales (LOPDGDD)</em>, se informa a los usuarios de lo siguiente:
    </p>
    <ul>
        <li><strong style="color:var(--green)">Datos Recabados:</strong> El sistema únicamente recabará el correo electrónico institucional (terminado en @estudiante.xtart.com) o aquellos datos estrictamente necesarios para el registro y funcionamiento de la actividad (escape room).</li>
        <li><strong style="color:var(--green)">Finalidad:</strong> Los datos se utilizarán exclusiva y limitadamente para el desarrollo del juego, control de acceso de los equipos, registro de tiempos en el ranking y gestión de incidencias técnicas.</li>
        <li><strong style="color:var(--green)">Conservación:</strong> Los datos se conservarán únicamente durante el tiempo que dure la actividad académica y el periodo de evaluación, procediéndose a su destrucción segura de las bases de datos una vez finalizado el proyecto.</li>
        <li><strong style="color:var(--green)">Cesión a terceros:</strong> En ningún caso los datos recopilados serán cedidos, vendidos o transferidos a terceras entidades gubernamentales o privadas fuera del ámbito académico estricto de la prueba.</li>
    </ul>

    <h2>3. Derechos de los Usuarios</h2>
    <p>
        Cualquier participante ("Agente") tiene derecho a acceder a sus datos, rectificarlos, solicitar su supresión ("derecho al olvido"), limitar su tratamiento o oponerse al mismo. Para ejercer estos derechos, el usuario deberá contactar con los administradores del sistema (profesores o creadores DAW) a través de los canales de comunicación del centro.
    </p>

    <h2>4. Uso de Cookies (Rastreadores del Sistema)</h2>
    <p>
        El sistema utiliza identificadores de sesión técnicos (cookies de sesión) requeridos indispensablemente para mantener activa la conexión del "Agente" con el nodo central mientras dure la prueba. No se utilizan cookies publicitarias ni de rastreo comportamental de terceros. El uso de la plataforma implica la aceptación de estas cookies técnicas.
    </p>

    <h2>5. Responsabilidad</h2>
    <p>
        El uso del sistema se realiza bajo la propia responsabilidad del usuario. Los creadores no se hacen responsables de problemas técnicos locales en el dispositivo del usuario ni de interrupciones externas que puedan afectar al cronómetro o al desarrollo de las pruebas.
    </p>

    <a href="{{ route('inscripcion.mostrar') }}" class="btn-back">← VOLVER A INSCRIPCIÓN</a>
</div>

</body>
</html>
