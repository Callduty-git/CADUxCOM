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
    <script src="{{ asset('js/modal-alert.js') }}"></script>
    
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
        
        /* Espaciado superior específico para la página de wishlist */
        .wishlist-container {
            margin-top: 120px; /* Espacio mayor para el header fijo más alto */
            padding-top: 20px; /* Espacio adicional para mejor separación */
        }
        
        /* Responsive para el espaciado */
        @media (max-width: 768px) {
            .wishlist-container {
                margin-top: 130px; /* Espacio mayor en móviles */
                padding-top: 15px;
            }
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
                    <img src="{{ asset('images/heart-icon.svg') }}" alt="Favoritos" class="heart-icon">
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
                        <img src="{{ asset('images/heart-icon.svg') }}" alt="Total" class="stat-icon-img">
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
                                <img src="{{ asset('images/heart-filled-icon.svg') }}" alt="Quitar" class="favorite-icon">
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
                    <img src="{{ asset('images/heart-icon.svg') }}" alt="Favoritos vacíos" class="empty-heart">
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
        // ========================================
        // FUNCIONES PRINCIPALES - DEFINIDAS AL INICIO
        // ========================================

        // Función para limpiar toda la wishlist
        async function clearWishlist() {
            console.log('Iniciando clearWishlist...');

            // Usar confirmación simple del navegador por ahora
            showModalAlert({
                title: 'Confirmar acción',
                message: '¿Estás seguro de que quieres limpiar toda tu lista de favoritos?',
                confirmText: 'Sí, limpiar',
                cancelText: 'Cancelar',
                color: '#AA5FC7',
                accent: '#89CF6D',
                showCancel: true,
                onConfirm: async () => {
                    console.log('Usuario confirmó, procediendo...');
                    try {
                        console.log('Enviando petición POST a:', '{{ route("wishlist.clear.post") }}');

                        const response = await fetch('{{ route("wishlist.clear.post") }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        });

                        console.log('Respuesta del servidor:', response.status, response.statusText);

                        const responseText = await response.text();
                        console.log('Respuesta completa:', responseText);

                        if (!response.ok) {
                            throw new Error(`HTTP ${response.status}: ${responseText}`);
                        }

                        const data = JSON.parse(responseText);
                        console.log('Datos parseados:', data);

                        if (data.success) {
                            console.log('Éxito: actualizando interfaz');
                            showNotification('Lista de favoritos limpiada correctamente', 'success');

                            // Actualizar interfaz inmediatamente
                            updateWishlistInterface();
                            updateWishlistCountHeader();
                        } else {
                            console.error('Error del servidor:', data.message);
                            showNotification(data.message || 'Error al limpiar favoritos', 'error');
                        }
                    } catch (error) {
                        console.error('Error en clearWishlist:', error);
                        showNotification('Error al limpiar favoritos: ' + error.message, 'error');
                    }
                },
                onCancel: () => {
                    console.log('Usuario canceló la operación');
                    return;
                }
            });
            return;
        }

        // Función para agregar todos al carrito
        function addAllToCart() {
            const productButtons = document.querySelectorAll('.btn-add-to-cart');
            const totalProducts = productButtons.length;

            if (totalProducts === 0) {
                showNotification('No hay productos para agregar', 'info');
                return;
            }

            // Confirmación personalizada
            showCustomConfirm(
                '¿Agregar todos al carrito?',
                `¿Quieres agregar los ${totalProducts} productos de tu lista de favoritos al carrito de compras?`,
                'Agregar todos',
                'Cancelar'
            ).then(confirmed => {
                if (!confirmed) return;

                showLoadingOverlay();
                showNotification(`Agregando ${totalProducts} productos al carrito...`, 'info');

                let successCount = 0;
                let errorCount = 0;
                let processedCount = 0;

                const processNext = () => {
                    if (processedCount >= totalProducts) {
                        // Todos los productos procesados
                        hideLoadingOverlay();

                        if (successCount > 0) {
                            showSuccessAnimation();
                            showNotification(`${successCount} de ${totalProducts} productos agregados al carrito`, 'success');
                            window.cartManager?.updateCartCounter();
                        } else {
                            showNotification('No se pudieron agregar productos al carrito', 'error');
                        }

                        if (errorCount > 0) {
                            showNotification(`${errorCount} productos no se pudieron agregar`, 'info');
                        }
                        return;
                    }

                    const button = productButtons[processedCount];
                    const productId = button.getAttribute('onclick')?.match(/\d+/)?.[0];

                    if (productId) {
                        // Usar el cartManager existente
                        const success = window.cartManager?.addToCart(productId, 1);
                        if (success) {
                            successCount++;
                        } else {
                            errorCount++;
                        }
                    } else {
                        errorCount++;
                    }

                    processedCount++;

                    // Procesar el siguiente producto después de un pequeño delay
                    setTimeout(processNext, 100);
                };

                // Iniciar procesamiento
                processNext();
            });
        }

        // Función para remover de favoritos
        async function removeFromWishlist(productId) {
            showModalAlert({
                title: 'Confirmar acción',
                message: '¿Estás seguro de que quieres quitar este producto de favoritos?',
                confirmText: 'Sí, quitar',
                cancelText: 'Cancelar',
                color: '#AA5FC7',
                accent: '#89CF6D',
                showCancel: true,
                onConfirm: async () => {
                    try {
                        // Mostrar notificación de carga
                        showNotification('Removiendo de favoritos...', 'info');

                        const response = await fetch('{{ route("wishlist.remove") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                product_id: productId
                            })
                        });

                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }

                        const data = await response.json();

                        if (data.success) {
                            // Remover el elemento del DOM con animación
                            const item = document.querySelector(`[data-product-id="${productId}"]`);
                            if (item) {
                                item.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                                item.style.opacity = '0';
                                item.style.transform = 'scale(0.8)';
                                setTimeout(() => {
                                    item.remove();
                                }, 300);
                            }
                            
                            // Actualizar contador
                            updateWishlistCount(data.wishlist_count);
                            
                            // Mostrar mensaje de éxito
                            showNotification(data.message || 'Producto removido de favoritos', 'success');
                            
                            // Si no hay más elementos, mostrar estado vacío
                            if (data.wishlist_count === 0) {
                                setTimeout(() => {
                                    updateWishlistInterface();
                                }, 500);
                            }
                        } else {
                            showNotification(data.message || 'Error al quitar de favoritos', 'error');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        showNotification('Error al quitar de favoritos: ' + error.message, 'error');
                    }
                },
                onCancel: () => {
                    return;
                }
            });
            return;
        }


        // Función para actualizar la interfaz después de limpiar
        function updateWishlistInterface() {
            // Ocultar todos los productos
            const productItems = document.querySelectorAll('.wishlist-item');
            productItems.forEach(item => {
                item.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                item.style.opacity = '0';
                item.style.transform = 'scale(0.8)';
                setTimeout(() => {
                    item.remove();
                }, 300);
            });

            // Ocultar estadísticas
            const statsSection = document.querySelector('.wishlist-stats');
            if (statsSection) {
                statsSection.style.transition = 'opacity 0.3s ease';
                statsSection.style.opacity = '0';
                setTimeout(() => {
                    statsSection.remove();
                }, 300);
            }

            // Ocultar botón de limpiar
            const clearButton = document.querySelector('.btn-clear-wishlist');
            if (clearButton) {
                clearButton.style.transition = 'opacity 0.3s ease';
                clearButton.style.opacity = '0';
                setTimeout(() => {
                    clearButton.remove();
                }, 300);
            }

            // Mostrar estado vacío después de un breve delay
            setTimeout(() => {
                showEmptyState();
            }, 500);
        }

        // Función para mostrar el estado vacío
        function showEmptyState() {
            const wishlistContainer = document.querySelector('.wishlist-container');
            if (wishlistContainer) {
                const emptyState = document.createElement('div');
                emptyState.className = 'empty-wishlist';
                emptyState.style.opacity = '0';
                emptyState.style.transition = 'opacity 0.5s ease';
                
                emptyState.innerHTML = `
                    <div class="empty-icon">
                        <img src="{{ asset('images/heart-icon.svg') }}" alt="Favoritos vacíos" class="empty-heart">
                    </div>
                    <h2 class="empty-title">Tu lista de favoritos está vacía</h2>
                    <p class="empty-description">Agrega productos que te gusten para verlos aquí</p>
                    <a href="{{ route('home') }}" class="btn-start-shopping">
                        <img src="{{ asset('images/icon-cart.png') }}" alt="Comprar" class="btn-icon">
                        Comenzar a Comprar
                    </a>
                `;
                
                wishlistContainer.appendChild(emptyState);
                
                // Animar entrada
                setTimeout(() => {
                    emptyState.style.opacity = '1';
                }, 100);
            }
        }

        // Función para agregar al carrito (usando el sistema unificado)
        function addToCart(productId) {
            window.cartManager.addToCart(productId, 1);
        }

        // Función para agregar todos al carrito
        async function addAllToCart() {
            const productButtons = document.querySelectorAll('.btn-add-to-cart');
            let successCount = 0;
            let totalProducts = productButtons.length;
            if (totalProducts === 0) {
                showNotification('No hay productos para agregar', 'info');
                return;
            }
            showNotification(`Agregando ${totalProducts} productos al carrito...`, 'info');
            for (const button of productButtons) {
                const productId = button.getAttribute('onclick').match(/\d+/)[0];
                try {
                    const success = await window.cartManager.addToCart(productId, 1);
                    if (success) successCount++;
                } catch (error) {
                    console.error('Error adding product to cart:', error);
                }
            }
            if (successCount > 0) {
                showNotification(`${successCount} de ${totalProducts} productos agregados al carrito`, 'success');
                window.cartManager.updateCartCounter(); // Actualiza el contador del carrito
            } else {
                showNotification('No se pudieron agregar productos al carrito', 'error');
            }
        }

        // Función para actualizar contador de wishlist en header global
        function updateWishlistCountHeader() {
            fetch("{{ route('wishlist.count') }}")
                .then(response => response.json())
                .then(data => {
                    const countElement = document.getElementById('wishlist-count');
                    if (countElement) {
                        countElement.textContent = data.count;
                        countElement.style.display = data.count > 0 ? 'flex' : 'none';
                        countElement.classList.add('update');
                        setTimeout(() => countElement.classList.remove('update'), 500);
                    }
                });
        }

        // Sobrescribir updateWishlistCount para que actualice el header global
        function updateWishlistCount(count) {
            updateWishlistCountHeader();
        }

        // Función para mostrar notificaciones (usando el sistema unificado)
        function showNotification(message, type = 'info') {
            // Usar el sistema de notificaciones del cartManager si está disponible
            if (window.cartManager && window.cartManager.showNotification) {
                window.cartManager.showNotification(message, type);
                return;
            }

            // Fallback: crear notificación personalizada
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
                notification.style.backgroundColor = '#49874E';
            } else if (type === 'error') {
                notification.style.backgroundColor = '#EF4444';
            } else if (type === 'info') {
                notification.style.backgroundColor = '#AA5FC7';
            } else {
                notification.style.backgroundColor = '#90D575';
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

        // Agregar estilos de animación (solo si no existen)
        if (!document.getElementById('wishlist-animations')) {
            const animationStyle = document.createElement('style');
            animationStyle.id = 'wishlist-animations';
            animationStyle.textContent = `
                @keyframes slideIn {
                    from { transform: translateX(100%); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
                @keyframes slideOut {
                    from { transform: translateX(0); opacity: 1; }
                    to { transform: translateX(100%); opacity: 0; }
                }
            `;
            document.head.appendChild(animationStyle);
        }

        // ========================================
        // FUNCIONES AUXILIARES PARA MEJOR UX
        // ========================================

        // Función para mostrar confirmación personalizada
        function showCustomConfirm(title, message, confirmText, cancelText) {
            return new Promise((resolve) => {
                const modal = document.createElement('div');
                modal.className = 'custom-confirm-modal';
                modal.innerHTML = `
                    <div class="confirm-overlay">
                        <div class="confirm-dialog">
                            <div class="confirm-header">
                                <h3>${title}</h3>
                            </div>
                            <div class="confirm-body">
                                <p>${message}</p>
                            </div>
                            <div class="confirm-footer">
                                <button class="btn-cancel">${cancelText}</button>
                                <button class="btn-confirm">${confirmText}</button>
                            </div>
                        </div>
                    </div>
                `;

                document.body.appendChild(modal);

                // Animar entrada
                setTimeout(() => modal.classList.add('show'), 10);

                const confirmBtn = modal.querySelector('.btn-confirm');
                const cancelBtn = modal.querySelector('.btn-cancel');

                const cleanup = (result) => {
                    modal.classList.remove('show');
                    setTimeout(() => {
                        if (modal.parentNode) {
                            modal.parentNode.removeChild(modal);
                        }
                    }, 300);
                    resolve(result);
                };

                confirmBtn.addEventListener('click', () => cleanup(true));
                cancelBtn.addEventListener('click', () => cleanup(false));

                // Cerrar con ESC
                document.addEventListener('keydown', function escHandler(e) {
                    if (e.key === 'Escape') {
                        document.removeEventListener('keydown', escHandler);
                        cleanup(false);
                    }
                });
            });
        }

        // Función para mostrar overlay de carga
        function showLoadingOverlay() {
            const container = document.querySelector('.wishlist-container');
            if (container) {
                const overlay = document.createElement('div');
                overlay.className = 'loading-overlay';
                overlay.innerHTML = `
                    <div class="loading-spinner"></div>
                    <p class="fw-bold" style="margin-left: 16px; color: var(--color-green-dark);">Procesando...</p>
                `;
                container.appendChild(overlay);
            }
        }

        // Función para ocultar overlay de carga
        function hideLoadingOverlay() {
            const overlay = document.querySelector('.loading-overlay');
            if (overlay) {
                overlay.remove();
            }
        }

        // Función para mostrar animación de éxito
        function showSuccessAnimation() {
            return new Promise((resolve) => {
                const container = document.querySelector('.wishlist-container');
                if (container) {
                    container.classList.add('success-flash');
                    setTimeout(() => {
                        container.classList.remove('success-flash');
                        resolve();
                    }, 600);
                } else {
                    resolve();
                }
            });
        }

    </script>

    <!-- CSS adicional para los modales personalizados -->
    <style>
        .custom-confirm-modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 10000;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .custom-confirm-modal.show {
            opacity: 1;
        }

        .confirm-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .confirm-dialog {
            background: var(--color-white);
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            max-width: 400px;
            width: 100%;
            overflow: hidden;
            transform: scale(0.9);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .custom-confirm-modal.show .confirm-dialog {
            transform: scale(1);
        }

        .confirm-header {
            padding: 24px 24px 0 24px;
        }

        .confirm-header h3 {
            margin: 0;
            color: var(--color-gray-dark);
            font-size: 1.5rem;
            font-weight: 700;
        }

        .confirm-body {
            padding: 16px 24px;
        }

        .confirm-body p {
            margin: 0;
            color: var(--color-gray-medium);
            line-height: 1.5;
        }

        .confirm-footer {
            padding: 0 24px 24px 24px;
            display: flex;
            gap: 12px;
            justify-content: flex-end;
        }

        .confirm-footer button {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .btn-cancel {
            background: var(--color-gray-light);
            color: var(--color-gray-medium);
        }

        .btn-cancel:hover {
            background: #e9ecef;
            transform: translateY(-1px);
        }

        .btn-confirm {
            background: var(--color-green-light);
            color: var(--color-white);
            box-shadow: 0 4px 12px rgba(137, 207, 109, 0.3);
        }

        .btn-confirm:hover {
            background: var(--color-green-dark);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(137, 207, 109, 0.4);
        }

        .btn-confirm:active,
        .btn-cancel:active {
            transform: scale(0.96);
        }

        @media (max-width: 480px) {
            .confirm-dialog {
                margin: 20px;
                max-width: none;
            }

            .confirm-footer {
                flex-direction: column;
            }

            .confirm-footer button {
                width: 100%;
            }
        }
    </style>

    <!-- Footer -->
    <x-footer />
</body>
</html>

