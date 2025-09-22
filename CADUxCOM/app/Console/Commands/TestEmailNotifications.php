<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Empresa;
use App\Mail\UserRegistrationNotification;
use App\Mail\UserEmailVerification;
use App\Mail\EmpresaRegistrationNotification;
use App\Mail\EmpresaPendingVerification;
use App\Mail\EmpresaApprovalNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmailNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:email-notifications {email} {--type=all : Tipo de email a probar (all, user-reg, user-ver, empresa-reg, empresa-pending, empresa-approved, empresa-rejected)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prueba el envío de emails de notificación del sistema de registro';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $type = $this->option('type');

        $this->info("🧪 Probando envío de emails de notificación...");
        $this->info("Email destino: {$email}");
        $this->info("Tipo: {$type}");

        // Crear o obtener datos de prueba
        $testUser = User::first();
        if (!$testUser) {
            $testUser = User::create([
                'name' => 'Usuario de Prueba',
                'email' => 'test@example.com',
                'password' => bcrypt('password123'),
                'email_verified' => false,
            ]);
        }

        $testEmpresa = Empresa::first();
        if (!$testEmpresa) {
            $testEmpresa = new Empresa([
                'Nombre' => 'Empresa de Prueba S.A.S.',
                'email' => 'empresa@example.com',
                'NIT' => '900123456-1',
                'Direccion' => 'Calle 123 #45-67',
                'Municipio' => 'Bogotá',
                'Contacto' => '3001234567',
                'status' => 'pending',
                'password' => bcrypt('password123'),
                'created_at' => now(),
            ]);
        }

        $emailsSent = 0;

        try {
            if ($type === 'all' || $type === 'user-reg') {
                $this->info("📧 Enviando notificación de registro de usuario...");
                Mail::to($email)->send(new UserRegistrationNotification($testUser));
                $emailsSent++;
                $this->line("  ✅ Email de notificación de usuario enviado");
            }

            if ($type === 'all' || $type === 'user-ver') {
                $this->info("📧 Enviando email de verificación de usuario...");
                Mail::to($email)->send(new UserEmailVerification($testUser));
                $emailsSent++;
                $this->line("  ✅ Email de verificación de usuario enviado");
            }

            if ($type === 'all' || $type === 'empresa-reg') {
                $this->info("📧 Enviando notificación de registro de empresa...");
                Mail::to($email)->send(new EmpresaRegistrationNotification($testEmpresa));
                $emailsSent++;
                $this->line("  ✅ Email de notificación de empresa enviado");
            }

            if ($type === 'all' || $type === 'empresa-pending') {
                $this->info("📧 Enviando email de espera de empresa...");
                Mail::to($email)->send(new EmpresaPendingVerification($testEmpresa));
                $emailsSent++;
                $this->line("  ✅ Email de espera de empresa enviado");
            }

            if ($type === 'all' || $type === 'empresa-approved') {
                $this->info("📧 Enviando email de aprobación de empresa...");
                Mail::to($email)->send(new EmpresaApprovalNotification($testEmpresa, true));
                $emailsSent++;
                $this->line("  ✅ Email de aprobación de empresa enviado");
            }

            if ($type === 'all' || $type === 'empresa-rejected') {
                $this->info("📧 Enviando email de rechazo de empresa...");
                Mail::to($email)->send(new EmpresaApprovalNotification($testEmpresa, false));
                $emailsSent++;
                $this->line("  ✅ Email de rechazo de empresa enviado");
            }

            $this->info("✅ Proceso completado. Se enviaron {$emailsSent} emails de prueba.");
            $this->info("📬 Revisa tu bandeja de entrada en: {$email}");

        } catch (\Exception $e) {
            $this->error("❌ Error enviando emails: " . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}