<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Categoria;
use App\Models\Subcategoria;
use App\Models\Producto;

class UpdateCategoriesCommand extends Command
{
    protected $signature = 'categories:update';
    protected $description = 'Actualiza las categorías y subcategorías del sistema';

    public function handle()
    {
        $this->info('🔄 Actualizando categorías y subcategorías...');

        // Paso 1: Limpiar datos antiguos
        $this->info('🧹 Limpiando datos antiguos...');
        
        // Primero actualizar productos que tienen subcategorías inexistentes
        $this->updateOrphanProducts();
        
        // Eliminar productos primero para evitar problemas con foreign keys
        $this->info('🗑️ Eliminando productos existentes...');
        Producto::query()->delete();
        $this->info('✅ Productos eliminados');
        
        // Eliminar subcategorías antiguas
        Subcategoria::query()->delete();
        $this->info('✅ Subcategorías antiguas eliminadas');
        
        // Eliminar categorías antiguas
        Categoria::query()->delete();
        $this->info('✅ Categorías antiguas eliminadas');

        // Paso 2: Ejecutar seeders
        $this->info('🌱 Ejecutando seeders...');
        $this->call('db:seed', ['--class' => 'CategoriaSeeder']);
        $this->call('db:seed', ['--class' => 'SubcategoriaSeeder']);

        // Paso 3: Verificar integridad
        $this->info('🔍 Verificando integridad...');
        $this->verifyIntegrity();

        $this->info('✅ ¡Actualización completada exitosamente!');
        
        // Mostrar resumen
        $this->showSummary();
    }

    private function updateOrphanProducts()
    {
        $this->info('🔗 Actualizando productos huérfanos...');
        
        // Obtener productos que tienen subcategorías que van a ser eliminadas
        $orphanProducts = Producto::whereNotIn('Id_Subcategoria', function($query) {
            $query->select('Id_Subcategoria')->from('subcategorias');
        })->get();

        if ($orphanProducts->count() > 0) {
            $this->warn("⚠️  Encontrados {$orphanProducts->count()} productos con subcategorías inexistentes");
            
            // Asignar a la primera subcategoría disponible (será la primera de Despensa)
            $firstSubcategory = Subcategoria::first();
            
            if ($firstSubcategory) {
                foreach ($orphanProducts as $product) {
                    $product->update(['Id_Subcategoria' => $firstSubcategory->Id_Subcategoria]);
                }
                $this->info("✅ {$orphanProducts->count()} productos actualizados a subcategoría: {$firstSubcategory->Nombre}");
            }
        } else {
            $this->info('✅ No se encontraron productos huérfanos');
        }
    }

    private function verifyIntegrity()
    {
        $categoriesCount = Categoria::count();
        $subcategoriesCount = Subcategoria::count();
        $productsCount = Producto::count();
        
        $this->info("📊 Categorías: {$categoriesCount}");
        $this->info("📊 Subcategorías: {$subcategoriesCount}");
        $this->info("📊 Productos: {$productsCount}");
        
        // Verificar que todas las subcategorías tienen categorías válidas
        $invalidSubcategories = Subcategoria::whereNotIn('Id_Categoria', function($query) {
            $query->select('Id_Categoria')->from('categorias');
        })->count();
        
        if ($invalidSubcategories > 0) {
            $this->error("❌ {$invalidSubcategories} subcategorías tienen categorías inválidas");
        } else {
            $this->info('✅ Todas las subcategorías tienen categorías válidas');
        }
    }

    private function showSummary()
    {
        $this->info('');
        $this->info('📋 RESUMEN DE CATEGORÍAS:');
        $this->info('========================');
        
        $categorias = Categoria::with('subcategorias')->get();
        
        foreach ($categorias as $categoria) {
            $this->info("{$categoria->Icono} {$categoria->Nombre} ({$categoria->subcategorias->count()} subcategorías)");
            
            foreach ($categoria->subcategorias as $subcategoria) {
                $this->line("  └─ {$subcategoria->Icono} {$subcategoria->Nombre}");
            }
        }
    }
}
