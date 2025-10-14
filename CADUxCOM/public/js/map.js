/**
 * Mapa de Ofertas CADUxCOM - JavaScript Moderno
 * Funcionalidades: Lazy loading, geolocalización, filtros dinámicos, responsividad
 */

class ModernMapManager {
    constructor() {
        this.map = null;
        this.markers = [];
        this.userLocation = null;
        this.userMarker = null;
        this.empresas = [];
        this.filteredEmpresas = [];
        this.currentInfoWindow = null;
        this.isMapLoaded = false;
        this.isLoading = false;
        
        // Configuración
        this.config = {
            defaultLocation: { lat: 2.9271, lng: -75.2819 }, // Centro del Huila
            defaultZoom: 8,
            userZoom: 14,
            maxZoom: 18,
            minZoom: 6,
            apiKey: window.googleMapsApiKey || 'AIzaSyBMDPpV5x-_Xl-ekz1kg48nuD79NgTN8mU'
        };
        
        // Elementos DOM
        this.elements = {
            map: null,
            sidebar: null,
            sidebarToggle: null,
            loadingOverlay: null,
            noCompaniesOverlay: null,
            resultsList: null,
            resultsCount: null,
            searchBtn: null,
            locationBtn: null,
            municipioFilter: null,
            radiusFilter: null,
            categoryFilter: null
        };
        
        this.init();
    }
    
    /**
     * Inicializar el gestor del mapa
     */
    init() {
        this.bindElements();
        this.bindEvents();
        this.setupResponsive();
        this.checkApiKey();
        
        // Inicializar el mapa automáticamente si Google Maps está disponible
        if (typeof google !== 'undefined' && google.maps) {
            console.log('Google Maps disponible, inicializando mapa...');
            this.initMap();
        } else {
            console.log('Google Maps no disponible aún, esperando...');
        }
    }
    
    /**
     * Vincular elementos DOM
     */
    bindElements() {
        this.elements.map = document.getElementById('map');
        this.elements.sidebar = document.getElementById('map-sidebar');
        this.elements.sidebarToggle = document.getElementById('sidebar-toggle');
        this.elements.loadingOverlay = document.getElementById('map-loading');
        this.elements.noCompaniesOverlay = document.getElementById('no-companies-overlay');
        this.elements.resultsList = document.getElementById('results-list');
        this.elements.resultsCount = document.getElementById('results-count');
        this.elements.searchBtn = document.getElementById('search-nearby-btn');
        this.elements.locationBtn = document.getElementById('my-location-btn');
        this.elements.municipioFilter = document.getElementById('municipio-filter');
        this.elements.radiusFilter = document.getElementById('radius-filter');
        this.elements.categoryFilter = document.getElementById('category-filter');
    }
    
    /**
     * Vincular eventos
     */
    bindEvents() {
        // Eventos de botones
        if (this.elements.searchBtn) {
            this.elements.searchBtn.addEventListener('click', () => this.searchNearby());
        }
        
        if (this.elements.locationBtn) {
            this.elements.locationBtn.addEventListener('click', () => this.getCurrentLocation());
        }
        
        if (this.elements.sidebarToggle) {
            this.elements.sidebarToggle.addEventListener('click', () => this.toggleSidebar());
        }
        
        // Eventos de filtros
        if (this.elements.municipioFilter) {
            this.elements.municipioFilter.addEventListener('change', () => this.applyFilters());
        }
        
        if (this.elements.categoryFilter) {
            this.elements.categoryFilter.addEventListener('change', () => this.applyFilters());
        }
        
        // Eventos de controles del mapa
        const centerBtn = document.getElementById('center-map-btn');
        const zoomInBtn = document.getElementById('zoom-in-btn');
        const zoomOutBtn = document.getElementById('zoom-out-btn');
        
        if (centerBtn) {
            centerBtn.addEventListener('click', () => this.centerMapOnEmpresas());
        }
        
        if (zoomInBtn) {
            zoomInBtn.addEventListener('click', () => this.zoomIn());
        }
        
        if (zoomOutBtn) {
            zoomOutBtn.addEventListener('click', () => this.zoomOut());
        }
        
        // Eventos de teclado
        document.addEventListener('keydown', (e) => this.handleKeyboard(e));
        
        // Eventos de redimensionamiento
        window.addEventListener('resize', () => this.handleResize());
    }
    
