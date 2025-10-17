<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CheckTableStructure extends Command
{
    protected $signature = 'check:table-structure {table}';
    protected $description = 'Verificar la estructura de una tabla';

    public function handle()
    {
        $table = $this->argument('table');
        
        $this->info("=== ESTRUCTURA DE LA TABLA: {$table} ===");
        
        if (!Schema::hasTable($table)) {
            $this->error("La tabla {$table} no existe.");
            return;
        }
        
        $columns = Schema::getColumnListing($table);
        $this->info("Columnas encontradas:");
        foreach ($columns as $column) {
            $this->line("- {$column}");
        }
        
        // Verificar algunos registros
        $this->info("\n=== DATOS DE MUESTRA ===");
        try {
            $records = DB::table($table)->limit(3)->get();
            if ($records->count() > 0) {
                $this->info("Primeros {$records->count()} registros:");
                foreach ($records as $record) {
                    $this->line(json_encode($record, JSON_PRETTY_PRINT));
                }
            } else {
                $this->warn("No hay registros en la tabla.");
            }
        } catch (\Exception $e) {
            $this->error("Error al obtener datos: " . $e->getMessage());
        }
    }
}