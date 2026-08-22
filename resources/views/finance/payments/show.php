<?php

declare(strict_types=1);

/**
 * @var array<string, mixed> $payment
 * @var array<int, array<string, mixed>> $invoiceItems
 * @var array<int, array<string, mixed>> $refunds
 */

$id =
    (int) (
        $payment['id']
        ?? 0
    );

$status =
    (string) (
        $payment['status']
        ?? 'PENDING'
    );

[$statusClass, $statusLabel] =
    match ($status) {
        'PENDING' =>
            [
                'text-bg-warning',
                'En attente',
            ],

        'PROCESSING' =>
            [
                'text-bg-info',
                'En traitement',
            ],

        'SUCCEEDED' =>
            [
                'text-bg-success',
                'Réussi',
            ],

        'FAILED' =>
            [
                'text-bg-danger',
                'Échoué',
            ],

        'CANCELLED' =>
            [
                'text-bg-secondary',
                'Annulé',
            ],

        'REFUNDED' =>
            [
                'text-bg-secondary',
                'Remboursé',
            ],

        default =>
            [
                'text-bg-secondary',
                $status,
            ],
    };

$invoiceStatus =
    (string) (
        $payment['invoice_status']
        ?? 'DRAFT'
    );

[$invoiceStatusClass, $invoiceStatusLabel] =
    match ($invoiceStatus) {
        'DRAFT' =>
            [
                'text-bg-secondary',
                'Brouillon',
            ],

        'ISSUED' =>
            [
                'text-bg-primary',
                'Émise',
            ],

        'PARTIALLY_PAID' =>
            [
                'text-bg-warning',
                'Partiellement payée',
            ],

        'PAID' =>
            [
                'text-bg-success',
                'Payée',
            ],

        'CANCELLED' =>
            [
                'text-bg-secondary',
                'Annulée',
            ],

        'REFUNDED' =>
            [
                'text-bg-info',
                'Remboursée',
            ],

        default =>
            [
                'text-bg-secondary',
                $invoiceStatus,
            ],
    };

$studentName =
    trim(
        implode(
            ' ',
            array_filter(
                [
                    $payment['first_name']
                        ?? null,

                    $payment['middle_name']
                        ?? null,

                    $payment['last_name']
                        ?? null,
                ],
                static fn (
                    mixed $value
                ): bool =>
                    is_string($value)
                    && trim($value) !== ''
            )
        )
    );

$studentName =
    $studentName !== ''
        ? $studentName
        : 'Étudiant';

$invoiceItems =
    is_array(
        $invoiceItems
        ?? null
    )
        ? $invoiceItems
        : [];

$refunds =
    is_array(
        $refunds
        ?? null
    )
        ? $refunds
        : [];
?>

