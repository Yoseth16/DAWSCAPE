# 📧 Configuración Resend para DAWSCAPE

## 🔑 Variables de Entorno (.env)

Para que Laravel envíe emails con **Resend**, añade estas líneas a tu archivo `.env`:

```env
# Configuración de Correo (Resend SMTP)
MAIL_MAILER=smtp
MAIL_HOST=smtp.resend.com
MAIL_PORT=587
MAIL_USERNAME=resend
MAIL_PASSWORD=re_XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=onboarding@resend.dev
MAIL_FROM_NAME="DAWSCAPE"
```

### Parámetros Explicados:

| Variable | Valor | Explicación |
|----------|-------|------|
| `MAIL_MAILER` | `smtp` | Usar protocolo SMTP |
| `MAIL_HOST` | `smtp.resend.com` | Servidor SMTP de Resend |
| `MAIL_PORT` | `587` | Puerto SMTP seguro (TLS) |
| `MAIL_USERNAME` | `resend` | Usuario fijo de Resend |
| `MAIL_PASSWORD` | `re_XXXXXXX...` | Tu API Key de Resend (cópiala desde el dashboard) |
| `MAIL_ENCRYPTION` | `tls` | Cifrado TLS recomendado |
| `MAIL_FROM_ADDRESS` | `onboarding@resend.dev` | Email del remitente (usar el de verificación inicial) |
| `MAIL_FROM_NAME` | `DAWSCAPE` | Nombre que aparecerá en "De:" |

---

## 🚀 Pasos para Obtener tu API Key de Resend

1. **Crea una cuenta** en [resend.com](https://resend.com) (si no tienes)
2. **Accede al dashboard**: https://dashboard.resend.com
3. **Ve a "API Keys"** en el menú lateral
4. **Copia tu API Key** (comienza con `re_`)
5. **Pega el valor** en `MAIL_PASSWORD` en el archivo `.env`

### ⚠️ Importante:
- **Nunca compartas** tu API Key
- En producción, gestiona las claves con variables de entorno seguras
- Resend permite emails desde `onboarding@resend.dev` por defecto (fase de prueba)
- Para dominios personalizados, verifica tu dominio en Resend

---

## ✅ Verificar que todo funciona

Ejecuta este comando en la terminal del proyecto para probar:

```bash
php artisan tinker
```

Luego dentro de Tinker:

```php
use Illuminate\Support\Facades\Mail;
use App\Models\Inscripcion;

// Obtén una inscripción de prueba
$inscripcion = Inscripcion::first();

// Envía un email de prueba
Mail::send(new \App\Mail\CodigoAccesoInscripcion($inscripcion));
```

Si no hay errores, ¡tu configuración funciona! 🎉

---

## 🎯 Ubicación del Mailable

El Mailable que hemos creado está en:
- **Clase**: `app/Mail/CodigoAccesoInscripcion.php`
- **Vista**: `resources/views/emails/codigo-acceso.blade.php`
- **Controlador que lo usa**: `app/Http/Controllers/InscripcionController.php` (método `guardar()`)

---

## 📋 Archivos Modificados

### 1. Migración Nueva
- `database/migrations/2026_03_11_000001_add_codigo_acceso_to_inscripcions_table.php`

### 2. Backend
- `app/Models/Inscripcion.php` - Agrega columna `codigo_acceso` al fillable y genera código en boot()
- `app/Http/Controllers/InscripcionController.php`:
  - Agrega array `$clases` (lista cerrada)
  - Actualiza `mostrar()` para pasar clases a vista
  - Actualiza `guardar()` con validación de clase y envío de email

### 3. Mailables
- `app/Mail/CodigoAccesoInscripcion.php` (nuevo)
- `resources/views/emails/codigo-acceso.blade.php` (nuevo)

### 4. Vistas Blade
- `resources/views/inscripcion.blade.php`:
  - Cambio input text → select para clase
  - Añade estilos CSS para select
- `resources/views/inscripcion-exito.blade.php`:
  - Muestra código_acceso
  - Actualiza instrucciones

---

## 🔄 Flujo Completo de Inscripción (ACTUALIZADO)

**Día 1 - Inscripción:**
1. Usuario accede a `/inscripcion`
2. Rellena formulario con **selector de clases**
3. Presiona "Registrar Equipo"
4. Se genera automáticamente:
   - `codigo_acceso`: 6 caracteres únicos (ej: X7B9A2)
   - `qr_token`: Token para descargar QR (opcional mantenerlo)
5. Se envía **email con Resend** con el código
6. Redirige a `/inscripcion/exito/{token}`
7. Muestra código + instrucciones

**Día 2 - Juego:**
1. Usuario accede a `/` (inicio)
2. Ingresa `correo` + `nombre_equipo` 
3. Sistema verifica en tabla `inscripcions`
4. Si OK → acceso al juego
5. (Futuro) También validar `codigo_acceso` si es necesario

---

## 🆘 Troubleshooting

### "Error: Connection could not be established with host smtp.resend.com"
- ✅ Verifica que `MAIL_HOST=smtp.resend.com`
- ✅ Verifica que `MAIL_PORT=587`
- ✅ Verifica que `MAIL_ENCRYPTION=tls`

### "Error: 535 Authentication failed"
- ✅ Verifica que `MAIL_USERNAME=resend`
- ✅ Verifica que tu `MAIL_PASSWORD` es correcta (cópiala de nuevo desde Resend)

### "Error: Code already exists"
- ✅ El código se genera automáticamente de forma única, no debería ocurrir
- ✅ Si ocurre, limpia la BD y reintenta

### Los emails no llegan
- ✅ Revisa la bandeja de SPAM
- ✅ En Resend dashboard > "Emails" verifica si se envió
- ✅ En log: `storage/logs/laravel.log` para ver errores

