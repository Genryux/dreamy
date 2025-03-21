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
                <label class="text-white/30 text-[14px]">Menu</label>
                <x-nav-link href="/admission" :active="request()->is('admission')">

                    <span class="flex flex-row space-x-2">
                        <i class="fi fi-ss-chart-simple text-[20px]"></i>
                        <p class="font-semibold text-[16px]">Dashboard</p>
                    </span>

                </x-nav-link>
                <x-nav-link href="/admission/status" :active="request()->is('admission/status')">

                    <span class="flex flex-row space-x-2">
                        <i class="fi fi-ss-pending text-[20px]"></i>
                        <p class="font-semibold text-[16px]">Application Status</p>
                    </span>

                </x-nav-link>
            </div>
        </x-side-nav-bar>

        <!-- Main content area -->
        <div id="content" class="flex-1 flex flex-col">
            <!-- Top Navigation Bar -->
            <header id="top-nav-bar" class="bg-gray-200 p-4 flex justify-end items-center gap-2">
                <!-- profile icon, notifications, etc. -->
                <p>notifs icon here</p>
                <p>profile icon here</p>
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
