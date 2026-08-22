<?php

declare(strict_types=1);

namespace MedTrack\Modules\Internship\Repositories;

use PDO;

final class InternshipRepository
{
    public function __construct(
        private readonly PDO $pdo
    ) {
    }

    /**
     * Retourne tous les stages avec leur contexte
     * étudiant, université et hôpital.
     */
    public function all(): array
    {
        $statement =
            $this->pdo->query(
                <<<'SQL'
                    SELECT
                        i.id,
                        i.uuid,
                        i.assignment_id,
                        i.student_id,
                        i.academic_enrollment_id,
                        i.university_id,
                        i.hospital_id,
                        i.internship_program_id,
                        i.starts_on,
                        i.ends_on,
                        i.status,
                        i.started_at,
                        i.completed_at,
                        i.created_at,

                        s.first_name,
                        s.middle_name,
                        s.last_name,

                        uo.code AS university_code,
                        uo.name AS university_name,

                        ho.code AS hospital_code,
                        ho.name AS hospital_name,

                        ip.code AS program_code,
                        ip.name AS program_name,

                        (
                            SELECT COUNT(*)
                            FROM rotations r
                            WHERE r.internship_id = i.id
                        ) AS rotation_count

                    FROM internships i

                    INNER JOIN students s
                        ON s.id = i.student_id

                    INNER JOIN organizations uo
                        ON uo.id = i.university_id

                    INNER JOIN organizations ho
                        ON ho.id = i.hospital_id

                    LEFT JOIN internship_programs ip
                        ON ip.id = i.internship_program_id

                    ORDER BY
                        i.created_at DESC,
                        i.id DESC
                SQL
            );

        return $statement->fetchAll(
            PDO::FETCH_ASSOC
        );
    }

    /**
     * Retourne un stage avec son contexte complet.
     */
    public function findById(
        int $id
    ): ?array {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    SELECT
                        i.id,
                        i.uuid,
                        i.assignment_id,
                        i.student_id,
                        i.academic_enrollment_id,
                        i.university_id,
                        i.hospital_id,
                        i.internship_program_id,
                        i.starts_on,
                        i.ends_on,
                        i.status,
                        i.started_at,
                        i.completed_at,
                        i.created_at,

                        s.first_name,
                        s.middle_name,
                        s.last_name,

                        uo.code AS university_code,
                        uo.name AS university_name,

                        ho.code AS hospital_code,
                        ho.name AS hospital_name,

                        ip.code AS program_code,
                        ip.name AS program_name,

                        ia.request_id,
                        ia.assigned_by,
                        ia.assigned_at,
                        ia.status AS assignment_status,

                        ir.uuid AS request_uuid,
                        ir.status AS request_status,
                        ir.submitted_at,

                        (
                            SELECT COUNT(*)
                            FROM rotations r
                            WHERE r.internship_id = i.id
                        ) AS rotation_count

                    FROM internships i

                    INNER JOIN students s
                        ON s.id = i.student_id

                    INNER JOIN organizations uo
                        ON uo.id = i.university_id

                    INNER JOIN organizations ho
                        ON ho.id = i.hospital_id

                    INNER JOIN internship_assignments ia
                        ON ia.id = i.assignment_id

                    INNER JOIN internship_requests ir
                        ON ir.id = ia.request_id

                    LEFT JOIN internship_programs ip
                        ON ip.id = i.internship_program_id

                    WHERE i.id = :id

                    LIMIT 1
                SQL
            );

        $statement->execute([
            'id' => $id,
        ]);

        $internship =
            $statement->fetch(
                PDO::FETCH_ASSOC
            );

