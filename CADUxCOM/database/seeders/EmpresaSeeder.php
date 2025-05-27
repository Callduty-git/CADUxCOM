<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Empresa;
use Illuminate\Support\Facades\Hash;

class EmpresaSeeder extends Seeder
{
    public function run(): void
    {
        Empresa::create([
            'Nombre' => 'Empresa Ejemplo',
            'Foto' => null,
            'Direccion' => 'Calle 123 #45-67',
            'Municipio' => 'Ciudad Demo',
            'Ubicacion' => null,
            'Contacto' => '3001234567',
            'email' => 'empresa@ejemplo.com',
            'NIT' => '900123456-7',
            'Certificado_Camara_de_comercio' => null,
            'password' => Hash::make('password'),
        ]);
    }
}