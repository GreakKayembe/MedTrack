<?php

declare(strict_types=1);

/** @var array<string, mixed> $hospital */

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
                    Fiche institutionnelle
                </span>

            </div>

            <h2 class="fw-bold mb-1">
                <?= htmlspecialchars(
                    (string) (
                        $hospital['name']
                        ?? 'Hôpital'
                    ),
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </h2>

            <p class="text-muted mb-0">
                <?= htmlspecialchars(
                    (string) (
                        $hospital['code']
                        ?? ''
                    ),
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </p>

        </div>


        <div class="d-flex gap-2">

            <a
                href="/hospitals"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Retour
            </a>

            <a
                href="/hospitals/<?= $id ?>/edit"
                class="btn btn-primary"
            >
                <i class="bi bi-pencil me-1"></i>
                Modifier
            </a>

        </div>

    </div>


    <div class="row g-4">

        <div class="col-xl-8">

            <!-- Identity -->

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
                            <i class="bi bi-hospital fs-4"></i>
                        </div>

                        <div>

                            <h5 class="fw-bold mb-1">
                                Identification
                            </h5>

                            <p class="text-muted small mb-0">
                                Informations principales de
                                l’établissement.
                            </p>

                        </div>

                    </div>


                    <div class="row g-4">

                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Code institutionnel
                            </div>

                            <div class="fw-semibold">
                                <?= htmlspecialchars(
                                    (string) (
                                        $hospital['code']
                                        ?? '—'
                                    ),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Niveau de l’établissement
                            </div>

                            <div class="fw-semibold">
                                <?= htmlspecialchars(
                                    (string) (
                                        $hospital['facility_level']
                                        ?? '—'
                                    ),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Spécialité principale
                            </div>

                            <div class="fw-semibold">
                                <?= htmlspecialchars(
                                    (string) (
                                        $hospital['specialty']
                                        ?? '—'
                                    ),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Capacité de stage
                            </div>

                            <div class="fw-semibold">
                                <?= (int) (
                                    $hospital['internship_capacity']
                                    ?? 0
                                ) ?>
                                stagiaires
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- Location -->

            <div class="card border-0 shadow-sm mb-4">

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
                            <i class="bi bi-geo-alt fs-4"></i>
                        </div>

                        <div>

                            <h5 class="fw-bold mb-1">
                                Localisation
                            </h5>

                            <p class="text-muted small mb-0">
                                Adresse administrative et coordonnées.
                            </p>

                        </div>

                    </div>


                    <div class="row g-4">

                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Province
                            </div>

                            <div class="fw-semibold">
                                <?= htmlspecialchars(
                                    (string) (
                                        $hospital['province']
                                        ?? '—'
                                    ),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Ville
                            </div>

                            <div class="fw-semibold">
                                <?= htmlspecialchars(
                                    (string) (
                                        $hospital['city']
                                        ?? '—'
                                    ),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </div>

                        </div>


                        <div class="col-12">

                            <div class="text-muted small mb-1">
                                Adresse
                            </div>

                            <div class="fw-semibold">
                                <?= htmlspecialchars(
                                    (string) (
                                        $hospital['address']
                                        ?? '—'
                                    ),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Latitude
                            </div>

                            <div class="fw-semibold">
                                <?= htmlspecialchars(
                                    (string) (
                                        $hospital['latitude']
                                        ?? '—'
                                    ),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Longitude
                            </div>

                            <div class="fw-semibold">
                                <?= htmlspecialchars(
                                    (string) (
                                        $hospital['longitude']
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


            <!-- Contact -->

            <div class="card border-0 shadow-sm">

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
                            <i class="bi bi-person-lines-fill fs-4"></i>
                        </div>

                        <div>

                            <h5 class="fw-bold mb-1">
                                Coordonnées
                            </h5>

                            <p class="text-muted small mb-0">
                                Contacts institutionnels.
                            </p>

                        </div>

                    </div>


                    <div class="row g-4">

                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Adresse e-mail
                            </div>

                            <div class="fw-semibold">
                                <?= htmlspecialchars(
                                    (string) (
                                        $hospital['email']
                                        ?? '—'
                                    ),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Téléphone
                            </div>

                            <div class="fw-semibold">
                                <?= htmlspecialchars(
                                    (string) (
                                        $hospital['phone']
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

        </div>


        <!-- Right column -->

        <div class="col-xl-4">

            <div
                class="card border-0 shadow-sm mb-4"
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
                            <i class="bi bi-patch-check fs-4"></i>
                        </div>

                        <div>

                            <h5 class="fw-bold mb-1">
                                Situation
                            </h5>

                            <p class="text-muted small mb-0">
                                État administratif actuel.
                            </p>

                        </div>

                    </div>


                    <div class="mb-4">

                        <div class="text-muted small mb-2">
                            Statut MedTrack
                        </div>

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

                    </div>


                    <div class="mb-4">

                        <div class="text-muted small mb-2">
                            Accréditation
                        </div>

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

                    </div>


                    <hr>


                    <div class="small text-muted mb-4">

                        <div class="d-flex gap-2 mb-3">

                            <i class="bi bi-database-check text-primary"></i>

                            <span>
                                Organisation ID :
                                <strong><?= $id ?></strong>
                            </span>

                        </div>

                        <div class="d-flex gap-2">

                            <i class="bi bi-calendar-check text-success"></i>

                            <span>
                                Créé le
                                <?= htmlspecialchars(
                                    (string) (
                                        $hospital['created_at']
                                        ?? '—'
                                    ),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </span>

                        </div>

                    </div>


                    <div class="d-grid">

                        <a
                            href="/hospitals/<?= $id ?>/edit"
                            class="btn btn-primary"
                        >
                            <i class="bi bi-pencil me-1"></i>
                            Modifier l’établissement
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>