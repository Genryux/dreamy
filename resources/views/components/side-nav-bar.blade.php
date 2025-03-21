<aside id="side-nav-bar" class="relative bg-[#1A3165] text-white p-4 w-48 md:w-64 h-screen overflow-y-auto">
    <div class="h-22 flex items-center justify-center mb-4 pr-2">
        <img src="{{ $logo }}" alt="Enrollment System" class="h-[60%] w-[80%]">
    </div>
    <nav id="nav-link" class="flex flex-col space-y-2">
        {{ $slot }}
    </nav>

    <div class="absolute bottom-5 left-4 right-4 w-auto">
        <span class="flex items-center">
            <span class="h-px flex-1 bg-[#f8f8f8]/30"></span>
        </span>
        <span class="py-2">
            <p class="opacity-60 py-2">Logged In as: Applicant</p>
            <form action="">
                <button
                    class="w-full flex flex-row hover:bg-[#199BCF] hover:bg-opacity-[0.30] hover:text-white/80 hover:ring-1 hover:ring-blue-400/60 rounded-md px-3 py-1 space-x-2 transition-all duration-150 ease-in-out">
                    <i class="fi fi-ss-exit flex flex-row items-center opacity-80"></i>
                    <p class="pb-1 font-semibold opacity-80">Logout</p>

                </button>
            </form>
        </span>

    </div>

</aside>
