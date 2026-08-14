<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\Repositories;

use PDO;

final class AcademicProgramRepository
{
    public function __construct(
        private readonly PDO $pdo
    ) {
    }

    /**
     * Retourne tous les programmes académiques.
     */
    public function all(): array
    {
        $statement = $this->pdo->query(
            <<<'SQL'
            SELECT
                ap.id,
                ap.university_id,
                ap.faculty_id,
                ap.code,
                ap.name,
                ap.discipline_code,
                ap.duration_years,
                ap.status,

                o.code AS university_code,
                o.name AS university_name,

                f.code AS faculty_code,
                f.name AS faculty_name

            FROM academic_programs ap

            INNER JOIN universities u
                ON u.organization_id = ap.university_id

            INNER JOIN organizations o
                ON o.id = u.organization_id

            LEFT JOIN faculties f
                ON f.id = ap.faculty_id

            ORDER BY
                o.name ASC,
                f.name ASC,
                ap.name ASC
            SQL
        );

        return $statement->fetchAll(
            PDO::FETCH_ASSOC
        );
    }

    /**
     * Recherche un programme académique.
     */
    public function findById(
        int $id
    ): ?array {
        $statement = $this->pdo->prepare(
            <<<'SQL'
            SELECT
                ap.id,
                ap.university_id,
                ap.faculty_id,
                ap.code,
                ap.name,
                ap.discipline_code,
                ap.duration_years,
                ap.status,

                o.code AS university_code,
                o.name AS university_name,

                f.code AS faculty_code,
                f.name AS faculty_name

            FROM academic_programs ap

            INNER JOIN universities u
                ON u.organization_id = ap.university_id

            INNER JOIN organizations o
                ON o.id = u.organization_id

            LEFT JOIN faculties f
                ON f.id = ap.faculty_id

            WHERE ap.id = :id

            LIMIT 1
            SQL
        );

        $statement->execute([
            'id' => $id,
        ]);

        $program = $statement->fetch(
            PDO::FETCH_ASSOC
        );

        return $program !== false
            ? $program
            : null;
    }

    /**
     * Vérifie l'existence d'une université.
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
     * Vérifie qu'une faculté appartient
     * à l'université indiquée.
     */
    public function facultyBelongsToUniversity(
        int $facultyId,
        int $universityId
    ): bool {
        $statement = $this->pdo->prepare(
            <<<'SQL'
            SELECT 1
            FROM faculties
            WHERE id = :faculty_id
              AND university_id = :university_id
            LIMIT 1
            SQL
        );

        $statement->execute([
            'faculty_id' => $facultyId,
            'university_id' => $universityId,
        ]);

        return $statement->fetchColumn() !== false;
    }

    /**
     * Vérifie l'unicité du code du programme
     * au sein d'une université.
     */
    public function codeExistsForUniversity(
        int $universityId,
        string $code,
        ?int $exceptId = null
    ): bool {
        $sql =
            <<<'SQL'
            SELECT 1
            FROM academic_programs
            WHERE university_id = :university_id
              AND code = :code
            SQL;

        $parameters = [
            'university_id' => $universityId,
            'code' => $code,
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
     * Crée un programme académique.
     */
    public function create(
        array $data
    ): int {
        $statement = $this->pdo->prepare(
            <<<'SQL'
            INSERT INTO academic_programs (
                university_id,
                faculty_id,
                code,
                name,
                discipline_code,
                duration_years,
                status
            )
            VALUES (
                :university_id,
                :faculty_id,
                :code,
                :name,
                :discipline_code,
                :duration_years,
                :status
            )
            SQL
        );

        $statement->execute([
            'university_id' =>
                $data['university_id'],

            'faculty_id' =>
                $data['faculty_id'],

            'code' =>
                $data['code'],

            'name' =>
                $data['name'],

            'discipline_code' =>
                $data['discipline_code'],

            'duration_years' =>
                $data['duration_years'],

            'status' =>
                $data['status'],
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Met à jour un programme académique.
     */
    public function update(
        int $id,
        array $data
    ): void {
        $statement = $this->pdo->prepare(
            <<<'SQL'
            UPDATE academic_programs
            SET
                university_id = :university_id,
                faculty_id = :faculty_id,
                code = :code,
                name = :name,
                discipline_code = :discipline_code,
                duration_years = :duration_years,
                status = :status
            WHERE id = :id
            SQL
        );

        $statement->execute([
            'university_id' =>
                $data['university_id'],

            'faculty_id' =>
                $data['faculty_id'],

            'code' =>
                $data['code'],

            'name' =>
                $data['name'],

            'discipline_code' =>
                $data['discipline_code'],

            'duration_years' =>
                $data['duration_years'],

            'status' =>
                $data['status'],

            'id' => $id,
        ]);
    }
}