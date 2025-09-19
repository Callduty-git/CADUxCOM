<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategoria extends Model
{
    use HasFactory;

    protected $primaryKey = 'Id_Subcategoria'; // <-- ¡Añade o corrige esta línea!

    protected $fillable = [
        'Id_Categoria',
        'Nombre',
        'Icono',
    ];

    // Si tienes una relación con Categoría
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'Id_Categoria', 'Id_Categoria');
    }

    // Si tienes una relación con Productos (productos que pertenecen a esta subcategoría)
    public function productos()
    {
        return $this->hasMany(Producto::class, 'Id_Subcategoria', 'Id_Subcategoria');
    }
}