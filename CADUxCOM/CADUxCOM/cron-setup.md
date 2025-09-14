# Configuración de Cron Jobs para Notificaciones Automáticas

## Configuración del Cron Job

Para que las notificaciones se envíen automáticamente, necesitas configurar un cron job en tu servidor.

### 1. Editar el crontab

```bash
crontab -e
```

### 2. Agregar las siguientes líneas

```bash
# Enviar notificaciones de caducidad cada 6 horas
0 */6 * * * cd /Users/ambiente106/Desktop/Proyecto_Jeidi/CADUxCOM && php artisan notifications:generate --type=expiry --days=7

# Enviar notificaciones de descuentos cada 12 horas
0 */12 * * * cd /Users/ambiente106/Desktop/Proyecto_Jeidi/CADUxCOM && php artisan notifications:generate --type=discount

# Enviar notificaciones de nuevos productos cada 24 horas
0 0 * * * cd /Users/ambiente106/Desktop/Proyecto_Jeidi/CADUxCOM && php artisan notifications:generate --type=new

# Procesar colas de emails cada minuto
* * * * * cd /Users/ambiente106/Desktop/Proyecto_Jeidi/CADUxCOM && php artisan queue:work --stop-when-empty
```

### 3. Para desarrollo local (opcional)

Si quieres probar las notificaciones manualmente:

```bash
# Enviar notificaciones de caducidad
php artisan notifications:generate --type=expiry --days=7

# Enviar notificaciones de descuentos
php artisan notifications:generate --type=discount

# Enviar notificaciones de nuevos productos
php artisan notifications:generate --type=new

# Procesar colas
php artisan queue:work
```

### 4. Configuración de Gmail

Para usar Gmail como servidor SMTP:

1. Ve a tu cuenta de Google
2. Activa la verificación en 2 pasos
3. Genera una contraseña de aplicación
4. Actualiza el archivo `.env` con:
   - `MAIL_USERNAME=tu-email@gmail.com`
   - `MAIL_PASSWORD=tu-contraseña-de-aplicación`

### 5. Prueba de emails

Para probar el envío de emails:

```bash
php artisan test:email-notifications tu-email@ejemplo.com
```

## Notas Importantes

- Los emails se envían a todos los usuarios registrados
- Las notificaciones se envían solo si hay productos que cumplan los criterios
- Los emails se procesan en cola para mejor rendimiento
- Asegúrate de que el servidor tenga acceso a internet para enviar emails
