<?php

declare(strict_types=1);

/**
 * @var array<string, mixed> $metrics
 * @var array<int, array<string, mixed>> $events
 * @var array<int, string> $actions
 * @var array<int, string> $entityTypes
 */

$metrics =
    is_array($metrics ?? null)
        ? $metrics
        : [];

$events =
    is_array($events ?? null)
        ? $events
        : [];

$actions =
    is_array($actions ?? null)
        ? $actions
        : [];

$entityTypes =
    is_array($entityTypes ?? null)
        ? $entityTypes
        : [];

$totalEvents =
    (int) (
        $metrics['total_events']
        ?? 0
    );

$events24h =
    (int) (
        $metrics['events_24h']
        ?? 0
    );

$events7d =
    (int) (
        $metrics['events_7d']
        ?? 0
    );

$distinctActors =
    (int) (
        $metrics['distinct_actors']
        ?? 0
    );

$distinctEntityTypes =
    (int) (
        $metrics['distinct_entity_types']
        ?? 0
    );
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
                    Audit
                </span>

                <span class="text-muted small">
                    Supervision plateforme
                </span>

            </div>

            <h2 class="fw-bold mb-1">
                Journal d’audit
            </h2>

            <p class="text-muted mb-0">
                Traçabilité des actions sensibles,
                modifications et accès effectués
                dans MedTrack.
            </p>

        </div>

    </div>


    <!-- ============================================================
         Metrics
         ============================================================ -->

    <div class="row g-3 mb-4">

        <div class="col-md-6 col-xl">

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
                            <i class="bi bi-journal-text fs-4"></i>
                        </div>

                        <div>

                            <div class="text-muted small">
                                Événements
                            </div>

                            <div class="fs-4 fw-bold">
                                <?= $totalEvents ?>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-6 col-xl">

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
                            <i class="bi bi-clock-history fs-4"></i>
                        </div>

                        <div>

                            <div class="text-muted small">
                                Dernières 24h
                            </div>

                            <div class="fs-4 fw-bold">
                                <?= $events24h ?>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-6 col-xl">

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
                            <i class="bi bi-calendar-week fs-4"></i>
                        </div>

                        <div>

                            <div class="text-muted small">
                                7 derniers jours
                            </div>

                            <div class="fs-4 fw-bold">
                                <?= $events7d ?>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-6 col-xl">

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
                            <i class="bi bi-people fs-4"></i>
                        </div>

                        <div>

                            <div class="text-muted small">
                                Acteurs
                            </div>

                            <div class="fs-4 fw-bold">
                                <?= $distinctActors ?>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-6 col-xl">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-4">

                    <div class="d-flex align-items-center gap-3">

                        <div
                            class="rounded-circle
                                   bg-secondary-subtle
                                   text-secondary
                                   d-flex align-items-center
                                   justify-content-center"
                            style="width:48px;height:48px;"
                        >
                            <i class="bi bi-diagram-3 fs-4"></i>
                        </div>

                        <div>

                            <div class="text-muted small">
                                Types d’entités
                            </div>

                            <div class="fs-4 fw-bold">
                                <?= $distinctEntityTypes ?>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- ============================================================
         Filters
         ============================================================ -->

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body p-4">

            <div class="row g-3 align-items-end">

                <div class="col-lg-4">

                    <label
                        for="auditSearch"
                        class="form-label"
                    >
                        Recherche
                    </label>

                    <input
                        type="search"
                        id="auditSearch"
                        class="form-control"
                        placeholder="Action, entité, acteur, organisation…"
                    >

                </div>


                <div class="col-md-6 col-lg-3">

                    <label
                        for="auditActionFilter"
                        class="form-label"
                    >
                        Action
                    </label>

                    <select
                        id="auditActionFilter"
                        class="form-select"
                    >
                        <option value="">
                            Toutes les actions
                        </option>

                        <?php foreach ($actions as $action): ?>

                            <option
                                value="<?= htmlspecialchars(
                                    (string) $action,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>"
                            >
                                <?= htmlspecialchars(
                                    (string) $action,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>


                <div class="col-md-6 col-lg-3">

                    <label
                        for="auditEntityFilter"
                        class="form-label"
                    >
                        Type d’entité
                    </label>

                    <select
                        id="auditEntityFilter"
                        class="form-select"
                    >
                        <option value="">
                            Toutes les entités
                        </option>

                        <?php foreach ($entityTypes as $entityType): ?>

                            <option
                                value="<?= htmlspecialchars(
                                    (string) $entityType,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>"
                            >
                                <?= htmlspecialchars(
                                    (string) $entityType,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>


                <div class="col-lg-2 d-grid">

                    <button
                        type="button"
                        class="btn btn-outline-secondary"
                        id="auditResetFilters"
                    >
                        <i class="bi bi-arrow-counterclockwise me-1"></i>
                        Réinitialiser
                    </button>

                </div>

            </div>

        </div>

    </div>


    <!-- ============================================================
         Events
         ============================================================ -->

    <div class="card border-0 shadow-sm">

        <div class="card-body p-0">

            <div
                class="d-flex flex-column flex-lg-row
                       justify-content-between
                       align-items-lg-center
                       gap-3 p-4 border-bottom"
            >

                <div>

                    <h5 class="fw-bold mb-1">
                        Événements récents
                    </h5>

                    <p class="text-muted small mb-0">
                        Jusqu’aux 100 derniers événements
                        enregistrés.
                    </p>

                </div>

                <span
                    class="badge rounded-pill text-bg-light"
                    id="auditVisibleCount"
                >
                    <?= count($events) ?> événement(s)
                </span>

            </div>


            <div class="table-responsive">

                <table
                    class="table align-middle mb-0"
                    id="auditTable"
                >

                    <thead class="table-light">

                        <tr>

                            <th class="ps-4">
                                Date
                            </th>

                            <th>
                                Action
                            </th>

                            <th>
                                Entité
                            </th>

                            <th>
                                Acteur
                            </th>

                            <th>
                                Organisation
                            </th>

                            <th>
                                IP
                            </th>

                            <th class="text-end pe-4">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php if ($events === []): ?>

                        <tr>

                            <td
                                colspan="7"
                                class="text-center py-5"
                            >

                                <div class="text-muted">

                                    <i
                                        class="bi bi-journal-x
                                               fs-1 d-block mb-3"
                                    ></i>

                                    <strong class="d-block mb-1">
                                        Aucun événement d’audit
                                    </strong>

                                    <span class="small">
                                        Les événements sensibles
                                        apparaîtront ici.
                                    </span>

                                </div>

                            </td>

                        </tr>

                    <?php else: ?>

                        <?php foreach ($events as $event): ?>

                            <?php
                            $auditId =
                                (int) (
                                    $event['id']
                                    ?? 0
                                );

                            $action =
                                (string) (
                                    $event['action']
                                    ?? ''
                                );

                            $entityType =
                                (string) (
                                    $event['entity_type']
                                    ?? ''
                                );

                            $entityId =
                                (string) (
                                    $event['entity_id']
                                    ?? ''
                                );

                            $actorEmail =
                                trim(
                                    (string) (
                                        $event['actor_email']
                                        ?? ''
                                    )
                                );

                            $actorPhone =
                                trim(
                                    (string) (
                                        $event['actor_phone']
                                        ?? ''
                                    )
                                );

                            $actor =
                                $actorEmail !== ''
                                    ? $actorEmail
                                    : (
                                        $actorPhone !== ''
                                            ? $actorPhone
                                            : (
                                                !empty(
                                                    $event['actor_user_id']
                                                )
                                                    ? 'Utilisateur #'
                                                        . (int) $event[
                                                            'actor_user_id'
                                                        ]
                                                    : 'Système'
                                            )
                                    );

                            $organization =
                                trim(
                                    (string) (
                                        $event['organization_name']
                                        ?? ''
                                    )
                                );

                            $searchable =
                                strtolower(
                                    implode(
                                        ' ',
                                        [
                                            $action,
                                            $entityType,
                                            $entityId,
                                            $actor,
                                            $organization,
                                            (string) (
                                                $event[
                                                    'organization_code'
                                                ]
                                                ?? ''
                                            ),
                                            (string) (
                                                $event['ip_address']
                                                ?? ''
                                            ),
                                        ]
                                    )
                                );
                            ?>

                            <tr
                                class="audit-event-row"
                                data-search="<?= htmlspecialchars(
                                    $searchable,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>"
                                data-action="<?= htmlspecialchars(
                                    $action,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>"
                                data-entity="<?= htmlspecialchars(
                                    $entityType,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>"
                            >

                                <td class="ps-4">

                                    <div class="fw-semibold">

                                        <?= htmlspecialchars(
                                            (string) (
                                                $event['created_at']
                                                ?? '—'
                                            ),
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>

                                    </div>

                                    <div class="text-muted small">
                                        #<?= $auditId ?>
                                    </div>

                                </td>


                                <td>

                                    <span
                                        class="badge
                                               text-bg-primary"
                                    >
                                        <?= htmlspecialchars(
                                            $action,
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </span>

                                </td>


                                <td>

                                    <div class="fw-semibold">

                                        <?= htmlspecialchars(
                                            $entityType,
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>

                                    </div>

                                    <code class="small">

                                        <?= htmlspecialchars(
                                            $entityId,
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>

                                    </code>

                                </td>


                                <td>

                                    <div class="fw-semibold">

                                        <?= htmlspecialchars(
                                            $actor,
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>

                                    </div>

                                    <?php if (
                                        !empty(
                                            $event[
                                                'actor_membership_id'
                                            ]
                                        )
                                    ): ?>

                                        <div class="text-muted small">
                                            Membership #
                                            <?= (int) $event[
                                                'actor_membership_id'
                                            ] ?>
                                        </div>

                                    <?php endif; ?>

                                </td>


                                <td>

                                    <?php if ($organization !== ''): ?>

                                        <div class="fw-semibold">

                                            <?= htmlspecialchars(
                                                $organization,
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>

                                        </div>

                                        <div class="text-muted small">

                                            <?= htmlspecialchars(
                                                (string) (
                                                    $event[
                                                        'organization_code'
                                                    ]
                                                    ?? ''
                                                ),
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>

                                        </div>

                                    <?php else: ?>

                                        <span class="text-muted">
                                            Plateforme
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <td>

                                    <code>

                                        <?= htmlspecialchars(
                                            (string) (
                                                $event['ip_address']
                                                ?? '—'
                                            ),
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>

                                    </code>

                                </td>


                                <td class="text-end pe-4">

                                    <a
                                        href="/audit/<?= $auditId ?>"
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

</div>