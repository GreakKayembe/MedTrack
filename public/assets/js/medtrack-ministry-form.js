'use strict';

document.addEventListener('DOMContentLoaded', () => {
    const form =
        document.getElementById(
            'ministryForm'
        );

    if (!form) {
        return;
    }

    const submitButton =
        document.getElementById(
            'ministrySubmitButton'
        );

    const submitIcon =
        document.getElementById(
            'ministrySubmitIcon'
        );

    const submitText =
        document.getElementById(
            'ministrySubmitText'
        );

    const alertBox =
        document.getElementById(
            'ministryFormAlert'
        );

    const codeInput =
        document.getElementById(
            'code'
        );

    const emailInput =
        document.getElementById(
            'email'
        );

    const originalButtonText =
        submitText
            ? submitText.textContent.trim()
            : 'Enregistrer';

    const originalButtonIcon =
        submitIcon
            ? submitIcon.innerHTML
            : '';

    let submitting = false;


    /*
    |--------------------------------------------------------------------------
    | Alerts
    |--------------------------------------------------------------------------
    */

    const showAlert = (
        message,
        type = 'danger'
    ) => {
        if (!alertBox) {
            return;
        }

        alertBox.className =
            `alert alert-${type}`;

        alertBox.textContent =
            message;

        alertBox.classList.remove(
            'd-none'
        );

        alertBox.scrollIntoView({
            behavior: 'smooth',
            block: 'center',
        });
    };


    const hideAlert = () => {
        if (!alertBox) {
            return;
        }

        alertBox.classList.add(
            'd-none'
        );

        alertBox.textContent = '';
    };


    /*
    |--------------------------------------------------------------------------
    | Loading state
    |--------------------------------------------------------------------------
    */

    const setLoading = (
        loading
    ) => {
        submitting =
            loading;

        if (submitButton) {
            submitButton.disabled =
                loading;
        }

        if (loading) {
            if (submitIcon) {
                submitIcon.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>';
            }

            if (submitText) {
                submitText.textContent =
                    'Enregistrement...';
            }

            return;
        }

        if (submitIcon) {
            submitIcon.innerHTML =
                originalButtonIcon;
        }

        if (submitText) {
            submitText.textContent =
                originalButtonText;
        }
    };


    /*
    |--------------------------------------------------------------------------
    | Normalization
    |--------------------------------------------------------------------------
    */

    const normalizeCode = () => {
        if (!codeInput) {
            return;
        }

        codeInput.value =
            codeInput.value
                .trim()
                .toUpperCase();
    };


    const normalizeEmail = () => {
        if (!emailInput) {
            return;
        }

        emailInput.value =
            emailInput.value
                .trim()
                .toLowerCase();
    };


    codeInput?.addEventListener(
        'blur',
        normalizeCode
    );


    emailInput?.addEventListener(
        'blur',
        normalizeEmail
    );


    /*
    |--------------------------------------------------------------------------
    | JSON response
    |--------------------------------------------------------------------------
    */

    const parseJsonResponse =
        async (response) => {
            const contentType =
                response.headers.get(
                    'content-type'
                ) ?? '';

            if (
                !contentType.includes(
                    'application/json'
                )
            ) {
                throw new Error(
                    'Le serveur a retourné une réponse inattendue.'
                );
            }

            return response.json();
        };


    /*
    |--------------------------------------------------------------------------
    | Bootstrap validation
    |--------------------------------------------------------------------------
    */

    const validateForm = () => {
        form.classList.add(
            'was-validated'
        );

        return form.checkValidity();
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

            if (submitting) {
                return;
            }

            hideAlert();

            normalizeCode();
            normalizeEmail();

            if (!validateForm()) {
                showAlert(
                    'Veuillez vérifier les champs obligatoires avant de continuer.',
                    'warning'
                );

                const invalidField =
                    form.querySelector(
                        ':invalid'
                    );

                invalidField?.focus();

                return;
            }

            setLoading(
                true
            );

            try {
                const response =
                    await fetch(
                        form.action,
                        {
                            method:
                                form.method
                                    .toUpperCase(),

                            body:
                                new FormData(
                                    form
                                ),

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

                const data =
                    await parseJsonResponse(
                        response
                    );

                if (
                    !response.ok
                    || data.status !== 'success'
                ) {
                    throw new Error(
                        data.message
                        ?? 'Impossible d’enregistrer le ministère.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Success
                |--------------------------------------------------------------------------
                */

                if (
                    typeof Swal !== 'undefined'
                ) {
                    await Swal.fire({
                        icon:
                            'success',

                        title:
                            'Opération réussie',

                        text:
                            data.message
                            ?? 'Le ministère a été enregistré avec succès.',

                        confirmButtonText:
                            'Continuer',
                    });
                } else {
                    showAlert(
                        data.message
                        ?? 'Opération effectuée avec succès.',
                        'success'
                    );
                }


                if (
                    typeof data.redirect === 'string'
                    && data.redirect !== ''
                ) {
                    window.location.href =
                        data.redirect;

                    return;
                }

                window.location.href =
                    '/ministries';
            } catch (error) {
                const message =
                    error instanceof Error
                        ? error.message
                        : 'Une erreur inattendue est survenue.';

                showAlert(
                    message,
                    'danger'
                );

                if (
                    typeof Swal !== 'undefined'
                ) {
                    await Swal.fire({
                        icon:
                            'error',

                        title:
                            'Opération impossible',

                        text:
                            message,

                        confirmButtonText:
                            'Fermer',
                    });
                }
            } finally {
                setLoading(
                    false
                );
            }
        }
    );
});