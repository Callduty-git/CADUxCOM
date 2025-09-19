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
</head>
<body>
    <div class="page-container">
        <x-header-pages />
        
        <main class="content pt-8 pb-0" style="margin-bottom: 0 !important; padding-bottom: 0 !important;">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-0">
                <!-- Header Mejorado -->
                <div class="text-center mb-12">
                    <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                        🛒 Todos los Productos
                    </h1>
                    <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                        Descubre nuestra amplia gama de productos frescos y de calidad
                    </p>
                </div>

                <!-- Estadísticas Rápidas -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-16">
                    <div class="stats-card p-6 text-center">
                        <div class="text-3xl font-bold text-blue-600 mb-2">{{ $productos->count() }}</div>
                        <div class="text-gray-600">Productos Disponibles</div>
                    </div>
                    <div class="stats-card p-6 text-center">
                        <div class="text-3xl font-bold text-green-600 mb-2">{{ $categorias->count() }}</div>
                        <div class="text-gray-600">Categorías</div>
                    </div>
                    <div class="stats-card p-6 text-center">
                        <div class="text-3xl font-bold text-purple-600 mb-2">{{ $subcategorias->count() }}</div>
                        <div class="text-gray-600">Subcategorías</div>
                    </div>
                </div>

                <!-- Filtros Mejorados -->
                <div class="filter-section text-white rounded-2xl p-8 mb-12">
                    <div class="text-center mb-6">
                        <h2 class="text-2xl font-bold mb-2">🔍 Buscar Productos</h2>
                        <p class="text-green-100">Encuentra exactamente lo que necesitas</p>
                    </div>
                    
                    <form method="GET" action="{{ route('productos.public.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <!-- Búsqueda -->
                        <div>
                            <label class="block text-sm font-semibold mb-3">🔍 Buscar</label>
                            <input type="text" name="query" value="{{ request('query') }}" 
                                   placeholder="Nombre o marca..." 
                                   class="search-input w-full px-4 py-3 rounded-xl text-gray-900 placeholder-gray-500">
                        </div>

                        <!-- Categoría -->
                        <div>
                            <label class="block text-sm font-semibold mb-3">📂 Categoría</label>
                            <select name="categoria" class="select-input w-full px-4 py-3 rounded-xl text-gray-900">
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
                            <label class="block text-sm font-semibold mb-3">🏷️ Subcategoría</label>
                            <select name="subcategoria" class="select-input w-full px-4 py-3 rounded-xl text-gray-900">
                                <option value="">Todas las subcategorías</option>
                                @foreach($subcategorias as $subcategoria)
                                    <option value="{{ $subcategoria->Id_Subcategoria }}" 
                                            {{ request('subcategoria') == $subcategoria->Id_Subcategoria ? 'selected' : '' }}>
                                        {{ $subcategoria->Nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Botones -->
                        <div class="flex flex-col space-y-3">
                            <button type="submit" class="btn-primary px-6 py-3 rounded-xl font-semibold">
                                🔍 Filtrar
                            </button>
                            <a href="{{ route('productos.public.index') }}" class="btn-secondary px-6 py-3 rounded-xl font-semibold text-center">
                                🗑️ Limpiar
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Resultados Mejorados -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10">
                    <div class="mb-4 md:mb-0">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">
                            📦 Resultados de Búsqueda
                        </h3>
                        <p class="text-gray-600">
                            Mostrando <span class="font-semibold text-green-600">{{ $productos->count() }}</span> productos
                            @if(request('query'))
                                para "<span class="font-semibold text-purple-600">"{{ request('query') }}"</span>"
                            @endif
                        </p>
                    </div>
                    
                    <!-- Filtros Activos -->
                    @if(request('categoria') || request('subcategoria'))
                        <div class="flex flex-wrap gap-2">
                            @if(request('categoria'))
                                @php $categoria = $categorias->firstWhere('Id_Categoria', request('categoria')) @endphp
                                @if($categoria)
                                    <span class="category-chip px-3 py-1 rounded-full text-sm font-medium">
                                        📂 {{ $categoria->Nombre }}
                                    </span>
                                @endif
                            @endif
                            @if(request('subcategoria'))
                                @php $subcategoria = $subcategorias->firstWhere('Id_Subcategoria', request('subcategoria')) @endphp
                                @if($subcategoria)
                                    <span class="category-chip px-3 py-1 rounded-full text-sm font-medium">
                                        🏷️ {{ $subcategoria->Nombre }}
                                    </span>
                                @endif
                            @endif
                        </div>
                    @endif
                </div>

                @if($productos->count() > 0)
                    <!-- Grid de productos mejorado -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 justify-items-center" style="margin-bottom: 0 !important; padding-bottom: 0 !important;">
                        @foreach($productos as $producto)
                            <div class="product-card">
                                <!-- Imagen del producto -->
                                <div class="aspect-square bg-gradient-to-br from-gray-50 to-gray-100 relative overflow-hidden">
                                    @if($producto->Foto)
                                        <img src="{{ asset('storage/' . $producto->Foto) }}" 
                                             alt="{{ $producto->Nombre }}" 
                                             class="product-image w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                                            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif

                                    <!-- Badge de descuento -->
                                    @if($producto->Precio_Descuento && $producto->Precio_Descuento < $producto->Precio)
                                        @php
                                            $descuento = round((($producto->Precio - $producto->Precio_Descuento) / $producto->Precio) * 100);
                                        @endphp
                                        <div class="absolute top-3 left-3">
                                            <span class="discount-badge px-3 py-1 rounded-lg text-sm">
                                                -{{ $descuento }}%
                                            </span>
                                        </div>
                                    @endif

                                    <!-- Badge de stock bajo -->
                                    @if($producto->Cantidad <= 5 && $producto->Cantidad > 0)
                                        <div class="absolute top-3 right-3">
                                            <span class="stock-warning px-2 py-1 rounded-lg text-xs">
                                                ¡ Solo {{ $producto->Cantidad }}
                                            </span>
                                        </div>
                                    @elseif($producto->Cantidad == 0)
                                        <div class="absolute top-3 right-3">
                                            <span class="stock-out px-2 py-1 rounded-lg text-xs">
                                                Agotado
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Información del producto -->
                                <div class="px-5 pt-5 flex-1 flex flex-col">
                                    <div class="flex-1">
                                        <h3 class="font-bold text-gray-900 text-lg mb-2 line-clamp-2">
                                            {{ $producto->Nombre }}
                                        </h3>
                                        
                                        <!-- Empresa -->
                                        <div class="flex items-center mb-3">
                                            <span class="text-sm text-gray-600">🏢 {{ $producto->empresa->Nombre_Empresa ?? 'Sin empresa' }}</span>
                                        </div>

                                        <!-- Precios -->
                                        <div class="mb-4">
                                            @if($producto->Precio_Descuento && $producto->Precio_Descuento < $producto->Precio)
                                                <div class="flex items-center space-x-2 mb-1">
                                                    <span class="price-highlight px-3 py-1 rounded-lg text-lg font-bold">
                                                        ${{ number_format($producto->Precio_Descuento, 0, ',', '.') }}
                                                    </span>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <span class="text-gray-500 line-through text-sm">
                                                        ${{ number_format($producto->Precio, 0, ',', '.') }}
                                                    </span>
                                                    <span class="text-gray-600 text-sm">
                                                        Ahorras ${{ number_format($producto->Precio - $producto->Precio_Descuento, 0, ',', '.') }}
                                                    </span>
                                                </div>
                                            @else
                                                <div class="flex items-center">
                                                    <span class="price-highlight px-3 py-1 rounded-lg text-lg font-bold">
                                                        ${{ number_format($producto->Precio, 0, ',', '.') }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Stock -->
                                        <div class="flex items-center mb-4">
                                            @if($producto->Cantidad > 0)
                                                <div class="flex items-center space-x-2">
                                                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                                    <span class="text-sm text-gray-600">
                                                        Disponible ({{ $producto->Cantidad }} {{ $producto->Unidad_Medida ?? 'unidades' }})
                                                    </span>
                                                </div>
                                            @else
                                                <div class="flex items-center space-x-2">
                                                    <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                                                    <span class="text-sm text-red-600 font-medium">Agotado</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Botones de acción mejorados -->
                                    <div class="product-actions space-y-3 px-5 pb-5">
                                        <a href="{{ route('productos.user.show', $producto->Id_Producto) }}" 
                                           class="block w-full bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white text-center py-1.5 rounded-lg font-medium transition-all duration-300 transform hover:scale-105 text-xs">
                                            👁️ Ver Detalles
                                        </a>
                                        
                                        @if($producto->Cantidad > 0)
                                            <!-- Selector de cantidad y botón agregar -->
                                            <div class="flex items-center space-x-3">
                                                <!-- Selector de cantidad -->
                                                <div class="quantity-selector flex items-center border-2 border-gray-200 rounded-lg bg-white">
                                                    <button type="button" onclick="decreaseQty({{ $producto->Id_Producto }})" 
                                                            class="w-7 h-7 flex items-center justify-center hover:bg-gray-100 rounded-l-lg font-bold text-gray-600 text-xs">
                                                        -
                                                    </button>
                                                    <input type="number" min="1" max="{{ $producto->Cantidad }}" 
                                                           value="1" id="qty-{{ $producto->Id_Producto }}"
                                                           class="w-10 text-center border-0 focus:ring-0 font-semibold text-gray-900 text-xs">
                                                    <button type="button" onclick="increaseQty({{ $producto->Id_Producto }})" 
                                                            class="w-7 h-7 flex items-center justify-center hover:bg-gray-100 rounded-r-lg font-bold text-gray-600 text-xs">
                                                        +
                                                    </button>
                                                </div>

                                                <!-- Botón agregar al carrito -->
                                                <button type="button" onclick="addToCart({{ $producto->Id_Producto }})" 
                                                        class="add-to-cart-btn flex-1 bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white px-2 py-1.5 rounded-lg font-medium transition-all duration-300 transform hover:scale-105 space-x-1 text-xs">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                                                    </svg>
                                                    <span>Agregar</span>
                                                </button>
                                            </div>
                                            
                                            <!-- Mensaje de stock bajo -->
                                            @if($producto->Cantidad <= 5)
                                                <p class="text-orange-600 text-sm text-center font-medium">
                                                    ⚠️ Solo quedan {{ $producto->Cantidad }} unidades
                                                </p>
                                            @endif
                                        @else
                                            <button disabled 
                                                    class="w-full bg-gray-400 text-white py-2 rounded-lg font-medium cursor-not-allowed text-xs">
                                                Producto Agotado
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Paginación mejorada -->
                    @if($productos->hasPages())
                        <div class="mt-0 flex justify-center">
                            <div class="bg-white rounded-xl shadow-lg p-4">
                                {{ $productos->links() }}
                            </div>
                        </div>
                    @endif
                @else
                    <!-- Sin resultados mejorado -->
                    <div class="text-center py-16">
                        <div class="mx-auto w-32 h-32 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mb-6">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">🔍 No se encontraron productos</h2>
                        <p class="text-gray-600 mb-8 text-lg">Intenta ajustar los filtros de búsqueda o explorar nuestras categorías</p>
                        <div class="space-x-4">
                            <a href="{{ route('productos.public.index') }}" class="btn-primary px-8 py-4 rounded-xl font-semibold text-lg">
                                🛒 Ver Todos los Productos
                            </a>
                            <a href="{{ route('home') }}" class="btn-secondary px-8 py-4 rounded-xl font-semibold text-lg">
                                🏠 Ir al Inicio
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </main>

        <!-- Botón flotante para ir arriba -->
        <div class="floating-action">
            <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" 
                    class="bg-gradient-to-r from-purple-600 to-purple-700 text-white p-4 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-110">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                </svg>
            </button>
        </div>

        <x-footer />
    </div>

    <script>
        // Funciones para manejar cantidad
        function increaseQty(productId) {
            const input = document.getElementById(`qty-${productId}`);
            const max = parseInt(input.getAttribute('max'));
            const current = parseInt(input.value);
            if (current < max) {
                input.value = current + 1;
            }
        }

        function decreaseQty(productId) {
            const input = document.getElementById(`qty-${productId}`);
            const current = parseInt(input.value);
            if (current > 1) {
                input.value = current - 1;
            }
        }

        // Función para agregar al carrito
        async function addToCart(productId) {
            const quantity = document.getElementById(`qty-${productId}`).value;
            const button = event.target.closest('button');
            const originalText = button.innerHTML;
            
            // Mostrar loading
            button.innerHTML = '<svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Agregando...';
            button.disabled = true;

            try {
                const response = await fetch('{{ route("cart.add") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: `product_id=${productId}&quantity=${quantity}`
                });

                const data = await response.json();

                if (data.success) {
                    // Mostrar notificación de éxito
                    showNotification('Producto agregado al carrito', 'success');
                    
                    // Actualizar contador del carrito
                    updateCartCounter();
                } else {
                    showNotification(data.message || 'Error al agregar el producto', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Error al agregar el producto', 'error');
            } finally {
                // Restaurar botón
                button.innerHTML = originalText;
                button.disabled = false;
            }
        }

        // Función para mostrar notificaciones
        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg text-white font-medium transform transition-all duration-300 ${
                type === 'success' ? 'bg-green-500' : 'bg-red-500'
            }`;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            // Animar entrada
            setTimeout(() => {
                notification.style.transform = 'translateX(0)';
            }, 100);
            
            // Remover después de 3 segundos
            setTimeout(() => {
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }

        // Función para actualizar contador del carrito
        async function updateCartCounter() {
            try {
                const response = await fetch('{{ route("cart.count") }}');
                const data = await response.json();
                
                const counter = document.querySelector('.cart-count');
                if (counter) {
                    counter.textContent = data.count;
                }
            } catch (error) {
                console.error('Error updating cart counter:', error);
            }
        }

        // Animación de entrada para las tarjetas
        function animateCards() {
            const cards = document.querySelectorAll('.product-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                
                setTimeout(() => {
                    card.style.transition = 'all 0.6s ease-out';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        }

        // Ejecutar animaciones cuando se carga la página
        document.addEventListener('DOMContentLoaded', function() {
            animateCards();
        });
    </script>
</body>
</html>










