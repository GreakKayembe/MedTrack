<?php

declare(strict_types=1);

namespace MedTrack\Modules\Identity\Controllers;

use MedTrack\Core\Http\Request;
use MedTrack\Core\Http\Response;
use MedTrack\Core\Http\View;
use MedTrack\Modules\Identity\Services\UserManagementService;
use RuntimeException;
use Throwable;

final class UserManagementController
{
    public function __construct(
        private readonly UserManagementService $users,
        private readonly View $view
    ) {
    }

    /**
     * Affiche la supervision globale
     * des utilisateurs MedTrack.
     */
    public function index(
        Request $request
    ): string {
        $overview =
            $this->users
                ->platformOverview();

        return $this->view->render(
            'identity.users.index',
            [
                'pageTitle' =>
                    'Utilisateurs',

                'metrics' =>
                    $overview['metrics'],

                'accessMetrics' =>
                    $overview['access_metrics'],

                'loginMetrics' =>
                    $overview['login_metrics'],

                'users' =>
                    $overview['users'],
            ]
        );
    }

    /**
     * Affiche la fiche complète
     * d'un utilisateur.
     */
    public function show(
        Request $request
    ): string {
        $id =
            $this->routeId(
                $request
            );

        $data =
            $this->users
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
                        'Utilisateur introuvable',
                ]
            );
        }

        return $this->view->render(
            'identity.users.show',
            [
                'pageTitle' =>
                    'Détail utilisateur',

                'user' =>
                    $data['user'],

                'platformRoles' =>
                    $data['platform_roles'],

                'availablePlatformRoles' =>
                    $data['available_platform_roles'],

                'platformPermissions' =>
                    $data['platform_permissions'],

                'memberships' =>
                    $data['memberships'],

                'loginHistory' =>
                    $data['login_history'],

                    'pageScripts' => [
                    '/assets/js/medtrack-user-management.js',
                ],
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | User status
    |--------------------------------------------------------------------------
    */

    /**
     * Modifie le statut d'un utilisateur.
     */
    public function updateStatus(
        Request $request
    ): never {
        $userId =
            $this->routeId(
                $request
            );

        try {
            $this->users
                ->changeUserStatus(
                    $userId,
                    (string) $request->input(
                        'status',
                        ''
                    )
                );
        } catch (RuntimeException $exception) {
            Response::json(
                [
                    'status' =>
                        'error',

                    'code' =>
                        'USER_STATUS_UPDATE_FAILED',

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
                        'USER_STATUS_UPDATE_ERROR',

                    'message' =>
                        'Impossible de modifier le statut '
                        . 'de cet utilisateur pour le moment.',
                ],
                500
            );
        }

        Response::json(
            [
                'status' =>
                    'success',

                'message' =>
                    'Le statut de l’utilisateur a été mis à jour.',

                'redirect' =>
                    '/users/' . $userId,
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Force password change
    |--------------------------------------------------------------------------
    */

    /**
     * Active ou désactive l'obligation
     * de changement de mot de passe.
     */
    public function updatePasswordChangeRequirement(
        Request $request
    ): never {
        $userId =
            $this->routeId(
                $request
            );

        $required =
            filter_var(
                $request->input(
                    'required',
                    true
                ),
                FILTER_VALIDATE_BOOL
            );

        try {
            $this->users
                ->forcePasswordChange(
                    $userId,
                    $required
                );
        } catch (RuntimeException $exception) {
            Response::json(
                [
                    'status' =>
                        'error',

                    'code' =>
                        'PASSWORD_CHANGE_REQUIREMENT_FAILED',

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
                        'PASSWORD_CHANGE_REQUIREMENT_ERROR',

                    'message' =>
                        'Impossible de modifier cette exigence '
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
                    $required
                        ? 'Le changement de mot de passe sera exigé.'
                        : 'L’obligation de changement de mot de passe a été retirée.',

                'redirect' =>
                    '/users/' . $userId,
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Platform roles
    |--------------------------------------------------------------------------
    */

    /**
     * Attribue un rôle plateforme
     * à un utilisateur.
     */
    public function assignPlatformRole(
        Request $request
    ): never {
        $userId =
            $this->routeId(
                $request
            );

        $roleId =
            (int) $request->input(
                'role_id',
                0
            );

        try {
            $this->users
                ->assignPlatformRole(
                    $userId,
                    $roleId
                );
        } catch (RuntimeException $exception) {
            Response::json(
                [
                    'status' =>
                        'error',

                    'code' =>
                        'PLATFORM_ROLE_ASSIGNMENT_FAILED',

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
                        'PLATFORM_ROLE_ASSIGNMENT_ERROR',

                    'message' =>
                        'Impossible d’attribuer ce rôle '
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
                    'Le rôle plateforme a été attribué.',

                'redirect' =>
                    '/users/' . $userId,
            ]
        );
    }

    /**
     * Retire un rôle plateforme
     * à un utilisateur.
     */
    public function removePlatformRole(
        Request $request
    ): never {
        $userId =
            $this->routeId(
                $request
            );

        $roleId =
            $this->routeRoleId(
                $request
            );

        try {
            $this->users
                ->removePlatformRole(
                    $userId,
                    $roleId
                );
        } catch (RuntimeException $exception) {
            Response::json(
                [
                    'status' =>
                        'error',

                    'code' =>
                        'PLATFORM_ROLE_REMOVAL_FAILED',

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
                        'PLATFORM_ROLE_REMOVAL_ERROR',

                    'message' =>
                        'Impossible de retirer ce rôle '
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
                    'Le rôle plateforme a été retiré.',

                'redirect' =>
                    '/users/' . $userId,
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Membership roles
    |--------------------------------------------------------------------------
    */

    /**
     * Attribue un rôle institutionnel
     * à un membership.
     */
    public function assignMembershipRole(
        Request $request
    ): never {
        $membershipId =
            $this->routeMembershipId(
                $request
            );

        $roleId =
            (int) $request->input(
                'role_id',
                0
            );

        try {
            $this->users
                ->assignMembershipRole(
                    $membershipId,
                    $roleId
                );
        } catch (RuntimeException $exception) {
            Response::json(
                [
                    'status' =>
                        'error',

                    'code' =>
                        'MEMBERSHIP_ROLE_ASSIGNMENT_FAILED',

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
                        'MEMBERSHIP_ROLE_ASSIGNMENT_ERROR',

                    'message' =>
                        'Impossible d’attribuer ce rôle '
                        . 'au membership pour le moment.',
                ],
                500
            );
        }

        Response::json(
            [
                'status' =>
                    'success',

                'message' =>
                    'Le rôle institutionnel a été attribué.',
            ]
        );
    }

    /**
     * Retire un rôle institutionnel
     * d'un membership.
     */
    public function removeMembershipRole(
        Request $request
    ): never {
        $membershipId =
            $this->routeMembershipId(
                $request
            );

        $roleId =
            $this->routeRoleId(
                $request
            );

        try {
            $this->users
                ->removeMembershipRole(
                    $membershipId,
                    $roleId
                );
        } catch (RuntimeException $exception) {
            Response::json(
                [
                    'status' =>
                        'error',

                    'code' =>
                        'MEMBERSHIP_ROLE_REMOVAL_FAILED',

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
                        'MEMBERSHIP_ROLE_REMOVAL_ERROR',

                    'message' =>
                        'Impossible de retirer ce rôle '
                        . 'du membership pour le moment.',
                ],
                500
            );
        }

        Response::json(
            [
                'status' =>
                    'success',

                'message' =>
                    'Le rôle institutionnel a été retiré.',
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Route parameters
    |--------------------------------------------------------------------------
    */

    /**
     * Extrait l'identifiant utilisateur.
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
                'Identifiant utilisateur invalide.'
            );
        }

        return $id;
    }

    /**
     * Extrait un identifiant de rôle.
     */
    private function routeRoleId(
        Request $request
    ): int {
        $roleId =
            (int) $request->attribute(
                'roleId',
                0
            );

        if ($roleId <= 0) {
            throw new RuntimeException(
                'Identifiant de rôle invalide.'
            );
        }

        return $roleId;
    }

    /**
     * Extrait un identifiant de membership.
     */
    private function routeMembershipId(
        Request $request
    ): int {
        $membershipId =
            (int) $request->attribute(
                'membershipId',
                0
            );

        if ($membershipId <= 0) {
            throw new RuntimeException(
                'Identifiant de membership invalide.'
            );
        }

        return $membershipId;
    }
}