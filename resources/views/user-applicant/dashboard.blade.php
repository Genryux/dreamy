@extends('layouts.admission')


@section('header')
    <span class="flex flex-row space-x-4">
        <i class="fi fi-rs-chart-simple text-[20px]"></i>
        <p class="text-[18px] md:text-[20px] font-bold">Dashboard</p>
    </span>
@endsection


@section('content')
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif


    <div class="flex flex-col h-full">
        <div class="text-center border-b border-[#1e1e1e]/10 p-4 md:p-6">
            @if ($activeEnrollmentPeriod->status == 'Ongoing')
                <p id="db-text" class="text-[18px] md:text-[20px] font-bold">Welcome to Dreamy School admission portal!</p>
            @elseif ($activeEnrollmentPeriod->status == 'Paused')
                <p id="db-text" class="text-[18px] md:text-[20px] font-bold">Enrollment period is temporarily closed!</p>
                    
            @else
            <p id="db-text" class="text-[18px] md:text-[20px] font-bold">Enrollment period is closed and will no longer accept any applications!</p>   
            @endif


            
        </div>
        <div id="btn-container" class="flex flex-col justify-center items-center flex-grow p-4 md:p-6">
            @if ($activeEnrollmentPeriod)
            <x-nav-link href="/admission/application-form" class="bg-[#199BCF] text-white px-6 py-3 rounded-full hover:bg-[#1689b8] transition-colors duration-200">
                <p class="text-[16px] font-bold">Get Started</p>
            </x-nav-link>
            @endif

        </div>
    </div>
@endsection

@push('script')
<script type="module">
    document.addEventListener('DOMContentLoaded', function() {

        window.Echo.channel('updating-enrollment-period-status').listen('EnrollmentPeriodStatusUpdated', (event) => {

            console.log(event);
            if (event.enrollmentPeriod.status == 'Paused') {

                document.getElementById('db-text').innerText = 'Enrollment period is temporarily closed!';
                document.getElementById('btn-container').classList.add('hidden');

            } else if (event.enrollmentPeriod.status == 'Closed') {

                document.getElementById('db-text').innerText = 'Enrollment period is closed and will no longer accept any applications!';
                document.getElementById('btn-container').classList.add('hidden');

            } else if (event.enrollmentPeriod.status == 'Open') {

                document.getElementById('db-text').innerText = 'Welcome to Dreamy School admission portal!';
                document.getElementById('btn-container').classList.remove('hidden');
            } 

        });

    });
</script>
@endpush