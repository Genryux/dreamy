export function initModal(modalId, openButtonId, closeButtonId, cancelButtonId) {
    let modal = document.querySelector(`#${modalId}`);
    let openButton = document.querySelector(`#${openButtonId}`);
    let closeButton = document.querySelector(`#${closeButtonId}`);
    let cancelButton = document.querySelector(`#${cancelButtonId}`);
    let body = document.querySelector('body');

    openButton.addEventListener('click', function() {

        if (modal.classList.contains('h-0')) {

            modal.classList.remove('h-0');
            modal.classList.add('h-full');
            body.classList.add('overflow-hidden')

        }

        if (!modal.classList.contains('h-0')) {

            closeButton.addEventListener('click', () => {
                
                modal.classList.remove('h-full');
                modal.classList.add('h-0');
                body.classList.remove('overflow-hidden')
                
            })

            modal.addEventListener('click', () => {
                
                modal.classList.remove('h-full');
                modal.classList.add('h-0');
                body.classList.remove('overflow-hidden')
                
            })

            cancelButton.addEventListener('click', () => {
                
                modal.classList.remove('h-full');
                modal.classList.add('h-0');
                body.classList.remove('overflow-hidden')
                
            })
        }

    })


}