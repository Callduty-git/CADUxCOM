<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Esta migración crea la tabla de logs de actividad.
     * Registra todas las acciones importantes en el sistema.
     */
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            // Identificador único del log
            $table->id();
            
            // Usuario que realizó la acción (puede ser null para acciones del sistema)
            $table->unsignedBigInteger('user_id')->nullable();
            
            // Empresa relacionada (si aplica)
            $table->unsignedBigInteger('empresa_id')->nullable();
            
            // Tipo de acción
            $table->enum('action_type', [
                'user_login',
                'user_register',
                'user_logout',
                'product_view',
                'product_create',
                'product_update',
                'product_delete',
                'order_create',
                'order_update',
                'order_cancel',
                'review_create',
                'review_update',
                'cart_add',
                'cart_remove',
                'wishlist_add',
                'wishlist_remove',
                'coupon_apply',
                'search_performed',
                'page_view'
            ]);
            
            // Descripción de la acción
            $table->string('description');
            
            // Datos adicionales en JSON
            $table->json('metadata')->nullable();
            
            // IP del usuario
            $table->string('ip_address')->nullable();
            
            // User Agent del navegador
            $table->text('user_agent')->nullable();
            
            // URL donde ocurrió la acción
            $table->string('url')->nullable();
            
            // Método HTTP
            $table->string('method')->nullable();
            
            // Timestamps
            $table->timestamps();
            
            // Claves foráneas
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('empresa_id')->references('Id_Empresa')->on('empresas')->onDelete('set null');
            
            // Índices para optimizar consultas
            $table->index(['user_id', 'created_at']);
            $table->index(['empresa_id', 'created_at']);
            $table->index(['action_type', 'created_at']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};