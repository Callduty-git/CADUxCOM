<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Categoria;
use App\Models\Subcategoria;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Puedes dejar esto vacío si no necesitas registrar servicios adicionales
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Compartir categorías y subcategorías con la vista del navbar
        View::composer('components.navbar', function ($view) {
            $view->with([
                'categorias' => Categoria::all(),
                'subcategorias' => Subcategoria::all(),
            ]);
        });
    }
}
