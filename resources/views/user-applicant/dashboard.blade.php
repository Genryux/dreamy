@extends('layouts.admission')


@if ($applicant->application_status === null)
    @section('content')
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="flex flex-col h-full py-8 px-6 md:px-2 gap-6">

            <div class="text-center">

                @if (!$currentAcadTerm)
                    <p class="text-[18px] md:text-[20px] font-semibold p-10">
                        Enrollment is currently unavailable. Please check back soon or contact the admissions office for
                        assistance.
                        <img src="{{ asset('images/unavailable.svg') }}" alt=""
                            class="size-[200px] md:size-[300px] mx-auto mt-4">
                    </p>
                    </svg>
                @elseif ($currentAcadTerm && !$activeEnrollmentPeriod)
                    {{-- Check if there was a past enrollment period for this term --}}
                    @php
                        $latestClosedPeriod = $currentAcadTerm
                            ->enrollmentPeriods()
                            ->where('status', 'Closed')
                            ->latest('updated_at')
                            ->first();
                    @endphp

                    @if ($latestClosedPeriod)
                        <p class="text-[18px] md:text-[20px] font-medium p-10">
                            Enrollment for the academic year <i
                                class="font-bold">{{ $currentAcadTerm->getFullNameAttribute() }}</i> has ended. We are no
                            longer
                            accepting new applications.
                        </p>
                    @else
                        <p class="text-[18px] md:text-[20px] font-medium p-10">
                            Enrollment for the academic year <i
                                class="font-bold">{{ $currentAcadTerm->getFullNameAttribute() }}</i> has not yet started.
                        </p>
                    @endif
                @else
                    {{-- There is an active enrollment period --}}
                    @if ($activeEnrollmentPeriod->status === 'Paused')
                        <p id="db-text" class="text-[18px] md:text-[20px] font-bold p-10">
                            Enrollment period is temporarily closed. At this time, we are not accepting any new
                            applications.
                        </p>
                    @elseif ($activeEnrollmentPeriod->status === 'Closed')
                        <p id="db-text" class="text-[18px] md:text-[20px] font-bold p-10">
                            Enrollment for the academic year {{ $currentAcadTerm->getFullNameAttribute() }} has ended. We
                            are no
                            longer accepting new applications.
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
                <div
                    class="flex flex-col md:flex-row justify-center items-center space-y-4 md:space-y-0 md:space-x-16 w-full">
                    <div
                        class="flex flex-col space-y-2 justify-center items-center bg-[#E3ECFF]/60 py-6 px-10 gap-2 rounded-md">
                        <p class="md:text-[16px] font-semibold opacity-90">Total Registrations</p>
                        <p class="md:text-[20px] font-black">500</p>
                        <p class="md:text-[14px] opacity-60">Applications Received</p>
                    </div>
                    <div
                        class="flex flex-col space-y-2 justify-center items-center bg-[#E3ECFF]/60 py-6 px-4 gap-2 rounded-md">
                        <p class="md:text-[16px] font-semibold opacity-90">Successful Applicaticants</p>
                        <p class="md:text-[20px] font-black">500</p>
                        <p class="md:text-[14px] opacity-60">58% Acceptance rate</p>
                    </div>
                </div>
                <div class="flex flex-row mt-4">
                    <p class="text-[16px] font-semibold opacity-70">Enrollment Period Status:</p><span
                        class="opacity-0 select-none">a</span>
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
@endif

