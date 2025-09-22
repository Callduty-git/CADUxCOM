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
use Illuminate\Support\Facades\Storage;

class GenerateEmailPreviews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:preview';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera vistas previas de todos los emails del sistema de registro';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("📧 Generando vistas previas de emails...");

        // Crear directorio para las vistas previas
        $previewDir = storage_path('app/email-previews');
        if (!is_dir($previewDir)) {
            mkdir($previewDir, 0755, true);
        }

        // Crear datos de prueba
        $testUser = User::first() ?? User::create([
            'name' => 'Juan Pérez',
            'email' => 'juan@ejemplo.com',
            'password' => bcrypt('password123'),
            'email_verified' => false,
        ]);

        $testEmpresa = Empresa::first() ?? new Empresa([
            'Nombre' => 'Mi Empresa S.A.S.',
            'email' => 'empresa@ejemplo.com',
            'NIT' => '900123456-1',
            'Direccion' => 'Calle 123 #45-67',
            'Municipio' => 'Bogotá',
            'Contacto' => '3001234567',
            'status' => 'pending',
            'password' => bcrypt('password123'),
            'created_at' => now(),
        ]);

        $emails = [
            'user-registration-notification' => new UserRegistrationNotification($testUser),
            'user-email-verification' => new UserEmailVerification($testUser),
            'empresa-registration-notification' => new EmpresaRegistrationNotification($testEmpresa),
            'empresa-pending-verification' => new EmpresaPendingVerification($testEmpresa),
            'empresa-approved' => new EmpresaApprovalNotification($testEmpresa, true),
            'empresa-rejected' => new EmpresaApprovalNotification($testEmpresa, false),
        ];

        foreach ($emails as $filename => $mailable) {
            try {
                $html = $mailable->render();
                file_put_contents($previewDir . '/' . $filename . '.html', $html);
                $this->line("  ✅ Generado: {$filename}.html");
            } catch (\Exception $e) {
                $this->error("  ❌ Error generando {$filename}: " . $e->getMessage());
            }
        }

        $this->info("✅ Vistas previas generadas en: {$previewDir}");
        $this->info("📁 Puedes abrir los archivos HTML en tu navegador para ver cómo se ven los emails");
        
        return Command::SUCCESS;
    }
}