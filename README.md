# 🎮 DAWSCAPE - Escape Room Educativo Interactivo

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind">
  <img src="https://img.shields.io/badge/JavaScript-ES6+-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black" alt="JavaScript">
</p>

---

## 📖 Descripción

**DAWSCAPE** es un **Escape Room Educativo Digital** desarrollado como proyecto de **1º DAW (Desarrollo de Aplicaciones Web)** que combina desafíos lógicos, exploración física del entorno real mediante códigos QR, y un sistema de ranking en tiempo real.

Los equipos deben resolver **7 pruebas secuenciales** que incluyen acertijos matemáticos, lógica, deducción, y búsqueda de códigos QR ocultos en el instituto. Todo ello mientras un cronómetro global cuenta el tiempo transcurrido y penaliza el uso de pistas.

El proyecto integra tecnologías modernas como **Laravel Reverb** para actualizaciones en tiempo real, **generación dinámica de QR**, **sistema de logros**, y un **panel de administración completo** para gestionar el evento.

---

## ✨ Características Principales

### 🎯 Sistema de Juego

- **7 Pruebas Secuenciales**: Cada una diseñada por un miembro diferente del equipo
- **Cronómetro Global**: 90 minutos máximo para completar todas las pruebas
- **Sistema de Pistas**: Los equipos pueden solicitar ayuda con penalización de tiempo (60 segundos)
- **Integración Física**: Códigos QR ocultos en ubicaciones reales del instituto
- **Validación Inteligente**: Normalización automática de respuestas (sin acentos, mayúsculas, espacios)
- **Sistema de Logros**: Medallas desbloqueables por rendimiento excepcional

### 🏆 Ranking y Competición

- **Top 10 en Tiempo Real**: Pantalla pública con actualización automática vía WebSockets
- **Podio Animado**: Visualización espectacular de los 3 primeros puestos
- **Celebración Automática**: Confetti y animaciones cuando un equipo completa el escape
- **Exportación PDF**: Generación de ranking Top 20 para impresión

### 🔐 Sistema de Inscripción

- **Registro Previo**: Los equipos se inscriben antes del evento
- **Código QR Único**: Cada equipo recibe un QR descargable para validar su acceso
- **Código de Acceso**: Sistema de 6 caracteres alfanuméricos enviado por email
- **Validación Triple**: Correo + Nombre de Equipo + Código de Acceso

### 👨‍💼 Panel de Administración

- **Dashboard en Tiempo Real**: Monitorización de todos los equipos activos
- **Métricas KPI**: Total inscritos, en progreso, completados, tiempo promedio
- **Gráficos Interactivos**: Distribución por fases con Chart.js
- **Gestión de QRs**: Creación y descarga de códigos QR para las pruebas físicas
- **Control Total**: Eliminación de equipos, visualización de logs, gestión de inscripciones

---

## 🧩 Inventario de Pruebas

| # | Nombre | Autor | Tipo | Descripción |
|---|--------|-------|------|-------------|
| **1** | Aula 115 | Mario | Digital | 6 acertijos lógicos sobre el aula de informática |
| **2** | El Clúster Fragmentado | Yoseth | Físico/Digital | Buscar 5 letras en máquinas expendedoras y formar un anagrama |
| **3** | Operación Cafeína | Ángel | Físico (QR) | Escanear QR en la máquina de café y resolver ecuación matemática |
| **4** | Operación Repuestos | Arturo | Físico (QR) | Localizar QR en máquina de snacks y calcular checksum |
| **5** | Protocolo Espejo | Eloy | Físico (QR) | Descifrar palabra invertida en taquillas (alfabeto espejo) |
| **6** | Sobrecarga del Sistema | Miguel | Digital | Simon Says cooperativo de 5 rondas con penalización |
| **7** | El Códice Oculto | Dinámico | Físico (QR) | Candado digital de 4 dígitos con acertijos en ubicaciones físicas |

### 🎖️ Sistema de Logros

- **⚡ Speedrunner Ninja**: Completar Prueba 1 en menos de 2 minutos
- **🧠 Cerebro Intacto**: Finalizar el juego sin usar ninguna pista
- **💻 Hackerman**: Descubrir el Easter Egg secreto en Prueba 5

---

## 🛠️ Stack Tecnológico

### Backend
- **Framework**: Laravel 11.x
- **Lenguaje**: PHP 8.2+
- **Base de Datos**: MySQL 8.0
- **ORM**: Eloquent
- **WebSockets**: Laravel Reverb (Broadcasting en tiempo real)
- **Generación QR**: `simplesoftwareio/simple-qrcode`
- **Generación PDF**: `barryvdh/laravel-dompdf`

### Frontend
- **Motor de Plantillas**: Blade
- **CSS Framework**: Tailwind CSS 3.x
- **CSS Personalizado**: Glassmorphism, animaciones CSS3
- **JavaScript**: ES6+ (Vanilla JS)
- **Iconos**: Phosphor Icons
- **Gráficos**: Chart.js
- **Fuentes**: Google Fonts (Orbitron, Share Tech Mono, Outfit)

