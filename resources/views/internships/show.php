<?php

declare(strict_types=1);

/**
 * @var array<string, mixed> $internship
 * @var array<int, array<string, mixed>> $rotations
 */

$id =
    (int) (
        $internship['id']
        ?? 0
    );

$status =
    (string) (
        $internship['status']
        ?? 'PLANNED'
    );

[$statusClass, $statusLabel] =
    match ($status) {
        'PLANNED' =>
            [
                'text-bg-secondary',
                'Planifié',
            ],

        'IN_PROGRESS' =>
            [
                'text-bg-primary',
                'En cours',
            ],

        'SUSPENDED' =>
            [
                'text-bg-warning',
                'Suspendu',
            ],

        'COMPLETED' =>
            [
                'text-bg-success',
                'Terminé',
            ],

        'EVALUATED' =>
            [
                'text-bg-info',
                'Évalué',
            ],

        'VALIDATED' =>
            [
                'text-bg-success',
                'Validé',
            ],

        'CERTIFIED' =>
            [
                'text-bg-success',
                'Certifié',
            ],

        'CANCELLED' =>
            [
                'text-bg-danger',
                'Annulé',
            ],

        default =>
            [
                'text-bg-secondary',
                $status,
            ],
    };

$assignmentStatus =
    (string) (
        $internship['assignment_status']
        ?? ''
    );

[$assignmentClass, $assignmentLabel] =
    match ($assignmentStatus) {
        'PROPOSED' =>
            [
                'text-bg-secondary',
                'Proposée',
            ],

        'ACCEPTED' =>
            [
                'text-bg-success',
                'Acceptée',
            ],

        'REJECTED' =>
            [
                'text-bg-danger',
                'Rejetée',
            ],

        'CANCELLED' =>
            [
                'text-bg-secondary',
                'Annulée',
            ],

        default =>
            [
                'text-bg-light',
                $assignmentStatus !== ''
                    ? $assignmentStatus
                    : '—',
            ],
    };

$requestStatus =
    (string) (
        $internship['request_status']
        ?? ''
    );

[$requestClass, $requestLabel] =
    match ($requestStatus) {
        'DRAFT' =>
            [
                'text-bg-secondary',
                'Brouillon',
            ],

        'SUBMITTED' =>
            [
                'text-bg-primary',
                'Soumise',
            ],

        'UNDER_REVIEW' =>
            [
                'text-bg-warning',
                'En examen',
            ],

        'APPROVED' =>
            [
                'text-bg-success',
                'Approuvée',
            ],

        'REJECTED' =>
            [
                'text-bg-danger',
                'Rejetée',
            ],

        'CANCELLED' =>
            [
                'text-bg-secondary',
                'Annulée',
            ],

        default =>
            [
                'text-bg-light',
                $requestStatus !== ''
                    ? $requestStatus
                    : '—',
            ],
    };

$studentName =
    trim(
        implode(
            ' ',
            array_filter(
                [
                    $internship['first_name']
                        ?? null,

                    $internship['middle_name']
                        ?? null,

                    $internship['last_name']
                        ?? null,
                ],
                static fn (
                    mixed $value
                ): bool =>
                    is_string($value)
                    && trim($value) !== ''
            )
        )
    );

$studentName =
    $studentName !== ''
        ? $studentName
        : 'Étudiant';

