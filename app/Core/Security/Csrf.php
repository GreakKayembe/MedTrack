<?php

declare(strict_types=1);

namespace MedTrack\Core\Security;

use MedTrack\Core\Auth\Session;

final class Csrf
{
    private const SESSION_KEY = '_csrf_token';

    public function __construct(
        private readonly Session $session
    ) {
    }

    public function token(): string
    {
        $token = $this->session->get(
            self::SESSION_KEY
        );

        if (
            !is_string($token)
            || strlen($token) !== 64
        ) {
            return $this->regenerate();
        }

        return $token;
    }

    public function validate(
        ?string $token
    ): bool {
        if (
            $token === null
            || $token === ''
        ) {
            return false;
        }

        $storedToken = $this->session->get(
            self::SESSION_KEY
        );

        if (
            !is_string($storedToken)
            || $storedToken === ''
        ) {
            return false;
        }

        return hash_equals(
            $storedToken,
            $token
        );
    }

    public function regenerate(): string
    {
        $token = bin2hex(
            random_bytes(32)
        );

        $this->session->put(
            self::SESSION_KEY,
            $token
        );

        return $token;
    }
}