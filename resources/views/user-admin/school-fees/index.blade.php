@extends('layouts.admin', ['title' => 'School Fees'])
@section('modal')
    {{-- invoice settings --}}
    <x-modal modal_id="set-down-payment-modal" modal_name="Set Down Payment" close_btn_id="set-down-payment-close-btn"
        modal_container_id="modal-container-3">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-usd-circle flex justify-center items-center '></i>
        </x-slot>

        <form id="set-down-payment-modal-form" class="p-6">
            @csrf

            <div class="flex flex-col justify-center items-center gap-2 w-full">

                <label for="name">Enter amount</label>

                <input type="text" name="down_payment" id="down_payment" required
                    class="border-2 border-gray-300 bg-gray-100 rounded-lg px-3 py-2 outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 transition duration-200 placeholder:italic placeholder:text-[14px]"
                    placeholder="₱5,000, ₱10,000, ₱15,000...">
                <p class="text-[14px] mt-2 text-gray-600 text-center w-full px-2">
                    This sets the default down payment used when generating monthly installment plans. You can change it
                    anytime; future plans will use the updated amount.
                </p>
            </div>

        </form>

        <x-slot name="modal_buttons">
            <button id="set-down-payment-cancel-btn"
                class="bg-gray-100 border border-[#1e1e1e]/10 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-200 hover:ring hover:ring-gray-100 transition duration-150">
                Cancel
            </button>
            {{-- This button will acts as the submit button --}}
            <button type="submit" form="set-down-payment-modal-form" id="set-down-payment-submit"
                class="bg-[#199BCF] text-[14px] px-3 py-2 rounded-xl text-[#f8f8f8] font-bold hover:ring hover:ring-[#C8A165]/20 hover:bg-[#C8A165] transition duration-150 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg gap-2 flex flex-row justify-center items-center">
                <i class="fi fi-rr-disk flex justify-center items-center"></i>
                Save
            </button>
        </x-slot>

    </x-modal>

    {{-- Set Due Date Modal --}}
    <x-modal modal_id="set-due-date-modal" modal_name="Set Due Date" close_btn_id="set-due-date-close-btn"
        modal_container_id="modal-container-4">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-calendar flex justify-center items-center '></i>
        </x-slot>

        <form id="set-due-date-modal-form" class="p-6">
            @csrf

            <div class="flex flex-col justify-center items-center gap-2 w-full">

                <label for="due_day_of_month">Select day of month</label>

                <select name="due_day_of_month" id="due_day_of_month" required
                    class="border-2 border-gray-300 bg-gray-100 rounded-lg px-3 py-2 outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 transition duration-200">
                    @for ($i = 1; $i <= 31; $i++)
                        <option value="{{ $i }}"
                            {{ ($schoolSetting->due_day_of_month ?? 10) == $i ? 'selected' : '' }}>
                            {{ $i }}{{ $i == 1 ? 'st' : ($i == 2 ? 'nd' : ($i == 3 ? 'rd' : 'th')) }}
                        </option>
                    @endfor
                </select>

                <p class="text-[14px] mt-2 text-gray-600 text-center w-full px-2">
                    This sets the day of each month when school fees are due. Monthly reminders will be sent based on this
                    date.
                </p>
            </div>
        </form>

        <x-slot name="modal_buttons">
            <button type="button" id="set-due-date-cancel-btn"
                class="bg-gray-100 border border-[#1e1e1e]/10 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-200 hover:ring hover:ring-gray-100 transition duration-150">
                Cancel
            </button>
            {{-- This button will acts as the submit button --}}
            <button type="submit" form="set-due-date-modal-form" id="set-due-date-submit"
                class="bg-[#199BCF] text-[14px] px-3 py-2 rounded-xl text-[#f8f8f8] font-bold hover:ring hover:ring-[#C8A165]/20 hover:bg-[#C8A165] transition duration-150 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg gap-2 flex flex-row justify-center items-center">
                <i class="fi fi-rr-disk flex justify-center items-center"></i>
                Save
            </button>
        </x-slot>

    </x-modal>

    {{-- create school fee --}}
    <x-modal modal_id="create-school-fee-modal" modal_name="Create School Fee"
        close_btn_id="create-school-fee-modal-close-btn" modal_container_id="modal-container-1">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-usd-circle flex justify-center items-center '></i>
        </x-slot>

        <form id="create-school-fee-modal-form" class="p-6">
            @csrf

            <div class="flex flex-col justify-center items-center space-y-4">

                <div class="w-full">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-tags mr-2"></i>
                        Fee Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" required
                        placeholder="e.g., Tuition Fee, Laboratory Fee"
                        class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]">
                </div>

                <div class="w-full">
                    <label for="program_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-graduation-cap mr-2"></i>
                        Applied to Program
                    </label>
                    <select name="program_id" id="program_id"
                        class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]">
                        <option value="">All Programs</option>
                        @foreach ($programs as $program)
                            <option value="{{ $program->id }}">{{ $program->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full">
                    <label for="grade_level" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-school mr-2"></i>
                        Applied to Year Level
                    </label>
                    <select name="grade_level" id="grade_level"
                        class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]">
                        <option value="">All Year Levels</option>
                        <option value="Grade 11">Grade 11</option>
                        <option value="Grade 12">Grade 12</option>
                    </select>
                </div>

                <div class="w-full">
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-usd-circle mr-2"></i>
                        Amount <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="amount" id="amount" required min="0" step="0.01"
                        placeholder="0.00"
                        class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]">
                </div>
            </div>

        </form>

        <x-slot name="modal_buttons">
            <button id="create-school-fee-modal-cancel-btn"
                class="bg-gray-50 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-100 hover:ring hover:ring-gray-200 transition duration-150">
                Cancel
            </button>
            {{-- This button will acts as the submit button --}}
            <button type="submit" form="create-school-fee-modal-form" id="create-school-fee-submit-btn"
                class="bg-[#199BCF] py-2 px-3 rounded-xl text-[14px] font-semibold gap-2 text-white hover:ring hover:ring-[#C8A165]/20 hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                Create Fee
            </button>
        </x-slot>

    </x-modal>
    {{-- Create invoice modal --}}
    <x-modal modal_id="create-invoice-modal" modal_name="Create Invoice" close_btn_id="create-invoice-modal-close-btn"
        modal_container_id="modal-container-2">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-receipt flex justify-center items-center'></i>
        </x-slot>

        <form method="POST" action="/invoice" id="create-invoice-modal-form" class="flex flex-col h-full">
            @csrf

            <div class="flex-1 overflow-y-auto px-6 py-4 space-y-4">
                {{-- Student Search Section --}}
                <div class="space-y-3">
                    <label for="studentSearch" class="block text-sm font-semibold text-gray-700">
                        Search Student
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fi fi-rs-search text-gray-400"></i>
                        </div>
                        <input type="text" name="search" id="studentSearch"
                            class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-10 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]"
                            placeholder="Search by last name or LRN">
                        <div id="search-status" class="absolute inset-y-0 right-0 pr-3 flex items-center hidden">
                            <i id="search-icon" class="text-sm flex flex justify-center items-center"></i>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500">Enter at least 2 characters to search</p>
                </div>

                <input type="hidden" name="student_id" id="student_id" value="">

                {{-- Student Information Section --}}
                <div id="student-info-section" class="space-y-3">
                    <div id="student-info-card" class="bg-[#E3ECFF]/30 border border-[#199BCF]/20 rounded-lg p-4">
                        <h3 class="text-sm font-semibold mb-3 text-gray-700 flex items-center gap-2">
                            Student Information
                        </h3>
                        <div class="flex flex-row text-sm">
                            <div class="flex-1 flex flex-col justify-center items-start gap-2">
                                <div>
                                    <span class="text-gray-600 font-medium">Full Name:</span>
                                    <span id="full-name" class="ml-2 text-gray-500">Search for a student...</span>
                                </div>
                                <div>
                                    <span class="text-gray-600 font-medium">Year Level:</span>
                                    <span id="level" class="ml-2 text-gray-500">-</span>
                                </div>

                            </div>
                            <div class="flex-1 flex flex-col justify-center items-start gap-2">
                                <div>
                                    <span class="text-gray-600 font-medium">LRN:</span>
                                    <span id="lrn" class="ml-2 text-gray-500">-</span>
                                </div>
                                <div>
                                    <span class="text-gray-600 font-medium">Program:</span>
                                    <span id="program" class="ml-2 text-gray-500">-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Fees Selection Section --}}
                <div id="fees-section" class="space-y-3">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                            Select Applicable Fees
                        </h3>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" id="select-all-fees"
                                class="w-4 h-4 text-[#199BCF] border-[#199BCF]/30 rounded focus:ring-[#199BCF]/20"
                                disabled>
                            <label for="select-all-fees"
                                class="text-sm font-medium text-gray-500 cursor-not-allowed">Select
                                All</label>
                        </div>
                    </div>
                    <div id="fees-container"
                        class="space-y-2 max-h-32 overflow-y-auto border border-[#199BCF]/20 rounded-lg p-4 bg-[#E3ECFF]/20 min-h-[120px] flex items-center justify-center">
                        <div class="text-center">
                            <i class="fi fi-rr-search text-[#199BCF]/60 text-3xl mb-3"></i>
                            <p class="text-sm text-[#199BCF]/70 font-medium">Search for a student to see applicable fees
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Total Amount Section --}}
                <div id="total-section" class="hidden">
                    <div class="bg-[#E3ECFF]/40 border border-[#199BCF]/30 rounded-lg p-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-semibold text-[#199BCF]">Total Amount:</span>
                            <span id="total-amount" class="text-lg font-bold text-[#199BCF]">₱0.00</span>
                        </div>
                    </div>
                </div>

                {{-- Status Messages --}}
                <div id="status-messages" class="space-y-2 hidden">
                    <p id="fees-msg" class="text-sm text-gray-500 text-center py-2">
                        Search for a student to see applicable fees
                    </p>
                </div>
            </div>
        </form>

        <x-slot name="modal_buttons">
            <button id="create-invoice-modal-cancel-btn" type="button"
                class="bg-gray-100 border border-[#1e1e1e]/15 text-[14px] px-4 py-2 rounded-md text-[#0f111c]/80 font-semibold shadow-sm hover:bg-gray-200 hover:ring hover:ring-gray-200 transition duration-150">
                Cancel
            </button>
            <button type="submit" form="create-invoice-modal-form" name="action" value="create"
                id="create-invoice-submit-btn"
                class="bg-[#199BCF] py-2 px-3 rounded-xl text-[14px] font-semibold gap-2 text-white hover:ring hover:ring-[#C8A165]/20 hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate disabled:opacity-50 disabled:cursor-not-allowed"
                disabled>
                Create Invoice

            </button>
        </x-slot>

    </x-modal>
    {{-- edit school fee --}}
    <x-modal modal_id="edit-school-fee-modal" modal_name="Edit School Fee" close_btn_id="edit-school-fee-modal-close-btn"
        modal_container_id="modal-container-5">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-usd-circle flex justify-center items-center '></i>
        </x-slot>

        <form id="edit-school-fee-modal-form" class="p-6">
            @csrf

            <div class="flex flex-col justify-center items-center space-y-4">

                <div class="w-full">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-tags mr-2"></i>
                        Fee Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" required
                        placeholder="e.g., Tuition Fee, Laboratory Fee"
                        class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]">
                </div>

                <div class="w-full">
                    <label for="program_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-graduation-cap mr-2"></i>
                        Applied to Program
                    </label>
                    <select name="program_id" id="program_id"
                        class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]">
                        <option value="">All Programs</option>
                        @foreach ($programs as $program)
                            <option value="{{ $program->id }}">{{ $program->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full">
                    <label for="grade_level" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-school mr-2"></i>
                        Applied to Year Level
                    </label>
                    <select name="grade_level" id="grade_level"
                        class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]">
                        <option value="">All Year Levels</option>
                        <option value="Grade 11">Grade 11</option>
                        <option value="Grade 12">Grade 12</option>
                    </select>
                </div>

                <div class="w-full">
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-usd-circle mr-2"></i>
                        Amount <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="amount" id="amount" required min="0" step="0.01"
                        placeholder="0.00"
                        class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]">
                </div>
            </div>

        </form>

        <x-slot name="modal_buttons">
            <button id="edit-school-fee-modal-cancel-btn"
                class="bg-gray-50 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-100 hover:ring hover:ring-gray-200 transition duration-150">
                Cancel
            </button>
            {{-- This button will acts as the submit button --}}
            <button type="submit" form="edit-school-fee-modal-form" id="edit-school-fee-submit-btn"
                class="bg-[#199BCF] py-2 px-3 rounded-xl text-[14px] font-semibold gap-2 text-white hover:ring hover:ring-[#C8A165]/20 hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                Update Fee
            </button>
        </x-slot>

    </x-modal>

    {{-- delete school fee --}}
    <x-modal modal_id="delete-school-fee-modal" modal_name="Delete School Fee" close_btn_id="delete-school-fee-close-btn"
        modal_container_id="modal-container-delete-school-fee">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-trash flex justify-center items-center text-red-500'></i>
        </x-slot>

        <div class="p-6">
            <div class="flex flex-col items-center space-y-4">
                <div class="text-center">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Confirm Deletion</h3>
                    <p class="text-gray-600">Are you sure you want to delete this school fee? This action cannot be undone.
                    </p>
                </div>
            </div>
        </div>

        <x-slot name="modal_buttons">
            <button id="delete-school-fee-cancel-btn"
                class="bg-gray-50 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-100 hover:ring hover:ring-gray-200 transition duration-150">
                Cancel
            </button>
            <form id="delete-school-fee-form" class="inline">
                @csrf
                <button type="submit" id="delete-school-fee-submit-btn"
                    class="bg-red-500 text-[14px] px-3 py-2 rounded-xl text-white font-bold hover:ring hover:ring-red-200 hover:bg-red-400 transition duration-150 shadow-sm hover:scale-95">
                    Delete Fee
                </button>
            </form>
        </x-slot>

    </x-modal>

    {{-- create discount --}}
    <x-modal modal_id="create-discount-modal" modal_name="Create Discount" close_btn_id="create-discount-modal-close-btn"
        modal_container_id="modal-container-6">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-percentage flex justify-center items-center'></i>
        </x-slot>

        <form id="create-discount-modal-form" class="p-6">
            @csrf

            <div class="flex flex-col justify-center items-center space-y-4">

                <div class="w-full">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-tags mr-2"></i>
                        Discount Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" required
                        placeholder="e.g., Early Bird Discount, Senior Citizen Discount"
                        class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]">
                </div>

                <div class="w-full">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-document mr-2"></i>
                        Description
                    </label>
                    <textarea name="description" id="description" rows="3" placeholder="Optional description of the discount"
                        class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]"></textarea>
                </div>

                <div class="w-full">
                    <label for="discount_type" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-percentage mr-2"></i>
                        Discount Type <span class="text-red-500">*</span>
                    </label>
                    <select name="discount_type" id="discount_type" required
                        class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]">
                        <option value="">Select Type</option>
                        <option value="percentage">Percentage</option>
                        <option value="fixed">Fixed Amount</option>
                    </select>
                </div>

                <div class="w-full">
                    <label for="discount_value" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-usd-circle mr-2"></i>
                        Discount Value <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="discount_value" id="discount_value" required min="0"
                        step="0.01" placeholder="0.00"
                        class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]">
                    <p class="text-sm text-gray-500 mt-1" id="value-help">Enter the discount value</p>
                </div>

                <div class="w-full">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active"
                            class="mr-2 w-4 h-4 text-[#199BCF] border-[#199BCF]/30 rounded focus:ring-[#199BCF]/20">
                        <span class="text-sm text-gray-700">Active</span>
                    </label>
                </div>
            </div>

        </form>

        <x-slot name="modal_buttons">
            <button id="create-discount-modal-cancel-btn"
                class="bg-gray-50 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-100 hover:ring hover:ring-gray-200 transition duration-150">
                Cancel
            </button>
            {{-- This button will acts as the submit button --}}
            <button type="submit" form="create-discount-modal-form" id="create-discount-submit-btn"
                class="bg-[#199BCF] py-2 px-3 rounded-xl text-[14px] font-semibold gap-2 text-white hover:ring hover:ring-[#C8A165]/20 hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                Create Discount
            </button>
        </x-slot>

    </x-modal>

    {{-- edit discount --}}
    <x-modal modal_id="edit-discount-modal" modal_name="Edit Discount" close_btn_id="edit-discount-modal-close-btn"
        modal_container_id="modal-container-7">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-edit flex justify-center items-center'></i>
        </x-slot>

        <form id="edit-discount-modal-form" class="p-6">
            @csrf

            <div class="flex flex-col justify-center items-center space-y-4">

                <div class="w-full">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-tags mr-2"></i>
                        Discount Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" required
                        placeholder="e.g., Early Bird Discount, Senior Citizen Discount"
                        class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]">
                </div>

                <div class="w-full">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-document mr-2"></i>
                        Description
                    </label>
                    <textarea name="description" id="description" rows="3" placeholder="Optional description of the discount"
                        class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]"></textarea>
                </div>

                <div class="w-full">
                    <label for="discount_type" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-percentage mr-2"></i>
                        Discount Type <span class="text-red-500">*</span>
                    </label>
                    <select name="discount_type" id="discount_type" required
                        class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]">
                        <option value="">Select Type</option>
                        <option value="percentage">Percentage</option>
                        <option value="fixed">Fixed Amount</option>
                    </select>
                </div>

                <div class="w-full">
                    <label for="discount_value" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-usd-circle mr-2"></i>
                        Discount Value <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="discount_value" id="discount_value" required min="0"
                        step="0.01" placeholder="0.00"
                        class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]">
                    <p class="text-sm text-gray-500 mt-1" id="value-help">Enter the discount value</p>
                </div>

                <div class="w-full">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active"
                            class="mr-2 w-4 h-4 text-[#199BCF] border-[#199BCF]/30 rounded focus:ring-[#199BCF]/20">
                        <span class="text-sm text-gray-700">Active</span>
                    </label>
                </div>
            </div>

        </form>

        <x-slot name="modal_buttons">
            <button id="edit-discount-modal-cancel-btn"
                class="bg-gray-50 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-100 hover:ring hover:ring-gray-200 transition duration-150">
                Cancel
            </button>
            {{-- This button will acts as the submit button --}}
            <button type="submit" form="edit-discount-modal-form" id="edit-discount-submit-btn"
                class="bg-[#199BCF] py-2 px-3 rounded-xl text-[14px] font-semibold gap-2 text-white hover:ring hover:ring-[#C8A165]/20 hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                Update Discount
            </button>
        </x-slot>

    </x-modal>

    {{-- delete discount --}}
    <x-modal modal_id="delete-discount-modal" modal_name="Delete Discount" close_btn_id="delete-discount-close-btn"
        modal_container_id="modal-container-delete-discount">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-trash flex justify-center items-center text-red-500'></i>
        </x-slot>

        <div class="p-6">
            <div class="flex flex-col items-center space-y-4">
                <div class="text-center">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Confirm Deletion</h3>
                    <p class="text-gray-600">Are you sure you want to delete this discount? This action cannot be undone.
                    </p>
                </div>
            </div>
        </div>

        <x-slot name="modal_buttons">
            <button id="delete-discount-cancel-btn"
                class="bg-gray-50 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-100 hover:ring hover:ring-gray-200 transition duration-150">
                Cancel
            </button>
            <form id="delete-discount-form" class="inline">
                @csrf
                <button type="submit" id="delete-discount-submit-btn"
                    class="bg-red-500 text-[14px] px-3 py-2 rounded-xl text-white font-bold hover:ring hover:ring-red-200 hover:bg-red-400 transition duration-150 shadow-sm hover:scale-95">
                    Delete Discount
                </button>
            </form>
        </x-slot>

    </x-modal>

    {{-- toggle discount --}}
    <x-modal modal_id="toggle-discount-modal" modal_name="Toggle Discount Status"
        close_btn_id="toggle-discount-close-btn" modal_container_id="modal-container-toggle-discount">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-toggle-on flex justify-center items-center text-yellow-500'></i>
        </x-slot>

        <div class="p-6">
            <div class="flex flex-col items-center space-y-4">
                <div class="text-center">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Toggle Discount Status</h3>
                    <p class="text-gray-600">Are you sure you want to change the status of this discount?</p>
                    <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <p class="text-sm text-yellow-800">
                            <strong>Note:</strong> This will activate or deactivate the discount for all future
                            applications.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <x-slot name="modal_buttons">
            <button id="toggle-discount-cancel-btn"
                class="bg-gray-50 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-100 hover:ring hover:ring-gray-200 transition duration-150">
                Cancel
            </button>
            <form id="toggle-discount-form" class="inline">
                @csrf
                <button type="submit" id="toggle-discount-submit-btn"
                    class="bg-yellow-500 text-[14px] px-3 py-2 rounded-xl text-white font-bold hover:ring hover:ring-yellow-200 hover:bg-yellow-400 transition duration-150 shadow-sm hover:scale-95">
                    Toggle Status
                </button>
            </form>
        </x-slot>

    </x-modal>
