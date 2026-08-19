<?php

declare(strict_types=1);

/**
 * @var array<int, array<string, mixed>> $faculties
 * @var array<string, int> $statistics
 * @var bool $isPlatform
 * @var bool $isUniversityContext
 */

$faculties =
    is_array(
        $faculties
        ?? null
    )
        ? $faculties
        : [];

$statistics =
    is_array(
        $statistics
        ?? null
    )
        ? $statistics
        : [];

$isPlatform =
    (bool) (
        $isPlatform
        ?? false
    );

$isUniversityContext =
    (bool) (
        $isUniversityContext
        ?? false
    );

$totalFaculties =
    (int) (
        $statistics['total']
        ?? count(
            $faculties
        )
    );

$activeFaculties =
    (int) (
        $statistics['active']
        ?? count(
            array_filter(
                $faculties,
                static fn (
                    array $faculty
                ): bool =>
                    strtoupper(
                        trim(
                            (string) (
                                $faculty['status']
                                ?? ''
                            )
                        )
                    ) === 'ACTIVE'
            )
        )
    );

$inactiveFaculties =
    (int) (
        $statistics['inactive']
        ?? max(
            0,
            $totalFaculties
            - $activeFaculties
        )
    );

$universitiesCount =
    $isPlatform
        ? count(
            array_unique(
                array_filter(
                    array_map(
                        static fn (
                            mixed $value
                        ): int =>
                            (int) $value,
                        array_column(
                            $faculties,
                            'university_id'
                        )
                    ),
                    static fn (
                        int $value
                    ): bool =>
                        $value > 0
                )
            )
        )
        : 1;

$activityRate =
    $totalFaculties > 0
        ? (int) round(
            (
                $activeFaculties
                / $totalFaculties
            ) * 100
        )
        : 0;
?>

