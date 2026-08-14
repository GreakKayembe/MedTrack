<?php

declare(strict_types=1);
?>

<div class="row justify-content-center">

    <div class="col-xl-9 col-lg-10">

        <div class="card border-0 shadow-sm">

            <!-- Header -->

            <div class="card-header bg-white border-0 py-4">

                <div
                    class="d-flex flex-column flex-md-row
                           justify-content-between
                           align-items-md-center gap-3"
                >

                    <div>

                        <h5 class="mb-1 fw-semibold">
                            Nouveau niveau d’études
                        </h5>

                        <p class="text-muted small mb-0">
                            Ajoutez un niveau au référentiel
                            académique de MedTrack.
                        </p>

                    </div>

                    <a
                        href="/study-levels"
                        class="btn btn-outline-secondary"
                    >
                        <i class="bi bi-arrow-left me-1"></i>
                        Retour
                    </a>

                </div>

            </div>


            <!-- Body -->

            <div class="card-body">

                <div
                    id="studyLevelFormAlert"
                    class="alert d-none"
                    role="alert"
                ></div>


                <form
                    id="studyLevelForm"
                    action="/study-levels"
                    method="POST"
                    novalidate
                >

                    <!-- CSRF -->

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

                        <!-- Code -->

                        <div class="col-md-6">

                            <label
                                for="code"
                                class="form-label"
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
                                placeholder="Ex. L1"
                                autocomplete="off"
                                required
                            >

                            <div class="form-text">
                                Code unique du niveau :
                                L1, L2, L3, M1, M2...
                            </div>

                            <div class="invalid-feedback">
                                Veuillez saisir le code du niveau.
                            </div>

                        </div>


                        <!-- Ordinal -->

                        <div class="col-md-6">

                            <label
                                for="ordinal"
                                class="form-label"
                            >
                                Ordre académique
                            </label>

                            <input
                                type="number"
                                class="form-control"
                                id="ordinal"
                                name="ordinal"
                                min="1"
                                max="65535"
                                step="1"
                                placeholder="Ex. 1"
                            >

                            <div class="form-text">
                                Détermine l’ordre d’affichage
                                et la progression des niveaux.
                            </div>

                            <div class="invalid-feedback">
                                L’ordre doit être un entier
                                supérieur ou égal à 1.
                            </div>

                        </div>


                        <!-- Name -->

                        <div class="col-12">

                            <label
                                for="name"
                                class="form-label"
                            >
                                Nom du niveau
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="name"
                                name="name"
                                maxlength="100"
                                placeholder="Ex. Première année de Licence"
                                autocomplete="off"
                                required
                            >

                            <div class="form-text">
                                Libellé complet qui sera présenté
                                aux utilisateurs.
                            </div>

                            <div class="invalid-feedback">
                                Veuillez saisir le nom du niveau d’études.
                            </div>

                        </div>

                    </div>


                    <!-- Information -->

                    <div class="alert alert-light border mt-4 mb-0">

                        <div class="d-flex align-items-start">

                            <i
                                class="bi bi-info-circle
                                       text-primary fs-5 me-3"
                            ></i>

                            <div>

                                <strong>
                                    Référentiel académique
                                </strong>

                                <div class="small text-muted mt-1">
                                    Les niveaux créés ici seront utilisés
                                    lors des inscriptions académiques des
                                    étudiants. Ils ne sont pas rattachés
                                    directement à une université.
                                </div>

                            </div>

                        </div>

                    </div>


                    <hr class="my-4">


                    <!-- Actions -->

                    <div
                        class="d-flex justify-content-end
                               gap-2"
                    >

                        <a
                            href="/study-levels"
                            class="btn btn-light border"
                        >
                            Annuler
                        </a>

                        <button
                            type="submit"
                            class="btn btn-primary"
                            id="studyLevelSubmitButton"
                        >

                            <span id="studyLevelSubmitIcon">
                                <i class="bi bi-check-lg me-1"></i>
                            </span>

                            <span id="studyLevelSubmitText">
                                Enregistrer
                            </span>

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>