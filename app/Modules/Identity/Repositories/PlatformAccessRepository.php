<?php

declare(strict_types=1);

namespace MedTrack\Modules\Identity\Repositories;

use PDO;

final class PlatformAccessRepository
{
    public function __construct(
        private readonly PDO $pdo
    ) {
    }

    /**
     * Retourne les rôles plateforme
     * attribués à un utilisateur.
     */
    public function rolesForUser(
        int $userId
    ): array {
        $sql = <<<'SQL'
            SELECT
                r.id,
                r.code,
                r.name,
                r.organization_type,
                r.is_system,
                pur.assigned_at

            FROM platform_user_roles pur

            INNER JOIN roles r
                ON r.id = pur.role_id

            WHERE pur.user_id = :user_id
              AND r.organization_type IS NULL

            ORDER BY
                r.code ASC
        SQL;

        $statement =
            $this->pdo->prepare($sql);

        $statement->execute([
            'user_id' =>
                $userId,
        ]);

        return $statement->fetchAll(
            PDO::FETCH_ASSOC
        );
    }


    /**
     * Vérifie si l'utilisateur possède
     * au moins un rôle plateforme.
     */
    public function hasPlatformAccess(
        int $userId
    ): bool {
        $sql = <<<'SQL'
            SELECT 1

            FROM platform_user_roles pur

            INNER JOIN roles r
                ON r.id = pur.role_id

            WHERE pur.user_id = :user_id
              AND r.organization_type IS NULL

            LIMIT 1
        SQL;

        $statement =
            $this->pdo->prepare($sql);

        $statement->execute([
            'user_id' =>
                $userId,
        ]);

        return $statement->fetchColumn()
            !== false;
    }


    /**
     * Vérifie si l'utilisateur possède
     * un rôle plateforme précis.
     */
    public function hasRole(
        int $userId,
        string $roleCode
    ): bool {
        $sql = <<<'SQL'
            SELECT 1

            FROM platform_user_roles pur

            INNER JOIN roles r
                ON r.id = pur.role_id

            WHERE pur.user_id = :user_id
              AND r.organization_type IS NULL
              AND r.code = :role_code

            LIMIT 1
        SQL;

        $statement =
            $this->pdo->prepare($sql);

        $statement->execute([
            'user_id' =>
                $userId,

            'role_code' =>
                $roleCode,
        ]);

        return $statement->fetchColumn()
            !== false;
    }


    /**
     * Retourne toutes les permissions
     * plateforme effectives d'un utilisateur.
     */
    public function permissionsForUser(
        int $userId
    ): array {
        $sql = <<<'SQL'
            SELECT DISTINCT
                p.id,
                p.code,
                p.name

            FROM platform_user_roles pur

            INNER JOIN roles r
                ON r.id = pur.role_id

            INNER JOIN role_permissions rp
                ON rp.role_id = r.id

            INNER JOIN permissions p
                ON p.id = rp.permission_id

            WHERE pur.user_id = :user_id
              AND r.organization_type IS NULL

            ORDER BY
                p.code ASC
        SQL;

        $statement =
            $this->pdo->prepare($sql);

        $statement->execute([
            'user_id' =>
                $userId,
        ]);

        return $statement->fetchAll(
            PDO::FETCH_ASSOC
        );
    }


    /**
     * Vérifie directement une permission
     * plateforme.
     */
    public function userHasPermission(
        int $userId,
        string $permissionCode
    ): bool {
        $sql = <<<'SQL'
            SELECT 1

            FROM platform_user_roles pur

            INNER JOIN roles r
                ON r.id = pur.role_id

            INNER JOIN role_permissions rp
                ON rp.role_id = r.id

            INNER JOIN permissions p
                ON p.id = rp.permission_id

            WHERE pur.user_id = :user_id
              AND r.organization_type IS NULL
              AND p.code = :permission_code

            LIMIT 1
        SQL;

        $statement =
            $this->pdo->prepare($sql);

        $statement->execute([
            'user_id' =>
                $userId,

            'permission_code' =>
                $permissionCode,
        ]);

        return $statement->fetchColumn()
            !== false;
    }


    /**
     * Détermine si l'utilisateur est
     * super administrateur plateforme.
     */
    public function isSuperAdmin(
        int $userId
    ): bool {
        return $this->hasRole(
            $userId,
            'PLATFORM_SUPER_ADMIN'
        );
    }
}