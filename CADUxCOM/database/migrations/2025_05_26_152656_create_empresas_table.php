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
        Schema::create('empresas', function (Blueprint $table) {
            $table->id('Id_Empresa');
            $table->string('Nombre');
            $table->string('Foto')->nullable();
            $table->text('Direccion');
            $table->string('Municipio');
            $table->string('Ubicacion');
            $table->string('Contacto');
            $table->string('email')->unique();
            $table->string('NIT')->unique();
            $table->string('Certificado_Camara_de_comercio')->nullable();
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};