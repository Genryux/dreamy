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

@section('status')

    <div class="bg-[#f8f8f8] w-full flex flex-row justify-between items-center rounded-md border border-[#1e1e1e]/20 p-4 sticky top-0 z-10">
        {{-- Status Badge --}}
        <div class="flex flex-row justify-center items-center gap-2">
            <p class="md:text-[16px] font-medium">Application status:</p>
            @if ($applicant->application_status == 'Pending')
                
                <div class="bg-[#FFF4E5] border border-[#FBBC04]/60 flex flex-row justify-center items-center py-1 px-2 rounded-md gap-2">
                    <i class="fi fi-ss-pending text-[#FBBC04] flex justify-center items-center"></i>
                    <p class="text-[#FBBC04] font-semibold">Pending</p>
                </div>
                @endif

            @if ($applicant->application_status == 'Selected')

                @if ($applicant->interview->status == 'Pending')

                    <div class="bg-[#E6F4EA] border border-[#34A853]/60 flex flex-row justify-center items-center py-1 px-2 rounded-full gap-1">
                        <i class="fi fi-ss-check-circle text-[#34A853] flex justify-center items-center"></i>
                        <p class="text-[#34A853] font-semibold text-[14px]">Approved</p>
                    </div>

                @elseif ($applicant->interview->status == 'Scheduled')

                    <div class="bg-[#E7F0FD] border border-[#1A73E8]/60 flex flex-row justify-center items-center py-1 px-2 rounded-md gap-2">
                        <i class="fi fi-ss-check-circle text-[#1A73E8] flex justify-center items-center"></i>
                        <p class="text-[#1A73E8] font-semibold">Approved-Scheduled</p>
                    </div>
                
                @endif

            @endif

        </div>
        {{-- Status Progress --}}
        <div class="flex flex-row justify-evenly items-center gap-2">
            {{-- Pending --}}
            <div class="flex flex-row justify-center items-center gap-2 {{ $applicant->application_status == 'Pending' || 'Selected' ? 'opacity-100' : 'opacity-50'}}">
                @if ($applicant->application_status == 'Pending' || $applicant->application_status == 'Selected')

                    <div class="flex justify-center items-center bg-[#34A853] rounded-full text-white size-[26px]">
                        <i class="fi fi-ss-check flex justify-center items-center text-[14px]"></i>
                    </div>

                @else

                    <div class="flex justify-center items-center bg-[#0f111c] rounded-full text-white size-[26px]">
                        <p class="font-bold text-[18px]">1</p>
                    </div>

                @endif

                <p class="text-[16px] font-semibold">Fill out form</p>

                <x-divider></x-divider>

            </div>
            {{-- Selected --}}
            <div class="flex flex-row justify-center items-center gap-2 {{ $applicant->application_status == 'Selected' ? 'opacity-100' : 'opacity-30'}}">
                @if ($applicant->application_status == 'Selected')

                    <div class="flex justify-center items-center bg-[#34A853] rounded-full text-white size-[26px]">
                        <i class="fi fi-ss-check flex justify-center items-center text-[14px]"></i>
                    </div>

                @else

                    <div class="flex justify-center items-center bg-[#0f111c] rounded-full text-white size-[26px]">
                        <p class="font-bold text-[18px]">2</p>
                    </div>
                @endif

                <p class="text-[16px] font-semibold">Answer interview</p>

                <x-divider></x-divider>
            </div>
            {{-- Interview Passed --}}
            <div class="flex flex-row justify-center items-center gap-2 {{ $applicant->interview->status == 'Interview-Passed' ? 'opacity-100' : 'opacity-30'}}">
                <div class="flex justify-center items-center bg-[#0f111c] rounded-full text-white size-[26px]">
                    <p class="font-bold text-[18px]">3</p>
                </div>
                <p class="text-[16px] font-semibold">Result</p>
                <x-divider></x-divider>
            </div>
            {{-- Pending Documents --}}
            <div class="flex flex-row justify-center items-center gap-2 opacity-30">
                <div class="flex justify-center items-center bg-[#0f111c] rounded-full text-white size-[26px]">
                    <p class="font-bold text-[18px]">4</p>
                </div>
                <p class="text-[16px] font-semibold">Submit documents</p>
                <x-divider class=""></x-divider>
            </div>
        </div>
    </div>
    
@endsection

