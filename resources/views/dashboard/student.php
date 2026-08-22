<?php

declare(strict_types=1);

$metrics = $dashboard['metrics'] ?? [];

$academicEnrollments =
    (int) (
        $metrics['academicEnrollments']
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

$title = (string) (
    $dashboard['title']
    ?? 'Mon espace étudiant'
);

$subtitle = (string) (
    $dashboard['subtitle']
    ?? 'Parcours académique et stages'
);
?>

<div class="medtrack-dashboard">

    <section class="dashboard-hero">

        <div class="dashboard-hero__content">

            <div class="dashboard-hero__eyebrow">
                <span class="dashboard-hero__pulse"></span>
                Espace personnel
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
                Consultez votre cursus, vos stages,
                vos évaluations et vos documents
                depuis votre espace personnel.
            </p>

        </div>

        <div
            class="dashboard-hero__visual"
            aria-hidden="true"
        >

            <div class="dashboard-orbit dashboard-orbit--outer"></div>
            <div class="dashboard-orbit dashboard-orbit--inner"></div>

            <div class="dashboard-hero__medical-icon">
                <i class="bi bi-person-circle"></i>
            </div>

            <span class="dashboard-floating-icon dashboard-floating-icon--one">
                <i class="bi bi-mortarboard-fill"></i>
            </span>

            <span class="dashboard-floating-icon dashboard-floating-icon--two">
                <i class="bi bi-briefcase-fill"></i>
            </span>

            <span class="dashboard-floating-icon dashboard-floating-icon--three">
                <i class="bi bi-award-fill"></i>
            </span>

        </div>

    </section>


    <section class="dashboard-metrics">

        <article class="dashboard-metric dashboard-metric--students">

            <div class="dashboard-metric__header">

                <div class="dashboard-metric__icon">
                    <i class="bi bi-journal-bookmark-fill"></i>
                </div>

                <span class="dashboard-metric__badge">
                    Parcours
                </span>

            </div>

            <div class="dashboard-metric__body">

                <strong
                    class="dashboard-metric__value"
                    data-counter="<?= $academicEnrollments ?>"
                >
                    <?= $academicEnrollments ?>
                </strong>

                <span class="dashboard-metric__label">
                    inscriptions académiques
                </span>

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

        </article>

    </section>


    <section class="dashboard-panel dashboard-modules">

        <div class="dashboard-panel__header">

            <div>
                <span class="dashboard-panel__eyebrow">
                    Mon parcours
                </span>

                <h3 class="dashboard-panel__title">
                    Mes services MedTrack
                </h3>
            </div>

        </div>

        <div class="dashboard-modules__grid">

            <div class="dashboard-module dashboard-module--students">

                <span class="dashboard-module__icon">
                    <i class="bi bi-person-vcard-fill"></i>
                </span>

                <span class="dashboard-module__content">
                    <strong>Mon profil</strong>
                    <small>Informations personnelles</small>
                </span>

            </div>

            <div class="dashboard-module dashboard-module--internships">

                <span class="dashboard-module__icon">
                    <i class="bi bi-journal-bookmark-fill"></i>
                </span>

                <span class="dashboard-module__content">
                    <strong>Mon cursus</strong>
                    <small>Inscriptions académiques</small>
                </span>

            </div>

            <div class="dashboard-module dashboard-module--hospitals">

                <span class="dashboard-module__icon">
                    <i class="bi bi-briefcase-fill"></i>
                </span>

                <span class="dashboard-module__content">
                    <strong>Mes stages</strong>
                    <small>Affectations et rotations</small>
                </span>

            </div>

            <div class="dashboard-module dashboard-module--attendance">

                <span class="dashboard-module__icon">
                    <i class="bi bi-calendar2-check-fill"></i>
                </span>

                <span class="dashboard-module__content">
                    <strong>Mes présences</strong>
                    <small>Assiduité et présence</small>
                </span>

            </div>

            <div class="dashboard-module dashboard-module--assessments">

                <span class="dashboard-module__icon">
                    <i class="bi bi-journal-check"></i>
                </span>

                <span class="dashboard-module__content">
                    <strong>Mon logbook</strong>
                    <small>Activités de stage</small>
                </span>

            </div>

            <div class="dashboard-module dashboard-module--payments">

                <span class="dashboard-module__icon">
                    <i class="bi bi-award-fill"></i>
                </span>

                <span class="dashboard-module__content">
                    <strong>Mes attestations</strong>
                    <small>Documents et certifications</small>
                </span>

            </div>

        </div>

    </section>

</div>