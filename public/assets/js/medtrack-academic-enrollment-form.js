document.addEventListener(
    'DOMContentLoaded',
    () => {
        const form =
            document.getElementById(
                'academicEnrollmentForm'
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

        const studentIdInput =
            document.getElementById(
                'student_id'
            );

        const studentSearchPanel =
            document.getElementById(
                'studentSearchPanel'
            );

        const studentSearchInput =
            document.getElementById(
                'studentSearchInput'
            );

        const studentSearchButton =
            document.getElementById(
                'studentSearchButton'
            );

        const studentSearchAlert =
            document.getElementById(
                'studentSearchAlert'
            );

        const studentSearchLoading =
            document.getElementById(
                'studentSearchLoading'
            );

        const studentSearchResults =
            document.getElementById(
                'studentSearchResults'
            );

        const studentNotFoundActions =
            document.getElementById(
                'studentNotFoundActions'
            );

        const selectedStudentCard =
            document.getElementById(
                'selectedStudentCard'
            );

        const selectedStudentName =
            document.getElementById(
                'selectedStudentName'
            );

        const selectedStudentDetails =
            document.getElementById(
                'selectedStudentDetails'
            );

        const changeStudentButton =
            document.getElementById(
                'changeStudentButton'
            );

        const createStudentIdentityButton =
            document.getElementById(
                'createStudentIdentityButton'
            );

        const studentIdentityCreationPanel =
            document.getElementById(
                'studentIdentityCreationPanel'
            );

        const cancelStudentIdentityButton =
            document.getElementById(
                'cancelStudentIdentityButton'
            );

        const cancelStudentIdentityCreationButton =
            document.getElementById(
                'cancelStudentIdentityCreationButton'
            );

        const saveStudentIdentityButton =
            document.getElementById(
                'saveStudentIdentityButton'
            );

        const saveStudentIdentityText =
            document.getElementById(
                'saveStudentIdentityText'
            );

        const saveStudentIdentityIcon =
            document.getElementById(
                'saveStudentIdentityIcon'
            );

        const studentIdentityAlert =
            document.getElementById(
                'studentIdentityAlert'
            );

        const studentIdentityFirstName =
            document.getElementById(
                'studentIdentityFirstName'
            );

        const studentIdentityMiddleName =
            document.getElementById(
                'studentIdentityMiddleName'
            );

        const studentIdentityLastName =
            document.getElementById(
                'studentIdentityLastName'
            );

        const studentIdentityGender =
            document.getElementById(
                'studentIdentityGender'
            );

        const studentIdentityBirthDate =
            document.getElementById(
                'studentIdentityBirthDate'
            );

        const studentIdentityBirthPlace =
            document.getElementById(
                'studentIdentityBirthPlace'
            );

        const studentIdentityNationality =
            document.getElementById(
                'studentIdentityNationality'
            );

        const studentIdentityNationalNumber =
            document.getElementById(
                'studentIdentityNationalNumber'
            );

        const studentIdentityEmail =
            document.getElementById(
                'studentIdentityEmail'
            );

        const studentIdentityPhone =
            document.getElementById(
                'studentIdentityPhone'
            );

        const csrfInput =
            form.querySelector(
                'input[name="_token"]'
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
        | Initial state
        |--------------------------------------------------------------------------
        */

        const originalSubmitText =
            submitText
                ? submitText.textContent.trim()
                : 'Enregistrer';

        const initialAcademicYearDisabled =
            academicYearSelect
                ? academicYearSelect.disabled
                : false;

        const initialRegistrationDisabled =
            registrationNumberInput
                ? registrationNumberInput.disabled
                : false;

        const initialSubmitDisabled =
            submitButton
                ? submitButton.disabled
                : false;


        /*
        |--------------------------------------------------------------------------
        | University
        |--------------------------------------------------------------------------
        */

        const universityId = () => {
            if (!universityField) {
                return '';
            }

            return String(
                universityField.value
                ?? ''
            ).trim();
        };


        /*
        |--------------------------------------------------------------------------
        | Student search
        |--------------------------------------------------------------------------
        */

        let studentSearchController =
            null;


        const hideStudentSearchAlert =
            () => {
                if (!studentSearchAlert) {
                    return;
                }

                studentSearchAlert.className =
                    'alert d-none';

                studentSearchAlert.textContent =
                    '';
            };


        const showStudentSearchAlert = (
            type,
            message
        ) => {
            if (!studentSearchAlert) {
                return;
            }

            studentSearchAlert.className =
                `alert alert-${type} mt-3 mb-0`;

            studentSearchAlert.textContent =
                message;
        };


        const setStudentSearchLoading = (
            loading
        ) => {
            if (studentSearchLoading) {
                studentSearchLoading
                    .classList
                    .toggle(
                        'd-none',
                        !loading
                    );
            }

            if (studentSearchButton) {
                studentSearchButton.disabled =
                    loading;
            }

            if (studentSearchInput) {
                studentSearchInput.disabled =
                    loading;
            }
        };


        const clearStudentSearchResults =
            () => {
                if (studentSearchResults) {
                    studentSearchResults
                        .replaceChildren();
                }

                if (studentNotFoundActions) {
                    studentNotFoundActions
                        .classList
                        .add(
                            'd-none'
                        );
                }
            };


        const formatStudentName = (
            student
        ) => {
            return [
                student?.first_name,
                student?.middle_name,
                student?.last_name,
            ]
                .map(
                    (value) =>
                        String(
                            value
                            ?? ''
                        ).trim()
                )
                .filter(Boolean)
                .join(' ');
        };


        const formatStudentDetails = (
            student
        ) => {
            const details = [];

            const nationalNumber =
                String(
                    student
                        ?.national_student_number
                    ?? ''
                ).trim();

            const birthDate =
                String(
                    student?.birth_date
                    ?? ''
                ).trim();

            if (nationalNumber !== '') {
                details.push(
                    `N° national : ${nationalNumber}`
                );
            }

            if (birthDate !== '') {
                details.push(
                    `Naissance : ${birthDate}`
                );
            }

            return details.join(
                ' • '
            );
        };


        const selectStudent = (
            student
        ) => {
            if (!studentIdInput) {
                return;
            }

            studentIdInput.value =
                String(
                    student?.id
                    ?? ''
                );

            studentIdInput
                .setCustomValidity(
                    ''
                );

            if (selectedStudentName) {
                selectedStudentName.textContent =
                    formatStudentName(
                        student
                    )
                    || (
                        'Étudiant #'
                        + studentIdInput.value
                    );
            }

            if (selectedStudentDetails) {
                selectedStudentDetails.textContent =
                    formatStudentDetails(
                        student
                    );
            }

            if (selectedStudentCard) {
                selectedStudentCard
                    .classList
                    .remove(
                        'd-none'
                    );
            }

            if (studentSearchPanel) {
                studentSearchPanel
                    .classList
                    .add(
                        'd-none'
                    );
            }

            clearStudentSearchResults();
            hideStudentSearchAlert();

            if (studentIdentityCreationPanel) {
                studentIdentityCreationPanel
                    .classList
                    .add(
                        'd-none'
                    );
            }

            form.classList.remove(
                'was-validated'
            );
        };


        const clearSelectedStudent =
            () => {
                if (studentIdInput) {
                    studentIdInput.value =
                        '';

                    studentIdInput
                        .setCustomValidity(
                            ''
                        );
                }

                if (selectedStudentName) {
                    selectedStudentName.textContent =
                        '';
                }

                if (selectedStudentDetails) {
                    selectedStudentDetails.textContent =
                        '';
                }

                if (selectedStudentCard) {
                    selectedStudentCard
                        .classList
                        .add(
                            'd-none'
                        );
                }

                if (studentSearchPanel) {
                    studentSearchPanel
                        .classList
                        .remove(
                            'd-none'
                        );
                }

                clearStudentSearchResults();
                hideStudentSearchAlert();

                if (studentSearchInput) {
                    studentSearchInput.focus();
                }
            };


        const renderStudentResults = (
            students
        ) => {
            clearStudentSearchResults();

            if (!studentSearchResults) {
                return;
            }

            if (
                !Array.isArray(students)
                || students.length === 0
            ) {
                showStudentSearchAlert(
                    'info',
                    'Aucun étudiant correspondant '
                    + 'n’a été trouvé.'
                );

                if (studentNotFoundActions) {
                    studentNotFoundActions
                        .classList
                        .remove(
                            'd-none'
                        );
                }

                return;
            }

            hideStudentSearchAlert();

            students.forEach(
                (student) => {
                    const alreadyEnrolled =
                        Number(
                            student?.already_enrolled
                            ?? 0
                        ) === 1;

                    const item =
                        document.createElement(
                            'div'
                        );

                    item.className =
                        'list-group-item';

                    const row =
                        document.createElement(
                            'div'
                        );

                    row.className =
                        'd-flex '
                        + 'justify-content-between '
                        + 'align-items-center '
                        + 'gap-3';

                    const identity =
                        document.createElement(
                            'div'
                        );

                    identity.className =
                        'flex-grow-1';

                    const name =
                        document.createElement(
                            'div'
                        );

                    name.className =
                        'fw-semibold';

                    name.textContent =
                        formatStudentName(
                            student
                        )
                        || (
                            'Étudiant #'
                            + String(
                                student?.id
                                ?? ''
                            )
                        );

                    const details =
                        document.createElement(
                            'div'
                        );

                    details.className =
                        'small text-muted';

                    details.textContent =
                        formatStudentDetails(
                            student
                        );

                    identity.appendChild(
                        name
                    );

                    if (
                        details.textContent
                        !== ''
                    ) {
                        identity.appendChild(
                            details
                        );
                    }

                    const action =
                        document.createElement(
                            'div'
                        );

                    if (alreadyEnrolled) {
                        const badge =
                            document.createElement(
                                'span'
                            );

                        badge.className =
                            'badge '
                            + 'text-bg-secondary';

                        badge.textContent =
                            'Déjà inscrit';

                        action.appendChild(
                            badge
                        );
                    } else {
                        const button =
                            document.createElement(
                                'button'
                            );

                        button.type =
                            'button';

                        button.className =
                            'btn btn-sm '
                            + 'btn-outline-primary';

                        button.textContent =
                            'Sélectionner';

                        button.addEventListener(
                            'click',
                            () => {
                                selectStudent(
                                    student
                                );
                            }
                        );

                        action.appendChild(
                            button
                        );
                    }

                    row.appendChild(
                        identity
                    );

                    row.appendChild(
                        action
                    );

                    item.appendChild(
                        row
                    );

                    studentSearchResults
                        .appendChild(
                            item
                        );
                }
            );

            if (studentNotFoundActions) {
                studentNotFoundActions
                    .classList
                    .remove(
                        'd-none'
                    );
            }
        };


        const searchStudents =
            async () => {
                if (
                    !studentSearchInput
                    || !studentSearchButton
                ) {
                    return;
                }

                const query =
                    studentSearchInput.value
                        .trim();

                hideStudentSearchAlert();
                clearStudentSearchResults();

                if (query.length < 3) {
                    showStudentSearchAlert(
                        'warning',
                        'Saisissez au moins '
                        + '3 caractères.'
                    );

                    studentSearchInput.focus();

                    return;
                }

                const activeUniversityId =
                    universityId();

                if (
                    activeUniversityId === ''
                ) {
                    showStudentSearchAlert(
                        'warning',
                        'Sélectionnez d’abord '
                        + 'une université.'
                    );

                    return;
                }

                if (studentSearchController) {
                    studentSearchController
                        .abort();
                }

                studentSearchController =
                    new AbortController();

                const parameters =
                    new URLSearchParams();

                parameters.set(
                    'q',
                    query
                );

                parameters.set(
                    'university_id',
                    activeUniversityId
                );

                setStudentSearchLoading(
                    true
                );

                try {
                    const response =
                        await fetch(
                            '/academic-enrollments/'
                            + 'student-search?'
                            + parameters.toString(),
                            {
                                method:
                                    'GET',

                                headers: {
                                    'Accept':
                                        'application/json',

                                    'X-Requested-With':
                                        'XMLHttpRequest',
                                },

                                credentials:
                                    'same-origin',

                                signal:
                                    studentSearchController
                                        .signal,
                            }
                        );

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

                    if (!response.ok) {
                        throw new Error(
                            data?.message
                            || (
                                'Impossible de rechercher '
                                + 'les étudiants.'
                            )
                        );
                    }

                    if (
                        data?.status
                        !== 'success'
                    ) {
                        throw new Error(
                            data?.message
                            || (
                                'La recherche '
                                + 'a échoué.'
                            )
                        );
                    }

                    renderStudentResults(
                        data?.students
                        ?? []
                    );

                } catch (error) {
                    if (
                        error instanceof DOMException
                        && error.name
                            === 'AbortError'
                    ) {
                        return;
                    }

                    showStudentSearchAlert(
                        'danger',

                        error instanceof Error
                            ? error.message
                            : (
                                'Une erreur '
                                + 'est survenue.'
                            )
                    );

                } finally {
                    setStudentSearchLoading(
                        false
                    );
                }
            };


        /*
        |--------------------------------------------------------------------------
        | Student search events
        |--------------------------------------------------------------------------
        */

        if (studentSearchButton) {
            studentSearchButton
                .addEventListener(
                    'click',
                    () => {
                        searchStudents();
                    }
                );
        }


        if (studentSearchInput) {
            studentSearchInput
                .addEventListener(
                    'keydown',
                    (event) => {
                        if (
                            event.key
                            !== 'Enter'
                        ) {
                            return;
                        }

                        event.preventDefault();

                        searchStudents();
                    }
                );
        }


        if (changeStudentButton) {
            changeStudentButton
                .addEventListener(
                    'click',
                    () => {
                        clearSelectedStudent();
                    }
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Student identity creation
        |--------------------------------------------------------------------------
        */

        const studentIdentityInputs = [
            studentIdentityFirstName,
            studentIdentityMiddleName,
            studentIdentityLastName,
            studentIdentityGender,
            studentIdentityBirthDate,
            studentIdentityBirthPlace,
            studentIdentityNationality,
            studentIdentityNationalNumber,
            studentIdentityEmail,
            studentIdentityPhone,
        ].filter(Boolean);


        const hideStudentIdentityAlert =
            () => {
                if (!studentIdentityAlert) {
                    return;
                }

                studentIdentityAlert.className =
                    'alert d-none';

                studentIdentityAlert.textContent =
                    '';
            };


        const showStudentIdentityAlert = (
            type,
            message
        ) => {
            if (!studentIdentityAlert) {
                return;
            }

            studentIdentityAlert.className =
                `alert alert-${type}`;

            studentIdentityAlert.textContent =
                message;
        };


        const setStudentIdentityLoading = (
            loading
        ) => {
            if (saveStudentIdentityButton) {
                saveStudentIdentityButton.disabled =
                    loading;
            }

            if (cancelStudentIdentityButton) {
                cancelStudentIdentityButton.disabled =
                    loading;
            }

            if (
                cancelStudentIdentityCreationButton
            ) {
                cancelStudentIdentityCreationButton
                    .disabled =
                    loading;
            }

            studentIdentityInputs.forEach(
                (field) => {
                    field.disabled =
                        loading;
                }
            );

            if (saveStudentIdentityText) {
                saveStudentIdentityText.textContent =
                    loading
                        ? 'Création...'
                        : 'Créer l’identité';
            }

            if (saveStudentIdentityIcon) {
                saveStudentIdentityIcon.innerHTML =
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
                            + 'bi-person-check me-1">'
                            + '</i>'
                        );
            }
        };


        const resetStudentIdentityForm =
            () => {
                studentIdentityInputs.forEach(
                    (field) => {
                        field.value =
                            '';

                        field.classList.remove(
                            'is-invalid'
                        );
                    }
                );

                hideStudentIdentityAlert();
            };


        const closeStudentIdentityPanel =
            () => {
                if (studentIdentityCreationPanel) {
                    studentIdentityCreationPanel
                        .classList
                        .add(
                            'd-none'
                        );
                }

                resetStudentIdentityForm();

                if (studentNotFoundActions) {
                    studentNotFoundActions
                        .classList
                        .remove(
                            'd-none'
                        );
                }
            };


        const openStudentIdentityPanel =
            () => {
                hideStudentSearchAlert();

                if (studentNotFoundActions) {
                    studentNotFoundActions
                        .classList
                        .add(
                            'd-none'
                        );
                }

                if (studentIdentityCreationPanel) {
                    studentIdentityCreationPanel
                        .classList
                        .remove(
                            'd-none'
                        );

                    studentIdentityCreationPanel
                        .scrollIntoView({
                            behavior:
                                'smooth',

                            block:
                                'nearest',
                        });
                }

                if (studentIdentityFirstName) {
                    studentIdentityFirstName
                        .focus();
                }
            };


        const validateStudentIdentity =
            () => {
                let valid =
                    true;

                const requiredFields = [
                    studentIdentityFirstName,
                    studentIdentityLastName,
                    studentIdentityBirthDate,
                ];

                requiredFields.forEach(
                    (field) => {
                        if (!field) {
                            return;
                        }

                        const empty =
                            String(
                                field.value
                                ?? ''
                            ).trim() === '';

                        field.classList.toggle(
                            'is-invalid',
                            empty
                        );

                        if (empty) {
                            valid =
                                false;
                        }
                    }
                );

                if (
                    studentIdentityEmail
                    && studentIdentityEmail.value
                        .trim() !== ''
                    && !studentIdentityEmail
                        .checkValidity()
                ) {
                    studentIdentityEmail
                        .classList
                        .add(
                            'is-invalid'
                        );

                    valid =
                        false;
                } else if (studentIdentityEmail) {
                    studentIdentityEmail
                        .classList
                        .remove(
                            'is-invalid'
                        );
                }

                return valid;
            };


        const createStudentIdentity =
            async () => {
                hideStudentIdentityAlert();

                if (
                    !studentIdentityCreationPanel
                    || !saveStudentIdentityButton
                ) {
                    return;
                }

                if (
                    universityId() === ''
                ) {
                    showStudentIdentityAlert(
                        'warning',
                        'Aucune université active '
                        + 'n’a été déterminée.'
                    );

                    return;
                }

                if (!validateStudentIdentity()) {
                    showStudentIdentityAlert(
                        'warning',
                        'Veuillez compléter les '
                        + 'informations obligatoires.'
                    );

                    return;
                }

                const endpoint =
                    String(
                        studentIdentityCreationPanel
                            .dataset.endpoint
                        || (
                            '/academic-enrollments/'
                            + 'student-identities'
                        )
                    );

                const payload =
                    new FormData();

                if (csrfInput) {
                    payload.append(
                        '_token',
                        csrfInput.value
                    );
                }

                payload.append(
                    'first_name',
                    studentIdentityFirstName
                        ? studentIdentityFirstName
                            .value
                            .trim()
                        : ''
                );

                payload.append(
                    'middle_name',
                    studentIdentityMiddleName
                        ? studentIdentityMiddleName
                            .value
                            .trim()
                        : ''
                );

                payload.append(
                    'last_name',
                    studentIdentityLastName
                        ? studentIdentityLastName
                            .value
                            .trim()
                        : ''
                );

                payload.append(
                    'gender',
                    studentIdentityGender
                        ? studentIdentityGender.value
                        : ''
                );

                payload.append(
                    'birth_date',
                    studentIdentityBirthDate
                        ? studentIdentityBirthDate.value
                        : ''
                );

                payload.append(
                    'birth_place',
                    studentIdentityBirthPlace
                        ? studentIdentityBirthPlace
                            .value
                            .trim()
                        : ''
                );

                payload.append(
                    'nationality',
                    studentIdentityNationality
                        ? studentIdentityNationality
                            .value
                            .trim()
                        : ''
                );

                payload.append(
                    'national_student_number',
                    studentIdentityNationalNumber
                        ? studentIdentityNationalNumber
                            .value
                            .trim()
                        : ''
                );

                payload.append(
                    'email',
                    studentIdentityEmail
                        ? studentIdentityEmail
                            .value
                            .trim()
                        : ''
                );

                payload.append(
                    'phone',
                    studentIdentityPhone
                        ? studentIdentityPhone
                            .value
                            .trim()
                        : ''
                );

                setStudentIdentityLoading(
                    true
                );

                try {
                    const response =
                        await fetch(
                            endpoint,
                            {
                                method:
                                    'POST',

                                body:
                                    payload,

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
                            await response
                                .json();
                    } catch {
                        throw new Error(
                            'Le serveur a retourné '
                            + 'une réponse invalide.'
                        );
                    }

                    if (!response.ok) {
                        throw new Error(
                            data?.message
                            || (
                                'Impossible de créer '
                                + 'l’identité étudiante.'
                            )
                        );
                    }

                    if (
                        data?.status
                        !== 'success'
                    ) {
                        throw new Error(
                            data?.message
                            || (
                                'La création de '
                                + 'l’identité étudiante '
                                + 'a échoué.'
                            )
                        );
                    }

                    const student =
                        data?.student;

                    if (
                        !student
                        || Number(
                            student?.id
                            ?? 0
                        ) <= 0
                    ) {
                        throw new Error(
                            'L’identité a été créée, '
                            + 'mais le serveur n’a pas '
                            + 'retourné un étudiant valide.'
                        );
                    }

                    resetStudentIdentityForm();

                    if (studentIdentityCreationPanel) {
                        studentIdentityCreationPanel
                            .classList
                            .add(
                                'd-none'
                            );
                    }

                    selectStudent(
                        student
                    );

                    showAlert(
                        'success',
                        data?.message
                        || (
                            'Identité étudiante '
                            + 'créée et sélectionnée '
                            + 'avec succès.'
                        )
                    );

                } catch (error) {
                    showStudentIdentityAlert(
                        'danger',

                        error instanceof Error
                            ? error.message
                            : (
                                'Une erreur '
                                + 'est survenue.'
                            )
                    );

                } finally {
                    setStudentIdentityLoading(
                        false
                    );
                }
            };


        if (createStudentIdentityButton) {
            createStudentIdentityButton
                .addEventListener(
                    'click',
                    () => {
                        openStudentIdentityPanel();
                    }
                );
        }


        if (cancelStudentIdentityButton) {
            cancelStudentIdentityButton
                .addEventListener(
                    'click',
                    () => {
                        closeStudentIdentityPanel();
                    }
                );
        }


        if (
            cancelStudentIdentityCreationButton
        ) {
            cancelStudentIdentityCreationButton
                .addEventListener(
                    'click',
                    () => {
                        closeStudentIdentityPanel();
                    }
                );
        }


        if (saveStudentIdentityButton) {
            saveStudentIdentityButton
                .addEventListener(
                    'click',
                    () => {
                        createStudentIdentity();
                    }
                );
        }


        studentIdentityInputs.forEach(
            (field) => {
                field.addEventListener(
                    'input',
                    () => {
                        field.classList.remove(
                            'is-invalid'
                        );

                        hideStudentIdentityAlert();
                    }
                );
            }
        );


        /*
        |--------------------------------------------------------------------------
        | Registration number
        |--------------------------------------------------------------------------
        */

        if (registrationNumberInput) {
            registrationNumberInput
                .addEventListener(
                    'input',
                    () => {
                        registrationNumberInput
                            .value =
                            registrationNumberInput
                                .value
                                .toUpperCase();
                    }
                );

            registrationNumberInput
                .addEventListener(
                    'blur',
                    () => {
                        registrationNumberInput
                            .value =
                            registrationNumberInput
                                .value
                                .trim()
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
            if (!programSelect) {
                return;
            }

            const activeUniversityId =
                universityId();

            const currentProgramId =
                preserveSelection
                    ? programSelect.value
                    : '';

            const options =
                programSelect
                    .querySelectorAll(
                        'option[data-university-id]'
                    );

            let availablePrograms =
                0;

            options.forEach(
                (option) => {
                    const optionUniversityId =
                        String(
                            option.dataset
                                .universityId
                            ?? ''
                        );

                    const matches =
                        activeUniversityId !== ''
                        && optionUniversityId
                            === activeUniversityId;

                    option.hidden =
                        !matches;

                    option.disabled =
                        !matches;

                    if (matches) {
                        availablePrograms++;
                    }
                }
            );

            const placeholder =
                programSelect
                    .querySelector(
                        'option:not([data-university-id])'
                    );

            if (
                activeUniversityId === ''
            ) {
                programSelect.value =
                    '';

                programSelect.disabled =
                    true;

                if (placeholder) {
                    placeholder.textContent =
                        'Sélectionnez d’abord '
                        + 'une université';
                }

                filterCohorts(
                    false
                );

                return;
            }

            programSelect.disabled =
                availablePrograms === 0;

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
                    Array
                        .from(options)
                        .find(
                            (option) =>
                                option.value
                                    === currentProgramId
                                && !option.disabled
                        );

                if (selected) {
                    programSelect.value =
                        currentProgramId;

                    filterCohorts(
                        true
                    );

                    return;
                }
            }

            programSelect.value =
                '';

            filterCohorts(
                false
            );
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
                String(
                    programSelect.value
                    ?? ''
                ).trim();

            const academicYearId =
                String(
                    academicYearSelect.value
                    ?? ''
                ).trim();

            const currentCohortId =
                preserveSelection
                    ? cohortSelect.value
                    : '';

            const options =
                cohortSelect
                    .querySelectorAll(
                        'option[data-program-id]'
                    );

            let availableCohorts =
                0;

            options.forEach(
                (option) => {
                    const optionProgramId =
                        String(
                            option.dataset
                                .programId
                            ?? ''
                        );

                    const optionAcademicYearId =
                        String(
                            option.dataset
                                .academicYearId
                            ?? ''
                        );

                    const matches =
                        programId !== ''
                        && academicYearId !== ''
                        && optionProgramId
                            === programId
                        && optionAcademicYearId
                            === academicYearId;

                    option.hidden =
                        !matches;

                    option.disabled =
                        !matches;

                    if (matches) {
                        availableCohorts++;
                    }
                }
            );

            const placeholder =
                cohortSelect
                    .querySelector(
                        'option:not([data-program-id])'
                    );

            if (
                programId === ''
                || academicYearId === ''
            ) {
                cohortSelect.value =
                    '';

                cohortSelect.disabled =
                    true;

                if (placeholder) {
                    placeholder.textContent =
                        'Sélectionnez d’abord '
                        + 'un programme '
                        + 'et une année';
                }

                return;
            }

            cohortSelect.disabled =
                false;

            if (placeholder) {
                placeholder.textContent =
                    availableCohorts > 0
                        ? (
                            'Aucune cohorte / '
                            + 'rattachement facultatif'
                        )
                        : (
                            'Aucune cohorte compatible '
                            + '— rattachement facultatif'
                        );
            }

            if (
                preserveSelection
                && currentCohortId !== ''
            ) {
                const selected =
                    Array
                        .from(options)
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

            cohortSelect.value =
                '';
        };


        /*
        |--------------------------------------------------------------------------
        | Academic structure events
        |--------------------------------------------------------------------------
        */

        if (
            universityField
            && universityField.tagName
                === 'SELECT'
        ) {
            universityField
                .addEventListener(
                    'change',
                    () => {
                        filterPrograms(
                            false
                        );

                        if (
                            studentIdInput
                            && studentIdInput.value
                                !== ''
                        ) {
                            clearSelectedStudent();
                        }

                        clearStudentSearchResults();
                        hideStudentSearchAlert();

                        if (
                            studentIdentityCreationPanel
                        ) {
                            studentIdentityCreationPanel
                                .classList
                                .add(
                                    'd-none'
                                );

                            resetStudentIdentityForm();
                        }
                    }
                );
        }


        if (programSelect) {
            programSelect
                .addEventListener(
                    'change',
                    () => {
                        filterCohorts(
                            false
                        );
                    }
                );
        }


        if (academicYearSelect) {
            academicYearSelect
                .addEventListener(
                    'change',
                    () => {
                        filterCohorts(
                            false
                        );
                    }
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Initial filtering
        |--------------------------------------------------------------------------
        */

        filterPrograms(
            true
        );

        filterCohorts(
            true
        );


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
                if (loading) {
                    programSelect.disabled =
                        true;
                } else {
                    filterPrograms(
                        true
                    );
                }
            }

            if (academicYearSelect) {
                academicYearSelect.disabled =
                    loading
                        ? true
                        : initialAcademicYearDisabled;
            }

            if (cohortSelect) {
                if (loading) {
                    cohortSelect.disabled =
                        true;
                } else {
                    filterCohorts(
                        true
                    );
                }
            }

            if (registrationNumberInput) {
                registrationNumberInput.disabled =
                    loading
                        ? true
                        : initialRegistrationDisabled;
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

                if (registrationNumberInput) {
                    registrationNumberInput.value =
                        registrationNumberInput
                            .value
                            .trim()
                            .toUpperCase();
                }


                /*
                |--------------------------------------------------------------------------
                | Student validation
                |--------------------------------------------------------------------------
                */

                const currentStudentId =
                    studentIdInput
                        ? String(
                            studentIdInput.value
                            ?? ''
                        ).trim()
                        : '';

                if (
                    studentIdInput
                    && currentStudentId === ''
                ) {
                    studentIdInput
                        .setCustomValidity(
                            'Veuillez sélectionner '
                            + 'un étudiant.'
                        );
                } else if (studentIdInput) {
                    studentIdInput
                        .setCustomValidity(
                            ''
                        );
                }


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

                    if (
                        studentIdInput
                        && currentStudentId === ''
                    ) {
                        if (studentSearchPanel) {
                            studentSearchPanel
                                .classList
                                .remove(
                                    'd-none'
                                );
                        }

                        if (studentSearchInput) {
                            studentSearchInput.focus();
                        }
                    }

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | FormData
                |--------------------------------------------------------------------------
                */

                const formData =
                    new FormData(
                        form
                    );

                if (
                    !formData.has(
                        'cohort_id'
                    )
                ) {
                    formData.append(
                        'cohort_id',
                        ''
                    );
                }

                if (
                    universityId() === ''
                ) {
                    showAlert(
                        'warning',
                        'Aucune université active '
                        + 'n’a été déterminée.'
                    );

                    return;
                }


                const studentId =
                    String(
                        formData.get(
                            'student_id'
                        )
                        ?? ''
                    ).trim();

                if (studentId === '') {
                    showAlert(
                        'warning',
                        'Veuillez rechercher puis '
                        + 'sélectionner un étudiant.'
                    );

                    if (studentSearchPanel) {
                        studentSearchPanel
                            .classList
                            .remove(
                                'd-none'
                            );
                    }

                    if (studentSearchInput) {
                        studentSearchInput.focus();
                    }

                    return;
                }


                const programId =
                    String(
                        formData.get(
                            'academic_program_id'
                        )
                        ?? ''
                    ).trim();

                if (programId === '') {
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


                const studyLevelId =
                    String(
                        formData.get(
                            'study_level_id'
                        )
                        ?? ''
                    ).trim();

                if (
                    studyLevelId === ''
                ) {
                    showAlert(
                        'warning',
                        'Veuillez sélectionner '
                        + 'un niveau d’études.'
                    );

                    return;
                }


                setLoading(
                    true
                );


                /*
                |--------------------------------------------------------------------------
                | Request
                |--------------------------------------------------------------------------
                */

                try {
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


                    if (!response.ok) {
                        throw new Error(
                            data?.message
                            || (
                                'Impossible d’enregistrer '
                                + 'l’inscription académique.'
                            )
                        );
                    }


                    if (
                        data?.status
                        !== 'success'
                    ) {
                        throw new Error(
                            data?.message
                            || (
                                'L’enregistrement '
                                + 'de l’inscription '
                                + 'académique a échoué.'
                            )
                        );
                    }


                    showAlert(
                        'success',

                        data?.message
                        || (
                            'Inscription académique '
                            + 'enregistrée avec succès.'
                        )
                    );


                    window.setTimeout(
                        () => {
                            window.location.href =
                                data?.redirect
                                || '/academic-enrollments';
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