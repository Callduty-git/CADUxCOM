<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $producto->Nombre }} - CADUxCOM</title>
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .product-image { transition: transform 0.3s ease; }
        .product-image:hover { transform: scale(1.05); }
        .price-original { text-decoration: line-through; }
        .discount-badge { background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); }
    </style>
</head>
<body class="bg-gray-50">
    <div class="page-container">
        <x-header-pages />
        
        <main class="content min-h-screen py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Breadcrumb -->
                <nav class="mb-6">
                    <ol class="flex items-center space-x-2 text-sm text-gray-600">
                        <li><a href="{{ route('home') }}" class="hover:text-gray-800">Inicio</a></li>
                        <li>/</li>
                        <li><a href="{{ route('empresa.productos.index') }}" class="hover:text-gray-800">Mis Productos</a></li>
                        <li>/</li>
                        <li class="text-gray-900 font-medium">{{ $producto->Nombre }}</li>
                    </ol>
                </nav>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                    <!-- Imagen del producto -->
                    <div class="space-y-4">
                        <div class="aspect-square bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                            @if ($producto->Foto)
                                <img src="{{ asset('storage/' . $producto->Foto) }}" 
                                     alt="{{ $producto->Nombre }}" 
                                     class="product-image w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                    <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Información del producto -->
                    <div class="space-y-6">
                        <!-- Título y empresa -->
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $producto->Nombre }}</h1>
                            <p class="text-lg text-gray-600">por <span class="font-semibold">{{ $producto->empresa->Nombre ?? 'Empresa no disponible' }}</span></p>
                        </div>

                        <!-- Precios -->
                        <div class="space-y-2">
                            @if($producto->PrecioOriginal > $producto->Precio)
                                <div class="flex items-center space-x-3">
                                    <span class="text-3xl font-bold text-gray-900">${{ number_format($producto->Precio, 0, ',', '.') }}</span>
                                    <span class="price-original text-xl text-gray-500">${{ number_format($producto->PrecioOriginal, 0, ',', '.') }}</span>
                                    <span class="discount-badge text-sm px-3 py-1 rounded-full text-white font-medium">
                                        -{{ number_format((($producto->PrecioOriginal - $producto->Precio) / $producto->PrecioOriginal) * 100, 0) }}% OFF
                                    </span>
                                </div>
                            @else
                                <span class="text-3xl font-bold text-gray-900">${{ number_format($producto->Precio, 0, ',', '.') }}</span>
                            @endif
                        </div>

                        <!-- Stock -->
                        <div class="flex items-center space-x-2">
                            @if($producto->Cantidad > 0)
                                <span class="text-green-600 font-medium">✓ Disponible</span>
                                <span class="text-gray-600">({{ $producto->Cantidad }} {{ $producto->Tipo }} en stock)</span>
                            @else
                                <span class="text-red-600 font-medium">✗ Agotado</span>
                            @endif
                        </div>

                        <!-- Agregar al carrito -->
                        @if($producto->Cantidad > 0)
                            <div class="border-t border-gray-200 pt-6">
                                <x-add-to-cart :product="$producto" />
                            </div>
                        @endif

                        <!-- Información adicional -->
                        <div class="space-y-4 border-t border-gray-200 pt-6">
                            <div>
                                <h3 class="font-semibold text-gray-900 mb-2">Descripción</h3>
                                <p class="text-gray-600">{{ $producto->Descripcion ?? 'Sin descripción disponible' }}</p>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <h4 class="font-medium text-gray-900">Marca</h4>
                                    <p class="text-gray-600">{{ $producto->Marca }}</p>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">Código</h4>
                                    <p class="text-gray-600">{{ $producto->Codigo }}</p>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">Categoría</h4>
                                    <p class="text-gray-600">{{ $producto->subcategoria->categoria->Nombre ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">Subcategoría</h4>
                                    <p class="text-gray-600">{{ $producto->subcategoria->Nombre ?? 'N/A' }}</p>
                                </div>
                            </div>

                            @if($producto->Fecha_Caducidad)
                                <div>
                                    <h4 class="font-medium text-gray-900">Fecha de Caducidad</h4>
                                    <p class="text-gray-600">{{ \Carbon\Carbon::parse($producto->Fecha_Caducidad)->format('d/m/Y') }}</p>
                                </div>
                            @endif
                        </div>

                        <!-- Información de la empresa -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="font-semibold text-gray-900 mb-2">Información del Vendedor</h3>
                            <div class="space-y-2">
                                <p><span class="font-medium">Empresa:</span> {{ $producto->empresa->Nombre ?? 'N/A' }}</p>
                                <p><span class="font-medium">Contacto:</span> {{ $producto->empresa->Contacto ?? 'N/A' }}</p>
                                <p><span class="font-medium">Ubicación:</span> {{ $producto->empresa->Ubicacion ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="{{ route('empresa.productos.index') }}" 
                       class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200">
                        ← Volver a Mis Productos
                    </a>
                    
                    @auth
                        @if(Auth::guard('empresa')->check() && Auth::guard('empresa')->user()->Id_Empresa == $producto->Id_Empresa)
                            <a href="{{ route('productos.edit', $producto->Id_Producto) }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200">
                                ✏️ Editar Producto
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </main>

        <x-footer />
    </div>
</body>
</html>