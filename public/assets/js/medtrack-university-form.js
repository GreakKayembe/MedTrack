document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('universityForm');

    if (!form) {
        return;
    }

    const alertBox =
        document.getElementById('universityFormAlert');

    const submitButton =
        document.getElementById('universitySubmitButton');

    const submitText =
        document.getElementById('universitySubmitText');

    const submitIcon =
        document.getElementById('universitySubmitIcon');

    const codeInput =
        document.getElementById('code');

    const scoreInput =
        document.getElementById('accreditation_score');

    /*
    |--------------------------------------------------------------------------
    | Original submit button state
    |--------------------------------------------------------------------------
    */

    const originalSubmitText =
        submitText
            ? submitText.textContent.trim()
            : 'Enregistrer';


    /*
    |--------------------------------------------------------------------------
    | Institutional code
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
    | Alert
    |--------------------------------------------------------------------------
    */

    const hideAlert = () => {
        if (!alertBox) {
            return;
        }

        alertBox.className = 'alert d-none';
        alertBox.textContent = '';
    };


    const showAlert = (type, message) => {
        if (!alertBox) {
            return;
        }

        alertBox.className =
            `alert alert-${type}`;

        alertBox.textContent = message;

        alertBox.scrollIntoView({
            behavior: 'smooth',
            block: 'center',
        });
    };


    /*
    |--------------------------------------------------------------------------
    | Loading state
    |--------------------------------------------------------------------------
    */

    const setLoading = (loading) => {
        if (!submitButton) {
            return;
        }

        submitButton.disabled = loading;

        if (submitText) {
            submitText.textContent =
                loading
                    ? 'Enregistrement...'
                    : originalSubmitText;
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
    | Accreditation score
    |--------------------------------------------------------------------------
    */

    if (scoreInput) {
        scoreInput.addEventListener('input', () => {
            if (scoreInput.value === '') {
                return;
            }

            const value =
                Number(scoreInput.value);

            if (Number.isNaN(value)) {
                return;
            }

            if (value > 100) {
                scoreInput.value = '100';
            }

            if (value < 0) {
                scoreInput.value = '0';
            }
        });
    }


    /*
    |--------------------------------------------------------------------------
    | Submit
    |--------------------------------------------------------------------------
    */

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        hideAlert();

        /*
         * Validation HTML native.
         */
        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return;
        }

        form.classList.remove('was-validated');

        setLoading(true);

        try {
            const formData =
                new FormData(form);

            const response = await fetch(
                form.action,
                {
                    method: 'POST',

                    body: formData,

                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },

                    credentials: 'same-origin',
                }
            );

            let data;

            try {
                data = await response.json();
            } catch {
                throw new Error(
                    'Le serveur a retourné une réponse invalide.'
                );
            }

            if (!response.ok) {
                throw new Error(
                    data.message
                    || 'Impossible d’enregistrer l’université.'
                );
            }

            showAlert(
                'success',
                data.message
                || 'Université enregistrée avec succès.'
            );

            /*
             * Redirection fournie par le backend.
             */
            window.setTimeout(() => {
                window.location.href =
                    data.redirect
                    || '/universities';
            }, 700);

        } catch (error) {
            showAlert(
                'danger',
                error instanceof Error
                    ? error.message
                    : 'Une erreur est survenue.'
            );

            setLoading(false);
        }
    });
});