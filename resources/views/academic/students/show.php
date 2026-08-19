<?php

declare(strict_types=1);

/**
 * @var array $student
 * @var array $academicEnrollments
 */

$student = $student ?? [];
$academicEnrollments = $academicEnrollments ?? [];

$studentId =
    (int) ($student['id'] ?? 0);

$firstName =
    trim(
        (string) ($student['first_name'] ?? '')
    );

$middleName =
    trim(
        (string) ($student['middle_name'] ?? '')
    );

$lastName =
    trim(
        (string) ($student['last_name'] ?? '')
    );

$fullName =
    trim(
        implode(
            ' ',
            array_filter(
                [
                    $firstName,
                    $middleName,
                    $lastName,
                ],
                static fn (string $value): bool =>
                    $value !== ''
            )
        )
    );

$uuid =
    (string) ($student['uuid'] ?? '');

$nationalStudentNumber =
    trim(
        (string) (
            $student['national_student_number']
            ?? ''
        )
    );

$email =
    trim(
        (string) ($student['email'] ?? '')
    );

$phone =
    trim(
        (string) ($student['phone'] ?? '')
    );

$gender =
    (string) ($student['gender'] ?? '');

$birthDate =
    trim(
        (string) ($student['birth_date'] ?? '')
    );

$birthPlace =
    trim(
        (string) ($student['birth_place'] ?? '')
    );

$nationality =
    trim(
        (string) ($student['nationality'] ?? '')
    );

$status =
    (string) ($student['status'] ?? 'INACTIVE');

$userId =
    $student['user_id'] ?? null;

$createdAt =
    trim(
        (string) ($student['created_at'] ?? '')
    );

$updatedAt =
    trim(
        (string) ($student['updated_at'] ?? '')
    );


$genderLabels = [
    'M' => 'Masculin',
    'F' => 'Féminin',
    'OTHER' => 'Autre',
    'UNSPECIFIED' => 'Non précisé',
];

$statusLabels = [
    'ACTIVE' => 'Actif',
    'SUSPENDED' => 'Suspendu',
    'GRADUATED' => 'Diplômé',
    'INACTIVE' => 'Inactif',
];

$statusClasses = [
    'ACTIVE' => 'success',
    'SUSPENDED' => 'warning',
    'GRADUATED' => 'primary',
    'INACTIVE' => 'secondary',
];

$enrollmentStatusLabels = [
    'PENDING' => 'En attente',
    'ACTIVE' => 'Active',
    'SUSPENDED' => 'Suspendue',
    'COMPLETED' => 'Terminée',
    'CANCELLED' => 'Annulée',
];

$enrollmentStatusClasses = [
    'PENDING' => 'warning',
    'ACTIVE' => 'success',
    'SUSPENDED' => 'danger',
    'COMPLETED' => 'primary',
    'CANCELLED' => 'secondary',
];

$genderLabel =
    $genderLabels[$gender]
    ?? 'Non renseigné';

$statusLabel =
    $statusLabels[$status]
    ?? $status;

$statusClass =
    $statusClasses[$status]
    ?? 'secondary';


$formatDate =
    static function (?string $date): string {
        if (
            $date === null
            || trim($date) === ''
        ) {
            return 'Non renseignée';
        }

        try {
            return (
                new DateTimeImmutable($date)
            )->format('d/m/Y');
        } catch (Throwable) {
            return $date;
        }
    };


$formatDateTime =
    static function (?string $date): string {
        if (
            $date === null
            || trim($date) === ''
        ) {
            return '—';
        }

        try {
            return (
                new DateTimeImmutable($date)
            )->format('d/m/Y à H:i');
        } catch (Throwable) {
            return $date;
        }
    };
?>

