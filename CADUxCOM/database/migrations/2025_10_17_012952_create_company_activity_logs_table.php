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
        Schema::create('company_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->index();
            $table->string('action', 100)->index(); // register, profile_update, product_create, product_update, product_delete, status_change, etc.
            $table->string('description', 500)->nullable();
            $table->json('data')->nullable(); // Additional data related to the action
            $table->unsignedBigInteger('user_id')->nullable()->index(); // Admin who performed the action (if applicable)
            $table->string('ip_address', 45)->nullable()->index();
            $table->text('user_agent')->nullable();
            $table->string('url', 500)->nullable();
            $table->string('method', 10)->nullable();
            $table->timestamps();

            $table->foreign('company_id')->references('Id_Empresa')->on('empresas')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->index(['action', 'created_at']);
            $table->index(['company_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_activity_logs');
    }
};
