/**
 * SFX Integration - Efectos de sonido para pruebas del juego
 * Maneja la reproducción automática de SFX en interacciones clave
 */
(function() {
  const sfxConfig = {
    // Selector de botón: clave de SFX
    '[onclick*="validar"]': 'check',  // Ajustar según el botón de validación
    '[data-sfx="correct"]': 'correct',
    '[data-sfx="error"]': 'error',
    '[data-sfx="hint"]': 'hint',
    '.pista-btn': 'hint',
  };

  /**
   * Adjuntar listeners de SFX a elementos
   */
  function initSFXButtons() {
    // Botón de pista
    const pistaBtn = document.getElementById('pistaBtn');
    if (pistaBtn) {
      pistaBtn.addEventListener('click', () => {
        audioManager.playSFX('hint');
      });
    }

    // Escuchar eventos de validación en el documento
    document.addEventListener('validation:correct', () => {
      audioManager.playSFX('correct');
    });

    document.addEventListener('validation:error', () => {
      audioManager.playSFX('error');
    });

    // Buscar elementos con data-sfx
    document.querySelectorAll('[data-sfx]').forEach(element => {
      element.addEventListener('click', (e) => {
        const sfxType = element.dataset.sfx;
        if (sfxType === 'check' && shouldPlayCheckSound(e)) {
          // El sonido se reproducirá según la validación (correct/error)
        } else if (sfxType === 'hint') {
          audioManager.playSFX('hint');
        }
      });
    });
  }

  /**
   * Determinar si reproducir sonido de validación
   */
  function shouldPlayCheckSound(event) {
    // Evitar sonidos accidentales
    return !event.target.disabled;
  }

  /**
   * Enviar evento de validación
   */
  window.triggerValidationSuccess = function() {
    document.dispatchEvent(new CustomEvent('validation:correct'));
  };

  window.triggerValidationError = function() {
    document.dispatchEvent(new CustomEvent('validation:error'));
  };

  // Inicializar cuando el DOM esté listo
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initSFXButtons);
  } else {
    initSFXButtons();
  }

  // Re-inicializar cuando se cargue dinámicamente contenido
  const observer = new MutationObserver(() => {
    initSFXButtons();
  });

  observer.observe(document.body, {
    childList: true,
    subtree: true,
    attributes: false
  });
})();
