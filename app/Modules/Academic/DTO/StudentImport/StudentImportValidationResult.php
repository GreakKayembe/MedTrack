<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\DTO\StudentImport;

final class StudentImportValidationResult
{
    /**
     * @param list<string> $errors
     * @param list<string> $warnings
     */
    public function __construct(
        public readonly int $rowNumber,

        public readonly string $status,

        public readonly array $errors = [],

        public readonly array $warnings = [],

        public readonly ?int $academicProgramId = null,

        public readonly ?int $academicYearId = null,

        public readonly ?int $studyLevelId = null,

        public readonly ?int $cohortId = null,

        public readonly ?int $matchedUserId = null,

        public readonly ?int $matchedStudentId = null,

        public readonly ?int $matchedEnrollmentId = null,

        public readonly string $duplicateType = 'NONE',
    ) {
    }

    public function isValid(): bool
    {
        return $this->status === 'VALID';
    }

    public function hasErrors(): bool
    {
        return $this->errors !== [];
    }

    public function hasWarnings(): bool
    {
        return $this->warnings !== [];
    }

    public function isExisting(): bool
    {
        return $this->status === 'EXISTING';
    }

    public function canBeImported(): bool
    {
        return in_array(
            $this->status,
            [
                'VALID',
                'WARNING',
            ],
            true
        );
    }

    /**
     * Format directement exploitable pour
     * student_import_rows.
     *
     * @return array<string, mixed>
     */
    public function toPersistenceArray(): array
    {
        return [
            'source_row_number' =>
                $this->rowNumber,

            'status' =>
                $this->status,

            'duplicate_type' =>
                $this->duplicateType,

            'resolved_academic_program_id' =>
                $this->academicProgramId,

            'resolved_academic_year_id' =>
                $this->academicYearId,

            'resolved_study_level_id' =>
                $this->studyLevelId,

            'resolved_cohort_id' =>
                $this->cohortId,

            'matched_user_id' =>
                $this->matchedUserId,

            'matched_student_id' =>
                $this->matchedStudentId,

            'matched_enrollment_id' =>
                $this->matchedEnrollmentId,

            'errors_json' =>
                $this->errors !== []
                    ? json_encode(
                        $this->errors,
                        JSON_THROW_ON_ERROR
                        | JSON_UNESCAPED_UNICODE
                    )
                    : null,

            'warnings_json' =>
                $this->warnings !== []
                    ? json_encode(
                        $this->warnings,
                        JSON_THROW_ON_ERROR
                        | JSON_UNESCAPED_UNICODE
                    )
                    : null,
        ];
    }

    /**
     * Format utile pour l'interface de prévisualisation.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'row_number' =>
                $this->rowNumber,

            'status' =>
                $this->status,

            'errors' =>
                $this->errors,

            'warnings' =>
                $this->warnings,

            'academic_program_id' =>
                $this->academicProgramId,

            'academic_year_id' =>
                $this->academicYearId,

            'study_level_id' =>
                $this->studyLevelId,

            'cohort_id' =>
                $this->cohortId,

            'matched_user_id' =>
                $this->matchedUserId,

            'matched_student_id' =>
                $this->matchedStudentId,

            'matched_enrollment_id' =>
                $this->matchedEnrollmentId,

            'duplicate_type' =>
                $this->duplicateType,

            'can_be_imported' =>
                $this->canBeImported(),
        ];
    }
}