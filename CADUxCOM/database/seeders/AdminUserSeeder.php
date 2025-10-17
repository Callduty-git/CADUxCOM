<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuario admin joker (existente)
        User::updateOrCreate(
            ['email' => 'joker@caduxcom.local'],
            [
                'name' => 'joker',
                'password' => Hash::make('123456789'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Crear usuario admin Jeidi
        $jeidi = User::updateOrCreate(
            ['email' => 'jeidi@admin.com'],
            [
                'name' => 'Jeidi',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'email_verified_at' => now(),
                'documento_id' => '12345678',
                'municipio' => 'Administración',
                'contacto' => '+57 300 123 4567'
            ]
        );

        $this->command->info('Usuarios administradores creados/actualizados:');
        $this->command->info('1. Email: joker@caduxcom.local - Contraseña: 123456789');
        $this->command->info('2. Email: jeidi@admin.com - Contraseña: admin123');
    }
}