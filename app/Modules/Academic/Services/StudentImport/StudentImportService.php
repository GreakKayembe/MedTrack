<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\Services\StudentImport;

use MedTrack\Modules\Academic\Repositories\StudentImport\StudentImportRepository;
use MedTrack\Modules\Academic\Repositories\StudentImport\StudentImportRowRepository;
use PDO;
use Throwable;

final class StudentImportService
{
    public function __construct(
        private readonly PDO $pdo,
        private readonly StudentImportParser $parser,
        private readonly StudentImportValidator $validator,
        private readonly StudentImportRepository $importRepository,
        private readonly StudentImportRowRepository $rowRepository
    ) {
    }

    /**
     * Analyse un fichier Excel et persiste sa prévalidation.
     *
     * IMPORTANT :
     * cette méthode ne crée encore aucun user,
     * student ou academic_enrollment.
     *
     * @return array{
     *     import_id:int,
     *     uuid:string,
     *     status:string,
     *     total_rows:int,
     *     valid_rows:int,
     *     warning_rows:int,
     *     error_rows:int,
     *     existing_rows:int
     * }
     */
    public function prepare(
        string $filePath,
        int $universityId,
        int $uploadedByUserId
    ): array {
        if (!is_file($filePath)) {
            throw new \RuntimeException(
                sprintf(
                    'Le fichier d’import est introuvable : %s',
                    $filePath
                )
            );
        }

        if (!is_readable($filePath)) {
            throw new \RuntimeException(
                sprintf(
                    'Le fichier d’import n’est pas lisible : %s',
                    $filePath
                )
            );
        }

        if ($universityId <= 0) {
            throw new \InvalidArgumentException(
                'L’identifiant de l’université est invalide.'
            );
        }

        if ($uploadedByUserId <= 0) {
            throw new \InvalidArgumentException(
                'L’utilisateur ayant envoyé le fichier est invalide.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Métadonnées du fichier
        |--------------------------------------------------------------------------
        */

        $uuid = $this->generateUuidV4();

        $originalFilename =
            basename($filePath);

        $fileSize =
            filesize($filePath);

        if ($fileSize === false) {
            $fileSize = null;
        }

        $mimeType =
            mime_content_type($filePath);

        if ($mimeType === false) {
            $mimeType = null;
        }

        $fileSha256 =
            hash_file(
                'sha256',
                $filePath
            );

        if ($fileSha256 === false) {
            $fileSha256 = null;
        }

        /*
        |--------------------------------------------------------------------------
        | Transaction
        |--------------------------------------------------------------------------
        */

        $this->pdo->beginTransaction();

        try {
            /*
            |--------------------------------------------------------------------------
            | Création du batch
            |--------------------------------------------------------------------------
            */

            $importId =
                $this->importRepository->create(
                    uuid:
                        $uuid,

                    universityId:
                        $universityId,

                    uploadedByUserId:
                        $uploadedByUserId,

                    originalFilename:
                        $originalFilename,

                    storedFilename:
                        $originalFilename,

                    storagePath:
                        $filePath,

                    mimeType:
                        $mimeType,

                    fileSize:
                        $fileSize !== null
                            ? (int) $fileSize
                            : null,

                    fileSha256:
                        $fileSha256,

                    templateVersion:
                        '1.0'
                );

            /*
            |--------------------------------------------------------------------------
            | Validation en cours
            |--------------------------------------------------------------------------
            */

            $this->importRepository
                ->markValidating(
                    $importId,
                    $universityId
                );

            /*
            |--------------------------------------------------------------------------
            | Parsing Excel
            |--------------------------------------------------------------------------
            */

            $rows =
                $this->parser->parse(
                    $filePath
                );

            /*
            |--------------------------------------------------------------------------
            | Validation + persistance des lignes
            |--------------------------------------------------------------------------
            */

            foreach ($rows as $row) {
                $validation =
                    $this->validator->validate(
                        $row,
                        $universityId
                    );

                $this->rowRepository->create(
                    $importId,
                    $row,
                    $validation
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Statistiques
            |--------------------------------------------------------------------------
            */

            $statistics =
                $this->rowRepository
                    ->statisticsForImport(
                        $importId
                    );

            $totalRows =
                count($rows);

            $validRows =
                $statistics['VALID'];

            $warningRows =
                $statistics['WARNING'];

            $errorRows =
                $statistics['ERROR'];

            $existingRows =
                $statistics['EXISTING'];

            $this->importRepository
                ->updateValidationStatistics(
                    id:
                        $importId,

                    universityId:
                        $universityId,

                    totalRows:
                        $totalRows,

                    validRows:
                        $validRows,

                    warningRows:
                        $warningRows,

                    errorRows:
                        $errorRows,

                    existingRows:
                        $existingRows
                );

            /*
            |--------------------------------------------------------------------------
            | READY
            |--------------------------------------------------------------------------
            */

            $this->importRepository
                ->markReady(
                    $importId,
                    $universityId
                );

            /*
            |--------------------------------------------------------------------------
            | Commit
            |--------------------------------------------------------------------------
            */

            $this->pdo->commit();

            return [
                'import_id' =>
                    $importId,

                'uuid' =>
                    $uuid,

                'status' =>
                    'READY',

                'total_rows' =>
                    $totalRows,

                'valid_rows' =>
                    $validRows,

                'warning_rows' =>
                    $warningRows,

                'error_rows' =>
                    $errorRows,

                'existing_rows' =>
                    $existingRows,
            ];

        } catch (Throwable $exception) {
            if (
                $this->pdo->inTransaction()
            ) {
                $this->pdo->rollBack();
            }

            throw $exception;
        }
    }

    /**
     * Retourne un import et ses lignes pour l'écran
     * de prévisualisation.
     *
     * @return array{
     *     import:array<string,mixed>,
     *     rows:list<array<string,mixed>>
     * }|null
     */
    public function preview(
        int $importId,
        int $universityId
    ): ?array {
        $import =
            $this->importRepository
                ->findByIdForUniversity(
                    $importId,
                    $universityId
                );

        if ($import === null) {
            return null;
        }

        $rows =
            $this->rowRepository
                ->allForImport(
                    $importId
                );

        return [
            'import' =>
                $import,

            'rows' =>
                $rows,
        ];
    }

    private function generateUuidV4(): string
    {
        $bytes =
            random_bytes(16);

        /*
         * Version 4
         */
        $bytes[6] =
            chr(
                (
                    ord($bytes[6])
                    & 0x0f
                )
                | 0x40
            );

        /*
         * RFC 4122 variant
         */
        $bytes[8] =
            chr(
                (
                    ord($bytes[8])
                    & 0x3f
                )
                | 0x80
            );

        $hex =
            bin2hex($bytes);

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