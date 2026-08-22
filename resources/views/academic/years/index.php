<?php

declare(strict_types=1);

/**
 * @var array<int, array<string, mixed>> $academicYears
 * @var array<string, int> $statistics
 * @var bool $readOnly
 */

$statusLabels = [
    'PLANNED' => 'Planifiée',
    'OPEN' => 'Ouverte',
    'CLOSED' => 'Clôturée',
];

$statusClasses = [
    'PLANNED' =>
        'bg-warning-subtle text-warning-emphasis',

    'OPEN' =>
        'bg-success-subtle text-success-emphasis',

    'CLOSED' =>
        'bg-secondary-subtle text-secondary-emphasis',
];

$statistics =
    is_array(
        $statistics
        ?? null
    )
        ? $statistics
        : [
            'total' => 0,
            'planned' => 0,
            'open' => 0,
            'closed' => 0,
        ];

$academicYears =
    is_array(
        $academicYears
        ?? null
    )
        ? $academicYears
        : [];

$readOnly =
    (bool) (
        $readOnly
        ?? false
    );
?>


<!-- ============================================================
     Statistics
     ============================================================ -->

<div class="row g-4 mb-4">

    <!-- Total -->

    <div class="col-xl-3 col-md-6">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <div
                    class="d-flex
                           align-items-center
                           justify-content-between"
                >

                    <div>

                        <div class="text-muted small mb-1">
                            Total
                        </div>

                        <h3 class="mb-0 fw-bold">
                            <?= (int) (
                                $statistics['total']
                                ?? 0
                            ) ?>
                        </h3>

                        <div class="small text-muted mt-1">
                            Années académiques
                        </div>

                    </div>


                    <div
                        class="rounded-circle
                               bg-primary-subtle
                               text-primary
                               d-flex
                               align-items-center
                               justify-content-center"
                        style="
                            width: 52px;
                            height: 52px;
                        "
                    >
                        <i class="bi bi-calendar3 fs-4"></i>
                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- Planned -->

    <div class="col-xl-3 col-md-6">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <div
                    class="d-flex
                           align-items-center
                           justify-content-between"
                >

                    <div>

                        <div class="text-muted small mb-1">
                            Planifiées
                        </div>

                        <h3 class="mb-0 fw-bold">
                            <?= (int) (
                                $statistics['planned']
                                ?? 0
                            ) ?>
                        </h3>

                        <div class="small text-muted mt-1">
                            À venir
                        </div>

                    </div>


                    <div
                        class="rounded-circle
                               bg-warning-subtle
                               text-warning
                               d-flex
                               align-items-center
                               justify-content-center"
                        style="
                            width: 52px;
                            height: 52px;
                        "
                    >
                        <i class="bi bi-clock-history fs-4"></i>
                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- Open -->

    <div class="col-xl-3 col-md-6">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <div
                    class="d-flex
                           align-items-center
                           justify-content-between"
                >

                    <div>

                        <div class="text-muted small mb-1">
                            Ouvertes
                        </div>

                        <h3 class="mb-0 fw-bold">
                            <?= (int) (
                                $statistics['open']
                                ?? 0
                            ) ?>
                        </h3>

                        <div class="small text-muted mt-1">
                            En cours
                        </div>

                    </div>


                    <div
                        class="rounded-circle
                               bg-success-subtle
                               text-success
                               d-flex
                               align-items-center
                               justify-content-center"
                        style="
                            width: 52px;
                            height: 52px;
                        "
                    >
                        <i class="bi bi-calendar-check fs-4"></i>
                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- Closed -->

    <div class="col-xl-3 col-md-6">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <div
                    class="d-flex
                           align-items-center
                           justify-content-between"
                >

                    <div>

                        <div class="text-muted small mb-1">
                            Clôturées
                        </div>

                        <h3 class="mb-0 fw-bold">
                            <?= (int) (
                                $statistics['closed']
                                ?? 0
                            ) ?>
                        </h3>

                        <div class="small text-muted mt-1">
                            Terminées
                        </div>

                    </div>


                    <div
                        class="rounded-circle
                               bg-secondary-subtle
                               text-secondary
                               d-flex
                               align-items-center
                               justify-content-center"
                        style="
                            width: 52px;
                            height: 52px;
                        "
                    >
                        <i class="bi bi-calendar-x fs-4"></i>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


<!-- ============================================================
     Academic years list
     ============================================================ -->

