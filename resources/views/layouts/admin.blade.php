<!DOCTYPE html>
<html lang="en">
<x-head></x-head>

<body class="bg-[#E4EAF9]/70 relative h-screen">
    @unless (Request::is('invoice/*'))
        @include('components.skeleton')
    @endunless
    <div id="main-container" class="h-full flex relative">

        @yield('modal')

        @unless (Request::is('invoice/*'))
            <x-side-nav-bar>
                <x-slot name="logo">
                    {{ asset('images/systemicon.png') }}
                </x-slot>
                <div class="max-h-[570px] overflow-y-scroll overflow-x-hidden flex flex-col justify-center items-start">
                    <div class="flex flex-col flex-1 space-y-2 w-full">
                        <x-divider color='#f8f8f8' opacity="0.10"></x-divider>
                        @role(['super_admin', 'registrar'])
                            <x-nav-link href="/admin" :active="request()->is('admin')">

                                <span class="flex flex-row items-center space-x-4">
                                    <i class="fi fi-rs-chart-simple text-[20px] flex-shrink-0"></i>
                                    <p class="font-semibold text-[16px] nav-text truncate">Enrollment Dashboard</p>
                                </span>

                            </x-nav-link>
                        @endrole
                        {{-- For head teacher --}}
                        @role('head_teacher')
                            <x-nav-link href="/head-teacher/dashboard" :active="request()->is('head-teacher/dashboard')">

                                <span class="flex flex-row items-center space-x-4">
                                    <i class="fi fi-rs-chart-simple text-[20px] flex-shrink-0"></i>
                                    <p class="font-semibold text-[16px] nav-text truncate">Home</p>
                                </span>

                            </x-nav-link>
                        @endrole
                        {{-- For teachers --}}
                        @role('teacher')
                            <x-nav-link href="/teacher/dashboard" :active="request()->is('teacher/dashboard')">

                                <span class="flex flex-row items-center space-x-4">
                                    <i class="fi fi-rs-chart-simple text-[20px] flex-shrink-0"></i>
                                    <p class="font-semibold text-[16px] nav-text truncate">Home</p>
                                </span>

                            </x-nav-link>
                        @endrole

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
                                        <p class="px-8 mx-2 font-semibold text-[16px] nav-text truncate">Pending
                                            Applications
                                        </p>
                                    </span>

                                </x-nav-link>
                                <x-nav-link href="/selected-applications" :active="request()->is('selected')">

                                    <span class="">
                                        <p class="px-8 mx-2 font-semibold text-[16px] nav-text truncate">Selected
                                            Applications
                                        </p>
                                    </span>

                                </x-nav-link>
                                <x-nav-link href="/pending-documents" :active="request()->is('documents')">

                                    <span class="">
                                        <p class="px-8 mx-2 font-semibold text-[16px] nav-text truncate">Pending Documents
                                        </p>
                                    </span>

                                </x-nav-link>


                            </div>


                        </div>
                        <span class='flex items-center mt-4'>
                            <span class="h-[0.9px] flex-1 bg-[#f8f8f8]/20"></span>
                        </span>
                    </div>

                    <div
                        class="flex flex-col space-y-2 flex-1 mt-2 max-h-[360px] w-full overflow-x-hidden overflow-y-scroll">

                        <x-nav-link href="/enrolled-students" :active="request()->is('enrolled-students')">

                            <span class="flex flex-row items-center space-x-4">
                                <i
                                    class="fi fi-rr-graduation-cap flex justify-center items-center text-[20px] flex-shrink-0"></i>

                                <p class="font-semibold text-[16px] nav-text truncate">Enrolled Students</p>
                            </span>

                        </x-nav-link>
                        <x-nav-link href="/admin" :active="request()->is('documents')">

                            <span class="flex flex-row items-center space-x-4">
                                <i class="fi fi-rr-document text-[20px] flex-shrink-0"></i>

                                <p class="font-semibold text-[16px] nav-text truncate">Documents</p>
                            </span>

                        </x-nav-link>
                        <x-nav-link href="/programs" :active="request()->is('programs')">

                            <span class="flex flex-row items-center space-x-4">
                                <i class="fi fi-rr-lesson text-[20px] flex-shrink-0"></i>
                                <p class="font-semibold text-[16px] nav-text truncate">Programs</p>
                            </span>

                        </x-nav-link>
                        <x-nav-link href="/sections" :active="request()->is('sections')">

                            <span class="flex flex-row items-center space-x-4">
                                <i class="fi fi-rr-users-class text-[20px] flex-shrink-0"></i>
                                <p class="font-semibold text-[16px] nav-text truncate">Sections</p>
                            </span>

                        </x-nav-link>
                        <x-nav-link href="/admin/users" :active="request()->is('admin/users*')">

                            <span class="flex flex-row items-center space-x-4">
                                <i class="fi fi-rr-envelope text-[20px] flex-shrink-0"></i>
                                <p class="font-semibold text-[16px] nav-text truncate">User Invitations</p>
                            </span>

                        </x-nav-link>
                        <x-nav-link href="/subjects" :active="request()->is('subjects')">

                            <span class="flex flex-row items-center space-x-4">
                                <i class="fi fi-rr-books text-[20px] flex-shrink-0"></i>
                                <p class="font-semibold text-[16px] nav-text truncate">Subjects</p>
                            </span>

                        </x-nav-link>
                        <x-nav-link href="/school-fees" :active="request()->is('school-fees')">

                            <span class="flex flex-row items-center space-x-4">
                                <i class="fi fi-rr-coins text-[20px] flex-shrink-0"></i>
                                <p class="font-semibold text-[16px] nav-text truncate">School Fees</p>
                            </span>

                        </x-nav-link>
                        <x-nav-link href="/homepage" :active="request()->is('homepage')">

                            <span class="flex flex-row items-center space-x-4">
                                <i class="fi fi-rr-books text-[20px] flex-shrink-0"></i>
                                <p class="font-semibold text-[16px] nav-text truncate">Site Management</p>
                            </span>

                        </x-nav-link>
                        <x-nav-link href="/admin/news" :active="request()->is('admin/news')">

                            <span class="flex flex-row items-center space-x-4">
                                <i class="fi fi-rr-newspaper text-[20px] flex-shrink-0"></i>
                                <p class="font-semibold text-[16px] nav-text truncate">News</p>
                            </span>

                        </x-nav-link>
                    </div>
                </div>


            </x-side-nav-bar>
        @endunless



        <!-- Main content area -->
        <div id="content" class="relative flex-1 flex flex-col transition-all duration-300 w-full">
            <!-- Top Navigation Bar -->
            @unless (Request::is('invoice/*'))
                <header id="top-nav-bar"
                    class="z-10 bg-[#f8f8f8] h-[60px] px-[10px] flex justify-between items-center gap-2 sticky top-0 shadow-sm border-b border-[#1e1e1e]/15">
                    <!-- profile icon, notifications, etc. -->
                    <button id="sidebar-toggle-button"
                        class="flex flex-row py-2 px-2 hover:bg-[#e0e0e0] rounded-md transition-all duration-150">
                        <i class="fi fi-rs-sidebar-flip text-[20px]"></i>
                    </button>
                    <button id="mobile-menu-button"
                        class="md:hidden flex flex-row py-2 px-2 hover:bg-[#e0e0e0] rounded-md transition-all duration-150">
                        <i class="fi fi-rs-menu-burger text-[20px]"></i>
                    </button>

                    <span class="flex flex-row space-x-4">
                        <i class="fi fi-rs-bell text-[20px]"></i>
                        <i class="fi fi-rs-user text-[20px]"></i>
                    </span>
                </header>
            @endunless

            <!-- Main Content -->
            <main id="main-content"
                class="flex-1 py-4 px-6 relative overflow-auto h-full flex flex-col text-[#0f111c] relative gap-y-4">

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

    <x-loader />

    <script>
        document.body.style.overflow = 'hidden';

        window.addEventListener('load', function() {

            const skeleton = document.getElementById('skeleton');
            if (!skeleton) return;

            skeleton.style.transition = 'opacity 0.5s';
            skeleton.style.opacity = 0;

            setTimeout(() => {
                skeleton.remove();
            }, 200);
        });
    </script>

    @stack('scripts')
</body>

</html>
