<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $primaryKey = 'Id_Categoria'; // <-- ¡Añade o corrige esta línea!

    protected $fillable = [
        'Nombre',
        'Icono',
    ];

    // Si tienes una relación con Subcategorías
    public function subcategorias()
    {
        return $this->hasMany(Subcategoria::class, 'Id_Categoria', 'Id_Categoria');
    }
}