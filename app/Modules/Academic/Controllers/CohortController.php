<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\Controllers;

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
    public function __construct(
        private readonly CohortService $cohorts,
        private readonly AcademicProgramService $academicPrograms,
        private readonly AcademicYearService $academicYears,
        private readonly View $view
    ) {
    }

    /**
     * Affiche la liste des cohortes.
     */
    public function index(
        Request $request
    ): string {
        return $this->view->render(
            'academic.cohorts.index',
            [
                'pageTitle' => 'Cohortes',

                'cohorts' =>
                    $this->cohorts->all(),
            ]
        );
    }

    /**
     * Affiche le formulaire de création.
     */
    public function create(
        Request $request
    ): string {
        return $this->view->render(
            'academic.cohorts.create',
            [
                'pageTitle' =>
                    'Nouvelle cohorte',

                'academicPrograms' =>
                    $this->academicPrograms->all(),

                'academicYears' =>
                    $this->academicYears->all(),

                'pageScripts' => [
                    '/assets/js/medtrack-cohort-form.js',
                ],
            ]
        );
    }

    /**
     * Enregistre une nouvelle cohorte.
     */
    public function store(
        Request $request
    ): never {
        try {
            $cohortId =
                $this->cohorts->create(
                    [
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
                    ]
                );
        } catch (RuntimeException $exception) {
            Response::json(
                [
                    'status' => 'error',

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
                    'status' => 'error',

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
                'status' => 'success',

                'message' =>
                    'La cohorte a été enregistrée '
                    . 'avec succès.',

                'cohort_id' =>
                    $cohortId,

                'redirect' =>
                    '/cohorts/' . $cohortId,
            ],
            201
        );
    }

    /**
     * Affiche une cohorte.
     */
    public function show(
        Request $request
    ): string {
        $id = $this->routeId(
            $request
        );

        $cohort =
            $this->cohorts->findById(
                $id
            );

        if ($cohort === null) {
            http_response_code(404);

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
                    $cohort['name'],

                'cohort' =>
                    $cohort,
            ]
        );
    }

    /**
     * Affiche le formulaire de modification.
     */
    public function edit(
        Request $request
    ): string {
        $id = $this->routeId(
            $request
        );

        $cohort =
            $this->cohorts->findById(
                $id
            );

        if ($cohort === null) {
            http_response_code(404);

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

                'academicPrograms' =>
                    $this->academicPrograms->all(),

                'academicYears' =>
                    $this->academicYears->all(),

                'pageScripts' => [
                    '/assets/js/medtrack-cohort-form.js',
                ],
            ]
        );
    }

    /**
     * Met à jour une cohorte.
     */
    public function update(
        Request $request
    ): never {
        $id = $this->routeId(
            $request
        );

        try {
            $this->cohorts->update(
                $id,
                [
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
                ]
            );
        } catch (RuntimeException $exception) {
            Response::json(
                [
                    'status' => 'error',

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
                    'status' => 'error',

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
                'status' => 'success',

                'message' =>
                    'La cohorte a été mise à jour '
                    . 'avec succès.',

                'redirect' =>
                    '/cohorts/' . $id,
            ]
        );
    }

    /**
     * Extrait l'identifiant numérique fourni
     * par le Router.
     */
    private function routeId(
        Request $request
    ): int {
        $id = (int) $request->attribute(
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