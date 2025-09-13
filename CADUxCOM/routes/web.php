
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
use App\Http\Controllers\ContactController;
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

    // NUEVAS RUTAS PARA CAMBIO DE CONTRASEÑA
    Route::get('/empresa/cambiar-contrasena', [EmpresaPasswordController::class, 'showChangeForm'])
        ->name('empresa.password.change');
    Route::post('/empresa/cambiar-contrasena', [EmpresaPasswordController::class, 'updatePassword'])
        ->name('empresa.password.update');

    // Mostrar productos para empresa
    Route::get('/empresa/productos', [ProductoController::class, 'index'])
        ->name('empresa.productos.index');

    // CRUD de productos para empresas
    Route::resource('/productos', ProductoController::class);
    
    // Vista de detalles de producto para empresas (con opciones de edición)
    Route::get('/empresa/productos/{id}', [ProductoController::class, 'show'])->name('empresa.productos.show');

    // Ruta para eliminar cuenta de empresa
    Route::delete('/empresa/eliminar', [EmpresaController::class, 'eliminarCuenta'])
        ->name('empresa.eliminar');

    // Rutas para reglas de descuento progresivo
    Route::resource('/empresa/discount-rules', DiscountRuleController::class, [
        'as' => 'discount-rules'
    ]);
    Route::post('/empresa/discount-rules/create-defaults', [DiscountRuleController::class, 'createDefaults'])
        ->name('discount-rules.create-defaults');
    Route::patch('/empresa/discount-rules/{id}/toggle', [DiscountRuleController::class, 'toggle'])
        ->name('discount-rules.toggle');
});

/*
|--------------------------------------------------------------------------
| Rutas de facturas
|--------------------------------------------------------------------------
*/
Route::get('/empresa/facturas', [EmpresaDashboardController::class, 'facturas'])
    ->middleware(['auth:empresa'])
    ->name('empresa.facturas');

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

// Ruta pública para usuarios (sin autenticación requerida)
Route::get('/producto/{id}', [ProductoController::class, 'userShow'])->name('productos.user.show');

// Rutas de contacto
Route::get('/contacto', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contacto', [ContactController::class, 'send'])->name('contact.send');

// Ruta de about
Route::get('/about', function () {
    return view('about');
})->name('about');

// Rutas de páginas legales y de ayuda
Route::get('/ayuda', function () {
    return view('help');
})->name('help');

Route::get('/terminos', function () {
    return view('terms');
})->name('terms');

Route::get('/privacidad', function () {
    return view('privacy');
})->name('privacy');

// Registro único para usuarios y empresas
Route::post('/register', [RegisteredUserController::class, 'store'])->name('register');

// Login unificado para usuario y empresa
Route::get('/login', [CustomLoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [CustomLoginController::class, 'login']);
Route::post('/logout', [CustomLoginController::class, 'logout'])->name('logout');
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
Route::post('/coupons/validate', [CouponController::class, 'validateCoupon'])->name('coupons.validate');
Route::post('/coupons/apply', [CouponController::class, 'apply'])->name('coupons.apply');
Route::post('/coupons/remove', [CouponController::class, 'remove'])->name('coupons.remove');
Route::get('/coupons/applied', [CouponController::class, 'getApplied'])->name('coupons.applied');
Route::get('/coupons/available', [CouponController::class, 'getAvailable'])->name('coupons.available');
Route::post('/coupons/check-product', [CouponController::class, 'checkProduct'])->name('coupons.check-product');

// Rutas del sistema de wishlist (solo para usuarios autenticados)
Route::middleware('auth')->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/add', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::post('/wishlist/remove', [WishlistController::class, 'remove'])->name('wishlist.remove');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::get('/wishlist/status', [WishlistController::class, 'status'])->name('wishlist.status');
    Route::get('/wishlist/count', [WishlistController::class, 'count'])->name('wishlist.count');
    Route::delete('/wishlist/clear', [WishlistController::class, 'clear'])->name('wishlist.clear');
    Route::post('/wishlist/multiple-status', [WishlistController::class, 'getMultipleStatus'])->name('wishlist.multiple-status');
});

/*
|--------------------------------------------------------------------------
| Rutas autenticadas para usuarios
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        // Verificar si es una empresa autenticada
        if (Auth::guard('empresa')->check()) {
            return view('dashboard');
        }
        // Si no es empresa, redirigir al home
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
| Rutas de geolocalización y mapa interactivo
|--------------------------------------------------------------------------
*/
Route::get('/mapa', [GeolocationController::class, 'map'])->name('mapa');

/*
|--------------------------------------------------------------------------
| Rutas de educación sobre desperdicio de alimentos
|--------------------------------------------------------------------------
*/
Route::get('/educacion', [EducationController::class, 'index'])->name('education.index');
Route::get('/educacion/calculadora', [EducationController::class, 'impactCalculator'])->name('education.calculator');
Route::post('/educacion/calcular-impacto', [EducationController::class, 'calculateImpact'])->name('education.calculate-impact');

/*
|--------------------------------------------------------------------------
| Rutas de notificaciones - Solo para usuarios autenticados
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
| Rutas API para descuentos progresivos
|--------------------------------------------------------------------------
*/
Route::get('/api/product-discount', [DiscountRuleController::class, 'getProductDiscount'])
    ->name('api.product-discount');
Route::get('/api/empresa/{empresaId}/discounted-products', [DiscountRuleController::class, 'getDiscountedProducts'])
    ->name('api.discounted-products');

/*
|--------------------------------------------------------------------------
| Rutas API para geolocalización
|--------------------------------------------------------------------------
*/
Route::post('/api/search-nearby', [GeolocationController::class, 'searchNearby'])
    ->name('api.search-nearby');
Route::post('/api/user-location', [GeolocationController::class, 'getUserLocation'])
    ->name('api.user-location');
Route::get('/api/geolocation-stats', [GeolocationController::class, 'getStats'])
    ->name('api.geolocation-stats');

/*
|--------------------------------------------------------------------------
| Rutas API para notificaciones - Solo para usuarios autenticados
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/api/notifications/unread', [NotificationController::class, 'getUnread'])
        ->name('api.notifications.unread');
    Route::get('/api/notifications/stats', [NotificationController::class, 'getStats'])
        ->name('api.notifications.stats');
    Route::post('/api/notifications', [NotificationController::class, 'create'])
        ->name('api.notifications.create');
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
| Rutas de auth (breeze)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
