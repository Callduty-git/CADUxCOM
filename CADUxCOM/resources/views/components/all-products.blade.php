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
    </script>
</div>