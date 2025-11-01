@extends('layouts.admin', ['title' => 'Students'])
@section('breadcrumbs')
    <nav aria-label="Breadcrumb" class="mb-4 mt-2">
        <ol class="flex items-center gap-1 text-sm text-gray-700">
            <li>
                <a href="/enrolled-students" class="block transition-colors hover:text-gray-900"> Enrolled Students </a>
            </li>

            <li class="rtl:rotate-180">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
            </li>

            <li>
                <a href="/selected-applications" class="block transition-colors hover:text-gray-900"> Student Information
                </a>
            </li>

        </ol>
    </nav>
@endsection
@section('modal')
    <x-modal modal_id="generate-coe-modal" modal_name="Generate Certificate of Enrollment"
        close_btn_id="generate-coe-close-btn" modal_container_id="modal-container-coe">
        <style>
            /* Smaller modal for preview */
            #generate-coe-modal>div {
                width: 60% !important;
                max-width: 48rem !important;
            }

            @media (min-width: 1280px) {
                #generate-coe-modal>div {
                    width: 55% !important;
                    max-width: 56rem !important;
                }
            }
        </style>
        <x-slot name="modal_icon">
            <div class="size-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center">
                <i class="fi fi-sr-file-pdf"></i>
            </div>
        </x-slot>
        <div class="p-6 space-y-3">
            <div class="w-full h-[65vh] bg-white border border-gray-200 rounded-md overflow-hidden">
                <iframe id="coe-preview-frame" class="w-full h-full"
                    src="{{ route('students.coe.preview', $student->record->id) }}?v={{ now()->timestamp }}"
                    title="COE Preview"></iframe>
            </div>
        </div>
        <x-slot name="modal_info"></x-slot>
        <x-slot name="modal_buttons">
            <div class="flex items-center justify-end gap-2">
                <button id="generate-coe-cancel-btn"
                    class="px-3 py-2 rounded-md text-gray-700 bg-gray-100 hover:bg-gray-200 transition">Cancel</button>
                <button
                    class="bg-[#199BCF] py-2 px-3 rounded-xl text-[14px] font-semibold gap-2 text-white hover:ring hover:ring-[#C8A165]/20 hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                    <a id="generate-coe-download-btn" target="_blank"
                        href="{{ route('students.coe.pdf', $student->record->id) }}">Download PDF</a>
                </button>

            </div>
        </x-slot>
    </x-modal>

    <x-modal modal_id="evaluate-student" modal_name="Academic Evaluation" close_btn_id="evaluate-student-close-btn"
        modal_container_id="modal-container-evaluate-student">


        <form action="/evaluate-student/{{ $student->id }}" method="POST" id="evaluate-form" class="p-6">
            @csrf
            @method('patch')
            <div class="mb-6">
                <h3 class="text-[14px] font-semibold text-gray-800 mb-4">Academic evaluation for the current term</h3>
                <div class="space-y-3 flex flex-col justify-center items-center">
                    <!-- Passed Option -->
                    <label for="passed"
                        class="w-full flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-green-300 transition-all duration-200 group has-[:checked]:border-green-500 has-[:checked]:bg-green-50">
                        <div class="flex items-center">
                            <div class="relative">
                                <input type="radio" name="result" id="passed" value="Passed" checked class="sr-only">
                                <div
                                    class="w-4 h-4 border-2 border-gray-300 rounded-full flex items-center justify-center group-has-[:checked]:border-green-500 group-has-[:checked]:bg-green-500 transition-all duration-200">
                                    <div
                                        class="w-2 h-2 bg-white rounded-full opacity-0 group-has-[:checked]:opacity-100 transition-opacity duration-200">
                                    </div>
                                </div>
                            </div>
                            <div class="ml-3">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                    <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900">Passed</span>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Student passed the current term</p>
                            </div>
                        </div>
                    </label>

                    <!-- Failed Option -->
                    <label for="failed"
                        class="w-full flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-red-300 transition-all duration-200 group has-[:checked]:border-red-500 has-[:checked]:bg-red-50">
                        <div class="flex items-center">
                            <div class="relative">
                                <input type="radio" name="result" id="failed" value="Failed" class="sr-only">
                                <div
                                    class="w-4 h-4 border-2 border-gray-300 rounded-full flex items-center justify-center group-has-[:checked]:border-red-500 group-has-[:checked]:bg-red-500 transition-all duration-200">
                                    <div
                                        class="w-2 h-2 bg-white rounded-full opacity-0 group-has-[:checked]:opacity-100 transition-opacity duration-200">
                                    </div>
                                </div>
                            </div>
                            <div class="ml-3">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                                    <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900">Failed</span>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Student did not meet the sufficient requirements for
                                    the current term</p>
                            </div>
                        </div>
                    </label>

                    <p class="self-center text-[14px] text-gray-500">Please make sure you select the correct option, as this
                        action cannot be undone.</p>
                </div>
            </div>

        </form>

        <x-slot name="modal_info"></x-slot>
        <x-slot name="modal_buttons">
            <button type="button" id="cancel-btn"
                class="border border-[#1e1e1e]/15 text-[14px] bg-gray-50 px-3 py-2 rounded-xl text-[#0f111c]/80 hover:ring hover:ring-gray-200 hover:bg-gray-100 font-semibold">
                Cancel
            </button>
            <button type="submit" form="evaluate-form"
                class="bg-[#199BCF] py-2 px-3 rounded-xl text-[14px] font-semibold gap-2 text-white hover:ring hover:ring-[#C8A165]/20 hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate"></i>Confirm
                Result
            </button>
        </x-slot>
    </x-modal>

    {{-- <!-- Academic Evaluation Modal -->
    <x-modal modal_id="evaluate-student" modal_name="Promote Confirmation" close_btn_id="evaluate-student-close-btn"
        modal_container_id="modal-container-evaluate-student">

        <x-slot name="modal_icon">
            <div class="size-10 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center">
                <i class="fi fi-rr-graduation-cap"></i>
            </div>
        </x-slot>

        <form action="/evaluate-student/{{ $student->id }}" method="POST" id="evaluate-form" class="p-6">
            @csrf
            @method('patch')
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Academic Evaluation</h3>

                <div class="space-y-3">
                    <!-- Passed Option -->
                    <label for="passed"
                        class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-green-300 transition-all duration-200 group has-[:checked]:border-green-500 has-[:checked]:bg-green-50">
                        <div class="flex items-center">
                            <div class="relative">
                                <input type="radio" name="result" id="passed" value="Passed" checked
                                    class="sr-only">
                                <div
                                    class="w-4 h-4 border-2 border-gray-300 rounded-full flex items-center justify-center group-has-[:checked]:border-green-500 group-has-[:checked]:bg-green-500 transition-all duration-200">
                                    <div
                                        class="w-2 h-2 bg-white rounded-full opacity-0 group-has-[:checked]:opacity-100 transition-opacity duration-200">
                                    </div>
                                </div>
                            </div>
                            <div class="ml-3">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                    <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900">Passed</span>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Student has successfully completed the current term
                                    requirements</p>
                            </div>
                        </div>
                    </label>

                    <!-- Failed Option -->
                    <label for="failed"
                        class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-red-300 transition-all duration-200 group has-[:checked]:border-red-500 has-[:checked]:bg-red-50">
                        <div class="flex items-center">
                            <div class="relative">
                                <input type="radio" name="result" id="failed" value="Failed" class="sr-only">
                                <div
                                    class="w-4 h-4 border-2 border-gray-300 rounded-full flex items-center justify-center group-has-[:checked]:border-red-500 group-has-[:checked]:bg-red-500 transition-all duration-200">
                                    <div
                                        class="w-2 h-2 bg-white rounded-full opacity-0 group-has-[:checked]:opacity-100 transition-opacity duration-200">
                                    </div>
                                </div>
                            </div>
                            <div class="ml-3">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                                    <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900">Failed</span>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Student did not meet the minimum requirements for the
                                    current term</p>
                            </div>
                        </div>
                    </label>

                    <!-- Incomplete Option -->
                    <label for="incomplete"
                        class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-yellow-300 transition-all duration-200 group has-[:checked]:border-yellow-500 has-[:checked]:bg-yellow-50">
                        <div class="flex items-center">
                            <div class="relative">
                                <input type="radio" name="result" id="incomplete" value="Incomplete"
                                    class="sr-only">
                                <div
                                    class="w-4 h-4 border-2 border-gray-300 rounded-full flex items-center justify-center group-has-[:checked]:border-yellow-500 group-has-[:checked]:bg-yellow-500 transition-all duration-200">
                                    <div
                                        class="w-2 h-2 bg-white rounded-full opacity-0 group-has-[:checked]:opacity-100 transition-opacity duration-200">
                                    </div>
                                </div>
                            </div>
                            <div class="ml-3">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></div>
                                    <span
                                        class="text-sm font-medium text-gray-700 group-hover:text-gray-900">Incomplete</span>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Student has incomplete requirements or pending
                                    submissions</p>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

        </form>

        <x-slot name="modal_info"></x-slot>
        <x-slot name="modal_buttons">
            <button type="button" id="evaluate-student-cancel-btn"
                class="border border-[#1e1e1e]/15 text-[14px] bg-gray-50 px-3 py-2 rounded-xl text-[#0f111c]/80 hover:ring hover:ring-gray-200 hover:bg-gray-100 font-semibold">
                Cancel
            </button>
            <button type="submit" form="evaluate-form"
                class="bg-indigo-600 hover:bg-indigo-700 py-2 px-3 rounded-xl text-[14px] font-semibold gap-2 text-white transition duration-200 shadow-lg">
                <i class="fi fi-rr-check mr-1"></i>Confirm Evaluation
            </button>
        </x-slot>
    </x-modal> --}}

    <!-- Student Promotion Modal -->
    <x-modal modal_id="promote-student" modal_name="Promote Student to Next Grade Level"
        close_btn_id="promote-student-close-btn" modal_container_id="modal-container-promote-student">

        <x-slot name="modal_icon">
            <div class="size-10 bg-teal-100 text-teal-600 rounded-full flex items-center justify-center">
                <i class="fi fi-rr-arrow-up"></i>
            </div>
        </x-slot>

        <form action="/promote-student/{{ $student->id }}" method="POST" id="promote-form" class="p-6">
            @csrf
            @method('patch')

            @php

                $message = '';
                $action = '';
                $warning = '';
                $warningColor = '';

                if ($student->grade_level === 'Grade 11') {
                    $message = 'This action will promote the student to the next year level (Grade 12)';
                    $action = 'promote-to-next-year';
                } else {
                    $message = 'This action will mark the student as graduated';
                    $action = 'mark-as-graduated';
                }

                if ($student->academic_status === null) {
                    $warning = 'The student has not yet evaluated by the designated faculty member.';
                    $warningColor = 'yellow';
                } elseif ($student->academic_status === 'Failed') {
                    $warning = 'The student has been evaluated as Failed. Promotion is not recommended';
                    $warningColor = 'red';
                }

            @endphp

            <input type="hidden" name="action" value="{{ $action }}">

            <div class="flex flex-col justify-center items-center py-8 px-6 font-regular text-[14px] text-center">
                <div class="flex justify-center items-center w-auto p-6 bg-teal-100 w-[300px] rounded-full">
                    <i class='fi fi-ss-exclamation flex justify-center text-[52px] items-center text-teal-500'></i>
                </div>
                <div class="py-8 px-6 space-y-2 font-regular text-[14px] text-center">
                    <p class="text-gray-700 text-[16px] font-semibold">
                        Are you sure you want promote this student?
                    </p>
                    <p class="text-gray-500">
                        {{ $message }}.
                    </p>
                    <p class="text-gray-600 font-semibold">Current Year Level: {{ $student->grade_level }}
                    </p>
                </div>
                @if ($warningColor === 'yellow')
                    <div class="bg-yellow-50 border border-yellow-400 rounded-xl p-4">
                        <p class="text-yellow-600">{{ $warning }}</p>
                    </div>
                @elseif ($warningColor === 'red')
                    <div class="bg-red-50 border border-red-400 rounded-xl p-4">
                        <p class="text-red-600">{{ $warning }}</p>
                    </div>
                @endif

                <div class="flex flex-row justify-center items-center mt-6 px-4">
                    <p class="text-start text-[12px] text-gray-600"><span
                            class="text-gray-800 font-semibold">Important:</span> The system automatically promotes all
                        students at the start of a new academic term. Please proceed
                        with this manual action only for special cases.</p>
                </div>

            </div>
        </form>

        <x-slot name="modal_info"></x-slot>
        <x-slot name="modal_buttons">
            <button type="button" id="promote-student-cancel-btn"
                class="border border-[#1e1e1e]/15 text-[14px] bg-gray-50 px-3 py-2 rounded-xl text-[#0f111c]/80 hover:ring hover:ring-gray-200 hover:bg-gray-100 font-semibold">
                Cancel
            </button>
            <button type="submit" form="promote-form"
                class="bg-teal-600 hover:bg-teal-700 py-2 px-3 rounded-xl text-[14px] font-semibold gap-2 text-white transition duration-200 shadow-lg">
                <i class="fi fi-rr-arrow-up mr-1"></i>Confirm Promotion
            </button>
        </x-slot>
    </x-modal>

    <!-- Edit Personal Information Modal -->
    <x-modal modal_id="edit-personal-info-modal" modal_name="Edit Personal Information"
        close_btn_id="edit-personal-info-close-btn" modal_container_id="modal-container-personal-info">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-user flex justify-center items-center text-blue-500'></i>
        </x-slot>

        <form id="edit-personal-info-form" class="p-6">
            @csrf
            @method('PUT')

            <div class="flex flex-col justify-center items-center space-y-4">
                <div class="w-full">
                    <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-square-f mr-2"></i>
                        First Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="first_name" id="first_name" required placeholder="Enter first name"
                        class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]">
                </div>

                <div class="w-full">
                    <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-square-l mr-2"></i>
                        Last Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="last_name" id="last_name" required placeholder="Enter last name"
                        class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]">
                </div>

                <div class="w-full">
                    <label for="middle_name" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-square-m mr-2"></i>
                        Middle Name
                    </label>
                    <input type="text" name="middle_name" id="middle_name" placeholder="Enter middle name"
                        class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]">
                </div>

                <div class="w-full">
                    <label for="extension_name" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-square-e mr-2"></i>
                        Extension Name
                    </label>
                    <input type="text" name="extension_name" id="extension_name" placeholder="e.g., Jr., Sr., III"
                        class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]">
                </div>

                <div class="w-full">
                    <label for="birthdate" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-party-horn mr-2"></i>
                        Birthdate <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="birthdate" id="birthdate" required
                        class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]">
                </div>

                <div class="w-full">
                    <label for="place_of_birth" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-land-layer-location mr-2"></i>
                        Place of Birth
                    </label>
                    <input type="text" name="place_of_birth" id="place_of_birth" placeholder="Enter place of birth"
                        class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]">
                </div>
            </div>
        </form>

        <x-slot name="modal_buttons">
            <button id="edit-personal-info-cancel-btn"
                class="bg-gray-50 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-100 hover:ring hover:ring-gray-200 transition duration-150">
                Cancel
            </button>
            <button type="submit" form="edit-personal-info-form" id="edit-personal-info-submit-btn"
                class="bg-[#199BCF] py-2 px-3 rounded-xl text-[14px] font-semibold gap-2 text-white hover:ring hover:ring-[#C8A165]/20 hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                <i class="fi fi-rr-disk mr-1"></i>Update Information
            </button>
        </x-slot>
    </x-modal>

    <!-- Edit Academic Information Modal -->
    <x-modal modal_id="edit-academic-info-modal" modal_name="Edit Academic Information"
        close_btn_id="edit-academic-info-close-btn" modal_container_id="modal-container-academic-info">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-graduation-cap flex justify-center items-center text-emerald-500'></i>
        </x-slot>

        <form id="edit-academic-info-form" class="p-6">
            @csrf
            @method('PUT')

            <div class="flex flex-col justify-center items-center space-y-4">
                <div class="w-full">
                    <label for="lrn" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-hastag mr-2"></i>
                        LRN (Learner Reference Number) <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="lrn" id="lrn" required placeholder="Enter LRN"
                        class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]">
                </div>

                <div class="w-full">
                    <label for="grade_level" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-star mr-2"></i>
                        Grade Level <span class="text-red-500">*</span>
                    </label>
                    <select name="grade_level" id="grade_level" required
                        class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]">
                        <option value="">Select Grade Level</option>
                        <option value="Grade 11">Grade 11</option>
                        <option value="Grade 12">Grade 12</option>
                    </select>
                </div>

                <div class="w-full">
                    <label for="program_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-graduation-cap mr-2"></i>
                        Program <span class="text-red-500">*</span>
                    </label>
                    <select name="program_id" id="program_id"
                        class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]">
                        <option value="">Select Program</option>
                        @foreach ($programs ?? [] as $program)
                            <option value="{{ $program->id }}">{{ $program->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full">
                    <label for="section" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-tags mr-2"></i>
                        Section
                    </label>
                    <select name="section" id="section"
                        class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]">
                        <option value="">Select Section</option>
                        @foreach ($sections ?? [] as $section)
                            <option value="{{ $section->id }}">{{ $section->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full">
                    <label for="acad_term_applied" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-calendar mr-2"></i>
                        Academic Year
                    </label>
                    <input type="text" name="acad_term_applied" id="acad_term_applied" placeholder="e.g., 2024-2025"
                        class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]">
                </div>

                <div class="w-full">
                    <label for="semester_applied" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-hourglass-end mr-2"></i>
                        Semester
                    </label>
                    <select name="semester_applied" id="semester_applied"
                        class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]">
                        <option value="">Select Semester</option>
                        <option value="1st Semester">1st Semester</option>
                        <option value="2nd Semester">2nd Semester</option>
                    </select>
                </div>
            </div>
        </form>

        <x-slot name="modal_buttons">
            <button id="edit-academic-info-cancel-btn"
                class="bg-gray-50 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-100 hover:ring hover:ring-gray-200 transition duration-150">
                Cancel
            </button>
            <button type="submit" form="edit-academic-info-form" id="edit-academic-info-submit-btn"
                class="bg-emerald-600 hover:bg-emerald-700 py-2 px-3 rounded-xl text-[14px] font-semibold gap-2 text-white transition duration-200 shadow-lg">
                <i class="fi fi-rr-disk mr-1"></i>Update Academic Info
            </button>
        </x-slot>
    </x-modal>

    <!-- Edit Address Information Modal -->
    <x-modal modal_id="edit-address-info-modal" modal_name="Edit Address Information"
        close_btn_id="edit-address-info-close-btn" modal_container_id="modal-container-address-info">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-marker flex justify-center items-center text-purple-500'></i>
        </x-slot>

        <form id="edit-address-info-form" class="p-6">
            @csrf
            @method('PUT')

            <div class="flex flex-col justify-center items-center space-y-4">
                <div class="w-full">
                    <label for="house_no" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-home mr-2"></i>
                        House Number
                    </label>
                    <input type="text" name="house_no" id="house_no" placeholder="Enter house number"
                        class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]">
                </div>

                <div class="w-full">
                    <label for="street" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-road mr-2"></i>
                        Street/Sitio
                    </label>
                    <input type="text" name="street" id="street" placeholder="Enter street or sitio"
                        class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]">
                </div>

                <div class="w-full">
                    <label for="barangay" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-map-marker mr-2"></i>
                        Barangay <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="barangay" id="barangay" required placeholder="Enter barangay"
                        class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]">
                </div>

                <div class="w-full">
                    <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-city mr-2"></i>
                        Municipality/City <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="city" id="city" required
                        placeholder="Enter municipality or city"
                        class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]">
                </div>

                <div class="w-full">
                    <label for="country" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-globe mr-2"></i>
                        Country
                    </label>
                    <input type="text" name="country" id="country" placeholder="Enter country"
                        class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]">
                </div>

                <div class="w-full">
                    <label for="zip_code" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-mailbox mr-2"></i>
                        Zip Code
                    </label>
                    <input type="text" name="zip_code" id="zip_code" placeholder="Enter zip code"
                        class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]">
                </div>
            </div>
        </form>

        <x-slot name="modal_buttons">
            <button id="edit-address-info-cancel-btn"
                class="bg-gray-50 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-100 hover:ring hover:ring-gray-200 transition duration-150">
                Cancel
            </button>
            <button type="submit" form="edit-address-info-form" id="edit-address-info-submit-btn"
                class="bg-purple-600 hover:bg-purple-700 py-2 px-3 rounded-xl text-[14px] font-semibold gap-2 text-white transition duration-200 shadow-lg">
                <i class="fi fi-rr-disk mr-1"></i>Update Address
            </button>
        </x-slot>
    </x-modal>

    <!-- Edit Emergency Contact Modal -->
    <x-modal modal_id="edit-emergency-info-modal" modal_name="Edit Emergency Contact"
        close_btn_id="edit-emergency-info-close-btn" modal_container_id="modal-container-emergency-info">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-phone-call flex justify-center items-center text-orange-500'></i>
        </x-slot>

        <form id="edit-emergency-info-form" class="p-6">
            @csrf
            @method('PUT')

            <div class="flex flex-col justify-center items-center space-y-4">
                <div class="w-full">
                    <label for="contact_number" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-phone-flip mr-2"></i>
                        Student Contact Number <span class="text-red-500">*</span>
                    </label>
                    <input type="tel" name="contact_number" id="contact_number" required
                        placeholder="Enter contact number"
                        class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]">
                </div>

                <div class="w-full">
                    <label for="guardian_name" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-user mr-2"></i>
                        Guardian Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="guardian_name" id="guardian_name" required
                        placeholder="Enter guardian's full name"
                        class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]">
                </div>

                <div class="w-full">
                    <label for="guardian_contact_number" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-phone mr-2"></i>
                        Guardian Contact Number <span class="text-red-500">*</span>
                    </label>
                    <input type="tel" name="guardian_contact_number" id="guardian_contact_number" required
                        placeholder="Enter guardian's contact number"
                        class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]">
                </div>
            </div>
        </form>

        <x-slot name="modal_buttons">
            <button id="edit-emergency-info-cancel-btn"
                class="bg-gray-50 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-100 hover:ring hover:ring-gray-200 transition duration-150">
                Cancel
            </button>
            <button type="submit" form="edit-emergency-info-form" id="edit-emergency-info-submit-btn"
                class="bg-orange-600 hover:bg-orange-700 py-2 px-3 rounded-xl text-[14px] font-semibold gap-2 text-white transition duration-200 shadow-lg">
                <i class="fi fi-rr-disk mr-1"></i>Update Contact Info
            </button>
        </x-slot>
    </x-modal>

    {{-- Withdraw student enrollment --}}
    <x-modal modal_id="withdraw-student" modal_name="Enrollment Withdrawal Confirmation"
        close_btn_id="withdraw-student-close-btn" modal_container_id="modal-container-withdraw-student">

        <x-slot name="modal_icon">
            <div class="size-10 bg-red-100 text-red-600 rounded-full flex items-center justify-center">
                <i class="fi fi-rr-arrow-down"></i>
            </div>
        </x-slot>

        <form action="/withdraw-student/{{ $student->id }}" method="POST" id="withdraw-form" class="p-6">
            @csrf
            @method('patch')

            <div class="flex flex-col justify-center items-center py-8 px-6 font-regular text-[14px] text-center">
                <div class="flex justify-center items-center w-auto p-6 bg-red-100 w-[300px] rounded-full">
                    <i class='fi fi-ss-exclamation flex justify-center text-[52px] items-center text-red-500'></i>
                </div>
                <div class="py-8 px-6 space-y-2 font-regular text-[14px] text-center">
                    <p class="text-gray-700 text-[16px] font-semibold">
                        Are you sure you want withdraw the enrollment of this student?
                    </p>
                    <p class="text-gray-500">
                        This would mark the student as dropped.
                    </p>
                </div>

            </div>
        </form>

        <x-slot name="modal_info"></x-slot>
        <x-slot name="modal_buttons">
            <button type="button" id="withdraw-student-cancel-btn"
                class="border border-[#1e1e1e]/15 text-[14px] bg-gray-50 px-3 py-2 rounded-xl text-[#0f111c]/80 hover:ring hover:ring-gray-200 hover:bg-gray-100 font-semibold">
                Cancel
            </button>
            <button type="submit" form="withdraw-form"
                class="bg-red-600 hover:bg-red-700 py-2 px-3 rounded-xl text-[14px] font-semibold gap-2 text-white transition duration-200 shadow-lg">
                <i class="fi fi-rr-arrow-down mr-1"></i>Confirm Withdrawal
            </button>
        </x-slot>
    </x-modal>
