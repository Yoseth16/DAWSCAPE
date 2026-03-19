# 🎮 DAWSCAPE — Escape Room Educativo Interactivo

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind">
  <img src="https://img.shields.io/badge/Vite-7.x-646CFF?style=for-the-badge&logo=vite&logoColor=white" alt="Vite">
  <img src="https://img.shields.io/badge/WebSockets-Reverb-4A154B?style=for-the-badge&logo=websocket&logoColor=white" alt="WebSockets">
</p>

---

## 📖 Descripción

**DAWSCAPE** es un **Escape Room Educativo Digital** desarrollado como proyecto de **1º DAW (Desarrollo de Aplicaciones Web)** que combina desafíos lógicos, exploración física del entorno real mediante códigos QR, y un sistema de ranking en tiempo real.

Los equipos deben resolver **9 pruebas secuenciales** que incluyen acertijos matemáticos, lógica, deducción, Simon Says cooperativo, preguntas de cultura general y búsqueda de códigos QR ocultos en el instituto. Todo ello mientras un cronómetro global cuenta el tiempo transcurrido y penaliza el uso de pistas.

El proyecto integra tecnologías modernas como **Laravel Reverb** para actualizaciones en tiempo real, **generación dinámica de QR**, **sistema de logros**, un **AudioManager global** con música por prueba y efectos de sonido, y un **panel de administración completo** para gestionar el evento.

---

## ✨ Características Principales

### 🎯 Sistema de Juego
- **9 Pruebas Secuenciales**: Digitales, físicas (QR) y cooperativas, cada una diseñada por un miembro del equipo
- **Cronómetro Global**: 90 minutos máximo con persistencia en base de datos
- **Penalización de Tiempo en BBDD**: El uso de pistas añade **+8 minutos** al crono; fallos en Simon Says añaden **+30 segundos** por intento
- **Escáner de Códigos QR Integrado**: Lectura de QR desde la cámara del móvil para desbloquear pruebas físicas (Pruebas 3, 4, 5 y 7)
- **Validación Inteligente**: Normalización automática de respuestas (sin acentos, mayúsculas, espacios)
- **Sistema de Logros**: Medallas desbloqueables (⚡ Speedrunner Ninja, 🧠 Cerebro Intacto, 💻 Hackerman)

### 🎵 Sistema de Audio Global (AudioManager)
- **Música dinámica por prueba**: Cada fase tiene su propia banda sonora (8-bit RPG, Synthwave, etc.)
- **Efectos de sonido (SFX)**: Feedback auditivo en aciertos, errores, pistas y navegación
- **Reproductor mini visible**: Control de play/pause y volumen persistente vía `localStorage`
- **Audio ducking**: La música baja automáticamente al reproducir SFX
- **Autoplay inteligente**: Detección de bloqueo del navegador con banner de activación suave y fade-in de 2 s
- **Instancia global**: `window.audioManager` disponible en todas las vistas

### 🏆 Live Ranking — Top 10 en Tiempo Real (WebSockets)
- **Actualización instantánea vía Pusher/Reverb**: Cada vez que un equipo completa una prueba, el ranking se actualiza en vivo en todos los clientes conectados
- **Evento `Top10Updated`** emitido con `ShouldBroadcastNow` en canal público `top10`
- **Podio animado**: Visualización espectacular de los 3 primeros puestos con cálculo de gap
- **Posición bloqueada**: Los equipos que finalizan mantienen su ranking fijo
- **Celebración automática**: Confetti y animaciones al completar el escape
- **Exportación PDF**: Generación del ranking Top 20 para impresión (`barryvdh/laravel-dompdf`)

### 🔐 Sistema de Inscripción
- **Registro previo de equipos** con validación de correo único y nombre de equipo único
- **Código QR descargable** por equipo (token de 32 caracteres)
- **Código de acceso de 6 caracteres** alfanuméricos enviado por email vía Resend
- **Validación triple**: Correo + Nombre de Equipo + Código de Acceso

