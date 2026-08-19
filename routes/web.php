<?php

declare(strict_types=1);

use MedTrack\Core\Database\Database;
use MedTrack\Modules\Dashboard\Controllers\DashboardController;
use MedTrack\Core\Http\Middleware\AccessContextMiddleware;
use MedTrack\Core\Http\Middleware\AuthMiddleware;
use MedTrack\Core\Http\Middleware\CsrfMiddleware;
use MedTrack\Core\Http\Middleware\GuestMiddleware;
use MedTrack\Core\Http\Middleware\PasswordChangeMiddleware;
use MedTrack\Core\Http\Middleware\PermissionMiddleware;
use MedTrack\Core\Http\View;
use MedTrack\Core\Routing\Router;
use MedTrack\Modules\Academic\Controllers\AcademicEnrollmentController;
use MedTrack\Modules\Academic\Controllers\ProfessionalOrderController;
use MedTrack\Modules\Academic\Controllers\MinistryController;
use MedTrack\Modules\Internship\Controllers\InternshipController;
use MedTrack\Modules\Finance\Controllers\PaymentController;
use MedTrack\Modules\Identity\Controllers\RoleManagementController;
use MedTrack\Modules\Audit\Controllers\AuditController;



use MedTrack\Modules\Academic\Controllers\AcademicProgramController;
use MedTrack\Modules\Academic\Controllers\AcademicYearController;
use MedTrack\Modules\Academic\Controllers\CohortController;
use MedTrack\Modules\Academic\Controllers\FacultyController;
use MedTrack\Modules\Academic\Controllers\StudentController;
use MedTrack\Modules\Academic\Controllers\StudyLevelController;
use MedTrack\Modules\Academic\Controllers\UniversityController;
use MedTrack\Modules\Academic\Controllers\HospitalController;


use MedTrack\Modules\Identity\Controllers\AuthController;
use MedTrack\Modules\Identity\Controllers\UserManagementController;
use MedTrack\Modules\Identity\Controllers\PasswordChangeController;
use MedTrack\Modules\Identity\Controllers\PasswordResetController;


