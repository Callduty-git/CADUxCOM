<div class="subcategorias-section">
    <div class="subcategorias-container">
        @php
            $imageMap = [
                // Despensa
                'Pastas, arroces y granos' => 'pasta.png',
                'Enlatados y conservas' => 'enlatados.png',
                'Harinas y mezclas' => 'harina.png',
                'Salsas y condimentos' => 'salsas.png',
                
                // Snacks y Dulces
                'Galletas y mecato' => 'galletas.png',
                'Chocolates y confitería' => 'dulces.png',
                'Barras y granolas' => 'granola.png',
                
                // Bebidas
                'Gaseosas y jugos' => 'agua.png',
                'Aguas saborizadas y energizantes' => 'energizante.png',
                'Café, té e infusiones' => 'cafe.png',
                
                // Lácteos y Derivados
                'Leches (líquida, en polvo, deslactosada)' => 'leche.png',
                'Yogures y kumis' => 'yogurt.png',
                'Quesos empacados' => 'queso.png',
                'Mantequillas y margarinas' => 'mantequilla.png',
                
                // Congelados
                'Comidas listas congeladas' => 'congelados.png',
                'Verduras/papas congeladas' => 'verduras.png',
                'Helados y postres' => 'helado.png',
                
                // Panadería
                'Pan tajado empacado' => 'pan.png',
                'Ponqués y repostería' => 'reposteria.png',
                'Arepas empacadas' => 'arepa.png',
                
                // Cuidado Personal
                'Shampoo y acondicionador' => 'shampoo.png',
                'Cremas corporales' => 'crema.png',
                'Desodorantes' => 'desodorante.png',
                'Jabones líquidos' => 'jabon.png',
                'Enjuagues bucales' => 'enjuague.png',
                'Cremas dentales' => 'pasta-dental.png',
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