### 👨‍💼 Panel de Administración
- **Dashboard en tiempo real**: Monitorización de todos los equipos activos y pendientes de inicio
- **Métricas KPI**: Total inscritos, pendientes, en progreso, completados, tiempo promedio
- **Gráficos interactivos**: Distribución por fases con Chart.js
- **Gestión de QRs dinámicos**: Creación, edición y descarga de códigos QR para ubicaciones físicas
- **Testing directo**: Rutas de bypass para que el administrador pueda probar cada prueba individualmente
- **Logs de equipo**: Auditoría completa de acciones por equipo
- **Gestión de Valoraciones**: Interfaz para consultar el feedback (estrellas) dejado por los equipos al terminar la partida.
- **Diploma Interactivo**: Previsualización y entrega de diploma digital (con animaciones personalizadas) al finalizar el juego.

---

## 🧩 Inventario de Pruebas

| # | Nombre | Autor | Tipo | Descripción |
|---|--------|-------|------|-------------|
| **1** | Criptografía | Mario | Digital | 6 acertijos lógicos sobre el aula de informática |
| **2** | Servidor Seguro | Yoseth | Físico/Digital | Buscar 5 letras ocultas y formar un anagrama |
| **3** | Operación Cafeína | Ángel | Físico (QR) | Escanear QR en la máquina de café y calcular un checksum |
| **4** | Operación Repuestos | Arturo | Físico (QR) | Localizar QR en máquina de snacks y calcular checksum |
| **5** | Protocolo Espejo | Eloy | Físico (QR) | Descifrar palabra invertida en taquillas (alfabeto espejo) |
| **6** | Sobrecarga del Sistema | Mígue | Digital | Simon Says cooperativo de 15 pasos con penalización de 30 s/fallo |
| **7** | El Códice Oculto | Nacho | Físico (QR) | Candado digital de 4 dígitos con acertijos en ubicaciones físicas |
| **8** | Cultura General | Dinámico | Digital | 5 preguntas aleatorias del banco de preguntas del admin |
| **9** | Juego de Pistas | Miguel Ángel | Mixto | 5 preguntas secuenciales que revelan letras → contraseña final |

### 🎖️ Sistema de Logros

| Logro | Condición |
|-------|-----------|
| ⚡ **Speedrunner Ninja** | Completar Prueba 1 en menos de 2 minutos |
| 🧠 **Cerebro Intacto** | Finalizar el juego sin usar ninguna pista |
| 💻 **Hackerman** | Escribir `sudo rm -rf` en Prueba 5 |

---

## 🛠️ Stack Tecnológico

### Backend

| Componente | Tecnología |
|------------|------------|
| Framework | Laravel 12.x |
| Lenguaje | PHP 8.2+ |
| Base de Datos | MySQL 8.0 / MariaDB 10.3+ |
| ORM | Eloquent |
| WebSockets | Laravel Reverb + Pusher JS |
| Broadcasting | Evento `Top10Updated` (canal `top10`) |
| Generación QR | `simplesoftwareio/simple-qrcode` |
| Generación PDF | `barryvdh/laravel-dompdf` |
| Email | Resend (vía Laravel Mail) |
| Autenticación | Laravel Breeze |

### Frontend

| Componente | Tecnología |
|------------|------------|
| Motor de Plantillas | Blade |
| CSS Framework | Tailwind CSS 3.x |
| Efectos CSS | Glassmorphism, animaciones CSS3 |
| JavaScript | ES6+ (Vanilla JS) + Alpine.js |
| Audio | AudioManager personalizado (`audio-manager.js`) |
| Gráficos | Chart.js |
| Captura de Diplomas | html2canvas |
| Iconos | Phosphor Icons |
| Fuentes | Google Fonts (Orbitron, Share Tech Mono, Outfit) |
| WebSocket Client | Laravel Echo + Pusher JS |

### Herramientas de Desarrollo

