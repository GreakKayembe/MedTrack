document.addEventListener('DOMContentLoaded', () => {
    const form =
        document.getElementById('studentForm');

    if (!form) {
        return;
    }

    const alertBox =
        document.getElementById('studentFormAlert');

    const submitButton =
        document.getElementById(
            'studentSubmitButton'
        );

    const submitText =
        document.getElementById(
            'studentSubmitText'
        );

    const submitIcon =
        document.getElementById(
            'studentSubmitIcon'
        );

    const birthDateInput =
        document.getElementById('birth_date');

    const nationalStudentNumberInput =
        document.getElementById(
            'national_student_number'
        );


    /*
    |--------------------------------------------------------------------------
    | Birth date
    |--------------------------------------------------------------------------
    */

    if (birthDateInput) {
        const today =
            new Date();

        const year =
            today.getFullYear();

        const month =
            String(
                today.getMonth() + 1
            ).padStart(2, '0');

        const day =
            String(
                today.getDate()
            ).padStart(2, '0');

        birthDateInput.max =
            `${year}-${month}-${day}`;
    }


    /*
    |--------------------------------------------------------------------------
    | National student number
    |--------------------------------------------------------------------------
    */

    if (nationalStudentNumberInput) {
        nationalStudentNumberInput.addEventListener(
            'input',
            () => {
                nationalStudentNumberInput.value =
                    nationalStudentNumberInput.value
                        .toUpperCase();
            }
        );
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
                        + 'l’étudiant.'
                    );
                }

                showAlert(
                    'success',
                    data.message
                    || 'Étudiant enregistré '
                    + 'avec succès.'
                );

                window.setTimeout(
                    () => {
                        window.location.href =
                            data.redirect
                            || '/students';
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