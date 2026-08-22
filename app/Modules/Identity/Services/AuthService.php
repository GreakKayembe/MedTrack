<?php

declare(strict_types=1);

namespace MedTrack\Modules\Identity\Services;

use MedTrack\Core\Auth\Session;
use MedTrack\Modules\Identity\Repositories\UserRepository;

final class AuthService
{
    public function __construct(
        private readonly UserRepository $users,
        private readonly Session $session
    ) {
    }

    /**
     * Tente d'authentifier un utilisateur.
     *
     * Le service retourne un résultat détaillé destiné
     * uniquement à la logique interne de MedTrack.
     *
     * Le contrôleur devra continuer à utiliser un message
     * générique côté client afin d'éviter l'énumération
     * des comptes.
     */
    public function attempt(
        string $login,
        string $password
    ): AuthenticationResult {
        /*
        |--------------------------------------------------------------------------
        | Recherche utilisateur
        |--------------------------------------------------------------------------
        */

        $user = $this->users->findByLogin(
            $login
        );

        if ($user === null) {
            return AuthenticationResult::failure(
                AuthenticationResult::USER_NOT_FOUND
            );
        }

        $userId = (int) $user['id'];

        /*
        |--------------------------------------------------------------------------
        | Vérification du statut
        |--------------------------------------------------------------------------
        */

        if ($user['status'] !== 'ACTIVE') {
            return AuthenticationResult::failure(
                AuthenticationResult::ACCOUNT_UNAVAILABLE,
                $userId
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Vérification du mot de passe
        |--------------------------------------------------------------------------
        */

        if (
            !password_verify(
                $password,
                $user['password_hash']
            )
        ) {
            return AuthenticationResult::failure(
                AuthenticationResult::INVALID_PASSWORD,
                $userId
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Protection contre la fixation de session
        |--------------------------------------------------------------------------
        */

        $this->session->regenerate();

        /*
        |--------------------------------------------------------------------------
        | Création de la session authentifiée
        |--------------------------------------------------------------------------
        */

        $this->session->put(
            'auth_user_id',
            $userId
        );

        /*
        |--------------------------------------------------------------------------
        | Dernière connexion
        |--------------------------------------------------------------------------
        */

        $this->users->updateLastLogin(
            $userId
        );

        /*
        |--------------------------------------------------------------------------
        | Succès
        |--------------------------------------------------------------------------
        */

        return AuthenticationResult::success(
            $userId
        );
    }

    /**
     * Indique si un utilisateur est actuellement authentifié.
     */
    public function check(): bool
    {
        return $this->session->has(
            'auth_user_id'
        );
    }

    /**
     * Retourne l'identifiant de l'utilisateur authentifié.
     */
    public function id(): ?int
    {
        $id = $this->session->get(
            'auth_user_id'
        );

        return $id !== null
            ? (int) $id
            : null;
    }

    /**
     * Retourne l'utilisateur actuellement authentifié.
     */
    public function user(): ?array
    {
        $id = $this->id();

        if ($id === null) {
            return null;
        }

        return $this->users->findById(
            $id
        );
    }

    /**
     * Déconnecte l'utilisateur.
     */
    public function logout(): void
    {
        $this->session->invalidate();
    }
}