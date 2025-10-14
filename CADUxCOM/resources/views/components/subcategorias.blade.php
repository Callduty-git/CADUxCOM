@php
    use App\Models\Subcategoria;
    $subcategorias = Subcategoria::all();
@endphp

<div class="subcategorias-section">
    <div class="subcategorias-container">
        @foreach($subcategorias as $subcategoria)
            <a href="{{ route('productos.public.index', ['subcategoria' => $subcategoria->Id_Subcategoria]) }}" class="subcategoria-item">
                @if($subcategoria->imagen && file_exists(public_path('images/subcategorias/' . $subcategoria->imagen)))
                    <img src="{{ asset('images/subcategorias/' . $subcategoria->imagen) }}" alt="{{ $subcategoria->Nombre }}">
                @else
                    <div class="subcategoria-placeholder">
                        <span class="subcategoria-icon">{{ $subcategoria->Icono }}</span>
                    </div>
                @endif
                <p>{{ $subcategoria->Nombre }}</p>
            </a>
        @endforeach
    </div>
</div>
