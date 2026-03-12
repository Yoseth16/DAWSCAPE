# ✅ Audio Integration Checklist

## Pre-requisitos
- [ ] Node.js y npm están instalados
- [ ] Archivos de sonido están en `public/sonido/`
- [ ] Laravel/Vite están configurados correctamente

## Instalación

### 1. Compilar Assets
```bash
npm install  # Si es necesario
npm run build  # Para producción
# o
npm run dev  # Para desarrollo
```

## Integración en Pruebas

### Prueba 1 (Mario - AULA 115)
- [ ] Incluir: `@include('components.game-audio-setup', ['trackKey' => 'prueba1'])`
- [ ] Agregar SFX en validaciones
- [ ] Probar en navegador
- [ ] Probar en móvil

### Prueba 2 (Yoseth)
- [ ] Incluir: `@include('components.game-audio-setup', ['trackKey' => 'prueba2'])`
- [ ] Integrar efectos de sonido
- [ ] Testing

### Prueba 3 (Angel - Máquina de Café)
- [ ] Incluir: `@include('components.game-audio-setup', ['trackKey' => 'prueba3'])`
- [ ] SFX en validaciones
- [ ] Testing

### Prueba 4 (Arturo - Oasis)
- [ ] Incluir: `@include('components.game-audio-setup', ['trackKey' => 'prueba4'])`
- [ ] SFX en validaciones
- [ ] Testing

### Prueba 5 (Eloy - Protocolo Espejo)
- [ ] Incluir: `@include('components.game-audio-setup', ['trackKey' => 'prueba5'])`
- [ ] SFX en validaciones
- [ ] Testing

### Prueba 6 (Migue - Secuencia)
- [ ] Incluir: `@include('components.game-audio-setup', ['trackKey' => 'prueba6'])`
- [ ] SFX en validaciones
- [ ] Testing con animaciones

### Prueba 7 (QR - Cortafuegos)
- [ ] Incluir: `@include('components.game-audio-setup', ['trackKey' => 'prueba7'])`
- [ ] SFX en quiz
- [ ] Testing

### Prueba 8 (Códice Oculto - Dinamica)
- [ ] Incluir: `@include('components.game-audio-setup', ['trackKey' => 'prueba8'])`
- [ ] SFX en validaciones
- [ ] Testing

### Prueba 9-10 (Opcionales)
- [ ] Incluir: `@include('components.game-audio-setup', ['trackKey' => 'prueba9/10'])`
- [ ] SFX en validaciones
- [ ] Testing

## Pantallas Especiales

### Login/Inicio
- [ ] `@include('components.game-audio-setup', ['trackKey' => 'login'])`
- [ ] Testing de fade-in

### Pantalla Final (victoria)
- [ ] `@include('components.game-audio-setup', ['trackKey' => 'final'])`
- [ ] Estado de celebración con audio

## Testing Funcional

### Audio Manager
- [ ] `console.log(audioManager)` → debe mostrar objeto
- [ ] `audioManager.playMusic('prueba1')` → debe sonar música
- [ ] `audioManager.playSFX('correct')` → debe sonar efecto

### Mini Reproductor
- [ ] Visible en esquina inferior izquierda
- [ ] Botones funcionan (play/pause)
- [ ] Slider de volumen funciona
- [ ] Título se actualiza
- [ ] Responsive en mobile

### Efectos de Sonido
- [ ] Click en pista → suena `Boton-Pista.mp3`
- [ ] Validación correcta → suena `Acierto.mp3`
- [ ] Validación error → suena `Error.mp3`

### Atajos de Teclado
- [ ] Espacio = Pausa/Reproducción
- [ ] Ctrl+↑ = Aumentar volumen
- [ ] Ctrl+↓ = Disminuir volumen

### Mobile
- [ ] Audio reproduce en Safari iOS
- [ ] Audio reproduce en Chrome Android
- [ ] Mini player es responsive
- [ ] Controles son táctiles

### Persistencia
- [ ] Volumen se guarda al cambiar
- [ ] Volumen se restaura al recargar
- [ ] localStorage no está corrupto

## Debugging

Si hay problemas, verificar:

### Consola del Navegador (F12)
```javascript
// Ver si AudioManager está cargado
console.log(window.audioManager);

// Probar reproducción
audioManager.playMusic('prueba1');

// Verificar errores CORS
// Buscar en Consola → Network banderas rojas
```

### Archivos Faltantes
- [ ] `public/sonido/*.mp3` existen todos
- [ ] Routes/permissions correctos
- [ ] No hay errores 404 en consola

### Vite Configuration
- [ ] Todos los JS están en vite.config.js
- [ ] Build completa sin errores: `npm run build`
- [ ] Manifest está correcto: `public/hot` (dev) o OK (prod)

## Performance

- [ ] Audio no causa lag en validaciones
- [ ] Mini player no consume recursos excesivos
- [ ] Memory no crece indefinidamente
- [ ] CPU usage es mínimo

## Browser Compatibility

Probar en:
- [ ] Chrome/Edge (últimas 2 versiones)
- [ ] Firefox (últimas 2 versiones)
- [ ] Safari (últimas 2 versiones)
- [ ] Mobile Safari (iOS)
- [ ] Chrome Mobile (Android)

## Deployment

### Antes de Deploy a Producción
1. [ ] Compilar: `npm run build`
2. [ ] Verificar: `ls public/built/` (o equivalent)
3. [ ] Testing en environment de stage
4. [ ] Verificar permisos de archivos en servidor
5. [ ] Probar en red lenta (simular throttling)

### Después de Deploy
- [ ] Verificar en servidor que archivos están donde deben
- [ ] Testing de audio en producción
- [ ] Monitorear error logs
- [ ] Feedback de usuarios

## Optimizaciones Futuras

- [ ] Preload de archivos de audio
- [ ] Compresión de audio (AAC, Opus)
- [ ] Transcoding a múltiples formatos
- [ ] CDN para archivos de audio
- [ ] Service Worker para cache
- [ ] Análisis de uso de audio

## Documentación

- [ ] Desarrolladores leyeron AUDIO_QUICKSTART.md
- [ ] Admin/profesor conoce cómo configurar volumen
- [ ] Documentación actualizada en wiki del proyecto
- [ ] Video tutorial grabado (opcional)

## Sign-Off

- [ ] Implementador: _________________ Fecha: _______
- [ ] QA/Tester: _________________ Fecha: _______
- [ ] Project Manager: _________________ Fecha: _______

---

**Última actualización**: 12/03/2026
**Versión**: 1.0
**Estado**: ✅ LISTO

*Sistema de Audio Completo - DAWSCAPE*
