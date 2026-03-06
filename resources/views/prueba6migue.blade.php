<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>SOBRECARGA DEL SISTEMA | DAWSCAPE</title>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Share+Tech+Mono&family=Orbitron:wght@400;700;900&display=swap');

  :root {
    --green: #00ff88;
    --red: #ff2244;
    --bg: #020c06;
    --panel: #041a0e;
    --border: #00ff8840;
    --text: #c8ffdf;
    
    /* Colores de los sectores */
    --c1: #ff2244; /* Rojo */
    --c2: #00ff88; /* Verde */
    --c3: #00aaff; /* Azul */
    --c4: #ffdd00; /* Amarillo */
  }

  * { margin: 0; padding: 0; box-sizing: border-box; }

  body {
    background: var(--bg);
    color: var(--text);
    font-family: 'Share Tech Mono', monospace;
    min-height: 100vh;
    overflow-x: hidden;
    user-select: none;
    -webkit-tap-highlight-color: transparent;
  }

  body::before {
    content: '';
    position: fixed;
    inset: 0;
    background: repeating-linear-gradient(0deg, transparent, transparent 2px, rgba(0,255,136,0.013) 2px, rgba(0,255,136,0.013) 4px);
    pointer-events: none;
    z-index: 1000;
  }

  /* HEADER GENERAL ESCAPE ROOM */
  .game-header {
    position: fixed;
    top: 0; left: 0; right: 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.8rem 1rem;
    background: rgba(2, 12, 6, 0.95);
    border-bottom: 1px solid var(--border);
    z-index: 100;
    backdrop-filter: blur(8px);
  }

  .team-info {
    font-size: 0.75rem;
    color: #88ccaa;
    line-height: 1.5;
  }
  .team-info strong { color: var(--green); }
  .header-timer {
    font-family: 'Orbitron', sans-serif;
    font-size: 1.3rem;
    color: var(--green);
    text-shadow: 0 0 10px var(--green);
    letter-spacing: 0.1em;
  }

  .screen { 
      display: flex; 
      flex-direction: column; 
      align-items: center; 
      justify-content: flex-start; 
      padding: 1.5rem; 
      padding-top: 5rem; 
      padding-bottom: 2rem;
      min-height: 100vh; 
      width: 100%;
      animation: fadeIn 0.5s ease; 
  }

  .title-glitch {
    font-family: 'Orbitron', sans-serif;
    font-size: clamp(1.2rem, 5vw, 2.5rem);
    font-weight: 900;
    color: var(--green);
    text-shadow: 0 0 15px var(--green);
    letter-spacing: 0.1em;
    margin-bottom: 1rem;
    text-align: center;
    text-transform: uppercase;
  }

  .lore-box {
    width: 100%;
    max-width: 500px;
    border: 1px dashed var(--green);
    padding: 1rem;
    margin-bottom: 1.5rem;
    background: rgba(0,255,136,0.02);
    font-size: 0.9rem;
    line-height: 1.6;
    text-align: center;
    color: #88ccaa;
  }

  #sys-status {
      font-weight: bold;
      color: var(--red);
      margin-bottom: 0.5rem;
      font-size: 1.1rem;
      letter-spacing: 0.1em;
      min-height: 1.5rem;
      text-transform: uppercase;
  }
  .status-ok { color: var(--green) !important; text-shadow: 0 0 10px var(--green); }
  .status-info { color: #00aaff !important; text-shadow: 0 0 10px #00aaff; }

  /* GAME BOARD */
  .simon-board {
      display: grid;
      grid-template-columns: 1fr 1fr;
      grid-gap: 1rem;
      width: 100%;
      max-width: 400px;
      margin: 1rem auto;
      pointer-events: none; /* Bloqueado hasta que termine la secuencia */
      opacity: 0.5;
      transition: opacity 0.3s;
  }
  .simon-board.active { pointer-events: auto; opacity: 1; }

  .sector {
      aspect-ratio: 1 / 1;
      border: 3px solid #223322;
      border-radius: 10px;
      cursor: pointer;
      position: relative;
      overflow: hidden;
      background: #011105;
      box-shadow: inset 0 0 20px #000;
      transition: all 0.1s;
  }

  .sector::after {
      content: ''; position: absolute; inset: 0; opacity: 0; transition: opacity 0.1s;
  }

  /* Colores Base apagados */
  #btn-0 { border-color: rgba(255,34,68,0.3); }
  #btn-1 { border-color: rgba(0,255,136,0.3); }
  #btn-2 { border-color: rgba(0,170,255,0.3); }
  #btn-3 { border-color: rgba(255,221,0,0.3); }

  /* Colores Encendidos (Light Up) */
  #btn-0.lit, #btn-0:active { border-color: var(--c1); background: rgba(255,34,68,0.2); box-shadow: 0 0 40px var(--c1), inset 0 0 30px var(--c1); }
  #btn-1.lit, #btn-1:active { border-color: var(--c2); background: rgba(0,255,136,0.2); box-shadow: 0 0 40px var(--c2), inset 0 0 30px var(--c2); }
  #btn-2.lit, #btn-2:active { border-color: var(--c3); background: rgba(0,170,255,0.2); box-shadow: 0 0 40px var(--c3), inset 0 0 30px var(--c3); }
  #btn-3.lit, #btn-3:active { border-color: var(--c4); background: rgba(255,221,0,0.2); box-shadow: 0 0 40px var(--c4), inset 0 0 30px var(--c4); }

  /* HUD INFOS */
  .hud {
      display: flex;
      justify-content: space-between;
      width: 100%;
      max-width: 400px;
      margin-top: 1rem;
      font-size: 0.9rem;
  }
  .hud div { background: var(--panel); padding: 0.5rem 1rem; border: 1px solid var(--border); }

  /* START BTN */
  .btn-start {
      margin-top: 2rem;
      border: 2px solid var(--green);
      background: rgba(0,255,136,0.1);
      color: var(--green);
      padding: 1rem 2rem;
      font-family: 'Orbitron', sans-serif;
      font-size: 1.1rem;
      font-weight: 900;
      cursor: pointer;
      text-transform: uppercase;
      letter-spacing: 0.1em;
      transition: all 0.3s;
  }
  .btn-start:hover { background: var(--green); color: var(--bg); }

  /* SUCCESS SCREEN (Oculto inicio) */
  #screen-success { display: none; text-align: center; justify-content: center; }
  .success-btn {
      margin-top: 2rem;
      display: inline-block;
      text-decoration: none;
      border: 2px solid var(--green);
      color: var(--bg);
      background: var(--green);
      padding: 1rem 2rem;
      font-family: 'Orbitron', sans-serif;
      text-transform: uppercase;
      font-weight: bold;
      transition: all 0.3s;
  }

  @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
  @keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-5px); } 75% { transform: translateX(5px); } }