@section('status')

    <div
        class="bg-[#f8f8f8] w-full flex flex-row justify-between items-center rounded-lg border border-[#1e1e1e]/20 p-4 sticky top-0 z-10">
        {{-- Status Badge --}}
        <div class="flex flex-row justify-center items-center gap-2">
            <p class="md:text-[16px] font-medium">Application status:</p>
            @if ($applicant->application_status == 'Pending')
                <div
                    class="bg-[#FFF4E5] border border-[#FBBC04]/60 flex flex-row justify-center items-center py-1 px-2 rounded-full gap-2">
                    <i class="fi fi-ss-pending text-[#FBBC04] flex justify-center items-center"></i>
                    <p class="text-[#FBBC04] font-semibold">Pending</p>
                </div>
            @endif

            @if ($applicant->application_status == 'Selected')
                @if ($applicant->interview->status == 'Pending')
                    <div
                        class="bg-[#E6F4EA] border border-[#34A853]/60 flex flex-row justify-center items-center py-1 px-2 rounded-full gap-1">
                        <i class="fi fi-ss-check-circle text-[#34A853] flex justify-center items-center"></i>
                        <p class="text-[#34A853] font-semibold text-[14px]">Approved</p>
                    </div>
                @elseif ($applicant->interview->status == 'Scheduled')
                    <div
                        class="bg-[#E7F0FD] border border-[#1A73E8]/60 flex flex-row justify-center items-center py-1 px-2 rounded-full gap-2">
                        <i class="fi fi-ss-check-circle text-[#1A73E8] flex justify-center items-center"></i>
                        <p class="text-[#1A73E8] font-semibold">Approved-Scheduled</p>
                    </div>
                @endif
            @endif

            @if ($applicant->application_status == 'Pending-Documents' && $applicant->interview->status == 'Interview-Passed')
                <div
                    class="bg-[#E6F4EA] border border-[#34A853]/60 flex flex-row justify-center items-center py-1 px-2 rounded-full gap-2">
                    <i class="fi fi-ss-check text-[#34A853] flex justify-center items-center"></i>
                    <p class="text-[#34A853] font-semibold">Interview-Passed</p>
                </div>
            @endif

            @if ($applicant->application_status == 'Pending-Documents' && $applicant->interview->status == 'Interview-Completed')
                <div
                    class="bg-[#FFF3E0] border border-[#FB8C00]/60 flex flex-row justify-center items-center py-1 px-2 rounded-full gap-2">
                    <i class="fi fi-rs-folder-times text-[#FB8C00] flex justify-center items-center"></i>
                    <p class="text-[#FB8C00] font-semibold">Pending-Documents</p>
                </div>
            @endif

            @if ($applicant->application_status == 'Officially Enrolled')
                <div
                    class="bg-[#E7F0FD] border border-[#1A73E8]/60 flex flex-row justify-center items-center py-1 px-2 rounded-full gap-2">
                    <i class="fi fi-ss-graduation-cap text-[#1A73E8] flex justify-center items-center"></i>
                    <p class="text-[#1A73E8] font-semibold">Officially Enrolled</p>
                </div>
            @endif

        </div>

        {{-- Status Progress --}}
        <div class="flex flex-row justify-evenly items-center gap-2">
            {{-- Pending --}}
            <div
                class="flex flex-row justify-center items-center gap-2 {{ $applicant->application_status == 'Pending' || 'Selected' ? 'opacity-100' : 'opacity-50' }}">
                @if (
                    $applicant->application_status == 'Pending' ||
                        $applicant->application_status == 'Selected' ||
                        $applicant->application_status == 'Pending-Documents' ||
                        $applicant->application_status == 'Officially Enrolled')
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
            <div
                class="flex flex-row justify-center items-center gap-2 {{ $applicant->application_status == 'Selected' || $applicant->application_status == 'Pending-Documents' || $applicant->application_status == 'Officially Enrolled' ? 'opacity-100' : 'opacity-30' }}">
                @if (
                    $applicant->application_status == 'Selected' ||
                        $applicant->application_status == 'Pending-Documents' ||
                        $applicant->application_status == 'Officially Enrolled')
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

            @if ($applicant->interview)
                <div
                    class="flex flex-row justify-center items-center gap-2 
                    {{ ($applicant->application_status == 'Pending-Documents' &&
                        ($applicant->interview->status == 'Interview-Passed' || $applicant->interview->status == 'Interview-Completed')) ||
                    $applicant->application_status == 'Officially Enrolled'
                        ? 'opacity-100'
                        : 'opacity-30' }}">
                @else
                    <div class="flex flex-row justify-center items-center gap-2 opacity-30">
            @endif

            @if ($applicant->application_status == 'Pending-Documents' || $applicant->application_status == 'Officially Enrolled')
                <div class="flex justify-center items-center bg-[#34A853] rounded-full text-white size-[26px]">
                    <i class="fi fi-ss-check flex justify-center items-center text-[14px]"></i>
                </div>
            @else
                <div class="flex justify-center items-center bg-[#0f111c] rounded-full text-white size-[26px]">
                    <p class="font-bold text-[18px]">3</p>
                </div>
            @endif
            <p class="text-[16px] font-semibold">Result</p>
            <x-divider></x-divider>
        </div>

        {{-- Pending Documents --}}
        <div
            class="flex flex-row justify-center items-center gap-2 {{ $applicant->application_status == 'Pending-Documents' || $applicant->application_status == 'Officially Enrolled' ? 'opacity-100' : 'opacity-30' }}">
            @if ($applicant->application_status == 'Officially Enrolled')
                <div class="flex justify-center items-center bg-[#34A853] rounded-full text-white size-[26px]">
                    <i class="fi fi-ss-check flex justify-center items-center text-[14px]"></i>
                </div>
            @else
                <div class="flex justify-center items-center bg-[#0f111c] rounded-full text-white size-[26px]">
                    <p class="font-bold text-[18px]">4</p>
                </div>
            @endif

            <p class="text-[16px] font-semibold">Submit documents</p>

            <x-divider></x-divider>
        </div>

    </div>
    </div>

@endsection

