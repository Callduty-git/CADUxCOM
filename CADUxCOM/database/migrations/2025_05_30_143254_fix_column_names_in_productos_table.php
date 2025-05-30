<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('productos', function (Blueprint $table) {
        // Eliminar columnas con nombres incorrectos
        $table->dropColumn(['Nombre, 100', 'Marca, 10', 'Tipo, 50', 'Codigo, 50']);

        // Agregar columnas correctas
        $table->string('Nombre', 100);
        $table->string('Marca', 10);
        $table->string('Tipo', 50);
        $table->string('Codigo', 50)->unique();
    });
}

public function down()
{
    Schema::table('productos', function (Blueprint $table) {
        $table->dropColumn(['Nombre', 'Marca', 'Tipo', 'Codigo']);

        $table->string('Nombre, 100');
        $table->string('Marca, 10');
        $table->string('Tipo, 50');
        $table->string('Codigo, 50')->unique();
    });
}

};
