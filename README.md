# DAWSCAPE: Educational Escape Room

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

DAWSCAPE es un **Escape Room Educativo interactivo** diseñado para dinamizar sesiones de formación combinando retos físicos en el mundo real con una potente aplicación web, integrando enigmas lógicos, escaneo de Códigos QR, y tableros de puntuación en tiempo real.

El juego sitúa a los alumnos en un escenario donde deben burlar el cortafuegos de un edificio corporativo resolviendo una serie de pruebas lineales y dinámicas para escapar antes de que el contador de seguridad (40 minutos) llegue a cero.

---

## 🚀 1. Descripción General del Flujo

1. **Pantalla de Inicio y Login**: Los alumnos acceden a la aplicación y registran el nombre de su equipo y su clase (para las mecánicas de ranking). Una vez entran, el temporizador global arranca en el servidor.
2. **Desarrollo Lineal de Pruebas**: Los equipos se enfrentan a pruebas secuenciales (1 a 7). Cada prueba contiene un desafío específico (lógica, matemáticas, geografía, deducción).
3. **Integración con QRs Físicos**: Las Pruebas 3, 4 y 5 invitan al jugador a levantarse del asiento, explorar el aula o edificio y escanear códigos QR que están escondidos físicamente en máquinas de café, taquillas u otros lugares reales.
4. **Validación Segura**: Cada respuesta introducida en la interfaz (simulando una terminal hacker) se valida silenciosamente en el backend contra la base de datos (omitiendo espacios, mayúsculas y acentos automáticamnete). Las pistas no bloquean el juego, pero añaden penalizaciones de tiempo invisibles.
5. **Cortafuegos Final**: La Prueba 7 inyecta aleatoriamente al cliente 5 acertijos o preguntas extraídas de una base de datos gestionada por el profesor.
6. **Reporte Final**: Una vez superada la última barrera, la app congela sus cronómetros y emite su Informe de Rendimiento para que los jugadores observen cuánto tiempo les costó cada reto.

---

## 🧩 2. Inventario Oficial de Pruebas y Respuestas

Cada prueba fue desarrollada por un miembro distinto. A continuación la ruta canónica y las llaves maestras:

| Prueba / Subsistema                  | Autor   | Tipo de Reto             | Master Key (Respuesta Esperada)         |
| ------------------------------------ | ------- | ------------------------ | --------------------------------------- |
| **Prueba 1: Secuencia Lógica**       | Mario   | Digital                  | `115, 16, 32, 14, 5x0lh8bx100127t, 201` |
| **Prueba 2: El Clúster Fragmentado** | Yoseth  | Lógico / Deducción       | `ROCIO`                                 |
| **Prueba 3: Operación Cafeína**      | Angel   | Físico (Código QR)       | `124` _(botones+110+5)_                 |
| **Prueba 4: Operación Repuestos**    | Arturo  | Físico (Código QR)       | `141` _(filas+nº prod+Oasis)_           |
| **Prueba 5: Protocolo Espejo**       | Eloy    | Físico (Código QR)       | `ZASIELX`                               |
| **Prueba 6: Sobrecarga del Sistema** | Miguel  | Digital                  | -                                       |
| **Prueba 7: Cortafuegos Dinámico**   | Creador | Preguntas Variables BBDD | _(Aleatorio según B. de Preguntas)_     |

---

## 🛠️ 3. Stack Tecnológico

DAWSCAPE se apoya en un stack moderno de backend y estilos híbridos:

- **Backend / Framework**: Laravel (PHP 8+)
- **Base de Datos**: MySQL (Eloquent ORM)
- **Motor de Plantillas**: Laravel Blade
- **Estilos UI**: Tailwind CSS (CDN/Vite) combinado con CSS Vainilla (`Glassmorphism` themes) y Google Fonts (Orbitron / Share Tech Mono).
- **Generación de QRs**: `simplesoftwareio/simple-qrcode`.
- **Generación de PDFs**: `barryvdh/laravel-dompdf` (Para exportar la clasificación).

---

## 💾 4. Instalación y Despliegue

### Requisitos Previos