| Herramienta | Versión |
|-------------|---------|
| Build Tool | Vite 7.x |
| PHP Packages | Composer |
| JS Packages | NPM |
| Linter | Laravel Pint |
| Testing | PHPUnit 11.x |
| Control de Versiones | Git |

---

## 📦 Instalación

### Requisitos Previos

- PHP >= 8.2
- Composer
- Node.js >= 18.x y NPM
- MySQL >= 8.0 o MariaDB >= 10.3
- Extensiones PHP: `gd`, `mbstring`, `pdo_mysql`, `zip`, `xml`

### Paso a Paso

```bash
# 1. Clonar el repositorio
git clone https://github.com/xtart1DAW/DAWSCAPE.git
cd DAWSCAPE

# 2. Instalar dependencias de PHP
composer install

# 3. Instalar dependencias de Node.js (Vite, Tailwind, Echo, Pusher JS…)
npm install

# 4. Copiar y configurar el entorno
cp .env.example .env
```

Editar `.env` con la configuración de base de datos, audio y WebSockets:

```env
# ── Base de datos ──
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dawscape
DB_USERNAME=root
DB_PASSWORD=

# ── WebSockets (Reverb) ──
BROADCAST_CONNECTION=reverb
REVERB_APP_ID=dawscape
REVERB_APP_KEY=tu-clave-secreta
REVERB_APP_SECRET=tu-secreto
REVERB_HOST=localhost
REVERB_PORT=8080
REVERB_SCHEME=http

# ── Pusher (si se usa en producción) ──
PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=eu

# ── Email (Resend) ──
MAIL_MAILER=resend
RESEND_API_KEY=re_xxxxxxxx
MAIL_FROM_ADDRESS=dawscape@tudominio.com
MAIL_FROM_NAME="DAWSCAPE"

# ── Vite (producción) ──
VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

```bash
# 5. Generar clave de aplicación
php artisan key:generate

# 6. Crear base de datos (si no existe)
mysql -u root -e "CREATE DATABASE IF NOT EXISTS dawscape CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 7. Ejecutar migraciones y seeders
php artisan migrate:fresh --seed
#    → Crea tablas, admin por defecto, banco de preguntas dinámicas, QRs de ejemplo

# 8. Enlazar almacenamiento público (para QRs generados)
php artisan storage:link

# 9. Compilar assets con Vite
npm run build
#    Para desarrollo con hot-reload:
#    npm run dev

# 10. (Opcional) Iniciar servidor WebSocket
php artisan reverb:start

# 11. Iniciar servidor de desarrollo
php artisan serve
```

La aplicación estará disponible en `http://localhost:8000`.

### Credenciales por Defecto

| Rol | Email | Contraseña |
|-----|-------|------------|
| Administrador | `1daw` | `1daw` |

> ⚠️ **Cambiar estas credenciales en producción.**

---

## 🚀 Despliegue en Producción

```bash
# Optimizar
composer install --optimize-autoloader --no-dev
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# Permisos (Linux)
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

Variables `.env` de producción clave:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://dawscape.fun
```

El proyecto incluye `deploy.sh` para automatizar el despliegue:

```bash
chmod +x deploy.sh
./deploy.sh
```

---

## 🎮 Uso del Sistema

### Para Equipos (Jugadores)

1. **Inscripción previa** → `/inscripcion` — Registrar equipo con correo y nombre
2. **Recibir código** — Código de acceso de 6 caracteres por email (Resend)
3. **Día del evento** → `/` — Introducir credenciales y empezar partida
4. **Resolver 9 pruebas** — Seguir el orden secuencial, escanear QRs y colaborar
5. **Ver resultados** — Pantalla final con tiempo, logros y posición en ranking

### Para Administradores

1. **Login** → `/login` con credenciales de administrador
2. **Dashboard** → `/dashboard` — Monitorizar equipos en tiempo real
3. **Gestión de QRs** → `/admin/qrs` — Crear y descargar QRs para ubicaciones físicas
4. **Exportar ranking** → Generar PDF del Top 20
5. **Proyección pública** → `/top10` — Mostrar en pantalla grande para el público
6. **Testing de pruebas** → `/prueba/prueba{N}` — Probar cada prueba individualmente

