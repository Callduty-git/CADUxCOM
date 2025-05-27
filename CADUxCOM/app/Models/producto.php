<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $primaryKey = 'Id_Producto'; // <-- ¡Añade o corrige esta línea!

    protected $fillable = [
        'Nombre',
        'Marca',
        'Fecha_Caducidad',
        'Cantidad',
        'Foto',
        'Descripcion',
        'Precio',
        'Tipo',
        'Codigo',
        'Id_Empresa',
        'Id_Subcategoria',
    ];

    // Define la relación con la Empresa
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'Id_Empresa', 'Id_Empresa');
        // Primer 'Id_Empresa': clave foránea en la tabla 'productos'
        // Segundo 'Id_Empresa': clave primaria en la tabla 'empresas' (la referenciada)
    }

    // Define la relación con la Subcategoria
    public function subcategoria()
    {
        return $this->belongsTo(Subcategoria::class, 'Id_Subcategoria', 'Id_Subcategoria');
        // Primer 'Id_Subcategoria': clave foránea en la tabla 'productos'
        // Segundo 'Id_Subcategoria': clave primaria en la tabla 'subcategorias'
    }
}