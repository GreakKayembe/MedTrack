<?php

declare(strict_types=1);

/**
 * @var array<string, mixed> $faculty
 * @var bool $isPlatform
 * @var bool $isUniversityContext
 */

$faculty =
    is_array($faculty ?? null)
        ? $faculty
        : [];

$isPlatform =
    (bool) ($isPlatform ?? false);

$isUniversityContext =
    (bool) ($isUniversityContext ?? false);

$id =
    (int) ($faculty['id'] ?? 0);

$name =
    trim((string) ($faculty['name'] ?? ''));

$code =
    trim((string) ($faculty['code'] ?? ''));

$status =
    strtoupper(
        trim(
            (string) ($faculty['status'] ?? 'INACTIVE')
        )
    );

$universityId =
    (int) ($faculty['university_id'] ?? 0);

$universityName =
    trim(
        (string) ($faculty['university_name'] ?? '')
    );

$universityCode =
    trim(
        (string) ($faculty['university_code'] ?? '')
    );

$isActive =
    $status === 'ACTIVE';
?>

<div class="container-fluid px-0">

    <!-- =========================================================
         Header
         ========================================================= -->

    <div
        class="d-flex flex-column flex-lg-row
               justify-content-between
               align-items-lg-center
               gap-3 mb-4"
    >

        <div class="d-flex align-items-center gap-3">

            <div
                class="rounded-circle
                       bg-primary-subtle
                       text-primary
                       d-flex
                       align-items-center
                       justify-content-center
                       flex-shrink-0"
                style="width:56px;height:56px;"
            >
                <i class="bi bi-diagram-3 fs-4"></i>
            </div>

            <div>

                <h2 class="fw-bold mb-1">
                    <?= htmlspecialchars(
                        $name,
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </h2>

                <div class="text-muted">
                    <?php if ($isUniversityContext): ?>
                        Faculté de votre université
                    <?php else: ?>
                        Faculté universitaire
                    <?php endif; ?>
                </div>

            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="/faculties"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Retour
            </a>

            <a
                href="/faculties/<?= $id ?>/edit"
                class="btn btn-primary"
            >
                <i class="bi bi-pencil-square me-1"></i>
                Modifier
            </a>

        </div>

    </div>


    <!-- =========================================================
         Metrics
         ========================================================= -->

    <div class="row g-4 mb-4">

        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-4">

                    <div class="text-muted small mb-2">
                        Statut
                    </div>

                    <?php if ($isActive): ?>

                        <span
                            class="badge rounded-pill
                                   text-bg-success px-3 py-2"
                        >
                            <i class="bi bi-check-circle me-1"></i>
                            Active
                        </span>

                    <?php else: ?>

                        <span
                            class="badge rounded-pill
                                   text-bg-secondary px-3 py-2"
                        >
                            <i class="bi bi-pause-circle me-1"></i>
                            Inactive
                        </span>

                    <?php endif; ?>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-4">

                    <div class="text-muted small mb-2">
                        Code de la faculté
                    </div>

                    <?php if ($code !== ''): ?>

                        <div class="fs-5 fw-bold">
                            <?= htmlspecialchars(
                                $code,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </div>

                    <?php else: ?>

                        <div class="text-muted">
                            Non renseigné
                        </div>

                    <?php endif; ?>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-4">

                    <div class="text-muted small mb-2">
                        Identifiant
                    </div>

                    <div class="fs-5 fw-bold">
                        #<?= $id ?>
                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- =========================================================
         Main information
         ========================================================= -->

    <div class="row g-4">

        <div class="col-xl-7">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white py-3">

                    <h5 class="fw-bold mb-0">
                        <i
                            class="bi bi-info-circle
                                   text-primary me-2"
                        ></i>
                        Informations de la faculté
                    </h5>

                </div>


                <div class="card-body p-4">

                    <div class="row g-4">

                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Nom
                            </div>

                            <div class="fw-semibold">
                                <?= htmlspecialchars(
                                    $name,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Code
                            </div>

                            <div class="fw-semibold">
                                <?= $code !== ''
                                    ? htmlspecialchars(
                                        $code,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    )
                                    : '—'
                                ?>
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Statut
                            </div>

                            <div class="fw-semibold">
                                <?= $isActive
                                    ? 'Active'
                                    : 'Inactive'
                                ?>
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                ID interne
                            </div>

                            <div class="fw-semibold">
                                <?= $id ?>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- =====================================================
             Institutional relationship
             ===================================================== -->

        <div class="col-xl-5">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white py-3">

                    <h5 class="fw-bold mb-0">

                        <i
                            class="bi bi-mortarboard
                                   text-primary me-2"
                        ></i>

                        <?php if ($isUniversityContext): ?>
                            Rattachement institutionnel
                        <?php else: ?>
                            Université de rattachement
                        <?php endif; ?>

                    </h5>

                </div>


                <div class="card-body p-4">

                    <div class="d-flex gap-3">

                        <div
                            class="rounded-circle
                                   bg-primary-subtle
                                   text-primary
                                   d-flex
                                   align-items-center
                                   justify-content-center
                                   flex-shrink-0"
                            style="width:52px;height:52px;"
                        >
                            <i class="bi bi-building fs-4"></i>
                        </div>


                        <div class="flex-grow-1">

                            <?php if ($universityName !== ''): ?>

                                <div class="fw-bold fs-5">
                                    <?= htmlspecialchars(
                                        $universityName,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </div>

                            <?php elseif ($isUniversityContext): ?>

                                <div class="fw-bold fs-5">
                                    Votre université
                                </div>

                            <?php else: ?>

                                <div class="fw-bold fs-5 text-muted">
                                    Université non renseignée
                                </div>

                            <?php endif; ?>


                            <?php if ($universityCode !== ''): ?>

                                <div class="text-muted mb-3">
                                    Code :
                                    <?= htmlspecialchars(
                                        $universityCode,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </div>

                            <?php endif; ?>


                            <?php if ($isUniversityContext): ?>

                                <div
                                    class="alert alert-light border
                                           py-2 px-3 mb-0 mt-3"
                                >
                                    <div
                                        class="d-flex
                                               align-items-start
                                               gap-2"
                                    >
                                        <i
                                            class="bi bi-shield-lock
                                                   text-primary"
                                        ></i>

                                        <small class="text-muted">
                                            Cette faculté est rattachée
                                            à votre université active.
                                            Ce rattachement est protégé
                                            et ne peut pas être modifié
                                            depuis l’espace Université.
                                        </small>
                                    </div>
                                </div>

                            <?php elseif (
                                $isPlatform
                                && $universityId > 0
                            ): ?>

                                <a
                                    href="/universities/<?= $universityId ?>"
                                    class="btn btn-sm
                                           btn-outline-primary"
                                >
                                    <i
                                        class="bi
                                               bi-box-arrow-up-right
                                               me-1"
                                    ></i>
                                    Voir l'université
                                </a>

                            <?php endif; ?>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- =========================================================
         Academic relationships
         ========================================================= -->

    <div class="card border-0 shadow-sm mt-4">

        <div class="card-body p-4">

            <div
                class="d-flex flex-column flex-md-row
                       justify-content-between
                       align-items-md-center
                       gap-3"
            >

                <div>

                    <h5 class="fw-bold mb-1">
                        <i
                            class="bi bi-journal-medical
                                   text-primary me-2"
                        ></i>
                        Programmes académiques
                    </h5>

                    <p class="text-muted mb-0">
                        <?php if ($isUniversityContext): ?>
                            Gérez les programmes académiques
                            rattachés à cette faculté.
                        <?php else: ?>
                            Les programmes académiques rattachés
                            à cette faculté seront disponibles ici.
                        <?php endif; ?>
                    </p>

                </div>


                <span
                    class="badge text-bg-light
                           border px-3 py-2"
                >
                    Module suivant
                </span>

            </div>

        </div>

    </div>

</div>