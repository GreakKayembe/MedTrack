<?php

declare(strict_types=1);

namespace MedTrack\Modules\Audit\Services;

use JsonException;
use MedTrack\Modules\Audit\Repositories\AuditRepository;

final class AuditRecorder
{
    public function __construct(
        private readonly AuditRepository $audits
    ) {
    }

    /**
     * Enregistre un événement dans le journal d'audit.
     *
     * @throws JsonException
     */
    public function record(
        string $action,
        string $entityType,
        string|int $entityId,
        ?int $actorUserId = null,
        ?int $organizationId = null,
        ?int $actorMembershipId = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?array $metadata = null,
        ?string $ipAddress = null,
        ?string $userAgent = null
    ): int {
        $action =
            trim(
                $action
            );

        $entityType =
            trim(
                $entityType
            );

        $entityId =
            trim(
                (string) $entityId
            );

        if ($action === '') {
            throw new \InvalidArgumentException(
                'L’action d’audit est obligatoire.'
            );
        }

        if ($entityType === '') {
            throw new \InvalidArgumentException(
                'Le type d’entité est obligatoire.'
            );
        }

        if ($entityId === '') {
            throw new \InvalidArgumentException(
                'L’identifiant de l’entité est obligatoire.'
            );
        }

        return $this->audits
            ->create(
                [
                    'uuid' =>
                        $this->uuid(),

                    'organization_id' =>
                        $organizationId,

                    'actor_user_id' =>
                        $actorUserId,

                    'actor_membership_id' =>
                        $actorMembershipId,

                    'action' =>
                        $action,

                    'entity_type' =>
                        $entityType,

                    'entity_id' =>
                        $entityId,

                    'old_values' =>
                        $this->encode(
                            $oldValues
                        ),

                    'new_values' =>
                        $this->encode(
                            $newValues
                        ),

                    'metadata' =>
                        $this->encode(
                            $metadata
                        ),

                    'ip_address' =>
                        $this->nullableString(
                            $ipAddress
                        ),

                    'user_agent' =>
                        $this->nullableString(
                            $userAgent
                        ),
                ]
            );
    }

    /**
     * Encode les structures JSON destinées
     * aux colonnes JSON MySQL.
     *
     * @throws JsonException
     */
    private function encode(
        ?array $value
    ): ?string {
        if ($value === null) {
            return null;
        }

        return json_encode(
            $value,
            JSON_THROW_ON_ERROR
            | JSON_UNESCAPED_UNICODE
            | JSON_UNESCAPED_SLASHES
        );
    }

    private function nullableString(
        ?string $value
    ): ?string {
        if ($value === null) {
            return null;
        }

        $value =
            trim(
                $value
            );

        return $value !== ''
            ? $value
            : null;
    }

    /**
     * UUID v4 sans dépendance externe.
     */
    private function uuid(): string
    {
        $bytes =
            random_bytes(
                16
            );

        $bytes[6] =
            chr(
                (
                    ord($bytes[6])
                    & 0x0f
                )
                | 0x40
            );

        $bytes[8] =
            chr(
                (
                    ord($bytes[8])
                    & 0x3f
                )
                | 0x80
            );

        $hex =
            bin2hex(
                $bytes
            );

        return sprintf(
            '%s-%s-%s-%s-%s',
            substr($hex, 0, 8),
            substr($hex, 8, 4),
            substr($hex, 12, 4),
            substr($hex, 16, 4),
            substr($hex, 20, 12)
        );
    }
}