<div class="container-fluid py-4">

    <!-- Header -->

    <div
        class="d-flex flex-column flex-lg-row
               align-items-lg-center
               justify-content-between
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

            <div
                class="d-flex align-items-center gap-3"
            >
                <div
                    class="rounded-circle
                           bg-primary-subtle
                           text-primary
                           d-flex
                           align-items-center
                           justify-content-center
                           flex-shrink-0"
                    style="
                        width: 56px;
                        height: 56px;
                    "
                >
                    <i
                        class="bi bi-person fs-3"
                    ></i>
                </div>

                <div>
                    <div
                        class="d-flex flex-wrap
                               align-items-center gap-2"
                    >
                        <h1 class="h3 mb-0">
                            <?= htmlspecialchars(
                                $fullName !== ''
                                    ? $fullName
                                    : 'Étudiant',
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </h1>

                        <span
                            class="badge text-bg-<?= htmlspecialchars(
                                $statusClass,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>"
                        >
                            <?= htmlspecialchars(
                                $statusLabel,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </span>
                    </div>

                    <div class="text-muted mt-1">
                        <?php if (
                            $nationalStudentNumber !== ''
                        ): ?>

                            N° étudiant :
                            <span class="fw-medium">
                                <?= htmlspecialchars(
                                    $nationalStudentNumber,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </span>

                        <?php else: ?>

                            Aucun numéro national étudiant

                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>


        <div
            class="d-flex flex-wrap gap-2"
        >
            <a
                href="/students/<?= $studentId ?>/edit"
                class="btn btn-outline-primary"
            >
                <i class="bi bi-pencil me-1"></i>
                Modifier
            </a>

            <a
                href="/academic-enrollments/create?student_id=<?= $studentId ?>"
                class="btn btn-primary"
            >
                <i class="bi bi-mortarboard me-1"></i>
                Nouvelle inscription
            </a>
        </div>

    </div>


    <div class="row g-4">

        <!-- Main -->

        <div class="col-xl-8">

            <!-- Personal information -->

            <div class="card border-0 shadow-sm mb-4">

                <div
                    class="card-header
                           bg-transparent py-3"
                >
                    <h2 class="h5 mb-0">
                        <i
                            class="bi bi-person-vcard me-2"
                        ></i>
                        Informations personnelles
                    </h2>
                </div>

                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-6">
                            <div
                                class="small text-muted mb-1"
                            >
                                Prénom
                            </div>

                            <div class="fw-medium">
                                <?= htmlspecialchars(
                                    $firstName !== ''
                                        ? $firstName
                                        : '—',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div
                                class="small text-muted mb-1"
                            >
                                Postnom
                            </div>

                            <div class="fw-medium">
                                <?= htmlspecialchars(
                                    $middleName !== ''
                                        ? $middleName
                                        : '—',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div
                                class="small text-muted mb-1"
                            >
                                Nom
                            </div>

                            <div class="fw-medium">
                                <?= htmlspecialchars(
                                    $lastName !== ''
                                        ? $lastName
                                        : '—',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div
                                class="small text-muted mb-1"
                            >
                                Sexe / genre
                            </div>

                            <div class="fw-medium">
                                <?= htmlspecialchars(
                                    $genderLabel,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div
                                class="small text-muted mb-1"
                            >
                                Date de naissance
                            </div>

                            <div class="fw-medium">
                                <?= htmlspecialchars(
                                    $formatDate($birthDate),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div
                                class="small text-muted mb-1"
                            >
                                Lieu de naissance
                            </div>

                            <div class="fw-medium">
                                <?= htmlspecialchars(
                                    $birthPlace !== ''
                                        ? $birthPlace
                                        : '—',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div
                                class="small text-muted mb-1"
                            >
                                Nationalité
                            </div>

                            <div class="fw-medium">
                                <?= htmlspecialchars(
                                    $nationality !== ''
                                        ? $nationality
                                        : '—',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div
                                class="small text-muted mb-1"
                            >
                                Numéro national étudiant
                            </div>

                            <div class="fw-medium">
                                <?= htmlspecialchars(
                                    $nationalStudentNumber !== ''
                                        ? $nationalStudentNumber
                                        : '—',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </div>
                        </div>

                    </div>

                </div>
            </div>


            <!-- Contact -->

            <div class="card border-0 shadow-sm mb-4">

                <div
                    class="card-header
                           bg-transparent py-3"
                >
                    <h2 class="h5 mb-0">
                        <i
                            class="bi bi-telephone me-2"
                        ></i>
                        Coordonnées
                    </h2>
                </div>

                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-6">
                            <div
                                class="small text-muted mb-1"
                            >
                                Adresse e-mail
                            </div>

                            <?php if ($email !== ''): ?>

                                <a
                                    href="mailto:<?= htmlspecialchars(
                                        $email,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                    class="text-decoration-none"
                                >
                                    <?= htmlspecialchars(
                                        $email,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </a>

                            <?php else: ?>

                                <span class="text-muted">
                                    —
                                </span>

                            <?php endif; ?>
                        </div>


                        <div class="col-md-6">
                            <div
                                class="small text-muted mb-1"
                            >
                                Téléphone
                            </div>

                            <?php if ($phone !== ''): ?>

                                <a
                                    href="tel:<?= htmlspecialchars(
                                        $phone,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                    class="text-decoration-none"
                                >
                                    <?= htmlspecialchars(
                                        $phone,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </a>

                            <?php else: ?>

                                <span class="text-muted">
                                    —
                                </span>

                            <?php endif; ?>
                        </div>

                    </div>

                </div>
            </div>


            <!-- Academic history -->

            <div class="card border-0 shadow-sm">

                <div
                    class="card-header
                           bg-transparent py-3
                           d-flex flex-column flex-md-row
                           align-items-md-center
                           justify-content-between gap-3"
                >
                    <div class="d-flex align-items-center gap-2">
                        <h2 class="h5 mb-0">
                            <i class="bi bi-mortarboard me-2"></i>
                            Parcours académique
                        </h2>

                        <span class="badge text-bg-secondary">
                            <?= count($academicEnrollments) ?>
                        </span>
                    </div>

                    <a
                        href="/academic-enrollments/create?student_id=<?= $studentId ?>"
                        class="btn btn-sm btn-outline-primary"
                    >
                        <i class="bi bi-plus-lg me-1"></i>
                        Ajouter une inscription
                    </a>
                </div>

                <div class="card-body">

                    <?php if ($academicEnrollments === []): ?>

                        <div class="text-center py-4">
                            <i
                                class="bi bi-journal-bookmark
                                       fs-1 text-muted"
                            ></i>

                            <h3 class="h6 mt-3 mb-2">
                                Aucun parcours académique
                            </h3>

                            <p class="text-muted small mb-3">
                                Cet étudiant ne possède encore
                                aucune inscription académique.
                            </p>

                            <a
                                href="/academic-enrollments/create?student_id=<?= $studentId ?>"
                                class="btn btn-sm btn-primary"
                            >
                                <i class="bi bi-mortarboard me-1"></i>
                                Créer la première inscription
                            </a>
                        </div>

                    <?php else: ?>

                        <div class="table-responsive">

                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Année</th>
                                        <th>Université / programme</th>
                                        <th>Niveau / cohorte</th>
                                        <th>Matricule</th>
                                        <th>Statut</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>

                                <tbody>

                                <?php foreach ($academicEnrollments as $enrollment): ?>

                                    <?php
                                    $enrollmentId =
                                        (int) ($enrollment['id'] ?? 0);

                                    $academicYearLabel =
                                        trim((string) ($enrollment['academic_year_label'] ?? ''));

                                    $universityName =
                                        trim((string) ($enrollment['university_name'] ?? ''));

                                    $universityCode =
                                        trim((string) ($enrollment['university_code'] ?? ''));

                                    $programName =
                                        trim((string) ($enrollment['academic_program_name'] ?? ''));

                                    $programCode =
                                        trim((string) ($enrollment['academic_program_code'] ?? ''));

                                    $studyLevelName =
                                        trim((string) ($enrollment['study_level_name'] ?? ''));

                                    $studyLevelCode =
                                        trim((string) ($enrollment['study_level_code'] ?? ''));

                                    $cohortName =
                                        trim((string) ($enrollment['cohort_name'] ?? ''));

                                    $registrationNumber =
                                        trim((string) ($enrollment['registration_number'] ?? ''));

                                    $enrollmentStatus =
                                        strtoupper(
                                            trim((string) ($enrollment['status'] ?? 'PENDING'))
                                        );

                                    $enrollmentStatusLabel =
                                        $enrollmentStatusLabels[$enrollmentStatus]
                                        ?? $enrollmentStatus;

                                    $enrollmentStatusClass =
                                        $enrollmentStatusClasses[$enrollmentStatus]
                                        ?? 'secondary';

                                    $enrolledAt =
                                        trim((string) ($enrollment['enrolled_at'] ?? ''));
                                    ?>

                                    <tr>

                                        <td>
                                            <div class="fw-semibold">
                                                <?= htmlspecialchars(
                                                    $academicYearLabel !== ''
                                                        ? $academicYearLabel
                                                        : '—',
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>
                                            </div>

                                            <?php if ($enrolledAt !== ''): ?>

                                                <div class="small text-muted">
                                                    Inscrit le
                                                    <?= htmlspecialchars(
                                                        $formatDate($enrolledAt),
                                                        ENT_QUOTES,
                                                        'UTF-8'
                                                    ) ?>
                                                </div>

                                            <?php endif; ?>
                                        </td>


                                        <td>
                                            <div class="fw-semibold">
                                                <?= htmlspecialchars(
                                                    $universityName !== ''
                                                        ? $universityName
                                                        : '—',
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>
                                            </div>

                                            <?php if ($universityCode !== ''): ?>
                                                <div class="small text-muted">
                                                    <?= htmlspecialchars(
                                                        $universityCode,
                                                        ENT_QUOTES,
                                                        'UTF-8'
                                                    ) ?>
                                                </div>
                                            <?php endif; ?>

                                            <div class="mt-2">
                                                <?= htmlspecialchars(
                                                    $programName !== ''
                                                        ? $programName
                                                        : '—',
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>
                                            </div>

                                            <?php if ($programCode !== ''): ?>
                                                <div class="small text-muted">
                                                    <?= htmlspecialchars(
                                                        $programCode,
                                                        ENT_QUOTES,
                                                        'UTF-8'
                                                    ) ?>
                                                </div>
                                            <?php endif; ?>
                                        </td>


                                        <td>
                                            <div class="fw-medium">
                                                <?= htmlspecialchars(
                                                    $studyLevelName !== ''
                                                        ? $studyLevelName
                                                        : '—',
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>
                                            </div>

                                            <?php if ($studyLevelCode !== ''): ?>
                                                <div class="small text-muted">
                                                    <?= htmlspecialchars(
                                                        $studyLevelCode,
                                                        ENT_QUOTES,
                                                        'UTF-8'
                                                    ) ?>
                                                </div>
                                            <?php endif; ?>

                                            <div class="small mt-2">
                                                Cohorte :
                                                <?= htmlspecialchars(
                                                    $cohortName !== ''
                                                        ? $cohortName
                                                        : 'Non rattaché',
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>
                                            </div>
                                        </td>


                                        <td>
                                            <span class="fw-medium">
                                                <?= htmlspecialchars(
                                                    $registrationNumber !== ''
                                                        ? $registrationNumber
                                                        : '—',
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>
                                            </span>
                                        </td>


                                        <td>
                                            <span
                                                class="badge text-bg-<?= htmlspecialchars(
                                                    $enrollmentStatusClass,
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>"
                                            >
                                                <?= htmlspecialchars(
                                                    $enrollmentStatusLabel,
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>
                                            </span>
                                        </td>


                                        <td class="text-end">
                                            <div
                                                class="btn-group btn-group-sm"
                                                role="group"
                                            >
                                                <a
                                                    href="/academic-enrollments/<?= $enrollmentId ?>"
                                                    class="btn btn-outline-secondary"
                                                    title="Consulter l'inscription"
                                                >
                                                    <i class="bi bi-eye"></i>
                                                </a>

                                                <a
                                                    href="/academic-enrollments/<?= $enrollmentId ?>/edit"
                                                    class="btn btn-outline-primary"
                                                    title="Modifier l'inscription"
                                                >
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            </div>
                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                                </tbody>
                            </table>

                        </div>

                    <?php endif; ?>

                </div>
            </div>

        </div>


        <!-- Sidebar -->

        <div class="col-xl-4">

            <!-- Student record -->

            <div class="card border-0 shadow-sm mb-4">

                <div
                    class="card-header
                           bg-transparent py-3"
                >
                    <h2 class="h5 mb-0">
                        <i
                            class="bi bi-card-text me-2"
                        ></i>
                        Dossier MedTrack
                    </h2>
                </div>

                <div class="card-body">

                    <div class="mb-3">
                        <div
                            class="small text-muted mb-1"
                        >
                            Identifiant interne
                        </div>

                        <div class="fw-medium">
                            #<?= $studentId ?>
                        </div>
                    </div>


                    <div class="mb-3">
                        <div
                            class="small text-muted mb-1"
                        >
                            UUID
                        </div>

                        <div
                            class="small font-monospace
                                   text-break"
                        >
                            <?= htmlspecialchars(
                                $uuid !== ''
                                    ? $uuid
                                    : '—',
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </div>
                    </div>


                    <div class="mb-3">
                        <div
                            class="small text-muted mb-1"
                        >
                            Compte utilisateur
                        </div>

                        <div class="fw-medium">
                            <?php if ($userId !== null): ?>

                                #<?= (int) $userId ?>

                            <?php else: ?>

                                <span class="text-muted">
                                    Non associé
                                </span>

                            <?php endif; ?>
                        </div>
                    </div>


                    <div class="mb-0">
                        <div
                            class="small text-muted mb-1"
                        >
                            Statut
                        </div>

                        <span
                            class="badge text-bg-<?= htmlspecialchars(
                                $statusClass,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>"
                        >
                            <?= htmlspecialchars(
                                $statusLabel,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </span>
                    </div>

                </div>
            </div>


            <!-- Metadata -->

            <div class="card border-0 shadow-sm">

                <div
                    class="card-header
                           bg-transparent py-3"
                >
                    <h2 class="h5 mb-0">
                        <i
                            class="bi bi-clock-history me-2"
                        ></i>
                        Historique
                    </h2>
                </div>

                <div class="card-body">

                    <div class="mb-3">
                        <div
                            class="small text-muted mb-1"
                        >
                            Créé le
                        </div>

                        <div>
                            <?= htmlspecialchars(
                                $formatDateTime(
                                    $createdAt
                                ),
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </div>
                    </div>


                    <div>
                        <div
                            class="small text-muted mb-1"
                        >
                            Dernière modification
                        </div>

                        <div>
                            <?= htmlspecialchars(
                                $formatDateTime(
                                    $updatedAt
                                ),
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>

</div>