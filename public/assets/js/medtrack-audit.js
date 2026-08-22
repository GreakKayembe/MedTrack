document.addEventListener('DOMContentLoaded', () => {
    const table =
        document.getElementById(
            'auditTable'
        );

    if (!table) {
        return;
    }

    const searchInput =
        document.getElementById(
            'auditSearch'
        );

    const actionFilter =
        document.getElementById(
            'auditActionFilter'
        );

    const entityFilter =
        document.getElementById(
            'auditEntityFilter'
        );

    const resetButton =
        document.getElementById(
            'auditResetFilters'
        );

    const visibleCount =
        document.getElementById(
            'auditVisibleCount'
        );

    const rows =
        Array.from(
            table.querySelectorAll(
                '.audit-event-row'
            )
        );


    /*
    |--------------------------------------------------------------------------
    | Normalize
    |--------------------------------------------------------------------------
    */

    const normalize =
        (value) => {
            return String(
                value ?? ''
            )
                .trim()
                .toLowerCase()
                .normalize('NFD')
                .replace(
                    /[\u0300-\u036f]/g,
                    ''
                );
        };


    /*
    |--------------------------------------------------------------------------
    | Counter
    |--------------------------------------------------------------------------
    */

    const updateCounter =
        (count) => {
            if (!visibleCount) {
                return;
            }

            visibleCount.textContent =
                `${count} événement${count > 1 ? 's' : ''}`;
        };


    /*
    |--------------------------------------------------------------------------
    | Empty filtered state
    |--------------------------------------------------------------------------
    */

    const emptyRow =
        document.createElement(
            'tr'
        );

    emptyRow.id =
        'auditFilteredEmptyState';

    emptyRow.classList.add(
        'd-none'
    );

    emptyRow.innerHTML =
        `
            <td
                colspan="7"
                class="text-center py-5"
            >
                <div class="text-muted">

                    <i
                        class="bi bi-search
                               fs-1 d-block mb-3"
                    ></i>

                    <strong class="d-block mb-1">
                        Aucun résultat
                    </strong>

                    <span class="small">
                        Aucun événement ne correspond
                        aux filtres sélectionnés.
                    </span>

                </div>
            </td>
        `;

    const tbody =
        table.querySelector(
            'tbody'
        );

    if (
        tbody
        && rows.length > 0
    ) {
        tbody.appendChild(
            emptyRow
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Filters
    |--------------------------------------------------------------------------
    */

    const applyFilters = () => {
        const search =
            normalize(
                searchInput?.value
            );

        const selectedAction =
            actionFilter?.value
            ?? '';

        const selectedEntity =
            entityFilter?.value
            ?? '';

        let count = 0;


        rows.forEach(
            (row) => {
                const searchable =
                    normalize(
                        row.dataset.search
                    );

                const rowAction =
                    row.dataset.action
                    ?? '';

                const rowEntity =
                    row.dataset.entity
                    ?? '';


                const matchesSearch =
                    search === ''
                    || searchable.includes(
                        search
                    );

                const matchesAction =
                    selectedAction === ''
                    || rowAction
                        === selectedAction;

                const matchesEntity =
                    selectedEntity === ''
                    || rowEntity
                        === selectedEntity;


                const visible =
                    matchesSearch
                    && matchesAction
                    && matchesEntity;


                row.classList.toggle(
                    'd-none',
                    !visible
                );


                if (visible) {
                    count++;
                }
            }
        );


        updateCounter(
            count
        );


        if (
            rows.length > 0
        ) {
            emptyRow.classList.toggle(
                'd-none',
                count !== 0
            );
        }
    };


    /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */

    if (searchInput) {
        searchInput.addEventListener(
            'input',
            applyFilters
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Action filter
    |--------------------------------------------------------------------------
    */

    if (actionFilter) {
        actionFilter.addEventListener(
            'change',
            applyFilters
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Entity filter
    |--------------------------------------------------------------------------
    */

    if (entityFilter) {
        entityFilter.addEventListener(
            'change',
            applyFilters
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Reset
    |--------------------------------------------------------------------------
    */

    if (resetButton) {
        resetButton.addEventListener(
            'click',
            () => {
                if (searchInput) {
                    searchInput.value =
                        '';
                }

                if (actionFilter) {
                    actionFilter.value =
                        '';
                }

                if (entityFilter) {
                    entityFilter.value =
                        '';
                }

                applyFilters();

                searchInput?.focus();
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Initial state
    |--------------------------------------------------------------------------
    */

    updateCounter(
        rows.length
    );
});