<?php

declare(strict_types=1);

$metrics = $dashboard['metrics'] ?? [];

$students = (int) ($metrics['students'] ?? 0);
$activeEnrollments = (int) ($metrics['activeEnrollments'] ?? 0);
$activeInternships = (int) ($metrics['activeInternships'] ?? 0);
$partnerHospitals = (int) ($metrics['partnerHospitals'] ?? 0);
$successfulPayments = (int) ($metrics['successfulPayments'] ?? 0);

$title = (string) (
    $dashboard['title']
    ?? 'Espace Université'
);

$subtitle = (string) (
    $dashboard['subtitle']
    ?? 'Gestion académique et suivi des stages'
);
?>

<div class="medtrack-dashboard">

    <section class="dashboard-hero">

        <div class="dashboard-hero__content">

            <div class="dashboard-hero__eyebrow">
                <span class="dashboard-hero__pulse"></span>
                Espace institutionnel
            </div>

            <h2 class="dashboard-hero__title">
                <?= htmlspecialchars(
                    $title,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </h2>

            <p class="dashboard-hero__description">
                <?= htmlspecialchars(
                    $subtitle,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>.
                Gérez vos étudiants, inscriptions,
                programmes académiques et stages
                depuis votre espace institutionnel.
            </p>

            <div class="dashboard-hero__actions">

                <a
                    href="/students"
                    class="btn dashboard-btn dashboard-btn--primary"
                >
                    <i class="bi bi-people-fill"></i>
                    <span>Mes étudiants</span>
                </a>

                <a
                    href="/academic-enrollments"
                    class="btn dashboard-btn dashboard-btn--glass"
                >
                    <i class="bi bi-person-vcard-fill"></i>
                    <span>Inscriptions</span>
                </a>

            </div>

        </div>

        <div
            class="dashboard-hero__visual"
            aria-hidden="true"
        >
            <div class="dashboard-orbit dashboard-orbit--outer"></div>
            <div class="dashboard-orbit dashboard-orbit--inner"></div>

            <div class="dashboard-hero__medical-icon">
                <i class="bi bi-mortarboard-fill"></i>
            </div>

            <span class="dashboard-floating-icon dashboard-floating-icon--one">
                <i class="bi bi-people-fill"></i>
            </span>

            <span class="dashboard-floating-icon dashboard-floating-icon--two">
                <i class="bi bi-journal-bookmark-fill"></i>
            </span>

            <span class="dashboard-floating-icon dashboard-floating-icon--three">
                <i class="bi bi-briefcase-fill"></i>
            </span>
        </div>

    </section>


    <section
        id="dashboard-overview"
        class="dashboard-metrics"
    >

        <article class="dashboard-metric dashboard-metric--students">

            <div class="dashboard-metric__header">
                <div class="dashboard-metric__icon">
                    <i class="bi bi-people-fill"></i>
                </div>

                <span class="dashboard-metric__badge">
                    Étudiants
                </span>
            </div>

            <div class="dashboard-metric__body">

                <strong
                    class="dashboard-metric__value"
                    data-counter="<?= $students ?>"
                >
                    <?= $students ?>
                </strong>

                <span class="dashboard-metric__label">
                    étudiants de l’université
                </span>

            </div>

        </article>


        <article class="dashboard-metric dashboard-metric--internships">

            <div class="dashboard-metric__header">
                <div class="dashboard-metric__icon">
                    <i class="bi bi-person-vcard-fill"></i>
                </div>

                <span class="dashboard-metric__badge">
                    Inscriptions
                </span>
            </div>

            <div class="dashboard-metric__body">

                <strong
                    class="dashboard-metric__value"
                    data-counter="<?= $activeEnrollments ?>"
                >
                    <?= $activeEnrollments ?>
                </strong>

                <span class="dashboard-metric__label">
                    inscriptions actives
                </span>

            </div>

        </article>


        <article class="dashboard-metric dashboard-metric--hospitals">

            <div class="dashboard-metric__header">
                <div class="dashboard-metric__icon">
                    <i class="bi bi-briefcase-fill"></i>
                </div>

                <span class="dashboard-metric__badge">
                    Stages
                </span>
            </div>

            <div class="dashboard-metric__body">

                <strong
                    class="dashboard-metric__value"
                    data-counter="<?= $activeInternships ?>"
                >
                    <?= $activeInternships ?>
                </strong>

                <span class="dashboard-metric__label">
                    stages en cours
                </span>

            </div>

        </article>


        <article class="dashboard-metric dashboard-metric--payments">

            <div class="dashboard-metric__header">
                <div class="dashboard-metric__icon">
                    <i class="bi bi-hospital-fill"></i>
                </div>

                <span class="dashboard-metric__badge">
                    Hôpitaux
                </span>
            </div>

            <div class="dashboard-metric__body">

                <strong
                    class="dashboard-metric__value"
                    data-counter="<?= $partnerHospitals ?>"
                >
                    <?= $partnerHospitals ?>
                </strong>

                <span class="dashboard-metric__label">
                    structures partenaires
                </span>

            </div>

        </article>

    </section>


    <section class="dashboard-grid dashboard-grid--operations">

        <article class="dashboard-panel dashboard-panel--large">

            <div class="dashboard-panel__header">
                <div>
                    <span class="dashboard-panel__eyebrow">
                        Gestion académique
                    </span>

                    <h3 class="dashboard-panel__title">
                        Accès rapides
                    </h3>
                </div>
            </div>

            <div class="dashboard-modules__grid">

                <a
                    href="/students"
                    class="dashboard-module dashboard-module--students"
                >
                    <span class="dashboard-module__icon">
                        <i class="bi bi-people-fill"></i>
                    </span>

                    <span class="dashboard-module__content">
                        <strong>Étudiants</strong>
                        <small>Répertoire institutionnel</small>
                    </span>

                    <i class="bi bi-arrow-up-right dashboard-module__arrow"></i>
                </a>


                <a
                    href="/academic-enrollments"
                    class="dashboard-module dashboard-module--internships"
                >
                    <span class="dashboard-module__icon">
                        <i class="bi bi-person-vcard-fill"></i>
                    </span>

                    <span class="dashboard-module__content">
                        <strong>Inscriptions</strong>
                        <small>Parcours académiques</small>
                    </span>

                    <i class="bi bi-arrow-up-right dashboard-module__arrow"></i>
                </a>


                <a
                    href="/faculties"
                    class="dashboard-module dashboard-module--hospitals"
                >
                    <span class="dashboard-module__icon">
                        <i class="bi bi-diagram-3-fill"></i>
                    </span>

                    <span class="dashboard-module__content">
                        <strong>Facultés</strong>
                        <small>Organisation académique</small>
                    </span>

                    <i class="bi bi-arrow-up-right dashboard-module__arrow"></i>
                </a>


                <a
                    href="/academic-programs"
                    class="dashboard-module dashboard-module--attendance"
                >
                    <span class="dashboard-module__icon">
                        <i class="bi bi-journal-bookmark-fill"></i>
                    </span>

                    <span class="dashboard-module__content">
                        <strong>Programmes</strong>
                        <small>Programmes académiques</small>
                    </span>

                    <i class="bi bi-arrow-up-right dashboard-module__arrow"></i>
                </a>


                <a
                    href="/academic-years"
                    class="dashboard-module dashboard-module--assessments"
                >
                    <span class="dashboard-module__icon">
                        <i class="bi bi-calendar3"></i>
                    </span>

                    <span class="dashboard-module__content">
                        <strong>Années académiques</strong>
                        <small>Calendrier académique</small>
                    </span>

                    <i class="bi bi-arrow-up-right dashboard-module__arrow"></i>
                </a>


                <a
                    href="/cohorts"
                    class="dashboard-module dashboard-module--payments"
                >
                    <span class="dashboard-module__icon">
                        <i class="bi bi-collection-fill"></i>
                    </span>

                    <span class="dashboard-module__content">
                        <strong>Cohortes</strong>
                        <small>Promotions et groupes</small>
                    </span>

                    <i class="bi bi-arrow-up-right dashboard-module__arrow"></i>
                </a>

            </div>

        </article>


        <article class="dashboard-panel">

            <div class="dashboard-panel__header">

                <div>
                    <span class="dashboard-panel__eyebrow">
                        Finance
                    </span>

                    <h3 class="dashboard-panel__title">
                        Paiements
                    </h3>
                </div>

                <span class="dashboard-panel__icon">
                    <i class="bi bi-wallet2"></i>
                </span>

            </div>

            <div class="dashboard-activity">

                <div class="dashboard-activity__empty">

                    <span class="dashboard-activity__icon">
                        <i class="bi bi-credit-card-fill"></i>
                    </span>

                    <strong>
                        <?= $successfulPayments ?>
                        transaction<?= $successfulPayments !== 1 ? 's' : '' ?>
                        réussie<?= $successfulPayments !== 1 ? 's' : '' ?>
                    </strong>

                    <p>
                        Les informations financières détaillées
                        seront accessibles selon les permissions
                        du compte connecté.
                    </p>

                </div>

            </div>

        </article>

    </section>

</div>