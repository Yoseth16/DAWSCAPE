<div align="center">
  <img src="https://laravel.com/img/logomark.min.svg" width="100" alt="Laravel Logo">
  <h1>🚀 DAWSCAPE: Escape Room Web App</h1>
  <p><strong>Plataforma integral para gestión y desarrollo de un Escape Room técnico y educativo.</strong></p>
</div>

---

## 📖 Descripción del Proyecto

**DAWSCAPE** es una aplicación web interactiva desarrollada para gestionar sesiones inmersivas de un Escape Room. La plataforma se encarga tanto de la experiencia de los jugadores (gestión del tiempo real, validación de puzles, escaneo de códigos QR dinámicos) como de la administración por parte de los Game Masters (panel en vivo con analíticas de progreso, penalizaciones y clasificaciones).

### 🛠️ Stack Tecnológico
- **Backend:** PHP 8.2+ / Laravel 11
- **Base de Datos:** MariaDB
- **Frontend:** HTML5, Vanilla CSS avanzado, JavaScript (ES6+), Blade Templates
- **Asset Manager:** Vite (Módulos JS y CSS Hot Reload)
- **Despliegue/Servidor:** AlmaLinux 9, Nginx, PHP-FPM, CI/CD Scripted

---

## ⚙️ Requisitos Previos

Para ejecutar y colaborar en este proyecto localmente, necesitas tener instalados los siguientes componentes en tu sistema:

- **PHP** >= 8.2
- **Composer** (Gestor de dependencias de PHP)
- **Node.js** >= 18 y **npm** (Para compilar los recursos front-end)
- **MySQL** o **MariaDB** instalado y ejecutándose localmente
- **Git**

---

## 🚀 Instalación en Local

Sigue estos pasos para arrancar el entorno de desarrollo en tu máquina:

**1. Clonar el repositorio**
```bash
git clone https://github.com/tu-usuario/DAWSCAPE.git
cd DAWSCAPE
```

**2. Instalar dependencias de PHP (Composer)**
```bash
composer install
```

**3. Instalar dependencias de Frontend (NPM) y compilar**
```bash
npm install
npm run build
```

**4. Configurar el entorno**
Copia el archivo de ejemplo para crear tu configuración local y genera la clave de cifrado:
```bash
cp .env.example .env
php artisan key:generate
```

**5. Configurar la Base de Datos**
Abre tu archivo `.env` recién creado y asegúrate de que los credenciales de la base de datos coincidan con los tuyos locales:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dawscape
DB_USERNAME=root
DB_PASSWORD=
```

**6. Ejecutar migraciones y seeders**
Crea la base de datos vacía en tu gestor (ej. phpMyAdmin o DBeaver) y ejecuta:
```bash
php artisan migrate --seed
```
*(El seeder creará los datos de prueba y administradores iniciales si están configurados).*

**7. Levantar el servidor de desarrollo**
Inicia el servidor backend de Laravel:
```bash
php artisan serve
```
Y **en otra terminal auxiliar**, levanta Vite para el *Hot-Reload* del frontend:
```bash
npm run dev
```

La app estará disponible en: `http://localhost:8000`

---

## 🌍 Despliegue en Producción (CI/CD)

El proyecto cuenta con un sistema de **Despliegue Continuo (Continuous Deployment)** preparado para el servidor AlmaLinux de producción.

Hemos creado el script de automatización `./deploy.sh` ubicado en la raíz del proyecto. Este script se encarga de:
1. Poner la aplicación en modo mantenimiento (`php artisan down`).
2. Actualizar las ramas de Git.
3. Instalar dependencias (Composer/NPM) de forma limpia.
4. Compilar los assets empaquetados mediante Vite (`npm run build`).
5. Ejecutar migraciones pendientes a la base de datos de manera segura.
6. Limpiar y reconstruir las cachés de Laravel (Config, Rutas, Vistas).
7. Ajustar y asegurar los permisos correctos (`chown -R nginx:nginx`).
8. Reiniciar los servicios `php-fpm` y `nginx`.

### ¿Cómo actualizar el servidor tras publicar un cambio?
Ya no tienes que picar decenas de comandos, simplemente entra a tu servidor de producción (SSH) y dentro de la carpeta del proyecto ejecuta:

```bash
sudo ./deploy.sh
```

*(Nota: Está configurado un Hook en GitHub (`post-merge`) de forma que si realizas un `git pull` en el servidor, este llamará al `deploy.sh` automáticamente).*

---

## 🗄️ Estructura de la Base de Datos

La lógica transaccional de **DAWSCAPE** es gestionada de manera robusta usando Eloquent ORM. Sus principales entidades son:

- `users` : Almacena las cuentas maestras (Profesores / Game Masters) con acceso al dashboard analítico.
- `partidas` : Tabla central. Gestiona la sesión de un equipo completo, su UUID para seguridad, fase actual, tiempo consumido penalizaciones por errores y estado (Activo/Completado).
- `jugadores` : Miembros individuales adscritos a una partida en concreto.
- `puzzles` & `intentos`: Registran las interacciones, repuestas e intentos fallidos a los distintos retos proporcionados en el Escape Room.
- `qrs` : Enlaces y metadatos de los códigos QR dinámicos repartidos físicamente en el entorno, que enrutan al participante a diferentes retos in-game.
- `hitos_fases`: Tabla intermedia histórica para almacenar el *timestamp* exacto en el que un equipo alcanza una nueva fase / checkpoint.

---

<div align="center">
  <i>Desarrollado con ❤️ para llevar los Escape Rooms al siguiente nivel técnico.</i>
</div>
