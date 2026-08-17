<?php

declare(strict_types=1);

namespace MedTrack\Modules\Dashboard\Repositories;

use PDO;

final class DashboardRepository
{
    public function __construct(
        private readonly PDO $pdo
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Platform dashboard
    |--------------------------------------------------------------------------
    */

    /**
     * Retourne les indicateurs globaux de la plateforme MedTrack.
     *
     * Cette méthode est exclusivement destinée au contexte PLATFORM.
     */
    public function platformMetrics(): array
    {
        return [
            'students' =>
                $this->countStudents(),

            'universities' =>
                $this->countOrganizationsByType(
                    'UNIVERSITY'
                ),

            'hospitals' =>
                $this->countOrganizationsByType(
                    'HOSPITAL'
                ),

            'professionalOrders' =>
                $this->countOrganizationsByType(
                    'PROFESSIONAL_ORDER'
                ),

            'ministries' =>
                $this->countOrganizationsByType(
                    'MINISTRY'
                ),

            'activeInternships' =>
                $this->countActiveInternships(),

            'successfulPayments' =>
                $this->countSuccessfulPayments(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | University dashboard
    |--------------------------------------------------------------------------
    */

    /**
     * Retourne les indicateurs appartenant exclusivement
     * à une université.
     */
    public function universityMetrics(
        int $universityId
    ): array {
        return [
            'students' =>
                $this->countUniversityStudents(
                    $universityId
                ),

            'activeEnrollments' =>
                $this->countUniversityActiveEnrollments(
                    $universityId
                ),

            'activeInternships' =>
                $this->countUniversityActiveInternships(
                    $universityId
                ),

            'partnerHospitals' =>
                $this->countUniversityPartnerHospitals(
                    $universityId
                ),

            'successfulPayments' =>
                $this->countUniversitySuccessfulPayments(
                    $universityId
                ),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Hospital dashboard
    |--------------------------------------------------------------------------
    */

    /**
     * Retourne les indicateurs appartenant exclusivement
     * à un hôpital.
     */
    public function hospitalMetrics(
        int $hospitalId
    ): array {
        return [
            'students' =>
                $this->countHospitalStudents(
                    $hospitalId
                ),

            'activeInternships' =>
                $this->countHospitalActiveInternships(
                    $hospitalId
                ),

            'partnerUniversities' =>
                $this->countHospitalPartnerUniversities(
                    $hospitalId
                ),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Student dashboard
    |--------------------------------------------------------------------------
    */

    /**
     * Retourne les indicateurs propres à un étudiant.
     */
    public function studentMetrics(
        int $studentId
    ): array {
        return [
            'academicEnrollments' =>
                $this->countStudentAcademicEnrollments(
                    $studentId
                ),

            'activeInternships' =>
                $this->countStudentActiveInternships(
                    $studentId
                ),

            'successfulPayments' =>
                $this->countStudentSuccessfulPayments(
                    $studentId
                ),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Platform counters
    |--------------------------------------------------------------------------
    */

    private function countStudents(): int
    {
        $sql = <<<'SQL'
            SELECT COUNT(*)
            FROM students
        SQL;

        return (int) $this->pdo
            ->query($sql)
            ->fetchColumn();
    }

    private function countOrganizationsByType(
        string $type
    ): int {
        $sql = <<<'SQL'
            SELECT COUNT(*)
            FROM organizations
            WHERE type = :type
              AND status = 'ACTIVE'
        SQL;

        $statement = $this->pdo->prepare(
            $sql
        );

        $statement->execute(
            [
                'type' => $type,
            ]
        );

        return (int) $statement->fetchColumn();
    }

    private function countActiveInternships(): int
    {
        $sql = <<<'SQL'
            SELECT COUNT(*)
            FROM internships
            WHERE status = 'IN_PROGRESS'
        SQL;

        return (int) $this->pdo
            ->query($sql)
            ->fetchColumn();
    }

    private function countSuccessfulPayments(): int
    {
        $sql = <<<'SQL'
            SELECT COUNT(*)
            FROM payments
            WHERE status = 'SUCCEEDED'
        SQL;

        return (int) $this->pdo
            ->query($sql)
            ->fetchColumn();
    }

    /*
    |--------------------------------------------------------------------------
    | University counters
    |--------------------------------------------------------------------------
    */

    /**
     * Un étudiant peut avoir plusieurs inscriptions académiques.
     *
     * COUNT(DISTINCT ...) évite donc de compter plusieurs fois
     * le même étudiant.
     */
    private function countUniversityStudents(
        int $universityId
    ): int {
        $sql = <<<'SQL'
            SELECT COUNT(DISTINCT ae.student_id)
            FROM academic_enrollments AS ae
            WHERE ae.university_id = :university_id
              AND ae.status <> 'CANCELLED'
        SQL;

        $statement = $this->pdo->prepare(
            $sql
        );

        $statement->execute(
            [
                'university_id' =>
                    $universityId,
            ]
        );

        return (int) $statement->fetchColumn();
    }

    private function countUniversityActiveEnrollments(
        int $universityId
    ): int {
        $sql = <<<'SQL'
            SELECT COUNT(*)
            FROM academic_enrollments
            WHERE university_id = :university_id
              AND status = 'ACTIVE'
        SQL;

        $statement = $this->pdo->prepare(
            $sql
        );

        $statement->execute(
            [
                'university_id' =>
                    $universityId,
            ]
        );

        return (int) $statement->fetchColumn();
    }

    private function countUniversityActiveInternships(
        int $universityId
    ): int {
        $sql = <<<'SQL'
            SELECT COUNT(*)
            FROM internships
            WHERE university_id = :university_id
              AND status = 'IN_PROGRESS'
        SQL;

        $statement = $this->pdo->prepare(
            $sql
        );

        $statement->execute(
            [
                'university_id' =>
                    $universityId,
            ]
        );

        return (int) $statement->fetchColumn();
    }

    private function countUniversityPartnerHospitals(
        int $universityId
    ): int {
        $sql = <<<'SQL'
            SELECT COUNT(DISTINCT hospital_id)
            FROM internships
            WHERE university_id = :university_id
              AND status <> 'CANCELLED'
        SQL;

        $statement = $this->pdo->prepare(
            $sql
        );

        $statement->execute(
            [
                'university_id' =>
                    $universityId,
            ]
        );

        return (int) $statement->fetchColumn();
    }

    /**
     * Le paiement ne contient pas directement university_id.
     *
     * Le rattachement à l'université est donc établi
     * par l'inscription académique de l'étudiant.
     *
     * EXISTS évite de compter plusieurs fois un paiement
     * lorsqu'un étudiant possède plusieurs inscriptions
     * dans la même université.
     */
    private function countUniversitySuccessfulPayments(
        int $universityId
    ): int {
        $sql = <<<'SQL'
            SELECT COUNT(*)
            FROM payments AS p
            WHERE p.status = 'SUCCEEDED'
              AND EXISTS (
                  SELECT 1
                  FROM academic_enrollments AS ae
                  WHERE ae.student_id = p.student_id
                    AND ae.university_id = :university_id
                    AND ae.status <> 'CANCELLED'
              )
        SQL;

        $statement = $this->pdo->prepare(
            $sql
        );

        $statement->execute(
            [
                'university_id' =>
                    $universityId,
            ]
        );

        return (int) $statement->fetchColumn();
    }

    /*
    |--------------------------------------------------------------------------
    | Hospital counters
    |--------------------------------------------------------------------------
    */

    private function countHospitalStudents(
        int $hospitalId
    ): int {
        $sql = <<<'SQL'
            SELECT COUNT(DISTINCT student_id)
            FROM internships
            WHERE hospital_id = :hospital_id
              AND status <> 'CANCELLED'
        SQL;

        $statement = $this->pdo->prepare(
            $sql
        );

        $statement->execute(
            [
                'hospital_id' =>
                    $hospitalId,
            ]
        );

        return (int) $statement->fetchColumn();
    }

    private function countHospitalActiveInternships(
        int $hospitalId
    ): int {
        $sql = <<<'SQL'
            SELECT COUNT(*)
            FROM internships
            WHERE hospital_id = :hospital_id
              AND status = 'IN_PROGRESS'
        SQL;

        $statement = $this->pdo->prepare(
            $sql
        );

        $statement->execute(
            [
                'hospital_id' =>
                    $hospitalId,
            ]
        );

        return (int) $statement->fetchColumn();
    }

    private function countHospitalPartnerUniversities(
        int $hospitalId
    ): int {
        $sql = <<<'SQL'
            SELECT COUNT(DISTINCT university_id)
            FROM internships
            WHERE hospital_id = :hospital_id
              AND status <> 'CANCELLED'
        SQL;

        $statement = $this->pdo->prepare(
            $sql
        );

        $statement->execute(
            [
                'hospital_id' =>
                    $hospitalId,
            ]
        );

        return (int) $statement->fetchColumn();
    }

    /*
    |--------------------------------------------------------------------------
    | Student counters
    |--------------------------------------------------------------------------
    */

    private function countStudentAcademicEnrollments(
        int $studentId
    ): int {
        $sql = <<<'SQL'
            SELECT COUNT(*)
            FROM academic_enrollments
            WHERE student_id = :student_id
              AND status <> 'CANCELLED'
        SQL;

        $statement = $this->pdo->prepare(
            $sql
        );

        $statement->execute(
            [
                'student_id' =>
                    $studentId,
            ]
        );

        return (int) $statement->fetchColumn();
    }

    private function countStudentActiveInternships(
        int $studentId
    ): int {
        $sql = <<<'SQL'
            SELECT COUNT(*)
            FROM internships
            WHERE student_id = :student_id
              AND status = 'IN_PROGRESS'
        SQL;

        $statement = $this->pdo->prepare(
            $sql
        );

        $statement->execute(
            [
                'student_id' =>
                    $studentId,
            ]
        );

        return (int) $statement->fetchColumn();
    }

    private function countStudentSuccessfulPayments(
        int $studentId
    ): int {
        $sql = <<<'SQL'
            SELECT COUNT(*)
            FROM payments
            WHERE student_id = :student_id
              AND status = 'SUCCEEDED'
        SQL;

        $statement = $this->pdo->prepare(
            $sql
        );

        $statement->execute(
            [
                'student_id' =>
                    $studentId,
            ]
        );

        return (int) $statement->fetchColumn();
    }
}
