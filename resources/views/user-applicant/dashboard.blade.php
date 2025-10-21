@extends('layouts.admission')

@section('alert')
    <x-alert />
@endsection

@if ($applicant->application_status === null)
    @section('content')
        <div class="flex flex-col h-full py-10 px-6 md:px-2 gap-6">

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
                        <div data-current-term="{{ $currentAcadTerm->getFullNameAttribute() }}">
                            <p id="db-text" class="text-[18px] md:text-[20px] font-bold p-10">
                                Enrollment period is temporarily closed. At this time, we are not accepting any new
                                applications.
                            </p>
                            <p id="zspan" class="text-[16px] md:text-[18px] font-medium hidden">
                                <!-- Hidden by default for paused status -->
                            </p>
                            <div id="btn-container" class="flex flex-col justify-center items-center flex-grow m-8 hidden">
                                <!-- Hidden by default for paused status -->
                            </div>
                        </div>
                    @elseif ($activeEnrollmentPeriod->status === 'Closed')
                        <div data-current-term="{{ $currentAcadTerm->getFullNameAttribute() }}">
                            <p id="db-text" class="text-[18px] md:text-[20px] font-bold p-10">
                                Enrollment for the academic year {{ $currentAcadTerm->getFullNameAttribute() }} has ended.
                                We
                                are no
                                longer accepting new applications.
                            </p>
                            <p id="zspan" class="text-[16px] md:text-[18px] font-medium hidden">
                                <!-- Hidden by default for closed status -->
                            </p>
                            <div id="btn-container" class="flex flex-col justify-center items-center flex-grow m-8 hidden">
                                <!-- Hidden by default for closed status -->
                            </div>
                        </div>
                    @elseif ($activeEnrollmentPeriod->status === 'Ongoing')
                        <div data-current-term="{{ $currentAcadTerm->getFullNameAttribute() }}">
                            <p id="db-text" class="text-[18px] md:text-[22px] font-bold p-2">
                                Welcome to Dreamy School' Online Registration for
                                {{ $currentAcadTerm->getFullNameAttribute() }}
                            </p>
                            <p id="zspan" class="text-[16px] md:text-[18px] font-medium">
                                Please click the button below to fill out the form.
                            </p>
                            <div id="btn-container" class="flex flex-col justify-center items-center flex-grow mt-8">
                                <a href="/admission/application-form"
                                    class="bg-[#199BCF]/80 text-white px-6 py-3 rounded-full hover:bg-[#1689b8] transition-colors duration-200 backdrop-blur-sm shadow-lg text-[16px] font-bold inline-block text-center">
                                    Get Started
                                </a>
                            </div>
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
                        <p id="total-registrations" class="md:text-[20px] font-black">0</p>
                        <p class="md:text-[14px] opacity-60">Applications Received</p>
                    </div>
                    <div
                        class="flex flex-col space-y-2 justify-center items-center bg-[#E3ECFF]/60 py-6 px-10 gap-2 rounded-md">
                        <p class="md:text-[16px] font-semibold opacity-90">Successful Applicants</p>
                        <p id="successful-applicants" class="md:text-[20px] font-black">0</p>
                        <p id="acceptance-rate" class="md:text-[14px] opacity-60">0% Acceptance rate</p>
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
        class="bg-[#f8f8f8] w-full flex flex-col md:flex-row justify-between items-center rounded-xl border border-[#1e1e1e]/20 p-6 sticky top-0 z-10 gap-2 ">
        {{-- Status Badge --}}
        <div class="flex flex-row justify-center items-center gap-2">
            <p class="md:text-[16px] font-medium text-gray-600">Application status:</p>
            @if ($applicant->application_status == 'Pending')
                <div
                    class="bg-yellow-50 border border-yellow-300 flex flex-row justify-center items-center py-1 px-2 rounded-full gap-2">
                    <i class="fi fi-ss-pending text-[14px] text-yellow-500 flex justify-center items-center"></i>
                    <p class="text-yellow-500 text-[14px] font-semibold">Pending</p>
                </div>
            @endif

            @if ($applicant->application_status == 'Accepted')
                @if ($applicant->interview->status == null)
                    <div
                        class="bg-[#E6F4EA] border border-[#34A853]/60 flex flex-row justify-center items-center py-1 px-2 rounded-full gap-1">
                        <i class="fi fi-ss-check-circle text-[#34A853] text-[14px] flex justify-center items-center"></i>
                        <p class="text-[#34A853] font-semibold text-[14px]">Accepted</p>
                    </div>
                @elseif ($applicant->interview->status == 'Scheduled')
                    <div
                        class="bg-blue-50 border border-blue-300 flex flex-row justify-center items-center py-1 px-2 rounded-full gap-2">
                        <i class="fi fi-ss-check-circle text-[14px] text-blue-500 flex justify-center items-center"></i>
                        <p class="text-blue-500 font-semibold text-[14px]">Scheduled</p>
                    </div>
                @elseif ($applicant->interview->status == 'Taking-Exam')
                    <div
                        class="bg-yellow-50 border border-yellow-300 flex flex-row justify-center items-center py-1 px-2 rounded-full gap-2">
                        <i class="fi fi-ss-clock text-[14px] text-yellow-500 flex justify-center items-center"></i>
                        <p class="text-yellow-500 text-[14px] font-semibold">Waiting For Result</p>
                    </div>
                @endif
            @endif

            @if ($applicant->application_status == 'Rejected')
                <div
                    class="bg-red-50 border border-red-300 flex flex-row justify-center items-center py-1 px-2 rounded-full gap-1">
                    <i class="fi fi-ss-cross-circle text-red-500 text-[14px] flex justify-center items-center"></i>
                    <p class="text-red-500 font-semibold text-[14px]">Rejected</p>
                </div>
            @endif

            @if ($applicant->application_status == 'Completed-Failed')
                @if ($applicant->interview->status == 'Exam-Failed')
                    <div
                        class="bg-red-50 border border-red-300 flex flex-row justify-center items-center py-1 px-2 rounded-full gap-1">
                        <i class="fi fi-ss-cross-circle text-red-500 text-[14px] flex justify-center items-center"></i>
                        <p class="text-red-500 font-semibold text-[14px]">Exam-Failed</p>
                    </div>
                @endif
            @endif

            @if ($applicant->application_status == 'Pending-Documents' && $applicant->interview->status == 'Exam-Passed')
                <div
                    class="bg-green-50 border border-green-500/60 flex flex-row justify-center items-center py-1 px-2 rounded-full gap-2">
                    <i class="fi fi-ss-check text-[14px] text-green-500 flex justify-center items-center"></i>
                    <p class="text-green-500 font-semibold text-[14px]">Exam-Passed</p>
                </div>
            @endif

            @if ($applicant->application_status == 'Pending-Documents' && $applicant->interview->status == 'Exam-Completed')
                <div
                    class="bg-orange-50 border border-orange-300 flex flex-row justify-center items-center py-1 px-2 rounded-full gap-2">
                    <i class="fi fi-rs-folder-times text-[14px] text-orange-500 flex justify-center items-center"></i>
                    <p class="text-orange-500 text-[14px] font-semibold">Pending-Documents</p>
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
        <div class="flex flex-col md:flex-row justify-center items-start md:items-center gap-1 md:gap-2 overflow-x-auto">
            {{-- Step 1: Fill out form --}}
            <div class="flex flex-row justify-center items-center gap-1 md:gap-2 min-w-0 flex-shrink-0">
                @if (in_array($applicant->application_status, [
                        'Pending',
                        'Accepted',
                        'Rejected',
                        'Pending-Documents',
                        'Completed-Failed',
                        'Officially Enrolled',
                    ]))
                    <div
                        class="flex justify-center items-center bg-[#34A853] rounded-full text-white size-[24px] md:size-[26px]">
                        <i class="fi fi-ss-check flex justify-center items-center text-[12px] md:text-[14px]"></i>
                    </div>
                @else
                    <div
                        class="flex justify-center items-center bg-gray-400 rounded-full text-white size-[24px] md:size-[26px]">
                        <p class="font-bold text-[14px] md:text-[16px]">1</p>
                    </div>
                @endif
                <p class="text-[12px] md:text-[14px] font-semibold whitespace-nowrap">Fill out form</p>
                <div class="hidden md:block text-gray-400">—</div>
            </div>

            {{-- Step 2: Take examination --}}
            <div class="flex flex-row justify-center items-center gap-1 md:gap-2 min-w-0 flex-shrink-0">
                @if (in_array($applicant->application_status, [
                        'Accepted',
                        'Pending-Documents',
                        'Completed-Failed',
                        'Officially Enrolled',
                    ]))
                    <div
                        class="flex justify-center items-center bg-[#34A853] rounded-full text-white size-[24px] md:size-[26px]">
                        <i class="fi fi-ss-check flex justify-center items-center text-[12px] md:text-[14px]"></i>
                    </div>
                @else
                    <div
                        class="flex justify-center items-center bg-gray-400 rounded-full text-white size-[24px] md:size-[26px]">
                        <p class="font-bold text-[14px] md:text-[16px]">2</p>
                    </div>
                @endif
                <p class="text-[12px] md:text-[14px] font-semibold whitespace-nowrap">Take examination</p>
                <div class="hidden md:block text-gray-400">—</div>
            </div>

            {{-- Step 3: Get Result --}}
            <div class="flex flex-row justify-center items-center gap-1 md:gap-2 min-w-0 flex-shrink-0">
                @if (in_array($applicant->application_status, ['Pending-Documents', 'Officially Enrolled', 'Completed-Failed']))
                    <div
                        class="flex justify-center items-center bg-[#34A853] rounded-full text-white size-[24px] md:size-[26px]">
                        <i class="fi fi-ss-check flex justify-center items-center text-[12px] md:text-[14px]"></i>
                    </div>
                @else
                    <div
                        class="flex justify-center items-center bg-gray-400 rounded-full text-white size-[24px] md:size-[26px]">
                        <p class="font-bold text-[14px] md:text-[16px]">3</p>
                    </div>
                @endif
                <p class="text-[12px] md:text-[14px] font-semibold whitespace-nowrap">Get Result</p>
                <div class="hidden md:block text-gray-400">—</div>
            </div>

            {{-- Step 4: Submit documents --}}
            <div class="flex flex-row justify-center items-center gap-1 md:gap-2 min-w-0 flex-shrink-0">
                @if ($applicant->application_status == 'Officially Enrolled')
                    <div
                        class="flex justify-center items-center bg-[#34A853] rounded-full text-white size-[24px] md:size-[26px]">
                        <i class="fi fi-ss-check flex justify-center items-center text-[12px] md:text-[14px]"></i>
                    </div>
                @elseif ($applicant->application_status == 'Pending-Documents')
                    <div
                        class="flex justify-center items-center bg-yellow-500 rounded-full text-white size-[24px] md:size-[26px]">
                        <i class="fi fi-ss-clock flex justify-center items-center text-[12px] md:text-[14px]"></i>
                    </div>
                @else
                    <div
                        class="flex justify-center items-center bg-gray-400 rounded-full text-white size-[24px] md:size-[26px]">
                        <p class="font-bold text-[14px] md:text-[16px]">4</p>
                    </div>
                @endif
                <p class="text-[12px] md:text-[14px] font-semibold whitespace-nowrap">Submit documents</p>
            </div>
        </div>
    </div>

