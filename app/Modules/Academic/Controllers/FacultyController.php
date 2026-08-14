<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\Controllers;

use MedTrack\Core\Http\Request;
use MedTrack\Core\Http\Response;
use MedTrack\Core\Http\View;
use MedTrack\Modules\Academic\Services\FacultyService;
use MedTrack\Modules\Academic\Services\UniversityService;
use RuntimeException;
use Throwable;

final class FacultyController
{
    public function __construct(
        private readonly FacultyService $faculties,
        private readonly UniversityService $universities,
        private readonly View $view
    ) {
    }

    /**
     * Affiche la liste des facultés.
     */
    public function index(
        Request $request
    ): string {
        return $this->view->render(
            'academic.faculties.index',
            [
                'pageTitle' => 'Facultés',

                'faculties' =>
                    $this->faculties->all(),
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
            'academic.faculties.create',
            [
                'pageTitle' => 'Nouvelle faculté',

                'universities' =>
                    $this->universities->all(),

                'pageScripts' => [
                    '/assets/js/medtrack-faculty-form.js',
                ],
            ]
        );
    }

    /**
     * Enregistre une nouvelle faculté.
     */
    public function store(
        Request $request
    ): never {
        try {
            $facultyId = $this->faculties->create(
                [
                    'university_id' =>
                        $request->input(
                            'university_id',
                            0
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

                    'status' =>
                        $request->input(
                            'status',
                            'ACTIVE'
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
                        'FACULTY_CREATION_FAILED',

                    'message' =>
                        'Impossible d’enregistrer '
                        . 'la faculté pour le moment.',
                ],
                500
            );
        }

        Response::json(
            [
                'status' => 'success',

                'message' =>
                    'La faculté a été enregistrée '
                    . 'avec succès.',

                'faculty_id' =>
                    $facultyId,

                'redirect' =>
                    '/faculties/' . $facultyId,
            ],
            201
        );
    }

    /**
     * Affiche la fiche d'une faculté.
     */
    public function show(
        Request $request
    ): string {
        $id = $this->routeId(
            $request
        );

        $faculty =
            $this->faculties->findById(
                $id
            );

        if ($faculty === null) {
            http_response_code(404);

            return $this->view->render(
                'errors.404',
                [
                    'pageTitle' =>
                        'Faculté introuvable',
                ]
            );
        }

        return $this->view->render(
            'academic.faculties.show',
            [
                'pageTitle' =>
                    $faculty['name'],

                'faculty' =>
                    $faculty,
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

        $faculty =
            $this->faculties->findById(
                $id
            );

        if ($faculty === null) {
            http_response_code(404);

            return $this->view->render(
                'errors.404',
                [
                    'pageTitle' =>
                        'Faculté introuvable',
                ]
            );
        }

        return $this->view->render(
            'academic.faculties.edit',
            [
                'pageTitle' =>
                    'Modifier la faculté',

                'faculty' =>
                    $faculty,

                'universities' =>
                    $this->universities->all(),

                'pageScripts' => [
                    '/assets/js/medtrack-faculty-form.js',
                ],
            ]
        );
    }

    /**
     * Met à jour une faculté.
     */
    public function update(
        Request $request
    ): never {
        $id = $this->routeId(
            $request
        );

        try {
            $this->faculties->update(
                $id,
                [
                    'university_id' =>
                        $request->input(
                            'university_id',
                            0
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

                    'status' =>
                        $request->input(
                            'status',
                            'ACTIVE'
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
                        'FACULTY_UPDATE_FAILED',

                    'message' =>
                        'Impossible de modifier '
                        . 'la faculté pour le moment.',
                ],
                500
            );
        }

        Response::json(
            [
                'status' => 'success',

                'message' =>
                    'La faculté a été mise à jour '
                    . 'avec succès.',

                'redirect' =>
                    '/faculties/' . $id,
            ]
        );
    }

    /**
     * Extrait l'identifiant fourni par le Router.
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
                'Identifiant de faculté invalide.'
            );
        }

        return $id;
    }
}