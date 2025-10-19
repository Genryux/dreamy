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
    const navContainer = document.getElementById('nav-container');
    
    // Set initial states for mobile
    if (window.innerWidth < 768) {
        if (overlay) overlay.classList.remove('visible');
        if (sideNav) sideNav.classList.remove('visible');
    }
    
    // Restore sidebar state from localStorage
    const savedState = localStorage.getItem('sidebarCollapsed');
    if (savedState === 'true' && window.innerWidth >= 768) {
        // Apply collapsed state
        sideNav.classList.add('collapsed');
        sideNav.style.width = '75px';
        
        // Hide texts and switch logo
        const navTexts = document.querySelectorAll('.nav-text');
        const logo = sideNav.querySelector('img');
        const navSpans = document.querySelectorAll('#side-nav-bar span.flex.flex-row, #side-nav-bar button span.flex.flex-row');
        
        navTexts.forEach(text => {
            text.style.opacity = '0';
            text.style.display = 'none';
        });
        
        // Switch to logo without text
        if (logo) {
            logo.src = '/images/Dreamy_logo.png';
            logo.style.opacity = '1';
        }
        
        navSpans.forEach(span => {
            span.classList.remove('space-x-4');
            span.classList.add('space-x-0');
        });
        
        // Apply collapsed height settings
        if (navContainer) {
            navContainer.style.maxHeight = 'none';
            navContainer.style.height = 'auto';
            navContainer.style.minHeight = '500px';
        }
        
        const navLinksContainer = document.querySelector('#nav-container .h-\\[700px\\]');
        if (navLinksContainer) {
            navLinksContainer.style.maxHeight = 'none';
            navLinksContainer.style.height = 'auto';
            navLinksContainer.style.minHeight = '500px';
        }
        
        // Adjust content width
        content.style.width = 'calc(100% - 75px)';
        
        // Increase main content padding when collapsed
        const mainContent = document.getElementById('main-content');
        if (mainContent) {
            // Remove px-10 and add px-24
            mainContent.classList.remove('px-10');
            mainContent.classList.add('px-24');
        }
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
                sideNav.style.width = '260px';
                sideNav.classList.remove('collapsed');
                
                // Show texts with a short delay to create a smooth transition
                setTimeout(() => {
                    navTexts.forEach(text => {
                        text.style.display = 'block';
                        text.style.opacity = '1';
                    });
                    
                    // Switch back to logo with text
                    logo.src = '/images/dreamy_logo2.png';
                    logo.style.opacity = '1';
                    
                    // Restore original spacing
                    navSpans.forEach(span => {
                        span.classList.remove('space-x-0');
                        span.classList.add('space-x-4');
                    });
                    
                    // Restore original navigation container height when expanding
                    const navContainer = document.getElementById('nav-container');
                    const navLinksContainer = document.querySelector('#nav-container .h-\\[700px\\]');
                    
                    if (navContainer) {
                        navContainer.style.maxHeight = '';
                        navContainer.style.height = '';
                        navContainer.style.minHeight = '';
                    }
                    
                    if (navLinksContainer) {
                        navLinksContainer.style.maxHeight = '';
                        navLinksContainer.style.height = '';
                        navLinksContainer.style.minHeight = '';
                    }
                    
                    // Remove all inline height styles from child elements
                    const allChildren = navContainer.querySelectorAll('*');
                    allChildren.forEach(child => {
                        child.style.maxHeight = '';
                        child.style.height = '';
                        child.style.minHeight = '';
                    });
                    
                    // Restore original main content padding when expanded
                    const mainContent = document.getElementById('main-content');
                    if (mainContent) {
                        // Remove px-24 and restore px-10
                        mainContent.classList.remove('px-24');
                        mainContent.classList.add('px-10');
                    }
                }, 200);
            } else {
                // Collapse
                sideNav.classList.add('collapsed');
                sideNav.style.width = '75px';
                
                // Hide texts immediately and switch logo
                navTexts.forEach(text => {
                    text.style.opacity = '0';
                    setTimeout(() => {
                        text.style.display = 'none';
                    }, 200);
                });
                
                // Switch to logo without text
                logo.src = '/images/Dreamy_logo.png';
                logo.style.opacity = '1';
                
                // Remove spacing that causes overflow
                navSpans.forEach(span => {
                    span.classList.remove('space-x-4');
                    span.classList.add('space-x-0');
                });
                
                // Remove ALL height restrictions when collapsed
                const navContainer = document.getElementById('nav-container');
                const navLinksContainer = document.querySelector('#nav-container .h-\\[700px\\]');
                
                if (navContainer) {
                    navContainer.style.maxHeight = 'none';
                    navContainer.style.height = 'auto';
                    navContainer.style.minHeight = '500px';
                }
                
                if (navLinksContainer) {
                    navLinksContainer.style.maxHeight = 'none';
                    navLinksContainer.style.height = 'auto';
                    navLinksContainer.style.minHeight = '500px';
                }
                
                // Remove height restrictions from all child elements
                const allChildren = navContainer.querySelectorAll('*');
                allChildren.forEach(child => {
                    child.style.maxHeight = 'none';
                    child.style.height = 'auto';
                });
                
                // Increase main content padding when collapsed
                const mainContent = document.getElementById('main-content');
                if (mainContent) {
                    // Remove px-10 and add px-24
                    mainContent.classList.remove('px-10');
                    mainContent.classList.add('px-24');
                }
            }
            
            // Adjust content width and ensure it takes full available space
            content.style.width = sideNav.classList.contains('collapsed') ? 'calc(100% - 75px)' : 'calc(100% - 260px)';
            
            // Save state to localStorage
            localStorage.setItem('sidebarCollapsed', sideNav.classList.contains('collapsed'));
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
