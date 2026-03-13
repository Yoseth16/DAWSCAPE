<!-- Mini Music Player - Esquina Superior Izquierda (Dropdown) -->
<div class="music-player-dropdown" id="music-player-wrapper">
  <!-- Botón Toggle (Nota musical) -->
  <button id="musicToggleBtn" class="music-toggle-btn" title="Reproductor de Música">
    🎵
  </button>

  <div id="music-player" class="music-player">
    <!-- Background gradient -->
    <div class="player-background"></div>

    <!-- Container principal -->
    <div class="player-container">
      <!-- Título de la canción -->
      <div class="track-title">
        <div class="title-text" id="trackTitle">DAWSCAPE - Tema Principal</div>
      </div>

      <!-- Controles de volumen -->
      <div class="volume-section">
        <button id="volumeMuteBtn" class="volume-icon-btn" title="Silenciar/Activar">
          <span id="volumeIcon">🔊</span>
        </button>
        <div class="volume-slider-wrap">
          <input
            type="range"
            id="volumeSlider"
            class="volume-slider"
            min="0"
            max="100"
            value="50"
            title="Ajustar volumen"
          >
        </div>
        <span class="volume-pct" id="volumePct">50%</span>
      </div>

      <!-- Controles de reproducción -->
      <div class="playback-controls">
        <button id="playPauseBtn" class="play-pause-btn" title="Pausa/Reproducción">
          <span class="icon-play">▶</span>
          <span class="icon-pause" style="display:none;">⏸</span>
        </button>
      </div>

      <!-- Indicador de reproducción -->
      <div class="player-indicator">
        <div class="indicator-dot"></div>
      </div>
    </div>
  </div>
</div>

