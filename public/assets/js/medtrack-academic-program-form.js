document.addEventListener('DOMContentLoaded', () => {
    const form =
        document.getElementById('academicProgramForm');

    if (!form) {
        return;
    }

    const universitySelect =
        document.getElementById('university_id');

    const facultySelect =
        document.getElementById('faculty_id');

    const codeInput =
        document.getElementById('code');

    const disciplineCodeInput =
        document.getElementById('discipline_code');

    const durationInput =
        document.getElementById('duration_years');

    const alertBox =
        document.getElementById('academicProgramFormAlert');

    const submitButton =
        document.getElementById(
            'academicProgramSubmitButton'
        );

    const submitText =
        document.getElementById(
            'academicProgramSubmitText'
        );

    const submitIcon =
        document.getElementById(
            'academicProgramSubmitIcon'
        );


    /*
    |--------------------------------------------------------------------------
    | Faculty filtering
    |--------------------------------------------------------------------------
    */

    const filterFaculties = (
        preserveSelection = false
    ) => {
        if (!universitySelect || !facultySelect) {
            return;
        }

        const universityId =
            universitySelect.value;

        const currentFacultyId =
            preserveSelection
                ? facultySelect.value
                : '';

        const options =
            facultySelect.querySelectorAll(
                'option[data-university-id]'
            );

        let availableFaculties = 0;

        options.forEach((option) => {
            const belongsToUniversity =
                universityId !== ''
                && option.dataset.universityId
                    === universityId;

            option.hidden =
                !belongsToUniversity;

            option.disabled =
                !belongsToUniversity;

            if (belongsToUniversity) {
                availableFaculties++;
            }
        });

        const placeholder =
            facultySelect.querySelector(
                'option:not([data-university-id])'
            );

        /*
         * Aucune université sélectionnée.
         */
        if (universityId === '') {
            facultySelect.value = '';
            facultySelect.disabled = true;

            if (placeholder) {
                placeholder.textContent =
                    'Sélectionnez d’abord une université';
            }

            return;
        }

        facultySelect.disabled = false;

        /*
         * Texte de l'option vide.
         */
        if (placeholder) {
            placeholder.textContent =
                availableFaculties > 0
                    ? 'Aucune faculté / rattachement direct'
                    : 'Aucune faculté disponible';
        }

        /*
         * En mode édition, conserver la faculté
         * actuellement sélectionnée si elle appartient
         * toujours à l'université.
         */
        if (
            preserveSelection
            && currentFacultyId !== ''
        ) {
            const selectedOption =
                Array.from(options).find(
                    (option) =>
                        option.value === currentFacultyId
                );

            if (
                selectedOption
                && !selectedOption.disabled
            ) {
                facultySelect.value =
                    currentFacultyId;

                return;
            }
        }

        /*
         * Lors d'un changement d'université,
         * supprimer l'ancienne faculté sélectionnée.
         */
        facultySelect.value = '';
    };


    /*
    |--------------------------------------------------------------------------
    | University change
    |--------------------------------------------------------------------------
    */

    if (universitySelect && facultySelect) {
        universitySelect.addEventListener(
            'change',
            () => {
                /*
                 * L'utilisateur change d'université :
                 * l'ancienne faculté ne doit pas
                 * être conservée.
                 */
                filterFaculties(false);
            }
        );

        /*
         * Au chargement :
         *
         * - CREATE :
         *   aucune faculté n'est sélectionnée ;
         *
         * - EDIT :
         *   la faculté existante est conservée
         *   si elle appartient à l'université.
         */
        filterFaculties(true);
    }


    /*
    |--------------------------------------------------------------------------
    | Uppercase codes
    |--------------------------------------------------------------------------
    */

    const uppercaseInput = (input) => {
        if (!input) {
            return;
        }

        input.addEventListener(
            'input',
            () => {
                input.value =
                    input.value.toUpperCase();
            }
        );
    };

    uppercaseInput(codeInput);
    uppercaseInput(disciplineCodeInput);


    /*
    |--------------------------------------------------------------------------
    | Duration
    |--------------------------------------------------------------------------
    */

    if (durationInput) {
        durationInput.addEventListener(
            'input',
            () => {
                if (durationInput.value === '') {
                    return;
                }

                const value =
                    Number(durationInput.value);

                if (Number.isNaN(value)) {
                    return;
                }

                if (value > 20) {
                    durationInput.value = '20';
                }

                if (value < 1) {
                    durationInput.value = '1';
                }
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

            /*
             * Validation HTML native.
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

                /*
                 * Un élément disabled n'est pas
                 * envoyé dans FormData.
                 *
                 * faculty_id étant facultatif,
                 * nous garantissons sa présence.
                 */
                if (!formData.has('faculty_id')) {
                    formData.append(
                        'faculty_id',
                        ''
                    );
                }

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
                        + 'le programme académique.'
                    );
                }

                showAlert(
                    'success',
                    data.message
                    || 'Programme académique '
                    + 'enregistré avec succès.'
                );

                /*
                 * Redirection fournie par le backend.
                 */
                window.setTimeout(
                    () => {
                        window.location.href =
                            data.redirect
                            || '/academic-programs';
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