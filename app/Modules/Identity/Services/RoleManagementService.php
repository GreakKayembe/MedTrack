<?php

declare(strict_types=1);

namespace MedTrack\Modules\Identity\Services;

use InvalidArgumentException;
use MedTrack\Core\Auth\Session;
use MedTrack\Modules\Audit\Services\AuditRecorder;
use MedTrack\Modules\Identity\Repositories\RoleManagementRepository;
use RuntimeException;

final class RoleManagementService
{
    private const ORGANIZATION_TYPES = [
        'UNIVERSITY',
        'HOSPITAL',
        'PROFESSIONAL_ORDER',
        'MINISTRY',
    ];

    public function __construct(
        private readonly RoleManagementRepository $roles,
        private readonly AuditRecorder $audit,
        private readonly Session $session
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Read
    |--------------------------------------------------------------------------
    */

    public function allRoles(): array
    {
        return $this->roles
            ->allRoles();
    }

    public function allPermissions(): array
    {
        return $this->roles
            ->allPermissions();
    }

    public function findRoleById(
        int $id
    ): ?array {
        if ($id <= 0) {
            return null;
        }

        return $this->roles
            ->findRoleById(
                $id
            );
    }

    public function metrics(): array
    {
        return $this->roles
            ->metrics();
    }

    /*
    |--------------------------------------------------------------------------
    | Platform overview
    |--------------------------------------------------------------------------
    */

    public function platformOverview(): array
    {
        return [
            'metrics' =>
                $this->metrics(),

            'roles' =>
                $this->allRoles(),

            'permissions' =>
                $this->allPermissions(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Role detail
    |--------------------------------------------------------------------------
    */

    public function platformShow(
        int $roleId
    ): ?array {
        $this->assertId(
            $roleId,
            'Identifiant de rôle invalide.'
        );

        $role =
            $this->findRoleById(
                $roleId
            );

        if ($role === null) {
            return null;
        }

        $assignedPermissions =
            $this->roles
                ->permissionsForRole(
                    $roleId
                );

        $allPermissions =
            $this->roles
                ->allPermissions();

        $assignedPermissionIds = [];

        foreach (
            $assignedPermissions
            as $permission
        ) {
            $permissionId =
                (int) (
                    $permission['id']
                    ?? 0
                );

            if ($permissionId > 0) {
                $assignedPermissionIds[] =
                    $permissionId;
            }
        }

        $availablePermissions =
            array_values(
                array_filter(
                    $allPermissions,
                    static function (
                        array $permission
                    ) use (
                        $assignedPermissionIds
                    ): bool {
                        $permissionId =
                            (int) (
                                $permission['id']
                                ?? 0
                            );

                        return $permissionId > 0
                            && !in_array(
                                $permissionId,
                                $assignedPermissionIds,
                                true
                            );
                    }
                )
            );

        return [
            'role' =>
                $role,

            'permissions' =>
                $assignedPermissions,

            'available_permissions' =>
                $availablePermissions,

            'platform_users' =>
                $this->roles
                    ->platformUsersForRole(
                        $roleId
                    ),

            'memberships' =>
                $this->roles
                    ->membershipsForRole(
                        $roleId
                    ),

            'usage' =>
                $this->roles
                    ->roleUsage(
                        $roleId
                    ),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Create role
    |--------------------------------------------------------------------------
    */

    public function createRole(
        string $code,
        string $name,
        ?string $organizationType
    ): int {
        $code =
            strtoupper(
                trim(
                    $code
                )
            );

        $name =
            trim(
                $name
            );

        $organizationType =
            $organizationType !== null
                ? strtoupper(
                    trim(
                        $organizationType
                    )
                )
                : null;

        if ($code === '') {
            throw new RuntimeException(
                'Le code du rôle est obligatoire.'
            );
        }

        if ($name === '') {
            throw new RuntimeException(
                'Le nom du rôle est obligatoire.'
            );
        }

        if (
            !preg_match(
                '/^[A-Z0-9_]+$/',
                $code
            )
        ) {
            throw new RuntimeException(
                'Le code du rôle ne peut contenir '
                . 'que des lettres majuscules, '
                . 'des chiffres et des underscores.'
            );
        }

        if (
            $organizationType !== null
            && $organizationType !== ''
            && !in_array(
                $organizationType,
                self::ORGANIZATION_TYPES,
                true
            )
        ) {
            throw new RuntimeException(
                'Type d’organisation invalide.'
            );
        }

        if ($organizationType === '') {
            $organizationType = null;
        }

        if (
            $this->roles
                ->roleCodeExists(
                    $code
                )
        ) {
            throw new RuntimeException(
                'Un rôle avec ce code existe déjà.'
            );
        }

        $roleId =
            $this->roles
                ->createRole(
                    $code,
                    $name,
                    $organizationType
                );

        $this->audit->record(
            action:
                'ROLE_CREATED',

            entityType:
                'role',

            entityId:
                $roleId,

            actorUserId:
                $this->authenticatedUserId(),

            newValues: [
                'id' =>
                    $roleId,

                'code' =>
                    $code,

                'name' =>
                    $name,

                'organization_type' =>
                    $organizationType,

                'is_system' =>
                    0,
            ]
        );

        return $roleId;
    }

    /*
    |--------------------------------------------------------------------------
    | Rename role
    |--------------------------------------------------------------------------
    */

    public function renameRole(
        int $roleId,
        string $name
    ): void {
        $this->assertId(
            $roleId,
            'Identifiant de rôle invalide.'
        );

        $name =
            trim(
                $name
            );

        if ($name === '') {
            throw new RuntimeException(
                'Le nom du rôle est obligatoire.'
            );
        }

        $role =
            $this->requireRole(
                $roleId
            );

        if (
            (int) (
                $role['is_system']
                ?? 0
            ) === 1
        ) {
            throw new RuntimeException(
                'Un rôle système ne peut pas être renommé.'
            );
        }

        $oldName =
            (string) (
                $role['name']
                ?? ''
            );

        /*
         * Évite une écriture et un audit inutiles
         * lorsque le nom ne change pas.
         */
        if ($oldName === $name) {
            return;
        }

        $this->roles
            ->updateRoleName(
                $roleId,
                $name
            );

        $this->audit->record(
            action:
                'ROLE_RENAMED',

            entityType:
                'role',

            entityId:
                $roleId,

            actorUserId:
                $this->authenticatedUserId(),

            oldValues: [
                'name' =>
                    $oldName,
            ],

            newValues: [
                'name' =>
                    $name,
            ],

            metadata: [
                'role_code' =>
                    $role['code']
                    ?? null,
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Permissions
    |--------------------------------------------------------------------------
    */

    public function assignPermission(
        int $roleId,
        int $permissionId
    ): void {
        $this->assertId(
            $roleId,
            'Identifiant de rôle invalide.'
        );

        $this->assertId(
            $permissionId,
            'Identifiant de permission invalide.'
        );

        $role =
            $this->requireRole(
                $roleId
            );

        if (
            (int) (
                $role['is_system']
                ?? 0
            ) === 1
        ) {
            throw new RuntimeException(
                'Les permissions d’un rôle système '
                . 'ne peuvent pas être modifiées depuis cette interface.'
            );
        }

        $permission =
            $this->roles
                ->findPermissionById(
                    $permissionId
                );

        if ($permission === null) {
            throw new RuntimeException(
                'Permission introuvable.'
            );
        }

        if (
            $this->roles
                ->roleHasPermission(
                    $roleId,
                    $permissionId
                )
        ) {
            throw new RuntimeException(
                'Cette permission est déjà attribuée à ce rôle.'
            );
        }

        $this->roles
            ->assignPermission(
                $roleId,
                $permissionId
            );

        $this->audit->record(
            action:
                'ROLE_PERMISSION_ASSIGNED',

            entityType:
                'role',

            entityId:
                $roleId,

            actorUserId:
                $this->authenticatedUserId(),

            newValues: [
                'permission_id' =>
                    $permissionId,

                'permission_code' =>
                    $permission['code']
                    ?? null,

                'permission_name' =>
                    $permission['name']
                    ?? null,
            ],

            metadata: [
                'role_code' =>
                    $role['code']
                    ?? null,

                'role_name' =>
                    $role['name']
                    ?? null,
            ]
        );
    }

    public function removePermission(
        int $roleId,
        int $permissionId
    ): void {
        $this->assertId(
            $roleId,
            'Identifiant de rôle invalide.'
        );

        $this->assertId(
            $permissionId,
            'Identifiant de permission invalide.'
        );

        $role =
            $this->requireRole(
                $roleId
            );

        if (
            (int) (
                $role['is_system']
                ?? 0
            ) === 1
        ) {
            throw new RuntimeException(
                'Les permissions d’un rôle système '
                . 'ne peuvent pas être modifiées depuis cette interface.'
            );
        }

        $permission =
            $this->roles
                ->findPermissionById(
                    $permissionId
                );

        if ($permission === null) {
            throw new RuntimeException(
                'Permission introuvable.'
            );
        }

        if (
            !$this->roles
                ->roleHasPermission(
                    $roleId,
                    $permissionId
                )
        ) {
            throw new RuntimeException(
                'Cette permission n’est pas attribuée à ce rôle.'
            );
        }

        $this->roles
            ->removePermission(
                $roleId,
                $permissionId
            );

        $this->audit->record(
            action:
                'ROLE_PERMISSION_REMOVED',

            entityType:
                'role',

            entityId:
                $roleId,

            actorUserId:
                $this->authenticatedUserId(),

            oldValues: [
                'permission_id' =>
                    $permissionId,

                'permission_code' =>
                    $permission['code']
                    ?? null,

                'permission_name' =>
                    $permission['name']
                    ?? null,
            ],

            metadata: [
                'role_code' =>
                    $role['code']
                    ?? null,

                'role_name' =>
                    $role['name']
                    ?? null,
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Delete role
    |--------------------------------------------------------------------------
    */

    public function deleteRole(
        int $roleId
    ): void {
        $this->assertId(
            $roleId,
            'Identifiant de rôle invalide.'
        );

        $role =
            $this->requireRole(
                $roleId
            );

        if (
            (int) (
                $role['is_system']
                ?? 0
            ) === 1
        ) {
            throw new RuntimeException(
                'Un rôle système ne peut pas être supprimé.'
            );
        }

        $usage =
            $this->roles
                ->roleUsage(
                    $roleId
                );

        $platformUsers =
            (int) (
                $usage['platform_users']
                ?? 0
            );

        $memberships =
            (int) (
                $usage['memberships']
                ?? 0
            );

        if (
            $platformUsers > 0
            || $memberships > 0
        ) {
            throw new RuntimeException(
                'Impossible de supprimer ce rôle '
                . 'car il est encore attribué.'
            );
        }

        /*
         * On conserve l'état avant suppression,
         * puisque le rôle n'existera plus ensuite.
         */
        $oldValues = [
            'id' =>
                $roleId,

            'code' =>
                $role['code']
                ?? null,

            'name' =>
                $role['name']
                ?? null,

            'organization_type' =>
                $role['organization_type']
                ?? null,

            'is_system' =>
                (int) (
                    $role['is_system']
                    ?? 0
                ),
        ];

        $this->roles
            ->deleteRole(
                $roleId
            );

        $this->audit->record(
            action:
                'ROLE_DELETED',

            entityType:
                'role',

            entityId:
                $roleId,

            actorUserId:
                $this->authenticatedUserId(),

            oldValues:
                $oldValues,

            metadata: [
                'platform_users' =>
                    $platformUsers,

                'memberships' =>
                    $memberships,
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Authentication context
    |--------------------------------------------------------------------------
    */

    private function authenticatedUserId(): ?int
    {
        $userId =
            (int) $this->session
                ->get(
                    'auth_user_id',
                    0
                );

        return $userId > 0
            ? $userId
            : null;
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    private function requireRole(
        int $roleId
    ): array {
        $role =
            $this->roles
                ->findRoleById(
                    $roleId
                );

        if ($role === null) {
            throw new RuntimeException(
                'Rôle introuvable.'
            );
        }

        return $role;
    }

    private function assertId(
        int $id,
        string $message
    ): void {
        if ($id <= 0) {
            throw new InvalidArgumentException(
                $message
            );
        }
    }
}