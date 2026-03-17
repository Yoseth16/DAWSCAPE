<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>VICTORIA | DAWSCAPE</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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

      .screen {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        padding: 1.5rem;
        position: relative;
        z-index: 10;
        animation: fadeIn 1s ease;
      }

      .victory-box {
        width: 100%;
        max-width: 680px;
        border: 1px solid var(--border);
        background: var(--panel);
        padding: clamp(1.5rem, 5vw, 3rem) clamp(1rem, 4vw, 3rem);
        position: relative;
        text-align: center;
        box-shadow: 0 0 40px rgba(0, 255, 136, 0.1);
      }

      .victory-box::before {
        content: '// REPORTE DEL SISTEMA';
        position: absolute;
        top: -0.7rem; left: 1rem;
        background: var(--bg);
        padding: 0 0.5rem;
        color: var(--green);
        font-size: 0.7rem;
        letter-spacing: 0.2em;
      }

      .victory-box.timeout {
        border-color: var(--red);
        box-shadow: 0 0 40px rgba(255, 34, 68, 0.15);
      }
      .victory-box.timeout::before {
        color: var(--red);
      }

      .title {
        font-family: 'Orbitron', sans-serif;
        font-size: clamp(2rem, 5vw, 3.5rem);
        font-weight: 900;
        letter-spacing: 0.1em;
        margin-bottom: 1.5rem;
      }

      .title.success {
        color: var(--green);
        text-shadow: 0 0 20px var(--green);
        animation: pulse-glow 2s infinite;
      }

      .title.error {
        color: var(--red);
        text-shadow: 0 0 20px var(--red);
        animation: glitch-blink 2s infinite;
      }

      /* MEJORADO: Subtitle text con mejor contraste */
      .subtitle-txt {
        font-size: 1.1rem;
        line-height: 1.8;
        color: #b0e0bf;
        margin-bottom: 2rem;
        text-shadow: 0 0 6px rgba(200, 255, 223, 0.15);
      }
      .victory-box.timeout .subtitle-txt {
        color: #ff9999;
        text-shadow: 0 0 6px rgba(255, 153, 153, 0.2);
      }

      .highlight { color: var(--green); font-weight: bold; }
      .victory-box.timeout .highlight { color: var(--red); }

      .time-display {
        font-family: 'Orbitron', sans-serif;
        font-size: clamp(2rem, 10vw, 3.5rem);
        color: var(--green);
        text-shadow: 0 0 20px var(--green);
        margin: 1.5rem 0;
        letter-spacing: 0.1em;
      }

      .ranking-panel {
        text-align: left;
        background: rgba(0, 0, 0, 0.4);
        border: 1px dotted var(--border);
        padding: clamp(1rem, 4vw, 1.5rem);
        margin-top: 1.5rem;
      }

      /* MEJORADO: Ranking panel header con mejor contraste */
      .ranking-panel h4 {
        color: #00ff88;
        font-size: 0.85rem;
        letter-spacing: 0.2em;
        margin-bottom: 1rem;
        text-transform: uppercase;
        text-shadow: 0 0 8px rgba(0, 255, 136, 0.2);
      }

      .breakdown-panel {
          margin-top: 1rem;
          margin-bottom: 2rem;
          background: rgba(0, 255, 136, 0.05);
          border-left: 3px solid var(--green);
          padding: 1.5rem;
          text-align: left;
      }

      .breakdown-panel h4 {
        color: var(--green);
        font-size: 0.85rem;
        letter-spacing: 0.2em;
        margin-bottom: 1rem;
        text-transform: uppercase;
      }

      /* MEJORADO: Breakdown list con mejor contraste */
      .breakdown-list {
          list-style: none;
          padding: 0;
          font-size: 0.95rem;
          line-height: 2;
          color: #b0e0bf;
          text-shadow: 0 0 5px rgba(200, 255, 223, 0.1);
      }

      .breakdown-list li {
          display: flex;
          justify-content: space-between;
          border-bottom: 1px solid rgba(0, 255, 136, 0.1);
          padding-bottom: 0.3rem;
          margin-bottom: 0.3rem;
      }

      /* MEJORADO: Ranking list con mejor contraste */
      .ranking-list {
        list-style-type: decimal;
        padding-left: 1.5rem;
        color: #88ccaa;
        line-height: 1.8;
        text-shadow: 0 0 4px rgba(136, 204, 170, 0.15);
      }

      .ranking-list li { margin-bottom: 0.5rem; }
      .ranking-list li.current-team {
        color: var(--green);
        font-weight: bold;
        text-shadow: 0 0 5px var(--green);
      }

      /* DIPLOMA FINAL */
      .diploma-section {
        margin-top: 1.5rem;
        border: 1px solid rgba(0, 255, 136, 0.35);
        background: radial-gradient(circle at top, rgba(0,255,136,0.08), transparent 55%);
        padding: clamp(0.8rem, 3vw, 1.5rem);
        width: 100%;
        max-width: 100%;
        overflow: hidden;
      }
      .diploma-header {
        font-size: clamp(0.65rem, 2vw, 0.8rem);
        letter-spacing: 0.18em;
        text-transform: uppercase;
        color: var(--green);
        margin-bottom: 0.75rem;
        text-align: center;
      }
      .diploma-layout {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        align-items: center;
        width: 100%;
      }
      .diploma-award {
        text-align: center;
      }
      .diploma-award-title {
        font-family: 'Orbitron', sans-serif;
        font-size: clamp(0.85rem, 2.5vw, 1.1rem);
        letter-spacing: 0.12em;
        text-transform: uppercase;
        word-break: break-word;
      }
      .diploma-award-emoji {
        font-size: 2.2rem;
        margin-bottom: 0.25rem;
      }
      .diploma-preview-frame {
        width: 100%;
        max-width: 100%;
        border: 1px solid rgba(0, 255, 136, 0.2);
        background: #000000;
        padding: clamp(0.25rem, 1vw, 0.5rem);
        overflow: hidden;
      }
      .diploma-preview-frame img {
        width: 100%;
        height: auto;
        display: block;
        object-fit: contain;
      }
      .diploma-actions {
        margin-top: 0.75rem;
        display: flex;
        justify-content: center;
        width: 100%;
      }
      .diploma-download-btn {
        display: inline-block;
        font-family: 'Orbitron', sans-serif;
        font-size: clamp(0.6rem, 2vw, 0.75rem);
        letter-spacing: 0.18em;
        padding: 0.9rem 1.6rem;
        border: 1px solid var(--green);
        color: var(--green);
        background: transparent;
        text-transform: uppercase;
        cursor: pointer;
        transition: all 0.2s;
        max-width: 100%;
        text-align: center;
        -webkit-tap-highlight-color: transparent;
        min-height: 48px;
      }
      .diploma-download-btn:hover {
        background: var(--green);
        color: #00110a;
        box-shadow: 0 0 14px rgba(0,255,136,0.4);
      }

      .btn {
        display: inline-block;
        font-family: 'Orbitron', sans-serif;
        font-size: 0.9rem;
        letter-spacing: 0.2em;
        padding: 1.2rem 2.5rem;
        border: 2px solid var(--green);
        background: transparent;
        color: var(--green);
        text-decoration: none;
        cursor: pointer;
        text-transform: uppercase;
        transition: all 0.25s;
        position: relative;
        overflow: hidden;
        margin-top: 2rem;
        font-weight: 700;
        width: 100%;
      }

      .btn::before {
        content: '';
        position: absolute;
        top: 0; left: -100%;
        width: 100%; height: 100%;
        background: var(--green);
        transition: left 0.3s;
        z-index: -1;
      }

      .btn:hover::before { left: 0; }
      .btn:hover { color: var(--bg); box-shadow: 0 0 20px var(--green); }

      @keyframes pulse-glow {
        0%, 100% { text-shadow: 0 0 20px var(--green); }
        50% { text-shadow: 0 0 40px var(--green), 0 0 60px #00ff8840; }
      }

      @keyframes glitch-blink {
        0%, 100% { opacity: 1; text-shadow: 0 0 20px var(--red); }
        50% { opacity: 0.8; text-shadow: 0 0 40px var(--red); }
      }

      @keyframes fadeIn { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }

      /* Particles */
      .particles { position: fixed; inset: 0; pointer-events: none; z-index: 1; }
      .particle {
        position: absolute;
        width: 2px; height: 2px;
        background: var(--green);
        border-radius: 50%;
        opacity: 0;
        animation: float linear infinite;
      }
      @keyframes float {
        0% { opacity: 0; transform: translateY(100vh); }
        10% { opacity: 0.5; }
        90% { opacity: 0.3; }
        100% { opacity: 0; transform: translateY(-10vh); }
      }

      /* MOBILE */
      .btn { min-height: 52px; -webkit-tap-highlight-color: transparent; }
      @media (max-width: 600px) {
        .screen { padding: 0.75rem; }
        .victory-box { padding: 1rem 0.75rem; }
        .victory-box::before { font-size: 0.6rem; left: 0.5rem; }
        .title { font-size: clamp(1.5rem, 7vw, 2.5rem); margin-bottom: 1rem; }
        .breakdown-list { font-size: 0.85rem; }
        .breakdown-panel { padding: 1rem; }
        .subtitle-txt { font-size: 0.95rem; line-height: 1.6; }
        .ranking-list { font-size: 0.85rem; padding-left: 1.2rem; }
        .ranking-list li { flex-wrap: wrap; gap: 0.2rem; }
        .time-display { font-size: clamp(1.5rem, 8vw, 2.5rem); }
        .diploma-section { padding: 0.6rem; }
        .diploma-layout { gap: 0.4rem; }
        .diploma-award-title { font-size: 0.8rem; }
        .diploma-award-emoji { font-size: 1.8rem; }
        .diploma-preview-frame { padding: 0.2rem; }
        .diploma-download-btn { padding: 0.75rem 1rem; width: 100%; }
        .btn { padding: 1rem 1.5rem; font-size: 0.8rem; }
      }

      @media (max-width: 380px) {
        .screen { padding: 0.5rem; }
        .victory-box { padding: 0.75rem 0.5rem; }
        .title { font-size: 1.4rem; }
        .subtitle-txt { font-size: 0.85rem; }
        .ranking-list { font-size: 0.8rem; }
      }
    </style>
</head>
<body>

@include('components.game-audio-setup', ['trackKey' => 'final'])

<div class="particles" id="particles"></div>

<div class="screen">
  @if($partida->fase_actual === 'FINALIZADO_TIMEOUT' || session('timeout'))
    <div class="victory-box timeout">
      <div style="font-size:3rem; margin-bottom:1rem">🚫</div>
      <h1 class="title error">TIEMPO AGOTADO</h1>

      <div class="subtitle-txt">
        Misión fallida, equipo <span class="highlight">{{ $partida->nombre_equipo }}</span> ({{ $partida->clase }}).<br><br>
        El sistema de seguridad ha bloqueado la salida y se acabó vuestro tiempo.
      </div>

      <div class="ranking-panel" style="border-color: #ffaa0040;">
        <h4 style="color:#ffaa00">>> Progreso antes del cierre_</h4>
        <div style="color:#ffddaa; font-size:1.1rem; margin-top:0.5rem">
          @if(session()->has('prueba1mario_superada'))
            Prueba 1 Mario completada (fallo en validación final).
          @else
            No habéis conseguido escapar a tiempo.
          @endif
        </div>
      </div>
  @else
    <div class="victory-box">
      <div style="font-size:3.5rem; margin-bottom:1rem">🔓</div>
      <h1 class="title success">SISTEMA HACKEADO</h1>

      <div class="subtitle-txt">
        Enhorabuena, equipo <span class="highlight">{{ $partida->nombre_equipo }}</span> ({{ $partida->clase }}).<br><br>
        Habéis conseguido escapar del edificio a tiempo cruzando el Firewall.
      </div>

      <div style="color:#007744; font-size:0.8rem; letter-spacing:0.2em; text-transform:uppercase">// TIEMPO TOTAL DE OPERACIÓN</div>
      <div class="time-display">
        {{ floor($partida->tiempo_segundos / 60) }}m {{ $partida->tiempo_segundos % 60 }}s
      </div>
      @if($partida->penalizacion_segundos > 0)
        <div style="color: #f59e0b; font-size: 0.9rem; margin-top: -10px; margin-bottom: 20px;">
          (Incluye {{ floor($partida->penalizacion_segundos / 60) }}m de penalización por pistas)
        </div>
      @endif

      @if(isset($hitos) && count($hitos) > 0)
          <div class="breakdown-panel">
            <h4>>> INFORME DE RENDIMIENTO (POR FASE)_</h4>
            <ul class="breakdown-list">
              @foreach($hitos as $hito)
                  <li>
                      <span>{{ $hito['fase'] }}</span>
                      <span style="color:var(--green); font-family: 'Orbitron', sans-serif;">
                          {{ floor($hito['segundos_invertidos'] / 60) }}m {{ $hito['segundos_invertidos'] % 60 }}s
                      </span>
                  </li>
              @endforeach
            </ul>
          </div>
      @endif
  @endif

      <div class="ranking-panel">
        <h4>>> TOP 3 EQUIPOS_</h4>
        @if($top3->count() > 0)
          <ol class="ranking-list">
              @foreach($top3 as $equipo)
                  <li class="{{ $equipo->email === $partida->email ? 'current-team' : '' }}" style="display:flex; justify-content:space-between; align-items:baseline; gap:0.5rem">
                      <span>{{ $equipo->nombre_equipo }} ({{ $equipo->clase }})</span> <span style="font-family:'Orbitron', sans-serif; white-space:nowrap">[{{ floor($equipo->tiempo_segundos / 60) }}m {{ $equipo->tiempo_segundos % 60 }}s]</span>
                  </li>
              @endforeach
          </ol>
        @else
            <p style="color:#445544; font-size:0.9rem">Aún no hay datos de ranking.</p>
        @endif
      </div>

      @if($partida->fase_actual === 'FINALIZADO')
      <div class="diploma-section" id="diploma-section">
        <div class="diploma-header">// DIPLOMA DIGITAL PERSONALIZADO</div>
        <div class="diploma-layout">
          <div class="diploma-award">
            <div class="diploma-award-emoji" id="diploma-award-emoji">🏆</div>
            <div class="diploma-award-title" id="diploma-award-title">Cargando premio...</div>
          </div>
          <div class="diploma-preview-frame">
            <img id="diploma-image" alt="Diploma del equipo {{ $partida->nombre_equipo }}">
          </div>
          <div class="diploma-actions">
            <button type="button" class="diploma-download-btn" id="download-diploma">
              DESCARGAR DIPLOMA PNG
            </button>
          </div>
        </div>
      </div>
      @endif

      <a href="/" class="btn">FINALIZAR CONEXIÓN →</a>
    </div>
</div>

<script>
// Partículas
(function() {
  const c = document.getElementById('particles');
  for (let i = 0; i < 25; i++) {
    const p = document.createElement('div');
    p.className = 'particle';
    p.style.left = Math.random() * 100 + '%';
    p.style.animationDuration = (10 + Math.random() * 14) + 's';
    p.style.animationDelay = (Math.random() * 12) + 's';
    c.appendChild(p);
  }
})();

// =========================
// SISTEMA DE DIPLOMA FINAL
// =========================
(function() {
  const section = document.getElementById('diploma-section');
  if (!section) return; // solo cuando el equipo ha finalizado

  const stats = {
    teamName: @json($partida->nombre_equipo),
    totalTime: {{ (int)($partida->tiempo_segundos ?? 0) }},
    timePerChallenge: @json(isset($hitos) ? collect($hitos)->pluck('segundos_invertidos')->values() : []),
    hintsUsed: 0,
    maxHints: 10,
    firstTrySuccessCount: 0,
    totalAttempts: 0,
    position: 0,
    totalChallenges: {{ isset($hitos) ? count($hitos) : 0 }},
  };

  // Helpers
  const minutes = (s) => s / 60;
  const ratio = (n, d) => (d === 0 ? 0 : n / d);
  const fastestChallengeTime = (st) =>
    st.timePerChallenge.length ? Math.min.apply(null, st.timePerChallenge) : Infinity;

  // Definición de premios (subset principal)
  const AWARDS = [
    {
      id: 'first_finish',
      label: 'Primer equipo en terminar',
      emoji: '🏆',
      weight: 1000,
      score: (s) => (s.position === 1 ? 100 : 0),
    },
    {
      id: 'velocista_digital',
      label: 'Velocista Digital',
      emoji: '⚡',
      weight: 850,
      score: (s) => {
        const m = minutes(s.totalTime);
        if (m > 60) return 0;
        if (m <= 30) return 90 + (30 - m);
        return 70 + (60 - m) * 0.5;
      },
    },
    {
      id: 'sin_pistas',
      label: 'Sin Pistas',
      emoji: '🧠',
      weight: 900,
      score: (s) => (s.hintsUsed === 0 ? 95 : 0),
    },
    {
      id: 'tortuga_ganadora',
      label: 'La Tortuga Ganadora',
      emoji: '🐢',
      weight: 740,
      score: (s) => {
        const m = minutes(s.totalTime);
        if (m < 60) return 0;
        return 60 + (m - 60) * 0.3;
      },
    },
    {
      id: 'eureka',
      label: 'Eureka',
      emoji: '💡',
      weight: 730,
      score: (s) => {
        const f = minutes(fastestChallengeTime(s));
        if (f === Infinity || f > 3) return 0;
        return 88 + (3 - f) * 4;
      },
    },
    {
      id: 'sobrevivientes_desafio',
      label: 'Sobrevivientes del Desafío',
      emoji: '🏅',
      weight: 650,
      score: (s) => 60 + minutes(s.totalTime) * 0.3,
    },
  ];

  function pickAward(st) {
    const candidates = AWARDS
      .map((cfg) => ({ cfg, value: cfg.score(st) }))
      .filter((c) => c.value > 0);
    if (!candidates.length) {
      const fallback = AWARDS.find((a) => a.id === 'sobrevivientes_desafio');
      return fallback;
    }
    candidates.sort((a, b) =>
      b.value !== a.value ? b.value - a.value : b.cfg.weight - a.cfg.weight
    );
    return candidates[0].cfg;
  }

  function formatDuration(seconds) {
    const m = Math.floor(seconds / 60);
    const s = seconds % 60;
    return `${m}m ${String(s).padStart(2, '0')}s`;
  }

  function generateDiplomaPNG(st, award) {
    const width = 2000;
    const height = 1400;
    const canvas = document.createElement('canvas');
    canvas.width = width;
    canvas.height = height;
    const ctx = canvas.getContext('2d');

    // Fondo básico temático según premio
    ctx.fillStyle = '#020c06';
    ctx.fillRect(0, 0, width, height);
    if (award.id === 'velocista_digital') {
      const grd = ctx.createLinearGradient(0, 0, width, height);
      grd.addColorStop(0, '#020617');
      grd.addColorStop(1, '#0f172a');
      ctx.fillStyle = grd;
      ctx.fillRect(0, 0, width, height);
    } else if (award.id === 'sin_pistas') {
      ctx.fillStyle = '#0b1020';
      ctx.fillRect(0, 0, width, height);
    } else if (award.id === 'tortuga_ganadora') {
      ctx.fillStyle = '#d1fae5';
      ctx.fillRect(0, 0, width, height);
    } else if (award.id === 'eureka') {
      ctx.fillStyle = '#1e1b4b';
      ctx.fillRect(0, 0, width, height);
    }

    // Marco
    ctx.strokeStyle = '#00ff8840';
    ctx.lineWidth = 18;
    ctx.strokeRect(80, 80, width - 160, height - 160);

    const centerX = width / 2;
    ctx.textAlign = 'center';

    ctx.fillStyle = '#c8ffdf';
    ctx.font = 'bold 80px "Share Tech Mono", monospace';
    ctx.fillText('CERTIFICADO DE AVENTURA', centerX, 220);

    ctx.font = '36px "Share Tech Mono", monospace';
    ctx.fillText('Este diploma certifica que el equipo', centerX, 320);

    ctx.font = 'bold 72px "Share Tech Mono", monospace';
    ctx.fillText(st.teamName, centerX, 400);

    ctx.font = '36px "Share Tech Mono", monospace';
    ctx.fillText('ha completado con éxito el desafío.', centerX, 470);

    ctx.font = '40px "Share Tech Mono", monospace';
    ctx.fillText('Premio obtenido:', centerX, 560);

    ctx.fillStyle = '#00ff88';
    ctx.font = 'bold 64px "Share Tech Mono", monospace';
    ctx.fillText(`${award.emoji} ${award.label}`, centerX, 630);

    const marginX = 200;
    let y = 760;
    ctx.textAlign = 'left';
    ctx.fillStyle = '#c8ffdf';
    ctx.font = '34px "Share Tech Mono", monospace';
    ctx.fillText(`Tiempo total: ${formatDuration(st.totalTime)}`, marginX, y);
    y += 60;
    ctx.fillText(`Retos resueltos: ${st.totalChallenges}`, marginX, y);
    y += 60;
    ctx.fillText(`Posición en el ranking: #${st.position || '-'}`, marginX, y);

    const dateStr = new Date().toLocaleDateString('es-ES');
    ctx.textAlign = 'right';
    ctx.fillText(`Fecha del reto: ${dateStr}`, width - marginX, y);

    // Sello simple
    const sealX = width - marginX - 150;
    const sealY = 860;
    ctx.beginPath();
    ctx.arc(sealX, sealY, 80, 0, Math.PI * 2);
    ctx.fillStyle = '#00ff8840';
    ctx.fill();
    ctx.fillStyle = '#00ff88';
    ctx.textAlign = 'center';
    ctx.font = '28px "Share Tech Mono", monospace';
    ctx.fillText('DAW', sealX, sealY - 5);
    ctx.fillText('SCAPE', sealX, sealY + 30);

    return canvas.toDataURL('image/png');
  }

  const award = pickAward(stats);
  const img = document.getElementById('diploma-image');
  const emojiEl = document.getElementById('diploma-award-emoji');
  const titleEl = document.getElementById('diploma-award-title');
  const btn = document.getElementById('download-diploma');

  emojiEl.textContent = award.emoji;
  titleEl.textContent = award.label;

  const dataUrl = generateDiplomaPNG(stats, award);
  img.src = dataUrl;

  btn.addEventListener('click', function() {
    const a = document.createElement('a');
    a.href = dataUrl;
    a.download = `diploma-{{ \Illuminate\Support\Str::slug($partida->nombre_equipo, '_') }}.png`;
    a.click();
  });
})();
</script>

<!-- Firma discreta -->
<div style="position: fixed; bottom: 5px; right: 10px; font-family: monospace; font-size: 0.65rem; color: rgba(255, 255, 255, 0.3); z-index: 9999; text-align: right;">
    DAWSCAPE SYSTEM // DAW <br>
    <a href="{{ route('legal') }}" style="color: rgba(255, 255, 255, 0.3); text-decoration: none; transition: color 0.3s;" onmouseover="this.style.color='#00ff88'" onmouseout="this.style.color='rgba(255, 255, 255, 0.3)'">AVISO LEGAL Y RGPD</a>
</div>

<!-- MODAL VALORACION -->
<style>
.val-modal-overlay {
    position: fixed; top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(0,0,0,0.8); backdrop-filter: blur(5px);
    z-index: 10000; display: flex; justify-content: center; align-items: center;
    opacity: 0; pointer-events: none; transition: opacity 0.5s ease;
}
.val-modal-overlay.active { opacity: 1; pointer-events: auto; }
.val-modal {
    background: #111; border: 2px solid #facc15; border-radius: 12px;
    padding: 2rem; width: 90%; max-width: 400px;
    box-shadow: 0 0 20px rgba(250, 204, 21, 0.2);
    text-align: center; color: white; font-family: 'Share Tech Mono', monospace;
    transform: translateY(20px); transition: transform 0.5s ease;
}
.val-modal-overlay.active .val-modal { transform: translateY(0); }
.val-title { color: #facc15; font-size: 1.5rem; margin-bottom: 1rem; text-shadow: 0 0 10px rgba(250, 204, 21, 0.5); }
.val-stars { display: flex; justify-content: center; gap: 10px; margin-bottom: 1.5rem; flex-direction: row-reverse; }
.val-stars input { display: none; }
.val-stars label { font-size: 2.5rem; color: #444; cursor: pointer; transition: color 0.2s, text-shadow 0.2s; }
.val-stars label:hover, .val-stars label:hover ~ label, .val-stars input:checked ~ label { color: #facc15; text-shadow: 0 0 15px rgba(250, 204, 21, 0.8); }
.val-textarea { width: 100%; background: #222; border: 1px solid #444; border-radius: 8px; color: white; padding: 0.75rem; font-family: inherit; resize: none; margin-bottom: 1.5rem; margin-top: 1rem; }
.val-textarea:focus { outline: none; border-color: #facc15; }
.val-buttons { display: flex; gap: 1rem; }
.val-btn { flex: 1; padding: 0.75rem; border-radius: 8px; font-weight: bold; font-family: inherit; cursor: pointer; text-transform: uppercase; border: none; transition: all 0.2s; }
.val-btn-submit { background: #facc15; color: #000; }
.val-btn-submit.disabled { opacity: 0.5; cursor: not-allowed; }
.val-btn-submit:not(.disabled):hover { box-shadow: 0 0 15px rgba(250, 204, 21, 0.6); }
.val-btn-skip { background: transparent; border: 1px solid #666; color: #aaa; }
.val-btn-skip:hover { background: #333; color: white; }
.val-msg { display: none; margin-top: 1rem; color: #00ff88; font-weight: bold; }
</style>

<div class="val-modal-overlay" id="valModalOverlay">
    <div class="val-modal">
        <div class="val-title">¿Qué te ha parecido la actividad?</div>
        
        <div class="val-stars">
            <input type="radio" name="estrellas" id="star5" value="5"><label for="star5">★</label>
            <input type="radio" name="estrellas" id="star4" value="4"><label for="star4">★</label>
            <input type="radio" name="estrellas" id="star3" value="3"><label for="star3">★</label>
            <input type="radio" name="estrellas" id="star2" value="2"><label for="star2">★</label>
            <input type="radio" name="estrellas" id="star1" value="1"><label for="star1">★</label>
        </div>
        
        <textarea id="valComentario" class="val-textarea" rows="3" placeholder="Déjanos un comentario (opcional)..."></textarea>
        
        <div class="val-buttons" id="valButtons">
            <button class="val-btn val-btn-skip" id="btnSkipVal">Omitir</button>
            <button class="val-btn val-btn-submit disabled" id="btnSubmitVal" disabled>Enviar valoración</button>
        </div>
        
        <div class="val-msg" id="valMsg">[OK] Valoración registrada.<br>¡Gracias por tu feedback!</div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Verificar si ya se mostro para esta partida
    if (localStorage.getItem('dawscape_valorado_{{ $partida->id ?? '' }}')) return;

    const overlay = document.getElementById('valModalOverlay');
    const btnSubmit = document.getElementById('btnSubmitVal');
    const btnSkip = document.getElementById('btnSkipVal');
    const msg = document.getElementById('valMsg');
    const buttonsContainer = document.getElementById('valButtons');
    const stars = document.querySelectorAll('input[name="estrellas"]');
    
    // Habilitar boton cuando se selecciona una estrella
    stars.forEach(star => {
        star.addEventListener('change', () => {
            btnSubmit.disabled = false;
            btnSubmit.classList.remove('disabled');
        });
    });

    // Mostrar modal despues de 5s
    setTimeout(() => {
        overlay.classList.add('active');
    }, 5000);

    // Omitir
    btnSkip.addEventListener('click', () => {
        localStorage.setItem('dawscape_valorado_{{ $partida->id ?? '' }}', 'true');
        overlay.classList.remove('active');
    });

    // Enviar
    btnSubmit.addEventListener('click', () => {
        if(btnSubmit.disabled) return;
        
        const checkedStar = document.querySelector('input[name="estrellas"]:checked');
        if(!checkedStar) return;
        
        const starsVal = checkedStar.value;
        const comment = document.getElementById('valComentario').value;
        const partidaId = '{{ $partida->id ?? '' }}';
        
        let csrfToken = '';
        const metaCsrf = document.querySelector('meta[name="csrf-token"]');
        if(metaCsrf) {
            csrfToken = metaCsrf.getAttribute('content');
        } else {
            // Si la vista no tiene meta csrf, lo buscamos en campos hidden o añadimos un tag global en algun lado. Pero laravel permite headers o inputs.
            csrfToken = '{{ csrf_token() }}';
        }

        // Deshabilitar boton
        btnSubmit.disabled = true;
        btnSubmit.textContent = 'Enviando...';

        fetch('/valoraciones', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                partida_id: partidaId,
                estrellas: starsVal,
                comentario: comment
            })
        }).then(res => res.json())
          .then(data => {
              if(data.success || data.message === 'Ya valorado') {
                  localStorage.setItem('dawscape_valorado_{{ $partida->id ?? '' }}', 'true');
                  buttonsContainer.style.display = 'none';
                  msg.style.display = 'block';
                  setTimeout(() => {
                      overlay.classList.remove('active');
                  }, 2000);
              } else {
                  console.error(data);
                  btnSubmit.disabled = false;
                  btnSubmit.textContent = 'Enviar valoración';
              }
          }).catch(err => {
              console.error('Error', err);
              btnSubmit.disabled = false;
              btnSubmit.textContent = 'Enviar valoración';
          });
    });
});
</script>
</body>
</html>
