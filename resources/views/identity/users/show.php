<?php

declare(strict_types=1);

/**
 * @var array<string, mixed> $user
 * @var array<int, array<string, mixed>> $platformRoles
 * @var array<int, array<string, mixed>> $availablePlatformRoles
 * @var array<int, array<string, mixed>> $platformPermissions
 * @var array<int, array<string, mixed>> $memberships
 * @var array<int, array<string, mixed>> $loginHistory
 * @var string $csrfToken
 */

$user =
    is_array($user ?? null)
        ? $user
        : [];

$platformRoles =
    is_array($platformRoles ?? null)
        ? $platformRoles
        : [];

$availablePlatformRoles =
    is_array($availablePlatformRoles ?? null)
        ? $availablePlatformRoles
        : [];

$csrfToken =
    (string) ($csrfToken ?? '');

$platformPermissions =
    is_array($platformPermissions ?? null)
        ? $platformPermissions
        : [];

$memberships =
    is_array($memberships ?? null)
        ? $memberships
        : [];

$loginHistory =
    is_array($loginHistory ?? null)
        ? $loginHistory
        : [];

$id =
    (int) ($user['id'] ?? 0);

$email =
    trim(
        (string) ($user['email'] ?? '')
    );

$phone =
    trim(
        (string) ($user['phone'] ?? '')
    );

