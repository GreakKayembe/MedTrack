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


$can =
    static function (
        string $permissionCode
    ) use (
        $scope,
        $permissionCodes
    ): bool {
        /*
         * Le contexte PLATFORM conserve
         * la navigation globale.
         *
         * Les actions sensibles restent
         * protégées côté serveur.
         */
        if ($scope === 'PLATFORM') {
            return true;
        }

        return in_array(
            $permissionCode,
            $permissionCodes,
            true
        );
    };
?>

<aside
    id="sidebar"
    class="sidebar"
>

    <div class="sidebar-brand">

        <a
            href="/"
            class="sidebar-brand__link"
            aria-label="Accueil MedTrack"
        >
            <span class="sidebar-brand__logo-wrap">
                <img
                    src="/assets/img/logo.png"
                    alt="MedTrack"
                    class="sidebar-brand__logo"
                >
            </span>

            <span class="sidebar-brand__content">
                <strong>MedTrack</strong>
                <small>Gestion des stages médicaux</small>
            </span>
        </a>

    </div>

    <ul
        class="sidebar-nav"
        id="sidebar-nav"
    >

        <!-- =========================================================
             Dashboard
             ========================================================= -->

        <li class="nav-item">

            <a
                class="nav-link <?= $isActive("/", true) ? '' : 'collapsed' ?>"
                href="/"
            >
                <i class="bi bi-grid"></i>

                <span>
                    Tableau de bord
                </span>
            </a>

        </li>


        <?php if ($scope === 'PLATFORM'): ?>

            <!--
            ==========================================================
            PLATFORM
            ==========================================================
            -->

            <li class="nav-heading">
                Plateforme
            </li>


            <!-- Universities -->

            <li class="nav-item">

                <a
                    class="nav-link <?= $isActive("/universities", false) ? '' : 'collapsed' ?>"
                    href="/universities"
                >
                    <i class="bi bi-bank2"></i>
                    <span>Universités</span>
                </a>

            </li>


            <!-- Hospitals -->

            <li class="nav-item">

                <a
                    class="nav-link <?= $isActive("/hospitals", false) ? '' : 'collapsed' ?>"
                    href="/hospitals"
                >
                    <i class="bi bi-hospital"></i>
                    <span>Hôpitaux</span>
                </a>

            </li>


            <!-- Professional orders -->

            <li class="nav-item">

                <a
                    class="nav-link <?= $isActive("/professional-orders", false) ? '' : 'collapsed' ?>"
                    href="/professional-orders"
                >
                    <i class="bi bi-award"></i>
                    <span>Ordres professionnels</span>
                </a>

            </li>


            <!-- Ministries -->

            <li class="nav-item">

                <a
                    class="nav-link <?= $isActive("/ministries", false) ? '' : 'collapsed' ?>"
                    href="/ministries"
                >
                    <i class="bi bi-building"></i>
                    <span>Ministères</span>
                </a>

            </li>


            <!-- Students -->

            <li class="nav-item">

                <a
                    class="nav-link <?= $isActive("/students", false) ? '' : 'collapsed' ?>"
                    href="/students"
                >
                    <i class="bi bi-people"></i>
                    <span>Étudiants</span>
                </a>

            </li>


            <!-- Academic enrollments -->

            <li class="nav-item">

                <a
                    class="nav-link <?= $isActive("/academic-enrollments", false) ? '' : 'collapsed' ?>"
                    href="/academic-enrollments"
                >
                    <i class="bi bi-person-vcard"></i>
                    <span>Inscriptions</span>
                </a>

            </li>


            <!-- =====================================================
                 Academic
                 ===================================================== -->

            <li class="nav-heading">
                Académique
            </li>


            <li class="nav-item">

                <a
                    class="nav-link <?= $isActive("/faculties", false) ? '' : 'collapsed' ?>"
                    href="/faculties"
                >
                    <i class="bi bi-diagram-3"></i>
                    <span>Facultés</span>
                </a>

            </li>


            <li class="nav-item">

                <a
                    class="nav-link <?= $isActive("/academic-programs", false) ? '' : 'collapsed' ?>"
                    href="/academic-programs"
                >
                    <i class="bi bi-journal-bookmark"></i>
                    <span>Programmes</span>
                </a>

            </li>


            <li class="nav-item">

                <a
                    class="nav-link <?= $isActive("/academic-years", false) ? '' : 'collapsed' ?>"
                    href="/academic-years"
                >
                    <i class="bi bi-calendar3"></i>
                    <span>Années académiques</span>
                </a>

            </li>


            <li class="nav-item">

                <a
                    class="nav-link <?= $isActive("/study-levels", false) ? '' : 'collapsed' ?>"
                    href="/study-levels"
                >
                    <i class="bi bi-layers"></i>
                    <span>Niveaux d’études</span>
                </a>

            </li>


            <li class="nav-item">

                <a
                    class="nav-link <?= $isActive("/cohorts", false) ? '' : 'collapsed' ?>"
                    href="/cohorts"
                >
                    <i class="bi bi-collection"></i>
                    <span>Cohortes</span>
                </a>

            </li>


            <!-- =====================================================
                 Internships
                 ===================================================== -->

            <li class="nav-heading">
                Stages & supervision
            </li>


            <li class="nav-item">

                <a
                    class="nav-link <?= $isActive("/internships", false) ? '' : 'collapsed' ?>"
                    href="/internships"
                >
                    <i class="bi bi-briefcase"></i>
                    <span>Stages</span>
                </a>

            </li>


            <!-- =====================================================
                 Finance
                 ===================================================== -->

            <li class="nav-heading">
                Finance
            </li>


            <!--
             * Route à construire durant le workflow Payments.
             * Nous gardons volontairement le lien désactivé
             * tant que /payments n'est pas implémenté.
             -->
                <li class="nav-item">

                    <a
                        class="nav-link <?= $isActive("/payments", false) ? '' : 'collapsed' ?>"
                        href="/payments"
                    >
                        <i class="bi bi-cash-stack"></i>
                        <span>Paiements</span>
                    </a>

                </li>


            <!-- =====================================================
                 Administration
                 ===================================================== -->

            <li class="nav-heading">
                Administration
            </li>


            <!-- Future /users -->

            <li class="nav-item">

                <a
                    class="nav-link <?= $isActive("/users", false) ? '' : 'collapsed' ?>"
                    href="/users"
                >
                    <i class="bi bi-people-fill"></i>
                    <span>Utilisateurs</span>
                </a>

            </li>



            <!-- Future /roles -->

            <li class="nav-item">

                <a
                    class="nav-link <?= $isActive("/roles", false) ? '' : 'collapsed' ?>"
                    href="/roles"
                >
                    <i class="bi bi-shield-lock"></i>
                    <span>Rôles & permissions</span>
                </a>

            </li>

           


            <!-- Future /audit -->

            <li class="nav-item">

                <a
                    class="nav-link <?= $isActive("/audit", false) ? '' : 'collapsed' ?>"
                    href="/audit"
                >
                    <i class="bi bi-clock-history"></i>
                    <span>Audit</span>
                </a>

            </li>


        <?php elseif (
            $scope === 'ORGANIZATION'
            && $organizationType === 'UNIVERSITY'
        ): ?>

            <!-- ==========================================================
                 UNIVERSITY
                 ========================================================== -->

            <!-- =====================================================
                 Structure académique
                 ===================================================== -->

            <li class="nav-heading">
                Structure académique
            </li>

            <?php if ($can('faculties.view')): ?>
                <li class="nav-item">
                    <a
                        class="nav-link <?= $isActive("/faculties", false) ? '' : 'collapsed' ?>"
                        href="/faculties"
                    >
                        <i class="bi bi-diagram-3"></i>
                        <span>Facultés</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if ($can('academic_programs.view')): ?>
                <li class="nav-item">
                    <a
                        class="nav-link <?= $isActive("/academic-programs", false) ? '' : 'collapsed' ?>"
                        href="/academic-programs"
                    >
                        <i class="bi bi-journal-bookmark"></i>
                        <span>Programmes académiques</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if ($can('academic_years.view')): ?>
                <li class="nav-item">
                    <a
                        class="nav-link <?= $isActive("/academic-years", false) ? '' : 'collapsed' ?>"
                        href="/academic-years"
                    >
                        <i class="bi bi-calendar3"></i>
                        <span>Années académiques</span>

                        <span
                            class="badge rounded-pill
                                   bg-light text-secondary
                                   border ms-auto"
                            title="Référentiel MedTrack en lecture seule"
                        >
                            <i class="bi bi-lock-fill"></i>
                        </span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if ($can('study_levels.view')): ?>
                <li class="nav-item">
                    <a
                        class="nav-link <?= $isActive("/study-levels", false) ? '' : 'collapsed' ?>"
                        href="/study-levels"
                    >
                        <i class="bi bi-layers"></i>
                        <span>Niveaux d’études</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if ($can('cohorts.view')): ?>
                <li class="nav-item">
                    <a
                        class="nav-link <?= $isActive("/cohorts", false) ? '' : 'collapsed' ?>"
                        href="/cohorts"
                    >
                        <i class="bi bi-collection"></i>
                        <span>Cohortes</span>
                    </a>
                </li>
            <?php endif; ?>


            <!-- =====================================================
                 Étudiants
                 ===================================================== -->

            <?php if (
                $can('students.view')
                || $can('students.import')
                || $can('academic_enrollments.view')
            ): ?>

                <li class="nav-heading">
                    Étudiants
                </li>

            <?php endif; ?>

            <?php if ($can('students.view')): ?>
                <li class="nav-item">
                    <a
                        class="nav-link <?= $isActive("/students", false) ? '' : 'collapsed' ?>"
                        href="/students"
                    >
                        <i class="bi bi-people"></i>
                        <span>Étudiants</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if ($can('students.import')): ?>
                <li class="nav-item">
                    <a
                        class="nav-link collapsed disabled"
                        href="#"
                        aria-disabled="true"
                        tabindex="-1"
                        title="Module à implémenter"
                    >
                        <i class="bi bi-file-earmark-arrow-up"></i>
                        <span>Importer les étudiants</span>
                        <span class="badge bg-light text-secondary ms-auto">
                            Bientôt
                        </span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if ($can('academic_enrollments.view')): ?>
                <li class="nav-item">
                    <a
                        class="nav-link <?= $isActive("/academic-enrollments", false) ? '' : 'collapsed' ?>"
                        href="/academic-enrollments"
                    >
                        <i class="bi bi-person-vcard"></i>
                        <span>Inscriptions académiques</span>
                    </a>
                </li>
            <?php endif; ?>


            <!-- =====================================================
                 Stages
                 ===================================================== -->

            <?php if (
                $can('internships.view')
                || $can('internships.create')
                || $can('internships.assign')
            ): ?>

                <li class="nav-heading">
                    Stages
                </li>

            <?php endif; ?>

            <?php if ($can('internships.view')): ?>
                <li class="nav-item">
                    <a
                        class="nav-link <?= $isActive("/internships", false) ? '' : 'collapsed' ?>"
                        href="/internships"
                    >
                        <i class="bi bi-briefcase"></i>
                        <span>Stages</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        class="nav-link collapsed disabled"
                        href="#"
                        aria-disabled="true"
                        tabindex="-1"
                        title="Module à implémenter"
                    >
                        <i class="bi bi-hospital"></i>
                        <span>Hôpitaux partenaires</span>
                        <span class="badge bg-light text-secondary ms-auto">
                            Bientôt
                        </span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if ($can('internships.create')): ?>
                <li class="nav-item">
                    <a
                        class="nav-link collapsed disabled"
                        href="#"
                        aria-disabled="true"
                        tabindex="-1"
                        title="Workflow à implémenter"
                    >
                        <i class="bi bi-send-check"></i>
                        <span>Demandes de stage</span>
                        <span class="badge bg-light text-secondary ms-auto">
                            Bientôt
                        </span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if ($can('internships.assign')): ?>
                <li class="nav-item">
                    <a
                        class="nav-link collapsed disabled"
                        href="#"
                        aria-disabled="true"
                        tabindex="-1"
                        title="Workflow à implémenter"
                    >
                        <i class="bi bi-diagram-2"></i>
                        <span>Affectations</span>
                        <span class="badge bg-light text-secondary ms-auto">
                            Bientôt
                        </span>
                    </a>
                </li>
            <?php endif; ?>


            <!-- =====================================================
                 Finance
                 ===================================================== -->

            <?php if (
                $can('payments.view')
                || $can('payments.manage')
            ): ?>

                <li class="nav-heading">
                    Finance
                </li>

            <?php endif; ?>

            <?php if ($can('payments.view')): ?>
                <li class="nav-item">
                    <a
                        class="nav-link collapsed disabled"
                        href="#"
                        aria-disabled="true"
                        tabindex="-1"
                        title="Module à implémenter"
                    >
                        <i class="bi bi-cash-stack"></i>
                        <span>Paiements</span>
                        <span class="badge bg-light text-secondary ms-auto">
                            Bientôt
                        </span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if ($can('payments.manage')): ?>
                <li class="nav-item">
                    <a
                        class="nav-link collapsed disabled"
                        href="#"
                        aria-disabled="true"
                        tabindex="-1"
                        title="Module à implémenter"
                    >
                        <i class="bi bi-wallet2"></i>
                        <span>Gestion financière</span>
                        <span class="badge bg-light text-secondary ms-auto">
                            Bientôt
                        </span>
                    </a>
                </li>
            <?php endif; ?>


            <!-- =====================================================
                 Pilotage
                 ===================================================== -->

            <?php if ($can('analytics.organization')): ?>

                <li class="nav-heading">
                    Pilotage
                </li>

                <li class="nav-item">
                    <a
                        class="nav-link collapsed disabled"
                        href="#"
                        aria-disabled="true"
                        tabindex="-1"
                        title="Module à implémenter"
                    >
                        <i class="bi bi-bar-chart"></i>
                        <span>Statistiques</span>
                        <span class="badge bg-light text-secondary ms-auto">
                            Bientôt
                        </span>
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        class="nav-link collapsed disabled"
                        href="#"
                        aria-disabled="true"
                        tabindex="-1"
                        title="Module à implémenter"
                    >
                        <i class="bi bi-file-earmark-bar-graph"></i>
                        <span>Rapports</span>
                        <span class="badge bg-light text-secondary ms-auto">
                            Bientôt
                        </span>
                    </a>
                </li>

            <?php endif; ?>


            <!-- =====================================================
                 Administration université
                 ===================================================== -->

            <?php if (
                $can('students.view')
                || $can('audit.view')
            ): ?>

                <li class="nav-heading">
                    Administration
                </li>

            <?php endif; ?>

            <?php if ($can('students.view')): ?>

                <li class="nav-item">
                    <a
                        class="nav-link collapsed disabled"
                        href="#"
                        aria-disabled="true"
                        tabindex="-1"
                        title="Gestion des membres de l'université à implémenter"
                    >
                        <i class="bi bi-people-fill"></i>
                        <span>Équipe universitaire</span>
                        <span class="badge bg-light text-secondary ms-auto">
                            Bientôt
                        </span>
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        class="nav-link collapsed disabled"
                        href="#"
                        aria-disabled="true"
                        tabindex="-1"
                        title="Gestion RBAC institutionnelle à implémenter"
                    >
                        <i class="bi bi-shield-lock"></i>
                        <span>Rôles & permissions</span>
                        <span class="badge bg-light text-secondary ms-auto">
                            Bientôt
                        </span>
                    </a>
                </li>

            <?php endif; ?>

            <?php if ($can('audit.view')): ?>

                <li class="nav-item">
                    <a
                        class="nav-link collapsed disabled"
                        href="#"
                        aria-disabled="true"
                        tabindex="-1"
                        title="Audit institutionnel à implémenter"
                    >
                        <i class="bi bi-clock-history"></i>
                        <span>Audit</span>
                        <span class="badge bg-light text-secondary ms-auto">
                            Bientôt
                        </span>
                    </a>
                </li>

            <?php endif; ?>


        <?php elseif (
            $scope === 'ORGANIZATION'
            && $organizationType === 'HOSPITAL'
        ): ?>

            <!--
            ==========================================================
            HOSPITAL
            ==========================================================
            -->

            <li class="nav-heading">
                Gestion hospitalière
            </li>


            <?php if ($can('internships.view')): ?>

                <li class="nav-item">

                    <a
                        class="nav-link <?= $isActive("/internships", false) ? '' : 'collapsed' ?>"
                        href="/internships"
                    >
                        <i class="bi bi-people"></i>
                        <span>Stagiaires</span>
                    </a>

                </li>

            <?php endif; ?>


            <?php if ($can('internships.manage_programs')): ?>

                <li class="nav-item">

                    <a
                        class="nav-link collapsed disabled"
                        href="#"
                        aria-disabled="true"
                    >
                        <i class="bi bi-briefcase"></i>
                        <span>Programmes de stage</span>
                    </a>

                </li>

            <?php endif; ?>


            <?php if ($can('rotations.manage')): ?>

                <li class="nav-item">

                    <a
                        class="nav-link collapsed disabled"
                        href="#"
                        aria-disabled="true"
                    >
                        <i class="bi bi-arrow-repeat"></i>
                        <span>Rotations</span>
                    </a>

                </li>

            <?php endif; ?>


            <?php if ($can('logbook.validate')): ?>

                <li class="nav-item">

                    <a
                        class="nav-link collapsed disabled"
                        href="#"
                        aria-disabled="true"
                    >
                        <i class="bi bi-journal-check"></i>
                        <span>Logbooks</span>
                    </a>

                </li>

            <?php endif; ?>


            <?php if (
                $can('evaluations.create')
                || $can('evaluations.finalize')
            ): ?>

                <li class="nav-item">

                    <a
                        class="nav-link collapsed disabled"
                        href="#"
                        aria-disabled="true"
                    >
                        <i class="bi bi-clipboard2-check"></i>
                        <span>Évaluations</span>
                    </a>

                </li>

            <?php endif; ?>


        <?php elseif (
            $scope === 'ORGANIZATION'
            && $organizationType === 'PROFESSIONAL_ORDER'
        ): ?>

            <!--
            ==========================================================
            PROFESSIONAL ORDER
            ==========================================================
            -->

            <li class="nav-heading">
                Ordre professionnel
            </li>


            <?php if (
                $can(
                    'professional_registration.review'
                )
            ): ?>

                <li class="nav-item">

                    <a
                        class="nav-link collapsed disabled"
                        href="#"
                        aria-disabled="true"
                    >
                        <i class="bi bi-file-earmark-person"></i>
                        <span>Dossiers</span>
                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-link collapsed disabled"
                        href="#"
                        aria-disabled="true"
                    >
                        <i class="bi bi-check2-circle"></i>
                        <span>Validations</span>
                    </a>

                </li>

            <?php endif; ?>


            <?php if ($can('certificates.issue')): ?>

                <li class="nav-item">

                    <a
                        class="nav-link collapsed disabled"
                        href="#"
                        aria-disabled="true"
                    >
                        <i class="bi bi-award"></i>
                        <span>Certifications</span>
                    </a>

                </li>

            <?php endif; ?>


        <?php elseif (
            $scope === 'ORGANIZATION'
            && $organizationType === 'MINISTRY'
        ): ?>

            <!--
            ==========================================================
            MINISTRY
            ==========================================================
            -->

            <li class="nav-heading">
                Supervision nationale
            </li>


            <?php if ($can('analytics.national')): ?>

                <li class="nav-item">

                    <a
                        class="nav-link collapsed disabled"
                        href="#"
                        aria-disabled="true"
                    >
                        <i class="bi bi-bar-chart"></i>
                        <span>Statistiques nationales</span>
                    </a>

                </li>

            <?php endif; ?>


            <?php if ($can('audit.view')): ?>

                <li class="nav-item">

                    <a
                        class="nav-link collapsed disabled"
                        href="#"
                        aria-disabled="true"
                    >
                        <i class="bi bi-shield-check"></i>
                        <span>Audit</span>
                    </a>

                </li>

            <?php endif; ?>


        <?php elseif ($scope === 'STUDENT'): ?>

            <!--
            ==========================================================
            STUDENT
            ==========================================================
            -->

            <li class="nav-heading">
                Mon espace
            </li>


            <li class="nav-item">

                <a
                    class="nav-link collapsed disabled"
                    href="#"
                    aria-disabled="true"
                >
                    <i class="bi bi-person-vcard"></i>
                    <span>Mon profil</span>
                </a>

            </li>


            <li class="nav-item">

                <a
                    class="nav-link collapsed disabled"
                    href="#"
                    aria-disabled="true"
                >
                    <i class="bi bi-mortarboard"></i>
                    <span>Mon cursus</span>
                </a>

            </li>


            <li class="nav-item">

                <a
                    class="nav-link collapsed disabled"
                    href="#"
                    aria-disabled="true"
                >
                    <i class="bi bi-briefcase"></i>
                    <span>Mes stages</span>
                </a>

            </li>


            <li class="nav-item">

                <a
                    class="nav-link collapsed disabled"
                    href="#"
                    aria-disabled="true"
                >
                    <i class="bi bi-calendar2-check"></i>
                    <span>Mes présences</span>
                </a>

            </li>


            <li class="nav-item">

                <a
                    class="nav-link collapsed disabled"
                    href="#"
                    aria-disabled="true"
                >
                    <i class="bi bi-journal-check"></i>
                    <span>Mon logbook</span>
                </a>

            </li>


            <li class="nav-item">

                <a
                    class="nav-link collapsed disabled"
                    href="#"
                    aria-disabled="true"
                >
                    <i class="bi bi-clipboard2-data"></i>
                    <span>Mes évaluations</span>
                </a>

            </li>


            <li class="nav-item">

                <a
                    class="nav-link collapsed disabled"
                    href="#"
                    aria-disabled="true"
                >
                    <i class="bi bi-award"></i>
                    <span>Mes attestations</span>
                </a>

            </li>

        <?php endif; ?>

    </ul>

</aside>