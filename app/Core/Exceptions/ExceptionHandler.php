<?php

declare(strict_types=1);

namespace MedTrack\Core\Exceptions;

use MedTrack\Core\Http\Response;
use Monolog\Logger;
use Throwable;

final class ExceptionHandler
{
    public function __construct(
        private readonly Logger $logger,
        private readonly bool $debug
    ) {
    }

    public function handle(Throwable $exception): never
    {
        $this->logger->error(
            $exception->getMessage(),
            [
                'exception' => $exception::class,
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString(),
            ]
        );

        $data = [
            'status' => 'error',
            'message' => 'Une erreur interne est survenue.',
        ];

        if ($this->debug) {
            $data['debug'] = [
                'exception' => $exception::class,
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ];
        }

        Response::json($data, 500);
    }
}
