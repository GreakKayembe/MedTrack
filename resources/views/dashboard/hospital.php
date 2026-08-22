<?php

declare(strict_types=1);

$metrics = $dashboard['metrics'] ?? [];

$students = (int) ($metrics['students'] ?? 0);
$activeInternships = (int) ($metrics['activeInternships'] ?? 0);
$partnerUniversities = (int) ($metrics['partnerUniversities'] ?? 0);

$title = (string) (
    $dashboard['title']
    ?? 'Espace Hôpital'
);

$subtitle = (string) (
    $dashboard['subtitle']
    ?? 'Stages, affectations et encadrement'
);
?>

<div class="medtrack-dashboard">

    <section class="dashboard-hero">

        <div class="dashboard-hero__content">

            <div class="dashboard-hero__eyebrow">
                <span class="dashboard-hero__pulse"></span>
                Espace hospitalier
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
                Supervisez les étudiants accueillis,
                les stages, les rotations et l’encadrement
                clinique.
            </p>

        </div>

        <div
            class="dashboard-hero__visual"
            aria-hidden="true"
        >

            <div class="dashboard-orbit dashboard-orbit--outer"></div>
            <div class="dashboard-orbit dashboard-orbit--inner"></div>

            <div class="dashboard-hero__medical-icon">
                <i class="bi bi-hospital-fill"></i>
            </div>

            <span class="dashboard-floating-icon dashboard-floating-icon--one">
                <i class="bi bi-person-badge-fill"></i>
            </span>

            <span class="dashboard-floating-icon dashboard-floating-icon--two">
                <i class="bi bi-briefcase-fill"></i>
            </span>

            <span class="dashboard-floating-icon dashboard-floating-icon--three">
                <i class="bi bi-clipboard2-pulse-fill"></i>
            </span>

        </div>

    </section>


    <section class="dashboard-metrics">

        <article class="dashboard-metric dashboard-metric--students">

            <div class="dashboard-metric__header">
                <div class="dashboard-metric__icon">
                    <i class="bi bi-people-fill"></i>
                </div>

                <span class="dashboard-metric__badge">
                    Stagiaires
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
                    étudiants accueillis
                </span>

            </div>

        </article>


        <article class="dashboard-metric dashboard-metric--internships">

            <div class="dashboard-metric__header">
                <div class="dashboard-metric__icon">
                    <i class="bi bi-briefcase-fill"></i>
                </div>

                <span class="dashboard-metric__badge">
                    Stages actifs
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


        <article class="dashboard-metric dashboard-metric--hospitals">

            <div class="dashboard-metric__header">
                <div class="dashboard-metric__icon">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>

                <span class="dashboard-metric__badge">
                    Universités
                </span>
            </div>

            <div class="dashboard-metric__body">

                <strong
                    class="dashboard-metric__value"
                    data-counter="<?= $partnerUniversities ?>"
                >
                    <?= $partnerUniversities ?>
                </strong>

                <span class="dashboard-metric__label">
                    universités partenaires
                </span>

            </div>

        </article>

    </section>


    <section class="dashboard-panel dashboard-modules">

        <div class="dashboard-panel__header">

            <div>
                <span class="dashboard-panel__eyebrow">
                    Gestion hospitalière
                </span>

                <h3 class="dashboard-panel__title">
                    Modules
                </h3>
            </div>

        </div>

        <div class="dashboard-modules__grid">

            <div class="dashboard-module dashboard-module--students">

                <span class="dashboard-module__icon">
                    <i class="bi bi-person-vcard-fill"></i>
                </span>

                <span class="dashboard-module__content">
                    <strong>Stagiaires</strong>
                    <small>Étudiants affectés</small>
                </span>

            </div>

            <div class="dashboard-module dashboard-module--internships">

                <span class="dashboard-module__icon">
                    <i class="bi bi-briefcase-fill"></i>
                </span>

                <span class="dashboard-module__content">
                    <strong>Stages</strong>
                    <small>Gestion des stages</small>
                </span>

            </div>

            <div class="dashboard-module dashboard-module--hospitals">

                <span class="dashboard-module__icon">
                    <i class="bi bi-diagram-3-fill"></i>
                </span>

                <span class="dashboard-module__content">
                    <strong>Services</strong>
                    <small>Services hospitaliers</small>
                </span>

            </div>

            <div class="dashboard-module dashboard-module--attendance">

                <span class="dashboard-module__icon">
                    <i class="bi bi-arrow-repeat"></i>
                </span>

                <span class="dashboard-module__content">
                    <strong>Rotations</strong>
                    <small>Planification clinique</small>
                </span>

            </div>

            <div class="dashboard-module dashboard-module--assessments">

                <span class="dashboard-module__icon">
                    <i class="bi bi-person-workspace"></i>
                </span>

                <span class="dashboard-module__content">
                    <strong>Encadreurs</strong>
                    <small>Supervision des étudiants</small>
                </span>

            </div>

            <div class="dashboard-module dashboard-module--payments">

                <span class="dashboard-module__icon">
                    <i class="bi bi-clipboard2-check-fill"></i>
                </span>

                <span class="dashboard-module__content">
                    <strong>Évaluations</strong>
                    <small>Compétences et validations</small>
                </span>

            </div>

        </div>

    </section>

</div>