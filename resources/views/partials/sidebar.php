<?php

declare(strict_types=1);

$currentAccess =
    $currentAccess
    ?? [];

$scope =
    (string) (
        $currentAccess['scope']
        ?? ''
    );

$organizationType =
    (string) (
        $currentAccess['organization_type']
        ?? ''
    );

$organizationName =
    trim(
        (string) (
            $currentAccess['organization_name']
            ?? ''
        )
    );

$permissions =
    is_array(
        $currentAccess['permissions']
        ?? null
    )
        ? $currentAccess['permissions']
        : [];

$permissionCodes = [];

foreach ($permissions as $permission) {
    if (
        is_array($permission)
        && isset($permission['code'])
    ) {
        $permissionCodes[] =
            (string) $permission['code'];
    }
}

$currentPath =
    parse_url(
        (string) (
            $_SERVER['REQUEST_URI']
            ?? '/'
        ),
        PHP_URL_PATH
    )
    ?: '/';

$isActive =
    static function (
        string $path,
        bool $exact = false
    ) use (
        $currentPath
    ): bool {
        if ($exact) {
            return $currentPath === $path;
        }

        return $currentPath === $path
            || str_starts_with(
                $currentPath,
                rtrim($path, '/') . '/'
            );
    };

$isAnyActive =
    static function (
        array $paths
    ) use (
        $isActive
    ): bool {
        foreach ($paths as $path) {
            if (
                is_string($path)
                && $isActive(
                    $path,
                    false
                )
            ) {
                return true;
            }
        }

        return false;
    };

$can =
    static function (
        string $permissionCode
    ) use (
        $scope,
        $permissionCodes
    ): bool {
        if ($scope === 'PLATFORM') {
            return true;
        }

        return in_array(
            $permissionCode,
            $permissionCodes,
            true
        );
    };

$scopeLabel =
    match ($scope) {
        'PLATFORM' =>
            'Administration plateforme',

        'STUDENT' =>
            'Espace étudiant',

        'ORGANIZATION' =>
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
                    'Organisation',
            },

        default =>
            'MedTrack',
    };
?>

<aside
    id="medtrackSidebar"
    class="medtrack-sidebar"
    aria-label="Navigation principale"
