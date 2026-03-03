<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# 🕵️‍♂️ DAWSCAPE - Escape Room Educativo

> **Proyecto de Gamificación y Ciberseguridad para 1º DAW**

DAWSCAPE es una experiencia inmersiva web desarrollada en Laravel. El objetivo es poner a prueba los conocimientos de los alumnos sobre redes, protocolos web y seguridad informática (SQL Injection) en un entorno controlado y competitivo.

## 🚀 Características Técnicas

Este proyecto no es solo una web estática, cuenta con una arquitectura backend completa:

* **Motor:** Laravel 10 (PHP 8.2).
* **Base de Datos:** SQLite (Registro persistente de partidas).
* **Front-end:** Blade + Bootstrap 5 (Estética Hacker/Terminal).
* **Seguridad Implementada:**
    * **Protección CSRF:** En todos los formularios.
    * **Validación de Dominio:** Restricción estricta a correos `@estudiante.xtart.com`.
    * **Sistema Anti-Replay:** Un usuario solo puede completar el juego una vez (verificación contra BBDD).
    * **Panel de Administración:** Ruta protegida `/admin` con autenticación básica para profesores.

## 🎮 Mecánica del Juego

1.  **Login Seguro:** Registro con validación de correo corporativo y "Declaración de Honestidad".
2.  **Prueba 1 (Redes):** Puzzle de lógica sobre puertos estándar y protocolos HTTP (Puerto 80).
3.  **Prueba 2 (Hacking Ético):** Simulación de vulnerabilidad de Inyección SQL para bypass de autenticación.
4.  **Ranking:** Cálculo en tiempo real de la duración de la partida (segundos) y guardado en base de datos.

## ⚠️ Nota sobre la Autenticación (Microsoft)

Actualmente, el sistema utiliza una **validación de dominio por software**.
> *La integración nativa con Microsoft Azure AD (OAuth 2.0) para el "Login con Microsoft" está planificada en el código, pero se encuentra pendiente de la aprobación de credenciales API por parte del departamento de IT del centro.*

## 🛠️ Instalación (Despliegue Local)

Si quieres probar este proyecto en tu máquina (Laragon/XAMPP):

1.  Clonar el repositorio:
    ```bash
    git clone [https://github.com/xtart1DAW/DAWSCAPE.git](https://github.com/xtart1DAW/DAWSCAPE.git)
    ```
2.  Instalar dependencias:
    ```bash
    composer install
    ```
3.  Configurar entorno:
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
4.  Crear base de datos:
    ```bash
    php artisan migrate
    ```
5.  Lanzar servidor (si no usas Laragon automático):
    ```bash
    php artisan serve
    ```

---
<p align="center">
    Desarrollado con ❤️ y mucho café por el equipo de <strong>DAWSCAPE</strong>.
    <br>
    Bajo licencia MIT (Laravel Framework).
</p>