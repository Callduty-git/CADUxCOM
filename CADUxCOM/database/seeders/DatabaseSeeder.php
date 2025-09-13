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
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'correo_electronico' => 'test@example.com',
            'documento_id' => '123456789',
            'email_verified_at' => now(),
            'password' => Hash::make('password'), 
            'remember_token' => Str::random(10),
        ]);

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