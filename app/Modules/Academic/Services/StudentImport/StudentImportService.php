<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\Services\StudentImport;

use InvalidArgumentException;
use MedTrack\Modules\Academic\Repositories\StudentImport\StudentImportRepository;
use MedTrack\Modules\Academic\Repositories\StudentImport\StudentImportRowRepository;
use PDO;
use RuntimeException;
use Throwable;

final class StudentImportService
{
    public function __construct(
        private readonly PDO $pdo,
        private readonly StudentImportParser $parser,
        private readonly StudentImportValidator $validator,
        private readonly StudentImportRepository $importRepository,
        private readonly StudentImportRowRepository $rowRepository,
        private readonly StudentOnboardingService $studentOnboarding
    ) {
    }

    /**
     * Analyse un fichier Excel et persiste sa prévalidation.
     *
     * Aucun user, student ou academic_enrollment
     * n'est créé pendant cette étape.
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
            throw new RuntimeException(
                sprintf(
                    'Le fichier d’import est introuvable : %s',
                    $filePath
                )
            );
        }

        if (!is_readable($filePath)) {
            throw new RuntimeException(
                sprintf(
                    'Le fichier d’import n’est pas lisible : %s',
                    $filePath
                )
            );
        }

        if ($universityId <= 0) {
            throw new InvalidArgumentException(
                'L’identifiant de l’université est invalide.'
            );
        }

        if ($uploadedByUserId <= 0) {
            throw new InvalidArgumentException(
                'L’utilisateur ayant envoyé le fichier est invalide.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Métadonnées
        |--------------------------------------------------------------------------
        */

        $uuid =
            $this->generateUuidV4();

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
            | Batch
            |--------------------------------------------------------------------------
            */

