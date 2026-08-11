<?php

declare(strict_types=1);

return [
    'name' => $_ENV['APP_NAME'] ?? 'MedTrack',
    'environment' => $_ENV['APP_ENV'] ?? 'production',
    'debug' => filter_var(
        $_ENV['APP_DEBUG'] ?? false,
        FILTER_VALIDATE_BOOL
    ),
    'url' => $_ENV['APP_URL'] ?? 'http://127.0.0.1:8000',
];
