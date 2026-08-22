<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Dashboard data
|--------------------------------------------------------------------------
*/

$metrics =
    is_array(
        $dashboard['metrics']
        ?? null
    )
        ? $dashboard['metrics']
        : [];

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
    trim(
        (string) (
            $dashboard['title']
            ?? 'Administration MedTrack'
        )
    );

$subtitle =
    trim(
        (string) (
            $dashboard['subtitle']
            ?? 'Vue globale de la plateforme'
        )
    );

$totalInstitutions =
    $universities
    + $hospitals
    + $professionalOrders
    + $ministries;


/*
|--------------------------------------------------------------------------
| Chart data
|--------------------------------------------------------------------------
*/

$institutionChartData = [
    'labels' => [
        'Universités',
        'Hôpitaux',
        'Ordres professionnels',
        'Ministères',
    ],

    'values' => [
        $universities,
        $hospitals,
        $professionalOrders,
        $ministries,
    ],
];

$activityChartData = [
    'labels' => [
        'Étudiants',
        'Stages actifs',
        'Paiements réussis',
        'Institutions',
    ],

    'values' => [
        $students,
        $activeInternships,
        $successfulPayments,
        $totalInstitutions,
    ],
];
?>

<div class="medtrack-dashboard">

    <!--
    |--------------------------------------------------------------------------
    | Hero
    |--------------------------------------------------------------------------
    -->

    <section class="dashboard-hero">

        <div class="dashboard-hero__content">

            <div class="dashboard-hero__eyebrow">

                <span
                    class="dashboard-hero__pulse"
                    aria-hidden="true"
                ></span>

                <span>
                    Super administration MedTrack
                </span>

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

                Analysez les institutions,
                les étudiants, les stages et
                l’activité opérationnelle
                depuis l’espace central MedTrack.

            </p>


            <div class="dashboard-hero__actions">

                <a
                    href="#dashboard-overview"
                    class="btn
                           dashboard-btn
                           dashboard-btn--primary"
                >
                    <i
                        class="bi bi-bar-chart-fill"
                        aria-hidden="true"
                    ></i>

                    <span>
                        Voir les statistiques
                    </span>
                </a>


                <button
                    type="button"
                    class="btn
                           dashboard-btn
                           dashboard-btn--glass"
                    data-dashboard-refresh
                >
                    <i
                        class="bi bi-arrow-clockwise"
                        aria-hidden="true"
                    ></i>

                    <span>
                        Actualiser
                    </span>
                </button>

            </div>

        </div>


        <!-- Hero visual -->

        <div
            class="dashboard-hero__visual"
            aria-hidden="true"
        >

            <div class="dashboard-hero__brand-stage">

                <span
                    class="dashboard-hero__brand-glow"
                ></span>


                <span
                    class="dashboard-hero__accent-orb
                           dashboard-hero__accent-orb--one"
                ></span>


                <span
                    class="dashboard-hero__accent-orb
                           dashboard-hero__accent-orb--two"
                ></span>


                <img
                    src="/assets/img/illustration_dashbord_1.png"
                    class="dashboard-hero__illustration
                           dashboard-hero__illustration--main"
                    alt=""
                    loading="eager"
                >


                <img
                    src="/assets/img/illustration_dashbord_2.png"
                    class="dashboard-hero__illustration
                           dashboard-hero__illustration--secondary"
                    alt=""
                    loading="eager"
                >


                <div class="dashboard-hero__logo-card">

                    <img
                        src="/assets/img/logo.png"
                        class="dashboard-hero__logo"
                        alt="MedTrack"
                    >

                    <span
                        class="dashboard-hero__brand-caption"
                    >
                        La plateforme intelligente
                        des stages médicaux
                    </span>

                </div>

            </div>

        </div>

    </section>


    <!--
    |--------------------------------------------------------------------------
    | KPI
    |--------------------------------------------------------------------------
    -->

    <section
        id="dashboard-overview"
        class="dashboard-metrics"
        aria-label="Indicateurs principaux"
    >

        <!-- Students -->

        <article
            class="dashboard-metric
                   dashboard-metric--navy"
        >

            <div class="dashboard-metric__header">

                <span class="dashboard-metric__icon">

                    <i
                        class="bi bi-mortarboard-fill"
                        aria-hidden="true"
                    ></i>

                </span>


                <span class="dashboard-metric__badge">
                    Étudiants
                </span>

            </div>


            <div class="dashboard-metric__body">

                <strong
                    class="dashboard-metric__value"
                    data-counter="<?= $students ?>"
                >
                    <?= number_format(
                        $students,
                        0,
                        ',',
                        ' '
                    ) ?>
                </strong>


                <span class="dashboard-metric__label">
                    étudiants enregistrés
                </span>

            </div>


            <div
                class="dashboard-metric__progress"
                aria-hidden="true"
            >
                <span></span>
            </div>

        </article>


        <!-- Internships -->

        <article
            class="dashboard-metric
                   dashboard-metric--turquoise"
        >

            <div class="dashboard-metric__header">

                <span class="dashboard-metric__icon">

                    <i
                        class="bi bi-briefcase-fill"
                        aria-hidden="true"
                    ></i>

                </span>


                <span class="dashboard-metric__badge">
                    Stages
                </span>

            </div>


            <div class="dashboard-metric__body">

                <strong
                    class="dashboard-metric__value"
                    data-counter="<?= $activeInternships ?>"
                >
                    <?= number_format(
                        $activeInternships,
                        0,
                        ',',
                        ' '
                    ) ?>
                </strong>


                <span class="dashboard-metric__label">
                    stages actuellement actifs
                </span>

            </div>


            <div
                class="dashboard-metric__progress"
                aria-hidden="true"
            >
                <span></span>
            </div>

        </article>


        <!-- Hospitals -->

        <article
            class="dashboard-metric
                   dashboard-metric--teal"
        >

            <div class="dashboard-metric__header">

                <span class="dashboard-metric__icon">

                    <i
                        class="bi bi-hospital-fill"
                        aria-hidden="true"
                    ></i>

                </span>


                <span class="dashboard-metric__badge">
                    Hôpitaux
                </span>

            </div>


            <div class="dashboard-metric__body">

                <strong
                    class="dashboard-metric__value"
                    data-counter="<?= $hospitals ?>"
                >
                    <?= number_format(
                        $hospitals,
                        0,
                        ',',
                        ' '
                    ) ?>
                </strong>


                <span class="dashboard-metric__label">
                    structures hospitalières
                </span>

            </div>


            <div
                class="dashboard-metric__progress"
                aria-hidden="true"
            >
                <span></span>
            </div>

        </article>


        <!-- Payments -->

        <article
            class="dashboard-metric
                   dashboard-metric--mint"
        >

            <div class="dashboard-metric__header">

                <span class="dashboard-metric__icon">

                    <i
                        class="bi bi-wallet2"
                        aria-hidden="true"
                    ></i>

                </span>


                <span class="dashboard-metric__badge">
                    Paiements
                </span>

            </div>


            <div class="dashboard-metric__body">

                <strong
                    class="dashboard-metric__value"
                    data-counter="<?= $successfulPayments ?>"
                >
                    <?= number_format(
                        $successfulPayments,
                        0,
                        ',',
                        ' '
                    ) ?>
                </strong>


                <span class="dashboard-metric__label">
                    transactions réussies
                </span>

            </div>


            <div
                class="dashboard-metric__progress"
                aria-hidden="true"
            >
                <span></span>
            </div>

        </article>

    </section>


    <!--
    |--------------------------------------------------------------------------
    | Analytics
    |--------------------------------------------------------------------------
    -->

    <section
        class="dashboard-grid
               dashboard-grid--main"
    >

        <!-- Institutions doughnut -->

        <article class="dashboard-panel">

            <div class="dashboard-panel__header">

                <div>

                    <span class="dashboard-panel__eyebrow">
                        Institutions
                    </span>


                    <h3 class="dashboard-panel__title">
                        Répartition des institutions
                    </h3>

                </div>


                <span class="dashboard-panel__icon">

                    <i
                        class="bi bi-pie-chart-fill"
                        aria-hidden="true"
                    ></i>

                </span>

            </div>


            <div
                class="dashboard-chart
                       dashboard-chart--donut"
            >

                <canvas
                    id="dashboardInstitutionsChart"
                    role="img"
                    aria-label="Répartition des institutions MedTrack"
                ></canvas>

            </div>


            <div
                class="dashboard-chart-summary"
                aria-label="Résumé des institutions"
            >

                <div class="dashboard-chart-summary__item">

                    <span
                        class="dashboard-chart-summary__dot
                               dashboard-chart-summary__dot--navy"
                    ></span>

                    <div>
                        <small>
                            Universités
                        </small>

                        <strong>
                            <?= $universities ?>
                        </strong>
                    </div>

                </div>


                <div class="dashboard-chart-summary__item">

                    <span
                        class="dashboard-chart-summary__dot
                               dashboard-chart-summary__dot--turquoise"
                    ></span>

                    <div>
                        <small>
                            Hôpitaux
                        </small>

                        <strong>
                            <?= $hospitals ?>
                        </strong>
                    </div>

                </div>


                <div class="dashboard-chart-summary__item">

                    <span
                        class="dashboard-chart-summary__dot
                               dashboard-chart-summary__dot--navy-light"
                    ></span>

                    <div>
                        <small>
                            Ordres
                        </small>

                        <strong>
                            <?= $professionalOrders ?>
                        </strong>
                    </div>

                </div>


                <div class="dashboard-chart-summary__item">

                    <span
                        class="dashboard-chart-summary__dot
                               dashboard-chart-summary__dot--mint"
                    ></span>

                    <div>
                        <small>
                            Ministères
                        </small>

                        <strong>
                            <?= $ministries ?>
                        </strong>
                    </div>

                </div>

            </div>

        </article>


        <!-- Activity bar -->

        <article class="dashboard-panel">

            <div class="dashboard-panel__header">

                <div>

                    <span class="dashboard-panel__eyebrow">
                        Pilotage
                    </span>


                    <h3 class="dashboard-panel__title">
                        Activité de la plateforme
                    </h3>

                </div>


                <span class="dashboard-panel__icon">

                    <i
                        class="bi bi-bar-chart-fill"
                        aria-hidden="true"
                    ></i>

                </span>

            </div>


            <div class="dashboard-chart">

                <canvas
                    id="dashboardActivityChart"
                    role="img"
                    aria-label="Activité globale de la plateforme MedTrack"
                ></canvas>

            </div>

        </article>

    </section>


    <!--
    |--------------------------------------------------------------------------
    | Platform overview
    |--------------------------------------------------------------------------
    -->

    <section
        class="dashboard-grid
               dashboard-grid--main"
    >

        <!-- Institution shortcuts -->

        <article class="dashboard-panel">

            <div class="dashboard-panel__header">

                <div>

                    <span class="dashboard-panel__eyebrow">
                        Écosystème
                    </span>


                    <h3 class="dashboard-panel__title">
                        Institutions MedTrack
                    </h3>

                </div>


                <span class="dashboard-panel__icon">

                    <i
                        class="bi bi-buildings-fill"
                        aria-hidden="true"
                    ></i>

                </span>

            </div>


            <div class="dashboard-modules__grid">

                <a
                    href="/universities"
                    class="dashboard-module"
                >

                    <span class="dashboard-module__icon">
                        <i class="bi bi-bank2"></i>
                    </span>


                    <span class="dashboard-module__content">

                        <strong>
                            Universités
                        </strong>

                        <small>
                            <?= $universities ?>
                            institution<?= $universities !== 1 ? 's' : '' ?>
                        </small>

                    </span>


                    <i
                        class="bi bi-arrow-up-right
                               dashboard-module__arrow"
                    ></i>

                </a>


                <a
                    href="/hospitals"
                    class="dashboard-module"
                >

                    <span class="dashboard-module__icon">
                        <i class="bi bi-hospital"></i>
                    </span>


                    <span class="dashboard-module__content">

                        <strong>
                            Hôpitaux
                        </strong>

                        <small>
                            <?= $hospitals ?>
                            structure<?= $hospitals !== 1 ? 's' : '' ?>
                        </small>

                    </span>


                    <i
                        class="bi bi-arrow-up-right
                               dashboard-module__arrow"
                    ></i>

                </a>


                <a
                    href="/professional-orders"
                    class="dashboard-module"
                >

                    <span class="dashboard-module__icon">
                        <i class="bi bi-patch-check-fill"></i>
                    </span>


                    <span class="dashboard-module__content">

                        <strong>
                            Ordres professionnels
                        </strong>

                        <small>
                            <?= $professionalOrders ?>
                            ordre<?= $professionalOrders !== 1 ? 's' : '' ?>
                        </small>

                    </span>


                    <i
                        class="bi bi-arrow-up-right
                               dashboard-module__arrow"
                    ></i>

                </a>


                <a
                    href="/ministries"
                    class="dashboard-module"
                >

                    <span class="dashboard-module__icon">
                        <i class="bi bi-building-check"></i>
                    </span>


                    <span class="dashboard-module__content">

                        <strong>
                            Ministères
                        </strong>

                        <small>
                            <?= $ministries ?>
                            ministère<?= $ministries !== 1 ? 's' : '' ?>
                        </small>

                    </span>


                    <i
                        class="bi bi-arrow-up-right
                               dashboard-module__arrow"
                    ></i>

                </a>

            </div>

        </article>


        <!-- Live state -->

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


            <div class="dashboard-state">

                <span class="dashboard-state__icon">

                    <i
                        class="bi bi-bell"
                        aria-hidden="true"
                    ></i>

                </span>


                <strong>
                    Aucun événement récent
                </strong>


                <p>
                    Les événements issus de
                    l’audit, des inscriptions
                    et des stages apparaîtront
                    ici progressivement.
                </p>

            </div>

        </article>

    </section>


    <!--
    |--------------------------------------------------------------------------
    | Quick navigation
    |--------------------------------------------------------------------------
    -->

    <section
        class="dashboard-panel
               dashboard-shortcuts"
    >

        <div class="dashboard-panel__header">

            <div>

                <span class="dashboard-panel__eyebrow">
                    Accès rapides
                </span>


                <h3 class="dashboard-panel__title">
                    Administration MedTrack
                </h3>

            </div>


            <span class="dashboard-panel__icon">

                <i
                    class="bi bi-lightning-charge-fill"
                    aria-hidden="true"
                ></i>

            </span>

        </div>


        <?php
        $shortcuts = [
            [
                'href' =>
                    '/students',

                'icon' =>
                    'bi-people-fill',

                'title' =>
                    'Étudiants',

                'description' =>
                    'Répertoire global',
            ],

            [
                'href' =>
                    '/academic-enrollments',

                'icon' =>
                    'bi-person-vcard-fill',

                'title' =>
                    'Inscriptions',

                'description' =>
                    'Parcours académiques',
            ],

            [
                'href' =>
                    '/internships',

                'icon' =>
                    'bi-briefcase-fill',

                'title' =>
                    'Stages',

                'description' =>
                    'Gestion des stages',
            ],

            [
                'href' =>
                    '/faculties',

                'icon' =>
                    'bi-diagram-3-fill',

                'title' =>
                    'Structure académique',

                'description' =>
                    'Facultés et programmes',
            ],

            [
                'href' =>
                    '/users',

                'icon' =>
                    'bi-person-gear',

                'title' =>
                    'Utilisateurs',

                'description' =>
                    'Comptes et accès',
            ],

            [
                'href' =>
                    '/roles',

                'icon' =>
                    'bi-shield-lock-fill',

                'title' =>
                    'Rôles & permissions',

                'description' =>
                    'Contrôle RBAC',
            ],

            [
                'href' =>
                    '/payments',

                'icon' =>
                    'bi-wallet2',

                'title' =>
                    'Paiements',

                'description' =>
                    'Transactions',
            ],

            [
                'href' =>
                    '/audit',

                'icon' =>
                    'bi-journal-check',

                'title' =>
                    'Audit',

                'description' =>
                    'Traçabilité',
            ],
        ];
        ?>


        <div
            class="dashboard-modules__grid
                   dashboard-modules__grid--admin"
        >

            <?php foreach (
                $shortcuts
                as $shortcut
            ): ?>

                <a
                    href="<?= htmlspecialchars(
                        $shortcut['href'],
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                    class="dashboard-module"
                >

                    <span class="dashboard-module__icon">

                        <i
                            class="bi <?= htmlspecialchars(
                                $shortcut['icon'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>"
                        ></i>

                    </span>


                    <span class="dashboard-module__content">

                        <strong>
                            <?= htmlspecialchars(
                                $shortcut['title'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </strong>


                        <small>
                            <?= htmlspecialchars(
                                $shortcut['description'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </small>

                    </span>


                    <i
                        class="bi bi-arrow-up-right
                               dashboard-module__arrow"
                    ></i>

                </a>

            <?php endforeach; ?>

        </div>

    </section>

</div>


<!--
|--------------------------------------------------------------------------
| Chart.js
|--------------------------------------------------------------------------
|
| chart.umd.js est déjà chargé dans partials/scripts.php.
|--------------------------------------------------------------------------
-->

<script>
document.addEventListener(
    'DOMContentLoaded',
    () => {

        if (
            typeof Chart
            === 'undefined'
        ) {
            console.warn(
                'Chart.js n’est pas disponible.'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Data
        |--------------------------------------------------------------------------
        */

        const institutionsData =
            <?= json_encode(
                $institutionChartData,
                JSON_UNESCAPED_UNICODE
                | JSON_UNESCAPED_SLASHES
                | JSON_THROW_ON_ERROR
            ) ?>;

        const activityData =
            <?= json_encode(
                $activityChartData,
                JSON_UNESCAPED_UNICODE
                | JSON_UNESCAPED_SLASHES
                | JSON_THROW_ON_ERROR
            ) ?>;


        /*
        |--------------------------------------------------------------------------
        | Brand
        |--------------------------------------------------------------------------
        */

        const colors = {
            navy:
                '#0b1f3a',

            navyLight:
                '#163a5f',

            turquoise:
                '#14b8a6',

            turquoiseDark:
                '#0f8f83',

            turquoiseLight:
                '#5eead4',

            grid:
                'rgba(11, 31, 58, 0.07)',

            muted:
                '#667085',
        };


        /*
        |--------------------------------------------------------------------------
        | Defaults
        |--------------------------------------------------------------------------
        */

        Chart.defaults.font.family =
            'Inter, -apple-system, '
            + 'BlinkMacSystemFont, '
            + '"Segoe UI", sans-serif';

        Chart.defaults.color =
            colors.muted;


        /*
        |--------------------------------------------------------------------------
        | Institutions
        |--------------------------------------------------------------------------
        */

        const institutionsCanvas =
            document.getElementById(
                'dashboardInstitutionsChart'
            );

        if (institutionsCanvas) {

            new Chart(
                institutionsCanvas,
                {
                    type:
                        'doughnut',

                    data: {
                        labels:
                            institutionsData.labels,

                        datasets: [
                            {
                                data:
                                    institutionsData.values,

                                backgroundColor: [
                                    colors.navy,
                                    colors.turquoise,
                                    colors.navyLight,
                                    colors.turquoiseLight,
                                ],

                                borderColor:
                                    '#ffffff',

                                borderWidth:
                                    3,

                                hoverOffset:
                                    8,
                            },
                        ],
                    },

                    options: {
                        responsive:
                            true,

                        maintainAspectRatio:
                            false,

                        cutout:
                            '70%',

                        animation: {
                            duration:
                                700,
                        },

                        plugins: {
                            legend: {
                                position:
                                    'bottom',

                                labels: {
                                    usePointStyle:
                                        true,

                                    pointStyle:
                                        'circle',

                                    padding:
                                        17,

                                    boxWidth:
                                        8,

                                    boxHeight:
                                        8,

                                    font: {
                                        size:
                                            11,

                                        weight:
                                            '600',
                                    },
                                },
                            },

                            tooltip: {
                                callbacks: {
                                    label:
                                        (context) => {
                                            const label =
                                                context.label
                                                || '';

                                            const value =
                                                Number(
                                                    context.raw
                                                    || 0
                                                );

                                            return (
                                                `${label}: ${value}`
                                            );
                                        },
                                },
                            },
                        },
                    },
                }
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Platform activity
        |--------------------------------------------------------------------------
        */

        const activityCanvas =
            document.getElementById(
                'dashboardActivityChart'
            );

        if (activityCanvas) {

            new Chart(
                activityCanvas,
                {
                    type:
                        'bar',

                    data: {
                        labels:
                            activityData.labels,

                        datasets: [
                            {
                                label:
                                    'Volume',

                                data:
                                    activityData.values,

                                backgroundColor: [
                                    colors.navy,
                                    colors.turquoise,
                                    colors.turquoiseDark,
                                    colors.navyLight,
                                ],

                                hoverBackgroundColor: [
                                    colors.navyLight,
                                    colors.turquoiseDark,
                                    colors.turquoise,
                                    colors.navy,
                                ],

                                borderWidth:
                                    0,

                                borderRadius:
                                    8,

                                borderSkipped:
                                    false,

                                maxBarThickness:
                                    48,
                            },
                        ],
                    },

                    options: {
                        responsive:
                            true,

                        maintainAspectRatio:
                            false,

                        animation: {
                            duration:
                                700,
                        },

                        plugins: {
                            legend: {
                                display:
                                    false,
                            },

                            tooltip: {
                                displayColors:
                                    false,
                            },
                        },

                        scales: {
                            x: {
                                grid: {
                                    display:
                                        false,
                                },

                                border: {
                                    display:
                                        false,
                                },

                                ticks: {
                                    color:
                                        colors.muted,

                                    font: {
                                        size:
                                            10,

                                        weight:
                                            '600',
                                    },
                                },
                            },

                            y: {
                                beginAtZero:
                                    true,

                                border: {
                                    display:
                                        false,
                                },

                                grid: {
                                    color:
                                        colors.grid,

                                    drawTicks:
                                        false,
                                },

                                ticks: {
                                    precision:
                                        0,

                                    color:
                                        colors.muted,

                                    padding:
                                        8,

                                    font: {
                                        size:
                                            10,
                                    },
                                },
                            },
                        },
                    },
                }
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Refresh
        |--------------------------------------------------------------------------
        */

        const dashboard =
            document.querySelector(
                '.medtrack-dashboard'
            );

        const refreshButton =
            document.querySelector(
                '[data-dashboard-refresh]'
            );

        if (
            dashboard
            && refreshButton
        ) {

            refreshButton.addEventListener(
                'click',
                () => {

                    dashboard
                        .classList
                        .add(
                            'is-refreshing'
                        );

                    window.setTimeout(
                        () => {
                            window
                                .location
                                .reload();
                        },
                        350
                    );

                }
            );

        }

    }
);
</script>