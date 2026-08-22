<?php

declare(strict_types=1);

namespace MedTrack\Modules\Internship\Controllers;

use MedTrack\Core\Http\Request;
use MedTrack\Core\Http\View;
use MedTrack\Modules\Internship\Services\InternshipService;
use RuntimeException;

final class InternshipController
{
    public function __construct(
        private readonly InternshipService $internships,
        private readonly View $view
    ) {
    }

    /**
     * Supervision globale des stages.
     */
    public function index(
        Request $request
    ): string {
        $overview =
            $this->internships
                ->platformOverview();

        return $this->view->render(
            'internships.index',
            [
                'pageTitle' =>
                    'Stages',

                'metrics' =>
                    $overview['metrics'],

                'requestMetrics' =>
                    $overview['request_metrics'],

                'internships' =>
                    $overview['internships'],

                'recentRequests' =>
                    $overview['recent_requests'],
            ]
        );
    }

    /**
     * Consultation détaillée d'un stage.
     */
    public function show(
        Request $request
    ): string {
        $id =
            $this->routeId(
                $request
            );

        $data =
            $this->internships
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
                        'Stage introuvable',
                ]
            );
        }

        $internship =
            $data['internship'];

        return $this->view->render(
            'internships.show',
            [
                'pageTitle' =>
                    'Détail du stage',

                'internship' =>
                    $internship,

                'rotations' =>
                    $data['rotations'],
            ]
        );
    }

    /**
     * Extrait l'identifiant numérique
     * fourni par le Router.
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
                'Identifiant de stage invalide.'
            );
        }

        return $id;
    }
}