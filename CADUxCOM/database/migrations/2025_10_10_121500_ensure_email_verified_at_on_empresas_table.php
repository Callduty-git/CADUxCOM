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
        // Solo agregar la columna si no existe en la tabla
        if (! Schema::hasColumn('empresas', 'email_verified_at')) {
            Schema::table('empresas', function (Blueprint $table) {
                $table->timestamp('email_verified_at')->nullable()->after('password');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('empresas', 'email_verified_at')) {
            Schema::table('empresas', function (Blueprint $table) {
                $table->dropColumn('email_verified_at');
            });
        }
    }
};