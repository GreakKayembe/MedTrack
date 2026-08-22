<?php

declare(strict_types=1);

namespace MedTrack\Modules\Identity\Repositories;

use PDO;

final class PasswordResetRepository
{
    public function __construct(
        private readonly PDO $pdo
    ) {
    }

    public function revokeActiveForUser(
        int $userId,
        string $channel
    ): void {
        $statement = $this->pdo->prepare(
            <<<'SQL'
                UPDATE password_reset_tokens
                SET revoked_at = CURRENT_TIMESTAMP
                WHERE user_id = :user_id
                  AND channel = :channel
                  AND used_at IS NULL
                  AND revoked_at IS NULL
            SQL
        );

        $statement->execute([
            'user_id' => $userId,
            'channel' => $channel,
        ]);
    }

    public function create(
    int $userId,
    string $channel,
    string $tokenHash,
    ?string $ipAddress
): void {
    $statement = $this->pdo->prepare(
        <<<'SQL'
            INSERT INTO password_reset_tokens (
                user_id,
                channel,
                token_hash,
                expires_at,
                ip_address
            ) VALUES (
                :user_id,
                :channel,
                :token_hash,
                DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 30 MINUTE),
                :ip_address
            )
        SQL
    );

    $statement->execute([
        'user_id' => $userId,
        'channel' => $channel,
        'token_hash' => $tokenHash,
        'ip_address' => $ipAddress,
    ]);
}

    public function findValidByHash(
        string $tokenHash
    ): ?array {
        $statement = $this->pdo->prepare(
            <<<'SQL'
                SELECT
                    id,
                    user_id,
                    channel,
                    token_hash,
                    expires_at,
                    used_at,
                    revoked_at,
                    attempt_count,
                    ip_address,
                    created_at
                FROM password_reset_tokens
                WHERE token_hash = :token_hash
                  AND used_at IS NULL
                  AND revoked_at IS NULL
                  AND expires_at > CURRENT_TIMESTAMP
                LIMIT 1
            SQL
        );

        $statement->execute([
            'token_hash' => $tokenHash,
        ]);

        $token = $statement->fetch(PDO::FETCH_ASSOC);

        return $token ?: null;
    }

    public function incrementAttempts(int $id): void
    {
        $statement = $this->pdo->prepare(
            <<<'SQL'
                UPDATE password_reset_tokens
                SET attempt_count = attempt_count + 1
                WHERE id = :id
                  AND attempt_count < 5
                  AND used_at IS NULL
                  AND revoked_at IS NULL
            SQL
        );

        $statement->execute([
            'id' => $id,
        ]);
    }

    public function markAsUsed(int $id): void
    {
        $statement = $this->pdo->prepare(
            <<<'SQL'
                UPDATE password_reset_tokens
                SET used_at = CURRENT_TIMESTAMP
                WHERE id = :id
                  AND used_at IS NULL
                  AND revoked_at IS NULL
            SQL
        );

        $statement->execute([
            'id' => $id,
        ]);
    }

    public function revoke(int $id): void
    {
        $statement = $this->pdo->prepare(
            <<<'SQL'
                UPDATE password_reset_tokens
                SET revoked_at = CURRENT_TIMESTAMP
                WHERE id = :id
                  AND used_at IS NULL
                  AND revoked_at IS NULL
            SQL
        );

        $statement->execute([
            'id' => $id,
        ]);
    }
}