<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\Repositories;

use PDO;

final class StudyLevelRepository
{
    public function __construct(
        private readonly PDO $pdo
    ) {
    }

    /**
     * Retourne tous les niveaux d'études,
     * classés selon leur ordre académique.
     */
    public function all(): array
    {
        $statement = $this->pdo->query(
            <<<'SQL'
            SELECT
                id,
                code,
                name,
                ordinal
            FROM study_levels
            ORDER BY
                ordinal IS NULL,
                ordinal ASC,
                name ASC
            SQL
        );

        return $statement->fetchAll(
            PDO::FETCH_ASSOC
        );
    }

    /**
     * Recherche un niveau d'études par son identifiant.
     */
    public function findById(
        int $id
    ): ?array {
        $statement = $this->pdo->prepare(
            <<<'SQL'
            SELECT
                id,
                code,
                name,
                ordinal
            FROM study_levels
            WHERE id = :id
            LIMIT 1
            SQL
        );

        $statement->execute([
            'id' => $id,
        ]);

        $studyLevel = $statement->fetch(
            PDO::FETCH_ASSOC
        );

        return $studyLevel !== false
            ? $studyLevel
            : null;
    }

    /**
     * Recherche un niveau d'études par son code.
     */
    public function findByCode(
        string $code
    ): ?array {
        $statement = $this->pdo->prepare(
            <<<'SQL'
            SELECT
                id,
                code,
                name,
                ordinal
            FROM study_levels
            WHERE code = :code
            LIMIT 1
            SQL
        );

        $statement->execute([
            'code' => $code,
        ]);

        $studyLevel = $statement->fetch(
            PDO::FETCH_ASSOC
        );

        return $studyLevel !== false
            ? $studyLevel
            : null;
    }

    /**
     * Vérifie si un code existe déjà.
     *
     * $exceptId permet d'ignorer le niveau actuellement
     * modifié lors d'une mise à jour.
     */
    public function codeExists(
        string $code,
        ?int $exceptId = null
    ): bool {
        if ($exceptId === null) {
            $statement = $this->pdo->prepare(
                <<<'SQL'
                SELECT 1
                FROM study_levels
                WHERE code = :code
                LIMIT 1
                SQL
            );

            $statement->execute([
                'code' => $code,
            ]);
        } else {
            $statement = $this->pdo->prepare(
                <<<'SQL'
                SELECT 1
                FROM study_levels
                WHERE code = :code
                  AND id <> :except_id
                LIMIT 1
                SQL
            );

            $statement->execute([
                'code' => $code,
                'except_id' => $exceptId,
            ]);
        }

        return $statement->fetchColumn() !== false;
    }

    /**
     * Crée un niveau d'études.
     */
    public function create(
        array $data
    ): int {
        $statement = $this->pdo->prepare(
            <<<'SQL'
            INSERT INTO study_levels (
                code,
                name,
                ordinal
            )
            VALUES (
                :code,
                :name,
                :ordinal
            )
            SQL
        );

        $statement->execute([
            'code' => $data['code'],
            'name' => $data['name'],
            'ordinal' => $data['ordinal'],
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Met à jour un niveau d'études.
     */
    public function update(
        int $id,
        array $data
    ): void {
        $statement = $this->pdo->prepare(
            <<<'SQL'
            UPDATE study_levels
            SET
                code = :code,
                name = :name,
                ordinal = :ordinal
            WHERE id = :id
            SQL
        );

        $statement->execute([
            'code' => $data['code'],
            'name' => $data['name'],
            'ordinal' => $data['ordinal'],
            'id' => $id,
        ]);
    }

    /**
     * Retourne le nombre total de niveaux d'études.
     */
    public function count(): int
    {
        $statement = $this->pdo->query(
            <<<'SQL'
            SELECT COUNT(*)
            FROM study_levels
            SQL
        );

        return (int) $statement->fetchColumn();
    }
}