### Herramientas de Desarrollo
- **Build Tool**: Vite
- **Package Manager**: Composer (PHP), NPM (JS)
- **Control de Versiones**: Git

---

## 📦 Instalación

### Requisitos Previos

- PHP >= 8.2
- Composer
- Node.js >= 18.x y NPM
- MySQL >= 8.0 o MariaDB >= 10.3
- Extensiones PHP: `gd`, `mbstring`, `pdo_mysql`, `zip`, `xml`

### Instalación en Local (Desarrollo)

1. **Clonar el repositorio**
```bash
git clone https://github.com/xtart1DAW/DAWSCAPE.git
cd DAWSCAPE
```

2. **Instalar dependencias de PHP**
```bash
composer install
```

3. **Instalar dependencias de Node.js**
```bash
npm install
```

4. **Configurar el entorno**
```bash
cp .env.example .env
```

5. **Editar el archivo `.env`** con tus credenciales de base de datos:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dawscape
DB_USERNAME=root
DB_PASSWORD=
```

6. **Generar clave de aplicación**
```bash
php artisan key:generate
```

7. **Crear la base de datos** (si no existe):
```sql
CREATE DATABASE dawscape CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

8. **Ejecutar migraciones y seeders**
```bash
php artisan migrate:fresh --seed
```

Esto creará:
- Todas las tablas necesarias
- Usuario administrador por defecto: `admin@dawscape.local` / `password`
- Banco de preguntas dinámicas para la Prueba 7
- Códigos QR de ejemplo

9. **Compilar assets**
```bash
npm run build
```

Para desarrollo con hot-reload:
```bash
npm run dev
```

10. **Enlazar almacenamiento público**
```bash
php artisan storage:link
```

11. **Iniciar servidor de desarrollo**
```bash
php artisan serve
```

La aplicación estará disponible en: `http://localhost:8000`

### Configuración de WebSockets (Opcional)

Para habilitar actualizaciones en tiempo real:

1. **Instalar Laravel Reverb** (ya incluido en composer.json)

2. **Configurar `.env`**:
```env
BROADCAST_DRIVER=reverb
REVERB_APP_ID=dawscape
REVERB_APP_KEY=tu-clave-secreta
REVERB_APP_SECRET=tu-secreto
REVERB_HOST=localhost
REVERB_PORT=8080
REVERB_SCHEME=http
```

3. **Iniciar servidor Reverb**:
```bash
php artisan reverb:start
```

---

## 🚀 Despliegue en Producción

### Preparación

1. **Configurar `.env` para producción**:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://dawscape.fun

DB_CONNECTION=mysql
DB_HOST=tu-servidor-mysql
DB_PORT=3306
DB_DATABASE=dawscape_prod
DB_USERNAME=usuario_prod
DB_PASSWORD=contraseña_segura
```

2. **Optimizar aplicación**:
```bash
composer install --optimize-autoloader --no-dev
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

3. **Configurar permisos**:
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

4. **Configurar servidor web** (Nginx/Apache) para apuntar a `/public`

### Script de Despliegue Automático

El proyecto incluye `deploy.sh` para automatizar el despliegue:

```bash
chmod +x deploy.sh
./deploy.sh
```

---

## 🎮 Uso del Sistema

### Para Equipos (Jugadores)

1. **Inscripción Previa**: Acceder a `/inscripcion` y registrar el equipo
2. **Recibir Código**: Anotar el código de acceso de 6 caracteres
3. **Día del Evento**: Acceder a `/inicio` e introducir credenciales
4. **Jugar**: Resolver las 7 pruebas secuencialmente
5. **Escanear QRs**: Buscar códigos QR físicos en el instituto para las pruebas 3, 4, 5 y 7
6. **Finalizar**: Ver resultados y posición en el ranking

### Para Administradores

1. **Acceso**: `/login` con credenciales de administrador
2. **Dashboard**: Monitorizar equipos en tiempo real
3. **Gestión de QRs**: Crear y descargar QRs para ubicaciones físicas
4. **Banco de Preguntas**: Añadir/editar preguntas para la Prueba 7
5. **Exportar Ranking**: Generar PDF del Top 20
6. **Proyección**: Mostrar `/top10` en pantalla grande para el público

### Credenciales por Defecto

**Administrador**:
- Email: `1daw`
- Password: `1daw`

⚠️ **IMPORTANTE**: Cambiar estas credenciales en producción.

---

## 📁 Estructura del Proyecto

