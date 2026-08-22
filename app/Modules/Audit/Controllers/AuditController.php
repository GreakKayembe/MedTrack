<?php

declare(strict_types=1);

namespace MedTrack\Modules\Audit\Controllers;

use MedTrack\Core\Http\Request;
use MedTrack\Core\Http\View;
use MedTrack\Modules\Audit\Services\AuditService;
use RuntimeException;

final class AuditController
{
    public function __construct(
        private readonly AuditService $audits,
        private readonly View $view
    ) {
    }

    /**
     * Supervision globale du journal d'audit.
     */
    public function index(
        Request $request
    ): string {
        $overview =
            $this->audits
                ->platformOverview(
                    100
                );

        return $this->view->render(
            'audit.index',
            [
                'pageTitle' =>
                    'Audit',

                'metrics' =>
                    $overview['metrics'],

                'events' =>
                    $overview['events'],

                'actions' =>
                    $overview['actions'],

                'entityTypes' =>
                    $overview['entity_types'],

                'pageScripts' => [
                    '/assets/js/medtrack-audit.js',
                ],
            ]
        );
    }

    /**
     * Détail d'un événement d'audit.
     */
    public function show(
        Request $request
    ): string {
        $id =
            $this->routeId(
                $request
            );

        $data =
            $this->audits
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
                        'Événement d’audit introuvable',
                ]
            );
        }

        return $this->view->render(
            'audit.show',
            [
                'pageTitle' =>
                    'Détail de l’audit',

                'audit' =>
                    $data['audit'],

                'oldValues' =>
                    $data['old_values'],

                'newValues' =>
                    $data['new_values'],

                'metadata' =>
                    $data['metadata'],

                'pageScripts' => [
                    '/assets/js/medtrack-audit.js',
                ],
            ]
        );
    }

    /**
     * Extrait l'identifiant depuis la route.
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
                'Identifiant d’audit invalide.'
            );
        }

        return $id;
    }
}