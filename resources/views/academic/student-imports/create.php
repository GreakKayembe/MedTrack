<?php

declare(strict_types=1);
?>

<div class="container-fluid py-4">

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
                Importer les étudiants
            </h1>

            <p class="text-muted mb-0">
                Importez plusieurs étudiants à partir
                du modèle Excel officiel MedTrack.
            </p>

        </div>

        <div>
            <a
                href="/assets/templates/medtrack_student_import_template.xlsx"
                class="btn btn-outline-primary"
                download
            >
                <i class="bi bi-download me-1"></i>
                Télécharger le modèle
            </a>
        </div>
    </div>


    <div
        class="alert alert-info
               border-0 shadow-sm mb-4"
    >
        <div class="d-flex gap-3">

            <div>
                <i class="bi bi-info-circle fs-4"></i>
            </div>

            <div>

                <div class="fw-semibold mb-1">
                    Importation contrôlée
                </div>

                <div class="small">
                    Le fichier sera d'abord analysé par MedTrack.
                    Aucun étudiant, compte utilisateur ou inscription
                    académique ne sera créé avant votre confirmation.
                </div>

            </div>

        </div>
    </div>


    <form
        id="studentImportForm"
        action="/student-imports"
        method="post"
        enctype="multipart/form-data"
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


        <div
            id="studentImportAlert"
            class="alert d-none"
            role="alert"
        ></div>


        <div class="row g-4">

            <div class="col-xl-8">

                <div class="card border-0 shadow-sm">

                    <div
                        class="card-header
                               bg-transparent py-3"
                    >
                        <h2 class="h5 mb-0">

                            <i
                                class="bi bi-file-earmark-excel me-2"
                            ></i>

                            Fichier Excel

                        </h2>
                    </div>


                    <div class="card-body">

                        <div class="mb-4">

                            <label
                                for="student_file"
                                class="form-label"
                            >
                                Fichier des étudiants
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="file"
                                class="form-control"
                                id="student_file"
                                name="student_file"
                                accept=".xlsx,.xls"
                                required
                            >

                            <div class="invalid-feedback">
                                Sélectionnez un fichier Excel.
                            </div>

                            <div class="form-text">
                                Formats acceptés :
                                .xlsx et .xls.
                                Taille maximale : 10 Mo.
                            </div>

                        </div>


                        <div
                            id="studentImportFileInfo"
                            class="alert alert-light
                                   border d-none mb-0"
                        >
                            <div
                                class="d-flex
                                       align-items-center
                                       gap-3"
                            >

                                <div class="fs-3 text-success">
                                    <i
                                        class="bi
                                               bi-file-earmark-excel"
                                    ></i>
                                </div>

                                <div class="flex-grow-1">

                                    <div
                                        id="studentImportFileName"
                                        class="fw-semibold"
                                    ></div>

                                    <div
                                        id="studentImportFileSize"
                                        class="small text-muted"
                                    ></div>

                                </div>

                            </div>
                        </div>

                    </div>

                </div>

            </div>


            <div class="col-xl-4">

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
                                class="bi bi-list-check me-2"
                            ></i>

                            Règles d'import

                        </h2>
                    </div>


                    <div class="card-body">

                        <ul class="small mb-0 ps-3">

                            <li class="mb-2">
                                Ne modifiez pas les noms
                                des colonnes Excel.
                            </li>

                            <li class="mb-2">
                                Utilisez les codes de programmes
                                existants dans votre université.
                            </li>

                            <li class="mb-2">
                                Utilisez une année académique
                                existante dans MedTrack.
                            </li>

                            <li class="mb-2">
                                Les niveaux d'études doivent
                                déjà exister dans MedTrack.
                            </li>

                            <li class="mb-2">
                                La cohorte est facultative.
                            </li>

                            <li class="mb-2">
                                Les doublons seront détectés
                                automatiquement.
                            </li>

                            <li>
                                La création définitive intervient
                                uniquement après prévisualisation
                                et confirmation.
                            </li>

                        </ul>

                    </div>

                </div>


                <div class="card border-0 shadow-sm">

                    <div class="card-body">

                        <div class="d-grid gap-2">

                            <button
                                type="submit"
                                class="btn btn-primary"
                                id="studentImportSubmitButton"
                            >
                                <span
                                    id="studentImportSubmitIcon"
                                >
                                    <i
                                        class="bi bi-search me-1"
                                    ></i>
                                </span>

                                <span
                                    id="studentImportSubmitText"
                                >
                                    Analyser le fichier
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