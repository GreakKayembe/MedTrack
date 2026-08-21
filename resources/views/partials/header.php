<?php

declare(strict_types=1);

/**
 * @var string $csrfToken
 */

$currentAccess =
    $currentAccess
    ?? [];

$displayName =
    trim(
        (string) (
            $currentAccess['display_name']
            ?? 'Utilisateur'
        )
    );

$contextLabel =
    trim(
        (string) (
            $currentAccess['label']
            ?? 'MedTrack'
        )
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
    trim(
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
        )
    );


/*
|--------------------------------------------------------------------------
| Initiales
|--------------------------------------------------------------------------
*/

$initials =
    'MT';

$nameParts =
    preg_split(
        '/\s+/',
        $displayName
    );

if (
    is_array($nameParts)
    && $nameParts !== []
) {
    $firstName =
        trim(
            (string) (
                $nameParts[0]
                ?? ''
            )
        );

    $lastName =
        count($nameParts) > 1
            ? trim(
                (string) (
                    $nameParts[
                        count($nameParts) - 1
                    ]
                    ?? ''
                )
            )
            : '';

    $firstInitial =
        $firstName !== ''
            ? mb_strtoupper(
                mb_substr(
                    $firstName,
                    0,
                    1,
                    'UTF-8'
                ),
                'UTF-8'
            )
            : '';

    $lastInitial =
        $lastName !== ''
            ? mb_strtoupper(
                mb_substr(
                    $lastName,
                    0,
                    1,
                    'UTF-8'
                ),
                'UTF-8'
            )
            : '';

    $candidate =
        $firstInitial
        . $lastInitial;

    if ($candidate !== '') {
        $initials =
            $candidate;
    }
}


/*
|--------------------------------------------------------------------------
| Contexte
|--------------------------------------------------------------------------
*/

$contextDisplayName =
    $organizationName !== ''
        ? $organizationName
        : $roleName;
?>

<header
    id="medtrackHeader"
    class="medtrack-header"
