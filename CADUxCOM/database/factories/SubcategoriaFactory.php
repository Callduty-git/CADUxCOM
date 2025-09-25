<?php

namespace Database\Factories;

use App\Models\Subcategoria;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubcategoriaFactory extends Factory
{
    protected $model = Subcategoria::class;

    public function definition()
    {
        return [
            'Nombre' => $this->faker->unique()->word(),
            'Icono' => null,
            'Id_Categoria' => 1, // Se ajusta en el test
        ];
    }
}
