document.addEventListener('DOMContentLoaded', () => {
    'use strict';

    const form =
        document.getElementById(
            'hospitalForm'
        );

    if (!form) {
        return;
    }

    const alertBox =
        document.getElementById(
            'hospitalFormAlert'
        );

    const submitButton =
        document.getElementById(
            'hospitalSubmitButton'
        );

    const submitIcon =
        document.getElementById(
            'hospitalSubmitIcon'
        );

    const submitText =
        document.getElementById(
            'hospitalSubmitText'
        );

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

        window.scrollTo({
            top: 0,
            behavior: 'smooth',
        });
    };

    const hideAlert = () => {
        if (!alertBox) {
            return;
        }

        alertBox.classList.add(
            'd-none'
        );
    };

    const setLoading = (
        loading
    ) => {
        if (submitButton) {
            submitButton.disabled =
                loading;
        }

        if (submitIcon) {
            submitIcon.innerHTML =
                loading
                    ? '<span class="spinner-border spinner-border-sm me-2"></span>'
                    : '<i class="bi bi-check-lg me-1"></i>';
        }

        if (submitText) {
            submitText.textContent =
                loading
                    ? 'Enregistrement...'
                    : 'Enregistrer';
        }
    };

    form.addEventListener(
        'submit',
        async (event) => {
            event.preventDefault();

            hideAlert();

            if (!form.checkValidity()) {
                form.classList.add(
                    'was-validated'
                );

                showAlert(
                    'Veuillez vérifier les champs obligatoires.'
                );

                return;
            }

            form.classList.add(
                'was-validated'
            );

            setLoading(true);

            try {
                const response =
                    await fetch(
                        form.action,
                        {
                            method: 'POST',

                            body:
                                new FormData(
                                    form
                                ),

                            headers: {
                                Accept:
                                    'application/json',

                                'X-Requested-With':
                                    'XMLHttpRequest',
                            },
                        }
                    );

                let payload = {};

                try {
                    payload =
                        await response.json();
                } catch {
                    throw new Error(
                        'La réponse du serveur est invalide.'
                    );
                }

                if (!response.ok) {
                    throw new Error(
                        payload.message
                        || 'Impossible d’enregistrer l’hôpital.'
                    );
                }

                if (
                    typeof Swal
                    !== 'undefined'
                ) {
                    await Swal.fire({
                        icon: 'success',

                        title:
                            'Hôpital enregistré',

                        text:
                            payload.message
                            || 'L’hôpital a été enregistré avec succès.',

                        confirmButtonText:
                            'Continuer',
                    });
                }

                window.location.href =
                    payload.redirect
                    || '/hospitals';
            } catch (error) {
                const message =
                    error instanceof Error
                        ? error.message
                        : 'Une erreur est survenue.';

                showAlert(
                    message
                );

                if (
                    typeof Swal
                    !== 'undefined'
                ) {
                    await Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: message,
                    });
                }
            } finally {
                setLoading(false);
            }
        }
    );
});