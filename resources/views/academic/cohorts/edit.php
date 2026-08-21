<?php

declare(strict_types=1);

/**
 * @var array<string, mixed> $cohort
 * @var array<int, array<string, mixed>> $academicPrograms
 * @var array<int, array<string, mixed>> $academicYears
 * @var bool $isPlatform
 * @var bool $isUniversityContext
 * @var int|null $activeUniversityId
 */

$cohort =
    $cohort ?? [];

$academicPrograms =
    $academicPrograms ?? [];

$academicYears =
    $academicYears ?? [];

$isPlatform =
    $isPlatform ?? false;

$isUniversityContext =
    $isUniversityContext ?? false;

$activeUniversityId =
    $activeUniversityId ?? null;

$id = (int) (
    $cohort['id']
    ?? 0
);

$currentProgramId = (int) (
    $cohort['academic_program_id']
    ?? 0
);

$currentAcademicYearId = (int) (
    $cohort['academic_year_id']
    ?? 0
);

$currentName = (string) (
    $cohort['name']
    ?? ''
);
?>

<div class="row justify-content-center">

    <div class="col-xl-9 col-lg-10">

        <div class="card border-0 shadow-sm">

            <!--
            |--------------------------------------------------------------------------
            | Header
            |--------------------------------------------------------------------------
            -->

            <div class="card-header bg-white border-0 py-4">

                <div
                    class="d-flex flex-column flex-md-row
                           justify-content-between
                           align-items-md-center gap-3"
                >

                    <div>

                        <h5 class="mb-1 fw-semibold">
                            Modifier la cohorte
                        </h5>

                        <p class="text-muted small mb-0">

                            <?php if ($isUniversityContext): ?>

                                Modifiez cette cohorte dans le périmètre
                                académique de votre université.

                            <?php else: ?>

                                Modifiez le programme, l'année académique
                                ou le nom de la cohorte.

                            <?php endif; ?>

                        </p>

                    </div>


                    <a
                        href="/cohorts/<?= $id ?>"
                        class="btn btn-outline-secondary"
                    >
                        <i class="bi bi-arrow-left me-1"></i>
                        Retour
                    </a>

                </div>

            </div>


            <!--
            |--------------------------------------------------------------------------
            | Body
            |--------------------------------------------------------------------------
            -->

            <div class="card-body">

                <!-- Alert -->

                <div
                    id="cohortFormAlert"
                    class="alert d-none"
                    role="alert"
                ></div>


                <!--
                |--------------------------------------------------------------------------
                | Form
                |--------------------------------------------------------------------------
                -->

                <form
                    id="cohortForm"
                    action="/cohorts/<?= $id ?>"
                    method="POST"
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


                    <div class="row g-4">

                        <!--
                        |--------------------------------------------------------------------------
                        | Academic program
                        |--------------------------------------------------------------------------
                        -->

                        <div class="col-12">

                            <label
                                for="academic_program_id"
                                class="form-label"
                            >
                                Programme académique
                                <span class="text-danger">*</span>
                            </label>


                            <select
                                class="form-select"
                                id="academic_program_id"
                                name="academic_program_id"
                                required
                            >

                                <option value="">
                                    Sélectionnez un programme académique
                                </option>


                                <?php
                                $currentProgramAvailable =
                                    false;
                                ?>

                                <?php foreach ($academicPrograms as $program): ?>

                                    <?php
                                    $programId =
                                        (int) (
                                            $program['id']
                                            ?? 0
                                        );

                                    $programCode =
                                        (string) (
                                            $program['code']
                                            ?? ''
                                        );

                                    $programName =
                                        (string) (
                                            $program['name']
                                            ?? ''
                                        );

                                    $universityName =
                                        (string) (
                                            $program['university_name']
                                            ?? ''
                                        );

                                    $facultyName =
                                        (string) (
                                            $program['faculty_name']
                                            ?? ''
                                        );

                                    $selected =
                                        $programId
                                        === $currentProgramId;

                                    if ($selected) {
                                        $currentProgramAvailable =
                                            true;
                                    }
                                    ?>

                                    <option
                                        value="<?= $programId ?>"
                                        <?= $selected
                                            ? 'selected'
                                            : '' ?>
                                    >

                                        <?php if (
                                            $isPlatform
                                            && $universityName !== ''
                                        ): ?>

                                            <?= htmlspecialchars(
                                                $universityName . ' — ',
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>

                                        <?php endif; ?>

                                        <?php if ($programCode !== ''): ?>

                                            <?= htmlspecialchars(
                                                $programCode . ' — ',
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>

                                        <?php endif; ?>

                                        <?= htmlspecialchars(
                                            $programName,
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>

                                        <?php if ($facultyName !== ''): ?>

                                            <?= htmlspecialchars(
                                                ' (' . $facultyName . ')',
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>

                                        <?php endif; ?>

                                    </option>

                                <?php endforeach; ?>

                                <?php if (
                                    !$currentProgramAvailable
                                    && $currentProgramId > 0
                                ): ?>

                                    <option
                                        value="<?= $currentProgramId ?>"
                                        selected
                                        disabled
                                    >
                                        Programme actuel indisponible
                                    </option>

                                <?php endif; ?>

                            </select>


                            <div class="form-text">
                                Programme académique auquel
                                appartient cette cohorte.

                                <?php if ($isUniversityContext): ?>
                                    Seuls les programmes de votre
                                    université sont disponibles.
                                <?php endif; ?>
                            </div>

                            <?php if (
                                isset($currentProgramAvailable)
                                && !$currentProgramAvailable
                                && $currentProgramId > 0
                            ): ?>

                                <div class="alert alert-warning mt-3 mb-0">
                                    <i class="bi bi-exclamation-triangle me-1"></i>
                                    Le programme actuellement associé
                                    n’est plus disponible dans votre
                                    périmètre. Sélectionnez un programme
                                    valide avant d’enregistrer.
                                </div>

                            <?php endif; ?>

                            <div class="invalid-feedback">
                                Veuillez sélectionner un programme
                                académique.
                            </div>

                        </div>


                        <!--
                        |--------------------------------------------------------------------------
                        | Academic year
                        |--------------------------------------------------------------------------
                        -->

                        <div class="col-md-6">

                            <label
                                for="academic_year_id"
                                class="form-label"
                            >
                                Année académique
                                <span class="text-danger">*</span>
                            </label>


                            <select
                                class="form-select"
                                id="academic_year_id"
                                name="academic_year_id"
                                required
                            >

                                <option value="">
                                    Sélectionnez une année académique
                                </option>


                                <?php foreach ($academicYears as $year): ?>

                                    <?php
                                    $yearId = (int) (
                                        $year['id']
                                        ?? 0
                                    );

                                    $yearLabel = (string) (
                                        $year['label']
                                        ?? ''
                                    );

                                    $yearStatus = (string) (
                                        $year['status']
                                        ?? ''
                                    );

                                    $selected =
                                        $yearId === $currentAcademicYearId;
                                    ?>

                                    <option
                                        value="<?= $yearId ?>"
                                        <?= $selected ? 'selected' : '' ?>
                                    >
                                        <?= htmlspecialchars(
                                            $yearLabel,
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>

                                        <?php if ($yearStatus !== ''): ?>

                                            <?= htmlspecialchars(
                                                ' — ' . $yearStatus,
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>

                                        <?php endif; ?>

                                    </option>

                                <?php endforeach; ?>

                            </select>


                            <div class="form-text">
                                Année académique associée
                                à cette cohorte.
                            </div>

                            <div class="invalid-feedback">
                                Veuillez sélectionner une année
                                académique.
                            </div>

                        </div>


                        <!--
                        |--------------------------------------------------------------------------
                        | Name
                        |--------------------------------------------------------------------------
                        -->

                        <div class="col-md-6">

                            <label
                                for="name"
                                class="form-label"
                            >
                                Nom de la cohorte
                                <span class="text-danger">*</span>
                            </label>


                            <input
                                type="text"
                                class="form-control"
                                id="name"
                                name="name"
                                maxlength="100"
                                autocomplete="off"
                                value="<?= htmlspecialchars(
                                    $currentName,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>"
                                required
                            >


                            <div class="form-text">
                                Nom permettant d'identifier
                                clairement cette cohorte.
                            </div>

                            <div class="invalid-feedback">
                                Veuillez saisir le nom
                                de la cohorte.
                            </div>

                        </div>

                    </div>


                    <!--
                    |--------------------------------------------------------------------------
                    | Current relationship
                    |--------------------------------------------------------------------------
                    -->

                    <div class="alert alert-light border mt-4 mb-0">

                        <div class="d-flex align-items-start">

                            <i
                                class="bi bi-diagram-3
                                       text-primary
                                       fs-5 me-3"
                            ></i>

                            <div>

                                <strong>
                                    Rattachement académique
                                </strong>

                                <div class="small text-muted mt-1">
                                    La cohorte reste définie par
                                    l'association entre un programme
                                    académique, une année académique
                                    et son nom.
                                </div>

                            </div>

                        </div>

                    </div>


                    <!--
                    |--------------------------------------------------------------------------
                    | Warning
                    |--------------------------------------------------------------------------
                    -->

                    <div class="alert alert-warning mt-3 mb-0">

                        <div class="d-flex align-items-start">

                            <i
                                class="bi bi-exclamation-triangle
                                       fs-5 me-3"
                            ></i>

                            <div>

                                <strong>
                                    Attention
                                </strong>

                                <div class="small mt-1">
                                    Modifier le programme ou l'année
                                    académique change le rattachement
                                    académique de cette cohorte.

                                    <?php if ($isUniversityContext): ?>
                                        Le programme sélectionné doit
                                        obligatoirement appartenir
                                        à votre université.
                                    <?php endif; ?>
                                </div>

                            </div>

                        </div>

                    </div>


                    <hr class="my-4">


                    <!--
                    |--------------------------------------------------------------------------
                    | Actions
                    |--------------------------------------------------------------------------
                    -->

                    <div
                        class="d-flex flex-column
                               flex-sm-row
                               justify-content-between
                               gap-2"
                    >

                        <a
                            href="/cohorts/<?= $id ?>"
                            class="btn btn-light border"
                        >
                            <i class="bi bi-x-lg me-1"></i>
                            Annuler
                        </a>


                        <button
                            type="submit"
                            class="btn btn-primary"
                            id="cohortSubmitButton"
                        >

                            <span id="cohortSubmitIcon">

                                <i
                                    class="bi bi-check-lg me-1"
                                ></i>

                            </span>

                            <span id="cohortSubmitText">
                                Enregistrer les modifications
                            </span>

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>