<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\NotificationController;

// Endpoints REST para wishlist (protegidos con auth:sanctum)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'index']); // Listar wishlist
    Route::post('/wishlist', [WishlistController::class, 'add']); // Agregar producto
    Route::delete('/wishlist', [WishlistController::class, 'clear']); // Limpiar wishlist
    Route::delete('/wishlist/{product_id}', [WishlistController::class, 'remove']); // Quitar producto
    Route::get('/wishlist/count', [WishlistController::class, 'count']); // Contador
});

// Endpoints REST para productos (protegidos con auth:sanctum y rate limiting)
Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    Route::get('/productos', [ProductoController::class, 'index']); // Listar productos
    Route::get('/productos/{id}', [ProductoController::class, 'show']); // Ver detalle
    // Puedes agregar POST, PUT, DELETE según permisos

    // Endpoints REST para notificaciones (protegidos con rate limiting)
    Route::get('/notificaciones', [NotificationController::class, 'index']); // Listar notificaciones
    Route::patch('/notificaciones/{notification}/read', [NotificationController::class, 'markAsRead']); // Marcar como leída
    Route::delete('/notificaciones/{notification}', [NotificationController::class, 'destroy']); // Eliminar
});

// Endpoints REST para órdenes (protegidos con auth:sanctum)
Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    Route::get('/orders', [\App\Http\Controllers\OrderController::class, 'index']); // Listar órdenes
    Route::get('/orders/{id}', [\App\Http\Controllers\OrderController::class, 'show']); // Ver detalle
});

// Puedes agregar más endpoints REST aquí para productos, notificaciones, etc.

// Endpoint de prueba
Route::middleware('api')->get('/ping', function () {
    return response()->json(['message' => 'pong']);
});
