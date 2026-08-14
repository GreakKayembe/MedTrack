<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\Services;

use MedTrack\Modules\Academic\Repositories\CohortRepository;
use RuntimeException;

final class CohortService
{
    public function __construct(
        private readonly CohortRepository $cohorts
    ) {
    }

    public function all(): array
    {
        return $this->cohorts->all();
    }

    public function findById(
        int $id
    ): ?array {
        return $this->cohorts->findById(
            $id
        );
    }

    public function create(
        array $data
    ): int {
        $normalized =
            $this->validate($data);

        if (
            $this->cohorts->exists(
                $normalized[
                    'academic_program_id'
                ],
                $normalized[
                    'academic_year_id'
                ],
                $normalized['name']
            )
        ) {
            throw new RuntimeException(
                'Cette cohorte existe déjà pour '
                . 'ce programme et cette année académique.'
            );
        }

        return $this->cohorts->create(
            $normalized
        );
    }

    public function update(
        int $id,
        array $data
    ): void {
        if (
            $this->cohorts->findById($id)
            === null
        ) {
            throw new RuntimeException(
                'Cohorte introuvable.'
            );
        }

        $normalized =
            $this->validate($data);

        if (
            $this->cohorts->exists(
                $normalized[
                    'academic_program_id'
                ],
                $normalized[
                    'academic_year_id'
                ],
                $normalized['name'],
                $id
            )
        ) {
            throw new RuntimeException(
                'Cette cohorte existe déjà pour '
                . 'ce programme et cette année académique.'
            );
        }

        $this->cohorts->update(
            $id,
            $normalized
        );
    }

    private function validate(
        array $data
    ): array {
        $academicProgramId =
            (int) (
                $data['academic_program_id']
                ?? 0
            );

        $academicYearId =
            (int) (
                $data['academic_year_id']
                ?? 0
            );

        $name = trim(
            (string) (
                $data['name']
                ?? ''
            )
        );


        /*
        |--------------------------------------------------------------------------
        | Academic program
        |--------------------------------------------------------------------------
        */

        if ($academicProgramId <= 0) {
            throw new RuntimeException(
                'Le programme académique est obligatoire.'
            );
        }

        if (
            !$this->cohorts
                ->academicProgramExists(
                    $academicProgramId
                )
        ) {
            throw new RuntimeException(
                'Le programme académique sélectionné '
                . 'est introuvable.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Academic year
        |--------------------------------------------------------------------------
        */

        if ($academicYearId <= 0) {
            throw new RuntimeException(
                'L’année académique est obligatoire.'
            );
        }

        if (
            !$this->cohorts
                ->academicYearExists(
                    $academicYearId
                )
        ) {
            throw new RuntimeException(
                'L’année académique sélectionnée '
                . 'est introuvable.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Name
        |--------------------------------------------------------------------------
        */

        if ($name === '') {
            throw new RuntimeException(
                'Le nom de la cohorte est obligatoire.'
            );
        }

        if (
            mb_strlen($name)
            > 100
        ) {
            throw new RuntimeException(
                'Le nom de la cohorte ne peut pas '
                . 'dépasser 100 caractères.'
            );
        }


        return [
            'academic_program_id' =>
                $academicProgramId,

            'academic_year_id' =>
                $academicYearId,

            'name' =>
                $name,
        ];
    }
}