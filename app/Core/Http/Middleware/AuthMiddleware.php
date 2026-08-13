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
        /*
        |--------------------------------------------------------------------------
        | Utilisateur authentifié
        |--------------------------------------------------------------------------
        */

        if ($this->auth->check()) {
            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | Requête AJAX / API
        |--------------------------------------------------------------------------
        |
        | Pour AJAX ou une future API MedTrack, nous ne renvoyons pas
        | une page HTML de connexion. Le client reçoit un HTTP 401.
        |
        */

        if ($request->expectsJson()) {
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
        |--------------------------------------------------------------------------
        | Navigation web classique
        |--------------------------------------------------------------------------
        */

        header(
            'Location: /login',
            true,
            302
        );

        exit;
    }
}