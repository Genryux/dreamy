<!DOCTYPE html>
<html lang="en">
<x-head></x-head>

<body class="font-sans bg-[#D9E2F5]">

    <div id="main-container" class="min-h-screen hidden md:flex">

        <x-side-nav-bar>
            <x-slot name="logo">
                {{ asset('images/admportal.png') }}
            </x-slot>
            <div class="flex flex-col space-y-1">
                <label class="text-white/30 text-[14px]">Menu</label>
                <x-nav-link href="/admission" :active="request()->is('admission')">

                    <span class="flex flex-row space-x-4">
                        <i class="fi fi-ss-chart-simple text-[20px]"></i>
                        <p class="font-semibold text-[16px]">Dashboard</p>
                    </span>

                </x-nav-link>
                <x-nav-link href="/admission/status" :active="request()->is('admission/status')">

                    <span class="flex flex-row space-x-4">
                        <i class="fi fi-ss-pending text-[20px]"></i>
                        <p class="font-semibold text-[16px]">Application Status</p>
                    </span>

                </x-nav-link>
            </div>
        </x-side-nav-bar>

        <!-- Main content area -->
        <div id="content" class="flex-1 flex flex-col">
            <!-- Top Navigation Bar -->
            <header id="top-nav-bar" class="bg-[#f8f8f8] border-b border-[#1e1e1e]/20 p-4 flex justify-end items-center gap-2">
                <!-- profile icon, notifications, etc. -->
                <p>notifs icon here</p>
                <p>profile icon here</p>
            </header>

            <!-- Main Content -->
            <main id="main-content" class="flex-1 p-5 overflow-auto h-full">

                <header class="bg-[#f8f8f8] p-6 mb-3 rounded-md border border-[#1e1e1e]/20">
                    @yield('header')
                </header>
                <section class="bg-[#f8f8f8] p-6 rounded-md border border-[#1e1e1e]/20">

                    @yield('content')

                    
                    
                </section>

            </main>
        </div>
    </div>

</body>

</html>
