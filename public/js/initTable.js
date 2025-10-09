export function initCustomDataTable(tableId, ajaxUrl, columns, order, searchInputId, columnDefs = [], paging = true, layoutBottomStart = 'info') {
    const customSearch = document.querySelector(`#${searchInputId}`);

    let table = new DataTable(`#${tableId}`, {
        paging: paging,
        searching: true,
        autoWidth: false,
        serverSide: true,
        processing: true,
        scrollCollapse: false,
        ajax: {
            url: ajaxUrl,
            data: function (d) {
                // Always read latest values at request time
                
                d.grade_filter = window.selectedGrade || '';
                d.program_filter = window.selectedProgram || '';
                d.role_filter = window.selectedRole || '';
                d.status_filter = window.selectedStatus || '';
                d.method_filter = window.selectedMethod || '';
                d.term_filter = window.selectedTerm || '';
                d.pageLength = window.selectedPageLength || 10;
                d.semester_filter = window.selectedSemester || '';
                d.category_filter = window.selectedCategory || '';
                d.gender_filter = window.selectedGender || '';
            }
        },
        order: order,
        columnDefs: columnDefs.length > 0 ? columnDefs : undefined, // 🔥 dynamically injected
        layout: {
            topStart: null,
            topEnd: null,
            bottomStart: layoutBottomStart,
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