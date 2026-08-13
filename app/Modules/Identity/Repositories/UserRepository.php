<?php

declare(strict_types=1);

namespace MedTrack\Modules\Identity\Repositories;

use PDO;

final class UserRepository
{
    public function __construct(
        private readonly PDO $pdo
    ) {
    }

    public function findByLogin(string $login): ?array
    {
        $sql = <<<'SQL'
            SELECT
                id,
                uuid,
                email,
                phone,
                password_hash,
                status,
                email_verified_at,
                phone_verified_at,
                must_change_password,
                mfa_enabled
            FROM users
            WHERE email = :email
               OR phone = :phone
            LIMIT 1
        SQL;

        $statement = $this->pdo->prepare($sql);

        $statement->execute([
            'email' => $login,
            'phone' => $login,
        ]);

        $user = $statement->fetch();

        return $user ?: null;
    }

    public function findById(int $id): ?array
    {
        $statement = $this->pdo->prepare(
            <<<'SQL'
                SELECT
                    u.id,
                    u.uuid,
                    u.email,
                    u.phone,
                    u.status,
                    u.last_login_at,
                    p.first_name,
                    p.middle_name,
                    p.last_name
                FROM users u
                LEFT JOIN user_profiles p ON p.user_id = u.id
                WHERE u.id = :id
                LIMIT 1
            SQL
        );

        $statement->execute(['id' => $id]);

        $user = $statement->fetch();

        return $user ?: null;
    }

    public function updateLastLogin(int $id): void
    {
        $statement = $this->pdo->prepare(
            'UPDATE users SET last_login_at = CURRENT_TIMESTAMP WHERE id = :id'
        );

        $statement->execute(['id' => $id]);
    }

        public function findForPasswordReset(
        string $identifier
    ): ?array {
        $statement = $this->pdo->prepare(
            <<<'SQL'
                SELECT
                    id,
                    uuid,
                    email,
                    phone,
                    status
                FROM users
                WHERE email = :email
                OR phone = :phone
                LIMIT 1
            SQL
        );

        $statement->execute([
            'email' => $identifier,
            'phone' => $identifier,
        ]);

        $user = $statement->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    public function updatePassword(
        int $userId,
        string $passwordHash
    ): void {
        $statement = $this->pdo->prepare(
            <<<'SQL'
                UPDATE users
                SET
                    password_hash = :password_hash,
                    must_change_password = 0
                WHERE id = :id
            SQL
        );

        $statement->execute([
            'password_hash' => $passwordHash,
            'id' => $userId,
        ]);
    }



}
