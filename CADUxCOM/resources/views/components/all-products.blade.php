<div class="container">
    <h1>Productos</h1>

    <form action="{{ route('productos.public.index') }}" method="GET" class="filter-form">
        <div class="form-group">
            <input type="text" name="query" placeholder="Buscar..." 
                   class="form-input" 
                   value="{{ request('query') }}">
            <select name="categoria" class="form-select">
                <option value="">Categoría</option>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->Id_Categoria }}" {{ request('categoria') == $categoria->Id_Categoria ? 'selected' : '' }}>
                        {{ $categoria->Nombre }}
                    </option>
                @endforeach
            </select>
            <select name="subcategoria" class="form-select">
                <option value="">Subcategoría</option>
                @foreach($subcategorias as $subcategoria)
                    <option value="{{ $subcategoria->Id_Subcategoria }}" {{ request('subcategoria') == $subcategoria->Id_Subcategoria ? 'selected' : '' }}>
                        {{ $subcategoria->Nombre }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="form-button">Buscar</button>
        </div>
    </form>

    <div class="product-grid">
        @forelse($productos as $producto)
            @php
                $img = $producto->Foto ? asset('storage/' . $producto->Foto) : asset('images/default-product.png');
                $hasOriginal = (float)($producto->PrecioOriginal ?? 0) > 0;
                $discount = $hasOriginal ? max(0, round((1 - ($producto->Precio / $producto->PrecioOriginal)) * 100)) : 0;
            @endphp
            <div class="product-card">
                <div class="media-wrap">
                    @if($discount > 0)
                        <span class="badge-discount">-{{ $discount }}%</span>
                    @endif
                    
                    <!-- Botón de favoritos -->
                    <x-wishlist-button :product-id="$producto->Id_Producto" />

                    <img src="{{ $img }}" alt="{{ $producto->Nombre }}" class="product-image">
                </div>

                <div class="product-details">
                    <h2 class="product-name">{{ $producto->Nombre }}</h2>
                    <p class="product-brand">{{ $producto->Marca }}</p>
                    @if($producto->empresa)
                        <p class="product-company">{{ $producto->empresa->Nombre }} — {{ $producto->empresa->Municipio }}</p>
                    @endif
                </div>

                <div class="product-footer">
                    <div class="footer-prices">
                        <span class="footer-original">${{ number_format($producto->PrecioOriginal, 0, ',', '.') }}</span>
                        <img src="{{ asset('images/flecha-correcta.png') }}" alt="Descuento" class="footer-arrow-img">
                        <span class="footer-discount">${{ number_format($producto->Precio, 0, ',', '.') }}</span>
                    </div>
                    <div class="footer-expire">
                        Vence: {{ \Carbon\Carbon::parse($producto->Fecha_Caducidad)->format('d/m/Y') }}
                    </div>
                    <div class="footer-actions">
                        <a href="{{ route('productos.show', $producto->Id_Producto) }}" class="btn btn-primary">Ver detalles</a>
                        @if($producto->Cantidad > 0)
                            <form method="POST" action="{{ route('cart.add') }}" class="btn-cart-form" data-product-id="{{ $producto->Id_Producto }}">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $producto->Id_Producto }}">
                                <button type="submit" class="btn btn-secondary">Agregar</button>
                            </form>
                        @else
                            <button type="button" class="btn btn-secondary" disabled>Agotado</button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <p class="no-products-message">No se encontraron productos con esos filtros.</p>
        @endforelse
    </div>

    @if(method_exists($productos, 'links'))
        <div class="pagination-wrapper">
            {{ $productos->withQueryString()->links() }}
        </div>
    @endif

    {{-- Scripts centralizados del carrito --}}
    <x-cart-scripts />

    <script>
    // Event listeners para los formularios de carrito
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-cart-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const productId = this.getAttribute('data-product-id');
                addToCart(e, productId);
            });
        });
    });

    async function addToCart(e, id){
        e.preventDefault();
        const form = e.target;
        const data = new FormData(form);
        const button = form.querySelector('button');
        
        // Usar el sistema unificado del carrito si está disponible
        if (window.cartManager && window.cartManager.addToCart) {
            const quantity = data.get('quantity') || 1;
            return await window.cartManager.addToCart(id, parseInt(quantity), button);
        }
        
        // Fallback: implementación local (mantener compatibilidad)
        const originalText = button.innerHTML;

        // Mostrar loader
        button.innerHTML = '<span>Agregando...</span>';
        button.disabled = true;

        try {
            const resp = await fetch(form.action, {
                method:'POST',
                headers:{
                    'X-Requested-With':'XMLHttpRequest',
                    'X-CSRF-TOKEN': data.get('_token')
                },
                body: data
            });
            const json = await resp.json();
            
            if (json && json.count !== undefined) {
                const badge = document.querySelector('.cart-badge');
                if (badge) badge.textContent = json.count;
            }

            if (json.success) {
                // Usar el sistema unificado de notificaciones si está disponible
                if (window.cartManager && window.cartManager.showNotification) {
                    window.cartManager.showNotification('Producto agregado al carrito', 'success');
                } else {
                    showNotification('Producto agregado al carrito', 'success');
                }
            } else {
                if (window.cartManager && window.cartManager.showNotification) {
                    window.cartManager.showNotification(json.error || 'Error al agregar al carrito', 'error');
                } else {
                    showNotification(json.error || 'Error al agregar al carrito', 'error');
                }
            }
        } catch(err) {
            console.error(err);
            if (window.cartManager && window.cartManager.showNotification) {
                window.cartManager.showNotification('Error al agregar al carrito', 'error');
            } else {
                showNotification('Error al agregar al carrito', 'error');
            }
        } finally {
            button.innerHTML = originalText;
            button.disabled = false;
        }
        return false;
    }

    function showNotification(message, type) {
        // Fallback para compatibilidad
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white font-medium ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        }`;
        notification.textContent = message;
        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 3000);
    }
    </script>
</div>
