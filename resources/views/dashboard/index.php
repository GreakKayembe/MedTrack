<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Dashboard data
|--------------------------------------------------------------------------
|
| Ces valeurs sont temporaires.
|
| Elles seront progressivement remplacées par les données réelles
| provenant des modules Students, Internships, Hospitals, Payments,
| Attendance et Assessments.
|
*/

$dashboard = [
    'students' => 0,
    'activeInternships' => 0,
    'hospitals' => 0,
    'payments' => 0,
];
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
                Vue d'ensemble
            </div>

            <h2 class="dashboard-hero__title">
                Bienvenue sur MedTrack
            </h2>

            <p class="dashboard-hero__description">
                Suivez les étudiants, les stages, les structures
                partenaires et l'activité académique depuis un espace
                centralisé.
            </p>

            <div class="dashboard-hero__actions">

                <a
                    href="#dashboard-overview"
                    class="btn dashboard-btn dashboard-btn--primary"
                >
                    <i class="bi bi-grid-1x2-fill"></i>

                    <span>
                        Voir l'activité
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
            aria-hidden="true"
        >

            <div class="dashboard-orbit dashboard-orbit--outer"></div>

            <div class="dashboard-orbit dashboard-orbit--inner"></div>

            <div class="dashboard-hero__medical-icon">
                <i class="bi bi-heart-pulse-fill"></i>
            </div>

            <span class="dashboard-floating-icon dashboard-floating-icon--one">
                <i class="bi bi-mortarboard-fill"></i>
            </span>

            <span class="dashboard-floating-icon dashboard-floating-icon--two">
                <i class="bi bi-hospital-fill"></i>
            </span>

            <span class="dashboard-floating-icon dashboard-floating-icon--three">
                <i class="bi bi-clipboard2-pulse-fill"></i>
            </span>

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
                    data-counter="<?= (int) $dashboard['students'] ?>"
                >
                    0
                </strong>

                <span class="dashboard-metric__label">
                    étudiants enregistrés
                </span>

            </div>

            <div class="dashboard-metric__progress">
                <span style="--progress: 0%;"></span>
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
                    data-counter="<?= (int) $dashboard['activeInternships'] ?>"
                >
                    0
                </strong>

                <span class="dashboard-metric__label">
                    stages actuellement actifs
                </span>

            </div>

            <div class="dashboard-metric__progress">
                <span style="--progress: 0%;"></span>
            </div>

        </article>


        <article class="dashboard-metric dashboard-metric--hospitals">

            <div class="dashboard-metric__header">

                <div class="dashboard-metric__icon">
                    <i class="bi bi-hospital-fill"></i>
                </div>

                <span class="dashboard-metric__badge">
                    Structures
                </span>

            </div>

            <div class="dashboard-metric__body">

                <strong
                    class="dashboard-metric__value"
                    data-counter="<?= (int) $dashboard['hospitals'] ?>"
                >
                    0
                </strong>

                <span class="dashboard-metric__label">
                    structures partenaires
                </span>

            </div>

            <div class="dashboard-metric__progress">
                <span style="--progress: 0%;"></span>
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
                    data-counter="<?= (int) $dashboard['payments'] ?>"
                >
                    0
                </strong>

                <span class="dashboard-metric__label">
                    transactions enregistrées
                </span>

            </div>

            <div class="dashboard-metric__progress">
                <span style="--progress: 0%;"></span>
            </div>

        </article>

    </section>


    <!--
    |--------------------------------------------------------------------------
    | Analytics
    |--------------------------------------------------------------------------
    -->

    <section class="dashboard-grid dashboard-grid--analytics">

        <!-- Internship evolution -->

        <article class="dashboard-panel dashboard-panel--large">

            <div class="dashboard-panel__header">

                <div>

                    <span class="dashboard-panel__eyebrow">
                        Analyse
                    </span>

                    <h3 class="dashboard-panel__title">
                        Évolution des stages
                    </h3>

                </div>

                <div class="dashboard-panel__action">

                    <select
                        class="form-select form-select-sm dashboard-select"
                        aria-label="Période du graphique"
                        data-dashboard-period
                    >
                        <option value="6">
                            6 mois
                        </option>

                        <option value="12">
                            12 mois
                        </option>
                    </select>

                </div>

            </div>


            <div class="dashboard-chart">

                <canvas
                    id="internshipsChart"
                    aria-label="Évolution des stages"
                    role="img"
                ></canvas>

                <div
                    class="dashboard-chart__empty"
                    data-chart-empty="internships"
                >
                    <div class="dashboard-empty-icon">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>

                    <strong>
                        Les statistiques apparaîtront ici
                    </strong>

                    <span>
                        Le graphique sera alimenté dès que les
                        premiers stages seront enregistrés.
                    </span>
                </div>

            </div>

        </article>


        <!-- Student distribution -->

        <article class="dashboard-panel">

            <div class="dashboard-panel__header">

                <div>

                    <span class="dashboard-panel__eyebrow">
                        Répartition
                    </span>

                    <h3 class="dashboard-panel__title">
                        Étudiants
                    </h3>

                </div>

                <span class="dashboard-panel__icon">
                    <i class="bi bi-pie-chart-fill"></i>
                </span>

            </div>


            <div class="dashboard-chart dashboard-chart--donut">

                <canvas
                    id="studentsChart"
                    aria-label="Répartition des étudiants"
                    role="img"
                ></canvas>

                <div
                    class="dashboard-chart__empty"
                    data-chart-empty="students"
                >
                    <div class="dashboard-empty-icon">
                        <i class="bi bi-pie-chart"></i>
                    </div>

                    <strong>
                        Aucune donnée disponible
                    </strong>

                    <span>
                        La répartition apparaîtra après
                        l'enregistrement des étudiants.
                    </span>
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

        <!-- Recent assignments -->

        <article class="dashboard-panel dashboard-panel--large">

            <div class="dashboard-panel__header">

                <div>

                    <span class="dashboard-panel__eyebrow">
                        Affectations
                    </span>

                    <h3 class="dashboard-panel__title">
                        Derniers stages
                    </h3>

                </div>

                <button
                    type="button"
                    class="dashboard-link-button"
                    disabled
                >
                    Voir tout

                    <i class="bi bi-arrow-right"></i>
                </button>

            </div>


            <div class="dashboard-table-wrapper">

                <table class="table dashboard-table">

                    <thead>

                        <tr>
                            <th>Étudiant</th>
                            <th>Structure</th>
                            <th>Service</th>
                            <th>Statut</th>
                        </tr>

                    </thead>

                    <tbody>

                        <tr class="dashboard-table__empty-row">

                            <td colspan="4">

                                <div class="dashboard-table-empty">

                                    <span class="dashboard-table-empty__icon">
                                        <i class="bi bi-clipboard2-check"></i>
                                    </span>

                                    <div>

                                        <strong>
                                            Aucun stage enregistré
                                        </strong>

                                        <span>
                                            Les dernières affectations
                                            apparaîtront ici.
                                        </span>

                                    </div>

                                </div>

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </article>


        <!-- Activity -->

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
                        Rien de nouveau
                    </strong>

                    <p>
                        Les événements importants de MedTrack
                        apparaîtront ici automatiquement.
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
                    Modules MedTrack
                </h3>

            </div>

        </div>


        <div class="dashboard-modules__grid">

            <button
                type="button"
                class="dashboard-module dashboard-module--students"
                disabled
            >

                <span class="dashboard-module__icon">
                    <i class="bi bi-people-fill"></i>
                </span>

                <span class="dashboard-module__content">
                    <strong>Étudiants</strong>
                    <small>Gestion académique</small>
                </span>

                <i class="bi bi-arrow-up-right dashboard-module__arrow"></i>

            </button>


            <button
                type="button"
                class="dashboard-module dashboard-module--internships"
                disabled
            >

                <span class="dashboard-module__icon">
                    <i class="bi bi-briefcase-fill"></i>
                </span>

                <span class="dashboard-module__content">
                    <strong>Stages</strong>
                    <small>Affectations et rotations</small>
                </span>

                <i class="bi bi-arrow-up-right dashboard-module__arrow"></i>

            </button>


            <button
                type="button"
                class="dashboard-module dashboard-module--hospitals"
                disabled
            >

                <span class="dashboard-module__icon">
                    <i class="bi bi-hospital-fill"></i>
                </span>

                <span class="dashboard-module__content">
                    <strong>Structures</strong>
                    <small>Hôpitaux partenaires</small>
                </span>

                <i class="bi bi-arrow-up-right dashboard-module__arrow"></i>

            </button>


            <button
                type="button"
                class="dashboard-module dashboard-module--attendance"
                disabled
            >

                <span class="dashboard-module__icon">
                    <i class="bi bi-calendar2-check-fill"></i>
                </span>

                <span class="dashboard-module__content">
                    <strong>Présences</strong>
                    <small>Suivi et assiduité</small>
                </span>

                <i class="bi bi-arrow-up-right dashboard-module__arrow"></i>

            </button>


            <button
                type="button"
                class="dashboard-module dashboard-module--assessments"
                disabled
            >

                <span class="dashboard-module__icon">
                    <i class="bi bi-clipboard2-data-fill"></i>
                </span>

                <span class="dashboard-module__content">
                    <strong>Évaluations</strong>
                    <small>Notes et compétences</small>
                </span>

                <i class="bi bi-arrow-up-right dashboard-module__arrow"></i>

            </button>


            <button
                type="button"
                class="dashboard-module dashboard-module--payments"
                disabled
            >

                <span class="dashboard-module__icon">
                    <i class="bi bi-credit-card-fill"></i>
                </span>

                <span class="dashboard-module__content">
                    <strong>Paiements</strong>
                    <small>Transactions et suivi</small>
                </span>

                <i class="bi bi-arrow-up-right dashboard-module__arrow"></i>

            </button>

        </div>

    </section>

</div>