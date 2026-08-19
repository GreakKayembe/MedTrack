<?php

declare(strict_types=1);

/**
 * @var array<string, mixed> $metrics
 * @var array<string, mixed> $accessMetrics
 * @var array<string, mixed> $loginMetrics
 * @var array<int, array<string, mixed>> $users
 */

$metrics =
    is_array($metrics ?? null)
        ? $metrics
        : [];

$accessMetrics =
    is_array($accessMetrics ?? null)
        ? $accessMetrics
        : [];

$loginMetrics =
    is_array($loginMetrics ?? null)
        ? $loginMetrics
        : [];

$users =
    is_array($users ?? null)
        ? $users
        : [];

$totalUsers =
    (int) ($metrics['total'] ?? 0);

$activeUsers =
    (int) ($metrics['active'] ?? 0);

$suspendedUsers =
    (int) ($metrics['suspended'] ?? 0);

$mfaUsers =
    (int) ($metrics['mfa_enabled'] ?? 0);

$platformUsers =
    (int) ($accessMetrics['platform_users'] ?? 0);

$activeMemberships =
    (int) ($accessMetrics['active_memberships'] ?? 0);

$successfulLogins =
    (int) ($loginMetrics['successful_attempts'] ?? 0);

$failedLogins =
    (int) ($loginMetrics['failed_attempts'] ?? 0);
?>

