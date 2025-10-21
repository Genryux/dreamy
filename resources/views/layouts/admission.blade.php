<!DOCTYPE html>
<html lang="en">
<x-head></x-head>

<body class="bg-[#1A3165]">

    <div id="main-container" class="relative min-h-screen flex flex-col md:flex-col pb-2">

        <header id="top-nav-bar" class=" flex justify-center items-center gap-2 p-4">
            <div>
                <a href="/admission" class="flex items-center space-x-2">
                    <img src="{{ asset('images/admportal.png') }}" alt="Logo" class="h-[80px]">
                </a>
            </div>

        </header>
        <span class="absolute top-5 right-10 p-2 flex flex-row space-x-4">
  
            <div class="flex justify-center items-center relative">
                <button id="user-button"
                    class="relative flex justify-center items-center p-1 w-[46px] h-[46px] bg-[#199BCF]/20 hover:bg-[#199BCF]/40 rounded-full transition-all duration-150">
                    <div
                        class="w-9 h-9 bg-gradient-to-br from-[#199BCF] to-[#C8A165] rounded-full flex items-center justify-center shadow-sm">
                        <span class="text-white text-sm font-semibold">
                            {{ strtoupper(substr(auth()->user()->first_name ?? 'U', 0, 1)) }}{{ strtoupper(substr(auth()->user()->last_name ?? 'N', 0, 1)) }}
                        </span>
                    </div>
                </button>

                {{-- User Dropdown --}}
                <div id="user-dropdown"
                    class="absolute right-0 top-12 mt-1 w-64 bg-white rounded-lg shadow-lg border border-gray-200 hidden z-50">
                    <div class="py-2">
                        {{-- User Profile --}}
                        <a href="#" class="flex items-center px-4 py-3 hover:bg-gray-50 transition-colors">
                            <div
                                class="flex-shrink-0 w-8 h-8 bg-gradient-to-br from-[#199BCF] to-[#C8A165] rounded-full flex items-center justify-center mr-3">
                                <span class="text-white text-sm font-semibold">
                                    {{ strtoupper(substr($applicant->first_name ?? 'U', 0, 1)) }}{{ strtoupper(substr($applicant->last_name ?? 'N', 0, 1)) }}
                                </span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">
                                    {{ $applicant->first_name ?? 'User' }} {{ $applicant->last_name ?? 'Name' }}
                                </p>
                                <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email ?? '-' }}</p>
                            </div>
                        </a>

                        {{-- Settings --}}
                        <a href="{{ route('profile.edit') }}"
                            class="flex items-center px-4 py-3 hover:bg-gray-50 transition-colors">
                            <div
                                class="flex-shrink-0 w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                                <i class="fi fi-rs-settings flex justify-center items-center text-gray-600 text-sm"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">Settings</p>
                            </div>
                        </a>

                        {{-- Logout --}}
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full flex items-center px-4 py-3 bg-red-50 text-red-500 hover:bg-red-100 transition duration-200 text-left">
                                <div
                                    class="flex-shrink-0 w-8 h-8 bg-red-200 rounded-full flex items-center justify-center mr-3">
                                    <i
                                        class="fi fi-rr-power flex justify-center items-center text-sm"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium">Log Out</p>
                                </div>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </span>
        <main id="content" class="p-[10px] overflow-auto h-full flex flex-col justify-center items-center relative">

            @yield('alert')


            @if ($applicant->application_status == null)
                <section
                    class="bg-[#f8f8f8] flex flex-col rounded-xl border border-[#1e1e1e]/20 md:w-[70%] justify-center ">
                    @yield('content')
                    <div class="self-center w-[80%] md:w-[60%] opacity-30">
                        <x-divider></x-divider>
                    </div>
                    @yield('summary')

                </section>
            @elseif ($applicant->application_status == 'Pending')
                <div class="flex flex-col justify-center items-center gap-2 md:w-[70%]">
                    @yield('status')
                    @yield('pending')
                </div>
            @elseif ($applicant->application_status == 'Accepted')
                <div class="flex flex-col justify-center items-center gap-2 md:w-[70%]">
                    @yield('status')
                    @yield('accepted')
                </div>
            @elseif ($applicant->application_status == 'Rejected')
                <div class="flex flex-col justify-center items-center gap-2 md:w-[70%]">
                    @yield('status')
                    @yield('Rejected')
                </div>
            @elseif ($applicant->application_status == 'Pending-Documents')
                <div class="flex flex-col justify-center items-center gap-2 md:w-[70%]">
                    @yield('status')
                    @yield('pending-documents')
                </div>
            @elseif ($applicant->application_status == 'Completed-Failed')
                <div class="flex flex-col justify-center items-center gap-2 md:w-[70%]">
                    @yield('status')
                    @yield('completed-failed')
                </div>
            @elseif ($applicant->application_status == 'Officially Enrolled')
                <div class="flex flex-col justify-center items-center gap-2 md:w-[70%]">
                    @yield('status')
                    @yield('officially-enrolled')
                </div>
            @endif






        </main>

        @stack('scripts')

    </div>
    <script type="module">
        document.addEventListener('DOMContentLoaded', function() {
            // User dropdown functionality
            const userButton = document.getElementById('user-button');
            const userDropdown = document.getElementById('user-dropdown');
            let isUserDropdownOpen = false;

            let isDropdownOpen = false;

            // User dropdown functionality
            if (userButton && userDropdown) {
                // Toggle user dropdown
                userButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    isUserDropdownOpen = !isUserDropdownOpen;
                    userDropdown.classList.toggle('hidden');

                    // Close notification dropdown if open
                    if (isDropdownOpen) {
                        notificationDropdown.classList.add('hidden');
                        isDropdownOpen = false;
                    }
                });

                // Close user dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!userButton.contains(e.target) && !userDropdown.contains(e.target)) {
                        userDropdown.classList.add('hidden');
                        isUserDropdownOpen = false;
                    }
                });

                // Handle dropdown item clicks
                const dropdownItems = userDropdown.querySelectorAll('a');
                dropdownItems.forEach(item => {
                    item.addEventListener('click', function(e) {
                        const text = this.querySelector('p').textContent.trim();

                        if (text === 'Log Out') {
                            // Let the form handle logout - don't prevent default
                            return;
                        }

                        // Allow Settings and View Profile to navigate normally
                        if (text.includes('Settings') || text.includes('View Profile')) {
                            // Let the link handle navigation - don't prevent default
                            return;
                        }

                        // Prevent default for other items
                        e.preventDefault();

                        // Close dropdown after action
                        userDropdown.classList.add('hidden');
                        isUserDropdownOpen = false;
                    });
                });
            }

        });
    </script>
</body>

</html>