@if ($applicant->application_status === 'Pending')
    @section('pending')
        <div class="flex flex-col justify-center items-center h-full w-full space-y-2">

            <div class="bg-[#f8f8f8] flex flex-col rounded-md border border-[#1e1e1e]/20 justify-center p-4">
                <div class="flex flex-row justify-start items-center gap-2">

                    <div
                        class="text-[24px] text-white bg-[#0f111c] size-[35px] rounded-full flex justify-center items-center">
                        1
                    </div>
                    <div class="flex flex-col justify-center items-start">
                        <p class="text-[16px]/5 font-bold">Fill out enrollment form</p>
                        <p class="text-[14px]/5 opacity-60 font-semibold">Date: June 16, 2025</p>
                    </div>

                </div>
                <x-divider class="mt-4 opacity-15"></x-divider>
                <div class="w-[80%] flex flex-row justify-center items-center text-center self-center my-8">
                    <p class="text-[14px]"> Your application is currently pending review. Please stay tuned for the next
                        steps, which may include an interview or document submission. We’ll notify you once there are
                        updates regarding your application status.</p>
                </div>
                <div class="space-y-3 border border-[#1e1e1e]/10 p-4 rounded-xl bg-[#E3ECFF]/20 w-[90%] self-center">
                    <div class=" border border-[#1e1e1e]/15 rounded-[8px] bg-[#f8f8f8] shadow-md">
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
                                    <td
                                        class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2 text-bold">
                                        With LRN:<span class="font-bold"> Yes</span></td>
                                    <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2 text-bold">LRN:
                                        <span class="font-bold"></span>
                                    </td>
                                </tr>
                                <tr class="opacity-[0.87]">
                                    <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Grade
                                        Level
                                        to Enroll:<span class="font-bold"> </span></td>
                                    <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Semester:<span
                                            class="font-bold"></span></td>
                                </tr>
                                <tr class="opacity-[0.87]">
                                    <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Primary
                                        Track:<span class="font-bold"> </span></td>
                                    <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Secondary
                                        Track:<span class="font-bold"></span></td>
                                </tr>
                                <tr class="opacity-[0.87]">
                                    <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Last
                                        Name:<span class="font-bold"></span></td>
                                    <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">First Name:<span
                                            class="font-bold"></span></td>
                                </tr>
                                <tr class="opacity-[0.87]">
                                    <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Middle
                                        Name:<span class="font-bold"></span></td>
                                    <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Extension
                                        Name:<span class="font-bold"></span></td>
                                </tr>
                                <tr class="opacity-[0.87]">
                                    <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">
                                        Birthdate:<span class="font-bold"> </span></td>
                                    <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Age:<span
                                            class="font-bold"> </span></td>
                                </tr>
                                <tr class="opacity-[0.87]">
                                    <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Place of
                                        Birth:<span class="font-bold"></span></td>
                                    <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Mother
                                        Tongue:<span class="font-bold"></span></td>
                                </tr>
                                <tr class="opacity-[0.87]">
                                    <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Belong to
                                        any
                                        IP community:<span class="font-bold"></span></td>
                                    <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Beneficiary of
                                        4Ps:<span class="font-bold"></span></td>
                                </tr>
                                <tr class="opacity-[0.87]">
                                    <td class="px-4 py-2 text-[14px]">Learner with disability:</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class=" border border-[#1e1e1e]/15 rounded-[8px] bg-[#f8f8f8] shadow-md">
                        <table class="text-[#0f111c] w-full">
                            <thead class="">
                                <tr class="">
                                    <th
                                        class="border-r border-[#1e1e1e]/15 px-4 py-2 bg-[#E3ECFF] text-start rounded-tl-[8px] text-[16px]">
                                        Current Address</th>
                                    <th class="px-4 py-2 bg-[#E3ECFF] text-start rounded-tr-[8px] text-[16px]">Permanent
                                        Address</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="opacity-[0.87]">
                                    <td
                                        class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2 text-bold">
                                        House No:</td>
                                    <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">House No:<span
                                            class="font-bold"></span> </td>
                                </tr>
                                <tr class="opacity-[0.87]">
                                    <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">
                                        Sitio/Street
                                        Name:</td>
                                    <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Sitio/Street Name:
                                    </td>
                                </tr>
                                <tr class="opacity-[0.87]">
                                    <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Barangay:
                                    </td>
                                    <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Barangay:</td>
                                </tr>
                                <tr class="opacity-[0.87]">
                                    <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">
                                        Municipality/City:</td>
                                    <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Municipality/City:
                                    </td>
                                </tr>
                                <tr class="opacity-[0.87]">
                                    <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Country:
                                    </td>
                                    <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Country:</td>
                                </tr>
                                <tr class="opacity-[0.87]">
                                    <td class="px-4 py-2 text-[14px] border-r border-[#1e1e1e]/15 w-1/2">Zip Code:</td>
                                    <td class="px-4 py-2 text-[14px] w-1/2">Zip Code:</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class=" border border-[#1e1e1e]/15 rounded-[8px] bg-[#f8f8f8] shadow-md">
                        <table class="text-[#0f111c] w-full table-fixed">
                            <thead class="">
                                <tr class="">
                                    <th
                                        class="border-b border-[#1e1e1e]/15 px-4 py-2 bg-[#E3ECFF] text-start rounded-tl-[8px] text-[16px]">
                                        Parent/Guardian's Information</th>
                                    <th class="border-b border-[#1e1e1e]/15 px-4 py-2 bg-[#E3ECFF] text-start text-[16px]">
                                    </th>
                                    <th
                                        class="border-b border-[#1e1e1e]/15 px-4 py-2 bg-[#E3ECFF] text-start rounded-tr-[8px] text-[16px]">
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="opacity-[0.87]">
                                    <td class="px-4 py-2 text-[16px] border-b border-r border-[#1e1e1e]/15 font-bold">
                                        Mother's
                                        Information:</td>
                                    <td class="px-4 py-2 text-[16px] border-b border-r border-[#1e1e1e]/15 font-bold">
                                        Father's
                                        Information:<span class="font-bold"></span></td>
                                    <td class="px-4 py-2 text-[16px] border-b border-[#1e1e1e]/15 font-bold">Guardian's
                                        Information:<span class="font-bold"></span></td>
                                </tr>
                                <tr class="opacity-[0.87]">
                                    <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Last
                                        Name:
                                    </td>
                                    <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Last
                                        Name:
                                    </td>
                                    <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Last Name:</td>
                                </tr>
                                <tr class="opacity-[0.87]">
                                    <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">First
                                        Name:
                                    </td>
                                    <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">First
                                        Name:
                                    </td>
                                    <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">First Name:</td>
                                </tr>
                                <tr class="opacity-[0.87]">
                                    <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Middle
                                        Name:
                                    </td>
                                    <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Middle
                                        Name:
                                    </td>
                                    <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Middle Name:</td>
                                </tr>
                                <tr class="opacity-[0.87]">
                                    <td class="px-4 py-2 text-[14px] border-r border-[#1e1e1e]/15 w-1/2">Contact Number:
                                    </td>
                                    <td class="px-4 py-2 text-[14px] border-r border-[#1e1e1e]/15 w-1/2">Contact Number:
                                    </td>
                                    <td class="px-4 py-2 text-[14px] w-1/2">Contact Number:</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class=" border border-[#1e1e1e]/15 rounded-[8px] bg-[#f8f8f8] shadow-md">
                        <table class="text-[#0f111c] w-full">
                            <thead class="">
                                <tr class="">
                                    <th
                                        class="border-b border-[#1e1e1e]/15 px-4 py-2 bg-[#E3ECFF] text-start rounded-tl-[8px] text-[16px]">
                                        Other Informations </th>
                                    <th
                                        class="border-b border-[#1e1e1e]/15 px-4 py-2 bg-[#E3ECFF] text-start rounded-tr-[8px] text-[16px]">
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="opacity-[0.87]">
                                    <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2 text-bold">
                                        Preferred
                                        Class Schedule:</td>
                                    <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2 text-bold"></td>
                                </tr>
                                <tr class="opacity-[0.87]">
                                    <td class="px-4 py-2 text-[14px] border-r border-[#1e1e1e]/15 w-1/2">Parent/Guardian's
                                        Signature:</td>
                                    <td class="px-4 py-2 text-[14px] w-1/2">Date Applied:</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>
    @endsection

@endif

