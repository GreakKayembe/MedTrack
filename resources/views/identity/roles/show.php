<?php

declare(strict_types=1);

/**
 * @var array<string, mixed> $role
 * @var array<int, array<string, mixed>> $permissions
 * @var array<int, array<string, mixed>> $availablePermissions
 * @var array<int, array<string, mixed>> $platformUsers
 * @var array<int, array<string, mixed>> $memberships
 * @var array<string, mixed> $usage
 */

$role =
    is_array($role ?? null)
        ? $role
        : [];

$permissions =
    is_array($permissions ?? null)
        ? $permissions
        : [];

$availablePermissions =
    is_array($availablePermissions ?? null)
        ? $availablePermissions
        : [];

$platformUsers =
    is_array($platformUsers ?? null)
        ? $platformUsers
        : [];

$memberships =
    is_array($memberships ?? null)
        ? $memberships
        : [];

$usage =
    is_array($usage ?? null)
        ? $usage
        : [];

$csrfToken =
    (string) (
        $csrfToken
        ?? ''
    );

$id =
    (int) (
        $role['id']
        ?? 0
    );

$code =
    (string) (
        $role['code']
        ?? ''
    );

$name =
    (string) (
        $role['name']
        ?? 'Rôle'
    );

$organizationType =
    trim(
        (string) (
            $role['organization_type']
            ?? ''
        )
    );

$isSystem =
    (int) (
        $role['is_system']
        ?? 0
    ) === 1;

$scopeLabel =
    match ($organizationType) {
        'UNIVERSITY' =>
            'Université',

        'HOSPITAL' =>
            'Hôpital',

        'PROFESSIONAL_ORDER' =>
            'Ordre professionnel',

        'MINISTRY' =>
            'Ministère',

        default =>
            'Plateforme',
    };