$identifier =
    $email !== ''
        ? $email
        : (
            $phone !== ''
                ? $phone
                : 'Utilisateur #' . $id
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

$mfaEnabled =
    (int) (
        $user['mfa_enabled']
        ?? 0
    ) === 1;

$mustChangePassword =
    (int) (
        $user['must_change_password']
        ?? 0
    ) === 1;

$emailVerified =
    !empty(
        $user['email_verified_at']
    );

$phoneVerified =
    !empty(
        $user['phone_verified_at']
    );
?>

<div class="container-fluid px-0">

    <!-- Header -->

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
                    Supervision utilisateur
                </span>

            </div>

            <h2 class="fw-bold mb-1">
                Utilisateur #<?= $id ?>
            </h2>

            <p class="text-muted mb-0">
                <?= htmlspecialchars(
                    $identifier,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </p>

        </div>


        <a
            href="/users"
            class="btn btn-outline-secondary
                   d-inline-flex align-items-center gap-2"
        >
            <i class="bi bi-arrow-left"></i>
            Retour aux utilisateurs
        </a>

    </div>


    <div class="row g-4">

        <!-- Main -->

        <div class="col-xl-8">

            <!-- Account -->

            <div class="card border-0 shadow-sm mb-4">

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
                            <i class="bi bi-person fs-4"></i>
                        </div>

                        <div>

                            <h5 class="fw-bold mb-1">
                                Compte utilisateur
                            </h5>

                            <p class="text-muted small mb-0">
                                Identité technique et état du compte.
                            </p>

                        </div>

                    </div>


                    <div class="row g-4">

                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Adresse e-mail
                            </div>

                            <div class="fw-semibold">

                                <?= htmlspecialchars(
                                    $email !== ''
                                        ? $email
                                        : 'Non renseignée',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>

                            </div>

                            <?php if ($email !== ''): ?>

                                <span
                                    class="badge mt-2
                                           <?= $emailVerified
                                               ? 'text-bg-success'
                                               : 'text-bg-warning' ?>"
                                >
                                    <?= $emailVerified
                                        ? 'E-mail vérifié'
                                        : 'E-mail non vérifié' ?>
                                </span>

                            <?php endif; ?>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Téléphone
                            </div>

                            <div class="fw-semibold">

                                <?= htmlspecialchars(
                                    $phone !== ''
                                        ? $phone
                                        : 'Non renseigné',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>

                            </div>

                            <?php if ($phone !== ''): ?>

                                <span
                                    class="badge mt-2
                                           <?= $phoneVerified
                                               ? 'text-bg-success'
                                               : 'text-bg-warning' ?>"
                                >
                                    <?= $phoneVerified
                                        ? 'Téléphone vérifié'
                                        : 'Téléphone non vérifié' ?>
                                </span>

                            <?php endif; ?>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Statut
                            </div>

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

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Dernière connexion
                            </div>

                            <div class="fw-semibold">

                                <?= htmlspecialchars(
                                    (string) (
                                        $user['last_login_at']
                                        ?? 'Jamais'
                                    ),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Créé le
                            </div>

                            <div class="fw-semibold">

                                <?= htmlspecialchars(
                                    (string) (
                                        $user['created_at']
                                        ?? '—'
                                    ),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Dernière modification
                            </div>

                            <div class="fw-semibold">

                                <?= htmlspecialchars(
                                    (string) (
                                        $user['updated_at']
                                        ?? '—'
                                    ),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- Platform roles -->

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-body p-4">

                    <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 mb-4">

                        <div class="d-flex align-items-center gap-3">

                            <div
                                class="rounded-circle
                                       bg-info-subtle
                                       text-info
                                       d-flex align-items-center
                                       justify-content-center"
                                style="width:48px;height:48px;"
                            >
                                <i class="bi bi-shield-check fs-4"></i>
                            </div>

                            <div>

                                <h5 class="fw-bold mb-1">
                                    Rôles plateforme
                                </h5>

                                <p class="text-muted small mb-0">
                                    Accès attribués directement
                                    au niveau MedTrack.
                                </p>

                            </div>

                        </div>

                        <form
                            class="js-user-action-form"
                            action="/users/<?= $id ?>/platform-roles"
                            method="post"
                            novalidate
                            data-confirm-title="Attribuer ce rôle ?"
                            data-confirm-text="Ce rôle donnera de nouveaux accès plateforme à cet utilisateur."
                            data-confirm-button="Attribuer"
                        >

                            <input
                                type="hidden"
                                name="_token"
                                value="<?= htmlspecialchars(
                                    $csrfToken,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>"
                            >

                            <div class="input-group">

                                <select
                                    name="role_id"
                                    class="form-select"
                                    data-field-label="Rôle plateforme"
                                    required
                                >
                                    <option value="">
                                        Ajouter un rôle plateforme…
                                    </option>

                                    <?php foreach ($availablePlatformRoles as $availableRole): ?>

                                        <?php
                                        $availableRoleId =
                                            (int) (
                                                $availableRole['id']
                                                ?? 0
                                            );

                                        $alreadyAssigned = false;

                                        foreach ($platformRoles as $assignedRole) {
                                            if (
                                                (int) (
                                                    $assignedRole['id']
                                                    ?? 0
                                                ) === $availableRoleId
                                            ) {
                                                $alreadyAssigned = true;
                                                break;
                                            }
                                        }

                                        if ($alreadyAssigned) {
                                            continue;
                                        }
                                        ?>

                                        <option value="<?= $availableRoleId ?>">
                                            <?= htmlspecialchars(
                                                (string) (
                                                    $availableRole['name']
                                                    ?? $availableRole['code']
                                                    ?? 'Rôle'
                                                ),
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        </option>

                                    <?php endforeach; ?>

                                </select>

                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                >
                                    <i class="bi bi-plus-lg"></i>
                                    <span class="d-none d-lg-inline ms-1">
                                        Attribuer
                                    </span>
                                </button>

                            </div>

                        </form>

                    </div>


                    <?php if ($platformRoles === []): ?>

                        <div
                            class="border rounded-3
                                   text-center text-muted py-4"
                        >
                            Aucun rôle plateforme attribué.
                        </div>

                    <?php else: ?>

                        <div class="row g-3">

                            <?php foreach ($platformRoles as $role): ?>

                                <?php
                                $roleId =
                                    (int) (
                                        $role['id']
                                        ?? 0
                                    );
                                ?>

                                <div class="col-md-6">

                                    <div
                                        class="border rounded-3
                                               p-3 h-100"
                                    >

                                        <div
                                            class="d-flex
                                                   justify-content-between
                                                   align-items-start gap-2"
                                        >

                                            <div>

                                                <div class="fw-semibold">

                                                    <?= htmlspecialchars(
                                                        (string) (
                                                            $role['name']
                                                            ?? 'Rôle'
                                                        ),
                                                        ENT_QUOTES,
                                                        'UTF-8'
                                                    ) ?>

                                                </div>

                                                <code class="small">

                                                    <?= htmlspecialchars(
                                                        (string) (
                                                            $role['code']
                                                            ?? ''
                                                        ),
                                                        ENT_QUOTES,
                                                        'UTF-8'
                                                    ) ?>

                                                </code>

                                            </div>

                                            <?php if (
                                                (int) (
                                                    $role['is_system']
                                                    ?? 0
                                                ) === 1
                                            ): ?>

                                                <span
                                                    class="badge
                                                           text-bg-primary"
                                                >
                                                    Système
                                                </span>

                                            <?php endif; ?>

                                        </div>


                                        <?php if (!empty($role['assigned_at'])): ?>

                                            <div class="small text-muted mt-3">

                                                Attribué le

                                                <?= htmlspecialchars(
                                                    (string) $role['assigned_at'],
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>

                                            </div>

                                        <?php endif; ?>


                                        <div class="mt-3 pt-3 border-top">

                                            <form
                                                class="js-user-action-form"
                                                action="/users/<?= $id ?>/platform-roles/<?= $roleId ?>/remove"
                                                method="post"
                                                novalidate
                                                data-confirm-title="Retirer ce rôle ?"
                                                data-confirm-text="Les permissions accordées par ce rôle seront retirées à l’utilisateur."
                                                data-confirm-button="Retirer"
                                                data-confirm-danger="true"
                                            >

                                                <input
                                                    type="hidden"
                                                    name="_token"
                                                    value="<?= htmlspecialchars(
                                                        $csrfToken,
                                                        ENT_QUOTES,
                                                        'UTF-8'
                                                    ) ?>"
                                                >

                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                >
                                                    <i class="bi bi-trash me-1"></i>
                                                    Retirer
                                                </button>

                                            </form>

                                        </div>

                                    </div>

                                </div>

                            <?php endforeach; ?>

                        </div>

                    <?php endif; ?>

                </div>

            </div>


            <!-- Platform permissions -->

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-body p-4">

                    <h5 class="fw-bold mb-1">
                        Permissions plateforme effectives
                    </h5>

                    <p class="text-muted small mb-4">
                        Permissions obtenues via les rôles plateforme.
                    </p>


                    <?php if ($platformPermissions === []): ?>

                        <div
                            class="border rounded-3
                                   text-center text-muted py-4"
                        >
                            Aucune permission plateforme.
                        </div>

                    <?php else: ?>

                        <div class="d-flex flex-wrap gap-2">

                            <?php foreach (
                                $platformPermissions
                                as $permission
                            ): ?>

                                <span
                                    class="badge
                                           text-bg-light
                                           border"
                                    title="<?= htmlspecialchars(
                                        (string) (
                                            $permission['name']
                                            ?? ''
                                        ),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                >

                                    <?= htmlspecialchars(
                                        (string) (
                                            $permission['code']
                                            ?? ''
                                        ),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                </span>

                            <?php endforeach; ?>

                        </div>

                    <?php endif; ?>

                </div>

            </div>


            <!-- Memberships -->

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-body p-0">

                    <div class="p-4 border-bottom">

                        <h5 class="fw-bold mb-1">
                            Appartenances institutionnelles
                        </h5>

                        <p class="text-muted small mb-0">
                            Organisations auxquelles cet utilisateur
                            est ou a été rattaché.
                        </p>

                    </div>


                    <?php if ($memberships === []): ?>

                        <div class="text-center text-muted py-5">

                            <i
                                class="bi bi-buildings
                                       fs-2 d-block mb-2"
                            ></i>

                            Aucun membership institutionnel.

                        </div>

                    <?php else: ?>

                        <?php foreach ($memberships as $membership): ?>

                            <?php
                            $membershipId =
                                (int) (
                                    $membership['id']
                                    ?? 0
                                );

                            $membershipStatus =
                                (string) (
                                    $membership['status']
                                    ?? 'INVITED'
                                );

                            [$membershipClass, $membershipLabel] =
                                match ($membershipStatus) {
                                    'INVITED' =>
                                        [
                                            'text-bg-info',
                                            'Invité',
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

                                    'ENDED' =>
                                        [
                                            'text-bg-secondary',
                                            'Terminé',
                                        ],

                                    default =>
                                        [
                                            'text-bg-secondary',
                                            $membershipStatus,
                                        ],
                                };

                            $membershipRoles =
                                is_array(
                                    $membership['roles']
                                    ?? null
                                )
                                    ? $membership['roles']
                                    : [];

                            $membershipPermissions =
                                is_array(
                                    $membership['permissions']
                                    ?? null
                                )
                                    ? $membership['permissions']
                                    : [];

                            $availableMembershipRoles =
                                is_array(
                                    $membership['available_roles']
                                    ?? null
                                )
                                    ? $membership['available_roles']
                                    : [];
                            ?>

                            <div class="p-4 border-bottom">

                                <div
                                    class="d-flex flex-column
                                           flex-lg-row
                                           justify-content-between
                                           gap-3 mb-3"
                                >

                                    <div>

                                        <div class="fw-bold">

                                            <?= htmlspecialchars(
                                                (string) (
                                                    $membership[
                                                        'organization_name'
                                                    ]
                                                    ?? 'Organisation'
                                                ),
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>

                                        </div>

                                        <div class="text-muted small">

                                            <?= htmlspecialchars(
                                                (string) (
                                                    $membership[
                                                        'organization_type'
                                                    ]
                                                    ?? ''
                                                ),
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>

                                            <?php if (
                                                !empty(
                                                    $membership[
                                                        'organization_code'
                                                    ]
                                                )
                                            ): ?>

                                                ·

                                                <?= htmlspecialchars(
                                                    (string) $membership[
                                                        'organization_code'
                                                    ],
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>

                                            <?php endif; ?>

                                        </div>

                                    </div>

                                    <div>

                                        <span
                                            class="badge rounded-pill
                                                   <?= $membershipClass ?>"
                                        >
                                            <?= htmlspecialchars(
                                                $membershipLabel,
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        </span>

                                    </div>

                                </div>


                                <div class="mb-4">

                                    <div
                                        class="text-muted
                                               small fw-semibold mb-2"
                                    >
                                        Rôles
                                    </div>

                                    <?php if ($membershipRoles === []): ?>

                                        <span class="text-muted small">
                                            Aucun rôle.
                                        </span>

                                    <?php else: ?>

                                        <div class="d-flex flex-wrap gap-2">

                                            <?php foreach ($membershipRoles as $role): ?>

                                                <?php
                                                $membershipRoleId =
                                                    (int) (
                                                        $role['id']
                                                        ?? 0
                                                    );
                                                ?>

                                                <div
                                                    class="d-inline-flex
                                                           align-items-center
                                                           gap-2 border
                                                           rounded-pill
                                                           px-3 py-2"
                                                >

                                                    <span class="small fw-semibold">

                                                        <?= htmlspecialchars(
                                                            (string) (
                                                                $role['name']
                                                                ?? $role['code']
                                                                ?? ''
                                                            ),
                                                            ENT_QUOTES,
                                                            'UTF-8'
                                                        ) ?>

                                                    </span>

                                                    <form
                                                        class="js-user-action-form d-inline"
                                                        action="/memberships/<?= $membershipId ?>/roles/<?= $membershipRoleId ?>/remove"
                                                        method="post"
                                                        novalidate
                                                        data-confirm-title="Retirer ce rôle ?"
                                                        data-confirm-text="Les permissions institutionnelles correspondantes seront retirées."
                                                        data-confirm-button="Retirer"
                                                        data-confirm-danger="true"
                                                    >

                                                        <input
                                                            type="hidden"
                                                            name="_token"
                                                            value="<?= htmlspecialchars(
                                                                $csrfToken,
                                                                ENT_QUOTES,
                                                                'UTF-8'
                                                            ) ?>"
                                                        >

                                                        <button
                                                            type="submit"
                                                            class="btn btn-sm p-0 border-0 text-danger"
                                                            title="Retirer le rôle"
                                                        >
                                                            <i class="bi bi-x-lg"></i>
                                                        </button>

                                                    </form>

                                                </div>

                                            <?php endforeach; ?>

                                        </div>

                                    <?php endif; ?>


                                    <?php
                                    $assignableMembershipRoles = [];

                                    foreach ($availableMembershipRoles as $availableRole) {
                                        $availableRoleId =
                                            (int) (
                                                $availableRole['id']
                                                ?? 0
                                            );

                                        $alreadyAssigned = false;

                                        foreach ($membershipRoles as $assignedRole) {
                                            if (
                                                (int) (
                                                    $assignedRole['id']
                                                    ?? 0
                                                ) === $availableRoleId
                                            ) {
                                                $alreadyAssigned = true;
                                                break;
                                            }
                                        }

                                        if (!$alreadyAssigned) {
                                            $assignableMembershipRoles[] =
                                                $availableRole;
                                        }
                                    }
                                    ?>

                                    <?php if ($assignableMembershipRoles !== []): ?>

                                        <form
                                            class="js-user-action-form mt-3"
                                            action="/memberships/<?= $membershipId ?>/roles"
                                            method="post"
                                            novalidate
                                            data-confirm-title="Attribuer ce rôle ?"
                                            data-confirm-text="Ce rôle donnera de nouveaux accès dans cette organisation."
                                            data-confirm-button="Attribuer"
                                        >

                                            <input
                                                type="hidden"
                                                name="_token"
                                                value="<?= htmlspecialchars(
                                                    $csrfToken,
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>"
                                            >

                                            <div class="input-group">

                                                <select
                                                    name="role_id"
                                                    class="form-select"
                                                    data-field-label="Rôle institutionnel"
                                                    required
                                                >
                                                    <option value="">
                                                        Ajouter un rôle…
                                                    </option>

                                                    <?php foreach ($assignableMembershipRoles as $availableRole): ?>

                                                        <option
                                                            value="<?= (int) ($availableRole['id'] ?? 0) ?>"
                                                        >
                                                            <?= htmlspecialchars(
                                                                (string) (
                                                                    $availableRole['name']
                                                                    ?? $availableRole['code']
                                                                    ?? 'Rôle'
                                                                ),
                                                                ENT_QUOTES,
                                                                'UTF-8'
                                                            ) ?>
                                                        </option>

                                                    <?php endforeach; ?>

                                                </select>

                                                <button
                                                    type="submit"
                                                    class="btn btn-outline-primary"
                                                >
                                                    <i class="bi bi-plus-lg"></i>
                                                </button>

                                            </div>

                                        </form>

                                    <?php endif; ?>

                                </div>


                                <div>

                                    <div
                                        class="text-muted
                                               small fw-semibold mb-2"
                                    >
                                        Permissions effectives
                                    </div>

                                    <?php if ($membershipPermissions === []): ?>

                                        <span class="text-muted small">
                                            Aucune permission.
                                        </span>

                                    <?php else: ?>

                                        <div class="d-flex flex-wrap gap-2">

                                            <?php foreach ($membershipPermissions as $permission): ?>

                                                <span
                                                    class="badge
                                                           text-bg-light
                                                           border"
                                                >
                                                    <?= htmlspecialchars(
                                                        (string) (
                                                            $permission['code']
                                                            ?? ''
                                                        ),
                                                        ENT_QUOTES,
                                                        'UTF-8'
                                                    ) ?>
                                                </span>

                                            <?php endforeach; ?>

                                        </div>

                                    <?php endif; ?>

                                </div>

                            </div>

                        <?php endforeach; ?>

                    <?php endif; ?>

                </div>

            </div>


            <!-- Login history -->

            <div class="card border-0 shadow-sm">

                <div class="card-body p-0">

                    <div class="p-4 border-bottom">

                        <h5 class="fw-bold mb-1">
                            Historique de connexion
                        </h5>

                        <p class="text-muted small mb-0">
                            20 dernières tentatives associées
                            à cet utilisateur.
                        </p>

                    </div>


                    <div class="table-responsive">

                        <table class="table align-middle mb-0">

                            <thead class="table-light">

                                <tr>

                                    <th class="ps-4">
                                        Date
                                    </th>

                                    <th>
                                        Identifiant
                                    </th>

                                    <th>
                                        Adresse IP
                                    </th>

                                    <th>
                                        Résultat
                                    </th>

                                    <th class="pe-4">
                                        Motif
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                            <?php if ($loginHistory === []): ?>

                                <tr>

                                    <td
                                        colspan="5"
                                        class="text-center
                                               py-4 text-muted"
                                    >
                                        Aucun historique de connexion.
                                    </td>

                                </tr>

                            <?php else: ?>

                                <?php foreach (
                                    $loginHistory
                                    as $login
                                ): ?>

                                    <?php
                                    $success =
                                        (int) (
                                            $login['success']
                                            ?? 0
                                        ) === 1;
                                    ?>

                                    <tr>

                                        <td class="ps-4">

                                            <?= htmlspecialchars(
                                                (string) (
                                                    $login['created_at']
                                                    ?? '—'
                                                ),
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>

                                        </td>


                                        <td>

                                            <?= htmlspecialchars(
                                                (string) (
                                                    $login[
                                                        'login_identifier'
                                                    ]
                                                    ?? '—'
                                                ),
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>

                                        </td>


                                        <td>

                                            <code>

                                                <?= htmlspecialchars(
                                                    (string) (
                                                        $login['ip_address']
                                                        ?? '—'
                                                    ),
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>

                                            </code>

                                        </td>


                                        <td>

                                            <span
                                                class="badge rounded-pill
                                                       <?= $success
                                                           ? 'text-bg-success'
                                                           : 'text-bg-danger' ?>"
                                            >
                                                <?= $success
                                                    ? 'Réussie'
                                                    : 'Échouée' ?>
                                            </span>

                                        </td>


                                        <td class="pe-4">

                                            <?= htmlspecialchars(
                                                (string) (
                                                    $login['failure_reason']
                                                    ?? '—'
                                                ),
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>

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


        <!-- Right panel -->

        <div class="col-xl-4">

            <div
                class="card border-0 shadow-sm"
                style="position:sticky;top:90px;"
            >

                <div class="card-body p-4">

                    <div class="text-center mb-4">

                        <div
                            class="rounded-circle
                                   bg-primary-subtle
                                   text-primary
                                   d-flex align-items-center
                                   justify-content-center
                                   mx-auto mb-3"
                            style="width:72px;height:72px;"
                        >
                            <i class="bi bi-person fs-2"></i>
                        </div>

                        <h5 class="fw-bold mb-1">

                            <?= htmlspecialchars(
                                $identifier,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>

                        </h5>

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

                    </div>


                    <hr>


                    <div class="mb-4">

                        <div class="text-muted small mb-1">
                            ID
                        </div>

                        <div class="fw-semibold">
                            <?= $id ?>
                        </div>

                    </div>


                    <div class="mb-4">

                        <div class="text-muted small mb-1">
                            UUID
                        </div>

                        <code class="small">

                            <?= htmlspecialchars(
                                (string) (
                                    $user['uuid']
                                    ?? '—'
                                ),
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>

                        </code>

                    </div>


                    <div class="mb-4">

                        <div class="text-muted small mb-2">
                            Sécurité
                        </div>

                        <div class="d-flex flex-wrap gap-2">

                            <span
                                class="badge
                                       <?= $mfaEnabled
                                           ? 'text-bg-success'
                                           : 'text-bg-light' ?>"
                            >
                                <i class="bi bi-shield-lock me-1"></i>

                                <?= $mfaEnabled
                                    ? 'MFA activé'
                                    : 'MFA désactivé' ?>
                            </span>


                            <?php if ($mustChangePassword): ?>

                                <span class="badge text-bg-warning">
                                    <i class="bi bi-key me-1"></i>
                                    Mot de passe à changer
                                </span>

                            <?php endif; ?>

                        </div>

                    </div>


                    <div class="mb-4">

                        <div class="text-muted small mb-1">
                            Rôles plateforme
                        </div>

                        <div class="fs-5 fw-bold">
                            <?= count($platformRoles) ?>
                        </div>

                    </div>


                    <div class="mb-4">

                        <div class="text-muted small mb-1">
                            Organisations
                        </div>

                        <div class="fs-5 fw-bold">
                            <?= count($memberships) ?>
                        </div>

                    </div>


                    <hr>


                    <div class="mb-4">

                        <div class="fw-semibold mb-3">
                            Administration du compte
                        </div>

                        <form
                            class="js-user-action-form mb-3"
                            action="/users/<?= $id ?>/status"
                            method="post"
                            novalidate
                            data-confirm-title="Modifier le statut ?"
                            data-confirm-text="L’état d’accès de ce compte sera modifié."
                            data-confirm-button="Confirmer"
                        >

                            <input
                                type="hidden"
                                name="_token"
                                value="<?= htmlspecialchars(
                                    $csrfToken,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>"
                            >

                            <div class="input-group">

                                <select
                                    name="status"
                                    class="form-select"
                                    data-field-label="Statut du compte"
                                    required
                                >
                                    <option
                                        value="PENDING"
                                        <?= $status === 'PENDING'
                                            ? 'selected'
                                            : '' ?>
                                    >
                                        En attente
                                    </option>

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
                                        value="DISABLED"
                                        <?= $status === 'DISABLED'
                                            ? 'selected'
                                            : '' ?>
                                    >
                                        Désactivé
                                    </option>
                                </select>

                                <button
                                    type="submit"
                                    class="btn btn-outline-primary"
                                >
                                    Appliquer
                                </button>

                            </div>

                        </form>


                        <form
                            class="js-user-action-form"
                            action="/users/<?= $id ?>/password-change-requirement"
                            method="post"
                            novalidate
                            data-confirm-title="<?= $mustChangePassword
                                ? 'Retirer l’obligation ?'
                                : 'Forcer le changement ?' ?>"
                            data-confirm-text="<?= $mustChangePassword
                                ? 'L’utilisateur ne sera plus obligé de modifier son mot de passe.'
                                : 'L’utilisateur devra modifier son mot de passe selon le mécanisme de sécurité MedTrack.' ?>"
                            data-confirm-button="Confirmer"
                        >

                            <input
                                type="hidden"
                                name="_token"
                                value="<?= htmlspecialchars(
                                    $csrfToken,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>"
                            >

                            <input
                                type="hidden"
                                name="required"
                                value="<?= $mustChangePassword
                                    ? 'false'
                                    : 'true' ?>"
                            >

                            <button
                                type="submit"
                                class="btn btn-outline-warning w-100"
                            >
                                <i class="bi bi-key me-1"></i>

                                <?= $mustChangePassword
                                    ? 'Retirer l’obligation'
                                    : 'Exiger un nouveau mot de passe' ?>
                            </button>

                        </form>

                    </div>

                    <div class="alert alert-light border small mb-4">

                        <i class="bi bi-shield-check text-success me-1"></i>

                        Les modifications sensibles sont validées côté serveur.
                        Le dernier Super Administrateur MedTrack ne peut pas
                        perdre son accès critique.

                    </div>


                    <div class="d-grid">

                        <a
                            href="/users"
                            class="btn btn-light"
                        >
                            <i class="bi bi-arrow-left me-1"></i>
                            Retour aux utilisateurs
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>