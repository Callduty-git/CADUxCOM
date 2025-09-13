<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Esta migración crea la tabla de listas de deseos.
     * Permite a los usuarios guardar productos para comprar después.
     */
    public function up(): void
    {
        Schema::create('wishlists', function (Blueprint $table) {
            // Identificador único del item en la lista de deseos
            $table->id();
            
            // Usuario propietario de la lista (puede ser null para invitados con sesión)
            $table->unsignedBigInteger('user_id')->nullable();
            
            // Identificador de sesión para usuarios no registrados
            $table->string('session_id')->nullable();
            
            // Producto en la lista de deseos
            $table->unsignedBigInteger('product_id');
            
            // Cantidad deseada (por defecto 1)
            $table->integer('quantity')->default(1);
            
            // Notas del usuario sobre este producto
            $table->text('notes')->nullable();
            
            // Prioridad del producto en la lista (1 = más importante)
            $table->integer('priority')->default(1);
            
            // Timestamps
            $table->timestamps();
            
            // Claves foráneas
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('product_id')->references('Id_Producto')->on('productos')->onDelete('cascade');
            
            // Índices para optimizar consultas
            $table->index(['user_id']);
            $table->index(['session_id']);
            $table->index(['product_id']);
            
            // Evitar duplicados: un usuario/sesión no puede tener el mismo producto dos veces
            $table->unique(['user_id', 'product_id'], 'unique_user_product');
            $table->unique(['session_id', 'product_id'], 'unique_session_product');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wishlists');
    }
};