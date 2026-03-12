<!-- Mini Music Player - Esquina Inferior Izquierda -->
<div id="music-player" class="music-player">
  <!-- Background gradient -->
  <div class="player-background"></div>
  
  <!-- Container principal -->
  <div class="player-container">
    <!-- Título de la canción -->
    <div class="track-title">
      <div class="title-text" id="trackTitle">DAWSCAPE - Tema Principal</div>
    </div>

    <!-- Controles -->
    <div class="player-controls">
      <!-- Volumen -->
      <div class="control-group volume-control">
        <button id="volumeDown" class="control-btn" title="Bajar volumen">
          <span>🔉</span>
        </button>
        <input 
          type="range" 
          id="volumeSlider" 
          class="volume-slider"
          min="0" 
          max="100" 
          value="50"
          title="Ajustar volumen"
        >
        <button id="volumeUp" class="control-btn" title="Subir volumen">
          <span>🔊</span>
        </button>
      </div>

      <!-- Play/Pause -->
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

<style>
  :root {
    --music-player-primary: #00ff88;
    --music-player-bg: rgba(2, 12, 6, 0.85);
    --music-player-border: #00ff8840;
    --music-player-text: #c8ffdf;
    --music-player-panel: #041a0e;
  }

  .music-player {
    position: fixed;
    bottom: 1rem;
    left: 1rem;
    z-index: 90;
    font-family: 'Share Tech Mono', monospace;
  }

  .player-background {
    position: absolute;
    inset: 0;
    background: var(--music-player-bg);
    border: 1px solid var(--music-player-border);
    border-radius: 8px;
    backdrop-filter: blur(10px);
    box-shadow: 0 4px 20px rgba(0, 255, 136, 0.1);
  }

  .player-container {
    position: relative;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    padding: 0.875rem;
    width: 280px;
    min-height: 100px;
  }

  /* Título de la canción */
  .track-title {
    overflow: hidden;
    height: 2.5rem;
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
    0% {
      transform: translateX(0);
    }
    20% {
      transform: translateX(0);
    }
    80% {
      transform: translateX(-100%);
    }
    100% {
      transform: translateX(-100%);
    }
  }

  /* Controles */
  .player-controls {
    display: flex;
    gap: 0.5rem;
    align-items: center;
    justify-content: space-between;
  }

  .control-group {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    flex: 1;
  }

  .volume-control {
    background: rgba(0, 255, 136, 0.05);
    padding: 0.4rem;
    border-radius: 4px;
    border: 1px solid rgba(0, 255, 136, 0.2);
  }

  .control-btn {
    background: none;
    border: none;
    color: var(--music-player-primary);
    cursor: pointer;
    font-size: 1rem;
    padding: 0.25rem;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .control-btn:hover {
    transform: scale(1.15);
    text-shadow: 0 0 10px rgba(0, 255, 136, 0.8);
  }

  .control-btn:active {
    transform: scale(0.95);
  }

  .volume-slider {
    width: 60px;
    height: 4px;
    cursor: pointer;
    accent-color: var(--music-player-primary);
    -webkit-appearance: none;
    appearance: none;
    background: rgba(0, 255, 136, 0.2);
    border-radius: 2px;
    outline: none;
  }

  .volume-slider::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: var(--music-player-primary);
    cursor: pointer;
    box-shadow: 0 0 8px rgba(0, 255, 136, 0.6);
    transition: all 0.2s ease;
  }

  .volume-slider::-webkit-slider-thumb:hover {
    box-shadow: 0 0 15px rgba(0, 255, 136, 0.9);
    transform: scale(1.2);
  }

  .volume-slider::-moz-range-thumb {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: var(--music-player-primary);
    border: none;
    cursor: pointer;
    box-shadow: 0 0 8px rgba(0, 255, 136, 0.6);
    transition: all 0.2s ease;
  }

  /* Play/Pause Button */
  .play-pause-btn {
    background: linear-gradient(135deg, rgba(0, 255, 136, 0.3), rgba(0, 255, 136, 0.1));
    border: 2px solid var(--music-player-primary);
    color: var(--music-player-primary);
    cursor: pointer;
    width: 50px;
    height: 50px;
    border-radius: 8px;
    font-size: 1.5rem;
    font-weight: bold;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 0 20px rgba(0, 255, 136, 0.3), inset 0 0 10px rgba(0, 255, 136, 0.1);
    line-height: 1;
  }

  .play-pause-btn:hover {
    background: linear-gradient(135deg, rgba(0, 255, 136, 0.5), rgba(0, 255, 136, 0.25));
    box-shadow: 0 0 30px rgba(0, 255, 136, 0.6), inset 0 0 15px rgba(0, 255, 136, 0.2);
    transform: scale(1.08);
  }

  .play-pause-btn:active {
    transform: scale(0.92);
    box-shadow: 0 0 15px rgba(0, 255, 136, 0.4), inset 0 0 8px rgba(0, 255, 136, 0.3);
  }

  /* Indicador de reproducción */
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
    0%, 100% {
      transform: scale(1);
      opacity: 0.8;
    }
    50% {
      transform: scale(1.5);
      opacity: 1;
    }
  }

  /* Responsive - Mobile */
  @media (max-width: 480px) {
    .music-player {
      bottom: 0.5rem;
      left: 0.5rem;
    }

    .player-container {
      width: 250px;
      padding: 0.75rem;
      gap: 0.6rem;
    }

    .track-title {
      height: 2rem;
    }

    .title-text {
      font-size: 0.7rem;
    }

    .volume-slider {
      width: 50px;
    }

    .play-pause-btn {
      width: 44px;
      height: 44px;
      font-size: 1.2rem;
    }
  }

  /* Tablet */
  @media (min-width: 768px) {
    .player-container {
      width: 300px;
    }

    .play-pause-btn {
      width: 55px;
      height: 55px;
      font-size: 1.8rem;
    }
  }

  /* Estados */
  .music-player.minimized {
    bottom: 1rem;
    left: 1rem;
    width: 50px;
  }

  .music-player.minimized .track-title,
  .music-player.minimized .player-controls {
    display: none;
  }

  .music-player.minimized .player-container {
    width: 50px;
    height: 50px;
    align-items: center;
    justify-content: center;
    padding: 0;
  }
