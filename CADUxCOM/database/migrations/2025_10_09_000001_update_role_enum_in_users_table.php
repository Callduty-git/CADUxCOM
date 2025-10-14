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
        Schema::table('users', function (Blueprint $table) {
            // Ampliar opciones del enum para incluir 'admin'
            $table->enum('role', ['usuario', 'empresa', 'admin'])->default('usuario')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Revertir a las opciones anteriores
            $table->enum('role', ['usuario', 'empresa'])->default('usuario')->change();
        });
    }
};