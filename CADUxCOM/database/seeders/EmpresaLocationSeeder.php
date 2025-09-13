<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Empresa;

class EmpresaLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Coordenadas reales de Garzón, Huila y alrededores
        $locations = [
            [
                'latitude' => 2.1962,
                'longitude' => -75.6278,
                'city' => 'Garzón',
                'state' => 'Huila',
                'postal_code' => '417010',
                'coverage_radius' => 15,
                'address' => 'Calle 10 # 20-30, Centro',
            ],
            [
                'latitude' => 2.2050,
                'longitude' => -75.6200,
                'city' => 'Garzón',
                'state' => 'Huila',
                'postal_code' => '417010',
                'coverage_radius' => 10,
                'address' => 'Carrera 5 # 15-25, Barrio San José',
            ],
            [
                'latitude' => 2.1900,
                'longitude' => -75.6350,
                'city' => 'Garzón',
                'state' => 'Huila',
                'postal_code' => '417010',
                'coverage_radius' => 12,
                'address' => 'Calle 8 # 12-40, Barrio El Centro',
            ],
            [
                'latitude' => 2.2100,
                'longitude' => -75.6100,
                'city' => 'Garzón',
                'state' => 'Huila',
                'postal_code' => '417010',
                'coverage_radius' => 8,
                'address' => 'Carrera 3 # 18-15, Barrio La Esperanza',
            ],
            [
                'latitude' => 2.1800,
                'longitude' => -75.6400,
                'city' => 'Garzón',
                'state' => 'Huila',
                'postal_code' => '417010',
                'coverage_radius' => 20,
                'address' => 'Vía Principal, Zona Rural',
            ],
            [
                'latitude' => 2.2000,
                'longitude' => -75.6150,
                'city' => 'Garzón',
                'state' => 'Huila',
                'postal_code' => '417010',
                'coverage_radius' => 14,
                'address' => 'Calle 15 # 5-20, Barrio Los Laureles',
            ],
            [
                'latitude' => 2.1850,
                'longitude' => -75.6250,
                'city' => 'Garzón',
                'state' => 'Huila',
                'postal_code' => '417010',
                'coverage_radius' => 11,
                'address' => 'Carrera 8 # 10-35, Barrio El Progreso',
            ],
        ];

        $empresas = Empresa::all();

        if ($empresas->isEmpty()) {
            $this->command->warn('No hay empresas en la base de datos. Ejecuta primero EmpresaSeeder.');
            return;
        }

        foreach ($empresas as $index => $empresa) {
            $location = $locations[$index % count($locations)];
            
            $empresa->update([
                'latitude' => $location['latitude'] + (rand(-30, 30) / 10000), // Pequeña variación
                'longitude' => $location['longitude'] + (rand(-30, 30) / 10000),
                'city' => $location['city'],
                'state' => $location['state'],
                'postal_code' => $location['postal_code'],
                'country' => 'Colombia',
                'coverage_radius' => $location['coverage_radius'],
                'location_verified' => true,
                'location_updated_at' => now(),
                'Direccion' => $location['address'], // Actualizar dirección con la real
            ]);

            $this->command->info("Ubicación agregada a empresa: {$empresa->Nombre} - {$location['address']}");
        }

        $this->command->info('Ubicaciones de empresas creadas exitosamente.');
    }
}