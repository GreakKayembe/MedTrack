<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\Services;

use MedTrack\Modules\Academic\Repositories\StudentRepository;
use RuntimeException;

final class StudentService
{
    private const ALLOWED_GENDERS = [
        'M',
        'F',
        'OTHER',
        'UNSPECIFIED',
    ];

    private const ALLOWED_STATUSES = [
        'ACTIVE',
        'SUSPENDED',
        'GRADUATED',
        'INACTIVE',
    ];

    public function __construct(
        private readonly StudentRepository $students
    ) {
    }

    /**
     * Retourne tous les étudiants.
     *
     * Cette méthode est destinée notamment
     * au contexte PLATFORM.
     */
    public function all(): array
    {
        return $this->students->all();
    }

    /**
     * Retourne uniquement les étudiants appartenant
     * au périmètre d'une université.
     */
    public function allForUniversity(
        int $universityId
    ): array {
        if ($universityId <= 0) {
            throw new RuntimeException(
                'Identifiant d’université invalide.'
            );
        }

        return $this->students
            ->allForUniversity(
                $universityId
            );
    }

    /**
     * Recherche un étudiant par identifiant.
     *
     * Cette méthode effectue une recherche globale.
     */
    public function findById(
        int $id
    ): ?array {
        if ($id <= 0) {
            return null;
        }

        return $this->students->findById(
            $id
        );
    }

    /**
     * Recherche un étudiant uniquement dans
     * le périmètre d'une université.
     */
    public function findByIdForUniversity(
        int $id,
        int $universityId
    ): ?array {
        if (
            $id <= 0
            || $universityId <= 0
        ) {
            return null;
        }

        return $this->students
            ->findByIdForUniversity(
                $id,
                $universityId
            );
    }

    /**
     * Recherche un étudiant par UUID.
     */
    public function findByUuid(
        string $uuid
    ): ?array {
        $uuid = trim($uuid);

        if ($uuid === '') {
            return null;
        }

        return $this->students->findByUuid(
            $uuid
        );
    }

    /**
     * Recherche un étudiant par son compte utilisateur.
     */
    public function findByUserId(
        int $userId
    ): ?array {
        if ($userId <= 0) {
            return null;
        }

        return $this->students->findByUserId(
            $userId
        );
    }

    /**
     * Crée un étudiant.
     */
    public function create(
        array $data
    ): int {
        $normalized =
            $this->normalize(
                $data
            );

        $this->validate(
            $normalized
        );

        $this->validateUniqueness(
            $normalized
        );

        $normalized['uuid'] =
            $this->generateUuidV4();

        return $this->students->create(
            $normalized
        );
    }

    /**
     * Met à jour un étudiant.
     *
     * Cette méthode est destinée aux opérations
     * globales autorisées, notamment PLATFORM.
     */
    public function update(
        int $id,
        array $data
    ): void {
        if ($id <= 0) {
            throw new RuntimeException(
                'Identifiant d’étudiant invalide.'
            );
        }

        if (
            $this->students->findById(
                $id
            ) === null
        ) {
            throw new RuntimeException(
                'Étudiant introuvable.'
            );
        }

        $normalized =
            $this->normalize(
                $data
            );

        $this->validate(
            $normalized
        );

        $this->validateUniqueness(
            $normalized,
            $id
        );

        $this->students->update(
            $id,
            $normalized
        );
    }

    /**
     * Met à jour un étudiant uniquement s'il
     * appartient à l'université concernée.
     */
    public function updateForUniversity(
        int $id,
        int $universityId,
        array $data
    ): void {
        if ($id <= 0) {
            throw new RuntimeException(
                'Identifiant d’étudiant invalide.'
            );
        }

        if ($universityId <= 0) {
            throw new RuntimeException(
                'Identifiant d’université invalide.'
            );
        }

        if (
            $this->students
                ->findByIdForUniversity(
                    $id,
                    $universityId
                ) === null
        ) {
            throw new RuntimeException(
                'Cet étudiant n’appartient pas '
                . 'à cette université.'
            );
        }

        $normalized =
            $this->normalize(
                $data
            );

        $this->validate(
            $normalized
        );

        $this->validateUniqueness(
            $normalized,
            $id
        );

        $this->students->update(
            $id,
            $normalized
        );
    }

