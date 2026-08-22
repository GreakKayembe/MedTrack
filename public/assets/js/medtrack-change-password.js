'use strict';

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById(
        'change-password-form'
    );

    if (!form) {
        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Elements
    |--------------------------------------------------------------------------
    */

    const currentPassword = document.getElementById(
        'current_password'
    );

    const newPassword = document.getElementById(
        'new_password'
    );

    const passwordConfirmation = document.getElementById(
        'password_confirmation'
    );

    const submitButton = document.getElementById(
        'change-password-submit'
    );

    const alertBox = document.getElementById(
        'change-password-alert'
    );

    const alertMessage = document.getElementById(
        'change-password-alert-message'
    );

    const successBox = document.getElementById(
        'change-password-success'
    );

    const strengthLabel = document.getElementById(
        'strength-label'
    );

    const strengthContainer = document.getElementById(
        'password-strength'
    );

    const defaultState = submitButton?.querySelector(
        '.mt-button-default'
    );

    const loadingState = submitButton?.querySelector(
        '.mt-button-loading'
    );

    const successState = submitButton?.querySelector(
        '.mt-button-success'
    );


    /*
    |--------------------------------------------------------------------------
    | Password rules
    |--------------------------------------------------------------------------
    */

    const passwordRules = {
        length: (value) => value.length >= 12,

        uppercase: (value) =>
            /[A-Z]/.test(value),

        lowercase: (value) =>
            /[a-z]/.test(value),

        number: (value) =>
            /[0-9]/.test(value),

        special: (value) =>
            /[^A-Za-z0-9]/.test(value),
    };


    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    const getFieldError = (fieldName) => {
        return form.querySelector(
            `[data-error-for="${fieldName}"]`
        );
    };


    const clearFieldError = (fieldName) => {
        const error = getFieldError(fieldName);

        if (error) {
            error.textContent = '';
        }
    };


    const setFieldError = (
        fieldName,
        message
    ) => {
        const error = getFieldError(fieldName);

        if (error) {
            error.textContent = message;
        }
    };


    const clearErrors = () => {
        form.querySelectorAll(
            '.mt-field-error'
        ).forEach((element) => {
            element.textContent = '';
        });

        hideAlert();
    };


    const showAlert = (message) => {
        if (!alertBox || !alertMessage) {
            return;
        }

        alertMessage.textContent = message;

        alertBox.classList.remove(
            'd-none'
        );
    };


    const hideAlert = () => {
        if (!alertBox) {
            return;
        }

        alertBox.classList.add(
            'd-none'
        );

        if (alertMessage) {
            alertMessage.textContent = '';
        }
    };


    const showSuccess = () => {
        if (!successBox) {
            return;
        }

        successBox.classList.remove(
            'd-none'
        );
    };


    const setLoading = (loading) => {
        if (!submitButton) {
            return;
        }

        submitButton.disabled = loading;

        if (defaultState) {
            defaultState.classList.toggle(
                'd-none',
                loading
            );
        }

        if (loadingState) {
            loadingState.classList.toggle(
                'd-none',
                !loading
            );
        }

        if (successState) {
            successState.classList.add(
                'd-none'
            );
        }
    };


    const setSuccessButton = () => {
        if (!submitButton) {
            return;
        }

        submitButton.disabled = true;

        if (defaultState) {
            defaultState.classList.add(
                'd-none'
            );
        }

        if (loadingState) {
            loadingState.classList.add(
                'd-none'
            );
        }

        if (successState) {
            successState.classList.remove(
                'd-none'
            );
        }
    };


    /*
    |--------------------------------------------------------------------------
    | Password visibility
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll(
        '[data-password-toggle]'
    ).forEach((button) => {
        button.addEventListener(
            'click',
            () => {
                const targetId =
                    button.dataset.passwordToggle;

                if (!targetId) {
                    return;
                }

                const input = document.getElementById(
                    targetId
                );

                if (!input) {
                    return;
                }

                const isHidden =
                    input.type === 'password';

                input.type = isHidden
                    ? 'text'
                    : 'password';

                const icon = button.querySelector(
                    'i'
                );

                if (icon) {
                    icon.classList.toggle(
                        'bi-eye',
                        !isHidden
                    );

                    icon.classList.toggle(
                        'bi-eye-slash',
                        isHidden
                    );
                }

                button.setAttribute(
                    'aria-label',
                    isHidden
                        ? 'Masquer le mot de passe'
                        : 'Afficher le mot de passe'
                );
            }
        );
    });


    /*
    |--------------------------------------------------------------------------
    | Password strength
    |--------------------------------------------------------------------------
    */

    const updatePasswordStrength = () => {
        if (!newPassword) {
            return;
        }

        const value = newPassword.value;

        let validRules = 0;

        Object.entries(
            passwordRules
        ).forEach(([ruleName, validator]) => {
            const valid = validator(value);

            if (valid) {
                validRules++;
            }

            const ruleElement = document.querySelector(
                `[data-password-rule="${ruleName}"]`
            );

            if (!ruleElement) {
                return;
            }

            ruleElement.classList.toggle(
                'is-valid',
                valid
            );

            const icon = ruleElement.querySelector(
                'i'
            );

            if (!icon) {
                return;
            }

            icon.classList.toggle(
                'bi-circle',
                !valid
            );

            icon.classList.toggle(
                'bi-check-circle-fill',
                valid
            );
        });


        /*
         * État visuel de robustesse.
         */

        if (strengthContainer) {
            strengthContainer.dataset.strength =
                String(validRules);
        }


        if (!strengthLabel) {
            return;
        }


        if (value === '') {
            strengthLabel.textContent =
                'Non évaluée';

            return;
        }


        if (validRules <= 2) {
            strengthLabel.textContent =
                'Faible';

            return;
        }


        if (validRules <= 4) {
            strengthLabel.textContent =
                'Moyenne';

            return;
        }


        strengthLabel.textContent =
            'Robuste';
    };


    newPassword?.addEventListener(
        'input',
        () => {
            clearFieldError(
                'new_password'
            );

            updatePasswordStrength();
        }
    );


    /*
    |--------------------------------------------------------------------------
    | Clear errors while typing
    |--------------------------------------------------------------------------
    */

    currentPassword?.addEventListener(
        'input',
        () => {
            clearFieldError(
                'current_password'
            );
        }
    );


    passwordConfirmation?.addEventListener(
        'input',
        () => {
            clearFieldError(
                'password_confirmation'
            );
        }
    );


    /*
    |--------------------------------------------------------------------------
    | Client validation
    |--------------------------------------------------------------------------
    */

    const validateForm = () => {
        let valid = true;

        const currentValue =
            currentPassword?.value ?? '';

        const newValue =
            newPassword?.value ?? '';

        const confirmationValue =
            passwordConfirmation?.value ?? '';


        if (currentValue === '') {
            setFieldError(
                'current_password',
                'Veuillez saisir votre mot de passe actuel.'
            );

            valid = false;
        }


        if (newValue === '') {
            setFieldError(
                'new_password',
                'Veuillez saisir votre nouveau mot de passe.'
            );

            valid = false;
        } else {
            const rulesValid = Object
                .values(passwordRules)
                .every(
                    (validator) =>
                        validator(newValue)
                );

            if (!rulesValid) {
                setFieldError(
                    'new_password',
                    'Le nouveau mot de passe ne respecte pas toutes les règles de sécurité.'
                );

                valid = false;
            }
        }


        if (confirmationValue === '') {
            setFieldError(
                'password_confirmation',
                'Veuillez confirmer votre nouveau mot de passe.'
            );

            valid = false;
        } else if (
            newValue !== confirmationValue
        ) {
            setFieldError(
                'password_confirmation',
                'La confirmation ne correspond pas au nouveau mot de passe.'
            );

            valid = false;
        }


        if (
            currentValue !== ''
            && newValue !== ''
            && currentValue === newValue
        ) {
            setFieldError(
                'new_password',
                'Le nouveau mot de passe doit être différent du mot de passe actuel.'
            );

            valid = false;
        }


        return valid;
    };


    /*
    |--------------------------------------------------------------------------
    | Submit
    |--------------------------------------------------------------------------
    */

    form.addEventListener(
        'submit',
        async (event) => {
            event.preventDefault();

            clearErrors();

            if (!validateForm()) {
                return;
            }

            setLoading(true);

            try {
                const response = await fetch(
                    '/change-password',
                    {
                        method: 'POST',

                        headers: {
                            'Accept':
                                'application/json',

                            'X-Requested-With':
                                'XMLHttpRequest',
                        },

                        body: new FormData(form),

                        credentials: 'same-origin',
                    }
                );


                let data;

                try {
                    data = await response.json();
                } catch {
                    throw new Error(
                        'Réponse invalide reçue du serveur.'
                    );
                }


                if (!response.ok) {
                    showAlert(
                        data.message
                        ?? 'Impossible de modifier le mot de passe.'
                    );

                    return;
                }


                if (data.status !== 'success') {
                    showAlert(
                        data.message
                        ?? 'Impossible de modifier le mot de passe.'
                    );

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | Success
                |--------------------------------------------------------------------------
                */

                hideAlert();

                showSuccess();

                setSuccessButton();

                currentPassword.value = '';
                newPassword.value = '';
                passwordConfirmation.value = '';

                updatePasswordStrength();


                const redirect =
                    typeof data.redirect === 'string'
                    && data.redirect !== ''
                        ? data.redirect
                        : '/';


                window.setTimeout(
                    () => {
                        window.location.href =
                            redirect;
                    },
                    1200
                );

            } catch (error) {
                const message =
                    error instanceof Error
                        ? error.message
                        : 'Une erreur inattendue est survenue.';

                showAlert(
                    message
                );

            } finally {
                /*
                 * Ne pas restaurer le bouton si
                 * l'opération a réussi.
                 */
                if (
                    successBox?.classList.contains(
                        'd-none'
                    )
                ) {
                    setLoading(false);
                }
            }
        }
    );


    /*
    |--------------------------------------------------------------------------
    | Initial state
    |--------------------------------------------------------------------------
    */

    updatePasswordStrength();
});