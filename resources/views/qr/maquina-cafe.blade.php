<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>NÚCLEO DE EXTRACCIÓN — DAWSCAPE</title>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Share+Tech+Mono&family=Orbitron:wght@400;700;900&display=swap');

  :root {
    --green: #00ff88;
    --red: #ff2244;
    --bg: #020c06;
    --panel: #041a0e;
    --border: #00ff8840;
    --text: #c8ffdf;
    --terminal: #0a0a0a;
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

  .screen { display: none; flex-direction: column; align-items: center; justify-content: center; padding: 1.5rem; padding-top: 5rem; padding-bottom: 2rem; min-height: 100vh; animation: fadeIn 0.5s ease; width: 100%;}
  .screen.active { display: flex; }

  .logo {
    font-family: 'Orbitron', sans-serif;
    font-size: clamp(1.8rem, 5vw, 3rem);
    font-weight: 900;
    color: var(--green);
    text-shadow: 0 0 20px var(--green), 0 0 60px #00ff8840;
    letter-spacing: 0.1em;
    margin-bottom: 2rem;
    text-align: center;
  }

  .intro-text {
    max-width: 600px;
    width: 100%;
    line-height: 1.8;
    color: #88ccaa;
    border: 1px solid var(--border);
    padding: clamp(1.5rem, 4vw, 2rem);
    background: var(--panel);
    position: relative;
    font-size: 1.05rem;
  }

  .intro-text::before {
    content: '// FIREWALL FÍSICO DETECTADO';
    position: absolute;
    top: -0.7rem; left: 1rem;
    background: var(--bg);
    padding: 0 0.5rem;
    color: var(--red);
    font-size: 0.7rem;
    letter-spacing: 0.2em;
  }
  
  .intro-text strong {
      color: var(--green);
      font-weight: normal;
  }
  
  .term-list {
      list-style-type: none;
      margin: 1.5rem 0;
      padding-left: 1rem;
      border-left: 2px solid var(--border);
  }
  
  .term-list li {
      margin-bottom: 0.8rem;
      position: relative;
  }
  
  .term-list li::before {
      content: '>';
      position: absolute;
      left: -1rem;
      color: var(--green);
  }

  .form-group {
    margin-top: 2rem;
    display: flex;
    flex-direction: column;
    gap: 1rem;
    align-items: center;
  }

  .answer-input {
    width: 100%;
    max-width: 300px;
    background: var(--terminal);
    border: 1px solid var(--border);
    color: var(--green);
    font-family: 'Share Tech Mono', monospace;
    font-size: clamp(1.4rem, 6vw, 1.8rem);
    padding: 1rem;
    outline: none;
    letter-spacing: 0.2em;
    text-align: center;
    transition: all 0.2s;
  }

  .answer-input:focus {
      box-shadow: 0 0 15px rgba(0,255,136,0.3);
      border-color: var(--green);
  }

  .answer-input.wrong {
    border-color: var(--red);
    color: var(--red);
    animation: shake 0.4s ease;
  }

  .answer-input.correct {
    border-color: var(--green);
    background: #00ff8808;
  }

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
    max-width: 300px;
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
  .btn:hover { color: var(--bg); }
  .btn:disabled { opacity: 0.5; cursor: not-allowed; pointer-events: none;}

  .feedback {
    text-align: center;
    font-size: 0.9rem;
    min-height: 1.5rem;
    margin-top: 0.5rem;
    letter-spacing: 0.08em;
  }
  .feedback.ok { color: var(--green); }
  .feedback.err { color: var(--red); }

  .hint-box {
      display: none;
      margin-top: 1.5rem;
      padding: 1rem;
      border: 1px dashed #aa9900;
      color: #ddcc44;
      font-size: 0.9rem;
      background: rgba(40,40,0,0.4);
      animation: fadeIn 0.5s ease;
  }

  /* TERMINAL WIN EFFECT */
  #term-win {
      width: 100%;
      max-width: 600px;
      background: var(--terminal);
      border: 1px solid var(--green);
      padding: 2rem;
      font-size: 1.1rem;
      line-height: 2;
      color: var(--green);
      box-shadow: inset 0 0 20px rgba(0,255,136,0.1);
  }

  .term-line { opacity: 0; }
  .term-line.show { opacity: 1; animation: slideRight 0.3s ease; }
  .term-line.narrative { color: #88ccaa; margin-top: 1.5rem; border-top: 1px dashed var(--border); padding-top: 1.5rem;}

  /* ANIMATIONS */
  @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
  @keyframes slideRight { from { opacity: 0; transform: translateX(-10px); } to { opacity: 1; transform: translateX(0); } }
  @keyframes pulse-glow {
    0%, 100% { text-shadow: 0 0 20px var(--green), 0 0 50px #00ff8860; }
    50% { text-shadow: 0 0 40px var(--green), 0 0 90px #00ff8840; }
  }
  @keyframes shake {
    0%,100% { transform: translateX(0); }
    20% { transform: translateX(-8px); }
    40% { transform: translateX(8px); }
    60% { transform: translateX(-5px); }
    80% { transform: translateX(5px); }
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

<!-- PANEL HACKING (MAIN) -->
<div class="screen active" id="screen-hacking">
  <div class="logo">PANEL DE MANTENIMIENTO</div>
  <div class="intro-text">
    <p style="color:var(--red)">Acceso al núcleo denegado.</p> 
    <p>El cortafuegos físico requiere una suma de verificación (checksum) basada en los parámetros de suministro actuales.</p>
    <br>
    <p>Para generar el código de bypass, combina estos tres factores de la unidad de infusión:</p>
    
    <ul class="term-list">
        <li>El censo total de disparadores táctiles (botones) de la interfaz.</li>
        <li>El valor nominal en céntimos de la esencia de cítricos (Té al Limón).</li>
        <li>El umbral de saturación de glucosa (nivel máximo de azúcar).</li>
    </ul>

    <p style="text-align: center; margin-top: 2rem;"><strong>Introduce la suma total para purgar el sistema... si te atreves.</strong></p>
    
    <div class="form-group">
        <label style="font-size: 0.8rem; color: #445544;">// CHECKSUM_REQUIRED</label>
        <input class="answer-input" id="checksum-input" type="number" placeholder="___" autocomplete="off">
        <div class="feedback" id="feedback-msg"></div>
        <button class="btn" id="btn-verificar" onclick="verificarChecksum()">VERIFICAR →</button>
    </div>

    <div class="hint-box" id="hint-box">
        <strong>[PISTA DEL SISTEMA]</strong> — La respuesta es una suma de TRES números. Cuenta bien todos los botones de la máquina (incluidos los de Start/Stop, Azúcar, etc).
    </div>
  </div>
</div>

<!-- TERMINAL WIN -->
<div class="screen" id="screen-win">
  <div class="logo">SISTEMA COMPROMETIDO</div>
  <div id="term-win">
      <div class="term-line" id="l1">[OK] Validating checksum... 100%</div>
      <div class="term-line" id="l2">[OK] Overflow de memoria corregido.</div>
      <div class="term-line" id="l3">[OK] Acceso concedido al núcleo del sistema.</div>
      <div class="term-line narrative" id="l4">-- NO MESSAGE --</div>
      <div class="term-line" id="l5" style="margin-top: 2rem; text-align: center;">
          <a href="/prueba4arturo" class="btn" style="text-decoration:none; display:inline-block;">PASAR A LA SIGUIENTE PRUEBA →</a>
      </div>
  </div>
</div>

<script>
// --- TIMER LOGIC ---
let tiempo = parseInt("{{ $tiempo_transcurrido ?? 0 }}");
const timerDisplay = document.getElementById('cronometro');
const MAX_MINUTOS = 90;

function actualizarTimer() {
  let minutos = parseInt(tiempo / 60, 10);
  let segundos = parseInt(tiempo % 60, 10);
  let m = minutos < 10 ? "0" + minutos : minutos;
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

// --- GAME LOGIC ---
let failedAttempts = 0;
let isProcessing = false;
const input = document.getElementById('checksum-input');
const btn = document.getElementById('btn-verificar');
const feedback = document.getElementById('feedback-msg');

input.addEventListener('keypress', function (e) {
    if (e.key === 'Enter') {
        verificarChecksum();
    }
});

function verificarChecksum() {
    if (isProcessing) return;
    const value = input.value.trim();
    
    if (value === "") {
        showError("Introduce un valor numérico válido.");
        return;
    }

    isProcessing = true;
    input.disabled = true;
    btn.disabled = true;
    feedback.textContent = "Verificando firmas locales...";
    feedback.className = "feedback";

    fetch('/prueba3angel', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ checksum: value })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            feedback.textContent = "[OK] SUMA DE VERIFICACIÓN ACEPTADA.";
            feedback.className = "feedback ok";
            input.classList.add('correct');
            
            setTimeout(() => {
                triggerWinSequence(data.mensaje);
            }, 1000);
        } else {
            failedAttempts++;
            showError("[ERROR] Checksum inválido o corrupto.");
            
            if (failedAttempts >= 5) {
                document.getElementById('hint-box').style.display = 'block';
            }
        }
    })
    .catch(error => {
        showError("Error de conexión con el host.");
    });
}

function showError(msg) {
    input.classList.add('wrong');
    feedback.textContent = msg;
    feedback.className = "feedback err";
    
    setTimeout(() => {
        input.classList.remove('wrong');
        input.value = '';
        input.disabled = false;
        btn.disabled = false;
        feedback.textContent = "";
        isProcessing = false;
        input.focus();
    }, 1200);
}

function triggerWinSequence(mensajeNarrativo) {
    document.getElementById('screen-hacking').classList.remove('active');
    document.getElementById('screen-win').classList.add('active');
    
    // Inyectamos el texto final desde backend
    document.getElementById('l4').innerText = mensajeNarrativo;

    // Secuencia de terminal delay
    const lines = [
        { id: 'l1', delay: 500 },
        { id: 'l2', delay: 1500 },
        { id: 'l3', delay: 2200 },
        { id: 'l4', delay: 3500 },
        { id: 'l5', delay: 4500 }
    ];

    lines.forEach(line => {
        setTimeout(() => {
            document.getElementById(line.id).classList.add('show');
        }, line.delay);
    });
}
</script>
</body>
</html>
