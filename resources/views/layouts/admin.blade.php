<!DOCTYPE html>
<html lang="en">
<x-head></x-head>

<body class="font-sans">

    <div id="main-container" class="min-h-screen hidden md:flex">

        <x-side-nav-bar>
            <x-slot name="logo">
                {{ asset('images/systemicon.png') }}
            </x-slot>
            <div class="flex flex-col space-y-1">
                <label class="text-white/30 text-[14px] nav-text">Menu</label>
                <x-nav-link href="/admission" :active="request()->is('admission')">

                    <span class="flex flex-row items-center space-x-4">
                        <i class="fi fi-rs-chart-simple text-[20px] flex-shrink-0"></i>
                        <p class="font-semibold text-[16px] nav-text truncate">Dashboard</p>
                    </span>

                </x-nav-link>
                <x-nav-link href="/admission/status" :active="request()->is('admission/status')">

                    <span class="flex flex-row items-center space-x-4">
                        <i class="fi fi-rs-memo-circle-check text-[20px] flex-shrink-0"></i>
                        <p class="font-semibold text-[16px] nav-text truncate">Applications</p>
                    </span>

                </x-nav-link>
            </div>
        </x-side-nav-bar>

        <!-- Main content area -->
        <div id="content" class="flex-1 flex flex-col">
            <!-- Top Navigation Bar -->
            <header id="top-nav-bar" class="bg-[#f8f8f8] border-b border-[#1e1e1e]/20 p-4 flex justify-between items-center gap-2">
                <!-- profile icon, notifications, etc. -->
                <button id="sidebar-toggle-button" class="flex flex-row space-x-4 hover:bg-[#e0e0e0] p-2 rounded-md transition-all duration-150">
                    <i class="fi fi-rs-sidebar-flip text-[20px]"></i>
                </button>
                
                <span class="flex flex-row space-x-4">
                    <i class="fi fi-rs-bell text-[20px]"></i>
                    <i class="fi fi-rs-user text-[20px]"></i>
                </span>
            </header>

            <!-- Main Content -->
            <main id="main-content" class="flex-1 p-6 overflow-auto bg-blue-500">
                <section>
                    <header class="mb-6">
                        @yield('header')
                    </header>
                    @yield('content')
                </section>
            </main>
        </div>
    </div>

</body>

</html>
