<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\Services\StudentImport;

use MedTrack\Modules\Academic\Services\AcademicEnrollmentService;
use MedTrack\Modules\Academic\Services\StudentService;
use MedTrack\Modules\Identity\Repositories\OrganizationOnboardingRepository;
use PDO;
use RuntimeException;
use Throwable;

final class StudentOnboardingService
{
    public function __construct(
        private readonly PDO $pdo,
        private readonly OrganizationOnboardingRepository $onboarding,
        private readonly StudentService $students,
        private readonly AcademicEnrollmentService $enrollments
    ) {
    }

    /**
     * Importe définitivement une ligne prévalidée.
     *
     * @param array<string, mixed> $row
     *
     * @return array{
     *     user_id:int,
     *     student_id:int,
     *     enrollment_id:int,
     *     created_user:bool,
     *     created_student:bool,
     *     created_enrollment:bool,
     *     temporary_password:?string
     * }
     */
    public function onboard(
        array $row,
        int $universityId
    ): array {
        if ($universityId <= 0) {
            throw new RuntimeException(
                'Identifiant d’université invalide.'
            );
        }

        $status =
            strtoupper(
                trim(
                    (string) (
                        $row['status']
                        ?? ''
                    )
                )
            );

        if (
            !in_array(
                $status,
                [
                    'VALID',
                    'WARNING',
                ],
                true
            )
        ) {
            throw new RuntimeException(
                'Cette ligne n’est pas importable.'
            );
        }

        $firstName =
            trim(
                (string) (
                    $row['first_name']
                    ?? ''
                )
            );

        $lastName =
            trim(
                (string) (
                    $row['last_name']
                    ?? ''
                )
            );

        $email =
            strtolower(
                trim(
                    (string) (
                        $row['email']
                        ?? ''
                    )
                )
            );

        $phone =
            trim(
                (string) (
                    $row['phone']
                    ?? ''
                )
            );

        $phone =
            $phone !== ''
                ? $phone
                : null;

        $registrationNumber =
            trim(
                (string) (
                    $row['registration_number']
                    ?? ''
                )
            );

        if (
            $firstName === ''
            || $lastName === ''
            || $email === ''
            || $registrationNumber === ''
        ) {
            throw new RuntimeException(
                'La ligne importée est incomplète.'
            );
        }

        if (
            filter_var(
                $email,
                FILTER_VALIDATE_EMAIL
            ) === false
        ) {
            throw new RuntimeException(
                'Adresse e-mail invalide.'
            );
        }

        $programId =
            $this->positiveInteger(
                $row[
                    'resolved_academic_program_id'
                ]
                ?? null,
                'Programme académique introuvable.'
            );

        $academicYearId =
            $this->positiveInteger(
                $row[
                    'resolved_academic_year_id'
                ]
                ?? null,
                'Année académique introuvable.'
            );

        $studyLevelId =
            $this->positiveInteger(
                $row[
                    'resolved_study_level_id'
                ]
                ?? null,
                'Niveau d’études introuvable.'
            );

        $cohortId =
            $this->nullablePositiveInteger(
                $row[
                    'resolved_cohort_id'
                ]
                ?? null
            );

        $matchedUserId =
            $this->nullablePositiveInteger(
                $row[
                    'matched_user_id'
                ]
                ?? null
            );

        $matchedStudentId =
            $this->nullablePositiveInteger(
                $row[
                    'matched_student_id'
                ]
                ?? null
            );

        $matchedEnrollmentId =
            $this->nullablePositiveInteger(
                $row[
                    'matched_enrollment_id'
                ]
                ?? null
            );

        if ($matchedEnrollmentId !== null) {
            throw new RuntimeException(
                'Cette inscription académique existe déjà.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Transaction
        |--------------------------------------------------------------------------
        */

        $ownsTransaction =
            !$this->pdo->inTransaction();

        if ($ownsTransaction) {
            $this->pdo->beginTransaction();
        }

        try {
            $createdUser =
                false;

            $createdStudent =
                false;

            $createdEnrollment =
                false;

            $temporaryPassword =
                null;

            /*
            |--------------------------------------------------------------------------
            | User
            |--------------------------------------------------------------------------
            */

            if ($matchedUserId !== null) {
                $userId =
                    $matchedUserId;

            } else {
                if (
                    $this->onboarding
                        ->emailExists(
                            $email
                        )
                ) {
                    throw new RuntimeException(
                        'Un compte utilise déjà cette adresse e-mail.'
                    );
                }

                if (
                    $phone !== null
                    && $this->onboarding
                        ->phoneExists(
                            $phone
                        )
                ) {
                    throw new RuntimeException(
                        'Un compte utilise déjà ce numéro de téléphone.'
                    );
                }

                $temporaryPassword =
                    $this->generateTemporaryPassword();

                $passwordHash =
                    password_hash(
                        $temporaryPassword,
                        PASSWORD_DEFAULT
                    );

                if (!is_string($passwordHash)) {
                    throw new RuntimeException(
                        'Impossible de sécuriser le mot de passe temporaire.'
                    );
                }

                $userId =
                    $this->onboarding
                        ->createUser(
                            uuid:
                                $this->generateUuidV4(),

                            email:
                                $email,

                            phone:
                                $phone,

                            passwordHash:
                                $passwordHash
                        );

                $this->onboarding
                    ->createUserProfile(
                        userId:
                            $userId,

                        firstName:
                            $firstName,

                        middleName:
                            null,

                        lastName:
                            $lastName
                    );

                $createdUser =
                    true;
            }

            /*
            |--------------------------------------------------------------------------
            | Student
            |--------------------------------------------------------------------------
            */

            if ($matchedStudentId !== null) {
                $studentId =
                    $matchedStudentId;

            } else {
                $studentId =
                    $this->createStudentLinkedToUser(
                        userId:
                            $userId,

                        row:
                            $row
                    );

                $createdStudent =
                    true;
            }

            /*
            |--------------------------------------------------------------------------
            | Academic enrollment
            |--------------------------------------------------------------------------
            */

            $enrollmentId =
                $this->enrollments
                    ->createForUniversity(
                        $universityId,
                        [
                            'student_id' =>
                                $studentId,

                            'academic_program_id' =>
                                $programId,

                            'academic_year_id' =>
                                $academicYearId,

                            'study_level_id' =>
                                $studyLevelId,

                            'cohort_id' =>
                                $cohortId,

                            'registration_number' =>
                                $registrationNumber,

                            'status' =>
                                'ACTIVE',

                            'enrolled_at' =>
                                date('Y-m-d'),
                        ]
                    );

            $createdEnrollment =
                true;

            if ($ownsTransaction) {
                $this->pdo->commit();
            }

            return [
                'user_id' =>
                    $userId,

                'student_id' =>
                    $studentId,

                'enrollment_id' =>
                    $enrollmentId,

                'created_user' =>
                    $createdUser,

                'created_student' =>
                    $createdStudent,

                'created_enrollment' =>
                    $createdEnrollment,

                'temporary_password' =>
                    $temporaryPassword,
            ];

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

    /**
     * StudentService::createForEnrollment() force user_id = null.
     *
     * Pour l'import, nous devons créer une identité
     * étudiante reliée au compte utilisateur créé/réutilisé.
     *
     * On utilise donc StudentService::create().
     *
     * @param array<string, mixed> $row
     */
    private function createStudentLinkedToUser(
        int $userId,
        array $row
    ): int {
        return $this->students->create(
            [
                'user_id' =>
                    $userId,

                'national_student_number' =>
                    null,

                'first_name' =>
                    (string) (
                        $row['first_name']
                        ?? ''
                    ),

                'middle_name' =>
                    null,

                'last_name' =>
                    (string) (
                        $row['last_name']
                        ?? ''
                    ),

                'gender' =>
                    $this->nullableString(
                        $row['gender']
                        ?? null
                    ),

                'birth_date' =>
                    $this->nullableString(
                        $row['birth_date']
                        ?? null
                    ),

                'birth_place' =>
                    null,

                'nationality' =>
                    null,

                'email' =>
                    $this->nullableString(
                        $row['email']
                        ?? null
                    ),

                'phone' =>
                    $this->nullableString(
                        $row['phone']
                        ?? null
                    ),

                'status' =>
                    'ACTIVE',
            ]
        );
    }

    private function positiveInteger(
        mixed $value,
        string $message
    ): int {
        $id =
            filter_var(
                $value,
                FILTER_VALIDATE_INT,
                [
                    'options' => [
                        'min_range' => 1,
                    ],
                ]
            );

        if ($id === false) {
            throw new RuntimeException(
                $message
            );
        }

        return (int) $id;
    }

    private function nullablePositiveInteger(
        mixed $value
    ): ?int {
        if (
            $value === null
            || $value === ''
        ) {
            return null;
        }

        $id =
            filter_var(
                $value,
                FILTER_VALIDATE_INT,
                [
                    'options' => [
                        'min_range' => 1,
                    ],
                ]
            );

        return $id !== false
            ? (int) $id
            : null;
    }

    private function nullableString(
        mixed $value
    ): ?string {
        if ($value === null) {
            return null;
        }

        $value =
            trim(
                (string) $value
            );

        return $value !== ''
            ? $value
            : null;
    }

    private function generateTemporaryPassword(): string
    {
        return 'MT-'
            . strtoupper(
                bin2hex(
                    random_bytes(5)
                )
            )
            . '!'
            . random_int(
                10,
                99
            );
    }

    private function generateUuidV4(): string
    {
        $bytes =
            random_bytes(16);

        $bytes[6] =
            chr(
                (
                    ord($bytes[6])
                    & 0x0f
                )
                | 0x40
            );

        $bytes[8] =
            chr(
                (
                    ord($bytes[8])
                    & 0x3f
                )
                | 0x80
            );

        $hex =
            bin2hex(
                $bytes
            );

        return sprintf(
            '%s-%s-%s-%s-%s',
            substr($hex, 0, 8),
            substr($hex, 8, 4),
            substr($hex, 12, 4),
            substr($hex, 16, 4),
            substr($hex, 20, 12)
        );
    }
}