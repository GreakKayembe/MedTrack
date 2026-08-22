document.addEventListener(
    'DOMContentLoaded',
    () => {
        const form =
            document.getElementById(
                'academicProgramForm'
            );

        if (!form) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Elements
        |--------------------------------------------------------------------------
        */

        const universityField =
            document.getElementById(
                'university_id'
            );

        const facultySelect =
            document.getElementById(
                'faculty_id'
            );

        const codeInput =
            document.getElementById(
                'code'
            );

        const disciplineCodeInput =
            document.getElementById(
                'discipline_code'
            );

        const durationInput =
            document.getElementById(
                'duration_years'
            );

        const alertBox =
            document.getElementById(
                'academicProgramFormAlert'
            );

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
        | Context detection
        |--------------------------------------------------------------------------
        |
        | PLATFORM :
        | university_id est un <select>.
        |
        | UNIVERSITY :
        | university_id est un <input type="hidden">.
        |--------------------------------------------------------------------------
        */

        const isPlatformContext =
            universityField
            instanceof HTMLSelectElement;

        const isUniversityContext =
            universityField
            instanceof HTMLInputElement
            && universityField.type === 'hidden';


        /*
        |--------------------------------------------------------------------------
        | Original submit button state
        |--------------------------------------------------------------------------
        */

        const originalSubmitText =
            submitText
                ? submitText.textContent.trim()
                : 'Enregistrer';


        /*
        |--------------------------------------------------------------------------
        | Faculty filtering
        |--------------------------------------------------------------------------
        */

        const filterFaculties = (
            preserveSelection = false
        ) => {
            if (
                !universityField
                || !facultySelect
            ) {
                return;
            }

            const universityId =
                String(
                    universityField.value
                    ?? ''
                ).trim();

            const currentFacultyId =
                preserveSelection
                    ? facultySelect.value
                    : '';

            const options =
                facultySelect.querySelectorAll(
                    'option[data-university-id]'
                );

            const placeholder =
                facultySelect.querySelector(
                    'option:not([data-university-id])'
                );

            let availableFaculties = 0;


            /*
            |--------------------------------------------------------------------------
            | UNIVERSITY context
            |--------------------------------------------------------------------------
            |
            | Le backend a déjà limité les facultés
            | à l'université active.
            |--------------------------------------------------------------------------
            */

            if (isUniversityContext) {
                options.forEach(
                    (option) => {
                        const belongsToUniversity =
                            universityId !== ''
                            && option.dataset
                                .universityId ===
                                universityId;

                        option.hidden =
                            !belongsToUniversity;

                        option.disabled =
                            !belongsToUniversity;

                        if (belongsToUniversity) {
                            availableFaculties++;
                        }
                    }
                );


                /*
                 * faculty_id reste facultatif.
                 */
                facultySelect.disabled =
                    false;


                if (placeholder) {
                    placeholder.textContent =
                        availableFaculties > 0
                            ? 'Aucune faculté / rattachement direct'
                            : 'Aucune faculté disponible';
                }


                /*
                 * Mode édition :
                 * conserver la faculté actuelle
                 * si elle appartient toujours
                 * à l'université active.
                 */
                if (
                    preserveSelection
                    && currentFacultyId !== ''
                ) {
                    const selectedOption =
                        Array.from(
                            options
                        ).find(
                            (option) =>
                                option.value ===
                                currentFacultyId
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
                 * Mode création :
                 * rattachement direct par défaut.
                 */
                if (!preserveSelection) {
                    facultySelect.value =
                        '';
                }

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | PLATFORM context
            |--------------------------------------------------------------------------
            */

            if (!isPlatformContext) {
                return;
            }


            options.forEach(
                (option) => {
                    const belongsToUniversity =
                        universityId !== ''
                        && option.dataset
                            .universityId ===
                            universityId;

                    option.hidden =
                        !belongsToUniversity;

                    option.disabled =
                        !belongsToUniversity;

                    if (belongsToUniversity) {
                        availableFaculties++;
                    }
                }
            );


            /*
             * Aucune université sélectionnée.
             */
            if (universityId === '') {
                facultySelect.value =
                    '';

                facultySelect.disabled =
                    true;

                if (placeholder) {
                    placeholder.textContent =
                        'Sélectionnez d’abord une université';
                }

                return;
            }


            facultySelect.disabled =
                false;


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
             * Mode édition :
             * conserver la faculté sélectionnée.
             */
            if (
                preserveSelection
                && currentFacultyId !== ''
            ) {
                const selectedOption =
                    Array.from(
                        options
                    ).find(
                        (option) =>
                            option.value ===
                            currentFacultyId
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
             * Changement d'université :
             * supprimer l'ancienne faculté.
             */
            facultySelect.value =
                '';
        };


        /*
        |--------------------------------------------------------------------------
        | University / faculty initialization
        |--------------------------------------------------------------------------
        */

        if (
            universityField
            && facultySelect
        ) {
            /*
             * PLATFORM :
             * changement manuel d'université.
             */
            if (isPlatformContext) {
                universityField.addEventListener(
                    'change',
                    () => {
                        filterFaculties(
                            false
                        );
                    }
                );
            }


            /*
             * Initialisation CREATE / EDIT.
             */
            filterFaculties(
                true
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Uppercase codes
        |--------------------------------------------------------------------------
        */

        const uppercaseInput = (
            input
        ) => {
            if (!input) {
                return;
            }

            input.addEventListener(
                'input',
                () => {
                    input.value =
                        input.value
                            .toUpperCase();
                }
            );
        };


        uppercaseInput(
            codeInput
        );

        uppercaseInput(
            disciplineCodeInput
        );


        /*
        |--------------------------------------------------------------------------
        | Duration
        |--------------------------------------------------------------------------
        */

        if (durationInput) {
            durationInput.addEventListener(
                'input',
                () => {
                    if (
                        durationInput.value === ''
                    ) {
                        return;
                    }

                    const value =
                        Number(
                            durationInput.value
                        );

                    if (
                        Number.isNaN(
                            value
                        )
                    ) {
                        return;
                    }

                    if (value > 20) {
                        durationInput.value =
                            '20';
                    }

                    if (value < 1) {
                        durationInput.value =
                            '1';
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

                form.classList.add(
                    'was-validated'
                );


                /*
                 * Validation HTML native.
                 */
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


                    /*
                    |--------------------------------------------------------------------------
                    | faculty_id
                    |--------------------------------------------------------------------------
                    |
                    | Un champ disabled n'est pas envoyé
                    | dans FormData.
                    |--------------------------------------------------------------------------
                    */

                    if (
                        !formData.has(
                            'faculty_id'
                        )
                    ) {
                        formData.append(
                            'faculty_id',
                            ''
                        );
                    }


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
                                + 'le programme académique.'
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
                                'L’enregistrement du '
                                + 'programme académique '
                                + 'a échoué.'
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
                            'Programme académique '
                            + 'enregistré avec succès.'
                        )
                    );


                    /*
                     * Redirection backend.
                     */
                    window.setTimeout(
                        () => {
                            window.location.href =
                                data?.redirect
                                || '/academic-programs';
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