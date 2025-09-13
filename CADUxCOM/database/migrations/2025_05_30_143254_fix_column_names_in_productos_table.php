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
        Schema::table('productos', function (Blueprint $table) {
            // Elimina columnas antiguas (si existen)
            $table->dropColumn(['Nombre', 'Marca', 'Tipo', 'Codigo']);

            // Agrega columnas con las longitudes deseadas
            $table->string('Nombre', 100);
            $table->string('Marca', 10);
            $table->string('Tipo', 50);
            $table->string('Codigo', 50)->unique();
        });
    }

    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            // Reversión: borra las nuevas y restaura como estaban antes (si es necesario)
            $table->dropColumn(['Nombre', 'Marca', 'Tipo', 'Codigo']);

            // Si antes tenían restricciones distintas, puedes restaurarlas aquí
            $table->string('Nombre'); // Sin longitud personalizada
            $table->string('Marca');
            $table->string('Tipo');
            $table->string('Codigo')->unique();
        });
    }
};
