<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\Repositories;

use PDO;
use Throwable;

final class HospitalRepository
{
    public function __construct(
        private readonly PDO $pdo
    ) {
    }

    /**
     * Retourne tous les hôpitaux.
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

                        h.facility_level,
                        h.specialty,
                        h.internship_capacity,
                        h.accreditation_status,
                        h.latitude,
                        h.longitude

                    FROM organizations o

                    INNER JOIN hospitals h
                        ON h.organization_id = o.id

                    WHERE o.type = 'HOSPITAL'

                    ORDER BY o.name ASC
                SQL
            );

        return $statement->fetchAll(
            PDO::FETCH_ASSOC
        );
    }

    /**
     * Retourne uniquement les hôpitaux actifs.
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

                        h.facility_level,
                        h.specialty,
                        h.internship_capacity,
                        h.accreditation_status

                    FROM organizations o

                    INNER JOIN hospitals h
                        ON h.organization_id = o.id

                    WHERE o.type = 'HOSPITAL'
                      AND o.status = 'ACTIVE'

                    ORDER BY o.name ASC
                SQL
            );

        return $statement->fetchAll(
            PDO::FETCH_ASSOC
        );
    }

    /**
     * Recherche un hôpital par son identifiant.
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

                        h.facility_level,
                        h.specialty,
                        h.internship_capacity,
                        h.accreditation_status,
                        h.latitude,
                        h.longitude

                    FROM organizations o

                    INNER JOIN hospitals h
                        ON h.organization_id = o.id

                    WHERE o.id = :id
                      AND o.type = 'HOSPITAL'

                    LIMIT 1
                SQL
            );

        $statement->execute([
            'id' => $id,
        ]);

        $hospital =
            $statement->fetch(
                PDO::FETCH_ASSOC
            );

        return $hospital !== false
            ? $hospital
            : null;
    }

    /**
     * Recherche un hôpital par UUID.
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

                        h.facility_level,
                        h.specialty,
                        h.internship_capacity,
                        h.accreditation_status,
                        h.latitude,
                        h.longitude

                    FROM organizations o

                    INNER JOIN hospitals h
                        ON h.organization_id = o.id

                    WHERE o.uuid = :uuid
                      AND o.type = 'HOSPITAL'

                    LIMIT 1
                SQL
            );

        $statement->execute([
            'uuid' => $uuid,
        ]);

        $hospital =
            $statement->fetch(
                PDO::FETCH_ASSOC
            );

        return $hospital !== false
            ? $hospital
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
     * Crée un nouvel hôpital.
     *
     * organizations + hospitals sont créés
     * dans une seule transaction.
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
        ?int $facilityLevel,
        ?string $specialty,
        int $internshipCapacity,
        string $accreditationStatus,
        ?float $latitude,
        ?float $longitude
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
                            'HOSPITAL',
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

            $hospitalStatement =
                $this->pdo->prepare(
                    <<<'SQL'
                        INSERT INTO hospitals (
                            organization_id,
                            facility_level,
                            specialty,
                            internship_capacity,
                            accreditation_status,
                            latitude,
                            longitude
                        )
                        VALUES (
                            :organization_id,
                            :facility_level,
                            :specialty,
                            :internship_capacity,
                            :accreditation_status,
                            :latitude,
                            :longitude
                        )
                    SQL
                );

            $hospitalStatement->execute([
                'organization_id' =>
                    $organizationId,

                'facility_level' =>
                    $facilityLevel,

                'specialty' =>
                    $specialty,

                'internship_capacity' =>
                    $internshipCapacity,

                'accreditation_status' =>
                    $accreditationStatus,

                'latitude' =>
                    $latitude,

                'longitude' =>
                    $longitude,
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
     * Met à jour un hôpital.
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
        ?int $facilityLevel,
        ?string $specialty,
        int $internshipCapacity,
        string $accreditationStatus,
        ?float $latitude,
        ?float $longitude
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
                          AND type = 'HOSPITAL'
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

            $hospitalStatement =
                $this->pdo->prepare(
                    <<<'SQL'
                        UPDATE hospitals
                        SET
                            facility_level = :facility_level,
                            specialty = :specialty,
                            internship_capacity = :internship_capacity,
                            accreditation_status = :accreditation_status,
                            latitude = :latitude,
                            longitude = :longitude
                        WHERE organization_id = :organization_id
                    SQL
                );

            $hospitalStatement->execute([
                'facility_level' =>
                    $facilityLevel,

                'specialty' =>
                    $specialty,

                'internship_capacity' =>
                    $internshipCapacity,

                'accreditation_status' =>
                    $accreditationStatus,

                'latitude' =>
                    $latitude,

                'longitude' =>
                    $longitude,

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