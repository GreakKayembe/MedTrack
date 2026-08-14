<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\Controllers;

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
    public function __construct(
        private readonly AcademicProgramService $programs,
        private readonly UniversityService $universities,
        private readonly FacultyService $faculties,
        private readonly View $view
    ) {
    }

    /**
     * Affiche la liste des programmes académiques.
     */
    public function index(
        Request $request
    ): string {
        return $this->view->render(
            'academic.programs.index',
            [
                'pageTitle' => 'Programmes académiques',

                'programs' =>
                    $this->programs->all(),
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
            'academic.programs.create',
            [
                'pageTitle' =>
                    'Nouveau programme académique',

                'universities' =>
                    $this->universities->all(),

                'faculties' =>
                    $this->faculties->all(),

                'pageScripts' => [
                    '/assets/js/medtrack-academic-program-form.js',
                ],
            ]
        );
    }

    /**
     * Enregistre un programme académique.
     */
    public function store(
        Request $request
    ): never {
        try {
            $programId = $this->programs->create(
                $this->formData(
                    $request
                )
            );
        } catch (RuntimeException $exception) {
            Response::json(
                [
                    'status' => 'error',
                    'code' => 'VALIDATION_ERROR',
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
                        'ACADEMIC_PROGRAM_CREATION_FAILED',

                    'message' =>
                        'Impossible d’enregistrer le programme '
                        . 'académique pour le moment.',
                ],
                500
            );
        }

        Response::json(
            [
                'status' => 'success',

                'message' =>
                    'Le programme académique a été '
                    . 'enregistré avec succès.',

                'program_id' =>
                    $programId,

                'redirect' =>
                    '/academic-programs/' . $programId,
            ],
            201
        );
    }

    /**
     * Affiche un programme académique.
     */
    public function show(
        Request $request
    ): string {
        $id = $this->routeId(
            $request
        );

        $program =
            $this->programs->findById(
                $id
            );

        if ($program === null) {
            http_response_code(404);

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
                    $program['name'],

                'program' =>
                    $program,
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

        $program =
            $this->programs->findById(
                $id
            );

        if ($program === null) {
            http_response_code(404);

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

                'universities' =>
                    $this->universities->all(),

                'faculties' =>
                    $this->faculties->all(),

                'pageScripts' => [
                    '/assets/js/medtrack-academic-program-form.js',
                ],
            ]
        );
    }

    /**
     * Met à jour un programme académique.
     */
    public function update(
        Request $request
    ): never {
        $id = $this->routeId(
            $request
        );

        try {
            $this->programs->update(
                $id,
                $this->formData(
                    $request
                )
            );
        } catch (RuntimeException $exception) {
            Response::json(
                [
                    'status' => 'error',
                    'code' => 'VALIDATION_ERROR',
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
                        'ACADEMIC_PROGRAM_UPDATE_FAILED',

                    'message' =>
                        'Impossible de modifier le programme '
                        . 'académique pour le moment.',
                ],
                500
            );
        }

        Response::json(
            [
                'status' => 'success',

                'message' =>
                    'Le programme académique a été '
                    . 'mis à jour avec succès.',

                'redirect' =>
                    '/academic-programs/' . $id,
            ]
        );
    }

    /**
     * Construit les données métier provenant
     * du formulaire.
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
                'Identifiant de programme académique invalide.'
            );
        }

        return $id;
    }
}