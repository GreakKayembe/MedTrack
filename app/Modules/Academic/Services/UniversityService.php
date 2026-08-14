<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\Services;

use MedTrack\Modules\Academic\Repositories\UniversityRepository;
use RuntimeException;

final class UniversityService
{
    private const ORGANIZATION_STATUSES = [
        'ACTIVE',
        'INACTIVE',
        'SUSPENDED',
    ];

    private const ACCREDITATION_STATUSES = [
        'PENDING',
        'ACCREDITED',
        'SUSPENDED',
        'REVOKED',
    ];

    public function __construct(
        private readonly UniversityRepository $universities
    ) {
    }

    /**
     * Retourne toutes les universités.
     */
    public function all(): array
    {
        return $this->universities->all();
    }

    /**
     * Retourne les universités actives.
     */
    public function allActive(): array
    {
        return $this->universities->allActive();
    }

    /**
     * Retourne une université par son identifiant.
     */
    public function findById(
        int $id
    ): ?array {
        if ($id <= 0) {
            return null;
        }

        return $this->universities->findById(
            $id
        );
    }

    /**
     * Retourne une université par son UUID.
     */
    public function findByUuid(
        string $uuid
    ): ?array {
        $uuid = trim($uuid);

        if ($uuid === '') {
            return null;
        }

        return $this->universities->findByUuid(
            $uuid
        );
    }

    /**
     * Crée une nouvelle université.
     */
    public function create(
        array $data
    ): int {
        $code = $this->normalizeCode(
            $data['code'] ?? null
        );

        $name = $this->requiredString(
            $data['name'] ?? null,
            'Le nom de l’université est obligatoire.'
        );

        if ($this->universities->codeExists($code)) {
            throw new RuntimeException(
                'Ce code d’organisation est déjà utilisé.'
            );
        }

        $province = $this->nullableString(
            $data['province'] ?? null
        );

        $city = $this->nullableString(
            $data['city'] ?? null
        );

        $address = $this->nullableString(
            $data['address'] ?? null
        );

        $phone = $this->nullableString(
            $data['phone'] ?? null
        );

        $email = $this->normalizeEmail(
            $data['email'] ?? null
        );

        $universityType = $this->nullableString(
            $data['university_type'] ?? null
        );

        $accreditationStatus =
            $this->validateAccreditationStatus(
                $data['accreditation_status']
                    ?? 'PENDING'
            );

        $accreditationScore =
            $this->normalizeAccreditationScore(
                $data['accreditation_score']
                    ?? null
            );

        $uuid = $this->generateUuidV4();

        return $this->universities->create(
            $uuid,
            $code,
            $name,
            $province,
            $city,
            $address,
            $phone,
            $email,
            $universityType,
            $accreditationStatus,
            $accreditationScore
        );
    }

    /**
     * Met à jour une université existante.
     */
    public function update(
        int $id,
        array $data
    ): void {
        if ($id <= 0) {
            throw new RuntimeException(
                'Université invalide.'
            );
        }

        $university =
            $this->universities->findById(
                $id
            );

        if ($university === null) {
            throw new RuntimeException(
                'Université introuvable.'
            );
        }

        $code = $this->normalizeCode(
            $data['code'] ?? null
        );

        $name = $this->requiredString(
            $data['name'] ?? null,
            'Le nom de l’université est obligatoire.'
        );

        if (
            $this->universities->codeExists(
                $code,
                $id
            )
        ) {
            throw new RuntimeException(
                'Ce code d’organisation est déjà utilisé.'
            );
        }

        $province = $this->nullableString(
            $data['province'] ?? null
        );

        $city = $this->nullableString(
            $data['city'] ?? null
        );

        $address = $this->nullableString(
            $data['address'] ?? null
        );

        $phone = $this->nullableString(
            $data['phone'] ?? null
        );

        $email = $this->normalizeEmail(
            $data['email'] ?? null
        );

        $status = $this->validateOrganizationStatus(
            $data['status'] ?? 'ACTIVE'
        );

        $universityType = $this->nullableString(
            $data['university_type'] ?? null
        );

        $accreditationStatus =
            $this->validateAccreditationStatus(
                $data['accreditation_status']
                    ?? 'PENDING'
            );

        $accreditationScore =
            $this->normalizeAccreditationScore(
                $data['accreditation_score']
                    ?? null
            );

        $this->universities->update(
            $id,
            $code,
            $name,
            $province,
            $city,
            $address,
            $phone,
            $email,
            $status,
            $universityType,
            $accreditationStatus,
            $accreditationScore
        );
    }

