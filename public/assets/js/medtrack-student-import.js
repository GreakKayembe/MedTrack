(function () {
    "use strict";


    /*
    |--------------------------------------------------------------------------
    | Upload
    |--------------------------------------------------------------------------
    */

    const form =
        document.getElementById(
            "studentImportForm"
        );

    const fileInput =
        document.getElementById(
            "student_file"
        );


    if (
        form
        && fileInput
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


        fileInput.addEventListener(
            "change",
            function () {

                const file =
                    this.files?.[0];

                if (!file) {

                    fileInfo?.classList.add(
                        "d-none"
                    );

                    return;
                }


                if (fileName) {
                    fileName.textContent =
                        file.name;
                }


                if (fileSize) {

                    const sizeMb =
                        file.size
                        / 1024
                        / 1024;

                    fileSize.textContent =
                        sizeMb.toFixed(2)
                        + " Mo";
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
                    fileInput.files?.[0];

                if (!file) {
                    return;
                }


                if (
                    file.size
                    > 10 * 1024 * 1024
                ) {

                    showError(
                        "Le fichier dépasse la limite de 10 Mo."
                    );

                    return;
                }


                const original =
                    submitButton
                        ?.innerHTML;


                if (submitButton) {

                    submitButton.disabled =
                        true;

                    submitButton.innerHTML =
                        '<span class="spinner-border '
                        + 'spinner-border-sm me-2"></span>'
                        + 'Analyse en cours...';
                }


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
                        await response.json();


                    if (
                        !response.ok
                        || payload.status
                            !== "success"
                    ) {
                        throw new Error(
                            payload.message
                            || "Import impossible."
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


                    if (submitButton) {

                        submitButton.disabled =
                            false;

                        submitButton.innerHTML =
                            original;
                    }
                }
            }
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

        confirmButton.addEventListener(
            "click",
            async function () {

                const importId =
                    this.dataset.importId;


                if (!importId) {
                    return;
                }


                const confirmed =
                    await confirmImport();

                if (!confirmed) {
                    return;
                }


                const original =
                    this.innerHTML;


                this.disabled =
                    true;

                this.innerHTML =
                    '<span class="spinner-border '
                    + 'spinner-border-sm me-2"></span>'
                    + 'Importation...';


                try {

                    const csrfToken =
                        document.querySelector(
                            'meta[name="csrf-token"]'
                        )?.content
                        ?? "";


                    const formData =
                        new FormData();

                    formData.append(
                        "_token",
                        csrfToken
                    );


                    const response =
                        await fetch(
                            "/student-imports/"
                            + importId
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
                        await response.json();


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
                                payload.message,

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


                    this.disabled =
                        false;

                    this.innerHTML =
                        original;
                }
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    async function confirmImport()
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
                        + "créés dans MedTrack.",

                    showCancelButton:
                        true,

                    confirmButtonText:
                        "Oui, importer",

                    cancelButtonText:
                        "Annuler",
                });


            return result.isConfirmed;
        }


        return window.confirm(
            "Confirmer l'import des étudiants ?"
        );
    }


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
            });

            return;
        }


        window.alert(
            message
        );
    }

})();