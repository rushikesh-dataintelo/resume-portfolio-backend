<?php

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    // Use comma-separated FRONTEND_URLS environment variable (falls back to sensible defaults)
    'allowed_origins' => array_filter(array_map('trim', explode(',', env('FRONTEND_URLS', 'http://localhost:5173,http://127.0.0.1:5173,http://localhost:3000,http://127.0.0.1:3000,https://resume-portfolio-platform.vercel.app')))),

    // Allow any vercel.app preview subdomain (e.g. my-site-pr-123.vercel.app)
    'allowed_origins_patterns' => [
        '/^https?:\/\/([a-zA-Z0-9-]+\.)*vercel\.app$/'
    ],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,
];
