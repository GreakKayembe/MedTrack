document.addEventListener('DOMContentLoaded', () => {
    const form =
        document.getElementById(
            'academicEnrollmentForm'
        );

    if (!form) {
        return;
    }

    const universitySelect =
        document.getElementById(
            'university_id'
        );

    const programSelect =
        document.getElementById(
            'academic_program_id'
        );

    const academicYearSelect =
        document.getElementById(
            'academic_year_id'
        );

    const cohortSelect =
        document.getElementById(
            'cohort_id'
        );

    const registrationNumberInput =
        document.getElementById(
            'registration_number'
        );

    const alertBox =
        document.getElementById(
            'academicEnrollmentFormAlert'
        );

    const submitButton =
        document.getElementById(
            'academicEnrollmentSubmitButton'
        );

    const submitText =
        document.getElementById(
            'academicEnrollmentSubmitText'
        );

    const submitIcon =
        document.getElementById(
            'academicEnrollmentSubmitIcon'
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
    | Registration number
    |--------------------------------------------------------------------------
    */

    if (registrationNumberInput) {
        registrationNumberInput.addEventListener(
            'input',
            () => {
                registrationNumberInput.value =
                    registrationNumberInput.value
                        .toUpperCase();
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Programs
    |--------------------------------------------------------------------------
    */

    const filterPrograms = (
        preserveSelection = false
    ) => {
        if (
            !universitySelect
            || !programSelect
        ) {
            return;
        }

        const universityId =
            universitySelect.value;

        const currentProgramId =
            preserveSelection
                ? programSelect.value
                : '';

        const options =
            programSelect.querySelectorAll(
                'option[data-university-id]'
            );

        let availablePrograms = 0;

        options.forEach((option) => {
            const matches =
                universityId !== ''
                && option.dataset.universityId
                    === universityId;

            option.hidden = !matches;
            option.disabled = !matches;

            if (matches) {
                availablePrograms++;
            }
        });

        const placeholder =
            programSelect.querySelector(
                'option:not([data-university-id])'
            );

        if (universityId === '') {
            programSelect.value = '';
            programSelect.disabled = true;

            if (placeholder) {
                placeholder.textContent =
                    'Sélectionnez d’abord une université';
            }

            filterCohorts();

            return;
        }

        programSelect.disabled = false;

        if (placeholder) {
            placeholder.textContent =
                availablePrograms > 0
                    ? 'Sélectionnez un programme'
                    : 'Aucun programme disponible';
        }

        if (
            preserveSelection
            && currentProgramId !== ''
        ) {
            const selected =
                Array.from(options)
                    .find(
                        (option) =>
                            option.value
                                === currentProgramId
                            && !option.disabled
                    );

            if (selected) {
                programSelect.value =
                    currentProgramId;

                filterCohorts(true);

                return;
            }
        }

        programSelect.value = '';

        filterCohorts();
    };


    /*
    |--------------------------------------------------------------------------
    | Cohorts
    |--------------------------------------------------------------------------
    */

    const filterCohorts = (
        preserveSelection = false
    ) => {
        if (
            !programSelect
            || !academicYearSelect
            || !cohortSelect
        ) {
            return;
        }

        const programId =
            programSelect.value;

        const academicYearId =
            academicYearSelect.value;

        const currentCohortId =
            preserveSelection
                ? cohortSelect.value
                : '';

        const options =
            cohortSelect.querySelectorAll(
                'option[data-program-id]'
            );

        let availableCohorts = 0;

        options.forEach((option) => {
            const matches =
                programId !== ''
                && academicYearId !== ''
                && option.dataset.programId
                    === programId
                && option.dataset.academicYearId
                    === academicYearId;

            option.hidden = !matches;
            option.disabled = !matches;

            if (matches) {
                availableCohorts++;
            }
        });

        const placeholder =
            cohortSelect.querySelector(
                'option:not([data-program-id])'
            );

        if (
            programId === ''
            || academicYearId === ''
        ) {
            cohortSelect.value = '';
            cohortSelect.disabled = true;

            if (placeholder) {
                placeholder.textContent =
                    'Sélectionnez d’abord un programme et une année';
            }

            return;
        }

        cohortSelect.disabled = false;

        if (placeholder) {
            placeholder.textContent =
                availableCohorts > 0
                    ? 'Aucune cohorte / rattachement facultatif'
                    : 'Aucune cohorte compatible';
        }

        if (
            preserveSelection
            && currentCohortId !== ''
        ) {
            const selected =
                Array.from(options)
                    .find(
                        (option) =>
                            option.value
                                === currentCohortId
                            && !option.disabled
                    );

            if (selected) {
                cohortSelect.value =
                    currentCohortId;

                return;
            }
        }

        cohortSelect.value = '';
    };


    /*
    |--------------------------------------------------------------------------
    | Events
    |--------------------------------------------------------------------------
    */

    if (universitySelect) {
        universitySelect.addEventListener(
            'change',
            () => {
                filterPrograms();
            }
        );
    }

    if (programSelect) {
        programSelect.addEventListener(
            'change',
            () => {
                filterCohorts();
            }
        );
    }

    if (academicYearSelect) {
        academicYearSelect.addEventListener(
            'change',
            () => {
                filterCohorts();
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Initial filtering
    |--------------------------------------------------------------------------
    */

    filterPrograms(true);
    filterCohorts(true);


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
             * Cohort can be disabled when
             * no compatible cohort exists.
             *
             * FormData must explicitly
             * contain cohort_id.
             */

            const formData =
                new FormData(form);

            if (!formData.has('cohort_id')) {
                formData.append(
                    'cohort_id',
                    ''
                );
            }

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
                        + 'l’inscription académique.'
                    );
                }

                showAlert(
                    'success',

                    data.message
                    || 'Inscription académique '
                    + 'enregistrée avec succès.'
                );

                window.setTimeout(
                    () => {
                        window.location.href =
                            data.redirect
                            || '/academic-enrollments';
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