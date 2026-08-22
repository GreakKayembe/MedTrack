<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\Repositories\StudentImport;

use MedTrack\Modules\Academic\DTO\StudentImport\StudentImportRow;
use MedTrack\Modules\Academic\DTO\StudentImport\StudentImportValidationResult;
use PDO;
use RuntimeException;

final class StudentImportRowRepository
{
    public function __construct(
        private readonly PDO $pdo
    ) {
    }

    public function create(
        int $studentImportId,
        StudentImportRow $row,
        StudentImportValidationResult $validation
    ): int {
        $statement = $this->pdo->prepare(
            <<<'SQL'
                INSERT INTO student_import_rows (
                    student_import_id,
                    source_row_number,
                    status,
                    duplicate_type,

                    first_name,
                    last_name,
                    email,
                    phone,
                    registration_number,
                    academic_program_code,
                    academic_year_label,
                    study_level_code,
                    cohort_name,
                    birth_date,
                    gender,

                    raw_data,
                    normalized_data,

                    resolved_academic_program_id,
                    resolved_academic_year_id,
                    resolved_study_level_id,
                    resolved_cohort_id,

                    matched_user_id,
                    matched_student_id,
                    matched_enrollment_id,

                    errors_json,
                    warnings_json
                )
                VALUES (
                    :student_import_id,
                    :source_row_number,
                    :status,
                    :duplicate_type,

                    :first_name,
                    :last_name,
                    :email,
                    :phone,
                    :registration_number,
                    :academic_program_code,
                    :academic_year_label,
                    :study_level_code,
                    :cohort_name,
                    :birth_date,
                    :gender,

                    :raw_data,
                    :normalized_data,

                    :resolved_academic_program_id,
                    :resolved_academic_year_id,
                    :resolved_study_level_id,
                    :resolved_cohort_id,

                    :matched_user_id,
                    :matched_student_id,
                    :matched_enrollment_id,

                    :errors_json,
                    :warnings_json
                )
            SQL
        );

        $normalizedData = [
            'first_name' =>
                $row->firstName,

            'last_name' =>
                $row->lastName,

            'email' =>
                $row->email,

            'phone' =>
                $row->phone,

            'registration_number' =>
                $row->registrationNumber,

            'academic_program_code' =>
                $row->academicProgramCode,

            'academic_year_label' =>
                $row->academicYearLabel,

            'study_level_code' =>
                $row->studyLevelCode,

            'cohort_name' =>
                $row->cohortName,

            'birth_date' =>
                $row->birthDate,

            'gender' =>
                $row->gender,
        ];

        /*
         * Pour l'instant, le DTO contient les valeurs normalisées
         * produites par le parser.
         *
         * On conserve donc une représentation JSON de ces données
         * dans raw_data et normalized_data.
         *
         * Plus tard, le parser pourra exposer séparément les valeurs
         * Excel brutes si nous souhaitons conserver les deux versions.
         */
        $rawData = $normalizedData;

        $statement->execute([
            'student_import_id' =>
                $studentImportId,

            'source_row_number' =>
                $row->rowNumber,

            'status' =>
                $validation->status,

            'duplicate_type' =>
                $validation->duplicateType,

            'first_name' =>
                $row->firstName,

            'last_name' =>
                $row->lastName,

            'email' =>
                $this->nullableString(
                    $row->email
                ),

            'phone' =>
                $this->nullableString(
                    $row->phone
                ),

            'registration_number' =>
                $this->nullableString(
                    $row->registrationNumber
                ),

            'academic_program_code' =>
                $this->nullableString(
                    $row->academicProgramCode
                ),

            'academic_year_label' =>
                $this->nullableString(
                    $row->academicYearLabel
                ),

            'study_level_code' =>
                $this->nullableString(
                    $row->studyLevelCode
                ),

            'cohort_name' =>
                $this->nullableString(
                    $row->cohortName
                ),

            'birth_date' =>
                $this->nullableString(
                    $row->birthDate
                ),

            'gender' =>
                $this->nullableString(
                    $row->gender
                ),

            'raw_data' =>
                $this->encodeJson(
                    $rawData
                ),

            'normalized_data' =>
                $this->encodeJson(
                    $normalizedData
                ),

            'resolved_academic_program_id' =>
                $validation->academicProgramId,

            'resolved_academic_year_id' =>
                $validation->academicYearId,

            'resolved_study_level_id' =>
                $validation->studyLevelId,

            'resolved_cohort_id' =>
                $validation->cohortId,

            'matched_user_id' =>
                $validation->matchedUserId,

            'matched_student_id' =>
                $validation->matchedStudentId,

            'matched_enrollment_id' =>
                $validation->matchedEnrollmentId,

            'errors_json' =>
                $validation->errors !== []
                    ? $this->encodeJson(
                        $validation->errors
                    )
                    : null,

            'warnings_json' =>
                $validation->warnings !== []
                    ? $this->encodeJson(
                        $validation->warnings
                    )
                    : null,
        ]);

        $id =
            (int) $this->pdo->lastInsertId();

        if ($id <= 0) {
            throw new RuntimeException(
                sprintf(
                    'Impossible d’enregistrer la ligne Excel %d.',
                    $row->rowNumber
                )
            );
        }

        return $id;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function allForImport(
        int $studentImportId
    ): array {
        $statement = $this->pdo->prepare(
            <<<'SQL'
                SELECT *
                FROM student_import_rows

                WHERE student_import_id = :student_import_id

                ORDER BY source_row_number ASC
            SQL
        );

        $statement->execute([
            'student_import_id' =>
                $studentImportId,
        ]);

        return $statement->fetchAll(
            PDO::FETCH_ASSOC
        );
    }

    public function countForImport(
        int $studentImportId
    ): int {
        $statement = $this->pdo->prepare(
            <<<'SQL'
                SELECT COUNT(*)
                FROM student_import_rows
                WHERE student_import_id = :student_import_id
            SQL
        );

        $statement->execute([
            'student_import_id' =>
                $studentImportId,
        ]);

        return (int) $statement->fetchColumn();
    }

    /**
     * @return array<string, int>
     */
    public function statisticsForImport(
        int $studentImportId
    ): array {
        $statement = $this->pdo->prepare(
            <<<'SQL'
                SELECT
                    status,
                    COUNT(*) AS total

                FROM student_import_rows

                WHERE student_import_id = :student_import_id

                GROUP BY status
            SQL
        );

        $statement->execute([
            'student_import_id' =>
                $studentImportId,
        ]);

        $statistics = [
            'VALID' => 0,
            'WARNING' => 0,
            'ERROR' => 0,
            'EXISTING' => 0,
            'IMPORTED' => 0,
            'FAILED' => 0,
            'SKIPPED' => 0,
        ];

        foreach (
            $statement->fetchAll(PDO::FETCH_ASSOC)
            as $result
        ) {
            $status =
                (string) $result['status'];

            if (
                array_key_exists(
                    $status,
                    $statistics
                )
            ) {
                $statistics[$status] =
                    (int) $result['total'];
            }
        }

        return $statistics;
    }

    public function deleteForImport(
        int $studentImportId
    ): void {
        $statement = $this->pdo->prepare(
            <<<'SQL'
                DELETE FROM student_import_rows
                WHERE student_import_id = :student_import_id
            SQL
        );

        $statement->execute([
            'student_import_id' =>
                $studentImportId,
        ]);
    }

    private function nullableString(
        ?string $value
    ): ?string {
        if ($value === null) {
            return null;
        }

        $value =
            trim($value);

        return $value !== ''
            ? $value
            : null;
    }

    /**
     * @param mixed $value
     */
    private function encodeJson(
        mixed $value
    ): string {
        $json = json_encode(
            $value,
            JSON_THROW_ON_ERROR
            | JSON_UNESCAPED_UNICODE
            | JSON_UNESCAPED_SLASHES
        );

        if (!is_string($json)) {
            throw new RuntimeException(
                'Impossible d’encoder les données de la ligne en JSON.'
            );
        }

        return $json;
    }
}