# 🔧 SERVER SETUP - PASOS EXACTOS PARA DESCARGAR NPM PACKAGES

## ⚠️ PROBLEMA ACTUAL

`npm run build` falla porque **no tienes las librerías descargadas en el servidor**.

Cuando haces `npm run build` sin las librerías, Vite no encuentra `tailwindcss`, `alpinejs`, etc.

---

## 🚀 SOLUCIÓN: Secuencia EXACTA en el servidor

### PASO 1: SSH al servidor

```bash
ssh user@tu-servidor.com
cd /ruta/a/DAWSCAPE
```

### PASO 2: Verificar que Git esté actualizado

```bash
git pull origin main
```

### PASO 3: Verificar que Node.js y npm estén instalados

```bash
node --version   # Debe mostrar: v18.x.x o superior
npm --version    # Debe mostrar: 8.x.x o superior
```

**Si falta Node.js:**
```bash
# Ubuntu/Debian:
sudo apt update && sudo apt install nodejs npm

# Alpine:
apk add nodejs npm

# CentOS/RHEL:
sudo yum install nodejs npm
```

### PASO 4: DESCARGAR TODAS LAS LIBRERÍAS (CRÍTICO) ⭐

```bash
npm install
```

**Esto descarga en `node_modules/` TODAS las librerías de `package.json`:**
- Vite
- Tailwind CSS
- Alpine.js
- Laravel Vite Plugin
- Etc...

**Espera a que termine completamente.** Puede tardar 2-5 minutos.

### PASO 5: COMPILAR los assets

Ahora SÍ funciona:
```bash
npm run build
```

**Esto genera `public/build/` con:**
```
public/build/
├── assets/
│   ├── app-xxxxx.js    ← App compilado CON audioManager
│   ├── app-xxxxx.css   ← Estilos compilados
│   └── ...
└── manifest.json       ← Índice de archivos
```

### PASO 6: Limpiar caches de Laravel

```bash
php artisan cache:clear
php artisan config:clear
```

### PASO 7: VERIFICAR que funcione

```bash
ls -la public/build/manifest.json
# Debe existir el archivo
```

---

## ✅ RESULTADO ESPERADO DESPUÉS DE `npm install`

```
added 180 packages, and removed 123 packages in 3m 45s
```

Verás muchas líneas como:
```
added 180 packages
  ├── @tailwindcss/forms
  ├── @tailwindcss/vite
  ├── alpinejs
  ├── axios
  ├── laravel-echo
  ├── laravel-vite-plugin
  ├── postcss
  ├── tailwindcss
  ├── vite
  └── (más librerías)
```

---

## 🔄 Flujo CORRECTO

```
Server inicial:
  package.json ✓ (existe)
  node_modules/ ✗ (NO existe)
  npm run build ✗ (FALLA porque necesita node_modules/)
        ↓
npm install:
  package.json ✓ (se lee)
  node_modules/ ← SE CREA (180 packages)
  package-lock.json ✅ (se genera)
        ↓
npm run build ✓ (AHORA FUNCIONA porque tiene node_modules/)
  public/build/ ← SE CREA con assets compilados
        ↓
@vite() en Blade:
  Busca public/build/manifest.json ✓ EXISTE
  Carga app-xxxxx.js ✓ EXISTE
  Ejecuta: import './audio-manager.js' ✓ FUNCIONA
  Define: window.audioManager ✅ DISPONIBLE EN GLOBAL SCOPE
```

---

## ⚡ COMANDO RÁPIDO (todo junto)

```bash
cd /ruta/a/DAWSCAPE && \
git pull origin main && \
npm install && \
npm run build && \
php artisan cache:clear && \
php artisan config:clear && \
echo "✅ DONE!"
```

---

## 🔍 Si algo falla

### Error: "npm command not found"
```bash
# Node.js no está instalado
sudo apt install nodejs npm
```

### Error: "Cannot find module 'vite'"
```bash
# Falta npm install
npm install
```

### Error: "EACCES: permission denied"
```bash
# Permisos insuficientes
sudo npm install --no-optional
# O cambiar permisos de npm:
mkdir ~/.npm-global
npm config set prefix '~/.npm-global'
export PATH=~/.npm-global/bin:$PATH
```

### node_modules/ crece mucho (>500MB)
```bash
# Normal. Contiene 180 paquetes. Git lo ignora por .gitignore
# No subir node_modules/ a Git
cat .gitignore | grep node_modules
# Debe mostrar: node_modules/
```

---

## 📋 RESUMEN PARA MAÑANA

**ANTES DE EXPONER:**

En terminal del servidor:

```bash
cd /ruta/a/DAWSCAPE
npm install          # ← Descargar librerías (solo 1 vez)
npm run build        # ← Compilar assets
php artisan cache:clear
```

**Luego en browser:**
```javascript
// F12 Console:
audioManager  // Debe ser objeto, NO undefined
```

---

## 🎯 CLAVE

**`npm install` = Descargar librerías de internet (package.json → node_modules/)**  
**`npm run build` = Compilar esas librerías + tu código (node_modules/ + src/ → public/build/)**

Sin `npm install`, `npm run build` NO puede compilar porque faltan librerías.

Es como:
- `npm install` = Descargar Vite, Tailwind, Alpine desde npm registry
- `npm run build` = Usar esas herramientas para compilar tu app

