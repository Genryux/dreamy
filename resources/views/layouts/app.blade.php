<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<x-head></x-head>

<body class="relative font-sans antialiased bg-[#1A3165]">
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

    <main class="flex flex-col justify-center items-center h-full w-full relative overflow-hidden">
        {{-- heading --}}
        @unless (Request::is('portal/login') || Request::is('portal/register') || Request::is('email/verify*') || Request::is('forgot-password') || Request::is('reset-password*') || Request::is('password-reset-success'))
            <div class="fixed top-0 flex flex-row justify-between items-center w-full px-[120px] py-4 z-10">
                <div>
                    <img src="{{ asset('images/Dreamy_logo.png') }}" class="size-[120px]" alt="">
                </div>
                <div class="flex-1"></div>
                <div class="flex-1 flex flex-row justify-evenly items-center text-[20px] text-white font-bold w-full">
                    <a href="/" class="hover:text-[#C8A165] transition-colors duration-200">Home</a>
                    <a href="#about" class="hover:text-[#C8A165] transition-colors duration-200">About</a>
                    <div>
                        <span>Academics</span>
                        <div>

                        </div>
                    </div>
                    <a href="/portal/login" class="hover:text-[#C8A165] transition-colors duration-200">Admission</a>
                    <a href="/news" class="hover:text-[#C8A165] transition-colors duration-200">News</a>
                    <a href="#contact" class="hover:text-[#C8A165] transition-colors duration-200">Contact</a>
                </div>
            </div>
        @endunless

        @yield('login_page')
        @yield('section_1')
        @yield('section_2')
        @yield('section_3')
    </main>
</body>

</html>