</style>
</head>
<body>

<!-- HEADER -->
<div class="game-header">
  <div class="team-info">
    <strong>Equipo:</strong> {{ session('nombre_equipo', 'Sin equipo') }}<br>
    <strong>Clase:</strong> {{ session('clase', 'N/A') }}
  </div>
  <div class="header-timer" id="cronometro">--:--</div>
</div>

<div class="screen" id="screen-game">
    <div class="title-glitch">RESTAURACIÓN DE ENERGÍA</div>
    
    <div class="lore-box">
        <strong>FALLO CASCADA DETECTADO.</strong><br>
        El sistema requiere la reintroducción manual de las secuencias de encendido. 
        <br><br>Repartíos el esfuerzo mental: la frecuencia sobrecargará la memoria de un solo individuo.
    </div>

    <!-- Indicador de estado -->
    <div id="sys-status">SISTEMA A LA ESPERA</div>

    <button id="btn-start" class="btn-start" onclick="startGame()">▶ INICIAR SECUENCIA</button>

    <div class="hud" style="display:none;" id="hud-container">
        <div>FASE: <span id="ronda-val" style="color:var(--green);font-weight:bold;">1/5</span></div>
        <div>PASOS: <span id="pasos-val" style="color:#00aaff;font-weight:bold;">0</span></div>
    </div>

    <!-- Tablero 2x2 -->
    <div class="simon-board" id="board">
        <div class="sector" id="btn-0" onmousedown="touchSector(0)" ontouchstart="touchSector(0)"></div>
        <div class="sector" id="btn-1" onmousedown="touchSector(1)" ontouchstart="touchSector(1)"></div>
        <div class="sector" id="btn-2" onmousedown="touchSector(2)" ontouchstart="touchSector(2)"></div>
        <div class="sector" id="btn-3" onmousedown="touchSector(3)" ontouchstart="touchSector(3)"></div>
    </div>

