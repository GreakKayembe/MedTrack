<?php

declare(strict_types=1);

/**
 * @var array $enrollment
 * @var array $students
 * @var array $universities
 * @var array $academicPrograms
 * @var array $academicYears
 * @var array $studyLevels
 * @var array $cohorts
 * @var bool $isPlatform
 * @var bool $isUniversityContext
 * @var int|null $activeUniversityId
 */

$enrollment = $enrollment ?? [];
$students = $students ?? [];
$universities = $universities ?? [];
$academicPrograms = $academicPrograms ?? [];
$academicYears = $academicYears ?? [];
$studyLevels = $studyLevels ?? [];
$cohorts = $cohorts ?? [];
$isPlatform = (bool) ($isPlatform ?? false);
$isUniversityContext =
    (bool) ($isUniversityContext ?? false);
$activeUniversityId =
    isset($activeUniversityId)
        ? (int) $activeUniversityId
        : null;

$enrollmentId =
    (int) (
        $enrollment['id']
        ?? 0
    );

$selectedStudentId =
    (int) (
        $enrollment['student_id']
        ?? 0
    );

$selectedUniversityId =
    (int) (
        $enrollment['university_id']
        ?? 0
    );

$selectedProgramId =
    (int) (
        $enrollment['academic_program_id']
        ?? 0
    );

$selectedAcademicYearId =
    (int) (
        $enrollment['academic_year_id']
        ?? 0
    );

$selectedStudyLevelId =
    (int) (
        $enrollment['study_level_id']
        ?? 0
    );

$selectedCohortId =
    isset($enrollment['cohort_id'])
    && $enrollment['cohort_id'] !== null
        ? (int) $enrollment['cohort_id']
        : 0;

$registrationNumber =
    trim(
        (string) (
            $enrollment['registration_number']
            ?? ''
        )
    );

$selectedStatus =
    strtoupper(
        trim(
            (string) (
                $enrollment['status']
                ?? 'ACTIVE'
            )
        )
    );

