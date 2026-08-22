<?php

declare(strict_types=1);

/**
 * @var array $enrollment
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

$selectedStudentId =
    (int) (
        $enrollment['student_id']
        ?? 0
    );

$selectedUniversityId =
    $isUniversityContext
        ? (int) ($activeUniversityId ?? 0)
        : (int) (
            $enrollment['university_id']
            ?? 0
        );

$selectedAcademicProgramId =
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
    (int) (
        $enrollment['cohort_id']
        ?? 0
    );

$selectedRegistrationNumber =
    trim(
        (string) (
            $enrollment['registration_number']
            ?? ''
        )
    );

$selectedEnrolledAt =
    trim(
        (string) (
            $enrollment['enrolled_at']
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

$activeUniversityName = '';

if (
    $isUniversityContext
    && $selectedUniversityId > 0
) {
    foreach ($academicPrograms as $program) {
        if (
            (int) ($program['university_id'] ?? 0)
            !== $selectedUniversityId
        ) {
            continue;
        }

        $activeUniversityName =
            trim(
                (string) (
                    $program['university_name']
                    ?? ''
                )
            );

        if ($activeUniversityName !== '') {
            break;
        }
    }
}
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
                Nouvelle inscription académique
            </h1>

            <p class="text-muted mb-0">
                Rattachez un étudiant à son parcours
                académique.
            </p>

        </div>
    </div>


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
                    <?php if ($isUniversityContext): ?>
                        L'université est imposée par votre
                        contexte actif. Les programmes proposés
                        appartiennent à cette université. Les
                        cohortes dépendent ensuite du programme
                        et de l'année académique.
                    <?php else: ?>
                        Les programmes proposés dépendent de
                        l'université sélectionnée. Les cohortes
                        dépendent ensuite du programme et de
                        l'année académique.
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>


    <form
        id="academicEnrollmentForm"
        action="/academic-enrollments"
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
                                class="bi bi-person-search
                                       me-2"
                            ></i>

                            Étudiant
                        </h2>
                    </div>

                    <div class="card-body">

                        <input
                            type="hidden"
                            id="student_id"
                            name="student_id"
                            value="<?= $selectedStudentId > 0
                                ? $selectedStudentId
                                : '' ?>"
                            required
                        >

                        <div
                            id="selectedStudentCard"
                            class="<?= $selectedStudentId > 0
                                ? ''
                                : 'd-none' ?>"
                        >
                            <?php
                            $selectedStudentName =
                                $selectedStudentId > 0
                                    ? 'Étudiant sélectionné'
                                    : '';
                            ?>

                            <div
                                class="alert alert-success
                                       border-0 mb-3"
                            >
                                <div
                                    class="d-flex
                                           justify-content-between
                                           align-items-start gap-3"
                                >
                                    <div>
                                        <div
                                            class="small text-muted mb-1"
                                        >
                                            Étudiant sélectionné
                                        </div>

                                        <div
                                            class="fw-semibold"
                                            id="selectedStudentName"
                                        >
                                            <?= htmlspecialchars(
                                                $selectedStudentName,
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        </div>

                                        <div
                                            class="small text-muted"
                                            id="selectedStudentDetails"
                                        ></div>
                                    </div>

                                    <button
                                        type="button"
                                        class="btn btn-sm
                                               btn-outline-secondary"
                                        id="changeStudentButton"
                                    >
                                        Changer
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div
                            id="studentSearchPanel"
                            class="<?= $selectedStudentId > 0
                                ? 'd-none'
                                : '' ?>"
                        >

                            <label
                                for="studentSearchInput"
                                class="form-label"
                            >
                                Rechercher un étudiant
                            </label>

                            <div class="input-group">

                                <input
                                    type="search"
                                    class="form-control"
                                    id="studentSearchInput"
                                    maxlength="190"
                                    autocomplete="off"
                                    placeholder="N° national, nom, prénom, email ou téléphone"
                                >

                                <button
                                    type="button"
                                    class="btn btn-outline-primary"
                                    id="studentSearchButton"
                                >
                                    <i
                                        class="bi bi-search me-1"
                                    ></i>

                                    Rechercher
                                </button>

                            </div>

                            <div class="form-text">
                                Saisissez au moins 3 caractères.
                                MedTrack recherche d'abord une
                                identité existante afin d'éviter
                                les doublons.
                            </div>

                            <div
                                id="studentSearchAlert"
                                class="alert d-none mt-3 mb-0"
                                role="alert"
                            ></div>

                            <div
                                id="studentSearchLoading"
                                class="d-none text-center py-4"
                            >
                                <div
                                    class="spinner-border
                                           spinner-border-sm"
                                    role="status"
                                ></div>

                                <span class="ms-2">
                                    Recherche...
                                </span>
                            </div>

                            <div
                                id="studentSearchResults"
                                class="list-group mt-3"
                            ></div>

                            <div
                                id="studentNotFoundActions"
                                class="d-none mt-3"
                            >
                                <div class="alert alert-light border mb-0">

                                    <div class="fw-semibold mb-1">
                                        Étudiant introuvable ?
                                    </div>

                                    <div class="small text-muted mb-3">
                                        Vérifiez les informations recherchées
                                        avant de créer une nouvelle identité.
                                    </div>

                                    <button
                                        type="button"
                                        class="btn btn-outline-primary btn-sm"
                                        id="createStudentIdentityButton"
                                    >
                                        <i class="bi bi-person-plus me-1"></i>
                                        Créer une nouvelle identité
                                    </button>

                                </div>
                            </div>


                            <!--
                            |--------------------------------------------------------------------------
                            | Création rapide d'une identité étudiante
                            |--------------------------------------------------------------------------
                            -->

                            <div
                                id="studentIdentityCreationPanel"
                                class="d-none mt-4"
                                data-endpoint="/academic-enrollments/student-identities"
                            >
                                <div class="card border-primary">

                                    <div class="card-header bg-transparent">

                                        <div
                                            class="d-flex justify-content-between
                                                   align-items-center gap-3"
                                        >
                                            <div>
                                                <div class="fw-semibold">
                                                    <i class="bi bi-person-plus me-2"></i>
                                                    Nouvelle identité étudiante
                                                </div>

                                                <div class="small text-muted mt-1">
                                                    Créez uniquement l'identité.
                                                    L'inscription académique sera
                                                    enregistrée ensuite.
                                                </div>
                                            </div>

                                            <button
                                                type="button"
                                                class="btn-close"
                                                id="cancelStudentIdentityButton"
                                                aria-label="Fermer"
                                            ></button>
                                        </div>

                                    </div>

                                    <div class="card-body">

                                        <div
                                            id="studentIdentityAlert"
                                            class="alert d-none"
                                            role="alert"
                                        ></div>

                                        <div class="row g-3">

                                            <div class="col-md-6">
                                                <label
                                                    for="studentIdentityFirstName"
                                                    class="form-label"
                                                >
                                                    Prénom
                                                    <span class="text-danger">*</span>
                                                </label>

                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    id="studentIdentityFirstName"
                                                    maxlength="150"
                                                    autocomplete="given-name"
                                                >

                                                <div class="invalid-feedback">
                                                    Le prénom est obligatoire.
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label
                                                    for="studentIdentityMiddleName"
                                                    class="form-label"
                                                >
                                                    Postnom
                                                </label>

                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    id="studentIdentityMiddleName"
                                                    maxlength="150"
                                                    autocomplete="additional-name"
                                                >
                                            </div>

                                            <div class="col-md-6">
                                                <label
                                                    for="studentIdentityLastName"
                                                    class="form-label"
                                                >
                                                    Nom
                                                    <span class="text-danger">*</span>
                                                </label>

                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    id="studentIdentityLastName"
                                                    maxlength="150"
                                                    autocomplete="family-name"
                                                >

                                                <div class="invalid-feedback">
                                                    Le nom est obligatoire.
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label
                                                    for="studentIdentityGender"
                                                    class="form-label"
                                                >
                                                    Sexe
                                                </label>

                                                <select
                                                    class="form-select"
                                                    id="studentIdentityGender"
                                                >
                                                    <option value="">
                                                        Non renseigné
                                                    </option>

                                                    <option value="M">
                                                        Masculin
                                                    </option>

                                                    <option value="F">
                                                        Féminin
                                                    </option>

                                                    <option value="OTHER">
                                                        Autre
                                                    </option>

                                                    <option value="UNSPECIFIED">
                                                        Non spécifié
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label
                                                    for="studentIdentityBirthDate"
                                                    class="form-label"
                                                >
                                                    Date de naissance
                                                    <span class="text-danger">*</span>
                                                </label>

                                                <input
                                                    type="date"
                                                    class="form-control"
                                                    id="studentIdentityBirthDate"
                                                >

                                                <div class="invalid-feedback">
                                                    La date de naissance est obligatoire.
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label
                                                    for="studentIdentityBirthPlace"
                                                    class="form-label"
                                                >
                                                    Lieu de naissance
                                                </label>

                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    id="studentIdentityBirthPlace"
                                                    maxlength="150"
                                                >
                                            </div>

                                            <div class="col-md-6">
                                                <label
                                                    for="studentIdentityNationality"
                                                    class="form-label"
                                                >
                                                    Nationalité
                                                </label>

                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    id="studentIdentityNationality"
                                                    maxlength="100"
                                                >
                                            </div>

                                            <div class="col-md-6">
                                                <label
                                                    for="studentIdentityNationalNumber"
                                                    class="form-label"
                                                >
                                                    N° étudiant national
                                                </label>

                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    id="studentIdentityNationalNumber"
                                                    maxlength="80"
                                                    autocomplete="off"
                                                >
                                            </div>

                                            <div class="col-md-6">
                                                <label
                                                    for="studentIdentityEmail"
                                                    class="form-label"
                                                >
                                                    Adresse email
                                                </label>

                                                <input
                                                    type="email"
                                                    class="form-control"
                                                    id="studentIdentityEmail"
                                                    maxlength="190"
                                                    autocomplete="email"
                                                >
                                            </div>

                                            <div class="col-md-6">
                                                <label
                                                    for="studentIdentityPhone"
                                                    class="form-label"
                                                >
                                                    Téléphone
                                                </label>

                                                <input
                                                    type="tel"
                                                    class="form-control"
                                                    id="studentIdentityPhone"
                                                    maxlength="30"
                                                    autocomplete="tel"
                                                >
                                            </div>

                                        </div>

                                        <div
                                            class="d-flex justify-content-end
                                                   gap-2 mt-4"
                                        >
                                            <button
                                                type="button"
                                                class="btn btn-outline-secondary"
                                                id="cancelStudentIdentityCreationButton"
                                            >
                                                Annuler
                                            </button>

                                            <button
                                                type="button"
                                                class="btn btn-primary"
                                                id="saveStudentIdentityButton"
                                            >
                                                <span
                                                    id="saveStudentIdentityIcon"
                                                >
                                                    <i class="bi bi-person-check me-1"></i>
                                                </span>

                                                <span
                                                    id="saveStudentIdentityText"
                                                >
                                                    Créer l'identité
                                                </span>
                                            </button>
                                        </div>

                                    </div>

                                </div>
                            </div>

                        </div>

                        <div
                            class="invalid-feedback"
                            id="studentSelectionFeedback"
                        >
                            Veuillez rechercher puis sélectionner
                            un étudiant.
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

                                <?php if ($isUniversityContext): ?>

                                    <input
                                        type="hidden"
                                        id="university_id"
                                        name="university_id"
                                        value="<?= $selectedUniversityId ?>"
                                    >

                                    <div
                                        class="form-control bg-body-tertiary"
                                        aria-readonly="true"
                                    >
                                        <div class="fw-semibold">
                                            <?= htmlspecialchars(
                                                $activeUniversityName !== ''
                                                    ? $activeUniversityName
                                                    : 'Université active',
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        </div>

                                        <small class="text-muted">
                                            Contexte universitaire imposé
                                            par MedTrack
                                        </small>
                                    </div>

                                <?php else: ?>

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

                                <?php endif; ?>

                            </div>


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
                                    disabled
                                    required
                                >

                                    <option value="">
                                        Sélectionnez d'abord une université
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
                                            <?= $programId === $selectedAcademicProgramId
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
                                    disabled
                                >

                                    <option value="">
                                        Sélectionnez d'abord
                                        un programme et une année
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
                                    compatibles avec le programme
                                    et l'année seront proposées.
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
                                        $selectedRegistrationNumber,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                    required
                                >

                                <div class="form-text">
                                    Le matricule doit être unique
                                    dans l'université pour cette année.
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
                                        $selectedEnrolledAt,
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


                <!-- Summary -->

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
                                class="bi bi-diagram-3
                                       me-2"
                            ></i>

                            Rattachement
                        </h2>
                    </div>

                    <div class="card-body">

                        <div class="small text-muted">

                            <div class="mb-3">
                                <strong>
                                    Université
                                </strong>
                                <br>
                                <?= $isUniversityContext
                                    ? 'est imposée par le contexte actif.'
                                    : 'détermine les programmes disponibles.' ?>
                            </div>

                            <div class="mb-3">
                                <strong>
                                    Programme + année
                                </strong>
                                <br>
                                déterminent les cohortes
                                disponibles.
                            </div>

                            <div>
                                Les mêmes contrôles sont
                                appliqués côté serveur.
                            </div>

                        </div>

                    </div>

                </div>


                <!-- Actions -->

                <div class="card border-0 shadow-sm">

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
                                    Enregistrer
                                </span>

                            </button>

                            <a
                                href="/academic-enrollments"
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