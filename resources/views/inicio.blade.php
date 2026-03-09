<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>DAWSCAPE | Escape Room - AULA 115</title>
<!-- ICONOS -->
<script src="https://unpkg.com/@phosphor-icons/web"></script>
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
  }

  .logo {
    font-family: 'Orbitron', sans-serif;
    font-size: clamp(2.5rem, 6vw, 4.5rem);
    font-weight: 900;
    color: var(--green);
    text-shadow: 0 0 20px var(--green), 0 0 60px #00ff8840;
    letter-spacing: 0.2em;
    animation: pulse-glow 2.5s ease-in-out infinite;
    margin-bottom: 0.5rem;
    text-align: center;
  }

  .subtitle {
    font-size: 0.85rem;
    color: #007744;
    letter-spacing: 0.3em;
    text-transform: uppercase;
    margin-bottom: 2rem;
    text-align: center;
  }

  .glitch-text { animation: blink 2s infinite; color: var(--red); font-size: 0.9rem; text-shadow: 0 0 10px var(--red); letter-spacing: 0.1em; text-align: center; margin-bottom: 2rem; }
  @keyframes blink { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }

  .login-card {
    width: 100%;
    max-width: 500px;
    border: 1px solid var(--border);
    background: var(--panel);
    padding: clamp(1.5rem, 5vw, 2.5rem) clamp(1rem, 4vw, 2rem);
    position: relative;
    box-shadow: 0 0 30px rgba(0, 255, 136, 0.1);
  }

  .login-card::before {
    content: '// IDENTIFICACIÓN';
    position: absolute;
    top: -0.7rem; left: 1rem;
    background: var(--bg);
    padding: 0 0.5rem;
    color: var(--green);
    font-size: 0.7rem;
    letter-spacing: 0.2em;
  }

  .form-group { margin-bottom: 1.8rem; }
  
  .form-label {
    display: block;
    font-size: 0.75rem;
    color: #007744;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    margin-bottom: 0.5rem;
  }

  .form-control {
    width: 100%;
    background: transparent;
    border: none;
    border-bottom: 2px solid #00ff8840;
    color: var(--green);
    font-family: 'Share Tech Mono', monospace;
    font-size: max(16px, 1.1rem); /* Prevents iOS zoom on focus */
    padding: 0.8rem 0.5rem;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
  }

  .form-control::placeholder { color: #88ccaa60; } /* Better WCAG contrast */
  .form-control:focus { border-bottom-color: var(--green); box-shadow: 0 4px 15px -10px var(--green); }

  .helper-text { display: block; margin-top: 0.4rem; color: #445544; font-size: 0.7rem; letter-spacing: 0.05em; }

  .checkbox-group {
    display: flex;
    align-items: flex-start;
    gap: 0.8rem;
    margin-top: 2rem;
    margin-bottom: 2rem;
  }

  input[type="checkbox"] {
    appearance: none;
    background-color: transparent;
    margin: 0;
    margin-top: 0.15rem;
    font: inherit;
    color: currentColor;
    width: 1.25em;
    height: 1.25em;
    border: 1px solid var(--green);
    display: grid;
    place-content: center;
    cursor: pointer;
  }

  input[type="checkbox"]::before {
    content: "";
    width: 0.65em;
    height: 0.65em;
    transform: scale(0);
    transition: 120ms transform ease-in-out;
    box-shadow: inset 1em 1em var(--green);
    background-color: var(--green);
  }

  input[type="checkbox"]:checked::before { transform: scale(1); }
  
  .checkbox-label { font-size: 0.85rem; color: #669977; line-height: 1.5; cursor: pointer; }

  /* Customization Selectors */
  .grid-selector { display: grid; grid-template-columns: repeat(8, 1fr); gap: 0.5rem; margin-top: 0.5rem; }
  .color-btn {
    aspect-ratio: 1; border-radius: 50%; border: 2px solid var(--panel); cursor: pointer; transition: transform 0.2s, box-shadow 0.2s;
  }
  .color-btn:hover { transform: scale(1.1); }
  .color-btn.active { border-color: var(--text); box-shadow: 0 0 15px currentColor; transform: scale(1.1); }
  
  .icon-btn {
    aspect-ratio: 1; border-radius: 8px; border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; font-size: 1.4rem; cursor: pointer; transition: all 0.2s; color: var(--green); background: rgba(0, 255, 136, 0.05);
  }
  .icon-btn:hover { background: rgba(0, 255, 136, 0.2); }
  .icon-btn.active { background: var(--green); color: var(--bg); border-color: var(--green); box-shadow: 0 0 15px var(--green); }

  .btn {
    font-family: 'Orbitron', sans-serif;
    font-size: 1rem;
    letter-spacing: 0.3em;
    padding: 1.2rem;
    border: 2px solid var(--green);
    background: transparent;
    color: var(--green);
    cursor: pointer;
    text-transform: uppercase;
    transition: all 0.25s;
    position: relative;
    overflow: hidden;
    width: 100%;
    font-weight: 700;
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
  .btn { min-height: 52px; -webkit-tap-highlight-color: transparent; }

  .error-feedback { color: var(--red); text-align: center; font-size: 0.85rem; margin-bottom: 1.5rem; letter-spacing: 0.08em; border: 1px dashed var(--red); padding: 0.8rem; background: rgba(255, 34, 68, 0.05); }

  @keyframes pulse-glow {
    0%, 100% { text-shadow: 0 0 20px var(--green), 0 0 50px #00ff8860; }
    50% { text-shadow: 0 0 40px var(--green), 0 0 90px #00ff8840; }
  }

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
  @media (max-width: 600px) {
    .screen { padding: 1rem; padding-top: 1.5rem; }
    .login-card { padding: 1.2rem 1rem; }
    .grid-selector { grid-template-columns: repeat(8, 1fr); gap: 0.3rem; }
    .icon-btn { font-size: 1.1rem; }
    .form-control { font-size: max(16px, 1rem); }
    .btn { font-size: 0.85rem; letter-spacing: 0.15em; }
  }
</style>
</head>
<body>

<div class="particles" id="particles"></div>

<div class="screen">
  <div class="logo">DAWSCAPE</div>
  <div class="subtitle">// AULA 115 · SISTEMA DE ESCAPE_</div>
  <div class="glitch-text">ACCESO RESTRINGIDO. IDENTIFÍQUESE.</div>

  <div class="login-card">
    <form action="/empezar" method="POST">
      @csrf

      @if(session('error'))
        <div class="error-feedback">✖ {{ session('error') }}</div>
      @endif
      
      <div class="form-group">
        <label class="form-label">CORREO INSTITUCIONAL</label>
        <input type="email" name="email" class="form-control" placeholder="usuario@estudiante.xtart.com" required autocomplete="off" autofocus>
        <span class="helper-text">* Solo se permite el dominio @estudiante.xtart.com</span>
      </div>

      <div class="form-group">
        <label class="form-label">NOMBRE DEL EQUIPO</label>
        <input type="text" name="nombre_equipo" class="form-control" placeholder="Ej: Los Hackers" required autocomplete="off">
        <span class="helper-text">Máx. 4 agentes por escuadrón</span>
      </div>

      <div class="form-group">
        <label class="form-label">CLASE</label>
        <input type="text" name="clase" class="form-control" placeholder="Ej: 1DAW" required autocomplete="off">
      </div>

      <!-- PERSONALIZACIÓN -->
      <div class="form-group">
        <label class="form-label">COLOR DEL EQUIPO</label>
        <input type="hidden" name="color_hex" id="input_color" value="#00ff88">
        <div class="grid-selector">
            <div class="color-btn active" data-color="#00ff88" style="background:#00ff88; color:#00ff88;"></div>
            <div class="color-btn" data-color="#3b82f6" style="background:#3b82f6; color:#3b82f6;"></div>
            <div class="color-btn" data-color="#f43f5e" style="background:#f43f5e; color:#f43f5e;"></div>
            <div class="color-btn" data-color="#a855f7" style="background:#a855f7; color:#a855f7;"></div>
            <div class="color-btn" data-color="#eab308" style="background:#eab308; color:#eab308;"></div>
            <div class="color-btn" data-color="#06b6d4" style="background:#06b6d4; color:#06b6d4;"></div>
            <div class="color-btn" data-color="#f97316" style="background:#f97316; color:#f97316;"></div>
            <div class="color-btn" data-color="#ec4899" style="background:#ec4899; color:#ec4899;"></div>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">AVATAR DEL EQUIPO</label>
        <input type="hidden" name="icono" id="input_icono" value="ph-first-aid">
        <div class="grid-selector" id="icons-grid">
            <div class="icon-btn active" data-icon="ph-first-aid" title="Primeros Auxilios (Sanidad)"><i class="ph ph-first-aid"></i></div>
            <div class="icon-btn" data-icon="ph-heartbeat" title="Cardiología (Sanidad)"><i class="ph ph-heartbeat"></i></div>
            <div class="icon-btn" data-icon="ph-tooth" title="Odontólogo (Dentistas)"><i class="ph ph-tooth"></i></div>
            <div class="icon-btn" data-icon="ph-pill" title="Farmacia (Parafarmacia)"><i class="ph ph-pill"></i></div>
            <div class="icon-btn" data-icon="ph-fire-extinguisher" title="Extintor (Emergencias)"><i class="ph ph-fire-extinguisher"></i></div>
            <div class="icon-btn" data-icon="ph-siren" title="Ambulancia (Protección Civil)"><i class="ph ph-siren"></i></div>
            <div class="icon-btn" data-icon="ph-megaphone" title="Megáfono (Marketing)"><i class="ph ph-megaphone"></i></div>
            <div class="icon-btn" data-icon="ph-chart-line-up" title="Crecimiento (Marketing)"><i class="ph ph-chart-line-up"></i></div>
        </div>
      </div>
      <!-- FIN PERSONALIZACIÓN -->

      <div class="checkbox-group">
        <input type="checkbox" id="checkHonestidad" required>
        <label class="checkbox-label" for="checkHonestidad">
            Certifico bajo honor que este es mi correo corporativo real. 
            El uso de identidad falsa conllevará la expulsión del sistema.
        </label>
      </div>

      <button type="submit" class="btn">INICIAR CONEXIÓN →</button>
    </form>
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
// Lógica Selectores
const colorBtns = document.querySelectorAll('.color-btn');
const inputColor = document.getElementById('input_color');
const iconBtns = document.querySelectorAll('.icon-btn');
const inputIcono = document.getElementById('input_icono');

colorBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        colorBtns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        inputColor.value = btn.dataset.color;
        
        // Efecto visual: cambiar el verde principal temporalmente
        document.documentElement.style.setProperty('--green', btn.dataset.color);
    });
});

iconBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        iconBtns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        inputIcono.value = btn.dataset.icon;
    });
});

</script>
<!-- Firma discreta -->
<div style="position: fixed; bottom: 5px; right: 10px; font-family: monospace; font-size: 0.65rem; color: rgba(255, 255, 255, 0.3); z-index: 9999; text-align: right;">
    DAWSCAPE SYSTEM // DAW <br>
    <a href="{{ route('legal') }}" style="color: rgba(255, 255, 255, 0.3); text-decoration: none; transition: color 0.3s;" onmouseover="this.style.color='#00ff88'" onmouseout="this.style.color='rgba(255, 255, 255, 0.3)'">AVISO LEGAL Y RGPD</a>
</div>
</body>
</html>