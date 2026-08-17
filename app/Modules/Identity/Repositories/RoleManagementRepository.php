<?php

declare(strict_types=1);

namespace MedTrack\Modules\Identity\Repositories;

use PDO;

final class RoleManagementRepository
{
    public function __construct(
        private readonly PDO $pdo
    ) {
    }

    /**
     * Retourne tous les rôles avec
     * leurs statistiques d'utilisation.
     */
    public function allRoles(): array
    {
        $statement =
            $this->pdo->query(
                <<<'SQL'
                    SELECT
                        r.id,
                        r.code,
                        r.name,
                        r.organization_type,
                        r.is_system,

                        (
                            SELECT COUNT(*)
                            FROM role_permissions rp
                            WHERE rp.role_id = r.id
                        ) AS permission_count,

                        (
                            SELECT COUNT(*)
                            FROM platform_user_roles pur
                            WHERE pur.role_id = r.id
                        ) AS platform_user_count,

                        (
                            SELECT COUNT(*)
                            FROM membership_roles mr
                            WHERE mr.role_id = r.id
                        ) AS membership_count

                    FROM roles r

                    ORDER BY
                        r.organization_type IS NOT NULL,
                        r.organization_type,
                        r.name
                SQL
            );

        return $statement->fetchAll(
            PDO::FETCH_ASSOC
        );
    }

    /**
     * Retourne toutes les permissions.
     */
    public function allPermissions(): array
    {
        $statement =
            $this->pdo->query(
                <<<'SQL'
                    SELECT
                        p.id,
                        p.code,
                        p.name,

                        (
                            SELECT COUNT(*)
                            FROM role_permissions rp
                            WHERE rp.permission_id = p.id
                        ) AS role_count

                    FROM permissions p

                    ORDER BY p.code ASC
                SQL
            );

        return $statement->fetchAll(
            PDO::FETCH_ASSOC
        );
    }

    /**
     * Retourne un rôle.
     */
    public function findRoleById(
        int $id
    ): ?array {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    SELECT
                        r.id,
                        r.code,
                        r.name,
                        r.organization_type,
                        r.is_system

                    FROM roles r

                    WHERE r.id = :id

                    LIMIT 1
                SQL
            );

        $statement->execute([
            'id' => $id,
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
     * Retourne les permissions d'un rôle.
     */
    public function permissionsForRole(
        int $roleId
    ): array {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    SELECT
                        p.id,
                        p.code,
                        p.name

                    FROM role_permissions rp

                    INNER JOIN permissions p
                        ON p.id = rp.permission_id

                    WHERE rp.role_id = :role_id

                    ORDER BY p.code ASC
                SQL
            );

        $statement->execute([
            'role_id' => $roleId,
        ]);

        return $statement->fetchAll(
            PDO::FETCH_ASSOC
        );
    }

    /**
     * Retourne les utilisateurs plateforme
     * auxquels ce rôle est attribué.
     */
    public function platformUsersForRole(
        int $roleId
    ): array {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    SELECT
                        u.id,
                        u.uuid,
                        u.email,
                        u.phone,
                        u.status,
                        pur.assigned_at

                    FROM platform_user_roles pur

                    INNER JOIN users u
                        ON u.id = pur.user_id

                    WHERE pur.role_id = :role_id

                    ORDER BY
                        u.email ASC,
                        u.phone ASC
                SQL
            );

        $statement->execute([
            'role_id' => $roleId,
        ]);

        return $statement->fetchAll(
            PDO::FETCH_ASSOC
        );
    }

    /**
     * Retourne les memberships institutionnels
     * auxquels ce rôle est attribué.
     */
    public function membershipsForRole(
        int $roleId
    ): array {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    SELECT
                        om.id AS membership_id,
                        om.status AS membership_status,
                        om.joined_at,
                        om.ended_at,

                        u.id AS user_id,
                        u.email,
                        u.phone,
                        u.status AS user_status,

                        o.id AS organization_id,
                        o.code AS organization_code,
                        o.name AS organization_name,
                        o.type AS organization_type

                    FROM membership_roles mr

                    INNER JOIN organization_memberships om
                        ON om.id = mr.membership_id

                    INNER JOIN users u
                        ON u.id = om.user_id

                    INNER JOIN organizations o
                        ON o.id = om.organization_id

                    WHERE mr.role_id = :role_id

                    ORDER BY
                        o.name ASC,
                        u.email ASC,
                        u.phone ASC
                SQL
            );

        $statement->execute([
            'role_id' => $roleId,
        ]);

        return $statement->fetchAll(
            PDO::FETCH_ASSOC
        );
    }

    /**
     * Métriques globales RBAC.
     */
    public function metrics(): array
    {
        $statement =
            $this->pdo->query(
                <<<'SQL'
                    SELECT
                        (
                            SELECT COUNT(*)
                            FROM roles
                        ) AS total_roles,

                        (
                            SELECT COUNT(*)
                            FROM roles
                            WHERE is_system = 1
                        ) AS system_roles,

                        (
                            SELECT COUNT(*)
                            FROM roles
                            WHERE organization_type IS NULL
                        ) AS platform_roles,

                        (
                            SELECT COUNT(*)
                            FROM roles
                            WHERE organization_type IS NOT NULL
                        ) AS organization_roles,

                        (
                            SELECT COUNT(*)
                            FROM permissions
                        ) AS total_permissions,

                        (
                            SELECT COUNT(*)
                            FROM role_permissions
                        ) AS role_permission_links
                SQL
            );

        $metrics =
            $statement->fetch(
                PDO::FETCH_ASSOC
            );

        return $metrics !== false
            ? $metrics
            : [];
    }
}