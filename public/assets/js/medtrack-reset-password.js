document.addEventListener('DOMContentLoaded', () => {

    const form =
        document.getElementById('reset-password-form');

    if (!form) {
        return;
    }

    const password =
        document.getElementById('password');

    const confirmation =
        document.getElementById('password_confirmation');

    const submit =
        document.getElementById('reset-submit');

    const defaultState =
        submit.querySelector('.mt-button-default');

    const loadingState =
        submit.querySelector('.mt-button-loading');

    const successState =
        submit.querySelector('.mt-button-success');

    const alertBox =
        document.getElementById('reset-alert');

    const alertMessage =
        document.getElementById('reset-alert-message');

    const successBox =
        document.getElementById('reset-success');

    const strengthLabel =
        document.getElementById('strength-label');

    const strengthBars =
        Array.from(
            document.querySelectorAll(
                '.mt-strength-bars span'
            )
        );


    /*
    |--------------------------------------------------------------------------
    | Password visibility
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll('[data-password-toggle]')
        .forEach(button => {

            button.addEventListener('click', () => {

                const targetId =
                    button.dataset.passwordToggle;

                const input =
                    document.getElementById(targetId);

                if (!input) {
                    return;
                }

                const show =
                    input.type === 'password';

                input.type =
                    show ? 'text' : 'password';

                const icon =
                    button.querySelector('i');

                if (icon) {

                    icon.className =
                        show
                            ? 'bi bi-eye-slash'
                            : 'bi bi-eye';
                }

                button.setAttribute(
                    'aria-label',
                    show
                        ? 'Masquer le mot de passe'
                        : 'Afficher le mot de passe'
                );

            });

        });


    /*
    |--------------------------------------------------------------------------
    | Password rules
    |--------------------------------------------------------------------------
    */

    const rules = {

        length: value =>
            value.length >= 12,

        uppercase: value =>
            /[A-Z]/.test(value),

        lowercase: value =>
            /[a-z]/.test(value),

        number: value =>
            /[0-9]/.test(value),

        special: value =>
            /[^a-zA-Z0-9]/.test(value)

    };


    password.addEventListener('input', () => {

        evaluatePassword();

        clearFieldError('password');

        hideError();

        validateConfirmation(false);

    });


    confirmation.addEventListener('input', () => {

        clearFieldError(
            'password_confirmation'
        );

        hideError();

        validateConfirmation(false);

    });


    function evaluatePassword() {

        const value =
            password.value;

        let score = 0;


        Object.entries(rules).forEach(
            ([name, validator]) => {

                const valid =
                    validator(value);

                const element =
                    document.querySelector(
                        `[data-password-rule="${name}"]`
                    );

                if (element) {

                    element.classList.toggle(
                        'mt-valid',
                        valid
                    );

                    const icon =
                        element.querySelector('i');

                    if (icon) {

                        icon.className =
                            valid
                                ? 'bi bi-check-circle-fill'
                                : 'bi bi-circle';
                    }
                }

                if (valid) {
                    score++;
                }

            }
        );


        /*
         * 5 règles → 4 niveaux visuels.
         */

        let visualScore = 0;

        if (score >= 1) {
            visualScore = 1;
        }

        if (score >= 3) {
            visualScore = 2;
        }

        if (score >= 4) {
            visualScore = 3;
        }

        if (score === 5) {
            visualScore = 4;
        }


        strengthBars.forEach(
            (bar, index) => {

                bar.classList.toggle(
                    'mt-active',
                    index < visualScore
                );

            }
        );


        if (value === '') {

            strengthLabel.textContent =
                'Non évaluée';

            return;
        }


        const labels = {
            1: 'Faible',
            2: 'Moyenne',
            3: 'Bonne',
            4: 'Robuste'
        };


        strengthLabel.textContent =
            labels[visualScore] ?? 'Faible';

    }


    /*
    |--------------------------------------------------------------------------
    | Submit
    |--------------------------------------------------------------------------
    */

    form.addEventListener('submit', async event => {

        event.preventDefault();

        hideError();

        clearFieldError('password');

        clearFieldError(
            'password_confirmation'
        );


        const passwordValue =
            password.value;

        const confirmationValue =
            confirmation.value;


        /*
         * Les mêmes règles existent côté PHP.
         * Le JS améliore uniquement l'expérience.
         */

        const passwordValid =
            Object.values(rules)
                .every(
                    validator =>
                        validator(passwordValue)
                );


        if (!passwordValid) {

            setFieldError(
                'password',
                'Le mot de passe ne respecte pas toutes les règles de sécurité.'
            );

            password.focus();

            shake(form);

            return;
        }


        if (
            passwordValue !== confirmationValue
        ) {

            setFieldError(
                'password_confirmation',
                'Les deux mots de passe ne correspondent pas.'
            );

            confirmation.focus();

            shake(form);

            return;
        }


        setLoading(true);


        try {

            const response =
                await fetch('/reset-password', {
                    method: 'POST',

                    body: new FormData(form),

                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });


            const data =
                await parseResponse(response);


            if (!response.ok) {

                showError(
                    data.message ??
                    'Impossible de réinitialiser le mot de passe.'
                );

                shake(form);

                return;
            }


            /*
             * Succès
             */

            setSuccess();

            form.classList.add('d-none');

            successBox.classList.remove(
                'd-none'
            );


            await wait(1300);


            window.location.assign(
                data.redirect ?? '/login'
            );


        } catch (error) {

            console.error(
                'MedTrack password reset:',
                error
            );

            showError(
                'Impossible de communiquer avec MedTrack.'
            );

        } finally {

            if (
                !submit.classList.contains(
                    'mt-success'
                )
            ) {

                setLoading(false);
            }

        }

    });


    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    function validateConfirmation(
        displayError = true
    ) {

        if (confirmation.value === '') {
            return false;
        }

        const valid =
            password.value === confirmation.value;

        if (!valid && displayError) {

            setFieldError(
                'password_confirmation',
                'Les deux mots de passe ne correspondent pas.'
            );

        }

        return valid;

    }


    function setLoading(loading) {

        submit.disabled = loading;

        defaultState.classList.toggle(
            'd-none',
            loading
        );

        loadingState.classList.toggle(
            'd-none',
            !loading
        );

        if (!loading) {
            successState.classList.add(
                'd-none'
            );
        }

    }


    function setSuccess() {

        submit.disabled = true;

        submit.classList.add(
            'mt-success'
        );

        defaultState.classList.add(
            'd-none'
        );

        loadingState.classList.add(
            'd-none'
        );

        successState.classList.remove(
            'd-none'
        );

    }


    function setFieldError(
        field,
        message
    ) {

        const element =
            document.querySelector(
                `[data-error-for="${field}"]`
            );

        if (element) {
            element.textContent = message;
        }

    }


    function clearFieldError(field) {

        setFieldError(field, '');

    }


    function showError(message) {

        alertMessage.textContent =
            message;

        alertBox.classList.remove(
            'd-none'
        );

    }


    function hideError() {

        alertMessage.textContent = '';

        alertBox.classList.add(
            'd-none'
        );

    }


    function shake(element) {

        element.animate(
            [
                { transform: 'translateX(0)' },
                { transform: 'translateX(-6px)' },
                { transform: 'translateX(6px)' },
                { transform: 'translateX(-4px)' },
                { transform: 'translateX(4px)' },
                { transform: 'translateX(0)' }
            ],
            {
                duration: 360,
                easing: 'ease-out'
            }
        );

    }


    function wait(milliseconds) {

        return new Promise(
            resolve =>
                setTimeout(
                    resolve,
                    milliseconds
                )
        );

    }


    async function parseResponse(response) {

        const contentType =
            response.headers.get(
                'content-type'
            ) ?? '';


        if (
            !contentType.includes(
                'application/json'
            )
        ) {

            throw new Error(
                'Le serveur n’a pas retourné une réponse JSON.'
            );
        }


        return response.json();

    }

});