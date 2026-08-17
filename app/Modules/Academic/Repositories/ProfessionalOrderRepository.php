<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\Repositories;

use PDO;
use Throwable;

final class ProfessionalOrderRepository
{
    public function __construct(
        private readonly PDO $pdo
    ) {
    }

    /**
     * Retourne tous les ordres professionnels.
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

                        po.profession_code

                    FROM organizations o

                    INNER JOIN professional_orders po
                        ON po.organization_id = o.id

                    WHERE o.type = 'PROFESSIONAL_ORDER'

                    ORDER BY o.name ASC
                SQL
            );

        return $statement->fetchAll(
            PDO::FETCH_ASSOC
        );
    }

    /**
     * Retourne uniquement les ordres professionnels actifs.
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
                        po.profession_code

                    FROM organizations o

                    INNER JOIN professional_orders po
                        ON po.organization_id = o.id

                    WHERE o.type = 'PROFESSIONAL_ORDER'
                      AND o.status = 'ACTIVE'

                    ORDER BY o.name ASC
                SQL
            );

        return $statement->fetchAll(
            PDO::FETCH_ASSOC
        );
    }

    /**
     * Recherche un ordre professionnel par son identifiant.
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

                        po.profession_code

                    FROM organizations o

                    INNER JOIN professional_orders po
                        ON po.organization_id = o.id

                    WHERE o.id = :id
                      AND o.type = 'PROFESSIONAL_ORDER'

                    LIMIT 1
                SQL
            );

        $statement->execute([
            'id' => $id,
        ]);

        $order =
            $statement->fetch(
                PDO::FETCH_ASSOC
            );

        return $order !== false
            ? $order
            : null;
    }

    /**
     * Recherche un ordre professionnel par UUID.
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

                        po.profession_code

                    FROM organizations o

                    INNER JOIN professional_orders po
                        ON po.organization_id = o.id

                    WHERE o.uuid = :uuid
                      AND o.type = 'PROFESSIONAL_ORDER'

                    LIMIT 1
                SQL
            );

        $statement->execute([
            'uuid' => $uuid,
        ]);

        $order =
            $statement->fetch(
                PDO::FETCH_ASSOC
            );

        return $order !== false
            ? $order
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
     * Vérifie si un profession_code existe déjà.
     */
    public function professionCodeExists(
        string $professionCode,
        ?int $exceptOrganizationId = null
    ): bool {
        $sql = <<<'SQL'
            SELECT 1
            FROM professional_orders
            WHERE profession_code = :profession_code
        SQL;

        $parameters = [
            'profession_code' =>
                $professionCode,
        ];

        if ($exceptOrganizationId !== null) {
            $sql .= <<<'SQL'

                AND organization_id <> :except_organization_id
            SQL;

            $parameters['except_organization_id'] =
                $exceptOrganizationId;
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
     * Crée un ordre professionnel.
     *
     * organizations + professional_orders sont créés
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
        string $professionCode
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
                            'PROFESSIONAL_ORDER',
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

            $orderStatement =
                $this->pdo->prepare(
                    <<<'SQL'
                        INSERT INTO professional_orders (
                            organization_id,
                            profession_code
                        )
                        VALUES (
                            :organization_id,
                            :profession_code
                        )
                    SQL
                );

            $orderStatement->execute([
                'organization_id' =>
                    $organizationId,

                'profession_code' =>
                    $professionCode,
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
     * Met à jour un ordre professionnel.
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
        string $professionCode
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
                          AND type = 'PROFESSIONAL_ORDER'
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

            $orderStatement =
                $this->pdo->prepare(
                    <<<'SQL'
                        UPDATE professional_orders
                        SET
                            profession_code = :profession_code
                        WHERE organization_id = :organization_id
                    SQL
                );

            $orderStatement->execute([
                'profession_code' =>
                    $professionCode,

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