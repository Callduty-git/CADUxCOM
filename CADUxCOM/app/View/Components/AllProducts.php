<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AllProducts extends Component
{
    public $productos;
    public $categorias;
    public $subcategorias;

    /**
     * Create a new component instance.
     */
    public function __construct($productos, $categorias, $subcategorias)
    {
        $this->productos = $productos;
        $this->categorias = $categorias;
        $this->subcategorias = $subcategorias;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.all-products');
    }
}