---

## 📁 Estructura del Proyecto

```
DAWSCAPE/
├── app/
│   ├── Events/                  # Eventos de Broadcasting (Top10Updated, PanicToggled…)
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AdminController      # Dashboard, KPIs, gestión, exportación PDF
│   │   │   ├── InscripcionController # Registro previo, QR de equipo, email
│   │   │   ├── JuegoController       # Lógica de las 9 pruebas + pistas
│   │   │   ├── QrController          # Escaneo y validación de QRs físicos
│   │   │   └── Top10Controller       # Ranking en tiempo real
│   │   ├── Middleware/          # CheckFase (anti-salto de pruebas)
│   │   └── Requests/           # Form Requests de validación
│   ├── Mail/                    # Mailable: CodigoAccesoInscripcion
│   ├── Models/                  # Partida, Inscripcion, HitoFase, Qr, PreguntaDinamica…
│   ├── Services/
│   │   ├── AdminStatsService    # Cálculos de métricas para el dashboard
│   │   └── GameTimerService     # Cronómetro con pausa, penalización y timeout
│   └── View/Components/        # Componentes Blade reutilizables
├── config/
│   ├── escape.php               # Respuestas correctas, pistas y penalizaciones
│   └── fases.php                # Definición de fases, nombres y pesos para ranking
├── database/
│   ├── migrations/              # Migraciones (partidas, inscripciones, hitos, QRs…)
│   └── seeders/                 # Datos iniciales (admin, preguntas dinámicas, QRs)
├── public/
│   └── sonido/                  # Archivos de audio (.mp3) por prueba y SFX
├── resources/
│   ├── css/                     # Estilos personalizados
│   ├── js/
│   │   ├── audio-manager.js     # Clase AudioManager (instancia global)
│   │   └── sfx-integration.js   # Dispara SFX en eventos de validación
│   └── views/
│       ├── admin/               # Vistas del panel de administración
│       ├── pruebas/             # Vistas de las 9 pruebas
│       ├── qr/                  # Vistas de validación de QR (café, snacks, taquillas)
│       └── components/          # game-audio-setup, music-player, timer…
├── routes/
│   ├── web.php                  # Rutas principales (público + auth)
│   ├── channels.php             # Canales de Broadcasting (top10)
│   └── auth.php                 # Autenticación (Breeze)
└── storage/
    └── app/public/qrs/          # QRs generados dinámicamente
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
            ['16', 'dieciseis'],
            // ...
        ],
        'prueba3' => 103,
        // ...
    ],
    'pistas' => [
        1 => ['texto' => '...', 'imagen' => 'mario.png'],
        // ...
    ],
    'penalizacion_pista' => 480, // segundos (8 minutos)
];
```

### Personalizar Tiempo Máximo

En `app/Services/GameTimerService.php`:

```php
const MAX_TIEMPO_MINUTOS = 90; // Cambiar según necesidad
```

### Añadir Preguntas Dinámicas (Prueba 8)

Desde el panel de administración o mediante seeder:

```php
PreguntaDinamica::create([
    'enunciado' => '¿Cuál es la capital de Francia?',
    'respuesta_correcta' => 'París',
    'activa' => true,
]);
```

---

## 🎵 Sistema de Audio — Referencia Técnica

### Archivos de Audio

Todos los archivos `.mp3` se ubican en `public/sonido/`:

| Archivo | Uso |
|---------|-----|
| `MusicaMenuLogin.mp3` | Pantalla de inicio / login |
| `Prueba1-suspiro.mp3` → `Prueba10-DanzaKuduroRPG.mp3` | Música de fondo de cada prueba |
| `FinalVictoria.mp3` | Pantalla de victoria final |
| `Acierto.mp3` | SFX: respuesta correcta |
| `Error.mp3` | SFX: respuesta incorrecta |
| `Boton-Pista.mp3` | SFX: botón de pista |

