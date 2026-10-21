<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | The panel calls its own API from the same origin, so only the app URL is
    | allowed. Add extra origins through CORS_ALLOWED_ORIGINS (comma separated)
    | when a real external client is introduced.
    |
    */

    'paths' => ['api/*'],

    'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],

    'allowed_origins' => array_values(array_filter(explode(',', env(
        'CORS_ALLOWED_ORIGINS',
        env('APP_URL', 'http://localhost')
    )))),

    'allowed_origins_patterns' => [],

    'allowed_headers' => [
        'Accept',
        'Authorization',
        'Content-Type',
        'X-CSRF-TOKEN',
        'X-Requested-With',
    ],

    'exposed_headers' => [],

    'max_age' => 3600,

    'supports_credentials' => false,

];