$enrolledAt =
    trim(
        (string) (
            $enrollment['enrolled_at']
            ?? ''
        )
    );
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
                    href="/academic-enrollments/<?= $enrollmentId ?>"
                    class="text-decoration-none small"
                >
                    <i class="bi bi-arrow-left me-1"></i>

                    Retour à l'inscription
                </a>

            </div>

            <h1 class="h3 mb-1">
                Modifier l'inscription académique
            </h1>

            <p class="text-muted mb-0">
                <?php if ($isUniversityContext): ?>
                    Modifiez le rattachement académique
                    de l'étudiant dans votre université.
                <?php else: ?>
                    Modifiez le rattachement académique
                    de l'étudiant dans MedTrack.
                <?php endif; ?>
            </p>

        </div>

    </div>


    <!-- Information -->

    <div
        class="alert alert-info
               border-0 shadow-sm mb-4"
    >

        <div class="d-flex gap-3">

            <div>
                <i class="bi bi-info-circle fs-4"></i>
            </div>

            <div>

                <div class="fw-semibold mb-1">
                    Cohérence académique
                </div>

                <div class="small">
                    Le programme doit appartenir à
                    l'université sélectionnée. Une cohorte,
                    lorsqu'elle est renseignée, doit
                    correspondre au programme et à
                    l'année académique.
                </div>

            </div>

        </div>

    </div>


    <form
        id="academicEnrollmentForm"
        action="/academic-enrollments/<?= $enrollmentId ?>"
        method="post"
        novalidate
    >

        <!-- CSRF -->

        <input
            type="hidden"
            name="_token"
            value="<?= htmlspecialchars(
                (string) ($csrfToken ?? ''),
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
        >


        <!-- AJAX alert -->

        <div
            id="academicEnrollmentFormAlert"
            class="alert d-none"
            role="alert"
        ></div>


        <div class="row g-4">

            <!-- Main column -->

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

                        <label
                            for="student_id"
                            class="form-label"
                        >
                            Étudiant

                            <span class="text-danger">
                                *
                            </span>
                        </label>

                        <select
                            class="form-select"
                            id="student_id"
                            name="student_id"
                            required
                        >

                            <option value="">
                                Sélectionnez un étudiant
                            </option>

                            <?php foreach (
                                $students as $student
                            ): ?>

                                <?php
                                $studentId =
                                    (int) (
                                        $student['id']
                                        ?? 0
                                    );

                                $firstName =
                                    trim(
                                        (string) (
                                            $student['first_name']
                                            ?? ''
                                        )
                                    );

                                $middleName =
                                    trim(
                                        (string) (
                                            $student['middle_name']
                                            ?? ''
                                        )
                                    );

                                $lastName =
                                    trim(
                                        (string) (
                                            $student['last_name']
                                            ?? ''
                                        )
                                    );

                                $fullName =
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

                                $nationalNumber =
                                    trim(
                                        (string) (
                                            $student[
                                                'national_student_number'
                                            ]
                                            ?? ''
                                        )
                                    );
                                ?>

                                <option
                                    value="<?= $studentId ?>"
                                    <?= $studentId === $selectedStudentId
                                        ? 'selected'
                                        : '' ?>
                                >

                                    <?= htmlspecialchars(
                                        $fullName !== ''
                                            ? $fullName
                                            : 'Étudiant #' . $studentId,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                    <?php if (
                                        $nationalNumber !== ''
                                    ): ?>

                                        <?= htmlspecialchars(
                                            ' — ' . $nationalNumber,
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>

                                    <?php endif; ?>

                                </option>

                            <?php endforeach; ?>

                        </select>

                        <div class="invalid-feedback">
                            Veuillez sélectionner un étudiant.
                        </div>

                    </div>

                </div>


                <!-- Academic structure -->

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

                        <div class="row g-3">


                            <!-- University -->

                            <?php if ($isPlatform): ?>

                                <div class="col-md-6">

                                    <label
                                        for="university_id"
                                        class="form-label"
                                    >
                                        Université

                                        <span class="text-danger">
                                            *
                                        </span>
                                    </label>

                                    <select
                                        class="form-select"
                                        id="university_id"
                                        name="university_id"
                                        required
                                    >

                                        <option value="">
                                            Sélectionnez une université
                                        </option>

                                        <?php foreach (
                                            $universities
                                            as $university
                                        ): ?>

                                            <?php
                                            $universityId =
                                                (int) (
                                                    $university[
                                                        'organization_id'
                                                    ]
                                                    ?? $university['id']
                                                    ?? 0
                                                );

                                            $universityCode =
                                                trim(
                                                    (string) (
                                                        $university['code']
                                                        ?? ''
                                                    )
                                                );

                                            $universityName =
                                                trim(
                                                    (string) (
                                                        $university['name']
                                                        ?? ''
                                                    )
                                                );
                                            ?>

                                            <option
                                                value="<?= $universityId ?>"
                                                <?= $universityId === $selectedUniversityId
                                                    ? 'selected'
                                                    : '' ?>
                                            >

                                                <?= htmlspecialchars(
                                                    $universityName !== ''
                                                        ? $universityName
                                                        : 'Université #'
                                                            . $universityId,
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>

                                                <?php if (
                                                    $universityCode !== ''
                                                ): ?>

                                                    <?= htmlspecialchars(
                                                        ' — '
                                                        . $universityCode,
                                                        ENT_QUOTES,
                                                        'UTF-8'
                                                    ) ?>

                                                <?php endif; ?>

                                            </option>

                                        <?php endforeach; ?>

                                    </select>

                                    <div class="invalid-feedback">
                                        Veuillez sélectionner une université.
                                    </div>

                                </div>

                            <?php else: ?>

                                <input
                                    type="hidden"
                                    id="university_id"
                                    name="university_id"
                                    value="<?= $selectedUniversityId ?>"
                                >

                                <div class="col-md-6">

                                    <label class="form-label">
                                        Université
                                    </label>

                                    <div class="form-control bg-body-tertiary">
                                        Université active
                                    </div>

                                    <div class="form-text">
                                        L'université de l'inscription
                                        ne peut pas être modifiée depuis
                                        l'espace université.
                                    </div>

                                </div>

                            <?php endif; ?>


                            <!-- Academic year -->

                            <div class="col-md-6">

                                <label
                                    for="academic_year_id"
                                    class="form-label"
                                >
                                    Année académique

                                    <span class="text-danger">
                                        *
                                    </span>
                                </label>

                                <select
                                    class="form-select"
                                    id="academic_year_id"
                                    name="academic_year_id"
                                    required
                                >

                                    <option value="">
                                        Sélectionnez une année
                                    </option>

                                    <?php foreach (
                                        $academicYears
                                        as $year
                                    ): ?>

                                        <?php
                                        $yearId =
                                            (int) (
                                                $year['id']
                                                ?? 0
                                            );

                                        $yearLabel =
                                            trim(
                                                (string) (
                                                    $year['label']
                                                    ?? ''
                                                )
                                            );

                                        $yearStatus =
                                            trim(
                                                (string) (
                                                    $year['status']
                                                    ?? ''
                                                )
                                            );
                                        ?>

                                        <option
                                            value="<?= $yearId ?>"
                                            <?= $yearId === $selectedAcademicYearId
                                                ? 'selected'
                                                : '' ?>
                                        >

                                            <?= htmlspecialchars(
                                                $yearLabel,
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>

                                            <?php if (
                                                $yearStatus !== ''
                                            ): ?>

                                                <?= htmlspecialchars(
                                                    ' — '
                                                    . $yearStatus,
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>

                                            <?php endif; ?>

                                        </option>

                                    <?php endforeach; ?>

                                </select>

                                <div class="invalid-feedback">
                                    Veuillez sélectionner une année académique.
                                </div>

                            </div>


                            <!-- Program -->

                            <div class="col-md-6">

                                <label
                                    for="academic_program_id"
                                    class="form-label"
                                >
                                    Programme académique

                                    <span class="text-danger">
                                        *
                                    </span>
                                </label>

                                <select
                                    class="form-select"
                                    id="academic_program_id"
                                    name="academic_program_id"
                                    required
                                >

                                    <option value="">
                                        Sélectionnez un programme
                                    </option>

                                    <?php foreach (
                                        $academicPrograms
                                        as $program
                                    ): ?>

                                        <?php
                                        $programId =
                                            (int) (
                                                $program['id']
                                                ?? 0
                                            );

                                        $programUniversityId =
                                            (int) (
                                                $program[
                                                    'university_id'
                                                ]
                                                ?? 0
                                            );

                                        $programCode =
                                            trim(
                                                (string) (
                                                    $program['code']
                                                    ?? ''
                                                )
                                            );

                                        $programName =
                                            trim(
                                                (string) (
                                                    $program['name']
                                                    ?? ''
                                                )
                                            );
                                        ?>

                                        <option
                                            value="<?= $programId ?>"
                                            data-university-id="<?= $programUniversityId ?>"
                                            <?= $programId === $selectedProgramId
                                                ? 'selected'
                                                : '' ?>
                                        >

                                            <?= htmlspecialchars(
                                                $programCode !== ''
                                                    ? $programCode
                                                        . ' — '
                                                        . $programName
                                                    : $programName,
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>

                                        </option>

                                    <?php endforeach; ?>

                                </select>

                                <div class="invalid-feedback">
                                    Veuillez sélectionner un programme.
                                </div>

                            </div>


                            <!-- Study level -->

                            <div class="col-md-6">

                                <label
                                    for="study_level_id"
                                    class="form-label"
                                >
                                    Niveau d'études

                                    <span class="text-danger">
                                        *
                                    </span>
                                </label>

                                <select
                                    class="form-select"
                                    id="study_level_id"
                                    name="study_level_id"
                                    required
                                >

                                    <option value="">
                                        Sélectionnez un niveau
                                    </option>

                                    <?php foreach (
                                        $studyLevels
                                        as $level
                                    ): ?>

                                        <?php
                                        $levelId =
                                            (int) (
                                                $level['id']
                                                ?? 0
                                            );

                                        $levelCode =
                                            trim(
                                                (string) (
                                                    $level['code']
                                                    ?? ''
                                                )
                                            );

                                        $levelName =
                                            trim(
                                                (string) (
                                                    $level['name']
                                                    ?? ''
                                                )
                                            );
                                        ?>

                                        <option
                                            value="<?= $levelId ?>"
                                            <?= $levelId === $selectedStudyLevelId
                                                ? 'selected'
                                                : '' ?>
                                        >

                                            <?= htmlspecialchars(
                                                $levelCode !== ''
                                                    ? $levelCode
                                                        . ' — '
                                                        . $levelName
                                                    : $levelName,
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>

                                        </option>

                                    <?php endforeach; ?>

                                </select>

                                <div class="invalid-feedback">
                                    Veuillez sélectionner un niveau.
                                </div>

                            </div>


                            <!-- Cohort -->

                            <div class="col-12">

                                <label
                                    for="cohort_id"
                                    class="form-label"
                                >
                                    Cohorte
                                </label>

                                <select
                                    class="form-select"
                                    id="cohort_id"
                                    name="cohort_id"
                                >

                                    <option value="">
                                        Aucune cohorte
                                    </option>

                                    <?php foreach (
                                        $cohorts
                                        as $cohort
                                    ): ?>

                                        <?php
                                        $cohortId =
                                            (int) (
                                                $cohort['id']
                                                ?? 0
                                            );

                                        $cohortProgramId =
                                            (int) (
                                                $cohort[
                                                    'academic_program_id'
                                                ]
                                                ?? 0
                                            );

                                        $cohortAcademicYearId =
                                            (int) (
                                                $cohort[
                                                    'academic_year_id'
                                                ]
                                                ?? 0
                                            );

                                        $cohortName =
                                            trim(
                                                (string) (
                                                    $cohort['name']
                                                    ?? ''
                                                )
                                            );
                                        ?>

                                        <option
                                            value="<?= $cohortId ?>"
                                            data-program-id="<?= $cohortProgramId ?>"
                                            data-academic-year-id="<?= $cohortAcademicYearId ?>"
                                            <?= $cohortId === $selectedCohortId
                                                ? 'selected'
                                                : '' ?>
                                        >

                                            <?= htmlspecialchars(
                                                $cohortName,
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>

                                        </option>

                                    <?php endforeach; ?>

                                </select>

                                <div class="form-text">
                                    Facultatif. Seules les cohortes
                                    correspondant au programme et à
                                    l'année seront disponibles.
                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                <!-- Registration information -->

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

                        <div class="row g-3">


                            <!-- Registration number -->

                            <div class="col-md-6">

                                <label
                                    for="registration_number"
                                    class="form-label"
                                >
                                    Matricule

                                    <span class="text-danger">
                                        *
                                    </span>
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="registration_number"
                                    name="registration_number"
                                    maxlength="80"
                                    autocomplete="off"
                                    value="<?= htmlspecialchars(
                                        $registrationNumber,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                    required
                                >

                                <div class="form-text">
                                    Le matricule doit être unique
                                    dans l'université pour cette
                                    année académique.
                                </div>

                                <div class="invalid-feedback">
                                    Le matricule est obligatoire.
                                </div>

                            </div>


                            <!-- Enrollment date -->

                            <div class="col-md-6">

                                <label
                                    for="enrolled_at"
                                    class="form-label"
                                >
                                    Date d'inscription
                                </label>

                                <input
                                    type="date"
                                    class="form-control"
                                    id="enrolled_at"
                                    name="enrolled_at"
                                    value="<?= htmlspecialchars(
                                        $enrolledAt,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                >

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- Sidebar -->

            <div class="col-xl-4">


                <!-- Status -->

                <div
                    class="card border-0
                           shadow-sm mb-4"
                >

                    <div
                        class="card-header
                               bg-transparent py-3"
                    >

                        <h2 class="h5 mb-0">

                            <i
                                class="bi bi-activity
                                       me-2"
                            ></i>

                            Statut

                        </h2>

                    </div>


                    <div class="card-body">

                        <label
                            for="status"
                            class="form-label"
                        >
                            Statut de l'inscription
                        </label>

                        <select
                            class="form-select"
                            id="status"
                            name="status"
                            required
                        >

                            <option
                                value="PENDING"
                                <?= $selectedStatus === 'PENDING'
                                    ? 'selected'
                                    : '' ?>
                            >
                                En attente
                            </option>

                            <option
                                value="ACTIVE"
                                <?= $selectedStatus === 'ACTIVE'
                                    ? 'selected'
                                    : '' ?>
                            >
                                Active
                            </option>

                            <option
                                value="SUSPENDED"
                                <?= $selectedStatus === 'SUSPENDED'
                                    ? 'selected'
                                    : '' ?>
                            >
                                Suspendue
                            </option>

                            <option
                                value="COMPLETED"
                                <?= $selectedStatus === 'COMPLETED'
                                    ? 'selected'
                                    : '' ?>
                            >
                                Terminée
                            </option>

                            <option
                                value="CANCELLED"
                                <?= $selectedStatus === 'CANCELLED'
                                    ? 'selected'
                                    : '' ?>
                            >
                                Annulée
                            </option>

                        </select>

                    </div>

                </div>


                <!-- Record -->

                <div
                    class="card border-0
                           shadow-sm mb-4"
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

                            Inscription

                        </h2>

                    </div>


                    <div class="card-body">

                        <div class="small text-muted mb-1">
                            Identifiant
                        </div>

                        <div class="fw-semibold mb-3">
                            #<?= $enrollmentId ?>
                        </div>

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

                </div>


                <!-- Actions -->

                <div
                    class="card border-0 shadow-sm"
                >

                    <div class="card-body">

                        <div class="d-grid gap-2">

                            <button
                                type="submit"
                                class="btn btn-primary"
                                id="academicEnrollmentSubmitButton"
                            >

                                <span
                                    id="academicEnrollmentSubmitIcon"
                                >
                                    <i
                                        class="bi bi-check-lg
                                               me-1"
                                    ></i>
                                </span>

                                <span
                                    id="academicEnrollmentSubmitText"
                                >
                                    Enregistrer les modifications
                                </span>

                            </button>

                            <a
                                href="/academic-enrollments/<?= $enrollmentId ?>"
                                class="btn btn-outline-secondary"
                            >
                                Annuler
                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>