- PHP >= 8.2
- Composer
- MySQL o MariaDB
- Node.js & NPM

### Instrucciones en Local (Desarrollo)

1. **Clonar Repositorio**: `git clone <repo_url>`
2. **Instalar Dependencias de PHP**: `composer install`
3. **Instalar Dependencias de Node**: `npm install`
4. **Configurar Entorno**: Renombrar `.env.example` a `.env`.
5. **BBDD**: Crear una base de datos local y configurar las credenciales en el `.env` (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).
6. **Generar Clave de APP**: Ejecutar `php artisan key:generate`.
7. **Migrar e Iniciar Tablas**: Ejecutar `php artisan migrate:fresh --seed`. Esto inyectará el administrador por defecto y el banco de preguntas inicial.
8. **Generar Assets**: `npm run build`
9. **Desplegar Servidor Local**: `php artisan serve`.

### Instrucciones en Producción (Hosting Común)

Repite los pasos anteriores vía SSH, pero asegurándote de:

- Configurar adecuadamente a `production` y cambiar `APP_DEBUG=false`.
- Habilitar las extensiones de PHP requeridas: **GD** (Para QRs y PDF) e **Imagick** si se demanda en dependencias secundarias.
- Enlazar la carpeta Storage de cara al público: `php artisan storage:link`.
- Borrar y regenerar caché: `php artisan optimize:clear` y `php artisan optimize`.

---

## 🏢 5. Panel de Control del Game Master (Administrador)

Puedes acceder al cuartel general como profesor dirigiéndote a `/login` e ingresando las credenciales predeterminadas sembradas en base de datos.

Este potente Dashboard ofrece:

- **Monitorización en Tiempo Real**: Tarjetas KPI que muestran el total de equipos en juego, cuántos han colapsado (timeout) y el tiempo promedio de toda la clase.
- **Gráficos (Chart.js)**: Distribución visual del progreso (cuántos equipos están estancados en cada fase).
- **Control Total**: Una tabla donde puedes eliminar a equipos (si iniciaron sesión sin querer), o seguir el progreso al milímetro en formato "Live".
- **Exportación a Papel**: El botón `[Exportar Top 20]` compilará un documento PDF listo para imprimir para entregar insignias físicas o publicar en el corcho del aula.
- **Banco de Preguntas Remoto**: Sección dedicada para meter, sacar y banear (ocultar) acertijos específicos de cultura general que impactan directamente a la aleatoriedad de la `Prueba 7`.
- **Testing Rápido (Bypass)**: Una columna lateral lista los botones de prueba para ejecutar cada uno de los retos desde el punto de vista del usuario _sin necesidad de escanear los QRs por los pasillos_, ideal para comprobar que la lógica o las resoluciones correctas operan adecuadamente tras actualizar el juego.

---

## 📸 6. El Sistema de QRs Dinámicos

Para las pruebas semi-físicas (Pruebas 3, 4 y 5) Dawscape evita hardcodear las rutas en el código:

1. El profesor abre la vista del "Gestor de QRs" (`/admin/qrs`).
2. Se rellena un formulario básico ("Nombre de pista", y "Fase obligatoria").
3. El sistema genera instantáneamente una imagen limpia QR vinculada a un slug indetectable (Ej: `dawscape.fun/qr/maquina-de-snacks-60abcde123`).
4. El profesor puede descargar la imagen SVG del QR pulsando e imprimirla en hojas perforables, colgándolas por el instituto.
5. **Enrutamiento Inteligente**: Si un alumno intenta listar todos los QRs de golpe de forma ilícita, el sistema validará si su estado en la tabla `partidas` coincide o se "merecen" leer ese QR ahora mismo, saltándoles un error irónico en caso de fraude ("¡Aún no estáis en esta fase!").

---

## 🌍 7. Url de Producción Pública

Actualmente DAWSCAPE opera desde el servidor en vivo:
🔗 [**https://dawscape.fun**](https://dawscape.fun)

_(También accesible en la ruta `/top10` para proyectores o televisiones en la sala principal habilitados para emitir la música de tensión y rotación asíncrona de los podios durante el turno del juego)._
