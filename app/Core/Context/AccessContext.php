<?php

declare(strict_types=1);

namespace MedTrack\Core\Context;

use InvalidArgumentException;
use LogicException;

final class AccessContext
{
    public const TYPE_PLATFORM =
        'PLATFORM';

    public const TYPE_ORGANIZATION =
        'ORGANIZATION';

    public const TYPE_STUDENT =
        'STUDENT';

    private function __construct(
        private readonly int $userId,
        private readonly string $type,
        private readonly ?int $organizationId = null,
        private readonly ?int $membershipId = null,
        private readonly ?string $organizationType = null,
        private readonly ?int $studentId = null
    ) {
        if ($this->userId <= 0) {
            throw new InvalidArgumentException(
                'Identifiant utilisateur invalide.'
            );
        }

        if (!in_array(
            $this->type,
            [
                self::TYPE_PLATFORM,
                self::TYPE_ORGANIZATION,
                self::TYPE_STUDENT,
            ],
            true
        )) {
            throw new InvalidArgumentException(
                'Type de contexte d’accès invalide.'
            );
        }

        $this->validate();
    }


    /**
     * Contexte d'administration centrale MedTrack.
     */
    public static function platform(
        int $userId
    ): self {
        return new self(
            userId: $userId,
            type: self::TYPE_PLATFORM
        );
    }


    /**
     * Contexte d'une organisation :
     * université, hôpital, ordre ou ministère.
     */
    public static function organization(
        int $userId,
        int $organizationId,
        int $membershipId,
        string $organizationType
    ): self {
        return new self(
            userId: $userId,
            type: self::TYPE_ORGANIZATION,
            organizationId: $organizationId,
            membershipId: $membershipId,
            organizationType: $organizationType
        );
    }


    /**
     * Contexte personnel d'un étudiant.
     */
    public static function student(
        int $userId,
        int $studentId
    ): self {
        return new self(
            userId: $userId,
            type: self::TYPE_STUDENT,
            studentId: $studentId
        );
    }


    public function userId(): int
    {
        return $this->userId;
    }


    public function type(): string
    {
        return $this->type;
    }


    public function isPlatform(): bool
    {
        return $this->type
            === self::TYPE_PLATFORM;
    }


    public function isOrganization(): bool
    {
        return $this->type
            === self::TYPE_ORGANIZATION;
    }


    public function isStudent(): bool
    {
        return $this->type
            === self::TYPE_STUDENT;
    }


    public function organizationId(): int
    {
        if (
            !$this->isOrganization()
            || $this->organizationId === null
        ) {
            throw new LogicException(
                'Aucune organisation active '
                . 'dans ce contexte.'
            );
        }

        return $this->organizationId;
    }


    public function membershipId(): int
    {
        if (
            !$this->isOrganization()
            || $this->membershipId === null
        ) {
            throw new LogicException(
                'Aucun membership actif '
                . 'dans ce contexte.'
            );
        }

        return $this->membershipId;
    }


    public function organizationType(): string
    {
        if (
            !$this->isOrganization()
            || $this->organizationType === null
        ) {
            throw new LogicException(
                'Aucun type d’organisation '
                . 'dans ce contexte.'
            );
        }

        return $this->organizationType;
    }


    public function studentId(): int
    {
        if (
            !$this->isStudent()
            || $this->studentId === null
        ) {
            throw new LogicException(
                'Aucun étudiant actif '
                . 'dans ce contexte.'
            );
        }

        return $this->studentId;
    }


    /**
     * Vérifie les invariants internes.
     *
     * Un contexte PLATFORM ne transporte aucune
     * organisation ni étudiant.
     *
     * Un contexte ORGANIZATION doit obligatoirement
     * transporter organization_id, membership_id
     * et organization_type.
     *
     * Un contexte STUDENT doit obligatoirement
     * transporter student_id.
     */
    private function validate(): void
    {
        if ($this->isPlatform()) {
            if (
                $this->organizationId !== null
                || $this->membershipId !== null
                || $this->organizationType !== null
                || $this->studentId !== null
            ) {
                throw new LogicException(
                    'Le contexte plateforme contient '
                    . 'des données incompatibles.'
                );
            }

            return;
        }

        if ($this->isOrganization()) {
            if (
                $this->organizationId === null
                || $this->organizationId <= 0
                || $this->membershipId === null
                || $this->membershipId <= 0
                || $this->organizationType === null
                || trim($this->organizationType) === ''
            ) {
                throw new LogicException(
                    'Le contexte organisationnel '
                    . 'est incomplet.'
                );
            }

            if ($this->studentId !== null) {
                throw new LogicException(
                    'Un contexte organisationnel '
                    . 'ne peut pas contenir '
                    . 'un étudiant actif.'
                );
            }

            return;
        }

        if (
            $this->studentId === null
            || $this->studentId <= 0
        ) {
            throw new LogicException(
                'Le contexte étudiant est incomplet.'
            );
        }

        if (
            $this->organizationId !== null
            || $this->membershipId !== null
            || $this->organizationType !== null
        ) {
            throw new LogicException(
                'Un contexte étudiant ne peut pas '
                . 'contenir une organisation active.'
            );
        }
    }
}