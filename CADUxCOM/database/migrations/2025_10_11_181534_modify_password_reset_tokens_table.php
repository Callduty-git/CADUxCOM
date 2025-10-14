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
        Schema::table('password_reset_tokens', function (Blueprint $table) {
            // Agregar columnas si no existen
            if (!Schema::hasColumn('password_reset_tokens', 'expires_at')) {
                $table->timestamp('expires_at')->nullable()->after('created_at');
            }
            
            if (!Schema::hasColumn('password_reset_tokens', 'used')) {
                $table->boolean('used')->default(false)->after('expires_at');
            }
            
            if (!Schema::hasColumn('password_reset_tokens', 'type')) {
                $table->string('type')->default('password_reset')->after('used');
            }
            
            // Agregar índice si no existe
            if (!Schema::hasIndex('password_reset_tokens', 'password_reset_tokens_email_index')) {
                $table->index('email');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('password_reset_tokens', function (Blueprint $table) {
            $table->dropColumn(['expires_at', 'used', 'type']);
            $table->dropIndex('password_reset_tokens_email_index');
        });
    }
};