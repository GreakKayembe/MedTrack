<?php

declare(strict_types=1);

namespace MedTrack\Core\Context;

use MedTrack\Core\Auth\Session;
use MedTrack\Modules\Academic\Repositories\StudentRepository;
use MedTrack\Modules\Identity\Repositories\OrganizationMembershipRepository;
use MedTrack\Modules\Identity\Repositories\PlatformAccessRepository;
use MedTrack\Modules\Identity\Services\AuthService;
use RuntimeException;

final class AccessContextResolver
{
    private const SESSION_CONTEXT_TYPE =
        'access_context_type';

    private const SESSION_MEMBERSHIP_ID =
        'active_membership_id';

    public function __construct(
        private readonly AuthService $auth,
        private readonly Session $session,
        private readonly PlatformAccessRepository $platformAccess,
        private readonly OrganizationMembershipRepository $memberships,
        private readonly StudentRepository $students
    ) {
    }


    /*
    |--------------------------------------------------------------------------
    | Resolve
    |--------------------------------------------------------------------------
    */

    /**
     * Résout le contexte d'accès effectif
     * de l'utilisateur actuellement connecté.
     *
     * Ordre :
     *
     * 1. contexte explicitement choisi dans la session ;
     * 2. plateforme si l'utilisateur possède un rôle plateforme ;
     * 3. organisation si un seul membership actif existe ;
     * 4. étudiant si son compte est lié à students.user_id ;
     * 5. aucun contexte exploitable => erreur.
     */
    public function resolve(): AccessContext
    {
        $userId =
            $this->authenticatedUserId();

        /*
        |--------------------------------------------------------------------------
        | Explicit session context
        |--------------------------------------------------------------------------
        */

        $storedType =
            strtoupper(
                trim(
                    (string) $this->session->get(
                        self::SESSION_CONTEXT_TYPE,
                        ''
                    )
                )
            );

        if ($storedType !== '') {
            $resolved =
                $this->resolveStoredContext(
                    $userId,
                    $storedType
                );

            if ($resolved !== null) {
                return $resolved;
            }

            /*
             * Le contexte stocké n'est plus valable :
             * membership suspendu, organisation inactive,
             * rôle retiré, etc.
             */
            $this->clearStoredContext();
        }


        /*
        |--------------------------------------------------------------------------
        | Platform
        |--------------------------------------------------------------------------
        */

        if (
            $this->platformAccess
                ->hasPlatformAccess(
                    $userId
                )
        ) {
            $context =
                AccessContext::platform(
                    $userId
                );

            $this->rememberPlatformContext();

            return $context;
        }


        /*
        |--------------------------------------------------------------------------
        | Organization
        |--------------------------------------------------------------------------
        */

        $memberships =
            $this->memberships
                ->findActiveByUserId(
                    $userId
                );

        if (count($memberships) === 1) {
            $context =
                $this->organizationContextFromMembership(
                    $userId,
                    $memberships[0]
                );

            $this->rememberOrganizationContext(
                $context->membershipId()
            );

            return $context;
        }


        /*
        |--------------------------------------------------------------------------
        | Student
        |--------------------------------------------------------------------------
        */

        $student =
            $this->students
                ->findByUserId(
                    $userId
                );

        if ($student !== null) {
            $studentId =
                (int) (
                    $student['id']
                    ?? 0
                );

            if ($studentId > 0) {
                $context =
                    AccessContext::student(
                        $userId,
                        $studentId
                    );

                $this->rememberStudentContext();

                return $context;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Multiple organizations
        |--------------------------------------------------------------------------
        */

        if (count($memberships) > 1) {
            throw new RuntimeException(
                'Plusieurs organisations sont disponibles. '
                . 'Un contexte d’organisation doit être sélectionné.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | No context
        |--------------------------------------------------------------------------
        */

        throw new RuntimeException(
            'Aucun contexte d’accès actif n’est disponible '
            . 'pour cet utilisateur.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Platform selection
    |--------------------------------------------------------------------------
    */

    /**
     * Sélectionne explicitement le contexte plateforme.
     *
     * Impossible si l'utilisateur ne possède
     * aucun rôle plateforme.
     */
    public function selectPlatform(): AccessContext
    {
        $userId =
            $this->authenticatedUserId();

        if (
            !$this->platformAccess
                ->hasPlatformAccess(
                    $userId
                )
        ) {
            throw new RuntimeException(
                'L’utilisateur ne possède pas '
                . 'd’accès à l’administration MedTrack.'
            );
        }

        $context =
            AccessContext::platform(
                $userId
            );

        $this->rememberPlatformContext();

        return $context;
    }


    /*
    |--------------------------------------------------------------------------
    | Organization selection
    |--------------------------------------------------------------------------
    */

    /**
     * Sélectionne explicitement un membership.
     *
     * Le repository vérifie que :
     *
     * - le membership existe ;
     * - il appartient à l'utilisateur ;
     * - il est ACTIVE ;
     * - l'organisation est ACTIVE.
     */
    public function selectOrganization(
        int $membershipId
    ): AccessContext {
        if ($membershipId <= 0) {
            throw new RuntimeException(
                'Membership organisationnel invalide.'
            );
        }

        $userId =
            $this->authenticatedUserId();

        $membership =
            $this->memberships
                ->findActiveMembership(
                    $membershipId,
                    $userId
                );

        if ($membership === null) {
            throw new RuntimeException(
                'Cette organisation n’est pas accessible '
                . 'par l’utilisateur connecté.'
            );
        }

        $context =
            $this->organizationContextFromMembership(
                $userId,
                $membership
            );

        $this->rememberOrganizationContext(
            $membershipId
        );

        return $context;
    }


    /*
    |--------------------------------------------------------------------------
    | Student selection
    |--------------------------------------------------------------------------
    */

    /**
     * Active explicitement l'espace personnel
     * étudiant du compte connecté.
     */
    public function selectStudent(): AccessContext
    {
        $userId =
            $this->authenticatedUserId();

        $student =
            $this->students
                ->findByUserId(
                    $userId
                );

        if ($student === null) {
            throw new RuntimeException(
                'Aucun dossier étudiant n’est associé '
                . 'à ce compte utilisateur.'
            );
        }

        $studentId =
            (int) (
                $student['id']
                ?? 0
            );

        if ($studentId <= 0) {
            throw new RuntimeException(
                'Le dossier étudiant associé est invalide.'
            );
        }

        $context =
            AccessContext::student(
                $userId,
                $studentId
            );

        $this->rememberStudentContext();

        return $context;
    }


    /*
    |--------------------------------------------------------------------------
    | Available organizations
    |--------------------------------------------------------------------------
    */

    /**
     * Organisations accessibles à l'utilisateur.
     *
     * Cette méthode servira au futur écran
     * de sélection/changement d'organisation.
     */
    public function availableOrganizations(): array
    {
        $userId =
            $this->authenticatedUserId();

        return $this->memberships
            ->findActiveByUserId(
                $userId
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Platform availability
    |--------------------------------------------------------------------------
    */

    public function canAccessPlatform(): bool
    {
        $userId =
            $this->authenticatedUserId();

        return $this->platformAccess
            ->hasPlatformAccess(
                $userId
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Student availability
    |--------------------------------------------------------------------------
    */

    public function canAccessStudentSpace(): bool
    {
        $userId =
            $this->authenticatedUserId();

        return $this->students
            ->findByUserId(
                $userId
            ) !== null;
    }


    /*
    |--------------------------------------------------------------------------
    | Clear
    |--------------------------------------------------------------------------
    */

    /**
     * Supprime uniquement le contexte courant.
     *
     * L'utilisateur reste authentifié.
     */
    public function clear(): void
    {
        $this->clearStoredContext();
    }


    /*
    |--------------------------------------------------------------------------
    | Stored context
    |--------------------------------------------------------------------------
    */

    private function resolveStoredContext(
        int $userId,
        string $type
    ): ?AccessContext {
        if (
            $type
            === AccessContext::TYPE_PLATFORM
        ) {
            if (
                !$this->platformAccess
                    ->hasPlatformAccess(
                        $userId
                    )
            ) {
                return null;
            }

            return AccessContext::platform(
                $userId
            );
        }


        if (
            $type
            === AccessContext::TYPE_ORGANIZATION
        ) {
            $membershipId =
                (int) $this->session->get(
                    self::SESSION_MEMBERSHIP_ID,
                    0
                );

            if ($membershipId <= 0) {
                return null;
            }

            $membership =
                $this->memberships
                    ->findActiveMembership(
                        $membershipId,
                        $userId
                    );

            if ($membership === null) {
                return null;
            }

            return $this->organizationContextFromMembership(
                $userId,
                $membership
            );
        }


        if (
            $type
            === AccessContext::TYPE_STUDENT
        ) {
            $student =
                $this->students
                    ->findByUserId(
                        $userId
                    );

            if ($student === null) {
                return null;
            }

            $studentId =
                (int) (
                    $student['id']
                    ?? 0
                );

            if ($studentId <= 0) {
                return null;
            }

            return AccessContext::student(
                $userId,
                $studentId
            );
        }

        return null;
    }


    /*
    |--------------------------------------------------------------------------
    | Build organization context
    |--------------------------------------------------------------------------
    */

    private function organizationContextFromMembership(
        int $userId,
        array $membership
    ): AccessContext {
        $membershipId =
            (int) (
                $membership['membership_id']
                ?? 0
            );

        $organizationId =
            (int) (
                $membership['organization_id']
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

        if (
            $membershipId <= 0
            || $organizationId <= 0
            || $organizationType === ''
        ) {
            throw new RuntimeException(
                'Le membership organisationnel '
                . 'est incomplet.'
            );
        }

        return AccessContext::organization(
            userId: $userId,
            organizationId: $organizationId,
            membershipId: $membershipId,
            organizationType: $organizationType
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    */

    private function authenticatedUserId(): int
    {
        $userId =
            $this->auth->id();

        if (
            $userId === null
            || $userId <= 0
        ) {
            throw new RuntimeException(
                'Aucun utilisateur authentifié.'
            );
        }

        return $userId;
    }


    /*
    |--------------------------------------------------------------------------
    | Remember contexts
    |--------------------------------------------------------------------------
    */

    private function rememberPlatformContext(): void
    {
        $this->session->put(
            self::SESSION_CONTEXT_TYPE,
            AccessContext::TYPE_PLATFORM
        );

        $this->session->forget(
            self::SESSION_MEMBERSHIP_ID
        );
    }


    private function rememberOrganizationContext(
        int $membershipId
    ): void {
        $this->session->put(
            self::SESSION_CONTEXT_TYPE,
            AccessContext::TYPE_ORGANIZATION
        );

        $this->session->put(
            self::SESSION_MEMBERSHIP_ID,
            $membershipId
        );
    }


    private function rememberStudentContext(): void
    {
        $this->session->put(
            self::SESSION_CONTEXT_TYPE,
            AccessContext::TYPE_STUDENT
        );

        $this->session->forget(
            self::SESSION_MEMBERSHIP_ID
        );
    }


    private function clearStoredContext(): void
    {
        $this->session->forget(
            self::SESSION_CONTEXT_TYPE
        );

        $this->session->forget(
            self::SESSION_MEMBERSHIP_ID
        );
    }
}