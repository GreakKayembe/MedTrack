<?php

declare(strict_types=1);

/** @var array<int, array<string, mixed>> $professionalOrders */

$professionalOrders =
    is_array(
        $professionalOrders
        ?? null
    )
        ? $professionalOrders
        : [];

$totalOrders =
    count(
        $professionalOrders
    );

$activeOrders =
    count(
        array_filter(
            $professionalOrders,
            static fn (
                array $order
            ): bool =>
                ($order['status'] ?? null)
                === 'ACTIVE'
        )
    );

$suspendedOrders =
    count(
        array_filter(
            $professionalOrders,
            static fn (
                array $order
            ): bool =>
                ($order['status'] ?? null)
                === 'SUSPENDED'
        )
    );

$inactiveOrders =
    count(
        array_filter(
            $professionalOrders,
            static fn (
                array $order
            ): bool =>
                ($order['status'] ?? null)
                === 'INACTIVE'
        )
    );
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
                    Professional Order
                </span>

                <span class="text-muted small">
                    Gestion institutionnelle
                </span>

            </div>

            <h2 class="fw-bold mb-1">
                Ordres professionnels
            </h2>

            <p class="text-muted mb-0">
                Gérez les ordres professionnels enregistrés
                sur la plateforme MedTrack.
            </p>

        </div>


        <a
            href="/professional-orders/create"
            class="btn btn-primary
                   d-inline-flex align-items-center gap-2"
        >
            <i class="bi bi-plus-lg"></i>

            Nouvel ordre professionnel
        </a>

    </div>


    <!-- ============================================================
         Metrics
         ============================================================ -->

    <div class="row g-3 mb-4">

        <!-- Total -->

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
                            <i class="bi bi-award fs-4"></i>
                        </div>

                        <div>

                            <div class="text-muted small">
                                Ordres enregistrés
                            </div>

                            <div class="fs-4 fw-bold">
                                <?= $totalOrders ?>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- Active -->

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
                                Actifs
                            </div>

                            <div class="fs-4 fw-bold">
                                <?= $activeOrders ?>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- Suspended -->

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
                            <i class="bi bi-pause-circle fs-4"></i>
                        </div>

                        <div>

                            <div class="text-muted small">
                                Suspendus
                            </div>

                            <div class="fs-4 fw-bold">
                                <?= $suspendedOrders ?>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- Inactive -->

        <div class="col-md-6 col-xl-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-4">

                    <div class="d-flex align-items-center gap-3">

                        <div
                            class="rounded-circle
                                   bg-secondary-subtle
                                   text-secondary
                                   d-flex align-items-center
                                   justify-content-center"
                            style="width:48px;height:48px;"
                        >
                            <i class="bi bi-dash-circle fs-4"></i>
                        </div>

                        <div>

                            <div class="text-muted small">
                                Inactifs
                            </div>

                            <div class="fs-4 fw-bold">
                                <?= $inactiveOrders ?>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- ============================================================
         Directory
         ============================================================ -->

    <div class="card border-0 shadow-sm">

        <div class="card-body p-0">

            <div
                class="d-flex flex-column flex-md-row
                       justify-content-between align-items-md-center
                       gap-3 p-4 border-bottom"
            >

                <div>

                    <h5 class="fw-bold mb-1">
                        Répertoire des ordres
                    </h5>

                    <p class="text-muted small mb-0">
                        Liste des ordres professionnels
                        enregistrés dans MedTrack.
                    </p>

                </div>

            </div>


            <div class="table-responsive">

                <table class="table align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="ps-4">
                                Ordre professionnel
                            </th>

                            <th>
                                Profession
                            </th>

                            <th>
                                Localisation
                            </th>

                            <th>
                                Contact
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

                        <?php if ($professionalOrders === []): ?>

                            <tr>

                                <td
                                    colspan="6"
                                    class="text-center py-5"
                                >

                                    <div class="text-muted">

                                        <i
                                            class="bi bi-award
                                                   fs-1 d-block mb-3"
                                        ></i>

                                        <strong class="d-block mb-1">
                                            Aucun ordre professionnel
                                        </strong>

                                        <span class="small">
                                            Créez le premier ordre
                                            professionnel de MedTrack.
                                        </span>

                                        <div class="mt-3">

                                            <a
                                                href="/professional-orders/create"
                                                class="btn btn-primary btn-sm"
                                            >
                                                <i class="bi bi-plus-lg me-1"></i>

                                                Nouvel ordre
                                            </a>

                                        </div>

                                    </div>

                                </td>

                            </tr>

                        <?php else: ?>

                            <?php foreach (
                                $professionalOrders
                                as $order
                            ): ?>

                                <?php
                                $id =
                                    (int) (
                                        $order['id']
                                        ?? 0
                                    );

                                $status =
                                    (string) (
                                        $order['status']
                                        ?? 'INACTIVE'
                                    );

                                $statusClass =
                                    match ($status) {
                                        'ACTIVE' =>
                                            'text-bg-success',

                                        'SUSPENDED' =>
                                            'text-bg-warning',

                                        'INACTIVE' =>
                                            'text-bg-secondary',

                                        default =>
                                            'text-bg-secondary',
                                    };

                                $statusLabel =
                                    match ($status) {
                                        'ACTIVE' =>
                                            'Actif',

                                        'SUSPENDED' =>
                                            'Suspendu',

                                        'INACTIVE' =>
                                            'Inactif',

                                        default =>
                                            $status,
                                    };

                                $city =
                                    trim(
                                        (string) (
                                            $order['city']
                                            ?? ''
                                        )
                                    );

                                $province =
                                    trim(
                                        (string) (
                                            $order['province']
                                            ?? ''
                                        )
                                    );

                                $email =
                                    trim(
                                        (string) (
                                            $order['email']
                                            ?? ''
                                        )
                                    );

                                $phone =
                                    trim(
                                        (string) (
                                            $order['phone']
                                            ?? ''
                                        )
                                    );
                                ?>

                                <tr>

                                    <!-- Organization -->

                                    <td class="ps-4">

                                        <div
                                            class="d-flex
                                                   align-items-center
                                                   gap-3"
                                        >

                                            <div
                                                class="rounded-circle
                                                       bg-primary-subtle
                                                       text-primary
                                                       d-flex
                                                       align-items-center
                                                       justify-content-center
                                                       flex-shrink-0"
                                                style="width:42px;height:42px;"
                                            >
                                                <i class="bi bi-award"></i>
                                            </div>


                                            <div>

                                                <a
                                                    href="/professional-orders/<?= $id ?>"
                                                    class="fw-semibold
                                                           text-decoration-none"
                                                >
                                                    <?= htmlspecialchars(
                                                        (string) (
                                                            $order['name']
                                                            ?? ''
                                                        ),
                                                        ENT_QUOTES,
                                                        'UTF-8'
                                                    ) ?>
                                                </a>

                                                <div class="text-muted small">

                                                    <?= htmlspecialchars(
                                                        (string) (
                                                            $order['code']
                                                            ?? ''
                                                        ),
                                                        ENT_QUOTES,
                                                        'UTF-8'
                                                    ) ?>

                                                </div>

                                            </div>

                                        </div>

                                    </td>


                                    <!-- Profession -->

                                    <td>

                                        <span
                                            class="badge
                                                   rounded-pill
                                                   text-bg-light
                                                   border"
                                        >
                                            <?= htmlspecialchars(
                                                (string) (
                                                    $order['profession_code']
                                                    ?? '—'
                                                ),
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        </span>

                                    </td>


                                    <!-- Location -->

                                    <td>

                                        <?php if (
                                            $city !== ''
                                            || $province !== ''
                                        ): ?>

                                            <div>
                                                <?= htmlspecialchars(
                                                    $city !== ''
                                                        ? $city
                                                        : '—',
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>
                                            </div>

                                            <?php if (
                                                $province !== ''
                                            ): ?>

                                                <small class="text-muted">
                                                    <?= htmlspecialchars(
                                                        $province,
                                                        ENT_QUOTES,
                                                        'UTF-8'
                                                    ) ?>
                                                </small>

                                            <?php endif; ?>

                                        <?php else: ?>

                                            <span class="text-muted">
                                                —
                                            </span>

                                        <?php endif; ?>

                                    </td>


                                    <!-- Contact -->

                                    <td>

                                        <?php if ($email !== ''): ?>

                                            <div class="small">

                                                <i
                                                    class="bi bi-envelope
                                                           text-muted me-1"
                                                ></i>

                                                <?= htmlspecialchars(
                                                    $email,
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>

                                            </div>

                                        <?php endif; ?>


                                        <?php if ($phone !== ''): ?>

                                            <div class="small text-muted">

                                                <i
                                                    class="bi bi-telephone
                                                           me-1"
                                                ></i>

                                                <?= htmlspecialchars(
                                                    $phone,
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>

                                            </div>

                                        <?php endif; ?>


                                        <?php if (
                                            $email === ''
                                            && $phone === ''
                                        ): ?>

                                            <span class="text-muted">
                                                —
                                            </span>

                                        <?php endif; ?>

                                    </td>


                                    <!-- Status -->

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


                                    <!-- Actions -->

                                    <td class="text-end pe-4">

                                        <div
                                            class="btn-group btn-group-sm"
                                            role="group"
                                        >

                                            <a
                                                href="/professional-orders/<?= $id ?>"
                                                class="btn btn-outline-primary"
                                                title="Consulter"
                                            >
                                                <i class="bi bi-eye"></i>
                                            </a>


                                            <a
                                                href="/professional-orders/<?= $id ?>/edit"
                                                class="btn btn-outline-secondary"
                                                title="Modifier"
                                            >
                                                <i class="bi bi-pencil"></i>
                                            </a>

                                        </div>

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