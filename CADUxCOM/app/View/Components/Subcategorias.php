<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Subcategoria;

class Subcategorias extends Component
{
    public $subcategorias;

    public function __construct()
    {
        // Carga todas las subcategorías
        $this->subcategorias = Subcategoria::all();
    }

    public function render()
    {
        return view('components.subcategorias');
    }
}
