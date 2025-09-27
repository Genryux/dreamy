@extends('layouts.admin')
@section('breadcrumbs')
    <nav aria-label="Breadcrumb" class="flex flex-row justify-between items-center mb-2 mt-2">
        <ol class="flex items-center gap-1 text-sm text-gray-700">
            <li class="rtl:rotate-180">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 rotate-180" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
            </li>
            <li>
                <a href="/programs" class="block transition-colors hover:text-gray-900"> Programs </a>
            </li>

            <li class="rtl:rotate-180">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
            </li>

            <li>
                <a href="#" class="block transition-colors hover:text-gray-900">
                    {{ $program->code . ' - ' . $program->name }}
                </a>
            </li>

        </ol>
        {{-- <div class="flex flex-row justify-center items-center h-full">
            <div id="dropdown_btn"
                class="relative space-y-10 h-full flex flex-col justify-center items-center gap-4 cursor-pointer">

                <div
                    class="group relative inline-flex items-center gap-2 border border-[#1e1e1e]/0 text-gray-700 font-semibold py-2 px-3 rounded-lg hover:shadow-sm hover:bg-gray-100 hover:border-[#1e1e1e]/15 transition ease-out duration-300">
                    <i class="fi fi-br-menu-dots flex justify-center items-center"></i>
                </div>

                <div id="dropdown_selection"
                    class="absolute top-0 right-0 z-10 bg-[#f8f8f8] flex-col justify-center items-center gap-4 rounded-lg shadow-md border border-[#1e1e1e]/15 py-2 px-1 opacity-0 scale-95 pointer-events-none transition-all duration-200 ease-out translate-y-1">
                    <button id="edit-section-modal-btn"
                        class="flex-1 flex justify-start items-center px-8 py-2 gap-2 text-[14px] font-medium opacity-80 w-full border-b border-[#1e1e1e]/15 hover:bg-gray-200 truncate">
                        <i class="fi fi-rr-pen-clip text-[16px] flex justify-center item-center"></i>Edit Section
                    </button>
                    <x-nav-link href="/students/export/excel"
                        class="flex-1 flex justify-start items-center px-8 py-2 gap-2 text-[14px] font-medium opacity-80 w-full border-b border-[#1e1e1e]/15 hover:bg-red-200 hover:text-red-500 truncate">
                        <i class="fi fi-rr-remove-user text-[16px] flex justify-center item-center"></i>Remove Student
                    </x-nav-link>
                    <button
                        class="flex-1 flex justify-start items-center px-8 py-2 gap-2 text-[14px] font-medium opacity-80 w-full border-b border-[#1e1e1e]/15 hover:bg-gray-200 truncate">
                        <i class="fi fi-rr-box text-[16px] flex justify-center item-center"></i>Archive Section
                    </button>
                    <button
                        class="flex-1 flex justify-start items-center px-8 py-2 gap-2 text-[14px] font-medium opacity-80 w-full hover:bg-gray-200 truncate">
                        <i class="fi fi-rr-trash text-[16px] flex justify-center item-center"></i>Delete Section
                    </button>
                </div>

            </div>
        </div> --}}
    </nav>
