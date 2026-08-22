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
        private readonly array $server,
        private readonly array $files = []
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
            $_SERVER,
            $_FILES
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

    /**
     * Retourne un fichier envoyé via multipart/form-data.
     *
     * Structure retournée :
     *
     * [
     *     'name' => 'students.xlsx',
     *     'full_path' => 'students.xlsx',
     *     'type' => '...',
     *     'tmp_name' => '/tmp/php...',
     *     'error' => UPLOAD_ERR_OK,
     *     'size' => 12345,
     * ]
     */
    public function file(
        string $key
    ): ?array {
        $file =
            $this->files[$key]
            ?? null;

        if (!is_array($file)) {
            return null;
        }

        return $file;
    }

    /**
     * Indique si un fichier a réellement été envoyé
     * sans erreur d'upload PHP.
     */
    public function hasFile(
        string $key
    ): bool {
        $file =
            $this->file($key);

        if ($file === null) {
            return false;
        }

        $tmpName =
            $file['tmp_name']
            ?? null;

        $error =
            $file['error']
            ?? UPLOAD_ERR_NO_FILE;

        return $error === UPLOAD_ERR_OK
            && is_string($tmpName)
            && $tmpName !== ''
            && is_uploaded_file($tmpName);
    }

    /**
     * Retourne tous les fichiers de la requête.
     */
    public function files(): array
    {
        return $this->files;
    }

    public function header(
        string $name,
        mixed $default = null
    ): mixed {
        $key = 'HTTP_' . strtoupper(
            str_replace(
                '-',
                '_',
                $name
            )
        );

        return $this->server[$key]
            ?? $default;
    }

    public function ip(): string
    {
        $ip =
            $this->server['REMOTE_ADDR']
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
        $this->attributes =
            $attributes;
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