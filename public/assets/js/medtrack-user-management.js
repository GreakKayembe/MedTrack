document.addEventListener('DOMContentLoaded', () => {
    const forms =
        document.querySelectorAll(
            '.js-user-action-form'
        );

    if (forms.length === 0) {
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | SweetAlert availability
    |--------------------------------------------------------------------------
    */

    const sweetAlertAvailable =
        typeof Swal !== 'undefined';


    /*
    |--------------------------------------------------------------------------
    | Response
    |--------------------------------------------------------------------------
    */

    const parseResponse =
        async (response) => {
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
                    || 'Impossible d’effectuer '
                    + 'cette opération.'
                );
            }

            if (
                data.status
                && data.status !== 'success'
            ) {
                throw new Error(
                    data.message
                    || 'L’opération a échoué.'
                );
            }

            return data;
        };


    /*
    |--------------------------------------------------------------------------
    | Loading state
    |--------------------------------------------------------------------------
    */

    const setLoading = (
        form,
        loading
    ) => {
        const submitButton =
            form.querySelector(
                'button[type="submit"]'
            );

        if (!submitButton) {
            return;
        }

        if (
            !submitButton.dataset
                .originalHtml
        ) {
            submitButton.dataset.originalHtml =
                submitButton.innerHTML;
        }

        submitButton.disabled =
            loading;

        if (loading) {
            submitButton.innerHTML =
                '<span '
                + 'class="spinner-border '
                + 'spinner-border-sm me-2" '
                + 'aria-hidden="true">'
                + '</span>'
                + 'Traitement...';

            return;
        }

        submitButton.innerHTML =
            submitButton.dataset
                .originalHtml;
    };


    /*
    |--------------------------------------------------------------------------
    | Reset validation
    |--------------------------------------------------------------------------
    */

    const resetValidation = (
        form
    ) => {
        form.classList.remove(
            'was-validated'
        );

        form.querySelectorAll(
            '.is-invalid'
        ).forEach(
            (field) => {
                field.classList.remove(
                    'is-invalid'
                );
            }
        );
    };


    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

    const validateForm =
        async (form) => {
            /*
             * Retire les anciennes erreurs
             * visuelles avant validation.
             */
            form.querySelectorAll(
                '.is-invalid'
            ).forEach(
                (field) => {
                    field.classList.remove(
                        'is-invalid'
                    );
                }
            );


            /*
             * Formulaire valide.
             */
            if (form.checkValidity()) {
                form.classList.remove(
                    'was-validated'
                );

                return true;
            }


            /*
             * Active les styles Bootstrap.
             */
            form.classList.add(
                'was-validated'
            );


            /*
             * Liste des champs invalides.
             */
            const invalidFields =
                Array.from(
                    form.querySelectorAll(
                        ':invalid'
                    )
                );


            invalidFields.forEach(
                (field) => {
                    field.classList.add(
                        'is-invalid'
                    );
                }
            );


            const firstInvalidField =
                invalidFields[0]
                || null;


            /*
             * Message générique.
             */
            let message =
                'Veuillez renseigner tous '
                + 'les champs obligatoires '
                + 'avant de continuer.';


            /*
             * Message spécifique si le champ
             * possède un label déclaré.
             */
            if (firstInvalidField) {
                const fieldLabel =
                    firstInvalidField
                        .getAttribute(
                            'data-field-label'
                        )
                    || firstInvalidField
                        .getAttribute(
                            'aria-label'
                        )
                    || '';

                if (fieldLabel !== '') {
                    message =
                        `Le champ « ${fieldLabel} » `
                        + 'est obligatoire.';
                }
            }


            /*
             * SweetAlert MedTrack.
             */
            if (sweetAlertAvailable) {
                await Swal.fire({
                    icon:
                        'warning',

                    title:
                        'Informations manquantes',

                    text:
                        message,

                    confirmButtonText:
                        'Corriger',
                });
            } else {
                console.error(
                    message
                );
            }


            /*
             * Positionnement sur le premier
             * champ invalide.
             */
            if (firstInvalidField) {
                firstInvalidField
                    .scrollIntoView({
                        behavior:
                            'smooth',

                        block:
                            'center',
                    });

                window.setTimeout(
                    () => {
                        firstInvalidField
                            .focus();
                    },
                    300
                );
            }


            return false;
        };


    /*
    |--------------------------------------------------------------------------
    | Confirmation
    |--------------------------------------------------------------------------
    */

    const confirmAction =
        async (form) => {
            if (!sweetAlertAvailable) {
                console.error(
                    'SweetAlert2 n’est pas chargé.'
                );

                return false;
            }

            const title =
                form.dataset.confirmTitle
                || 'Confirmer cette action ?';

            const text =
                form.dataset.confirmText
                || '';

            const confirmButton =
                form.dataset.confirmButton
                || 'Confirmer';

            const danger =
                form.dataset.confirmDanger
                === 'true';


            const result =
                await Swal.fire({
                    icon:
                        danger
                            ? 'warning'
                            : 'question',

                    title:
                        title,

                    text:
                        text,

                    showCancelButton:
                        true,

                    confirmButtonText:
                        confirmButton,

                    cancelButtonText:
                        'Annuler',

                    reverseButtons:
                        true,

                    focusCancel:
                        danger,
                });


            return result.isConfirmed;
        };


    /*
    |--------------------------------------------------------------------------
    | Success
    |--------------------------------------------------------------------------
    */

    const showSuccess =
        async (message) => {
            if (!sweetAlertAvailable) {
                console.log(
                    message
                );

                return;
            }

            await Swal.fire({
                icon:
                    'success',

                title:
                    'Opération réussie',

                text:
                    message
                    || 'La modification '
                    + 'a été enregistrée.',

                confirmButtonText:
                    'Continuer',
            });
        };


    /*
    |--------------------------------------------------------------------------
    | Error
    |--------------------------------------------------------------------------
    */

    const showError =
        async (message) => {
            if (!sweetAlertAvailable) {
                console.error(
                    message
                );

                return;
            }

            await Swal.fire({
                icon:
                    'error',

                title:
                    'Action impossible',

                text:
                    message
                    || 'Une erreur est survenue.',

                confirmButtonText:
                    'Fermer',
            });
        };


    /*
    |--------------------------------------------------------------------------
    | Request
    |--------------------------------------------------------------------------
    */

    const submitForm =
        async (form) => {
            const formData =
                new FormData(
                    form
                );

            const response =
                await fetch(
                    form.action,
                    {
                        method:
                            'POST',

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

            return parseResponse(
                response
            );
        };


    /*
    |--------------------------------------------------------------------------
    | Forms
    |--------------------------------------------------------------------------
    */

    forms.forEach(
        (form) => {
            /*
             * Nettoyage visuel dès que
             * l'utilisateur modifie un champ.
             */
            form.querySelectorAll(
                'input, select, textarea'
            ).forEach(
                (field) => {
                    field.addEventListener(
                        'input',
                        () => {
                            if (
                                field.checkValidity()
                            ) {
                                field.classList.remove(
                                    'is-invalid'
                                );
                            }
                        }
                    );

                    field.addEventListener(
                        'change',
                        () => {
                            if (
                                field.checkValidity()
                            ) {
                                field.classList.remove(
                                    'is-invalid'
                                );
                            }
                        }
                    );
                }
            );


            /*
            |--------------------------------------------------------------------------
            | Submit
            |--------------------------------------------------------------------------
            */

            form.addEventListener(
                'submit',
                async (event) => {
                    event.preventDefault();


                    /*
                     * Validation.
                     */
                    const valid =
                        await validateForm(
                            form
                        );

                    if (!valid) {
                        return;
                    }


                    /*
                     * Confirmation.
                     */
                    const confirmed =
                        await confirmAction(
                            form
                        );

                    if (!confirmed) {
                        return;
                    }


                    /*
                     * Loading.
                     */
                    setLoading(
                        form,
                        true
                    );


                    try {
                        /*
                         * AJAX.
                         */
                        const data =
                            await submitForm(
                                form
                            );


                        /*
                         * Succès.
                         */
                        await showSuccess(
                            data.message
                            || 'Modification '
                            + 'effectuée avec succès.'
                        );


                        /*
                         * Nettoyage validation.
                         */
                        resetValidation(
                            form
                        );


                        /*
                         * Redirect fourni par
                         * le serveur.
                         */
                        if (
                            typeof data.redirect
                                === 'string'
                            && data.redirect !== ''
                        ) {
                            window.location.href =
                                data.redirect;

                            return;
                        }


                        /*
                         * Sinon recharge la fiche
                         * pour refléter les nouveaux
                         * rôles / statuts.
                         */
                        window.location.reload();

                    } catch (error) {
                        await showError(
                            error instanceof Error
                                ? error.message
                                : 'Une erreur '
                                + 'est survenue.'
                        );

                        setLoading(
                            form,
                            false
                        );
                    }
                }
            );
        }
    );
});