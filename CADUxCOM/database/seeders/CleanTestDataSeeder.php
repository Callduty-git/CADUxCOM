<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Empresa;
use App\Models\Producto;
use App\Models\Wishlist;
use App\Models\Order;
use App\Models\OrderItem;

class CleanTestDataSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar datos relacionados primero (por las foreign keys)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Eliminar wishlists
        Wishlist::truncate();
        
        // Eliminar items de órdenes
        OrderItem::truncate();
        
        // Eliminar órdenes
        Order::truncate();
        
        // Eliminar productos de prueba
        Producto::truncate();
        
        // Eliminar empresas de prueba
        Empresa::truncate();
        
        // Eliminar usuarios de prueba
        User::where('email', 'test@example.com')->delete();
        
        // Eliminar admin de prueba
        DB::table('admin')->where('usuario', 'admin')->delete();
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $this->command->info('✅ Datos de prueba eliminados exitosamente');
        $this->command->info('🗑️  Empresas de prueba eliminadas');
        $this->command->info('🗑️  Productos de prueba eliminados');
        $this->command->info('🗑️  Usuarios de prueba eliminados');
        $this->command->info('🗑️  Admin de prueba eliminado');
        $this->command->info('🗑️  Wishlists eliminadas');
        $this->command->info('🗑️  Órdenes eliminadas');
        $this->command->info('✅ Categorías y subcategorías mantenidas');
    }
}
