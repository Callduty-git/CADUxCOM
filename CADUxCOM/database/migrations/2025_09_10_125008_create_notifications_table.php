<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Esta migración crea la tabla de notificaciones automáticas
     * para alertar sobre productos próximos a caducar.
     */
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // 'expiry_alert', 'discount_available', 'new_product', etc.
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable(); // Datos adicionales (producto, empresa, etc.)
            
            // Relaciones
            $table->unsignedBigInteger('user_id')->nullable(); // Usuario que recibe la notificación
            $table->unsignedBigInteger('empresa_id')->nullable(); // Empresa relacionada
            $table->unsignedBigInteger('producto_id')->nullable(); // Producto relacionado
            
            // Configuración de notificación
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->enum('channel', ['email', 'push', 'sms', 'in_app'])->default('in_app');
            $table->boolean('is_read')->default(false);
            $table->boolean('is_sent')->default(false);
            $table->timestamp('scheduled_at')->nullable(); // Para notificaciones programadas
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('read_at')->nullable();
            
            $table->timestamps();
            
            // Índices
            $table->index(['user_id', 'is_read']);
            $table->index(['empresa_id', 'type']);
            $table->index(['producto_id', 'type']);
            $table->index(['scheduled_at', 'is_sent']);
            $table->index('type');
            $table->index('priority');
            
            // Claves foráneas
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('empresa_id')->references('Id_Empresa')->on('empresas')->onDelete('cascade');
            $table->foreign('producto_id')->references('Id_Producto')->on('productos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};