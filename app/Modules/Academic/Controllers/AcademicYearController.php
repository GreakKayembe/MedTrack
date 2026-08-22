<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\Controllers;

use MedTrack\Core\Context\AccessContextResolver;
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
        private readonly AccessContextResolver $accessContextResolver,
        private readonly View $view
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Read
    |--------------------------------------------------------------------------
    */

    /**
     * Affiche la liste des années académiques.
     *
     * Accessible :
     * - plateforme ;
     * - université en lecture seule.
     */
    public function index(
        Request $request
    ): string {
        return $this->view->render(
            'academic.years.index',
            [
                'pageTitle' =>
                    'Années académiques',

                'academicYears' =>
                    $this->academicYears
                        ->all(),

                'statistics' =>
                    $this->academicYears
                        ->statistics(),

                /*
                 * Permet à la vue de masquer
                 * les actions de création/modification.
                 */
                'readOnly' =>
                    !$this->isPlatformContext(),
            ]
        );
    }

    /**
     * Affiche les informations détaillées
     * d'une année académique.
     *
     * Accessible en lecture seule à l'université.
     */
    public function show(
        Request $request
    ): string {
        $id =
            $this->routeId(
                $request
            );

        $academicYear =
            $this->academicYears
                ->findById(
                    $id
                );

        if ($academicYear === null) {
            http_response_code(
                404
            );

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

                'readOnly' =>
                    !$this->isPlatformContext(),
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    /**
     * Affiche le formulaire de création.
     *
     * Réservé exclusivement au contexte PLATFORM.
     */
    public function create(
        Request $request
    ): string {
        $this->assertPlatformAccess();

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
     *
     * Réservé exclusivement au contexte PLATFORM.
     */
    public function store(
        Request $request
    ): never {
        /*
         * Le contrôle d'autorisation doit être
         * effectué avant toute validation métier.
         */
        if (!$this->isPlatformContext()) {
            Response::json(
                [
                    'status' =>
                        'error',

                    'code' =>
                        'FORBIDDEN',

                    'message' =>
                        'La création des années académiques '
                        . 'est réservée à l’administration '
                        . 'centrale MedTrack.',
                ],
                403
            );
        }

        try {
            $academicYearId =
                $this->academicYears
                    ->create(
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
                'status' =>
                    'success',

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

    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    /**
     * Affiche le formulaire de modification.
     *
     * Réservé exclusivement au contexte PLATFORM.
     */
    public function edit(
        Request $request
    ): string {
        $this->assertPlatformAccess();

        $id =
            $this->routeId(
                $request
            );

        $academicYear =
            $this->academicYears
                ->findById(
                    $id
                );

        if ($academicYear === null) {
            http_response_code(
                404
            );

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
     *
     * Réservé exclusivement au contexte PLATFORM.
     */
    public function update(
        Request $request
    ): never {
        if (!$this->isPlatformContext()) {
            Response::json(
                [
                    'status' =>
                        'error',

                    'code' =>
                        'FORBIDDEN',

                    'message' =>
                        'La modification des années académiques '
                        . 'est réservée à l’administration '
                        . 'centrale MedTrack.',
                ],
                403
            );
        }

        $id =
            $this->routeId(
                $request
            );

        try {
            $this->academicYears
                ->update(
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
                'status' =>
                    'success',

                'message' =>
                    'L’année académique a été '
                    . 'mise à jour avec succès.',

                'redirect' =>
                    '/academic-years/' . $id,
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Access control
    |--------------------------------------------------------------------------
    */

    /**
     * Indique si le contexte actif correspond
     * à l'administration centrale MedTrack.
     */
    private function isPlatformContext(): bool
    {
        $context =
            $this->accessContextResolver
                ->resolve();

        return $context->isPlatform();
    }

    /**
     * Bloque toute tentative d'accès à une
     * action d'administration globale depuis
     * un contexte institutionnel.
     */
    private function assertPlatformAccess(): void
    {
        if ($this->isPlatformContext()) {
            return;
        }

        throw new RuntimeException(
            'Cette opération est réservée '
            . 'à l’administration centrale MedTrack.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Route helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Extrait l'identifiant numérique injecté
     * dans Request par le Router.
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
                'Identifiant d’année académique invalide.'
            );
        }

        return $id;
    }
}