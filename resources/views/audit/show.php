<?php

declare(strict_types=1);

/**
 * @var array<string, mixed> $audit
 * @var array<string, mixed> $oldValues
 * @var array<string, mixed> $newValues
 * @var array<string, mixed> $metadata
 */

$audit =
    is_array($audit ?? null)
        ? $audit
        : [];

$oldValues =
    is_array($oldValues ?? null)
        ? $oldValues
        : [];

$newValues =
    is_array($newValues ?? null)
        ? $newValues
        : [];

$metadata =
    is_array($metadata ?? null)
        ? $metadata
        : [];

$id =
    (int) (
        $audit['id']
        ?? 0
    );

$uuid =
    (string) (
        $audit['uuid']
        ?? ''
    );

$action =
    (string) (
        $audit['action']
        ?? ''
    );

$entityType =
    (string) (
        $audit['entity_type']
        ?? ''
    );

$entityId =
    (string) (
        $audit['entity_id']
        ?? ''
    );

$createdAt =
    (string) (
        $audit['created_at']
        ?? '—'
    );

$actorEmail =
    trim(
        (string) (
            $audit['actor_email']
            ?? ''
        )
    );

$actorPhone =
    trim(
        (string) (
            $audit['actor_phone']
            ?? ''
        )
    );

$actorUserId =
    (int) (
        $audit['actor_user_id']
        ?? 0
    );

$actor =
    $actorEmail !== ''
        ? $actorEmail
        : (
            $actorPhone !== ''
                ? $actorPhone
                : (
                    $actorUserId > 0
                        ? 'Utilisateur #' . $actorUserId
                        : 'Système'
                )
        );

$organizationName =
    trim(
        (string) (
            $audit['organization_name']
            ?? ''
        )
    );

$organizationCode =
    trim(
        (string) (
            $audit['organization_code']
            ?? ''
        )
    );

$organizationType =
    trim(
        (string) (
            $audit['organization_type']
            ?? ''
        )
    );

$ipAddress =
    trim(
        (string) (
            $audit['ip_address']
            ?? ''
        )
    );

$userAgent =
    trim(
        (string) (
            $audit['user_agent']
            ?? ''
        )
    );

$membershipId =
    (int) (
        $audit['actor_membership_id']
        ?? 0
    );

$membershipStatus =
    (string) (
        $audit['membership_status']
        ?? ''
    );

$organizationTypeLabel =
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
            $organizationType !== ''
                ? $organizationType
                : 'Plateforme',
    };

