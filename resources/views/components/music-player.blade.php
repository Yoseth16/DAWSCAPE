<!-- Botón de sonido minimalista (mute/unmute) -->
<button id="musicMuteBtn" class="music-mute-btn" type="button" title="Silenciar / Activar sonido">
  <span id="musicMuteIcon">🔊</span>
</button>

<style>
  :root {
    --music-player-primary: #00ff88;
    --music-player-bg: rgba(2, 12, 6, 0.92);
    --music-player-border: #00ff8840;
  }

  /* Botón flotante, pequeño y discreto en esquina inferior izquierda */
  .music-mute-btn {
    position: fixed;
    bottom: 0.75rem;
    left: 0.75rem;
    z-index: 900;
    width: 38px;
    height: 38px;
    border-radius: 999px;
    border: 1px solid var(--music-player-border);
    background: rgba(2, 12, 6, 0.9);
    color: var(--music-player-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.15rem;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(0, 255, 136, 0.2);
    backdrop-filter: blur(6px);
    -webkit-tap-highlight-color: transparent;
    transition:
      transform 0.18s ease,
      box-shadow 0.18s ease,
      border-color 0.18s ease,
      background-color 0.18s ease,
      opacity 0.18s ease;
    opacity: 0.9;
  }

  .music-mute-btn:hover {
    transform: translateY(-1px) scale(1.04);
    box-shadow: 0 6px 18px rgba(0, 255, 136, 0.35);
    border-color: var(--music-player-primary);
    opacity: 1;
  }

  .music-mute-btn:active {
    transform: translateY(0) scale(0.96);
    box-shadow: 0 2px 8px rgba(0, 255, 136, 0.2);
  }

  /* Mobile: ligeramente más pegado al borde y sin molestar al contenido */
  @media (max-width: 600px) {
    .music-mute-btn {
      bottom: 0.6rem;
      left: 0.6rem;
      width: 34px;
      height: 34px;
      font-size: 1rem;
      box-shadow: 0 3px 10px rgba(0, 255, 136, 0.25);
    }
  }
</style>

<script>
  (function() {
    const muteBtn = document.getElementById('musicMuteBtn');
    const muteIcon = document.getElementById('musicMuteIcon');

    if (!muteBtn || !muteIcon) return;

    let previousVolume = 0.5; // Volumen recordado al silenciar

    function updateIcon(vol) {
      if (vol <= 0.001) {
        muteIcon.textContent = '🔇';
      } else {
        muteIcon.textContent = '🔊';
      }
    }

    // Sincronizar icono con el estado global del AudioManager
    document.addEventListener('audioStateChanged', function(e) {
      if (!e.detail) return;
      const volume = typeof e.detail.volume === 'number' ? e.detail.volume : 0.5;

      if (volume > 0) {
        previousVolume = volume;
      }

      updateIcon(volume);
    });

    // Toggle mute/unmute manteniendo la música en reproducción
    muteBtn.addEventListener('click', function() {
      if (!window.audioManager) return;

      const currentVol = window.audioManager.currentVolume;

      if (currentVol > 0.001) {
        previousVolume = currentVol;
        window.audioManager.setVolume(0);
        updateIcon(0);
      } else {
        const restoreVol = previousVolume > 0 ? previousVolume : (window.audioManager.targetVolume || 0.5);
        window.audioManager.setVolume(restoreVol);
        updateIcon(restoreVol);
      }
    });

    // Inicializar icono cuando el AudioManager esté listo
    function initFromManager() {
      if (!window.audioManager) return;
      const vol = window.audioManager.currentVolume;
      previousVolume = window.audioManager.targetVolume || vol || 0.5;
      updateIcon(vol);
    }

    if (window.audioManager) {
      initFromManager();
    } else {
      document.addEventListener('audioManagerReady', initFromManager, { once: true });
    }
  })();
</script>
