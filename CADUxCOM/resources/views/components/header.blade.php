<header class="main-header">
    <!-- Sección izquierda: Logo -->
    <div class="left-section">
        <a href="{{ route('home') }}" class="logo-link">
            <img src="{{ asset('images/logocort-caduxcom.png') }}" alt="Logo CADUxCOM" class="logo">
            <span class="logo-text">CADUxCOM</span>
        </a>
    </div>

    <!-- Sección central: Barra de búsqueda -->
    <div class="center-section">
        <div class="search-bar">
            <input type="text" placeholder="Buscar productos..." class="search-input">
            <button class="search-button" type="button">
                <img src="{{ asset('images/icon-search.png') }}" alt="Buscar" class="search-icon">
            </button>
            <!-- Contenedor del autocompletado -->
            <div class="search-autocomplete" id="searchAutocomplete">
                <!-- Los resultados del autocompletado se cargarán aquí dinámicamente -->
            </div>
        </div>
    </div>

    <!-- Sección derecha: Iconos de usuario -->
    <div class="right-section">
        <!-- Carrito de compras -->
        <div class="cart-container">
            <x-cart-counter />
        </div>

        <!-- Favoritos -->
        <a href="{{ route('wishlist.index') }}" class="header-icon-link" title="Mis Favoritos">
            <img src="{{ asset('images/favoritos.png') }}" alt="Favoritos" class="header-icon">
            <span class="wishlist-count" id="wishlist-count" data-url="{{ auth()->check() ? route('wishlist.count') : '' }}">0</span>
        </a>

        <!-- Ayuda -->
        <a href="{{ route('help') }}" class="header-icon-link" title="Ayuda">
            <img src="{{ asset('images/icon-help.png') }}" alt="Ayuda" class="header-icon">
        </a>

        <!-- Usuario / Login-Register -->
        <div class="user-section">
            @auth
                <!-- Usuario autenticado -->
                <div class="user-dropdown">
                    <button class="user-button" id="userButton" type="button">
                        <img src="{{ asset('images/icon-user.png') }}" alt="Usuario" class="user-icon">
                        <span class="user-name">{{ Auth::user()->name ?? 'Usuario' }}</span>
                        <span class="dropdown-arrow">▼</span>
                    </button>
                    <div class="user-menu" id="userMenu">
                        @if(Auth::guard('empresa')->check())
                            <a href="{{ route('empresa.perfil.edit') }}" class="menu-item">
                                <span class="menu-icon">🏢</span>
                                Perfil empresa
                            </a>
                        @else
                            <a href="{{ route('profile.edit') }}" class="menu-item">
                                <span class="menu-icon">👤</span>
                                Perfil usuario
                            </a>
                        @endif
                        <div class="menu-divider"></div>
                        <form method="POST" action="{{ route('logout') }}" class="logout-form">
                            @csrf
                            <button type="submit" class="menu-item logout-btn">
                                <span class="menu-icon">🚪</span>
                                Cerrar sesión
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <!-- Usuario no autenticado -->
                <div class="auth-buttons">
                    <a href="{{ route('login') }}" class="auth-btn login-btn">
                        <span class="btn-icon">🔑</span>
                        <span class="btn-text">Login</span>
                    </a>
                    <a href="{{ route('register') }}" class="auth-btn register-btn">
                        <span class="btn-icon">📝</span>
                        <span class="btn-text">Register</span>
                    </a>
                </div>
            @endauth
        </div>
    </div>
</header>

