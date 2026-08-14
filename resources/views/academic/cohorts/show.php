<?php

declare(strict_types=1);

/**
 * @var array $cohort
 */

$cohort = $cohort ?? [];

$id = (int) (
    $cohort['id']
    ?? 0
);

$name = (string) (
    $cohort['name']
    ?? ''
);

$programCode = (string) (
    $cohort['program_code']
    ?? ''
);

$programName = (string) (
    $cohort['program_name']
    ?? ''
);

$programStatus = (string) (
    $cohort['program_status']
    ?? ''
);

$disciplineCode = (string) (
    $cohort['discipline_code']
    ?? ''
);

$durationYears =
    $cohort['duration_years']
    ?? null;

$academicYearLabel = (string) (
    $cohort['academic_year_label']
    ?? ''
);

$academicYearStatus = (string) (
    $cohort['academic_year_status']
    ?? ''
);

$startsOn = (string) (
    $cohort['starts_on']
    ?? ''
);

$endsOn = (string) (
    $cohort['ends_on']
    ?? ''
);

$universityCode = (string) (
    $cohort['university_code']
    ?? ''
);

$universityName = (string) (
    $cohort['university_name']
    ?? ''
);

$facultyCode = (string) (
    $cohort['faculty_code']
    ?? ''
);

$facultyName = (string) (
    $cohort['faculty_name']
    ?? ''
);


/*
|--------------------------------------------------------------------------
| Badges
|--------------------------------------------------------------------------
*/

$yearBadge = match ($academicYearStatus) {
    'OPEN' => 'success',
    'CLOSED' => 'secondary',
    'PLANNED' => 'warning',
    default => 'light',
};

$programBadge = match ($programStatus) {
    'ACTIVE' => 'success',
    'INACTIVE' => 'secondary',
    default => 'light',
};


/*
|--------------------------------------------------------------------------
| Date formatter
|--------------------------------------------------------------------------
*/

