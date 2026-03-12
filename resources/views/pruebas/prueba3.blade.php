<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>OPERACIÓN CAFEÍNA | DAWSCAPE</title>  @vite(['resources/css/app.css', 'resources/js/app.js'])<style>
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

  /* MEJORADO: Team info con mejor contraste */
  .team-info {
    font-size: 0.75rem;
    color: #b0e0bf;
    line-height: 1.6;
    text-shadow: 0 0 8px rgba(0, 255, 136, 0.2);
  }

  .team-info strong { color: var(--green); text-shadow: 0 0 10px var(--green); }

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

  /* MEJORADO: Intro text con mejor contraste */
  .intro-text {
    max-width: 600px;
    line-height: 1.9;
    color: #c8ffdf;
    border: 1px solid var(--border);
    padding: clamp(1.5rem, 5vw, 2.5rem) clamp(1.2rem, 4vw, 2rem);
    background: var(--panel);
    position: relative;
    text-align: center;
    font-size: clamp(1rem, 4vw, 1.1rem);
    text-shadow: 0 0 5px rgba(200, 255, 223, 0.1);
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

  /* ===========================
     QR MODAL (Prueba 3)
     - Overlay oscuro
     - Centrado
     - Z-index > scanlines (body::before)
  ============================ */
  #qr-modal {
    position: fixed;
    inset: 0;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 1rem;
    background: rgba(0, 0, 0, 0.75);
    z-index: 3000;
  }
  #qr-modal[aria-hidden="false"] { display: flex; }
  .qr-panel {
    width: 100%;
    max-width: 520px;
    background: var(--panel);
    border: 1px solid var(--border);
    box-shadow: 0 0 35px rgba(0,255,136,0.15);
    padding: 1rem;
  }
  .qr-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 0.75rem;
  }
  .qr-title {
    font-family: 'Orbitron', sans-serif;
    font-size: 0.95rem;
    letter-spacing: 0.12em;
    color: var(--green);
  }
  .btn {
    cursor: pointer;
    border: 1px solid var(--border);
    background: rgba(0,255,136,0.08);
    color: var(--text);
    padding: 0.65rem 0.85rem;
    font-family: inherit;
    font-size: 0.95rem;
    transition: transform .08s ease, background .15s ease, border-color .15s ease;
  }
  .btn:hover { background: rgba(0,255,136,0.14); border-color: rgba(0,255,136,0.6); }
  .btn:active { transform: translateY(1px); }
  .btn-danger { background: rgba(255,34,68,0.10); border-color: rgba(255,34,68,0.40); }
  .btn-danger:hover { background: rgba(255,34,68,0.18); border-color: rgba(255,34,68,0.65); }
  #qr-reader {
    width: 100%;
    border: 1px dashed var(--border);
    background: rgba(2, 12, 6, 0.35);
    padding: 0.5rem;
  }
  #qr-result {
    margin-top: 0.75rem;
    font-size: 0.9rem;
    color: #b0e0bf;
    line-height: 1.5;
    min-height: 1.4rem;
  }
  .qr-hint {
    margin-top: 0.5rem;
    font-size: 0.75rem;
    color: #7aa88c;
    opacity: 0.9;
  }
</style>
</head>
<body>
  @include('components.game-audio-setup', ['trackKey' => 'prueba3'])
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
    <p style="font-size: 0.85rem; color:#445544; margin-top: 1rem;">// Continúa cuando encuentres la pista en la localización física.</p>
    <div style="margin-top: 1.25rem; display:flex; justify-content:center;">
      <button id="btn-scan" type="button" class="btn">📷 Escanear QR</button>
    </div>
  </div>
</div>

<!-- Modal / Overlay QR -->
<div id="qr-modal" aria-hidden="true" role="dialog" aria-modal="true" aria-label="Escáner QR">
  <div class="qr-panel">
    <div class="qr-panel-header">
      <div class="qr-title">ESCÁNER QR // PRUEBA 3</div>
      <button id="btn-close-modal" class="btn btn-danger" type="button">✕ Cerrar</button>
    </div>
    <div id="qr-reader"></div>
    <p id="qr-result">Pulsa "📷 Escanear QR" para iniciar la cámara.</p>
    <div class="qr-hint">// Si no aparece la cámara, revisa permisos del navegador.</div>
  </div>
</div>

