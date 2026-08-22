<?php

declare(strict_types=1);
?>

<div class="row justify-content-center">

    <div class="col-xl-9 col-lg-10">

        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white border-0 py-4">

                <div
                    class="d-flex flex-column flex-md-row
                           justify-content-between
                           align-items-md-center gap-3"
                >

                    <div>
                        <h5 class="mb-1 fw-semibold">
                            Nouvelle année académique
                        </h5>

                        <p class="text-muted small mb-0">
                            Définissez la période et le statut
                            de l’année académique.
                        </p>
                    </div>

                    <a
                        href="/academic-years"
                        class="btn btn-outline-secondary"
                    >
                        <i class="bi bi-arrow-left me-1"></i>
                        Retour
                    </a>

                </div>

            </div>


            <div class="card-body">

                <div
                    id="academicYearFormAlert"
                    class="alert d-none"
                    role="alert"
                ></div>


                <form
                    id="academicYearForm"
                    action="/academic-years"
                    method="POST"
                    novalidate
                >

                    <input
                        type="hidden"
                        name="_token"
                        value="<?= htmlspecialchars(
                            (string) ($csrfToken ?? '')
                        ) ?>"
                    >


                    <div class="row g-4">

                        <!-- Label -->

                        <div class="col-md-6">

                            <label
                                for="label"
                                class="form-label"
                            >
                                Année académique
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="label"
                                name="label"
                                placeholder="Ex. 2026-2027"
                                maxlength="50"
                                autocomplete="off"
                                required
                            >

                            <div class="form-text">
                                Exemple : 2026-2027.
                            </div>

                            <div class="invalid-feedback">
                                Veuillez indiquer l’année académique.
                            </div>

                        </div>


                        <!-- Status -->

                        <div class="col-md-6">

                            <label
                                for="status"
                                class="form-label"
                            >
                                Statut
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                class="form-select"
                                id="status"
                                name="status"
                                required
                            >

                                <option value="PLANNED" selected>
                                    Planifiée
                                </option>

                                <option value="OPEN">
                                    Ouverte
                                </option>

                                <option value="CLOSED">
                                    Clôturée
                                </option>

                            </select>

                            <div class="form-text">
                                Une nouvelle année peut être
                                créée comme planifiée.
                            </div>

                            <div class="invalid-feedback">
                                Veuillez sélectionner un statut.
                            </div>

                        </div>


                        <!-- Start date -->

                        <div class="col-md-6">

                            <label
                                for="starts_on"
                                class="form-label"
                            >
                                Date de début
                                <span class="text-danger">*</span>
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    <i class="bi bi-calendar-event"></i>
                                </span>

                                <input
                                    type="date"
                                    class="form-control"
                                    id="starts_on"
                                    name="starts_on"
                                    required
                                >

                                <div class="invalid-feedback">
                                    Veuillez indiquer la date de début.
                                </div>

                            </div>

                        </div>


                        <!-- End date -->

                        <div class="col-md-6">

                            <label
                                for="ends_on"
                                class="form-label"
                            >
                                Date de fin
                                <span class="text-danger">*</span>
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    <i class="bi bi-calendar-check"></i>
                                </span>

                                <input
                                    type="date"
                                    class="form-control"
                                    id="ends_on"
                                    name="ends_on"
                                    required
                                >

                                <div class="invalid-feedback">
                                    Veuillez indiquer la date de fin.
                                </div>

                            </div>

                        </div>

                    </div>


                    <hr class="my-4">


                    <div
                        class="alert alert-light border
                               d-flex align-items-start"
                    >

                        <i
                            class="bi bi-info-circle
                                   text-primary fs-5 me-3"
                        ></i>

                        <div>
                            <strong>
                                À propos de l’année académique
                            </strong>

                            <div class="small text-muted mt-1">
                                Les cohortes et les inscriptions
                                académiques seront ensuite rattachées
                                à cette période. La date de fin doit
                                être postérieure à la date de début.
                            </div>
                        </div>

                    </div>


                    <div
                        class="d-flex justify-content-end
                               gap-2 mt-4"
                    >

                        <a
                            href="/academic-years"
                            class="btn btn-light border"
                        >
                            Annuler
                        </a>

                        <button
                            type="submit"
                            class="btn btn-primary"
                            id="academicYearSubmitButton"
                        >

                            <span id="academicYearSubmitIcon">
                                <i class="bi bi-check-lg me-1"></i>
                            </span>

                            <span id="academicYearSubmitText">
                                Enregistrer
                            </span>

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>