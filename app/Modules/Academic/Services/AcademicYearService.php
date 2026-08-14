<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\Services;

use DateTimeImmutable;
use MedTrack\Modules\Academic\Repositories\AcademicYearRepository;
use RuntimeException;

final class AcademicYearService
{
    private const ALLOWED_STATUSES = [
        'PLANNED',
        'OPEN',
        'CLOSED',
    ];

    public function __construct(
        private readonly AcademicYearRepository $academicYears
    ) {
    }

    /**
     * Retourne toutes les années académiques.
     */
    public function all(): array
    {
        return $this->academicYears->all();
    }

    /**
     * Recherche une année académique
     * à partir de son identifiant.
     */
    public function findById(
        int $id
    ): ?array {
        if ($id <= 0) {
            return null;
        }

        return $this->academicYears->findById(
            $id
        );
    }

    /**
     * Crée une nouvelle année académique.
     */
    public function create(
        array $data
    ): int {
        $validated = $this->validate(
            $data
        );

        if (
            $this->academicYears->labelExists(
                $validated['label']
            )
        ) {
            throw new RuntimeException(
                'Une année académique avec ce libellé existe déjà.'
            );
        }

        return $this->academicYears->create(
            $validated
        );
    }

    /**
     * Met à jour une année académique existante.
     */
    public function update(
        int $id,
        array $data
    ): void {
        if ($id <= 0) {
            throw new RuntimeException(
                'Identifiant d’année académique invalide.'
            );
        }

        $existing =
            $this->academicYears->findById(
                $id
            );

        if ($existing === null) {
            throw new RuntimeException(
                'Année académique introuvable.'
            );
        }

        $validated = $this->validate(
            $data
        );

        if (
            $this->academicYears->labelExists(
                $validated['label'],
                $id
            )
        ) {
            throw new RuntimeException(
                'Une autre année académique utilise déjà ce libellé.'
            );
        }

        $this->academicYears->update(
            $id,
            $validated
        );
    }

    /**
     * Retourne les statistiques utilisées
     * par l'interface des années académiques.
     */
    public function statistics(): array
    {
        return [
            'total' =>
                $this->academicYears->count(),

            'planned' =>
                $this->academicYears->countByStatus(
                    'PLANNED'
                ),

            'open' =>
                $this->academicYears->countByStatus(
                    'OPEN'
                ),

            'closed' =>
                $this->academicYears->countByStatus(
                    'CLOSED'
                ),
        ];
    }

    /**
     * Valide et normalise les données
     * d'une année académique.
     */
    private function validate(
        array $data
    ): array {
        $label = trim(
            (string) (
                $data['label']
                ?? ''
            )
        );

        $startsOn = trim(
            (string) (
                $data['starts_on']
                ?? ''
            )
        );

        $endsOn = trim(
            (string) (
                $data['ends_on']
                ?? ''
            )
        );

        $status = strtoupper(
            trim(
                (string) (
                    $data['status']
                    ?? 'PLANNED'
                )
            )
        );


        /*
        |--------------------------------------------------------------------------
        | Label
        |--------------------------------------------------------------------------
        */

        if ($label === '') {
            throw new RuntimeException(
                'Le libellé de l’année académique est obligatoire.'
            );
        }

        if (mb_strlen($label) > 50) {
            throw new RuntimeException(
                'Le libellé de l’année académique ne peut pas dépasser 50 caractères.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Dates
        |--------------------------------------------------------------------------
        */

        if ($startsOn === '') {
            throw new RuntimeException(
                'La date de début est obligatoire.'
            );
        }

        if ($endsOn === '') {
            throw new RuntimeException(
                'La date de fin est obligatoire.'
            );
        }

        $startDate =
            $this->parseDate(
                $startsOn,
                'La date de début est invalide.'
            );

        $endDate =
            $this->parseDate(
                $endsOn,
                'La date de fin est invalide.'
            );

        if ($endDate <= $startDate) {
            throw new RuntimeException(
                'La date de fin doit être postérieure à la date de début.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        if (
            !in_array(
                $status,
                self::ALLOWED_STATUSES,
                true
            )
        ) {
            throw new RuntimeException(
                'Le statut de l’année académique est invalide.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Normalized data
        |--------------------------------------------------------------------------
        */

        return [
            'label' => $label,

            'starts_on' =>
                $startDate->format(
                    'Y-m-d'
                ),

            'ends_on' =>
                $endDate->format(
                    'Y-m-d'
                ),

            'status' => $status,
        ];
    }

    /**
     * Parse strictement une date au format YYYY-MM-DD.
     */
    private function parseDate(
        string $value,
        string $errorMessage
    ): DateTimeImmutable {
        $date =
            DateTimeImmutable::createFromFormat(
                '!Y-m-d',
                $value
            );

        $errors =
            DateTimeImmutable::getLastErrors();

        /*
         * getLastErrors() retourne false lorsqu'aucune
         * erreur ni aucun avertissement n'est détecté.
         */
        $hasErrors =
            is_array($errors)
            && (
                $errors['warning_count'] > 0
                || $errors['error_count'] > 0
            );

        if (
            $date === false
            || $hasErrors
            || $date->format('Y-m-d') !== $value
        ) {
            throw new RuntimeException(
                $errorMessage
            );
        }

        return $date;
    }
}