</div>

<!-- PANTALLA ÉXITO -->
<div class="screen" id="screen-success">
    <div style="font-size: 5rem; margin-bottom: 1rem; filter: drop-shadow(0 0 20px #00ff88);">⚡</div>
    <div class="title-glitch" style="margin-bottom:0.5rem">SISTEMA RESTAURADO</div>
    <div style="color:#88ccaa; font-size:1.1rem; margin-bottom: 2rem;">"Enrutamiento de energía cooperativo completado al 100%."</div>
    <a href="/final" class="success-btn">COMPLETAR ESCAPE ROOM →</a>
</div>

<!-- LIBRERÍA DE SONIDOS (Simulados generados con Web Audio API para no requerir mp3 externos) -->
<script>
// Web Audio API Synth Simple
const AudioContext = window.AudioContext || window.webkitAudioContext;
const audioCtx = new AudioContext();

function playTone(freq, type = 'sine', duration = 0.3) {
    if (audioCtx.state === 'suspended') audioCtx.resume();
    const osc = audioCtx.createOscillator();
    const gainNode = audioCtx.createGain();
    
    osc.type = type;
    osc.frequency.value = freq;
    
    // Envelope
    gainNode.gain.setValueAtTime(0, audioCtx.currentTime);
    gainNode.gain.linearRampToValueAtTime(0.5, audioCtx.currentTime + 0.05);
    gainNode.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + duration);
    
    osc.connect(gainNode);
    gainNode.connect(audioCtx.destination);
    
    osc.start();
    osc.stop(audioCtx.currentTime + duration);
}

// Frecuencias (Rojo, Verde, Azul, Amarillo, Error, Éxito)
const FREQS = [329.63, 261.63, 220.00, 164.81]; // E4, C4, A3, E3
const S_ERR = 110.00; // A2
const S_OK = 880.00;  // A5

// ── TIMER (Mismo de siempre) ──
let tiempo = parseInt("{{ $tiempo_transcurrido ?? 0 }}");
const timerDisplay = document.getElementById('cronometro');
const MAX_MINUTOS = 90;

function actualizarTimer() {
  let minutos = parseInt(tiempo / 60, 10);
  let segundos = parseInt(tiempo % 60, 10);
  let m = minutos  < 10 ? "0" + minutos  : minutos;
  let s = segundos < 10 ? "0" + segundos : segundos;
  timerDisplay.textContent = m + ":" + s;
  if (minutos >= MAX_MINUTOS) { window.location.href = '/final'; return; }
  tiempo++;
}
actualizarTimer();
setInterval(actualizarTimer, 1000);

// ── LOGICA SIMON SAYS COOPERATIVO ──
let sequence = [];
let playerStep = 0;
let roundLengths = [4, 6, 9, 12, 15]; // Rondas exigentes
let currentRound = 0;
let isPlaying = false; 

