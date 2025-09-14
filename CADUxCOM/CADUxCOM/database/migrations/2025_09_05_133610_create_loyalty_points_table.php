<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Esta migración crea la tabla de puntos de fidelidad.
     * Permite a los usuarios acumular y canjear puntos por compras.
     */
    public function up(): void
    {
        Schema::create('loyalty_points', function (Blueprint $table) {
            // Identificador único del registro de puntos
            $table->id();
            
            // Usuario propietario de los puntos
            $table->unsignedBigInteger('user_id');
            
            // Orden relacionada (si aplica)
            $table->unsignedBigInteger('order_id')->nullable();
            
            // Tipo de transacción
            $table->enum('type', [
                'earned',     // Puntos ganados
                'redeemed',   // Puntos canjeados
                'expired',    // Puntos expirados
                'adjusted'    // Ajuste manual
            ]);
            
            // Cantidad de puntos (positivo para ganados, negativo para canjeados)
            $table->integer('points');
            
            // Descripción de la transacción
            $table->string('description');
            
            // Fecha de expiración de los puntos (si aplica)
            $table->date('expires_at')->nullable();
            
            // Estado de los puntos
            $table->enum('status', [
                'active',     // Activos
                'expired',    // Expirados
                'redeemed'    // Canjeados
            ])->default('active');
            
            // Información adicional
            $table->text('notes')->nullable();
            $table->string('reference')->nullable(); // Referencia externa
            
            // Timestamps
            $table->timestamps();
            
            // Claves foráneas
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');
            
            // Índices para optimizar consultas
            $table->index(['user_id', 'status']);
            $table->index(['type', 'created_at']);
            $table->index(['expires_at']);
            $table->index(['order_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loyalty_points');
    }
};