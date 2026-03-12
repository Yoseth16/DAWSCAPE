# 🎵 Sistema de Audio - Quick Start

Guía rápida para integrar audio en las pruebas de DAWSCAPE.

## ⚡ 30 segundos - Inicio Rápido

**Paso 1**: Agregar al inicio de tu vista Blade (después de `@extends`):

```blade
@include('components.game-audio-setup', ['trackKey' => 'prueba1'])
```

**Listo.** Ya tienes:
- 🎶 Música de fondo con fade-in suave
- 🎚️ Mini reproductor en esquina inferior izquierda  
- 🔊 Efectos de sonido listos para usar

---

## 📝 Ejemplos Comunes

### 1. Reproducir Sonido en Validación Correcta

```blade
<button @click="
  validarRespuesta().then(result => {
    if(result.success) {
      triggerValidationSuccess();
      // Pasar a siguiente prueba
    }
  })
">Validar</button>
```

O en JavaScript vanilla:

```javascript
document.getElementById('btnValidar').addEventListener('click', async () => {
  const isCorrect = await validateAnswer();
  if (isCorrect) {
    triggerValidationSuccess(); // ✨ Sonido + evento
  } else {
    triggerValidationError();   // 💥 Sonido de error
  }
});
```

### 2. Cambiar Música en Mitad de la Prueba

```javascript
// En caso de "boss fight" o fase especial
audioManager.playMusic('prueba2', 1500); // Fade in de 1.5s
```

### 3. El Botón de Pista ya Reproduce Sonido

```blade
@include('components.pista-ayuda', [
  'pruebaNumero' => 1,
  'pistaConfig' => $pistaConfig,
  'yaUsada' => $yaUsada
])
```

Ya está integrado automáticamente. ✅

### 4. Control Manual de Volumen

```javascript
audioManager.setVolume(0.8);  // 0-1
audioManager.increaseVolume(0.1);
audioManager.decreaseVolume(0.1);
audioManager.pauseMusic();
audioManager.resumeMusic();
```

---

## 🎛️ Tracks Disponibles

Usa el `trackKey` apropiado en `game-audio-setup`:

```blade
<!-- Para Prueba 1 -->
@include('components.game-audio-setup', ['trackKey' => 'prueba1'])

<!-- Para Prueba 2 -->
@include('components.game-audio-setup', ['trackKey' => 'prueba2'])

<!-- Para pantalla de login -->
@include('components.game-audio-setup', ['trackKey' => 'login'])

<!-- Pantalla final -->
@include('components.game-audio-setup', ['trackKey' => 'final'])
```

Tracks: `login`, `prueba1` - `prueba10`, `final`

---

## 🔊 Efectos de Sonido

Ya están accesibles automáticamente:

```javascript
audioManager.playSFX('correct'); // Acierto.mp3
audioManager.playSFX('error');   // Error.mp3
audioManager.playSFX('hint');    // Boton-Pista.mp3
```

O usa los helpers (más cómodo):

```javascript
triggerValidationSuccess();  // Mismo que playSFX('correct')
triggerValidationError();    // Mismo que playSFX('error')
```

---

## 📱 Mini Reproductor

Se incluye automáticamente. Características:

- **🔉 Volumen**: Subir/bajar con botones o slider
- **▶/⏸ Play/Pause**: Control de reproducción
- **Título**: Muestra la canción actual
- **Responsive**: Funciona en mobile/tablet
- **Atajos**: Espacio = Play/Pause, Ctrl+↑↓ = Volumen

---

## 🚀 Integración Completa (Ejemplo Real)

```blade
<!-- resources/views/pruebas/prueba1.blade.php -->

@extends('layouts.app')

@section('content')
  @include('components.game-audio-setup', ['trackKey' => 'prueba1'])

  <div class="game-container">
    <h1>Acertijo 1</h1>
    <input type="text" id="answer" placeholder="Tu respuesta">
    
    <button onclick="validateAnswer()">
      Validar Respuesta
    </button>

    @include('components.pista-ayuda', [
      'pruebaNumero' => 1,
      'pistaConfig' => $pistaConfig,
      'yaUsada' => $yaUsada
    ])
  </div>

  @push('scripts')
    <script>
      function validateAnswer() {
        const answer = document.getElementById('answer').value;
        const isCorrect = answer.toLowerCase() === 'respuesta_correcta';

        // Reproducir sonido
        if (isCorrect) {
          triggerValidationSuccess();
          setTimeout(() => window.location = '/prueba2', 1000);
        } else {
          triggerValidationError();
        }
      }
    </script>
  @endpush
@endsection
```

---

## 🧪 Testing en Consola

Abre DevTools (F12) en la consola y prueba:

```javascript
// Reproducir música
audioManager.playMusic('prueba1');

// Cambiar música
audioManager.playMusic('prueba2');

// Efectos
audioManager.playSFX('correct');
audioManager.playSFX('error');
audioManager.playSFX('hint');

// Helpers
triggerValidationSuccess();
triggerValidationError();

// Volumen
audioManager.setVolume(0.6);

// Info actual
console.log(audioManager.getCurrentTrackInfo());
console.log(audioManager.isPlaying);
```

---

## ⚠️ Problemas Comunes

### No se escucha nada

1. Verifica que los archivos están en `public/sonido/`
2. Abre ConsOLA (F12) y busca errores
3. Prueba: `console.log(audioManager)` debe mostrar un objeto

### Mini reproductor no se ve

1. ¿Incluiste `@include('components.game-audio-setup', ...)`?
2. Verifica que `music-player.blade.php` existe en `resources/views/components/`

### SFX no suena pero la música sí

1. Verifica volumen de SFX vs música (pueden ser diferentes)
2. Intenta directamente: `audioManager.playSFX('correct')`

---

## 📚 Documentación Completa

Lee [AUDIO_SYSTEM_DOCUMENTATION.md](./AUDIO_SYSTEM_DOCUMENTATION.md) para:
- API detallada
- Configuración avanzada
- Troubleshooting exhaustivo
- Ejemplos adicionales

---

## 🎯 Checklist de Integración

- [ ] Agregar `@include('components.game-audio-setup', ['trackKey' => 'prueba1'])` en la vista
- [ ] Compilar con `npm run dev` o `npm run build`
- [ ] Probar en navegador (F12 para consola)
- [ ] Integrar SFX en botones de validación
- [ ] Probar en móvil

¡Listo! 🎉

---

*Sistema de Audio - DAWSCAPE 2026*
