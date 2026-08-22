<?php

declare(strict_types=1);

/** @var array<int, array<string, mixed>> $ministries */

$ministries =
    is_array(
        $ministries
        ?? null
    )
        ? $ministries
        : [];

$totalMinistries =
    count(
        $ministries
    );

$activeMinistries =
    count(
        array_filter(
            $ministries,
            static fn (
                array $ministry
            ): bool =>
                ($ministry['status'] ?? null)
                === 'ACTIVE'
        )
    );

$suspendedMinistries =
    count(
        array_filter(
            $ministries,
            static fn (
                array $ministry
            ): bool =>
                ($ministry['status'] ?? null)
                === 'SUSPENDED'
        )
    );

$inactiveMinistries =
    count(
        array_filter(
            $ministries,
            static fn (
                array $ministry
            ): bool =>
                ($ministry['status'] ?? null)
                === 'INACTIVE'
        )
    );
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
                    Ministry
                </span>

                <span class="text-muted small">
                    Supervision institutionnelle
                </span>

            </div>

            <h2 class="fw-bold mb-1">
                Ministères
            </h2>

            <p class="text-muted mb-0">
                Gérez les institutions ministérielles
                enregistrées sur la plateforme MedTrack.
            </p>

        </div>


        <a
            href="/ministries/create"
            class="btn btn-primary
                   d-inline-flex align-items-center gap-2"
        >
            <i class="bi bi-plus-lg"></i>
            Nouveau ministère
        </a>

    </div>


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
                            <i class="bi bi-building fs-4"></i>
                        </div>

                        <div>
                            <div class="text-muted small">
                                Ministères enregistrés
                            </div>

                            <div class="fs-4 fw-bold">
                                <?= $totalMinistries ?>
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
                                Actifs
                            </div>

                            <div class="fs-4 fw-bold">
                                <?= $activeMinistries ?>
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
                            <i class="bi bi-pause-circle fs-4"></i>
                        </div>

                        <div>
                            <div class="text-muted small">
                                Suspendus
                            </div>

                            <div class="fs-4 fw-bold">
                                <?= $suspendedMinistries ?>
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
                                <?= $inactiveMinistries ?>
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
                        Répertoire des ministères
                    </h5>

                    <p class="text-muted small mb-0">
                        Liste des institutions ministérielles
                        enregistrées dans MedTrack.
                    </p>

                </div>

            </div>


            <div class="table-responsive">

                <table class="table align-middle mb-0">

                    <thead class="table-light">

                        <tr>
                            <th class="ps-4">Ministère</th>
                            <th>Domaine de compétence</th>
                            <th>Localisation</th>
                            <th>Contact</th>
                            <th>Statut</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>

                    </thead>


                    <tbody>

                        <?php if ($ministries === []): ?>

                            <tr>

                                <td
                                    colspan="6"
                                    class="text-center py-5"
                                >

                                    <div class="text-muted">

                                        <i
                                            class="bi bi-building
                                                   fs-1 d-block mb-3"
                                        ></i>

                                        <strong class="d-block mb-1">
                                            Aucun ministère enregistré
                                        </strong>

                                        <span class="small">
                                            Créez la première institution
                                            ministérielle de MedTrack.
                                        </span>

                                        <div class="mt-3">

                                            <a
                                                href="/ministries/create"
                                                class="btn btn-primary btn-sm"
                                            >
                                                <i class="bi bi-plus-lg me-1"></i>
                                                Nouveau ministère
                                            </a>

                                        </div>

                                    </div>

                                </td>

                            </tr>

                        <?php else: ?>

                            <?php foreach ($ministries as $ministry): ?>

                                <?php
                                $id =
                                    (int) (
                                        $ministry['id']
                                        ?? 0
                                    );

                                $status =
                                    (string) (
                                        $ministry['status']
                                        ?? 'INACTIVE'
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
                                            $ministry['city']
                                            ?? ''
                                        )
                                    );

                                $province =
                                    trim(
                                        (string) (
                                            $ministry['province']
                                            ?? ''
                                        )
                                    );

                                $email =
                                    trim(
                                        (string) (
                                            $ministry['email']
                                            ?? ''
                                        )
                                    );

                                $phone =
                                    trim(
                                        (string) (
                                            $ministry['phone']
                                            ?? ''
                                        )
                                    );
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
                                                <i class="bi bi-building"></i>
                                            </div>

                                            <div>

                                                <a
                                                    href="/ministries/<?= $id ?>"
                                                    class="fw-semibold text-decoration-none"
                                                >
                                                    <?= htmlspecialchars(
                                                        (string) (
                                                            $ministry['name']
                                                            ?? ''
                                                        ),
                                                        ENT_QUOTES,
                                                        'UTF-8'
                                                    ) ?>
                                                </a>

                                                <div class="text-muted small">
                                                    <?= htmlspecialchars(
                                                        (string) (
                                                            $ministry['code']
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

                                        <?php
                                        $scope =
                                            trim(
                                                (string) (
                                                    $ministry['ministry_scope']
                                                    ?? ''
                                                )
                                            );
                                        ?>

                                        <?= htmlspecialchars(
                                            $scope !== ''
                                                ? $scope
                                                : '—',
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>

                                    </td>


                                    <td>

                                        <div>
                                            <?= htmlspecialchars(
                                                $city !== ''
                                                    ? $city
                                                    : '—',
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        </div>

                                        <?php if ($province !== ''): ?>

                                            <small class="text-muted">
                                                <?= htmlspecialchars(
                                                    $province,
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>
                                            </small>

                                        <?php endif; ?>

                                    </td>


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

                                        <div
                                            class="btn-group btn-group-sm"
                                            role="group"
                                        >

                                            <a
                                                href="/ministries/<?= $id ?>"
                                                class="btn btn-outline-primary"
                                                title="Consulter"
                                            >
                                                <i class="bi bi-eye"></i>
                                            </a>


                                            <a
                                                href="/ministries/<?= $id ?>/edit"
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