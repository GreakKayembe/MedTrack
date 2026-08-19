<?php

declare(strict_types=1);

namespace MedTrack\Modules\Audit\Services;

use InvalidArgumentException;
use MedTrack\Modules\Audit\Repositories\AuditRepository;

final class AuditService
{
    public function __construct(
        private readonly AuditRepository $audits
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Read
    |--------------------------------------------------------------------------
    */

    public function all(
        int $limit = 100
    ): array {
        return $this->audits
            ->all(
                $limit
            );
    }

    public function findById(
        int $id
    ): ?array {
        if ($id <= 0) {
            return null;
        }

        return $this->audits
            ->findById(
                $id
            );
    }

    public function actions(): array
    {
        return $this->audits
            ->actions();
    }

    public function entityTypes(): array
    {
        return $this->audits
            ->entityTypes();
    }

    public function metrics(): array
    {
        return $this->audits
            ->metrics();
    }

    /*
    |--------------------------------------------------------------------------
    | Platform overview
    |--------------------------------------------------------------------------
    */

    public function platformOverview(
        int $limit = 100
    ): array {
        return [
            'metrics' =>
                $this->metrics(),

            'events' =>
                $this->all(
                    $limit
                ),

            'actions' =>
                $this->actions(),

            'entity_types' =>
                $this->entityTypes(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Detail
    |--------------------------------------------------------------------------
    */

    public function platformShow(
        int $auditId
    ): ?array {
        $this->assertId(
            $auditId,
            'Identifiant d’audit invalide.'
        );

        $audit =
            $this->findById(
                $auditId
            );

        if ($audit === null) {
            return null;
        }

        return [
            'audit' =>
                $audit,

            'old_values' =>
                $this->decodeJson(
                    $audit['old_values']
                    ?? null
                ),

            'new_values' =>
                $this->decodeJson(
                    $audit['new_values']
                    ?? null
                ),

            'metadata' =>
                $this->decodeJson(
                    $audit['metadata']
                    ?? null
                ),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | JSON
    |--------------------------------------------------------------------------
    */

    private function decodeJson(
        mixed $value
    ): array {
        if ($value === null || $value === '') {
            return [];
        }

        if (is_array($value)) {
            return $value;
        }

        if (!is_string($value)) {
            return [];
        }

        $decoded =
            json_decode(
                $value,
                true
            );

        return is_array($decoded)
            ? $decoded
            : [];
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    private function assertId(
        int $id,
        string $message
    ): void {
        if ($id <= 0) {
            throw new InvalidArgumentException(
                $message
            );
        }
    }
}