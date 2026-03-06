# Análisis Arquitectónico - DAWSCAPE (Escape Room Educativo)

Este documento detalla la estructura, patrones y flujos de seguridad implementados en el proyecto Laravel **DAWSCAPE** tras su refactorización hacia estándares *Enterprise*.

## 1. Patrones de Diseño Implementados

### 1.1. Arquitectura MVC (Model-View-Controller)
La aplicación sigue estrictamente el patrón MVC clásico de Laravel, pero se ha saneado dividiendo responsabilidades (`Single Responsibility Principle`):
- **`JuegoController`**: Gestiona **exclusivamente** el flujo de las pruebas del jugador (renderizar vistas de puzzles) y la validación de sus respuestas correctas.
- **`AdminController`**: Gestiona el panel de control del profesorado, el borrado de equipos, el testeo (bypass) de pruebas y la exportación de ránkings a formato PDF.
- **`QrController`**: Encapsula toda la lógica de creación dinámica de códigos QR en BBDD, eliminaciones y el enrutamiento inteligente cuando un alumno escanea una pista.

### 1.2. Patrón de Servicios (Service Layer)
- **`GameTimerService`**: Se extrajo la lógica de negocio repetitiva relacionada con el cálculo del tiempo de las partidas y las sesiones.
  - *Ventaja:* El controlador no necesita saber **cómo** se calcula el tiempo (BBDD vs Sesión), solo le pregunta al servicio `obtenerTiempoTranscurrido()`. Permite inyección de dependencias (`DI`) limpia en los constructores.

### 1.3. Patrón FormRequest
- En lugar de inyectar las reglas de validación de los formularios directamente dentro de los Controladores (mezclando lógica HTTP con lógica de Dominio), se han extraído a clases dedicadas en `App\Http\Requests\`:
  - `EmpezarPartidaRequest`
  - `ValidarPrueba1Request` ... `ValidarPrueba6Request`
  - `GuardarQrRequest`
- *Ventaja:* El controlador se asegura de que, si el flujo entra a la función, la información (checksums, secuencias, tipos de datos) ya está purgada, verificada y libre de malformaciones o inyecciones simples.

---

## 2. Flujos de Seguridad y Anti-Trampas

### 2.1. Middleware de Autenticación (AdminAuth)
El panel de control (`/admin/*`) está protegido por un Middleware `AdminAuth`.
- Utiliza **HTTP Basic Authentication** configurado mediante las variables base del entorno (`.env`):
  - `ADMIN_USER`
  - `ADMIN_PASSWORD`
- Ya no existen contraseñas duras ("*hardcoded*") en la lógica del enrutador.

### 2.2. Middleware Predictivo de Cascada (`CheckFase`)
Para evitar que un equipo se salte Pruebas escribiendo la URL manualmente (ej. ir directo a `/prueba6migue`), se utiliza el middleware custom `check.fase`.
- Registrado en `bootstrap/app.php` y enrutado en bloque en `routes/web.php`.
- *Ventaja:* En lugar de ensuciar el código del controlador verificando si la sesión tiene la fase superada (`if (!session()->has...)`), el Middleware aborta la petición a nivel de *Route*, redirigiendo automáticamente al infractor al puzzle en el que realmente debían estar.

### 2.3. Blindaje de Tiempo (Server-Side Timeline)
Las aplicaciones web para estudiantes a menudo sufren trampas alterando el reloj de JavaScript en el navegador.
- DAWSCAPE ignora cualquier tiempo enviado por el navegador.
- Al iniciar, la BBDD estampa el tiempo en la columna `inicio_juego` usando `Carbon\Carbon::now()`.
- Cuando el equipo completa la última prueba, el servidor calcula la diferencia absoluta (`diffInSeconds`) entre su timestamp y la hora en el que el servidor recibió la petición de victoria. **Es incrackeable desde el Front-End**.

### 2.4. Autoridad del Backend en Pruebas
Pruebas dinámicas en JavaScript (como el *"Simon Dice"* de `prueba6migue`) envían *Payloads* POST con la secuencia completa introducida. El `JuegoController` comprueba nativamente en PHP que el array recibido es correcto. No confía simplemente en un `{"completed": true}` que un alumno avispado podría falsificar por Fetch/Postman.

---

## 3. Optimización de Frontend (Blade Layouts)

- Se diseñó una estructura madre en `resources/views/layouts/app.blade.php`.
- Todas las vistas heredan de esta base (`@extends('layouts.app')`).
- *Ventaja:* Centralizar el `<head>`, la invocación de metaetiquetas de CSRF (necesarias para las peticiones seguras de AJAX), la carga de tipografías importadas de Google Fonts y la importación de `Bootstrap`. Cualquier cambio de interfaz masivo (ej. Cambio de logos inter-escolar) requiere edición de 1 único archivo en lugar de 10. Se inyectaron firmas discretas de auditoría ("Desarrollado por Alumnos DAW").

---

## Conclusión

La aplicación DAWSCAPE presenta un diseño resiliente para el entorno académico. Separa la lógica de presentación de la capa matemática, valida agresivamente todos los *Inputs* salientes y centraliza la protección de acceso, formando un producto final seguro, mantenible e ideal para crecer modularmente con más pruebas de Escape Room en el futuro.
