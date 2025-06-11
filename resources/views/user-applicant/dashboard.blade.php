@extends('layouts.admission')


@section('content')
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif


    <div class="flex flex-col h-full py-8 px-6 md:px-2 gap-6">
        <div class="text-center">
            @if ($activeEnrollmentPeriod->status == 'Ongoing')
                <p id="db-text" class="text-[18px] md:text-[24px] font-bold">Welcome to Dreamy School' Online Registration for 2025 <br> <p id="zspan" class="text-[16px] md:text-[18px] font-medium">Please click the button below to fill out the form.</p> </p>
            @elseif ($activeEnrollmentPeriod->status == 'Paused')
                <p id="db-text" class="text-[18px] md:text-[20px] font-bold">We would like to inform you that the enrollment period is currently closed. At this time, we are not accepting any new applications.</p>
                    
            @else
            <p id="db-text" class="text-[18px] md:text-[20px] font-bold">Enrollment period is closed and will no longer accept any applications!</p>   
            @endif
        </div>

        <div id="btn-container" class="flex flex-col justify-center items-center flex-grow m-8">
            @if ($activeEnrollmentPeriod)
            <x-nav-link href="/admission/application-form" class="bg-[#199BCF]/80 text-white px-6 py-3 rounded-full hover:bg-[#1689b8] transition-colors duration-200 backdrop-blur-sm shadow-lg">
                <p class="text-[16px] font-bold">Get Started</p>
            </x-nav-link>
            @endif

        </div>
    </div>
@endsection

@section('summary')

    <div class="flex flex-col md:flex-col justify-between items-center py-8 gap-4">

        <div class="flex flex-col space-y-2 justify-center items-center mb-4">
            <p class="text-[18px] font-semibold">Application Summary</p>
        </div>
        <div class="flex flex-col md:flex-row justify-center items-center space-y-4 md:space-y-0 md:space-x-16 w-full">
            <div class="flex flex-col space-y-2 justify-center items-center bg-[#E3ECFF] py-6 px-10 gap-2 rounded-md">
                <p class="md:text-[16px] font-semibold opacity-90">Total Registrations</p>
                <p class="md:text-[20px] font-black">500</p>
                <p class="md:text-[14px] opacity-60">Applications Received</p>   
            </div>
            <div class="flex flex-col space-y-2 justify-center items-center bg-[#E3ECFF] py-6 px-4 gap-2 rounded-md">
                <p class="md:text-[16px] font-semibold opacity-90">Successful Applicaticants</p>
                <p class="md:text-[20px] font-black">500</p>
                <p class="md:text-[14px] opacity-60">58% Acceptance rate</p> 
            </div>
        </div>
        <div class="flex flex-row mt-4">
            <p class="text-[16px] font-semibold opacity-70">Enrollment Period Status:</p><span class="opacity-0 select-none">a</span>
            <p class="text-[16px] text-[#34A853] font-semibold">{{ $activeEnrollmentPeriod->status}}</p>
        </div>

    </div>
    

@endsection

@push('scripts')
<script type="module">
    
    document.addEventListener('DOMContentLoaded', function() {

        console.log('Dashboard script loaded');

        window.Echo.channel('updating-enrollment-period-status').listen('EnrollmentPeriodStatusUpdated', (event) => {

            console.log(event);
            if (event.enrollmentPeriod.status == 'Paused') {

                document.getElementById('db-text').innerText = 'Enrollment period is temporarily closed. At this time, we are not accepting any new applications.';
                document.getElementById('zspan').innerText = "";
                document.getElementById('btn-container').classList.add('hidden');

            } else if (event.enrollmentPeriod.status == 'Closed') {

                document.getElementById('db-text').innerText = 'We would like to inform you that the enrollment period is currently closed. At this time, we are not accepting any new applications.';
                document.getElementById('btn-container').classList.add('hidden');

            } else if (event.enrollmentPeriod.status == 'Ongoing') {

                document.getElementById('db-text').innerText = "Welcome to Dreamy School' Online Registration for 2025";
                document.getElementById('zspan').innerText = "Please click the button below to fill out the form.";
                document.getElementById('btn-container').classList.remove('hidden');
                document.getElementById('btn-container').classList.add('flex');
            } 

        });

    });
</script>
@endpush