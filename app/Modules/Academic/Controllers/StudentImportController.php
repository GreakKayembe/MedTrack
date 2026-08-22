<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\Controllers;

use MedTrack\Core\Context\AccessContext;
use MedTrack\Core\Context\AccessContextResolver;
use MedTrack\Core\Http\Request;
use MedTrack\Core\Http\Response;
use MedTrack\Core\Http\View;
use MedTrack\Modules\Academic\Services\StudentImport\StudentImportService;
use RuntimeException;
use Throwable;

final class StudentImportController
{
    public function __construct(
        private readonly StudentImportService $imports,
        private readonly AccessContextResolver $accessContextResolver,
        private readonly View $view
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    /**
     * Affiche le formulaire d'import Excel.
     *
     * UNIVERSITY uniquement.
     */
    public function create(
        Request $request
    ): string {
        $context =
            $this->universityContext();

        return $this->view->render(
            'academic.student-imports.create',
            [
                'pageTitle' =>
                    'Importer les étudiants',

                'universityId' =>
                    $context->organizationId(),

                'pageScripts' => [
                    '/assets/js/medtrack-student-import.js',
                ],
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Store / Preview preparation
    |--------------------------------------------------------------------------
    */

    /**
     * Reçoit le fichier Excel.
     *
     * Le fichier est :
     *
     * - contrôlé ;
     * - stocké ;
     * - analysé ;
     * - validé ;
     * - persisté dans student_imports
     *   et student_import_rows.
     *
     * Aucun étudiant définitif n'est encore créé ici.
     */
    public function store(
        Request $request
    ): never {
        try {
            $context =
                $this->universityContext();

            /*
            |--------------------------------------------------------------------------
            | Uploaded file
            |--------------------------------------------------------------------------
            */

            $file =
                $request->file(
                    'student_file'
                );

            if ($file === null) {
                throw new RuntimeException(
                    'Aucun fichier Excel n’a été envoyé.'
                );
            }

            $uploadError =
                (int) (
                    $file['error']
                    ?? UPLOAD_ERR_NO_FILE
                );

            if (
                $uploadError
                !== UPLOAD_ERR_OK
            ) {
                throw new RuntimeException(
                    $this->uploadErrorMessage(
                        $uploadError
                    )
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Temporary upload
            |--------------------------------------------------------------------------
            */

            $tmpPath =
                (string) (
                    $file['tmp_name']
                    ?? ''
                );

            if (
                $tmpPath === ''
                || !is_uploaded_file(
                    $tmpPath
                )
            ) {
                throw new RuntimeException(
                    'Le fichier temporaire reçu est invalide.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Original filename
            |--------------------------------------------------------------------------
            */

            $originalName =
                trim(
                    (string) (
                        $file['name']
                        ?? ''
                    )
                );

            if ($originalName === '') {
                throw new RuntimeException(
                    'Le nom du fichier est invalide.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Extension
            |--------------------------------------------------------------------------
            */

            $extension =
                strtolower(
                    pathinfo(
                        $originalName,
                        PATHINFO_EXTENSION
                    )
                );

            if (
                !in_array(
                    $extension,
                    [
                        'xlsx',
                        'xls',
                    ],
                    true
                )
            ) {
                throw new RuntimeException(
                    'Seuls les fichiers Excel '
                    . '.xlsx et .xls sont acceptés.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | File size
            |--------------------------------------------------------------------------
            */

            $fileSize =
                (int) (
                    $file['size']
                    ?? 0
                );

            if ($fileSize <= 0) {
                throw new RuntimeException(
                    'Le fichier envoyé est vide.'
                );
            }

            $maxFileSize =
                10 * 1024 * 1024;

            if (
                $fileSize
                > $maxFileSize
            ) {
                throw new RuntimeException(
                    'Le fichier dépasse la taille '
                    . 'maximale autorisée de 10 Mo.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Storage directory
            |--------------------------------------------------------------------------
            */

            $storageDirectory =
                dirname(
                    __DIR__,
                    4
                )
                . '/storage/student-imports';

            if (
                !is_dir(
                    $storageDirectory
                )
                && !mkdir(
                    $storageDirectory,
                    0775,
                    true
                )
                && !is_dir(
                    $storageDirectory
                )
            ) {
                throw new RuntimeException(
                    'Impossible de créer le dossier '
                    . 'de stockage des imports.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Stored filename
            |--------------------------------------------------------------------------
            */

            $storedFilename =
                bin2hex(
                    random_bytes(16)
                )
                . '.'
                . $extension;

            $storedPath =
                $storageDirectory
                . '/'
                . $storedFilename;

            /*
            |--------------------------------------------------------------------------
            | Move uploaded file
            |--------------------------------------------------------------------------
            */

            if (
                !move_uploaded_file(
                    $tmpPath,
                    $storedPath
                )
            ) {
                throw new RuntimeException(
                    'Impossible de stocker '
                    . 'le fichier Excel envoyé.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Actor
            |--------------------------------------------------------------------------
            */

            $userId =
                $context->userId();

            if ($userId <= 0) {
                throw new RuntimeException(
                    'Utilisateur actif introuvable.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Prepare import
            |--------------------------------------------------------------------------
            */

            try {
                $result =
                    $this->imports->prepare(
                        $storedPath,
                        $context->organizationId(),
                        $userId
                    );
            } catch (Throwable $exception) {
                /*
                 * Si la préparation échoue,
                 * aucun fichier orphelin ne doit rester.
                 */
                if (
                    is_file(
                        $storedPath
                    )
                ) {
                    @unlink(
                        $storedPath
                    );
                }

                throw $exception;
            }

            /*
            |--------------------------------------------------------------------------
            | Success
            |--------------------------------------------------------------------------
            */

            Response::json(
                [
                    'status' =>
                        'success',

                    'message' =>
                        'Le fichier a été analysé '
                        . 'avec succès.',

                    'import' =>
                        $result,

                    'redirect' =>
                        '/student-imports/'
                        . $result['import_id'],
                ],
                201
            );

        } catch (RuntimeException $exception) {
            Response::json(
                [
                    'status' =>
                        'error',

                    'code' =>
                        'STUDENT_IMPORT_VALIDATION_ERROR',

                    'message' =>
                        $exception->getMessage(),
                ],
                422
            );

        } catch (Throwable $exception) {
            Response::json(
                [
                    'status' =>
                        'error',

                    'code' =>
                        'STUDENT_IMPORT_FAILED',

                    'message' =>
                        'Impossible d’analyser '
                        . 'le fichier des étudiants '
                        . 'pour le moment.',
                ],
                500
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Preview
    |--------------------------------------------------------------------------
    */

    /**
     * Affiche la prévisualisation d'un import.
     */
    public function show(
        Request $request
    ): string {
        $context =
            $this->universityContext();

        $importId =
            $this->routeId(
                $request
            );

        $preview =
            $this->imports->preview(
                $importId,
                $context->organizationId()
            );

        if ($preview === null) {
            http_response_code(
                404
            );

            return $this->view->render(
                'errors.404',
                [
                    'pageTitle' =>
                        'Import introuvable',
                ]
            );
        }

        return $this->view->render(
            'academic.student-imports.preview',
            [
                'pageTitle' =>
                    'Prévisualisation de l’import',

                'import' =>
                    $preview['import'],

                'rows' =>
                    $preview['rows'],

                'pageScripts' => [
                    '/assets/js/medtrack-student-import.js',
                ],
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Confirm
    |--------------------------------------------------------------------------
    */

    /**
     * Confirme un import prévalidé.
     *
     * IMPORTANT :
     *
     * Le backend métier de création définitive
     * des User / Student / AcademicEnrollment
     * sera branché dans StudentImportService
     * lors du LOT suivant.
     *
     * Cette méthode permet déjà :
     *
     * - de sécuriser la route ;
     * - de vérifier l'université active ;
     * - de vérifier l'existence du batch ;
     * - de vérifier son statut READY ;
     * - d'empêcher une confirmation contenant
     *   encore des erreurs.
     */
    public function confirm(
        Request $request
    ): never {
        try {
            $context =
                $this->universityContext();

            $importId =
                $this->routeId(
                    $request
                );

            /*
            |--------------------------------------------------------------------------
            | Retrieve import
            |--------------------------------------------------------------------------
            */

            $preview =
                $this->imports->preview(
                    $importId,
                    $context->organizationId()
                );

            if ($preview === null) {
                Response::json(
                    [
                        'status' =>
                            'error',

                        'code' =>
                            'STUDENT_IMPORT_NOT_FOUND',

                        'message' =>
                            'Import étudiant introuvable.',
                    ],
                    404
                );
            }

            $import =
                $preview['import'];

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            $status =
                strtoupper(
                    (string) (
                        $import['status']
                        ?? ''
                    )
                );

            if ($status !== 'READY') {
                throw new RuntimeException(
                    'Cet import n’est pas prêt '
                    . 'à être confirmé.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Errors
            |--------------------------------------------------------------------------
            */

            $errorRows =
                (int) (
                    $import['error_rows']
                    ?? 0
                );

            if ($errorRows > 0) {
                throw new RuntimeException(
                    'Cet import contient encore '
                    . 'des lignes en erreur.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Importable rows
            |--------------------------------------------------------------------------
            */

            $validRows =
                (int) (
                    $import['valid_rows']
                    ?? 0
                );

            $warningRows =
                (int) (
                    $import['warning_rows']
                    ?? 0
                );

            if (
                $validRows <= 0
                && $warningRows <= 0
            ) {
                throw new RuntimeException(
                    'Aucune ligne importable '
                    . 'n’a été trouvée.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Temporary response
            |--------------------------------------------------------------------------
            |
            | La route fonctionne maintenant réellement.
            |
            | La prochaine étape sera de remplacer cette
            | réponse par :
            |
            | $result = $this->imports->confirm(...)
            |
            | après création du StudentOnboardingService.
            |--------------------------------------------------------------------------
            */

            Response::json(
                [
                    'status' =>
                        'error',

                    'code' =>
                        'STUDENT_IMPORT_CONFIRMATION_NOT_IMPLEMENTED',

                    'message' =>
                        'Le fichier est prêt à être importé, '
                        . 'mais la création définitive '
                        . 'des comptes étudiants et des '
                        . 'inscriptions académiques '
                        . 'n’est pas encore implémentée.',
                ],
                409
            );

        } catch (RuntimeException $exception) {
            Response::json(
                [
                    'status' =>
                        'error',

                    'code' =>
                        'STUDENT_IMPORT_CONFIRMATION_ERROR',

                    'message' =>
                        $exception->getMessage(),
                ],
                422
            );

        } catch (Throwable $exception) {
            Response::json(
                [
                    'status' =>
                        'error',

                    'code' =>
                        'STUDENT_IMPORT_CONFIRMATION_FAILED',

                    'message' =>
                        'Impossible de confirmer '
                        . 'l’import pour le moment.',
                ],
                500
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Context
    |--------------------------------------------------------------------------
    */

    /**
     * Retourne exclusivement un contexte UNIVERSITY.
     */
    private function universityContext(): AccessContext
    {
        $context =
            $this->accessContextResolver
                ->resolve();

        if (
            !$context->isOrganization()
            || $context->organizationType()
                !== 'UNIVERSITY'
        ) {
            throw new RuntimeException(
                'Cette fonctionnalité est réservée '
                . 'au contexte universitaire.'
            );
        }

        return $context;
    }

    /*
    |--------------------------------------------------------------------------
    | Route ID
    |--------------------------------------------------------------------------
    */

    private function routeId(
        Request $request
    ): int {
        $value =
            $request->attribute(
                'id'
            );

        if (
            !is_string($value)
            && !is_int($value)
        ) {
            throw new RuntimeException(
                'Identifiant d’import invalide.'
            );
        }

        $id =
            filter_var(
                $value,
                FILTER_VALIDATE_INT,
                [
                    'options' => [
                        'min_range' =>
                            1,
                    ],
                ]
            );

        if ($id === false) {
            throw new RuntimeException(
                'Identifiant d’import invalide.'
            );
        }

        return (int) $id;
    }

    /*
    |--------------------------------------------------------------------------
    | Upload errors
    |--------------------------------------------------------------------------
    */

    private function uploadErrorMessage(
        int $error
    ): string {
        return match ($error) {
            UPLOAD_ERR_INI_SIZE,
            UPLOAD_ERR_FORM_SIZE =>
                'Le fichier dépasse la taille '
                . 'maximale autorisée.',

            UPLOAD_ERR_PARTIAL =>
                'Le fichier n’a été envoyé '
                . 'que partiellement.',

            UPLOAD_ERR_NO_FILE =>
                'Aucun fichier n’a été sélectionné.',

            UPLOAD_ERR_NO_TMP_DIR =>
                'Le dossier temporaire PHP '
                . 'est indisponible.',

            UPLOAD_ERR_CANT_WRITE =>
                'Le serveur n’a pas pu enregistrer '
                . 'le fichier temporaire.',

            UPLOAD_ERR_EXTENSION =>
                'Une extension PHP a interrompu '
                . 'l’envoi du fichier.',

            default =>
                'Erreur inconnue lors '
                . 'de l’envoi du fichier.',
        };
    }
}