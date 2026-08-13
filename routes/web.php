<?php

declare(strict_types=1);

use MedTrack\Core\Database\Database;
use MedTrack\Core\Http\View;
use MedTrack\Core\Routing\Router;
use MedTrack\Modules\Identity\Controllers\AuthController;
use MedTrack\Modules\Identity\Controllers\PasswordResetController;

return static function (
    Router $router,
    Database $database,
    View $view,
    AuthController $authController,
    PasswordResetController $passwordResetController
    
): void {
    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    */

    $router->get(
        '/login',
        [$authController, 'showLogin']
    );

    $router->post(
        '/login',
        [$authController, 'login']
    );

    $router->post(
        '/logout',
        [$authController, 'logout']
    );

    /*
|--------------------------------------------------------------------------
| Password recovery
|--------------------------------------------------------------------------
*/

$router->get(
    '/forgot-password',
    [$passwordResetController, 'showForgotPassword']
);

$router->post(
    '/forgot-password',
    [$passwordResetController, 'sendResetLink']
);

$router->get(
    '/reset-password',
    [$passwordResetController, 'showResetPassword']
);

$router->post(
    '/reset-password',
    [$passwordResetController, 'resetPassword']
);

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