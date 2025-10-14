<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Agrega 'sandbox' al ENUM de status y lo deja como default
        DB::statement("ALTER TABLE empresas MODIFY status ENUM('pending','sandbox','approved','rejected') NOT NULL DEFAULT 'sandbox'");
    }

    public function down(): void
    {
        // Revierte al ENUM original sin 'sandbox'
        DB::statement("ALTER TABLE empresas MODIFY status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending'");
    }
};