# Integración del Escáner QR en DAWSCAPE

## 📱 Descripción General

Se ha integrado un sistema de escaneo de códigos QR en tiempo real utilizando la librería **html5-qrcode** en las vistas de las pruebas que lo requieren. Esto permite a los jugadores escanear códigos QR físicos con la cámara de su dispositivo móvil para resolver las pruebas del escape room.

## 🎯 Características Implementadas

### 1. Componente Reutilizable
- **Ubicación**: `resources/views/components/qr-scanner.blade.php`
- **Librería**: html5-qrcode v2.3.8 (cargada vía CDN)
- **Iconos**: Phosphor Icons para la interfaz

### 2. Funcionalidades del Escáner

✅ **Activación/Desactivación Manual**
- Botón para activar/detener el escáner
- Control total del usuario sobre el uso de la cámara

✅ **Detección Automática**
- Escaneo continuo a 10 FPS
- Detección automática del código QR
- Detención automática tras escaneo exitoso

✅ **Feedback Visual**
- Mensajes de éxito en verde
- Mensajes de error en rojo
- Animaciones suaves de transición

✅ **Feedback Háptico**
- Vibración al escanear correctamente (si el dispositivo lo soporta)
- Vibración al detectar error

✅ **Diseño Responsive**
- Optimizado para móviles
- Centrado y adaptable a diferentes tamaños de pantalla
- Cámara trasera por defecto (ideal para escanear)

✅ **Integración con Backend**
- Envío automático del código QR al servidor vía AJAX
- Manejo de respuestas success/error
- Redirección automática si el servidor lo indica

## 📂 Vistas Integradas

### Prueba 3: Máquina de Café
**Archivo**: `resources/views/qr/maquina-cafe.blade.php`
- **Ruta**: `/prueba3`
- **Uso**: Escanear QR de la máquina de café para obtener la respuesta directamente

### Prueba 4: Máquina Oasis (Snacks)
**Archivo**: `resources/views/qr/maquina-snacks.blade.php`
- **Ruta**: `/prueba4`
- **Uso**: Escanear QR de la máquina Oasis para obtener la respuesta

### Prueba 5: Taquillas (Protocolo Espejo)
**Archivo**: `resources/views/qr/taquillas.blade.php`
- **Ruta**: `/prueba5`
- **Uso**: Escanear QR de las taquillas para encontrar las letras

## 🔧 Uso del Componente

### Sintaxis Básica

```blade
@include('components.qr-scanner', [
    'route' => '/prueba3',
    'successMessage' => 'Código QR validado correctamente'
])
```

### Parámetros

| Parámetro | Tipo | Requerido | Descripción |
|-----------|------|-----------|-------------|
| `route` | string | Sí | Ruta del endpoint que procesará el QR |
| `successMessage` | string | No | Mensaje personalizado de éxito (default: "QR escaneado correctamente") |

## 🔌 Integración Backend

### Formato de Envío

El escáner envía los datos al servidor mediante POST con el siguiente formato:

```javascript
{
    "qr_data": "contenido_del_codigo_qr"
}
```

### Formato de Respuesta Esperado

```json
{
    "success": true,
    "message": "Mensaje opcional",
    "redirect": "/ruta/opcional"
}
```

### Ejemplo de Controlador

```php
public function validarQR(Request $request)
{
    $qrData = $request->input('qr_data');
    
    // Validar el código QR
    if ($this->esQRValido($qrData)) {
        return response()->json([
            'success' => true,
            'message' => 'QR validado correctamente',
            'redirect' => route('siguiente.prueba')
        ]);
    }
    
    return response()->json([
        'success' => false,
        'message' => 'QR inválido o no reconocido'
    ]);
}
```

## 🎨 Personalización de Estilos

El componente utiliza variables CSS que se adaptan al tema de cada vista:

```css
--green: #00ff88;
--red: #ff2244;
--border: #00ff8840;
--text: #c8ffdf;
```

## 📱 Permisos de Cámara

### Navegadores Soportados
- ✅ Chrome/Edge (Android/iOS)
- ✅ Safari (iOS)
- ✅ Firefox (Android)

### Requisitos
- HTTPS o localhost (requerido por la API de cámara)
- Permisos de cámara otorgados por el usuario

### Manejo de Errores
Si el usuario deniega los permisos de cámara, se muestra un mensaje de error claro:
> "No se pudo acceder a la cámara. Verifica los permisos."

## 🔒 Seguridad

- ✅ Token CSRF incluido en todas las peticiones
- ✅ Validación del QR en el servidor
- ✅ No se almacena información sensible en el cliente
- ✅ Detención automática del escáner al cambiar de página

## 🚀 Ventajas de la Implementación

1. **Experiencia de Usuario Mejorada**
   - Alternativa rápida al método manual
   - Reduce errores de transcripción
   - Más inmersivo y tecnológico

2. **Accesibilidad**
   - Opción adicional, no obligatoria
   - Los jugadores pueden elegir el método que prefieran

3. **Mantenibilidad**
   - Componente reutilizable
   - Fácil de integrar en nuevas vistas
   - Código centralizado

4. **Performance**
   - Librería ligera y optimizada
   - Escaneo eficiente (10 FPS)
   - Detención automática para ahorrar recursos

## 📝 Notas Técnicas

### Librería html5-qrcode
- **Versión**: 2.3.8
- **CDN**: https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js
- **Documentación**: https://github.com/mebjas/html5-qrcode

### Configuración del Escáner
```javascript
const config = {
    fps: 10,                          // Frames por segundo
    qrbox: { width: 250, height: 250 }, // Área de escaneo
    aspectRatio: 1.0                  // Relación de aspecto
};
```

### Cámara Utilizada
```javascript
{ facingMode: "environment" }  // Cámara trasera (ideal para escanear)
```

## 🐛 Troubleshooting

### El escáner no se activa
- Verificar que el sitio esté en HTTPS o localhost
- Comprobar permisos de cámara en el navegador
- Revisar la consola del navegador para errores

### El QR no se detecta
- Asegurar buena iluminación
- Mantener el QR dentro del área de escaneo (cuadro verde)
- Verificar que el QR no esté dañado o borroso

### Error de conexión con el servidor
- Verificar que la ruta del endpoint sea correcta
- Comprobar que el token CSRF esté presente
- Revisar los logs del servidor

## 🎓 Ejemplo de Uso Completo

```blade
<!-- En la vista de la prueba -->
<div class="intro-text">
    <!-- Contenido de la prueba -->
    
    <!-- Escáner QR alternativo -->
    <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--border);">
        <p style="text-align: center; color: #668877; margin-bottom: 1rem;">
            <strong>¿Necesitas ayuda?</strong><br>
            Escanea el código QR para obtener la respuesta.
        </p>
        @include('components.qr-scanner', [
            'route' => '/prueba3',
            'successMessage' => 'Código QR validado correctamente'
        ])
    </div>
</div>

<!-- Cargar iconos Phosphor -->
<script src="https://unpkg.com/@phosphor-icons/web"></script>
```

## 📊 Estadísticas de Implementación

- **Componentes creados**: 1 (reutilizable)
- **Vistas modificadas**: 3 (prueba3, prueba4, prueba5)
- **Líneas de código**: ~350 (componente completo)
- **Dependencias externas**: 2 (html5-qrcode, phosphor-icons)
- **Tiempo de integración**: ~30 minutos por vista

---

**Desarrollado para**: DAWSCAPE - Escape Room Educativo  
**Fecha**: Marzo 2026  
**Tecnologías**: Laravel 12, Blade, html5-qrcode, Phosphor Icons
