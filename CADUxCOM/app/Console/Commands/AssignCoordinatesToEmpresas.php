<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Empresa;

class AssignCoordinatesToEmpresas extends Command
{
    protected $signature = 'empresas:assign-coordinates';
    protected $description = 'Asignar coordenadas a las empresas existentes';

    public function handle()
    {
        $this->info('Asignando coordenadas a las empresas...');
        
        // Coordenadas aproximadas para diferentes municipios del Huila
        $coordinates = [
            'Neiva' => ['lat' => 2.9273, 'lng' => -75.2819],
            'Pitalito' => ['lat' => 1.8536, 'lng' => -76.0508],
            'Garzón' => ['lat' => 2.1961, 'lng' => -75.6277],
            'La Plata' => ['lat' => 2.3889, 'lng' => -75.8889],
            'San Agustín' => ['lat' => 1.8833, 'lng' => -76.2667],
            'Timaná' => ['lat' => 1.9667, 'lng' => -75.9167],
            'Rivera' => ['lat' => 2.7667, 'lng' => -75.2500],
            'Campoalegre' => ['lat' => 2.6833, 'lng' => -75.3167],
            'Palermo' => ['lat' => 2.8833, 'lng' => -75.4500],
            'Algeciras' => ['lat' => 2.5333, 'lng' => -75.2167]
        ];
        
        $empresas = Empresa::whereNull('latitude')->orWhereNull('longitude')->get();
        
        if ($empresas->isEmpty()) {
            $this->info('No hay empresas sin coordenadas.');
            return;
        }
        
        $this->info("Encontradas {$empresas->count()} empresas sin coordenadas:");
        
        foreach ($empresas as $empresa) {
            $this->line("- {$empresa->Nombre} ({$empresa->Municipio})");
            
            $municipio = $empresa->Municipio;
            
            if (isset($coordinates[$municipio])) {
                $lat = $coordinates[$municipio]['lat'];
                $lng = $coordinates[$municipio]['lng'];
                
                // Agregar una pequeña variación aleatoria para evitar que todas estén en el mismo punto
                $lat += (rand(-50, 50) / 10000); // ±0.005 grados
                $lng += (rand(-50, 50) / 10000); // ±0.005 grados
                
                $empresa->update([
                    'latitude' => $lat,
                    'longitude' => $lng
                ]);
                
                $this->info("  ✓ Coordenadas asignadas: ({$lat}, {$lng})");
            } else {
                // Coordenadas por defecto para Neiva si no se encuentra el municipio
                $lat = 2.9273 + (rand(-100, 100) / 10000);
                $lng = -75.2819 + (rand(-100, 100) / 10000);
                
                $empresa->update([
                    'latitude' => $lat,
                    'longitude' => $lng
                ]);
                
                $this->warn("  ⚠ Municipio no encontrado, usando coordenadas de Neiva: ({$lat}, {$lng})");
            }
        }
        
        $this->info('✅ Coordenadas asignadas exitosamente!');
        
        // Verificar el resultado
        $empresasConCoordenadas = Empresa::withValidCoordinates()->count();
        $this->info("Total de empresas con coordenadas válidas: {$empresasConCoordenadas}");
    }
}