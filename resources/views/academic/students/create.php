<?php

declare(strict_types=1);
?>

<div class="container-fluid py-4">

    <!--
    |--------------------------------------------------------------------------
    | Header
    |--------------------------------------------------------------------------
    -->

    <div
        class="d-flex flex-column flex-md-row
               align-items-md-center justify-content-between
               gap-3 mb-4"
    >
        <div>
            <div class="mb-2">
                <a
                    href="/students"
                    class="text-decoration-none small"
                >
                    <i class="bi bi-arrow-left me-1"></i>
                    Retour aux étudiants
                </a>
            </div>

            <h1 class="h3 mb-1">
                Nouvel étudiant
            </h1>

            <p class="text-muted mb-0">
                Enregistrez l'identité et les informations
                personnelles de l'étudiant.
            </p>
        </div>
    </div>


    <!--
    |--------------------------------------------------------------------------
    | Important information
    |--------------------------------------------------------------------------
    -->

    <div class="alert alert-info border-0 shadow-sm mb-4">
        <div class="d-flex gap-3">
            <div>
                <i class="bi bi-info-circle fs-4"></i>
            </div>

            <div>
                <div class="fw-semibold mb-1">
                    Identité de l'étudiant
                </div>

                <div class="small">
                    Cette étape crée uniquement le dossier personnel
                    de l'étudiant. Son université, son programme,
                    son année académique, son niveau et sa cohorte
                    seront renseignés lors de son inscription académique.
                </div>
            </div>
        </div>
    </div>


    <!--
    |--------------------------------------------------------------------------
    | Form
    |--------------------------------------------------------------------------
    -->

    <form
        id="studentForm"
        action="/students"
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


        <!--
        |--------------------------------------------------------------------------
        | AJAX alert
        |--------------------------------------------------------------------------
        -->

        <div
            id="studentFormAlert"
            class="alert d-none"
            role="alert"
        ></div>


        <div class="row g-4">

            <!--
            |--------------------------------------------------------------------------
            | Main information
            |--------------------------------------------------------------------------
            -->

            <div class="col-xl-8">

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-transparent py-3">
                        <h2 class="h5 mb-0">
                            <i class="bi bi-person me-2"></i>
                            Identité
                        </h2>
                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            <!-- First name -->

                            <div class="col-md-6">
                                <label
                                    for="first_name"
                                    class="form-label"
                                >
                                    Prénom
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="first_name"
                                    name="first_name"
                                    maxlength="150"
                                    autocomplete="given-name"
                                    required
                                >

                                <div class="invalid-feedback">
                                    Le prénom est obligatoire.
                                </div>
                            </div>


                            <!-- Middle name -->

                            <div class="col-md-6">
                                <label
                                    for="middle_name"
                                    class="form-label"
                                >
                                    Postnom
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="middle_name"
                                    name="middle_name"
                                    maxlength="150"
                                    autocomplete="additional-name"
                                >
                            </div>


                            <!-- Last name -->

                            <div class="col-md-6">
                                <label
                                    for="last_name"
                                    class="form-label"
                                >
                                    Nom
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="last_name"
                                    name="last_name"
                                    maxlength="150"
                                    autocomplete="family-name"
                                    required
                                >

                                <div class="invalid-feedback">
                                    Le nom est obligatoire.
                                </div>
                            </div>


                            <!-- Gender -->

                            <div class="col-md-6">
                                <label
                                    for="gender"
                                    class="form-label"
                                >
                                    Sexe / genre
                                </label>

                                <select
                                    class="form-select"
                                    id="gender"
                                    name="gender"
                                >
                                    <option value="">
                                        Non renseigné
                                    </option>

                                    <option value="M">
                                        Masculin
                                    </option>

                                    <option value="F">
                                        Féminin
                                    </option>

                                    <option value="OTHER">
                                        Autre
                                    </option>

                                    <option value="UNSPECIFIED">
                                        Non précisé
                                    </option>
                                </select>
                            </div>


                            <!-- Birth date -->

                            <div class="col-md-6">
                                <label
                                    for="birth_date"
                                    class="form-label"
                                >
                                    Date de naissance
                                </label>

                                <input
                                    type="date"
                                    class="form-control"
                                    id="birth_date"
                                    name="birth_date"
                                >

                                <div class="invalid-feedback">
                                    La date de naissance est invalide.
                                </div>
                            </div>


                            <!-- Birth place -->

                            <div class="col-md-6">
                                <label
                                    for="birth_place"
                                    class="form-label"
                                >
                                    Lieu de naissance
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="birth_place"
                                    name="birth_place"
                                    maxlength="150"
                                >
                            </div>


                            <!-- Nationality -->

                            <div class="col-md-6">
                                <label
                                    for="nationality"
                                    class="form-label"
                                >
                                    Nationalité
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="nationality"
                                    name="nationality"
                                    maxlength="100"
                                    autocomplete="country-name"
                                >
                            </div>


                            <!-- National student number -->

                            <div class="col-md-6">
                                <label
                                    for="national_student_number"
                                    class="form-label"
                                >
                                    Numéro national étudiant
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="national_student_number"
                                    name="national_student_number"
                                    maxlength="80"
                                >

                                <div class="form-text">
                                    Laissez vide si aucun numéro national
                                    n'a encore été attribué.
                                </div>
                            </div>

                        </div>

                    </div>
                </div>


                <!--
                |--------------------------------------------------------------------------
                | Contact
                |--------------------------------------------------------------------------
                -->

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent py-3">
                        <h2 class="h5 mb-0">
                            <i class="bi bi-envelope me-2"></i>
                            Coordonnées
                        </h2>
                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            <!-- Email -->

                            <div class="col-md-6">
                                <label
                                    for="email"
                                    class="form-label"
                                >
                                    Adresse e-mail
                                </label>

                                <input
                                    type="email"
                                    class="form-control"
                                    id="email"
                                    name="email"
                                    maxlength="190"
                                    autocomplete="email"
                                >

                                <div class="invalid-feedback">
                                    Saisissez une adresse e-mail valide.
                                </div>
                            </div>


                            <!-- Phone -->

                            <div class="col-md-6">
                                <label
                                    for="phone"
                                    class="form-label"
                                >
                                    Téléphone
                                </label>

                                <input
                                    type="tel"
                                    class="form-control"
                                    id="phone"
                                    name="phone"
                                    maxlength="30"
                                    autocomplete="tel"
                                >
                            </div>

                        </div>

                    </div>
                </div>

            </div>


            <!--
            |--------------------------------------------------------------------------
            | Sidebar
            |--------------------------------------------------------------------------
            -->

            <div class="col-xl-4">

                <!-- Status -->

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-transparent py-3">
                        <h2 class="h5 mb-0">
                            <i class="bi bi-activity me-2"></i>
                            Statut
                        </h2>
                    </div>

                    <div class="card-body">

                        <label
                            for="status"
                            class="form-label"
                        >
                            Statut de l'étudiant
                        </label>

                        <select
                            class="form-select"
                            id="status"
                            name="status"
                            required
                        >
                            <option
                                value="ACTIVE"
                                selected
                            >
                                Actif
                            </option>

                            <option value="SUSPENDED">
                                Suspendu
                            </option>

                            <option value="GRADUATED">
                                Diplômé
                            </option>

                            <option value="INACTIVE">
                                Inactif
                            </option>
                        </select>

                        <div class="form-text mt-2">
                            Le statut concerne le dossier général
                            de l'étudiant dans MedTrack.
                        </div>

                    </div>
                </div>


                <!-- Account -->

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-transparent py-3">
                        <h2 class="h5 mb-0">
                            <i class="bi bi-person-lock me-2"></i>
                            Compte utilisateur
                        </h2>
                    </div>

                    <div class="card-body">

                        <label
                            for="user_id"
                            class="form-label"
                        >
                            ID du compte utilisateur
                        </label>

                        <input
                            type="number"
                            class="form-control"
                            id="user_id"
                            name="user_id"
                            min="1"
                            step="1"
                        >

                        <div class="form-text">
                            Facultatif. Laissez ce champ vide si
                            l'étudiant ne possède pas encore de
                            compte de connexion.
                        </div>

                    </div>
                </div>


                <!-- Actions -->

                <div class="card border-0 shadow-sm">
                    <div class="card-body">

                        <div class="d-grid gap-2">

                            <button
                                type="submit"
                                class="btn btn-primary"
                                id="studentSubmitButton"
                            >
                                <span
                                    id="studentSubmitIcon"
                                >
                                    <i
                                        class="bi bi-check-lg me-1"
                                    ></i>
                                </span>

                                <span
                                    id="studentSubmitText"
                                >
                                    Enregistrer
                                </span>
                            </button>

                            <a
                                href="/students"
                                class="btn btn-outline-secondary"
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