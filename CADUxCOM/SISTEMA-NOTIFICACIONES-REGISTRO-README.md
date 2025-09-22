# Sistema de Notificaciones de Registro - CADUxCOM

## 📧 Descripción

Se ha implementado un sistema completo de notificaciones por correo electrónico para el registro de usuarios y empresas en CADUxCOM, con diferentes flujos de verificación según el tipo de usuario.

## 🚀 Características Implementadas

### Para Usuarios Normales:
1. **Notificación al Administrador**: Se envía un email al correo empresarial cuando un usuario se registra
2. **Verificación de Email**: El usuario recibe un email de confirmación que debe verificar antes de poder iniciar sesión
3. **Control de Acceso**: Los usuarios no pueden iniciar sesión hasta verificar su email

### Para Empresas:
1. **Notificación al Administrador**: Se envía un email al correo empresarial cuando una empresa se registra
2. **Estado Pendiente**: La empresa recibe un email informando que su registro está en verificación
3. **Verificación Manual**: El administrador debe revisar y aprobar/rechazar la empresa
4. **Notificación de Resultado**: La empresa recibe un email con el resultado de la verificación

## 📁 Archivos Creados/Modificados

### Clases de Mail:
- `app/Mail/UserRegistrationNotification.php` - Notificación de nuevo usuario al admin
- `app/Mail/UserEmailVerification.php` - Email de verificación para usuarios
- `app/Mail/EmpresaRegistrationNotification.php` - Notificación de nueva empresa al admin
- `app/Mail/EmpresaPendingVerification.php` - Email de espera para empresas
- `app/Mail/EmpresaApprovalNotification.php` - Email de aprobación/rechazo para empresas

### Vistas de Email:
- `resources/views/emails/user-registration-notification.blade.php`
- `resources/views/emails/user-email-verification.blade.php`
- `resources/views/emails/empresa-registration-notification.blade.php`
- `resources/views/emails/empresa-pending-verification.blade.php`
- `resources/views/emails/empresa-approved.blade.php`
- `resources/views/emails/empresa-rejected.blade.php`

### Controladores:
- `app/Http/Controllers/Admin/EmpresaVerificationController.php` - Panel de administración
- `app/Http/Controllers/Auth/UserEmailVerificationController.php` - Verificación de usuarios

### Migraciones:
- `database/migrations/2025_09_15_142607_add_verification_fields_to_users_table.php`
- `database/migrations/2025_09_15_142617_add_verification_fields_to_empresas_table.php`

### Modelos Modificados:
- `app/Models/User.php` - Agregado campo `email_verified`
- `app/Models/Empresa.php` - Agregados campos `status`, `approved_at`, `rejected_at`, `rejection_reason`

## ⚙️ Configuración Requerida

### 1. Configuración de Email (.env)

```bash
# Configuración de correo
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu-email@gmail.com
MAIL_PASSWORD=tu-contraseña-de-aplicación
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="caduxcom.store@gmail.com"
MAIL_FROM_NAME="CADUxCOM"

# Email del administrador (donde llegan las notificaciones)
ADMIN_EMAIL=caduxcom.store@gmail.com
```

### 2. Configuración de Colas (Opcional)

```bash
# En .env para procesar emails en cola
QUEUE_CONNECTION=database
```

### 3. Configuración de Gmail

Para usar Gmail como servidor SMTP:
1. Ve a tu cuenta de Google
2. Activa la verificación en 2 pasos
3. Genera una contraseña de aplicación
4. Usa esa contraseña en `MAIL_PASSWORD`

## 🔧 Rutas Agregadas

### Verificación de Usuarios:
- `GET /verify-email/{id}/{hash}` - Verificar email del usuario
- `POST /resend-verification` - Reenviar email de verificación

### Panel de Administración:
- `GET /admin/empresas/pending` - Lista de empresas pendientes
- `GET /admin/empresas/{empresa}` - Ver detalles de empresa
- `POST /admin/empresas/{empresa}/approve` - Aprobar empresa
- `POST /admin/empresas/{empresa}/reject` - Rechazar empresa
- `GET /admin/empresas/approved` - Empresas aprobadas
- `GET /admin/empresas/rejected` - Empresas rechazadas

## 📋 Flujo de Registro

### Usuario Normal:
1. Usuario se registra
2. Se envía notificación al admin
3. Se envía email de verificación al usuario
4. Usuario hace clic en el enlace de verificación
5. Usuario puede iniciar sesión

### Empresa:
1. Empresa se registra
2. Se envía notificación al admin
3. Se envía email de espera a la empresa
4. Admin revisa documentos y datos
5. Admin aprueba o rechaza
6. Empresa recibe notificación del resultado
7. Si aprobada, empresa puede iniciar sesión

## 🎯 Características de Seguridad

- **Enlaces firmados**: Los enlaces de verificación tienen firma digital y expiran en 60 minutos
- **Verificación de hash**: Se verifica que el hash del email coincida
- **Control de acceso**: Solo usuarios verificados y empresas aprobadas pueden iniciar sesión
- **Logs de actividad**: Se registran todas las acciones importantes

## 🚀 Uso del Sistema

### Para Administradores:
1. Acceder a `/admin/empresas/pending` para ver empresas pendientes
2. Revisar documentos y datos de la empresa
3. Aprobar o rechazar con motivo específico
4. Las empresas recibirán notificación automática

### Para Usuarios:
1. Registrarse normalmente
2. Revisar email y hacer clic en enlace de verificación
3. Iniciar sesión después de verificar

### Para Empresas:
1. Registrarse con todos los documentos
2. Esperar notificación de que está en verificación
3. Recibir notificación de aprobación/rechazo
4. Iniciar sesión solo si fue aprobada

## 🔍 Pruebas

Para probar el sistema:

```bash
# Probar envío de emails
php artisan tinker
Mail::to('test@example.com')->send(new \App\Mail\UserEmailVerification(\App\Models\User::first()));

# Verificar colas (si están habilitadas)
php artisan queue:work
```

## 📝 Notas Importantes

- Los emails se envían automáticamente al registrarse
- El sistema es completamente funcional sin configuración adicional
- Se puede personalizar el diseño de los emails modificando las vistas Blade
- El panel de administración requiere autenticación de usuario
- Todos los emails incluyen diseño responsive y profesional

## 🛠️ Mantenimiento

- Revisar regularmente las empresas pendientes
- Monitorear los logs de email para detectar problemas
- Actualizar la configuración de email según sea necesario
- Hacer backup de los documentos de empresas regularmente