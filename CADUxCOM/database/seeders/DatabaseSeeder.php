<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Crear usuario de prueba
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'email_verified_at' => now(),
                'password' => Hash::make('password'), 
                'remember_token' => Str::random(10),
            ]
        );

        // Ejecutar seeders personalizados
        $this->call([
            EmpresaSeeder::class,
            AdminSeeder::class,
            CategoriaSeeder::class,
            SubcategoriaSeeder::class,
            ProductoSeeder::class,
        ]);
    }
}
