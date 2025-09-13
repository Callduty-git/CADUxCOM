<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('product_id');
            $table->timestamps();
            
            // Claves foráneas
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('product_id')->references('Id_Producto')->on('productos')->onDelete('cascade');
            
            // Índices para mejorar rendimiento
            $table->index(['user_id']);
            $table->index(['product_id']);
            
            // Evitar duplicados: un usuario no puede tener el mismo producto dos veces
            $table->unique(['user_id', 'product_id'], 'unique_user_product_wishlist');
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