<?php

declare(strict_types=1);

$statusLabels = [
    'PLANNED' => 'Planifiée',
    'OPEN' => 'Ouverte',
    'CLOSED' => 'Clôturée',
];

$statusClasses = [
    'PLANNED' => 'bg-warning-subtle text-warning-emphasis',
    'OPEN' => 'bg-success-subtle text-success-emphasis',
    'CLOSED' => 'bg-secondary-subtle text-secondary-emphasis',
];

$status = (string) ($academicYear['status'] ?? '');

$statusLabel =
    $statusLabels[$status] ?? $status;

$statusClass =
    $statusClasses[$status]
    ?? 'bg-light text-dark';

$startsOn =
    (string) ($academicYear['starts_on'] ?? '');

$endsOn =
    (string) ($academicYear['ends_on'] ?? '');
?>

<div class="row justify-content-center">

    <div class="col-xl-10">

        <div class="d-flex flex-column flex-md-row
                    justify-content-between
                    align-items-md-center
                    gap-3 mb-4">

            <div>
                <h4 class="fw-bold mb-1">
                    <?= htmlspecialchars(
                        (string) $academicYear['label']
                    ) ?>
                </h4>

                <p class="text-muted mb-0">
                    Informations de l’année académique
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

                <a
                    href="/academic-years/<?= (int) $academicYear['id'] ?>/edit"
                    class="btn btn-primary"
                >
                    <i class="bi bi-pencil me-1"></i>
                    Modifier
                </a>

            </div>

        </div>


        <div class="row g-4">

            <div class="col-lg-8">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-header bg-white border-0 py-3">

                        <h5 class="mb-0 fw-semibold">
                            <i class="bi bi-calendar3 me-2 text-primary"></i>
                            Période académique
                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="row g-4">

                            <div class="col-md-6">

                                <div class="text-muted small mb-1">
                                    Année académique
                                </div>

                                <div class="fw-semibold fs-5">
                                    <?= htmlspecialchars(
                                        (string) $academicYear['label']
                                    ) ?>
                                </div>

                            </div>


                            <div class="col-md-6">

                                <div class="text-muted small mb-1">
                                    Statut
                                </div>

                                <span
                                    class="badge rounded-pill <?= htmlspecialchars(
                                        $statusClass
                                    ) ?>"
                                >
                                    <?= htmlspecialchars($statusLabel) ?>
                                </span>

                            </div>


                            <div class="col-md-6">

                                <div class="text-muted small mb-1">
                                    Date de début
                                </div>

                                <div class="fw-semibold">

                                    <?php if ($startsOn !== ''): ?>

                                        <i class="bi bi-calendar-event me-1 text-primary"></i>

                                        <?= htmlspecialchars(
                                            date(
                                                'd/m/Y',
                                                strtotime($startsOn)
                                            )
                                        ) ?>

                                    <?php else: ?>

                                        —

                                    <?php endif; ?>

                                </div>

                            </div>


                            <div class="col-md-6">

                                <div class="text-muted small mb-1">
                                    Date de fin
                                </div>

                                <div class="fw-semibold">

                                    <?php if ($endsOn !== ''): ?>

                                        <i class="bi bi-calendar-check me-1 text-success"></i>

                                        <?= htmlspecialchars(
                                            date(
                                                'd/m/Y',
                                                strtotime($endsOn)
                                            )
                                        ) ?>

                                    <?php else: ?>

                                        —

                                    <?php endif; ?>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <div class="col-lg-4">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body text-center py-4">

                        <div
                            class="rounded-circle bg-primary-subtle
                                   text-primary d-inline-flex
                                   align-items-center
                                   justify-content-center mb-3"
                            style="width: 72px; height: 72px;"
                        >
                            <i class="bi bi-calendar-range fs-2"></i>
                        </div>

                        <h5 class="fw-semibold">
                            <?= htmlspecialchars(
                                (string) $academicYear['label']
                            ) ?>
                        </h5>

                        <p class="text-muted small">
                            Période académique MedTrack
                        </p>

                        <hr>

                        <div class="text-start">

                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">
                                    Identifiant
                                </span>

                                <strong>
                                    #<?= (int) $academicYear['id'] ?>
                                </strong>
                            </div>

                            <div class="d-flex justify-content-between">
                                <span class="text-muted">
                                    Statut
                                </span>

                                <strong>
                                    <?= htmlspecialchars($statusLabel) ?>
                                </strong>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>