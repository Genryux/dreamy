@extends('layouts.admin')

@section('breadcrumbs')
    <nav aria-label="Breadcrumb" class="mb-4 mt-2">
        <ol class="flex items-center gap-1 text-sm text-gray-700">
            <li class="rtl:rotate-180 border border-gray-300 bg-gray-100 p-2 rounded-lg mr-1">
                <a href="/applications/accepted" class="block transition-colors hover:text-gray-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 rotate-180" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                </a>
            </li>
            <li>
                <a href="/applications/accepted" class="block transition-colors hover:text-gray-500 text-gray-400">
                    Applications
                </a>
            </li>

            <li class="rtl:rotate-180">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 opacity-60" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
            </li>

            <li>
                <a class="block transition-colors hover:text-gray-500 text-gray-500"> Accepted Applications
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
                <a href="#" class="block transition-colors hover:text-gray-900"> Admission Details </a>
            </li>
        </ol>
    </nav>
@endsection

@section('modal')
    <x-modal modal_id="sched-admission-modal" modal_name="Schedule Admission Exam" close_btn_id="sched-admission-close-btn"
        modal_container_id="modal-container-1">

        <form id="admission-form" class="p-6">
            @csrf
            <input type="hidden" name="action" value="schedule-admission">
            <div class="space-y-6">
                <!-- Date and Time Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Date -->
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fi fi-rr-calendar mr-2"></i>
                            Admission Exam Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="date" id="date" required
                            class="w-full border-2 border-gray-300 bg-gray-100 rounded-lg px-3 py-2 outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 transition duration-200 placeholder:italic placeholder:text-[14px] text-[14px]">
                    </div>

                    <!-- Time -->
                    <div>
                        <label for="time" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fi fi-rr-clock mr-2"></i>
                            Admission Exam Time <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="time" id="time" required
                            class="w-full border-2 border-gray-300 bg-gray-100 rounded-lg px-3 py-2 outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 transition duration-200 placeholder:italic placeholder:text-[14px] text-[14px]">
                    </div>
                </div>

                <!-- Location -->
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-marker mr-2"></i>
                        Admission Location <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="location" id="location" required
                        placeholder="e.g., Main Office, Conference Room A"
                        class="w-full border-2 border-gray-300 bg-gray-100 rounded-lg px-3 py-2 outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 transition duration-200 placeholder:italic placeholder:text-[14px] text-[14px]">
                </div>

                <!-- Person to look for -->
                <div>
                    <label for="contact_person" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-user mr-2"></i>
                        Contact Person
                    </label>
                    <select name="contact_person" id="contact_person"
                        class="w-full border-2 border-gray-300 bg-gray-100 rounded-lg px-3 py-2 outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 transition duration-200 placeholder:italic placeholder:text-[14px] text-[14px]">
                        <option value="" disabled>Select contact person (optional)</option>
                        @forelse (\App\Models\Teacher::with(['user', 'program'])->where('status', 'active')->get() as $teacher)
                            <option value="{{ $teacher->id }}">
                                {{ $teacher->getFullNameAttribute() }}
                                @if ($teacher->program)
                                    - {{ $teacher->program->name }}
                                @endif
                            </option>
                        @empty
                            <option value="" disabled>Not teacher was found.</option>
                        @endforelse
                    </select>
                </div>

                <!-- Additional Information -->
                <div>
                    <label for="add_info" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-info mr-2"></i>
                        Additional Information
                    </label>
                    <textarea name="add_info" id="add_info" rows="4"
                        placeholder="Any additional notes or instructions for the admission exam..."
                        class="w-full border-2 border-gray-300 bg-gray-100 rounded-lg px-3 py-2 outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 transition duration-200 placeholder:italic placeholder:text-[14px] text-[14px] resize-none"></textarea>
                </div>
            </div>

        </form>

        <x-slot name="modal_buttons">

            <button id="cancel-btn"
                class="border border-[#1e1e1e]/15 text-[14px] bg-gray-50 px-3 py-2 rounded-xl text-[#0f111c]/80 hover:ring hover:ring-gray-200 hover:bg-gray-100 font-semibold">
                Cancel
            </button>
            <button form="admission-form" type="submit"
                class="bg-[#199BCF] py-2 px-3 rounded-xl text-[14px] font-semibold gap-2 text-white hover:ring hover:ring-[#C8A165]/20 hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                Confirm
            </button>

        </x-slot>
    </x-modal>
    {{-- Record Interview Result Modal --}}
    <x-modal modal_id="record-interview-modal" modal_name="Record Interview Result"
        close_btn_id="record-interview-close-btn" modal_container_id='modal-container-2'>

        <form class="py-6 px-6" id="record-result-form">
            @csrf
            
            <!-- Result Selection Section -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Admission Exam Result</h3>
                
                <div class="space-y-3">
                    <!-- Passed Option -->
                    <label for="passed" class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-green-300 transition-all duration-200 group has-[:checked]:border-green-500 has-[:checked]:bg-green-50">
                        <div class="flex items-center">
                            <div class="relative">
                                <input type="radio" name="result" id="passed" value="Exam-Passed" checked class="sr-only">
                                <div class="w-4 h-4 border-2 border-gray-300 rounded-full flex items-center justify-center group-has-[:checked]:border-green-500 group-has-[:checked]:bg-green-500 transition-all duration-200">
                                    <div class="w-2 h-2 bg-white rounded-full opacity-0 group-has-[:checked]:opacity-100 transition-opacity duration-200"></div>
                                </div>
                            </div>
                            <div class="ml-3">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                    <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900">Passed</span>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Applicant has successfully passed the interview</p>
                            </div>
                        </div>
                    </label>

                    <!-- Failed Option -->
                    <label for="failed" class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-red-300 transition-all duration-200 group has-[:checked]:border-red-500 has-[:checked]:bg-red-50">
                        <div class="flex items-center">
                            <div class="relative">
                                <input type="radio" name="result" id="failed" value="Exam-Failed" class="sr-only">
                                <div class="w-4 h-4 border-2 border-gray-300 rounded-full flex items-center justify-center group-has-[:checked]:border-red-500 group-has-[:checked]:bg-red-500 transition-all duration-200">
                                    <div class="w-2 h-2 bg-white rounded-full opacity-0 group-has-[:checked]:opacity-100 transition-opacity duration-200"></div>
                                </div>
                            </div>
                            <div class="ml-3">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                                    <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900">Failed</span>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Applicant did not meet the interview requirements</p>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Conditional Section for Passed Result -->
            <div id="passed-options" class="space-y-4 transition-all duration-300 ease-in-out">
                <!-- Due Date Section -->
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <label for="due-date" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar-alt mr-2 text-gray-500"></i>Document Submission Deadline
                    </label>
                    <input type="date" name="due-date" id="due-date" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-400 focus:border-transparent text-sm bg-white">
                    <p class="text-xs text-gray-500 mt-1">Set the deadline for document submission</p>
                </div>

                <!-- Information Message -->
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-gray-500 mt-0.5"></i>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-gray-700">Automatic Document Assignment</h4>
                            <p class="text-sm text-gray-600 mt-1">
                                By selecting 'Passed', standard document requirements will be automatically assigned to this applicant. 
                                The applicant will receive notifications about the required documents and submission deadline.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <x-slot name="modal_buttons">
            <button type="button" id="cancel-btn"
                class="border border-[#1e1e1e]/15 text-[14px] bg-gray-50 px-3 py-2 rounded-xl text-[#0f111c]/80 hover:ring hover:ring-gray-200 hover:bg-gray-100 font-semibold">
                Cancel
            </button>
            <button type="submit" form="record-result-form"
                class="bg-[#199BCF] py-2 px-3 rounded-xl text-[14px] font-semibold gap-2 text-white hover:ring hover:ring-[#C8A165]/20 hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate"></i>Confirm Result
            </button>
        </x-slot>

    </x-modal>
    {{-- Edit Interview Schedule Modal --}}
    <x-modal modal_id="edit-sched-modal" modal_name="Edit Interview Schedule" close_btn_id="edit-sched-close-btn"
        modal_container_id='modal-container-3'>

        <form id="edit-schedule-form" class="p-6">
            @csrf
            <input type="hidden" name="action" value="edit-admission">
            <div class="space-y-6">
                <!-- Date and Time Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Date -->
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fi fi-rr-calendar mr-2"></i>
                            Admission Exam Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="date" id="date" required
                            class="w-full border-2 border-gray-300 bg-gray-100 rounded-lg px-3 py-2 outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 transition duration-200 placeholder:italic placeholder:text-[14px] text-[14px]">
                    </div>

                    <!-- Time -->
                    <div>
                        <label for="time" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fi fi-rr-clock mr-2"></i>
                            Admission Exam Time <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="time" id="time" required
                            class="w-full border-2 border-gray-300 bg-gray-100 rounded-lg px-3 py-2 outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 transition duration-200 placeholder:italic placeholder:text-[14px] text-[14px]">
                    </div>
                </div>

                <!-- Location -->
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-marker mr-2"></i>
                        Admission Location <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="location" id="location" required
                        placeholder="e.g., Main Office, Conference Room A"
                        class="w-full border-2 border-gray-300 bg-gray-100 rounded-lg px-3 py-2 outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 transition duration-200 placeholder:italic placeholder:text-[14px] text-[14px]">
                </div>

                <!-- Person to look for -->
                <div>
                    <label for="contact_person" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-user mr-2"></i>
                        Contact Person
                    </label>
                    <select name="contact_person" id="contact_person"
                        class="w-full border-2 border-gray-300 bg-gray-100 rounded-lg px-3 py-2 outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 transition duration-200 placeholder:italic placeholder:text-[14px] text-[14px]">
                        <option value="" disabled>Select contact person (optional)</option>
                        @forelse (\App\Models\Teacher::with(['user', 'program'])->where('status', 'active')->get() as $teacher)
                            <option value="{{ $teacher->id }}">
                                {{ $teacher->getFullNameAttribute() }}
                                @if ($teacher->program)
                                    - {{ $teacher->program->name }}
                                @endif
                            </option>
                        @empty
                            <option value="" disabled>Not teacher was found.</option>
                        @endforelse
                    </select>
                </div>

                <!-- Additional Information -->
                <div>
                    <label for="add_info" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-info mr-2"></i>
                        Additional Information
                    </label>
                    <textarea name="add_info" id="add_info" rows="4"
                        placeholder="Any additional notes or instructions for the admission exam..."
                        class="w-full border-2 border-gray-300 bg-gray-100 rounded-lg px-3 py-2 outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 transition duration-200 placeholder:italic placeholder:text-[14px] text-[14px] resize-none"></textarea>
                </div>
            </div>

        </form>

        <x-slot name="modal_buttons">
            <button id="cancel-btn"
                class="border border-[#1e1e1e]/15 text-[14px] bg-gray-50 px-3 py-2 rounded-xl text-[#0f111c]/80 hover:ring hover:ring-gray-200 hover:bg-gray-100 font-semibold">
                Cancel
            </button>
            <button form="edit-schedule-form"
                class="bg-[#199BCF] py-2 px-3 rounded-xl text-[14px] font-semibold gap-2 text-white hover:ring hover:ring-[#C8A165]/20 hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                Confirm
            </button>
        </x-slot>

    </x-modal>
    {{-- Update status for tracking modal --}}
    <x-modal modal_id="update-status-modal" modal_name="Update Status" close_btn_id="update-status-modal-close-btn"
        modal_container_id="modal-container-4">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-check flex justify-center items-center text-green-500'></i>
        </x-slot>

        <form id="update-status-form" class="p-6">
            @csrf
            <input type="hidden" name="action" value="update-status">

            <div class="space-y-6">
                <!-- Confirmation Message -->
                <div class="text-center">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Confirm Update</h3>
                    <p class="text-sm text-gray-500 mb-4">
                        Are you sure you want to update this status? You should only proceed if the applicant is about to
                        take the admission exam for tracking purposes.
                    </p>
                </div>
            </div>
        </form>

        <x-slot name="modal_buttons">
            <button id="cancel-update-status-btn"
                class="bg-gray-50 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-100 hover:ring hover:ring-gray-200 transition duration-150">
                Cancel
            </button>
            {{-- This button will act as the submit button --}}
            <button type="submit" form="update-status-form"
                class="self-end flex flex-row justify-center items-center bg-green-500 py-2 px-3 rounded-xl text-[14px] font-semibold gap-2 text-white hover:bg-green-600 hover:scale-95 transition duration-200 shadow-green-500/20 hover:shadow-green-600/20 shadow-lg truncate">
                Accept Application
            </button>
        </x-slot>

    </x-modal>
