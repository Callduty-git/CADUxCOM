<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todos los Productos - CADUxCOM</title>
    <link rel="stylesheet" href="{{ asset('css/header.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/productos-public.css') }}?v={{ time() }}">
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
        
        <main>
            <div class="max-w-7xl">
                <!-- Título principal -->
                <div class="page-title">
                    <h1 class="flex items-center justify-center gap-3">
                        🛒 Todos los Productos
                    </h1>
                    <p>
                        Descubre nuestra amplia gama de productos frescos y de calidad
                    </p>
                </div>

                <!-- Estadísticas Rápidas -->
                <div class="stats-section">
                    <div class="stats-grid">
                        <div class="stats-card">
                            <h3>{{ $productos->count() }}</h3>
                            <p>Productos Disponibles</p>
                        </div>
                        <div class="stats-card">
                            <h3>{{ $categorias->count() }}</h3>
                            <p>Categorías</p>
                        </div>
                        <div class="stats-card">
                            <h3>{{ $subcategorias->count() }}</h3>
                            <p>Subcategorías</p>
                        </div>
                    </div>
                </div>

                <!-- Sección de Filtros -->
                <div class="filter-section">
                    <h3>🔍 Buscar Productos</h3>
                    <p>Encuentra exactamente lo que necesitas</p>
                    
                    <form method="GET" action="{{ route('productos.public.index') }}" class="filter-form">
                        <div class="filter-grid">
                            <!-- Búsqueda por nombre -->
                            <div>
                                <label class="block text-sm font-semibold mb-2 text-white">🔍 Buscar</label>
                                <input type="text" name="query" value="{{ request('query') }}" 
                                       placeholder="Nombre o marca..." 
                                       class="search-input w-full">
                            </div>

                            <!-- Categoría -->
                            <div>
                                <label class="block text-sm font-semibold mb-2 text-white">📂 Categoría</label>
                                <select name="categoria" class="select-input w-full">
                                    <option value="">Todas las categorías</option>
                                    @foreach($categorias as $categoria)
                                        <option value="{{ $categoria->Id_Categoria }}" 
                                                {{ request('categoria') == $categoria->Id_Categoria ? 'selected' : '' }}>
                                            {{ $categoria->Nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Subcategoría -->
                            <div>
                                <label class="block text-sm font-semibold mb-2 text-white">📁 Subcategoría</label>
                                <select name="subcategoria" class="select-input w-full">
                                    <option value="">Todas las subcategorías</option>
                                    @foreach($subcategorias as $subcategoria)
                                        <option value="{{ $subcategoria->Id_Subcategoria }}" 
                                                {{ request('subcategoria') == $subcategoria->Id_Subcategoria ? 'selected' : '' }}>
                                            {{ $subcategoria->Nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="filter-buttons">
                            <button type="submit" class="btn-primary">
                                🔍 Filtrar
                            </button>
                            <a href="{{ route('productos.public.index') }}" class="btn-secondary">
                                🗑️ Limpiar
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Sección de Resultados -->
                <div class="results-section">
                    <div class="results-header">
                        <h3>📦 Resultados de Búsqueda</h3>
                        <p>
                            Mostrando <span class="font-semibold text-green-600">{{ $productos->count() }}</span> productos
                            @if(request('query'))
                                para "<span class="font-semibold text-purple-600">"{{ request('query') }}"</span>"
                            @endif
                        </p>
                    </div>
                    
                    @if($productos->count() > 0)
                        <div class="products-grid">
                            @foreach($productos as $producto)
                                <div class="product-card">
                                    <!-- Imagen del producto -->
                                    <div class="product-image-container">
                                        @if($producto->Foto)
                                            <img src="{{ asset('storage/' . $producto->Foto) }}" 
                                                 alt="{{ $producto->Nombre }}"
                                                 class="product-image">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        @endif

                                        <!-- Badge de descuento -->
                                        @if($producto->PrecioOriginal > $producto->Precio)
                                            @php
                                                $descuento = round((($producto->PrecioOriginal - $producto->Precio) / $producto->PrecioOriginal) * 100);
                                            @endphp
                                            <div class="discount-badge">
                                                -{{ $descuento }}%
                                            </div>
                                        @endif

                                        <!-- Badge de stock bajo -->
                                        @if($producto->Cantidad <= 5 && $producto->Cantidad > 0)
                                            <div class="stock-warning">
                                                ¡Solo {{ $producto->Cantidad }}!
                                            </div>
                                        @elseif($producto->Cantidad == 0)
                                            <div class="stock-out">
                                                Agotado
                                            </div>
                                        @endif

                                        <!-- Botón de favoritos -->
                                        <x-wishlist-button :product-id="$producto->Id_Producto" />
                                    </div>

                                    <!-- Contenido de la tarjeta -->
                                    <div class="product-content">
                                        <h3 class="product-title">
                                            {{ $producto->Nombre }}
                                        </h3>
                                        
                                        <!-- Marca -->
                                        <div class="product-brand">
                                            {{ $producto->Marca ?? 'Sin marca' }}
                                        </div>

                                        <!-- Descripción -->
                                        <div class="product-description">
                                            {{ $producto->Descripcion ?? 'Justo y bueno' }} — {{ $producto->subcategoria->Nombre ?? 'Garzón' }}
                                        </div>

                                        <!-- Precios -->
                                        <div class="price-section">
                                            @if($producto->PrecioOriginal > $producto->Precio)
                                                <div class="price-container">
                                                    <span class="price-original">
                                                        ${{ number_format($producto->PrecioOriginal, 0, ',', '.') }}
                                                    </span>
                                                    <span class="price-arrow">→</span>
                                                    <span class="price-current">
                                                        ${{ number_format($producto->Precio, 0, ',', '.') }}
                                                    </span>
                                                </div>
                                            @else
                                                <div class="price-container">
                                                    <span class="price-current">
                                                        ${{ number_format($producto->Precio, 0, ',', '.') }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Fecha de vencimiento -->
                                        @if($producto->Fecha_Caducidad)
                                            <div class="expiration-date">
                                                Vence: {{ date('d/m/Y', strtotime($producto->Fecha_Caducidad)) }}
                                            </div>
                                        @endif

                                        <!-- Stock -->
                                        <div class="stock-info">
                                            @if($producto->Cantidad > 0)
                                                <div class="stock-dot available"></div>
                                                <span class="stock-text">
                                                    Disponible ({{ $producto->Cantidad }} {{ $producto->Tipo ?? 'unidades' }})
                                                </span>
                                            @else
                                                <div class="stock-dot out-of-stock"></div>
                                                <span class="stock-text">Agotado</span>
                                            @endif
                                        </div>

                                        <!-- Acciones del producto -->
                                        <div class="product-actions">
                                            <div class="action-buttons">
                                                <a href="{{ route('productos.user.show', $producto->Id_Producto) }}" 
                                                   class="btn-view-details">
                                                    Ver detalles
                                                </a>

                                                @if($producto->Cantidad > 0)
                                                    <button type="button" class="add-to-cart-btn" 
                                                            onclick="addToCart({{ $producto->Id_Producto }})"
                                                            id="add-cart-btn-{{ $producto->Id_Producto }}">
                                                        <span class="btn-text">Agregar</span>
                                                    </button>
                                                @else
                                                    <button disabled class="add-to-cart-btn">
                                                        Producto Agotado
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Paginación -->
                        <div class="pagination-section">
                            <div class="pagination-container">
                                {{ $productos->links() }}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="text-6xl mb-4">🔍</div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">No se encontraron productos</h3>
                            <p class="text-gray-600 mb-6">Intenta ajustar tus filtros de búsqueda</p>
                            <a href="{{ route('productos.public.index') }}" class="btn-primary">
                                Ver Todos los Productos
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </main>

        <!-- Botón flotante para ir arriba -->
        <div class="floating-action">
            <button class="scroll-to-top" onclick="scrollToTop()">
                ↑
            </button>
        </div>

        <x-footer />
    </div>

    {{-- Scripts centralizados del carrito --}}
    <x-cart-scripts />

    <script>
        // Función para aumentar cantidad
        function increaseQty(productId) {
            const input = document.getElementById(`qty-${productId}`);
            const max = parseInt(input.getAttribute('max'));
            const current = parseInt(input.value);
            if (current < max) {
                input.value = current + 1;
            }
        }

        // Función para disminuir cantidad
        function decreaseQty(productId) {
            const input = document.getElementById(`qty-${productId}`);
            const current = parseInt(input.value);
            if (current > 1) {
                input.value = current - 1;
            }
        }

        // Función para ir arriba
        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        // Mostrar/ocultar botón flotante
        window.addEventListener('scroll', function() {
            const scrollButton = document.querySelector('.scroll-to-top');
            if (window.pageYOffset > 300) {
                scrollButton.style.display = 'flex';
            } else {
                scrollButton.style.display = 'none';
            }
        });

        // Inicializar
        document.addEventListener('DOMContentLoaded', function() {
            // Ocultar botón flotante inicialmente
            document.querySelector('.scroll-to-top').style.display = 'none';
        });
    </script>
</body>
</html>