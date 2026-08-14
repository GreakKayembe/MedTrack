<?php

declare(strict_types=1);

/**
 * @var array $faculties
 */

$faculties = $faculties ?? [];

$totalFaculties = count($faculties);

$activeFaculties = count(
    array_filter(
        $faculties,
        static fn (array $faculty): bool =>
            ($faculty['status'] ?? '') === 'ACTIVE'
    )
);

$inactiveFaculties =
    $totalFaculties - $activeFaculties;

$universitiesCount = count(
    array_unique(
        array_column(
            $faculties,
            'university_id'
        )
    )
);
?>

<div class="container-fluid px-0">

    <!-- =========================================================
         Header
         ========================================================= -->

    <div class="d-flex flex-column flex-lg-row
                justify-content-between
                align-items-lg-center
                gap-3 mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                Facultés
            </h2>

            <p class="text-muted mb-0">
                Gérez les facultés rattachées aux universités
                enregistrées dans MedTrack.
            </p>
        </div>

        <a
            href="/faculties/create"
            class="btn btn-primary
                   d-inline-flex align-items-center gap-2"
        >
            <i class="bi bi-plus-lg"></i>
            Nouvelle faculté
        </a>

    </div>


    <!-- =========================================================
         Statistics
         ========================================================= -->

    <div class="row g-3 mb-4">

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">

                    <div class="d-flex
                                justify-content-between
                                align-items-center">

                        <div>
                            <div class="text-muted small mb-1">
                                Total
                            </div>

                            <div class="fs-3 fw-bold">
                                <?= $totalFaculties ?>
                            </div>

                            <div class="small text-muted">
                                Facultés enregistrées
                            </div>
                        </div>

                        <div
                            class="rounded-circle
                                   bg-primary-subtle
                                   text-primary
                                   d-flex
                                   align-items-center
                                   justify-content-center"
                            style="width:52px;height:52px;"
                        >
                            <i class="bi bi-diagram-3 fs-4"></i>
                        </div>

                    </div>

                </div>
            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">

                    <div class="d-flex
                                justify-content-between
                                align-items-center">

                        <div>
                            <div class="text-muted small mb-1">
                                Actives
                            </div>

                            <div class="fs-3 fw-bold">
                                <?= $activeFaculties ?>
                            </div>

                            <div class="small text-muted">
                                En activité
                            </div>
                        </div>

                        <div
                            class="rounded-circle
                                   bg-success-subtle
                                   text-success
                                   d-flex
                                   align-items-center
                                   justify-content-center"
                            style="width:52px;height:52px;"
                        >
                            <i class="bi bi-check-circle fs-4"></i>
                        </div>

                    </div>

                </div>
            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">

                    <div class="d-flex
                                justify-content-between
                                align-items-center">

                        <div>
                            <div class="text-muted small mb-1">
                                Inactives
                            </div>

                            <div class="fs-3 fw-bold">
                                <?= $inactiveFaculties ?>
                            </div>

                            <div class="small text-muted">
                                Désactivées
                            </div>
                        </div>

                        <div
                            class="rounded-circle
                                   bg-secondary-subtle
                                   text-secondary
                                   d-flex
                                   align-items-center
                                   justify-content-center"
                            style="width:52px;height:52px;"
                        >
                            <i class="bi bi-pause-circle fs-4"></i>
                        </div>

                    </div>

                </div>
            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">

                    <div class="d-flex
                                justify-content-between
                                align-items-center">

                        <div>
                            <div class="text-muted small mb-1">
                                Universités
                            </div>

                            <div class="fs-3 fw-bold">
                                <?= $universitiesCount ?>
                            </div>

                            <div class="small text-muted">
                                Avec des facultés
                            </div>
                        </div>

                        <div
                            class="rounded-circle
                                   bg-info-subtle
                                   text-info
                                   d-flex
                                   align-items-center
                                   justify-content-center"
                            style="width:52px;height:52px;"
                        >
                            <i class="bi bi-mortarboard fs-4"></i>
                        </div>

                    </div>

                </div>
            </div>

        </div>

    </div>


    <!-- =========================================================
         Faculties table
         ========================================================= -->

    <div class="card border-0 shadow-sm">

        <div class="card-body p-0">

            <div class="p-4 border-bottom">

                <div class="d-flex flex-column flex-lg-row
                            justify-content-between
                            align-items-lg-center
                            gap-3">

                    <div>
                        <h5 class="fw-bold mb-1">
                            Liste des facultés
                        </h5>

                        <div class="text-muted small">
                            <?= $totalFaculties ?>
                            faculté<?= $totalFaculties > 1 ? 's' : '' ?>
                            enregistrée<?= $totalFaculties > 1 ? 's' : '' ?>
                        </div>
                    </div>

                    <?php if ($totalFaculties > 0): ?>

                        <div style="max-width:320px;width:100%;">

                            <div class="input-group">

                                <span class="input-group-text bg-white">
                                    <i class="bi bi-search"></i>
                                </span>

                                <input
                                    type="search"
                                    id="facultySearch"
                                    class="form-control"
                                    placeholder="Rechercher une faculté..."
                                    autocomplete="off"
                                >

                            </div>

                        </div>

                    <?php endif; ?>

                </div>

            </div>


            <?php if ($faculties === []): ?>

                <!-- Empty state -->

                <div class="text-center py-5 px-3">

                    <div
                        class="rounded-circle
                               bg-primary-subtle
                               text-primary
                               d-inline-flex
                               align-items-center
                               justify-content-center
                               mb-3"
                        style="width:72px;height:72px;"
                    >
                        <i class="bi bi-diagram-3 fs-2"></i>
                    </div>

                    <h5 class="fw-bold">
                        Aucune faculté enregistrée
                    </h5>

                    <p
                        class="text-muted mx-auto"
                        style="max-width:500px;"
                    >
                        Commencez par créer une faculté
                        et rattachez-la à une université
                        existante.
                    </p>

                    <a
                        href="/faculties/create"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-plus-lg me-1"></i>
                        Créer une faculté
                    </a>

                </div>

            <?php else: ?>

                <div class="table-responsive">

                    <table
                        class="table
                               table-hover
                               align-middle
                               mb-0"
                        id="facultiesTable"
                    >

                        <thead class="table-light">

                            <tr>
                                <th class="ps-4">
                                    Faculté
                                </th>

                                <th>
                                    Code
                                </th>

                                <th>
                                    Université
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

                        <?php foreach ($faculties as $faculty): ?>

                            <?php
                            $id = (int) (
                                $faculty['id']
                                ?? 0
                            );

                            $name = (string) (
                                $faculty['name']
                                ?? ''
                            );

                            $code = (string) (
                                $faculty['code']
                                ?? ''
                            );

                            $universityName = (string) (
                                $faculty['university_name']
                                ?? ''
                            );

                            $universityCode = (string) (
                                $faculty['university_code']
                                ?? ''
                            );

                            $status = (string) (
                                $faculty['status']
                                ?? 'INACTIVE'
                            );

                            $isActive =
                                $status === 'ACTIVE';
                            ?>

                            <tr
                                class="faculty-row"
                                data-search="<?= htmlspecialchars(
                                    strtolower(
                                        implode(
                                            ' ',
                                            [
                                                $name,
                                                $code,
                                                $universityName,
                                                $universityCode,
                                                $status,
                                            ]
                                        )
                                    ),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>"
                            >

                                <td class="ps-4">

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
                                                width:42px;
                                                height:42px;
                                            "
                                        >
                                            <i class="bi bi-diagram-3"></i>
                                        </div>

                                        <div>

                                            <a
                                                href="/faculties/<?= $id ?>"
                                                class="fw-semibold
                                                       text-decoration-none"
                                            >
                                                <?= htmlspecialchars(
                                                    $name
                                                ) ?>
                                            </a>

                                            <div class="small text-muted">
                                                Faculté
                                            </div>

                                        </div>

                                    </div>

                                </td>


                                <td>

                                    <?php if ($code !== ''): ?>

                                        <span
                                            class="badge
                                                   text-bg-light
                                                   border"
                                        >
                                            <?= htmlspecialchars(
                                                $code
                                            ) ?>
                                        </span>

                                    <?php else: ?>

                                        <span class="text-muted">
                                            —
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <td>

                                    <div class="fw-semibold">
                                        <?= htmlspecialchars(
                                            $universityName
                                        ) ?>
                                    </div>

                                    <?php if ($universityCode !== ''): ?>

                                        <div class="small text-muted">
                                            <?= htmlspecialchars(
                                                $universityCode
                                            ) ?>
                                        </div>

                                    <?php endif; ?>

                                </td>


                                <td>

                                    <?php if ($isActive): ?>

                                        <span
                                            class="badge
                                                   rounded-pill
                                                   text-bg-success"
                                        >
                                            <i
                                                class="bi
                                                       bi-check-circle
                                                       me-1"
                                            ></i>

                                            Active
                                        </span>

                                    <?php else: ?>

                                        <span
                                            class="badge
                                                   rounded-pill
                                                   text-bg-secondary"
                                        >
                                            <i
                                                class="bi
                                                       bi-pause-circle
                                                       me-1"
                                            ></i>

                                            Inactive
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <td class="text-end pe-4">

                                    <div class="dropdown">

                                        <button
                                            class="btn
                                                   btn-sm
                                                   btn-light"
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
                                                    href="/faculties/<?= $id ?>"
                                                >
                                                    <i
                                                        class="bi
                                                               bi-eye
                                                               me-2"
                                                    ></i>

                                                    Consulter
                                                </a>

                                            </li>

                                            <li>

                                                <a
                                                    class="dropdown-item"
                                                    href="/faculties/<?= $id ?>/edit"
                                                >
                                                    <i
                                                        class="bi
                                                               bi-pencil-square
                                                               me-2"
                                                    ></i>

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


                <!-- No search results -->

                <div
                    id="facultySearchEmpty"
                    class="text-center py-5 d-none"
                >
                    <i
                        class="bi bi-search
                               fs-2
                               text-muted"
                    ></i>

                    <div class="fw-semibold mt-3">
                        Aucun résultat
                    </div>

                    <div class="small text-muted">
                        Aucune faculté ne correspond
                        à votre recherche.
                    </div>
                </div>

            <?php endif; ?>

        </div>

    </div>

</div>


<?php if ($faculties !== []): ?>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const searchInput =
        document.getElementById('facultySearch');

    const table =
        document.getElementById('facultiesTable');

    const emptyState =
        document.getElementById('facultySearchEmpty');

    if (!searchInput || !table) {
        return;
    }

    const rows = Array.from(
        table.querySelectorAll('.faculty-row')
    );

    searchInput.addEventListener('input', () => {
        const query =
            searchInput.value
                .trim()
                .toLowerCase();

        let visible = 0;

        rows.forEach((row) => {
            const searchable =
                row.dataset.search
                || '';

            const matches =
                searchable.includes(query);

            row.classList.toggle(
                'd-none',
                !matches
            );

            if (matches) {
                visible++;
            }
        });

        table.classList.toggle(
            'd-none',
            visible === 0
        );

        if (emptyState) {
            emptyState.classList.toggle(
                'd-none',
                visible !== 0
            );
        }
    });
});
</script>

<?php endif; ?>