$rotations =
    is_array(
        $rotations
        ?? null
    )
        ? $rotations
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
                    Internship
                </span>

                <span class="text-muted small">
                    Supervision plateforme
                </span>

            </div>

            <h2 class="fw-bold mb-1">
                <?= htmlspecialchars(
                    $studentName,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </h2>

            <p class="text-muted mb-0">
                Stage #<?= $id ?>
                ·
                <?= htmlspecialchars(
                    (string) (
                        $internship['university_name']
                        ?? ''
                    ),
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </p>

        </div>


        <div class="d-flex gap-2">

            <a
                href="/internships"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Retour
            </a>

        </div>

    </div>


    <div class="row g-4">

        <div class="col-xl-8">

            <!-- Stage -->

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
                            <i class="bi bi-briefcase fs-4"></i>
                        </div>

                        <div>

                            <h5 class="fw-bold mb-1">
                                Informations du stage
                            </h5>

                            <p class="text-muted small mb-0">
                                Contexte académique et hospitalier.
                            </p>

                        </div>

                    </div>


                    <div class="row g-4">

                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Étudiant
                            </div>

                            <div class="fw-semibold">
                                <?= htmlspecialchars(
                                    $studentName,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
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


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Université
                            </div>

                            <div class="fw-semibold">
                                <?= htmlspecialchars(
                                    (string) (
                                        $internship['university_name']
                                        ?? '—'
                                    ),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </div>

                            <div class="text-muted small">
                                <?= htmlspecialchars(
                                    (string) (
                                        $internship['university_code']
                                        ?? ''
                                    ),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Hôpital
                            </div>

                            <div class="fw-semibold">
                                <?= htmlspecialchars(
                                    (string) (
                                        $internship['hospital_name']
                                        ?? '—'
                                    ),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </div>

                            <div class="text-muted small">
                                <?= htmlspecialchars(
                                    (string) (
                                        $internship['hospital_code']
                                        ?? ''
                                    ),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Programme de stage
                            </div>

                            <div class="fw-semibold">
                                <?= htmlspecialchars(
                                    (string) (
                                        $internship['program_name']
                                        ?? '—'
                                    ),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </div>

                            <div class="text-muted small">
                                <?= htmlspecialchars(
                                    (string) (
                                        $internship['program_code']
                                        ?? ''
                                    ),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Période
                            </div>

                            <div class="fw-semibold">
                                <?= htmlspecialchars(
                                    (string) (
                                        $internship['starts_on']
                                        ?? '—'
                                    ),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>

                                <span class="text-muted mx-1">
                                    →
                                </span>

                                <?= htmlspecialchars(
                                    (string) (
                                        $internship['ends_on']
                                        ?? '—'
                                    ),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Début effectif
                            </div>

                            <div class="fw-semibold">
                                <?= htmlspecialchars(
                                    (string) (
                                        $internship['started_at']
                                        ?? '—'
                                    ),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Fin effective
                            </div>

                            <div class="fw-semibold">
                                <?= htmlspecialchars(
                                    (string) (
                                        $internship['completed_at']
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


            <!-- Request & assignment -->

            <div class="card border-0 shadow-sm mb-4">

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
                            <i class="bi bi-diagram-3 fs-4"></i>
                        </div>

                        <div>

                            <h5 class="fw-bold mb-1">
                                Demande & affectation
                            </h5>

                            <p class="text-muted small mb-0">
                                Historique du processus
                                avant la création du stage.
                            </p>

                        </div>

                    </div>


                    <div class="row g-4">

                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Statut de la demande
                            </div>

                            <span
                                class="badge rounded-pill
                                       <?= $requestClass ?>"
                            >
                                <?= htmlspecialchars(
                                    $requestLabel,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </span>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Statut de l’affectation
                            </div>

                            <span
                                class="badge rounded-pill
                                       <?= $assignmentClass ?>"
                            >
                                <?= htmlspecialchars(
                                    $assignmentLabel,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </span>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Demande soumise le
                            </div>

                            <div class="fw-semibold">
                                <?= htmlspecialchars(
                                    (string) (
                                        $internship['submitted_at']
                                        ?? '—'
                                    ),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Affecté le
                            </div>

                            <div class="fw-semibold">
                                <?= htmlspecialchars(
                                    (string) (
                                        $internship['assigned_at']
                                        ?? '—'
                                    ),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </div>

                        </div>


                        <div class="col-12">

                            <div class="text-muted small mb-1">
                                UUID de la demande
                            </div>

                            <code>
                                <?= htmlspecialchars(
                                    (string) (
                                        $internship['request_uuid']
                                        ?? '—'
                                    ),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </code>

                        </div>

                    </div>

                </div>

            </div>


            <!-- Rotations -->

            <div class="card border-0 shadow-sm">

                <div class="card-body p-0">

                    <div class="p-4 border-bottom">

                        <div
                            class="d-flex
                                   justify-content-between
                                   align-items-center
                                   gap-3"
                        >

                            <div>

                                <h5 class="fw-bold mb-1">
                                    Rotations
                                </h5>

                                <p class="text-muted small mb-0">
                                    Parcours du stagiaire dans
                                    les services hospitaliers.
                                </p>

                            </div>


                            <span class="badge rounded-pill text-bg-primary">
                                <?= count($rotations) ?>
                            </span>

                        </div>

                    </div>


                    <div class="table-responsive">

                        <table class="table align-middle mb-0">

                            <thead class="table-light">

                                <tr>
                                    <th class="ps-4">
                                        #
                                    </th>

                                    <th>
                                        Service
                                    </th>

                                    <th>
                                        Période
                                    </th>

                                    <th>
                                        Score final
                                    </th>

                                    <th class="pe-4">
                                        Statut
                                    </th>
                                </tr>

                            </thead>


                            <tbody>

                            <?php if ($rotations === []): ?>

                                <tr>

                                    <td
                                        colspan="5"
                                        class="text-center
                                               py-5 text-muted"
                                    >

                                        <i
                                            class="bi bi-arrow-repeat
                                                   fs-2 d-block mb-2"
                                        ></i>

                                        Aucune rotation enregistrée.

                                    </td>

                                </tr>

                            <?php else: ?>

                                <?php foreach ($rotations as $rotation): ?>

                                    <?php
                                    $rotationStatus =
                                        (string) (
                                            $rotation['status']
                                            ?? 'PLANNED'
                                        );

                                    [$rotationClass, $rotationLabel] =
                                        match ($rotationStatus) {
                                            'PLANNED' =>
                                                [
                                                    'text-bg-secondary',
                                                    'Planifiée',
                                                ],

                                            'IN_PROGRESS' =>
                                                [
                                                    'text-bg-primary',
                                                    'En cours',
                                                ],

                                            'COMPLETED' =>
                                                [
                                                    'text-bg-success',
                                                    'Terminée',
                                                ],

                                            'SUSPENDED' =>
                                                [
                                                    'text-bg-warning',
                                                    'Suspendue',
                                                ],

                                            'CANCELLED' =>
                                                [
                                                    'text-bg-danger',
                                                    'Annulée',
                                                ],

                                            default =>
                                                [
                                                    'text-bg-secondary',
                                                    $rotationStatus,
                                                ],
                                        };
                                    ?>

                                    <tr>

                                        <td class="ps-4 fw-semibold">
                                            <?= (int) (
                                                $rotation['sequence_no']
                                                ?? 0
                                            ) ?>
                                        </td>


                                        <td>

                                            <div class="fw-semibold">
                                                <?= htmlspecialchars(
                                                    (string) (
                                                        $rotation['service_name']
                                                        ?? '—'
                                                    ),
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>
                                            </div>

                                            <div class="text-muted small">
                                                <?= htmlspecialchars(
                                                    (string) (
                                                        $rotation['service_code']
                                                        ?? ''
                                                    ),
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>
                                            </div>

                                        </td>


                                        <td>

                                            <?= htmlspecialchars(
                                                (string) (
                                                    $rotation['starts_on']
                                                    ?? '—'
                                                ),
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>

                                            <span class="text-muted mx-1">
                                                →
                                            </span>

                                            <?= htmlspecialchars(
                                                (string) (
                                                    $rotation['ends_on']
                                                    ?? '—'
                                                ),
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>

                                        </td>


                                        <td>

                                            <?php if (
                                                $rotation['final_score']
                                                !== null
                                                && $rotation['final_score']
                                                !== ''
                                            ): ?>

                                                <span class="fw-semibold">
                                                    <?= htmlspecialchars(
                                                        (string) $rotation[
                                                            'final_score'
                                                        ],
                                                        ENT_QUOTES,
                                                        'UTF-8'
                                                    ) ?>
                                                    /100
                                                </span>

                                            <?php else: ?>

                                                <span class="text-muted">
                                                    —
                                                </span>

                                            <?php endif; ?>

                                        </td>


                                        <td class="pe-4">

                                            <span
                                                class="badge rounded-pill
                                                       <?= $rotationClass ?>"
                                            >
                                                <?= htmlspecialchars(
                                                    $rotationLabel,
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>
                                            </span>

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
                                Informations techniques du stage.
                            </p>

                        </div>

                    </div>


                    <div class="mb-4">

                        <div class="text-muted small mb-2">
                            Statut du stage
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

                        <div class="text-muted small mb-1">
                            Stage ID
                        </div>

                        <div class="fw-semibold">
                            <?= $id ?>
                        </div>

                    </div>


                    <div class="mb-4">

                        <div class="text-muted small mb-1">
                            UUID
                        </div>

                        <code class="small">
                            <?= htmlspecialchars(
                                (string) (
                                    $internship['uuid']
                                    ?? '—'
                                ),
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </code>

                    </div>


                    <div class="mb-4">

                        <div class="text-muted small mb-1">
                            Inscription académique
                        </div>

                        <div class="fw-semibold">
                            #<?= (int) (
                                $internship[
                                    'academic_enrollment_id'
                                ]
                                ?? 0
                            ) ?>
                        </div>

                    </div>


                    <div class="mb-4">

                        <div class="text-muted small mb-1">
                            Affectation
                        </div>

                        <div class="fw-semibold">
                            #<?= (int) (
                                $internship['assignment_id']
                                ?? 0
                            ) ?>
                        </div>

                    </div>


                    <div class="mb-4">

                        <div class="text-muted small mb-1">
                            Nombre de rotations
                        </div>

                        <div class="fw-semibold">
                            <?= count($rotations) ?>
                        </div>

                    </div>


                    <hr>


                    <div class="small text-muted mb-4">

                        <div class="d-flex gap-2 mb-3">

                            <i
                                class="bi bi-eye
                                       text-primary"
                            ></i>

                            <span>
                                Le Super Admin consulte ce stage
                                en mode supervision plateforme.
                            </span>

                        </div>


                        <div class="d-flex gap-2">

                            <i
                                class="bi bi-shield-lock
                                       text-success"
                            ></i>

                            <span>
                                Les opérations métier sont réservées
                                aux workflows Université et Hôpital.
                            </span>

                        </div>

                    </div>


                    <div class="d-grid">

                        <a
                            href="/internships"
                            class="btn btn-light"
                        >
                            <i class="bi bi-arrow-left me-1"></i>
                            Retour aux stages
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>