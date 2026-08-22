document.addEventListener('DOMContentLoaded', () => {
    const form =
        document.getElementById('facultyForm');

    if (!form) {
        return;
    }

    const alertBox =
        document.getElementById('facultyFormAlert');

    const submitButton =
        document.getElementById('facultySubmitButton');

    const submitText =
        document.getElementById('facultySubmitText');

    const submitIcon =
        document.getElementById('facultySubmitIcon');

    const codeInput =
        document.getElementById('code');


    /*
    |--------------------------------------------------------------------------
    | Faculty code
    |--------------------------------------------------------------------------
    */

    if (codeInput) {
        codeInput.addEventListener('input', () => {
            codeInput.value =
                codeInput.value.toUpperCase();
        });
    }


    /*
    |--------------------------------------------------------------------------
    | Alerts
    |--------------------------------------------------------------------------
    */

    const hideAlert = () => {
        if (!alertBox) {
            return;
        }

        alertBox.className =
            'alert d-none';

        alertBox.textContent = '';
    };


    const showAlert = (
        type,
        message
    ) => {
        if (!alertBox) {
            return;
        }

        alertBox.className =
            `alert alert-${type}`;

        alertBox.textContent =
            message;

        alertBox.scrollIntoView({
            behavior: 'smooth',
            block: 'center',
        });
    };


    /*
    |--------------------------------------------------------------------------
    | Loading
    |--------------------------------------------------------------------------
    */

    const setLoading = (loading) => {
        if (!submitButton) {
            return;
        }

        submitButton.disabled =
            loading;

        if (submitText) {
            submitText.textContent =
                loading
                    ? 'Enregistrement...'
                    : 'Enregistrer';
        }

        if (submitIcon) {
            submitIcon.innerHTML =
                loading
                    ? '<span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>'
                    : '<i class="bi bi-check-lg me-1"></i>';
        }
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

            hideAlert();

            if (!form.checkValidity()) {
                form.classList.add(
                    'was-validated'
                );

                return;
            }

            form.classList.remove(
                'was-validated'
            );

            setLoading(true);

            try {
                const formData =
                    new FormData(form);

                const response =
                    await fetch(
                        form.action,
                        {
                            method: 'POST',

                            body: formData,

                            headers: {
                                'Accept':
                                    'application/json',

                                'X-Requested-With':
                                    'XMLHttpRequest',
                            },

                            credentials:
                                'same-origin',
                        }
                    );

                let data;

                try {
                    data =
                        await response.json();
                } catch {
                    throw new Error(
                        'Le serveur a retourné '
                        + 'une réponse invalide.'
                    );
                }

                if (!response.ok) {
                    throw new Error(
                        data.message
                        || 'Impossible d’enregistrer '
                        + 'la faculté.'
                    );
                }

                showAlert(
                    'success',
                    data.message
                    || 'Faculté enregistrée avec succès.'
                );

                window.setTimeout(
                    () => {
                        window.location.href =
                            data.redirect
                            || '/faculties';
                    },
                    700
                );
            } catch (error) {
                showAlert(
                    'danger',
                    error instanceof Error
                        ? error.message
                        : 'Une erreur est survenue.'
                );

                setLoading(false);
            }
        }
    );
});