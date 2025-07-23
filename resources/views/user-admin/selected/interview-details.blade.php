@extends('layouts.admin')

@section('breadcrumbs')
    <nav aria-label="Breadcrumb" class="mb-4 mt-2">
        <ol class="flex items-center gap-1 text-sm text-gray-700">
            <li>
                <a href="#" class="block transition-colors hover:text-gray-900"> Applications </a>
            </li>

            <li class="rtl:rotate-180">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
            </li>

            <li>
                <a href="/selected-applications" class="block transition-colors hover:text-gray-900"> Selected Applications
                </a>
            </li>

            <li class="rtl:rotate-180">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
            </li>

            <li>
                <a href="#" class="block transition-colors hover:text-gray-900"> Interview Details </a>
            </li>
        </ol>
    </nav>
@endsection

@section('modal')
    @if ($interview_details->status === 'Pending')
        {{-- Schedule Interview Modal --}}
        <x-modal modal_id="sched-interview-modal" modal_name="Schedule Interview" close_btn_id="sched-interview-close-btn">

            <form action="/set-interview/{{ $interview_details->id }}" method="post" id="interview-form"
                class="flex flex-col space-y-2 px-4 py-2">
                @csrf
                @method('PATCH')
                <input type="hidden" name="applicant_id" value="{{ $applicant->id }}">
                <div class="flex flex-row space-x-2">
                    <div class="flex-1 space-y-1">

                        <label for="date" class="text-[14px] font-bold">Date</label>
                        <div
                            class="flex items-center px-2 py-2 rounded-md bg-[#E3ECFF] focus-within:ring-2 focus-within:ring-[#199BCF]/40 space-x-2">
                            <i class="fi fi-rs-calendar-day flex items-center opacity-60"></i>
                            <input type="date" name="date" id="date"
                                class="bg-transparent outline-none font-medium text-[14px] w-full">
                        </div>

                    </div>
                    <div class="flex-1 space-y-1">

                        <label for="time" class="text-[14px] font-bold">Time</label>
                        <div
                            class="flex items-center px-2 py-2 rounded-md bg-[#E3ECFF] focus-within:ring-2 focus-within:ring-[#199BCF]/40 space-x-2">
                            <i class="fi fi-rs-clock-five flex items-center opacity-60"></i>
                            <input type="time" name="time" id="time"
                                class="bg-transparent outline-none font-medium text-[14px] w-full">
                        </div>

                    </div>
                    <div class="flex-1 space-y-1">

                        <label for="location" class="text-[14px] font-bold">Location</label>
                        <div
                            class="flex items-center px-2 py-2 rounded-md bg-[#E3ECFF] focus-within:ring-2 focus-within:ring-[#199BCF]/40 space-x-2">
                            <i class="fi fi-rs-marker flex items-center opacity-60"></i>
                            <input type="text" name="location" id="location"
                                class="bg-transparent outline-none font-medium text-[14px] w-full">
                        </div>

                    </div>
                </div>

                <div class="flex-1 space-y-1">

                    <label for="" class="text-[14px] font-bold">Assign to</label>
                    <div
                        class="flex items-center px-2 py-2 rounded-md bg-[#E3ECFF] w-2/3 focus-within:ring-2 focus-within:ring-[#199BCF]/40 space-x-2">
                        <i class="fi fi-rs-user flex items-center opacity-60"></i>
                        <select name="" id=""
                            class="bg-transparent outline-none font-medium text-[14px] w-full">
                            <option value="" class="font-Manrope">Juan Dela Cruz</option>
                            <option value="">Peter Dela Cruz</option>
                        </select>
                    </div>

                </div>

                <div class="flex-1 space-y-1">

                    <label for="add_info" class="text-[14px] font-bold">Additional Information</label>
                    <div
                        class="flex items-center px-2 py-2 rounded-md bg-[#E3ECFF] focus-within:ring-2 focus-within:ring-[#199BCF]/40 space-x-2">
                        <i class="fi fi-rs-info flex items-center opacity-60"></i>
                        <textarea name="add_info" id="add_info" cols="10" rows="10"
                            class="bg-transparent outline-none font-medium text-[14px] w-full resize-none h-[100px]"></textarea>
                    </div>

                </div>

            </form>

            <x-slot name="modal_buttons">

                <button id="cancel-btn"
                    class="border border-[#1e1e1e]/15 text-[14px] px-2 py-1 rounded-md text-[#0f111c]/80 font-bold">
                    Cancel
                </button>
                <button form="interview-form" name="action" value="schedule-interview"
                    class="bg-[#199BCF] text-[14px] px-2 py-1 rounded-md text-[#f8f8f8] font-bold">
                    Confirm
                </button>

            </x-slot>
        </x-modal>
    @elseif ($interview_details->status === 'Scheduled')
        {{-- Record Interview Result Modal --}}
        <x-modal modal_id="record-interview-modal" modal_name="Record Interview Result"
            close_btn_id="record-interview-close-btn">

            <form action="/set-interview/{{ $interview_details->id }}" method="post" class="py-2 px-4 space-y-2"
                form="interview-form" id="interview-form">

                @csrf
                @method('PATCH')
                <input type="hidden" name="applicant_id" value="{{ $applicant->id }}">
                <label for="passed"
                    class="flex items-center justify-between has-checked:bg-red-500 has-checked:ring-red-500">
                    <p>Passed</p>
                    <input type="radio" name="result" id="passed" value="Interview-Passed" checked>
                </label>

                <label for="failed"
                    class="flex items-center justify-between has-checked:ring-2 has-checked:ring-red-500">
                    <p>Failed</p>
                    <input type="radio" name="result" id="failed" value="Interview-Failed">
                </label>

                <p>By selecting 'Passed', standard document requirements will be automatically assigned to this applicant
                </p>



            </form>

            <x-slot name="modal_buttons">
                <button id="cancel-btn"
                    class="border border-[#1e1e1e]/15 text-[14px] px-2 py-1 rounded-md text-[#0f111c]/80 font-bold">
                    Cancel
                </button>
                <button form="interview-form" name="action" value="record-result"
                    class="bg-[#199BCF] text-[14px] px-2 py-1 rounded-md text-[#f8f8f8] font-bold">
                    Confirm
                </button>
            </x-slot>

        </x-modal>

        {{-- Edit Interview Schedule Modal --}}
        <x-modal modal_id="edit-sched-modal" modal_name="Edit Interview Schedule" close_btn_id="edit-sched-close-btn">

            <form action="/set-interview/{{ $interview_details->id }}" method="post" id="interview-form"
                class="flex flex-col space-y-2 px-4 py-2">
                @csrf
                @method('PATCH')

                <div class="flex flex-row space-x-2">
                    <div class="flex-1 space-y-1">
                        <label for="date" class="text-[14px] font-bold">Date</label>
                        <div
                            class="flex items-center px-2 py-2 rounded-md bg-[#E3ECFF] focus-within:ring-2 focus-within:ring-[#199BCF]/40 space-x-2">
                            <i class="fi fi-rs-calendar-day flex items-center opacity-60"></i>
                            <input type="date" name="date" id="date"
                                class="bg-transparent outline-none font-medium text-[14px] w-full">
                        </div>
                    </div>
                    <div class="flex-1 space-y-1">
                        <label for="time" class="text-[14px] font-bold">Time</label>
                        <div
                            class="flex items-center px-2 py-2 rounded-md bg-[#E3ECFF] focus-within:ring-2 focus-within:ring-[#199BCF]/40 space-x-2">
                            <i class="fi fi-rs-clock-five flex items-center opacity-60"></i>
                            <input type="time" name="time" id="time"
                                class="bg-transparent outline-none font-medium text-[14px] w-full">
                        </div>
                    </div>
                    <div class="flex-1 space-y-1">
                        <label for="location" class="text-[14px] font-bold">Location</label>
                        <div
                            class="flex items-center px-2 py-2 rounded-md bg-[#E3ECFF] focus-within:ring-2 focus-within:ring-[#199BCF]/40 space-x-2">
                            <i class="fi fi-rs-marker flex items-center opacity-60"></i>
                            <input type="text" name="location" id="location"
                                class="bg-transparent outline-none font-medium text-[14px] w-full">
                        </div>
                    </div>
                </div>

                <div class="flex-1 space-y-1">
                    <label for="" class="text-[14px] font-bold">
                        Assign to
                    </label>
                    <div
                        class="flex items-center px-2 py-2 rounded-md bg-[#E3ECFF] w-2/3 focus-within:ring-2 focus-within:ring-[#199BCF]/40 space-x-2">
                        <i class="fi fi-rs-user flex items-center opacity-60"></i>
                        <select name="" id=""
                            class="bg-transparent outline-none font-medium text-[14px] w-full">
                            <option value="" class="font-Manrope">Juan Dela Cruz</option>
                            <option value="">Peter Dela Cruz</option>
                        </select>
                    </div>
                </div>

                <div class="flex-1 space-y-1">
                    <label for="add_info" class="text-[14px] font-bold">
                        Additional Information
                    </label>
                    <div
                        class="flex items-center px-2 py-2 rounded-md bg-[#E3ECFF] focus-within:ring-2 focus-within:ring-[#199BCF]/40 space-x-2">
                        <i class="fi fi-rs-info flex items-center opacity-60"></i>
                        <textarea name="add_info" id="add_info" cols="10" rows="10"
                            class="bg-transparent outline-none font-medium text-[14px] w-full resize-none h-[100px]"></textarea>
                    </div>
                </div>

            </form>

            <x-slot name="modal_buttons">
                <button id="cancel-btn"
                    class="border border-[#1e1e1e]/15 text-[14px] px-2 py-1 rounded-md text-[#0f111c]/80 font-bold">
                    Cancel
                </button>
                <button form="interview-form" name="action" value="edit-interview"
                    class="bg-[#199BCF] text-[14px] px-2 py-1 rounded-md text-[#f8f8f8] font-bold">
                    Confirm
                </button>
            </x-slot>

        </x-modal>
    @endif
