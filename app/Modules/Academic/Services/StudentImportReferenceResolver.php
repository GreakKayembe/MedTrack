<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\Services\StudentImport;

use PDO;

final class StudentImportReferenceResolver
{
    public function __construct(
        private readonly PDO $pdo
    ) {
    }

    public function resolveProgram(
        int $universityId,
        string $code
    ): ?array {
        $statement = $this->pdo->prepare(
            <<<'SQL'
                SELECT
                    id,
                    university_id,
                    faculty_id,
                    code,
                    name,
                    discipline_code,
                    duration_years,
                    status

                FROM academic_programs

                WHERE university_id = :university_id
                  AND code = :code

                LIMIT 1
            SQL
        );

        $statement->execute([
            'university_id' => $universityId,
            'code' => $code,
        ]);

        $result = $statement->fetch(
            PDO::FETCH_ASSOC
        );

        return $result !== false
            ? $result
            : null;
    }

    public function resolveAcademicYear(
        string $label
    ): ?array {
        $statement = $this->pdo->prepare(
            <<<'SQL'
                SELECT
                    id,
                    label,
                    starts_on,
                    ends_on,
                    status

                FROM academic_years

                WHERE label = :label

                LIMIT 1
            SQL
        );

        $statement->execute([
            'label' => $label,
        ]);

        $result = $statement->fetch(
            PDO::FETCH_ASSOC
        );

        return $result !== false
            ? $result
            : null;
    }

    public function resolveStudyLevel(
        string $code
    ): ?array {
        $statement = $this->pdo->prepare(
            <<<'SQL'
                SELECT
                    id,
                    code,
                    name,
                    ordinal

                FROM study_levels

                WHERE code = :code

                LIMIT 1
            SQL
        );

        $statement->execute([
            'code' => $code,
        ]);

        $result = $statement->fetch(
            PDO::FETCH_ASSOC
        );

        return $result !== false
            ? $result
            : null;
    }

    public function resolveCohort(
        int $programId,
        int $academicYearId,
        string $name
    ): ?array {
        $statement = $this->pdo->prepare(
            <<<'SQL'
                SELECT
                    id,
                    academic_program_id,
                    academic_year_id,
                    name

                FROM cohorts

                WHERE academic_program_id = :program_id
                  AND academic_year_id = :academic_year_id
                  AND name = :name

                LIMIT 1
            SQL
        );

        $statement->execute([
            'program_id' => $programId,
            'academic_year_id' => $academicYearId,
            'name' => $name,
        ]);

        $result = $statement->fetch(
            PDO::FETCH_ASSOC
        );

        return $result !== false
            ? $result
            : null;
    }

    public function findUserByEmail(
        string $email
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

                LIMIT 1
            SQL
        );

        $statement->execute([
            'email' => $email,
        ]);

        $result = $statement->fetch(
            PDO::FETCH_ASSOC
        );

        return $result !== false
            ? $result
            : null;
    }

    public function findStudentByEmail(
        string $email
    ): ?array {
        $statement = $this->pdo->prepare(
            <<<'SQL'
                SELECT
                    id,
                    uuid,
                    user_id,
                    email,
                    phone,
                    national_student_number,
                    status

                FROM students

                WHERE email = :email

                LIMIT 1
            SQL
        );

        $statement->execute([
            'email' => $email,
        ]);

        $result = $statement->fetch(
            PDO::FETCH_ASSOC
        );

        return $result !== false
            ? $result
            : null;
    }

    public function findEnrollmentByRegistrationNumber(
        int $universityId,
        int $academicYearId,
        string $registrationNumber
    ): ?array {
        $statement = $this->pdo->prepare(
            <<<'SQL'
                SELECT
                    id,
                    student_id,
                    university_id,
                    academic_program_id,
                    academic_year_id,
                    study_level_id,
                    cohort_id,
                    registration_number,
                    status

                FROM academic_enrollments

                WHERE university_id = :university_id
                  AND academic_year_id = :academic_year_id
                  AND registration_number = :registration_number

                LIMIT 1
            SQL
        );

        $statement->execute([
            'university_id' => $universityId,
            'academic_year_id' => $academicYearId,
            'registration_number' => $registrationNumber,
        ]);

        $result = $statement->fetch(
            PDO::FETCH_ASSOC
        );

        return $result !== false
            ? $result
            : null;
    }
}