<?php

declare(strict_types=1);

namespace MedTrack\Core\Routing;

use MedTrack\Core\Http\Request;
use MedTrack\Core\Http\Response;

final class Router
{
    private array $routes = [];

    private array $middlewares = [];

    public function get(
        string $path,
        callable $handler
    ): void {
        $this->add(
            'GET',
            $path,
            $handler
        );
    }

    public function post(
        string $path,
        callable $handler
    ): void {
        $this->add(
            'POST',
            $path,
            $handler
        );
    }

    public function middleware(
        string $method,
        string $path,
        callable $middleware
    ): void {
        $method = strtoupper($method);
        $path = $this->normalize($path);

        $this->middlewares[$method][$path][] =
            $middleware;
    }

    private function add(
        string $method,
        string $path,
        callable $handler
    ): void {
        $this->routes[$method][
            $this->normalize($path)
        ] = $handler;
    }

    public function dispatch(
        Request $request
    ): never {
        $method = $request->method();

        $path = $this->normalize(
            $request->path()
        );

        $handler =
            $this->routes[$method][$path]
            ?? null;

        if ($handler === null) {
            Response::json(
                [
                    'status' => 'error',
                    'message' => 'Route not found.',
                ],
                404
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Middleware pipeline
        |--------------------------------------------------------------------------
        */

        $middlewares =
            $this->middlewares[$method][$path]
            ?? [];

        $pipeline = array_reduce(
            array_reverse($middlewares),

            static function (
                callable $next,
                callable $middleware
            ): callable {
                return static function (
                    Request $request
                ) use (
                    $middleware,
                    $next
                ): mixed {
                    return $middleware(
                        $request,
                        $next
                    );
                };
            },

            static function (
                Request $request
            ) use ($handler): mixed {
                return $handler($request);
            }
        );

        $result = $pipeline($request);


        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        if (is_array($result)) {
            Response::json($result);
        }

        if (is_string($result)) {
            Response::html($result);
        }

        Response::json(
            [
                'status' => 'error',
                'message' => 'Invalid response.',
            ],
            500
        );
    }

    private function normalize(
        string $path
    ): string {
        $path =
            '/' . trim($path, '/');

        return $path === '/'
            ? '/'
            : rtrim($path, '/');
    }
}