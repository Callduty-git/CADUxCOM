<?php

namespace App\Console\Commands;

use App\Models\Producto;
use App\Models\User;
use App\Mail\ProductExpiryNotification;
use App\Mail\DiscountAlertNotification;
use App\Mail\NewProductNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmailNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:email-notifications {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envía emails de prueba a una dirección específica';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        $this->info("🧪 Enviando emails de prueba a: {$email}");
        
        // Obtener un producto de ejemplo
        $producto = Producto::with('empresa')->first();
        $user = User::first();
        
        if (!$producto) {
            $this->error("❌ No hay productos en la base de datos");
            return Command::FAILURE;
        }
        
        if (!$user) {
            $this->error("❌ No hay usuarios en la base de datos");
            return Command::FAILURE;
        }
        
        try {
            // Enviar email de caducidad
            $this->info("📧 Enviando email de alerta de caducidad...");
            Mail::to($email)->send(
                new ProductExpiryNotification($producto, $user, 3)
            );
            $this->info("✅ Email de caducidad enviado");
            
            // Enviar email de descuento
            $this->info("📧 Enviando email de alerta de descuento...");
            Mail::to($email)->send(
                new DiscountAlertNotification($producto, $user, 25.5)
            );
            $this->info("✅ Email de descuento enviado");
            
            // Enviar email de nuevo producto
            $this->info("📧 Enviando email de nuevo producto...");
            Mail::to($email)->send(
                new NewProductNotification($producto, $user)
            );
            $this->info("✅ Email de nuevo producto enviado");
            
            $this->info("🎉 Todos los emails de prueba enviados correctamente");
            
        } catch (\Exception $e) {
            $this->error("❌ Error enviando emails: " . $e->getMessage());
            return Command::FAILURE;
        }
        
        return Command::SUCCESS;
    }
}