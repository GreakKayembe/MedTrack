<?php

declare(strict_types=1);

use Dotenv\Dotenv;
use MedTrack\Core\Application;
use MedTrack\Core\Config;
use MedTrack\Core\Database\Database;
use MedTrack\Core\Exceptions\ExceptionHandler;
use MedTrack\Core\Http\View;
use MedTrack\Core\Routing\Router;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;

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

$database = new Database($databaseConfig);

$router = new Router();

$view = new View(
    $root . '/resources/views'
);

/*
|--------------------------------------------------------------------------
| Logging
|--------------------------------------------------------------------------
*/

$logger = new Logger('medtrack');

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
    (bool) $appConfig->get('debug', false)
);

set_exception_handler(
    static function (Throwable $exception) use ($exceptionHandler): void {
        $exceptionHandler->handle($exception);
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
    $view
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