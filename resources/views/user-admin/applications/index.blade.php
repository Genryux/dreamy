@extends('layouts.admin')
@section('modal')
    @if (Route::is('applications.pending'))
        {{-- add student modal --}}
        <x-modal modal_id="create-section-modal" modal_name="Create Section" close_btn_id="create-section-modal-close-btn"
            modal_container_id="modal-container-1">
            <x-slot name="modal_icon">
                <i class='fi fi-rr-progress-upload flex justify-center items-center '></i>

            </x-slot>

            <form id="create-section-form" method="post" action="/section" class="p-6">
                @csrf


            </form>

            <x-slot name="modal_info">

            </x-slot>

            <x-slot name="modal_buttons">
                <button id="create-section-cancel-btn"
                    class="bg-gray-100 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-md text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-200 hover:ring hover:ring-gray-200 transition duration-150">
                    Cancel
                </button>
                {{-- This button will acts as the submit button --}}
                <button type="submit" form="create-section-form" name="action" value="create-section"
                    class="bg-[#199BCF] text-[14px] px-3 py-2 rounded-md text-[#f8f8f8] font-bold hover:ring hover:ring-[#C8A165]/40 hover:bg-[#C8A165] transition duration-200 shadow-sm">
                    Continue
                </button>
            </x-slot>

        </x-modal>
    @endif
@endsection
@section('header')
    <div class="flex flex-row justify-between items-start text-start px-[14px] py-2">
        <div>
            <h1 class="text-[20px] font-black">Application Management</h1>
            <p class="text-[14px]  text-gray-900/60">View and manage student applications across different statuses.
            </p>
        </div>
    </div>
