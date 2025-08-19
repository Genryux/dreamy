export function initModal(modalId, openButtonId, closeButtonId, cancelButtonId, modalContainerId) {
    let modal = document.querySelector(`#${modalId}`);
    let openButton = document.querySelector(`#${openButtonId}`);
    let closeButton = document.querySelector(`#${closeButtonId}`);
    let cancelButton = document.querySelector(`#${cancelButtonId}`);
    let body = document.querySelector(`#${modalContainerId}`);

    if (openButton && body) {

        const openModal = () => {
            modal.classList.remove('opacity-0', 'pointer-events-none', 'scale-95');
            modal.classList.add('opacity-100', 'scale-100');
            body.classList.remove('opacity-0', 'pointer-events-none');
            body.classList.add('opacity-100');
            console.log('modal opened')
        };

        const closeModal = () => {
            modal.classList.remove('opacity-100', 'scale-100');
            modal.classList.add('opacity-0', 'pointer-events-none', 'scale-95');
            body.classList.remove('opacity-100');
            body.classList.add('opacity-0', 'pointer-events-none');
        };

        // Open modal
        openButton.addEventListener('click', openModal);

        // Close modal with buttons
        if (closeButton) closeButton.addEventListener('click', closeModal);
        if (cancelButton) cancelButton.addEventListener('click', closeModal);

        // Close modal when clicking outside content
        modal.addEventListener('click', (e) => {

            closeModal();

        });
    }

}