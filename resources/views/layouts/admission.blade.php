<!DOCTYPE html>
<html lang="en">
<x-head></x-head>

<body class="bg-[#D9E2F5]">

    <div id="main-container" class="min-h-screen flex flex-col md:flex-row">

        <x-side-nav-bar>
            <x-slot name="logo">
                {{ asset('images/admportal.png') }}
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
                        <p class="font-semibold text-[16px] nav-text truncate">Application Status</p>
                    </span>
                </x-nav-link>
            </div>
        </x-side-nav-bar>

        <!-- Main content area -->
        <div id="content" class="flex-1 flex flex-col transition-all duration-300 w-full">
            <!-- Top Navigation Bar -->
            <header id="top-nav-bar" class="bg-[#f8f8f8] border-b h-[60px] border-[#1e1e1e]/20 px-[10px] flex justify-between items-center gap-2">
                <!-- profile icon, notifications, etc. -->
                <button id="sidebar-toggle-button" class="flex flex-row py-2 px-2 hover:bg-[#e0e0e0] rounded-md transition-all duration-150">
                    <i class="fi fi-rs-sidebar-flip text-[20px] hidden md:block"></i>
                    <i class="fi fi-rs-list text-[20px] md:hidden"></i>
                </button>
                
                <span class="flex flex-row space-x-4">
                    <i class="fi fi-rs-bell text-[20px]"></i>
                    <i class="fi fi-rs-user text-[20px]"></i>
                </span>
            </header>

            <!-- Main Content -->
            <main id="main-content" class="flex-1 p-[10px] overflow-auto h-full flex flex-col">

                <header class="bg-[#f8f8f8] px-[22px] py-[18px] mb-3 rounded-md border border-[#1e1e1e]/20">
                    @yield('header')
                </header>
                <section class="bg-[#f8f8f8] flex-1 flex flex-col rounded-md border border-[#1e1e1e]/20">

                    @yield('content')

                    
                    
                </section>

            </main>
        </div>
    </div>

</body>

</html>
