<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogEmpresa extends Model
{
    // Nombre de la tabla en la base de datos
    protected $table = 'log_empresas';

    // Laravel no manejará automáticamente created_at y updated_at
    public $timestamps = false;

    // Campos que se pueden asignar de forma masiva
    protected $fillable = [
        'mensaje',
        'empresa_id',
        'accion',
        'descripcion',
    ];


    /**
     * Relación: un log pertenece a una empresa
     */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    /**
     * Boot method para setear la hora automáticamente al crear un log
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->hora)) {
                $model->hora = now();
            }
        });
    }
}
