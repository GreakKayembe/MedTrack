<?php

declare(strict_types=1);

namespace MedTrack\Modules\Finance\Repositories;

use PDO;

final class PaymentRepository
{
    public function __construct(
        private readonly PDO $pdo
    ) {
    }

    /**
     * Retourne tous les paiements avec
     * leur contexte étudiant, facture et université.
     */
    public function all(): array
    {
        $statement =
            $this->pdo->query(
                <<<'SQL'
                    SELECT
                        p.id,
                        p.uuid,
                        p.invoice_id,
                        p.student_id,
                        p.amount,
                        p.currency,
                        p.payment_method,
                        p.provider,
                        p.external_reference,
                        p.status,
                        p.paid_at,
                        p.created_at,

                        i.invoice_number,
                        i.total AS invoice_total,
                        i.amount_paid AS invoice_amount_paid,
                        i.status AS invoice_status,
                        i.university_id,

                        s.first_name,
                        s.middle_name,
                        s.last_name,
                        s.email AS student_email,
                        s.phone AS student_phone,

                        o.code AS university_code,
                        o.name AS university_name,

                        (
                            SELECT COUNT(*)
                            FROM refunds r
                            WHERE r.payment_id = p.id
                        ) AS refund_count,

                        (
                            SELECT COALESCE(SUM(r.amount), 0)
                            FROM refunds r
                            WHERE r.payment_id = p.id
                              AND r.status = 'SUCCEEDED'
                        ) AS refunded_amount

                    FROM payments p

                    INNER JOIN invoices i
                        ON i.id = p.invoice_id

                    INNER JOIN students s
                        ON s.id = p.student_id

                    INNER JOIN organizations o
                        ON o.id = i.university_id

                    ORDER BY
                        p.created_at DESC,
                        p.id DESC
                SQL
            );

        return $statement->fetchAll(
            PDO::FETCH_ASSOC
        );
    }

    /**
     * Retourne un paiement par identifiant.
     */
    public function findById(
        int $id
    ): ?array {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    SELECT
                        p.id,
                        p.uuid,
                        p.invoice_id,
                        p.student_id,
                        p.amount,
                        p.currency,
                        p.payment_method,
                        p.provider,
                        p.external_reference,
                        p.status,
                        p.paid_at,
                        p.created_at,

                        i.uuid AS invoice_uuid,
                        i.invoice_number,
                        i.currency AS invoice_currency,
                        i.subtotal AS invoice_subtotal,
                        i.total AS invoice_total,
                        i.amount_paid AS invoice_amount_paid,
                        i.status AS invoice_status,
                        i.issued_at,
                        i.due_at,
                        i.university_id,
                        i.academic_enrollment_id,
                        i.internship_request_id,

                        s.uuid AS student_uuid,
                        s.first_name,
                        s.middle_name,
                        s.last_name,
                        s.email AS student_email,
                        s.phone AS student_phone,
                        s.national_student_number,

                        o.code AS university_code,
                        o.name AS university_name

                    FROM payments p

                    INNER JOIN invoices i
                        ON i.id = p.invoice_id

                    INNER JOIN students s
                        ON s.id = p.student_id

                    INNER JOIN organizations o
                        ON o.id = i.university_id

                    WHERE p.id = :id

                    LIMIT 1
                SQL
            );

        $statement->execute([
            'id' => $id,
        ]);

        $payment =
            $statement->fetch(
                PDO::FETCH_ASSOC
            );

