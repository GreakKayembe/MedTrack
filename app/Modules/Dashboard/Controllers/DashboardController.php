<?php

declare(strict_types=1);

namespace MedTrack\Modules\Dashboard\Controllers;

use MedTrack\Core\Context\AccessContextResolver;
use MedTrack\Core\Http\Request;
use MedTrack\Core\Http\View;
use MedTrack\Modules\Dashboard\Services\DashboardService;
use RuntimeException;

final class DashboardController
{
    public function __construct(
        private readonly AccessContextResolver $accessContextResolver,
        private readonly DashboardService $dashboardService,
        private readonly View $view
    ) {
    }

    /**
     * Affiche automatiquement le tableau de bord
     * correspondant au contexte actif.
     */
    public function index(
        Request $request
    ): string {
        $context =
            $this->accessContextResolver
                ->resolve();

        /*
        |--------------------------------------------------------------------------
        | Platform
        |--------------------------------------------------------------------------
        */

        if ($context->isPlatform()) {
            $dashboard =
                $this->dashboardService
                    ->platform();

            return $this->render(
                'dashboard.platform',
                $dashboard
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Organization
        |--------------------------------------------------------------------------
        */

        if ($context->isOrganization()) {
            $dashboard =
                $this->dashboardService
                    ->organization(
                        $context->organizationId(),
                        $context->organizationType()
                    );

            $viewName =
                $this->organizationView(
                    $context->organizationType()
                );

            return $this->render(
                $viewName,
                $dashboard
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Student
        |--------------------------------------------------------------------------
        */

        if ($context->isStudent()) {
            $dashboard =
                $this->dashboardService
                    ->student(
                        $context->studentId()
                    );

            return $this->render(
                'dashboard.student',
                $dashboard
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Unsupported context
        |--------------------------------------------------------------------------
        */

        throw new RuntimeException(
            'Impossible de déterminer le tableau '
            . 'de bord correspondant au contexte actif.'
        );
    }

    /**
     * Détermine la vue institutionnelle
     * correspondant au type d'organisation.
     */
    private function organizationView(
        string $organizationType
    ): string {
        $organizationType =
            strtoupper(
                trim(
                    $organizationType
                )
            );

        return match ($organizationType) {
            'UNIVERSITY' =>
                'dashboard.university',

            'HOSPITAL' =>
                'dashboard.hospital',

            'PROFESSIONAL_ORDER' =>
                'dashboard.professional-order',

            'MINISTRY' =>
                'dashboard.ministry',

            default =>
                throw new RuntimeException(
                    'Aucune vue de tableau de bord '
                    . 'n’est disponible pour ce type '
                    . 'd’organisation.'
                ),
        };
    }

    /**
     * Rend une vue de dashboard avec les
     * variables communes.
     */
    private function render(
        string $viewName,
        array $dashboard
    ): string {
        return $this->view->render(
            $viewName,
            [
                'pageTitle' =>
                    $dashboard['title']
                    ?? 'Tableau de bord',

                'dashboard' =>
                    $dashboard,

                'pageScripts' => [
                    '/assets/js/medtrack-dashboard.js',
                ],
            ]
        );
    }
}
