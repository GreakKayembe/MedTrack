<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\Controllers;

use MedTrack\Core\Http\Request;
use MedTrack\Core\Http\Response;
use MedTrack\Core\Http\View;
use MedTrack\Modules\Academic\Services\UniversityOnboardingService;
use MedTrack\Modules\Academic\Services\UniversityService;
use RuntimeException;
use Throwable;

final class UniversityController
{
    public function __construct(
        private readonly UniversityService $universities,
        private readonly UniversityOnboardingService $universityOnboarding,
        private readonly View $view
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    /**
     * Affiche la liste des universités.
     */
    public function index(
        Request $request
    ): string {
        return $this->view->render(
            'academic.universities.index',
            [
                'pageTitle' =>
                    'Universités',

                'universities' =>
                    $this->universities
                        ->all(),
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    /**
     * Affiche le formulaire de création
     * d'une université.
     */
    public function create(
        Request $request
    ): string {
        return $this->view->render(
            'academic.universities.create',
            [
                'pageTitle' =>
                    'Nouvelle université',

                'pageScripts' => [
                    '/assets/js/medtrack-university-form.js',
                ],
            ]
        );
    }

    /**
     * Crée une université ainsi que
     * son administrateur principal.
     */
    public function store(
        Request $request
    ): never {
        try {
            $result =
                $this->universityOnboarding
                    ->createUniversityWithAdministrator(
                        /*
                        |--------------------------------------------------------------------------
                        | University
                        |--------------------------------------------------------------------------
                        */
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

                            'university_type' =>
                                $request->input(
                                    'university_type',
                                    ''
                                ),

                            'accreditation_status' =>
                                $request->input(
                                    'accreditation_status',
                                    'PENDING'
                                ),

                            'accreditation_score' =>
                                $request->input(
                                    'accreditation_score',
                                    ''
                                ),
                        ],

                        /*
                        |--------------------------------------------------------------------------
                        | Administrator
                        |--------------------------------------------------------------------------
                        */
                        [
                            'first_name' =>
                                $request->input(
                                    'admin_first_name',
                                    ''
                                ),

                            'middle_name' =>
                                $request->input(
                                    'admin_middle_name',
                                    ''
                                ),

                            'last_name' =>
                                $request->input(
                                    'admin_last_name',
                                    ''
                                ),

                            'email' =>
                                $request->input(
                                    'admin_email',
                                    ''
                                ),

                            'phone' =>
                                $request->input(
                                    'admin_phone',
                                    ''
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
            /*
             * Les détails SQL, mots de passe hashés,
             * stack traces ou autres informations
             * internes ne sont jamais exposés.
             */
            Response::json(
                [
                    'status' =>
                        'error',

                    'code' =>
                        'UNIVERSITY_ONBOARDING_FAILED',

                    'message' =>
                        'Impossible de créer '
                        . 'l’université et son '
                        . 'administrateur pour le moment.',
                ],
                500
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Successful onboarding
        |--------------------------------------------------------------------------
        |
        | Le mot de passe temporaire est renvoyé
        | une seule fois au Super Admin.
        |
        | Il n'est jamais stocké en clair.
        |--------------------------------------------------------------------------
        */

        Response::json(
            [
                'status' =>
                    'success',

                'message' =>
                    'L’université et son administrateur '
                    . 'principal ont été créés avec succès.',

                'university_id' =>
                    $result['university_id'],

                'administrator' => [
                    'user_id' =>
                        $result['user_id'],

                    'membership_id' =>
                        $result['membership_id'],

                    'email' =>
                        $result[
                            'administrator_email'
                        ],

                    'temporary_password' =>
                        $result[
                            'temporary_password'
                        ],

                    'must_change_password' =>
                        true,
                ],

                'redirect' =>
                    '/universities/'
                    . $result['university_id'],
            ],
            201
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Show
    |--------------------------------------------------------------------------
    */

    /**
     * Affiche une université.
     */
    public function show(
        Request $request
    ): string {
        $id =
            $this->routeId(
                $request
            );

        $university =
            $this->universities
                ->findById(
                    $id
                );

        if ($university === null) {
            http_response_code(
                404
            );

            return $this->view->render(
                'errors.404',
                [
                    'pageTitle' =>
                        'Université introuvable',
                ]
            );
        }

        return $this->view->render(
            'academic.universities.show',
            [
                'pageTitle' =>
                    $university['name'],

                'university' =>
                    $university,
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    /**
     * Affiche le formulaire
     * de modification.
     */
    public function edit(
        Request $request
    ): string {
        $id =
            $this->routeId(
                $request
            );

        $university =
            $this->universities
                ->findById(
                    $id
                );

        if ($university === null) {
            http_response_code(
                404
            );

            return $this->view->render(
                'errors.404',
                [
                    'pageTitle' =>
                        'Université introuvable',
                ]
            );
        }

        return $this->view->render(
            'academic.universities.edit',
            [
                'pageTitle' =>
                    'Modifier l’université',

                'university' =>
                    $university,

                'pageScripts' => [
                    '/assets/js/medtrack-university-form.js',
                ],
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    /**
     * Met à jour une université.
     *
     * Cette opération ne modifie pas
     * automatiquement son administrateur.
     */
    public function update(
        Request $request
    ): never {
        $id =
            $this->routeId(
                $request
            );

        try {
            $this->universities
                ->update(
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

                        'university_type' =>
                            $request->input(
                                'university_type',
                                ''
                            ),

                        'accreditation_status' =>
                            $request->input(
                                'accreditation_status',
                                'PENDING'
                            ),

                        'accreditation_score' =>
                            $request->input(
                                'accreditation_score',
                                ''
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
                        'UNIVERSITY_UPDATE_FAILED',

                    'message' =>
                        'Impossible de modifier '
                        . 'l’université pour le moment.',
                ],
                500
            );
        }

        Response::json(
            [
                'status' =>
                    'success',

                'message' =>
                    'L’université a été mise '
                    . 'à jour avec succès.',

                'redirect' =>
                    '/universities/'
                    . $id,
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

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
                'Identifiant d’université invalide.'
            );
        }

        return $id;
    }
}