# 🔴 CRITICAL: LA MÚSICA NO FUNCIONA EN PRODUCCIÓN - PROBLEMA IDENTIFICADO

## ⚠️ RAZÓN DEL FALLO

Los errores que ves:
```
Uncaught ReferenceError: audioManager is not defined
    at prueba1:746:5
    at HTMLButtonElement.<anonymous> (prueba1:711:7)
    at (index):664:5
    at HTMLButtonElement.<anonymous> ((index):629:7)
```

**CAUSA RAÍZ:** `npm run build` **NUNCA SE EJECUTÓ EN EL SERVIDOR**

### ¿Por qué falla?

```
1. Laravel @ views (@vite() directives)
   ↓
2. Vite intenta cargar: /public/build/assets/app-xxxxx.js  
   ↓
3. Archivo NO EXISTE (porque npm run build no se corrió)
   ↓
4. app.js nunca se carga
   ↓
5. import './audio-manager.js' nunca se ejecuta
   ↓
6. window.audioManager = audioManager NUNCA se asigna
   ↓
7. Código Blade intenta usar audioManager
   ↓
8. window.audioManager es undefined
   ↓
9. 💥 BOOM: "Uncaught ReferenceError"
```

---

## 🚀 SOLUCIÓN: EJECUTAR EN EL SERVIDOR AHORA

### **OPCIÓN 1: Linux/Mac (SSH)**

```bash
cd /ruta/a/DAWSCAPE
git pull origin main
npm run build
php artisan cache:clear
php artisan config:clear
```

### **OPCIÓN 2: Windows PowerShell (si es Windows Server)**

```powershell
cd C:\ruta\a\DAWSCAPE
git pull origin main
npm run build
php artisan cache:clear
php artisan config:clear
```

### **OPCIÓN 3: Usar script automático (recomendado)**

**Linux/Mac:**
```bash
bash fix-audio-server.sh
```

**Windows:**
```powershell
Set-ExecutionPolicy -ExecutionPolicy Bypass -Scope Process
.\fix-audio-server.ps1
```

---

## ✅ VERIFICAR QUE FUNCIONE

Después de ejecutar los comandos:

1. **Abre el browser en:** `https://tu-dominio.com`
2. **Presiona F12** (DevTools)
3. **Selecciona Console tab**
4. **Escribe:** `audioManager`
5. **Deberías ver:**
   ```
   AudioManager {
     bgMusic: Audio,
     currentTrack: "login",
     isPlaying: true,
     currentVolume: 0.5,
     playMusic: ƒ playMusic(trackKey, fadeInDuration),
     playSFX: ƒ playSFX(sfxKey),
     setVolume: ƒ setVolume(value),
     ...
   }
   ```

Si ves `undefined` → algo falló en npm run build

---

## 🔍 Debugging: Si aún falla

### Paso 1: Verificar que public/build/ existe

```bash
ls -la public/build/manifest.json
# Debe mostrar el archivo

# O en Windows:
Test-Path public/build/manifest.json
# Debe mostrar: True
```

### Paso 2: Verificar permisos

```bash
chmod -R 755 public/build/
chmod -R 644 public/sonido/*
```

### Paso 3: Revisar errors de npm

```bash
npm run build 2>&1 | tee build-output.log
# Enviar build-output.log para verificar errores
```

### Paso 4: Instalar dependencias frescas

```bash
rm -rf node_modules package-lock.json
npm install
npm run build
php artisan cache:clear
```

### Paso 5: Reiniciar servidor web

```bash
# Si usar nginx:
sudo systemctl restart nginx

# Si usar apache:
sudo systemctl restart apache2
```

### Paso 6: Hard refresh en browser

- **Windows/Linux:** Ctrl + Shift + R
- **Mac:** Cmd + Shift + R

---

## 📋 RECAPITULACIÓN

**Problema:** audioManager no definido en producción

**Causa:** npm run build no se ejecutó → public/build/ no existe → app.js no carga → window.audioManager nunca se define

**Solución:** Ejecutar `npm run build` en servidor

**Tiempo estimado:** 3-5 minutos

**Criticidad:** 🔴 ALTA - Sin esto, audio NO funciona

---

## 🚨 MAÑANA ANTES DE EXPONER

**SI O SI ejecuta esto en el servidor terminal:**

```bash
npm run build && php artisan cache:clear && php artisan config:clear
```

**Luego verifica en F12 console:**
```javascript
audioManager  // Debe ser un objeto, NO undefined
```

**Si sale `undefined` → la exposición fallará. Necesitas tiempo extra del admin del servidor.**

---

*Creado: March 12, 2026*  
*Urgencia: MÁXIMA - Mañana es la exposición*