</style>

<script>
  // Sistema de controles del reproductor de música
  (function() {
    const musicPlayer = document.getElementById('music-player');
    const playPauseBtn = document.getElementById('playPauseBtn');
    const volumeSlider = document.getElementById('volumeSlider');
    const volumeDown = document.getElementById('volumeDown');
    const volumeUp = document.getElementById('volumeUp');
    const trackTitle = document.getElementById('trackTitle');
    const indicatorDot = document.querySelector('.indicator-dot');

    // Event listener para cambios de estado de audio
    document.addEventListener('audioStateChanged', (e) => {
      const { isPlaying, trackInfo, volume } = e.detail;

      // Actualizar botón play/pause
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

      // Actualizar título
      if (trackInfo && trackInfo.title) {
        trackTitle.textContent = trackInfo.title;
      }

      // Actualizar volumen slider
      volumeSlider.value = Math.round(volume * 100);
    });

    // Play/Pause
    playPauseBtn.addEventListener('click', () => {
      if (window.audioManager) {
        window.audioManager.togglePlayPause();
      } else {
        console.warn('audioManager no disponible');
      }
    });

    // Volume slider
    volumeSlider.addEventListener('input', (e) => {
      if (window.audioManager) {
        const value = e.target.value / 100;
        window.audioManager.setVolume(value);
      }
    });

    // Volume buttons
    volumeDown.addEventListener('click', () => {
      if (window.audioManager) {
        window.audioManager.decreaseVolume(0.1);
      }
    });

    volumeUp.addEventListener('click', () => {
      if (window.audioManager) {
        window.audioManager.increaseVolume(0.1);
      }
    });

    // Keyboard shortcuts
    document.addEventListener('keydown', (e) => {
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
    });

    // Inicialización visual
    if (window.audioManager && window.audioManager.isPlaying) {
      const playIcon = playPauseBtn.querySelector('.icon-play');
      const pauseIcon = playPauseBtn.querySelector('.icon-pause');
      playIcon.style.display = 'none';
      pauseIcon.style.display = 'inline';
    }
  })();
</script>
