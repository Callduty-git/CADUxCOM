<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckMapsConfig extends Command
{
    protected $signature = 'maps:check-config';
    protected $description = 'Verificar la configuración de Google Maps';

    public function handle()
    {
        $apiKey = config('services.google.maps_api_key');
        
        if (empty($apiKey) || $apiKey === 'YOUR_API_KEY') {
            $this->error('❌ Google Maps API Key no está configurada correctamente');
            $this->line('Agrega GOOGLE_MAPS_API_KEY=tu_api_key_aqui a tu archivo .env');
            return 1;
        }
        
        $this->info('✅ Google Maps API Key configurada correctamente');
        $this->line('API Key: ' . substr($apiKey, 0, 10) . '...');
        
        // Verificar que la vista del mapa existe
        $mapViewPath = resource_path('views/geolocation/map.blade.php');
        if (file_exists($mapViewPath)) {
            $this->info('✅ Vista del mapa encontrada');
        } else {
            $this->error('❌ Vista del mapa no encontrada');
            return 1;
        }
        
        // Verificar que el CSS existe
        $cssPath = public_path('css/map.css');
        if (file_exists($cssPath)) {
            $this->info('✅ Estilos del mapa encontrados');
        } else {
            $this->error('❌ Estilos del mapa no encontrados');
            return 1;
        }
        
        $this->info('🎉 Configuración del mapa completa y lista para usar');
        return 0;
    }
}






