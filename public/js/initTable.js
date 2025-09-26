export function initCustomDataTable(tableId, ajaxUrl, columns, order, searchInputId, columnDefs = []) {
    const customSearch = document.querySelector(`#${searchInputId}`);

    let table = new DataTable(`#${tableId}`, {
        paging: true,
        searching: true,
        autoWidth: false,
        serverSide: true,
        processing: true,
        ajax: {
            url: ajaxUrl,
            data: function (d) {
                // Always read latest values at request time
                d.role_filter = window.selectedRole || '';
                d.status_filter = window.selectedStatus || '';
                d.pageLength = window.selectedPageLength || 10;
            }
        },
        order: order,
        columnDefs: columnDefs, // 🔥 dynamically injected
        layout: {
            topStart: null,
            topEnd: null,
            bottomStart: 'info',
            bottomEnd: 'paging',
        },
        columns: columns,
    });


    // Custom search input
    if (customSearch) {
        customSearch.addEventListener("input", function () {
            table.search(this.value).draw();
        });
    }


    // Custom row styling
    table.on('draw', function () {
        let rows = document.querySelectorAll(`#${tableId} tbody tr`);

        rows.forEach(function (row) {
            row.classList.add(
                'hover:bg-gray-200', 'transition', 'duration-150'
            );

            let cells = row.querySelectorAll('td');
            cells.forEach(function (cell) {
                cell.classList.add(
                    'px-4', 'py-1', 'text-start', 'font-regular',
                    'text-[14px]', 'opacity-80', 'truncate',
                    'border-t', 'border-[#1e1e1e]/10', 'font-semibold'
                );
            });
        });
    });

    // Remove default search box
    table.on("init", function () {
        const defaultSearch = document.querySelector("#dt-search-0");
        if (defaultSearch) {
            defaultSearch.remove();
        }
    });

    // Optional clear button handler
    // clearSearch('clear-btn', searchInputId, table);

    return table;
}