<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\Repositories;

use PDO;

final class FacultyRepository
{
    public function __construct(
        private readonly PDO $pdo
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Global / Platform
    |--------------------------------------------------------------------------
    */

    /**
     * Retourne toutes les facultés avec
     * leur université.
     *
     * Cette méthode est destinée au contexte
     * plateforme uniquement.
     */
    public function all(): array
    {
        $statement =
            $this->pdo->query(
                <<<'SQL'
                    SELECT
                        f.id,
                        f.university_id,
                        f.code,
                        f.name,
                        f.status,

                        o.uuid AS university_uuid,
                        o.code AS university_code,
                        o.name AS university_name

                    FROM faculties f

                    INNER JOIN universities u
                        ON u.organization_id = f.university_id

                    INNER JOIN organizations o
                        ON o.id = u.organization_id

                    ORDER BY
                        o.name ASC,
                        f.name ASC
                SQL
            );

        return $statement->fetchAll(
            PDO::FETCH_ASSOC
        );
    }

    /**
     * Recherche une faculté globalement.
     *
     * À utiliser uniquement lorsque le contexte
     * autorise l'accès plateforme.
     */
    public function findById(
        int $id
    ): ?array {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    SELECT
                        f.id,
                        f.university_id,
                        f.code,
                        f.name,
                        f.status,

                        o.uuid AS university_uuid,
                        o.code AS university_code,
                        o.name AS university_name

                    FROM faculties f

                    INNER JOIN universities u
                        ON u.organization_id = f.university_id

                    INNER JOIN organizations o
                        ON o.id = u.organization_id

                    WHERE f.id = :id

                    LIMIT 1
                SQL
            );

        $statement->execute([
            'id' => $id,
        ]);

        $faculty =
            $statement->fetch(
                PDO::FETCH_ASSOC
            );

        return $faculty !== false
            ? $faculty
            : null;
    }

    /*
    |--------------------------------------------------------------------------
    | University scope
    |--------------------------------------------------------------------------
    */

    /**
     * Retourne uniquement les facultés
     * appartenant à une université.
     */
    public function findByUniversity(
        int $universityId
    ): array {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    SELECT
                        f.id,
                        f.university_id,
                        f.code,
                        f.name,
                        f.status

                    FROM faculties f

                    WHERE f.university_id = :university_id

                    ORDER BY
                        f.name ASC,
                        f.id ASC
                SQL
            );

        $statement->execute([
            'university_id' =>
                $universityId,
        ]);

