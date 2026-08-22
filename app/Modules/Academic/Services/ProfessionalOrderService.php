<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\Services;

use MedTrack\Modules\Academic\Repositories\ProfessionalOrderRepository;
use RuntimeException;

final class ProfessionalOrderService
{
    private const ORGANIZATION_STATUSES = [
        'ACTIVE',
        'INACTIVE',
        'SUSPENDED',
    ];

    public function __construct(
        private readonly ProfessionalOrderRepository $orders
    ) {
    }

    /**
     * Retourne tous les ordres professionnels.
     */
    public function all(): array
    {
        return $this->orders->all();
    }

    /**
     * Retourne uniquement les ordres professionnels actifs.
     */
    public function allActive(): array
    {
        return $this->orders->allActive();
    }

    /**
     * Recherche un ordre professionnel par son identifiant.
     */
    public function findById(
        int $id
    ): ?array {
        if ($id <= 0) {
            return null;
        }

        return $this->orders->findById(
            $id
        );
    }

    /**
     * Recherche un ordre professionnel par UUID.
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

        return $this->orders->findByUuid(
            $uuid
        );
    }

    /**
     * Crée un ordre professionnel.
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
                'Le nom de l’ordre professionnel est obligatoire.'
            );

        if (
            $this->orders->codeExists(
                $code
            )
        ) {
            throw new RuntimeException(
                'Ce code d’organisation est déjà utilisé.'
            );
        }

        $professionCode =
            $this->normalizeProfessionCode(
                $data['profession_code']
                ?? null
            );

        if (
            $this->orders->professionCodeExists(
                $professionCode
            )
        ) {
            throw new RuntimeException(
                'Ce code de profession est déjà utilisé.'
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

        $uuid =
            $this->generateUuidV4();

        return $this->orders->create(
            $uuid,
            $code,
            $name,
            $province,
            $city,
            $address,
            $phone,
            $email,
            $professionCode
        );
    }

    /**
     * Met à jour un ordre professionnel.
     */
    public function update(
        int $id,
        array $data
    ): void {
        if ($id <= 0) {
            throw new RuntimeException(
                'Ordre professionnel invalide.'
            );
        }

        $order =
            $this->orders->findById(
                $id
            );

        if ($order === null) {
            throw new RuntimeException(
                'Ordre professionnel introuvable.'
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
                'Le nom de l’ordre professionnel est obligatoire.'
            );

        if (
            $this->orders->codeExists(
                $code,
                $id
            )
        ) {
            throw new RuntimeException(
                'Ce code d’organisation est déjà utilisé.'
            );
        }

        $professionCode =
            $this->normalizeProfessionCode(
                $data['profession_code']
                ?? null
            );

        if (
            $this->orders->professionCodeExists(
                $professionCode,
                $id
            )
        ) {
            throw new RuntimeException(
                'Ce code de profession est déjà utilisé.'
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

        $this->orders->update(
            $id,
            $code,
            $name,
            $province,
            $city,
            $address,
            $phone,
            $email,
            $status,
            $professionCode
        );
    }

    /**
     * Normalise le code d'organisation.
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
                'Le code de l’ordre professionnel est obligatoire.'
            );
        }

        if (
            mb_strlen(
                $code
            ) > 50
        ) {
            throw new RuntimeException(
                'Le code de l’ordre professionnel ne peut pas dépasser 50 caractères.'
            );
        }

        return $code;
    }

    /**
     * Normalise le code métier de la profession.
     */
    private function normalizeProfessionCode(
        mixed $value
    ): string {
        $professionCode =
            strtoupper(
                trim(
                    (string) $value
                )
            );

        if ($professionCode === '') {
            throw new RuntimeException(
                'Le code de profession est obligatoire.'
            );
        }

        if (
            mb_strlen(
                $professionCode
            ) > 50
        ) {
            throw new RuntimeException(
                'Le code de profession ne peut pas dépasser 50 caractères.'
            );
        }

        return $professionCode;
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
     * Convertit une valeur vide en NULL.
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
                'L’adresse email de l’ordre professionnel est invalide.'
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
                'Le statut de l’ordre professionnel est invalide.'
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