<?php

declare(strict_types=1);

namespace MedTrack\Core\Http\Middleware;

use MedTrack\Core\Context\AccessContextResolver;
use MedTrack\Core\Http\Request;
use MedTrack\Core\Http\Response;
use MedTrack\Modules\Identity\Services\AuthorizationService;
use RuntimeException;

final class PermissionMiddleware
{
    public function __construct(
        private readonly AccessContextResolver $contextResolver,
        private readonly AuthorizationService $authorization
    ) {
    }

    /**
     * Construit un middleware exigeant une permission précise.
     *
     * Exemple :
     *
     * $router->middleware(
     *     'GET',
     *     '/students',
     *     $permissionMiddleware->require('students.view')
     * );
     */
    public function require(
        string $permissionCode
    ): callable {
        $permissionCode =
            trim(
                $permissionCode
            );

        if ($permissionCode === '') {
            throw new RuntimeException(
                'Le code de permission ne peut pas être vide.'
            );
        }

        return function (
            Request $request,
            callable $next
        ) use ($permissionCode): mixed {
            /*
            |--------------------------------------------------------------------------
            | Current access context
            |--------------------------------------------------------------------------
            */

            try {
                $context =
                    $this->contextResolver
                        ->resolve();
            } catch (RuntimeException $exception) {
                $this->deny(
                    $request,
                    'ACCESS_CONTEXT_REQUIRED',
                    $exception->getMessage()
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Authorization
            |--------------------------------------------------------------------------
            */

            if (
                !$this->authorization->can(
                    $context,
                    $permissionCode
                )
            ) {
                $this->deny(
                    $request,
                    'FORBIDDEN',
                    'Vous n’êtes pas autorisé à accéder '
                    . 'à cette ressource.'
                );
            }

            return $next($request);
        };
    }


    /**
     * Exige qu'au moins une permission
     * parmi la liste soit accordée.
     */
    public function requireAny(
        array $permissionCodes
    ): callable {
        $permissionCodes =
            $this->normalizePermissionCodes(
                $permissionCodes
            );

        if ($permissionCodes === []) {
            throw new RuntimeException(
                'Au moins une permission est requise.'
            );
        }

        return function (
            Request $request,
            callable $next
        ) use ($permissionCodes): mixed {
            try {
                $context =
                    $this->contextResolver
                        ->resolve();
            } catch (RuntimeException $exception) {
                $this->deny(
                    $request,
                    'ACCESS_CONTEXT_REQUIRED',
                    $exception->getMessage()
                );
            }

            if (
                !$this->authorization->canAny(
                    $context,
                    $permissionCodes
                )
            ) {
                $this->deny(
                    $request,
                    'FORBIDDEN',
                    'Vous ne possédez aucune des permissions '
                    . 'requises pour cette ressource.'
                );
            }

            return $next($request);
        };
    }


    /**
     * Exige toutes les permissions
     * fournies.
     */
    public function requireAll(
        array $permissionCodes
    ): callable {
        $permissionCodes =
            $this->normalizePermissionCodes(
                $permissionCodes
            );

        if ($permissionCodes === []) {
            throw new RuntimeException(
                'Au moins une permission est requise.'
            );
        }

        return function (
            Request $request,
            callable $next
        ) use ($permissionCodes): mixed {
            try {
                $context =
                    $this->contextResolver
                        ->resolve();
            } catch (RuntimeException $exception) {
                $this->deny(
                    $request,
                    'ACCESS_CONTEXT_REQUIRED',
                    $exception->getMessage()
                );
            }

            if (
                !$this->authorization->canAll(
                    $context,
                    $permissionCodes
                )
            ) {
                $this->deny(
                    $request,
                    'FORBIDDEN',
                    'Toutes les permissions requises '
                    . 'ne sont pas accordées.'
                );
            }

            return $next($request);
        };
    }


    /**
     * Normalise une liste de codes de permission.
     */
    private function normalizePermissionCodes(
        array $permissionCodes
    ): array {
        $normalized = [];

        foreach ($permissionCodes as $permissionCode) {
            if (!is_string($permissionCode)) {
                continue;
            }

            $permissionCode =
                trim(
                    $permissionCode
                );

            if ($permissionCode === '') {
                continue;
            }

            $normalized[] =
                $permissionCode;
        }

        return array_values(
            array_unique(
                $normalized
            )
        );
    }


    /**
     * Interrompt la requête avec HTTP 403.
     */
    private function deny(
        Request $request,
        string $code,
        string $message
    ): never {
        /*
        |--------------------------------------------------------------------------
        | AJAX / API
        |--------------------------------------------------------------------------
        */

        if ($request->expectsJson()) {
            Response::json(
                [
                    'status' => 'error',

                    'code' =>
                        $code,

                    'message' =>
                        $message,
                ],
                403
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Navigation web
        |--------------------------------------------------------------------------
        */

        http_response_code(403);

        echo 'Accès refusé.';

        exit;
    }
}