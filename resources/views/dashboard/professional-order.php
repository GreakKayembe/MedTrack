<?php

declare(strict_types=1);

$title = (string) (
    $dashboard['title']
    ?? 'Espace Ordre professionnel'
);

$subtitle = (string) (
    $dashboard['subtitle']
    ?? 'Inscriptions et validations professionnelles'
);
?>

<div class="medtrack-dashboard">

    <section class="dashboard-hero">

        <div class="dashboard-hero__content">

            <div class="dashboard-hero__eyebrow">
                <span class="dashboard-hero__pulse"></span>
                Ordre professionnel
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
                Examinez les dossiers transmis,
                contrôlez les pièces et gérez les
                validations professionnelles.
            </p>

        </div>

        <div
            class="dashboard-hero__visual"
            aria-hidden="true"
        >

            <div class="dashboard-orbit dashboard-orbit--outer"></div>
            <div class="dashboard-orbit dashboard-orbit--inner"></div>

            <div class="dashboard-hero__medical-icon">
                <i class="bi bi-patch-check-fill"></i>
            </div>

            <span class="dashboard-floating-icon dashboard-floating-icon--one">
                <i class="bi bi-file-earmark-person-fill"></i>
            </span>

            <span class="dashboard-floating-icon dashboard-floating-icon--two">
                <i class="bi bi-shield-check"></i>
            </span>

            <span class="dashboard-floating-icon dashboard-floating-icon--three">
                <i class="bi bi-award-fill"></i>
            </span>

        </div>

    </section>


    <section class="dashboard-panel dashboard-modules">

        <div class="dashboard-panel__header">

            <div>
                <span class="dashboard-panel__eyebrow">
                    Gestion professionnelle
                </span>

                <h3 class="dashboard-panel__title">
                    Modules
                </h3>
            </div>

        </div>

        <div class="dashboard-modules__grid">

            <div class="dashboard-module dashboard-module--students">

                <span class="dashboard-module__icon">
                    <i class="bi bi-file-earmark-person-fill"></i>
                </span>

                <span class="dashboard-module__content">
                    <strong>Dossiers</strong>
                    <small>Dossiers professionnels</small>
                </span>

            </div>

            <div class="dashboard-module dashboard-module--internships">

                <span class="dashboard-module__icon">
                    <i class="bi bi-check2-circle"></i>
                </span>

                <span class="dashboard-module__content">
                    <strong>Validations</strong>
                    <small>Contrôle des demandes</small>
                </span>

            </div>

            <div class="dashboard-module dashboard-module--hospitals">

                <span class="dashboard-module__icon">
                    <i class="bi bi-person-badge-fill"></i>
                </span>

                <span class="dashboard-module__content">
                    <strong>Inscriptions</strong>
                    <small>Registre professionnel</small>
                </span>

            </div>

            <div class="dashboard-module dashboard-module--assessments">

                <span class="dashboard-module__icon">
                    <i class="bi bi-award-fill"></i>
                </span>

                <span class="dashboard-module__content">
                    <strong>Certifications</strong>
                    <small>Documents professionnels</small>
                </span>

            </div>

        </div>

    </section>

</div>