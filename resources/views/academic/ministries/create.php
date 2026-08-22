<?php

declare(strict_types=1);

/** @var string $csrfToken */
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
                    Ministry
                </span>

                <span class="text-muted small">
                    Supervision institutionnelle
                </span>

            </div>

            <h2 class="fw-bold mb-1">
                Nouveau ministère
            </h2>

            <p class="text-muted mb-0">
                Enregistrez une nouvelle institution ministérielle
                sur la plateforme MedTrack.
            </p>

        </div>

        <a
            href="/ministries"
            class="btn btn-outline-secondary
                   d-inline-flex align-items-center gap-2"
        >
            <i class="bi bi-arrow-left"></i>
            Retour aux ministères
        </a>

    </div>


    <!-- ============================================================
         Alert
         ============================================================ -->

    <div
        id="ministryFormAlert"
        class="alert d-none"
        role="alert"
    ></div>


    <!-- ============================================================
         Form
         ============================================================ -->

    <form
        id="ministryForm"
        action="/ministries"
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
                 Main
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
                                <i class="bi bi-building fs-4"></i>
                            </div>

                            <div>

                                <h5 class="fw-bold mb-1">
                                    Identification
                                </h5>

                                <p class="text-muted small mb-0">
                                    Informations principales
                                    de l’institution ministérielle.
                                </p>

                            </div>

                        </div>


                        <div class="row g-3">

                            <!-- Code -->

                            <div class="col-md-5">

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
                                    placeholder="Ex. MIN-SANTE"
                                >

                                <div class="form-text">
                                    Code unique utilisé par MedTrack.
                                </div>

                            </div>


                            <!-- Initial status -->

                            <div class="col-md-7">

                                <label
                                    for="initial_status"
                                    class="form-label fw-semibold"
                                >
                                    Statut initial
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="initial_status"
                                    value="Actif"
                                    disabled
                                >

                                <div class="form-text">
                                    Le ministère sera créé avec
                                    le statut actif.
                                </div>

                            </div>


                            <!-- Name -->

                            <div class="col-12">

                                <label
                                    for="name"
                                    class="form-label fw-semibold"
                                >
                                    Nom officiel du ministère
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="name"
                                    name="name"
                                    autocomplete="organization"
                                    required
                                    placeholder="Ex. Ministère de la Santé Publique"
                                >

                            </div>


                            <!-- Scope -->

                            <div class="col-12">

                                <label
                                    for="ministry_scope"
                                    class="form-label fw-semibold"
                                >
                                    Domaine de compétence
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="ministry_scope"
                                    name="ministry_scope"
                                    maxlength="150"
                                    placeholder="Ex. Santé publique et réglementation sanitaire"
                                >

                                <div class="form-text">
                                    Domaine institutionnel supervisé
                                    par ce ministère.
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
                                    Siège administratif du ministère.
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
                                    placeholder="Ex. Kinshasa"
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
                                    placeholder="Ex. Kinshasa"
                                >

                            </div>


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
                                    placeholder="Adresse physique du ministère"
                                ></textarea>

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
                                        placeholder="contact@ministere.cd"
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
                                        autocomplete="tel"
                                        placeholder="+243 ..."
                                    >

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- ====================================================
                 Right panel
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
                                <i class="bi bi-shield-check fs-4"></i>
                            </div>

                            <div>

                                <h5 class="fw-bold mb-1">
                                    Enregistrement
                                </h5>

                                <p class="text-muted small mb-0">
                                    Création institutionnelle.
                                </p>

                            </div>

                        </div>


                        <div class="mb-4">

                            <div class="text-muted small mb-2">
                                Type d’organisation
                            </div>

                            <span class="badge rounded-pill text-bg-primary">
                                Ministère
                            </span>

                        </div>


                        <div class="mb-4">

                            <div class="text-muted small mb-2">
                                Statut initial
                            </div>

                            <span class="badge rounded-pill text-bg-success">
                                Actif
                            </span>

                        </div>


                        <hr>


                        <div class="small text-muted mb-4">

                            <div class="d-flex gap-2 mb-3">

                                <i class="bi bi-shield-check text-success"></i>

                                <span>
                                    Les données sont validées
                                    côté serveur.
                                </span>

                            </div>


                            <div class="d-flex gap-2 mb-3">

                                <i class="bi bi-database-check text-primary"></i>

                                <span>
                                    L’organisation et son profil ministère
                                    seront créés dans une transaction unique.
                                </span>

                            </div>


                            <div class="d-flex gap-2">

                                <i class="bi bi-key text-warning"></i>

                                <span>
                                    Le code institutionnel doit être
                                    unique dans MedTrack.
                                </span>

                            </div>

                        </div>


                        <div class="d-grid gap-2">

                            <button
                                type="submit"
                                id="ministrySubmitButton"
                                class="btn btn-primary btn-lg"
                            >

                                <span id="ministrySubmitIcon">
                                    <i class="bi bi-plus-lg me-1"></i>
                                </span>

                                <span id="ministrySubmitText">
                                    Enregistrer le ministère
                                </span>

                            </button>


                            <a
                                href="/ministries"
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