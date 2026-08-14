<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\Services;

use MedTrack\Modules\Academic\Repositories\StudyLevelRepository;
use RuntimeException;

final class StudyLevelService
{
    public function __construct(
        private readonly StudyLevelRepository $studyLevels
    ) {
    }

    /**
     * Retourne tous les niveaux d'études.
     */
    public function all(): array
    {
        return $this->studyLevels->all();
    }

    /**
     * Recherche un niveau d'études.
     */
    public function findById(
        int $id
    ): ?array {
        if ($id <= 0) {
            return null;
        }

        return $this->studyLevels->findById(
            $id
        );
    }

    /**
     * Retourne quelques statistiques simples.
     */
    public function statistics(): array
    {
        return [
            'total' => $this->studyLevels->count(),
        ];
    }

    /**
     * Crée un nouveau niveau d'études.
     */
    public function create(
        array $data
    ): int {
        $normalized = $this->validate(
            $data
        );

        if (
            $this->studyLevels->codeExists(
                $normalized['code']
            )
        ) {
            throw new RuntimeException(
                'Un niveau d’études avec ce code existe déjà.'
            );
        }

        return $this->studyLevels->create(
            $normalized
        );
    }

    /**
     * Met à jour un niveau d'études existant.
     */
    public function update(
        int $id,
        array $data
    ): void {
        if ($id <= 0) {
            throw new RuntimeException(
                'Identifiant du niveau d’études invalide.'
            );
        }

        $existing =
            $this->studyLevels->findById(
                $id
            );

        if ($existing === null) {
            throw new RuntimeException(
                'Le niveau d’études demandé est introuvable.'
            );
        }

        $normalized = $this->validate(
            $data
        );

        if (
            $this->studyLevels->codeExists(
                $normalized['code'],
                $id
            )
        ) {
            throw new RuntimeException(
                'Un autre niveau d’études utilise déjà ce code.'
            );
        }

        $this->studyLevels->update(
            $id,
            $normalized
        );
    }

    /**
     * Validation et normalisation des données.
     */
    private function validate(
        array $data
    ): array {
        $code = strtoupper(
            trim(
                (string) ($data['code'] ?? '')
            )
        );

        $name = trim(
            (string) ($data['name'] ?? '')
        );

        $ordinalRaw =
            $data['ordinal'] ?? null;


        /*
        |--------------------------------------------------------------------------
        | Code
        |--------------------------------------------------------------------------
        */

        if ($code === '') {
            throw new RuntimeException(
                'Le code du niveau d’études est obligatoire.'
            );
        }

        if (mb_strlen($code) > 50) {
            throw new RuntimeException(
                'Le code du niveau d’études ne peut pas dépasser 50 caractères.'
            );
        }

        /*
         * On autorise par exemple :
         *
         * L1
         * L2
         * L3
         * M1
         * M2
         * BAC1
         * DOCTORAT
         * SPECIALITE-1
         */
        if (
            preg_match(
                '/^[A-Z0-9_-]+$/',
                $code
            ) !== 1
        ) {
            throw new RuntimeException(
                'Le code ne peut contenir que des lettres, chiffres, tirets et underscores.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Name
        |--------------------------------------------------------------------------
        */

        if ($name === '') {
            throw new RuntimeException(
                'Le nom du niveau d’études est obligatoire.'
            );
        }

        if (mb_strlen($name) > 100) {
            throw new RuntimeException(
                'Le nom du niveau d’études ne peut pas dépasser 100 caractères.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Ordinal
        |--------------------------------------------------------------------------
        */

        $ordinal = null;

        if (
            $ordinalRaw !== null
            && $ordinalRaw !== ''
        ) {
            $ordinalString =
                trim((string) $ordinalRaw);

            if (
                filter_var(
                    $ordinalString,
                    FILTER_VALIDATE_INT
                ) === false
            ) {
                throw new RuntimeException(
                    'L’ordre académique doit être un nombre entier.'
                );
            }

            $ordinal =
                (int) $ordinalString;

            /*
             * SMALLINT UNSIGNED autorise 0,
             * mais un ordre métier commence ici à 1.
             */
            if ($ordinal < 1) {
                throw new RuntimeException(
                    'L’ordre académique doit être supérieur ou égal à 1.'
                );
            }

            if ($ordinal > 65535) {
                throw new RuntimeException(
                    'L’ordre académique est trop élevé.'
                );
            }
        }


        return [
            'code' => $code,
            'name' => $name,
            'ordinal' => $ordinal,
        ];
    }
}