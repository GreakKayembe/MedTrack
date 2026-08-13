<?php

declare(strict_types=1);

use MedTrack\Core\Database\Database;
use MedTrack\Core\Http\Middleware\AuthMiddleware;
use MedTrack\Core\Http\Middleware\CsrfMiddleware;
use MedTrack\Core\Http\Middleware\GuestMiddleware;
use MedTrack\Core\Http\Middleware\PasswordChangeMiddleware;
use MedTrack\Core\Http\View;
use MedTrack\Core\Routing\Router;
use MedTrack\Modules\Identity\Controllers\AuthController;
use MedTrack\Modules\Identity\Controllers\PasswordChangeController;
use MedTrack\Modules\Identity\Controllers\PasswordResetController;


return static function (
    Router $router,
    Database $database,
    View $view,
    AuthController $authController,
    PasswordResetController $passwordResetController,
    PasswordChangeController $passwordChangeController,
    CsrfMiddleware $csrfMiddleware,
    AuthMiddleware $authMiddleware,
    GuestMiddleware $guestMiddleware,
    PasswordChangeMiddleware $passwordChangeMiddleware
): void {

    /*
    |--------------------------------------------------------------------------
    | Middleware callables
    |--------------------------------------------------------------------------
    */

    $csrfProtection = [
        $csrfMiddleware,
        'handle',
    ];

    $authProtection = [
        $authMiddleware,
        'handle',
    ];

    $guestProtection = [
        $guestMiddleware,
        'handle',
    ];

    $passwordChangeProtection = [
        $passwordChangeMiddleware,
        'handle',
    ];


    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    */

    $router->get(
        '/login',
        [$authController, 'showLogin']
    );

    $router->middleware(
        'GET',
        '/login',
        $guestProtection
    );


    $router->post(
        '/login',
        [$authController, 'login']
    );

    $router->middleware(
        'POST',
        '/login',
        $guestProtection
    );

    $router->middleware(
        'POST',
        '/login',
        $csrfProtection
    );


    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */

    $router->post(
        '/logout',
        [$authController, 'logout']
    );

    /*
     * La déconnexion reste toujours autorisée pour un utilisateur
     * authentifié, même lorsqu'un changement de mot de passe
     * est obligatoire.
     */
    $router->middleware(
        'POST',
        '/logout',
        $authProtection
    );

    $router->middleware(
        'POST',
        '/logout',
        $csrfProtection
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

    $router->middleware(
        'GET',
        '/forgot-password',
        $guestProtection
    );


    $router->post(
        '/forgot-password',
        [$passwordResetController, 'sendResetLink']
    );

    $router->middleware(
        'POST',
        '/forgot-password',
        $guestProtection
    );

    $router->middleware(
        'POST',
        '/forgot-password',
        $csrfProtection
    );


    /*
    |--------------------------------------------------------------------------
    | Password reset
    |--------------------------------------------------------------------------
    */

    $router->get(
        '/reset-password',
        [$passwordResetController, 'showResetPassword']
    );

    $router->middleware(
        'GET',
        '/reset-password',
        $guestProtection
    );


    $router->post(
        '/reset-password',
        [$passwordResetController, 'resetPassword']
    );

    $router->middleware(
        'POST',
        '/reset-password',
        $guestProtection
    );

    $router->middleware(
        'POST',
        '/reset-password',
        $csrfProtection
    );


    /*
    |--------------------------------------------------------------------------
    | Password change
    |--------------------------------------------------------------------------
    */

    $router->get(
        '/change-password',
        [$passwordChangeController, 'show']
    );

    /*
     * L'utilisateur doit être authentifié.
     *
     * PasswordChangeMiddleware n'est volontairement pas nécessaire ici :
     * cette route doit rester accessible lorsque
     * must_change_password = 1.
     */
    $router->middleware(
        'GET',
        '/change-password',
        $authProtection
    );


    $router->post(
        '/change-password',
        [$passwordChangeController, 'update']
    );

    $router->middleware(
        'POST',
        '/change-password',
        $authProtection
    );

    $router->middleware(
        'POST',
        '/change-password',
        $csrfProtection
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
     * Ordre logique :
     *
     * 1. vérifier l'authentification ;
     * 2. vérifier si un changement de mot de passe est obligatoire ;
     * 3. seulement ensuite exécuter le dashboard.
     */
    $router->middleware(
        'GET',
        '/',
        $authProtection
    );

    $router->middleware(
        'GET',
        '/',
        $passwordChangeProtection
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

                'application' =>
                    $_ENV['APP_NAME'] ?? 'MedTrack',

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