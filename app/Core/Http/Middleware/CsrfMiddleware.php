<?php

declare(strict_types=1);

namespace MedTrack\Core\Http\Middleware;

use MedTrack\Core\Http\Request;
use MedTrack\Core\Http\Response;
use MedTrack\Core\Security\Csrf;

final class CsrfMiddleware
{
    public function __construct(
        private readonly Csrf $csrf
    ) {
    }

    public function handle(
        Request $request,
        callable $next
    ): mixed {
        if ($request->method() !== 'POST') {
            return $next($request);
        }


                $token = $request->input(
            '_token',
            $request->header(
                'X-CSRF-Token'
            )
        );


        if (
            !is_string($token)
            || !$this->csrf->validate($token)
        ) {
            Response::json(
                [
                    'status' => 'error',
                    'code' => 'CSRF_TOKEN_MISMATCH',
                    'message' =>
                        'Votre session de sécurité a expiré. '
                        . 'Actualisez la page puis réessayez.',
                ],
                419
            );
        }

        return $next($request);
    }
}