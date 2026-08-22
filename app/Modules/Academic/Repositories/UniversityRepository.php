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

    /*
    |--------------------------------------------------------------------------
    | Read
    |--------------------------------------------------------------------------
    */

    /**
     * Retourne toutes les universités.
     *
     * Les informations générales proviennent
     * de organizations tandis que les données
     * académiques proviennent de universities.
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
     * Recherche une université
     * par son identifiant organisationnel.
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

        $university =
            $statement->fetch(
                PDO::FETCH_ASSOC
            );

        return $university !== false
            ? $university
            : null;
    }

    /**
     * Recherche une université par UUID.
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

        $university =
            $statement->fetch(
                PDO::FETCH_ASSOC
            );

        return $university !== false
            ? $university
            : null;
    }

    /*
    |--------------------------------------------------------------------------
    | Uniqueness
    |--------------------------------------------------------------------------
    */

    /**
     * Vérifie si un code d'organisation
     * est déjà utilisé.
     */
    public function codeExists(
        string $code,
        ?int $exceptId = null
    ): bool {
        if ($exceptId === null) {
            $statement =
                $this->pdo->prepare(
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
            $statement =
                $this->pdo->prepare(
                    <<<'SQL'
                        SELECT 1

                        FROM organizations

                        WHERE code = :code
                          AND id <> :except_id

                        LIMIT 1
                    SQL
                );

            $statement->execute([
                'code' =>
                    $code,

                'except_id' =>
                    $exceptId,
            ]);
        }

        return $statement->fetchColumn()
            !== false;
    }

    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    /**
     * Crée une université.
     *
     * Une université est composée de :
     *
     * organizations
     * +
     * universities
     *
     * IMPORTANT :
     * Cette méthode est transaction-aware.
     *
     * - Si aucune transaction n'est active,
     *   elle en ouvre une et la gère elle-même.
     *
     * - Si une transaction est déjà active
     *   (ex. UniversityOnboardingService),
     *   elle rejoint cette transaction et ne
     *   réalise ni COMMIT ni ROLLBACK elle-même.
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
        $ownsTransaction =
            !$this->pdo->inTransaction();

        if ($ownsTransaction) {
            $this->pdo
                ->beginTransaction();
        }

        try {
            /*
            |--------------------------------------------------------------------------
            | Organization
            |--------------------------------------------------------------------------
            */

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

            if ($organizationId <= 0) {
                throw new \RuntimeException(
                    'Impossible de récupérer '
                    . 'l’identifiant de l’université créée.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | University extension
            |--------------------------------------------------------------------------
            */

            $universityStatement =
                $this->pdo->prepare(
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
                'organization_id' =>
                    $organizationId,

                'university_type' =>
                    $universityType,

                'accreditation_status' =>
                    $accreditationStatus,

                'accreditation_score' =>
                    $accreditationScore,
            ]);

            /*
             * Nous ne committons que si cette
             * méthode possède la transaction.
             */
            if ($ownsTransaction) {
                $this->pdo->commit();
            }

            return $organizationId;
        } catch (Throwable $exception) {
            /*
             * Même principe pour le rollback :
             * un repository imbriqué ne doit jamais
             * annuler directement la transaction
             * appartenant au service orchestrateur.
             */
            if (
                $ownsTransaction
                && $this->pdo->inTransaction()
            ) {
                $this->pdo->rollBack();
            }

            throw $exception;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    /**
     * Met à jour une université.
     *
     * Cette méthode est également compatible
     * avec une transaction externe.
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
        $ownsTransaction =
            !$this->pdo->inTransaction();

        if ($ownsTransaction) {
            $this->pdo
                ->beginTransaction();
        }

        try {
            /*
            |--------------------------------------------------------------------------
            | Organization
            |--------------------------------------------------------------------------
            */

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
                          AND type = 'UNIVERSITY'
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

            /*
            |--------------------------------------------------------------------------
            | University extension
            |--------------------------------------------------------------------------
            */

            $universityStatement =
                $this->pdo->prepare(
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
                'university_type' =>
                    $universityType,

                'accreditation_status' =>
                    $accreditationStatus,

                'accreditation_score' =>
                    $accreditationScore,

                'organization_id' =>
                    $id,
            ]);

            if ($ownsTransaction) {
                $this->pdo->commit();
            }
        } catch (Throwable $exception) {
            if (
                $ownsTransaction
                && $this->pdo->inTransaction()
            ) {
                $this->pdo->rollBack();
            }

            throw $exception;
        }
    }
}