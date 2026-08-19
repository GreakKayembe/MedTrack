<?php

declare(strict_types=1);

/**
 * @var array $enrollment
 */

$enrollment = $enrollment ?? [];

$enrollmentId =
    (int) (
        $enrollment['id']
        ?? 0
    );

$studentId =
    (int) (
        $enrollment['student_id']
        ?? 0
    );


/*
|--------------------------------------------------------------------------
| Student
|--------------------------------------------------------------------------
*/

$firstName =
    trim(
        (string) (
            $enrollment['first_name']
            ?? ''
        )
    );

$middleName =
    trim(
        (string) (
            $enrollment['middle_name']
            ?? ''
        )
    );

$lastName =
    trim(
        (string) (
            $enrollment['last_name']
            ?? ''
        )
    );

$studentName =
    trim(
        implode(
            ' ',
            array_filter(
                [
                    $firstName,
                    $middleName,
                    $lastName,
                ],
                static fn (
                    string $value
                ): bool =>
                    $value !== ''
            )
        )
    );

$studentNumber =
    trim(
        (string) (
            $enrollment[
                'national_student_number'
            ]
            ?? ''
        )
    );

$studentEmail =
    trim(
        (string) (
            $enrollment['student_email']
            ?? ''
        )
    );

$studentPhone =
    trim(
        (string) (
            $enrollment['student_phone']
            ?? ''
        )
    );


/*
|--------------------------------------------------------------------------
| University
|--------------------------------------------------------------------------
*/

$universityName =
    trim(
        (string) (
            $enrollment['university_name']
            ?? ''
        )
    );

$universityCode =
    trim(
        (string) (
            $enrollment['university_code']
            ?? ''
        )
    );


/*
|--------------------------------------------------------------------------
| Program
|--------------------------------------------------------------------------
*/

$programName =
    trim(
        (string) (
            $enrollment[
                'academic_program_name'
            ]
            ?? ''
        )
    );

$programCode =
    trim(
        (string) (
            $enrollment[
                'academic_program_code'
            ]
            ?? ''
        )
    );


/*
|--------------------------------------------------------------------------
| Academic year
|--------------------------------------------------------------------------
*/

$academicYear =
    trim(
        (string) (
            $enrollment[
                'academic_year_label'
            ]
            ?? ''
        )
    );

$academicYearStartsOn =
    trim(
        (string) (
            $enrollment[
                'academic_year_starts_on'
            ]
            ?? ''
        )
    );

$academicYearEndsOn =
    trim(
        (string) (
            $enrollment[
                'academic_year_ends_on'
            ]
            ?? ''
        )
    );


/*
|--------------------------------------------------------------------------
| Study level
|--------------------------------------------------------------------------
*/

$studyLevelName =
    trim(
        (string) (
            $enrollment[
                'study_level_name'
            ]
            ?? ''
        )
    );

$studyLevelCode =
    trim(
        (string) (
            $enrollment[
                'study_level_code'
            ]
            ?? ''
        )
    );


/*
|--------------------------------------------------------------------------
| Cohort
|--------------------------------------------------------------------------
*/

$cohortName =
    trim(
        (string) (
            $enrollment['cohort_name']
            ?? ''
        )
    );


/*
|--------------------------------------------------------------------------
| Registration
|--------------------------------------------------------------------------
*/

$registrationNumber =
    trim(
        (string) (
            $enrollment[
                'registration_number'
            ]
            ?? ''
        )
    );

$enrolledAt =
    trim(
        (string) (
            $enrollment['enrolled_at']
            ?? ''
        )
    );


/*
|--------------------------------------------------------------------------
| Status
|--------------------------------------------------------------------------
*/

$status =
    strtoupper(
        trim(
            (string) (
                $enrollment['status']
                ?? 'PENDING'
            )
        )
    );

$statusLabels = [
    'PENDING' => 'En attente',
    'ACTIVE' => 'Active',
    'SUSPENDED' => 'Suspendue',
    'COMPLETED' => 'Terminée',
    'CANCELLED' => 'Annulée',
];

$statusClasses = [
    'PENDING' => 'warning',
    'ACTIVE' => 'success',
    'SUSPENDED' => 'danger',
    'COMPLETED' => 'primary',
    'CANCELLED' => 'secondary',
];

$statusLabel =
    $statusLabels[$status]
    ?? $status;

