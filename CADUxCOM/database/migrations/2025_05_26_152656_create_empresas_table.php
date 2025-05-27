<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->bigIncrements("Id_Empresa"); // ¡CAMBIO AQUÍ! De increments a bigIncrements
            $table->string('Nombre');
            $table->string('Foto')->nullable();
            $table->string('Direccion');
            $table->string('Municipio');
            $table->string('Ubicacion')->nullable();
            $table->string('Contacto');
            $table->string('email')->unique();
            $table->string('NIT')->unique();
            $table->string('Certificado_Camara_de_comercio')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
