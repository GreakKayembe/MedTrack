<?php

declare(strict_types=1);

$universities = $universities ?? [];
$faculties = $faculties ?? [];

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
            <h4 class="mb-1 fw-bold">
                Nouveau programme académique
            </h4>

            <p class="text-muted mb-0">
                Enregistrez un programme académique
                et rattachez-le à une université.
            </p>
        </div>

        <a
            href="/academic-programs"
            class="btn btn-outline-secondary"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Retour aux programmes
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
                            <i class="bi bi-journal-medical fs-4"></i>
                        </div>

                        <div>
                            <h5 class="fw-semibold mb-1">
                                Informations du programme
                            </h5>

                            <p class="text-muted small mb-0">
                                Les champs marqués d’un astérisque
                                sont obligatoires.
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
                        action="/academic-programs"
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


                        <!--
                        ==========================================================
                        Université / Faculté
                        ==========================================================
                        -->

                        <div class="mb-4">

                            <h6 class="fw-semibold mb-3">
                                <i class="bi bi-building me-1"></i>
                                Structure académique
                            </h6>

                            <div class="row g-3">

                                <!-- Université -->
                                <div class="col-md-6">

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
                                            /*
                                             * UniversityRepository peut exposer
                                             * organization_id ou id selon
                                             * l'implémentation actuelle.
                                             */
                                            $universityId = (int) (
                                                $university['organization_id']
                                                ?? $university['id']
                                                ?? 0
                                            );

                                            $universityName = htmlspecialchars(
                                                (string) (
                                                    $university['name']
                                                    ?? ''
                                                )
                                            );

                                            $universityCode = htmlspecialchars(
                                                (string) (
                                                    $university['code']
                                                    ?? ''
                                                )
                                            );
                                            ?>

                                            <option
                                                value="<?= $universityId ?>"
                                            >
                                                <?= $universityName ?>

                                                <?php if ($universityCode !== ''): ?>
                                                    (<?= $universityCode ?>)
                                                <?php endif; ?>
                                            </option>

                                        <?php endforeach; ?>

                                    </select>

                                    <div class="invalid-feedback">
                                        Veuillez sélectionner une université.
                                    </div>

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
                                        disabled
                                    >

                                        <option value="">
                                            Sélectionnez d’abord une université
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

                                            $facultyName = htmlspecialchars(
                                                (string) (
                                                    $faculty['name']
                                                    ?? ''
                                                )
                                            );

                                            $facultyCode = htmlspecialchars(
                                                (string) (
                                                    $faculty['code']
                                                    ?? ''
                                                )
                                            );
                                            ?>

                                            <option
                                                value="<?= $facultyId ?>"
                                                data-university-id="<?= $facultyUniversityId ?>"
                                                hidden
                                            >
                                                <?= $facultyName ?>

                                                <?php if ($facultyCode !== ''): ?>
                                                    (<?= $facultyCode ?>)
                                                <?php endif; ?>
                                            </option>

                                        <?php endforeach; ?>

                                    </select>

                                    <div class="form-text">
                                        La faculté est facultative.
                                        Seules les facultés de l’université
                                        sélectionnée seront proposées.
                                    </div>

                                </div>

                            </div>

                        </div>


                        <hr class="my-4">


                        <!--
                        ==========================================================
                        Identification
                        ==========================================================
                        -->

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
                                        placeholder="Ex. MED"
                                        autocomplete="off"
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
                                        placeholder="Ex. Médecine générale"
                                        autocomplete="off"
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
                                        placeholder="Ex. MEDICINE"
                                        autocomplete="off"
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
                                            placeholder="5"
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
                                            selected
                                        >
                                            Actif
                                        </option>

                                        <option value="INACTIVE">
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
                                   justify-content-end
                                   gap-2"
                        >

                            <a
                                href="/academic-programs"
                                class="btn btn-light border"
                            >
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