        return $payment !== false
            ? $payment
            : null;
    }

    /**
     * Retourne les lignes de la facture
     * associée au paiement.
     */
    public function invoiceItems(
        int $invoiceId
    ): array {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    SELECT
                        id,
                        description,
                        quantity,
                        unit_price,
                        total

                    FROM invoice_items

                    WHERE invoice_id = :invoice_id

                    ORDER BY id ASC
                SQL
            );

        $statement->execute([
            'invoice_id' =>
                $invoiceId,
        ]);

        return $statement->fetchAll(
            PDO::FETCH_ASSOC
        );
    }

    /**
     * Retourne les remboursements
     * liés au paiement.
     */
    public function refunds(
        int $paymentId
    ): array {
        $statement =
            $this->pdo->prepare(
                <<<'SQL'
                    SELECT
                        id,
                        payment_id,
                        amount,
                        reason,
                        status,
                        provider_reference,
                        created_at,
                        completed_at

                    FROM refunds

                    WHERE payment_id = :payment_id

                    ORDER BY
                        created_at DESC,
                        id DESC
                SQL
            );

        $statement->execute([
            'payment_id' =>
                $paymentId,
        ]);

        return $statement->fetchAll(
            PDO::FETCH_ASSOC
        );
    }

    /**
     * Métriques globales des paiements.
     */
    public function metrics(): array
    {
        $statement =
            $this->pdo->query(
                <<<'SQL'
                    SELECT
                        COUNT(*) AS total,

                        COALESCE(
                            SUM(status = 'PENDING'),
                            0
                        ) AS pending,

                        COALESCE(
                            SUM(status = 'PROCESSING'),
                            0
                        ) AS processing,

                        COALESCE(
                            SUM(status = 'SUCCEEDED'),
                            0
                        ) AS succeeded,

                        COALESCE(
                            SUM(status = 'FAILED'),
                            0
                        ) AS failed,

                        COALESCE(
                            SUM(status = 'CANCELLED'),
                            0
                        ) AS cancelled,

                        COALESCE(
                            SUM(status = 'REFUNDED'),
                            0
                        ) AS refunded,

                        COALESCE(
                            SUM(
                                CASE
                                    WHEN status = 'SUCCEEDED'
                                    THEN amount
                                    ELSE 0
                                END
                            ),
                            0
                        ) AS succeeded_amount

                    FROM payments
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
     * Montants encaissés par devise.
     *
     * Important :
     * on ne mélange jamais CDF, USD ou
     * toute autre devise dans une seule somme.
     */
    public function successfulAmountsByCurrency(): array
    {
        $statement =
            $this->pdo->query(
                <<<'SQL'
                    SELECT
                        currency,
                        COUNT(*) AS payment_count,
                        COALESCE(SUM(amount), 0) AS amount

                    FROM payments

                    WHERE status = 'SUCCEEDED'

                    GROUP BY currency

                    ORDER BY currency ASC
                SQL
            );

        return $statement->fetchAll(
            PDO::FETCH_ASSOC
        );
    }

    /**
     * Répartition par moyen de paiement.
     */
    public function paymentMethods(): array
    {
        $statement =
            $this->pdo->query(
                <<<'SQL'
                    SELECT
                        payment_method,
                        COUNT(*) AS payment_count

                    FROM payments

                    GROUP BY payment_method

                    ORDER BY payment_count DESC
                SQL
            );

        return $statement->fetchAll(
            PDO::FETCH_ASSOC
        );
    }

    /**
     * Répartition des paiements par fournisseur.
     *
     * Très utile lorsque Mobile Money
     * sera activé.
     */
    public function providers(): array
    {
        $statement =
            $this->pdo->query(
                <<<'SQL'
                    SELECT
                        COALESCE(provider, 'NON_SPECIFIE') AS provider,
                        COUNT(*) AS payment_count,

                        COALESCE(
                            SUM(
                                CASE
                                    WHEN status = 'SUCCEEDED'
                                    THEN amount
                                    ELSE 0
                                END
                            ),
                            0
                        ) AS succeeded_amount

                    FROM payments

                    GROUP BY provider

                    ORDER BY payment_count DESC
                SQL
            );

        return $statement->fetchAll(
            PDO::FETCH_ASSOC
        );
    }

    /**
     * Factures récentes.
     */
    public function recentInvoices(
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

        $sql =
            sprintf(
                <<<'SQL'
                    SELECT
                        i.id,
                        i.uuid,
                        i.invoice_number,
                        i.currency,
                        i.subtotal,
                        i.total,
                        i.amount_paid,
                        i.status,
                        i.issued_at,
                        i.due_at,
                        i.created_at,

                        s.id AS student_id,
                        s.first_name,
                        s.middle_name,
                        s.last_name,

                        o.id AS university_id,
                        o.code AS university_code,
                        o.name AS university_name

                    FROM invoices i

                    INNER JOIN students s
                        ON s.id = i.student_id

                    INNER JOIN organizations o
                        ON o.id = i.university_id

                    ORDER BY
                        i.created_at DESC,
                        i.id DESC

                    LIMIT %d
                SQL,
                $limit
            );

        $statement =
            $this->pdo->query(
                $sql
            );

        return $statement->fetchAll(
            PDO::FETCH_ASSOC
        );
    }

    /**
     * Métriques des factures.
     */
    public function invoiceMetrics(): array
    {
        $statement =
            $this->pdo->query(
                <<<'SQL'
                    SELECT
                        COUNT(*) AS total,

                        COALESCE(
                            SUM(status = 'DRAFT'),
                            0
                        ) AS draft,

                        COALESCE(
                            SUM(status = 'ISSUED'),
                            0
                        ) AS issued,

                        COALESCE(
                            SUM(status = 'PARTIALLY_PAID'),
                            0
                        ) AS partially_paid,

                        COALESCE(
                            SUM(status = 'PAID'),
                            0
                        ) AS paid,

                        COALESCE(
                            SUM(status = 'CANCELLED'),
                            0
                        ) AS cancelled,

                        COALESCE(
                            SUM(status = 'REFUNDED'),
                            0
                        ) AS refunded

                    FROM invoices
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
}