<!-- JavaScript del carrito -->
<script src="{{ asset('js/cart.js') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // === FUNCIONALIDAD DE WISHLIST ===
        const wishlistUrl = (document.getElementById('wishlist-count')?.dataset.url || null);

        function updateWishlistCountHeader() {
            if (!wishlistUrl) {
                // Si no hay URL, ocultar el contador
                const countElement = document.getElementById('wishlist-count');
                if (countElement) {
                    countElement.style.display = 'none';
                }
                return;
            }

            fetch(wishlistUrl)
                .then(response => response.json())
                .then(data => {
                    const countElement = document.getElementById('wishlist-count');
                    if (countElement) {
                        if (data.count > 0) {
                            countElement.textContent = data.count;
                            countElement.style.display = 'flex';
                        } else {
                            countElement.style.display = 'none';
                        }
                        countElement.classList.add('update');
                        setTimeout(() => countElement.classList.remove('update'), 500);
                    }
                })
                .catch(error => {
                    console.log('Error al obtener contador de wishlist:', error);
                });
        }

        // Hacer la función disponible globalmente
        window.updateWishlistCountHeader = updateWishlistCountHeader;

        updateWishlistCountHeader();

        // === FUNCIONALIDAD DE DROPDOWN DE USUARIO ===
        const userButton = document.getElementById('userButton');
        const userMenu = document.getElementById('userMenu');

        if (userButton && userMenu) {
            userButton.addEventListener('click', function(e) {
                e.stopPropagation();
                userMenu.classList.toggle('show');
                userButton.classList.toggle('active');
            });

            // Cerrar dropdown al hacer clic fuera
            document.addEventListener('click', function(event) {
                if (!userMenu.contains(event.target) && !userButton.contains(event.target)) {
                    userMenu.classList.remove('show');
                    userButton.classList.remove('active');
                }
            });

            // Cerrar dropdown con Escape
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    userMenu.classList.remove('show');
                    userButton.classList.remove('active');
                }
            });
        }

        // === FUNCIONALIDAD DE BÚSQUEDA ===
        const searchInput = document.querySelector('.search-input');
        const searchButton = document.querySelector('.search-button');

        // Función para realizar la búsqueda
        function performSearch() {
            const query = searchInput.value.trim();
            
            if (query.length === 0) {
                alert('Por favor, ingresa un término de búsqueda');
                return;
            }
            
            // Redirigir a la página de resultados de búsqueda
            window.location.href = `/search?q=${encodeURIComponent(query)}`;
        }

        if (searchButton) {
            searchButton.addEventListener('click', performSearch);
        }

        if (searchInput) {
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    performSearch();
                }
            });

            // Autocompletado en tiempo real
            const autocompleteContainer = document.getElementById('searchAutocomplete');
            let searchTimeout;
            let currentHighlightIndex = -1;
            let autocompleteResults = [];

            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                const query = this.value.trim();
                
                if (query.length >= 2) {
                    searchTimeout = setTimeout(() => {
                        fetchAutocomplete(query);
                    }, 300);
                } else {
                    hideAutocomplete();
                }
            });

            // Navegación con teclado
            searchInput.addEventListener('keydown', function(e) {
                if (!autocompleteContainer.classList.contains('show')) return;

                switch(e.key) {
                    case 'ArrowDown':
                        e.preventDefault();
                        currentHighlightIndex = Math.min(currentHighlightIndex + 1, autocompleteResults.length - 1);
                        updateHighlight();
                        break;
                    case 'ArrowUp':
                        e.preventDefault();
                        currentHighlightIndex = Math.max(currentHighlightIndex - 1, -1);
                        updateHighlight();
                        break;
                    case 'Enter':
                        e.preventDefault();
                        if (currentHighlightIndex >= 0) {
                            selectAutocompleteItem(autocompleteResults[currentHighlightIndex]);
                        } else {
                            performSearch();
                        }
                        break;
                    case 'Escape':
                        hideAutocomplete();
                        break;
                }
            });

            // Ocultar autocompletado al hacer clic fuera
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.search-bar')) {
                    hideAutocomplete();
                }
            });

            function fetchAutocomplete(query) {
                // Mostrar indicador de carga
                showLoading();

                fetch(`/api/search/autocomplete?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        autocompleteResults = data;
                        displayAutocomplete(data);
                    })
                    .catch(error => {
                        console.error('Error en autocompletado:', error);
                        hideAutocomplete();
                    });
            }

            function displayAutocomplete(results) {
                if (results.length === 0) {
                    showNoResults();
                    return;
                }

                let html = '';
                results.forEach((item, index) => {
                    html += `
                        <div class="autocomplete-item" data-index="${index}" onclick="selectAutocompleteItem(autocompleteResults[${index}])">
                            <div class="autocomplete-icon">🛒</div>
                            <div class="autocomplete-content">
                                <div class="autocomplete-title">${item.title}</div>
                                <div class="autocomplete-subtitle">${item.subtitle}</div>
                            </div>
                            <div class="autocomplete-price">$${item.price}</div>
                        </div>
                    `;
                });

                autocompleteContainer.innerHTML = html;
                autocompleteContainer.classList.add('show');
                currentHighlightIndex = -1;
            }

            function showLoading() {
                autocompleteContainer.innerHTML = '<div class="autocomplete-loading">Buscando...</div>';
                autocompleteContainer.classList.add('show');
            }

            function showNoResults() {
                autocompleteContainer.innerHTML = '<div class="autocomplete-no-results">No se encontraron productos</div>';
                autocompleteContainer.classList.add('show');
            }

            function hideAutocomplete() {
                autocompleteContainer.classList.remove('show');
                currentHighlightIndex = -1;
            }

            function updateHighlight() {
                const items = autocompleteContainer.querySelectorAll('.autocomplete-item');
                items.forEach((item, index) => {
                    item.classList.toggle('highlighted', index === currentHighlightIndex);
                });
            }

            function selectAutocompleteItem(item) {
                window.location.href = item.url;
            }

            // Hacer la función global para onclick
            window.selectAutocompleteItem = selectAutocompleteItem;
        }

        // === RESPONSIVE HEADER ===
        function handleResize() {
            const header = document.querySelector('.main-header');
            const rightSection = document.querySelector('.right-section');
            
            if (window.innerWidth <= 768) {
                header.classList.add('mobile');
            } else {
                header.classList.remove('mobile');
            }
        }

        window.addEventListener('resize', handleResize);
        handleResize(); // Ejecutar al cargar
    });
</script>
