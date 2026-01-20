@extends('layouts.admin', ['title' => 'Teacher Dashboard'])

@section('header')
    <div class="flex flex-col justify-center items-start text-start px-[14px] py-2">
        @if (Route::is('teacher.advising-sections'))
            <h1 class="text-[20px] font-black">Advising Sections</h1>
            <p class="text-[14px] text-gray-900/60">View students and your assigned subjects per section.</p>
        @else
            <h1 class="text-[20px] font-black">Dashboard</h1>
            <p class="text-[14px] text-gray-900/60">View and manage subjects you're teaching.</p>
        @endif
    </div>
@endsection

@section('stat')
    <div class="flex justify-center items-center">
        <div
            class="flex flex-col justify-center items-center flex-grow px-10 pb-10 pt-2 bg-gradient-to-br from-[#199BCF] to-[#1A3165] rounded-xl shadow-[#199BCF]/30 shadow-xl gap-2 text-white">

            <div class="flex flex-row items-center justify-between w-full gap-4 py-2 rounded-lg ">

                <div class="flex flex-col items-start justify-end gap-2 pt-4">
                    <h1 class="text-[40px] font-black" id="teacher_name">Dashboard Overview</h1>
                    <p class="text-[16px] text-white/60">{{ $teacher->program->name ?? 'Teacher' }}
                    </p>
                </div>

                <div class="flex flex-col items-end justify-center">
                    <p id="sectionCount" class="text-[50px] font-bold ">{{ $totalSections }}</p>
                    <div class="flex flex-row justify-center items-center opacity-70 gap-2 text-[14px]">
                        <p class="text-[16px]">Total Sections</p>
                    </div>
                </div>

            </div>
            <div class="flex flex-row justify-center items-center w-full gap-4">
                <div
                    class="flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-8 py-6 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-150">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-rr-users flex justify-center items-center"></i>
                        <p class="text-[14px]">Total Students</p>
                    </div>
                    <p class="font-bold text-[24px]">{{ $totalStudents }}</p>
                    <p class="text-[12px] truncate text-gray-300">Students across all sections</p>
                </div>

                <div
                    class="flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-8 py-6 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-300">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-rr-user-tie flex flex-row justify-center items-center"></i>
                        <p class="text-[14px]">Advised Sections</p>
                    </div>
                    <p class="font-bold text-[24px]">{{ $advisedSectionsCount }}</p>
                    <p class="text-[12px] truncate text-gray-300">Sections you're advising</p>
                </div>

                <div
                    class="flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-8 py-6 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-300">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-rr-chalkboard-teacher flex justify-center items-center"></i>
                        <p class="text-[14px]">Teaching Sections</p>
                    </div>
                    <p class="font-bold text-[24px]">{{ $teachingSectionsCount }}</p>
                    <p class="text-[12px] truncate text-gray-300">Sections you're teaching</p>
                </div>

                <div
                    class="flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-8 py-6 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-300">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-rr-book flex justify-center items-center"></i>
                        <p class="text-[14px]">Total Subjects</p>
                    </div>
                    <p class="font-bold text-[24px]">{{ $teacher->sectionSubjects()->count() }}</p>
                    <p class="text-[12px] truncate text-gray-300">Subjects you're teaching</p>
                </div>
            </div>

            <div class="flex flex-row justify-center items-center w-full gap-4 mt-4">
                <div
                    class="flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-8 py-6 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-300">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-rr-graduation-cap flex justify-center items-center"></i>
                        <p class="text-[14px]">Program</p>
                    </div>
                    <p class="font-bold text-[24px]">{{ $teacher->program->code ?? 'Not Set' }}</p>
                    <p class="text-[12px] truncate text-gray-300">{{ $teacher->program->name ?? 'No program assigned' }}</p>
                </div>

                <div
                    class="flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-8 py-6 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-300">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-rr-calendar flex justify-center items-center"></i>
                        <p class="text-[14px]">Academic Year</p>
                    </div>
                    <p class="font-bold text-[24px]">{{ $academicTermData['year'] ?? 'No Active Term' }}</p>
                    <p class="text-[12px] truncate text-gray-300">Current academic year</p>
                </div>

                <div
                    class="flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-8 py-6 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-300">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-rr-clock flex justify-center items-center"></i>
                        <p class="text-[14px]">Semester</p>
                    </div>
                    <p class="font-bold text-[24px]">{{ $academicTermData['semester'] ?? 'No Active Term' }}</p>
                    <p class="text-[12px] truncate text-gray-300">Current semester</p>
                </div>

            </div>

        </div>
    </div>