<style>
  :root {
    --music-player-primary: #00ff88;
    --music-player-bg: rgba(2, 12, 6, 0.92);
    --music-player-border: #00ff8840;
    --music-player-text: #c8ffdf;
    --music-player-panel: #041a0e;
  }

  /* Wrapper y botón toggle */
  .music-player-dropdown {
    position: fixed;
    top: 4rem;
    left: 1rem;
    z-index: 900;
    font-family: 'Share Tech Mono', monospace;
  }

  .music-toggle-btn {
    background: var(--music-player-bg);
    border: 1px solid var(--music-player-border);
    border-radius: 50%;
    width: 44px;
    height: 44px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    color: var(--music-player-primary);
    box-shadow: 0 4px 15px rgba(0, 255, 136, 0.2);
    backdrop-filter: blur(5px);
    transition: all 0.3s ease;
    -webkit-tap-highlight-color: transparent;
  }

  .music-toggle-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 25px rgba(0, 255, 136, 0.4);
    border-color: var(--music-player-primary);
  }

  .music-toggle-btn.active {
    background: rgba(0, 255, 136, 0.15);
    border-color: var(--music-player-primary);
    box-shadow: 0 0 15px rgba(0, 255, 136, 0.5);
  }

  /* Panel del reproductor */
  .music-player {
    position: absolute;
    top: 3rem;
    left: 0;
    width: max-content;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: opacity 0.3s ease, transform 0.3s ease, visibility 0.3s ease;
    pointer-events: none;
  }

  .music-player.show {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
    pointer-events: auto;
  }

  .player-background {
    position: absolute;
    inset: 0;
    background: var(--music-player-bg);
    border: 1px solid var(--music-player-border);
    border-radius: 10px;
    backdrop-filter: blur(10px);
    box-shadow: 0 4px 24px rgba(0, 255, 136, 0.12);
  }

  .player-container {
    position: relative;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    padding: 1rem;
    width: 280px;
  }

  /* Título */
  .track-title {
    overflow: hidden;
    height: 1.6rem;
    display: flex;
    align-items: center;
  }

  .title-text {
    font-size: 0.75rem;
    color: var(--music-player-primary);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    font-weight: bold;
    text-shadow: 0 0 10px rgba(0, 255, 136, 0.5);
    animation: scrollTitle 10s linear infinite;
  }

  @keyframes scrollTitle {
    0%  { transform: translateX(0); }
    20% { transform: translateX(0); }
    80% { transform: translateX(-100%); }
    100%{ transform: translateX(-100%); }
  }

  /* ═══ Sección de volumen ═══ */
  .volume-section {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(0, 255, 136, 0.04);
    padding: 0.5rem 0.6rem;
    border-radius: 8px;
    border: 1px solid rgba(0, 255, 136, 0.15);
  }

  .volume-icon-btn {
    background: none;
    border: none;
    color: var(--music-player-primary);
    cursor: pointer;
    font-size: 1.15rem;
    padding: 0.3rem;
    min-width: 32px;
    min-height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    border-radius: 6px;
    -webkit-tap-highlight-color: transparent;
  }

  .volume-icon-btn:hover {
    background: rgba(0, 255, 136, 0.1);
  }

  .volume-icon-btn:active {
    transform: scale(0.9);
  }

  .volume-slider-wrap {
    flex: 1;
    display: flex;
    align-items: center;
    position: relative;
    padding: 6px 0;
  }

  .volume-slider {
    width: 100%;
    height: 6px;
    cursor: pointer;
    -webkit-appearance: none;
    appearance: none;
    background: rgba(0, 255, 136, 0.15);
    border-radius: 3px;
    outline: none;
    transition: height 0.15s ease;
  }

  /* Filled track via JS-set CSS variable */
  .volume-slider {
    background: linear-gradient(
      to right,
      var(--music-player-primary) 0%,
      var(--music-player-primary) var(--vol-pct, 50%),
      rgba(0, 255, 136, 0.15) var(--vol-pct, 50%),
      rgba(0, 255, 136, 0.15) 100%
    );
  }

  .volume-slider::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: var(--music-player-primary);
    cursor: pointer;
    box-shadow: 0 0 10px rgba(0, 255, 136, 0.6);
    transition: box-shadow 0.2s ease, transform 0.15s ease;
    border: 2px solid #041a0e;
  }

  .volume-slider::-webkit-slider-thumb:hover {
    box-shadow: 0 0 18px rgba(0, 255, 136, 0.9);
    transform: scale(1.15);
  }

  .volume-slider::-moz-range-thumb {
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: var(--music-player-primary);
    border: 2px solid #041a0e;
    cursor: pointer;
    box-shadow: 0 0 10px rgba(0, 255, 136, 0.6);
  }

  .volume-slider::-moz-range-track {
    height: 6px;
    background: transparent;
    border: none;
  }

  .volume-pct {
    font-size: 0.7rem;
    color: var(--music-player-primary);
    min-width: 2.2rem;
    text-align: right;
    font-weight: bold;
    opacity: 0.8;
    user-select: none;
  }

  /* ═══ Controles de reproducción ═══ */
  .playback-controls {
    display: flex;
    justify-content: center;
  }

  .play-pause-btn {
    background: linear-gradient(135deg, rgba(0, 255, 136, 0.3), rgba(0, 255, 136, 0.1));
    border: 2px solid var(--music-player-primary);
    color: var(--music-player-primary);
    cursor: pointer;
    width: 48px;
    height: 48px;
    border-radius: 8px;
    font-size: 1.4rem;
    font-weight: bold;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 0 20px rgba(0, 255, 136, 0.3), inset 0 0 10px rgba(0, 255, 136, 0.1);
    line-height: 1;
    -webkit-tap-highlight-color: transparent;
  }

  .play-pause-btn:hover {
    background: linear-gradient(135deg, rgba(0, 255, 136, 0.5), rgba(0, 255, 136, 0.25));
    box-shadow: 0 0 30px rgba(0, 255, 136, 0.6), inset 0 0 15px rgba(0, 255, 136, 0.2);
    transform: scale(1.08);
  }

  .play-pause-btn:active {
    transform: scale(0.92);
  }

  /* Indicador */
  .player-indicator {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 6px;
  }

  .indicator-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: var(--music-player-primary);
    animation: pulse 1s ease-in-out infinite;
    opacity: 0;
    transition: opacity 0.3s ease;
  }

  .indicator-dot.playing {
    opacity: 1;
  }

  @keyframes pulse {
    0%, 100% { transform: scale(1); opacity: 0.8; }
    50% { transform: scale(1.5); opacity: 1; }
  }

  /* ═══ Mobile ═══ */
  @media (max-width: 480px) {
    .music-player-dropdown {
      top: 3.5rem;
      left: 0.5rem;
    }

    .player-container {
      width: 260px;
      padding: 0.85rem;
      gap: 0.6rem;
    }

    .volume-slider::-webkit-slider-thumb {
      width: 22px;
      height: 22px;
    }

    .volume-slider::-moz-range-thumb {
      width: 22px;
      height: 22px;
    }

    .volume-slider {
      height: 8px;
    }

    .play-pause-btn {
      width: 44px;
      height: 44px;
      font-size: 1.2rem;
    }
  }

  /* Tablet+ */
  @media (min-width: 768px) {
    .player-container {
      width: 300px;
    }

    .play-pause-btn {
      width: 52px;
      height: 52px;
      font-size: 1.6rem;
    }
  }
</style>

