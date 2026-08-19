<?php

declare(strict_types=1);

$metrics =
    $dashboard['metrics']
    ?? [];

$students =
    (int) (
        $metrics['students']
        ?? 0
    );

$universities =
    (int) (
        $metrics['universities']
        ?? 0
    );

$hospitals =
    (int) (
        $metrics['hospitals']
        ?? 0
    );

$professionalOrders =
    (int) (
        $metrics['professionalOrders']
        ?? 0
    );

$ministries =
    (int) (
        $metrics['ministries']
        ?? 0
    );

$activeInternships =
    (int) (
        $metrics['activeInternships']
        ?? 0
    );

$successfulPayments =
    (int) (
        $metrics['successfulPayments']
        ?? 0
    );

$title =
    (string) (
        $dashboard['title']
        ?? 'Administration MedTrack'
    );

$subtitle =
    (string) (
        $dashboard['subtitle']
        ?? 'Vue globale de la plateforme'
    );
?>

<div class="medtrack-dashboard">

    <!--
    |--------------------------------------------------------------------------
    | Welcome / Hero
    |--------------------------------------------------------------------------
    -->

    <section class="dashboard-hero">

        <div class="dashboard-hero__content">

            <div class="dashboard-hero__eyebrow">
                <span class="dashboard-hero__pulse"></span>
                Super administration MedTrack
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
                Supervisez les institutions, les étudiants,
                les stages et les opérations principales
                depuis l’espace central MedTrack.
            </p>

            <div class="dashboard-hero__actions">

                <a
                    href="#dashboard-overview"
                    class="btn dashboard-btn dashboard-btn--primary"
                >
                    <i class="bi bi-grid-1x2-fill"></i>

                    <span>
                        Voir l’activité
                    </span>
                </a>

                <button
                    type="button"
                    class="btn dashboard-btn dashboard-btn--glass"
                    data-dashboard-refresh
                >
                    <i class="bi bi-arrow-clockwise"></i>

                    <span>
                        Actualiser
                    </span>
                </button>

            </div>

        </div>

        <div
            class="dashboard-hero__visual"
            aria-label="Identité visuelle MedTrack"
        >

            <div class="dashboard-hero__brand-stage">

                <span class="dashboard-hero__brand-glow"></span>
                <span class="dashboard-hero__gold-orb dashboard-hero__gold-orb--one"></span>
                <span class="dashboard-hero__gold-orb dashboard-hero__gold-orb--two"></span>

                <img
                    src="/assets/img/illustration_dashbord_1.png"
                    class="dashboard-hero__illustration dashboard-hero__illustration--main"
                    alt=""
                    loading="eager"
                >

                <img
                    src="/assets/img/illustration_dashbord_2.png"
                    class="dashboard-hero__illustration dashboard-hero__illustration--secondary"
                    alt=""
                    loading="eager"
                >

                <div class="dashboard-hero__logo-card">

                    <img
                        src="/assets/img/logo.png"
                        class="dashboard-hero__logo"
                        alt="MedTrack"
                    >

                    <span class="dashboard-hero__brand-caption">
                        La plateforme intelligente des stages médicaux
                    </span>

                </div>

            </div>

        </div>

    </section>


    <!--
    |--------------------------------------------------------------------------
    | Main indicators
    |--------------------------------------------------------------------------
    -->

    <section
        id="dashboard-overview"
        class="dashboard-metrics"
    >

        <article class="dashboard-metric dashboard-metric--students">

            <div class="dashboard-metric__header">

                <div class="dashboard-metric__icon">
                    <i class="bi bi-mortarboard-fill"></i>
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
                    étudiants enregistrés
                </span>

            </div>

            <div class="dashboard-metric__progress">
                <span style="--progress: 100%;"></span>
            </div>

        </article>


        <article class="dashboard-metric dashboard-metric--internships">

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
                    stages actuellement actifs
                </span>

            </div>

            <div class="dashboard-metric__progress">
                <span style="--progress: 100%;"></span>
            </div>

        </article>


        <article class="dashboard-metric dashboard-metric--hospitals">

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
                    data-counter="<?= $hospitals ?>"
                >
                    <?= $hospitals ?>
                </strong>

                <span class="dashboard-metric__label">
                    établissements hospitaliers actifs
                </span>

            </div>

            <div class="dashboard-metric__progress">
                <span style="--progress: 100%;"></span>
            </div>

        </article>


        <article class="dashboard-metric dashboard-metric--payments">

            <div class="dashboard-metric__header">

                <div class="dashboard-metric__icon">
                    <i class="bi bi-wallet2"></i>
                </div>

                <span class="dashboard-metric__badge">
                    Paiements
                </span>

            </div>

            <div class="dashboard-metric__body">

                <strong
                    class="dashboard-metric__value"
                    data-counter="<?= $successfulPayments ?>"
                >
                    <?= $successfulPayments ?>
                </strong>

                <span class="dashboard-metric__label">
                    transactions réussies
                </span>

            </div>

            <div class="dashboard-metric__progress">
                <span style="--progress: 100%;"></span>
            </div>

        </article>

    </section>


    <!--
    |--------------------------------------------------------------------------
    | Institutions overview
    |--------------------------------------------------------------------------
    -->

    <section class="dashboard-grid dashboard-grid--analytics">

        <article class="dashboard-panel dashboard-panel--large">

            <div class="dashboard-panel__header">

                <div>

                    <span class="dashboard-panel__eyebrow">
                        Institutions
                    </span>

                    <h3 class="dashboard-panel__title">
                        Écosystème MedTrack
                    </h3>

                </div>

                <span class="dashboard-panel__icon">
                    <i class="bi bi-buildings-fill"></i>
                </span>

            </div>


            <div class="dashboard-modules__grid">

                <a
                    href="/universities"
                    class="dashboard-module dashboard-module--students"
                >

                    <span class="dashboard-module__icon">
                        <i class="bi bi-bank2"></i>
                    </span>

                    <span class="dashboard-module__content">
                        <strong>
                            <?= $universities ?>
                            Université<?= $universities !== 1 ? 's' : '' ?>
                        </strong>

                        <small>
                            Institutions universitaires actives
                        </small>
                    </span>

                    <i class="bi bi-arrow-up-right dashboard-module__arrow"></i>

                </a>


                <a
                    href="/hospitals"
                    class="dashboard-module dashboard-module--hospitals"
                >

                    <span class="dashboard-module__icon">
                        <i class="bi bi-hospital-fill"></i>
                    </span>

                    <span class="dashboard-module__content">
                        <strong>
                            <?= $hospitals ?>
                            Hôpital<?= $hospitals !== 1 ? 'aux' : '' ?>
                        </strong>

                        <small>
                            Structures hospitalières
                        </small>
                    </span>

                    <i class="bi bi-arrow-up-right dashboard-module__arrow"></i>

                </a>


                <a
                    href="/professional-orders"
                    class="dashboard-module dashboard-module--assessments"
                >

                    <span class="dashboard-module__icon">
                        <i class="bi bi-patch-check-fill"></i>
                    </span>

                    <span class="dashboard-module__content">
                        <strong>
                            <?= $professionalOrders ?>
                            Ordre<?= $professionalOrders !== 1 ? 's' : '' ?>
                        </strong>

                        <small>
                            Ordres professionnels actifs
                        </small>
                    </span>

                    <i class="bi bi-arrow-up-right dashboard-module__arrow"></i>

                </a>


                <a
                    href="/ministries"
                    class="dashboard-module dashboard-module--payments"
                >

                    <span class="dashboard-module__icon">
                        <i class="bi bi-building-check"></i>
                    </span>

                    <span class="dashboard-module__content">
                        <strong>
                            <?= $ministries ?>
                            Ministère<?= $ministries !== 1 ? 's' : '' ?>
                        </strong>

                        <small>
                            Institutions ministérielles
                        </small>
                    </span>

                    <i class="bi bi-arrow-up-right dashboard-module__arrow"></i>

                </a>

            </div>

        </article>


        <article class="dashboard-panel">

            <div class="dashboard-panel__header">

                <div>

                    <span class="dashboard-panel__eyebrow">
                        Contexte
                    </span>

                    <h3 class="dashboard-panel__title">
                        Administration centrale
                    </h3>

                </div>

                <span class="dashboard-panel__icon">
                    <i class="bi bi-shield-lock-fill"></i>
                </span>

            </div>


            <div class="dashboard-activity">

                <div class="dashboard-activity__empty">

                    <span class="dashboard-activity__icon">
                        <i class="bi bi-shield-check"></i>
                    </span>

                    <strong>
                        Accès plateforme
                    </strong>

                    <p>
                        Vous travaillez actuellement dans le contexte
                        global MedTrack. Les données présentées ici
                        couvrent l’ensemble de la plateforme.
                    </p>

                </div>

            </div>

        </article>

    </section>


    <!--
    |--------------------------------------------------------------------------
    | Operational overview
    |--------------------------------------------------------------------------
    -->

    <section class="dashboard-grid dashboard-grid--operations">

        <article class="dashboard-panel dashboard-panel--large">

            <div class="dashboard-panel__header">

                <div>

                    <span class="dashboard-panel__eyebrow">
                        Pilotage
                    </span>

                    <h3 class="dashboard-panel__title">
                        Activité opérationnelle
                    </h3>

                </div>

            </div>


            <div class="dashboard-table-wrapper">

                <table class="table dashboard-table">

                    <thead>

                        <tr>
                            <th>Indicateur</th>
                            <th>Valeur</th>
                            <th>Périmètre</th>
                        </tr>

                    </thead>

                    <tbody>

                        <tr>
                            <td>Étudiants</td>

                            <td>
                                <strong>
                                    <?= $students ?>
                                </strong>
                            </td>

                            <td>
                                Plateforme
                            </td>
                        </tr>

                        <tr>
                            <td>Stages actifs</td>

                            <td>
                                <strong>
                                    <?= $activeInternships ?>
                                </strong>
                            </td>

                            <td>
                                Plateforme
                            </td>
                        </tr>

                        <tr>
                            <td>Paiements réussis</td>

                            <td>
                                <strong>
                                    <?= $successfulPayments ?>
                                </strong>
                            </td>

                            <td>
                                Plateforme
                            </td>
                        </tr>

                        <tr>
                            <td>Institutions actives</td>

                            <td>
                                <strong>
                                    <?=
                                        $universities
                                        + $hospitals
                                        + $professionalOrders
                                        + $ministries
                                    ?>
                                </strong>
                            </td>

                            <td>
                                Plateforme
                            </td>
                        </tr>

                    </tbody>

                </table>

            </div>

        </article>


        <article class="dashboard-panel">

            <div class="dashboard-panel__header">

                <div>

                    <span class="dashboard-panel__eyebrow">
                        Temps réel
                    </span>

                    <h3 class="dashboard-panel__title">
                        Activités récentes
                    </h3>

                </div>

                <span class="dashboard-live-indicator">
                    <span></span>
                    Live
                </span>

            </div>


            <div class="dashboard-activity">

                <div class="dashboard-activity__empty">

                    <span class="dashboard-activity__icon">
                        <i class="bi bi-bell"></i>
                    </span>

                    <strong>
                        Aucun événement récent
                    </strong>

                    <p>
                        Les événements issus de l’audit,
                        des inscriptions et des stages
                        apparaîtront ici progressivement.
                    </p>

                </div>

            </div>

        </article>

    </section>


    <!--
    |--------------------------------------------------------------------------
    | Module shortcuts
    |--------------------------------------------------------------------------
    -->

    <section class="dashboard-panel dashboard-modules">

        <div class="dashboard-panel__header">

            <div>

                <span class="dashboard-panel__eyebrow">
                    Navigation rapide
                </span>

                <h3 class="dashboard-panel__title">
                    Administration MedTrack
                </h3>

            </div>

        </div>


        <div class="dashboard-modules__grid dashboard-modules__grid--admin">

            <a href="/universities" class="dashboard-module dashboard-module--students">
                <span class="dashboard-module__icon"><i class="bi bi-bank2"></i></span>
                <span class="dashboard-module__content">
                    <strong>Universités</strong>
                    <small>Institutions universitaires</small>
                </span>
                <i class="bi bi-arrow-up-right dashboard-module__arrow"></i>
            </a>

            <a href="/hospitals" class="dashboard-module dashboard-module--hospitals">
                <span class="dashboard-module__icon"><i class="bi bi-hospital-fill"></i></span>
                <span class="dashboard-module__content">
                    <strong>Hôpitaux</strong>
                    <small>Structures hospitalières</small>
                </span>
                <i class="bi bi-arrow-up-right dashboard-module__arrow"></i>
            </a>

            <a href="/professional-orders" class="dashboard-module dashboard-module--assessments">
                <span class="dashboard-module__icon"><i class="bi bi-patch-check-fill"></i></span>
                <span class="dashboard-module__content">
                    <strong>Ordres professionnels</strong>
                    <small>Supervision institutionnelle</small>
                </span>
                <i class="bi bi-arrow-up-right dashboard-module__arrow"></i>
            </a>

            <a href="/ministries" class="dashboard-module dashboard-module--payments">
                <span class="dashboard-module__icon"><i class="bi bi-building-check"></i></span>
                <span class="dashboard-module__content">
                    <strong>Ministères</strong>
                    <small>Institutions ministérielles</small>
                </span>
                <i class="bi bi-arrow-up-right dashboard-module__arrow"></i>
            </a>

            <a href="/students" class="dashboard-module dashboard-module--students">
                <span class="dashboard-module__icon"><i class="bi bi-people-fill"></i></span>
                <span class="dashboard-module__content">
                    <strong>Étudiants</strong>
                    <small>Répertoire global</small>
                </span>
                <i class="bi bi-arrow-up-right dashboard-module__arrow"></i>
            </a>

            <a href="/academic-enrollments" class="dashboard-module dashboard-module--internships">
                <span class="dashboard-module__icon"><i class="bi bi-person-vcard-fill"></i></span>
                <span class="dashboard-module__content">
                    <strong>Inscriptions</strong>
                    <small>Inscriptions académiques</small>
                </span>
                <i class="bi bi-arrow-up-right dashboard-module__arrow"></i>
            </a>

            <a href="/internships" class="dashboard-module dashboard-module--internships">
                <span class="dashboard-module__icon"><i class="bi bi-briefcase-fill"></i></span>
                <span class="dashboard-module__content">
                    <strong>Stages</strong>
                    <small>Suivi des stages médicaux</small>
                </span>
                <i class="bi bi-arrow-up-right dashboard-module__arrow"></i>
            </a>

            <a href="/payments" class="dashboard-module dashboard-module--payments">
                <span class="dashboard-module__icon"><i class="bi bi-wallet2"></i></span>
                <span class="dashboard-module__content">
                    <strong>Paiements</strong>
                    <small>Transactions et règlements</small>
                </span>
                <i class="bi bi-arrow-up-right dashboard-module__arrow"></i>
            </a>

            <a href="/users" class="dashboard-module dashboard-module--attendance">
                <span class="dashboard-module__icon"><i class="bi bi-person-gear"></i></span>
                <span class="dashboard-module__content">
                    <strong>Utilisateurs</strong>
                    <small>Comptes et accès plateforme</small>
                </span>
                <i class="bi bi-arrow-up-right dashboard-module__arrow"></i>
            </a>

            <a href="/roles" class="dashboard-module dashboard-module--assessments">
                <span class="dashboard-module__icon"><i class="bi bi-shield-lock-fill"></i></span>
                <span class="dashboard-module__content">
                    <strong>Rôles & permissions</strong>
                    <small>Contrôle des autorisations</small>
                </span>
                <i class="bi bi-arrow-up-right dashboard-module__arrow"></i>
            </a>

            <a href="/audit" class="dashboard-module dashboard-module--audit">
                <span class="dashboard-module__icon"><i class="bi bi-journal-check"></i></span>
                <span class="dashboard-module__content">
                    <strong>Audit</strong>
                    <small>Traçabilité des opérations</small>
                </span>
                <i class="bi bi-arrow-up-right dashboard-module__arrow"></i>
            </a>

            <a href="/faculties" class="dashboard-module dashboard-module--hospitals">
                <span class="dashboard-module__icon"><i class="bi bi-diagram-3-fill"></i></span>
                <span class="dashboard-module__content">
                    <strong>Structure académique</strong>
                    <small>Facultés et programmes</small>
                </span>
                <i class="bi bi-arrow-up-right dashboard-module__arrow"></i>
            </a>

        </div>

    </section>

</div>