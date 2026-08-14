document.addEventListener('DOMContentLoaded', () => {
    const form =
        document.getElementById('cohortForm');

    if (!form) {
        return;
    }

    const nameInput =
        document.getElementById('name');

    const programSelect =
        document.getElementById(
            'academic_program_id'
        );

    const yearSelect =
        document.getElementById(
            'academic_year_id'
        );

    const alertBox =
        document.getElementById(
            'cohortFormAlert'
        );

    const submitButton =
        document.getElementById(
            'cohortSubmitButton'
        );

    const submitText =
        document.getElementById(
            'cohortSubmitText'
        );

    const submitIcon =
        document.getElementById(
            'cohortSubmitIcon'
        );


    /*
    |--------------------------------------------------------------------------
    | Original submit text
    |--------------------------------------------------------------------------
    */

    const originalSubmitText =
        submitText
            ? submitText.textContent.trim()
            : 'Enregistrer';


    /*
    |--------------------------------------------------------------------------
    | Name
    |--------------------------------------------------------------------------
    */

    if (nameInput) {
        nameInput.addEventListener(
            'blur',
            () => {
                nameInput.value =
                    nameInput.value.trim();
            }
        );
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

        if (programSelect) {
            programSelect.disabled =
                loading;
        }

        if (yearSelect) {
            yearSelect.disabled =
                loading;
        }

        if (nameInput) {
            nameInput.disabled =
                loading;
        }

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
    | Submit
    |--------------------------------------------------------------------------
    */

    form.addEventListener(
        'submit',
        async (event) => {
            event.preventDefault();

            hideAlert();


            /*
             * Normalize
             */

            if (nameInput) {
                nameInput.value =
                    nameInput.value.trim();
            }


            /*
             * Native validation
             */

            if (!form.checkValidity()) {
                form.classList.add(
                    'was-validated'
                );

                return;
            }

            form.classList.remove(
                'was-validated'
            );


            /*
             * Important:
             * FormData must be created BEFORE
             * disabling the fields.
             */

            const formData =
                new FormData(form);

            setLoading(true);


            try {
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
                        + 'la cohorte.'
                    );
                }


                showAlert(
                    'success',

                    data.message
                    || 'Cohorte enregistrée '
                    + 'avec succès.'
                );


                window.setTimeout(
                    () => {
                        window.location.href =
                            data.redirect
                            || '/cohorts';
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