    /**
     * Normalise et valide le code institutionnel.
     */
    private function normalizeCode(
        mixed $value
    ): string {
        $code = strtoupper(
            trim(
                (string) $value
            )
        );

        if ($code === '') {
            throw new RuntimeException(
                'Le code de l’université est obligatoire.'
            );
        }

        if (mb_strlen($code) > 50) {
            throw new RuntimeException(
                'Le code de l’université ne peut pas dépasser 50 caractères.'
            );
        }

        return $code;
    }

    /**
     * Valide une chaîne obligatoire.
     */
    private function requiredString(
        mixed $value,
        string $message
    ): string {
        $value = trim(
            (string) $value
        );

        if ($value === '') {
            throw new RuntimeException(
                $message
            );
        }

        return $value;
    }

    /**
     * Convertit une valeur vide en NULL.
     */
    private function nullableString(
        mixed $value
    ): ?string {
        if ($value === null) {
            return null;
        }

        $value = trim(
            (string) $value
        );

        return $value !== ''
            ? $value
            : null;
    }

    /**
     * Normalise et valide une adresse email optionnelle.
     */
    private function normalizeEmail(
        mixed $value
    ): ?string {
        $email = $this->nullableString(
            $value
        );

        if ($email === null) {
            return null;
        }

        $email = strtolower($email);

        if (
            filter_var(
                $email,
                FILTER_VALIDATE_EMAIL
            ) === false
        ) {
            throw new RuntimeException(
                'L’adresse email de l’université est invalide.'
            );
        }

        if (mb_strlen($email) > 190) {
            throw new RuntimeException(
                'L’adresse email est trop longue.'
            );
        }

        return $email;
    }

    /**
     * Valide le statut général de l'organisation.
     */
    private function validateOrganizationStatus(
        mixed $value
    ): string {
        $status = strtoupper(
            trim(
                (string) $value
            )
        );

        if (
            !in_array(
                $status,
                self::ORGANIZATION_STATUSES,
                true
            )
        ) {
            throw new RuntimeException(
                'Le statut de l’université est invalide.'
            );
        }

        return $status;
    }

    /**
     * Valide le statut d'accréditation.
     */
    private function validateAccreditationStatus(
        mixed $value
    ): string {
        $status = strtoupper(
            trim(
                (string) $value
            )
        );

        if (
            !in_array(
                $status,
                self::ACCREDITATION_STATUSES,
                true
            )
        ) {
            throw new RuntimeException(
                'Le statut d’accréditation est invalide.'
            );
        }

        return $status;
    }

    /**
     * Valide le score d'accréditation.
     *
     * MySQL impose également une contrainte CHECK
     * comprise entre 0 et 100.
     */
    private function normalizeAccreditationScore(
        mixed $value
    ): ?float {
        if (
            $value === null
            || trim((string) $value) === ''
        ) {
            return null;
        }

        if (!is_numeric($value)) {
            throw new RuntimeException(
                'Le score d’accréditation doit être numérique.'
            );
        }

        $score = (float) $value;

        if (
            $score < 0
            || $score > 100
        ) {
            throw new RuntimeException(
                'Le score d’accréditation doit être compris entre 0 et 100.'
            );
        }

        return round(
            $score,
            2
        );
    }

    /**
     * Génère un UUID version 4 sans dépendance externe.
     */
    private function generateUuidV4(): string
    {
        $data = random_bytes(16);

        /*
         * Version 4.
         */
        $data[6] = chr(
            (ord($data[6]) & 0x0f)
            | 0x40
        );

        /*
         * Variant RFC 4122.
         */
        $data[8] = chr(
            (ord($data[8]) & 0x3f)
            | 0x80
        );

        return vsprintf(
            '%s%s-%s-%s-%s-%s%s%s',
            str_split(
                bin2hex($data),
                4
            )
        );
    }
}