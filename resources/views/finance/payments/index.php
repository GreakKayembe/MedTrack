<?php

declare(strict_types=1);

/**
 * @var array<string, mixed> $metrics
 * @var array<string, mixed> $invoiceMetrics
 * @var array<int, array<string, mixed>> $amountsByCurrency
 * @var array<int, array<string, mixed>> $paymentMethods
 * @var array<int, array<string, mixed>> $providers
 * @var array<int, array<string, mixed>> $payments
 * @var array<int, array<string, mixed>> $recentInvoices
 */

$metrics =
    is_array($metrics ?? null)
        ? $metrics
        : [];

$invoiceMetrics =
    is_array($invoiceMetrics ?? null)
        ? $invoiceMetrics
        : [];

$amountsByCurrency =
    is_array($amountsByCurrency ?? null)
        ? $amountsByCurrency
        : [];

$paymentMethods =
    is_array($paymentMethods ?? null)
        ? $paymentMethods
        : [];

$providers =
    is_array($providers ?? null)
        ? $providers
        : [];

$payments =
    is_array($payments ?? null)
        ? $payments
        : [];

$recentInvoices =
    is_array($recentInvoices ?? null)
        ? $recentInvoices
        : [];

$totalPayments =
    (int) ($metrics['total'] ?? 0);

$succeededPayments =
    (int) ($metrics['succeeded'] ?? 0);

$pendingPayments =
    (int) ($metrics['pending'] ?? 0)
    + (int) ($metrics['processing'] ?? 0);

$failedPayments =
    (int) ($metrics['failed'] ?? 0);

$totalInvoices =
    (int) ($invoiceMetrics['total'] ?? 0);

$paidInvoices =
    (int) ($invoiceMetrics['paid'] ?? 0);

$partialInvoices =
    (int) ($invoiceMetrics['partially_paid'] ?? 0);

$issuedInvoices =
    (int) ($invoiceMetrics['issued'] ?? 0);
?>