<div class="card border-0 shadow-sm">

    <div class="card-header bg-white border-0 py-3">

        <div
            class="d-flex
                   flex-column
                   flex-md-row
                   align-items-md-center
                   justify-content-between
                   gap-3"
        >

            <div>

                <h5 class="mb-1 fw-semibold">
                    Années académiques
                </h5>


                <p class="text-muted small mb-0">

                    <?php if ($readOnly): ?>

                        Consultez le référentiel des années
                        académiques défini par MedTrack.

                    <?php else: ?>

                        Gérez les périodes académiques
                        utilisées par MedTrack.

                    <?php endif; ?>

                </p>

            </div>


            <?php if (!$readOnly): ?>

                <div>

                    <a
                        href="/academic-years/create"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-plus-lg me-1"></i>

                        Nouvelle année académique
                    </a>

                </div>

            <?php else: ?>

                <div>

                    <span
                        class="badge
                               rounded-pill
                               bg-light
                               text-secondary
                               border
                               px-3
                               py-2"
                    >
                        <i class="bi bi-lock me-1"></i>

                        Référentiel MedTrack
                    </span>

                </div>

            <?php endif; ?>

        </div>

    </div>


    <div class="card-body">

        <!-- ========================================================
             Empty state
             ======================================================== -->

        <?php if ($academicYears === []): ?>

            <div class="text-center py-5">

                <div class="mb-3">

                    <i
                        class="bi bi-calendar3
                               display-4
                               text-muted"
                    ></i>

                </div>


                <h5>
                    Aucune année académique
                </h5>


                <?php if ($readOnly): ?>

                    <p class="text-muted mb-0">
                        Aucune année académique
                        n'est actuellement disponible
                        dans le référentiel MedTrack.
                    </p>

                <?php else: ?>

                    <p class="text-muted mb-4">
                        Commencez par enregistrer
                        votre première année académique.
                    </p>


                    <a
                        href="/academic-years/create"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-plus-lg me-1"></i>

                        Créer une année académique
                    </a>

                <?php endif; ?>

            </div>


        <!-- ========================================================
             Table
             ======================================================== -->

        <?php else: ?>

            <div class="table-responsive">

                <table
                    class="table
                           table-hover
                           align-middle"
                >

                    <thead>

                        <tr>

                            <th>
                                Année académique
                            </th>

                            <th>
                                Début
                            </th>

                            <th>
                                Fin
                            </th>

                            <th>
                                Statut
                            </th>

                            <th
                                class="text-end"
                                style="width: 160px;"
                            >
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php foreach (
                            $academicYears
                            as $academicYear
                        ): ?>

                            <?php
                            $id =
                                (int) (
                                    $academicYear['id']
                                    ?? 0
                                );

                            $label =
                                (string) (
                                    $academicYear['label']
                                    ?? ''
                                );

                            $status =
                                strtoupper(
                                    trim(
                                        (string) (
                                            $academicYear['status']
                                            ?? ''
                                        )
                                    )
                                );

                            $statusLabel =
                                $statusLabels[$status]
                                ?? $status;

                            $statusClass =
                                $statusClasses[$status]
                                ?? 'bg-light text-dark';

                            $startsOn =
                                (string) (
                                    $academicYear['starts_on']
                                    ?? ''
                                );

                            $endsOn =
                                (string) (
                                    $academicYear['ends_on']
                                    ?? ''
                                );


                            $formattedStartsOn = '—';

                            if ($startsOn !== '') {
                                $timestamp =
                                    strtotime(
                                        $startsOn
                                    );

                                if ($timestamp !== false) {
                                    $formattedStartsOn =
                                        date(
                                            'd/m/Y',
                                            $timestamp
                                        );
                                }
                            }


                            $formattedEndsOn = '—';

                            if ($endsOn !== '') {
                                $timestamp =
                                    strtotime(
                                        $endsOn
                                    );

                                if ($timestamp !== false) {
                                    $formattedEndsOn =
                                        date(
                                            'd/m/Y',
                                            $timestamp
                                        );
                                }
                            }
                            ?>


                            <tr>

                                <!-- Academic year -->

                                <td>

                                    <div
                                        class="d-flex
                                               align-items-center"
                                    >

                                        <div
                                            class="rounded-circle
                                                   bg-primary-subtle
                                                   text-primary
                                                   d-flex
                                                   align-items-center
                                                   justify-content-center
                                                   me-3"
                                            style="
                                                width: 42px;
                                                height: 42px;
                                                min-width: 42px;
                                            "
                                        >
                                            <i
                                                class="bi
                                                       bi-calendar-event"
                                            ></i>
                                        </div>


                                        <div>

                                            <div class="fw-semibold">

                                                <?= htmlspecialchars(
                                                    $label,
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>

                                            </div>


                                            <small class="text-muted">

                                                ID #<?= $id ?>

                                            </small>

                                        </div>

                                    </div>

                                </td>


                                <!-- Start -->

                                <td>

                                    <?= htmlspecialchars(
                                        $formattedStartsOn,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                </td>


                                <!-- End -->

                                <td>

                                    <?= htmlspecialchars(
                                        $formattedEndsOn,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                </td>


                                <!-- Status -->

                                <td>

                                    <span
                                        class="badge
                                               rounded-pill
                                               <?= htmlspecialchars(
                                                   $statusClass,
                                                   ENT_QUOTES,
                                                   'UTF-8'
                                               ) ?>"
                                    >
                                        <?= htmlspecialchars(
                                            $statusLabel,
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </span>

                                </td>


                                <!-- Actions -->

                                <td class="text-end">

                                    <?php if ($id > 0): ?>

                                        <a
                                            href="/academic-years/<?= $id ?>"
                                            class="btn
                                                   btn-sm
                                                   btn-outline-primary"
                                            title="Consulter"
                                            aria-label="Consulter l'année académique"
                                        >
                                            <i class="bi bi-eye"></i>
                                        </a>


                                        <?php if (!$readOnly): ?>

                                            <a
                                                href="/academic-years/<?= $id ?>/edit"
                                                class="btn
                                                       btn-sm
                                                       btn-outline-secondary"
                                                title="Modifier"
                                                aria-label="Modifier l'année académique"
                                            >
                                                <i class="bi bi-pencil"></i>
                                            </a>

                                        <?php endif; ?>

                                    <?php endif; ?>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        <?php endif; ?>

    </div>

</div>