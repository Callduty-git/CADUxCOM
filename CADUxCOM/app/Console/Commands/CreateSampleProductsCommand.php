<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Producto;
use App\Models\Empresa;
use App\Models\Subcategoria;

class CreateSampleProductsCommand extends Command
{
    protected $signature = 'products:create-sample';
    protected $description = 'Crea productos de ejemplo para probar las nuevas categorías';

    public function handle()
    {
        $this->info('🛍️ Creando productos de ejemplo...');

        // Obtener una empresa de ejemplo
        $empresa = Empresa::first();
        if (!$empresa) {
            $this->error('❌ No hay empresas disponibles. Ejecuta primero los seeders de empresas.');
            return;
        }

        // Obtener subcategorías
        $subcategorias = Subcategoria::all()->keyBy('Nombre');

        $productos = [
            // Despensa
            [
                'Nombre' => 'Arroz Blanco 500g',
                'Marca' => 'Diana',
                'Fecha_Caducidad' => now()->addMonths(12),
                'Cantidad' => 50,
                'Descripcion' => 'Arroz blanco de alta calidad',
                'Precio' => 2500,
                'PrecioOriginal' => 3000,
                'Tipo' => 'Granos',
                'Codigo' => 'ARR001',
                'Id_Empresa' => $empresa->Id_Empresa,
                'Id_Subcategoria' => $subcategorias['Pastas, arroces y granos']->Id_Subcategoria,
            ],
            [
                'Nombre' => 'Pasta Espagueti 500g',
                'Marca' => 'Barilla',
                'Fecha_Caducidad' => now()->addMonths(18),
                'Cantidad' => 30,
                'Descripcion' => 'Pasta italiana de trigo duro',
                'Precio' => 4500,
                'PrecioOriginal' => 5000,
                'Tipo' => 'Pasta',
                'Codigo' => 'PAS001',
                'Id_Empresa' => $empresa->Id_Empresa,
                'Id_Subcategoria' => $subcategorias['Pastas, arroces y granos']->Id_Subcategoria,
            ],
            [
                'Nombre' => 'Atún en Lata 180g',
                'Marca' => 'Van Camp\'s',
                'Fecha_Caducidad' => now()->addMonths(24),
                'Cantidad' => 25,
                'Descripcion' => 'Atún en agua, rico en proteínas',
                'Precio' => 3500,
                'PrecioOriginal' => 4000,
                'Tipo' => 'Enlatado',
                'Codigo' => 'ATU001',
                'Id_Empresa' => $empresa->Id_Empresa,
                'Id_Subcategoria' => $subcategorias['Enlatados y conservas']->Id_Subcategoria,
            ],

            // Snacks y Dulces
            [
                'Nombre' => 'Galletas Oreo 150g',
                'Marca' => 'Oreo',
                'Fecha_Caducidad' => now()->addMonths(8),
                'Cantidad' => 40,
                'Descripcion' => 'Galletas de chocolate rellenas',
                'Precio' => 2800,
                'PrecioOriginal' => 3200,
                'Tipo' => 'Galletas',
                'Codigo' => 'GAL001',
                'Id_Empresa' => $empresa->Id_Empresa,
                'Id_Subcategoria' => $subcategorias['Galletas y mecato']->Id_Subcategoria,
            ],
            [
                'Nombre' => 'Chocolate Hershey\'s 100g',
                'Marca' => 'Hershey\'s',
                'Fecha_Caducidad' => now()->addMonths(6),
                'Cantidad' => 35,
                'Descripcion' => 'Chocolate con leche',
                'Precio' => 3200,
                'PrecioOriginal' => 3800,
                'Tipo' => 'Chocolate',
                'Codigo' => 'CHO001',
                'Id_Empresa' => $empresa->Id_Empresa,
                'Id_Subcategoria' => $subcategorias['Chocolates y confitería']->Id_Subcategoria,
            ],

            // Bebidas
            [
                'Nombre' => 'Coca-Cola 350ml',
                'Marca' => 'Coca-Cola',
                'Fecha_Caducidad' => now()->addMonths(9),
                'Cantidad' => 60,
                'Descripcion' => 'Bebida gaseosa cola',
                'Precio' => 1800,
                'PrecioOriginal' => 2200,
                'Tipo' => 'Gaseosa',
                'Codigo' => 'COC001',
                'Id_Empresa' => $empresa->Id_Empresa,
                'Id_Subcategoria' => $subcategorias['Gaseosas y jugos']->Id_Subcategoria,
            ],
            [
                'Nombre' => 'Café Juan Valdez 500g',
                'Marca' => 'Juan Valdez',
                'Fecha_Caducidad' => now()->addMonths(12),
                'Cantidad' => 20,
                'Descripcion' => 'Café molido premium',
                'Precio' => 12000,
                'PrecioOriginal' => 15000,
                'Tipo' => 'Café',
                'Codigo' => 'CAF001',
                'Id_Empresa' => $empresa->Id_Empresa,
                'Id_Subcategoria' => $subcategorias['Café, té e infusiones']->Id_Subcategoria,
            ],

            // Lácteos y Derivados
            [
                'Nombre' => 'Leche Entera 1L',
                'Marca' => 'Alquería',
                'Fecha_Caducidad' => now()->addDays(7),
                'Cantidad' => 15,
                'Descripcion' => 'Leche fresca entera',
                'Precio' => 3500,
                'PrecioOriginal' => 4000,
                'Tipo' => 'Leche',
                'Codigo' => 'LEC001',
                'Id_Empresa' => $empresa->Id_Empresa,
                'Id_Subcategoria' => $subcategorias['Leches (líquida, en polvo, deslactosada)']->Id_Subcategoria,
            ],
            [
                'Nombre' => 'Yogurt Griego 150g',
                'Marca' => 'Alpina',
                'Fecha_Caducidad' => now()->addDays(5),
                'Cantidad' => 25,
                'Descripcion' => 'Yogurt griego natural',
                'Precio' => 2800,
                'PrecioOriginal' => 3200,
                'Tipo' => 'Yogurt',
                'Codigo' => 'YOG001',
                'Id_Empresa' => $empresa->Id_Empresa,
                'Id_Subcategoria' => $subcategorias['Yogures y kumis']->Id_Subcategoria,
            ],

            // Congelados
            [
                'Nombre' => 'Pizza Congelada 4 Quesos',
                'Marca' => 'Pizza Hut',
                'Fecha_Caducidad' => now()->addMonths(6),
                'Cantidad' => 12,
                'Descripcion' => 'Pizza lista para hornear',
                'Precio' => 8500,
                'PrecioOriginal' => 10000,
                'Tipo' => 'Congelado',
                'Codigo' => 'PIZ001',
                'Id_Empresa' => $empresa->Id_Empresa,
                'Id_Subcategoria' => $subcategorias['Comidas listas congeladas']->Id_Subcategoria,
            ],

            // Panadería
            [
                'Nombre' => 'Pan Tajado Integral',
                'Marca' => 'Bimbo',
                'Fecha_Caducidad' => now()->addDays(3),
                'Cantidad' => 20,
                'Descripcion' => 'Pan integral tajado',
                'Precio' => 2200,
                'PrecioOriginal' => 2800,
                'Tipo' => 'Pan',
                'Codigo' => 'PAN001',
                'Id_Empresa' => $empresa->Id_Empresa,
                'Id_Subcategoria' => $subcategorias['Pan tajado empacado']->Id_Subcategoria,
            ],

            // Cuidado Personal
            [
                'Nombre' => 'Shampoo Pantene 400ml',
                'Marca' => 'Pantene',
                'Fecha_Caducidad' => now()->addMonths(36),
                'Cantidad' => 18,
                'Descripcion' => 'Shampoo para cabello dañado',
                'Precio' => 8500,
                'PrecioOriginal' => 10000,
                'Tipo' => 'Cuidado Personal',
                'Codigo' => 'SHA001',
                'Id_Empresa' => $empresa->Id_Empresa,
                'Id_Subcategoria' => $subcategorias['Shampoo y acondicionador']->Id_Subcategoria,
            ],
        ];

        $created = 0;
        foreach ($productos as $productoData) {
            try {
                Producto::create($productoData);
                $created++;
                $this->line("✅ Creado: {$productoData['Nombre']}");
            } catch (\Exception $e) {
                $this->error("❌ Error creando {$productoData['Nombre']}: {$e->getMessage()}");
            }
        }

        $this->info("🎉 ¡Se crearon {$created} productos de ejemplo!");
        
        // Mostrar resumen por categoría
        $this->showSummary();
    }

    private function showSummary()
    {
        $this->info('');
        $this->info('📊 RESUMEN DE PRODUCTOS POR CATEGORÍA:');
        $this->info('=====================================');
        
        $categorias = \App\Models\Categoria::with(['subcategorias.productos'])->get();
        
        foreach ($categorias as $categoria) {
            $totalProductos = $categoria->subcategorias->sum(function($sub) {
                return $sub->productos->count();
            });
            
            $this->info("{$categoria->Icono} {$categoria->Nombre}: {$totalProductos} productos");
            
            foreach ($categoria->subcategorias as $subcategoria) {
                $productCount = $subcategoria->productos->count();
                if ($productCount > 0) {
                    $this->line("  └─ {$subcategoria->Icono} {$subcategoria->Nombre}: {$productCount} productos");
                }
            }
        }
    }
}

