<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Esta migración agrega campos de geolocalización a la tabla empresas
     * para permitir la funcionalidad de mapa interactivo y búsqueda por proximidad.
     */
    public function up(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            // Coordenadas geográficas
            $table->decimal('latitude', 10, 8)->nullable()->after('Direccion');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            
            // Información de ubicación adicional
            $table->string('city')->nullable()->after('longitude');
            $table->string('state')->nullable()->after('city');
            $table->string('postal_code')->nullable()->after('state');
            $table->string('country')->default('Colombia')->after('postal_code');
            
            // Radio de cobertura en kilómetros
            $table->integer('coverage_radius')->default(10)->after('country');
            
            // Estado de verificación de ubicación
            $table->boolean('location_verified')->default(false)->after('coverage_radius');
            
            // Timestamp de última actualización de ubicación
            $table->timestamp('location_updated_at')->nullable()->after('location_verified');
            
            // Índices para optimizar consultas geográficas
            $table->index(['latitude', 'longitude']);
            $table->index(['city', 'state']);
            $table->index('coverage_radius');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropIndex(['latitude', 'longitude']);
            $table->dropIndex(['city', 'state']);
            $table->dropIndex(['coverage_radius']);
            
            $table->dropColumn([
                'latitude',
                'longitude',
                'city',
                'state',
                'postal_code',
                'country',
                'coverage_radius',
                'location_verified',
                'location_updated_at'
            ]);
        });
    }
};