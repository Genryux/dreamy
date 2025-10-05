@extends('layouts.admin')
@section('modal')
    <x-modal modal_id="create-school-fee-modal" modal_name="Create School Fee" close_btn_id="create-school-fee-modal-close-btn"
        modal_container_id="modal-container-1">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-progress-upload flex justify-center items-center '></i>

        </x-slot>

        <form method="POST" action="/school-fees" id="create-school-fee-modal-form" class="p-6">
            @csrf

            <div class="flex flex-col justify-center items-center">

                <label for="name">Name</label>
                <input type="text" name="name" id="name">
                <label for="program_id">Applied to (program)</label>
                <select name="program_id" id="program_id">
                    <option>Program</option>
                    @foreach ($programs as $program)
                        <option value="{{ $program->id }}">{{ $program->name }}</option>
                    @endforeach
                    <option value="">All</option>
                </select>
                <label for="grade_level">Applied to (Year Level)</label>
                <select name="grade_level" id="grade_level">
                    <option>Year Level</option>
                    <option value="Grade 11">Grade 11</option>
                    <option value="Grade 12">Grade 12</option>
                    <option value="">All</option>
                </select>
                <label for="amount">Enter Amount</label>
                <input type="number" name="amount" id="amount">
            </div>

        </form>

        <x-slot name="modal_info">
            <i class="fi fi-bs-download flex justify-center items-center"></i>
            <a href="{{ asset('templates/Officially_Enrolled_Students_Import_Template.xlsx') }}" download>Click here to
                download the
                template</a>
        </x-slot>

        <x-slot name="modal_buttons">
            <button id="create-school-fee-modal-cancel-btn"
                class="bg-gray-100 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-md text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-200 hover:ring hover:ring-gray-200 transition duration-150">
                Cancel
            </button>
            {{-- This button will acts as the submit button --}}
            <button type="submit" form="create-school-fee-modal-form" name="action" value="verify"
                class="bg-blue-500 text-[14px] px-3 py-2 rounded-md text-[#f8f8f8] font-bold hover:ring hover:ring-blue-200 hover:bg-blue-400 transition duration-150 shadow-sm">
                Import
            </button>
        </x-slot>

    </x-modal>
    {{-- Create invoice modal --}}
    <x-modal modal_id="create-invoice-modal" modal_name="Create Invoice" close_btn_id="create-invoice-modal-close-btn"
        modal_container_id="modal-container-2">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-receipt flex justify-center items-center text-blue-600'></i>
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
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 text-sm"
                            placeholder="Search by last name or LRN">
                        <div id="search-status" class="absolute inset-y-0 right-0 pr-3 flex items-center hidden">
                            <i id="search-icon" class="text-sm"></i>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500">Enter at least 2 characters to search</p>
                </div>

                <input type="hidden" name="student_id" id="student_id" value="">

                {{-- Student Information Section --}}
                <div id="student-info-section" class="hidden space-y-3">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h3 class="text-sm font-semibold mb-3 text-gray-700 flex items-center gap-2">
                            Student Information
                        </h3>
                        <div class="flex flex-row text-sm">
                            <div class="flex-1 flex flex-col justify-center items-start gap-2">
                                <div>
                                    <span class="text-gray-600 font-medium">Full Name:</span>
                                    <span id="full-name" class="ml-2 text-gray-900 font-semibold">-</span>
                                </div>
                                <div>
                                    <span class="text-gray-600 font-medium">Year Level:</span>
                                    <span id="level" class="ml-2 text-gray-900 font-semibold">-</span>
                                </div>

                            </div>
                            <div class="flex-1 flex flex-col justify-center items-start gap-2">
                                <div>
                                    <span class="text-gray-600 font-medium">LRN:</span>
                                    <span id="lrn" class="ml-2 text-gray-900 font-semibold">-</span>
                                </div>
                                <div>
                                    <span class="text-gray-600 font-medium">Program:</span>
                                    <span id="program" class="ml-2 text-gray-900 font-semibold">-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Fees Selection Section --}}
                <div id="fees-section" class="hidden space-y-3">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                            Select Applicable Fees
                        </h3>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" id="select-all-fees"
                                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <label for="select-all-fees" class="text-sm font-medium text-gray-700 cursor-pointer">Select
                                All</label>
                        </div>
                    </div>
                    <div id="fees-container"
                        class="space-y-2 max-h-32 overflow-y-auto border border-gray-200 rounded-lg p-3 bg-gray-50">
                        <!-- Fees will be dynamically populated here -->
                    </div>
                </div>

                {{-- Total Amount Section --}}
                <div id="total-section" class="hidden">
                    <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-semibold text-green-800">Total Amount:</span>
                            <span id="total-amount" class="text-lg font-bold text-green-900">₱0.00</span>
                        </div>
                    </div>
                </div>

                {{-- Status Messages --}}
                <div id="status-messages" class="space-y-2">
                    <p id="fees-msg" class="text-sm text-gray-500 text-center py-2">
                        Search for a student to see applicable fees
                    </p>
                </div>
            </div>
        </form>

        <x-slot name="modal_info">
            <i class="fi fi-bs-download flex justify-center items-center"></i>
            <a href="{{ asset('templates/Officially_Enrolled_Students_Import_Template.xlsx') }}" download
                class="hover:text-blue-600 hover:underline transition duration-150">
                Download Invoice Template
            </a>
        </x-slot>

        <x-slot name="modal_buttons">
            <button id="create-invoice-modal-cancel-btn" type="button"
                class="bg-gray-100 border border-[#1e1e1e]/15 text-[14px] px-4 py-2 rounded-md text-[#0f111c]/80 font-semibold shadow-sm hover:bg-gray-200 hover:ring hover:ring-gray-200 transition duration-150">
                Cancel
            </button>
            <button type="submit" form="create-invoice-modal-form" name="action" value="create"
                id="create-invoice-submit-btn"
                class="bg-blue-500 text-[14px] px-4 py-2 rounded-md text-white font-semibold hover:ring hover:ring-blue-200 hover:bg-blue-400 transition duration-150 shadow-sm disabled:opacity-50 disabled:cursor-not-allowed"
                disabled>
                <span class="flex items-center gap-2">
                    <i class="fi fi-rr-receipt"></i>
                    Create Invoice
                </span>
            </button>
        </x-slot>

    </x-modal>
