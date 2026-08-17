<?php

declare(strict_types=1);

namespace MedTrack\Modules\Identity\Controllers;

use MedTrack\Core\Http\Request;
use MedTrack\Core\Http\View;
use MedTrack\Modules\Identity\Services\UserManagementService;
use RuntimeException;

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

                'platformPermissions' =>
                    $data['platform_permissions'],

                'memberships' =>
                    $data['memberships'],

                'loginHistory' =>
                    $data['login_history'],
            ]
        );
    }

    /**
     * Extrait l'identifiant utilisateur
     * transmis par le Router.
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
}