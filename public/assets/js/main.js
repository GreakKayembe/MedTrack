(function () {
    "use strict";


    /*
    |--------------------------------------------------------------------------
    | Selector helpers
    |--------------------------------------------------------------------------
    */

    const select = (
        selector,
        all = false
    ) => {
        const normalizedSelector =
            selector.trim();

        if (all) {
            return [
                ...document.querySelectorAll(
                    normalizedSelector
                ),
            ];
        }

        return document.querySelector(
            normalizedSelector
        );
    };


    /*
    |--------------------------------------------------------------------------
    | Back to top
    |--------------------------------------------------------------------------
    */

    const backToTop =
        select(
            '.back-to-top'
        );

    if (backToTop) {
        const toggleBackToTop =
            () => {
                backToTop
                    .classList
                    .toggle(
                        'active',
                        window.scrollY > 100
                    );
            };

        window.addEventListener(
            'load',
            toggleBackToTop
        );

        window.addEventListener(
            'scroll',
            toggleBackToTop,
            {
                passive: true,
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Bootstrap tooltips
    |--------------------------------------------------------------------------
    */

    if (
        typeof bootstrap
        !== 'undefined'
    ) {
        const tooltipTriggers =
            document.querySelectorAll(
                '[data-bs-toggle="tooltip"]'
            );

        tooltipTriggers.forEach(
            (element) => {
                new bootstrap.Tooltip(
                    element
                );
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Quill editors
    |--------------------------------------------------------------------------
    */

    if (
        typeof Quill
        !== 'undefined'
    ) {
        const defaultEditor =
            select(
                '.quill-editor-default'
            );

        if (defaultEditor) {
            new Quill(
                defaultEditor,
                {
                    theme:
                        'snow',
                }
            );
        }


        const bubbleEditor =
            select(
                '.quill-editor-bubble'
            );

        if (bubbleEditor) {
            new Quill(
                bubbleEditor,
                {
                    theme:
                        'bubble',
                }
            );
        }


        const fullEditor =
            select(
                '.quill-editor-full'
            );

        if (fullEditor) {
            new Quill(
                fullEditor,
                {
                    modules: {
                        toolbar: [
                            [
                                {
                                    font: [],
                                },
                                {
                                    size: [],
                                },
                            ],

                            [
                                'bold',
                                'italic',
                                'underline',
                                'strike',
                            ],

                            [
                                {
                                    color: [],
                                },
                                {
                                    background: [],
                                },
                            ],

                            [
                                {
                                    script:
                                        'super',
                                },
                                {
                                    script:
                                        'sub',
                                },
                            ],

                            [
                                {
                                    list:
                                        'ordered',
                                },
                                {
                                    list:
                                        'bullet',
                                },
                                {
                                    indent:
                                        '-1',
                                },
                                {
                                    indent:
                                        '+1',
                                },
                            ],

                            [
                                'direction',
                                {
                                    align: [],
                                },
                            ],

                            [
                                'link',
                                'image',
                                'video',
                            ],

                            [
                                'clean',
                            ],
                        ],
                    },

                    theme:
                        'snow',
                }
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | TinyMCE
    |--------------------------------------------------------------------------
    */

    if (
        typeof tinymce
        !== 'undefined'
        && document.querySelector(
            'textarea.tinymce-editor'
        )
    ) {
        const useDarkMode =
            window
                .matchMedia(
                    '(prefers-color-scheme: dark)'
                )
                .matches;

        const isSmallScreen =
            window
                .matchMedia(
                    '(max-width: 1023.5px)'
                )
                .matches;


        tinymce.init({
            selector:
                'textarea.tinymce-editor',

            plugins:
                'preview importcss '
                + 'searchreplace autolink '
                + 'autosave save directionality '
                + 'code visualblocks visualchars '
                + 'fullscreen image link media '
                + 'template codesample table '
                + 'charmap pagebreak nonbreaking '
                + 'anchor insertdatetime advlist '
                + 'lists wordcount help '
                + 'quickbars emoticons',

            menubar:
                'file edit view insert '
                + 'format tools table help',

            toolbar:
                'undo redo | '
                + 'bold italic underline '
                + 'strikethrough | '
                + 'fontfamily fontsize blocks | '
                + 'alignleft aligncenter '
                + 'alignright alignjustify | '
                + 'outdent indent | '
                + 'numlist bullist | '
                + 'forecolor backcolor '
                + 'removeformat | '
                + 'pagebreak | '
                + 'charmap emoticons | '
                + 'fullscreen preview save print | '
                + 'image media link '
                + 'anchor codesample | '
                + 'ltr rtl',

            toolbar_sticky:
                true,

            toolbar_sticky_offset:
                isSmallScreen
                    ? 70
                    : 78,

            autosave_ask_before_unload:
                true,

            autosave_interval:
                '30s',

            autosave_prefix:
                '{path}{query}-{id}-',

            autosave_restore_when_empty:
                false,

            autosave_retention:
                '2m',

            image_advtab:
                true,

            importcss_append:
                true,

            height:
                600,

            image_caption:
                true,

            quickbars_selection_toolbar:
                'bold italic | '
                + 'quicklink h2 h3 '
                + 'blockquote quickimage '
                + 'quicktable',

            noneditable_class:
                'mceNonEditable',

            toolbar_mode:
                'sliding',

            contextmenu:
                'link image table',

            skin:
                useDarkMode
                    ? 'oxide-dark'
                    : 'oxide',

            content_css:
                useDarkMode
                    ? 'dark'
                    : 'default',

            content_style:
                'body { '
                + 'font-family: Inter, '
                + 'Arial, sans-serif; '
                + 'font-size: 16px; '
                + '}',
        });
    }


    /*
    |--------------------------------------------------------------------------
    | Bootstrap validation
    |--------------------------------------------------------------------------
    */

    const validationForms =
        document.querySelectorAll(
            '.needs-validation'
        );

    validationForms.forEach(
        (form) => {
            form.addEventListener(
                'submit',
                (event) => {
                    if (
                        !form.checkValidity()
                    ) {
                        event.preventDefault();
                        event.stopPropagation();
                    }

                    form.classList.add(
                        'was-validated'
                    );
                }
            );
        }
    );


    /*
    |--------------------------------------------------------------------------
    | Simple Datatables
    |--------------------------------------------------------------------------
    */

    if (
        typeof simpleDatatables
        !== 'undefined'
    ) {
        const datatables =
            select(
                '.datatable',
                true
            );

        datatables.forEach(
            (datatable) => {
                new simpleDatatables
                    .DataTable(
                        datatable
                    );
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ECharts resize
    |--------------------------------------------------------------------------
    */

    if (
        typeof echarts
        !== 'undefined'
        && typeof ResizeObserver
        !== 'undefined'
    ) {
        const mainContainer =
            document.querySelector(
                '.medtrack-main'
            );

        if (mainContainer) {
            const resizeCharts =
                () => {
                    document
                        .querySelectorAll(
                            '.echart'
                        )
                        .forEach(
                            (chartElement) => {
                                const instance =
                                    echarts
                                        .getInstanceByDom(
                                            chartElement
                                        );

                                if (instance) {
                                    instance.resize();
                                }
                            }
                        );
                };


            const observer =
                new ResizeObserver(
                    resizeCharts
                );

            observer.observe(
                mainContainer
            );
        }
    }

})();