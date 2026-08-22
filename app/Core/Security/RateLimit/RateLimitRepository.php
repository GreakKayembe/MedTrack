<?php

declare(strict_types=1);

namespace MedTrack\Core\Security\RateLimit;

use PDO;

final class RateLimitRepository
{
    public function __construct(
        private readonly PDO $pdo
    ) {
    }

    public function find(
        string $action,
        string $identifierHash,
        string $ipAddress
    ): ?array {
        $statement = $this->pdo->prepare(
            <<<'SQL'
            SELECT
                id,
                action,
                identifier_hash,
                ip_address,
                attempt_count,
                window_started_at,
                blocked_until,
                last_attempt_at,

                CASE
                    WHEN blocked_until IS NOT NULL
                     AND blocked_until > CURRENT_TIMESTAMP
                    THEN 1
                    ELSE 0
                END AS is_blocked,

                CASE
                    WHEN blocked_until IS NOT NULL
                     AND blocked_until > CURRENT_TIMESTAMP
                    THEN TIMESTAMPDIFF(
                        SECOND,
                        CURRENT_TIMESTAMP,
                        blocked_until
                    )
                    ELSE 0
                END AS retry_after

            FROM security_rate_limits

            WHERE action = :action
              AND identifier_hash = :identifier_hash
              AND ip_address = :ip_address

            LIMIT 1
            SQL
        );

        $statement->execute([
            'action' => $action,
            'identifier_hash' => $identifierHash,
            'ip_address' => $ipAddress,
        ]);

        $row = $statement->fetch(
            PDO::FETCH_ASSOC
        );

        return $row !== false
            ? $row
            : null;
    }

    public function create(
        string $action,
        string $identifierHash,
        string $ipAddress
    ): void {
        $statement = $this->pdo->prepare(
            <<<'SQL'
            INSERT INTO security_rate_limits (
                action,
                identifier_hash,
                ip_address,
                attempt_count,
                window_started_at,
                blocked_until,
                last_attempt_at
            )
            VALUES (
                :action,
                :identifier_hash,
                :ip_address,
                1,
                CURRENT_TIMESTAMP,
                NULL,
                CURRENT_TIMESTAMP
            )
            SQL
        );

        $statement->execute([
            'action' => $action,
            'identifier_hash' => $identifierHash,
            'ip_address' => $ipAddress,
        ]);
    }

    public function increment(
        int $id
    ): void {
        $statement = $this->pdo->prepare(
            <<<'SQL'
            UPDATE security_rate_limits
            SET
                attempt_count = attempt_count + 1,
                last_attempt_at = CURRENT_TIMESTAMP
            WHERE id = :id
            SQL
        );

        $statement->execute([
            'id' => $id,
        ]);
    }

    public function resetWindow(
        int $id
    ): void {
        $statement = $this->pdo->prepare(
            <<<'SQL'
            UPDATE security_rate_limits
            SET
                attempt_count = 1,
                window_started_at = CURRENT_TIMESTAMP,
                blocked_until = NULL,
                last_attempt_at = CURRENT_TIMESTAMP
            WHERE id = :id
            SQL
        );

        $statement->execute([
            'id' => $id,
        ]);
    }

    public function isWindowExpired(
        int $id,
        int $windowMinutes
    ): bool {
        $statement = $this->pdo->prepare(
            <<<'SQL'
            SELECT
                CASE
                    WHEN window_started_at <= TIMESTAMPADD(
                        MINUTE,
                        -:window_minutes,
                        CURRENT_TIMESTAMP
                    )
                    THEN 1
                    ELSE 0
                END AS is_expired
            FROM security_rate_limits
            WHERE id = :id
            LIMIT 1
            SQL
        );

        $statement->bindValue(
            ':window_minutes',
            $windowMinutes,
            PDO::PARAM_INT
        );

        $statement->bindValue(
            ':id',
            $id,
            PDO::PARAM_INT
        );

        $statement->execute();

        return (int) $statement->fetchColumn() === 1;
    }

    public function block(
        int $id,
        int $minutes
    ): void {
        $statement = $this->pdo->prepare(
            <<<'SQL'
            UPDATE security_rate_limits
            SET
                blocked_until = TIMESTAMPADD(
                    MINUTE,
                    :minutes,
                    CURRENT_TIMESTAMP
                ),
                last_attempt_at = CURRENT_TIMESTAMP
            WHERE id = :id
            SQL
        );

        $statement->bindValue(
            ':minutes',
            $minutes,
            PDO::PARAM_INT
        );

        $statement->bindValue(
            ':id',
            $id,
            PDO::PARAM_INT
        );

        $statement->execute();
    }

    public function clear(
        string $action,
        string $identifierHash,
        string $ipAddress
    ): void {
        $statement = $this->pdo->prepare(
            <<<'SQL'
            DELETE FROM security_rate_limits
            WHERE action = :action
              AND identifier_hash = :identifier_hash
              AND ip_address = :ip_address
            SQL
        );

        $statement->execute([
            'action' => $action,
            'identifier_hash' => $identifierHash,
            'ip_address' => $ipAddress,
        ]);
    }
}