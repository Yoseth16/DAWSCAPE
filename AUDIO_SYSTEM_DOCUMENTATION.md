# 🎵 Sistema de Audio Completo - Documentación de Integración

## Resumen

Sistema modular y escalable de audio/música para DAWSCAPE que incluye:

- 🎶 **Música de fondo** con fade in/out automático
- 🔊 **Efectos de sonido** para validaciones e interacciones  
- 🎚️ **Mini reproductor funcional** en esquina inferior izquierda
- 📱 **Responsive** para mobile y tablet
- ⌨️ **Atajos de teclado** para controlar el audio

---

## 🏗️ Estructura de Archivos

```
resources/
├── js/
│   ├── audio-manager.js          # Sistema central de gestión de audio
│   └── sfx-integration.js        # Integración de efectos de sonido
└── views/
    └── components/
        ├── music-player.blade.php    # Mini reproductor UI
        └── game-audio-setup.blade.php # Componente wrapper para pruebas

public/sonido/
├── MusicaMenuLogin.mp3          # Tema de login
├── Prueba1-suspiro.mp3           # Música Prueba 1
├── Prueba2-zasi.mp3              # Música Prueba 2
├── ... (Prueba 3-10)
├── FinalVictoria.mp3             # Tema final
├── Acierto.mp3                   # SFX: Validación correcta
├── Error.mp3                     # SFX: Validación incorrecta
└── Boton-Pista.mp3              # SFX: Click en botón de pista
```

---

## 🚀 Integración en Vistas de Prueba

### Uso Básico

En cualquier vista de prueba (Blade), simplemente agrega:

```blade
@include('components.game-audio-setup', ['trackKey' => 'prueba1'])
```

Dónde `trackKey` puede ser:
- `prueba1`, `prueba2`, ..., `prueba10`
- `login`, `final`

### Ejemplo Completo - Prueba 1

```blade
@extends('layouts.app')

@section('content')
  <!-- Incluir setup de audio -->
  @include('components.game-audio-setup', ['trackKey' => 'prueba1'])

  <!-- Contenido de la prueba -->
  <div class="game-container">
    <!-- Tu contenido aquí -->
  </div>
@endsection
```

---

## 🎛️ API del AudioManager

### Reproducir Música

```javascript
// Reproducir una pista con fade in de 2 segundos
audioManager.playMusic('prueba1', 2000);

// Cambiar a otra pista (automáticoade out + fade in)
audioManager.playMusic('prueba2', 2000);
```

### Control de Reproducción

```javascript
// Pausar
audioManager.pauseMusic();

// Reanudar
audioManager.resumeMusic();

// Toggle pausa/reproducción
audioManager.togglePlayPause();
```

### Control de Volumen

```javascript
// Establecer volumen (0-1)
audioManager.setVolume(0.7);

// Aumentar (por defecto +0.1)
audioManager.increaseVolume(0.1);

// Disminuir (por defecto -0.1)
audioManager.decreaseVolume(0.1);
```

### Efectos de Sonido

```javascript
// Reproduzca sonidos de validación
audioManager.playSFX('correct');  // Acierto
audioManager.playSFX('error');    // Error
audioManager.playSFX('hint');     // Pista

// Los SFX se reproducen instantáneamente
// No se ven afectados por los controles de música
```

### Obtener Información

```javascript
// Información de la pista actual
const trackInfo = audioManager.getCurrentTrackInfo();
console.log(trackInfo.title); // "Suspiro 8 Bits - Sergio Mouliaá"
console.log(trackInfo.file);  // "/sonido/Prueba1-suspiro.mp3"
```

---

## 🎚️ Mini Reproductor - Funcionalidades

El mini reproductor incluido automáticamente en `game-audio-setup.blade.php` ofrece:

