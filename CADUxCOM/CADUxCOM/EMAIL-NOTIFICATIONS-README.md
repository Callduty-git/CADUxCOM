# Sistema de Notificaciones por Email - CADUxCOM

## 📧 Descripción

El sistema de notificaciones por email de CADUxCOM envía automáticamente alertas a los usuarios sobre:

- **Productos próximos a caducar** - Alertas cuando los productos están cerca de su fecha de vencimiento
- **Descuentos disponibles** - Notificaciones sobre productos con descuentos significativos
- **Nuevos productos** - Información sobre productos recién agregados

## 🚀 Características

### Tipos de Notificaciones

1. **ProductExpiryNotification**
   - Se envía cuando un producto está próximo a caducar
   - Incluye información detallada del producto, precios y días restantes
   - Diseño visual con alertas de urgencia

2. **DiscountAlertNotification**
   - Se envía para productos con descuentos significativos (>10%)
   - Muestra el porcentaje de descuento y ahorro
   - Diseño atractivo con colores llamativos

3. **NewProductNotification**
   - Se envía para productos recién agregados (últimas 24 horas)
   - Información completa del nuevo producto
   - Diseño limpio y profesional

### Características Técnicas

- **Sistema de Colas**: Los emails se procesan en cola para mejor rendimiento
- **Diseño Responsivo**: Templates HTML optimizados para todos los dispositivos
- **Personalización**: Emails personalizados con nombre del usuario
- **Configuración Flexible**: Parámetros configurables para diferentes tipos de alertas

## ⚙️ Configuración

### 1. Configuración de Email

El sistema está configurado para usar Gmail SMTP. Para configurar:

```bash
# Editar archivo .env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu-email@gmail.com
MAIL_PASSWORD=tu-contraseña-de-aplicación
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="caduxcom@gmail.com"
MAIL_FROM_NAME="CADUxCOM"
```

### 2. Configuración de Colas

```bash
# En .env
QUEUE_CONNECTION=database
```

### 3. Configuración de Cron Jobs

```bash
# Editar crontab
crontab -e

# Agregar las siguientes líneas:
0 */6 * * * cd /ruta/a/CADUxCOM && php artisan notifications:generate --type=expiry --days=7
0 */12 * * * cd /ruta/a/CADUxCOM && php artisan notifications:generate --type=discount
0 0 * * * cd /ruta/a/CADUxCOM && php artisan notifications:generate --type=new
* * * * * cd /ruta/a/CADUxCOM && php artisan queue:work --stop-when-empty
```

## 🧪 Pruebas

### Comando de Prueba

```bash
# Enviar emails de prueba a una dirección específica
php artisan test:email-notifications tu-email@ejemplo.com
```

### Comandos de Notificaciones

```bash
# Enviar notificaciones de caducidad
php artisan notifications:generate --type=expiry --days=7

# Enviar notificaciones de descuentos
php artisan notifications:generate --type=discount

# Enviar notificaciones de nuevos productos
php artisan notifications:generate --type=new

# Enviar todas las notificaciones
php artisan notifications:generate --type=all
```

### Procesar Colas

```bash
# Procesar colas de emails
php artisan queue:work

# Procesar colas una vez
php artisan queue:work --stop-when-empty
```

## 📁 Estructura de Archivos

```
app/
├── Mail/
│   ├── ProductExpiryNotification.php
│   ├── DiscountAlertNotification.php
│   └── NewProductNotification.php
├── Console/Commands/
│   ├── GenerateNotifications.php
│   └── TestEmailNotifications.php
└── Models/
    └── Producto.php (métodos actualizados)

resources/views/emails/
├── product-expiry.blade.php
├── discount-alert.blade.php
└── new-product.blade.php
```

## 🎨 Diseño de Emails

### Características de Diseño

- **Colores**: Esquema de colores verde y púrpura consistente con la marca
- **Responsivo**: Adaptable a dispositivos móviles y desktop
- **Imágenes**: Soporte para imágenes de productos
- **Call-to-Action**: Botones claros para acciones del usuario
- **Información Completa**: Precios, descuentos, fechas de caducidad

### Elementos Visuales

- **Headers**: Gradientes de colores para cada tipo de notificación
- **Product Cards**: Tarjetas de producto con información detallada
- **Badges**: Etiquetas de descuento y estado
- **Footer**: Información de la empresa y opciones de cancelación

## 📊 Monitoreo

### Logs

Los emails se registran en los logs de Laravel. Para monitorear:

```bash
# Ver logs en tiempo real
tail -f storage/logs/laravel.log

# Ver logs de colas
php artisan queue:monitor
```

### Métricas

El sistema proporciona información detallada sobre:
- Número de emails enviados
- Tipos de notificaciones
- Errores de envío
- Tiempo de procesamiento

## 🔧 Mantenimiento

### Limpieza de Colas

```bash
# Limpiar colas fallidas
php artisan queue:flush

# Reintentar trabajos fallidos
php artisan queue:retry all
```

### Actualización de Configuración

```bash
# Limpiar caché después de cambios
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

## 🚨 Solución de Problemas

### Problemas Comunes

1. **Emails no se envían**
   - Verificar configuración SMTP
   - Comprobar credenciales de Gmail
   - Verificar que las colas estén procesándose

2. **Emails van a spam**
   - Configurar SPF, DKIM y DMARC
   - Usar dirección de email verificada
   - Evitar contenido que active filtros de spam

3. **Colas no se procesan**
   - Verificar que el worker esté ejecutándose
   - Comprobar configuración de base de datos
   - Revisar logs de errores

### Comandos de Diagnóstico

```bash
# Verificar configuración de email
php artisan tinker
>>> config('mail')

# Verificar colas
php artisan queue:work --once

# Verificar productos con descuentos
php artisan tinker
>>> App\Models\Producto::where('PrecioOriginal', '>', 'Precio')->count()
```

## 📈 Mejoras Futuras

- **Segmentación de Usuarios**: Envío basado en preferencias y comportamiento
- **Plantillas Personalizables**: Permitir a las empresas personalizar emails
- **Analytics**: Métricas detalladas de apertura y clics
- **A/B Testing**: Pruebas de diferentes versiones de emails
- **Integración con Redes Sociales**: Compartir ofertas en redes sociales

## 📞 Soporte

Para soporte técnico o preguntas sobre el sistema de notificaciones:

- **Email**: soporte@caduxcom.com
- **Documentación**: Ver archivos de configuración en el proyecto
- **Logs**: Revisar logs de Laravel para errores específicos

---

**CADUxCOM** - Reduciendo el desperdicio de alimentos a través de tecnología innovadora 🌱
