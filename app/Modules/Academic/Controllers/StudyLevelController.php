<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\Controllers;

use MedTrack\Core\Context\AccessContext;
use MedTrack\Core\Context\AccessContextResolver;
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
     * Affiche la liste des niveaux d'études.
     *
     * PLATFORM :
     * lecture complète.
     *
     * UNIVERSITY :
     * lecture seule.
     */
    public function index(
        Request $request
    ): string {
        $context =
            $this->accessContext();

        return $this->view->render(
            'academic.study-levels.index',
            [
                'pageTitle' =>
                    'Niveaux d’études',

                'studyLevels' =>
                    $this->studyLevels
                        ->all(),

                'statistics' =>
                    $this->studyLevels
                        ->statistics(),

                'isPlatform' =>
                    $context->isPlatform(),

                'isUniversityContext' =>
                    $this->isUniversityContext(
                        $context
                    ),

                /*
                 * Les vues utiliseront cette valeur
                 * pour masquer les boutons
                 * Créer / Modifier.
                 */
                'isReadOnly' =>
                    !$context->isPlatform(),
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
     * Réservé à l'administration centrale MedTrack.
     */
    public function create(
        Request $request
    ): string {
        $this->requirePlatformContext();

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

    /*
    |--------------------------------------------------------------------------
    | Store
    |--------------------------------------------------------------------------
    */

    /**
     * Enregistre un nouveau niveau d'études.
     *
     * Réservé à PLATFORM.
     */
    public function store(
        Request $request
    ): never {
        /*
         * Protection métier en profondeur.
         *
         * Même si quelqu'un contourne l'interface
         * ou appelle directement cette route,
         * une organisation UNIVERSITY ne peut
         * jamais créer un référentiel global.
         */
        $this->requirePlatformContext();

        try {
            $studyLevelId =
                $this->studyLevels
                    ->create(
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
                'status' =>
                    'success',

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

    /*
    |--------------------------------------------------------------------------
    | Show
    |--------------------------------------------------------------------------
    */

    /**
     * Affiche un niveau d'études.
     *
     * Accessible en lecture à PLATFORM
     * et UNIVERSITY.
     */
    public function show(
        Request $request
    ): string {
        $context =
            $this->accessContext();

        $id =
            $this->routeId(
                $request
            );

        $studyLevel =
            $this->studyLevels
                ->findById(
                    $id
                );

        if ($studyLevel === null) {
            http_response_code(
                404
            );

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
                    $studyLevel['name']
                    ?? 'Niveau d’études',

                'studyLevel' =>
                    $studyLevel,

                'isPlatform' =>
                    $context->isPlatform(),

                'isUniversityContext' =>
                    $this->isUniversityContext(
                        $context
                    ),

                'isReadOnly' =>
                    !$context->isPlatform(),
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
     * Réservé à PLATFORM.
     */
    public function edit(
        Request $request
    ): string {
        $this->requirePlatformContext();

        $id =
            $this->routeId(
                $request
            );

        $studyLevel =
            $this->studyLevels
                ->findById(
                    $id
                );

        if ($studyLevel === null) {
            http_response_code(
                404
            );

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

    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    /**
     * Met à jour un niveau d'études.
     *
     * Réservé à PLATFORM.
     */
    public function update(
        Request $request
    ): never {
        $this->requirePlatformContext();

        $id =
            $this->routeId(
                $request
            );

        try {
            $this->studyLevels
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
                'status' =>
                    'success',

                'message' =>
                    'Le niveau d’études a été '
                    . 'mis à jour avec succès.',

                'redirect' =>
                    '/study-levels/'
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
     * Résout le contexte d'accès actif.
     */
    private function accessContext(): AccessContext
    {
        return $this->accessContextResolver
            ->resolve();
    }

    /**
     * Vérifie si l'utilisateur travaille
     * dans un contexte UNIVERSITY.
     */
    private function isUniversityContext(
        AccessContext $context
    ): bool {
        return
            $context->isOrganization()
            && strtoupper(
                trim(
                    $context->organizationType()
                )
            ) === 'UNIVERSITY';
    }

    /**
     * Exige un contexte PLATFORM.
     *
     * Study Levels est un référentiel global MedTrack.
     * Une organisation ne doit jamais pouvoir
     * créer ou modifier ses valeurs.
     */
    private function requirePlatformContext(): void
    {
        $context =
            $this->accessContext();

        if ($context->isPlatform()) {
            return;
        }

        throw new RuntimeException(
            'Les niveaux d’études sont administrés '
            . 'exclusivement par MedTrack. '
            . 'Votre espace dispose uniquement '
            . 'd’un accès en lecture.'
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
                'Identifiant du niveau '
                . 'd’études invalide.'
            );
        }

        return $id;
    }
}