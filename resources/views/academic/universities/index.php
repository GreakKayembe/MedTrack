<?php

declare(strict_types=1);

/**
 * @var array $universities
 */

$universities = $universities ?? [];

$totalUniversities = count($universities);

$activeUniversities = count(
    array_filter(
        $universities,
        static fn (array $university): bool =>
            ($university['status'] ?? null) === 'ACTIVE'
    )
);

$accreditedUniversities = count(
    array_filter(
        $universities,
        static fn (array $university): bool =>
            ($university['accreditation_status'] ?? null) === 'ACCREDITED'
    )
);

$pendingUniversities = count(
    array_filter(
        $universities,
        static fn (array $university): bool =>
            ($university['accreditation_status'] ?? null) === 'PENDING'
    )
);
?>

<div class="container-fluid px-0">

    <!-- =========================================================
         Header
         ========================================================= -->

    <div class="d-flex flex-column flex-lg-row
                justify-content-between align-items-lg-center
                gap-3 mb-4">

        <div>
            <div class="d-flex align-items-center gap-2 mb-2">

                <span class="badge rounded-pill text-bg-primary">
                    Academic
                </span>

                <span class="text-muted small">
                    Gestion institutionnelle
                </span>

            </div>

            <h2 class="fw-bold mb-1">
                Universités
            </h2>

            <p class="text-muted mb-0">
                Gérez les établissements universitaires
                enregistrés dans MedTrack.
            </p>
        </div>

        <div>
            <a
                href="/universities/create"
                class="btn btn-primary d-inline-flex
                       align-items-center gap-2"
            >
                <i class="bi bi-plus-lg"></i>

                Nouvelle université
            </a>
        </div>

    </div>


    <!-- =========================================================
         Statistics
         ========================================================= -->

    <div class="row g-3 mb-4">

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex
                                justify-content-between
                                align-items-start">

                        <div>
                            <p class="text-muted small mb-1">
                                Universités
                            </p>

                            <h3 class="fw-bold mb-0">
                                <?= $totalUniversities ?>
                            </h3>
                        </div>

                        <div class="fs-3 text-primary">
                            <i class="bi bi-buildings"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex
                                justify-content-between
                                align-items-start">

                        <div>
                            <p class="text-muted small mb-1">
                                Actives
                            </p>

                            <h3 class="fw-bold mb-0">
                                <?= $activeUniversities ?>
                            </h3>
                        </div>

                        <div class="fs-3 text-success">
                            <i class="bi bi-check-circle"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex
                                justify-content-between
                                align-items-start">

                        <div>
                            <p class="text-muted small mb-1">
                                Accréditées
                            </p>

                            <h3 class="fw-bold mb-0">
                                <?= $accreditedUniversities ?>
                            </h3>
                        </div>

                        <div class="fs-3 text-info">
                            <i class="bi bi-patch-check"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex
                                justify-content-between
                                align-items-start">

                        <div>
                            <p class="text-muted small mb-1">
                                En attente
                            </p>

                            <h3 class="fw-bold mb-0">
                                <?= $pendingUniversities ?>
                            </h3>
                        </div>

                        <div class="fs-3 text-warning">
                            <i class="bi bi-hourglass-split"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- =========================================================
         Universities table
         ========================================================= -->

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <div class="d-flex flex-column flex-lg-row
                        justify-content-between
                        align-items-lg-center
                        gap-3 py-3">

                <div>

                    <h5 class="fw-semibold mb-1">
                        Liste des universités
                    </h5>

                    <span class="text-muted small">
                        <?= $totalUniversities ?>
                        établissement<?= $totalUniversities !== 1 ? 's' : '' ?>
                        enregistré<?= $totalUniversities !== 1 ? 's' : '' ?>
                    </span>

                </div>


                <div style="max-width: 340px; width: 100%;">

                    <div class="input-group">

                        <span class="input-group-text bg-white">
                            <i class="bi bi-search"></i>
                        </span>

                        <input
                            type="search"
                            id="universitySearch"
                            class="form-control"
                            placeholder="Rechercher une université..."
                            autocomplete="off"
                        >

                    </div>

                </div>

            </div>


            <?php if ($universities === []): ?>

                <div class="text-center py-5">

                    <div class="display-5 text-muted mb-3">
                        <i class="bi bi-building-add"></i>
                    </div>

                    <h5 class="fw-semibold">
                        Aucune université
                    </h5>

                    <p class="text-muted mb-4">
                        Aucun établissement universitaire
                        n'est encore enregistré dans MedTrack.
                    </p>

                    <a
                        href="/universities/create"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-plus-lg me-1"></i>
                        Ajouter une université
                    </a>

                </div>

            <?php else: ?>

                <div class="table-responsive">

                    <table
                        class="table table-hover align-middle mb-0"
                        id="universitiesTable"
                    >

                        <thead class="table-light">

                            <tr>
                                <th>Université</th>
                                <th>Code</th>
                                <th>Localisation</th>
                                <th>Type</th>
                                <th>Accréditation</th>
                                <th>Statut</th>
                                <th class="text-end">
                                    Actions
                                </th>
                            </tr>

                        </thead>

                        <tbody>

                        <?php foreach ($universities as $university): ?>

                            <?php
                            $status =
                                $university['status']
                                ?? 'INACTIVE';

                            $accreditation =
                                $university['accreditation_status']
                                ?? 'PENDING';

                            $statusClass = match ($status) {
                                'ACTIVE' => 'success',
                                'SUSPENDED' => 'warning',
                                default => 'secondary',
                            };

                            $accreditationClass = match ($accreditation) {
                                'ACCREDITED' => 'success',
                                'SUSPENDED' => 'warning',
                                'REVOKED' => 'danger',
                                default => 'secondary',
                            };

                            $id = (int) (
                                $university['id']
                                ?? 0
                            );
                            ?>

                            <tr class="university-row">

                                <td>

                                    <div class="d-flex
                                                align-items-center
                                                gap-3">

                                        <div
                                            class="rounded-circle
                                                   bg-primary-subtle
                                                   text-primary
                                                   d-flex
                                                   align-items-center
                                                   justify-content-center
                                                   flex-shrink-0"
                                            style="
                                                width: 42px;
                                                height: 42px;
                                            "
                                        >
                                            <i class="bi bi-mortarboard"></i>
                                        </div>

                                        <div>

                                            <div class="fw-semibold university-name">
                                                <?= htmlspecialchars(
                                                    (string) $university['name']
                                                ) ?>
                                            </div>

                                            <?php if (
                                                !empty($university['email'])
                                            ): ?>

                                                <div class="small text-muted">
                                                    <?= htmlspecialchars(
                                                        (string) $university['email']
                                                    ) ?>
                                                </div>

                                            <?php endif; ?>

                                        </div>

                                    </div>

                                </td>


                                <td>
                                    <span class="badge text-bg-light">
                                        <?= htmlspecialchars(
                                            (string) $university['code']
                                        ) ?>
                                    </span>
                                </td>


                                <td>

                                    <?php
                                    $location = array_filter(
                                        [
                                            $university['city'] ?? null,
                                            $university['province'] ?? null,
                                        ]
                                    );
                                    ?>

                                    <?php if ($location !== []): ?>

                                        <i class="bi bi-geo-alt me-1 text-muted"></i>

                                        <?= htmlspecialchars(
                                            implode(
                                                ', ',
                                                $location
                                            )
                                        ) ?>

                                    <?php else: ?>

                                        <span class="text-muted">
                                            Non renseignée
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <td>
                                    <?= htmlspecialchars(
                                        (string) (
                                            $university['university_type']
                                            ?? 'Non défini'
                                        )
                                    ) ?>
                                </td>


                                <td>

                                    <span
                                        class="badge text-bg-<?= $accreditationClass ?>"
                                    >
                                        <?= htmlspecialchars(
                                            $accreditation
                                        ) ?>
                                    </span>

                                    <?php if (
                                        $university['accreditation_score']
                                        !== null
                                    ): ?>

                                        <div class="small text-muted mt-1">
                                            Score :
                                            <?= htmlspecialchars(
                                                (string)
                                                $university['accreditation_score']
                                            ) ?>/100
                                        </div>

                                    <?php endif; ?>

                                </td>


                                <td>

                                    <span
                                        class="badge text-bg-<?= $statusClass ?>"
                                    >
                                        <?= htmlspecialchars(
                                            $status
                                        ) ?>
                                    </span>

                                </td>


                                <td class="text-end">

                                    <div class="dropdown">

                                        <button
                                            class="btn btn-sm
                                                   btn-outline-secondary
                                                   dropdown-toggle"
                                            type="button"
                                            data-bs-toggle="dropdown"
                                            aria-expanded="false"
                                        >
                                            <i class="bi bi-three-dots"></i>
                                        </button>

                                        <ul
                                            class="dropdown-menu
                                                   dropdown-menu-end"
                                        >

                                            <li>
                                                <a
                                                    class="dropdown-item"
                                                    href="/universities/<?= $id ?>"
                                                >
                                                    <i class="bi bi-eye me-2"></i>
                                                    Consulter
                                                </a>
                                            </li>

                                            <li>
                                                <a
                                                    class="dropdown-item"
                                                    href="/universities/<?= $id ?>/edit"
                                                >
                                                    <i class="bi bi-pencil me-2"></i>
                                                    Modifier
                                                </a>
                                            </li>

                                        </ul>

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
document.addEventListener(
    'DOMContentLoaded',
    function () {
        const searchInput =
            document.getElementById(
                'universitySearch'
            );

        if (!searchInput) {
            return;
        }

        searchInput.addEventListener(
            'input',
            function () {
                const search =
                    this.value
                        .trim()
                        .toLowerCase();

                document
                    .querySelectorAll(
                        '.university-row'
                    )
                    .forEach(
                        function (row) {
                            const content =
                                row.textContent
                                    .toLowerCase();

                            row.style.display =
                                content.includes(search)
                                    ? ''
                                    : 'none';
                        }
                    );
            }
        );
    }
);
</script>