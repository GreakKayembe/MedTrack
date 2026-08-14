<?php

declare(strict_types=1);

use Dotenv\Dotenv;
use MedTrack\Core\Application;
use MedTrack\Core\Auth\Session;
use MedTrack\Core\Config;
use MedTrack\Core\Database\Database;
use MedTrack\Core\Exceptions\ExceptionHandler;
use MedTrack\Core\Http\Middleware\AuthMiddleware;
use MedTrack\Core\Http\Middleware\CsrfMiddleware;
use MedTrack\Core\Http\Middleware\GuestMiddleware;
use MedTrack\Core\Http\Middleware\PasswordChangeMiddleware;
use MedTrack\Core\Http\View;
use MedTrack\Core\Routing\Router;
use MedTrack\Core\Security\Csrf;
use MedTrack\Core\Security\RateLimit\RateLimiter;
use MedTrack\Core\Security\RateLimit\RateLimitRepository;

use MedTrack\Modules\Academic\Controllers\AcademicProgramController;
use MedTrack\Modules\Academic\Controllers\AcademicYearController;
use MedTrack\Modules\Academic\Controllers\CohortController;
use MedTrack\Modules\Academic\Controllers\FacultyController;
use MedTrack\Modules\Academic\Controllers\StudyLevelController;
use MedTrack\Modules\Academic\Controllers\UniversityController;
use MedTrack\Modules\Academic\Repositories\AcademicProgramRepository;
use MedTrack\Modules\Academic\Repositories\AcademicYearRepository;
use MedTrack\Modules\Academic\Repositories\CohortRepository;
use MedTrack\Modules\Academic\Repositories\FacultyRepository;
use MedTrack\Modules\Academic\Repositories\StudyLevelRepository;
use MedTrack\Modules\Academic\Repositories\UniversityRepository;
use MedTrack\Modules\Academic\Services\AcademicProgramService;
use MedTrack\Modules\Academic\Services\AcademicYearService;
use MedTrack\Modules\Academic\Services\CohortService;
use MedTrack\Modules\Academic\Services\FacultyService;
use MedTrack\Modules\Academic\Services\StudyLevelService;
use MedTrack\Modules\Academic\Services\UniversityService;

use MedTrack\Modules\Identity\Controllers\AuthController;
use MedTrack\Modules\Identity\Controllers\PasswordChangeController;
use MedTrack\Modules\Identity\Controllers\PasswordResetController;
use MedTrack\Modules\Identity\Repositories\LoginHistoryRepository;
use MedTrack\Modules\Identity\Repositories\PasswordResetRepository;
use MedTrack\Modules\Identity\Repositories\UserRepository;
use MedTrack\Modules\Identity\Services\AuthService;
use MedTrack\Modules\Identity\Services\PasswordChangeService;
use MedTrack\Modules\Identity\Services\PasswordResetService;

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

Dotenv::createImmutable($root)->safeLoad();


/*
|--------------------------------------------------------------------------
| Configuration
|--------------------------------------------------------------------------
*/

$appConfig = new Config(
    require $root . '/config/app.php'
);

$databaseConfig = require $root . '/config/database.php';


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

$csrfMiddleware = new CsrfMiddleware(
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

$userRepository = new UserRepository(
    $database->connection()
);

$loginHistoryRepository = new LoginHistoryRepository(
    $database->connection()
);

$passwordResetRepository = new PasswordResetRepository(
    $database->connection()
);


/*
|--------------------------------------------------------------------------
| Authentication service
|--------------------------------------------------------------------------
*/

$authService = new AuthService(
    $userRepository,
    $session
);


/*
|--------------------------------------------------------------------------
| Rate limiting
|--------------------------------------------------------------------------
*/

$rateLimitRepository = new RateLimitRepository(
    $database->connection()
);

$rateLimiter = new RateLimiter(
    $rateLimitRepository
);


/*
|--------------------------------------------------------------------------
| Password change
|--------------------------------------------------------------------------
*/

$passwordChangeService = new PasswordChangeService(
    $userRepository
);

$passwordChangeController = new PasswordChangeController(
    $authService,
    $passwordChangeService,
    $view
);


/*
|--------------------------------------------------------------------------
| Authentication middleware
|--------------------------------------------------------------------------
*/

$authMiddleware = new AuthMiddleware(
    $authService
);

$guestMiddleware = new GuestMiddleware(
    $authService
);

$passwordChangeMiddleware = new PasswordChangeMiddleware(
    $authService
);


/*
|--------------------------------------------------------------------------
| Authentication controller
|--------------------------------------------------------------------------
*/

$authController = new AuthController(
    $authService,
    $view,
    $rateLimiter,
    $loginHistoryRepository
);


/*
|--------------------------------------------------------------------------
| Password recovery
|--------------------------------------------------------------------------
*/

$passwordResetService = new PasswordResetService(
    $userRepository,
    $passwordResetRepository
);

$passwordResetController = new PasswordResetController(
    $passwordResetService,
    $view,
    $rateLimiter
);


/*
|--------------------------------------------------------------------------
| Academic repositories
|--------------------------------------------------------------------------
*/

$universityRepository = new UniversityRepository(
    $database->connection()
);

$facultyRepository = new FacultyRepository(
    $database->connection()
);

$academicProgramRepository = new AcademicProgramRepository(
    $database->connection()
);

$academicYearRepository = new AcademicYearRepository(
    $database->connection()
);

$studyLevelRepository = new StudyLevelRepository(
    $database->connection()
);

$cohortRepository = new CohortRepository(
    $database->connection()
);


/*
|--------------------------------------------------------------------------
| Academic services
|--------------------------------------------------------------------------
*/

$universityService = new UniversityService(
    $universityRepository
);

$facultyService = new FacultyService(
    $facultyRepository
);

$academicProgramService = new AcademicProgramService(
    $academicProgramRepository
);

$academicYearService = new AcademicYearService(
    $academicYearRepository
);

$studyLevelService = new StudyLevelService(
    $studyLevelRepository
);

$cohortService = new CohortService(
    $cohortRepository
);


/*
|--------------------------------------------------------------------------
| Academic controllers
|--------------------------------------------------------------------------
*/

$universityController = new UniversityController(
    $universityService,
    $view
);

$facultyController = new FacultyController(
    $facultyService,
    $universityService,
    $view
);

$academicProgramController = new AcademicProgramController(
    $academicProgramService,
    $universityService,
    $facultyService,
    $view
);

$academicYearController = new AcademicYearController(
    $academicYearService,
    $view
);

$studyLevelController = new StudyLevelController(
    $studyLevelService,
    $view
);

$cohortController = new CohortController(
    $cohortService,
    $academicProgramService,
    $academicYearService,
    $view
);


/*
|--------------------------------------------------------------------------
| Logging
|--------------------------------------------------------------------------
*/

$logger = new Logger(
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

$exceptionHandler = new ExceptionHandler(
    $logger,
    (bool) $appConfig->get(
        'debug',
        false
    )
);

set_exception_handler(
    static function (
        Throwable $exception
    ) use ($exceptionHandler): void {
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

$registerRoutes = require $root . '/routes/web.php';

$registerRoutes(
    $router,
    $database,
    $view,
    $authController,
    $passwordResetController,
    $passwordChangeController,
    $universityController,
    $facultyController,
    $academicProgramController,
    $academicYearController,
    $studyLevelController,
    $cohortController,
    $csrfMiddleware,
    $authMiddleware,
    $guestMiddleware,
    $passwordChangeMiddleware
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