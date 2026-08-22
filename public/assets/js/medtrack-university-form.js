document.addEventListener(
    'DOMContentLoaded',
    () => {
        const form =
            document.getElementById(
                'universityForm'
            );

        if (!form) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Main elements
        |--------------------------------------------------------------------------
        */

        const alertBox =
            document.getElementById(
                'universityFormAlert'
            );

        const submitButton =
            document.getElementById(
                'universitySubmitButton'
            );

        const submitText =
            document.getElementById(
                'universitySubmitText'
            );

        const submitIcon =
            document.getElementById(
                'universitySubmitIcon'
            );

        const codeInput =
            document.getElementById(
                'code'
            );

        const scoreInput =
            document.getElementById(
                'accreditation_score'
            );


        /*
        |--------------------------------------------------------------------------
        | Onboarding success elements
        |--------------------------------------------------------------------------
        */

        const successPanel =
            document.getElementById(
                'universityOnboardingSuccess'
            );

        const adminEmailOutput =
            document.getElementById(
                'onboardingAdminEmail'
            );

        const temporaryPasswordOutput =
            document.getElementById(
                'onboardingTemporaryPassword'
            );

        const copyPasswordButton =
            document.getElementById(
                'copyTemporaryPassword'
            );

        const universityLink =
            document.getElementById(
                'onboardingUniversityLink'
            );


        /*
        |--------------------------------------------------------------------------
        | Original submit state
        |--------------------------------------------------------------------------
        */

        const originalSubmitText =
            submitText
                ? submitText.textContent.trim()
                : 'Créer l’université';


        /*
        |--------------------------------------------------------------------------
        | Institutional code
        |--------------------------------------------------------------------------
        */

        if (codeInput) {
            codeInput.addEventListener(
                'input',
                () => {
                    codeInput.value =
                        codeInput.value
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
        | Loading
        |--------------------------------------------------------------------------
        */

        const setLoading = (
            loading
        ) => {
            if (submitButton) {
                submitButton.disabled =
                    loading;
            }

            if (submitText) {
                submitText.textContent =
                    loading
                        ? 'Création en cours...'
                        : originalSubmitText;
            }

            if (submitIcon) {
                submitIcon.innerHTML =
                    loading
                        ? `
                            <span
                                class="spinner-border
                                       spinner-border-sm
                                       me-2"
                                aria-hidden="true"
                            ></span>
                        `
                        : `
                            <i
                                class="bi
                                       bi-check-lg
                                       me-1"
                            ></i>
                        `;
            }
        };


        /*
        |--------------------------------------------------------------------------
        | Accreditation score
        |--------------------------------------------------------------------------
        */

        if (scoreInput) {
            scoreInput.addEventListener(
                'input',
                () => {
                    if (
                        scoreInput.value
                        === ''
                    ) {
                        return;
                    }

                    const value =
                        Number(
                            scoreInput.value
                        );

                    if (
                        Number.isNaN(
                            value
                        )
                    ) {
                        return;
                    }

                    if (value > 100) {
                        scoreInput.value =
                            '100';
                    }

                    if (value < 0) {
                        scoreInput.value =
                            '0';
                    }
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Show onboarding result
        |--------------------------------------------------------------------------
        */

        const showOnboardingSuccess = (
            data
        ) => {
            const administrator =
                data?.administrator
                ?? {};

            const administratorEmail =
                administrator.email
                ?? '';

            const temporaryPassword =
                administrator
                    .temporary_password
                ?? '';

            const universityId =
                Number(
                    data?.university_id
                    ?? 0
                );


            /*
             * Email.
             */
            if (adminEmailOutput) {
                adminEmailOutput.textContent =
                    administratorEmail !== ''
                        ? administratorEmail
                        : '—';
            }


            /*
             * Temporary password.
             */
            if (
                temporaryPasswordOutput
            ) {
                temporaryPasswordOutput
                    .textContent =
                        temporaryPassword
                            !== ''
                            ? temporaryPassword
                            : '—';
            }


            /*
             * University detail link.
             */
            if (
                universityLink
                && universityId > 0
            ) {
                universityLink.href =
                    `/universities/${universityId}`;
            }


            /*
             * Prevent a second submission.
             */
            form.classList.add(
                'd-none'
            );


            /*
             * Show credentials panel.
             */
            if (successPanel) {
                successPanel.classList
                    .remove(
                        'd-none'
                    );

                successPanel.scrollIntoView({
                    behavior:
                        'smooth',

                    block:
                        'start',
                });
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
                 * Native HTML validation.
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


                setLoading(
                    true
                );


                try {
                    const formData =
                        new FormData(
                            form
                        );


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
                                'Impossible de créer '
                                + 'l’université.'
                            )
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Application error
                    |--------------------------------------------------------------------------
                    */

                    if (
                        data?.status
                        !== 'success'
                    ) {
                        throw new Error(
                            data?.message
                            || (
                                'La création de '
                                + 'l’université a échoué.'
                            )
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Validate onboarding payload
                    |--------------------------------------------------------------------------
                    */

                    if (
                        !data?.administrator
                        || !data
                            .administrator
                            .email
                        || !data
                            .administrator
                            .temporary_password
                    ) {
                        throw new Error(
                            'L’université a été créée, '
                            + 'mais les identifiants '
                            + 'temporaires de '
                            + 'l’administrateur sont '
                            + 'absents de la réponse.'
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Success
                    |--------------------------------------------------------------------------
                    */

                    showOnboardingSuccess(
                        data
                    );

                } catch (error) {
                    showAlert(
                        'danger',

                        error instanceof Error
                            ? error.message
                            : (
                                'Une erreur '
                                + 'inattendue est survenue.'
                            )
                    );

                } finally {
                    setLoading(
                        false
                    );
                }
            }
        );


        /*
        |--------------------------------------------------------------------------
        | Copy temporary password
        |--------------------------------------------------------------------------
        */

        if (
            copyPasswordButton
            && temporaryPasswordOutput
        ) {
            copyPasswordButton
                .addEventListener(
                    'click',
                    async () => {
                        const password =
                            temporaryPasswordOutput
                                .textContent
                                ?.trim()
                            ?? '';

                        if (
                            password === ''
                            || password === '—'
                        ) {
                            return;
                        }

                        try {
                            await navigator
                                .clipboard
                                .writeText(
                                    password
                                );

                            copyPasswordButton
                                .innerHTML =
                                    `
                                        <i
                                            class="bi
                                                   bi-check-lg"
                                        ></i>
                                    `;

                            copyPasswordButton
                                .classList
                                .remove(
                                    'btn-outline-secondary'
                                );

                            copyPasswordButton
                                .classList
                                .add(
                                    'btn-outline-success'
                                );


                            window.setTimeout(
                                () => {
                                    copyPasswordButton
                                        .innerHTML =
                                            `
                                                <i
                                                    class="bi
                                                           bi-copy"
                                                ></i>
                                            `;

                                    copyPasswordButton
                                        .classList
                                        .remove(
                                            'btn-outline-success'
                                        );

                                    copyPasswordButton
                                        .classList
                                        .add(
                                            'btn-outline-secondary'
                                        );
                                },
                                1500
                            );

                        } catch {
                            showAlert(
                                'warning',
                                'Impossible de copier '
                                + 'automatiquement le mot '
                                + 'de passe. Sélectionnez-le '
                                + 'manuellement.'
                            );
                        }
                    }
                );
        }
    }
);