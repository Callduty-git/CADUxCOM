<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa de Ofertas - CADUxCOM</title>
    
    <!-- Meta tags para SEO -->
    <meta name="description" content="Descubre las mejores ofertas cerca de ti con nuestro mapa interactivo de CADUxCOM">
    <meta name="keywords" content="ofertas, mapa, geolocalización, productos, descuentos, CADUxCOM">
    <meta name="robots" content="index, follow">
    
    <!-- Estilos del header y footer -->
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Meta CSRF -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="{{ asset('css/map.css') }}">
    <link rel="stylesheet" href="{{ asset('css/infowindow.css') }}">
    
    <!-- Google Maps API -->
    <script>
        window.googleMapsApiKey = "AIzaSyBMDPpV5x-_Xl-ekz1kg48nuD79NgTN8mU";
        window.empresasData = @json($empresas);
        window.municipiosData = @json($municipiosHuila);
        window.categoriasData = @json($categorias);
    </script>
</head>
<body>
    <!-- Header -->
    <x-header-pages />
    
    <!-- Contenedor principal del mapa -->
    <div class="map-page-container">
        <!-- Sidebar de filtros -->
        <aside class="map-sidebar" id="map-sidebar">
            <!-- Header del sidebar -->
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <i class="fas fa-map-marked-alt"></i>
                    <h1>Mapa de Ofertas</h1>
                </div>
                <p class="sidebar-subtitle">Encuentra las mejores ofertas cerca de ti</p>
            </div>
            
            <!-- Panel de filtros -->
            <div class="filters-panel">
                <div class="filters-header">
                    <h2><i class="fas fa-filter"></i> Filtros</h2>
                </div>
                
                <!-- Filtro de ubicación -->
                <div class="filter-group">
                    <label class="filter-label">
                        <i class="fas fa-map-marker-alt"></i>
                        Ubicación
                    </label>
                    <select id="municipio-filter" class="filter-select">
                        <option value="">Todos los municipios</option>
                        @foreach($municipiosHuila as $municipio)
                            <option value="{{ $municipio }}">{{ $municipio }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Filtro de radio -->
                <div class="filter-group">
                    <label class="filter-label">
                        <i class="fas fa-circle"></i>
                        Radio de búsqueda
                    </label>
                    <select id="radius-filter" class="filter-select">
                        <option value="1">1 km</option>
                        <option value="5">5 km</option>
                        <option value="10" selected>10 km</option>
                        <option value="20">20 km</option>
                        <option value="50">50 km</option>
                    </select>
                </div>
                
                <!-- Filtro de categoría -->
                <div class="filter-group">
                    <label class="filter-label">
                        <i class="fas fa-tags"></i>
                        Categoría
                    </label>
                    <select id="category-filter" class="filter-select">
                        <option value="">Todas las categorías</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->Id_Categoria }}">{{ $categoria->Nombre }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Botones de acción -->
                <div class="filter-actions">
                    <button id="search-nearby-btn" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                        Buscar Cercanas
                    </button>
                    <button id="my-location-btn" class="btn btn-secondary">
                        <i class="fas fa-crosshairs"></i>
                        Mi Ubicación
                    </button>
                </div>
            </div>
            
            <!-- Panel de resultados -->
            <div class="results-panel">
                <div class="results-header">
                    <h2><i class="fas fa-list"></i> Empresas Encontradas</h2>
                    <span id="results-count" class="results-count">{{ count($empresas) }} empresas</span>
                </div>
                
                <div class="results-list" id="results-list">
                    @if(count($empresas) > 0)
                        @foreach($empresas as $empresa)
                            <div class="result-card" data-empresa-id="{{ $empresa['id'] }}">
                                <div class="result-header">
                                    <h3 class="result-name">{{ $empresa['name'] }}</h3>
                                    <span class="result-distance" data-distance="{{ $empresa['distance'] ?? '' }}">
                                        @if(isset($empresa['distance']))
                                            {{ number_format($empresa['distance'], 1) }} km
                                        @endif
                                    </span>
                                </div>
                                
                                <div class="result-address">
                                    <i class="fas fa-map-marker-alt"></i>
                                    {{ $empresa['address'] }}
                                </div>
                                
                                <div class="result-stats">
                                    <div class="stat-item">
                                        <i class="fas fa-box"></i>
                                        <span>{{ $empresa['products_count'] }} productos</span>
                                    </div>
                                    @if($empresa['discounted_products_count'] > 0)
                                        <div class="stat-item discount">
                                            <i class="fas fa-percentage"></i>
                                            <span>{{ $empresa['discounted_products_count'] }} con descuento</span>
                                        </div>
                                    @endif
                                </div>
                                
                                @if($empresa['products']->count() > 0)
                                    <div class="result-products">
                                        <h4>Productos destacados:</h4>
                                        <div class="products-grid">
                                            @foreach($empresa['products']->take(3) as $producto)
                                                <div class="product-card">
                                                    <img src="{{ $producto['image'] }}" alt="{{ $producto['name'] }}" 
                                                         class="product-image" 
                                                         onerror="this.src='/images/default-product.png'">
                                                    <div class="product-info">
                                                        <h5 class="product-name">{{ $producto['name'] }}</h5>
                                                        <div class="product-price">
                                                            @if($producto['has_discount'])
                                                                <span class="price-discounted">${{ number_format($producto['discounted_price']) }}</span>
                                                                <span class="price-original">${{ number_format($producto['price']) }}</span>
                                                                <span class="discount-badge">-{{ $producto['discount_percentage'] }}%</span>
                                                            @else
                                                                <span class="price-normal">${{ number_format($producto['price']) }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        @if($empresa['products']->count() > 3)
                                            <p class="more-products">+{{ $empresa['products']->count() - 3 }} productos más</p>
                                        @endif
                                    </div>
                                @endif
                                
                                <div class="result-actions">
                                    <button class="btn btn-outline btn-sm" onclick="mapManager.centerOnEmpresa({{ $empresa['id'] }})">
                                        <i class="fas fa-crosshairs"></i>
                                        Centrar aquí
                                    </button>
                                    <button class="btn btn-primary btn-sm" onclick="window.open('/empresa/{{ $empresa['id'] }}', '_blank')">
                                        <i class="fas fa-external-link-alt"></i>
                                        Ver empresa
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="no-results">
                            <div class="no-results-icon">
                                <i class="fas fa-search"></i>
                            </div>
                            <h3>No hay empresas disponibles</h3>
                            <p>Actualmente no hay empresas registradas en el mapa. Vuelve más tarde para ver las ofertas disponibles.</p>
                            <button onclick="location.reload()" class="btn btn-primary">
                                <i class="fas fa-sync-alt"></i>
                                Recargar
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </aside>
        
        <!-- Contenedor del mapa -->
        <main class="map-container">
            <!-- Botón para mostrar/ocultar sidebar en móvil -->
            <button id="sidebar-toggle" class="sidebar-toggle">
                <i class="fas fa-bars"></i>
            </button>
            
            <!-- Mapa -->
            <div id="map" class="map"></div>
            
            <!-- Overlay de carga -->
            <div id="map-loading" class="map-loading">
                <div class="loading-content">
                    <div class="loading-spinner"></div>
                    <h3>Cargando mapa...</h3>
                    <p>Preparando las mejores ofertas para ti</p>
                </div>
            </div>
            
            <!-- Mensaje cuando no hay empresas -->
            <div id="no-companies-overlay" class="no-companies-overlay" style="display: none;">
                <div class="no-companies-content">
                    <div class="no-companies-icon">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <h3>No hay empresas disponibles</h3>
                    <p>Actualmente no hay empresas registradas en el mapa. Vuelve más tarde para ver las ofertas disponibles.</p>
                    <button onclick="location.reload()" class="btn btn-primary">
                        <i class="fas fa-sync-alt"></i>
                        Recargar
                    </button>
                </div>
            </div>
            
            <!-- Controles del mapa -->
            <div class="map-controls">
                <button id="center-map-btn" class="map-control-btn" title="Centrar mapa">
                    <i class="fas fa-crosshairs"></i>
                </button>
                <button id="zoom-in-btn" class="map-control-btn" title="Acercar">
                    <i class="fas fa-plus"></i>
                </button>
                <button id="zoom-out-btn" class="map-control-btn" title="Alejar">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </main>
    </div>
    
    <!-- Footer -->
    <x-footer />
    
    <!-- Scripts -->
    <script src="{{ asset('js/map.js') }}"></script>
    
    <!-- Google Maps API -->
    <script async defer 
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBMDPpV5x-_Xl-ekz1kg48nuD79NgTN8mU&libraries=places,geometry&callback=initMap">
    </script>
</body>
</html>