### Controles Visibles
- **🔉 Bajar Volumen**: Reduce el volumen en 0.1 (10%
- **🔊 Subir Volumen**: Aumenta el volumen en 0.1 (10%)
- **Slider de Volumen**: Control preciso del volumen (0-100%)
- **▶/⏸ Play/Pause**: Pausa o reanuda la música
- **Título Dinámico**: Muestra el nombre de la canción actual
- **Indicador Luminoso**: Punto que pulsa cuando está reproduciendo

### Atajos de Teclado (Global)
- **Espacio**: Pausa/Reproducción
- **Ctrl + ⬆️**: Aumentar volumen (+5%)
- **Ctrl + ⬇️**: Disminuir volumen (-5%)

### Responsive
- **Desktop**: 300px ancho
- **Tablet**: 300px ancho (adaptado)
- **Mobile**: 250px ancho, fuente más pequeña

---

## 🔗 Integración con Validaciones de Pruebas

### Para reproducir sonidos en validaciones correctas/incorrectas:

En el archivo Blade o JavaScript de la prueba:

```javascript
// Cuando la validación es correcta
function validarPruebaOK() {
  audioManager.playSFX('correct');
  // ... resto del código
}

// Cuando hay error
function validarPruebaFail() {
  audioManager.playSFX('error');
  // ... resto del código
}
```

O usando eventos personalizados:

```javascript
// Disparar evento de validación correcta
document.dispatchEvent(new CustomEvent('validation:correct'));

// Disparar evento de validación incorrecta
document.dispatchEvent(new CustomEvent('validation:error'));
```

---

## 🔊 Mapeo de Pistas de Música

| Clave | Archivo | Título Mostrado |
|-------|---------|-----------------|
| `login` | MusicaMenuLogin.mp3 | DAWSCAPE - Tema Principal |
| `prueba1` | Prueba1-suspiro.mp3 | Suspiro 8 Bits - Sergio Mouliaá |
| `prueba2` | Prueba2-zasi.mp3 | X TUS BESOS 8 Bits - Zasi |
| `prueba3` | Prueba3-farsante.mp3 | El Farsante 8 Bits - Ozuna |
| `prueba4` | Prueba4-Arturo.mp3 | Pluma Pluma G.. 8 Bits - Morancos |
| `prueba5` | Prueba5-delocos.mp3 | De Locos 8 Bits - Sergio Mouliaá |
| `prueba6` | Prueba6-fernanfloo.mp3 | Rap de Fernanfloo 8 Bits - Bambiel |
| `prueba7` | Prueba7-NachoRPG.mp3 | Tokyo Drift 8 Bits - Nacho |
| `prueba8` | Prueba8-mandanga.mp3 | Mandanga Style 8 Bits - Amador |
| `prueba9` | Prueba9-MineroRPG.mp3 | Minero 8 Bits - ElRubiusOMG |
| `prueba10` | Prueba10-DanzaKuduroRPG.mp3 | Danza Kuduro 8 Bits - Fernando |
| `final` | FinalVictoria.mp3 | We Are The Champions 8 Bits |

---

## 🔉 Mapeo de Efectos de Sonido

| Clave | Archivo | Uso |
|-------|---------|-----|
| `correct` | Acierto.mp3 | Cuando una validación es correcta |
| `error` | Error.mp3 | Cuando una validación falla |
| `hint` | Boton-Pista.mp3 | Al hacer click en el botón de pista |

---

## 📱 Comportamiento en Mobile

- Audio se pausa automáticamente cuando se cambia de pestaña
- Se reanuda cuando vuelves a la aplicación
- Volumen se persiste en localStorage
- Los controles son táctiles y responsive
- El layout se adapta automáticamente a pantallas pequeñas

---

## 💾 Persistencia

El volumen se guarda automáticamente en `localStorage` bajo la clave `'audioVolume'` y se restaura en las próximas sesiones.

```javascript
// El usuario no necesita hacer nada, es automático
// Pero si necesitas borrar la configuración:
localStorage.removeItem('audioVolume');
```

---

## ⚠️ Consideraciones y Buenas Prácticas

### 1. Fade In/Out Suave
La música siempre hace fade in when inicia y fade out cuando cambia:

```javascript
// Automático - cambiar de prueba
audioManager.playMusic('prueba2'); // Fade out automático + fade in
```

### 2. No Reproducir SFX Simultáneamente
Si necesitas reproducir múltiples SFX, asegúrate de que no se sobrelapan:

```javascript
audioManager.playSFX('correct');
// Esperar un poco antes de otro SFX
setTimeout(() => audioManager.playSFX('hint'), 500);
```

### 3. Inicialización en el Boot
El AudioManager se inicializa **automáticamente** mediante `game-audio-setup.blade.php`, así que siempre está disponible como `window.audioManager`.

### 4. CORS
Todos los archivos de sonido están en `public/sonido/` y se acceden directamente, sin requerir rutas Laravel.

---

## 🧪 Testing

Para probar el audio manager en la consola del navegador:

```javascript
// Probar música
audioManager.playMusic('prueba1');
audioManager.playMusic('prueba2'); // Cambiar música

// Probar SFX
audioManager.playSFX('correct');
audioManager.playSFX('error');
audioManager.playSFX('hint');

// Control de volumen
audioManager.setVolume(0.8);
audioManager.togglePlayPause();

// Información actual
console.log(audioManager.getCurrentTrackInfo());
console.log(audioManager.isPlaying);
console.log(audioManager.currentVolume);
```

---

## 🐛 Troubleshooting

### Audio no se reproduce
1. Verifica que los archivos existen en `public/sonido/`
2. Abre la consola del navegador (F12) para ver errores
3. Asegúrate de que `audioManager` está definido: `console.log(window.audioManager)`

### El componente no se ve
1. Verifica que incluiste `@include('components.game-audio-setup', ['trackKey' => 'prueba1'])`
2. Revisa que el archivo `music-player.blade.php` existe com la ruta correcta

### SFX no se reproducen
1. Verifica que los archivos están en `public/sonido/`
2. Comprueba que no hay errores en la consola del navegador
3. Intenta reproducir manualmente: `audioManager.playSFX('correct')`

### Volumen no se persiste
1. Verifica que localStorage está habilitado en el navegador
2. Revisa la consola para errores de permisos

---

## 📝 Ejemplo Completo de Integración

```blade
<!-- resources/views/pruebas/prueba1.blade.php -->

@extends('layouts.app')

@section('content')
  <!-- Componente de audio (incluye música + mini player) -->
  @include('components.game-audio-setup', ['trackKey' => 'prueba1'])

  <div class="game-container">
    <header class="game-header">
      <h1>{{ $titulo }} - Escape Room</h1>
      <div id="timer">--:--</div>
    </header>

    <main class="game-content">
      <!-- Contenido de la prueba -->
      <p>Resuelve el acertijo...</p>
      
      <button onclick="validateAnswer()">Validar Respuesta</button>
    </main>

    <!-- Incluir componente de pista (con sonido integrado) -->
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
        
        // Validación...
        if (isCorrect) {
          audioManager.playSFX('correct');
          // Pasar a la siguiente prueba
        } else {
          audioManager.playSFX('error');
          // Mostrar error
        }
      }
    </script>
  @endpush
@endsection
```

---

## 🎯 Próximos Pasos

1. **Integrare componente en Prueba 1** ✅
2. Replicar en Pruebas 2-10
3. Agregar sonido en pantalla de login
4. Agregar sonido en pantalla final

---

*Sistema de Audio - DAWSCAPE Escape Room Educativo*
