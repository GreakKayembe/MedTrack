<?php

declare(strict_types=1);

namespace MedTrack\Modules\Identity\Repositories;

use PDO;
use RuntimeException;

final class OrganizationOnboardingRepository
{
    public function __construct(
        private readonly PDO $pdo
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Users
    |--------------------------------------------------------------------------
    */

    /**
     * Vérifie si un email est déjà utilisé.
     */
    public function emailExists(
        string $email
    ): bool {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    SELECT 1

                    FROM users

                    WHERE email = :email

                    LIMIT 1
                SQL
            );

        $statement->execute([
            'email' =>
                $email,
        ]);

        return $statement->fetchColumn()
            !== false;
    }

    /**
     * Vérifie si un numéro de téléphone
     * est déjà utilisé.
     */
    public function phoneExists(
        string $phone
    ): bool {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    SELECT 1

                    FROM users

                    WHERE phone = :phone

                    LIMIT 1
                SQL
            );

        $statement->execute([
            'phone' =>
                $phone,
        ]);

        return $statement->fetchColumn()
            !== false;
    }

    /**
     * Crée un utilisateur institutionnel.
     *
     * Le compte est directement ACTIVE mais
     * doit obligatoirement modifier son mot
     * de passe à la première connexion.
     */
    public function createUser(
        string $uuid,
        string $email,
        ?string $phone,
        string $passwordHash
    ): int {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    INSERT INTO users (
                        uuid,
                        email,
                        phone,
                        password_hash,
                        status,
                        must_change_password,
                        mfa_enabled
                    )
                    VALUES (
                        :uuid,
                        :email,
                        :phone,
                        :password_hash,
                        'ACTIVE',
                        1,
                        0
                    )
                SQL
            );

        $statement->execute([
            'uuid' =>
                $uuid,

            'email' =>
                $email,

            'phone' =>
                $phone,

            'password_hash' =>
                $passwordHash,
        ]);

        $userId =
            (int) $this->pdo
                ->lastInsertId();

        if ($userId <= 0) {
            throw new RuntimeException(
                'Impossible de récupérer '
                . 'l’identifiant du compte créé.'
            );
        }

        return $userId;
    }

    /*
    |--------------------------------------------------------------------------
    | User profiles
    |--------------------------------------------------------------------------
    */

    /**
     * Crée le profil utilisateur.
     */
    public function createUserProfile(
        int $userId,
        string $firstName,
        ?string $middleName,
        string $lastName
    ): void {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    INSERT INTO user_profiles (
                        user_id,
                        first_name,
                        middle_name,
                        last_name
                    )
                    VALUES (
                        :user_id,
                        :first_name,
                        :middle_name,
                        :last_name
                    )
                SQL
            );

        $statement->execute([
            'user_id' =>
                $userId,

            'first_name' =>
                $firstName,

            'middle_name' =>
                $middleName,

            'last_name' =>
                $lastName,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Memberships
    |--------------------------------------------------------------------------
    */

    /**
     * Vérifie si l'utilisateur possède déjà
     * un membership dans l'organisation.
     */
    public function membershipExists(
        int $organizationId,
        int $userId
    ): bool {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    SELECT 1

                    FROM organization_memberships

                    WHERE organization_id = :organization_id
                      AND user_id = :user_id

                    LIMIT 1
                SQL
            );

        $statement->execute([
            'organization_id' =>
                $organizationId,

            'user_id' =>
                $userId,
        ]);

        return $statement->fetchColumn()
            !== false;
    }

    /**
     * Crée un membership organisationnel actif.
     */
    public function createMembership(
        int $organizationId,
        int $userId
    ): int {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    INSERT INTO organization_memberships (
                        organization_id,
                        user_id,
                        status,
                        joined_at
                    )
                    VALUES (
                        :organization_id,
                        :user_id,
                        'ACTIVE',
                        CURRENT_TIMESTAMP
                    )
                SQL
            );

        $statement->execute([
            'organization_id' =>
                $organizationId,

            'user_id' =>
                $userId,
        ]);

        $membershipId =
            (int) $this->pdo
                ->lastInsertId();

        if ($membershipId <= 0) {
            throw new RuntimeException(
                'Impossible de récupérer '
                . 'l’identifiant du membership créé.'
            );
        }

        return $membershipId;
    }

    /*
    |--------------------------------------------------------------------------
    | Roles
    |--------------------------------------------------------------------------
    */

    /**
     * Recherche un rôle institutionnel
     * par son code et son type d'organisation.
     */
    public function findOrganizationRoleByCode(
        string $code,
        string $organizationType
    ): ?array {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    SELECT
                        id,
                        code,
                        name,
                        organization_type,
                        is_system

                    FROM roles

                    WHERE code = :code
                      AND organization_type = :organization_type

                    LIMIT 1
                SQL
            );

        $statement->execute([
            'code' =>
                $code,

            'organization_type' =>
                $organizationType,
        ]);

        $role =
            $statement->fetch(
                PDO::FETCH_ASSOC
            );

        return $role !== false
            ? $role
            : null;
    }

    /**
     * Vérifie si le membership possède
     * déjà un rôle.
     */
    public function membershipHasRole(
        int $membershipId,
        int $roleId
    ): bool {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    SELECT 1

                    FROM membership_roles

                    WHERE membership_id = :membership_id
                      AND role_id = :role_id

                    LIMIT 1
                SQL
            );

        $statement->execute([
            'membership_id' =>
                $membershipId,

            'role_id' =>
                $roleId,
        ]);

        return $statement->fetchColumn()
            !== false;
    }

    /**
     * Attribue un rôle au membership.
     */
    public function assignMembershipRole(
        int $membershipId,
        int $roleId
    ): void {
        if (
            $this->membershipHasRole(
                $membershipId,
                $roleId
            )
        ) {
            return;
        }

        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    INSERT INTO membership_roles (
                        membership_id,
                        role_id
                    )
                    VALUES (
                        :membership_id,
                        :role_id
                    )
                SQL
            );

        $statement->execute([
            'membership_id' =>
                $membershipId,

            'role_id' =>
                $roleId,
        ]);
    }
}