<div class="container-fluid px-0">

    <!-- =========================================================
         Header
         ========================================================= -->

    <div
        class="d-flex
               flex-column
               flex-lg-row
               justify-content-between
               align-items-lg-center
               gap-3
               mb-4"
    >

        <div>

            <h2 class="fw-bold mb-1">
                Facultés
            </h2>

            <p class="text-muted mb-0">

                <?php if ($isUniversityContext): ?>

                    Gérez les facultés de votre université
                    et leur disponibilité dans MedTrack.

                <?php else: ?>

                    Gérez les facultés rattachées aux universités
                    enregistrées dans MedTrack.

                <?php endif; ?>

            </p>

        </div>


        <a
            href="/faculties/create"
            class="btn
                   btn-primary
                   d-inline-flex
                   align-items-center
                   gap-2"
        >
            <i class="bi bi-plus-lg"></i>

            Nouvelle faculté
        </a>

    </div>


    <!-- =========================================================
         Statistics
         ========================================================= -->

    <div class="row g-3 mb-4">

        <!-- Total -->

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-4">

                    <div
                        class="d-flex
                               justify-content-between
                               align-items-center"
                    >

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
                            style="
                                width: 52px;
                                height: 52px;
                            "
                        >
                            <i class="bi bi-diagram-3 fs-4"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- Active -->

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-4">

                    <div
                        class="d-flex
                               justify-content-between
                               align-items-center"
                    >

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
                            style="
                                width: 52px;
                                height: 52px;
                            "
                        >
                            <i class="bi bi-check-circle fs-4"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- Inactive -->

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-4">

                    <div
                        class="d-flex
                               justify-content-between
                               align-items-center"
                    >

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
                            style="
                                width: 52px;
                                height: 52px;
                            "
                        >
                            <i class="bi bi-pause-circle fs-4"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- Context-specific metric -->

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-4">

                    <div
                        class="d-flex
                               justify-content-between
                               align-items-center"
                    >

                        <div>

                            <?php if ($isPlatform): ?>

                                <div class="text-muted small mb-1">
                                    Universités
                                </div>

                                <div class="fs-3 fw-bold">
                                    <?= $universitiesCount ?>
                                </div>

                                <div class="small text-muted">
                                    Avec des facultés
                                </div>

                            <?php else: ?>

                                <div class="text-muted small mb-1">
                                    Taux d'activité
                                </div>

                                <div class="fs-3 fw-bold">
                                    <?= $activityRate ?>%
                                </div>

                                <div class="small text-muted">
                                    Facultés actives
                                </div>

                            <?php endif; ?>

                        </div>


                        <div
                            class="rounded-circle
                                   bg-warning-subtle
                                   text-warning-emphasis
                                   d-flex
                                   align-items-center
                                   justify-content-center"
                            style="
                                width: 52px;
                                height: 52px;
                            "
                        >

                            <?php if ($isPlatform): ?>

                                <i class="bi bi-mortarboard fs-4"></i>

                            <?php else: ?>

                                <i class="bi bi-activity fs-4"></i>

                            <?php endif; ?>

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

                <div
                    class="d-flex
                           flex-column
                           flex-lg-row
                           justify-content-between
                           align-items-lg-center
                           gap-3"
                >

                    <div>

                        <h5 class="fw-bold mb-1">
                            Liste des facultés
                        </h5>

                        <div class="text-muted small">

                            <?= $totalFaculties ?>

                            faculté<?= $totalFaculties > 1
                                ? 's'
                                : ''
                            ?>

                            enregistrée<?= $totalFaculties > 1
                                ? 's'
                                : ''
                            ?>

                        </div>

                    </div>


                    <?php if ($totalFaculties > 0): ?>

                        <div
                            style="
                                max-width: 320px;
                                width: 100%;
                            "
                        >

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
                        style="
                            width: 72px;
                            height: 72px;
                        "
                    >
                        <i class="bi bi-diagram-3 fs-2"></i>
                    </div>

                    <h5 class="fw-bold">
                        Aucune faculté enregistrée
                    </h5>

                    <p
                        class="text-muted mx-auto"
                        style="max-width: 500px;"
                    >

                        <?php if ($isUniversityContext): ?>

                            Votre université ne possède encore
                            aucune faculté. Commencez par créer
                            votre première structure académique.

                        <?php else: ?>

                            Commencez par créer une faculté
                            et rattachez-la à une université
                            existante.

                        <?php endif; ?>

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

                                <?php if ($isPlatform): ?>

                                    <th>
                                        Université
                                    </th>

                                <?php endif; ?>

                                <th>
                                    Statut
                                </th>

                                <th class="text-end pe-4">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            <?php foreach (
                                $faculties
                                as $faculty
                            ): ?>

                                <?php
                                $id =
                                    (int) (
                                        $faculty['id']
                                        ?? 0
                                    );

                                $name =
                                    trim(
                                        (string) (
                                            $faculty['name']
                                            ?? ''
                                        )
                                    );

                                $code =
                                    trim(
                                        (string) (
                                            $faculty['code']
                                            ?? ''
                                        )
                                    );

                                $universityName =
                                    trim(
                                        (string) (
                                            $faculty['university_name']
                                            ?? ''
                                        )
                                    );

                                $universityCode =
                                    trim(
                                        (string) (
                                            $faculty['university_code']
                                            ?? ''
                                        )
                                    );

                                $status =
                                    strtoupper(
                                        trim(
                                            (string) (
                                                $faculty['status']
                                                ?? 'INACTIVE'
                                            )
                                        )
                                    );

                                $isFacultyActive =
                                    $status === 'ACTIVE';

                                $searchValues = [
                                    $name,
                                    $code,
                                    $status,
                                ];

                                if ($isPlatform) {
                                    $searchValues[] =
                                        $universityName;

                                    $searchValues[] =
                                        $universityCode;
                                }

                                $searchable =
                                    strtolower(
                                        implode(
                                            ' ',
                                            array_filter(
                                                $searchValues,
                                                static fn (
                                                    string $value
                                                ): bool =>
                                                    $value !== ''
                                            )
                                        )
                                    );
                                ?>


                                <tr
                                    class="faculty-row"
                                    data-search="<?= htmlspecialchars(
                                        $searchable,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                >

                                    <!-- Faculty -->

                                    <td class="ps-4">

                                        <div
                                            class="d-flex
                                                   align-items-center
                                                   gap-3"
                                        >

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
                                                <i class="bi bi-diagram-3"></i>
                                            </div>


                                            <div>

                                                <?php if ($id > 0): ?>

                                                    <a
                                                        href="/faculties/<?= $id ?>"
                                                        class="fw-semibold
                                                               text-decoration-none"
                                                    >
                                                        <?= htmlspecialchars(
                                                            $name,
                                                            ENT_QUOTES,
                                                            'UTF-8'
                                                        ) ?>
                                                    </a>

                                                <?php else: ?>

                                                    <span class="fw-semibold">
                                                        <?= htmlspecialchars(
                                                            $name,
                                                            ENT_QUOTES,
                                                            'UTF-8'
                                                        ) ?>
                                                    </span>

                                                <?php endif; ?>

                                                <div class="small text-muted">
                                                    Faculté
                                                </div>

                                            </div>

                                        </div>

                                    </td>


                                    <!-- Code -->

                                    <td>

                                        <?php if ($code !== ''): ?>

                                            <span
                                                class="badge
                                                       text-bg-light
                                                       border"
                                            >
                                                <?= htmlspecialchars(
                                                    $code,
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>
                                            </span>

                                        <?php else: ?>

                                            <span class="text-muted">
                                                —
                                            </span>

                                        <?php endif; ?>

                                    </td>


                                    <!-- University -->

                                    <?php if ($isPlatform): ?>

                                        <td>

                                            <div class="fw-semibold">

                                                <?= htmlspecialchars(
                                                    $universityName !== ''
                                                        ? $universityName
                                                        : '—',
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>

                                            </div>

                                            <?php if (
                                                $universityCode !== ''
                                            ): ?>

                                                <div class="small text-muted">

                                                    <?= htmlspecialchars(
                                                        $universityCode,
                                                        ENT_QUOTES,
                                                        'UTF-8'
                                                    ) ?>

                                                </div>

                                            <?php endif; ?>

                                        </td>

                                    <?php endif; ?>


                                    <!-- Status -->

                                    <td>

                                        <?php if ($isFacultyActive): ?>

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


                                    <!-- Actions -->

                                    <td class="text-end pe-4">

                                        <?php if ($id > 0): ?>

                                            <div class="dropdown">

                                                <button
                                                    class="btn
                                                           btn-sm
                                                           btn-light"
                                                    type="button"
                                                    data-bs-toggle="dropdown"
                                                    aria-expanded="false"
                                                    aria-label="Actions de la faculté"
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

                                        <?php endif; ?>

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
                        class="bi
                               bi-search
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
document.addEventListener(
    'DOMContentLoaded',
    () => {
        const searchInput =
            document.getElementById(
                'facultySearch'
            );

        const table =
            document.getElementById(
                'facultiesTable'
            );

        const emptyState =
            document.getElementById(
                'facultySearchEmpty'
            );

        if (!searchInput || !table) {
            return;
        }

        const rows =
            Array.from(
                table.querySelectorAll(
                    '.faculty-row'
                )
            );

        searchInput.addEventListener(
            'input',
            () => {
                const query =
                    searchInput.value
                        .trim()
                        .toLowerCase();

                let visible = 0;

                rows.forEach(
                    (row) => {
                        const searchable =
                            row.dataset.search
                            || '';

                        const matches =
                            searchable.includes(
                                query
                            );

                        row.classList.toggle(
                            'd-none',
                            !matches
                        );

                        if (matches) {
                            visible++;
                        }
                    }
                );

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
            }
        );
    }
);
</script>

<?php endif; ?>