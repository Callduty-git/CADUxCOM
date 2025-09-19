<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras - CADUxCOM</title>
    
    {{-- Archivos CSS --}}
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/cart.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .cart-item { transition: all 0.3s ease; }
        .cart-item:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,0,0,0.1); }
        .btn-primary { background: linear-gradient(135deg, #49874E 0%, #90D575 100%); }
        .btn-secondary { background: linear-gradient(135deg, #AA5FC7 0%, #8B5CF6 100%); }
        .summary-card { background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); }
        
        /* Estilos para el carrito vacío */
        .empty-cart-container {
            position: relative;
            background: white;
            min-height: 60vh;
        }
        
        .empty-cart-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(73, 135, 78, 0.1);
            box-shadow: 0 20px 60px rgba(73, 135, 78, 0.15);
        }
        
        .cart-icon-container {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border: 2px solid rgba(73, 135, 78, 0.1);
        }
        
        .cart-icon {
            color: #49874E;
            filter: drop-shadow(0 4px 8px rgba(73, 135, 78, 0.2));
        }
        
        .decorative-dots {
            animation: pulse-dots 2s ease-in-out infinite;
        }
        
        .decorative-dots:nth-child(2) {
            animation-delay: 0.2s;
        }
        
        .decorative-dots:nth-child(3) {
            animation-delay: 0.4s;
        }
        
        @keyframes pulse-dots {
            0%, 100% { opacity: 0.4; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.1); }
        }
        
        .continue-btn {
            background: linear-gradient(135deg, #49874E 0%, #90D575 100%);
            box-shadow: 0 8px 25px rgba(73, 135, 78, 0.3);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .continue-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(73, 135, 78, 0.4);
        }
        
        .continue-btn:active {
            transform: translateY(0);
        }
        
        .background-decoration {
            animation: float 6s ease-in-out infinite;
        }
        
        .background-decoration:nth-child(2) {
            animation-delay: 3s;
            animation-direction: reverse;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }
        
        /* Espaciado superior específico para la página del carrito */
        .app-wrapper {
            padding-top: 0; /* No padding-top porque el header es fixed */
        }
        
        .content {
            margin-top: 90px; /* Espacio mínimo para el header fijo */
            padding-top: 10px; /* Espacio adicional reducido */
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .empty-cart-container {
                min-height: 50vh;
                padding: 1rem;
            }
            
            .empty-cart-card {
                padding: 2rem 1.5rem;
            }
            
            .cart-icon-container {
                width: 6rem;
                height: 6rem;
            }
            
            .cart-icon {
                width: 3rem;
                height: 3rem;
            }
            
            .content {
                margin-top: 100px; /* Espacio reducido en móviles */
                padding-top: 5px;
            }
        }
        
        @media (max-width: 480px) {
            .empty-cart-card {
                padding: 1.5rem 1rem;
            }
            
            .cart-icon-container {
                width: 5rem;
                height: 5rem;
            }
            
            .cart-icon {
                width: 2.5rem;
                height: 2.5rem;
            }
        }
    </style>
</head>
<body class="bg-white">
    <div class="app-wrapper">
        {{-- Componentes globales --}}
        <x-header />
        <x-navbar />

        <main class="content min-h-screen py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Carrito de Compras</h1>
                    <p class="text-gray-600">Revisa tus productos antes de proceder al checkout</p>
                </div>

                @if(empty($items))
                    <!-- Carrito vacío mejorado -->
                    <div class="empty-cart-container flex flex-col items-center justify-center px-4">
                        <!-- Contenedor principal con diseño corporativo -->
                        <div class="empty-cart-card rounded-2xl p-8 md:p-12 max-w-md w-full text-center">
                            <!-- Ícono del carrito con paleta corporativa -->
                            <div class="mb-8">
                                <div class="cart-icon-container mx-auto w-32 h-32 rounded-full flex items-center justify-center mb-6">
                                    <svg class="cart-icon w-16 h-16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                                    </svg>
                                </div>
                                
                                <!-- Elementos decorativos -->
                                <div class="flex justify-center space-x-2 mb-4">
                                    <div class="decorative-dots w-2 h-2 bg-green-400 rounded-full"></div>
                                    <div class="decorative-dots w-2 h-2 bg-green-300 rounded-full"></div>
                                    <div class="decorative-dots w-2 h-2 bg-green-200 rounded-full"></div>
                                </div>
                            </div>
                            
                            <!-- Texto principal -->
                            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-3">
                                Tu carrito está vacío
                            </h2>
                            
                            <!-- Descripción -->
                            <p class="text-gray-600 text-base md:text-lg mb-8 leading-relaxed">
                                Explora nuestros productos y agrega los que más te gusten para comenzar tu compra
                            </p>
                            
                            <!-- Botón de acción -->
                            <a href="{{ route('productos.public.index') }}" 
                               class="continue-btn inline-flex items-center justify-center px-8 py-4 text-white font-semibold text-lg rounded-xl focus:outline-none focus:ring-4 focus:ring-green-200">
                                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                Continuar Comprando
                            </a>
                            
                            <!-- Enlaces adicionales -->
                            <div class="mt-8 pt-6 border-t border-gray-100">
                                <p class="text-sm text-gray-500 mb-4">O explora nuestras categorías:</p>
                                <div class="flex flex-wrap justify-center gap-3">
                                    <a href="{{ route('home') }}" class="text-sm text-green-600 hover:text-green-700 font-medium transition-colors">
                                        🏠 Inicio
                                    </a>
                                    <span class="text-gray-300">•</span>
                                    <a href="{{ route('productos.public.index') }}" class="text-sm text-green-600 hover:text-green-700 font-medium transition-colors">
                                        🛍️ Todos los Productos
                                    </a>
                                    <span class="text-gray-300">•</span>
                                    <a href="{{ route('home') }}#categorias" class="text-sm text-green-600 hover:text-green-700 font-medium transition-colors">
                                        📂 Categorías
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Elementos decorativos de fondo -->
                        <div class="absolute inset-0 -z-10 overflow-hidden pointer-events-none">
                            <div class="background-decoration absolute top-1/4 left-1/4 w-64 h-64 bg-green-100 rounded-full mix-blend-multiply filter blur-xl opacity-20"></div>
                            <div class="background-decoration absolute top-3/4 right-1/4 w-64 h-64 bg-green-200 rounded-full mix-blend-multiply filter blur-xl opacity-20"></div>
                        </div>
                    </div>
                @else
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Lista de productos -->
                        <div class="lg:col-span-2">
                            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                                <div class="p-6">
                                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Productos en tu carrito</h2>
                                    
                                    <div class="space-y-4">
                                        @foreach($items as $item)
                                            <div class="cart-item flex items-center space-x-4 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                                <!-- Imagen del producto -->
                                                <div class="flex-shrink-0">
                                                    <img src="{{ $item['product']->Foto ? asset('storage/' . $item['product']->Foto) : asset('images/default-product.png') }}" 
                                                         alt="{{ $item['product']->Nombre }}" 
                                                         class="w-20 h-20 object-cover rounded-lg">
                                                </div>
                                                
                                                <!-- Información del producto -->
                                                <div class="flex-1 min-w-0">
                                                    <h3 class="text-sm font-medium text-gray-900 truncate">
                                                        {{ $item['product']->Nombre }}
                                                    </h3>
                                                    <p class="text-sm text-gray-500">
                                                        {{ $item['product']->empresa->Nombre }}
                                                    </p>
                                                    <p class="text-sm text-gray-500">
                                                        Código: {{ $item['product']->Codigo }}
                                                    </p>
                                                    
                                                    @if($item['discount'] > 0)
                                                        <div class="flex items-center space-x-2 mt-1">
                                                            <span class="text-sm text-gray-500 line-through">
                                                                ${{ number_format($item['original_price'], 0, ',', '.') }}
                                                            </span>
                                                            <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded-full">
                                                                -{{ number_format(($item['discount'] / $item['original_price']) * 100, 0) }}%
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                                
                                                <!-- Cantidad -->
                                                <div class="flex items-center space-x-2">
                                                    <button onclick="updateQuantity({{ $item['product']->Id_Producto }}, {{ $item['quantity'] - 1 }})" 
                                                            class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-100 transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                        </svg>
                                                    </button>
                                                    <span class="w-12 text-center font-medium">{{ $item['quantity'] }}</span>
                                                    <button onclick="updateQuantity({{ $item['product']->Id_Producto }}, {{ $item['quantity'] + 1 }})" 
                                                            class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-100 transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                                
                                                <!-- Precio -->
                                                <div class="text-right">
                                                    <p class="text-lg font-semibold text-gray-900">
                                                        ${{ number_format($item['line_total'], 0, ',', '.') }}
                                                    </p>
                                                    <p class="text-sm text-gray-500">
                                                        ${{ number_format($item['unit_price'], 0, ',', '.') }} c/u
                                                    </p>
                                                </div>
                                                
                                                <!-- Botón eliminar -->
                                                <button onclick="removeFromCart({{ $item['product']->Id_Producto }})" 
                                                        class="text-red-500 hover:text-red-700 transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                    <!-- Botones de acción -->
                                    <div class="mt-6 flex flex-col sm:flex-row gap-4">
                                        <a href="{{ route('productos.public.index') }}" 
                                           class="flex-1 text-center px-6 py-3 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors">
                                            Continuar Comprando
                                        </a>
                                        <button onclick="clearCart()" 
                                                class="flex-1 px-6 py-3 border border-red-300 text-red-700 rounded-md hover:bg-red-50 transition-colors">
                                            Vaciar Carrito
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Resumen del pedido -->
                        <div class="lg:col-span-1">
                            <div class="summary-card rounded-lg shadow-sm border border-gray-200 sticky top-4">
                                <div class="p-6">
                                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Resumen del Pedido</h2>
                                    
                                    <div class="space-y-3">
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-600">Subtotal</span>
                                            <span class="font-medium">${{ number_format($subtotal, 0, ',', '.') }}</span>
                                        </div>
                                        
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-600">IVA (19%)</span>
                                            <span class="font-medium">${{ number_format($tax, 0, ',', '.') }}</span>
                                        </div>
                                        
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-600">Envío</span>
                                            <span class="font-medium">
                                                @if($shipping > 0)
                                                    ${{ number_format($shipping, 0, ',', '.') }}
                                                @else
                                                    <span class="text-green-600">Gratis</span>
                                                @endif
                                            </span>
                                        </div>
                                        
                                        @if($shipping > 0)
                                            <div class="text-xs text-gray-500 bg-blue-50 p-2 rounded">
                                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Envío gratis en compras superiores a $100,000
                                            </div>
                                        @endif
                                        
                                        <hr class="my-4">
                                        
                                        <div class="flex justify-between text-lg font-semibold">
                                            <span>Total</span>
                                            <span class="text-green-600">${{ number_format($total, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                    
                                    <!-- Botón de checkout -->
                                    <a href="{{ route('checkout.index') }}" 
                                       class="w-full mt-6 btn-primary text-white py-3 px-4 rounded-md font-medium hover:opacity-90 transition-all text-center block">
                                        Proceder al Checkout
                                    </a>
                                    
                                    <!-- Cupón -->
                                    <div class="mt-4">
                                        <div class="flex space-x-2">
                                            <input type="text" id="couponCode" placeholder="Código de cupón" 
                                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                                            <button onclick="applyCoupon()" 
                                                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors text-sm">
                                                Aplicar
                                            </button>
                                        </div>
                                        <div id="couponMessage" class="mt-2 text-sm"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </main>

        {{-- Footer --}}
        <x-footer />
    </div>

    <script>
    function updateQuantity(productId, newQuantity) {
        if (newQuantity < 1) return;
        
        const formData = new FormData();
        formData.append('product_id', productId);
        formData.append('quantity', newQuantity);
        formData.append('_token', '{{ csrf_token() }}');
        
        fetch('{{ route("cart.update") }}', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                window.cartManager.showNotification(data.error || 'Error al actualizar la cantidad', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            window.cartManager.showNotification('Error al actualizar la cantidad', 'error');
        });
    }

    function removeFromCart(productId) {
        window.cartManager.showConfirmModal(
            'Eliminar Producto',
            '¿Estás seguro de que quieres eliminar este producto del carrito?',
            () => {
                // Función a ejecutar al confirmar
                const formData = new FormData();
                formData.append('product_id', productId);
                formData.append('_token', '{{ csrf_token() }}');
                
                fetch('{{ route("cart.remove") }}', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        window.cartManager.showNotification(data.error || 'Error al eliminar el producto', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    window.cartManager.showNotification('Error al eliminar el producto', 'error');
                });
            }
        );
    }

    function clearCart() {
        window.cartManager.showConfirmModal(
            'Vaciar Carrito',
            '¿Estás seguro de que quieres vaciar todo el carrito?',
            () => {
                // Función a ejecutar al confirmar
                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                
                fetch('{{ route("cart.clear") }}', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        window.cartManager.showNotification('Error al vaciar el carrito', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    window.cartManager.showNotification('Error al vaciar el carrito', 'error');
                });
            }
        );
    }

    function applyCoupon() {
        const couponCode = document.getElementById('couponCode').value.trim();
        const messageDiv = document.getElementById('couponMessage');
        
        if (!couponCode) {
            messageDiv.innerHTML = '<span class="text-red-600">Ingresa un código de cupón</span>';
            return;
        }
        
        fetch('{{ route("coupons.apply") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                code: couponCode,
                subtotal: {{ $subtotal }},
                product_ids: [{{ collect($items)->pluck('product.Id_Producto')->implode(',') }}]
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                messageDiv.innerHTML = '<span class="text-green-600">✓ ' + data.message + '</span>';
                setTimeout(() => location.reload(), 1000);
            } else {
                messageDiv.innerHTML = '<span class="text-red-600">✗ ' + data.message + '</span>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            messageDiv.innerHTML = '<span class="text-red-600">Error al aplicar el cupón</span>';
        });
    }
    </script>
</body>
</html>