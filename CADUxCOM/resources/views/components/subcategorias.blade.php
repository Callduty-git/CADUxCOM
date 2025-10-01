<div class="subcategorias-section">
    <div class="subcategorias-container">
        @php
            $imageMap = [
                'Arepas' => 'arepa.png',
                'Bebidas' => 'agua.png',
                'Lácteos' => 'leche.png',
                'Huevos' => 'huevos.png',
                'Pan Empacado' => 'pan.png',
                'Cafés' => 'cafe.png',
                'Untables' => 'mermelada.png',
                'Aceites' => 'aceite.png',
                'Sopas y Cremas' => 'sopa.png',
                'Carnes enlatadas' => 'enlatados.png',
                'Verduras Enlatadas' => 'verduras.png',
                'Dulces' => 'dulces.png',
                'Galletas' => 'galletas.png',
                'Pastas' => 'pasta.png',
                'Reposteria' => 'reposteria.png',
                'Salsas y Aderezos' => 'salsas.png',
                'Condimentos' => 'condimentos.png',
                'Alimentos Refrigerados' => 'refrigerados.png',
                'Licores' => 'licor.png',
                'Pasabocas' => 'pasabocas.png'
            ];
        @endphp
        
        @foreach($subcategorias as $subcategoria)
            @php
                $imageName = $imageMap[$subcategoria->Nombre] ?? 'default.png';
            @endphp
            <a href="{{ route('productos.public.index', ['subcategoria' => $subcategoria->Id_Subcategoria]) }}" class="subcategoria-item">
                <img src="{{ asset('images/subcategorias/' . $imageName) }}" alt="{{ $subcategoria->Nombre }}">
                <p>{{ $subcategoria->Nombre }}</p>
            </a>
        @endforeach
    </div>
</div>
