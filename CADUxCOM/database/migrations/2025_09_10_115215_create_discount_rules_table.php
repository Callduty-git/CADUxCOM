<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Esta migración crea la tabla de reglas de descuentos progresivos.
     * Permite a las empresas configurar descuentos automáticos basados en
     * la proximidad a la fecha de caducidad de los productos.
     */
    public function up(): void
    {
        Schema::create('discount_rules', function (Blueprint $table) {
            // Identificador único de la regla
            $table->id();
            
            // Empresa propietaria de la regla
            $table->unsignedBigInteger('empresa_id');
            
            // Nombre descriptivo de la regla
            $table->string('name');
            $table->text('description')->nullable();
            
            // Configuración de la regla
            $table->integer('days_before_expiry'); // Días antes de la caducidad para aplicar
            $table->enum('discount_type', [
                'percentage',   // Descuento por porcentaje
                'fixed_amount'  // Descuento por cantidad fija
            ]);
            $table->decimal('discount_value', 8, 2); // Valor del descuento
            
            // Restricciones
            $table->decimal('minimum_discount', 8, 2)->default(0); // Descuento mínimo
            $table->decimal('maximum_discount', 8, 2)->nullable(); // Descuento máximo
            $table->decimal('minimum_product_price', 8, 2)->default(0); // Precio mínimo del producto
            
            // Aplicabilidad
            $table->json('applicable_categories')->nullable(); // Categorías aplicables
            $table->json('applicable_products')->nullable(); // Productos específicos aplicables
            $table->json('excluded_products')->nullable(); // Productos excluidos
            
            // Estado y configuración
            $table->boolean('is_active')->default(true);
            $table->boolean('is_automatic')->default(true); // Si se aplica automáticamente
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            
            // Metadatos
            $table->integer('usage_count')->default(0); // Contador de usos
            $table->decimal('total_savings', 12, 2)->default(0); // Ahorro total generado
            
            // Timestamps
            $table->timestamps();
            
            // Claves foráneas
            $table->foreign('empresa_id')->references('Id_Empresa')->on('empresas')->onDelete('cascade');
            
            // Índices para optimizar consultas
            $table->index(['empresa_id', 'is_active']);
            $table->index(['days_before_expiry', 'is_active']);
            $table->index(['starts_at', 'expires_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discount_rules');
    }
};