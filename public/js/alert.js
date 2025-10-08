export function showAlert(type, message) {
    const alertContainer = document.getElementById('alert-container');
    const alertMessage = document.getElementById('alertMessage');
    const alertTitle = document.getElementById('alertTitle');

    // Update the content
    alertMessage.innerText = message;
    alertTitle.innerText = type === 'success' ? 'Success' : 'Failed';

    // Update colors and icons
    alertContainer.className = `opacity-100 ${type === 'success' ? 'bg-green-100 ring-green-300' : 'bg-red-100 ring-red-300'} ring-2 fixed top-0 left-1/2 -translate-x-1/2 flex flex-row justify-center items-center gap-4 p-4 rounded-lg shadow-lg text-center z-50 text-gray-700 scale-95 translate-y-5 transition ease-in-out duration-200 text-start truncate`;
    alertContainer.querySelector('i').className = `${type === 'success' ? 'fi fi-sr-check-circle text-green-500' : 'fi fi-sr-cross-circle text-red-500'} flex justify-center items-center text-[24px] self-start`;

    // Close button
    const closeBtn = document.getElementById('alert-close-btn');
    closeBtn.onclick = () => {

        alertContainer.classList.remove('opacity-100');
        alertContainer.classList.add('opacity-0');
        alertContainer.classList.add('pointer-events-none');
        alertContainer.classList.toggle('translate-y-5');
        alertContainer.classList.toggle('scale-95');
    };


    // Auto hide after 3 seconds
    setTimeout(() => {
        console.log('asdasdsa')
        alertContainer.classList.toggle('translate-y-5');
        alertContainer.classList.toggle('scale-95');
        alertContainer.classList.remove('opacity-100');
        alertContainer.classList.add('opacity-0');
        alertContainer.classList.add('pointer-events-none');
    }, 6000);
}
