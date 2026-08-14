'use strict';

/*
|--------------------------------------------------------------------------
| MedTrack Dashboard
|--------------------------------------------------------------------------
*/

document.addEventListener('DOMContentLoaded', () => {

    const dashboard = document.querySelector(
        '.medtrack-dashboard'
    );

    if (!dashboard) {
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | Counter animation
    |--------------------------------------------------------------------------
    */

    const counters = dashboard.querySelectorAll(
        '[data-counter]'
    );

    const animateCounter = (element) => {

        const target = Number(
            element.dataset.counter ?? 0
        );

        if (
            !Number.isFinite(target)
            || target <= 0
        ) {
            element.textContent = '0';
            return;
        }

        const duration = 900;

        const startTime = performance.now();

        const formatter = new Intl.NumberFormat(
            'fr-FR'
        );

        const update = (currentTime) => {

            const elapsed =
                currentTime - startTime;

            const progress = Math.min(
                elapsed / duration,
                1
            );

            /*
             * Ease-out cubic.
             */
            const eased =
                1 - Math.pow(
                    1 - progress,
                    3
                );

            const currentValue =
                Math.round(
                    target * eased
                );

            element.textContent =
                formatter.format(
                    currentValue
                );

            if (progress < 1) {
                requestAnimationFrame(
                    update
                );
            }
        };

        requestAnimationFrame(
            update
        );
    };


    /*
    |--------------------------------------------------------------------------
    | Counter observer
    |--------------------------------------------------------------------------
    */

    if ('IntersectionObserver' in window) {

        const counterObserver =
            new IntersectionObserver(
                (entries, observer) => {

                    entries.forEach(
                        (entry) => {

                            if (
                                !entry.isIntersecting
                            ) {
                                return;
                            }

                            animateCounter(
                                entry.target
                            );

                            observer.unobserve(
                                entry.target
                            );
                        }
                    );
                },
                {
                    threshold: 0.35,
                }
            );

        counters.forEach(
            (counter) => {
                counterObserver.observe(
                    counter
                );
            }
        );

    } else {

        counters.forEach(
            animateCounter
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Dashboard refresh
    |--------------------------------------------------------------------------
    */

    const refreshButton =
        dashboard.querySelector(
            '[data-dashboard-refresh]'
        );

    if (refreshButton) {

        refreshButton.addEventListener(
            'click',
            () => {

                if (
                    dashboard.classList.contains(
                        'is-refreshing'
                    )
                ) {
                    return;
                }

                dashboard.classList.add(
                    'is-refreshing'
                );

                refreshButton.disabled = true;

                window.setTimeout(
                    () => {

                        dashboard.classList.remove(
                            'is-refreshing'
                        );

                        refreshButton.disabled =
                            false;

                    },
                    700
                );
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Chart.js availability
    |--------------------------------------------------------------------------
    */

    if (typeof Chart === 'undefined') {

        console.warn(
            '[MedTrack Dashboard] Chart.js n’est pas disponible.'
        );

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | Chart defaults
    |--------------------------------------------------------------------------
    */

    Chart.defaults.font.family =
        'Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif';

    Chart.defaults.color =
        '#64748b';

    Chart.defaults.animation.duration =
        700;


    /*
    |--------------------------------------------------------------------------
    | Dashboard chart data
    |--------------------------------------------------------------------------
    |
    | IMPORTANT :
    |
    | Nous n'inventons aucune donnée métier.
    |
    | Ces tableaux seront alimentés plus tard depuis PHP / MySQL.
    |
    */

        const dashboardData = {

            /*
            * Données temporaires de démonstration.
            *
            * Elles seront remplacées par les données MySQL
            * lorsque les modules métier seront développés.
            */

            internships: {
                labels: [
                    'Mars',
                    'Avril',
                    'Mai',
                    'Juin',
                    'Juillet',
                    'Août',
                ],

                values: [
                    18,
                    27,
                    35,
                    31,
                    48,
                    56,
                ],
            },

            students: {
                labels: [
                    'Médecine',
                    'Sciences infirmières',
                    'Sage-femme',
                    'Autres',
                ],

                values: [
                    48,
                    32,
                    14,
                    6,
                ],
            },

        };
    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    const hasChartData = (
        labels,
        values
    ) => {

        if (
            !Array.isArray(labels)
            || !Array.isArray(values)
        ) {
            return false;
        }

        if (
            labels.length === 0
            || values.length === 0
        ) {
            return false;
        }

        return values.some(
            (value) =>
                Number(value) > 0
        );
    };


    const hideEmptyState = (
        name
    ) => {

        const emptyState =
            dashboard.querySelector(
                `[data-chart-empty="${name}"]`
            );

        if (emptyState) {
            emptyState.hidden = true;
        }
    };


    /*
    |--------------------------------------------------------------------------
    | Internships chart
    |--------------------------------------------------------------------------
    */

    const internshipsCanvas =
        document.getElementById(
            'internshipsChart'
        );

    let internshipsChart = null;

    const renderInternshipsChart = () => {

        if (!internshipsCanvas) {
            return;
        }

        const labels =
            dashboardData
                .internships
                .labels;

        const values =
            dashboardData
                .internships
                .values;

        if (
            !hasChartData(
                labels,
                values
            )
        ) {
            return;
        }

        hideEmptyState(
            'internships'
        );

        const context =
            internshipsCanvas.getContext(
                '2d'
            );

        if (!context) {
            return;
        }

        const gradient =
            context.createLinearGradient(
                0,
                0,
                0,
                300
            );

        gradient.addColorStop(
            0,
            'rgba(37, 99, 235, 0.30)'
        );

        gradient.addColorStop(
            1,
            'rgba(37, 99, 235, 0.01)'
        );

        internshipsChart =
            new Chart(
                context,
                {
                    type: 'line',

                    data: {
                        labels,

                        datasets: [
                            {
                                label:
                                    'Stages',

                                data:
                                    values,

                                borderColor:
                                    '#2563eb',

                                backgroundColor:
                                    gradient,

                                borderWidth:
                                    3,

                                fill:
                                    true,

                                tension:
                                    0.42,

                                pointRadius:
                                    4,

                                pointHoverRadius:
                                    6,

                                pointBackgroundColor:
                                    '#ffffff',

                                pointBorderColor:
                                    '#2563eb',

                                pointBorderWidth:
                                    2,
                            },
                        ],
                    },

                    options: {

                        responsive:
                            true,

                        maintainAspectRatio:
                            false,

                        interaction: {
                            intersect:
                                false,

                            mode:
                                'index',
                        },

                        plugins: {

                            legend: {
                                display:
                                    false,
                            },

                            tooltip: {

                                displayColors:
                                    false,

                                padding:
                                    12,

                                callbacks: {

                                    label:
                                        (context) =>
                                            `${context.parsed.y} stage(s)`,
                                },
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
                                    font: {
                                        size:
                                            11,
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
                                        'rgba(148, 163, 184, 0.14)',
                                },

                                ticks: {

                                    precision:
                                        0,

                                    font: {
                                        size:
                                            11,
                                    },
                                },
                            },
                        },
                    },
                }
            );
    };


    /*
    |--------------------------------------------------------------------------
    | Students distribution chart
    |--------------------------------------------------------------------------
    */

    const studentsCanvas =
        document.getElementById(
            'studentsChart'
        );

    let studentsChart = null;

    const renderStudentsChart = () => {

        if (!studentsCanvas) {
            return;
        }

        const labels =
            dashboardData
                .students
                .labels;

        const values =
            dashboardData
                .students
                .values;

        if (
            !hasChartData(
                labels,
                values
            )
        ) {
            return;
        }

        hideEmptyState(
            'students'
        );

        const context =
            studentsCanvas.getContext(
                '2d'
            );

        if (!context) {
            return;
        }

        studentsChart =
            new Chart(
                context,
                {
                    type:
                        'doughnut',

                    data: {

                        labels,

                        datasets: [
                            {
                                data:
                                    values,

                                backgroundColor: [
                                    '#2563eb',
                                    '#7c3aed',
                                    '#059669',
                                    '#0891b2',
                                    '#d97706',
                                ],

                                borderColor:
                                    '#ffffff',

                                borderWidth:
                                    4,

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
                            '72%',

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
                                        18,

                                    font: {
                                        size:
                                            11,
                                    },
                                },
                            },

                            tooltip: {

                                padding:
                                    12,

                                callbacks: {

                                    label:
                                        (context) => {

                                            const label =
                                                context.label
                                                ?? '';

                                            const value =
                                                context.parsed
                                                ?? 0;

                                            return `${label}: ${value}`;
                                        },
                                },
                            },
                        },
                    },
                }
            );
    };


    /*
    |--------------------------------------------------------------------------
    | Initial rendering
    |--------------------------------------------------------------------------
    */

    renderInternshipsChart();

    renderStudentsChart();


    /*
    |--------------------------------------------------------------------------
    | Period selector
    |--------------------------------------------------------------------------
    */

    const periodSelector =
        dashboard.querySelector(
            '[data-dashboard-period]'
        );

    if (periodSelector) {

        periodSelector.addEventListener(
            'change',
            () => {

                /*
                 * Cette interaction sera connectée au backend
                 * lorsque les données de stages existeront.
                 */

                if (!internshipsChart) {
                    return;
                }

                internshipsChart.update();
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Cleanup
    |--------------------------------------------------------------------------
    */

    window.addEventListener(
        'beforeunload',
        () => {

            if (internshipsChart) {
                internshipsChart.destroy();
            }

            if (studentsChart) {
                studentsChart.destroy();
            }
        }
    );

});