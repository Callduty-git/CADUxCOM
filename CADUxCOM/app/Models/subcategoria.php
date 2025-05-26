<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategoria extends Model
{
    use HasFactory;

    protected $table = 'subcategorias';
    protected $primaryKey = 'Id_Subcategoria';
    protected $fillable = ['Nombre', 'Icono', 'Id_Categoria'];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'Id_Categoria');
    }

    public function productos()
    {
        return $this->hasMany(Producto::class, 'Id_Subcategoria');
    }
}