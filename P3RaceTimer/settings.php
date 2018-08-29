<?php
// Load .env file
$envPath = __DIR__ . '/../';
if (file_exists($envPath . '.env')) {
    $dotenv = new Dotenv\Dotenv($envPath);
    $dotenv->load();
}

return [
    'settings' => [
        // Slim Settings
        'determineRouteBeforeAppMiddleware' => true,
        'displayErrorDetails' => true,
        'p3Socket' => [
            'host' => getenv('P3_HOST') ?: '127.0.0.1:5403'
        ],
        'eventSocket' => [
            'port' => getenv('EVENT_PORT') ?: '6000'
        ],
        'webSocket' => [
            'port' => getenv('EVENT_PORT') ?: '8080'
        ]
    ]
];
