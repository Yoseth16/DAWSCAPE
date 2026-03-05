<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>ESCAPE ROOM — CLÚSTER FRAGMENTADO | DAWSCAPE</title>
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

  .screen { display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 100vh; padding: 2rem; padding-top: 5rem; position: relative; z-index: 10; }

  /* ALERT BOX */
  .alert-box {
    width: 100%;
    max-width: 680px;
    border: 1px solid var(--red);
    background: rgba(255, 34, 68, 0.05);
    padding: 2rem;
    position: relative;
    margin-bottom: 2rem;
    animation: fadeIn 1s ease;
  }

  .alert-box::before {
    content: '[ALERTA DE SISTEMA]';
    position: absolute;
    top: -0.7rem; left: 1rem;
    background: var(--bg);
    padding: 0 0.5rem;
    color: var(--red);
    font-size: 0.8rem;
    font-weight: bold;
    letter-spacing: 0.1em;
    animation: blink 2s infinite;
  }

  .alert-box p {
    font-size: 1.05rem;
    line-height: 1.9;
    color: #ffcccc;
    margin-bottom: 1rem;
  }

  /* INPUT AREA */
  .terminal-box {
    width: 100%;
    max-width: 680px;
    border: 1px solid var(--border);
    background: var(--panel);
    padding: 2rem;
    position: relative;
    animation: fadeIn 1.5s ease;
  }

  .terminal-box::before {
    content: '// ENTRADA DE DATOS';
    position: absolute;
    top: -0.7rem; left: 1rem;
    background: var(--bg);
    padding: 0 0.5rem;
    color: var(--green);
    font-size: 0.7rem;
    letter-spacing: 0.2em;
  }

  .terminal-label {
    display: block;
    font-size: 0.9rem;
    color: #88ccaa;
    margin-bottom: 1rem;
    text-align: center;
  }

  .answer-input {
    width: 100%;
    background: transparent;
    border: none;
    border-bottom: 2px solid var(--green);
    color: var(--green);
    font-family: 'Share Tech Mono', monospace;
    font-size: 1.8rem;
    padding: 0.8rem 0.5rem;
    outline: none;
    letter-spacing: 0.5em;
    text-align: center;
    text-transform: uppercase;
    transition: border-color 0.2s;
  }

  .answer-input:focus { box-shadow: 0 2px 10px #00ff8820; }
  .answer-input::placeholder { color: #00ff8830; letter-spacing: 0.5em; }

  /* HINT AREA */
  .hint {
    display: none;
    margin-top: 1.5rem;
    padding: 1rem;
    border: 1px dashed #007744;
    color: #88ccaa;
    font-size: 0.9rem;
    text-align: center;
    line-height: 1.6;
    animation: fadeIn 0.5s ease;
  }

  /* CONSOLE OUTPUT */
  .console-out {
    margin-top: 2rem;
    font-size: 0.9rem;
    line-height: 1.6;
    min-height: 60px;
    width: 100%;
    max-width: 680px;
  }

  .out-ok { color: var(--green); }
  .out-err { color: var(--red); }

  .btn {
    font-family: 'Orbitron', sans-serif;
    font-size: 0.9rem;
    letter-spacing: 0.2em;
    padding: 1rem 2.5rem;
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
    display: none;
  }

  .btn.visible { display: block; }
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
  .btn:hover { color: var(--bg); }

  /* ANIMATIONS */
  @keyframes fadeIn { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
  @keyframes blink { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }

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

<div class="particles" id="particles"></div>

<div class="screen">
  
  <div class="alert-box">
    <p>La clave maestra de enrutamiento ha sido fragmentada por un malware. Los 5 paquetes de datos resultantes se han ocultado en la red de Nodos de Suministro (Máquinas Expendedoras) repartidos por todo el edificio.</p>
    <p><strong>Vuestra misión:</strong> Localizad las 5 máquinas de bebidas del instituto. Cada máquina tiene pegado un sector de memoria (una letra). Reunidlas todas y descifrad el anagrama para reconstruir el código de acceso.</p>
  </div>

  <div class="terminal-box">
    <label class="terminal-label">Introduce la clave maestra de 5 caracteres:</label>
    <input class="answer-input" id="answer-input" type="text" maxlength="5" placeholder="_ _ _ _ _" autocomplete="off" spellcheck="false" autofocus>
    
    <div class="hint" id="system-hint">
        [PISTA DEL SISTEMA] — Los sectores de memoria forman el nombre de una mujer.
    </div>
  </div>

  <div class="console-out" id="console-out"></div>
  
  <a href="/final" class="btn" id="next-btn">PASAR A LA SIGUIENTE PRUEBA →</a>

</div>

<script>
// ── TIMER DESDE BBDD ────────────────────────────────────────
let tiempo = parseInt("{{ $tiempo_transcurrido ?? 0 }}");
const timerDisplay = document.getElementById('cronometro');
const MAX_MINUTOS = 90;

function actualizarTimer() {
  let minutos = parseInt(tiempo / 60, 10);
  let segundos = parseInt(tiempo % 60, 10);

  let m = minutos  < 10 ? "0" + minutos  : minutos;
  let s = segundos < 10 ? "0" + segundos : segundos;

  timerDisplay.textContent = m + ":" + s;

  if (minutos >= MAX_MINUTOS) {
      window.location.href = '/final';
      return;
  }
  tiempo++;
}
actualizarTimer();
setInterval(actualizarTimer, 1000);

// ── PARTÍCULAS ───────────────────────────────────────────────
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

// ── LÓGICA DE VALIDACIÓN ─────────────────────────────────────
let failedAttempts = 0;
const correctAnswer = "ROCIO";
const inputField = document.getElementById('answer-input');
const consoleOut = document.getElementById('console-out');
const hintBox = document.getElementById('system-hint');
const nextBtn = document.getElementById('next-btn');
let solved = false;

inputField.addEventListener('keydown', function(e) {
    if (e.key === 'Enter' && !solved) {
        let val = this.value.trim().toUpperCase();
        
        // Remove spaces inside words
        val = val.replace(/\s+/g, '');
        
        if (val.length !== 5) {
            printError("[ERROR] Sintaxis inválida. La clave debe tener exactamente 5 caracteres.");
            return;
        }

        if (validateWord(val)) {
            solved = true;
            inputField.disabled = true;
            printSuccess();
            notifyServer();
        } else {
            failedAttempts++;
            printError("[ERROR] Clave no reconocida. Verificad los sectores de memoria.");
            if (failedAttempts >= 5) {
                hintBox.style.display = 'block';
            }
        }
    }
});

function validateWord(word) {
    // Basic normalization handling to make sure accents don't break logic just in case,
    // though ROCIO doesn't necessarily need it, better safe than sorry.
    const normalized = word.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
    return normalized === correctAnswer;
}

function printError(msg) {
    consoleOut.innerHTML = `<span class="out-err">${msg}</span>`;
    inputField.value = '';
}

function printSuccess() {
    consoleOut.innerHTML = `
        <span class="out-ok">[OK] Reconstrucción de paquetes exitosa... 100%</span><br>
        <span class="out-ok">[OK] Clave maestra aceptada.</span><br>
        <span class="out-ok">[OK] Cortafuegos perimetral desactivado.</span><br><br>
        <span style="color: #88ccaa">"Habéis restaurado el flujo de datos. Avanzad al siguiente nivel."</span>
    `;
    nextBtn.classList.add('visible');
}

function notifyServer() {
    fetch("/prueba2yoseth", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ completed: true })
    }).catch(err => console.error('Error:', err));
}
</script>
</body>
</html>