        return $internship !== false
            ? $internship
            : null;
    }

    /**
     * Retourne les rotations d'un stage.
     */
    public function rotationsForInternship(
        int $internshipId
    ): array {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    SELECT
                        r.id,
                        r.sequence_no,
                        r.starts_on,
                        r.ends_on,
                        r.status,
                        r.final_score,

                        hs.id AS service_id,
                        hs.code AS service_code,
                        hs.name AS service_name,
                        hs.capacity AS service_capacity

                    FROM rotations r

                    INNER JOIN hospital_services hs
                        ON hs.id = r.hospital_service_id

                    WHERE r.internship_id = :internship_id

                    ORDER BY
                        r.sequence_no ASC
                SQL
            );

        $statement->execute([
            'internship_id' =>
                $internshipId,
        ]);

        return $statement->fetchAll(
            PDO::FETCH_ASSOC
        );
    }

    /**
     * Métriques globales du module Stages.
     */
    public function metrics(): array
    {
        $statement =
            $this->pdo->query(
                <<<'SQL'
                    SELECT
                        COUNT(*) AS total,

                        COALESCE(
                            SUM(status = 'PLANNED'),
                            0
                        ) AS planned,

                        COALESCE(
                            SUM(status = 'IN_PROGRESS'),
                            0
                        ) AS in_progress,

                        COALESCE(
                            SUM(status = 'SUSPENDED'),
                            0
                        ) AS suspended,

                        COALESCE(
                            SUM(status = 'COMPLETED'),
                            0
                        ) AS completed,

                        COALESCE(
                            SUM(status = 'EVALUATED'),
                            0
                        ) AS evaluated,

                        COALESCE(
                            SUM(status = 'VALIDATED'),
                            0
                        ) AS validated,

                        COALESCE(
                            SUM(status = 'CERTIFIED'),
                            0
                        ) AS certified,

                        COALESCE(
                            SUM(status = 'CANCELLED'),
                            0
                        ) AS cancelled

                    FROM internships
                SQL
            );

        $metrics =
            $statement->fetch(
                PDO::FETCH_ASSOC
            );

        return $metrics !== false
            ? $metrics
            : [];
    }

    /**
     * Métriques des demandes de stage.
     */
    public function requestMetrics(): array
    {
        $statement =
            $this->pdo->query(
                <<<'SQL'
                    SELECT
                        COUNT(*) AS total,

                        COALESCE(
                            SUM(status = 'SUBMITTED'),
                            0
                        ) AS submitted,

                        COALESCE(
                            SUM(status = 'UNDER_REVIEW'),
                            0
                        ) AS under_review,

                        COALESCE(
                            SUM(status = 'APPROVED'),
                            0
                        ) AS approved,

                        COALESCE(
                            SUM(status = 'REJECTED'),
                            0
                        ) AS rejected

                    FROM internship_requests
                SQL
            );

        $metrics =
            $statement->fetch(
                PDO::FETCH_ASSOC
            );

        return $metrics !== false
            ? $metrics
            : [];
    }

    /**
     * Retourne les demandes récentes.
     */
    public function recentRequests(
        int $limit = 10
    ): array {
        $limit =
            max(
                1,
                min(
                    $limit,
                    100
                )
            );

        $statement =
            $this->pdo->query(
                sprintf(
                    <<<'SQL'
                        SELECT
                            ir.id,
                            ir.uuid,
                            ir.student_id,
                            ir.university_id,
                            ir.preferred_hospital_id,
                            ir.status,
                            ir.submitted_at,
                            ir.reviewed_at,
                            ir.created_at,

                            s.first_name,
                            s.middle_name,
                            s.last_name,

                            uo.name AS university_name,

                            ho.name AS preferred_hospital_name

                        FROM internship_requests ir

                        INNER JOIN students s
                            ON s.id = ir.student_id

                        INNER JOIN organizations uo
                            ON uo.id = ir.university_id

                        LEFT JOIN organizations ho
                            ON ho.id = ir.preferred_hospital_id

                        ORDER BY
                            ir.created_at DESC,
                            ir.id DESC

                        LIMIT %d
                    SQL,
                    $limit
                )
            );

        return $statement->fetchAll(
            PDO::FETCH_ASSOC
        );
    }
}