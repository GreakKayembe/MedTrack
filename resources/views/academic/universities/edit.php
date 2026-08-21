<?php

declare(strict_types=1);

/**
 * @var array $university
 */

$id = (int) ($university['id'] ?? 0);

$code = (string) ($university['code'] ?? '');
$name = (string) ($university['name'] ?? '');
$province = (string) ($university['province'] ?? '');
$city = (string) ($university['city'] ?? '');
$address = (string) ($university['address'] ?? '');
$phone = (string) ($university['phone'] ?? '');
$email = (string) ($university['email'] ?? '');

$status = (string) (
    $university['status']
    ?? 'ACTIVE'
);

$universityType = (string) (
    $university['university_type']
    ?? ''
);

$accreditationStatus = (string) (
    $university['accreditation_status']
    ?? 'PENDING'
);

$accreditationScore =
    $university['accreditation_score']
    ?? '';
?>

<div class="container-fluid px-0">

    <div class="d-flex flex-column flex-lg-row
                justify-content-between align-items-lg-center
                gap-3 mb-4">

        <div>

            <div class="d-flex align-items-center gap-2 mb-2">

                <a
                    href="/universities"
                    class="text-decoration-none text-muted"
                >
                    Universités
                </a>

                <i class="bi bi-chevron-right small text-muted"></i>

                <a
                    href="/universities/<?= $id ?>"
                    class="text-decoration-none text-muted"
                >
                    <?= htmlspecialchars($name) ?>
                </a>

                <i class="bi bi-chevron-right small text-muted"></i>

                <span class="text-muted">
                    Modification
                </span>

            </div>

            <h2 class="fw-bold mb-1">
                Modifier l'université
            </h2>

            <p class="text-muted mb-0">
                Modifiez les informations institutionnelles
                de <?= htmlspecialchars($name) ?>.
                Le compte administrateur principal se gère séparément.
            </p>

        </div>


        <a
            href="/universities/<?= $id ?>"
            class="btn btn-outline-secondary
                   d-inline-flex align-items-center gap-2"
        >
            <i class="bi bi-arrow-left"></i>
            Retour à la fiche
        </a>

    </div>


    <div
        id="universityFormAlert"
        class="alert d-none"
        role="alert"
    ></div>


    <form
        id="universityForm"
        action="/universities/<?= $id ?>"
        method="post"
        novalidate
    >

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

            <!-- Main -->

            <div class="col-xl-8">

                <!-- Identification -->

                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-body p-4">

                        <div class="d-flex align-items-center gap-3 mb-4">

                            <div
                                class="rounded-circle
                                       bg-primary-subtle
                                       text-primary
                                       d-flex align-items-center
                                       justify-content-center"
                                style="width:48px;height:48px;"
                            >
                                <i class="bi bi-building fs-4"></i>
                            </div>

                            <div>
                                <h5 class="fw-bold mb-1">
                                    Identification
                                </h5>

                                <p class="text-muted small mb-0">
                                    Informations principales
                                    de l'établissement.
                                </p>
                            </div>

                        </div>


                        <div class="row g-3">

                            <div class="col-md-4">

                                <label
                                    for="code"
                                    class="form-label fw-semibold"
                                >
                                    Code
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="code"
                                    name="code"
                                    maxlength="50"
                                    required
                                    value="<?= htmlspecialchars(
                                        $code,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                >

                            </div>


                            <div class="col-md-8">

                                <label
                                    for="name"
                                    class="form-label fw-semibold"
                                >
                                    Nom de l'université
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="name"
                                    name="name"
                                    required
                                    value="<?= htmlspecialchars(
                                        $name,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                >

                            </div>


                            <div class="col-md-6">

                                <label
                                    for="university_type"
                                    class="form-label fw-semibold"
                                >
                                    Type d'université
                                </label>

                                <select
                                    class="form-select"
                                    id="university_type"
                                    name="university_type"
                                >

                                    <option value="">
                                        Sélectionner...
                                    </option>

                                    <option
                                        value="PUBLIQUE"
                                        <?= $universityType === 'PUBLIQUE'
                                            ? 'selected'
                                            : '' ?>
                                    >
                                        Publique
                                    </option>

                                    <option
                                        value="PRIVEE"
                                        <?= $universityType === 'PRIVEE'
                                            ? 'selected'
                                            : '' ?>
                                    >
                                        Privée
                                    </option>

                                    <option
                                        value="CONFESSIONNELLE"
                                        <?= $universityType === 'CONFESSIONNELLE'
                                            ? 'selected'
                                            : '' ?>
                                    >
                                        Confessionnelle
                                    </option>

                                    <option
                                        value="AUTRE"
                                        <?= $universityType === 'AUTRE'
                                            ? 'selected'
                                            : '' ?>
                                    >
                                        Autre
                                    </option>

                                </select>

                            </div>


                            <div class="col-md-6">

                                <label
                                    for="status"
                                    class="form-label fw-semibold"
                                >
                                    Statut institutionnel
                                </label>

                                <select
                                    class="form-select"
                                    id="status"
                                    name="status"
                                >

                                    <option
                                        value="ACTIVE"
                                        <?= $status === 'ACTIVE'
                                            ? 'selected'
                                            : '' ?>
                                    >
                                        Active
                                    </option>

                                    <option
                                        value="INACTIVE"
                                        <?= $status === 'INACTIVE'
                                            ? 'selected'
                                            : '' ?>
                                    >
                                        Inactive
                                    </option>

                                    <option
                                        value="SUSPENDED"
                                        <?= $status === 'SUSPENDED'
                                            ? 'selected'
                                            : '' ?>
                                    >
                                        Suspendue
                                    </option>

                                </select>

                            </div>

                        </div>

                    </div>

                </div>


                <!-- Localisation -->

                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-body p-4">

                        <div class="d-flex align-items-center gap-3 mb-4">

                            <div
                                class="rounded-circle
                                       bg-success-subtle
                                       text-success
                                       d-flex align-items-center
                                       justify-content-center"
                                style="width:48px;height:48px;"
                            >
                                <i class="bi bi-geo-alt fs-4"></i>
                            </div>

                            <div>
                                <h5 class="fw-bold mb-1">
                                    Localisation
                                </h5>

                                <p class="text-muted small mb-0">
                                    Adresse administrative
                                    de l'université.
                                </p>
                            </div>

                        </div>


                        <div class="row g-3">

                            <div class="col-md-6">

                                <label
                                    for="province"
                                    class="form-label fw-semibold"
                                >
                                    Province
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="province"
                                    name="province"
                                    maxlength="100"
                                    value="<?= htmlspecialchars(
                                        $province,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                >

                            </div>


                            <div class="col-md-6">

                                <label
                                    for="city"
                                    class="form-label fw-semibold"
                                >
                                    Ville
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="city"
                                    name="city"
                                    maxlength="100"
                                    value="<?= htmlspecialchars(
                                        $city,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                >

                            </div>


                            <div class="col-12">

                                <label
                                    for="address"
                                    class="form-label fw-semibold"
                                >
                                    Adresse
                                </label>

                                <textarea
                                    class="form-control"
                                    id="address"
                                    name="address"
                                    rows="3"
                                ><?= htmlspecialchars(
                                    $address,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?></textarea>

                            </div>

                        </div>

                    </div>

                </div>


                <!-- Contact -->

                <div class="card border-0 shadow-sm">

                    <div class="card-body p-4">

                        <div class="d-flex align-items-center gap-3 mb-4">

                            <div
                                class="rounded-circle
                                       bg-info-subtle
                                       text-info
                                       d-flex align-items-center
                                       justify-content-center"
                                style="width:48px;height:48px;"
                            >
                                <i class="bi bi-person-lines-fill fs-4"></i>
                            </div>

                            <div>
                                <h5 class="fw-bold mb-1">
                                    Coordonnées institutionnelles
                                </h5>

                                <p class="text-muted small mb-0">
                                    Coordonnées officielles de l’université. Elles ne correspondent pas nécessairement aux identifiants de connexion de l’administrateur principal.
                                </p>
                            </div>

                        </div>


                        <div class="row g-3">

                            <div class="col-md-6">

                                <label
                                    for="email"
                                    class="form-label fw-semibold"
                                >
                                    E-mail institutionnel
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="bi bi-envelope"></i>
                                    </span>

                                    <input
                                        type="email"
                                        class="form-control"
                                        id="email"
                                        name="email"
                                        maxlength="190"
                                        value="<?= htmlspecialchars(
                                            $email,
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>"
                                    >

                                </div>

                                <div class="form-text">
                                    Adresse officielle de l’université.
                                    Elle n’est pas utilisée automatiquement
                                    comme compte de connexion administrateur.
                                </div>

                            </div>


                            <div class="col-md-6">

                                <label
                                    for="phone"
                                    class="form-label fw-semibold"
                                >
                                    Téléphone
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="bi bi-telephone"></i>
                                    </span>

                                    <input
                                        type="tel"
                                        class="form-control"
                                        id="phone"
                                        name="phone"
                                        maxlength="30"
                                        value="<?= htmlspecialchars(
                                            $phone,
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>"
                                    >

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- Accreditation -->

            <div class="col-xl-4">

                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-body p-4">

                        <div class="d-flex align-items-start gap-3">

                            <div
                                class="rounded-circle
                                       bg-primary-subtle
                                       text-primary
                                       d-flex align-items-center
                                       justify-content-center
                                       flex-shrink-0"
                                style="width:48px;height:48px;"
                            >
                                <i class="bi bi-person-gear fs-4"></i>
                            </div>

                            <div>

                                <h5 class="fw-bold mb-1">
                                    Administrateur principal
                                </h5>

                                <p class="text-muted small mb-3">
                                    Le compte UNIVERSITY_ADMIN n’est pas modifié
                                    depuis ce formulaire. Il possède son propre
                                    cycle de vie utilisateur, membership et rôles.
                                </p>

                                <div class="alert alert-light border mb-0">

                                    <div class="d-flex gap-2">

                                        <i class="bi bi-shield-lock text-primary"></i>

                                        <small class="text-muted">
                                            Les modifications ci-dessous concernent
                                            uniquement l’université et son accréditation.
                                        </small>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <div
                    class="card border-0 shadow-sm"
                    style="position:sticky;top:90px;"
                >

                    <div class="card-body p-4">

                        <div class="d-flex align-items-center gap-3 mb-4">

                            <div
                                class="rounded-circle
                                       bg-warning-subtle
                                       text-warning
                                       d-flex align-items-center
                                       justify-content-center"
                                style="width:48px;height:48px;"
                            >
                                <i class="bi bi-patch-check fs-4"></i>
                            </div>

                            <div>

                                <h5 class="fw-bold mb-1">
                                    Accréditation
                                </h5>

                                <p class="text-muted small mb-0">
                                    Situation réglementaire.
                                </p>

                            </div>

                        </div>


                        <div class="mb-4">

                            <label
                                for="accreditation_status"
                                class="form-label fw-semibold"
                            >
                                Statut
                            </label>

                            <select
                                class="form-select"
                                id="accreditation_status"
                                name="accreditation_status"
                            >

                                <option
                                    value="PENDING"
                                    <?= $accreditationStatus === 'PENDING'
                                        ? 'selected'
                                        : '' ?>
                                >
                                    En attente
                                </option>

                                <option
                                    value="ACCREDITED"
                                    <?= $accreditationStatus === 'ACCREDITED'
                                        ? 'selected'
                                        : '' ?>
                                >
                                    Accréditée
                                </option>

                                <option
                                    value="SUSPENDED"
                                    <?= $accreditationStatus === 'SUSPENDED'
                                        ? 'selected'
                                        : '' ?>
                                >
                                    Suspendue
                                </option>

                                <option
                                    value="REVOKED"
                                    <?= $accreditationStatus === 'REVOKED'
                                        ? 'selected'
                                        : '' ?>
                                >
                                    Révoquée
                                </option>

                            </select>

                        </div>


                        <div class="mb-4">

                            <label
                                for="accreditation_score"
                                class="form-label fw-semibold"
                            >
                                Score d'accréditation
                            </label>

                            <div class="input-group">

                                <input
                                    type="number"
                                    class="form-control"
                                    id="accreditation_score"
                                    name="accreditation_score"
                                    min="0"
                                    max="100"
                                    step="0.01"
                                    value="<?= htmlspecialchars(
                                        (string) $accreditationScore,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                >

                                <span class="input-group-text">
                                    / 100
                                </span>

                            </div>

                        </div>


                        <hr>


                        <div class="small text-muted mb-4">

                            <div class="d-flex gap-2 mb-2">

                                <i class="bi bi-shield-check text-success"></i>

                                <span>
                                    Les modifications sont
                                    validées côté serveur.
                                </span>

                            </div>

                            <div class="d-flex gap-2">

                                <i class="bi bi-database-check text-primary"></i>

                                <span>
                                    Les données institutionnelles
                                    et universitaires restent synchronisées.
                                </span>

                            </div>

                        </div>


                        <div class="d-grid gap-2">

                            <button
                                type="submit"
                                id="universitySubmitButton"
                                class="btn btn-primary btn-lg"
                            >

                                <span id="universitySubmitIcon">
                                    <i class="bi bi-check-lg me-1"></i>
                                </span>

                                <span id="universitySubmitText">
                                    Enregistrer les modifications
                                </span>

                            </button>


                            <a
                                href="/universities/<?= $id ?>"
                                class="btn btn-light"
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