@if ($applicant->application_status === 'Selected')
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

            @if ($applicant->interview() && $applicant->interview->status === 'Pending')
                <div class="flex flex-col justify-center items-center space-y-4 py-14">

                    <p>Your interview schedule will be available soon. Please check back or contact the admissions office
                        for
                        updates.</p>
                    <img src="{{ asset('images/Waiting.svg') }}" alt=""
                        class="size-[200px] md:size-[300px] mx-auto mt-4">

                </div>
            @elseif ($applicant->interview() && $applicant->interview->status === 'Scheduled')
                <div class="flex flex-col justify-center items-center space-y-4 py-8 ">

                    <p class="font-black text-[22px]">Your interview has been scheduled! 🎉</p>
                    <p class="opacity-70">Everything is set up for your upcoming interview. Please review the details below
                        and make sure to
                        arrive on time. </p>


                    <div class="w-[90%] border border-[#1e1e1e]/10 px-8 py-4 rounded-xl bg-[#E3ECFF]/20  shadow-sm">

                        <div class="flex flex-row justify-center items-center gap-2 mb-4">
                            <p class="font-semibold text-[18px] opacity-90">Interview Details</p>
                        </div>
                        {{-- applicant id --}}
                        <div class="pb-2 px-2">
                            <div
                                class="flex flex-row justify-start items-center p-4 gap-4 bg-[#f8f8f8] rounded-xl border border-[#1e1e1e]/10 hover:ring ring-[#4D8FF0]/20 hover:border-[#1A73E8]/70 transition duration-150 shadow-lg">

                                <div class="bg-[#1A73E8] p-2 rounded-md flex justify-center items-center">
                                    <i
                                        class="fi fi-rs-fingerprint flex justify-center items-center text-[24px] text-white opacity-90"></i>
                                </div>
                                <div>
                                    <p class="text-[14px] opacity-70 font-medium">Your Applicant ID</p>
                                    <span class="text-[16px] font-bold">{{ $applicant->applicant_id }}</span>
                                </div>
                            </div>
                        </div>
                        {{-- date --}}

                        <div class="flex flex-wrap flex-row">

                            <div class="w-1/2 pb-2 px-2">
                                <div
                                    class="flex flex-row justify-start items-center p-4 gap-4 bg-[#f8f8f8] rounded-xl border border-[#1e1e1e]/10 hover:ring ring-[#4D8FF0]/20 hover:border-[#1A73E8]/70 transition duration-150 shadow-lg">

                                    <div class="bg-[#1A73E8] p-2 rounded-md flex justify-center items-center">
                                        <i
                                            class="fi fi-rs-calendar-day flex justify-center items-center text-[24px] text-white opacity-90"></i>
                                    </div>
                                    <div>
                                        <p class="text-[14px] opacity-70 font-medium">Interview Date</p>
                                        <span
                                            class="text-[16px] font-bold">{{ \Carbon\Carbon::parse($applicant->interview->date)->format('F j, Y') }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- time --}}
                            <div class="w-1/2 pb-2 px-2">
                                <div
                                    class="flex flex-row justify-start items-center p-4 gap-4 bg-[#f8f8f8] rounded-xl border border-[#1e1e1e]/10 hover:ring ring-[#4D8FF0]/20 hover:border-[#1A73E8]/70 transition duration-150 shadow-lg">

                                    <div class="bg-[#1A73E8] p-2 rounded-md flex justify-center items-center">
                                        <i
                                            class="fi fi-rs-calendar-clock flex justify-center items-center text-[24px] text-white opacity-90"></i>
                                    </div>
                                    <div>
                                        <p class="text-[14px] opacity-70 font-medium">Interview Time</p>
                                        <span
                                            class="text-[16px] font-bold">{{ \Carbon\Carbon::parse($applicant->interview->time)->format('h:i A') }}</span>
                                    </div>
                                </div>
                            </div>
                            {{-- location --}}
                            <div class="w-1/2 pb-2 px-2">
                                <div
                                    class="flex flex-row justify-start items-center p-4 gap-4 bg-[#f8f8f8] rounded-xl border border-[#1e1e1e]/10 hover:ring ring-[#4D8FF0]/20 hover:border-[#1A73E8]/70 transition duration-150 shadow-lg">

                                    <div class="bg-[#1A73E8] p-2 rounded-md flex justify-center items-center">
                                        <i
                                            class="fi fi-rs-land-layer-location flex justify-center items-center text-[24px] text-white opacity-90"></i>
                                    </div>
                                    <div>
                                        <p class="text-[14px] opacity-70 font-medium">Location</p>
                                        <span class="text-[16px] font-bold">Room 1, Second Floor</span>
                                    </div>
                                </div>
                            </div>

                            {{-- interviewer --}}
                            <div class="w-1/2 pb-2 px-2">
                                <div
                                    class="flex flex-row justify-start items-center p-4 gap-4 bg-[#f8f8f8] rounded-xl border border-[#1e1e1e]/10 hover:ring ring-[#4D8FF0]/20 hover:border-[#1A73E8]/70 transition duration-150 shadow-lg">

                                    <div class="bg-[#1A73E8] p-2 rounded-md flex justify-center items-center">
                                        <i
                                            class="fi fi-rs-user flex justify-center items-center text-[24px] text-white opacity-90"></i>
                                    </div>
                                    <div>
                                        <p class="text-[14px] opacity-70 font-medium">Interviewer</p>
                                        <span class="text-[16px] font-bold">Peter DelaCruz</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- important --}}
                        <div class="px-2">
                            <div
                                class="flex flex-col justify-center items-start p-4 gap-2 bg-[#E7F0FD] border border-[#1e1e1e]/10 rounded-xl hover:ring hover:ring-[#4D8FF0]/20 hover:border-[#1A73E8]/70 transition duration-150 shadow-md">

                                <div class="flex flex-row justify-center items-center gap-4">
                                    <div class="bg-[#1A73E8] p-2 rounded-md flex justify-center items-center">
                                        <i
                                            class="fi fi-rs-exclamation flex justify-center items-center text-[24px] text-white"></i>
                                    </div>
                                    <p class="text-[16px] font-bold">Additional Info</p>

                                </div>

                                <div class="flex justify-center items-center text-center px-4 py-2 pl-14">
                                    <span class="text-[16px] font-medium">asdasdsa asd sad asd sadasdasdasdasdsa sadasdsad
                                        as asd </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    @endsection

