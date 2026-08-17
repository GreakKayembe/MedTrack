<?php

declare(strict_types=1);

namespace MedTrack\Modules\Dashboard\Services;

use InvalidArgumentException;
use MedTrack\Modules\Dashboard\Repositories\DashboardRepository;
use RuntimeException;

final class DashboardService
{
    public function __construct(
        private readonly DashboardRepository $repository
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Platform
    |--------------------------------------------------------------------------
    */

    /**
     * Dashboard global de l'administration MedTrack.
     */
    public function platform(): array
    {
        return [
            'scope' =>
                'PLATFORM',

            'title' =>
                'Administration MedTrack',

            'subtitle' =>
                'Vue globale de la plateforme',

            'metrics' =>
                $this->repository
                    ->platformMetrics(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | University
    |--------------------------------------------------------------------------
    */

    /**
     * Dashboard d'une université.
     */
    public function university(
        int $universityId
    ): array {
        $this->assertOrganizationId(
            $universityId
        );

        return [
            'scope' =>
                'UNIVERSITY',

            'title' =>
                'Espace Université',

            'subtitle' =>
                'Gestion académique et suivi des stages',

            'organization_id' =>
                $universityId,

            'metrics' =>
                $this->repository
                    ->universityMetrics(
                        $universityId
                    ),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Hospital
    |--------------------------------------------------------------------------
    */

    /**
     * Dashboard d'un établissement hospitalier.
     */
    public function hospital(
        int $hospitalId
    ): array {
        $this->assertOrganizationId(
            $hospitalId
        );

        return [
            'scope' =>
                'HOSPITAL',

            'title' =>
                'Espace Hôpital',

            'subtitle' =>
                'Stages, affectations et encadrement',

            'organization_id' =>
                $hospitalId,

            'metrics' =>
                $this->repository
                    ->hospitalMetrics(
                        $hospitalId
                    ),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Student
    |--------------------------------------------------------------------------
    */

    /**
     * Dashboard personnel d'un étudiant.
     */
    public function student(
        int $studentId
    ): array {
        if ($studentId <= 0) {
            throw new InvalidArgumentException(
                'Identifiant d’étudiant invalide.'
            );
        }

        return [
            'scope' =>
                'STUDENT',

            'title' =>
                'Mon espace étudiant',

            'subtitle' =>
                'Parcours académique et stages',

            'student_id' =>
                $studentId,

            'metrics' =>
                $this->repository
                    ->studentMetrics(
                        $studentId
                    ),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Professional order
    |--------------------------------------------------------------------------
    */

    /**
     * Le repository spécifique aux ordres sera branché
     * lorsque leur modèle métier aura été vérifié.
     */
    public function professionalOrder(
        int $organizationId
    ): array {
        $this->assertOrganizationId(
            $organizationId
        );

        return [
            'scope' =>
                'PROFESSIONAL_ORDER',

            'title' =>
                'Espace Ordre professionnel',

            'subtitle' =>
                'Inscriptions et validations professionnelles',

            'organization_id' =>
                $organizationId,

            'metrics' => [],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Ministry
    |--------------------------------------------------------------------------
    */

    /**
     * Le dashboard analytique du ministère sera complété
     * après validation de son modèle de données.
     */
    public function ministry(
        int $organizationId
    ): array {
        $this->assertOrganizationId(
            $organizationId
        );

        return [
            'scope' =>
                'MINISTRY',

            'title' =>
                'Espace Ministère',

            'subtitle' =>
                'Supervision et statistiques nationales',

            'organization_id' =>
                $organizationId,

            'metrics' => [],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Organization dispatcher
    |--------------------------------------------------------------------------
    */

    /**
     * Résout le dashboard institutionnel à partir
     * du type d'organisation déjà déterminé
     * par le contexte d'accès.
     *
     * IMPORTANT :
     * organizationId et organizationType doivent venir
     * du contexte serveur et jamais du navigateur.
     */
    public function organization(
        int $organizationId,
        string $organizationType
    ): array {
        $this->assertOrganizationId(
            $organizationId
        );

        $organizationType =
            strtoupper(
                trim(
                    $organizationType
                )
            );

        return match ($organizationType) {
            'UNIVERSITY' =>
                $this->university(
                    $organizationId
                ),

            'HOSPITAL' =>
                $this->hospital(
                    $organizationId
                ),

            'PROFESSIONAL_ORDER' =>
                $this->professionalOrder(
                    $organizationId
                ),

            'MINISTRY' =>
                $this->ministry(
                    $organizationId
                ),

            default =>
                throw new RuntimeException(
                    'Type d’organisation non pris '
                    . 'en charge par le tableau de bord.'
                ),
        };
    }

    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

    private function assertOrganizationId(
        int $organizationId
    ): void {
        if ($organizationId <= 0) {
            throw new InvalidArgumentException(
                'Identifiant d’organisation invalide.'
            );
        }
    }
}
