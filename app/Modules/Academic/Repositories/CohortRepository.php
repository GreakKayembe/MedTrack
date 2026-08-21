<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\Repositories;

use PDO;

final class CohortRepository
{
    public function __construct(
        private readonly PDO $pdo
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Read - Platform
    |--------------------------------------------------------------------------
    */

    /**
     * Retourne toutes les cohortes.
     *
     * Utilisation :
     * administration centrale MedTrack.
     */
    public function all(): array
    {
        $statement =
            $this->pdo->query(
                <<<'SQL'
                    SELECT
                        c.id,
                        c.academic_program_id,
                        c.academic_year_id,
                        c.name,

                        ap.code AS program_code,
                        ap.name AS program_name,
                        ap.discipline_code,
                        ap.duration_years,
                        ap.status AS program_status,

                        ay.label AS academic_year_label,
                        ay.starts_on,
                        ay.ends_on,
                        ay.status AS academic_year_status,

                        o.id AS university_id,
                        o.code AS university_code,
                        o.name AS university_name,

                        f.id AS faculty_id,
                        f.code AS faculty_code,
                        f.name AS faculty_name

                    FROM cohorts c

                    INNER JOIN academic_programs ap
                        ON ap.id = c.academic_program_id

                    INNER JOIN academic_years ay
                        ON ay.id = c.academic_year_id

                    INNER JOIN universities u
                        ON u.organization_id = ap.university_id

                    INNER JOIN organizations o
                        ON o.id = u.organization_id

                    LEFT JOIN faculties f
                        ON f.id = ap.faculty_id
                       AND f.university_id = ap.university_id

                    ORDER BY
                        ay.starts_on DESC,
                        o.name ASC,
                        ap.name ASC,
                        c.name ASC,
                        c.id ASC
                SQL
            );

        return $statement->fetchAll(
            PDO::FETCH_ASSOC
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Read - University
    |--------------------------------------------------------------------------
    */

    /**
     * Retourne uniquement les cohortes
     * appartenant à une université.
     *
     * Le rattachement universitaire est dérivé
     * du programme académique.
     */
    public function findByUniversity(
        int $universityId
    ): array {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    SELECT
                        c.id,
                        c.academic_program_id,
                        c.academic_year_id,
                        c.name,

                        ap.code AS program_code,
                        ap.name AS program_name,
                        ap.discipline_code,
                        ap.duration_years,
                        ap.status AS program_status,

                        ay.label AS academic_year_label,
                        ay.starts_on,
                        ay.ends_on,
                        ay.status AS academic_year_status,

                        o.id AS university_id,
                        o.code AS university_code,
                        o.name AS university_name,

                        f.id AS faculty_id,
                        f.code AS faculty_code,
                        f.name AS faculty_name

                    FROM cohorts c

                    INNER JOIN academic_programs ap
                        ON ap.id = c.academic_program_id

                    INNER JOIN academic_years ay
                        ON ay.id = c.academic_year_id

                    INNER JOIN universities u
                        ON u.organization_id = ap.university_id

                    INNER JOIN organizations o
                        ON o.id = u.organization_id

                    LEFT JOIN faculties f
                        ON f.id = ap.faculty_id
                       AND f.university_id = ap.university_id

                    WHERE ap.university_id = :university_id

                    ORDER BY
                        ay.starts_on DESC,
                        ap.name ASC,
                        c.name ASC,
                        c.id ASC
                SQL
            );

        $statement->execute([
            'university_id' =>
                $universityId,
        ]);

        return $statement->fetchAll(
            PDO::FETCH_ASSOC
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Find - Platform
    |--------------------------------------------------------------------------
    */

    /**
     * Recherche une cohorte par identifiant.
     *
     * Utilisation :
     * administration centrale.
     */
    public function findById(
        int $id
    ): ?array {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    SELECT
                        c.id,
                        c.academic_program_id,
                        c.academic_year_id,
                        c.name,

                        ap.code AS program_code,
                        ap.name AS program_name,
                        ap.discipline_code,
                        ap.duration_years,
                        ap.status AS program_status,

                        ay.label AS academic_year_label,
                        ay.starts_on,
                        ay.ends_on,
                        ay.status AS academic_year_status,

                        o.id AS university_id,
                        o.code AS university_code,
                        o.name AS university_name,

                        f.id AS faculty_id,
                        f.code AS faculty_code,
                        f.name AS faculty_name

                    FROM cohorts c

                    INNER JOIN academic_programs ap
                        ON ap.id = c.academic_program_id

                    INNER JOIN academic_years ay
                        ON ay.id = c.academic_year_id

                    INNER JOIN universities u
                        ON u.organization_id = ap.university_id

                    INNER JOIN organizations o
                        ON o.id = u.organization_id

                    LEFT JOIN faculties f
                        ON f.id = ap.faculty_id
                       AND f.university_id = ap.university_id

                    WHERE c.id = :id

                    LIMIT 1
                SQL
            );

        $statement->execute([
            'id' =>
                $id,
        ]);

        $cohort =
            $statement->fetch(
                PDO::FETCH_ASSOC
            );

        return $cohort !== false
            ? $cohort
            : null;
    }

    /*
    |--------------------------------------------------------------------------
    | Find - University
    |--------------------------------------------------------------------------
    */

    /**
     * Recherche une cohorte uniquement si
     * son programme appartient à l'université indiquée.
     *
     * Cette méthode empêche l'accès inter-universités
     * via manipulation de l'URL.
     */
    public function findByIdForUniversity(
        int $id,
        int $universityId
    ): ?array {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    SELECT
                        c.id,
                        c.academic_program_id,
                        c.academic_year_id,
                        c.name,

                        ap.code AS program_code,
                        ap.name AS program_name,
                        ap.discipline_code,
                        ap.duration_years,
                        ap.status AS program_status,

                        ay.label AS academic_year_label,
                        ay.starts_on,
                        ay.ends_on,
                        ay.status AS academic_year_status,

                        o.id AS university_id,
                        o.code AS university_code,
                        o.name AS university_name,

                        f.id AS faculty_id,
                        f.code AS faculty_code,
                        f.name AS faculty_name

                    FROM cohorts c

                    INNER JOIN academic_programs ap
                        ON ap.id = c.academic_program_id

                    INNER JOIN academic_years ay
                        ON ay.id = c.academic_year_id

                    INNER JOIN universities u
                        ON u.organization_id = ap.university_id

                    INNER JOIN organizations o
                        ON o.id = u.organization_id

                    LEFT JOIN faculties f
                        ON f.id = ap.faculty_id
                       AND f.university_id = ap.university_id

                    WHERE c.id = :id
                      AND ap.university_id = :university_id

                    LIMIT 1
                SQL
            );

        $statement->execute([
            'id' =>
                $id,

            'university_id' =>
                $universityId,
        ]);

        $cohort =
            $statement->fetch(
                PDO::FETCH_ASSOC
            );

        return $cohort !== false
            ? $cohort
            : null;
    }

    /*
    |--------------------------------------------------------------------------
    | Referential checks
    |--------------------------------------------------------------------------
    */

    /**
     * Vérifie l'existence d'un programme académique.
     */
    public function academicProgramExists(
        int $academicProgramId
    ): bool {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    SELECT 1

                    FROM academic_programs

                    WHERE id = :id

                    LIMIT 1
                SQL
            );

        $statement->execute([
            'id' =>
                $academicProgramId,
        ]);

        return $statement->fetchColumn()
            !== false;
    }

    /**
     * Vérifie qu'un programme appartient
     * à une université.
     */
    public function academicProgramBelongsToUniversity(
        int $academicProgramId,
        int $universityId
    ): bool {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    SELECT 1

                    FROM academic_programs

                    WHERE id = :academic_program_id
                      AND university_id = :university_id

                    LIMIT 1
                SQL
            );

        $statement->execute([
            'academic_program_id' =>
                $academicProgramId,

            'university_id' =>
                $universityId,
        ]);

        return $statement->fetchColumn()
            !== false;
    }