$formatDate = static function (
    string $date
): string {
    if ($date === '') {
        return '—';
    }

    $timestamp = strtotime($date);

    if ($timestamp === false) {
        return $date;
    }

    return date(
        'd/m/Y',
        $timestamp
    );
};
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

            <div
                class="d-flex flex-wrap
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

                <?php if ($academicYearLabel !== ''): ?>

                    <span
                        class="badge
                               bg-primary-subtle
                               text-primary
                               rounded-pill"
                    >

                        <?= htmlspecialchars(
                            $academicYearLabel,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>

                    </span>

                <?php endif; ?>

            </div>

            <p class="text-muted mb-0">
                Fiche détaillée de la cohorte académique
            </p>

        </div>


        <div class="d-flex gap-2">

            <a
                href="/cohorts"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Retour
            </a>

            <a
                href="/cohorts/<?= $id ?>/edit"
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
        | Cohort
        |--------------------------------------------------------------------------
        -->

        <div class="col-lg-8">

            <div class="card border-0 shadow-sm mb-4">

                <div
                    class="card-header
                           bg-white
                           border-0
                           py-3"
                >

                    <h5 class="mb-0 fw-semibold">

                        <i
                            class="bi bi-people
                                   text-primary
                                   me-2"
                        ></i>

                        Informations de la cohorte

                    </h5>

                </div>


                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Nom
                            </div>

                            <div class="fw-semibold">

                                <?= htmlspecialchars(
                                    $name,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Identifiant interne
                            </div>

                            <div class="fw-semibold">
                                #<?= $id ?>
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Année académique
                            </div>

                            <div class="fw-semibold">

                                <?= htmlspecialchars(
                                    $academicYearLabel !== ''
                                        ? $academicYearLabel
                                        : '—',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Statut de l'année
                            </div>

                            <span
                                class="badge text-bg-<?= $yearBadge ?>"
                            >

                                <?= htmlspecialchars(
                                    $academicYearStatus !== ''
                                        ? $academicYearStatus
                                        : '—',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>

                            </span>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Début
                            </div>

                            <div class="fw-semibold">

                                <?= htmlspecialchars(
                                    $formatDate($startsOn),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Fin
                            </div>

                            <div class="fw-semibold">

                                <?= htmlspecialchars(
                                    $formatDate($endsOn),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!--
            |--------------------------------------------------------------------------
            | Program
            |--------------------------------------------------------------------------
            -->

            <div class="card border-0 shadow-sm">

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

                        Programme académique

                    </h5>

                </div>


                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Programme
                            </div>

                            <div class="fw-semibold">

                                <?= htmlspecialchars(
                                    $programName !== ''
                                        ? $programName
                                        : '—',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>

                            </div>

                            <?php if ($programCode !== ''): ?>

                                <small class="text-muted">

                                    <?= htmlspecialchars(
                                        $programCode,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                </small>

                            <?php endif; ?>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Statut du programme
                            </div>

                            <span
                                class="badge text-bg-<?= $programBadge ?>"
                            >

                                <?= htmlspecialchars(
                                    $programStatus !== ''
                                        ? $programStatus
                                        : '—',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>

                            </span>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Code de discipline
                            </div>

                            <div class="fw-semibold">

                                <?= htmlspecialchars(
                                    $disciplineCode !== ''
                                        ? $disciplineCode
                                        : '—',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Durée
                            </div>

                            <div class="fw-semibold">

                                <?php if (
                                    $durationYears !== null
                                    && $durationYears !== ''
                                ): ?>

                                    <?= (int) $durationYears ?>
                                    an<?= (int) $durationYears > 1
                                        ? 's'
                                        : '' ?>

                                <?php else: ?>

                                    —

                                <?php endif; ?>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!--
        |--------------------------------------------------------------------------
        | Institutional context
        |--------------------------------------------------------------------------
        -->

        <div class="col-lg-4">

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-body text-center py-4">

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

                        <i class="bi bi-people fs-2"></i>

                    </div>


                    <h5 class="fw-semibold mb-1">

                        <?= htmlspecialchars(
                            $name,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>

                    </h5>


                    <?php if ($academicYearLabel !== ''): ?>

                        <div class="mb-3">

                            <span
                                class="badge
                                       bg-primary-subtle
                                       text-primary
                                       rounded-pill"
                            >

                                <?= htmlspecialchars(
                                    $academicYearLabel,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>

                            </span>

                        </div>

                    <?php endif; ?>


                    <p class="text-muted small mb-0">
                        Cohorte académique MedTrack
                    </p>

                </div>

            </div>


            <!-- University -->

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-body">

                    <h6 class="fw-semibold mb-3">

                        <i
                            class="bi bi-building
                                   text-primary
                                   me-2"
                        ></i>

                        Université

                    </h6>


                    <div class="fw-semibold">

                        <?= htmlspecialchars(
                            $universityName !== ''
                                ? $universityName
                                : '—',
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>

                    </div>


                    <?php if ($universityCode !== ''): ?>

                        <small class="text-muted">

                            <?= htmlspecialchars(
                                $universityCode,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>

                        </small>

                    <?php endif; ?>

                </div>

            </div>


            <!-- Faculty -->

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <h6 class="fw-semibold mb-3">

                        <i
                            class="bi bi-diagram-3
                                   text-primary
                                   me-2"
                        ></i>

                        Faculté

                    </h6>


                    <?php if ($facultyName !== ''): ?>

                        <div class="fw-semibold">

                            <?= htmlspecialchars(
                                $facultyName,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>

                        </div>

                        <?php if ($facultyCode !== ''): ?>

                            <small class="text-muted">

                                <?= htmlspecialchars(
                                    $facultyCode,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>

                            </small>

                        <?php endif; ?>

                    <?php else: ?>

                        <div class="text-muted">
                            Rattachement direct à l'université
                        </div>

                    <?php endif; ?>

                </div>

            </div>

        </div>

    </div>


    <!--
    |--------------------------------------------------------------------------
    | Relationship information
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
                    Structure académique
                </strong>

                <div class="small text-muted mt-1">
                    Cette cohorte est rattachée au programme

                    <strong>
                        <?= htmlspecialchars(
                            $programName !== ''
                                ? $programName
                                : 'sélectionné',
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </strong>

                    pour l'année académique

                    <strong>
                        <?= htmlspecialchars(
                            $academicYearLabel !== ''
                                ? $academicYearLabel
                                : 'sélectionnée',
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </strong>.

                    L'université et la faculté sont déterminées
                    par le programme académique.
                </div>

            </div>

        </div>

    </div>

</div>