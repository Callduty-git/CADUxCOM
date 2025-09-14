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
    </style>
</head>
<body class="bg-gray-50">
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
                    <!-- Carrito vacío -->
                    <div class="text-center py-16">
                        <div class="mb-6">
                            <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Tu carrito está vacío</h3>
                        <p class="text-gray-600 mb-6">Agrega algunos productos para comenzar tu compra</p>
                        <a href="{{ route('productos.public.index') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white btn-primary hover:opacity-90 transition-all">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            Continuar Comprando
                        </a>
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
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.error || 'Error al actualizar la cantidad');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al actualizar la cantidad');
        });
    }

    function removeFromCart(productId) {
        if (!confirm('¿Estás seguro de que quieres eliminar este producto del carrito?')) return;
        
        const formData = new FormData();
        formData.append('product_id', productId);
        formData.append('_token', '{{ csrf_token() }}');
        
        fetch('{{ route("cart.remove") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.error || 'Error al eliminar el producto');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al eliminar el producto');
        });
    }

    function clearCart() {
        if (!confirm('¿Estás seguro de que quieres vaciar todo el carrito?')) return;
        
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        
        fetch('{{ route("cart.clear") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error al vaciar el carrito');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al vaciar el carrito');
        });
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