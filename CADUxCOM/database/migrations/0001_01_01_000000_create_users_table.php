<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Conservamos el ID por defecto
            $table->string('name'); // Usado por Laravel
            $table->string('foto')->nullable();
            $table->string('municipio')->nullable();
            $table->string('contacto')->nullable();
            $table->string('correo_electronico')->unique(); // extra
            $table->string('documento_id')->unique();
            $table->text('preferencias')->nullable();
            $table->string('ubicacion')->nullable();
            $table->string('email')->unique(); // Usado por Laravel
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password'); // Usado por Laravel
            $table->rememberToken();
            $table->timestamps();
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