@endsection
@section('stat')
    <div class="flex justify-center items-center">
        <div
            class="flex flex-col justify-center items-center flex-grow px-10 pb-10 pt-2 bg-gradient-to-br from-[#199BCF] to-[#1A3165] rounded-xl shadow-[#199BCF]/30 shadow-xl gap-2 text-white">

            <div class="flex flex-row items-center justify-between w-full gap-4 py-2 rounded-lg ">

                <div class="flex flex-col items-start justify-end gap-2 pt-4">
                    <h1 class="text-[40px] font-black">Application Summary</h1>
                    <p class="text-[16px] text-white/60">
                        Monitor and manage student applications across all statuses
                    </p>
                </div>

                <div class="flex flex-col items-end justify-center">
                    <p id="totalApplicationsCount" class="text-[50px] font-bold">0</p>
                    <div class="flex flex-row justify-center items-center opacity-70 gap-2 text-[14px]">
                        <p class="text-[16px]">Total Applications</p>
                    </div>
                </div>


            </div>
            <div class="flex flex-row justify-center items-center w-full gap-4">
                <div
                    class="flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-8 py-6 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-150">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-rr-clock flex justify-center items-center"></i>
                        <p class="text-[14px]">Pending Applications</p>
                    </div>
                    <p class="font-bold text-[24px]" id="pendingApplicationsCount">0</p>
                    <p class="text-[12px] truncate text-gray-300">Applications awaiting review</p>
                </div>

                <div
                    class="flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-8 py-6 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-300">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-rr-check-circle flex flex-row justify-center items-center"></i>
                        <p class="text-[14px]">Accepted Applications</p>
                    </div>
                    <p class="font-bold text-[24px]" id="acceptedApplicationsCount">0</p>
                    <p class="text-[12px] truncate text-gray-300">Applications that have been accepted</p>
                </div>

                <div
                    class="flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-8 py-6 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-300">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-rr-document flex justify-center items-center"></i>
                        <p class="text-[14px]">Pending Documents</p>
                    </div>
                    <p class="font-bold text-[24px]" id="pendingDocumentsCount">0</p>
                    <p class="text-[12px] truncate text-gray-300">Applicants with pending document</p>
                </div>

                <div
                    class="flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-8 py-6 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-300">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2 ">
                        <i class="fi fi-rr-cross-circle flex justify-center items-center"></i>
                        <p class="text-[14px] truncate">Rejected Applications</p>
                    </div>
                    <p class="font-bold text-[24px]" id="rejectedApplicationsCount">0</p>
                    <p class="text-[12px] truncate text-gray-300">Applications that have been rejected</p>
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
            <li class="me-2">
                <a href="{{ route('applications.pending') }}"
                    class="inline-block p-4 border-b-2 rounded-t-lg 
              {{ Route::is('applications.pending') ? 'text-blue-600 border-blue-600 dark:text-blue-500 dark:border-blue-500' : 'border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300' }}">
                    Pending
                </a>
            </li>

            <li class="me-2">
                <a href="{{ route('applications.accepted') }}"
                    class="inline-block p-4 border-b-2 rounded-t-lg 
              {{ Route::is('applications.accepted') ? 'text-blue-600 border-blue-600 dark:text-blue-500 dark:border-blue-500' : 'border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300' }}">
                    Accepted
                </a>
            </li>

            <li class="me-2">
                <a href="{{ route('applications.pending-documents') }}"
                    class="inline-block p-4 border-b-2 rounded-t-lg 
              {{ Route::is('applications.pending-documents') ? 'text-blue-600 border-blue-600 dark:text-blue-500 dark:border-blue-500' : 'border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300' }}">
                    Pending Documents
                </a>
            </li>

            <li class="me-2">
                <a href="{{ route('applications.rejected') }}"
                    class="inline-block p-4 border-b-2 rounded-t-lg 
              {{ Route::is('applications.rejected') ? 'text-blue-600 border-blue-600 dark:text-blue-500 dark:border-blue-500' : 'border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300' }}">
                    Rejected
                </a>
            </li>

        </ul>
    </div>

    @if (Route::is('applications.pending'))
        <div class="flex flex-row justify-center items-start gap-4">
            <div
                class="flex flex-col justify-start items-center flex-grow p-5 space-y-4 bg-[#f8f8f8] rounded-xl shadow-md border border-[#1e1e1e]/10 w-[40%]">
                <div class="flex flex-col my-2 justify-center items-center w-full">
                    <span class="font-semibold text-[18px]">
                        Pending Applications
                    </span>
                    <span class="font-medium text-gray-400 text-[14px]">
                        Applications awaiting review and approval
                    </span>
                </div>
                <div class="flex flex-row justify-between items-center w-full">

                    <div class="w-full flex flex-row justify-between items-center gap-4">

                        <label for="myCustomSearch"
                            class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-2 gap-2 w-[40%] hover:ring hover:ring-blue-200 focus-within:ring focus-within:ring-blue-100 focus-within:border-blue-500 transition duration-150 shadow-sm">
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

                            <div id="grade_selection_container"
                                class="flex flex-row justify-between items-center rounded-lg border border-[#1e1e1e]/10 bg-gray-100 px-3 py-2 gap-2 hover:bg-gray-200 hover:border-[#1e1e1e]/15 transition-all ease-in-out duration-150 shadow-sm">

                                <select name="grade_selection" id="grade_selection"
                                    class="appearance-none bg-transparent text-[14px] font-medium text-gray-700 h-full w-full cursor-pointer">
                                    <option value="" disabled selected>Grade</option>
                                    <option value="" data-putanginamo="Grade 11">Grade 11</option>
                                    <option value="" data-putanginamo="Grade 12">Grade 12</option>
                                </select>
                                <i id="clear-grade-filter-btn"
                                    class="fi fi-rr-caret-down text-gray-500 flex justify-center items-center"></i>

                            </div>

                            <!-- Program Filter -->
                            <div id="program_selection_container"
                                class="flex flex-row justify-between items-center rounded-lg border border-[#1e1e1e]/10 bg-gray-100 px-3 py-2 gap-2 focus-within:bg-gray-200 focus-within:border-[#1e1e1e]/15 hover:bg-gray-200 hover:border-[#1e1e1e]/15 transition duration-150 shadow-sm">
                                <select name="" id="program_selection"
                                    class="appearance-none bg-transparent text-[14px] font-medium text-gray-700 w-full cursor-pointer">
                                    <option value="" selected>All Programs</option>
                                    @foreach (\App\Models\Program::where('status', 'active')->get() as $program)
                                        <option value="{{ $program->code }}">{{ $program->code }}</option>
                                    @endforeach
                                </select>
                                <i id="clear-program-filter-btn"
                                    class="fi fi-rr-caret-down text-gray-500 flex justify-center items-center"></i>
                            </div>


                        </div>
                    </div>

                </div>

                <!-- Table Layout Container -->
                <div id="table-layout-container" class="w-full">
                    <table id="pending-table" class="w-full table-fixed">
                        <thead class="text-[14px]">
                            <tr>
                                <th class="w-[3%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-2 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">#</span>
                                </th>
                                <th class="w-[15%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Applicant ID</span>
                                    <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                                </th>
                                <th class="w-[20%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Applicant Name</span>
                                    <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                                </th>
                                <th class="w-[15%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Program</span>
                                    <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                                </th>
                                <th class="w-[15%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Grade Level</span>
                                    <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                                </th>
                                <th class="w-[12%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Date Applied</span>
                                    <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                                </th>
                                <th class="w-[10%] text-center bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
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
    @if (Route::is('applications.accepted'))
        <div class="flex flex-row justify-center items-start gap-4">
            <div
                class="flex flex-col justify-start items-start flex-grow p-5 space-y-4 bg-[#f8f8f8] rounded-xl shadow-md border border-[#1e1e1e]/10 w-[40%]">
                <div class="flex flex-col my-2 justify-center items-center w-full">
                    <span class="font-semibold text-[18px]">
                        Accepted Applications
                    </span>
                    <span class="font-medium text-gray-400 text-[14px]">
                        Applications that have been accepted and are in the admission process
                    </span>
                </div>
                <div class="flex flex-row justify-between items-center w-full">

                    <div class="w-full flex flex-row justify-between items-center gap-4">

                        <label for="myCustomSearch"
                            class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-2 gap-2 w-[40%] hover:ring hover:ring-blue-200 focus-within:ring focus-within:ring-blue-100 focus-within:border-blue-500 transition duration-150 shadow-sm">
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
                            <div id="status_selection_container"
                                class="flex flex-row justify-between items-center rounded-lg border border-[#1e1e1e]/10 bg-gray-100 px-3 py-2 gap-2 focus-within:bg-gray-200 focus-within:border-[#1e1e1e]/15 hover:bg-gray-200 hover:border-[#1e1e1e]/15 transition duration-150 shadow-sm">
                                <select name="" id="status_selection"
                                    class="appearance-none bg-transparent text-[14px] font-medium text-gray-700 w-full cursor-pointer">
                                    <option value="" selected>All Status</option>
                                    <option value="Accepted">Accepted</option>
                                    <option value="Scheduled">Scheduled</option>
                                    <option value="Taking-Exam">Taking-Exam</option>
                                    <option value="Exam-Failed">Exam-Failed</option>
                                </select>
                                <i id="clear-status-filter-btn"
                                    class="fi fi-rr-caret-down text-gray-500 flex justify-center items-center"></i>
                            </div>

                            <div id="grade_selection_container"
                                class="flex flex-row justify-between items-center rounded-lg border border-[#1e1e1e]/10 bg-gray-100 px-3 py-2 gap-2 hover:bg-gray-200 hover:border-[#1e1e1e]/15 transition-all ease-in-out duration-150 shadow-sm">

                                <select name="grade_selection" id="grade_selection"
                                    class="appearance-none bg-transparent text-[14px] font-medium text-gray-700 h-full w-full cursor-pointer">
                                    <option value="" disabled selected>Year Level</option>
                                    <option value="" data-putanginamo="Grade 11">Grade 11</option>
                                    <option value="" data-putanginamo="Grade 12">Grade 12</option>
                                </select>
                                <i id="clear-grade-filter-btn"
                                    class="fi fi-rr-caret-down text-gray-500 flex justify-center items-center"></i>

                            </div>
                            <div id="program_selection_container"
                                class="flex flex-row justify-between items-center rounded-lg border border-[#1e1e1e]/10 bg-gray-100 px-3 py-2 gap-2 focus-within:bg-gray-200 focus-within:border-[#1e1e1e]/15 hover:bg-gray-200 hover:border-[#1e1e1e]/15 transition duration-150 shadow-sm">
                                <select name="" id="program_selection"
                                    class="appearance-none bg-transparent text-[14px] font-medium text-gray-700 w-full cursor-pointer">
                                    <option value="" selected>All Programs</option>
                                    @foreach (\App\Models\Program::where('status', 'active')->get() as $program)
                                        <option value="{{ $program->code }}">{{ $program->code }}</option>
                                    @endforeach
                                </select>
                                <i id="clear-program-filter-btn"
                                    class="fi fi-rr-caret-down text-gray-500 flex justify-center items-center"></i>
                            </div>

                        </div>
                    </div>

                </div>

                <div class="w-full">
                    <table id="accepted-table" class="w-full table-fixed">
                        <thead class="text-[14px]">
                            <tr>
                                <th class="w-[3%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-2 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">#</span>
                                </th>
                                <th class="w-[15%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Applicant ID</span>
                                    <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                                </th>
                                <th class="w-[20%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Applicant Name</span>
                                    <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                                </th>
                                <th class="w-[15%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Program</span>
                                    <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                                </th>
                                <th class="w-[15%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Grade Level</span>
                                    <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                                </th>
                                <th class="w-[12%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Status</span>
                                    <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                                </th>
                                <th class="w-[12%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Accepted At</span>
                                    <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                                </th>
                                <th class="w-[10%] text-center bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
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

    @if (Route::is('applications.pending-documents'))
        <div class="flex flex-row justify-center items-start gap-4">
            <div
                class="flex flex-col justify-start items-start flex-grow p-5 space-y-4 bg-[#f8f8f8] rounded-xl shadow-md border border-[#1e1e1e]/10 w-[40%]">
                <div class="flex flex-col my-2 justify-center items-center w-full">
                    <span class="font-semibold text-[18px]">
                        Pending Documents
                    </span>
                    <span class="font-medium text-gray-400 text-[14px]">
                        Applicants who need to submit required documents
                    </span>
                </div>
                <div class="flex flex-row justify-between items-center w-full">

                    <div class="w-full flex flex-row justify-between items-center gap-4">

                        <label for="myCustomSearch"
                            class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-2 gap-2 w-[40%] hover:ring hover:ring-blue-200 focus-within:ring focus-within:ring-blue-100 focus-within:border-blue-500 transition duration-150 shadow-sm">
                            <i class="fi fi-rs-search flex justify-center items-center text-[#1e1e1e]/60 text-[16px]"></i>
                            <input type="search" name="" id="myCustomSearch"
                                class="my-custom-search bg-transparent outline-none text-[14px] w-full peer"
                                placeholder="Search by applicant name, program, etc.">
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

                            <div id="grade_selection_container"
                                class="flex flex-row justify-between items-center rounded-lg border border-[#1e1e1e]/10 bg-gray-100 px-3 py-2 gap-2 hover:bg-gray-200 hover:border-[#1e1e1e]/15 transition-all ease-in-out duration-150 shadow-sm">

                                <select name="grade_selection" id="grade_selection"
                                    class="appearance-none bg-transparent text-[14px] font-medium text-gray-700 h-full w-full cursor-pointer">
                                    <option value="" disabled selected>Grade</option>
                                    <option value="" data-putanginamo="Grade 11">Grade 11</option>
                                    <option value="" data-putanginamo="Grade 12">Grade 12</option>
                                </select>
                                <i id="clear-grade-filter-btn"
                                    class="fi fi-rr-caret-down text-gray-500 flex justify-center items-center"></i>

                            </div>

                            <div id="program_selection_container"
                                class="flex flex-row justify-between items-center rounded-lg border border-[#1e1e1e]/10 bg-gray-100 px-3 py-2 gap-2 focus-within:bg-gray-200 focus-within:border-[#1e1e1e]/15 hover:bg-gray-200 hover:border-[#1e1e1e]/15 transition duration-150 shadow-sm">
                                <select name="" id="program_selection"
                                    class="appearance-none bg-transparent text-[14px] font-medium text-gray-700 w-full cursor-pointer">
                                    <option value="" selected>All Programs</option>
                                    @foreach (\App\Models\Program::where('status', 'active')->get() as $program)
                                        <option value="{{ $program->code }}">{{ $program->code }}</option>
                                    @endforeach
                                </select>
                                <i id="clear-program-filter-btn"
                                    class="fi fi-rr-caret-down text-gray-500 flex justify-center items-center"></i>
                            </div>

                        </div>
                    </div>

                </div>

                <div class="w-full">
                    <table id="pending-documents-table" class="w-full table-fixed">
                        <thead class="text-[14px]">
                            <tr>
                                <th class="w-[3%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-2 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">#</span>
                                </th>
                                <th class="w-[20%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Applicant Id</span>
                                    <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                                </th>
                                <th class="w-[20%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Applicant Name</span>
                                    <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                                </th>

                                <th class="w-[15%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Program</span>
                                    <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                                </th>

                                <th class="w-[15%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Grade Level</span>
                                    <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                                </th>
                                <th class="w-[15%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Documents Status</span>
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

    @if (Route::is('applications.rejected'))
        <div class="flex flex-row justify-center items-start gap-4">
            <div
                class="flex flex-col justify-start items-start flex-grow p-5 space-y-4 bg-[#f8f8f8] rounded-xl shadow-md border border-[#1e1e1e]/10 w-[40%]">
                <div class="flex flex-col my-2 justify-center items-center w-full">
                    <span class="font-semibold text-[18px]">
                        Rejected Applications
                    </span>
                    <span class="font-medium text-gray-400 text-[14px]">
                        Applications that have been rejected
                    </span>
                </div>
                <div class="flex flex-row justify-between items-center w-full">

                    <div class="w-full flex flex-row justify-between items-center gap-4">

                        <label for="myCustomSearch"
                            class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-2 gap-2 w-[40%] hover:ring hover:ring-blue-200 focus-within:ring focus-within:ring-blue-100 focus-within:border-blue-500 transition duration-150 shadow-sm">
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

                            <div id="grade_selection_container"
                                class="flex flex-row justify-between items-center rounded-lg border border-[#1e1e1e]/10 bg-gray-100 px-3 py-2 gap-2 hover:bg-gray-200 hover:border-[#1e1e1e]/15 transition-all ease-in-out duration-150 shadow-sm">

                                <select name="grade_selection" id="grade_selection"
                                    class="appearance-none bg-transparent text-[14px] font-medium text-gray-700 h-full w-full cursor-pointer">
                                    <option value="" disabled selected>Grade</option>
                                    <option value="" data-putanginamo="Grade 11">Grade 11</option>
                                    <option value="" data-putanginamo="Grade 12">Grade 12</option>
                                </select>
                                <i id="clear-grade-filter-btn"
                                    class="fi fi-rr-caret-down text-gray-500 flex justify-center items-center"></i>

                            </div>

                            <div id="program_selection_container"
                                class="flex flex-row justify-between items-center rounded-lg border border-[#1e1e1e]/10 bg-gray-100 px-3 py-2 gap-2 focus-within:bg-gray-200 focus-within:border-[#1e1e1e]/15 hover:bg-gray-200 hover:border-[#1e1e1e]/15 transition duration-150 shadow-sm">
                                <select name="" id="program_selection"
                                    class="appearance-none bg-transparent text-[14px] font-medium text-gray-700 w-full cursor-pointer">
                                    <option value="" selected>All Programs</option>
                                    @foreach (\App\Models\Program::where('status', 'active')->get() as $program)
                                        <option value="{{ $program->code }}">{{ $program->code }}</option>
                                    @endforeach
                                </select>
                                <i id="clear-program-filter-btn"
                                    class="fi fi-rr-caret-down text-gray-500 flex justify-center items-center"></i>
                            </div>

                        </div>
                    </div>

                </div>

                <div class="w-full">
                    <table id="rejected-table" class="w-full table-fixed">
                        <thead class="text-[14px]">
                            <tr>
                                <th class="w-[3%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-2 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">#</span>
                                </th>
                                <th class="w-[15%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Applicant ID</span>
                                    <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                                </th>
                                <th class="w-[20%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Applicant Name</span>
                                    <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                                </th>
                                <th class="w-[15%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Program</span>
                                    <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                                </th>
                                <th class="w-[15%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Grade Level</span>
                                    <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                                </th>
                                <th class="w-[12%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Status</span>
                                    <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                                </th>
                                <th class="w-[12%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Rejected At</span>
                                    <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                                </th>
                                <th class="w-[10%] text-center bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
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

        // Global variables for applications
        window.selectedGrade = '';
        window.selectedProgram = '';
        window.selectedStatus = '';
        window.selectedPageLength = 10;
        window.currentLayout = 'cards'; // 'table' or 'cards'
        window.currentPage = 1;
        window.totalPages = 1;
        window.sectionsData = [];

        // Table instances for each tab
        let pendingTable = null;
        let acceptedTable = null;
        let approvedTable = null;
        let pendingDocumentsTable = null;
        let rejectedTable = null;

        document.addEventListener("DOMContentLoaded", function() {

            // Initialize modals
            initModal('create-section-modal', 'create-section-modal-btn', 'create-section-modal-close-btn',
                'create-section-cancel-btn',
                'modal-container-1');
            initModal('edit-program-modal', 'edit-program-modal-btn', 'edit-program-modal-close-btn',
                'edit-program-cancel-btn',
                'modal-container-2');

            // Load application statistics
            loadApplicationStatistics();

            // Initialize tables based on current route
            const currentPath = window.location.pathname;

            if (currentPath === '/applications/pending') {
                initializePendingTable();
            } else if (currentPath === '/applications/accepted') {
                initializeAcceptedTable();
            } else if (currentPath === '/applications/pending-documents') {
                initializePendingDocumentsTable();
            } else if (currentPath === '/applications/rejected') {
                initializeRejectedTable();
            } else if (currentPath === '/school-fees/payments') {
                initializePaymentHistoryTab();
            }

            // Initialize dropdowns
            dropDown('dropdown_2', 'dropdown_selection2');
            dropDown('dropdown_btn', 'dropdown_selection');
            dropDown('dropdown_3', 'dropdown_selection3');

            // Initialize filter event listeners
            initializeFilterListeners();
        });

        // Load Application Statistics
        function loadApplicationStatistics() {
            fetch('/api/application-statistics')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('totalApplicationsCount').textContent = data.statistics.total || 0;
                        document.getElementById('pendingApplicationsCount').textContent = data.statistics.pending || 0;
                        document.getElementById('acceptedApplicationsCount').textContent = data.statistics.accepted ||
                        0;
                        document.getElementById('pendingDocumentsCount').textContent = data.statistics
                            .pending_documents || 0;
                        document.getElementById('rejectedApplicationsCount').textContent = data.statistics.rejected ||
                        0;
                    } else {
                        console.error('Failed to load application statistics:', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error loading application statistics:', error);
                    // Set default values on error
                    document.getElementById('totalApplicationsCount').textContent = '0';
                    document.getElementById('pendingApplicationsCount').textContent = '0';
                    document.getElementById('acceptedApplicationsCount').textContent = '0';
                    document.getElementById('pendingDocumentsCount').textContent = '0';
                    document.getElementById('rejectedApplicationsCount').textContent = '0';
                });
        }

        // Initialize Pending Applications Table
        function initializePendingTable() {
            pendingTable = initCustomDataTable(
                'pending-table',
                '/getPendingApplications',
                [{
                        data: 'index',
                        width: '3%',
                        searchable: true,
                        orderable: false,
                        render: DataTable.render.text()
                    },
                    {
                        data: 'applicant_id',
                        width: '15%',
                        orderable: true,
                        render: DataTable.render.text()
                    },
                    {
                        data: 'full_name',
                        width: '20%',
                        orderable: true,
                        render: DataTable.render.text()
                    },
                    {
                        data: 'program',
                        width: '15%',
                        orderable: true,
                        render: DataTable.render.text()
                    },
                    {
                        data: 'grade_level',
                        width: '15%',
                        orderable: true,
                        render: DataTable.render.text()
                    },
                    {
                        data: 'submitted_at',
                        width: '15%',
                        orderable: true,
                        render: DataTable.render.text()
                    },
                    {
                        data: 'id',
                        className: 'text-center',
                        width: '15%',
                        render: function(data, type, row) {
                            return `
                                <div class='flex flex-row justify-center items-center opacity-100'>
                                    <a href="/applications/pending/form-details/${data}" 
                                       class="group relative inline-flex items-center gap-2 bg-blue-100 text-blue-500 font-semibold px-3 py-1 rounded-xl hover:bg-blue-500 hover:ring hover:ring-blue-200 hover:text-white transition duration-150">
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
                ], // Sort by created_at (column 4) descending by default
                'myCustomSearch', {
                    grade_filter: window.selectedGrade,
                    program_filter: window.selectedProgram,
                    pageLength: window.selectedPageLength
                }
            );
            clearSearch('clear-btn', 'myCustomSearch', pendingTable);

        }

        // Initialize Approved Applications Table
        function initializeAcceptedTable() {
            acceptedTable = initCustomDataTable(
                'accepted-table',
                '/getAcceptedApplications',
                [{
                        data: 'index',
                        width: '3%',
                        searchable: true,
                        orderable: true,
                        render: DataTable.render.text()
                    },
                    {
                        data: 'applicant_id',
                        width: '15%',
                        orderable: true,
                        searchable: true,
                        render: DataTable.render.text()
                    },
                    {
                        data: 'full_name',
                        width: '15%',
                        orderable: true,
                        searchable: true,
                        render: DataTable.render.text()
                    },
                    {
                        data: 'program',
                        width: '15%',
                        orderable: true,
                        searchable: true,
                        render: DataTable.render.text()
                    },
                    {
                        data: 'grade_level',
                        width: '15%',
                        orderable: true,
                        searchable: true,
                        render: DataTable.render.text()
                    },
                    {
                        data: 'status',
                        width: '12%',
                        orderable: true,
                        searchable: true,
                        render: function(data, type, row) {
                            let badgeClass = '';
                            let badgeText = data;

                            switch (data) {
                                case 'Accepted':
                                    badgeClass = 'bg-green-100 text-green-800';
                                    badgeText = 'Accepted';
                                    break;
                                case 'Scheduled':
                                    badgeClass = 'bg-blue-100 text-blue-800';
                                    badgeText = 'Scheduled';
                                    break;
                                case 'Taking-Exam':
                                default:
                                    badgeClass = 'bg-yellow-200 text-yellow-800';
                                    badgeText = 'Taking-Exam';
                                    break;
                            }

                            return `<span class="px-2 py-1 rounded-full text-xs font-medium ${badgeClass}">${badgeText}</span>`;
                        }
                    },
                    {
                        data: 'accepted_at',
                        width: '18%',
                        orderable: true,
                        searchable: true,
                        render: DataTable.render.text()
                    },
                    {
                        data: 'id',
                        className: 'text-center',
                        width: '10%',
                        render: function(data, type, row) {
                            return `
                                <div class='flex flex-row justify-center items-center opacity-100'>
                                    <a href="/applications/accepted/admission-details/${data}" 
                                       class="group relative inline-flex items-center gap-2 bg-green-100 text-green-500 font-semibold px-3 py-1 rounded-xl hover:bg-green-500 hover:ring hover:ring-green-200 hover:text-white transition duration-150">
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
                    grade_filter: window.selectedGrade,
                    program_filter: window.selectedProgram,
                    status_filter: window.selectedStatus,
                    pageLength: window.selectedPageLength
                }
            );

            clearSearch('clear-btn', 'myCustomSearch', acceptedTable);
        }

        // Initialize Pending Documents Table
        function initializePendingDocumentsTable() {
            if (document.getElementById('pending-documents-table')) {
                pendingDocumentsTable = initCustomDataTable(
                    'pending-documents-table',
                    '/getPendingDocumentsApplications',
                    [{
                            data: 'index',
                            width: '3%',
                            searchable: true
                        },
                        {
                            data: 'applicant_id',
                            width: '20%',
                            searchable: true
                        },
                        {
                            data: 'full_name',
                            width: '20%',
                            searchable: true
                        },
                        {
                            data: 'program',
                            width: '15%',
                            searchable: true
                        },
                        {
                            data: 'grade_level',
                            width: '15%',
                            searchable: true
                        },
                        {
                            data: 'status',
                            width: '18%',

                            render: function(data, type, row) {
                                let badgeClass = '';
                                let badgeText = data;

                                switch (data) {
                                    case 'No Requirements':
                                        badgeClass = 'bg-gray-100 text-gray-800';
                                        badgeText = 'No Requirements';
                                        break;
                                    case 'Overdue':
                                        badgeClass = 'bg-red-100 text-red-800';
                                        badgeText = 'Overdue';
                                        break;
                                    case 'Completed':
                                        badgeClass = 'bg-green-100 text-green-800';
                                        badgeText = 'Completed';
                                        break;
                                    case 'Pending':
                                    default:
                                        badgeClass = 'bg-yellow-200 text-yellow-800';
                                        badgeText = data;
                                        break;
                                }

                                return `<span class="px-2 py-1 rounded-full text-xs font-medium ${badgeClass}">${badgeText}</span>`;
                            }
                        },
                        {
                            data: 'id',
                            className: 'text-center',
                            width: '12%',
                            render: function(data, type, row) {
                                return `
                                <div class='flex flex-row justify-center items-center opacity-100'>
                                    <a href="/applications/pending-document/submission-details/${data}" 
                                       class="group relative inline-flex items-center gap-2 bg-orange-100 text-orange-500 font-semibold px-3 py-1 rounded-xl hover:bg-orange-500 hover:ring hover:ring-orange-200 hover:text-white transition duration-150">
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
                        grade_filter: window.selectedGrade,
                        program_filter: window.selectedProgram,
                        pageLength: window.selectedPageLength
                    }
                );
            }

            clearSearch('clear-btn', 'myCustomSearch', pendingDocumentsTable);

        }

        // Initialize Rejected Applications Table
        function initializeRejectedTable() {
            if (document.getElementById('rejected-table')) {
                rejectedTable = initCustomDataTable(
                    'rejected-table',
                    '/getRejectedApplications',
                    [{
                            data: 'index',
                            width: '3%',
                            searchable: true,
                            orderable: true,
                            render: DataTable.render.text()
                        },
                        {
                            data: 'applicant_id',
                            width: '15%',
                            orderable: true,
                            searchable: true,
                            render: DataTable.render.text()
                        },
                        {
                            data: 'full_name',
                            width: '20%',
                            orderable: true,
                            searchable: true,
                            render: DataTable.render.text()
                        },
                        {
                            data: 'program',
                            width: '15%',
                            orderable: true,
                            searchable: true,
                            render: DataTable.render.text()
                        },
                        {
                            data: 'grade_level',
                            width: '15%',
                            orderable: true,
                            searchable: true,
                            render: DataTable.render.text()
                        },
                        {
                            data: 'status',
                            width: '12%',
                            orderable: true,
                            searchable: true,
                            render: function(data, type, row) {
                                return `<span class="px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">${data}</span>`;
                            }
                        },
                        {
                            data: 'rejected_at',
                            width: '12%',
                            orderable: true,
                            searchable: true,
                            render: DataTable.render.text()
                        },
                        {
                            data: 'id',
                            className: 'text-center',
                            width: '10%',
                            render: function(data, type, row) {
                                return `
                                <div class='flex flex-row justify-center items-center opacity-100'>
                                    <a href="/rejected-application/details/${data}" 
                                       class="group relative inline-flex items-center gap-2 bg-red-100 text-red-500 font-semibold px-3 py-1 rounded-xl hover:bg-red-500 hover:ring hover:ring-red-200 hover:text-white transition duration-150">
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
                        grade_filter: window.selectedGrade,
                        program_filter: window.selectedProgram,
                        pageLength: window.selectedPageLength
                    }
                );
            }

            clearSearch('clear-btn', 'myCustomSearch', rejectedTable);
        }

        // Initialize filter event listeners
        function initializeFilterListeners() {
            let programSelection = document.querySelector('#program_selection');
            let gradeSelection = document.querySelector('#grade_selection');
            let statusSelection = document.querySelector('#status_selection');
            let pageLengthSelection = document.querySelector('#page-length-selection');

            let clearProgramFilterBtn = document.querySelector('#clear-program-filter-btn');
            let clearGradeFilterBtn = document.querySelector('#clear-grade-filter-btn');
            let clearStatusFilterBtn = document.querySelector('#clear-status-filter-btn');
            let programContainer = document.querySelector('#program_selection_container');
            let gradeContainer = document.querySelector('#grade_selection_container');
            let statusContainer = document.querySelector('#status_selection_container');

            // Program filter
            if (programSelection) {
                programSelection.addEventListener('change', function() {
                    window.selectedProgram = this.value;
                    refreshCurrentTable();

                    // Update styling when program is selected
                    if (this.value) {
                        let clearProgramFilterRem = ['text-gray-500', 'fi-rr-caret-down'];
                        let clearProgramFilterAdd = ['fi-bs-cross-small', 'cursor-pointer', 'text-gray-600'];
                        let programSelectionRem = ['border-[#1e1e1e]/10', 'text-gray-700'];
                        let programSelectionAdd = ['text-gray-800'];
                        let programContainerRem = ['bg-gray-100'];
                        let programContainerAdd = ['bg-gray-200', 'border-gray-400', 'hover:bg-gray-300'];

                        clearProgramFilterBtn.classList.remove(...clearProgramFilterRem);
                        clearProgramFilterBtn.classList.add(...clearProgramFilterAdd);
                        programSelection.classList.remove(...programSelectionRem);
                        programSelection.classList.add(...programSelectionAdd);
                        programContainer.classList.remove(...programContainerRem);
                        programContainer.classList.add(...programContainerAdd);

                        // Clear filter handler is set up below
                    }
                });
            }

            // Grade filter
            if (gradeSelection) {
                gradeSelection.addEventListener('change', function() {
                    const selectedOption = this.selectedOptions[0];
                    const gradeValue = selectedOption.getAttribute('data-putanginamo');
                    window.selectedGrade = gradeValue;
                    refreshCurrentTable();

                    // Update styling when grade is selected
                    if (gradeValue) {
                        let clearGradeFilterRem = ['text-gray-500', 'fi-rr-caret-down'];
                        let clearGradeFilterAdd = ['fi-bs-cross-small', 'cursor-pointer', 'text-gray-600'];
                        let gradeSelectionRem = ['border-[#1e1e1e]/10', 'text-gray-700'];
                        let gradeSelectionAdd = ['text-gray-800'];
                        let gradeContainerRem = ['bg-gray-100'];
                        let gradeContainerAdd = ['bg-gray-200', 'border-gray-400', 'hover:bg-gray-300'];

                        clearGradeFilterBtn.classList.remove(...clearGradeFilterRem);
                        clearGradeFilterBtn.classList.add(...clearGradeFilterAdd);
                        gradeSelection.classList.remove(...gradeSelectionRem);
                        gradeSelection.classList.add(...gradeSelectionAdd);
                        gradeContainer.classList.remove(...gradeContainerRem);
                        gradeContainer.classList.add(...gradeContainerAdd);

                        // Clear filter handler is set up below
                    }
                });
            }

            // Status filter
            if (statusSelection) {
                statusSelection.addEventListener('change', function() {
                    window.selectedStatus = this.value;
                    refreshCurrentTable();

                    // Update styling when status is selected
                    if (this.value) {
                        let clearStatusFilterRem = ['text-gray-500', 'fi-rr-caret-down'];
                        let clearStatusFilterAdd = ['fi-bs-cross-small', 'cursor-pointer', 'text-gray-600'];
                        let statusSelectionRem = ['border-[#1e1e1e]/10', 'text-gray-700'];
                        let statusSelectionAdd = ['text-gray-800'];
                        let statusContainerRem = ['bg-gray-100'];
                        let statusContainerAdd = ['bg-gray-200', 'border-gray-400', 'hover:bg-gray-300'];

                        clearStatusFilterBtn.classList.remove(...clearStatusFilterRem);
                        clearStatusFilterBtn.classList.add(...clearStatusFilterAdd);
                        statusSelection.classList.remove(...statusSelectionRem);
                        statusSelection.classList.add(...statusSelectionAdd);
                        statusContainer.classList.remove(...statusContainerRem);
                        statusContainer.classList.add(...statusContainerAdd);

                        // Clear filter handler is set up below
                    }
                });
            }

            // Page length filter
            if (pageLengthSelection) {
                pageLengthSelection.addEventListener('change', function() {
                    window.selectedPageLength = parseInt(this.value, 10);
                    refreshCurrentTable();
                });
            }

            // Clear program filter function
            function handleClearProgramFilter() {
                if (!clearProgramFilterBtn || !programContainer || !programSelection) return;

                clearProgramFilterBtn.addEventListener('click', () => {
                    programContainer.classList.remove('bg-gray-200');
                    programContainer.classList.remove('border-gray-400');
                    programContainer.classList.remove('hover:bg-gray-300');
                    clearProgramFilterBtn.classList.remove('fi-bs-cross-small');
                    clearProgramFilterBtn.classList.add('fi-rr-caret-down');
                    programContainer.classList.add('bg-gray-100');
                    programSelection.classList.remove('text-gray-800');
                    programSelection.classList.add('text-gray-700');
                    clearProgramFilterBtn.classList.remove('text-gray-600');
                    clearProgramFilterBtn.classList.add('text-gray-500');

                    programSelection.selectedIndex = 0;
                    window.selectedProgram = '';
                    refreshCurrentTable();
                });
            }

            // Clear grade filter function
            function handleClearGradeFilter() {
                if (!clearGradeFilterBtn || !gradeContainer || !gradeSelection) return;

                clearGradeFilterBtn.addEventListener('click', () => {
                    gradeContainer.classList.remove('bg-gray-200');
                    gradeContainer.classList.remove('border-gray-400');
                    gradeContainer.classList.remove('hover:bg-gray-300');
                    clearGradeFilterBtn.classList.remove('fi-bs-cross-small');
                    clearGradeFilterBtn.classList.add('fi-rr-caret-down');
                    gradeContainer.classList.add('bg-gray-100');
                    gradeSelection.classList.remove('text-gray-800');
                    gradeSelection.classList.add('text-gray-700');
                    clearGradeFilterBtn.classList.remove('text-gray-600');
                    clearGradeFilterBtn.classList.add('text-gray-500');

                    gradeSelection.selectedIndex = 0;
                    window.selectedGrade = '';
                    refreshCurrentTable();
                });
            }

            // Clear status filter function
            function handleClearStatusFilter() {
                if (!clearStatusFilterBtn || !statusContainer || !statusSelection) return;

                clearStatusFilterBtn.addEventListener('click', () => {
                    statusContainer.classList.remove('bg-gray-200');
                    statusContainer.classList.remove('border-gray-400');
                    statusContainer.classList.remove('hover:bg-gray-300');
                    clearStatusFilterBtn.classList.remove('fi-bs-cross-small');
                    clearStatusFilterBtn.classList.add('fi-rr-caret-down');
                    statusContainer.classList.add('bg-gray-100');
                    statusSelection.classList.remove('text-gray-800');
                    statusSelection.classList.add('text-gray-700');
                    clearStatusFilterBtn.classList.remove('text-gray-600');
                    clearStatusFilterBtn.classList.add('text-gray-500');

                    statusSelection.selectedIndex = 0;
                    window.selectedStatus = '';
                    refreshCurrentTable();
                });
            }

            // Initialize default selections
            window.onload = function() {
                if (gradeSelection) gradeSelection.selectedIndex = 0;
                if (programSelection) programSelection.selectedIndex = 0;
                if (statusSelection) statusSelection.selectedIndex = 0;
                if (pageLengthSelection) pageLengthSelection.selectedIndex = 0;
            }

            // Set up clear filter handlers
            handleClearProgramFilter();
            handleClearGradeFilter();
            handleClearStatusFilter();
        }

        // Refresh the current table based on route
        function refreshCurrentTable() {
            @if (Route::is('applications.pending'))
                if (pendingTable) {
                    pendingTable.ajax.reload();
                }
            @elseif (Route::is('applications.accepted'))
                if (acceptedTable) {
                    acceptedTable.ajax.reload();
                }
            @elseif (Route::is('applications.pending-documents'))
                if (pendingDocumentsTable) {
                    pendingDocumentsTable.ajax.reload();
                }
            @elseif (Route::is('applications.rejected'))
                if (rejectedTable) {
                    rejectedTable.ajax.reload();
                }
            @endif
        }
    </script>
@endpush

