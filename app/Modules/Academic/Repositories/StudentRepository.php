<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\Repositories;

use PDO;

final class StudentRepository
{
    public function __construct(
        private readonly PDO $pdo
    ) {
    }

    /**
     * Retourne tous les étudiants.
     *
     * Cette méthode fournit une vue globale.
     * Elle est notamment destinée au contexte PLATFORM.
     */
    public function all(): array
    {
        $sql = <<<'SQL'
            SELECT
                s.id,
                s.uuid,
                s.user_id,
                s.national_student_number,
                s.first_name,
                s.middle_name,
                s.last_name,
                s.gender,
                s.birth_date,
                s.birth_place,
                s.nationality,
                s.email,
                s.phone,
                s.status,
                s.created_at,
                s.updated_at
            FROM students AS s
            ORDER BY
                s.last_name ASC,
                s.first_name ASC,
                s.id DESC
        SQL;

        $statement =
            $this->pdo->query(
                $sql
            );

        return $statement->fetchAll(
            PDO::FETCH_ASSOC
        );
    }

    /**
     * Retourne uniquement les étudiants ayant
     * au moins une inscription académique
     * dans l'université indiquée.
     *
     * EXISTS évite les doublons lorsqu'un étudiant
     * possède plusieurs inscriptions dans la même
     * université.
     */
    public function allForUniversity(
        int $universityId
    ): array {
        $sql = <<<'SQL'
            SELECT
                s.id,
                s.uuid,
                s.user_id,
                s.national_student_number,
                s.first_name,
                s.middle_name,
                s.last_name,
                s.gender,
                s.birth_date,
                s.birth_place,
                s.nationality,
                s.email,
                s.phone,
                s.status,
                s.created_at,
                s.updated_at
            FROM students AS s
            WHERE EXISTS (
                SELECT 1
                FROM academic_enrollments AS ae
                WHERE ae.student_id = s.id
                  AND ae.university_id = :university_id
            )
            ORDER BY
                s.last_name ASC,
                s.first_name ASC,
                s.id DESC
        SQL;

        $statement =
            $this->pdo->prepare(
                $sql
            );

        $statement->execute(
            [
                'university_id' =>
                    $universityId,
            ]
        );

        return $statement->fetchAll(
            PDO::FETCH_ASSOC
        );
    }

    /**
     * Recherche un étudiant par son identifiant.
     *
     * Recherche globale destinée notamment
     * au contexte PLATFORM.
     */
    public function findById(
        int $id
    ): ?array {
        $sql = <<<'SQL'
            SELECT
                s.id,
                s.uuid,
                s.user_id,
                s.national_student_number,
                s.first_name,
                s.middle_name,
                s.last_name,
                s.gender,
                s.birth_date,
                s.birth_place,
                s.nationality,
                s.email,
                s.phone,
                s.status,
                s.created_at,
                s.updated_at
            FROM students AS s
            WHERE s.id = :id
            LIMIT 1
        SQL;

        $statement =
            $this->pdo->prepare(
                $sql
            );

        $statement->execute(
            [
                'id' =>
                    $id,
            ]
        );

        $student =
            $statement->fetch(
                PDO::FETCH_ASSOC
            );

        return $student !== false
            ? $student
            : null;
    }

    /**
     * Recherche un étudiant uniquement dans
     * le périmètre d'une université.
     */
    public function findByIdForUniversity(
        int $id,
        int $universityId
    ): ?array {
        $sql = <<<'SQL'
            SELECT
                s.id,
                s.uuid,
                s.user_id,
                s.national_student_number,
                s.first_name,
                s.middle_name,
                s.last_name,
                s.gender,
                s.birth_date,
                s.birth_place,
                s.nationality,
                s.email,
                s.phone,
                s.status,
                s.created_at,
                s.updated_at
            FROM students AS s
            WHERE s.id = :id
              AND EXISTS (
                  SELECT 1
                  FROM academic_enrollments AS ae
                  WHERE ae.student_id = s.id
                    AND ae.university_id = :university_id
              )
            LIMIT 1
        SQL;

        $statement =
            $this->pdo->prepare(
                $sql
            );

        $statement->execute(
            [
                'id' =>
                    $id,

                'university_id' =>
                    $universityId,
            ]
        );

        $student =
            $statement->fetch(
                PDO::FETCH_ASSOC
            );

        return $student !== false
            ? $student
            : null;
    }

    /**
     * Recherche un étudiant par son compte utilisateur.
     */
    public function findByUserId(
        int $userId
    ): ?array {
        $sql = <<<'SQL'
            SELECT
                s.id,
                s.uuid,
                s.user_id,
                s.national_student_number,
                s.first_name,
                s.middle_name,
                s.last_name,
                s.gender,
                s.birth_date,
                s.birth_place,
                s.nationality,
                s.email,
                s.phone,
                s.status,
                s.created_at,
                s.updated_at
            FROM students AS s
            WHERE s.user_id = :user_id
            LIMIT 1
        SQL;

        $statement =
            $this->pdo->prepare(
                $sql
            );

        $statement->execute(
            [
                'user_id' =>
                    $userId,
            ]
        );

        $student =
            $statement->fetch(
                PDO::FETCH_ASSOC
            );

        return $student !== false
            ? $student
            : null;
    }

