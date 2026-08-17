<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\Repositories;

use PDO;
use Throwable;

final class MinistryRepository
{
    public function __construct(
        private readonly PDO $pdo
    ) {
    }

    /**
     * Retourne tous les ministères.
     */
    public function all(): array
    {
        $statement =
            $this->pdo->query(
                <<<'SQL'
                    SELECT
                        o.id,
                        o.uuid,
                        o.code,
                        o.name,
                        o.province,
                        o.city,
                        o.address,
                        o.phone,
                        o.email,
                        o.status,
                        o.created_at,
                        o.updated_at,

                        m.ministry_scope

                    FROM organizations o

                    INNER JOIN ministries m
                        ON m.organization_id = o.id

                    WHERE o.type = 'MINISTRY'

                    ORDER BY o.name ASC
                SQL
            );

        return $statement->fetchAll(
            PDO::FETCH_ASSOC
        );
    }

    /**
     * Retourne uniquement les ministères actifs.
     */
    public function allActive(): array
    {
        $statement =
            $this->pdo->query(
                <<<'SQL'
                    SELECT
                        o.id,
                        o.uuid,
                        o.code,
                        o.name,
                        o.province,
                        o.city,

                        m.ministry_scope

                    FROM organizations o

                    INNER JOIN ministries m
                        ON m.organization_id = o.id

                    WHERE o.type = 'MINISTRY'
                      AND o.status = 'ACTIVE'

                    ORDER BY o.name ASC
                SQL
            );

        return $statement->fetchAll(
            PDO::FETCH_ASSOC
        );
    }

    /**
     * Recherche un ministère par son identifiant.
     */
    public function findById(
        int $id
    ): ?array {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    SELECT
                        o.id,
                        o.uuid,
                        o.code,
                        o.name,
                        o.province,
                        o.city,
                        o.address,
                        o.phone,
                        o.email,
                        o.status,
                        o.created_at,
                        o.updated_at,

                        m.ministry_scope

                    FROM organizations o

                    INNER JOIN ministries m
                        ON m.organization_id = o.id

                    WHERE o.id = :id
                      AND o.type = 'MINISTRY'

                    LIMIT 1
                SQL
            );

        $statement->execute([
            'id' => $id,
        ]);

        $ministry =
            $statement->fetch(
                PDO::FETCH_ASSOC
            );

        return $ministry !== false
            ? $ministry
            : null;
    }

    /**
     * Recherche un ministère par UUID.
     */
    public function findByUuid(
        string $uuid
    ): ?array {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    SELECT
                        o.id,
                        o.uuid,
                        o.code,
                        o.name,
                        o.province,
                        o.city,
                        o.address,
                        o.phone,
                        o.email,
                        o.status,
                        o.created_at,
                        o.updated_at,

                        m.ministry_scope

                    FROM organizations o

                    INNER JOIN ministries m
                        ON m.organization_id = o.id

                    WHERE o.uuid = :uuid
                      AND o.type = 'MINISTRY'

                    LIMIT 1
                SQL
            );

        $statement->execute([
            'uuid' => $uuid,
        ]);

        $ministry =
            $statement->fetch(
                PDO::FETCH_ASSOC
            );

        return $ministry !== false
            ? $ministry
            : null;
    }

    /**
     * Vérifie si un code d'organisation existe déjà.
     */
    public function codeExists(
        string $code,
        ?int $exceptId = null
    ): bool {
        $sql = <<<'SQL'
            SELECT 1
            FROM organizations
            WHERE code = :code
        SQL;

        $parameters = [
            'code' => $code,
        ];

        if ($exceptId !== null) {
            $sql .= <<<'SQL'

                AND id <> :except_id
            SQL;

            $parameters['except_id'] =
                $exceptId;
        }

        $sql .= <<<'SQL'

            LIMIT 1
        SQL;

        $statement =
            $this->pdo->prepare(
                $sql
            );

        $statement->execute(
            $parameters
        );

        return $statement->fetchColumn()
            !== false;
    }

    /**
     * Crée un ministère.
     *
     * organizations + ministries sont créés
     * dans une transaction unique.
     */
    public function create(
        string $uuid,
        string $code,
        string $name,
        ?string $province,
        ?string $city,
        ?string $address,
        ?string $phone,
        ?string $email,
        ?string $ministryScope
    ): int {
        $this->pdo->beginTransaction();

        try {
            $organizationStatement =
                $this->pdo->prepare(
                    <<<'SQL'
                        INSERT INTO organizations (
                            uuid,
                            type,
                            code,
                            name,
                            province,
                            city,
                            address,
                            phone,
                            email,
                            status
                        )
                        VALUES (
                            :uuid,
                            'MINISTRY',
                            :code,
                            :name,
                            :province,
                            :city,
                            :address,
                            :phone,
                            :email,
                            'ACTIVE'
                        )
                    SQL
                );

            $organizationStatement->execute([
                'uuid' =>
                    $uuid,

                'code' =>
                    $code,

                'name' =>
                    $name,

                'province' =>
                    $province,

                'city' =>
                    $city,

                'address' =>
                    $address,

                'phone' =>
                    $phone,

                'email' =>
                    $email,
            ]);

            $organizationId =
                (int) $this->pdo
                    ->lastInsertId();

            $ministryStatement =
                $this->pdo->prepare(
                    <<<'SQL'
                        INSERT INTO ministries (
                            organization_id,
                            ministry_scope
                        )
                        VALUES (
                            :organization_id,
                            :ministry_scope
                        )
                    SQL
                );

            $ministryStatement->execute([
                'organization_id' =>
                    $organizationId,

                'ministry_scope' =>
                    $ministryScope,
            ]);

            $this->pdo->commit();

            return $organizationId;
        } catch (Throwable $exception) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            throw $exception;
        }
    }

    /**
     * Met à jour un ministère.
     */
    public function update(
        int $id,
        string $code,
        string $name,
        ?string $province,
        ?string $city,
        ?string $address,
        ?string $phone,
        ?string $email,
        string $status,
        ?string $ministryScope
    ): void {
        $this->pdo->beginTransaction();

        try {
            $organizationStatement =
                $this->pdo->prepare(
                    <<<'SQL'
                        UPDATE organizations
                        SET
                            code = :code,
                            name = :name,
                            province = :province,
                            city = :city,
                            address = :address,
                            phone = :phone,
                            email = :email,
                            status = :status
                        WHERE id = :id
                          AND type = 'MINISTRY'
                    SQL
                );

            $organizationStatement->execute([
                'code' =>
                    $code,

                'name' =>
                    $name,

                'province' =>
                    $province,

                'city' =>
                    $city,

                'address' =>
                    $address,

                'phone' =>
                    $phone,

                'email' =>
                    $email,

                'status' =>
                    $status,

                'id' =>
                    $id,
            ]);

            $ministryStatement =
                $this->pdo->prepare(
                    <<<'SQL'
                        UPDATE ministries
                        SET
                            ministry_scope = :ministry_scope
                        WHERE organization_id = :organization_id
                    SQL
                );

            $ministryStatement->execute([
                'ministry_scope' =>
                    $ministryScope,

                'organization_id' =>
                    $id,
            ]);

            $this->pdo->commit();
        } catch (Throwable $exception) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            throw $exception;
        }
    }
}