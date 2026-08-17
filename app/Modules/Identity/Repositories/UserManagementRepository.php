<?php

declare(strict_types=1);

namespace MedTrack\Modules\Identity\Repositories;

use PDO;

final class UserManagementRepository
{
    public function __construct(
        private readonly PDO $pdo
    ) {
    }

    /**
     * Retourne tous les utilisateurs avec
     * quelques informations d'accès globales.
     */
    public function all(): array
    {
        $statement =
            $this->pdo->query(
                <<<'SQL'
                    SELECT
                        u.id,
                        u.uuid,
                        u.email,
                        u.phone,
                        u.status,
                        u.email_verified_at,
                        u.phone_verified_at,
                        u.must_change_password,
                        u.mfa_enabled,
                        u.last_login_at,
                        u.created_at,
                        u.updated_at,

                        (
                            SELECT COUNT(*)
                            FROM platform_user_roles pur
                            WHERE pur.user_id = u.id
                        ) AS platform_role_count,

                        (
                            SELECT COUNT(*)
                            FROM organization_memberships om
                            WHERE om.user_id = u.id
                              AND om.status = 'ACTIVE'
                        ) AS active_membership_count

                    FROM users u

                    ORDER BY
                        u.created_at DESC,
                        u.id DESC
                SQL
            );

        return $statement->fetchAll(
            PDO::FETCH_ASSOC
        );
    }

    /**
     * Recherche un utilisateur.
     */
    public function findById(
        int $id
    ): ?array {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    SELECT
                        u.id,
                        u.uuid,
                        u.email,
                        u.phone,
                        u.status,
                        u.email_verified_at,
                        u.phone_verified_at,
                        u.must_change_password,
                        u.mfa_enabled,
                        u.last_login_at,
                        u.created_at,
                        u.updated_at

                    FROM users u

                    WHERE u.id = :id

                    LIMIT 1
                SQL
            );

        $statement->execute([
            'id' => $id,
        ]);

        $user =
            $statement->fetch(
                PDO::FETCH_ASSOC
            );

