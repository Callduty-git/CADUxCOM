<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mis Favoritos - CADUxCOM</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- CSS del header -->
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <!-- CSS específico para wishlist -->
    <link rel="stylesheet" href="{{ asset('css/wishlist.css') }}">
    <!-- CSS del footer -->
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    
    <!-- JavaScript del carrito -->
    <script src="{{ asset('js/cart.js') }}"></script>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Figtree', Arial, sans-serif;
            background-color: #FFFFFF !important;
            color: #333;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <!-- HEADER DEL DASHBOARD -->
    <x-header />
    
    <!-- CONTENIDO PRINCIPAL -->
    <div class="wishlist-container">
        <div class="wishlist-header">
            <div class="wishlist-title-section">
                <div class="wishlist-icon">
                    <img src="{{ asset('images/favoritos.png') }}" alt="Favoritos" class="heart-icon">
                </div>
                <div class="wishlist-title-content">
                    <h1 class="wishlist-title">Mis Favoritos</h1>
                    <p class="wishlist-subtitle">Productos que te gustan y quieres comprar más tarde</p>
                </div>
            </div>
            
            @if($wishlistCount > 0)
                <div class="wishlist-actions">
                    <button class="btn-clear-wishlist" onclick="clearWishlist()">
                        <img src="{{ asset('images/escoba.png') }}" alt="Limpiar" class="btn-icon">
                        Limpiar Lista
                    </button>
                </div>
            @endif
        </div>

        @if($wishlistItems->count() > 0)
            <!-- ESTADÍSTICAS -->
            <div class="wishlist-stats">
                <div class="stat-card">
                    <div class="stat-icon">
                        <img src="{{ asset('images/favoritos.png') }}" alt="Total" class="stat-icon-img">
                    </div>
                    <div class="stat-content">
                        <p class="stat-label">Total Favoritos</p>
                        <p class="stat-value">{{ $wishlistCount }}</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <img src="{{ asset('images/icon-cart.png') }}" alt="Carrito" class="stat-icon-img">
                    </div>
                    <div class="stat-content">
                        <p class="stat-label">Agregar al Carrito</p>
                        <button class="btn-add-all" onclick="addAllToCart()">
                            Agregar Todos
                        </button>
                    </div>
                </div>
            </div>

            <!-- LISTA DE PRODUCTOS -->
            <div class="wishlist-grid">
                @foreach($wishlistItems as $item)
                    <div class="wishlist-item" data-product-id="{{ $item->product->Id_Producto }}">
                        <div class="product-image">
                            @if($item->product->Imagen && file_exists(public_path('storage/' . $item->product->Imagen)))
                                <img src="{{ asset('storage/' . $item->product->Imagen) }}" alt="{{ $item->product->Nombre }}" class="product-img" loading="lazy">
                            @elseif($item->product->Foto && file_exists(public_path('storage/' . $item->product->Foto)))
                                <img src="{{ asset('storage/' . $item->product->Foto) }}" alt="{{ $item->product->Nombre }}" class="product-img" loading="lazy">
                            @else
                                <div class="no-image-placeholder">
                                    <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                        <circle cx="8.5" cy="8.5" r="1.5"/>
                                        <polyline points="21,15 16,10 5,21"/>
                                    </svg>
                                    <span>Sin imagen</span>
                                </div>
                            @endif
                            
                            <!-- Botón de eliminar de favoritos -->
                            <button class="remove-favorite-btn" onclick="removeFromWishlist({{ $item->product->Id_Producto }})" title="Quitar de favoritos">
                                <img src="{{ asset('images/favoritos.png') }}" alt="Quitar" class="favorite-icon">
                            </button>
                        </div>
                        
                        <div class="product-info">
                            <h3 class="product-name">{{ $item->product->Nombre }}</h3>
                            <p class="product-company">{{ $item->product->empresa->Nombre ?? 'Sin empresa' }}</p>
                            <p class="product-category">{{ $item->product->subcategoria->Nombre ?? 'Sin categoría' }}</p>
                            
                            <div class="product-price">
                                <span class="price">${{ number_format($item->product->Precio, 0, ',', '.') }}</span>
                                @if($item->product->PrecioOriginal && $item->product->PrecioOriginal > $item->product->Precio)
                                    <span class="discount-price">${{ number_format($item->product->PrecioOriginal, 0, ',', '.') }}</span>
                                @endif
                            </div>
                            
                            <!-- Información adicional del producto -->
                            <div class="product-details">
                                @if($item->product->Marca)
                                    <span class="product-brand">Marca: {{ $item->product->Marca }}</span>
                                @endif
                                @if($item->product->Fecha_Caducidad)
                                    <span class="product-expiry">Vence: {{ \Carbon\Carbon::parse($item->product->Fecha_Caducidad)->format('d/m/Y') }}</span>
                                @endif
                            </div>
                            
                            <div class="product-actions">
                                <button class="btn-add-to-cart" onclick="addToCart({{ $item->product->Id_Producto }})">
                                    <img src="{{ asset('images/icon-cart.png') }}" alt="Carrito" class="btn-icon">
                                    Agregar al Carrito
                                </button>
                                
                                <a href="{{ route('productos.user.show', $item->product->Id_Producto) }}" class="btn-view-details">
                                    Ver Detalles
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- ESTADO VACÍO -->
            <div class="empty-wishlist">
                <div class="empty-icon">
                    <img src="{{ asset('images/favoritos.png') }}" alt="Favoritos vacíos" class="empty-heart">
                </div>
                <h2 class="empty-title">Tu lista de favoritos está vacía</h2>
                <p class="empty-description">Agrega productos que te gusten para verlos aquí</p>
                <a href="{{ route('home') }}" class="btn-start-shopping">
                    <img src="{{ asset('images/icon-cart.png') }}" alt="Comprar" class="btn-icon">
                    Comenzar a Comprar
                </a>
            </div>
        @endif
    </div>

    <!-- SCRIPT DE FUNCIONALIDAD -->
    <script>
        // Función para remover de favoritos
        function removeFromWishlist(productId) {
            if (!confirm('¿Estás seguro de que quieres quitar este producto de favoritos?')) {
                return;
            }

            fetch('{{ route("wishlist.remove") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    product_id: productId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remover el elemento del DOM
                    const item = document.querySelector(`[data-product-id="${productId}"]`);
                    if (item) {
                        item.remove();
                    }
                    
                    // Actualizar contador
                    updateWishlistCount(data.wishlist_count);
                    
                    // Mostrar mensaje
                    showNotification(data.message, 'success');
                    
                    // Si no hay más elementos, recargar la página
                    if (data.wishlist_count === 0) {
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    }
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error al quitar de favoritos', 'error');
            });
        }

        // Función para limpiar toda la wishlist
        function clearWishlist() {
            if (!confirm('¿Estás seguro de que quieres limpiar toda tu lista de favoritos?')) {
                return;
            }

            fetch('{{ route("wishlist.clear") }}', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    showNotification('Error al limpiar favoritos', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error al limpiar favoritos', 'error');
            });
        }

        // Función para agregar al carrito (usando el sistema unificado)
        function addToCart(productId) {
            window.cartManager.addToCart(productId, 1);
        }

        // Función para agregar todos al carrito
        async function addAllToCart() {
            const productButtons = document.querySelectorAll('.btn-add-to-cart');
            let successCount = 0;
            
            for (const button of productButtons) {
                const productId = button.getAttribute('onclick').match(/\d+/)[0];
                const success = await window.cartManager.addToCart(productId, 1);
                if (success) successCount++;
            }
            
            if (successCount > 0) {
                window.cartManager.showNotification(`${successCount} productos agregados al carrito`, 'success');
            }
        }

        // Función para actualizar contador de wishlist
        function updateWishlistCount(count) {
            const countElement = document.getElementById('wishlist-count');
            if (countElement) {
                countElement.textContent = count;
            }
        }

        // Función para mostrar notificaciones
        function showNotification(message, type = 'info') {
            // Crear elemento de notificación
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            notification.textContent = message;
            
            // Agregar estilos
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 15px 20px;
                border-radius: 8px;
                color: white;
                font-weight: 500;
                z-index: 10000;
                animation: slideIn 0.3s ease-out;
                max-width: 300px;
                font-family: 'Figtree', Arial, sans-serif;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            `;
            
            // Colores según el tipo
            if (type === 'success') {
                notification.style.backgroundColor = 'var(--color-green-dark)';
            } else if (type === 'error') {
                notification.style.backgroundColor = '#e74c3c';
            } else if (type === 'info') {
                notification.style.backgroundColor = 'var(--color-purple)';
            } else {
                notification.style.backgroundColor = 'var(--color-green-light)';
            }
            
            // Agregar al DOM
            document.body.appendChild(notification);
            
            // Remover después de 3 segundos
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease-in';
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }, 3000);
        }

        // Agregar estilos de animación
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOut {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
        `;
        document.head.appendChild(style);
    </script>

    <!-- Footer -->
    <x-footer />
</body>
</html>
