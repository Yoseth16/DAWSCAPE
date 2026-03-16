<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>JUEGO DE PISTAS | DAWSCAPE</title>
@vite(['resources/css/app.css', 'resources/js/app.js'])
<style>
  @import url('https://fonts.googleapis.com/css2?family=Share+Tech+Mono&family=Orbitron:wght@400;700;900&display=swap');

  :root {
    --green: #00ff88;
    --red: #ff2244;
    --amber: #ffaa00;
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

  .team-info { font-size: 0.75rem; color: #b0e0bf; line-height: 1.6; text-shadow: 0 0 8px rgba(0, 255, 136, 0.2); }
  .team-info strong { color: var(--green); text-shadow: 0 0 10px var(--green); }

  .header-timer {
    font-family: 'Orbitron', sans-serif;
    font-size: 1.3rem;
    color: var(--green);
    text-shadow: 0 0 10px var(--green);
    letter-spacing: 0.1em;
  }

  .screen { display: none; min-height: 100vh; }
  .screen.active {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 1.5rem;
    padding-top: 5rem;
    padding-bottom: 2rem;
    gap: 1rem;
  }

  .logo {
    font-family: 'Orbitron', sans-serif;
    font-size: clamp(1.8rem, 5vw, 3.5rem);
    font-weight: 900;
    color: var(--green);
    text-shadow: 0 0 20px var(--green), 0 0 60px #00ff8840;
    letter-spacing: 0.15em;
  }

  .subtitle {
    font-size: 0.8rem;
    color: #00cc66;
    letter-spacing: 0.28em;
    text-transform: uppercase;
    text-shadow: 0 0 6px rgba(0, 204, 102, 0.2);
    text-align: center;
  }

  .panel {
    width: 100%;
    max-width: 720px;
    border: 1px solid var(--border);
    background: var(--panel);
    padding: 1.4rem 1.6rem;
    position: relative;
    box-shadow: 0 0 30px rgba(0, 255, 136, 0.08);
  }

  .panel::before {
    content: attr(data-label);
    position: absolute;
    top: -0.7rem; left: 1rem;
    background: var(--bg);
    padding: 0 0.5rem;
    color: var(--green);
    font-size: 0.65rem;
    letter-spacing: 0.2em;
    text-transform: uppercase;
  }

  .question {
    font-size: 1.05rem;
    line-height: 1.8;
    color: #aaffcc;
    white-space: pre-line;
  }

  .letters-row {
    display: flex;
    gap: 8px;
    justify-content: center;
    flex-wrap: wrap;
    margin-top: 0.8rem;
  }

  .letter-chip {
    border: 1px solid #00ff8840;
    background: rgba(0, 0, 0, 0.35);
    padding: 6px 10px;
    min-width: 34px;
    text-align: center;
    font-family: 'Orbitron', sans-serif;
    font-weight: 700;
    color: var(--green);
    box-shadow: 0 0 12px rgba(0,255,136,0.08);
  }

  .answer-input {
    width: 100%;
    background: transparent;
    border: none;
    border-bottom: 2px solid var(--green);
    color: var(--green);
    font-family: 'Share Tech Mono', monospace;
    font-size: max(16px, 1.25rem);
    padding: 0.85rem 0.5rem;
    outline: none;
    letter-spacing: 0.08em;
    text-align: center;
    margin-top: 1.1rem;
  }

  .answer-input:focus { box-shadow: 0 2px 10px #00ff8820; }

  .btn {
    font-family: 'Orbitron', sans-serif;
    font-size: 0.85rem;
    letter-spacing: 0.18em;
    padding: 1rem 1.2rem;
    border: 2px solid var(--green);
    background: transparent;
    color: var(--green);
    cursor: pointer;
    text-transform: uppercase;
    transition: all 0.25s;
    position: relative;
    overflow: hidden;
    width: 100%;
    margin-top: 1rem;
    min-height: 54px;
    -webkit-tap-highlight-color: transparent;
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
  .btn:hover { color: var(--bg); box-shadow: 0 0 18px rgba(0,255,136,0.25); }
  .btn:disabled { opacity: 0.6; cursor: not-allowed; }

  .feedback {
    text-align: center;
    font-size: 0.85rem;
    min-height: 1.4rem;
    margin-top: 0.8rem;
    letter-spacing: 0.06em;
  }
  .feedback.ok { color: var(--green); }
  .feedback.err { color: var(--red); }

  .system-hint {
    border: 1px solid #ffaa0040;
    background: #110a00;
    padding: 0.9rem 1.1rem;
    color: var(--amber);
    font-size: 0.82rem;
    line-height: 1.7;
    display: none;
    margin-top: 1rem;
  }
  .system-hint.visible { display: block; }

  .terminal-ok {
    border: 1px solid var(--border);
    background: rgba(0, 0, 0, 0.35);
    padding: 1.2rem 1.4rem;
    max-width: 720px;
    width: 100%;
    line-height: 2.0;
    color: #88ccaa;
  }
  .terminal-ok .ok { color: var(--green); }

  @keyframes fadeIn { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
</style>
</head>
<body>

@include('components.game-audio-setup', ['trackKey' => 'prueba9'])

<div class="game-header">
  <div class="team-info">
    <strong>Equipo:</strong> {{ session('nombre_equipo', 'Sin equipo') }}<br>
    <strong>Clase:</strong> {{ session('clase', 'N/A') }}
  </div>
  <div class="header-timer" id="cronometro">--:--</div>
</div>

<!-- INTRO -->
<div class="screen active" id="screen-intro">
  <div class="logo">JUEGO DE PISTAS</div>
  <div class="subtitle">// 7 preguntas → 7 letras → contraseña final</div>
  <div class="panel" data-label="// INSTRUCCIONES">
    <div class="question">
      Responde correctamente para revelar una letra pista.
      <br><br>
      Cuando consigas las 7 letras, únelas en orden para formar la contraseña final.
    </div>
  </div>
  <button class="btn" id="btn-start" type="button">▶ INICIAR PRUEBA</button>
</div>

<!-- QUESTION -->
<div class="screen" id="screen-question">
  <div class="subtitle" id="q-counter">PREGUNTA 01 / 07</div>
  <div class="panel" id="q-panel" data-label="// PREGUNTA">
    <div class="question" id="q-text">—</div>

    <input class="answer-input" id="answer-input" type="text" autocomplete="off" spellcheck="false" autocapitalize="off" placeholder="ESCRIBE LA RESPUESTA">
    <button class="btn" id="btn-send" type="button">ENVIAR RESPUESTA →</button>
    <div class="feedback" id="feedback"></div>
    <div class="system-hint" id="system-hint">[PISTA DEL SISTEMA] — Piensa en lo que ves cada día en el instituto.</div>
  </div>

  <div class="panel" data-label="// LETRAS OBTENIDAS">
    <div class="letters-row" id="letters-row"></div>
  </div>
</div>

<!-- PASSWORD -->
<div class="screen" id="screen-password">
  <div class="subtitle">// CONTRASEÑA FINAL</div>
  <div class="panel" data-label="// LETRAS PISTA">
    <div class="letters-row" id="letters-row-final"></div>
  </div>
  <div class="panel" data-label="// INTRODUCE LA CONTRASEÑA">
    <div class="question" style="text-align:center; color:#b0e0bf;">
      Une las letras en orden y escribe la contraseña.
    </div>
    <input class="answer-input" id="password-input" type="text" autocomplete="off" spellcheck="false" autocapitalize="off" placeholder="CONTRASEÑA">
    <button class="btn" id="btn-password" type="button">VERIFICAR CONTRASEÑA →</button>
    <div class="feedback" id="feedback-pass"></div>
    <div class="system-hint" id="system-hint-pass">[PISTA DEL SISTEMA] — Piensa en lo que ves cada día en el instituto.</div>
  </div>
</div>

<!-- DONE -->
<div class="screen" id="screen-done">
  <div class="subtitle">// SISTEMA CENTRAL</div>
  <div class="terminal-ok" id="terminal-ok">
    <span class="ok">[OK]</span> Verificando contraseña... 100%<br>
    <span class="ok">[OK]</span> Acceso concedido al sistema central.<br>
    <span class="ok">[OK]</span> Prueba completada exitosamente.<br><br>
    "Has descifrado el código del instituto. El DAWSCAPE ha sido<br>
    completamente restaurado. ¡Enhorabuena!"
  </div>
  <button class="btn" id="btn-final" type="button">VER RESULTADOS FINALES →</button>
</div>

<script>
  const QUESTIONS = [
    { q: "¿Cuántas escaleras mecánicas no funcionan casi siempre?", letter: "J" },
    { q: "¿Cuántas clases de desarrollo web hay en total?", letter: "U" },
    { q: "¿Qué empresa invierte en FPS como Xtart?", letter: "M" },
    { q: "¿En qué clase de Xtart no hay un ordenador solo?", letter: "P" },
    { q: "¿Qué estabilizante llevan las patatas de Vicente Vidal?", letter: "E" },
    { q: "¿Qué hacer si alguien se atraganta con una Oreo?", letter: "R" },
    { q: "¿Dónde está el extintor de la clase 2ºDAM?", letter: "S" },
  ];

  const SERVER_PROGRESS = @json($progress ?? ['index' => 0, 'letters' => [], 'fails' => 0]);

  // TIMER
  let tiempoInicial = parseInt("{{ $tiempo_transcurrido ?? 0 }}") || 0;
  const loadTimeMs = Date.now();
  const timerDisplay = document.getElementById('cronometro');
  const MAX_MINUTOS = 90;
  function actualizarTimer() {
    const tiempo = tiempoInicial + Math.floor((Date.now() - loadTimeMs) / 1000);
    const m = Math.floor(tiempo / 60);
    const s = tiempo % 60;
    if (timerDisplay) timerDisplay.textContent = (m < 10 ? '0'+m : m) + ':' + (s < 10 ? '0'+s : s);
    if (m >= MAX_MINUTOS) { window.location.href = '/final'; }
  }
  actualizarTimer();
  setInterval(actualizarTimer, 1000);

  // UI helpers
  function showScreen(id) {
    document.querySelectorAll('.screen').forEach(s => s.classList.remove('active'));
    document.getElementById(id).classList.add('active');
  }

  function renderLetters(elId, letters) {
    const row = document.getElementById(elId);
    row.innerHTML = '';
    for (let i = 0; i < 7; i++) {
      const chip = document.createElement('div');
      chip.className = 'letter-chip';
      chip.textContent = letters[i] ? letters[i] : '_';
      row.appendChild(chip);
    }
  }

  let index = parseInt(SERVER_PROGRESS.index || 0);
  let letters = Array.isArray(SERVER_PROGRESS.letters) ? SERVER_PROGRESS.letters.slice(0) : [];
  let fails = parseInt(SERVER_PROGRESS.fails || 0);

  function refreshQuestionUI() {
    const counter = document.getElementById('q-counter');
    const qText = document.getElementById('q-text');
    const n = Math.min(index, QUESTIONS.length - 1);
    counter.textContent = `PREGUNTA ${String(n+1).padStart(2,'0')} / 07`;
    qText.textContent = QUESTIONS[n].q;
    renderLetters('letters-row', letters);
    document.getElementById('feedback').textContent = '';
    document.getElementById('feedback').className = 'feedback';
    document.getElementById('system-hint').classList.toggle('visible', fails >= 5);
    const inp = document.getElementById('answer-input');
    inp.value = '';
    setTimeout(() => inp.focus(), 50);
  }

  async function postJSON(payload) {
    const res = await fetch('/prueba9', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify(payload)
    });
    return await res.json();
  }

  async function sendAnswer() {
    const btn = document.getElementById('btn-send');
    const inp = document.getElementById('answer-input');
    const feedback = document.getElementById('feedback');
    btn.disabled = true;
    const answer = (inp.value || '').trim();

    try {
      const data = await postJSON({ action: 'answer', answer });
      if (!data.success) {
        fails = data.fails || fails;
        feedback.className = 'feedback err';
        feedback.textContent = data.message || '[ERROR] Respuesta incorrecta. Inténtalo de nuevo.';
        document.getElementById('system-hint').classList.toggle('visible', fails >= 5);
        btn.disabled = false;
        inp.focus();
        return;
      }

      // Correcta: guardar letra local para mostrar inmediatamente
      if (data.letter) letters.push(data.letter);
      renderLetters('letters-row', letters);

      // Si toca password screen
      if (data.state === 'password') {
        renderLetters('letters-row-final', data.letters || letters);
        showScreen('screen-password');
        document.getElementById('password-input').focus();
        return;
      }

      index = data.nextIndex || (index + 1);
      refreshQuestionUI();
      btn.disabled = false;
    } catch (e) {
      feedback.className = 'feedback err';
      feedback.textContent = '[ERROR] Fallo de conexión. Inténtalo de nuevo.';
      btn.disabled = false;
    }
  }

  async function sendPassword() {
    const btn = document.getElementById('btn-password');
    const inp = document.getElementById('password-input');
    const feedback = document.getElementById('feedback-pass');
    btn.disabled = true;

    try {
      const data = await postJSON({ action: 'password', password: inp.value || '' });
      if (!data.success) {
        fails = data.fails || fails;
        feedback.className = 'feedback err';
        feedback.textContent = data.message || '[ERROR] Contraseña incorrecta. Inténtalo de nuevo.';
        document.getElementById('system-hint-pass').classList.toggle('visible', fails >= 5);
        btn.disabled = false;
        inp.focus();
        return;
      }

      if (data.state === 'done') {
        showScreen('screen-done');
        return;
      }
      btn.disabled = false;
    } catch (e) {
      feedback.className = 'feedback err';
      feedback.textContent = '[ERROR] Fallo de conexión. Inténtalo de nuevo.';
      btn.disabled = false;
    }
  }

  document.getElementById('btn-start').addEventListener('click', () => {
    if (index >= 7) {
      renderLetters('letters-row-final', letters);
      showScreen('screen-password');
      document.getElementById('password-input').focus();
    } else {
      showScreen('screen-question');
      refreshQuestionUI();
    }
  });
  document.getElementById('btn-send').addEventListener('click', sendAnswer);
  document.getElementById('btn-password').addEventListener('click', sendPassword);
  document.getElementById('btn-final').addEventListener('click', () => window.location.href = '/final');

  // Si ya hay progreso en sesión, entrar directo a la pantalla correcta
  (function bootstrap() {
    if (index >= 7) {
      renderLetters('letters-row-final', letters);
      showScreen('screen-password');
    }
  })();
</script>
</body>
</html>

