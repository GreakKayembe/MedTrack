<?php

declare(strict_types=1);

/**
 * @var string $token
 * @var bool $tokenValid
 */

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
                    Protection du compte
                </div>

                <h1>
                    Sécurisez votre
                    <span>nouvel accès.</span>
                </h1>

                <p class="mt-auth-description">
                    Créez un nouveau mot de passe robuste pour
                    protéger votre espace MedTrack et les données
                    auxquelles votre compte donne accès.
                </p>

                <div class="mt-recovery-steps">

                    <div class="mt-recovery-step">
                        <span>
                            <i class="bi bi-shield-check"></i>
                        </span>

                        <div>
                            <strong>Lien sécurisé</strong>
                            <small>
                                Le lien de réinitialisation est temporaire
                                et utilisable une seule fois.
                            </small>
                        </div>
                    </div>

                    <div class="mt-recovery-step">
                        <span>
                            <i class="bi bi-key"></i>
                        </span>

                        <div>
                            <strong>Mot de passe robuste</strong>
                            <small>
                                Utilisez une combinaison difficile
                                à deviner.
                            </small>
                        </div>
                    </div>

                    <div class="mt-recovery-step">
                        <span>
                            <i class="bi bi-person-check"></i>
                        </span>

                        <div>
                            <strong>Retour sécurisé</strong>
                            <small>
                                Après la modification, reconnectez-vous
                                avec votre nouveau mot de passe.
                            </small>
                        </div>
                    </div>

                </div>

            </div>

            <div class="mt-auth-brand-footer">
                <i class="bi bi-shield-lock"></i>

                <span>Protection MedTrack</span>

                <span class="mt-dot"></span>

                <span>Réinitialisation à usage unique</span>
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


            <?php if (!$tokenValid): ?>

                <div class="mt-invalid-token">

                    <div class="mt-invalid-token-icon">
                        <i class="bi bi-link-45deg"></i>
                    </div>

                    <span class="mt-section-label">
                        LIEN INVALIDE
                    </span>

                    <h2>
                        Ce lien n'est plus valide
                    </h2>

                    <p>
                        Ce lien de réinitialisation est invalide,
                        expiré, déjà utilisé ou a été révoqué.
                    </p>

                    <a
                        href="/forgot-password"
                        class="mt-primary-link-button"
                    >
                        Demander un nouveau lien

                        <i class="bi bi-arrow-right"></i>
                    </a>

                </div>


            <?php else: ?>

                <div class="mt-recovery-icon">
                    <i class="bi bi-shield-lock-fill"></i>
                </div>

                <div class="mt-login-heading">

                    <span class="mt-section-label">
                        NOUVEAU MOT DE PASSE
                    </span>

                    <h2>
                        Créez votre nouveau mot de passe
                    </h2>

                    <p>
                        Choisissez un mot de passe sécurisé
                        que vous n'utilisez pas sur un autre service.
                    </p>

                </div>


                <div
                    id="reset-alert"
                    class="mt-login-alert d-none"
                    role="alert"
                    aria-live="polite"
                >
                    <i class="bi bi-exclamation-circle-fill"></i>

                    <div>
                        <strong>
                            Réinitialisation impossible
                        </strong>

                        <span id="reset-alert-message"></span>
                    </div>
                </div>


                <div
                    id="reset-success"
                    class="mt-recovery-success d-none"
                    role="status"
                    aria-live="polite"
                >
                    <div class="mt-recovery-success-icon">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>

                    <div>
                        <strong>
                            Mot de passe modifié
                        </strong>

                        <p>
                            Votre nouveau mot de passe a été
                            enregistré. Redirection vers la
                            connexion...
                        </p>
                    </div>
                </div>


                <form
                    id="reset-password-form"
                    novalidate
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


                    <!-- Nouveau mot de passe -->

                    <div class="mt-form-group">

                        <label for="password">
                            Nouveau mot de passe
                        </label>

                        <div class="mt-input-wrapper">

                            <span class="mt-input-icon">
                                <i class="bi bi-lock"></i>
                            </span>

                            <input
                                type="password"
                                name="password"
                                id="password"
                                placeholder="Votre nouveau mot de passe"
                                autocomplete="new-password"
                                required
                            >

                            <button
                                type="button"
                                class="mt-password-toggle"
                                data-password-toggle="password"
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


                    <!-- Robustesse -->

                    <div
                        class="mt-password-strength"
                        id="password-strength"
                    >

                        <div class="mt-strength-header">

                            <span>
                                Robustesse
                            </span>

                            <strong id="strength-label">
                                Non évaluée
                            </strong>

                        </div>

                        <div class="mt-strength-bars">

                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>

                        </div>

                    </div>


                    <!-- Règles -->

                    <div class="mt-password-rules">

                        <div
                            class="mt-password-rule"
                            data-password-rule="length"
                        >
                            <i class="bi bi-circle"></i>
                            12 caractères minimum
                        </div>

                        <div
                            class="mt-password-rule"
                            data-password-rule="uppercase"
                        >
                            <i class="bi bi-circle"></i>
                            Une lettre majuscule
                        </div>

                        <div
                            class="mt-password-rule"
                            data-password-rule="lowercase"
                        >
                            <i class="bi bi-circle"></i>
                            Une lettre minuscule
                        </div>

                        <div
                            class="mt-password-rule"
                            data-password-rule="number"
                        >
                            <i class="bi bi-circle"></i>
                            Un chiffre
                        </div>

                        <div
                            class="mt-password-rule"
                            data-password-rule="special"
                        >
                            <i class="bi bi-circle"></i>
                            Un caractère spécial
                        </div>

                    </div>


                    <!-- Confirmation -->

                    <div class="mt-form-group">

                        <label for="password_confirmation">
                            Confirmer le mot de passe
                        </label>

                        <div class="mt-input-wrapper">

                            <span class="mt-input-icon">
                                <i class="bi bi-shield-check"></i>
                            </span>

                            <input
                                type="password"
                                name="password_confirmation"
                                id="password_confirmation"
                                placeholder="Confirmez votre mot de passe"
                                autocomplete="new-password"
                                required
                            >

                            <button
                                type="button"
                                class="mt-password-toggle"
                                data-password-toggle="password_confirmation"
                                aria-label="Afficher le mot de passe"
                            >
                                <i class="bi bi-eye"></i>
                            </button>

                        </div>

                        <small
                            class="mt-field-error"
                            data-error-for="password_confirmation"
                        ></small>

                    </div>


                    <button
                        type="submit"
                        id="reset-submit"
                        class="mt-login-button"
                    >

                        <span class="mt-button-default">
                            Enregistrer le nouveau mot de passe
                            <i class="bi bi-arrow-right"></i>
                        </span>

                        <span class="mt-button-loading d-none">
                            <span class="mt-spinner"></span>
                            Sécurisation...
                        </span>

                        <span class="mt-button-success d-none">
                            <i class="bi bi-check-circle-fill"></i>
                            Mot de passe modifié
                        </span>

                    </button>

                </form>

            <?php endif; ?>


            <footer class="mt-login-footer">
                © <?= date('Y') ?> MedTrack · Tous droits réservés · Groupe SNADARPE
            </footer>

        </div>

    </section>

</div>

<?php if ($tokenValid): ?>
    <script src="/assets/js/medtrack-reset-password.js"></script>
<?php endif; ?>