    /**
     * Configurar responsividad
     */
    setupResponsive() {
        const mediaQuery = window.matchMedia('(max-width: 768px)');
        
        const handleMediaChange = (e) => {
            if (e.matches) {
                // Móvil
                this.elements.sidebar.classList.remove('open');
            } else {
                // Desktop
                this.elements.sidebar.classList.add('open');
            }
        };
        
        mediaQuery.addListener(handleMediaChange);
        handleMediaChange(mediaQuery);
    }
    
    /**
     * Verificar API Key
     */
    checkApiKey() {
        if (!this.config.apiKey || this.config.apiKey === 'YOUR_API_KEY') {
            this.showError('API Key de Google Maps no configurada');
            return;
        }
    }
    
    /**
     * Alternar sidebar en móvil
     */
    toggleSidebar() {
        this.elements.sidebar.classList.toggle('open');
    }
    
    /**
     * Manejar eventos de teclado
     */
    handleKeyboard(e) {
        // ESC para cerrar sidebar en móvil
        if (e.key === 'Escape' && window.innerWidth <= 768) {
            this.elements.sidebar.classList.remove('open');
        }
        
        // Ctrl/Cmd + F para buscar
        if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
            e.preventDefault();
            this.elements.searchBtn?.click();
        }
    }
    
    /**
     * Manejar redimensionamiento
     */
    handleResize() {
        if (this.map && window.google?.maps) {
            setTimeout(() => {
                google.maps.event.trigger(this.map, 'resize');
            }, 100);
        }
    }
    
    /**
     * Verificar si Google Maps está disponible
     */
    checkGoogleMapsAvailable() {
        return typeof google !== 'undefined' && google.maps;
    }
    
    /**
     * Inicializar el mapa
     */
    initMap() {
        if (this.isMapLoaded) return;
        
        console.log('Inicializando mapa...');
        
        try {
            this.showLoading(true);
            
            // Verificar que Google Maps esté disponible
            if (!this.checkGoogleMapsAvailable()) {
                throw new Error('Google Maps API no está disponible');
            }
            
            console.log('Google Maps disponible, creando mapa...');
            
            this.map = new google.maps.Map(this.elements.map, {
                zoom: this.config.defaultZoom,
                center: this.config.defaultLocation,
                mapTypeId: 'roadmap',
                styles: this.getMapStyles(),
                mapTypeControl: true,
                streetViewControl: false,
                fullscreenControl: true,
                zoomControl: false, // Usamos controles personalizados
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
            
            console.log('Mapa creado exitosamente');
            
            this.isMapLoaded = true;
            this.loadEmpresas();
            this.hideLoading();
            
        } catch (error) {
            console.error('Error inicializando mapa:', error);
            this.showError('Error al cargar el mapa: ' + error.message);
            this.hideLoading();
        }
    }
    
    /**
     * Obtener estilos del mapa
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
                stylers: [{ color: '#89CF6D' }]
            },
            {
                featureType: 'landscape',
                elementType: 'geometry',
                stylers: [{ color: '#FFFFFF' }]
            }
        ];
    }
    
    /**
     * Cargar empresas
     */
    loadEmpresas() {
        this.empresas = window.empresasData || [];
        this.filteredEmpresas = [...this.empresas];
        
        console.log('Empresas cargadas:', this.empresas);
        console.log('Número de empresas:', this.empresas.length);
        
        if (this.empresas.length === 0) {
            console.log('No hay empresas disponibles');
            this.showNoCompanies();
            return;
        }
        
        // Verificar que las empresas tengan coordenadas válidas
        const empresasConCoordenadas = this.empresas.filter(empresa => 
            empresa.coordinates && 
            empresa.coordinates.lat && 
            empresa.coordinates.lng
        );
        
        console.log('Empresas con coordenadas válidas:', empresasConCoordenadas.length);
        
        if (empresasConCoordenadas.length === 0) {
            console.log('No hay empresas con coordenadas válidas');
            this.showNoCompanies();
            return;
        }
        
        this.addMarkers();
        this.centerMapOnEmpresas();
        this.updateResultsCount();
    }
    
    /**
     * Mostrar estado sin empresas
     */
    showNoCompanies() {
        this.elements.noCompaniesOverlay.style.display = 'flex';
    }
    
    /**
     * Agregar marcadores al mapa
     */
    addMarkers() {
        this.clearMarkers();
        
        console.log('Agregando marcadores para', this.filteredEmpresas.length, 'empresas');
        
        this.filteredEmpresas.forEach((empresa, index) => {
            if (empresa.coordinates && empresa.coordinates.lat && empresa.coordinates.lng) {
                console.log(`Creando marcador ${index + 1}:`, empresa.name, empresa.coordinates);
                const marker = this.createMarker(empresa);
                this.markers.push(marker);
            } else {
                console.warn(`Empresa sin coordenadas válidas:`, empresa.name, empresa.coordinates);
            }
        });
        
        console.log('Marcadores creados:', this.markers.length);
    }
    
    /**
     * Crear marcador
     */
    createMarker(empresa) {
        const marker = new google.maps.Marker({
            position: empresa.coordinates,
            map: this.map,
            title: empresa.name,
            icon: this.createMarkerIcon(empresa),
            animation: google.maps.Animation.DROP
        });
        
        const infoWindow = new google.maps.InfoWindow({
            content: this.createInfoWindowContent(empresa)
        });
        
        marker.addListener('click', () => {
            if (this.currentInfoWindow) {
                this.currentInfoWindow.close();
            }
            infoWindow.open(this.map, marker);
            this.currentInfoWindow = infoWindow;
        });
        
        return marker;
    }
    
    /**
     * Crear icono del marcador
     */
    createMarkerIcon(empresa) {
        const discountCount = empresa.discounted_products_count || 0;
        const color = discountCount > 0 ? '#49874E' : '#89CF6D';
        
        return {
            url: `data:image/svg+xml;charset=UTF-8,${encodeURIComponent(`
                <svg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="20" cy="20" r="18" fill="${color}" stroke="white" stroke-width="2"/>
                    <text x="20" y="26" text-anchor="middle" fill="white" font-size="12" font-weight="bold">${discountCount}</text>
                </svg>
            `)}`,
            scaledSize: new google.maps.Size(40, 40),
            anchor: new google.maps.Point(20, 20)
        };
    }
    
    /**
     * Crear contenido del InfoWindow
     */
    createInfoWindowContent(empresa) {
        const products = empresa.products || [];
        const distance = empresa.distance ? `${empresa.distance.toFixed(1)} km` : '';
        
        return `
            <div class="info-window">
                <div class="info-header">
                    <h3 class="info-title">${empresa.name}</h3>
                    ${distance ? `<span class="info-distance">${distance}</span>` : ''}
                </div>
                <p class="info-address">
                    <i class="fas fa-map-marker-alt"></i>
                    ${empresa.address}
                </p>
                <div class="info-stats">
                    <span class="stat-item">
                        <i class="fas fa-box"></i>
                        ${empresa.products_count} productos
                    </span>
                    ${empresa.discounted_products_count > 0 ? 
                        `<span class="stat-item discount">
                            <i class="fas fa-percentage"></i>
                            ${empresa.discounted_products_count} con descuento
                        </span>` : 
                        ''
                    }
                </div>
                ${products.length > 0 ? `
                    <div class="info-products">
                        <h4>Productos destacados:</h4>
                        ${products.slice(0, 2).map(product => `
                            <div class="product-item">
                                <img src="${product.image}" alt="${product.name}" class="product-image">
                                <div class="product-info">
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
                    </div>
                ` : ''}
                <div class="info-actions">
                    <button onclick="mapManager.centerOnEmpresa(${empresa.id})" class="btn btn-primary btn-sm">
                        <i class="fas fa-crosshairs"></i>
                        Centrar aquí
                    </button>
                    <button onclick="window.open('/empresa/${empresa.id}', '_blank')" class="btn btn-secondary btn-sm">
                        <i class="fas fa-external-link-alt"></i>
                        Ver empresa
                    </button>
                </div>
            </div>
        `;
    }
    
    /**
     * Centrar mapa en empresas
     */
    centerMapOnEmpresas() {
        if (this.filteredEmpresas.length === 0) {
            this.map.setCenter(this.config.defaultLocation);
            this.map.setZoom(this.config.defaultZoom);
            return;
        }
        
        const bounds = new google.maps.LatLngBounds();
        let validCoordinates = 0;
        
        this.filteredEmpresas.forEach(empresa => {
            if (empresa.coordinates && empresa.coordinates.lat && empresa.coordinates.lng) {
                bounds.extend(empresa.coordinates);
                validCoordinates++;
            }
        });
        
        if (validCoordinates > 0) {
            if (validCoordinates === 1) {
                const empresa = this.filteredEmpresas.find(e => e.coordinates);
                this.map.setCenter(empresa.coordinates);
                this.map.setZoom(this.config.userZoom);
            } else {
                this.map.fitBounds(bounds);
                
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
        }
    }
    
    /**
     * Centrar en empresa específica
     */
    centerOnEmpresa(empresaId) {
        const empresa = this.empresas.find(e => e.id === empresaId);
        if (!empresa || !empresa.coordinates) return;
        
        this.map.setCenter(empresa.coordinates);
        this.map.setZoom(this.config.userZoom);
        
        // Cerrar sidebar en móvil después de centrar
        if (window.innerWidth <= 768) {
            this.elements.sidebar.classList.remove('open');
        }
        
        // Encontrar y hacer clic en el marcador
        const marker = this.markers.find(m => {
            const pos = m.getPosition();
            return pos.lat() === empresa.coordinates.lat && pos.lng() === empresa.coordinates.lng;
        });
        
        if (marker) {
            google.maps.event.trigger(marker, 'click');
        }
    }
    
    /**
     * Obtener ubicación actual
     */
    async getCurrentLocation() {
        if (!navigator.geolocation) {
            this.showNotification('Geolocalización no soportada', 'error');
            return;
        }
        
        this.setButtonLoading(this.elements.locationBtn, 'Obteniendo ubicación...');
        
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
            this.handleLocationError(error);
        } finally {
            this.setButtonLoading(this.elements.locationBtn, null);
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
                maximumAge: 300000
            });
        });
    }
    
    /**
     * Manejar errores de geolocalización
     */
    handleLocationError(error) {
        let message = 'No se pudo obtener tu ubicación';
        
        switch (error.code) {
            case error.PERMISSION_DENIED:
                message = 'Permisos de ubicación denegados. Permite el acceso en la configuración del navegador.';
                break;
            case error.POSITION_UNAVAILABLE:
                message = 'Ubicación no disponible. Verifica tu conexión y GPS.';
                break;
            case error.TIMEOUT:
                message = 'Tiempo de espera agotado. Intenta nuevamente.';
                break;
        }
        
        this.showNotification(message, 'error');
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
                        <circle cx="12" cy="12" r="10" fill="#AA5FC7" stroke="white" stroke-width="2"/>
                        <circle cx="12" cy="12" r="4" fill="white"/>
                    </svg>
                `),
                scaledSize: new google.maps.Size(24, 24),
                anchor: new google.maps.Point(12, 12)
            },
            animation: google.maps.Animation.BOUNCE
        });
        
        setTimeout(() => {
            if (this.userMarker) {
                this.userMarker.setAnimation(null);
            }
        }, 2000);
    }
    
    /**
     * Centrar mapa en usuario
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
        
        this.setButtonLoading(this.elements.searchBtn, 'Buscando...');
        
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
                this.empresas = data.data || [];
                this.filteredEmpresas = [...this.empresas];
                this.updateResults();
                this.addMarkers();
                this.centerMapOnEmpresas();
                this.showNotification(`Encontradas ${this.empresas.length} empresas cercanas`, 'success');
            } else {
                this.showNotification('Error en la búsqueda', 'error');
            }
        } catch (error) {
            console.error('Error en búsqueda:', error);
            this.showNotification('Error al buscar ofertas cercanas', 'error');
        } finally {
            this.setButtonLoading(this.elements.searchBtn, null);
        }
    }
    
    /**
     * Obtener parámetros de búsqueda
     */
    getSearchParams() {
        return {
            radius: this.elements.radiusFilter?.value || 10,
            category: this.elements.categoryFilter?.value || null
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
     * Aplicar filtros
     */
    applyFilters() {
        const municipio = this.elements.municipioFilter?.value || '';
        const category = this.elements.categoryFilter?.value || '';
        
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
        
        this.updateResults();
        this.addMarkers();
        this.centerMapOnEmpresas();
        this.updateResultsCount();
        
        if (municipio || category) {
            this.showNotification(`Filtros aplicados: ${this.filteredEmpresas.length} empresas encontradas`, 'info');
        }
    }
    
    /**
     * Actualizar resultados
     */
    updateResults() {
        if (!this.elements.resultsList) return;
        
        if (this.filteredEmpresas.length === 0) {
            this.elements.resultsList.innerHTML = `
                <div class="no-results">
                    <div class="no-results-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3>No se encontraron empresas</h3>
                    <p>Intenta ampliar el radio de búsqueda o cambiar los filtros.</p>
                    <button onclick="mapManager.clearFilters()" class="btn btn-primary">
                        <i class="fas fa-refresh"></i>
                        Limpiar filtros
                    </button>
                </div>
            `;
            return;
        }
        
        this.elements.resultsList.innerHTML = this.filteredEmpresas.map(empresa => 
            this.createResultCard(empresa)
        ).join('');
    }
    
    /**
     * Crear tarjeta de resultado
     */
    createResultCard(empresa) {
        const products = empresa.products || [];
        const distance = empresa.distance ? `${empresa.distance.toFixed(1)} km` : '';
        
        return `
            <div class="result-card" data-empresa-id="${empresa.id}">
                <div class="result-header">
                    <h3 class="result-name">${empresa.name}</h3>
                    ${distance ? `<span class="result-distance">${distance}</span>` : ''}
                </div>
                <div class="result-address">
                    <i class="fas fa-map-marker-alt"></i>
                    ${empresa.address}
                </div>
                <div class="result-stats">
                    <div class="stat-item">
                        <i class="fas fa-box"></i>
                        <span>${empresa.products_count} productos</span>
                    </div>
                    ${empresa.discounted_products_count > 0 ? 
                        `<div class="stat-item discount">
                            <i class="fas fa-percentage"></i>
                            <span>${empresa.discounted_products_count} con descuento</span>
                        </div>` : 
                        ''
                    }
                </div>
                ${products.length > 0 ? `
                    <div class="result-products">
                        <h4>Productos destacados:</h4>
                        <div class="products-grid">
                            ${products.slice(0, 3).map(product => `
                                <div class="product-card">
                                    <img src="${product.image}" alt="${product.name}" class="product-image" 
                                         onerror="this.src='/images/default-product.png'">
                                    <div class="product-info">
                                        <h5 class="product-name">${product.name}</h5>
                                        <div class="product-price ${product.has_discount ? 'discounted' : ''}">
                                            ${product.has_discount ? `
                                                <span class="price-discounted">$${this.formatPrice(product.discounted_price)}</span>
                                                <span class="price-original">$${this.formatPrice(product.price)}</span>
                                                <span class="discount-badge">-${product.discount_percentage}%</span>
                                            ` : `
                                                <span class="price-normal">$${this.formatPrice(product.price)}</span>
                                            `}
                                        </div>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                        ${products.length > 3 ? `<p class="more-products">+${products.length - 3} productos más</p>` : ''}
                    </div>
                ` : ''}
                <div class="result-actions">
                    <button class="btn btn-outline btn-sm" onclick="mapManager.centerOnEmpresa(${empresa.id})">
                        <i class="fas fa-crosshairs"></i>
                        Centrar aquí
                    </button>
                    <button class="btn btn-primary btn-sm" onclick="window.open('/empresa/${empresa.id}', '_blank')">
                        <i class="fas fa-external-link-alt"></i>
                        Ver empresa
                    </button>
                </div>
            </div>
        `;
    }
    
    /**
     * Limpiar filtros
     */
    clearFilters() {
        if (this.elements.municipioFilter) this.elements.municipioFilter.value = '';
        if (this.elements.categoryFilter) this.elements.categoryFilter.value = '';
        
        this.filteredEmpresas = [...this.empresas];
        this.updateResults();
        this.addMarkers();
        this.centerMapOnEmpresas();
        this.updateResultsCount();
        
        this.showNotification('Filtros limpiados', 'info');
    }
    
    /**
     * Actualizar contador de resultados
     */
    updateResultsCount() {
        if (this.elements.resultsCount) {
            this.elements.resultsCount.textContent = `${this.filteredEmpresas.length} empresas`;
        }
    }
    
    /**
     * Limpiar marcadores
     */
    clearMarkers() {
        this.markers.forEach(marker => marker.setMap(null));
        this.markers = [];
    }
    
    /**
     * Mostrar/ocultar loading
     */
    showLoading(show) {
        if (this.elements.loadingOverlay) {
            this.elements.loadingOverlay.style.display = show ? 'flex' : 'none';
        }
    }
    
    /**
     * Ocultar loading
     */
    hideLoading() {
        this.showLoading(false);
    }
    
    /**
     * Configurar estado de carga en botón
     */
    setButtonLoading(button, text) {
        if (!button) return;
        
        if (text) {
            button.disabled = true;
            button.innerHTML = `<i class="fas fa-spinner fa-spin"></i> ${text}`;
        } else {
            button.disabled = false;
            // Restaurar texto original según el botón
            if (button === this.elements.searchBtn) {
                button.innerHTML = '<i class="fas fa-search"></i> Buscar Cercanas';
            } else if (button === this.elements.locationBtn) {
                button.innerHTML = '<i class="fas fa-crosshairs"></i> Mi Ubicación';
            }
        }
    }
    
    /**
     * Zoom in
     */
    zoomIn() {
        if (this.map) {
            this.map.setZoom(this.map.getZoom() + 1);
        }
    }
    
    /**
     * Zoom out
     */
    zoomOut() {
        if (this.map) {
            this.map.setZoom(this.map.getZoom() - 1);
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
            success: '#49874E',
            error: '#AA5FC7',
            warning: '#49874E',
            info: '#89CF6D'
        };
        
        notification.className = 'notification-toast';
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 10000;
            background: ${colors[type] || colors.info};
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            transform: translateX(100%);
            transition: transform 0.3s ease;
            max-width: 400px;
            font-family: 'Inter', sans-serif;
        `;
        
        notification.innerHTML = `
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <i class="fas ${icons[type] || icons.info}" style="font-size: 1.125rem;"></i>
                <span style="font-weight: 500;">${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" 
                        style="background: none; border: none; color: white; cursor: pointer; margin-left: auto;">
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
     * Limpiar recursos
     */
    destroy() {
        this.clearMarkers();
        if (this.userMarker) {
            this.userMarker.setMap(null);
        }
        if (this.currentInfoWindow) {
            this.currentInfoWindow.close();
        }
    }
}

// Función global para inicializar el mapa
function initMap() {
    try {
        console.log('Inicializando mapa desde callback de Google Maps...');
        window.mapManager = new ModernMapManager();
        // El initMap() se llama automáticamente en el constructor de ModernMapManager
    } catch (error) {
        console.error('Error inicializando el mapa:', error);
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM cargado, verificando Google Maps...');
    // Si Google Maps ya está cargado, inicializar inmediatamente
    if (typeof google !== 'undefined' && google.maps) {
        console.log('Google Maps ya está disponible, inicializando...');
        initMap();
    } else {
        console.log('Esperando a que Google Maps se cargue...');
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

window.addEventListener('unhandledrejection', (event) => {
    console.error('Promesa rechazada:', event.reason);
});
