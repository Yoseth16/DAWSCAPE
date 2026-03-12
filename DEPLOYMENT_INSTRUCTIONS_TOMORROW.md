# 🎵 AUDIO SYSTEM - DEPLOYMENT INSTRUCTIONS FOR PRODUCTION

**TARGET DATE:** Mañana (March 13, 2026)  
**CRITICAL:** El audio debe funcionar 100% para la exposición!

---

## ✅ VER ESTADO ACTUAL

```bash
git log --oneline -5
```

Deberías ver estos commits recientes:
```
01123eb CRITICAL FIX: Make audioManager globally available in window scope
be03bdc fix: properly load audio-system globally via app.js and vite
[más commits anteriores...]
```

---

## 🚀 PASOS A EJECUTAR EN EL SERVIDOR (SSH/Terminal)

### PASO 1: Obtener Últimos Cambios
```bash
cd /path/to/DAWSCAPE
git pull origin main
```

### PASO 2: Compilar Assets (CRÍTICO - Genera public/build/)
```bash
npm run build
```

**Si falla con "npm not found":**
- Instalar Node.js: `curl https://nodejs.org/dist/v20.9.0/node-v20.9.0-linux-x64.tar.xz | tar -xJ`
- O instalar con tu package manager: `sudo apt install nodejs npm`

### PASO 3: Limpiar Caches de Laravel
```bash
php artisan cache:clear
php artisan config:clear
```

### PASO 4: Verificar que public/build/ existe
```bash
ls -la public/build/
```

Deberías ver:
```
drwxr-xr-x  assets/
-rw-r--r--  manifest.json
```

---

## 🧪 VERIFICAR QUE FUNCIONE (Browser DevTools)

### Abrir browser en: `https://tu-dominio.com/inicio`

1. **Abre F12 (DevTools)**
2. **Consola (Console tab)**
3. **Ejecuta:** `console.log(audioManager)`
4. **Resultado esperado:**
   - ✅ Ver un objeto `AudioManager` con métodos como `playMusic()`, `playSFX()`, etc.
   - ❌ Si sale `undefined` → ERROR! (ver troubleshooting abajo)

5. **Ejecuta:** `audioManager.playMusic('prueba1')`
6. **Resultado esperado:**
   - ✅ Escuchar música comenzando a sonar en fade-in
   - ❌ Si no suena → revisar console para errores

---

## 🔧 TROUBLESHOOTING

### ❌ "audioManager is not defined"
**Causa:** public/build/ no existe o @vite() directives no cargan

**Solución:**
```bash
# Verificar nuevo commit está en server
git log --oneline -1

# Si no: hacer pull again
git pull

# Recompilar
npm run build

# Verificar build files
ls -la public/build/manifest.json
```

### ❌ "Failed to fetch manifest.json"
**Causa:** Permisos o servidor web no sirve public/build/

**Solución:**
```bash
# Revisar permisos
chmod -R 755 public/build/

# Reiniciar servidor web (apache/nginx)
sudo systemctl restart nginx   # si usa nginx
# o
sudo systemctl restart apache2 # si usa apache
```

### ❌ Música no auto-reproduce pero audioManager existe
**Causa:** Audio files no accesibles

**Solución:**
```bash
# Verificar archivos de música existen
ls -la public/sonido/

# Verificar permisos
chmod -R 644 public/sonido/*
```

---

## 📋 CHECKLIST FINAL

ANTES de mostrar mañana:

- [ ] `git pull` ejecutado y muestra commit `01123eb`
- [ ] `npm run build` ejecutado sin errores
- [ ] `php artisan cache:clear` ejecutado
- [ ] Browser F12 console: `audioManager` es un objeto (no undefined)
- [ ] Música auto-reproduce en login
- [ ] Mini player visible en bottom-left
- [ ] SFX suena cuando validas preguntas

---

## 🎯 RESUMEN CAMBIOS CRÍTICOS

Estos 4 archivos se arreglaron HOY:

1. **resources/js/audio-manager.js**
   - ✅ Ahora: `window.audioManager = audioManager` (line 330)
   
2. **resources/js/sfx-integration.js**
   - ✅ Ahora: `if (window.audioManager) window.audioManager.playSFX(...)`
   
3. **resources/js/validation-sfx-helper.js**
   - ✅ Ahora: `if (window.audioManager) window.audioManager.playSFX(...)`
   
4. **resources/views/components/game-audio-setup.blade.php**
   - ✅ Ahora: `if (window.audioManager)` + retry logic

---

## 🆘 AYUDA RÁPIDA

**Si todo falla en 5 minutos antes de exposition:**

Ejecutar esto en servidor en ORDEN:

```bash
cd /path/to/DAWSCAPE

# 1. Verificar código correcto
git log --oneline -1

# 2. Recompilar agresivamente
rm -rf node_modules package-lock.json
npm install
npm run build

# 3. Limpiar todo
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# 4. Verificar permisos
chmod -R 755 public/build/
chmod -R 644 public/sonido/*

# 5. Reiniciar web server
systemctl reload nginx  # o apache2

# 6. En browser: F5 + Ctrl+Shift+R (hard refresh)
```

¡ÉXITO! 🚀