@endif

@if ($applicant->application_status === 'Pending-Documents' && $applicant->interview->status === 'Interview-Passed')
    @section('pending-documents')
        <div class="bg-[#f8f8f8] flex flex-col rounded-md border border-[#1e1e1e]/20 md:w-full justify-center p-4">
            <div class="flex flex-row justify-start items-center gap-2">

                <div class="text-[24px] text-white bg-[#0f111c] size-[35px] rounded-full flex justify-center items-center">
                    3
                </div>
                <div class="flex flex-col justify-center items-start">
                    <p class="text-[16px]/5 font-bold">Result</p>
                    <p class="text-[14px]/5 opacity-60 font-semibold">Date: June 16, 2025</p>
                </div>

            </div>
            <x-divider class="my-4 opacity-15"></x-divider>
            <div class="flex flex-col justify-center items-center py-4 pb-8">

                <img src="{{ asset('images/celebration.png') }}" alt=""
                    class="size-[200px] md:size-[230px] mx-auto mt-4">

                <div class="flex flex-col justify-center items-center gap-4">
                    <div class="flex flex-col justify-center items-center gap-1">
                        <p class="font-medium text-[18px] opacity-80">Congratulations!</p>
                        <p class="font-black text-[28px]">🎉You're In!🎉</p>
                    </div>


                    <p class="text-center opacity-70">You have successfully passed your interview and are now conditionally
                        enrolled! <br> To complete your official
                        enrollment, please submit all required documents (Online/Physical) as instructed. </p>
                </div>


                <form action="/set-interview/{{ $applicant->interview->id }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="Interview-Completed">
                    <input type="hidden" name="applicant_id" value="{{ $applicant->id }}">
                    <button name="action" value="update-docs"
                        class="hover:ring ring-[#1A73E8]/30 bg-[#1A73E8] text-[#f8f8f8] px-4 py-3 rounded-xl flex flex-row justify-center items-center gap-2 font-medium mt-10 hover:bg-[#1A73E8]/90 hover:text-white transition duration-200">
                        Submit Required Documents<i
                            class="fi fi-rs-arrow-small-right flex flex-row justify-center items-center text-[22px]"></i>
                    </button>
                </form>



            </div>

        </div>
    @endsection

@endif

