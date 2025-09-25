<?php

namespace Database\Factories;

use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductoFactory extends Factory
{
    protected $model = Producto::class;

    public function definition()
    {
        return [
            'Id_Empresa' => 1, // Ajusta según tu test, o crea una empresa de prueba
            'Id_Subcategoria' => 1, // Ajusta según tu test, o crea una subcategoría de prueba
            'Nombre' => $this->faker->words(3, true),
            'Marca' => $this->faker->word(),
            'Fecha_Caducidad' => $this->faker->date(),
            'Cantidad' => $this->faker->numberBetween(1, 100),
            'Foto' => null,
            'Descripcion' => $this->faker->sentence(),
            'Precio' => $this->faker->randomFloat(2, 1, 100),
            'PrecioOriginal' => $this->faker->randomFloat(2, 100, 200),
            'Tipo' => $this->faker->word(),
            'Codigo' => $this->faker->unique()->ean13(),
        ];
    }
}
