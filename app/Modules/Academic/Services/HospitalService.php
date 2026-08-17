<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\Services;

use MedTrack\Modules\Academic\Repositories\HospitalRepository;
use RuntimeException;

final class HospitalService
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
        private readonly HospitalRepository $hospitals
    ) {
    }

    /**
     * Retourne tous les hôpitaux.
     */
    public function all(): array
    {
        return $this->hospitals->all();
    }

    /**
     * Retourne uniquement les hôpitaux actifs.
     */
    public function allActive(): array
    {
        return $this->hospitals->allActive();
    }

    /**
     * Recherche un hôpital par son identifiant.
     */
    public function findById(
        int $id
    ): ?array {
        if ($id <= 0) {
            return null;
        }

        return $this->hospitals->findById(
            $id
        );
    }

    /**
     * Recherche un hôpital par son UUID.
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

        return $this->hospitals->findByUuid(
            $uuid
        );
    }

    /**
     * Crée un nouvel hôpital.
     */
    public function create(
        array $data
    ): int {
        $code =
            $this->normalizeCode(
                $data['code']
                ?? null
            );

        $name =
            $this->requiredString(
                $data['name']
                ?? null,
                'Le nom de l’hôpital est obligatoire.'
            );

        if (
            $this->hospitals->codeExists(
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

        $facilityLevel =
            $this->normalizeFacilityLevel(
                $data['facility_level']
                ?? null
            );

        $specialty =
            $this->nullableString(
                $data['specialty']
                ?? null
            );

        if (
            $specialty !== null
            && mb_strlen(
                $specialty
            ) > 150
        ) {
            throw new RuntimeException(
                'La spécialité ne peut pas dépasser 150 caractères.'
            );
        }

        $internshipCapacity =
            $this->normalizeInternshipCapacity(
                $data['internship_capacity']
                ?? 0
            );

        $accreditationStatus =
            $this->validateAccreditationStatus(
                $data['accreditation_status']
                ?? 'PENDING'
            );

        $latitude =
            $this->normalizeLatitude(
                $data['latitude']
                ?? null
            );

        $longitude =
            $this->normalizeLongitude(
                $data['longitude']
                ?? null
            );

        $uuid =
            $this->generateUuidV4();

        return $this->hospitals->create(
            $uuid,
            $code,
            $name,
            $province,
            $city,
            $address,
            $phone,
            $email,
            $facilityLevel,
            $specialty,
            $internshipCapacity,
            $accreditationStatus,
            $latitude,
            $longitude
        );
    }

    /**
     * Met à jour un hôpital existant.
     */
    public function update(
        int $id,
        array $data
    ): void {
        if ($id <= 0) {
            throw new RuntimeException(
                'Hôpital invalide.'
            );
        }

        $hospital =
            $this->hospitals->findById(
                $id
            );

        if ($hospital === null) {
            throw new RuntimeException(
                'Hôpital introuvable.'
            );
        }

        $code =
            $this->normalizeCode(
                $data['code']
                ?? null
            );

        $name =
            $this->requiredString(
                $data['name']
                ?? null,
                'Le nom de l’hôpital est obligatoire.'
            );

        if (
            $this->hospitals->codeExists(
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

        $facilityLevel =
            $this->normalizeFacilityLevel(
                $data['facility_level']
                ?? null
            );

        $specialty =
            $this->nullableString(
                $data['specialty']
                ?? null
            );

        if (
            $specialty !== null
            && mb_strlen(
                $specialty
            ) > 150
        ) {
            throw new RuntimeException(
                'La spécialité ne peut pas dépasser 150 caractères.'
            );
        }

        $internshipCapacity =
            $this->normalizeInternshipCapacity(
                $data['internship_capacity']
                ?? 0
            );

        $accreditationStatus =
            $this->validateAccreditationStatus(
                $data['accreditation_status']
                ?? 'PENDING'
            );

        $latitude =
            $this->normalizeLatitude(
                $data['latitude']
                ?? null
            );

        $longitude =
            $this->normalizeLongitude(
                $data['longitude']
                ?? null
            );

        $this->hospitals->update(
            $id,
            $code,
            $name,
            $province,
            $city,
            $address,
            $phone,
            $email,
            $status,
            $facilityLevel,
            $specialty,
            $internshipCapacity,
            $accreditationStatus,
            $latitude,
            $longitude
        );
    }

    /**
     * Normalise le code institutionnel.
     */
    private function normalizeCode(
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
                'Le code de l’hôpital est obligatoire.'
            );
        }

        if (
            mb_strlen(
                $code
            ) > 50
        ) {
            throw new RuntimeException(
                'Le code de l’hôpital ne peut pas dépasser 50 caractères.'
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
     * Valide l'adresse email optionnelle.
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
                'L’adresse email de l’hôpital est invalide.'
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
     * Valide le statut général de l'organisation.
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
                'Le statut de l’hôpital est invalide.'
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
        $status =
            strtoupper(
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
     * Niveau de l'établissement.
     */
    private function normalizeFacilityLevel(
        mixed $value
    ): ?int {
        if (
            $value === null
            || trim(
                (string) $value
            ) === ''
        ) {
            return null;
        }

        if (
            filter_var(
                $value,
                FILTER_VALIDATE_INT
            ) === false
        ) {
            throw new RuntimeException(
                'Le niveau de l’établissement est invalide.'
            );
        }

        $level =
            (int) $value;

        if ($level < 0) {
            throw new RuntimeException(
                'Le niveau de l’établissement ne peut pas être négatif.'
            );
        }

        if ($level > 65535) {
            throw new RuntimeException(
                'Le niveau de l’établissement est trop élevé.'
            );
        }

        return $level;
    }

    /**
     * Capacité d'accueil des stagiaires.
     */
    private function normalizeInternshipCapacity(
        mixed $value
    ): int {
        if (
            $value === null
            || trim(
                (string) $value
            ) === ''
        ) {
            return 0;
        }

        if (
            filter_var(
                $value,
                FILTER_VALIDATE_INT
            ) === false
        ) {
            throw new RuntimeException(
                'La capacité de stage est invalide.'
            );
        }

        $capacity =
            (int) $value;

        if ($capacity < 0) {
            throw new RuntimeException(
                'La capacité de stage ne peut pas être négative.'
            );
        }

        return $capacity;
    }

    /**
     * Latitude comprise entre -90 et 90.
     */
    private function normalizeLatitude(
        mixed $value
    ): ?float {
        if (
            $value === null
            || trim(
                (string) $value
            ) === ''
        ) {
            return null;
        }

        if (!is_numeric($value)) {
            throw new RuntimeException(
                'La latitude doit être numérique.'
            );
        }

        $latitude =
            (float) $value;

        if (
            $latitude < -90
            || $latitude > 90
        ) {
            throw new RuntimeException(
                'La latitude doit être comprise entre -90 et 90.'
            );
        }

        return round(
            $latitude,
            8
        );
    }

    /**
     * Longitude comprise entre -180 et 180.
     */
    private function normalizeLongitude(
        mixed $value
    ): ?float {
        if (
            $value === null
            || trim(
                (string) $value
            ) === ''
        ) {
            return null;
        }

        if (!is_numeric($value)) {
            throw new RuntimeException(
                'La longitude doit être numérique.'
            );
        }

        $longitude =
            (float) $value;

        if (
            $longitude < -180
            || $longitude > 180
        ) {
            throw new RuntimeException(
                'La longitude doit être comprise entre -180 et 180.'
            );
        }

        return round(
            $longitude,
            8
        );
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