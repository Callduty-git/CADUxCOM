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
        Schema::create('comentarios', function (Blueprint $table) {
            $table->id();
            $table->text('contenido');
            $table->unsignedBigInteger('producto_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('empresa_id')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable(); // Para respuestas
            $table->timestamps();

            // Claves foráneas
            $table->foreign('producto_id')->references('Id_Producto')->on('productos')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('empresa_id')->references('Id_Empresa')->on('empresas')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('comentarios')->onDelete('cascade');

            // Índices
            $table->index(['producto_id', 'parent_id']);
            $table->index(['user_id', 'empresa_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comentarios');
    }
};
