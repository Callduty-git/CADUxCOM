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
            ['Nombre' => 'Pastas, arroces y granos', 'Icono' => '🍝', 'Categoria' => 'Despensa'],
            ['Nombre' => 'Enlatados y conservas', 'Icono' => '🥫', 'Categoria' => 'Despensa'],
            ['Nombre' => 'Harinas y mezclas', 'Icono' => '🌾', 'Categoria' => 'Despensa'],
            ['Nombre' => 'Salsas y condimentos', 'Icono' => '🧂', 'Categoria' => 'Despensa'],
            
            // Snacks y Dulces
            ['Nombre' => 'Galletas y mecato', 'Icono' => '🍪', 'Categoria' => 'Snacks y Dulces'],
            ['Nombre' => 'Chocolates y confitería', 'Icono' => '🍫', 'Categoria' => 'Snacks y Dulces'],
            ['Nombre' => 'Barras y granolas', 'Icono' => '🌰', 'Categoria' => 'Snacks y Dulces'],
            
            // Bebidas
            ['Nombre' => 'Gaseosas y jugos', 'Icono' => '🥤', 'Categoria' => 'Bebidas'],
            ['Nombre' => 'Aguas saborizadas y energizantes', 'Icono' => '💧', 'Categoria' => 'Bebidas'],
            ['Nombre' => 'Café, té e infusiones', 'Icono' => '☕', 'Categoria' => 'Bebidas'],
            
            // Lácteos y Derivados
            ['Nombre' => 'Leches (líquida, en polvo, deslactosada)', 'Icono' => '🥛', 'Categoria' => 'Lácteos y Derivados'],
            ['Nombre' => 'Yogures y kumis', 'Icono' => '🍦', 'Categoria' => 'Lácteos y Derivados'],
            ['Nombre' => 'Quesos empacados', 'Icono' => '🧀', 'Categoria' => 'Lácteos y Derivados'],
            ['Nombre' => 'Mantequillas y margarinas', 'Icono' => '🧈', 'Categoria' => 'Lácteos y Derivados'],
            
            // Congelados
            ['Nombre' => 'Comidas listas congeladas', 'Icono' => '🍽️', 'Categoria' => 'Congelados'],
            ['Nombre' => 'Verduras/papas congeladas', 'Icono' => '🥔', 'Categoria' => 'Congelados'],
            ['Nombre' => 'Helados y postres', 'Icono' => '🍨', 'Categoria' => 'Congelados'],
            
            // Panadería
            ['Nombre' => 'Pan tajado empacado', 'Icono' => '🍞', 'Categoria' => 'Panadería'],
            ['Nombre' => 'Ponqués y repostería', 'Icono' => '🧁', 'Categoria' => 'Panadería'],
            ['Nombre' => 'Arepas empacadas', 'Icono' => '🫓', 'Categoria' => 'Panadería'],
            
            // Cuidado Personal
            ['Nombre' => 'Shampoo y acondicionador', 'Icono' => '🧴', 'Categoria' => 'Cuidado Personal'],
            ['Nombre' => 'Cremas corporales', 'Icono' => '🧴', 'Categoria' => 'Cuidado Personal'],
            ['Nombre' => 'Desodorantes', 'Icono' => '🧴', 'Categoria' => 'Cuidado Personal'],
            ['Nombre' => 'Jabones líquidos', 'Icono' => '🧴', 'Categoria' => 'Cuidado Personal'],
            ['Nombre' => 'Enjuagues bucales', 'Icono' => '🧴', 'Categoria' => 'Cuidado Personal'],
            ['Nombre' => 'Cremas dentales', 'Icono' => '🧴', 'Categoria' => 'Cuidado Personal'],
        ];

        foreach ($subcategorias as $sub) {
            Subcategoria::create([
                'Nombre' => $sub['Nombre'],
                'Icono' => $sub['Icono'],
                'Id_Categoria' => $categorias[$sub['Categoria']],
            ]);
        }
    }
}