        return $statement->fetchAll(
            PDO::FETCH_ASSOC
        );
    }

    /**
     * Recherche une faculté uniquement
     * dans l'université donnée.
     *
     * Cette méthode est essentielle pour
     * empêcher une université de consulter
     * la faculté d'une autre institution.
     */
    public function findByIdForUniversity(
        int $id,
        int $universityId
    ): ?array {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    SELECT
                        f.id,
                        f.university_id,
                        f.code,
                        f.name,
                        f.status

                    FROM faculties f

                    WHERE f.id = :id
                      AND f.university_id = :university_id

                    LIMIT 1
                SQL
            );

        $statement->execute([
            'id' =>
                $id,

            'university_id' =>
                $universityId,
        ]);

        $faculty =
            $statement->fetch(
                PDO::FETCH_ASSOC
            );

        return $faculty !== false
            ? $faculty
            : null;
    }

    /*
    |--------------------------------------------------------------------------
    | University existence
    |--------------------------------------------------------------------------
    */

    /**
     * Vérifie qu'une université existe.
     */
    public function universityExists(
        int $universityId
    ): bool {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    SELECT 1

                    FROM universities

                    WHERE organization_id = :university_id

                    LIMIT 1
                SQL
            );

        $statement->execute([
            'university_id' =>
                $universityId,
        ]);

        return $statement->fetchColumn()
            !== false;
    }

    /*
    |--------------------------------------------------------------------------
    | Uniqueness
    |--------------------------------------------------------------------------
    */

    /**
     * Vérifie si une faculté portant ce nom
     * existe déjà dans la même université.
     */
    public function nameExistsForUniversity(
        int $universityId,
        string $name,
        ?int $exceptId = null
    ): bool {
        $sql =
            <<<'SQL'
                SELECT 1

                FROM faculties

                WHERE university_id = :university_id
                  AND name = :name
            SQL;

        $parameters = [
            'university_id' =>
                $universityId,

            'name' =>
                $name,
        ];

        if ($exceptId !== null) {
            $sql .=
                ' AND id <> :except_id';

            $parameters['except_id'] =
                $exceptId;
        }

        $sql .=
            ' LIMIT 1';

        $statement =
            $this->pdo->prepare(
                $sql
            );

        $statement->execute(
            $parameters
        );

        return $statement->fetchColumn()
            !== false;
    }

    /**
     * Vérifie si un code de faculté existe
     * déjà dans la même université.
     *
     * Le code est optionnel mais, lorsqu'il
     * est renseigné, nous évitons les doublons.
     */
    public function codeExistsForUniversity(
        int $universityId,
        string $code,
        ?int $exceptId = null
    ): bool {
        $sql =
            <<<'SQL'
                SELECT 1

                FROM faculties

                WHERE university_id = :university_id
                  AND code = :code
            SQL;

        $parameters = [
            'university_id' =>
                $universityId,

            'code' =>
                $code,
        ];

        if ($exceptId !== null) {
            $sql .=
                ' AND id <> :except_id';

            $parameters['except_id'] =
                $exceptId;
        }

        $sql .=
            ' LIMIT 1';

        $statement =
            $this->pdo->prepare(
                $sql
            );

        $statement->execute(
            $parameters
        );

        return $statement->fetchColumn()
            !== false;
    }

    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    /**
     * Crée une faculté.
     *
     * university_id doit être déterminé
     * par le Service depuis AccessContext
     * lorsqu'on est dans l'espace Université.
     */
    public function create(
        array $data
    ): int {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    INSERT INTO faculties (
                        university_id,
                        code,
                        name,
                        status
                    )
                    VALUES (
                        :university_id,
                        :code,
                        :name,
                        :status
                    )
                SQL
            );

        $statement->execute([
            'university_id' =>
                $data['university_id'],

            'code' =>
                $data['code'],

            'name' =>
                $data['name'],

            'status' =>
                $data['status'],
        ]);

        return (int) $this->pdo
            ->lastInsertId();
    }

    /*
    |--------------------------------------------------------------------------
    | Platform update
    |--------------------------------------------------------------------------
    */

    /**
     * Mise à jour globale.
     *
     * Cette méthode est destinée au contexte
     * plateforme, qui peut éventuellement
     * gérer le rattachement institutionnel.
     */
    public function update(
        int $id,
        array $data
    ): void {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    UPDATE faculties

                    SET
                        university_id = :university_id,
                        code = :code,
                        name = :name,
                        status = :status

                    WHERE id = :id
                SQL
            );

        $statement->execute([
            'university_id' =>
                $data['university_id'],

            'code' =>
                $data['code'],

            'name' =>
                $data['name'],

            'status' =>
                $data['status'],

            'id' =>
                $id,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | University update
    |--------------------------------------------------------------------------
    */

    /**
     * Met à jour une faculté dans le périmètre
     * strict d'une université.
     *
     * IMPORTANT :
     * university_id n'est jamais modifié.
     */
    public function updateForUniversity(
        int $id,
        int $universityId,
        array $data
    ): bool {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    UPDATE faculties

                    SET
                        code = :code,
                        name = :name,
                        status = :status

                    WHERE id = :id
                      AND university_id = :university_id
                SQL
            );

        $statement->execute([
            'code' =>
                $data['code'],

            'name' =>
                $data['name'],

            'status' =>
                $data['status'],

            'id' =>
                $id,

            'university_id' =>
                $universityId,
        ]);

        return $statement->rowCount() > 0;
    }

    /*
    |--------------------------------------------------------------------------
    | Statistics
    |--------------------------------------------------------------------------
    */

    /**
     * Compte les facultés d'une université.
     */
    public function countForUniversity(
        int $universityId
    ): int {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    SELECT COUNT(*)

                    FROM faculties

                    WHERE university_id = :university_id
                SQL
            );

        $statement->execute([
            'university_id' =>
                $universityId,
        ]);

        return (int) $statement
            ->fetchColumn();
    }

    /**
     * Compte les facultés d'une université
     * selon leur statut.
     */
    public function countByStatusForUniversity(
        int $universityId,
        string $status
    ): int {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    SELECT COUNT(*)

                    FROM faculties

                    WHERE university_id = :university_id
                      AND status = :status
                SQL
            );

        $statement->execute([
            'university_id' =>
                $universityId,

            'status' =>
                $status,
        ]);

        return (int) $statement
            ->fetchColumn();
    }
}