<?php

declare(strict_types=1);

namespace MedTrack\Modules\Identity\Controllers;

use MedTrack\Core\Http\View;
use MedTrack\Modules\Identity\Services\PasswordResetService;
use RuntimeException;

final class PasswordResetController
{
    public function __construct(
        private readonly PasswordResetService $passwordReset,
        private readonly View $view
    ) {
    }

    /**
     * Affiche le formulaire "Mot de passe oublié".
     */
    public function showForgotPassword(): string
    {
        return $this->view->render(
            'auth.forgot-password',
            [
                'pageTitle' => 'Mot de passe oublié',
            ],
            'layouts.auth'
        );
    }

    /**
     * Traite la demande de réinitialisation.
     *
     * En développement, le token est retourné afin de tester
     * le parcours sans SMTP.
     *
     * À supprimer dès que le transport email sera branché.
     */
    public function sendResetLink(): array
    {
        $identifier = trim(
            (string) ($_POST['identifier'] ?? '')
        );

        if ($identifier === '') {
            http_response_code(422);

            return [
                'status' => 'error',
                'message' => 'Veuillez saisir votre adresse email.',
            ];
        }

        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;

        $token = $this->passwordReset->requestEmailReset(
            $identifier,
            $ipAddress
        );

        /*
         * Réponse publique volontairement générique.
         *
         * Ne jamais indiquer :
         * "cet email n'existe pas".
         */
        $response = [
            'status' => 'success',
            'message' =>
                'Si un compte correspond à cette adresse, '
                . 'les instructions de réinitialisation ont été envoyées.',
        ];

        /*
         * Développement local uniquement.
         *
         * Le token permet de tester le parcours avant SMTP.
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
    public function showResetPassword(): string
    {
        $token = trim(
            (string) ($_GET['token'] ?? '')
        );

        $tokenValid =
            $this->passwordReset->validateToken($token);

        return $this->view->render(
            'auth.reset-password',
            [
                'pageTitle' => 'Nouveau mot de passe',
                'token' => $token,
                'tokenValid' => $tokenValid,
            ],
            'layouts.auth'
        );
    }

    /**
     * Enregistre le nouveau mot de passe.
     */
    public function resetPassword(): array
    {
        $token = trim(
            (string) ($_POST['token'] ?? '')
        );

        $password =
            (string) ($_POST['password'] ?? '');

        $passwordConfirmation =
            (string) ($_POST['password_confirmation'] ?? '');

        if (
            $token === ''
            || $password === ''
            || $passwordConfirmation === ''
        ) {
            http_response_code(422);

            return [
                'status' => 'error',
                'message' => 'Tous les champs sont obligatoires.',
            ];
        }

        if ($password !== $passwordConfirmation) {
            http_response_code(422);

            return [
                'status' => 'error',
                'message' =>
                    'La confirmation du mot de passe ne correspond pas.',
            ];
        }

        try {
            $this->passwordReset->resetPassword(
                $token,
                $password
            );
        } catch (RuntimeException $exception) {
            http_response_code(422);

            return [
                'status' => 'error',
                'message' => $exception->getMessage(),
            ];
        }

        return [
            'status' => 'success',
            'message' =>
                'Votre mot de passe a été réinitialisé avec succès.',
            'redirect' => '/login',
        ];
    }
}
