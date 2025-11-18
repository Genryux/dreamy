<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<x-head :title="$title ?? 'Dreamy School'"></x-head>

<body class="relative font-sans antialiased bg-[#1A3165] scroll-smooth">
    {{-- 
        @if (Route::has('login'))
            
            <nav class="-mx-3 flex flex-1 justify-end">
                @auth

                    <form action="/logout" method="POST">
                        @csrf
                        @method('delete')
                        <button>Logout</button>
                    </form>
                @else
                    <a
                        href="{{ route('login') }}"
                        class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                    >
                        Log in
                    </a>

                    @if (Route::has('register'))
                        <a
                            href="{{ route('register') }}"
                            class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                        >
                            Register
                        </a>
                    @endif
                @endauth
            </nav>
            
        @endif --}}

    <main class="flex flex-col justify-center items-center h-full w-full relative overflow-hidden scroll-smooth">
        {{-- heading --}}
        @unless (Request::is('portal/login') ||
                Request::is('portal/register') ||
                Request::is('email/verify*') ||
                Request::is('forgot-password') ||
                Request::is('reset-password*') ||
                Request::is('password-reset-success'))
            <div id="header"
                class="fixed top-0 flex flex-row justify-between items-center w-full px-4 md:px-[120px] py-4 z-20 transition-all duration-500 ease-in-out">
                <!-- Logo and Admin Link -->
                <div class="flex flex-row justify-center items-center gap-2 md:gap-4">
                    <img id="logo" src="{{ asset('images/Dreamy_logo.png') }}"
                        class="size-[60px] md:size-[120px] transition-all duration-500 ease-in-out" alt="">
                    @auth
                        @if (auth()->user()->hasRole(['registrar', 'super_admin']))
                            <a href="/admin"
                                class="hidden md:block text-white text-[20px] font-bold hover:text-[#C8A165] transition-colors duration-200">Admin
                                Portal</a>
                        @else
                            <a href="/admission"
                                class="hidden md:block text-white text-[20px] font-bold hover:text-[#C8A165] transition-colors duration-200">Admission</a>
                        @endif
                    @endauth
                </div>

                <!-- Spacer for desktop -->
                <div class="hidden md:block flex-1"></div>

                <!-- Desktop Navigation -->
                <div id="nav-links"
                    class="hidden md:flex flex-row justify-evenly items-center text-[20px] gap-16 text-white font-bold">
                    <a href="/" class="hover:text-[#C8A165] transition-colors duration-200">Home</a>
                    <a href="#about" class="hover:text-[#C8A165] transition-colors duration-200">About</a>
                    @auth
                    @else
                        <a href="/portal/login" class="hover:text-[#C8A165] transition-colors duration-200">Portal</a>
                    @endauth
                    <a href="/news" class="hover:text-[#C8A165] transition-colors duration-200">News</a>
                    <a href="#contact" class="hover:text-[#C8A165] transition-colors duration-200">Contact</a>
                </div>

                <!-- Mobile Menu Button -->
                <button id="mobile-menu-button"
                    class="md:hidden flex flex-col justify-center items-center w-8 h-8 text-white">
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                </button>
            </div>

            <!-- Mobile Navigation Menu -->
            <div id="mobile-menu"
                class="fixed top-0 left-0 w-full h-full bg-[#1A3165] z-30 transform -translate-x-full transition-transform duration-300 ease-in-out md:hidden">
                <!-- Close Button -->
                <div class="flex justify-end p-6">
                    <button id="mobile-menu-close"
                        class="text-white text-3xl font-bold hover:text-[#C8A165] transition-colors duration-200">
                        ×
                    </button>
                </div>

                <div class="flex flex-col justify-start items-center px-8">
                    <!-- Mobile Logo -->
                    <img src="{{ asset('images/Dreamy_logo.png') }}" class="size-[100px] mb-8" alt="">

                    <!-- Mobile Navigation Links -->
                    <div class="flex flex-col space-y-6 text-center">
                        <a href="/"
                            class="text-white text-[24px] font-bold hover:text-[#C8A165] transition-colors duration-200">Home</a>
                        <a href="#section2"
                            class="text-white text-[24px] font-bold hover:text-[#C8A165] transition-colors duration-200">About</a>
                        @auth
                            @if (auth()->user()->hasRole(['registrar', 'super_admin']))
                                <a href="/admin"
                                    class="text-white text-[24px] font-bold hover:text-[#C8A165] transition-colors duration-200">Admin
                                    Portal</a>
                            @else
                                <a href="/admission"
                                    class="text-white text-[24px] font-bold hover:text-[#C8A165] transition-colors duration-200">Admission</a>
                            @endif
                        @else
                            <a href="/portal/login"
                                class="text-white text-[24px] font-bold hover:text-[#C8A165] transition-colors duration-200">Admission</a>
                        @endauth
                        <a href="/news"
                            class="text-white text-[24px] font-bold hover:text-[#C8A165] transition-colors duration-200">News</a>
                        <a href="#section7"
                            class="text-white text-[24px] font-bold hover:text-[#C8A165] transition-colors duration-200">Contact</a>
                    </div>
                </div>
            </div>
        @endunless

        @yield('login_page')
        @yield('section_1')
        @yield('section_2')
        @yield('section_3')
        @yield('section_4')
        @yield('section_5')
        @yield('section_6')
        @yield('section_7')
        @yield('footer')

    </main>

    <style>
        /* Header scroll animation styles */
        #header {
            backdrop-filter: blur(0px);
            background: transparent;
        }

        #header.scrolled {
            background: linear-gradient(0deg, rgba(42, 123, 155, 0) 0%, rgba(26, 49, 101, 1) 100%);
            padding: 0.5rem 7.5rem;
            /* py-2 px-[120px] - reduced y padding */
            height: auto;
            /* Allow height to adjust with padding */
        }

        #logo {
            transform: scale(1);
        }

        #logo.scrolled {
            transform: scale(0.7);
        }

        #nav-links {
            font-size: 1.25rem;
            /* text-[20px] - keep consistent */
        }

        /* Smooth transitions for header and logo only */
        #header,
        #logo {
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Hamburger menu styles */
        .hamburger-line {
            width: 25px;
            height: 3px;
            background-color: white;
            margin: 3px 0;
            transition: 0.3s;
            border-radius: 2px;
        }

        #mobile-menu-button.active .hamburger-line:nth-child(1) {
            transform: rotate(-45deg) translate(-5px, 6px);
        }

        #mobile-menu-button.active .hamburger-line:nth-child(2) {
            opacity: 0;
        }

        #mobile-menu-button.active .hamburger-line:nth-child(3) {
            transform: rotate(45deg) translate(-5px, -6px);
        }

        /* Mobile menu overlay */
        #mobile-menu.show {
            transform: translateX(0);
        }
    </style>

    <script>
        // Header scroll animation
        document.addEventListener('DOMContentLoaded', function() {
            const header = document.getElementById('header');
            const logo = document.getElementById('logo');
            const navLinks = document.getElementById('nav-links');

            let lastScrollTop = 0;
            let ticking = false;

            function updateHeader() {
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

                if (scrollTop > 100) {
                    // Scrolled down - add compact styles
                    header.classList.add('scrolled');
                    logo.classList.add('scrolled');
                } else {
                    // At top - remove compact styles
                    header.classList.remove('scrolled');
                    logo.classList.remove('scrolled');
                }

                lastScrollTop = scrollTop;
                ticking = false;
            }

            function requestTick() {
                if (!ticking) {
                    requestAnimationFrame(updateHeader);
                    ticking = true;
                }
            }

            // Listen for scroll events
            window.addEventListener('scroll', requestTick, {
                passive: true
            });

            // Initial check
            updateHeader();

            // Mobile menu functionality
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            const mobileMenuClose = document.getElementById('mobile-menu-close');

            function closeMobileMenu() {
                mobileMenuButton.classList.remove('active');
                mobileMenu.classList.remove('show');
            }

            function openMobileMenu() {
                mobileMenuButton.classList.add('active');
                mobileMenu.classList.add('show');
            }

            if (mobileMenuButton && mobileMenu) {
                // Open menu when hamburger is clicked
                mobileMenuButton.addEventListener('click', function() {
                    if (mobileMenu.classList.contains('show')) {
                        closeMobileMenu();
                    } else {
                        openMobileMenu();
                    }
                });

                // Close menu when X button is clicked
                if (mobileMenuClose) {
                    mobileMenuClose.addEventListener('click', closeMobileMenu);
                }

                // Close mobile menu when clicking on links
                const mobileLinks = mobileMenu.querySelectorAll('a');
                mobileLinks.forEach(link => {
                    link.addEventListener('click', closeMobileMenu);
                });

                // Close mobile menu when clicking outside
                document.addEventListener('click', function(event) {
                    if (!mobileMenuButton.contains(event.target) && !mobileMenu.contains(event.target)) {
                        closeMobileMenu();
                    }
                });
            }
        });
    </script>
</body>

</html>
