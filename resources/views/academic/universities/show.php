<?php

declare(strict_types=1);

/**
 * @var array $university
 */

$status = (string) ($university['status'] ?? 'INACTIVE');

$accreditationStatus = (string) (
    $university['accreditation_status']
    ?? 'PENDING'
);

$score = $university['accreditation_score'] ?? null;

$statusClass = match ($status) {
    'ACTIVE' => 'success',
    'SUSPENDED' => 'warning',
    default => 'secondary',
};

$statusLabel = match ($status) {
    'ACTIVE' => 'Active',
    'SUSPENDED' => 'Suspendue',
    'INACTIVE' => 'Inactive',
    default => $status,
};

$accreditationClass = match ($accreditationStatus) {
    'ACCREDITED' => 'success',
    'SUSPENDED' => 'warning',
    'REVOKED' => 'danger',
    default => 'secondary',
};

$accreditationLabel = match ($accreditationStatus) {
    'ACCREDITED' => 'Accréditée',
    'SUSPENDED' => 'Suspendue',
    'REVOKED' => 'Révoquée',
    'PENDING' => 'En attente',
    default => $accreditationStatus,
};

$id = (int) ($university['id'] ?? 0);

$name = (string) ($university['name'] ?? '');
$code = (string) ($university['code'] ?? '');
$type = (string) ($university['university_type'] ?? '');

$province = (string) ($university['province'] ?? '');
$city = (string) ($university['city'] ?? '');
$address = (string) ($university['address'] ?? '');
$email = (string) ($university['email'] ?? '');
$phone = (string) ($university['phone'] ?? '');

$location = array_filter([
    $city,
    $province,
]);
?>

