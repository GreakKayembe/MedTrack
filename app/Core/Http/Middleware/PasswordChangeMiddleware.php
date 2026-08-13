<?php

declare(strict_types=1);

namespace MedTrack\Core\Http\Middleware;

use MedTrack\Core\Http\Request;
use MedTrack\Core\Http\Response;
use MedTrack\Modules\Identity\Services\AuthService;

final class PasswordChangeMiddleware
{
    public function __construct(
        private readonly AuthService $auth
    ) {
    }

    /**
     * Empêche un utilisateur authentifié utilisant encore
     * un mot de passe temporaire d'accéder aux ressources
     * protégées de l'application.
     */
    public function handle(
        Request $request,
        callable $next
    ): mixed {
        /*
        |--------------------------------------------------------------------------
        | Authentication
        |--------------------------------------------------------------------------
        |
        | Ce middleware n'a pas pour responsabilité principale
        | de gérer l'authentification.
        |
        | AuthMiddleware doit normalement être exécuté avant lui.
        |
        */

        if (!$this->auth->check()) {
            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | Current user
        |--------------------------------------------------------------------------
        */

        $user = $this->auth->user();

        if ($user === null) {
            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | Password change requirement
        |--------------------------------------------------------------------------
        */

        $mustChangePassword = (bool) (
            $user['must_change_password']
            ?? false
        );

        if (!$mustChangePassword) {
            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | Allow password change route
        |--------------------------------------------------------------------------
        |
        | Il est impératif de laisser l'utilisateur accéder à cette
        | route, sinon nous créerions une boucle de redirection.
        |
        */

        if ($request->path() === '/change-password') {
            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | AJAX / API request
        |--------------------------------------------------------------------------
        */

        if ($this->expectsJson($request)) {
            Response::json(
                [
                    'status' => 'error',
                    'code' => 'PASSWORD_CHANGE_REQUIRED',
                    'message' =>
                        'Vous devez modifier votre mot de passe '
                        . 'avant de continuer.',
                    'redirect' => '/change-password',
                ],
                403
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Browser navigation
        |--------------------------------------------------------------------------
        */

        header(
            'Location: /change-password',
            true,
            302
        );

        exit;
    }

    /**
     * Détermine si la requête attend une réponse JSON.
     */
    private function expectsJson(
        Request $request
    ): bool {
        $accept = strtolower(
            (string) $request->header(
                'Accept'
            )
        );

        $requestedWith = strtolower(
            (string) $request->header(
                'X-Requested-With'
            )
        );

        return str_contains(
            $accept,
            'application/json'
        ) || $requestedWith === 'xmlhttprequest';
    }
}