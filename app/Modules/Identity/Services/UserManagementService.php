<?php

declare(strict_types=1);

namespace MedTrack\Modules\Identity\Services;

use InvalidArgumentException;
use MedTrack\Core\Auth\Session;
use MedTrack\Modules\Audit\Services\AuditRecorder;
use MedTrack\Modules\Identity\Repositories\UserManagementRepository;
use RuntimeException;

final class UserManagementService
{
    private const USER_STATUSES = [
        'PENDING',
        'ACTIVE',
        'SUSPENDED',
        'DISABLED',
    ];

    private const SUPER_ADMIN_ROLE_CODE =
        'SUPER_ADMIN_MEDTRACK';

    public function __construct(
        private readonly UserManagementRepository $users,
        private readonly AuditRecorder $audit,
        private readonly Session $session
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Read
    |--------------------------------------------------------------------------
    */

    public function all(): array
    {
        return $this->users->all();
    }

    public function findById(
        int $id
    ): ?array {
        if ($id <= 0) {
            return null;
        }

        return $this->users->findById(
            $id
        );
    }

    public function metrics(): array
    {
        return $this->users->metrics();
    }

    public function accessMetrics(): array
    {
        return $this->users
            ->accessMetrics();
    }

    public function loginMetrics(): array
    {
        return $this->users
            ->loginMetrics();
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

            'access_metrics' =>
                $this->accessMetrics(),

            'login_metrics' =>
                $this->loginMetrics(),

            'users' =>
                $this->all(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Platform user detail
    |--------------------------------------------------------------------------
    */

    public function platformShow(
        int $userId
    ): ?array {
        $this->assertId(
            $userId,
            'Identifiant utilisateur invalide.'
        );

        $user =
            $this->findById(
                $userId
            );

        if ($user === null) {
            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | Platform access
        |--------------------------------------------------------------------------
        */

        $platformRoles =
            $this->users
                ->platformRoles(
                    $userId
                );

        $availablePlatformRoles =
            $this->users
                ->availablePlatformRoles();

        $platformPermissions =
            $this->users
                ->platformPermissions(
                    $userId
                );

        /*
        |--------------------------------------------------------------------------
        | Memberships
        |--------------------------------------------------------------------------
        */

        $memberships =
            $this->users
                ->memberships(
                    $userId
                );

        foreach (
            $memberships
            as &$membership
        ) {
            $membershipId =
                (int) (
                    $membership['id']
                    ?? 0
                );

            $organizationType =
                strtoupper(
                    trim(
                        (string) (
                            $membership[
                                'organization_type'
                            ]
                            ?? ''
                        )
                    )
                );

            if ($membershipId <= 0) {
                $membership['roles'] = [];
                $membership['permissions'] = [];
                $membership['available_roles'] = [];

                continue;
            }

            $membership['roles'] =
                $this->users
                    ->membershipRoles(
                        $membershipId
                    );

            $membership['permissions'] =
                $this->users
                    ->membershipPermissions(
                        $membershipId
                    );

            $membership['available_roles'] =
                $organizationType !== ''
                    ? $this->users
                        ->availableRolesForOrganizationType(
                            $organizationType
                        )
                    : [];
        }

        unset($membership);

        return [
            'user' =>
                $user,

            'platform_roles' =>
                $platformRoles,

            'available_platform_roles' =>
                $availablePlatformRoles,

            'platform_permissions' =>
                $platformPermissions,

            'memberships' =>
                $memberships,

            'login_history' =>
                $this->users
                    ->loginHistory(
                        $userId,
                        20
                    ),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | User status
    |--------------------------------------------------------------------------
    */

    public function changeUserStatus(
        int $userId,
        string $status
    ): void {
        $user =
            $this->assertUserExists(
                $userId
            );

        $status =
            strtoupper(
                trim(
                    $status
                )
            );

        if (
            !in_array(
                $status,
                self::USER_STATUSES,
                true
            )
        ) {
            throw new RuntimeException(
                'Statut utilisateur invalide.'
            );
        }

        $oldStatus =
            strtoupper(
                trim(
                    (string) (
                        $user['status']
                        ?? ''
                    )
                )
            );

        if ($oldStatus === $status) {
            return;
        }

        /*
         * Un compte détenant le dernier rôle
         * SUPER_ADMIN_MEDTRACK ne doit pas pouvoir
         * être suspendu ou désactivé.
         */
        if (
            in_array(
                $status,
                [
                    'SUSPENDED',
                    'DISABLED',
                ],
                true
            )
            && $this->isLastSuperAdmin(
                $userId
            )
        ) {
            throw new RuntimeException(
                'Impossible de suspendre ou désactiver '
                . 'le dernier Super Administrateur MedTrack.'
            );
        }

        $this->users
            ->updateStatus(
                $userId,
                $status
            );

        $this->audit
            ->record(
                action:
                    'USER_STATUS_CHANGED',

                entityType:
                    'user',

                entityId:
                    $userId,

                actorUserId:
                    $this->authenticatedUserId(),

                oldValues: [
                    'status' =>
                        $oldStatus,
                ],

                newValues: [
                    'status' =>
                        $status,
                ],

                metadata: [
                    'email' =>
                        $user['email']
                        ?? null,

                    'phone' =>
                        $user['phone']
                        ?? null,
                ]
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Password change
    |--------------------------------------------------------------------------
    */

    public function forcePasswordChange(
        int $userId,
        bool $required = true
    ): void {
        $user =
            $this->assertUserExists(
                $userId
            );

        $oldRequired =
            (bool) (
                $user['must_change_password']
                ?? false
            );

        if ($oldRequired === $required) {
            return;
        }

        $this->users
            ->setMustChangePassword(
                $userId,
                $required
            );

        $this->audit
            ->record(
                action:
                    'PASSWORD_CHANGE_REQUIREMENT_CHANGED',

                entityType:
                    'user',

                entityId:
                    $userId,

                actorUserId:
                    $this->authenticatedUserId(),

                oldValues: [
                    'must_change_password' =>
                        $oldRequired,
                ],

                newValues: [
                    'must_change_password' =>
                        $required,
                ],

                metadata: [
                    'email' =>
                        $user['email']
                        ?? null,

                    'phone' =>
                        $user['phone']
                        ?? null,
                ]
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Platform roles
    |--------------------------------------------------------------------------
    */

    public function assignPlatformRole(
        int $userId,
        int $roleId
    ): void {
        $user =
            $this->assertUserExists(
                $userId
            );

        $this->assertId(
            $roleId,
            'Identifiant de rôle invalide.'
        );

        $role =
            $this->users
                ->findRoleById(
                    $roleId
                );

        if ($role === null) {
            throw new RuntimeException(
                'Rôle introuvable.'
            );
        }

        /*
         * Un rôle plateforme doit avoir
         * organization_type = NULL.
         */
        if (
            $role['organization_type']
            ?? null
        ) {
            throw new RuntimeException(
                'Ce rôle est réservé à un contexte institutionnel.'
            );
        }

        if (
            $this->users
                ->hasPlatformRole(
                    $userId,
                    $roleId
                )
        ) {
            throw new RuntimeException(
                'Cet utilisateur possède déjà ce rôle.'
            );
        }

        $this->users
            ->assignPlatformRole(
                $userId,
                $roleId
            );

        $this->audit
            ->record(
                action:
                    'PLATFORM_ROLE_ASSIGNED',

                entityType:
                    'user',

                entityId:
                    $userId,

                actorUserId:
                    $this->authenticatedUserId(),

                newValues: [
                    'role_id' =>
                        $roleId,

                    'role_code' =>
                        $role['code']
                        ?? null,

                    'role_name' =>
                        $role['name']
                        ?? null,
                ],

                metadata: [
                    'target_user_email' =>
                        $user['email']
                        ?? null,

                    'target_user_phone' =>
                        $user['phone']
                        ?? null,
                ]
            );
    }

    public function removePlatformRole(
        int $userId,
        int $roleId
    ): void {
        $user =
            $this->assertUserExists(
                $userId
            );

        $this->assertId(
            $roleId,
            'Identifiant de rôle invalide.'
        );

        $role =
            $this->users
                ->findRoleById(
                    $roleId
                );

        if ($role === null) {
            throw new RuntimeException(
                'Rôle introuvable.'
            );
        }

        if (
            !$this->users
                ->hasPlatformRole(
                    $userId,
                    $roleId
                )
        ) {
            throw new RuntimeException(
                'Ce rôle n’est pas attribué à cet utilisateur.'
            );
        }

        /*
         * Protection critique :
         * le dernier SUPER_ADMIN_MEDTRACK
         * ne peut pas perdre ce rôle.
         */
        if (
            ($role['code'] ?? null)
            === self::SUPER_ADMIN_ROLE_CODE
            && $this->users
                ->countUsersWithPlatformRole(
                    $roleId
                ) <= 1
        ) {
            throw new RuntimeException(
                'Impossible de retirer le dernier rôle '
                . 'SUPER_ADMIN_MEDTRACK de la plateforme.'
            );
        }

        $this->users
            ->removePlatformRole(
                $userId,
                $roleId
            );

        $this->audit
            ->record(
                action:
                    'PLATFORM_ROLE_REMOVED',

                entityType:
                    'user',

                entityId:
                    $userId,

                actorUserId:
                    $this->authenticatedUserId(),

                oldValues: [
                    'role_id' =>
                        $roleId,

                    'role_code' =>
                        $role['code']
                        ?? null,

                    'role_name' =>
                        $role['name']
                        ?? null,
                ],

                metadata: [
                    'target_user_email' =>
                        $user['email']
                        ?? null,

                    'target_user_phone' =>
                        $user['phone']
                        ?? null,
                ]
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Membership roles
    |--------------------------------------------------------------------------
    */

    public function assignMembershipRole(
        int $membershipId,
        int $roleId
    ): void {
        $this->assertId(
            $membershipId,
            'Identifiant de membership invalide.'
        );

        $this->assertId(
            $roleId,
            'Identifiant de rôle invalide.'
        );

        $membership =
            $this->users
                ->findMembershipById(
                    $membershipId
                );

        if ($membership === null) {
            throw new RuntimeException(
                'Membership introuvable.'
            );
        }

        $role =
            $this->users
                ->findRoleById(
                    $roleId
                );

        if ($role === null) {
            throw new RuntimeException(
                'Rôle introuvable.'
            );
        }

        $organizationType =
            strtoupper(
                trim(
                    (string) (
                        $membership[
                            'organization_type'
                        ]
                        ?? ''
                    )
                )
            );

        $roleOrganizationType =
            strtoupper(
                trim(
                    (string) (
                        $role[
                            'organization_type'
                        ]
                        ?? ''
                    )
                )
            );

        if (
            $organizationType === ''
            || $roleOrganizationType === ''
            || $organizationType
                !== $roleOrganizationType
        ) {
            throw new RuntimeException(
                'Ce rôle n’est pas compatible '
                . 'avec le type de cette organisation.'
            );
        }

        if (
            $this->users
                ->membershipHasRole(
                    $membershipId,
                    $roleId
                )
        ) {
            throw new RuntimeException(
                'Ce membership possède déjà ce rôle.'
            );
        }

        $this->users
            ->assignMembershipRole(
                $membershipId,
                $roleId
            );

        $organizationId =
            isset(
                $membership[
                    'organization_id'
                ]
            )
                ? (int) $membership[
                    'organization_id'
                ]
                : null;

        $this->audit
            ->record(
                action:
                    'MEMBERSHIP_ROLE_ASSIGNED',

                entityType:
                    'membership',

                entityId:
                    $membershipId,

                actorUserId:
                    $this->authenticatedUserId(),

                organizationId:
                    $organizationId,

                actorMembershipId:
                    null,

                newValues: [
                    'role_id' =>
                        $roleId,

                    'role_code' =>
                        $role['code']
                        ?? null,

                    'role_name' =>
                        $role['name']
                        ?? null,
                ],

                metadata: [
                    'membership_user_id' =>
                        $membership['user_id']
                        ?? null,

                    'organization_type' =>
                        $organizationType,

                    'organization_name' =>
                        $membership[
                            'organization_name'
                        ]
                        ?? null,

                    'organization_code' =>
                        $membership[
                            'organization_code'
                        ]
                        ?? null,
                ]
            );
    }

    public function removeMembershipRole(
        int $membershipId,
        int $roleId
    ): void {
        $this->assertId(
            $membershipId,
            'Identifiant de membership invalide.'
        );

        $this->assertId(
            $roleId,
            'Identifiant de rôle invalide.'
        );

        $membership =
            $this->users
                ->findMembershipById(
                    $membershipId
                );

        if ($membership === null) {
            throw new RuntimeException(
                'Membership introuvable.'
            );
        }

        $role =
            $this->users
                ->findRoleById(
                    $roleId
                );

        if ($role === null) {
            throw new RuntimeException(
                'Rôle introuvable.'
            );
        }

        if (
            !$this->users
                ->membershipHasRole(
                    $membershipId,
                    $roleId
                )
        ) {
            throw new RuntimeException(
                'Ce rôle n’est pas attribué à ce membership.'
            );
        }

        $this->users
            ->removeMembershipRole(
                $membershipId,
                $roleId
            );

        $organizationId =
            isset(
                $membership[
                    'organization_id'
                ]
            )
                ? (int) $membership[
                    'organization_id'
                ]
                : null;

        $this->audit
            ->record(
                action:
                    'MEMBERSHIP_ROLE_REMOVED',

                entityType:
                    'membership',

                entityId:
                    $membershipId,

                actorUserId:
                    $this->authenticatedUserId(),

                organizationId:
                    $organizationId,

                actorMembershipId:
                    null,

                oldValues: [
                    'role_id' =>
                        $roleId,

                    'role_code' =>
                        $role['code']
                        ?? null,

                    'role_name' =>
                        $role['name']
                        ?? null,
                ],

                metadata: [
                    'membership_user_id' =>
                        $membership['user_id']
                        ?? null,

                    'organization_type' =>
                        $membership[
                            'organization_type'
                        ]
                        ?? null,

                    'organization_name' =>
                        $membership[
                            'organization_name'
                        ]
                        ?? null,

                    'organization_code' =>
                        $membership[
                            'organization_code'
                        ]
                        ?? null,
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
    | Security helpers
    |--------------------------------------------------------------------------
    */

    private function isLastSuperAdmin(
        int $userId
    ): bool {
        $role =
            $this->users
                ->findPlatformRoleByCode(
                    self::SUPER_ADMIN_ROLE_CODE
                );

        if ($role === null) {
            return false;
        }

        $roleId =
            (int) (
                $role['id']
                ?? 0
            );

        if ($roleId <= 0) {
            return false;
        }

        if (
            !$this->users
                ->hasPlatformRole(
                    $userId,
                    $roleId
                )
        ) {
            return false;
        }

        return $this->users
            ->countUsersWithPlatformRole(
                $roleId
            ) <= 1;
    }

    private function assertUserExists(
        int $userId
    ): array {
        $this->assertId(
            $userId,
            'Identifiant utilisateur invalide.'
        );

        $user =
            $this->users
                ->findById(
                    $userId
                );

        if ($user === null) {
            throw new RuntimeException(
                'Utilisateur introuvable.'
            );
        }

        return $user;
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