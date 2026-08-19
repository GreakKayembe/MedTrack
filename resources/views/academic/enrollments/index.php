<?php

declare(strict_types=1);

/**
 * @var array $enrollments
 */

$enrollments = $enrollments ?? [];

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
?>

<div class="container-fluid py-4">

    <div
        class="d-flex flex-column flex-md-row
               align-items-md-center justify-content-between
               gap-3 mb-4"
    >
        <div>
            <h1 class="h3 mb-1">
                Inscriptions académiques
            </h1>

            <p class="text-muted mb-0">
                Gérez les inscriptions académiques
                des étudiants dans MedTrack.
            </p>
        </div>

        <div>
            <a
                href="/academic-enrollments/create"
                class="btn btn-primary"
            >
                <i class="bi bi-person-check me-1"></i>

                Nouvelle inscription
            </a>
        </div>
    </div>


    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <?php if ($enrollments === []): ?>

                <div class="text-center py-5">

                    <div class="mb-3">
                        <i
                            class="bi bi-person-vcard
                                   fs-1 text-muted"
                        ></i>
                    </div>

                    <h2 class="h5">
                        Aucune inscription académique
                    </h2>

                    <p class="text-muted mb-4">
                        Aucun étudiant n'est encore inscrit
                        dans un programme académique.
                    </p>

                    <a
                        href="/academic-enrollments/create"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-person-check me-1"></i>

                        Inscrire un étudiant
                    </a>

                </div>

            <?php else: ?>

                <div class="table-responsive">

                    <table
                        class="table table-hover
                               align-middle mb-0"
                    >

                        <thead>
                            <tr>

                                <th>
                                    Étudiant
                                </th>

                                <th>
                                    Matricule
                                </th>

                                <th>
                                    Université
                                </th>

                                <th>
                                    Programme
                                </th>

                                <th>
                                    Année
                                </th>

                                <th>
                                    Niveau
                                </th>

                                <th>
                                    Cohorte
                                </th>

                                <th>
                                    Statut
                                </th>

                                <th class="text-end">
                                    Actions
                                </th>

                            </tr>
                        </thead>

                        <tbody>

                        <?php foreach ($enrollments as $enrollment): ?>

                            <?php
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


                            /*
                            |--------------------------------------------------------------------------
                            | University
                            |--------------------------------------------------------------------------
                            */

                            $universityName =
                                trim(
                                    (string) (
                                        $enrollment[
                                            'university_name'
                                        ]
                                        ?? ''
                                    )
                                );

                            $universityCode =
                                trim(
                                    (string) (
                                        $enrollment[
                                            'university_code'
                                        ]
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


                            /*
                            |--------------------------------------------------------------------------
                            | Study level
                            |--------------------------------------------------------------------------
                            */

                            $studyLevel =
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
                                        $enrollment[
                                            'cohort_name'
                                        ]
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

                            $statusLabel =
                                $statusLabels[$status]
                                ?? $status;

                            $statusClass =
                                $statusClasses[$status]
                                ?? 'secondary';
                            ?>

                            <tr>

                                <!-- Student -->
                                <td>

                                    <div
                                        class="d-flex
                                               align-items-center
                                               gap-3"
                                    >

                                        <div
                                            class="rounded-circle
                                                   bg-body-secondary
                                                   d-flex
                                                   align-items-center
                                                   justify-content-center
                                                   flex-shrink-0"
                                            style="
                                                width: 42px;
                                                height: 42px;
                                            "
                                        >
                                            <i
                                                class="bi bi-person"
                                            ></i>
                                        </div>

                                        <div>

                                            <?php if ($studentId > 0): ?>

                                                <a
                                                    href="/students/<?= $studentId ?>"
                                                    class="fw-semibold
                                                           text-decoration-none"
                                                >
                                                    <?= htmlspecialchars(
                                                        $studentName !== ''
                                                            ? $studentName
                                                            : 'Étudiant',
                                                        ENT_QUOTES,
                                                        'UTF-8'
                                                    ) ?>
                                                </a>

                                            <?php else: ?>

                                                <span class="fw-semibold">
                                                    <?= htmlspecialchars(
                                                        $studentName !== ''
                                                            ? $studentName
                                                            : 'Étudiant',
                                                        ENT_QUOTES,
                                                        'UTF-8'
                                                    ) ?>
                                                </span>

                                            <?php endif; ?>

                                        </div>

                                    </div>

                                </td>


                                <!-- Registration number -->
                                <td>

                                    <?php if (
                                        $registrationNumber !== ''
                                    ): ?>

                                        <span class="fw-medium">
                                            <?= htmlspecialchars(
                                                $registrationNumber,
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
                                <td>

                                    <div class="fw-medium">
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


                                <!-- Program -->
                                <td>

                                    <div>
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

                                </td>


                                <!-- Academic year -->
                                <td>

                                    <?= htmlspecialchars(
                                        $academicYear !== ''
                                            ? $academicYear
                                            : '—',
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                </td>


                                <!-- Study level -->
                                <td>

                                    <div>
                                        <?= htmlspecialchars(
                                            $studyLevel !== ''
                                                ? $studyLevel
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

                                </td>


                                <!-- Cohort -->
                                <td>

                                    <?php if ($cohortName !== ''): ?>

                                        <?= htmlspecialchars(
                                            $cohortName,
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>

                                    <?php else: ?>

                                        <span class="text-muted">
                                            —
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <!-- Status -->
                                <td>

                                    <span
                                        class="badge text-bg-<?= htmlspecialchars(
                                            $statusClass,
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>"
                                    >
                                        <?= htmlspecialchars(
                                            $statusLabel,
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </span>

                                </td>


                                <!-- Actions -->
                                <td class="text-end">

                                    <div
                                        class="btn-group btn-group-sm"
                                        role="group"
                                    >

                                        <a
                                            href="/academic-enrollments/<?= $enrollmentId ?>"
                                            class="btn btn-outline-secondary"
                                            title="Consulter"
                                        >
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <a
                                            href="/academic-enrollments/<?= $enrollmentId ?>/edit"
                                            class="btn btn-outline-primary"
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