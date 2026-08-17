<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\Controllers;

use MedTrack\Core\Http\Request;
use MedTrack\Core\Http\Response;
use MedTrack\Core\Http\View;
use MedTrack\Modules\Academic\Services\ProfessionalOrderService;
use RuntimeException;
use Throwable;

final class ProfessionalOrderController
{
    public function __construct(
        private readonly ProfessionalOrderService $orders,
        private readonly View $view
    ) {
    }

    /**
     * Affiche la liste des ordres professionnels.
     */
    public function index(
        Request $request
    ): string {
        return $this->view->render(
            'academic.professional-orders.index',
            [
                'pageTitle' =>
                    'Ordres professionnels',

                'professionalOrders' =>
                    $this->orders->all(),
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
            'academic.professional-orders.create',
            [
                'pageTitle' =>
                    'Nouvel ordre professionnel',

                'pageScripts' => [
                    '/assets/js/medtrack-professional-order-form.js',
                ],
            ]
        );
    }

    /**
     * Enregistre un nouvel ordre professionnel.
     */
    public function store(
        Request $request
    ): never {
        try {
            $orderId =
                $this->orders->create(
                    $this->orderPayload(
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
            /*
             * Les détails SQL et les erreurs internes
             * ne doivent jamais être exposés au client.
             */
            Response::json(
                [
                    'status' =>
                        'error',

                    'code' =>
                        'PROFESSIONAL_ORDER_CREATION_FAILED',

                    'message' =>
                        'Impossible d’enregistrer '
                        . 'l’ordre professionnel '
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
                    'L’ordre professionnel a été '
                    . 'enregistré avec succès.',

                'professional_order_id' =>
                    $orderId,

                'redirect' =>
                    '/professional-orders/' . $orderId,
            ],
            201
        );
    }

    /**
     * Affiche la fiche d'un ordre professionnel.
     */
    public function show(
        Request $request
    ): string {
        $id =
            $this->routeId(
                $request
            );

        $order =
            $this->orders->findById(
                $id
            );

        if ($order === null) {
            http_response_code(
                404
            );

            return $this->view->render(
                'errors.404',
                [
                    'pageTitle' =>
                        'Ordre professionnel introuvable',
                ]
            );
        }

        return $this->view->render(
            'academic.professional-orders.show',
            [
                'pageTitle' =>
                    (string) (
                        $order['name']
                        ?? 'Ordre professionnel'
                    ),

                'professionalOrder' =>
                    $order,
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

        $order =
            $this->orders->findById(
                $id
            );

        if ($order === null) {
            http_response_code(
                404
            );

            return $this->view->render(
                'errors.404',
                [
                    'pageTitle' =>
                        'Ordre professionnel introuvable',
                ]
            );
        }

        return $this->view->render(
            'academic.professional-orders.edit',
            [
                'pageTitle' =>
                    'Modifier l’ordre professionnel',

                'professionalOrder' =>
                    $order,

                'pageScripts' => [
                    '/assets/js/medtrack-professional-order-form.js',
                ],
            ]
        );
    }

    /**
     * Met à jour un ordre professionnel.
     */
    public function update(
        Request $request
    ): never {
        $id =
            $this->routeId(
                $request
            );

        try {
            $this->orders->update(
                $id,
                $this->orderPayload(
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
                        'PROFESSIONAL_ORDER_UPDATE_FAILED',

                    'message' =>
                        'Impossible de modifier '
                        . 'l’ordre professionnel '
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
                    'L’ordre professionnel a été '
                    . 'mis à jour avec succès.',

                'redirect' =>
                    '/professional-orders/' . $id,
            ]
        );
    }

    /**
     * Construit les données métier autorisées
     * provenant de la requête HTTP.
     *
     * UUID, type, created_at et updated_at
     * sont exclusivement gérés côté serveur.
     */
    private function orderPayload(
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

            'profession_code' =>
                $request->input(
                    'profession_code',
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
                'Identifiant d’ordre professionnel invalide.'
            );
        }

        return $id;
    }
}