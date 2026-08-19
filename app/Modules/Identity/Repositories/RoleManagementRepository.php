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
     * Recherche un rôle par son code.
     */
    public function findRoleByCode(
        string $code
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

                    LIMIT 1
                SQL
            );

        $statement->execute([
            'code' => $code,
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
     * Recherche une permission.
     */
    public function findPermissionById(
        int $permissionId
    ): ?array {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    SELECT
                        id,
                        code,
                        name

                    FROM permissions

                    WHERE id = :id

                    LIMIT 1
                SQL
            );

        $statement->execute([
            'id' => $permissionId,
        ]);

        $permission =
            $statement->fetch(
                PDO::FETCH_ASSOC
            );

        return $permission !== false
            ? $permission
            : null;
    }

    /**
     * Vérifie si un rôle possède
     * déjà une permission.
     */
    public function roleHasPermission(
        int $roleId,
        int $permissionId
    ): bool {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    SELECT 1

                    FROM role_permissions

                    WHERE role_id = :role_id
                      AND permission_id = :permission_id

                    LIMIT 1
                SQL
            );

        $statement->execute([
            'role_id' => $roleId,
            'permission_id' => $permissionId,
        ]);

        return $statement->fetchColumn()
            !== false;
    }

    /**
     * Attribue une permission à un rôle.
     */
    public function assignPermission(
        int $roleId,
        int $permissionId
    ): void {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    INSERT INTO role_permissions (
                        role_id,
                        permission_id
                    )
                    VALUES (
                        :role_id,
                        :permission_id
                    )
                SQL
            );

        $statement->execute([
            'role_id' => $roleId,
            'permission_id' => $permissionId,
        ]);
    }

    /**
     * Retire une permission d'un rôle.
     */
    public function removePermission(
        int $roleId,
        int $permissionId
    ): void {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    DELETE FROM role_permissions

                    WHERE role_id = :role_id
                      AND permission_id = :permission_id
                SQL
            );

        $statement->execute([
            'role_id' => $roleId,
            'permission_id' => $permissionId,
        ]);
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
     * Vérifie si un code de rôle existe déjà.
     */
    public function roleCodeExists(
        string $code
    ): bool {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    SELECT 1

                    FROM roles

                    WHERE code = :code

                    LIMIT 1
                SQL
            );

        $statement->execute([
            'code' => $code,
        ]);

        return $statement->fetchColumn()
            !== false;
    }

    /**
     * Crée un rôle personnalisé.
     */
    public function createRole(
        string $code,
        string $name,
        ?string $organizationType
    ): int {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    INSERT INTO roles (
                        code,
                        name,
                        organization_type,
                        is_system
                    )
                    VALUES (
                        :code,
                        :name,
                        :organization_type,
                        0
                    )
                SQL
            );

        $statement->execute([
            'code' => $code,
            'name' => $name,
            'organization_type' => $organizationType,
        ]);

        return (int) $this->pdo
            ->lastInsertId();
    }

    /**
     * Modifie le nom d'un rôle.
     *
     * Le code et la portée restent immuables
     * afin d'éviter de casser les références
     * logiques du système RBAC.
     */
    public function updateRoleName(
        int $roleId,
        string $name
    ): void {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    UPDATE roles

                    SET name = :name

                    WHERE id = :role_id
                SQL
            );

        $statement->execute([
            'name' => $name,
            'role_id' => $roleId,
        ]);
    }

    /**
     * Retourne le nombre d'utilisations
     * d'un rôle.
     */
    public function roleUsage(
        int $roleId
    ): array {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    SELECT
                        (
                            SELECT COUNT(*)
                            FROM platform_user_roles
                            WHERE role_id = :platform_role_id
                        ) AS platform_users,

                        (
                            SELECT COUNT(*)
                            FROM membership_roles
                            WHERE role_id = :membership_role_id
                        ) AS memberships
                SQL
            );

        $statement->execute([
            'platform_role_id' => $roleId,
            'membership_role_id' => $roleId,
        ]);

        $usage =
            $statement->fetch(
                PDO::FETCH_ASSOC
            );

        return $usage !== false
            ? $usage
            : [
                'platform_users' => 0,
                'memberships' => 0,
            ];
    }

    /**
     * Supprime un rôle.
     *
     * Les validations métier sont
     * appliquées dans le Service.
     */
    public function deleteRole(
        int $roleId
    ): void {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    DELETE FROM roles

                    WHERE id = :role_id
                SQL
            );

        $statement->execute([
            'role_id' => $roleId,
        ]);
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