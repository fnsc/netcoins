<?php

return [
    'driver' => env('CRYPTO_DRIVER', 'coingecko'),

    'providers' => [
        'coingecko' => [
            'base_url' => env('CRYPTO_PROVIDER_BASE_URL', ''),
            'available_endpoints' => [
                'list' => env('CRYPTO_PROVIDER_AVAILABLE_ENDPOINTS_LIST', ''),
                'price' => env('CRYPTO_PROVIDER_AVAILABLE_ENDPOINTS_PRICE', ''),
            ],
        ],
    ],

    'supported_crypto_currencies' => [
        'bitcoin',
        'litecoin',
        'ethereum',
    ],

    'available_currencies' => [
        'usd',
    ],
];