<div class="container-fluid px-0">

    <div
        class="d-flex flex-column flex-lg-row
               justify-content-between align-items-lg-center
               gap-3 mb-4"
    >

        <div>

            <div class="d-flex align-items-center gap-2 mb-2">

                <span class="badge rounded-pill text-bg-primary">
                    Identity
                </span>

                <span class="text-muted small">
                    Supervision plateforme
                </span>

            </div>

            <h2 class="fw-bold mb-1">
                Utilisateurs
            </h2>

            <p class="text-muted mb-0">
                Supervision des comptes, accès plateforme,
                memberships et sécurité MedTrack.
            </p>

        </div>

    </div>


    <div class="row g-3 mb-4">

        <div class="col-md-6 col-xl-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-4">

                    <div class="d-flex align-items-center gap-3">

                        <div
                            class="rounded-circle
                                   bg-primary-subtle
                                   text-primary
                                   d-flex align-items-center
                                   justify-content-center"
                            style="width:48px;height:48px;"
                        >
                            <i class="bi bi-people-fill fs-4"></i>
                        </div>

                        <div>

                            <div class="text-muted small">
                                Comptes
                            </div>

                            <div class="fs-4 fw-bold">
                                <?= $totalUsers ?>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-6 col-xl-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-4">

                    <div class="d-flex align-items-center gap-3">

                        <div
                            class="rounded-circle
                                   bg-success-subtle
                                   text-success
                                   d-flex align-items-center
                                   justify-content-center"
                            style="width:48px;height:48px;"
                        >
                            <i class="bi bi-person-check fs-4"></i>
                        </div>

                        <div>

                            <div class="text-muted small">
                                Actifs
                            </div>

                            <div class="fs-4 fw-bold">
                                <?= $activeUsers ?>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-6 col-xl-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-4">

                    <div class="d-flex align-items-center gap-3">

                        <div
                            class="rounded-circle
                                   bg-warning-subtle
                                   text-warning
                                   d-flex align-items-center
                                   justify-content-center"
                            style="width:48px;height:48px;"
                        >
                            <i class="bi bi-person-dash fs-4"></i>
                        </div>

                        <div>

                            <div class="text-muted small">
                                Suspendus
                            </div>

                            <div class="fs-4 fw-bold">
                                <?= $suspendedUsers ?>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-6 col-xl-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-4">

                    <div class="d-flex align-items-center gap-3">

                        <div
                            class="rounded-circle
                                   bg-info-subtle
                                   text-info
                                   d-flex align-items-center
                                   justify-content-center"
                            style="width:48px;height:48px;"
                        >
                            <i class="bi bi-shield-lock fs-4"></i>
                        </div>

                        <div>

                            <div class="text-muted small">
                                MFA activé
                            </div>

                            <div class="fs-4 fw-bold">
                                <?= $mfaUsers ?>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <div class="row g-4 mb-4">

        <div class="col-xl-6">

            <div class="card border-0 shadow-sm h-100">

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
                            <i class="bi bi-diagram-3 fs-4"></i>
                        </div>

                        <div>

                            <h5 class="fw-bold mb-1">
                                Accès
                            </h5>

                            <p class="text-muted small mb-0">
                                Répartition des accès plateforme
                                et institutionnels.
                            </p>

                        </div>

                    </div>


                    <div class="row g-3">

                        <div class="col-md-6">

                            <div class="border rounded-3 p-3">

                                <div class="text-muted small">
                                    Utilisateurs plateforme
                                </div>

                                <div class="fs-5 fw-bold">
                                    <?= $platformUsers ?>
                                </div>

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="border rounded-3 p-3">

                                <div class="text-muted small">
                                    Memberships actifs
                                </div>

                                <div class="fs-5 fw-bold">
                                    <?= $activeMemberships ?>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-6">

            <div class="card border-0 shadow-sm h-100">

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
                            <i class="bi bi-box-arrow-in-right fs-4"></i>
                        </div>

                        <div>

                            <h5 class="fw-bold mb-1">
                                Connexions — 30 jours
                            </h5>

                            <p class="text-muted small mb-0">
                                Tentatives récentes de connexion.
                            </p>

                        </div>

                    </div>


                    <div class="row g-3">

                        <div class="col-md-6">

                            <div class="border rounded-3 p-3">

                                <div class="text-muted small">
                                    Réussies
                                </div>

                                <div class="fs-5 fw-bold text-success">
                                    <?= $successfulLogins ?>
                                </div>

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="border rounded-3 p-3">

                                <div class="text-muted small">
                                    Échouées
                                </div>

                                <div class="fs-5 fw-bold text-danger">
                                    <?= $failedLogins ?>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <div class="card border-0 shadow-sm">

        <div class="card-body p-0">

            <div class="p-4 border-bottom">

                <h5 class="fw-bold mb-1">
                    Répertoire des utilisateurs
                </h5>

                <p class="text-muted small mb-0">
                    Comptes enregistrés sur la plateforme MedTrack.
                </p>

            </div>


            <div class="table-responsive">

                <table class="table align-middle mb-0">

                    <thead class="table-light">

                        <tr>
                            <th class="ps-4">
                                Utilisateur
                            </th>

                            <th>
                                Contact
                            </th>

                            <th>
                                Accès plateforme
                            </th>

                            <th>
                                Memberships
                            </th>

                            <th>
                                Sécurité
                            </th>

                            <th>
                                Dernière connexion
                            </th>

                            <th>
                                Statut
                            </th>

                            <th class="text-end pe-4">
                                Actions
                            </th>
                        </tr>

                    </thead>


                    <tbody>

                    <?php if ($users === []): ?>

                        <tr>

                            <td
                                colspan="8"
                                class="text-center py-5"
                            >

                                <div class="text-muted">

                                    <i
                                        class="bi bi-people
                                               fs-1 d-block mb-3"
                                    ></i>

                                    <strong class="d-block mb-1">
                                        Aucun utilisateur
                                    </strong>

                                    <span class="small">
                                        Les comptes MedTrack
                                        apparaîtront ici.
                                    </span>

                                </div>

                            </td>

                        </tr>

                    <?php else: ?>

                        <?php foreach ($users as $user): ?>

                            <?php
                            $id =
                                (int) (
                                    $user['id']
                                    ?? 0
                                );

                            $status =
                                (string) (
                                    $user['status']
                                    ?? 'PENDING'
                                );

                            [$statusClass, $statusLabel] =
                                match ($status) {
                                    'PENDING' =>
                                        [
                                            'text-bg-warning',
                                            'En attente',
                                        ],

                                    'ACTIVE' =>
                                        [
                                            'text-bg-success',
                                            'Actif',
                                        ],

                                    'SUSPENDED' =>
                                        [
                                            'text-bg-warning',
                                            'Suspendu',
                                        ],

                                    'DISABLED' =>
                                        [
                                            'text-bg-secondary',
                                            'Désactivé',
                                        ],

                                    default =>
                                        [
                                            'text-bg-secondary',
                                            $status,
                                        ],
                                };

                            $email =
                                trim(
                                    (string) (
                                        $user['email']
                                        ?? ''
                                    )
                                );

                            $phone =
                                trim(
                                    (string) (
                                        $user['phone']
                                        ?? ''
                                    )
                                );

                            $identifier =
                                $email !== ''
                                    ? $email
                                    : (
                                        $phone !== ''
                                            ? $phone
                                            : 'Utilisateur #' . $id
                                    );
                            ?>

                            <tr>

                                <td class="ps-4">

                                    <div class="d-flex align-items-center gap-3">

                                        <div
                                            class="rounded-circle
                                                   bg-primary-subtle
                                                   text-primary
                                                   d-flex align-items-center
                                                   justify-content-center
                                                   flex-shrink-0"
                                            style="width:42px;height:42px;"
                                        >
                                            <i class="bi bi-person"></i>
                                        </div>

                                        <div>

                                            <a
                                                href="/users/<?= $id ?>"
                                                class="fw-semibold
                                                       text-decoration-none"
                                            >
                                                <?= htmlspecialchars(
                                                    $identifier,
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>
                                            </a>

                                            <div class="text-muted small">
                                                ID #<?= $id ?>
                                            </div>

                                        </div>

                                    </div>

                                </td>


                                <td>

                                    <?php if ($email !== ''): ?>

                                        <div class="small">

                                            <i
                                                class="bi bi-envelope
                                                       text-muted me-1"
                                            ></i>

                                            <?= htmlspecialchars(
                                                $email,
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>

                                        </div>

                                    <?php endif; ?>


                                    <?php if ($phone !== ''): ?>

                                        <div class="small text-muted">

                                            <i
                                                class="bi bi-telephone
                                                       me-1"
                                            ></i>

                                            <?= htmlspecialchars(
                                                $phone,
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>

                                        </div>

                                    <?php endif; ?>

                                </td>


                                <td>

                                    <span class="badge text-bg-light">

                                        <?= (int) (
                                            $user['platform_role_count']
                                            ?? 0
                                        ) ?>

                                        rôle(s)

                                    </span>

                                </td>


                                <td>

                                    <span class="badge text-bg-light">

                                        <?= (int) (
                                            $user['active_membership_count']
                                            ?? 0
                                        ) ?>

                                        actif(s)

                                    </span>

                                </td>


                                <td>

                                    <div class="d-flex gap-1 flex-wrap">

                                        <?php if (
                                            (int) (
                                                $user['mfa_enabled']
                                                ?? 0
                                            ) === 1
                                        ): ?>

                                            <span
                                                class="badge
                                                       text-bg-success"
                                            >
                                                MFA
                                            </span>

                                        <?php else: ?>

                                            <span
                                                class="badge
                                                       text-bg-light"
                                            >
                                                Sans MFA
                                            </span>

                                        <?php endif; ?>


                                        <?php if (
                                            (int) (
                                                $user[
                                                    'must_change_password'
                                                ]
                                                ?? 0
                                            ) === 1
                                        ): ?>

                                            <span
                                                class="badge
                                                       text-bg-warning"
                                            >
                                                MDP à changer
                                            </span>

                                        <?php endif; ?>

                                    </div>

                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        (string) (
                                            $user['last_login_at']
                                            ?? 'Jamais'
                                        ),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                </td>


                                <td>

                                    <span
                                        class="badge rounded-pill
                                               <?= $statusClass ?>"
                                    >
                                        <?= htmlspecialchars(
                                            $statusLabel,
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </span>

                                </td>


                                <td class="text-end pe-4">

                                    <a
                                        href="/users/<?= $id ?>"
                                        class="btn btn-sm
                                               btn-outline-primary"
                                        title="Consulter"
                                    >
                                        <i class="bi bi-eye"></i>
                                    </a>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>