    /**
     * Recherche un étudiant par UUID.
     */
    public function findByUuid(
        string $uuid
    ): ?array {
        $sql = <<<'SQL'
            SELECT
                s.id,
                s.uuid,
                s.user_id,
                s.national_student_number,
                s.first_name,
                s.middle_name,
                s.last_name,
                s.gender,
                s.birth_date,
                s.birth_place,
                s.nationality,
                s.email,
                s.phone,
                s.status,
                s.created_at,
                s.updated_at
            FROM students AS s
            WHERE s.uuid = :uuid
            LIMIT 1
        SQL;

        $statement =
            $this->pdo->prepare(
                $sql
            );

        $statement->execute(
            [
                'uuid' =>
                    $uuid,
            ]
        );

        $student =
            $statement->fetch(
                PDO::FETCH_ASSOC
            );

        return $student !== false
            ? $student
            : null;
    }

    /**
     * Recherche un étudiant par numéro national.
     */
    public function findByNationalStudentNumber(
        string $number
    ): ?array {
        $sql = <<<'SQL'
            SELECT
                s.id,
                s.uuid,
                s.user_id,
                s.national_student_number,
                s.first_name,
                s.middle_name,
                s.last_name,
                s.gender,
                s.birth_date,
                s.birth_place,
                s.nationality,
                s.email,
                s.phone,
                s.status,
                s.created_at,
                s.updated_at
            FROM students AS s
            WHERE s.national_student_number = :number
            LIMIT 1
        SQL;

        $statement =
            $this->pdo->prepare(
                $sql
            );

        $statement->execute(
            [
                'number' =>
                    $number,
            ]
        );

        $student =
            $statement->fetch(
                PDO::FETCH_ASSOC
            );

        return $student !== false
            ? $student
            : null;
    }

    /**
     * Recherche contrôlée d'identités étudiantes
     * pour le workflow d'inscription académique.
     *
     * Cette méthode effectue volontairement une
     * recherche globale : un étudiant peut déjà
     * exister dans MedTrack sans être encore
     * inscrit dans l'université courante.
     *
     * Seules les données minimales nécessaires à
     * l'identification sont retournées.
     */
    public function searchForEnrollment(
        string $query,
        int $universityId,
        int $limit = 10
    ): array {
        $query = trim($query);

        if (
            $query === ''
            || $universityId <= 0
            || $limit <= 0
        ) {
            return [];
        }

        $limit = min(
            $limit,
            10
        );

        $likeQuery =
            '%' . $query . '%';

        $prefixQuery =
            $query . '%';

        $sql = <<<'SQL'
            SELECT
                s.id,
                s.national_student_number,
                s.first_name,
                s.middle_name,
                s.last_name,
                s.birth_date,

                CASE
                    WHEN EXISTS (
                        SELECT 1
                        FROM academic_enrollments AS ae
                        WHERE ae.student_id = s.id
                          AND ae.university_id = :university_id
                    )
                    THEN 1
                    ELSE 0
                END AS already_enrolled

            FROM students AS s

            WHERE
                s.national_student_number = :exact_query
                OR s.email = :exact_email
                OR s.phone = :exact_phone
                OR s.first_name LIKE :like_query
                OR s.middle_name LIKE :like_query_middle
                OR s.last_name LIKE :like_query_last
                OR CONCAT_WS(
                    ' ',
                    s.first_name,
                    s.middle_name,
                    s.last_name
                ) LIKE :like_full_name

            ORDER BY
                CASE
                    WHEN s.national_student_number = :rank_exact_number
                        THEN 0
                    WHEN s.email = :rank_exact_email
                        THEN 1
                    WHEN s.phone = :rank_exact_phone
                        THEN 2
                    WHEN s.last_name LIKE :rank_last_prefix
                        THEN 3
                    WHEN s.first_name LIKE :rank_first_prefix
                        THEN 4
                    ELSE 5
                END ASC,
                s.last_name ASC,
                s.first_name ASC,
                s.id ASC

            LIMIT :result_limit
        SQL;

        $statement =
            $this->pdo->prepare(
                $sql
            );

        $statement->bindValue(
            ':university_id',
            $universityId,
            PDO::PARAM_INT
        );

        $statement->bindValue(
            ':exact_query',
            $query,
            PDO::PARAM_STR
        );

        $statement->bindValue(
            ':exact_email',
            mb_strtolower($query),
            PDO::PARAM_STR
        );

        $statement->bindValue(
            ':exact_phone',
            $query,
            PDO::PARAM_STR
        );

        $statement->bindValue(
            ':like_query',
            $likeQuery,
            PDO::PARAM_STR
        );

        $statement->bindValue(
            ':like_query_middle',
            $likeQuery,
            PDO::PARAM_STR
        );

        $statement->bindValue(
            ':like_query_last',
            $likeQuery,
            PDO::PARAM_STR
        );

        $statement->bindValue(
            ':like_full_name',
            $likeQuery,
            PDO::PARAM_STR
        );

        $statement->bindValue(
            ':rank_exact_number',
            $query,
            PDO::PARAM_STR
        );

        $statement->bindValue(
            ':rank_exact_email',
            mb_strtolower($query),
            PDO::PARAM_STR
        );

        $statement->bindValue(
            ':rank_exact_phone',
            $query,
            PDO::PARAM_STR
        );

        $statement->bindValue(
            ':rank_last_prefix',
            $prefixQuery,
            PDO::PARAM_STR
        );

        $statement->bindValue(
            ':rank_first_prefix',
            $prefixQuery,
            PDO::PARAM_STR
        );

        $statement->bindValue(
            ':result_limit',
            $limit,
            PDO::PARAM_INT
        );

        $statement->execute();

        return $statement->fetchAll(
            PDO::FETCH_ASSOC
        );
    }