```
DAWSCAPE/
├── app/
│   ├── Events/              # Eventos de Broadcasting (WebSockets)
│   ├── Http/
│   │   ├── Controllers/     # Controladores principales
│   │   ├── Middleware/      # Middleware personalizado
│   │   └── Requests/        # Form Requests de validación
│   ├── Mail/                # Clases de correo electrónico
│   ├── Models/              # Modelos Eloquent
│   └── Services/            # Servicios (GameTimer, AdminStats)
├── config/
│   └── escape.php           # Configuración del juego (respuestas, pistas)
├── database/
│   ├── migrations/          # Migraciones de base de datos
│   └── seeders/             # Seeders (datos iniciales)
├── public/
│   └── images/pistas/       # Imágenes de pistas
├── resources/
│   ├── css/                 # Estilos personalizados
│   ├── js/                  # JavaScript (Vite)
│   └── views/
│       ├── admin/           # Vistas del panel de administración
│       ├── pruebas/         # Vistas de las 7 pruebas
│       ├── qr/              # Vistas de validación de QR
│       └── components/      # Componentes Blade reutilizables
├── routes/
│   ├── web.php              # Rutas principales
│   ├── channels.php         # Canales de Broadcasting
│   └── console.php          # Comandos Artisan
└── storage/
    └── app/public/qrs/      # QRs generados dinámicamente
```

---

## 🔧 Configuración Avanzada

### Personalizar Respuestas y Pistas

Editar `config/escape.php`:

```php
return [
    'respuestas' => [
        'prueba1' => [
            ['115', 'aula 115'],
            ['16', 'dieciséis'],
            // ...
        ],
        'prueba3' => 103,
        // ...
    ],
    'pistas' => [
        1 => [
            'texto' => 'Observa bien el aula...',
            'imagen' => '/images/pistas/angel.png',
        ],
        // ...
    ],
    'penalizacion_pista' => 60, // segundos
];
```

### Añadir Nuevas Preguntas Dinámicas

Desde el panel de administración o mediante seeder:

```php
PreguntaDinamica::create([
    'enunciado' => '¿Cuál es la capital de Francia?',
    'respuesta_correcta' => 'París',
    'activa' => true,
]);
```

### Personalizar Tiempo Máximo

En `app/Services/GameTimerService.php`:

```php
const TIEMPO_MAXIMO_MINUTOS = 90; // Cambiar según necesidad
```

---

## 🐛 Solución de Problemas

### Error: "Class 'SimpleSoftwareIO\QrCode\Facades\QrCode' not found"

```bash
composer require simplesoftwareio/simple-qrcode
php artisan config:clear
```

### Error: "SQLSTATE[HY000] [2002] Connection refused"

Verificar que MySQL esté corriendo y las credenciales en `.env` sean correctas.

### Los QRs no se generan

```bash
php artisan storage:link
chmod -R 775 storage
```

### WebSockets no funcionan

Verificar que Laravel Reverb esté corriendo:
```bash
php artisan reverb:start
```

Y que el puerto 8080 esté abierto en el firewall.

---

## 📊 Base de Datos

### Tablas Principales

- **inscripcions**: Equipos registrados antes del evento
- **partidas**: Sesiones de juego activas
- **hitos_fases**: Registro de progreso por fase
- **pistas_usadas**: Control de pistas solicitadas
- **log_juegos**: Auditoría de acciones
- **qrs**: Códigos QR dinámicos generados
- **pregunta_dinamicas**: Banco de preguntas para Prueba 7

---

## 🤝 Contribuciones

Este proyecto fue desarrollado como trabajo académico por el equipo de 1º DAW:

- **Mario**: Prueba 1 (Aula 115)
- **Yoseth**: Prueba 2 (Clúster Fragmentado)
- **Ángel**: Prueba 3 (Operación Cafeína)
- **Arturo**: Prueba 4 (Operación Repuestos)
- **Eloy**: Prueba 5 (Protocolo Espejo)
- **Miguel**: Prueba 6 (Sobrecarga del Sistema)
- **Nacho**: Prueba 7 (Búsqueda de códigos QR)
- **Coordinación General**: Sistema de inscripción, ranking, administración

---

## 📄 Licencia

Este proyecto es de código abierto y está disponible bajo la licencia MIT.

---

## 🎓 Contexto Educativo

**DAWSCAPE** fue desarrollado como proyecto del módulo de **Desarrollo Web en Entorno Servidor** de 1º DAW en XTART.

El objetivo era crear una aplicación web completa que integrara:
- ✅ Backend robusto con Laravel
- ✅ Base de datos relacional compleja
- ✅ Autenticación y autorización
- ✅ Validación de formularios
- ✅ Generación de archivos (PDF, QR)
- ✅ WebSockets y tiempo real
- ✅ Responsive design
- ✅ Integración con el mundo físico

---

## 📞 Contacto y Soporte

Para dudas, sugerencias o reportar bugs:

- **GitHub Issues**: [https://github.com/xtart1DAW/DAWSCAPE/issues](https://github.com/xtart1DAW/DAWSCAPE/issues)
- **Email**: [Tu email de contacto]

---

<p align="center">
  <strong>Desarrollado con ❤️ por el equipo de 1º DAW</strong><br>
  <em>Proyecto Educativo - XTART - Curso 2025/2026</em>
</p>

---

**¿Listo para escapar? 🚀**
