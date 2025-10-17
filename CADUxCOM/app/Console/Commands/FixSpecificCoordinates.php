<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Empresa;

class FixSpecificCoordinates extends Command
{
    protected $signature = 'empresas:fix-coordinates';
    protected $description = 'Corregir coordenadas específicas para municipios';

    public function handle()
    {
        $this->info('Corrigiendo coordenadas específicas...');
        
        // Corregir SurtiLaura Garzon para Pitalito
        $empresa1 = Empresa::where('Nombre', 'SurtiLaura Garzon')->first();
        if ($empresa1) {
            $empresa1->update([
                'latitude' => 1.8536,
                'longitude' => -76.0508
            ]);
            $this->info('✓ SurtiLaura Garzon actualizada con coordenadas de Pitalito');
        }
        
        // Corregir chochorico para Guadalupe
        $empresa2 = Empresa::where('Nombre', 'chochorico')->first();
        if ($empresa2) {
            $empresa2->update([
                'latitude' => 2.3500,
                'longitude' => -75.7500
            ]);
            $this->info('✓ chochorico actualizada con coordenadas de Guadalupe');
        }
        
        // Corregir pepeganga que no se actualizó
        $empresa3 = Empresa::where('Nombre', 'pepeganga')->first();
        if ($empresa3) {
            $empresa3->update([
                'latitude' => 2.1961,
                'longitude' => -75.6277
            ]);
            $this->info('✓ pepeganga actualizada con coordenadas de Garzón');
        }
        
        $this->info('✅ Coordenadas corregidas exitosamente!');
        
        // Mostrar todas las empresas con sus coordenadas
        $empresas = Empresa::all(['Nombre', 'Municipio', 'latitude', 'longitude']);
        $this->info("\nEmpresas con coordenadas:");
        foreach ($empresas as $empresa) {
            $this->line("- {$empresa->Nombre} ({$empresa->Municipio}): {$empresa->latitude}, {$empresa->longitude}");
        }
    }
}