<script>
  (function() {
    const musicPlayer = document.getElementById('music-player');
    const playPauseBtn = document.getElementById('playPauseBtn');
    const volumeSlider = document.getElementById('volumeSlider');
    const volumeMuteBtn = document.getElementById('volumeMuteBtn');
    const volumeIcon = document.getElementById('volumeIcon');
    const volumePct = document.getElementById('volumePct');
    const trackTitle = document.getElementById('trackTitle');
    const indicatorDot = document.querySelector('.indicator-dot');
    const toggleBtn = document.getElementById('musicToggleBtn');

    let previousVolume = 0.5; // Para restaurar al desmutear

    /**
     * Actualiza el icono de volumen según el nivel
     */
    function updateVolumeIcon(vol) {
      if (vol <= 0) {
        volumeIcon.textContent = '🔇';
      } else if (vol < 0.35) {
        volumeIcon.textContent = '🔈';
      } else if (vol < 0.7) {
        volumeIcon.textContent = '🔉';
      } else {
        volumeIcon.textContent = '🔊';
      }
    }

    /**
     * Actualiza la barra de relleno del slider
     */
    function updateSliderFill(pct) {
      volumeSlider.style.setProperty('--vol-pct', pct + '%');
    }

    /**
     * Sincroniza todos los controles visuales con un valor de volumen
     */
    function syncVolumeUI(vol) {
      const pct = Math.round(vol * 100);
      volumeSlider.value = pct;
      volumePct.textContent = pct + '%';
      updateVolumeIcon(vol);
      updateSliderFill(pct);
    }

    // Estado de audio → UI
    document.addEventListener('audioStateChanged', function(e) {
      const { isPlaying, trackInfo, volume } = e.detail;

      // Play/Pause
      const playIcon = playPauseBtn.querySelector('.icon-play');
      const pauseIcon = playPauseBtn.querySelector('.icon-pause');

      if (isPlaying) {
        playIcon.style.display = 'none';
        pauseIcon.style.display = 'inline';
        indicatorDot.classList.add('playing');
      } else {
        playIcon.style.display = 'inline';
        pauseIcon.style.display = 'none';
        indicatorDot.classList.remove('playing');
      }

      // Título
      if (trackInfo && trackInfo.title) {
        trackTitle.textContent = trackInfo.title;
      }

      // Volumen
      syncVolumeUI(volume);
    });

    // Play/Pause
    playPauseBtn.addEventListener('click', function() {
      if (window.audioManager) {
        window.audioManager.togglePlayPause();
      }
    });

    // Volume slider — input para respuesta en tiempo real
    volumeSlider.addEventListener('input', function(e) {
      if (window.audioManager) {
        var val = parseInt(e.target.value, 10) / 100;
        window.audioManager.setVolume(val);
        previousVolume = val > 0 ? val : previousVolume;
      }
    });

    // Soporte táctil explícito para slider en móvil
    volumeSlider.addEventListener('touchstart', function(e) {
      e.stopPropagation(); // Evitar que el contenedor capture el toque
    }, { passive: true });

    // Mute/Unmute
    volumeMuteBtn.addEventListener('click', function() {
      if (!window.audioManager) return;

      if (window.audioManager.currentVolume > 0) {
        previousVolume = window.audioManager.currentVolume;
        window.audioManager.setVolume(0);
      } else {
        window.audioManager.setVolume(previousVolume || 0.5);
      }
    });

    // Atajos de teclado
    document.addEventListener('keydown', function(e) {
      if (e.code === 'Space' && e.target === document.body) {
        e.preventDefault();
        if (window.audioManager) window.audioManager.togglePlayPause();
      }
      if (e.code === 'ArrowUp' && e.ctrlKey) {
        e.preventDefault();
        if (window.audioManager) window.audioManager.increaseVolume(0.05);
      }
      if (e.code === 'ArrowDown' && e.ctrlKey) {
        e.preventDefault();
        if (window.audioManager) window.audioManager.decreaseVolume(0.05);
      }
      if (e.code === 'KeyM' && e.ctrlKey) {
        e.preventDefault();
        volumeMuteBtn.click();
      }
    });

    // Toggle panel
    toggleBtn.addEventListener('click', function(e) {
      musicPlayer.classList.toggle('show');
      toggleBtn.classList.toggle('active');
      e.stopPropagation();
    });

    // Cerrar si se clica fuera del panel
    document.addEventListener('click', function(e) {
      if (!musicPlayer.contains(e.target) && e.target !== toggleBtn) {
        musicPlayer.classList.remove('show');
        toggleBtn.classList.remove('active');
      }
    });

    // Inicialización: sincronizar UI con el estado actual del AudioManager
    function initPlayerUI() {
      if (window.audioManager) {
        syncVolumeUI(window.audioManager.currentVolume);
        previousVolume = window.audioManager.targetVolume || 0.5;

        if (window.audioManager.isPlaying) {
          var playIcon = playPauseBtn.querySelector('.icon-play');
          var pauseIcon = playPauseBtn.querySelector('.icon-pause');
          playIcon.style.display = 'none';
          pauseIcon.style.display = 'inline';
          indicatorDot.classList.add('playing');
        }
      }
    }

    // Ejecutar init cuando el audioManager esté listo
    if (window.audioManager) {
      initPlayerUI();
    } else {
      document.addEventListener('audioManagerReady', initPlayerUI, { once: true });
    }
  })();
</script>
