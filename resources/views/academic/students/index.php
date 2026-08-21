<?php

declare(strict_types=1);

/**
 * @var array $students
 * @var bool $isPlatform
 * @var bool $isUniversityContext
 */

$students = $students ?? [];
$isPlatform = (bool) ($isPlatform ?? false);
$isUniversityContext =
    (bool) ($isUniversityContext ?? false);

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

$genderLabels = [
    'M' => 'Masculin',
    'F' => 'Féminin',
    'OTHER' => 'Autre',
    'UNSPECIFIED' => 'Non précisé',
];
?>

<div class="container-fluid py-4">

    <div
        class="d-flex flex-column flex-md-row
               align-items-md-center justify-content-between
               gap-3 mb-4"
    >
        <div>
            <h1 class="h3 mb-1">
                Étudiants
            </h1>

            <p class="text-muted mb-0">
                <?php if ($isUniversityContext): ?>
                    Consultez les étudiants rattachés
                    à votre université.
                <?php else: ?>
                    Gérez les identités étudiantes
                    enregistrées dans MedTrack.
                <?php endif; ?>
            </p>
        </div>

        <div>
            <?php if ($isUniversityContext): ?>

                <a
                    href="/academic-enrollments/create"
                    class="btn btn-primary"
                >
                    <i class="bi bi-person-check me-1"></i>

                    Inscrire un étudiant
                </a>

            <?php elseif ($isPlatform): ?>

                <a
                    href="/students/create"
                    class="btn btn-primary"
                >
                    <i class="bi bi-person-plus me-1"></i>

                    Nouvel étudiant
                </a>

            <?php endif; ?>
        </div>
    </div>


    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <?php if ($students === []): ?>

                <div
                    class="text-center py-5"
                >
                    <div
                        class="mb-3"
                    >
                        <i
                            class="bi bi-people fs-1 text-muted"
                        ></i>
                    </div>

                    <h2 class="h5">
                        Aucun étudiant
                    </h2>

                    <p class="text-muted mb-4">
                        <?php if ($isUniversityContext): ?>
                            Aucun étudiant n'est actuellement
                            rattaché à votre université.
                        <?php else: ?>
                            Aucun étudiant n'est encore enregistré
                            dans MedTrack.
                        <?php endif; ?>
                    </p>

                    <?php if ($isUniversityContext): ?>

                        <a
                            href="/academic-enrollments/create"
                            class="btn btn-primary"
                        >
                            <i class="bi bi-person-check me-1"></i>

                            Inscrire un étudiant
                        </a>

                    <?php elseif ($isPlatform): ?>

                        <a
                            href="/students/create"
                            class="btn btn-primary"
                        >
                            <i class="bi bi-person-plus me-1"></i>

                            Ajouter un étudiant
                        </a>

                    <?php endif; ?>
                </div>

            <?php else: ?>

                <div class="table-responsive">

                    <table
                        class="table table-hover align-middle mb-0"
                    >
                        <thead>
                            <tr>
                                <th>
                                    Étudiant
                                </th>

                                <th>
                                    N° étudiant
                                </th>

                                <th>
                                    Contact
                                </th>

                                <th>
                                    Genre
                                </th>

                                <th>
                                    Statut
                                </th>

                                <th
                                    class="text-end"
                                >
                                    Actions
                                </th>
                            </tr>
                        </thead>

                        <tbody>

                        <?php foreach ($students as $student): ?>

                            <?php
                            $studentId =
                                (int) (
                                    $student['id']
                                    ?? 0
                                );

                            $firstName =
                                trim(
                                    (string) (
                                        $student['first_name']
                                        ?? ''
                                    )
                                );

                            $middleName =
                                trim(
                                    (string) (
                                        $student['middle_name']
                                        ?? ''
                                    )
                                );

                            $lastName =
                                trim(
                                    (string) (
                                        $student['last_name']
                                        ?? ''
                                    )
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
                                            static fn (
                                                string $value
                                            ): bool =>
                                                $value !== ''
                                        )
                                    )
                                );

                            $nationalNumber =
                                trim(
                                    (string) (
                                        $student[
                                            'national_student_number'
                                        ]
                                        ?? ''
                                    )
                                );

                            $email =
                                trim(
                                    (string) (
                                        $student['email']
                                        ?? ''
                                    )
                                );

                            $phone =
                                trim(
                                    (string) (
                                        $student['phone']
                                        ?? ''
                                    )
                                );

                            $gender =
                                (string) (
                                    $student['gender']
                                    ?? ''
                                );

                            $status =
                                (string) (
                                    $student['status']
                                    ?? 'INACTIVE'
                                );

                            $statusLabel =
                                $statusLabels[$status]
                                ?? $status;

                            $statusClass =
                                $statusClasses[$status]
                                ?? 'secondary';

                            $genderLabel =
                                $genderLabels[$gender]
                                ?? 'Non précisé';
                            ?>

                            <tr>

                                <td>
                                    <div
                                        class="d-flex align-items-center gap-3"
                                    >
                                        <div
                                            class="rounded-circle
                                                   bg-body-secondary
                                                   d-flex
                                                   align-items-center
                                                   justify-content-center
                                                   flex-shrink-0"
                                            style="
                                                width: 42px;
                                                height: 42px;
                                            "
                                        >
                                            <i
                                                class="bi bi-person"
                                            ></i>
                                        </div>

                                        <div>
                                            <a
                                                href="/students/<?= $studentId ?>"
                                                class="fw-semibold
                                                       text-decoration-none"
                                            >
                                                <?= htmlspecialchars(
                                                    $fullName !== ''
                                                        ? $fullName
                                                        : 'Étudiant',
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>
                                            </a>

                                            <?php if ($email !== ''): ?>

                                                <div
                                                    class="small text-muted"
                                                >
                                                    <?= htmlspecialchars(
                                                        $email,
                                                        ENT_QUOTES,
                                                        'UTF-8'
                                                    ) ?>
                                                </div>

                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>


                                <td>
                                    <?php if ($nationalNumber !== ''): ?>

                                        <span class="fw-medium">
                                            <?= htmlspecialchars(
                                                $nationalNumber,
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        </span>

                                    <?php else: ?>

                                        <span class="text-muted">
                                            —
                                        </span>

                                    <?php endif; ?>
                                </td>


                                <td>
                                    <?php if ($phone !== ''): ?>

                                        <div>
                                            <?= htmlspecialchars(
                                                $phone,
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        </div>

                                    <?php endif; ?>

                                    <?php if (
                                        $phone === ''
                                        && $email === ''
                                    ): ?>

                                        <span class="text-muted">
                                            —
                                        </span>

                                    <?php endif; ?>
                                </td>


                                <td>
                                    <?= htmlspecialchars(
                                        $genderLabel,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </td>


                                <td>
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
                                </td>


                                <td class="text-end">

                                    <div
                                        class="btn-group btn-group-sm"
                                        role="group"
                                    >
                                        <a
                                            href="/students/<?= $studentId ?>"
                                            class="btn btn-outline-secondary"
                                            title="Consulter"
                                        >
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <?php if ($isPlatform): ?>

                                            <a
                                                href="/students/<?= $studentId ?>/edit"
                                                class="btn btn-outline-primary"
                                                title="Modifier l'identité"
                                            >
                                                <i class="bi bi-pencil"></i>
                                            </a>

                                        <?php endif; ?>
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