@section('pending')

    <div class="flex flex-col justify-center items-center h-full w-full space-y-2">

        <div class="bg-[#f8f8f8] flex flex-col rounded-md border border-[#1e1e1e]/20 justify-center p-4">
            <div class="flex flex-row justify-start items-center gap-2">

                <div class="text-[24px] text-white bg-[#0f111c] size-[35px] rounded-full flex justify-center items-center">
                    1
                </div>
                <div class="flex flex-col justify-center items-start">
                    <p class="text-[16px]/5 font-bold">Fill out enrollment form</p>
                    <p class="text-[14px]/5 opacity-60 font-semibold">Date: June 16, 2025</p>
                </div>

            </div>
            <x-divider class="my-4 opacity-15"></x-divider>

            <div class="space-y-3">
                <div class=" border border-[#1e1e1e]/15 rounded-[8px]">
                    <table class="text-[#0f111c] w-full">
                        <thead class="">
                            <tr class="">
                                <th class="px-4 py-2 bg-[#E3ECFF] text-start rounded-tl-[8px]">Learner Information</th>
                                <th class="bg-[#E3ECFF] text-start rounded-tr-[8px]"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-t border-[#1e1e1e]/15 opacity-[0.87]">
                                <td class="px-4 py-2 text-[14px]">Returning (Balik-Aral):</td>
                            </tr>
                            <tr class="opacity-[0.87]">
                                <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2 text-bold">With LRN:<span class="font-bold"> Yes</span></td>
                                <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2 text-bold">LRN: <span class="font-bold"></span></td>
                            </tr>
                            <tr class="opacity-[0.87]">
                                <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Grade Level to Enroll:<span class="font-bold"> </span></td>
                                <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Semester:<span class="font-bold"></span></td>
                            </tr>
                            <tr class="opacity-[0.87]">
                                <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Primary Track:<span class="font-bold"> </span></td>
                                <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Secondary Track:<span class="font-bold"></span></td>
                            </tr>
                            <tr class="opacity-[0.87]">
                                <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Last Name:<span class="font-bold"></span></td>
                                <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">First Name:<span class="font-bold"></span></td>
                            </tr>
                            <tr class="opacity-[0.87]">
                                <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Middle Name:<span class="font-bold"></span></td>
                                <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Extension Name:<span class="font-bold"></span></td>
                            </tr>
                            <tr class="opacity-[0.87]">
                                <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Birthdate:<span class="font-bold"> </span></td>
                                <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Age:<span class="font-bold"> </span></td>
                            </tr>
                            <tr class="opacity-[0.87]">
                                <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Place of Birth:<span class="font-bold"></span></td>
                                <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Mother Tongue:<span class="font-bold"></span></td>
                            </tr>
                            <tr class="opacity-[0.87]">
                                <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Belong to any IP community:<span class="font-bold"></span></td>
                                <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Beneficiary of 4Ps:<span class="font-bold"></span></td>
                            </tr>
                            <tr class="opacity-[0.87]">
                                <td class="px-4 py-2 text-[14px]">Learner with disability:</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class=" border border-[#1e1e1e]/15 rounded-[8px]">
                    <table class="text-[#0f111c] w-full">
                        <thead class="">
                            <tr class="">
                                <th class="border-r border-[#1e1e1e]/15 px-4 py-2 bg-[#E3ECFF] text-start rounded-tl-[8px] text-[16px]">Current Address</th>
                                <th class="px-4 py-2 bg-[#E3ECFF] text-start rounded-tr-[8px] text-[16px]">Permanent Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="opacity-[0.87]">
                                <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2 text-bold">House No:</td>
                                <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">House No:<span class="font-bold"></span> </td>
                            </tr>
                            <tr class="opacity-[0.87]">
                                <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Sitio/Street Name:</td>
                                <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Sitio/Street Name:</td>
                            </tr>
                            <tr class="opacity-[0.87]">
                                <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Barangay:</td>
                                <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Barangay:</td>
                            </tr>
                            <tr class="opacity-[0.87]">
                                <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Municipality/City:</td>
                                <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Municipality/City:</td>
                            </tr>
                            <tr class="opacity-[0.87]">
                                <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Country:</td>
                                <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Country:</td>
                            </tr>
                            <tr class="opacity-[0.87]">
                                <td class="px-4 py-2 text-[14px] border-r border-[#1e1e1e]/15 w-1/2">Zip Code:</td>
                                <td class="px-4 py-2 text-[14px] w-1/2">Zip Code:</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class=" border border-[#1e1e1e]/15 rounded-[8px]">
                    <table class="text-[#0f111c] w-full table-fixed">
                        <thead class="">
                            <tr class="">
                                <th class="border-b border-[#1e1e1e]/15 px-4 py-2 bg-[#E3ECFF] text-start rounded-tl-[8px] text-[16px]">Parent/Guardian's Information</th>
                                <th class="border-b border-[#1e1e1e]/15 px-4 py-2 bg-[#E3ECFF] text-start text-[16px]"></th>
                                <th class="border-b border-[#1e1e1e]/15 px-4 py-2 bg-[#E3ECFF] text-start rounded-tr-[8px] text-[16px]"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="opacity-[0.87]">
                                <td class="px-4 py-2 text-[16px] border-b border-r border-[#1e1e1e]/15 font-bold">Mother's Information:</td>
                                <td class="px-4 py-2 text-[16px] border-b border-r border-[#1e1e1e]/15 font-bold">Father's Information:<span class="font-bold"></span></td>
                                <td class="px-4 py-2 text-[16px] border-b border-[#1e1e1e]/15 font-bold">Guardian's Information:<span class="font-bold"></span></td>
                            </tr>
                            <tr class="opacity-[0.87]">
                                <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Last Name:</td>
                                <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Last Name:</td>
                                <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Last Name:</td>
                            </tr>
                            <tr class="opacity-[0.87]">
                                <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">First Name:</td>
                                <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">First Name:</td>
                                <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">First Name:</td>
                            </tr>
                            <tr class="opacity-[0.87]">
                                <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Middle Name:</td>
                                <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Middle Name:</td>
                                <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Middle Name:</td>
                            </tr>
                            <tr class="opacity-[0.87]">
                                <td class="px-4 py-2 text-[14px] border-r border-[#1e1e1e]/15 w-1/2">Contact Number:</td>
                                <td class="px-4 py-2 text-[14px] border-r border-[#1e1e1e]/15 w-1/2">Contact Number:</td>
                                <td class="px-4 py-2 text-[14px] w-1/2">Contact Number:</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class=" border border-[#1e1e1e]/15 rounded-[8px]">
                    <table class="text-[#0f111c] w-full">
                        <thead class="">
                            <tr class="">
                                <th class="border-b border-[#1e1e1e]/15 px-4 py-2 bg-[#E3ECFF] text-start rounded-tl-[8px] text-[16px]"> Other Informations </th>
                                <th class="border-b border-[#1e1e1e]/15 px-4 py-2 bg-[#E3ECFF] text-start rounded-tr-[8px] text-[16px]"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="opacity-[0.87]">
                                <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2 text-bold">Preferred Class Schedule:</td>
                                <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2 text-bold"></td>
                            </tr>
                            <tr class="opacity-[0.87]">
                                <td class="px-4 py-2 text-[14px] border-r border-[#1e1e1e]/15 w-1/2">Parent/Guardian's Signature:</td>
                                <td class="px-4 py-2 text-[14px] w-1/2">Date Applied:</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>


@endsection


@section('selected')


    <div class="bg-[#f8f8f8] flex flex-col rounded-md border border-[#1e1e1e]/20 md:w-full justify-center p-4">
        <div class="flex flex-row justify-start items-center gap-2">

            <div class="text-[24px] text-white bg-[#0f111c] size-[35px] rounded-full flex justify-center items-center">
                1
            </div>
            <div class="flex flex-col justify-center items-start">
                <p class="text-[16px]/5 font-bold">Take oral or document-based interview at School</p>
                <p class="text-[14px]/5 opacity-60 font-semibold">Date: June 16, 2025</p>
            </div>

        </div>
        <x-divider class="my-4 opacity-15"></x-divider>
        <div class="flex flex-col justify-center items-center space-y-4 py-14">

            @if ($applicant->interview() && $applicant->interview->status === 'Pending')
                <p>Your interview schedule will be available soon. Please check back or contact the admissions office for updates.</p>
                <img src="{{ asset('images/Waiting.svg') }}" alt="" class="size-[200px] md:size-[300px] mx-auto mt-4">
            @endif

        </div>
    </div>
    
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