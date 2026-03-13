/**
 * AudioManager - Sistema completo de gestión de audio
 * Maneja música de fondo, efectos de sonido y fade in/out
 *
 * Usa window.AUDIO_BASE_URL (inyectado por Blade vía asset()) para
 * construir URLs correctas independientemente del subpath del servidor.
 */
class AudioManager {
  constructor() {
    // Música de fondo
    this.bgMusic = new Audio();
    this.bgMusic.crossOrigin = 'anonymous';
    this.currentTrach = null;
    this.isPlaying = false;
    this.isFading = false;
    this.currentVolume = 0.5;
    this.targetVolume = 0.5;

    // Efectos de sonido
    this.sfx = {};
    this.sfxVolume = 0.7;

    // Base URL resuelta por Laravel asset() desde el layout.
    // Fallback a '/sonido' si la variable no fue inyectada.
    this.baseUrl = (window.AUDIO_BASE_URL || '/sonido').replace(/\/$/, '');

    // Configuración de pistas (rutas relativas a baseUrl)
    this.tracks = {
      login: {
        file: 'MusicaMenuLogin.mp3',
        title: 'DAWSCAPE - Tema Principal',
      },
      prueba1: {
        file: 'Prueba1-suspiro.mp3',
        title: 'Suspiro 8 Bits - Sergio Mouliaá',
      },
      prueba2: {
        file: 'Prueba2-zasi.mp3',
        title: 'X TUS BESOS 8 Bits - Zasi',
      },
      prueba3: {
        file: 'Prueba3-farsante.mp3',
        title: 'El Farsante 8 Bits - Ozuna',
      },
      prueba4: {
        file: 'Prueba4-Arturo.mp3',
        title: 'Pluma Pluma G.. 8 Bits - Morancos',
      },
      prueba5: {
        file: 'Prueba5-delocos.mp3',
        title: 'De Locos 8 Bits - Sergio Mouliaá',
      },
      prueba6: {
        file: 'Prueba6-fernanfloo.mp3',
        title: 'Rap de Fernanfloo 8 Bits - Bambiel',
      },
      prueba7: {
        file: 'Prueba7-NachoRPG.mp3',
        title: 'Tokyo Drift 8 Bits - Nacho',
      },
      prueba8: {
        file: 'Prueba8-mandanga.mp3',
        title: 'Mandanga Style 8 Bits - Amador',
      },
      prueba9: {
        file: 'Prueba9-MineroRPG.mp3',
        title: 'Minero 8 Bits - ElRubiusOMG',
      },
      prueba10: {
        file: 'Prueba10-DanzaKuduroRPG.mp3',
        title: 'Danza Kuduro 8 Bits - Fernando',
      },
      final: {
        file: 'FinalVictoria.mp3',
        title: 'We Are The Champions 8 Bits',
      },
    };

    // SFX (rutas relativas a baseUrl)
    this.sfxFiles = {
      correct: 'Acierto.mp3',
      error: 'Error.mp3',
      hint: 'Boton-Pista.mp3',
      speaking: 'hablando-pista.mp3',
    };

    this.initializeSFX();
    this.setupEventListeners();
  }

  /**
   * Construye la URL completa de un archivo de audio.
   * @param {string} filename - Nombre del archivo (ej: 'Acierto.mp3')
   * @returns {string}
   */
  _url(filename) {
    return this.baseUrl + '/' + filename;
  }

  /**
   * Inicializar efectos de sonido
   */
  initializeSFX() {
    Object.keys(this.sfxFiles).forEach((key) => {
      this.sfx[key] = new Audio(this._url(this.sfxFiles[key]));
      this.sfx[key].crossOrigin = 'anonymous';
      this.sfx[key].volume = this.sfxVolume;
    });
  }

  /**
   * Setup listeners para eventos globales
   */
  setupEventListeners() {
    // Pausa audio cuando se cambia de pestaña
    document.addEventListener('visibilitychange', () => {
      if (document.hidden) {
        this.pauseMusic();
      }
    });
  }

  /**
   * Reproducir pista musical con fade in
   * @param {string} trackKey - Clave de la pista (ej: 'prueba1')
   * @param {number} fadeInDuration - Duración del fade in en ms (default 2000)
   */
  playMusic(trackKey, fadeInDuration = 2000) {
    const track = this.tracks[trackKey];

    if (!track) {
      console.warn(`Track '${trackKey}' no encontrado`);
      return;
    }

    // Si es la misma canción y está sonando, no hacer nada
    if (this.currentTrach === trackKey && this.isPlaying) {
      return;
    }

    // Si hay música sonando, hacer fade out primero
    if (this.isPlaying) {
      this.fadeOut(1000).then(() => {
        this._loadAndPlayTrack(trackKey, fadeInDuration);
      });
    } else {
      this._loadAndPlayTrack(trackKey, fadeInDuration);
    }
  }

