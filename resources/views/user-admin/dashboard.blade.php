@extends('layouts.admin')

@section('modal')
    {{-- academic term modal --}}
    <x-modal modal_id="acad-term-modal" modal_name="Add New Academic Term" close_btn_id="at-close-btn"
        modal_container_id="modal-container-1">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-calendar-day flex justify-center items-center'></i>
        </x-slot>

        <form action="/academic-terms" method="POST" id="academic-term-form" class="p-6">
            @csrf
            <div class="space-y-4">
                <!-- Period and Semester Row -->
                <div class="flex flex-row gap-4">
                    <div class="flex-1 flex flex-col">
                        <label for="year" class="text-sm font-medium text-gray-700 mb-2">Academic Year</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fi fi-rr-calendar-day text-gray-400"></i>
                            </div>
                            <input type="text" name="year" id="year" placeholder="2024-2025"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg bg-white text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#199BCF]/20 focus:border-[#199BCF] transition duration-150"
                                required>
                        </div>
                    </div>
                    <div class="flex-1 flex flex-col">
                        <label for="semester" class="text-sm font-medium text-gray-700 mb-2">Semester</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fi fi-rr-clock-five text-gray-400"></i>
                            </div>
                            <select name="semester" id="semester"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-2 focus:ring-[#199BCF]/20 focus:border-[#199BCF] transition duration-150"
                                required>
                                <option value="">Select Semester</option>
                                <option value="1st Semester">1st Semester</option>
                                <option value="2nd Semester">2nd Semester</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Start and End Date Row -->
                <div class="flex flex-row gap-4">
                    <div class="flex-1 flex flex-col">
                        <label for="start_date" class="text-sm font-medium text-gray-700 mb-2">Start Date</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fi fi-rr-calendar-check text-gray-400"></i>
                            </div>
                            <input type="date" name="start_date" id="start_date"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-2 focus:ring-[#199BCF]/20 focus:border-[#199BCF] transition duration-150"
                                required>
                        </div>
                    </div>
                    <div class="flex-1 flex flex-col">
                        <label for="end_date" class="text-sm font-medium text-gray-700 mb-2">End Date</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fi fi-rr-calendar-xmark text-gray-400"></i>
                            </div>
                            <input type="date" name="end_date" id="end_date"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-2 focus:ring-[#199BCF]/20 focus:border-[#199BCF] transition duration-150"
                                required>
                        </div>
                    </div>
                </div>

                <!-- Active Status -->
                <div class="flex flex-col">
                    <label for="is_active" class="text-sm font-medium text-gray-700 mb-2">Set as Active Term?</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fi fi-rr-settings text-gray-400"></i>
                        </div>
                        <select name="is_active" id="is_active"
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-2 focus:ring-[#199BCF]/20 focus:border-[#199BCF] transition duration-150"
                            required>
                            <option value="">Select Status</option>
                            <option value="1">Yes - Set as Active</option>
                            <option value="0">No - Keep Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
        </form>

        <x-slot name="modal_buttons">
            <button id="cancel-btn"
                class="bg-gray-50 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-100 hover:ring hover:ring-gray-200 transition duration-150">
                Cancel
            </button>
            <button type="submit" form="academic-term-form"
                class="self-center flex flex-row justify-center items-center bg-[#199BCF] py-2 px-3 rounded-xl text-[14px] font-semibold gap-2 text-white hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                Create Term
            </button>
        </x-slot>
    </x-modal>

    {{-- Edit academic term modal --}}
    <x-modal modal_id="edit-acad-term-modal" modal_name="Edit Academic Term" close_btn_id="edit-at-close-btn"
        modal_container_id="modal-container-4">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-edit flex justify-center items-center'></i>
        </x-slot>

        <form id="edit-academic-term-form" method="POST" class="p-6">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <!-- Period and Semester Row -->
                <div class="flex flex-row gap-4">
                    <div class="flex-1 flex flex-col">
                        <label for="edit_year" class="text-sm font-medium text-gray-700 mb-2">Academic Year</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fi fi-rr-calendar-day text-gray-400"></i>
                            </div>
                            <input type="text" name="year" id="edit_year" placeholder="2024-2025"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg bg-white text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#199BCF]/20 focus:border-[#199BCF] transition duration-150"
                                required>
                        </div>
                    </div>
                    <div class="flex-1 flex flex-col">
                        <label for="edit_semester" class="text-sm font-medium text-gray-700 mb-2">Semester</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fi fi-rr-clock-five text-gray-400"></i>
                            </div>
                            <select name="semester" id="edit_semester"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-2 focus:ring-[#199BCF]/20 focus:border-[#199BCF] transition duration-150"
                                required>
                                <option value="">Select Semester</option>
                                <option value="1st Semester">1st Semester</option>
                                <option value="2nd Semester">2nd Semester</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Start and End Date Row -->
                <div class="flex flex-row gap-4">
                    <div class="flex-1 flex flex-col">
                        <label for="edit_start_date" class="text-sm font-medium text-gray-700 mb-2">Start Date</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fi fi-rr-calendar-check text-gray-400"></i>
                            </div>
                            <input type="date" name="start_date" id="edit_start_date"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-2 focus:ring-[#199BCF]/20 focus:border-[#199BCF] transition duration-150"
                                required>
                        </div>
                    </div>
                    <div class="flex-1 flex flex-col">
                        <label for="edit_end_date" class="text-sm font-medium text-gray-700 mb-2">End Date</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fi fi-rr-calendar-xmark text-gray-400"></i>
                            </div>
                            <input type="date" name="end_date" id="edit_end_date"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-2 focus:ring-[#199BCF]/20 focus:border-[#199BCF] transition duration-150"
                                required>
                        </div>
                    </div>
                </div>

                <!-- Active Status -->
                <div class="flex flex-col">
                    <label for="edit_is_active" class="text-sm font-medium text-gray-700 mb-2">Set as Active Term?</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fi fi-rr-settings text-gray-400"></i>
                        </div>
                        <select name="is_active" id="edit_is_active"
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-2 focus:ring-[#199BCF]/20 focus:border-[#199BCF] transition duration-150"
                            required>
                            <option value="">Select Status</option>
                            <option value="1">Yes - Set as Active</option>
                            <option value="0">No - Keep Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
        </form>

        <x-slot name="modal_buttons">
            <button id="edit-cancel-btn"
                class="bg-gray-50 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-100 hover:ring hover:ring-gray-200 transition duration-150">
                Cancel
            </button>
            <button type="submit" form="edit-academic-term-form"
                class="self-center flex flex-row justify-center items-center bg-green-500 py-2 px-3 rounded-xl text-[14px] font-semibold gap-2 text-white hover:bg-green-600 hover:scale-95 transition duration-200 shadow-green-500/20 hover:shadow-green-600/20 shadow-lg truncate">
                Update Term
            </button>
        </x-slot>
    </x-modal>

    {{-- enrollment period modal --}}
    <x-modal modal_id="enrollment-period-modal" modal_name="Add Enrollment Period" close_btn_id="ep-close-btn"
        modal_container_id="modal-container-2">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-user-add flex justify-center items-center'></i>
        </x-slot>

        <form action="/enrollment-period" method="POST" id="enrollment-period-form" class="p-6">
            @csrf
            <input type="hidden" name="academic_terms_id" value="{{ $currentAcadTerm->id ?? '' }}">
            <div class="space-y-4">
                <!-- Name and Max Applicants Row -->
                <div class="flex flex-row gap-4">
                    <div class="flex-1 flex flex-col">
                        <label for="name" class="text-sm font-medium text-gray-700 mb-2">Period Name</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fi fi-rr-calendar-day text-gray-400"></i>
                            </div>
                            <input type="text" name="name" id="name"
                                placeholder="Early Registration, Regular, etc."
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg bg-white text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#199BCF]/20 focus:border-[#199BCF] transition duration-150"
                                required>
                        </div>
                    </div>
                    <div class="flex-1 flex flex-col">
                        <label for="max_applicants" class="text-sm font-medium text-gray-700 mb-2">Max Applicants</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fi fi-rr-users text-gray-400"></i>
                            </div>
                            <input type="number" name="max_applicants" id="max_applicants"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-2 focus:ring-[#199BCF]/20 focus:border-[#199BCF] transition duration-150"
                                required>
                        </div>
                    </div>
                </div>

                <!-- Start and End Date Row -->
                <div class="flex flex-row gap-4">
                    <div class="flex-1 flex flex-col">
                        <label for="start_date" class="text-sm font-medium text-gray-700 mb-2">Application Start
                            Date</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fi fi-rr-calendar-check text-gray-400"></i>
                            </div>
                            <input type="date" name="application_start_date" id="start_date"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-2 focus:ring-[#199BCF]/20 focus:border-[#199BCF] transition duration-150"
                                required>
                        </div>
                    </div>
                    <div class="flex-1 flex flex-col">
                        <label for="end_date" class="text-sm font-medium text-gray-700 mb-2">Application End Date</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fi fi-rr-calendar-xmark text-gray-400"></i>
                            </div>
                            <input type="date" name="application_end_date" id="end_date"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-2 focus:ring-[#199BCF]/20 focus:border-[#199BCF] transition duration-150"
                                required>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <x-slot name="modal_buttons">
            <button id="ep-cancel-btn"
                class="bg-gray-50 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-100 hover:ring hover:ring-gray-200 transition duration-150">
                Cancel
            </button>
            <button type="submit" form="enrollment-period-form"
                class="self-center flex flex-row justify-center items-center bg-[#199BCF] py-2 px-3 rounded-xl text-[14px] font-semibold gap-2 text-white hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                Create Period
            </button>
        </x-slot>
    </x-modal>
    @if ($activeEnrollmentPeriod)
        <x-modal modal_id="end-enrollment-modal" modal_name="End Enrollment Period"
            close_btn_id="end-enrollment-close-btn" modal_container_id="modal-container-3">
            <x-slot name="modal_icon">
                <i class='fi fi-rr-exclamation flex justify-center items-center text-red-500'></i>
            </x-slot>

            <form action="/enrollment-period/{{ $activeEnrollmentPeriod->id }}" method="POST" id="end-enrollment-form"
                class="p-6">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" id="ep-status" value="Closed">

                <div class="space-y-4">
                    <div class="text-center">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Confirm End Enrollment Period</h3>
                        <p class="text-gray-600 mb-4">Are you sure you want to end the current enrollment period?</p>
                    </div>

                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fi fi-rr-exclamation-triangle text-yellow-400"></i>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-sm font-medium text-yellow-800">Important Notice</h4>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <ul class="list-disc list-inside space-y-1">
                                        <li>Ensure all applications have been reviewed</li>
                                        <li>No pending or unprocessed submissions remain</li>
                                        <li>This action will prevent further applications</li>
                                        <li>Ongoing applications may be affected</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <x-slot name="modal_buttons">
                <button id="end-enrollment-cancel-btn"
                    class="bg-gray-50 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-100 hover:ring hover:ring-gray-200 transition duration-150">
                    Cancel
                </button>
                <button type="submit" form="end-enrollment-form" id="end-enrollment-period-confirmation"
                    data-id="{{ $activeEnrollmentPeriod->id }}"
                    class="self-center flex flex-row justify-center items-center bg-red-500 py-2 px-3 rounded-xl text-[14px] font-semibold gap-2 text-white hover:bg-red-600 hover:scale-95 transition duration-200 shadow-red-500/20 hover:shadow-red-600/20 shadow-lg truncate">
                    End Period
                </button>
            </x-slot>
        </x-modal>
    @endif
    {{-- end enrollment period modal --}}
@endsection

@section('dashboard-acad-term')
    <div class="flex flex-col justify-center items-start text-start px-[14px] py-2">
        <h1 class="text-[20px] font-black">Dashboard</h1>
        <p class="text-[14px]  text-gray-900/60">Monitor and manage application activity for the current academic term.
        </p>
    </div>
@endsection

@section('header')
    <x-header-container>
        {{-- <div class="flex flex-row items-center space-x-2 px-[14px] py-[10px]">
        <i class="fi fi-rs-chart-simple text-[20px]"></i>
        <p class="text-[16px] md:text-[18px] font-bold">Dashboard</p>
        </div>
        <span class="flex items-center">  
        <span class="h-px flex-1 bg-[#1e1e1e]/15 dark:bg-[#1e1e1e]/15"></span>
    </span>
      
    <div class="flex flex-row space-x-2 px-[14px] py-[14px]">
        <x-total-stat-card
            card_title="Total Application"
            color="#1A73E8"
            class="border-[#1A73E8] bg-[#E7F0FD]"
            text_color="#1A73E8"
        >
            <x-slot name="card_icon">
                <i class="fi fi-ss-pending flex flex-row items-center text-[16px] text-[#1A73E8]"></i>
            </x-slot>
            {{ 0 }}
        </x-total-stat-card>
        <x-total-stat-card
            card_title="Selected Application"
            color="#34A853"
            class="border-[#34A853] bg-[#E6F4EA]"
            text_color="#34A853"
        >
            <x-slot name="card_icon">
                <i class="fi fi-ss-check-circle flex flex-row items-center text-[16px] text-[#34A853]"></i>
            </x-slot>
            {{ 0 }}
        </x-total-stat-card>
        <x-total-stat-card
            card_title="Pending Application"
            color="#FBBC04"
            class="border-[#FBBC04] bg-[#FFF4E5]"
            text_color="#FBBC04"
        >
            <x-slot name="card_icon">
                <i class="fi fi-ss-pending flex flex-row items-center text-[16px] text-[#FBBC04]"></i>
            </x-slot>
            {{ $pendingApplicationsCount ?? '0' }}
        </x-total-stat-card>
        <x-total-stat-card
            card_title="Rejected Application"
            color="#EA4335"
            class="border-[#EA4335] bg-[#FCE8E6]"
            text_color="#EA4335"
        >
            <x-slot name="card_icon">
                <i class="fi fi-ss-cross-circle flex flex-row items-center text-[16px] text-[#EA4335]"></i>
            </x-slot>
            {{ 0 }}
        </x-total-stat-card>


    </div>            --}}

        <div class="flex flex-row items-center justify-between space-x-2 p-6">
            <div class="flex flex-row items-center space-x-1">
                @if(@isset($currentAcadTerm->id))
                <button id="edit-acad-term-btn"
                    class="flex flex-col justify-center items-start text-blue-500 hover:text-blue-400 ease-in-out duration-150 cursor-pointer"
                    data-term-id="{{ $currentAcadTerm->id }}"
                    data-year="{{ $currentAcadTerm->year }}"
                    data-semester="{{ $currentAcadTerm->semester }}"
                    data-start-date="{{ $currentAcadTerm->start_date }}"
                    data-end-date="{{ $currentAcadTerm->end_date }}"
                    data-is-active="{{ $currentAcadTerm->is_active }}"
                    title="Click to edit academic term">

                    @if ($currentAcadTerm->year === null)
                        <span class="font-bold text-[18px] ">No active academic year yet.</span>
                    @else
                        <span class="font-bold text-[18px] ">Academic Year {{ $currentAcadTerm->year }}</span>
                        <span class="text-[14px] font-medium opacity-80">{{ $currentAcadTerm->semester }}</span>
                    @endif
                </button>
                @else
                <button
                    class="flex flex-col justify-center items-start text-blue-500 hover:text-blue-400 ease-in-out duration-150">

                    <span class="font-bold text-[18px] ">No academic year found, please create first.</span>
                </button>
                @endif
            </div>
            <div>
                <button id="acad-term-btn"
                    class="self-center flex flex-row justify-center items-center bg-[#199BCF] py-2.5 px-3 rounded-xl text-[16px] font-semibold gap-2 text-white hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                    <i class="fi fi-sr-square-plus flex justify-center opacity-70 items-center text-[18px] "></i>
                    Add new term</button>
            </div>
        </div>
    </x-header-container>
@endsection

@section('stat')

    <div class="flex flex-row space-x-4">
        <div class="bg-[#f8f8f8] flex-1 rounded-xl p-6 shadow-sm border border-[#1e1e1e]/10">
            <div class="flex flex-row items-center justify-between pb-3 border-b border-[#1e1e1e]/10">
                <span class="font-bold text-[16px] text-gray-800">Active Enrollment Period</span>
                @if ($activeEnrollmentPeriod)
                    @if ($activeEnrollmentPeriod->status == 'Ongoing')
                        <span id="status-span"
                            class="text-[12px] md:text-[14px] font-bold text-[#34A853] bg-[#E6F4EA] px-2 py-1 rounded-full">Ongoing</span>
                    @elseif ($activeEnrollmentPeriod->status == 'Paused')
                        <span id="status-span"
                            class="text-[12px] md:text-[14px] font-bold text-[#EA4335] bg-[#FCE8E6] px-2 py-1 rounded-full">Paused</span>
                    @endif
                @endif
            </div>

            @if ($activeEnrollmentPeriod)
                <div id="ep-details"
                    class="{{ $activeEnrollmentPeriod->status == 'Paused' ? 'opacity-30' : 'opacity-100' }}">
                @else
                    <div id="ep-details">
            @endif
            @if ($activeEnrollmentPeriod)
                <div class="flex flex-row py-4 justify-between items-center">
                    <div class="flex flex-col">
                        <span class="font-semibold text-[14px] text-gray-700">{{ $activeEnrollmentPeriod->name }}</span>
                        <span
                            class="font-medium text-[12px] text-gray-500">{{ $activeEnrollmentPeriod->academicTerms->full_name }}</span>
                    </div>
                    <div>
                        @if ($activeEnrollmentPeriod)
                            <span class="text-[14px] font-bold">
                                {{-- toggle --}}
                                <label for="toggleEnrollmentPeriod"
                                    class="relative inline-flex items-center cursor-pointer select-none">
                                    <input type="checkbox" id="toggleEnrollmentPeriod" class="sr-only peer"
                                        @if ($activeEnrollmentPeriod->status == 'Ongoing') checked 
                                    value="Paused"
                                @elseif ($activeEnrollmentPeriod->status == 'Paused')
                                    value="Ongoing" @endif>
                                    <div
                                        class="w-12 h-6 bg-[#EA4335]/70 peer-checked:bg-[#34A853]/70 rounded-full transition-colors duration-200">
                                    </div>
                                    <span
                                        class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform duration-200 peer-checked:translate-x-6"></span>
                                </label>
                            </span>
                        @endif
                    </div>
                </div>
            @endif
            @if ($activeEnrollmentPeriod)
                <div class="flex flex-row gap-2 py-4">
                    <div
                        class="flex flex-row gap-3 items-center bg-white/70 backdrop-blur-sm border border-[#34A853]/20 rounded-xl px-5 py-3">
                        <div class="bg-[#E6F4EA] px-3 py-2 rounded-full">
                            <i class="fi fi-sr-calendar-check text-[22px] text-[#34A853]"></i>
                        </div>
                        <div class="flex flex-col min-w-0">
                            <span
                                class="font-bold whitespace-nowrap">{{ \Carbon\Carbon::parse($activeEnrollmentPeriod->application_start_date)->format('F d') }}</span>
                            <span class="text-[13px] md:text-[14px] opacity-60">Start Date</span>
                        </div>
                    </div>

                    <div class="hidden md:flex items-center justify-center w-1/2">
                        <i class="fi fi-rs-arrow-right text-[24px] opacity-40"></i>
                    </div>

                    <div
                        class="flex flex-row gap-3 items-center bg-white/70 backdrop-blur-sm border border-[#EA4335]/20 rounded-xl px-5 py-3">
                        <div class="bg-[#FCE8E6] px-3 py-2 rounded-full">
                            <i class="fi fi-sr-calendar-xmark text-[22px] text-[#EA4335]"></i>
                        </div>
                        <div class="flex flex-col min-w-0">
                            <span
                                class="font-bold whitespace-nowrap">{{ \Carbon\Carbon::parse($activeEnrollmentPeriod->application_end_date)->format('F d') }}</span>
                            <span class="text-[13px] md:text-[14px] opacity-60">End Date</span>
                        </div>
                    </div>
                </div>
            @elseif (!$activeEnrollmentPeriod)
                <div class="flex flex-row justify-evenly py-2">

                    <div class="flex flex-col justify-center items-center space-y-3">
                        <img src="{{ asset('images/empty-box.png') }}" alt="empty-box" class="size-[120px]">
                        <span class="text-gray-500 text-[14px]">There is currently no active enrollment period</span>
                        @if ($currentAcadTerm)
                            <button id="enrollment-period-btn"
                                class="flex flex-row justify-center items-center bg-[#199BCF]/10 border border-[#199BCF]/20  text-[#199BCF] hover:text-white px-3 py-2.5 font-bold text-[14px] rounded-xl gap-2 hover:bg-[#199BCF] hover:border-[#199BCF] shadow-[#199BCF]/40 hover:shadow-xl transition duration-200">
                                <i
                                    class="fi fi-sr-square-plus flex justify-center opacity-70 items-center text-[18px] "></i>
                                Add Enrollment Period
                            </button>
                        @endif

                    </div>

                </div>
            @endif
        </div>
        @if ($activeEnrollmentPeriod)
            <div class="flex flex-row items-center justify-between pt-3">
                <span id="ep-time"
                    data-end="{{ \Carbon\Carbon::parse($activeEnrollmentPeriod->application_end_date)->toIso8601String() }}"
                    data-status="{{ $activeEnrollmentPeriod->status }}"
                    class="{{ $activeEnrollmentPeriod->status == 'Paused' ? 'opacity-30' : 'opacity-100' }} text-[14px] text-gray-600 md:text-[15px]">Time
                    Remaining:
                    <span id="ep-time-value" class="opacity-100 font-bold">
                        @php
                            $remainingDays = max(
                                0,
                                \Carbon\Carbon::parse($activeEnrollmentPeriod->application_end_date)->diffInDays(
                                    \Carbon\Carbon::now(),
                                ),
                            );
                            echo $remainingDays . ' ' . Str::plural('Day', $remainingDays);
                        @endphp
                    </span>
                </span>
                <div>
                    <button id="end-enrollment-btn"
                        class="bg-red-100 text-red-500 px-3 py-1 rounded-xl text-[14px] font-semibold hover:bg-red-500 hover:text-white hover:ring hover:ring-red-200 ease-in-out duration-150">End
                        Period
                    </button>
                </div>
            </div>
        @endif
    </div>
    {{-- Application overview --}}
    <div class="bg-[#f8f8f8] flex-1 rounded-xl p-6 space-y-6 shadow-sm border border-[#1e1e1e]/10">
        <div class="flex flex-row items-center justify-between">
            <span class="font-bold text-[16px] text-gray-800">Application Overview</span>
        </div>

        @php
            $maxApplicants = $activeEnrollmentPeriod->max_applicants ?? 0;
            $currentCount = $applicationCount ?? 0;
            $percent = $maxApplicants > 0 ? min(100, round(($currentCount / $maxApplicants) * 100)) : 0;
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- KPI card --}}
            <div class="flex flex-col justify-between bg-[#E3ECFF]/40 rounded-xl p-4">
                <div class="flex flex-col items-center justify-center py-4">
                    <span id="total-application"
                        class="text-[36px] md:text-[40px] font-extrabold">{{ $currentCount }}<span
                            class="text-[18px] md:text-[20px] opacity-60">/{{ $maxApplicants ?: '-' }}</span></span>
                    <span class="font-medium text-gray-600">Total Applications</span>
                </div>
                <div class="space-y-2">
                    <div class="w-full">
                        <div class="bg-[#d9d9d9] h-2 rounded-full w-full overflow-hidden">
                            <div class="bg-blue-500 h-2" style="width: {{ $percent }}%"></div>
                        </div>
                    </div>
                    <div class="text-[13px] md:text-[12px] text-gray-500 text-center">{{ $percent }}% of max
                        applications</div>
                </div>
            </div>

            {{-- Stat tiles --}}
            <div class="md:col-span-2 grid grid-cols-2 gap-3">
                <div
                    class="flex flex-col justify-center items-center bg-yellow-50 backdrop-blur-sm gap-2 p-2 rounded-xl border border-yellow-200">
                    <p class="font-semibold text-[14px] text-yellow-600">Pending</p>
                    <p class="font-bold text-[28px] text-yellow-600">
                        {{ $pendingApplicationsCount ?? '0' }}
                    </p>
                </div>

                <div
                    class="flex flex-col justify-center items-center bg-green-50 backdrop-blur-sm gap-2 p-2 rounded-xl border border-green-200">
                    <p class="font-semibold text-[14px] text-green-600">Accepted</p>
                    <p class="font-bold text-[28px] text-green-600">
                        {{ $selectedApplicationsCount ?? '0' }}
                    </p>
                </div>

                <div
                    class="flex flex-col justify-center items-center bg-orange-50 backdrop-blur-sm gap-2 p-2 rounded-xl border border-orange-200">
                    <p class="font-semibold text-[14px] text-orange-500">Pending-Document</p>
                    <p class="font-bold text-[28px] text-orange-500">
                        {{ $pendingDocumentsApplicationsCount ?? '0' }}
                    </p>
                </div>

                <div
                    class="flex flex-col justify-center items-center bg-blue-50 backdrop-blur-sm gap-2 p-2 rounded-xl border border-blue-200">
                    <p class="font-semibold text-[14px] text-blue-500">Enrolled</p>
                    <p class="font-bold text-[28px] text-blue-500">
                        {{ $enrolledApplicationsCount ?? '0' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('content')
    @error('year')
        <div class="text-red-500 text-[14px] font-bold">{{ $message }}</div>
    @enderror
    @error('semester')
        <div class="text-red-500 text-[14px] font-bold">{{ $message }}</div>
    @enderror
    @error('start_date')
        <div class="text-red-500 text-[14px] font-bold">{{ $message }}</div>
    @enderror
    @error('end_date')
        <div class="text-red-500 text-[14px] font-bold">{{ $message }}</div>
    @enderror
    @error('is_active')
        <div class="text-red-500 text-[14px] font-bold">{{ $message }}</div>
    @enderror
    @error('error')
        <div class="text-red-500 text-[14px] font-bold">{{ $message }}</div>
    @enderror
    @if (session('error'))
        <div class="text-red-500 text-[14px] font-bold">{{ session('error') }}</div>
    @endif


    <div class="flex flex-col justify-center items-start gap-4">

        <div class="flex flex-row w-full h-auto gap-4">
            <!-- Program Analytics Section -->
            <div class="flex flex-col w-[70%] h-auto bg-white rounded-xl border shadow-sm border-[#1e1e1e]/10 p-6 gap-4">
                <div class="flex flex-row justify-between items-center">
                    <h3 class="text-[16px] font-semibold text-gray-800">Enrollment Breakdown</h3>
                    <div class="flex items-center gap-2">
                        <span class="text-[12px] text-gray-500">Last updated:</span>
                        <span id="analytics-last-updated" class="text-[12px] font-medium text-gray-600">Loading...</span>
                    </div>
                </div>

                <!-- Analytics Cards Container -->
                <div id="program-analytics-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Cards will be dynamically generated here -->
                    <div class="flex items-center justify-center p-8">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-[#199BCF]"></div>
                        <span class="ml-2 text-gray-600">Loading analytics...</span>
                    </div>
                </div>
            </div>
            <div class="w-[30%] h-full space-y-4">
                <div
                    class="flex-1 flex flex-col bg-gradient-to-br from-[#199BCF] to-[#1A3165] rounded-xl shadow-sm p-6 gap-4">
                    <span class="font-bold text-white text-[16px]">Quick Actions</span>
                    <div class="flex flex-col justify-center items-center  gap-2 text-center">
                        <x-nav-link href="/documents"
                            class="w-full bg-[#33ACD6] py-2.5 text-[16px] px-4 rounded-xl font-medium text-white hover:bg-[#199BCF] hover:shadow-md transition duration-150">
                            Setup Required Documents
                        </x-nav-link>
                        <x-nav-link href="/school-fees"
                            class="w-full bg-[#33ACD6] py-2.5 text-[16px] px-4 rounded-xl font-medium text-white hover:bg-[#199BCF] hover:shadow-md transition duration-150">
                            Setup School Fees
                        </x-nav-link>
                        <x-nav-link href="/tracks"
                            class="w-full bg-[#33ACD6] py-2.5 text-[16px] px-4 rounded-xl font-medium text-white hover:bg-[#199BCF] hover:shadow-md transition duration-150">
                            Setup Curriculum
                        </x-nav-link>
                    </div>
                </div>
            </div>

        </div>
        <div class="flex flex-col w-full h-auto bg-white rounded-xl border shadow-sm border-[#1e1e1e]/10 p-6 gap-4">
            <span class="text-[16px] text-gray-800 font-bold">Recent Applications</span>


            <div class="flex flex-col items-center flex-grow space-y-2">

                <div class="w-full">
                    <table id="myTable" class="w-full table-fixed">
                        <thead class="text-[14px]">
                            <tr>
                                <th class="w-1/7 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2 truncate">
                                    <span class="mr-2 font-medium opacity-70">Applicant Id</span>
                                </th>
                                <th class="w-1/7 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-70">Full Name</span>
                                </th>
                                <th class="w-1/7 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-70">Program</span>
                                </th>
                                <th class="w-1/7 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-70">Grade Level</span>
                                </th>
                                <th class="w-1/7 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-70">Submitted at</span>
                                </th>
                                <th class="w-1/7 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-70">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be populated by DataTables AJAX -->
                        </tbody>
                    </table>
                </div>
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
            initCustomDataTable
        } from "/js/initTable.js";

        let table;
        let totalApplications = document.querySelector('#total-application');
        let endEnrollmentBtn = document.querySelector('#end-enrollment-period-confirmation');
        let toggle = document.querySelector('#toggleEnrollmentPeriod');

        document.addEventListener("DOMContentLoaded", function() {
            // Load program analytics
            loadProgramAnalytics();

            let recentApplicationTable = initCustomDataTable(
                'myTable',
                `/getRecentApplications`,
                [{
                        data: 'applicant_id',
                        render: DataTable.render.text()
                    },
                    {
                        data: 'full_name',
                        render: DataTable.render.text()
                    },
                    {
                        data: 'program',
                        render: DataTable.render.text()
                    },
                    {
                        data: 'grade_level',
                        render: DataTable.render.text()
                    },
                    {
                        data: 'submitted_at',
                        render: DataTable.render.text()
                    },
                    {
                        data: 'id',
                        render: function(data, type, row) {
                            return `
                                <a href='/applications/pending/form-details/${data}' id="open-edit-modal-btn-${data}"
                                    data-school-fee-id="${data}"
                                    class="edit-school-fee-btn group relative inline-flex items-center gap-1 bg-blue-100 text-blue-500 font-semibold px-3 py-2 rounded-xl hover:bg-blue-500 hover:ring hover:ring-blue-200 hover:text-white transition duration-150">
                                    <i class="fi fi-rr-eye text-[16px] flex justify-center items-center"></i>
                                    View
                                </a>
                            `;
                        },
                        orderable: false,
                        searchable: false
                    }
                ],
                [
                    [0, 'desc']
                ],
                'school-fee-search',
                [{
                        width: '20%',
                        targets: 0,
                        className: 'text-center'
                    },
                    {
                        width: '18%',
                        targets: 1
                    },
                    {
                        width: '15%',
                        targets: 2
                    },
                    {
                        width: '15%',
                        targets: 3
                    },
                    {
                        width: '15%',
                        targets: 4
                    },
                    {
                        width: '10%',
                        targets: 5,
                        className: 'text-center'
                    }
                ],
            );


            initModal('acad-term-modal', 'acad-term-btn', 'at-close-btn', 'cancel-btn', 'modal-container-1');
            initModal('edit-acad-term-modal', 'edit-acad-term-btn', 'edit-at-close-btn', 'edit-cancel-btn', 'modal-container-4');
            initModal('enrollment-period-modal', 'enrollment-period-btn', 'ep-close-btn', 'ep-cancel-btn',
                'modal-container-2');
            initModal('end-enrollment-modal', 'end-enrollment-btn', 'end-enrollment-close-btn',
                'end-enrollment-cancel-btn', 'modal-container-3');

            // Edit Academic Term Modal Functionality
            const editAcadTermBtn = document.getElementById('edit-acad-term-btn');
            if (editAcadTermBtn) {
                editAcadTermBtn.addEventListener('click', function() {
                    const termId = this.getAttribute('data-term-id');
                    const year = this.getAttribute('data-year');
                    const semester = this.getAttribute('data-semester');
                    const startDate = this.getAttribute('data-start-date');
                    const endDate = this.getAttribute('data-end-date');
                    const isActive = this.getAttribute('data-is-active');

                    // Populate the edit form
                    document.getElementById('edit_year').value = year;
                    document.getElementById('edit_semester').value = semester;
                    document.getElementById('edit_start_date').value = startDate;
                    document.getElementById('edit_end_date').value = endDate;
                    document.getElementById('edit_is_active').value = isActive;

                    // Update form action URL
                    const editForm = document.getElementById('edit-academic-term-form');
                    editForm.action = `/academic-terms/${termId}`;
                });
            }

            // Handle edit form submission - let it submit normally
            const editAcadTermForm = document.getElementById('edit-academic-term-form');
            if (editAcadTermForm) {
                editAcadTermForm.addEventListener('submit', function(e) {
                    // Let the form submit normally - no preventDefault needed
                    // The form action will be set when the edit button is clicked
                });
            }

            console.log(window.Echo);

            // Restrict fetching-recent-applications channel to super_admin and admin only
            const userRoles = window.Laravel?.user?.roles?.map(role => role.name || role) || [];

            if (userRoles.some(role => ['super_admin', 'admin'].includes(role))) {
                console.log('Setting up recent applications listener for admin users');

                window.Echo.channel('fetching-recent-applications').listen('RecentApplicationTableUpdated', (
                    event) => {
                    console.log('New application received:', event);
                    console.log('Total applications:', event.total_applications);
                    console.log('Application data:', event.application);

                    // Update total applications counter
                    if (totalApplications) {
                        totalApplications.innerHTML = event.total_applications;
                    }

                    // Reload the table to show new data
                    recentApplicationTable.ajax.reload(function() {
                        console.log('Table reloaded with new application data');

                        // Highlight the first row (newest application) after reload
                        setTimeout(() => {
                            let firstRow = document.querySelector(
                                '#myTable tbody tr:first-child');
                            if (firstRow) {
                                firstRow.classList.add(
                                    'duration-300',
                                    'ease-in-out',
                                    'bg-[#FBBC04]/30'
                                );

                                // Remove highlight after 4000ms
                                setTimeout(() => {
                                    firstRow.classList.remove('bg-[#FBBC04]/30');
                                }, 4000);
                            }
                        }, 500); // Small delay to ensure DOM is updated
                    }, false); // false = don't reset paging

                    // Reload program analytics to reflect new application
                    console.log('Refreshing program analytics...');
                    loadProgramAnalytics();
                });
            } else {
                console.log(
                    'User does not have super_admin or admin role, skipping recent applications channel subscription'
                );
            }

            // if (endEnrollmentBtn) {
            //     endEnrollmentBtn.addEventListener('click', async () => {
            //         const id = endEnrollmentBtn.dataset.id;
            //         console.log(id);
            //         try {
            //             const response = await window.axios.patch(`/enrollment-period/${id}`, {
            //                 status: 'Closed'
            //             }, {
            //                 headers: {
            //                     'Content-Type': 'application/json'
            //                 }
            //             });
            //             if (response.status == 200) {
            //                     window.location.reload();
            //             }
            //         } catch (error) {
            //             console.error(error);
            //         }
            //     });
            // }

            if (toggle) {
                toggle.addEventListener('change', async () => {
                    const id = endEnrollmentBtn.dataset.id;
                    const status = toggle.checked ? 'Ongoing' : 'Paused';
                    console.log(id);
                    try {
                        const response = await window.axios.patch(`/enrollment-period/${id}`, {
                            status: status
                        }, {
                            headers: {
                                'Content-Type': 'application/json'
                            }
                        });
                    } catch (error) {
                        console.error(error);
                    }
                });
            }

            window.Echo.channel('updating-enrollment-period-status').listen('EnrollmentPeriodStatusUpdated', (
                event) => {
                console.log(event.enrollmentPeriod.status);
                let epDetails = document.querySelector('#ep-details');
                let epTime = document.querySelector('#ep-time');
                let statusSpan = document.querySelector('#status-span');

                if (event.enrollmentPeriod.status == 'Paused') {
                    epDetails.classList.add('opacity-30');
                    epTime.classList.add('opacity-30');
                    statusSpan.innerHTML = event.enrollmentPeriod.status;
                    statusSpan.classList.remove('text-[#34A853]');
                    statusSpan.classList.add('text-[#EA4335]');
                } else if (event.enrollmentPeriod.status == 'Ongoing') {
                    epDetails.classList.remove('opacity-30');
                    epTime.classList.remove('opacity-30');
                    statusSpan.innerHTML = event.enrollmentPeriod.status;
                    statusSpan.classList.remove('text-[#EA4335]');
                    statusSpan.classList.add('text-[#34A853]');
                }


            });


            // Live countdown for Time Remaining
            const epTimeSpan = document.querySelector('#ep-time');
            const epTimeValue = document.querySelector('#ep-time-value');
            let countdownInterval;

            function formatRemaining(ms) {
                if (ms <= 0) return '0 Days';
                const totalSeconds = Math.floor(ms / 1000);
                const days = Math.floor(totalSeconds / (3600 * 24));
                const hours = Math.floor((totalSeconds % (3600 * 24)) / 3600);
                const minutes = Math.floor((totalSeconds % 3600) / 60);
                const seconds = totalSeconds % 60;
                const dd = days + ' ' + (days === 1 ? 'Day' : 'Days');
                const hh = String(hours).padStart(2, '0');
                const mm = String(minutes).padStart(2, '0');
                const ss = String(seconds).padStart(2, '0');
                return `${dd} ${hh}:${mm}:${ss}`;
            }

            function startCountdown() {
                if (!epTimeSpan || !epTimeValue) return;
                const endIso = epTimeSpan.getAttribute('data-end');
                const status = epTimeSpan.getAttribute('data-status');
                if (!endIso) return;
                const end = new Date(endIso);
                const tzOffsetMs = new Date().getTimezoneOffset() * 60000; // local offset in ms

                function tick() {
                    if (epTimeSpan.getAttribute('data-status') === 'Paused') return; // don't update while paused
                    const nowLocal = new Date(Date.now());
                    const remaining = end - nowLocal;
                    epTimeValue.textContent = formatRemaining(remaining);
                    if (remaining <= 0) {
                        clearInterval(countdownInterval);
                    }
                }

                // initial draw
                tick();
                // update every second
                countdownInterval = setInterval(tick, 1000);
            }

            startCountdown();

            // Update countdown opacity and status dynamically on status events
            window.Echo.channel('updating-enrollment-period-status').listen('EnrollmentPeriodStatusUpdated', (
                event) => {
                if (!epTimeSpan) return;
                epTimeSpan.setAttribute('data-status', event.enrollmentPeriod.status);
            });





        });

        // Function to load program analytics
        async function loadProgramAnalytics() {
            try {
                const response = await fetch('/application-analytics');
                const result = await response.json();

                if (result.success) {
                    displayProgramAnalytics(result.data.programs);
                    updateLastUpdatedTime();
                } else {
                    // Handle specific cases for better UX
                    if (result.message && result.message.includes('No active academic term')) {
                        displayNoActiveTermState();
                    } else if (result.message && result.message.includes('No active enrollment period')) {
                        displayNoActiveEnrollmentPeriodState();
                    } else {
                        console.error('Failed to load analytics:', result.error || result.message);
                        displayAnalyticsError(result.error || result.message || 'Failed to load analytics');
                    }
                }
            } catch (error) {
                console.error('Error loading analytics:', error);
                displayAnalyticsError('Network error occurred');
            }
        }

        // Function to display program analytics cards
        function displayProgramAnalytics(programs) {
            const container = document.getElementById('program-analytics-container');

            if (!programs || programs.length === 0) {
                container.innerHTML = `
                    <div class="col-span-full flex items-center justify-center p-8 text-gray-500">
                        <div class="text-center">
                            <i class="fi fi-rr-document text-4xl mb-2"></i>
                            <p class="font-medium text-gray-700">No Applications Yet</p>
                            <p class="text-sm text-gray-500 mt-1">Applications will appear here once students start applying during the enrollment period</p>
                        </div>
                    </div>
                `;
                updateLastUpdatedTime();
                return;
            }

            // Generate cards for each program
            const cardsHTML = programs.map(program => {
                // Use real grade level data from the API
                const grade11Count = program.grade_11 || 0;
                const grade12Count = program.grade_12 || 0;

                return `
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-3">
                            <div class="bg-white rounded-md px-3 py-1 border border-gray-200">
                                <span class="text-sm font-bold text-gray-800">${program.code}</span>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-gray-700 bg-gray-200 rounded-lg px-3 py-1">
                                    ${program.count}
                                </div>
                                <div class="text-xs text-gray-600 mt-1">Total</div>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Grade 11:</span>
                                <span class="text-sm font-medium text-gray-800">${grade11Count}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Grade 12:</span>
                                <span class="text-sm font-medium text-gray-800">${grade12Count}</span>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');

            container.innerHTML = cardsHTML;
        }

        // Function to display no active term state
        function displayNoActiveTermState() {
            const container = document.getElementById('program-analytics-container');
            container.innerHTML = `
                <div class="col-span-full flex items-center justify-center p-8 text-gray-500">
                    <div class="text-center">
                        <i class="fi fi-rr-calendar text-4xl mb-2"></i>
                        <p class="font-medium text-gray-700">No Active Academic Term</p>
                        <p class="text-sm text-gray-500 mt-1">Please set up an active academic term to view enrollment analytics</p>
                    </div>
                </div>
            `;
            // Update last updated time to show current time
            updateLastUpdatedTime();
        }

        // Function to display no active enrollment period state
        function displayNoActiveEnrollmentPeriodState() {
            const container = document.getElementById('program-analytics-container');
            container.innerHTML = `
                <div class="col-span-full flex items-center justify-center p-8 text-gray-500">
                    <div class="text-center">
                        <i class="fi fi-rr-clock text-4xl mb-2"></i>
                        <p class="font-medium text-gray-700">No Active Enrollment Period</p>
                        <p class="text-sm text-gray-500 mt-1">Please set up an active enrollment period to view application analytics</p>
                    </div>
                </div>
            `;
            // Update last updated time to show current time
            updateLastUpdatedTime();
        }

        // Function to display error state
        function displayAnalyticsError(errorMessage) {
            const container = document.getElementById('program-analytics-container');
            container.innerHTML = `
                <div class="col-span-full flex items-center justify-center p-8 text-red-500">
                    <div class="text-center">
                        <i class="fi fi-rr-exclamation text-4xl mb-2"></i>
                        <p>Error loading analytics</p>
                        <p class="text-sm text-gray-500 mt-1">${errorMessage}</p>
                    </div>
                </div>
            `;
        }

        // Function to update last updated time
        function updateLastUpdatedTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            });
            document.getElementById('analytics-last-updated').textContent = timeString;
        }
    </script>
@endpush
