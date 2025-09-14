<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHoraToLogEmpresasTable extends Migration
{
    public function up(): void
    {
        Schema::table('log_empresas', function (Blueprint $table) {
            $table->timestamp('hora')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('log_empresas', function (Blueprint $table) {
            $table->dropColumn('hora');
        });
    }
}