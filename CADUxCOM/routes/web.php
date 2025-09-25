
<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\CustomLoginController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\EmpresaDashboardController;
use App\Http\Controllers\empresa\LogEmpresaController;
use App\Http\Controllers\EmpresaProfileController;
use App\Http\Controllers\empresa\EmpresaPasswordController;
use App\Http\Controllers\EmpresaController;

/*
|--------------------------------------------------------------------------
| Rutas de edición de perfil de empresa y dashboard
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:empresa'])->group(function () {
    // Dashboard
    Route::get('/empresa/dashboard', [EmpresaDashboardController::class, 'index'])
        ->name('empresa.dashboard');

    // Perfil de empresa
    Route::put('/empresa/perfil', [EmpresaProfileController::class, 'update'])
        ->name('empresa.perfil.update');
    Route::patch('/empresa/perfil', [EmpresaProfileController::class, 'update']);

    // NUEVAS RUTAS PARA CAMBIO DE CONTRASEÑA
    Route::get('/empresa/cambiar-contrasena', [EmpresaPasswordController::class, 'showChangeForm'])
        ->name('empresa.password.change');
    Route::post('/empresa/cambiar-contrasena', [EmpresaPasswordController::class, 'updatePassword'])
        ->name('empresa.password.update');

    // Mostrar productos para empresa
    Route::get('/empresa/productos', [ProductoController::class, 'index'])
        ->name('empresa.productos.index');
    
    // Ver producto específico para empresa
    Route::get('/empresa/productos/{id}', [ProductoController::class, 'showEmpresa'])
        ->name('empresa.productos.show');

    // CRUD de productos
    Route::resource('/productos', ProductoController::class);

    // Ruta para eliminar cuenta de empresa
    Route::delete('/empresa/eliminar', [EmpresaController::class, 'eliminarCuenta'])
        ->name('empresa.eliminar');
});

/*
|--------------------------------------------------------------------------
| Rutas de facturas
|--------------------------------------------------------------------------
*/
Route::get('/empresa/facturas', [EmpresaDashboardController::class, 'facturas'])
    ->middleware(['auth:empresa'])
    ->name('empresa.facturas');

Route::delete('/empresa/facturas/clear-logs', [EmpresaDashboardController::class, 'clearLogs'])
    ->middleware(['auth:empresa'])
    ->name('empresa.facturas.clear-logs');

/*
|--------------------------------------------------------------------------
| Nueva ruta para ver logs en la vista factura.blade.php
|--------------------------------------------------------------------------
*/
Route::get('/empresa/logs', [LogEmpresaController::class, 'index'])
    ->middleware(['auth:empresa'])
    ->name('empresa.logs');

