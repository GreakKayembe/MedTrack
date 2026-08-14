<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\Controllers;

use MedTrack\Core\Http\Request;
use MedTrack\Core\Http\Response;
use MedTrack\Core\Http\View;
use MedTrack\Modules\Academic\Services\StudyLevelService;
use RuntimeException;
use Throwable;

final class StudyLevelController
{
    public function __construct(
        private readonly StudyLevelService $studyLevels,
        private readonly View $view
    ) {
    }

    /**
     * Affiche la liste des niveaux d'études.
     */
    public function index(
        Request $request
    ): string {
        return $this->view->render(
            'academic.study-levels.index',
            [
                'pageTitle' => 'Niveaux d’études',

                'studyLevels' =>
                    $this->studyLevels->all(),

                'statistics' =>
                    $this->studyLevels->statistics(),
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
            'academic.study-levels.create',
            [
                'pageTitle' =>
                    'Nouveau niveau d’études',

                'pageScripts' => [
                    '/assets/js/medtrack-study-level-form.js',
                ],
            ]
        );
    }

    /**
     * Enregistre un nouveau niveau d'études.
     */
    public function store(
        Request $request
    ): never {
        try {
            $studyLevelId =
                $this->studyLevels->create(
                    [
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

                        'ordinal' =>
                            $request->input(
                                'ordinal',
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
                        'STUDY_LEVEL_CREATION_FAILED',

                    'message' =>
                        'Impossible d’enregistrer '
                        . 'le niveau d’études pour le moment.',
                ],
                500
            );
        }

        Response::json(
            [
                'status' => 'success',

                'message' =>
                    'Le niveau d’études a été '
                    . 'enregistré avec succès.',

                'study_level_id' =>
                    $studyLevelId,

                'redirect' =>
                    '/study-levels/'
                    . $studyLevelId,
            ],
            201
        );
    }

    /**
     * Affiche un niveau d'études.
     */
    public function show(
        Request $request
    ): string {
        $id = $this->routeId(
            $request
        );

        $studyLevel =
            $this->studyLevels->findById(
                $id
            );

        if ($studyLevel === null) {
            http_response_code(404);

            return $this->view->render(
                'errors.404',
                [
                    'pageTitle' =>
                        'Niveau d’études introuvable',
                ]
            );
        }

        return $this->view->render(
            'academic.study-levels.show',
            [
                'pageTitle' =>
                    $studyLevel['name'],

                'studyLevel' =>
                    $studyLevel,
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

        $studyLevel =
            $this->studyLevels->findById(
                $id
            );

        if ($studyLevel === null) {
            http_response_code(404);

            return $this->view->render(
                'errors.404',
                [
                    'pageTitle' =>
                        'Niveau d’études introuvable',
                ]
            );
        }

        return $this->view->render(
            'academic.study-levels.edit',
            [
                'pageTitle' =>
                    'Modifier le niveau d’études',

                'studyLevel' =>
                    $studyLevel,

                'pageScripts' => [
                    '/assets/js/medtrack-study-level-form.js',
                ],
            ]
        );
    }

    /**
     * Met à jour un niveau d'études.
     */
    public function update(
        Request $request
    ): never {
        $id = $this->routeId(
            $request
        );

        try {
            $this->studyLevels->update(
                $id,
                [
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

                    'ordinal' =>
                        $request->input(
                            'ordinal',
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
                        'STUDY_LEVEL_UPDATE_FAILED',

                    'message' =>
                        'Impossible de modifier '
                        . 'le niveau d’études pour le moment.',
                ],
                500
            );
        }

        Response::json(
            [
                'status' => 'success',

                'message' =>
                    'Le niveau d’études a été '
                    . 'mis à jour avec succès.',

                'redirect' =>
                    '/study-levels/' . $id,
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
                'Identifiant du niveau d’études invalide.'
            );
        }

        return $id;
    }
}