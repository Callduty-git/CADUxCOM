<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Aquí puedes registrar rutas API en el futuro
Route::middleware('api')->get('/ping', function () {
    return response()->json(['message' => 'pong']);
});