/*
|--------------------------------------------------------------------------
| Rutas públicas
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

// Ruta pública para ver todos los productos
Route::get('/productos', [ProductoController::class, 'publicIndex'])->name('productos.public.index');

// Ruta pública para ver un producto específico
Route::get('/productos/{id}', [ProductoController::class, 'show'])->name('productos.show');

// Registro único para usuarios y empresas
Route::post('/register', [RegisteredUserController::class, 'store'])->name('register');

// Login unificado para usuario y empresa
Route::get('/login', [CustomLoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [CustomLoginController::class, 'login']);
Route::post('/logout', [CustomLoginController::class, 'logout'])->name('logout');

// Rutas de verificación de email
Route::get('/verify-email/{id}/{hash}', [\App\Http\Controllers\Auth\UserEmailVerificationController::class, 'verify'])
    ->name('verification.verify');
Route::post('/resend-verification', [\App\Http\Controllers\Auth\UserEmailVerificationController::class, 'resend'])
    ->name('verification.resend');
Route::get('/navbar', [CategoriaController::class, 'navbar'])->name('navbar');
Route::get('/subcategorias/{id}', [CategoriaController::class, 'getSubcategorias']);

// Rutas del carrito de compras
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/cart/count', [CartController::class, 'getCount'])->name('cart.count');

// Rutas del sistema de checkout
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');

// Rutas del sistema de cupones
Route::post('/coupons/validate', [CouponController::class, 'validate'])->name('coupons.validate');
Route::post('/coupons/apply', [CouponController::class, 'apply'])->name('coupons.apply');
Route::post('/coupons/remove', [CouponController::class, 'remove'])->name('coupons.remove');
Route::get('/coupons/applied', [CouponController::class, 'getApplied'])->name('coupons.applied');
Route::get('/coupons/available', [CouponController::class, 'getAvailable'])->name('coupons.available');
Route::post('/coupons/check-product', [CouponController::class, 'checkProduct'])->name('coupons.check-product');

// Rutas del sistema de wishlist
Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
Route::post('/wishlist/add', [WishlistController::class, 'add'])->name('wishlist.add');
Route::post('/wishlist/remove', [WishlistController::class, 'remove'])->name('wishlist.remove');
Route::post('/wishlist/update', [WishlistController::class, 'update'])->name('wishlist.update');
Route::post('/wishlist/move', [WishlistController::class, 'move'])->name('wishlist.move');
Route::post('/wishlist/add-all-to-cart', [WishlistController::class, 'addAllToCart'])->name('wishlist.add-all-to-cart');
Route::post('/wishlist/clear', [WishlistController::class, 'clear'])->name('wishlist.clear');
Route::get('/wishlist/count', [WishlistController::class, 'getCount'])->name('wishlist.count');

/*
|--------------------------------------------------------------------------
| Rutas autenticadas para usuarios
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard para usuarios autenticados (no empresas): redirige al home
    Route::get('/dashboard', function () {
        return redirect()->route('home');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas del sistema de órdenes (requieren autenticación)
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('/orders/{id}/refund', [OrderController::class, 'requestRefund'])->name('orders.refund');
    Route::post('/orders/{id}/reorder', [OrderController::class, 'reorder'])->name('orders.reorder');
    Route::get('/orders/{id}/invoice', [OrderController::class, 'downloadInvoice'])->name('orders.invoice');
    Route::get('/orders/stats', [OrderController::class, 'getStats'])->name('orders.stats');
    Route::post('/orders/{id}/mark-received', [OrderController::class, 'markAsReceived'])->name('orders.mark-received');
});

/*
|--------------------------------------------------------------------------
| Logout para empresa (solo POST)
|--------------------------------------------------------------------------
*/
Route::post('/empresa/logout', function () {
    Auth::guard('empresa')->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('empresa.logout');

/*
|--------------------------------------------------------------------------
| Rutas de administración de empresas
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/empresas/pending', [\App\Http\Controllers\Admin\EmpresaVerificationController::class, 'index'])
        ->name('empresas.pending');
    Route::get('/empresas/{empresa}', [\App\Http\Controllers\Admin\EmpresaVerificationController::class, 'show'])
        ->name('empresas.show');
    Route::post('/empresas/{empresa}/approve', [\App\Http\Controllers\Admin\EmpresaVerificationController::class, 'approve'])
        ->name('empresas.approve');
    Route::post('/empresas/{empresa}/reject', [\App\Http\Controllers\Admin\EmpresaVerificationController::class, 'reject'])
        ->name('empresas.reject');
    Route::get('/empresas/approved', [\App\Http\Controllers\Admin\EmpresaVerificationController::class, 'approved'])
        ->name('empresas.approved');
    Route::get('/empresas/rejected', [\App\Http\Controllers\Admin\EmpresaVerificationController::class, 'rejected'])
        ->name('empresas.rejected');
    Route::get('/empresas/{empresa}/certificado', [\App\Http\Controllers\Admin\EmpresaVerificationController::class, 'downloadCertificado'])
        ->name('empresas.certificado');
    Route::get('/empresas/{empresa}/foto', [\App\Http\Controllers\Admin\EmpresaVerificationController::class, 'viewFoto'])
        ->name('empresas.foto');
});

/*
|--------------------------------------------------------------------------
| Rutas de auth (breeze)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
