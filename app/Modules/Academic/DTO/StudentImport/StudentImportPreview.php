<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\DTO\StudentImport;

final readonly class StudentImportPreview
{
    public function __construct(
        public int $totalRows,
        public int $validRows,
        public int $warningRows,
        public int $errorRows,
        public int $existingRows,
    ) {
    }

    public function importableRows(): int
    {
        return
            $this->validRows
            + $this->warningRows
            + $this->existingRows;
    }

    public function hasErrors(): bool
    {
        return $this->errorRows > 0;
    }

    public function isEmpty(): bool
    {
        return $this->totalRows === 0;
    }

    /**
     * @return array<string, int>
     */
    public function toArray(): array
    {
        return [
            'total_rows' =>
                $this->totalRows,

            'valid_rows' =>
                $this->validRows,

            'warning_rows' =>
                $this->warningRows,

            'error_rows' =>
                $this->errorRows,

            'existing_rows' =>
                $this->existingRows,

            'importable_rows' =>
                $this->importableRows(),
        ];
    }
}