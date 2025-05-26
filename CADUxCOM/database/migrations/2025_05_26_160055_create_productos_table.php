<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id('Id_Producto');

            $table->unsignedBigInteger('Id_Empresa');
            $table->unsignedBigInteger('Id_Subcategoria');

            $table->string('Nombre');
            $table->string('Marca')->nullable();
            $table->date('Fecha_Caducidad')->nullable();
            $table->integer('Cantidad');
            $table->string('Foto')->nullable();
            $table->text('Descripcion')->nullable();
            $table->decimal('Precio', 8, 2);
            $table->string('Tipo')->nullable(); // Opcional: puedes usarlo para tipo de empaque, etc.
            $table->string('Codigo')->unique();
            $table->timestamps();

            // Llaves foráneas
            $table->foreign('Id_Empresa')->references('id')->on('empresas')->onDelete('cascade');
            $table->foreign('Id_Subcategoria')->references('Id_Subcategoria')->on('subcategorias')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};