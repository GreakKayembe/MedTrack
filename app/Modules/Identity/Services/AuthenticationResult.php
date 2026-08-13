<?php

declare(strict_types=1);

namespace MedTrack\Modules\Identity\Services;

final class AuthenticationResult
{
    public const SUCCESS = 'SUCCESS';

    public const USER_NOT_FOUND = 'USER_NOT_FOUND';

    public const ACCOUNT_UNAVAILABLE = 'ACCOUNT_UNAVAILABLE';

    public const INVALID_PASSWORD = 'INVALID_PASSWORD';

    public function __construct(
        public readonly bool $successful,
        public readonly string $reason,
        public readonly ?int $userId = null
    ) {
    }

    public static function success(
        int $userId
    ): self {
        return new self(
            true,
            self::SUCCESS,
            $userId
        );
    }

    public static function failure(
        string $reason,
        ?int $userId = null
    ): self {
        return new self(
            false,
            $reason,
            $userId
        );
    }
}