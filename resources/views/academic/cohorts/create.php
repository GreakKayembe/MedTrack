<?php

declare(strict_types=1);

/**
 * @var array $academicPrograms
 * @var array $academicYears
 */

$academicPrograms = $academicPrograms ?? [];
$academicYears = $academicYears ?? [];
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
                            Nouvelle cohorte
                        </h5>

                        <p class="text-muted small mb-0">
                            Associez un programme académique à une
                            année académique et définissez la cohorte.
                        </p>

                    </div>

                    <a
                        href="/cohorts"
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

                <div
                    id="cohortFormAlert"
                    class="alert d-none"
                    role="alert"
                ></div>


                <form
                    id="cohortForm"
                    action="/cohorts"
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

                                <?php foreach ($academicPrograms as $program): ?>

                                    <?php
                                    $programId = (int) (
                                        $program['id']
                                        ?? 0
                                    );

                                    $programCode = (string) (
                                        $program['code']
                                        ?? ''
                                    );

                                    $programName = (string) (
                                        $program['name']
                                        ?? ''
                                    );

                                    $universityName = (string) (
                                        $program['university_name']
                                        ?? ''
                                    );

                                    $facultyName = (string) (
                                        $program['faculty_name']
                                        ?? ''
                                    );
                                    ?>

                                    <option
                                        value="<?= $programId ?>"
                                    >
                                        <?= htmlspecialchars(
                                            $universityName !== ''
                                                ? $universityName . ' — '
                                                : '',
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>

                                        <?= htmlspecialchars(
                                            $programCode !== ''
                                                ? $programCode . ' — '
                                                : '',
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>

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

                            </select>

                            <div class="form-text">
                                Sélectionnez le programme auquel
                                cette cohorte sera rattachée.
                            </div>

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
                                    ?>

                                    <option
                                        value="<?= $yearId ?>"
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
                                Année académique durant laquelle
                                cette cohorte est organisée.
                            </div>

                            <div class="invalid-feedback">
                                Veuillez sélectionner une année
                                académique.
                            </div>

                        </div>


                        <!--
                        |--------------------------------------------------------------------------
                        | Cohort name
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
                                placeholder="Ex. Médecine 2026"
                                autocomplete="off"
                                required
                            >

                            <div class="form-text">
                                Nom permettant d'identifier clairement
                                la cohorte.
                            </div>

                            <div class="invalid-feedback">
                                Veuillez saisir le nom de la cohorte.
                            </div>

                        </div>

                    </div>


                    <!--
                    |--------------------------------------------------------------------------
                    | Academic relationship
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
                                    Une cohorte appartient à un programme
                                    académique pour une année académique
                                    déterminée. L'université et la faculté
                                    sont obtenues automatiquement à partir
                                    du programme sélectionné.
                                </div>

                            </div>

                        </div>

                    </div>


                    <!--
                    |--------------------------------------------------------------------------
                    | Current structure
                    |--------------------------------------------------------------------------
                    -->

                    <div class="mt-4">

                        <div class="row g-3">

                            <div class="col-md-4">

                                <div
                                    class="border rounded-3 p-3 h-100"
                                >

                                    <div
                                        class="text-primary mb-2"
                                    >
                                        <i
                                            class="bi bi-mortarboard fs-4"
                                        ></i>
                                    </div>

                                    <div class="fw-semibold">
                                        Programme
                                    </div>

                                    <div class="small text-muted">
                                        Définit la formation suivie.
                                    </div>

                                </div>

                            </div>


                            <div class="col-md-4">

                                <div
                                    class="border rounded-3 p-3 h-100"
                                >

                                    <div
                                        class="text-primary mb-2"
                                    >
                                        <i
                                            class="bi bi-calendar3 fs-4"
                                        ></i>
                                    </div>

                                    <div class="fw-semibold">
                                        Année académique
                                    </div>

                                    <div class="small text-muted">
                                        Définit la période académique.
                                    </div>

                                </div>

                            </div>


                            <div class="col-md-4">

                                <div
                                    class="border rounded-3 p-3 h-100"
                                >

                                    <div
                                        class="text-primary mb-2"
                                    >
                                        <i
                                            class="bi bi-people fs-4"
                                        ></i>
                                    </div>

                                    <div class="fw-semibold">
                                        Cohorte
                                    </div>

                                    <div class="small text-muted">
                                        Regroupe le parcours académique
                                        concerné.
                                    </div>

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
                            href="/cohorts"
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
                                <i class="bi bi-check-lg me-1"></i>
                            </span>

                            <span id="cohortSubmitText">
                                Enregistrer
                            </span>

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>