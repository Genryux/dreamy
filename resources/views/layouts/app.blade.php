<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<x-head></x-head>

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
                class="fixed top-0 flex flex-row justify-between items-center w-full px-[120px] py-4 z-20 transition-all duration-500 ease-in-out">
                <div class="flex flex-row justify-center items-center gap-4">
                    <img id="logo" src="{{ asset('images/Dreamy_logo.png') }}"
                        class="size-[120px] transition-all duration-500 ease-in-out" alt="">
                    @auth
                        @if (auth()->user()->hasRole(['registrar', 'super_admin']))
                            <a href="/admin"
                                class="text-white text-[20px] font-bold hover:text-[#C8A165] transition-colors duration-200">Admin
                                Portal</a>
                        @else
                            <a href="/admission" class="hover:text-[#C8A165] transition-colors duration-200">Admission</a>
                        @endif
                    @endauth
                </div>
                <div class="flex-1"></div>
                <div id="nav-links"
                    class="flex-1 flex flex-row justify-evenly items-center text-[20px] text-white font-bold w-full">
                    <a href="/" class="hover:text-[#C8A165] transition-colors duration-200">Home</a>
                    <a href="#section2" class="hover:text-[#C8A165] transition-colors duration-200">About</a>
                    @auth
                    @else
                        <a href="/portal/login" class="hover:text-[#C8A165] transition-colors duration-200">Admission</a>
                    @endauth
                    <a href="/news" class="hover:text-[#C8A165] transition-colors duration-200">News</a>
                    <a href="#section7" class="hover:text-[#C8A165] transition-colors duration-200">Contact</a>
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
        });
    </script>
</body>

</html>
