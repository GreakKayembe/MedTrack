<?php

declare(strict_types=1);

/** @var array<string, mixed> $professionalOrder */
/** @var string $csrfToken */

$id =
    (int) (
        $professionalOrder['id']
        ?? 0
    );

$status =
    (string) (
        $professionalOrder['status']
        ?? 'ACTIVE'
    );
?>

<div class="container-fluid px-0">

    <!-- ============================================================
         Header
         ============================================================ -->

    <div
        class="d-flex flex-column flex-lg-row
               justify-content-between align-items-lg-center
               gap-3 mb-4"
    >

        <div>

            <div class="d-flex align-items-center gap-2 mb-2">

                <span class="badge rounded-pill text-bg-primary">
                    Professional Order
                </span>

                <span class="text-muted small">
                    Gestion institutionnelle
                </span>

            </div>

            <h2 class="fw-bold mb-1">
                Modifier l’ordre professionnel
            </h2>

            <p class="text-muted mb-0">
                <?= htmlspecialchars(
                    (string) (
                        $professionalOrder['name']
                        ?? ''
                    ),
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </p>

        </div>


        <a
            href="/professional-orders/<?= $id ?>"
            class="btn btn-outline-secondary
                   d-inline-flex align-items-center gap-2"
        >
            <i class="bi bi-arrow-left"></i>
            Retour à la fiche
        </a>

    </div>


    <!-- ============================================================
         Alert
         ============================================================ -->

    <div
        id="professionalOrderFormAlert"
        class="alert d-none"
        role="alert"
    ></div>


    <!-- ============================================================
         Form
         ============================================================ -->

    <form
        id="professionalOrderForm"
        action="/professional-orders/<?= $id ?>"
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

            <!-- ====================================================
                 Main content
                 ==================================================== -->

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
                                <i class="bi bi-award fs-4"></i>
                            </div>

                            <div>

                                <h5 class="fw-bold mb-1">
                                    Identification
                                </h5>

                                <p class="text-muted small mb-0">
                                    Informations principales de
                                    l’ordre professionnel.
                                </p>

                            </div>

                        </div>


                        <div class="row g-3">

                            <!-- Organization code -->

                            <div class="col-md-6">

                                <label
                                    for="code"
                                    class="form-label fw-semibold"
                                >
                                    Code institutionnel
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="code"
                                    name="code"
                                    maxlength="50"
                                    autocomplete="off"
                                    required
                                    value="<?= htmlspecialchars(
                                        (string) (
                                            $professionalOrder['code']
                                            ?? ''
                                        ),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                >

                                <div class="form-text">
                                    Code unique utilisé par MedTrack.
                                </div>

                            </div>


                            <!-- Profession code -->

                            <div class="col-md-6">

                                <label
                                    for="profession_code"
                                    class="form-label fw-semibold"
                                >
                                    Code profession
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="profession_code"
                                    name="profession_code"
                                    maxlength="50"
                                    autocomplete="off"
                                    required
                                    value="<?= htmlspecialchars(
                                        (string) (
                                            $professionalOrder[
                                                'profession_code'
                                            ]
                                            ?? ''
                                        ),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                >

                                <div class="form-text">
                                    Identifie la profession réglementée.
                                </div>

                            </div>


                            <!-- Name -->

                            <div class="col-12">

                                <label
                                    for="name"
                                    class="form-label fw-semibold"
                                >
                                    Nom de l’ordre professionnel
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="name"
                                    name="name"
                                    autocomplete="organization"
                                    required
                                    value="<?= htmlspecialchars(
                                        (string) (
                                            $professionalOrder['name']
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
                                    Siège et localisation administrative
                                    de l’ordre professionnel.
                                </p>

                            </div>

                        </div>


                        <div class="row g-3">

                            <!-- Province -->

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
                                            $professionalOrder['province']
                                            ?? ''
                                        ),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                >

                            </div>


                            <!-- City -->

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
                                            $professionalOrder['city']
                                            ?? ''
                                        ),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                >

                            </div>


                            <!-- Address -->

                            <div class="col-12">

                                <label
                                    for="address"
                                    class="form-label fw-semibold"
                                >
                                    Adresse du siège
                                </label>

                                <textarea
                                    class="form-control"
                                    id="address"
                                    name="address"
                                    rows="3"
                                ><?= htmlspecialchars(
                                    (string) (
                                        $professionalOrder['address']
                                        ?? ''
                                    ),
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
                                    Coordonnées
                                </h5>

                                <p class="text-muted small mb-0">
                                    Informations de contact
                                    institutionnelles.
                                </p>

                            </div>

                        </div>


                        <div class="row g-3">

                            <!-- Email -->

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
                                        autocomplete="email"
                                        value="<?= htmlspecialchars(
                                            (string) (
                                                $professionalOrder['email']
                                                ?? ''
                                            ),
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>"
                                    >

                                </div>

                            </div>


                            <!-- Phone -->

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
                                        autocomplete="tel"
                                        value="<?= htmlspecialchars(
                                            (string) (
                                                $professionalOrder['phone']
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


            <!-- ====================================================
                 Administration
                 ==================================================== -->

            <div class="col-xl-4">

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
                                <i class="bi bi-sliders fs-4"></i>
                            </div>

                            <div>

                                <h5 class="fw-bold mb-1">
                                    Administration
                                </h5>

                                <p class="text-muted small mb-0">
                                    Statut institutionnel MedTrack.
                                </p>

                            </div>

                        </div>


                        <!-- Status -->

                        <div class="mb-4">

                            <label
                                for="status"
                                class="form-label fw-semibold"
                            >
                                Statut
                            </label>

                            <select
                                class="form-select"
                                id="status"
                                name="status"
                            >

                                <?php foreach (
                                    [
                                        'ACTIVE' =>
                                            'Actif',

                                        'INACTIVE' =>
                                            'Inactif',

                                        'SUSPENDED' =>
                                            'Suspendu',
                                    ]
                                    as $value => $label
                                ): ?>

                                    <option
                                        value="<?= htmlspecialchars(
                                            $value,
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>"
                                        <?= $status === $value
                                            ? 'selected'
                                            : '' ?>
                                    >
                                        <?= htmlspecialchars(
                                            $label,
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </option>

                                <?php endforeach; ?>

                            </select>

                        </div>


                        <!-- Organization type -->

                        <div class="mb-4">

                            <div class="text-muted small mb-2">
                                Type d’organisation
                            </div>

                            <span
                                class="badge rounded-pill
                                       text-bg-primary"
                            >
                                Ordre professionnel
                            </span>

                        </div>


                        <hr>


                        <!-- Information -->

                        <div class="small text-muted mb-4">

                            <div class="d-flex gap-2 mb-3">

                                <i
                                    class="bi bi-shield-check
                                           text-success"
                                ></i>

                                <span>
                                    Les données sont validées
                                    côté serveur.
                                </span>

                            </div>


                            <div class="d-flex gap-2 mb-3">

                                <i
                                    class="bi bi-database-check
                                           text-primary"
                                ></i>

                                <span>
                                    Les tables organizations et
                                    professional_orders seront mises
                                    à jour dans une transaction unique.
                                </span>

                            </div>


                            <div class="d-flex gap-2">

                                <i
                                    class="bi bi-key
                                           text-warning"
                                ></i>

                                <span>
                                    Les codes institutionnel et
                                    professionnel doivent rester uniques.
                                </span>

                            </div>

                        </div>


                        <!-- Actions -->

                        <div class="d-grid gap-2">

                            <button
                                type="submit"
                                id="professionalOrderSubmitButton"
                                class="btn btn-primary btn-lg"
                            >

                                <span id="professionalOrderSubmitIcon">
                                    <i class="bi bi-save me-1"></i>
                                </span>

                                <span id="professionalOrderSubmitText">
                                    Enregistrer
                                </span>

                            </button>


                            <a
                                href="/professional-orders/<?= $id ?>"
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