@endsection

@section('content')
    <x-alert />

    <!-- Tab Navigation -->
    <div
        class="px-5 text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:text-gray-400 dark:border-gray-300">
        <ul class="flex flex-wrap -mb-px">
            <li class="me-2">
                <a href="{{ route('teacher.dashboard') }}"
                    class="inline-block p-4 border-b-2 rounded-t-lg
                    {{ Route::is('teacher.dashboard') ? 'text-blue-600 border-blue-600 dark:text-blue-500 dark:border-blue-500' : 'border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300' }}">
                    Subjects
                </a>
            </li>
            <li class="me-2">
                <a href="{{ route('teacher.advising-sections') }}"
                    class="inline-block p-4 border-b-2 rounded-t-lg
                    {{ Route::is('teacher.advising-sections') ? 'text-blue-600 border-blue-600 dark:text-blue-500 dark:border-blue-500' : 'border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300' }}">
                    Advising Sections
                </a>
            </li>
        </ul>
    </div>

    @if (Route::is('teacher.dashboard'))
    <div class="flex flex-row justify-center items-start gap-4">
        <div
            class="flex flex-col justify-start items-center flex-grow p-5 space-y-4 bg-[#f8f8f8] rounded-xl shadow-md border border-[#1e1e1e]/10 w-[40%]">
            <div class="flex flex-col my-2 justify-center items-center w-full">
                <span class="font-semibold text-[18px]">
                    Subjects
                </span>
                <span class="font-medium text-gray-400 text-[14px]">
                    Subjects you're teaching
                </span>
            </div>
            <div class="flex flex-row justify-between items-center w-full">

                <div class="w-full flex flex-row justify-between items-center gap-4">

                    <label for="myCustomSearch"
                        class="flex flex-row justify-start items-center border-2 border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-2 gap-2 w-full outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 focus-within:shadow-lg transition duration-150 shadow-sm">
                        <i class="fi fi-rs-search flex justify-center items-center text-[#1e1e1e]/60 text-[16px]"></i>
                        <input type="search" name="" id="myCustomSearch"
                            class="my-custom-search bg-transparent outline-none text-[14px] w-full peer"
                            placeholder="Search by subject, section, program, etc.">
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

                        <div id="program_selection_container"
                            class="flex flex-row justify-between items-center rounded-lg border border-[#1e1e1e]/10 bg-gray-100 px-3 py-2 gap-2 hover:bg-gray-200 hover:border-[#1e1e1e]/15 transition-all ease-in-out duration-150 shadow-sm">

                            <select name="program_selection" id="program_selection"
                                class="appearance-none bg-transparent text-[14px] font-medium text-gray-700 h-full w-full cursor-pointer">
                                <option value="" disabled selected>Program</option>
                                @foreach($programs as $program)
                                    <option value="{{ $program->code }}" data-program="{{ $program->code }}">{{ $program->code }}</option>
                                @endforeach
                            </select>
                            <i id="clear-program-filter-btn"
                                class="fi fi-rr-caret-down text-gray-500 flex justify-center items-center"></i>

                        </div>

                        <div id="grade_selection_container"
                            class="flex flex-row justify-between items-center rounded-lg border border-[#1e1e1e]/10 bg-gray-100 px-3 py-2 gap-2 hover:bg-gray-200 hover:border-[#1e1e1e]/15 transition-all ease-in-out duration-150 shadow-sm">

                            <select name="grade_selection" id="grade_selection"
                                class="appearance-none bg-transparent text-[14px] font-medium text-gray-700 h-full w-full cursor-pointer">
                                <option value="" disabled selected>Grade</option>
                                <option value="" data-grade="Grade 11">Grade 11</option>
                                <option value="" data-grade="Grade 12">Grade 12</option>
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

            </div>

            <!-- Table Layout Container -->
            <div id="table-layout-container" class="w-full hidden">
                <table id="teacher-sections" class="w-full table-fixed">
                    <thead class="text-[14px]">
                        <tr>
                            <th class="text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                <span class="mr-2 font-medium opacity-60 cursor-pointer">#</span>
                            </th>
                            <th class="w-1/7 text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                <span class="mr-2 font-medium opacity-60 cursor-pointer">Subject</span>
                                <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                            </th>
                            <th class="w-1/7 text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                <span class="mr-2 font-medium opacity-60 cursor-pointer">Section</span>
                                <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                            </th>
                            <th class="w-1/7 text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                <span class="mr-2 font-medium opacity-60 cursor-pointer">Program</span>
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
                                <span class="mr-2 font-medium opacity-60 cursor-pointer">Schedule</span>
                                <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                            </th>
                            <th class="w-1/7 text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                <span class="mr-2 font-medium opacity-60 cursor-pointer">Students</span>
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
    @elseif (Route::is('teacher.advising-sections'))
        <div class="flex flex-col gap-6 p-5">
            @forelse($advisedSections as $section)
                <div class="bg-white rounded-xl shadow-md border border-[#1e1e1e]/10 p-6">
                    <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-4 mb-4">
                        <div>
                            <h2 class="text-[18px] font-bold text-gray-900">{{ $section->name }}</h2>
                            <p class="text-[14px] text-gray-600 mt-1">
                                {{ $section->program->code ?? 'N/A' }} • {{ $section->year_level }} • Room:
                                {{ $section->room ?? 'Not assigned' }}
                            </p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1 bg-blue-50 text-blue-700 text-xs font-medium rounded-full">
                                Students: {{ $section->enrollments->count() }}
                            </span>
                            <a href="{{ route('teacher.section.show', $section->id) }}"
                                class="group relative inline-flex items-center gap-2 bg-blue-100 text-blue-600 font-semibold px-3 py-1.5 rounded-xl hover:bg-blue-500 hover:ring hover:ring-blue-200 hover:text-white transition duration-150">
                                <span class="relative w-4 h-4">
                                    <i class="fi fi-rs-eye flex justify-center items-center absolute inset-0 group-hover:opacity-0 transition-opacity text-[16px]"></i>
                                    <i class="fi fi-ss-eye flex justify-center items-center absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity text-[16px]"></i>
                                </span>
                                View Section
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                        <div class="lg:col-span-2 bg-[#f8f8f8] rounded-xl border border-[#1e1e1e]/10 p-4">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-[16px] font-semibold text-gray-900">Students</h3>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="text-left text-gray-500 border-b border-gray-200">
                                            <th class="px-3 py-2 w-10">#</th>
                                            <th class="px-3 py-2">LRN</th>
                                            <th class="px-3 py-2">Full Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($section->enrollments as $index => $enrollment)
                                            @php
                                                $student = $enrollment->student;
                                            @endphp
                                            <tr class="border-t border-gray-200">
                                                <td class="px-3 py-2">{{ $index + 1 }}</td>
                                                <td class="px-3 py-2">{{ $student->lrn ?? 'N/A' }}</td>
                                                <td class="px-3 py-2">
                                                    {{ $student?->full_name ?? 'N/A' }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="px-3 py-6 text-center text-sm text-gray-500">
                                                    No students enrolled in this section.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="bg-[#f8f8f8] rounded-xl border border-[#1e1e1e]/10 p-4">
                            <h3 class="text-[16px] font-semibold text-gray-900 mb-3">Your Subjects</h3>
                            <div class="space-y-3">
                                @forelse($section->sectionSubjects as $sectionSubject)
                                    <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-sm transition duration-150">
                                        <div class="flex items-start gap-3">
                                            <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <i class="fi fi-sr-book text-gray-600 text-sm"></i>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-semibold text-gray-900 line-clamp-2">
                                                    {{ $sectionSubject->subject->name }}
                                                </p>
                                                <p class="text-xs text-gray-500 mt-1">
                                                    {{ $sectionSubject->subject->category ?? 'General Subject' }}
                                                </p>
                                            </div>
                                        </div>

                                        @if ($sectionSubject->days_of_week || $sectionSubject->start_time)
                                            <div class="mt-3 text-xs text-gray-600">
                                                <div class="flex flex-wrap gap-2">
                                                    @if ($sectionSubject->days_of_week)
                                                        @foreach($sectionSubject->days_of_week as $day)
                                                            <span class="px-2 py-1 bg-blue-50 text-blue-700 text-xs font-medium rounded-md whitespace-nowrap">
                                                                {{ $day }}
                                                            </span>
                                                        @endforeach
                                                    @endif
                                                    @if ($sectionSubject->start_time && $sectionSubject->end_time)
                                                        <span class="px-2 py-1 bg-gray-50 text-gray-700 text-xs font-medium rounded-md whitespace-nowrap">
                                                            {{ $sectionSubject->start_time }} - {{ $sectionSubject->end_time }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @empty
                                    <div class="flex flex-col items-center justify-center text-gray-500 py-8">
                                        <i class="fi fi-sr-folder-open text-3xl mb-3"></i>
                                        <p class="text-sm font-medium">No subjects assigned to you</p>
                                        <p class="text-xs">You are not teaching subjects in this section yet</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl shadow-md border border-[#1e1e1e]/10 p-10 text-center text-gray-500">
                    <i class="fi fi-sr-folder-open text-4xl mb-4"></i>
                    <p class="text-lg font-medium">No advising sections found</p>
                    <p class="text-sm">You are not assigned as an adviser to any section.</p>
                </div>
            @endforelse
        </div>
    @endif
@endsection

@if (Route::is('teacher.dashboard'))
    @push('scripts')
        <script type="module">
            import {
                dropDown
            } from "/js/dropdown.js";
            import {
                clearSearch
            } from "/js/clearSearch.js"
            import {
                initCustomDataTable
            } from "/js/initTable.js";

        let teacherSectionsTable;
        window.selectedGrade = '';
        window.selectedProgram = '';
        window.selectedPageLength = 10;
        window.currentLayout = 'cards'; // 'table' or 'cards'
        window.currentPage = 1;
        window.totalPages = 1;
        window.sectionsData = [];

        document.addEventListener("DOMContentLoaded", function() {

            teacherSectionsTable = initCustomDataTable(
                'teacher-sections',
                '/teacher/subjects',
                [{
                        data: 'index',
                        width: '3%',
                        searchable: true
                    },
                    {
                        data: 'subject',
                        width: '18%'
                    },
                    {
                        data: 'section',
                        width: '15%'
                    },
                    {
                        data: 'program',
                        width: '14%'
                    },
                    {
                        data: 'year_level',
                        width: '12%'
                    },
                    {
                        data: 'room',
                        width: '12%'
                    },
                    {
                        data: 'schedule',
                        width: '17%'
                    },
                    {
                        data: 'students',
                        width: '12%'
                    },
                    {
                        data: 'id',
                        className: 'text-center',
                        width: '15%',
                        render: function(data, type, row) {
                            return `
                            <div class='flex flex-row justify-center items-center opacity-100'>
                                <a href="/teacher/subject/${data}" class="group relative inline-flex items-center gap-2 bg-blue-100 text-blue-500 font-semibold px-3 py-1 rounded-xl hover:bg-blue-500 hover:ring hover:ring-blue-200 hover:text-white transition duration-150 ">
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
                    teacherSectionsTable.search(this.value).draw();
                } else {
                    // For card layout, fetch with search
                    fetchSectionsForCards(1);
                }
            });

            clearSearch('clear-btn', 'myCustomSearch', teacherSectionsTable)

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
                            <p class="text-lg font-medium">No subjects found</p>
                            <p class="text-sm">You are not assigned to any subjects yet</p>
                        </div>
                    `;
                    paginationContainer.innerHTML = '';
                    return;
                }

                // Render cards
                cardsGrid.innerHTML = data.map(subject => `
                    <div class="bg-white rounded-xl shadow-md border border-[#1e1e1e]/10 hover:border-[#199BCF]/60 hover:bg-blue-50 hover:shadow-lg hover:-translate-y-1 transition-all duration-200 p-6">
                        <div class="flex flex-col space-y-4">
                            <!-- Header -->
                            <div class="flex flex-row justify-between items-start">
                                <div class="flex flex-col">
                                    <h3 class="text-lg font-bold text-gray-800 ">${subject.subject}</h3>
                                    <p class="text-sm text-gray-600">${subject.section} • ${subject.program}</p>
                                </div>
                                <div class="flex flex-col items-end">
                                    <span class="text-xs text-gray-500">#${subject.index}</span>
                                </div>
                            </div>

                            <!-- Details -->
                            <div class="space-y-3">
                                <div class="flex flex-row items-center gap-3">
                                    <div class="flex justify-center items-center bg-gray-200 rounded-full w-8 h-8 p-1 flex-shrink-0">
                                        <i class="fi fi-sr-graduation-cap text-gray-700 text-sm"></i>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-xs text-gray-500">Year Level</span>
                                        <span class="text-sm font-medium">${subject.year_level}</span>
                                    </div>
                                </div>
                                
                                <div class="flex flex-row items-center gap-3">
                                    <div class="flex justify-center items-center bg-gray-200 rounded-full w-8 h-8 p-1 flex-shrink-0">
                                        <i class="fi fi-sr-home text-gray-700 text-sm"></i>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-xs text-gray-500">Room</span>
                                        <span class="text-sm font-medium">${subject.room}</span>
                                    </div>
                                </div>
                                
                                <div class="flex flex-row items-center gap-3">
                                    <div class="flex justify-center items-center bg-gray-200 rounded-full w-8 h-8 p-1 flex-shrink-0">
                                        <i class="fi fi-rr-clock text-gray-700 text-sm"></i>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-xs text-gray-500">Schedule</span>
                                        <span class="text-sm font-medium">${subject.schedule}</span>
                                    </div>
                                </div>

                                <div class="flex flex-row items-center gap-3">
                                    <div class="flex justify-center items-center bg-gray-200 rounded-full w-8 h-8 p-1 flex-shrink-0">
                                        <i class="fi fi-sr-users text-gray-700 text-sm"></i>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-xs text-gray-500">Students</span>
                                        <span class="text-sm font-medium">${subject.students}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <div class="pt-2 border-t border-gray-100">
                                <a href="/teacher/subject/${subject.id}" 
                                   class="w-full flex justify-center items-center gap-2 bg-[#199BCF] text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-[#C8A165] transition-colors duration-200">
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
                        `/teacher/subjects?start=${(page - 1) * window.selectedPageLength}&length=${window.selectedPageLength}&grade_filter=${window.selectedGrade}&program_filter=${window.selectedProgram}&search[value]=${document.getElementById('myCustomSearch').value}`
                    );
                    const data = await response.json();

                    window.sectionsData = data.data;
                    window.currentPage = page;
                    window.totalPages = Math.ceil(data.recordsTotal / window.selectedPageLength);

                    renderCards(data.data, page, window.totalPages);
                } catch (error) {
                    console.error('Error fetching sections');
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

                    layoutToggleIcon.className = 'fi fi-sr-apps text-[16px]';
                    layoutToggleText.textContent = 'Cards';

                    // Refresh table
                    teacherSectionsTable.draw();
                }
            });

            let gradeSelection = document.querySelector('#grade_selection');
            let programSelection = document.querySelector('#program_selection');
            let pageLengthSelection = document.querySelector('#page-length-selection');

            let clearGradeFilterBtn = document.querySelector('#clear-grade-filter-btn');
            let clearProgramFilterBtn = document.querySelector('#clear-program-filter-btn');
            let gradeContainer = document.querySelector('#grade_selection_container');
            let programContainer = document.querySelector('#program_selection_container');

            pageLengthSelection.addEventListener('change', (e) => {
                let selectedPageLength = parseInt(e.target.value, 10);
                window.selectedPageLength = selectedPageLength;
                teacherSectionsTable.page.len(selectedPageLength).draw();

                // If in card layout, refresh cards
                if (window.currentLayout === 'cards') {
                    fetchSectionsForCards(1);
                }
            })

            // Program selection handler
            programSelection.addEventListener('change', (e) => {
                let selectedOption = e.target.selectedOptions[0];
                let program = selectedOption.getAttribute('data-program');

                window.selectedProgram = program;
                teacherSectionsTable.draw();

                // If in card layout, refresh cards
                if (window.currentLayout === 'cards') {
                    fetchSectionsForCards(1);
                }

                // Update visual state with neutral colors
                let clearProgramFilterRem = ['text-gray-500', 'fi-rr-caret-down'];
                let clearProgramFilterAdd = ['fi-bs-cross-small', 'cursor-pointer', 'text-gray-900'];
                let programSelectionRem = ['border-[#1e1e1e]/10', 'text-gray-700'];
                let programSelectionAdd = ['text-gray-900'];
                let programContainerRem = ['bg-gray-100'];
                let programContainerAdd = ['bg-gray-200', 'border-gray-400', 'hover:bg-gray-300'];

                clearProgramFilterBtn.classList.remove(...clearProgramFilterRem);
                clearProgramFilterBtn.classList.add(...clearProgramFilterAdd);
                programSelection.classList.remove(...programSelectionRem);
                programSelection.classList.add(...programSelectionAdd);
                programContainer.classList.remove(...programContainerRem);
                programContainer.classList.add(...programContainerAdd);

                handleClearProgramFilter(selectedOption);
            });

            gradeSelection.addEventListener('change', (e) => {
                let selectedOption = e.target.selectedOptions[0];
                let grade = selectedOption.getAttribute('data-grade');

                window.selectedGrade = grade;
                teacherSectionsTable.draw();

                // If in card layout, refresh cards
                if (window.currentLayout === 'cards') {
                    fetchSectionsForCards(1);
                }

                // Update visual state with neutral colors
                let clearGradeFilterRem = ['text-gray-500', 'fi-rr-caret-down'];
                let clearGradeFilterAdd = ['fi-bs-cross-small', 'cursor-pointer', 'text-gray-900'];
                let gradeSelectionRem = ['border-[#1e1e1e]/10', 'text-gray-700'];
                let gradeSelectionAdd = ['text-gray-900'];
                let gradeContainerRem = ['bg-gray-100'];
                let gradeContainerAdd = ['bg-gray-200', 'border-gray-400', 'hover:bg-gray-300'];

                clearGradeFilterBtn.classList.remove(...clearGradeFilterRem);
                clearGradeFilterBtn.classList.add(...clearGradeFilterAdd);
                gradeSelection.classList.remove(...gradeSelectionRem);
                gradeSelection.classList.add(...gradeSelectionAdd);
                gradeContainer.classList.remove(...gradeContainerRem);
                gradeContainer.classList.add(...gradeContainerAdd);

                handleClearGradeFilter(selectedOption)
            })

            function handleClearProgramFilter(selectedOption) {
                clearProgramFilterBtn.addEventListener('click', () => {
                    programContainer.classList.remove('bg-gray-200', 'border-gray-400', 'hover:bg-gray-300');
                    clearProgramFilterBtn.classList.remove('fi-bs-cross-small');

                    clearProgramFilterBtn.classList.add('fi-rr-caret-down');
                    programContainer.classList.add('bg-gray-100');
                    programSelection.classList.remove('text-gray-900');
                    programSelection.classList.add('text-gray-700');
                    clearProgramFilterBtn.classList.remove('text-gray-900');
                    clearProgramFilterBtn.classList.add('text-gray-500');

                    programSelection.selectedIndex = 0;
                    window.selectedProgram = '';
                    teacherSectionsTable.draw();

                    // If in card layout, refresh cards
                    if (window.currentLayout === 'cards') {
                        fetchSectionsForCards(1);
                    }
                });
            }

            function handleClearGradeFilter(selectedOption) {
                clearGradeFilterBtn.addEventListener('click', () => {
                    gradeContainer.classList.remove('bg-gray-200', 'border-gray-400', 'hover:bg-gray-300');
                    clearGradeFilterBtn.classList.remove('fi-bs-cross-small');

                    clearGradeFilterBtn.classList.add('fi-rr-caret-down');
                    gradeContainer.classList.add('bg-gray-100');
                    gradeSelection.classList.remove('text-gray-900');
                    gradeSelection.classList.add('text-gray-700');
                    clearGradeFilterBtn.classList.remove('text-gray-900');
                    clearGradeFilterBtn.classList.add('text-gray-500');

                    gradeSelection.selectedIndex = 0;
                    window.selectedGrade = '';
                    teacherSectionsTable.draw();

                    // If in card layout, refresh cards
                    if (window.currentLayout === 'cards') {
                        fetchSectionsForCards(1);
                    }
                });
            }

            window.onload = function() {
                gradeSelection.selectedIndex = 0;
                programSelection.selectedIndex = 0;
                pageLengthSelection.selectedIndex = 0;

                // Initialize with cards layout (default)
                fetchSectionsForCards(1);
            }

            dropDown('dropdown_btn', 'dropdown_selection');

        });
        </script>
    @endpush
@endif
