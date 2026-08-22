<script
    src="/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"
></script>

<script
    src="/assets/vendor/chartjs/chart.umd.js"
></script>

<script
    src="/assets/vendor/sweetalert2/sweetalert2.all.min.js"
></script>


<!--
|--------------------------------------------------------------------------
| MedTrack shell
|--------------------------------------------------------------------------
-->

<script>
document.addEventListener(
    'DOMContentLoaded',
    () => {
        const body =
            document.body;

        const sidebar =
            document.getElementById(
                'medtrackSidebar'
            );

        const toggle =
            document.getElementById(
                'medtrackSidebarToggle'
            );

        if (
            !sidebar
            || !toggle
        ) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Breakpoint
        |--------------------------------------------------------------------------
        */

        const desktopMedia =
            window.matchMedia(
                '(min-width: 1200px)'
            );


        /*
        |--------------------------------------------------------------------------
        | Accessibility
        |--------------------------------------------------------------------------
        */

        const updateAriaState =
            () => {
                if (desktopMedia.matches) {
                    const collapsed =
                        body.classList.contains(
                            'medtrack-sidebar-collapsed'
                        );

                    toggle.setAttribute(
                        'aria-expanded',
                        collapsed
                            ? 'false'
                            : 'true'
                    );

                    return;
                }

                const opened =
                    body.classList.contains(
                        'medtrack-sidebar-open'
                    );

                toggle.setAttribute(
                    'aria-expanded',
                    opened
                        ? 'true'
                        : 'false'
                );
            };


        /*
        |--------------------------------------------------------------------------
        | Desktop
        |--------------------------------------------------------------------------
        */

        const toggleDesktopSidebar =
            () => {
                body.classList.toggle(
                    'medtrack-sidebar-collapsed'
                );

                /*
                 * Temporary compatibility with
                 * our current CSS migration.
                 */
                body.classList.toggle(
                    'toggle-sidebar',
                    body.classList.contains(
                        'medtrack-sidebar-collapsed'
                    )
                );

                updateAriaState();
            };


        /*
        |--------------------------------------------------------------------------
        | Mobile
        |--------------------------------------------------------------------------
        */

        const openMobileSidebar =
            () => {
                body.classList.add(
                    'medtrack-sidebar-open'
                );

                updateAriaState();
            };


        const closeMobileSidebar =
            () => {
                body.classList.remove(
                    'medtrack-sidebar-open'
                );

                body.classList.remove(
                    'toggle-sidebar'
                );

                updateAriaState();
            };


        const toggleMobileSidebar =
            () => {
                if (
                    body.classList.contains(
                        'medtrack-sidebar-open'
                    )
                ) {
                    closeMobileSidebar();

                    return;
                }

                openMobileSidebar();
            };


        /*
        |--------------------------------------------------------------------------
        | Toggle
        |--------------------------------------------------------------------------
        */

        toggle.addEventListener(
            'click',
            () => {
                if (desktopMedia.matches) {
                    toggleDesktopSidebar();

                    return;
                }

                toggleMobileSidebar();
            }
        );


        /*
        |--------------------------------------------------------------------------
        | Close on overlay
        |--------------------------------------------------------------------------
        */

        document.addEventListener(
            'click',
            (event) => {
                if (
                    desktopMedia.matches
                    || !body.classList.contains(
                        'medtrack-sidebar-open'
                    )
                ) {
                    return;
                }

                const target =
                    event.target;

                if (
                    !(target instanceof Node)
                ) {
                    return;
                }

                const clickedInsideSidebar =
                    sidebar.contains(
                        target
                    );

                const clickedToggle =
                    toggle.contains(
                        target
                    );

                if (
                    !clickedInsideSidebar
                    && !clickedToggle
                ) {
                    closeMobileSidebar();
                }
            }
        );


        /*
        |--------------------------------------------------------------------------
        | Escape
        |--------------------------------------------------------------------------
        */

        document.addEventListener(
            'keydown',
            (event) => {
                if (
                    event.key === 'Escape'
                    && !desktopMedia.matches
                ) {
                    closeMobileSidebar();
                }
            }
        );


        /*
        |--------------------------------------------------------------------------
        | Navigation on mobile
        |--------------------------------------------------------------------------
        */

        sidebar.addEventListener(
            'click',
            (event) => {
                if (desktopMedia.matches) {
                    return;
                }

                const target =
                    event.target;

                if (
                    !(target instanceof Element)
                ) {
                    return;
                }

                const link =
                    target.closest(
                        'a.medtrack-sidebar__link'
                    );

                if (!link) {
                    return;
                }

                /*
                 * A collapse trigger must not
                 * close the sidebar.
                 */
                if (
                    link.hasAttribute(
                        'data-bs-toggle'
                    )
                ) {
                    return;
                }

                if (
                    link.classList.contains(
                        'is-disabled'
                    )
                ) {
                    return;
                }

                closeMobileSidebar();
            }
        );


        /*
        |--------------------------------------------------------------------------
        | Breakpoint change
        |--------------------------------------------------------------------------
        */

        desktopMedia.addEventListener(
            'change',
            () => {
                if (desktopMedia.matches) {
                    body.classList.remove(
                        'medtrack-sidebar-open'
                    );
                } else {
                    body.classList.remove(
                        'medtrack-sidebar-collapsed'
                    );

                    body.classList.remove(
                        'toggle-sidebar'
                    );
                }

                updateAriaState();
            }
        );


        /*
        |--------------------------------------------------------------------------
        | Initial state
        |--------------------------------------------------------------------------
        */

        updateAriaState();
    }
);
</script>


<!--
|--------------------------------------------------------------------------
| Legacy application JS
|--------------------------------------------------------------------------
|
| main.js reste temporairement chargé.
| Dès que nous aurons vérifié qu'il ne contient plus
| de fonctions indispensables hors NiceAdmin,
| nous le supprimerons.
|--------------------------------------------------------------------------
-->

<script
    src="/assets/js/main.js"
></script>


<!--
|--------------------------------------------------------------------------
| Page scripts
|--------------------------------------------------------------------------
-->

<?php if (
    !empty($pageScripts)
    && is_array($pageScripts)
): ?>

    <?php foreach (
        $pageScripts
        as $script
    ): ?>

        <script
            src="<?= htmlspecialchars(
                (string) $script,
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
        ></script>

    <?php endforeach; ?>

<?php endif; ?>