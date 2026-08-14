<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\Services;

use MedTrack\Modules\Academic\Repositories\FacultyRepository;
use RuntimeException;

final class FacultyService
{
    private const ALLOWED_STATUSES = [
        'ACTIVE',
        'INACTIVE',
    ];

    public function __construct(
        private readonly FacultyRepository $faculties
    ) {
    }

    /**
     * Retourne toutes les facultés.
     */
    public function all(): array
    {
        return $this->faculties->all();
    }

    /**
     * Retourne les facultés appartenant
     * à une université.
     */
    public function findByUniversity(
        int $universityId
    ): array {
        if ($universityId <= 0) {
            throw new RuntimeException(
                'Identifiant d’université invalide.'
            );
        }

        return $this->faculties->findByUniversity(
            $universityId
        );
    }

    /**
     * Recherche une faculté.
     */
    public function findById(
        int $id
    ): ?array {
        if ($id <= 0) {
            return null;
        }

        return $this->faculties->findById(
            $id
        );
    }

    /**
     * Crée une faculté.
     */
    public function create(
        array $data
    ): int {
        $data = $this->normalize(
            $data
        );

        $this->validate(
            $data
        );

        if (
            !$this->faculties->universityExists(
                $data['university_id']
            )
        ) {
            throw new RuntimeException(
                'L’université sélectionnée est introuvable.'
            );
        }

        if (
            $this->faculties->nameExistsForUniversity(
                $data['university_id'],
                $data['name']
            )
        ) {
            throw new RuntimeException(
                'Une faculté portant ce nom existe déjà '
                . 'dans cette université.'
            );
        }

        return $this->faculties->create(
            $data
        );
    }

    /**
     * Met à jour une faculté.
     */
    public function update(
        int $id,
        array $data
    ): void {
        if ($id <= 0) {
            throw new RuntimeException(
                'Identifiant de faculté invalide.'
            );
        }

        $faculty =
            $this->faculties->findById(
                $id
            );

        if ($faculty === null) {
            throw new RuntimeException(
                'La faculté demandée est introuvable.'
            );
        }

        $data = $this->normalize(
            $data
        );

        $this->validate(
            $data
        );

        if (
            !$this->faculties->universityExists(
                $data['university_id']
            )
        ) {
            throw new RuntimeException(
                'L’université sélectionnée est introuvable.'
            );
        }

        if (
            $this->faculties->nameExistsForUniversity(
                $data['university_id'],
                $data['name'],
                $id
            )
        ) {
            throw new RuntimeException(
                'Une faculté portant ce nom existe déjà '
                . 'dans cette université.'
            );
        }

        $this->faculties->update(
            $id,
            $data
        );
    }

    /**
     * Normalise les données reçues.
     */
    private function normalize(
        array $data
    ): array {
        $code = trim(
            (string) ($data['code'] ?? '')
        );

        $name = trim(
            (string) ($data['name'] ?? '')
        );

        $status = strtoupper(
            trim(
                (string) (
                    $data['status']
                    ?? 'ACTIVE'
                )
            )
        );

        return [
            'university_id' => (int) (
                $data['university_id']
                ?? 0
            ),

            'code' =>
                $code !== ''
                    ? strtoupper($code)
                    : null,

            'name' => $name,

            'status' => $status,
        ];
    }

    /**
     * Valide les règles métier.
     */
    private function validate(
        array $data
    ): void {
        if ($data['university_id'] <= 0) {
            throw new RuntimeException(
                'Veuillez sélectionner une université.'
            );
        }

        if ($data['name'] === '') {
            throw new RuntimeException(
                'Le nom de la faculté est obligatoire.'
            );
        }

        if (
            mb_strlen(
                $data['name']
            ) > 255
        ) {
            throw new RuntimeException(
                'Le nom de la faculté ne peut pas dépasser '
                . '255 caractères.'
            );
        }

        if (
            $data['code'] !== null
            && mb_strlen(
                $data['code']
            ) > 50
        ) {
            throw new RuntimeException(
                'Le code de la faculté ne peut pas dépasser '
                . '50 caractères.'
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
                'Le statut de la faculté est invalide.'
            );
        }
    }
}