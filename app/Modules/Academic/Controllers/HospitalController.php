<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\Controllers;

use MedTrack\Core\Http\Request;
use MedTrack\Core\Http\Response;
use MedTrack\Core\Http\View;
use MedTrack\Modules\Academic\Services\HospitalService;
use RuntimeException;
use Throwable;

final class HospitalController
{
    public function __construct(
        private readonly HospitalService $hospitals,
        private readonly View $view
    ) {
    }

    /**
     * Affiche la liste des hôpitaux.
     */
    public function index(
        Request $request
    ): string {
        return $this->view->render(
            'academic.hospitals.index',
            [
                'pageTitle' =>
                    'Hôpitaux',

                'hospitals' =>
                    $this->hospitals->all(),
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
            'academic.hospitals.create',
            [
                'pageTitle' =>
                    'Nouvel hôpital',

                'pageScripts' => [
                    '/assets/js/medtrack-hospital-form.js',
                ],
            ]
        );
    }

    /**
     * Enregistre un nouvel hôpital.
     */
    public function store(
        Request $request
    ): never {
        try {
            $hospitalId =
                $this->hospitals->create(
                    $this->hospitalPayload(
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
                        'HOSPITAL_CREATION_FAILED',

                    'message' =>
                        'Impossible d’enregistrer '
                        . 'l’hôpital pour le moment.',
                ],
                500
            );
        }

        Response::json(
            [
                'status' =>
                    'success',

                'message' =>
                    'L’hôpital a été enregistré '
                    . 'avec succès.',

                'hospital_id' =>
                    $hospitalId,

                'redirect' =>
                    '/hospitals',
            ],
            201
        );
    }

    /**
     * Affiche un hôpital.
     */
    public function show(
        Request $request
    ): string {
        $id =
            $this->routeId(
                $request
            );

        $hospital =
            $this->hospitals
                ->findById(
                    $id
                );

        if ($hospital === null) {
            http_response_code(404);

            return $this->view->render(
                'errors.404',
                [
                    'pageTitle' =>
                        'Hôpital introuvable',
                ]
            );
        }

        return $this->view->render(
            'academic.hospitals.show',
            [
                'pageTitle' =>
                    (string) (
                        $hospital['name']
                        ?? 'Hôpital'
                    ),

                'hospital' =>
                    $hospital,
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

        $hospital =
            $this->hospitals
                ->findById(
                    $id
                );

        if ($hospital === null) {
            http_response_code(404);

            return $this->view->render(
                'errors.404',
                [
                    'pageTitle' =>
                        'Hôpital introuvable',
                ]
            );
        }

        return $this->view->render(
            'academic.hospitals.edit',
            [
                'pageTitle' =>
                    'Modifier l’hôpital',

                'hospital' =>
                    $hospital,

                'pageScripts' => [
                    '/assets/js/medtrack-hospital-form.js',
                ],
            ]
        );
    }

    /**
     * Met à jour un hôpital.
     */
    public function update(
        Request $request
    ): never {
        $id =
            $this->routeId(
                $request
            );

        try {
            $this->hospitals->update(
                $id,
                $this->hospitalPayload(
                    $request,
                    true
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
                        'HOSPITAL_UPDATE_FAILED',

                    'message' =>
                        'Impossible de modifier '
                        . 'l’hôpital pour le moment.',
                ],
                500
            );
        }

        Response::json(
            [
                'status' =>
                    'success',

                'message' =>
                    'L’hôpital a été mis à jour '
                    . 'avec succès.',

                'redirect' =>
                    '/hospitals/' . $id,
            ]
        );
    }

    /**
     * Construit les données provenant
     * du formulaire d'hôpital.
     */
    private function hospitalPayload(
        Request $request,
        bool $includeStatus = false
    ): array {
        $payload = [
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

            'facility_level' =>
                $request->input(
                    'facility_level',
                    ''
                ),

            'specialty' =>
                $request->input(
                    'specialty',
                    ''
                ),

            'internship_capacity' =>
                $request->input(
                    'internship_capacity',
                    '0'
                ),

            'accreditation_status' =>
                $request->input(
                    'accreditation_status',
                    'PENDING'
                ),

            'latitude' =>
                $request->input(
                    'latitude',
                    ''
                ),

            'longitude' =>
                $request->input(
                    'longitude',
                    ''
                ),
        ];

        if ($includeStatus) {
            $payload['status'] =
                $request->input(
                    'status',
                    'ACTIVE'
                );
        }

        return $payload;
    }

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
                'Identifiant d’hôpital invalide.'
            );
        }

        return $id;
    }
}