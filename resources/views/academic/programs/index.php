<?php

declare(strict_types=1);

$programs = $programs ?? [];

ob_start();
?>

<div class="container-fluid px-0">

    <!-- Header -->
    <div
        class="d-flex flex-column flex-md-row
               justify-content-between align-items-md-center
               gap-3 mb-4"
    >
        <div>
            <h4 class="mb-1 fw-bold">
                Programmes académiques
            </h4>

            <p class="text-muted mb-0">
                Gérez les programmes académiques proposés
                par les universités partenaires.
            </p>
        </div>

        <div>
            <a
                href="/academic-programs/create"
                class="btn btn-primary"
            >
                <i class="bi bi-plus-lg me-1"></i>
                Nouveau programme
            </a>
        </div>
    </div>


    <!-- Statistiques -->
    <div class="row g-3 mb-4">

        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <div
                        class="d-flex align-items-center
                               justify-content-between"
                    >
                        <div>
                            <div class="text-muted small mb-1">
                                Programmes
                            </div>

                            <h3 class="fw-bold mb-0">
                                <?= count($programs) ?>
                            </h3>
                        </div>

                        <div
                            class="rounded-circle bg-primary-subtle
                                   text-primary d-flex
                                   align-items-center
                                   justify-content-center"
                            style="width: 48px; height: 48px;"
                        >
                            <i class="bi bi-journal-bookmark fs-4"></i>
                        </div>
                    </div>

                </div>
            </div>
        </div>


        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <div
                        class="d-flex align-items-center
                               justify-content-between"
                    >
                        <div>
                            <div class="text-muted small mb-1">
                                Programmes actifs
                            </div>

                            <h3 class="fw-bold mb-0">

                                <?php
                                $activePrograms = array_filter(
                                    $programs,
                                    static fn (array $program): bool =>
                                        ($program['status'] ?? null)
                                        === 'ACTIVE'
                                );
                                ?>

                                <?= count($activePrograms) ?>

                            </h3>
                        </div>

                        <div
                            class="rounded-circle bg-success-subtle
                                   text-success d-flex
                                   align-items-center
                                   justify-content-center"
                            style="width: 48px; height: 48px;"
                        >
                            <i class="bi bi-check-circle fs-4"></i>
                        </div>
                    </div>

                </div>
            </div>
        </div>


        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <div
                        class="d-flex align-items-center
                               justify-content-between"
                    >
                        <div>
                            <div class="text-muted small mb-1">
                                Programmes inactifs
                            </div>

                            <h3 class="fw-bold mb-0">

                                <?php
                                $inactivePrograms = array_filter(
                                    $programs,
                                    static fn (array $program): bool =>
                                        ($program['status'] ?? null)
                                        === 'INACTIVE'
                                );
                                ?>

                                <?= count($inactivePrograms) ?>

                            </h3>
                        </div>

                        <div
                            class="rounded-circle bg-secondary-subtle
                                   text-secondary d-flex
                                   align-items-center
                                   justify-content-center"
                            style="width: 48px; height: 48px;"
                        >
                            <i class="bi bi-pause-circle fs-4"></i>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>


    <!-- Liste -->
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-0 py-3">

            <div
                class="d-flex flex-column flex-md-row
                       justify-content-between
                       align-items-md-center gap-3"
            >
                <div>
                    <h5 class="mb-1 fw-semibold">
                        Liste des programmes
                    </h5>

                    <small class="text-muted">
                        <?= count($programs) ?>
                        programme<?= count($programs) > 1 ? 's' : '' ?>
                        enregistré<?= count($programs) > 1 ? 's' : '' ?>
                    </small>
                </div>

                <div style="max-width: 320px; width: 100%;">
                    <div class="input-group">

                        <span class="input-group-text bg-white">
                            <i class="bi bi-search"></i>
                        </span>

                        <input
                            type="search"
                            id="programSearch"
                            class="form-control"
                            placeholder="Rechercher..."
                            autocomplete="off"
                        >

                    </div>
                </div>
            </div>

        </div>


        <div class="card-body p-0">

            <?php if ($programs === []): ?>

                <!-- Empty state -->
                <div class="text-center py-5 px-3">

                    <div
                        class="rounded-circle bg-primary-subtle
                               text-primary d-inline-flex
                               align-items-center
                               justify-content-center mb-3"
                        style="width: 72px; height: 72px;"
                    >
                        <i class="bi bi-journal-plus fs-2"></i>
                    </div>

                    <h5 class="fw-semibold">
                        Aucun programme académique
                    </h5>

                    <p class="text-muted mb-4">
                        Aucun programme académique n'a encore
                        été enregistré dans MedTrack.
                    </p>

                    <a
                        href="/academic-programs/create"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-plus-lg me-1"></i>
                        Créer le premier programme
                    </a>

                </div>

            <?php else: ?>

                <div class="table-responsive">

                    <table
                        class="table table-hover
                               align-middle mb-0"
                        id="programsTable"
                    >

                        <thead class="table-light">

                            <tr>
                                <th class="ps-4">
                                    Programme
                                </th>

                                <th>
                                    Université
                                </th>

                                <th>
                                    Faculté
                                </th>

                                <th>
                                    Discipline
                                </th>

                                <th>
                                    Durée
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

                        <?php foreach ($programs as $program): ?>

                            <?php
                            $id = (int) (
                                $program['id']
                                ?? 0
                            );

                            $name = htmlspecialchars(
                                (string) (
                                    $program['name']
                                    ?? ''
                                )
                            );

                            $code = htmlspecialchars(
                                (string) (
                                    $program['code']
                                    ?? ''
                                )
                            );

                            $universityName = htmlspecialchars(
                                (string) (
                                    $program['university_name']
                                    ?? '—'
                                )
                            );

                            $facultyName = htmlspecialchars(
                                (string) (
                                    $program['faculty_name']
                                    ?? '—'
                                )
                            );

                            $disciplineCode = htmlspecialchars(
                                (string) (
                                    $program['discipline_code']
                                    ?? '—'
                                )
                            );

                            $durationYears =
                                $program['duration_years']
                                ?? null;

                            $status =
                                $program['status']
                                ?? 'INACTIVE';
                            ?>

                            <tr class="program-row">

                                <td class="ps-4">

                                    <div class="d-flex align-items-center">

                                        <div
                                            class="rounded-circle
                                                   bg-primary-subtle
                                                   text-primary
                                                   d-flex
                                                   align-items-center
                                                   justify-content-center
                                                   flex-shrink-0 me-3"
                                            style="
                                                width: 42px;
                                                height: 42px;
                                            "
                                        >
                                            <i class="bi bi-journal-medical"></i>
                                        </div>

                                        <div>
                                            <a
                                                href="/academic-programs/<?= $id ?>"
                                                class="fw-semibold
                                                       text-decoration-none"
                                            >
                                                <?= $name ?>
                                            </a>

                                            <div class="small text-muted">
                                                <?= $code ?>
                                            </div>
                                        </div>

                                    </div>

                                </td>


                                <td>
                                    <?= $universityName ?>
                                </td>


                                <td>
                                    <?= $facultyName ?>
                                </td>


                                <td>
                                    <span
                                        class="badge
                                               text-bg-light
                                               border"
                                    >
                                        <?= $disciplineCode ?>
                                    </span>
                                </td>


                                <td>

                                    <?php if ($durationYears !== null): ?>

                                        <?= (int) $durationYears ?>
                                        an<?= (int) $durationYears > 1 ? 's' : '' ?>

                                    <?php else: ?>

                                        <span class="text-muted">
                                            —
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <td>

                                    <?php if ($status === 'ACTIVE'): ?>

                                        <span
                                            class="badge
                                                   bg-success-subtle
                                                   text-success"
                                        >
                                            <i
                                                class="bi bi-check-circle
                                                       me-1"
                                            ></i>
                                            Actif
                                        </span>

                                    <?php else: ?>

                                        <span
                                            class="badge
                                                   bg-secondary-subtle
                                                   text-secondary"
                                        >
                                            <i
                                                class="bi bi-pause-circle
                                                       me-1"
                                            ></i>
                                            Inactif
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <td class="text-end pe-4">

                                    <div class="btn-group">

                                        <a
                                            href="/academic-programs/<?= $id ?>"
                                            class="btn btn-sm
                                                   btn-outline-primary"
                                            title="Consulter"
                                        >
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <a
                                            href="/academic-programs/<?= $id ?>/edit"
                                            class="btn btn-sm
                                                   btn-outline-secondary"
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


<script>
document.addEventListener('DOMContentLoaded', () => {
    const searchInput =
        document.getElementById('programSearch');

    const rows =
        document.querySelectorAll('.program-row');

    if (!searchInput || rows.length === 0) {
        return;
    }

    searchInput.addEventListener('input', () => {
        const search =
            searchInput.value
                .trim()
                .toLowerCase();

        rows.forEach((row) => {
            const content =
                row.textContent
                    .toLowerCase();

            row.classList.toggle(
                'd-none',
                search !== ''
                && !content.includes(search)
            );
        });
    });
});
</script>
