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
                <a href="/programs" class="block transition-colors hover:text-gray-900">Programs</a>
            </li>
            <li class="rtl:rotate-180">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
            </li>
            <li>
                <a href="{{ route('program.sections', $section->program->id) }}" class="block transition-colors hover:text-gray-900">
                    {{ $section->program->code }}
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
                <span class="block text-gray-900">{{ $section->name }}</span>
            </li>
        </ol>
    </nav>
@endsection

@section('modal')
    <!-- Import Students Modal -->
    <x-modal modal_id="import-modal" modal_name="Import Students" close_btn_id="import-modal-close-btn"
        modal_container_id="modal-container-1">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-progress-upload flex justify-center items-center'></i>
        </x-slot>
        <form enctype="multipart/form-data" id="import-form" class="p-6">
            @csrf
            <label for="fileInput" id="fileInputLabel"
                class="flex flex-col items-center justify-center w-full border-2 border-[#1A73E8]/60 border-dashed rounded-lg bg-[#E7F0FD] hover:bg-blue-100 cursor-pointer transition duration-150">
                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                    <svg class="w-8 h-8 mb-4 text-[#1A73E8]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 20 16">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                    </svg>
                    <p class="mb-2 text-sm text-[#0f111c]/80"><span class="font-semibold">Choose files to upload</span></p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Supported Formats: .xlsx, .xls, .csv</p>
                </div>
                <span class="bg-blue-500 px-4 py-2 rounded-lg text-white mb-4 hover:bg-blue-600 transition duration-200">Choose Files</span>
                <input type="file" id="fileInput" name="file" class="hidden" accept=".xlsx,.xls,.csv" required>
                <span id="fileName" class="text-gray-500 italic">No file chosen</span>
            </label>
        </form>
        <x-slot name="modal_info">
            <i class="fi fi-bs-download flex justify-center items-center"></i>
            <a href="{{ asset('templates/Officially_Enrolled_Students_Import_Template.xlsx') }}" download>Click here to download the template</a>
        </x-slot>
        <x-slot name="modal_buttons">
            <button id="cancel-btn"
                class="bg-gray-100 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-md text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-200 hover:ring hover:ring-gray-200 transition duration-150">
                Cancel
            </button>
            <button type="submit" form="import-form" name="action" value="verify"
                class="bg-blue-500 text-[14px] px-3 py-2 rounded-md text-[#f8f8f8] font-bold hover:ring hover:ring-blue-200 hover:bg-blue-400 transition duration-150 shadow-sm">
                Import
            </button>
        </x-slot>
    </x-modal>

    <!-- Edit Section Modal -->
    <x-modal modal_id="edit-section-modal" modal_name="Edit Section" close_btn_id="edit-section-close-btn"
        modal_container_id="modal-container-3">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-pen-clip flex justify-center items-center'></i>
        </x-slot>
        <form enctype="multipart/form-data" id="edit-section-form" class="p-6">
            @csrf
            <div class="flex flex-row gap-4">
                <div class="flex-1 flex flex-col">
                    <label for="name" class="text-sm font-medium text-gray-700 mb-2">Section Name</label>
                    <input type="text" name="name" id="name" placeholder="{{ $section->name }}"
                        class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                </div>
                <div class="flex-1 flex flex-col">
                    <label for="room" class="text-sm font-medium text-gray-700 mb-2">Room</label>
                    <input type="text" name="room" id="room" placeholder="{{ $section->room ?? 'Not Assigned Yet' }}"
                        class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                </div>
            </div>
        </form>
        <x-slot name="modal_buttons">
            <button id="edit-section-cancel-btn"
                class="bg-gray-100 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-md text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-200 hover:ring hover:ring-gray-200 transition duration-150">
                Cancel
            </button>
            <button type="submit" form="edit-section-form" name="action" value="verify"
                class="bg-blue-500 text-[14px] px-3 py-2 rounded-md text-[#f8f8f8] font-bold hover:ring hover:ring-blue-200 hover:bg-blue-400 transition duration-150 shadow-sm">
                Update
            </button>
        </x-slot>
    </x-modal>

    <!-- Add Student Modal -->
    <x-modal modal_id="add-student-modal" modal_name="Add Students" close_btn_id="add-student-modal-close-btn"
        modal_container_id="modal-container-2">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-user-plus flex justify-center items-center'></i>
        </x-slot>
        <form enctype="multipart/form-data" id="add-student-form" class="p-6">
            @csrf
            <div class="mb-4">
                <p class="text-sm text-gray-600 mb-2">Below is the list of {{ $section->year_level }} {{ $section->program->code }} students who are currently unassigned to a section.</p>
            </div>
            <div class="relative flex flex-col justify-start items-center overflow-y-auto max-h-[400px] border border-gray-200 rounded-lg">
                <div class="sticky top-0 flex flex-row justify-between items-center bg-[#f8f8f8] w-full p-3 border-b border-gray-200 font-medium text-sm text-gray-700">
                    <div class="w-8">#</div>
                    <div class="flex-1 text-left ml-4">Full Name</div>
                </div>
                @forelse ($students as $index => $student)
                    <div class="flex flex-row justify-between items-center gap-2 w-full p-3 hover:bg-gray-50 transition duration-150">
                        <div class="w-8 text-sm text-gray-500">{{ $index + 1 }}</div>
                        <input type="checkbox" name="student[]" id="lrn-{{ $student->lrn }}" value="{{ $student->id }}"
                            class="peer sr-only" />
                        <label for="lrn-{{ $student->lrn }}"
                            class="flex-1 bg-gray-100 peer-checked:bg-green-100 peer-checked:border-green-300 inline-block px-3 py-2 rounded border border-transparent cursor-pointer transition duration-150">
                            <span class="text-sm">{{ $student->user->last_name }}, {{ $student->user->first_name }}</span>
                        </label>
                    </div>
                @empty
                    <div class="py-8 text-center text-gray-500">
                        <i class="fi fi-sr-user-slash text-2xl mb-2"></i>
                        <p>No students available to assign.</p>
                    </div>
                @endforelse
            </div>
        </form>
        <x-slot name="modal_buttons">
            <button id="add-student-cancel-btn"
                class="bg-gray-100 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-md text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-200 hover:ring hover:ring-gray-200 transition duration-150">
                Cancel
            </button>
            <button type="submit" form="add-student-form" name="action" value="verify"
                class="bg-[#1A3165] text-[14px] px-3 py-2 rounded-md text-[#f8f8f8] font-bold hover:ring hover:ring-blue-200 hover:bg-blue-500 transition duration-150 shadow-sm">
                Add Students
            </button>
        </x-slot>
    </x-modal>

    <!-- Add Subject Modal -->
    <x-modal modal_id="add-subject-modal" modal_name="Add Subject" close_btn_id="add-subject-modal-close-btn"
        modal_container_id="modal-container-4">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-book flex justify-center items-center'></i>
        </x-slot>
        <div id="modal-content" class="max-h-96 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
            <form id="add-subject-form" class="p-6">
                @csrf
                <div class="space-y-4">
                <!-- Subject Selection -->
                <div class="flex flex-col">
                    <label for="subject_id" class="text-sm font-medium text-gray-700 mb-2">Subject</label>
                    <select name="subject_id" id="subject_id" required
                        class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                        <option value="" disabled selected>Select a subject</option>
                        <!-- Options will be populated dynamically -->
                    </select>
                </div>

                <!-- Teacher Selection -->
                <div class="flex flex-col">
                    <label for="teacher_id" class="text-sm font-medium text-gray-700 mb-2">Teacher</label>
                    <select name="teacher_id" id="teacher_id"
                        class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                        <option value="" selected>Select a teacher (optional)</option>
                        <!-- Options will be populated dynamically -->
                    </select>
                </div>

                <!-- Room -->
                <div class="flex flex-col">
                    <label for="room" class="text-sm font-medium text-gray-700 mb-2">Room</label>
                    <input type="text" name="room" id="room" placeholder="Enter room number"
                        class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                </div>

                <!-- Days of Week -->
                <div class="flex flex-col">
                    <label class="text-sm font-medium text-gray-700 mb-2">Days of Week</label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="flex items-center">
                            <input type="checkbox" name="days_of_week[]" value="Monday" class="mr-2">
                            <span class="text-sm">Monday</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="days_of_week[]" value="Tuesday" class="mr-2">
                            <span class="text-sm">Tuesday</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="days_of_week[]" value="Wednesday" class="mr-2">
                            <span class="text-sm">Wednesday</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="days_of_week[]" value="Thursday" class="mr-2">
                            <span class="text-sm">Thursday</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="days_of_week[]" value="Friday" class="mr-2">
                            <span class="text-sm">Friday</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="days_of_week[]" value="Saturday" class="mr-2">
                            <span class="text-sm">Saturday</span>
                        </label>
                    </div>
                </div>

                <!-- Time Schedule -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="flex flex-col">
                        <label for="start_time" class="text-sm font-medium text-gray-700 mb-2">Start Time</label>
                        <input type="time" name="start_time" id="start_time"
                            class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                    </div>
                    <div class="flex flex-col">
                        <label for="end_time" class="text-sm font-medium text-gray-700 mb-2">End Time</label>
                        <input type="time" name="end_time" id="end_time"
                            class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                    </div>
                </div>

                <!-- Schedule Conflict Warning -->
                <div id="schedule-conflict-warning" class="hidden p-3 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-center">
                        <i class="fi fi-sr-exclamation-triangle text-red-500 mr-2"></i>
                        <span class="text-sm text-red-700 font-medium">Schedule Conflict Detected!</span>
                    </div>
                    <p id="conflict-details" class="text-xs text-red-600 mt-1"></p>
                </div>

                <!-- Schedule Suggestions -->
                <div id="schedule-suggestions" class="hidden p-3 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-center mb-2">
                        <i class="fi fi-sr-lightbulb text-blue-500 mr-2"></i>
                        <span class="text-sm text-blue-700 font-medium">Available Time Slots</span>
                    </div>
                    <p class="text-xs text-blue-600 mb-2">Click on any suggestion to auto-fill the form:</p>
                    <div id="suggestions-list" class="max-h-32 overflow-y-auto scrollbar-thin scrollbar-thumb-blue-300 scrollbar-track-blue-100 grid grid-cols-2 gap-1 text-xs">
                        <!-- Suggestions will be populated here -->
                    </div>
                </div>
            </div>
        </form>
        </div>
        <x-slot name="modal_buttons">
            <button id="add-subject-cancel-btn"
                class="bg-gray-100 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-md text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-200 hover:ring hover:ring-gray-200 transition duration-150">
                Cancel
            </button>
            <button type="submit" form="add-subject-form" id="add-subject-submit-btn"
                class="bg-green-600 text-[14px] px-3 py-2 rounded-md text-white font-bold hover:ring hover:ring-green-200 hover:bg-green-700 transition duration-150 shadow-sm">
                Add Subject
            </button>
        </x-slot>
    </x-modal>
