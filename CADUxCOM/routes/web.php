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
use App\Http\Controllers\DiscountRuleController;
use App\Http\Controllers\GeolocationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\EmpresaAdvancedDashboardController;
use App\Http\Controllers\EducationController;

/*
|--------------------------------------------------------------------------
| Rutas de edición de perfil de empresa y dashboard
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:empresa'])->group(function () {
    // Dashboard
    Route::get('/empresa/dashboard', [EmpresaDashboardController::class, 'index'])
        ->name('empresa.dashboard');
    Route::get('/empresa/dashboard/advanced', [EmpresaAdvancedDashboardController::class, 'index'])
        ->name('empresa.advanced-dashboard');
    Route::get('/empresa/dashboard/export', [EmpresaAdvancedDashboardController::class, 'exportData'])
        ->name('empresa.dashboard.export');

    // Perfil de empresa
    Route::get('/empresa/perfil', [EmpresaProfileController::class, 'edit'])
        ->name('empresa.perfil.edit');
    Route::put('/empresa/perfil', [EmpresaProfileController::class, 'update'])
        ->name('empresa.perfil.update');
    Route::patch('/empresa/perfil', [EmpresaProfileController::class, 'update']);

    // Cambio de contraseña
    Route::get('/empresa/cambiar-contrasena', [EmpresaPasswordController::class, 'showChangeForm'])
        ->name('empresa.password.change');
    Route::post('/empresa/cambiar-contrasena', [EmpresaPasswordController::class, 'updatePassword'])
        ->name('empresa.password.update');

    // CRUD de productos para empresas (prefijo /empresa)
    Route::get('/empresa/productos', [ProductoController::class, 'index'])->name('empresa.productos.index');
    Route::post('/empresa/productos', [ProductoController::class, 'store'])->name('empresa.productos.store');
    Route::get('/empresa/productos/create', [ProductoController::class, 'create'])->name('empresa.productos.create');
    Route::get('/empresa/productos/{producto}', [ProductoController::class, 'showEmpresa'])->name('empresa.productos.show');
    Route::get('/empresa/productos/{producto}/edit', [ProductoController::class, 'edit'])->name('empresa.productos.edit');
    Route::put('/empresa/productos/{producto}', [ProductoController::class, 'update'])->name('empresa.productos.update');
    Route::delete('/empresa/productos/{producto}', [ProductoController::class, 'destroy'])->name('empresa.productos.destroy');

    // Rutas para reglas de descuento progresivo
    Route::resource('/empresa/discount-rules', DiscountRuleController::class, [
        'as' => 'discount-rules'
    ]);
    Route::post('/empresa/discount-rules/create-defaults', [DiscountRuleController::class, 'createDefaults'])
        ->name('discount-rules.create-defaults');
    Route::patch('/empresa/discount-rules/{id}/toggle', [DiscountRuleController::class, 'toggle'])
        ->name('discount-rules.toggle');

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
| Rutas para ver logs
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
Route::get('/productos', [ProductoController::class, 'publicIndex'])->name('productos.public.index');
Route::get('/producto/{id}', [ProductoController::class, 'userShow'])->name('productos.user.show');
Route::get('/productos/subcategoria/{subcategoria}', [ProductoController::class, 'bySubcategory'])->name('productos.by-subcategory');

// Ruta pública para ver empresas
Route::get('/empresa/{id}', [App\Http\Controllers\EmpresaController::class, 'publicShow'])->name('empresa.public.show');

// Rutas adicionales para productos (compatibilidad) - ORDEN IMPORTANTE: específicas antes que genéricas
Route::get('/productos/index', [ProductoController::class, 'index'])->name('productos.index');
Route::get('/productos/create', [ProductoController::class, 'create'])->name('productos.create');
Route::post('/productos', [ProductoController::class, 'store'])->name('productos.store');
Route::get('/productos/{id}/edit', [ProductoController::class, 'edit'])->name('productos.edit');
Route::put('/productos/{id}', [ProductoController::class, 'update'])->name('productos.update');
Route::delete('/productos/{id}', [ProductoController::class, 'destroy'])->name('productos.destroy');
Route::get('/productos/{id}', [ProductoController::class, 'show'])->name('productos.show');

Route::get('/contacto', [\App\Http\Controllers\ContactController::class, 'index'])->name('contact.index');
Route::post('/contacto', [\App\Http\Controllers\ContactController::class, 'send'])->name('contact.send');

Route::get('/about', function () { return view('about'); })->name('about');
Route::get('/ayuda', function () { return view('help'); })->name('help');
Route::get('/terminos', function () { return view('terms'); })->name('terms');
Route::get('/privacidad', function () { return view('privacy'); })->name('privacy');

/*
|--------------------------------------------------------------------------
| Registro y Login
|--------------------------------------------------------------------------
*/
Route::post('/register', [RegisteredUserController::class, 'store'])->name('register');
Route::get('/login', [CustomLoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [CustomLoginController::class, 'login'])->middleware('throttle:10,1');
Route::post('/logout', [CustomLoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Navbar y subcategorias
|--------------------------------------------------------------------------
*/
Route::get('/navbar', [CategoriaController::class, 'navbar'])->name('navbar');
Route::get('/subcategorias/{id}', [CategoriaController::class, 'getSubcategorias']);

/*
|--------------------------------------------------------------------------
| Carrito de compras
|--------------------------------------------------------------------------
*/
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/cart/count', [CartController::class, 'getCount'])->name('cart.count');

/*
|--------------------------------------------------------------------------
| Checkout
|--------------------------------------------------------------------------
*/
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');

/*
|--------------------------------------------------------------------------
| Cupones
|--------------------------------------------------------------------------
*/
Route::post('/coupons/validate', [CouponController::class, 'validateCoupon'])->name('coupons.validate');
Route::post('/coupons/apply', [CouponController::class, 'apply'])->name('coupons.apply');
Route::post('/coupons/remove', [CouponController::class, 'remove'])->name('coupons.remove');
Route::get('/coupons/applied', [CouponController::class, 'getApplied'])->name('coupons.applied');
Route::get('/coupons/available', [CouponController::class, 'getAvailable'])->name('coupons.available');
Route::post('/coupons/check-product', [CouponController::class, 'checkProduct'])->name('coupons.check-product');

/*
|--------------------------------------------------------------------------
| Wishlist
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/add', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::post('/wishlist/remove', [WishlistController::class, 'remove'])->name('wishlist.remove');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::get('/wishlist/status', [WishlistController::class, 'status'])->name('wishlist.status');
    Route::get('/wishlist/count', [WishlistController::class, 'count'])->name('wishlist.count');
    Route::delete('/wishlist/clear', [WishlistController::class, 'clear'])->name('wishlist.clear');
    Route::post('/wishlist/clear', [WishlistController::class, 'clear'])->name('wishlist.clear.post');
    Route::post('/wishlist/multiple-status', [WishlistController::class, 'getMultipleStatus'])->name('wishlist.multiple-status');
});

/*
|--------------------------------------------------------------------------
| Dashboard y perfil de usuario
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        if (Auth::guard('empresa')->check()) {
            return view('dashboard');
        }
        return redirect()->route('home');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Mapa de ofertas
    Route::get('/offers-map', [App\Http\Controllers\OffersMapController::class, 'index'])->name('offers.map');
    Route::post('/api/search-nearby', [App\Http\Controllers\OffersMapController::class, 'searchNearby'])->name('api.search.nearby');

    // Órdenes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/stats', [OrderController::class, 'getStats'])->name('orders.stats');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->whereNumber('id')->name('orders.show');
    Route::post('/orders/{id}/cancel', [OrderController::class, 'cancel'])->whereNumber('id')->name('orders.cancel');
    Route::post('/orders/{id}/refund', [OrderController::class, 'requestRefund'])->whereNumber('id')->name('orders.refund');
    Route::post('/orders/{id}/reorder', [OrderController::class, 'reorder'])->whereNumber('id')->name('orders.reorder');
    Route::get('/orders/{id}/invoice', [OrderController::class, 'downloadInvoice'])->whereNumber('id')->name('orders.invoice');
    Route::post('/orders/{id}/mark-received', [OrderController::class, 'markAsReceived'])->whereNumber('id')->name('orders.mark-received');
});

/*
|--------------------------------------------------------------------------
| Geolocalización y educación
|--------------------------------------------------------------------------
*/
Route::get('/mapa', [App\Http\Controllers\OffersMapController::class, 'index'])->name('mapa');
Route::get('/educacion', [EducationController::class, 'index'])->name('education.index');
Route::get('/educacion/calculadora', [EducationController::class, 'impactCalculator'])->name('education.calculator');
Route::post('/educacion/calcular-impacto', [EducationController::class, 'calculateImpact'])->name('education.calculate-impact');

/*
|--------------------------------------------------------------------------
| Notificaciones
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/notificaciones', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notificaciones/{notification}', [NotificationController::class, 'show'])->name('notifications.show');
    Route::patch('/notificaciones/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::patch('/notificaciones/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notificaciones/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
});

/*
|--------------------------------------------------------------------------
| Logout empresa
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
| Rutas de autenticación de empresa
|--------------------------------------------------------------------------
*/
Route::middleware('guest:empresa')->group(function () {
    Route::get('/empresa/login', [\App\Http\Controllers\Auth\EmpresaAuthController::class, 'showLoginForm'])
        ->name('empresa.login');
    Route::post('/empresa/login', [\App\Http\Controllers\Auth\EmpresaAuthController::class, 'login']);
});

/*
|--------------------------------------------------------------------------
| Auth Breeze
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
