<?php

declare(strict_types=1);

/** @var string $csrfToken */

?>

<div class="mt-auth-shell">

    <!-- =========================
         LEFT / BRAND EXPERIENCE
    ========================== -->
    <section class="mt-auth-brand-panel">

        <div class="mt-auth-grid"></div>

        <div class="mt-auth-glow mt-auth-glow-1"></div>
        <div class="mt-auth-glow mt-auth-glow-2"></div>

        <div class="mt-auth-brand-content">

            <a href="/login" class="mt-brand">
                <div class="mt-brand-symbol">
                    <img
                        src="/assets/img/logo.png"
                        alt="MedTrack"
                        class="mt-brand-logo"
                    >
                </div>

                <div>
                    <strong>MedTrack</strong>
                    <small>Health Training Platform</small>
                </div>
            </a>

            <div class="mt-auth-illustration" aria-hidden="true">
                <img
                    src="/assets/img/illustration_login.png"
                    alt=""
                    class="mt-auth-illustration__image"
                >
            </div>

            <div class="mt-auth-presentation">

                <div class="mt-auth-badge">
                    <span class="mt-auth-status-dot"></span>
                    Plateforme de gestion des stages en santé
                </div>

                <h1>
                    Un parcours clinique
                    <span>connecté.</span>
                </h1>

                <p class="mt-auth-description">
                    MedTrack connecte les acteurs de la formation
                    médicale et infirmière autour d'un parcours
                    de stage structuré, traçable et sécurisé.
                </p>

                <div class="mt-ecosystem">

                    <div class="mt-ecosystem-item">
                        <div class="mt-ecosystem-icon">
                            <i class="bi bi-mortarboard-fill"></i>
                        </div>

                        <div>
                            <strong>Universités</strong>
                            <small>Formation & affectations</small>
                        </div>
                    </div>

                    <div class="mt-ecosystem-line"></div>

                    <div class="mt-ecosystem-item">
                        <div class="mt-ecosystem-icon">
                            <i class="bi bi-person-vcard-fill"></i>
                        </div>

                        <div>
                            <strong>Étudiants</strong>
                            <small>Parcours & compétences</small>
                        </div>
                    </div>

                    <div class="mt-ecosystem-line"></div>

                    <div class="mt-ecosystem-item">
                        <div class="mt-ecosystem-icon">
                            <i class="bi bi-hospital-fill"></i>
                        </div>

                        <div>
                            <strong>Hôpitaux</strong>
                            <small>Encadrement & évaluation</small>
                        </div>
                    </div>

                </div>

                <div class="mt-institution-row">

                    <span>
                        <i class="bi bi-patch-check-fill"></i>
                        Ordres professionnels
                    </span>

                    <span>
                        <i class="bi bi-building-fill"></i>
                        Ministères
                    </span>

                </div>

            </div>

            <div class="mt-auth-brand-footer">
                <i class="bi bi-shield-check"></i>
                <span>Infrastructure sécurisée</span>

                <span class="mt-dot"></span>

                <span>Accès institutionnel</span>
            </div>

        </div>
    </section>


    <!-- =========================
         RIGHT / LOGIN
    ========================== -->
    <section class="mt-auth-form-panel">

        <div class="mt-mobile-brand">
            <div class="mt-brand-symbol">
                <img
                    src="/assets/img/logo.png"
                    alt="MedTrack"
                    class="mt-brand-logo"
                >
            </div>

            <strong>MedTrack</strong>
        </div>


        <div class="mt-login-container">

            <div class="mt-login-heading">

                <span class="mt-section-label">
                    ESPACE SÉCURISÉ
                </span>

                <h2>Bienvenue sur MedTrack</h2>

                <p>
                    Connectez-vous pour accéder à votre espace
                    et poursuivre votre parcours.
                </p>

            </div>


            <div
                id="login-alert"
                class="mt-login-alert d-none"
                role="alert"
                aria-live="polite"
            >
                <i class="bi bi-exclamation-circle-fill"></i>

                <div>
                    <strong>Connexion impossible</strong>
                    <span id="login-alert-message"></span>
                </div>
            </div>

            <form id="login-form" novalidate>

                <input
                    type="hidden"
                    name="_token"
                    value="<?= htmlspecialchars(
                        $csrfToken,
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                >



                <!-- LOGIN -->
                <div class="mt-form-group">

                    <label for="login">
                        Email ou numéro de téléphone
                    </label>

                    <div class="mt-input-wrapper">

                        <span class="mt-input-icon">
                            <i class="bi bi-person"></i>
                        </span>

                        <input
                            type="text"
                            name="login"
                            id="login"
                            placeholder="exemple@universite.cd"
                            autocomplete="username"
                            required
                        >

                        <span class="mt-input-validation">
                            <i class="bi bi-check-circle-fill"></i>
                        </span>

                    </div>

                    <small
                        class="mt-field-error"
                        data-error-for="login"
                    ></small>

                </div>


                <!-- PASSWORD -->
                <div class="mt-form-group">

                    <div class="mt-password-header">

                        <label for="password">
                            Mot de passe
                        </label>

                        <a
                            href="/forgot-password"
                            class="mt-forgot-link"
                        >
                            Mot de passe oublié ?
                        </a>

                    </div>

                    <div class="mt-input-wrapper">

                        <span class="mt-input-icon">
                            <i class="bi bi-lock"></i>
                        </span>

                        <input
                            type="password"
                            name="password"
                            id="password"
                            placeholder="Votre mot de passe"
                            autocomplete="current-password"
                            required
                        >

                        <button
                            type="button"
                            id="toggle-password"
                            class="mt-password-toggle"
                            aria-label="Afficher le mot de passe"
                        >
                            <i class="bi bi-eye"></i>
                        </button>

                    </div>

                    <small
                        class="mt-field-error"
                        data-error-for="password"
                    ></small>

                </div>


                <!-- OPTIONS -->
                <div class="mt-login-options">

                    <label class="mt-remember">

                        <input
                            type="checkbox"
                            name="remember"
                            value="1"
                        >

                        <span class="mt-checkbox"></span>

                        <span>
                            Se souvenir de moi
                        </span>

                    </label>

                </div>


                <!-- SUBMIT -->
                <button
                    type="submit"
                    id="login-submit"
                    class="mt-login-button"
                >

                    <span class="mt-button-default">
                        <span>Se connecter</span>
                        <i class="bi bi-arrow-right"></i>
                    </span>

                    <span class="mt-button-loading d-none">
                        <span class="mt-spinner"></span>
                        Vérification...
                    </span>

                    <span class="mt-button-success d-none">
                        <i class="bi bi-check-circle-fill"></i>
                        Connexion réussie
                    </span>

                </button>

            </form>


            <div class="mt-security-info">

                <div class="mt-security-icon">
                    <i class="bi bi-lock-fill"></i>
                </div>

                <div>
                    <strong>Connexion sécurisée</strong>

                    <p>
                        Vos identifiants sont transmis
                        de manière sécurisée.
                    </p>
                </div>

            </div>


            <div class="mt-login-help">

                <span>Vous rencontrez un problème ?</span>

                <a href="#">
                    <i class="bi bi-headset"></i>
                    Assistance MedTrack
                </a>

            </div>


            <footer class="mt-login-footer">
                © <?= date('Y') ?> MedTrack · Tous droits réservés · Groupe SNADARPE
            </footer>

        </div>

    </section>

</div>