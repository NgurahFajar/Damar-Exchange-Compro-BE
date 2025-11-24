<?php

return [
    'name' => env('APP_NAME', 'Laravel'),
    'env' => env('APP_ENV', 'production'),
    'debug' => (bool) env('APP_DEBUG', false),
    'url' => env('APP_URL', 'http://localhost'),
    'timezone' => env('APP_TIMEZONE', 'UTC'),
    'locale' => env('APP_LOCALE', 'en'),
    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),
    'faker_locale' => env('APP_FAKER_LOCALE', 'en_US'),

    /*
    |--------------------------------------------------------------------------
    | Translation Language Lines
    |--------------------------------------------------------------------------
    |
    | Configuration for loading translation files and language settings
    |
    */
    'available_locales' => ['en'],
    'translation_files' => [
        'auth' => [
            'dir' => resource_path('lang'),
            'group' => 'auth',
            'namespace' => 'auth'
        ],
        'currency' => [
            'dir' => resource_path('lang'),
            'group' => 'currency',
            'namespace' => 'currency'
        ]
    ],

    'cipher' => 'AES-256-CBC',
    'key' => env('APP_KEY'),
    'previous_keys' => [
        ...array_filter(
            explode(',', env('APP_PREVIOUS_KEYS', ''))
        ),
    ],
    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],
];
