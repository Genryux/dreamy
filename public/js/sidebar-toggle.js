/**
 * Sidebar Toggle Functionality
 * 
 * This script provides functionality to toggle a sidebar between expanded and collapsed states.
 * It can be reused across multiple dashboard layouts (admin, student, registrar, etc.)
 */

document.addEventListener('DOMContentLoaded', function() {
    const sideNav = document.getElementById('side-nav-bar');
    const content = document.getElementById('content');
    const overlay = document.getElementById('mobile-overlay');
    
    // Set initial states for mobile
    if (window.innerWidth < 768) {
        if (overlay) overlay.classList.remove('visible');
        if (sideNav) sideNav.classList.remove('visible');
    }
    
    // Desktop sidebar toggle (also works for mobile)
    const toggleButton = document.getElementById('sidebar-toggle-button');
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    
    // Function to toggle mobile menu
    function toggleMobileMenu() {
        sideNav.classList.toggle('visible');
        if (overlay) {
            overlay.classList.toggle('visible');
        }
        // Prevent body scrolling when menu is open
        document.body.classList.toggle('overflow-hidden');
    }
    
    if (toggleButton) {
        toggleButton.addEventListener('click', function() {
            // On mobile, toggle the sidebar visibility
            if (window.innerWidth < 768) {
                toggleMobileMenu();
                return;
            }
            
            // On desktop, collapse/expand the sidebar
            const navTexts = document.querySelectorAll('.nav-text');
            const logo = sideNav.querySelector('img');
            // Select all navigation item spans regardless of current class
            const navSpans = document.querySelectorAll('#side-nav-bar span.flex.flex-row, #side-nav-bar button span.flex.flex-row');
            
            // Toggle sidebar width
            if (sideNav.classList.contains('collapsed')) {
                // Expand
                sideNav.style.width = '300px';
                sideNav.classList.remove('collapsed');
                
                // Show texts with a short delay to create a smooth transition
                setTimeout(() => {
                    navTexts.forEach(text => {
                        text.style.display = 'block';
                        text.style.opacity = '1';
                    });
                    logo.style.opacity = '1';
                    
                    // Restore original spacing
                    navSpans.forEach(span => {
                        span.classList.remove('space-x-0');
                        span.classList.add('space-x-4');
                    });
                }, 150);
            } else {
                // Collapse
                sideNav.classList.add('collapsed');
                sideNav.style.width = '80px';
                
                // Hide texts immediately
                navTexts.forEach(text => {
                    text.style.opacity = '0';
                    setTimeout(() => {
                        text.style.display = 'none';
                    }, 150);
                });
                logo.style.opacity = '0.3';
                
                // Remove spacing that causes overflow
                navSpans.forEach(span => {
                    span.classList.remove('space-x-4');
                    span.classList.add('space-x-0');
                });
            }
            
            // Adjust content width and ensure it takes full available space
            content.style.transition = 'width 0.3s ease-in-out';
            content.style.width = sideNav.classList.contains('collapsed') ? 'calc(100% - 80px)' : 'calc(100% - 300px)';
        });
    }
    
    // Mobile menu button (hamburger menu)
    if (mobileMenuButton) {
        mobileMenuButton.addEventListener('click', function() {
            toggleMobileMenu();
        });
    }
    
    // Close sidebar on mobile when clicking a nav link (for better UX)
    const navLinks = document.querySelectorAll('#side-nav-bar a');
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth < 768) { // Only on mobile
                sideNav.classList.remove('visible');
                if (overlay) {
                    overlay.classList.remove('visible');
                }
                document.body.classList.remove('overflow-hidden');
            }
        });
    });
    
    // Close menu when clicking overlay
    if (overlay) {
        overlay.addEventListener('click', function() {
            sideNav.classList.remove('visible');
            overlay.classList.remove('visible');
            document.body.classList.remove('overflow-hidden');
        });
    }
    
    // Handle window resize events for responsive behavior
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 768) {
            // On desktop, reset mobile menu state
            if (overlay) {
                overlay.classList.remove('visible');
            }
            document.body.classList.remove('overflow-hidden');
            
            // Show sidebar on desktop
            sideNav.classList.remove('visible');
        }
    });
}); 

