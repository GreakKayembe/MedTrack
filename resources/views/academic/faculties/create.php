<?php

declare(strict_types=1);

/**
 * @var array $universities
 * @var string $csrfToken
 */

$universities = $universities ?? [];
?>

<div class="container-fluid px-0">

    <div class="d-flex flex-column flex-lg-row
                justify-content-between
                align-items-lg-center
                gap-3 mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                Nouvelle faculté
            </h2>

            <p class="text-muted mb-0">
                Ajoutez une faculté et rattachez-la
                à une université enregistrée dans MedTrack.
            </p>
        </div>

        <a
            href="/faculties"
            class="btn btn-outline-secondary"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Retour aux facultés
        </a>

    </div>


    <?php if ($universities === []): ?>

        <div class="alert alert-warning shadow-sm">

            <div class="d-flex gap-3">

                <i class="bi bi-exclamation-triangle fs-4"></i>

                <div>
                    <div class="fw-bold mb-1">
                        Aucune université disponible
                    </div>

                    <div class="mb-3">
                        Une faculté doit obligatoirement être
                        rattachée à une université.
                    </div>

                    <a
                        href="/universities/create"
                        class="btn btn-warning btn-sm"
                    >
                        <i class="bi bi-plus-lg me-1"></i>
                        Créer une université
                    </a>
                </div>

            </div>

        </div>

    <?php else: ?>

        <form
            id="facultyForm"
            action="/faculties"
            method="post"
            novalidate
        >

            <input
                type="hidden"
                name="_token"
                value="<?= htmlspecialchars(
                    $csrfToken ?? '',
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>"
            >


            <div
                id="facultyFormAlert"
                class="alert d-none"
                role="alert"
            ></div>


            <div class="row g-4">

                <!-- Main form -->

                <div class="col-xl-8">

                    <div class="card border-0 shadow-sm">

                        <div class="card-header bg-white py-3">

                            <h5 class="fw-bold mb-0">
                                <i
                                    class="bi bi-diagram-3
                                           text-primary me-2"
                                ></i>

                                Informations de la faculté
                            </h5>

                        </div>


                        <div class="card-body p-4">

                            <div class="row g-4">

                                <!-- University -->

                                <div class="col-12">

                                    <label
                                        for="university_id"
                                        class="form-label fw-semibold"
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
                                            Sélectionnez une université
                                        </option>

                                        <?php foreach ($universities as $university): ?>

                                            <?php
                                            $universityId = (int) (
                                                $university['id']
                                                ?? $university['organization_id']
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
                                            >
                                                <?= htmlspecialchars(
                                                    $universityName,
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>

                                                <?php if ($universityCode !== ''): ?>
                                                    (<?= htmlspecialchars(
                                                        $universityCode,
                                                        ENT_QUOTES,
                                                        'UTF-8'
                                                    ) ?>)
                                                <?php endif; ?>
                                            </option>

                                        <?php endforeach; ?>

                                    </select>

                                    <div class="invalid-feedback">
                                        Veuillez sélectionner une université.
                                    </div>

                                    <div class="form-text">
                                        La faculté sera rattachée
                                        à cette université.
                                    </div>

                                </div>


                                <!-- Name -->

                                <div class="col-md-8">

                                    <label
                                        for="name"
                                        class="form-label fw-semibold"
                                    >
                                        Nom de la faculté
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input
                                        type="text"
                                        id="name"
                                        name="name"
                                        class="form-control"
                                        maxlength="255"
                                        placeholder="Ex. Faculté de Médecine"
                                        required
                                    >

                                    <div class="invalid-feedback">
                                        Le nom de la faculté est obligatoire.
                                    </div>

                                </div>


                                <!-- Code -->

                                <div class="col-md-4">

                                    <label
                                        for="code"
                                        class="form-label fw-semibold"
                                    >
                                        Code
                                    </label>

                                    <input
                                        type="text"
                                        id="code"
                                        name="code"
                                        class="form-control text-uppercase"
                                        maxlength="50"
                                        placeholder="Ex. MED"
                                        autocomplete="off"
                                    >

                                    <div class="form-text">
                                        Facultatif.
                                    </div>

                                </div>


                                <!-- Status -->

                                <div class="col-md-6">

                                    <label
                                        for="status"
                                        class="form-label fw-semibold"
                                    >
                                        Statut
                                        <span class="text-danger">*</span>
                                    </label>

                                    <select
                                        id="status"
                                        name="status"
                                        class="form-select"
                                        required
                                    >
                                        <option
                                            value="ACTIVE"
                                            selected
                                        >
                                            Active
                                        </option>

                                        <option value="INACTIVE">
                                            Inactive
                                        </option>
                                    </select>

                                    <div class="invalid-feedback">
                                        Veuillez sélectionner un statut.
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                <!-- Information panel -->

                <div class="col-xl-4">

                    <div class="card border-0 shadow-sm mb-4">

                        <div class="card-body p-4">

                            <div
                                class="rounded-circle
                                       bg-primary-subtle
                                       text-primary
                                       d-flex
                                       align-items-center
                                       justify-content-center
                                       mb-3"
                                style="width:52px;height:52px;"
                            >
                                <i class="bi bi-info-circle fs-4"></i>
                            </div>

                            <h5 class="fw-bold">
                                Rattachement académique
                            </h5>

                            <p class="text-muted small mb-0">
                                Chaque faculté appartient à une
                                université. Les programmes académiques
                                pourront ensuite être rattachés à cette
                                faculté.
                            </p>

                        </div>

                    </div>


                    <div class="card border-0 shadow-sm">

                        <div class="card-body p-4">

                            <h6 class="fw-bold mb-3">
                                <i class="bi bi-shield-check me-2"></i>
                                Règles
                            </h6>

                            <div class="d-flex gap-2 mb-3">
                                <i class="bi bi-check-circle text-success"></i>

                                <small class="text-muted">
                                    L’université est obligatoire.
                                </small>
                            </div>

                            <div class="d-flex gap-2 mb-3">
                                <i class="bi bi-check-circle text-success"></i>

                                <small class="text-muted">
                                    Le nom est obligatoire.
                                </small>
                            </div>

                            <div class="d-flex gap-2">
                                <i class="bi bi-check-circle text-success"></i>

                                <small class="text-muted">
                                    Une université ne peut pas avoir
                                    deux facultés portant le même nom.
                                </small>
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- Actions -->

            <div class="d-flex
                        justify-content-end
                        gap-2
                        mt-4">

                <a
                    href="/faculties"
                    class="btn btn-light border"
                >
                    Annuler
                </a>

                <button
                    type="submit"
                    id="facultySubmitButton"
                    class="btn btn-primary"
                >
                    <span id="facultySubmitIcon">
                        <i class="bi bi-check-lg me-1"></i>
                    </span>

                    <span id="facultySubmitText">
                        Enregistrer
                    </span>
                </button>

            </div>

        </form>

    <?php endif; ?>

</div>