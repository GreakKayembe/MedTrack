<?php

declare(strict_types=1);

use Dotenv\Dotenv;
use MedTrack\Core\Application;
use MedTrack\Core\Auth\Session;
use MedTrack\Core\Config;
use MedTrack\Core\Context\AccessContextResolver;
use MedTrack\Core\Database\Database;
use MedTrack\Core\Exceptions\ExceptionHandler;
use MedTrack\Core\Http\Middleware\AccessContextMiddleware;
use MedTrack\Core\Http\Middleware\AuthMiddleware;
use MedTrack\Core\Http\Middleware\CsrfMiddleware;
use MedTrack\Core\Http\Middleware\GuestMiddleware;
use MedTrack\Core\Http\Middleware\PasswordChangeMiddleware;
use MedTrack\Core\Http\Middleware\PermissionMiddleware;
use MedTrack\Core\Http\View;
use MedTrack\Core\Routing\Router;
use MedTrack\Core\Security\Csrf;
use MedTrack\Core\Security\RateLimit\RateLimiter;
use MedTrack\Core\Security\RateLimit\RateLimitRepository;

use MedTrack\Modules\Dashboard\Controllers\DashboardController;
use MedTrack\Modules\Dashboard\Repositories\DashboardRepository;
use MedTrack\Modules\Dashboard\Services\DashboardService;

use MedTrack\Modules\Academic\Controllers\AcademicEnrollmentController;
use MedTrack\Modules\Academic\Controllers\AcademicProgramController;
use MedTrack\Modules\Academic\Controllers\AcademicYearController;
use MedTrack\Modules\Academic\Controllers\CohortController;
use MedTrack\Modules\Academic\Controllers\FacultyController;
use MedTrack\Modules\Academic\Controllers\StudentController;
use MedTrack\Modules\Academic\Controllers\StudyLevelController;
use MedTrack\Modules\Academic\Controllers\UniversityController;
use MedTrack\Modules\Academic\Controllers\MinistryController;
use MedTrack\Modules\Academic\Controllers\ProfessionalOrderController;
use MedTrack\Modules\Internship\Controllers\InternshipController;

use MedTrack\Modules\Academic\Repositories\AcademicEnrollmentRepository;
use MedTrack\Modules\Academic\Repositories\AcademicProgramRepository;
use MedTrack\Modules\Academic\Repositories\AcademicYearRepository;
use MedTrack\Modules\Academic\Repositories\CohortRepository;
use MedTrack\Modules\Academic\Repositories\FacultyRepository;
use MedTrack\Modules\Academic\Repositories\StudentRepository;
use MedTrack\Modules\Academic\Repositories\StudyLevelRepository;
use MedTrack\Modules\Academic\Repositories\UniversityRepository;
use MedTrack\Modules\Academic\Repositories\ProfessionalOrderRepository;
use MedTrack\Modules\Academic\Repositories\MinistryRepository;
use MedTrack\Modules\Internship\Repositories\InternshipRepository;
use MedTrack\Modules\Identity\Repositories\UserManagementRepository;
use MedTrack\Modules\Identity\Controllers\RoleManagementController;
use MedTrack\Modules\Identity\Repositories\RoleManagementRepository;
use MedTrack\Modules\Identity\Services\RoleManagementService;
use MedTrack\Modules\Audit\Services\AuditRecorder;



use MedTrack\Modules\Identity\Services\UserManagementService;
use MedTrack\Modules\Academic\Services\ProfessionalOrderService;
use MedTrack\Modules\Academic\Services\AcademicEnrollmentService;
use MedTrack\Modules\Academic\Services\AcademicProgramService;
use MedTrack\Modules\Academic\Services\AcademicYearService;
use MedTrack\Modules\Academic\Services\CohortService;
use MedTrack\Modules\Academic\Services\FacultyService;
use MedTrack\Modules\Academic\Services\StudentService;
use MedTrack\Modules\Academic\Services\StudyLevelService;
use MedTrack\Modules\Academic\Services\UniversityService;
use MedTrack\Modules\Academic\Controllers\HospitalController;
use MedTrack\Modules\Academic\Repositories\HospitalRepository;
use MedTrack\Modules\Academic\Services\HospitalService;
use MedTrack\Modules\Academic\Services\MinistryService;
use MedTrack\Modules\Internship\Services\InternshipService;
use MedTrack\Modules\Identity\Services\AuthService;
use MedTrack\Modules\Identity\Services\AuthorizationService;
use MedTrack\Modules\Identity\Services\PasswordChangeService;
use MedTrack\Modules\Identity\Services\PasswordResetService;
use MedTrack\Modules\Audit\Controllers\AuditController;
use MedTrack\Modules\Audit\Repositories\AuditRepository;