    /**
     * Crée un étudiant.
     */
    public function create(
        array $data
    ): int {
        $sql = <<<'SQL'
            INSERT INTO students (
                uuid,
                user_id,
                national_student_number,
                first_name,
                middle_name,
                last_name,
                gender,
                birth_date,
                birth_place,
                nationality,
                email,
                phone,
                status
            ) VALUES (
                :uuid,
                :user_id,
                :national_student_number,
                :first_name,
                :middle_name,
                :last_name,
                :gender,
                :birth_date,
                :birth_place,
                :nationality,
                :email,
                :phone,
                :status
            )
        SQL;

        $statement =
            $this->pdo->prepare(
                $sql
            );

        $statement->execute(
            [
                'uuid' =>
                    $data['uuid'],

                'user_id' =>
                    $data['user_id'],

                'national_student_number' =>
                    $data[
                        'national_student_number'
                    ],

                'first_name' =>
                    $data['first_name'],

                'middle_name' =>
                    $data['middle_name'],

                'last_name' =>
                    $data['last_name'],

                'gender' =>
                    $data['gender'],

                'birth_date' =>
                    $data['birth_date'],

                'birth_place' =>
                    $data['birth_place'],

                'nationality' =>
                    $data['nationality'],

                'email' =>
                    $data['email'],

                'phone' =>
                    $data['phone'],

                'status' =>
                    $data['status'],
            ]
        );

        return (int)
            $this->pdo->lastInsertId();
    }

    /**
     * Met à jour les informations d'un étudiant.
     *
     * L'UUID n'est volontairement pas modifiable.
     */
    public function update(
        int $id,
        array $data
    ): void {
        $sql = <<<'SQL'
            UPDATE students
            SET
                user_id = :user_id,
                national_student_number = :national_student_number,
                first_name = :first_name,
                middle_name = :middle_name,
                last_name = :last_name,
                gender = :gender,
                birth_date = :birth_date,
                birth_place = :birth_place,
                nationality = :nationality,
                email = :email,
                phone = :phone,
                status = :status
            WHERE id = :id
        SQL;

        $statement =
            $this->pdo->prepare(
                $sql
            );

        $statement->execute(
            [
                'id' =>
                    $id,

                'user_id' =>
                    $data['user_id'],

                'national_student_number' =>
                    $data[
                        'national_student_number'
                    ],

                'first_name' =>
                    $data['first_name'],

                'middle_name' =>
                    $data['middle_name'],

                'last_name' =>
                    $data['last_name'],

                'gender' =>
                    $data['gender'],

                'birth_date' =>
                    $data['birth_date'],

                'birth_place' =>
                    $data['birth_place'],

                'nationality' =>
                    $data['nationality'],

                'email' =>
                    $data['email'],

                'phone' =>
                    $data['phone'],

                'status' =>
                    $data['status'],
            ]
        );
    }

    /**
     * Vérifie si un numéro national existe déjà.
     */
    public function nationalStudentNumberExists(
        string $number,
        ?int $exceptId = null
    ): bool {
        $sql = <<<'SQL'
            SELECT 1
            FROM students
            WHERE national_student_number = :number
        SQL;

        $parameters = [
            'number' =>
                $number,
        ];

        if ($exceptId !== null) {
            $sql .= <<<'SQL'

                AND id <> :except_id
            SQL;

            $parameters['except_id'] =
                $exceptId;
        }

        $sql .= <<<'SQL'

            LIMIT 1
        SQL;

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
     * Vérifie si un user_id est déjà rattaché
     * à un autre étudiant.
     */
    public function userIdExists(
        int $userId,
        ?int $exceptId = null
    ): bool {
        $sql = <<<'SQL'
            SELECT 1
            FROM students
            WHERE user_id = :user_id
        SQL;

        $parameters = [
            'user_id' =>
                $userId,
        ];

        if ($exceptId !== null) {
            $sql .= <<<'SQL'

                AND id <> :except_id
            SQL;

            $parameters['except_id'] =
                $exceptId;
        }

        $sql .= <<<'SQL'

            LIMIT 1
        SQL;

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
}