    /**
     * Normalise les données provenant
     * du contrôleur.
     */
    private function normalize(
        array $data
    ): array {
        return [
            'user_id' =>
                $this->nullablePositiveInteger(
                    $data['user_id']
                    ?? null
                ),

            'national_student_number' =>
                $this->nullableString(
                    $data[
                        'national_student_number'
                    ]
                    ?? null
                ),

            'first_name' =>
                trim(
                    (string) (
                        $data['first_name']
                        ?? ''
                    )
                ),

            'middle_name' =>
                $this->nullableString(
                    $data['middle_name']
                    ?? null
                ),

            'last_name' =>
                trim(
                    (string) (
                        $data['last_name']
                        ?? ''
                    )
                ),

            'gender' =>
                $this->nullableUppercaseString(
                    $data['gender']
                    ?? null
                ),

            'birth_date' =>
                $this->nullableString(
                    $data['birth_date']
                    ?? null
                ),

            'birth_place' =>
                $this->nullableString(
                    $data['birth_place']
                    ?? null
                ),

            'nationality' =>
                $this->nullableString(
                    $data['nationality']
                    ?? null
                ),

            'email' =>
                $this->nullableLowercaseString(
                    $data['email']
                    ?? null
                ),

            'phone' =>
                $this->nullableString(
                    $data['phone']
                    ?? null
                ),

            'status' =>
                strtoupper(
                    trim(
                        (string) (
                            $data['status']
                            ?? 'ACTIVE'
                        )
                    )
                ),
        ];
    }

    /**
     * Valide les données métier.
     */
    private function validate(
        array $data
    ): void {
        if ($data['first_name'] === '') {
            throw new RuntimeException(
                'Le prénom de l’étudiant est obligatoire.'
            );
        }

        if (
            mb_strlen(
                $data['first_name']
            ) > 150
        ) {
            throw new RuntimeException(
                'Le prénom ne peut pas dépasser 150 caractères.'
            );
        }

        if ($data['last_name'] === '') {
            throw new RuntimeException(
                'Le nom de l’étudiant est obligatoire.'
            );
        }

        if (
            mb_strlen(
                $data['last_name']
            ) > 150
        ) {
            throw new RuntimeException(
                'Le nom ne peut pas dépasser 150 caractères.'
            );
        }

        if (
            $data['middle_name'] !== null
            && mb_strlen(
                $data['middle_name']
            ) > 150
        ) {
            throw new RuntimeException(
                'Le postnom ne peut pas dépasser 150 caractères.'
            );
        }

        if (
            $data['national_student_number']
                !== null
            && mb_strlen(
                $data[
                    'national_student_number'
                ]
            ) > 80
        ) {
            throw new RuntimeException(
                'Le numéro national étudiant ne peut pas dépasser 80 caractères.'
            );
        }

        if (
            $data['gender'] !== null
            && !in_array(
                $data['gender'],
                self::ALLOWED_GENDERS,
                true
            )
        ) {
            throw new RuntimeException(
                'Le genre sélectionné est invalide.'
            );
        }

        if (
            !in_array(
                $data['status'],
                self::ALLOWED_STATUSES,
                true
            )
        ) {
            throw new RuntimeException(
                'Le statut de l’étudiant est invalide.'
            );
        }

        if (
            $data['email'] !== null
            && !filter_var(
                $data['email'],
                FILTER_VALIDATE_EMAIL
            )
        ) {
            throw new RuntimeException(
                'L’adresse e-mail de l’étudiant est invalide.'
            );
        }

        if (
            $data['email'] !== null
            && mb_strlen(
                $data['email']
            ) > 190
        ) {
            throw new RuntimeException(
                'L’adresse e-mail ne peut pas dépasser 190 caractères.'
            );
        }

        if (
            $data['phone'] !== null
            && mb_strlen(
                $data['phone']
            ) > 30
        ) {
            throw new RuntimeException(
                'Le numéro de téléphone ne peut pas dépasser 30 caractères.'
            );
        }

        if (
            $data['birth_place'] !== null
            && mb_strlen(
                $data['birth_place']
            ) > 150
        ) {
            throw new RuntimeException(
                'Le lieu de naissance ne peut pas dépasser 150 caractères.'
            );
        }

        if (
            $data['nationality'] !== null
            && mb_strlen(
                $data['nationality']
            ) > 100
        ) {
            throw new RuntimeException(
                'La nationalité ne peut pas dépasser 100 caractères.'
            );
        }

        $this->validateBirthDate(
            $data['birth_date']
        );
    }