use MedTrack\Modules\Identity\Controllers\AuthController;
use MedTrack\Modules\Identity\Controllers\PasswordChangeController;
use MedTrack\Modules\Identity\Controllers\PasswordResetController;
use MedTrack\Modules\Identity\Controllers\UserManagementController;
use MedTrack\Modules\Identity\Repositories\LoginHistoryRepository;
use MedTrack\Modules\Identity\Repositories\OrganizationMembershipRepository;
use MedTrack\Modules\Identity\Repositories\PasswordResetRepository;
use MedTrack\Modules\Identity\Repositories\PlatformAccessRepository;
use MedTrack\Modules\Identity\Repositories\UserRepository;
use MedTrack\Modules\Finance\Controllers\PaymentController;
use MedTrack\Modules\Finance\Repositories\PaymentRepository;
use MedTrack\Modules\Finance\Services\PaymentService;
use MedTrack\Modules\Audit\Services\AuditService;

use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;


/*
|--------------------------------------------------------------------------
| Root path
|--------------------------------------------------------------------------
*/

$root = dirname(__DIR__);


/*
|--------------------------------------------------------------------------
| Environment
|--------------------------------------------------------------------------
*/

Dotenv::createImmutable(
    $root
)->safeLoad();


/*
|--------------------------------------------------------------------------
| Configuration
|--------------------------------------------------------------------------
*/

$appConfig = new Config(
    require $root . '/config/app.php'
);

$databaseConfig =
    require $root . '/config/database.php';


/*
|--------------------------------------------------------------------------
| Core services
|--------------------------------------------------------------------------
*/

$database = new Database(
    $databaseConfig
);

$router = new Router();

$view = new View(
    $root . '/resources/views'
);


/*
|--------------------------------------------------------------------------
| Session
|--------------------------------------------------------------------------
*/

$session = new Session();

$session->start();


/*
|--------------------------------------------------------------------------
| CSRF protection
|--------------------------------------------------------------------------
*/

$csrf = new Csrf(
    $session
);

$csrfMiddleware =
    new CsrfMiddleware(
        $csrf
    );

$view->share(
    'csrfToken',
    $csrf->token()
);


/*
|--------------------------------------------------------------------------
| Identity repositories
|--------------------------------------------------------------------------
*/

$userRepository =
    new UserRepository(
        $database->connection()
    );

$loginHistoryRepository =
    new LoginHistoryRepository(
        $database->connection()
    );

$passwordResetRepository =
    new PasswordResetRepository(
        $database->connection()
    );

$organizationMembershipRepository =
    new OrganizationMembershipRepository(
        $database->connection()
    );

$platformAccessRepository =
    new PlatformAccessRepository(
        $database->connection()
    );

$userManagementRepository =
    new UserManagementRepository(
        $database->connection()
    );


$roleManagementRepository =
    new RoleManagementRepository(
        $database->connection()
    );

    $auditRepository =
    new AuditRepository(
        $database->connection()
    );

$auditRecorder =
    new AuditRecorder(
        $auditRepository
    );

$auditService =
    new AuditService(
        $auditRepository
    );

$auditController =
    new AuditController(
        $auditService,
        $view
    );


/*
|--------------------------------------------------------------------------
| Authentication service
|--------------------------------------------------------------------------
*/

$authService =
    new AuthService(
        $userRepository,
        $session
    );
$userManagementService =
    new UserManagementService(
        $userManagementRepository,
        $auditRecorder,
        $session
    );

$roleManagementService =
    new RoleManagementService(
        $roleManagementRepository,
        $auditRecorder,
        $session
    );

/*
|--------------------------------------------------------------------------
| Rate limiting
|--------------------------------------------------------------------------
*/

$rateLimitRepository =
    new RateLimitRepository(
        $database->connection()
    );

$rateLimiter =
    new RateLimiter(
        $rateLimitRepository
    );


/*
|--------------------------------------------------------------------------
| Password change
|--------------------------------------------------------------------------
*/

$passwordChangeService =
    new PasswordChangeService(
        $userRepository
    );

$passwordChangeController =
    new PasswordChangeController(
        $authService,
        $passwordChangeService,
        $view
    );


