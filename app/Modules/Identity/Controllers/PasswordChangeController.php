<?php

declare(strict_types=1);

namespace MedTrack\Modules\Identity\Controllers;

use MedTrack\Core\Http\Request;
use MedTrack\Core\Http\Response;
use MedTrack\Core\Http\View;
use MedTrack\Modules\Identity\Services\AuthService;
use MedTrack\Modules\Identity\Services\PasswordChangeService;
use RuntimeException;

final class PasswordChangeController
{
    public function __construct(
        private readonly AuthService $auth,
        private readonly PasswordChangeService $passwordChange,
        private readonly View $view
    ) {
    }

    /**
     * Affiche le formulaire de changement de mot de passe.
     */
    public function show(
        Request $request
    ): string {
        $user = $this->auth->user();

        if ($user === null) {
            header(
                'Location: /login',
                true,
                302
            );

            exit;
        }

        return $this->view->render(
            'auth.change-password',
            [
                'pageTitle' => 'Changer le mot de passe',
                'user' => $user,
                'mustChangePassword' =>
                    (bool) ($user['must_change_password'] ?? false),
            ],
            'layouts.auth'
        );
    }

    /**
     * Enregistre le nouveau mot de passe.
     */
    public function update(
        Request $request
    ): never {
        /*
        |--------------------------------------------------------------------------
        | Authentication
        |--------------------------------------------------------------------------
        */

        $userId = $this->auth->id();

        if ($userId === null) {
            Response::json(
                [
                    'status' => 'error',
                    'code' => 'UNAUTHENTICATED',
                    'message' =>
                        'Vous devez être connecté pour effectuer cette opération.',
                ],
                401
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Input
        |--------------------------------------------------------------------------
        */

        $currentPassword = (string) $request->input(
            'current_password',
            ''
        );

        $newPassword = (string) $request->input(
            'new_password',
            ''
        );

        $passwordConfirmation = (string) $request->input(
            'password_confirmation',
            ''
        );

        /*
        |--------------------------------------------------------------------------
        | Password change
        |--------------------------------------------------------------------------
        */

        try {
            $this->passwordChange->change(
                $userId,
                $currentPassword,
                $newPassword,
                $passwordConfirmation
            );
        } catch (RuntimeException $exception) {
            Response::json(
                [
                    'status' => 'error',
                    'code' => 'PASSWORD_CHANGE_FAILED',
                    'message' => $exception->getMessage(),
                ],
                422
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Success
        |--------------------------------------------------------------------------
        */

        Response::json(
            [
                'status' => 'success',
                'message' =>
                    'Votre mot de passe a été modifié avec succès.',
                'redirect' => '/',
            ]
        );
    }
}