@endsection

@section('header')
    <div class="flex flex-col justify-center items-start text-start px-[14px] py-2">
        <h1 class="text-[20px] font-black">Interview Details</h1>
        <p class="text-[14px]  text-gray-900/60">Manage approved applicants, set interview schedules, and record interview
            results.</p>
    </div>
@endsection

@section('content')
    <div class="flex flex-col p-6 text-[14px] gap-4">
        <div class="flex flex-row justify-between items-center">
            <div class="flex flex-row gap-2 justify-center items-center">
                <div class="rounded-full overflow-hidden bg-gray-200 ">
                    <img src="{{ asset('images/business-man.png') }}" alt="user-icon" class="size-16 user-select-none">
                </div>
                <div>
                    <p class="font-bold text-[18px]">{{ $applicant->getFullNameAttribute() }}</p>
                    <div class="flex flex-row items-center justify-start gap-1">
                        <p class="text-[16px] opacity-70 font-medium">Applicant ID: </p>
                        <span class="text-[16px] font-black">{{ $applicant->applicant_id }}</span>
                    </div>

                </div>
            </div>
            <div>

                {{-- @if ($applicant->application_status === 'Officially Enrolled')
                    <button type="button" id="open-enroll-student-modal-btn" disabled
                        class="py-2 px-4 bg-gray-300 text-gray-400 rounded-xl font-bold transition duration-200 cursor-not-allowed">
                        Enroll applicant
                    </button>
                @else
                    <button type="button" id="open-enroll-student-modal-btn"
                        class="py-2 px-4 bg-blue-500 text-white rounded-xl font-bold hover:ring hover:ring-blue-200 transition duration-200">
                        Enroll applicant
                    </button>
                @endif --}}
                @if ($interview_details->status === 'Pending')
                    <button id="record-btn"
                        class="py-2 px-4 bg-blue-500 text-white rounded-xl font-bold hover:ring hover:ring-blue-200 transition duration-200">Schedule
                        Interview</button>
                @elseif ($interview_details->status === 'Scheduled' || $interview_details->status === 'Ongoing-Interview')
                    <div class="flex flex-row justify-center items-center gap-2">
                        <button id="edit-sched-btn"
                            class="py-2 px-4 bg-[#f8f8f8] text-[#0f111c] border border-[#1e1e1e]/10 rounded-xl font-bold hover:ring hover:ring-blue-200 transition duration-200">Edit</button>
                        <button id="record-interview-btn"
                            class="py-2 px-4 bg-blue-500 text-white rounded-xl font-bold hover:bg-blue-400 hover:ring hover:ring-blue-200 transition duration-200">Record
                            Interview Result
                        </button>
                    </div>
                @else
                    <button id="interview-btn" disabled
                        class="py-2 px-4 bg-gray-200 text-gray-300 rounded-xl font-bold cursor-not-allowed">Start Interview
                    </button>
                @endif

            </div>

        </div>
        <x-divider color="#1e1e1e" opacity="0.10"></x-divider>
        <div class="flex flex-row">
            <div class="flex flex-col flex-1 space-y-4">
                <span>
                    <p class="opacity-80">Grade</p>
                    <p class="font-bold">Grade 11</p>
                </span>
                <span>
                    <p class="opacity-80">Track</p>
                    <p class="font-bold">HUMSS</p>
                </span>

            </div>
            <div class="flex flex-col flex-1 space-y-4">
                <span>
                    <p class="opacity-80">Contact</p>
                    <p class="font-bold">091234789</p>
                </span>
                <span>
                    <p class="opacity-80">Interview Date</p>
                    <p class="font-bold">June 21, 2025</p>
                </span>

                </span>
            </div>
            <div class="flex flex-col flex-1 space-y-4">
                <span>
                    <p class="opacity-80">Interview Time</p>
                    <p class="font-bold">10:30 AM</p>
                </span>
                <span>
                    <p class="opacity-80">Location</p>
                    <p class="font-bold">First floor, Room 301</p>
            </div>
            <div class="flex flex-col flex-1 space-y-4">
                <span>
                    <p class="opacity-80">Interviewer</p>
                    <p class="font-bold">Peter Dela Cruz</p>
                </span>
                <span>
                    <p class="opacity-80">Status</p>
                    <p class="font-bold">{{ $applicant->application_status }}</p>
                </span>
            </div>
        </div>

    </div>
    <div
        class="flex flex-row items-center justify-between px-[14px] py-[10px] text-[14px] font-medium transition duration-150">
        <button id="show-details-btn"
            class="flex flex-row gap-2 border border-[#1e1e1e]/15 rounded-md px-2 py-1 text-[#0f111c]/80 ">View
            Applicant's Full Details <i
                class="fi fi-rs-angle-small-down flex flex-row items-center text-[18px] text-[#0f111c]/80"></i></button>


    </div>
    <div id="details-container" class="hidden flex-col px-[14px] py-[14px] space-y-3 ">
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
                        <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2 text-bold">With
                            LRN:<span class="font-bold"> Yes</span></td>
                        <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2 text-bold">LRN: <span
                                class="font-bold"></span></td>
                    </tr>
                    <tr class="opacity-[0.87]">
                        <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Grade Level to
                            Enroll:<span class="font-bold"></span></td>
                        <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Semester:<span
                                class="font-bold"></span></td>
                    </tr>
                    <tr class="opacity-[0.87]">
                        <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Primary Track:<span
                                class="font-bold"> </span></td>
                        <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Secondary Track:<span
                                class="font-bold"></span></td>
                    </tr>
                    <tr class="opacity-[0.87]">
                        <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Last Name:<span
                                class="font-bold"></span></td>
                        <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">First Name:<span
                                class="font-bold"></span></td>
                    </tr>
                    <tr class="opacity-[0.87]">
                        <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Middle Name:<span
                                class="font-bold"></span></td>
                        <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Extension Name:<span
                                class="font-bold"></span></td>
                    </tr>
                    <tr class="opacity-[0.87]">
                        <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Birthdate:<span
                                class="font-bold"></span></td>
                        <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Age:<span class="font-bold">
                            </span></td>
                    </tr>
                    <tr class="opacity-[0.87]">
                        <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Place of Birth:<span
                                class="font-bold"></span></td>
                        <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Mother Tongue:<span
                                class="font-bold"></span></td>
                    </tr>
                    <tr class="opacity-[0.87]">
                        <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Belong to any IP
                            community:<span class="font-bold"></span></td>
                        <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Beneficiary of 4Ps:<span
                                class="font-bold"></span></td>
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
                        <th
                            class="border-r border-[#1e1e1e]/15 px-4 py-2 bg-[#E3ECFF] text-start rounded-tl-[8px] text-[16px]">
                            Current Address</th>
                        <th class="px-4 py-2 bg-[#E3ECFF] text-start rounded-tr-[8px] text-[16px]">Permanent Address</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="opacity-[0.87]">
                        <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2 text-bold">House No:
                        </td>
                        <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">House No:<span
                                class="font-bold"></span> </td>
                    </tr>
                    <tr class="opacity-[0.87]">
                        <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Sitio/Street Name:
                        </td>
                        <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Sitio/Street Name:</td>
                    </tr>
                    <tr class="opacity-[0.87]">
                        <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Barangay:</td>
                        <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Barangay:</td>
                    </tr>
                    <tr class="opacity-[0.87]">
                        <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Municipality/City:
                        </td>
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
                        <th
                            class="border-b border-[#1e1e1e]/15 px-4 py-2 bg-[#E3ECFF] text-start rounded-tl-[8px] text-[16px]">
                            Parent/Guardian's Information</th>
                        <th class="border-b border-[#1e1e1e]/15 px-4 py-2 bg-[#E3ECFF] text-start text-[16px]"></th>
                        <th
                            class="border-b border-[#1e1e1e]/15 px-4 py-2 bg-[#E3ECFF] text-start rounded-tr-[8px] text-[16px]">
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="opacity-[0.87]">
                        <td class="px-4 py-2 text-[16px] border-b border-r border-[#1e1e1e]/15 font-bold">Mother's
                            Information:</td>
                        <td class="px-4 py-2 text-[16px] border-b border-r border-[#1e1e1e]/15 font-bold">Father's
                            Information:<span class="font-bold"></span></td>
                        <td class="px-4 py-2 text-[16px] border-b border-[#1e1e1e]/15 font-bold">Guardian's
                            Information:<span class="font-bold"></span></td>
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
                        <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2 text-bold">Preferred Class
                            Schedule:</td>
                        <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2 text-bold"></td>
                    </tr>
                    <tr class="opacity-[0.87]">
                        <td class="px-4 py-2 text-[14px] border-r border-[#1e1e1e]/15 w-1/2">Parent/Guardian's Signature:
                        </td>
                        <td class="px-4 py-2 text-[14px] w-1/2">Date Applied:</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="module">
        import {
            initModal
        } from "/js/modal.js";

        document.addEventListener("DOMContentLoaded", function() {

            initModal('edit-sched-modal', 'edit-sched-btn', 'edit-sched-close-btn', 'cancel-btn');
            initModal('record-interview-modal', 'record-interview-btn', 'record-interview-close-btn', 'cancel-btn');
            initModal('sched-interview-modal', 'record-btn', 'sched-interview-close-btn', 'cancel-btn');

            const detailsContainer = document.getElementById('details-container');
            const showDetailsBtn = document.getElementById('show-details-btn');

            showDetailsBtn.addEventListener('click', function() {
                detailsContainer.classList.toggle('hidden');
                if (detailsContainer.classList.contains('hidden')) {
                    showDetailsBtn.innerHTML =
                        'View Applicant\'s Full Details <i class="fi fi-rs-angle-small-down flex flex-row items-center text-[18px] text-[#0f111c]/80 transition duration-150"></i>';
                } else {
                    showDetailsBtn.innerHTML =
                        'Hide Applicant\'s Full Details <i class="fi fi-rs-angle-small-up flex flex-row items-center text-[18px] text-[#0f111c]/80 transition duration-150"></i>';
                }
            });

        });
    </script>
@endpush