### Arquitectura

```
resources/js/audio-manager.js          ← Clase AudioManager (window.audioManager)
resources/js/sfx-integration.js        ← Dispara SFX en eventos de validación
resources/views/components/
  ├── game-audio-setup.blade.php       ← Inicialización por prueba + autoplay unlock
  └── music-player.blade.php           ← Reproductor mini (play/pause, volumen)
```

### Añadir música a una prueba nueva

1. Colocar el `.mp3` en `public/sonido/`
2. Registrar la pista en `resources/js/audio-manager.js`:
   ```js
   this.tracks = {
     miPrueba: { file: 'MiAudio.mp3', title: 'Título visible' },
   };
   ```
3. Incluir en la vista Blade:
   ```blade
   @include('components.game-audio-setup', ['trackKey' => 'miPrueba'])
   ```

---

## 📊 Base de Datos

### Tablas Principales

| Tabla | Descripción |
|-------|-------------|
| `inscripcions` | Equipos registrados antes del evento (correo, nombre, código acceso, QR token) |
| `partidas` | Sesiones de juego activas (UUID, fase, tiempo, penalizaciones, logros, combinación códice) |
| `hitos_fases` | Registro de progreso por fase con timestamp de completado |
| `pistas_usadas` | Control de pistas solicitadas por cada equipo |
| `log_juegos` | Auditoría completa de acciones |
| `qrs` | Códigos QR dinámicos (ubicación, fase requerida, código secreto) |
| `pregunta_dinamicas` | Banco de preguntas para Prueba 8 (gestionado por admin) |
| `valoraciones` | Feedback y puntuación enviada por los equipos al completar el escape |

### Sistema de Penalización de Tiempo

El tiempo final de un equipo se calcula en `GameTimerService`:

```
Tiempo = (ahora - inicio_juego - tiempo_pausado) + penalizacion_segundos
```

| Evento | Penalización |
|--------|-------------|
| Uso de pista | +480 s (8 min) |
| Fallo en Simon Says (Prueba 6) | +30 s por intento |
| Timeout (> 90 min) | Partida finalizada con estado `FINALIZADO_TIMEOUT` |

---

## 🐛 Solución de Problemas

| Problema | Solución |
|----------|----------|
| `Class 'SimpleSoftwareIO\...' not found` | `composer require simplesoftwareio/simple-qrcode && php artisan config:clear` |
| `SQLSTATE[HY000] [2002] Connection refused` | Verificar que MySQL esté corriendo y credenciales en `.env` |
| QRs no se generan | `php artisan storage:link && chmod -R 775 storage` |
| WebSockets no funcionan | Verificar que `php artisan reverb:start` esté corriendo y puerto 8080 abierto |
| Audio no suena / `audioManager is undefined` | Ejecutar script `fix-audio-server.sh` (Linux) o `fix-audio-server.ps1` (Windows) para regenerar assets con Vite. |
| Error 404 en archivos de audio | Asegurar que `window.AUDIO_BASE_URL` se inyecte con `asset('sonido')` desde Blade |

---

## 🔐 Guía de Soluciones (Para Desarrolladores y Administradores)

> **⚠️ SPOILERS**: Esta sección contiene las respuestas correctas de todas las pruebas. Está destinada exclusivamente a desarrolladores y administradores para facilitar el testing y la depuración.

<details>
<summary><strong>Prueba 1 — Criptografía (Mario)</strong></summary>

**Tipo**: Digital — 6 acertijos lógicos sobre el aula de informática.

| Acertijo | Respuestas aceptadas |
|----------|---------------------|
| 1 | `115`, `aula 115` |
| 2 | `16`, `dieciseis` |
| 3 | `32`, `treinta y dos` |
| 4 | `14`, `catorce` |
| 5 | `5x0lh8bx100127t` |
| 6 | `201`, `doscientos uno` |

