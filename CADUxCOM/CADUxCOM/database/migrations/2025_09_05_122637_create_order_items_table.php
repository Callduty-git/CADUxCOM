<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Esta migración crea la tabla de items de órdenes.
     * Almacena los productos específicos de cada orden con sus cantidades y precios.
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            // Identificador único del item
            $table->id();
            
            // Referencia a la orden
            $table->unsignedBigInteger('order_id');
            
            // Información del producto (almacenamos snapshot por si el producto cambia)
            $table->unsignedBigInteger('product_id');
            $table->string('product_name');
            $table->string('product_sku')->nullable(); // Código del producto
            $table->text('product_description')->nullable();
            
            // Información de la empresa vendedora
            $table->unsignedBigInteger('empresa_id');
            $table->string('empresa_name');
            
            // Cantidad y precios
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2); // Precio unitario al momento de la compra
            $table->decimal('total_price', 10, 2); // Precio total (quantity * unit_price)
            
            // Información adicional del producto
            $table->string('product_image')->nullable();
            $table->string('product_brand')->nullable();
            $table->string('product_category')->nullable();
            $table->string('product_subcategory')->nullable();
            
            // Timestamps
            $table->timestamps();
            
            // Claves foráneas
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('product_id')->references('Id_Producto')->on('productos')->onDelete('cascade');
            $table->foreign('empresa_id')->references('Id_Empresa')->on('empresas')->onDelete('cascade');
            
            // Índices para optimizar consultas
            $table->index(['order_id']);
            $table->index(['product_id']);
            $table->index(['empresa_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};