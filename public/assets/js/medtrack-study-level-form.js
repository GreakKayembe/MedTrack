document.addEventListener('DOMContentLoaded', () => {
    const form =
        document.getElementById('studyLevelForm');

    if (!form) {
        return;
    }

    const codeInput =
        document.getElementById('code');

    const nameInput =
        document.getElementById('name');

    const ordinalInput =
        document.getElementById('ordinal');

    const alertBox =
        document.getElementById(
            'studyLevelFormAlert'
        );

    const submitButton =
        document.getElementById(
            'studyLevelSubmitButton'
        );

    const submitText =
        document.getElementById(
            'studyLevelSubmitText'
        );

    const submitIcon =
        document.getElementById(
            'studyLevelSubmitIcon'
        );


    /*
    |--------------------------------------------------------------------------
    | Code
    |--------------------------------------------------------------------------
    */

    if (codeInput) {
        codeInput.addEventListener(
            'input',
            () => {
                codeInput.value =
                    codeInput.value
                        .toUpperCase()
                        .replace(/\s+/g, '');
            }
        );
    }


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
    | Ordinal
    |--------------------------------------------------------------------------
    */

    if (ordinalInput) {
        ordinalInput.addEventListener(
            'input',
            () => {
                ordinalInput.setCustomValidity('');

                if (ordinalInput.value === '') {
                    return;
                }

                const value =
                    Number(ordinalInput.value);

                if (
                    !Number.isInteger(value)
                    || value < 1
                    || value > 65535
                ) {
                    ordinalInput.setCustomValidity(
                        'L’ordre académique doit être '
                        + 'un entier compris entre 1 et 65535.'
                    );
                }
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
    | Loading
    |--------------------------------------------------------------------------
    */

    const originalSubmitText =
        submitText
            ? submitText.textContent.trim()
            : 'Enregistrer';


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
             * Nettoyage final.
             */
            if (codeInput) {
                codeInput.value =
                    codeInput.value
                        .trim()
                        .toUpperCase();
            }

            if (nameInput) {
                nameInput.value =
                    nameInput.value.trim();
            }


            /*
             * Validation HTML.
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
                        + 'le niveau d’études.'
                    );
                }


                showAlert(
                    'success',
                    data.message
                    || 'Niveau d’études enregistré '
                    + 'avec succès.'
                );


                window.setTimeout(
                    () => {
                        window.location.href =
                            data.redirect
                            || '/study-levels';
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