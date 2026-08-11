<?php

declare(strict_types=1);

namespace MedTrack\Core;

use MedTrack\Core\Database\Database;
use MedTrack\Core\Http\Request;
use MedTrack\Core\Routing\Router;

final class Application
{
    public function __construct(
        private readonly Router $router,
        private readonly Database $database
    ) {
    }

    public function run(): never
    {
        $request = Request::capture();

        $this->router->dispatch($request);
    }

    public function database(): Database
    {
        return $this->database;
    }

    public function router(): Router
    {
        return $this->router;
    }
}
