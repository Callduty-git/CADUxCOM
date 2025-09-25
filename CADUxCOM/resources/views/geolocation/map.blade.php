<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa de Ofertas - CADUxCOM</title>
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/map.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Meta tags para SEO y rendimiento -->
    <meta name="description" content="Encuentra ofertas de productos cerca de ti en el mapa interactivo de CADUxCOM">
    <meta name="keywords" content="ofertas, mapa, geolocalización, productos, descuentos">
    <meta name="robots" content="index, follow">
    
    <!-- Preload de recursos críticos -->
    <link rel="preload" href="{{ asset('js/map.js') }}" as="script">
    <link rel="preload" href="{{ asset('css/map.css') }}" as="style">
    
    <!-- Google Maps API -->
    <script>
        // Variable global para la API key
        window.googleMapsApiKey = 'AIzaSyBDaeWicvigtP9xPv919E-RNoxfvC-Hqik';
        
        // Función de inicialización del mapa
        function initMap() {
            if (window.mapManager) return;
            window.mapManager = new MapManager();
        }
    </script>
    
    <!-- Estilos inline para asegurar que se apliquen -->
    <style>
        .filter-select {
            border: 2px solid #90D575 !important;
            background: #FFFFFF !important;
            color: #49874E !important;
            border-radius: 8px !important;
            appearance: none !important;
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
        }
        
        .filter-select:hover {
            border-color: #49874E !important;
        }
        
        .filter-select:focus {
            border-color: #49874E !important;
            outline: none !important;
        }
        
        #municipio-select, #category-select, #radius-select {
            border: 2px solid #90D575 !important;
            background: #FFFFFF !important;
            color: #49874E !important;
        }
        
        /* Espaciado superior específico para la página del mapa */
        .map-container {
            margin-top: 50px; /* Espacio mínimo necesario para el header fijo */
            padding-top: 0; /* Eliminado el padding adicional */
            position: relative;
            z-index: 1;
        }
        
        /* Responsive para el espaciado */
        @media (max-width: 768px) {
            .map-container {
                margin-top: 110px !important; /* Ajustado para coincidir con el header fijo en móviles */
                padding-top: 0;
                height: calc(100vh - 110px);
            }
        }
    </style>
