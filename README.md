<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# 🕵️‍♂️ DAWSCAPE - Escape Room Educativo

> **Proyecto de Gamificación y Ciberseguridad para 1º DAW**

DAWSCAPE es una experiencia inmersiva web desarrollada en Laravel. El objetivo es poner a prueba los conocimientos de los alumnos en un entorno controlado y competitivo. Ha sido estructurado mediante un flujo de pantallas gamificadas estilo "terminal/hacker".

## 🚀 Actualizaciones Recientes (Backend y Mobile-First)

- **Refactorización UI Total (Mobile-First):** Todas las interfaces del juego han sido reconstruidas para garantizar una legibilidad y usabilidad perfectas en teléfonos móviles. Se ha utilizado la función `clamp()` de CSS, fuentes a un mínimo de 16px para evitar auto-zoom en iOS/Android y botones adaptados para pulsación táctil (área tap de más de 48px).
- **Sistema de QRs Físicos Integrado:** Se ha añadido una infraestructura backend para generar códigos QR escaneables por los alumnos, enlazando el mundo real (el centro/instituto) con la aplicación web.
- **Nuevas Pruebas y Expansión:** El juego cuenta ahora con un recorrido completo de 5 pruebas entrelazadas.
- **Panel de Admin Avanzado:** Funciones de gestión en caliente con búsqueda, saltos/bypass directos a cualquier prueba para verificar el estado de la vista como profesor, control CRUD de usuarios y exportación de ranking TOP 20 en PDF.

## 🎮 Mecánica del Juego y Flujo de Pruebas

El juego consta de una ruta lineal y secuencial bajo la presión de un cronómetro persistente en la Base de Datos (límite por defecto de 90 minutos):

1.  **Login Seguro:** Registro con validación de correo corporativo estricto y "Declaración de Honor".
2.  **Prueba 1 (Aula 115):** 6 Acertijos escalonados de lógica matemática y observación visual del entorno físico del aula.
3.  **Prueba 2 (Clúster Fragmentado):** Reconstrucción de un anagrama escondido.
4.  **Prueba 3 (Operación Cafeína):** Checksum de hardware físico. Escaneo de QR en la máquina de café de la sala de profesores.
5.  **Prueba 4 (Operación Repuestos):** Auditoría física sobre la máquina expendedora Oasis. Escaneo de terminal QR.
6.  **Prueba 5 (Protocolo Espejo):** Desencriptación basada en alfabeto invertido escondida en las taquillas de pasillo.
7.  **Ranking Final:** Extracción del tiempo exacto que le tomó al equipo superar el cortafuegos para su posterior entrada en la clasificación oficial.

## 🔐 Respuestas Maestras (Solo para Administrador)

Estas son las contraseñas exactas codificadas en el backend requeridas para avanzar en las diferentes etapas del juego:

- **Prueba 1 (Aula 115):**
    - Acertijo 1 (puerta): `115`
    - Acertijo 2 (ordenadores pares): `16`
    - Acertijo 3 (pantallas x2): `32`
    - Acertijo 4 (luces pares): `14`
    - Acertijo 5 (serial de la pantalla principal): `5x0lh8bx100127t`
    - Acertijo 6 (suma de salida): `201`
- **Prueba 2 (Clúster Fragmentado):** Anagrama `ROCIO`
- **Prueba 3 (Operación Cafeína - QR Máquina de Café):** Suma total (Checksum): `124`
- **Prueba 4 (Operación Repuestos - QR Máquina Oasis Snacks):** Suma total (Checksum): `141`
- **Prueba 5 (Protocolo Espejo - QR Taquillas):** Código de entrada: `ZASIELX`

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
