<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\Controllers;

use MedTrack\Core\Http\Request;
use MedTrack\Core\Http\Response;
use MedTrack\Core\Http\View;
use MedTrack\Modules\Academic\Services\UniversityService;
use RuntimeException;
use Throwable;

final class UniversityController
{
    public function __construct(
        private readonly UniversityService $universities,
        private readonly View $view
    ) {
    }

    /**
     * Affiche la liste des universités.
     */
    public function index(
        Request $request
    ): string {
        return $this->view->render(
            'academic.universities.index',
            [
                'pageTitle' => 'Universités',
                'universities' => $this->universities->all(),
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
            'academic.universities.create',
            [
                'pageTitle' => 'Nouvelle université',

                'pageScripts' => [
                    '/assets/js/medtrack-university-form.js',
                ],
            ]
        );
    }

    /**
     * Enregistre une nouvelle université.
     */
    public function store(
        Request $request
    ): never {
        try {
            $universityId = $this->universities->create(
                [
                    'code' => $request->input(
                        'code',
                        ''
                    ),

                    'name' => $request->input(
                        'name',
                        ''
                    ),

                    'province' => $request->input(
                        'province',
                        ''
                    ),

                    'city' => $request->input(
                        'city',
                        ''
                    ),

                    'address' => $request->input(
                        'address',
                        ''
                    ),

                    'phone' => $request->input(
                        'phone',
                        ''
                    ),

                    'email' => $request->input(
                        'email',
                        ''
                    ),

                    'university_type' => $request->input(
                        'university_type',
                        ''
                    ),

                    'accreditation_status' => $request->input(
                        'accreditation_status',
                        'PENDING'
                    ),

                    'accreditation_score' => $request->input(
                        'accreditation_score',
                        ''
                    ),
                ]
            );
        } catch (RuntimeException $exception) {
            Response::json(
                [
                    'status' => 'error',
                    'code' => 'VALIDATION_ERROR',
                    'message' => $exception->getMessage(),
                ],
                422
            );
        } catch (Throwable $exception) {
            /*
             * Ne pas exposer les détails SQL ou internes
             * dans la réponse publique.
             */
            Response::json(
                [
                    'status' => 'error',
                    'code' => 'UNIVERSITY_CREATION_FAILED',
                    'message' =>
                        'Impossible d’enregistrer l’université pour le moment.',
                ],
                500
            );
        }

        Response::json(
            [
                'status' => 'success',
                'message' =>
                    'L’université a été enregistrée avec succès.',
                'university_id' => $universityId,
                'redirect' => '/universities',
            ],
            201
        );
    }

    /**
     * Affiche une université.
     */
    public function show(
        Request $request
    ): string {
        $id = $this->routeId(
            $request
        );

        $university =
            $this->universities->findById(
                $id
            );

        if ($university === null) {
            http_response_code(404);

            return $this->view->render(
                'errors.404',
                [
                    'pageTitle' => 'Université introuvable',
                ]
            );
        }

        return $this->view->render(
            'academic.universities.show',
            [
                'pageTitle' => $university['name'],
                'university' => $university,
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

        $university =
            $this->universities->findById(
                $id
            );

        if ($university === null) {
            http_response_code(404);

            return $this->view->render(
                'errors.404',
                [
                    'pageTitle' => 'Université introuvable',
                ]
            );
        }

                return $this->view->render(
            'academic.universities.edit',
            [
                'pageTitle' => 'Modifier l’université',

                'university' => $university,

                'pageScripts' => [
                    '/assets/js/medtrack-university-form.js',
                ],
            ]
        );
    }

    /**
     * Met à jour une université.
     */
    public function update(
        Request $request
    ): never {
        $id = $this->routeId(
            $request
        );

        try {
            $this->universities->update(
                $id,
                [
                    'code' => $request->input(
                        'code',
                        ''
                    ),

                    'name' => $request->input(
                        'name',
                        ''
                    ),

                    'province' => $request->input(
                        'province',
                        ''
                    ),

                    'city' => $request->input(
                        'city',
                        ''
                    ),

                    'address' => $request->input(
                        'address',
                        ''
                    ),

                    'phone' => $request->input(
                        'phone',
                        ''
                    ),

                    'email' => $request->input(
                        'email',
                        ''
                    ),

                    'status' => $request->input(
                        'status',
                        'ACTIVE'
                    ),

                    'university_type' => $request->input(
                        'university_type',
                        ''
                    ),

                    'accreditation_status' => $request->input(
                        'accreditation_status',
                        'PENDING'
                    ),

                    'accreditation_score' => $request->input(
                        'accreditation_score',
                        ''
                    ),
                ]
            );
        } catch (RuntimeException $exception) {
            Response::json(
                [
                    'status' => 'error',
                    'code' => 'VALIDATION_ERROR',
                    'message' => $exception->getMessage(),
                ],
                422
            );
        } catch (Throwable $exception) {
            Response::json(
                [
                    'status' => 'error',
                    'code' => 'UNIVERSITY_UPDATE_FAILED',
                    'message' =>
                        'Impossible de modifier l’université pour le moment.',
                ],
                500
            );
        }

        Response::json(
            [
                'status' => 'success',
                'message' =>
                    'L’université a été mise à jour avec succès.',
                'redirect' => '/universities',
            ]
        );
    }

    /**
     * Extrait l'identifiant numérique fourni par le Router.
     *
     * Le nom exact de la clé dépend du mécanisme de paramètres
     * déjà implémenté dans Request/Router.
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
                'Identifiant d’université invalide.'
            );
        }

        return $id;
    }
}