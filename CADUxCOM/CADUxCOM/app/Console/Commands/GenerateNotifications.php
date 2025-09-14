<?php

namespace App\Console\Commands;

use App\Models\Producto;
use App\Models\User;
use App\Models\Empresa;
use App\Mail\ProductExpiryNotification;
use App\Mail\DiscountAlertNotification;
use App\Mail\NewProductNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class GenerateNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:generate 
                            {--type=all : Tipo de notificaciones a generar (all, expiry, discount, new)}
                            {--days=7 : Días de anticipación para alertas de caducidad}
                            {--radius=10 : Radio en km para alertas de ubicación}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera notificaciones automáticas por email para productos próximos a caducar, descuentos disponibles y nuevos productos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->option('type');
        $days = (int) $this->option('days');
        $radius = (float) $this->option('radius');

        $this->info("🚀 Generando notificaciones automáticas por email...");
        $this->info("Tipo: {$type} | Días: {$days} | Radio: {$radius} km");

        $totalEmails = 0;

        if ($type === 'all' || $type === 'expiry') {
            $totalEmails += $this->sendExpiryNotifications($days);
        }

        if ($type === 'all' || $type === 'discount') {
            $totalEmails += $this->sendDiscountNotifications();
        }

        if ($type === 'all' || $type === 'new') {
            $totalEmails += $this->sendNewProductNotifications();
        }

        $this->info("✅ Proceso completado. Se enviaron {$totalEmails} emails.");
        
        return Command::SUCCESS;
    }

    /**
     * Enviar notificaciones de alerta de caducidad por email
     */
    private function sendExpiryNotifications(int $days): int
    {
        $this->info("📅 Enviando alertas de caducidad por email...");

        $productos = Producto::where('Cantidad', '>', 0)
            ->whereNotNull('Fecha_Caducidad')
            ->where('Fecha_Caducidad', '>', now())
            ->where('Fecha_Caducidad', '<=', now()->addDays($days))
            ->with('empresa')
            ->get();

        $emailsSent = 0;

        foreach ($productos as $producto) {
            $daysUntilExpiry = $producto->getDaysUntilExpiry();
            
            // Obtener usuarios que podrían estar interesados en este producto
            $users = $this->getInterestedUsers($producto);
            
            foreach ($users as $user) {
                try {
                    Mail::to($user->email)->send(
                        new ProductExpiryNotification($producto, $user, $daysUntilExpiry)
                    );
                    
                    $emailsSent++;
                    $this->line("  📧 Email enviado a {$user->email} - {$producto->Nombre} ({$daysUntilExpiry} días)");
                } catch (\Exception $e) {
                    $this->error("  ❌ Error enviando email a {$user->email}: " . $e->getMessage());
                }
            }
        }

        $this->info("  📊 Se enviaron {$emailsSent} emails de alerta de caducidad");
        return $emailsSent;
    }

    /**
     * Enviar notificaciones de descuentos disponibles por email
     */
    private function sendDiscountNotifications(): int
    {
        $this->info("💰 Enviando alertas de descuentos por email...");

        $productos = Producto::where('Cantidad', '>', 0)
            ->with('empresa')
            ->get()
            ->filter(function ($producto) {
                return $producto->hasDiscount();
            });

        $emailsSent = 0;

        foreach ($productos as $producto) {
            $discountInfo = $producto->getDiscountInfo();
            
            // Solo enviar si el descuento es significativo (>10%)
            if ($discountInfo['discount_percentage'] > 10) {
                $users = $this->getInterestedUsers($producto);
                
                foreach ($users as $user) {
                    try {
                        Mail::to($user->email)->send(
                            new DiscountAlertNotification($producto, $user, $discountInfo['discount_percentage'])
                        );
                        
                        $emailsSent++;
                        $discountPercentage = round($discountInfo['discount_percentage'], 0);
                        $this->line("  📧 Email enviado a {$user->email} - {$producto->Nombre} ({$discountPercentage}% descuento)");
                    } catch (\Exception $e) {
                        $this->error("  ❌ Error enviando email a {$user->email}: " . $e->getMessage());
                    }
                }
            }
        }

        $this->info("  📊 Se enviaron {$emailsSent} emails de alertas de descuentos");
        return $emailsSent;
    }

    /**
     * Enviar notificaciones de nuevos productos por email
     */
    private function sendNewProductNotifications(): int
    {
        $this->info("🆕 Enviando notificaciones de nuevos productos por email...");

        // Productos creados en las últimas 24 horas
        $productos = Producto::where('created_at', '>=', now()->subDay())
            ->where('Cantidad', '>', 0)
            ->with('empresa')
            ->get();

        $emailsSent = 0;

        foreach ($productos as $producto) {
            $users = $this->getInterestedUsers($producto);
            
            foreach ($users as $user) {
                try {
                    Mail::to($user->email)->send(
                        new NewProductNotification($producto, $user)
                    );
                    
                    $emailsSent++;
                    $this->line("  📧 Email enviado a {$user->email} - Nuevo producto: {$producto->Nombre}");
                } catch (\Exception $e) {
                    $this->error("  ❌ Error enviando email a {$user->email}: " . $e->getMessage());
                }
            }
        }

        $this->info("  📊 Se enviaron {$emailsSent} emails de nuevos productos");
        return $emailsSent;
    }

    /**
     * Obtener usuarios que podrían estar interesados en un producto
     */
    private function getInterestedUsers(Producto $producto): \Illuminate\Database\Eloquent\Collection
    {
        // Por ahora, enviar a todos los usuarios registrados
        // En el futuro, esto podría ser más inteligente basado en:
        // - Historial de compras
        // - Lista de deseos
        // - Preferencias de categorías
        // - Ubicación geográfica
        
        return User::where('email', '!=', null)
            ->where('email', '!=', '')
            ->get();
    }
}