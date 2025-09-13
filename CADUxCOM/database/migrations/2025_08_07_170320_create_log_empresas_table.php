<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('log_empresas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empresa_id');
            $table->string('accion');
            $table->text('descripcion')->nullable();
            $table->timestamps();

            $table->foreign('empresa_id', 'fk_log_empresa_id')
                  ->references('Id_Empresa') // 👈 CORREGIDO
                  ->on('empresas')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_empresas');
    }
};
