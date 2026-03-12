{{--
  Componente Pista/Ayuda DAWSCAPE
  Props: $pruebaNumero, $pistaConfig, $yaUsada
--}}
@php
    $imagenCara = $pistaConfig['imagen'] ?? 'mario.png';
    $pruebaNum  = $pruebaNumero ?? 1;
    $usada      = $yaUsada ?? false;
@endphp

<style>
/* ═══ SISTEMA DE PISTAS ═══ */
.pista-btn {
    position: fixed;
    bottom: 1.5rem;
    right: 1.5rem;
    width: 80px;
    height: 80px;
    border-radius: 50%;
    border: 4px solid var(--green, #00ff88);
    background: linear-gradient(135deg, rgba(4, 26, 14, 0.95), rgba(0, 50, 25, 0.95));
    cursor: pointer;
    z-index: 500;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 0 20px rgba(0,255,136,0.5), 0 6px 20px rgba(0,0,0,0.6), inset 0 0 15px rgba(0,255,136,0.2);
    animation: pista-pulse 2s ease-in-out infinite;
    padding: 0;
}
.pista-btn img {
    width: 100%; height: 100%;
    object-fit: cover;
    image-rendering: pixelated;
    display: block;
}
.pista-btn:hover {
    transform: scale(1.15);
    box-shadow: 0 0 25px rgba(0,255,136,0.5), 0 0 50px rgba(0,255,136,0.2);
    border-color: #66ffaa;
}
.pista-btn:active { transform: scale(1.05); }
.pista-btn.usada {
    border-color: #ffaa00;
    box-shadow: 0 0 15px rgba(255,170,0,0.3), 0 4px 15px rgba(0,0,0,0.5);
    animation: none;
}
.pista-btn.usada:hover { box-shadow: 0 0 25px rgba(255,170,0,0.5); border-color: #ffcc44; }

@keyframes pista-pulse {
    0%,100% { box-shadow: 0 0 15px rgba(0,255,136,0.3), 0 4px 15px rgba(0,0,0,0.5); }
    50%     { box-shadow: 0 0 25px rgba(0,255,136,0.5), 0 0 40px rgba(0,255,136,0.15); }
}

/* Tooltip */
.pista-tooltip {
    position: fixed;
    bottom: 6rem; right: 1.2rem;
    background: rgba(4,26,14,0.95);
    border: 1px solid var(--green, #00ff88);
    color: var(--green, #00ff88);
    font-family: 'Share Tech Mono', monospace;
    font-size: 0.7rem;
    padding: 0.4rem 0.8rem;
    border-radius: 6px;
    white-space: nowrap;
    z-index: 499;
    pointer-events: none;
    opacity: 0;
    transform: translateY(5px);
    transition: all 0.3s ease;
}

/* Overlay con fade correcto */
.pista-overlay {
    position: fixed; inset: 0;
    background: rgba(0,0,0,0.6);
    z-index: 600;
    display: flex;
    align-items: center; justify-content: center;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s ease;
    backdrop-filter: blur(3px);
}
.pista-overlay.visible {
    opacity: 1;
    pointer-events: auto;
}

/* Bocadillo */
.pista-bocadillo {
    position: relative;
    width: 90%; max-width: 420px;
    min-height: 200px;
    display: flex; align-items: center; justify-content: center;
    transform: scale(0.8) translateY(20px);
    opacity: 0;
    transition: all 0.4s ease;
}
.pista-overlay.visible .pista-bocadillo {
    transform: scale(1) translateY(0);
    opacity: 1;
}
.pista-bocadillo-bg {
    width: 100%; height: auto;
    display: block;
    image-rendering: pixelated;
}
.pista-bocadillo-texto {
    position: absolute;
    top: 18%; left: 15%; right: 15%; bottom: 35%;
    display: flex; align-items: center; justify-content: center;
    text-align: center;
    font-family: 'Share Tech Mono', monospace;
    font-size: clamp(0.8rem, 3vw, 0.95rem);
    color: #1a1a2e;
    line-height: 1.7;
    padding: 1rem;
    font-weight: 600;
    overflow-y: auto;
    word-wrap: break-word;
    max-height: 100%;
}
.pista-close {
    position: absolute; top: 6%; right: 8%;
    width: 30px; height: 30px;
    border: none;
    background: rgba(0,0,0,0.6);
    color: white; font-size: 1.2rem;
    border-radius: 50%;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: all 0.2s; z-index: 10;
}
.pista-close:hover { background: rgba(255,34,68,0.8); transform: scale(1.1); }

/* Penalización aviso */
.pista-penalizacion-aviso {
    position: fixed;
    top: 4.5rem; left: 50%;
    transform: translateX(-50%) translateY(-20px);
    background: rgba(255,170,0,0.95);
    color: #1a1a00;
    font-family: 'Orbitron', sans-serif;
    font-size: 0.85rem; font-weight: 700;
    padding: 0.6rem 1.5rem;
    border-radius: 8px;
    z-index: 700;
    opacity: 0;
    transition: all 0.4s ease;
    pointer-events: none;
    box-shadow: 0 4px 20px rgba(255,170,0,0.4);
    letter-spacing: 0.05em;
    white-space: nowrap;
}
.pista-penalizacion-aviso.show { opacity: 1; transform: translateX(-50%) translateY(0); }

@media (max-width: 600px) {
    .pista-btn { width: 58px; height: 58px; bottom: 1rem; right: 1rem; }
    .pista-tooltip { bottom: 5rem; right: 0.8rem; font-size: 0.65rem; }
    .pista-bocadillo { width: 95%; max-width: 350px; }
    .pista-bocadillo-texto { font-size: clamp(0.75rem, 3vw, 0.95rem); top: 18%; left: 15%; right: 15%; bottom: 35%; padding: 1rem; }
    .pista-penalizacion-aviso { font-size: 0.75rem; padding: 0.5rem 1rem; white-space: normal; text-align: center; }
}
</style>

<button class="pista-btn {{ $usada ? 'usada' : '' }}" id="pistaBtn" title="Pedir pista">
    <img src="/images/pistas/{{ $imagenCara }}" alt="Ayuda">
</button>
<div class="pista-tooltip" id="pistaTooltip">{{ $usada ? '📖 Ver pista' : '❓ ¿Necesitas ayuda?' }}</div>

<div class="pista-overlay" id="pistaOverlay">
    <div class="pista-bocadillo" id="pistaBocadillo">
        <img src="/images/pistas/bocadillo.png" alt="Bocadillo" class="pista-bocadillo-bg">
        <div class="pista-bocadillo-texto" id="pistaTexto">Cargando pista...</div>
        <button class="pista-close" id="pistaClose" title="Cerrar">✕</button>
    </div>
</div>

<div class="pista-penalizacion-aviso" id="pistaPenalizacion">⚠️ Penalización: +1 minuto | ¡Cuidado! Las pistas restan tiempo</div>

<script>
(function() {
    const pruebaNumero = {{ $pruebaNum }};
    let pistaYaUsada = {{ $usada ? 'true' : 'false' }};
    let pistaTextoCache = null;
    let fetchEnCurso = false;

    const btn = document.getElementById('pistaBtn');
    const overlay = document.getElementById('pistaOverlay');
    const textoEl = document.getElementById('pistaTexto');
    const closeBtn = document.getElementById('pistaClose');
    const avisoEl = document.getElementById('pistaPenalizacion');
    const tooltipEl = document.getElementById('pistaTooltip');

    if (!btn || !overlay) return; // Seguridad: si no existen los elementos, no hacer nada

    function abrirBocadillo(texto) {
        textoEl.textContent = texto;
        overlay.classList.add('visible');
    }

    function cerrarBocadillo() {
        overlay.classList.remove('visible');
    }

    function mostrarPenalizacion() {
        avisoEl.classList.add('show');
        setTimeout(function() { avisoEl.classList.remove('show'); }, 3500);
    }

    function marcarComoUsada() {
        pistaYaUsada = true;
        btn.classList.add('usada');
        if (tooltipEl) tooltipEl.textContent = '📖 Ver pista';
    }

    // Hover tooltip
    btn.addEventListener('mouseenter', function() {
        if (tooltipEl) { tooltipEl.style.opacity = '1'; tooltipEl.style.transform = 'translateY(0)'; }
    });
    btn.addEventListener('mouseleave', function() {
        if (tooltipEl) { tooltipEl.style.opacity = '0'; tooltipEl.style.transform = 'translateY(5px)'; }
    });

    btn.addEventListener('click', function() {
        // Reproducir sonido del botón de pista
        if (typeof audioManager !== 'undefined') {
          audioManager.playSFX('hint');
        }

        // Si ya está abierto, cerrar
        if (overlay.classList.contains('visible')) { cerrarBocadillo(); return; }

        // Si ya se usó y tenemos el texto en cache, mostrar directamente
        if (pistaYaUsada && pistaTextoCache) { abrirBocadillo(pistaTextoCache); return; }

        // Evitar doble fetch
        if (fetchEnCurso) return;
        fetchEnCurso = true;

        var csrfMeta = document.querySelector('meta[name="csrf-token"]');
        if (!csrfMeta) {
            console.error('CSRF token no encontrado. Asegúrate de tener <meta name="csrf-token"> en el <head>.');
            fetchEnCurso = false;
            return;
        }

        btn.style.pointerEvents = 'none';
        btn.style.opacity = '0.6';

        fetch('/pista', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfMeta.getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ prueba_numero: pruebaNumero })
        })
        .then(function(res) {
            if (!res.ok) throw new Error('HTTP ' + res.status);
            return res.json();
        })
        .then(function(data) {
            btn.style.pointerEvents = '';
            btn.style.opacity = '';
            fetchEnCurso = false;

            if (!data.success) {
                console.error('Error pista:', data.message);
                return;
            }

            pistaTextoCache = data.texto;
            abrirBocadillo(data.texto);

            if (!data.ya_usada && data.penalizacion > 0) {
                mostrarPenalizacion();
                marcarComoUsada();
                // Actualizar timer local si existe
                if (typeof tiempoInicial !== 'undefined') {
                    tiempoInicial += data.penalizacion;
                }
                // Mostrar advertencia sobre uso de pistas
                var avisoDiv = document.getElementById('pistaPenalizacion');
                if (avisoDiv) {
                    var minutosActuales = Math.floor(data.penalizacion / 60);
                    avisoDiv.textContent = '⚠️ +' + minutosActuales + ' minutos de penalización | ¡Cuidado! Las pistas restan mucho tiempo';
                }
            } else {
                marcarComoUsada();
            }
        })
        .catch(function(err) {
            btn.style.pointerEvents = '';
            btn.style.opacity = '';
            fetchEnCurso = false;
            console.error('Error al solicitar pista:', err);
        });
    });

    closeBtn.addEventListener('click', function(e) { e.stopPropagation(); cerrarBocadillo(); });
    overlay.addEventListener('click', function(e) { if (e.target === overlay) cerrarBocadillo(); });
    document.addEventListener('keydown', function(e) { if (e.key === 'Escape' && overlay.classList.contains('visible')) cerrarBocadillo(); });
})();
</script>
