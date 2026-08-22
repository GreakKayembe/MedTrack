<?php

declare(strict_types=1);

/** @var array<string, mixed> $hospital */
/** @var string $csrfToken */

$id =
    (int) (
        $hospital['id']
        ?? 0
    );

$status =
    (string) (
        $hospital['status']
        ?? 'ACTIVE'
    );

$accreditationStatus =
    (string) (
        $hospital['accreditation_status']
        ?? 'PENDING'
    );
?>

<div class="container-fluid px-0">

    <div
        class="d-flex flex-column flex-lg-row
               justify-content-between align-items-lg-center
               gap-3 mb-4"
    >

        <div>

            <div class="d-flex align-items-center gap-2 mb-2">

                <span class="badge rounded-pill text-bg-primary">
                    Hospital
                </span>

                <span class="text-muted small">
                    Gestion institutionnelle
                </span>

            </div>

            <h2 class="fw-bold mb-1">
                Modifier l’hôpital
            </h2>

            <p class="text-muted mb-0">
                <?= htmlspecialchars(
                    (string) (
                        $hospital['name']
                        ?? ''
                    ),
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </p>

        </div>


        <a
            href="/hospitals/<?= $id ?>"
            class="btn btn-outline-secondary
                   d-inline-flex align-items-center gap-2"
        >
            <i class="bi bi-arrow-left"></i>
            Retour à la fiche
        </a>

    </div>


    <div
        id="hospitalFormAlert"
        class="alert d-none"
        role="alert"
    ></div>


    <form
        id="hospitalForm"
        action="/hospitals/<?= $id ?>"
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

            <!-- =========================
                 Main content
                 ========================= -->

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
                                <i class="bi bi-hospital fs-4"></i>
                            </div>

                            <div>

                                <h5 class="fw-bold mb-1">
                                    Identification
                                </h5>

                                <p class="text-muted small mb-0">
                                    Informations principales de
                                    l’établissement hospitalier.
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
                                        (string) (
                                            $hospital['code']
                                            ?? ''
                                        ),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                >

                                <div class="form-text">
                                    Code institutionnel unique.
                                </div>

                            </div>


                            <div class="col-md-8">

                                <label
                                    for="name"
                                    class="form-label fw-semibold"
                                >
                                    Nom de l’hôpital
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="name"
                                    name="name"
                                    required
                                    value="<?= htmlspecialchars(
                                        (string) (
                                            $hospital['name']
                                            ?? ''
                                        ),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                >

                            </div>


                            <div class="col-md-4">

                                <label
                                    for="facility_level"
                                    class="form-label fw-semibold"
                                >
                                    Niveau de l’établissement
                                </label>

                                <input
                                    type="number"
                                    class="form-control"
                                    id="facility_level"
                                    name="facility_level"
                                    min="0"
                                    value="<?= htmlspecialchars(
                                        (string) (
                                            $hospital['facility_level']
                                            ?? ''
                                        ),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                >

                            </div>


                            <div class="col-md-4">

                                <label
                                    for="specialty"
                                    class="form-label fw-semibold"
                                >
                                    Spécialité principale
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="specialty"
                                    name="specialty"
                                    maxlength="150"
                                    value="<?= htmlspecialchars(
                                        (string) (
                                            $hospital['specialty']
                                            ?? ''
                                        ),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                >

                            </div>


                            <div class="col-md-4">

                                <label
                                    for="internship_capacity"
                                    class="form-label fw-semibold"
                                >
                                    Capacité de stage
                                </label>

                                <input
                                    type="number"
                                    class="form-control"
                                    id="internship_capacity"
                                    name="internship_capacity"
                                    min="0"
                                    value="<?= (int) (
                                        $hospital['internship_capacity']
                                        ?? 0
                                    ) ?>"
                                >

                                <div class="form-text">
                                    Nombre maximal de stagiaires.
                                </div>

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
                                    Adresse administrative et coordonnées
                                    géographiques de l’hôpital.
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
                                        (string) (
                                            $hospital['province']
                                            ?? ''
                                        ),
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
                                        (string) (
                                            $hospital['city']
                                            ?? ''
                                        ),
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
                                    (string) (
                                        $hospital['address']
                                        ?? ''
                                    ),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?></textarea>

                            </div>


                            <div class="col-md-6">

                                <label
                                    for="latitude"
                                    class="form-label fw-semibold"
                                >
                                    Latitude
                                </label>

                                <input
                                    type="number"
                                    class="form-control"
                                    id="latitude"
                                    name="latitude"
                                    min="-90"
                                    max="90"
                                    step="0.00000001"
                                    value="<?= htmlspecialchars(
                                        (string) (
                                            $hospital['latitude']
                                            ?? ''
                                        ),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                >

                            </div>


                            <div class="col-md-6">

                                <label
                                    for="longitude"
                                    class="form-label fw-semibold"
                                >
                                    Longitude
                                </label>

                                <input
                                    type="number"
                                    class="form-control"
                                    id="longitude"
                                    name="longitude"
                                    min="-180"
                                    max="180"
                                    step="0.00000001"
                                    value="<?= htmlspecialchars(
                                        (string) (
                                            $hospital['longitude']
                                            ?? ''
                                        ),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                >

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
                                    Coordonnées
                                </h5>

                                <p class="text-muted small mb-0">
                                    Informations de contact institutionnelles.
                                </p>

                            </div>

                        </div>


                        <div class="row g-3">

                            <div class="col-md-6">

                                <label
                                    for="email"
                                    class="form-label fw-semibold"
                                >
                                    Adresse e-mail
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
                                            (string) (
                                                $hospital['email']
                                                ?? ''
                                            ),
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>"
                                    >

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
                                            (string) (
                                                $hospital['phone']
                                                ?? ''
                                            ),
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


            <!-- =========================
                 Administration
                 ========================= -->

            <div class="col-xl-4">

                <div
                    class="card border-0 shadow-sm"
                    style="position: sticky; top: 90px;"
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
                                <i class="bi bi-sliders fs-4"></i>
                            </div>

                            <div>

                                <h5 class="fw-bold mb-1">
                                    Administration
                                </h5>

                                <p class="text-muted small mb-0">
                                    Statut MedTrack et accréditation.
                                </p>

                            </div>

                        </div>


                        <div class="mb-4">

                            <label
                                for="status"
                                class="form-label fw-semibold"
                            >
                                Statut MedTrack
                            </label>

                            <select
                                class="form-select"
                                id="status"
                                name="status"
                            >

                                <?php foreach (
                                    [
                                        'ACTIVE' => 'Actif',
                                        'INACTIVE' => 'Inactif',
                                        'SUSPENDED' => 'Suspendu',
                                    ]
                                    as $value => $label
                                ): ?>

                                    <option
                                        value="<?= $value ?>"
                                        <?= $status === $value
                                            ? 'selected'
                                            : '' ?>
                                    >
                                        <?= $label ?>
                                    </option>

                                <?php endforeach; ?>

                            </select>

                        </div>


                        <div class="mb-4">

                            <label
                                for="accreditation_status"
                                class="form-label fw-semibold"
                            >
                                Statut d’accréditation
                            </label>

                            <select
                                class="form-select"
                                id="accreditation_status"
                                name="accreditation_status"
                            >

                                <?php foreach (
                                    [
                                        'PENDING' => 'En attente',
                                        'ACCREDITED' => 'Accrédité',
                                        'SUSPENDED' => 'Suspendu',
                                        'REVOKED' => 'Révoqué',
                                    ]
                                    as $value => $label
                                ): ?>

                                    <option
                                        value="<?= $value ?>"
                                        <?= $accreditationStatus === $value
                                            ? 'selected'
                                            : '' ?>
                                    >
                                        <?= $label ?>
                                    </option>

                                <?php endforeach; ?>

                            </select>

                        </div>


                        <hr>


                        <div class="small text-muted mb-4">

                            <div class="d-flex gap-2 mb-2">

                                <i class="bi bi-shield-check text-success"></i>

                                <span>
                                    Les données seront validées
                                    côté serveur.
                                </span>

                            </div>

                            <div class="d-flex gap-2">

                                <i class="bi bi-database-check text-primary"></i>

                                <span>
                                    Les tables organizations et hospitals
                                    seront mises à jour dans une même transaction.
                                </span>

                            </div>

                        </div>


                        <div class="d-grid gap-2">

                            <button
                                type="submit"
                                id="hospitalSubmitButton"
                                class="btn btn-primary btn-lg"
                            >

                                <span id="hospitalSubmitIcon">
                                    <i class="bi bi-save me-1"></i>
                                </span>

                                <span id="hospitalSubmitText">
                                    Enregistrer
                                </span>

                            </button>


                            <a
                                href="/hospitals/<?= $id ?>"
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