<div class="container-fluid px-0">

    <div
        class="d-flex flex-column flex-lg-row
               justify-content-between align-items-lg-center
               gap-3 mb-4"
    >

        <div>

            <div class="d-flex align-items-center gap-2 mb-2">

                <span class="badge rounded-pill text-bg-primary">
                    Payment
                </span>

                <span class="text-muted small">
                    Supervision financière
                </span>

            </div>

            <h2 class="fw-bold mb-1">
                Paiement #<?= $id ?>
            </h2>

            <p class="text-muted mb-0">

                <?= htmlspecialchars(
                    (string) (
                        $payment['invoice_number']
                        ?? 'Facture'
                    ),
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>

                ·

                <?= htmlspecialchars(
                    $studentName,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>

            </p>

        </div>


        <a
            href="/payments"
            class="btn btn-outline-secondary
                   d-inline-flex align-items-center gap-2"
        >
            <i class="bi bi-arrow-left"></i>
            Retour aux paiements
        </a>

    </div>


    <div class="row g-4">

        <div class="col-xl-8">

            <!-- Transaction -->

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-body p-4">

                    <div class="d-flex align-items-center gap-3 mb-4">

                        <div
                            class="rounded-circle
                                   bg-primary-subtle
                                   text-primary
                                   d-flex align-items-center
                                   justify-content-center"
                            style="width:48px;height:48px;"
                        >
                            <i class="bi bi-credit-card fs-4"></i>
                        </div>

                        <div>

                            <h5 class="fw-bold mb-1">
                                Transaction
                            </h5>

                            <p class="text-muted small mb-0">
                                Informations financières et fournisseur.
                            </p>

                        </div>

                    </div>


                    <div class="row g-4">

                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Montant
                            </div>

                            <div class="fs-4 fw-bold">

                                <?= number_format(
                                    (float) (
                                        $payment['amount']
                                        ?? 0
                                    ),
                                    2,
                                    ',',
                                    ' '
                                ) ?>

                                <span class="fs-6 text-muted">

                                    <?= htmlspecialchars(
                                        (string) (
                                            $payment['currency']
                                            ?? ''
                                        ),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                </span>

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Statut
                            </div>

                            <span
                                class="badge rounded-pill
                                       <?= $statusClass ?>"
                            >
                                <?= htmlspecialchars(
                                    $statusLabel,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </span>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Moyen de paiement
                            </div>

                            <div class="fw-semibold">
                                <?= htmlspecialchars(
                                    (string) (
                                        $payment['payment_method']
                                        ?? '—'
                                    ),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Fournisseur
                            </div>

                            <div class="fw-semibold">
                                <?= htmlspecialchars(
                                    (string) (
                                        $payment['provider']
                                        ?? 'Non spécifié'
                                    ),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </div>

                        </div>


                        <div class="col-12">

                            <div class="text-muted small mb-1">
                                Référence externe
                            </div>

                            <code>
                                <?= htmlspecialchars(
                                    (string) (
                                        $payment['external_reference']
                                        ?? '—'
                                    ),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </code>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Créé le
                            </div>

                            <div class="fw-semibold">
                                <?= htmlspecialchars(
                                    (string) (
                                        $payment['created_at']
                                        ?? '—'
                                    ),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Payé le
                            </div>

                            <div class="fw-semibold">
                                <?= htmlspecialchars(
                                    (string) (
                                        $payment['paid_at']
                                        ?? '—'
                                    ),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- Invoice -->

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-body p-4">

                    <div class="d-flex align-items-center gap-3 mb-4">

                        <div
                            class="rounded-circle
                                   bg-info-subtle
                                   text-info
                                   d-flex align-items-center
                                   justify-content-center"
                            style="width:48px;height:48px;"
                        >
                            <i class="bi bi-receipt fs-4"></i>
                        </div>

                        <div>

                            <h5 class="fw-bold mb-1">
                                Facture associée
                            </h5>

                            <p class="text-muted small mb-0">
                                Situation de la facture liée au paiement.
                            </p>

                        </div>

                    </div>


                    <div class="row g-4">

                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Numéro
                            </div>

                            <div class="fw-semibold">
                                <?= htmlspecialchars(
                                    (string) (
                                        $payment['invoice_number']
                                        ?? '—'
                                    ),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Statut
                            </div>

                            <span
                                class="badge rounded-pill
                                       <?= $invoiceStatusClass ?>"
                            >
                                <?= htmlspecialchars(
                                    $invoiceStatusLabel,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </span>

                        </div>


                        <div class="col-md-4">

                            <div class="text-muted small mb-1">
                                Sous-total
                            </div>

                            <div class="fw-semibold">

                                <?= number_format(
                                    (float) (
                                        $payment['invoice_subtotal']
                                        ?? 0
                                    ),
                                    2,
                                    ',',
                                    ' '
                                ) ?>

                                <?= htmlspecialchars(
                                    (string) (
                                        $payment['invoice_currency']
                                        ?? ''
                                    ),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>

                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="text-muted small mb-1">
                                Total
                            </div>

                            <div class="fw-semibold">

                                <?= number_format(
                                    (float) (
                                        $payment['invoice_total']
                                        ?? 0
                                    ),
                                    2,
                                    ',',
                                    ' '
                                ) ?>

                                <?= htmlspecialchars(
                                    (string) (
                                        $payment['invoice_currency']
                                        ?? ''
                                    ),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>

                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="text-muted small mb-1">
                                Montant payé
                            </div>

                            <div class="fw-semibold text-success">

                                <?= number_format(
                                    (float) (
                                        $payment['invoice_amount_paid']
                                        ?? 0
                                    ),
                                    2,
                                    ',',
                                    ' '
                                ) ?>

                                <?= htmlspecialchars(
                                    (string) (
                                        $payment['invoice_currency']
                                        ?? ''
                                    ),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Date d’émission
                            </div>

                            <div class="fw-semibold">
                                <?= htmlspecialchars(
                                    (string) (
                                        $payment['issued_at']
                                        ?? '—'
                                    ),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Échéance
                            </div>

                            <div class="fw-semibold">
                                <?= htmlspecialchars(
                                    (string) (
                                        $payment['due_at']
                                        ?? '—'
                                    ),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- Invoice items -->

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-body p-0">

                    <div class="p-4 border-bottom">

                        <h5 class="fw-bold mb-1">
                            Lignes de facture
                        </h5>

                        <p class="text-muted small mb-0">
                            Détail des éléments facturés.
                        </p>

                    </div>


                    <div class="table-responsive">

                        <table class="table align-middle mb-0">

                            <thead class="table-light">

                                <tr>
                                    <th class="ps-4">
                                        Description
                                    </th>

                                    <th>
                                        Quantité
                                    </th>

                                    <th>
                                        Prix unitaire
                                    </th>

                                    <th class="pe-4">
                                        Total
                                    </th>
                                </tr>

                            </thead>


                            <tbody>

                            <?php if ($invoiceItems === []): ?>

                                <tr>

                                    <td
                                        colspan="4"
                                        class="text-center
                                               py-4 text-muted"
                                    >
                                        Aucune ligne de facture.
                                    </td>

                                </tr>

                            <?php else: ?>

                                <?php foreach ($invoiceItems as $item): ?>

                                    <tr>

                                        <td class="ps-4 fw-semibold">
                                            <?= htmlspecialchars(
                                                (string) (
                                                    $item['description']
                                                    ?? '—'
                                                ),
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        </td>


                                        <td>
                                            <?= htmlspecialchars(
                                                (string) (
                                                    $item['quantity']
                                                    ?? '0'
                                                ),
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        </td>


                                        <td>

                                            <?= number_format(
                                                (float) (
                                                    $item['unit_price']
                                                    ?? 0
                                                ),
                                                2,
                                                ',',
                                                ' '
                                            ) ?>

                                        </td>


                                        <td class="pe-4 fw-semibold">

                                            <?= number_format(
                                                (float) (
                                                    $item['total']
                                                    ?? 0
                                                ),
                                                2,
                                                ',',
                                                ' '
                                            ) ?>

                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                            <?php endif; ?>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>


            <!-- Refunds -->

            <div class="card border-0 shadow-sm">

                <div class="card-body p-0">

                    <div class="p-4 border-bottom">

                        <h5 class="fw-bold mb-1">
                            Remboursements
                        </h5>

                        <p class="text-muted small mb-0">
                            Historique des remboursements liés
                            à cette transaction.
                        </p>

                    </div>


                    <div class="table-responsive">

                        <table class="table align-middle mb-0">

                            <thead class="table-light">

                                <tr>
                                    <th class="ps-4">
                                        Montant
                                    </th>

                                    <th>
                                        Motif
                                    </th>

                                    <th>
                                        Référence fournisseur
                                    </th>

                                    <th>
                                        Date
                                    </th>

                                    <th class="pe-4">
                                        Statut
                                    </th>
                                </tr>

                            </thead>


                            <tbody>

                            <?php if ($refunds === []): ?>

                                <tr>

                                    <td
                                        colspan="5"
                                        class="text-center
                                               py-4 text-muted"
                                    >
                                        Aucun remboursement enregistré.
                                    </td>

                                </tr>

                            <?php else: ?>

                                <?php foreach ($refunds as $refund): ?>

                                    <?php
                                    $refundStatus =
                                        (string) (
                                            $refund['status']
                                            ?? 'PENDING'
                                        );

                                    [$refundClass, $refundLabel] =
                                        match ($refundStatus) {
                                            'PENDING' =>
                                                [
                                                    'text-bg-warning',
                                                    'En attente',
                                                ],

                                            'PROCESSING' =>
                                                [
                                                    'text-bg-info',
                                                    'Traitement',
                                                ],

                                            'SUCCEEDED' =>
                                                [
                                                    'text-bg-success',
                                                    'Réussi',
                                                ],

                                            'FAILED' =>
                                                [
                                                    'text-bg-danger',
                                                    'Échoué',
                                                ],

                                            default =>
                                                [
                                                    'text-bg-secondary',
                                                    $refundStatus,
                                                ],
                                        };
                                    ?>

                                    <tr>

                                        <td class="ps-4 fw-semibold">

                                            <?= number_format(
                                                (float) (
                                                    $refund['amount']
                                                    ?? 0
                                                ),
                                                2,
                                                ',',
                                                ' '
                                            ) ?>

                                            <?= htmlspecialchars(
                                                (string) (
                                                    $payment['currency']
                                                    ?? ''
                                                ),
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>

                                        </td>


                                        <td>

                                            <?= htmlspecialchars(
                                                (string) (
                                                    $refund['reason']
                                                    ?? '—'
                                                ),
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>

                                        </td>


                                        <td>

                                            <code>
                                                <?= htmlspecialchars(
                                                    (string) (
                                                        $refund[
                                                            'provider_reference'
                                                        ]
                                                        ?? '—'
                                                    ),
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>
                                            </code>

                                        </td>


                                        <td>

                                            <?= htmlspecialchars(
                                                (string) (
                                                    $refund['created_at']
                                                    ?? '—'
                                                ),
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>

                                        </td>


                                        <td class="pe-4">

                                            <span
                                                class="badge rounded-pill
                                                       <?= $refundClass ?>"
                                            >
                                                <?= htmlspecialchars(
                                                    $refundLabel,
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>
                                            </span>

                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                            <?php endif; ?>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>


        <!-- ========================================================
             Right panel
             ======================================================== -->

        <div class="col-xl-4">

            <div
                class="card border-0 shadow-sm"
                style="position:sticky;top:90px;"
            >

                <div class="card-body p-4">

                    <div class="d-flex align-items-center gap-3 mb-4">

                        <div
                            class="rounded-circle
                                   bg-warning-subtle
                                   text-warning
                                   d-flex align-items-center
                                   justify-content-center"
                            style="width:48px;height:48px;"
                        >
                            <i class="bi bi-shield-check fs-4"></i>
                        </div>

                        <div>

                            <h5 class="fw-bold mb-1">
                                Situation
                            </h5>

                            <p class="text-muted small mb-0">
                                Informations de contrôle.
                            </p>

                        </div>

                    </div>


                    <div class="mb-4">

                        <div class="text-muted small mb-2">
                            Statut du paiement
                        </div>

                        <span
                            class="badge rounded-pill
                                   <?= $statusClass ?>"
                        >
                            <?= htmlspecialchars(
                                $statusLabel,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </span>

                    </div>


                    <div class="mb-4">

                        <div class="text-muted small mb-1">
                            Paiement ID
                        </div>

                        <div class="fw-semibold">
                            <?= $id ?>
                        </div>

                    </div>


                    <div class="mb-4">

                        <div class="text-muted small mb-1">
                            UUID
                        </div>

                        <code class="small">
                            <?= htmlspecialchars(
                                (string) (
                                    $payment['uuid']
                                    ?? '—'
                                ),
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </code>

                    </div>


                    <div class="mb-4">

                        <div class="text-muted small mb-1">
                            Étudiant
                        </div>

                        <div class="fw-semibold">
                            <?= htmlspecialchars(
                                $studentName,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </div>

                        <?php if (
                            !empty(
                                $payment['national_student_number']
                            )
                        ): ?>

                            <small class="text-muted">

                                <?= htmlspecialchars(
                                    (string) $payment[
                                        'national_student_number'
                                    ],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>

                            </small>

                        <?php endif; ?>

                    </div>


                    <div class="mb-4">

                        <div class="text-muted small mb-1">
                            Université
                        </div>

                        <div class="fw-semibold">
                            <?= htmlspecialchars(
                                (string) (
                                    $payment['university_name']
                                    ?? '—'
                                ),
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </div>

                    </div>


                    <hr>


                    <div class="small text-muted mb-4">

                        <div class="d-flex gap-2 mb-3">

                            <i class="bi bi-eye text-primary"></i>

                            <span>
                                Vue de supervision financière
                                du Super Admin MedTrack.
                            </span>

                        </div>


                        <div class="d-flex gap-2 mb-3">

                            <i class="bi bi-phone text-success"></i>

                            <span>
                                Les références fournisseur permettront
                                de tracer les futures transactions
                                Mobile Money.
                            </span>

                        </div>


                        <div class="d-flex gap-2">

                            <i class="bi bi-shield-lock text-warning"></i>

                            <span>
                                Aucun statut financier n’est modifié
                                manuellement depuis cette vue.
                            </span>

                        </div>

                    </div>


                    <div class="d-grid">

                        <a
                            href="/payments"
                            class="btn btn-light"
                        >
                            <i class="bi bi-arrow-left me-1"></i>
                            Retour aux paiements
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>