<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\Services;

use MedTrack\Modules\Academic\Repositories\AcademicProgramRepository;
use RuntimeException;

final class AcademicProgramService
{
    private const ALLOWED_STATUSES = [
        'ACTIVE',
        'INACTIVE',
    ];

    public function __construct(
        private readonly AcademicProgramRepository $programs
    ) {
    }

    /**
     * Retourne tous les programmes académiques.
     */
    public function all(): array
    {
        return $this->programs->all();
    }

    /**
     * Recherche un programme académique.
     */
    public function findById(
        int $id
    ): ?array {
        if ($id <= 0) {
            return null;
        }

        return $this->programs->findById(
            $id
        );
    }

    /**
     * Crée un programme académique.
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

        $this->validateRelationships(
            $data
        );

        if (
            $this->programs->codeExistsForUniversity(
                $data['university_id'],
                $data['code']
            )
        ) {
            throw new RuntimeException(
                'Un programme portant ce code existe déjà '
                . 'dans cette université.'
            );
        }

        return $this->programs->create(
            $data
        );
    }

    /**
     * Met à jour un programme académique.
     */
    public function update(
        int $id,
        array $data
    ): void {
        if ($id <= 0) {
            throw new RuntimeException(
                'Identifiant de programme académique invalide.'
            );
        }

        $program =
            $this->programs->findById(
                $id
            );

        if ($program === null) {
            throw new RuntimeException(
                'Le programme académique demandé est introuvable.'
            );
        }

        $data = $this->normalize(
            $data
        );

        $this->validate(
            $data
        );

        $this->validateRelationships(
            $data
        );

        if (
            $this->programs->codeExistsForUniversity(
                $data['university_id'],
                $data['code'],
                $id
            )
        ) {
            throw new RuntimeException(
                'Un programme portant ce code existe déjà '
                . 'dans cette université.'
            );
        }

        $this->programs->update(
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
        $facultyId = (int) (
            $data['faculty_id']
            ?? 0
        );

        $durationYears = trim(
            (string) (
                $data['duration_years']
                ?? ''
            )
        );

        return [
            'university_id' => (int) (
                $data['university_id']
                ?? 0
            ),

            'faculty_id' =>
                $facultyId > 0
                    ? $facultyId
                    : null,

            'code' => strtoupper(
                trim(
                    (string) (
                        $data['code']
                        ?? ''
                    )
                )
            ),

            'name' => trim(
                (string) (
                    $data['name']
                    ?? ''
                )
            ),

            'discipline_code' => strtoupper(
                trim(
                    (string) (
                        $data['discipline_code']
                        ?? ''
                    )
                )
            ),

            'duration_years' =>
                $durationYears !== ''
                    ? (int) $durationYears
                    : null,

            'status' => strtoupper(
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
        if ($data['university_id'] <= 0) {
            throw new RuntimeException(
                'Veuillez sélectionner une université.'
            );
        }

        if ($data['code'] === '') {
            throw new RuntimeException(
                'Le code du programme académique est obligatoire.'
            );
        }

        if (
            mb_strlen(
                $data['code']
            ) > 50
        ) {
            throw new RuntimeException(
                'Le code du programme ne peut pas dépasser '
                . '50 caractères.'
            );
        }

        if ($data['name'] === '') {
            throw new RuntimeException(
                'Le nom du programme académique est obligatoire.'
            );
        }

        if (
            mb_strlen(
                $data['name']
            ) > 255
        ) {
            throw new RuntimeException(
                'Le nom du programme ne peut pas dépasser '
                . '255 caractères.'
            );
        }

        if ($data['discipline_code'] === '') {
            throw new RuntimeException(
                'Le code de discipline est obligatoire.'
            );
        }

        if (
            mb_strlen(
                $data['discipline_code']
            ) > 80
        ) {
            throw new RuntimeException(
                'Le code de discipline ne peut pas dépasser '
                . '80 caractères.'
            );
        }

        if (
            $data['duration_years'] !== null
            && (
                $data['duration_years'] < 1
                || $data['duration_years'] > 20
            )
        ) {
            throw new RuntimeException(
                'La durée du programme doit être comprise '
                . 'entre 1 et 20 ans.'
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
                'Le statut du programme académique est invalide.'
            );
        }
    }

    /**
     * Vérifie les relations académiques.
     */
    private function validateRelationships(
        array $data
    ): void {
        if (
            !$this->programs->universityExists(
                $data['university_id']
            )
        ) {
            throw new RuntimeException(
                'L’université sélectionnée est introuvable.'
            );
        }

        /*
         * faculty_id est nullable dans le schéma.
         *
         * Si aucune faculté n'est sélectionnée,
         * le programme peut donc être directement
         * rattaché à l'université.
         */
        if ($data['faculty_id'] === null) {
            return;
        }

        /*
         * Une faculté sélectionnée doit appartenir
         * à l'université sélectionnée.
         *
         * Cette règle n'est pas garantie par les FK
         * actuelles et doit donc être appliquée ici.
         */
        if (
            !$this->programs->facultyBelongsToUniversity(
                $data['faculty_id'],
                $data['university_id']
            )
        ) {
            throw new RuntimeException(
                'La faculté sélectionnée n’appartient pas '
                . 'à l’université sélectionnée.'
            );
        }
    }
}