<?php

declare(strict_types=1);

/**
 * @var array<string, mixed> $faculty
 * @var array<int, array<string, mixed>> $universities
 * @var string $csrfToken
 * @var bool $isPlatform
 * @var bool $isUniversityContext
 * @var int|null $activeUniversityId
 */

$faculty =
    is_array($faculty ?? null)
        ? $faculty
        : [];

$universities =
    is_array($universities ?? null)
        ? $universities
        : [];

$isPlatform =
    (bool) ($isPlatform ?? false);

$isUniversityContext =
    (bool) ($isUniversityContext ?? false);

$activeUniversityId =
    isset($activeUniversityId)
        ? (int) $activeUniversityId
        : null;

$csrfToken =
    (string) ($csrfToken ?? '');

$id =
    (int) ($faculty['id'] ?? 0);

$currentUniversityId =
    (int) ($faculty['university_id'] ?? 0);

$name =
    trim((string) ($faculty['name'] ?? ''));

$code =
    trim((string) ($faculty['code'] ?? ''));

$status =
    strtoupper(
        trim(
            (string) ($faculty['status'] ?? 'ACTIVE')
        )
    );
?>

<div class="container-fluid px-0">

    <div
        class="d-flex flex-column flex-lg-row
               justify-content-between
               align-items-lg-center
               gap-3 mb-4"
    >
        <div>
            <h2 class="fw-bold mb-1">
                Modifier la faculté
            </h2>

            <p class="text-muted mb-0">
                <?php if ($isUniversityContext): ?>
                    Modifiez les informations de cette faculté.
                    Son rattachement à votre université est protégé
                    par le contexte MedTrack.
                <?php else: ?>
                    Modifiez les informations académiques
                    et le rattachement de cette faculté.
                <?php endif; ?>
            </p>
        </div>

        <a
            href="/faculties/<?= $id ?>"
            class="btn btn-outline-secondary"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Retour
        </a>
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
                $csrfToken,
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
        >

        <?php if (
            $isUniversityContext
            && $activeUniversityId !== null
            && $activeUniversityId > 0
        ): ?>
            <input
                type="hidden"
                name="university_id"
                value="<?= $activeUniversityId ?>"
            >
        <?php endif; ?>

        <div
            id="facultyFormAlert"
            class="alert d-none"
            role="alert"
            aria-live="polite"
        ></div>


        <div class="row g-4">

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

                            <?php if ($isPlatform): ?>

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

                                        <?php foreach (
                                            $universities
                                            as $university
                                        ): ?>

                                            <?php
                                            $universityId =
                                                (int) (
                                                    $university['id']
                                                    ?? $university['organization_id']
                                                    ?? 0
                                                );

                                            $universityName =
                                                trim(
                                                    (string) (
                                                        $university['name']
                                                        ?? ''
                                                    )
                                                );

                                            $universityCode =
                                                trim(
                                                    (string) (
                                                        $university['code']
                                                        ?? ''
                                                    )
                                                );

                                            $selected =
                                                $universityId
                                                === $currentUniversityId;
                                            ?>

                                            <?php if ($universityId > 0): ?>
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

                                                    <?php if (
                                                        $universityCode !== ''
                                                    ): ?>
                                                        (<?= htmlspecialchars(
                                                            $universityCode,
                                                            ENT_QUOTES,
                                                            'UTF-8'
                                                        ) ?>)
                                                    <?php endif; ?>
                                                </option>
                                            <?php endif; ?>

                                        <?php endforeach; ?>
                                    </select>

                                    <div class="invalid-feedback">
                                        Veuillez sélectionner une université.
                                    </div>

                                    <div class="form-text">
                                        Le Super Admin peut modifier
                                        le rattachement institutionnel.
                                    </div>

                                </div>

                            <?php elseif ($isUniversityContext): ?>

                                <div class="col-12">

                                    <div
                                        class="alert alert-light border
                                               d-flex align-items-start
                                               gap-3 mb-0"
                                    >
                                        <span
                                            class="rounded-circle
                                                   bg-primary-subtle
                                                   text-primary
                                                   d-inline-flex
                                                   align-items-center
                                                   justify-content-center
                                                   flex-shrink-0"
                                            style="
                                                width: 38px;
                                                height: 38px;
                                            "
                                        >
                                            <i class="bi bi-bank2"></i>
                                        </span>

                                        <div>
                                            <div class="fw-semibold">
                                                Rattachement protégé
                                            </div>

                                            <small class="text-muted">
                                                Cette faculté appartient
                                                à votre université active.
                                                Son université ne peut pas
                                                être changée depuis cet espace.
                                            </small>
                                        </div>

                                        <span
                                            class="badge bg-light
                                                   text-secondary border
                                                   ms-auto"
                                        >
                                            <i
                                                class="bi bi-shield-lock me-1"
                                            ></i>
                                            Sécurisé
                                        </span>
                                    </div>

                                </div>

                            <?php endif; ?>


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
                                $name !== ''
                                    ? $name
                                    : 'Faculté',
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

                        <hr>

                        <div class="small text-muted">
                            Identifiant :
                            <strong>#<?= $id ?></strong>
                        </div>

                    </div>

                </div>


                <div class="card border-0 shadow-sm">

                    <div class="card-body p-4">

                        <h6 class="fw-bold mb-3">
                            <i class="bi bi-info-circle me-2"></i>
                            À savoir
                        </h6>

                        <?php if ($isUniversityContext): ?>

                            <p class="small text-muted mb-3">
                                Vous pouvez modifier le nom,
                                le code et le statut de la faculté.
                                Son rattachement institutionnel
                                reste verrouillé sur votre université.
                            </p>

                        <?php else: ?>

                            <p class="small text-muted mb-3">
                                Le Super Admin peut déplacer une faculté
                                vers une autre université si les règles
                                d’unicité restent respectées.
                            </p>

                        <?php endif; ?>

                        <p class="small text-muted mb-0">
                            Utilisez le statut
                            <strong>Inactive</strong>
                            lorsqu'une faculté ne doit plus être utilisée,
                            plutôt que de supprimer ses données.
                        </p>

                    </div>

                </div>

            </div>

        </div>


        <div
            class="d-flex
                   justify-content-end
                   gap-2
                   mt-4"
        >
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