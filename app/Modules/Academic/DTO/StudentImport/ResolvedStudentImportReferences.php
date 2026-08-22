<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\DTO\StudentImport;

final readonly class ResolvedStudentImportReferences
{
    public function __construct(
        public int $academicProgramId,
        public int $academicYearId,
        public int $studyLevelId,
        public ?int $cohortId,
    ) {
    }

    /**
     * @return array<string, int|null>
     */
    public function toArray(): array
    {
        return [
            'academic_program_id' =>
                $this->academicProgramId,

            'academic_year_id' =>
                $this->academicYearId,

            'study_level_id' =>
                $this->studyLevelId,

            'cohort_id' =>
                $this->cohortId,
        ];
    }
}