@endsection

@section('header')
    <div class="flex flex-col justify-center items-start text-start px-[14px] py-2">
        <h1 class="text-[20px] font-black">Admission Details</h1>
        <p class="text-[14px]  text-gray-900/60">Manage approved applicants, set schedules, and record
            results.</p>
    </div>
@endsection

@section('content')
    <x-alert />
    <div class="flex flex-col bg-[#f8f8f8] rounded-xl shadow-sm border border-[#1e1e1e]/10 p-2 text-[14px]">
        <!-- Professional Applicant Information Card -->
        <div class="overflow-hidden rounded-xl">
            <!-- Header Section -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-8 py-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <!-- Applicant Profile -->
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <div
                                class="w-20 h-20 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                                <span class="text-2xl font-bold text-white">
                                    {{ strtoupper(substr($applicant->first_name, 0, 1) . substr($applicant->last_name, 0, 1)) }}
                                </span>
                            </div>
                            <!-- Status Indicator -->
                            <div
                                class="absolute -bottom-1 -right-1 w-6 h-6 rounded-full border-2 border-white flex items-center justify-center
                                @if ($applicant->application_status === 'Accepted') bg-green-500
                                @elseif($applicant->application_status === 'Pending') bg-yellow-500
                                @elseif($applicant->application_status === 'Rejected') bg-red-500
                                @else bg-gray-400 @endif">
                                <div class="w-2 h-2 bg-white rounded-full"></div>
                            </div>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-1">{{ $applicant->getFullNameAttribute() }}</h2>
                            <div class="flex items-center space-x-1">
                                <span class="text-sm text-gray-500">Applicant ID:</span>
                                <span
                                    class="text-sm font-mono font-semibold text-gray-700 bg-gray-100 px-1 py-1 rounded">{{ $applicant->applicant_id }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center space-x-3">
                        @if (!$interview_details->status || $interview_details->status === null)
                            <button id="record-btn"
                                class="inline-flex items-center px-6 py-3 bg-[#199BCF] text-white font-semibold rounded-xl hover:bg-[#C8A165] hover:ring hover:ring-[#C8A165]/40 hover:scale-95 transition-all duration-200 shadow-[#199BCF]/20 shadow-xl hover:shadow-[#C8A165]/20">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                Schedule Admission
                            </button>
                        @elseif ($interview_details->status === 'Scheduled')
                            <div class="flex flex-col items-center space-y-1">
                                <p class="self-end text-gray-400 pr-2 text-[12px]">Status will change to: Taking-Exam</p>

                                <div class="flex flex-row justify-center items-center gap-2">
                                    <button id="edit-sched-btn"
                                        class="inline-flex items-center px-4 py-2.5 bg-white text-gray-700 font-semibold rounded-xl border border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                        Edit
                                    </button>
                                    <button id="update-status-btn"
                                        class="inline-flex items-center px-3 py-2.5 bg-green-600 text-white font-semibold rounded-xl outline-none hover:ring hover:ring-green-200 hover:scale-95 focus:ring-green-500 transition-all duration-200 shadow-lg shadow-green-600/20">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Update status for tracking
                                    </button>
                                </div>

                            </div>
                        @elseif (isset($interview_details->status) && $interview_details->status === 'Taking-Exam')
                            <div class="flex items-center space-x-3">

                                <button id="record-interview-btn"
                                    class="inline-flex items-center px-6 py-3 bg-[#199BCF] text-white font-semibold rounded-xl hover:bg-[#C8A165] hover:scale-95 hover:ring hover:ring-[#C8A165]/20 transition duration-200 shadow-xl">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Record Result
                                </button>
                            </div>
                        @else
                            <span class="p-6 border border-yellow-300 bg-yellow-50 rounded-xl text-yellow-600">
                                Application already processed.
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Information Grid -->
            <div class="p-4 space-y-2">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- Academic Information -->
                    <div class="space-y-4 p-6 hover:shadow-xl hover:-translate-y-1 transition duration-200 rounded-xl">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">Academic Information
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Grade Level</p>
                                <p class="text-[16px] font-semibold text-gray-900">
                                    {{ $applicant->applicationForm->grade_level ?? 'Not specified' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Primary Track</p>
                                <p class="text-[16px] font-semibold text-gray-900">
                                    {{ $applicant->track->name ?? 'Not specified' }}</p>
                            </div>
                            @if ($applicant->program->code)
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Secondary Track</p>
                                    <p class="text-[16px] font-semibold text-gray-900">
                                        {{ $applicant->program->code }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="space-y-4 p-6 hover:shadow-xl hover:-translate-y-1 transition duration-200 rounded-xl">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">Contact Information
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Phone Number</p>
                                <p class="text-[16px] font-semibold text-gray-900">
                                    {{ $applicant->applicationForm->contact_number ?? 'Not provided' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Email</p>
                                <p class="text-[16px] font-semibold text-gray-900">
                                    {{ $applicant->user->email ?? 'Not provided' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Application Date</p>
                                <p class="text-[16px] font-semibold text-gray-900">
                                    {{ $applicant->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Interview Details -->
                    <div class="space-y-4 p-6 hover:shadow-xl hover:-translate-y-1 transition duration-200 rounded-xl">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">Admission Exam Details
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Admission Exam Date</p>
                                <p class="text-[16px] font-semibold text-gray-900">
                                    @if ($interview_details->date)
                                        {{ \Carbon\Carbon::parse($interview_details->date)->format('M d, Y') }}
                                    @else
                                        Not scheduled
                                    @endif
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Admission Exam Time</p>
                                <p class="text-[16px] font-semibold text-gray-900">
                                    @if ($interview_details->time)
                                        {{ \Carbon\Carbon::parse($interview_details->time)->format('g:i A') }}
                                    @else
                                        Not scheduled
                                    @endif
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Location</p>
                                <p class="text-[16px] font-semibold text-gray-900">
                                    {{ $interview_details->location ?? 'Not specified' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Interview Status -->
                    <div class="space-y-4 p-6 hover:shadow-xl hover:-translate-y-1 transition duration-200 rounded-xl">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">Admission Exam Status
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Contact Person</p>
                                <p class="text-[16px] font-semibold text-gray-900">
                                    {{ $interview_details->interviewer_name ?? 'Not assigned' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Status</p>
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @if ($interview_details->status === 'Completed') bg-green-100 text-green-800
                                    @elseif($interview_details->status === 'Scheduled') bg-blue-100 text-blue-800
                                    @elseif($interview_details->status === 'Ongoing-Interview') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ $interview_details->status ?? 'Pending' }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Recorded by</p>
                                <p class="text-[16px] font-semibold text-gray-900">
                                    {{ $interview_details->recorded_by ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($interview_details->remarks)
                    <div class="border border-blue-300 bg-blue-50 rounded-xl px-4 py-2 space-y-1">
                        <p class="text-[16px] font-medium text-gray-900">Remarks</p>
                        <p class="italic">“{{ $interview_details->remarks ?? '-' }}”</p>
                    </div>
                @endif

            </div>
        </div>
        <div
            class="flex flex-row items-center justify-between px-[14px] pb-4 text-[14px] font-medium transition duration-150">
            <button id="show-details-btn"
                class="flex flex-row gap-2 border border-[#1e1e1e]/15 rounded-md px-2 py-1 text-[#0f111c]/80 ">View
                Applicant's Full Details <i
                    class="fi fi-rs-angle-small-down flex flex-row items-center text-[18px] text-[#0f111c]/80"></i></button>
        </div>
        <div id="details-container" class="hidden space-y-3 bg-[#f8f8f8] rounded-xl px-4 py-2">
            <div
                class=" border border-[#1e1e1e]/15 rounded-[8px] hover:shadow-xl hover:border-[#199BCF]/50 transition duration-200">
                <table class="text-[#0f111c] w-full">
                    <thead class="">
                        <tr class="">
                            <th class="px-6 py-2 bg-[#E3ECFF] text-start rounded-tl-[8px]">Learner Information</th>
                            <th class="bg-[#E3ECFF] text-start rounded-tr-[8px]"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-t border-[#1e1e1e]/15 opacity-[0.87]">
                            <td class="px-6 py-2 text-[14px]">Returning (Balik-Aral):</td>
                        </tr>
                        <tr class="opacity-[0.87]">
                            <td class="px-6 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2 text-bold">With
                                LRN:<span class="font-bold"> Yes</span></td>
                            <td class="px-6 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2 text-bold">LRN: <span
                                    class="font-bold">{{ $applicant->applicationForm->lrn ?? '-' }}</span></td>
                        </tr>
                        <tr class="opacity-[0.87]">
                            <td class="px-6 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Grade Level to
                                Enroll:<span class="font-bold">
                                    {{ $applicant->applicationForm->grade_level ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Semester:<span
                                    class="font-bold"> {{ $applicant->applicationForm->semester_applied ?? '-' }}</span>
                            </td>
                        </tr>
                        <tr class="opacity-[0.87]">
                            <td class="px-6 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Primary
                                Track:<span class="font-bold"> {{ $applicant->track->name ?? '-' }}</span></td>
                            <td class="px-6 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Secondary Track:<span
                                    class="font-bold">
                                    {{ $applicant->program->code . ' - ' . $applicant->program->name ?? '-' }}</span></td>
                        </tr>
                        <tr class="opacity-[0.87]">
                            <td class="px-6 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Last Name:<span
                                    class="font-bold"> {{ $applicant->applicationForm->first_name ?? '-' }}</span></td>
                            <td class="px-6 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">First Name:<span
                                    class="font-bold"> {{ $applicant->applicationForm->last_name ?? '-' }}</span></td>
                        </tr>
                        <tr class="opacity-[0.87]">
                            <td class="px-6 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Middle Name:<span
                                    class="font-bold"> {{ $applicant->applicationForm->middle_name ?? '-' }}</span></td>
                            <td class="px-6 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Extension Name:<span
                                    class="font-bold"> {{ $applicant->applicationForm->extension_name ?? '-' }}</span>
                            </td>
                        </tr>
                        <tr class="opacity-[0.87]">
                            <td class="px-6 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Birthdate:<span
                                    class="font-bold">
                                    {{ \Carbon\Carbon::parse($applicant->applicationForm->birthdate)->timezone('Asia/Manila')->format('M. d, Y') ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Age:<span
                                    class="font-bold">
                                    {{ $applicant->applicationForm->age ?? '-' }}</span></td>
                        </tr>
                        <tr class="opacity-[0.87]">
                            <td class="px-6 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Place of
                                Birth:<span class="font-bold">
                                    {{ $applicant->applicationForm->place_of_birth ?? '-' }}</span></td>
                            <td class="px-6 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Mother Tongue:<span
                                    class="font-bold"> {{ $applicant->applicationForm->mother_tongue ?? '-' }}</span></td>
                        </tr>
                        <tr class="opacity-[0.87]">
                            <td class="px-6 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Belong to any IP
                                community:<span class="font-bold">
                                    {{ $applicant->applicationForm->belongs_to_ip === 1 ? 'Yes' : 'No' }}</span></td>
                            <td class="px-6 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Beneficiary of 4Ps:<span
                                    class="font-bold">
                                    {{ $applicant->applicationForm->is_4ps_beneficiary === 1 ? 'Yes' : 'No' }}</span>
                            </td>
                        </tr>
                        <tr class="opacity-[0.87]">
                            <td class="px-6 py-2 text-[14px] border-r border-[#1e1e1e]/15">Learner with disability: <span
                                    class="font-bold">
                                    {{ $applicant->applicationForm->has_special_needs === 1 ? 'Yes' : 'No' }}</span></td>
                            <td class="px-6 py-2 text-[14px]">Special needs: <span class="font-bold">
                                    {{ implode(', ', $applicant->applicationForm->special_needs ?? []) }}</span></td>
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
                                class="border-r border-[#1e1e1e]/15 px-6 py-2 bg-[#E3ECFF] text-start rounded-tl-[8px] text-[16px]">
                                Current Address</th>
                            <th class="px-6 py-2 bg-[#E3ECFF] text-start rounded-tr-[8px] text-[16px]">Permanent Address
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="opacity-[0.87]">
                            <td class="px-6 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2 text-bold">House
                                No:<span class="font-bold"> {{ $applicant->applicationForm->cur_house_no ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">House No:
                                <span class="font-bold"> {{ $applicant->applicationForm->perm_house_no ?? '-' }}</span>
                            </td>
                        </tr>
                        <tr class="opacity-[0.87]">
                            <td class="px-6 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Sitio/Street
                                Name:
                                <span class="font-bold"> {{ $applicant->applicationForm->cur_street ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Sitio/Street
                                Name:<span class="font-bold"> {{ $applicant->applicationForm->perm_street ?? '-' }}</span>
                            </td>
                        </tr>
                        <tr class="opacity-[0.87]">
                            <td class="px-6 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Barangay:<span
                                    class="font-bold"> {{ $applicant->applicationForm->cur_barangay ?? '-' }}</span></td>
                            <td class="px-6 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Barangay: <span
                                    class="font-bold"> {{ $applicant->applicationForm->perm_barangay ?? '-' }}</span></td>
                        </tr>
                        <tr class="opacity-[0.87]">
                            <td class="px-6 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">
                                Municipality/City:<span class="font-bold">
                                    {{ $applicant->applicationForm->cur_city ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Municipality/City:<span
                                    class="font-bold"> {{ $applicant->applicationForm->perm_city ?? '-' }}</span></td>
                        </tr>
                        <tr class="opacity-[0.87]">
                            <td class="px-6 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Country:<span
                                    class="font-bold"> {{ $applicant->applicationForm->cur_country ?? '-' }}</span></td>
                            <td class="px-6 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Country:<span
                                    class="font-bold"> {{ $applicant->applicationForm->perm_country ?? '-' }}</span></td>
                        </tr>
                        <tr class="opacity-[0.87]">
                            <td class="px-6 py-2 text-[14px] border-r border-[#1e1e1e]/15 w-1/2">Zip Code: <span
                                    class="font-bold"> {{ $applicant->applicationForm->cur_zip_code ?? '-' }}</span></td>
                            <td class="px-6 py-2 text-[14px] w-1/2">Zip Code: <span class="font-bold">
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
                            <th
                                class="border-b border-[#1e1e1e]/15 px-6 py-2 bg-[#E3ECFF] text-start rounded-tl-[8px] text-[16px]">
                                Parent/Guardian's Information</th>
                            <th class="border-b border-[#1e1e1e]/15 px-6 py-2 bg-[#E3ECFF] text-start text-[16px]"></th>
                            <th
                                class="border-b border-[#1e1e1e]/15 px-6 py-2 bg-[#E3ECFF] text-start rounded-tr-[8px] text-[16px]">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="opacity-[0.87]">
                            <td class="px-6 py-2 text-[16px] border-b border-r border-[#1e1e1e]/15 font-bold">Mother's
                                Information:</td>
                            <td class="px-6 py-2 text-[16px] border-b border-r border-[#1e1e1e]/15 font-bold">Father's
                                Information:<span class="font-bold"></span></td>
                            <td class="px-6 py-2 text-[16px] border-b border-[#1e1e1e]/15 font-bold">Guardian's
                                Information:<span class="font-bold"></span></td>
                        </tr>
                        <tr class="opacity-[0.87]">
                            <td class="px-6 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Last Name:<span
                                    class="font-bold"> {{ $applicant->applicationForm->mother_last_name ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Last Name:<span
                                    class="font-bold"> {{ $applicant->applicationForm->father_last_name ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Last Name:<span
                                    class="font-bold"> {{ $applicant->applicationForm->guardian_last_name ?? '-' }}</span>
                            </td>
                        </tr>
                        <tr class="opacity-[0.87]">
                            <td class="px-6 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">First Name:<span
                                    class="font-bold"> {{ $applicant->applicationForm->mother_first_name ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">First Name:<span
                                    class="font-bold"> {{ $applicant->applicationForm->father_first_name ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">First Name:<span
                                    class="font-bold">
                                    {{ $applicant->applicationForm->guardian_first_name ?? '-' }}</span>
                            </td>
                        </tr>
                        <tr class="opacity-[0.87]">
                            <td class="px-6 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Middle Name:<span
                                    class="font-bold"> {{ $applicant->applicationForm->mother_middle_name ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Middle Name:<span
                                    class="font-bold"> {{ $applicant->applicationForm->father_middle_name ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Middle Name:<span
                                    class="font-bold">
                                    {{ $applicant->applicationForm->guardian_middle_name ?? '-' }}</span>
                            </td>
                        </tr>
                        <tr class="opacity-[0.87]">
                            <td class="px-6 py-2 text-[14px] border-r border-[#1e1e1e]/15 w-1/2">Contact Number:<span
                                    class="font-bold">
                                    {{ $applicant->applicationForm->mother_contact_number ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-2 text-[14px] border-r border-[#1e1e1e]/15 w-1/2">Contact Number:<span
                                    class="font-bold">
                                    {{ $applicant->applicationForm->father_contact_number ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-2 text-[14px] w-1/2">Contact Number:<span class="font-bold">
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
                                class="border-b border-[#1e1e1e]/15 px-6 py-2 bg-[#E3ECFF] text-start rounded-tl-[8px] text-[16px]">
                                Other Informations </th>
                            <th
                                class="border-b border-[#1e1e1e]/15 px-6 py-2 bg-[#E3ECFF] text-start rounded-tr-[8px] text-[16px]">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="opacity-[0.87]">
                            <td class="px-6 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2 text-bold">Preferred Class
                                Schedule:<span class="font-bold">
                                    {{ $applicant->applicationForm->preferred_sched ?? '-' }}</span></td>
                            <td class="px-6 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2 text-bold"></td>
                        </tr>
                        <tr class="opacity-[0.87]">
                            <td class="px-6 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2 text-bold">Last Grade Level
                                Completed:<span class="font-bold">
                                    {{ $applicant->applicationForm->last_grade_level_completed ?? '-' }}</span></td>
                            <td class="px-6 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2 text-bold"></td>
                        </tr>
                        <tr class="opacity-[0.87]">
                            <td class="px-6 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2 text-bold">Lat School
                                Attended:<span class="font-bold">
                                    {{ $applicant->applicationForm->last_school_attended ?? '-' }}</span></td>
                            <td class="px-6 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2 text-bold"></td>
                        </tr>
                        <tr class="opacity-[0.87]">
                            <td class="px-6 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2 text-bold">Last School Year
                                Completed:<span class="font-bold">
                                    {{ \Carbon\Carbon::parse($applicant->applicationForm->last_school_year_completed)->timezone('Asia/Manila')->format('M. d, Y') ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2 text-bold"></td>
                        </tr>
                        <tr class="opacity-[0.87]">
                            <td class="px-6 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2 text-bold">School Id:<span
                                    class="font-bold">
                                    {{ $applicant->applicationForm->school_id ?? '-' }}</span></td>
                            <td class="px-6 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2 text-bold"></td>
                        </tr>
                        <tr class="opacity-[0.87]">
                            <td class="px-6 py-2 text-[14px] w-1/2">Date Applied:<span class="font-bold">
                                    {{ \Carbon\Carbon::parse($applicant->applicationForm->admission_date)->timezone('Asia/Manila')->format('M. d, Y — g:i A') ?? '-' }}</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
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
            showLoader,
            hideLoader
        } from "/js/loader.js";
        import {
            showAlert
        } from "/js/alert.js";

        const applicantId = @json($applicant->id);
        const interviewId = @json($interview_details->id ?? null);
        console.log('Interview ID:', interviewId);
        console.log('Applicant ID:', applicantId);



        document.addEventListener("DOMContentLoaded", function() {
            initModal('update-status-modal', 'update-status-btn', 'update-status-close-btn', 'cancel-btn',
                'modal-container-4');
            initModal('edit-sched-modal', 'edit-sched-btn', 'edit-sched-close-btn', 'cancel-btn',
                'modal-container-3');
            initModal('record-interview-modal', 'record-interview-btn', 'record-interview-close-btn', 'cancel-btn',
                'modal-container-2');
            initModal('sched-admission-modal', 'record-btn', 'sched-admission-close-btn', 'cancel-btn',
                'modal-container-1');
            const detailsContainer = document.getElementById('details-container');
            const showDetailsBtn = document.getElementById('show-details-btn');

            const admissionForm = document.getElementById('admission-form');
            if (admissionForm) {
                admissionForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    let form = e.target;
                    let formData = new FormData(form);

                    // Show loader
                    showLoader("Scheduling admission exam...");

                    fetch(`/schedule-admission/${applicantId}`, {
                            method: "POST",
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            },
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            hideLoader();

                            if (data.success) {
                                // Reset form
                                form.reset();

                                // Close modal
                                closeModal('schedule-admission-modal', 'modal-container-1');

                                // Show success alert
                                showAlert('success', data.message);

                                // Reload page to show updated data
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1000);
                                console.log('Success:', data.message);
                            } else {
                                console.error('Error:', data.message);
                                closeModal('schedule-admission-modal', 'modal-container-1');
                                showAlert('error', data.message);
                            }
                        })
                        .catch(err => {
                            hideLoader();
                            console.error('Error:', err);
                            closeModal('schedule-admission-modal', 'modal-container-1');
                            showAlert('error',
                                'Something went wrong while scheduling the admission exam');
                        });
                });
            }

            const editAdmissionForm = document.getElementById('edit-schedule-form');
            if (editAdmissionForm) {
                editAdmissionForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    let form = e.target;
                    let formData = new FormData(form);

                    // Show loader
                    showLoader("Updating admission schedule...");

                    fetch(`/schedule-admission/${applicantId}`, {
                            method: "POST",
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            },
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            hideLoader();

                            if (data.success) {
                                // Reset form
                                form.reset();

                                // Close modal
                                closeModal('edit-schedule-modal', 'modal-container-3');

                                // Show success alert
                                showAlert('success', data.message);

                                // Reload page to show updated data
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1000);
                                console.log('Success:', data.message);
                            } else {
                                console.error('Error:', data.message);
                                closeModal('edit-schedule-modal', 'modal-container-3');
                                showAlert('error', data.message);
                            }
                        })
                        .catch(err => {
                            hideLoader();
                            console.error('Error:', err);
                            closeModal('schedule-admission-modal', 'modal-container-3');
                            showAlert('error',
                                'Something went wrong while scheduling the admission exam');
                        });
                });
            }

            const recordResult = document.getElementById('record-result-form');
            if (recordResult) {
                recordResult.addEventListener('submit', function(e) {
                    e.preventDefault();

                    let form = e.target;
                    let formData = new FormData(form);


                    // Show loader
                    showLoader("Recording admission result...");

                    // Add method override for PUT
                    formData.append('_method', 'PUT');

                    fetch(`/record-admission-result/${applicantId}`, {
                            method: "POST",
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            },
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            hideLoader();

                            if (data.success) {
                                // Reset form
                                form.reset();

                                // Close modal
                                closeModal('record-interview-modal', 'modal-container-2');

                                // Show success alert
                                showAlert('success', data.message);

                                // Reload page to show updated data
                                setTimeout(() => {
                                    window.location.href = '/applications/accepted';
                                }, 1500);
                                console.log('Success:', data.message);
                            } else {
                                console.error('Error:', data.message);
                                closeModal('record-interview-modal', 'modal-container-2');
                                showAlert('error', data.message);
                            }
                        })
                        .catch(err => {
                            hideLoader();
                            console.error('Error:', err);
                            closeModal('record-interview-modal', 'modal-container-2');
                            showAlert('error',
                                err);
                        });
                });
            }

            const updateStatusForm = document.getElementById('update-status-form');
            if (updateStatusForm) {
                updateStatusForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    let form = e.target;
                    let formData = new FormData(form);

                    // Show loader
                    showLoader("Updating status...");

                    fetch(`/schedule-admission/${applicantId}`, {
                            method: "POST",
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            },
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            hideLoader();

                            if (data.success) {
                                // Reset form
                                form.reset();

                                // Close modal
                                closeModal('update-status-modal', 'modal-container-4');

                                // Show success alert
                                showAlert('success', data.message ||
                                    'Application accepted successfully!');

                                // Reload page to show updated data
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1000);

                            } else {
                                console.error('Error:', data.message);
                                closeModal('update-status-modal', 'modal-container-4');
                                showAlert('error', data.message);
                            }
                        })
                        .catch(err => {
                            hideLoader();
                            console.error('Error:', err);
                            closeModal('update-status-modal', 'modal-container-4');
                            showAlert('error', 'Something went wrong while accepting the application');
                        });
                });
            }

            if (showDetailsBtn && detailsContainer) {
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
            }

            function closeModal(modalId, modalContainerId) {
                let modal = document.querySelector(`#${modalId}`)
                let body = document.querySelector(`#${modalContainerId}`);

                if (modal && body) {
                    modal.classList.remove('opacity-100', 'scale-100');
                    modal.classList.add('opacity-0', 'pointer-events-none', 'scale-95');
                    body.classList.remove('opacity-100');
                    body.classList.add('opacity-0', 'pointer-events-none');
                }
            }

        });
        // Interview Result Modal - Conditional Visibility
        document.addEventListener('DOMContentLoaded', function() {
            const passedRadio = document.getElementById('passed');
            const failedRadio = document.getElementById('failed');
            const passedOptions = document.getElementById('passed-options');
            
            // Function to toggle visibility
            function togglePassedOptions() {
                if (passedRadio.checked) {
                    passedOptions.style.display = 'block';
                    passedOptions.style.opacity = '1';
                    passedOptions.style.maxHeight = '500px';
                } else {
                    passedOptions.style.display = 'none';
                    passedOptions.style.opacity = '0';
                    passedOptions.style.maxHeight = '0';
                }
            }
            
            // Initial state
            togglePassedOptions();
            
            // Add event listeners
            passedRadio.addEventListener('change', togglePassedOptions);
            failedRadio.addEventListener('change', togglePassedOptions);
            
            // Set default due date to 7 days from now
            const dueDateInput = document.getElementById('due-date');
            if (dueDateInput) {
                const today = new Date();
                const nextWeek = new Date(today.getTime() + 7 * 24 * 60 * 60 * 1000);
                dueDateInput.value = nextWeek.toISOString().split('T')[0];
            }
        });

    </script>
@endpush
