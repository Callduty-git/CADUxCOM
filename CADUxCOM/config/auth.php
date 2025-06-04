<?php

return [

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'), // Por defecto usuarios
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

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

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
