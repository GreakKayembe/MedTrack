<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| University dashboard data
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

$activeEnrollments =
    (int) (
        $metrics['activeEnrollments']
        ?? 0
    );

$activeInternships =
    (int) (
        $metrics['activeInternships']
        ?? 0
    );

$partnerHospitals =
    (int) (
        $metrics['partnerHospitals']
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
            ?? 'Espace Université'
        )
    );

$subtitle =
    trim(
        (string) (
            $dashboard['subtitle']
            ?? 'Gestion académique et suivi des stages'
        )
    );


/*
|--------------------------------------------------------------------------
| Derived values
|--------------------------------------------------------------------------
*/

$academicPopulation =
    $students
    + $activeEnrollments;

$operationalActivity =
    $activeInternships
    + $partnerHospitals;


/*
|--------------------------------------------------------------------------
| Chart data
|--------------------------------------------------------------------------
*/

$academicChartData = [
    'labels' => [
        'Étudiants',
        'Inscriptions actives',
        'Stages actifs',
        'Hôpitaux partenaires',
    ],

    'values' => [
        $students,
        $activeEnrollments,
        $activeInternships,
        $partnerHospitals,
    ],
];

$institutionChartData = [
    'labels' => [
        'Inscriptions',
        'Stages',
        'Hôpitaux partenaires',
    ],

    'values' => [
        $activeEnrollments,
        $activeInternships,
        $partnerHospitals,
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
                    Espace institutionnel
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

                Pilotez vos étudiants,
                inscriptions académiques,
                programmes et stages
                depuis votre espace universitaire.

            </p>


            <div class="dashboard-hero__actions">

                <a
                    href="/students"
                    class="btn
                           dashboard-btn
                           dashboard-btn--primary"
                >
                    <i
                        class="bi bi-people-fill"
                        aria-hidden="true"
                    ></i>

                    <span>
                        Mes étudiants
                    </span>
                </a>


                <a
                    href="/academic-enrollments"
                    class="btn
                           dashboard-btn
                           dashboard-btn--glass"
                >
                    <i
                        class="bi bi-person-vcard-fill"
                        aria-hidden="true"
                    ></i>

                    <span>
                        Inscriptions
                    </span>
                </a>

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


                <div class="dashboard-hero__logo-card">

                    <span
                        class="dashboard-university-hero-icon"
                    >
                        <i class="bi bi-mortarboard-fill"></i>
                    </span>


                    <span
                        class="dashboard-hero__brand-caption"
                    >
                        Gestion académique,
                        stages et suivi institutionnel
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
        aria-label="Indicateurs universitaires"
    >

        <!-- Students -->

        <article
            class="dashboard-metric
                   dashboard-metric--navy"
        >

            <div class="dashboard-metric__header">

                <span class="dashboard-metric__icon">

                    <i
                        class="bi bi-people-fill"
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
                    étudiants de l’université
                </span>

            </div>


            <div
                class="dashboard-metric__progress"
                aria-hidden="true"
            >
                <span></span>
            </div>

        </article>


        <!-- Enrollments -->

        <article
            class="dashboard-metric
                   dashboard-metric--turquoise"
        >

            <div class="dashboard-metric__header">

                <span class="dashboard-metric__icon">

                    <i
                        class="bi bi-person-vcard-fill"
                        aria-hidden="true"
                    ></i>

                </span>


                <span class="dashboard-metric__badge">
                    Inscriptions
                </span>

            </div>


            <div class="dashboard-metric__body">

                <strong
                    class="dashboard-metric__value"
                    data-counter="<?= $activeEnrollments ?>"
                >
                    <?= number_format(
                        $activeEnrollments,
                        0,
                        ',',
                        ' '
                    ) ?>
                </strong>


                <span class="dashboard-metric__label">
                    inscriptions actives
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
                   dashboard-metric--teal"
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
                    stages actuellement en cours
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
                   dashboard-metric--mint"
        >

            <div class="dashboard-metric__header">

                <span class="dashboard-metric__icon">

                    <i
                        class="bi bi-hospital-fill"
                        aria-hidden="true"
                    ></i>

                </span>


                <span class="dashboard-metric__badge">
                    Partenaires
                </span>

            </div>


            <div class="dashboard-metric__body">

                <strong
                    class="dashboard-metric__value"
                    data-counter="<?= $partnerHospitals ?>"
                >
                    <?= number_format(
                        $partnerHospitals,
                        0,
                        ',',
                        ' '
                    ) ?>
                </strong>


                <span class="dashboard-metric__label">
                    hôpitaux partenaires
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
    | Charts
    |--------------------------------------------------------------------------
    -->

    <section
        class="dashboard-grid
               dashboard-grid--main"
    >

        <!-- Academic activity -->

        <article class="dashboard-panel">

            <div class="dashboard-panel__header">

                <div>

                    <span class="dashboard-panel__eyebrow">
                        Pilotage académique
                    </span>


                    <h3 class="dashboard-panel__title">
                        Activité universitaire
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
                    id="universityAcademicChart"
                    role="img"
                    aria-label="Activité académique de l’université"
                ></canvas>

            </div>

        </article>


        <!-- Internship ecosystem -->

        <article class="dashboard-panel">

            <div class="dashboard-panel__header">

                <div>

                    <span class="dashboard-panel__eyebrow">
                        Stages
                    </span>


                    <h3 class="dashboard-panel__title">
                        Écosystème de stages
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
                    id="universityInternshipChart"
                    role="img"
                    aria-label="Répartition des stages de l’université"
                ></canvas>

            </div>


            <div class="dashboard-chart-summary">

                <div class="dashboard-chart-summary__item">

                    <span
                        class="dashboard-chart-summary__dot
                               dashboard-chart-summary__dot--navy"
                    ></span>

                    <div>
                        <small>
                            Inscriptions
                        </small>

                        <strong>
                            <?= $activeEnrollments ?>
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
                            Stages
                        </small>

                        <strong>
                            <?= $activeInternships ?>
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
                            Hôpitaux
                        </small>

                        <strong>
                            <?= $partnerHospitals ?>
                        </strong>
                    </div>

                </div>

            </div>

        </article>

    </section>


    <!--
    |--------------------------------------------------------------------------
    | Quick access
    |--------------------------------------------------------------------------
    -->

    <section
        class="dashboard-panel
               dashboard-shortcuts"
    >

        <div class="dashboard-panel__header">

            <div>

                <span class="dashboard-panel__eyebrow">
                    Gestion académique
                </span>


                <h3 class="dashboard-panel__title">
                    Accès rapides
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
                    'Répertoire institutionnel',
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
                    '/faculties',

                'icon' =>
                    'bi-diagram-3-fill',

                'title' =>
                    'Facultés',

                'description' =>
                    'Organisation académique',
            ],

            [
                'href' =>
                    '/academic-programs',

                'icon' =>
                    'bi-journal-bookmark-fill',

                'title' =>
                    'Programmes',

                'description' =>
                    'Programmes académiques',
            ],

            [
                'href' =>
                    '/academic-years',

                'icon' =>
                    'bi-calendar3',

                'title' =>
                    'Années académiques',

                'description' =>
                    'Calendrier académique',
            ],

            [
                'href' =>
                    '/study-levels',

                'icon' =>
                    'bi-layers-fill',

                'title' =>
                    'Niveaux d’études',

                'description' =>
                    'Référentiel des niveaux',
            ],

            [
                'href' =>
                    '/cohorts',

                'icon' =>
                    'bi-collection-fill',

                'title' =>
                    'Cohortes',

                'description' =>
                    'Promotions et groupes',
            ],

            [
                'href' =>
                    '/internships',

                'icon' =>
                    'bi-briefcase-fill',

                'title' =>
                    'Stages',

                'description' =>
                    'Suivi des stages médicaux',
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


    <!--
    |--------------------------------------------------------------------------
    | Finance / context
    |--------------------------------------------------------------------------
    -->

    <section
        class="dashboard-grid
               dashboard-grid--main"
    >

        <!-- Finance -->

        <article class="dashboard-panel">

            <div class="dashboard-panel__header">

                <div>

                    <span class="dashboard-panel__eyebrow">
                        Finance
                    </span>


                    <h3 class="dashboard-panel__title">
                        Situation des paiements
                    </h3>

                </div>


                <span class="dashboard-panel__icon">

                    <i
                        class="bi bi-wallet2"
                        aria-hidden="true"
                    ></i>

                </span>

            </div>


            <div class="dashboard-state">

                <span class="dashboard-state__icon">

                    <i
                        class="bi bi-credit-card-fill"
                        aria-hidden="true"
                    ></i>

                </span>


                <strong>

                    <?= number_format(
                        $successfulPayments,
                        0,
                        ',',
                        ' '
                    ) ?>

                    transaction<?= $successfulPayments !== 1 ? 's' : '' ?>

                    réussie<?= $successfulPayments !== 1 ? 's' : '' ?>

                </strong>


                <p>
                    Les informations financières
                    détaillées restent soumises
                    aux permissions du compte connecté.
                </p>

            </div>

        </article>


        <!-- Institutional summary -->

        <article class="dashboard-panel">

            <div class="dashboard-panel__header">

                <div>

                    <span class="dashboard-panel__eyebrow">
                        Synthèse
                    </span>


                    <h3 class="dashboard-panel__title">
                        Activité institutionnelle
                    </h3>

                </div>


                <span class="dashboard-panel__icon">

                    <i
                        class="bi bi-activity"
                        aria-hidden="true"
                    ></i>

                </span>

            </div>


            <div class="dashboard-university-summary">

                <div class="dashboard-university-summary__item">

                    <span>
                        Population académique
                    </span>

                    <strong>
                        <?= number_format(
                            $academicPopulation,
                            0,
                            ',',
                            ' '
                        ) ?>
                    </strong>

                </div>


                <div class="dashboard-university-summary__item">

                    <span>
                        Activité de stage
                    </span>

                    <strong>
                        <?= number_format(
                            $operationalActivity,
                            0,
                            ',',
                            ' '
                        ) ?>
                    </strong>

                </div>


                <div class="dashboard-university-summary__item">

                    <span>
                        Paiements réussis
                    </span>

                    <strong>
                        <?= number_format(
                            $successfulPayments,
                            0,
                            ',',
                            ' '
                        ) ?>
                    </strong>

                </div>

            </div>

        </article>

    </section>

</div>


<!--
|--------------------------------------------------------------------------
| Chart.js
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

        const academicData =
            <?= json_encode(
                $academicChartData,
                JSON_UNESCAPED_UNICODE
                | JSON_UNESCAPED_SLASHES
                | JSON_THROW_ON_ERROR
            ) ?>;

        const internshipData =
            <?= json_encode(
                $institutionChartData,
                JSON_UNESCAPED_UNICODE
                | JSON_UNESCAPED_SLASHES
                | JSON_THROW_ON_ERROR
            ) ?>;


        /*
        |--------------------------------------------------------------------------
        | MedTrack colors
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

            muted:
                '#667085',

            grid:
                'rgba(11,31,58,0.07)',
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
        | Academic bar chart
        |--------------------------------------------------------------------------
        */

        const academicCanvas =
            document.getElementById(
                'universityAcademicChart'
            );

        if (academicCanvas) {

            new Chart(
                academicCanvas,
                {
                    type:
                        'bar',

                    data: {
                        labels:
                            academicData.labels,

                        datasets: [
                            {
                                label:
                                    'Volume',

                                data:
                                    academicData.values,

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
                                    9,

                                borderSkipped:
                                    false,

                                maxBarThickness:
                                    52,
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

                                    padding:
                                        8,

                                    color:
                                        colors.muted,

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
        | Internship doughnut
        |--------------------------------------------------------------------------
        */

        const internshipCanvas =
            document.getElementById(
                'universityInternshipChart'
            );

        if (internshipCanvas) {

            new Chart(
                internshipCanvas,
                {
                    type:
                        'doughnut',

                    data: {
                        labels:
                            internshipData.labels,

                        datasets: [
                            {
                                data:
                                    internshipData.values,

                                backgroundColor: [
                                    colors.navy,
                                    colors.turquoise,
                                    colors.turquoiseLight,
                                ],

                                borderColor:
                                    '#ffffff',

                                borderWidth:
                                    3,

                                hoverOffset:
                                    7,
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
                                            10,

                                        weight:
                                            '600',
                                    },
                                },
                            },
                        },
                    },
                }
            );

        }

    }
);
</script>