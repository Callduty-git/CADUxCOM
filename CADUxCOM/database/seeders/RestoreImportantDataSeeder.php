<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DiscountRule;
use App\Models\Empresa;

class RestoreImportantDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🔄 Restaurando datos importantes del sistema...');
        
        // Verificar si hay empresas para crear reglas de descuento
        $empresas = Empresa::all();
        
        if ($empresas->isEmpty()) {
            $this->command->warn('⚠️  No hay empresas en la base de datos. Las reglas de descuento se crearán cuando haya empresas.');
            return;
        }
        
        // Crear reglas de descuento por defecto para cada empresa
        foreach ($empresas as $empresa) {
            DiscountRule::createDefaultRules($empresa->Id_Empresa);
            $this->command->info("✅ Reglas de descuento creadas para empresa: {$empresa->Nombre}");
        }
        
        $this->command->info('✅ Datos importantes restaurados exitosamente');
        $this->command->info('📊 Reglas de descuento: ' . DiscountRule::count());
    }
}


