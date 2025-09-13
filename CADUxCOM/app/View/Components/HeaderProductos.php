<?php

namespace App\View\Components;

use Illuminate\View\Component;

class HeaderProductos extends Component
{
    public $categorias;
    public $subcategorias;

    /**
     * Create a new component instance.
     */
    public function __construct($categorias = null, $subcategorias = null)
    {
        $this->categorias = $categorias ?? collect();
        $this->subcategorias = $subcategorias ?? collect();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.header-productos');
    }
}
