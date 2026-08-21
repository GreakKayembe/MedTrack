<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\Controllers;

use MedTrack\Core\Context\AccessContext;
use MedTrack\Core\Context\AccessContextResolver;
use MedTrack\Core\Http\Request;
use MedTrack\Core\Http\Response;
use MedTrack\Core\Http\View;
use MedTrack\Modules\Academic\Services\AcademicProgramService;
use MedTrack\Modules\Academic\Services\FacultyService;
use MedTrack\Modules\Academic\Services\UniversityService;
use RuntimeException;
use Throwable;

final class AcademicProgramController
{
    private const UNIVERSITY_TYPE =
        'UNIVERSITY';

    public function __construct(
        private readonly AcademicProgramService $programs,
        private readonly UniversityService $universities,
        private readonly FacultyService $faculties,
        private readonly AccessContextResolver $accessContextResolver,
        private readonly View $view
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    /**
     * Affiche les programmes académiques
     * visibles dans le contexte actif.
     */
    public function index(
        Request $request
    ): string {
        $context =
            $this->accessContext();

        return $this->view->render(
            'academic.programs.index',
            [
                'pageTitle' =>
                    'Programmes académiques',

                /*
                 * AcademicProgramService applique
                 * déjà le cloisonnement PLATFORM /
                 * UNIVERSITY.
                 */
                'programs' =>
                    $this->programs
                        ->all(),

                'statistics' =>
                    $this->programs
                        ->statistics(),

                'isPlatform' =>
                    $context->isPlatform(),

                'isUniversityContext' =>
                    $this->isUniversityContext(
                        $context
                    ),

                'activeUniversityId' =>
                    $this->activeUniversityId(
                        $context
                    ),
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    /**
     * Affiche le formulaire de création.
     */
    public function create(
        Request $request
    ): string {
        $context =
            $this->accessContext();

        $isPlatform =
            $context->isPlatform();

        $isUniversityContext =
            $this->isUniversityContext(
                $context
            );

        if (
            !$isPlatform
            && !$isUniversityContext
        ) {
            throw new RuntimeException(
                'Le contexte actif ne permet pas '
                . 'de créer un programme académique.'
            );
        }

        return $this->view->render(
            'academic.programs.create',
            [
                'pageTitle' =>
                    'Nouveau programme académique',

                /*
                 * PLATFORM :
                 * le formulaire peut sélectionner
                 * n'importe quelle université.
                 *
                 * UNIVERSITY :
                 * aucune liste globale n'est exposée.
                 */
                'universities' =>
                    $isPlatform
                        ? $this->universities
                            ->all()
                        : [],

                /*
                 * FacultyService est déjà
                 * AccessContext-aware.
                 *
                 * PLATFORM :
                 * toutes les facultés.
                 *
                 * UNIVERSITY :
                 * uniquement celles de
                 * l'université active.
                 */
                'faculties' =>
                    $this->faculties
                        ->all(),

                'isPlatform' =>
                    $isPlatform,

                'isUniversityContext' =>
                    $isUniversityContext,

                'activeUniversityId' =>
                    $this->activeUniversityId(
                        $context
                    ),

                'pageScripts' => [
                    '/assets/js/medtrack-academic-program-form.js',
                ],
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Store
    |--------------------------------------------------------------------------
    */

    /**
     * Enregistre un programme académique.
     *
     * IMPORTANT :
     * AcademicProgramService écrase university_id
     * avec l'organisation active en contexte
     * UNIVERSITY.
     */
    public function store(
        Request $request
    ): never {
        try {
            $programId =
                $this->programs
                    ->create(
                        $this->formData(
                            $request
                        )
                    );
        } catch (RuntimeException $exception) {
            Response::json(
                [
                    'status' =>
                        'error',

                    'code' =>
                        'VALIDATION_ERROR',

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
                        'ACADEMIC_PROGRAM_CREATION_FAILED',

                    'message' =>
                        'Impossible d’enregistrer '
                        . 'le programme académique '
                        . 'pour le moment.',
                ],
                500
            );
        }

        Response::json(
            [
                'status' =>
                    'success',

                'message' =>
                    'Le programme académique a été '
                    . 'enregistré avec succès.',

                'program_id' =>
                    $programId,

                'redirect' =>
                    '/academic-programs/'
                    . $programId,
            ],
            201
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Show
    |--------------------------------------------------------------------------
    */

    /**
     * Affiche un programme académique.
     *
     * AcademicProgramService retourne null
     * lorsqu'une université tente d'accéder
     * au programme d'une autre université.
     */
    public function show(
        Request $request
    ): string {
        $context =
            $this->accessContext();

        $id =
            $this->routeId(
                $request
            );

        $program =
            $this->programs
                ->findById(
                    $id
                );

        if ($program === null) {
            http_response_code(
                404
            );

            return $this->view->render(
                'errors.404',
                [
                    'pageTitle' =>
                        'Programme académique introuvable',
                ]
            );
        }

        return $this->view->render(
            'academic.programs.show',
            [
                'pageTitle' =>
                    $program['name']
                    ?? 'Programme académique',

                'program' =>
                    $program,

                'isPlatform' =>
                    $context->isPlatform(),

                'isUniversityContext' =>
                    $this->isUniversityContext(
                        $context
                    ),

                'activeUniversityId' =>
                    $this->activeUniversityId(
                        $context
                    ),
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    /**
     * Affiche le formulaire de modification.
     */
    public function edit(
        Request $request
    ): string {
        $context =
            $this->accessContext();

        $isPlatform =
            $context->isPlatform();

        $isUniversityContext =
            $this->isUniversityContext(
                $context
            );

        if (
            !$isPlatform
            && !$isUniversityContext
        ) {
            throw new RuntimeException(
                'Le contexte actif ne permet pas '
                . 'de modifier un programme académique.'
            );
        }

        $id =
            $this->routeId(
                $request
            );

        /*
         * La recherche est déjà limitée
         * par AcademicProgramService.
         */
        $program =
            $this->programs
                ->findById(
                    $id
                );

        if ($program === null) {
            http_response_code(
                404
            );

            return $this->view->render(
                'errors.404',
                [
                    'pageTitle' =>
                        'Programme académique introuvable',
                ]
            );
        }

        return $this->view->render(
            'academic.programs.edit',
            [
                'pageTitle' =>
                    'Modifier le programme académique',

                'program' =>
                    $program,

                /*
                 * Le sélecteur d'université
                 * n'est nécessaire que pour PLATFORM.
                 */
                'universities' =>
                    $isPlatform
                        ? $this->universities
                            ->all()
                        : [],

                /*
                 * FacultyService renvoie automatiquement
                 * les facultés correspondant au contexte.
                 */
                'faculties' =>
                    $this->faculties
                        ->all(),

                'isPlatform' =>
                    $isPlatform,

                'isUniversityContext' =>
                    $isUniversityContext,

                'activeUniversityId' =>
                    $this->activeUniversityId(
                        $context
                    ),

                'pageScripts' => [
                    '/assets/js/medtrack-academic-program-form.js',
                ],
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    /**
     * Met à jour un programme académique.
     *
     * En contexte UNIVERSITY, university_id
     * provenant du formulaire est ignoré
     * par AcademicProgramService.
     */
    public function update(
        Request $request
    ): never {
        $id =
            $this->routeId(
                $request
            );

        try {
            $this->programs
                ->update(
                    $id,
                    $this->formData(
                        $request
                    )
                );
        } catch (RuntimeException $exception) {
            Response::json(
                [
                    'status' =>
                        'error',

                    'code' =>
                        'VALIDATION_ERROR',

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
                        'ACADEMIC_PROGRAM_UPDATE_FAILED',

                    'message' =>
                        'Impossible de modifier '
                        . 'le programme académique '
                        . 'pour le moment.',
                ],
                500
            );
        }

        Response::json(
            [
                'status' =>
                    'success',

                'message' =>
                    'Le programme académique a été '
                    . 'mis à jour avec succès.',

                'redirect' =>
                    '/academic-programs/'
                    . $id,
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Form data
    |--------------------------------------------------------------------------
    */

    /**
     * Construit les données provenant
     * du formulaire.
     *
     * university_id peut être présent dans
     * la requête PLATFORM.
     *
     * En contexte UNIVERSITY, cette valeur
     * n'est jamais considérée comme fiable :
     * AcademicProgramService la remplace.
     */
    private function formData(
        Request $request
    ): array {
        return [
            'university_id' =>
                $request->input(
                    'university_id',
                    0
                ),

            'faculty_id' =>
                $request->input(
                    'faculty_id',
                    ''
                ),

            'code' =>
                $request->input(
                    'code',
                    ''
                ),

            'name' =>
                $request->input(
                    'name',
                    ''
                ),

            'discipline_code' =>
                $request->input(
                    'discipline_code',
                    ''
                ),

            'duration_years' =>
                $request->input(
                    'duration_years',
                    ''
                ),

            'status' =>
                $request->input(
                    'status',
                    'ACTIVE'
                ),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Context helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Résout le contexte actif.
     */
    private function accessContext(): AccessContext
    {
        return $this->accessContextResolver
            ->resolve();
    }

    /**
     * Vérifie que le contexte actif
     * correspond à une université.
     */
    private function isUniversityContext(
        AccessContext $context
    ): bool {
        return
            $context->isOrganization()
            && strtoupper(
                trim(
                    $context->organizationType()
                )
            ) === self::UNIVERSITY_TYPE;
    }

    /**
     * Retourne l'université active
     * uniquement en contexte UNIVERSITY.
     */
    private function activeUniversityId(
        AccessContext $context
    ): ?int {
        if (
            !$this->isUniversityContext(
                $context
            )
        ) {
            return null;
        }

        return $context
            ->organizationId();
    }

    /*
    |--------------------------------------------------------------------------
    | Route helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Extrait l'identifiant numérique
     * fourni par le Router.
     */
    private function routeId(
        Request $request
    ): int {
        $id =
            (int) $request->attribute(
                'id',
                0
            );

        if ($id <= 0) {
            throw new RuntimeException(
                'Identifiant de programme '
                . 'académique invalide.'
            );
        }

        return $id;
    }
}