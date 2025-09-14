<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    */

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'), // Por defecto usuarios
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Aquí defines cada "guard" para tu app. Tienes el guard por defecto 'web'
    | para usuarios comunes, y el guard 'empresa' para las empresas.
    |
    */

    'guards' => [
        'web' => [ // Usuarios normales
            'driver' => 'session',
            'provider' => 'users',
        ],

        'empresa' => [ // Empresas
            'driver' => 'session',
            'provider' => 'empresas',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | Cada proveedor define cómo se obtienen los usuarios desde la base de datos.
    | Para 'users' usamos el modelo User, y para 'empresas' usamos Empresa.
    |
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],

        'empresas' => [
            'driver' => 'eloquent',
            'model' => App\Models\Empresa::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Reset Settings
    |--------------------------------------------------------------------------
    |
    | Aquí defines cómo se resetean contraseñas para cada tipo de usuario.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],

        'empresas' => [
            'provider' => 'empresas',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],  

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Tiempo antes de requerir confirmación de contraseña nuevamente (en segundos).
    |
    */

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
