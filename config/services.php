<?php

return [
    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'stripe' => [
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    ],

    'facebook' => [
        'pixel_id' => env('FACEBOOK_PIXEL_ID'),
    ],

    'gtm' => [
        'container_id' => env('GTM_CONTAINER_ID'),
    ],

    'whatsapp' => [
        'number' => env('WHATSAPP_NUMBER', '5511999999999'),
        'message' => env('WHATSAPP_MESSAGE', 'Olá! Quero saber mais sobre o ClicaFood'),
    ],
];
