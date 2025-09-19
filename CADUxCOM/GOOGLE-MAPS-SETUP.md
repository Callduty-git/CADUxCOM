# Configuración de Google Maps para CADUxCOM

## Configuración de la API Key

Para que el mapa funcione correctamente, necesitas configurar tu API Key de Google Maps en el archivo `.env`:

### 1. Obtener API Key de Google Maps

1. Ve a [Google Cloud Console](https://console.cloud.google.com/)
2. Crea un nuevo proyecto o selecciona uno existente
3. Habilita las siguientes APIs:
   - **Maps JavaScript API**
   - **Geocoding API** (opcional, para geocodificación)
   - **Places API** (opcional, para búsquedas de lugares)

### 2. Configurar la API Key

Agrega la siguiente línea a tu archivo `.env`:

```env
GOOGLE_MAPS_API_KEY=tu_api_key_aqui
```

### 3. Restricciones de Seguridad (Recomendado)

Para mayor seguridad, configura restricciones en tu API Key:

1. **Restricciones de aplicación**: Selecciona "HTTP referrers (web sites)"
2. **Sitios web**: Agrega tu dominio (ej: `https://tudominio.com/*`)
3. **Restricciones de API**: Limita solo a las APIs que necesitas

### 4. Verificar la Configuración

La configuración se encuentra en `config/services.php`:

```php
'google' => [
    'maps_api_key' => env('GOOGLE_MAPS_API_KEY'),
],
```

## Funcionalidades Implementadas

### ✅ Características Principales

- **Mapa interactivo** con Google Maps JavaScript API
- **Pins de empresas** con información detallada
- **Info windows** con animaciones suaves
- **Geolocalización del usuario** con manejo de errores
- **Filtros avanzados**:
  - Por municipio
  - Por radio de búsqueda
  - Por categoría de productos
  - Solo productos con descuento
- **Búsqueda por proximidad** usando la ubicación del usuario
- **Diseño responsivo** para móviles y desktop
- **Animaciones suaves** para mejor UX

### 🎨 Mejoras de Diseño

- **Colores corporativos** de CADUxCOM
- **Iconos Font Awesome** para mejor visualización
- **Animaciones CSS** para transiciones suaves
- **Notificaciones toast** con feedback visual
- **Diseño adaptativo** para diferentes tamaños de pantalla

### 🔧 Funcionalidades Técnicas

- **Manejo de errores** de geolocalización
- **Filtrado local** para mejor rendimiento
- **Lazy loading** de marcadores
- **Optimización de consultas** a la base de datos
- **Caché de ubicaciones** del usuario

## Estructura de Archivos

```
resources/views/geolocation/
├── map.blade.php          # Vista principal del mapa
public/css/
├── map.css               # Estilos del mapa
app/Http/Controllers/
├── GeolocationController.php  # Controlador del mapa
app/Models/
├── Empresa.php           # Modelo con métodos de geolocalización
```

## Rutas API

- `GET /mapa` - Vista del mapa
- `POST /api/search-nearby` - Búsqueda por proximidad
- `POST /api/user-location` - Guardar ubicación del usuario
- `GET /api/geolocation-stats` - Estadísticas de geolocalización

## Personalización

### Cambiar Colores Corporativos

Modifica las variables CSS en `public/css/map.css`:

```css
:root {
    --primary-color: #3b82f6;
    --secondary-color: #1d4ed8;
    --success-color: #10b981;
    --error-color: #ef4444;
}
```

### Ajustar Zoom por Defecto

En `resources/views/geolocation/map.blade.php`:

```javascript
map = new google.maps.Map(document.getElementById('map'), {
    zoom: 6, // Cambiar este valor
    center: defaultLocation,
    // ...
});
```

## Solución de Problemas

### El mapa no se carga
1. Verifica que la API Key esté configurada correctamente
2. Revisa la consola del navegador para errores
3. Asegúrate de que las APIs estén habilitadas en Google Cloud Console

### La geolocalización no funciona
1. Verifica que el sitio use HTTPS (requerido para geolocalización)
2. Revisa los permisos del navegador
3. Verifica la consola para mensajes de error

### Los marcadores no aparecen
1. Verifica que las empresas tengan coordenadas válidas
2. Revisa la consola para errores de JavaScript
3. Verifica que los datos se estén cargando correctamente

## Soporte

Para problemas técnicos o mejoras, contacta al equipo de desarrollo de CADUxCOM.






