<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | Este archivo almacena las credenciales para servicios de terceros como
    | Mailgun, Postmark, AWS, Stripe, Wompi, etc. Aquí puedes definir las
    | llaves y configuraciones para que Laravel las use globalmente.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'google' => [
        'maps_api_key' => env('GOOGLE_MAPS_API_KEY'),
    ],

    // Stripe payment gateway
    'stripe' => [
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    ],

    // Wompi payment gateway
    'wompi' => [
        'public_key' => env('WOMPI_PUBLIC_KEY'),
        'private_key' => env('WOMPI_PRIVATE_KEY'),
        'integrity_secret' => env('WOMPI_INTEGRITY_SECRET'),
        // sandbox | production
        'environment' => env('WOMPI_ENV', 'sandbox'),
        // URL de retorno después del pago
        'redirect_url' => env('WOMPI_REDIRECT_URL', env('APP_URL', 'http://localhost') . '/payments/wompi/callback'),
        // Endpoint base según entorno
        'base_url' => env('WOMPI_ENV', 'sandbox') === 'production'
            ? 'https://production.wompi.co'
            : 'https://sandbox.wompi.co',
    ],

];
