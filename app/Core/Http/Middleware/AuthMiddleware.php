<?php

declare(strict_types=1);

namespace MedTrack\Core\Http\Middleware;

use MedTrack\Core\Http\Request;
use MedTrack\Core\Http\Response;
use MedTrack\Modules\Identity\Services\AuthService;

final class AuthMiddleware
{
    public function __construct(
        private readonly AuthService $auth
    ) {
    }

    public function handle(
        Request $request,
        callable $next
    ): mixed {
        if ($this->auth->check()) {
            return $next($request);
        }

        /*
         * Une requête AJAX/API reçoit une réponse JSON.
         */
        if ($this->expectsJson()) {
            Response::json(
                [
                    'status' => 'error',
                    'code' => 'UNAUTHENTICATED',
                    'message' =>
                        'Vous devez être connecté pour accéder à cette ressource.',
                ],
                401
            );
        }

        /*
         * Navigation web classique.
         */
        header(
            'Location: /login',
            true,
            302
        );

        exit;
    }

    private function expectsJson(): bool
    {
        $accept =
            $_SERVER['HTTP_ACCEPT']
            ?? '';

        $requestedWith =
            $_SERVER['HTTP_X_REQUESTED_WITH']
            ?? '';

        return str_contains(
            strtolower($accept),
            'application/json'
        )
            || strtolower($requestedWith)
                === 'xmlhttprequest';
    }
}