<?php

declare(strict_types=1);

namespace MedTrack\Modules\Identity\Services;

use InvalidArgumentException;
use MedTrack\Modules\Identity\Repositories\RoleManagementRepository;

final class RoleManagementService
{
    public function __construct(
        private readonly RoleManagementRepository $roles
    ) {
    }

    /**
     * Retourne tous les rôles.
     */
    public function allRoles(): array
    {
        return $this->roles
            ->allRoles();
    }

    /**
     * Retourne toutes les permissions.
     */
    public function allPermissions(): array
    {
        return $this->roles
            ->allPermissions();
    }

    /**
     * Retourne un rôle par identifiant.
     */
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

    /**
     * Métriques RBAC globales.
     */
    public function metrics(): array
    {
        return $this->roles
            ->metrics();
    }

    /**
     * Construit la vue globale RBAC
     * pour le Super Admin.
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

    /**
     * Construit la fiche détaillée d'un rôle.
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

        return [
            'role' =>
                $role,

            'permissions' =>
                $this->roles
                    ->permissionsForRole(
                        $roleId
                    ),

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
        ];
    }

    /**
     * Vérifie qu'un identifiant est valide.
     */
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