**Validación**: Las 6 respuestas deben proporcionarse en orden. No distingue mayúsculas/minúsculas ni acentos.

**Logro**: ⚡ *Speedrunner Ninja* — completar en menos de 120 segundos.

**Pista** (penalización +8 min): *"El guardián silencioso vigila desde lo alto. Su identidad está grabada en metal, visible para quien mire hacia arriba. Los pares se multiplican en este santuario del conocimiento."*

</details>

---

<details>
<summary><strong>Prueba 2 — Servidor Seguro / El Clúster Fragmentado (Yoseth)</strong></summary>

**Tipo**: Físico/Digital — Buscar 5 letras ocultas en máquinas del edificio y formar un anagrama.

**Respuesta correcta**: `rocio`

**Validación**: Se normaliza a minúsculas, se eliminan acentos (`Normalizer::FORM_D`) y se recortan espacios.

**Pista** (penalización +8 min): *"Cinco fragmentos dispersos por el edificio. Cada máquina guarda una letra. El nombre de una mujer emerge del caos cuando ordenas el desorden."*

</details>

---

<details>
<summary><strong>Prueba 3 — Operación Cafeína (Ángel)</strong></summary>

**Tipo**: Físico (QR) — Escanear QR en la máquina de café y resolver un cálculo.

**Respuesta correcta**: `103`

**Desglose del checksum**: 18 botones + 80 céntimos + 5 de azúcar = **103**

**Código QR de acceso**: `ACCESO_FASE_3` (texto plano) o QR dinámico con `fase_requerida = "Prueba 3 Angel"`

**Validación**: La entrada se convierte a entero y se compara con el valor configurado.

**Pista** (penalización +8 min): *"Tres elementos convergen en un único número: la interfaz táctil, el tributo monetario y la dulzura medida. La suma de estos tres revela el camino."*

</details>

---

<details>
<summary><strong>Prueba 4 — Operación Repuestos / Oasis Snacks (Arturo)</strong></summary>

**Tipo**: Físico (QR) — Localizar QR en la máquina de snacks y calcular checksum.

**Respuesta correcta**: `141`

**Desglose del checksum**: 6 filas + código 128 del café + 7 items en A1 = **141**

**Código QR de acceso**: `ACCESO_FASE_4` (texto plano) o QR dinámico con `fase_requerida = "Prueba 4 Arturo"`

**Validación**: La entrada se convierte a entero.

**Pista** (penalización +8 min): *"La estructura vertical, el código del elixir oscuro y la primera columna del alfabeto. Tres valores que, unidos, desbloquean el siguiente nivel."*

</details>

---

<details>
<summary><strong>Prueba 5 — Protocolo Espejo (Eloy)</strong></summary>

**Tipo**: Físico (QR) — Descifrar una palabra invertida usando el alfabeto espejo en las taquillas.

**Respuesta correcta**: `zasielx`

**Código QR de acceso**: `ACCESO_FASE_5` (texto plano) o QR dinámico con `fase_requerida = "Prueba 5 Eloy"`

**Validación**: La entrada se convierte a minúsculas y se recortan espacios.

**Easter Egg**: Escribir `sudo rm -rf` desbloquea el logro 💻 *Hackerman*.

**Pista** (penalización +8 min): *"El mundo al revés. Donde A se encuentra con Z, y la primera letra del alfabeto besa a la última. 64 nodos metálicos guardan el secreto invertido."*

</details>

---

<details>
<summary><strong>Prueba 6 — Sobrecarga del Sistema / Simon Says (Mígue)</strong></summary>

**Tipo**: Digital cooperativo — Simon Says de 15 pasos.

**Lógica**: El servidor genera una secuencia aleatoria de 15 valores (0–3), representando 4 cuadrantes/botones. Se almacena en `session('prueba6_sequence')`. Los jugadores deben reproducir la secuencia completa en orden.

