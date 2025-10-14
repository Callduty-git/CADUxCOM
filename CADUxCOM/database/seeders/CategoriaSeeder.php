<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            ['Nombre' => 'Despensa', 'Icono' => '🥫'],
            ['Nombre' => 'Snacks y Dulces', 'Icono' => '🍫'],
            ['Nombre' => 'Bebidas', 'Icono' => '🧃'],
            ['Nombre' => 'Lácteos y Derivados', 'Icono' => '🧀'],
            ['Nombre' => 'Congelados', 'Icono' => '❄️'],
            ['Nombre' => 'Panadería', 'Icono' => '🍞'],
            ['Nombre' => 'Cuidado Personal', 'Icono' => '🧴'],
        ];

        foreach ($categorias as $categoria) {
            Categoria::firstOrCreate(
                ['Nombre' => $categoria['Nombre']],
                ['Icono' => $categoria['Icono']]
            );
        }
    }
}