<?php

declare(strict_types=1);

namespace MedTrack\Modules\Internship\Services;

use InvalidArgumentException;
use MedTrack\Modules\Internship\Repositories\InternshipRepository;

final class InternshipService
{
    public function __construct(
        private readonly InternshipRepository $internships
    ) {
    }

    /**
     * Retourne tous les stages visibles
     * depuis la supervision plateforme.
     */
    public function all(): array
    {
        return $this->internships->all();
    }

    /**
     * Retourne un stage avec son contexte complet.
     */
    public function findById(
        int $id
    ): ?array {
        if ($id <= 0) {
            return null;
        }

        return $this->internships->findById(
            $id
        );
    }

    /**
     * Retourne les rotations d'un stage.
     */
    public function rotationsForInternship(
        int $internshipId
    ): array {
        $this->assertId(
            $internshipId,
            'Identifiant de stage invalide.'
        );

        return $this->internships
            ->rotationsForInternship(
                $internshipId
            );
    }

    /**
     * Retourne les indicateurs globaux
     * du module Stages.
     */
    public function metrics(): array
    {
        return $this->internships->metrics();
    }

    /**
     * Retourne les indicateurs
     * concernant les demandes de stage.
     */
    public function requestMetrics(): array
    {
        return $this->internships
            ->requestMetrics();
    }

    /**
     * Retourne les demandes récentes.
     */
    public function recentRequests(
        int $limit = 10
    ): array {
        if ($limit <= 0) {
            throw new InvalidArgumentException(
                'La limite doit être supérieure à zéro.'
            );
        }

        return $this->internships
            ->recentRequests(
                min(
                    $limit,
                    100
                )
            );
    }

    /**
     * Construit les données nécessaires
     * au tableau de bord Stages plateforme.
     */
    public function platformOverview(): array
    {
        return [
            'metrics' =>
                $this->metrics(),

            'request_metrics' =>
                $this->requestMetrics(),

            'internships' =>
                $this->all(),

            'recent_requests' =>
                $this->recentRequests(
                    10
                ),
        ];
    }

    /**
     * Construit la fiche complète
     * d'un stage.
     */
    public function platformShow(
        int $internshipId
    ): ?array {
        $this->assertId(
            $internshipId,
            'Identifiant de stage invalide.'
        );

        $internship =
            $this->findById(
                $internshipId
            );

        if ($internship === null) {
            return null;
        }

        return [
            'internship' =>
                $internship,

            'rotations' =>
                $this->rotationsForInternship(
                    $internshipId
                ),
        ];
    }

    private function assertId(
        int $id,
        string $message
    ): void {
        if ($id <= 0) {
            throw new InvalidArgumentException(
                $message
            );
        }
    }
}