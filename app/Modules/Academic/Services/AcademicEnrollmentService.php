<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\Services;

use InvalidArgumentException;
use MedTrack\Modules\Academic\Repositories\AcademicEnrollmentRepository;
use PDO;
use RuntimeException;

final class AcademicEnrollmentService
{
    private const STATUSES = [
        'PENDING',
        'ACTIVE',
        'SUSPENDED',
        'COMPLETED',
        'CANCELLED',
    ];

    public function __construct(
        private readonly AcademicEnrollmentRepository $repository,
        private readonly PDO $pdo
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | List - Platform
    |--------------------------------------------------------------------------
    */

    public function all(): array
    {
        return $this->repository->all();
    }

    /*
    |--------------------------------------------------------------------------
    | List - University
    |--------------------------------------------------------------------------
    */

    public function allForUniversity(
        int $universityId
    ): array {
        if ($universityId <= 0) {
            throw new InvalidArgumentException(
                'Identifiant d’université invalide.'
            );
        }

        return $this->repository
            ->allForUniversity(
                $universityId
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Find - Platform
    |--------------------------------------------------------------------------
    */

    public function find(
        int $id
    ): ?array {
        if ($id <= 0) {
            return null;
        }

        return $this->repository->find(
            $id
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Find - University
    |--------------------------------------------------------------------------
    */

    public function findForUniversity(
        int $id,
        int $universityId
    ): ?array {
        if (
            $id <= 0
            || $universityId <= 0
        ) {
            return null;
        }

        return $this->repository
            ->findForUniversity(
                $id,
                $universityId
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Find or fail - Platform
    |--------------------------------------------------------------------------
    */

    public function findOrFail(
        int $id
    ): array {
        if ($id <= 0) {
            throw new InvalidArgumentException(
                'Identifiant d’inscription académique invalide.'
            );
        }

        $enrollment =
            $this->repository->find(
                $id
            );

        if ($enrollment === null) {
            throw new RuntimeException(
                'Inscription académique introuvable.'
            );
        }

        return $enrollment;
    }

    /*
    |--------------------------------------------------------------------------
    | Find or fail - University
    |--------------------------------------------------------------------------
    */

    public function findOrFailForUniversity(
        int $id,
        int $universityId
    ): array {
        if ($id <= 0) {
            throw new InvalidArgumentException(
                'Identifiant d’inscription académique invalide.'
            );
        }

        if ($universityId <= 0) {
            throw new InvalidArgumentException(
                'Identifiant d’université invalide.'
            );
        }

        $enrollment =
            $this->repository
                ->findForUniversity(
                    $id,
                    $universityId
                );

        if ($enrollment === null) {
            throw new RuntimeException(
                'Inscription académique introuvable '
                . 'dans cette université.'
            );
        }

        return $enrollment;
    }

    /*
    |--------------------------------------------------------------------------
    | Student enrollments - Platform
    |--------------------------------------------------------------------------
    */

    public function findByStudent(
        int $studentId
    ): array {
        if ($studentId <= 0) {
            return [];
        }

        return $this->repository
            ->findByStudent(
                $studentId
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Student enrollments - University
    |--------------------------------------------------------------------------
    */

    public function findByStudentAndUniversity(
        int $studentId,
        int $universityId
    ): array {
        if (
            $studentId <= 0
            || $universityId <= 0
        ) {
            return [];
        }

        return $this->repository
            ->findByStudentAndUniversity(
                $studentId,
                $universityId
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Create - Platform
    |--------------------------------------------------------------------------
    */

    public function create(
        array $data
    ): int {
        $normalized =
            $this->validateAndNormalize(
                $data
            );

        $this->validateRelations(
            $normalized
        );

        $this->validateDuplicates(
            $normalized
        );

        return $this->repository->create(
            $normalized
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Create - University
    |--------------------------------------------------------------------------
    */

    public function createForUniversity(
        int $universityId,
        array $data
    ): int {
        if ($universityId <= 0) {
            throw new InvalidArgumentException(
                'Identifiant d’université invalide.'
            );
        }

        /*
         * L'université provenant du contexte
         * est prioritaire sur toute valeur envoyée
         * par le navigateur.
         *
         * Cela empêche une université de créer
         * une inscription au nom d'une autre.
         */
        $data['university_id'] =
            $universityId;

        $normalized =
            $this->validateAndNormalize(
                $data
            );

        $this->validateRelations(
            $normalized
        );

        $this->validateDuplicates(
            $normalized
        );

        return $this->repository->create(
            $normalized
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Update - Platform
    |--------------------------------------------------------------------------
    */

    public function update(
        int $id,
        array $data
    ): void {
        $this->findOrFail(
            $id
        );

        $normalized =
            $this->validateAndNormalize(
                $data
            );

        $this->validateRelations(
            $normalized
        );

        $this->validateDuplicates(
            $normalized,
            $id
        );

        $this->repository->update(
            $id,
            $normalized
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Update - University
    |--------------------------------------------------------------------------
    */

    public function updateForUniversity(
        int $id,
        int $universityId,
        array $data
    ): void {
        /*
         * Vérifie d'abord que l'inscription
         * appartient réellement à l'université.
         */
        $this->findOrFailForUniversity(
            $id,
            $universityId
        );

        /*
         * On force toujours l'université depuis
         * le contexte serveur.
         *
         * Une valeur university_id falsifiée dans
         * le formulaire ne peut donc pas déplacer
         * l'inscription vers une autre université.
         */
        $data['university_id'] =
            $universityId;

        $normalized =
            $this->validateAndNormalize(
                $data
            );

        $this->validateRelations(
            $normalized
        );

        $this->validateDuplicates(
            $normalized,
            $id
        );

        $this->repository->update(
            $id,
            $normalized
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Validation and normalization
    |--------------------------------------------------------------------------
    */

    private function validateAndNormalize(
        array $data
    ): array {
        $studentId =
            $this->positiveInteger(
                $data['student_id']
                    ?? null,
                'L’étudiant est obligatoire.'
            );

        $universityId =
            $this->positiveInteger(
                $data['university_id']
                    ?? null,
                'L’université est obligatoire.'
            );

        $academicProgramId =
            $this->positiveInteger(
                $data['academic_program_id']
                    ?? null,
                'Le programme académique est obligatoire.'
            );

        $academicYearId =
            $this->positiveInteger(
                $data['academic_year_id']
                    ?? null,
                'L’année académique est obligatoire.'
            );

        $studyLevelId =
            $this->positiveInteger(
                $data['study_level_id']
                    ?? null,
                'Le niveau d’études est obligatoire.'
            );

        /*
        |--------------------------------------------------------------------------
        | Cohort
        |--------------------------------------------------------------------------
        */

        $rawCohortId =
            trim(
                (string) (
                    $data['cohort_id']
                    ?? ''
                )
            );

        $cohortId = null;

        if ($rawCohortId !== '') {
            $cohortId =
                $this->positiveInteger(
                    $rawCohortId,
                    'La cohorte sélectionnée est invalide.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Registration number
        |--------------------------------------------------------------------------
        */

        $registrationNumber =
            strtoupper(
                trim(
                    (string) (
                        $data['registration_number']
                        ?? ''
                    )
                )
            );

        if ($registrationNumber === '') {
            throw new InvalidArgumentException(
                'Le matricule est obligatoire.'
            );
        }

        if (
            mb_strlen(
                $registrationNumber
            ) > 80
        ) {
            throw new InvalidArgumentException(
                'Le matricule ne peut pas dépasser 80 caractères.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        $status =
            strtoupper(
                trim(
                    (string) (
                        $data['status']
                        ?? 'ACTIVE'
                    )
                )
            );

        if (
            !in_array(
                $status,
                self::STATUSES,
                true
            )
        ) {
            throw new InvalidArgumentException(
                'Le statut de l’inscription académique est invalide.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Enrollment date
        |--------------------------------------------------------------------------
        */

        $enrolledAt =
            trim(
                (string) (
                    $data['enrolled_at']
                    ?? ''
                )
            );

        if ($enrolledAt === '') {
            $enrolledAt = null;
        } elseif (
            !$this->isValidDate(
                $enrolledAt
            )
        ) {
            throw new InvalidArgumentException(
                'La date d’inscription est invalide.'
            );
        }

        return [
            'student_id' =>
                $studentId,

            'university_id' =>
                $universityId,

            'academic_program_id' =>
                $academicProgramId,

            'academic_year_id' =>
                $academicYearId,

            'study_level_id' =>
                $studyLevelId,

            'cohort_id' =>
                $cohortId,

            'registration_number' =>
                $registrationNumber,

            'status' =>
                $status,

            'enrolled_at' =>
                $enrolledAt,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relation validation
    |--------------------------------------------------------------------------
    */

    private function validateRelations(
        array $data
    ): void {
        $this->assertStudentExists(
            $data['student_id']
        );

        $this->assertUniversityExists(
            $data['university_id']
        );

        $this->assertAcademicProgramMatchesUniversity(
            $data['academic_program_id'],
            $data['university_id']
        );

        $this->assertAcademicYearExists(
            $data['academic_year_id']
        );

        $this->assertStudyLevelExists(
            $data['study_level_id']
        );

        if ($data['cohort_id'] !== null) {
            $this->assertCohortMatches(
                $data['cohort_id'],
                $data['academic_program_id'],
                $data['academic_year_id']
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Student validation
    |--------------------------------------------------------------------------
    */

    private function assertStudentExists(
        int $studentId
    ): void {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    SELECT 1
                    FROM students
                    WHERE id = :id
                    LIMIT 1
                SQL
            );

        $statement->execute([
            'id' =>
                $studentId,
        ]);

        if (
            $statement->fetchColumn()
            === false
        ) {
            throw new InvalidArgumentException(
                'L’étudiant sélectionné n’existe pas.'
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | University validation
    |--------------------------------------------------------------------------
    */

    private function assertUniversityExists(
        int $universityId
    ): void {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    SELECT 1
                    FROM universities
                    WHERE organization_id = :id
                    LIMIT 1
                SQL
            );

        $statement->execute([
            'id' =>
                $universityId,
        ]);

        if (
            $statement->fetchColumn()
            === false
        ) {
            throw new InvalidArgumentException(
                'L’université sélectionnée n’existe pas.'
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Program / University validation
    |--------------------------------------------------------------------------
    */

    private function assertAcademicProgramMatchesUniversity(
        int $academicProgramId,
        int $universityId
    ): void {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    SELECT 1
                    FROM academic_programs
                    WHERE id = :program_id
                      AND university_id = :university_id
                    LIMIT 1
                SQL
            );

        $statement->execute([
            'program_id' =>
                $academicProgramId,

            'university_id' =>
                $universityId,
        ]);

        if (
            $statement->fetchColumn()
            === false
        ) {
            throw new InvalidArgumentException(
                'Le programme académique sélectionné '
                . 'n’appartient pas à cette université.'
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Academic year validation
    |--------------------------------------------------------------------------
    */

    private function assertAcademicYearExists(
        int $academicYearId
    ): void {
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

        if (
            $statement->fetchColumn()
            === false
        ) {
            throw new InvalidArgumentException(
                'L’année académique sélectionnée n’existe pas.'
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Study level validation
    |--------------------------------------------------------------------------
    */

    private function assertStudyLevelExists(
        int $studyLevelId
    ): void {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    SELECT 1
                    FROM study_levels
                    WHERE id = :id
                    LIMIT 1
                SQL
            );

        $statement->execute([
            'id' =>
                $studyLevelId,
        ]);

        if (
            $statement->fetchColumn()
            === false
        ) {
            throw new InvalidArgumentException(
                'Le niveau d’études sélectionné n’existe pas.'
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Cohort validation
    |--------------------------------------------------------------------------
    */

    private function assertCohortMatches(
        int $cohortId,
        int $academicProgramId,
        int $academicYearId
    ): void {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    SELECT 1
                    FROM cohorts
                    WHERE id = :cohort_id
                      AND academic_program_id = :program_id
                      AND academic_year_id = :academic_year_id
                    LIMIT 1
                SQL
            );

        $statement->execute([
            'cohort_id' =>
                $cohortId,

            'program_id' =>
                $academicProgramId,

            'academic_year_id' =>
                $academicYearId,
        ]);

        if (
            $statement->fetchColumn()
            === false
        ) {
            throw new InvalidArgumentException(
                'La cohorte sélectionnée ne correspond '
                . 'pas au programme et à l’année académique choisis.'
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Duplicate validation
    |--------------------------------------------------------------------------
    */

    private function validateDuplicates(
        array $data,
        ?int $excludeId = null
    ): void {
        if (
            $this->repository
                ->existsEnrollment(
                    $data['student_id'],
                    $data['university_id'],
                    $data['academic_year_id'],
                    $data['academic_program_id'],
                    $excludeId
                )
        ) {
            throw new InvalidArgumentException(
                'Cet étudiant possède déjà une inscription '
                . 'pour ce programme, cette université '
                . 'et cette année académique.'
            );
        }

        if (
            $this->repository
                ->existsRegistrationNumber(
                    $data['university_id'],
                    $data['academic_year_id'],
                    $data['registration_number'],
                    $excludeId
                )
        ) {
            throw new InvalidArgumentException(
                'Ce matricule est déjà utilisé dans cette '
                . 'université pour cette année académique.'
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Positive integer
    |--------------------------------------------------------------------------
    */

    private function positiveInteger(
        mixed $value,
        string $message
    ): int {
        if (
            is_int($value)
            && $value > 0
        ) {
            return $value;
        }

        if (
            is_string($value)
            && ctype_digit($value)
        ) {
            $integer =
                (int) $value;

            if ($integer > 0) {
                return $integer;
            }
        }

        throw new InvalidArgumentException(
            $message
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Date validation
    |--------------------------------------------------------------------------
    */

    private function isValidDate(
        string $value
    ): bool {
        $date =
            \DateTimeImmutable::createFromFormat(
                '!Y-m-d',
                $value
            );

        if ($date === false) {
            return false;
        }

        $errors =
            \DateTimeImmutable::getLastErrors();

        if (
            is_array($errors)
            && (
                $errors['warning_count'] > 0
                || $errors['error_count'] > 0
            )
        ) {
            return false;
        }

        return $date->format('Y-m-d')
            === $value;
    }
}