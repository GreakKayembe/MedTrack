<?php

declare(strict_types=1);

namespace MedTrack\Core\Http\Middleware;

use MedTrack\Core\Http\Request;
use MedTrack\Modules\Identity\Services\AuthService;

final class GuestMiddleware
{
    public function __construct(
        private readonly AuthService $auth
    ) {
    }

    public function handle(
        Request $request,
        callable $next
    ): mixed {
        if (!$this->auth->check()) {
            return $next($request);
        }

        header(
            'Location: /',
            true,
            302
        );

        exit;
    }
}