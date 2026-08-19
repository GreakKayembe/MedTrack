<?php

declare(strict_types=1);

/**
 * @var string $csrfToken
 */

$csrfToken =
    (string) (
        $csrfToken
        ?? ''
    );
?>

<div class="mt-auth-shell">

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
                    Sécurité du compte
                </div>

                <h1>
                    Récupérez votre accès
                    <span>en toute sécurité.</span>
                </h1>

                <p class="mt-auth-description">
                    MedTrack protège l'accès aux données académiques
                    et cliniques de chaque utilisateur. Un lien sécurisé
                    et temporaire vous permettra de créer un nouveau
                    mot de passe.
                </p>

                <div class="mt-recovery-steps">

                    <div class="mt-recovery-step">
                        <span>1</span>
                        <div>
                            <strong>Identifiez votre compte</strong>
                            <small>Renseignez votre adresse email.</small>
                        </div>
                    </div>

                    <div class="mt-recovery-step">
                        <span>2</span>
                        <div>
                            <strong>Recevez le lien sécurisé</strong>
                            <small>Le lien est temporaire et à usage unique.</small>
                        </div>
                    </div>

                    <div class="mt-recovery-step">
                        <span>3</span>
                        <div>
                            <strong>Créez un nouveau mot de passe</strong>
                            <small>Retrouvez ensuite votre espace MedTrack.</small>
                        </div>
                    </div>

                </div>

            </div>

            <div class="mt-auth-brand-footer">
                <i class="bi bi-shield-check"></i>
                <span>Réinitialisation sécurisée</span>
                <span class="mt-dot"></span>
                <span>Lien à usage unique</span>
            </div>

        </div>

    </section>

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

            <a href="/login" class="mt-back-link">
                <i class="bi bi-arrow-left"></i>
                Retour à la connexion
            </a>

            <div class="mt-recovery-icon">
                <i class="bi bi-key-fill"></i>
            </div>

            <div class="mt-login-heading">
                <span class="mt-section-label">RÉCUPÉRATION DU COMPTE</span>
                <h2>Mot de passe oublié ?</h2>
                <p>
                    Saisissez l'adresse email associée à votre compte.
                    Si elle correspond à un compte MedTrack actif,
                    vous recevrez les instructions de réinitialisation.
                </p>
            </div>

            <div
                id="forgot-alert"
                class="mt-login-alert d-none"
                role="alert"
                aria-live="polite"
            >
                <i class="bi bi-exclamation-circle-fill"></i>
                <div>
                    <strong>Demande impossible</strong>
                    <span id="forgot-alert-message"></span>
                </div>
            </div>

            <div
                id="forgot-success"
                class="mt-recovery-success d-none"
                role="status"
                aria-live="polite"
            >
                <div class="mt-recovery-success-icon">
                    <i class="bi bi-envelope-check-fill"></i>
                </div>

                <div>
                    <strong>Vérifiez votre messagerie</strong>
                    <p id="forgot-success-message">
                        Si un compte correspond à cette adresse,
                        les instructions ont été envoyées.
                    </p>

                    <a
                        href="#"
                        id="development-reset-link"
                        class="d-none"
                    >
                        Ouvrir le lien de test
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>

            <form id="forgot-password-form" novalidate>

                <input
                    type="hidden"
                    name="_token"
                    value="<?= htmlspecialchars(
                        $csrfToken,
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                >

                <div class="mt-form-group">
                    <label for="identifier">Adresse email</label>

                    <div class="mt-input-wrapper">
                        <span class="mt-input-icon">
                            <i class="bi bi-envelope"></i>
                        </span>

                        <input
                            type="email"
                            name="identifier"
                            id="identifier"
                            placeholder="exemple@universite.cd"
                            autocomplete="email"
                            required
                        >
                    </div>

                    <small
                        class="mt-field-error"
                        data-error-for="identifier"
                    ></small>
                </div>

                <button
                    type="submit"
                    id="forgot-submit"
                    class="mt-login-button"
                >
                    <span class="mt-button-default">
                        Envoyer les instructions
                        <i class="bi bi-arrow-right"></i>
                    </span>

                    <span class="mt-button-loading d-none">
                        <span class="mt-spinner"></span>
                        Vérification...
                    </span>
                </button>

            </form>

            <div class="mt-security-info">
                <div class="mt-security-icon">
                    <i class="bi bi-shield-lock-fill"></i>
                </div>

                <div>
                    <strong>Protection de votre identité</strong>
                    <p>
                        MedTrack ne révèle jamais publiquement
                        si une adresse possède ou non un compte.
                    </p>
                </div>
            </div>

            <footer class="mt-login-footer">
                © <?= date('Y') ?> MedTrack · Tous droits réservés · Groupe SNADARPE
            </footer>

        </div>

    </section>

</div>