$scopeClass =
    $organizationType === ''
        ? 'text-bg-primary'
        : 'text-bg-info';
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
                    RBAC
                </span>

                <span class="text-muted small">
                    Détail du rôle
                </span>

            </div>

            <h2 class="fw-bold mb-1">
                <?= htmlspecialchars(
                    $name,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </h2>

            <p class="text-muted mb-0">
                <code>
                    <?= htmlspecialchars(
                        $code,
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </code>
            </p>

        </div>


        <a
            href="/roles"
            class="btn btn-outline-secondary
                   d-inline-flex align-items-center gap-2"
        >
            <i class="bi bi-arrow-left"></i>
            Retour aux rôles
        </a>

    </div>


    <div class="row g-4">

        <!-- Main -->

        <div class="col-xl-8">

            <!-- Role -->

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
                            <i class="bi bi-shield-lock fs-4"></i>
                        </div>

                        <div>

                            <h5 class="fw-bold mb-1">
                                Informations du rôle
                            </h5>

                            <p class="text-muted small mb-0">
                                Portée et classification RBAC.
                            </p>

                        </div>

                    </div>


                    <div class="row g-4">

                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Nom
                            </div>

                            <div class="fw-semibold">
                                <?= htmlspecialchars(
                                    $name,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Code
                            </div>

                            <code>
                                <?= htmlspecialchars(
                                    $code,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </code>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Portée
                            </div>

                            <span
                                class="badge rounded-pill
                                       <?= $scopeClass ?>"
                            >
                                <?= htmlspecialchars(
                                    $scopeLabel,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </span>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Classification
                            </div>

                            <?php if ($isSystem): ?>

                                <span
                                    class="badge rounded-pill
                                           text-bg-success"
                                >
                                    Rôle système
                                </span>

                            <?php else: ?>

                                <span
                                    class="badge rounded-pill
                                           text-bg-secondary"
                                >
                                    Rôle personnalisé
                                </span>

                            <?php endif; ?>

                        </div>

                    </div>

                    <?php if (!$isSystem): ?>

                        <hr class="my-4">

                        <form
                            class="js-role-action-form"
                            action="/roles/<?= $id ?>/rename"
                            method="post"
                            data-confirm-title="Renommer ce rôle ?"
                            data-confirm-text="Le nouveau nom sera appliqué immédiatement."
                            data-confirm-button="Renommer"
                            novalidate
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

                            <div class="row g-3 align-items-end">

                                <div class="col-md-8">
                                    <label
                                        for="roleName"
                                        class="form-label fw-semibold"
                                    >
                                        Modifier le nom
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        id="roleName"
                                        name="name"
                                        value="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>"
                                        data-field-label="Nom du rôle"
                                        maxlength="150"
                                        required
                                    >
                                </div>

                                <div class="col-md-4 d-grid">
                                    <button
                                        type="submit"
                                        class="btn btn-outline-primary"
                                    >
                                        <i class="bi bi-pencil-square me-1"></i>
                                        Renommer
                                    </button>
                                </div>

                            </div>
                        </form>

                    <?php else: ?>

                        <div class="alert alert-light border mt-4 mb-0">
                            <i class="bi bi-lock-fill me-2 text-success"></i>
                            Ce rôle système est protégé et ne peut pas être renommé.
                        </div>

                    <?php endif; ?>

                </div>

            </div>


            <!-- Permissions -->

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-body p-4">

                    <div
                        class="d-flex
                               justify-content-between
                               align-items-center
                               gap-3 mb-4"
                    >

                        <div>

                            <h5 class="fw-bold mb-1">
                                Permissions
                            </h5>

                            <p class="text-muted small mb-0">
                                Permissions accordées
                                à ce rôle.
                            </p>

                        </div>


                        <span
                            class="badge rounded-pill
                                   text-bg-primary"
                        >
                            <?= count($permissions) ?>
                        </span>

                    </div>


                    <?php if (!$isSystem): ?>

                        <form
                            class="js-role-action-form mb-4"
                            action="/roles/<?= $id ?>/permissions"
                            method="post"
                            data-confirm-title="Attribuer cette permission ?"
                            data-confirm-text="La permission sera ajoutée à ce rôle."
                            data-confirm-button="Attribuer"
                            novalidate
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

                            <div class="row g-3 align-items-end">

                                <div class="col-md-8">
                                    <label
                                        for="permissionId"
                                        class="form-label fw-semibold"
                                    >
                                        Ajouter une permission
                                        <span class="text-danger">*</span>
                                    </label>

                                    <select
                                        class="form-select"
                                        id="permissionId"
                                        name="permission_id"
                                        data-field-label="Permission"
                                        required
                                    >
                                        <option value="">
                                            Sélectionner une permission
                                        </option>

                                        <?php foreach ($availablePermissions as $availablePermission): ?>
                                            <option
                                                value="<?= (int) ($availablePermission['id'] ?? 0) ?>"
                                            >
                                                <?= htmlspecialchars(
                                                    (string) ($availablePermission['code'] ?? ''),
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>
                                                —
                                                <?= htmlspecialchars(
                                                    (string) ($availablePermission['name'] ?? ''),
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-4 d-grid">
                                    <button
                                        type="submit"
                                        class="btn btn-primary"
                                        <?= $availablePermissions === [] ? 'disabled' : '' ?>
                                    >
                                        <i class="bi bi-plus-lg me-1"></i>
                                        Attribuer
                                    </button>
                                </div>

                            </div>
                        </form>

                    <?php else: ?>

                        <div class="alert alert-light border mb-4">
                            <i class="bi bi-shield-lock me-2 text-success"></i>
                            Les permissions d’un rôle système sont protégées.
                        </div>

                    <?php endif; ?>


                    <?php if ($permissions === []): ?>

                        <div
                            class="border rounded-3
                                   text-center
                                   text-muted py-4"
                        >
                            Aucune permission attribuée.
                        </div>

                    <?php else: ?>

                        <div class="row g-3">

                            <?php foreach (
                                $permissions
                                as $permission
                            ): ?>

                                <div class="col-md-6">

                                    <div
                                        class="border rounded-3
                                               p-3 h-100"
                                    >

                                        <code class="fw-semibold">

                                            <?= htmlspecialchars(
                                                (string) (
                                                    $permission['code']
                                                    ?? ''
                                                ),
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>

                                        </code>


                                        <div class="text-muted small mt-2">

                                            <?= htmlspecialchars(
                                                (string) (
                                                    $permission['name']
                                                    ?? ''
                                                ),
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>

                                        </div>

                                        <?php if (!$isSystem): ?>

                                            <?php
                                            $permissionId =
                                                (int) (
                                                    $permission['id']
                                                    ?? 0
                                                );
                                            ?>

                                            <form
                                                class="js-role-action-form mt-3"
                                                action="/roles/<?= $id ?>/permissions/<?= $permissionId ?>/remove"
                                                method="post"
                                                data-confirm-title="Retirer cette permission ?"
                                                data-confirm-text="Cette permission ne sera plus accordée par ce rôle."
                                                data-confirm-button="Retirer"
                                                data-confirm-danger="true"
                                                novalidate
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
                                                    <i class="bi bi-x-lg me-1"></i>
                                                    Retirer
                                                </button>
                                            </form>

                                        <?php endif; ?>

                                    </div>

                                </div>

                            <?php endforeach; ?>

                        </div>

                    <?php endif; ?>

                </div>

            </div>


            <!-- Platform users -->

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-body p-0">

                    <div class="p-4 border-bottom">

                        <div
                            class="d-flex
                                   justify-content-between
                                   align-items-center
                                   gap-3"
                        >

                            <div>

                                <h5 class="fw-bold mb-1">
                                    Utilisateurs plateforme
                                </h5>

                                <p class="text-muted small mb-0">
                                    Comptes auxquels ce rôle
                                    est attribué directement.
                                </p>

                            </div>


                            <span
                                class="badge rounded-pill
                                       text-bg-primary"
                            >
                                <?= count($platformUsers) ?>
                            </span>

                        </div>

                    </div>


                    <div class="table-responsive">

                        <table class="table align-middle mb-0">

                            <thead class="table-light">

                                <tr>

                                    <th class="ps-4">
                                        Utilisateur
                                    </th>

                                    <th>
                                        Statut
                                    </th>

                                    <th>
                                        Attribué le
                                    </th>

                                    <th class="text-end pe-4">
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                            <?php if ($platformUsers === []): ?>

                                <tr>

                                    <td
                                        colspan="4"
                                        class="text-center
                                               py-4 text-muted"
                                    >
                                        Aucun utilisateur plateforme
                                        ne possède ce rôle.
                                    </td>

                                </tr>

                            <?php else: ?>

                                <?php foreach (
                                    $platformUsers
                                    as $user
                                ): ?>

                                    <?php
                                    $userId =
                                        (int) (
                                            $user['id']
                                            ?? 0
                                        );

                                    $userStatus =
                                        (string) (
                                            $user['status']
                                            ?? 'PENDING'
                                        );

                                    [$userStatusClass, $userStatusLabel] =
                                        match ($userStatus) {
                                            'ACTIVE' =>
                                                [
                                                    'text-bg-success',
                                                    'Actif',
                                                ],

                                            'PENDING' =>
                                                [
                                                    'text-bg-warning',
                                                    'En attente',
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
                                                    $userStatus,
                                                ],
                                        };

                                    $identifier =
                                        trim(
                                            (string) (
                                                $user['email']
                                                ?? ''
                                            )
                                        );

                                    if ($identifier === '') {
                                        $identifier =
                                            trim(
                                                (string) (
                                                    $user['phone']
                                                    ?? ''
                                                )
                                            );
                                    }

                                    if ($identifier === '') {
                                        $identifier =
                                            'Utilisateur #' . $userId;
                                    }
                                    ?>

                                    <tr>

                                        <td class="ps-4">

                                            <div class="fw-semibold">

                                                <?= htmlspecialchars(
                                                    $identifier,
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>

                                            </div>

                                            <div class="text-muted small">
                                                ID #<?= $userId ?>
                                            </div>

                                        </td>


                                        <td>

                                            <span
                                                class="badge rounded-pill
                                                       <?= $userStatusClass ?>"
                                            >
                                                <?= htmlspecialchars(
                                                    $userStatusLabel,
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>
                                            </span>

                                        </td>


                                        <td>

                                            <?= htmlspecialchars(
                                                (string) (
                                                    $user['assigned_at']
                                                    ?? '—'
                                                ),
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>

                                        </td>


                                        <td class="text-end pe-4">

                                            <a
                                                href="/users/<?= $userId ?>"
                                                class="btn btn-sm
                                                       btn-outline-primary"
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


            <!-- Memberships -->

            <div class="card border-0 shadow-sm">

                <div class="card-body p-0">

                    <div class="p-4 border-bottom">

                        <div
                            class="d-flex
                                   justify-content-between
                                   align-items-center gap-3"
                        >

                            <div>

                                <h5 class="fw-bold mb-1">
                                    Memberships institutionnels
                                </h5>

                                <p class="text-muted small mb-0">
                                    Affectations institutionnelles
                                    utilisant ce rôle.
                                </p>

                            </div>


                            <span
                                class="badge rounded-pill
                                       text-bg-info"
                            >
                                <?= count($memberships) ?>
                            </span>

                        </div>

                    </div>


                    <div class="table-responsive">

                        <table class="table align-middle mb-0">

                            <thead class="table-light">

                                <tr>

                                    <th class="ps-4">
                                        Utilisateur
                                    </th>

                                    <th>
                                        Organisation
                                    </th>

                                    <th>
                                        Type
                                    </th>

                                    <th>
                                        Membership
                                    </th>

                                    <th class="text-end pe-4">
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                            <?php if ($memberships === []): ?>

                                <tr>

                                    <td
                                        colspan="5"
                                        class="text-center
                                               py-4 text-muted"
                                    >
                                        Aucun membership n’utilise
                                        ce rôle.
                                    </td>

                                </tr>

                            <?php else: ?>

                                <?php foreach (
                                    $memberships
                                    as $membership
                                ): ?>

                                    <?php
                                    $userId =
                                        (int) (
                                            $membership['user_id']
                                            ?? 0
                                        );

                                    $identifier =
                                        trim(
                                            (string) (
                                                $membership['email']
                                                ?? ''
                                            )
                                        );

                                    if ($identifier === '') {
                                        $identifier =
                                            trim(
                                                (string) (
                                                    $membership['phone']
                                                    ?? ''
                                                )
                                            );
                                    }

                                    if ($identifier === '') {
                                        $identifier =
                                            'Utilisateur #' . $userId;
                                    }

                                    $membershipStatus =
                                        (string) (
                                            $membership[
                                                'membership_status'
                                            ]
                                            ?? 'INVITED'
                                        );

                                    [$membershipClass, $membershipLabel] =
                                        match ($membershipStatus) {
                                            'ACTIVE' =>
                                                [
                                                    'text-bg-success',
                                                    'Actif',
                                                ],

                                            'INVITED' =>
                                                [
                                                    'text-bg-info',
                                                    'Invité',
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

                                    $orgType =
                                        (string) (
                                            $membership[
                                                'organization_type'
                                            ]
                                            ?? ''
                                        );

                                    $orgTypeLabel =
                                        match ($orgType) {
                                            'UNIVERSITY' =>
                                                'Université',

                                            'HOSPITAL' =>
                                                'Hôpital',

                                            'PROFESSIONAL_ORDER' =>
                                                'Ordre professionnel',

                                            'MINISTRY' =>
                                                'Ministère',

                                            default =>
                                                $orgType,
                                        };
                                    ?>

                                    <tr>

                                        <td class="ps-4">

                                            <div class="fw-semibold">

                                                <?= htmlspecialchars(
                                                    $identifier,
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>

                                            </div>

                                        </td>


                                        <td>

                                            <div class="fw-semibold">

                                                <?= htmlspecialchars(
                                                    (string) (
                                                        $membership[
                                                            'organization_name'
                                                        ]
                                                        ?? '—'
                                                    ),
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>

                                            </div>

                                            <div class="text-muted small">

                                                <?= htmlspecialchars(
                                                    (string) (
                                                        $membership[
                                                            'organization_code'
                                                        ]
                                                        ?? ''
                                                    ),
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>

                                            </div>

                                        </td>


                                        <td>

                                            <?= htmlspecialchars(
                                                $orgTypeLabel,
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>

                                        </td>


                                        <td>

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

                                        </td>


                                        <td class="text-end pe-4">

                                            <a
                                                href="/users/<?= $userId ?>"
                                                class="btn btn-sm
                                                       btn-outline-primary"
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
                            <i class="bi bi-shield-lock fs-2"></i>
                        </div>

                        <h5 class="fw-bold mb-1">

                            <?= htmlspecialchars(
                                $name,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>

                        </h5>

                        <code>
                            <?= htmlspecialchars(
                                $code,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </code>

                    </div>


                    <hr>


                    <div class="mb-4">

                        <div class="text-muted small mb-1">
                            ID du rôle
                        </div>

                        <div class="fw-semibold">
                            <?= $id ?>
                        </div>

                    </div>


                    <div class="mb-4">

                        <div class="text-muted small mb-2">
                            Portée
                        </div>

                        <span
                            class="badge rounded-pill
                                   <?= $scopeClass ?>"
                        >
                            <?= htmlspecialchars(
                                $scopeLabel,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </span>

                    </div>


                    <div class="mb-4">

                        <div class="text-muted small mb-2">
                            Classification
                        </div>

                        <span
                            class="badge rounded-pill
                                   <?= $isSystem
                                       ? 'text-bg-success'
                                       : 'text-bg-secondary' ?>"
                        >
                            <?= $isSystem
                                ? 'Système'
                                : 'Personnalisé' ?>
                        </span>

                    </div>


                    <div class="row g-3 mb-4">

                        <div class="col-4">

                            <div
                                class="border rounded-3
                                       text-center p-3"
                            >

                                <div class="fs-5 fw-bold">
                                    <?= count($permissions) ?>
                                </div>

                                <div class="text-muted small">
                                    Permissions
                                </div>

                            </div>

                        </div>


                        <div class="col-4">

                            <div
                                class="border rounded-3
                                       text-center p-3"
                            >

                                <div class="fs-5 fw-bold">
                                    <?= count($platformUsers) ?>
                                </div>

                                <div class="text-muted small">
                                    Plateforme
                                </div>

                            </div>

                        </div>


                        <div class="col-4">

                            <div
                                class="border rounded-3
                                       text-center p-3"
                            >

                                <div class="fs-5 fw-bold">
                                    <?= count($memberships) ?>
                                </div>

                                <div class="text-muted small">
                                    Membres
                                </div>

                            </div>

                        </div>

                    </div>


                    <hr>


                    <div class="small text-muted mb-4">

                        <?php if ($isSystem): ?>

                            <div class="d-flex gap-2">
                                <i class="bi bi-shield-lock text-success"></i>
                                <span>
                                    Rôle système protégé : renommage,
                                    permissions et suppression verrouillés.
                                </span>
                            </div>

                        <?php else: ?>

                            <div class="d-flex gap-2 mb-3">
                                <i class="bi bi-pencil-square text-primary"></i>
                                <span>
                                    Ce rôle personnalisé peut être renommé
                                    et ses permissions peuvent être administrées.
                                </span>
                            </div>

                            <div class="d-flex gap-2">
                                <i class="bi bi-trash text-danger"></i>
                                <span>
                                    La suppression est autorisée uniquement
                                    lorsque le rôle n’est attribué à personne.
                                </span>
                            </div>

                        <?php endif; ?>

                    </div>


                    <?php if (!$isSystem): ?>

                        <?php
                        $platformUsage =
                            (int) (
                                $usage['platform_users']
                                ?? count($platformUsers)
                            );

                        $membershipUsage =
                            (int) (
                                $usage['memberships']
                                ?? count($memberships)
                            );

                        $canDelete =
                            $platformUsage === 0
                            && $membershipUsage === 0;
                        ?>

                        <form
                            class="js-role-action-form mb-3"
                            action="/roles/<?= $id ?>/delete"
                            method="post"
                            data-confirm-title="Supprimer ce rôle ?"
                            data-confirm-text="Cette action est définitive."
                            data-confirm-button="Supprimer"
                            data-confirm-danger="true"
                            novalidate
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

                            <div class="d-grid">
                                <button
                                    type="submit"
                                    class="btn btn-outline-danger"
                                    <?= !$canDelete ? 'disabled' : '' ?>
                                >
                                    <i class="bi bi-trash me-1"></i>
                                    Supprimer le rôle
                                </button>
                            </div>

                            <?php if (!$canDelete): ?>
                                <div class="form-text mt-2">
                                    Suppression impossible : ce rôle est encore attribué.
                                </div>
                            <?php endif; ?>
                        </form>

                    <?php endif; ?>


                    <div class="d-grid">

                        <a
                            href="/roles"
                            class="btn btn-light"
                        >
                            <i class="bi bi-arrow-left me-1"></i>
                            Retour aux rôles
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>