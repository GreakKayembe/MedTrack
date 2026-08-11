<?php

declare(strict_types=1);

use MedTrack\Core\Application;

require dirname(__DIR__) . '/vendor/autoload.php';

/** @var Application $app */
$app = require dirname(__DIR__) . '/bootstrap/app.php';

$app->run();