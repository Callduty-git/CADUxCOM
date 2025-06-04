<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\Auth\EmpresaAuthController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Rutas para usuarios normales
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Registro (único punto para usuarios y empresas)
Route::post('/register', [RegisteredUserController::class, 'store'])->name('register');

// Rutas para productos protegidas solo para empresas
Route::middleware('auth:empresa')->group(function () {
    Route::resource('/productos', ProductoController::class);
});

// Rutas para empresas — login y dashboard
Route::prefix('empresa')->name('empresa.')->group(function () {
    Route::get('login', [EmpresaAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [EmpresaAuthController::class, 'login']);
    Route::post('logout', [EmpresaAuthController::class, 'logout'])->name('logout');

    Route::middleware('auth:empresa')->group(function () {
        Route::get('dashboard', function () {
            return view('empresa.dashboard');
        })->name('dashboard');
    });
});

require __DIR__.'/auth.php';