>

    <!-- Brand -->



    <!-- Navigation -->

    <nav
        class="medtrack-sidebar__body"
        aria-label="Menu MedTrack"
    >

        <ul
            class="medtrack-sidebar__menu"
            id="medtrackSidebarMenu"
        >

            <!-- Dashboard -->

            <li class="medtrack-sidebar__item">

                <a
                    href="/"
                    class="medtrack-sidebar__link
                           <?= $isActive('/', true)
                               ? 'is-active'
                               : '' ?>"
                >
                    <i class="bi bi-grid-1x2-fill"></i>

                    <span>
                        Tableau de bord
                    </span>
                </a>

            </li>


            <?php if ($scope === 'PLATFORM'): ?>

                <?php
                $platformInstitutionsOpen =
                    $isAnyActive([
                        '/universities',
                        '/hospitals',
                        '/professional-orders',
                        '/ministries',
                    ]);

                $platformAcademicOpen =
                    $isAnyActive([
                        '/students',
                        '/academic-enrollments',
                        '/faculties',
                        '/academic-programs',
                        '/academic-years',
                        '/study-levels',
                        '/cohorts',
                    ]);

                $platformInternshipsOpen =
                    $isAnyActive([
                        '/internships',
                    ]);

                $platformAdminOpen =
                    $isAnyActive([
                        '/users',
                        '/roles',
                        '/audit',
                    ]);
                ?>


                <li class="medtrack-sidebar__section">
                    Plateforme
                </li>


                <!-- Institutions -->

                <li class="medtrack-sidebar__item">

                    <a
                        href="#platformInstitutions"
                        class="medtrack-sidebar__link"
                        data-bs-toggle="collapse"
                        role="button"
                        aria-expanded="<?= $platformInstitutionsOpen
                            ? 'true'
                            : 'false' ?>"
                        aria-controls="platformInstitutions"
                    >
                        <i class="bi bi-buildings"></i>

                        <span>
                            Établissements
                        </span>

                        <i
                            class="bi bi-chevron-right
                                   medtrack-sidebar__chevron"
                        ></i>
                    </a>


                    <div
                        class="collapse <?= $platformInstitutionsOpen
                            ? 'show'
                            : '' ?>"
                        id="platformInstitutions"
                    >
                        <ul class="medtrack-sidebar__submenu">

                            <li>
                                <a
                                    href="/universities"
                                    class="medtrack-sidebar__link
                                           <?= $isActive('/universities')
                                               ? 'is-active'
                                               : '' ?>"
                                >
                                    Universités
                                </a>
                            </li>

                            <li>
                                <a
                                    href="/hospitals"
                                    class="medtrack-sidebar__link
                                           <?= $isActive('/hospitals')
                                               ? 'is-active'
                                               : '' ?>"
                                >
                                    Hôpitaux
                                </a>
                            </li>

                            <li>
                                <a
                                    href="/professional-orders"
                                    class="medtrack-sidebar__link
                                           <?= $isActive('/professional-orders')
                                               ? 'is-active'
                                               : '' ?>"
                                >
                                    Ordres professionnels
                                </a>
                            </li>

                            <li>
                                <a
                                    href="/ministries"
                                    class="medtrack-sidebar__link
                                           <?= $isActive('/ministries')
                                               ? 'is-active'
                                               : '' ?>"
                                >
                                    Ministères
                                </a>
                            </li>

                        </ul>
                    </div>

                </li>


                <!-- Academic -->

                <li class="medtrack-sidebar__item">

                    <a
                        href="#platformAcademic"
                        class="medtrack-sidebar__link"
                        data-bs-toggle="collapse"
                        role="button"
                        aria-expanded="<?= $platformAcademicOpen
                            ? 'true'
                            : 'false' ?>"
                        aria-controls="platformAcademic"
                    >
                        <i class="bi bi-mortarboard"></i>

                        <span>
                            Académique
                        </span>

                        <i
                            class="bi bi-chevron-right
                                   medtrack-sidebar__chevron"
                        ></i>
                    </a>


                    <div
                        class="collapse <?= $platformAcademicOpen
                            ? 'show'
                            : '' ?>"
                        id="platformAcademic"
                    >
                        <ul class="medtrack-sidebar__submenu">

                            <li>
                                <a
                                    href="/students"
                                    class="medtrack-sidebar__link
                                           <?= $isActive('/students')
                                               ? 'is-active'
                                               : '' ?>"
                                >
                                    Étudiants
                                </a>
                            </li>

                            <li>
                                <a
                                    href="/academic-enrollments"
                                    class="medtrack-sidebar__link
                                           <?= $isActive('/academic-enrollments')
                                               ? 'is-active'
                                               : '' ?>"
                                >
                                    Inscriptions
                                </a>
                            </li>

                            <li>
                                <a
                                    href="/faculties"
                                    class="medtrack-sidebar__link
                                           <?= $isActive('/faculties')
                                               ? 'is-active'
                                               : '' ?>"
                                >
                                    Facultés
                                </a>
                            </li>

                            <li>
                                <a
                                    href="/academic-programs"
                                    class="medtrack-sidebar__link
                                           <?= $isActive('/academic-programs')
                                               ? 'is-active'
                                               : '' ?>"
                                >
                                    Programmes
                                </a>
                            </li>

                            <li>
                                <a
                                    href="/academic-years"
                                    class="medtrack-sidebar__link
                                           <?= $isActive('/academic-years')
                                               ? 'is-active'
                                               : '' ?>"
                                >
                                    Années académiques
                                </a>
                            </li>

                            <li>
                                <a
                                    href="/study-levels"
                                    class="medtrack-sidebar__link
                                           <?= $isActive('/study-levels')
                                               ? 'is-active'
                                               : '' ?>"
                                >
                                    Niveaux d’études
                                </a>
                            </li>

                            <li>
                                <a
                                    href="/cohorts"
                                    class="medtrack-sidebar__link
                                           <?= $isActive('/cohorts')
                                               ? 'is-active'
                                               : '' ?>"
                                >
                                    Cohortes
                                </a>
                            </li>

                        </ul>
                    </div>

                </li>


                <!-- Internships -->

                <li class="medtrack-sidebar__item">

                    <a
                        href="#platformInternships"
                        class="medtrack-sidebar__link"
                        data-bs-toggle="collapse"
                        role="button"
                        aria-expanded="<?= $platformInternshipsOpen
                            ? 'true'
                            : 'false' ?>"
                        aria-controls="platformInternships"
                    >
                        <i class="bi bi-briefcase"></i>

                        <span>
                            Stages
                        </span>

                        <i
                            class="bi bi-chevron-right
                                   medtrack-sidebar__chevron"
                        ></i>
                    </a>

                    <div
                        class="collapse <?= $platformInternshipsOpen
                            ? 'show'
                            : '' ?>"
                        id="platformInternships"
                    >
                        <ul class="medtrack-sidebar__submenu">
                            <li>
                                <a
                                    href="/internships"
                                    class="medtrack-sidebar__link
                                           <?= $isActive('/internships')
                                               ? 'is-active'
                                               : '' ?>"
                                >
                                    Gestion des stages
                                </a>
                            </li>
                        </ul>
                    </div>

                </li>


                <li class="medtrack-sidebar__section">
                    Gestion
                </li>


                <!-- Finance -->

                <li class="medtrack-sidebar__item">
                    <a
                        href="/payments"
                        class="medtrack-sidebar__link
                               <?= $isActive('/payments')
                                   ? 'is-active'
                                   : '' ?>"
                    >
                        <i class="bi bi-cash-stack"></i>

                        <span>
                            Paiements
                        </span>
                    </a>
                </li>


                <!-- Administration -->

                <li class="medtrack-sidebar__item">

                    <a
                        href="#platformAdministration"
                        class="medtrack-sidebar__link"
                        data-bs-toggle="collapse"
                        role="button"
                        aria-expanded="<?= $platformAdminOpen
                            ? 'true'
                            : 'false' ?>"
                        aria-controls="platformAdministration"
                    >
                        <i class="bi bi-shield-lock"></i>

                        <span>
                            Administration
                        </span>

                        <i
                            class="bi bi-chevron-right
                                   medtrack-sidebar__chevron"
                        ></i>
                    </a>

                    <div
                        class="collapse <?= $platformAdminOpen
                            ? 'show'
                            : '' ?>"
                        id="platformAdministration"
                    >
                        <ul class="medtrack-sidebar__submenu">

                            <li>
                                <a
                                    href="/users"
                                    class="medtrack-sidebar__link
                                           <?= $isActive('/users')
                                               ? 'is-active'
                                               : '' ?>"
                                >
                                    Utilisateurs
                                </a>
                            </li>

                            <li>
                                <a
                                    href="/roles"
                                    class="medtrack-sidebar__link
                                           <?= $isActive('/roles')
                                               ? 'is-active'
                                               : '' ?>"
                                >
                                    Rôles & permissions
                                </a>
                            </li>

                            <li>
                                <a
                                    href="/audit"
                                    class="medtrack-sidebar__link
                                           <?= $isActive('/audit')
                                               ? 'is-active'
                                               : '' ?>"
                                >
                                    Audit
                                </a>
                            </li>

                        </ul>
                    </div>

                </li>


            <?php elseif (
                $scope === 'ORGANIZATION'
                && $organizationType === 'UNIVERSITY'
            ): ?>

                <li class="medtrack-sidebar__section">
                    Structure académique
                </li>


                <?php if ($can('faculties.view')): ?>
                    <li class="medtrack-sidebar__item">
                        <a
                            href="/faculties"
                            class="medtrack-sidebar__link
                                   <?= $isActive('/faculties')
                                       ? 'is-active'
                                       : '' ?>"
                        >
                            <i class="bi bi-diagram-3"></i>
                            <span>Facultés</span>
                        </a>
                    </li>
                <?php endif; ?>


                <?php if ($can('academic_programs.view')): ?>
                    <li class="medtrack-sidebar__item">
                        <a
                            href="/academic-programs"
                            class="medtrack-sidebar__link
                                   <?= $isActive('/academic-programs')
                                       ? 'is-active'
                                       : '' ?>"
                        >
                            <i class="bi bi-journal-bookmark"></i>
                            <span>Programmes académiques</span>
                        </a>
                    </li>
                <?php endif; ?>


                <?php if ($can('academic_years.view')): ?>
                    <li class="medtrack-sidebar__item">
                        <a
                            href="/academic-years"
                            class="medtrack-sidebar__link
                                   <?= $isActive('/academic-years')
                                       ? 'is-active'
                                       : '' ?>"
                        >
                            <i class="bi bi-calendar3"></i>
                            <span>Années académiques</span>
                            <span
                                class="medtrack-sidebar__badge"
                                title="Référentiel MedTrack en lecture seule"
                            >
                                Réf.
                            </span>
                        </a>
                    </li>
                <?php endif; ?>


                <?php if ($can('study_levels.view')): ?>
                    <li class="medtrack-sidebar__item">
                        <a
                            href="/study-levels"
                            class="medtrack-sidebar__link
                                   <?= $isActive('/study-levels')
                                       ? 'is-active'
                                       : '' ?>"
                        >
                            <i class="bi bi-layers"></i>
                            <span>Niveaux d’études</span>
                        </a>
                    </li>
                <?php endif; ?>


                <?php if ($can('cohorts.view')): ?>
                    <li class="medtrack-sidebar__item">
                        <a
                            href="/cohorts"
                            class="medtrack-sidebar__link
                                   <?= $isActive('/cohorts')
                                       ? 'is-active'
                                       : '' ?>"
                        >
                            <i class="bi bi-collection"></i>
                            <span>Cohortes</span>
                        </a>
                    </li>
                <?php endif; ?>


                <?php if (
                    $can('students.view')
                    || $can('students.import')
                    || $can('academic_enrollments.view')
                ): ?>
                    <li class="medtrack-sidebar__section">
                        Étudiants
                    </li>
                <?php endif; ?>


                <?php if ($can('students.view')): ?>
                    <li class="medtrack-sidebar__item">
                        <a
                            href="/students"
                            class="medtrack-sidebar__link
                                   <?= $isActive('/students')
                                       ? 'is-active'
                                       : '' ?>"
                        >
                            <i class="bi bi-people"></i>
                            <span>Étudiants</span>
                        </a>
                    </li>
                <?php endif; ?>


                <?php if ($can('students.import')): ?>
                    <li class="medtrack-sidebar__item">
                        <a
                            href="#"
                            class="medtrack-sidebar__link
                                   is-disabled"
                            aria-disabled="true"
                            tabindex="-1"
                        >
                            <i class="bi bi-file-earmark-arrow-up"></i>
                            <span>Importer les étudiants</span>
                            <span class="medtrack-sidebar__badge">
                                Bientôt
                            </span>
                        </a>
                    </li>
                <?php endif; ?>


                <?php if ($can('academic_enrollments.view')): ?>
                    <li class="medtrack-sidebar__item">
                        <a
                            href="/academic-enrollments"
                            class="medtrack-sidebar__link
                                   <?= $isActive('/academic-enrollments')
                                       ? 'is-active'
                                       : '' ?>"
                        >
                            <i class="bi bi-person-vcard"></i>
                            <span>Inscriptions académiques</span>
                        </a>
                    </li>
                <?php endif; ?>


                <?php if (
                    $can('internships.view')
                    || $can('internships.create')
                    || $can('internships.assign')
                ): ?>
                    <li class="medtrack-sidebar__section">
                        Stages
                    </li>
                <?php endif; ?>


                <?php if ($can('internships.view')): ?>
                    <li class="medtrack-sidebar__item">
                        <a
                            href="/internships"
                            class="medtrack-sidebar__link
                                   <?= $isActive('/internships')
                                       ? 'is-active'
                                       : '' ?>"
                        >
                            <i class="bi bi-briefcase"></i>
                            <span>Stages</span>
                        </a>
                    </li>

                    <li class="medtrack-sidebar__item">
                        <a
                            href="#"
                            class="medtrack-sidebar__link
                                   is-disabled"
                            aria-disabled="true"
                            tabindex="-1"
                        >
                            <i class="bi bi-hospital"></i>
                            <span>Hôpitaux partenaires</span>
                            <span class="medtrack-sidebar__badge">
                                Bientôt
                            </span>
                        </a>
                    </li>
                <?php endif; ?>


                <?php if ($can('internships.create')): ?>
                    <li class="medtrack-sidebar__item">
                        <a
                            href="#"
                            class="medtrack-sidebar__link
                                   is-disabled"
                            aria-disabled="true"
                            tabindex="-1"
                        >
                            <i class="bi bi-send-check"></i>
                            <span>Demandes de stage</span>
                            <span class="medtrack-sidebar__badge">
                                Bientôt
                            </span>
                        </a>
                    </li>
                <?php endif; ?>


                <?php if ($can('internships.assign')): ?>
                    <li class="medtrack-sidebar__item">
                        <a
                            href="#"
                            class="medtrack-sidebar__link
                                   is-disabled"
                            aria-disabled="true"
                            tabindex="-1"
                        >
                            <i class="bi bi-diagram-2"></i>
                            <span>Affectations</span>
                            <span class="medtrack-sidebar__badge">
                                Bientôt
                            </span>
                        </a>
                    </li>
                <?php endif; ?>


                <?php if (
                    $can('payments.view')
                    || $can('payments.manage')
                ): ?>
                    <li class="medtrack-sidebar__section">
                        Finance
                    </li>
                <?php endif; ?>


                <?php if ($can('payments.view')): ?>
                    <li class="medtrack-sidebar__item">
                        <a
                            href="#"
                            class="medtrack-sidebar__link
                                   is-disabled"
                            aria-disabled="true"
                            tabindex="-1"
                        >
                            <i class="bi bi-cash-stack"></i>
                            <span>Paiements</span>
                            <span class="medtrack-sidebar__badge">
                                Bientôt
                            </span>
                        </a>
                    </li>
                <?php endif; ?>


                <?php if ($can('payments.manage')): ?>
                    <li class="medtrack-sidebar__item">
                        <a
                            href="#"
                            class="medtrack-sidebar__link
                                   is-disabled"
                            aria-disabled="true"
                            tabindex="-1"
                        >
                            <i class="bi bi-wallet2"></i>
                            <span>Gestion financière</span>
                            <span class="medtrack-sidebar__badge">
                                Bientôt
                            </span>
                        </a>
                    </li>
                <?php endif; ?>


                <?php if ($can('analytics.organization')): ?>
                    <li class="medtrack-sidebar__section">
                        Pilotage
                    </li>

                    <li class="medtrack-sidebar__item">
                        <a
                            href="#"
                            class="medtrack-sidebar__link
                                   is-disabled"
                            aria-disabled="true"
                            tabindex="-1"
                        >
                            <i class="bi bi-bar-chart"></i>
                            <span>Statistiques</span>
                            <span class="medtrack-sidebar__badge">
                                Bientôt
                            </span>
                        </a>
                    </li>

                    <li class="medtrack-sidebar__item">
                        <a
                            href="#"
                            class="medtrack-sidebar__link
                                   is-disabled"
                            aria-disabled="true"
                            tabindex="-1"
                        >
                            <i class="bi bi-file-earmark-bar-graph"></i>
                            <span>Rapports</span>
                            <span class="medtrack-sidebar__badge">
                                Bientôt
                            </span>
                        </a>
                    </li>
                <?php endif; ?>


                <?php if (
                    $can('students.view')
                    || $can('audit.view')
                ): ?>
                    <li class="medtrack-sidebar__section">
                        Administration
                    </li>
                <?php endif; ?>


                <?php if ($can('students.view')): ?>
                    <li class="medtrack-sidebar__item">
                        <a
                            href="#"
                            class="medtrack-sidebar__link
                                   is-disabled"
                            aria-disabled="true"
                            tabindex="-1"
                        >
                            <i class="bi bi-people-fill"></i>
                            <span>Équipe universitaire</span>
                            <span class="medtrack-sidebar__badge">
                                Bientôt
                            </span>
                        </a>
                    </li>

                    <li class="medtrack-sidebar__item">
                        <a
                            href="#"
                            class="medtrack-sidebar__link
                                   is-disabled"
                            aria-disabled="true"
                            tabindex="-1"
                        >
                            <i class="bi bi-shield-lock"></i>
                            <span>Rôles & permissions</span>
                            <span class="medtrack-sidebar__badge">
                                Bientôt
                            </span>
                        </a>
                    </li>
                <?php endif; ?>


                <?php if ($can('audit.view')): ?>
                    <li class="medtrack-sidebar__item">
                        <a
                            href="#"
                            class="medtrack-sidebar__link
                                   is-disabled"
                            aria-disabled="true"
                            tabindex="-1"
                        >
                            <i class="bi bi-clock-history"></i>
                            <span>Audit</span>
                            <span class="medtrack-sidebar__badge">
                                Bientôt
                            </span>
                        </a>
                    </li>
                <?php endif; ?>


            <?php elseif (
                $scope === 'ORGANIZATION'
                && $organizationType === 'HOSPITAL'
            ): ?>

                <li class="medtrack-sidebar__section">
                    Gestion hospitalière
                </li>


                <?php if ($can('internships.view')): ?>
                    <li class="medtrack-sidebar__item">
                        <a
                            href="/internships"
                            class="medtrack-sidebar__link
                                   <?= $isActive('/internships')
                                       ? 'is-active'
                                       : '' ?>"
                        >
                            <i class="bi bi-people"></i>
                            <span>Stagiaires</span>
                        </a>
                    </li>
                <?php endif; ?>


                <?php if ($can('internships.manage_programs')): ?>
                    <li class="medtrack-sidebar__item">
                        <a
                            href="#"
                            class="medtrack-sidebar__link is-disabled"
                            aria-disabled="true"
                            tabindex="-1"
                        >
                            <i class="bi bi-briefcase"></i>
                            <span>Programmes de stage</span>
                            <span class="medtrack-sidebar__badge">
                                Bientôt
                            </span>
                        </a>
                    </li>
                <?php endif; ?>


                <?php if ($can('rotations.manage')): ?>
                    <li class="medtrack-sidebar__item">
                        <a
                            href="#"
                            class="medtrack-sidebar__link is-disabled"
                            aria-disabled="true"
                            tabindex="-1"
                        >
                            <i class="bi bi-arrow-repeat"></i>
                            <span>Rotations</span>
                            <span class="medtrack-sidebar__badge">
                                Bientôt
                            </span>
                        </a>
                    </li>
                <?php endif; ?>


                <?php if ($can('logbook.validate')): ?>
                    <li class="medtrack-sidebar__item">
                        <a
                            href="#"
                            class="medtrack-sidebar__link is-disabled"
                            aria-disabled="true"
                            tabindex="-1"
                        >
                            <i class="bi bi-journal-check"></i>
                            <span>Logbooks</span>
                            <span class="medtrack-sidebar__badge">
                                Bientôt
                            </span>
                        </a>
                    </li>
                <?php endif; ?>


                <?php if (
                    $can('evaluations.create')
                    || $can('evaluations.finalize')
                ): ?>
                    <li class="medtrack-sidebar__item">
                        <a
                            href="#"
                            class="medtrack-sidebar__link is-disabled"
                            aria-disabled="true"
                            tabindex="-1"
                        >
                            <i class="bi bi-clipboard2-check"></i>
                            <span>Évaluations</span>
                            <span class="medtrack-sidebar__badge">
                                Bientôt
                            </span>
                        </a>
                    </li>
                <?php endif; ?>


            <?php elseif (
                $scope === 'ORGANIZATION'
                && $organizationType === 'PROFESSIONAL_ORDER'
            ): ?>

                <li class="medtrack-sidebar__section">
                    Ordre professionnel
                </li>


                <?php if (
                    $can(
                        'professional_registration.review'
                    )
                ): ?>
                    <li class="medtrack-sidebar__item">
                        <a
                            href="#"
                            class="medtrack-sidebar__link is-disabled"
                            aria-disabled="true"
                            tabindex="-1"
                        >
                            <i class="bi bi-file-earmark-person"></i>
                            <span>Dossiers</span>
                            <span class="medtrack-sidebar__badge">
                                Bientôt
                            </span>
                        </a>
                    </li>

                    <li class="medtrack-sidebar__item">
                        <a
                            href="#"
                            class="medtrack-sidebar__link is-disabled"
                            aria-disabled="true"
                            tabindex="-1"
                        >
                            <i class="bi bi-check2-circle"></i>
                            <span>Validations</span>
                            <span class="medtrack-sidebar__badge">
                                Bientôt
                            </span>
                        </a>
                    </li>
                <?php endif; ?>


                <?php if ($can('certificates.issue')): ?>
                    <li class="medtrack-sidebar__item">
                        <a
                            href="#"
                            class="medtrack-sidebar__link is-disabled"
                            aria-disabled="true"
                            tabindex="-1"
                        >
                            <i class="bi bi-award"></i>
                            <span>Certifications</span>
                            <span class="medtrack-sidebar__badge">
                                Bientôt
                            </span>
                        </a>
                    </li>
                <?php endif; ?>


            <?php elseif (
                $scope === 'ORGANIZATION'
                && $organizationType === 'MINISTRY'
            ): ?>

                <li class="medtrack-sidebar__section">
                    Supervision nationale
                </li>


                <?php if ($can('analytics.national')): ?>
                    <li class="medtrack-sidebar__item">
                        <a
                            href="#"
                            class="medtrack-sidebar__link is-disabled"
                            aria-disabled="true"
                            tabindex="-1"
                        >
                            <i class="bi bi-bar-chart"></i>
                            <span>Statistiques nationales</span>
                            <span class="medtrack-sidebar__badge">
                                Bientôt
                            </span>
                        </a>
                    </li>
                <?php endif; ?>


                <?php if ($can('audit.view')): ?>
                    <li class="medtrack-sidebar__item">
                        <a
                            href="#"
                            class="medtrack-sidebar__link is-disabled"
                            aria-disabled="true"
                            tabindex="-1"
                        >
                            <i class="bi bi-shield-check"></i>
                            <span>Audit</span>
                            <span class="medtrack-sidebar__badge">
                                Bientôt
                            </span>
                        </a>
                    </li>
                <?php endif; ?>


            <?php elseif ($scope === 'STUDENT'): ?>

                <li class="medtrack-sidebar__section">
                    Mon espace
                </li>


                <?php
                $studentItems = [
                    [
                        'icon' => 'bi-person-vcard',
                        'label' => 'Mon profil',
                    ],
                    [
                        'icon' => 'bi-mortarboard',
                        'label' => 'Mon cursus',
                    ],
                    [
                        'icon' => 'bi-briefcase',
                        'label' => 'Mes stages',
                    ],
                    [
                        'icon' => 'bi-calendar2-check',
                        'label' => 'Mes présences',
                    ],
                    [
                        'icon' => 'bi-journal-check',
                        'label' => 'Mon logbook',
                    ],
                    [
                        'icon' => 'bi-clipboard2-data',
                        'label' => 'Mes évaluations',
                    ],
                    [
                        'icon' => 'bi-award',
                        'label' => 'Mes attestations',
                    ],
                ];
                ?>

                <?php foreach ($studentItems as $item): ?>

                    <li class="medtrack-sidebar__item">

                        <a
                            href="#"
                            class="medtrack-sidebar__link
                                   is-disabled"
                            aria-disabled="true"
                            tabindex="-1"
                        >
                            <i
                                class="bi <?= htmlspecialchars(
                                    $item['icon'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>"
                            ></i>

                            <span>
                                <?= htmlspecialchars(
                                    $item['label'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </span>

                            <span class="medtrack-sidebar__badge">
                                Bientôt
                            </span>
                        </a>

                    </li>

                <?php endforeach; ?>

            <?php endif; ?>

        </ul>

    </nav>


    <!-- Context -->

    <div class="medtrack-sidebar__footer">

        <div class="medtrack-sidebar__footer-card">

            <small>
                Contexte actif
            </small>

            <strong>
                <?= htmlspecialchars(
                    $organizationName !== ''
                        ? $organizationName
                        : $scopeLabel,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </strong>

        </div>

    </div>

</aside>