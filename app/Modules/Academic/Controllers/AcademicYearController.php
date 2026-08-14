<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\Controllers;

use MedTrack\Core\Http\Request;
use MedTrack\Core\Http\Response;
use MedTrack\Core\Http\View;
use MedTrack\Modules\Academic\Services\AcademicYearService;
use RuntimeException;
use Throwable;

final class AcademicYearController
{
    public function __construct(
        private readonly AcademicYearService $academicYears,
        private readonly View $view
    ) {
    }

    /**
     * Affiche la liste des années académiques.
     */
    public function index(
        Request $request
    ): string {
        return $this->view->render(
            'academic.years.index',
            [
                'pageTitle' => 'Années académiques',

                'academicYears' =>
                    $this->academicYears->all(),

                'statistics' =>
                    $this->academicYears->statistics(),
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
            'academic.years.create',
            [
                'pageTitle' =>
                    'Nouvelle année académique',

                'pageScripts' => [
                    '/assets/js/medtrack-academic-year-form.js',
                ],
            ]
        );
    }

    /**
     * Enregistre une nouvelle année académique.
     */
    public function store(
        Request $request
    ): never {
        try {
            $academicYearId =
                $this->academicYears->create(
                    [
                        'label' =>
                            $request->input(
                                'label',
                                ''
                            ),

                        'starts_on' =>
                            $request->input(
                                'starts_on',
                                ''
                            ),

                        'ends_on' =>
                            $request->input(
                                'ends_on',
                                ''
                            ),

                        'status' =>
                            $request->input(
                                'status',
                                'PLANNED'
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
            /*
             * Les détails SQL ou internes ne doivent
             * jamais être exposés dans la réponse.
             */
            Response::json(
                [
                    'status' => 'error',

                    'code' =>
                        'ACADEMIC_YEAR_CREATION_FAILED',

                    'message' =>
                        'Impossible d’enregistrer '
                        . 'l’année académique pour le moment.',
                ],
                500
            );
        }

        Response::json(
            [
                'status' => 'success',

                'message' =>
                    'L’année académique a été '
                    . 'enregistrée avec succès.',

                'academic_year_id' =>
                    $academicYearId,

                'redirect' =>
                    '/academic-years/'
                    . $academicYearId,
            ],
            201
        );
    }

    /**
     * Affiche les informations détaillées
     * d'une année académique.
     */
    public function show(
        Request $request
    ): string {
        $id = $this->routeId(
            $request
        );

        $academicYear =
            $this->academicYears->findById(
                $id
            );

        if ($academicYear === null) {
            http_response_code(404);

            return $this->view->render(
                'errors.404',
                [
                    'pageTitle' =>
                        'Année académique introuvable',
                ]
            );
        }

        return $this->view->render(
            'academic.years.show',
            [
                'pageTitle' =>
                    $academicYear['label'],

                'academicYear' =>
                    $academicYear,
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

        $academicYear =
            $this->academicYears->findById(
                $id
            );

        if ($academicYear === null) {
            http_response_code(404);

            return $this->view->render(
                'errors.404',
                [
                    'pageTitle' =>
                        'Année académique introuvable',
                ]
            );
        }

        return $this->view->render(
            'academic.years.edit',
            [
                'pageTitle' =>
                    'Modifier l’année académique',

                'academicYear' =>
                    $academicYear,

                'pageScripts' => [
                    '/assets/js/medtrack-academic-year-form.js',
                ],
            ]
        );
    }

    /**
     * Met à jour une année académique.
     */
    public function update(
        Request $request
    ): never {
        $id = $this->routeId(
            $request
        );

        try {
            $this->academicYears->update(
                $id,
                [
                    'label' =>
                        $request->input(
                            'label',
                            ''
                        ),

                    'starts_on' =>
                        $request->input(
                            'starts_on',
                            ''
                        ),

                    'ends_on' =>
                        $request->input(
                            'ends_on',
                            ''
                        ),

                    'status' =>
                        $request->input(
                            'status',
                            'PLANNED'
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
                        'ACADEMIC_YEAR_UPDATE_FAILED',

                    'message' =>
                        'Impossible de modifier '
                        . 'l’année académique pour le moment.',
                ],
                500
            );
        }

        Response::json(
            [
                'status' => 'success',

                'message' =>
                    'L’année académique a été '
                    . 'mise à jour avec succès.',

                'redirect' =>
                    '/academic-years/' . $id,
            ]
        );
    }

    /**
     * Extrait l'identifiant numérique injecté
     * dans Request par le Router.
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
                'Identifiant d’année académique invalide.'
            );
        }

        return $id;
    }
}