<?php

declare(strict_types=1);

$id = (int) ($studyLevel['id'] ?? 0);

$code = (string) (
    $studyLevel['code']
    ?? ''
);

$name = (string) (
    $studyLevel['name']
    ?? ''
);

$ordinal =
    $studyLevel['ordinal']
    ?? null;
?>

<div class="container-fluid px-0">

    <div class="row justify-content-center">

        <div class="col-xl-10">

            <!--
            |--------------------------------------------------------------------------
            | Header
            |--------------------------------------------------------------------------
            -->

            <div
                class="d-flex flex-column flex-md-row
                       justify-content-between
                       align-items-md-center
                       gap-3 mb-4"
            >

                <div>

                    <div
                        class="d-flex
                               align-items-center
                               gap-2 mb-1"
                    >

                        <h4 class="fw-bold mb-0">
                            <?= htmlspecialchars(
                                $name,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </h4>

                        <span
                            class="badge
                                   bg-primary-subtle
                                   text-primary
                                   rounded-pill"
                        >
                            <?= htmlspecialchars(
                                $code,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </span>

                    </div>

                    <p class="text-muted mb-0">
                        Informations du niveau d’études
                    </p>

                </div>


                <div class="d-flex gap-2">

                    <a
                        href="/study-levels"
                        class="btn btn-outline-secondary"
                    >
                        <i class="bi bi-arrow-left me-1"></i>
                        Retour
                    </a>

                    <a
                        href="/study-levels/<?= $id ?>/edit"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-pencil me-1"></i>
                        Modifier
                    </a>

                </div>

            </div>


            <div class="row g-4">

                <!--
                |--------------------------------------------------------------------------
                | Main information
                |--------------------------------------------------------------------------
                -->

                <div class="col-lg-8">

                    <div class="card border-0 shadow-sm h-100">

                        <div
                            class="card-header
                                   bg-white
                                   border-0
                                   py-3"
                        >

                            <h5 class="mb-0 fw-semibold">

                                <i
                                    class="bi bi-mortarboard
                                           text-primary
                                           me-2"
                                ></i>

                                Informations académiques

                            </h5>

                        </div>


                        <div class="card-body">

                            <div class="row g-4">

                                <!-- Code -->

                                <div class="col-md-6">

                                    <div
                                        class="text-muted
                                               small mb-1"
                                    >
                                        Code
                                    </div>

                                    <div>

                                        <span
                                            class="badge
                                                   bg-primary-subtle
                                                   text-primary
                                                   rounded-pill
                                                   fs-6"
                                        >
                                            <?= htmlspecialchars(
                                                $code,
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        </span>

                                    </div>

                                </div>


                                <!-- Name -->

                                <div class="col-md-6">

                                    <div
                                        class="text-muted
                                               small mb-1"
                                    >
                                        Nom du niveau
                                    </div>

                                    <div class="fw-semibold">

                                        <?= htmlspecialchars(
                                            $name,
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>

                                    </div>

                                </div>


                                <!-- Ordinal -->

                                <div class="col-md-6">

                                    <div
                                        class="text-muted
                                               small mb-1"
                                    >
                                        Ordre académique
                                    </div>

                                    <div class="fw-semibold">

                                        <?php if ($ordinal !== null): ?>

                                            <span
                                                class="badge
                                                       bg-secondary-subtle
                                                       text-secondary-emphasis
                                                       rounded-pill"
                                            >
                                                <?= (int) $ordinal ?>
                                            </span>

                                        <?php else: ?>

                                            <span class="text-muted">
                                                Non défini
                                            </span>

                                        <?php endif; ?>

                                    </div>

                                </div>


                                <!-- Identifier -->

                                <div class="col-md-6">

                                    <div
                                        class="text-muted
                                               small mb-1"
                                    >
                                        Identifiant interne
                                    </div>

                                    <div class="fw-semibold">
                                        #<?= $id ?>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                <!--
                |--------------------------------------------------------------------------
                | Summary
                |--------------------------------------------------------------------------
                -->

                <div class="col-lg-4">

                    <div class="card border-0 shadow-sm h-100">

                        <div
                            class="card-body
                                   text-center
                                   py-4"
                        >

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

                                <i
                                    class="bi bi-layers
                                           fs-2"
                                ></i>

                            </div>


                            <h5 class="fw-semibold mb-1">

                                <?= htmlspecialchars(
                                    $name,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>

                            </h5>


                            <div class="mb-3">

                                <span
                                    class="badge
                                           bg-primary-subtle
                                           text-primary
                                           rounded-pill"
                                >
                                    <?= htmlspecialchars(
                                        $code,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </span>

                            </div>


                            <p class="text-muted small">
                                Niveau du référentiel académique
                                MedTrack
                            </p>


                            <hr>


                            <div class="text-start">

                                <div
                                    class="d-flex
                                           justify-content-between
                                           align-items-center
                                           mb-3"
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
                                           align-items-center"
                                >

                                    <span class="text-muted">
                                        Ordre
                                    </span>

                                    <strong>

                                        <?php if ($ordinal !== null): ?>

                                            <?= (int) $ordinal ?>

                                        <?php else: ?>

                                            —

                                        <?php endif; ?>

                                    </strong>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!--
            |--------------------------------------------------------------------------
            | Information
            |--------------------------------------------------------------------------
            -->

            <div class="alert alert-light border mt-4 mb-0">

                <div class="d-flex align-items-start">

                    <i
                        class="bi bi-info-circle
                               text-primary
                               fs-5 me-3"
                    ></i>

                    <div>

                        <strong>
                            Utilisation du niveau
                        </strong>

                        <div class="small text-muted mt-1">
                            Ce niveau fait partie du référentiel
                            académique global. Il pourra être utilisé
                            lors des inscriptions académiques afin
                            d’indiquer le niveau d’études de l’étudiant.
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>