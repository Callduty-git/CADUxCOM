<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Categoria;
use App\Models\Subcategoria;
use App\Models\Producto;

class VerifyCategoriesCommand extends Command
{
    protected $signature = 'categories:verify';
    protected $description = 'Verifica que las categorías y subcategorías estén funcionando correctamente';

    public function handle()
    {
        $this->info('🔍 Verificando estructura de categorías...');

        // Verificar categorías
        $categorias = Categoria::all();
        $this->info("📊 Total de categorías: {$categorias->count()}");

        // Verificar subcategorías
        $subcategorias = Subcategoria::all();
        $this->info("📊 Total de subcategorías: {$subcategorias->count()}");

        // Verificar productos
        $productos = Producto::all();
        $this->info("📊 Total de productos: {$productos->count()}");

        // Verificar relaciones
        $this->info('');
        $this->info('🔗 Verificando relaciones...');

        $invalidSubcategorias = Subcategoria::whereNotIn('Id_Categoria', function($query) {
            $query->select('Id_Categoria')->from('categorias');
        })->count();

        if ($invalidSubcategorias > 0) {
            $this->error("❌ {$invalidSubcategorias} subcategorías tienen categorías inválidas");
        } else {
            $this->info('✅ Todas las subcategorías tienen categorías válidas');
        }

        $invalidProductos = Producto::whereNotIn('Id_Subcategoria', function($query) {
            $query->select('Id_Subcategoria')->from('subcategorias');
        })->count();

        if ($invalidProductos > 0) {
            $this->error("❌ {$invalidProductos} productos tienen subcategorías inválidas");
        } else {
            $this->info('✅ Todos los productos tienen subcategorías válidas');
        }

        // Mostrar estructura completa
        $this->showCompleteStructure();

        $this->info('');
        $this->info('✅ ¡Verificación completada!');
    }

    private function showCompleteStructure()
    {
        $this->info('');
        $this->info('📋 ESTRUCTURA COMPLETA:');
        $this->info('======================');

        $categorias = Categoria::with(['subcategorias.productos'])->get();

        foreach ($categorias as $categoria) {
            $totalProductos = $categoria->subcategorias->sum(function($sub) {
                return $sub->productos->count();
            });

            $this->info("{$categoria->Icono} {$categoria->Nombre} ({$totalProductos} productos)");

            foreach ($categoria->subcategorias as $subcategoria) {
                $productCount = $subcategoria->productos->count();
                $this->line("  └─ {$subcategoria->Icono} {$subcategoria->Nombre} ({$productCount} productos)");

                // Mostrar algunos productos de ejemplo
                if ($productCount > 0) {
                    $productos = $subcategoria->productos->take(3);
                    foreach ($productos as $producto) {
                        $this->line("      • {$producto->Nombre} - {$producto->Marca}");
                    }
                    if ($productCount > 3) {
                        $this->line("      ... y " . ($productCount - 3) . " más");
                    }
                }
            }
        }
    }
}

