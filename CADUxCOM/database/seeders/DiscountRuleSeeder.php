<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DiscountRule;
use App\Models\Empresa;

class DiscountRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener todas las empresas
        $empresas = Empresa::all();

        if ($empresas->isEmpty()) {
            $this->command->warn('No hay empresas en la base de datos. Ejecuta primero EmpresaSeeder.');
            return;
        }

        foreach ($empresas as $empresa) {
            // Crear reglas de descuento por defecto para cada empresa
            DiscountRule::createDefaultRules($empresa->Id_Empresa);
            
            $this->command->info("Reglas de descuento creadas para empresa: {$empresa->Nombre}");
        }

        $this->command->info('Reglas de descuento progresivo creadas exitosamente.');
    }
}