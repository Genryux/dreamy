<!DOCTYPE html>
<html lang="en">
<x-head></x-head>

<body class="bg-[#E4EAF9]">

    <div id="main-container" class="min-h-screen hidden md:flex relative">

        @yield('modal')

        <x-side-nav-bar>
            <x-slot name="logo">
                {{ asset('images/systemicon.png') }}
            </x-slot>
            <div class="flex flex-col space-y-2">
                <x-divider color='#f8f8f8' opacity="0.10"></x-divider>
                <x-nav-link href="/admin" :active="request()->is('admin')">

                    <span class="flex flex-row items-center space-x-4">
                        <i class="fi fi-rs-chart-simple text-[20px] flex-shrink-0"></i>
                        <p class="font-semibold text-[16px] nav-text truncate">Dashboard</p>
                    </span>

                </x-nav-link>

                <div id="dropdown-button"
                    class="cursor-pointer overflow-hidden ease-in-out duration-150 h-[40px] transition-all rounded-md">
                    <div class="px-4 w-full h-[41px] flex flex-row items-center space-x-4 hover:bg-[#199BCF]/30">
                        <div class="w-full h-[4px] flex flex-row items-center space-x-4 text-gray-300/80">
                            <i
                                class="fi fi-rs-memo-circle-check text-[20px] flex justify-center items-center flex-shrink-0"></i>
                            <p class="font-semibold text-[16px] nav-text truncate select-none">Applications</p>
                        </div>
                        <div>
                            <i class="fi fi-rs-angle-small-down text-[16px] text-white/60"></i>
                        </div>
                    </div>

                    <div id="flex" class="flex flex-col space-y-1 mt-1">
                        <x-nav-link href="/pending-applications" :active="request()->is('pending')">

                            <span class="">
                                <p class="px-8 mx-2 font-semibold text-[16px] nav-text truncate">Pending Applications
                                </p>
                            </span>

                        </x-nav-link>
                        <x-nav-link href="/selected-applications" :active="request()->is('selected')">

                            <span class="">
                                <p class="px-8 mx-2 font-semibold text-[16px] nav-text truncate">Selected Applications
                                </p>
                            </span>

                        </x-nav-link>
                        <x-nav-link href="/pending-documents" :active="request()->is('documents')">

                            <span class="">
                                <p class="px-8 mx-2 font-semibold text-[16px] nav-text truncate">Pending Documents</p>
                            </span>

                        </x-nav-link>


                    </div>

                </div>

            </div>
            <div class="flex flex-col space-y-2 mt-10">
                <span class='flex items-center mt-4'>
                    <span class="h-[0.9px] flex-1 bg-[#f8f8f8]/20"></span>
                </span>
                <x-nav-link href="/admin" :active="request()->is('enrolled-students')">

                    <span class="flex flex-row items-center space-x-4">
                        <i
                            class="fi fi-rs-graduation-cap flex justify-center items-center text-[20px] flex-shrink-0"></i>

                        <p class="font-semibold text-[16px] nav-text truncate">Enrolled Students</p>
                    </span>

                </x-nav-link>
            </div>
        </x-side-nav-bar>

        <!-- Main content area -->
        <div id="content" class="flex-1 flex flex-col transition-all duration-300 w-full">
            <!-- Top Navigation Bar -->
            <header id="top-nav-bar"
                class="z-10 bg-[#f8f8f8] h-[60px] px-[10px] flex justify-between items-center gap-2 sticky top-0 shadow-sm border-b border-[#1e1e1e]/15">
                <!-- profile icon, notifications, etc. -->
                <button id="sidebar-toggle-button"
                    class="flex flex-row py-2 px-2 hover:bg-[#e0e0e0] rounded-md transition-all duration-150">
                    <i class="fi fi-rs-sidebar-flip text-[20px]"></i>
                </button>

                <span class="flex flex-row space-x-4">
                    <i class="fi fi-rs-bell text-[20px]"></i>
                    <i class="fi fi-rs-user text-[20px]"></i>
                </span>
            </header>

            <!-- Main Content -->
            <main id="main-content"
                class="flex-1 py-4 px-6 relative overflow-auto h-full flex flex-col text-[#0f111c] relative space-y-4">

                @yield('breadcrumbs')
                @yield('dashboard-acad-term')
                @yield('header')
                @yield('stat')

                {{-- <section class="bg-[#f8f8f8] flex flex-col rounded-xl border shadow-sm border-[#1e1e1e]/15"> --}}

                @yield('content')


                {{-- </section> --}}
                @yield('ongoing-interviews')
                @yield('docs_submission_progress')

            </main>
        </div>
    </div>

    @stack('scripts')
</body>

</html>
