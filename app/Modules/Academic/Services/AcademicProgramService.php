<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\Services;

use MedTrack\Core\Context\AccessContext;
use MedTrack\Core\Context\AccessContextResolver;
use MedTrack\Modules\Academic\Repositories\AcademicProgramRepository;
use RuntimeException;

final class AcademicProgramService
{
    private const ALLOWED_STATUSES = [
        'ACTIVE',
        'INACTIVE',
    ];

    private const UNIVERSITY_TYPE =
        'UNIVERSITY';

    public function __construct(
        private readonly AcademicProgramRepository $programs,
        private readonly AccessContextResolver $accessContextResolver
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Listing
    |--------------------------------------------------------------------------
    */

    /**
     * Retourne les programmes visibles
     * dans le contexte actif.
     */
    public function all(): array
    {
        $context =
            $this->accessContext();

        /*
        |--------------------------------------------------------------------------
        | Platform
        |--------------------------------------------------------------------------
        */

        if ($context->isPlatform()) {
            return $this->programs
                ->all();
        }

        /*
        |--------------------------------------------------------------------------
        | University
        |--------------------------------------------------------------------------
        */

        if ($this->isUniversityContext(
            $context
        )) {
            return $this->programs
                ->findByUniversity(
                    $context->organizationId()
                );
        }

        throw new RuntimeException(
            'Le contexte actif ne permet pas '
            . 'de consulter les programmes académiques.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Find
    |--------------------------------------------------------------------------
    */

    /**
     * Recherche un programme dans
     * le périmètre d'accès courant.
     */
    public function findById(
        int $id
    ): ?array {
        if ($id <= 0) {
            return null;
        }

        $context =
            $this->accessContext();

        /*
        |--------------------------------------------------------------------------
        | Platform
        |--------------------------------------------------------------------------
        */

        if ($context->isPlatform()) {
            return $this->programs
                ->findById(
                    $id
                );
        }

        /*
        |--------------------------------------------------------------------------
        | University
        |--------------------------------------------------------------------------
        */

        if ($this->isUniversityContext(
            $context
        )) {
            return $this->programs
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
     * Crée un programme académique.
     *
     * En contexte Université, university_id
     * est toujours imposé depuis AccessContext.
     */
    public function create(
        array $data
    ): int {
        $context =
            $this->accessContext();

        $data =
            $this->applyUniversityScope(
                $context,
                $data
            );

        $data =
            $this->normalize(
                $data
            );

        $this->validate(
            $data
        );

        $this->validateRelationships(
            $data
        );

        if (
            $this->programs
                ->codeExistsForUniversity(
                    $data['university_id'],
                    $data['code']
                )
        ) {
            throw new RuntimeException(
                'Un programme portant ce code existe déjà '
                . 'dans cette université.'
            );
        }

        return $this->programs
            ->create(
                $data
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    /**
     * Met à jour un programme.
     *
     * PLATFORM :
     * l'université peut éventuellement
     * être modifiée.
     *
     * UNIVERSITY :
     * university_id est verrouillé sur
     * l'organisation active.
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

        if ($this->isUniversityContext(
            $context
        )) {
            $this->updateAsUniversity(
                $context,
                $id,
                $data
            );

            return;
        }

        throw new RuntimeException(
            'Le contexte actif ne permet pas '
            . 'de modifier un programme académique.'
        );
    }

    /**
     * Mise à jour depuis l'administration centrale.
     */
    private function updateAsPlatform(
        int $id,
        array $data
    ): void {
        $program =
            $this->programs
                ->findById(
                    $id
                );

        if ($program === null) {
            throw new RuntimeException(
                'Le programme académique demandé est introuvable.'
            );
        }

        $data =
            $this->normalize(
                $data
            );

        $this->validate(
            $data
        );

        $this->validateRelationships(
            $data
        );

        if (
            $this->programs
                ->codeExistsForUniversity(
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

        $this->programs
            ->update(
                $id,
                $data
            );
    }

    /**
     * Mise à jour depuis une université.
     */
    private function updateAsUniversity(
        AccessContext $context,
        int $id,
        array $data
    ): void {
        $universityId =
            $context->organizationId();

        /*
         * Recherche impérativement limitée
         * à l'université active.
         */
        $program =
            $this->programs
                ->findByIdForUniversity(
                    $id,
                    $universityId
                );

        if ($program === null) {
            /*
             * 404 logique :
             * on ne révèle pas si le programme
             * existe dans une autre université.
             */
            throw new RuntimeException(
                'Le programme académique demandé est introuvable.'
            );
        }

        /*
         * university_id fourni par le navigateur
         * est volontairement ignoré.
         */
        $data['university_id'] =
            $universityId;

        $data =
            $this->normalize(
                $data
            );

        $this->validate(
            $data
        );

        $this->validateRelationships(
            $data
        );

        if (
            $this->programs
                ->codeExistsForUniversity(
                    $universityId,
                    $data['code'],
                    $id
                )
        ) {
            throw new RuntimeException(
                'Un programme portant ce code existe déjà '
                . 'dans votre université.'
            );
        }

        $this->programs
            ->updateForUniversity(
                $id,
                $universityId,
                $data
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Statistics
    |--------------------------------------------------------------------------
    */

    /**
     * Statistiques des programmes
     * de l'université active.
     */
    public function statistics(): array
    {
        $context =
            $this->accessContext();

        if (!$this->isUniversityContext(
            $context
        )) {
            return [];
        }

        $universityId =
            $context->organizationId();

        return [
            'total' =>
                $this->programs
                    ->countByUniversity(
                        $universityId
                    ),

            'active' =>
                $this->programs
                    ->countByUniversityAndStatus(
                        $universityId,
                        'ACTIVE'
                    ),

            'inactive' =>
                $this->programs
                    ->countByUniversityAndStatus(
                        $universityId,
                        'INACTIVE'
                    ),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Scope
    |--------------------------------------------------------------------------
    */

    /**
     * Applique automatiquement
     * l'université du contexte actif.
     */
    private function applyUniversityScope(
        AccessContext $context,
        array $data
    ): array {
        /*
        |--------------------------------------------------------------------------
        | Platform
        |--------------------------------------------------------------------------
        */

        if ($context->isPlatform()) {
            return $data;
        }

        /*
        |--------------------------------------------------------------------------
        | University
        |--------------------------------------------------------------------------
        */

        if ($this->isUniversityContext(
            $context
        )) {
            /*
             * Toute valeur university_id provenant
             * du navigateur est écrasée.
             */
            $data['university_id'] =
                $context->organizationId();

            return $data;
        }

        throw new RuntimeException(
            'Le contexte actif ne permet pas '
            . 'de gérer des programmes académiques.'
        );
    }

    /**
     * Vérifie que le contexte représente
     * réellement une université.
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
     * Résout le contexte actif.
     */
    private function accessContext(): AccessContext
    {
        return $this->accessContextResolver
            ->resolve();
    }

    /*
    |--------------------------------------------------------------------------
    | Normalization
    |--------------------------------------------------------------------------
    */

    /**
     * Normalise les données reçues.
     */
    private function normalize(
        array $data
    ): array {
        $facultyId =
            (int) (
                $data['faculty_id']
                ?? 0
            );

        $durationYears =
            trim(
                (string) (
                    $data['duration_years']
                    ?? ''
                )
            );

        return [
            'university_id' =>
                (int) (
                    $data['university_id']
                    ?? 0
                ),

            'faculty_id' =>
                $facultyId > 0
                    ? $facultyId
                    : null,

            'code' =>
                strtoupper(
                    trim(
                        (string) (
                            $data['code']
                            ?? ''
                        )
                    )
                ),

            'name' =>
                trim(
                    (string) (
                        $data['name']
                        ?? ''
                    )
                ),

            'discipline_code' =>
                strtoupper(
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

    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

    /**
     * Valide les données métier.
     */
    private function validate(
        array $data
    ): void {
        if ($data['university_id'] <= 0) {
            throw new RuntimeException(
                'Université invalide.'
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

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Vérifie les relations académiques.
     */
    private function validateRelationships(
        array $data
    ): void {
        if (
            !$this->programs
                ->universityExists(
                    $data['university_id']
                )
        ) {
            throw new RuntimeException(
                'L’université est introuvable.'
            );
        }

        /*
         * Un programme peut être directement
         * rattaché à l'université.
         */
        if ($data['faculty_id'] === null) {
            return;
        }

        /*
         * Une faculté ne peut être utilisée
         * que si elle appartient à la même université.
         */
        if (
            !$this->programs
                ->facultyBelongsToUniversity(
                    $data['faculty_id'],
                    $data['university_id']
                )
        ) {
            throw new RuntimeException(
                'La faculté sélectionnée n’appartient pas '
                . 'à cette université.'
            );
        }
    }
}