#!/bin/bash

# ==============================================================================
# Script de Despliegue Automatizado DAWSCAPE (CI/CD Local)
# Entorno: AlmaLinux, Nginx, PHP-FPM, MariaDB, Laravel + Vite
# ==============================================================================

# ── FIX: Asegurar que sudo hereda las rutas de los binarios ──
# Cuando se ejecuta con "sudo", el PATH puede perder /usr/local/bin, ~/.composer, etc.
export PATH="/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:$PATH"

# Variables de entorno
APP_DIR="/var/www/DAWSCAPE"
LARAVEL_USER="nginx" # o "www-data", depende de cómo se ejecute Nginx en AlmaLinux

# Colores para los mensajes (hace la terminal mucho más legible)
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Función para manejar errores de forma centralizada
manejar_error() {
    echo -e "${RED}❌ ERROR CRÍTICO EN EL PASO ANTERIOR. El despliegue se ha detenido para evitar corrupción.${NC}"
    # Opcional: Podrías añadir aquí un curl a un webhook de Discord/Slack para avisarte si falla en automático
    exit 1
}

# Activar modo estricto: Si un comando falla ($? != 0), todo el script se detiene y llama a manejar_error
set -e
trap 'manejar_error' ERR

echo -e "${BLUE}================================================${NC}"
echo -e "${BLUE}🚀 INICIANDO DESPLIEGUE EN $APP_DIR...${NC}"
echo -e "${BLUE}================================================${NC}"

# Verificar que estamos en la ruta correcta, si no, ir a ella
if [ "$PWD" != "$APP_DIR" ]; then
    echo -e "${YELLOW}Cambiando al directorio de la aplicación ($APP_DIR)...${NC}"
    cd "$APP_DIR" || exit
fi

# 1. MODO MANTENIMIENTO
echo -e "${YELLOW}[1/8] Activando modo mantenimiento (Laravel Down)...${NC}"
php artisan down || true # Si ya estaba caído, no quiero que falle el script

# 2. OBTENER CÓDIGO NUEVO
echo -e "${YELLOW}[2/8] Descargando cambios de Git...${NC}"
git pull origin main

# 3. ACTUALIZAR DEPENDENCIAS PHP
echo -e "${YELLOW}[3/8] Actualizando dependencias de Composer...${NC}"
composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# 4. COMPILAR VITE (FRONTEND)
echo -e "${YELLOW}[4/8] Compilando Web Assets (NPM & Vite)...${NC}"
npm install
npm run build

# 5. MIGRACIONES BASE DE DATOS
echo -e "${YELLOW}[5/8] Ejecutando Migraciones (MariaDB)...${NC}"
php artisan migrate --force

# 6. OPTIMIZACIÓN MÁXIMA (LARAVEL)
echo -e "${YELLOW}[6/8] Limpiando y Cacheando configuraciones...${NC}"
php artisan optimize:clear
php artisan config:cache
php artisan event:cache
php artisan route:cache
php artisan view:cache

# 7. PERMISOS DE ARCHIVOS
echo -e "${YELLOW}[7/8] Restaurando permisos seguros...${NC}"
chown -R $LARAVEL_USER:$LARAVEL_USER storage bootstrap/cache public/build
chmod -R 775 storage bootstrap/cache public/build

# 8. REINICIO DE SERVICIOS
echo -e "${YELLOW}[8/8] Reiniciando servicios (Nginx & PHP-FPM)...${NC}"
systemctl restart php-fpm
systemctl restart nginx

# FIN DEL MODO MANTENIMIENTO
echo -e "${YELLOW}Levantando aplicación...${NC}"
php artisan up

echo -e "${GREEN}================================================${NC}"
echo -e "${GREEN}✅ DESPLIEGUE COMPLETADO CON ÉXITO A LAS $(date +"%T")${NC}"
echo -e "${GREEN}================================================${NC}"
