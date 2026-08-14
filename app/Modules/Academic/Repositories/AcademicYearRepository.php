<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\Repositories;

use PDO;

final class AcademicYearRepository
{
    public function __construct(
        private readonly PDO $pdo
    ) {
    }

    /**
     * Retourne toutes les années académiques.
     */
    public function all(): array
    {
        $statement = $this->pdo->query(
            <<<'SQL'
            SELECT
                id,
                label,
                starts_on,
                ends_on,
                status
            FROM academic_years
            ORDER BY starts_on DESC, id DESC
            SQL
        );

        return $statement->fetchAll(
            PDO::FETCH_ASSOC
        );
    }

    /**
     * Recherche une année académique
     * à partir de son identifiant.
     */
    public function findById(
        int $id
    ): ?array {
        $statement = $this->pdo->prepare(
            <<<'SQL'
            SELECT
                id,
                label,
                starts_on,
                ends_on,
                status
            FROM academic_years
            WHERE id = :id
            LIMIT 1
            SQL
        );

        $statement->execute([
            'id' => $id,
        ]);

        $academicYear = $statement->fetch(
            PDO::FETCH_ASSOC
        );

        return $academicYear !== false
            ? $academicYear
            : null;
    }

    /**
     * Recherche une année académique
     * à partir de son libellé.
     */
    public function findByLabel(
        string $label
    ): ?array {
        $statement = $this->pdo->prepare(
            <<<'SQL'
            SELECT
                id,
                label,
                starts_on,
                ends_on,
                status
            FROM academic_years
            WHERE label = :label
            LIMIT 1
            SQL
        );

        $statement->execute([
            'label' => $label,
        ]);

        $academicYear = $statement->fetch(
            PDO::FETCH_ASSOC
        );

        return $academicYear !== false
            ? $academicYear
            : null;
    }

    /**
     * Vérifie si un libellé existe déjà.
     *
     * $exceptId permet d'exclure l'année actuellement
     * modifiée lors d'une opération UPDATE.
     */
    public function labelExists(
        string $label,
        ?int $exceptId = null
    ): bool {
        if ($exceptId === null) {
            $statement = $this->pdo->prepare(
                <<<'SQL'
                SELECT 1
                FROM academic_years
                WHERE label = :label
                LIMIT 1
                SQL
            );

            $statement->execute([
                'label' => $label,
            ]);
        } else {
            $statement = $this->pdo->prepare(
                <<<'SQL'
                SELECT 1
                FROM academic_years
                WHERE label = :label
                  AND id <> :except_id
                LIMIT 1
                SQL
            );

            $statement->execute([
                'label' => $label,
                'except_id' => $exceptId,
            ]);
        }

        return $statement->fetchColumn()
            !== false;
    }

    /**
     * Enregistre une nouvelle année académique.
     */
    public function create(
        array $data
    ): int {
        $statement = $this->pdo->prepare(
            <<<'SQL'
            INSERT INTO academic_years (
                label,
                starts_on,
                ends_on,
                status
            )
            VALUES (
                :label,
                :starts_on,
                :ends_on,
                :status
            )
            SQL
        );

        $statement->execute([
            'label' => $data['label'],
            'starts_on' => $data['starts_on'],
            'ends_on' => $data['ends_on'],
            'status' => $data['status'],
        ]);

        return (int) $this->pdo
            ->lastInsertId();
    }

    /**
     * Met à jour une année académique.
     */
    public function update(
        int $id,
        array $data
    ): void {
        $statement = $this->pdo->prepare(
            <<<'SQL'
            UPDATE academic_years
            SET
                label = :label,
                starts_on = :starts_on,
                ends_on = :ends_on,
                status = :status
            WHERE id = :id
            SQL
        );

        $statement->execute([
            'label' => $data['label'],
            'starts_on' => $data['starts_on'],
            'ends_on' => $data['ends_on'],
            'status' => $data['status'],
            'id' => $id,
        ]);
    }

    /**
     * Compte toutes les années académiques.
     */
    public function count(): int
    {
        return (int) $this->pdo
            ->query(
                <<<'SQL'
                SELECT COUNT(*)
                FROM academic_years
                SQL
            )
            ->fetchColumn();
    }

    /**
     * Compte les années académiques
     * selon leur statut.
     */
    public function countByStatus(
        string $status
    ): int {
        $statement = $this->pdo->prepare(
            <<<'SQL'
            SELECT COUNT(*)
            FROM academic_years
            WHERE status = :status
            SQL
        );

        $statement->execute([
            'status' => $status,
        ]);

        return (int) $statement
            ->fetchColumn();
    }
}