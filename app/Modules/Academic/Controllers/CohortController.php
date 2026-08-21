<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\Controllers;

use MedTrack\Core\Context\AccessContext;
use MedTrack\Core\Context\AccessContextResolver;
use MedTrack\Core\Http\Request;
use MedTrack\Core\Http\Response;
use MedTrack\Core\Http\View;
use MedTrack\Modules\Academic\Services\AcademicProgramService;
use MedTrack\Modules\Academic\Services\AcademicYearService;
use MedTrack\Modules\Academic\Services\CohortService;
use RuntimeException;
use Throwable;

final class CohortController
{
    private const UNIVERSITY_TYPE =
        'UNIVERSITY';

    public function __construct(
        private readonly CohortService $cohorts,
        private readonly AcademicProgramService $academicPrograms,
        private readonly AcademicYearService $academicYears,
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
     * Affiche les cohortes visibles
     * dans le contexte actif.
     */
    public function index(
        Request $request
    ): string {
        $context =
            $this->accessContext();

        return $this->view->render(
            'academic.cohorts.index',
            [
                'pageTitle' =>
                    'Cohortes',

                /*
                 * CohortService applique déjà :
                 *
                 * PLATFORM
                 * → toutes les cohortes
                 *
                 * UNIVERSITY
                 * → uniquement les cohortes
                 *   de ses propres programmes
                 */
                'cohorts' =>
                    $this->cohorts
                        ->all(),

                'statistics' =>
                    $this->cohorts
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
     * Affiche le formulaire
     * de création d'une cohorte.
     */
    public function create(
        Request $request
    ): string {
        $context =
            $this->accessContext();

        $this->ensureAllowedContext(
            $context
        );

        return $this->view->render(
            'academic.cohorts.create',
            [
                'pageTitle' =>
                    'Nouvelle cohorte',

                /*
                 * AcademicProgramService est
                 * AccessContext-aware.
                 *
                 * UNIVERSITY :
                 * uniquement les programmes
                 * de l'université active.
                 */
                'academicPrograms' =>
                    $this->academicPrograms
                        ->all(),

                /*
                 * Academic Years est un référentiel
                 * global MedTrack en lecture pour
                 * l'espace UNIVERSITY.
                 */
                'academicYears' =>
                    $this->academicYears
                        ->all(),

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

                'pageScripts' => [
                    '/assets/js/medtrack-cohort-form.js',
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
     * Enregistre une nouvelle cohorte.
     */
    public function store(
        Request $request
    ): never {
        try {
            $cohortId =
                $this->cohorts
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
                        'COHORT_CREATION_FAILED',

                    'message' =>
                        'Impossible d’enregistrer '
                        . 'la cohorte pour le moment.',
                ],
                500
            );
        }

        Response::json(
            [
                'status' =>
                    'success',

                'message' =>
                    'La cohorte a été enregistrée '
                    . 'avec succès.',

                'cohort_id' =>
                    $cohortId,

                'redirect' =>
                    '/cohorts/'
                    . $cohortId,
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
     * Affiche une cohorte.
     *
     * En contexte UNIVERSITY,
     * CohortService retourne null si la cohorte
     * appartient à une autre université.
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

        $cohort =
            $this->cohorts
                ->findById(
                    $id
                );

        if ($cohort === null) {
            http_response_code(
                404
            );

            return $this->view->render(
                'errors.404',
                [
                    'pageTitle' =>
                        'Cohorte introuvable',
                ]
            );
        }

        return $this->view->render(
            'academic.cohorts.show',
            [
                'pageTitle' =>
                    $cohort['name']
                    ?? 'Cohorte',

                'cohort' =>
                    $cohort,

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
     * Affiche le formulaire
     * de modification.
     */
    public function edit(
        Request $request
    ): string {
        $context =
            $this->accessContext();

        $this->ensureAllowedContext(
            $context
        );

        $id =
            $this->routeId(
                $request
            );

        /*
         * Recherche scoped.
         *
         * Une université ne peut donc pas
         * charger une cohorte appartenant
         * à une autre université.
         */
        $cohort =
            $this->cohorts
                ->findById(
                    $id
                );

        if ($cohort === null) {
            http_response_code(
                404
            );

            return $this->view->render(
                'errors.404',
                [
                    'pageTitle' =>
                        'Cohorte introuvable',
                ]
            );
        }

        return $this->view->render(
            'academic.cohorts.edit',
            [
                'pageTitle' =>
                    'Modifier la cohorte',

                'cohort' =>
                    $cohort,

                /*
                 * UNIVERSITY :
                 * uniquement les programmes
                 * appartenant à l'université active.
                 */
                'academicPrograms' =>
                    $this->academicPrograms
                        ->all(),

                /*
                 * Référentiel global MedTrack.
                 */
                'academicYears' =>
                    $this->academicYears
                        ->all(),

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

                'pageScripts' => [
                    '/assets/js/medtrack-cohort-form.js',
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
     * Met à jour une cohorte.
     */
    public function update(
        Request $request
    ): never {
        $id =
            $this->routeId(
                $request
            );

        try {
            $this->cohorts
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
                        'COHORT_UPDATE_FAILED',

                    'message' =>
                        'Impossible de modifier '
                        . 'la cohorte pour le moment.',
                ],
                500
            );
        }

        Response::json(
            [
                'status' =>
                    'success',

                'message' =>
                    'La cohorte a été mise à jour '
                    . 'avec succès.',

                'redirect' =>
                    '/cohorts/'
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
     * Les identifiants reçus du navigateur
     * ne sont jamais considérés comme suffisants
     * pour l'autorisation.
     *
     * CohortService vérifie notamment que
     * academic_program_id appartient
     * à l'université active.
     */
    private function formData(
        Request $request
    ): array {
        return [
            'academic_program_id' =>
                $request->input(
                    'academic_program_id',
                    ''
                ),

            'academic_year_id' =>
                $request->input(
                    'academic_year_id',
                    ''
                ),

            'name' =>
                $request->input(
                    'name',
                    ''
                ),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Context
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
     * Vérifie que le contexte correspond
     * à une université.
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
     * Vérifie que le contexte peut
     * administrer les cohortes.
     */
    private function ensureAllowedContext(
        AccessContext $context
    ): void {
        if ($context->isPlatform()) {
            return;
        }

        if (
            $this->isUniversityContext(
                $context
            )
        ) {
            return;
        }

        throw new RuntimeException(
            'Le contexte actif ne permet pas '
            . 'de gérer les cohortes.'
        );
    }

    /**
     * Retourne l'université active
     * lorsque le contexte est UNIVERSITY.
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
    | Route
    |--------------------------------------------------------------------------
    */

    /**
     * Extrait l'identifiant numérique fourni
     * par le Router.
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
                'Identifiant de cohorte invalide.'
            );
        }

        return $id;
    }
}