const board = document.getElementById('board');
const statusTxt = document.getElementById('sys-status');
const rondaVal = document.getElementById('ronda-val');
const pasosVal = document.getElementById('pasos-val');

function startGame() {
    document.getElementById('btn-start').style.display = 'none';
    document.getElementById('hud-container').style.display = 'flex';
    
    // Generar la secuencia completa del tirón (el maximo) para ir revelando parte por parte
    sequence = [];
    let maxSteps = roundLengths[roundLengths.length - 1];
    for(let i=0; i < maxSteps; i++) {
        sequence.push(Math.floor(Math.random() * 4));
    }

    currentRound = 0;
    playRound();
}

function playRound() {
    isPlaying = false;
    board.classList.remove('active'); // Bloquea clics
    playerStep = 0;

    rondaVal.innerText = (currentRound + 1) + "/" + roundLengths.length;
    let targetSteps = roundLengths[currentRound];
    pasosVal.innerText = targetSteps;
    
    statusTxt.innerText = "// MEMORIZANDO PATRÓN...";
    statusTxt.className = 'status-info';

    // Reproducir secuencia parcial hasta el límite de la ronda
    let i = 0;
    // Un pequeño delay inicial
    setTimeout(() => {
        let interval = setInterval(() => {
            lightUp(sequence[i]);
            i++;
            if (i >= targetSteps) {
                clearInterval(interval);
                // Dar control al jugador
                setTimeout(() => {
                    statusTxt.innerText = "INTRODUCE LA SECUENCIA REQUERIDA";
                    statusTxt.className = '';
                    isPlaying = true;
                    board.classList.add('active');
                }, 500);
            }
        }, 800); // 800ms de velocidad base.
    }, 1000);
}

function lightUp(sectorId, isPlayer = false) {
    const btn = document.getElementById('btn-' + sectorId);
    btn.classList.add('lit');
    playTone(FREQS[sectorId], isPlayer ? 'square' : 'sine', 0.4);
    
    setTimeout(() => {
        btn.classList.remove('lit');
    }, 450); // Mantiene destello poco mas de medio segundo
}

function touchSector(sectorId) {
    // Evita doble triggering por eventos simultáneos de touch y click si fuera necesario,
    // o clics cuando no toca jugar.
    if (!isPlaying) return;

    // Efecto visual instantaneo
    lightUp(sectorId, true);

    // Comprobar logica
    let targetSteps = roundLengths[currentRound];

    if (sectorId === sequence[playerStep]) {
        // Correcto
        playerStep++;
        
        if (playerStep === targetSteps) {
            // Completó la ronda!
            isPlaying = false;
            board.classList.remove('active');
            statusTxt.innerText = "[OK] FASE ESTABILIZADA";
            statusTxt.className = 'status-ok';
            playTone(S_OK, 'triangle', 0.5);

            setTimeout(() => {
                currentRound++;
                if(currentRound >= roundLengths.length) {
                    winGame();
                } else {
                    playRound();
                }
            }, 1500);
        }
    } else {
        // Falló
        isPlaying = false;
        board.classList.remove('active');
        board.style.animation = 'shake 0.4s';
        
        statusTxt.innerText = "[ERROR] DESINCRONIZACIÓN. REINICIANDO FASE...";
        statusTxt.className = 'status-info';
        statusTxt.style.color = 'var(--red)';
        
        playTone(S_ERR, 'sawtooth', 0.8);

        setTimeout(() => {
            board.style.animation = '';
            // repite la misma ronda (no limpia a cero, la repite desde el primer paso para no frutrarles infinitamente)
            playRound();
        }, 2000);
    }
}

function winGame() {
    document.getElementById('screen-game').style.display = 'none';
    document.getElementById('screen-success').style.display = 'flex';

    fetch("/prueba6migue", {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
        body: JSON.stringify({ completed: true })
    }).then(r => r.json()).then(res => {
        if(res.success) {
            console.log(res.mensaje);
        }
    });
}
</script>

</body>
</html>
