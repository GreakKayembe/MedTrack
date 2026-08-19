<?php

declare(strict_types=1);

/**
 * @var array $user
 * @var bool $mustChangePassword
 * @var string $csrfToken
 */

?>

<div class="mt-auth-shell">

    <section class="mt-auth-brand-panel">

        <div class="mt-auth-grid"></div>
        <div class="mt-auth-glow mt-auth-glow-1"></div>
        <div class="mt-auth-glow mt-auth-glow-2"></div>

        <div class="mt-auth-brand-content">

            <a href="/" class="mt-brand">
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
                    Protégez votre
                    <span>accès MedTrack.</span>
                </h1>

                <p class="mt-auth-description">
                    Utilisez un mot de passe robuste et unique
                    afin de protéger votre compte et les données
                    auxquelles vous avez accès.
                </p>


                <div class="mt-recovery-steps">

                    <div class="mt-recovery-step">

                        <span>
                            <i class="bi bi-person-lock"></i>
                        </span>

                        <div>
                            <strong>
                                Vérification sécurisée
                            </strong>

                            <small>
                                Votre mot de passe actuel est requis
                                avant toute modification.
                            </small>
                        </div>

                    </div>


                    <div class="mt-recovery-step">

                        <span>
                            <i class="bi bi-shield-check"></i>
                        </span>

                        <div>
                            <strong>
                                Mot de passe robuste
                            </strong>

                            <small>
                                Utilisez au minimum 12 caractères
                                avec plusieurs types de caractères.
                            </small>
                        </div>

                    </div>


                    <div class="mt-recovery-step">

                        <span>
                            <i class="bi bi-key"></i>
                        </span>

                        <div>
                            <strong>
                                Nouveau secret
                            </strong>

                            <small>
                                Votre nouveau mot de passe doit être
                                différent du mot de passe actuel.
                            </small>
                        </div>

                    </div>

                </div>

            </div>


            <div class="mt-auth-brand-footer">

                <i class="bi bi-shield-lock"></i>

                <span>
                    Protection MedTrack
                </span>

                <span class="mt-dot"></span>

                <span>
                    Accès sécurisé
                </span>

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


            <?php if (!$mustChangePassword): ?>

                <a
                    href="/"
                    class="mt-back-link"
                >
                    <i class="bi bi-arrow-left"></i>
                    Retour au tableau de bord
                </a>

            <?php endif; ?>


            <div class="mt-recovery-icon">
                <i class="bi bi-key-fill"></i>
            </div>


            <div class="mt-login-heading">

                <span class="mt-section-label">

                    <?php if ($mustChangePassword): ?>

                        CHANGEMENT OBLIGATOIRE

                    <?php else: ?>

                        SÉCURITÉ DU COMPTE

                    <?php endif; ?>

                </span>


                <h2>
                    Changer votre mot de passe
                </h2>


                <?php if ($mustChangePassword): ?>

                    <p>
                        Vous utilisez actuellement un mot de passe
                        temporaire. Définissez votre propre mot de
                        passe avant de continuer vers MedTrack.
                    </p>

                <?php else: ?>

                    <p>
                        Confirmez votre mot de passe actuel puis
                        choisissez un nouveau mot de passe sécurisé.
                    </p>

                <?php endif; ?>

            </div>


            <div
                id="change-password-alert"
                class="mt-login-alert d-none"
                role="alert"
                aria-live="polite"
            >

                <i class="bi bi-exclamation-circle-fill"></i>

                <div>

                    <strong>
                        Modification impossible
                    </strong>

                    <span
                        id="change-password-alert-message"
                    ></span>

                </div>

            </div>


            <div
                id="change-password-success"
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
                        enregistré avec succès. Redirection...
                    </p>

                </div>

            </div>


            <form
                id="change-password-form"
                novalidate
            >

                <!-- CSRF -->

                <input
                    type="hidden"
                    name="_token"
                    value="<?= htmlspecialchars(
                        $csrfToken,
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                >


                <!-- Mot de passe actuel -->

                <div class="mt-form-group">

                    <label for="current_password">
                        Mot de passe actuel
                    </label>

                    <div class="mt-input-wrapper">

                        <span class="mt-input-icon">
                            <i class="bi bi-lock"></i>
                        </span>

                        <input
                            type="password"
                            name="current_password"
                            id="current_password"
                            placeholder="Votre mot de passe actuel"
                            autocomplete="current-password"
                            required
                        >

                        <button
                            type="button"
                            class="mt-password-toggle"
                            data-password-toggle="current_password"
                            aria-label="Afficher le mot de passe"
                        >
                            <i class="bi bi-eye"></i>
                        </button>

                    </div>

                    <small
                        class="mt-field-error"
                        data-error-for="current_password"
                    ></small>

                </div>


                <!-- Nouveau mot de passe -->

                <div class="mt-form-group">

                    <label for="new_password">
                        Nouveau mot de passe
                    </label>

                    <div class="mt-input-wrapper">

                        <span class="mt-input-icon">
                            <i class="bi bi-shield-lock"></i>
                        </span>

                        <input
                            type="password"
                            name="new_password"
                            id="new_password"
                            placeholder="Votre nouveau mot de passe"
                            autocomplete="new-password"
                            required
                        >

                        <button
                            type="button"
                            class="mt-password-toggle"
                            data-password-toggle="new_password"
                            aria-label="Afficher le mot de passe"
                        >
                            <i class="bi bi-eye"></i>
                        </button>

                    </div>

                    <small
                        class="mt-field-error"
                        data-error-for="new_password"
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
                        Confirmer le nouveau mot de passe
                    </label>

                    <div class="mt-input-wrapper">

                        <span class="mt-input-icon">
                            <i class="bi bi-shield-check"></i>
                        </span>

                        <input
                            type="password"
                            name="password_confirmation"
                            id="password_confirmation"
                            placeholder="Confirmez le nouveau mot de passe"
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
                    id="change-password-submit"
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


            <footer class="mt-login-footer">
                © <?= date('Y') ?> MedTrack · Tous droits réservés · Groupe SNADARPE
            </footer>

        </div>

    </section>

</div>


<script src="/assets/js/medtrack-change-password.js"></script>