**No hay respuesta fija**: La secuencia es aleatoria y diferente para cada equipo.

**Penalización por fallo**: +30 segundos por cada intento fallido (vía endpoint `POST /prueba6/penalizar`).

**Logro**: 🧠 *Cerebro Intacto* — completar el juego entero sin usar ninguna pista.

**Pista** (penalización +8 min): *"La memoria es frágil cuando trabaja sola. Dividid la carga, compartid el patrón. Cuatro cuadrantes, una mente colectiva. La sincronización es vuestra única salvación."*

</details>

---

<details>
<summary><strong>Prueba 7 — El Códice Oculto (Nacho)</strong></summary>

**Tipo**: Físico (QR) — Candado digital de 4 dígitos con acertijos en ubicaciones del instituto.

**Lógica**: Se seleccionan 4 dígitos únicos aleatorios (0–9) por equipo, almacenados en `partida.combinacion_codice`. Cada dígito tiene un QR físico con un acertijo que revela el número.

| Dígito | Ubicación del QR | Pista del acertijo |
|--------|------------------|--------------------|
| 0 | Mostrador de secretaría | No valgo absolutamente nada si me pones a la izquierda, pero multiplico tu riqueza si me pones a la derecha. Fui el último dígito en inventarse, nacido en la India para representar la nada y el vacío absoluto. |
| 1 | Mesa de Nescafé cafetería | Soy el único número cuyo cuadrado, cubo y raíz son exactamente idénticos a mí mismo. En la tabla periódica, corono al elemento más ligero y abundante de todo el universo. |
| 2 | Aseos de cafetería | En informática, soy la base fundamental de todo el lenguaje que entienden las máquinas. Soy la excepción a la regla: el único número par que tiene el honor de ser primo. |
| 3 | Extintor final del pasillo junto a la cristalera del LAB 103 | Newton necesitó exactamente este número de leyes para explicar el movimiento del universo. Si quieres definir un plano geométrico perfecto en el espacio sin que cojee, me necesitas estrictamente. |
| 4 | Clase vacía esquina donde las escaleras interiores que no funcionan | Lato sin descanso en tu pecho gracias a mis cavidades anatómicas. En la genética, soy el total de letras químicas (A, C, G, T) que escriben el libro de la vida. |
| 5 | Máquinas de vending planta 1 junto a los aseos de emergencias en el Oasis | En la geometría clásica, represento el total de sólidos platónicos perfectos que existen. En el emblema de los Juegos Olímpicos, doy forma a los anillos que representan las partes habitadas del mundo. |
| 6 | Puertas "X EMERGENCIAS+PROTECCION CIVIL" junto al cuadro de luces | Soy el primer número perfecto, la suma de mis divisores exactos (1, 2 y 3) te dará mi valor. En química, soy el cimiento de la vida, el número atómico del carbono. |
| 7 | Taquillas Oasis planta 2 | Químicamente hablando, soy la neutralidad perfecta, el pH del agua pura. Anatómicamente, soy el número de vértebras cervicales que comparte un humano con una jirafa. |
| 8 | Aseos cerrados donde el cartel sin salida y la plancha amarilla que cierra el paso | En el mundo digital, formo la unidad básica de memoria (el byte) cuando agrupo a mis pequeños soldados (los bits). Soy el número de planetas reconocidos oficialmente en nuestro sistema solar. |
| 9 | Plantas de al lado de las escaleras en la planta 0 | Tengo una maldición: multiplícame por el entero que quieras; si sumas las cifras del resultado, siempre volverás a mí. Soy el total de círculos del Infierno de Dante. |

**Validación**: Los 4 dígitos deben coincidir exactamente con la combinación del equipo (el orden importa). Se cuenta cada intento en `intentos_codice`.

**Pista** (penalización +8 min): *"Cuatro dígitos escondidos en cuatro rincones del edificio. No los busquéis al azar: cada QR os dará un acertijo. Resolved el acertijo, descubrid el número, y colocadlos en el orden correcto. ¡Drift hacia la victoria!"*

