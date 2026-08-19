<?php

declare(strict_types=1);

namespace MedTrack\Modules\Identity\Services;

use MedTrack\Core\Context\AccessContext;
use MedTrack\Modules\Identity\Repositories\OrganizationMembershipRepository;
use MedTrack\Modules\Identity\Repositories\PlatformAccessRepository;
use RuntimeException;

final class AuthorizationService
{
    public function __construct(
        private readonly PlatformAccessRepository $platformAccess,
        private readonly OrganizationMembershipRepository $memberships
    ) {
    }

    /**
     * Vérifie si une permission est accordée
     * dans le contexte d'accès courant.
     */
    public function can(
        AccessContext $context,
        string $permissionCode
    ): bool {
        $permissionCode =
            trim($permissionCode);

        if ($permissionCode === '') {
            return false;
        }

        /*
        |--------------------------------------------------------------------------
        | Platform
        |--------------------------------------------------------------------------
        */

        if ($context->isPlatform()) {
            /*
             * Le super administrateur possède un accès
             * global explicite.
             *
             * On ne se base jamais sur l'adresse e-mail,
             * l'ID utilisateur ou l'absence de membership.
             */
            if (
                $this->platformAccess->isSuperAdmin(
                    $context->userId()
                )
            ) {
                return true;
            }

            return $this->platformAccess
                ->userHasPermission(
                    $context->userId(),
                    $permissionCode
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Organization
        |--------------------------------------------------------------------------
        */

        if ($context->isOrganization()) {
            /*
             * Revalidation du membership.
             *
             * Même si AccessContextResolver l'a déjà vérifié,
             * cette vérification empêche un contexte devenu
             * obsolète d'accorder des droits.
             */
            $membership =
                $this->memberships
                    ->findActiveMembership(
                        $context->membershipId(),
                        $context->userId()
                    );

            if ($membership === null) {
                return false;
            }

            if (
                (int) $membership['organization_id']
                !== $context->organizationId()
            ) {
                return false;
            }

            if (
                strtoupper(
                    (string) $membership['organization_type']
                )
                !== strtoupper(
                    $context->organizationType()
                )
            ) {
                return false;
            }

            return $this->memberships
                ->membershipHasPermission(
                    $context->membershipId(),
                    $permissionCode
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Student
        |--------------------------------------------------------------------------
        |
        | Les permissions organisationnelles ne sont pas
        | automatiquement accordées à un étudiant.
        |
        | L'espace étudiant sera protégé par des règles
        | spécifiques à la propriété des ressources.
        |--------------------------------------------------------------------------
        */

        if ($context->isStudent()) {
            return false;
        }

        return false;
    }


    /**
     * Vérifie plusieurs permissions.
     *
     * Retourne true si AU MOINS UNE permission
     * est accordée.
     */
    public function canAny(
        AccessContext $context,
        array $permissionCodes
    ): bool {
        foreach ($permissionCodes as $permissionCode) {
            if (
                is_string($permissionCode)
                && $this->can(
                    $context,
                    $permissionCode
                )
            ) {
                return true;
            }
        }

        return false;
    }


    /**
     * Vérifie plusieurs permissions.
     *
     * Retourne true uniquement si TOUTES
     * les permissions sont accordées.
     */
    public function canAll(
        AccessContext $context,
        array $permissionCodes
    ): bool {
        if ($permissionCodes === []) {
            return false;
        }

        foreach ($permissionCodes as $permissionCode) {
            if (
                !is_string($permissionCode)
                || !$this->can(
                    $context,
                    $permissionCode
                )
            ) {
                return false;
            }
        }

        return true;
    }


    /**
     * Exige une permission.
     *
     * Cette méthode sera utile dans les services métier
     * lorsque la sécurité doit être appliquée en profondeur,
     * et pas uniquement au niveau HTTP.
     */
    public function authorize(
        AccessContext $context,
        string $permissionCode
    ): void {
        if (
            !$this->can(
                $context,
                $permissionCode
            )
        ) {
            throw new RuntimeException(
                'Vous n’êtes pas autorisé à effectuer '
                . 'cette action.'
            );
        }
    }


    /**
     * Indique si le contexte correspond
     * à l'administration centrale MedTrack.
     */
    public function isPlatform(
        AccessContext $context
    ): bool {
        return $context->isPlatform();
    }


    /**
     * Indique si le contexte appartient
     * à une organisation d'un type précis.
     */
    public function isOrganizationType(
        AccessContext $context,
        string $organizationType
    ): bool {
        if (!$context->isOrganization()) {
            return false;
        }

        return strtoupper(
            trim(
                $context->organizationType()
            )
        ) === strtoupper(
            trim(
                $organizationType
            )
        );
    }


    /**
     * Vérifie qu'une ressource appartient
     * à l'organisation active.
     *
     * Cette méthode sera utilisée par les services
     * métier pour empêcher les accès inter-organisations.
     */
    public function belongsToCurrentOrganization(
        AccessContext $context,
        int $organizationId
    ): bool {
        if ($organizationId <= 0) {
            return false;
        }

        /*
         * L'administration centrale peut travailler
         * sur toutes les organisations.
         */
        if ($context->isPlatform()) {
            return true;
        }

        if (!$context->isOrganization()) {
            return false;
        }

        return $context->organizationId()
            === $organizationId;
    }


    /**
     * Vérifie qu'une ressource étudiant appartient
     * à l'étudiant actuellement connecté.
     */
    public function belongsToCurrentStudent(
        AccessContext $context,
        int $studentId
    ): bool {
        if (
            !$context->isStudent()
            || $studentId <= 0
        ) {
            return false;
        }

        return $context->studentId()
            === $studentId;
    }
}