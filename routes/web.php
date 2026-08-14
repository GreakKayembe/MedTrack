<?php

declare(strict_types=1);

use MedTrack\Core\Database\Database;
use MedTrack\Core\Http\Middleware\AuthMiddleware;
use MedTrack\Core\Http\Middleware\CsrfMiddleware;
use MedTrack\Core\Http\Middleware\GuestMiddleware;
use MedTrack\Core\Http\Middleware\PasswordChangeMiddleware;
use MedTrack\Core\Http\View;
use MedTrack\Core\Routing\Router;

use MedTrack\Modules\Academic\Controllers\AcademicProgramController;
use MedTrack\Modules\Academic\Controllers\AcademicYearController;
use MedTrack\Modules\Academic\Controllers\FacultyController;
use MedTrack\Modules\Academic\Controllers\StudyLevelController;
use MedTrack\Modules\Academic\Controllers\CohortController;
use MedTrack\Modules\Academic\Controllers\UniversityController;

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
    UniversityController $universityController,
    FacultyController $facultyController,
    AcademicProgramController $academicProgramController,
    AcademicYearController $academicYearController,
    StudyLevelController $studyLevelController,
    CohortController $cohortController,
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
     * Cette route doit rester accessible lorsque
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

                    'pageScripts' => [
                        '/assets/js/medtrack-dashboard.js',
                    ],
                ]
            );
        }
    );

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
    | Academic - Universities
    |--------------------------------------------------------------------------
    */

    /*
     * Liste des universités.
     */
    $router->get(
        '/universities',
        [$universityController, 'index']
    );

    $router->middleware(
        'GET',
        '/universities',
        $authProtection
    );

    $router->middleware(
        'GET',
        '/universities',
        $passwordChangeProtection
    );


    /*
     * Formulaire de création.
     */
    $router->get(
        '/universities/create',
        [$universityController, 'create']
    );

    $router->middleware(
        'GET',
        '/universities/create',
        $authProtection
    );

    $router->middleware(
        'GET',
        '/universities/create',
        $passwordChangeProtection
    );


    /*
     * Enregistrement d'une université.
     */
    $router->post(
        '/universities',
        [$universityController, 'store']
    );

    $router->middleware(
        'POST',
        '/universities',
        $authProtection
    );

    $router->middleware(
        'POST',
        '/universities',
        $passwordChangeProtection
    );

    $router->middleware(
        'POST',
        '/universities',
        $csrfProtection
    );


    /*
     * Consultation d'une université.
     */
    $router->get(
        '/universities/{id}',
        [$universityController, 'show']
    );

    $router->middleware(
        'GET',
        '/universities/{id}',
        $authProtection
    );

    $router->middleware(
        'GET',
        '/universities/{id}',
        $passwordChangeProtection
    );


    /*
     * Formulaire de modification.
     */
    $router->get(
        '/universities/{id}/edit',
        [$universityController, 'edit']
    );

    $router->middleware(
        'GET',
        '/universities/{id}/edit',
        $authProtection
    );

    $router->middleware(
        'GET',
        '/universities/{id}/edit',
        $passwordChangeProtection
    );


    /*
     * Mise à jour d'une université.
     */
    $router->post(
        '/universities/{id}',
        [$universityController, 'update']
    );

    $router->middleware(
        'POST',
        '/universities/{id}',
        $authProtection
    );

    $router->middleware(
        'POST',
        '/universities/{id}',
        $passwordChangeProtection
    );

    $router->middleware(
        'POST',
        '/universities/{id}',
        $csrfProtection
    );


    /*
    |--------------------------------------------------------------------------
    | Academic - Faculties
    |--------------------------------------------------------------------------
    */

    /*
     * Liste des facultés.
     */
    $router->get(
        '/faculties',
        [$facultyController, 'index']
    );

    $router->middleware(
        'GET',
        '/faculties',
        $authProtection
    );

    $router->middleware(
        'GET',
        '/faculties',
        $passwordChangeProtection
    );


    /*
     * Formulaire de création.
     */
    $router->get(
        '/faculties/create',
        [$facultyController, 'create']
    );

    $router->middleware(
        'GET',
        '/faculties/create',
        $authProtection
    );

    $router->middleware(
        'GET',
        '/faculties/create',
        $passwordChangeProtection
    );


    /*
     * Enregistrement d'une faculté.
     */
    $router->post(
        '/faculties',
        [$facultyController, 'store']
    );

    $router->middleware(
        'POST',
        '/faculties',
        $authProtection
    );

    $router->middleware(
        'POST',
        '/faculties',
        $passwordChangeProtection
    );

    $router->middleware(
        'POST',
        '/faculties',
        $csrfProtection
    );


    /*
     * Consultation d'une faculté.
     */
    $router->get(
        '/faculties/{id}',
        [$facultyController, 'show']
    );

    $router->middleware(
        'GET',
        '/faculties/{id}',
        $authProtection
    );

    $router->middleware(
        'GET',
        '/faculties/{id}',
        $passwordChangeProtection
    );


    /*
     * Formulaire de modification.
     */
    $router->get(
        '/faculties/{id}/edit',
        [$facultyController, 'edit']
    );

    $router->middleware(
        'GET',
        '/faculties/{id}/edit',
        $authProtection
    );

    $router->middleware(
        'GET',
        '/faculties/{id}/edit',
        $passwordChangeProtection
    );


    /*
     * Mise à jour d'une faculté.
     */
    $router->post(
        '/faculties/{id}',
        [$facultyController, 'update']
    );

    $router->middleware(
        'POST',
        '/faculties/{id}',
        $authProtection
    );

    $router->middleware(
        'POST',
        '/faculties/{id}',
        $passwordChangeProtection
    );

    $router->middleware(
        'POST',
        '/faculties/{id}',
        $csrfProtection
    );


    /*
    |--------------------------------------------------------------------------
    | Academic - Academic Programs
    |--------------------------------------------------------------------------
    */

    /*
     * Liste des programmes académiques.
     */
    $router->get(
        '/academic-programs',
        [$academicProgramController, 'index']
    );

    $router->middleware(
        'GET',
        '/academic-programs',
        $authProtection
    );

    $router->middleware(
        'GET',
        '/academic-programs',
        $passwordChangeProtection
    );


    /*
     * Formulaire de création.
     */
    $router->get(
        '/academic-programs/create',
        [$academicProgramController, 'create']
    );

    $router->middleware(
        'GET',
        '/academic-programs/create',
        $authProtection
    );

    $router->middleware(
        'GET',
        '/academic-programs/create',
        $passwordChangeProtection
    );


    /*
     * Enregistrement d'un programme académique.
     */
    $router->post(
        '/academic-programs',
        [$academicProgramController, 'store']
    );

    $router->middleware(
        'POST',
        '/academic-programs',
        $authProtection
    );

    $router->middleware(
        'POST',
        '/academic-programs',
        $passwordChangeProtection
    );

    $router->middleware(
        'POST',
        '/academic-programs',
        $csrfProtection
    );


    /*
     * Consultation d'un programme académique.
     */
    $router->get(
        '/academic-programs/{id}',
        [$academicProgramController, 'show']
    );

    $router->middleware(
        'GET',
        '/academic-programs/{id}',
        $authProtection
    );

    $router->middleware(
        'GET',
        '/academic-programs/{id}',
        $passwordChangeProtection
    );


    /*
     * Formulaire de modification.
     */
    $router->get(
        '/academic-programs/{id}/edit',
        [$academicProgramController, 'edit']
    );

    $router->middleware(
        'GET',
        '/academic-programs/{id}/edit',
        $authProtection
    );

    $router->middleware(
        'GET',
        '/academic-programs/{id}/edit',
        $passwordChangeProtection
    );


    /*
     * Mise à jour d'un programme académique.
     */
    $router->post(
        '/academic-programs/{id}',
        [$academicProgramController, 'update']
    );

    $router->middleware(
        'POST',
        '/academic-programs/{id}',
        $authProtection
    );

    $router->middleware(
        'POST',
        '/academic-programs/{id}',
        $passwordChangeProtection
    );

    $router->middleware(
        'POST',
        '/academic-programs/{id}',
        $csrfProtection
    );


    /*
    |--------------------------------------------------------------------------
    | Academic - Academic Years
    |--------------------------------------------------------------------------
    */

    /*
     * Liste des années académiques.
     */
    $router->get(
        '/academic-years',
        [$academicYearController, 'index']
    );

    $router->middleware(
        'GET',
        '/academic-years',
        $authProtection
    );

    $router->middleware(
        'GET',
        '/academic-years',
        $passwordChangeProtection
    );


    /*
     * Formulaire de création.
     */
    $router->get(
        '/academic-years/create',
        [$academicYearController, 'create']
    );

    $router->middleware(
        'GET',
        '/academic-years/create',
        $authProtection
    );

    $router->middleware(
        'GET',
        '/academic-years/create',
        $passwordChangeProtection
    );


    /*
     * Enregistrement d'une année académique.
     */
    $router->post(
        '/academic-years',
        [$academicYearController, 'store']
    );

    $router->middleware(
        'POST',
        '/academic-years',
        $authProtection
    );

    $router->middleware(
        'POST',
        '/academic-years',
        $passwordChangeProtection
    );

    $router->middleware(
        'POST',
        '/academic-years',
        $csrfProtection
    );


    /*
     * Consultation d'une année académique.
     */
    $router->get(
        '/academic-years/{id}',
        [$academicYearController, 'show']
    );

    $router->middleware(
        'GET',
        '/academic-years/{id}',
        $authProtection
    );

    $router->middleware(
        'GET',
        '/academic-years/{id}',
        $passwordChangeProtection
    );


    /*
     * Formulaire de modification.
     */
    $router->get(
        '/academic-years/{id}/edit',
        [$academicYearController, 'edit']
    );

    $router->middleware(
        'GET',
        '/academic-years/{id}/edit',
        $authProtection
    );

    $router->middleware(
        'GET',
        '/academic-years/{id}/edit',
        $passwordChangeProtection
    );


    /*
     * Mise à jour d'une année académique.
     */
    $router->post(
        '/academic-years/{id}',
        [$academicYearController, 'update']
    );

    $router->middleware(
        'POST',
        '/academic-years/{id}',
        $authProtection
    );

    $router->middleware(
        'POST',
        '/academic-years/{id}',
        $passwordChangeProtection
    );

    $router->middleware(
        'POST',
        '/academic-years/{id}',
        $csrfProtection
    );


        /*
    |--------------------------------------------------------------------------
    | Academic - Study Levels
    |--------------------------------------------------------------------------
    */

    /*
     * Liste des niveaux d'études.
     */
    $router->get(
        '/study-levels',
        [$studyLevelController, 'index']
    );

    $router->middleware(
        'GET',
        '/study-levels',
        $authProtection
    );

    $router->middleware(
        'GET',
        '/study-levels',
        $passwordChangeProtection
    );


    /*
     * Formulaire de création.
     */
    $router->get(
        '/study-levels/create',
        [$studyLevelController, 'create']
    );

    $router->middleware(
        'GET',
        '/study-levels/create',
        $authProtection
    );

    $router->middleware(
        'GET',
        '/study-levels/create',
        $passwordChangeProtection
    );


    /*
     * Enregistrement d'un niveau d'études.
     */
    $router->post(
        '/study-levels',
        [$studyLevelController, 'store']
    );

    $router->middleware(
        'POST',
        '/study-levels',
        $authProtection
    );

    $router->middleware(
        'POST',
        '/study-levels',
        $passwordChangeProtection
    );

    $router->middleware(
        'POST',
        '/study-levels',
        $csrfProtection
    );


    /*
     * Consultation d'un niveau d'études.
     */
    $router->get(
        '/study-levels/{id}',
        [$studyLevelController, 'show']
    );

    $router->middleware(
        'GET',
        '/study-levels/{id}',
        $authProtection
    );

    $router->middleware(
        'GET',
        '/study-levels/{id}',
        $passwordChangeProtection
    );


    /*
     * Formulaire de modification.
     */
    $router->get(
        '/study-levels/{id}/edit',
        [$studyLevelController, 'edit']
    );

    $router->middleware(
        'GET',
        '/study-levels/{id}/edit',
        $authProtection
    );

    $router->middleware(
        'GET',
        '/study-levels/{id}/edit',
        $passwordChangeProtection
    );


    /*
     * Mise à jour d'un niveau d'études.
     */
    $router->post(
        '/study-levels/{id}',
        [$studyLevelController, 'update']
    );

    $router->middleware(
        'POST',
        '/study-levels/{id}',
        $authProtection
    );

    $router->middleware(
        'POST',
        '/study-levels/{id}',
        $passwordChangeProtection
    );

    $router->middleware(
        'POST',
        '/study-levels/{id}',
        $csrfProtection
    );


        /*
    |--------------------------------------------------------------------------
    | Academic - Cohorts
    |--------------------------------------------------------------------------
    */

    /*
     * Liste des cohortes.
     */
    $router->get(
        '/cohorts',
        [$cohortController, 'index']
    );

    $router->middleware(
        'GET',
        '/cohorts',
        $authProtection
    );

    $router->middleware(
        'GET',
        '/cohorts',
        $passwordChangeProtection
    );


    /*
     * Formulaire de création.
     */
    $router->get(
        '/cohorts/create',
        [$cohortController, 'create']
    );

    $router->middleware(
        'GET',
        '/cohorts/create',
        $authProtection
    );

    $router->middleware(
        'GET',
        '/cohorts/create',
        $passwordChangeProtection
    );


    /*
     * Enregistrement d'une cohorte.
     */
    $router->post(
        '/cohorts',
        [$cohortController, 'store']
    );

    $router->middleware(
        'POST',
        '/cohorts',
        $authProtection
    );

    $router->middleware(
        'POST',
        '/cohorts',
        $passwordChangeProtection
    );

    $router->middleware(
        'POST',
        '/cohorts',
        $csrfProtection
    );


    /*
     * Consultation d'une cohorte.
     */
    $router->get(
        '/cohorts/{id}',
        [$cohortController, 'show']
    );

    $router->middleware(
        'GET',
        '/cohorts/{id}',
        $authProtection
    );

    $router->middleware(
        'GET',
        '/cohorts/{id}',
        $passwordChangeProtection
    );


    /*
     * Formulaire de modification.
     */
    $router->get(
        '/cohorts/{id}/edit',
        [$cohortController, 'edit']
    );

    $router->middleware(
        'GET',
        '/cohorts/{id}/edit',
        $authProtection
    );

    $router->middleware(
        'GET',
        '/cohorts/{id}/edit',
        $passwordChangeProtection
    );


    /*
     * Mise à jour d'une cohorte.
     */
    $router->post(
        '/cohorts/{id}',
        [$cohortController, 'update']
    );

    $router->middleware(
        'POST',
        '/cohorts/{id}',
        $authProtection
    );

    $router->middleware(
        'POST',
        '/cohorts/{id}',
        $passwordChangeProtection
    );

    $router->middleware(
        'POST',
        '/cohorts/{id}',
        $csrfProtection
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