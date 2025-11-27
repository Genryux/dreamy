export function initModal(modalId, openButtonId, closeButtonId, cancelButtonId, modalContainerId) {
    let modal = document.querySelector(`#${modalId}`);
    let openButton = document.querySelector(`#${openButtonId}`);
    let closeButton = document.querySelector(`#${closeButtonId}`);
    let cancelButton = document.querySelector(`#${cancelButtonId}`);
    let body = document.querySelector(`#${modalContainerId}`);

    if (!modal || !body) return; // required elements only

    const openModal = () => {
        modal.classList.remove('opacity-0', 'pointer-events-none', 'scale-95');
        modal.classList.add('opacity-100', 'scale-100');
        body.classList.remove('opacity-0', 'pointer-events-none');
        body.classList.add('opacity-100');
    };

    const closeModal = () => {
        modal.classList.remove('opacity-100', 'scale-100');
        modal.classList.add('opacity-0', 'pointer-events-none', 'scale-95');
        body.classList.remove('opacity-100');
        body.classList.add('opacity-0', 'pointer-events-none');
    };

    // 🔵 Only attach this if the open button exists
    if (openButton) {
        openButton.addEventListener('click', openModal);
    }

    // 🔴 Close modal buttons will ALWAYS work
    if (closeButton) closeButton.addEventListener('click', closeModal);
    if (cancelButton) cancelButton.addEventListener('click', closeModal);

    // 🔴 Clicking outside closes modal too
    modal.addEventListener('click', (e) => {
        if (e.target === modal) closeModal();
    });

    return { openModal, closeModal }; // optional: expose functions

}