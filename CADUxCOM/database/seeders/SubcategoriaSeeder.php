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
            ['Nombre' => 'Arepas', 'Icono' => '🫓', 'Categoria' => 'Harinas'],
            ['Nombre' => 'Bebidas', 'Icono' => '🧃', 'Categoria' => 'Lácteos'],
            ['Nombre' => 'Lácteos', 'Icono' => '🥛', 'Categoria' => 'Lácteos'],
            ['Nombre' => 'Huevos', 'Icono' => '🥚', 'Categoria' => 'Lácteos'],
            ['Nombre' => 'Pan Empacado', 'Icono' => '🍞', 'Categoria' => 'Harinas'],
            ['Nombre' => 'Cafés', 'Icono' => '☕', 'Categoria' => 'Granos'],
            ['Nombre' => 'Untables', 'Icono' => '🍯', 'Categoria' => 'Lácteos'],
            ['Nombre' => 'Aceites', 'Icono' => '🛢️', 'Categoria' => 'Granos'],
            ['Nombre' => 'Sopas y Cremas', 'Icono' => '🥣', 'Categoria' => 'Enlatados'],
            ['Nombre' => 'Carnes enlatadas', 'Icono' => '🥫', 'Categoria' => 'Enlatados'],
            ['Nombre' => 'Verduras Enlatadas', 'Icono' => '🫛', 'Categoria' => 'Enlatados'],
            ['Nombre' => 'Dulces', 'Icono' => '🍬', 'Categoria' => 'Congelados'],
            ['Nombre' => 'Galletas', 'Icono' => '🍪', 'Categoria' => 'Harinas'],
            ['Nombre' => 'Reposteria', 'Icono' => '🧁', 'Categoria' => 'Harinas'],
            ['Nombre' => 'Salsas y Aderezos', 'Icono' => '🧂', 'Categoria' => 'Granos'],
            ['Nombre' => 'Condimentos', 'Icono' => '🌶️', 'Categoria' => 'Granos'],
            ['Nombre' => 'Alimentos Refrigerados', 'Icono' => '🧊', 'Categoria' => 'Congelados'],
            ['Nombre' => 'Licores', 'Icono' => '🥃', 'Categoria' => 'Granos'],
            ['Nombre' => 'Pasabocas', 'Icono' => '🍿', 'Categoria' => 'Harinas'],
            // --- ¡AGREGA ESTAS LÍNEAS! ---
            ['Nombre' => 'Leche', 'Icono' => '🥛', 'Categoria' => 'Lácteos'], // Asegúrate de que 'Lácteos' sea una categoría existente
            ['Nombre' => 'Yogurt', 'Icono' => '🍦', 'Categoria' => 'Lácteos'], // 'Lácteos' también
            ['Nombre' => 'Arroz', 'Icono' => '🍚', 'Categoria' => 'Granos'], // 'Granos' también
            // -----------------------------
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