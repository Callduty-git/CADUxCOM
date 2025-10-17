<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados de Búsqueda - CADUxCOM</title>
    <link rel="stylesheet" href="{{ asset('css/header.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/productos-public.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/all-products.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/search-results.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/wishlist-button.css') }}">
    <link rel="stylesheet" href="{{ asset('css/cart-animations.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    
    <!-- JavaScript del carrito -->
    <script src="{{ asset('js/cart.js') }}"></script>
    
</head>
<body>
    <div class="page-container">
        <x-header-pages />
        
        <main class="search-container">
            <div class="max-w-7xl">
                <!-- Título principal -->
                <div class="page-title">
                    <h1>
                        🔍 Resultados de Búsqueda
                    </h1>
                    @if($searchStats['query'])
                        <p>
                            Resultados para: "<strong class="search-highlight">{{ $searchStats['query'] }}</strong>"
                        </p>
                    @endif
                </div>

                <!-- Estadísticas de búsqueda -->
                <div class="search-stats">
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-icon">📦</div>
                            <div class="stat-content">
                                <span class="stat-number">{{ $searchStats['total'] }}</span>
                                <span class="stat-label">Productos encontrados</span>
                            </div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-icon">🏷️</div>
                            <div class="stat-content">
                                <span class="stat-number">{{ $searchStats['categories'] }}</span>
                                <span class="stat-label">Categorías</span>
                            </div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-icon">🏢</div>
                            <div class="stat-content">
                                <span class="stat-number">{{ $searchStats['companies'] }}</span>
                                <span class="stat-label">Empresas</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filtros -->
                <div class="filters-section">
                    <div class="filters-header">
                        <h2>🔧 Filtros de Búsqueda</h2>
                        <button type="button" class="filters-toggle" onclick="toggleFilters()">
                            <span class="toggle-text">Mostrar filtros</span>
                            <span class="toggle-icon">▼</span>
                        </button>
                    </div>
                    
                    <div class="filters-content" id="filtersContent">
                        <form method="GET" action="{{ route('search') }}" class="filters-form">
                            <input type="hidden" name="q" value="{{ request('q') }}">
                            
                            <div class="filters-grid">
                                <!-- Filtro por Categoría -->
                                <div class="filter-group">
                                    <label for="categoria">
                                        <span class="filter-icon">🏷️</span>
                                        Categoría
                                    </label>
                                    <select name="categoria" id="categoria" class="filter-select">
                                        <option value="">Todas las categorías</option>
                                        @foreach($categorias as $categoria)
                                            <option value="{{ $categoria->Id_Categoria }}" 
                                                    {{ request('categoria') == $categoria->Id_Categoria ? 'selected' : '' }}>
                                                {{ $categoria->Nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Filtro por Subcategoría -->
                                <div class="filter-group">
                                    <label for="subcategoria">
                                        <span class="filter-icon">📂</span>
                                        Subcategoría
                                    </label>
                                    <select name="subcategoria" id="subcategoria" class="filter-select">
                                        <option value="">Todas las subcategorías</option>
                                        @foreach($subcategorias as $subcategoria)
                                            <option value="{{ $subcategoria->Id_Subcategoria }}" 
                                                    {{ request('subcategoria') == $subcategoria->Id_Subcategoria ? 'selected' : '' }}>
                                                {{ $subcategoria->Nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Filtro por Empresa -->
                                <div class="filter-group">
                                    <label for="empresa">
                                        <span class="filter-icon">🏢</span>
                                        Empresa
                                    </label>
                                    <select name="empresa" id="empresa" class="filter-select">
                                        <option value="">Todas las empresas</option>
                                        @foreach($empresas as $empresa)
                                            <option value="{{ $empresa->Id_Empresa }}" 
                                                    {{ request('empresa') == $empresa->Id_Empresa ? 'selected' : '' }}>
                                                {{ $empresa->Nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Filtro por Precio -->
                                <div class="filter-group">
                                    <label for="min_price">
                                        <span class="filter-icon">💰</span>
                                        Precio mínimo
                                    </label>
                                    <input type="number" name="min_price" id="min_price" 
                                           value="{{ request('min_price') }}" 
                                           placeholder="$0.00" step="0.01" min="0" class="filter-input">
                                </div>

                                <div class="filter-group">
                                    <label for="max_price">
                                        <span class="filter-icon">💸</span>
                                        Precio máximo
                                    </label>
                                    <input type="number" name="max_price" id="max_price" 
                                           value="{{ request('max_price') }}" 
                                           placeholder="$999999" step="0.01" min="0" class="filter-input">
                                </div>

                                <!-- Filtro por Ordenamiento -->
                                <div class="filter-group">
                                    <label for="sort_by">
                                        <span class="filter-icon">🔄</span>
                                        Ordenar por
                                    </label>
                                    <select name="sort_by" id="sort_by" class="filter-select">
                                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Más recientes</option>
                                        <option value="price_asc" {{ request('sort_by') == 'price_asc' ? 'selected' : '' }}>Precio: menor a mayor</option>
                                        <option value="price_desc" {{ request('sort_by') == 'price_desc' ? 'selected' : '' }}>Precio: mayor a menor</option>
                                        <option value="name_asc" {{ request('sort_by') == 'name_asc' ? 'selected' : '' }}>Nombre: A-Z</option>
                                        <option value="name_desc" {{ request('sort_by') == 'name_desc' ? 'selected' : '' }}>Nombre: Z-A</option>
                                    </select>
                                </div>
                            </div>

                            <div class="filter-actions">
                                <button type="submit" class="btn-apply-filters">
                                    ✅ Aplicar Filtros
                                </button>
                                <a href="{{ route('search', ['q' => request('q')]) }}" class="btn-clear-filters">
                                    🗑️ Limpiar Filtros
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Resultados de productos -->
                <div class="products-section">
                    @if($productos->count() > 0)
                        <div class="products-header">
                            <h3>📦 Productos encontrados ({{ $productos->total() }})</h3>
                            <div class="view-options">
                                <button type="button" class="view-toggle active" data-view="grid" title="Vista en cuadrícula">
                                    ⊞
                                </button>
                                <button type="button" class="view-toggle" data-view="list" title="Vista en lista">
                                    ☰
                                </button>
                            </div>
                        </div>
                        
                        <div class="products-grid" id="productsContainer">
                            @foreach($productos as $producto)
                                <x-product-card :product="$producto" />
                            @endforeach
                        </div>

                        <!-- Paginación -->
                        <div class="pagination-wrapper">
                            {{ $productos->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="no-results">
                            <div class="no-results-content">
                                <div class="no-results-icon">🔍</div>
                                <h3>No se encontraron productos</h3>
                                <p>Intenta ajustar tus filtros de búsqueda o usar términos diferentes.</p>
                                <div class="no-results-actions">
                                    <a href="{{ route('search') }}" class="btn-new-search">
                                        🔄 Nueva búsqueda
                                    </a>
                                    <a href="{{ route('search', ['q' => request('q')]) }}" class="btn-clear-filters">
                                        🗑️ Limpiar filtros
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </main>

        <x-footer />
    </div>

    <script>
        // Toggle de filtros
        function toggleFilters() {
            const filtersContent = document.getElementById('filtersContent');
            const toggleText = document.querySelector('.toggle-text');
            const toggleIcon = document.querySelector('.toggle-icon');
            
            if (filtersContent.style.display === 'none' || filtersContent.style.display === '') {
                filtersContent.style.display = 'block';
                toggleText.textContent = 'Ocultar filtros';
                toggleIcon.textContent = '▲';
            } else {
                filtersContent.style.display = 'none';
                toggleText.textContent = 'Mostrar filtros';
                toggleIcon.textContent = '▼';
            }
        }

        // Cambio de vista de productos
        document.addEventListener('DOMContentLoaded', function() {
            const viewToggles = document.querySelectorAll('.view-toggle');
            const productsContainer = document.getElementById('productsContainer');

            viewToggles.forEach(toggle => {
                toggle.addEventListener('click', function() {
                    const view = this.getAttribute('data-view');
                    
                    // Remover clase active de todos los botones
                    viewToggles.forEach(btn => btn.classList.remove('active'));
                    
                    // Agregar clase active al botón clickeado
                    this.classList.add('active');
                    
                    // Cambiar vista del contenedor
                    if (view === 'list') {
                        productsContainer.classList.add('products-list');
                        productsContainer.classList.remove('products-grid');
                    } else {
                        productsContainer.classList.add('products-grid');
                        productsContainer.classList.remove('products-list');
                    }
                });
            });

            // Auto-ocultar filtros en móviles
            if (window.innerWidth <= 768) {
                const filtersContent = document.getElementById('filtersContent');
                filtersContent.style.display = 'none';
            }
        });

        // Animación de entrada para productos
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observar productos para animación
        document.addEventListener('DOMContentLoaded', function() {
            const productCards = document.querySelectorAll('.product-card');
            productCards.forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(card);
            });
        });

        // Auto-submit del formulario cuando cambian los filtros
        const filterSelects = document.querySelectorAll('#categoria, #subcategoria, #empresa, #sort_by');
        
        filterSelects.forEach(select => {
            select.addEventListener('change', function() {
                this.form.submit();
            });
        });

        // Filtro de subcategorías dinámico basado en categoría
        const categoriaSelect = document.getElementById('categoria');
        const subcategoriaSelect = document.getElementById('subcategoria');
        
        if (categoriaSelect && subcategoriaSelect) {
            categoriaSelect.addEventListener('change', function() {
                const categoriaId = this.value;
                
                // Resetear subcategorías
                subcategoriaSelect.innerHTML = '<option value="">Todas las subcategorías</option>';
                
                if (categoriaId) {
                    // Aquí podrías hacer una llamada AJAX para cargar subcategorías
                    // Por ahora, simplemente mostramos todas
                    @foreach($subcategorias as $subcategoria)
                        if ({{ $subcategoria->Id_Categoria ?? 'null' }} == categoriaId || !categoriaId) {
                            const option = document.createElement('option');
                            option.value = '{{ $subcategoria->Id_Subcategoria }}';
                            option.textContent = '{{ $subcategoria->Nombre }}';
                            subcategoriaSelect.appendChild(option);
                        }
                    @endforeach
                }
            });
        }
    </script>

    <!-- Incluir scripts de notificaciones si existen -->
    @if(file_exists(public_path('js/notifications.js')))
        <script src="{{ asset('js/notifications.js') }}"></script>
    @endif
</body>
</html>