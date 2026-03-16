<!DOCTYPE html>
<html lang="es" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top 10 Live | DAWSCAPE</title>
    <!-- TIPOGRAFÍA: Terminal Monospace -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Share+Tech+Mono&display=swap" rel="stylesheet">
    <!-- ICONOS -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    @vite(['resources/js/app.js'])
    <style>
        :root {
            --bg-color: #020c06;
            --surface-color: #0a1f12;
            --text-main: #00ff41;
            --text-muted: #1e8749;
            --border-color: rgba(0, 255, 65, 0.2);

            --podium-1-border: #eab308;
            --podium-1-text: #eab308;
            --podium-2-border: #94a3b8;
            --podium-2-text: #94a3b8;
            --podium-3-border: #d97706;
            --podium-3-text: #d97706;

            --success-color: #00ff41;
            --danger-color: #ff2244;
            --warning-color: #ffcc00;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Share Tech Mono', monospace;
            background-color: var(--bg-color);
            color: var(--text-main);
            overflow: hidden; /* Sin scroll en TV */
            display: flex;
            flex-direction: column;
            height: 100vh; /* Vista completa */
        }

        /* MATRIX BG SCENE */
        #matrix-bg {
            position: fixed;
            top: 0; left: 0;
            width: 100vw; height: 100vh;
            z-index: -2;
            opacity: 0.15;
            pointer-events: none;
        }

        /* CRT SCANLINES & VIGNETTE */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: repeating-linear-gradient(0deg, transparent, transparent 2px, rgba(0,255,136,0.013) 2px, rgba(0,255,136,0.013) 4px);
            pointer-events: none;
            z-index: 1000;
        }

        body::after {
            content: '';
            position: fixed;
            inset: 0;
            background: radial-gradient(circle, transparent 50%, rgba(0,0,0,0.85) 120%);
            pointer-events: none;
            z-index: 999;
        }

        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--bg-color); }
        ::-webkit-scrollbar-thumb { background: var(--text-muted); border-radius: 4px; }

        /* HEADER */
        .header {
            text-align: center;
            padding: 1rem 0;
            border-bottom: 1px solid var(--border-color);
            background: rgba(2, 12, 6, 0.95);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header h1 {
            margin: 0;
            font-size: 2.8rem;
            font-weight: 900;
            letter-spacing: 4px;
            color: var(--text-main);
            text-transform: uppercase;
            text-shadow: 0 0 10px rgba(0, 255, 65, 0.6);
            position: relative;
            display: inline-block;
        }

        .header h1::before, .header h1::after {
            content: 'DAWSCAPE LIVE RANKING';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0.8;
        }
        .header h1::before {
            left: 2px;
            text-shadow: -2px 0 var(--danger-color);
            animation: glitch-anim-1 2s infinite linear alternate-reverse;
        }
        .header h1::after {
            left: -2px;
            text-shadow: 2px 0 #00ffff;
            animation: glitch-anim-2 3s infinite linear alternate-reverse;
        }

        @keyframes glitch-anim-1 {
            0% { clip-path: inset(20% 0 80% 0); }
            20% { clip-path: inset(60% 0 10% 0); }
            40% { clip-path: inset(40% 0 50% 0); }
            60% { clip-path: inset(80% 0 5% 0); }
            80% { clip-path: inset(10% 0 70% 0); }
            100% { clip-path: inset(30% 0 50% 0); }
        }
        @keyframes glitch-anim-2 {
            0% { clip-path: inset(10% 0 60% 0); }
            20% { clip-path: inset(30% 0 20% 0); }
            40% { clip-path: inset(70% 0 10% 0); }
            60% { clip-path: inset(20% 0 50% 0); }
            80% { clip-path: inset(90% 0 5% 0); }
            100% { clip-path: inset(50% 0 30% 0); }
        }

        /* MAIN LAYOUT */
        .main-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            padding: 1.5rem 1.5rem 2rem 1.5rem;
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
            box-sizing: border-box;
            align-items: stretch;
        }

        /* Zona superior (podio) */
        .left-col {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            width: 100%;
            flex: 0 0 auto;
            align-items: center;
            justify-content: flex-start;
        }

        /* Zona inferior (lista 4-10) */
        .right-col {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            width: 100%;
            flex: 1 1 auto;
            min-height: 0;
            overflow-y: auto;
        }

        /* SECCIÓN TÍTULOS */
        .section-title {
            font-size: 1.3rem;
            font-weight: bold;
            margin: 0 0 0.5rem 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--text-main);
            text-align: center;
            text-shadow: 0 0 6px rgba(0,255,65,0.4);
        }
        .section-title::before { content: '>_ '; color: var(--text-main); }
        .blinking-cursor {
            display: inline-block;
            width: 12px;
            height: 1.2rem;
            background-color: var(--text-main);
            animation: blink 1s step-end infinite;
            margin-left: 8px;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0; }
        }

        /* PODIUM (TOP 3) */
        .podium-container {
            display: flex;
            align-items: flex-end;
            justify-content: center;
            gap: 0.8rem;
            width: 100%;
        }

        .podium-slot {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 32%;
            min-width: 0;
        }

        /* Card (info del equipo) — flujo normal, encima del pedestal */
        .podium-card {
            background: var(--surface-color);
            border: 1px solid;
            padding: 0.8rem 0.6rem;
            border-radius: 4px;
            width: 100%;
            text-align: center;
            position: relative;
            box-sizing: border-box;
        }

        /* Pedestal (número de ranking) — debajo de la card, sin overlap */
        .podium-box {
            width: 100%;
            border-top: 2px solid;
            border-left: 1px solid;
            border-right: 1px solid;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0,0,0,0.5);
        }

        .p2 {
            min-height: 100px;
            border-color: var(--podium-2-border);
            color: var(--podium-2-text);
        }
        .p1 {
            min-height: 140px;
            border-color: var(--podium-1-border);
            border-top-width: 3px;
            color: var(--podium-1-text);
        }
        .p3 {
            min-height: 70px;
            border-color: var(--podium-3-border);
            color: var(--podium-3-text);
        }

        .podium-rank {
            font-size: 3rem;
            font-weight: bold;
            line-height: 1;
            text-shadow: 0 0 6px currentColor;
            padding: 0.5rem 0;
        }
        .podium-rank::before { content: '['; opacity: 0.5; }
        .podium-rank::after { content: ']'; opacity: 0.5; }

        .podium-name {
            font-size: 1.2rem;
            font-weight: bold;
            color: currentColor;
            margin: 0 0 4px 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.3;
        }
        .p1-card .podium-name { font-size: 1.5rem; color: var(--podium-1-text); }

        .podium-time {
            font-family: 'Share Tech Mono', monospace;
            font-size: 1.3rem;
            font-weight: bold;
            color: var(--text-main);
            text-shadow: 0 0 4px rgba(0,255,65,0.4);
            letter-spacing: 1px;
        }

        .podium-phase {
            font-size: 0.9rem;
            color: var(--text-muted);
            margin-top: 5px;
            font-weight: normal;
            border-top: 1px dashed var(--text-muted);
            padding-top: 5px;
        }

        .p1-badge {
            display: inline-block;
            background: var(--bg-color);
            color: var(--danger-color);
            padding: 2px 8px;
            border: 1px solid var(--danger-color);
            font-weight: bold;
            font-size: 0.75rem;
            letter-spacing: 2px;
            margin-bottom: 6px;
            white-space: nowrap;
        }

        .gap-info {
            font-size: 0.8rem;
            color: var(--danger-color);
            font-weight: bold;
            margin-top: 4px;
        }

        /* LISTA (4-10) TERMINAL STYLE */
        .list-container {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
            flex: 1;
            max-height: calc(100% - 2.5rem);
        }

        .list-item {
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid var(--border-color);
            border-left: 3px solid var(--text-main);
            padding: 0.6rem 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            font-family: 'Share Tech Mono', monospace;
            border-radius: 2px;
        }
        .list-item:nth-child(even) {
            background: rgba(0, 255, 65, 0.03);
        }

        .move-up { animation: flash-green-hack 2s; }
        .move-down { animation: flash-red-hack 2s; }

        @keyframes flash-green-hack { 0% { background-color: rgba(0, 255, 65, 0.2); } 100% { background-color: transparent; } }
        @keyframes flash-red-hack { 0% { background-color: rgba(255, 34, 68, 0.2); } 100% { background-color: transparent; } }

        .list-pos {
            font-size: 1.3rem;
            font-weight: bold;
            color: var(--text-muted);
            width: 60px;
            text-align: left;
        }
        .list-pos::before { content: '[#'; }
        .list-pos::after { content: ']'; }

        .list-info { flex: 1; min-width: 0; display: flex; flex-direction: column; gap: 4px; }

        .list-heading {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .list-terminal-prefix {
            color: var(--text-muted);
            font-size: 1rem;
        }

        .list-info h4 {
            margin: 0;
            font-size: 1.15rem;
            font-weight: bold;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            color: var(--text-main);
            line-height: 1.4;
        }

        .list-phase {
            font-size: 0.95rem;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 4px;
        }

        .list-time {
            font-size: 1.2rem;
            font-weight: bold;
            color: var(--warning-color);
            text-align: right;
            letter-spacing: 1px;
            white-space: nowrap;
        }

        /* PROGRESS BAR HACKER STYLE */
        .progress-wrapper {
            margin-top: 6px;
            height: 10px;
            background: rgba(0, 255, 65, 0.06);
            border-radius: 1px;
            border: 1px solid var(--border-color);
            position: relative;
            width: 100%;
            display: flex;
            align-items: center;
            padding: 1px;
        }

        .progress-fill {
            height: 100%;
            background: var(--text-main);
            transition: width 1s ease-in-out;
        }

        /* FINISHERS SECTION */
        .finishers-panel {
            background: rgba(0, 0, 0, 0.4);
            border: 1px dashed var(--border-color);
            padding: 1rem;
            font-family: 'Share Tech Mono', monospace;
            border-radius: 2px;
        }

        .finisher-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.8rem 0;
            border-bottom: 1px dashed var(--border-color);
        }
        .finisher-item:last-child { border-bottom: none; }

        .finisher-pos { color: var(--text-muted); font-weight: bold; font-size: 1.1rem; width: 60px; }
        .finisher-pos::before { content: 'SYS:'; color: var(--success-color); margin-right: 5px; }

        .finisher-name { font-weight: bold; font-size: 1.1rem; flex: 1; color: var(--text-main); }
        .finisher-time { font-size: 1.1rem; font-weight: bold; color: var(--warning-color); }
        .finisher-hour { font-size: 0.85rem; color: var(--text-muted); text-align: right; margin-top: 4px; }

        /* Ocultar completamente sección de finalizados en TV */
        #finishers-section {
            display: none !important;
        }


        /* FULLSCREEN CELEBRATION OVERLAY (MATRIX) */
        #celebration-overlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.9);
            backdrop-filter: blur(5px);
            z-index: 9999;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.5s;
        }
        #celebration-overlay.active { opacity: 1; pointer-events: all; }

        .celeb-title {
            font-size: 3rem;
            color: var(--success-color);
            font-weight: bold;
            font-family: 'Share Tech Mono', monospace;
            text-transform: uppercase;
            animation: glitch-anim-1 1s infinite alternate;
            text-shadow: 0 0 8px rgba(0,255,65,0.5);
            margin-bottom: 0;
        }
        .celeb-title::before { content: '>> ACCESS GRANTED // '; }

        .celeb-team {
            font-size: 5rem;
            color: #ffffff;
            font-weight: bold;
            margin: 10px 0;
            animation: popIn 1s cubic-bezier(0.175, 0.885, 0.32, 1.275) 0.2s both, text-glow 2s infinite alternate;
            text-shadow: 0 0 10px var(--success-color);
        }
        .celeb-time {
            font-size: 2.5rem;
            color: var(--warning-color);
            font-weight: bold;
            text-shadow: 0 0 6px rgba(255,204,0,0.5);
            animation: popIn 1s cubic-bezier(0.175, 0.885, 0.32, 1.275) 0.4s both;
        }

        @keyframes text-glow {
            0% { text-shadow: 0 0 10px var(--success-color), 0 0 20px var(--success-color); }
            100% { text-shadow: 0 0 20px var(--success-color), 0 0 40px var(--success-color), 0 0 60px #ffffff; }
        }

        @keyframes popIn {
            0% { transform: scale(0.5); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }

        /* MATRIX CANVAS */
        #confetti { position: fixed; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: -1; }

        /* ===== MOBILE (<768px) ===== */
        @media (max-width: 767px) {
            body {
                overflow-y: auto;
                overflow-x: hidden;
                height: auto;
                min-height: 100vh;
            }
            .header h1 {
                font-size: 1.3rem;
                letter-spacing: 2px;
            }
            .main-container {
                padding: 0.75rem;
                gap: 0.75rem;
            }
            .section-title {
                font-size: 0.95rem;
                letter-spacing: 1px;
                gap: 6px;
                margin-bottom: 0.4rem;
            }
            /* Podio: vertical stack en móvil */
            .podium-container {
                flex-direction: column;
                align-items: stretch;
                gap: 0.6rem;
            }
            .podium-slot {
                width: 100%;
                flex-direction: row;
                align-items: stretch;
            }
            .podium-card {
                flex: 1;
                min-width: 0;
                padding: 0.6rem;
                border-radius: 4px 0 0 4px;
                text-align: left;
            }
            .podium-box {
                width: 60px;
                min-width: 60px;
                border-top: none;
                border-left: 2px solid;
                border-right: none;
                border-bottom: none;
                border-radius: 0 4px 4px 0;
            }
            .p1, .p2, .p3 {
                min-height: auto;
            }
            .podium-rank { font-size: 2rem; }
            .podium-name { font-size: 1rem; }
            .p1-card .podium-name { font-size: 1.15rem; }
            .podium-time { font-size: 1.1rem; }
            .podium-phase { font-size: 0.7rem; }
            .p1-badge { font-size: 0.65rem; padding: 1px 5px; }
            .gap-info { font-size: 0.7rem; }
            /* Lista compacta */
            .list-item {
                padding: 0.4rem 0.6rem;
                gap: 0.5rem;
            }
            .list-pos { font-size: 0.95rem; width: 42px; }
            .list-info h4 { font-size: 0.95rem; }
            .list-terminal-prefix { display: none; }
            .list-time { font-size: 1rem; }
            .list-phase { font-size: 0.75rem; }
            .progress-wrapper { height: 6px; }
        }

        /* ===== TV / LARGE SCREEN (40-55") ===== */
        @media (min-width: 1400px) {
            .header h1 { font-size: 3.2rem; letter-spacing: 5px; }
            .main-container { max-width: 1600px; gap: 1.2rem; padding: 1.2rem 2rem; }
            .section-title { font-size: 1.8rem; margin-bottom: 0.6rem; }

            .podium-container { gap: 1.2rem; }
            .p1 { min-height: 200px; }
            .p2 { min-height: 150px; }
            .p3 { min-height: 110px; }
            .podium-rank { font-size: 3.8rem; }
            .podium-name { font-size: 1.8rem; }
            .p1-card .podium-name { font-size: 2.2rem; }
            .podium-time { font-size: 1.8rem; }
            .podium-phase { font-size: 1rem; }
            .podium-card { padding: 1.2rem; }

            .list-item { padding: 0.8rem 1.2rem; gap: 1.2rem; }
            .list-pos { font-size: 1.6rem; width: 75px; }
            .list-info h4 { font-size: 1.6rem; }
            .list-phase { font-size: 1rem; }
            .list-time { font-size: 1.6rem; }
            .progress-wrapper { height: 12px; }
        }

    </style>
</head>
<body>

    <!-- FONDO MATRIX PERMANENTE -->
    <canvas id="matrix-bg"></canvas>

    <header class="header">
        <h1>DAWSCAPE LIVE RANKING</h1>
    </header>

    <div class="main-container">
        <!-- COLUMNA IZQUIERDA: PODIO -->
        <div class="left-col">
            <h2 class="section-title">
                <i class="ph-fill ph-terminal-window"></i> CABEZA DE CARRERA <span class="blinking-cursor"></span>
            </h2>

            <div class="podium-container" id="podium-container">
                <!-- Se rellena por JS -->
                <div style="text-align:center; width:100%; color:var(--text-muted); font-size:1.5rem; margin-top:50px;">
                    <i class="ph ph-spinner-gap ph-spin" style="font-size:3rem;"></i><br>Conectando con servidores centrales...
                </div>
            </div>
        </div>

        <!-- COLUMNA DERECHA: RESTO DE EQUIPOS Y FINALIZADOS -->
        <div class="right-col">
            <h2 class="section-title" id="activos-title">
                <i class="ph-fill ph-code"></i> PERSIGUIENDO (Top 4-10) <span class="blinking-cursor"></span>
            </h2>
            <div class="list-container" id="list-container">
                <!-- Se rellena por JS -->
            </div>

            <div id="finishers-section" style="display: none; margin-top: 1.5rem;">
                <h2 class="section-title" style="color:var(--warning-color); text-shadow:0 0 8px var(--warning-color);">
                    <i class="ph-fill ph-folder-lock"></i> > EQUIPOS_FINALIZADOS.exe <span class="blinking-cursor"></span>
                </h2>
                <div class="finishers-panel" id="finishers-container">
                    <!-- Se rellena por JS -->
                </div>
            </div>
        </div>
    </div>



    <!-- OVERLAY CELEBRACIÓN MATRIX -->
    <div id="celebration-overlay">
        <canvas id="confetti"></canvas>
        <div class="celeb-title">SISTEMA COMPROMETIDO</div>
        <div class="celeb-team" id="celeb-team-name">NOMBRE_EQUIPO</div>
        <div class="celeb-time" id="celeb-team-time">00m 00s</div>
    </div>

    <!-- SCRIPT DE LÓGICA (SE MANTIENE INTACTO RESPECTO A DATOS) -->
    <script>
        // BACKGROUND MATRIX EFFECT
        (function() {
            const bgCanvas = document.getElementById('matrix-bg');
            const bCtx = bgCanvas.getContext('2d');

            function resizeBg() {
                bgCanvas.width = window.innerWidth;
                bgCanvas.height = window.innerHeight;
            }
            window.addEventListener('resize', resizeBg);
            resizeBg();

            const chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#$%^&*";
            const fontSize = 14;
            let columns = bgCanvas.width / fontSize;
            const drops = [];
            for (let x = 0; x < columns; x++) drops[x] = 1;

            function drawBgMatrix() {
                bCtx.fillStyle = "rgba(2, 12, 6, 0.05)";
                bCtx.fillRect(0, 0, bgCanvas.width, bgCanvas.height);
                bCtx.fillStyle = "#00ff41";
                bCtx.font = fontSize + "px 'Share Tech Mono'";

                for (let i = 0; i < drops.length; i++) {
                    const text = chars.charAt(Math.floor(Math.random() * chars.length));
                    bCtx.fillText(text, i * fontSize, drops[i] * fontSize);
                    if (drops[i] * fontSize > bgCanvas.height && Math.random() > 0.975) {
                        drops[i] = 0;
                    }
                    drops[i]++;
                }
            }
            setInterval(drawBgMatrix, 50);
        })();

        // Variables de estado
        let previousTeamsState = {};
        let knownFinishers = new Set();

        // MATRIX CELEBRATION EFFECT (REPLACES CONFETTI)
        const canvas = document.getElementById('confetti');
        const ctx = canvas.getContext('2d');
        let cCols = 0;
        let cDrops = [];
        const cFontSize = 20;
        let isConfettiActive = false;

        function initMatrixCelebration() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
            cCols = canvas.width / cFontSize;
            cDrops = [];
            for (let x = 0; x < cCols; x++) cDrops[x] = Math.random() * -100; // start drops off screen
        }

        function drawMatrixCelebration() {
            if(!isConfettiActive) {
                ctx.clearRect(0,0,canvas.width,canvas.height);
                return;
            }

            ctx.fillStyle = "rgba(0,0,0,0.1)"; // Darken slowly for trails
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            ctx.fillStyle = "#00ff41";
            ctx.font = cFontSize + "px 'Share Tech Mono'";

            for (let i = 0; i < cDrops.length; i++) {
                const text = Math.random() > 0.5 ? '1' : '0';
                ctx.fillText(text, i * cFontSize, cDrops[i] * cFontSize);
                if (cDrops[i] * cFontSize > canvas.height && Math.random() > 0.95) {
                    cDrops[i] = 0;
                }
                cDrops[i] += 1.5; // fall speed
            }
            requestAnimationFrame(drawMatrixCelebration);
        }

        function triggerCelebration(teamName, timeFormatted) {
            const overlay = document.getElementById('celebration-overlay');
            document.getElementById('celeb-team-name').innerText = teamName;
            document.getElementById('celeb-team-time').innerText = "TIEMPO: " + timeFormatted;

            overlay.classList.add('active');
            isConfettiActive = true;
            initMatrixCelebration();
            drawMatrixCelebration();

            setTimeout(() => {
                isConfettiActive = false;
                overlay.classList.remove('active');
            }, 7000);
        }

        window.addEventListener('resize', () => {
            if(isConfettiActive) initMatrixCelebration();
        });

        // Utilidad global para medallas/logros (usado en lista y podio)
        function renderMedals(logros) {
            if(!logros || logros.length === 0) return '';
            let html = '<div style="display:inline-flex; gap:5px; margin-left:10px; vertical-align:middle;">';
            logros.forEach(logro => {
                if (logro === 'speedrunner_ninja') html += '<i class="ph-fill ph-lightning" title="Speedrunner Ninja" style="color:#eab308; font-size:1.4rem; filter: drop-shadow(0 0 5px #eab308);"></i>';
                if (logro === 'cerebro_intacto') html += '<i class="ph-fill ph-brain" title="Cerebro Intacto" style="color:#ec4899; font-size:1.4rem; filter: drop-shadow(0 0 5px #ec4899);"></i>';
                if (logro === 'hackerman') html += '<i class="ph-fill ph-terminal-window" title="Hackerman" style="color:#10b981; font-size:1.4rem; filter: drop-shadow(0 0 5px #10b981);"></i>';
            });
            html += '</div>';
            return html;
        }

        // FETCH DATA
        async function fetchTop10() {
            try {
                const response = await fetch('/top10/datos');
                if (!response.ok) {
                    console.error('Error HTTP en /top10/datos:', response.status, response.statusText);
                    return;
                }
                const data = await response.json();
                console.log('Top10 datos recibidos:', data);

                if (!data || !Array.isArray(data.top10) || data.top10.length === 0) {
                    console.warn('Top10: no hay datos o formato incorrecto', data);
                    // mensaje de espera en el podio
                    const podiumContainer = document.getElementById('podium-container');
                    podiumContainer.innerHTML = `<div style="text-align:center; width:100%; color:var(--text-muted); font-size:1.5rem; margin-top:50px;"><i class="ph ph-terminal-window" style="font-size:4rem; opacity:0.3;"></i><br>Esperando conexiones de equipos...</div>`;
                    document.getElementById('list-container').innerHTML = '';
                    return;
                }

                renderDashboard(data);
            } catch (err) {
                console.error("Error fetching Top 10 data:", err);
            }
        }

        function renderDashboard(data) {
            const top3 = data.top10.slice(0, 3);
            const remaining = data.top10.slice(3, 10);

            // RENDER PODIO
            const podiumContainer = document.getElementById('podium-container');
            if (top3.length === 0) {
                podiumContainer.innerHTML = `<div style="text-align:center; width:100%; color:var(--text-muted); font-size:1.5rem; margin-top:50px;"><i class="ph ph-terminal-window" style="font-size:4rem; opacity:0.3;"></i><br>Esperando conexiones entrantes...</div>`;
            } else {
                let podiumHTML = '';
                if(top3[1]) podiumHTML += createPodiumSlot(top3[1], 2, 'p2');
                else podiumHTML += `<div class="podium-slot"></div>`;

                if(top3[0]) podiumHTML += createPodiumSlot(top3[0], 1, 'p1');
                else podiumHTML += `<div class="podium-slot"></div>`;

                if(top3[2]) podiumHTML += createPodiumSlot(top3[2], 3, 'p3');
                else podiumHTML += `<div class="podium-slot"></div>`;

                podiumContainer.innerHTML = podiumHTML;
            }

            // RENDER LISTA 4-10
            const listContainer = document.getElementById('list-container');
            document.getElementById('activos-title').innerHTML = `<i class="ph-fill ph-code"></i> PERSIGUIENDO (Top 4-10) <span class="blinking-cursor"></span>`;

            const renderMedals = (logros) => {
                if(!logros || logros.length === 0) return '';
                let html = '<div style="display:inline-flex; gap:5px; margin-left:10px; vertical-align:middle;">';
                logros.forEach(logro => {
                    if (logro === 'speedrunner_ninja') html += '<i class="ph-fill ph-lightning" title="Speedrunner Ninja" style="color:#eab308; font-size:1.4rem; filter: drop-shadow(0 0 5px #eab308);"></i>';
                    if (logro === 'cerebro_intacto') html += '<i class="ph-fill ph-brain" title="Cerebro Intacto" style="color:#ec4899; font-size:1.4rem; filter: drop-shadow(0 0 5px #ec4899);"></i>';
                    if (logro === 'hackerman') html += '<i class="ph-fill ph-terminal-window" title="Hackerman" style="color:#10b981; font-size:1.4rem; filter: drop-shadow(0 0 5px #10b981);"></i>';
                });
                html += '</div>';
                return html;
            };

            let listHTML = '';
            remaining.forEach((team, index) => {
                const rank = team.rank ?? (index + 4);
                const oldRank = previousTeamsState[team.id] ? previousTeamsState[team.id].rank : rank;
                let animClass = '';
                if(rank < oldRank) animClass = 'move-up';
                else if(rank > oldRank) animClass = 'move-down';

                // For hacker theme we override the dynamic color if they are abandoned to gray
                const isTimeout = team.estado === 'timeout';
                const isFinalizado = team.estado === 'finalizado';
                const colorCode = isTimeout ? '#64748b' : team.color_hex;

                const badge = isFinalizado
                    ? `<span style="margin-left:10px; color: var(--success-color); text-shadow: 0 0 6px var(--success-color);">✓ FINALIZADO</span>`
                    : (isTimeout ? `<span style="margin-left:10px; color: var(--text-muted);">TIEMPO AGOTADO</span>` : '');

                listHTML += `
                <div class="list-item ${animClass}" ${isTimeout ? 'style="opacity:0.4;"' : ''}>
                    <div class="list-pos">${rank < 10 ? '0'+rank : rank}</div>
                    <div class="list-info">
                        <div class="list-heading">
                            <span class="list-terminal-prefix">user@dawscape:~$</span>
                            <h4 style="color: ${colorCode};"><i class="ph-fill ${team.icono || 'ph-users'}" style="margin-right:8px; font-size:1.1em; vertical-align:middle;"></i>${team.nombre_equipo} ${renderMedals(team.logros)} ${badge}</h4>
                        </div>
                        <div class="list-phase">
                            <i class="ph ${isFinalizado ? 'ph-check-circle' : (isTimeout ? 'ph-x-circle' : 'ph-spinner-gap ph-spin')}"></i>
                            ${isFinalizado ? '[COMPLETADO]' : (isTimeout ? '[DETENIDO]' : '[EJECUTANDO]')}: ${team.nombre_prueba}
                        </div>
                        <div class="progress-wrapper">
                            <div class="progress-fill" style="width: ${team.progreso_porcentaje}%;"></div>
                        </div>
                    </div>
                    <div class="list-time">
                        ${team.tiempo_formateado}
                    </div>
                </div>`;
            });
            listContainer.innerHTML = listHTML;

            // RENDER FINALIZADOS
            const finSection = document.getElementById('finishers-section');
            const finContainer = document.getElementById('finishers-container');
            if(data.finalizados.length > 0) {
                finSection.style.display = 'block';
                let finHTML = '';
                data.finalizados.forEach((team, index) => {
                    const formatRank = index+1 < 10 ? '0'+(index+1) : (index+1);
                    finHTML += `
                    <div class="finisher-item">
                        <div class="finisher-pos">${formatRank}</div>
                        <div class="finisher-name">${team.nombre_equipo}</div>
                        <div style="text-align:right;">
                            <div class="finisher-time">${team.tiempo_formateado}</div>
                            <div class="finisher-hour"><i class="ph ph-clock"></i> COMPILADO @ ${team.hora_fin}</div>
                        </div>
                    </div>`;

                    if(!knownFinishers.has(team.id)) {
                        knownFinishers.add(team.id);
                        if(Object.keys(previousTeamsState).length > 0) {
                            triggerCelebration(team.nombre_equipo, team.tiempo_formateado);
                        }
                    }
                });
                finContainer.innerHTML = finHTML;
            } else {
                finSection.style.display = 'none';
            }

            let newState = {};
            data.top10.forEach((t, i) => { newState[t.id] = { rank: (t.rank ?? (i+1)) }; });
            previousTeamsState = newState;

            if(knownFinishers.size === 0) {
                data.finalizados.forEach(t => knownFinishers.add(t.id));
            }
        }

        function createPodiumSlot(team, rank, boxClass) {
            const badge = rank === 1 ? `<div class="p1-badge"><i class="ph-fill ph-warning-circle"></i> ROOT_ACCESS</div>` : '';
            const isTimeout = team.estado === 'timeout';
            const isFinalizado = team.estado === 'finalizado';
            const gap = rank > 1 && team.gap && !isTimeout ? `<div class="gap-info">${team.gap}</div>` : '';
            const opacity = isTimeout ? 'opacity: 0.4;' : '';
            const titleStatus = isFinalizado ? '(✓ FINALIZADO)' : (isTimeout ? '(ERR_TIMEOUT)' : '');
            const finBorder = isFinalizado ? `border-color: var(--success-color);` : `border-color: ${team.color_hex};`;

            return `
            <div class="podium-slot" style="${opacity}">
                <div class="podium-card ${rank===1 ? 'p1-card' : ''}" style="${finBorder}">
                    ${badge}
                    <div style="font-size: 2rem; margin-bottom: 4px; color: ${team.color_hex};"><i class="ph ${team.icono}"></i></div>
                    <div class="podium-name" style="color: ${team.color_hex};">${team.nombre_equipo}</div>
                    <div style="margin: 4px 0;">${renderMedals(team.logros)}</div>
                    <div class="podium-time">${team.tiempo_formateado}</div>
                    ${gap}
                    <div class="podium-phase">[PID:${team.id}] ${team.nombre_prueba} ${titleStatus}</div>
                    <div class="progress-wrapper" style="margin-top:6px;">
                        <div class="progress-fill" style="width: ${team.progreso_porcentaje}%; background-color: ${team.color_hex};"></div>
                    </div>
                </div>
                <div class="podium-box ${boxClass}">
                    <div class="podium-rank">0${rank}</div>
                </div>
            </div>`;
        }

        // Init Polling
        fetchTop10();
        setInterval(fetchTop10, 5000);
        // Echo listener (opcional, refuerza el refresco en tiempo real)
        document.addEventListener('DOMContentLoaded', () => {
            if (window.Echo) {
                window.Echo.channel('top10')
                    .listen('Top10Updated', () => {
                        fetchTop10();
                    });
            }
        });

    </script>
    <!-- Firma discreta -->
    <div style="position: fixed; bottom: 5px; right: 10px; font-family: 'Share Tech Mono', monospace; font-size: 0.75rem; color: rgba(0, 255, 65, 0.3); z-index: 9999; text-align: right;">
        DAWSCAPE_OS v1.0 // EOF <br>
        <a href="{{ route('legal') }}" style="color: rgba(0, 255, 65, 0.4); text-decoration: none; transition: color 0.3s;" onmouseover="this.style.color='#00ff41'" onmouseout="this.style.color='rgba(0, 255, 65, 0.4)'">SYS_POLICY</a>
    </div>
</body>
</html>
