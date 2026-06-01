<?php

return [
    'paths' => ['api/*', 'login', 'logout', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    // Izinkan alamat asal dari kp-frontend kamu
    'allowed_origins' => ['http://localhost:8000', 'http://127.0.0.1:8000'],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,
];
