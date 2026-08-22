<?php

declare(strict_types=1);

/** @var array<int, array<string, mixed>> $hospitals */

$hospitals =
    is_array(
        $hospitals
        ?? null
    )
        ? $hospitals
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
                    Hospital
                </span>

                <span class="text-muted small">
                    Gestion institutionnelle
                </span>

            </div>

            <h2 class="fw-bold mb-1">
                Hôpitaux
            </h2>

            <p class="text-muted mb-0">
                Gérez les établissements hospitaliers,
                leurs capacités de stage et leur statut
                d’accréditation.
            </p>

        </div>


        <a
            href="/hospitals/create"
            class="btn btn-primary
                   d-inline-flex align-items-center gap-2"
        >
            <i class="bi bi-plus-lg"></i>
            Nouvel hôpital
        </a>

    </div>


    <!-- Summary cards -->

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
                            <i class="bi bi-hospital fs-4"></i>
                        </div>

                        <div>
                            <div class="text-muted small">
                                Hôpitaux enregistrés
                            </div>

                            <div class="fs-4 fw-bold">
                                <?= count($hospitals) ?>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-6 col-xl-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-4">

                    <?php
                    $activeCount =
                        count(
                            array_filter(
                                $hospitals,
                                static fn (
                                    array $hospital
                                ): bool =>
                                    ($hospital['status'] ?? null)
                                    === 'ACTIVE'
                            )
                        );
                    ?>

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
                                <?= $activeCount ?>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-6 col-xl-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-4">

                    <?php
                    $accreditedCount =
                        count(
                            array_filter(
                                $hospitals,
                                static fn (
                                    array $hospital
                                ): bool =>
                                    (
                                        $hospital['accreditation_status']
                                        ?? null
                                    ) === 'ACCREDITED'
                            )
                        );
                    ?>

                    <div class="d-flex align-items-center gap-3">

                        <div
                            class="rounded-circle
                                   bg-warning-subtle
                                   text-warning
                                   d-flex align-items-center
                                   justify-content-center"
                            style="width:48px;height:48px;"
                        >
                            <i class="bi bi-patch-check fs-4"></i>
                        </div>

                        <div>
                            <div class="text-muted small">
                                Accrédités
                            </div>

                            <div class="fs-4 fw-bold">
                                <?= $accreditedCount ?>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-6 col-xl-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-4">

                    <?php
                    $capacityTotal = 0;

                    foreach ($hospitals as $hospital) {
                        $capacityTotal +=
                            (int) (
                                $hospital['internship_capacity']
                                ?? 0
                            );
                    }
                    ?>

                    <div class="d-flex align-items-center gap-3">

                        <div
                            class="rounded-circle
                                   bg-info-subtle
                                   text-info
                                   d-flex align-items-center
                                   justify-content-center"
                            style="width:48px;height:48px;"
                        >
                            <i class="bi bi-people fs-4"></i>
                        </div>

                        <div>
                            <div class="text-muted small">
                                Capacité totale
                            </div>

                            <div class="fs-4 fw-bold">
                                <?= $capacityTotal ?>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <div class="card border-0 shadow-sm">

        <div class="card-body p-0">

            <div
                class="d-flex flex-column flex-md-row
                       justify-content-between align-items-md-center
                       gap-3 p-4 border-bottom"
            >

                <div>

                    <h5 class="fw-bold mb-1">
                        Répertoire hospitalier
                    </h5>

                    <p class="text-muted small mb-0">
                        Liste des établissements enregistrés
                        dans MedTrack.
                    </p>

                </div>

            </div>


            <div class="table-responsive">

                <table class="table align-middle mb-0">

                    <thead class="table-light">

                        <tr>
                            <th class="ps-4">Établissement</th>
                            <th>Localisation</th>
                            <th>Spécialité</th>
                            <th>Capacité</th>
                            <th>Accréditation</th>
                            <th>Statut</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>

                    </thead>

                    <tbody>

                        <?php if ($hospitals === []): ?>

                            <tr>

                                <td
                                    colspan="7"
                                    class="text-center py-5"
                                >

                                    <div class="text-muted">

                                        <i
                                            class="bi bi-hospital
                                                   fs-1 d-block mb-3"
                                        ></i>

                                        <strong class="d-block mb-1">
                                            Aucun hôpital enregistré
                                        </strong>

                                        <span class="small">
                                            Créez votre premier établissement
                                            hospitalier.
                                        </span>

                                    </div>

                                </td>

                            </tr>

                        <?php else: ?>

                            <?php foreach ($hospitals as $hospital): ?>

                                <?php
                                $id =
                                    (int) (
                                        $hospital['id']
                                        ?? 0
                                    );

                                $status =
                                    (string) (
                                        $hospital['status']
                                        ?? ''
                                    );

                                $accreditation =
                                    (string) (
                                        $hospital['accreditation_status']
                                        ?? ''
                                    );

                                $statusClass =
                                    match ($status) {
                                        'ACTIVE' =>
                                            'text-bg-success',

                                        'SUSPENDED' =>
                                            'text-bg-warning',

                                        default =>
                                            'text-bg-secondary',
                                    };

                                $accreditationClass =
                                    match ($accreditation) {
                                        'ACCREDITED' =>
                                            'text-bg-success',

                                        'PENDING' =>
                                            'text-bg-warning',

                                        'SUSPENDED' =>
                                            'text-bg-secondary',

                                        'REVOKED' =>
                                            'text-bg-danger',

                                        default =>
                                            'text-bg-light',
                                    };
                                ?>

                                <tr>

                                    <td class="ps-4">

                                        <div class="d-flex align-items-center gap-3">

                                            <div
                                                class="rounded-circle
                                                       bg-primary-subtle
                                                       text-primary
                                                       d-flex align-items-center
                                                       justify-content-center
                                                       flex-shrink-0"
                                                style="width:42px;height:42px;"
                                            >
                                                <i class="bi bi-hospital"></i>
                                            </div>

                                            <div>

                                                <a
                                                    href="/hospitals/<?= $id ?>"
                                                    class="fw-semibold
                                                           text-decoration-none"
                                                >
                                                    <?= htmlspecialchars(
                                                        (string) (
                                                            $hospital['name']
                                                            ?? ''
                                                        ),
                                                        ENT_QUOTES,
                                                        'UTF-8'
                                                    ) ?>
                                                </a>

                                                <div class="text-muted small">
                                                    <?= htmlspecialchars(
                                                        (string) (
                                                            $hospital['code']
                                                            ?? ''
                                                        ),
                                                        ENT_QUOTES,
                                                        'UTF-8'
                                                    ) ?>
                                                </div>

                                            </div>

                                        </div>

                                    </td>


                                    <td>

                                        <div>
                                            <?= htmlspecialchars(
                                                (string) (
                                                    $hospital['city']
                                                    ?? '—'
                                                ),
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        </div>

                                        <small class="text-muted">
                                            <?= htmlspecialchars(
                                                (string) (
                                                    $hospital['province']
                                                    ?? '—'
                                                ),
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        </small>

                                    </td>


                                    <td>
                                        <?= htmlspecialchars(
                                            (string) (
                                                $hospital['specialty']
                                                ?? '—'
                                            ),
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </td>


                                    <td>

                                        <span class="fw-semibold">
                                            <?= (int) (
                                                $hospital['internship_capacity']
                                                ?? 0
                                            ) ?>
                                        </span>

                                        <small class="text-muted">
                                            stagiaires
                                        </small>

                                    </td>


                                    <td>

                                        <span
                                            class="badge rounded-pill
                                                   <?= $accreditationClass ?>"
                                        >
                                            <?= htmlspecialchars(
                                                $accreditation,
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        </span>

                                    </td>


                                    <td>

                                        <span
                                            class="badge rounded-pill
                                                   <?= $statusClass ?>"
                                        >
                                            <?= htmlspecialchars(
                                                $status,
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        </span>

                                    </td>


                                    <td class="text-end pe-4">

                                        <div
                                            class="btn-group btn-group-sm"
                                            role="group"
                                        >

                                            <a
                                                href="/hospitals/<?= $id ?>"
                                                class="btn btn-outline-primary"
                                                title="Consulter"
                                            >
                                                <i class="bi bi-eye"></i>
                                            </a>

                                            <a
                                                href="/hospitals/<?= $id ?>/edit"
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