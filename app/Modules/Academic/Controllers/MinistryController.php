<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\Controllers;

use MedTrack\Core\Http\Request;
use MedTrack\Core\Http\Response;
use MedTrack\Core\Http\View;
use MedTrack\Modules\Academic\Services\MinistryService;
use RuntimeException;
use Throwable;

final class MinistryController
{
    public function __construct(
        private readonly MinistryService $ministries,
        private readonly View $view
    ) {
    }

    /**
     * Affiche la liste des ministères.
     */
    public function index(
        Request $request
    ): string {
        return $this->view->render(
            'academic.ministries.index',
            [
                'pageTitle' =>
                    'Ministères',

                'ministries' =>
                    $this->ministries->all(),
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
            'academic.ministries.create',
            [
                'pageTitle' =>
                    'Nouveau ministère',

                'pageScripts' => [
                    '/assets/js/medtrack-ministry-form.js',
                ],
            ]
        );
    }

    /**
     * Enregistre un nouveau ministère.
     */
    public function store(
        Request $request
    ): never {
        try {
            $ministryId =
                $this->ministries->create(
                    $this->ministryPayload(
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
                        'MINISTRY_CREATION_FAILED',

                    'message' =>
                        'Impossible d’enregistrer '
                        . 'le ministère pour le moment.',
                ],
                500
            );
        }

        Response::json(
            [
                'status' =>
                    'success',

                'message' =>
                    'Le ministère a été enregistré '
                    . 'avec succès.',

                'ministry_id' =>
                    $ministryId,

                'redirect' =>
                    '/ministries/' . $ministryId,
            ],
            201
        );
    }

    /**
     * Affiche la fiche d'un ministère.
     */
    public function show(
        Request $request
    ): string {
        $id =
            $this->routeId(
                $request
            );

        $ministry =
            $this->ministries->findById(
                $id
            );

        if ($ministry === null) {
            http_response_code(
                404
            );

            return $this->view->render(
                'errors.404',
                [
                    'pageTitle' =>
                        'Ministère introuvable',
                ]
            );
        }

        return $this->view->render(
            'academic.ministries.show',
            [
                'pageTitle' =>
                    (string) (
                        $ministry['name']
                        ?? 'Ministère'
                    ),

                'ministry' =>
                    $ministry,
            ]
        );
    }

    /**
     * Affiche le formulaire de modification.
     */
    public function edit(
        Request $request
    ): string {
        $id =
            $this->routeId(
                $request
            );

        $ministry =
            $this->ministries->findById(
                $id
            );

        if ($ministry === null) {
            http_response_code(
                404
            );

            return $this->view->render(
                'errors.404',
                [
                    'pageTitle' =>
                        'Ministère introuvable',
                ]
            );
        }

        return $this->view->render(
            'academic.ministries.edit',
            [
                'pageTitle' =>
                    'Modifier le ministère',

                'ministry' =>
                    $ministry,

                'pageScripts' => [
                    '/assets/js/medtrack-ministry-form.js',
                ],
            ]
        );
    }

    /**
     * Met à jour un ministère.
     */
    public function update(
        Request $request
    ): never {
        $id =
            $this->routeId(
                $request
            );

        try {
            $this->ministries->update(
                $id,
                $this->ministryPayload(
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
                        'MINISTRY_UPDATE_FAILED',

                    'message' =>
                        'Impossible de modifier '
                        . 'le ministère pour le moment.',
                ],
                500
            );
        }

        Response::json(
            [
                'status' =>
                    'success',

                'message' =>
                    'Le ministère a été mis à jour '
                    . 'avec succès.',

                'redirect' =>
                    '/ministries/' . $id,
            ]
        );
    }

    /**
     * Construit les données métier autorisées
     * provenant de la requête HTTP.
     */
    private function ministryPayload(
        Request $request
    ): array {
        return [
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

            'ministry_scope' =>
                $request->input(
                    'ministry_scope',
                    ''
                ),

            'province' =>
                $request->input(
                    'province',
                    ''
                ),

            'city' =>
                $request->input(
                    'city',
                    ''
                ),

            'address' =>
                $request->input(
                    'address',
                    ''
                ),

            'phone' =>
                $request->input(
                    'phone',
                    ''
                ),

            'email' =>
                $request->input(
                    'email',
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
        $id =
            (int) $request->attribute(
                'id',
                0
            );

        if ($id <= 0) {
            throw new RuntimeException(
                'Identifiant de ministère invalide.'
            );
        }

        return $id;
    }
}