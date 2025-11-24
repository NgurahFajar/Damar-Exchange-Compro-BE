<?php

return [
    'token' => [
        'prefix' => env('TOKEN_PREFIX', 'damar_token_'),
        'expiration' => env('TOKEN_EXPIRATION', 1440), // 24 hours
    ],
    'admin' => [
        'user_id' => env('ADMIN_USER_ID', 1),
    ],
];
