<?php

declare(strict_types=1);

namespace MedTrack\Core\Http\Middleware;

use MedTrack\Core\Context\AccessContextResolver;
use MedTrack\Core\Http\Request;
use MedTrack\Core\Http\Response;
use RuntimeException;

final class AccessContextMiddleware
{
    public function __construct(
        private readonly AccessContextResolver $contextResolver
    ) {
    }

    /**
     * Vérifie qu'un contexte d'accès valide peut être
     * résolu pour l'utilisateur authentifié.
     *
     * Le middleware ne décide pas encore si l'utilisateur
     * possède une permission métier particulière.
     */
    public function handle(
        Request $request,
        callable $next
    ): mixed {
        try {
            $this->contextResolver->resolve();
        } catch (RuntimeException $exception) {
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
                            'ACCESS_CONTEXT_REQUIRED',

                        'message' =>
                            $exception->getMessage(),
                    ],
                    403
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Navigation web
            |--------------------------------------------------------------------------
            |
            | Nous ne redirigeons pas encore vers un écran de sélection
            | d'organisation, car cette route n'existe pas encore.
            |
            | Lorsque nous créerons le sélecteur de contexte, ce bloc
            | pourra rediriger proprement vers celui-ci.
            |--------------------------------------------------------------------------
            */

            http_response_code(403);

            echo 'Accès refusé : aucun contexte d’accès valide '
                . 'n’est disponible pour ce compte.';

            exit;
        }

        return $next($request);
    }
}