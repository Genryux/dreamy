<!DOCTYPE html>
<html lang="en">
<x-head></x-head>

<body class="bg-[#1A3165]">

    <div id="main-container" class="min-h-screen flex flex-col md:flex-col">

        <header id="top-nav-bar" class=" flex justify-center items-center gap-2 p-4">

            <div>
                <a href="/admission" class="flex items-center space-x-2">
                    <img src="{{ asset('images/admportal.png') }}" alt="Logo" class="h-[80px]">
                </a>
            </div>

        </header>

        <main id="content" class="p-[10px] overflow-auto h-full flex flex-col justify-center items-center">

            <section class="bg-[#f8f8f8] flex flex-col rounded-md border border-[#1e1e1e]/20 md:w-[70%] justify-center ">

                {{-- @if ($activeEnrollmentPeriod) --}}
                    @yield('content')
                    <div class="self-center w-[80%] md:w-[60%] opacity-30">
                        <x-divider></x-divider>
                    </div>
                    @yield('summary')
                {{-- @else

                    <div class="text-center p-10 md:p-24 flex flex-col items-center gap-16">

                        <div>
                            <p id="db-text" class="text-[18px] md:text-[24px] font-bold">Enrollment for [School Year] has not yet started. <br> 
                            <p id="zspan" class="text-[16px] md:text-[18px] font-medium">Please stay tuned for further announcements and updates. We appreciate your patience!</p> </p>    
                        </div>
                        <div>
                            <img src="{{ asset('images/Waiting.png') }}" class="size-[200px] md:size-[300px]" alt="">
                        </div>
 
                    </div>
            
                @endif --}}


                
            </section>

        </main>

        @stack('scripts')
 
</body>

</html>
