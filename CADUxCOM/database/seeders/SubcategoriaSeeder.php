<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subcategoria;
use App\Models\Categoria;

class SubcategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = Categoria::pluck('Id_Categoria', 'Nombre');

        $subcategorias = [
            // Despensa
            ['Nombre' => 'Pastas, arroces y granos', 'Icono' => '🍝', 'imagen' => 'pasta.png', 'Categoria' => 'Despensa'],
            ['Nombre' => 'Enlatados y conservas', 'Icono' => '🥫', 'imagen' => 'enlatados.png', 'Categoria' => 'Despensa'],
            ['Nombre' => 'Harinas y mezclas', 'Icono' => '🌾', 'imagen' => 'harina.jpg', 'Categoria' => 'Despensa'],
            ['Nombre' => 'Salsas y condimentos', 'Icono' => '🧂', 'imagen' => 'condimentos.png', 'Categoria' => 'Despensa'],
            
            // Snacks y Dulces
            ['Nombre' => 'Galletas y mecato', 'Icono' => '🍪', 'imagen' => 'galletas.png', 'Categoria' => 'Snacks y Dulces'],
            ['Nombre' => 'Chocolates y confitería', 'Icono' => '🍫', 'imagen' => 'dulces.png', 'Categoria' => 'Snacks y Dulces'],
            ['Nombre' => 'Barras y granolas', 'Icono' => '🌰', 'imagen' => 'barra-granola.jpg', 'Categoria' => 'Snacks y Dulces'],
            
            // Bebidas
            ['Nombre' => 'Gaseosas y jugos', 'Icono' => '🥤', 'imagen' => 'agua.png', 'Categoria' => 'Bebidas'],
            ['Nombre' => 'Aguas saborizadas y energizantes', 'Icono' => '💧', 'imagen' => 'energizante.jpg', 'Categoria' => 'Bebidas'],
            ['Nombre' => 'Café, té e infusiones', 'Icono' => '☕', 'imagen' => 'cafe.png', 'Categoria' => 'Bebidas'],
            
            // Lácteos y Derivados
            ['Nombre' => 'Leches (líquida, en polvo, deslactosada)', 'Icono' => '🥛', 'imagen' => 'leche.png', 'Categoria' => 'Lácteos y Derivados'],
            ['Nombre' => 'Yogures y kumis', 'Icono' => '🍦', 'imagen' => 'yogurt.jpg', 'Categoria' => 'Lácteos y Derivados'],
            ['Nombre' => 'Quesos empacados', 'Icono' => '🧀', 'imagen' => 'queso.jpg', 'Categoria' => 'Lácteos y Derivados'],
            ['Nombre' => 'Mantequillas y margarinas', 'Icono' => '🧈', 'imagen' => 'mantequilla.png', 'Categoria' => 'Lácteos y Derivados'],
            
            // Congelados
            ['Nombre' => 'Comidas listas congeladas', 'Icono' => '🍽️', 'imagen' => 'comida-congelada.png', 'Categoria' => 'Congelados'],
            ['Nombre' => 'Verduras/papas congeladas', 'Icono' => '🥔', 'imagen' => 'verduras.png', 'Categoria' => 'Congelados'],
            ['Nombre' => 'Helados y postres', 'Icono' => '🍨', 'imagen' => 'helado.jpg', 'Categoria' => 'Congelados'],
            
            // Panadería
            ['Nombre' => 'Pan tajado empacado', 'Icono' => '🍞', 'imagen' => 'pan.png', 'Categoria' => 'Panadería'],
            ['Nombre' => 'Ponqués y repostería', 'Icono' => '🧁', 'imagen' => 'reposteria.png', 'Categoria' => 'Panadería'],
            ['Nombre' => 'Arepas empacadas', 'Icono' => '🫓', 'imagen' => 'arepa.png', 'Categoria' => 'Panadería'],
            
            // Cuidado Personal
            ['Nombre' => 'Shampoo y acondicionador', 'Icono' => '🧴', 'imagen' => 'shampoo.png', 'Categoria' => 'Cuidado Personal'],
            ['Nombre' => 'Cremas corporales', 'Icono' => '🧴', 'imagen' => 'crema-corporal.png', 'Categoria' => 'Cuidado Personal'],
            ['Nombre' => 'Desodorantes', 'Icono' => '🧴', 'imagen' => 'desodorante.png', 'Categoria' => 'Cuidado Personal'],
            ['Nombre' => 'Jabones líquidos', 'Icono' => '🧴', 'imagen' => 'jabon-liquido.png', 'Categoria' => 'Cuidado Personal'],
            ['Nombre' => 'Enjuagues bucales', 'Icono' => '🧴', 'imagen' => 'enjuaguebucal.png', 'Categoria' => 'Cuidado Personal'],
            ['Nombre' => 'Cremas dentales', 'Icono' => '🧴', 'imagen' => 'crema-dental.png', 'Categoria' => 'Cuidado Personal'],
        ];

        foreach ($subcategorias as $sub) {
            $subcategoria = Subcategoria::where('Nombre', $sub['Nombre'])->first();
            if ($subcategoria) {
                // Actualizar la imagen si existe
                $subcategoria->update(['imagen' => $sub['imagen']]);
            } else {
                // Crear nueva subcategoría si no existe
                Subcategoria::create([
                    'Nombre' => $sub['Nombre'],
                    'Icono' => $sub['Icono'],
                    'imagen' => $sub['imagen'],
                    'Id_Categoria' => $categorias[$sub['Categoria']],
                ]);
            }
        }
    }
}