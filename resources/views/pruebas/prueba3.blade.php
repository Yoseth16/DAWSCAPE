<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>OPERACIÓN CAFEÍNA | DAWSCAPE</title>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Share+Tech+Mono&family=Orbitron:wght@400;700;900&display=swap');

  :root {
    --green: #00ff88;
    --red: #ff2244;
    --bg: #020c06;
    --panel: #041a0e;
    --border: #00ff8840;
    --text: #c8ffdf;
  }

  * { margin: 0; padding: 0; box-sizing: border-box; }

  body {
    background: var(--bg);
    color: var(--text);
    font-family: 'Share Tech Mono', monospace;
    min-height: 100vh;
    overflow-x: hidden;
  }

  body::before {
    content: '';
    position: fixed;
    inset: 0;
    background: repeating-linear-gradient(0deg, transparent, transparent 2px, rgba(0,255,136,0.013) 2px, rgba(0,255,136,0.013) 4px);
    pointer-events: none;
    z-index: 1000;
  }

  /* HEADER */
  .game-header {
    position: fixed;
    top: 0; left: 0; right: 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.8rem 1.5rem;
    background: rgba(2, 12, 6, 0.95);
    border-bottom: 1px solid var(--border);
    z-index: 100;
    backdrop-filter: blur(8px);
  }

  .team-info {
    font-size: 0.75rem;
    color: #88ccaa;
    line-height: 1.6;
  }

  .team-info strong { color: var(--green); }

  .header-timer {
    font-family: 'Orbitron', sans-serif;
    font-size: 1.3rem;
    color: var(--green);
    text-shadow: 0 0 10px var(--green);
    letter-spacing: 0.1em;
  }

  .screen { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 1.5rem; padding-top: 5rem; padding-bottom: 2rem; min-height: 100vh; animation: fadeIn 1s ease; }

  .logo {
    font-family: 'Orbitron', sans-serif;
    font-size: clamp(2rem, 5vw, 4rem);
    font-weight: 900;
    color: var(--green);
    text-shadow: 0 0 20px var(--green), 0 0 60px #00ff8840;
    letter-spacing: 0.1em;
    margin-bottom: 2rem;
    text-align: center;
    animation: pulse-glow 2.5s ease-in-out infinite;
  }

  .intro-text {
    max-width: 600px;
    line-height: 1.9;
    color: #88ccaa;
    border: 1px solid var(--border);
    padding: clamp(1.5rem, 5vw, 2.5rem) clamp(1.2rem, 4vw, 2rem);
    background: var(--panel);
    position: relative;
    text-align: center;
    font-size: clamp(1rem, 4vw, 1.1rem);
  }

  .intro-text::before {
    content: '// NUEVA MISIÓN';
    position: absolute;
    top: -0.7rem; left: 1rem;
    background: var(--bg);
    padding: 0 0.5rem;
    color: var(--green);
    font-size: 0.7rem;
    letter-spacing: 0.2em;
  }
  
  .intro-text strong {
      color: var(--green);
      font-weight: normal;
  }

  /* HTML5 Scanner styles removed, kept clean as requested */
  
  /* ANIMATIONS */
  @keyframes fadeIn { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
  @keyframes pulse-glow {
    0%, 100% { text-shadow: 0 0 20px var(--green), 0 0 50px #00ff8860; }
    50% { text-shadow: 0 0 40px var(--green), 0 0 90px #00ff8840; }
  }

  /* MOBILE */
  @media (max-width: 600px) {
    .screen { padding-left: 1rem; padding-right: 1rem; padding-top: 5.5rem; }
    .game-header { padding: 0.6rem 1rem; }
    .team-info { font-size: 0.7rem; }
    .header-timer { font-size: 1.1rem; }
    .intro-text { font-size: 0.95rem !important; padding: 1.2rem 1rem; }
    .logo { font-size: clamp(1.5rem, 7vw, 3rem) !important; letter-spacing: 0.05em; }
  }
</style>
</head>
<body>

<div class="game-header">
  <div class="team-info">
    <strong>Equipo:</strong> {{ session('nombre_equipo', 'Sin equipo') }}<br>
    <strong>Clase:</strong> {{ session('clase', 'N/A') }}
  </div>
  <div class="header-timer" id="cronometro">--:--</div>
</div>

<div class="screen">
  <div class="logo">OPERACIÓN CAFEÍNA</div>
  <div class="intro-text">
    <p>El servidor se está sobrecalentando y el administrador necesita combustible negro.</p>
    <br>
    <p><strong>Busca la fuente de energía líquida donde los profesores se resetean cada mañana.</strong></p>
    <br>
    <p style="font-size: 0.85rem; color:#445544; margin-top: 1rem;">// Escanea el código en la localización física para continuar.</p>
  </div>
</div>

<script>
let tiempoInicial = parseInt("{{ $tiempo_transcurrido ?? 0 }}");
const loadTimeMs = Date.now();
const timerDisplay = document.getElementById('cronometro');
const MAX_MINUTOS = 90;

function actualizarTimer() {
  let tiempo = tiempoInicial + Math.floor((Date.now() - loadTimeMs) / 1000);
  let minutos = parseInt(tiempo / 60, 10);
  let segundos = parseInt(tiempo % 60, 10);

  let m = minutos  < 10 ? "0" + minutos  : minutos;
  let s = segundos < 10 ? "0" + segundos : segundos;

  if(timerDisplay) timerDisplay.textContent = m + ":" + s;

  if (minutos >= MAX_MINUTOS) {
      window.location.href = '/final';
      return;
  }
}

actualizarTimer();
setInterval(actualizarTimer, 1000);
</script>
</body>
</html>

@include('components.pista-ayuda', ['pruebaNumero' => 3, 'pistaConfig' => $pistaConfig, 'yaUsada' => $yaUsada])
@include('components.live-listener')
