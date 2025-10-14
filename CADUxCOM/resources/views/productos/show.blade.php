<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $producto->Nombre }} - CADUxCOM</title>
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user-details.css') }}">
    <link rel="stylesheet" href="{{ asset('css/comentarios.css') }}">
    <link rel="stylesheet" href="{{ asset('css/wishlist-button.css') }}">
    <link rel="stylesheet" href="{{ asset('css/cart-animations.css') }}">
    <link rel="stylesheet" href="{{ asset('css/product-detail-enhanced.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/producto-publico-show.css') }}">
    
    <style>
    /* Estilos específicos para el botón de agregar al carrito */
    .add-to-cart-section {
        margin: 1.5rem 0;
    }
    
    .quantity-selector {
        display: flex;
        align-items: center;
        border: 2px solid #90D575;
        border-radius: 12px;
        background: white;
        box-shadow: 0 4px 15px rgba(144, 213, 117, 0.3);
        overflow: hidden;
    }
    
    .qty-btn {
        width: 48px;
        height: 48px;
        background: #90D575;
        color: white;
        border: none;
        font-size: 20px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .qty-btn:hover {
        background: #49874E;
        transform: scale(1.05);
    }
    
    .qty-input {
        width: 80px;
        height: 48px;
        border: none;
        text-align: center;
        font-size: 18px;
        font-weight: bold;
        color: #49874E;
        background: #f0f9f0;
        outline: none;
    }
    
    .add-cart-btn {
        background: linear-gradient(135deg, #90D575, #49874E);
        color: white;
        border: none;
        padding: 16px 32px;
        border-radius: 12px;
        font-size: 18px;
        font-weight: bold;
        cursor: pointer;
        box-shadow: 0 8px 25px rgba(144, 213, 117, 0.4);
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 250px;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }
    
    .add-cart-btn:hover {
        background: linear-gradient(135deg, #49874E, #90D575);
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(144, 213, 117, 0.6);
    }
    
    .add-cart-btn:active {
        transform: translateY(-1px);
    }
    
    .cart-icon {
        font-size: 24px;
    }
    
    .cart-text {
        font-size: 18px;
        font-weight: bold;
    }
    
    /* Animación de éxito */
    .add-cart-btn.success {
        background: linear-gradient(135deg, #27ae60, #2ecc71) !important;
        transform: scale(1.05) !important;
    }
    
    .add-cart-btn.success::after {
        content: '✓';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-weight: bold;
        font-size: 24px;
        animation: checkmark 0.6s ease-in-out;
    }
    
    @keyframes checkmark {
        0% {
            opacity: 0;
            transform: translate(-50%, -50%) scale(0);
        }
        50% {
            opacity: 1;
            transform: translate(-50%, -50%) scale(1.2);
        }
        100% {
            opacity: 1;
            transform: translate(-50%, -50%) scale(1);
        }
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .add-to-cart-section form {
            flex-direction: column;
            gap: 16px;
        }
        
        .add-cart-btn {
            min-width: 100%;
        }
        
        .quantity-selector {
            align-self: center;
        }
    }
    </style>
</head>
<body class="product-detail-container">
    <div class="page-container">
        <x-header-pages />
        
        <main class="content min-h-screen py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Breadcrumb -->
                <nav class="mb-6">
                    <ol class="flex items-center space-x-2 text-sm text-gray-600">
                        <li><a href="{{ route('home') }}" class="hover:text-gray-800">Inicio</a></li>
                        <li>/</li>
                        <li><a href="{{ route('productos.public.index') }}" class="hover:text-gray-800">Productos</a></li>
                        <li>/</li>
                        <li class="text-gray-900 font-medium">{{ $producto->Nombre }}</li>
                    </ol>
                </nav>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                    <!-- Imagen del producto -->
                    <div class="space-y-4">
                        <div class="product-detail-card">
                            <div class="product-image-container aspect-square">
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
                                
                                <!-- Badge de descuento -->
                                @if($producto->PrecioOriginal > $producto->Precio)
                                    <div class="absolute top-4 left-4">
                                        <span class="discount-badge">
                                            -{{ number_format((($producto->PrecioOriginal - $producto->Precio) / $producto->PrecioOriginal) * 100, 0) }}% OFF
                                        </span>
                                    </div>
                                @endif
                                
                                <!-- Botón de wishlist -->
                                <x-wishlist-button :product-id="$producto->Id_Producto" />
                            </div>
                        </div>
                    </div>

                    <!-- Información del producto -->
                    <div class="product-info-section">
                        <!-- Título y empresa -->
                        <div class="mb-6">
                            <h1 class="product-title">{{ $producto->Nombre }}</h1>
                            <p class="product-company">por <span class="font-semibold">{{ $producto->empresa->Nombre ?? 'Empresa no disponible' }}</span></p>
                        </div>

                        <!-- Precios -->
                        <div class="price-section">
                            @if($producto->PrecioOriginal > $producto->Precio)
                                <div class="flex items-center flex-wrap gap-3">
                                    <span class="price-current">${{ number_format($producto->Precio, 0, ',', '.') }}</span>
                                    <span class="price-original">${{ number_format($producto->PrecioOriginal, 0, ',', '.') }}</span>
                                    <span class="price-savings">
                                        Ahorras ${{ number_format($producto->PrecioOriginal - $producto->Precio, 0, ',', '.') }}
                                    </span>
                                </div>
                            @else
                                <span class="price-current">${{ number_format($producto->Precio, 0, ',', '.') }}</span>
                            @endif
                        </div>

                        <!-- Stock -->
                        <div class="stock-section">
                            @if($producto->Cantidad > 0)
                                <div class="stock-available">✓ Disponible</div>
                                <div class="stock-count">({{ $producto->Cantidad }} {{ $producto->Tipo }} en stock)</div>
                            @else
                                <div class="stock-out">✗ Agotado</div>
                            @endif
                        </div>

                        <!-- Botones de acción -->
                        @if($producto->Cantidad > 0)
                            <div class="action-buttons">
                                <!-- Botón de agregar al carrito mejorado -->
                                <div class="add-to-cart-section">
                                    <form id="add-to-cart-form-{{ $producto->Id_Producto }}" method="POST" action="{{ route('cart.add') }}" class="flex items-center gap-4">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $producto->Id_Producto }}">
                                        
                                        <!-- Selector de cantidad -->
                                        <div class="quantity-selector">
                                            <button type="button" onclick="decreaseQty({{ $producto->Id_Producto }})" class="qty-btn qty-minus">-</button>
                                            <input type="number" name="quantity" min="1" max="{{ $producto->Cantidad }}" value="1" id="qty-{{ $producto->Id_Producto }}" class="qty-input">
                                            <button type="button" onclick="increaseQty({{ $producto->Id_Producto }})" class="qty-btn qty-plus">+</button>
                                        </div>

                                        <!-- Botón principal -->
                                        <button type="submit" class="add-cart-btn" id="cart-btn-{{ $producto->Id_Producto }}">
                                            <span class="cart-icon">🛒</span>
                                            <span class="cart-text">Agregar al Carrito</span>
                                        </button>
                                    </form>
                                </div>
                                
                                <a href="{{ route('productos.public.index') }}" class="btn-outline-enhanced">
                                    <span>🛍️</span>
                                    Ver más productos
                                </a>
                            </div>
                        @else
                            <div class="action-buttons">
                                <button disabled class="btn-secondary-enhanced opacity-50 cursor-not-allowed">
                                    <span>📦</span>
                                    Producto agotado
                                </button>
                                <a href="{{ route('productos.public.index') }}" class="btn-outline-enhanced">
                                    <span>🛍️</span>
                                    Ver más productos
                                </a>
                            </div>
                        @endif

                        <!-- Información adicional -->
                        <div class="additional-info">
                            <h3 class="info-section-title">📋 Información del Producto</h3>
                            
                            <div class="info-grid">
                                <div class="info-item">
                                    <div class="info-label">Descripción</div>
                                    <div class="info-value">{{ $producto->Descripcion ?? 'Sin descripción disponible' }}</div>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-label">Marca</div>
                                    <div class="info-value">{{ $producto->Marca }}</div>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-label">Código</div>
                                    <div class="info-value">{{ $producto->Codigo }}</div>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-label">Categoría</div>
                                    <div class="info-value">{{ $producto->subcategoria->categoria->Nombre ?? 'N/A' }}</div>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-label">Subcategoría</div>
                                    <div class="info-value">{{ $producto->subcategoria->Nombre ?? 'N/A' }}</div>
                                </div>
                                
                                @if($producto->Fecha_Caducidad)
                                    <div class="info-item">
                                        <div class="info-label">Fecha de Caducidad</div>
                                        <div class="info-value">{{ \Carbon\Carbon::parse($producto->Fecha_Caducidad)->format('d/m/Y') }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Información de la empresa -->
                        <div class="company-section">
                            <h3 class="company-title">
                                <span>🏢</span>
                                Información del Vendedor
                            </h3>
                            <div class="company-info">
                                <div class="company-item">
                                    <div class="info-label">Empresa</div>
                                    <div class="info-value">{{ $producto->empresa->Nombre ?? 'N/A' }}</div>
                                </div>
                                <div class="company-item">
                                    <div class="info-label">Contacto</div>
                                    <div class="info-value">{{ $producto->empresa->Contacto ?? 'N/A' }}</div>
                                </div>
                                <div class="company-item">
                                    <div class="info-label">Ubicación</div>
                                    <div class="info-value">{{ $producto->empresa->Ubicacion ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="{{ route('productos.public.index') }}" 
                       class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200">
                        ← Volver a Productos
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

        {{-- Sistema de Comentarios --}}
        <section class="py-8 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <x-comentarios :producto="$producto" />
            </div>
        </section>

        <x-footer />
    </div>
    
    <!-- JavaScript de animaciones del carrito -->
    <script src="{{ asset('js/cart-animations.js') }}"></script>
    
    <script>
    // Funciones para manejar la cantidad
    function increaseQty(productId) {
        const input = document.getElementById('qty-' + productId);
        const max = parseInt(input.getAttribute('max'));
        const current = parseInt(input.value);
        if (current < max) {
            input.value = current + 1;
        }
    }
    
    function decreaseQty(productId) {
        const input = document.getElementById('qty-' + productId);
        const current = parseInt(input.value);
        if (current > 1) {
            input.value = current - 1;
        }
    }
    
    // Manejar el envío del formulario
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('add-to-cart-form-{{ $producto->Id_Producto }}');
        if (form) {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const button = document.getElementById('cart-btn-{{ $producto->Id_Producto }}');
                const originalText = button.innerHTML;
                
                // Mostrar estado de carga
                button.innerHTML = '<span class="animate-spin">⏳</span><span>Cargando...</span>';
                button.disabled = true;
                
                try {
                    const formData = new FormData(form);
                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });
                    
                    if (response.ok) {
                        // Éxito - mostrar animación
                        button.classList.add('success');
                        button.innerHTML = '<span>✓</span><span>¡Agregado!</span>';
                        
                        // Mostrar notificación
                        if (window.showCartNotification) {
                            window.showCartNotification('Producto agregado al carrito', 3000);
                        }
                        
                        // Actualizar contador del carrito si existe
                        if (window.updateCartCount) {
                            window.updateCartCount();
                        }
                        
                        // Restaurar botón después de 2 segundos
                        setTimeout(() => {
                            button.classList.remove('success');
                            button.innerHTML = originalText;
                            button.disabled = false;
                        }, 2000);
                        
                    } else {
                        throw new Error('Error al agregar al carrito');
                    }
                    
                } catch (error) {
                    console.error('Error:', error);
                    button.innerHTML = '<span>❌</span><span>Error</span>';
                    
                    // Restaurar botón después de 2 segundos
                    setTimeout(() => {
                        button.innerHTML = originalText;
                        button.disabled = false;
                    }, 2000);
                }
            });
        }
    });
    </script>
</body>
</html>