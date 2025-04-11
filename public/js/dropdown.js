document.addEventListener('DOMContentLoaded', function() {

    let dropdownButton = document.querySelector('#dropdown-button');
    
    dropdownButton.addEventListener('click', () => {
        console.log('clicked');

        const dropdownContent = dropdownButton.querySelector('#flex');
        
        if (dropdownButton.classList.contains('h-[40px]')) {
            // Calculate content height by getting all children and summing their heights
            const contentHeight = dropdownContent.scrollHeight + 46; // 46px is the height of the button
            
            // First set transition to smooth
            dropdownButton.style.transition = 'height 0.3s ease';
            
            // Remove fixed height class and set dynamic height
            dropdownButton.classList.remove('h-[40px]');
            dropdownButton.style.height = contentHeight + 'px';
        } 
        else {
            // Collapse back to initial height
            dropdownButton.style.transition = 'height 0.3s ease';
            dropdownButton.style.height = '40px';
            
            // Reset to class-based height after transition completes
            setTimeout(() => {
                dropdownButton.classList.add('h-[40px]');
                dropdownButton.style.height = '';
            }, 300);
        }
    });

});