  /**
   * Método interno para cargar y reproducir la pista
   */
  _loadAndPlayTrack(trackKey, fadeInDuration) {
    const track = this.tracks[trackKey];
    const url = this._url(track.file);

    console.log(`🎵 Cargando audio: ${url}`);

    this.bgMusic.src = url;
    this.bgMusic.volume = 0;
    this.currentVolume = 0;
    this.targetVolume = 0.5;
    this.currentTrach = trackKey;

    this.bgMusic
      .play()
      .then(() => {
        this.isPlaying = true;
        this._fadeInMusic(fadeInDuration);
        this._updateUI();
      })
      .catch((err) => {
        console.error('Error reproduciendo música:', err);
        // El navegador bloqueó el autoplay; se activará en el primer clic
        this._updateUI();
      });
  }

  /**
   * Fade in interno
   */
  _fadeInMusic(duration) {
    this.isFading = true;
    const startTime = Date.now();
    const step = 50;

    const fadeInterval = setInterval(() => {
      const elapsed = Date.now() - startTime;
      const progress = Math.min(elapsed / duration, 1);

      this.currentVolume = 0.5 * progress;
      this.bgMusic.volume = this.currentVolume;

      if (progress >= 1) {
        clearInterval(fadeInterval);
        this.isFading = false;
        this.currentVolume = 0.5;
        this.bgMusic.volume = 0.5;
      }
    }, step);
  }

  /**
   * Hacer fade out de la música actual
   * @param {number} duration - Duración en ms
   * @returns {Promise}
   */
  fadeOut(duration = 2000) {
    return new Promise((resolve) => {
      if (!this.isPlaying) {
        resolve();
        return;
      }

      this.isFading = true;
      const startTime = Date.now();
      const startVolume = this.currentVolume;

      const fadeInterval = setInterval(() => {
        const elapsed = Date.now() - startTime;
        const progress = Math.min(elapsed / duration, 1);

        this.currentVolume = startVolume * (1 - progress);
        this.bgMusic.volume = this.currentVolume;

        if (progress >= 1) {
          clearInterval(fadeInterval);
          this.bgMusic.pause();
          this.bgMusic.currentTime = 0;
          this.isPlaying = false;
          this.isFading = false;
          this._updateUI();
          resolve();
        }
      }, 50);
    });
  }

  /**
   * Pausar música
   */
  pauseMusic() {
    if (this.isPlaying) {
      this.bgMusic.pause();
      this.isPlaying = false;
      this._updateUI();
    }
  }

  /**
   * Reanudar música
   */
  resumeMusic() {
    if (!this.isPlaying && this.currentTrach) {
      this.bgMusic
        .play()
        .then(() => {
          this.isPlaying = true;
          this._updateUI();
        })
        .catch((err) => {
          console.error('Error reanudando música:', err);
        });
    }
  }

  /**
   * Toggle pausa/reproducción
   */
  togglePlayPause() {
    if (this.isPlaying) {
      this.pauseMusic();
    } else {
      this.resumeMusic();
    }
  }

  /**
   * Establecer volumen de música (0-1)
   * @param {number} value
   */
  setVolume(value) {
    // ⚠ Corrección: Math.max (no Math.Max)
    const vol = Math.max(0, Math.min(1, value));
    this.targetVolume = vol;
    this.bgMusic.volume = vol;
    this.currentVolume = vol;
    localStorage.setItem('audioVolume', vol);
    this._updateUI();
  }

  /**
   * Aumentar volumen
   * @param {number} step
   */
  increaseVolume(step = 0.1) {
    this.setVolume(this.targetVolume + step);
  }

  /**
   * Disminuir volumen
   * @param {number} step
   */
  decreaseVolume(step = 0.1) {
    this.setVolume(this.targetVolume - step);
  }

  /**
   * Reproducir efecto de sonido
   * @param {string} sfxKey
   */
  playSFX(sfxKey) {
    if (!this.sfx[sfxKey]) {
      console.warn(`SFX '${sfxKey}' no encontrado`);
      return;
    }

    try {
      this.sfx[sfxKey].currentTime = 0;
      this.sfx[sfxKey].play().catch((err) => {
        console.error(`Error reproduciendo SFX '${sfxKey}':`, err);
      });
    } catch (err) {
      console.error('Error con SFX:', err);
    }
  }

  /**
   * Obtener información de la pista actual
   * @returns {object|null}
   */
  getCurrentTrackInfo() {
    if (!this.currentTrach) return null;
    return this.tracks[this.currentTrach];
  }

  /**
   * Actualizar UI del reproductor
   */
  _updateUI() {
    const event = new CustomEvent('audioStateChanged', {
      detail: {
        isPlaying: this.isPlaying,
        track: this.currentTrach,
        volume: this.currentVolume,
        trackInfo: this.getCurrentTrackInfo(),
      },
    });
    document.dispatchEvent(event);
  }

  /**
   * Restaurar configuración guardada
   */
  restoreSettings() {
    const savedVolume = localStorage.getItem('audioVolume');
    if (savedVolume) {
      this.setVolume(parseFloat(savedVolume));
    }
  }
}

// Instancia global
const audioManager = new AudioManager();
audioManager.restoreSettings();

// Hacer disponible globalmente en window
window.audioManager = audioManager;

// Notificar a los componentes Blade que ya pueden usar audioManager
document.dispatchEvent(new Event('audioManagerReady'));
