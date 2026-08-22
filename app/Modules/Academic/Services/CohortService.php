<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\Services;

use MedTrack\Core\Context\AccessContext;
use MedTrack\Core\Context\AccessContextResolver;
use MedTrack\Modules\Academic\Repositories\CohortRepository;
use RuntimeException;

final class CohortService
{
    private const UNIVERSITY_TYPE =
        'UNIVERSITY';

    public function __construct(
        private readonly CohortRepository $cohorts,
        private readonly AccessContextResolver $accessContextResolver
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Listing
    |--------------------------------------------------------------------------
    */

    /**
     * Retourne les cohortes visibles
     * dans le contexte actif.
     */
    public function all(): array
    {
        $context =
            $this->accessContext();

        if ($context->isPlatform()) {
            return $this->cohorts
                ->all();
        }

        if (
            $this->isUniversityContext(
                $context
            )
        ) {
            return $this->cohorts
                ->findByUniversity(
                    $context->organizationId()
                );
        }

        throw new RuntimeException(
            'Le contexte actif ne permet pas '
            . 'de consulter les cohortes.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Find
    |--------------------------------------------------------------------------
    */

    /**
     * Recherche une cohorte dans
     * le périmètre actif.
     */
    public function findById(
        int $id
    ): ?array {
        if ($id <= 0) {
            return null;
        }

        $context =
            $this->accessContext();

        if ($context->isPlatform()) {
            return $this->cohorts
                ->findById(
                    $id
                );
        }

        if (
            $this->isUniversityContext(
                $context
            )
        ) {
            return $this->cohorts
                ->findByIdForUniversity(
                    $id,
                    $context->organizationId()
                );
        }

        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    /**
     * Crée une cohorte.
     */
    public function create(
        array $data
    ): int {
        $context =
            $this->accessContext();

        $normalized =
            $this->validate(
                $data
            );

        /*
        |--------------------------------------------------------------------------
        | University scope
        |--------------------------------------------------------------------------
        */

        if (
            $this->isUniversityContext(
                $context
            )
        ) {
            $this->ensureProgramBelongsToUniversity(
                $normalized['academic_program_id'],
                $context->organizationId()
            );
        } elseif (!$context->isPlatform()) {
            throw new RuntimeException(
                'Le contexte actif ne permet pas '
                . 'de créer une cohorte.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Uniqueness
        |--------------------------------------------------------------------------
        */

        if (
            $this->cohorts->exists(
                $normalized['academic_program_id'],
                $normalized['academic_year_id'],
                $normalized['name']
            )
        ) {
            throw new RuntimeException(
                'Cette cohorte existe déjà pour '
                . 'ce programme et cette année académique.'
            );
        }

        return $this->cohorts
            ->create(
                $normalized
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    /**
     * Met à jour une cohorte.
     */
    public function update(
        int $id,
        array $data
    ): void {
        if ($id <= 0) {
            throw new RuntimeException(
                'Identifiant de cohorte invalide.'
            );
        }

        $context =
            $this->accessContext();

        /*
        |--------------------------------------------------------------------------
        | Platform
        |--------------------------------------------------------------------------
        */

        if ($context->isPlatform()) {
            $this->updateAsPlatform(
                $id,
                $data
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | University
        |--------------------------------------------------------------------------
        */

        if (
            $this->isUniversityContext(
                $context
            )
        ) {
            $this->updateAsUniversity(
                $context,
                $id,
                $data
            );

            return;
        }

        throw new RuntimeException(
            'Le contexte actif ne permet pas '
            . 'de modifier une cohorte.'
        );
    }

    /**
     * Mise à jour depuis PLATFORM.
     */
    private function updateAsPlatform(
        int $id,
        array $data
    ): void {
        $existing =
            $this->cohorts
                ->findById(
                    $id
                );

        if ($existing === null) {
            throw new RuntimeException(
                'Cohorte introuvable.'
            );
        }

        $normalized =
            $this->validate(
                $data
            );

        if (
            $this->cohorts->exists(
                $normalized['academic_program_id'],
                $normalized['academic_year_id'],
                $normalized['name'],
                $id
            )
        ) {
            throw new RuntimeException(
                'Cette cohorte existe déjà pour '
                . 'ce programme et cette année académique.'
            );
        }

        $this->cohorts
            ->update(
                $id,
                $normalized
            );
    }

    /**
     * Mise à jour depuis UNIVERSITY.
     */
    private function updateAsUniversity(
        AccessContext $context,
        int $id,
        array $data
    ): void {
        $universityId =
            $context->organizationId();

        /*
         * La cohorte actuelle doit appartenir
         * à l'université active.
         */
        $existing =
            $this->cohorts
                ->findByIdForUniversity(
                    $id,
                    $universityId
                );

        if ($existing === null) {
            throw new RuntimeException(
                'Cohorte introuvable.'
            );
        }

        $normalized =
            $this->validate(
                $data
            );

        /*
         * Le nouveau programme sélectionné
         * doit lui aussi appartenir
         * à l'université active.
         */
        $this->ensureProgramBelongsToUniversity(
            $normalized['academic_program_id'],
            $universityId
        );

        if (
            $this->cohorts->exists(
                $normalized['academic_program_id'],
                $normalized['academic_year_id'],
                $normalized['name'],
                $id
            )
        ) {
            throw new RuntimeException(
                'Cette cohorte existe déjà pour '
                . 'ce programme et cette année académique.'
            );
        }

        $this->cohorts
            ->updateForUniversity(
                $id,
                $universityId,
                $normalized
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Statistics
    |--------------------------------------------------------------------------
    */

    /**
     * Retourne les statistiques
     * de l'université active.
     */
    public function statistics(): array
    {
        $context =
            $this->accessContext();

        if (
            !$this->isUniversityContext(
                $context
            )
        ) {
            return [];
        }

        $universityId =
            $context->organizationId();

        return [
            'total' =>
                $this->cohorts
                    ->countByUniversity(
                        $universityId
                    ),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

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

        $name =
            trim(
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
            mb_strlen(
                $name
            ) > 100
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

    /*
    |--------------------------------------------------------------------------
    | Scope helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Vérifie que le programme appartient
     * réellement à l'université active.
     */
    private function ensureProgramBelongsToUniversity(
        int $academicProgramId,
        int $universityId
    ): void {
        if (
            !$this->cohorts
                ->academicProgramBelongsToUniversity(
                    $academicProgramId,
                    $universityId
                )
        ) {
            throw new RuntimeException(
                'Le programme académique sélectionné '
                . 'n’appartient pas à votre université.'
            );
        }
    }

    /**
     * Vérifie que le contexte est UNIVERSITY.
     */
    private function isUniversityContext(
        AccessContext $context
    ): bool {
        return
            $context->isOrganization()
            && strtoupper(
                trim(
                    $context->organizationType()
                )
            ) === self::UNIVERSITY_TYPE;
    }

    /**
     * Résout le contexte d'accès actif.
     */
    private function accessContext(): AccessContext
    {
        return $this->accessContextResolver
            ->resolve();
    }
}