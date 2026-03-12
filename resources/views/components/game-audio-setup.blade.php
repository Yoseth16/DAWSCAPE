{{-- Componente Game Audio Setup - Inicializa audio para las pruebas --}}
@props(['trackKey' => 'prueba1'])

<!-- Audio Setup -->
<div id="game-audio-setup" class="game-audio-setup" data-track="{{ $trackKey }}"></div>

@push('scripts')
  <script>
    // Esperar a que AudioManager esté disponible
    function initializeAudio() {
      const trackKey = document.querySelector('[data-track]')?.dataset.track || '{{ $trackKey }}';
      
      if (typeof audioManager !== 'undefined') {
        // Reproducir música con fade in de 2 segundos
        console.log('✓ AudioManager cargado. Reproduciendo:', trackKey);
        audioManager.playMusic(trackKey, 2000);
      } else {
        console.warn('⏳ AudioManager aún no disponible, esperando...');
        setTimeout(initializeAudio, 100);
      }
    }

    // Iniciar cuando el DOM esté listo
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', initializeAudio);
    } else {
      initializeAudio();
    }
  </script>
@endpush

<!-- Music Player Component -->
@include('components.music-player')
