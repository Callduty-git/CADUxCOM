<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;
use App\Models\Subcategoria;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        $productos = [
            [
                'Nombre' => 'Leche entera Alpina',
                'Marca' => 'Alpina',
                'Fecha_Caducidad' => now()->addDays(15),
                'Cantidad' => 20,
                'Foto' => null,
                'Descripcion' => 'Leche entera en caja de 1L',
                'Precio' => 3.50,
                'Tipo' => 'Unidad',
                'Codigo' => 'LCH001',
                'Subcategoria' => 'Leche'
            ],
            [
                'Nombre' => 'Yogurt natural',
                'Marca' => 'Colanta',
                'Fecha_Caducidad' => now()->addDays(10),
                'Cantidad' => 15,
                'Foto' => null,
                'Descripcion' => 'Yogurt natural 500ml',
                'Precio' => 2.20,
                'Tipo' => 'Unidad',
                'Codigo' => 'YGT002',
                'Subcategoria' => 'Yogurt'
            ],
            [
                'Nombre' => 'Arroz blanco 1kg',
                'Marca' => 'Diana',
                'Fecha_Caducidad' => now()->addMonths(6),
                'Cantidad' => 50,
                'Foto' => null,
                'Descripcion' => 'Arroz blanco 1kg en bolsa',
                'Precio' => 4.10,
                'Tipo' => 'Unidad',
                'Codigo' => 'ARZ003',
                'Subcategoria' => 'Arroz'
            ],
        ];

        foreach ($productos as $prod) {
            $subcat = Subcategoria::where('Nombre', $prod['Subcategoria'])->first();
            Producto::create([
                'Id_Empresa' => 1, // Asegúrate de tener una empresa con ID 1
                'Id_Subcategoria' => $subcat->Id_Subcategoria,
                'Nombre' => $prod['Nombre'],
                'Marca' => $prod['Marca'],
                'Fecha_Caducidad' => $prod['Fecha_Caducidad'],
                'Cantidad' => $prod['Cantidad'],
                'Foto' => $prod['Foto'],
                'Descripcion' => $prod['Descripcion'],
                'Precio' => $prod['Precio'],
                'Tipo' => $prod['Tipo'],
                'Codigo' => $prod['Codigo'],
            ]);
        }
    }
}