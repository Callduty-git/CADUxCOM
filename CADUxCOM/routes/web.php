<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController; // ¡Importa tu controlador!

// ... (otras rutas que ya tengas, como la ruta de bienvenida)

Route::resource('productos', ProductoController::class);

// Opcional: Ruta de bienvenida si la has modificado o quieres volver a la original
Route::get('/', function () {
    return view('welcome');
});