<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\DTO\StudentImport;

final readonly class StudentImportRow
{
    public function __construct(
        public int $rowNumber,

        public string $firstName,

        public string $lastName,

        public string $email,

        public ?string $phone,

        public string $registrationNumber,

        public string $academicProgramCode,

        public string $academicYearCode,

        public string $studyLevelCode,

        public ?string $cohortCode,

        public ?string $dateOfBirth,

        public ?string $gender,
    ) {
    }
}