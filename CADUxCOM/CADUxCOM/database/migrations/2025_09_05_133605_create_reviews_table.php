<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Esta migración crea la tabla de reseñas y calificaciones.
     * Permite a los usuarios calificar y reseñar productos.
     */
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            // Identificador único de la reseña
            $table->id();
            
            // Usuario que escribió la reseña
            $table->unsignedBigInteger('user_id');
            
            // Producto reseñado
            $table->unsignedBigInteger('product_id');
            
            // Calificación (1-5 estrellas)
            $table->integer('rating')->unsigned();
            
            // Título de la reseña
            $table->string('title')->nullable();
            
            // Contenido de la reseña
            $table->text('content');
            
            // Estado de la reseña
            $table->enum('status', [
                'pending',    // Pendiente de moderación
                'approved',   // Aprobada
                'rejected',   // Rechazada
                'hidden'      // Ocultada
            ])->default('pending');
            
            // Información adicional
            $table->boolean('is_verified_purchase')->default(false);
            $table->integer('helpful_count')->default(0);
            $table->integer('not_helpful_count')->default(0);
            
            // Timestamps
            $table->timestamps();
            
            // Claves foráneas
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('product_id')->references('Id_Producto')->on('productos')->onDelete('cascade');
            
            // Índices para optimizar consultas
            $table->index(['product_id', 'status']);
            $table->index(['user_id', 'product_id']);
            $table->index(['rating']);
            $table->index(['status', 'created_at']);
            
            // Evitar reseñas duplicadas del mismo usuario para el mismo producto
            $table->unique(['user_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};