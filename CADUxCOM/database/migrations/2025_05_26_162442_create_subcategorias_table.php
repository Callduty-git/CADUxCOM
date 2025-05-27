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
        Schema::create('subcategorias', function (Blueprint $table) {
            $table->bigIncrements('Id_Subcategoria'); // ¡CAMBIO AQUÍ! De id() a bigIncrements
            $table->unsignedBigInteger('Id_Categoria'); // Mantenemos unsignedBigInteger
            $table->string('Nombre')->unique();
            $table->string('Icono')->nullable();
            $table->timestamps();
            $table->foreign('Id_Categoria')->references('Id_Categoria')->on('categorias')->onDelete('cascade');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subcategorias');
    }
};
