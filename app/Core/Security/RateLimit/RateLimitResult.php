<?php

declare(strict_types=1);

namespace MedTrack\Core\Security\RateLimit;

final class RateLimitResult
{
    public function __construct(
        public readonly bool $allowed,
        public readonly int $retryAfter = 0
    ) {
    }

    public static function allowed(): self
    {
        return new self(true);
    }

    public static function blocked(
        int $retryAfter
    ): self {
        return new self(
            false,
            max(1, $retryAfter)
        );
    }
}