@if ($applicant->application_status === 'Pending-Documents' && $applicant->interview->status === 'Interview-Completed')
    @section('pending-documents')
        <div class="bg-[#f8f8f8] flex flex-col rounded-md border border-[#1e1e1e]/20 md:w-full justify-center p-4">
            <div class="flex flex-row justify-start items-center gap-2">

                <div class="text-[24px] text-white bg-[#0f111c] size-[35px] rounded-full flex justify-center items-center">
                    4
                </div>
                <div class="flex flex-col justify-center items-start">
                    <p class="text-[16px]/5 font-bold">Submit Required Documents</p>
                    <p class="text-[14px]/5 opacity-60 font-semibold">Date: June 16, 2025</p>
                </div>

            </div>
            <x-divider class="my-4 opacity-15"></x-divider>
            <div class="flex flex-col justify-center items-center py-4 pb-8 space-y-4">

                <p class="mb-6 text-center">
                    Upload all required documents for your application. Make sure all files are clear and
                    readable.
                    <br>
                    <span class="font-bold opacity-70">Please select a document type first before uploading any
                        file.</span>
                </p>

                <div
                    class="w-[80%] flex flex-col justify-center items-center space-y-2 bg-[#E3ECFF]/20 p-6 border border-[#1e1e1e]/5 rounded-md ">
                    <p class="self-start font-bold opacity-80">Required Documents</p>
                    <div class="bg-[#f8f8f8] flex flex-col rounded-md border shadow-sm border-[#1e1e1e]/10 ">


                        <table id="docs-table" class="w-full table-fixed ">
                            <thead class="text-[14px]">
                                <tr>
                                    <th
                                        class="w-1/7 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 rounded-tl-md px-4 py-2 cursor-pointer ">
                                        <span class="mr-2">Documents</span>
                                    </th>

                                    <th class="w-1/7 text-center bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                        <span class="mr-2">Date Submitted</span>
                                    </th>
                                    <th
                                        class="w-1/7 text-center bg-[#E3ECFF] border-b border-[#1e1e1e]/15 rounded-tr-md px-4 py-2">
                                        Status
                                    </th>
                                </tr>
                            </thead>


                            <tbody>

                                @if ($documents)
                                    @foreach ($documents as $doc)
                                        @php
                                            $submission = $submissions[$doc->id] ?? null;

                                        @endphp

                                        <tr class="border-t-[1px] border-[#1e1e1e]/15 w-full rounded-md">
                                            <td
                                                class="w-1/8 text-start font-medium py-[8px] text-[14px] opacity-80 px-4 py-2 truncate">
                                                {{ $doc->type }}
                                            </td>
                                            @if (!is_null($submission))
                                                <td
                                                    class="w-1/8 text-center font-medium py-[8px] text-[14px] opacity-80 px-4 py-2 truncate">
                                                    {{ \Carbon\Carbon::parse($doc->updated_at)->timezone('Asia/Manila')->format('M. d - g:i A') }}
                                                </td>

                                                <td
                                                    class="w-1/8 text-center font-medium py-[8px] text-[14px] opacity-100 px-4 py-2 truncate">

                                                    @if ($submission->status == 'Pending')
                                                        <span
                                                            class="bg-[#FFF4E5] text-[#FBBC04] px-2 py-1 rounded-md font-medium">
                                                            Pending
                                                        </span>
                                                    @elseif ($submission->status == 'Verified')
                                                        <span
                                                            class="bg-[#E6F4EA] text-[#34A853] px-2 py-1 rounded-md font-medium">
                                                            Verified
                                                        </span>
                                                    @elseif ($submission->status == 'Rejected')
                                                        <span
                                                            class="bg-[#FCE8E6] text-[#EA4335] px-2 py-1 rounded-md font-medium">
                                                            Rejected
                                                        </span>
                                                    @endif


                                                </td>
                                            @else
                                                <td
                                                    class="w-1/8 text-center font-medium py-[8px] text-[14px] opacity-100 px-4 py-2 truncate">
                                                    <div class="flex flex-row justify-center items-center gap-2">
                                                        -
                                                    </div>
                                                </td>

                                                <td
                                                    class="w-1/8 text-center font-medium py-[8px] text-[14px] opacity-80 px-4 py-2 truncate">
                                                    <span
                                                        class="bg-[#E8EAED] text-[#5F6368] px-2 py-1 rounded-md font-medium">
                                                        Not submitted
                                                    </span>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>

                    </div>
                </div>
                <div
                    class="w-[80%] flex flex-col justify-center items-center space-y-4 bg-[#E3ECFF]/20 p-6 border border-[#1e1e1e]/5 rounded-md ">

                    <div class="flex flex-col w-full space-y-2">

                        {{-- @if ($documents)
                            <div class="w-full flex-wrap flex flex-row gap-2">
                                @foreach ($documents as $doc)
                                    <div
                                        class="bg-[#f3f4f6] border border-[#e5e7eb] flex justify-center items-center py-1 px-3 rounded-full text-gray-400">
                                        {{ $doc->type }}
                                    </div>
                                @endforeach
                            </div>
                        @endif --}}
                        <p class="font-bold opacity-80">Document type</p>
                        <label for="document-option"
                            class="bg-[#f8f8f8] border border-[#1e1e1e]/15 w-full py-2 rounded-md px-4 focus-within:border-[#1A73E8] transition duration-150 focus-within:ring ring-[#1A73E8]/20">

                            <select name="document-option" id="document-option"
                                class="bg-transparent w-full text-[16px] ">
                                @if ($documents)
                                    <option selected disabled>Select document type...</option>
                                    @foreach ($documents as $doc)
                                        <option value="{{ $doc->id }}">{{ $doc->type }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </label>
                    </div>

                    <form id="uploadForm" class="flex flex-col items-center justify-center w-full space-y-2">
                        <p class="self-start font-bold opacity-80">Upload File</p>
                        <label for="fileInput" id="fileInputLabel"
                            class="flex flex-col items-center justify-center w-full border-2 border-[#1A73E8]/60 border-dashed rounded-lg bg-[#E7F0FD] hover:bg-blue-100 opacity-40 cursor-pointer cursor-not-allowed select-none">

                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-8 h-8 mb-4 text-[#1A73E8]" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                </svg>
                                <p class="mb-2 text-sm text-[#0f111c]/80"><span class="font-semibold">Click to
                                        upload</span> or drag and drop</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG or PDF(MAX. 800x400px)</p>
                            </div>
                            <span
                                class="bg-blue-500 px-4 py-2 rounded-lg text-white mb-4 hover:bg-blue-600 transition duration-200">Choose
                                Files</span>

                            <input id="fileInput" type="file" class="hidden" accept=".pdf,.png,.jpeg" disabled />
                        </label>
                    </form>
                </div>

                <div class="uploaded-files w-[80%] flex flex-col justify-center items-center space-y-4 bg-[#E3ECFF]/20 p-6 border border-[#1e1e1e]/5 rounded-md "
                    id="uploadedFiles">
                    <h2 class="section-title self-start font-bold opacity-80">Uploaded Documents</h2>
                    <div id="filesList" class="w-full space-y-2">

                    </div>
                </div>

                <label
                    class="w-[80%] flex flex-row justify-center items-center bg-[#E3ECFF] p-6 border border-[#1e1e1e]/5 rounded-md text-[14px] gap-2 hover:ring ring-blue-400/20 hover:border-blue-500 hover:shadow-md transition duration-200">
                    <input type="checkbox" name="consent" id="consent" class="size-[30px]" required>

                    <p>
                        I confirm that the documents I am uploading are accurate and belong to me. I understand that
                        these
                        may
                        contain personal or sensitive information, and I consent to the school securely reviewing and
                        processing
                        them for my application, in accordance with the
                        <a href="/privacy-policy" target="_blank"
                            class="underline text-blue-500 visited:text-purple-400">Privacy Policy</a>.
                    </p>



                </label>

                <div class="w-[75%] flex justify-center items-center">
                    <button form="uploadForm" id="submitBtn"
                        class="bg-blue-500 px-4 py-2 rounded-lg text-white mb-4 hover:bg-blue-600 transition duration-200 opacity-50 cursor-not-allowed"
                        disabled>Submit
                        All Documents</button>
                </div>

                @if (session('success'))
                    <p style="color: green;">{{ session('success') }}</p>
                @endif




            </div>

        </div>
    @endsection

@endif

@if ($applicant->application_status === 'Officially Enrolled')
    @section('officially-enrolled')
        <div class="bg-[#f8f8f8] flex flex-col rounded-lg border border-[#1e1e1e]/20 md:w-full justify-center p-4">

            <div class="flex flex-col w-full justify-center items-center px-4 pb-8 gap-8">


                <div class="flex flex-col justify-center items-center w-[80%] gap-4">

                    <img src="{{ asset('images/Welcome.gif') }}" alt=""
                        class="size-[200px] md:size-[230px] mx-auto mt-4">
                    <p class="font-bold text-[28px] opacity-90">🎉Welcome to Dreamy School!🎉</p>

                    <p class="text-center opacity-80">We’re thrilled to welcome you to our academic community! You've
                        completed
                        the enrollment process, and your hard work has paid off. We can’t wait to see what you’ll achieve
                        with
                        us.</p>

                </div>

                <div
                    class="bg-[#E3ECFF]/60 w-[80%] flex flex-col justify-center items-center text-center gap-4 p-4 rounded-lg">
                    <div>
                        <p class="font-bold text-[18px]">🚀 Ready to Get Started?</p>
                    </div>
                    <div class="space-y-2 w-full ">
                        <form method="post" action="/students/{{ $applicant->id }}"
                            class="w-full gap-3 border border-[#1e1e1e]/15 rounded-lg p-4 text-start bg-[#f8f8f8] cursor-pointer hover:ring hover:ring-blue-200 hover:border-blue-500 hover:shadow-lg transition duration-150">
                            @csrf
                            <label for="submit-form"
                                class="w-full h-full cursor-pointer flex flex-row justify-start items-center gap-3">
                                <div
                                    class="flex justify-center items-center border border-[#1e1e1e]/15 p-2 rounded-lg bg-[#199BCF] text-white">
                                    <i class="fi fi-ss-site-alt text-[24px] flex justify-center items-center"></i>
                                </div>
                                <div>
                                    <p class="font-bold">Access Student Portal</p>
                                    <p class="opacity-70 font-medium">View your class schedule, subjects, and other
                                        academic-related informations.
                                    </p>
                                </div>
                            </label>

                            <input type="submit" value="" class="hidden" id="submit-form">



                        </form>
                        <div
                            class="flex flex-row justify-between items-center w-full bg-[#E7F0FD] ring-1 ring-[#1A73E8]/40 p-2 rounded-lg text-[#1A73E8]">
                            <i class="fi fi-ss-info flex justify-center items-center text-[18px] text-[#1A73E8]/80"></i>
                            <p class="text-[14px] font-regular">
                                By clicking this button, your student account will be created using your current login
                                credentials.
                            </p>
                            <p></p>
                        </div>
                        <span class="flex items-center opacity-70">
                            <span class="h-px flex-1 bg-gradient-to-r from-transparent to-gray-500"></span>

                            <span class="shrink-0 px-4 text-gray-900">Or</span>

                            <span class="h-px flex-1 bg-gradient-to-l from-transparent to-gray-500"></span>
                        </span>

                        <button
                            class="w-full flex flex-row justify-start items-center gap-3 border border-[#1e1e1e]/15 rounded-lg p-4 text-start bg-[#f8f8f8] bg-[#f8f8f8] hover:ring hover:ring-blue-200 hover:border-blue-500 hover:shadow-lg transition duration-150">
                            <div
                                class="flex justify-center items-center border border-[#1e1e1e]/15 p-2 rounded-lg bg-[#199BCF] text-white">
                                <i class="fi fi-rs-mobile-notch text-[24px] flex justify-center items-center"></i>
                            </div>
                            <div>
                                <p class="font-bold">Download Mobile App</p>
                                <p class="opacity-70 font-medium">Get the official Dreamy School app for quick access to
                                    everything you need on the go
                                </p>
                            </div>

                        </button>
                    </div>

                </div>


            </div>

        </div>
    @endsection

@endif

@push('scripts')
    <script type="module">
        document.addEventListener('DOMContentLoaded', function() {


            let applicant = @json($applicant ?? null);

            console.log(applicant.application_status)

            const btn = document.getElementById('upload-btn');
            const input = document.getElementById('fileInput');
            const doc_option = document.getElementById('document-option');
            const fileInputLabel = document.getElementById('fileInputLabel');
            const container = document.getElementById('filesList');


            if (applicant.application_status === 'Pending-Documents') {

                let submittedDocs = @json($submissions ?? null);
                let requiredDocs = @json($documents ?? null);
                let submittedDocsArr = Object.values(submittedDocs);


                const submittedDocsWithStatus = submittedDocsArr.filter(item => ['Pending', 'Verified'].includes(
                    item
                    .status));

                let requiredDocsIds = new Set(requiredDocs.map(item => item.id));

                const matchedItems = submittedDocsWithStatus.filter(item => requiredDocsIds.has(item.documents_id));

                matchedItems.forEach(docs => {

                    //find options with a value matched with the documents_id of the docs
                    const foundOption = Array.from(doc_option.options).find(
                        option => option.value === String(docs.documents_id)
                    )

                    foundOption.disabled = true;

                })

                let uploadedFiles = [];
                let attachedFiles = [];

                updateFileUploadInput()
                updateUploadedFilesList()

                doc_option.addEventListener('change', (event) => {
                    updateFileUploadInput()
                })

                function updateFileUploadInput() {
                    if (doc_option.options[doc_option.selectedIndex].text !== 'Select document type...') {
                        fileInputLabel.classList.remove('opacity-40')
                        fileInputLabel.classList.remove('cursor-not-allowed')
                        input.disabled = false;
                    } else {
                        fileInputLabel.classList.add('opacity-40')
                        fileInputLabel.classList.add('cursor-not-allowed')
                        input.disabled = true;
                    }
                }

                input.addEventListener('change', (event) => {

                    const files = Array.from(event.target.files);

                    files.forEach(file => {
                        uploadedFiles.push({
                            file: file, // actual File object
                            assignedTo: doc_option.value
                        });
                    });

                    files.forEach(file => {
                        attachedFiles.push({
                            name: file.name,
                            assignedTo: doc_option.options[doc_option.selectedIndex].text,
                            docId: doc_option.value
                        });
                    });

                    console.log(uploadedFiles)

                    updateUploadedFilesList()
                    updateButton()

                    let optionId = doc_option.options[doc_option.selectedIndex].value;
                    disableSelection(optionId)
                    updateFileUploadInput()
                    updateSubmitButton()
                    //checkboxState()
                    //setupSubmitButtonWatcher()
                })

                function updateUploadedFilesList() {

                    container.innerHTML = '';

                    attachedFiles.forEach((file, index) => {
                        const docTypeInfo = attachedFiles.find(req => req.docId === file.docId);
                        const item = document.createElement('div');
                        item.className = 'uploaded-file';

                        item.innerHTML = `
                            <div
                                class="flex flex-row justify-between items-center gap-2 bg-[#E7F0FD]/60 border border-[#1e1e1e]/10 px-4 py-2 rounded-md">
                                <div class="flex flex-row items-center gap-2 flex-1">
                                    <!-- Icon -->
                                    <div class="size-10 bg-blue-500 rounded-lg flex justify-center items-center text-white">
                                        <i class="fi fi-ss-document flex justify-center items-center text-[24px]"></i>
                                    </div>

                                    <!-- Text container -->
                                    <div class="flex flex-col justify-center items-start overflow-hidden">
                                        <p class="font-bold opacity-80 leading-tight truncate max-w-[300px]">
                                            ${file.name}
                                        </p>
                                        <p class="opacity-60">${file.assignedTo}</p>
                                    </div>
                                </div>

                                <!-- Remove Button -->
                                <div class="shrink-0">
                                    <button
                                        id="${file.docId}"
                                        class="remove-btn border border-red-500/30 hover:bg-red-500 rounded-lg flex justify-center items-center text-red-500 px-3 py-2 gap-1 hover:text-white hover:ring ring-red-200 transition duration-200">
                                        <i class="fi fi-ss-trash text-[16px]"></i>
                                        Remove
                                    </button>
                                </div>
                            </div>
                    `;
                        container.appendChild(item);
                        console.log(uploadedFiles)

                    });

                    if (uploadedFiles.length === 0) {
                        container.innerHTML = `
                    <div class="empty-state flex flex-col justify-center items-center gap-4">
                        <img src="{{ asset('images/clipboard.png') }}" class="h-[70px] opacity-60" alt="">
                        <div class="opacity-60">No documents uploaded yet</div>
                    </div>
                `;

                    }
                }

                function updateButton() {
                    const removeButton = document.querySelectorAll('.remove-btn');

                    removeButton.forEach((button, index) => {

                        button.addEventListener('click', () => {
                            let docId = button.id;
                            removeDocument(docId);
                        })

                    })
                }

                function removeDocument(docId) {
                    let optionId = doc_option.options[doc_option.selectedIndex].value;
                    uploadedFiles = uploadedFiles.filter(doc => doc.assignedTo !== docId);
                    attachedFiles = attachedFiles.filter(doc => doc.docId !== docId);
                    console.log(uploadedFiles)
                    updateUploadedFilesList()
                    updateButton()
                    enableSelection(docId)
                    updateSubmitButton()
                    //setupSubmitButtonWatcher()
                }

                function disableSelection(optionId) {
                    const foundItem = uploadedFiles.find(item => item.assignedTo === optionId);
                    const foundOption = Array.from(doc_option.options).find(
                        option => option.value === foundItem.assignedTo
                    );
                    foundOption.disabled = true
                    doc_option.selectedIndex = 0;
                }

                function enableSelection(optionId) {
                    const foundOption = Array.from(doc_option.options).find(
                        option => option.value === optionId
                    );
                    foundOption.disabled = false
                    doc_option.selectedIndex = 0;
                }

                function updateSubmitButton() {
                    const submitBtn = document.getElementById('submitBtn');
                    const checkbox = document.getElementById('consent');

                    if (uploadedFiles.length <= 0) {
                        disableSubmitButton()
                    } else if (uploadedFiles.length > 0 && checkbox.checked === true) {
                        enableSubmitButton()
                    } else {
                        checkboxState()
                    }

                }

                (function checkboxState() {

                    const checkbox = document.getElementById('consent');

                    checkbox.addEventListener('change', () => {

                        if (uploadedFiles.length <= 0 && checkbox.checked === true) {
                            disableSubmitButton()
                        } else if (uploadedFiles.length > 0 && checkbox.checked ===
                            true) {
                            enableSubmitButton()
                        } else {
                            disableSubmitButton()
                        }

                    })

                })()

                function enableSubmitButton() {
                    submitBtn.classList.remove('opacity-50');
                    submitBtn.classList.remove('cursor-not-allowed');
                    submitBtn.disabled = false
                }

                function disableSubmitButton() {
                    submitBtn.classList.add('opacity-50');
                    submitBtn.classList.add('cursor-not-allowed');
                    submitBtn.disabled = true
                }


                const form = document.getElementById('uploadForm')
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const fileInput = document.getElementById('fileInput');
                    const files = fileInput.files;

                    const formData = new FormData();

                    console.log(files)

                    uploadedFiles.forEach((item, i) => {
                        // Add the file
                        formData.append(`documents[${i}]`, item.file);

                        // Add the assigned option (or use files_assigned[i] = ...)
                        formData.append(`documents_id[${i}]`, item.assignedTo);
                    });

                    console.log(formData)

                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content');

                    axios.post('/submit-document', formData, {
                            headers: {
                                'X-CSRF-TOKEN': csrfToken
                                // Do NOT set Content-Type manually — Axios handles it
                            }
                        })
                        .then(response => {
                            console.log('Upload successful:', response.data);
                            window.location.reload();
                        })
                        .catch(error => {
                            console.error('Upload failed:', error.response?.data || error.message);
                            window.location.reload();
                        });

                });

            }

            if (applicant.application_status === null) {
                window.Echo.channel('updating-enrollment-period-status').listen('EnrollmentPeriodStatusUpdated', (
                    event) => {
                    console.log('Enrollment period status updated:', event.enrollmentPeriod.status);
                    const status = event.enrollmentPeriod.status;
                    const dbText = document.getElementById('db-text');
                    const epstatus = document.getElementById('ep-status');
                    const zspan = document.getElementById('zspan');
                    const btnContainer = document.getElementById('btn-container');

                    if (status === 'Paused') {

                        dbText.textContent =
                            'Enrollment period is temporarily closed. At this time, we are not accepting any new applications.';
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

                            if (isset($currentAcadTerm)) {
                                $termName = $currentAcadTerm ? $currentAcadTerm->getFullNameAttribute() : '-';
                            }

                        @endphp

                        dbText.innerHTML =
                            `Enrollment for the academic year <i class='font-bold'>${termName}</i> has ended. We are no longer accepting new applications.`;
                        zspan.textContent = '';
                        btnContainer.classList.add('hidden');
                        dbText.classList.remove('font-bold');
                        dbText.classList.add('font-medium');
                        window.location.reload();

                    }
                });
            }





        });
    </script>
@endpush
