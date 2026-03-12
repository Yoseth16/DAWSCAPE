{{-- Componente Game Audio Setup - Inicializa audio para las pruebas --}}
@props(['trackKey' => 'prueba1'])

<!-- Audio Setup -->
<div id="game-audio-setup" class="game-audio-setup" data-track="{{ $trackKey }}"></div>

@push('scripts')
  <!-- Audio Manager -->
  @vite('resources/js/audio-manager.js')
  
  <!-- SFX Integration -->
  @vite('resources/js/sfx-integration.js')
  
  <!-- Validation SFX Helper -->
  @vite('resources/js/validation-sfx-helper.js')
  
  <script>
    (function() {
      const trackKey = document.querySelector('[data-track]')?.dataset.track || '{{ $trackKey }}';
      
      // Esperar a que AudioManager esté disponible
      const waitForAudioManager = () => {
        if (typeof audioManager !== 'undefined') {
          // Iniciar música con fade in de 2 segundos
          audioManager.playMusic(trackKey, 2000);
          clearInterval(checkInterval);
        }
      };

      // Máximo 5 segundos esperando
      let attempts = 0;
      const checkInterval = setInterval(() => {
        attempts++;
        waitForAudioManager();
        if (attempts > 50) clearInterval(checkInterval);
      }, 100);

      // Intentar inmediatamente también
      waitForAudioManager();
    })();
  </script>
@endpush

<!-- Music Player Component -->
@include('components.music-player')
