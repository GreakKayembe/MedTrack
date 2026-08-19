<?php

declare(strict_types=1);

namespace MedTrack\Modules\Audit\Repositories;

use PDO;

final class AuditRepository
{
    public function __construct(
        private readonly PDO $pdo
    ) {
    }

    /**
     * Retourne les événements d'audit récents.
     */
    public function all(
        int $limit = 100
    ): array {
        $limit =
            max(
                1,
                min(
                    $limit,
                    500
                )
            );

        $sql =
            sprintf(
                <<<'SQL'
                    SELECT
                        ae.id,
                        ae.uuid,
                        ae.organization_id,
                        ae.actor_user_id,
                        ae.actor_membership_id,
                        ae.action,
                        ae.entity_type,
                        ae.entity_id,
                        ae.ip_address,
                        ae.user_agent,
                        ae.created_at,

                        o.code AS organization_code,
                        o.name AS organization_name,
                        o.type AS organization_type,

                        u.email AS actor_email,
                        u.phone AS actor_phone

                    FROM audit_events ae

                    LEFT JOIN organizations o
                        ON o.id = ae.organization_id

                    LEFT JOIN users u
                        ON u.id = ae.actor_user_id

                    ORDER BY
                        ae.created_at DESC,
                        ae.id DESC

                    LIMIT %d
                SQL,
                $limit
            );

        $statement =
            $this->pdo->query(
                $sql
            );

        return $statement->fetchAll(
            PDO::FETCH_ASSOC
        );
    }

    /**
     * Retourne un événement d'audit.
     */
    public function findById(
        int $id
    ): ?array {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    SELECT
                        ae.id,
                        ae.uuid,
                        ae.organization_id,
                        ae.actor_user_id,
                        ae.actor_membership_id,
                        ae.action,
                        ae.entity_type,
                        ae.entity_id,
                        ae.old_values,
                        ae.new_values,
                        ae.metadata,
                        ae.ip_address,
                        ae.user_agent,
                        ae.created_at,

                        o.code AS organization_code,
                        o.name AS organization_name,
                        o.type AS organization_type,

                        u.email AS actor_email,
                        u.phone AS actor_phone,

                        om.status AS membership_status,
                        om.joined_at AS membership_joined_at

                    FROM audit_events ae

                    LEFT JOIN organizations o
                        ON o.id = ae.organization_id

                    LEFT JOIN users u
                        ON u.id = ae.actor_user_id

                    LEFT JOIN organization_memberships om
                        ON om.id = ae.actor_membership_id

                    WHERE ae.id = :id

                    LIMIT 1
                SQL
            );

        $statement->execute([
            'id' => $id,
        ]);

        $audit =
            $statement->fetch(
                PDO::FETCH_ASSOC
            );

        return $audit !== false
            ? $audit
            : null;
    }

    /**
     * Retourne les actions distinctes
     * présentes dans le journal.
     */
    public function actions(): array
    {
        $statement =
            $this->pdo->query(
                <<<'SQL'
                    SELECT DISTINCT
                        action

                    FROM audit_events

                    WHERE action <> ''

                    ORDER BY action ASC
                SQL
            );

        return $statement->fetchAll(
            PDO::FETCH_COLUMN
        );
    }

    /**
     * Retourne les types d'entités distincts.
     */
    public function entityTypes(): array
    {
        $statement =
            $this->pdo->query(
                <<<'SQL'
                    SELECT DISTINCT
                        entity_type

                    FROM audit_events

                    WHERE entity_type <> ''

                    ORDER BY entity_type ASC
                SQL
            );

        return $statement->fetchAll(
            PDO::FETCH_COLUMN
        );
    }

    /**
     * Métriques globales du journal.
     */
    public function metrics(): array
    {
        $statement =
            $this->pdo->query(
                <<<'SQL'
                    SELECT
                        COUNT(*) AS total_events,

                        COALESCE(
                            SUM(
                                created_at >=
                                DATE_SUB(
                                    CURRENT_TIMESTAMP,
                                    INTERVAL 24 HOUR
                                )
                            ),
                            0
                        ) AS events_24h,

                        COALESCE(
                            SUM(
                                created_at >=
                                DATE_SUB(
                                    CURRENT_TIMESTAMP,
                                    INTERVAL 7 DAY
                                )
                            ),
                            0
                        ) AS events_7d,

                        COUNT(
                            DISTINCT actor_user_id
                        ) AS distinct_actors,

                        COUNT(
                            DISTINCT entity_type
                        ) AS distinct_entity_types

                    FROM audit_events
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
     * Enregistre un événement d'audit.
     */
    public function create(
        array $data
    ): int {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    INSERT INTO audit_events (
                        uuid,
                        organization_id,
                        actor_user_id,
                        actor_membership_id,
                        action,
                        entity_type,
                        entity_id,
                        old_values,
                        new_values,
                        metadata,
                        ip_address,
                        user_agent
                    )
                    VALUES (
                        :uuid,
                        :organization_id,
                        :actor_user_id,
                        :actor_membership_id,
                        :action,
                        :entity_type,
                        :entity_id,
                        :old_values,
                        :new_values,
                        :metadata,
                        :ip_address,
                        :user_agent
                    )
                SQL
            );

        $statement->execute([
            'uuid' =>
                $data['uuid'],

            'organization_id' =>
                $data['organization_id'],

            'actor_user_id' =>
                $data['actor_user_id'],

            'actor_membership_id' =>
                $data['actor_membership_id'],

            'action' =>
                $data['action'],

            'entity_type' =>
                $data['entity_type'],

            'entity_id' =>
                $data['entity_id'],

            'old_values' =>
                $data['old_values'],

            'new_values' =>
                $data['new_values'],

            'metadata' =>
                $data['metadata'],

            'ip_address' =>
                $data['ip_address'],

            'user_agent' =>
                $data['user_agent'],
        ]);

        return (int) $this->pdo
            ->lastInsertId();
    }
}