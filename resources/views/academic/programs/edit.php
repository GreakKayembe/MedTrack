<?php

declare(strict_types=1);

$program =
    is_array($program ?? null)
        ? $program
        : [];

$universities =
    is_array($universities ?? null)
        ? $universities
        : [];

$faculties =
    is_array($faculties ?? null)
        ? $faculties
        : [];

$isPlatform =
    (bool) ($isPlatform ?? false);

$isUniversityContext =
    (bool) ($isUniversityContext ?? false);

$activeUniversityId =
    isset($activeUniversityId)
        ? (int) $activeUniversityId
        : null;

$programId = (int) (
    $program['id']
    ?? 0
);

$selectedUniversityId = (int) (
    $program['university_id']
    ?? 0
);

$selectedFacultyId = isset($program['faculty_id'])
    && $program['faculty_id'] !== null
        ? (int) $program['faculty_id']
        : null;

$programCode = (string) (
    $program['code']
    ?? ''
);

$programName = (string) (
    $program['name']
    ?? ''
);

$disciplineCode = (string) (
    $program['discipline_code']
    ?? ''
);

$durationYears = $program['duration_years']
    ?? '';

$programStatus = (string) (
    $program['status']
    ?? 'ACTIVE'
);

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

            <div class="mb-2">
                <a
                    href="/academic-programs/<?= $programId ?>"
                    class="text-decoration-none text-muted"
                >
                    <i class="bi bi-arrow-left me-1"></i>
                    Retour au programme
                </a>
            </div>

            <h4 class="mb-1 fw-bold">
                Modifier le programme académique
            </h4>

            <p class="text-muted mb-0">
                Modifiez les informations académiques
                de <?= htmlspecialchars($programName) ?>.
            </p>

        </div>

        <a
            href="/academic-programs/<?= $programId ?>"
            class="btn btn-outline-secondary"
        >
            <i class="bi bi-eye me-1"></i>
            Consulter
        </a>
    </div>


    <div class="row justify-content-center">

        <div class="col-xl-9 col-lg-10">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white border-0 pt-4 px-4">

                    <div class="d-flex align-items-center gap-3">

                        <div
                            class="rounded-circle
                                   bg-primary-subtle
                                   text-primary
                                   d-flex
                                   align-items-center
                                   justify-content-center
                                   flex-shrink-0"
                            style="
                                width: 48px;
                                height: 48px;
                            "
                        >
                            <i class="bi bi-pencil-square fs-4"></i>
                        </div>

                        <div>

                            <h5 class="fw-semibold mb-1">
                                Informations du programme
                            </h5>

                            <p class="text-muted small mb-0">
                                Mettez à jour les informations
                                nécessaires puis enregistrez.
                            </p>

                        </div>

                    </div>

                </div>


                <div class="card-body p-4">

                    <!-- Alert -->
                    <div
                        id="academicProgramFormAlert"
                        class="alert d-none"
                        role="alert"
                    ></div>


                    <form
                        id="academicProgramForm"
                        action="/academic-programs/<?= $programId ?>"
                        method="POST"
                        novalidate
                    >

                        <!-- CSRF -->
                        <input
                            type="hidden"
                            name="_token"
                            value="<?= htmlspecialchars(
                                (string) ($csrfToken ?? '')
                            ) ?>"
                        >


                        <!-- Structure académique -->
                        <div class="mb-4">

                            <h6 class="fw-semibold mb-3">
                                <i class="bi bi-building me-1"></i>
                                Structure académique
                            </h6>

                            <div class="row g-3">

                                <!-- Université -->
                                <div class="col-md-6">

                                    <?php if ($isPlatform): ?>

                                        <label
                                            for="university_id"
                                            class="form-label"
                                        >
                                            Université
                                            <span class="text-danger">*</span>
                                        </label>

                                        <select
                                            id="university_id"
                                            name="university_id"
                                            class="form-select"
                                            required
                                        >

                                            <option value="">
                                                Sélectionner une université
                                            </option>

                                            <?php foreach ($universities as $university): ?>

                                                <?php
                                                $universityId = (int) (
                                                    $university['organization_id']
                                                    ?? $university['id']
                                                    ?? 0
                                                );

                                                $universityName = (string) (
                                                    $university['name']
                                                    ?? ''
                                                );

                                                $universityCode = (string) (
                                                    $university['code']
                                                    ?? ''
                                                );
                                                ?>

                                                <option
                                                    value="<?= $universityId ?>"
                                                    <?= $universityId === $selectedUniversityId
                                                        ? 'selected'
                                                        : '' ?>
                                                >
                                                    <?= htmlspecialchars(
                                                        $universityName
                                                    ) ?>

                                                    <?php if ($universityCode !== ''): ?>
                                                        (<?= htmlspecialchars(
                                                            $universityCode
                                                        ) ?>)
                                                    <?php endif; ?>
                                                </option>

                                            <?php endforeach; ?>

                                        </select>

                                        <div class="invalid-feedback">
                                            Veuillez sélectionner une université.
                                        </div>

                                    <?php else: ?>

                                        <?php
                                        $effectiveUniversityId =
                                            $activeUniversityId
                                            ?? $selectedUniversityId;

                                        $universityName =
                                            (string) (
                                                $program['university_name']
                                                ?? 'Université active'
                                            );

                                        $universityCode =
                                            (string) (
                                                $program['university_code']
                                                ?? ''
                                            );
                                        ?>

                                        <label class="form-label">
                                            Université
                                        </label>

                                        <input
                                            type="hidden"
                                            id="university_id"
                                            name="university_id"
                                            value="<?= (int) $effectiveUniversityId ?>"
                                        >

                                        <div class="form-control bg-light">

                                            <i class="bi bi-building me-1"></i>

                                            <?= htmlspecialchars(
                                                $universityName
                                            ) ?>

                                            <?php if ($universityCode !== ''): ?>

                                                <span class="text-muted">
                                                    (<?= htmlspecialchars(
                                                        $universityCode
                                                    ) ?>)
                                                </span>

                                            <?php endif; ?>

                                        </div>

                                        <div class="form-text">
                                            Le programme reste rattaché à
                                            votre université active.
                                        </div>

                                    <?php endif; ?>

                                </div>


                                <!-- Faculté -->
                                <div class="col-md-6">

                                    <label
                                        for="faculty_id"
                                        class="form-label"
                                    >
                                        Faculté
                                    </label>

                                    <select
                                        id="faculty_id"
                                        name="faculty_id"
                                        class="form-select"
                                    >

                                        <option value="">
                                            Aucune faculté / rattachement direct
                                        </option>

                                        <?php foreach ($faculties as $faculty): ?>

                                            <?php
                                            $facultyId = (int) (
                                                $faculty['id']
                                                ?? 0
                                            );

                                            $facultyUniversityId = (int) (
                                                $faculty['university_id']
                                                ?? 0
                                            );

                                            $facultyName = (string) (
                                                $faculty['name']
                                                ?? ''
                                            );

                                            $facultyCode = (string) (
                                                $faculty['code']
                                                ?? ''
                                            );

                                            $effectiveUniversityId =
                                                $activeUniversityId
                                                ?? $selectedUniversityId;

                                            $belongsToSelectedUniversity =
                                                $facultyUniversityId
                                                === $effectiveUniversityId;

                                            $isSelected =
                                                $selectedFacultyId !== null
                                                && $facultyId
                                                    === $selectedFacultyId;
                                            ?>

                                            <option
                                                value="<?= $facultyId ?>"
                                                data-university-id="<?= $facultyUniversityId ?>"
                                                <?= $isSelected
                                                    ? 'selected'
                                                    : '' ?>
                                                <?= !$belongsToSelectedUniversity
                                                    ? 'hidden disabled'
                                                    : '' ?>
                                            >
                                                <?= htmlspecialchars(
                                                    $facultyName
                                                ) ?>

                                                <?php if ($facultyCode !== ''): ?>
                                                    (<?= htmlspecialchars(
                                                        $facultyCode
                                                    ) ?>)
                                                <?php endif; ?>
                                            </option>

                                        <?php endforeach; ?>

                                    </select>

                                    <div class="form-text">
                                        <?php if ($isUniversityContext): ?>
                                            La faculté est facultative.
                                            Seules les facultés de votre université
                                            peuvent être utilisées.
                                        <?php else: ?>
                                            La faculté est facultative.
                                            Elle doit appartenir à
                                            l’université sélectionnée.
                                        <?php endif; ?>
                                    </div>

                                </div>

                            </div>

                        </div>


                        <hr class="my-4">


                        <!-- Identification -->
                        <div class="mb-4">

                            <h6 class="fw-semibold mb-3">
                                <i class="bi bi-card-heading me-1"></i>
                                Identification
                            </h6>

                            <div class="row g-3">

                                <!-- Code -->
                                <div class="col-md-4">

                                    <label
                                        for="code"
                                        class="form-label"
                                    >
                                        Code
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input
                                        type="text"
                                        id="code"
                                        name="code"
                                        class="form-control"
                                        maxlength="50"
                                        value="<?= htmlspecialchars(
                                            $programCode
                                        ) ?>"
                                        required
                                    >

                                    <div class="invalid-feedback">
                                        Le code du programme est obligatoire.
                                    </div>

                                </div>


                                <!-- Nom -->
                                <div class="col-md-8">

                                    <label
                                        for="name"
                                        class="form-label"
                                    >
                                        Nom du programme
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input
                                        type="text"
                                        id="name"
                                        name="name"
                                        class="form-control"
                                        maxlength="255"
                                        value="<?= htmlspecialchars(
                                            $programName
                                        ) ?>"
                                        required
                                    >

                                    <div class="invalid-feedback">
                                        Le nom du programme est obligatoire.
                                    </div>

                                </div>


                                <!-- Discipline -->
                                <div class="col-md-6">

                                    <label
                                        for="discipline_code"
                                        class="form-label"
                                    >
                                        Code de discipline
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input
                                        type="text"
                                        id="discipline_code"
                                        name="discipline_code"
                                        class="form-control"
                                        maxlength="80"
                                        value="<?= htmlspecialchars(
                                            $disciplineCode
                                        ) ?>"
                                        required
                                    >

                                    <div class="form-text">
                                        Code métier représentant
                                        la discipline académique.
                                    </div>

                                    <div class="invalid-feedback">
                                        Le code de discipline est obligatoire.
                                    </div>

                                </div>


                                <!-- Durée -->
                                <div class="col-md-3">

                                    <label
                                        for="duration_years"
                                        class="form-label"
                                    >
                                        Durée
                                    </label>

                                    <div class="input-group">

                                        <input
                                            type="number"
                                            id="duration_years"
                                            name="duration_years"
                                            class="form-control"
                                            min="1"
                                            max="20"
                                            step="1"
                                            value="<?= htmlspecialchars(
                                                (string) $durationYears
                                            ) ?>"
                                        >

                                        <span class="input-group-text">
                                            ans
                                        </span>

                                    </div>

                                    <div class="form-text">
                                        Entre 1 et 20 ans.
                                    </div>

                                </div>


                                <!-- Statut -->
                                <div class="col-md-3">

                                    <label
                                        for="status"
                                        class="form-label"
                                    >
                                        Statut
                                    </label>

                                    <select
                                        id="status"
                                        name="status"
                                        class="form-select"
                                    >

                                        <option
                                            value="ACTIVE"
                                            <?= $programStatus === 'ACTIVE'
                                                ? 'selected'
                                                : '' ?>
                                        >
                                            Actif
                                        </option>

                                        <option
                                            value="INACTIVE"
                                            <?= $programStatus === 'INACTIVE'
                                                ? 'selected'
                                                : '' ?>
                                        >
                                            Inactif
                                        </option>

                                    </select>

                                </div>

                            </div>

                        </div>


                        <hr class="my-4">


                        <!-- Actions -->
                        <div
                            class="d-flex flex-column
                                   flex-sm-row
                                   justify-content-between
                                   gap-2"
                        >

                            <a
                                href="/academic-programs/<?= $programId ?>"
                                class="btn btn-light border"
                            >
                                <i class="bi bi-x-lg me-1"></i>
                                Annuler
                            </a>


                            <button
                                type="submit"
                                id="academicProgramSubmitButton"
                                class="btn btn-primary px-4"
                            >
                                <span id="academicProgramSubmitIcon">
                                    <i class="bi bi-check-lg me-1"></i>
                                </span>

                                <span id="academicProgramSubmitText">
                                    Enregistrer
                                </span>
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>