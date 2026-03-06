<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VICTORIA | DAWSCAPE</title>
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

      .subtitle-txt {
        font-size: 1.1rem;
        line-height: 1.8;
        color: #88ccaa;
        margin-bottom: 2rem;
      }
      .victory-box.timeout .subtitle-txt {
        color: #ffcccc;
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

      .ranking-panel h4 {
        color: #007744;
        font-size: 0.85rem;
        letter-spacing: 0.2em;
        margin-bottom: 1rem;
        text-transform: uppercase;
      }

      .ranking-list {
        list-style-type: decimal;
        padding-left: 1.5rem;
        color: #669977;
        line-height: 1.8;
      }

      .ranking-list li { margin-bottom: 0.5rem; }
      .ranking-list li.current-team {
        color: var(--green);
        font-weight: bold;
        text-shadow: 0 0 5px var(--green);
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
    </style>
</head>
<body>

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
  @endif

      <div class="ranking-panel">
        <h4>>> TOP 3 EQUIPOS_</h4>
        @if($top3->count() > 0)
          <ol class="ranking-list">
              @foreach($top3 as $equipo)
                  <li class="{{ $equipo->email === $partida->email ? 'current-team' : '' }}">
                      {{ $equipo->nombre_equipo }} ({{ $equipo->clase }}) <span style="font-family:'Orbitron', sans-serif; float:right">[{{ floor($equipo->tiempo_segundos / 60) }}m {{ $equipo->tiempo_segundos % 60 }}s]</span>
                  </li>
              @endforeach
          </ol>
        @else
            <p style="color:#445544; font-size:0.9rem">Aún no hay datos de ranking.</p>
        @endif
      </div>

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
</script>

</body>
</html>