return static function (
    Router $router,
    Database $database,
    View $view,
    AuthController $authController,
    PasswordResetController $passwordResetController,
    PasswordChangeController $passwordChangeController,
    DashboardController $dashboardController,

    UniversityController $universityController,
    HospitalController $hospitalController,
    ProfessionalOrderController $professionalOrderController,
    MinistryController $ministryController,

    FacultyController $facultyController,
    AcademicProgramController $academicProgramController,
    AcademicYearController $academicYearController,
    StudyLevelController $studyLevelController,
    CohortController $cohortController,
    StudentController $studentController,

    InternshipController $internshipController,
    PaymentController $paymentController,
    UserManagementController $userManagementController,
    RoleManagementController $roleManagementController,
    AuditController $auditController,
    AcademicEnrollmentController $academicEnrollmentController,

    CsrfMiddleware $csrfMiddleware,
    AuthMiddleware $authMiddleware,
    GuestMiddleware $guestMiddleware,
    PasswordChangeMiddleware $passwordChangeMiddleware,
    AccessContextMiddleware $accessContextMiddleware,
    PermissionMiddleware $permissionMiddleware
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

        $accessContextProtection = [
        $accessContextMiddleware,
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
    | Identity - Users
    |--------------------------------------------------------------------------
    */

    /*
    * Supervision globale des utilisateurs.
    */
    $router->get(
        '/users',
        [$userManagementController, 'index']
    );

    $router->middleware(
        'GET',
        '/users',
        $authProtection
    );

    $router->middleware(
        'GET',
        '/users',
        $passwordChangeProtection
    );

    $router->middleware(
        'GET',
        '/users',
        $accessContextProtection
    );


    /*
    * Consultation d'un utilisateur.
    */
    $router->get(
        '/users/{id}',
        [$userManagementController, 'show']
    );

    $router->middleware(
        'GET',
        '/users/{id}',
        $authProtection
    );

    $router->middleware(
        'GET',
        '/users/{id}',
        $passwordChangeProtection
    );

    $router->middleware(
        'GET',
        '/users/{id}',
        $accessContextProtection
    );



        /*
    |--------------------------------------------------------------------------
    | Identity - Roles & Permissions
    |--------------------------------------------------------------------------
    */

    /*
    * Supervision globale des rôles et permissions.
    */
    $router->get(
        '/roles',
        [$roleManagementController, 'index']
    );

    $router->middleware(
        'GET',
        '/roles',
        $authProtection
    );

    $router->middleware(
        'GET',
        '/roles',
        $passwordChangeProtection
    );

    $router->middleware(
        'GET',
        '/roles',
        $accessContextProtection
    );


    /*
    * Consultation d'un rôle.
    */
    $router->get(
        '/roles/{id}',
        [$roleManagementController, 'show']
    );

    $router->middleware(
        'GET',
        '/roles/{id}',
        $authProtection
    );

    $router->middleware(
        'GET',
        '/roles/{id}',
        $passwordChangeProtection
    );

    $router->middleware(
        'GET',
        '/roles/{id}',
        $accessContextProtection
    );


        /*
    |--------------------------------------------------------------------------
    | Identity - Role Management Actions
    |--------------------------------------------------------------------------
    */

    /*
    * Création d'un rôle personnalisé.
    */
    $router->post(
        '/roles',
        [$roleManagementController, 'create']
    );

    $router->middleware(
        'POST',
        '/roles',
        $authProtection
    );

    $router->middleware(
        'POST',
        '/roles',
        $passwordChangeProtection
    );

    $router->middleware(
        'POST',
        '/roles',
        $accessContextProtection
    );

    $router->middleware(
        'POST',
        '/roles',
        $csrfProtection
    );


    /*
    * Renommage d'un rôle personnalisé.
    */
    $router->post(
        '/roles/{id}/rename',
        [$roleManagementController, 'rename']
    );

    $router->middleware(
        'POST',
        '/roles/{id}/rename',
        $authProtection
    );

    $router->middleware(
        'POST',
        '/roles/{id}/rename',
        $passwordChangeProtection
    );

    $router->middleware(
        'POST',
        '/roles/{id}/rename',
        $accessContextProtection
    );

    $router->middleware(
        'POST',
        '/roles/{id}/rename',
        $csrfProtection
    );


    /*
    * Attribution d'une permission.
    */
    $router->post(
        '/roles/{id}/permissions',
        [$roleManagementController, 'assignPermission']
    );

    $router->middleware(
        'POST',
        '/roles/{id}/permissions',
        $authProtection
    );

    $router->middleware(
        'POST',
        '/roles/{id}/permissions',
        $passwordChangeProtection
    );

    $router->middleware(
        'POST',
        '/roles/{id}/permissions',
        $accessContextProtection
    );

    $router->middleware(
        'POST',
        '/roles/{id}/permissions',
        $csrfProtection
    );


    /*
    * Retrait d'une permission.
    */
    $router->post(
        '/roles/{id}/permissions/{permissionId}/remove',
        [$roleManagementController, 'removePermission']
    );

    $router->middleware(
        'POST',
        '/roles/{id}/permissions/{permissionId}/remove',
        $authProtection
    );

    $router->middleware(
        'POST',
        '/roles/{id}/permissions/{permissionId}/remove',
        $passwordChangeProtection
    );

    $router->middleware(
        'POST',
        '/roles/{id}/permissions/{permissionId}/remove',
        $accessContextProtection
    );

    $router->middleware(
        'POST',
        '/roles/{id}/permissions/{permissionId}/remove',
        $csrfProtection
    );


    /*
    * Suppression d'un rôle personnalisé.
    */
    $router->post(
        '/roles/{id}/delete',
        [$roleManagementController, 'delete']
    );

    $router->middleware(
        'POST',
        '/roles/{id}/delete',
        $authProtection
    );

    $router->middleware(
        'POST',
        '/roles/{id}/delete',
        $passwordChangeProtection
    );

    $router->middleware(
        'POST',
        '/roles/{id}/delete',
        $accessContextProtection
    );

    $router->middleware(
        'POST',
        '/roles/{id}/delete',
        $csrfProtection
    );


        /*
    |--------------------------------------------------------------------------
    | Audit
    |--------------------------------------------------------------------------
    */

    /**
     * Journal global des événements d'audit.
     */
    $router->get(
        '/audit',
        [$auditController, 'index']
    );

    $router->middleware(
        'GET',
        '/audit',
        $authProtection
    );

    $router->middleware(
        'GET',
        '/audit',
        $passwordChangeProtection
    );

    $router->middleware(
        'GET',
        '/audit',
        $accessContextProtection
    );


    /**
     * Détail d'un événement d'audit.
     */
    $router->get(
        '/audit/{id}',
        [$auditController, 'show']
    );

    $router->middleware(
        'GET',
        '/audit/{id}',
        $authProtection
    );

    $router->middleware(
        'GET',
        '/audit/{id}',
        $passwordChangeProtection
    );

    $router->middleware(
        'GET',
        '/audit/{id}',
        $accessContextProtection
    );



        /*
    |--------------------------------------------------------------------------
    | Identity - User Management Actions
    |--------------------------------------------------------------------------
    */

    /*
    * Modifier le statut d'un utilisateur.
    */
    $router->post(
        '/users/{id}/status',
        [$userManagementController, 'updateStatus']
    );

    $router->middleware(
        'POST',
        '/users/{id}/status',
        $authProtection
    );

    $router->middleware(
        'POST',
        '/users/{id}/status',
        $passwordChangeProtection
    );

    $router->middleware(
        'POST',
        '/users/{id}/status',
        $accessContextProtection
    );

    $router->middleware(
        'POST',
        '/users/{id}/status',
        $csrfProtection
    );


    /*
    * Forcer ou retirer l'obligation
    * de changement de mot de passe.
    */
    $router->post(
        '/users/{id}/password-change-requirement',
        [
            $userManagementController,
            'updatePasswordChangeRequirement',
        ]
    );

    $router->middleware(
        'POST',
        '/users/{id}/password-change-requirement',
        $authProtection
    );

    $router->middleware(
        'POST',
        '/users/{id}/password-change-requirement',
        $passwordChangeProtection
    );

    $router->middleware(
        'POST',
        '/users/{id}/password-change-requirement',
        $accessContextProtection
    );

    $router->middleware(
        'POST',
        '/users/{id}/password-change-requirement',
        $csrfProtection
    );


    /*
    * Attribuer un rôle plateforme.
    */
    $router->post(
        '/users/{id}/platform-roles',
        [$userManagementController, 'assignPlatformRole']
    );

    $router->middleware(
        'POST',
        '/users/{id}/platform-roles',
        $authProtection
    );

    $router->middleware(
        'POST',
        '/users/{id}/platform-roles',
        $passwordChangeProtection
    );

    $router->middleware(
        'POST',
        '/users/{id}/platform-roles',
        $accessContextProtection
    );

    $router->middleware(
        'POST',
        '/users/{id}/platform-roles',
        $csrfProtection
    );


    /*
    * Retirer un rôle plateforme.
    */
    $router->post(
        '/users/{id}/platform-roles/{roleId}/remove',
        [$userManagementController, 'removePlatformRole']
    );

    $router->middleware(
        'POST',
        '/users/{id}/platform-roles/{roleId}/remove',
        $authProtection
    );

    $router->middleware(
        'POST',
        '/users/{id}/platform-roles/{roleId}/remove',
        $passwordChangeProtection
    );

    $router->middleware(
        'POST',
        '/users/{id}/platform-roles/{roleId}/remove',
        $accessContextProtection
    );

    $router->middleware(
        'POST',
        '/users/{id}/platform-roles/{roleId}/remove',
        $csrfProtection
    );


    /*
    * Attribuer un rôle institutionnel
    * à un membership.
    */
    $router->post(
        '/memberships/{membershipId}/roles',
        [$userManagementController, 'assignMembershipRole']
    );

    $router->middleware(
        'POST',
        '/memberships/{membershipId}/roles',
        $authProtection
    );

    $router->middleware(
        'POST',
        '/memberships/{membershipId}/roles',
        $passwordChangeProtection
    );

    $router->middleware(
        'POST',
        '/memberships/{membershipId}/roles',
        $accessContextProtection
    );

    $router->middleware(
        'POST',
        '/memberships/{membershipId}/roles',
        $csrfProtection
    );


    /*
    * Retirer un rôle institutionnel
    * d'un membership.
    */
    $router->post(
        '/memberships/{membershipId}/roles/{roleId}/remove',
        [$userManagementController, 'removeMembershipRole']
    );

    $router->middleware(
        'POST',
        '/memberships/{membershipId}/roles/{roleId}/remove',
        $authProtection
    );

    $router->middleware(
        'POST',
        '/memberships/{membershipId}/roles/{roleId}/remove',
        $passwordChangeProtection
    );

    $router->middleware(
        'POST',
        '/memberships/{membershipId}/roles/{roleId}/remove',
        $accessContextProtection
    );

    $router->middleware(
        'POST',
        '/memberships/{membershipId}/roles/{roleId}/remove',
        $csrfProtection
    );

        /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    $router->get(
        '/',
        [$dashboardController, 'index']
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

    $router->middleware(
        'GET',
        '/',
        $accessContextProtection
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
    | Academic - Hospitals
    |--------------------------------------------------------------------------
    */

    /*
    * Liste des hôpitaux.
    */
    $router->get(
        '/hospitals',
        [$hospitalController, 'index']
    );

    $router->middleware(
        'GET',
        '/hospitals',
        $authProtection
    );

    $router->middleware(
        'GET',
        '/hospitals',
        $passwordChangeProtection
    );

    $router->middleware(
        'GET',
        '/hospitals',
        $accessContextProtection
    );


    /*
    * Formulaire de création.
    */
    $router->get(
        '/hospitals/create',
        [$hospitalController, 'create']
    );

    $router->middleware(
        'GET',
        '/hospitals/create',
        $authProtection
    );

    $router->middleware(
        'GET',
        '/hospitals/create',
        $passwordChangeProtection
    );

    $router->middleware(
        'GET',
        '/hospitals/create',
        $accessContextProtection
    );


    /*
    * Enregistrement d'un hôpital.
    */
    $router->post(
        '/hospitals',
        [$hospitalController, 'store']
    );

    $router->middleware(
        'POST',
        '/hospitals',
        $authProtection
    );

    $router->middleware(
        'POST',
        '/hospitals',
        $passwordChangeProtection
    );

    $router->middleware(
        'POST',
        '/hospitals',
        $accessContextProtection
    );

    $router->middleware(
        'POST',
        '/hospitals',
        $csrfProtection
    );


    /*
    * Consultation d'un hôpital.
    */
    $router->get(
        '/hospitals/{id}',
        [$hospitalController, 'show']
    );

    $router->middleware(
        'GET',
        '/hospitals/{id}',
        $authProtection
    );

    $router->middleware(
        'GET',
        '/hospitals/{id}',
        $passwordChangeProtection
    );

    $router->middleware(
        'GET',
        '/hospitals/{id}',
        $accessContextProtection
    );


    /*
    * Formulaire de modification.
    */
    $router->get(
        '/hospitals/{id}/edit',
        [$hospitalController, 'edit']
    );

    $router->middleware(
        'GET',
        '/hospitals/{id}/edit',
        $authProtection
    );

    $router->middleware(
        'GET',
        '/hospitals/{id}/edit',
        $passwordChangeProtection
    );

    $router->middleware(
        'GET',
        '/hospitals/{id}/edit',
        $accessContextProtection
    );


    /*
    * Mise à jour d'un hôpital.
    */
    $router->post(
        '/hospitals/{id}',
        [$hospitalController, 'update']
    );

    $router->middleware(
        'POST',
        '/hospitals/{id}',
        $authProtection
    );

    $router->middleware(
        'POST',
        '/hospitals/{id}',
        $passwordChangeProtection
    );

    $router->middleware(
        'POST',
        '/hospitals/{id}',
        $accessContextProtection
    );

    $router->middleware(
        'POST',
        '/hospitals/{id}',
        $csrfProtection
    );



        /*
    |--------------------------------------------------------------------------
    | Academic - Professional Orders
    |--------------------------------------------------------------------------
    */

    /*
    * Liste des ordres professionnels.
    */
    $router->get(
        '/professional-orders',
        [$professionalOrderController, 'index']
    );

    $router->middleware(
        'GET',
        '/professional-orders',
        $authProtection
    );

    $router->middleware(
        'GET',
        '/professional-orders',
        $passwordChangeProtection
    );

    $router->middleware(
        'GET',
        '/professional-orders',
        $accessContextProtection
    );


    /*
    * Formulaire de création.
    */
    $router->get(
        '/professional-orders/create',
        [$professionalOrderController, 'create']
    );

    $router->middleware(
        'GET',
        '/professional-orders/create',
        $authProtection
    );

    $router->middleware(
        'GET',
        '/professional-orders/create',
        $passwordChangeProtection
    );

    $router->middleware(
        'GET',
        '/professional-orders/create',
        $accessContextProtection
    );


    /*
    * Enregistrement.
    */
    $router->post(
        '/professional-orders',
        [$professionalOrderController, 'store']
    );

    $router->middleware(
        'POST',
        '/professional-orders',
        $authProtection
    );

    $router->middleware(
        'POST',
        '/professional-orders',
        $passwordChangeProtection
    );

    $router->middleware(
        'POST',
        '/professional-orders',
        $accessContextProtection
    );

    $router->middleware(
        'POST',
        '/professional-orders',
        $csrfProtection
    );


    /*
    * Consultation.
    */
    $router->get(
        '/professional-orders/{id}',
        [$professionalOrderController, 'show']
    );

    $router->middleware(
        'GET',
        '/professional-orders/{id}',
        $authProtection
    );

    $router->middleware(
        'GET',
        '/professional-orders/{id}',
        $passwordChangeProtection
    );

    $router->middleware(
        'GET',
        '/professional-orders/{id}',
        $accessContextProtection
    );


    /*
    * Formulaire de modification.
    */
    $router->get(
        '/professional-orders/{id}/edit',
        [$professionalOrderController, 'edit']
    );

    $router->middleware(
        'GET',
        '/professional-orders/{id}/edit',
        $authProtection
    );

    $router->middleware(
        'GET',
        '/professional-orders/{id}/edit',
        $passwordChangeProtection
    );

    $router->middleware(
        'GET',
        '/professional-orders/{id}/edit',
        $accessContextProtection
    );


    /*
    * Mise à jour.
    */
    $router->post(
        '/professional-orders/{id}',
        [$professionalOrderController, 'update']
    );

    $router->middleware(
        'POST',
        '/professional-orders/{id}',
        $authProtection
    );

    $router->middleware(
        'POST',
        '/professional-orders/{id}',
        $passwordChangeProtection
    );

    $router->middleware(
        'POST',
        '/professional-orders/{id}',
        $accessContextProtection
    );

    $router->middleware(
        'POST',
        '/professional-orders/{id}',
        $csrfProtection
    );


        /*
    |--------------------------------------------------------------------------
    | Academic - Ministries
    |--------------------------------------------------------------------------
    */

    /*
    * Liste des ministères.
    */
    $router->get(
        '/ministries',
        [$ministryController, 'index']
    );

    $router->middleware(
        'GET',
        '/ministries',
        $authProtection
    );

    $router->middleware(
        'GET',
        '/ministries',
        $passwordChangeProtection
    );

    $router->middleware(
        'GET',
        '/ministries',
        $accessContextProtection
    );


    /*
    * Formulaire de création.
    */
    $router->get(
        '/ministries/create',
        [$ministryController, 'create']
    );

    $router->middleware(
        'GET',
        '/ministries/create',
        $authProtection
    );

    $router->middleware(
        'GET',
        '/ministries/create',
        $passwordChangeProtection
    );

    $router->middleware(
        'GET',
        '/ministries/create',
        $accessContextProtection
    );


    /*
    * Enregistrement.
    */
    $router->post(
        '/ministries',
        [$ministryController, 'store']
    );

    $router->middleware(
        'POST',
        '/ministries',
        $authProtection
    );

    $router->middleware(
        'POST',
        '/ministries',
        $passwordChangeProtection
    );

    $router->middleware(
        'POST',
        '/ministries',
        $accessContextProtection
    );

    $router->middleware(
        'POST',
        '/ministries',
        $csrfProtection
    );


    /*
    * Consultation.
    */
    $router->get(
        '/ministries/{id}',
        [$ministryController, 'show']
    );

    $router->middleware(
        'GET',
        '/ministries/{id}',
        $authProtection
    );

    $router->middleware(
        'GET',
        '/ministries/{id}',
        $passwordChangeProtection
    );

    $router->middleware(
        'GET',
        '/ministries/{id}',
        $accessContextProtection
    );


    /*
    * Formulaire de modification.
    */
    $router->get(
        '/ministries/{id}/edit',
        [$ministryController, 'edit']
    );

    $router->middleware(
        'GET',
        '/ministries/{id}/edit',
        $authProtection
    );

    $router->middleware(
        'GET',
        '/ministries/{id}/edit',
        $passwordChangeProtection
    );

    $router->middleware(
        'GET',
        '/ministries/{id}/edit',
        $accessContextProtection
    );


    /*
    * Mise à jour.
    */
    $router->post(
        '/ministries/{id}',
        [$ministryController, 'update']
    );

    $router->middleware(
        'POST',
        '/ministries/{id}',
        $authProtection
    );

    $router->middleware(
        'POST',
        '/ministries/{id}',
        $passwordChangeProtection
    );

    $router->middleware(
        'POST',
        '/ministries/{id}',
        $accessContextProtection
    );

    $router->middleware(
        'POST',
        '/ministries/{id}',
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

    $router->middleware(
        'GET',
        '/faculties',
        $accessContextProtection
    );

    $router->middleware(
        'GET',
        '/faculties',
        $permissionMiddleware->require(
            'faculties.view'
        )
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

    $router->middleware(
        'GET',
        '/faculties/create',
        $accessContextProtection
    );

    $router->middleware(
        'GET',
        '/faculties/create',
        $permissionMiddleware->require(
            'faculties.create'
        )
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
        $accessContextProtection
    );

    $router->middleware(
        'POST',
        '/faculties',
        $permissionMiddleware->require(
            'faculties.create'
        )
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

    $router->middleware(
        'GET',
        '/faculties/{id}',
        $accessContextProtection
    );

    $router->middleware(
        'GET',
        '/faculties/{id}',
        $permissionMiddleware->require(
            'faculties.view'
        )
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

    $router->middleware(
        'GET',
        '/faculties/{id}/edit',
        $accessContextProtection
    );

    $router->middleware(
        'GET',
        '/faculties/{id}/edit',
        $permissionMiddleware->require(
            'faculties.update'
        )
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
        $accessContextProtection
    );

    $router->middleware(
        'POST',
        '/faculties/{id}',
        $permissionMiddleware->require(
            'faculties.update'
        )
    );

    $router->middleware(
        'POST',
        '/faculties/{id}',
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
    | Academic - Students
    |--------------------------------------------------------------------------
    */

    /*
     * Liste des étudiants.
     */
    $router->get(
        '/students',
        [$studentController, 'index']
    );

    $router->middleware(
        'GET',
        '/students',
        $authProtection
    );

    $router->middleware(
        'GET',
        '/students',
        $passwordChangeProtection
    );

        $router->middleware(
        'GET',
        '/students',
        $accessContextProtection
    );

    $router->middleware(
        'GET',
        '/students',
        $permissionMiddleware->require(
            'students.view'
        )
    );


    /*
     * Formulaire de création.
     */
    $router->get(
        '/students/create',
        [$studentController, 'create']
    );

    $router->middleware(
        'GET',
        '/students/create',
        $authProtection
    );

    $router->middleware(
        'GET',
        '/students/create',
        $passwordChangeProtection
    );

        $router->middleware(
        'GET',
        '/students/create',
        $accessContextProtection
    );

    $router->middleware(
        'GET',
        '/students/create',
        $permissionMiddleware->require(
            'students.create'
        )
    );


    /*
     * Enregistrement d'un étudiant.
     */
    $router->post(
        '/students',
        [$studentController, 'store']
    );

    $router->middleware(
        'POST',
        '/students',
        $authProtection
    );

    $router->middleware(
        'POST',
        '/students',
        $passwordChangeProtection
    );

    $router->middleware(
        'POST',
        '/students',
        $csrfProtection
    );


        $router->middleware(
        'POST',
        '/students',
        $accessContextProtection
    );

    $router->middleware(
        'POST',
        '/students',
        $permissionMiddleware->require(
            'students.create'
        )
    );


    /*
     * Consultation d'un étudiant.
     */
    $router->get(
        '/students/{id}',
        [$studentController, 'show']
    );

    $router->middleware(
        'GET',
        '/students/{id}',
        $authProtection
    );

    $router->middleware(
        'GET',
        '/students/{id}',
        $passwordChangeProtection
    );


    /*
     * Formulaire de modification.
     */
    $router->get(
        '/students/{id}/edit',
        [$studentController, 'edit']
    );

    $router->middleware(
        'GET',
        '/students/{id}/edit',
        $authProtection
    );

    $router->middleware(
        'GET',
        '/students/{id}/edit',
        $passwordChangeProtection
    );


    /*
     * Mise à jour d'un étudiant.
     */
    $router->post(
        '/students/{id}',
        [$studentController, 'update']
    );

    $router->middleware(
        'POST',
        '/students/{id}',
        $authProtection
    );

    $router->middleware(
        'POST',
        '/students/{id}',
        $passwordChangeProtection
    );

    $router->middleware(
        'POST',
        '/students/{id}',
        $csrfProtection
    );

     

        /*
    |--------------------------------------------------------------------------
    | Academic - Enrollments
    |--------------------------------------------------------------------------
    */

    $router->get(
        '/academic-enrollments',
        [$academicEnrollmentController, 'index']
    );

    $router->middleware(
        'GET',
        '/academic-enrollments',
        $authProtection
    );

    $router->middleware(
        'GET',
        '/academic-enrollments',
        $passwordChangeProtection
    );


    $router->get(
        '/academic-enrollments/create',
        [$academicEnrollmentController, 'create']
    );

    $router->middleware(
        'GET',
        '/academic-enrollments/create',
        $authProtection
    );

    $router->middleware(
        'GET',
        '/academic-enrollments/create',
        $passwordChangeProtection
    );


    $router->post(
        '/academic-enrollments',
        [$academicEnrollmentController, 'store']
    );

    $router->middleware(
        'POST',
        '/academic-enrollments',
        $authProtection
    );

    $router->middleware(
        'POST',
        '/academic-enrollments',
        $passwordChangeProtection
    );

    $router->middleware(
        'POST',
        '/academic-enrollments',
        $csrfProtection
    );


    $router->get(
        '/academic-enrollments/{id}',
        [$academicEnrollmentController, 'show']
    );

    $router->middleware(
        'GET',
        '/academic-enrollments/{id}',
        $authProtection
    );

    $router->middleware(
        'GET',
        '/academic-enrollments/{id}',
        $passwordChangeProtection
    );


    $router->get(
        '/academic-enrollments/{id}/edit',
        [$academicEnrollmentController, 'edit']
    );

    $router->middleware(
        'GET',
        '/academic-enrollments/{id}/edit',
        $authProtection
    );

    $router->middleware(
        'GET',
        '/academic-enrollments/{id}/edit',
        $passwordChangeProtection
    );


    $router->post(
        '/academic-enrollments/{id}',
        [$academicEnrollmentController, 'update']
    );

    $router->middleware(
        'POST',
        '/academic-enrollments/{id}',
        $authProtection
    );

    $router->middleware(
        'POST',
        '/academic-enrollments/{id}',
        $passwordChangeProtection
    );

    $router->middleware(
        'POST',
        '/academic-enrollments/{id}',
        $csrfProtection
    );


    /*
|--------------------------------------------------------------------------
| Internships - Platform supervision
|--------------------------------------------------------------------------
*/

/*
 * Liste globale des stages.
 */
$router->get(
    '/internships',
    [$internshipController, 'index']
);

$router->middleware(
    'GET',
    '/internships',
    $authProtection
);

$router->middleware(
    'GET',
    '/internships',
    $passwordChangeProtection
);

$router->middleware(
    'GET',
    '/internships',
    $accessContextProtection
);


/*
 * Consultation d'un stage.
 */
$router->get(
    '/internships/{id}',
    [$internshipController, 'show']
);

$router->middleware(
    'GET',
    '/internships/{id}',
    $authProtection
);

$router->middleware(
    'GET',
    '/internships/{id}',
    $passwordChangeProtection
);

$router->middleware(
    'GET',
    '/internships/{id}',
    $accessContextProtection
);


/*
|--------------------------------------------------------------------------
| Finance - Payments
|--------------------------------------------------------------------------
*/

/*
 * Supervision globale des paiements.
 */
$router->get(
    '/payments',
    [$paymentController, 'index']
);

$router->middleware(
    'GET',
    '/payments',
    $authProtection
);

$router->middleware(
    'GET',
    '/payments',
    $passwordChangeProtection
);

$router->middleware(
    'GET',
    '/payments',
    $accessContextProtection
);


/*
 * Consultation d'un paiement.
 */
$router->get(
    '/payments/{id}',
    [$paymentController, 'show']
);

$router->middleware(
    'GET',
    '/payments/{id}',
    $authProtection
);

$router->middleware(
    'GET',
    '/payments/{id}',
    $passwordChangeProtection
);

$router->middleware(
    'GET',
    '/payments/{id}',
    $accessContextProtection
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