/*
|--------------------------------------------------------------------------
| Authentication middleware
|--------------------------------------------------------------------------
*/

$authMiddleware =
    new AuthMiddleware(
        $authService
    );

$guestMiddleware =
    new GuestMiddleware(
        $authService
    );

$passwordChangeMiddleware =
    new PasswordChangeMiddleware(
        $authService
    );


/*
|--------------------------------------------------------------------------
| Authentication controller
|--------------------------------------------------------------------------
*/

$authController =
    new AuthController(
        $authService,
        $view,
        $rateLimiter,
        $loginHistoryRepository
    );


$userManagementController =
    new UserManagementController(
        $userManagementService,
        $view
    );


$roleManagementController =
    new RoleManagementController(
        $roleManagementService,
        $view
    );



/*
|--------------------------------------------------------------------------
| Password recovery
|--------------------------------------------------------------------------
*/

$passwordResetService =
    new PasswordResetService(
        $userRepository,
        $passwordResetRepository
    );

$passwordResetController =
    new PasswordResetController(
        $passwordResetService,
        $view,
        $rateLimiter
    );


/*
|--------------------------------------------------------------------------
| Academic repositories
|--------------------------------------------------------------------------
*/

$universityRepository =
    new UniversityRepository(
        $database->connection()
    );

$hospitalRepository =
    new HospitalRepository(
        $database->connection()
    );


$professionalOrderRepository =
    new ProfessionalOrderRepository(
        $database->connection()
    );

$ministryRepository =
    new MinistryRepository(
        $database->connection()
    );

$facultyRepository =
    new FacultyRepository(
        $database->connection()
    );

$academicProgramRepository =
    new AcademicProgramRepository(
        $database->connection()
    );

$academicYearRepository =
    new AcademicYearRepository(
        $database->connection()
    );

$studyLevelRepository =
    new StudyLevelRepository(
        $database->connection()
    );

$cohortRepository =
    new CohortRepository(
        $database->connection()
    );

$studentRepository =
    new StudentRepository(
        $database->connection()
    );

$academicEnrollmentRepository =
    new AcademicEnrollmentRepository(
        $database->connection()
    );



/*
|--------------------------------------------------------------------------
| Finance repositories
|--------------------------------------------------------------------------
*/

$paymentRepository =
    new PaymentRepository(
        $database->connection()
    );


/*
|--------------------------------------------------------------------------
| Finance services
|--------------------------------------------------------------------------
*/

$paymentService =
    new PaymentService(
        $paymentRepository
    );



/*
|--------------------------------------------------------------------------
| Finance controllers
|--------------------------------------------------------------------------
*/

$paymentController =
    new PaymentController(
        $paymentService,
        $view
    );


/*
|--------------------------------------------------------------------------
| Access context and authorization
|--------------------------------------------------------------------------
*/

$accessContextResolver =
    new AccessContextResolver(
        $authService,
        $session,
        $platformAccessRepository,
        $organizationMembershipRepository,
        $studentRepository
    );

$authorizationService =
    new AuthorizationService(
        $platformAccessRepository,
        $organizationMembershipRepository
    );

$accessContextMiddleware =
    new AccessContextMiddleware(
        $accessContextResolver
    );

$permissionMiddleware =
    new PermissionMiddleware(
        $accessContextResolver,
        $authorizationService
    );


    /*
|--------------------------------------------------------------------------
| Shared authenticated view context
|--------------------------------------------------------------------------
*/

$currentUser =
    null;

$currentAccess =
    [
        'scope' => null,
        'label' => 'MedTrack',
        'organization_id' => null,
        'organization_name' => null,
        'organization_type' => null,
        'student_id' => null,
        'roles' => [],
        'permissions' => [],
        'display_name' => 'Utilisateur',
    ];

