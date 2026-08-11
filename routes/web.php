<?php

declare(strict_types=1);

use MedTrack\Core\Database\Database;
use MedTrack\Core\Http\View;
use MedTrack\Core\Routing\Router;

return static function (
    Router $router,
    Database $database,
    View $view
): void {
    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    $router->get(
        '/',
        static function () use ($view): string {
            return $view->render(
                'dashboard.index',
                [
                    'pageTitle' => 'Tableau de bord',
                ]
            );
        }
    );

    /*
    |--------------------------------------------------------------------------
    | Technical
    |--------------------------------------------------------------------------
    */

    $router->get(
        '/technical/ping',
        static function () use ($database): array {
            $pdo = $database->connection();

            return [
                'status' => 'ok',
                'application' => $_ENV['APP_NAME'] ?? 'MedTrack',
                'database' => [
                    'status' => 'connected',
                    'name' => $pdo
                        ->query('SELECT DATABASE()')
                        ->fetchColumn(),
                    'server' => 'MySQL',
                    'version' => $pdo
                        ->query('SELECT VERSION()')
                        ->fetchColumn(),
                ],
            ];
        }
    );
};