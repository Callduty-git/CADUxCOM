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
                        @if($producto->Cantidad > 0)
                            <button type="button" class="btn btn-secondary" 
                                    onclick="addToCart({{ $producto->Id_Producto }})"
                                    id="add-cart-btn-{{ $producto->Id_Producto }}">
                                <span class="btn-text">Agregar</span>
                            </button>
                        @else
                            <button type="button" class="btn btn-secondary" disabled>
                                Agotado
                            </button>
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
</div>