@endsection
@section('modal')
    <x-modal modal_id="edit-program-modal" modal_name="Edit Program" close_btn_id="edit-program-modal-close-btn"
        modal_container_id="modal-container-2">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-progress-upload flex justify-center items-center '></i>
        </x-slot>

        <form id="edit-program-form" class="p-6">
            @csrf
            <div class="space-y-6">
                <!-- Program Code -->
                <div>
                    <label for="program_code" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-tag mr-2"></i>
                        Program Code <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="code" id="program_code" required placeholder="e.g., STEM, ABM, HUMSS"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Program Name/Description -->
                <div>
                    <label for="program_name" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-graduation-cap mr-2"></i>
                        Program Description <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="program_name" required
                        placeholder="e.g., Science, Technology, Engineering and Mathematics"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Program Track -->
                <div>
                    <label for="program_track" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-school mr-2"></i>
                        Program Track
                    </label>
                    <input type="text" name="track" id="program_track"
                        placeholder="e.g., Academic, Technical-Vocational, Sports, Arts and Design"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Program Status -->
                <div>
                    <label for="program_status" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-check-circle mr-2"></i>
                        Status
                    </label>
                    <select name="status" id="program_status"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
        </form>

        <x-slot name="modal_buttons">
            <button id="cancel-btn"
                class="bg-gray-100 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-md text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-200 hover:ring hover:ring-gray-200 transition duration-150">
                Cancel
            </button>
            {{-- This button will acts as the submit button --}}
            <button type="submit" form="edit-program-form"
                class="bg-blue-500 text-[14px] px-3 py-2 rounded-md text-[#f8f8f8] font-bold hover:ring hover:ring-blue-200 hover:bg-blue-400 transition duration-150 shadow-sm">
                Update
            </button>
        </x-slot>

    </x-modal>
    @if (Route::is('program.sections'))
        {{-- add student modal --}}
        <x-modal modal_id="create-section-modal" modal_name="Create Section" close_btn_id="create-section-modal-close-btn"
            modal_container_id="modal-container-1">
            <x-slot name="modal_icon">
                <i class='fi fi-rr-progress-upload flex justify-center items-center '></i>

            </x-slot>

            <form id="create-section-form" method="post" action="/section" class="p-6">
                @csrf
                <div class="space-y-6">
                    <!-- Program Selection -->
                    <div>
                        <label for="program_id" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fi fi-rr-graduation-cap mr-2"></i>
                            Program <span class="text-red-500">*</span>
                        </label>
                        <select name="program_id" id="program_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Program</option>
                            @foreach ($programs as $prog)
                                <option value="{{ $prog->id }}" {{ $prog->id == $program->id ? 'selected' : '' }}>
                                    {{ $prog->name }} ({{ $prog->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Year Level -->
                    <div>
                        <label for="year_level" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fi fi-rr-calendar mr-2"></i>
                            Year Level <span class="text-red-500">*</span>
                        </label>
                        <select name="year_level" id="year_level" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Year Level</option>
                            <option value="Grade 11">Grade 11</option>
                            <option value="Grade 12">Grade 12</option>
                        </select>
                    </div>

                    <!-- Section Code -->
                    <div>
                        <label for="section_code" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fi fi-rr-users-class mr-2"></i>
                            Section Code <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="section_code" id="section_code" placeholder="e.g., 11-HUMSS-A"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Room -->
                    <div>
                        <label for="room" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fi fi-rr-home mr-2"></i>
                            Room Assignment
                        </label>
                        <input type="text" name="room" id="room" placeholder="e.g., Room 101, Lab 2"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Adviser Selection -->
                    <div>
                        <label for="adviser_id" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fi fi-rr-user-tie mr-2"></i>
                            Assign Adviser
                        </label>
                        <select name="adviser_id" id="adviser_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Adviser</option>
                            @foreach (\App\Models\Teacher::with(['user', 'program'])->where('status', 'active')->get() as $teacher)
                                <option value="{{ $teacher->id }}" data-program-id="{{ $teacher->program_id }}"
                                    {{ $teacher->program_id == $program->id ? '' : 'style="display:none"' }}>
                                    {{ $teacher->getFullNameAttribute() }}
                                    @if ($teacher->program)
                                        - {{ $teacher->program->name }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-sm text-gray-500">Only teachers from the selected program will be shown</p>
                    </div>

                    <!-- Auto Assign Subjects -->
                    <div class="flex items-center">
                        <input type="checkbox" name="auto_assign" id="auto_assign"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="auto_assign" class="ml-2 block text-sm text-gray-700">
                            <i class="fi fi-rr-magic-wand mr-1"></i>
                            Auto-Assign Subjects (Current Term)
                        </label>
                    </div>
                </div>

                <div id="subjects-container" class="mt-6"></div> <!-- subjects will be inserted here -->

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
            <h1 class="text-[20px] font-black">Program Details</h1>
            <p class="text-[14px]  text-gray-900/60">View and manage program details and associated sections and subjects.
            </p>
        </div>

        <div id="dropdown_2"
            class="relative space-y-10 h-full flex flex-col justify-start items-center gap-4 cursor-pointer">

            <div
                class="group relative inline-flex items-center gap-2 bg-gray-100 border border-[#1e1e1e]/10 text-gray-700 font-semibold py-2 px-3 rounded-lg shadow-sm hover:bg-gray-200 hover:border-[#1e1e1e]/15 transition duration-150">
                <i class="fi fi-br-menu-dots flex justify-center items-center text-[18px]"></i>
            </div>

            <div id="dropdown_selection2"
                class="absolute top-0 right-0 z-10 bg-[#f8f8f8] flex-col justify-center items-center gap-1 rounded-lg shadow-md border border-[#1e1e1e]/15 py-2 px-1 opacity-0 scale-95 pointer-events-none transition-all duration-200 ease-out translate-y-1">
                <button id="edit-program-modal-btn"
                    class="flex-1 flex justify-start items-center px-8 py-2 gap-2 text-[14px] font-medium opacity-80 w-full border-b border-[#1e1e1e]/15 hover:bg-gray-200 truncate">
                    <i class="fi fi-sr-file-import text-[16px]"></i>Edit Program
                </button>
                <x-nav-link href="/students/export/excel"
                    class="flex-1 flex justify-start items-center px-8 py-2 gap-2 text-[14px] font-medium opacity-80 w-full border-b border-[#1e1e1e]/15 hover:bg-gray-200 truncate">
                    <i class="fi fi-sr-file-excel text-[16px]"></i>Archive Program
                </x-nav-link>
                <button
                    class="flex-1 flex justify-start items-center px-8 py-2 gap-2 text-[14px] font-medium opacity-80 w-full hover:bg-gray-200 truncate">
                    <i class="fi fi-sr-file-pdf text-[16px]"></i>Delete Program
                </button>
            </div>

        </div>
    </div>
@endsection
@section('stat')
    <div class="flex justify-center items-center">
        <div
            class="flex flex-col justify-center items-center flex-grow px-6 pb-8 pt-2 bg-gradient-to-br from-blue-500 to-[#1A3165] rounded-xl shadow-xl border border-[#1e1e1e]/10 gap-2 text-white">

            <div class="flex flex-row items-center justify-between w-full gap-4 py-2 rounded-lg ">

                <div class="flex flex-col items-start justify-end gap-2 pt-4">
                    <h1 class="text-[40px] font-black" id="section_name">{{ $program->code }}</h1>
                    <p class="text-[16px]  text-white/60">{{ $program->name }}
                    </p>
                </div>

                <div class="flex flex-col items-end justify-center">
                    <p id="studentCount" class="text-[50px] font-bold ">
                    </p>
                    <div class="flex flex-row justify-center items-center opacity-70 gap-2 text-[14px]">
                        {{-- <i class="fi fi-sr-graduation-cap flex justify-center items-center "></i> --}}
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
                    <p class="font-bold text-[24px]">{{ $totalStudents }}</p>
                    <p class="text-[12px] truncate text-gray-300">Total students enrolled in this program</p>
                </div>

                <div
                    class="flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-8 py-6 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-300">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-rr-lesson flex flex-row justify-center items-center"></i>
                        <p class="text-[14px]">Active Sections</p>
                    </div>
                    <p class="font-bold text-[24px]">{{ $activeSections }}</p>
                    <p class="text-[12px] truncate text-gray-300">Active sections across this program</p>
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
@endsection
@section('content')
    <x-alert />

    <div
        class="px-5 text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:text-gray-400 dark:border-gray-300">
        <ul class="flex flex-wrap -mb-px">
            <li class="me-2">
                <a href="{{ route('program.sections', $program->id) }}"
                    class="inline-block p-4 border-b-2 rounded-t-lg 
              {{ Route::is('program.sections') ? 'text-blue-600 border-blue-600 dark:text-blue-500 dark:border-blue-500' : 'border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300' }}">
                    Sections
                </a>
            </li>

            <li class="me-2">
                <a href="{{ route('program.subjects', $program->id) }}"
                    class="inline-block p-4 border-b-2 rounded-t-lg 
              {{ Route::is('program.subjects') ? 'text-blue-600 border-blue-600 dark:text-blue-500 dark:border-blue-500' : 'border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300' }}">
                    Subjects
                </a>
            </li>



        </ul>
    </div>

    @if (Route::is('program.sections'))
        <div class="flex flex-row justify-center items-start gap-4">
            <div
                class="flex flex-col justify-start items-center flex-grow p-5 space-y-4 bg-[#f8f8f8] rounded-xl shadow-md border border-[#1e1e1e]/10 w-[40%]">
                <div class="flex flex-row justify-between items-center w-full">
                    <span class="font-semibold text-[18px]">
                        Section List
                    </span>
                    <div id="dropdown_btn"
                        class="relative space-y-10 flex flex-col justify-start items-center gap-4 cursor-pointer">

                        <div
                            class="group relative inline-flex items-center gap-2 bg-gray-100 border border-[#1e1e1e]/10 text-gray-700 font-semibold py-2 px-3 rounded-lg shadow-sm hover:bg-gray-200 hover:border-[#1e1e1e]/15 transition duration-150">
                            <i class="fi fi-br-menu-dots flex justify-center items-center"></i>
                        </div>

                        <div id="dropdown_selection"
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

                            <!-- Layout Toggle Button -->
                            <div id="layout_toggle_container"
                                class="flex flex-row justify-center items-center rounded-lg border border-[#1e1e1e]/10 bg-gray-100 px-3 py-2 gap-2 hover:bg-gray-200 hover:border-[#1e1e1e]/15 transition-all ease-in-out duration-150 shadow-sm">
                                <button id="layout-toggle-btn"
                                    class="flex flex-row justify-center items-center gap-2 text-[14px] font-medium text-gray-700 hover:text-[#1A3165] transition-colors duration-150">
                                    <i id="layout-toggle-icon" class="fi fi-sr-apps text-[16px]"></i>
                                    <span id="layout-toggle-text">Cards</span>
                                </button>
                            </div>


                        </div>
                    </div>

                    <div class="flex flex-row justify-center items-center gap-2">
                        <div class="flex flex-row justify-center items-center truncate">
                            <button id="create-section-modal-btn"
                                class="bg-[#1A3165] p-2 rounded-lg text-[14px] font-semibold flex justify-center items-center gap-2 text-white">
                                <i class="fi fi-rr-plus flex justify-center items-center "></i>
                                Create New Section
                            </button>
                        </div>

                    </div>


                </div>

                <!-- Table Layout Container -->
                <div id="table-layout-container" class="w-full hidden">
                    <table id="sections" class="w-full table-fixed">
                        <thead class="text-[14px]">
                            <tr>
                                <th class="text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">#</span>
                                </th>
                                <th class="w-1/7 text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Name</span>
                                    <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                                </th>
                                <th class="w-1/7 text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Adviser</span>
                                    <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                                </th>
                                <th class="w-1/7 text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Year Level</span>
                                    <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                                </th>
                                <th class="w-1/7 text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Room</span>
                                    <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                                </th>
                                <th class="w-1/7 text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Total Students</span>
                                    <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                                </th>
                                <th class="w-1/7 text-center bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10  px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 select-none">Actions</span>
                                </th>

                            </tr>
                        </thead>
                        <tbody>
                            {{-- <tr class="border-t-[1px] border-[#1e1e1e]/15 w-full rounded-md"></tr> --}}
                        </tbody>
                    </table>
                </div>

                <!-- Card Layout Container -->
                <div id="card-layout-container" class="w-full">
                    <div id="sections-cards-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Cards will be dynamically inserted here -->
                    </div>

                    <!-- Card Layout Pagination -->
                    <div id="card-pagination" class="flex justify-center items-center mt-6 gap-2">
                        <!-- Pagination will be dynamically inserted here -->
                    </div>
                </div>
            </div>

        </div>
    @endif
    @if (Route::is('program.subjects'))
        <div class="flex flex-row justify-center items-start gap-4">
            <div
                class="flex flex-col justify-start items-start flex-grow p-5 space-y-4 bg-[#f8f8f8] rounded-xl shadow-md border border-[#1e1e1e]/10 w-[40%]">
                <div class="flex flex-row justify-between items-center w-full">
                    <span class="font-semibold text-[18px]">
                        Subject List
                    </span>
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
                            <div
                                class="flex flex-row justify-between items-center rounded-lg border border-[#1e1e1e]/10 bg-gray-100 px-3 py-2 gap-2 focus-within:bg-gray-200 focus-within:border-[#1e1e1e]/15 hover:bg-gray-200 hover:border-[#1e1e1e]/15 transition duration-150 shadow-sm">
                                <select name="" id="program_selection"
                                    class="appearance-none bg-transparent text-[14px] font-medium text-gray-700 w-full cursor-pointer">
                                    <option value="" selected disabled>Category</option>
                                    <option value="" data-id="Core">Core</option>
                                    <option value="" data-id="Applied">Applied</option>
                                    <option value="" data-id="Specialized">Specialized</option>

                                </select>
                                <i id="clear-program-filter-btn"
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
                            <div id="semester_selection_container"
                                class="flex flex-row justify-between items-center rounded-lg border border-[#1e1e1e]/10 bg-gray-100 px-3 py-2 gap-2 hover:bg-gray-200 hover:border-[#1e1e1e]/15 transition-all ease-in-out duration-150 shadow-sm">

                                <select name="semester_selection" id="semester_selection"
                                    class="appearance-none bg-transparent text-[14px] font-medium text-gray-700 h-full w-full cursor-pointer">
                                    <option value="" disabled selected>Semester</option>
                                    <option value="" data-sem="1st Semester">1st Semester</option>
                                    <option value="" data-sem="2nd Semester">2nd Semester</option>
                                </select>
                                <i id="clear-grade-filter-btn"
                                    class="fi fi-rr-caret-down text-gray-500 flex justify-center items-center"></i>

                            </div>


                        </div>
                    </div>

                    <div class="flex flex-row justify-center items-center gap-2">

                        <div class="flex flex-row justify-center items-center truncate">
                            <button id="add-student-modal-btn"
                                class="bg-[#1A3165] p-2 rounded-lg text-[14px] font-semibold flex justify-center items-center gap-2 text-white">
                                <i class="fi fi-rr-plus flex justify-center items-center "></i>
                                Create New Subject
                            </button>
                        </div>


                    </div>


                </div>

                <div class="w-full">
                    <table id="subjects" class="w-full table-fixed">
                        <thead class="text-[14px]">
                            <tr>
                                <th class="w-[3%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-2 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">#</span>
                                </th>

                                <th class="w-[10%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Subject Name</span>
                                    <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                                </th>

                                <th class="w-[45%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Category</span>
                                    <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                                </th>

                                <th class="w-[15%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Year Level</span>
                                    <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                                </th>
                                <th class="w-[15%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Semester</span>
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
        window.selectedGrade = '';
        window.selectedProgram = '';
        window.selectedPageLength = 10;
        window.currentLayout = 'cards'; // 'table' or 'cards'
        window.currentPage = 1;
        window.totalPages = 1;
        window.sectionsData = [];

        const programId = @json($program->id);

        console.log(programId);


        document.addEventListener("DOMContentLoaded", function() {

            let assignCheckbox = document.getElementById('auto_assign');


            initModal('create-section-modal', 'create-section-modal-btn', 'create-section-modal-close-btn',
                'create-section-cancel-btn',
                'modal-container-1');
            initModal('edit-program-modal', 'edit-program-modal-btn', 'edit-program-modal-close-btn',
                'edit-program-cancel-btn',
                'modal-container-2');

            let studentCount = document.querySelector('#studentCount');
            let sectionName = document.querySelector('#section_name');
            let sectionRoom = document.querySelector('#section_room');

            // const fileInput = document.getElementById('fileInput');
            // const fileName = document.getElementById('fileName');

            // fileInput.addEventListener('change', function() {
            //     fileName.textContent = this.files.length > 0 ? this.files[0].name : 'No file chosen';
            // });

            //Overriding default search input

            let sectionTable = initCustomDataTable(
                'sections',
                `/getSections/${programId}`,
                [{
                        data: 'index',
                        width: '3%',
                        searchable: true
                    },
                    {
                        data: 'name',
                        width: '15%'
                    },
                    {
                        data: 'adviser',
                        width: '15%'
                    },
                    {
                        data: 'year_level',
                        width: '15%'
                    },
                    {
                        data: 'room',
                        width: '15%'
                    },
                    {
                        data: 'total_enrolled_students',
                        width: '15%'
                    },
                    {
                        data: 'id',
                        className: 'text-center',
                        width: '15%',
                        render: function(data, type, row) {
                            return `
                            <div class='flex flex-row justify-center items-center opacity-100'>

                                <a href="/section/${data}" class="group relative inline-flex items-center gap-2 bg-blue-100 text-blue-500 font-semibold px-3 py-1 rounded-xl hover:bg-blue-500 hover:ring hover:ring-blue-200 hover:text-white transition duration-150 ">

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

            let subjectTable = initCustomDataTable(
                'subjects',
                `/getSubjects/${programId}`,
                [{
                        data: 'index',
                        width: '3%',
                        searchable: true
                    },
                    {
                        data: 'name',
                        width: '30%'
                    },
                    {
                        data: 'category',
                        width: '10%'
                    },
                    {
                        data: 'year_level',
                        width: '10%'
                    },
                    {
                        data: 'semester',
                        width: '10%'
                    },
                    {
                        data: 'id',
                        className: 'text-center',
                        width: '15%',
                        render: function(data, type, row) {
                            return `
                            <div class='flex flex-row justify-center items-center opacity-100'>

                                <a href="/section/${data}" class="group relative inline-flex items-center gap-2 bg-blue-100 text-blue-500 font-semibold px-3 py-1 rounded-xl hover:bg-blue-500 hover:ring hover:ring-blue-200 hover:text-white transition duration-150 ">

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


            const customSearch1 = document.getElementById("myCustomSearch");

            // Update search functionality to work with both layouts
            customSearch1.addEventListener("input", function() {
                if (window.currentLayout === 'table') {
                    sectionTable.search(this.value).draw();
                } else {
                    // For card layout, fetch with search
                    fetchSectionsForCards(1);
                }
            });

            // table1 = new DataTable('#sections', {
            //     paging: true,
            //     searching: true,
            //     autoWidth: false,
            //     serverSide: true,
            //     processing: true,
            //     ajax: {
            //         url: `/getPrograms`,
            //         data: function(d) {

            //             d.grade_filter = selectedGrade;
            //             d.program_filter = selectedProgram;
            //             d.pageLength = selectedPageLength;
            //         }
            //     },
            //     order: [
            //         [6, 'desc']
            //     ],
            //     columnDefs: [{
            //             width: '3%',
            //             targets: 0,
            //             className: 'text-center'
            //         }, // index
            //         {
            //             width: '15%',
            //             targets: 1
            //         }, // code
            //         {
            //             width: '50%',
            //             targets: 2
            //         }, // name
            //         {
            //             width: '20%',
            //             targets: 3
            //         }, // created at
            //         {
            //             width: '15%',
            //             targets: 4,
            //             className: 'text-center'
            //         } // actions
            //     ],
            //     layout: {
            //         topStart: null,
            //         topEnd: null,
            //         bottomStart: 'info',
            //         bottomEnd: 'paging',
            //     },
            //     columns: [{
            //             data: 'index'
            //         },
            //         {
            //             data: 'code'
            //         },
            //         {
            //             data: 'name'
            //         },
            //         {
            //             data: 'created_at'
            //         },
            //         {
            //             data: 'id', // pass ID for rendering the link
            //             render: function(data, type, row) {
            //                 return `
        //                 <div class='flex flex-row justify-center items-center opacity-100'>

        //                     <a href="/program/${data}" class="group relative inline-flex items-center gap-2 bg-blue-100 text-blue-500 font-semibold px-3 py-1 rounded-xl hover:bg-blue-500 hover:ring hover:ring-blue-200 hover:text-white transition duration-150 ">

        //                         <span class="relative w-4 h-4">
        //                             <i class="fi fi-rs-eye flex justify-center items-center absolute inset-0 group-hover:opacity-0 transition-opacity text-[16px]"></i>
        //                             <i class="fi fi-ss-eye flex justify-center items-center absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity text-[16px]"></i>
        //                         </span>

        //                         View
        //                     </a>

        //                 </div>

        //                 `;
            //             },
            //             orderable: false,
            //             searchable: false
            //         }
            //     ],
            // });

            // customSearch1.addEventListener("input", function() {
            //     table1.search(this.value).draw();
            // });

            // table1.on('draw', function() {
            //     let rows = document.querySelectorAll('#sections tbody tr');

            //     rows.forEach(function(row) {
            //         // Add hover style to the row
            //         row.classList.add(
            //             'hover:bg-gray-200',
            //             'transition',
            //             'duration-150'
            //         );

            //         // Style all cells in the row
            //         let cells = row.querySelectorAll('td');
            //         cells.forEach(function(cell) {
            //             cell.classList.add(
            //                 'px-4', // Horizontal padding
            //                 'py-1', // Vertical padding
            //                 'text-start', // Align text left
            //                 'font-regular',
            //                 'text-[14px]',
            //                 'opacity-80',
            //                 'truncate',
            //                 'border-t',
            //                 'border-[#1e1e1e]/10',
            //                 'font-semibold'
            //             );
            //         });
            //     });
            // });

            // table1.on("init", function() {
            //     const defaultSearch = document.querySelector("#dt-search-0");
            //     if (defaultSearch) {
            //         defaultSearch.remove();
            //     }

            // });

            clearSearch('clear-btn', 'myCustomSearch', sectionTable)

            // Layout Toggle Functionality
            const layoutToggleBtn = document.getElementById('layout-toggle-btn');
            const layoutToggleIcon = document.getElementById('layout-toggle-icon');
            const layoutToggleText = document.getElementById('layout-toggle-text');
            const tableLayoutContainer = document.getElementById('table-layout-container');
            const cardLayoutContainer = document.getElementById('card-layout-container');

            // Function to render cards
            function renderCards(data, currentPage = 1, totalPages = 1) {
                const cardsGrid = document.getElementById('sections-cards-grid');
                const paginationContainer = document.getElementById('card-pagination');

                if (!data || data.length === 0) {
                    cardsGrid.innerHTML = `
                        <div class="col-span-full flex flex-col justify-center items-center py-12 text-gray-500">
                            <i class="fi fi-sr-folder-open text-4xl mb-4"></i>
                            <p class="text-lg font-medium">No sections found</p>
                            <p class="text-sm">Try adjusting your search or filters</p>
                        </div>
                    `;
                    paginationContainer.innerHTML = '';
                    return;
                }

                // Render cards
                cardsGrid.innerHTML = data.map(section => `
                    <div class="bg-white rounded-xl shadow-md border border-[#1e1e1e]/10 hover:shadow-lg hover:-translate-y-1 transition-all duration-200 p-6">
                        <div class="flex flex-col space-y-4">
                            <!-- Header -->
                            <div class="flex flex-row justify-between items-start">
                                <div class="flex flex-col">
                                    <h3 class="text-lg font-bold text-[#1A3165]">${section.name}</h3>
                                    <p class="text-sm text-gray-600">${section.year_level}</p>
                                </div>
                                <div class="flex flex-col items-end">
                                    <span class="text-xs text-gray-500">#${section.index}</span>
                                </div>
                            </div>

                            <!-- Details -->
                            <div class="space-y-3">
                                <div class="flex flex-row items-center gap-3">
                                    <i class="fi fi-sr-user text-[#1A3165] text-sm"></i>
                                    <div class="flex flex-col">
                                        <span class="text-xs text-gray-500">Adviser</span>
                                        <span class="text-sm font-medium">${section.adviser}</span>
                                    </div>
                                </div>
                                
                                <div class="flex flex-row items-center gap-3">
                                    <i class="fi fi-sr-home text-[#1A3165] text-sm"></i>
                                    <div class="flex flex-col">
                                        <span class="text-xs text-gray-500">Room</span>
                                        <span class="text-sm font-medium">${section.room}</span>
                                    </div>
                                </div>
                                
                                <div class="flex flex-row items-center gap-3">
                                    <i class="fi fi-sr-users text-[#1A3165] text-sm"></i>
                                    <div class="flex flex-col">
                                        <span class="text-xs text-gray-500">Total Students</span>
                                        <span class="text-sm font-medium">${section.total_enrolled_students}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <div class="pt-2 border-t border-gray-100">
                                <a href="/section/${section.id}" 
                                   class="w-full flex justify-center items-center gap-2 bg-[#1A3165] text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-[#0f1f3a] transition-colors duration-150">
                                    <i class="fi fi-rs-eye text-sm"></i>
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                `).join('');

                // Render pagination
                if (totalPages > 1) {
                    let paginationHTML = '';

                    // Previous button
                    if (currentPage > 1) {
                        paginationHTML += `
                            <button onclick="changeCardPage(${currentPage - 1})" 
                                    class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-700 transition-colors duration-150">
                                <i class="fi fi-sr-angle-left"></i>
                            </button>
                        `;
                    }

                    // Page numbers
                    const startPage = Math.max(1, currentPage - 2);
                    const endPage = Math.min(totalPages, currentPage + 2);

                    for (let i = startPage; i <= endPage; i++) {
                        paginationHTML += `
                            <button onclick="changeCardPage(${i})" 
                                    class="px-3 py-2 text-sm font-medium ${i === currentPage ? 'bg-[#1A3165] text-white' : 'text-gray-500 bg-white border border-gray-300 hover:bg-gray-50 hover:text-gray-700'} rounded-lg transition-colors duration-150">
                                ${i}
                            </button>
                        `;
                    }

                    // Next button
                    if (currentPage < totalPages) {
                        paginationHTML += `
                            <button onclick="changeCardPage(${currentPage + 1})" 
                                    class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-700 transition-colors duration-150">
                                <i class="fi fi-sr-angle-right"></i>
                            </button>
                        `;
                    }

                    paginationContainer.innerHTML = paginationHTML;
                } else {
                    paginationContainer.innerHTML = '';
                }
            }

            // Function to fetch data for cards
            async function fetchSectionsForCards(page = 1) {
                try {
                    const response = await fetch(
                        `/getSections/${programId}?start=${(page - 1) * window.selectedPageLength}&length=${window.selectedPageLength}&grade_filter=${window.selectedGrade}&program_filter=${window.selectedProgram}&search[value]=${document.getElementById('myCustomSearch').value}`
                    );
                    const data = await response.json();

                    window.sectionsData = data.data;
                    window.currentPage = page;
                    window.totalPages = Math.ceil(data.recordsTotal / window.selectedPageLength);

                    renderCards(data.data, page, window.totalPages);
                } catch (error) {
                    console.error('Error fetching sections:', error);
                }
            }

            // Function to change card page
            window.changeCardPage = function(page) {
                fetchSectionsForCards(page);
            }

            // Layout toggle event listener
            layoutToggleBtn.addEventListener('click', function() {
                if (window.currentLayout === 'table') {
                    // Switch to cards
                    window.currentLayout = 'cards';
                    tableLayoutContainer.classList.add('hidden');
                    cardLayoutContainer.classList.remove('hidden');

                    layoutToggleIcon.className = 'fi fi-sr-list text-[16px]';
                    layoutToggleText.textContent = 'Table';

                    // Fetch data for cards
                    fetchSectionsForCards(1);
                } else {
                    // Switch to table
                    window.currentLayout = 'table';
                    cardLayoutContainer.classList.add('hidden');
                    tableLayoutContainer.classList.remove('hidden');

                    layoutToggleIcon.className = 'fi fi-sr-list text-[16px]';
                    layoutToggleText.textContent = 'Table';

                    // Refresh table
                    sectionTable.draw();
                }
            });

            let gradeSelection = document.querySelector('#grade_selection');
            let pageLengthSelection = document.querySelector('#page-length-selection');

            let clearGradeFilterBtn = document.querySelector('#clear-grade-filter-btn');
            let gradeContainer = document.querySelector('#grade_selection_container');

            pageLengthSelection.addEventListener('change', (e) => {

                let selectedPageLength = parseInt(e.target.value, 10);
                window.selectedPageLength = selectedPageLength;
                sectionTable.page.len(selectedPageLength).draw();

                // If in card layout, refresh cards
                if (window.currentLayout === 'cards') {
                    fetchSectionsForCards(1);
                }

            })

            gradeSelection.addEventListener('change', (e) => {

                let selectedOption = e.target.selectedOptions[0];
                let email = selectedOption.getAttribute('data-putanginamo');

                selectedGrade = email;
                sectionTable.draw();

                // If in card layout, refresh cards
                if (window.currentLayout === 'cards') {
                    fetchSectionsForCards(1);
                }

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
                    sectionTable.draw();

                    // If in card layout, refresh cards
                    if (window.currentLayout === 'cards') {
                        fetchSectionsForCards(1);
                    }
                })

            }

            window.onload = function() {
                gradeSelection.selectedIndex = 0
                pageLengthSelection.selectedIndex = 0

                // Initialize with cards layout (default)
                fetchSectionsForCards(1);
            }

            dropDown('dropdown_2', 'dropdown_selection2');
            dropDown('dropdown_btn', 'dropdown_selection');

            if (assignCheckbox) {
                assignCheckbox.checked = false;


                assignCheckbox.addEventListener('change', function(e) {
                    const isChecked = e.target.checked;
                    const programId = document.getElementById('program_id').value;
                    const yearLevel = document.getElementById('year_level').value;
                    const container = document.getElementById('subjects-container');

                    container.innerHTML = ""; // clear old subjects

                    if (isChecked) {
                        if (!programId || !yearLevel) {
                            alert("Please select a program and year level first.");
                            e.target.checked = false; // uncheck box
                            return;
                        }

                        fetch(`/subjects/auto-assign?program_id=${programId}&year_level=${yearLevel}`, {
                                headers: {
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.subjects && data.subjects.length > 0) {
                                    data.subjects.forEach(subj => {
                                        const div = document.createElement('div');
                                        div.innerHTML = `
                        <label>
                            <input type="checkbox" name="subjects[]" value="${subj.id}" checked>
                             - ${subj.name}
                        </label>
                    `;
                                        container.appendChild(div);
                                    });
                                } else {
                                    container.innerHTML =
                                        "<p>No subjects found for this selection.</p>";
                                }
                            })
                            .catch(err => {
                                console.error("Error fetching subjects:", err);
                                container.innerHTML = "<p>Failed to load subjects.</p>";
                            });
                    }
                });
            }





            // Populate edit form when edit button is clicked
            document.getElementById('edit-program-modal-btn').addEventListener('click', function() {
                // Fetch current program data
                fetch(`{{ url('/program') }}/${programId}`)
                    .then(response => response.json())
                    .then(data => {
                        // Populate form fields
                        document.getElementById('program_code').value = data.code || '';
                        document.getElementById('program_name').value = data.name || '';
                        document.getElementById('program_track').value = data.track || '';
                        document.getElementById('program_status').value = data.status || 'active';
                    })
                    .catch(err => {
                        console.error('Error fetching program data:', err);
                        showAlert('error', 'Failed to load program data');
                    });
            });

            document.getElementById('edit-program-form').addEventListener('submit', function(e) {
                e.preventDefault();

                let form = e.target;
                let formData = new FormData(form);

                // Show loader
                showLoader("Editing program...");

                fetch(`/updateProgram/${programId}`, {
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: formData
                    })
                    .then(response => {
                        console.log('Response status:', response.status);
                        return response.json();
                    })
                    .then(data => {
                        hideLoader();

                        console.log('Response data:', data);

                        if (data.success) {
                            // Reset form
                            form.reset();

                            // Close modal
                            closeModal('edit-program-modal', 'modal-container-2');

                            // Show success alert
                            showAlert('success', data.success);

                            // Reload page to show updated data
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);

                        } else if (data.error) {
                            closeModal('edit-program-modal', 'modal-container-2');
                            showAlert('error', data.error);
                        }
                    })
                    .catch(err => {
                        hideLoader();
                        console.error('Error:', err);
                        closeModal('edit-program-modal', 'modal-container-2');
                        showAlert('error', 'Something went wrong while updating the program');
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

            // Program-based adviser filtering
            const programSelect = document.getElementById('program_id');
            const adviserSelect = document.getElementById('adviser_id');

            if (programSelect && adviserSelect) {
                programSelect.addEventListener('change', function() {
                    const selectedProgramId = this.value;
                    const adviserOptions = adviserSelect.querySelectorAll('option[data-program-id]');

                    adviserOptions.forEach(option => {
                        if (selectedProgramId === '' || option.getAttribute('data-program-id') ===
                            selectedProgramId) {
                            option.style.display = 'block';
                        } else {
                            option.style.display = 'none';
                        }
                    });

                    // Reset adviser selection if current selection is not valid for new program
                    if (adviserSelect.value && adviserSelect.querySelector(
                            `option[value="${adviserSelect.value}"]`).style.display === 'none') {
                        adviserSelect.value = '';
                    }
                });
            }

        });
    </script>
@endpush