@endsection
@section('header')
    <div class="flex flex-row justify-between items-start text-start px-[14px] py-2">
        <div>
            <h1 class="text-[20px] font-black">Manage Invoices</h1>
            <p class="text-[14px]  text-gray-900/60">View and manage program list and associated sections and subjects.
            </p>
        </div>
    </div>
@endsection
{{-- @section('stat')
    <div class="flex justify-center items-center">
        <div
            class="flex flex-col justify-center items-center flex-grow px-6 pb-8 pt-2 bg-gradient-to-br from-blue-500 to-[#1A3165] rounded-xl shadow-xl border border-[#1e1e1e]/10 gap-2 text-white">

            <div class="flex flex-row items-center justify-between w-full gap-4 py-2 rounded-lg ">

                <div class="flex flex-col items-start justify-end gap-2 pt-4">
                    <h1 class="text-[40px] font-black" id="section_name">Academic Programs Overview</h1>
                    <p class="text-[16px]  text-white/60">Senior High School tracks and strands for the current academic
                        year
                    </p>
                </div>

                <div class="flex flex-col items-end justify-center">
                    <p id="studentCount" class="text-[50px] font-bold ">
                    </p>
                    <div class="flex flex-row justify-center items-center opacity-70 gap-2 text-[14px]">
                        <p class="text-[16px]">Active Programs</p>
                    </div>
                </div>


            </div>
            <div class="flex flex-row justify-center items-center w-full gap-4">
                <div
                    class="flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-8 py-6 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-150">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-rr-star flex justify-center items-center"></i>
                        <p class="text-[14px]">Total Students</p>
                    </div>
                    <p class="font-bold text-[24px]"></p>
                </div>

                <div
                    class="flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-8 py-6 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-300">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-rr-lesson flex flex-row justify-center items-center"></i>
                        <p class="text-[14px]">Active Sections</p>
                    </div>
                    <p class="font-bold text-[24px]"></p>
                </div>

                <div
                    class="flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-8 py-6 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-300">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-rr-school flex justify-center items-center"></i>
                        <p class="text-[14px]">Faculty Members</p>
                    </div>
                    <p class="font-bold text-[24px]" id="section_room"></p>
                </div>

                <div
                    class="flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-8 py-6 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-300">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2 ">
                        <i class="fi fi-rr-employee-man-alt flex justify-center items-center"></i>
                        <p class="text-[14px] truncate">Specialized + Applied Subjects</p>
                    </div>
                    <p class="font-bold text-[24px]"></p>
                </div>
            </div>



        </div>
    </div>
@endsection --}}
@section('content')
    <x-alert />

    <div
        class="px-5 text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:text-gray-400 dark:border-gray-300">
        <ul class="flex flex-wrap -mb-px">
            <li class="me-2">
                <a href="{{ route('school-fees.index') }}"
                    class="inline-block p-4 border-b-2 rounded-t-lg 
              {{ Route::is('school-fees.index') ? 'text-blue-600 border-blue-600 dark:text-blue-500 dark:border-blue-500' : 'border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300' }}">
                    School Fees
                </a>
            </li>

            <li class="me-2">
                <a href="{{ route('school-fees.invoices') }}"
                    class="inline-block p-4 border-b-2 rounded-t-lg 
              {{ Route::is('school-fees.invoices') ? 'text-blue-600 border-blue-600 dark:text-blue-500 dark:border-blue-500' : 'border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300' }}">
                    Invoices
                </a>
            </li>

            <li class="me-2">
                <a href="{{ route('school-fees.payments') }}"
                    class="inline-block p-4 border-b-2 rounded-t-lg 
              {{ Route::is('school-fees.payments') ? 'text-blue-600 border-blue-600 dark:text-blue-500 dark:border-blue-500' : 'border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300' }}">
                    Payment History
                </a>
            </li>



        </ul>
    </div>

    @if (Route::is('school-fees.index'))
        <div class="flex flex-row justify-center items-start gap-4">
            <div
                class="flex flex-col justify-start items-start flex-grow p-5 space-y-4 bg-[#f8f8f8] rounded-xl shadow-md border border-[#1e1e1e]/10 w-[40%]">
                <span class="font-semibold text-[18px]">
                    School Fees
                </span>
                <div class="flex flex-row justify-between items-center w-full">
                    <div class="w-full flex flex-row justify-between items-center gap-4">

                        <label for="myCustomSearch"
                            class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-1 px-2 gap-2 w-[40%] hover:ring hover:ring-blue-200 focus-within:ring focus-within:ring-blue-100 focus-within:border-blue-500 transition duration-150 shadow-sm">
                            <i class="fi fi-rs-search flex justify-center items-center text-[#1e1e1e]/60 text-[16px]"></i>
                            <input type="search" name="" id="myCustomSearch"
                                class="my-custom-search bg-transparent outline-none text-[14px] w-full peer"
                                placeholder="Search by lrn, name, grade level, etc.">
                            <button id="clear-btn"
                                class="clear-btn flex justify-center items-center peer-placeholder-shown:hidden peer-not-placeholder-shown:block">
                                <i class="fi fi-rs-cross-small text-[18px] flex justify-center items-center"></i>
                            </button>
                        </label>
                        <div class="flex flex-row justify-start items-center w-full gap-2">
                            <div
                                class="flex flex-row justify-between items-center rounded-lg border border-[#1e1e1e]/10 bg-gray-100 px-3 py-1 gap-2 hover:bg-gray-200 hover:border-[#1e1e1e]/15 transition-all ease-in-out duration-150 shadow-sm">
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
                                class="flex flex-row justify-between items-center rounded-lg border border-[#1e1e1e]/10 bg-gray-100 px-3 py-1 gap-2 focus-within:bg-gray-200 focus-within:border-[#1e1e1e]/15 hover:bg-gray-200 hover:border-[#1e1e1e]/15 transition duration-150 shadow-sm">
                                <select name="" id="program_selection"
                                    class="appearance-none bg-transparent text-[14px] font-medium text-gray-700 w-full cursor-pointer">
                                    <option value="" selected disabled>Program</option>
                                    <option value="" data-id="HUMSS">HUMSS</option>
                                    <option value="" data-id="ABM">ABM</option>
                                </select>
                                <i id="clear-program-filter-btn"
                                    class="fi fi-rr-caret-down text-gray-500 flex justify-center items-center"></i>
                            </div>


                            <div id="grade_selection_container"
                                class="flex flex-row justify-between items-center rounded-lg border border-[#1e1e1e]/10 bg-gray-100 px-3 py-1 gap-2 hover:bg-gray-200 hover:border-[#1e1e1e]/15 transition-all ease-in-out duration-150 shadow-sm">

                                <select name="grade_selection" id="grade_selection"
                                    class="appearance-none bg-transparent text-[14px] font-medium text-gray-700 h-full w-full cursor-pointer">
                                    <option value="" disabled selected>Grade</option>
                                    <option value="" data-putanginamo="Grade 11">Grade 11</option>
                                    <option value="" data-putanginamo="Grade 12">Grade 12</option>
                                </select>
                                <i id="clear-grade-filter-btn"
                                    class="fi fi-rr-caret-down text-gray-500 flex justify-center items-center"></i>

                            </div>


                        </div>
                    </div>

                    <div class="flex flex-row justify-center items-center gap-2">

                        <div class="flex flex-row justify-center items-center truncate">
                            <button id="create-school-fee-modal-btn"
                                class="bg-[#1A3165] p-2 rounded-lg text-[14px] font-semibold flex justify-center items-center gap-2 text-white">
                                <i class="fi fi-rr-plus flex justify-center items-center "></i>
                                Create School Fee
                            </button>
                        </div>

                        <div id="dropdown_2"
                            class="relative space-y-10 h-full flex flex-col justify-start items-center gap-4 cursor-pointer">

                            <div
                                class="group relative inline-flex items-center gap-2 bg-gray-100 border border-[#1e1e1e]/10 text-gray-700 font-semibold py-2 px-3 rounded-lg shadow-sm hover:bg-gray-200 hover:border-[#1e1e1e]/15 transition duration-150">
                                <i class="fi fi-br-menu-dots flex justify-center items-center text-[18px]"></i>
                            </div>

                            <div id="dropdown_selection2"
                                class="absolute top-0 right-0 z-10 bg-[#f8f8f8] flex-col justify-center items-center gap-1 rounded-lg shadow-md border border-[#1e1e1e]/15 py-2 px-1 opacity-0 scale-95 pointer-events-none transition-all duration-200 ease-out translate-y-1">
                                <button id="import-modal-btn"
                                    class="flex-1 flex justify-start items-center px-8 py-2 gap-2 text-[14px] font-medium opacity-80 w-full border-b border-[#1e1e1e]/15 hover:bg-gray-200 truncate">
                                    <i class="fi fi-sr-file-import text-[16px]"></i>Import Students
                                </button>
                                <x-nav-link href="/students/export/excel"
                                    class="flex-1 flex justify-start items-center px-8 py-2 gap-2 text-[14px] font-medium opacity-80 w-full border-b border-[#1e1e1e]/15 hover:bg-gray-200 truncate">
                                    <i class="fi fi-sr-file-excel text-[16px]"></i>Export As .xlsx
                                </x-nav-link>
                                <button
                                    class="flex-1 flex justify-start items-center px-8 py-2 gap-2 text-[14px] font-medium opacity-80 w-full hover:bg-gray-200 truncate">
                                    <i class="fi fi-sr-file-pdf text-[16px]"></i>Export As .pdf
                                </button>
                            </div>

                        </div>
                    </div>


                </div>

                <div class="w-full">
                    <table id="sections" class="w-full table-fixed">
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


    @if (Route::is('school-fees.payments'))
        <div class="flex flex-row justify-center items-start gap-4">
            <div
                class="flex flex-col justify-start items-start flex-grow p-5 space-y-4 bg-[#f8f8f8] rounded-xl shadow-md border border-[#1e1e1e]/10 w-[40%]">
                <span class="font-semibold text-[18px]">
                    Payment History
                </span>
                <div class="flex flex-row justify-between items-center w-full">
                    <div class="w-full flex flex-row justify-between items-center gap-4">
                        <label for="paymentsSearch"
                            class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-1 px-2 gap-2 w-[40%] hover:ring hover:ring-blue-200 focus-within:ring focus-within:ring-blue-100 focus-within:border-blue-500 transition duration-150 shadow-sm">
                            <i class="fi fi-rs-search flex justify-center items-center text-[#1e1e1e]/60 text-[16px]"></i>
                            <input type="search" id="paymentsSearch"
                                class="bg-transparent outline-none text-[14px] w-full peer"
                                placeholder="Search by reference, student, method...">
                        </label>
                    </div>
                </div>

                <div class="w-full">
                    <table id="payments" class="w-full table-fixed">
                        <thead class="text-[14px]">
                            <tr>
                                <th class="w-[4%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-2 py-2">#</th>
                                <th class="w-[12%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">Date
                                </th>
                                <th class="w-[18%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    Reference</th>
                                <th class="w-[16%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    Method</th>
                                <th class="w-[16%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">Type
                                </th>
                                <th class="w-[14%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    Amount</th>
                                <th class="w-[20%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    Student</th>
                                <th class="w-[12%] text-center bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    @if (Route::is('school-fees.invoices'))
        <div class="flex flex-row justify-center items-start gap-4">
            <div
                class="flex flex-col justify-start items-start flex-grow p-5 space-y-4 bg-[#f8f8f8] rounded-xl shadow-md border border-[#1e1e1e]/10 w-[40%]">
                <span class="font-semibold text-[18px]">
                    Invoices
                </span>
                <div class="flex flex-row justify-between items-center w-full">
                    <div class="w-full flex flex-row justify-between items-center gap-4">

                        <label for="myCustomSearch"
                            class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-1 px-2 gap-2 w-[40%] hover:ring hover:ring-blue-200 focus-within:ring focus-within:ring-blue-100 focus-within:border-blue-500 transition duration-150 shadow-sm">
                            <i class="fi fi-rs-search flex justify-center items-center text-[#1e1e1e]/60 text-[16px]"></i>
                            <input type="search" name="" id="myCustomSearch"
                                class="my-custom-search bg-transparent outline-none text-[14px] w-full peer"
                                placeholder="Search by lrn, name, grade level, etc.">
                            <button id="clear-btn"
                                class="clear-btn flex justify-center items-center peer-placeholder-shown:hidden peer-not-placeholder-shown:block">
                                <i class="fi fi-rs-cross-small text-[18px] flex justify-center items-center"></i>
                            </button>
                        </label>
                        <div class="flex flex-row justify-start items-center w-full gap-2">
                            <div
                                class="flex flex-row justify-between items-center rounded-lg border border-[#1e1e1e]/10 bg-gray-100 px-3 py-1 gap-2 hover:bg-gray-200 hover:border-[#1e1e1e]/15 transition-all ease-in-out duration-150 shadow-sm">
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
                                class="flex flex-row justify-between items-center rounded-lg border border-[#1e1e1e]/10 bg-gray-100 px-3 py-1 gap-2 focus-within:bg-gray-200 focus-within:border-[#1e1e1e]/15 hover:bg-gray-200 hover:border-[#1e1e1e]/15 transition duration-150 shadow-sm">
                                <select name="" id="program_selection"
                                    class="appearance-none bg-transparent text-[14px] font-medium text-gray-700 w-full cursor-pointer">
                                    <option value="" selected disabled>Program</option>
                                    <option value="" data-id="HUMSS">HUMSS</option>
                                    <option value="" data-id="ABM">ABM</option>
                                </select>
                                <i id="clear-program-filter-btn"
                                    class="fi fi-rr-caret-down text-gray-500 flex justify-center items-center"></i>
                            </div>


                            <div id="grade_selection_container"
                                class="flex flex-row justify-between items-center rounded-lg border border-[#1e1e1e]/10 bg-gray-100 px-3 py-1 gap-2 hover:bg-gray-200 hover:border-[#1e1e1e]/15 transition-all ease-in-out duration-150 shadow-sm">

                                <select name="grade_selection" id="grade_selection"
                                    class="appearance-none bg-transparent text-[14px] font-medium text-gray-700 h-full w-full cursor-pointer">
                                    <option value="" disabled selected>Grade</option>
                                    <option value="" data-putanginamo="Grade 11">Grade 11</option>
                                    <option value="" data-putanginamo="Grade 12">Grade 12</option>
                                </select>
                                <i id="clear-grade-filter-btn"
                                    class="fi fi-rr-caret-down text-gray-500 flex justify-center items-center"></i>

                            </div>


                        </div>
                    </div>

                    <div class="flex flex-row justify-center items-center gap-2">

                        <div class="flex flex-row justify-center items-center truncate">
                            <button id="create-invoice-modal-btn"
                                class="bg-[#1A3165] p-2 rounded-lg text-[14px] font-semibold flex justify-center items-center gap-2 text-white">
                                <i class="fi fi-rr-plus flex justify-center items-center "></i>
                                Create Invoices
                            </button>
                        </div>

                        <div id="dropdown_2"
                            class="relative space-y-10 h-full flex flex-col justify-start items-center gap-4 cursor-pointer">

                            <div
                                class="group relative inline-flex items-center gap-2 bg-gray-100 border border-[#1e1e1e]/10 text-gray-700 font-semibold py-2 px-3 rounded-lg shadow-sm hover:bg-gray-200 hover:border-[#1e1e1e]/15 transition duration-150">
                                <i class="fi fi-br-menu-dots flex justify-center items-center text-[18px]"></i>
                            </div>

                            <div id="dropdown_selection2"
                                class="absolute top-0 right-0 z-10 bg-[#f8f8f8] flex-col justify-center items-center gap-1 rounded-lg shadow-md border border-[#1e1e1e]/15 py-2 px-1 opacity-0 scale-95 pointer-events-none transition-all duration-200 ease-out translate-y-1">
                                <button id="import-modal-btn"
                                    class="flex-1 flex justify-start items-center px-8 py-2 gap-2 text-[14px] font-medium opacity-80 w-full border-b border-[#1e1e1e]/15 hover:bg-gray-200 truncate">
                                    <i class="fi fi-sr-file-import text-[16px]"></i>Import Students
                                </button>
                                <x-nav-link href="/students/export/excel"
                                    class="flex-1 flex justify-start items-center px-8 py-2 gap-2 text-[14px] font-medium opacity-80 w-full border-b border-[#1e1e1e]/15 hover:bg-gray-200 truncate">
                                    <i class="fi fi-sr-file-excel text-[16px]"></i>Export As .xlsx
                                </x-nav-link>
                                <button
                                    class="flex-1 flex justify-start items-center px-8 py-2 gap-2 text-[14px] font-medium opacity-80 w-full hover:bg-gray-200 truncate">
                                    <i class="fi fi-sr-file-pdf text-[16px]"></i>Export As .pdf
                                </button>
                            </div>

                        </div>
                    </div>


                </div>

                <div class="w-full">
                    <table id="invoices" class="w-full table-fixed">
                        <thead class="text-[14px]">
                            <tr>
                                <th class="w-[3%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-2 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">#</span>
                                </th>

                                <th class="w-[10%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Invoice No.</span>
                                    <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                                </th>

                                <th class="w-[45%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Student</span>
                                    <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                                </th>

                                <th class="w-[15%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Status</span>
                                    <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                                </th>
                                <th class="w-[15%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Total</span>
                                    <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                                </th>
                                <th class="w-[15%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Remaining Balance</span>
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
@endsection

