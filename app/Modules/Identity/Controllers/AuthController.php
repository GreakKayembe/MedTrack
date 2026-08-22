<?php

declare(strict_types=1);

namespace MedTrack\Modules\Identity\Controllers;

use MedTrack\Core\Http\Request;
use MedTrack\Core\Http\Response;
use MedTrack\Core\Http\View;
use MedTrack\Core\Security\RateLimit\RateLimiter;
use MedTrack\Modules\Identity\Repositories\LoginHistoryRepository;
use MedTrack\Modules\Identity\Services\AuthService;

final class AuthController
{
    public function __construct(
        private readonly AuthService $auth,
        private readonly View $view,
        private readonly RateLimiter $rateLimiter,
        private readonly LoginHistoryRepository $loginHistory
    ) {
    }

    /**
     * Affiche le formulaire de connexion.
     */
    public function showLogin(
        Request $request
    ): string {
        if ($this->auth->check()) {
            $user = $this->auth->user();

            if (
                $user !== null
                && (bool) ($user['must_change_password'] ?? false)
            ) {
                header(
                    'Location: /change-password',
                    true,
                    302
                );

                exit;
            }

            header(
                'Location: /',
                true,
                302
            );

            exit;
        }

        return $this->view->render(
            'auth.login',
            [
                'pageTitle' => 'Connexion',
            ],
            'layouts.auth'
        );
    }

    /**
     * Traite une tentative de connexion.
     */
    public function login(
        Request $request
    ): never {
        /*
        |--------------------------------------------------------------------------
        | Credentials
        |--------------------------------------------------------------------------
        */

        $login = trim(
            (string) $request->input(
                'login',
                ''
            )
        );

        $password = (string) $request->input(
            'password',
            ''
        );

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        if ($login === '' || $password === '') {
            Response::json(
                [
                    'status' => 'error',
                    'code' => 'VALIDATION_ERROR',
                    'message' =>
                        'Identifiant et mot de passe obligatoires.',
                ],
                422
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Client information
        |--------------------------------------------------------------------------
        */

        $ipAddress = $request->ip();

        $userAgent = $request->header(
            'User-Agent'
        );

        /*
        |--------------------------------------------------------------------------
        | Rate limiting
        |--------------------------------------------------------------------------
        */

        $rateLimit = $this->rateLimiter->check(
            RateLimiter::LOGIN,
            $login,
            $ipAddress
        );

        if (!$rateLimit->allowed) {
            $this->loginHistory->recordFailure(
                null,
                $login,
                $ipAddress,
                $userAgent,
                'RATE_LIMITED'
            );

            Response::json(
                [
                    'status' => 'error',
                    'code' => 'TOO_MANY_LOGIN_ATTEMPTS',
                    'message' =>
                        'Trop de tentatives de connexion. '
                        . 'Veuillez patienter avant de réessayer.',
                    'retry_after' =>
                        $rateLimit->retryAfter,
                ],
                429
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Authentication
        |--------------------------------------------------------------------------
        */

        $authentication = $this->auth->attempt(
            $login,
            $password
        );

        /*
        |--------------------------------------------------------------------------
        | Authentication failure
        |--------------------------------------------------------------------------
        */

        if (!$authentication->successful) {
            /*
             * Journal de sécurité.
             *
             * Le motif détaillé reste exclusivement côté serveur.
             */
            $this->loginHistory->recordFailure(
                $authentication->userId,
                $login,
                $ipAddress,
                $userAgent,
                $authentication->reason
            );

            /*
             * Enregistrement de la tentative dans le rate limiter.
             */
            $this->rateLimiter->hit(
                RateLimiter::LOGIN,
                $login,
                $ipAddress
            );

            /*
             * Vérifie si cette tentative vient d'atteindre
             * la limite autorisée.
             */
            $rateLimit = $this->rateLimiter->check(
                RateLimiter::LOGIN,
                $login,
                $ipAddress
            );

            if (!$rateLimit->allowed) {
                Response::json(
                    [
                        'status' => 'error',
                        'code' => 'TOO_MANY_LOGIN_ATTEMPTS',
                        'message' =>
                            'Trop de tentatives de connexion. '
                            . 'Votre accès est temporairement limité.',
                        'retry_after' =>
                            $rateLimit->retryAfter,
                    ],
                    429
                );
            }

            /*
             * Réponse volontairement générique.
             */
            Response::json(
                [
                    'status' => 'error',
                    'code' => 'INVALID_CREDENTIALS',
                    'message' =>
                        'Identifiants incorrects ou compte indisponible.',
                ],
                401
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Authentication success
        |--------------------------------------------------------------------------
        */

        $this->loginHistory->recordSuccess(
            $authentication->userId,
            $login,
            $ipAddress,
            $userAgent
        );

        /*
         * Une authentification réussie supprime le compteur
         * d'échecs correspondant.
         */
        $this->rateLimiter->clear(
            RateLimiter::LOGIN,
            $login,
            $ipAddress
        );

        /*
        |--------------------------------------------------------------------------
        | Post-authentication destination
        |--------------------------------------------------------------------------
        |
        | L'utilisateur est maintenant authentifié.
        |
        | Nous relisons ses informations depuis la base afin de déterminer
        | s'il utilise encore un mot de passe temporaire.
        |
        */

        $user = $this->auth->user();

        $mustChangePassword =
            $user !== null
            && (bool) ($user['must_change_password'] ?? false);

        $redirect = $mustChangePassword
            ? '/change-password'
            : '/';

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        Response::json(
            [
                'status' => 'success',
                'message' => $mustChangePassword
                    ? 'Connexion réussie. Vous devez définir un nouveau mot de passe.'
                    : 'Connexion réussie.',
                'redirect' => $redirect,
                'must_change_password' => $mustChangePassword,
            ]
        );
    }

    /**
     * Déconnecte l'utilisateur.
     */
    public function logout(
        Request $request
    ): never {
        $this->auth->logout();

        header(
            'Location: /login',
            true,
            302
        );

        exit;
    }
}