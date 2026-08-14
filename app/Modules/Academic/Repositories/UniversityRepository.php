<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\Repositories;

use PDO;
use Throwable;

final class UniversityRepository
{
    public function __construct(
        private readonly PDO $pdo
    ) {
    }

    /**
     * Retourne toutes les universités.
     *
     * Les informations générales proviennent de organizations,
     * tandis que les informations académiques spécifiques
     * proviennent de universities.
     */
    public function all(): array
    {
        $statement = $this->pdo->query(
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
                u.university_type,
                u.accreditation_status,
                u.accreditation_score
            FROM organizations o
            INNER JOIN universities u
                ON u.organization_id = o.id
            WHERE o.type = 'UNIVERSITY'
            ORDER BY o.name ASC
            SQL
        );

        return $statement->fetchAll(
            PDO::FETCH_ASSOC
        );
    }

    /**
     * Retourne uniquement les universités actives.
     *
     * Cette méthode sera notamment utilisée pour les listes
     * de sélection dans les autres modules.
     */
    public function allActive(): array
    {
        $statement = $this->pdo->query(
            <<<'SQL'
            SELECT
                o.id,
                o.uuid,
                o.code,
                o.name,
                o.province,
                o.city,
                u.university_type,
                u.accreditation_status,
                u.accreditation_score
            FROM organizations o
            INNER JOIN universities u
                ON u.organization_id = o.id
            WHERE o.type = 'UNIVERSITY'
              AND o.status = 'ACTIVE'
            ORDER BY o.name ASC
            SQL
        );

        return $statement->fetchAll(
            PDO::FETCH_ASSOC
        );
    }

    /**
     * Recherche une université par son identifiant interne.
     */
    public function findById(
        int $id
    ): ?array {
        $statement = $this->pdo->prepare(
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
                u.university_type,
                u.accreditation_status,
                u.accreditation_score
            FROM organizations o
            INNER JOIN universities u
                ON u.organization_id = o.id
            WHERE o.id = :id
              AND o.type = 'UNIVERSITY'
            LIMIT 1
            SQL
        );

        $statement->execute([
            'id' => $id,
        ]);

        $university = $statement->fetch(
            PDO::FETCH_ASSOC
        );

        return $university !== false
            ? $university
            : null;
    }

    /**
     * Recherche une université par son UUID public.
     */
    public function findByUuid(
        string $uuid
    ): ?array {
        $statement = $this->pdo->prepare(
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
                u.university_type,
                u.accreditation_status,
                u.accreditation_score
            FROM organizations o
            INNER JOIN universities u
                ON u.organization_id = o.id
            WHERE o.uuid = :uuid
              AND o.type = 'UNIVERSITY'
            LIMIT 1
            SQL
        );

        $statement->execute([
            'uuid' => $uuid,
        ]);

        $university = $statement->fetch(
            PDO::FETCH_ASSOC
        );

        return $university !== false
            ? $university
            : null;
    }

    /**
     * Vérifie si un code d'organisation est déjà utilisé.
     */
    public function codeExists(
        string $code,
        ?int $exceptId = null
    ): bool {
        if ($exceptId === null) {
            $statement = $this->pdo->prepare(
                <<<'SQL'
                SELECT 1
                FROM organizations
                WHERE code = :code
                LIMIT 1
                SQL
            );

            $statement->execute([
                'code' => $code,
            ]);
        } else {
            $statement = $this->pdo->prepare(
                <<<'SQL'
                SELECT 1
                FROM organizations
                WHERE code = :code
                  AND id <> :except_id
                LIMIT 1
                SQL
            );

            $statement->execute([
                'code' => $code,
                'except_id' => $exceptId,
            ]);
        }

        return $statement->fetchColumn() !== false;
    }

    /**
     * Crée une université.
     *
     * Une université est composée de deux enregistrements :
     *
     * organizations
     *      +
     * universities
     *
     * Les deux insertions sont donc exécutées dans une seule
     * transaction afin d'éviter une organisation universitaire
     * partiellement créée.
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
        ?string $universityType,
        string $accreditationStatus = 'PENDING',
        ?float $accreditationScore = null
    ): int {
        $this->pdo->beginTransaction();

        try {
            $organizationStatement = $this->pdo->prepare(
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
                    'UNIVERSITY',
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
                'uuid' => $uuid,
                'code' => $code,
                'name' => $name,
                'province' => $province,
                'city' => $city,
                'address' => $address,
                'phone' => $phone,
                'email' => $email,
            ]);

            $organizationId = (int) $this->pdo->lastInsertId();

            $universityStatement = $this->pdo->prepare(
                <<<'SQL'
                INSERT INTO universities (
                    organization_id,
                    university_type,
                    accreditation_status,
                    accreditation_score
                )
                VALUES (
                    :organization_id,
                    :university_type,
                    :accreditation_status,
                    :accreditation_score
                )
                SQL
            );

            $universityStatement->execute([
                'organization_id' => $organizationId,
                'university_type' => $universityType,
                'accreditation_status' => $accreditationStatus,
                'accreditation_score' => $accreditationScore,
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
     * Met à jour les informations d'une université.
     *
     * Les deux tables sont modifiées dans une transaction unique.
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
        ?string $universityType,
        string $accreditationStatus,
        ?float $accreditationScore
    ): void {
        $this->pdo->beginTransaction();

        try {
            $organizationStatement = $this->pdo->prepare(
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
                  AND type = 'UNIVERSITY'
                SQL
            );

            $organizationStatement->execute([
                'code' => $code,
                'name' => $name,
                'province' => $province,
                'city' => $city,
                'address' => $address,
                'phone' => $phone,
                'email' => $email,
                'status' => $status,
                'id' => $id,
            ]);

            $universityStatement = $this->pdo->prepare(
                <<<'SQL'
                UPDATE universities
                SET
                    university_type = :university_type,
                    accreditation_status = :accreditation_status,
                    accreditation_score = :accreditation_score
                WHERE organization_id = :organization_id
                SQL
            );

            $universityStatement->execute([
                'university_type' => $universityType,
                'accreditation_status' => $accreditationStatus,
                'accreditation_score' => $accreditationScore,
                'organization_id' => $id,
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