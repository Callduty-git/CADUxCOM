<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $producto->Nombre }} - CADUxCOM</title>
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/product-detail.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- JavaScript del carrito -->
    <script src="{{ asset('js/cart.js') }}"></script>
    
    <!-- Estilos específicos para la página de detalles de productos -->
    <style>
        /* Espaciado superior específico para la página de detalles de productos */
        .product-detail-container {
            margin-top: 100px; /* Espacio justo para el header fijo */
            padding-top: 0; /* Sin padding adicional */
        }
        
        /* Espaciado específico para el breadcrumb */
        .breadcrumb {
            margin-top: 60px; /* Espacio aún mayor antes del breadcrumb */
            padding-top: 15px; /* Padding adicional */
        }
        
        /* Responsive para el espaciado */
        @media (max-width: 768px) {
            .product-detail-container {
                margin-top: 110px; /* Espacio mínimo en móviles */
                padding-top: 0;
            }
            
            .breadcrumb {
                margin-top: 45px; /* Espacio mayor en móviles */
                padding-top: 12px;
            }
        }
    </style>
</head>
<body>
    <x-header-pages />
    
    <div class="product-detail-container">
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <div class="breadcrumb-content">
                <a href="{{ route('home') }}" class="breadcrumb-link">Inicio</a>
                <span class="breadcrumb-separator">/</span>
                <a href="{{ route('productos.public.index') }}" class="breadcrumb-link">Productos</a>
                <span class="breadcrumb-separator">/</span>
                <span class="breadcrumb-current">{{ $producto->Nombre }}</span>
            </div>
        </div>

        <div class="product-detail-content">
            <!-- Imagen del producto -->
            <div class="product-image-section">
                <div class="product-image-container">
                    @if ($producto->Foto)
                        <img src="{{ asset('storage/' . $producto->Foto) }}" 
                             alt="{{ $producto->Nombre }}" 
                             class="product-main-image">
                    @else
                        <div class="product-placeholder">
                            <img src="{{ asset('images/icon-user.png') }}" alt="Sin imagen" class="placeholder-icon">
                            <p>Sin imagen disponible</p>
                        </div>
                    @endif
                    
                    <!-- Botón de favoritos -->
                    <x-wishlist-button :product-id="$producto->Id_Producto" />
                    
                    <!-- Badge de descuento progresivo -->
                    @php
                        $discountInfo = $producto->getDiscountInfo();
                    @endphp
                    @if($discountInfo['has_discount'])
                        <div class="discount-badge {{ $discountInfo['expiry_class'] }}">
                            -{{ round($discountInfo['discount_percentage'], 0) }}% OFF
                        </div>
                    @endif
                </div>
            </div>

            <!-- Información del producto -->
            <div class="product-info-section">
                <!-- Título y empresa -->
                <div class="product-header">
                    <h1 class="product-title">{{ $producto->Nombre }}</h1>
                    <p class="product-company">por <span class="company-name">{{ $producto->empresa->Nombre ?? 'Empresa no disponible' }}</span></p>
                </div>

                <!-- Precios con descuento progresivo -->
                <div class="price-section">
                    @if($discountInfo['has_discount'])
                        <div class="price-container">
                            <span class="current-price">${{ number_format($discountInfo['discounted_price'], 0, ',', '.') }}</span>
                            <span class="original-price">${{ number_format($discountInfo['original_price'], 0, ',', '.') }}</span>
                            <div class="savings {{ $discountInfo['expiry_class'] }}">
                                {{ $discountInfo['savings_message'] }}
                            </div>
                        </div>
                    @else
                        <span class="current-price">${{ number_format($producto->Precio, 0, ',', '.') }}</span>
                    @endif
                </div>

                <!-- Stock y disponibilidad -->
                <div class="stock-section">
                    @if($producto->Cantidad > 0)
                        <div class="stock-available">
                            <span class="stock-icon">✓</span>
                            <span class="stock-text">Disponible</span>
                            <span class="stock-quantity">({{ $producto->Cantidad }} {{ $producto->Tipo }} en stock)</span>
                        </div>
                    @else
                        <div class="stock-unavailable">
                            <span class="stock-icon">✗</span>
                            <span class="stock-text">Agotado</span>
                        </div>
                    @endif
                </div>

                <!-- Fecha de caducidad con estado -->
                @if($producto->Fecha_Caducidad)
                    <div class="expiry-section {{ $discountInfo['expiry_class'] }}">
                        <span class="expiry-label">Fecha de caducidad:</span>
                        <span class="expiry-date">{{ \Carbon\Carbon::parse($producto->Fecha_Caducidad)->format('d/m/Y') }}</span>
                        <span class="expiry-status">{{ $discountInfo['expiry_label'] }}</span>
                        @if($discountInfo['has_discount'])
                            <div class="discount-info">
                                <small>Descuento aplicado por proximidad a caducidad</small>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Botones de acción -->
                @if($producto->Cantidad > 0)
                    <div class="action-buttons">
                        <button onclick="addToCart({{ $producto->Id_Producto }})" class="btn-add-cart"
                                id="add-cart-btn-{{ $producto->Id_Producto }}">
                            <img src="{{ asset('images/carrito-de-compras.png') }}" alt="Carrito" class="btn-icon">
                            <span class="btn-text">Agregar al Carrito</span>
                        </button>
                        
                    </div>
                @else
                    <div class="out-of-stock">
                        <button class="btn-out-of-stock" disabled>
                            Producto Agotado
                        </button>
                    </div>
                @endif

                <!-- Información del vendedor -->
                <div class="seller-info">
                    <h3 class="seller-title">Información del Vendedor</h3>
                    <div class="seller-details">
                        <div class="seller-item">
                            <span class="seller-label">Empresa:</span>
                            <span class="seller-value">{{ $producto->empresa->Nombre ?? 'N/A' }}</span>
                        </div>
                        <div class="seller-item">
                            <span class="seller-label">Contacto:</span>
                            <span class="seller-value">{{ $producto->empresa->Contacto ?? 'N/A' }}</span>
                        </div>
                        <div class="seller-item">
                            <span class="seller-label">Ubicación:</span>
                            <span class="seller-value">{{ $producto->empresa->Ubicacion ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información detallada -->
        <div class="product-details-section">
            <div class="details-tabs">
                <button class="tab-button active" onclick="showTab('description')">Descripción</button>
                <button class="tab-button" onclick="showTab('specifications')">Especificaciones</button>
                <button class="tab-button" onclick="showTab('reviews')">Reseñas</button>
            </div>

            <div class="tab-content">
                <!-- Descripción -->
                <div id="description" class="tab-panel active">
                    <h3>Descripción del Producto</h3>
                    <p>{{ $producto->Descripcion ?? 'Sin descripción disponible' }}</p>
                </div>

                <!-- Especificaciones -->
                <div id="specifications" class="tab-panel">
                    <h3>Especificaciones</h3>
                    <div class="specs-grid">
                        <div class="spec-item">
                            <span class="spec-label">Marca:</span>
                            <span class="spec-value">{{ $producto->Marca }}</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Código:</span>
                            <span class="spec-value">{{ $producto->Codigo }}</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Categoría:</span>
                            <span class="spec-value">{{ $producto->subcategoria->categoria->Nombre ?? 'N/A' }}</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Subcategoría:</span>
                            <span class="spec-value">{{ $producto->subcategoria->Nombre ?? 'N/A' }}</span>
                        </div>
                        @if($producto->Fecha_Caducidad)
                            <div class="spec-item">
                                <span class="spec-label">Fecha de Caducidad:</span>
                                <span class="spec-value">{{ \Carbon\Carbon::parse($producto->Fecha_Caducidad)->format('d/m/Y') }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Reseñas -->
                <div id="reviews" class="tab-panel">
                    <h3>Reseñas de Clientes</h3>
                    <div class="reviews-placeholder">
                        <p>Las reseñas estarán disponibles próximamente.</p>
                        <p>¡Sé el primero en dejar tu opinión sobre este producto!</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Productos relacionados -->
        <div class="related-products-section">
            <h2>Productos Relacionados</h2>
            <div class="related-products-grid">
                <!-- Aquí se pueden agregar productos relacionados -->
                <div class="related-product-placeholder">
                    <p>Productos relacionados próximamente</p>
                </div>
            </div>
        </div>

        <!-- Botones de navegación -->
        <div class="navigation-buttons">
            <a href="{{ route('productos.public.index') }}" class="btn-back">
                ← Volver a Productos
            </a>
        </div>
    </div>

    <x-footer />

    {{-- Scripts centralizados del carrito --}}
    <x-cart-scripts />

    <script>
        // Funcionalidad de tabs
        function showTab(tabName) {
            // Ocultar todos los paneles
            document.querySelectorAll('.tab-panel').forEach(panel => {
                panel.classList.remove('active');
            });
            
            // Remover clase active de todos los botones
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('active');
            });
            
            // Mostrar el panel seleccionado
            document.getElementById(tabName).classList.add('active');
            
            // Agregar clase active al botón seleccionado
            event.target.classList.add('active');
        }

        // Funcionalidad del carrito (sobrescribir la función global para incluir cantidad)
        function addToCart(productId) {
            const quantity = document.getElementById('quantity') ? document.getElementById('quantity').value : 1;
            const button = document.getElementById(`add-cart-btn-${productId}`);
            window.cartManager.addToCart(productId, quantity, button);
        }
    </script>
</body>
</html>

