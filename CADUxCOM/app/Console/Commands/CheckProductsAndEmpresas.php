<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Empresa;
use App\Models\Producto;

class CheckProductsAndEmpresas extends Command
{
    protected $signature = 'check:products-empresas';
    protected $description = 'Verificar productos y empresas en la base de datos';

    public function handle()
    {
        $this->info('=== VERIFICACIÓN DE EMPRESAS Y PRODUCTOS ===');
        
        // Verificar empresas
        $empresas = Empresa::all();
        $this->info("\n📊 EMPRESAS ({$empresas->count()}):");
        foreach ($empresas as $empresa) {
            $productosCount = $empresa->productos()->count();
            $this->line("- {$empresa->Nombre} ({$empresa->Municipio}) - {$productosCount} productos - Coords: ({$empresa->latitude}, {$empresa->longitude})");
        }
        
        // Verificar productos
        $productos = Producto::with('empresa')->get();
        $this->info("\n🛍️ PRODUCTOS ({$productos->count()}):");
        foreach ($productos as $producto) {
            $activo = $producto->Activo ? '✅ Activo' : '❌ Inactivo';
            $descuento = $producto->Descuento ? "{$producto->Descuento}%" : 'Sin descuento';
            $this->line("- {$producto->Nombre} ({$producto->empresa->Nombre}) - {$activo} - {$descuento}");
        }
        
        // Verificar empresas con productos activos
        $empresasConProductosActivos = Empresa::whereHas('productos', function($query) {
            $query->where('Activo', true);
        })->withValidCoordinates()->get();
        
        $this->info("\n🎯 EMPRESAS CON PRODUCTOS ACTIVOS Y COORDENADAS ({$empresasConProductosActivos->count()}):");
        foreach ($empresasConProductosActivos as $empresa) {
            $productosActivos = $empresa->productos()->where('Activo', true)->count();
            $this->line("- {$empresa->Nombre} - {$productosActivos} productos activos");
        }
        
        // Simular la consulta del controlador
        $this->info("\n🔍 SIMULANDO CONSULTA DEL CONTROLADOR:");
        try {
            $empresasParaMapa = Empresa::withValidCoordinates()
                ->whereHas('productos', function($query) {
                    $query->where('Activo', true);
                })
                ->with(['productos' => function($query) {
                    $query->where('Activo', true);
                }])
                ->get();
                
            $this->info("Empresas encontradas para el mapa: {$empresasParaMapa->count()}");
            
            foreach ($empresasParaMapa as $empresa) {
                $this->line("- {$empresa->Nombre}: {$empresa->productos->count()} productos activos");
            }
        } catch (\Exception $e) {
            $this->error("Error en la consulta: " . $e->getMessage());
        }
    }
}