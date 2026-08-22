<?php

declare(strict_types=1);

namespace MedTrack\Modules\Identity\Controllers;

use MedTrack\Core\Http\Request;
use MedTrack\Core\Http\Response;
use MedTrack\Core\Http\View;
use MedTrack\Modules\Identity\Services\RoleManagementService;
use RuntimeException;
use Throwable;

final class RoleManagementController
{
    public function __construct(
        private readonly RoleManagementService $roles,
        private readonly View $view
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Read
    |--------------------------------------------------------------------------
    */

    /**
     * Supervision globale des rôles
     * et permissions MedTrack.
     */
    public function index(
        Request $request
    ): string {
        $overview =
            $this->roles
                ->platformOverview();

        return $this->view->render(
            'identity.roles.index',
            [
                'pageTitle' =>
                    'Rôles & permissions',

                'metrics' =>
                    $overview['metrics'],

                'roles' =>
                    $overview['roles'],

                'permissions' =>
                    $overview['permissions'],

                'pageScripts' => [
                    '/assets/js/medtrack-role-management.js',
                ],
            ]
        );
    }

    /**
     * Fiche détaillée d'un rôle.
     */
    public function show(
        Request $request
    ): string {
        $id =
            $this->routeId(
                $request
            );

        $data =
            $this->roles
                ->platformShow(
                    $id
                );

        if ($data === null) {
            http_response_code(
                404
            );

            return $this->view->render(
                'errors.404',
                [
                    'pageTitle' =>
                        'Rôle introuvable',
                ]
            );
        }

        return $this->view->render(
            'identity.roles.show',
            [
                'pageTitle' =>
                    'Détail du rôle',

                'role' =>
                    $data['role'],

                'permissions' =>
                    $data['permissions'],

                'availablePermissions' =>
                    $data['available_permissions'],

                'platformUsers' =>
                    $data['platform_users'],

                'memberships' =>
                    $data['memberships'],

                'usage' =>
                    $data['usage'],

                'pageScripts' => [
                    '/assets/js/medtrack-role-management.js',
                ],
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Create role
    |--------------------------------------------------------------------------
    */

    /**
     * Crée un rôle personnalisé.
     */
    public function create(
        Request $request
    ): never {
        try {
            $organizationType =
                trim(
                    (string) $request->input(
                        'organization_type',
                        ''
                    )
                );

            $roleId =
                $this->roles
                    ->createRole(
                        (string) $request->input(
                            'code',
                            ''
                        ),

                        (string) $request->input(
                            'name',
                            ''
                        ),

                        $organizationType !== ''
                            ? $organizationType
                            : null
                    );
        } catch (RuntimeException $exception) {
            Response::json(
                [
                    'status' =>
                        'error',

                    'code' =>
                        'ROLE_CREATION_FAILED',

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
                        'ROLE_CREATION_ERROR',

                    'message' =>
                        'Impossible de créer ce rôle '
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
                    'Le rôle a été créé avec succès.',

                'role_id' =>
                    $roleId,

                'redirect' =>
                    '/roles/' . $roleId,
            ],
            201
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Rename role
    |--------------------------------------------------------------------------
    */

    /**
     * Modifie le nom d'un rôle personnalisé.
     */
    public function rename(
        Request $request
    ): never {
        $roleId =
            $this->routeId(
                $request
            );

        try {
            $this->roles
                ->renameRole(
                    $roleId,
                    (string) $request->input(
                        'name',
                        ''
                    )
                );
        } catch (RuntimeException $exception) {
            Response::json(
                [
                    'status' =>
                        'error',

                    'code' =>
                        'ROLE_RENAME_FAILED',

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
                        'ROLE_RENAME_ERROR',

                    'message' =>
                        'Impossible de modifier ce rôle '
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
                    'Le rôle a été renommé avec succès.',

                'redirect' =>
                    '/roles/' . $roleId,
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Permissions
    |--------------------------------------------------------------------------
    */

    /**
     * Attribue une permission à un rôle.
     */
    public function assignPermission(
        Request $request
    ): never {
        $roleId =
            $this->routeId(
                $request
            );

        $permissionId =
            (int) $request->input(
                'permission_id',
                0
            );

        try {
            $this->roles
                ->assignPermission(
                    $roleId,
                    $permissionId
                );
        } catch (RuntimeException $exception) {
            Response::json(
                [
                    'status' =>
                        'error',

                    'code' =>
                        'ROLE_PERMISSION_ASSIGNMENT_FAILED',

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
                        'ROLE_PERMISSION_ASSIGNMENT_ERROR',

                    'message' =>
                        'Impossible d’attribuer cette permission '
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
                    'La permission a été attribuée au rôle.',

                'redirect' =>
                    '/roles/' . $roleId,
            ]
        );
    }

    /**
     * Retire une permission d'un rôle.
     */
    public function removePermission(
        Request $request
    ): never {
        $roleId =
            $this->routeId(
                $request
            );

        $permissionId =
            $this->routePermissionId(
                $request
            );

        try {
            $this->roles
                ->removePermission(
                    $roleId,
                    $permissionId
                );
        } catch (RuntimeException $exception) {
            Response::json(
                [
                    'status' =>
                        'error',

                    'code' =>
                        'ROLE_PERMISSION_REMOVAL_FAILED',

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
                        'ROLE_PERMISSION_REMOVAL_ERROR',

                    'message' =>
                        'Impossible de retirer cette permission '
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
                    'La permission a été retirée du rôle.',

                'redirect' =>
                    '/roles/' . $roleId,
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Delete role
    |--------------------------------------------------------------------------
    */

    /**
     * Supprime un rôle personnalisé inutilisé.
     */
    public function delete(
        Request $request
    ): never {
        $roleId =
            $this->routeId(
                $request
            );

        try {
            $this->roles
                ->deleteRole(
                    $roleId
                );
        } catch (RuntimeException $exception) {
            Response::json(
                [
                    'status' =>
                        'error',

                    'code' =>
                        'ROLE_DELETE_FAILED',

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
                        'ROLE_DELETE_ERROR',

                    'message' =>
                        'Impossible de supprimer ce rôle '
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
                    'Le rôle a été supprimé avec succès.',

                'redirect' =>
                    '/roles',
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Route parameters
    |--------------------------------------------------------------------------
    */

    /**
     * Extrait l'identifiant du rôle
     * depuis les attributs de route.
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
                'Identifiant de rôle invalide.'
            );
        }

        return $id;
    }

    /**
     * Extrait l'identifiant d'une permission
     * depuis les attributs de route.
     */
    private function routePermissionId(
        Request $request
    ): int {
        $permissionId =
            (int) $request->attribute(
                'permissionId',
                0
            );

        if ($permissionId <= 0) {
            throw new RuntimeException(
                'Identifiant de permission invalide.'
            );
        }

        return $permissionId;
    }
}