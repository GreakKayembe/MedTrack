<?php

declare(strict_types=1);

namespace MedTrack\Core\Http;

final class Request
{
    private array $attributes = [];

    public function __construct(
        private readonly string $method,
        private readonly string $path,
        private readonly array $query,
        private readonly array $body,
        private readonly array $server
    ) {
    }

    public static function capture(): self
    {
        $method = strtoupper(
            $_SERVER['REQUEST_METHOD'] ?? 'GET'
        );

        $path = parse_url(
            $_SERVER['REQUEST_URI'] ?? '/',
            PHP_URL_PATH
        ) ?: '/';

        return new self(
            $method,
            '/' . trim($path, '/'),
            $_GET,
            $_POST,
            $_SERVER
        );
    }

    public function method(): string
    {
        return $this->method;
    }

    public function path(): string
    {
        return $this->path === '/'
            ? '/'
            : rtrim($this->path, '/');
    }

    public function query(
        string $key,
        mixed $default = null
    ): mixed {
        return $this->query[$key]
            ?? $default;
    }

    public function input(
        string $key,
        mixed $default = null
    ): mixed {
        return $this->body[$key]
            ?? $default;
    }

    public function all(): array
    {
        return $this->body;
    }

    public function header(
        string $name,
        mixed $default = null
    ): mixed {
        $key = 'HTTP_' . strtoupper(
            str_replace('-', '_', $name)
        );

        return $this->server[$key]
            ?? $default;
    }

    public function ip(): string
    {
        $ip = $this->server['REMOTE_ADDR']
            ?? '0.0.0.0';

        return is_string($ip)
            ? $ip
            : '0.0.0.0';
    }

    public function expectsJson(): bool
    {
        $accept = strtolower(
            (string) $this->header(
                'Accept',
                ''
            )
        );

        $requestedWith = strtolower(
            (string) $this->header(
                'X-Requested-With',
                ''
            )
        );

        return str_contains(
            $accept,
            'application/json'
        ) || $requestedWith === 'xmlhttprequest';
    }

    /**
     * Ajoute les paramètres extraits de la route.
     *
     * Exemple :
     *
     * /universities/{id}
     *
     * devient :
     *
     * [
     *     'id' => '12',
     * ]
     */
    public function setRouteAttributes(
        array $attributes
    ): void {
        $this->attributes = $attributes;
    }

    /**
     * Retourne un paramètre de route.
     */
    public function attribute(
        string $key,
        mixed $default = null
    ): mixed {
        return $this->attributes[$key]
            ?? $default;
    }

    /**
     * Retourne tous les paramètres de route.
     */
    public function attributes(): array
    {
        return $this->attributes;
    }
}