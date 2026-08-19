<?php

declare(strict_types=1);

$title = (string) (
    $dashboard['title']
    ?? 'Espace Ministère'
);

$subtitle = (string) (
    $dashboard['subtitle']
    ?? 'Supervision et statistiques nationales'
);
?>

<div class="medtrack-dashboard">

    <section class="dashboard-hero">

        <div class="dashboard-hero__content">

            <div class="dashboard-hero__eyebrow">
                <span class="dashboard-hero__pulse"></span>
                Supervision nationale
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
                Disposez d’une vision consolidée
                de l’écosystème académique et sanitaire
                supervisé par MedTrack.
            </p>

        </div>

        <div
            class="dashboard-hero__visual"
            aria-hidden="true"
        >

            <div class="dashboard-orbit dashboard-orbit--outer"></div>
            <div class="dashboard-orbit dashboard-orbit--inner"></div>

            <div class="dashboard-hero__medical-icon">
                <i class="bi bi-building-check"></i>
            </div>

            <span class="dashboard-floating-icon dashboard-floating-icon--one">
                <i class="bi bi-bar-chart-fill"></i>
            </span>

            <span class="dashboard-floating-icon dashboard-floating-icon--two">
                <i class="bi bi-map-fill"></i>
            </span>

            <span class="dashboard-floating-icon dashboard-floating-icon--three">
                <i class="bi bi-graph-up-arrow"></i>
            </span>

        </div>

    </section>


    <section class="dashboard-panel dashboard-modules">

        <div class="dashboard-panel__header">

            <div>
                <span class="dashboard-panel__eyebrow">
                    Pilotage national
                </span>

                <h3 class="dashboard-panel__title">
                    Modules de supervision
                </h3>
            </div>

        </div>

        <div class="dashboard-modules__grid">

            <div class="dashboard-module dashboard-module--students">

                <span class="dashboard-module__icon">
                    <i class="bi bi-mortarboard-fill"></i>
                </span>

                <span class="dashboard-module__content">
                    <strong>Universités</strong>
                    <small>Suivi institutionnel</small>
                </span>

            </div>

            <div class="dashboard-module dashboard-module--hospitals">

                <span class="dashboard-module__icon">
                    <i class="bi bi-hospital-fill"></i>
                </span>

                <span class="dashboard-module__content">
                    <strong>Hôpitaux</strong>
                    <small>Structures partenaires</small>
                </span>

            </div>

            <div class="dashboard-module dashboard-module--internships">

                <span class="dashboard-module__icon">
                    <i class="bi bi-briefcase-fill"></i>
                </span>

                <span class="dashboard-module__content">
                    <strong>Stages</strong>
                    <small>Suivi national</small>
                </span>

            </div>

            <div class="dashboard-module dashboard-module--attendance">

                <span class="dashboard-module__icon">
                    <i class="bi bi-bar-chart-fill"></i>
                </span>

                <span class="dashboard-module__content">
                    <strong>Statistiques</strong>
                    <small>Analyse nationale</small>
                </span>

            </div>

            <div class="dashboard-module dashboard-module--assessments">

                <span class="dashboard-module__icon">
                    <i class="bi bi-file-earmark-bar-graph-fill"></i>
                </span>

                <span class="dashboard-module__content">
                    <strong>Rapports</strong>
                    <small>Rapports institutionnels</small>
                </span>

            </div>

            <div class="dashboard-module dashboard-module--payments">

                <span class="dashboard-module__icon">
                    <i class="bi bi-shield-check"></i>
                </span>

                <span class="dashboard-module__content">
                    <strong>Audit</strong>
                    <small>Contrôle et traçabilité</small>
                </span>

            </div>

        </div>

    </section>

</div>