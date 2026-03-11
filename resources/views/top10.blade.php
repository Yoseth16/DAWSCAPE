<!DOCTYPE html>
<html lang="es" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top 10 Live | DAWSCAPE</title>
    <!-- TIPOGRAFÍA -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800;900&display=swap" rel="stylesheet">
    <!-- ICONOS -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    @vite(['resources/js/app.js'])
    <style>
        :root {
            /* Modo Oscuro (Por defecto) */
            --bg-color: #0b1120;
            --surface-color: #1e293b;
            --surface-hover: #334155;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --border-color: #334155;

            --brand-color: #3b82f6;
            --brand-glow: rgba(59, 130, 246, 0.4);

            --podium-1: linear-gradient(135deg, #fef08a, #eab308);
            --podium-1-text: #713f12;
            --podium-2: linear-gradient(135deg, #e2e8f0, #94a3b8);
            --podium-2-text: #334155;
            --podium-3: linear-gradient(135deg, #fed7aa, #f97316);
            --podium-3-text: #7c2d12;

            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
        }

        [data-theme="light"] {
            --bg-color: #f8fafc;
            --surface-color: #ffffff;
            --surface-hover: #f1f5f9;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --brand-glow: rgba(59, 130, 246, 0.15);
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: background-color 0.5s ease, color 0.5s ease;
        }

        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--bg-color); }
        ::-webkit-scrollbar-thumb { background: var(--border-color); border-radius: 4px; }

        /* HEADER LOGO */
        .header {
            text-align: center;
            padding: 1rem 0;
            border-bottom: 1px solid var(--border-color);
            background: rgba(11, 17, 32, 0.8);
            backdrop-filter: blur(10px);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        [data-theme="light"] .header { background: rgba(255, 255, 255, 0.9); }
        .header h1 {
            margin: 0;
            font-size: 2.5rem;
            font-weight: 900;
            letter-spacing: 2px;
            background: linear-gradient(90deg, #60a5fa, #a855f7);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            text-transform: uppercase;
        }

        /* MAIN LAYOUT */
        .main-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 3rem;
            padding: 2rem 1rem 6rem 1rem; /* Margen inferior para no solapar el ticker */
            max-width: 1200px; /* Reducido para que la lista no se extienda demasiado en TV */
            margin: 0 auto;
            width: 100%;
            box-sizing: border-box;
            align-items: center;
        }

        .left-col { display: flex; flex-direction: column; gap: 2rem; width: 100%; max-width: 1000px; align-items: center; }
        .right-col { display: flex; flex-direction: column; gap: 1.5rem; width: 100%; max-width: 1000px; }

        /* SECCIÓN TÍTULOS */
        .section-title {
            font-size: 1.8rem;
            font-weight: 800;
            margin: 0 0 1rem 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-main);
            text-align: center;
        }

        /* PODIUM (TOP 3) */
        .podium-container {
            display: flex;
            align-items: flex-end;
            justify-content: center;
            gap: 1.5rem;
            height: 400px;
            position: relative;
            width: 100%;
        }

        .podium-slot {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 32%;
            position: relative;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .podium-box {
            width: 100%;
            border-radius: 16px 16px 0 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding-top: 1.5rem;
            position: relative;
            box-shadow: 0 -10px 30px rgba(0,0,0,0.1);
        }
        [data-theme="dark"] .podium-box { box-shadow: 0 -10px 40px rgba(0,0,0,0.4); }

        .p2 { height: 220px; background: var(--podium-2); color: var(--podium-2-text); }
        .p1 { height: 300px; background: var(--podium-1); color: var(--podium-1-text); z-index: 10; box-shadow: 0 -10px 50px rgba(234, 179, 8, 0.3); }
        .p3 { height: 160px; background: var(--podium-3); color: var(--podium-3-text); }

        .podium-rank {
            font-size: 4rem;
            font-weight: 900;
            line-height: 1;
            opacity: 0.8;
            margin-bottom: 0.5rem;
        }

        .podium-card {
            background: var(--surface-color);
            border: 1px solid var(--border-color);
            padding: 1.5rem;
            border-radius: 16px;
            width: 100%;
            text-align: center;
            margin-bottom: -15px; /* Overlap con el cajón */
            z-index: 5;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            position: relative;
        }

        .podium-name {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--text-main);
            margin: 0 0 5px 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .p1-card .podium-name { font-size: 2.2rem; color: #eab308; }

        .podium-time {
            font-family: monospace;
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--warning-color);
        }
        [data-theme="light"] .podium-time { color: #d97706; }

        .podium-phase {
            font-size: 0.9rem;
            color: var(--text-muted);
            margin-top: 5px;
            font-weight: 600;
        }

        .p1-badge {
            position: absolute;
            top: -20px;
            left: 50%;
            transform: translateX(-50%);
            background: #ef4444;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: 800;
            font-size: 0.85rem;
            letter-spacing: 1px;
            box-shadow: 0 0 15px rgba(239, 68, 68, 0.5);
            animation: pulse-badge 2s infinite;
            white-space: nowrap;
        }

        @keyframes pulse-badge {
            0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); }
            100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
        }

        .gap-info {
            font-size: 0.85rem;
            color: var(--danger-color);
            font-weight: 800;
            margin-top: 5px;
        }

        /* LISTA (4-10) */
        .list-container {
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
        }

        .list-item {
            background: var(--surface-color);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1.2rem;
            display: flex;
            align-items: center;
            gap: 1.5rem;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .move-up { animation: flash-green 2s; }
        .move-down { animation: flash-red 2s; }

        @keyframes flash-green { 0% { background-color: rgba(16, 185, 129, 0.2); } 100% { background-color: var(--surface-color); } }
        @keyframes flash-red { 0% { background-color: rgba(239, 68, 68, 0.2); } 100% { background-color: var(--surface-color); } }

        .list-pos {
            font-size: 2rem;
            font-weight: 900;
            color: var(--text-muted);
            width: 40px;
            text-align: center;
            opacity: 0.5;
        }

        .list-info { flex: 1; min-width: 0; }
        .list-info h4 { margin: 0 0 5px 0; font-size: 1.4rem; font-weight: 700; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: var(--text-main); }
        .list-phase { font-size: 0.95rem; color: var(--text-muted); display: flex; align-items: center; gap: 8px; }

        .list-time {
            font-family: monospace;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-main);
            text-align: right;
        }

        /* PROGRESS BAR */
        .progress-wrapper {
            margin-top: 8px;
            height: 6px;
            background: rgba(255,255,255,0.05);
            border-radius: 4px;
            overflow: hidden;
            width: 100%;
        }
        [data-theme="light"] .progress-wrapper { background: rgba(0,0,0,0.05); }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--brand-color), #a855f7);
            border-radius: 4px;
            transition: width 1s ease-in-out;
            position: relative;
        }

        .progress-fill::after {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer { 0% { transform: translateX(-100%); } 100% { transform: translateX(100%); } }

        /* FINISHERS SECTION */
        .finishers-panel {
            background: var(--surface-color);
            border: 1px solid var(--success-color);
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 0 20px rgba(16, 185, 129, 0.1);
        }

        .finisher-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
        }
        .finisher-item:last-child { border-bottom: none; }

        .finisher-pos { color: var(--success-color); font-weight: 900; font-size: 1.2rem; width: 30px; }
        .finisher-name { font-weight: 700; font-size: 1.2rem; flex: 1; }
        .finisher-time { font-family: monospace; font-size: 1.2rem; font-weight: 700; color: var(--success-color); }
        .finisher-hour { font-size: 0.85rem; color: var(--text-muted); text-align: right; margin-top: 4px; }



        /* FULLSCREEN CELEBRATION OVERLAY */
        #celebration-overlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.85);
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
            font-size: 4rem;
            color: var(--success-color);
            font-weight: 900;
            text-transform: uppercase;
            animation: popIn 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-shadow: 0 0 30px rgba(16, 185, 129, 0.5);
            margin-bottom: 0;
        }
        .celeb-team {
            font-size: 6rem;
            color: white;
            font-weight: 900;
            margin: 10px 0;
            animation: popIn 1s cubic-bezier(0.175, 0.885, 0.32, 1.275) 0.2s both;
            text-shadow: 0 0 40px rgba(255,255,255,0.3);
        }
        .celeb-time {
            font-size: 3rem;
            color: #fef08a;
            font-family: monospace;
            font-weight: 700;
            animation: popIn 1s cubic-bezier(0.175, 0.885, 0.32, 1.275) 0.4s both;
        }

        @keyframes popIn {
            0% { transform: scale(0.5); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }

        /* CONFETTI CANVAS */
        #confetti { position: fixed; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: 10000; }

        /* ===== TV / LARGE SCREEN OPTIMIZATION (40-55") ===== */
        @media (min-width: 1400px) {
            .header h1 { font-size: 3.5rem; letter-spacing: 4px; }
            .main-container { max-width: 1600px; gap: 3.5rem; padding: 2.5rem 2rem 7rem 2rem; }
            .section-title { font-size: 2.8rem; margin-bottom: 1.5rem; }

            /* Podio escalado */
            .podium-container { height: 550px; gap: 2.5rem; }
            .p1 { height: 420px; } .p2 { height: 300px; } .p3 { height: 220px; }
            .podium-rank { font-size: 6rem; }
            .podium-name { font-size: 2.6rem; }
            .p1-card .podium-name { font-size: 3rem; }
            .podium-time { font-size: 2.6rem; }
            .podium-phase { font-size: 1.2rem; }
            .p1-badge { font-size: 1.1rem; padding: 8px 22px; }
            .podium-card { padding: 2rem; }

            /* Lista 4-10 */
            .list-item { padding: 1.8rem; border-radius: 16px; gap: 2rem; }
            .list-pos { font-size: 3rem; width: 60px; }
            .list-info h4 { font-size: 2rem; }
            .list-phase { font-size: 1.3rem; }
            .list-time { font-size: 2.2rem; }
            .progress-wrapper { height: 10px; margin-top: 12px; }

            /* Finalizados */
            .finisher-name { font-size: 1.8rem; }
            .finisher-time { font-size: 1.8rem; }
            .finisher-pos { font-size: 1.6rem; }
            .finishers-panel { padding: 2rem; }
            .finisher-item { padding: 1.4rem; }

            /* Celebration overlay */
            .celeb-title { font-size: 6rem; }
            .celeb-team { font-size: 9rem; }
            .celeb-time { font-size: 4.5rem; }
        }

    </style>
</head>
<body>

    <header class="header">
        <h1>DAWSCAPE LIVE RANKING</h1>
    </header>

    <div class="main-container">
        <!-- COLUMNA IZQUIERDA: PODIO Y TICKER -->
        <div class="left-col">
            <h2 class="section-title"><i class="ph-fill ph-trophy"></i> CABEZA DE CARRERA</h2>

            <div class="podium-container" id="podium-container">
                <!-- Se rellena por JS -->
                <div style="text-align:center; width:100%; color:var(--text-muted); font-size:1.5rem; margin-top:50px;">
                    <i class="ph ph-spinner-gap ph-spin" style="font-size:3rem;"></i><br>Cargando datos en vivo...
                </div>
            </div>
        </div>

        <!-- COLUMNA DERECHA: RESTO DE EQUIPOS Y FINALIZADOS -->
        <div class="right-col">
            <h2 class="section-title" id="activos-title"><i class="ph-fill ph-users-three"></i> PERSIGUIENDO (Top 4-10)</h2>
            <div class="list-container" id="list-container">
                <!-- Se rellena por JS -->
            </div>

            <div id="finishers-section" style="display: none; margin-top: 1rem;">
                <h2 class="section-title" style="color:var(--success-color);"><i class="ph-fill ph-flag-checkered"></i> ESCAPARON CON ÉXITO</h2>
                <div class="finishers-panel" id="finishers-container">
                    <!-- Se rellena por JS -->
                </div>
            </div>
        </div>
    </div>



    <!-- OVERLAY CELEBRACIÓN -->
    <div id="celebration-overlay">
        <canvas id="confetti"></canvas>
        <div class="celeb-title">¡UN EQUIPO HA ESCAPADO!</div>
        <div class="celeb-team" id="celeb-team-name">NOMBRE EQUIPO</div>
        <div class="celeb-time" id="celeb-team-time">00m 00s</div>
    </div>

    <!-- SCRIPT DE LÓGICA -->
    <script>
        // Variables de estado
        let previousTeamsState = {}; // Para comparar posiciones y lanzar animaciones
        let knownFinishers = new Set(); // Para saber quién ha terminado ya y no repetir animación

        // THEME TOGGLE (Ctrl + D o Doble Click en Header)
        document.querySelector('.header').addEventListener('dblclick', toggleTheme);
        document.addEventListener('keydown', (e) => {
            if (e.ctrlKey && (e.key === 'd' || e.key === 'D')) {
                e.preventDefault();
                toggleTheme();
            }
        });

        function toggleTheme() {
            const html = document.documentElement;
            const isDark = html.getAttribute('data-theme') === 'dark';
            html.setAttribute('data-theme', isDark ? 'light' : 'dark');
        }

        // CONFETTI LOGIC
        const canvas = document.getElementById('confetti');
        const ctx = canvas.getContext('2d');
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
        let particles = [];
        let isConfettiActive = false;
        const colors = ['#fef08a', '#3b82f6', '#10b981', '#ef4444', '#a855f7'];

        function createConfetti() {
            for(let i=0; i<150; i++) {
                particles.push({
                    x: Math.random() * canvas.width,
                    y: Math.random() * canvas.height - canvas.height,
                    w: Math.random() * 10 + 5,
                    h: Math.random() * 10 + 5,
                    color: colors[Math.floor(Math.random() * colors.length)],
                    vy: Math.random() * 3 + 2,
                    vx: Math.random() * 4 - 2,
                    rot: Math.random() * 360,
                    rotSpeed: Math.random() * 10 - 5
                });
            }
        }

        function drawConfetti() {
            if(!isConfettiActive && particles.length === 0) return;
            ctx.clearRect(0,0,canvas.width,canvas.height);
            for(let i=0; i<particles.length; i++) {
                let p = particles[i];
                ctx.save();
                ctx.translate(p.x + p.w/2, p.y + p.h/2);
                ctx.rotate(p.rot * Math.PI/180);
                ctx.fillStyle = p.color;
                ctx.fillRect(-p.w/2, -p.h/2, p.w, p.h);
                ctx.restore();

                p.y += p.vy;
                p.x += p.vx;
                p.rot += p.rotSpeed;

                if(p.y > canvas.height) {
                    if(isConfettiActive) {
                        p.y = -20;
                        p.x = Math.random() * canvas.width;
                    } else {
                        particles.splice(i, 1);
                        i--;
                    }
                }
            }
            requestAnimationFrame(drawConfetti);
        }

        function triggerCelebration(teamName, timeFormatted) {
            const overlay = document.getElementById('celebration-overlay');
            document.getElementById('celeb-team-name').innerText = teamName;
            document.getElementById('celeb-team-time').innerText = "TIEMPO: " + timeFormatted;

            overlay.classList.add('active');
            isConfettiActive = true;
            particles = [];
            createConfetti();
            drawConfetti();

            // Apagar después de 7 segundos
            setTimeout(() => {
                isConfettiActive = false; // Deja caer los que quedan
                overlay.classList.remove('active');
            }, 7000);
        }

        // FETCH DATA
        async function fetchTop10() {
            try {
                const response = await fetch('/api/top10');
                const data = await response.json();
                renderDashboard(data);
            } catch (err) {
                console.error("Error fetching Top 10 data:", err);
            }
        }

        function renderDashboard(data) {
            // Separar Podio (1-3) y Resto (4-10)
            const top3 = data.top10.slice(0, 3);
            const remaining = data.top10.slice(3, 10);

            // RENDER PODIO
            const podiumContainer = document.getElementById('podium-container');
            if (top3.length === 0) {
                podiumContainer.innerHTML = `<div style="text-align:center; width:100%; color:var(--text-muted); font-size:1.5rem; margin-top:50px;"><i class="ph ph-ghost" style="font-size:4rem; opacity:0.3;"></i><br>Aún no hay equipos activos</div>`;
            } else {
                // Orden visual del podio: 2º, 1º, 3º
                let podiumHTML = '';

                // 2º Puesto
                if(top3[1]) podiumHTML += createPodiumSlot(top3[1], 2, 'p2');
                else podiumHTML += `<div class="podium-slot"></div>`;

                // 1º Puesto
                if(top3[0]) podiumHTML += createPodiumSlot(top3[0], 1, 'p1');
                else podiumHTML += `<div class="podium-slot"></div>`;

                // 3º Puesto
                if(top3[2]) podiumHTML += createPodiumSlot(top3[2], 3, 'p3');
                else podiumHTML += `<div class="podium-slot"></div>`;

                podiumContainer.innerHTML = podiumHTML;
            }

            // RENDER LISTA 4-10
            const listContainer = document.getElementById('list-container');
            document.getElementById('activos-title').innerHTML = `<i class="ph-fill ph-users-three"></i> PERSIGUIENDO (Top 4-10)`;

            // Helper for medals
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
                const rank = index + 4;
                const oldRank = previousTeamsState[team.id] ? previousTeamsState[team.id].rank : rank;
                let animClass = '';
                if(rank < oldRank) animClass = 'move-up';
                else if(rank > oldRank) animClass = 'move-down';

                listHTML += `
                <div class="list-item ${animClass}" ${team.abandonado ? 'style="opacity:0.5; filter:grayscale(1);"' : ''} style="border-left: 4px solid ${team.color_hex};">
                    <div class="list-pos">${rank}</div>
                    <div style="font-size: 2rem; color: ${team.color_hex};"><i class="ph ${team.icono}"></i></div>
                    <div class="list-info">
                        <h4 style="color: ${team.color_hex};">${team.nombre_equipo} ${renderMedals(team.logros)} ${team.abandonado ? '(TIMEOUT)' : ''}</h4>
                        <div class="list-phase">
                            <i class="ph-fill ${team.abandonado ? 'ph-x-circle' : 'ph-spinner-gap ph-spin'}"></i>
                            ${team.nombre_prueba}
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
                    finHTML += `
                    <div class="finisher-item">
                        <div class="finisher-pos">#${index+1}</div>
                        <div class="finisher-name">${team.nombre_equipo}</div>
                        <div style="text-align:right;">
                            <div class="finisher-time">${team.tiempo_formateado}</div>
                            <div class="finisher-hour"><i class="ph ph-clock"></i> ${team.hora_fin}</div>
                        </div>
                    </div>`;

                    // Check for new finisher celebration
                    if(!knownFinishers.has(team.id)) {
                        knownFinishers.add(team.id);
                        // Solo lanzar si no es la carga inicial
                        if(Object.keys(previousTeamsState).length > 0) {
                            triggerCelebration(team.nombre_equipo, team.tiempo_formateado);
                        }
                    }
                });
                finContainer.innerHTML = finHTML;
            } else {
                finSection.style.display = 'none';
            }

            // Actualizar estado previo para comparar en la sig iteración
            let newState = {};
            data.top10.forEach((t, i) => { newState[t.id] = { rank: i+1 }; });
            previousTeamsState = newState;

            // Si hay initial load de finalizados, añadirlos a conocidos
            if(knownFinishers.size === 0) {
                data.finalizados.forEach(t => knownFinishers.add(t.id));
            }
        }

        function createPodiumSlot(team, rank, boxClass) {
            const badge = rank === 1 ? `<div class="p1-badge"><i class="ph-fill ph-crown"></i> LÍDER ACTUAL</div>` : '';
            const gap = rank > 1 && team.gap && !team.abandonado ? `<div class="gap-info">${team.gap}</div>` : '';
            const opacity = team.abandonado ? 'opacity: 0.5; filter: grayscale(1);' : '';
            const titleStatus = team.abandonado ? '(TIMEOUT)' : '';

            return `
            <div class="podium-slot" style="${opacity}">
                <div class="podium-card ${rank===1 ? 'p1-card' : ''}">
                    ${badge}
                    <div style="font-size: 3rem; margin-bottom: 5px; color: ${team.color_hex}; filter: drop-shadow(0 0 10px ${team.color_hex}80);"><i class="ph ${team.icono}"></i></div>
                    <div class="podium-name" style="color: ${rank===1 ? '#eab308' : team.color_hex};">${team.nombre_equipo}</div>
                    <div style="margin-bottom: 10px;">${renderMedals(team.logros)}</div>
                    <div class="podium-time">${team.tiempo_formateado}</div>
                    ${gap}
                    <div class="podium-phase">${team.nombre_prueba} ${titleStatus}</div>
                    <div class="progress-wrapper" style="margin-top:10px;">
                        <div class="progress-fill" style="width: ${team.progreso_porcentaje}%;"></div>
                    </div>
                </div>
                <div class="podium-box ${boxClass}">
                    <div class="podium-rank">${rank}</div>
                </div>
            </div>`;
        }

        // Init Polling
        fetchTop10();
        setInterval(fetchTop10, 5000); // Polling permanente como Fallback robusto

        // Escuchar eventos en vivo desde Reverb
        document.addEventListener('DOMContentLoaded', () => {
            if (window.Echo) {
                window.Echo.channel('top10')
                    .listen('Top10Updated', (e) => {
                        fetchTop10();
                    });
            } else {
                console.error("Laravel Echo is not loaded. Fallback to polling.");
                setInterval(fetchTop10, 5000);
            }
        });

    </script>
    <!-- Firma discreta -->
    <div style="position: fixed; bottom: 5px; right: 10px; font-family: monospace; font-size: 0.65rem; color: rgba(255, 255, 255, 0.3); z-index: 9999; text-align: right;">
        DAWSCAPE SYSTEM // DAW <br>
        <a href="{{ route('legal') }}" style="color: rgba(255, 255, 255, 0.3); text-decoration: none; transition: color 0.3s;" onmouseover="this.style.color='#00ff88'" onmouseout="this.style.color='rgba(255, 255, 255, 0.3)'">AVISO LEGAL Y RGPD</a>
    </div>
</body>
</html>
