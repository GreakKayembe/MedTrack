<?php

declare(strict_types=1);

/**
 * @var array<int, array<string, mixed>> $cohorts
 * @var array<string, mixed> $statistics
 * @var bool $isPlatform
 * @var bool $isUniversityContext
 * @var int|null $activeUniversityId
 */

$total =
    (int) (
        $statistics['total']
        ?? count($cohorts)
    );
?>

<div class="d-flex flex-column flex-md-row
            justify-content-between align-items-md-center
            gap-3 mb-4">

    <div>

        <h4 class="mb-1">
            Gestion des cohortes
        </h4>

        <p class="text-muted mb-0">

            <?php if ($isUniversityContext): ?>

                Gérez les cohortes associées
                aux programmes de votre université
                et aux années académiques MedTrack.

            <?php else: ?>

                Gérez les cohortes associées aux programmes,
                universités et années académiques.

            <?php endif; ?>

        </p>

    </div>

    <div>

        <a
            href="/cohorts/create"
            class="btn btn-primary"
        >
            <i class="bi bi-plus-circle me-1"></i>
            Nouvelle cohorte
        </a>

    </div>

</div>


<div class="card">

    <div class="card-body">

        <div class="d-flex justify-content-between
                    align-items-center mb-3">

            <h5 class="card-title mb-0">
                Cohortes
            </h5>

            <span class="badge bg-primary-subtle text-primary">
                <?= $total ?>
                cohorte<?= $total > 1 ? 's' : '' ?>
            </span>

        </div>


        <?php if ($cohorts === []): ?>

            <div class="text-center py-5">

                <div class="mb-3">
                    <i
                        class="bi bi-people"
                        style="font-size: 3rem;"
                    ></i>
                </div>

                <h5>
                    Aucune cohorte
                </h5>

                <p class="text-muted">

                    <?php if ($isUniversityContext): ?>

                        Votre université ne possède encore
                        aucune cohorte enregistrée.

                    <?php else: ?>

                        Aucune cohorte n'a encore été
                        enregistrée dans MedTrack.

                    <?php endif; ?>

                </p>

                <a
                    href="/cohorts/create"
                    class="btn btn-primary"
                >
                    <i class="bi bi-plus-circle me-1"></i>
                    Créer une cohorte
                </a>

            </div>

        <?php else: ?>

            <div class="table-responsive">

                <table
                    class="table table-hover align-middle"
                >

                    <thead>

                        <tr>

                            <th>
                                Cohorte
                            </th>

                            <?php if ($isPlatform): ?>

                                <th>
                                    Université
                                </th>

                            <?php endif; ?>

                            <th>
                                Programme
                            </th>

                            <th>
                                Faculté
                            </th>

                            <th>
                                Année académique
                            </th>

                            <th>
                                Statut année
                            </th>

                            <th class="text-end">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php foreach ($cohorts as $cohort): ?>

                        <?php
                        $cohortId =
                            (int) (
                                $cohort['id']
                                ?? 0
                            );

                        $yearStatus =
                            (string) (
                                $cohort[
                                    'academic_year_status'
                                ]
                                ?? ''
                            );

                        $yearBadge = match ($yearStatus) {
                            'OPEN' =>
                                'success',

                            'CLOSED' =>
                                'secondary',

                            'PLANNED' =>
                                'warning',

                            default =>
                                'light',
                        };
                        ?>

                        <tr>

                            <td>

                                <div class="fw-semibold">

                                    <a
                                        href="/cohorts/<?= $cohortId ?>"
                                        class="text-decoration-none"
                                    >
                                        <?= htmlspecialchars(
                                            (string) (
                                                $cohort['name']
                                                ?? ''
                                            )
                                        ) ?>
                                    </a>

                                </div>

                            </td>


                            <?php if ($isPlatform): ?>

                                <td>

                                    <div class="fw-semibold">

                                        <?= htmlspecialchars(
                                            (string) (
                                                $cohort[
                                                    'university_name'
                                                ]
                                                ?? '—'
                                            )
                                        ) ?>

                                    </div>

                                    <?php if (
                                        !empty(
                                            $cohort[
                                                'university_code'
                                            ]
                                        )
                                    ): ?>

                                        <small class="text-muted">

                                            <?= htmlspecialchars(
                                                (string) $cohort[
                                                    'university_code'
                                                ]
                                            ) ?>

                                        </small>

                                    <?php endif; ?>

                                </td>

                            <?php endif; ?>


                            <td>

                                <div class="fw-semibold">

                                    <?= htmlspecialchars(
                                        (string) (
                                            $cohort[
                                                'program_name'
                                            ]
                                            ?? '—'
                                        )
                                    ) ?>

                                </div>

                                <?php if (
                                    !empty(
                                        $cohort[
                                            'program_code'
                                        ]
                                    )
                                ): ?>

                                    <small class="text-muted">

                                        <?= htmlspecialchars(
                                            (string) $cohort[
                                                'program_code'
                                            ]
                                        ) ?>

                                    </small>

                                <?php endif; ?>

                            </td>


                            <td>

                                <?php if (
                                    !empty(
                                        $cohort[
                                            'faculty_name'
                                        ]
                                    )
                                ): ?>

                                    <?= htmlspecialchars(
                                        (string) $cohort[
                                            'faculty_name'
                                        ]
                                    ) ?>

                                <?php else: ?>

                                    <span class="text-muted">
                                        Rattachement direct
                                    </span>

                                <?php endif; ?>

                            </td>


                            <td>

                                <span class="fw-semibold">

                                    <?= htmlspecialchars(
                                        (string) (
                                            $cohort[
                                                'academic_year_label'
                                            ]
                                            ?? '—'
                                        )
                                    ) ?>

                                </span>

                            </td>


                            <td>

                                <span
                                    class="badge text-bg-<?= $yearBadge ?>"
                                >
                                    <?= htmlspecialchars(
                                        $yearStatus !== ''
                                            ? $yearStatus
                                            : '—'
                                    ) ?>
                                </span>

                            </td>


                            <td class="text-end">

                                <div
                                    class="btn-group btn-group-sm"
                                    role="group"
                                >

                                    <a
                                        href="/cohorts/<?= $cohortId ?>"
                                        class="btn btn-outline-primary"
                                        title="Consulter"
                                    >
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <a
                                        href="/cohorts/<?= $cohortId ?>/edit"
                                        class="btn btn-outline-secondary"
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