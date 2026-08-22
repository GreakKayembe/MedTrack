<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\DTO\StudentImport;

final readonly class StudentImportResult
{
    public function __construct(
        public int $totalRows,
        public int $importedRows,
        public int $failedRows,
        public int $skippedRows,
        public int $createdUsers,
        public int $createdStudents,
        public int $createdEnrollments,
    ) {
    }

    public function processedRows(): int
    {
        return
            $this->importedRows
            + $this->failedRows
            + $this->skippedRows;
    }

    public function hasFailures(): bool
    {
        return $this->failedRows > 0;
    }

    public function isSuccessful(): bool
    {
        return
            $this->totalRows > 0
            && $this->failedRows === 0
            && $this->processedRows()
                === $this->totalRows;
    }

    /**
     * @return array<string, int>
     */
    public function toArray(): array
    {
        return [
            'total_rows' =>
                $this->totalRows,

            'imported_rows' =>
                $this->importedRows,

            'failed_rows' =>
                $this->failedRows,

            'skipped_rows' =>
                $this->skippedRows,

            'created_users' =>
                $this->createdUsers,

            'created_students' =>
                $this->createdStudents,

            'created_enrollments' =>
                $this->createdEnrollments,

            'processed_rows' =>
                $this->processedRows(),
        ];
    }
}