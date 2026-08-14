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

$statistics = $statistics ?? [
    'total' => 0,
    'planned' => 0,
    'open' => 0,
    'closed' => 0,
];

$academicYears = $academicYears ?? [];
?>

<div class="row g-4 mb-4">

    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">

                    <div>
                        <div class="text-muted small mb-1">
                            Total
                        </div>

                        <h3 class="mb-0 fw-bold">
                            <?= (int) $statistics['total'] ?>
                        </h3>

                        <div class="small text-muted mt-1">
                            Années académiques
                        </div>
                    </div>

                    <div
                        class="rounded-circle bg-primary-subtle text-primary
                               d-flex align-items-center justify-content-center"
                        style="width: 52px; height: 52px;"
                    >
                        <i class="bi bi-calendar3 fs-4"></i>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">

                    <div>
                        <div class="text-muted small mb-1">
                            Planifiées
                        </div>

                        <h3 class="mb-0 fw-bold">
                            <?= (int) $statistics['planned'] ?>
                        </h3>

                        <div class="small text-muted mt-1">
                            À venir
                        </div>
                    </div>

                    <div
                        class="rounded-circle bg-warning-subtle text-warning
                               d-flex align-items-center justify-content-center"
                        style="width: 52px; height: 52px;"
                    >
                        <i class="bi bi-clock-history fs-4"></i>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">

                    <div>
                        <div class="text-muted small mb-1">
                            Ouvertes
                        </div>

                        <h3 class="mb-0 fw-bold">
                            <?= (int) $statistics['open'] ?>
                        </h3>

                        <div class="small text-muted mt-1">
                            En cours
                        </div>
                    </div>

                    <div
                        class="rounded-circle bg-success-subtle text-success
                               d-flex align-items-center justify-content-center"
                        style="width: 52px; height: 52px;"
                    >
                        <i class="bi bi-calendar-check fs-4"></i>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">

                    <div>
                        <div class="text-muted small mb-1">
                            Clôturées
                        </div>

                        <h3 class="mb-0 fw-bold">
                            <?= (int) $statistics['closed'] ?>
                        </h3>

                        <div class="small text-muted mt-1">
                            Terminées
                        </div>
                    </div>

                    <div
                        class="rounded-circle bg-secondary-subtle text-secondary
                               d-flex align-items-center justify-content-center"
                        style="width: 52px; height: 52px;"
                    >
                        <i class="bi bi-calendar-x fs-4"></i>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>


<div class="card border-0 shadow-sm">

    <div class="card-header bg-white border-0 py-3">

        <div
            class="d-flex flex-column flex-md-row
                   align-items-md-center
                   justify-content-between gap-3"
        >

            <div>
                <h5 class="mb-1 fw-semibold">
                    Années académiques
                </h5>

                <p class="text-muted small mb-0">
                    Gérez les périodes académiques utilisées
                    par MedTrack.
                </p>
            </div>

            <div>
                <a
                    href="/academic-years/create"
                    class="btn btn-primary"
                >
                    <i class="bi bi-plus-lg me-1"></i>
                    Nouvelle année académique
                </a>
            </div>

        </div>

    </div>


    <div class="card-body">

        <?php if ($academicYears === []): ?>

            <div class="text-center py-5">

                <div class="mb-3">
                    <i
                        class="bi bi-calendar3
                               display-4 text-muted"
                    ></i>
                </div>

                <h5>
                    Aucune année académique
                </h5>

                <p class="text-muted mb-4">
                    Commencez par enregistrer votre
                    première année académique.
                </p>

                <a
                    href="/academic-years/create"
                    class="btn btn-primary"
                >
                    <i class="bi bi-plus-lg me-1"></i>
                    Créer une année académique
                </a>

            </div>

        <?php else: ?>

            <div class="table-responsive">

                <table
                    class="table table-hover align-middle"
                >

                    <thead>
                        <tr>
                            <th>Année académique</th>
                            <th>Début</th>
                            <th>Fin</th>
                            <th>Statut</th>

                            <th
                                class="text-end"
                                style="width: 160px;"
                            >
                                Actions
                            </th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php foreach ($academicYears as $academicYear): ?>

                            <?php
                            $status =
                                (string) (
                                    $academicYear['status']
                                    ?? ''
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
                            ?>

                            <tr>

                                <td>
                                    <div class="d-flex align-items-center">

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
                                            <i class="bi bi-calendar-event"></i>
                                        </div>

                                        <div>
                                            <div class="fw-semibold">
                                                <?= htmlspecialchars(
                                                    (string) $academicYear['label']
                                                ) ?>
                                            </div>

                                            <small class="text-muted">
                                                ID #<?= (int) $academicYear['id'] ?>
                                            </small>
                                        </div>

                                    </div>
                                </td>


                                <td>
                                    <?= $startsOn !== ''
                                        ? htmlspecialchars(
                                            date(
                                                'd/m/Y',
                                                strtotime($startsOn)
                                            )
                                        )
                                        : '—'
                                    ?>
                                </td>


                                <td>
                                    <?= $endsOn !== ''
                                        ? htmlspecialchars(
                                            date(
                                                'd/m/Y',
                                                strtotime($endsOn)
                                            )
                                        )
                                        : '—'
                                    ?>
                                </td>


                                <td>
                                    <span
                                        class="badge rounded-pill
                                               <?= htmlspecialchars(
                                                   $statusClass
                                               ) ?>"
                                    >
                                        <?= htmlspecialchars(
                                            $statusLabel
                                        ) ?>
                                    </span>
                                </td>


                                <td class="text-end">

                                    <a
                                        href="/academic-years/<?= (int) $academicYear['id'] ?>"
                                        class="btn btn-sm btn-outline-primary"
                                        title="Consulter"
                                    >
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <a
                                        href="/academic-years/<?= (int) $academicYear['id'] ?>/edit"
                                        class="btn btn-sm btn-outline-secondary"
                                        title="Modifier"
                                    >
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        <?php endif; ?>

    </div>

</div>