@endsection
@section('header')
    <div class="flex flex-row justify-between items-start text-start px-[14px] py-2">
        <div>
            <h1 class="text-[20px] font-black">School Fees Management</h1>
            <p class="text-[14px]  text-gray-900/60">View and manage school fees, invoices, and payment history.
            </p>
        </div>
        <div class="flex flex-row items-center gap-2 h-full">
            <label for="academic-term-selector" class="text-sm font-medium text-gray-600">Academic Term:</label>
            <select id="academic-term-selector"
                class="border border-gray-300 rounded-lg px-3 py-1 text-sm bg-white hover:bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                <option value="current" {{ !request()->has('term_id') ? 'selected' : '' }}>Current Term</option>
                @foreach (\App\Models\AcademicTerms::where('is_active', false)->orderBy('year', 'desc')->orderBy('semester', 'desc')->get() as $term)
                    <option value="{{ $term->id }}" {{ request()->input('term_id') == $term->id ? 'selected' : '' }}>
                        {{ $term->getFullNameAttribute() }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
@endsection
@section('stat')
    <div class="flex justify-center items-center">
        <div
            class="flex flex-col justify-center items-center flex-grow px-10 pb-10 pt-2 bg-gradient-to-br from-[#199BCF] to-[#1A3165] rounded-xl shadow-[#199BCF]/30 shadow-xl gap-2 text-white">

            <div class="flex flex-row items-center justify-between w-full gap-4 py-2 rounded-lg ">

                <div class="flex flex-col items-start justify-end gap-2 pt-4">
                    <h1 class="text-[36px] font-black" id="section_name">Financial Summary</h1>
                    <p class="text-[16px]  text-white/60">School fees, invoices, and payment tracking for the current
                        academic
                        term
                    </p>
                </div>

                <div class="flex flex-col items-end justify-center">
                    <div class="relative flex flex-row justify-center items-center gap-2">
                        <button id="set-down-payment-btn"
                            class="p-2 mt-1 rounded-lg hover:bg-[#f8f8f8]/20 transition duration-200"><i
                                class="fi fi-rs-pencil text-[16px] flex justify-center items-center"></i></button>

                        @if (isset($schoolSetting->down_payment))
                            <p id="studentCount" class="text-[48px] font-bold ">
                                ₱{{ number_format($schoolSetting->down_payment) }}</p>
                        @else
                            <p id="studentCount" class="text-[48px] font-bold ">
                                ₱0</p>
                            <div id="mark"
                                class="absolute right-0 top-3 border border-white rounded-full w-[12px] h-[12px] bg-red-500">
                            </div>
                        @endif


                    </div>
                    <div class="flex flex-row justify-center items-center opacity-70 gap-2 text-[14px]">
                        <p class="text-[16px]">Down payment</p>
                    </div>
                </div>


            </div>
            <div class="flex flex-row justify-center items-center w-full gap-4">
                <div
                    class="flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-6 py-4 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-150">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-rr-usd-circle flex justify-center items-center"></i>
                        <p class="text-[14px]">Total Fee Amount</p>
                    </div>
                    <p id="totalSchoolFeesDisplay" class="font-bold text-[24px]">₱{{ number_format($totalSchoolFees) }}
                    </p>
                    <p class="font-medium text-[14px]">All school fees combined</p>
                </div>

                <div
                    class="flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-6 py-4 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-300">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-rr-receipt flex flex-row justify-center items-center"></i>
                        <p class="text-[14px]">Total Invoices</p>
                    </div>
                    <p id="totalInvoicesDisplay" class="font-bold text-[24px]">{{ $totalInvoices }}</p>
                    <p class="font-medium text-[14px]">Generated invoices</p>
                </div>

                <div
                    class="flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-6 py-4 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-300">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-rr-check-circle flex justify-center items-center"></i>
                        <p class="text-[14px]">Paid Invoices</p>
                    </div>
                    <p id="paidInvoicesDisplay" class="font-bold text-[24px]">{{ $paidInvoices }}</p>
                    <p class="font-medium text-[14px]">Successfully paid</p>
                </div>
            </div>

            <div class="flex flex-row justify-center items-center w-full gap-4 mt-2">
                <div
                    class="flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-6 py-4 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-300">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-rr-clock flex justify-center items-center"></i>
                        <p class="text-[14px]">Pending Invoices</p>
                    </div>
                    <p id="pendingInvoicesDisplay" class="font-bold text-[24px]">{{ $pendingInvoices }}</p>
                    <p class="font-medium text-[14px]">Awaiting payment</p>
                </div>

                <div
                    class="flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-6 py-4 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-300">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-rr-money flex justify-center items-center"></i>
                        <p class="text-[14px]">Total Revenue</p>
                    </div>
                    <p class="font-bold text-[24px]">₱{{ number_format($totalRevenue) }}</p>
                    <p class="font-medium text-[14px]">From paid invoices</p>
                </div>

                <div
                    class="flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-6 py-4 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-300">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2 ">
                        <i class="fi fi-rr-calendar flex justify-center items-center"></i>
                        <p class="text-[14px] truncate">Due Date</p>
                    </div>
                    <p id="dueDateDisplay" class="font-bold text-[24px]">{{ $schoolSetting->due_day_of_month ?? 10 }}th
                        of
                        Month</p>
                    <button id="set-due-date-btn"
                        class="flex flex-row justify-center items-center font-bold text-[14px] bg-gray-50/5 hover:bg-gray-50/15 w-1/2 rounded-md transition duration-200 gap-2">
                        <i class="fi fi-rs-pencil text-[12px] flex justify-center items-center"></i>Edit
                    </button>

                </div>
            </div>



        </div>
    </div>
@endsection
@section('content')
    <x-alert />

    <div
        class="px-5 text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:text-gray-400 dark:border-gray-300">
        <ul class="flex flex-wrap -mb-px">

            @can('view school fees')
                <li class="me-2">
                    <a href="{{ route('school-fees.index') }}"
                        class="inline-block p-4 border-b-2 rounded-t-lg 
              {{ Route::is('school-fees.index') ? 'text-blue-600 border-blue-600 dark:text-blue-500 dark:border-blue-500' : 'border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300' }}">
                        School Fees
                    </a>
                </li>
            @endcan


            @can('view invoice')
                <li class="me-2">
                    <a href="{{ route('school-fees.invoices') }}"
                        class="inline-block p-4 border-b-2 rounded-t-lg 
              {{ Route::is('school-fees.invoices') ? 'text-blue-600 border-blue-600 dark:text-blue-500 dark:border-blue-500' : 'border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300' }}">
                        Invoices
                    </a>
                @endcan
            </li>

            @can('view payment history')
                <li class="me-2">
                    <a href="{{ route('school-fees.payments') }}"
                        class="inline-block p-4 border-b-2 rounded-t-lg 
              {{ Route::is('school-fees.payments') ? 'text-blue-600 border-blue-600 dark:text-blue-500 dark:border-blue-500' : 'border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300' }}">
                        Payment History
                    </a>
                </li>
            @endcan

            <li class="me-2">
                <a href="{{ route('school-fees.discounts') }}"
                    class="inline-block p-4 border-b-2 rounded-t-lg 
              {{ Route::is('school-fees.discounts') ? 'text-blue-600 border-blue-600 dark:text-blue-500 dark:border-blue-500' : 'border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300' }}">
                    Discounts
                </a>
            </li>




        </ul>
    </div>

    @if (Route::is('school-fees.discounts'))
        <div
            class="flex flex-col justify-start items-start flex-grow p-5 space-y-2 bg-[#f8f8f8] rounded-xl shadow-md border border-[#1e1e1e]/10">
            <div class="flex flex-col justify-center my-2 items-center w-full">
                <span class="font-semibold text-[18px]">
                    Discounts Management
                </span>
                <span class="font-medium text-gray-400 text-[14px]">
                    Manage discount types and values
                </span>
            </div>

            <div class="flex flex-row justify-between items-center w-full h-full py-2">
                <div class="flex flex-row justify-between w-2/3 items-center gap-4">
                    <label for="discount-search"
                        class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-2 gap-2 w-[40%] hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm">
                        <i class="fi fi-rs-search flex justify-center items-center text-[#1e1e1e]/60 text-[16px]"></i>
                        <input type="search" name="" id="discount-search"
                            class="my-custom-search bg-transparent outline-none text-[14px] w-full peer"
                            placeholder="Search by name or description">
                        <button id="clear-discount-btn"
                            class="clear-btn flex justify-center items-center peer-placeholder-shown:hidden peer-not-placeholder-shown:block">
                            <i class="fi fi-rs-cross-small text-[18px] flex justify-center items-center"></i>
                        </button>
                    </label>

                    <div class="flex flex-row justify-start items-center w-full gap-2">
                        <div
                            class="flex flex-row justify-between items-center rounded-lg border border-[#1e1e1e]/10 bg-gray-100 px-3 py-2 gap-2 focus-within:bg-gray-200 focus-within:border-[#1e1e1e]/15 hover:bg-gray-200 hover:border-[#1e1e1e]/15 transition duration-150 shadow-sm">
                            <select name="" id="type-selection"
                                class="appearance-none bg-transparent text-[14px] font-medium text-gray-700 w-full cursor-pointer">
                                <option value="" selected>All Types</option>
                                <option value="percentage" data-type="percentage">Percentage</option>
                                <option value="fixed" data-type="fixed">Fixed Amount</option>
                            </select>
                            <i id="clear-type-filter-btn"
                                class="fi fi-rr-caret-down text-gray-500 flex justify-center items-center cursor-pointer"></i>
                        </div>

                        <div
                            class="flex flex-row justify-between items-center rounded-lg border border-[#1e1e1e]/10 bg-gray-100 px-3 py-2 gap-2 focus-within:bg-gray-200 focus-within:border-[#1e1e1e]/15 hover:bg-gray-200 hover:border-[#1e1e1e]/15 transition duration-150 shadow-sm">
                            <select name="" id="status-selection-discount"
                                class="appearance-none bg-transparent text-[14px] font-medium text-gray-700 w-full cursor-pointer">
                                <option value="" selected>All Status</option>
                                <option value="active" data-status="active">Active</option>
                                <option value="inactive" data-status="inactive">Inactive</option>
                            </select>
                            <i id="clear-status-filter-btn-discount"
                                class="fi fi-rr-caret-down text-gray-500 flex justify-center items-center cursor-pointer"></i>
                        </div>
                    </div>
                </div>

                <button id="create-discount-btn"
                    class="self-end flex flex-row justify-center items-center bg-[#199BCF] py-2 px-3 rounded-xl text-[16px] font-semibold gap-2 text-white hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                    <i class="fi fi-sr-square-plus opacity-70 flex justify-center items-center text-[18px]"></i>
                    Add Discount
                </button>
            </div>

            <div class="w-full">
                <table id="discount-table" class="w-full table-fixed">
                    <thead class="text-[14px]">
                        <tr>
                            <th class="w-[20%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                <span class="mr-2 font-medium opacity-60 cursor-pointer">#</span>
                                <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                            </th>
                            <th class="w-[20%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                <span class="mr-2 font-medium opacity-60 cursor-pointer">Name</span>
                                <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                            </th>
                            <th class="w-[25%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                <span class="mr-2 font-medium opacity-60 cursor-pointer">Description</span>
                                <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                            </th>
                            <th class="w-[15%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                <span class="mr-2 font-medium opacity-60 cursor-pointer">Type</span>
                                <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                            </th>
                            <th class="w-[15%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                <span class="mr-2 font-medium opacity-60 cursor-pointer">Value</span>
                                <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                            </th>
                            <th class="w-[10%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                <span class="mr-2 font-medium opacity-60 cursor-pointer">Status</span>
                                <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                            </th>
                            <th class="w-[15%] text-center bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                <span class="mr-2 font-medium opacity-60 select-none">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    @if (Route::is('school-fees.index'))
        <div class="flex flex-row justify-center items-start gap-4">
            <div
                class="flex flex-col justify-start items-start flex-grow p-5 space-y-2 bg-[#f8f8f8] rounded-xl shadow-md border border-[#1e1e1e]/10 w-[40%]">
                <div class="flex flex-col justify-center my-2 items-center w-full">
                    <span class="font-semibold text-[18px]">
                        School Fees
                    </span>
                    <span class="font-medium text-gray-400 text-[14px]">
                        Manage school fees and their amounts
                    </span>
                </div>
                <div class="flex flex-row justify-between items-center w-full h-full py-2">
                    <div class="flex flex-row justify-between w-2/3 items-center gap-4">

                        <label for="school-fee-search"
                            class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-2 gap-2 w-[40%] hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm">
                            <i class="fi fi-rs-search flex justify-center items-center text-[#1e1e1e]/60 text-[16px]"></i>
                            <input type="search" name="" id="school-fee-search"
                                class="my-custom-search bg-transparent outline-none text-[14px] w-full peer"
                                placeholder="Search by name and amount">
                            <button id="clear-btn"
                                class="clear-btn flex justify-center items-center peer-placeholder-shown:hidden peer-not-placeholder-shown:block">
                                <i class="fi fi-rs-cross-small text-[18px] flex justify-center items-center"></i>
                            </button>
                        </label>
                        <div class="flex flex-row justify-start items-center w-full gap-2">
                            <div
                                class="flex flex-row justify-between items-center rounded-lg border border-[#1e1e1e]/10 bg-gray-100 px-3 py-2 gap-2 hover:bg-gray-200 hover:border-[#1e1e1e]/15 transition-all ease-in-out duration-150 shadow-sm">
                                <select name="pageLength" id="page-length-selection"
                                    class="appearance-none bg-transparent text-[14px] font-medium text-gray-700 h-full w-full cursor-pointer">
                                    <option selected disabled>Entries</option>
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="150">150</option>
                                    <option value="200">200</option>
                                </select>
                                <i id="clear-gender-filter-btn"
                                    class="fi fi-rr-caret-down text-gray-500 flex justify-center items-center"></i>
                            </div>
                            <div
                                class="flex flex-row justify-between items-center rounded-lg border border-[#1e1e1e]/10 bg-gray-100 px-3 py-2 gap-2 focus-within:bg-gray-200 focus-within:border-[#1e1e1e]/15 hover:bg-gray-200 hover:border-[#1e1e1e]/15 transition duration-150 shadow-sm">
                                <select name="" id="program_selection"
                                    class="appearance-none bg-transparent text-[14px] font-medium text-gray-700 w-full cursor-pointer">
                                    <option value="" selected disabled>Program</option>

                                    @foreach ($programs as $program)
                                        <option value="" data-id="{{ $program->id }}">{{ $program->code }}
                                        </option>
                                    @endforeach
                                    <option value="" data-id="">All Programs</option>
                                </select>
                                <i id="clear-program-filter-btn"
                                    class="fi fi-rr-caret-down text-gray-500 flex justify-center items-center cursor-pointer"></i>
                            </div>


                            <div id="grade_selection_container"
                                class="flex flex-row justify-between items-center rounded-lg border border-[#1e1e1e]/10 bg-gray-100 px-3 py-2 gap-2 hover:bg-gray-200 hover:border-[#1e1e1e]/15 transition-all ease-in-out duration-150 shadow-sm">

                                <select name="grade_selection" id="grade_selection"
                                    class="appearance-none bg-transparent text-[14px] font-medium text-gray-700 h-full w-full cursor-pointer">
                                    <option disabled selected>Year Level</option>
                                    <option data-grade-level="Grade 11">Grade 11</option>
                                    <option data-grade-level="Grade 12">Grade 12</option>
                                    <option data-grade-level="">All Year Level</option>
                                </select>
                                <i id="clear-grade-filter-btn"
                                    class="fi fi-rr-caret-down text-gray-500 flex justify-center items-center cursor-pointer"></i>

                            </div>


                        </div>
                    </div>
                    @can('create school fees')
                        <button id="create-school-fee-modal-btn"
                            class="self-end flex flex-row justify-center items-center bg-[#199BCF] py-2 px-3 rounded-xl text-[16px] font-semibold gap-2 text-white hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                            <i class="fi fi-sr-square-plus opacity-70 flex justify-center items-center text-[18px]"></i>
                            Create new fee
                        </button>
                    @endcan


                </div>

                <div class="w-full">
                    <table id="school-fee-table" class="w-full table-fixed">
                        <thead class="text-[14px]">
                            <tr>
                                <th class="w-[3%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-2 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">#</span>
                                </th>

                                <th class="w-[10%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Name</span>
                                    <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                                </th>

                                <th class="w-[45%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Applied to (Program)</span>
                                    <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                                </th>

                                <th class="w-[15%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Applied to (Year Level)</span>
                                    <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                                </th>
                                <th class="w-[15%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Amount</span>
                                    <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                                </th>

                                <th class="w-[12%] text-center bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 select-none">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- <tr class="border-t-[1px] border-[#1e1e1e]/15 w-full rounded-md"></tr> --}}
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    @endif

    @if (Route::is('school-fees.invoices'))
        <div class="flex flex-row justify-center items-start gap-4">
            <div
                class="flex flex-col justify-start items-start flex-grow p-5 space-y-2 bg-[#f8f8f8] rounded-xl shadow-md border border-[#1e1e1e]/10 w-[40%]">
                <div class="flex flex-col my-2 justify-center items-center w-full">
                    <span class="font-semibold text-[18px]">
                        Invoices
                    </span>
                    <span class="font-medium text-gray-400 text-[14px]">
                        Student's invoice list across different academic terms
                    </span>
                </div>
                <div class="flex flex-row justify-between items-center w-full h-full py-2">
                    <div class="flex flex-row justify-between w-3/4 items-center gap-4">

                        <label for="invoice-search"
                            class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-2 gap-2 w-[40%] hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm">
                            <i class="fi fi-rs-search flex justify-center items-center text-[#1e1e1e]/60 text-[16px]"></i>
                            <input type="search" name="" id="invoice-search"
                                class="my-custom-search bg-transparent outline-none text-[14px] w-full peer"
                                placeholder="Search by Invoice no., Student, etc.">
                            <button id="clear-btn"
                                class="clear-btn flex justify-center items-center peer-placeholder-shown:hidden peer-not-placeholder-shown:block">
                                <i class="fi fi-rs-cross-small text-[18px] flex justify-center items-center"></i>
                            </button>
                        </label>
                        <div class="flex flex-row justify-start items-center w-full gap-2">
                            <div
                                class="flex flex-row justify-between items-center rounded-lg border border-[#1e1e1e]/10 bg-gray-100 px-3 py-2 gap-2 hover:bg-gray-200 hover:border-[#1e1e1e]/15 transition-all ease-in-out duration-150 shadow-sm">
                                <select name="pageLength" id="page-length-selection"
                                    class="appearance-none bg-transparent text-[14px] font-medium text-gray-700 h-full w-full cursor-pointer">
                                    <option selected disabled>Entries</option>
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="150">150</option>
                                    <option value="200">200</option>
                                </select>
                                <i id="clear-gender-filter-btn"
                                    class="fi fi-rr-caret-down text-gray-500 flex justify-center items-center"></i>
                            </div>

                            <div id="status-selection-container"
                                class="flex flex-row justify-between items-center rounded-lg border border-[#1e1e1e]/10 bg-gray-100 px-3 py-2 gap-2 hover:bg-gray-200 hover:border-[#1e1e1e]/15 transition-all ease-in-out duration-150 shadow-sm">

                                <select name="status-selection" id="status-selection"
                                    class="appearance-none bg-transparent text-[14px] font-medium text-gray-700 h-full w-full cursor-pointer">
                                    <option value="" disabled selected>Status</option>
                                    <option value="" data-status="paid">Paid</option>
                                    <option value="" data-status="unpaid">Unpaid</option>
                                    <option value="" data-status="partially_paid">Partially Paid</option>
                                    <option value="" data-status="overdue">Overdue</option>

                                </select>
                                <i id="clear-status-filter-btn"
                                    class="fi fi-rr-caret-down text-gray-500 flex justify-center items-center cursor-pointer"></i>

                            </div>

                            <div id="method-selection-container"
                                class="flex flex-row justify-between items-center rounded-lg border border-[#1e1e1e]/10 bg-gray-100 px-3 py-2 gap-2 focus-within:bg-gray-200 focus-within:border-[#1e1e1e]/15 hover:bg-gray-200 hover:border-[#1e1e1e]/15 transition duration-150 shadow-sm">
                                <select name="method-selection" id="method-selection"
                                    class="appearance-none bg-transparent text-[14px] font-medium text-gray-700 w-full cursor-pointer">
                                    <option value="" selected disabled>Method</option>
                                    <option value="installment" data-method="installment">Installment</option>
                                    <option value="full" data-method="full">Full</option>
                                    <option value="" data-method="flexible">Not Set</option>


                                </select>
                                <i id="clear-method-filter-btn"
                                    class="fi fi-rr-caret-down text-gray-500 flex justify-center items-center cursor-pointer"></i>
                            </div>

                            <div id="term-selection-container"
                                class="flex flex-row justify-between items-center rounded-lg border border-[#1e1e1e]/10 bg-gray-100 px-3 py-2 gap-2 hover:bg-gray-200 hover:border-[#1e1e1e]/15 transition-all ease-in-out duration-150 shadow-sm">

                                <select name="term-selection" id="term-selection"
                                    class="appearance-none bg-transparent text-[14px] font-medium text-gray-700 h-full w-full cursor-pointer">
                                    <option value="" disabled selected>Academic Terms</option>
                                    @foreach ($allTerm as $term)
                                        <option value="" data-term="{{ $term->id }}">
                                            {{ $term->year . ' - ' . $term->semester }}</option>
                                    @endforeach
                                </select>
                                <i id="clear-term-filter-btn"
                                    class="fi fi-rr-caret-down text-gray-500 flex justify-center items-center cursor-pointer"></i>

                            </div>


                        </div>
                    </div>

                    @can('create invoice')
                        <button id="create-invoice-modal-btn"
                            class="self-end flex flex-row justify-center items-center bg-[#199BCF] py-2 px-3 rounded-xl text-[16px] font-semibold gap-2 text-white hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                            <i class="fi fi-sr-square-plus opacity-70 flex justify-center items-center text-[18px]"></i>
                            Assign Invoice
                        </button>
                    @endcan

                </div>

                <div class="w-full">
                    <table id="invoices" class="w-full table-fixed">
                        <thead class="text-[14px]">
                            <tr>
                                <th class="w-[3%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-2 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">#</span>
                                </th>

                                <th class="w-[12%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Invoice No.</span>
                                    <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                                </th>

                                <th class="w-[15%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Student</span>
                                    <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                                </th>

                                <th class="w-[12%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Term</span>
                                    <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                                </th>

                                <th class="w-[10%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Method</span>
                                    <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                                </th>

                                <th class="w-[10%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Status</span>
                                    <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                                </th>

                                <th class="w-[12%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Total</span>
                                    <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                                </th>

                                <th class="w-[12%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Balance</span>
                                    <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                                </th>

                                <th class="w-[15%] text-center bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 select-none">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- <tr class="border-t-[1px] border-[#1e1e1e]/15 w-full rounded-md"></tr> --}}
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    @endif

    @if (Route::is('school-fees.payments'))
        <div class="flex flex-row justify-center items-start gap-4">
            <div
                class="flex flex-col justify-start items-start flex-grow p-5 space-y-4 bg-[#f8f8f8] rounded-xl shadow-md border border-[#1e1e1e]/10 w-[40%]">
                <div class="flex flex-col justify-center items-center w-full my-2">
                    <span class="font-semibold text-[18px]">
                        Invoice History
                    </span>
                    <span class="font-medium text-gray-400 text-[14px]">
                        Student's invoice history across different academic terms
                    </span>
                </div>
                <div class="flex flex-row justify-between items-center w-full">
                    <div class="w-full flex flex-row justify-between items-center gap-4">
                        <label for="invoice-search"
                            class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-2 gap-2 w-[40%] hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm">
                            <i class="fi fi-rs-search flex justify-center items-center text-[#1e1e1e]/60 text-[16px]"></i>
                            <input type="search" name="search" id="payment-history-search"
                                class="my-custom-search bg-transparent outline-none text-[14px] w-full peer"
                                placeholder="Search by Reference no., Student, etc.">
                            <button id="clear-btn"
                                class="clear-btn flex justify-center items-center peer-placeholder-shown:hidden peer-not-placeholder-shown:block">
                                <i class="fi fi-rs-cross-small text-[18px] flex justify-center items-center"></i>
                            </button>
                        </label>
                    </div>
                </div>

                <div class="w-full">
                    <table id="payments" class="w-full table-fixed">
                        <thead class="text-[14px]">
                            <tr>
                                <th class="w-[4%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-2 py-2">
                                    <span class="mr-2 font-medium opacity-60">#</span>
                                </th>
                                <th class="w-[12%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60">Invoice No.</span>
                                </th>
                                <th class="w-[18%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60">Total</span>
                                </th>
                                <th class="w-[16%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60">Method</span>
                                </th>
                                <th class="w-[16%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60">Status</span>
                                </th>
                                <th class="w-[14%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60">Student</span>
                                </th>
                                <th class="w-[20%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60">Term</span>
                                </th>
                                <th class="w-[12%] text-center bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script type="module">
        import {
            dropDown
        } from "{{ asset('js/dropDown.js') }}";
        import {
            clearSearch
        } from "{{ asset('js/clearSearch.js') }}"
        import {
            initModal
        } from "{{ asset('js/modal.js') }}";
        import {
            showAlert
        } from "{{ asset('js/alert.js') }}";
        import {
            initCustomDataTable
        } from "{{ asset('js/initTable.js') }}";
        import {
            showLoader,
            hideLoader
        } from "{{ asset('js/loader.js') }}";

        let selectedGrade = '';
        let selectedProgram = '';
        let selectedPageLength = '';
        let selectedStatus = '';
        let selectedMethod = '';
        let selectedTerm = '';

        // Set global variables for initTable.js
        window.selectedGrade = selectedGrade;
        window.selectedProgram = selectedProgram;
        window.selectedPageLength = selectedPageLength;
        window.selectedStatus = selectedStatus;
        window.selectedMethod = selectedMethod;
        window.selectedTerm = selectedTerm;

        // Academic Term Selector Change Handler
        document.getElementById('academic-term-selector').addEventListener('change', function() {
            const selectedTermId = this.value;
            const url = new URL(window.location);

            if (selectedTermId === 'current') {
                url.searchParams.delete('term_id');
            } else {
                url.searchParams.set('term_id', selectedTermId);
            }

            // Reload page with new term parameter
            window.location.href = url.toString();
        });

        // Global reusable filter functions
        function handleFilterChange(filterType, dataAttribute, globalVariable, table, selectElement, containerElement,
            clearBtnElement) {
            return function(e) {
                let selectedOption = e.target.selectedOptions[0];
                let value = selectedOption.getAttribute(dataAttribute);

                // Update global variable
                window[globalVariable] = value;

                // Update local variable if it exists
                if (typeof window[globalVariable] !== 'undefined') {
                    eval(`${globalVariable} = value`);
                }

                // Apply visual styling when filter is active
                if (value && value !== '') {
                    if (containerElement) {
                        containerElement.classList.remove('bg-gray-100');
                        containerElement.classList.add('bg-slate-50', 'border-slate-300', 'hover:bg-slate-100');
                    }

                    if (selectElement) {
                        selectElement.classList.remove('text-gray-700');
                        selectElement.classList.add('text-slate-800');
                    }

                    if (clearBtnElement) {
                        clearBtnElement.classList.remove('fi-rr-caret-down', 'text-gray-500');
                        clearBtnElement.classList.add('fi-bs-cross-small', 'cursor-pointer', 'text-slate-600');
                    }
                }

                // Redraw table
                if (table) {
                    table.draw();
                }
            };
        }

        // Reusable clear filter function
        function createClearFilterHandler(selectElement, containerElement, clearBtnElement, globalVariable, table,
            filterType) {
            if (!clearBtnElement || !selectElement) return;

            clearBtnElement.addEventListener('click', () => {
                // Reset visual styling
                if (containerElement) {
                    containerElement.classList.remove('bg-slate-50', 'border-slate-300', 'hover:bg-slate-100');
                    containerElement.classList.add('bg-gray-100');
                }

                if (clearBtnElement) {
                    clearBtnElement.classList.remove('fi-bs-cross-small', 'cursor-pointer', 'text-slate-600');
                    clearBtnElement.classList.add('fi-rr-caret-down', 'text-gray-500');
                }

                if (selectElement) {
                    selectElement.classList.remove('text-slate-800');
                    selectElement.classList.add('text-gray-700');
                    selectElement.selectedIndex = 0;
                }

                // Reset variables
                window[globalVariable] = '';
                if (typeof window[globalVariable] !== 'undefined') {
                    eval(`${globalVariable} = ''`);
                }

                // Redraw table
                if (table) {
                    table.draw();
                }
            });
        }

        document.addEventListener("DOMContentLoaded", function() {

            initModal('set-down-payment-modal', 'set-down-payment-btn', 'set-down-payment-close-btn',
                'set-down-payment-cancel-btn',
                'modal-container-3');
            initModal('set-due-date-modal', 'set-due-date-btn', 'set-due-date-close-btn',
                'set-due-date-cancel-btn',
                'modal-container-4');

            const currentPath = window.location.pathname;

            if (currentPath === '/school-fees') {
                initializeSchoolFeeTab();
            } else if (currentPath === '/school-fees/invoices') {
                initializeInvoiceTab();
            } else if (currentPath === '/school-fees/payments') {
                initializePaymentHistoryTab();
            } else if (currentPath === '/school-fees/discounts') {
                initializeDiscountTab();
            }


        });

        // Initialize edit school fee modals dynamically
        function initializeEditSchoolFeeModals() {
            document.querySelectorAll('.edit-school-fee-btn').forEach((button) => {
                let schoolFeeId = button.getAttribute('data-school-fee-id');
                let buttonId = `open-edit-modal-btn-${schoolFeeId}`;

                // Initialize modal for this specific button
                initModal('edit-school-fee-modal', buttonId, 'edit-school-fee-modal-close-btn',
                    'edit-school-fee-modal-cancel-btn', 'modal-container-5');

                button.addEventListener('click', () => {
                    // Clear any existing hidden inputs first
                    let form = document.getElementById('edit-school-fee-modal-form');
                    let existingInputs = form.querySelectorAll('input[name="school_fee_id"]');
                    existingInputs.forEach(input => input.remove());

                    // Add school fee ID as hidden input
                    let schoolFeeIdInput = document.createElement('input');
                    schoolFeeIdInput.type = 'hidden';
                    schoolFeeIdInput.value = schoolFeeId;
                    schoolFeeIdInput.name = "school_fee_id";
                    schoolFeeIdInput.id = "edit_school_fee_id";
                    form.appendChild(schoolFeeIdInput);

                    // Fetch school fee data and populate the form
                    showLoader();
                    fetch(`/school-fees/${schoolFeeId}`)
                        .then(response => response.json())
                        .then(data => {
                            hideLoader();
                            if (data.success && data.schoolFee) {
                                const schoolFee = data.schoolFee;

                                // Populate form fields
                                document.getElementById('edit-school-fee-modal-form').querySelector(
                                    'input[name="name"]').value = schoolFee.name || '';
                                document.getElementById('edit-school-fee-modal-form').querySelector(
                                    'select[name="program_id"]').value = schoolFee.program_id || '';
                                document.getElementById('edit-school-fee-modal-form').querySelector(
                                        'select[name="grade_level"]').value = schoolFee.grade_level ||
                                    '';
                                document.getElementById('edit-school-fee-modal-form').querySelector(
                                    'input[name="amount"]').value = schoolFee.amount || '';

                            } else {
                                showAlert('error', 'Error loading school fee: ' + data.error);
                            }
                        })
                        .catch(error => {
                            hideLoader();
                            showAlert('error', 'An error occurred while loading the school fee');
                        });
                });
            });
        }

        // Initialize delete school fee modals dynamically
        function initializeDeleteSchoolFeeModals() {
            document.querySelectorAll('.delete-school-fee-btn').forEach((button) => {
                let schoolFeeId = button.getAttribute('data-school-fee-id');
                let buttonId = `open-delete-modal-btn-${schoolFeeId}`;

                // Initialize modal for this specific button
                initModal('delete-school-fee-modal', buttonId, 'delete-school-fee-close-btn',
                    'delete-school-fee-cancel-btn', 'modal-container-delete-school-fee');

                button.addEventListener('click', () => {
                    // Clear any existing hidden inputs first
                    let form = document.getElementById('delete-school-fee-form');
                    let existingInputs = form.querySelectorAll('input[name="school_fee_id"]');
                    existingInputs.forEach(input => input.remove());

                    // Set the form action dynamically
                    form.action = `/school-fees/${schoolFeeId}`;

                    // Add school fee ID as hidden input
                    let schoolFeeIdInput = document.createElement('input');
                    schoolFeeIdInput.type = 'hidden';
                    schoolFeeIdInput.name = 'school_fee_id';
                    schoolFeeIdInput.value = schoolFeeId;
                    form.appendChild(schoolFeeIdInput);

                });
            });
        }

        // SCHOOL FEES
        function initializeSchoolFeeTab() {
            initModal('create-school-fee-modal', 'create-school-fee-modal-btn',
                'create-school-fee-modal-close-btn',
                'create-school-fee-modal-cancel-btn',
                'modal-container-1');
            initModal('edit-school-fee-modal', 'edit-school-fee-modal-btn',
                'edit-school-fee-modal-close-btn',
                'edit-school-fee-modal-cancel-btn',
                'modal-container-5');



            let schoolfeeTable = initCustomDataTable(
                'school-fee-table',
                `/getSchoolFees`,
                [{
                        data: 'index'
                    },
                    {
                        data: 'name',
                        render: DataTable.render.text()
                    },
                    {
                        data: 'applied_to_program',
                        render: DataTable.render.text()
                    },
                    {
                        data: 'applied_to_level',
                        render: DataTable.render.text()
                    },
                    {
                        data: 'amount',
                        render: DataTable.render.text()
                    },
                    {
                        data: 'id',
                        render: function(data, type, row) {
                            return `
                            <div class='flex flex-row justify-center items-center gap-2'>
                                <button type="button" id="open-edit-modal-btn-${data}"
                                    data-school-fee-id="${data}"
                                    class="edit-school-fee-btn group relative inline-flex items-center gap-2 bg-blue-100 text-blue-500 font-semibold p-2 rounded-xl hover:bg-blue-500 hover:ring hover:ring-blue-200 hover:text-white transition duration-150">
                                    <i class="fi fi-rr-edit text-[16px] flex justify-center items-center"></i>
                                </button>
                                <button type="button" id="open-delete-modal-btn-${data}"
                                    data-school-fee-id="${data}"
                                    class="delete-school-fee-btn group relative inline-flex items-center gap-2 bg-red-100 text-red-500 font-semibold p-2 rounded-xl hover:bg-red-500 hover:ring hover:ring-red-200 hover:text-white transition duration-150">
                                    <i class="fi fi-rr-trash text-[16px] flex justify-center items-center"></i>
                                </button>
                            </div>`;
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
                        width: '3%',
                        targets: 0,
                        className: 'text-center'
                    },
                    {
                        width: '20%',
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
                        width: '10%',
                        targets: 4
                    },
                    {
                        width: '15%',
                        targets: 5,
                        className: 'text-center'
                    }
                ]
            );

            clearSearch('clear-btn', 'school-fee-search', schoolfeeTable)

            let programSelection = document.querySelector('#program_selection');
            let gradeSelection = document.querySelector('#grade_selection');
            let pageLengthSelection = document.querySelector('#page-length-selection');

            let clearGradeFilterBtn = document.querySelector('#clear-grade-filter-btn');
            let gradeContainer = document.querySelector('#grade_selection_container');

            // Apply filter handlers using reusable functions
            programSelection.addEventListener('change', handleFilterChange('program', 'data-id', 'selectedProgram',
                schoolfeeTable, programSelection, null, document.querySelector('#clear-program-filter-btn')));
            gradeSelection.addEventListener('change', handleFilterChange('grade', 'data-grade-level', 'selectedGrade',
                schoolfeeTable, gradeSelection, gradeContainer, clearGradeFilterBtn));

            // Special handler for page length (different behavior)
            pageLengthSelection.addEventListener('change', (e) => {
                let selectedPageLength = parseInt(e.target.value, 10);
                schoolfeeTable.page.len(selectedPageLength).draw();
            })

            // Apply clear handlers to school fees table filters
            createClearFilterHandler(programSelection, null, document.querySelector('#clear-program-filter-btn'),
                'selectedProgram', schoolfeeTable, 'program');
            createClearFilterHandler(gradeSelection, gradeContainer, clearGradeFilterBtn, 'selectedGrade', schoolfeeTable,
                'grade');

            window.onload = function() {
                gradeSelection.selectedIndex = 0
                programSelection.selectedIndex = 0
                pageLengthSelection.selectedIndex = 0
            }

            // School Fee Form Submission
            document.getElementById('create-school-fee-modal-form').addEventListener('submit', function(e) {
                e.preventDefault();

                let form = e.target;
                let formData = new FormData(form);

                // Show loader
                showLoader("Creating school fee...");

                fetch('/school-fees', {
                        method: "POST",
                        body: formData,
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Accept": "application/json"
                        }
                    })
                    .then(response => {
                        return response.json();
                    })
                    .then(data => {
                        hideLoader();

                        if (data.id && data.success) {
                            // Reset form
                            form.reset();

                            // Close modal
                            closeModal('create-school-fee-modal', 'modal-container-1');

                            // Show success alert
                            showAlert('success', 'School fee created successfully!');

                            // Update total school fees display
                            if (data.totalSchoolFees !== undefined) {
                                const formattedAmount = Math.round(data.totalSchoolFees)
                                    .toLocaleString();
                                document.getElementById('totalSchoolFeesDisplay').textContent =
                                    `₱${formattedAmount}`;
                            }

                            // Refresh table
                            if (typeof schoolfeeTable !== 'undefined') {
                                schoolfeeTable.draw();
                            }

                        } else if (data.error) {
                            closeModal('create-school-fee-modal', 'modal-container-1');
                            showAlert('error', data.error);
                        } else if (data.message) {
                            closeModal('create-school-fee-modal', 'modal-container-1');
                            showAlert('error', data.message);
                        }
                    })
                    .catch(err => {
                        hideLoader();
                        closeModal('create-school-fee-modal', 'modal-container-1');
                        showAlert('error', 'Something went wrong while creating the school fee');
                    });
            });

            // Initialize edit and delete modals dynamically
            initializeEditSchoolFeeModals();
            initializeDeleteSchoolFeeModals();

            // Reinitialize modals after table draw
            schoolfeeTable.on('draw', function() {
                initializeEditSchoolFeeModals();
                initializeDeleteSchoolFeeModals();
            });

            // Edit school Fee Form Submission
            document.getElementById('edit-school-fee-modal-form').addEventListener('submit', function(e) {
                e.preventDefault();

                let form = e.target;
                let formData = new FormData(form);
                const schoolFeeId = formData.get('school_fee_id');

                if (!schoolFeeId) {
                    showAlert('error', 'School fee ID not found');
                    return;
                }

                // Add the school fee ID to the form data
                formData.append('_method', 'PUT');

                // Show loader
                showLoader("Updating school fee...");

                fetch(`/school-fees/${schoolFeeId}`, {
                        method: "POST",
                        body: formData,
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Accept": "application/json"
                        }
                    })
                    .then(response => {
                        return response.json();
                    })
                    .then(data => {
                        hideLoader();

                        if (data.success) {
                            // Reset form
                            form.reset();

                            // Remove hidden input
                            const hiddenInput = document.getElementById('edit_school_fee_id');
                            if (hiddenInput) {
                                hiddenInput.remove();
                            }

                            // Close modal
                            closeModal('edit-school-fee-modal', 'modal-container-5');

                            // Show success alert
                            showAlert('success', 'School fee updated successfully!');

                            // Update total school fees display
                            if (data.totalSchoolFees !== undefined) {
                                const formattedAmount = Math.round(data.totalSchoolFees)
                                    .toLocaleString();
                                document.getElementById('totalSchoolFeesDisplay').textContent =
                                    `₱${formattedAmount}`;
                            }

                            // Refresh table
                            if (typeof schoolfeeTable !== 'undefined') {
                                schoolfeeTable.draw();
                            }

                        } else if (data.error) {
                            closeModal('edit-school-fee-modal', 'modal-container-5');
                            showAlert('error', data.error);
                        } else if (data.message) {
                            closeModal('edit-school-fee-modal', 'modal-container-5');
                            showAlert('error', data.message);
                        }
                    })
                    .catch(err => {
                        hideLoader();
                        closeModal('edit-school-fee-modal', 'modal-container-5');
                        showAlert('error', 'Something went wrong while updating the school fee');
                    });
            });

            // Delete school fee form submission
            const deleteSchoolFeeForm = document.getElementById('delete-school-fee-form');
            if (deleteSchoolFeeForm) {
                deleteSchoolFeeForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(this);
                    const schoolFeeId = formData.get('school_fee_id');

                    showLoader();
                    fetch(`/school-fees/${schoolFeeId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            hideLoader();
                            if (data.success === false && data.has_invoice_items === true) {
                                showAlert('error', data.error);
                                closeModal('delete-school-fee-modal', 'modal-container-delete-school-fee');
                            } else if (data.success === true) {
                                showAlert('success', data.message);
                                schoolfeeTable.draw(); // Refresh the table

                                // Update total school fees display
                                fetch('/getSchoolFees', {
                                        method: 'GET',
                                        headers: {
                                            'Accept': 'application/json'
                                        }
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.data) {
                                            const totalAmount = data.data.reduce((sum, fee) => {
                                                const amount = parseFloat(fee.amount.replace(
                                                    /[₱,]/g, ''));
                                                return sum + amount;
                                            }, 0);
                                            const formattedAmount = Math.round(totalAmount)
                                                .toLocaleString();
                                            document.getElementById('totalSchoolFeesDisplay').textContent =
                                                `₱${formattedAmount}`;
                                        }
                                    });

                                // Close modal
                                document.getElementById('delete-school-fee-close-btn').click();
                            } else {
                                showAlert('error', data.message);
                            }
                        })
                        .catch(error => {
                            hideLoader();
                            showAlert('error', 'An error occurred while deleting the school fee');
                        });
                });
            }

        }

        function initializeInvoiceTab() {

            initModal('create-invoice-modal', 'create-invoice-modal-btn', 'create-invoice-modal-close-btn',
                'create-invoice-modal-cancel-btn',
                'modal-container-2');

            // Add custom cancel button handler for invoice modal
            document.getElementById('create-invoice-modal-cancel-btn').addEventListener('click', function() {
                closeModal('create-invoice-modal', 'modal-container-2');
                resetInvoiceForm();
            });

            // Add custom close button handler for invoice modal
            document.getElementById('create-invoice-modal-close-btn').addEventListener('click', function() {
                closeModal('create-invoice-modal', 'modal-container-2');
                resetInvoiceForm();
            });


            let invoiceTable = initCustomDataTable(
                'invoices',
                `/getInvoices`,
                [{
                        data: 'index',
                        width: '3%',
                        searchable: true
                    },
                    {
                        data: 'invoice_number',
                        width: '14%',
                        render: DataTable.render.text()
                    },
                    {
                        data: 'student',
                        width: '14%',
                        render: DataTable.render.text()
                    },
                    {
                        data: 'academic_term',
                        width: '15%',
                        render: DataTable.render.text()
                    },
                    {
                        data: 'payment_method',
                        width: '10%',
                        render: function(data, type, row) {
                            let badgeClass = '';
                            let badgeText = data;

                            switch (data) {
                                case 'installment':
                                    badgeClass = 'bg-green-100 text-green-800';
                                    badgeText = 'Installment';
                                    break;
                                case 'full':
                                    badgeClass = 'bg-yellow-100 text-yellow-800';
                                    badgeText = 'Full';
                                    break;
                                case 'Not Set':
                                default:
                                    badgeClass = 'bg-gray-200 text-gray-800';
                                    badgeText = 'Not Set';
                                    break;
                            }

                            return `<span class="px-2 py-1 rounded-full text-xs font-medium ${badgeClass}">${badgeText}</span>`;
                        }
                    },
                    {
                        data: 'status',
                        width: '10%',
                        render: function(data, type, row) {
                            let badgeClass = '';
                            let badgeText = data;

                            switch (data) {
                                case 'paid':
                                    badgeClass = 'bg-green-100 text-green-800';
                                    badgeText = 'Paid';
                                    break;
                                case 'partially_paid':
                                    badgeClass = 'bg-yellow-100 text-yellow-800';
                                    badgeText = 'Partially Paid';
                                    break;
                                case 'overdue':
                                    badgeClass = 'bg-red-100 text-red-800';
                                    badgeText = 'Overdue';
                                    break;
                                case 'unpaid':
                                default:
                                    badgeClass = 'bg-gray-100 text-gray-800';
                                    badgeText = 'Unpaid';
                                    break;
                            }

                            return `<span class="px-2 py-1 rounded-full text-xs font-medium ${badgeClass}">${badgeText}</span>`;
                        }
                    },
                    {
                        data: 'total',
                        width: '10%',
                        render: DataTable.render.text()
                    },
                    {
                        data: 'balance',
                        width: '10%',
                        render: DataTable.render.text()
                    },
                    {
                        data: 'id',
                        className: 'text-center',
                        width: '10%',
                        render: function(data, type, row) {
                            return `
                            <div class='flex flex-row justify-center items-center opacity-100'>

                                <a href="/invoice/${data}" class="group relative inline-flex items-center gap-2 bg-blue-100 text-blue-500 font-semibold px-3 py-1 rounded-xl hover:bg-blue-500 hover:ring hover:ring-blue-200 hover:text-white transition duration-150 ">

                                    <span class="relative w-4 h-4">
                                        <i class="fi fi-rs-eye flex justify-center items-center absolute inset-0 group-hover:opacity-0 transition-opacity text-[16px]"></i>
                                        <i class="fi fi-ss-eye flex justify-center items-center absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity text-[16px]"></i>
                                    </span>

                                    View
                                </a>

                            </div>
                            
                            `;
                        },
                        orderable: false,
                        searchable: false
                    }

                ],

                [
                    [0, 'desc']
                ],
                'invoice-search', {
                    status_filter: selectedStatus,
                    method_filter: selectedMethod,
                    term_filter: selectedTerm,
                    pageLength: selectedPageLength
                }

            )

            clearSearch('clear-btn', 'invoice-search', invoiceTable)

            let statusSelection = document.querySelector('#status-selection');
            let methodSelection = document.querySelector('#method-selection');
            let termSelection = document.querySelector('#term-selection');
            let pageLengthSelection = document.querySelector('#page-length-selection');

            let clearGradeFilterBtn = document.querySelector('#clear-grade-filter-btn');
            let gradeContainer = document.querySelector('#grade_selection_container');


            // Apply filter handlers
            statusSelection.addEventListener('change', handleFilterChange('status', 'data-status', 'selectedStatus',
                invoiceTable, statusSelection, null, document.querySelector('#clear-status-filter-btn')));
            methodSelection.addEventListener('change', handleFilterChange('method', 'data-method', 'selectedMethod',
                invoiceTable, methodSelection, document.querySelector('#method-selection-container'), document
                .querySelector('#clear-method-filter-btn')));
            termSelection.addEventListener('change', handleFilterChange('term', 'data-term', 'selectedTerm', invoiceTable,
                termSelection, null, document.querySelector('#clear-term-filter-btn')));

            // Special handler for page length (different behavior)
            pageLengthSelection.addEventListener('change', (e) => {
                let selectedPageLength = parseInt(e.target.value, 10);
                invoiceTable.page.len(selectedPageLength).draw();
            })


            // Apply clear handlers to all filters
            createClearFilterHandler(statusSelection, null, document.querySelector('#clear-status-filter-btn'),
                'selectedStatus', invoiceTable, 'status');
            createClearFilterHandler(methodSelection, document.querySelector('#method-selection-container'), document
                .querySelector('#clear-method-filter-btn'), 'selectedMethod', invoiceTable, 'method');
            createClearFilterHandler(termSelection, null, document.querySelector('#clear-term-filter-btn'), 'selectedTerm',
                invoiceTable, 'term');

            window.onload = function() {
                statusSelection.selectedIndex = 0
                pageLengthSelection.selectedIndex = 0
            }

            document.querySelector('#studentSearch').addEventListener('input', function(e) {
                e.preventDefault();

                let fullName = document.querySelector('#full-name');
                let lrn = document.querySelector('#lrn');
                let program = document.querySelector('#program');
                let level = document.querySelector('#level');
                let feesContainer = document.getElementById('fees-container');
                let feesmsg = document.getElementById('fees-msg');
                let studentId = document.getElementById('student_id');
                let searchStatus = document.getElementById('search-status');
                let searchIcon = document.getElementById('search-icon');
                let studentInfoSection = document.getElementById('student-info-section');
                let feesSection = document.getElementById('fees-section');
                let totalSection = document.getElementById('total-section');
                let submitBtn = document.getElementById('create-invoice-submit-btn');

                // Clear previous state
                totalSection.classList.add('hidden');
                searchStatus.classList.add('hidden');
                submitBtn.disabled = true;

                // Reset to empty states
                fullName.innerHTML = 'Search for a student...';
                lrn.innerHTML = '-';
                program.innerHTML = '-';
                level.innerHTML = '-';

                // Reset fees section to empty state
                feesContainer.innerHTML = `
                        <div class="text-center">
                            <i class="fi fi-rr-search text-[#199BCF]/60 text-3xl mb-3"></i>
                            <p class="text-sm text-[#199BCF]/70 font-medium">Search for a student to see applicable fees</p>
                        </div>
                    `;
                feesContainer.classList.remove('space-y-2');
                feesContainer.classList.add('flex', 'items-center', 'justify-center',
                    'min-h-[120px]');

                // Disable select all checkbox
                document.getElementById('select-all-fees').disabled = true;

                let searchTerm = e.target.value.trim();
                if (searchTerm.length < 2) {
                    this.classList.remove('ring-2', 'ring-red-500', 'ring-green-500',
                        'border-red-500', 'border-green-500');
                    this.classList.add('border-gray-300');

                    fullName.innerHTML = '-';
                    lrn.innerHTML = '-';
                    program.innerHTML = '-';
                    level.innerHTML = '-';

                    return;
                }

                // Show loading state
                searchStatus.classList.remove('hidden');
                searchIcon.className = 'fi fi-rr-spinner animate-spin text-blue-500';

                fetch('/getStudent', {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            search: searchTerm
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        searchStatus.classList.add('hidden');

                        if (data.success) {
                            if (data.data === null) {
                                // Student not found
                                this.classList.remove('ring-green-500',
                                    'border-green-500');
                                this.classList.add('ring-2', 'ring-red-500',
                                    'border-red-500');

                                // Show error message in the fees container
                                feesContainer.innerHTML = `
                                        <div class="text-center">
                                            <i class="fi fi-rr-exclamation-triangle text-red-500 text-3xl mb-3"></i>
                                            <p class="text-sm text-red-600 font-medium">Student not found. Please check the LRN or name.</p>
                                        </div>
                                    `;

                            } else if (data.data !== null) {
                                // Student found
                                this.classList.remove('ring-red-500', 'border-red-500');
                                this.classList.add('ring-2', 'ring-green-500',
                                    'border-green-500');

                                fullName.innerHTML = data.data.user ?
                                    `${data.data.user.first_name || ''} ${data.data.user.last_name || ''}`
                                    .trim() || '-' :
                                    'Student name not available';
                                lrn.innerHTML = data.data.lrn || '-';
                                program.innerHTML = data.data.program || '-';
                                level.innerHTML = data.data.grade_level || '-';
                                studentId.value = data.data.id;

                                // Update student info section with actual data
                                let studentInfoCard = document.getElementById(
                                    'student-info-card');
                                if (studentInfoCard) {
                                    studentInfoCard.classList.remove('bg-[#E3ECFF]/30',
                                        'border-[#199BCF]/20');
                                    studentInfoCard.classList.add('bg-[#E3ECFF]/50',
                                        'border-[#199BCF]/40');
                                }

                                // Clear any previous messages
                                // if (data.hasInvoice) {
                                //     // Student already has an invoice
                                //     feesContainer.innerHTML = `
                            //             <div class="text-center">
                            //                 <i class="fi fi-rr-exclamation-triangle text-orange-500 text-3xl mb-3"></i>
                            //                 <p class="text-sm text-orange-600 font-medium">This student already has an invoice for the current academic term</p>
                            //                 <p class="text-xs text-orange-500 mt-2">Only one invoice per student per academic term is allowed</p>
                            //             </div>
                            //         `;
                                //     feesContainer.classList.remove('space-y-2');
                                //     feesContainer.classList.add('flex', 'items-center',
                                //         'justify-center', 'min-h-[120px]');

                                //     // Disable select all checkbox
                                //     document.getElementById('select-all-fees').disabled = true;
                                //     document.querySelector('label[for="select-all-fees"]')
                                //         .classList
                                //         .remove('text-gray-700', 'cursor-pointer');
                                //     document.querySelector('label[for="select-all-fees"]')
                                //         .classList
                                //         .add('text-gray-500', 'cursor-not-allowed');

                                //     // Disable submit button
                                //     submitBtn.disabled = true;
                                // } else 

                                if (data.fees && data.fees.length > 0) {
                                    // Remove empty state styling and add proper container styling
                                    feesContainer.classList.remove('flex', 'items-center',
                                        'justify-center', 'min-h-[120px]');
                                    feesContainer.classList.add('space-y-2');

                                    // Clear the empty state content
                                    feesContainer.innerHTML = '';

                                    // Enable select all checkbox
                                    document.getElementById('select-all-fees').disabled = false;
                                    document.querySelector('label[for="select-all-fees"]')
                                        .classList
                                        .remove('text-gray-500', 'cursor-not-allowed');
                                    document.querySelector('label[for="select-all-fees"]')
                                        .classList
                                        .add('text-gray-700', 'cursor-pointer');

                                    data.fees.forEach(fee => {
                                        let feeItem = document.createElement('div');
                                        feeItem.classList.add('flex', 'items-center',
                                            'justify-between', 'p-3', 'bg-white',
                                            'rounded-lg', 'border',
                                            'border-[#199BCF]/20',
                                            'hover:border-[#199BCF]/40',
                                            'hover:bg-[#E3ECFF]/20',
                                            'transition-colors',
                                            'shadow-sm', 'hover:shadow-md'
                                        );

                                        let leftDiv = document.createElement('div');
                                        leftDiv.classList.add('flex', 'items-center',
                                            'gap-2');

                                        let checkbox = document.createElement('input');
                                        checkbox.type = 'checkbox';
                                        checkbox.name = 'school_fees[]';
                                        checkbox.value = fee.id;
                                        checkbox.classList.add('w-4', 'h-4',
                                            'text-[#199BCF]',
                                            'border-[#199BCF]/30', 'rounded',
                                            'focus:ring-[#199BCF]/20');
                                        checkbox.addEventListener('change',
                                            calculateTotal);

                                        let label = document.createElement('label');
                                        label.textContent = fee.name;
                                        label.classList.add('text-sm', 'font-semibold',
                                            'text-gray-900', 'cursor-pointer',
                                            'flex-1');
                                        label.setAttribute('for', `fee-${fee.id}`);
                                        checkbox.id = `fee-${fee.id}`;

                                        let amountSpan = document.createElement('span');
                                        amountSpan.textContent =
                                            `₱${parseFloat(fee.amount).toLocaleString('en-PH', {minimumFractionDigits: 2})}`;
                                        amountSpan.classList.add('text-sm', 'font-bold',
                                            'text-[#199BCF]');

                                        let hidden = document.createElement('input');
                                        hidden.type = 'hidden';
                                        hidden.name = `school_fee_amounts[${fee.id}]`;
                                        hidden.value = fee.amount;

                                        leftDiv.appendChild(checkbox);
                                        leftDiv.appendChild(label);
                                        feeItem.appendChild(leftDiv);
                                        feeItem.appendChild(amountSpan);
                                        feeItem.appendChild(hidden);
                                        feesContainer.appendChild(feeItem);
                                    });

                                    // Show total section
                                    totalSection.classList.remove('hidden');
                                    calculateTotal();

                                    // Setup Select All functionality
                                    setupSelectAll();

                                } else {
                                    // Student found but no fees available
                                    feesContainer.innerHTML = `
                                            <div class="text-center">
                                                <i class="fi fi-rr-info text-[#199BCF]/60 text-3xl mb-3"></i>
                                                <p class="text-sm text-[#199BCF]/70 font-medium">No applicable fees found for this student</p>
                                            </div>
                                        `;
                                    feesContainer.classList.remove('space-y-2');
                                    feesContainer.classList.add('flex', 'items-center',
                                        'justify-center', 'min-h-[120px]');

                                    // Disable select all checkbox
                                    document.getElementById('select-all-fees').disabled = true;
                                    document.querySelector('label[for="select-all-fees"]')
                                        .classList
                                        .remove('text-gray-700', 'cursor-pointer');
                                    document.querySelector('label[for="select-all-fees"]')
                                        .classList
                                        .add('text-gray-500', 'cursor-not-allowed');

                                    // Disable submit button
                                    submitBtn.disabled = true;
                                }

                            }
                        } else {
                            // Handle error response
                            this.classList.remove('ring-green-500', 'border-green-500');
                            this.classList.add('ring-2', 'ring-red-500',
                                'border-red-500');

                            feesContainer.innerHTML = `
                                    <div class="text-center">
                                        <i class="fi fi-rr-exclamation-triangle text-red-500 text-3xl mb-3"></i>
                                        <p class="text-sm text-red-600 font-medium">Error searching for student. Please try again.</p>
                                    </div>
                                `;
                            feesContainer.classList.remove('space-y-2');
                            feesContainer.classList.add('flex', 'items-center',
                                'justify-center',
                                'min-h-[120px]');
                        }
                    })
                    .catch(err => {
                        searchStatus.classList.add('hidden');
                        studentSeach.classList.remove('ring-green-500', 'border-green-500');
                        studentSeach.classList.add('ring-2', 'ring-red-500', 'border-red-500');

                        feesmsg.innerHTML = 'Error searching for student. Please try again.';
                        feesmsg.classList.remove('hidden');
                    });
            });

            // Function to calculate total amount
            function calculateTotal() {
                let checkboxes = document.querySelectorAll('input[name="school_fees[]"]:checked');
                let total = 0;

                checkboxes.forEach(checkbox => {
                    let hiddenInput = document.querySelector(
                        `input[name="school_fee_amounts[${checkbox.value}]"]`);
                    if (hiddenInput) {
                        total += parseFloat(hiddenInput.value) || 0;
                    }
                });

                let totalAmountElement = document.getElementById('total-amount');
                let submitBtn = document.getElementById('create-invoice-submit-btn');

                totalAmountElement.textContent =
                    `₱${total.toLocaleString('en-PH', {minimumFractionDigits: 2})}`;

                // Enable/disable submit button based on selection
                submitBtn.disabled = total === 0;

                // Update Select All checkbox state
                updateSelectAllState();
            }

            // Function to setup Select All functionality
            function setupSelectAll() {
                let selectAllCheckbox = document.getElementById('select-all-fees');
                let feeCheckboxes = document.querySelectorAll('input[name="school_fees[]"]');

                // Remove existing event listeners to prevent duplicates
                selectAllCheckbox.replaceWith(selectAllCheckbox.cloneNode(true));
                selectAllCheckbox = document.getElementById('select-all-fees');

                selectAllCheckbox.addEventListener('change', function() {
                    feeCheckboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                    calculateTotal();
                });

                // Add change listeners to individual checkboxes
                feeCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', calculateTotal);
                });
            }

            // Function to update Select All checkbox state
            function updateSelectAllState() {
                let selectAllCheckbox = document.getElementById('select-all-fees');
                let feeCheckboxes = document.querySelectorAll('input[name="school_fees[]"]');
                let checkedCount = document.querySelectorAll('input[name="school_fees[]"]:checked').length;

                if (checkedCount === 0) {
                    selectAllCheckbox.indeterminate = false;
                    selectAllCheckbox.checked = false;
                } else if (checkedCount === feeCheckboxes.length) {
                    selectAllCheckbox.indeterminate = false;
                    selectAllCheckbox.checked = true;
                } else {
                    selectAllCheckbox.indeterminate = true;
                    selectAllCheckbox.checked = false;
                }
            }


            // Form submission handling
            document.getElementById('create-invoice-modal-form').addEventListener('submit', function(e) {
                e.preventDefault();

                let studentId = document.getElementById('student_id').value;
                let selectedFees = document.querySelectorAll('input[name="school_fees[]"]:checked');

                // Validation
                if (!studentId) {
                    showAlert('error', 'Please search and select a student first.');
                    return;
                }

                if (selectedFees.length === 0) {
                    showAlert('error', 'Please select at least one fee to create an invoice.');
                    return;
                }

                // Show loading state
                let submitBtn = document.getElementById('create-invoice-submit-btn');
                let originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fi fi-rr-spinner animate-spin"></i> Creating...';
                submitBtn.disabled = true;

                // Prepare form data
                let formData = new FormData(this);

                fetch('/invoice', {
                        method: "POST",
                        body: formData,
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Accept": "application/json"
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Reset button state
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;

                        if (data.success) {
                            // Close modal
                            closeModal('create-invoice-modal', 'modal-container-2');

                            // Show success message
                            showAlert('success', data.message || 'Invoice created successfully!');

                            // Update invoice counts display
                            if (data.totalInvoices !== undefined) {
                                document.getElementById('totalInvoicesDisplay').textContent = data
                                    .totalInvoices;
                            }
                            if (data.pendingInvoices !== undefined) {
                                document.getElementById('pendingInvoicesDisplay').textContent = data
                                    .pendingInvoices;
                            }
                            if (data.paidInvoices !== undefined) {
                                document.getElementById('paidInvoicesDisplay').textContent = data.paidInvoices;
                            }

                            // Reset form
                            resetInvoiceForm();

                            // Refresh invoice table if it exists
                            if (typeof invoiceTable !== 'undefined') {
                                invoiceTable.draw();
                            }
                        } else {
                            showAlert('error', data.error ||
                                'Failed to create invoice. Please try again.');
                        }
                    })
                    .catch(err => {
                        // Reset button state
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;

                        showAlert('error', 'Something went wrong. Please try again.');
                    });
            });

            // Function to reset the invoice form
            function resetInvoiceForm() {
                let form = document.getElementById('create-invoice-modal-form');
                let studentSearch = document.getElementById('studentSearch');
                let studentInfoSection = document.getElementById('student-info-section');
                let feesSection = document.getElementById('fees-section');
                let totalSection = document.getElementById('total-section');
                let feesmsg = document.getElementById('fees-msg');
                let submitBtn = document.getElementById('create-invoice-submit-btn');

                // Reset form
                form.reset();

                // Clear search input specifically
                studentSearch.value = '';

                // Clear search input styling
                studentSearch.classList.remove('ring-2', 'ring-red-500', 'ring-green-500', 'border-red-500',
                    'border-green-500');
                studentSearch.classList.add('border-gray-300');

                // Reset to empty states
                totalSection.classList.add('hidden');

                // Reset student info to empty state
                let fullName = document.querySelector('#full-name');
                let lrn = document.querySelector('#lrn');
                let program = document.querySelector('#program');
                let level = document.querySelector('#level');

                fullName.innerHTML = 'Search for a student...';
                lrn.innerHTML = '-';
                program.innerHTML = '-';
                level.innerHTML = '-';

                // Reset student info section styling
                let studentInfoCard = document.getElementById('student-info-card');
                if (studentInfoCard) {
                    studentInfoCard.classList.remove('bg-[#E3ECFF]/50', 'border-[#199BCF]/40');
                    studentInfoCard.classList.add('bg-[#E3ECFF]/30', 'border-[#199BCF]/20');
                }

                // Reset fees section to empty state
                let feesContainer = document.getElementById('fees-container');
                feesContainer.innerHTML = `
                    <div class="text-center">
                        <i class="fi fi-rr-search text-[#199BCF]/60 text-3xl mb-3"></i>
                        <p class="text-sm text-[#199BCF]/70 font-medium">Search for a student to see applicable fees</p>
                    </div>
                `;
                feesContainer.classList.remove('space-y-2');
                feesContainer.classList.add('flex', 'items-center', 'justify-center', 'min-h-[120px]');

                // Disable select all checkbox
                document.getElementById('select-all-fees').disabled = true;
                document.querySelector('label[for="select-all-fees"]').classList.remove('text-gray-700',
                    'cursor-pointer');
                document.querySelector('label[for="select-all-fees"]').classList.add('text-gray-500',
                    'cursor-not-allowed');

                // Disable submit button
                submitBtn.disabled = true;

                // Reset Select All checkbox
                let selectAllCheckbox = document.getElementById('select-all-fees');
                if (selectAllCheckbox) {
                    selectAllCheckbox.checked = false;
                    selectAllCheckbox.indeterminate = false;
                }
            }

        }

        // PAYMENT HISTORY
        function initializePaymentHistoryTab() {
            let paymentHistory = initCustomDataTable(
                'payments',
                `/getInvoiceHistory`,
                [{
                        data: 'index',
                        width: '3%',
                        searchable: true
                    },
                    {
                        data: 'invoice_number',
                        width: '14%',
                        render: DataTable.render.text()
                    },
                    {
                        data: 'total',
                        width: '10%',
                        render: DataTable.render.text()
                    },
                    {
                        data: 'payment_method',
                        width: '10%',
                        render: function(data, type, row) {
                            let badgeClass = '';
                            let badgeText = data;

                            switch (data) {
                                case 'installment':
                                    badgeClass = 'bg-green-100 text-green-800';
                                    badgeText = 'Installment';
                                    break;
                                case 'full':
                                    badgeClass = 'bg-yellow-100 text-yellow-800';
                                    badgeText = 'Full';
                                    break;
                                case 'Not Set':
                                default:
                                    badgeClass = 'bg-gray-200 text-gray-800';
                                    badgeText = 'Not Set';
                                    break;
                            }

                            return `<span class="px-2 py-1 rounded-full text-xs font-medium ${badgeClass}">${badgeText}</span>`;
                        }
                    },
                    {
                        data: 'status',
                        width: '10%',
                        render: function(data, type, row) {
                            let badgeClass = '';
                            let badgeText = data;

                            if (data === 'paid') {
                                badgeClass = 'bg-green-100 text-green-800';
                                badgeText = 'Paid';
                            }

                            return `<span class="px-2 py-1 rounded-full text-xs font-medium ${badgeClass}">${badgeText}</span>`;
                        }
                    },
                    {
                        data: 'student',
                        width: '14%',
                        render: DataTable.render.text()
                    },
                    {
                        data: 'academic_term',
                        width: '15%',
                        render: DataTable.render.text()
                    },
                    {
                        data: 'id',
                        className: 'text-center',
                        width: '10%',
                        render: function(data, type, row) {
                            return `
                            <div class='flex flex-row justify-center items-center opacity-100'>

                                <a href="/invoice/${data}?from=history" class="group relative inline-flex items-center gap-2 bg-blue-100 text-blue-500 font-semibold px-3 py-1 rounded-xl hover:bg-blue-500 hover:ring hover:ring-blue-200 hover:text-white transition duration-150 ">

                                    <span class="relative w-4 h-4">
                                        <i class="fi fi-rs-eye flex justify-center items-center absolute inset-0 group-hover:opacity-0 transition-opacity text-[16px]"></i>
                                        <i class="fi fi-ss-eye flex justify-center items-center absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity text-[16px]"></i>
                                    </span>

                                    View
                                </a>

                            </div>
                            
                            `;
                        },
                        orderable: false,
                        searchable: false
                    }

                ],
                [
                    [0, 'desc']
                ],
                'payment-history-search', {
                    status_filter: selectedStatus,
                    method_filter: selectedMethod,
                    term_filter: selectedTerm,
                    pageLength: selectedPageLength
                }

            )

            clearSearch('clear-btn', 'payment-history-search', paymentHistory)
        }

        function initializeDiscountTab() {
            // Initialize discount table
            let discountTable = initCustomDataTable(
                'discount-table',
                `/getDiscounts`,
                [{
                        data: 'index',
                        width: '5%',
                    },
                    {
                        data: 'name',
                        width: '15%',
                        render: DataTable.render.text()
                    },
                    {
                        width: '15%',
                        data: 'description',
                        render: DataTable.render.text()
                    },
                    {
                        data: 'discount_type',
                        render: function(data, type, row) {
                            let badgeClass = data === 'percentage' ? 'bg-blue-100 text-blue-800' :
                                'bg-green-100 text-green-800';
                            let badgeText = data === 'percentage' ? 'Percentage' : 'Fixed Amount';
                            return `<span class="px-2 py-1 rounded-full text-xs font-medium ${badgeClass}">${badgeText}</span>`;
                        }
                    },
                    {
                        data: 'discount_value',
                        render: DataTable.render.text()
                    },
                    {
                        data: 'is_active',
                        render: function(data, type, row) {
                            let badgeClass = data ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800';
                            let badgeText = data ? 'Active' : 'Inactive';
                            return `<span class="px-2 py-1 rounded-full text-xs font-medium ${badgeClass}">${badgeText}</span>`;
                        }
                    },
                    {
                        data: 'id',
                        className: 'text-center',
                        render: function(data, type, row) {
                            return `
                                <div class='flex flex-row justify-center items-center gap-2'>
                                    <button id="open-edit-discount-btn-${data}" data-discount-id="${data}"
                                        class="edit-discount-btn group relative inline-flex items-center gap-1 bg-blue-100 text-blue-500 font-semibold px-3 py-2 rounded-xl hover:bg-blue-500 hover:ring hover:ring-blue-200 hover:text-white transition duration-150">
                                        <i class="fi fi-rr-edit text-[16px] flex justify-center items-center"></i>
                                    </button>
                                    <button id="open-toggle-discount-btn-${data}" data-discount-id="${data}"
                                        class="toggle-discount-btn group relative inline-flex items-center gap-1 bg-yellow-100 text-yellow-500 font-semibold px-3 py-2 rounded-xl hover:bg-yellow-500 hover:ring hover:ring-yellow-200 hover:text-white transition duration-150">
                                        <i class="fi fi-rr-toggle-on text-[16px] flex justify-center items-center"></i>
                                    </button>
                                    <button id="open-delete-discount-btn-${data}" data-discount-id="${data}"
                                        class="delete-discount-btn group relative inline-flex items-center gap-1 bg-red-100 text-red-500 font-semibold px-3 py-2 rounded-xl hover:bg-red-500 hover:ring hover:ring-red-200 hover:text-white transition duration-150">
                                        <i class="fi fi-rr-trash text-[16px] flex justify-center items-center"></i>
                                    </button>
                                </div>
                            `;
                        },
                        orderable: false,
                        searchable: false
                    }
                ],
                [
                    [0, 'desc']
                ],
                'discount-search', {
                    type_filter: window.selectedType || '',
                    status_filter: window.selectedStatus || ''
                }
            );

            clearSearch('clear-discount-btn', 'discount-search', discountTable);

            // Filter handlers
            let typeSelection = document.querySelector('#type-selection');
            let statusSelection = document.querySelector('#status-selection-discount');

            typeSelection.addEventListener('change', handleFilterChange('type', 'data-type', 'selectedType',
                discountTable, typeSelection, null, document.querySelector('#clear-type-filter-btn')));
            statusSelection.addEventListener('change', handleFilterChange('status', 'data-status', 'selectedStatus',
                discountTable, statusSelection, null, document.querySelector('#clear-status-filter-btn-discount')));

            createClearFilterHandler(typeSelection, null, document.querySelector('#clear-type-filter-btn'),
                'selectedType', discountTable, 'type_filter');
            createClearFilterHandler(statusSelection, null, document.querySelector('#clear-status-filter-btn-discount'),
                'selectedStatus', discountTable, 'status_filter');

            // Initialize modals
            initModal('create-discount-modal', 'create-discount-btn', 'create-discount-modal-close-btn',
                'create-discount-modal-cancel-btn', 'modal-container-6');

            // Initialize edit discount modals
            function initializeEditDiscountModals() {
                document.querySelectorAll('.edit-discount-btn').forEach((button) => {
                    let discountId = button.getAttribute('data-discount-id');
                    let buttonId = `open-edit-discount-btn-${discountId}`;

                    initModal('edit-discount-modal', buttonId, 'edit-discount-modal-close-btn',
                        'edit-discount-modal-cancel-btn', 'modal-container-7');

                    button.addEventListener('click', () => {
                        fetch(`/getDiscounts`)
                            .then(response => response.json())
                            .then(data => {
                                let discount = data.data.find(d => d.id == discountId);
                                if (discount) {
                                    document.querySelector(
                                            '#edit-discount-modal-form input[name="name"]').value =
                                        discount.name;
                                    document.querySelector(
                                            '#edit-discount-modal-form textarea[name="description"]')
                                        .value = discount.description || '';
                                    document.querySelector(
                                            '#edit-discount-modal-form select[name="discount_type"]')
                                        .value = discount.discount_type;
                                    document.querySelector(
                                            '#edit-discount-modal-form input[name="discount_value"]')
                                        .value = discount.discount_value;
                                    document.querySelector(
                                            '#edit-discount-modal-form input[name="is_active"]')
                                        .checked = discount.is_active;
                                    document.querySelector('#edit-discount-modal-form').setAttribute(
                                        'data-discount-id', discountId);
                                }
                            });
                    });
                });
            }

            // Initialize delete discount modals
            function initializeDeleteDiscountModals() {
                document.querySelectorAll('.delete-discount-btn').forEach((button) => {
                    let discountId = button.getAttribute('data-discount-id');
                    let buttonId = `open-delete-discount-btn-${discountId}`;

                    initModal('delete-discount-modal', buttonId, 'delete-discount-close-btn',
                        'delete-discount-cancel-btn', 'modal-container-delete-discount');

                    button.addEventListener('click', () => {
                        let form = document.getElementById('delete-discount-form');
                        let existingInputs = form.querySelectorAll('input[name="discount_id"]');
                        existingInputs.forEach(input => input.remove());

                        form.action = `/admin/discounts/${discountId}`;

                        let discountIdInput = document.createElement('input');
                        discountIdInput.type = 'hidden';
                        discountIdInput.name = 'discount_id';
                        discountIdInput.value = discountId;
                        form.appendChild(discountIdInput);
                    });
                });
            }

            // Initialize toggle discount modals
            function initializeToggleDiscountModals() {
                document.querySelectorAll('.toggle-discount-btn').forEach((button) => {
                    let discountId = button.getAttribute('data-discount-id');
                    let buttonId = `open-toggle-discount-btn-${discountId}`;

                    initModal('toggle-discount-modal', buttonId, 'toggle-discount-close-btn',
                        'toggle-discount-cancel-btn', 'modal-container-toggle-discount');

                    button.addEventListener('click', () => {
                        let form = document.getElementById('toggle-discount-form');
                        let existingInputs = form.querySelectorAll('input[name="discount_id"]');
                        existingInputs.forEach(input => input.remove());

                        form.action = `/admin/discounts/${discountId}/toggle`;

                        let discountIdInput = document.createElement('input');
                        discountIdInput.type = 'hidden';
                        discountIdInput.name = 'discount_id';
                        discountIdInput.value = discountId;
                        form.appendChild(discountIdInput);
                    });
                });
            }

            discountTable.on('draw', function() {
                initializeEditDiscountModals();
                initializeDeleteDiscountModals();
                initializeToggleDiscountModals();
            });

            // Create discount form submission
            const createDiscountForm = document.getElementById('create-discount-modal-form');
            if (createDiscountForm) {
                createDiscountForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    formData.append('is_active', document.getElementById('is_active').checked ? 1 : 0);

                    showLoader();
                    fetch('/admin/discounts', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            hideLoader();
                            if (data.success === true) {
                                showAlert('success', data.message);
                                discountTable.draw();
                                document.getElementById('create-discount-modal-close-btn').click();
                                createDiscountForm.reset();
                            } else {
                                showAlert('error', data.message);
                            }
                        })
                        .catch(error => {
                            hideLoader();
                            showAlert('error', 'An error occurred while creating the discount');
                        });
                });
            }

            // Edit discount form submission
            const editDiscountForm = document.getElementById('edit-discount-modal-form');
            if (editDiscountForm) {
                editDiscountForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    const discountId = this.getAttribute('data-discount-id');
                    formData.append('is_active', document.querySelector(
                        '#edit-discount-modal-form input[name="is_active"]').checked ? 1 : 0);
                    formData.append('_method', 'PUT');

                    showLoader();
                    fetch(`/admin/discounts/${discountId}`, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            hideLoader();
                            if (data.success === true) {
                                showAlert('success', data.message);
                                discountTable.draw();
                                document.getElementById('edit-discount-modal-close-btn').click();
                            } else {
                                showAlert('error', data.message);
                            }
                        })
                        .catch(error => {
                            hideLoader();
                            showAlert('error', 'An error occurred while updating the discount');
                        });
                });
            }

            // Delete discount form submission
            const deleteDiscountForm = document.getElementById('delete-discount-form');
            if (deleteDiscountForm) {
                deleteDiscountForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    const discountId = formData.get('discount_id');

                    showLoader();
                    fetch(`/admin/discounts/${discountId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            hideLoader();
                            if (data.success === true) {
                                showAlert('success', data.message);
                                discountTable.draw();
                                document.getElementById('delete-discount-close-btn').click();
                            } else {
                                showAlert('error', data.message);
                            }
                        })
                        .catch(error => {
                            hideLoader();
                            showAlert('error', 'An error occurred while deleting the discount');
                        });
                });
            }

            // Toggle discount form submission
            const toggleDiscountForm = document.getElementById('toggle-discount-form');
            if (toggleDiscountForm) {
                toggleDiscountForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    const discountId = formData.get('discount_id');

                    showLoader();
                    fetch(`/admin/discounts/${discountId}/toggle`, {
                            method: 'PATCH',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            hideLoader();
                            if (data.success === true) {
                                showAlert('success', data.message);
                                discountTable.draw();
                                document.getElementById('toggle-discount-close-btn').click();
                            } else {
                                showAlert('error', data.message);
                            }
                        })
                        .catch(error => {
                            hideLoader();
                            showAlert('error', 'An error occurred while toggling the discount');
                        });
                });
            }
        }

        // =========================
        // Down Payment Auto-Format
        // =========================
        (function setupDownPaymentFormatter() {
            const input = document.getElementById('down_payment');
            const form = document.getElementById('set-down-payment-modal-form');

            if (!input) return;

            const formatNumber = (value) => {
                const digits = (value || '').toString().replace(/[^\d]/g, '');
                if (!digits) return '';
                const num = parseInt(digits, 10);
                if (isNaN(num)) return '';
                return `₱ ${new Intl.NumberFormat('en-PH').format(num)}`;
            };

            const onInput = () => {
                const selectionStart = input.selectionStart;
                const before = input.value;
                const formatted = formatNumber(before);
                input.value = formatted;
                // Try to keep caret towards end after reformat
                requestAnimationFrame(() => {
                    input.setSelectionRange(input.value.length, input.value.length);
                });
            };

            input.addEventListener('input', onInput);
            input.addEventListener('blur', onInput);

            if (form) {
                form.addEventListener('submit', function() {
                    // strip formatting before submit so backend gets a clean number
                    const raw = (input.value || '').replace(/[^\d]/g, '');
                    input.value = raw;
                });
            }
        })();


        // Due Date Form Submission
        document.getElementById('set-due-date-modal-form').addEventListener('submit', function(e) {
            e.preventDefault();

            let form = e.target;
            let formData = new FormData(form);

            showLoader('Saving...');

            fetch(`{{ route('admin.settings.school.payments.update') }}`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Accept': 'application/json',
                        'X-HTTP-Method-Override': 'PUT'
                    }
                })
                .then(res => res.json())
                .then((data) => {
                    hideLoader();
                    if (data && data.success) {
                        // Update the display with the new due date value
                        const newDueDate = data.data?.due_day_of_month || 10;
                        let suffix = 'th';
                        if (newDueDate == 1) suffix = 'st';
                        else if (newDueDate == 2) suffix = 'nd';
                        else if (newDueDate == 3) suffix = 'rd';
                        document.getElementById('dueDateDisplay').textContent =
                            `${newDueDate}${suffix} of Month`;

                        closeModal('set-due-date-modal', 'modal-container-4');
                        showAlert('success', 'Due date updated successfully.');
                    } else {
                        showAlert('error', (data && data.error) || 'Failed to save due date.');
                    }
                })
                .catch(() => {
                    hideLoader();
                    showAlert('error', 'Failed to save due date. Please try again.');
                });
        });


        // Down Payment Form Submission
        document.getElementById('set-down-payment-modal-form').addEventListener('submit', function(e) {
            e.preventDefault();

            let form = e.target;
            let formData = new FormData(form);

            // Ensure numeric down_payment - only strip formatting if it has currency symbols
            const dp = formData.get('down_payment') || '';

            if (dp && dp.includes('₱')) {
                // Only strip formatting if it contains currency symbols
                const cleaned = String(dp).replace(/[^\d]/g, '');
                formData.set('down_payment', cleaned);
            }

            showLoader('Saving...');


            fetch('/admin/settings/school/payments', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Accept': 'application/json',
                        'X-HTTP-Method-Override': 'PUT'
                    }
                })
                .then(res => {
                    return res.json();
                })
                .then((data) => {
                    hideLoader();

                    if (data.success === true) {
                        try {
                            // Update the display with the new down payment value
                            const newDownPayment = data.data?.down_payment || 0;
                            const formattedAmount = newDownPayment.toLocaleString();

                            // Safely update elements
                            const studentCountElement = document.getElementById('studentCount');
                            const markElement = document.getElementById('mark');

                            if (studentCountElement) {
                                studentCountElement.textContent = `₱${formattedAmount}`;
                            }

                            if (markElement) {
                                markElement.classList.add('hidden');
                            }

                            closeModal('set-down-payment-modal', 'modal-container-3');
                            showAlert('success', 'Settings saved successfully.');
                        } catch (updateError) {
                            closeModal('set-down-payment-modal', 'modal-container-3');
                            showAlert('success', 'Settings saved successfully.');
                        }
                    } else {
                        showAlert('error', (data && data.error) || 'Failed to save settings.');
                    }
                })
                .catch((error) => {
                    hideLoader();
                    showAlert('error', 'Network error occurred.');
                });
        });



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

        function openAlert() {
            const alertContainer = document.querySelector('#alert-container');
            alertContainer.classList.toggle('opacity-100');
            alertContainer.classList.toggle('scale-95');
            alertContainer.classList.toggle('pointer-events-none');
            alertContainer.classList.toggle('translate-y-5');
        }
    </script>
@endpush
