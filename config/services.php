<?php

return [

    'mailgun' => [
        'domain'   => env('MAILGUN_DOMAIN'),
        'secret'   => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme'   => 'https',
    ],

    'stripe' => [
        'model'            => App\Models\User::class,
        'key'              => env('STRIPE_KEY'),
        'secret'           => env('STRIPE_SECRET'),
        'webhook_secret'   => env('STRIPE_WEBHOOK_SECRET'),
        'monthly_price_id' => env('STRIPE_MONTHLY_PRICE_ID'),
        'annual_price_id'  => env('STRIPE_ANNUAL_PRICE_ID'),
    ],

    'ses' => [
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

];
