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
        public string $academicYearLabel,
        public string $studyLevelCode,
        public ?string $cohortName,
        public ?string $birthDate,
        public ?string $gender,
    ) {
    }

    public function fullName(): string
    {
        return trim(
            $this->firstName
            . ' '
            . $this->lastName
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'row_number' =>
                $this->rowNumber,

            'first_name' =>
                $this->firstName,

            'last_name' =>
                $this->lastName,

            'email' =>
                $this->email,

            'phone' =>
                $this->phone,

            'registration_number' =>
                $this->registrationNumber,

            'academic_program_code' =>
                $this->academicProgramCode,

            'academic_year_label' =>
                $this->academicYearLabel,

            'study_level_code' =>
                $this->studyLevelCode,

            'cohort_name' =>
                $this->cohortName,

            'birth_date' =>
                $this->birthDate,

            'gender' =>
                $this->gender,
        ];
    }
}