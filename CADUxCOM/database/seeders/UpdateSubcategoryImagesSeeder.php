<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subcategoria;

class UpdateSubcategoryImagesSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🖼️  Actualizando imágenes de subcategorías...');
        
        // Mapeo de subcategorías a imágenes disponibles (usando archivos que realmente existen)
        $imageMapping = [
            // Despensa
            'Pastas, arroces y granos' => 'pasta.png',
            'Enlatados y conservas' => 'enlatados.png',
            'Harinas y mezclas' => 'harina.jpg',
            'Salsas y condimentos' => 'condimentos.png', // Usando condimentos.png que existe
            
            // Snacks y Dulces
            'Galletas y mecato' => 'galletas.png',
            'Chocolates y confitería' => 'dulces.png',
            'Barras y granolas' => 'barra-granola.jpg',
            
            // Bebidas
            'Gaseosas y jugos' => 'agua.png',
            'Aguas saborizadas y energizantes' => 'energizante.jpg',
            'Café, té e infusiones' => 'cafe.png',
            
            // Lácteos y Derivados
            'Leches (líquida, en polvo, deslactosada)' => 'leche.png',
            'Yogures y kumis' => 'yogurt.jpg',
            'Quesos empacados' => 'queso.jpg',
            'Mantequillas y margarinas' => 'mantequilla.png',
            
            // Congelados
            'Comidas listas congeladas' => 'comida-congelada.png',
            'Verduras/papas congeladas' => 'verduras.png',
            'Helados y postres' => 'helado.jpg',
            
            // Panadería
            'Pan tajado empacado' => 'pan.png',
            'Ponqués y repostería' => 'reposteria.png',
            'Arepas empacadas' => 'arepa.png',
            
            // Cuidado Personal
            'Shampoo y acondicionador' => 'shampoo.png',
            'Cremas corporales' => 'crema-corporal.png',
            'Desodorantes' => 'desodorante.png',
            'Jabones líquidos' => 'jabon-liquido.png',
            'Enjuagues bucales' => 'enjuaguebucal.png',
            'Cremas dentales' => 'crema-dental.png',
        ];
        
        $updated = 0;
        $notFound = 0;
        
        foreach ($imageMapping as $subcategoryName => $imageName) {
            $subcategoria = Subcategoria::where('Nombre', $subcategoryName)->first();
            
            if ($subcategoria) {
                $subcategoria->update([
                    'Icono' => 'images/subcategorias/' . $imageName
                ]);
                $this->command->info("✅ {$subcategoryName} → {$imageName}");
                $updated++;
            } else {
                $this->command->warn("⚠️  No encontrada: {$subcategoryName}");
                $notFound++;
            }
        }
        
        $this->command->info('');
        $this->command->info("📊 Resumen:");
        $this->command->info("✅ Actualizadas: {$updated}");
        $this->command->info("⚠️  No encontradas: {$notFound}");
        $this->command->info("🖼️  Total de imágenes disponibles: " . count($imageMapping));
    }
}
