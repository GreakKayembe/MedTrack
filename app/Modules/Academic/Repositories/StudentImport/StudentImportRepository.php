<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\Repositories\StudentImport;

use PDO;
use RuntimeException;

final class StudentImportRepository
{
    public function __construct(
        private readonly PDO $pdo
    ) {
    }

    /**
     * Crée le batch représentant le fichier importé.
     */
    public function create(
        string $uuid,
        int $universityId,
        int $uploadedByUserId,
        string $originalFilename,
        ?string $storedFilename,
        ?string $storagePath,
        ?string $mimeType,
        ?int $fileSize,
        ?string $fileSha256,
        string $templateVersion = '1.0'
    ): int {
        $statement = $this->pdo->prepare(
            <<<'SQL'
                INSERT INTO student_imports (
                    uuid,
                    university_id,
                    uploaded_by_user_id,
                    original_filename,
                    stored_filename,
                    storage_path,
                    mime_type,
                    file_size,
                    file_sha256,
                    template_version,
                    status
                )
                VALUES (
                    :uuid,
                    :university_id,
                    :uploaded_by_user_id,
                    :original_filename,
                    :stored_filename,
                    :storage_path,
                    :mime_type,
                    :file_size,
                    :file_sha256,
                    :template_version,
                    'UPLOADED'
                )
            SQL
        );

        $statement->execute([
            'uuid' => $uuid,
            'university_id' => $universityId,
            'uploaded_by_user_id' => $uploadedByUserId,
            'original_filename' => $originalFilename,
            'stored_filename' => $storedFilename,
            'storage_path' => $storagePath,
            'mime_type' => $mimeType,
            'file_size' => $fileSize,
            'file_sha256' => $fileSha256,
            'template_version' => $templateVersion,
        ]);

        $id = (int) $this->pdo->lastInsertId();

        if ($id <= 0) {
            throw new RuntimeException(
                'Impossible de créer le batch d’import des étudiants.'
            );
        }

        return $id;
    }

    public function findByIdForUniversity(
        int $id,
        int $universityId
    ): ?array {
        $statement = $this->pdo->prepare(
            <<<'SQL'
                SELECT *
                FROM student_imports
                WHERE id = :id
                  AND university_id = :university_id
                LIMIT 1
            SQL
        );

        $statement->execute([
            'id' => $id,
            'university_id' => $universityId,
        ]);

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result !== false
            ? $result
            : null;
    }

    public function findByUuidForUniversity(
        string $uuid,
        int $universityId
    ): ?array {
        $statement = $this->pdo->prepare(
            <<<'SQL'
                SELECT *
                FROM student_imports
                WHERE uuid = :uuid
                  AND university_id = :university_id
                LIMIT 1
            SQL
        );

        $statement->execute([
            'uuid' => $uuid,
            'university_id' => $universityId,
        ]);

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result !== false
            ? $result
            : null;
    }

    public function markValidating(
        int $id,
        int $universityId
    ): void {
        $this->changeStatus(
            $id,
            $universityId,
            'VALIDATING'
        );
    }

    public function markReady(
        int $id,
        int $universityId
    ): void {
        $statement = $this->pdo->prepare(
            <<<'SQL'
                UPDATE student_imports

                SET status = 'READY',
                    validated_at = CURRENT_TIMESTAMP

                WHERE id = :id
                  AND university_id = :university_id
            SQL
        );

        $statement->execute([
            'id' => $id,
            'university_id' => $universityId,
        ]);

        $this->assertUpdated(
            $statement->rowCount(),
            $id
        );
    }

    public function markFailed(
        int $id,
        int $universityId
    ): void {
        $this->changeStatus(
            $id,
            $universityId,
            'FAILED'
        );
    }

    /**
     * Met à jour les statistiques après validation.
     */
    public function updateValidationStatistics(
        int $id,
        int $universityId,
        int $totalRows,
        int $validRows,
        int $warningRows,
        int $errorRows,
        int $existingRows
    ): void {
        $statement = $this->pdo->prepare(
            <<<'SQL'
                UPDATE student_imports

                SET total_rows = :total_rows,
                    valid_rows = :valid_rows,
                    warning_rows = :warning_rows,
                    error_rows = :error_rows,
                    existing_rows = :existing_rows

                WHERE id = :id
                  AND university_id = :university_id
            SQL
        );

        $statement->execute([
            'total_rows' => $totalRows,
            'valid_rows' => $validRows,
            'warning_rows' => $warningRows,
            'error_rows' => $errorRows,
            'existing_rows' => $existingRows,
            'id' => $id,
            'university_id' => $universityId,
        ]);

        $this->assertUpdated(
            $statement->rowCount(),
            $id
        );
    }

    private function changeStatus(
        int $id,
        int $universityId,
        string $status
    ): void {
        $statement = $this->pdo->prepare(
            <<<'SQL'
                UPDATE student_imports
                SET status = :status
                WHERE id = :id
                  AND university_id = :university_id
            SQL
        );

        $statement->execute([
            'status' => $status,
            'id' => $id,
            'university_id' => $universityId,
        ]);

        $this->assertUpdated(
            $statement->rowCount(),
            $id
        );
    }

    private function assertUpdated(
        int $affectedRows,
        int $importId
    ): void {
        if ($affectedRows < 1) {
            throw new RuntimeException(
                sprintf(
                    'Import étudiant introuvable ou non modifié : %d.',
                    $importId
                )
            );
        }
    }
}