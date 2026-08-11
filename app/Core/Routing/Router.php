<?php

declare(strict_types=1);

namespace MedTrack\Core\Routing;

use MedTrack\Core\Http\Request;
use MedTrack\Core\Http\Response;

final class Router
{
    private array $routes = [];

    public function get(string $path, callable $handler): void
    {
        $this->add('GET', $path, $handler);
    }

    public function post(string $path, callable $handler): void
    {
        $this->add('POST', $path, $handler);
    }

    private function add(
        string $method,
        string $path,
        callable $handler
    ): void {
        $this->routes[$method][$this->normalize($path)] = $handler;
    }

    public function dispatch(Request $request): never
    {
        $method = $request->method();
        $path = $this->normalize($request->path());

        $handler = $this->routes[$method][$path] ?? null;

        if ($handler === null) {
            Response::json([
                'status' => 'error',
                'message' => 'Route not found.',
            ], 404);
        }

        $result = $handler($request);

        if (is_array($result)) {
            Response::json($result);
        }

        if (is_string($result)) {
            Response::html($result);
        }

        Response::json([
            'status' => 'error',
            'message' => 'Invalid response.',
        ], 500);
    }

    private function normalize(string $path): string
    {
        $path = '/' . trim($path, '/');

        return $path === '/' ? '/' : rtrim($path, '/');
    }
}
