/**
 * Mapa de Ofertas CADUxCOM - JavaScript Optimizado
 * Funcionalidades: Lazy loading, centrado automático, manejo de errores, responsividad
 */

class MapManager {
    constructor() {
        this.map = null;
        this.markers = [];
        this.markerClusterer = null;
        this.userLocation = null;
        this.userMarker = null;
        this.empresas = [];
        this.filteredEmpresas = [];
        this.currentInfoWindow = null;
        this.isMapLoaded = false;
        this.isLoading = false;
        this.mapContainer = null;
        this.observer = null;
        
        // Configuración
        this.config = {
            defaultLocation: { lat: 2.9271, lng: -75.2819 }, // Huila centro
            defaultZoom: 8,
            userZoom: 14,
            maxZoom: 18,
            minZoom: 6,
            apiKey: window.googleMapsApiKey || 'YOUR_API_KEY',
            // Configuración de clustering (nueva librería oficial)
            clusterOptions: {
                // Usamos configuración por defecto de @googlemaps/markerclusterer.
                // Si se requiere personalización, se puede agregar renderer/algorithm.
            }
        };
        
        this.init();
    }

    /**
     * Normalizar objeto de coordenadas desde backend a {lat, lng}
     */
    normalizeCoordinates(coords) {
        if (!coords) return null;
        const lat = Number(coords.lat ?? coords.latitude);
        const lng = Number(coords.lng ?? coords.longitude);
        if (!Number.isFinite(lat) || !Number.isFinite(lng)) return null;
        return { lat, lng };
    }

    /**
     * Normalizar una empresa para asegurar coordinates {lat, lng}
     */
    normalizeEmpresa(empresa) {
        if (!empresa) return empresa;
        const normalized = this.normalizeCoordinates(empresa.coordinates);
        if (!normalized) return empresa;
        return { ...empresa, coordinates: normalized };
    }

    /**
     * Inicializar el mapa
     */
    init() {
        this.setupIntersectionObserver();
        this.bindEvents();
        this.checkApiKey();
    }

    /**
     * Verificar si la API Key está configurada
     */
    checkApiKey() {
        if (this.config.apiKey === 'YOUR_API_KEY' || !this.config.apiKey) {
            console.warn('Google Maps API Key no configurada correctamente');
            this.showApiKeyError();
            return;
        }
    }

    /**
     * Mostrar error de API Key
     */
    showApiKeyError() {
        const mapContainer = document.getElementById('map');
        if (mapContainer) {
            mapContainer.innerHTML = `
                <div class="flex flex-col items-center justify-center h-full bg-gray-100 text-center p-8">
                    <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-lg max-w-md">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-exclamation-triangle text-xl mr-2"></i>
                            <h3 class="font-semibold">Error de configuración</h3>
                        </div>
                        <p class="text-sm mb-3">
                            La API Key de Google Maps no está configurada correctamente. Por favor, contacta al administrador del sistema.
                        </p>
                        <div class="flex gap-2">
                            <button onclick="location.reload()" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition-colors">
                                <i class="fas fa-refresh mr-2"></i>
                                Recargar
                            </button>
                            <button onclick="window.history.back()" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition-colors">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Volver
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }
    }

    /**
     * Mostrar error de carga de Google Maps
     */
    showGoogleMapsError() {
        const mapContainer = document.getElementById('map');
        if (mapContainer) {
            mapContainer.innerHTML = `
                <div class="flex flex-col items-center justify-center h-full bg-gray-100 text-center p-8">
                    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-6 py-4 rounded-lg max-w-md">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-map-marked-alt text-xl mr-2"></i>
                            <h3 class="font-semibold">Error al cargar el mapa</h3>
                        </div>
                        <p class="text-sm mb-3">
                            No se pudo cargar Google Maps. Verifica tu conexión a internet e intenta nuevamente.
                        </p>
                        <div class="flex gap-2">
                            <button onclick="location.reload()" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 transition-colors">
                                <i class="fas fa-refresh mr-2"></i>
                                Reintentar
                            </button>
                            <button onclick="window.history.back()" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition-colors">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Volver
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }
    }