</details>

---

<details>
<summary><strong>Prueba 8 — Cultura General / Cortafuegos</strong></summary>

**Tipo**: Digital — 5 preguntas aleatorias del banco de preguntas dinámicas.

**Lógica**: El administrador gestiona las preguntas desde el panel. Se seleccionan 5 preguntas activas al azar de la tabla `preguntas_dinamicas`. La validación pregunta a pregunta se realiza en el frontend; el backend verifica que el flag `completed: true` sea enviado.

**No hay respuestas fijas**: Depende del banco de preguntas configurado por el administrador.

</details>

---

<details>
<summary><strong>Prueba 9 — Juego de Pistas (Prueba Final)</strong></summary>

**Tipo**: Mixto — 7 preguntas secuenciales que revelan letras para formar una contraseña.

**Fase 1 — Preguntas y letras**:

| # | Pregunta | Respuesta | Letra |
|---|----------|-----------|-------|
| 1 | Nombre del director del centro | `eduardo` | **N** |
| 2 | ¿Cuántas clases de desarrollo web hay en total en el centro? | `1` | **H** |
| 3 | ¿Qué empresa coreana invierte en FP como XTART para promocionar sus productos? | `samsung` | **O** |
| 4 | ¿Cuántas ventanas hay en la clase de DAW? | `5` | **A** |
| 5 | ¿Cuántas plantas tiene el centro? | `3` | **C** |

**Fase 2 — Contraseña final**: Las letras recopiladas (N, H, O, A, C) forman → **`nacho`**

**Validación**: Las respuestas se normalizan (minúsculas, sin acentos, espacios colapsados). Tras 5 fallos acumulados aparece la pista: *"Piensa en lo que ves cada día en el instituto."*

**Al completar**: Se congela el tiempo, la fase pasa a `FINALIZADO` y se registran dos hitos (prueba completada + juego finalizado).

</details>

---

## 🤝 Equipo de Desarrollo

| Miembro | Contribución |
|---------|-------------|
| **Mario** | Prueba 1 — Criptografía |
| **Yoseth** | Prueba 2 — El Clúster Fragmentado |
| **Ángel** | Prueba 3 — Operación Cafeína |
| **Arturo** | Prueba 4 — Operación Repuestos |
| **Eloy** | Prueba 5 — Protocolo Espejo |
| **Mígue** | Prueba 6 — Sobrecarga del Sistema |
| **Nacho** | Prueba 7 — El Códice Oculto |
| **Miguel Ángel** | Prueba 9 — Juego de Pistas |
| **Coordinación** | Sistema de inscripción, ranking, administración, audio, infraestructura |

---

## 📄 Licencia

Este proyecto es de código abierto y está disponible bajo la **Licencia MIT**.

---

## 🎓 Contexto Educativo

**DAWSCAPE** fue desarrollado como proyecto del módulo de **Desarrollo Web en Entorno Servidor** de 1º DAW en XTART (Curso 2025/2026).

Objetivos técnicos alcanzados:

- ✅ Backend robusto con Laravel 12
- ✅ Base de datos relacional compleja (7 tablas interrelacionadas)
- ✅ Autenticación y autorización (Breeze + middleware personalizado)
- ✅ Validación de formularios con Form Requests
- ✅ Generación de archivos (PDF con DomPDF, QR con SimpleSoftwareIO)
- ✅ WebSockets y tiempo real (Reverb + Echo + Pusher JS)
- ✅ Sistema de audio global con AudioManager
- ✅ Escáner de QR integrado con cámara
- ✅ Penalización de tiempo persistente en BBDD
- ✅ Responsive design con Tailwind CSS
- ✅ Integración digital-física mediante códigos QR

---

<p align="center">
  <strong>Desarrollado con ❤️ por el equipo de 1º DAW</strong><br>
  <em>Proyecto Educativo — XTART — Curso 2025/2026</em>
</p>
