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
        Schema::create('productos', function (Blueprint $table) {
            $table->bigIncrements('Id_Producto'); // ¡CAMBIO AQUÍ! De increments a bigIncrements

            // Ahora todos los IDs son BIGINT UNSIGNED, así que sus foráneas también lo serán
            $table->unsignedBigInteger('Id_Empresa');
            $table->unsignedBigInteger('Id_Subcategoria');

            $table->string('Nombre, 100');
            $table->string('Marca, 10');
            $table->date('Fecha_Caducidad')->nullable();
            $table->integer('Cantidad')->default(0)->check('Cantidad >= 0');
            $table->string('Foto')->nullable();
            $table->text('Descripcion')->nullable();
            $table->decimal('Precio', 10, 2); // Agregué el "2" para decimales, es buena práctica
            $table->string('Tipo, 50');
            $table->string('Codigo, 50')->unique();
            $table->timestamps();

            // Definición de las claves foráneas
            $table->foreign('Id_Empresa')
                  ->references('Id_Empresa')
                  ->on('empresas')
                  ->onDelete('cascade');

            $table->foreign('Id_Subcategoria')
                  ->references('Id_Subcategoria')
                  ->on('subcategorias')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
