<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    // 'paths' => ['api/*', 'sanctum/csrf-cookie', 'csrf-token', 'login'],

    // 'allowed_methods' => ['*'],

    // 'allowed_origins' => ['*'], //http://localhost:3000

    // 'allowed_origins_patterns' => [],

    // 'allowed_headers' => ['*'],

    // 'exposed_headers' => [],

    // 'max_age' => 0,

    // 'supports_credentials' => true,

    'paths' => ['api/*', 'sanctum/csrf-cookie', 'login', 'register'],
    'allowed_methods' => ['*'],

    // lista explícita de orígenes (mejor que '*')
    'allowed_origins' => [
        'https://patatas-gourmet-frontend.vercel.app',
        'http://localhost:3000',
    ],

    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,

    // como usas orígenes concretos, aquí puedes dejar false.
    // Si algún día usas cookies/sanctum entre dominios, pon true
    // y mantén 'allowed_origins' SIN '*'.
    'supports_credentials' => false,

];