<div class="container-fluid px-0">

    <!-- =========================================================
         Header
         ========================================================= -->

    <div class="d-flex flex-column flex-xl-row
                justify-content-between
                align-items-xl-center
                gap-3 mb-4">

        <div>

            <div class="d-flex align-items-center gap-2 mb-2">

                <a
                    href="/universities"
                    class="text-decoration-none text-muted"
                >
                    Universités
                </a>

                <i class="bi bi-chevron-right small text-muted"></i>

                <span class="text-muted">
                    Fiche institutionnelle
                </span>

            </div>

            <div class="d-flex flex-wrap align-items-center gap-3">

                <h2 class="fw-bold mb-0">
                    <?= htmlspecialchars($name) ?>
                </h2>

                <span
                    class="badge rounded-pill
                           text-bg-<?= $statusClass ?>"
                >
                    <?= htmlspecialchars($statusLabel) ?>
                </span>

            </div>

            <div class="text-muted mt-2">

                <span class="me-3">
                    <i class="bi bi-upc-scan me-1"></i>

                    <?= htmlspecialchars($code) ?>
                </span>

                <?php if ($location !== []): ?>

                    <span>
                        <i class="bi bi-geo-alt me-1"></i>

                        <?= htmlspecialchars(
                            implode(', ', $location)
                        ) ?>
                    </span>

                <?php endif; ?>

            </div>

        </div>


        <div class="d-flex flex-wrap gap-2">

            <a
                href="/universities"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Retour
            </a>

            <a
                href="/universities/<?= $id ?>/edit"
                class="btn btn-primary"
            >
                <i class="bi bi-pencil-square me-1"></i>
                Modifier
            </a>

        </div>

    </div>


    <!-- =========================================================
         Overview
         ========================================================= -->

    <div class="row g-4 mb-4">

        <!-- Identity -->

        <div class="col-xl-8">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-4">

                    <div class="d-flex
                                justify-content-between
                                align-items-start
                                gap-3 mb-4">

                        <div class="d-flex align-items-center gap-3">

                            <div
                                class="rounded-circle
                                       bg-primary-subtle
                                       text-primary
                                       d-flex
                                       align-items-center
                                       justify-content-center
                                       flex-shrink-0"
                                style="
                                    width: 56px;
                                    height: 56px;
                                "
                            >
                                <i class="bi bi-mortarboard fs-3"></i>
                            </div>

                            <div>

                                <h5 class="fw-bold mb-1">
                                    Identité institutionnelle
                                </h5>

                                <p class="text-muted small mb-0">
                                    Informations générales de l'université
                                </p>

                            </div>

                        </div>

                    </div>


                    <div class="row g-4">

                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Nom officiel
                            </div>

                            <div class="fw-semibold">
                                <?= htmlspecialchars($name) ?>
                            </div>

                        </div>


                        <div class="col-md-3">

                            <div class="text-muted small mb-1">
                                Code
                            </div>

                            <span class="badge text-bg-light fs-6">
                                <?= htmlspecialchars($code) ?>
                            </span>

                        </div>


                        <div class="col-md-3">

                            <div class="text-muted small mb-1">
                                Type
                            </div>

                            <div class="fw-semibold">

                                <?= $type !== ''
                                    ? htmlspecialchars($type)
                                    : '<span class="text-muted">Non renseigné</span>'
                                ?>

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Statut institutionnel
                            </div>

                            <span
                                class="badge text-bg-<?= $statusClass ?>"
                            >
                                <?= htmlspecialchars($statusLabel) ?>
                            </span>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Accréditation
                            </div>

                            <span
                                class="badge text-bg-<?= $accreditationClass ?>"
                            >
                                <?= htmlspecialchars(
                                    $accreditationLabel
                                ) ?>
                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- Accreditation score -->

        <div class="col-xl-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-4">

                    <div class="d-flex align-items-center gap-3 mb-4">

                        <div
                            class="rounded-circle
                                   bg-warning-subtle
                                   text-warning
                                   d-flex
                                   align-items-center
                                   justify-content-center"
                            style="
                                width: 48px;
                                height: 48px;
                            "
                        >
                            <i class="bi bi-patch-check fs-4"></i>
                        </div>

                        <div>

                            <h5 class="fw-bold mb-1">
                                Accréditation
                            </h5>

                            <span
                                class="badge
                                       text-bg-<?= $accreditationClass ?>"
                            >
                                <?= htmlspecialchars(
                                    $accreditationLabel
                                ) ?>
                            </span>

                        </div>

                    </div>


                    <?php if ($score !== null): ?>

                        <?php
                        $numericScore = max(
                            0,
                            min(
                                100,
                                (float) $score
                            )
                        );
                        ?>

                        <div class="text-center py-3">

                            <div class="display-4 fw-bold">
                                <?= htmlspecialchars(
                                    number_format(
                                        $numericScore,
                                        1,
                                        ',',
                                        ' '
                                    )
                                ) ?>
                            </div>

                            <div class="text-muted">
                                sur 100
                            </div>

                        </div>


                        <div
                            class="progress"
                            role="progressbar"
                            aria-valuenow="<?= $numericScore ?>"
                            aria-valuemin="0"
                            aria-valuemax="100"
                            style="height: 10px;"
                        >
                            <div
                                class="progress-bar"
                                style="
                                    width:
                                    <?= $numericScore ?>%;
                                "
                            ></div>
                        </div>

                    <?php else: ?>

                        <div class="text-center py-4">

                            <div class="display-6 text-muted mb-2">
                                <i class="bi bi-bar-chart"></i>
                            </div>

                            <div class="fw-semibold">
                                Score non disponible
                            </div>

                            <div class="small text-muted mt-1">
                                Aucun score d'accréditation
                                n'a encore été enregistré.
                            </div>

                        </div>

                    <?php endif; ?>

                </div>

            </div>

        </div>

    </div>


    <!-- =========================================================
         Contact and location
         ========================================================= -->

    <div class="row g-4 mb-4">

        <!-- Contact -->

        <div class="col-lg-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-4">

                    <div class="d-flex align-items-center gap-3 mb-4">

                        <div
                            class="rounded-circle
                                   bg-info-subtle
                                   text-info
                                   d-flex
                                   align-items-center
                                   justify-content-center"
                            style="
                                width: 46px;
                                height: 46px;
                            "
                        >
                            <i class="bi bi-person-lines-fill"></i>
                        </div>

                        <div>

                            <h5 class="fw-bold mb-0">
                                Coordonnées institutionnelles
                            </h5>

                        </div>

                    </div>


                    <div class="d-flex gap-3 mb-4">

                        <div class="text-muted">
                            <i class="bi bi-envelope"></i>
                        </div>

                        <div>

                            <div class="small text-muted">
                                E-mail institutionnel
                            </div>

                            <?php if ($email !== ''): ?>

                                <a
                                    href="mailto:<?= htmlspecialchars(
                                        $email,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                    class="text-decoration-none fw-semibold"
                                >
                                    <?= htmlspecialchars($email) ?>
                                </a>

                            <?php else: ?>

                                <span class="text-muted">
                                    Non renseignée
                                </span>

                            <?php endif; ?>

                        </div>

                    </div>


                    <div class="d-flex gap-3">

                        <div class="text-muted">
                            <i class="bi bi-telephone"></i>
                        </div>

                        <div>

                            <div class="small text-muted">
                                Téléphone
                            </div>

                            <?php if ($phone !== ''): ?>

                                <a
                                    href="tel:<?= htmlspecialchars(
                                        $phone,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                    class="text-decoration-none fw-semibold"
                                >
                                    <?= htmlspecialchars($phone) ?>
                                </a>

                            <?php else: ?>

                                <span class="text-muted">
                                    Non renseigné
                                </span>

                            <?php endif; ?>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- Location -->

        <div class="col-lg-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-4">

                    <div class="d-flex align-items-center gap-3 mb-4">

                        <div
                            class="rounded-circle
                                   bg-success-subtle
                                   text-success
                                   d-flex
                                   align-items-center
                                   justify-content-center"
                            style="
                                width: 46px;
                                height: 46px;
                            "
                        >
                            <i class="bi bi-geo-alt"></i>
                        </div>

                        <h5 class="fw-bold mb-0">
                            Localisation
                        </h5>

                    </div>


                    <div class="row g-4">

                        <div class="col-sm-6">

                            <div class="small text-muted">
                                Province
                            </div>

                            <div class="fw-semibold">
                                <?= $province !== ''
                                    ? htmlspecialchars($province)
                                    : 'Non renseignée'
                                ?>
                            </div>

                        </div>


                        <div class="col-sm-6">

                            <div class="small text-muted">
                                Ville
                            </div>

                            <div class="fw-semibold">
                                <?= $city !== ''
                                    ? htmlspecialchars($city)
                                    : 'Non renseignée'
                                ?>
                            </div>

                        </div>


                        <div class="col-12">

                            <div class="small text-muted">
                                Adresse
                            </div>

                            <div class="fw-semibold">
                                <?= $address !== ''
                                    ? nl2br(
                                        htmlspecialchars($address)
                                    )
                                    : 'Non renseignée'
                                ?>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- =========================================================
         Institutional access
         ========================================================= -->

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body p-4">

            <div class="d-flex flex-column flex-lg-row
                        justify-content-between
                        align-items-lg-center
                        gap-3">

                <div class="d-flex align-items-start gap-3">

                    <div
                        class="rounded-circle
                               bg-primary-subtle
                               text-primary
                               d-flex align-items-center
                               justify-content-center
                               flex-shrink-0"
                        style="width: 52px; height: 52px;"
                    >
                        <i class="bi bi-person-lock fs-4"></i>
                    </div>

                    <div>

                        <h5 class="fw-bold mb-1">
                            Accès institutionnel
                        </h5>

                        <p class="text-muted mb-2">
                            L’administrateur principal de cette université
                            est géré séparément des informations institutionnelles.
                        </p>

                        <div class="small text-muted">
                            <i class="bi bi-shield-check me-1"></i>
                            Rôle attendu :
                            <strong class="text-body">UNIVERSITY_ADMIN</strong>
                        </div>

                    </div>

                </div>

                <div>
                    <span class="badge text-bg-light border px-3 py-2">
                        <i class="bi bi-key me-1"></i>
                        Compte administrateur séparé
                    </span>
                </div>

            </div>

        </div>

    </div>


    <!-- =========================================================
         Academic ecosystem
         ========================================================= -->

    <div class="card border-0 shadow-sm">

        <div class="card-body p-4">

            <div class="d-flex flex-column flex-lg-row
                        justify-content-between
                        align-items-lg-center
                        gap-3 mb-4">

                <div>

                    <h5 class="fw-bold mb-1">
                        Structure académique
                    </h5>

                    <p class="text-muted mb-0">
                        Ressources académiques associées
                        à cette université.
                    </p>

                </div>

            </div>


            <div class="row g-3">

                <div class="col-xl-3 col-md-6">

                    <div
                        class="border rounded-3 p-3 h-100
                               d-flex align-items-center gap-3"
                    >

                        <div class="fs-3 text-primary">
                            <i class="bi bi-diagram-3"></i>
                        </div>

                        <div>
                            <div class="fw-bold fs-5">
                                —
                            </div>

                            <div class="small text-muted">
                                Facultés
                            </div>
                        </div>

                    </div>

                </div>


                <div class="col-xl-3 col-md-6">

                    <div
                        class="border rounded-3 p-3 h-100
                               d-flex align-items-center gap-3"
                    >

                        <div class="fs-3 text-success">
                            <i class="bi bi-journal-bookmark"></i>
                        </div>

                        <div>
                            <div class="fw-bold fs-5">
                                —
                            </div>

                            <div class="small text-muted">
                                Programmes
                            </div>
                        </div>

                    </div>

                </div>


                <div class="col-xl-3 col-md-6">

                    <div
                        class="border rounded-3 p-3 h-100
                               d-flex align-items-center gap-3"
                    >

                        <div class="fs-3 text-info">
                            <i class="bi bi-people"></i>
                        </div>

                        <div>
                            <div class="fw-bold fs-5">
                                —
                            </div>

                            <div class="small text-muted">
                                Étudiants
                            </div>
                        </div>

                    </div>

                </div>


                <div class="col-xl-3 col-md-6">

                    <div
                        class="border rounded-3 p-3 h-100
                               d-flex align-items-center gap-3"
                    >

                        <div class="fs-3 text-warning">
                            <i class="bi bi-briefcase"></i>
                        </div>

                        <div>
                            <div class="fw-bold fs-5">
                                —
                            </div>

                            <div class="small text-muted">
                                Stages
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>