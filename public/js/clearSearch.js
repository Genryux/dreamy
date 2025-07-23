export function clearSearch(clearBtnElement, customSearchElement, table) {

    const clearBtn = document.querySelector(`#${clearBtnElement}`);
    const customSearch = document.querySelector(`#${customSearchElement}`);

    if (clearBtn) {

        clearBtn.addEventListener('click', () => {
            customSearch.value = '';
            table.search(customSearch.value).draw();
        })


    }

}