@endsection
@section('header')
    <div class="flex flex-col justify-center items-start text-start px-[14px] py-2">
        <h1 class="text-[20px] font-black">Student Information</h1>
        <p class="text-[14px]  text-gray-900/60">View and manage individual student information and records.
        </p>
    </div>
@endsection
@section('content')
    <x-alert />

    <div class="flex flex-row justify-center items-start gap-4">
        <div
            class="flex flex-row justify-center items-start flex-grow p-6  bg-[#f8f8f8] rounded-xl shadow-md border border-[#1e1e1e]/10 w-[40%]">
            {{-- info container --}}
            <div class="flex-1 flex flex-col gap-4 border-r border-[#1e1e1e]/10 pr-6">
                {{-- profile --}}
                <div class="flex flex-row gap-4">
                    <img src="{{ asset('images/business-man.png') }}" alt=""
                        class="size-20 rounded-md ring ring-gray-200">
                    <div class="flex flex-col justify-center items-start pt-1">
                        <p class="text-lg font-bold">{{ $student->record->getFullName() ?? 'N/A' }}</p>
                        <p class="text-sm font-medium opacity-60">#{{ $student->lrn ?? 'N/A' }}</p>

                        @if ($student->status === 'Officially Enrolled')
                            @if ($student->enrollments->isNotEmpty() && $student->enrollments->first()->status === 'enrolled')
                                <div class="bg-blue-50 border border-blue-300 rounded-lg px-3 py-1 mt-1">
                                    <p class="text-blue-500 text-[12px]">Enrolled</p>
                                </div>
                            @else
                                <div class="bg-yellow-50 border border-yellow-300 rounded-lg px-3 py-1 mt-1">
                                    <p class="text-yellow-500 text-[12px]">Pending-Confirmation</p>
                                </div>
                            @endif
                        @elseif ($student->status === 'Graduated')
                            <div class="bg-green-50 border border-green-300 rounded-lg px-3 py-1 mt-1">
                                <p class="text-green-500 text-[12px]">Graduated</p>
                            </div>
                        @elseif ($student->status === 'Dropped')
                            <div class="bg-red-50 border border-red-300 rounded-lg px-3 py-1 mt-1">
                                <p class="text-red-500 text-[12px]">Dropped</p>
                            </div>
                        @elseif ($student->status === 'Transferred')
                            <div class="bg-gray-50 border border-gray-300 rounded-lg px-3 py-1 mt-1">
                                <p class="text-gray-500 text-[12px]">Transferred</p>
                            </div>
                        @endif
                    </div>
                </div>
                {{-- About --}}
                <div class="flex flex-col justify-center items-start space-y-1 pb-4 border-b border-[#1e1e1e]/10">
                    <h2 class="font-bold opacity-90">About</h2>
                    <div class="flex flex-col justify-start items-start w-full gap-1">
                        <div class="flex flex-row justify-start items-start w-full gap-2">
                            {{-- icon here --}}
                            <div class="flex flex-row justify-center items-center gap-2 opacity-70">
                                <i class="fi fi-rr-phone-flip flex justify-center items-center"></i>
                                <span>Phone:</span>
                            </div>
                            <p class="font-semibold opacity-85">{{ $student->record->contact_number ?? 'N/A' }}</p>
                        </div>
                        <div class="flex flex-row justify-start items-start w-full gap-2">
                            {{-- icon here --}}
                            <div class="flex flex-row justify-center items-center gap-2 opacity-70">
                                <i class="fi fi-rr-at flex justify-center items-center"></i>
                                <span>Email:</span>
                            </div>
                            <p class="font-semibold opacity-85">{{ $student->user->email ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
                {{-- personal info --}}
                <div class="space-y-1 pb-4 border-b border-[#1e1e1e]/10">
                    <div class="flex flex-row justify-between items-center">
                        <h2 class="font-bold opacity-90">Personal information</h2>
                        <button id="edit-personal-info-btn"
                            class="edit-btn opacity-0 pointer-events-none scale-90 text-[14px] font-semibold text-blue-500 hover:text-blue-600 transition duration-150">
                            Edit
                        </button>
                    </div>
                    <div class="flex flex-col justify-center items-start w-full gap-1">
                        <div class="flex flex-row justify-start items-start gap-2 w-full">
                            <div class="flex flex-row justify-center items-center gap-2 opacity-70">
                                <i class="fi fi-rr-square-l flex justify-center items-center"></i>
                                <span>Last Name:</span>
                            </div>
                            <p class="font-semibold opacity-85">{{ $student->user->last_name ?? 'N/A' }}</p>
                        </div>
                        <div class="flex flex-row justify-start items-start gap-2 w-full">
                            <div class="flex flex-row justify-center items-center gap-2 opacity-70">
                                <i class="fi fi-rr-square-f flex justify-center items-center"></i>
                                <span>First Name:</span>
                            </div>
                            <p class="font-semibold opacity-85">{{ $student->user->first_name ?? 'N/A' }}</p>
                        </div>
                        <div class="flex flex-row justify-start items-start gap-2 w-full">
                            <div class="flex flex-row justify-center items-center gap-2 opacity-70">
                                <i class="fi fi-rr-square-m flex justify-center items-center"></i>
                                <span>Middle Name:</span>
                            </div>
                            <p class="font-semibold opacity-85">{{ $student->record->middle_name ?? 'N/A' }}</p>
                        </div>
                        <div class="flex flex-row justify-start items-start gap-2 w-full">
                            <div class="flex flex-row justify-center items-center gap-2 opacity-70">
                                <i class="fi fi-rr-square-e flex justify-center items-center"></i>
                                <span>Extension Name:</span>
                            </div>
                            <p class="font-semibold opacity-85">{{ $student->record->extension_name ?? 'N/A' }}</p>
                        </div>
                        <div class="flex flex-row justify-start items-start gap-2 w-full">
                            <div class="flex flex-row justify-center items-center gap-2 opacity-70">
                                <i class="fi fi-rr-party-horn flex justify-center items-center"></i>
                                <span>Birthdate:</span>
                            </div>
                            <p class="font-semibold opacity-85">
                                {{ \Carbon\Carbon::parse($student->record->birthdate)->timezone('Asia/Manila')->format('M. d, Y') }}
                            </p>
                        </div>
                        <div class="flex flex-row justify-start items-start gap-2 w-full">
                            <div class="flex flex-row justify-center items-center gap-2 opacity-70">
                                <i class="fi fi-rr-age-restriction-sixteen flex justify-center items-center"></i>
                                <span>Age</span>
                            </div>
                            <p class="font-semibold opacity-85">{{ $student->record->age ?? 'N/A' }}</p>
                        </div>
                        <div class="flex flex-row justify-start items-start gap-2 w-full">
                            <div class="flex flex-row justify-center items-center gap-2 opacity-70">
                                <i class="fi fi-rr-land-layer-location flex justify-center items-center"></i>
                                <span>Place of Birth</span>
                            </div>
                            <p class="font-semibold opacity-85">{{ $student->record->place_of_birth ?? 'N/A' }}</p>
                        </div>
                    </div>

                </div>
                {{-- academic info --}}
                <div class="space-y-1 pb-4 border-b border-[#1e1e1e]/10">
                    <div class="flex flex-row justify-between items-center">
                        <h2 class="font-bold opacity-90">Academic information</h2>
                        <button id="edit-academic-info-btn"
                            class="edit-btn opacity-0 pointer-events-none text-[14px] font-semibold text-blue-500 hover:text-blue-600 transition duration-150">
                            Edit
                        </button>
                    </div>
                    <div class="flex flex-col justify-center items-start w-full gap-1">
                        <div class="flex flex-row justify-start items-start gap-2 w-full">
                            <div class="flex flex-row justify-center items-center gap-2 opacity-70">
                                <i class="fi fi-rr-hastag flex justify-center items-center"></i>
                                <span>LRN:</span>
                            </div>
                            <p class="font-semibold opacity-85">{{ $student->lrn ?? 'N/A' }}</p>
                        </div>
                        <div class="flex flex-row justify-start items-start gap-2 w-full">
                            <div class="flex flex-row justify-center items-center gap-2 opacity-70">
                                <i class="fi fi-rr-star flex justify-center items-center"></i>
                                <span>Grade Level:</span>
                            </div>
                            <p class="font-semibold opacity-85">{{ $student->grade_level ?? 'N/A' }}</p>
                        </div>
                        <div class="flex flex-row justify-start items-start gap-2 w-full">
                            <div class="flex flex-row justify-center items-center gap-2 opacity-70">
                                <i class="fi fi-rr-graduation-cap flex justify-center items-center"></i>
                                <span>Program:</span>
                            </div>
                            <p class="font-semibold opacity-85">{{ $student->program->code ?? 'N/A' }}</p>
                        </div>
                        <div class="flex flex-row justify-start items-start gap-2 w-full">
                            <div class="flex flex-row justify-center items-center gap-2 opacity-70">
                                <i class="fi fi-rr-calendar flex justify-center items-center"></i>
                                <span>Academic Year:</span>
                            </div>
                            <p class="font-semibold opacity-85">
                                {{ $student->record?->acad_term_applied ?? $acadTerm->year }}</p>
                        </div>
                        <div class="flex flex-row justify-start items-start gap-2 w-full">
                            <div class="flex flex-row justify-center items-center gap-2 opacity-70">
                                <i class="fi fi-rr-hourglass-end flex justify-center items-center"></i>
                                <span>Semester</span>
                            </div>
                            <p class="font-semibold opacity-85">
                                {{ $student->record?->acad_term_applied ?? $acadTerm->semester }}</p>
                        </div>
                        <div class="flex flex-row justify-start items-start gap-2 w-full">
                            <div class="flex flex-row justify-center items-center gap-2 opacity-70">
                                <i class="fi fi-rr-tags flex justify-center items-center"></i>
                                <span>Section</span>
                            </div>
                            <p class="font-semibold opacity-85">{{ $student->getCurrentSectionName() }}</p>
                        </div>
                        <div class="flex flex-row justify-start items-start gap-2 w-full">
                            <div class="flex flex-row justify-center items-center gap-2 opacity-70">
                                <i class="fi fi-rr-graduation-cap flex justify-center items-center"></i>
                                <span>Academic Status:</span>
                            </div>
                            <div class="flex items-center">
                                @php
                                    $status = $student->academic_status ?? 'Not Evaluated';
                                    $statusConfig = [
                                        'Passed' => [
                                            'class' => 'bg-green-100 text-green-800',
                                            'label' => 'Passed',
                                        ],
                                        'Failed' => [
                                            'class' => 'bg-red-100 text-red-800',
                                            'label' => 'Failed',
                                        ],
                                        'Completed' => [
                                            'class' => 'bg-blue-100 text-blue-800',
                                            'label' => 'Completed',
                                        ],
                                        'Not Evaluated' => [
                                            'class' => 'bg-gray-100 text-gray-800',
                                            'label' => 'Not Evaluated',
                                        ],
                                    ];
                                    $config = $statusConfig[$status] ?? $statusConfig['Not Evaluated'];
                                @endphp
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $config['class'] }}">
                                    {{ $config['label'] }}
                                </span>
                            </div>
                        </div>
                    </div>

                </div>
                {{-- addresss --}}
                <div class="space-y-2">
                    <div class="flex flex-row justify-between items-center">
                        <h2 class="font-bold opacity-90">Address</h2>
                        <button id="edit-address-info-btn"
                            class="edit-btn opacity-0 pointer-events-none text-[14px] font-semibold text-blue-500 hover:text-blue-600 transition duration-150">
                            Edit
                        </button>
                    </div>
                    <div class="flex flex-col justify-center items-start w-full gap-1">
                        <div class="flex flex-row justify-start items-start gap-2 w-full">
                            <div class="flex flex-row justify-center items-center gap-2 opacity-70">
                                {{-- icon here --}}
                                <span>House No:</span>
                            </div>
                            <p class="font-semibold opacity-85">{{ $student->record->house_no ?? 'N/A' }}</p>
                        </div>
                        <div class="flex flex-row justify-start items-start gap-2 w-full">
                            <div class="flex flex-row justify-center items-center gap-2 opacity-70">
                                {{-- icon here --}}
                                <span>Sitio/Street:</span>
                            </div>
                            <p class="font-semibold opacity-85">{{ $student->record->street ?? 'N/A' }}</p>
                        </div>
                        <div class="flex flex-row justify-start items-start gap-2 w-full">
                            <div class="flex flex-row justify-center items-center gap-2 opacity-70">
                                {{-- icon here --}}
                                <span>Barangay:</span>
                            </div>
                            <p class="font-semibold opacity-85">{{ $student->record->barangay ?? 'N/A' }}</p>
                        </div>
                        <div class="flex flex-row justify-start items-start gap-2 w-full">
                            <div class="flex flex-row justify-center items-center gap-2 opacity-70">
                                {{-- icon here --}}
                                <span>Municipality/City:</span>
                            </div>
                            <p class="font-semibold opacity-85">{{ $student->record->city ?? 'N/A' }}</p>
                        </div>
                        <div class="flex flex-row justify-start items-start gap-2 w-full">
                            <div class="flex flex-row justify-center items-center gap-2 opacity-70">
                                {{-- icon here --}}
                                <span>Country:</span>
                            </div>
                            <p class="font-semibold opacity-85">{{ $student->record->country ?? 'N/A' }}</p>
                        </div>
                        <div class="flex flex-row justify-start items-start gap-2 w-full">
                            <div class="flex flex-row justify-center items-center gap-2 opacity-70">
                                {{-- icon here --}}
                                <span>Zip Code:</span>
                            </div>
                            <p class="font-semibold opacity-85">{{ $student->record->zip_code ?? 'N/A' }}</p>
                        </div>
                    </div>

                </div>
                {{-- emergency contact --}}
                <div class="space-y-2">
                    <div class="flex flex-row justify-between items-center">
                        <h2 class="font-bold opacity-90">Emergency contact</h2>
                        <button id="edit-emergency-info-btn"
                            class="edit-btn opacity-0 pointer-events-none text-[14px] font-semibold text-blue-500 hover:text-blue-600 transition duration-150">
                            Edit
                        </button>
                    </div>
                    <div class="flex flex-col justify-center items-start w-full gap-1">
                        <div class="flex flex-row justify-start items-start gap-2 w-full">
                            <div class="flex flex-row justify-center items-center gap-2 opacity-70">
                                {{-- icon here --}}
                                <span>Guardian Name:</span>
                            </div>
                            <p class="font-semibold opacity-85">{{ $student->record->guardian_name ?? 'N/A' }}</p>
                        </div>
                        <div class="flex flex-row justify-start items-start gap-2 w-full">
                            <div class="flex flex-row justify-center items-center gap-2 opacity-70">
                                {{-- icon here --}}
                                <span>Guardian Contact Number:</span>
                            </div>
                            <p class="font-semibold opacity-85">{{ $student->record->guardian_contact_number ?? 'N/A' }}
                            </p>
                        </div>
                    </div>

                </div>
                <div class="space-y-2">
                    <div class="flex flex-row justify-between items-center">
                        <h2 class="font-bold opacity-90">Other informations</h2>
                    </div>
                    <div class="flex flex-col justify-center items-start w-full gap-1">
                        <div class="flex flex-row justify-start items-start gap-2 w-full">
                            <div class="flex flex-row justify-center items-center gap-2 opacity-70">
                                {{-- icon here --}}
                                <span>Last School Attended:</span>
                            </div>
                            <p class="font-semibold opacity-85">{{ $student->record->last_school_attended ?? 'N/A' }}</p>
                        </div>
                        <div class="flex flex-row justify-start items-start gap-2 w-full">
                            <div class="flex flex-row justify-center items-center gap-2 opacity-70">
                                {{-- icon here --}}
                                <span>Last Grade Level Completed:</span>
                            </div>
                            <p class="font-semibold opacity-85">
                                {{ $student->record->last_grade_level_completed ?? 'N/A' }}</p>
                        </div>
                        <div class="flex flex-row justify-start items-start gap-2 w-full">
                            <div class="flex flex-row justify-center items-center gap-2 opacity-70">
                                {{-- icon here --}}
                                <span>School ID:</span>
                            </div>
                            <p class="font-semibold opacity-85">{{ $student->record->school_id ?? 'N/A' }}</p>
                        </div>
                        <div class="flex flex-row justify-start items-start gap-2 w-full">
                            <div class="flex flex-row justify-center items-center gap-2 opacity-70">
                                {{-- icon here --}}
                                <span>Admission Date:</span>
                            </div>
                            <p class="font-semibold opacity-85">

                                {{ \Carbon\Carbon::parse($student->record->admission_date)->timezone('Asia/Manila')->format('M. d, Y — g:i A') }}

                            </p>
                        </div>
                        <div class="flex flex-row justify-start items-start gap-2 w-full">
                            <div class="flex flex-row justify-center items-center gap-2 opacity-70">
                                {{-- icon here --}}
                                <span>Special Needs:</span>
                            </div>
                            <p class="font-semibold opacity-85">
                                {{ implode(', ', $student->record->special_needs ?? []) }}</p>
                        </div>
                        <div class="flex flex-row justify-start items-start gap-2 w-full">
                            <div class="flex flex-row justify-center items-center gap-2 opacity-70">
                                {{-- icon here --}}
                                <span>Belongs to IP:</span>
                            </div>
                            <p class="font-semibold opacity-85">{{ $student->record->belongs_to_ip === 1 ? 'Yes' : 'No' }}
                            </p>
                        </div>
                        <div class="flex flex-row justify-start items-start gap-2 w-full">
                            <div class="flex flex-row justify-center items-center gap-2 opacity-70">
                                {{-- icon here --}}
                                <span>4Ps Beneficiary:</span>
                            </div>
                            <p class="font-semibold opacity-85">
                                {{ $student->record->is_4ps_beneficiary === 1 ? 'Yes' : 'No' }}</p>
                        </div>
                    </div>

                </div>
            </div>

            <div class="w-2/3 flex flex-col justify-start items-start pl-6 gap-8">
                <!-- Student Management Actions -->
                <div class="w-full">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Student Management</h2>
                            <p class="text-sm text-gray-600 mt-1">Manage student information and generate documents</p>
                        </div>
                    </div>

                    <!-- Action Buttons Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @hasanyrole('registrar|super_admin')
                            <!-- Edit Student Info -->
                            @if ($student->status === 'Officially Enrolled')
                                <button id="edit-info-btn"
                                    class="group relative overflow-hidden bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-xl p-4 transition-all duration-300 hover:shadow-lg hover:shadow-blue-500/25 hover:-translate-y-1">
                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="flex-shrink-0 w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center group-hover:bg-white/30 transition-colors">
                                            <i class="fi fi-rr-edit flex justify-center items-center text-lg"></i>
                                        </div>
                                        <div class="text-left">
                                            <p class="font-semibold text-sm">Edit Student Info</p>
                                            <p class="text-xs text-blue-100">Update personal details</p>
                                        </div>
                                    </div>
                                </button>
                            @endif
                        @endhasanyrole

                        @hasanyrole('registrar|super_admin')
                            <!-- Generate COE -->
                            @if ($student->status === 'Officially Enrolled')
                                <button id="generate-coe-btn" type="button"
                                    class="group relative overflow-hidden bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white rounded-xl p-4 transition-all duration-300 hover:shadow-lg hover:shadow-emerald-500/25 hover:-translate-y-1">
                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="flex-shrink-0 w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center group-hover:bg-white/30 transition-colors">
                                            <i class="fi fi-sr-file-pdf flex justify-center items-center text-lg"></i>
                                        </div>
                                        <div class="text-left">
                                            <p class="font-semibold text-sm">Generate COE</p>
                                            <p class="text-xs text-emerald-100">Certificate of Enrollment</p>
                                        </div>
                                    </div>
                                </button>
                            @endif
                        @endhasanyrole

                        @hasanyrole('registrar|super_admin')
                            <!-- Generate SIS -->
                            @if ($student->status === 'Officially Enrolled')
                                <button
                                    class="group relative overflow-hidden bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white rounded-xl p-4 transition-all duration-300 hover:shadow-lg hover:shadow-purple-500/25 hover:-translate-y-1">
                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="flex-shrink-0 w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center group-hover:bg-white/30 transition-colors">
                                            <i class="fi fi-rr-document flex justify-center items-center text-lg"></i>
                                        </div>
                                        <div class="text-left">
                                            <p class="font-semibold text-sm">Generate SIS</p>
                                            <p class="text-xs text-purple-100">Student Information Sheet</p>
                                        </div>
                                    </div>
                                </button>
                            @endif
                        @endhasanyrole


                        <!-- Update Academic Status -->
                        @if ($student->status === 'Officially Enrolled' && $student->academic_status === null)
                            <button id="evaluate-student-btn"
                                class="group relative overflow-hidden bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white rounded-xl p-4 transition-all duration-300 hover:shadow-lg hover:shadow-indigo-500/25 hover:-translate-y-1">
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="flex-shrink-0 w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center group-hover:bg-white/30 transition-colors">
                                        <i class="fi fi-rr-graduation-cap flex justify-center items-center text-lg"></i>
                                    </div>
                                    <div class="text-left">
                                        <p class="font-semibold text-sm">Academic Status</p>
                                        <p class="text-xs text-indigo-100">Pass/Fail evaluation</p>
                                    </div>
                                </div>
                            </button>
                        @else
                            <button disabled
                                class="group relative overflow-hidden bg-gradient-to-r from-gray-400 to-gray-500 cursor-not-allowed text-white rounded-xl p-4 transition-all duration-300 ">
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="flex-shrink-0 w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center group-hover:bg-white/30 transition-colors">
                                        <i class="fi fi-rr-graduation-cap flex justify-center items-center text-lg"></i>
                                    </div>
                                    <div class="text-left">
                                        <p class="font-semibold text-sm">Academic Status</p>
                                        <p class="text-xs text-gray-100">Pass/Fail evaluation</p>
                                    </div>
                                </div>
                            </button>
                        @endif

                        @hasanyrole('registrar|super_admin')
                            <!-- Promote Student -->
                            @if ($student->status === 'Officially Enrolled')
                                <button id="promote-student-btn"
                                    class="group relative overflow-hidden bg-gradient-to-r from-teal-600 to-teal-700 hover:from-teal-700 hover:to-teal-800 text-white rounded-xl p-4 transition-all duration-300 hover:shadow-lg hover:shadow-teal-500/25 hover:-translate-y-1">
                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="flex-shrink-0 w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center group-hover:bg-white/30 transition-colors">
                                            <i class="fi fi-rr-arrow-up flex justify-center items-center text-lg"></i>
                                        </div>
                                        <div class="text-left">
                                            <p class="font-semibold text-sm">Promote Student</p>
                                            <p class="text-xs text-teal-100">Advance to next level</p>
                                        </div>
                                    </div>
                                </button>
                            @endif
                        @endhasanyrole
                        @hasanyrole('registrar|super_admin')
                            <!-- Withdraw Enrollment -->
                            @if ($student->status === 'Officially Enrolled')
                                <button id="withdraw-btn"
                                    class="group relative overflow-hidden bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white rounded-xl p-4 transition-all duration-300 hover:shadow-lg hover:shadow-red-500/25 hover:-translate-y-1">
                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="flex-shrink-0 w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center group-hover:bg-white/30 transition-colors">
                                            <i class="fi fi-rr-delete-user flex justify-center items-center text-lg"></i>
                                        </div>
                                        <div class="text-left">
                                            <p class="font-semibold text-sm">Withdraw</p>
                                            <p class="text-xs text-red-100">Remove enrollment</p>
                                        </div>
                                    </div>
                                </button>
                            @endif
                        @endhasanyrole
                    </div>
                </div>

                @hasanyrole('registrar|super_admin')
                    <!-- Documents & Requirements Section -->
                    <div class="w-full">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h2 class="text-xl font-bold text-gray-900">Documents & Requirements</h2>
                                <p class="text-sm text-gray-600 mt-1">Track student document submissions and status</p>
                            </div>
                            @if (!$assignedDocuments)
                                <button
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                    <i class="fi fi-rr-plus mr-2"></i>
                                    Assign Documents
                                </button>
                            @endif
                        </div>

                        <!-- Documents Table -->
                        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                            <div class="overflow-x-auto">
                                <table id="enrolledStudents" class="w-full">
                                    <thead class="bg-gray-50 border-b border-gray-200">
                                        <tr>
                                            <th
                                                class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-16">
                                                #
                                            </th>
                                            <th
                                                class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                Document Name
                                            </th>
                                            <th
                                                class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider w-32">
                                                Status
                                            </th>
                                            <th
                                                class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider w-40">
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse ($assignedDocuments as $doc)
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <!-- Index -->
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $loop->iteration }}
                                                </td>

                                                <!-- Document Name -->
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div
                                                            class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                                            <i class="fi fi-rr-file text-blue-600 text-sm"></i>
                                                        </div>
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $doc->documents->type }}</div>
                                                    </div>
                                                </td>

                                                <!-- Status -->
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    @php
                                                        $statusColors = [
                                                            'submitted' => 'bg-green-100 text-green-800',
                                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                                            'missing' => 'bg-red-100 text-red-800',
                                                            'reviewed' => 'bg-blue-100 text-blue-800',
                                                        ];
                                                        $statusColor =
                                                            $statusColors[strtolower($doc->status)] ??
                                                            'bg-gray-100 text-gray-800';
                                                    @endphp
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                                                        {{ ucfirst($doc->status) }}
                                                    </span>
                                                </td>

                                                <!-- Action -->
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    @if ($doc->latest_submission)
                                                        <a href="{{ asset('storage/' . $doc->latest_submission->file_path) }}"
                                                            target="_blank"
                                                            class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-lg transition-colors">
                                                            <i class="fi fi-rr-eye mr-1.5"></i>
                                                            View PDF
                                                        </a>
                                                    @else
                                                        <button
                                                            class="inline-flex items-center px-3 py-1.5 bg-orange-600 hover:bg-orange-700 text-white text-xs font-medium rounded-lg transition-colors">
                                                            <i class="fi fi-rr-bell mr-1.5"></i>
                                                            Notify
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="px-6 py-12 text-center">
                                                    <div class="flex flex-col items-center">
                                                        <div
                                                            class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                                            <i class="fi fi-rr-document text-gray-400 text-2xl"></i>
                                                        </div>
                                                        <h3 class="text-lg font-medium text-gray-900 mb-2">No documents
                                                            found</h3>
                                                        <p class="text-sm text-gray-500 mb-4">This student was either imported
                                                            or got promoted without submitting any documents.</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endhasanyrole




            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="module">
        import {
            initModal
        } from "/js/modal.js";

        import {
            showAlert
        } from "/js/alert.js";

        document.addEventListener("DOMContentLoaded", function() {

            // Handle flash messages from server
            @if (session('success'))
                showAlert('success', '{{ session('success') }}');
            @endif

            @if ($errors->any())
                showAlert('error', '{{ $errors->first() }}');
            @endif

            let editStudentBtn = document.querySelector("#edit-info-btn");
            let editBtns = document.querySelectorAll(".edit-btn");
            let openCoeBtn = document.querySelector('#generate-coe-btn');

            // Initialize COE modal
            initModal('generate-coe-modal', 'generate-coe-btn', 'generate-coe-close-btn', 'generate-coe-cancel-btn',
                'modal-container-coe');

            initModal('evaluate-student', 'evaluate-student-btn', 'evaluate-student-close-btn',
                'evaluate-student-cancel-btn',
                'modal-container-evaluate-student');

            initModal('promote-student', 'promote-student-btn', 'promote-student-close-btn',
                'promote-student-cancel-btn',
                'modal-container-promote-student');

            // Initialize edit modals
            initModal('edit-personal-info-modal', 'edit-personal-info-btn', 'edit-personal-info-close-btn',
                'edit-personal-info-cancel-btn', 'modal-container-personal-info');

            initModal('edit-academic-info-modal', 'edit-academic-info-btn', 'edit-academic-info-close-btn',
                'edit-academic-info-cancel-btn', 'modal-container-academic-info');

            initModal('edit-address-info-modal', 'edit-address-info-btn', 'edit-address-info-close-btn',
                'edit-address-info-cancel-btn', 'modal-container-address-info');

            initModal('edit-emergency-info-modal', 'edit-emergency-info-btn', 'edit-emergency-info-close-btn',
                'edit-emergency-info-cancel-btn', 'modal-container-emergency-info');

            initModal('withdraw-student', 'withdraw-btn', 'withdraw-student-close-btn',
                'withdraw-student-cancel-btn', 'modal-container-withdraw-student');

            // Populate forms with current data when edit buttons are clicked
            document.getElementById('edit-personal-info-btn').addEventListener('click', function() {
                // Populate personal info form
                document.getElementById('first_name').value = '{{ $student->user->first_name ?? '' }}';
                document.getElementById('last_name').value = '{{ $student->user->last_name ?? '' }}';
                document.getElementById('middle_name').value = '{{ $student->record->middle_name ?? '' }}';
                document.getElementById('extension_name').value =
                    '{{ $student->record->extension_name ?? '' }}';
                document.getElementById('birthdate').value = '{{ $student->record->birthdate ?? '' }}';
                document.getElementById('place_of_birth').value =
                    '{{ $student->record->place_of_birth ?? '' }}';
            });

            document.getElementById('edit-academic-info-btn').addEventListener('click', function() {
                // Populate academic info form
                document.getElementById('lrn').value = '{{ $student->lrn ?? '' }}';
                document.getElementById('grade_level').value = '{{ $student->grade_level ?? '' }}';
                document.getElementById('program_id').value = '{{ $student->program_id ?? '' }}';
                document.getElementById('section').value = '{{ $student->section ?? '' }}';
                document.getElementById('acad_term_applied').value =
                    '{{ $student->record->acad_term_applied ?? '' }}';
                document.getElementById('semester_applied').value =
                    '{{ $student->record->semester_applied ?? '' }}';
            });

            document.getElementById('edit-address-info-btn').addEventListener('click', function() {
                // Populate address info form
                document.getElementById('house_no').value = '{{ $student->record->house_no ?? '' }}';
                document.getElementById('street').value = '{{ $student->record->street ?? '' }}';
                document.getElementById('barangay').value = '{{ $student->record->barangay ?? '' }}';
                document.getElementById('city').value = '{{ $student->record->city ?? '' }}';
                document.getElementById('country').value = '{{ $student->record->country ?? '' }}';
                document.getElementById('zip_code').value = '{{ $student->record->zip_code ?? '' }}';
            });

            document.getElementById('edit-emergency-info-btn').addEventListener('click', function() {
                // Populate emergency contact form
                document.getElementById('contact_number').value =
                    '{{ $student->record->contact_number ?? '' }}';
                document.getElementById('guardian_name').value =
                    '{{ $student->record->guardian_name ?? '' }}';
                document.getElementById('guardian_contact_number').value =
                    '{{ $student->record->guardian_contact_number ?? '' }}';
            });

            if (editStudentBtn) {
                editStudentBtn.addEventListener('click', () => {

                    editBtns.forEach(element => {

                        element.classList.toggle('opacity-100');
                        element.classList.toggle('pointer-events-none');
                        element.classList.toggle('scale-90');

                    });


                })
            }



            // Handle edit form submissions
            // Personal Information Form
            document.getElementById('edit-personal-info-form').addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                formData.append('_method', 'PUT');

                fetch(`/students/{{ $student->id }}/personal-info`, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showAlert('success', 'Personal information updated successfully!');
                            // Close modal
                            document.getElementById('edit-personal-info-close-btn').click();
                            // Reload page to show updated data
                            setTimeout(() => window.location.reload(), 1000);
                        } else {
                            showAlert('error', data.message || 'Failed to update personal information');
                        }
                    })
                    .catch(error => {
                        showAlert('error', 'An error occurred while updating personal information');
                    });
            });

            // Academic Information Form
            document.getElementById('edit-academic-info-form').addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                formData.append('_method', 'PUT');

                fetch(`/students/{{ $student->id }}/academic-info`, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showAlert('success', 'Academic information updated successfully!');
                            // Close modal
                            document.getElementById('edit-academic-info-close-btn').click();
                            // Reload page to show updated data
                            setTimeout(() => window.location.reload(), 1000);
                        } else {
                            showAlert('error', data.message || 'Failed to update academic information');
                        }
                    })
                    .catch(error => {
                        showAlert('error', 'An error occurred while updating academic information');
                    });
            });

            // Address Information Form
            document.getElementById('edit-address-info-form').addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                formData.append('_method', 'PUT');

                fetch(`/students/{{ $student->id }}/address-info`, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showAlert('success', 'Address information updated successfully!');
                            // Close modal
                            document.getElementById('edit-address-info-close-btn').click();
                            // Reload page to show updated data
                            setTimeout(() => window.location.reload(), 1000);
                        } else {
                            showAlert('error', data.message || 'Failed to update address information');
                        }
                    })
                    .catch(error => {
                        showAlert('error', 'An error occurred while updating address information');
                    });
            });

            // Emergency Contact Form
            document.getElementById('edit-emergency-info-form').addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                formData.append('_method', 'PUT');

                fetch(`/students/{{ $student->id }}/emergency-info`, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showAlert('success', 'Emergency contact information updated successfully!');
                            // Close modal
                            document.getElementById('edit-emergency-info-close-btn').click();
                            // Reload page to show updated data
                            setTimeout(() => window.location.reload(), 1000);
                        } else {
                            showAlert('error', data.message ||
                                'Failed to update emergency contact information');
                        }
                    })
                    .catch(error => {
                        showAlert('error',
                            'An error occurred while updating emergency contact information');
                    });
            });

        });
    </script>
@endpush
