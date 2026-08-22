<?php

declare(strict_types=1);

/**
 * @var array $student
 */

$student = $student ?? [];

$studentId =
    (int) ($student['id'] ?? 0);

$firstName =
    (string) ($student['first_name'] ?? '');

$middleName =
    (string) ($student['middle_name'] ?? '');

$lastName =
    (string) ($student['last_name'] ?? '');

$gender =
    (string) ($student['gender'] ?? '');

$birthDate =
    (string) ($student['birth_date'] ?? '');

$birthPlace =
    (string) ($student['birth_place'] ?? '');

$nationality =
    (string) ($student['nationality'] ?? '');

$nationalStudentNumber =
    (string) (
        $student['national_student_number']
        ?? ''
    );

$email =
    (string) ($student['email'] ?? '');

$phone =
    (string) ($student['phone'] ?? '');

$status =
    (string) ($student['status'] ?? 'ACTIVE');

$userId =
    $student['user_id'] ?? null;
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
                    href="/students/<?= $studentId ?>"
                    class="text-decoration-none small"
                >
                    <i class="bi bi-arrow-left me-1"></i>
                    Retour à la fiche étudiant
                </a>
            </div>

            <h1 class="h3 mb-1">
                Modifier l'étudiant
            </h1>

            <p class="text-muted mb-0">
                Modifiez les informations personnelles
                et administratives de l'étudiant.
            </p>

        </div>
    </div>


    <!--
    |--------------------------------------------------------------------------
    | Information
    |--------------------------------------------------------------------------
    -->

    <div
        class="alert alert-info
               border-0 shadow-sm mb-4"
    >
        <div class="d-flex gap-3">

            <div>
                <i
                    class="bi bi-info-circle fs-4"
                ></i>
            </div>

            <div>
                <div class="fw-semibold mb-1">
                    Dossier étudiant
                </div>

                <div class="small">
                    Cette page modifie uniquement
                    l'identité générale de l'étudiant.
                    Son programme, son année académique,
                    son niveau et sa cohorte seront gérés
                    séparément dans ses inscriptions
                    académiques.
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
        action="/students/<?= $studentId ?>"
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


        <!-- AJAX alert -->

        <div
            id="studentFormAlert"
            class="alert d-none"
            role="alert"
        ></div>


        <div class="row g-4">

            <!--
            |--------------------------------------------------------------------------
            | Main column
            |--------------------------------------------------------------------------
            -->

            <div class="col-xl-8">

                <!-- Identity -->

                <div
                    class="card border-0
                           shadow-sm mb-4"
                >

                    <div
                        class="card-header
                               bg-transparent py-3"
                    >
                        <h2 class="h5 mb-0">
                            <i
                                class="bi bi-person me-2"
                            ></i>
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
                                    <span class="text-danger">
                                        *
                                    </span>
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="first_name"
                                    name="first_name"
                                    maxlength="150"
                                    autocomplete="given-name"
                                    value="<?= htmlspecialchars(
                                        $firstName,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
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
                                    value="<?= htmlspecialchars(
                                        $middleName,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                >

                            </div>


                            <!-- Last name -->

                            <div class="col-md-6">

                                <label
                                    for="last_name"
                                    class="form-label"
                                >
                                    Nom
                                    <span class="text-danger">
                                        *
                                    </span>
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="last_name"
                                    name="last_name"
                                    maxlength="150"
                                    autocomplete="family-name"
                                    value="<?= htmlspecialchars(
                                        $lastName,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
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
                                    <option
                                        value=""
                                        <?= $gender === ''
                                            ? 'selected'
                                            : '' ?>
                                    >
                                        Non renseigné
                                    </option>

                                    <option
                                        value="M"
                                        <?= $gender === 'M'
                                            ? 'selected'
                                            : '' ?>
                                    >
                                        Masculin
                                    </option>

                                    <option
                                        value="F"
                                        <?= $gender === 'F'
                                            ? 'selected'
                                            : '' ?>
                                    >
                                        Féminin
                                    </option>

                                    <option
                                        value="OTHER"
                                        <?= $gender === 'OTHER'
                                            ? 'selected'
                                            : '' ?>
                                    >
                                        Autre
                                    </option>

                                    <option
                                        value="UNSPECIFIED"
                                        <?= $gender === 'UNSPECIFIED'
                                            ? 'selected'
                                            : '' ?>
                                    >
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
                                    value="<?= htmlspecialchars(
                                        $birthDate,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
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
                                    value="<?= htmlspecialchars(
                                        $birthPlace,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
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
                                    value="<?= htmlspecialchars(
                                        $nationality,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                >

                            </div>


                            <!-- National number -->

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
                                    value="<?= htmlspecialchars(
                                        $nationalStudentNumber,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                >

                                <div class="form-text">
                                    Ce numéro doit rester unique
                                    lorsqu'il est renseigné.
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

                <div
                    class="card border-0 shadow-sm"
                >

                    <div
                        class="card-header
                               bg-transparent py-3"
                    >
                        <h2 class="h5 mb-0">
                            <i
                                class="bi bi-envelope me-2"
                            ></i>
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
                                    value="<?= htmlspecialchars(
                                        $email,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                >

                                <div class="invalid-feedback">
                                    Saisissez une adresse
                                    e-mail valide.
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


            <!--
            |--------------------------------------------------------------------------
            | Sidebar
            |--------------------------------------------------------------------------
            -->

            <div class="col-xl-4">

                <!-- Status -->

                <div
                    class="card border-0
                           shadow-sm mb-4"
                >

                    <div
                        class="card-header
                               bg-transparent py-3"
                    >
                        <h2 class="h5 mb-0">
                            <i
                                class="bi bi-activity me-2"
                            ></i>
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
                                <?= $status === 'ACTIVE'
                                    ? 'selected'
                                    : '' ?>
                            >
                                Actif
                            </option>

                            <option
                                value="SUSPENDED"
                                <?= $status === 'SUSPENDED'
                                    ? 'selected'
                                    : '' ?>
                            >
                                Suspendu
                            </option>

                            <option
                                value="GRADUATED"
                                <?= $status === 'GRADUATED'
                                    ? 'selected'
                                    : '' ?>
                            >
                                Diplômé
                            </option>

                            <option
                                value="INACTIVE"
                                <?= $status === 'INACTIVE'
                                    ? 'selected'
                                    : '' ?>
                            >
                                Inactif
                            </option>
                        </select>

                        <div class="form-text mt-2">
                            Ce statut concerne le dossier
                            général de l'étudiant.
                        </div>

                    </div>
                </div>


                <!-- User account -->

                <div
                    class="card border-0
                           shadow-sm mb-4"
                >

                    <div
                        class="card-header
                               bg-transparent py-3"
                    >
                        <h2 class="h5 mb-0">
                            <i
                                class="bi bi-person-lock me-2"
                            ></i>
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
                            value="<?= $userId !== null
                                ? (int) $userId
                                : '' ?>"
                        >

                        <div class="form-text">
                            Facultatif. Un même compte
                            utilisateur ne peut être associé
                            qu'à un seul étudiant.
                        </div>

                    </div>
                </div>


                <!-- Actions -->

                <div
                    class="card border-0 shadow-sm"
                >
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
                                href="/students/<?= $studentId ?>"
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