@endsection

@if ($applicant->application_status === 'Pending')
    @section('pending')
        <div class="flex flex-col justify-center items-center h-full w-full space-y-2">

            <div
                class="bg-[#f8f8f8] flex flex-col rounded-xl border border-[#1e1e1e]/20 justify-center py-3 px-3 md:py-4 md:px-6">
                <div class="flex flex-row justify-start items-center gap-2 md:gap-3">

                    <div
                        class="text-[16px] md:text-[20px] text-white bg-[#0f111c] size-[30px] md:size-[35px] rounded-full flex justify-center items-center">
                        1
                    </div>
                    <div class="flex flex-col justify-center items-start">
                        <p class="text-[14px] md:text-[16px] font-semibold text-gray-800">Fill out enrollment form</p>
                        <p class="text-[12px] md:text-[14px] font-medium text-gray-500">Last update:
                            {{ \Carbon\Carbon::parse($applicant->updated_at)->timezone('Asia/Manila')->format('M. d, Y - g:i A') }}
                        </p>
                    </div>

                </div>
                <x-divider class="mt-3 md:mt-4 opacity-15"></x-divider>
                <div
                    class="w-[90%] md:w-[80%] flex flex-row justify-center items-center text-center self-center mt-4 md:mt-8">
                    <div class="flex flex-col justify-center items-center gap-1">
                        <h2 class="font-semibold text-[16px] md:text-[18px] text-gray-800">Your application is currently
                            awaiting review
                        </h2>
                        <p
                            class="self-center text-center text-[12px] md:text-[14px] text-gray-500 px-4 md:px-22 mb-4">
                            Please check back later or
                            contact the Admissions Office if you need
                            further assistance or updates.</p>
                        <p
                            class="self-center text-center text-[12px] md:text-[14px] text-gray-500 px-4 md:px-22 mb-4 md:mb-8">
                            You will receive an email notification once your application form has been reviewed and accepted.</p>
                    </div>
                </div>
                <div
                    class="space-y-2 md:space-y-3 border border-[#1e1e1e]/10 p-3 md:p-4 rounded-xl bg-[#E3ECFF]/20 w-full md:w-[90%] self-center">
                    <div
                        class=" border border-[#1e1e1e]/15 rounded-[8px] hover:shadow-xl hover:border-[#199BCF]/50 transition duration-200">
                        <table class="text-[#0f111c] w-full">
                            <thead class="">
                                <tr class="">
                                    <th
                                        class="px-3 md:px-6 py-2 bg-[#E3ECFF] text-start rounded-tl-[8px] truncate text-[14px] md:text-[16px]">
                                        Learner
                                        Information</th>
                                    <th class="bg-[#E3ECFF] text-start rounded-tr-[8px]"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b border-t border-[#1e1e1e]/15 opacity-[0.87]">
                                    <td class="px-3 md:px-6 py-2 text-[12px] md:text-[14px]">Returning (Balik-Aral):</td>
                                </tr>
                                <tr class="opacity-[0.87]">
                                    <td
                                        class="px-3 md:px-6 py-2 text-[12px] md:text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2 text-bold">
                                        With
                                        LRN:<span class="font-bold"> Yes</span></td>
                                    <td
                                        class="px-3 md:px-6 py-2 text-[12px] md:text-[14px] border-b border-[#1e1e1e]/15 w-1/2 text-bold">
                                        LRN:
                                        <span class="font-bold">{{ $applicant->applicationForm->lrn ?? '-' }}</span>
                                    </td>
                                </tr>
                                <tr class="opacity-[0.87]">
                                    <td
                                        class="px-3 md:px-6 py-2 text-[12px] md:text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">
                                        Grade
                                        Level to
                                        Enroll:<span class="font-bold">
                                            {{ $applicant->applicationForm->grade_level ?? '-' }}</span>
                                    </td>
                                    <td
                                        class="px-3 md:px-6 py-2 text-[12px] md:text-[14px] border-b border-[#1e1e1e]/15 w-1/2">
                                        Semester:<span class="font-bold">
                                            {{ $applicant->applicationForm->semester_applied ?? '-' }}</span></td>
                                </tr>
                                <tr class="opacity-[0.87]">
                                    <td
                                        class="px-3 md:px-6 py-2 text-[12px] md:text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">
                                        Primary
                                        Track:<span class="font-bold"> {{ $applicant->track->name ?? '-' }}</span></td>
                                    <td
                                        class="px-3 md:px-6 py-2 text-[12px] md:text-[14px] border-b border-[#1e1e1e]/15 w-1/2 truncate-2">
                                        Secondary
                                        Track:<span class="font-bold">
                                            {{ $applicant->program->code . ' - ' . $applicant->program->name ?? '-' }}</span>
                                    </td>
                                </tr>
                                <tr class="opacity-[0.87]">
                                    <td
                                        class="px-3 md:px-6 py-2 text-[12px] md:text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">
                                        Last
                                        Name:<span class="font-bold">
                                            {{ $applicant->applicationForm->first_name ?? '-' }}</span></td>
                                    <td
                                        class="px-3 md:px-6 py-2 text-[12px] md:text-[14px] border-b border-[#1e1e1e]/15 w-1/2 truncate">
                                        First
                                        Name:<span class="font-bold">
                                            {{ $applicant->applicationForm->last_name ?? '-' }}</span>
                                    </td>
                                </tr>
                                <tr class="opacity-[0.87]">
                                    <td
                                        class="px-3 md:px-6 py-2 text-[12px] md:text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">
                                        Middle
                                        Name:<span class="font-bold">
                                            {{ $applicant->applicationForm->middle_name ?? '-' }}</span></td>
                                    <td
                                        class="px-3 md:px-6 py-2 text-[12px] md:text-[14px] border-b border-[#1e1e1e]/15 w-1/2">
                                        Extension
                                        Name:<span class="font-bold">
                                            {{ $applicant->applicationForm->extension_name ?? '-' }}</span></td>
                                </tr>
                                <tr class="opacity-[0.87]">
                                    <td
                                        class="px-3 md:px-6 py-2 text-[12px] md:text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">
                                        Birthdate:<span class="font-bold">
                                            {{ \Carbon\Carbon::parse($applicant->applicationForm->birthdate)->timezone('Asia/Manila')->format('M. d, Y') }}
                                    </td>
                                    <td
                                        class="px-3 md:px-6 py-2 text-[12px] md:text-[14px] border-b border-[#1e1e1e]/15 w-1/2">
                                        Age:<span class="font-bold">
                                            {{ $applicant->applicationForm->age ?? '-' }}</span></td>
                                </tr>
                                <tr class="opacity-[0.87]">
                                    <td
                                        class="px-3 md:px-6 py-2 text-[12px] md:text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">
                                        Place of
                                        Birth:<span class="font-bold">
                                            {{ $applicant->applicationForm->place_of_birth ?? '-' }}</span></td>
                                    <td
                                        class="px-3 md:px-6 py-2 text-[12px] md:text-[14px] border-b border-[#1e1e1e]/15 w-1/2">
                                        Mother
                                        Tongue:<span class="font-bold">
                                            {{ $applicant->applicationForm->mother_tongue ?? '-' }}</span></td>
                                </tr>
                                <tr class="opacity-[0.87]">
                                    <td
                                        class="px-3 md:px-6 py-2 text-[12px] md:text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">
                                        Belong to
                                        any IP
                                        community:<span class="font-bold">
                                            {{ $applicant->applicationForm->belongs_to_ip === 1 ? 'Yes' : 'No' }}</span>
                                    </td>
                                    <td
                                        class="px-3 md:px-6 py-2 text-[12px] md:text-[14px] border-b border-[#1e1e1e]/15 w-1/2">
                                        Beneficiary of
                                        4Ps:<span class="font-bold">
                                            {{ $applicant->applicationForm->is_4ps_beneficiary === 1 ? 'Yes' : 'No' }}</span>
                                    </td>
                                </tr>
                                <tr class="opacity-[0.87]">
                                    <td class="px-3 md:px-6 py-2 text-[12px] md:text-[14px] border-r border-[#1e1e1e]/15">
                                        Learner with disability:
                                        <span class="font-bold">
                                            {{ $applicant->applicationForm->has_special_needs === 1 ? 'Yes' : 'No' }}</span>
                                    </td>
                                    <td class="px-3 md:px-6 py-2 text-[12px] md:text-[14px]">Special needs: <span
                                            class="font-bold">
                                            {{ implode(', ', $applicant->applicationForm->special_needs ?? []) }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div
                        class=" border border-[#1e1e1e]/15 rounded-[8px] hover:shadow-xl hover:border-[#199BCF]/50 transition duration-200">
                        <table class="text-[#0f111c] w-full">
                            <thead class="">
                                <tr class="">
                                    <th
                                        class="border-r border-[#1e1e1e]/15 px-3 md:px-6 py-2 bg-[#E3ECFF] text-start rounded-tl-[8px] text-[14px] md:text-[16px]">
                                        Current Address</th>
                                    <th
                                        class="px-3 md:px-6 py-2 bg-[#E3ECFF] text-start rounded-tr-[8px] text-[14px] md:text-[16px]">
                                        Permanent
                                        Address</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="opacity-[0.87]">
                                    <td
                                        class="px-3 md:px-6 py-2 text-[12px] md:text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2 text-bold">
                                        House
                                        No:<span class="font-bold">
                                            {{ $applicant->applicationForm->cur_house_no ?? '-' }}</span>
                                    </td>
                                    <td
                                        class="px-3 md:px-6 py-2 text-[12px] md:text-[14px] border-b border-[#1e1e1e]/15 w-1/2">
                                        House No:
                                        <span class="font-bold">
                                            {{ $applicant->applicationForm->perm_house_no ?? '-' }}</span>
                                    </td>
                                </tr>
                                <tr class="opacity-[0.87]">
                                    <td
                                        class="px-3 md:px-6 py-2 text-[12px] md:text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">
                                        Sitio/Street Name:
                                        <span class="font-bold">
                                            {{ $applicant->applicationForm->cur_street ?? '-' }}</span>
                                    </td>
                                    <td
                                        class="px-3 md:px-6 py-2 text-[12px] md:text-[14px] border-b border-[#1e1e1e]/15 w-1/2">
                                        Sitio/Street
                                        Name:<span class="font-bold">
                                            {{ $applicant->applicationForm->perm_street ?? '-' }}</span>
                                    </td>
                                </tr>
                                <tr class="opacity-[0.87]">
                                    <td
                                        class="px-3 md:px-6 py-2 text-[12px] md:text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">
                                        Barangay:<span class="font-bold">
                                            {{ $applicant->applicationForm->cur_barangay ?? '-' }}</span></td>
                                    <td
                                        class="px-3 md:px-6 py-2 text-[12px] md:text-[14px] border-b border-[#1e1e1e]/15 w-1/2">
                                        Barangay: <span class="font-bold">
                                            {{ $applicant->applicationForm->perm_barangay ?? '-' }}</span></td>
                                </tr>
                                <tr class="opacity-[0.87]">
                                    <td
                                        class="px-3 md:px-6 py-2 text-[12px] md:text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">
                                        Municipality/City:<span class="font-bold">
                                            {{ $applicant->applicationForm->cur_city ?? '-' }}</span>
                                    </td>
                                    <td
                                        class="px-3 md:px-6 py-2 text-[12px] md:text-[14px] border-b border-[#1e1e1e]/15 w-1/2">
                                        Municipality/City:<span class="font-bold">
                                            {{ $applicant->applicationForm->perm_city ?? '-' }}</span></td>
                                </tr>
                                <tr class="opacity-[0.87]">
                                    <td
                                        class="px-3 md:px-6 py-2 text-[12px] md:text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">
                                        Country:<span class="font-bold">
                                            {{ $applicant->applicationForm->cur_country ?? '-' }}</span></td>
                                    <td
                                        class="px-3 md:px-6 py-2 text-[12px] md:text-[14px] border-b border-[#1e1e1e]/15 w-1/2">
                                        Country:<span class="font-bold">
                                            {{ $applicant->applicationForm->perm_country ?? '-' }}</span></td>
                                </tr>
                                <tr class="opacity-[0.87]">
                                    <td
                                        class="px-3 md:px-6 py-2 text-[12px] md:text-[14px] border-r border-[#1e1e1e]/15 w-1/2">
                                        Zip Code: <span class="font-bold">
                                            {{ $applicant->applicationForm->cur_zip_code ?? '-' }}</span></td>
                                    <td class="px-3 md:px-6 py-2 text-[12px] md:text-[14px] w-1/2">Zip Code: <span
                                            class="font-bold">
                                            {{ $applicant->applicationForm->perm_zip_code ?? '-' }}</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div
                        class=" border border-[#1e1e1e]/15 rounded-[8px] hover:shadow-xl hover:border-[#199BCF]/50 transition duration-200">
                        <table class="text-[#0f111c] w-full table-fixed">
                            <thead class="">
                                <tr class="">
                                    <th colspan="3"
                                        class="border-b border-[#1e1e1e]/15 px-3 md:px-6 py-2 bg-[#E3ECFF] text-start rounded-tl-[8px] text-[14px] md:text-[16px] truncate">
                                        Parent/Guardian's Information</th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr class="opacity-[0.87]">
                                    <td
                                        class="px-3 md:px-6 py-2 text-[14px] md:text-[16px] border-b border-r border-[#1e1e1e]/15 font-bold">
                                        Mother's
                                        Information:</td>
                                    <td
                                        class="px-3 md:px-6 py-2 text-[14px] md:text-[16px] border-b border-r border-[#1e1e1e]/15 font-bold">
                                        Father's
                                        Information:<span class="font-bold"></span></td>
                                    <td
                                        class="px-3 md:px-6 py-2 text-[14px] md:text-[16px] border-b border-[#1e1e1e]/15 font-bold">
                                        Guardian's
                                        Information:<span class="font-bold"></span></td>
                                </tr>
                                <tr class="opacity-[0.87]">
                                    <td
                                        class="px-3 md:px-6 py-2 text-[12px] md:text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">
                                        Last
                                        Name:<span class="font-bold">
                                            {{ $applicant->applicationForm->mother_last_name ?? '-' }}</span></td>
                                    <td
                                        class="px-3 md:px-6 py-2 text-[12px] md:text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">
                                        Last
                                        Name:<span class="font-bold">
                                            {{ $applicant->applicationForm->father_last_name ?? '-' }}</span></td>
                                    <td
                                        class="px-3 md:px-6 py-2 text-[12px] md:text-[14px] border-b border-[#1e1e1e]/15 w-1/2">
                                        Last Name:<span class="font-bold">
                                            {{ $applicant->applicationForm->guardian_last_name ?? '-' }}</span>
                                    </td>
                                </tr>
                                <tr class="opacity-[0.87]">
                                    <td
                                        class="px-3 md:px-6 py-2 text-[12px] md:text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">
                                        First
                                        Name:<span class="font-bold">
                                            {{ $applicant->applicationForm->mother_first_name ?? '-' }}</span></td>
                                    <td
                                        class="px-3 md:px-6 py-2 text-[12px] md:text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">
                                        First
                                        Name:<span class="font-bold">
                                            {{ $applicant->applicationForm->father_first_name ?? '-' }}</span></td>
                                    <td
                                        class="px-3 md:px-6 py-2 text-[12px] md:text-[14px] border-b border-[#1e1e1e]/15 w-1/2">
                                        First Name:<span class="font-bold">
                                            {{ $applicant->applicationForm->guardian_first_name ?? '-' }}</span>
                                    </td>
                                </tr>
                                <tr class="opacity-[0.87]">
                                    <td
                                        class="px-3 md:px-6 py-2 text-[12px] md:text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">
                                        Middle
                                        Name:<span class="font-bold">
                                            {{ $applicant->applicationForm->mother_middle_name ?? '-' }}</span>
                                    </td>
                                    <td
                                        class="px-3 md:px-6 py-2 text-[12px] md:text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">
                                        Middle
                                        Name:<span class="font-bold">
                                            {{ $applicant->applicationForm->father_middle_name ?? '-' }}</span>
                                    </td>
                                    <td
                                        class="px-3 md:px-6 py-2 text-[12px] md:text-[14px] border-b border-[#1e1e1e]/15 w-1/2">
                                        Middle Name:<span class="font-bold">
                                            {{ $applicant->applicationForm->guardian_middle_name ?? '-' }}</span>
                                    </td>
                                </tr>
                                <tr class="opacity-[0.87]">
                                    <td
                                        class="px-3 md:px-6 py-2 text-[12px] md:text-[14px] border-r border-[#1e1e1e]/15 w-1/2">
                                        Contact
                                        Number:<span class="font-bold">
                                            {{ $applicant->applicationForm->mother_contact_number ?? '-' }}</span>
                                    </td>
                                    <td
                                        class="px-3 md:px-6 py-2 text-[12px] md:text-[14px] border-r border-[#1e1e1e]/15 w-1/2">
                                        Contact
                                        Number:<span class="font-bold">
                                            {{ $applicant->applicationForm->father_contact_number ?? '-' }}</span>
                                    </td>
                                    <td class="px-3 md:px-6 py-2 text-[12px] md:text-[14px] w-1/2">Contact Number:<span
                                            class="font-bold">
                                            {{ $applicant->applicationForm->guardian_contact_number ?? '-' }}</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div
                        class=" border border-[#1e1e1e]/15 rounded-[8px] hover:shadow-xl hover:border-[#199BCF]/50 transition duration-200">
                        <table class="text-[#0f111c] w-full">
                            <thead class="">
                                <tr class="">
                                    <th
                                        class="border-b border-[#1e1e1e]/15 px-3 md:px-6 py-2 bg-[#E3ECFF] text-start rounded-tl-[8px] text-[14px] md:text-[16px]">
                                        Other Informations </th>
                                    <th
                                        class="border-b border-[#1e1e1e]/15 px-3 md:px-6 py-2 bg-[#E3ECFF] text-start rounded-tr-[8px] text-[14px] md:text-[16px]">
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="opacity-[0.87]">
                                    <td
                                        class="px-3 md:px-6 py-2 text-[12px] md:text-[14px] border-b border-[#1e1e1e]/15 w-1/2 text-bold truncate">
                                        Preferred Class
                                        Schedule:<span class="font-bold">
                                            {{ $applicant->applicationForm->preferred_sched ?? '-' }}</span></td>
                                    <td
                                        class="px-3 md:px-6 py-2 text-[12px] md:text-[14px] border-b border-[#1e1e1e]/15 w-1/2 text-bold">
                                    </td>
                                </tr>
                                <tr class="opacity-[0.87]">
                                    <td
                                        class="px-3 md:px-6 py-2 text-[12px] md:text-[14px] border-b border-[#1e1e1e]/15 w-1/2 text-bold">
                                        Last
                                        Grade Level Completed:<span class="font-bold">
                                            {{ $applicant->applicationForm->last_grade_level_completed ?? '-' }}</span>
                                    </td>
                                    <td
                                        class="px-3 md:px-6 py-2 text-[12px] md:text-[14px] border-b border-[#1e1e1e]/15 w-1/2 text-bold">
                                    </td>
                                </tr>
                                <tr class="opacity-[0.87]">
                                    <td
                                        class="px-3 md:px-6 py-2 text-[12px] md:text-[14px] border-b border-[#1e1e1e]/15 w-1/2 text-bold">
                                        Lat
                                        School Attended:<span class="font-bold">
                                            {{ $applicant->applicationForm->last_school_attended ?? '-' }}</span></td>
                                    <td
                                        class="px-3 md:px-6 py-2 text-[12px] md:text-[14px] border-b border-[#1e1e1e]/15 w-1/2 text-bold">
                                    </td>
                                </tr>
                                <tr class="opacity-[0.87]">
                                    <td
                                        class="px-3 md:px-6 py-2 text-[12px] md:text-[14px] border-b border-[#1e1e1e]/15 w-1/2 text-bold truncate">
                                        Last
                                        School Year Completed:<span class="font-bold">
                                            {{ \Carbon\Carbon::parse($applicant->applicationForm->last_school_year_completed)->timezone('Asia/Manila')->format('M. d, Y') ?? '-' }}</span>
                                    </td>
                                    <td
                                        class="px-3 md:px-6 py-2 text-[12px] md:text-[14px] border-b border-[#1e1e1e]/15 w-1/2 text-bold">
                                    </td>
                                </tr>
                                <tr class="opacity-[0.87]">
                                    <td
                                        class="px-3 md:px-6 py-2 text-[12px] md:text-[14px] border-b border-[#1e1e1e]/15 w-1/2 text-bold">
                                        School
                                        Id:<span class="font-bold">
                                            {{ $applicant->applicationForm->school_id ?? '-' }}</span></td>
                                    <td
                                        class="px-3 md:px-6 py-2 text-[12px] md:text-[14px] border-b border-[#1e1e1e]/15 w-1/2 text-bold">
                                    </td>
                                </tr>
                                <tr class="opacity-[0.87]">
                                    <td class="px-3 md:px-6 py-2 text-[12px] md:text-[14px] w-1/2 truncate">Date
                                        Applied:<span class="font-bold">
                                            {{ \Carbon\Carbon::parse($applicant->applicationForm->admission_date)->timezone('Asia/Manila')->format('M. d, Y — g:i A') ?? '-' }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>
    @endsection

@endif

@if ($applicant->application_status === 'Accepted')
    @section('accepted')
        <div class="bg-[#f8f8f8] flex flex-col rounded-xl border border-[#1e1e1e]/20 md:w-full justify-center py-4 px-6">
            <div class="flex flex-row justify-start items-center gap-3">

                <div class="text-[20px] text-white bg-[#0f111c] size-[35px] rounded-full flex justify-center items-center">
                    2
                </div>
                <div class="flex flex-col justify-center items-start">
                    <p class="text-[16px] font-semibold text-gray-800">Take admission exam at school</p>
                    <p class="text-[14px] font-medium text-gray-500">Last update:
                        {{ \Carbon\Carbon::parse($applicant->interview->updated_at)->timezone('Asia/Manila')->format('M. d, Y — g:i A') }}
                    </p>
                </div>

            </div>
            <x-divider class="my-4 opacity-15"></x-divider>

            @if ($applicant->interview() && $applicant->interview->status === null)
                <div class="flex flex-col justify-center items-center space-y-4 md:space-y-6 py-4">
                    <div class="flex flex-col justify-center items-center gap-1">
                        <h2 class="font-semibold text-[16px] md:text-[18px] text-gray-800">Awaiting Your Admission Exam
                            Schedule</h2>
                        <p
                            class="self-center text-center text-[12px] md:text-[14px] text-gray-500 px-4 md:px-22 mb-4 md:mb-8">
                            Your exam schedule will be
                            available soon. Please check back later or contact the <br class="hidden md:block"> Admissions
                            Office if you need
                            further assistance or updates.</p>
                    </div>

                    <img src="{{ asset('images/Waiting.svg') }}" alt=""
                        class="size-[180px] md:size-[200px] lg:size-[250px] mx-auto mt-6 md:mt-10 mb-4 md:mb-6">

                </div>
            @elseif ($applicant->interview() && $applicant->interview->status === 'Scheduled')
                <div class="flex flex-col justify-center items-center space-y-3 md:space-y-4 py-6 md:py-8">

                    <div class="flex flex-col justify-center items-center gap-1">
                        <p class="font-semibold text-[16px] md:text-[18px] text-gray-800">Your admission exam has been
                            scheduled!</p>
                        <p
                            class="self-center text-center text-[12px] md:text-[14px] text-gray-500 px-4 md:px-22 mb-4 md:mb-8">
                            Everything is set up for
                            your upcoming interview. Please review the details below
                            and make sure to
                            arrive on time. </p>
                    </div>

                    <div
                        class="w-full md:w-[70%] border border-[#1e1e1e]/10 px-3 md:px-2 py-3 md:py-4 rounded-xl bg-[#E3ECFF]/20">

                        <div class="flex flex-row justify-center items-center gap-2 mb-3 md:mb-4">
                            <p class="font-semibold text-[16px] md:text-[18px] text-gray-800">Interview Details</p>
                        </div>
                        {{-- applicant id --}}
                        <div class="pb-2 px-1 md:px-2">
                            <div
                                class="flex flex-row justify-start items-center p-3 md:p-4 gap-3 md:gap-4 bg-[#f8f8f8] rounded-xl border-2 border-[#1e1e1e]/10 hover:ring ring-[#4D8FF0]/20 hover:border-[#199BCF]/70 transition duration-150 hover:shadow-lg">

                                <div class="bg-[#199BCF] p-2 md:p-3 rounded-xl flex justify-center items-center">
                                    <i
                                        class="fi fi-rs-fingerprint flex justify-center items-center text-[16px] md:text-[20px] text-white opacity-90"></i>
                                </div>
                                <div>
                                    <p class="text-[11px] md:text-[12px] text-gray-500">Your Applicant ID</p>
                                    <span
                                        class="font-semibold text-[14px] md:text-[16px] text-gray-800">{{ $applicant->applicant_id ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                        {{-- date --}}

                        <div class="flex flex-wrap flex-row">

                            <div class="w-full md:w-1/2 pb-2 px-1 md:px-2">
                                <div
                                    class="flex flex-row justify-start items-center p-3 md:p-4 gap-3 md:gap-4 bg-[#f8f8f8] rounded-xl border-2 border-[#1e1e1e]/10 hover:ring ring-[#4D8FF0]/20 hover:border-[#199BCF]/70 transition duration-150 hover:shadow-lg">

                                    <div class="bg-[#199BCF] p-2 md:p-3 rounded-xl flex justify-center items-center">
                                        <i
                                            class="fi fi-rs-calendar-day flex justify-center items-center text-[16px] md:text-[20px] text-white opacity-90"></i>
                                    </div>
                                    <div>
                                        <p class="text-[11px] md:text-[12px] text-gray-500">Addmission Exam Date</p>
                                        <span
                                            class="font-semibold text-[14px] md:text-[16px] text-gray-800">{{ \Carbon\Carbon::parse($applicant->interview->date)->format('F j, Y') ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- time --}}
                            <div class="w-full md:w-1/2 pb-2 px-1 md:px-2">
                                <div
                                    class="flex flex-row justify-start items-center p-3 md:p-4 gap-3 md:gap-4 bg-[#f8f8f8] rounded-xl border-2 border-[#1e1e1e]/10 hover:ring ring-[#4D8FF0]/20 hover:border-[#199BCF]/70 transition duration-150 hover:shadow-lg">

                                    <div class="bg-[#199BCF] p-2 md:p-3 rounded-xl flex justify-center items-center">
                                        <i
                                            class="fi fi-rs-calendar-clock flex justify-center items-center text-[16px] md:text-[20px] text-white opacity-90"></i>
                                    </div>
                                    <div>
                                        <p class="text-[11px] md:text-[12px] text-gray-500">Admission Exam Time</p>
                                        <span
                                            class="font-semibold text-[14px] md:text-[16px] text-gray-800">{{ \Carbon\Carbon::parse($applicant->interview->time)->format('h:i A') ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                            {{-- location --}}
                            <div class="w-full md:w-1/2 pb-2 px-1 md:px-2">
                                <div
                                    class="flex flex-row justify-start items-center p-3 md:p-4 gap-3 md:gap-4 bg-[#f8f8f8] rounded-xl border-2 border-[#1e1e1e]/10 hover:ring ring-[#4D8FF0]/20 hover:border-[#199BCF]/70 transition duration-150 hover:shadow-lg">

                                    <div class="bg-[#199BCF] p-2 md:p-3 rounded-xl flex justify-center items-center">
                                        <i
                                            class="fi fi-rs-land-layer-location flex justify-center items-center text-[16px] md:text-[20px] text-white opacity-90"></i>
                                    </div>
                                    <div>
                                        <p class="text-[11px] md:text-[12px] text-gray-500">Location</p>
                                        <span
                                            class="font-semibold text-[14px] md:text-[16px] text-gray-800">{{ $applicant->interview->location ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- interviewer --}}
                            <div class="w-full md:w-1/2 pb-2 px-1 md:px-2">
                                <div
                                    class="flex flex-row justify-start items-center p-3 md:p-4 gap-3 md:gap-4 bg-[#f8f8f8] rounded-xl border-2 border-[#1e1e1e]/10 hover:ring ring-[#4D8FF0]/20 hover:border-[#199BCF]/70 transition duration-150 hover:shadow-lg">

                                    <div class="bg-[#199BCF] p-2 md:p-3 rounded-xl flex justify-center items-center">
                                        <i
                                            class="fi fi-rs-user flex justify-center items-center text-[16px] md:text-[20px] text-white opacity-90"></i>
                                    </div>
                                    <div>
                                        <p class="text-[11px] md:text-[12px] text-gray-500">Contact Person</p>
                                        <span
                                            class="font-semibold text-[14px] md:text-[16px] text-gray-800">{{ $teacherLastName }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- important --}}
                        <div class="px-1 md:px-2">
                            <div
                                class="flex flex-col justify-center items-start p-3 md:p-4 bg-[#E7F0FD] border border-[#1e1e1e]/10 rounded-xl hover:ring hover:ring-[#4D8FF0]/20 hover:border-[#199BCF]/70 transition duration-150 hover:shadow-lg">

                                <div class="flex flex-row justify-center items-center gap-3 md:gap-4">
                                    <div class="bg-[#199BCF] p-2 md:p-3 rounded-xl flex justify-center items-center">
                                        <i
                                            class="fi fi-rs-exclamation flex justify-center items-center text-[16px] md:text-[20px] text-white"></i>
                                    </div>
                                    <p class="text-[11px] md:text-[12px] text-gray-500">Additional Info</p>

                                </div>

                                <div class="flex justify-center items-center text-center px-3 md:px-5 pl-10 md:pl-14">
                                    <span
                                        class="text-[14px] md:text-[16px] text-start font-medium">{{ $applicant->interview->add_info ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif ($applicant->interview() && $applicant->interview->status === 'Taking-Exam')
                <div class="flex flex-col justify-center items-center space-y-4 py-4">
                    <div class="flex flex-col justify-center items-center gap-1">
                        <h2 class="font-semibold text-[18px] text-gray-800">Awaiting Your Admission Exam Result</h2>
                        <p class="self-center text-center text-[14px] text-gray-500 px-22 mb-8">Your exam result will be
                            available soon! Thank you for your patience. Please check back later, or feel free to contact
                            the <br> Admissions Office if you have any questions or need assistance.</p>
                    </div>

                    <img src="{{ asset('images/Waiting.svg') }}" alt=""
                        class="size-[200px] md:size-[250px] mx-auto mt-10 mb-6">

                </div>
            @endif

        </div>
    @endsection

@endif

@if ($applicant->application_status === 'Rejected')
    @section('Rejected')
        <div class="bg-[#f8f8f8] flex flex-col rounded-xl border border-[#1e1e1e]/20 md:w-full justify-center py-4 px-6">
            <div class="flex flex-row justify-start items-center gap-3">

                <div class="text-[20px] text-white bg-[#0f111c] size-[35px] rounded-full flex justify-center items-center">
                    1
                </div>
                <div class="flex flex-col justify-center items-start">
                    <p class="text-[16px] font-semibold text-gray-800">Fill out enrollment form</p>
                    <p class="text-[14px] font-medium text-gray-500">Last update:
                        {{ \Carbon\Carbon::parse($applicant->updated_at)->timezone('Asia/Manila')->format('M. d, Y — g:i A') }}
                    </p>
                </div>

            </div>
            <x-divider class="my-4 opacity-15"></x-divider>

            <div class="flex flex-col justify-center items-center space-y-6 py-4">
                <div class="flex flex-col justify-center items-center gap-1">
                    <h2 class="font-semibold text-[18px] text-gray-800">Application Result: Not Accepted</h2>
                    <p class="self-center text-center text-[14px] text-gray-500 px-22 mb-6">Thank you for your interest and
                        for submitting your application. After thorough evaluation, we’re <br> unable to proceed with your
                        application at this time. We truly appreciate your effort and<br> encourage you to apply again in
                        the future.</p>
                </div>

                <img src="{{ asset('images/sorry.png') }}" alt=""
                    class="size-[200px] md:size-[230px] mx-auto mt-4 mb-6">

            </div>


        </div>
    @endsection

@endif

@if ($applicant->application_status === 'Pending-Documents')
    @section('pending-documents')
        <div class="bg-[#f8f8f8] flex flex-col rounded-xl border border-[#1e1e1e]/20 md:w-full justify-center py-4 px-6">
            <div class="flex flex-row justify-start items-center gap-3">

                <div class="text-[20px] text-white bg-[#0f111c] size-[35px] rounded-full flex justify-center items-center">
                    3
                </div>
                <div class="flex flex-col justify-center items-start">
                    <p class="text-[16px] font-semibold text-gray-800">Get Result</p>
                    <p class="text-[14px] font-medium text-gray-500">Last update:
                        {{ \Carbon\Carbon::parse($applicant->interview->updated_at)->timezone('Asia/Manila')->format('M. d, Y — g:i A') }}
                    </p>
                </div>

            </div>
            <x-divider class="my-4 opacity-15"></x-divider>

            @if ($applicant->interview->status === 'Exam-Passed')
                <div class="flex flex-col justify-center items-center py-4 pb-8">

                    <div class="flex flex-col justify-center items-center gap-1">
                        <h2 class="font-semibold text-[18px] text-gray-800">Congratulations! You're In!</h2>
                        <p class="self-center text-center text-[14px] text-gray-500 px-22 mb-8">You’ve successfully
                            passed
                            your interview and are now conditionally enrolled. <br class="hidden md:block">Please submit
                            all required documents by clicking the button below. <br>
                        </p>
                    </div>

                    <img src="{{ asset('images/celebration.png') }}" alt=""
                        class="size-[200px] md:size-[230px] mx-auto mt-2">


                    <form action="/update-status/{{ $applicant->id }}" method="POST">
                        @csrf

                        <input type="hidden" name="status" value="Exam-Completed">
                        <button name="action" value="update-docs"
                            class="self-start hover:ring ring-[#C8A165]/30 bg-[#199BCF] text-[#f8f8f8] px-3 py-2 rounded-xl flex flex-row justify-center items-center gap-2 font-medium mb-4 hover:bg-[#C8A165]/90 hover:text-white hover:scale-95 shadow-[#199BCF]/20 shadow-xl hover:shadow-[#C8A165]/20 transition duration-200">
                            Submit Documents<i
                                class="fi fi-rs-arrow-small-right flex flex-row justify-center items-center text-[22px]"></i>
                        </button>
                    </form>

                    <span class="text-[12px] text-center text-gray-500 mt-4">
                        Note: After submitting your documents online, please provide the physical
                        copies as part of the verification process.
                    </span>

                </div>
            @endif

            @if ($applicant->interview->status === 'Exam-Completed')
                <div class="flex flex-col justify-center items-center py-4 pb-8 space-y-4">

                    @if ($verifiedCount < $totalAssignedDocuments)
                        <div class="flex flex-col justify-center items-center gap-1">
                            <h2 class="font-semibold text-[18px] text-center text-gray-800">Upload all required documents
                                for your
                                application. Make sure all files are clear and
                                readable.</h2>
                            <p class="self-center text-center text-[14px] text-gray-500 px-22 mb-4">
                                Please select a document type first before uploading any file.
                            </p>
                            <div class="bg-blue-100 border border-blue-300 rounded-lg p-3 mb-4">
                                <p class="text-sm text-blue-800">
                                    <strong>Progress:</strong> {{ $verifiedCount }} of {{ $totalAssignedDocuments }}
                                    documents verified
                                </p>
                            </div>
                        </div>
                    @else
                        <div class="flex flex-col justify-center items-center gap-1">
                            <h2 class="font-semibold text-[18px] text-gray-800">Great News! Your Documents Are Verified
                            </h2>
                            <p class="self-center text-center text-[14px] text-gray-500 px-22 mb-8">All your submitted
                                documents have been successfully verified. Your application is now awaiting promotion.</p>
                        </div>
                    @endif



                    <div
                        class="w-full md:w-[80%] flex flex-col justify-center items-center space-y-2 bg-[#E3ECFF]/20 p-6 border border-[#1e1e1e]/5 rounded-xl ">
                        <p class="self-start font-medium text-gray-600">Required Documents</p>
                        <div class="bg-[#f8f8f8] flex flex-col rounded-md border shadow-sm border-[#1e1e1e]/10 ">


                            <table id="docs-table" class="w-full table-fixed ">
                                <thead class="text-[14px]">
                                    <tr>
                                        <th
                                            class="w-1/7 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 rounded-tl-md px-4 py-2 cursor-pointer ">
                                            <span class="mr-2 text-gray-500 font-medium">Documents</span>
                                        </th>
                                        <th class="w-1/7 text-center bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                            <span class="mr-2 text-gray-500 font-medium">Submit Before</span>
                                        </th>
                                        <th class="w-1/7 text-center bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                            <span class="mr-2 text-gray-500 font-medium">Date Submitted</span>
                                        </th>
                                        <th
                                            class="w-1/7 text-center bg-[#E3ECFF] border-b border-[#1e1e1e]/15 rounded-tr-md px-4 py-2">
                                            <span class="mr-2 text-gray-500 font-medium">Status</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @if ($assignedDocuments)
                                        @foreach ($assignedDocuments as $doc)
                                            <tr class="border-t-[1px] border-[#1e1e1e]/15 w-full rounded-md">
                                                <td
                                                    class="w-1/8 text-start font-medium py-[8px] text-[14px] opacity-80 px-4 py-2 truncate">
                                                    {{ $doc->documents->type }}
                                                </td>

                                                <td
                                                    class="w-1/8 text-center font-medium py-[8px] text-[14px] opacity-80 px-4 py-2 truncate">
                                                    @if ($doc->submit_before)
                                                        {{ \Carbon\Carbon::parse($doc->submit_before)->format('M d, Y') }}
                                                    @else
                                                        <span class="text-gray-400 text-sm">-</span>
                                                    @endif
                                                </td>
                                                <td
                                                    class="w-1/8 text-center font-medium py-[8px] text-[14px] opacity-80 px-4 py-2 truncate">
                                                    @forelse ($doc->submissions as $submission)
                                                        {{ $submission->submitted_at->timezone('Asia/Manila')->format('M. d - g:i A') }}<br>
                                                    @empty
                                                        -
                                                    @endforelse
                                                </td>
                                                <td
                                                    class="w-1/8 text-center font-medium py-[8px] text-[14px] opacity-100 px-4 py-2 truncate">

                                                    @if ($doc->status == 'Pending')
                                                        <span
                                                            class="bg-yellow-50  text-yellow-500 px-2 py-1 rounded-full text-[14px] font-medium">
                                                            Pending
                                                        </span>
                                                    @elseif ($doc->status == 'Submitted')
                                                        <span
                                                            class="bg-blue-50 text-blue-500 px-2 py-1 rounded-full text-[14px] font-medium">
                                                            Submitted
                                                        </span>
                                                    @elseif ($doc->status == 'Verified')
                                                        <span
                                                            class="bg-green-50 text-green-500 px-2 py-1 rounded-full text-[14px] font-medium">
                                                            Verified
                                                        </span>
                                                    @elseif ($doc->status == 'Rejected')
                                                        <span
                                                            class="bg-red-50 text-red-500 px-2 py-1 rounded-full text-[14px] font-medium">
                                                            Rejected
                                                        </span>
                                                    @endif


                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>

                        </div>
                    </div>

                    @if ($verifiedCount < $totalAssignedDocuments)
                        <div
                            class="w-full md:w-[80%] flex flex-col justify-center items-center space-y-4 bg-[#E3ECFF]/20 p-6 border border-[#1e1e1e]/5 rounded-xl ">

                            <div class="flex flex-col w-full space-y-2">

                                <p class="font-medium text-gray-600">Document type</p>

                                <select name="document-option" id="document-option"
                                    class="w-full border-2 border-gray-300 bg-gray-100 rounded-lg px-3 py-2 outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 transition duration-200 placeholder:italic placeholder:text-[14px] text-[14px]">
                                    @if ($assignedDocuments)
                                        <option selected disabled>Select document type...</option>
                                        @foreach ($assignedDocuments as $doc)
                                            <option value="{{ $doc->documents->id }}">{{ $doc->documents->type }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <form id="uploadForm" class="flex flex-col items-center justify-center w-full space-y-2">
                                <p class="self-start font-medium text-gray-600">Upload File</p>
                                <label for="fileInput" id="fileInputLabel"
                                    class="flex flex-col items-center justify-center w-full border-2 border-[#199BCF]/60 border-dashed rounded-lg bg-blue-50 hover:bg-[#E7F0FD] opacity-40 cursor-pointer cursor-not-allowed select-none">

                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-8 h-8 mb-4 text-[#199BCF]" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                        </svg>
                                        <p class="mb-2 text-sm text-[#0f111c]/80"><span class="font-semibold">Click to
                                                upload</span> or drag and drop</p>
                                        <p id="fileRestrictions" class="text-xs text-gray-500 dark:text-gray-400">
                                            Loading file restrictions...
                                        </p>
                                    </div>
                                    <span
                                        class="flex flex-row justify-center items-center bg-[#199BCF] py-2 px-3 rounded-xl text-[14px] font-semibold gap-2 text-white hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 mb-6 shadow-lg truncate">Choose
                                        Files</span>

                                    <input id="fileInput" type="file" class="hidden" accept="" disabled />
                                </label>
                            </form>
                        </div>

                        <div class="uploaded-files w-full md:w-[80%] flex flex-col justify-center items-center space-y-4 bg-[#E3ECFF]/20 p-6 border border-[#1e1e1e]/5 rounded-xl "
                            id="uploadedFiles">
                            <h2 class="section-title self-start font-medium text-gray-600">Uploaded Documents</h2>
                            <div id="filesList" class="w-full space-y-2">

                            </div>
                        </div>

                        <label
                            class="w-full md:w-[80%] flex flex-row justify-center items-center bg-[#E3ECFF] p-6 border-2 border-[#1e1e1e]/5 rounded-xl text-[14px] gap-2 hover:ring ring-[#199BCF]/20 hover:border-[#199BCF] hover:shadow-md transition duration-200">
                            <input type="checkbox" name="consent" id="consent" class="size-[30px]" required>

                            <p>
                                I confirm that the documents I am uploading are accurate and belong to me. I understand that
                                these
                                may
                                contain personal or sensitive information, and I consent to the school securely reviewing
                                and
                                processing
                                them for my application, in accordance with the
                                <a href="/privacy-policy" target="_blank"
                                    class="underline text-blue-500 visited:text-purple-400">Privacy Policy</a>.
                            </p>



                        </label>

                        <div class="w-[75%] flex justify-center items-center">
                            <button form="uploadForm" id="submitBtn"
                                class="flex flex-row justify-center items-center bg-[#199BCF] py-2.5 px-4 rounded-xl text-[18px] md:text-[14px] font-semibold gap-2 text-white hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 mb-6 shadow-lg truncate opacity-50 cursor-not-allowed"
                                disabled>Submit
                                All Documents</button>
                        </div>
                    @endif



                </div>
            @endif

        </div>
    @endsection
@endif

@if ($applicant->application_status === 'Completed-Failed')
    @section('completed-failed')
        <div class="bg-[#f8f8f8] flex flex-col rounded-xl border border-[#1e1e1e]/20 md:w-full justify-center py-4 px-6">
            <div class="flex flex-row justify-start items-center gap-3">

                <div class="text-[20px] text-white bg-[#0f111c] size-[35px] rounded-full flex justify-center items-center">
                    2
                </div>
                <div class="flex flex-col justify-center items-start">
                    <p class="text-[16px] font-semibold text-gray-800">Take admission exam at school</p>
                    <p class="text-[14px] font-medium text-gray-500">Last update:
                        {{ \Carbon\Carbon::parse($applicant->applicationForm->updated_at)->timezone('Asia/Manila')->format('M. d, Y — g:i A') }}
                    </p>
                </div>

            </div>
            <x-divider class="my-4 opacity-15"></x-divider>

            @if ($applicant->interview->status === 'Exam-Failed')
                <div class="flex flex-col justify-center items-center py-4 pb-8">

                    <div class="flex flex-col justify-center items-center gap-1">
                        <h2 class="font-semibold text-[18px] text-gray-800">Admission Exam Result: Unsuccessful</h2>
                        <p class="self-center text-center text-[14px] text-gray-500 px-22 mb-8">Thank you for taking part
                            in the admission process. We understand the effort you put into your application. <br>
                            Unfortunately, your exam result did not meet the required criteria for this term.</p>
                    </div>

                    <img src="{{ asset('images/sorry.png') }}" alt=""
                        class="size-[200px] md:size-[230px] mx-auto mt-4">

                </div>
            @endif

        </div>
    @endsection

@endif

@if ($applicant->application_status === 'Officially Enrolled')
    @section('officially-enrolled')
        <div class="bg-[#f8f8f8] flex flex-col rounded-xl border border-[#1e1e1e]/20 md:w-full justify-center p-4">

            <div class="flex flex-col w-full justify-center items-center px-4 pb-8 gap-8">


                <div class="flex flex-col justify-center items-center w-[80%] gap-4">

                    <img src="{{ asset('images/Welcome.gif') }}" alt=""
                        class="size-[200px] md:size-[230px] mx-auto mt-4">
                    <p class="font-semibold text-[18px] text-gray-800">Welcome to Dreamy School!</p>

                    <p class="self-center text-center text-[14px] text-gray-500 px-22 mb-8">We’re thrilled to welcome you
                        to our academic community! You've
                        completed
                        the enrollment process, and your hard work has paid off. We can’t wait to see what you’ll
                        achieve
                        with
                        us.</p>

                </div>

                <div
                    class="bg-[#E3ECFF]/60 w-full md:w-[80%] flex flex-col justify-center items-center text-center gap-4 p-4 rounded-lg">
                    <div>
                        <p class="font-semibold text-[18px] text-gray-800">What's Next?</p>
                    </div>
                    <div class="space-y-2 w-full ">
                        <div class="flex flex-col justify-center items-center">
                            <p class="font-semibold text-[14px] text-gray-700 mb-1">Your school fee details have been sent
                                to the mobile app</p>
                            <p class="self-center text-center text-[14px] text-gray-500 px-22 mb-6">Download our official
                                mobile app using the link below to choose your preferred payment plan <br> and settle your
                                fees at school.</p>

                            <button
                                class="w-auto flex flex-row justify-start items-center rounded-full px-4 py-2.5 text-start bg-[#199BCF] text-white shadow-[#199BCF]/40 gap-1 shadow-xl transition hover:translate-x-2 hover:bg-[#C8A165] hover:shadow-[#C8A165]/40 duration-200 mb-6">
                                <p class="font-medium">Download App</p><i
                                    class="fi fi-rr-arrow-small-right flex justify-center items-center text-[24px] pt-0.5"></i>
                            </button>

                            <p class="self-center text-center text-[14px] font-medium text-gray-600 px-22 mb-2">Inside the
                                app, you’ll also find exclusive student content such as:
                            </p>

                            <div class="flex flex-col justify-center items-start w-auto mb-4">
                                <p class="text-[14px] text-gray-500 px-22">• News & announcements</p>
                                <p class="text-[14px] text-gray-500 px-22">• School fees and payment status</p>
                                <p class="text-[14px] text-gray-500 px-22">• Class sections</p>
                                <p class="text-[14px] text-gray-500 px-22">• Subjects and schedules</p>
                            </div>
                            <p class="text-[14px] text-gray-500">Stay informed and manage everything in one place!</p>
                        </div>
                    </div>

                </div>


            </div>

        </div>
    @endsection

@endif

@push('scripts')
    <script type="module">
        import {
            initModal
        } from '/js/modal.js';
        import {
            showAlert
        } from "/js/alert.js";

        document.addEventListener('DOMContentLoaded', function() {

            // Load application summary data
            loadApplicationSummary();

            let applicant = @json($applicant ?? null);

            console.log(applicant.application_status)

            const btn = document.getElementById('upload-btn');
            const input = document.getElementById('fileInput');
            const doc_option = document.getElementById('document-option');
            const fileInputLabel = document.getElementById('fileInputLabel');
            const container = document.getElementById('filesList');


            if (applicant.application_status === 'Pending-Documents' && applicant.interview.status ===
                'Exam-Completed') {

                let submittedDocs = @json($applicant->submissions ?? null);

                // console.log(submittedDocs);



                let requiredDocs = @json($assignedDocuments ?? null);

                // convert objects into array
                let submittedDocsArr = Object.values(submittedDocs);

                // find documents with only pending or verified status
                const submittedDocsWithStatus = requiredDocs.filter(item => ['Submitted', 'Verified']
                    .includes(
                        item
                        .status));

                console.log(submittedDocsWithStatus);

                let submittedDocsId = new Set(submittedDocs.map(item => item.documents_id));



                const matchedItems = submittedDocsWithStatus.filter(item => submittedDocsId.has(item.documents_id));

                console.log(matchedItems);

                // Only process if doc_option exists (upload section is visible)
                if (doc_option) {
                    matchedItems.forEach(docs => {
                        //find options with a value matched with the documents_id of the docs
                        const foundOption = Array.from(doc_option.options).find(
                            option => option.value === String(docs.documents_id)
                        )

                        if (foundOption) {
                            foundOption.disabled = true;
                        }
                    })
                }

                let uploadedFiles = [];
                let attachedFiles = [];

                updateFileUploadInput()
                updateUploadedFilesList()

                if (doc_option) {
                    doc_option.addEventListener('change', (event) => {
                        updateFileUploadInput()
                    })
                }

                function updateFileUploadInput() {
                    if (doc_option && doc_option.options[doc_option.selectedIndex].text !==
                        'Select document type...') {
                        if (fileInputLabel) {
                            fileInputLabel.classList.remove('opacity-40')
                            fileInputLabel.classList.remove('cursor-not-allowed')
                        }
                        if (input) {
                            input.disabled = false;
                        }
                    } else {
                        if (fileInputLabel) {
                            fileInputLabel.classList.add('opacity-40')
                            fileInputLabel.classList.add('cursor-not-allowed')
                        }
                        if (input) {
                            input.disabled = true;
                        }
                    }
                }

                if (input) {
                    input.addEventListener('change', (event) => {

                        const files = Array.from(event.target.files);

                        files.forEach(file => {
                            uploadedFiles.push({
                                file: file, // actual File object
                                assignedTo: doc_option ? doc_option.value : ''
                            });
                        });

                        files.forEach(file => {
                            attachedFiles.push({
                                name: file.name,
                                assignedTo: doc_option ? doc_option.options[doc_option
                                    .selectedIndex].text : '',
                                docId: doc_option ? doc_option.value : ''
                            });
                        });

                        console.log(uploadedFiles)

                        updateUploadedFilesList()
                        updateButton()

                        if (doc_option) {
                            let optionId = doc_option.options[doc_option.selectedIndex].value;
                            disableSelection(optionId)
                        }
                        updateFileUploadInput()
                        updateSubmitButton()
                        //checkboxState()
                        //setupSubmitButtonWatcher()
                    })
                }

                function updateUploadedFilesList() {
                    if (container) {
                        container.innerHTML = '';
                    }

                    if (container) {
                        attachedFiles.forEach((file, index) => {
                            const docTypeInfo = attachedFiles.find(req => req.docId === file.docId);
                            const item = document.createElement('div');
                            item.className = 'uploaded-file';

                            item.innerHTML = `
                                <div
                                    class="flex flex-row justify-between items-center gap-2 bg-[#E7F0FD]/60 border-2 border-[#1e1e1e]/10 px-4 py-4 rounded-xl hover:shadow-xl hover:ring hover:ring-[#199BCF]/20 hover:border-[#199BCF]/60 transition duration-200">
                                    <div class="flex flex-row items-center gap-2 flex-1">
                                        <!-- Icon -->
                                        <div class="size-10 bg-[#199BCF] rounded-lg flex justify-center items-center text-white">
                                            <i class="fi fi-rr-document flex justify-center items-center text-[24px]"></i>
                                        </div>

                                        <!-- Text container -->
                                        <div class="flex flex-col justify-center items-start overflow-hidden">
                                            <p class="font-semibold text-gray-700 leading-tight truncate max-w-[300px]">
                                                ${file.name}
                                            </p>
                                            <p class="text-[14px] text-gray-500">${file.assignedTo}</p>
                                        </div>
                                    </div>

                                    <!-- Remove Button -->
                                    <div class="shrink-0">
                                        <button
                                            id="${file.docId}"
                                            class="remove-btn border border-red-300 bg-red-50 hover:bg-red-500 rounded-xl flex justify-center items-center text-red-500 px-3 py-2 gap-1 hover:text-white hover:ring ring-red-200 transition duration-200 text-[14px]">
                                            <i class="fi fi-ss-trash flex justify-center items-center text-[14px]"></i>
                                            Remove
                                        </button>
                                    </div>
                                </div>
                        `;
                            container.appendChild(item);
                            console.log(uploadedFiles)

                        });
                    }

                    if (uploadedFiles.length === 0 && container) {
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
                    if (doc_option) {
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
                }

                function disableSelection(optionId) {
                    if (doc_option) {
                        const foundItem = uploadedFiles.find(item => item.assignedTo === optionId);
                        const foundOption = Array.from(doc_option.options).find(
                            option => option.value === foundItem.assignedTo
                        );
                        if (foundOption) {
                            foundOption.disabled = true
                        }
                        doc_option.selectedIndex = 0;
                    }
                }

                function enableSelection(optionId) {
                    if (doc_option) {
                        const foundOption = Array.from(doc_option.options).find(
                            option => option.value === optionId
                        );
                        if (foundOption) {
                            foundOption.disabled = false
                        }
                        doc_option.selectedIndex = 0;
                    }
                }

                function updateSubmitButton() {
                    const submitBtn = document.getElementById('submitBtn');
                    const checkbox = document.getElementById('consent');

                    if (submitBtn) {
                        if (uploadedFiles.length <= 0) {
                            disableSubmitButton()
                        } else if (uploadedFiles.length > 0 && checkbox && checkbox.checked === true) {
                            enableSubmitButton()
                        } else {
                            disableSubmitButton()
                        }
                    }
                }

                function checkboxState() {
                    const checkbox = document.getElementById('consent');

                    if (checkbox) {
                        checkbox.addEventListener('change', () => {
                            if (uploadedFiles.length <= 0 && checkbox.checked === true) {
                                disableSubmitButton()
                            } else if (uploadedFiles.length > 0 && checkbox.checked === true) {
                                enableSubmitButton()
                            } else {
                                disableSubmitButton()
                            }
                        })
                    }
                }

                // Initialize checkbox state
                checkboxState();

                function enableSubmitButton() {
                    const submitBtn = document.getElementById('submitBtn');
                    if (submitBtn) {
                        submitBtn.classList.remove('opacity-50');
                        submitBtn.classList.remove('cursor-not-allowed');
                        submitBtn.disabled = false
                    }
                }

                function disableSubmitButton() {
                    const submitBtn = document.getElementById('submitBtn');
                    if (submitBtn) {
                        submitBtn.classList.add('opacity-50');
                        submitBtn.classList.add('cursor-not-allowed');
                        submitBtn.disabled = true
                    }
                }


                const form = document.getElementById('uploadForm')

                if (form) {
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
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        .getAttribute('content'),
                                    "Accept": "application/json"
                                }
                            })
                            .then(response => {
                                const data = response.data;

                                if (data.success) {
                                    showAlert('success', data.message ||
                                        'Document submitted successfully!');
                                    setTimeout(() => {
                                        window.location.reload();
                                    }, 1500);
                                } else {
                                    showAlert('error', data.message || 'Failed to submit document');
                                    setTimeout(() => {
                                        window.location.reload();
                                    }, 1500);
                                }
                            })
                            .catch(error => {
                                console.error('Upload failed:', error.response?.data || error.message);
                                const errorMessage = error.response?.data?.message ||
                                    'An error occurred while submitting the document';
                                showAlert('error', errorMessage);
                                setTimeout(() => {
                                    window.location.reload();
                                }, 2000);
                            });

                    });
                }

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
                        if (dbText) {
                            dbText.textContent =
                                'Enrollment period is temporarily closed. At this time, we are not accepting any new applications.';
                            dbText.classList.remove('md:text-[22px]');
                            dbText.classList.add('md:text-[20px]');
                            dbText.classList.remove('p-2');
                            dbText.classList.add('p-10');
                        }
                        if (zspan) {
                            zspan.textContent = '';
                            zspan.style.display = 'none'; // Force hide
                        }
                        if (btnContainer) {
                            btnContainer.classList.add('hidden');
                            btnContainer.style.display = 'none'; // Force hide
                        }
                        if (epstatus) {
                            epstatus.textContent = status;
                            epstatus.style.color = '#FF9800'; // Orange color for paused status
                        }

                        // Force hide with timeout to ensure it's hidden
                        setTimeout(() => {
                            if (btnContainer) {
                                btnContainer.classList.add('hidden');
                                btnContainer.style.display = 'none';
                            }
                            if (zspan) {
                                zspan.style.display = 'none';
                            }
                        }, 100);
                    }

                    if (status === 'Ongoing') {
                        // Get the current academic term name from the page
                        const currentTermElement = document.querySelector('[data-current-term]');
                        const termName = currentTermElement ? currentTermElement.getAttribute(
                                'data-current-term') :
                            '{{ $currentAcadTerm?->getFullNameAttribute() ?? 'Academic Year' }}';

                        // Update the dashboard text to match the PHP template
                        if (dbText) {
                            dbText.textContent =
                                `Welcome to Dreamy School' Online Registration for ${termName}`;
                            dbText.classList.remove('md:text-[20px]');
                            dbText.classList.add('md:text-[22px]');
                            dbText.classList.remove('p-10');
                            dbText.classList.add('p-2');
                        }
                        if (zspan) {
                            zspan.textContent = "Please click the button below to fill out the form.";
                            zspan.style.display = 'block'; // Ensure it's visible
                        }
                        if (btnContainer) {
                            btnContainer.classList.remove('hidden');
                            btnContainer.style.display = 'block'; // Force display
                        }
                        if (epstatus) {
                            epstatus.textContent = status;
                            epstatus.style.color = '#34A853'; // Green color for ongoing status
                        }

                        // Force a re-render by triggering a small delay
                        setTimeout(() => {
                            if (btnContainer) {
                                btnContainer.style.display = 'block';
                            }
                            if (zspan) {
                                zspan.style.display = 'block';
                            }
                        }, 100);
                    }

                    if (status === 'Closed') {
                        // Get the current academic term name from the page
                        const currentTermElement = document.querySelector('[data-current-term]');
                        const termName = currentTermElement ? currentTermElement.getAttribute(
                                'data-current-term') :
                            '{{ $currentAcadTerm?->getFullNameAttribute() ?? 'Academic Year' }}';

                        // Update the dashboard text to match the PHP template
                        if (dbText) {
                            dbText.innerHTML =
                                `Enrollment for the academic year <i class="font-bold">${termName}</i> has ended. We are no longer accepting new applications.`;
                            dbText.classList.remove('md:text-[22px]');
                            dbText.classList.add('md:text-[20px]');
                            dbText.classList.remove('p-2');
                            dbText.classList.add('p-10');
                        }
                        if (zspan) {
                            zspan.textContent = '';
                        }
                        if (btnContainer) {
                            btnContainer.classList.add('hidden');
                        }
                        if (epstatus) {
                            epstatus.textContent = status;
                            epstatus.style.color = '#F44336'; // Red color for closed status
                        }
                    }
                });
            }





            // Load Application Summary Data
            function loadApplicationSummary() {
                fetch('/api/application-summary')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update total registrations
                            const totalRegistrationsElement = document.getElementById('total-registrations');
                            if (totalRegistrationsElement) {
                                totalRegistrationsElement.textContent = data.summary.total_registrations || 0;
                            }

                            // Update successful applicants
                            const successfulApplicantsElement = document.getElementById(
                                'successful-applicants');
                            if (successfulApplicantsElement) {
                                successfulApplicantsElement.textContent = data.summary.successful_applicants ||
                                    0;
                            }

                            // Update acceptance rate
                            const acceptanceRateElement = document.getElementById('acceptance-rate');
                            if (acceptanceRateElement) {
                                acceptanceRateElement.textContent =
                                    `${data.summary.acceptance_rate || 0}% Acceptance rate`;
                            }
                        } else {
                            console.error('Failed to load application summary:', data.message);
                            // Set default values on error
                            setDefaultSummaryValues();
                        }
                    })
                    .catch(error => {
                        console.error('Error loading application summary:', error);
                        // Set default values on error
                        setDefaultSummaryValues();
                    });
            }

            // Set default values when API fails
            function setDefaultSummaryValues() {
                const totalRegistrationsElement = document.getElementById('total-registrations');
                const successfulApplicantsElement = document.getElementById('successful-applicants');
                const acceptanceRateElement = document.getElementById('acceptance-rate');

                if (totalRegistrationsElement) totalRegistrationsElement.textContent = '0';
                if (successfulApplicantsElement) successfulApplicantsElement.textContent = '0';
                if (acceptanceRateElement) acceptanceRateElement.textContent = '0% Acceptance rate';
            }

            // Load dynamic file restrictions
            function loadFileRestrictions() {
                fetch('/document-restrictions')
                    .then(response => response.json())
                    .then(restrictions => {
                        if (Object.keys(restrictions).length > 0) {
                            // Get the first document's restrictions (or you can modify this logic)
                            const firstDocId = Object.keys(restrictions)[0];
                            const restriction = restrictions[firstDocId];

                            // Update file input accept attribute
                            const fileInput = document.getElementById('fileInput');
                            if (fileInput) {
                                fileInput.accept = restriction.accept_string;
                            }

                            // Update restrictions text
                            const restrictionsText = document.getElementById('fileRestrictions');
                            if (restrictionsText) {
                                const typesText = restriction.allowed_types.join(', ').toUpperCase();
                                restrictionsText.textContent =
                                    `${typesText} (MAX. ${restriction.max_size_mb}MB)`;
                            }
                        } else {
                            // Fallback to default restrictions
                            const fileInput = document.getElementById('fileInput');
                            const restrictionsText = document.getElementById('fileRestrictions');

                            if (fileInput) fileInput.accept = '.pdf,.png,.jpeg';
                            if (restrictionsText) restrictionsText.textContent = 'PNG, JPG or PDF (MAX. 10MB)';
                        }
                    })
                    .catch(error => {
                        console.error('Error loading file restrictions:', error);
                        // Fallback to default restrictions
                        const fileInput = document.getElementById('fileInput');
                        const restrictionsText = document.getElementById('fileRestrictions');

                        if (fileInput) fileInput.accept = '.pdf,.png,.jpeg';
                        if (restrictionsText) restrictionsText.textContent = 'PNG, JPG or PDF (MAX. 10MB)';
                    });
            }

            // Load file restrictions on page load
            loadFileRestrictions();

        });
    </script>
@endpush
