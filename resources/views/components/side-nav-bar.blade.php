<aside id="side-nav-bar" class="md:block fixed md:relative z-50 md:z-auto bg-[#1A3165] text-white py-4 px-4 w-[280px] md:w-62 lg:w-[280px] xl:w-[280px] h-screen overflow-y-auto transition-all duration-300">
    <div class="h-22 flex items-center justify-center mb-4 pr-2">
        <img src="{{ $logo }}" alt="Enrollment System" class="h-[60%] w-[80%] transition-opacity duration-300">
    </div>
    <nav id="nav-link" class="flex flex-col space-y-2">
        {{ $slot }}
    </nav>

    <div class="absolute bottom-5 left-4 right-4 w-auto">
        <span class="flex items-center">
            <span class="h-px flex-1 bg-[#f8f8f8]/30"></span>
        </span>
        
        <span class="py-2">
            <p class="opacity-60 py-2 whitespace-nowrap nav-text">Logged In as: Applicant</p>
            <form action="">
                <button
                    class="w-full flex flex-row hover:bg-[#199BCF] hover:bg-opacity-[0.30] hover:text-white/80 hover:ring-1 hover:ring-blue-400/60 rounded-md px-3 py-1 space-x-2 transition-all duration-150 ease-in-out">
                    <i class="fi fi-rs-exit text-[20px]"></i>
                    <p class="pb-1 font-semibold opacity-80 nav-text whitespace-nowrap">Logout</p>
                </button>
            </form>
        </span>
    </div>
</aside>

<!-- Overlay for mobile menu background -->
<div id="mobile-overlay" class="md:hidden fixed inset-0 bg-black bg-opacity-50 z-40"></div>