<div class="container-fluid px-0">

    <!-- ============================================================
         Header
         ============================================================ -->

    <div
        class="d-flex flex-column flex-lg-row
               justify-content-between align-items-lg-center
               gap-3 mb-4"
    >

        <div>

            <div class="d-flex align-items-center gap-2 mb-2">

                <span class="badge rounded-pill text-bg-primary">
                    Finance
                </span>

                <span class="text-muted small">
                    Supervision plateforme
                </span>

            </div>

            <h2 class="fw-bold mb-1">
                Paiements
            </h2>

            <p class="text-muted mb-0">
                Supervision des transactions, factures,
                fournisseurs et remboursements MedTrack.
            </p>

        </div>

    </div>


    <!-- ============================================================
         Payment metrics
         ============================================================ -->

    <div class="row g-3 mb-4">

        <div class="col-md-6 col-xl-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-4">

                    <div class="d-flex align-items-center gap-3">

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

                            <div class="text-muted small">
                                Transactions
                            </div>

                            <div class="fs-4 fw-bold">
                                <?= $totalPayments ?>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-6 col-xl-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-4">

                    <div class="d-flex align-items-center gap-3">

                        <div
                            class="rounded-circle
                                   bg-success-subtle
                                   text-success
                                   d-flex align-items-center
                                   justify-content-center"
                            style="width:48px;height:48px;"
                        >
                            <i class="bi bi-check-circle fs-4"></i>
                        </div>

                        <div>

                            <div class="text-muted small">
                                Réussies
                            </div>

                            <div class="fs-4 fw-bold">
                                <?= $succeededPayments ?>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-6 col-xl-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-4">

                    <div class="d-flex align-items-center gap-3">

                        <div
                            class="rounded-circle
                                   bg-warning-subtle
                                   text-warning
                                   d-flex align-items-center
                                   justify-content-center"
                            style="width:48px;height:48px;"
                        >
                            <i class="bi bi-hourglass-split fs-4"></i>
                        </div>

                        <div>

                            <div class="text-muted small">
                                En traitement
                            </div>

                            <div class="fs-4 fw-bold">
                                <?= $pendingPayments ?>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-6 col-xl-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-4">

                    <div class="d-flex align-items-center gap-3">

                        <div
                            class="rounded-circle
                                   bg-danger-subtle
                                   text-danger
                                   d-flex align-items-center
                                   justify-content-center"
                            style="width:48px;height:48px;"
                        >
                            <i class="bi bi-x-circle fs-4"></i>
                        </div>

                        <div>

                            <div class="text-muted small">
                                Échouées
                            </div>

                            <div class="fs-4 fw-bold">
                                <?= $failedPayments ?>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- ============================================================
         Amounts by currency
         ============================================================ -->

    <div class="row g-4 mb-4">

        <div class="col-xl-7">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-4">

                    <div class="d-flex align-items-center gap-3 mb-4">

                        <div
                            class="rounded-circle
                                   bg-success-subtle
                                   text-success
                                   d-flex align-items-center
                                   justify-content-center"
                            style="width:48px;height:48px;"
                        >
                            <i class="bi bi-wallet2 fs-4"></i>
                        </div>

                        <div>

                            <h5 class="fw-bold mb-1">
                                Encaissements
                            </h5>

                            <p class="text-muted small mb-0">
                                Montants des transactions réussies,
                                séparés par devise.
                            </p>

                        </div>

                    </div>


                    <?php if ($amountsByCurrency === []): ?>

                        <div class="text-center text-muted py-4">

                            <i
                                class="bi bi-cash-stack
                                       fs-2 d-block mb-2"
                            ></i>

                            Aucun encaissement enregistré.

                        </div>

                    <?php else: ?>

                        <div class="row g-3">

                            <?php foreach (
                                $amountsByCurrency
                                as $currencyAmount
                            ): ?>

                                <?php
                                $currency =
                                    (string) (
                                        $currencyAmount['currency']
                                        ?? ''
                                    );

                                $amount =
                                    (float) (
                                        $currencyAmount['amount']
                                        ?? 0
                                    );

                                $count =
                                    (int) (
                                        $currencyAmount['payment_count']
                                        ?? 0
                                    );
                                ?>

                                <div class="col-md-6">

                                    <div
                                        class="border rounded-3
                                               p-3 h-100"
                                    >

                                        <div
                                            class="d-flex
                                                   justify-content-between
                                                   align-items-center mb-2"
                                        >

                                            <span class="text-muted small">
                                                <?= htmlspecialchars(
                                                    $currency,
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>
                                            </span>

                                            <span
                                                class="badge
                                                       rounded-pill
                                                       text-bg-light"
                                            >
                                                <?= $count ?>
                                                paiement<?= $count > 1 ? 's' : '' ?>
                                            </span>

                                        </div>


                                        <div class="fs-4 fw-bold">

                                            <?= number_format(
                                                $amount,
                                                2,
                                                ',',
                                                ' '
                                            ) ?>

                                            <span class="fs-6 text-muted">
                                                <?= htmlspecialchars(
                                                    $currency,
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>
                                            </span>

                                        </div>

                                    </div>

                                </div>

                            <?php endforeach; ?>

                        </div>

                    <?php endif; ?>

                </div>

            </div>

        </div>


        <!-- Invoice summary -->

        <div class="col-xl-5">

            <div class="card border-0 shadow-sm h-100">

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
                                Facturation
                            </h5>

                            <p class="text-muted small mb-0">
                                Situation globale des factures.
                            </p>

                        </div>

                    </div>


                    <div class="row g-3">

                        <div class="col-6">

                            <div class="border rounded-3 p-3">

                                <div class="text-muted small">
                                    Total
                                </div>

                                <div class="fs-5 fw-bold">
                                    <?= $totalInvoices ?>
                                </div>

                            </div>

                        </div>


                        <div class="col-6">

                            <div class="border rounded-3 p-3">

                                <div class="text-muted small">
                                    Payées
                                </div>

                                <div class="fs-5 fw-bold text-success">
                                    <?= $paidInvoices ?>
                                </div>

                            </div>

                        </div>


                        <div class="col-6">

                            <div class="border rounded-3 p-3">

                                <div class="text-muted small">
                                    Partielles
                                </div>

                                <div class="fs-5 fw-bold text-warning">
                                    <?= $partialInvoices ?>
                                </div>

                            </div>

                        </div>


                        <div class="col-6">

                            <div class="border rounded-3 p-3">

                                <div class="text-muted small">
                                    Émises
                                </div>

                                <div class="fs-5 fw-bold text-primary">
                                    <?= $issuedInvoices ?>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- ============================================================
         Provider / payment methods
         ============================================================ -->

    <div class="row g-4 mb-4">

        <!-- Providers -->

        <div class="col-xl-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-0">

                    <div class="p-4 border-bottom">

                        <h5 class="fw-bold mb-1">
                            Fournisseurs de paiement
                        </h5>

                        <p class="text-muted small mb-0">
                            Répartition par fournisseur externe.
                            Cette vue accueillera notamment
                            les opérateurs Mobile Money.
                        </p>

                    </div>


                    <div class="table-responsive">

                        <table class="table align-middle mb-0">

                            <thead class="table-light">

                                <tr>
                                    <th class="ps-4">
                                        Fournisseur
                                    </th>

                                    <th>
                                        Transactions
                                    </th>

                                    <th class="pe-4">
                                        Montant réussi
                                    </th>
                                </tr>

                            </thead>


                            <tbody>

                            <?php if ($providers === []): ?>

                                <tr>

                                    <td
                                        colspan="3"
                                        class="text-center
                                               py-4 text-muted"
                                    >
                                        Aucun fournisseur utilisé.
                                    </td>

                                </tr>

                            <?php else: ?>

                                <?php foreach ($providers as $provider): ?>

                                    <tr>

                                        <td class="ps-4 fw-semibold">

                                            <?= htmlspecialchars(
                                                (string) (
                                                    $provider['provider']
                                                    ?? 'NON_SPECIFIE'
                                                ),
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>

                                        </td>


                                        <td>
                                            <?= (int) (
                                                $provider['payment_count']
                                                ?? 0
                                            ) ?>
                                        </td>


                                        <td class="pe-4">

                                            <?= number_format(
                                                (float) (
                                                    $provider[
                                                        'succeeded_amount'
                                                    ]
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

        </div>


        <!-- Methods -->

        <div class="col-xl-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-0">

                    <div class="p-4 border-bottom">

                        <h5 class="fw-bold mb-1">
                            Moyens de paiement
                        </h5>

                        <p class="text-muted small mb-0">
                            Répartition des transactions
                            selon le canal utilisé.
                        </p>

                    </div>


                    <div class="table-responsive">

                        <table class="table align-middle mb-0">

                            <thead class="table-light">

                                <tr>
                                    <th class="ps-4">
                                        Moyen
                                    </th>

                                    <th class="pe-4">
                                        Transactions
                                    </th>
                                </tr>

                            </thead>


                            <tbody>

                            <?php if ($paymentMethods === []): ?>

                                <tr>

                                    <td
                                        colspan="2"
                                        class="text-center
                                               py-4 text-muted"
                                    >
                                        Aucun moyen de paiement utilisé.
                                    </td>

                                </tr>

                            <?php else: ?>

                                <?php foreach (
                                    $paymentMethods
                                    as $method
                                ): ?>

                                    <?php
                                    $methodCode =
                                        (string) (
                                            $method['payment_method']
                                            ?? ''
                                        );

                                    $methodLabel =
                                        match ($methodCode) {
                                            'MOBILE_MONEY' =>
                                                'Mobile Money',

                                            'BANK' =>
                                                'Banque',

                                            'CARD' =>
                                                'Carte',

                                            'CASH' =>
                                                'Espèces',

                                            'OTHER' =>
                                                'Autre',

                                            default =>
                                                $methodCode,
                                        };

                                    $methodIcon =
                                        match ($methodCode) {
                                            'MOBILE_MONEY' =>
                                                'bi-phone',

                                            'BANK' =>
                                                'bi-bank',

                                            'CARD' =>
                                                'bi-credit-card',

                                            'CASH' =>
                                                'bi-cash',

                                            default =>
                                                'bi-wallet2',
                                        };
                                    ?>

                                    <tr>

                                        <td class="ps-4">

                                            <div
                                                class="d-flex
                                                       align-items-center
                                                       gap-2"
                                            >

                                                <i
                                                    class="bi
                                                           <?= $methodIcon ?>
                                                           text-primary"
                                                ></i>

                                                <span class="fw-semibold">

                                                    <?= htmlspecialchars(
                                                        $methodLabel,
                                                        ENT_QUOTES,
                                                        'UTF-8'
                                                    ) ?>

                                                </span>

                                            </div>

                                        </td>


                                        <td class="pe-4">

                                            <?= (int) (
                                                $method['payment_count']
                                                ?? 0
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

        </div>

    </div>


    <!-- ============================================================
         Payments table
         ============================================================ -->

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body p-0">

            <div class="p-4 border-bottom">

                <h5 class="fw-bold mb-1">
                    Transactions
                </h5>

                <p class="text-muted small mb-0">
                    Registre global des paiements MedTrack.
                </p>

            </div>


            <div class="table-responsive">

                <table class="table align-middle mb-0">

                    <thead class="table-light">

                        <tr>
                            <th class="ps-4">
                                Transaction
                            </th>

                            <th>
                                Étudiant
                            </th>

                            <th>
                                Facture
                            </th>

                            <th>
                                Montant
                            </th>

                            <th>
                                Méthode
                            </th>

                            <th>
                                Fournisseur
                            </th>

                            <th>
                                Statut
                            </th>

                            <th class="text-end pe-4">
                                Actions
                            </th>
                        </tr>

                    </thead>


                    <tbody>

                    <?php if ($payments === []): ?>

                        <tr>

                            <td
                                colspan="8"
                                class="text-center py-5"
                            >

                                <div class="text-muted">

                                    <i
                                        class="bi bi-credit-card
                                               fs-1 d-block mb-3"
                                    ></i>

                                    <strong class="d-block mb-1">
                                        Aucun paiement enregistré
                                    </strong>

                                    <span class="small">
                                        Les transactions apparaîtront
                                        ici dès le premier paiement.
                                    </span>

                                </div>

                            </td>

                        </tr>

                    <?php else: ?>

                        <?php foreach ($payments as $payment): ?>

                            <?php
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
                            ?>

                            <tr>

                                <td class="ps-4">

                                    <a
                                        href="/payments/<?= $id ?>"
                                        class="fw-semibold
                                               text-decoration-none"
                                    >
                                        #<?= $id ?>
                                    </a>

                                    <div class="text-muted small">

                                        <?= htmlspecialchars(
                                            (string) (
                                                $payment['external_reference']
                                                ?? $payment['uuid']
                                                ?? ''
                                            ),
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>

                                    </div>

                                </td>


                                <td>

                                    <div class="fw-semibold">

                                        <?= htmlspecialchars(
                                            $studentName !== ''
                                                ? $studentName
                                                : 'Étudiant',
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>

                                    </div>

                                    <small class="text-muted">

                                        <?= htmlspecialchars(
                                            (string) (
                                                $payment['university_name']
                                                ?? ''
                                            ),
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>

                                    </small>

                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        (string) (
                                            $payment['invoice_number']
                                            ?? '—'
                                        ),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                </td>


                                <td>

                                    <strong>

                                        <?= number_format(
                                            (float) (
                                                $payment['amount']
                                                ?? 0
                                            ),
                                            2,
                                            ',',
                                            ' '
                                        ) ?>

                                    </strong>

                                    <small class="text-muted">

                                        <?= htmlspecialchars(
                                            (string) (
                                                $payment['currency']
                                                ?? ''
                                            ),
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>

                                    </small>

                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        (string) (
                                            $payment['payment_method']
                                            ?? '—'
                                        ),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        (string) (
                                            $payment['provider']
                                            ?? '—'
                                        ),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                </td>


                                <td>

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

                                </td>


                                <td class="text-end pe-4">

                                    <a
                                        href="/payments/<?= $id ?>"
                                        class="btn btn-sm
                                               btn-outline-primary"
                                        title="Consulter"
                                    >
                                        <i class="bi bi-eye"></i>
                                    </a>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    <!-- ============================================================
         Recent invoices
         ============================================================ -->

    <div class="card border-0 shadow-sm">

        <div class="card-body p-0">

            <div class="p-4 border-bottom">

                <h5 class="fw-bold mb-1">
                    Factures récentes
                </h5>

                <p class="text-muted small mb-0">
                    Dernières factures créées
                    sur la plateforme.
                </p>

            </div>


            <div class="table-responsive">

                <table class="table align-middle mb-0">

                    <thead class="table-light">

                        <tr>
                            <th class="ps-4">
                                Facture
                            </th>

                            <th>
                                Étudiant
                            </th>

                            <th>
                                Université
                            </th>

                            <th>
                                Total
                            </th>

                            <th>
                                Payé
                            </th>

                            <th class="pe-4">
                                Statut
                            </th>
                        </tr>

                    </thead>


                    <tbody>

                    <?php if ($recentInvoices === []): ?>

                        <tr>

                            <td
                                colspan="6"
                                class="text-center
                                       py-4 text-muted"
                            >
                                Aucune facture enregistrée.
                            </td>

                        </tr>

                    <?php else: ?>

                        <?php foreach (
                            $recentInvoices
                            as $invoice
                        ): ?>

                            <?php
                            $invoiceStatus =
                                (string) (
                                    $invoice['status']
                                    ?? 'DRAFT'
                                );

                            [$invoiceClass, $invoiceLabel] =
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

                            $invoiceStudent =
                                trim(
                                    implode(
                                        ' ',
                                        array_filter(
                                            [
                                                $invoice['first_name']
                                                    ?? null,

                                                $invoice['middle_name']
                                                    ?? null,

                                                $invoice['last_name']
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
                            ?>

                            <tr>

                                <td class="ps-4 fw-semibold">

                                    <?= htmlspecialchars(
                                        (string) (
                                            $invoice['invoice_number']
                                            ?? '—'
                                        ),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        $invoiceStudent !== ''
                                            ? $invoiceStudent
                                            : 'Étudiant',
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        (string) (
                                            $invoice['university_name']
                                            ?? '—'
                                        ),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                </td>


                                <td>

                                    <?= number_format(
                                        (float) (
                                            $invoice['total']
                                            ?? 0
                                        ),
                                        2,
                                        ',',
                                        ' '
                                    ) ?>

                                    <?= htmlspecialchars(
                                        (string) (
                                            $invoice['currency']
                                            ?? ''
                                        ),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                </td>


                                <td>

                                    <?= number_format(
                                        (float) (
                                            $invoice['amount_paid']
                                            ?? 0
                                        ),
                                        2,
                                        ',',
                                        ' '
                                    ) ?>

                                </td>


                                <td class="pe-4">

                                    <span
                                        class="badge rounded-pill
                                               <?= $invoiceClass ?>"
                                    >
                                        <?= htmlspecialchars(
                                            $invoiceLabel,
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
