<?php

declare(strict_types=1);

namespace MedTrack\Modules\Identity\Services;

use MedTrack\Modules\Identity\Repositories\UserRepository;
use RuntimeException;

final class PasswordChangeService
{
    public function __construct(
        private readonly UserRepository $users
    ) {
    }

    /**
     * Change le mot de passe d'un utilisateur authentifié.
     */
    public function change(
        int $userId,
        string $currentPassword,
        string $newPassword,
        string $confirmation
    ): void {
        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        if (
            $currentPassword === ''
            || $newPassword === ''
            || $confirmation === ''
        ) {
            throw new RuntimeException(
                'Tous les champs sont obligatoires.'
            );
        }

        if ($newPassword !== $confirmation) {
            throw new RuntimeException(
                'La confirmation du nouveau mot de passe ne correspond pas.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Password policy
        |--------------------------------------------------------------------------
        */

        if (mb_strlen($newPassword, 'UTF-8') < 12) {
            throw new RuntimeException(
                'Le nouveau mot de passe doit contenir au moins 12 caractères.'
            );
        }

        if (!preg_match('/[A-Z]/', $newPassword)) {
            throw new RuntimeException(
                'Le nouveau mot de passe doit contenir au moins une lettre majuscule.'
            );
        }

        if (!preg_match('/[a-z]/', $newPassword)) {
            throw new RuntimeException(
                'Le nouveau mot de passe doit contenir au moins une lettre minuscule.'
            );
        }

        if (!preg_match('/[0-9]/', $newPassword)) {
            throw new RuntimeException(
                'Le nouveau mot de passe doit contenir au moins un chiffre.'
            );
        }

        if (!preg_match('/[^A-Za-z0-9]/', $newPassword)) {
            throw new RuntimeException(
                'Le nouveau mot de passe doit contenir au moins un caractère spécial.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | User
        |--------------------------------------------------------------------------
        */

        $user = $this->users->findForAuthentication(
            $userId
        );

        if ($user === null) {
            throw new RuntimeException(
                'Utilisateur introuvable.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Current password
        |--------------------------------------------------------------------------
        */

        if (
            !password_verify(
                $currentPassword,
                $user['password_hash']
            )
        ) {
            throw new RuntimeException(
                'Le mot de passe actuel est incorrect.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Prevent password reuse
        |--------------------------------------------------------------------------
        */

        if (
            password_verify(
                $newPassword,
                $user['password_hash']
            )
        ) {
            throw new RuntimeException(
                'Le nouveau mot de passe doit être différent du mot de passe actuel.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Hash
        |--------------------------------------------------------------------------
        */

        $passwordHash = password_hash(
            $newPassword,
            PASSWORD_DEFAULT
        );

        if ($passwordHash === false) {
            throw new RuntimeException(
                'Impossible de sécuriser le nouveau mot de passe.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $this->users->updatePassword(
            $userId,
            $passwordHash
        );
    }
}