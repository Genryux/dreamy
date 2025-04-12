<!DOCTYPE html>
<html lang="en">
<x-head></x-head>

<body class="bg-[#D9E2F5]">

    <div id="main-container" class="min-h-screen hidden md:flex relative">


        <div id="modal-bg" class="absolute bottom-0 left-0 bg-[#0f111c]/40 h-0 w-full z-20 ease-in-out duration-150 overflow-hidden">

            <div class="flex items-center justify-center h-screen w-screen">

                <div id="modal" class="bg-[#f8f8f8] flex flex-col rounded-md w-[40%]" onclick="event.stopPropagation()">

                    <span class="px-4 py-2 flex flex-row items-center justify-between">
                        <p class="font-bold">Accept & Schedule Interview</p>
                        <i id="close-btn" class="fi fi-rs-cross-small text-[20px] flex items-center rounded-full cursor-pointer hover:ring hover:ring-[#1e1e1e]/15"></i>
                    </span>

                    <x-divider color="#1e1e1e" opacity="0.15"></x-divider>

                    <form action="" class="flex flex-col space-y-2 px-4 py-2">

                        <div class="flex flex-row space-x-2">
                            <div class="flex-1 space-y-1">
                                <label for="" class="text-[14px] font-bold">Date</label>
                                <div class="flex items-center px-2 py-2 rounded-md bg-[#E3ECFF] focus-within:ring-2 focus-within:ring-[#199BCF]/40 space-x-2">
                                    <i class="fi fi-rs-calendar-day flex items-center opacity-60"></i>
                                    <input type="date" name="" id="" class="bg-transparent outline-none font-medium text-[14px] w-full">
                                </div>
                                
                            </div >
                            <div class="flex-1 space-y-1">
                                <label for="" class="text-[14px] font-bold">Time</label>
                                <div class="flex items-center px-2 py-2 rounded-md bg-[#E3ECFF] focus-within:ring-2 focus-within:ring-[#199BCF]/40 space-x-2">
                                    <i class="fi fi-rs-clock-five flex items-center opacity-60"></i>
                                    <input type="time" name="" id="" class="bg-transparent outline-none font-medium text-[14px] w-full">
                                </div>
                                
                            </div>
                            <div class="flex-1 space-y-1">
                                <label for="" class="text-[14px] font-bold">Location</label>
                                <div class="flex items-center px-2 py-2 rounded-md bg-[#E3ECFF] focus-within:ring-2 focus-within:ring-[#199BCF]/40 space-x-2">
                                    <i class="fi fi-rs-marker flex items-center opacity-60"></i>
                                    <input type="text" name="" id="" class="bg-transparent outline-none font-medium text-[14px] w-full">
                                </div>
                            </div>
                        </div>

                        <div class="flex-1 space-y-1">
                            <label for="" class="text-[14px] font-bold">Assign to</label>
                            <div class="flex items-center px-2 py-2 rounded-md bg-[#E3ECFF] w-2/3 focus-within:ring-2 focus-within:ring-[#199BCF]/40 space-x-2">
                                <i class="fi fi-rs-user flex items-center opacity-60"></i>
                                <select name="" id="" class="bg-transparent outline-none font-medium text-[14px] w-full">
                                    <option value="" class="font-Manrope">Juan Dela Cruz</option>
                                    <option value="">Peter Dela Cruz</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex-1 space-y-1">
                            <label for="" class="text-[14px] font-bold">Additional Information</label>
                            <div class="flex items-center px-2 py-2 rounded-md bg-[#E3ECFF] focus-within:ring-2 focus-within:ring-[#199BCF]/40 space-x-2">
                                <i class="fi fi-rs-info flex items-center opacity-60"></i>
                                <textarea name="" id="" cols="10" rows="10" class="bg-transparent outline-none font-medium text-[14px] w-full resize-none h-[100px]"></textarea>
                            </div>
                        </div>

                    </form>

                    <x-divider color="#1e1e1e" opacity="0.15"></x-divider>

                    <div class="flex justify-end px-4 py-3 space-x-1">
                        <button id="cancel-btn" class="border border-[#1e1e1e]/15 text-[14px] px-2 py-1 rounded-md text-[#0f111c]/80 font-bold">Cancel</button>
                        <button class="bg-[#199BCF] text-[14px] px-2 py-1 rounded-md text-[#f8f8f8] font-bold">Confirm</button>
                    </div>

                </div>
                
            </div>
        
        </div>

        <x-side-nav-bar>
            <x-slot name="logo">
                {{ asset('images/systemicon.png') }}
            </x-slot>
            <div class="flex flex-col space-y-1">
                <x-divider color='#f8f8f8' opacity="0.30"></x-divider>
                <label class="text-white/30 text-[14px] nav-text">Menu</label>
                <x-nav-link href="/admin" :active="request()->is('admin')">

                    <span class="flex flex-row items-center space-x-4">
                        <i class="fi fi-rs-chart-simple text-[20px] flex-shrink-0"></i>
                        <p class="font-semibold text-[16px] nav-text truncate">Dashboard</p>
                    </span>

                </x-nav-link>

                <div id="dropdown-button" class="cursor-pointer overflow-hidden ease-in-out duration-150 h-[40px] transition-all rounded-md">
                    <div class="px-3 w-full h-[41px] flex flex-row items-center space-x-4 hover:bg-[#199BCF]/30">
                        <div class="w-full h-[4px] flex flex-row items-center space-x-4 text-gray-300/80">
                            <i class="fi fi-rs-memo-circle-check text-[20px] flex-shrink-0"></i>
                            <p class="font-semibold text-[16px] nav-text truncate select-none">Applications</p>
                        </div>
                        <div>
                            <i class="fi fi-rs-angle-small-down text-[16px] text-white/60"></i>
                        </div>
                    </div>

                    <div id="flex" class="flex flex-col space-y-1 mt-1">
                        <x-nav-link href="/pending-applications" :active="request()->is('pending*')">

                            <span class="">
                                <p class="px-7 mx-2 font-semibold text-[16px] nav-text truncate">Pending Applications</p>
                            </span>
        
                        </x-nav-link>
                        <x-nav-link href="/admin" :active="request()->is('selected')">

                            <span class="">
                                <p class="px-7 mx-2 font-semibold text-[16px] nav-text truncate">Selected Applications</p>
                            </span>
        
                        </x-nav-link>
                        <x-nav-link href="/admin" :active="request()->is('rejected')">

                            <span class="">
                                <p class="px-7 mx-2 font-semibold text-[16px] nav-text truncate">Rejected Applications</p>
                            </span>
        
                        </x-nav-link>
                        <x-nav-link href="/admin" :active="request()->is('pending.docu')">

                            <span class="">
                                <p class="px-7 mx-2 font-semibold text-[16px] nav-text truncate">Dashboard</p>
                            </span>
        
                        </x-nav-link>

                    </div>
                    
                </div>
            </div>
            <div class="flex flex-col space-y-1 mt-10">
                <span class='flex items-center mt-4'>
                    <span class="h-[0.9px] flex-1 bg-[#f8f8f8]/30"></span>
                </span>
                <label class="text-white/30 text-[14px] nav-text">Student</label>
            </div>
        </x-side-nav-bar>

        <!-- Main content area -->
        <div id="content" class="flex-1 flex flex-col transition-all duration-300 w-full">
            <!-- Top Navigation Bar -->
            <header id="top-nav-bar" class="z-10 bg-[#f8f8f8] border-b h-[60px] border-[#1e1e1e]/20 px-[10px] flex justify-between items-center gap-2 sticky top-0">
                <!-- profile icon, notifications, etc. -->
                <button id="sidebar-toggle-button" class="flex flex-row py-2 px-2 hover:bg-[#e0e0e0] rounded-md transition-all duration-150">
                    <i class="fi fi-rs-sidebar-flip text-[20px]"></i>
                </button>
                
                <span class="flex flex-row space-x-4">
                    <i class="fi fi-rs-bell text-[20px]"></i>
                    <i class="fi fi-rs-user text-[20px]"></i>
                </span>
            </header>

            <!-- Main Content -->
            <main id="main-content" class="flex-1 p-[10px] relative overflow-auto h-full flex flex-col text-[#0f111c] relative">

                @yield('breadcrumbs')

                <header class="bg-[#f8f8f8] mb-2 rounded-md border border-[#1e1e1e]/20">
                    @yield('header')
                </header>
                <section class="bg-[#f8f8f8] flex flex-col rounded-md border border-[#1e1e1e]/20">

                    @yield('content')

                    
                    
                </section>

            </main>
        </div>
    </div>

    @stack('scripts')
</body>

</html>
