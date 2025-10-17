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
        Schema::create('empresa_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empresa_id');
            $table->string('type'); // 'new_order', 'order_status_change', etc.
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable(); // Datos adicionales como order_id, etc.
            $table->boolean('read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            
            $table->foreign('empresa_id')->references('Id_Empresa')->on('empresas')->onDelete('cascade');
            $table->index(['empresa_id', 'read']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresa_notifications');
    }
};
