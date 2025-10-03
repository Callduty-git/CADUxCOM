<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Log de Productos - CADUxCOM</title>
    <link rel="stylesheet" href="{{ asset('css/empresa-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/empresa-sidebar.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <link rel="stylesheet" href="{{ asset('css/facturas.css') }}">
</head>
<body>
    <!-- HEADER -->
    <x-header-empresa />

    <x-empresa-sidebar />

    <div class="main-container">
        <main class="dashboard-panel">
            <!-- Mensajes de sesión -->
            @if(session('success'))
                <div class="session-message success">
                    <div class="notification-icon">✓</div>
                    <div class="notification-content">
                        <div class="notification-message">{{ session('success') }}</div>
                    </div>
                    <button class="notification-close" onclick="this.parentElement.remove()">×</button>
                </div>
            @endif
            @if(session('error'))
                <div class="session-message error">
                    <div class="notification-icon">✕</div>
                    <div class="notification-content">
                        <div class="notification-message">{{ session('error') }}</div>
                    </div>
                    <button class="notification-close" onclick="this.parentElement.remove()">×</button>
                </div>
            @endif
            
            <div class="log-container">
                <div class="log-header">
                    <div>
                        <div class="log-title">
                            <i class="fas fa-clipboard-list log-icon"></i>
                            Log de Productos
                        </div>
                        <div class="log-subtitle">Registro de actividades de productos - Subidas y eliminaciones</div>
                    </div>
                    
                    <!-- Contador de logs más visible -->
                    <div class="log-counter-container {{ $totalLogs >= 45 ? 'counter-warning' : '' }}">
                        <div class="log-counter-large">
                            <span class="counter-number {{ $totalLogs >= 45 ? 'counter-number-warning' : '' }}">{{ $totalLogs }}</span>
                            <span class="counter-separator">/</span>
                            <span class="counter-total">{{ $maxLogs }}</span>
                        </div>
                        <div class="counter-label">
                            @if($totalLogs >= 45)
                                ⚠️ Registros - Cerca del Límite
                            @else
                                Registros
                            @endif
                        </div>
                    </div>
                    
                    <!-- Botón de limpiar logs cuando esté cerca del límite -->
                    @if($totalLogs >= 45)
                        <div class="log-actions">
                            <form method="POST" action="{{ route('empresa.facturas.clear-logs') }}" class="d-inline" onsubmit="return confirm('¿Estás seguro de que quieres eliminar todos los logs? Esta acción no se puede deshacer.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-clear-logs">
                                    <i class="fas fa-trash-alt"></i>
                                    Limpiar Todos los Logs
                                </button>
                            </form>
                        </div>
                    @endif
                    
                    <!-- Barra de búsqueda mejorada -->
                    <div class="search-container">
                        <form method="GET" action="{{ route('empresa.facturas') }}" class="search-form">
                            <div class="search-input-group">
                                <div class="search-icon">
                                    <i class="fas fa-search"></i>
                                </div>
                                <input type="text" 
                                       name="search" 
                                       placeholder="Buscar por producto, acción, fecha (ayer, hoy, semana)..." 
                                       class="search-input" 
                                       value="{{ request('search') }}"
                                       autocomplete="off"
                                       id="smartSearchInput">
                                <div class="search-actions">
                                    @if(request('search'))
                                        <a href="{{ route('empresa.facturas') }}" class="clear-search" title="Limpiar búsqueda">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    @endif
                                    <button type="submit" class="search-button" title="Buscar">
                                        <i class="fas fa-arrow-right"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Sugerencias de búsqueda -->
                            <div class="search-suggestions" id="searchSuggestions">
                                <div class="suggestion-category">
                                    <span class="category-title">📅 Búsquedas por fecha:</span>
                                    <div class="suggestion-items">
                                        <span class="suggestion-item" data-search="hoy">hoy</span>
                                        <span class="suggestion-item" data-search="ayer">ayer</span>
                                        <span class="suggestion-item" data-search="esta semana">esta semana</span>
                                        <span class="suggestion-item" data-search="este mes">este mes</span>
                                    </div>
                                </div>
                                <div class="suggestion-category">
                                    <span class="category-title">📆 Días de la semana:</span>
                                    <div class="suggestion-items">
                                        <span class="suggestion-item" data-search="lunes">lunes</span>
                                        <span class="suggestion-item" data-search="martes">martes</span>
                                        <span class="suggestion-item" data-search="miércoles">miércoles</span>
                                        <span class="suggestion-item" data-search="jueves">jueves</span>
                                        <span class="suggestion-item" data-search="viernes">viernes</span>
                                        <span class="suggestion-item" data-search="sábado">sábado</span>
                                        <span class="suggestion-item" data-search="domingo">domingo</span>
                                    </div>
                                </div>
                                <div class="suggestion-category">
                                    <span class="category-title">⚡ Acciones:</span>
                                    <div class="suggestion-items">
                                        <span class="suggestion-item" data-search="agregar">agregar</span>
                                        <span class="suggestion-item" data-search="eliminar">eliminar</span>
                                    </div>
                                </div>
                                <div class="suggestion-category">
                                    <span class="category-title">💡 Ejemplos de fechas:</span>
                                    <div class="suggestion-items">
                                        <span class="suggestion-item" data-search="{{ date('d/m/Y') }}">{{ date('d/m/Y') }}</span>
                                        <span class="suggestion-item" data-search="{{ date('d/m/Y', strtotime('-1 day')) }}">{{ date('d/m/Y', strtotime('-1 day')) }}</span>
                                        <span class="suggestion-item" data-search="{{ date('d/m/Y', strtotime('-7 days')) }}">{{ date('d/m/Y', strtotime('-7 days')) }}</span>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="log-content">
                    @if(request('search') && count($logs) > 0)
                        <div class="search-results-info">
                            <i class="fas fa-info-circle"></i>
                            Se encontraron {{ count(array_filter($logs, function($log) { return $log['type'] === 'log'; })) }} resultado(s) para "{{ request('search') }}"
                        </div>
                    @endif
                    
                    @forelse ($logs as $log)
                        @if($log['type'] === 'separator')
                            <div class="log-separator">{{ $log['text'] }}</div>
                        @else
                            @php
                                $logData = $log['data'];
                                $accion = strtolower($logData->accion ?? '');
                                $tipoClase = 'default';
                                $icono = 'fas fa-cog';
                                
                                if (strpos($accion, 'agregar') !== false || strpos($accion, 'crear') !== false || strpos($accion, 'subir') !== false) {
                                    $tipoClase = 'agregado';
                                    $icono = 'fas fa-plus-circle';
                                } elseif (strpos($accion, 'eliminar') !== false || strpos($accion, 'borrar') !== false || strpos($accion, 'delete') !== false) {
                                    $tipoClase = 'eliminado';
                                    $icono = 'fas fa-trash-alt';
                                } elseif (strpos($accion, 'modificar') !== false || strpos($accion, 'editar') !== false || strpos($accion, 'update') !== false) {
                                    $tipoClase = 'modificado';
                                    $icono = 'fas fa-edit';
                                }
                                
                                // Obtener la fecha del log
                                $fechaLog = \Carbon\Carbon::parse($logData->hora);
                            
                            
                            // Intentar extraer información del producto de la descripción
                            $productoImagen = null;
                            $productoNombre = null;
                            
                                // Buscar patrones comunes en la descripción para extraer info del producto
                                if (preg_match('/producto[:\s]+([^,]+)/i', $logData->descripcion ?? '', $matches)) {
                                    $productoNombre = trim($matches[1]);
                                }
                                
                                // Si no encontramos nombre, usar la descripción completa
                                if (!$productoNombre) {
                                    $productoNombre = $logData->descripcion ?? 'Producto';
                                }
                            
                            // Buscar el producto real en la colección de productos
                            $productoReal = null;
                            $imagenProducto = asset('images/icon-congelados.png'); // Imagen por defecto
                            
                            // Buscar por nombre del producto
                            foreach ($productos as $producto) {
                                if (stripos($producto->Nombre, $productoNombre) !== false || 
                                    stripos($productoNombre, $producto->Nombre) !== false) {
                                    $productoReal = $producto;
                                    break;
                                }
                            }
                            
                            // Si encontramos el producto, usar su imagen real
                            if ($productoReal && $productoReal->Foto) {
                                $imagenProducto = asset('storage/' . $productoReal->Foto);
                            } elseif ($productoReal) {
                                // Si el producto existe pero no tiene foto, usar una imagen por defecto según el tipo
                                if ($tipoClase === 'agregado') {
                                    $imagenProducto = asset('images/icon-lacteos.png');
                                } elseif ($tipoClase === 'eliminado') {
                                    $imagenProducto = asset('images/icon-enlatados.png');
                                } elseif ($tipoClase === 'modificado') {
                                    $imagenProducto = asset('images/icon-granos.png');
                                }
                            }
                        @endphp
                        
                        
                        <div class="log-item {{ $tipoClase }}">
                            <!-- Imagen del producto -->
                            <img src="{{ $imagenProducto }}" 
                                 alt="{{ $productoNombre }}" 
                                 class="product-image {{ $tipoClase }}"
                                 onerror="this.src='{{ asset('images/icon-congelados.png') }}'">
                            
                                <div class="log-details">
                                    <div class="log-accion">{{ $logData->accion ?? 'Actividad' }}</div>
                                    <div class="log-descripcion">{{ $logData->descripcion ?? 'Sin descripción' }}</div>
                                    <div class="log-fecha">
                                        <i class="fas fa-clock"></i>
                                        {{ $fechaLog->format('d/m/Y H:i:s') }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="empty-state">
                            <div class="empty-icon">
                                @if(request('search'))
                                    <i class="fas fa-search"></i>
                                @else
                                    <i class="fas fa-clipboard-list"></i>
                                @endif
                            </div>
                            <div class="empty-title">
                                @if(request('search'))
                                    No se encontraron resultados
                                @else
                                    No hay actividades registradas
                                @endif
                            </div>
                            <div class="empty-subtitle">
                                @if(request('search'))
                                    No se encontraron actividades que coincidan con "{{ request('search') }}"
                                @else
                                    Las actividades de productos aparecerán aquí cuando subas o elimines productos
                                @endif
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </main>
    </div>

    <!-- SCRIPT INMEDIATO PARA ALINEAR SIDEBAR A LA IZQUIERDA -->
    <script>
        (function(){
            function applySidebarFix(){
                const container=document.querySelector('.sidebar-container');
                if(container){ container.classList.add('sidebar-fixed-left'); }
            }
            applySidebarFix();
            document.addEventListener('DOMContentLoaded',applySidebarFix);
            window.addEventListener('load',applySidebarFix);
        })();
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('smartSearchInput');
            const searchSuggestions = document.getElementById('searchSuggestions');
            const suggestionItems = document.querySelectorAll('.suggestion-item');
            
            // Mostrar/ocultar sugerencias
            searchInput.addEventListener('focus', function() {
                searchSuggestions.classList.add('show');
            });
            
            searchInput.addEventListener('blur', function() {
                // Delay para permitir clicks en sugerencias
                setTimeout(() => {
                    searchSuggestions.classList.remove('show');
                }, 200);
            });
            
            // Click en sugerencias
            suggestionItems.forEach(item => {
                item.addEventListener('click', function() {
                    const searchTerm = this.getAttribute('data-search');
                    searchInput.value = searchTerm;
                    searchSuggestions.classList.remove('show');
                    // Enviar formulario automáticamente
                    searchInput.closest('form').submit();
                });
            });
            
            // Búsqueda inteligente con Enter
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    this.closest('form').submit();
                }
            });
            
            // Auto-completado inteligente
            searchInput.addEventListener('input', function() {
                const value = this.value.toLowerCase();
                
                // Mostrar sugerencias relevantes basadas en lo que se está escribiendo
                suggestionItems.forEach(item => {
                    const itemText = item.textContent.toLowerCase();
                    if (itemText.includes(value) || value.includes(itemText)) {
                        item.style.display = 'inline-block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
        
        // Aplicar clase fija al sidebar (sin estilos inline)
        function forceLeftAlignSidebar(){
            const sidebarContainer=document.querySelector('.sidebar-container');
            if(sidebarContainer){ sidebarContainer.classList.add('sidebar-fixed-left'); }
        }
        forceLeftAlignSidebar();
        document.addEventListener('DOMContentLoaded',forceLeftAlignSidebar);
        window.addEventListener('load',forceLeftAlignSidebar);
    </script>

    <!-- Footer -->
    <x-footer />
</body>
</html>
