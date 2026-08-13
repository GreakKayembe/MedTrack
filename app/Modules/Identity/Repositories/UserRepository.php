<?php

declare(strict_types=1);

namespace MedTrack\Modules\Identity\Repositories;

use PDO;

final class UserRepository
{
    public function __construct(
        private readonly PDO $pdo
    ) {
    }

    /**
     * Recherche un utilisateur par email ou téléphone
     * pour le processus de connexion.
     */
    public function findByLogin(
        string $login
    ): ?array {
        $statement = $this->pdo->prepare(
            <<<'SQL'
            SELECT
                id,
                uuid,
                email,
                phone,
                password_hash,
                status,
                email_verified_at,
                phone_verified_at,
                must_change_password,
                mfa_enabled
            FROM users
            WHERE email = :email
               OR phone = :phone
            LIMIT 1
            SQL
        );

        $statement->execute([
            'email' => $login,
            'phone' => $login,
        ]);

        $user = $statement->fetch(
            PDO::FETCH_ASSOC
        );

        return $user !== false
            ? $user
            : null;
    }

    /**
     * Retourne les informations générales d'un utilisateur.
     *
     * Le hash du mot de passe n'est volontairement pas
     * exposé par cette méthode.
     */
    public function findById(
        int $id
    ): ?array {
        $statement = $this->pdo->prepare(
            <<<'SQL'
            SELECT
                u.id,
                u.uuid,
                u.email,
                u.phone,
                u.status,
                u.must_change_password,
                u.mfa_enabled,
                u.last_login_at,
                p.first_name,
                p.middle_name,
                p.last_name
            FROM users u
            LEFT JOIN user_profiles p
                ON p.user_id = u.id
            WHERE u.id = :id
            LIMIT 1
            SQL
        );

        $statement->execute([
            'id' => $id,
        ]);

        $user = $statement->fetch(
            PDO::FETCH_ASSOC
        );

        return $user !== false
            ? $user
            : null;
    }

    /**
     * Retourne uniquement les données nécessaires
     * aux opérations sensibles d'authentification.
     */
    public function findForAuthentication(
        int $id
    ): ?array {
        $statement = $this->pdo->prepare(
            <<<'SQL'
            SELECT
                id,
                uuid,
                email,
                phone,
                password_hash,
                status,
                must_change_password,
                mfa_enabled
            FROM users
            WHERE id = :id
            LIMIT 1
            SQL
        );

        $statement->execute([
            'id' => $id,
        ]);

        $user = $statement->fetch(
            PDO::FETCH_ASSOC
        );

        return $user !== false
            ? $user
            : null;
    }

    /**
     * Recherche un utilisateur pouvant recevoir
     * une demande de réinitialisation du mot de passe.
     */
    public function findForPasswordReset(
        string $identifier
    ): ?array {
        $statement = $this->pdo->prepare(
            <<<'SQL'
            SELECT
                id,
                uuid,
                email,
                phone,
                status
            FROM users
            WHERE email = :email
               OR phone = :phone
            LIMIT 1
            SQL
        );

        $statement->execute([
            'email' => $identifier,
            'phone' => $identifier,
        ]);

        $user = $statement->fetch(
            PDO::FETCH_ASSOC
        );

        return $user !== false
            ? $user
            : null;
    }

    /**
     * Met à jour la date de dernière connexion.
     */
    public function updateLastLogin(
        int $id
    ): void {
        $statement = $this->pdo->prepare(
            <<<'SQL'
            UPDATE users
            SET last_login_at = CURRENT_TIMESTAMP
            WHERE id = :id
            SQL
        );

        $statement->execute([
            'id' => $id,
        ]);
    }

    /**
     * Met à jour le mot de passe.
     *
     * Une modification réussie supprime automatiquement
     * l'obligation de changement du mot de passe.
     */
    public function updatePassword(
        int $userId,
        string $passwordHash
    ): void {
        $statement = $this->pdo->prepare(
            <<<'SQL'
            UPDATE users
            SET
                password_hash = :password_hash,
                must_change_password = 0
            WHERE id = :id
            SQL
        );

        $statement->execute([
            'password_hash' => $passwordHash,
            'id' => $userId,
        ]);
    }
}