<!-- Librería html5-qrcode (CDN) -->
<script src="https://unpkg.com/html5-qrcode"></script>

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

<script>
// Escaneo QR (html5-qrcode) — Prueba 3
(function () {
  const PRUEBA_NUMERO = 3;

  const btnScan  = document.getElementById('btn-scan');
  const modal    = document.getElementById('qr-modal');
  const btnClose = document.getElementById('btn-close-modal');
  const resultEl = document.getElementById('qr-result');
  const readerEl = document.getElementById('qr-reader');

  const csrfToken =
    document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

  let scanner = null;
  let isStopping = false;

  function setResult(msg, isError = false) {
    if (!resultEl) return;
    resultEl.textContent = msg;
    resultEl.style.color = isError ? '#ff8aa0' : '#b0e0bf';
  }

  async function stopScanner() {
    if (!scanner || isStopping) return;
    isStopping = true;
    try {
      await scanner.clear();
    } catch (e) {
    } finally {
      scanner = null;
      isStopping = false;
      if (readerEl) readerEl.innerHTML = '';
    }
  }

  function openModal() {
    if (!modal) return;
    modal.setAttribute('aria-hidden', 'false');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
  }

  async function closeModal() {
    await stopScanner();
    if (!modal) return;
    modal.setAttribute('aria-hidden', 'true');
    modal.style.display = 'none';
    document.body.style.overflow = '';
    setResult('Escáner cerrado. Puedes volver a intentarlo cuando quieras.');
  }

  async function validarCodigo(decodedText) {
    setResult('Verificando código...');

    // Si el código escaneado es una URL directa de DAWSCAPE (ej. https://dawscape.fun/qr/prueba3angel)
    if (decodedText.includes('dawscape.fun/qr/') || decodedText.includes('/qr/')) {
        if (window.audioManager) window.audioManager.playSFX('correct');
        window.location.href = decodedText;
        return;
    }

    try {
      const resp = await fetch('/validar-codigo-qr', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json'
        },
        body: JSON.stringify({ codigo: decodedText, prueba: PRUEBA_NUMERO })
      });

      const data = await resp.json().catch(() => ({}));
      if (resp.ok && data && data.success === true && data.redirect_url) {
        if (window.audioManager) window.audioManager.playSFX('correct');
        window.location.href = data.redirect_url;
        return;
      }

      const msg =
        data?.message ||
        (resp.status === 429
          ? 'Demasiados intentos. Espera un minuto e inténtalo de nuevo.'
          : 'No se pudo validar el código. Inténtalo de nuevo.');
      if (window.audioManager) window.audioManager.playSFX('error');
      setResult(msg, true);
    } catch (err) {
      setResult('Error de red. Revisa tu conexión e inténtalo de nuevo.', true);
    }
  }

  async function startScanner() {
    if (typeof Html5QrcodeScanner === 'undefined') {
      setResult('No se pudo cargar la librería del escáner. Recarga la página.', true);
      return;
    }

    await stopScanner();
    if (readerEl) readerEl.innerHTML = '';
    setResult('Inicializando cámara...');

    const qrSize = Math.min(250, window.innerWidth - 80);
    const config = {
      fps: 10,
      qrbox: { width: qrSize, height: qrSize },
      rememberLastUsedCamera: true,
    };

    scanner = new Html5QrcodeScanner('qr-reader', config, /* verbose= */ false);
    scanner.render(
      async (decodedText) => {
        await stopScanner();
        await validarCodigo(decodedText);
      },
      () => {
        // errores normales de escaneo, los ignoramos
      }
    );

    setTimeout(() => {
      const hasVideo = readerEl?.querySelector('video');
      if (!hasVideo && modal && modal.getAttribute('aria-hidden') === 'false') {
        setResult('No se pudo acceder a la cámara. Revisa permisos o usa otro dispositivo.', true);
      }
    }, 1200);
  }

  btnScan?.addEventListener('click', async () => {
    openModal();
    await startScanner();
  });

  btnClose?.addEventListener('click', closeModal);

  modal?.addEventListener('click', (e) => {
    if (e.target === modal) closeModal();
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && modal && modal.getAttribute('aria-hidden') === 'false') {
      closeModal();
    }
  });
})();
</script>
</body>
</html>

@include('components.pista-ayuda', ['pruebaNumero' => 3, 'pistaConfig' => $pistaConfig, 'yaUsada' => $yaUsada])
@include('components.live-listener')
