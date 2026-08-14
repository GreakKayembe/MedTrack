<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\Repositories;

use PDO;

final class FacultyRepository
{
    public function __construct(
        private readonly PDO $pdo
    ) {
    }

    /**
     * Retourne toutes les facultés avec leur université.
     */
    public function all(): array
    {
        $statement = $this->pdo->query(
            <<<'SQL'
            SELECT
                f.id,
                f.university_id,
                f.code,
                f.name,
                f.status,

                o.uuid AS university_uuid,
                o.code AS university_code,
                o.name AS university_name

            FROM faculties f

            INNER JOIN universities u
                ON u.organization_id = f.university_id

            INNER JOIN organizations o
                ON o.id = u.organization_id

            ORDER BY
                o.name ASC,
                f.name ASC
            SQL
        );

        return $statement->fetchAll(
            PDO::FETCH_ASSOC
        );
    }

    /**
     * Retourne les facultés d'une université.
     */
    public function findByUniversity(
        int $universityId
    ): array {
        $statement = $this->pdo->prepare(
            <<<'SQL'
            SELECT
                f.id,
                f.university_id,
                f.code,
                f.name,
                f.status
            FROM faculties f
            WHERE f.university_id = :university_id
            ORDER BY f.name ASC
            SQL
        );

        $statement->execute([
            'university_id' => $universityId,
        ]);

        return $statement->fetchAll(
            PDO::FETCH_ASSOC
        );
    }

    /**
     * Recherche une faculté par son identifiant.
     */
    public function findById(
        int $id
    ): ?array {
        $statement = $this->pdo->prepare(
            <<<'SQL'
            SELECT
                f.id,
                f.university_id,
                f.code,
                f.name,
                f.status,

                o.uuid AS university_uuid,
                o.code AS university_code,
                o.name AS university_name

            FROM faculties f

            INNER JOIN universities u
                ON u.organization_id = f.university_id

            INNER JOIN organizations o
                ON o.id = u.organization_id

            WHERE f.id = :id

            LIMIT 1
            SQL
        );

        $statement->execute([
            'id' => $id,
        ]);

        $faculty = $statement->fetch(
            PDO::FETCH_ASSOC
        );

        return $faculty !== false
            ? $faculty
            : null;
    }

    /**
     * Vérifie qu'une université existe.
     */
    public function universityExists(
        int $universityId
    ): bool {
        $statement = $this->pdo->prepare(
            <<<'SQL'
            SELECT 1
            FROM universities
            WHERE organization_id = :university_id
            LIMIT 1
            SQL
        );

        $statement->execute([
            'university_id' => $universityId,
        ]);

        return $statement->fetchColumn() !== false;
    }

    /**
     * Vérifie si une faculté portant ce nom existe déjà
     * dans la même université.
     */
    public function nameExistsForUniversity(
        int $universityId,
        string $name,
        ?int $exceptId = null
    ): bool {
        $sql =
            <<<'SQL'
            SELECT 1
            FROM faculties
            WHERE university_id = :university_id
              AND name = :name
            SQL;

        $parameters = [
            'university_id' => $universityId,
            'name' => $name,
        ];

        if ($exceptId !== null) {
            $sql .= ' AND id <> :except_id';

            $parameters['except_id'] =
                $exceptId;
        }

        $sql .= ' LIMIT 1';

        $statement = $this->pdo->prepare(
            $sql
        );

        $statement->execute(
            $parameters
        );

        return $statement->fetchColumn() !== false;
    }

    /**
     * Crée une faculté.
     */
    public function create(
        array $data
    ): int {
        $statement = $this->pdo->prepare(
            <<<'SQL'
            INSERT INTO faculties (
                university_id,
                code,
                name,
                status
            )
            VALUES (
                :university_id,
                :code,
                :name,
                :status
            )
            SQL
        );

        $statement->execute([
            'university_id' =>
                $data['university_id'],

            'code' =>
                $data['code'],

            'name' =>
                $data['name'],

            'status' =>
                $data['status'],
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Met à jour une faculté.
     */
    public function update(
        int $id,
        array $data
    ): void {
        $statement = $this->pdo->prepare(
            <<<'SQL'
            UPDATE faculties
            SET
                university_id = :university_id,
                code = :code,
                name = :name,
                status = :status
            WHERE id = :id
            SQL
        );

        $statement->execute([
            'university_id' =>
                $data['university_id'],

            'code' =>
                $data['code'],

            'name' =>
                $data['name'],

            'status' =>
                $data['status'],

            'id' => $id,
        ]);
    }
}