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
            $table->id('Id_Subcategoria');
            $table->unsignedBigInteger('Id_Categoria');

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
