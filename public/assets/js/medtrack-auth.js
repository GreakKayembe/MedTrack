document.addEventListener('DOMContentLoaded', () => {

    const form = document.getElementById('login-form');

    if (!form) {
        return;
    }

    const loginInput = document.getElementById('login');
    const passwordInput = document.getElementById('password');
    const togglePassword = document.getElementById('toggle-password');

    const submitButton = document.getElementById('login-submit');

    const defaultState =
        submitButton.querySelector('.mt-button-default');

    const loadingState =
        submitButton.querySelector('.mt-button-loading');

    const successState =
        submitButton.querySelector('.mt-button-success');

    const alertBox =
        document.getElementById('login-alert');

    const alertMessage =
        document.getElementById('login-alert-message');


    /*
    |--------------------------------------------------------------------------
    | Afficher / masquer le mot de passe
    |--------------------------------------------------------------------------
    */

    togglePassword?.addEventListener('click', () => {

        const show =
            passwordInput.type === 'password';

        passwordInput.type =
            show ? 'text' : 'password';

        const icon =
            togglePassword.querySelector('i');

        if (icon) {
            icon.className =
                show
                    ? 'bi bi-eye-slash'
                    : 'bi bi-eye';
        }

        togglePassword.setAttribute(
            'aria-label',
            show
                ? 'Masquer le mot de passe'
                : 'Afficher le mot de passe'
        );
    });


    /*
    |--------------------------------------------------------------------------
    | Nettoyage des erreurs
    |--------------------------------------------------------------------------
    */

    [loginInput, passwordInput].forEach(input => {

        input.addEventListener('input', () => {

            setFieldError(input.name, '');

            hideError();
        });
    });


    /*
    |--------------------------------------------------------------------------
    | Connexion
    |--------------------------------------------------------------------------
    */

    form.addEventListener('submit', async event => {

        event.preventDefault();

        hideError();

        setFieldError('login', '');
        setFieldError('password', '');

        const login =
            loginInput.value.trim();

        const password =
            passwordInput.value;

        let valid = true;

        if (login === '') {

            setFieldError(
                'login',
                'Veuillez saisir votre identifiant.'
            );

            valid = false;
        }

        if (password === '') {

            setFieldError(
                'password',
                'Veuillez saisir votre mot de passe.'
            );

            valid = false;
        }

        if (!valid) {

            shake(form);

            return;
        }

        setLoading(true);

        try {

            const response = await fetch('/login', {
                method: 'POST',

                body: new FormData(form),

                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();

            if (!response.ok) {

                showError(
                    data.message ??
                    'Les informations de connexion sont incorrectes.'
                );

                shake(form);

                passwordInput.focus();

                return;
            }

            setSuccess();

            await new Promise(
                resolve => setTimeout(resolve, 700)
            );

            window.location.assign(
                data.redirect ?? '/'
            );

        } catch (error) {

            console.error(
                'MedTrack login:',
                error
            );

            showError(
                'Impossible de communiquer avec MedTrack.'
            );

        } finally {

            if (
                !submitButton.classList.contains('mt-success')
            ) {
                setLoading(false);
            }
        }
    });


    function setLoading(loading) {

        submitButton.disabled = loading;

        defaultState.classList.toggle(
            'd-none',
            loading
        );

        loadingState.classList.toggle(
            'd-none',
            !loading
        );

        if (!loading) {
            successState.classList.add('d-none');
        }
    }


    function setSuccess() {

        submitButton.disabled = true;

        submitButton.classList.add(
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


    function showError(message) {

        alertMessage.textContent = message;

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


    function setFieldError(field, message) {

        const element =
            document.querySelector(
                `[data-error-for="${field}"]`
            );

        if (element) {
            element.textContent = message;
        }
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

});