>

    <!--
    |--------------------------------------------------------------------------
    | Marque
    |--------------------------------------------------------------------------
    -->

    <div
        class="medtrack-header__brand
               d-flex
               align-items-center"
    >

        <a
            href="/"
            class="medtrack-header__brand-link"
            aria-label="Accueil MedTrack"
        >

            <span
                class="medtrack-header__logo-wrap"
            >
                <img
                    src="/assets/img/logo.png"
                    alt="MedTrack"
                    class="medtrack-header__logo"
                >
            </span>


            <span
                class="medtrack-header__brand-copy
                       d-none
                       d-lg-flex"
            >

                <strong
                    class="medtrack-header__brand-name"
                >
                    MedTrack
                </strong>

                <small
                    class="medtrack-header__brand-tagline"
                >
                    Stages médicaux
                </small>

            </span>

        </a>


        <button
            type="button"
            class="medtrack-header__toggle"
            id="medtrackSidebarToggle"
            aria-label="Afficher ou masquer le menu"
            aria-controls="medtrackSidebar"
            aria-expanded="true"
        >
            <i class="bi bi-list"></i>
        </button>

    </div>


    <!--
    |--------------------------------------------------------------------------
    | Contexte actif
    |--------------------------------------------------------------------------
    -->

    <div
        class="medtrack-header__context
               d-none
               d-md-flex"
    >

        <span
            class="medtrack-header__context-icon"
            aria-hidden="true"
        >
            <i class="bi bi-grid-1x2-fill"></i>
        </span>


        <div class="medtrack-header__context-copy">

            <small
                class="medtrack-header__context-label"
            >
                <?= htmlspecialchars(
                    $contextLabel !== ''
                        ? $contextLabel
                        : 'MedTrack',
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </small>


            <strong
                class="medtrack-header__context-name"
                title="<?= htmlspecialchars(
                    $contextDisplayName,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>"
            >
                <?= htmlspecialchars(
                    $contextDisplayName,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </strong>

        </div>

    </div>


    <!--
    |--------------------------------------------------------------------------
    | Actions
    |--------------------------------------------------------------------------
    -->

    <nav
        class="medtrack-header__actions"
        aria-label="Actions utilisateur"
    >

        <ul
            class="medtrack-header__actions-list"
        >

            <!--
            |--------------------------------------------------------------------------
            | Notifications
            |--------------------------------------------------------------------------
            -->

            <li class="dropdown">

                <button
                    type="button"
                    class="medtrack-header__action-button
                           medtrack-header__notification-button"
                    data-bs-toggle="dropdown"
                    data-bs-auto-close="outside"
                    aria-label="Notifications"
                    aria-expanded="false"
                >

                    <i class="bi bi-bell"></i>

                    <span
                        class="medtrack-header__notification-dot"
                        aria-hidden="true"
                    ></span>

                </button>


                <div
                    class="dropdown-menu
                           dropdown-menu-end
                           medtrack-dropdown
                           medtrack-notifications-menu"
                >

                    <div
                        class="medtrack-dropdown__header"
                    >

                        <div>

                            <span
                                class="medtrack-dropdown__eyebrow"
                            >
                                Centre
                            </span>

                            <h6>
                                Notifications
                            </h6>

                        </div>


                        <span
                            class="medtrack-dropdown__header-icon"
                        >
                            <i class="bi bi-bell"></i>
                        </span>

                    </div>


                    <div
                        class="medtrack-dropdown__divider"
                    ></div>


                    <div
                        class="medtrack-notifications-menu__empty"
                    >

                        <span
                            class="medtrack-notifications-menu__empty-icon"
                        >
                            <i class="bi bi-check2-circle"></i>
                        </span>


                        <strong>
                            Tout est à jour
                        </strong>


                        <p>
                            Aucune nouvelle notification
                            pour le moment.
                        </p>

                    </div>

                </div>

            </li>


            <!--
            |--------------------------------------------------------------------------
            | Mon compte
            |--------------------------------------------------------------------------
            -->

            <li class="dropdown">

                <button
                    type="button"
                    class="medtrack-account-trigger"
                    data-bs-toggle="dropdown"
                    data-bs-auto-close="outside"
                    aria-label="Mon compte"
                    aria-expanded="false"
                >

                    <span
                        class="medtrack-header__avatar"
                    >
                        <?= htmlspecialchars(
                            $initials,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </span>


                    <span
                        class="medtrack-account-trigger__copy
                               d-none
                               d-md-flex"
                    >

                        <small>
                            Mon compte
                        </small>

                        <strong>
                            <?= htmlspecialchars(
                                $displayName !== ''
                                    ? $displayName
                                    : 'Utilisateur',
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </strong>

                    </span>


                    <i
                        class="bi
                               bi-chevron-down
                               medtrack-account-trigger__chevron
                               d-none
                               d-md-inline"
                    ></i>

                </button>


                <div
                    class="dropdown-menu
                           dropdown-menu-end
                           medtrack-dropdown
                           medtrack-profile-menu"
                >

                    <!-- Profil -->

                    <div
                        class="medtrack-profile-menu__identity"
                    >

                        <span
                            class="medtrack-profile-menu__avatar"
                        >
                            <?= htmlspecialchars(
                                $initials,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </span>


                        <div>

                            <h6>
                                <?= htmlspecialchars(
                                    $displayName !== ''
                                        ? $displayName
                                        : 'Utilisateur',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </h6>


                            <span
                                class="medtrack-profile-menu__role"
                            >
                                <?= htmlspecialchars(
                                    $roleName,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </span>

                        </div>

                    </div>


                    <?php if (
                        $organizationName !== ''
                    ): ?>

                        <div
                            class="medtrack-profile-menu__organization"
                        >
                            <i class="bi bi-building"></i>

                            <span>
                                <?= htmlspecialchars(
                                    $organizationName,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </span>
                        </div>

                    <?php endif; ?>


                    <div
                        class="medtrack-dropdown__divider"
                    ></div>


                    <!-- Mon profil -->

                    <a
                        href="#"
                        class="medtrack-profile-menu__item"
                    >

                        <span
                            class="medtrack-profile-menu__item-icon"
                        >
                            <i class="bi bi-person"></i>
                        </span>


                        <span
                            class="medtrack-profile-menu__item-copy"
                        >
                            <strong>
                                Mon profil
                            </strong>

                            <small>
                                Informations personnelles
                            </small>
                        </span>


                        <i
                            class="bi bi-chevron-right"
                        ></i>

                    </a>


                    <!-- Contexte -->

                    <div
                        class="medtrack-profile-menu__context"
                    >

                        <span
                            class="medtrack-profile-menu__item-icon"
                        >
                            <i class="bi bi-diagram-3"></i>
                        </span>


                        <span
                            class="medtrack-profile-menu__item-copy"
                        >

                            <small>
                                Contexte actif
                            </small>

                            <strong
                                title="<?= htmlspecialchars(
                                    $contextDisplayName,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>"
                            >
                                <?= htmlspecialchars(
                                    $contextDisplayName,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </strong>

                        </span>

                    </div>


                    <div
                        class="medtrack-dropdown__divider"
                    ></div>


                    <!-- Logout -->

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
                            class="medtrack-profile-menu__logout"
                        >

                            <span
                                class="medtrack-profile-menu__logout-icon"
                            >
                                <i class="bi bi-box-arrow-right"></i>
                            </span>


                            <span>
                                Déconnexion
                            </span>

                        </button>

                    </form>

                </div>

            </li>

        </ul>

    </nav>

</header>