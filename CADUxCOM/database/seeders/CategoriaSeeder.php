<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            ['Nombre' => 'Lácteos', 'Icono' => '🥛'],
            ['Nombre' => 'Granos', 'Icono' => '🌰'],
            ['Nombre' => 'Harinas', 'Icono' => '🧂'],
            ['Nombre' => 'Congelados', 'Icono' => '❄️'],
            ['Nombre' => 'Enlatados', 'Icono' => '🥫'],
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }
    }
}