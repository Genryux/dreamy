export function showLoader(message = "Loading...") {
    const loaderContainer = document.getElementById('loader-container');
    const loaderText = loaderContainer.querySelector('#loader-text');

    loaderText.innerText = message;
    loaderContainer.classList.remove('opacity-0', 'pointer-events-none');
    loaderContainer.classList.add('opacity-100');
}

export function hideLoader() {
    const loaderContainer = document.getElementById('loader-container');

    loaderContainer.classList.add('opacity-0');
    loaderContainer.classList.remove('opacity-100');
    loaderContainer.classList.add('pointer-events-none');
}