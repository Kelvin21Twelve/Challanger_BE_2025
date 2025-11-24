<?php

return [

    'cors_profile' => Spatie\Cors\CorsProfile\DefaultProfile::class,

    'default_profile' => [

        'allow_credentials' => false,

        'allow_origins' => [
            'http://localhost:3000',
            'http://127.0.0.1:3000',
            '*' // keep if needed
        ],

        'allow_methods' => [
            'POST',
            'GET',
            'OPTIONS',
            'PUT',
            'PATCH',
            'DELETE',
        ],

        'allow_headers' => [
            '*',
        ],

        'expose_headers' => [
            '*',
        ],

        'forbidden_response' => [
            'message' => 'Forbidden (cors).',
            'status' => 403,
        ],

        'max_age' => 60 * 60 * 24,
    ],
];