    /**
     * Vérifie l'existence d'une année académique.
     */
    public function academicYearExists(
        int $academicYearId
    ): bool {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    SELECT 1

                    FROM academic_years

                    WHERE id = :id

                    LIMIT 1
                SQL
            );

        $statement->execute([
            'id' =>
                $academicYearId,
        ]);

        return $statement->fetchColumn()
            !== false;
    }

    /*
    |--------------------------------------------------------------------------
    | Uniqueness
    |--------------------------------------------------------------------------
    */

    /**
     * Vérifie l'unicité métier d'une cohorte.
     */
    public function exists(
        int $academicProgramId,
        int $academicYearId,
        string $name,
        ?int $exceptId = null
    ): bool {
        $sql =
            <<<'SQL'
                SELECT 1

                FROM cohorts

                WHERE academic_program_id = :academic_program_id
                  AND academic_year_id = :academic_year_id
                  AND name = :name
            SQL;

        $parameters = [
            'academic_program_id' =>
                $academicProgramId,

            'academic_year_id' =>
                $academicYearId,

            'name' =>
                $name,
        ];

        if ($exceptId !== null) {
            $sql .=
                ' AND id <> :except_id';

            $parameters['except_id'] =
                $exceptId;
        }

        $sql .=
            ' LIMIT 1';

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

    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    /**
     * Crée une cohorte.
     */
    public function create(
        array $data
    ): int {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    INSERT INTO cohorts (
                        academic_program_id,
                        academic_year_id,
                        name
                    )
                    VALUES (
                        :academic_program_id,
                        :academic_year_id,
                        :name
                    )
                SQL
            );

        $statement->execute([
            'academic_program_id' =>
                $data['academic_program_id'],

            'academic_year_id' =>
                $data['academic_year_id'],

            'name' =>
                $data['name'],
        ]);

        return (int) $this->pdo
            ->lastInsertId();
    }

    /*
    |--------------------------------------------------------------------------
    | Update - Platform
    |--------------------------------------------------------------------------
    */

    /**
     * Met à jour une cohorte.
     *
     * Utilisation :
     * administration centrale.
     */
    public function update(
        int $id,
        array $data
    ): void {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    UPDATE cohorts

                    SET
                        academic_program_id =
                            :academic_program_id,

                        academic_year_id =
                            :academic_year_id,

                        name =
                            :name

                    WHERE id = :id
                SQL
            );

        $statement->execute([
            'academic_program_id' =>
                $data['academic_program_id'],

            'academic_year_id' =>
                $data['academic_year_id'],

            'name' =>
                $data['name'],

            'id' =>
                $id,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Update - University
    |--------------------------------------------------------------------------
    */

    /**
     * Met à jour une cohorte uniquement
     * si elle appartient à l'université indiquée.
     *
     * Le contrôle se fait via le programme actuel
     * de la cohorte.
     */
    public function updateForUniversity(
        int $id,
        int $universityId,
        array $data
    ): bool {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    UPDATE cohorts c

                    INNER JOIN academic_programs current_program
                        ON current_program.id = c.academic_program_id

                    SET
                        c.academic_program_id =
                            :academic_program_id,

                        c.academic_year_id =
                            :academic_year_id,

                        c.name =
                            :name

                    WHERE c.id = :id
                      AND current_program.university_id =
                            :university_id
                SQL
            );

        $statement->execute([
            'academic_program_id' =>
                $data['academic_program_id'],

            'academic_year_id' =>
                $data['academic_year_id'],

            'name' =>
                $data['name'],

            'id' =>
                $id,

            'university_id' =>
                $universityId,
        ]);

        return $statement->rowCount() > 0;
    }

    /*
    |--------------------------------------------------------------------------
    | Metrics
    |--------------------------------------------------------------------------
    */

    /**
     * Compte les cohortes d'une université.
     */
    public function countByUniversity(
        int $universityId
    ): int {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    SELECT COUNT(*)

                    FROM cohorts c

                    INNER JOIN academic_programs ap
                        ON ap.id = c.academic_program_id

                    WHERE ap.university_id = :university_id
                SQL
            );

        $statement->execute([
            'university_id' =>
                $universityId,
        ]);

        return (int) $statement
            ->fetchColumn();
    }

    /**
     * Compte les cohortes d'une université
     * pour une année académique donnée.
     */
    public function countByUniversityAndAcademicYear(
        int $universityId,
        int $academicYearId
    ): int {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    SELECT COUNT(*)

                    FROM cohorts c

                    INNER JOIN academic_programs ap
                        ON ap.id = c.academic_program_id

                    WHERE ap.university_id = :university_id
                      AND c.academic_year_id = :academic_year_id
                SQL
            );

        $statement->execute([
            'university_id' =>
                $universityId,

            'academic_year_id' =>
                $academicYearId,
        ]);

        return (int) $statement
            ->fetchColumn();
    }
}