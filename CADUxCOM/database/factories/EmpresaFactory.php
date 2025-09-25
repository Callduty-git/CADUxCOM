<?php

namespace Database\Factories;

use App\Models\Empresa;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmpresaFactory extends Factory
{
    protected $model = Empresa::class;

    public function definition()
    {
        return [
            'Nombre' => $this->faker->company(),
            'Direccion' => $this->faker->address(),
            'Municipio' => $this->faker->city(),
            'Ubicacion' => $this->faker->address(),
            'Contacto' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'NIT' => $this->faker->unique()->numerify('#########'),
            'Certificado_Camara_de_comercio' => 'certificado.pdf',
            'Foto' => null,
            'password' => bcrypt('password'),
        ];
    }
}
