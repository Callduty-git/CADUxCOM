<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Empresa;
use Illuminate\Support\Facades\Hash;

class EmpresaSeeder extends Seeder
{
    public function run(): void
    {
        Empresa::firstOrCreate(
            ['email' => 'justoybueno@example.com'],
            [
                'Nombre' => 'Justo y bueno',
                'Foto' => null,
                'Direccion' => 'Calle 10 # 20-30',
                'Municipio' => 'Garzón',
                'Ubicacion' => 'Centro',
                'Contacto' => '3001234567',
                'NIT' => '900123456-7',
                'Certificado_Camara_de_comercio' => null,
                'password' => Hash::make('password'),
            ]
        );
    }
}