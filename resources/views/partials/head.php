<meta charset="utf-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1"
>

<title>
    <?= htmlspecialchars(
        $pageTitle ?? 'MedTrack',
        ENT_QUOTES,
        'UTF-8'
    ) ?> | MedTrack
</title>

<meta
    name="description"
    content="MedTrack - Plateforme de gestion des stages médicaux et paramédicaux"
>

<meta
    name="theme-color"
    content="#6f2dbd"
>

<link
    rel="icon"
    type="image/png"
    href="/assets/img/logo.png"
>

<link
    rel="apple-touch-icon"
    href="/assets/img/logo.png"
>

<link
    rel="stylesheet"
    href="/assets/vendor/sweetalert2/sweetalert2.min.css"
>

<link
    href="/assets/vendor/bootstrap/css/bootstrap.min.css"
    rel="stylesheet"
>

<link
    href="/assets/vendor/bootstrap-icons/bootstrap-icons.css"
    rel="stylesheet"
>

<link
    href="/assets/css/style.css"
    rel="stylesheet"
>

<link
    href="/assets/css/medtrack-dashboard.css"
    rel="stylesheet"
>

<style>
    :root {
        --mt-shell-purple: #6f2dbd;
        --mt-shell-purple-dark: #4c1d95;
        --mt-shell-purple-soft: #f5effd;
        --mt-shell-gold: #d4a017;
        --mt-shell-gold-light: #f2ca52;
        --mt-shell-gold-soft: #fff8df;
        --mt-shell-text: #271a38;
        --mt-shell-muted: #756b83;
        --mt-shell-border: rgba(111, 45, 189, 0.12);
    }

    body {
        background:
            linear-gradient(
                180deg,
                #fcfaff 0%,
                #f8f5fc 100%
            );
        color: var(--mt-shell-text);
    }

    /*
    |--------------------------------------------------------------------------
    | Header
    |--------------------------------------------------------------------------
    */

    .medtrack-header {
        border-bottom:
            1px solid var(--mt-shell-border);
        background:
            rgba(255, 255, 255, 0.96);
        box-shadow:
            0 8px 28px rgba(76, 29, 149, 0.08);
        backdrop-filter: blur(16px);
    }

    .medtrack-header__brand {
        min-width: 280px;
    }

    .medtrack-header .logo {
        color: inherit;
        text-decoration: none;
    }

    .medtrack-header__logo-wrap {
        display: flex;
        width: 42px;
        height: 42px;
        flex: 0 0 42px;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border:
            1px solid rgba(111, 45, 189, 0.12);
        border-radius: 13px;
        background:
            linear-gradient(
                135deg,
                #ffffff,
                var(--mt-shell-purple-soft)
            );
        box-shadow:
            0 8px 20px rgba(76, 29, 149, 0.10);
    }

    .medtrack-header__logo {
        display: block;
        width: 86%;
        height: 86%;
        object-fit: contain;
    }

    .medtrack-header__brand-name {
        color: var(--mt-shell-purple-dark);
        font-size: 1rem;
        letter-spacing: -0.02em;
    }

    .medtrack-header__brand-tagline {
        margin-top: 2px;
        color: var(--mt-shell-gold);
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.03em;
    }

    .medtrack-header__toggle {
        display: inline-flex;
        width: 39px;
        height: 39px;
        margin-left: 10px;
        align-items: center;
        justify-content: center;
        border: 0;
        border-radius: 12px;
        background: var(--mt-shell-purple-soft);
        color: var(--mt-shell-purple);
        font-size: 1.45rem;
        transition:
            transform 0.2s ease,
            background 0.2s ease;
    }

    .medtrack-header__toggle:hover {
        transform: translateY(-1px);
        background: #eee3fa;
    }

    .medtrack-header__context {
        gap: 10px;
        padding:
            7px 13px;
        border:
            1px solid var(--mt-shell-border);
        border-radius: 14px;
        background: #fbf9fe;
    }

    .medtrack-header__context-icon {
        display: flex;
        width: 34px;
        height: 34px;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background:
            linear-gradient(
                135deg,
                var(--mt-shell-purple-soft),
                var(--mt-shell-gold-soft)
            );
        color: var(--mt-shell-purple);
    }

    .medtrack-header__context-label {
        color: var(--mt-shell-muted);
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .medtrack-header__context-name {
        display: block;
        max-width: 260px;
        overflow: hidden;
        color: var(--mt-shell-text);
        font-size: 0.78rem;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .medtrack-header__icon-button {
        display: flex !important;
        width: 40px;
        height: 40px;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        color: var(--mt-shell-purple) !important;
        transition:
            background 0.2s ease,
            transform 0.2s ease;
    }

    .medtrack-header__icon-button:hover {
        transform: translateY(-1px);
        background: var(--mt-shell-purple-soft);
    }

    .medtrack-header__profile {
        padding:
            5px 7px !important;
        border-radius: 14px;
        transition: background 0.2s ease;
    }

    .medtrack-header__profile:hover {
        background: #fbf9fe;
    }

    .medtrack-header__avatar,
    .medtrack-profile-menu__avatar {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background:
            linear-gradient(
                135deg,
                var(--mt-shell-purple),
                var(--mt-shell-purple-dark)
            );
        color: #ffffff;
        font-weight: 800;
        box-shadow:
            0 8px 20px rgba(76, 29, 149, 0.20);
    }

    .medtrack-header__avatar {
        width: 38px;
        height: 38px;
        flex: 0 0 38px;
        font-size: 0.72rem;
    }

    .medtrack-header__profile-copy strong {
        max-width: 180px;
        overflow: hidden;
        color: var(--mt-shell-text);
        font-size: 0.77rem;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .medtrack-header__profile-copy small {
        max-width: 180px;
        margin-top: 1px;
        overflow: hidden;
        color: var(--mt-shell-muted);
        font-size: 0.62rem;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .medtrack-dropdown {
        overflow: hidden;
        border:
            1px solid var(--mt-shell-border);
        border-radius: 17px;
        box-shadow:
            0 18px 45px rgba(76, 29, 149, 0.14);
    }

    .medtrack-profile-menu__avatar {
        width: 48px;
        height: 48px;
        margin:
            0 auto 10px;
        font-size: 0.82rem;
    }

    .medtrack-dropdown .dropdown-item {
        color: var(--mt-shell-text);
    }

    .medtrack-dropdown .dropdown-item:hover {
        background: var(--mt-shell-purple-soft);
        color: var(--mt-shell-purple-dark);
    }

    .medtrack-profile-menu__logout {
        color: #b42318 !important;
    }

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    */

    .sidebar {
        border-right:
            1px solid var(--mt-shell-border);
        background:
            linear-gradient(
                180deg,
                #ffffff 0%,
                #fbf9fe 100%
            );
        box-shadow:
            8px 0 30px rgba(76, 29, 149, 0.06);
    }

    .sidebar-brand {
        padding:
            18px 16px 10px;
    }

    .sidebar-brand__link {
        display: flex;
        align-items: center;
        gap: 11px;
        padding:
            10px 11px;
        border:
            1px solid var(--mt-shell-border);
        border-radius: 17px;
        background:
            linear-gradient(
                135deg,
                #ffffff,
                #f9f4ff
            );
        color: inherit;
        text-decoration: none;
        box-shadow:
            0 10px 28px rgba(76, 29, 149, 0.08);
    }

    .sidebar-brand__logo-wrap {
        display: flex;
        width: 46px;
        height: 46px;
        flex: 0 0 46px;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border-radius: 14px;
        background:
            linear-gradient(
                145deg,
                var(--mt-shell-purple-soft),
                var(--mt-shell-gold-soft)
            );
    }

    .sidebar-brand__logo {
        width: 88%;
        height: 88%;
        object-fit: contain;
    }

    .sidebar-brand__content {
        display: flex;
        min-width: 0;
        flex-direction: column;
    }

    .sidebar-brand__content strong {
        color: var(--mt-shell-purple-dark);
        font-size: 0.93rem;
        letter-spacing: -0.02em;
    }

    .sidebar-brand__content small {
        margin-top: 2px;
        overflow: hidden;
        color: var(--mt-shell-gold);
        font-size: 0.60rem;
        font-weight: 700;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .sidebar-nav {
        padding:
            4px 12px 26px;
    }

    .sidebar-nav .nav-heading {
        margin:
            19px 10px 7px;
        color: var(--mt-shell-gold-dark, #a97800);
        font-size: 0.64rem;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .sidebar-nav .nav-link {
        min-height: 43px;
        margin:
            3px 0;
        padding:
            10px 12px;
        border-radius: 13px;
        color: #5f5669;
        font-size: 0.82rem;
        font-weight: 600;
        transition:
            color 0.2s ease,
            background 0.2s ease,
            transform 0.2s ease;
    }

    .sidebar-nav .nav-link i {
        color: #8c7b9b;
        transition: color 0.2s ease;
    }

    .sidebar-nav .nav-link:hover {
        transform: translateX(2px);
        background: var(--mt-shell-purple-soft);
        color: var(--mt-shell-purple-dark);
    }

    .sidebar-nav .nav-link:hover i {
        color: var(--mt-shell-purple);
    }

    .sidebar-nav .nav-link:not(.collapsed) {
        position: relative;
        background:
            linear-gradient(
                135deg,
                var(--mt-shell-purple),
                var(--mt-shell-purple-dark)
            );
        color: #ffffff;
        box-shadow:
            0 10px 24px rgba(76, 29, 149, 0.18);
    }

    .sidebar-nav .nav-link:not(.collapsed)::after {
        content: "";
        position: absolute;
        top: 10px;
        right: 8px;
        width: 4px;
        height: calc(100% - 20px);
        border-radius: 999px;
        background: var(--mt-shell-gold-light);
    }

    .sidebar-nav .nav-link:not(.collapsed) i {
        color: var(--mt-shell-gold-light);
    }

    .sidebar-nav .nav-link.disabled {
        opacity: 0.50;
        cursor: not-allowed;
    }

    /*
    |--------------------------------------------------------------------------
    | Footer
    |--------------------------------------------------------------------------
    */

    .footer.medtrack-footer {
        border-top:
            1px solid var(--mt-shell-border);
        background: rgba(255, 255, 255, 0.82);
        color: var(--mt-shell-muted);
    }

    .medtrack-footer__brand {
        color: var(--mt-shell-purple-dark);
        font-weight: 800;
    }

    .medtrack-footer__accent {
        color: var(--mt-shell-gold);
        font-weight: 800;
    }

    .medtrack-footer__credits {
        margin-top: 4px;
        color: var(--mt-shell-muted);
    }

    @media (max-width: 1199.98px) {
        .medtrack-header__brand {
            min-width: auto;
        }

        .medtrack-header__context-name {
            max-width: 180px;
        }
    }

    @media (max-width: 767.98px) {
        .medtrack-header__logo-wrap {
            width: 38px;
            height: 38px;
            flex-basis: 38px;
        }

        .sidebar-brand {
            padding-top: 12px;
        }
    }
</style>