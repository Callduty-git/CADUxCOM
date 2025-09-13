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
                        <a href="{{ route('productos.user.show', $producto->Id_Producto) }}" class="btn btn-primary">Ver detalles</a>
                        <form method="POST" action="{{ route('cart.add') }}" class="btn-cart-form" onsubmit="return addToCart(event, {{ $producto->Id_Producto }})">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $producto->Id_Producto }}">
                            <button type="submit" class="btn btn-secondary">Agregar</button>
                        </form>
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

    <script>
    async function addToCart(e, id){
        e.preventDefault();
        const form = e.target;
        const data = new FormData(form);
        const resp = await fetch(form.action, {method:'POST', headers:{'X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':data.get('_token')}, body:data});
        try {
            const json = await resp.json();
            const badge = document.querySelector('.cart-badge');
            if (json && json.count !== undefined && badge){ badge.textContent = json.count; }
        } catch (err) {}
        return false;
    }

    function toggleFavorites(productId) {
        // Verificar si el usuario está autenticado
        @guest
            // Si no está autenticado, redirigir al login
            window.location.href = '{{ route("login") }}';
            return;
        @endguest

        fetch('{{ route("wishlist.add") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: 1
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Producto agregado a tus favoritos', 'success');
                updateWishlistCount();
                // Cambiar el icono a favorito lleno
                const btn = document.getElementById(`favorites-btn-${productId}`);
                if (btn) {
                    const img = btn.querySelector('img');
                    img.src = '{{ asset("images/heart-filled-icon.svg") }}';
                    btn.title = 'Eliminar de favoritos';
                }
            } else if (data.redirect) {
                // Redirigir al login si no está autenticado
                window.location.href = data.redirect;
            } else {
                showNotification(data.error || 'Error al agregar a favoritos', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error al agregar a favoritos', 'error');
        });
    }

    function updateWishlistCount() {
        @auth
            fetch('{{ route("wishlist.count") }}')
            .then(response => response.json())
            .then(data => {
                const wishlistCount = document.getElementById('wishlist-count');
                if (wishlistCount) {
                    wishlistCount.textContent = data.count;
                }
            });
        @endauth
    }

    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-md shadow-lg max-w-sm ${
            type === 'success' ? 'bg-green-100 text-green-800 border border-green-200' :
            type === 'error' ? 'bg-red-100 text-red-800 border border-red-200' :
            'bg-blue-100 text-blue-800 border border-blue-200'
        }`;
        
        notification.innerHTML = `
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ${type === 'success' ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>' :
                      '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>'}
                </svg>
                <span class="text-sm font-medium">${message}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 5000);
    }
    </script>
</div>