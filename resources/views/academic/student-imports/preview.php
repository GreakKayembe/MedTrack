<?php

declare(strict_types=1);

/** @var array<string, mixed> $import */
/** @var list<array<string, mixed>> $rows */

$importId =
    (int) (
        $import['id']
        ?? 0
    );

$totalRows =
    (int) (
        $import['total_rows']
        ?? 0
    );

$validRows =
    (int) (
        $import['valid_rows']
        ?? 0
    );

$warningRows =
    (int) (
        $import['warning_rows']
        ?? 0
    );

$errorRows =
    (int) (
        $import['error_rows']
        ?? 0
    );

$existingRows =
    (int) (
        $import['existing_rows']
        ?? 0
    );

$canConfirm =
    $importId > 0
    && $totalRows > 0
    && $errorRows === 0
    && (
        $validRows > 0
        || $warningRows > 0
    );
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
                    href="/student-imports/create"
                    class="text-decoration-none small"
                >
                    <i class="bi bi-arrow-left me-1"></i>
                    Nouvel import
                </a>

            </div>

            <h1 class="h3 mb-1">
                Prévisualisation de l'import
            </h1>

            <p class="text-muted mb-0">
                Vérifiez les étudiants avant leur
                création définitive dans MedTrack.
            </p>

        </div>

    </div>


    <!--
    |--------------------------------------------------------------------------
    | Global status
    |--------------------------------------------------------------------------
    -->

    <?php if ($errorRows > 0): ?>

        <div
            class="alert alert-danger
                   border-0 shadow-sm mb-4"
        >

            <div class="d-flex gap-3">

                <div>
                    <i class="bi bi-exclamation-triangle fs-4"></i>
                </div>

                <div>

                    <div class="fw-semibold mb-1">
                        Certaines lignes contiennent des erreurs
                    </div>

                    <div class="small">
                        Corrigez le fichier Excel puis effectuez
                        un nouvel import avant de continuer.
                    </div>

                </div>

            </div>

        </div>

    <?php else: ?>

        <div
            class="alert alert-success
                   border-0 shadow-sm mb-4"
        >

            <div class="d-flex gap-3">

                <div>
                    <i class="bi bi-check-circle fs-4"></i>
                </div>

                <div>

                    <div class="fw-semibold mb-1">
                        Fichier prêt à être importé
                    </div>

                    <div class="small">
                        Toutes les lignes ont été analysées.
                        Vérifiez les informations avant
                        de confirmer l'import.
                    </div>

                </div>

            </div>

        </div>

    <?php endif; ?>


    <!--
    |--------------------------------------------------------------------------
    | Statistics
    |--------------------------------------------------------------------------
    -->

    <div class="row g-3 mb-4">

        <div class="col-6 col-md">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small mb-1">
                        Total
                    </div>

                    <div class="fs-4 fw-bold">
                        <?= $totalRows ?>
                    </div>

                </div>

            </div>

        </div>


        <div class="col-6 col-md">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-success small mb-1">
                        Valides
                    </div>

                    <div class="fs-4 fw-bold text-success">
                        <?= $validRows ?>
                    </div>

                </div>

            </div>

        </div>


        <div class="col-6 col-md">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-warning small mb-1">
                        Avertissements
                    </div>

                    <div class="fs-4 fw-bold text-warning">
                        <?= $warningRows ?>
                    </div>

                </div>

            </div>

        </div>


        <div class="col-6 col-md">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-danger small mb-1">
                        Erreurs
                    </div>

                    <div class="fs-4 fw-bold text-danger">
                        <?= $errorRows ?>
                    </div>

                </div>

            </div>

        </div>


        <div class="col-6 col-md">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-primary small mb-1">
                        Existants
                    </div>

                    <div class="fs-4 fw-bold text-primary">
                        <?= $existingRows ?>
                    </div>

                </div>

            </div>

        </div>

    </div>


    <!--
    |--------------------------------------------------------------------------
    | Rows
    |--------------------------------------------------------------------------
    -->

    <div class="card border-0 shadow-sm mb-4">

        <div
            class="card-header bg-transparent py-3
                   d-flex align-items-center
                   justify-content-between gap-3"
        >

            <h2 class="h5 mb-0">
                <i class="bi bi-people me-2"></i>
                Étudiants détectés
            </h2>

            <span class="badge bg-light text-dark">
                <?= $totalRows ?>
                ligne<?= $totalRows !== 1 ? 's' : '' ?>
            </span>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table
                    class="table table-hover
                           align-middle mb-0"
                >

                    <thead class="table-light">

                        <tr>

                            <th class="ps-3">
                                Ligne
                            </th>

                            <th>
                                Étudiant
                            </th>

                            <th>
                                Email
                            </th>

                            <th>
                                Matricule
                            </th>

                            <th>
                                Programme
                            </th>

                            <th>
                                Année
                            </th>

                            <th>
                                Niveau
                            </th>

                            <th>
                                Statut
                            </th>

                            <th class="pe-3">
                                Détails
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php foreach ($rows as $row): ?>

                        <?php
                        $status =
                            strtoupper(
                                (string) (
                                    $row['status']
                                    ?? 'ERROR'
                                )
                            );

                        $errors =
                            !empty(
                                $row['errors_json']
                            )
                                ? json_decode(
                                    (string)
                                    $row['errors_json'],
                                    true
                                )
                                : [];

                        $warnings =
                            !empty(
                                $row['warnings_json']
                            )
                                ? json_decode(
                                    (string)
                                    $row['warnings_json'],
                                    true
                                )
                                : [];

                        if (!is_array($errors)) {
                            $errors = [];
                        }

                        if (!is_array($warnings)) {
                            $warnings = [];
                        }

                        $badgeClass =
                            match ($status) {
                                'VALID' =>
                                    'bg-success',

                                'WARNING' =>
                                    'bg-warning text-dark',

                                'ERROR' =>
                                    'bg-danger',

                                'EXISTING' =>
                                    'bg-primary',

                                default =>
                                    'bg-secondary',
                            };
                        ?>

                        <tr>

                            <td class="ps-3 text-muted">

                                <?= (int) (
                                    $row['source_row_number']
                                    ?? 0
                                ) ?>

                            </td>


                            <td>

                                <div class="fw-semibold">

                                    <?= htmlspecialchars(
                                        trim(
                                            (string) (
                                                $row['first_name']
                                                ?? ''
                                            )
                                            . ' '
                                            . (string) (
                                                $row['last_name']
                                                ?? ''
                                            )
                                        ),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                </div>

                            </td>


                            <td>

                                <?= htmlspecialchars(
                                    (string) (
                                        $row['email']
                                        ?? ''
                                    ),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>

                            </td>


                            <td>

                                <span class="font-monospace small">

                                    <?= htmlspecialchars(
                                        (string) (
                                            $row[
                                                'registration_number'
                                            ]
                                            ?? ''
                                        ),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                </span>

                            </td>


                            <td>

                                <?= htmlspecialchars(
                                    (string) (
                                        $row[
                                            'academic_program_code'
                                        ]
                                        ?? ''
                                    ),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>

                            </td>


                            <td>

                                <?= htmlspecialchars(
                                    (string) (
                                        $row[
                                            'academic_year_label'
                                        ]
                                        ?? ''
                                    ),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>

                            </td>


                            <td>

                                <?= htmlspecialchars(
                                    (string) (
                                        $row[
                                            'study_level_code'
                                        ]
                                        ?? ''
                                    ),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>

                            </td>


                            <td>

                                <span
                                    class="badge <?= $badgeClass ?>"
                                >
                                    <?= htmlspecialchars(
                                        $status,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </span>

                            </td>


                            <td class="pe-3">

                                <?php if ($errors !== []): ?>

                                    <?php foreach (
                                        $errors
                                        as $error
                                    ): ?>

                                        <div
                                            class="small text-danger mb-1"
                                        >
                                            <i
                                                class="bi bi-x-circle me-1"
                                            ></i>

                                            <?= htmlspecialchars(
                                                (string) $error,
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        </div>

                                    <?php endforeach; ?>


                                <?php elseif (
                                    $warnings !== []
                                ): ?>

                                    <?php foreach (
                                        $warnings
                                        as $warning
                                    ): ?>

                                        <div
                                            class="small text-warning mb-1"
                                        >
                                            <i
                                                class="bi bi-exclamation-triangle me-1"
                                            ></i>

                                            <?= htmlspecialchars(
                                                (string) $warning,
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        </div>

                                    <?php endforeach; ?>


                                <?php else: ?>

                                    <span
                                        class="small text-success"
                                    >
                                        <i
                                            class="bi bi-check-circle me-1"
                                        ></i>

                                        Aucun problème détecté
                                    </span>

                                <?php endif; ?>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    <!--
    |--------------------------------------------------------------------------
    | Confirmation
    |--------------------------------------------------------------------------
    -->

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <div
                class="d-flex flex-column flex-md-row
                       align-items-md-center
                       justify-content-between
                       gap-3"
            >

                <div>

                    <div class="fw-semibold">
                        Confirmation de l'import
                    </div>

                    <div class="small text-muted">
                        Cette action créera réellement
                        les étudiants et leurs inscriptions.
                    </div>

                </div>


                <div class="d-flex gap-2">

                    <a
                        href="/student-imports/create"
                        class="btn btn-outline-secondary"
                    >
                        Annuler
                    </a>


                    <button
                        type="button"
                        class="btn btn-primary"
                        id="studentImportConfirmButton"
                        data-import-id="<?= $importId ?>"
                        <?= !$canConfirm
                            ? 'disabled'
                            : '' ?>
                    >
                        <span id="studentImportConfirmIcon">
                            <i class="bi bi-check-lg me-1"></i>
                        </span>

                        <span id="studentImportConfirmText">
                            Confirmer l'import
                        </span>
                    </button>

                </div>

            </div>

        </div>

    </div>

</div>