<?php

declare(strict_types=1);

$program = $program ?? [];

$id = (int) (
    $program['id']
    ?? 0
);

$name = htmlspecialchars(
    (string) (
        $program['name']
        ?? 'Programme académique'
    )
);

$code = htmlspecialchars(
    (string) (
        $program['code']
        ?? '—'
    )
);

$disciplineCode = htmlspecialchars(
    (string) (
        $program['discipline_code']
        ?? '—'
    )
);

$universityName = htmlspecialchars(
    (string) (
        $program['university_name']
        ?? '—'
    )
);

$universityCode = htmlspecialchars(
    (string) (
        $program['university_code']
        ?? ''
    )
);

$facultyName = htmlspecialchars(
    (string) (
        $program['faculty_name']
        ?? ''
    )
);

$facultyCode = htmlspecialchars(
    (string) (
        $program['faculty_code']
        ?? ''
    )
);

$durationYears =
    $program['duration_years']
    ?? null;

$status = (string) (
    $program['status']
    ?? 'INACTIVE'
);

ob_start();
?>

<div class="container-fluid px-0">

    <!-- Header -->
    <div
        class="d-flex flex-column flex-lg-row
               justify-content-between
               align-items-lg-center
               gap-3 mb-4"
    >

        <div>

            <div class="d-flex align-items-center gap-2 mb-2">

                <a
                    href="/academic-programs"
                    class="text-decoration-none text-muted"
                >
                    <i class="bi bi-arrow-left"></i>
                    Programmes
                </a>

                <span class="text-muted">
                    /
                </span>

                <span class="text-muted">
                    <?= $code ?>
                </span>

            </div>

            <h3 class="fw-bold mb-1">
                <?= $name ?>
            </h3>

            <p class="text-muted mb-0">
                Informations détaillées du programme académique.
            </p>

        </div>


        <div class="d-flex gap-2">

            <a
                href="/academic-programs"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-list-ul me-1"></i>
                Liste
            </a>

            <a
                href="/academic-programs/<?= $id ?>/edit"
                class="btn btn-primary"
            >
                <i class="bi bi-pencil me-1"></i>
                Modifier
            </a>

        </div>

    </div>


    <!-- Overview -->
    <div class="row g-4 mb-4">

        <!-- Programme -->
        <div class="col-xl-8">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-4">

                    <div
                        class="d-flex flex-column
                               flex-md-row
                               align-items-md-center
                               gap-4"
                    >

                        <div
                            class="rounded-4
                                   bg-primary-subtle
                                   text-primary
                                   d-flex
                                   align-items-center
                                   justify-content-center
                                   flex-shrink-0"
                            style="
                                width: 82px;
                                height: 82px;
                            "
                        >
                            <i class="bi bi-journal-medical fs-1"></i>
                        </div>


                        <div class="flex-grow-1">

                            <div
                                class="d-flex flex-wrap
                                       align-items-center
                                       gap-2 mb-2"
                            >

                                <h4 class="fw-bold mb-0">
                                    <?= $name ?>
                                </h4>


                                <?php if ($status === 'ACTIVE'): ?>

                                    <span
                                        class="badge
                                               bg-success-subtle
                                               text-success
                                               rounded-pill"
                                    >
                                        <i class="bi bi-check-circle me-1"></i>
                                        Actif
                                    </span>

                                <?php else: ?>

                                    <span
                                        class="badge
                                               bg-secondary-subtle
                                               text-secondary
                                               rounded-pill"
                                    >
                                        <i class="bi bi-pause-circle me-1"></i>
                                        Inactif
                                    </span>

                                <?php endif; ?>

                            </div>


                            <div class="text-muted mb-3">
                                Code :
                                <strong class="text-body">
                                    <?= $code ?>
                                </strong>
                            </div>


                            <div class="d-flex flex-wrap gap-2">

                                <span
                                    class="badge
                                           text-bg-light
                                           border
                                           px-3 py-2"
                                >
                                    <i class="bi bi-tag me-1"></i>
                                    <?= $disciplineCode ?>
                                </span>


                                <?php if ($durationYears !== null): ?>

                                    <span
                                        class="badge
                                               text-bg-light
                                               border
                                               px-3 py-2"
                                    >
                                        <i class="bi bi-clock me-1"></i>

                                        <?= (int) $durationYears ?>

                                        an<?= (int) $durationYears > 1 ? 's' : '' ?>
                                    </span>

                                <?php endif; ?>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- Status -->
        <div class="col-xl-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-4">

                    <div
                        class="d-flex
                               justify-content-between
                               align-items-start
                               mb-4"
                    >

                        <div>
                            <div class="text-muted small mb-1">
                                Statut actuel
                            </div>

                            <?php if ($status === 'ACTIVE'): ?>

                                <h5 class="text-success fw-bold mb-0">
                                    Actif
                                </h5>

                            <?php else: ?>

                                <h5 class="text-secondary fw-bold mb-0">
                                    Inactif
                                </h5>

                            <?php endif; ?>

                        </div>


                        <div
                            class="rounded-circle
                                   <?= $status === 'ACTIVE'
                                       ? 'bg-success-subtle text-success'
                                       : 'bg-secondary-subtle text-secondary' ?>
                                   d-flex
                                   align-items-center
                                   justify-content-center"
                            style="
                                width: 48px;
                                height: 48px;
                            "
                        >
                            <i
                                class="bi
                                <?= $status === 'ACTIVE'
                                    ? 'bi-check-circle'
                                    : 'bi-pause-circle' ?>
                                fs-4"
                            ></i>
                        </div>

                    </div>


                    <hr>


                    <div class="small text-muted mb-1">
                        Identifiant MedTrack
                    </div>

                    <div class="fw-semibold">
                        #<?= $id ?>
                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- Academic structure -->
    <div class="row g-4">

        <div class="col-lg-7">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white border-0 pt-4 px-4">

                    <div class="d-flex align-items-center gap-2">

                        <div
                            class="rounded-circle
                                   bg-primary-subtle
                                   text-primary
                                   d-flex
                                   align-items-center
                                   justify-content-center"
                            style="
                                width: 40px;
                                height: 40px;
                            "
                        >
                            <i class="bi bi-building"></i>
                        </div>

                        <div>
                            <h5 class="fw-semibold mb-0">
                                Structure académique
                            </h5>
                        </div>

                    </div>

                </div>


                <div class="card-body px-4 pb-4">

                    <!-- University -->
                    <div class="py-3 border-bottom">

                        <div class="row align-items-center">

                            <div class="col-md-4">
                                <div class="text-muted small">
                                    Université
                                </div>
                            </div>

                            <div class="col-md-8">

                                <div class="fw-semibold">
                                    <?= $universityName ?>
                                </div>

                                <?php if ($universityCode !== ''): ?>

                                    <div class="small text-muted">
                                        <?= $universityCode ?>
                                    </div>

                                <?php endif; ?>

                            </div>

                        </div>

                    </div>


                    <!-- Faculty -->
                    <div class="py-3">

                        <div class="row align-items-center">

                            <div class="col-md-4">
                                <div class="text-muted small">
                                    Faculté
                                </div>
                            </div>

                            <div class="col-md-8">

                                <?php if ($facultyName !== ''): ?>

                                    <div class="fw-semibold">
                                        <?= $facultyName ?>
                                    </div>

                                    <?php if ($facultyCode !== ''): ?>

                                        <div class="small text-muted">
                                            <?= $facultyCode ?>
                                        </div>

                                    <?php endif; ?>

                                <?php else: ?>

                                    <div class="text-muted">
                                        <i class="bi bi-dash-circle me-1"></i>
                                        Rattachement direct à l'université
                                    </div>

                                <?php endif; ?>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- Program details -->
        <div class="col-lg-5">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white border-0 pt-4 px-4">

                    <div class="d-flex align-items-center gap-2">

                        <div
                            class="rounded-circle
                                   bg-info-subtle
                                   text-info
                                   d-flex
                                   align-items-center
                                   justify-content-center"
                            style="
                                width: 40px;
                                height: 40px;
                            "
                        >
                            <i class="bi bi-info-circle"></i>
                        </div>

                        <h5 class="fw-semibold mb-0">
                            Détails du programme
                        </h5>

                    </div>

                </div>


                <div class="card-body px-4 pb-4">

                    <div class="py-3 border-bottom">

                        <div class="text-muted small mb-1">
                            Code du programme
                        </div>

                        <div class="fw-semibold">
                            <?= $code ?>
                        </div>

                    </div>


                    <div class="py-3 border-bottom">

                        <div class="text-muted small mb-1">
                            Discipline
                        </div>

                        <span class="badge text-bg-light border">
                            <?= $disciplineCode ?>
                        </span>

                    </div>


                    <div class="py-3">

                        <div class="text-muted small mb-1">
                            Durée académique
                        </div>

                        <?php if ($durationYears !== null): ?>

                            <div class="fw-semibold">
                                <i class="bi bi-calendar3 me-1 text-primary"></i>

                                <?= (int) $durationYears ?>

                                an<?= (int) $durationYears > 1 ? 's' : '' ?>
                            </div>

                        <?php else: ?>

                            <div class="text-muted">
                                Non renseignée
                            </div>

                        <?php endif; ?>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- Bottom actions -->
    <div
        class="d-flex
               justify-content-between
               align-items-center
               mt-4"
    >

        <a
            href="/academic-programs"
            class="btn btn-light border"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Retour
        </a>

        <a
            href="/academic-programs/<?= $id ?>/edit"
            class="btn btn-primary"
        >
            <i class="bi bi-pencil-square me-1"></i>
            Modifier le programme
        </a>

    </div>

</div>

