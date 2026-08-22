<?php

declare(strict_types=1);

/**
 * @var array<string, mixed> $metrics
 * @var array<string, mixed> $requestMetrics
 * @var array<int, array<string, mixed>> $internships
 * @var array<int, array<string, mixed>> $recentRequests
 */

$metrics =
    is_array($metrics ?? null)
        ? $metrics
        : [];

$requestMetrics =
    is_array($requestMetrics ?? null)
        ? $requestMetrics
        : [];

$internships =
    is_array($internships ?? null)
        ? $internships
        : [];

$recentRequests =
    is_array($recentRequests ?? null)
        ? $recentRequests
        : [];

$total =
    (int) ($metrics['total'] ?? 0);

$inProgress =
    (int) ($metrics['in_progress'] ?? 0);

$completed =
    (int) ($metrics['completed'] ?? 0);

$certified =
    (int) ($metrics['certified'] ?? 0);

$totalRequests =
    (int) ($requestMetrics['total'] ?? 0);

$pendingRequests =
    (int) ($requestMetrics['submitted'] ?? 0)
    + (int) ($requestMetrics['under_review'] ?? 0);
?>

<div class="container-fluid px-0">

    <!-- ============================================================
         Header
         ============================================================ -->

    <div
        class="d-flex flex-column flex-lg-row
               justify-content-between align-items-lg-center
               gap-3 mb-4"
    >

        <div>

            <div class="d-flex align-items-center gap-2 mb-2">

                <span class="badge rounded-pill text-bg-primary">
                    Internships
                </span>

                <span class="text-muted small">
                    Supervision plateforme
                </span>

            </div>

            <h2 class="fw-bold mb-1">
                Stages
            </h2>

            <p class="text-muted mb-0">
                Supervision globale des demandes,
                affectations, stages et rotations MedTrack.
            </p>

        </div>

    </div>


    <!-- ============================================================
         Main metrics
         ============================================================ -->

    <div class="row g-3 mb-4">

        <!-- Total -->

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
                            <i class="bi bi-briefcase fs-4"></i>
                        </div>

                        <div>

                            <div class="text-muted small">
                                Total des stages
                            </div>

                            <div class="fs-4 fw-bold">
                                <?= $total ?>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- In progress -->

        <div class="col-md-6 col-xl-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-4">

                    <div class="d-flex align-items-center gap-3">

                        <div
                            class="rounded-circle
                                   bg-info-subtle
                                   text-info
                                   d-flex align-items-center
                                   justify-content-center"
                            style="width:48px;height:48px;"
                        >
                            <i class="bi bi-activity fs-4"></i>
                        </div>

                        <div>

                            <div class="text-muted small">
                                En cours
                            </div>

                            <div class="fs-4 fw-bold">
                                <?= $inProgress ?>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- Completed -->

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
                                Terminés
                            </div>

                            <div class="fs-4 fw-bold">
                                <?= $completed ?>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- Certified -->

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
                            <i class="bi bi-award fs-4"></i>
                        </div>

                        <div>

                            <div class="text-muted small">
                                Certifiés
                            </div>

                            <div class="fs-4 fw-bold">
                                <?= $certified ?>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- ============================================================
         Request overview
         ============================================================ -->

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body p-4">

            <div
                class="d-flex flex-column flex-md-row
                       justify-content-between
                       align-items-md-center gap-3"
            >

                <div>

                    <h5 class="fw-bold mb-1">
                        Demandes de stage
                    </h5>

                    <p class="text-muted small mb-0">
                        État global du processus de candidature
                        et de validation.
                    </p>

                </div>


                <div class="d-flex flex-wrap gap-2">

                    <span class="badge text-bg-light p-2">

                        Total :
                        <strong>
                            <?= $totalRequests ?>
                        </strong>

                    </span>

                    <span class="badge text-bg-warning p-2">

                        En attente :
                        <strong>
                            <?= $pendingRequests ?>
                        </strong>

                    </span>

                    <span class="badge text-bg-success p-2">

                        Approuvées :
                        <strong>
                            <?= (int) (
                                $requestMetrics['approved']
                                ?? 0
                            ) ?>
                        </strong>

                    </span>

                    <span class="badge text-bg-danger p-2">

                        Rejetées :
                        <strong>
                            <?= (int) (
                                $requestMetrics['rejected']
                                ?? 0
                            ) ?>
                        </strong>

                    </span>

                </div>

            </div>

        </div>

    </div>


    <!-- ============================================================
         Internships
         ============================================================ -->

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body p-0">

            <div class="p-4 border-bottom">

                <h5 class="fw-bold mb-1">
                    Registre des stages
                </h5>

                <p class="text-muted small mb-0">
                    Tous les stages enregistrés
                    sur la plateforme MedTrack.
                </p>

            </div>


            <div class="table-responsive">

                <table class="table align-middle mb-0">

                    <thead class="table-light">

                        <tr>
                            <th class="ps-4">
                                Étudiant
                            </th>

                            <th>
                                Université
                            </th>

                            <th>
                                Hôpital
                            </th>

                            <th>
                                Programme
                            </th>

                            <th>
                                Période
                            </th>

                            <th>
                                Rotations
                            </th>

                            <th>
                                Statut
                            </th>

                            <th class="text-end pe-4">
                                Actions
                            </th>
                        </tr>

                    </thead>


                    <tbody>

                    <?php if ($internships === []): ?>

                        <tr>

                            <td
                                colspan="8"
                                class="text-center py-5"
                            >

                                <div class="text-muted">

                                    <i
                                        class="bi bi-briefcase
                                               fs-1 d-block mb-3"
                                    ></i>

                                    <strong class="d-block mb-1">
                                        Aucun stage enregistré
                                    </strong>

                                    <span class="small">
                                        Les stages apparaîtront ici
                                        dès leur création par les
                                        workflows opérationnels.
                                    </span>

                                </div>

                            </td>

                        </tr>

                    <?php else: ?>

                        <?php foreach ($internships as $internship): ?>

                            <?php
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

                            $startsOn =
                                (string) (
                                    $internship['starts_on']
                                    ?? ''
                                );

                            $endsOn =
                                (string) (
                                    $internship['ends_on']
                                    ?? ''
                                );
                            ?>

                            <tr>

                                <!-- Student -->

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
                                            <i class="bi bi-person"></i>
                                        </div>

                                        <div>

                                            <a
                                                href="/internships/<?= $id ?>"
                                                class="fw-semibold
                                                       text-decoration-none"
                                            >
                                                <?= htmlspecialchars(
                                                    $studentName,
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>
                                            </a>

                                            <div class="text-muted small">
                                                Stage #<?= $id ?>
                                            </div>

                                        </div>

                                    </div>

                                </td>


                                <!-- University -->

                                <td>

                                    <?= htmlspecialchars(
                                        (string) (
                                            $internship['university_name']
                                            ?? '—'
                                        ),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                </td>


                                <!-- Hospital -->

                                <td>

                                    <?= htmlspecialchars(
                                        (string) (
                                            $internship['hospital_name']
                                            ?? '—'
                                        ),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                </td>


                                <!-- Program -->

                                <td>

                                    <div>
                                        <?= htmlspecialchars(
                                            (string) (
                                                $internship['program_name']
                                                ?? '—'
                                            ),
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </div>

                                    <?php if (
                                        !empty(
                                            $internship['program_code']
                                        )
                                    ): ?>

                                        <small class="text-muted">

                                            <?= htmlspecialchars(
                                                (string) $internship[
                                                    'program_code'
                                                ],
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>

                                        </small>

                                    <?php endif; ?>

                                </td>


                                <!-- Period -->

                                <td>

                                    <?php if ($startsOn !== ''): ?>

                                        <div class="small">

                                            <i
                                                class="bi bi-calendar-event
                                                       text-muted me-1"
                                            ></i>

                                            <?= htmlspecialchars(
                                                $startsOn,
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>

                                        </div>

                                    <?php endif; ?>


                                    <?php if ($endsOn !== ''): ?>

                                        <div class="small text-muted">

                                            au

                                            <?= htmlspecialchars(
                                                $endsOn,
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>

                                        </div>

                                    <?php endif; ?>

                                </td>


                                <!-- Rotations -->

                                <td>

                                    <span class="badge text-bg-light">

                                        <?= (int) (
                                            $internship['rotation_count']
                                            ?? 0
                                        ) ?>

                                    </span>

                                </td>


                                <!-- Status -->

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


                                <!-- Actions -->

                                <td class="text-end pe-4">

                                    <a
                                        href="/internships/<?= $id ?>"
                                        class="btn btn-sm
                                               btn-outline-primary"
                                        title="Consulter"
                                    >
                                        <i class="bi bi-eye"></i>
                                    </a>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    <!-- ============================================================
         Recent requests
         ============================================================ -->

    <div class="card border-0 shadow-sm">

        <div class="card-body p-0">

            <div class="p-4 border-bottom">

                <h5 class="fw-bold mb-1">
                    Demandes récentes
                </h5>

                <p class="text-muted small mb-0">
                    Dernières demandes de stage
                    enregistrées sur MedTrack.
                </p>

            </div>


            <div class="table-responsive">

                <table class="table align-middle mb-0">

                    <thead class="table-light">

                        <tr>
                            <th class="ps-4">
                                Étudiant
                            </th>

                            <th>
                                Université
                            </th>

                            <th>
                                Hôpital souhaité
                            </th>

                            <th>
                                Date
                            </th>

                            <th class="pe-4">
                                Statut
                            </th>
                        </tr>

                    </thead>


                    <tbody>

                    <?php if ($recentRequests === []): ?>

                        <tr>

                            <td
                                colspan="5"
                                class="text-center py-4 text-muted"
                            >
                                Aucune demande récente.
                            </td>

                        </tr>

                    <?php else: ?>

                        <?php foreach ($recentRequests as $request): ?>

                            <?php
                            $requestStatus =
                                (string) (
                                    $request['status']
                                    ?? 'SUBMITTED'
                                );

                            [$requestClass, $requestLabel] =
                                match ($requestStatus) {
                                    'SUBMITTED' =>
                                        [
                                            'text-bg-secondary',
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

                                    default =>
                                        [
                                            'text-bg-secondary',
                                            $requestStatus,
                                        ],
                                };

                            $requestStudent =
                                trim(
                                    implode(
                                        ' ',
                                        array_filter(
                                            [
                                                $request['first_name']
                                                    ?? null,

                                                $request['middle_name']
                                                    ?? null,

                                                $request['last_name']
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
                            ?>

                            <tr>

                                <td class="ps-4 fw-semibold">

                                    <?= htmlspecialchars(
                                        $requestStudent !== ''
                                            ? $requestStudent
                                            : 'Étudiant',
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        (string) (
                                            $request['university_name']
                                            ?? '—'
                                        ),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        (string) (
                                            $request[
                                                'preferred_hospital_name'
                                            ]
                                            ?? 'Non spécifié'
                                        ),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        (string) (
                                            $request['submitted_at']
                                            ?? $request['created_at']
                                            ?? '—'
                                        ),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                </td>


                                <td class="pe-4">

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