    /**
     * Vérifie les contraintes d'unicité
     * applicatives avant l'écriture SQL.
     */
    private function validateUniqueness(
        array $data,
        ?int $exceptId = null
    ): void {
        $nationalNumber =
            $data[
                'national_student_number'
            ];

        if (
            $nationalNumber !== null
            && $this->students
                ->nationalStudentNumberExists(
                    $nationalNumber,
                    $exceptId
                )
        ) {
            throw new RuntimeException(
                'Ce numéro national étudiant est déjà utilisé.'
            );
        }

        $userId =
            $data['user_id'];

        if (
            $userId !== null
            && $this->students->userIdExists(
                $userId,
                $exceptId
            )
        ) {
            throw new RuntimeException(
                'Ce compte utilisateur est déjà associé à un étudiant.'
            );
        }
    }

    /**
     * Valide la date de naissance.
     */
    private function validateBirthDate(
        ?string $birthDate
    ): void {
        if ($birthDate === null) {
            return;
        }

        $date =
            \DateTimeImmutable::createFromFormat(
                '!Y-m-d',
                $birthDate
            );

        $errors =
            \DateTimeImmutable::getLastErrors();

        if (
            $date === false
            || (
                is_array($errors)
                && (
                    $errors['warning_count'] > 0
                    || $errors['error_count'] > 0
                )
            )
            || $date->format('Y-m-d')
                !== $birthDate
        ) {
            throw new RuntimeException(
                'La date de naissance est invalide.'
            );
        }

        $today =
            new \DateTimeImmutable(
                'today'
            );

        if ($date > $today) {
            throw new RuntimeException(
                'La date de naissance ne peut pas être dans le futur.'
            );
        }
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
     * Convertit une chaîne optionnelle
     * en minuscules.
     */
    private function nullableLowercaseString(
        mixed $value
    ): ?string {
        $value =
            $this->nullableString(
                $value
            );

        return $value !== null
            ? mb_strtolower($value)
            : null;
    }

    /**
     * Convertit une chaîne optionnelle
     * en majuscules.
     */
    private function nullableUppercaseString(
        mixed $value
    ): ?string {
        $value =
            $this->nullableString(
                $value
            );

        return $value !== null
            ? mb_strtoupper($value)
            : null;
    }

    /**
     * Convertit une valeur optionnelle
     * en entier positif.
     */
    private function nullablePositiveInteger(
        mixed $value
    ): ?int {
        if (
            $value === null
            || $value === ''
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
                'Identifiant utilisateur invalide.'
            );
        }

        $value = (int) $value;

        if ($value <= 0) {
            throw new RuntimeException(
                'Identifiant utilisateur invalide.'
            );
        }

        return $value;
    }

    /**
     * Génère un UUID version 4
     * sans dépendance externe.
     */
    private function generateUuidV4(): string
    {
        $bytes = random_bytes(16);

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