            $importId =
                $this->importRepository
                    ->create(
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
            | Validation
            |--------------------------------------------------------------------------
            */

            $this->importRepository
                ->markValidating(
                    $importId,
                    $universityId
                );

            $rows =
                $this->parser
                    ->parse(
                        $filePath
                    );

            foreach ($rows as $row) {
                $validation =
                    $this->validator
                        ->validate(
                            $row,
                            $universityId
                        );

                $this->rowRepository
                    ->create(
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
                (int) (
                    $statistics['VALID']
                    ?? 0
                );

            $warningRows =
                (int) (
                    $statistics['WARNING']
                    ?? 0
                );

            $errorRows =
                (int) (
                    $statistics['ERROR']
                    ?? 0
                );

            $existingRows =
                (int) (
                    $statistics['EXISTING']
                    ?? 0
                );

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
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            throw $exception;
        }
    }

    /**
     * Retourne un import et ses lignes
     * pour l'écran de prévisualisation.
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
        if (
            $importId <= 0
            || $universityId <= 0
        ) {
            return null;
        }

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

    /**
     * Confirme définitivement un import prévalidé.
     *
     * Les lignes VALID et WARNING sont traitées.
     *
     * Les lignes ERROR et EXISTING sont ignorées.
     *
     * Une erreur sur une ligne ne bloque pas les autres :
     * StudentOnboardingService garantit la transaction
     * de la ligne concernée.
     *
     * @return array{
     *     import_id:int,
     *     status:string,
     *     imported_rows:int,
     *     failed_rows:int,
     *     skipped_rows:int,
     *     created_users:int,
     *     created_students:int,
     *     created_enrollments:int
     * }
     */
    public function confirm(
        int $importId,
        int $universityId,
        int $confirmedByUserId
    ): array {
        if ($importId <= 0) {
            throw new InvalidArgumentException(
                'Identifiant d’import invalide.'
            );
        }

        if ($universityId <= 0) {
            throw new InvalidArgumentException(
                'Identifiant d’université invalide.'
            );
        }

        if ($confirmedByUserId <= 0) {
            throw new InvalidArgumentException(
                'Utilisateur de confirmation invalide.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Import
        |--------------------------------------------------------------------------
        */

        $import =
            $this->importRepository
                ->findByIdForUniversity(
                    $importId,
                    $universityId
                );

        if ($import === null) {
            throw new RuntimeException(
                'Import étudiant introuvable.'
            );
        }

        $status =
            strtoupper(
                trim(
                    (string) (
                        $import['status']
                        ?? ''
                    )
                )
            );

        if ($status !== 'READY') {
            throw new RuntimeException(
                match ($status) {
                    'PROCESSING' =>
                        'Cet import est déjà en cours de traitement.',

                    'COMPLETED',
                    'COMPLETED_WITH_ERRORS' =>
                        'Cet import a déjà été confirmé.',

                    'FAILED' =>
                        'Cet import est en échec et ne peut pas être confirmé.',

                    'CANCELLED' =>
                        'Cet import a été annulé.',

                    default =>
                        'Cet import n’est pas prêt à être confirmé.',
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Verrou logique : READY -> PROCESSING
        |--------------------------------------------------------------------------
        |
        | Cette transition est volontairement séparée du traitement
        | des lignes.
        |
        | Elle empêche deux confirmations concurrentes du même batch.
        |
        */

        $this->importRepository
            ->markProcessing(
                id:
                    $importId,

                universityId:
                    $universityId,

                confirmedByUserId:
                    $confirmedByUserId
            );

        /*
        |--------------------------------------------------------------------------
        | Lignes
        |--------------------------------------------------------------------------
        */

        $rows =
            $this->rowRepository
                ->allForImport(
                    $importId
                );

        $importedRows = 0;
        $failedRows = 0;
        $skippedRows = 0;

        $createdUsers = 0;
        $createdStudents = 0;
        $createdEnrollments = 0;

        foreach ($rows as $row) {
            $rowId =
                (int) (
                    $row['id']
                    ?? 0
                );

            if ($rowId <= 0) {
                ++$failedRows;

                continue;
            }

            $rowStatus =
                strtoupper(
                    trim(
                        (string) (
                            $row['status']
                            ?? ''
                        )
                    )
                );

            /*
            |--------------------------------------------------------------------------
            | Non importable
            |--------------------------------------------------------------------------
            */

            if (
                !in_array(
                    $rowStatus,
                    [
                        'VALID',
                        'WARNING',
                    ],
                    true
                )
            ) {
                try {
                    $this->rowRepository
                        ->markSkipped(
                            $rowId
                        );

                    ++$skippedRows;

                } catch (Throwable) {
                    ++$failedRows;
                }

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Import d'une ligne
            |--------------------------------------------------------------------------
            */

            try {
                $result =
                    $this->studentOnboarding
                        ->onboard(
                            $row,
                            $universityId
                        );

                /*
                 * Les colonnes created_* doivent uniquement
                 * contenir les objets réellement créés pendant
                 * cet import.
                 *
                 * Si un User ou Student existait déjà,
                 * la colonne correspondante reste NULL.
                 */

                $createdUserId =
                    !empty(
                        $result['created_user']
                    )
                        ? (int) (
                            $result['user_id']
                            ?? 0
                        )
                        : null;

                $createdStudentId =
                    !empty(
                        $result['created_student']
                    )
                        ? (int) (
                            $result['student_id']
                            ?? 0
                        )
                        : null;

                $createdEnrollmentId =
                    !empty(
                        $result[
                            'created_enrollment'
                        ]
                    )
                        ? (int) (
                            $result['enrollment_id']
                            ?? 0
                        )
                        : null;

                $createdUserId =
                    $createdUserId !== null
                    && $createdUserId > 0
                        ? $createdUserId
                        : null;

                $createdStudentId =
                    $createdStudentId !== null
                    && $createdStudentId > 0
                        ? $createdStudentId
                        : null;

                $createdEnrollmentId =
                    $createdEnrollmentId !== null
                    && $createdEnrollmentId > 0
                        ? $createdEnrollmentId
                        : null;

                $this->rowRepository
                    ->markImported(
                        rowId:
                            $rowId,

                        createdUserId:
                            $createdUserId,

                        createdStudentId:
                            $createdStudentId,

                        createdEnrollmentId:
                            $createdEnrollmentId
                    );

                ++$importedRows;

                if (
                    !empty(
                        $result['created_user']
                    )
                ) {
                    ++$createdUsers;
                }

                if (
                    !empty(
                        $result['created_student']
                    )
                ) {
                    ++$createdStudents;
                }

                if (
                    !empty(
                        $result[
                            'created_enrollment'
                        ]
                    )
                ) {
                    ++$createdEnrollments;
                }

            } catch (Throwable $exception) {
                ++$failedRows;

                /*
                 * Une erreur de persistance de l'état FAILED
                 * ne doit pas masquer l'erreur métier originale
                 * ni interrompre les autres lignes.
                 */
                try {
                    $this->rowRepository
                        ->markFailed(
                            rowId:
                                $rowId,

                            errors: [
                                $exception
                                    ->getMessage(),
                            ]
                        );

                } catch (Throwable) {
                    // Continuer les autres lignes.
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Statistiques finales
        |--------------------------------------------------------------------------
        */

        $this->importRepository
            ->updateImportStatistics(
                id:
                    $importId,

                universityId:
                    $universityId,

                importedRows:
                    $importedRows,

                failedRows:
                    $failedRows,

                skippedRows:
                    $skippedRows,

                createdUsers:
                    $createdUsers,

                createdStudents:
                    $createdStudents,

                createdEnrollments:
                    $createdEnrollments
            );

        /*
        |--------------------------------------------------------------------------
        | État final
        |--------------------------------------------------------------------------
        */

        $withErrors =
            $failedRows > 0;

        $this->importRepository
            ->markCompleted(
                id:
                    $importId,

                universityId:
                    $universityId,

                withErrors:
                    $withErrors
            );

        $finalStatus =
            $withErrors
                ? 'COMPLETED_WITH_ERRORS'
                : 'COMPLETED';

        return [
            'import_id' =>
                $importId,

            'status' =>
                $finalStatus,

            'imported_rows' =>
                $importedRows,

            'failed_rows' =>
                $failedRows,

            'skipped_rows' =>
                $skippedRows,

            'created_users' =>
                $createdUsers,

            'created_students' =>
                $createdStudents,

            'created_enrollments' =>
                $createdEnrollments,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | UUID
    |--------------------------------------------------------------------------
    */

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
            bin2hex(
                $bytes
            );

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