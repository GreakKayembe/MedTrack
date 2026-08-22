<?php

declare(strict_types=1);

namespace MedTrack\Modules\Identity\Repositories;

use PDO;

final class OrganizationMembershipRepository
{
    public function __construct(
        private readonly PDO $pdo
    ) {
    }

    /**
     * Retourne tous les memberships actifs
     * appartenant à un utilisateur.
     */
    public function findActiveByUserId(
        int $userId
    ): array {
        $sql = <<<'SQL'
            SELECT
                om.id AS membership_id,
                om.organization_id,
                om.user_id,
                om.status AS membership_status,
                om.joined_at,
                om.ended_at,

                o.uuid AS organization_uuid,
                o.type AS organization_type,
                o.code AS organization_code,
                o.name AS organization_name,
                o.status AS organization_status,
                o.province,
                o.city

            FROM organization_memberships om

            INNER JOIN organizations o
                ON o.id = om.organization_id

            WHERE om.user_id = :user_id
              AND om.status = 'ACTIVE'
              AND o.status = 'ACTIVE'

            ORDER BY
                o.name ASC,
                om.id ASC
        SQL;

        $statement =
            $this->pdo->prepare($sql);

        $statement->execute([
            'user_id' => $userId,
        ]);

        return $statement->fetchAll(
            PDO::FETCH_ASSOC
        );
    }


    /**
     * Retourne un membership actif précis
     * pour un utilisateur.
     *
     * Cette méthode est essentielle pour empêcher
     * un utilisateur de sélectionner arbitrairement
     * une organisation dont il n'est pas membre.
     */
    public function findActiveMembership(
        int $membershipId,
        int $userId
    ): ?array {
        $sql = <<<'SQL'
            SELECT
                om.id AS membership_id,
                om.organization_id,
                om.user_id,
                om.status AS membership_status,
                om.joined_at,
                om.ended_at,

                o.uuid AS organization_uuid,
                o.type AS organization_type,
                o.code AS organization_code,
                o.name AS organization_name,
                o.status AS organization_status,
                o.province,
                o.city

            FROM organization_memberships om

            INNER JOIN organizations o
                ON o.id = om.organization_id

            WHERE om.id = :membership_id
              AND om.user_id = :user_id
              AND om.status = 'ACTIVE'
              AND o.status = 'ACTIVE'

            LIMIT 1
        SQL;

        $statement =
            $this->pdo->prepare($sql);

        $statement->execute([
            'membership_id' =>
                $membershipId,

            'user_id' =>
                $userId,
        ]);

        $membership =
            $statement->fetch(
                PDO::FETCH_ASSOC
            );

        return $membership !== false
            ? $membership
            : null;
    }


    /**
     * Retourne un membership actif à partir
     * de l'organisation.
     */
    public function findActiveByUserAndOrganization(
        int $userId,
        int $organizationId
    ): ?array {
        $sql = <<<'SQL'
            SELECT
                om.id AS membership_id,
                om.organization_id,
                om.user_id,
                om.status AS membership_status,
                om.joined_at,
                om.ended_at,

                o.uuid AS organization_uuid,
                o.type AS organization_type,
                o.code AS organization_code,
                o.name AS organization_name,
                o.status AS organization_status,
                o.province,
                o.city

            FROM organization_memberships om

            INNER JOIN organizations o
                ON o.id = om.organization_id

            WHERE om.user_id = :user_id
              AND om.organization_id = :organization_id
              AND om.status = 'ACTIVE'
              AND o.status = 'ACTIVE'

            LIMIT 1
        SQL;

        $statement =
            $this->pdo->prepare($sql);

        $statement->execute([
            'user_id' =>
                $userId,

            'organization_id' =>
                $organizationId,
        ]);

        $membership =
            $statement->fetch(
                PDO::FETCH_ASSOC
            );

        return $membership !== false
            ? $membership
            : null;
    }


    /**
     * Retourne les rôles réellement attribués
     * à un membership.
     *
     * Le type du rôle doit correspondre au type
     * de l'organisation.
     */
    public function rolesForMembership(
        int $membershipId
    ): array {
        $sql = <<<'SQL'
            SELECT
                r.id,
                r.code,
                r.name,
                r.organization_type,
                r.is_system

            FROM membership_roles mr

            INNER JOIN roles r
                ON r.id = mr.role_id

            INNER JOIN organization_memberships om
                ON om.id = mr.membership_id

            INNER JOIN organizations o
                ON o.id = om.organization_id

            WHERE mr.membership_id = :membership_id
              AND om.status = 'ACTIVE'
              AND o.status = 'ACTIVE'
              AND r.organization_type = o.type

            ORDER BY
                r.code ASC
        SQL;

        $statement =
            $this->pdo->prepare($sql);

        $statement->execute([
            'membership_id' =>
                $membershipId,
        ]);

        return $statement->fetchAll(
            PDO::FETCH_ASSOC
        );
    }


    /**
     * Retourne toutes les permissions effectives
     * d'un membership.
     *
     * DISTINCT est indispensable lorsqu'une même
     * permission est accordée par plusieurs rôles.
     */
    public function permissionsForMembership(
        int $membershipId
    ): array {
        $sql = <<<'SQL'
            SELECT DISTINCT
                p.id,
                p.code,
                p.name

            FROM membership_roles mr

            INNER JOIN roles r
                ON r.id = mr.role_id

            INNER JOIN role_permissions rp
                ON rp.role_id = r.id

            INNER JOIN permissions p
                ON p.id = rp.permission_id

            INNER JOIN organization_memberships om
                ON om.id = mr.membership_id

            INNER JOIN organizations o
                ON o.id = om.organization_id

            WHERE mr.membership_id = :membership_id
              AND om.status = 'ACTIVE'
              AND o.status = 'ACTIVE'
              AND r.organization_type = o.type

            ORDER BY
                p.code ASC
        SQL;

        $statement =
            $this->pdo->prepare($sql);

        $statement->execute([
            'membership_id' =>
                $membershipId,
        ]);

        return $statement->fetchAll(
            PDO::FETCH_ASSOC
        );
    }


    /**
     * Vérifie directement une permission sur
     * un membership.
     *
     * Cette méthode pourra être utilisée par
     * AuthorizationService.
     */
    public function membershipHasPermission(
        int $membershipId,
        string $permissionCode
    ): bool {
        $sql = <<<'SQL'
            SELECT 1

            FROM membership_roles mr

            INNER JOIN roles r
                ON r.id = mr.role_id

            INNER JOIN role_permissions rp
                ON rp.role_id = r.id

            INNER JOIN permissions p
                ON p.id = rp.permission_id

            INNER JOIN organization_memberships om
                ON om.id = mr.membership_id

            INNER JOIN organizations o
                ON o.id = om.organization_id

            WHERE mr.membership_id = :membership_id
              AND om.status = 'ACTIVE'
              AND o.status = 'ACTIVE'
              AND r.organization_type = o.type
              AND p.code = :permission_code

            LIMIT 1
        SQL;

        $statement =
            $this->pdo->prepare($sql);

        $statement->execute([
            'membership_id' =>
                $membershipId,

            'permission_code' =>
                $permissionCode,
        ]);

        return $statement->fetchColumn()
            !== false;
    }
}