@push('scripts')
    <script type="module">
        import {
            dropDown
        } from "/js/dropDown.js";
        import {
            clearSearch
        } from "/js/clearSearch.js"
        import {
            initModal
        } from "/js/modal.js";
        import {
            showAlert
        } from "/js/alert.js";
        import {
            initCustomDataTable
        } from "/js/initTable.js";
        import {
            showLoader,
            hideLoader
        } from "/js/loader.js";

        let table1;
        let selectedGrade = '';
        let selectedProgram = '';
        let selectedPageLength = '';




        document.addEventListener("DOMContentLoaded", function() {

            initModal('create-school-fee-modal', 'create-school-fee-modal-btn', 'create-school-fee-modal-close-btn',
                'create-school-fee-modal-cancel-btn',
                'modal-container-1');
            initModal('create-invoice-modal', 'create-invoice-modal-btn', 'create-invoice-modal-close-btn',
                'create-invoice-modal-cancel-btn',
                'modal-container-2');

            // Add custom cancel button handler for invoice modal
            document.getElementById('create-invoice-modal-cancel-btn').addEventListener('click', function() {
                closeModal('create-invoice-modal', 'modal-container-2');
                resetInvoiceForm();
            });
            initModal('edit-section-modal', 'edit-section-modal-btn', 'edit-section-close-btn',
                'edit-section-cancel-btn',
                'modal-container-3');

            let studentSeach = document.querySelector('#studentSearch');
            let studentCount = document.querySelector('#studentCount');
            let sectionName = document.querySelector('#section_name');
            let sectionRoom = document.querySelector('#section_room');

            // const fileInput = document.getElementById('fileInput');
            // const fileName = document.getElementById('fileName');

            // fileInput.addEventListener('change', function() {
            //     fileName.textContent = this.files.length > 0 ? this.files[0].name : 'No file chosen';
            // });

            //Overriding default search input
            const customSearch1 = document.getElementById("myCustomSearch");

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
                        width: '15%'
                    },
                    {
                        data: 'student',
                        width: '15%'
                    },
                    {
                        data: 'status',
                        width: '15%'
                    },
                    {
                        data: 'total',
                        width: '15%'
                    },
                    {
                        data: 'balance',
                        width: '15%'
                    },
                    {
                        data: 'id',
                        className: 'text-center',
                        width: '15%',
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
                'myCustomSearch', {
                    grade_filter: selectedGrade,
                    program_filter: selectedProgram,
                    pageLength: selectedPageLength
                }

            )

            let paymentHistory = initCustomDataTable(
                'payments',
                `/getPayments`,
                [{
                        data: 'index',
                        width: '4%'
                    },
                    {
                        data: 'date',
                        width: '12%'
                    },
                    {
                        data: 'reference_no',
                        width: '18%'
                    },
                    {
                        data: 'method',
                        width: '12%'
                    },
                    {
                        data: 'type',
                        width: '12%'
                    },
                    {
                        data: 'amount',
                        width: '14%'
                    },
                    {
                        data: 'student',
                        width: '20%'
                    },
                    {
                        data: 'invoice_id',
                        width: '17%',
                        className: 'text-center',
                        orderable: false,
                        searchable: false,
                        render: function(id, type, row) {
                            return `
                                <a href="/invoice/${id}?from=history" class="group relative inline-flex items-center gap-2 bg-blue-100 text-blue-500 font-semibold px-3 py-1 rounded-xl hover:bg-blue-500 hover:ring hover:ring-blue-200 hover:text-white transition duration-150 ">
                                    <span class="relative w-4 h-4">
                                        <i class="fi fi-rs-eye flex justify-center items-center absolute inset-0 group-hover:opacity-0 transition-opacity text-[16px]"></i>
                                        <i class="fi fi-ss-eye flex justify-center items-center absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity text-[16px]"></i>
                                    </span>
                                    View Invoice
                                </a>`;
                        }
                    },
                ],

                [
                    [0, 'desc']
                ],
                'myCustomSearch', {
                    grade_filter: selectedGrade,
                    program_filter: selectedProgram,
                    pageLength: selectedPageLength
                }

            )


            table1 = new DataTable('#sections', {
                paging: true,
                searching: true,
                autoWidth: false,
                serverSide: true,
                processing: true,
                ajax: {
                    url: `/getSchoolFees`,
                    data: function(d) {

                        d.grade_filter = selectedGrade;
                        d.program_filter = selectedProgram;
                        d.pageLength = selectedPageLength;
                    }
                },
                order: [
                    [6, 'desc']
                ],
                columnDefs: [{
                        width: '3%',
                        targets: 0,
                        className: 'text-center'
                    }, // index
                    {
                        width: '20%',
                        targets: 1
                    }, // namr
                    {
                        width: '15%',
                        targets: 2
                    }, // applied_to_program
                    {
                        width: '15%',
                        targets: 3
                    }, // applied_to_level
                    {
                        width: '10%',
                        targets: 3
                    }, // amount
                    {
                        width: '15%',
                        targets: 4,
                        className: 'text-center'
                    } // actions
                ],
                layout: {
                    topStart: null,
                    topEnd: null,
                    bottomStart: 'info',
                    bottomEnd: 'paging',
                },
                columns: [{
                        data: 'index'
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'applied_to_program'
                    },
                    {
                        data: 'applied_to_level'
                    },
                    {
                        data: 'amount'
                    },
                    {
                        data: 'id', // pass ID for rendering the link
                        render: function(data, type, row) {
                            return `
                            <div class='flex flex-row justify-center items-center opacity-100'>

                                <a href="/program/${data}/sections" class="group relative inline-flex items-center gap-2 bg-blue-100 text-blue-500 font-semibold px-3 py-1 rounded-xl hover:bg-blue-500 hover:ring hover:ring-blue-200 hover:text-white transition duration-150 ">

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
            });

            customSearch1.addEventListener("input", function() {
                table1.search(this.value).draw();
            });

            table1.on('draw', function() {
                let rows = document.querySelectorAll('#sections tbody tr');

                rows.forEach(function(row) {
                    // Add hover style to the row
                    row.classList.add(
                        'hover:bg-gray-200',
                        'transition',
                        'duration-150'
                    );

                    // Style all cells in the row
                    let cells = row.querySelectorAll('td');
                    cells.forEach(function(cell) {
                        cell.classList.add(
                            'px-4', // Horizontal padding
                            'py-1', // Vertical padding
                            'text-start', // Align text left
                            'font-regular',
                            'text-[14px]',
                            'opacity-80',
                            'truncate',
                            'border-t',
                            'border-[#1e1e1e]/10',
                            'font-semibold'
                        );
                    });
                });
            });

            table1.on("init", function() {
                const defaultSearch = document.querySelector("#dt-search-0");
                if (defaultSearch) {
                    defaultSearch.remove();
                }

            });

            clearSearch('clear-btn', 'myCustomSearch', table1)

            let programSelection = document.querySelector('#program_selection');
            let gradeSelection = document.querySelector('#grade_selection');
            let pageLengthSelection = document.querySelector('#page-length-selection');

            let clearGradeFilterBtn = document.querySelector('#clear-grade-filter-btn');
            let gradeContainer = document.querySelector('#grade_selection_container');

            programSelection.addEventListener('change', (e) => {

                let selectedOption = e.target.selectedOptions[0];
                let id = selectedOption.getAttribute('data-id');

                selectedProgram = id;
                table1.draw();

                //console.log(id);
            })

            pageLengthSelection.addEventListener('change', (e) => {

                let selectedPageLength = parseInt(e.target.value, 10);

                table1.page.len(selectedPageLength).draw();

                //console.log(id);
            })

            gradeSelection.addEventListener('change', (e) => {

                let selectedOption = e.target.selectedOptions[0];
                let email = selectedOption.getAttribute('data-putanginamo');

                selectedGrade = email;
                table1.draw();

                let clearGradeFilterRem = ['text-gray-500', 'fi-rr-caret-down'];
                let clearGradeFilterAdd = ['fi-bs-cross-small', 'cursor-pointer', 'text-[#1A3165]'];
                let gradeSelectionRem = ['border-[#1e1e1e]/10', 'text-gray-700'];
                let gradeSelectionAdd = ['text-[#1A3165]'];
                let gradeContainerRem = ['bg-gray-100'];
                let gradeContainerAdd = ['bg-[#1A73E8]/15', 'bg-[#1A73E8]/15', 'border-[#1A73E8]',
                    'hover:bg-[#1A73E8]/25'
                ];

                clearGradeFilterBtn.classList.remove(...clearGradeFilterRem);
                clearGradeFilterBtn.classList.add(...clearGradeFilterAdd);
                gradeSelection.classList.remove(...gradeSelectionRem);
                gradeSelection.classList.add(...gradeSelectionAdd);
                gradeContainer.classList.remove(...gradeContainerRem);
                gradeContainer.classList.add(...gradeContainerAdd);


                handleClearGradeFilter(selectedOption)
            })

            function handleClearGradeFilter(selectedOption) {

                clearGradeFilterBtn.addEventListener('click', () => {

                    gradeContainer.classList.remove('bg-[#1A73E8]/15')
                    gradeContainer.classList.remove('border-blue-300')
                    gradeContainer.classList.remove('hover:bg-blue-300')
                    clearGradeFilterBtn.classList.remove('fi-bs-cross-small');

                    clearGradeFilterBtn.classList.add('fi-rr-caret-down');
                    gradeContainer.classList.add('bg-gray-100')
                    gradeSelection.classList.remove('text-[#1A3165]')
                    gradeSelection.classList.add('text-gray-700')
                    clearGradeFilterBtn.classList.remove('text-[#1A3165]')
                    clearGradeFilterBtn.classList.add('text-gray-500')


                    gradeSelection.selectedIndex = 0
                    selectedGrade = '';
                    table1.draw();
                })

            }

            window.onload = function() {
                gradeSelection.selectedIndex = 0
                programSelection.selectedIndex = 0
                pageLengthSelection.selectedIndex = 0
            }

            dropDown('dropdown_2', 'dropdown_selection2');
            dropDown('dropdown_btn', 'dropdown_selection');

            if (studentSeach) {

                studentSeach.addEventListener('input', function(e) {
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
                    feesContainer.innerHTML = '';
                    studentInfoSection.classList.add('hidden');
                    feesSection.classList.add('hidden');
                    totalSection.classList.add('hidden');
                    searchStatus.classList.add('hidden');
                    submitBtn.disabled = true;

                    let searchTerm = e.target.value.trim();
                    if (searchTerm.length < 2) {
                        studentSeach.classList.remove('ring-2', 'ring-red-500', 'ring-green-500',
                            'border-red-500', 'border-green-500');
                        studentSeach.classList.add('border-gray-300');

                        fullName.innerHTML = '-';
                        lrn.innerHTML = '-';
                        program.innerHTML = '-';
                        level.innerHTML = '-';

                        feesmsg.innerHTML = 'Search for a student to see applicable fees';
                        feesmsg.classList.remove('hidden');
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
                                    studentSeach.classList.remove('ring-green-500', 'border-green-500');
                                    studentSeach.classList.add('ring-2', 'ring-red-500',
                                        'border-red-500');

                                    feesmsg.innerHTML =
                                        'Student not found. Please check the LRN or name.';
                                    feesmsg.classList.remove('hidden');

                                } else if (data.data !== null) {
                                    // Student found
                                    studentSeach.classList.remove('ring-red-500', 'border-red-500');
                                    studentSeach.classList.add('ring-2', 'ring-green-500',
                                        'border-green-500');

                                    fullName.innerHTML = data.data.full_name ||
                                        `${data.data.first_name || ''} ${data.data.last_name || ''}`
                                        .trim() || '-';
                                    lrn.innerHTML = data.data.lrn || '-';
                                    program.innerHTML = data.data.program || '-';
                                    level.innerHTML = data.data.grade_level || '-';
                                    studentId.value = data.data.id;

                                    // Show student info section
                                    studentInfoSection.classList.remove('hidden');

                                    // Render school fees checkboxes
                                    feesmsg.classList.add('hidden');
                                    if (data.fees && data.fees.length > 0) {
                                        feesSection.classList.remove('hidden');

                                        data.fees.forEach(fee => {
                                            let feeItem = document.createElement('div');
                                            feeItem.classList.add('flex', 'items-center',
                                                'justify-between', 'p-2', 'bg-white',
                                                'rounded-md', 'border', 'border-gray-200',
                                                'hover:border-blue-300', 'transition-colors'
                                            );

                                            let leftDiv = document.createElement('div');
                                            leftDiv.classList.add('flex', 'items-center',
                                                'gap-2');

                                            let checkbox = document.createElement('input');
                                            checkbox.type = 'checkbox';
                                            checkbox.name = 'school_fees[]';
                                            checkbox.value = fee.id;
                                            checkbox.classList.add('w-3', 'h-3',
                                                'text-blue-600',
                                                'border-gray-300', 'rounded',
                                                'focus:ring-blue-500');
                                            checkbox.addEventListener('change', calculateTotal);

                                            let label = document.createElement('label');
                                            label.textContent = fee.name;
                                            label.classList.add('text-xs', 'font-medium',
                                                'text-gray-900', 'cursor-pointer');

                                            let amountSpan = document.createElement('span');
                                            amountSpan.textContent =
                                                `₱${parseFloat(fee.amount).toLocaleString('en-PH', {minimumFractionDigits: 2})}`;
                                            amountSpan.classList.add('text-xs', 'font-semibold',
                                                'text-green-600');

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
                                        feesmsg.innerHTML =
                                            'No applicable fees found for this student.';
                                        feesmsg.classList.remove('hidden');
                                    }

                                } else {
                                    feesmsg.innerHTML = 'Student already has an invoice assigned.';
                                    feesmsg.classList.remove('hidden');
                                }
                            } else {
                                studentSeach.classList.remove('ring-green-500', 'border-green-500');
                                studentSeach.classList.add('ring-2', 'ring-red-500', 'border-red-500');

                                feesmsg.innerHTML = 'Error searching for student. Please try again.';
                                feesmsg.classList.remove('hidden');
                            }
                        })
                        .catch(err => {
                            searchStatus.classList.add('hidden');
                            studentSeach.classList.remove('ring-green-500', 'border-green-500');
                            studentSeach.classList.add('ring-2', 'ring-red-500', 'border-red-500');

                            feesmsg.innerHTML = 'Error searching for student. Please try again.';
                            feesmsg.classList.remove('hidden');
                            console.error(err);
                        });
                });

            }

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

                totalAmountElement.textContent = `₱${total.toLocaleString('en-PH', {minimumFractionDigits: 2})}`;

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
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
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
                        console.error(err);
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

                // Clear search input styling
                studentSearch.classList.remove('ring-2', 'ring-red-500', 'ring-green-500', 'border-red-500',
                    'border-green-500');
                studentSearch.classList.add('border-gray-300');

                // Hide sections
                studentInfoSection.classList.add('hidden');
                feesSection.classList.add('hidden');
                totalSection.classList.add('hidden');

                // Show default message
                feesmsg.innerHTML = 'Search for a student to see applicable fees';
                feesmsg.classList.remove('hidden');

                // Disable submit button
                submitBtn.disabled = true;

                // Reset Select All checkbox
                let selectAllCheckbox = document.getElementById('select-all-fees');
                if (selectAllCheckbox) {
                    selectAllCheckbox.checked = false;
                    selectAllCheckbox.indeterminate = false;
                }
            }


            // document.getElementById('add-student-form').addEventListener('submit', function(e) {
            //     e.preventDefault();

            //     closeModal();

            //     let form = e.target;
            //     let formData = new FormData(form);

            //     // Show loader
            //     showLoader("Adding...");

            //     fetch(`/assign-section/${sectionId}`, {
            //             method: "POST",
            //             body: formData,
            //             headers: {
            //                 "X-CSRF-TOKEN": "{{ csrf_token() }}"
            //             }
            //         })
            //         .then(response => response.json())
            //         .then(data => {
            //             hideLoader();

            //             console.log(data)

            //             if (data.success) {

            //                 studentCount.innerHTML = data.count;
            //                 closeModal('add-student-modal', 'modal-container-2');
            //                 showAlert('success', data.success);
            //                 table1.draw();

            //             } else if (data.error) {

            //                 closeModal('add-student-modal', 'modal-container-2');
            //                 showAlert('error', data.error);
            //             }
            //         })
            //         .catch(err => {
            //             hideLoader();

            //             closeModal('add-student-modal', 'modal-container-2');
            //             showAlert('error', 'Something went wrong');
            //         });
            // });


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




        });
    </script>
@endpush
