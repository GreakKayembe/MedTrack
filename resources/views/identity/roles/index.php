<?php

declare(strict_types=1);

/**
 * @var array<string, mixed> $metrics
 * @var array<int, array<string, mixed>> $roles
 * @var array<int, array<string, mixed>> $permissions
 */

$metrics =
    is_array($metrics ?? null)
        ? $metrics
        : [];

$roles =
    is_array($roles ?? null)
        ? $roles
        : [];

$permissions =
    is_array($permissions ?? null)
        ? $permissions
        : [];

$csrfToken =
    (string) (
        $csrfToken
        ?? ''
    );

$totalRoles =
    (int) ($metrics['total_roles'] ?? 0);

$systemRoles =
    (int) ($metrics['system_roles'] ?? 0);

$platformRoles =
    (int) ($metrics['platform_roles'] ?? 0);

$organizationRoles =
    (int) ($metrics['organization_roles'] ?? 0);

$totalPermissions =
    (int) ($metrics['total_permissions'] ?? 0);

$rolePermissionLinks =
    (int) ($metrics['role_permission_links'] ?? 0);
?>

<div class="container-fluid px-0">

    <!-- ============================================================
         Header
         ============================================================ -->

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
                    Supervision plateforme
                </span>

            </div>

            <h2 class="fw-bold mb-1">
                Rôles & permissions
            </h2>

            <p class="text-muted mb-0">
                Supervision du modèle d’autorisation
                de la plateforme MedTrack.
            </p>

        </div>

        <div class="d-flex align-items-center gap-2">

            <button
                type="button"
                class="btn btn-primary"
                data-bs-toggle="modal"
                data-bs-target="#createRoleModal"
            >
                <i class="bi bi-plus-lg me-1"></i>
                Nouveau rôle
            </button>

        </div>

    </div>


    <!-- ============================================================
         Metrics
         ============================================================ -->

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
                            <i class="bi bi-shield-lock fs-4"></i>
                        </div>

                        <div>

                            <div class="text-muted small">
                                Rôles
                            </div>

                            <div class="fs-4 fw-bold">
                                <?= $totalRoles ?>
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
                            <i class="bi bi-gear fs-4"></i>
                        </div>

                        <div>

                            <div class="text-muted small">
                                Rôles système
                            </div>

                            <div class="fs-4 fw-bold">
                                <?= $systemRoles ?>
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
                            <i class="bi bi-key fs-4"></i>
                        </div>

                        <div>

                            <div class="text-muted small">
                                Permissions
                            </div>

                            <div class="fs-4 fw-bold">
                                <?= $totalPermissions ?>
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
                            <i class="bi bi-diagram-3 fs-4"></i>
                        </div>

                        <div>

                            <div class="text-muted small">
                                Associations
                            </div>

                            <div class="fs-4 fw-bold">
                                <?= $rolePermissionLinks ?>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- ============================================================
         Role distribution
         ============================================================ -->

    <div class="row g-4 mb-4">

        <div class="col-md-6">

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
                            <i class="bi bi-globe fs-4"></i>
                        </div>

                        <div>

                            <h5 class="fw-bold mb-1">
                                Rôles plateforme
                            </h5>

                            <p class="text-muted small mb-0">
                                Rôles utilisables hors contexte
                                institutionnel.
                            </p>

                        </div>

                    </div>

                    <div class="fs-3 fw-bold">
                        <?= $platformRoles ?>
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-4">

                    <div class="d-flex align-items-center gap-3 mb-4">

                        <div
                            class="rounded-circle
                                   bg-info-subtle
                                   text-info
                                   d-flex align-items-center
                                   justify-content-center"
                            style="width:48px;height:48px;"
                        >
                            <i class="bi bi-buildings fs-4"></i>
                        </div>

                        <div>

                            <h5 class="fw-bold mb-1">
                                Rôles institutionnels
                            </h5>

                            <p class="text-muted small mb-0">
                                Rôles associés à un type
                                d’organisation.
                            </p>

                        </div>

                    </div>

                    <div class="fs-3 fw-bold">
                        <?= $organizationRoles ?>
                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- ============================================================
         Roles
         ============================================================ -->

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body p-0">

            <div class="p-4 border-bottom">

                <h5 class="fw-bold mb-1">
                    Répertoire des rôles
                </h5>

                <p class="text-muted small mb-0">
                    Rôles disponibles dans le système RBAC.
                </p>

            </div>


            <div class="table-responsive">

                <table class="table align-middle mb-0">

                    <thead class="table-light">

                        <tr>
                            <th class="ps-4">
                                Rôle
                            </th>

                            <th>
                                Portée
                            </th>

                            <th>
                                Permissions
                            </th>

                            <th>
                                Utilisateurs plateforme
                            </th>

                            <th>
                                Memberships
                            </th>

                            <th>
                                Type
                            </th>

                            <th class="text-end pe-4">
                                Actions
                            </th>
                        </tr>

                    </thead>


                    <tbody>

                    <?php if ($roles === []): ?>

                        <tr>

                            <td
                                colspan="7"
                                class="text-center py-5"
                            >

                                <div class="text-muted">

                                    <i
                                        class="bi bi-shield-lock
                                               fs-1 d-block mb-3"
                                    ></i>

                                    <strong class="d-block mb-1">
                                        Aucun rôle
                                    </strong>

                                    <span class="small">
                                        Aucun rôle RBAC n’est enregistré.
                                    </span>

                                </div>

                            </td>

                        </tr>

                    <?php else: ?>

                        <?php foreach ($roles as $role): ?>

                            <?php
                            $id =
                                (int) (
                                    $role['id']
                                    ?? 0
                                );

                            $organizationType =
                                trim(
                                    (string) (
                                        $role['organization_type']
                                        ?? ''
                                    )
                                );

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
                                    : 'text-bg-light border';

                            $isSystem =
                                (int) (
                                    $role['is_system']
                                    ?? 0
                                ) === 1;
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
                                            <i class="bi bi-shield"></i>
                                        </div>

                                        <div>

                                            <a
                                                href="/roles/<?= $id ?>"
                                                class="fw-semibold
                                                       text-decoration-none"
                                            >
                                                <?= htmlspecialchars(
                                                    (string) (
                                                        $role['name']
                                                        ?? 'Rôle'
                                                    ),
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>
                                            </a>

                                            <div class="text-muted small">

                                                <code>
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

                                        </div>

                                    </div>

                                </td>


                                <td>

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

                                </td>


                                <td>

                                    <span class="badge text-bg-light">
                                        <?= (int) (
                                            $role['permission_count']
                                            ?? 0
                                        ) ?>
                                    </span>

                                </td>


                                <td>

                                    <?= (int) (
                                        $role['platform_user_count']
                                        ?? 0
                                    ) ?>

                                </td>


                                <td>

                                    <?= (int) (
                                        $role['membership_count']
                                        ?? 0
                                    ) ?>

                                </td>


                                <td>

                                    <?php if ($isSystem): ?>

                                        <span
                                            class="badge rounded-pill
                                                   text-bg-success"
                                        >
                                            Système
                                        </span>

                                    <?php else: ?>

                                        <span
                                            class="badge rounded-pill
                                                   text-bg-secondary"
                                        >
                                            Personnalisé
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <td class="text-end pe-4">

                                    <a
                                        href="/roles/<?= $id ?>"
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


    <!-- ============================================================
         Permissions
         ============================================================ -->

    <div class="card border-0 shadow-sm">

        <div class="card-body p-0">

            <div class="p-4 border-bottom">

                <h5 class="fw-bold mb-1">
                    Catalogue des permissions
                </h5>

                <p class="text-muted small mb-0">
                    Permissions disponibles dans MedTrack.
                </p>

            </div>


            <div class="table-responsive">

                <table class="table align-middle mb-0">

                    <thead class="table-light">

                        <tr>
                            <th class="ps-4">
                                Permission
                            </th>

                            <th>
                                Libellé
                            </th>

                            <th class="pe-4">
                                Utilisée par
                            </th>
                        </tr>

                    </thead>


                    <tbody>

                    <?php if ($permissions === []): ?>

                        <tr>

                            <td
                                colspan="3"
                                class="text-center
                                       py-4 text-muted"
                            >
                                Aucune permission.
                            </td>

                        </tr>

                    <?php else: ?>

                        <?php foreach (
                            $permissions
                            as $permission
                        ): ?>

                            <tr>

                                <td class="ps-4">

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

                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        (string) (
                                            $permission['name']
                                            ?? '—'
                                        ),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                </td>


                                <td class="pe-4">

                                    <span class="badge text-bg-light">

                                        <?= (int) (
                                            $permission['role_count']
                                            ?? 0
                                        ) ?>

                                        rôle(s)

                                    </span>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    <!-- ============================================================
         Create role modal
         ============================================================ -->

    <div
        class="modal fade"
        id="createRoleModal"
        tabindex="-1"
        aria-labelledby="createRoleModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content border-0 shadow">

                <div class="modal-header">

                    <div>
                        <h5
                            class="modal-title fw-bold"
                            id="createRoleModalLabel"
                        >
                            Nouveau rôle
                        </h5>

                        <p class="text-muted small mb-0">
                            Créer un rôle personnalisé dans le système RBAC.
                        </p>
                    </div>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Fermer"
                    ></button>

                </div>

                <form
                    id="createRoleForm"
                    class="js-role-action-form"
                    action="/roles"
                    method="post"
                    data-confirm-title="Créer ce rôle ?"
                    data-confirm-text="Le rôle sera ajouté au système RBAC MedTrack."
                    data-confirm-button="Créer le rôle"
                    novalidate
                >

                    <div class="modal-body p-4">

                        <input
                            type="hidden"
                            name="_token"
                            value="<?= $csrfToken ?>"
                        >

                        <div class="mb-3">

                            <label
                                for="roleCode"
                                class="form-label fw-semibold"
                            >
                                Code du rôle
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="roleCode"
                                name="code"
                                data-field-label="Code du rôle"
                                placeholder="Ex. STAGE_COORDINATOR"
                                autocomplete="off"
                                maxlength="80"
                                pattern="[A-Za-z0-9_]+"
                                required
                            >

                            <div class="form-text">
                                Lettres, chiffres et underscore uniquement.
                                Le code sera normalisé en majuscules.
                            </div>

                        </div>

                        <div class="mb-3">

                            <label
                                for="roleName"
                                class="form-label fw-semibold"
                            >
                                Nom du rôle
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="roleName"
                                name="name"
                                data-field-label="Nom du rôle"
                                placeholder="Ex. Coordinateur des stages"
                                autocomplete="off"
                                maxlength="150"
                                required
                            >

                        </div>

                        <div>

                            <label
                                for="roleOrganizationType"
                                class="form-label fw-semibold"
                            >
                                Portée
                            </label>

                            <select
                                class="form-select"
                                id="roleOrganizationType"
                                name="organization_type"
                            >
                                <option value="">
                                    Plateforme MedTrack
                                </option>

                                <option value="UNIVERSITY">
                                    Université
                                </option>

                                <option value="HOSPITAL">
                                    Hôpital
                                </option>

                                <option value="PROFESSIONAL_ORDER">
                                    Ordre professionnel
                                </option>

                                <option value="MINISTRY">
                                    Ministère
                                </option>
                            </select>

                            <div class="form-text">
                                La portée détermine le contexte dans lequel
                                le rôle pourra être attribué.
                            </div>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <button
                            type="button"
                            class="btn btn-light"
                            data-bs-dismiss="modal"
                        >
                            Annuler
                        </button>

                        <button
                            type="submit"
                            class="btn btn-primary"
                            id="createRoleSubmitButton"
                        >
                            <span id="createRoleSubmitIcon">
                                <i class="bi bi-check-lg me-1"></i>
                            </span>

                            <span id="createRoleSubmitText">
                                Créer le rôle
                            </span>
                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>

</div>