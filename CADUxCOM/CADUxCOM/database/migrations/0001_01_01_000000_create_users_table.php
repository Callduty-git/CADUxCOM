<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // ID por defecto de Laravel
            $table->string('name'); // Nombre del usuario
            $table->string('foto')->nullable(); // Foto de perfil
            $table->string('municipio')->nullable();
            $table->string('contacto')->nullable();
            $table->string('documento_id')->unique()->nullable(); // Documento de identidad obligatorio
            $table->text('preferencias')->nullable();
            $table->string('ubicacion')->nullable();
            $table->string('email')->unique(); // Email principal (requerido por Laravel)
            $table->timestamp('email_verified_at')->nullable(); // Verificación
            $table->string('password'); // Contraseña
            $table->rememberToken(); // Token de sesión
            $table->timestamps(); // created_at y updated_at
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
