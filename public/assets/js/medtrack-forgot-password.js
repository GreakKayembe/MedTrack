document.addEventListener('DOMContentLoaded', () => {

    const form =
        document.getElementById('forgot-password-form');

    if (!form) {
        return;
    }

    const identifier =
        document.getElementById('identifier');

    const submit =
        document.getElementById('forgot-submit');

    const defaultState =
        submit.querySelector('.mt-button-default');

    const loadingState =
        submit.querySelector('.mt-button-loading');

    const alertBox =
        document.getElementById('forgot-alert');

    const alertMessage =
        document.getElementById('forgot-alert-message');

    const successBox =
        document.getElementById('forgot-success');

    const successMessage =
        document.getElementById('forgot-success-message');

    const developmentLink =
        document.getElementById('development-reset-link');


    identifier.addEventListener('input', () => {

        hideError();

        setFieldError('');
    });


    form.addEventListener('submit', async event => {

        event.preventDefault();

        hideError();

        setFieldError('');

        const email =
            identifier.value.trim();

        if (email === '') {

            setFieldError(
                'Veuillez saisir votre adresse email.'
            );

            identifier.focus();

            shake(form);

            return;
        }

        if (!isValidEmail(email)) {

            setFieldError(
                'Veuillez saisir une adresse email valide.'
            );

            identifier.focus();

            shake(form);

            return;
        }

        setLoading(true);

        try {

            const response =
                await fetch('/forgot-password', {
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
                    'Impossible de traiter votre demande.'
                );

                shake(form);

                return;
            }

            successMessage.textContent =
                data.message ??
                'Si un compte correspond à cette adresse, '
                + 'les instructions ont été envoyées.';

            form.classList.add('d-none');

            successBox.classList.remove('d-none');


            /*
             * Développement uniquement.
             */
            if (
                data.development_reset_url
                && developmentLink
            ) {

                developmentLink.href =
                    data.development_reset_url;

                developmentLink.classList.remove(
                    'd-none'
                );
            }

        } catch (error) {

            console.error(
                'MedTrack password recovery:',
                error
            );

            showError(
                'Impossible de communiquer avec MedTrack.'
            );

        } finally {

            setLoading(false);
        }
    });


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
    }


    function showError(message) {

        alertMessage.textContent = message;

        alertBox.classList.remove('d-none');
    }


    function hideError() {

        alertMessage.textContent = '';

        alertBox.classList.add('d-none');
    }


    function setFieldError(message) {

        const error =
            document.querySelector(
                '[data-error-for="identifier"]'
            );

        if (error) {
            error.textContent = message;
        }
    }


    function isValidEmail(email) {

        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(
            email
        );
    }


    async function parseResponse(response) {

        const contentType =
            response.headers.get('content-type') ?? '';

        if (
            !contentType.includes('application/json')
        ) {
            throw new Error(
                'Réponse serveur invalide.'
            );
        }

        return response.json();
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