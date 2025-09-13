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
    
    <!-- Google Maps API -->
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key', 'YOUR_API_KEY') }}&libraries=places&callback=initMap" async defer></script>
</head>
<body>
    <x-header-pages />
    
    <div class="map-container">
        <!-- Panel de control -->
        <div class="map-controls">
            <div class="controls-header">
                <h1 class="page-title">Mapa de Ofertas</h1>
                <p class="page-subtitle">Encuentra productos próximos a caducar cerca de ti</p>
            </div>

            <!-- Filtros -->
            <div class="filters-section">
                <div class="filter-group">
                    <label for="radius-select" class="filter-label">Radio de búsqueda:</label>
                    <select id="radius-select" class="filter-select">
                        <option value="5">5 km</option>
                        <option value="10" selected>10 km</option>
                        <option value="20">20 km</option>
                        <option value="50">50 km</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="category-select" class="filter-label">Categoría:</label>
                    <select id="category-select" class="filter-select">
                        <option value="">Todas las categorías</option>
                        @foreach($categorias ?? [] as $categoria)
                            <option value="{{ $categoria->Id_Categoria }}">{{ $categoria->Nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label class="filter-checkbox">
                        <input type="checkbox" id="discount-only" checked>
                        <span class="checkmark"></span>
                        Solo productos con descuento
                    </label>
                </div>

                <div class="filter-actions">
                    <button id="search-btn" class="btn btn-primary">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Buscar Cerca
                    </button>
                    <button id="my-location-btn" class="btn btn-secondary">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Mi Ubicación
                    </button>
                </div>
            </div>

            <!-- Resultados -->
            <div class="results-section">
                <div class="results-header">
                    <h3 class="results-title">Resultados</h3>
                    <span id="results-count" class="results-count">0 empresas encontradas</span>
                </div>
                <div id="results-list" class="results-list">
                    <div class="no-results">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <p>Usa los filtros para buscar ofertas cerca de ti</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mapa -->
        <div class="map-wrapper">
            <div id="map" class="map"></div>
            <div class="map-overlay">
                <div class="loading-spinner" id="loading-spinner" style="display: none;">
                    <div class="spinner"></div>
                    <p>Buscando ofertas...</p>
                </div>
            </div>
        </div>
    </div>

    <x-footer />

    <script>
        let map;
        let markers = [];
        let userLocation = null;
        let userMarker = null;
        let empresas = @json($empresas);

        // Inicializar mapa
        function initMap() {
            // Coordenadas por defecto (Garzón, Huila)
            const defaultLocation = { lat: 2.1962, lng: -75.6278 };
            
            map = new google.maps.Map(document.getElementById('map'), {
                zoom: 13,
                center: defaultLocation,
                mapTypeId: 'roadmap',
                styles: [
                    {
                        featureType: 'poi',
                        elementType: 'labels',
                        stylers: [{ visibility: 'off' }]
                    }
                ]
            });

            // Agregar marcadores de empresas
            addEmpresaMarkers();

            // Event listeners
            document.getElementById('search-btn').addEventListener('click', searchNearby);
            document.getElementById('my-location-btn').addEventListener('click', getCurrentLocation);
            document.getElementById('radius-select').addEventListener('change', searchNearby);
            document.getElementById('category-select').addEventListener('change', searchNearby);
            document.getElementById('discount-only').addEventListener('change', searchNearby);
        }

        // Agregar marcadores de empresas
        function addEmpresaMarkers() {
            clearMarkers();
            
            empresas.forEach(empresa => {
                if (empresa.coordinates) {
                    const marker = new google.maps.Marker({
                        position: empresa.coordinates,
                        map: map,
                        title: empresa.name,
                        icon: {
                            url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(`
                                <svg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="20" cy="20" r="18" fill="#3b82f6" stroke="white" stroke-width="2"/>
                                    <text x="20" y="26" text-anchor="middle" fill="white" font-size="16" font-weight="bold">${empresa.discounted_products_count}</text>
                                </svg>
                            `),
                            scaledSize: new google.maps.Size(40, 40),
                            anchor: new google.maps.Point(20, 20)
                        }
                    });

                    const infoWindow = new google.maps.InfoWindow({
                        content: createInfoWindowContent(empresa)
                    });

                    marker.addListener('click', () => {
                        infoWindow.open(map, marker);
                    });

                    markers.push(marker);
                }
            });
        }

        // Crear contenido del InfoWindow
        function createInfoWindowContent(empresa) {
            let content = `
                <div class="info-window">
                    <h3 class="info-title">${empresa.name}</h3>
                    <p class="info-address">${empresa.address}</p>
                    <div class="info-stats">
                        <span class="stat-item">${empresa.products_count} productos</span>
                        <span class="stat-item discount">${empresa.discounted_products_count} con descuento</span>
                    </div>
                    <div class="info-products">
            `;

            if (empresa.products && empresa.products.length > 0) {
                empresa.products.slice(0, 3).forEach(producto => {
                    const priceClass = producto.has_discount ? 'discounted' : '';
                    content += `
                        <div class="product-item">
                            <img src="${producto.image}" alt="${producto.name}" class="product-image">
                            <div class="product-info">
                                <h4 class="product-name">${producto.name}</h4>
                                <div class="product-price ${priceClass}">
                                    ${producto.has_discount ? 
                                        `<span class="original-price">$${formatPrice(producto.price)}</span>
                                         <span class="discount-price">$${formatPrice(producto.discounted_price)}</span>
                                         <span class="discount-badge">-${Math.round(producto.discount_percentage)}%</span>` :
                                        `<span class="price">$${formatPrice(producto.price)}</span>`
                                    }
                                </div>
                                <span class="expiry-status ${producto.expiry_status}">${producto.expiry_label}</span>
                            </div>
                        </div>
                    `;
                });
            }

            content += `
                    </div>
                    <a href="/productos?empresa=${empresa.id}" class="btn btn-primary btn-sm">Ver Productos</a>
                </div>
            `;

            return content;
        }

        // Obtener ubicación actual del usuario
        function getCurrentLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        userLocation = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude
                        };

                        // Actualizar ubicación del usuario en el servidor
                        fetch('/api/user-location', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify(userLocation)
                        });

                        // Centrar mapa en ubicación del usuario
                        map.setCenter(userLocation);
                        map.setZoom(15);

                        // Agregar marcador del usuario
                        if (userMarker) {
                            userMarker.setMap(null);
                        }

                        userMarker = new google.maps.Marker({
                            position: userLocation,
                            map: map,
                            title: 'Tu ubicación',
                            icon: {
                                url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(`
                                    <svg width="30" height="30" viewBox="0 0 30 30" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="15" cy="15" r="12" fill="#ef4444" stroke="white" stroke-width="3"/>
                                        <circle cx="15" cy="15" r="6" fill="white"/>
                                    </svg>
                                `),
                                scaledSize: new google.maps.Size(30, 30),
                                anchor: new google.maps.Point(15, 15)
                            }
                        });

                        // Buscar ofertas cercanas
                        searchNearby();
                    },
                    (error) => {
                        console.error('Error obteniendo ubicación:', error);
                        showNotification('No se pudo obtener tu ubicación', 'error');
                    }
                );
            } else {
                showNotification('Geolocalización no soportada por este navegador', 'error');
            }
        }

        // Buscar ofertas cercanas
        async function searchNearby() {
            if (!userLocation) {
                showNotification('Primero obtén tu ubicación', 'error');
                return;
            }

            showLoading(true);

            const radius = document.getElementById('radius-select').value;
            const category = document.getElementById('category-select').value;
            const hasDiscount = document.getElementById('discount-only').checked;

            try {
                const response = await fetch('/api/search-nearby', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        latitude: userLocation.lat,
                        longitude: userLocation.lng,
                        radius: radius,
                        category: category || null,
                        has_discount: hasDiscount
                    })
                });

                const data = await response.json();

                if (data.success) {
                    displayResults(data.data);
                    updateMapMarkers(data.data);
                } else {
                    showNotification('Error en la búsqueda', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Error de conexión', 'error');
            } finally {
                showLoading(false);
            }
        }

        // Mostrar resultados
        function displayResults(empresas) {
            const resultsList = document.getElementById('results-list');
            const resultsCount = document.getElementById('results-count');
            
            resultsCount.textContent = `${empresas.length} empresas encontradas`;

            if (empresas.length === 0) {
                resultsList.innerHTML = `
                    <div class="no-results">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.47-.881-6.08-2.33"></path>
                        </svg>
                        <p>No se encontraron ofertas en el área seleccionada</p>
                    </div>
                `;
                return;
            }

            resultsList.innerHTML = empresas.map(empresa => `
                <div class="result-item" onclick="centerOnEmpresa(${empresa.id})">
                    <div class="result-header">
                        <h4 class="result-name">${empresa.name}</h4>
                        <span class="result-distance">${empresa.distance} km</span>
                    </div>
                    <p class="result-address">${empresa.address}</p>
                    <div class="result-stats">
                        <span class="stat-item">${empresa.products_count} productos</span>
                        <span class="stat-item discount">${empresa.discounted_products_count} con descuento</span>
                    </div>
                    ${empresa.products.length > 0 ? `
                        <div class="result-products">
                            ${empresa.products.slice(0, 2).map(producto => `
                                <div class="product-preview">
                                    <img src="${producto.image}" alt="${producto.name}" class="product-thumb">
                                    <div class="product-details">
                                        <span class="product-name">${producto.name}</span>
                                        <div class="product-price ${producto.has_discount ? 'discounted' : ''}">
                                            ${producto.has_discount ? 
                                                `<span class="discount-price">$${formatPrice(producto.discounted_price)}</span>
                                                 <span class="discount-badge">-${Math.round(producto.discount_percentage)}%</span>` :
                                                `<span class="price">$${formatPrice(producto.price)}</span>`
                                            }
                                        </div>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    ` : ''}
                </div>
            `).join('');
        }

        // Actualizar marcadores del mapa
        function updateMapMarkers(empresas) {
            clearMarkers();
            
            empresas.forEach(empresa => {
                if (empresa.coordinates) {
                    const marker = new google.maps.Marker({
                        position: empresa.coordinates,
                        map: map,
                        title: empresa.name,
                        icon: {
                            url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(`
                                <svg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="20" cy="20" r="18" fill="#10b981" stroke="white" stroke-width="2"/>
                                    <text x="20" y="26" text-anchor="middle" fill="white" font-size="16" font-weight="bold">${empresa.discounted_products_count}</text>
                                </svg>
                            `),
                            scaledSize: new google.maps.Size(40, 40),
                            anchor: new google.maps.Point(20, 20)
                        }
                    });

                    const infoWindow = new google.maps.InfoWindow({
                        content: createInfoWindowContent(empresa)
                    });

                    marker.addListener('click', () => {
                        infoWindow.open(map, marker);
                    });

                    markers.push(marker);
                }
            });
        }

        // Centrar mapa en empresa
        function centerOnEmpresa(empresaId) {
            const empresa = empresas.find(e => e.id === empresaId);
            if (empresa && empresa.coordinates) {
                map.setCenter(empresa.coordinates);
                map.setZoom(16);
            }
        }

        // Limpiar marcadores
        function clearMarkers() {
            markers.forEach(marker => marker.setMap(null));
            markers = [];
        }

        // Mostrar/ocultar loading
        function showLoading(show) {
            document.getElementById('loading-spinner').style.display = show ? 'flex' : 'none';
        }

        // Formatear precio
        function formatPrice(price) {
            return new Intl.NumberFormat('es-CO').format(Math.round(price));
        }

        // Mostrar notificación
        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg text-white font-medium transform transition-all duration-300 ${
                type === 'success' ? 'bg-green-500' : 'bg-red-500'
            }`;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.transform = 'translateX(0)';
            }, 100);
            
            setTimeout(() => {
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }
    </script>
</body>
</html>
