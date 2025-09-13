<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Esta migración crea la tabla de órdenes para el sistema de e-commerce.
     * Almacena información de las compras realizadas por los usuarios.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            // Identificador único de la orden
            $table->id();
            
            // Número de orden único para referencia del cliente
            $table->string('order_number')->unique();
            
            // Usuario que realizó la compra (puede ser null para compras como invitado)
            $table->unsignedBigInteger('user_id')->nullable();
            
            // Información del cliente (para compras como invitado)
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone')->nullable();
            
            // Información de envío
            $table->text('shipping_address');
            $table->string('shipping_city');
            $table->string('shipping_state');
            $table->string('shipping_postal_code');
            $table->string('shipping_country')->default('Colombia');
            
            // Información de facturación (puede ser diferente al envío)
            $table->text('billing_address')->nullable();
            $table->string('billing_city')->nullable();
            $table->string('billing_state')->nullable();
            $table->string('billing_postal_code')->nullable();
            $table->string('billing_country')->nullable();
            
            // Totales de la orden
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('shipping_amount', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            
            // Cupón aplicado
            $table->string('coupon_code')->nullable();
            $table->decimal('coupon_discount', 10, 2)->default(0);
            
            // Estado de la orden
            $table->enum('status', [
                'pending',      // Pendiente de pago
                'paid',         // Pagada
                'processing',   // En procesamiento
                'shipped',      // Enviada
                'delivered',    // Entregada
                'cancelled',    // Cancelada
                'refunded'      // Reembolsada
            ])->default('pending');
            
            // Método de pago
            $table->enum('payment_method', [
                'credit_card',
                'debit_card',
                'bank_transfer',
                'cash_on_delivery',
                'digital_wallet'
            ])->nullable();
            
            // Información de pago
            $table->string('payment_reference')->nullable();
            $table->timestamp('paid_at')->nullable();
            
            // Información de envío
            $table->string('tracking_number')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            
            // Notas adicionales
            $table->text('notes')->nullable();
            $table->text('admin_notes')->nullable();
            
            // Timestamps
            $table->timestamps();
            
            // Índices para optimizar consultas
            $table->index(['user_id', 'status']);
            $table->index(['order_number']);
            $table->index(['customer_email']);
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};