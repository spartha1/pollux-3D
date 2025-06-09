<?php

return [
    'paths' => ['api/*', 'models/*', 'sanctum/csrf-cookie'],
    'allowed_origins' => [env('PYTHON_SERVICE_URL', 'http://localhost:8000')],
    'allowed_methods' => ['*'],
    'allowed_headers' => ['*'],
];
