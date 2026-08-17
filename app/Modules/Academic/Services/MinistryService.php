<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\Services;

use MedTrack\Modules\Academic\Repositories\MinistryRepository;
use RuntimeException;

final class MinistryService
{
    private const ORGANIZATION_STATUSES = [
        'ACTIVE',
        'INACTIVE',
        'SUSPENDED',
    ];

    public function __construct(
        private readonly MinistryRepository $ministries
    ) {
    }

    /**
     * Retourne tous les ministères.
     */
    public function all(): array
    {
        return $this->ministries->all();
    }

    /**
     * Retourne uniquement les ministères actifs.
     */
    public function allActive(): array
    {
        return $this->ministries->allActive();
    }

    /**
     * Recherche un ministère par son identifiant.
     */
    public function findById(
        int $id
    ): ?array {
        if ($id <= 0) {
            return null;
        }

        return $this->ministries->findById(
            $id
        );
    }

    /**
     * Recherche un ministère par UUID.
     */
    public function findByUuid(
        string $uuid
    ): ?array {
        $uuid =
            trim(
                $uuid
            );

        if ($uuid === '') {
            return null;
        }

        return $this->ministries->findByUuid(
            $uuid
        );
    }

    /**
     * Crée un ministère.
     */
    public function create(
        array $data
    ): int {
        $code =
            $this->normalizeOrganizationCode(
                $data['code']
                ?? null
            );

        $name =
            $this->requiredString(
                $data['name']
                ?? null,
                'Le nom du ministère est obligatoire.'
            );

        if (
            $this->ministries->codeExists(
                $code
            )
        ) {
            throw new RuntimeException(
                'Ce code d’organisation est déjà utilisé.'
            );
        }

        $province =
            $this->nullableString(
                $data['province']
                ?? null
            );

        $city =
            $this->nullableString(
                $data['city']
                ?? null
            );

        $address =
            $this->nullableString(
                $data['address']
                ?? null
            );

        $phone =
            $this->nullableString(
                $data['phone']
                ?? null
            );

        $email =
            $this->normalizeEmail(
                $data['email']
                ?? null
            );

        $ministryScope =
            $this->normalizeMinistryScope(
                $data['ministry_scope']
                ?? null
            );

        $uuid =
            $this->generateUuidV4();

        return $this->ministries->create(
            $uuid,
            $code,
            $name,
            $province,
            $city,
            $address,
            $phone,
            $email,
            $ministryScope
        );
    }

    /**
     * Met à jour un ministère.
     */
    public function update(
        int $id,
        array $data
    ): void {
        if ($id <= 0) {
            throw new RuntimeException(
                'Ministère invalide.'
            );
        }

        $ministry =
            $this->ministries->findById(
                $id
            );

        if ($ministry === null) {
            throw new RuntimeException(
                'Ministère introuvable.'
            );
        }

        $code =
            $this->normalizeOrganizationCode(
                $data['code']
                ?? null
            );

        $name =
            $this->requiredString(
                $data['name']
                ?? null,
                'Le nom du ministère est obligatoire.'
            );

        if (
            $this->ministries->codeExists(
                $code,
                $id
            )
        ) {
            throw new RuntimeException(
                'Ce code d’organisation est déjà utilisé.'
            );
        }

        $province =
            $this->nullableString(
                $data['province']
                ?? null
            );

        $city =
            $this->nullableString(
                $data['city']
                ?? null
            );

        $address =
            $this->nullableString(
                $data['address']
                ?? null
            );

        $phone =
            $this->nullableString(
                $data['phone']
                ?? null
            );

        $email =
            $this->normalizeEmail(
                $data['email']
                ?? null
            );

        $status =
            $this->validateOrganizationStatus(
                $data['status']
                ?? 'ACTIVE'
            );

        $ministryScope =
            $this->normalizeMinistryScope(
                $data['ministry_scope']
                ?? null
            );

        $this->ministries->update(
            $id,
            $code,
            $name,
            $province,
            $city,
            $address,
            $phone,
            $email,
            $status,
            $ministryScope
        );
    }

    /**
     * Normalise le code institutionnel.
     */
    private function normalizeOrganizationCode(
        mixed $value
    ): string {
        $code =
            strtoupper(
                trim(
                    (string) $value
                )
            );

        if ($code === '') {
            throw new RuntimeException(
                'Le code du ministère est obligatoire.'
            );
        }

        if (
            mb_strlen(
                $code
            ) > 50
        ) {
            throw new RuntimeException(
                'Le code du ministère ne peut pas dépasser 50 caractères.'
            );
        }

        return $code;
    }

    /**
     * Normalise le champ ministry_scope.
     *
     * La colonne ministries.ministry_scope
     * accepte NULL et est limitée à 150 caractères.
     */
    private function normalizeMinistryScope(
        mixed $value
    ): ?string {
        $scope =
            $this->nullableString(
                $value
            );

        if ($scope === null) {
            return null;
        }

        if (
            mb_strlen(
                $scope
            ) > 150
        ) {
            throw new RuntimeException(
                'Le domaine de compétence du ministère '
                . 'ne peut pas dépasser 150 caractères.'
            );
        }

        return $scope;
    }

    /**
     * Valide une chaîne obligatoire.
     */
    private function requiredString(
        mixed $value,
        string $message
    ): string {
        $value =
            trim(
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
     * Convertit une chaîne vide en NULL.
     */
    private function nullableString(
        mixed $value
    ): ?string {
        if ($value === null) {
            return null;
        }

        $value =
            trim(
                (string) $value
            );

        return $value !== ''
            ? $value
            : null;
    }

    /**
     * Normalise et valide l'adresse email.
     */
    private function normalizeEmail(
        mixed $value
    ): ?string {
        $email =
            $this->nullableString(
                $value
            );

        if ($email === null) {
            return null;
        }

        $email =
            strtolower(
                $email
            );

        if (
            filter_var(
                $email,
                FILTER_VALIDATE_EMAIL
            ) === false
        ) {
            throw new RuntimeException(
                'L’adresse email du ministère est invalide.'
            );
        }

        if (
            mb_strlen(
                $email
            ) > 190
        ) {
            throw new RuntimeException(
                'L’adresse email est trop longue.'
            );
        }

        return $email;
    }

    /**
     * Valide le statut MedTrack de l'organisation.
     */
    private function validateOrganizationStatus(
        mixed $value
    ): string {
        $status =
            strtoupper(
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
                'Le statut du ministère est invalide.'
            );
        }

        return $status;
    }

    /**
     * Génère un UUID version 4.
     */
    private function generateUuidV4(): string
    {
        $data =
            random_bytes(
                16
            );

        $data[6] =
            chr(
                (
                    ord(
                        $data[6]
                    )
                    & 0x0f
                )
                | 0x40
            );

        $data[8] =
            chr(
                (
                    ord(
                        $data[8]
                    )
                    & 0x3f
                )
                | 0x80
            );

        return vsprintf(
            '%s%s-%s-%s-%s-%s%s%s',
            str_split(
                bin2hex(
                    $data
                ),
                4
            )
        );
    }
}