        return $user !== false
            ? $user
            : null;
    }

    /**
     * Retourne les rôles plateforme d'un utilisateur.
     */
    public function platformRoles(
        int $userId
    ): array {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
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

                    ORDER BY r.name ASC
                SQL
            );

        $statement->execute([
            'user_id' =>
                $userId,
        ]);

        return $statement->fetchAll(
            PDO::FETCH_ASSOC
        );
    }

    /**
     * Retourne les permissions plateforme
     * résolues à partir des rôles.
     */
    public function platformPermissions(
        int $userId
    ): array {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    SELECT DISTINCT
                        p.id,
                        p.code,
                        p.name

                    FROM platform_user_roles pur

                    INNER JOIN role_permissions rp
                        ON rp.role_id = pur.role_id

                    INNER JOIN permissions p
                        ON p.id = rp.permission_id

                    WHERE pur.user_id = :user_id

                    ORDER BY p.code ASC
                SQL
            );

        $statement->execute([
            'user_id' =>
                $userId,
        ]);

        return $statement->fetchAll(
            PDO::FETCH_ASSOC
        );
    }

    /**
     * Retourne les memberships institutionnels
     * de l'utilisateur.
     */
    public function memberships(
        int $userId
    ): array {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    SELECT
                        om.id,
                        om.organization_id,
                        om.user_id,
                        om.status,
                        om.joined_at,
                        om.ended_at,
                        om.created_at,

                        o.uuid AS organization_uuid,
                        o.code AS organization_code,
                        o.name AS organization_name,
                        o.type AS organization_type,
                        o.status AS organization_status

                    FROM organization_memberships om

                    INNER JOIN organizations o
                        ON o.id = om.organization_id

                    WHERE om.user_id = :user_id

                    ORDER BY
                        om.created_at DESC,
                        om.id DESC
                SQL
            );

        $statement->execute([
            'user_id' =>
                $userId,
        ]);

        return $statement->fetchAll(
            PDO::FETCH_ASSOC
        );
    }

    /**
     * Retourne les rôles rattachés à un membership.
     */
    public function membershipRoles(
        int $membershipId
    ): array {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    SELECT
                        r.id,
                        r.code,
                        r.name,
                        r.organization_type,
                        r.is_system

                    FROM membership_roles mr

                    INNER JOIN roles r
                        ON r.id = mr.role_id

                    WHERE mr.membership_id = :membership_id

                    ORDER BY r.name ASC
                SQL
            );

        $statement->execute([
            'membership_id' =>
                $membershipId,
        ]);

        return $statement->fetchAll(
            PDO::FETCH_ASSOC
        );
    }

    /**
     * Retourne les permissions rattachées
     * à un membership via ses rôles.
     */
    public function membershipPermissions(
        int $membershipId
    ): array {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    SELECT DISTINCT
                        p.id,
                        p.code,
                        p.name

                    FROM membership_roles mr

                    INNER JOIN role_permissions rp
                        ON rp.role_id = mr.role_id

                    INNER JOIN permissions p
                        ON p.id = rp.permission_id

                    WHERE mr.membership_id = :membership_id

                    ORDER BY p.code ASC
                SQL
            );

        $statement->execute([
            'membership_id' =>
                $membershipId,
        ]);

        return $statement->fetchAll(
            PDO::FETCH_ASSOC
        );
    }

    /**
     * Retourne l'historique récent de connexion.
     */
    public function loginHistory(
        int $userId,
        int $limit = 20
    ): array {
        $limit =
            max(
                1,
                min(
                    $limit,
                    100
                )
            );

        $sql =
            sprintf(
                <<<'SQL'
                    SELECT
                        id,
                        user_id,
                        login_identifier,
                        success,
                        ip_address,
                        user_agent,
                        failure_reason,
                        created_at

                    FROM login_history

                    WHERE user_id = :user_id

                    ORDER BY
                        created_at DESC,
                        id DESC

                    LIMIT %d
                SQL,
                $limit
            );

        $statement =
            $this->pdo->prepare(
                $sql
            );

        $statement->execute([
            'user_id' =>
                $userId,
        ]);

        return $statement->fetchAll(
            PDO::FETCH_ASSOC
        );
    }

    /**
     * Métriques globales des comptes.
     */
    public function metrics(): array
    {
        $statement =
            $this->pdo->query(
                <<<'SQL'
                    SELECT
                        COUNT(*) AS total,

                        COALESCE(
                            SUM(status = 'PENDING'),
                            0
                        ) AS pending,

                        COALESCE(
                            SUM(status = 'ACTIVE'),
                            0
                        ) AS active,

                        COALESCE(
                            SUM(status = 'SUSPENDED'),
                            0
                        ) AS suspended,

                        COALESCE(
                            SUM(status = 'DISABLED'),
                            0
                        ) AS disabled,

                        COALESCE(
                            SUM(mfa_enabled = 1),
                            0
                        ) AS mfa_enabled,

                        COALESCE(
                            SUM(must_change_password = 1),
                            0
                        ) AS password_change_required

                    FROM users
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

    /**
     * Métriques des accès.
     */
    public function accessMetrics(): array
    {
        $statement =
            $this->pdo->query(
                <<<'SQL'
                    SELECT
                        (
                            SELECT COUNT(DISTINCT user_id)
                            FROM platform_user_roles
                        ) AS platform_users,

                        (
                            SELECT COUNT(*)
                            FROM organization_memberships
                            WHERE status = 'ACTIVE'
                        ) AS active_memberships,

                        (
                            SELECT COUNT(*)
                            FROM organization_memberships
                            WHERE status = 'INVITED'
                        ) AS invited_memberships,

                        (
                            SELECT COUNT(*)
                            FROM organization_memberships
                            WHERE status = 'SUSPENDED'
                        ) AS suspended_memberships
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

    /**
     * Métriques récentes de connexion.
     */
    public function loginMetrics(): array
    {
        $statement =
            $this->pdo->query(
                <<<'SQL'
                    SELECT
                        COUNT(*) AS total_attempts,

                        COALESCE(
                            SUM(success = 1),
                            0
                        ) AS successful_attempts,

                        COALESCE(
                            SUM(success = 0),
                            0
                        ) AS failed_attempts

                    FROM login_history

                    WHERE created_at >=
                        DATE_SUB(
                            CURRENT_TIMESTAMP,
                            INTERVAL 30 DAY
                        )
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