    /**
     * Configurar Intersection Observer para lazy loading
     */
    setupIntersectionObserver() {
        if ('IntersectionObserver' in window) {
            this.observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting && !this.isMapLoaded && !this.isLoading) {
                        this.loadMap();
                        this.observer.unobserve(entry.target);
                    }
                });
            }, {
                rootMargin: '200px', // Cargar antes de que sea visible
                threshold: 0.1
            });

            // Observar el contenedor del mapa
            this.mapContainer = document.getElementById('map');
            if (this.mapContainer) {
                this.observer.observe(this.mapContainer);
            }
        } else {
            // Fallback para navegadores que no soportan IntersectionObserver
            setTimeout(() => this.loadMap(), 100);
        }
    }

    /**
     * Cargar el mapa (lazy loading)
     */
    async loadMap() {
        if (this.isMapLoaded || this.isLoading) return;
        
        this.isLoading = true;
        this.showLoading(true);

        try {
            // Cargar Google Maps script si no está presente
            await this.loadGoogleMapsScript();
            // Esperar a que Google Maps esté disponible
            await this.waitForGoogleMaps();
            this.initializeMap();
            this.isMapLoaded = true;
        } catch (error) {
            console.error('Error cargando el mapa:', error);
            this.showGoogleMapsError();
        } finally {
            this.isLoading = false;
            this.showLoading(false);
        }
    }

    /**
     * Esperar a que Google Maps esté disponible
     */
    waitForGoogleMaps() {
        // Espera robusta: asegura que el constructor Map esté disponible.
        const ensureLibraries = async () => {
            try {
                if (google?.maps?.importLibrary) {
                    await google.maps.importLibrary('maps');
                    try { await google.maps.importLibrary('marker'); } catch (_) {}
                }
            } catch (_) {}
        };

        return new Promise((resolve, reject) => {
            const ready = () => (typeof google !== 'undefined' && google.maps);
            const resolveWhenReady = async () => {
                if (!google.maps.Map) {
                    await ensureLibraries();
                }
                if (google.maps.Map) {
                    resolve();
                }
            };

            if (ready()) {
                resolveWhenReady();
                return;
            }

            // Si ya hay un callback configurado, esperar a que se ejecute
            if (window.initMap) {
                const originalCallback = window.initMap;
                window.initMap = async function() {
                    try { originalCallback(); } catch (_) {}
                    await resolveWhenReady();
                };
                return;
            }

            const checkGoogle = setInterval(async () => {
                if (ready()) {
                    clearInterval(checkGoogle);
                    await resolveWhenReady();
                }
            }, 100);
            
            // Timeout después de 15 segundos
            setTimeout(() => {
                clearInterval(checkGoogle);
                reject(new Error('Timeout esperando Google Maps API'));
            }, 15000);
        });
    }

    /**
     * Cargar el script de Google Maps dinámicamente
     */
    loadGoogleMapsScript() {
        return new Promise((resolve, reject) => {
            if (typeof google !== 'undefined' && google.maps) {
                resolve();
                return;
            }

            // Verificar si ya existe un script de Google Maps
            const existingScript = document.querySelector('script[src*="maps.googleapis.com"]');
            if (existingScript) {
                // Esperar a que se cargue el script existente
                const checkGoogle = setInterval(() => {
                    if (typeof google !== 'undefined' && google.maps) {
                        clearInterval(checkGoogle);
                        resolve();
                    }
                }, 100);
                
                // Timeout después de 10 segundos
                setTimeout(() => {
                    clearInterval(checkGoogle);
                    reject(new Error('Timeout cargando Google Maps API'));
                }, 10000);
                return;
            }

            const script = document.createElement('script');
            // Incluir librería 'marker' para AdvancedMarkerElement
            script.src = `https://maps.googleapis.com/maps/api/js?key=${this.config.apiKey}&libraries=places,geometry,marker&callback=initMapCallback`;
            script.async = true;
            script.defer = true;
            
            // Callback global para cuando el script se carga
            window.initMapCallback = () => {
                resolve();
                delete window.initMapCallback;
            };

            script.onerror = () => {
                reject(new Error('Error cargando Google Maps API'));
            };

            document.head.appendChild(script);
        });
    }

    /**
     * Inicializar el mapa de Google Maps
     */
    initializeMap() {
        const mapElement = document.getElementById('map');
        if (!mapElement) {
            throw new Error('Elemento del mapa no encontrado');
        }

        this.map = new google.maps.Map(mapElement, {
            zoom: this.config.defaultZoom,
            center: this.config.defaultLocation,
            mapTypeId: 'roadmap',
            styles: this.getMapStyles(),
            mapTypeControl: true,
            streetViewControl: false,
            fullscreenControl: true,
            zoomControl: true,
            gestureHandling: 'greedy',
            restriction: {
                latLngBounds: {
                    north: 15.5,
                    south: -4.2,
                    west: -82.1,
                    east: -66.9
                },
                strictBounds: false
            }
        });

        // Cargar datos de empresas
        this.loadEmpresas();
        
        // Intentar obtener ubicación del usuario
        this.getCurrentLocation();

        // Ocultar overlay de carga inicial si existe
        this.hideInitialLoading();
    }

    /**
     * Obtener estilos personalizados del mapa
     */
    getMapStyles() {
        return [
            {
                featureType: 'poi',
                elementType: 'labels',
                stylers: [{ visibility: 'off' }]
            },
            {
                featureType: 'transit',
                elementType: 'labels',
                stylers: [{ visibility: 'off' }]
            },
            {
                featureType: 'water',
                elementType: 'geometry',
                stylers: [{ color: '#e9f5ff' }]
            },
            {
                featureType: 'landscape',
                elementType: 'geometry',
                stylers: [{ color: '#f5f5f5' }]
            }
        ];
    }

    /**
     * Cargar empresas desde el servidor
     */
    async loadEmpresas() {
        try {
            // Los datos de empresas ya están disponibles en la página
            const rawEmpresas = window.empresasData || [];
            this.empresas = rawEmpresas.map(e => this.normalizeEmpresa(e));
            this.filteredEmpresas = [...this.empresas];
            
            if (this.empresas.length === 0) {
                this.showNoEmpresasMessage();
                return;
            }

            this.addEmpresaMarkers();
            this.centerMapOnEmpresas();
        } catch (error) {
            console.error('Error cargando empresas:', error);
            this.showError('Error al cargar las empresas');
        }
    }

    /**
     * Mostrar mensaje cuando no hay empresas
     */
    showNoEmpresasMessage() {
        const mapElement = document.getElementById('map');
        if (mapElement) {
            mapElement.innerHTML = `
                <div class="flex flex-col items-center justify-center h-full bg-gray-50 text-center p-8">
                    <div class="bg-blue-50 border border-blue-200 text-blue-800 px-6 py-8 rounded-lg max-w-md">
                        <i class="fas fa-map-marker-alt text-4xl mb-4 text-blue-500"></i>
                        <h3 class="text-lg font-semibold mb-2">No hay empresas disponibles</h3>
                        <p class="text-sm mb-4">
                            Actualmente no hay empresas registradas en el mapa. 
                            Vuelve más tarde para ver las ofertas disponibles.
                        </p>
                        <button onclick="location.reload()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition-colors">
                            <i class="fas fa-refresh mr-2"></i>
                            Recargar
                        </button>
                    </div>
                </div>
            `;
        }
    }

    /**
     * Agregar marcadores de empresas al mapa
     */
    addEmpresaMarkers() {
        this.clearMarkers();
        
        this.filteredEmpresas.forEach(empresa => {
            const normalized = this.normalizeCoordinates(empresa.coordinates);
            if (normalized) {
                const safeEmpresa = { ...empresa, coordinates: normalized };
                const marker = this.createEmpresaMarker(safeEmpresa);
                this.markers.push(marker);
            }
        });

        // Aplicar clustering si hay marcadores
        if (this.markers.length > 0) {
            this.applyClustering();
        }
    }

    /**
     * Aplicar clustering a los marcadores
     */
    applyClustering() {
        // Limpiar clusterer anterior si existe
        if (this.markerClusterer) {
            this.markerClusterer.clearMarkers();
        }

        // Verificar disponibilidad del UMD oficial
        const UMDAvailable = typeof window.markerClusterer !== 'undefined' && typeof window.markerClusterer.MarkerClusterer === 'function';
        if (!UMDAvailable) {
            console.warn('MarkerClusterer no está disponible; se mostrará sin agrupación.');
            return;
        }

        // Crear nuevo clusterer (API nueva)
        this.markerClusterer = new window.markerClusterer.MarkerClusterer({
            map: this.map,
            markers: this.markers,
            ...this.config.clusterOptions
        });
    }

    /**
     * Crear marcador para una empresa
     */
    createEmpresaMarker(empresa) {
        const AdvancedMarker = google?.maps?.marker?.AdvancedMarkerElement;
        let marker;
        if (AdvancedMarker) {
            marker = new AdvancedMarker({
                position: empresa.coordinates,
                map: this.map,
                title: empresa.name
            });
        } else {
            // Fallback a Marker clásico si la librería 'marker' no está disponible
            marker = new google.maps.Marker({
                position: empresa.coordinates,
                map: this.map,
                title: empresa.name,
                icon: this.createMarkerIcon(empresa),
                animation: google.maps.Animation.DROP
            });
        }

        const infoWindow = new google.maps.InfoWindow({
            content: this.createInfoWindowContent(empresa)
        });

        marker.addListener('click', () => {
            if (this.currentInfoWindow) {
                this.currentInfoWindow.close();
            }
            // Abrir InfoWindow con firma según tipo de marcador
            if (google?.maps?.marker?.AdvancedMarkerElement && marker instanceof google.maps.marker.AdvancedMarkerElement) {
                infoWindow.open({ anchor: marker, map: this.map });
            } else {
                infoWindow.open(this.map, marker);
            }
            this.currentInfoWindow = infoWindow;
        });

        return marker;
    }

    /**
     * Obtener LatLng de marcador (compatible con Marker y AdvancedMarker)
     */
    getMarkerLatLng(marker) {
        const pos = marker?.position || (typeof marker?.getPosition === 'function' ? marker.getPosition() : null);
        if (!pos) return null;
        const lat = typeof pos.lat === 'function' ? pos.lat() : pos.lat;
        const lng = typeof pos.lng === 'function' ? pos.lng() : pos.lng;
        return { lat, lng };
    }

    /**
     * Crear icono personalizado para el marcador
     */
    createMarkerIcon(empresa) {
        const discountCount = empresa.discounted_products_count || 0;
        const color = discountCount > 0 ? '#10b981' : '#6b7280';
        
        return {
            url: `data:image/svg+xml;charset=UTF-8,${encodeURIComponent(`
                <svg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="20" cy="20" r="18" fill="${color}" stroke="white" stroke-width="2"/>
                    <text x="20" y="26" text-anchor="middle" fill="white" font-size="14" font-weight="bold">${discountCount}</text>
                </svg>
            `)}`,
            scaledSize: new google.maps.Size(40, 40),
            anchor: new google.maps.Point(20, 20)
        };
    }

    /**
     * Crear contenido para InfoWindow
     */
    createInfoWindowContent(empresa) {
        const products = empresa.products || [];
        const hasProducts = products.length > 0;
        const distance = empresa.distance ? `${empresa.distance} km` : '';
        
        return `
            <div class="info-window">
                <div class="info-header">
                    <h3 class="info-title">${empresa.name}</h3>
                    ${distance ? `<span class="info-distance">${distance}</span>` : ''}
                </div>
                <p class="info-address">
                    <i class="fas fa-map-marker-alt mr-1"></i>
                    ${empresa.address}
                </p>
                <div class="info-stats">
                    <span class="stat-item">
                        <i class="fas fa-box mr-1"></i>
                        ${empresa.products_count} productos
                    </span>
                    ${empresa.discounted_products_count > 0 ? 
                        `<span class="stat-item discount">
                            <i class="fas fa-percentage mr-1"></i>
                            ${empresa.discounted_products_count} con descuento
                        </span>` : 
                        ''
                    }
                </div>
                ${hasProducts ? `
                    <div class="info-products">
                        <h4 class="text-sm font-medium mb-2">
                            <i class="fas fa-star mr-1"></i>
                            Productos destacados:
                        </h4>
                        ${products.slice(0, 3).map(product => `
                            <div class="product-item">
                                <img src="${product.image}" alt="${product.name}" class="product-image" 
                                     onerror="this.src='${window.defaultProductImage || '/images/default-product.png'}'">
                                <div class="product-info">
                                    <p class="product-name">${product.name}</p>
                                    <div class="product-price ${product.has_discount ? 'discounted' : ''}">
                                        ${product.has_discount ? `
                                            <span class="original-price">$${this.formatPrice(product.price)}</span>
                                            <span class="discount-price">$${this.formatPrice(product.discounted_price)}</span>
                                            <span class="discount-badge">-${product.discount_percentage}%</span>
                                        ` : `
                                            <span class="price">$${this.formatPrice(product.price)}</span>
                                        `}
                                    </div>
                                    ${product.expiry_status ? `
                                        <span class="expiry-status ${product.expiry_status}">${product.expiry_label}</span>
                                    ` : ''}
                                </div>
                            </div>
                        `).join('')}
                        ${products.length > 3 ? `<p class="text-xs text-gray-500 mt-2">+${products.length - 3} productos más</p>` : ''}
                    </div>
                ` : ''}
                <div class="info-actions">
                    <button onclick="mapManager.centerOnEmpresa(${empresa.id})" class="btn btn-primary btn-sm">
                        <i class="fas fa-crosshairs mr-1"></i>
                        Centrar aquí
                    </button>
                    <button onclick="window.open('/empresa/${empresa.id}', '_blank')" class="btn btn-secondary btn-sm">
                        <i class="fas fa-external-link-alt mr-1"></i>
                        Ver empresa
                    </button>
                </div>
            </div>
        `;
    }

    /**
     * Centrar mapa en las empresas
     */
    centerMapOnEmpresas() {
        if (this.filteredEmpresas.length === 0) {
            // Si no hay empresas, centrar en ubicación por defecto del Huila
            this.map.setCenter(this.config.defaultLocation);
            this.map.setZoom(this.config.defaultZoom);
            return;
        }

        const bounds = new google.maps.LatLngBounds();
        let validCoordinates = 0;

        this.filteredEmpresas.forEach(empresa => {
            const normalized = this.normalizeCoordinates(empresa.coordinates);
            if (normalized) {
                bounds.extend(normalized);
                validCoordinates++;
            }
        });

        if (validCoordinates > 0) {
            if (validCoordinates === 1) {
                // Si solo hay una empresa, centrar en ella con zoom específico
                const empresa = this.filteredEmpresas.find(e => this.normalizeCoordinates(e.coordinates));
                const normalized = this.normalizeCoordinates(empresa.coordinates);
                this.map.setCenter(normalized);
                this.map.setZoom(this.config.userZoom);
            } else {
                // Si hay múltiples empresas, ajustar bounds
                this.map.fitBounds(bounds);
                
                // Asegurar que el zoom no sea demasiado alto o bajo
                const listener = google.maps.event.addListener(this.map, 'idle', () => {
                    const currentZoom = this.map.getZoom();
                    if (currentZoom > this.config.maxZoom) {
                        this.map.setZoom(this.config.maxZoom);
                    } else if (currentZoom < this.config.minZoom) {
                        this.map.setZoom(this.config.minZoom);
                    }
                    google.maps.event.removeListener(listener);
                });
            }
        } else {
            // Si no hay coordenadas válidas, centrar en ubicación por defecto
            this.map.setCenter(this.config.defaultLocation);
            this.map.setZoom(this.config.defaultZoom);
        }
    }

    /**
     * Centrar mapa en empresa específica (única implementación)
     */
    centerOnEmpresa(empresaId) {
        const empresa = this.empresas.find(e => e.id === empresaId);
        if (!empresa) return;
        const normalized = this.normalizeCoordinates(empresa.coordinates);
        if (!normalized) return;

        this.map.setCenter(normalized);
        this.map.setZoom(this.config.userZoom);

        const marker = this.markers.find(m => {
            const p = this.getMarkerLatLng(m);
            return p && p.lat === normalized.lat && p.lng === normalized.lng;
        });
        if (marker) {
            google.maps.event.trigger(marker, 'click');
        }
    }

    /**
     * Obtener ubicación actual del usuario
     */
    async getCurrentLocation() {
        if (!navigator.geolocation) {
            this.showNotification('Geolocalización no soportada por este navegador', 'error');
            return;
        }

        // Mostrar loading en el botón (si existe)
        const locationBtn = document.getElementById('my-location-btn');
        const originalText = locationBtn ? locationBtn.innerHTML : '';
        if (locationBtn) {
            locationBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Obteniendo ubicación...';
            locationBtn.disabled = true;
        }

        try {
            const position = await this.getCurrentPositionPromise();
            this.userLocation = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };

            this.addUserMarker();
            this.centerMapOnUser();
            this.showNotification('Ubicación obtenida correctamente', 'success');
            
            // Buscar ofertas cercanas automáticamente
            this.searchNearby();
        } catch (error) {
            console.warn('Error obteniendo ubicación:', error);
            this.handleLocationError(error);
        } finally {
            // Restaurar botón
            if (locationBtn) {
                locationBtn.innerHTML = originalText;
                locationBtn.disabled = false;
            }
        }
    }

    /**
     * Promise wrapper para getCurrentPosition
     */
    getCurrentPositionPromise() {
        return new Promise((resolve, reject) => {
            navigator.geolocation.getCurrentPosition(resolve, reject, {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 300000 // 5 minutos
            });
        });
    }

    /**
     * Manejar errores de geolocalización
     */
    handleLocationError(error) {
        let message = 'No se pudo obtener tu ubicación';
        let showRetryButton = false;
        
        switch (error.code) {
            case error.PERMISSION_DENIED:
                message = 'Permisos de ubicación denegados. Por favor, permite el acceso a tu ubicación en la configuración del navegador para encontrar ofertas cercanas.';
                showRetryButton = true;
                break;
            case error.POSITION_UNAVAILABLE:
                message = 'Ubicación no disponible. Verifica tu conexión a internet y que el GPS esté activado.';
                showRetryButton = true;
                break;
            case error.TIMEOUT:
                message = 'Tiempo de espera agotado. Intenta nuevamente.';
                showRetryButton = true;
                break;
            default:
                message = 'Error desconocido al obtener la ubicación. Intenta nuevamente.';
                showRetryButton = true;
                break;
        }
        
        this.showNotification(message, 'error');
        
        // Mostrar botón de reintentar si es apropiado
        if (showRetryButton) {
            setTimeout(() => {
                this.showRetryLocationButton();
            }, 2000);
        }
    }

    /**
     * Mostrar botón de reintentar ubicación
     */
    showRetryLocationButton() {
        const notification = document.createElement('div');
        notification.className = 'fixed top-20 right-4 z-50 px-4 py-2 bg-blue-500 text-white rounded-lg shadow-lg';
        notification.innerHTML = `
            <div class="flex items-center gap-2">
                <i class="fas fa-map-marker-alt"></i>
                <span class="text-sm">¿Reintentar ubicación?</span>
                <button onclick="mapManager.getCurrentLocation(); this.parentElement.parentElement.remove();" 
                        class="ml-2 px-2 py-1 bg-white text-blue-500 rounded text-xs hover:bg-gray-100">
                    Reintentar
                </button>
                <button onclick="this.parentElement.parentElement.remove();" 
                        class="ml-1 text-white hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Auto-remover después de 10 segundos
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 10000);
    }

    /**
     * Agregar marcador del usuario
     */
    addUserMarker() {
        if (!this.userLocation) return;

        this.userMarker = new google.maps.Marker({
            position: this.userLocation,
            map: this.map,
            title: 'Tu ubicación',
            icon: {
                url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(`
                    <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="10" fill="#3b82f6" stroke="white" stroke-width="2"/>
                        <circle cx="12" cy="12" r="4" fill="white"/>
                    </svg>
                `),
                scaledSize: new google.maps.Size(24, 24),
                anchor: new google.maps.Point(12, 12)
            },
            animation: google.maps.Animation.BOUNCE
        });

        // Detener animación después de 2 segundos
        setTimeout(() => {
            if (this.userMarker) {
                this.userMarker.setAnimation(null);
            }
        }, 2000);
    }

    /**
     * Centrar mapa en el usuario
     */
    centerMapOnUser() {
        if (!this.userLocation || !this.map) return;
        
        this.map.setCenter(this.userLocation);
        this.map.setZoom(this.config.userZoom);
    }

    /**
     * Buscar ofertas cercanas
     */
    async searchNearby() {
        if (!this.userLocation) {
            this.showNotification('Primero obtén tu ubicación', 'error');
            return;
        }

        this.showLoading(true);

        try {
            const searchParams = this.getSearchParams();
            const response = await fetch('/api/search-nearby', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.getCSRFToken()
                },
                body: JSON.stringify({
                    latitude: this.userLocation.lat,
                    longitude: this.userLocation.lng,
                    ...searchParams
                })
            });

            const data = await response.json();

            if (data.success) {
                const normalizedList = (data.data || []).map(e => this.normalizeEmpresa(e));
                this.empresas = normalizedList;
                this.filteredEmpresas = normalizedList;
                this.displayResults(normalizedList);
                this.updateMapMarkers(normalizedList);
                this.centerMapOnEmpresas();
                this.showNotification(`Encontradas ${normalizedList.length} empresas cercanas`, 'success');
            } else {
                this.showNotification('Error en la búsqueda', 'error');
            }
        } catch (error) {
            console.error('Error en búsqueda:', error);
            this.showNotification('Error al buscar ofertas cercanas', 'error');
        } finally {
            this.showLoading(false);
        }
    }

    /**
     * Obtener parámetros de búsqueda del formulario
     */
    getSearchParams() {
        return {
            radius: document.getElementById('radius-select')?.value || 10,
            category: document.getElementById('category-select')?.value || null
        };
    }

    /**
     * Obtener token CSRF
     */
    getCSRFToken() {
        const token = document.querySelector('meta[name="csrf-token"]');
        return token ? token.getAttribute('content') : '';
    }

    /**
     * Actualizar marcadores del mapa
     */
    updateMapMarkers(empresas) {
        this.clearMarkers();
        
        empresas.forEach(empresa => {
            const normalized = this.normalizeCoordinates(empresa.coordinates);
            if (normalized) {
                const safeEmpresa = { ...empresa, coordinates: normalized };
                const marker = this.createEmpresaMarker(safeEmpresa);
                this.markers.push(marker);
            }
        });
    }

    /**
     * Mostrar resultados en el panel lateral
     */
    displayResults(empresas) {
        const resultsContainer = document.getElementById('results-list');
        if (!resultsContainer) return;

        if (empresas.length === 0) {
            resultsContainer.innerHTML = `
                <div class="no-results">
                    <i class="fas fa-search text-4xl text-gray-400 mb-4"></i>
                    <h4>No se encontraron empresas</h4>
                    <p>Intenta ampliar el radio de búsqueda o cambiar los filtros.</p>
                    <button onclick="mapManager.clearFilters()" class="btn btn-outline mt-4">
                        <i class="fas fa-refresh mr-2"></i>
                        Limpiar filtros
                    </button>
                </div>
            `;
            return;
        }

        resultsContainer.innerHTML = empresas.map(empresa => this.createResultItem(empresa)).join('');
    }

    /**
     * Crear elemento de resultado
     */
    createResultItem(empresa) {
        const products = empresa.products || [];
        const hasProducts = products.length > 0;
        
        return `
            <div class="result-item" onclick="mapManager.centerOnEmpresa(${empresa.id})">
                <div class="result-header">
                    <h3 class="result-name">${empresa.name}</h3>
                    ${empresa.distance ? `<span class="result-distance">${empresa.distance} km</span>` : ''}
                </div>
                <p class="result-address">${empresa.address}</p>
                <div class="result-stats">
                    <span class="stat-item">${empresa.products_count} productos</span>
                    ${empresa.discounted_products_count > 0 ? 
                        `<span class="stat-item discount">${empresa.discounted_products_count} con descuento</span>` : 
                        ''
                    }
                </div>
                ${hasProducts ? `
                    <div class="result-products">
                        ${products.slice(0, 2).map(product => `
                            <div class="product-preview">
                                <img src="${product.image}" alt="${product.name}" class="product-thumb" 
                                     onerror="this.src='${window.defaultProductImage || '/images/default-product.png'}'">
                                <div class="product-details">
                                    <p class="product-name">${product.name}</p>
                                    <div class="product-price ${product.has_discount ? 'discounted' : ''}">
                                        ${product.has_discount ? `
                                            <span class="discount-price">$${this.formatPrice(product.discounted_price)}</span>
                                            <span class="discount-badge">-${product.discount_percentage}%</span>
                                        ` : `
                                            <span class="price">$${this.formatPrice(product.price)}</span>
                                        `}
                                    </div>
                                </div>
                            </div>
                        `).join('')}
                        ${products.length > 2 ? `<p class="text-xs text-gray-500 mt-2">+${products.length - 2} productos más</p>` : ''}
                    </div>
                ` : ''}
            </div>
        `;
    }

    

    /**
     * Limpiar filtros
     */
    clearFilters() {
        // Resetear formulario
        const form = document.querySelector('.filters-section');
        if (form) {
            form.reset();
        }
        
        // Mostrar todas las empresas
        this.filteredEmpresas = [...this.empresas];
        this.displayResults(this.filteredEmpresas);
        this.updateMapMarkers(this.filteredEmpresas);
        this.centerMapOnEmpresas();
    }

    /**
     * Limpiar marcadores
     */
    clearMarkers() {
        // Limpiar clusterer si existe
        if (this.markerClusterer) {
            this.markerClusterer.clearMarkers();
            this.markerClusterer = null;
        }
        
        // Limpiar marcadores individuales
        this.markers.forEach(marker => marker.setMap(null));
        this.markers = [];
    }

    /**
     * Mostrar/ocultar loading
     */
    showLoading(show) {
        const spinner = document.getElementById('loading-spinner');
        if (spinner) {
            spinner.style.display = show ? 'flex' : 'none';
        }
    }

    /**
     * Ocultar overlay de carga inicial
     */
    hideInitialLoading() {
        const overlay = document.getElementById('initial-loading');
        if (overlay) {
            overlay.classList.add('hidden');
            // También quitar del flujo tras animación
            setTimeout(() => {
                overlay.style.display = 'none';
            }, 300);
        }
    }

    /**
     * Formatear precio
     */
    formatPrice(price) {
        return new Intl.NumberFormat('es-CO').format(Math.round(price));
    }

    /**
     * Mostrar notificación
     */
    showNotification(message, type = 'info') {
        // Remover notificaciones anteriores
        const existingNotifications = document.querySelectorAll('.notification-toast');
        existingNotifications.forEach(notif => notif.remove());

        const notification = document.createElement('div');
        const icons = {
            success: 'fa-check-circle',
            error: 'fa-exclamation-circle',
            warning: 'fa-exclamation-triangle',
            info: 'fa-info-circle'
        };
        const colors = {
            success: 'bg-green-500',
            error: 'bg-red-500',
            warning: 'bg-yellow-500',
            info: 'bg-blue-500'
        };

        notification.className = `notification-toast fixed top-4 right-4 z-50 px-6 py-4 rounded-lg text-white font-medium transform transition-all duration-300 shadow-lg ${colors[type] || colors.info}`;
        notification.style.transform = 'translateX(100%)';

        notification.innerHTML = `
            <div class="flex items-center gap-3">
                <i class="fas ${icons[type] || icons.info} text-lg"></i>
                <span>${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-2 text-white hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;

        document.body.appendChild(notification);

        // Animación de entrada
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);

        // Auto-remover después de 4 segundos
        setTimeout(() => {
            if (notification.parentElement) {
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.remove();
                    }
                }, 300);
            }
        }, 4000);
    }

    /**
     * Mostrar error
     */
    showError(message) {
        this.showNotification(message, 'error');
    }

    /**
     * Vincular eventos
     */
    bindEvents() {
        // Event listeners para botones
        document.addEventListener('DOMContentLoaded', () => {
            const searchBtn = document.getElementById('search-btn');
            const locationBtn = document.getElementById('my-location-btn');
            const municipioSelect = document.getElementById('municipio-select');
            const categorySelect = document.getElementById('category-select');
            
            if (searchBtn) {
                searchBtn.addEventListener('click', () => this.searchNearby());
            }
            
            if (locationBtn) {
                locationBtn.addEventListener('click', () => this.getCurrentLocation());
            }

            // Filtros en tiempo real
            if (municipioSelect) {
                municipioSelect.addEventListener('change', () => this.applyFilters());
            }

            if (categorySelect) {
                categorySelect.addEventListener('change', () => this.applyFilters());
            }
        });
    }

    /**
     * Aplicar filtros locales
     */
    applyFilters() {
        const municipio = document.getElementById('municipio-select')?.value || '';
        const category = document.getElementById('category-select')?.value || '';

        // Filtrar empresas localmente
        this.filteredEmpresas = this.empresas.filter(empresa => {
            // Filtro por municipio
            if (municipio && !empresa.address.toLowerCase().includes(municipio.toLowerCase())) {
                return false;
            }

            // Filtro por categoría
            if (category && empresa.products) {
                const hasCategoryProduct = empresa.products.some(producto => 
                    producto.category_id == category
                );
                if (!hasCategoryProduct) return false;
            }

            return true;
        });

        // Actualizar marcadores y resultados
        this.addEmpresaMarkers();
        this.displayResults(this.filteredEmpresas);
        this.centerMapOnEmpresas();
        
        // Actualizar contador
        this.updateResultsCount();
        
        // Mostrar notificación de filtros aplicados
        if (municipio || category) {
            this.showNotification(`Filtros aplicados: ${this.filteredEmpresas.length} empresas encontradas`, 'info');
        }
    }

    /**
     * Actualizar contador de resultados
     */
    updateResultsCount() {
        const resultsCount = document.getElementById('results-count');
        if (resultsCount) {
            resultsCount.textContent = `${this.filteredEmpresas.length} empresas encontradas`;
        }
    }

    /**
     * Limpiar recursos
     */
    destroy() {
        if (this.observer) {
            this.observer.disconnect();
        }
        this.clearMarkers();
        if (this.userMarker) {
            this.userMarker.setMap(null);
        }
        if (this.currentInfoWindow) {
            this.currentInfoWindow.close();
        }
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    try {
        window.mapManager = new MapManager();
    } catch (error) {
        console.error('Error inicializando el mapa:', error);
        // Mostrar mensaje de error al usuario
        const mapContainer = document.getElementById('map');
        if (mapContainer) {
            mapContainer.innerHTML = `
                <div class="flex flex-col items-center justify-center h-full bg-gray-100 text-center p-8">
                    <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-lg max-w-md">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-exclamation-triangle text-xl mr-2"></i>
                            <h3 class="font-semibold">Error al cargar el mapa</h3>
                        </div>
                        <p class="text-sm mb-3">
                            Hubo un problema al inicializar el mapa. Por favor, recarga la página.
                        </p>
                        <button onclick="location.reload()" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition-colors">
                            <i class="fas fa-refresh mr-2"></i>
                            Recargar página
                        </button>
                    </div>
                </div>
            `;
        }
    }
});

// Limpiar recursos cuando se cierre la página
window.addEventListener('beforeunload', () => {
    if (window.mapManager) {
        window.mapManager.destroy();
    }
});

// Manejar errores globales
window.addEventListener('error', (event) => {
    console.error('Error global:', event.error);
});

// Manejar promesas rechazadas
window.addEventListener('unhandledrejection', (event) => {
    console.error('Promesa rechazada:', event.reason);
});
