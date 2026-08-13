<?php

declare(strict_types=1);

namespace MedTrack\Modules\Identity\Repositories;

use PDO;

final class LoginHistoryRepository
{
    public function __construct(
        private readonly PDO $pdo
    ) {
    }

    public function recordSuccess(
        int $userId,
        string $loginIdentifier,
        string $ipAddress,
        ?string $userAgent
    ): void {
        $statement = $this->pdo->prepare(
            <<<'SQL'
            INSERT INTO login_history (
                user_id,
                login_identifier,
                success,
                ip_address,
                user_agent,
                failure_reason,
                created_at
            )
            VALUES (
                :user_id,
                :login_identifier,
                1,
                :ip_address,
                :user_agent,
                NULL,
                CURRENT_TIMESTAMP
            )
            SQL
        );

        $statement->execute([
            'user_id' => $userId,
            'login_identifier' => $loginIdentifier,
            'ip_address' => $ipAddress,
            'user_agent' => $this->normalizeUserAgent(
                $userAgent
            ),
        ]);
    }

    public function recordFailure(
        ?int $userId,
        string $loginIdentifier,
        string $ipAddress,
        ?string $userAgent,
        string $failureReason
    ): void {
        $statement = $this->pdo->prepare(
            <<<'SQL'
            INSERT INTO login_history (
                user_id,
                login_identifier,
                success,
                ip_address,
                user_agent,
                failure_reason,
                created_at
            )
            VALUES (
                :user_id,
                :login_identifier,
                0,
                :ip_address,
                :user_agent,
                :failure_reason,
                CURRENT_TIMESTAMP
            )
            SQL
        );

        if ($userId === null) {
            $statement->bindValue(
                ':user_id',
                null,
                PDO::PARAM_NULL
            );
        } else {
            $statement->bindValue(
                ':user_id',
                $userId,
                PDO::PARAM_INT
            );
        }

        $statement->bindValue(
            ':login_identifier',
            $loginIdentifier
        );

        $statement->bindValue(
            ':ip_address',
            $ipAddress
        );

        $userAgent = $this->normalizeUserAgent(
            $userAgent
        );

        if ($userAgent === null) {
            $statement->bindValue(
                ':user_agent',
                null,
                PDO::PARAM_NULL
            );
        } else {
            $statement->bindValue(
                ':user_agent',
                $userAgent
            );
        }

        $statement->bindValue(
            ':failure_reason',
            $failureReason
        );

        $statement->execute();
    }

    private function normalizeUserAgent(
        ?string $userAgent
    ): ?string {
        if ($userAgent === null) {
            return null;
        }

        $userAgent = trim($userAgent);

        if ($userAgent === '') {
            return null;
        }

        return mb_substr(
            $userAgent,
            0,
            500,
            'UTF-8'
        );
    }
}