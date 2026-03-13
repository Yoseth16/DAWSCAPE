<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>PROTOCOLO ESPEJO | DAWSCAPE</title>
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

  /* HEADER */
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

  .screen {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 1.5rem;
      padding-top: 5rem;
      padding-bottom: 2rem;
      min-height: 100vh;
      animation: fadeIn 1s ease;
  }

  .logo {
    font-family: 'Orbitron', sans-serif;
    font-size: clamp(1.8rem, 5vw, 4rem);
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
    width: 100%;
    max-width: 600px;
    line-height: 1.8;
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
    content: '// ALERTA DE SISTEMA';
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

  .icon {
      font-size: 2.5rem;
      margin-bottom: 1rem;
      display: block;
      animation: float 3s ease-in-out infinite;
  }

  /* ANIMATIONS */
  @keyframes fadeIn { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
  @keyframes float {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-10px); text-shadow: 0 10px 20px rgba(0,255,136,0.3); }
  }
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
     QR NATIVO
  ============================ */
  .btn-qr-native {
    min-height: 48px;
    font-size: 1.1rem;
    font-weight: bold;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 1rem 2rem;
  }
  #qr-processing {
    margin-top: 1rem;
    font-size: 0.9rem;
    color: var(--green);
    display: none;
    text-align: center;
    text-shadow: 0 0 5px var(--green);
  }
</style>
</head>
<body>
  @include('components.game-audio-setup', ['trackKey' => 'prueba5'])
<div class="game-header">
  <div class="team-info">
    <strong>Equipo:</strong> {{ session('nombre_equipo', 'Sin equipo') }}<br>
    <strong>Clase:</strong> {{ session('clase', 'N/A') }}
  </div>
  <div class="header-timer" id="cronometro">--:--</div>
</div>

<div class="screen">
  <div class="logo">PROTOCOLO ESPEJO</div>
  <div class="intro-text">
    <span class="icon">🪞</span>
    <p>El sistema ha sido invertido. El orden natural ha desaparecido. Lo que empieza por A ahora termina en Z.</p>
    <br>
    <p>El archivo maestro ha sido fragmentado y ocultado en <strong>64 nodos metálicos</strong> alineados en el pasillo del oasis.</p>
    <br>
    <p>Si quieres restaurar el núcleo del DAWSCAPE, deberás pensar al revés.</p>
    <p><strong>Busca el muro de almacenamiento donde cada número guarda una parte del código. El alfabeto ha sido reflejado.</strong></p>
    <br>
    <p style="color: var(--red); font-weight: bold; border-top: 1px dashed var(--border); padding-top: 1rem; margin-top: 1rem;">Solo los más atentos podrán reconstruir la palabra clave.</p>
    <div style="margin-top: 1.25rem; display:flex; flex-direction:column; align-items:center; gap: 1rem;">
      <input type="file" accept="image/*" capture="environment" id="qr-input" style="display:none">
      <button id="btn-scan" type="button" class="btn btn-qr-native" onclick="document.getElementById('qr-input').click()">
        📷 ESCANEAR
      </button>
      <div id="qr-processing"></div>
    </div>
  </div>
</div>

<div id="dummy-reader" style="visibility:hidden; width:1px; height:1px; position:absolute;"></div>

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
// Escaneo QR Nativo (Prueba 5)
(function () {
  const PRUEBA_NUMERO = 5;

  const qrInput = document.getElementById('qr-input');
  const processingEl = document.getElementById('qr-processing');
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

  function setResult(msg, isError = false) {
    if (!processingEl) return;
    processingEl.textContent = msg;
    processingEl.style.color = isError ? '#ff8aa0' : 'var(--green)';
    processingEl.style.display = 'block';
  }

  async function validarCodigo(decodedText) {
    setResult('Verificando código...');

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

  qrInput?.addEventListener('change', async (e) => {
      if (e.target.files && e.target.files.length > 0) {
          const file = e.target.files[0];
          setResult('Procesando imagen...');
          
          try {
              if (typeof Html5Qrcode === 'undefined') {
                  setResult('Error al cargar escáner. Recarga la página.', true);
                  return;
              }
              const html5QrCode = new Html5Qrcode("dummy-reader");
              const decodedText = await html5QrCode.scanFile(file, true);
              await validarCodigo(decodedText);
          } catch (err) {
              setResult('No se encontró un código QR válido en la imagen. Inténtalo de nuevo.', true);
              if (window.audioManager) window.audioManager.playSFX('error');
          }
          
          e.target.value = ''; // Limpiar para permitir volver a subir la misma
      }
  });

})();
</script>
</body>
</html>

@include('components.pista-ayuda', ['pruebaNumero' => 5, 'pistaConfig' => $pistaConfig, 'yaUsada' => $yaUsada])
@include('components.live-listener')
