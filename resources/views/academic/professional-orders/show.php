<?php

declare(strict_types=1);

/** @var array<string, mixed> $professionalOrder */

$id =
    (int) (
        $professionalOrder['id']
        ?? 0
    );

$status =
    (string) (
        $professionalOrder['status']
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
                    Professional Order
                </span>

                <span class="text-muted small">
                    Fiche institutionnelle
                </span>

            </div>

            <h2 class="fw-bold mb-1">
                <?= htmlspecialchars(
                    (string) (
                        $professionalOrder['name']
                        ?? 'Ordre professionnel'
                    ),
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </h2>

            <p class="text-muted mb-0">
                <?= htmlspecialchars(
                    (string) (
                        $professionalOrder['code']
                        ?? ''
                    ),
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </p>

        </div>

        <div class="d-flex gap-2">

            <a
                href="/professional-orders"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Retour
            </a>

            <a
                href="/professional-orders/<?= $id ?>/edit"
                class="btn btn-primary"
            >
                <i class="bi bi-pencil me-1"></i>
                Modifier
            </a>

        </div>

    </div>


    <div class="row g-4">

        <div class="col-xl-8">

            <!-- Identification -->

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
                            <i class="bi bi-award fs-4"></i>
                        </div>

                        <div>

                            <h5 class="fw-bold mb-1">
                                Identification
                            </h5>

                            <p class="text-muted small mb-0">
                                Informations principales de
                                l’ordre professionnel.
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
                                        $professionalOrder['code']
                                        ?? '—'
                                    ),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Code profession
                            </div>

                            <div class="fw-semibold">
                                <?= htmlspecialchars(
                                    (string) (
                                        $professionalOrder['profession_code']
                                        ?? '—'
                                    ),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </div>

                        </div>


                        <div class="col-12">

                            <div class="text-muted small mb-1">
                                Nom officiel
                            </div>

                            <div class="fw-semibold">
                                <?= htmlspecialchars(
                                    (string) (
                                        $professionalOrder['name']
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


            <!-- Localisation -->

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
                                Siège et implantation administrative.
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
                                        $professionalOrder['province']
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
                                        $professionalOrder['city']
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
                                        $professionalOrder['address']
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
                                Informations de contact institutionnelles.
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
                                        $professionalOrder['email']
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
                                        $professionalOrder['phone']
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


        <!-- Right panel -->

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
                                État administratif MedTrack.
                            </p>

                        </div>

                    </div>


                    <div class="mb-4">

                        <div class="text-muted small mb-2">
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


                    <div class="mb-4">

                        <div class="text-muted small mb-2">
                            Type d’organisation
                        </div>

                        <span
                            class="badge rounded-pill
                                   text-bg-primary"
                        >
                            Ordre professionnel
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


                        <div class="d-flex gap-2 mb-3">

                            <i class="bi bi-fingerprint text-info"></i>

                            <span>
                                UUID :
                                <?= htmlspecialchars(
                                    (string) (
                                        $professionalOrder['uuid']
                                        ?? '—'
                                    ),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </span>

                        </div>


                        <div class="d-flex gap-2">

                            <i class="bi bi-calendar-check text-success"></i>

                            <span>
                                Créé le
                                <?= htmlspecialchars(
                                    (string) (
                                        $professionalOrder['created_at']
                                        ?? '—'
                                    ),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </span>

                        </div>

                    </div>


                    <div class="d-grid gap-2">

                        <a
                            href="/professional-orders/<?= $id ?>/edit"
                            class="btn btn-primary"
                        >
                            <i class="bi bi-pencil me-1"></i>
                            Modifier l’ordre
                        </a>

                        <a
                            href="/professional-orders"
                            class="btn btn-light"
                        >
                            Retour au répertoire
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>