$renderValue =
    static function (
        mixed $value
    ): string {
        if ($value === null) {
            return 'null';
        }

        if (is_bool($value)) {
            return $value
                ? 'true'
                : 'false';
        }

        if (
            is_array($value)
            || is_object($value)
        ) {
            return json_encode(
                $value,
                JSON_PRETTY_PRINT
                | JSON_UNESCAPED_UNICODE
                | JSON_UNESCAPED_SLASHES
            ) ?: '';
        }

        return (string) $value;
    };
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
                    Audit
                </span>

                <span class="text-muted small">
                    Détail de l’événement
                </span>

            </div>

            <h2 class="fw-bold mb-1">
                <?= htmlspecialchars(
                    $action !== ''
                        ? $action
                        : 'Événement d’audit',
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </h2>

            <p class="text-muted mb-0">
                <?= htmlspecialchars(
                    $entityType,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>

                <?php if ($entityId !== ''): ?>
                    ·
                    <code>
                        <?= htmlspecialchars(
                            $entityId,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </code>
                <?php endif; ?>
            </p>

        </div>


        <a
            href="/audit"
            class="btn btn-outline-secondary
                   d-inline-flex align-items-center gap-2"
        >
            <i class="bi bi-arrow-left"></i>
            Retour à l’audit
        </a>

    </div>


    <div class="row g-4">

        <!-- ========================================================
             Main
             ======================================================== -->

        <div class="col-xl-8">

            <!-- Event summary -->

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
                            <i class="bi bi-journal-text fs-4"></i>
                        </div>

                        <div>

                            <h5 class="fw-bold mb-1">
                                Événement
                            </h5>

                            <p class="text-muted small mb-0">
                                Informations principales de traçabilité.
                            </p>

                        </div>

                    </div>


                    <div class="row g-4">

                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Action
                            </div>

                            <span class="badge text-bg-primary">

                                <?= htmlspecialchars(
                                    $action !== ''
                                        ? $action
                                        : '—',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>

                            </span>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Date
                            </div>

                            <div class="fw-semibold">

                                <?= htmlspecialchars(
                                    $createdAt,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Type d’entité
                            </div>

                            <div class="fw-semibold">

                                <?= htmlspecialchars(
                                    $entityType !== ''
                                        ? $entityType
                                        : '—',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Identifiant entité
                            </div>

                            <code>

                                <?= htmlspecialchars(
                                    $entityId !== ''
                                        ? $entityId
                                        : '—',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>

                            </code>

                        </div>


                        <div class="col-12">

                            <div class="text-muted small mb-1">
                                UUID
                            </div>

                            <code class="small">

                                <?= htmlspecialchars(
                                    $uuid !== ''
                                        ? $uuid
                                        : '—',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>

                            </code>

                        </div>

                    </div>

                </div>

            </div>


            <!-- ====================================================
                 Values comparison
                 ==================================================== -->

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-body p-4">

                    <div class="mb-4">

                        <h5 class="fw-bold mb-1">
                            Comparaison des valeurs
                        </h5>

                        <p class="text-muted small mb-0">
                            État avant et après l’opération.
                        </p>

                    </div>


                    <?php
                    $keys =
                        array_values(
                            array_unique(
                                array_merge(
                                    array_keys(
                                        $oldValues
                                    ),
                                    array_keys(
                                        $newValues
                                    )
                                )
                            )
                        );
                    ?>


                    <?php if ($keys === []): ?>

                        <div
                            class="border rounded-3
                                   text-center text-muted py-4"
                        >
                            Aucune donnée avant/après
                            enregistrée pour cet événement.
                        </div>

                    <?php else: ?>

                        <div class="table-responsive">

                            <table class="table align-middle mb-0">

                                <thead class="table-light">

                                    <tr>

                                        <th>
                                            Champ
                                        </th>

                                        <th>
                                            Avant
                                        </th>

                                        <th>
                                            Après
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                <?php foreach ($keys as $key): ?>

                                    <?php
                                    $hasOld =
                                        array_key_exists(
                                            $key,
                                            $oldValues
                                        );

                                    $hasNew =
                                        array_key_exists(
                                            $key,
                                            $newValues
                                        );

                                    $oldValue =
                                        $hasOld
                                            ? $oldValues[$key]
                                            : null;

                                    $newValue =
                                        $hasNew
                                            ? $newValues[$key]
                                            : null;

                                    $changed =
                                        $renderValue(
                                            $oldValue
                                        ) !== $renderValue(
                                            $newValue
                                        );
                                    ?>

                                    <tr>

                                        <td>

                                            <code class="fw-semibold">

                                                <?= htmlspecialchars(
                                                    (string) $key,
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>

                                            </code>

                                            <?php if ($changed): ?>

                                                <span
                                                    class="badge
                                                           text-bg-warning
                                                           ms-2"
                                                >
                                                    Modifié
                                                </span>

                                            <?php endif; ?>

                                        </td>


                                        <td>

                                            <?php if (!$hasOld): ?>

                                                <span class="text-muted">
                                                    —
                                                </span>

                                            <?php else: ?>

                                                <pre
                                                    class="bg-light border
                                                           rounded-3 p-3
                                                           small mb-0"
                                                ><?= htmlspecialchars(
                                                    $renderValue(
                                                        $oldValue
                                                    ),
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?></pre>

                                            <?php endif; ?>

                                        </td>


                                        <td>

                                            <?php if (!$hasNew): ?>

                                                <span class="text-muted">
                                                    —
                                                </span>

                                            <?php else: ?>

                                                <pre
                                                    class="bg-light border
                                                           rounded-3 p-3
                                                           small mb-0"
                                                ><?= htmlspecialchars(
                                                    $renderValue(
                                                        $newValue
                                                    ),
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?></pre>

                                            <?php endif; ?>

                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                                </tbody>

                            </table>

                        </div>

                    <?php endif; ?>

                </div>

            </div>


            <!-- ====================================================
                 Metadata
                 ==================================================== -->

            <div class="card border-0 shadow-sm">

                <div class="card-body p-4">

                    <div class="mb-4">

                        <h5 class="fw-bold mb-1">
                            Métadonnées
                        </h5>

                        <p class="text-muted small mb-0">
                            Informations complémentaires
                            enregistrées avec l’événement.
                        </p>

                    </div>


                    <?php if ($metadata === []): ?>

                        <div
                            class="border rounded-3
                                   text-center text-muted py-4"
                        >
                            Aucune métadonnée disponible.
                        </div>

                    <?php else: ?>

                        <div class="row g-3">

                            <?php foreach (
                                $metadata
                                as $key => $value
                            ): ?>

                                <div class="col-md-6">

                                    <div
                                        class="border rounded-3
                                               p-3 h-100"
                                    >

                                        <div
                                            class="text-muted
                                                   small mb-2"
                                        >

                                            <?= htmlspecialchars(
                                                (string) $key,
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>

                                        </div>


                                        <pre
                                            class="small mb-0
                                                   text-wrap"
                                        ><?= htmlspecialchars(
                                            $renderValue(
                                                $value
                                            ),
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?></pre>

                                    </div>

                                </div>

                            <?php endforeach; ?>

                        </div>

                    <?php endif; ?>

                </div>

            </div>

        </div>


        <!-- ========================================================
             Right panel
             ======================================================== -->

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
                            <i class="bi bi-shield-check fs-2"></i>
                        </div>

                        <h5 class="fw-bold mb-1">
                            Audit #<?= $id ?>
                        </h5>

                        <span class="badge text-bg-primary">

                            <?= htmlspecialchars(
                                $action !== ''
                                    ? $action
                                    : 'Événement',
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>

                        </span>

                    </div>


                    <hr>


                    <!-- Actor -->

                    <div class="mb-4">

                        <div class="text-muted small mb-1">
                            Acteur
                        </div>

                        <div class="fw-semibold">

                            <?= htmlspecialchars(
                                $actor,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>

                        </div>

                        <?php if ($actorUserId > 0): ?>

                            <a
                                href="/users/<?= $actorUserId ?>"
                                class="small text-decoration-none"
                            >
                                Voir l’utilisateur
                            </a>

                        <?php endif; ?>

                    </div>


                    <!-- Membership -->

                    <?php if ($membershipId > 0): ?>

                        <div class="mb-4">

                            <div class="text-muted small mb-1">
                                Membership acteur
                            </div>

                            <div class="fw-semibold">
                                #<?= $membershipId ?>
                            </div>

                            <?php if ($membershipStatus !== ''): ?>

                                <div class="text-muted small">
                                    <?= htmlspecialchars(
                                        $membershipStatus,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </div>

                            <?php endif; ?>

                        </div>

                    <?php endif; ?>


                    <!-- Organization -->

                    <div class="mb-4">

                        <div class="text-muted small mb-1">
                            Organisation
                        </div>

                        <?php if ($organizationName !== ''): ?>

                            <div class="fw-semibold">

                                <?= htmlspecialchars(
                                    $organizationName,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>

                            </div>

                            <div class="text-muted small">

                                <?= htmlspecialchars(
                                    $organizationTypeLabel,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>

                                <?php if (
                                    $organizationCode !== ''
                                ): ?>

                                    ·
                                    <?= htmlspecialchars(
                                        $organizationCode,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                <?php endif; ?>

                            </div>

                        <?php else: ?>

                            <span class="text-muted">
                                Plateforme MedTrack
                            </span>

                        <?php endif; ?>

                    </div>


                    <!-- Network -->

                    <div class="mb-4">

                        <div class="text-muted small mb-1">
                            Adresse IP
                        </div>

                        <code>

                            <?= htmlspecialchars(
                                $ipAddress !== ''
                                    ? $ipAddress
                                    : 'Non enregistrée',
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>

                        </code>

                    </div>


                    <div class="mb-4">

                        <div class="text-muted small mb-1">
                            User-Agent
                        </div>

                        <div
                            class="small text-break"
                            title="<?= htmlspecialchars(
                                $userAgent,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>"
                        >

                            <?= htmlspecialchars(
                                $userAgent !== ''
                                    ? $userAgent
                                    : 'Non enregistré',
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>

                        </div>

                    </div>


                    <hr>


                    <div class="alert alert-light border small mb-4">

                        <div class="d-flex gap-2">

                            <i
                                class="bi bi-lock-fill
                                       text-success"
                            ></i>

                            <span>
                                Le journal d’audit est
                                strictement en lecture seule.
                                Cet événement ne peut pas être
                                modifié depuis l’interface.
                            </span>

                        </div>

                    </div>


                    <div class="d-grid">

                        <a
                            href="/audit"
                            class="btn btn-light"
                        >
                            <i class="bi bi-arrow-left me-1"></i>
                            Retour au journal
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>