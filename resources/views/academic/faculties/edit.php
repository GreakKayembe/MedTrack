<?php

declare(strict_types=1);

/**
 * @var array $faculty
 * @var array $universities
 * @var string $csrfToken
 */

$faculty = $faculty ?? [];
$universities = $universities ?? [];

$id = (int) (
    $faculty['id']
    ?? 0
);

$currentUniversityId = (int) (
    $faculty['university_id']
    ?? 0
);

$name = (string) (
    $faculty['name']
    ?? ''
);

$code = (string) (
    $faculty['code']
    ?? ''
);

$status = (string) (
    $faculty['status']
    ?? 'ACTIVE'
);
?>

<div class="container-fluid px-0">

    <!-- =========================================================
         Header
         ========================================================= -->

    <div class="d-flex flex-column flex-lg-row
                justify-content-between
                align-items-lg-center
                gap-3 mb-4">

        <div>

            <h2 class="fw-bold mb-1">
                Modifier la faculté
            </h2>

            <p class="text-muted mb-0">
                Modifiez les informations académiques
                de cette faculté.
            </p>

        </div>

        <div class="d-flex gap-2">

            <a
                href="/faculties/<?= $id ?>"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Retour
            </a>

        </div>

    </div>


    <form
        id="facultyForm"
        action="/faculties/<?= $id ?>"
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


        <!-- Alert -->

        <div
            id="facultyFormAlert"
            class="alert d-none"
            role="alert"
        ></div>


        <div class="row g-4">

            <!-- =====================================================
                 Main form
                 ===================================================== -->

            <div class="col-xl-8">

                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white py-3">

                        <h5 class="fw-bold mb-0">

                            <i
                                class="bi bi-pencil-square
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

                                        $selected =
                                            $universityId ===
                                            $currentUniversityId;
                                        ?>

                                        <option
                                            value="<?= $universityId ?>"
                                            <?= $selected
                                                ? 'selected'
                                                : ''
                                            ?>
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
                                    Université à laquelle cette faculté
                                    est rattachée.
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
                                    value="<?= htmlspecialchars(
                                        $name,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
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
                                    value="<?= htmlspecialchars(
                                        $code,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
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
                                        <?= $status === 'ACTIVE'
                                            ? 'selected'
                                            : ''
                                        ?>
                                    >
                                        Active
                                    </option>

                                    <option
                                        value="INACTIVE"
                                        <?= $status === 'INACTIVE'
                                            ? 'selected'
                                            : ''
                                        ?>
                                    >
                                        Inactive
                                    </option>

                                </select>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- =====================================================
                 Side panel
                 ===================================================== -->

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
                            <i class="bi bi-diagram-3 fs-4"></i>
                        </div>

                        <h5 class="fw-bold">
                            <?= htmlspecialchars(
                                $name,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </h5>

                        <?php if ($code !== ''): ?>

                            <div class="text-muted">
                                Code :
                                <?= htmlspecialchars(
                                    $code,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </div>

                        <?php endif; ?>

                    </div>

                </div>


                <div class="card border-0 shadow-sm">

                    <div class="card-body p-4">

                        <h6 class="fw-bold mb-3">

                            <i class="bi bi-info-circle me-2"></i>

                            À savoir

                        </h6>

                        <p class="small text-muted mb-3">
                            Une faculté peut être déplacée vers
                            une autre université uniquement si
                            la combinaison université + nom reste
                            unique.
                        </p>

                        <p class="small text-muted mb-0">
                            Utilisez le statut
                            <strong>Inactive</strong> lorsqu'une
                            faculté ne doit plus être utilisée,
                            plutôt que de supprimer ses données.
                        </p>

                    </div>

                </div>

            </div>

        </div>


        <!-- =========================================================
             Actions
             ========================================================= -->

        <div class="d-flex
                    justify-content-end
                    gap-2
                    mt-4">

            <a
                href="/faculties/<?= $id ?>"
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

</div>