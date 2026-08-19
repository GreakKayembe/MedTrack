<?php

declare(strict_types=1);

/** @var string $csrfToken */

$currentAccess =
    $currentAccess
    ?? [];

$displayName =
    (string) (
        $currentAccess['display_name']
        ?? 'Utilisateur'
    );

$contextLabel =
    (string) (
        $currentAccess['label']
        ?? 'MedTrack'
    );

$organizationName =
    trim(
        (string) (
            $currentAccess['organization_name']
            ?? ''
        )
    );

$roles =
    is_array(
        $currentAccess['roles']
        ?? null
    )
        ? $currentAccess['roles']
        : [];

$roleName =
    (string) (
        $roles[0]['name']
        ?? match (
            $currentAccess['scope']
            ?? null
        ) {
            'PLATFORM' =>
                'Administration plateforme',

            'STUDENT' =>
                'Étudiant',

            default =>
                'Compte MedTrack',
        }
    );

$initials =
    strtoupper(
        substr(
            trim(
                preg_replace(
                    '/\s+/',
                    '',
                    $displayName
                )
                ?? $displayName
            ),
            0,
            2
        )
    );

if ($initials === '') {
    $initials = 'MT';
}
?>

<header
    id="header"
    class="header fixed-top d-flex align-items-center medtrack-header"
>

    <div class="medtrack-header__brand d-flex align-items-center">

        <a
            href="/"
            class="logo d-flex align-items-center gap-2"
            aria-label="Accueil MedTrack"
        >
            <span class="medtrack-header__logo-wrap">
                <img
                    src="/assets/img/logo.png"
                    alt="MedTrack"
                    class="medtrack-header__logo"
                >
            </span>

            <span class="d-none d-lg-flex flex-column lh-sm">
                <strong class="medtrack-header__brand-name">
                    MedTrack
                </strong>

                <small class="medtrack-header__brand-tagline">
                    Stages médicaux
                </small>
            </span>
        </a>

        <button
            type="button"
            class="toggle-sidebar-btn medtrack-header__toggle"
            aria-label="Afficher ou masquer le menu"
        >
            <i class="bi bi-list"></i>
        </button>

    </div>


    <div
        class="d-none d-md-flex
               align-items-center
               ms-3
               medtrack-header__context"
    >

        <span class="medtrack-header__context-icon">
            <i class="bi bi-grid-1x2-fill"></i>
        </span>

        <div>

            <small class="medtrack-header__context-label d-block">
                <?= htmlspecialchars(
                    $contextLabel,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </small>

            <strong class="medtrack-header__context-name">
                <?= htmlspecialchars(
                    $organizationName !== ''
                        ? $organizationName
                        : $roleName,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </strong>

        </div>

    </div>


    <nav class="header-nav ms-auto">

        <ul class="d-flex align-items-center">

            <li class="nav-item dropdown">

                <a
                    class="nav-link nav-icon medtrack-header__icon-button"
                    href="#"
                    data-bs-toggle="dropdown"
                    aria-label="Notifications"
                >
                    <i class="bi bi-bell"></i>
                </a>

                <ul
                    class="dropdown-menu dropdown-menu-end
                           dropdown-menu-arrow notifications
                           medtrack-dropdown"
                >

                    <li class="dropdown-header">
                        Aucune nouvelle notification
                    </li>

                </ul>

            </li>


            <li class="nav-item dropdown pe-3">

                <a
                    class="nav-link nav-profile
                           d-flex align-items-center pe-0
                           medtrack-header__profile"
                    href="#"
                    data-bs-toggle="dropdown"
                    aria-label="Menu du profil"
                >

                    <span class="medtrack-header__avatar">
                        <?= htmlspecialchars(
                            $initials,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </span>

                    <span
                        class="d-none d-md-flex
                               flex-column
                               dropdown-toggle
                               ps-2
                               medtrack-header__profile-copy"
                    >
                        <strong>
                            <?= htmlspecialchars(
                                $displayName,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </strong>

                        <small>
                            <?= htmlspecialchars(
                                $roleName,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </small>
                    </span>

                </a>


                <ul
                    class="dropdown-menu dropdown-menu-end
                           dropdown-menu-arrow profile
                           medtrack-dropdown
                           medtrack-profile-menu"
                >

                    <li class="dropdown-header">

                        <span class="medtrack-profile-menu__avatar">
                            <?= htmlspecialchars(
                                $initials,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </span>

                        <h6>
                            <?= htmlspecialchars(
                                $displayName,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </h6>

                        <span>
                            <?= htmlspecialchars(
                                $roleName,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </span>

                        <?php if ($organizationName !== ''): ?>

                            <small class="d-block mt-1 text-muted">
                                <?= htmlspecialchars(
                                    $organizationName,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </small>

                        <?php endif; ?>

                    </li>


                    <li>
                        <hr class="dropdown-divider">
                    </li>


                    <li>

                        <a
                            class="dropdown-item d-flex align-items-center"
                            href="#"
                        >
                            <i class="bi bi-person"></i>

                            <span>
                                Mon profil
                            </span>
                        </a>

                    </li>


                    <li>
                        <hr class="dropdown-divider">
                    </li>


                    <li>

                        <form
                            action="/logout"
                            method="POST"
                            id="logout-form"
                        >

                            <input
                                type="hidden"
                                name="_token"
                                value="<?= htmlspecialchars(
                                    $csrfToken,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>"
                            >

                            <button
                                type="submit"
                                class="dropdown-item
                                       d-flex align-items-center
                                       medtrack-profile-menu__logout"
                            >
                                <i class="bi bi-box-arrow-right"></i>

                                <span>
                                    Déconnexion
                                </span>
                            </button>

                        </form>

                    </li>

                </ul>

            </li>

        </ul>

    </nav>

</header>