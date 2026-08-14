document.addEventListener('DOMContentLoaded', () => {
    const form =
        document.getElementById('academicYearForm');

    if (!form) {
        return;
    }

    const labelInput =
        document.getElementById('label');

    const startInput =
        document.getElementById('starts_on');

    const endInput =
        document.getElementById('ends_on');

    const alertBox =
        document.getElementById(
            'academicYearFormAlert'
        );

    const submitButton =
        document.getElementById(
            'academicYearSubmitButton'
        );

    const submitText =
        document.getElementById(
            'academicYearSubmitText'
        );

    const submitIcon =
        document.getElementById(
            'academicYearSubmitIcon'
        );


    /*
    |--------------------------------------------------------------------------
    | Alert
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
    | Loading state
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
    | Date validation
    |--------------------------------------------------------------------------
    */

    const validateDates = () => {
        if (!startInput || !endInput) {
            return true;
        }

        endInput.setCustomValidity('');

        if (
            startInput.value === ''
            || endInput.value === ''
        ) {
            return true;
        }

        if (
            endInput.value
            <= startInput.value
        ) {
            endInput.setCustomValidity(
                'La date de fin doit être '
                + 'postérieure à la date de début.'
            );

            return false;
        }

        return true;
    };


    if (startInput && endInput) {
        startInput.addEventListener(
            'change',
            () => {
                /*
                 * Empêche également le navigateur
                 * de proposer une date antérieure
                 * ou égale à la date de début.
                 */
                if (startInput.value !== '') {
                    endInput.min =
                        startInput.value;
                } else {
                    endInput.removeAttribute(
                        'min'
                    );
                }

                validateDates();
            }
        );

        endInput.addEventListener(
            'change',
            validateDates
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Academic year label
    |--------------------------------------------------------------------------
    */

    if (labelInput) {
        labelInput.addEventListener(
            'input',
            () => {
                /*
                 * Supprime les espaces accidentels.
                 *
                 * Exemple :
                 * "2026 - 2027" -> "2026-2027"
                 */
                labelInput.value =
                    labelInput.value.replace(
                        /\s+/g,
                        ''
                    );
            }
        );
    }


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

            validateDates();

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
                        + 'l’année académique.'
                    );
                }

                showAlert(
                    'success',
                    data.message
                    || 'Année académique '
                    + 'enregistrée avec succès.'
                );

                window.setTimeout(
                    () => {
                        window.location.href =
                            data.redirect
                            || '/academic-years';
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