(function () {
    "use strict";


    /*
    |--------------------------------------------------------------------------
    | Upload
    |--------------------------------------------------------------------------
    */

    const uploadForm =
        document.getElementById(
            "studentImportForm"
        );

    const fileInput =
        document.getElementById(
            "student_file"
        );


    if (
        uploadForm
        && fileInput
    ) {
        initializeUpload(
            uploadForm,
            fileInput
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Confirmation
    |--------------------------------------------------------------------------
    */

    const confirmButton =
        document.getElementById(
            "studentImportConfirmButton"
        );


    if (confirmButton) {
        initializeConfirmation(
            confirmButton
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Upload initializer
    |--------------------------------------------------------------------------
    */

    function initializeUpload(
        form,
        input
    ) {
        const fileInfo =
            document.getElementById(
                "studentImportFileInfo"
            );

        const fileName =
            document.getElementById(
                "studentImportFileName"
            );

        const fileSize =
            document.getElementById(
                "studentImportFileSize"
            );

        const submitButton =
            document.getElementById(
                "studentImportSubmitButton"
            );


        input.addEventListener(
            "change",
            function () {
                const file =
                    this.files?.[0];

                if (!file) {
                    clearFileInformation(
                        fileInfo,
                        fileName,
                        fileSize
                    );

                    return;
                }


                try {
                    validateExcelFile(
                        file
                    );
                } catch (error) {
                    this.value = "";

                    clearFileInformation(
                        fileInfo,
                        fileName,
                        fileSize
                    );

                    showError(
                        error instanceof Error
                            ? error.message
                            : "Fichier invalide."
                    );

                    return;
                }


                if (fileName) {
                    fileName.textContent =
                        file.name;
                }


                if (fileSize) {
                    fileSize.textContent =
                        formatFileSize(
                            file.size
                        );
                }


                fileInfo?.classList.remove(
                    "d-none"
                );
            }
        );


        form.addEventListener(
            "submit",
            async function (event) {
                event.preventDefault();


                if (!form.checkValidity()) {
                    form.classList.add(
                        "was-validated"
                    );

                    return;
                }


                const file =
                    input.files?.[0];

                if (!file) {
                    showError(
                        "Sélectionnez un fichier Excel."
                    );

                    return;
                }


                try {
                    validateExcelFile(
                        file
                    );
                } catch (error) {
                    showError(
                        error instanceof Error
                            ? error.message
                            : "Fichier invalide."
                    );

                    return;
                }


                const originalContent =
                    submitButton?.innerHTML
                    ?? "";


                setButtonLoading(
                    submitButton,
                    "Analyse en cours..."
                );


                try {
                    const response =
                        await fetch(
                            form.action,
                            {
                                method:
                                    "POST",

                                body:
                                    new FormData(
                                        form
                                    ),

                                headers: {
                                    Accept:
                                        "application/json",

                                    "X-Requested-With":
                                        "XMLHttpRequest",
                                },
                            }
                        );


                    const payload =
                        await readJsonResponse(
                            response
                        );


                    if (
                        !response.ok
                        || payload.status
                            !== "success"
                    ) {
                        throw new Error(
                            payload.message
                            || "Impossible d'analyser le fichier."
                        );
                    }


                    if (
                        typeof payload.redirect
                            !== "string"
                        || payload.redirect === ""
                    ) {
                        throw new Error(
                            "La page de prévisualisation est introuvable."
                        );
                    }


                    window.location.href =
                        payload.redirect;


                } catch (error) {
                    showError(
                        error instanceof Error
                            ? error.message
                            : "Une erreur est survenue."
                    );


                    restoreButton(
                        submitButton,
                        originalContent
                    );
                }
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Confirmation initializer
    |--------------------------------------------------------------------------
    */

    function initializeConfirmation(
        button
    ) {
        button.addEventListener(
            "click",
            async function () {
                const importId =
                    String(
                        button.dataset.importId
                        ?? ""
                    ).trim();


                if (!importId) {
                    showError(
                        "Identifiant d'import introuvable."
                    );

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | CSRF
                |--------------------------------------------------------------------------
                |
                | On accepte deux sources :
                |
                | 1. #studentImportCsrfToken sur preview.php
                | 2. n'importe quel input[name="_token"] présent sur la page
                |--------------------------------------------------------------------------
                */

                const csrfToken =
                    resolveCsrfToken();


                if (!csrfToken) {
                    showError(
                        "Token de sécurité introuvable. "
                        + "Actualisez la page puis réessayez."
                    );

                    return;
                }


                const confirmed =
                    await askConfirmation();

                if (!confirmed) {
                    return;
                }


                const originalContent =
                    button.innerHTML;


                setButtonLoading(
                    button,
                    "Importation..."
                );


                try {
                    const formData =
                        new FormData();

                    formData.append(
                        "_token",
                        csrfToken
                    );


                    const response =
                        await fetch(
                            "/student-imports/"
                            + encodeURIComponent(
                                importId
                            )
                            + "/confirm",
                            {
                                method:
                                    "POST",

                                body:
                                    formData,

                                headers: {
                                    Accept:
                                        "application/json",

                                    "X-Requested-With":
                                        "XMLHttpRequest",
                                },
                            }
                        );


                    const payload =
                        await readJsonResponse(
                            response
                        );


                    if (
                        !response.ok
                        || payload.status
                            !== "success"
                    ) {
                        throw new Error(
                            payload.message
                            || "Impossible de confirmer l'import."
                        );
                    }


                    if (
                        typeof Swal
                        !== "undefined"
                    ) {
                        await Swal.fire({
                            icon:
                                "success",

                            title:
                                "Import terminé",

                            text:
                                payload.message
                                || "Les étudiants ont été importés.",

                            confirmButtonText:
                                "Continuer",
                        });
                    }


                    window.location.href =
                        payload.redirect
                        || "/students";


                } catch (error) {
                    showError(
                        error instanceof Error
                            ? error.message
                            : "Impossible de confirmer l'import."
                    );


                    restoreButton(
                        button,
                        originalContent
                    );
                }
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CSRF
    |--------------------------------------------------------------------------
    */

    function resolveCsrfToken()
    {
        const dedicatedToken =
            document.getElementById(
                "studentImportCsrfToken"
            )?.value;


        if (
            typeof dedicatedToken
                === "string"
            && dedicatedToken.trim()
                !== ""
        ) {
            return dedicatedToken.trim();
        }


        const genericToken =
            document.querySelector(
                'input[name="_token"]'
            )?.value;


        if (
            typeof genericToken
                === "string"
            && genericToken.trim()
                !== ""
        ) {
            return genericToken.trim();
        }


        return "";
    }


    /*
    |--------------------------------------------------------------------------
    | File validation
    |--------------------------------------------------------------------------
    */

    function validateExcelFile(
        file
    ) {
        const extension =
            getFileExtension(
                file.name
            );


        if (
            extension !== "xlsx"
            && extension !== "xls"
        ) {
            throw new Error(
                "Le fichier doit être au format .xlsx ou .xls."
            );
        }


        const maxSize =
            10 * 1024 * 1024;


        if (
            file.size
            > maxSize
        ) {
            throw new Error(
                "Le fichier dépasse la limite de 10 Mo."
            );
        }


        if (
            file.size <= 0
        ) {
            throw new Error(
                "Le fichier sélectionné est vide."
            );
        }
    }


    function getFileExtension(
        filename
    ) {
        const parts =
            String(
                filename
            ).split(".");


        if (
            parts.length < 2
        ) {
            return "";
        }


        return String(
            parts.pop()
            ?? ""
        ).toLowerCase();
    }


    function formatFileSize(
        bytes
    ) {
        const sizeMb =
            bytes
            / 1024
            / 1024;


        return sizeMb.toFixed(2)
            + " Mo";
    }


    function clearFileInformation(
        container,
        name,
        size
    ) {
        container?.classList.add(
            "d-none"
        );


        if (name) {
            name.textContent = "";
        }


        if (size) {
            size.textContent = "";
        }
    }


    /*
    |--------------------------------------------------------------------------
    | HTTP
    |--------------------------------------------------------------------------
    */

    async function readJsonResponse(
        response
    ) {
        const contentType =
            response.headers.get(
                "content-type"
            )
            ?? "";


        if (
            !contentType
                .toLowerCase()
                .includes(
                    "application/json"
                )
        ) {
            throw new Error(
                "Le serveur a retourné "
                + "une réponse inattendue."
            );
        }


        return await response.json();
    }


    /*
    |--------------------------------------------------------------------------
    | Confirmation dialog
    |--------------------------------------------------------------------------
    */

    async function askConfirmation()
    {
        if (
            typeof Swal
            !== "undefined"
        ) {
            const result =
                await Swal.fire({
                    icon:
                        "warning",

                    title:
                        "Confirmer l'import ?",

                    text:
                        "Les étudiants valides seront "
                        + "créés définitivement dans MedTrack.",

                    showCancelButton:
                        true,

                    confirmButtonText:
                        "Oui, importer",

                    cancelButtonText:
                        "Annuler",

                    reverseButtons:
                        true,
                });


            return result.isConfirmed;
        }


        return window.confirm(
            "Confirmer l'import des étudiants ?"
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Button state
    |--------------------------------------------------------------------------
    */

    function setButtonLoading(
        button,
        message
    ) {
        if (!button) {
            return;
        }


        button.disabled =
            true;


        button.innerHTML =
            '<span '
            + 'class="spinner-border '
            + 'spinner-border-sm me-2" '
            + 'role="status" '
            + 'aria-hidden="true">'
            + '</span>'
            + escapeHtml(
                message
            );
    }


    function restoreButton(
        button,
        html
    ) {
        if (!button) {
            return;
        }


        button.disabled =
            false;


        button.innerHTML =
            html;
    }


    /*
    |--------------------------------------------------------------------------
    | Error
    |--------------------------------------------------------------------------
    */

    function showError(
        message
    ) {
        if (
            typeof Swal
            !== "undefined"
        ) {
            Swal.fire({
                icon:
                    "error",

                title:
                    "Opération impossible",

                text:
                    message,

                confirmButtonText:
                    "Fermer",
            });

            return;
        }


        window.alert(
            message
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Escape
    |--------------------------------------------------------------------------
    */

    function escapeHtml(
        value
    ) {
        const element =
            document.createElement(
                "div"
            );


        element.textContent =
            String(
                value
            );


        return element.innerHTML;
    }

})();