</head>
<body>
    <x-header-pages />
    
    <div class="map-container">
        <!-- Panel de control -->
        <div class="map-controls">
            <div class="controls-header">
                <h1 class="page-title">
                    <i class="fas fa-map-marked-alt mr-2"></i>
                    Mapa de Ofertas
                </h1>
                <p class="page-subtitle">
                    Descubre productos con descuento cerca de ti
                </p>
            </div>

            <!-- Filtros -->
            <div class="filters-section">
                <div class="filter-group">
                    <label for="municipio-select" class="filter-label">
                        <i class="fas fa-map-marker-alt"></i>
                        Municipio
                    </label>
                    <select id="municipio-select" class="filter-select">
                        <option value="">Todos los municipios</option>
                        @foreach($municipiosHuila as $municipio)
                            <option value="{{ $municipio }}">{{ $municipio }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label for="radius-select" class="filter-label">
                        <i class="fas fa-circle"></i>
                        Radio de búsqueda
                    </label>
                    <select id="radius-select" class="filter-select">
                        <option value="1">1 km</option>
                        <option value="5">5 km</option>
                        <option value="10" selected>10 km</option>
                        <option value="20">20 km</option>
                        <option value="50">50 km</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="category-select" class="filter-label">
                        <i class="fas fa-tags"></i>
                        Categoría
                    </label>
                    <select id="category-select" class="filter-select">
                        <option value="">Todas las categorías</option>
                        @foreach($categorias ?? [] as $categoria)
                            <option value="{{ $categoria->Id_Categoria }}">{{ $categoria->Nombre }}</option>
                        @endforeach
                    </select>
                </div>


                <div class="filter-actions">
                    <button id="search-btn" class="btn btn-primary">
                        <i class="fas fa-search mr-2"></i>
                        Buscar Cercanas
                    </button>
                    <button id="my-location-btn" class="btn btn-secondary">
                        <i class="fas fa-crosshairs mr-2"></i>
                        Mi Ubicación
                    </button>
                </div>
            </div>

            <!-- Resultados -->
            <div class="results-section">
                <div class="results-header">
                    <h2 class="results-title">Empresas Encontradas</h2>
                    <p class="results-count" id="results-count">
                        {{ count($empresas) }} empresas disponibles
                    </p>
                </div>
                <div class="results-list" id="results-list">
                    @if(count($empresas) > 0)
                        @foreach($empresas as $empresa)
                            <div class="result-item" onclick="mapManager.centerOnEmpresa({{ $empresa['id'] }})">
                                <div class="result-header">
                                    <h3 class="result-name">{{ $empresa['name'] }}</h3>
                                </div>
                                <p class="result-address">{{ $empresa['address'] }}</p>
                                <div class="result-stats">
                                    <span class="stat-item">{{ $empresa['products_count'] }} productos</span>
                                    @if($empresa['discounted_products_count'] > 0)
                                        <span class="stat-item discount">{{ $empresa['discounted_products_count'] }} con descuento</span>
                                    @endif
                                </div>
                                @if($empresa['products']->count() > 0)
                                    <div class="result-products">
                                        @foreach($empresa['products']->take(2) as $producto)
                                            <div class="product-preview">
                                                <img src="{{ $producto['image'] }}" alt="{{ $producto['name'] }}" class="product-thumb" 
                                                     onerror="this.src='{{ asset('images/default-product.png') }}'">
                                                <div class="product-details">
                                                    <p class="product-name">{{ $producto['name'] }}</p>
                                                    <div class="product-price {{ $producto['has_discount'] ? 'discounted' : '' }}">
                                                        @if($producto['has_discount'])
                                                            <span class="discount-price">${{ number_format($producto['discounted_price']) }}</span>
                                                            <span class="discount-badge">-{{ $producto['discount_percentage'] }}%</span>
                                                        @else
                                                            <span class="price">${{ number_format($producto['price']) }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        @if($empresa['products']->count() > 2)
                                            <p class="text-xs text-gray-500 mt-2">+{{ $empresa['products']->count() - 2 }} productos más</p>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @else
                    <div class="no-results">
                            <i class="fas fa-map-marker-alt text-4xl text-gray-400 mb-4"></i>
                            <h4>No hay empresas disponibles</h4>
                            <p>Actualmente no hay empresas registradas en el mapa.</p>
                            <button onclick="location.reload()" class="btn btn-outline mt-4">
                                <i class="fas fa-refresh mr-2"></i>
                                Recargar
                            </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Mapa -->
        <div class="map-wrapper">
            <div id="map-container" style="position: relative; min-height: 400px;">
                <div id="map" class="map" style="height: 100%; width: 100%;"></div>
                <!-- Loading inicial -->
                <div class="map-overlay" id="initial-loading">
                    <div class="loading-spinner">
                        <div class="spinner"></div>
                        <p>Cargando mapa...</p>
                    </div>
                </div>
                <div id="no-companies-message" class="no-companies-message" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 80%; max-width: 500px;">
                    <h3>No hay empresas disponibles</h3>
                    <p>Actualmente no hay empresas registradas en el mapa. Vuelve más tarde para ver las ofertas disponibles.</p>
                    <button onclick="location.reload()">
                        <i class="fas fa-sync-alt"></i> Recargar
                    </button>
                </div>
            </div>
            <div class="map-overlay" id="loading-spinner" style="display: none;">
                <div class="loading-spinner">
                    <div class="spinner"></div>
                    <p>Buscando ofertas...</p>
                </div>
            </div>
        </div>
    </div>

    <x-footer />

    <!-- Scripts -->
    <script>
        // Datos globales para el mapa
        window.empresasData = @json($empresas);
        window.googleMapsApiKey = '{{ config('services.google.maps_api_key', 'AIzaSyBDaeWicvigtP9xPv919E-RNoxfvC-Hqik') }}';
        window.defaultProductImage = '{{ asset('images/default-product.png') }}';
        
        // Verificar si la API Key está configurada
        if (window.googleMapsApiKey === 'YOUR_API_KEY') {
            console.warn('Google Maps API Key no configurada');
        }

        // Función de callback global para Google Maps
        window.initMap = function() {
            console.log('Google Maps API cargada correctamente');
            // Inicializar el mapa manualmente
            if (typeof MapManager === 'function') {
                window.mapManager = new MapManager();
            } else {
                console.error('MapManager no está definido. Asegúrate de que map.js se cargue correctamente.');
            }
        };
    </script>
    
    <!-- Cargar MarkerClusterer primero -->
    <script src="https://unpkg.com/@googlemaps/markerclusterer/dist/index.min.js"></script>
    
    <!-- Cargar script del mapa antes de la API -->
    <script src="{{ asset('js/map.js') }}"></script>
    
    <!-- Cargar Google Maps API al final -->
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key', 'AIzaSyBDaeWicvigtP9xPv919E-RNoxfvC-Hqik') }}&libraries=places,geometry&callback=initMap" async defer></script>
</body>
</html>
