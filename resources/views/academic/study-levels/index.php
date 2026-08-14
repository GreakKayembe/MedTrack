<?php

declare(strict_types=1);

$studyLevels = $studyLevels ?? [];
$statistics = $statistics ?? [];

$total = (int) ($statistics['total'] ?? count($studyLevels));
?>

<div class="container-fluid px-0">

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

            <h4 class="fw-bold mb-1">
                Niveaux d’études
            </h4>

            <p class="text-muted mb-0">
                Gérez les niveaux académiques utilisés
                dans les inscriptions des étudiants.
            </p>

        </div>


        <div>

            <a
                href="/study-levels/create"
                class="btn btn-primary"
            >
                <i class="bi bi-plus-lg me-1"></i>

                Nouveau niveau
            </a>

        </div>

    </div>


    <!--
    |--------------------------------------------------------------------------
    | Statistics
    |--------------------------------------------------------------------------
    -->

    <div class="row g-3 mb-4">

        <div class="col-sm-6 col-lg-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div
                        class="d-flex
                               justify-content-between
                               align-items-center"
                    >

                        <div>

                            <div
                                class="text-muted
                                       small mb-1"
                            >
                                Total des niveaux
                            </div>

                            <div class="fs-3 fw-bold">
                                <?= $total ?>
                            </div>

                        </div>


                        <div
                            class="rounded-circle
                                   bg-primary-subtle
                                   text-primary
                                   d-flex
                                   align-items-center
                                   justify-content-center"
                            style="
                                width: 52px;
                                height: 52px;
                            "
                        >

                            <i
                                class="bi bi-layers
                                       fs-4"
                            ></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!--
    |--------------------------------------------------------------------------
    | Levels table
    |--------------------------------------------------------------------------
    -->

    <div class="card border-0 shadow-sm">

        <div
            class="card-header
                   bg-white
                   border-0
                   py-3"
        >

            <div
                class="d-flex
                       justify-content-between
                       align-items-center"
            >

                <div>

                    <h5 class="mb-1 fw-semibold">
                        Référentiel des niveaux
                    </h5>

                    <div class="text-muted small">
                        Classement selon l’ordre académique.
                    </div>

                </div>


                <span
                    class="badge
                           bg-primary-subtle
                           text-primary
                           rounded-pill"
                >
                    <?= $total ?> niveau<?= $total !== 1 ? 'x' : '' ?>
                </span>

            </div>

        </div>


        <div class="card-body p-0">

            <?php if ($studyLevels === []): ?>

                <!--
                |--------------------------------------------------------------------------
                | Empty state
                |--------------------------------------------------------------------------
                -->

                <div
                    class="text-center
                           py-5 px-3"
                >

                    <div
                        class="rounded-circle
                               bg-light
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
                                   fs-2
                                   text-muted"
                        ></i>

                    </div>


                    <h5 class="fw-semibold">
                        Aucun niveau d’études
                    </h5>


                    <p
                        class="text-muted
                               mb-4"
                    >
                        Commencez par créer les niveaux
                        académiques utilisés par votre
                        établissement.
                    </p>


                    <a
                        href="/study-levels/create"
                        class="btn btn-primary"
                    >

                        <i class="bi bi-plus-lg me-1"></i>

                        Créer le premier niveau

                    </a>

                </div>

            <?php else: ?>

                <!--
                |--------------------------------------------------------------------------
                | Table
                |--------------------------------------------------------------------------
                -->

                <div class="table-responsive">

                    <table
                        class="table
                               table-hover
                               align-middle
                               mb-0"
                    >

                        <thead class="table-light">

                            <tr>

                                <th class="ps-4">
                                    Ordre
                                </th>

                                <th>
                                    Code
                                </th>

                                <th>
                                    Niveau d’études
                                </th>

                                <th
                                    class="text-end pe-4"
                                >
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            <?php foreach ($studyLevels as $studyLevel): ?>

                                <?php
                                $id = (int) (
                                    $studyLevel['id']
                                    ?? 0
                                );

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

                                <tr>

                                    <!-- Ordinal -->

                                    <td class="ps-4">

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
                                                —
                                            </span>

                                        <?php endif; ?>

                                    </td>


                                    <!-- Code -->

                                    <td>

                                        <span
                                            class="badge
                                                   bg-primary-subtle
                                                   text-primary
                                                   rounded-pill"
                                        >
                                            <?= htmlspecialchars(
                                                $code
                                            ) ?>
                                        </span>

                                    </td>


                                    <!-- Name -->

                                    <td>

                                        <div class="fw-semibold">

                                            <?= htmlspecialchars(
                                                $name
                                            ) ?>

                                        </div>

                                    </td>


                                    <!-- Actions -->

                                    <td
                                        class="text-end pe-4"
                                    >

                                        <div
                                            class="btn-group
                                                   btn-group-sm"
                                            role="group"
                                        >

                                            <a
                                                href="/study-levels/<?= $id ?>"
                                                class="btn
                                                       btn-outline-secondary"
                                                title="Consulter"
                                            >
                                                <i class="bi bi-eye"></i>
                                            </a>


                                            <a
                                                href="/study-levels/<?= $id ?>/edit"
                                                class="btn
                                                       btn-outline-primary"
                                                title="Modifier"
                                            >
                                                <i class="bi bi-pencil"></i>
                                            </a>

                                        </div>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            <?php endif; ?>

        </div>

    </div>

</div>