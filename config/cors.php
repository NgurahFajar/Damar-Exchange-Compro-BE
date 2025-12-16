<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => array_filter(explode(',', env('CORS_ALLOWED_ORIGINS', ''))),
    'allowed_origins_patterns' => [],
    'allowed_headers' => [
        'Origin',
        'Content-Type',
        'X-Requested-With',
        'Authorization',
        'Accept',
        'X-Auth-Token',
    ],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];
