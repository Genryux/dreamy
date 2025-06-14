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

            @if (!$currentAcadTerm)
                <p class="text-[18px] md:text-[20px] font-semibold p-10">
                    Enrollment is currently unavailable. Please check back soon or contact the admissions office for assistance.
                    <img src="{{ asset('images/unavailable.svg') }}" alt="" class="size-[200px] md:size-[300px] mx-auto mt-4">
                </p>
                </svg>
            @elseif ($currentAcadTerm && !$activeEnrollmentPeriod)
                {{-- Check if there was a past enrollment period for this term --}}
                @php
                    $latestClosedPeriod = $currentAcadTerm->enrollmentPeriods()
                        ->where('status', 'Closed')
                        ->latest('updated_at')
                        ->first();
                @endphp

                @if ($latestClosedPeriod)
                    <p class="text-[18px] md:text-[20px] font-medium p-10">
                        Enrollment for the academic year <i class="font-bold">{{ $currentAcadTerm->getFullNameAttribute() }}</i> has ended. We are no longer accepting new applications.
                    </p>
                @else
                    <p class="text-[18px] md:text-[20px] font-medium p-10">
                        Enrollment for the academic year <i class="font-bold">{{ $currentAcadTerm->getFullNameAttribute() }}</i> has not yet started.
                    </p>
                @endif

            @else
                {{-- There is an active enrollment period --}}
                @if ($activeEnrollmentPeriod->status === 'Paused')
                    <p id="db-text" class="text-[18px] md:text-[20px] font-bold p-10">
                        Enrollment period is temporarily closed. At this time, we are not accepting any new applications.
                    </p>

                @elseif ($activeEnrollmentPeriod->status === 'Closed')
                    <p id="db-text" class="text-[18px] md:text-[20px] font-bold p-10">
                        Enrollment for the academic year {{ $currentAcadTerm->getFullNameAttribute() }} has ended. We are no longer accepting new applications.
                    </p>

                @elseif ($activeEnrollmentPeriod->status === 'Ongoing')
                    <p id="db-text" class="text-[18px] md:text-[22px] font-bold p-2">
                        Welcome to Dreamy School' Online Registration for {{ $currentAcadTerm->getFullNameAttribute() }}
                    </p>
                    <p id="zspan" class="text-[16px] md:text-[18px] font-medium">
                        Please click the button below to fill out the form.
                    </p>
                    <div id="btn-container" class="flex flex-col justify-center items-center flex-grow m-8">
                        <x-nav-link href="/admission/application-form"
                            class="bg-[#199BCF]/80 text-white px-6 py-3 rounded-full hover:bg-[#1689b8] transition-colors duration-200 backdrop-blur-sm shadow-lg">
                            <p class="text-[16px] font-bold">Get Started</p>
                        </x-nav-link>
                    </div>
                @endif
            @endif

        </div>

    </div>
@endsection

@section('summary')

    @if ($currentAcadTerm && $activeEnrollmentPeriod)


        {{-- @if ($latestClosedPeriod) --}}
            <div class="flex flex-col md:flex-col justify-between items-center py-8 gap-4">

                <div class="flex flex-col space-y-2 justify-center items-center mb-4">
                    <p class="text-[18px] font-semibold">Application Summary</p>
                </div>
                <div class="flex flex-col md:flex-row justify-center items-center space-y-4 md:space-y-0 md:space-x-16 w-full">
                    <div class="flex flex-col space-y-2 justify-center items-center bg-[#E3ECFF]/60 py-6 px-10 gap-2 rounded-md">
                        <p class="md:text-[16px] font-semibold opacity-90">Total Registrations</p>
                        <p class="md:text-[20px] font-black">500</p>
                        <p class="md:text-[14px] opacity-60">Applications Received</p>   
                    </div>
                    <div class="flex flex-col space-y-2 justify-center items-center bg-[#E3ECFF]/60 py-6 px-4 gap-2 rounded-md">
                        <p class="md:text-[16px] font-semibold opacity-90">Successful Applicaticants</p>
                        <p class="md:text-[20px] font-black">500</p>
                        <p class="md:text-[14px] opacity-60">58% Acceptance rate</p> 
                    </div>
                </div>
                <div class="flex flex-row mt-4">
                    <p class="text-[16px] font-semibold opacity-70">Enrollment Period Status:</p><span class="opacity-0 select-none">a</span>
                    @if ($activeEnrollmentPeriod->status === 'Paused')
                        <p id="ep-status" class="text-[16px] text-[#FF9800] font-semibold ">Paused</p>
                    @elseif ($activeEnrollmentPeriod->status === 'Closed')
                        <p id="ep-status" class="text-[16px] text-[#FF9800] font-semibold ">Closed</p>
                    @elseif ($activeEnrollmentPeriod->status === 'Ongoing')
                        <p id="ep-status" class="text-[16px] text-[#34A853] font-semibold ">Ongoing</p>
                        
                    @endif
                    {{-- <p id="ep-status" class="text-[16px] text-[#34A853] font-semibold">{{$activeEnrollmentPeriod->status ?? "-"}}</p> --}}
                </div>

            </div>
        {{-- @endif --}}

    @endif

@endsection

@push('scripts')
<script type="module">
    
    document.addEventListener('DOMContentLoaded', function() {

        window.Echo.channel('updating-enrollment-period-status').listen('EnrollmentPeriodStatusUpdated', (event) => {
            console.log('Enrollment period status updated:', event.enrollmentPeriod.status);
        const status = event.enrollmentPeriod.status;
        const dbText = document.getElementById('db-text');
        const epstatus = document.getElementById('ep-status');
        const zspan = document.getElementById('zspan');
        const btnContainer = document.getElementById('btn-container');
            
            if (status === 'Paused') {

                dbText.textContent = 'Enrollment period is temporarily closed. At this time, we are not accepting any new applications.';
                zspan.textContent = '';
                btnContainer.classList.add('hidden');
                dbText.classList.remove('md:text-[22px]');
                dbText.classList.add('md:text-[20px]');
                dbText.classList.remove('p-2');
                dbText.classList.add('p-10');
                epstatus.textContent = status;
                epstatus.style.color = '#FF9800'; // Orange color for paused status

            }
            
            if (status === 'Ongoing') {

               // window.location.reload();
                dbText.textContent = "Welcome to Dreamy School' Online Registration for 2025";
                zspan.textContent = "Please click the button below to fill out the form.";
                btnContainer.classList.remove('hidden');
                dbText.classList.remove('md:text-[20px]');
                dbText.classList.add('md:text-[22px]');
                dbText.classList.remove('p-10');
                dbText.classList.add('p-2');
                epstatus.textContent = status;
                epstatus.style.color = '#34A853'; // Green color for ongoing status
            }

            if (status === 'Closed') {

                @php
                    $termName = $currentAcadTerm ? $currentAcadTerm->getFullNameAttribute() : '-';
                @endphp
                    dbText.innerHTML = "Enrollment for the academic year <i class='font-bold'>{{ $termName }}</i> has ended. We are no longer accepting new applications.";
                    zspan.textContent = '';
                    btnContainer.classList.add('hidden');
                    dbText.classList.remove('font-bold');
                    dbText.classList.add('font-medium');
                    window.location.reload();

            } 
        });

    });
</script>
@endpush