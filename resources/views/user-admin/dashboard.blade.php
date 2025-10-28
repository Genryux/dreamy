@extends('layouts.admin', ['title' => 'Dashboard'])

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

    @if (!$activeEnrollmentPeriod)
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
                            <label for="max_applicants" class="text-sm font-medium text-gray-700 mb-2">Max
                                Applicants</label>
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
                            <label for="end_date" class="text-sm font-medium text-gray-700 mb-2">Application End
                                Date</label>
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

                    <!-- Period Type and Early Discount Row -->
                    <div class="flex flex-row gap-4">
                        <div class="flex-1 flex flex-col">
                            <label for="period_type" class="text-sm font-medium text-gray-700 mb-2">Period Type</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fi fi-rr-calendar-day text-gray-400"></i>
                                </div>
                                <select name="period_type" id="edit_period_type"
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-2 focus:ring-[#199BCF]/20 focus:border-[#199BCF] transition duration-150"
                                    required>
                                    <option value="">Select Period Type</option>
                                    <option value="early"
                                        {{ $activeEnrollmentPeriod?->period_type == 'early' ? 'selected' : '' }}>Early
                                        Enrollment</option>
                                    <option value="regular"
                                        {{ $activeEnrollmentPeriod?->period_type == 'regular' ? 'selected' : '' }}>Regular
                                        Enrollment</option>
                                    <option value="late"
                                        {{ $activeEnrollmentPeriod?->period_type == 'late' ? 'selected' : '' }}>Late
                                        Enrollment</option>
                                </select>
                            </div>
                        </div>
                        <div class="flex-1 flex flex-col">
                            <label for="early_discount_percentage" class="text-sm font-medium text-gray-700 mb-2">Early
                                Discount Percentage</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fi fi-rr-percentage text-gray-400"></i>
                                </div>
                                <input type="number" name="early_discount_percentage"
                                    id="edit_early_discount_percentage" min="0" max="100" step="0.01"
                                    placeholder="0.00" value="{{ $activeEnrollmentPeriod?->early_discount_percentage }}"
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-2 focus:ring-[#199BCF]/20 focus:border-[#199BCF] transition duration-150">
                            </div>
                            <p class="text-xs text-gray-500 mt-1" id="discount-help-text">Enter discount percentage for
                                early
                                enrollment (0-100)</p>
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
    @endif


    @if ($activeEnrollmentPeriod)
        <x-modal modal_id="edit-period-modal" modal_name="Edit Enrollment Period"
            close_btn_id="edit-enrollment-close-btn" modal_container_id="modal-container-edit-period">
            <x-slot name="modal_icon">
                <i class='fi fi-rr-edit flex justify-center items-center text-blue-500'></i>
            </x-slot>

            <form action="/enrollment-period/{{ $activeEnrollmentPeriod->id }}" method="POST"
                id="edit-enrollment-period-form" class="p-6">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" value="{{ $activeEnrollmentPeriod->id }}">
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
                                <input type="text" name="name" id="edit_name"
                                    placeholder="Early Registration, Regular, etc."
                                    value="{{ $activeEnrollmentPeriod->name }}"
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg bg-white text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#199BCF]/20 focus:border-[#199BCF] transition duration-150"
                                    required>
                            </div>
                        </div>
                        <div class="flex-1 flex flex-col">
                            <label for="max_applicants" class="text-sm font-medium text-gray-700 mb-2">Max
                                Applicants</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fi fi-rr-users text-gray-400"></i>
                                </div>
                                <input type="number" name="max_applicants" id="edit_max_applicants"
                                    value="{{ $activeEnrollmentPeriod->max_applicants }}"
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
                                <input type="date" name="application_start_date" id="edit_start_date"
                                    value="{{ $activeEnrollmentPeriod->application_start_date }}"
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-2 focus:ring-[#199BCF]/20 focus:border-[#199BCF] transition duration-150"
                                    required>
                            </div>
                        </div>
                        <div class="flex-1 flex flex-col">
                            <label for="end_date" class="text-sm font-medium text-gray-700 mb-2">Application End
                                Date</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fi fi-rr-calendar-xmark text-gray-400"></i>
                                </div>
                                <input type="date" name="application_end_date" id="edit_end_date"
                                    value="{{ $activeEnrollmentPeriod->application_end_date }}"
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-2 focus:ring-[#199BCF]/20 focus:border-[#199BCF] transition duration-150"
                                    required>
                            </div>
                        </div>
                    </div>

                    <!-- Period Type and Early Discount Row -->
                    <div class="flex flex-row gap-4">
                        <div class="flex-1 flex flex-col">
                            <label for="period_type" class="text-sm font-medium text-gray-700 mb-2">Period Type</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fi fi-rr-calendar-day text-gray-400"></i>
                                </div>
                                <select name="period_type" id="period_type"
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-2 focus:ring-[#199BCF]/20 focus:border-[#199BCF] transition duration-150"
                                    required>
                                    <option value="">Select Period Type</option>
                                    <option value="early">Early Enrollment</option>
                                    <option value="regular">Regular Enrollment</option>
                                    <option value="late">Late Enrollment</option>
                                </select>
                            </div>
                        </div>
                        <div class="flex-1 flex flex-col">
                            <label for="early_discount_percentage" class="text-sm font-medium text-gray-700 mb-2">Early
                                Discount Percentage</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fi fi-rr-percentage text-gray-400"></i>
                                </div>
                                <input type="number" name="early_discount_percentage" id="early_discount_percentage"
                                    min="0" max="100" step="0.01" placeholder="0.00"
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-2 focus:ring-[#199BCF]/20 focus:border-[#199BCF] transition duration-150">
                            </div>
                            <p class="text-xs text-gray-500 mt-1" id="discount-help-text">Enter discount percentage for
                                early
                                enrollment (0-100)</p>
                        </div>
                    </div>
                </div>
            </form>

            <x-slot name="modal_buttons">
                <button id="edit-period-cancel-btn"
                    class="bg-gray-50 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-100 hover:ring hover:ring-gray-200 transition duration-150">
                    Cancel
                </button>
                <button type="submit" form="edit-enrollment-period-form"
                    class="self-center flex flex-row justify-center items-center bg-[#199BCF] py-2 px-3 rounded-xl text-[14px] font-semibold gap-2 text-white hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                    Update Period
                </button>
            </x-slot>
        </x-modal>
        {{-- End period --}}
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

    @if ($currentAcadTerm)
        <x-modal modal_id="start-academic-term" modal_name="Start new academic term"
            close_btn_id="start-academic-term-close-btn" modal_container_id="modal-container-start-term">
            <x-slot name="modal_icon">
                <i class='fi fi-rr-calendar flex justify-center items-center'></i>
            </x-slot>

            <form action="/new-term/{{ $currentAcadTerm->id }}" method="POST" id="start-academic-term-form"
                class="p-6">
                @csrf
                <input type="hidden" name="status" id="ep-status" value="Closed">

                <div class="space-y-4">

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
                                        <option value="1st Semester" selected>1st Semester</option>
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
                    </div>

                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fi fi-rr-exclamation-triangle text-yellow-400"></i>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-sm font-medium text-yellow-900">Important Notice</h4>
                                <p class="text-[13px] text-yellow-800 mt-2">This process will:</p>
                                <div class="mt-1 text-sm text-yellow-700">
                                    <ul class="list-disc list-inside space-y-1">
                                        <li>
                                            Promote all students to the next year level, including those not yet evaluated.
                                            <i class="text-[12px]">(Completed grade 12 students will be mark as Graduated.)
                                            </i>
                                        </li>
                                        <li>
                                            Set all student statuses to <span class="font-medium">“Pending
                                                Confirmation”</span> , prompting them to confirm
                                            enrollment via the mobile app.
                                        </li>
                                        <li>
                                            Automatically assign all relevant school fees.
                                        </li>
                                    </ul>
                                    <h4 class="mt-2 text-[13px]">
                                        Please review all student records carefully before initiating this process to ensure
                                        accuracy.
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fi fi-rr-exclamation-triangle text-gray-400"></i>
                            </div>
                            <div class="ml-3 w-full">
                                <h4 class="text-sm font-medium text-gray-900">Summary</h4>
                                <div class="mt-1 text-sm text-gray-700 flex flex-row justify-evenly items-center w-full">
                                    <div class="flex flex-col justify-center items-center">
                                        <span>To promote</span>
                                        <span
                                            class="font-semibold text-gray-600">{{ $countStudentStatuses['to_promote'] }}</span>
                                    </div>
                                    <div class="flex flex-col justify-center items-center">
                                        <span>To retain</span>
                                        <span
                                            class="font-semibold text-gray-600">{{ $countStudentStatuses['to_retain'] }}</span>

                                    </div>
                                    <div class="flex flex-col justify-center items-center">
                                        <span>To graduate</span>
                                        <span
                                            class="font-semibold text-gray-600">{{ $countStudentStatuses['to_graduate'] }}</span>

                                    </div>
                                    <div class="flex flex-col justify-center items-center">
                                        <span>Not Evaluated</span>
                                        <span
                                            class="font-semibold text-gray-600">{{ $countStudentStatuses['not_evaluated'] }}</span>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <x-slot name="modal_buttons">
                <button id="start-academic-term-cancel-btn"
                    class="bg-gray-50 border border-[#1e1e1e]/15 text-[14px] px-3 py-2.5 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-100 hover:ring hover:ring-gray-200 transition duration-150">
                    Cancel
                </button>
                <button type="submit" form="start-academic-term-form" id="start-academic-term-confirmation"
                    data-id="{{ $currentAcadTerm->id }}"
                    class="self-center flex flex-row justify-center items-center bg-[#199BCF] py-2.5 px-3 rounded-xl text-[14px] font-semibold gap-2 text-white hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                    Start New Term
                </button>
            </x-slot>
        </x-modal>
    @endif
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
        <div class="flex flex-row items-center justify-center gap-4 p-4">
            <div class="flex-1 w-full flex flex-row items-center bg-blue-50 rounded-xl justify-between p-6">
                <div class="flex flex-row items-center justify-center">
                    @if (@isset($currentAcadTerm->id))
                        <button id="edit-acad-term-btn"
                            class="flex flex-col justify-center items-start text-gray-800 hover:text-blue-400 ease-in-out duration-150 cursor-pointer space-y-1"
                            data-term-id="{{ $currentAcadTerm->id }}" data-year="{{ $currentAcadTerm->year }}"
                            data-semester="{{ $currentAcadTerm->semester }}"
                            data-start-date="{{ $currentAcadTerm->start_date }}"
                            data-end-date="{{ $currentAcadTerm->end_date }}"
                            data-is-active="{{ $currentAcadTerm->is_active }}" title="Click to edit academic term">

                            @if ($currentAcadTerm->year === null)
                                <span class="font-bold text-[16px] ">No active academic year yet.</span>
                            @else
                                <span class="font-bold text-[16px] ">Academic Year {{ $currentAcadTerm->year }}</span>
                                <span
                                    class="text-[14px] font-semibold text-gray-700">{{ $currentAcadTerm->semester }}</span>
                                <span
                                    class="text-[12px] font-medium text-gray-500">{{ \Carbon\Carbon::parse($currentAcadTerm->start_date)->format('M. d, Y') . ' - ' . \Carbon\Carbon::parse($currentAcadTerm->end_date)->format('M. d, Y') }}</span>
                            @endif
                        </button>
                    @else
                        <button
                            class="flex flex-col justify-center items-start text-blue-500 hover:text-blue-400 ease-in-out duration-150 py-7">

                            <span class="font-bold text-[14px] ">No academic term found.</span>
                        </button>
                    @endif
                </div>
                <div>
                    @if ($currentAcadTerm)
                        <button id="end-term-btn"
                            class="self-center flex flex-row justify-center items-center bg-[#199BCF] py-2.5 px-3 rounded-xl text-[14px] font-semibold gap-2 text-white hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                            <i
                                class="fi fi-sr-calendar-xmark flex justify-center opacity-70 items-center text-[16px] "></i>
                            End & Start New Term
                        </button>
                    @else
                        <button id="acad-term-btn"
                            class="self-center flex flex-row justify-center items-center bg-[#199BCF] py-2.5 px-3 rounded-xl text-[16px] font-semibold gap-2 text-white hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                            <i class="fi fi-sr-square-plus flex justify-center opacity-70 items-center text-[18px] "></i>
                            Add new term
                        </button>
                    @endif
                </div>
            </div>
            @if ($activeEnrollmentPeriod)
                <div id="enrollment-cont"
                    class="flex-1 w-full flex flex-row items-center {{ $activeEnrollmentPeriod->status == 'Ongoing' ? 'bg-green-50' : 'bg-red-50' }} bg-blue-50 rounded-xl justify-between p-6">
                    <div class="flex flex-row items-center space-x-1">
                        @if (@isset($currentAcadTerm->id))
                            <button id="edit-period-btn"
                                class="flex flex-col justify-center items-start text-blue-500 hover:text-blue-400 ease-in-out duration-150 cursor-pointer">
                                <span class="font-bold text-[16px] text-gray-800">
                                    {{ $activeEnrollmentPeriod->name }}
                                </span>
                                <span class="font-semibold text-[14px] text-gray-700">
                                    {{ \Carbon\Carbon::parse($activeEnrollmentPeriod->application_start_date)->format('M. d') . ' - ' . \Carbon\Carbon::parse($activeEnrollmentPeriod->application_end_date)->format('M. d') }}
                                </span>
                                @if ($activeEnrollmentPeriod->status == 'Ongoing')
                                    <span id="status-span"
                                        class="text-[12px] text-green-500 bg-green-100 px-2 py-1 rounded-full mt-1">Ongoing</span>
                                @elseif ($activeEnrollmentPeriod->status == 'Paused')
                                    <span id="status-span"
                                        class="text-[12px] text-red-500 bg-red-100 px-2 py-1 rounded-full mt-1">Paused</span>
                                @endif
                            </button>
                        @else
                            <div class="flex flex-col">
                                <button
                                    class="flex flex-col justify-center items-start text-blue-500 hover:text-blue-400 ease-in-out duration-150">

                                    <span class="font-bold text-[18px] ">No academic year found, please create
                                        first.</span>
                                </button>
                            </div>
                        @endif
                    </div>
                    <div class="flex flex-col gap-2 justify-center items-end">
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
                        <button id="end-enrollment-btn"
                            class="self-center flex flex-row justify-center items-center bg-red-100 border border-red-200 py-2.5 px-3 rounded-xl text-[12px] font-semibold gap-2 text-red-500 hover:bg-red-100 hover:-translate-y-1 transition duration-200  truncate">
                            <i class="fi fi-sr-cross-circle flex justify-center opacity-70 items-center text-[18px] "></i>
                            End Period</button>
                    </div>
                </div>
            @else
                <div id="enrollment-cont"
                    class="flex-1 w-full flex flex-row items-center bg-blue-50 rounded-xl justify-between p-6 py-10">
                    <span class="text-gray-700 text-[14px]">There is currently no active enrollment period</span>
                    <button id="enrollment-period-btn"
                        class="flex flex-row justify-center items-center bg-[#199BCF]/10 border border-[#199BCF]/20  text-[#199BCF] hover:text-white px-3 py-2.5 font-bold text-[14px] rounded-xl gap-2 hover:bg-[#199BCF] hover:border-[#199BCF] shadow-[#199BCF]/40 hover:shadow-xl transition duration-200">
                        <i class="fi fi-sr-square-plus flex justify-center opacity-70 items-center text-[18px] "></i>
                        Add Enrollment Period
                    </button>

                </div>
            @endif

        </div>

    </x-header-container>
@endsection

@section('stat')
    @if (!$enrollmentSummary)

        <div class="flex flex-row space-x-4 bg-white p-6 rounded-xl border border-[#1e1e1e]/10 shadow-sm">
            {{-- Application overview --}}
            <div class="bg-blue-50 flex-1 w-full rounded-xl p-6 space-y-2 shadow-sm  ">
                <div class="flex flex-row items-center justify-between">
                    <span class="font-semibold text-[16px] text-gray-800">Enrollment Overview</span>
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
                            <span class="font-medium text-[14px] text-gray-600">Total Applications</span>
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
            <!-- Program Analytics Section -->
            <div class="flex-1 w-full flex flex-col h-auto bg-blue-50 rounded-xl  shadow-sm  p-6 gap-4">
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
                    <div class="flex flex-col items-center justify-center p-8 w-full">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-[#199BCF]"></div>
                        <span class="ml-2 text-gray-600">Loading analytics...</span>
                    </div>
                </div>
            </div>

        </div>
    @else
        <div class="flex flex-col justify-center items-start gap-4 mb-4">
            <div class="w-full bg-white rounded-xl border border-[#1e1e1e]/10 shadow-sm p-6">
                <div class="flex flex-row items-center justify-between mb-4">
                    <div class="flex flex-col">
                        <h2 class="text-[20px] font-bold text-gray-800">Enrollment Summary</h2>
                        <p class="text-[14px] text-gray-600">{{ $enrollmentSummary['enrollment_period']->name }} -
                            {{ \Carbon\Carbon::parse($enrollmentSummary['enrollment_period']->application_start_date)->format('M d') }}
                            to
                            {{ \Carbon\Carbon::parse($enrollmentSummary['enrollment_period']->application_end_date)->format('M d, Y') }}
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-[12px] text-gray-500">Period Duration:</span>
                        <span class="text-[12px] font-medium text-gray-600">{{ $enrollmentSummary['period_duration'] }}
                            days</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <!-- Total Applications -->
                    <div class="bg-white rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Total Applications</p>
                                <p class="text-2xl font-bold text-gray-900">
                                    {{ number_format($enrollmentSummary['total_applications']) }}</p>
                            </div>
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fi fi-rr-document text-blue-600 text-sm"></i>
                            </div>
                        </div>
                        @if ($enrollmentSummary['max_applicants'] > 0)
                            <div class="mt-2">
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-500 h-2 rounded-full"
                                        style="width: {{ min(100, $enrollmentSummary['capacity_utilization']) }}%"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">{{ $enrollmentSummary['capacity_utilization'] }}% of
                                    capacity</p>
                            </div>
                        @endif
                    </div>

                    <!-- Acceptance Rate -->
                    <div class="bg-white rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Acceptance Rate</p>
                                <p class="text-2xl font-bold text-green-600">{{ $enrollmentSummary['acceptance_rate'] }}%
                                </p>
                            </div>
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fi fi-rr-check-circle text-green-600 text-sm"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ number_format($enrollmentSummary['ever_accepted_applications']) }} ever accepted</p>
                    </div>

                    <!-- Enrollment Success Rate -->
                    <div class="bg-white rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Enrollment Success</p>
                                <p class="text-2xl font-bold text-purple-600">
                                    {{ $enrollmentSummary['enrollment_success_rate'] }}%</p>
                            </div>
                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                <i class="fi fi-rr-graduation-cap text-purple-600 text-sm"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ number_format($enrollmentSummary['officially_enrolled']) }} enrolled</p>
                    </div>

                    <!-- Overall Success Rate -->
                    <div class="bg-white rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Overall Success</p>
                                <p class="text-2xl font-bold text-indigo-600">
                                    {{ $enrollmentSummary['overall_success_rate'] }}%</p>
                            </div>
                            <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                                <i class="fi fi-rr-trophy text-indigo-600 text-sm"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Application to enrollment</p>
                    </div>
                </div>

                <!-- Status Breakdown -->
                <div class="grid grid-cols-2 md:grid-cols-6 gap-3 mb-6">
                    <div class="bg-yellow-50 rounded-lg p-3 border border-yellow-200">
                        <p class="text-xs font-medium text-yellow-700">Pending</p>
                        <p class="text-lg font-bold text-yellow-800">
                            {{ number_format($enrollmentSummary['pending_applications']) }}</p>
                    </div>
                    <div class="bg-green-50 rounded-lg p-3 border border-green-200">
                        <p class="text-xs font-medium text-green-700">Currently Accepted</p>
                        <p class="text-lg font-bold text-green-800">
                            {{ number_format($enrollmentSummary['currently_accepted_applications']) }}</p>
                    </div>
                    <div class="bg-orange-50 rounded-lg p-3 border border-orange-200">
                        <p class="text-xs font-medium text-orange-700">Pending Docs</p>
                        <p class="text-lg font-bold text-orange-800">
                            {{ number_format($enrollmentSummary['pending_documents']) }}</p>
                    </div>
                    <div class="bg-blue-50 rounded-lg p-3 border border-blue-200">
                        <p class="text-xs font-medium text-blue-700">Enrolled</p>
                        <p class="text-lg font-bold text-blue-800">
                            {{ number_format($enrollmentSummary['officially_enrolled']) }}</p>
                    </div>
                    <div class="bg-red-50 rounded-lg p-3 border border-red-200">
                        <p class="text-xs font-medium text-red-700">Rejected</p>
                        <p class="text-lg font-bold text-red-800">
                            {{ number_format($enrollmentSummary['rejected_applications']) }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                        <p class="text-xs font-medium text-gray-700">Failed</p>
                        <p class="text-lg font-bold text-gray-800">
                            {{ number_format($enrollmentSummary['completed_failed']) }}</p>
                    </div>
                </div>

                <!-- Program Breakdown -->
                @if ($enrollmentSummary['program_breakdown']->count() > 0)
                    <div class="bg-white rounded-lg p-4 border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-800 mb-3">Program Breakdown</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach ($enrollmentSummary['program_breakdown'] as $program)
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <div class="flex items-center justify-between mb-2">
                                        <span
                                            class="text-sm font-medium text-gray-800">{{ $program['program_code'] }}</span>
                                        <span class="text-xs text-gray-500">{{ $program['total_applications'] }}
                                            total</span>
                                    </div>
                                    <div class="space-y-1">
                                        <div class="flex justify-between text-xs">
                                            <span class="text-green-600">Ever Accepted:
                                                {{ $program['ever_accepted'] }}</span>
                                            <span class="text-blue-600">Enrolled: {{ $program['enrolled'] }}</span>
                                        </div>
                                        @if ($program['total_applications'] > 0)
                                            <div class="w-full bg-gray-200 rounded-full h-1">
                                                <div class="bg-blue-500 h-1 rounded-full"
                                                    style="width: {{ round(($program['enrolled'] / $program['total_applications']) * 100) }}%">
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

    @endif
@endsection


@section('content')
    <x-alert />

    <div class="flex flex-col justify-center items-start gap-4">

        <div class="flex flex-row w-full h-auto gap-4">
            <div class="flex flex-col w-[70%] h-auto bg-white rounded-xl border shadow-sm border-[#1e1e1e]/10 p-6 gap-4">
                <span class="text-[16px] text-gray-800 font-bold">Recent Applications</span>


                <div class="flex flex-col items-center flex-grow space-y-2">

                    <div class="w-full">
                        <table id="myTable" class="w-full table-fixed">
                            <thead class="text-[14px]">
                                <tr>
                                    <th
                                        class="w-1/7 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2 truncate">
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
        import {
            showAlert
        } from "/js/alert.js";
        import {
            showLoader,
            hideLoader
        } from "/js/loader.js";

        let table;
        let totalApplications = document.querySelector('#total-application');
        let endEnrollmentBtn = document.querySelector('#end-enrollment-period-confirmation');
        let toggle = document.querySelector('#toggleEnrollmentPeriod');

        document.addEventListener("DOMContentLoaded", function() {


            @if (session('success'))
                showAlert('success', '{{ session('success') }}');
            @endif

            @if (session('error'))
                showAlert('error', '{{ session('error') }}');
            @endif

            @if ($errors->any())
                showAlert('error', '{{ $errors->first() }}');
            @endif
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
            initModal('edit-acad-term-modal', 'edit-acad-term-btn', 'edit-at-close-btn', 'edit-cancel-btn',
                'modal-container-4');
            initModal('enrollment-period-modal', 'enrollment-period-btn', 'ep-close-btn', 'ep-cancel-btn',
                'modal-container-2');
            initModal('end-enrollment-modal', 'end-enrollment-btn', 'end-enrollment-close-btn',
                'end-enrollment-cancel-btn', 'modal-container-3');
            initModal('start-academic-term', 'end-term-btn', 'start-academic-term-close-btn',
                'start-academic-term-cancel-btn', 'modal-container-start-term');
            initModal('edit-period-modal', 'edit-period-btn', 'edit-enrollment-close-btn',
                'edit-period-cancel-btn', 'modal-container-edit-period');

            // Enrollment Period Type Logic
            const periodTypeSelect = document.getElementById('period_type');
            const discountField = document.getElementById('early_discount_percentage');
            const discountLabel = document.querySelector('label[for="early_discount_percentage"]');
            const discountHelpText = document.getElementById('discount-help-text');
            const discountContainer = discountField?.closest('.flex-1');

            if (periodTypeSelect && discountField && discountContainer) {
                // Function to update discount field based on period type
                function updateDiscountField() {
                    const isEarly = periodTypeSelect.value === 'early';

                    if (isEarly) {
                        // For early enrollment - make discount optional but encourage it
                        discountField.required = false;
                        discountField.setAttribute('min', '0');
                        discountField.setAttribute('max', '100');
                        discountField.placeholder = 'Enter discount percentage (0-100)';
                        discountLabel.innerHTML =
                            'Early Discount Percentage <span class="text-blue-500">(Recommended)</span>';
                        if (discountHelpText) {
                            discountHelpText.textContent =
                                'Optional: Enter discount percentage to incentivize early enrollment (0-100)';
                            discountHelpText.className = 'text-xs text-blue-500 mt-1';
                        }
                        discountContainer.style.opacity = '1';
                        discountContainer.style.pointerEvents = 'auto';
                    } else {
                        // For regular/late enrollment - make discount optional
                        discountField.required = false;
                        discountField.setAttribute('min', '0');
                        discountField.setAttribute('max', '100');
                        discountField.placeholder = '0.00';
                        discountField.value = '0';
                        discountLabel.innerHTML =
                            'Early Discount Percentage <span class="text-gray-400">(Optional)</span>';
                        if (discountHelpText) {
                            discountHelpText.textContent = 'Optional discount for early enrollment (0-100)';
                            discountHelpText.className = 'text-xs text-gray-500 mt-1';
                        }
                        discountContainer.style.opacity = '0.6';
                        discountContainer.style.pointerEvents = 'auto';
                    }
                }

                // Initial state
                updateDiscountField();

                // Listen for changes
                periodTypeSelect.addEventListener('change', updateDiscountField);
            }

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

            // Edit Enrollment Period Modal Functionality
            const editPeriodBtn = document.getElementById('edit-period-btn');
            if (editPeriodBtn) {
                editPeriodBtn.addEventListener('click', function() {
                    // The form is already populated with data from the server
                    // No need to populate fields as they're already set in the Blade template
                    console.log('Edit enrollment period modal opened');
                });
            }

            // Handle edit enrollment period form submission
            const editEnrollmentPeriodForm = document.getElementById('edit-enrollment-period-form');
            if (editEnrollmentPeriodForm) {
                editEnrollmentPeriodForm.addEventListener('submit', function(e) {
                    // Let the form submit normally - no preventDefault needed
                    console.log('Edit enrollment period form submitted');
                });
            }

            // Restrict fetching-recent-applications channel to super_admin and admin only
            const userRoles = window.Laravel?.user?.roles?.map(role => role.name || role) || [];

            if (userRoles.some(role => ['super_admin', 'admin'].includes(role))) {

                window.Echo.channel('fetching-recent-applications').listen('RecentApplicationTableUpdated', (
                    event) => {

                    // Update total applications counter
                    if (totalApplications) {
                        totalApplications.innerHTML = event.total_applications;
                    }

                    // Reload the table to show new data
                    recentApplicationTable.ajax.reload(function() {

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
                let statusSpan = document.querySelector('#status-span');

                if (event.enrollmentPeriod.status == 'Paused') {
                    statusSpan.innerHTML = event.enrollmentPeriod.status;
                    statusSpan.classList.remove('text-green-500');
                    statusSpan.classList.remove('bg-green-100');
                    statusSpan.classList.add('text-red-500');
                    statusSpan.classList.add('bg-red-100');
                    document.querySelector('#enrollment-cont').classList.remove('bg-blue-50')
                    document.querySelector('#enrollment-cont').classList.add('bg-red-50')
                } else if (event.enrollmentPeriod.status == 'Ongoing') {
                    statusSpan.innerHTML = event.enrollmentPeriod.status;
                    statusSpan.classList.remove('text-red-500');
                    statusSpan.classList.remove('bg-red-100');
                    statusSpan.classList.add('text-green-500');
                    statusSpan.classList.add('bg-green-100');
                    document.querySelector('#enrollment-cont').classList.remove('bg-red-50')
                    document.querySelector('#enrollment-cont').classList.add('bg-green-50')


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
                    if (result.message && (result.message.includes('No academic term found') || result.message.includes(
                            'No active academic term'))) {
                        displayNoActiveTermState();
                    } else if (result.message && result.message.includes('No active enrollment period')) {
                        displayNoActiveEnrollmentPeriodState();
                    } else {
                        displayAnalyticsError(result.error || result.message || 'Failed to load analytics');
                    }
                }
            } catch (error) {
                displayAnalyticsError('Network error occurred');
            }
        }

        // Function to display program analytics cards
        function displayProgramAnalytics(programs) {
            const container = document.getElementById('program-analytics-container');

            if (!container) {
                console.warn('program-analytics-container not found');
                return;
            }

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

            if (!container) {
                console.warn('program-analytics-container not found');
                return;
            }

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

            if (!container) {
                console.warn('program-analytics-container not found');
                return;
            }

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

            if (!container) {
                console.warn('program-analytics-container not found');
                return;
            }

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
            const element = document.getElementById('analytics-last-updated');

            if (!element) {
                console.warn('analytics-last-updated element not found');
                return;
            }

            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            });
            element.textContent = timeString;
        }
    </script>
@endpush
