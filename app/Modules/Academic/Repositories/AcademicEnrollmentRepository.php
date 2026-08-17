<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\Repositories;

use PDO;

final class AcademicEnrollmentRepository
{
    public function __construct(
        private readonly PDO $pdo
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | All - Platform
    |--------------------------------------------------------------------------
    */

    /**
     * Retourne toutes les inscriptions académiques.
     *
     * Vue globale destinée notamment au contexte PLATFORM.
     */
    public function all(): array
    {
        $sql = <<<'SQL'
            SELECT
                ae.*,

                s.first_name,
                s.middle_name,
                s.last_name,
                s.national_student_number,

                o.name AS university_name,
                o.code AS university_code,

                ap.code AS academic_program_code,
                ap.name AS academic_program_name,

                ay.label AS academic_year_label,

                sl.code AS study_level_code,
                sl.name AS study_level_name,

                c.name AS cohort_name

            FROM academic_enrollments ae

            INNER JOIN students s
                ON s.id = ae.student_id

            INNER JOIN universities u
                ON u.organization_id = ae.university_id

            INNER JOIN organizations o
                ON o.id = u.organization_id

            INNER JOIN academic_programs ap
                ON ap.id = ae.academic_program_id

            INNER JOIN academic_years ay
                ON ay.id = ae.academic_year_id

            INNER JOIN study_levels sl
                ON sl.id = ae.study_level_id

            LEFT JOIN cohorts c
                ON c.id = ae.cohort_id

            ORDER BY
                ae.id DESC
        SQL;

        $statement =
            $this->pdo->query(
                $sql
            );

        return $statement->fetchAll(
            PDO::FETCH_ASSOC
        );
    }

    /*
    |--------------------------------------------------------------------------
    | All - University scope
    |--------------------------------------------------------------------------
    */

    /**
     * Retourne uniquement les inscriptions
     * appartenant à une université.
     */
    public function allForUniversity(
        int $universityId
    ): array {
        $sql = <<<'SQL'
            SELECT
                ae.*,

                s.first_name,
                s.middle_name,
                s.last_name,
                s.national_student_number,

                o.name AS university_name,
                o.code AS university_code,

                ap.code AS academic_program_code,
                ap.name AS academic_program_name,

                ay.label AS academic_year_label,

                sl.code AS study_level_code,
                sl.name AS study_level_name,

                c.name AS cohort_name

            FROM academic_enrollments ae

            INNER JOIN students s
                ON s.id = ae.student_id

            INNER JOIN universities u
                ON u.organization_id = ae.university_id

            INNER JOIN organizations o
                ON o.id = u.organization_id

            INNER JOIN academic_programs ap
                ON ap.id = ae.academic_program_id

            INNER JOIN academic_years ay
                ON ay.id = ae.academic_year_id

            INNER JOIN study_levels sl
                ON sl.id = ae.study_level_id

            LEFT JOIN cohorts c
                ON c.id = ae.cohort_id

            WHERE ae.university_id = :university_id

            ORDER BY
                ae.id DESC
        SQL;

        $statement =
            $this->pdo->prepare(
                $sql
            );

        $statement->execute(
            [
                'university_id' =>
                    $universityId,
            ]
        );

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
     * Recherche une inscription académique
     * par son identifiant.
     *
     * Recherche globale destinée notamment
     * au contexte PLATFORM.
     */
    public function find(
        int $id
    ): ?array {
        $sql = <<<'SQL'
            SELECT
                ae.*,

                s.uuid AS student_uuid,
                s.first_name,
                s.middle_name,
                s.last_name,
                s.national_student_number,
                s.email AS student_email,
                s.phone AS student_phone,

                o.name AS university_name,
                o.code AS university_code,

                ap.code AS academic_program_code,
                ap.name AS academic_program_name,

                ay.label AS academic_year_label,
                ay.starts_on AS academic_year_starts_on,
                ay.ends_on AS academic_year_ends_on,

                sl.code AS study_level_code,
                sl.name AS study_level_name,

                c.name AS cohort_name

            FROM academic_enrollments ae

            INNER JOIN students s
                ON s.id = ae.student_id

            INNER JOIN universities u
                ON u.organization_id = ae.university_id

            INNER JOIN organizations o
                ON o.id = u.organization_id

            INNER JOIN academic_programs ap
                ON ap.id = ae.academic_program_id

            INNER JOIN academic_years ay
                ON ay.id = ae.academic_year_id

            INNER JOIN study_levels sl
                ON sl.id = ae.study_level_id

            LEFT JOIN cohorts c
                ON c.id = ae.cohort_id

            WHERE ae.id = :id

            LIMIT 1
        SQL;

        $statement =
            $this->pdo->prepare(
                $sql
            );

        $statement->execute(
            [
                'id' =>
                    $id,
            ]
        );

        $enrollment =
            $statement->fetch(
                PDO::FETCH_ASSOC
            );

        return $enrollment !== false
            ? $enrollment
            : null;
    }

    /*
    |--------------------------------------------------------------------------
    | Find - University scope
    |--------------------------------------------------------------------------
    */

    /**
     * Recherche une inscription uniquement
     * dans le périmètre d'une université.
     */
    public function findForUniversity(
        int $id,
        int $universityId
    ): ?array {
        $sql = <<<'SQL'
            SELECT
                ae.*,

                s.uuid AS student_uuid,
                s.first_name,
                s.middle_name,
                s.last_name,
                s.national_student_number,
                s.email AS student_email,
                s.phone AS student_phone,

                o.name AS university_name,
                o.code AS university_code,

                ap.code AS academic_program_code,
                ap.name AS academic_program_name,

                ay.label AS academic_year_label,
                ay.starts_on AS academic_year_starts_on,
                ay.ends_on AS academic_year_ends_on,

                sl.code AS study_level_code,
                sl.name AS study_level_name,

                c.name AS cohort_name

            FROM academic_enrollments ae

            INNER JOIN students s
                ON s.id = ae.student_id

            INNER JOIN universities u
                ON u.organization_id = ae.university_id

            INNER JOIN organizations o
                ON o.id = u.organization_id

            INNER JOIN academic_programs ap
                ON ap.id = ae.academic_program_id

            INNER JOIN academic_years ay
                ON ay.id = ae.academic_year_id

            INNER JOIN study_levels sl
                ON sl.id = ae.study_level_id

            LEFT JOIN cohorts c
                ON c.id = ae.cohort_id

            WHERE ae.id = :id
              AND ae.university_id = :university_id

            LIMIT 1
        SQL;

        $statement =
            $this->pdo->prepare(
                $sql
            );

        $statement->execute(
            [
                'id' =>
                    $id,

                'university_id' =>
                    $universityId,
            ]
        );

        $enrollment =
            $statement->fetch(
                PDO::FETCH_ASSOC
            );

        return $enrollment !== false
            ? $enrollment
            : null;
    }

    /*
    |--------------------------------------------------------------------------
    | Student enrollments - Platform
    |--------------------------------------------------------------------------
    */

    /**
     * Retourne toutes les inscriptions
     * académiques d'un étudiant.
     *
     * Cette méthode peut traverser plusieurs universités.
     */
    public function findByStudent(
        int $studentId
    ): array {
        $sql = <<<'SQL'
            SELECT
                ae.*,

                o.name AS university_name,
                o.code AS university_code,

                ap.code AS academic_program_code,
                ap.name AS academic_program_name,

                ay.label AS academic_year_label,

                sl.code AS study_level_code,
                sl.name AS study_level_name,

                c.name AS cohort_name

            FROM academic_enrollments ae

            INNER JOIN universities u
                ON u.organization_id = ae.university_id

            INNER JOIN organizations o
                ON o.id = u.organization_id

            INNER JOIN academic_programs ap
                ON ap.id = ae.academic_program_id

            INNER JOIN academic_years ay
                ON ay.id = ae.academic_year_id

            INNER JOIN study_levels sl
                ON sl.id = ae.study_level_id

            LEFT JOIN cohorts c
                ON c.id = ae.cohort_id

            WHERE ae.student_id = :student_id

            ORDER BY
                ay.starts_on DESC,
                ae.id DESC
        SQL;

        $statement =
            $this->pdo->prepare(
                $sql
            );

        $statement->execute(
            [
                'student_id' =>
                    $studentId,
            ]
        );

        return $statement->fetchAll(
            PDO::FETCH_ASSOC
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Student enrollments - University scope
    |--------------------------------------------------------------------------
    */

    /**
     * Retourne uniquement les inscriptions
     * d'un étudiant dans une université précise.
     *
     * Cette méthode empêche une université
     * de consulter l'historique académique
     * de l'étudiant dans une autre institution.
     */
    public function findByStudentAndUniversity(
        int $studentId,
        int $universityId
    ): array {
        $sql = <<<'SQL'
            SELECT
                ae.*,

                o.name AS university_name,
                o.code AS university_code,

                ap.code AS academic_program_code,
                ap.name AS academic_program_name,

                ay.label AS academic_year_label,

                sl.code AS study_level_code,
                sl.name AS study_level_name,

                c.name AS cohort_name

            FROM academic_enrollments ae

            INNER JOIN universities u
                ON u.organization_id = ae.university_id

            INNER JOIN organizations o
                ON o.id = u.organization_id

            INNER JOIN academic_programs ap
                ON ap.id = ae.academic_program_id

            INNER JOIN academic_years ay
                ON ay.id = ae.academic_year_id

            INNER JOIN study_levels sl
                ON sl.id = ae.study_level_id

            LEFT JOIN cohorts c
                ON c.id = ae.cohort_id

            WHERE ae.student_id = :student_id
              AND ae.university_id = :university_id

            ORDER BY
                ay.starts_on DESC,
                ae.id DESC
        SQL;

        $statement =
            $this->pdo->prepare(
                $sql
            );

        $statement->execute(
            [
                'student_id' =>
                    $studentId,

                'university_id' =>
                    $universityId,
            ]
        );

        return $statement->fetchAll(
            PDO::FETCH_ASSOC
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create(
        array $data
    ): int {
        $sql = <<<'SQL'
            INSERT INTO academic_enrollments (
                student_id,
                university_id,
                academic_program_id,
                academic_year_id,
                study_level_id,
                cohort_id,
                registration_number,
                status,
                enrolled_at
            ) VALUES (
                :student_id,
                :university_id,
                :academic_program_id,
                :academic_year_id,
                :study_level_id,
                :cohort_id,
                :registration_number,
                :status,
                :enrolled_at
            )
        SQL;

        $statement =
            $this->pdo->prepare(
                $sql
            );

        $statement->execute(
            [
                'student_id' =>
                    $data['student_id'],

                'university_id' =>
                    $data['university_id'],

                'academic_program_id' =>
                    $data['academic_program_id'],

                'academic_year_id' =>
                    $data['academic_year_id'],

                'study_level_id' =>
                    $data['study_level_id'],

                'cohort_id' =>
                    $data['cohort_id'],

                'registration_number' =>
                    $data['registration_number'],

                'status' =>
                    $data['status'],

                'enrolled_at' =>
                    $data['enrolled_at'],
            ]
        );

        return (int)
            $this->pdo->lastInsertId();
    }

    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(
        int $id,
        array $data
    ): void {
        $sql = <<<'SQL'
            UPDATE academic_enrollments

            SET
                student_id = :student_id,
                university_id = :university_id,
                academic_program_id = :academic_program_id,
                academic_year_id = :academic_year_id,
                study_level_id = :study_level_id,
                cohort_id = :cohort_id,
                registration_number = :registration_number,
                status = :status,
                enrolled_at = :enrolled_at

            WHERE id = :id
        SQL;

        $statement =
            $this->pdo->prepare(
                $sql
            );

        $statement->execute(
            [
                'student_id' =>
                    $data['student_id'],

                'university_id' =>
                    $data['university_id'],

                'academic_program_id' =>
                    $data['academic_program_id'],

                'academic_year_id' =>
                    $data['academic_year_id'],

                'study_level_id' =>
                    $data['study_level_id'],

                'cohort_id' =>
                    $data['cohort_id'],

                'registration_number' =>
                    $data['registration_number'],

                'status' =>
                    $data['status'],

                'enrolled_at' =>
                    $data['enrolled_at'],

                'id' =>
                    $id,
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Duplicate enrollment
    |--------------------------------------------------------------------------
    */

    public function existsEnrollment(
        int $studentId,
        int $universityId,
        int $academicYearId,
        int $academicProgramId,
        ?int $excludeId = null
    ): bool {
        $sql = <<<'SQL'
            SELECT COUNT(*)

            FROM academic_enrollments

            WHERE student_id = :student_id
              AND university_id = :university_id
              AND academic_year_id = :academic_year_id
              AND academic_program_id = :academic_program_id
        SQL;

        $parameters = [
            'student_id' =>
                $studentId,

            'university_id' =>
                $universityId,

            'academic_year_id' =>
                $academicYearId,

            'academic_program_id' =>
                $academicProgramId,
        ];

        if ($excludeId !== null) {
            $sql .= <<<'SQL'

              AND id <> :exclude_id
            SQL;

            $parameters['exclude_id'] =
                $excludeId;
        }

        $statement =
            $this->pdo->prepare(
                $sql
            );

        $statement->execute(
            $parameters
        );

        return (int)
            $statement->fetchColumn() > 0;
    }

    /*
    |--------------------------------------------------------------------------
    | Registration number duplicate
    |--------------------------------------------------------------------------
    */

    public function existsRegistrationNumber(
        int $universityId,
        int $academicYearId,
        string $registrationNumber,
        ?int $excludeId = null
    ): bool {
        $sql = <<<'SQL'
            SELECT COUNT(*)

            FROM academic_enrollments

            WHERE university_id = :university_id
              AND academic_year_id = :academic_year_id
              AND registration_number = :registration_number
        SQL;

        $parameters = [
            'university_id' =>
                $universityId,

            'academic_year_id' =>
                $academicYearId,

            'registration_number' =>
                $registrationNumber,
        ];

        if ($excludeId !== null) {
            $sql .= <<<'SQL'

              AND id <> :exclude_id
            SQL;

            $parameters['exclude_id'] =
                $excludeId;
        }

        $statement =
            $this->pdo->prepare(
                $sql
            );

        $statement->execute(
            $parameters
        );

        return (int)
            $statement->fetchColumn() > 0;
    }
}