if ($authService->check()) {
    try {
        $currentUser =
            $authService->user();

        $context =
            $accessContextResolver
                ->resolve();

        /*
        |--------------------------------------------------------------------------
        | Platform
        |--------------------------------------------------------------------------
        */

        if ($context->isPlatform()) {
            $roles =
                $platformAccessRepository
                    ->rolesForUser(
                        $context->userId()
                    );

            $permissions =
                $platformAccessRepository
                    ->permissionsForUser(
                        $context->userId()
                    );

            $currentAccess = [
                'scope' =>
                    'PLATFORM',

                'label' =>
                    'Administration MedTrack',

                'organization_id' =>
                    null,

                'organization_name' =>
                    null,

                'organization_type' =>
                    null,

                'student_id' =>
                    null,

                'roles' =>
                    $roles,

                'permissions' =>
                    $permissions,

                'display_name' =>
                    (string) (
                        $currentUser['email']
                        ?? $currentUser['phone']
                        ?? 'Administrateur MedTrack'
                    ),
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Organization
        |--------------------------------------------------------------------------
        */

        elseif ($context->isOrganization()) {
            $membership =
                $organizationMembershipRepository
                    ->findActiveMembership(
                        $context->membershipId(),
                        $context->userId()
                    );

            $roles =
                $organizationMembershipRepository
                    ->rolesForMembership(
                        $context->membershipId()
                    );

            $permissions =
                $organizationMembershipRepository
                    ->permissionsForMembership(
                        $context->membershipId()
                    );

            $currentAccess = [
                'scope' =>
                    'ORGANIZATION',

                'label' =>
                    match (
                        $context->organizationType()
                    ) {
                        'UNIVERSITY' =>
                            'Espace Université',

                        'HOSPITAL' =>
                            'Espace Hôpital',

                        'PROFESSIONAL_ORDER' =>
                            'Espace Ordre professionnel',

                        'MINISTRY' =>
                            'Espace Ministère',

                        default =>
                            'Espace institutionnel',
                    },

                'organization_id' =>
                    $context->organizationId(),

                'organization_name' =>
                    $membership['organization_name']
                    ?? null,

                'organization_type' =>
                    $context->organizationType(),

                'student_id' =>
                    null,

                'roles' =>
                    $roles,

                'permissions' =>
                    $permissions,

                'display_name' =>
                    (string) (
                        $currentUser['email']
                        ?? $currentUser['phone']
                        ?? 'Utilisateur'
                    ),
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Student
        |--------------------------------------------------------------------------
        */

        elseif ($context->isStudent()) {
            $student =
                $studentRepository
                    ->findById(
                        $context->studentId()
                    );

            $nameParts = [
                trim(
                    (string) (
                        $student['first_name']
                        ?? ''
                    )
                ),

                trim(
                    (string) (
                        $student['middle_name']
                        ?? ''
                    )
                ),

                trim(
                    (string) (
                        $student['last_name']
                        ?? ''
                    )
                ),
            ];

            $nameParts =
                array_values(
                    array_filter(
                        $nameParts,
                        static fn (
                            string $value
                        ): bool => $value !== ''
                    )
                );

            $currentAccess = [
                'scope' =>
                    'STUDENT',

                'label' =>
                    'Espace Étudiant',

                'organization_id' =>
                    null,

                'organization_name' =>
                    null,

                'organization_type' =>
                    null,

                'student_id' =>
                    $context->studentId(),

                'roles' =>
                    [],

                'permissions' =>
                    [],

                'display_name' =>
                    $nameParts !== []
                        ? implode(' ', $nameParts)
                        : (
                            $currentUser['email']
                            ?? 'Étudiant'
                        ),
            ];
        }
    } catch (Throwable) {
        /*
         * Les middlewares restent responsables
         * du refus d'accès.
         *
         * Le rendu d'une page publique ne doit
         * jamais échouer à cause du contexte UI.
         */
    }
}

$view->share(
    'currentUser',
    $currentUser
);

$view->share(
    'currentAccess',
    $currentAccess
);


/*

|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

$dashboardRepository =
    new DashboardRepository(
        $database->connection()
    );

$dashboardService =
    new DashboardService(
        $dashboardRepository
    );

$dashboardController =
    new DashboardController(
        $accessContextResolver,
        $dashboardService,
        $view
    );


/*
|--------------------------------------------------------------------------
| Academic services
|--------------------------------------------------------------------------
*/

$universityService =
    new UniversityService(
        $universityRepository
    );

$hospitalService =
    new HospitalService(
        $hospitalRepository
    );

$professionalOrderService =
    new ProfessionalOrderService(
        $professionalOrderRepository
    );


$ministryService =
    new MinistryService(
        $ministryRepository
    );



$facultyService =
    new FacultyService(
        $facultyRepository,
        $accessContextResolver
    );

$academicProgramService =
    new AcademicProgramService(
        $academicProgramRepository
    );

$academicYearService =
    new AcademicYearService(
        $academicYearRepository,
        $accessContextResolver
    );

$studyLevelService =
    new StudyLevelService(
        $studyLevelRepository
    );

$cohortService =
    new CohortService(
        $cohortRepository
    );

$studentService =
    new StudentService(
        $studentRepository
    );

$academicEnrollmentService =
    new AcademicEnrollmentService(
        $academicEnrollmentRepository,
        $database->connection()
    );


/*
|--------------------------------------------------------------------------
| Academic controllers
|--------------------------------------------------------------------------
*/

$universityController =
    new UniversityController(
        $universityService,
        $view
    );

$hospitalController =
    new HospitalController(
        $hospitalService,
        $view
    );


$professionalOrderController =
    new ProfessionalOrderController(
        $professionalOrderService,
        $view
    );


$ministryController =
    new MinistryController(
        $ministryService,
        $view
    );


$facultyController =
    new FacultyController(
        $facultyService,
        $universityService,
        $accessContextResolver,
        $view
    );

$academicProgramController =
    new AcademicProgramController(
        $academicProgramService,
        $universityService,
        $facultyService,
        $view
    );


$academicYearController =
    new AcademicYearController(
        $academicYearService,
        $accessContextResolver,
        $view
    );

$studyLevelController =
    new StudyLevelController(
        $studyLevelService,
        $view
    );

$cohortController =
    new CohortController(
        $cohortService,
        $academicProgramService,
        $academicYearService,
        $view
    );

$studentController =
    new StudentController(
        $studentService,
        $academicEnrollmentService,
        $accessContextResolver,
        $view
    );

$academicEnrollmentController =
    new AcademicEnrollmentController(
        $academicEnrollmentService,
        $studentService,
        $universityService,
        $academicProgramService,
        $academicYearService,
        $studyLevelService,
        $cohortService,
        $view
    );


/*
|--------------------------------------------------------------------------
| Internship repositories
|--------------------------------------------------------------------------
*/



    $internshipRepository =
    new InternshipRepository(
        $database->connection()
    );


/*
|--------------------------------------------------------------------------
| Internship service
|--------------------------------------------------------------------------
*/


    $internshipService =
        new InternshipService(
            $internshipRepository
        );


/*
|--------------------------------------------------------------------------
| Internship controllers
|--------------------------------------------------------------------------
*/


    $internshipController =
        new InternshipController(
            $internshipService,
            $view
        );


/*

*
|--------------------------------------------------------------------------
| Audit Repositories
|--------------------------------------------------------------------------
*/

$auditRepository =
    new AuditRepository(
        $database->connection()
    );


/*
*
|--------------------------------------------------------------------------
| Audit Services 
|--------------------------------------------------------------------------
*/

$auditService =
    new AuditService(
        $auditRepository
    );



/*
*
|--------------------------------------------------------------------------
| Audit Controllers
|--------------------------------------------------------------------------
*/


$auditController =
    new AuditController(
        $auditService,
        $view
    );



/*

*
|--------------------------------------------------------------------------
| Logging
|--------------------------------------------------------------------------
*/

$logger =
    new Logger(
        'medtrack'
    );

$logger->pushHandler(
    new StreamHandler(
        $root . '/storage/logs/medtrack.log',
        Level::Debug
    )
);


/*
|--------------------------------------------------------------------------
| Exception handling
|--------------------------------------------------------------------------
*/

$exceptionHandler =
    new ExceptionHandler(
        $logger,
        (bool) $appConfig->get(
            'debug',
            false
        )
    );

set_exception_handler(
    static function (
        Throwable $exception
    ) use (
        $exceptionHandler
    ): void {
        $exceptionHandler->handle(
            $exception
        );
    }
);


/*
|--------------------------------------------------------------------------
| Routes
|--------------------------------------------------------------------------
*/

$registerRoutes =
    require $root . '/routes/web.php';
$registerRoutes(
    $router,
    $database,
    $view,
    $authController,
    $passwordResetController,
    $passwordChangeController,
    $dashboardController,

    $universityController,
    $hospitalController,
    $professionalOrderController,
    $ministryController,
    

    $facultyController,
    $academicProgramController,
    $academicYearController,
    $studyLevelController,
    $cohortController,
    $studentController,

    $internshipController,
    $paymentController,
    $userManagementController,
    $roleManagementController,
    $auditController,
    $academicEnrollmentController,

    $csrfMiddleware,
    $authMiddleware,
    $guestMiddleware,
    $passwordChangeMiddleware,
    $accessContextMiddleware,
    $permissionMiddleware
);

/*
|--------------------------------------------------------------------------
| Application
|--------------------------------------------------------------------------
*/

return new Application(
    $router,
    $database
);