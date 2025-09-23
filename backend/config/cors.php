<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => [env('FRONTEND_URL')],
    'allowed_origins' => [
        'http://localhost:3000',  // если Nuxt dev
        'http://localhost:5173',  // Vite (Vue)
        'http://localhost:8080',  // Vue CLI
        'http://test.dennistp.beget.tech', // продакшн
        'https://test.dennistp.beget.tech',
    ],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];
