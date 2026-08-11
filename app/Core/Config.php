<?php

declare(strict_types=1);

namespace MedTrack\Core;

final class Config
{
    public function __construct(
        private readonly array $items
    ) {
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->items[$key] ?? $default;
    }
}