@endsection

@section('header')
    <div class="flex flex-row justify-between items-start text-start px-[14px] py-2">
        <div>
            <h1 class="text-[24px] font-black text-gray-900">{{ $section->name }}</h1>
            <p class="text-[14px] text-gray-600 mt-1">{{ $section->program->name }} • {{ $section->year_level }}</p>
        </div>
        <div class="flex flex-row justify-center items-center h-full">
            <div id="dropdown_btn"
                class="relative space-y-12 h-full flex flex-col justify-center items-center gap-4 cursor-pointer">
                <div
                    class="group relative inline-flex items-center gap-2 border border-[#1e1e1e]/0 text-gray-700 font-semibold py-2 px-3 rounded-lg hover:shadow-sm hover:bg-gray-100 hover:border-[#1e1e1e]/15 transition ease-out duration-300">
                    <i class="fi fi-br-menu-dots flex justify-center items-center"></i>
                </div>
                <div id="dropdown_selection"
                    class="absolute top-0 right-0 z-10 bg-[#f8f8f8] flex-col justify-center items-center gap-1 rounded-lg shadow-md border border-[#1e1e1e]/15 py-2 px-1 opacity-0 scale-95 pointer-events-none transition-all duration-200 ease-out translate-y-1">
                    <button id="edit-section-modal-btn"
                        class="flex-1 flex justify-start items-center px-8 py-2 gap-2 text-[14px] font-medium opacity-80 w-full border-b border-[#1e1e1e]/15 hover:bg-blue-200 hover:text-blue-600 truncate">
                        <i class="fi fi-rr-pen-clip text-[16px] flex justify-center item-center"></i>Edit Section
                    </button>
                    <button id="import-modal-btn"
                        class="flex-1 flex justify-start items-center px-8 py-2 gap-2 text-[14px] font-medium opacity-80 w-full border-b border-[#1e1e1e]/15 hover:bg-gray-200 truncate">
                        <i class="fi fi-sr-file-import text-[16px]"></i>Import Students
                    </button>
                    <x-nav-link href="/students/export/excel"
                        class="flex-1 flex justify-start items-center px-8 py-2 gap-2 text-[14px] font-medium opacity-80 w-full border-b border-[#1e1e1e]/15 hover:bg-green-200 hover:text-green-600 truncate">
                        <i class="fi fi-sr-file-excel text-[16px] flex justify-center item-center"></i>Export Students
                    </x-nav-link>
                    <button
                        class="flex-1 flex justify-start items-center px-8 py-2 gap-2 text-[14px] font-medium opacity-80 w-full border-b border-[#1e1e1e]/15 hover:bg-yellow-200 hover:text-yellow-600 truncate">
                        <i class="fi fi-rr-box text-[16px] flex justify-center item-center"></i>Archive Section
                    </button>
                    <button
                        class="flex-1 flex justify-start items-center px-8 py-2 gap-2 text-[14px] font-medium opacity-80 w-full hover:bg-red-200 hover:text-red-500 truncate">
                        <i class="fi fi-rr-trash text-[16px] flex justify-center item-center"></i>Delete Section
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('stat')
    <div class="flex justify-center items-center">
        <div
            class="flex flex-col justify-center items-center flex-grow px-6 pb-8 pt-2 bg-gradient-to-br from-blue-500 to-[#1A3165] rounded-xl shadow-xl border border-[#1e1e1e]/10 gap-2 text-white">
            <div class="flex flex-row items-start justify-between w-full gap-4 py-2 rounded-lg">
                <div class="flex flex-col items-start justify-center">
                    <h1 class="text-[45px] font-black" id="section_name">{{ $section->name }}</h1>
                    <p class="text-[16px] text-white/60">{{ $section->program->name }}</p>
                </div>
                <div class="flex flex-col items-end justify-center">
                    <p id="studentCount" class="text-[50px] font-bold">{{ $section->students->count() }}</p>
                    <div class="flex flex-row justify-center items-center opacity-70 gap-2 text-[14px]">
                        <p class="text-[16px]">Total Students</p>
                    </div>
                </div>
            </div>
            <div class="flex flex-row justify-center items-center w-full gap-4">
                <div
                    class="flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-6 py-4 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-150">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-sr-graduation-cap flex justify-center items-center"></i>
                        <p class="text-[14px]">Year Level</p>
                    </div>
                    <p class="font-bold text-[20px]">{{ $section->year_level }}</p>
                </div>
                <div
                    class="flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-6 py-4 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-300">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-sr-school flex flex-row justify-center items-center"></i>
                        <p class="text-[14px]">Program</p>
                    </div>
                    <p class="font-bold text-[20px]">{{ $section->program->code }}</p>
                </div>
                <div
                    class="flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-6 py-4 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-300">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-sr-home flex justify-center items-center"></i>
                        <p class="text-[14px]">Room</p>
                    </div>
                    <p class="font-bold text-[20px]" id="section_room">{{ $section->room ?? 'Not assigned' }}</p>
                </div>
                <div
                    class="flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-6 py-4 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-300">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-sr-user flex justify-center items-center"></i>
                        <p class="text-[14px]">Adviser</p>
                    </div>
                    <p class="font-bold text-[20px]">{{ $section->teacher->name ?? 'Not assigned' }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <x-alert />

    <!-- Main Content Layout -->
    <div class="flex flex-row justify-center items-start gap-4">
        
        <!-- Students Section -->
        <div class="w-[65%] bg-white rounded-xl shadow-md border border-[#1e1e1e]/10 p-6">
            <div class="flex flex-row justify-between items-center mb-6">
                <div>
                    <h2 class="text-[20px] font-bold text-gray-900">Students</h2>
                    <p class="text-[14px] text-gray-600 mt-1">Manage enrolled students in this section</p>
                </div>
                    <button id="add-student-modal-btn"
                    class="bg-[#1A3165] px-4 py-2 rounded-lg text-[14px] font-semibold flex justify-center items-center gap-2 text-white hover:bg-[#0f1f3a] transition duration-150">
                    <i class="fi fi-rr-plus flex justify-center items-center"></i>
                        Add Student
                    </button>
                </div>

            <!-- Search and Filters -->
            <div class="flex flex-row justify-between items-center mb-4 gap-4">
                    <label for="myCustomSearch"
                    class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 rounded-lg py-2 px-3 gap-2 flex-1 hover:ring hover:ring-blue-200 focus-within:ring focus-within:ring-blue-100 focus-within:border-blue-500 transition duration-150 shadow-sm">
                        <i class="fi fi-rs-search flex justify-center items-center text-[#1e1e1e]/60 text-[16px]"></i>
                        <input type="search" name="" id="myCustomSearch"
                            class="my-custom-search bg-transparent outline-none text-[14px] w-full peer"
                        placeholder="Search students...">
                        <button id="clear-btn"
                            class="clear-btn flex justify-center items-center peer-placeholder-shown:hidden peer-not-placeholder-shown:block">
                            <i class="fi fi-rs-cross-small text-[18px] flex justify-center items-center"></i>
                        </button>
                    </label>
                
                <div class="flex flex-row gap-2">
                    <div class="flex flex-row justify-between items-center rounded-lg border border-[#1e1e1e]/10 bg-gray-100 px-3 py-2 gap-2 hover:bg-gray-200 hover:border-[#1e1e1e]/15 transition-all ease-in-out duration-150 shadow-sm">
                            <select name="pageLength" id="page-length-selection"
                                class="appearance-none bg-transparent text-[14px] font-medium text-gray-700 h-full w-full cursor-pointer">
                                <option selected disabled>Entries</option>
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        <i class="fi fi-rr-caret-down text-gray-500 flex justify-center items-center"></i>
                        </div>

                    <!-- Gender Filter -->
                    <div id="gender_selection_container"
                        class="flex flex-row justify-between items-center rounded-lg border border-[#1e1e1e]/10 bg-gray-100 px-3 py-2 gap-2 hover:bg-gray-200 hover:border-[#1e1e1e]/15 transition-all ease-in-out duration-150 shadow-sm">
                        <select name="gender_selection" id="gender_selection"
                                class="appearance-none bg-transparent text-[14px] font-medium text-gray-700 h-full w-full cursor-pointer">
                            <option value="" disabled selected>Gender</option>
                            <option value="" data-gender="Male">Male</option>
                            <option value="" data-gender="Female">Female</option>
                            </select>
                        <i id="clear-gender-filter-btn"
                                class="fi fi-rr-caret-down text-gray-500 flex justify-center items-center"></i>
                        </div>
                    </div>
                </div>

            <!-- Students Table -->
            <div class="w-full">
                <table id="sections" class="w-full table-fixed">
                    <thead class="text-[14px]">
                        <tr>
                            <th class="text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-3">
                                <span class="mr-2 font-medium opacity-60 cursor-pointer">#</span>
                            </th>
                            <th class="w-1/6 text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-3">
                                <span class="mr-2 font-medium opacity-60 cursor-pointer">LRN</span>
                                <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                            </th>
                            <th class="w-1/3 text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-3">
                                <span class="mr-2 font-medium opacity-60 cursor-pointer">Full Name</span>
                                <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                            </th>
                            <th class="w-1/6 text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-3">
                                <span class="mr-2 font-medium opacity-60 cursor-pointer">Age</span>
                                <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                            </th>
                            <th class="w-1/6 text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-3">
                                <span class="mr-2 font-medium opacity-60 cursor-pointer">Gender</span>
                                <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                            </th>
                            <th class="w-1/6 text-center bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-3">
                                <span class="mr-2 font-medium opacity-60 select-none">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be populated by DataTables -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Subjects Section -->
        <div class="w-[35%] bg-white rounded-xl shadow-md border border-[#1e1e1e]/10 p-6">
            <div class="flex flex-row justify-between items-center mb-6">
                <div>
                    <h2 class="text-[20px] font-bold text-gray-900">Subjects</h2>
                    <p class="text-[14px] text-gray-600 mt-1">Subjects assigned to this section</p>
                </div>
                <button id="add-subject-modal-btn" class="bg-green-600 px-4 py-2 rounded-lg text-[14px] font-semibold flex justify-center items-center gap-2 text-white hover:bg-green-700 transition duration-150">
                    <i class="fi fi-rr-plus flex justify-center items-center"></i>
                    Add Subject
                </button>
            </div>

            <!-- Subjects Grid -->
            <div class="space-y-4">
                @forelse($section->sectionSubjects as $sectionSubject)
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-200 p-4 hover:shadow-md transition duration-200">
                        <div class="flex flex-row justify-between items-start mb-3">
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-[#1A3165]">{{ $sectionSubject->subject->name }}</h3>
                                <p class="text-sm text-gray-600">{{ $sectionSubject->subject->category ?? 'General' }}</p>
                            </div>
                            <div class="flex flex-col items-end">
                                <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">{{ $sectionSubject->subject->year_level ?? $section->year_level }}</span>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 mb-3">
                            <div class="flex flex-row items-center gap-2">
                                <i class="fi fi-sr-user text-[#1A3165] text-sm"></i>
                                <div class="flex flex-col">
                                    <span class="text-xs text-gray-500">Teacher</span>
                                    <span class="text-sm font-medium">{{ $sectionSubject->teacher->name ?? 'Not assigned' }}</span>
                                </div>
                            </div>
                            
                            <div class="flex flex-row items-center gap-2">
                                <i class="fi fi-sr-home text-[#1A3165] text-sm"></i>
                                <div class="flex flex-col">
                                    <span class="text-xs text-gray-500">Room</span>
                                    <span class="text-sm font-medium">{{ $sectionSubject->room ?? 'Not assigned' }}</span>
                                </div>
                            </div>
                        </div>

                        @if($sectionSubject->days_of_week || $sectionSubject->start_time)
                        <div class="flex flex-row items-center gap-2 mb-3">
                            <i class="fi fi-sr-clock text-[#1A3165] text-sm"></i>
                            <div class="flex flex-col">
                                <span class="text-xs text-gray-500">Schedule</span>
                                <span class="text-sm font-medium">
                                    @if($sectionSubject->days_of_week)
                                        {{ implode(', ', $sectionSubject->days_of_week) }}
                                    @endif
                                    @if($sectionSubject->start_time && $sectionSubject->end_time)
                                        • {{ $sectionSubject->start_time }} - {{ $sectionSubject->end_time }}
                                    @endif
                                </span>
                            </div>
                        </div>
                        @endif

                        <div class="flex flex-row justify-between items-center pt-2 border-t border-blue-200">
                            <div class="flex flex-row items-center gap-2">
                                <i class="fi fi-sr-users text-[#1A3165] text-sm"></i>
                                <span class="text-sm text-gray-600">{{ $sectionSubject->students()->count() }} enrolled</span>
                            </div>
                            <div class="flex flex-row gap-2">
                                <button class="px-3 py-1 text-xs font-medium text-blue-600 bg-blue-100 rounded hover:bg-blue-200 transition duration-150">
                                    <i class="fi fi-rr-edit text-xs mr-1"></i>Edit
                                </button>
                                <button class="px-3 py-1 text-xs font-medium text-red-600 bg-red-100 rounded hover:bg-red-200 transition duration-150">
                                    <i class="fi fi-rr-trash text-xs mr-1"></i>Remove
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 text-gray-500">
                        <i class="fi fi-sr-book text-4xl mb-4"></i>
                        <p class="text-lg font-medium">No subjects assigned</p>
                        <p class="text-sm">Add subjects to this section to get started</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="module">
        import { dropDown } from "/js/dropDown.js";
        import { clearSearch } from "/js/clearSearch.js"
        import { initModal } from "/js/modal.js";
        import { showAlert } from "/js/alert.js";
        import { showLoader, hideLoader } from "/js/loader.js";
        import { initCustomDataTable } from "/js/initTable.js";

        let table1;
        window.selectedGrade = '';
        window.selectedProgram = '';
        window.selectedGender = '';
        window.selectedPageLength = 10;

        let sectionId = @json($section->id);

        document.addEventListener("DOMContentLoaded", function() {
            // Initialize modals
            initModal('import-modal', 'import-modal-btn', 'import-modal-close-btn', 'cancel-btn', 'modal-container-1');
            initModal('add-student-modal', 'add-student-modal-btn', 'add-student-modal-close-btn', 'add-student-cancel-btn', 'modal-container-2');
            initModal('edit-section-modal', 'edit-section-modal-btn', 'edit-section-close-btn', 'edit-section-cancel-btn', 'modal-container-3');
            initModal('add-subject-modal', 'add-subject-modal-btn', 'add-subject-modal-close-btn', 'add-subject-cancel-btn', 'modal-container-4');

            // Refresh student list when modal is opened
            document.getElementById('add-student-modal-btn').addEventListener('click', function() {
                refreshStudentList();
            });

            // Add event listener for Add Subject modal button
            document.getElementById('add-subject-modal-btn').addEventListener('click', function() {
                loadSubjectsAndTeachers();
            });

            let studentCount = document.querySelector('#studentCount');
            let sectionName = document.querySelector('#section_name');
            let sectionRoom = document.querySelector('#section_room');

            // Initialize DataTable using the clean component
            table1 = initCustomDataTable(
                'sections',
                `/getStudents/${sectionId}`,
                [
                    { data: 'index' },
                    { data: 'lrn' },
                    { data: 'full_name' },
                    { data: 'age' },
                    { data: 'gender' },
                    {
                        data: 'id',
                        render: function(data, type, row) {
                            return `
                                <div class='flex flex-row justify-center items-center gap-1'>
                                    <button class="px-2 py-1 text-xs font-medium text-blue-600 bg-blue-100 rounded hover:bg-blue-200 transition duration-150">
                                        <i class="fi fi-rr-eye text-xs"></i>
                                    </button>
                                    <button class="px-2 py-1 text-xs font-medium text-red-600 bg-red-100 rounded hover:bg-red-200 transition duration-150">
                                        <i class="fi fi-rr-trash text-xs"></i>
                                    </button>
                            </div>
                            `;
                        },
                        orderable: false,
                        searchable: false
                    }
                ],
                [[0, 'asc']],
                'myCustomSearch',
                [
                    { width: '5%', targets: 0, className: 'text-center' },
                    { width: '18%', targets: 1 },
                    { width: '30%', targets: 2 },
                    { width: '15%', targets: 3, className: 'text-center' },
                    { width: '15%', targets: 4, className: 'text-center' },
                    { width: '20%', targets: 5, className: 'text-center' }
                ]
            );

            clearSearch('clear-btn', 'myCustomSearch', table1);

            // Event listeners
            let pageLengthSelection = document.querySelector('#page-length-selection');
            let genderSelection = document.querySelector('#gender_selection');
            let clearGenderFilterBtn = document.querySelector('#clear-gender-filter-btn');
            let genderContainer = document.querySelector('#gender_selection_container');

            pageLengthSelection.addEventListener('change', (e) => {
                let selectedPageLength = parseInt(e.target.value, 10);
                window.selectedPageLength = selectedPageLength;
                table1.page.len(selectedPageLength).draw();
            });

            genderSelection.addEventListener('change', (e) => {
                let selectedOption = e.target.selectedOptions[0];
                let gender = selectedOption.getAttribute('data-gender');

                window.selectedGender = gender;
                table1.draw();

                // Update UI to show active filter
                let clearGenderFilterRem = ['text-gray-500', 'fi-rr-caret-down'];
                let clearGenderFilterAdd = ['fi-bs-cross-small', 'cursor-pointer', 'text-[#1A3165]'];
                let genderSelectionRem = ['border-[#1e1e1e]/10', 'text-gray-700'];
                let genderSelectionAdd = ['text-[#1A3165]'];
                let genderContainerRem = ['bg-gray-100'];
                let genderContainerAdd = ['bg-[#1A73E8]/15', 'border-[#1A73E8]', 'hover:bg-[#1A73E8]/25'];

                clearGenderFilterBtn.classList.remove(...clearGenderFilterRem);
                clearGenderFilterBtn.classList.add(...clearGenderFilterAdd);
                genderSelection.classList.remove(...genderSelectionRem);
                genderSelection.classList.add(...genderSelectionAdd);
                genderContainer.classList.remove(...genderContainerRem);
                genderContainer.classList.add(...genderContainerAdd);

                handleClearGenderFilter(selectedOption);
            });

            function handleClearGenderFilter(selectedOption) {
                clearGenderFilterBtn.addEventListener('click', () => {
                    genderContainer.classList.remove('bg-[#1A73E8]/15');
                    genderContainer.classList.remove('border-blue-300');
                    genderContainer.classList.remove('hover:bg-blue-300');
                    clearGenderFilterBtn.classList.remove('fi-bs-cross-small');

                    clearGenderFilterBtn.classList.add('fi-rr-caret-down');
                    genderContainer.classList.add('bg-gray-100');
                    genderSelection.classList.remove('text-[#1A3165]');
                    genderSelection.classList.add('text-gray-700');
                    clearGenderFilterBtn.classList.remove('text-[#1A3165]');
                    clearGenderFilterBtn.classList.add('text-gray-500');

                    genderSelection.selectedIndex = 0;
                    window.selectedGender = '';
                    table1.draw();
                });
            }

            // Dropdown functionality for main dropdown
            dropDown('dropdown_btn', 'dropdown_selection');

            // Form submissions
            document.getElementById('edit-section-form').addEventListener('submit', function(e) {
                e.preventDefault();
                closeModal();
                let form = e.target;
                let formData = new FormData(form);
                showLoader("Updating...");

                fetch(`/section/${sectionId}`, {
                        method: "POST",
                        body: formData,
                    headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" }
                    })
                    .then(response => response.json())
                    .then(data => {
                        hideLoader();
                        if (data.success) {
                            sectionName.innerHTML = data.newData['newSectionName'];
                        sectionRoom.innerHTML = data.newData['newRoom'] || 'Not assigned';
                            closeModal('edit-section-modal', 'modal-container-3');
                            showAlert('success', data.success);
                        } else if (data.error) {
                            closeModal('edit-section-modal', 'modal-container-3');
                            showAlert('error', data.error);
                        }
                    })
                    .catch(err => {
                        hideLoader();
                    closeModal('edit-section-modal', 'modal-container-3');
                        showAlert('error', 'Something went wrong');
                    });
            });

            // Function to refresh the student list in the modal
            async function refreshStudentList() {
                try {
                    const response = await fetch(`/getAvailableStudents/${sectionId}`);
                    const data = await response.json();
                    
                    const studentListContainer = document.querySelector('#add-student-form .relative.flex.flex-col');
                    
                    // Clear ALL existing content except the header
                    const headerRow = studentListContainer.querySelector('.sticky.top-0');
                    studentListContainer.innerHTML = '';
                    if (headerRow) {
                        studentListContainer.appendChild(headerRow);
                    }
                    
                    // Add new student rows
                    if (data.students && data.students.length > 0) {
                        data.students.forEach((student, index) => {
                            const studentRow = document.createElement('div');
                            studentRow.className = 'flex flex-row justify-between items-center gap-2 w-full p-3 hover:bg-gray-50 transition duration-150';
                            studentRow.innerHTML = `
                                <div class="w-8 text-sm text-gray-500">${index + 1}</div>
                                <input type="checkbox" name="student[]" id="lrn-${student.lrn}" value="${student.id}" class="peer sr-only" />
                                <label for="lrn-${student.lrn}" class="flex-1 bg-gray-100 peer-checked:bg-green-100 peer-checked:border-green-300 inline-block px-3 py-2 rounded border border-transparent cursor-pointer transition duration-150">
                                    <span class="text-sm">${student.user.last_name}, ${student.user.first_name}</span>
                                </label>
                            `;
                            studentListContainer.appendChild(studentRow);
                        });
                    } else {
                        // Show empty state
                        const emptyState = document.createElement('div');
                        emptyState.className = 'py-8 text-center text-gray-500';
                        emptyState.innerHTML = `
                            <i class="fi fi-sr-user-slash text-2xl mb-2"></i>
                            <p>No students available to assign.</p>
                        `;
                        studentListContainer.appendChild(emptyState);
                    }
                } catch (error) {
                    console.error('Error refreshing student list:', error);
                }
            }

            document.getElementById('add-student-form').addEventListener('submit', function(e) {
                e.preventDefault();
                closeModal();
                let form = e.target;
                let formData = new FormData(form);
                showLoader("Adding...");

                fetch(`/assign-section/${sectionId}`, {
                        method: "POST",
                        body: formData,
                    headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" }
                    })
                    .then(response => response.json())
                    .then(data => {
                        hideLoader();
                        if (data.success) {
                            studentCount.innerHTML = data.count;
                            closeModal('add-student-modal', 'modal-container-2');
                            showAlert('success', data.success);
                            table1.draw();

                        // Refresh the student list in the modal
                        refreshStudentList();
                        
                        // Clear all checkboxes
                        const checkboxes = form.querySelectorAll('input[type="checkbox"]');
                        checkboxes.forEach(checkbox => {
                            checkbox.checked = false;
                        });
                    } else if (data.error) {
                            closeModal('add-student-modal', 'modal-container-2');
                            showAlert('error', data.error);
                        }
                    })
                    .catch(err => {
                        hideLoader();
                        closeModal('add-student-modal', 'modal-container-2');
                        showAlert('error', 'Something went wrong');
                    });
            });

            function closeModal(modalId, modalContainerId) {
                let modal = document.querySelector(`#${modalId}`);
                let body = document.querySelector(`#${modalContainerId}`);
                if (modal && body) {
                    modal.classList.remove('opacity-100', 'scale-100');
                    modal.classList.add('opacity-0', 'pointer-events-none', 'scale-95');
                    body.classList.remove('opacity-100');
                    body.classList.add('opacity-0', 'pointer-events-none');
                }
            }

            // Function to load subjects and teachers for the modal
            async function loadSubjectsAndTeachers() {
                try {
                    // Load subjects
                    const subjectsResponse = await fetch(`/getAvailableSubjects/${sectionId}`);
                    const subjectsData = await subjectsResponse.json();
                    
                    const subjectSelect = document.getElementById('subject_id');
                    subjectSelect.innerHTML = '<option value="" disabled selected>Select a subject</option>';
                    
                    if (subjectsData.subjects && subjectsData.subjects.length > 0) {
                        subjectsData.subjects.forEach(subject => {
                            const option = document.createElement('option');
                            option.value = subject.id;
                            option.textContent = `${subject.name} (${subject.category})`;
                            subjectSelect.appendChild(option);
                        });
                    } else {
                        const option = document.createElement('option');
                        option.value = '';
                        option.textContent = 'No subjects available';
                        option.disabled = true;
                        subjectSelect.appendChild(option);
                    }

                    // Load teachers
                    const teachersResponse = await fetch('/getTeachers');
                    const teachersData = await teachersResponse.json();
                    
                    const teacherSelect = document.getElementById('teacher_id');
                    teacherSelect.innerHTML = '<option value="" selected>Select a teacher (optional)</option>';
                    
                    if (teachersData.teachers && teachersData.teachers.length > 0) {
                        teachersData.teachers.forEach(teacher => {
                            const option = document.createElement('option');
                            option.value = teacher.id;
                            option.textContent = `${teacher.first_name} ${teacher.last_name}`;
                            teacherSelect.appendChild(option);
                        });
                    }
                } catch (error) {
                    console.error('Error loading subjects and teachers:', error);
                }
            }

            // Function to check schedule conflicts
            async function checkScheduleConflict() {
                const formData = new FormData(document.getElementById('add-subject-form'));
                const data = Object.fromEntries(formData.entries());
                
                // Convert days_of_week array properly
                const daysOfWeek = [];
                document.querySelectorAll('input[name="days_of_week[]"]:checked').forEach(checkbox => {
                    daysOfWeek.push(checkbox.value);
                });
                data.days_of_week = daysOfWeek;

                try {
                    const response = await fetch(`/checkScheduleConflict/${sectionId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(data)
                    });

                    const result = await response.json();
                    const warningDiv = document.getElementById('schedule-conflict-warning');
                    const detailsDiv = document.getElementById('conflict-details');
                    const suggestionsDiv = document.getElementById('schedule-suggestions');
                    const suggestionsList = document.getElementById('suggestions-list');
                    const submitBtn = document.getElementById('add-subject-submit-btn');

                    if (result.has_conflicts) {
                        warningDiv.classList.remove('hidden');
                        detailsDiv.innerHTML = result.conflicts.map(conflict => conflict.message).join('<br>');
                        submitBtn.disabled = true;
                        submitBtn.classList.add('opacity-50', 'cursor-not-allowed');

                        // Show suggestions if available
                        if (result.suggestions && result.suggestions.length > 0) {
                            suggestionsDiv.classList.remove('hidden');
                            suggestionsList.innerHTML = '';
                            
                            result.suggestions.forEach(suggestion => {
                                const suggestionBtn = document.createElement('button');
                                suggestionBtn.type = 'button';
                                suggestionBtn.className = 'px-2 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded text-xs transition duration-150';
                                suggestionBtn.textContent = suggestion.display;
                                suggestionBtn.onclick = () => applySuggestion(suggestion);
                                suggestionsList.appendChild(suggestionBtn);
                            });
                            
                            // Increase modal height when suggestions are shown
                            document.getElementById('modal-content').classList.remove('max-h-96');
                            document.getElementById('modal-content').classList.add('max-h-[32rem]');
                        } else {
                            suggestionsDiv.classList.add('hidden');
                            // Increase modal height when only conflicts are shown
                            document.getElementById('modal-content').classList.remove('max-h-96');
                            document.getElementById('modal-content').classList.add('max-h-[28rem]');
                        }
                    } else {
                        warningDiv.classList.add('hidden');
                        suggestionsDiv.classList.add('hidden');
                        submitBtn.disabled = false;
                        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                        
                        // Reset to default height when no conflicts/suggestions
                        document.getElementById('modal-content').classList.remove('max-h-[28rem]', 'max-h-[32rem]');
                        document.getElementById('modal-content').classList.add('max-h-96');
                    }
                } catch (error) {
                    console.error('Error checking schedule conflict:', error);
                }
            }

            // Function to apply a schedule suggestion
            function applySuggestion(suggestion) {
                // Set the start and end times
                document.getElementById('start_time').value = suggestion.start_time;
                document.getElementById('end_time').value = suggestion.end_time;
                
                // Clear all day checkboxes first
                document.querySelectorAll('input[name="days_of_week[]"]').forEach(checkbox => {
                    checkbox.checked = false;
                });
                
                // Check the specific day
                const dayCheckbox = document.querySelector(`input[name="days_of_week[]"][value="${suggestion.day}"]`);
                if (dayCheckbox) {
                    dayCheckbox.checked = true;
                }
                
                // Hide suggestions and re-check for conflicts
                document.getElementById('schedule-suggestions').classList.add('hidden');
                
                // Reset modal height
                document.getElementById('modal-content').classList.remove('max-h-[28rem]', 'max-h-[32rem]');
                document.getElementById('modal-content').classList.add('max-h-96');
                
                // Trigger conflict check to update the UI
                setTimeout(() => {
                    checkScheduleConflict();
                }, 100);
            }

            // Add event listeners for live conflict checking
            document.addEventListener('DOMContentLoaded', function() {
                const conflictInputs = ['teacher_id', 'room', 'start_time', 'end_time'];
                conflictInputs.forEach(inputId => {
                    const input = document.getElementById(inputId);
                    if (input) {
                        input.addEventListener('change', checkScheduleConflict);
                    }
                });

                // Add listeners for day checkboxes
                document.querySelectorAll('input[name="days_of_week[]"]').forEach(checkbox => {
                    checkbox.addEventListener('change', checkScheduleConflict);
                });
            });

            // Handle form submission
            document.getElementById('add-subject-form').addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const data = Object.fromEntries(formData.entries());
                
                // Convert days_of_week array properly
                const daysOfWeek = [];
                document.querySelectorAll('input[name="days_of_week[]"]:checked').forEach(checkbox => {
                    daysOfWeek.push(checkbox.value);
                });
                data.days_of_week = daysOfWeek;

                // First check for conflicts before submitting
                try {
                    const conflictResponse = await fetch(`/checkScheduleConflict/${sectionId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(data)
                    });

                    const conflictResult = await conflictResponse.json();

                    if (conflictResult.has_conflicts) {
                        // Show conflict warning and prevent submission
                        const warningDiv = document.getElementById('schedule-conflict-warning');
                        const detailsDiv = document.getElementById('conflict-details');
                        const suggestionsDiv = document.getElementById('schedule-suggestions');
                        const suggestionsList = document.getElementById('suggestions-list');
                        
                        warningDiv.classList.remove('hidden');
                        detailsDiv.innerHTML = conflictResult.conflicts.map(conflict => conflict.message).join('<br>');
                        
                        // Show suggestions if available
                        if (conflictResult.suggestions && conflictResult.suggestions.length > 0) {
                            suggestionsDiv.classList.remove('hidden');
                            suggestionsList.innerHTML = '';
                            
                            conflictResult.suggestions.forEach(suggestion => {
                                const suggestionBtn = document.createElement('button');
                                suggestionBtn.type = 'button';
                                suggestionBtn.className = 'px-2 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded text-xs transition duration-150';
                                suggestionBtn.textContent = suggestion.display;
                                suggestionBtn.onclick = () => applySuggestion(suggestion);
                                suggestionsList.appendChild(suggestionBtn);
                            });
                            
                            // Increase modal height when suggestions are shown
                            document.getElementById('modal-content').classList.remove('max-h-96');
                            document.getElementById('modal-content').classList.add('max-h-[32rem]');
                        } else {
                            suggestionsDiv.classList.add('hidden');
                            // Increase modal height when only conflicts are shown
                            document.getElementById('modal-content').classList.remove('max-h-96');
                            document.getElementById('modal-content').classList.add('max-h-[28rem]');
                        }
                        
                        showAlert('error', 'Schedule conflicts detected. Please resolve conflicts before submitting.');
                        return; // Stop submission
                    }

                    // No conflicts, proceed with submission
                    const response = await fetch(`/assignSubject/${sectionId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(data)
                    });

                    const result = await response.json();

                    if (result.success) {
                        closeModal('add-subject-modal', 'modal-container-4');
                        showAlert('success', result.success);
                        
                        // Refresh the page to show the new subject
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        showAlert('error', result.error || 'Failed to assign subject');
                    }
                } catch (error) {
                    console.error('Error assigning subject:', error);
                    showAlert('error', 'Something went wrong');
                }
            });

            // Initialize page
            window.onload = function() {
                pageLengthSelection.selectedIndex = 0;
                genderSelection.selectedIndex = 0;
            }
        });
    </script>
@endpush