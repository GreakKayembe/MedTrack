<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\Services\StudentImport;

use DateTimeImmutable;
use MedTrack\Modules\Academic\DTO\StudentImport\StudentImportRow;
use MedTrack\Modules\Academic\DTO\StudentImport\StudentImportValidationResult;

final class StudentImportValidator
{
    public function __construct(
        private readonly StudentImportReferenceResolver $resolver
    ) {
    }

    public function validate(
        StudentImportRow $row,
        int $universityId
    ): StudentImportValidationResult {
        $errors = [];
        $warnings = [];

        /*
        |--------------------------------------------------------------------------
        | Validation des données de base
        |--------------------------------------------------------------------------
        */

        if (trim($row->firstName) === '') {
            $errors[] = 'Le prénom est obligatoire.';
        }

        if (trim($row->lastName) === '') {
            $errors[] = 'Le nom est obligatoire.';
        }

        if (trim($row->email) === '') {
            $errors[] = 'L’adresse e-mail est obligatoire.';
        } elseif (
            filter_var(
                $row->email,
                FILTER_VALIDATE_EMAIL
            ) === false
        ) {
            $errors[] = 'L’adresse e-mail est invalide.';
        }

        if (trim($row->registrationNumber) === '') {
            $errors[] = 'Le matricule est obligatoire.';
        }

        if (trim($row->academicProgramCode) === '') {
            $errors[] = 'Le programme académique est obligatoire.';
        }

        if (trim($row->academicYearLabel) === '') {
            $errors[] = 'L’année académique est obligatoire.';
        }

        if (trim($row->studyLevelCode) === '') {
            $errors[] = 'Le niveau d’études est obligatoire.';
        }

        /*
        |--------------------------------------------------------------------------
        | Genre
        |--------------------------------------------------------------------------
        */

        if (
            $row->gender !== null
            && !in_array(
                $row->gender,
                [
                    'M',
                    'F',
                    'OTHER',
                    'UNSPECIFIED',
                ],
                true
            )
        ) {
            $errors[] =
                'Le genre doit être M, F, OTHER ou UNSPECIFIED.';
        }

        /*
        |--------------------------------------------------------------------------
        | Date de naissance
        |--------------------------------------------------------------------------
        */

        if (
            $row->birthDate !== null
            && !$this->isValidDate(
                $row->birthDate
            )
        ) {
            $errors[] =
                'La date de naissance doit être au format YYYY-MM-DD.';
        }

        if (
            $row->birthDate !== null
            && $this->isValidDate($row->birthDate)
            && new DateTimeImmutable($row->birthDate)
                > new DateTimeImmutable('today')
        ) {
            $errors[] =
                'La date de naissance ne peut pas être future.';
        }

        /*
        |--------------------------------------------------------------------------
        | Programme académique
        |--------------------------------------------------------------------------
        */

        $program = null;

        if ($row->academicProgramCode !== '') {
            $program =
                $this->resolver->resolveProgram(
                    $universityId,
                    $row->academicProgramCode
                );

            if ($program === null) {
                $errors[] = sprintf(
                    'Le programme académique "%s" '
                    . 'n’existe pas dans cette université.',
                    $row->academicProgramCode
                );
            } elseif (
                ($program['status'] ?? null)
                !== 'ACTIVE'
            ) {
                $errors[] = sprintf(
                    'Le programme académique "%s" '
                    . 'n’est pas actif.',
                    $row->academicProgramCode
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Année académique
        |--------------------------------------------------------------------------
        */

        $academicYear = null;

        if ($row->academicYearLabel !== '') {
            $academicYear =
                $this->resolver->resolveAcademicYear(
                    $row->academicYearLabel
                );

            if ($academicYear === null) {
                $errors[] = sprintf(
                    'L’année académique "%s" n’existe pas.',
                    $row->academicYearLabel
                );
            } elseif (
                ($academicYear['status'] ?? null)
                === 'CLOSED'
            ) {
                $warnings[] = sprintf(
                    'L’année académique "%s" est clôturée.',
                    $row->academicYearLabel
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Niveau d'études
        |--------------------------------------------------------------------------
        */

        $studyLevel = null;

        if ($row->studyLevelCode !== '') {
            $studyLevel =
                $this->resolver->resolveStudyLevel(
                    $row->studyLevelCode
                );

            if ($studyLevel === null) {
                $errors[] = sprintf(
                    'Le niveau d’études "%s" n’existe pas.',
                    $row->studyLevelCode
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Cohorte
        |--------------------------------------------------------------------------
        */

        $cohort = null;

        if ($row->cohortName !== null) {
            if (
                $program !== null
                && $academicYear !== null
            ) {
                $cohort =
                    $this->resolver->resolveCohort(
                        (int) $program['id'],
                        (int) $academicYear['id'],
                        $row->cohortName
                    );

                if ($cohort === null) {
                    $errors[] = sprintf(
                        'La cohorte "%s" ne correspond pas '
                        . 'au programme "%s" et à l’année "%s".',
                        $row->cohortName,
                        $row->academicProgramCode,
                        $row->academicYearLabel
                    );
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Recherche d'identité existante
        |--------------------------------------------------------------------------
        */

        $existingUser = null;
        $existingStudent = null;

        if (
            $row->email !== ''
            && filter_var(
                $row->email,
                FILTER_VALIDATE_EMAIL
            ) !== false
        ) {
            $existingUser =
                $this->resolver->findUserByEmail(
                    $row->email
                );

            $existingStudent =
                $this->resolver->findStudentByEmail(
                    $row->email
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Inscription / matricule existant
        |--------------------------------------------------------------------------
        */

        $existingEnrollment = null;

        if (
            $academicYear !== null
            && $row->registrationNumber !== ''
        ) {
            $existingEnrollment =
                $this->resolver
                    ->findEnrollmentByRegistrationNumber(
                        $universityId,
                        (int) $academicYear['id'],
                        $row->registrationNumber
                    );
        }

        /*
        |--------------------------------------------------------------------------
        | Détermination du doublon
        |--------------------------------------------------------------------------
        */

        $duplicateType = 'NONE';

        if ($existingEnrollment !== null) {
            $duplicateType =
                'EXISTING_ENROLLMENT';

        } elseif ($existingStudent !== null) {
            $duplicateType =
                'EXISTING_STUDENT';

            $warnings[] =
                'Cet étudiant existe déjà dans MedTrack. '
                . 'Son identité pourra être réutilisée.';

        } elseif ($existingUser !== null) {
            $duplicateType =
                'EXISTING_USER';

            $warnings[] =
                'Un compte MedTrack utilise déjà cette '
                . 'adresse e-mail. Le compte existant '
                . 'devra être réutilisé.';
        }

        /*
        |--------------------------------------------------------------------------
        | Détermination du statut
        |--------------------------------------------------------------------------
        */

        if ($errors !== []) {
            $status = 'ERROR';

        } elseif ($existingEnrollment !== null) {
            $status = 'EXISTING';

        } elseif ($warnings !== []) {
            $status = 'WARNING';

        } else {
            $status = 'VALID';
        }

        /*
        |--------------------------------------------------------------------------
        | Résultat
        |--------------------------------------------------------------------------
        */

        return new StudentImportValidationResult(
            rowNumber:
                $row->rowNumber,

            status:
                $status,

            errors:
                $errors,

            warnings:
                $warnings,

            academicProgramId:
                $program !== null
                    ? (int) $program['id']
                    : null,

            academicYearId:
                $academicYear !== null
                    ? (int) $academicYear['id']
                    : null,

            studyLevelId:
                $studyLevel !== null
                    ? (int) $studyLevel['id']
                    : null,

            cohortId:
                $cohort !== null
                    ? (int) $cohort['id']
                    : null,

            matchedUserId:
                $existingUser !== null
                    ? (int) $existingUser['id']
                    : null,

            matchedStudentId:
                $existingStudent !== null
                    ? (int) $existingStudent['id']
                    : null,

            matchedEnrollmentId:
                $existingEnrollment !== null
                    ? (int) $existingEnrollment['id']
                    : null,

            duplicateType:
                $duplicateType,
        );
    }

    private function isValidDate(
        string $date
    ): bool {
        $parsed =
            DateTimeImmutable::createFromFormat(
                '!Y-m-d',
                $date
            );

        if ($parsed === false) {
            return false;
        }

        $errors =
            DateTimeImmutable::getLastErrors();

        if (
            is_array($errors)
            && (
                $errors['warning_count'] > 0
                || $errors['error_count'] > 0
            )
        ) {
            return false;
        }

        return $parsed->format('Y-m-d')
            === $date;
    }
}