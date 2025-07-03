<!DOCTYPE html>
<html lang="en">
<x-head></x-head>

<body class="bg-[#1A3165]">

    <div id="main-container" class="min-h-screen flex flex-col md:flex-col pb-2">

        <header id="top-nav-bar" class=" flex justify-center items-center gap-2 p-4">

            <div>
                <a href="/admission" class="flex items-center space-x-2">
                    <img src="{{ asset('images/admportal.png') }}" alt="Logo" class="h-[80px]">
                </a>
            </div>

        </header>

        <main id="content" class="p-[10px] overflow-auto h-full flex flex-col justify-center items-center relative">



            @if ($applicant->application_status == null)
                <section class="bg-[#f8f8f8] flex flex-col rounded-md border border-[#1e1e1e]/20 md:w-[70%] justify-center ">
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

            @elseif ($applicant->application_status == 'Selected')

                <div class="flex flex-col justify-center items-center gap-2 md:w-[70%]">
                    @yield('status')
                    @yield('selected')
                </div>

            @elseif ($applicant->application_status == 'Pending-Documents')

                <div class="flex flex-col justify-center items-center gap-2 md:w-[70%]">
                    @yield('status')
                    @yield('pending-documents')
                </div>

            @endif




                

        </main>

        @stack('scripts')

    </div>
 
</body>

</html>
