<?php

declare(strict_types=1);

/** @var string $csrfToken */

?>
<header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
        <a
            href="/"
            class="logo d-flex align-items-center"
        >
            <span class="d-none d-lg-block">
                MedTrack
            </span>
        </a>

        <i class="bi bi-list toggle-sidebar-btn"></i>
    </div>

    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">

            <li class="nav-item dropdown">

                <a
                    class="nav-link nav-icon"
                    href="#"
                    data-bs-toggle="dropdown"
                >
                    <i class="bi bi-bell"></i>
                </a>

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">

                    <li class="dropdown-header">
                        Aucune nouvelle notification
                    </li>

                </ul>

            </li>

            <li class="nav-item dropdown pe-3">

                <a
                    class="nav-link nav-profile d-flex align-items-center pe-0"
                    href="#"
                    data-bs-toggle="dropdown"
                >
                    <i class="bi bi-person-circle fs-4"></i>

                    <span class="d-none d-md-block dropdown-toggle ps-2">
                        Utilisateur
                    </span>
                </a>

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">

                    <li class="dropdown-header">
                        <h6>Utilisateur MedTrack</h6>
                        <span>Compte</span>
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
                            <span>Mon profil</span>
                        </a>
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
                            class="dropdown-item"
                        >
                            <i class="bi bi-box-arrow-right"></i>
                            Déconnexion
                        </button>
                    </form>
                        
                    </li>

                </ul>

            </li>

        </ul>
    </nav>

</header>
