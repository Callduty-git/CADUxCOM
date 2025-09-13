<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Esta migración crea la tabla de cupones de descuento.
     * Permite crear promociones y descuentos para los clientes.
     */
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            // Identificador único del cupón
            $table->id();
            
            // Código del cupón (único)
            $table->string('code')->unique();
            
            // Nombre descriptivo del cupón
            $table->string('name');
            $table->text('description')->nullable();
            
            // Tipo de descuento
            $table->enum('type', [
                'percentage',   // Porcentaje de descuento
                'fixed_amount', // Cantidad fija de descuento
                'free_shipping' // Envío gratuito
            ]);
            
            // Valor del descuento
            $table->decimal('value', 10, 2); // Porcentaje o cantidad fija
            
            // Monto mínimo de compra para aplicar el cupón
            $table->decimal('minimum_amount', 10, 2)->default(0);
            
            // Monto máximo de descuento (para cupones de porcentaje)
            $table->decimal('maximum_discount', 10, 2)->nullable();
            
            // Límites de uso
            $table->integer('usage_limit')->nullable(); // Límite total de usos
            $table->integer('usage_limit_per_user')->default(1); // Límite por usuario
            $table->integer('used_count')->default(0); // Contador de usos
            
            // Fechas de validez
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            
            // Estado del cupón
            $table->boolean('is_active')->default(true);
            
            // Restricciones
            $table->json('applicable_categories')->nullable(); // Categorías aplicables
            $table->json('applicable_products')->nullable(); // Productos específicos
            $table->json('excluded_products')->nullable(); // Productos excluidos
            
            // Información adicional
            $table->text('terms_conditions')->nullable();
            $table->string('created_by')->nullable(); // Quién creó el cupón
            
            // Timestamps
            $table->timestamps();
            
            // Índices para optimizar consultas
            $table->index(['code', 'is_active']);
            $table->index(['starts_at', 'expires_at']);
            $table->index(['is_active', 'expires_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};