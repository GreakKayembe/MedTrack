<?php

declare(strict_types=1);

/**
 * @var array<string, mixed> $academicYear
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

$readOnly =
    (bool) (
        $readOnly
        ?? false
    );

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

<div class="row justify-content-center">

    <div class="col-xl-10">

        <!-- ========================================================
             Header
             ======================================================== -->

        <div
            class="d-flex
                   flex-column
                   flex-md-row
                   justify-content-between
                   align-items-md-center
                   gap-3
                   mb-4"
        >

            <div>

                <div class="d-flex align-items-center gap-2 mb-2">

                    <?php if ($readOnly): ?>

                        <span
                            class="badge
                                   rounded-pill
                                   bg-light
                                   text-secondary
                                   border"
                        >
                            <i class="bi bi-lock me-1"></i>
                            Référentiel MedTrack
                        </span>

                    <?php else: ?>

                        <span
                            class="badge
                                   rounded-pill
                                   bg-primary-subtle
                                   text-primary"
                        >
                            Administration
                        </span>

                    <?php endif; ?>

                </div>


                <h4 class="fw-bold mb-1">

                    <?= htmlspecialchars(
                        $label,
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>

                </h4>


                <p class="text-muted mb-0">

                    <?php if ($readOnly): ?>

                        Consultation de l’année académique
                        définie dans le référentiel MedTrack.

                    <?php else: ?>

                        Informations de l’année académique.

                    <?php endif; ?>

                </p>

            </div>


            <div class="d-flex gap-2">

                <a
                    href="/academic-years"
                    class="btn btn-outline-secondary"
                >
                    <i class="bi bi-arrow-left me-1"></i>
                    Retour
                </a>


                <?php if (
                    !$readOnly
                    && $id > 0
                ): ?>

                    <a
                        href="/academic-years/<?= $id ?>/edit"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-pencil me-1"></i>
                        Modifier
                    </a>

                <?php endif; ?>

            </div>

        </div>


        <div class="row g-4">

            <!-- ====================================================
                 Academic period
                 ==================================================== -->

            <div class="col-lg-8">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-header bg-white border-0 py-3">

                        <h5 class="mb-0 fw-semibold">

                            <i
                                class="bi bi-calendar3
                                       me-2
                                       text-primary"
                            ></i>

                            Période académique

                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row g-4">

                            <!-- Label -->

                            <div class="col-md-6">

                                <div class="text-muted small mb-1">
                                    Année académique
                                </div>

                                <div class="fw-semibold fs-5">

                                    <?= htmlspecialchars(
                                        $label,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                </div>

                            </div>


                            <!-- Status -->

                            <div class="col-md-6">

                                <div class="text-muted small mb-1">
                                    Statut
                                </div>

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

                            </div>


                            <!-- Start date -->

                            <div class="col-md-6">

                                <div class="text-muted small mb-1">
                                    Date de début
                                </div>

                                <div class="fw-semibold">

                                    <?php if (
                                        $formattedStartsOn !== '—'
                                    ): ?>

                                        <i
                                            class="bi bi-calendar-event
                                                   me-1
                                                   text-primary"
                                        ></i>

                                    <?php endif; ?>

                                    <?= htmlspecialchars(
                                        $formattedStartsOn,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                </div>

                            </div>


                            <!-- End date -->

                            <div class="col-md-6">

                                <div class="text-muted small mb-1">
                                    Date de fin
                                </div>

                                <div class="fw-semibold">

                                    <?php if (
                                        $formattedEndsOn !== '—'
                                    ): ?>

                                        <i
                                            class="bi bi-calendar-check
                                                   me-1
                                                   text-success"
                                        ></i>

                                    <?php endif; ?>

                                    <?= htmlspecialchars(
                                        $formattedEndsOn,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- ====================================================
                 Summary
                 ==================================================== -->

            <div class="col-lg-4">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body text-center py-4">

                        <div
                            class="rounded-circle
                                   bg-primary-subtle
                                   text-primary
                                   d-inline-flex
                                   align-items-center
                                   justify-content-center
                                   mb-3"
                            style="
                                width: 72px;
                                height: 72px;
                            "
                        >
                            <i class="bi bi-calendar-range fs-2"></i>
                        </div>


                        <h5 class="fw-semibold">

                            <?= htmlspecialchars(
                                $label,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>

                        </h5>


                        <p class="text-muted small">

                            <?php if ($readOnly): ?>

                                Référentiel académique MedTrack

                            <?php else: ?>

                                Période académique MedTrack

                            <?php endif; ?>

                        </p>


                        <hr>


                        <div class="text-start">

                            <div
                                class="d-flex
                                       justify-content-between
                                       mb-2"
                            >

                                <span class="text-muted">
                                    Identifiant
                                </span>

                                <strong>
                                    #<?= $id ?>
                                </strong>

                            </div>


                            <div
                                class="d-flex
                                       justify-content-between
                                       mb-2"
                            >

                                <span class="text-muted">
                                    Statut
                                </span>

                                <strong>

                                    <?= htmlspecialchars(
                                        $statusLabel,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                </strong>

                            </div>


                            <div
                                class="d-flex
                                       justify-content-between"
                            >

                                <span class="text-muted">
                                    Accès
                                </span>

                                <?php if ($readOnly): ?>

                                    <strong class="text-secondary">
                                        Lecture seule
                                    </strong>

                                <?php else: ?>

                                    <strong class="text-primary">
                                        Administration
                                    </strong>

                                <?php endif; ?>

                            </div>

                        </div>


                        <?php if ($readOnly): ?>

                            <div
                                class="alert
                                       alert-light
                                       border
                                       small
                                       text-start
                                       mt-4
                                       mb-0"
                            >
                                <div class="d-flex gap-2">

                                    <i
                                        class="bi bi-info-circle
                                               text-primary"
                                    ></i>

                                    <span>
                                        Cette année académique est
                                        définie au niveau central de
                                        MedTrack. Votre université peut
                                        la consulter et l’utiliser dans
                                        ses opérations, mais ne peut pas
                                        la modifier.
                                    </span>

                                </div>
                            </div>

                        <?php endif; ?>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>