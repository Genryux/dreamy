@props(['role', 'logo'])

<aside id="side-nav-bar" class="md:block fixed md:relative z-50 md:z-auto bg-[#1A3165] text-white py-4 px-4 w-[260px] h-screen transition-all duration-300">
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
            <p class="opacity-60 py-2 whitespace-nowrap nav-text">Logged In as: {{ $role ?? '-'}}</p>
        </span>
    </div>
</aside>

<!-- Overlay for mobile menu background -->
<div id="mobile-overlay" class="md:hidden fixed inset-0 bg-black bg-opacity-50 z-40"></div>
