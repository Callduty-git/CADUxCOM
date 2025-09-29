<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todos los Productos - CADUxCOM</title>
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/empresa-dashboard.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .product-card { transition: all 0.3s ease; }
        .product-card:hover { transform: translateY(-4px); box-shadow: 0 8px 25px rgba(0,0,0,0.1); }
        .discount-badge { background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); }
        .filter-section { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        
        /* ====== SIDEBAR CONTAINER ====== */
        .sidebar-container {
            position: fixed !important;
            left: 20px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            width: 80px !important;
            height: auto !important;
            z-index: 9999 !important;
            transition: all 0.3s ease !important;
            margin: 0 !important;
            padding: 0 !important;
            right: auto !important;
            bottom: auto !important;
        }
        
        .sidebar {
            width: 80px;
            background-color: transparent; /* sin cuadro blanco */
            padding: 0; /* que el alto dependa de los botones */
            display: flex;
            flex-direction: column;
            align-items: center;
            border-radius: 0;
            border: none;
            position: relative;
            z-index: 1001;
            box-shadow: none;
            transition: all 0.3s ease;
            opacity: 0.95;
            overflow: visible; /* que crezca con el contenido */
            max-height: none; /* sin tope de altura */
        }
        
        .sidebar:hover {
            width: 280px !important;
            opacity: 1;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }
        
        .sidebar:hover .nav-buttons .btn span {
            opacity: 1 !important;
        }
        
        .sidebar-container:hover {
            width: 280px !important;
        }
        
        .nav-buttons {
            width: 100%;
            padding: 20px 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 35px;
            align-items: center;
        }
        
        .nav-buttons .btn {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 15px;
            background-color: #d88ef0;
            color: white;
            padding: 15px 20px;
            text-align: left;
            border-radius: 15px;
            font-weight: 600;
            text-decoration: none;
            border: 1px solid rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            font-size: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
            width: 60px;
            min-width: 60px;
            white-space: nowrap;
        }
        
        .sidebar:hover .nav-buttons .btn {
            width: 240px !important;
            justify-content: flex-start !important;
        }
        
        .nav-buttons .btn i {
            font-size: 20px !important;
            opacity: 0.9 !important;
            min-width: 20px !important;
            text-align: center !important;
        }
        
        .nav-buttons .btn span {
            opacity: 0 !important;
            transition: opacity 0.3s ease !important;
            font-size: 14px !important;
        }
        
        .nav-buttons .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }
        
        .nav-buttons .btn:hover::before {
            left: 100%;
        }
        
        .nav-buttons .btn:hover {
            background-color: #b963d1;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(185, 99, 209, 0.4);
            border-color: rgba(0, 0, 0, 0.3);
        }
        
        .nav-buttons .btn:active {
            transform: translateY(0);
            box-shadow: 0 4px 12px rgba(185, 99, 209, 0.3);
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="page-container">
        <x-header-pages />
        
        <div class="sidebar-container">
            <aside class="sidebar" id="sidebar">
                <nav class="nav-buttons">
                    <a href="{{ route('empresa.dashboard') }}" class="btn">
                        <i class="fas fa-home"></i>
                        <span>Inicio</span>
                    </a>
                    <a href="{{ route('empresa.productos.index') }}" class="btn">
                        <i class="fas fa-box"></i>
                        <span>Productos</span>
                    </a>
                    <a href="{{ route('empresa.facturas') }}" class="btn">
                        <i class="fas fa-list-alt"></i>
                        <span>Log de Productos</span>
                    </a>
                    <form method="POST" action="{{ route('empresa.logout') }}" style="margin: 0;">
                        @csrf
                        <button type="submit" class="btn" aria-label="Cerrar sesión">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Salir</span>
                        </button>
                    </form>
                </nav>
            </aside>
        </div>
        
        <main class="content min-h-screen py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900">Todos los Productos</h1>
                    <p class="text-gray-600 mt-2">Descubre nuestra amplia gama de productos</p>
                </div>

                <!-- Filtros -->
                <div class="filter-section text-white rounded-lg p-6 mb-8">
                    <form method="GET" action="{{ route('productos.public.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Búsqueda -->
                        <div>
                            <label class="block text-sm font-medium mb-2">Buscar</label>
                            <input type="text" name="query" value="{{ request('query') }}" 
                                   placeholder="Nombre o marca..." 
                                   class="w-full px-3 py-2 rounded-lg text-gray-900">
                        </div>

                        <!-- Categoría -->
                        <div>
                            <label class="block text-sm font-medium mb-2">Categoría</label>
                            <select name="categoria" class="w-full px-3 py-2 rounded-lg text-gray-900">
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
                            <label class="block text-sm font-medium mb-2">Subcategoría</label>
                            <select name="subcategoria" class="w-full px-3 py-2 rounded-lg text-gray-900">
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
                        <div class="flex items-end space-x-2">
                            <button type="submit" class="bg-white text-purple-600 px-6 py-2 rounded-lg font-medium hover:bg-gray-100 transition-colors">
                                🔍 Filtrar
                            </button>
                            <a href="{{ route('productos.public.index') }}" class="bg-gray-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-gray-700 transition-colors">
                                Limpiar
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Resultados -->
                <div class="mb-4">
                    <p class="text-gray-600">
                        Mostrando {{ $productos->count() }} productos
                        @if(request('query'))
                            para "{{ request('query') }}"
                        @endif
                    </p>
                </div>

                @if($productos->count() > 0)
                    <!-- Grid de productos -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        @foreach($productos as $producto)
                            <div class="product-card bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                                <!-- Imagen del producto -->
                                <div class="aspect-square bg-gray-100 relative overflow-hidden">
                                    @if($producto->Foto)
                                        <img src="{{ asset('storage/' . $producto->Foto) }}" 
                                             alt="{{ $producto->Nombre }}" 
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif

                                    <!-- Badge de descuento -->
                                    @if($producto->PrecioOriginal > $producto->Precio)
                                        <div class="absolute top-2 left-2">
                                            <span class="discount-badge text-xs px-2 py-1 rounded-full text-white font-medium">
                                                -{{ number_format((($producto->PrecioOriginal - $producto->Precio) / $producto->PrecioOriginal) * 100, 0) }}%
                                            </span>
                                        </div>
                                    @endif

                                    <!-- Badge de stock -->
                                    @if($producto->Cantidad <= 5 && $producto->Cantidad > 0)
                                        <div class="absolute top-2 right-2">
                                            <span class="bg-orange-500 text-white text-xs px-2 py-1 rounded-full font-medium">
                                                Solo {{ $producto->Cantidad }}
                                            </span>
                                        </div>
                                    @elseif($producto->Cantidad == 0)
                                        <div class="absolute top-2 right-2">
                                            <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full font-medium">
                                                Agotado
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Información del producto -->
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-900 text-lg mb-1 line-clamp-2">
                                        {{ $producto->Nombre }}
                                    </h3>
                                    
                                    <p class="text-sm text-gray-600 mb-2">
                                        {{ $producto->empresa->Nombre ?? 'Empresa no disponible' }}
                                    </p>

                                    <!-- Precios -->
                                    <div class="mb-3">
                                        @if($producto->PrecioOriginal > $producto->Precio)
                                            <div class="flex items-center space-x-2">
                                                <span class="text-lg font-bold text-gray-900">
                                                    ${{ number_format($producto->Precio, 0, ',', '.') }}
                                                </span>
                                                <span class="text-sm text-gray-500 line-through">
                                                    ${{ number_format($producto->PrecioOriginal, 0, ',', '.') }}
                                                </span>
                                            </div>
                                        @else
                                            <span class="text-lg font-bold text-gray-900">
                                                ${{ number_format($producto->Precio, 0, ',', '.') }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Stock -->
                                    <div class="text-sm text-gray-600 mb-4">
                                        @if($producto->Cantidad > 0)
                                            <span class="text-green-600">✓ Disponible</span>
                                            <span class="text-gray-500">({{ $producto->Cantidad }} {{ $producto->Tipo }})</span>
                                        @else
                                            <span class="text-red-600">✗ Agotado</span>
                                        @endif
                                    </div>

                                    <!-- Botones de acción -->
                                    <div class="space-y-2">
                                        <a href="{{ route('productos.show', $producto->Id_Producto) }}" 
                                           class="block w-full bg-gray-600 hover:bg-gray-700 text-white text-center py-2 rounded-lg font-medium transition-colors">
                                            Ver Detalles
                                        </a>
                                        
                                        @if($producto->Cantidad > 0)
                                            <x-add-to-cart :product="$producto" />
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Paginación -->
                    @if($productos->hasPages())
                        <div class="mt-8">
                            {{ $productos->links() }}
                        </div>
                    @endif
                @else
                    <!-- Sin resultados -->
                    <div class="text-center py-12">
                        <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-2">No se encontraron productos</h2>
                        <p class="text-gray-600 mb-6">Intenta ajustar los filtros de búsqueda</p>
                        <a href="{{ route('productos.public.index') }}" class="bg-purple-600 text-white px-6 py-3 rounded-lg font-semibold hover:opacity-90 transition-opacity">
                            Ver Todos los Productos
                        </a>
                    </div>
                @endif
            </div>
        </main>

        <x-footer />
    </div>

    <script>
        // Actualizar contador del carrito
        function updateCartCount() {
            fetch('{{ route("cart.count") }}')
                .then(response => response.json())
                .then(data => {
                    const cartCount = document.querySelector('.cart-count');
                    if (cartCount) {
                        cartCount.textContent = data.count;
                        cartCount.style.display = data.count > 0 ? 'block' : 'none';
                    }
                });
        }

        // Actualizar contador al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            updateCartCount();
        });
    </script>

    <!-- SCRIPT INMEDIATO PARA ALINEAR SIDEBAR A LA IZQUIERDA -->
    <script>
        // Ejecutar inmediatamente, antes de que se cargue cualquier otro script
        (function() {
            function forceLeftAlign() {
                const container = document.querySelector('.sidebar-container');
                if (container) {
                    container.style.cssText = `
                        position: fixed !important;
                        left: 20px !important;
                        top: 50% !important;
                        transform: translateY(-50%) !important;
                        width: 80px !important;
                        height: auto !important;
                        z-index: 9999 !important;
                        margin: 0 !important;
                        padding: 0 !important;
                        right: auto !important;
                        bottom: auto !important;
                    `;
                }
            }
            
            // Ejecutar inmediatamente
            forceLeftAlign();
            
            // Ejecutar cuando el DOM esté listo
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', forceLeftAlign);
            } else {
                forceLeftAlign();
            }
            
            // Ejecutar continuamente
            setInterval(forceLeftAlign, 100);
        })();
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Funcionalidad del sidebar alineado a la izquierda - FORZADO
            function forceLeftAlignSidebar() {
                const sidebarContainer = document.querySelector('.sidebar-container');
                if (sidebarContainer) {
                    sidebarContainer.style.cssText = `
                        position: fixed !important;
                        left: 20px !important;
                        top: 50% !important;
                        transform: translateY(-50%) !important;
                        width: 80px !important;
                        height: auto !important;
                        z-index: 9999 !important;
                        margin: 0 !important;
                        padding: 0 !important;
                        right: auto !important;
                        bottom: auto !important;
                    `;
                }
            }
            
            // Aplicar inmediatamente
            forceLeftAlignSidebar();
            
            // Aplicar cuando el DOM esté listo
            document.addEventListener('DOMContentLoaded', forceLeftAlignSidebar);
            
            // Aplicar cuando la ventana se carga
            window.addEventListener('load', forceLeftAlignSidebar);
            
            // Aplicar continuamente
            setInterval(forceLeftAlignSidebar, 50);
            
            // Aplicar en cualquier cambio
            const observer = new MutationObserver(forceLeftAlignSidebar);
            observer.observe(document.body, { childList: true, subtree: true });
        });
    </script>
</body>
</html>