$statusClass =
    $statusClasses[$status]
    ?? 'secondary';


/*
|--------------------------------------------------------------------------
| Dates formatting
|--------------------------------------------------------------------------
*/

$formatDate =
    static function (
        string $date
    ): string {
        if ($date === '') {
            return '—';
        }

        $timestamp =
            strtotime($date);

        if ($timestamp === false) {
            return $date;
        }

        return date(
            'd/m/Y',
            $timestamp
        );
    };
?>

<div class="container-fluid py-4">

    <!-- Header -->

    <div
        class="d-flex flex-column flex-md-row
               align-items-md-center
               justify-content-between
               gap-3 mb-4"
    >
        <div>

            <div class="mb-2">
                <a
                    href="/academic-enrollments"
                    class="text-decoration-none small"
                >
                    <i class="bi bi-arrow-left me-1"></i>

                    Retour aux inscriptions
                </a>
            </div>

            <h1 class="h3 mb-1">
                Inscription académique
            </h1>

            <p class="text-muted mb-0">
                Consultez le rattachement académique
                de l'étudiant.
            </p>

        </div>


        <div class="d-flex gap-2">

            <?php if ($studentId > 0): ?>

                <a
                    href="/students/<?= $studentId ?>"
                    class="btn btn-outline-secondary"
                >
                    <i class="bi bi-person me-1"></i>

                    Voir l'étudiant
                </a>

            <?php endif; ?>

            <a
                href="/academic-enrollments/<?= $enrollmentId ?>/edit"
                class="btn btn-primary"
            >
                <i class="bi bi-pencil me-1"></i>

                Modifier
            </a>

        </div>

    </div>


    <div class="row g-4">

        <!-- Main -->

        <div class="col-xl-8">

            <!-- Student -->

            <div
                class="card border-0 shadow-sm mb-4"
            >

                <div
                    class="card-header
                           bg-transparent py-3"
                >
                    <h2 class="h5 mb-0">
                        <i
                            class="bi bi-person
                                   me-2"
                        ></i>

                        Étudiant
                    </h2>
                </div>


                <div class="card-body">

                    <div
                        class="d-flex
                               align-items-center
                               gap-3 mb-4"
                    >

                        <div
                            class="rounded-circle
                                   bg-body-secondary
                                   d-flex
                                   align-items-center
                                   justify-content-center
                                   flex-shrink-0"
                            style="
                                width: 56px;
                                height: 56px;
                            "
                        >
                            <i
                                class="bi bi-person
                                       fs-4"
                            ></i>
                        </div>


                        <div>

                            <h3 class="h5 mb-1">
                                <?= htmlspecialchars(
                                    $studentName !== ''
                                        ? $studentName
                                        : 'Étudiant',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </h3>

                            <?php if (
                                $studentNumber !== ''
                            ): ?>

                                <div class="text-muted">
                                    N° étudiant :
                                    <?= htmlspecialchars(
                                        $studentNumber,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </div>

                            <?php endif; ?>

                        </div>

                    </div>


                    <div class="row g-3">

                        <div class="col-md-6">

                            <div class="small text-muted mb-1">
                                Email
                            </div>

                            <div class="fw-medium">

                                <?php if (
                                    $studentEmail !== ''
                                ): ?>

                                    <?= htmlspecialchars(
                                        $studentEmail,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                <?php else: ?>

                                    —

                                <?php endif; ?>

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="small text-muted mb-1">
                                Téléphone
                            </div>

                            <div class="fw-medium">

                                <?php if (
                                    $studentPhone !== ''
                                ): ?>

                                    <?= htmlspecialchars(
                                        $studentPhone,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                <?php else: ?>

                                    —

                                <?php endif; ?>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- Academic information -->

            <div
                class="card border-0 shadow-sm mb-4"
            >

                <div
                    class="card-header
                           bg-transparent py-3"
                >
                    <h2 class="h5 mb-0">
                        <i
                            class="bi bi-mortarboard
                                   me-2"
                        ></i>

                        Parcours académique
                    </h2>
                </div>


                <div class="card-body">

                    <div class="row g-4">


                        <!-- University -->

                        <div class="col-md-6">

                            <div class="small text-muted mb-1">
                                Université
                            </div>

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

                        </div>


                        <!-- Program -->

                        <div class="col-md-6">

                            <div class="small text-muted mb-1">
                                Programme académique
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

                            <?php if (
                                $programCode !== ''
                            ): ?>

                                <div class="small text-muted">
                                    <?= htmlspecialchars(
                                        $programCode,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </div>

                            <?php endif; ?>

                        </div>


                        <!-- Academic year -->

                        <div class="col-md-6">

                            <div class="small text-muted mb-1">
                                Année académique
                            </div>

                            <div class="fw-semibold">

                                <?= htmlspecialchars(
                                    $academicYear !== ''
                                        ? $academicYear
                                        : '—',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>

                            </div>

                            <?php if (
                                $academicYearStartsOn !== ''
                                || $academicYearEndsOn !== ''
                            ): ?>

                                <div class="small text-muted">

                                    <?= htmlspecialchars(
                                        $formatDate(
                                            $academicYearStartsOn
                                        ),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                    →

                                    <?= htmlspecialchars(
                                        $formatDate(
                                            $academicYearEndsOn
                                        ),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                </div>

                            <?php endif; ?>

                        </div>


                        <!-- Study level -->

                        <div class="col-md-6">

                            <div class="small text-muted mb-1">
                                Niveau d'études
                            </div>

                            <div class="fw-semibold">

                                <?= htmlspecialchars(
                                    $studyLevelName !== ''
                                        ? $studyLevelName
                                        : '—',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>

                            </div>

                            <?php if (
                                $studyLevelCode !== ''
                            ): ?>

                                <div class="small text-muted">
                                    <?= htmlspecialchars(
                                        $studyLevelCode,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </div>

                            <?php endif; ?>

                        </div>


                        <!-- Cohort -->

                        <div class="col-md-6">

                            <div class="small text-muted mb-1">
                                Cohorte
                            </div>

                            <div class="fw-semibold">

                                <?= htmlspecialchars(
                                    $cohortName !== ''
                                        ? $cohortName
                                        : 'Non rattaché',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- Registration -->

            <div
                class="card border-0 shadow-sm"
            >

                <div
                    class="card-header
                           bg-transparent py-3"
                >
                    <h2 class="h5 mb-0">
                        <i
                            class="bi bi-card-text
                                   me-2"
                        ></i>

                        Informations d'inscription
                    </h2>
                </div>


                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-6">

                            <div class="small text-muted mb-1">
                                Matricule
                            </div>

                            <div class="fw-semibold">

                                <?= htmlspecialchars(
                                    $registrationNumber !== ''
                                        ? $registrationNumber
                                        : '—',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="small text-muted mb-1">
                                Date d'inscription
                            </div>

                            <div class="fw-semibold">

                                <?= htmlspecialchars(
                                    $formatDate(
                                        $enrolledAt
                                    ),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- Sidebar -->

        <div class="col-xl-4">

            <!-- Status -->

            <div
                class="card border-0 shadow-sm mb-4"
            >

                <div
                    class="card-header
                           bg-transparent py-3"
                >
                    <h2 class="h5 mb-0">
                        Statut
                    </h2>
                </div>

                <div class="card-body">

                    <span
                        class="badge text-bg-<?= htmlspecialchars(
                            $statusClass,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?> fs-6"
                    >
                        <?= htmlspecialchars(
                            $statusLabel,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </span>

                </div>

            </div>


            <!-- Summary -->

            <div
                class="card border-0 shadow-sm"
            >

                <div
                    class="card-header
                           bg-transparent py-3"
                >
                    <h2 class="h5 mb-0">
                        <i
                            class="bi bi-info-circle
                                   me-2"
                        ></i>

                        Résumé
                    </h2>
                </div>


                <div class="card-body">

                    <div class="small text-muted mb-1">
                        Inscription #
                    </div>

                    <div class="fw-semibold mb-3">
                        <?= $enrollmentId ?>
                    </div>


                    <div class="small text-muted mb-1">
                        Étudiant
                    </div>

                    <div class="fw-semibold mb-3">
                        <?= htmlspecialchars(
                            $studentName !== ''
                                ? $studentName
                                : '—',
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </div>


                    <div class="small text-muted mb-1">
                        Année académique
                    </div>

                    <div class="fw-semibold">
                        <?= htmlspecialchars(
                            $academicYear !== ''
                                ? $academicYear
                                : '—',
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>