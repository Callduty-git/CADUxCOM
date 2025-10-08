<x-app-layout>
    <div class="home-container">
        <!-- Productos -->
        <x-all-products :productos="$productos" :categorias="$categorias" :subcategorias="$subcategorias" />
    </div>
</x-app-layout>