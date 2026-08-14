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
        $method = strtoupper($method);

        $path = $this->normalize($path);

        $this->routes[$method][$path] =
            $handler;
    }

    public function dispatch(
        Request $request
    ): never {
        $method = $request->method();

        $path = $this->normalize(
            $request->path()
        );

        $matchedRoute = $this->matchRoute(
            $method,
            $path
        );

        if ($matchedRoute === null) {
            Response::json(
                [
                    'status' => 'error',
                    'message' => 'Route not found.',
                ],
                404
            );
        }

        $routePath =
            $matchedRoute['route'];

        $handler =
            $matchedRoute['handler'];

        $attributes =
            $matchedRoute['attributes'];

        /*
        |--------------------------------------------------------------------------
        | Route attributes
        |--------------------------------------------------------------------------
        */

        $request->setRouteAttributes(
            $attributes
        );

        /*
        |--------------------------------------------------------------------------
        | Middleware pipeline
        |--------------------------------------------------------------------------
        */

        $middlewares =
            $this->middlewares[$method][$routePath]
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

        $result = $pipeline(
            $request
        );

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        if (is_array($result)) {
            Response::json(
                $result
            );
        }

        if (is_string($result)) {
            Response::html(
                $result
            );
        }

        Response::json(
            [
                'status' => 'error',
                'message' => 'Invalid response.',
            ],
            500
        );
    }

    /**
     * Recherche une route correspondant à la requête.
     *
     * Supporte :
     *
     * /universities
     * /universities/{id}
     * /universities/{id}/edit
     */
    private function matchRoute(
        string $method,
        string $requestPath
    ): ?array {
        $routes =
            $this->routes[$method]
            ?? [];

        /*
         * Recherche exacte en priorité.
         */
        if (
            array_key_exists(
                $requestPath,
                $routes
            )
        ) {
            return [
                'route' => $requestPath,
                'handler' => $routes[$requestPath],
                'attributes' => [],
            ];
        }

        /*
         * Recherche des routes dynamiques.
         */
        foreach (
            $routes as
            $routePath => $handler
        ) {
            if (
                !str_contains(
                    $routePath,
                    '{'
                )
            ) {
                continue;
            }

            $attributes =
                $this->matchDynamicRoute(
                    $routePath,
                    $requestPath
                );

            if ($attributes === null) {
                continue;
            }

            return [
                'route' => $routePath,
                'handler' => $handler,
                'attributes' => $attributes,
            ];
        }

        return null;
    }

    /**
     * Compare une route dynamique avec l'URL demandée.
     */
    private function matchDynamicRoute(
        string $routePath,
        string $requestPath
    ): ?array {
        $routeSegments =
            $this->segments(
                $routePath
            );

        $requestSegments =
            $this->segments(
                $requestPath
            );

        if (
            count($routeSegments)
            !== count($requestSegments)
        ) {
            return null;
        }

        $attributes = [];

        foreach (
            $routeSegments as
            $index => $routeSegment
        ) {
            $requestSegment =
                $requestSegments[$index];

            if (
                $this->isParameter(
                    $routeSegment
                )
            ) {
                $parameterName =
                    substr(
                        $routeSegment,
                        1,
                        -1
                    );

                if (
                    $parameterName === ''
                    || $requestSegment === ''
                ) {
                    return null;
                }

                $attributes[$parameterName] =
                    rawurldecode(
                        $requestSegment
                    );

                continue;
            }

            if (
                $routeSegment
                !== $requestSegment
            ) {
                return null;
            }
        }

        return $attributes;
    }

    /**
     * Détermine si un segment représente
     * un paramètre dynamique.
     */
    private function isParameter(
        string $segment
    ): bool {
        return strlen($segment) >= 3
            && $segment[0] === '{'
            && $segment[
                strlen($segment) - 1
            ] === '}';
    }

    /**
     * Découpe une route en segments.
     */
    private function segments(
        string $path
    ): array {
        $path = trim(
            $path,
            '/'
        );

        if ($path === '') {
            return [];
        }

        return explode(
            '/',
            $path
        );
    }

    private function normalize(
        string $path
    ): string {
        $path =
            '/' . trim(
                $path,
                '/'
            );

        return $path === '/'
            ? '/'
            : rtrim(
                $path,
                '/'
            );
    }
}