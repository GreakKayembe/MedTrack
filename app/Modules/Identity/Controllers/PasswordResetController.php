<?php

declare(strict_types=1);

namespace MedTrack\Modules\Identity\Controllers;

use MedTrack\Core\Http\Request;
use MedTrack\Core\Http\View;
use MedTrack\Core\Security\RateLimit\RateLimiter;
use MedTrack\Modules\Identity\Services\PasswordResetService;
use RuntimeException;

final class PasswordResetController
{
    public function __construct(
        private readonly PasswordResetService $passwordReset,
        private readonly View $view,
        private readonly RateLimiter $rateLimiter
    ) {
    }

    /**
     * Affiche le formulaire "Mot de passe oublié".
     */
    public function showForgotPassword(
        Request $request
    ): string {
        return $this->view->render(
            'auth.forgot-password',
            [
                'pageTitle' => 'Mot de passe oublié',
            ],
            'layouts.auth'
        );
    }

    /**
     * Traite une demande de réinitialisation.
     *
     * En développement local, le token peut être retourné
     * afin de tester le parcours sans serveur SMTP.
     */
    public function sendResetLink(
        Request $request
    ): array {
        /*
        |--------------------------------------------------------------------------
        | Identifier
        |--------------------------------------------------------------------------
        */

        $identifier = trim(
            (string) $request->input(
                'identifier',
                ''
            )
        );

        if ($identifier === '') {
            http_response_code(422);

            return [
                'status' => 'error',
                'code' => 'VALIDATION_ERROR',
                'message' =>
                    'Veuillez saisir votre adresse email.',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Client IP
        |--------------------------------------------------------------------------
        */

        $ipAddress = $request->ip();

        /*
        |--------------------------------------------------------------------------
        | Rate limiting
        |--------------------------------------------------------------------------
        */

        $rateLimit = $this->rateLimiter->check(
            RateLimiter::PASSWORD_RESET,
            $identifier,
            $ipAddress
        );

        if (!$rateLimit->allowed) {
            http_response_code(429);

            return [
                'status' => 'error',
                'code' => 'TOO_MANY_PASSWORD_RESET_ATTEMPTS',
                'message' =>
                    'Trop de demandes de réinitialisation. '
                    . 'Veuillez patienter avant de réessayer.',
                'retry_after' =>
                    $rateLimit->retryAfter,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Password reset request
        |--------------------------------------------------------------------------
        */

        $token = $this->passwordReset->requestEmailReset(
            $identifier,
            $ipAddress
        );

        /*
         * Toute demande valide au niveau HTTP est comptabilisée,
         * que l'adresse corresponde ou non à un utilisateur.
         *
         * Cela évite également de révéler l'existence d'un compte.
         */
        $this->rateLimiter->hit(
            RateLimiter::PASSWORD_RESET,
            $identifier,
            $ipAddress
        );

        /*
        |--------------------------------------------------------------------------
        | Public response
        |--------------------------------------------------------------------------
        |
        | La réponse reste volontairement générique.
        |
        | Ne jamais répondre :
        | "Cet email n'existe pas."
        |
        */

        $response = [
            'status' => 'success',
            'message' =>
                'Si un compte correspond à cette adresse, '
                . 'les instructions de réinitialisation ont été envoyées.',
        ];

        /*
        |--------------------------------------------------------------------------
        | Local development
        |--------------------------------------------------------------------------
        |
        | À supprimer lorsque le transport email sera branché
        | en production.
        |
        */

        if ($token !== null) {
            $response['development_reset_url'] =
                '/reset-password?token='
                . urlencode($token);
        }

        return $response;
    }

    /**
     * Affiche le formulaire de nouveau mot de passe.
     */
    public function showResetPassword(
        Request $request
    ): string {
        $token = trim(
            (string) $request->query(
                'token',
                ''
            )
        );

        $tokenValid =
            $this->passwordReset->validateToken(
                $token
            );

        return $this->view->render(
            'auth.reset-password',
            [
                'pageTitle' =>
                    'Nouveau mot de passe',

                'token' =>
                    $token,

                'tokenValid' =>
                    $tokenValid,
            ],
            'layouts.auth'
        );
    }

    /**
     * Enregistre le nouveau mot de passe.
     */
    public function resetPassword(
        Request $request
    ): array {
        /*
        |--------------------------------------------------------------------------
        | Input
        |--------------------------------------------------------------------------
        */

        $token = trim(
            (string) $request->input(
                'token',
                ''
            )
        );

        $password = (string) $request->input(
            'password',
            ''
        );

        $passwordConfirmation =
            (string) $request->input(
                'password_confirmation',
                ''
            );

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        if (
            $token === ''
            || $password === ''
            || $passwordConfirmation === ''
        ) {
            http_response_code(422);

            return [
                'status' => 'error',
                'code' => 'VALIDATION_ERROR',
                'message' =>
                    'Tous les champs sont obligatoires.',
            ];
        }

        if ($password !== $passwordConfirmation) {
            http_response_code(422);

            return [
                'status' => 'error',
                'code' => 'PASSWORD_CONFIRMATION_MISMATCH',
                'message' =>
                    'La confirmation du mot de passe '
                    . 'ne correspond pas.',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Password reset
        |--------------------------------------------------------------------------
        */

        try {
            $this->passwordReset->resetPassword(
                $token,
                $password
            );
        } catch (RuntimeException $exception) {
            http_response_code(422);

            return [
                'status' => 'error',
                'code' => 'PASSWORD_RESET_FAILED',
                'message' =>
                    $exception->getMessage(),
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Success
        |--------------------------------------------------------------------------
        */

        return [
            'status' => 'success',
            'message' =>
                'Votre mot de passe a été '
                . 'réinitialisé avec succès.',
            'redirect' => '/login',
        ];
    }
}