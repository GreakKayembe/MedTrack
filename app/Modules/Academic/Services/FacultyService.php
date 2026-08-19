<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\Services;

use MedTrack\Core\Context\AccessContext;
use MedTrack\Core\Context\AccessContextResolver;
use MedTrack\Modules\Academic\Repositories\FacultyRepository;
use RuntimeException;

final class FacultyService
{
    private const ALLOWED_STATUSES = [
        'ACTIVE',
        'INACTIVE',
    ];

    private const UNIVERSITY_TYPE =
        'UNIVERSITY';

    public function __construct(
        private readonly FacultyRepository $faculties,
        private readonly AccessContextResolver $accessContextResolver
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Listing
    |--------------------------------------------------------------------------
    */

    /**
     * Retourne les facultés visibles
     * dans le contexte actif.
     *
     * PLATFORM :
     * toutes les facultés.
     *
     * UNIVERSITY :
     * uniquement les facultés de
     * l'université active.
     */
    public function all(): array
    {
        $context =
            $this->context();

        if ($context->isPlatform()) {
            return $this->faculties
                ->all();
        }

        $universityId =
            $this->universityIdFromContext(
                $context
            );

        return $this->faculties
            ->findByUniversity(
                $universityId
            );
    }

    /**
     * Retourne les facultés appartenant
     * à une université.
     *
     * En contexte Université, l'identifiant
     * transmis est ignoré et remplacé par
     * celui du contexte actif.
     */
    public function findByUniversity(
        int $universityId
    ): array {
        $context =
            $this->context();

        if ($context->isPlatform()) {
            $this->assertUniversityId(
                $universityId
            );

            return $this->faculties
                ->findByUniversity(
                    $universityId
                );
        }

        $contextUniversityId =
            $this->universityIdFromContext(
                $context
            );

        return $this->faculties
            ->findByUniversity(
                $contextUniversityId
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Detail
    |--------------------------------------------------------------------------
    */

    /**
     * Recherche une faculté visible
     * depuis le contexte actif.
     */
    public function findById(
        int $id
    ): ?array {
        if ($id <= 0) {
            return null;
        }

        $context =
            $this->context();

        if ($context->isPlatform()) {
            return $this->faculties
                ->findById(
                    $id
                );
        }

        $universityId =
            $this->universityIdFromContext(
                $context
            );

        return $this->faculties
            ->findByIdForUniversity(
                $id,
                $universityId
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    /**
     * Crée une faculté.
     *
     * En contexte Université, university_id
     * provient exclusivement du contexte
     * serveur.
     */
    public function create(
        array $data
    ): int {
        $context =
            $this->context();

        $data =
            $this->normalize(
                $data
            );

        /*
         * En contexte institutionnel,
         * university_id transmis par le
         * navigateur est volontairement ignoré.
         */
        if (!$context->isPlatform()) {
            $data['university_id'] =
                $this->universityIdFromContext(
                    $context
                );
        }

        $this->validate(
            $data
        );

        $universityId =
            (int) $data['university_id'];

        if (
            !$this->faculties
                ->universityExists(
                    $universityId
                )
        ) {
            throw new RuntimeException(
                'L’université sélectionnée '
                . 'est introuvable.'
            );
        }

        if (
            $this->faculties
                ->nameExistsForUniversity(
                    $universityId,
                    $data['name']
                )
        ) {
            throw new RuntimeException(
                'Une faculté portant ce nom '
                . 'existe déjà dans cette université.'
            );
        }

        if (
            $data['code'] !== null
            && $this->faculties
                ->codeExistsForUniversity(
                    $universityId,
                    $data['code']
                )
        ) {
            throw new RuntimeException(
                'Une faculté utilisant ce code '
                . 'existe déjà dans cette université.'
            );
        }

        return $this->faculties
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
     * Met à jour une faculté.
     *
     * PLATFORM :
     * peut utiliser la méthode globale.
     *
     * UNIVERSITY :
     * modification strictement limitée
     * à son organisation active.
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

        $context =
            $this->context();

        /*
        |--------------------------------------------------------------------------
        | Platform
        |--------------------------------------------------------------------------
        */

        if ($context->isPlatform()) {
            $faculty =
                $this->faculties
                    ->findById(
                        $id
                    );

            if ($faculty === null) {
                throw new RuntimeException(
                    'La faculté demandée '
                    . 'est introuvable.'
                );
            }

            $data =
                $this->normalize(
                    $data
                );

            $this->validate(
                $data
            );

            $universityId =
                (int) $data[
                    'university_id'
                ];

            if (
                !$this->faculties
                    ->universityExists(
                        $universityId
                    )
            ) {
                throw new RuntimeException(
                    'L’université sélectionnée '
                    . 'est introuvable.'
                );
            }

            $this->assertUniqueFaculty(
                universityId:
                    $universityId,
                name:
                    $data['name'],
                code:
                    $data['code'],
                exceptId:
                    $id
            );

            $this->faculties
                ->update(
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

        $universityId =
            $this->universityIdFromContext(
                $context
            );

        $faculty =
            $this->faculties
                ->findByIdForUniversity(
                    $id,
                    $universityId
                );

        if ($faculty === null) {
            /*
             * On ne révèle volontairement pas
             * qu'une faculté existe éventuellement
             * dans une autre université.
             */
            throw new RuntimeException(
                'La faculté demandée '
                . 'est introuvable.'
            );
        }

        $data =
            $this->normalize(
                $data
            );

        /*
         * Le rattachement institutionnel
         * est imposé par le serveur.
         */
        $data['university_id'] =
            $universityId;

        $this->validate(
            $data
        );

        $this->assertUniqueFaculty(
            universityId:
                $universityId,
            name:
                $data['name'],
            code:
                $data['code'],
            exceptId:
                $id
        );

        $updated =
            $this->faculties
                ->updateForUniversity(
                    $id,
                    $universityId,
                    $data
                );

        /*
         * rowCount() peut retourner 0 lorsque
         * les valeurs envoyées sont identiques
         * aux valeurs existantes.
         *
         * Nous ne considérons donc pas cela
         * automatiquement comme une erreur.
         */
        if (!$updated) {
            $stillExists =
                $this->faculties
                    ->findByIdForUniversity(
                        $id,
                        $universityId
                    );

            if ($stillExists === null) {
                throw new RuntimeException(
                    'Impossible de modifier '
                    . 'cette faculté.'
                );
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Statistics
    |--------------------------------------------------------------------------
    */

    /**
     * Statistiques visibles dans
     * l'espace Université.
     */
    public function statistics(): array
    {
        $context =
            $this->context();

        if ($context->isPlatform()) {
            $all =
                $this->faculties
                    ->all();

            $active = 0;
            $inactive = 0;

            foreach ($all as $faculty) {
                $status =
                    strtoupper(
                        trim(
                            (string) (
                                $faculty['status']
                                ?? ''
                            )
                        )
                    );

                if ($status === 'ACTIVE') {
                    $active++;
                }

                if ($status === 'INACTIVE') {
                    $inactive++;
                }
            }

            return [
                'total' =>
                    count(
                        $all
                    ),

                'active' =>
                    $active,

                'inactive' =>
                    $inactive,
            ];
        }

        $universityId =
            $this->universityIdFromContext(
                $context
            );

        return [
            'total' =>
                $this->faculties
                    ->countForUniversity(
                        $universityId
                    ),

            'active' =>
                $this->faculties
                    ->countByStatusForUniversity(
                        $universityId,
                        'ACTIVE'
                    ),

            'inactive' =>
                $this->faculties
                    ->countByStatusForUniversity(
                        $universityId,
                        'INACTIVE'
                    ),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Context
    |--------------------------------------------------------------------------
    */

    /**
     * Retourne le contexte actif.
     */
    private function context(): AccessContext
    {
        return $this->accessContextResolver
            ->resolve();
    }

    /**
     * Retourne l'université active
     * depuis le contexte serveur.
     */
    private function universityIdFromContext(
        AccessContext $context
    ): int {
        if (!$context->isOrganization()) {
            throw new RuntimeException(
                'Un contexte universitaire '
                . 'est requis pour cette opération.'
            );
        }

        $organizationType =
            strtoupper(
                trim(
                    $context->organizationType()
                )
            );

        if (
            $organizationType
            !== self::UNIVERSITY_TYPE
        ) {
            throw new RuntimeException(
                'Cette opération est réservée '
                . 'aux universités.'
            );
        }

        $universityId =
            $context->organizationId();

        $this->assertUniversityId(
            $universityId
        );

        return $universityId;
    }

    /*
    |--------------------------------------------------------------------------
    | Uniqueness
    |--------------------------------------------------------------------------
    */

    private function assertUniqueFaculty(
        int $universityId,
        string $name,
        ?string $code,
        ?int $exceptId = null
    ): void {
        if (
            $this->faculties
                ->nameExistsForUniversity(
                    $universityId,
                    $name,
                    $exceptId
                )
        ) {
            throw new RuntimeException(
                'Une faculté portant ce nom '
                . 'existe déjà dans cette université.'
            );
        }

        if (
            $code !== null
            && $this->faculties
                ->codeExistsForUniversity(
                    $universityId,
                    $code,
                    $exceptId
                )
        ) {
            throw new RuntimeException(
                'Une faculté utilisant ce code '
                . 'existe déjà dans cette université.'
            );
        }
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
        $code =
            trim(
                (string) (
                    $data['code']
                    ?? ''
                )
            );

        $name =
            trim(
                (string) (
                    $data['name']
                    ?? ''
                )
            );

        $status =
            strtoupper(
                trim(
                    (string) (
                        $data['status']
                        ?? 'ACTIVE'
                    )
                )
            );

        return [
            /*
             * Cette valeur ne sera réellement
             * utilisée depuis le navigateur
             * qu'en contexte PLATFORM.
             */
            'university_id' =>
                (int) (
                    $data['university_id']
                    ?? 0
                ),

            'code' =>
                $code !== ''
                    ? strtoupper(
                        $code
                    )
                    : null,

            'name' =>
                $name,

            'status' =>
                $status,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

    /**
     * Valide les règles métier.
     */
    private function validate(
        array $data
    ): void {
        if (
            (int) $data['university_id']
            <= 0
        ) {
            throw new RuntimeException(
                'Veuillez sélectionner '
                . 'une université.'
            );
        }

        if ($data['name'] === '') {
            throw new RuntimeException(
                'Le nom de la faculté '
                . 'est obligatoire.'
            );
        }

        if (
            mb_strlen(
                $data['name']
            ) > 255
        ) {
            throw new RuntimeException(
                'Le nom de la faculté '
                . 'ne peut pas dépasser '
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
                'Le code de la faculté '
                . 'ne peut pas dépasser '
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
                'Le statut de la faculté '
                . 'est invalide.'
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    private function assertUniversityId(
        int $universityId
    ): void {
        if ($universityId <= 0) {
            throw new RuntimeException(
                'Identifiant d’université '
                . 'invalide.'
            );
        }
    }
}