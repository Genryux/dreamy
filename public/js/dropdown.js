export function dropDown(dropDownId, dropDownContainerId) {
    let dropDownBtn = document.querySelector(`#${dropDownId}`);
    let dropdownselection = document.querySelector(`#${dropDownContainerId}`);

    if (dropDownBtn) {
        dropDownBtn.addEventListener('click', () => {
            dropdownselection.classList.toggle('opacity-0');
            dropdownselection.classList.toggle('scale-95');
            dropdownselection.classList.toggle('pointer-events-none');
            dropdownselection.classList.toggle('translate-y-1');
        })
    }


}