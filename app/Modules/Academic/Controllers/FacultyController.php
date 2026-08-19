<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\Controllers;

use MedTrack\Core\Context\AccessContext;
use MedTrack\Core\Context\AccessContextResolver;
use MedTrack\Core\Http\Request;
use MedTrack\Core\Http\Response;
use MedTrack\Core\Http\View;
use MedTrack\Modules\Academic\Services\FacultyService;
use MedTrack\Modules\Academic\Services\UniversityService;
use RuntimeException;
use Throwable;

final class FacultyController
{
    private const UNIVERSITY_TYPE =
        'UNIVERSITY';

    public function __construct(
        private readonly FacultyService $faculties,
        private readonly UniversityService $universities,
        private readonly AccessContextResolver $accessContextResolver,
        private readonly View $view
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    /**
     * Affiche les facultés visibles
     * depuis le contexte actif.
     *
     * PLATFORM :
     * toutes les facultés.
     *
     * UNIVERSITY :
     * uniquement les facultés
     * de l'université active.
     */
    public function index(
        Request $request
    ): string {
        $context =
            $this->facultyContext();

        return $this->view->render(
            'academic.faculties.index',
            [
                'pageTitle' =>
                    'Facultés',

                'faculties' =>
                    $this->faculties
                        ->all(),

                'statistics' =>
                    $this->faculties
                        ->statistics(),

                'isPlatform' =>
                    $context->isPlatform(),

                'isUniversityContext' =>
                    $this->isUniversityContext(
                        $context
                    ),
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
     * PLATFORM :
     * choix de l'université.
     *
     * UNIVERSITY :
     * l'université est imposée par
     * le contexte serveur.
     */
    public function create(
        Request $request
    ): string {
        $context =
            $this->facultyContext();

        $isPlatform =
            $context->isPlatform();

        return $this->view->render(
            'academic.faculties.create',
            [
                'pageTitle' =>
                    'Nouvelle faculté',

                /*
                 * En contexte Université,
                 * nous ne devons pas exposer
                 * la liste des autres universités.
                 */
                'universities' =>
                    $isPlatform
                        ? $this->universities
                            ->all()
                        : [],

                'isPlatform' =>
                    $isPlatform,

                'isUniversityContext' =>
                    !$isPlatform,

                'activeUniversityId' =>
                    !$isPlatform
                        ? $context
                            ->organizationId()
                        : null,

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
        $context =
            $this->facultyContext();

        /*
         * En contexte Université, cette valeur
         * sera de toute façon remplacée par
         * FacultyService avec organizationId().
         *
         * On la lit encore ici pour préserver
         * le workflow PLATFORM.
         */
        $universityId =
            $context->isPlatform()
                ? (int) $request->input(
                    'university_id',
                    0
                )
                : $context->organizationId();

        try {
            $facultyId =
                $this->faculties
                    ->create(
                        [
                            'university_id' =>
                                $universityId,

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
                'status' =>
                    'success',

                'message' =>
                    'La faculté a été enregistrée '
                    . 'avec succès.',

                'faculty_id' =>
                    $facultyId,

                'redirect' =>
                    '/faculties/'
                    . $facultyId,
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
     * Affiche la fiche d'une faculté.
     *
     * FacultyService garantit qu'une université
     * ne peut consulter qu'une faculté lui
     * appartenant.
     */
    public function show(
        Request $request
    ): string {
        $context =
            $this->facultyContext();

        $id =
            $this->routeId(
                $request
            );

        $faculty =
            $this->faculties
                ->findById(
                    $id
                );

        if ($faculty === null) {
            http_response_code(
                404
            );

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

                'isPlatform' =>
                    $context->isPlatform(),

                'isUniversityContext' =>
                    $this->isUniversityContext(
                        $context
                    ),
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    /**
     * Affiche le formulaire de modification.
     *
     * Une université ne peut modifier
     * qu'une faculté lui appartenant.
     */
    public function edit(
        Request $request
    ): string {
        $context =
            $this->facultyContext();

        $id =
            $this->routeId(
                $request
            );

        $faculty =
            $this->faculties
                ->findById(
                    $id
                );

        if ($faculty === null) {
            http_response_code(
                404
            );

            return $this->view->render(
                'errors.404',
                [
                    'pageTitle' =>
                        'Faculté introuvable',
                ]
            );
        }

        $isPlatform =
            $context->isPlatform();

        return $this->view->render(
            'academic.faculties.edit',
            [
                'pageTitle' =>
                    'Modifier la faculté',

                'faculty' =>
                    $faculty,

                /*
                 * L'espace Université ne doit
                 * jamais voir la liste des autres
                 * institutions.
                 */
                'universities' =>
                    $isPlatform
                        ? $this->universities
                            ->all()
                        : [],

                'isPlatform' =>
                    $isPlatform,

                'isUniversityContext' =>
                    !$isPlatform,

                'activeUniversityId' =>
                    !$isPlatform
                        ? $context
                            ->organizationId()
                        : null,

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
        $context =
            $this->facultyContext();

        $id =
            $this->routeId(
                $request
            );

        $universityId =
            $context->isPlatform()
                ? (int) $request->input(
                    'university_id',
                    0
                )
                : $context->organizationId();

        try {
            $this->faculties
                ->update(
                    $id,
                    [
                        'university_id' =>
                            $universityId,

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
                'status' =>
                    'success',

                'message' =>
                    'La faculté a été mise à jour '
                    . 'avec succès.',

                'redirect' =>
                    '/faculties/'
                    . $id,
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Context
    |--------------------------------------------------------------------------
    */

    /**
     * Retourne uniquement un contexte dans lequel
     * le module Facultés est autorisé.
     *
     * Le module est accessible :
     * - à PLATFORM ;
     * - à ORGANIZATION / UNIVERSITY.
     */
    private function facultyContext(): AccessContext
    {
        $context =
            $this->accessContextResolver
                ->resolve();

        if ($context->isPlatform()) {
            return $context;
        }

        if (
            $this->isUniversityContext(
                $context
            )
        ) {
            return $context;
        }

        throw new RuntimeException(
            'Le module Facultés est réservé '
            . 'à l’administration MedTrack '
            . 'et aux universités.'
        );
    }

    /**
     * Vérifie que le contexte est celui
     * d'une université.
     */
    private function isUniversityContext(
        AccessContext $context
    ): bool {
        if (!$context->isOrganization()) {
            return false;
        }

        return strtoupper(
            trim(
                $context->organizationType()
            )
        ) === self::UNIVERSITY_TYPE;
    }

    /*
    |--------------------------------------------------------------------------
    | Route
    |--------------------------------------------------------------------------
    */

    /**
     * Extrait l'identifiant fourni
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
                'Identifiant de faculté invalide.'
            );
        }

        return $id;
    }
}