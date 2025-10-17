<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Empresa;
use App\Models\EmpresaNotification;

class CreateTestNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:create-test {empresa_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crear notificaciones de prueba para una empresa';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $empresaId = $this->argument('empresa_id');
        
        if ($empresaId) {
            $empresa = Empresa::find($empresaId);
            if (!$empresa) {
                $this->error("No se encontró la empresa con ID: {$empresaId}");
                return 1;
            }
            $empresas = collect([$empresa]);
        } else {
            $empresas = Empresa::all();
            if ($empresas->isEmpty()) {
                $this->error("No hay empresas registradas en el sistema");
                return 1;
            }
        }

        $this->info("Creando notificaciones de prueba...");

        foreach ($empresas as $empresa) {
            // Notificación de nuevo pedido
            EmpresaNotification::create([
                'empresa_id' => $empresa->Id_Empresa,
                'type' => 'new_order',
                'title' => 'Nuevo pedido recibido',
                'message' => 'Has recibido un nuevo pedido de un cliente.',
                'data' => [
                    'order_id' => rand(1000, 9999),
                    'customer_name' => 'Cliente de Prueba',
                    'total_amount' => rand(50, 500),
                    'product_count' => rand(1, 5)
                ],
                'read' => false
            ]);

            // Notificación de cambio de estado
            EmpresaNotification::create([
                'empresa_id' => $empresa->Id_Empresa,
                'type' => 'order_status_change',
                'title' => 'Estado de pedido actualizado',
                'message' => 'El estado de un pedido ha sido actualizado a "En preparación".',
                'data' => [
                    'order_id' => rand(1000, 9999),
                    'old_status' => 'pendiente',
                    'new_status' => 'en_preparacion'
                ],
                'read' => rand(0, 1) == 1
            ]);

            // Notificación de stock bajo
            EmpresaNotification::create([
                'empresa_id' => $empresa->Id_Empresa,
                'type' => 'low_stock',
                'title' => 'Stock bajo detectado',
                'message' => 'Algunos productos tienen stock bajo y necesitan reposición.',
                'data' => [
                    'products_count' => rand(1, 3),
                    'products' => ['Producto A', 'Producto B']
                ],
                'read' => false
            ]);

            $this->info("✓ Notificaciones creadas para: {$empresa->Nombre}");
        }

        $this->info("¡Notificaciones de prueba creadas exitosamente!");
        return 0;
    }
}
