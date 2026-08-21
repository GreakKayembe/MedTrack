document.addEventListener(
    'DOMContentLoaded',
    () => {
        const form =
            document.getElementById(
                'cohortForm'
            );

        if (!form) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Elements
        |--------------------------------------------------------------------------
        */

        const nameInput =
            document.getElementById(
                'name'
            );

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
        | Initial states
        |--------------------------------------------------------------------------
        |
        | On mémorise l'état initial.
        |
        | C'est important car, par exemple,
        | academic_program_id peut être volontairement
        | disabled lorsque l'université ne possède
        | encore aucun programme.
        |--------------------------------------------------------------------------
        */

        const initialProgramDisabled =
            programSelect
                ? programSelect.disabled
                : false;

        const initialYearDisabled =
            yearSelect
                ? yearSelect.disabled
                : false;

        const initialNameDisabled =
            nameInput
                ? nameInput.disabled
                : false;

        const initialSubmitDisabled =
            submitButton
                ? submitButton.disabled
                : false;


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
        | Name normalization
        |--------------------------------------------------------------------------
        */

        const normalizeName = () => {
            if (!nameInput) {
                return;
            }

            nameInput.value =
                nameInput.value.trim();
        };


        if (nameInput) {
            nameInput.addEventListener(
                'blur',
                normalizeName
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

            alertBox.textContent =
                '';
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
                behavior:
                    'smooth',

                block:
                    'center',
            });
        };


        /*
        |--------------------------------------------------------------------------
        | Loading state
        |--------------------------------------------------------------------------
        */

        const setLoading = (
            loading
        ) => {
            if (submitButton) {
                submitButton.disabled =
                    loading
                        ? true
                        : initialSubmitDisabled;
            }

            if (programSelect) {
                programSelect.disabled =
                    loading
                        ? true
                        : initialProgramDisabled;
            }

            if (yearSelect) {
                yearSelect.disabled =
                    loading
                        ? true
                        : initialYearDisabled;
            }

            if (nameInput) {
                nameInput.disabled =
                    loading
                        ? true
                        : initialNameDisabled;
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
                        ? (
                            '<span '
                            + 'class="spinner-border '
                            + 'spinner-border-sm me-2" '
                            + 'aria-hidden="true">'
                            + '</span>'
                        )
                        : (
                            '<i class="bi '
                            + 'bi-check-lg me-1">'
                            + '</i>'
                        );
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
                |--------------------------------------------------------------------------
                | Normalize
                |--------------------------------------------------------------------------
                */

                normalizeName();


                /*
                |--------------------------------------------------------------------------
                | Native validation
                |--------------------------------------------------------------------------
                */

                form.classList.add(
                    'was-validated'
                );

                if (
                    !form.checkValidity()
                ) {
                    showAlert(
                        'warning',
                        'Veuillez compléter correctement '
                        + 'les champs obligatoires.'
                    );

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | FormData
                |--------------------------------------------------------------------------
                |
                | IMPORTANT :
                | FormData doit être construit AVANT
                | de désactiver les champs.
                |--------------------------------------------------------------------------
                */

                const formData =
                    new FormData(
                        form
                    );


                /*
                 * Sécurité supplémentaire.
                 *
                 * Normalement ce cas ne se produit pas
                 * puisque le bouton est lui-même disabled
                 * lorsqu'aucun programme n'existe.
                 */
                const academicProgramId =
                    String(
                        formData.get(
                            'academic_program_id'
                        )
                        ?? ''
                    ).trim();

                if (
                    academicProgramId === ''
                ) {
                    showAlert(
                        'warning',
                        'Veuillez sélectionner '
                        + 'un programme académique.'
                    );

                    return;
                }


                const academicYearId =
                    String(
                        formData.get(
                            'academic_year_id'
                        )
                        ?? ''
                    ).trim();

                if (
                    academicYearId === ''
                ) {
                    showAlert(
                        'warning',
                        'Veuillez sélectionner '
                        + 'une année académique.'
                    );

                    return;
                }


                setLoading(
                    true
                );


                try {
                    /*
                    |--------------------------------------------------------------------------
                    | Request
                    |--------------------------------------------------------------------------
                    */

                    const response =
                        await fetch(
                            form.action,
                            {
                                method:
                                    form.method
                                        .toUpperCase(),

                                body:
                                    formData,

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


                    /*
                    |--------------------------------------------------------------------------
                    | JSON response
                    |--------------------------------------------------------------------------
                    */

                    let data;

                    try {
                        data =
                            await response
                                .json();
                    } catch {
                        throw new Error(
                            'Le serveur a retourné '
                            + 'une réponse invalide.'
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | HTTP error
                    |--------------------------------------------------------------------------
                    */

                    if (!response.ok) {
                        throw new Error(
                            data?.message
                            || (
                                'Impossible d’enregistrer '
                                + 'la cohorte.'
                            )
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Application error
                    |--------------------------------------------------------------------------
                    */

                    if (
                        data?.status !==
                        'success'
                    ) {
                        throw new Error(
                            data?.message
                            || (
                                'L’enregistrement '
                                + 'de la cohorte a échoué.'
                            )
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Success
                    |--------------------------------------------------------------------------
                    */

                    showAlert(
                        'success',

                        data?.message
                        || (
                            'Cohorte enregistrée '
                            + 'avec succès.'
                        )
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Redirect
                    |--------------------------------------------------------------------------
                    */

                    window.setTimeout(
                        () => {
                            window.location.href =
                                data?.redirect
                                || '/cohorts';
                        },
                        700
                    );

                } catch (error) {
                    showAlert(
                        'danger',

                        error instanceof Error
                            ? error.message
                            : (
                                'Une erreur '
                                + 'est survenue.'
                            )
                    );

                    setLoading(
                        false
                    );
                }
            }
        );
    }
);