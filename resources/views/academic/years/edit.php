<?php

declare(strict_types=1);

$id = (int) ($academicYear['id'] ?? 0);
?>

<div class="row justify-content-center">

    <div class="col-xl-9 col-lg-10">

        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white border-0 py-4">

                <div class="d-flex flex-column flex-md-row
                            justify-content-between
                            align-items-md-center gap-3">

                    <div>

                        <h5 class="mb-1 fw-semibold">
                            Modifier l’année académique
                        </h5>

                        <p class="text-muted small mb-0">
                            <?= htmlspecialchars(
                                (string) ($academicYear['label'] ?? '')
                            ) ?>
                        </p>

                    </div>

                    <a
                        href="/academic-years/<?= $id ?>"
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
                    action="/academic-years/<?= $id ?>"
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
                                value="<?= htmlspecialchars(
                                    (string) ($academicYear['label'] ?? '')
                                ) ?>"
                                maxlength="50"
                                autocomplete="off"
                                required
                            >

                            <div class="invalid-feedback">
                                Veuillez indiquer l’année académique.
                            </div>

                        </div>


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

                                <option
                                    value="PLANNED"
                                    <?= ($academicYear['status'] ?? '') === 'PLANNED'
                                        ? 'selected'
                                        : '' ?>
                                >
                                    Planifiée
                                </option>

                                <option
                                    value="OPEN"
                                    <?= ($academicYear['status'] ?? '') === 'OPEN'
                                        ? 'selected'
                                        : '' ?>
                                >
                                    Ouverte
                                </option>

                                <option
                                    value="CLOSED"
                                    <?= ($academicYear['status'] ?? '') === 'CLOSED'
                                        ? 'selected'
                                        : '' ?>
                                >
                                    Clôturée
                                </option>

                            </select>

                            <div class="invalid-feedback">
                                Veuillez sélectionner un statut.
                            </div>

                        </div>


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
                                    value="<?= htmlspecialchars(
                                        (string) ($academicYear['starts_on'] ?? '')
                                    ) ?>"
                                    required
                                >

                            </div>

                        </div>


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
                                    value="<?= htmlspecialchars(
                                        (string) ($academicYear['ends_on'] ?? '')
                                    ) ?>"
                                    required
                                >

                            </div>

                        </div>

                    </div>


                    <hr class="my-4">


                    <div class="alert alert-light border">

                        <div class="d-flex">

                            <i class="bi bi-info-circle
                                      text-primary fs-5 me-3"></i>

                            <div>

                                <strong>
                                    Modification de la période
                                </strong>

                                <div class="small text-muted mt-1">
                                    Vérifiez les dates avant de modifier
                                    une année déjà utilisée par des cohortes
                                    ou des inscriptions académiques.
                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="d-flex justify-content-end gap-2 mt-4">

                        <a
                            href="/academic-years/<?= $id ?>"
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