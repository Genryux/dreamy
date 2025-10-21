@extends('layouts.admin')
@section('breadcrumbs')
    <nav aria-label="Breadcrumb" class="flex flex-row justify-between items-center mb-2 mt-2">
        <ol class="flex items-center gap-1 text-sm text-gray-700">
            <li class="rtl:rotate-180 border border-gray-300 bg-gray-100 p-2 rounded-lg mr-1">
                <a href="/tracks" class="block transition-colors hover:text-gray-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 rotate-180" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                </a>
            </li>
            <li>
                <a href="/teacher/dashboard" class="block transition-colors hover:text-gray-900"> Dashboard </a>
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

@section('header')
    <div class="flex flex-row justify-between items-start text-start px-[14px] py-2">
        <div>
            <h1 class="text-[24px] font-black text-gray-900">Section Details</h1>
            <p class="text-[14px] text-gray-600 mt-1">{{ $section->program->name }} • {{ $section->year_level }}</p>
        </div>
    </div>
@endsection

@section('stat')
    <div class="flex justify-center items-center">
        <div
            class="flex flex-col justify-center items-center flex-grow px-10 pb-10 pt-2 bg-gradient-to-br from-[#199BCF] to-[#1A3165] rounded-xl shadow-[#199BCF]/30 shadow-xl gap-2 text-white">
            
            <div class="flex flex-row items-center justify-between w-full gap-4 py-2 rounded-lg">
                <div class="flex flex-col items-start justify-end gap-2 pt-4">
                    <h1 class="text-[40px] font-black" id="section_name">{{ $section->name }}</h1>
                    <p class="text-[16px] text-white/60">{{ $section->program->name }} • {{ $section->year_level }}</p>
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
                    class="flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-8 py-6 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-150">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-rr-graduation-cap flex justify-center items-center"></i>
                        <p class="text-[14px]">Year Level</p>
                    </div>
                    <p class="font-bold text-[24px]">{{ $section->year_level }}</p>
                    <p class="text-[12px] truncate text-gray-300">Academic level</p>
                </div>

                <div
                    class="flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-8 py-6 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-300">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-rr-school flex flex-row justify-center items-center"></i>
                        <p class="text-[14px]">Program</p>
                    </div>
                    <p class="font-bold text-[24px]">{{ $section->program->code }}</p>
                    <p class="text-[12px] truncate text-gray-300">{{ $section->program->name }}</p>
                </div>

                <div
                    class="flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-8 py-6 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-300">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-rr-home flex justify-center items-center"></i>
                        <p class="text-[14px]">Room</p>
                    </div>
                    <p class="font-bold text-[24px]" id="section_room">{{ $section->room ?? 'Not assigned' }}</p>
                    <p class="text-[12px] truncate text-gray-300">Classroom location</p>
                </div>

                <div
                    class="flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-8 py-6 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-300">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-rr-user-tie flex justify-center items-center"></i>
                        <p class="text-[14px]">Adviser</p>
                    </div>
                    <p class="font-bold text-[24px]">
                        @if($section->teacher && $section->teacher->user)
                            {{ $section->teacher->user->last_name }}, {{ $section->teacher->user->first_name }}
                        @elseif($section->teacher)
                            {{ $section->teacher->first_name }} {{ $section->teacher->last_name }}
                        @else
                            Not assigned
                        @endif
                    </p>
                    <p class="text-[12px] truncate text-gray-300">Section adviser</p>
                </div>
            </div>

            <div class="flex flex-row justify-center items-center w-full gap-4 mt-4">
                <div
                    class="flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-8 py-6 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-300">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-rr-book flex justify-center items-center"></i>
                        <p class="text-[14px]">Total Subjects</p>
                    </div>
                    <p class="font-bold text-[24px]">{{ $section->sectionSubjects->count() }}</p>
                    <p class="text-[12px] truncate text-gray-300">Subjects in this section</p>
                </div>

                @if(!$isAdviser)
                <div
                    class="flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-8 py-6 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-300">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-rr-chalkboard-teacher flex justify-center items-center"></i>
                        <p class="text-[14px]">Your Subjects</p>
                    </div>
                    <p class="font-bold text-[24px]">{{ $section->sectionSubjects->where('teacher_id', $teacher->id)->count() }}</p>
                    <p class="text-[12px] truncate text-gray-300">Subjects you teach</p>
                </div>
                @endif

                <div
                    class="flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-8 py-6 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-300">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-rr-users flex justify-center items-center"></i>
                        <p class="text-[14px]">Enrolled Students</p>
                    </div>
                    <p class="font-bold text-[24px]">{{ $section->enrollments->count() }}</p>
                    <p class="text-[12px] truncate text-gray-300">Currently enrolled</p>
                </div>

                <div
                    class="flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-8 py-6 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-300">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-rr-employee-man-alt flex justify-center items-center"></i>
                        <p class="text-[14px]">Your Role</p>
                    </div>
                    <p class="font-bold text-[24px]">{{ $isAdviser ? 'Adviser' : 'Teacher' }}</p>
                    <p class="text-[12px] truncate text-gray-300">{{ $isAdviser ? 'Section adviser' : 'Subject teacher' }}</p>
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
                    <div
                        class="flex flex-row justify-between items-center rounded-lg border border-[#1e1e1e]/10 bg-gray-100 px-3 py-2 gap-2 hover:bg-gray-200 hover:border-[#1e1e1e]/15 transition-all ease-in-out duration-150 shadow-sm">
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
                    @if($isAdviser)
                        <p class="text-[14px] text-gray-600 mt-1">All subjects assigned to this section</p>
                    @else
                        <p class="text-[14px] text-gray-600 mt-1">Subjects you are teaching in this section</p>
                    @endif
                </div>
            </div>

            <!-- Subjects Grid -->
            <div class="space-y-3">
                @if($isAdviser)
                    {{-- Show all subjects when teacher is the adviser --}}
                    @forelse($section->sectionSubjects as $sectionSubject)
                        <div class="group bg-white border border-gray-200 rounded-xl p-5 hover:shadow-lg hover:border-gray-300 transition-all duration-300">
                            <!-- Header -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start gap-3 mb-2">
                                        <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <i class="fi fi-sr-book text-gray-600 text-lg"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-lg font-semibold text-gray-900 line-clamp-2 leading-tight">{{ $sectionSubject->subject->name }}</h3>
                                            <p class="text-sm text-gray-500 mt-1">{{ $sectionSubject->subject->category ?? 'General Subject' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Details Grid -->
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-gray-50 rounded-lg flex items-center justify-center">
                                        <i class="fi fi-sr-user text-gray-500 text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Instructor</p>
                                        <p class="text-sm font-semibold text-gray-900">
                                            {{ $sectionSubject->teacher ? $sectionSubject->teacher->getFullNameAttribute() : 'Not assigned' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-gray-50 rounded-lg flex items-center justify-center">
                                        <i class="fi fi-sr-home text-gray-500 text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Room</p>
                                        <p class="text-sm font-semibold text-gray-900">{{ $sectionSubject->room ?? 'Not assigned' }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Schedule -->
                            @if ($sectionSubject->days_of_week || $sectionSubject->start_time)
                                <div class="flex items-start gap-3 mb-4">
                                    <div class="w-8 h-8 bg-gray-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class="fi fi-sr-clock text-gray-500 text-sm"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Schedule</p>
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
                                </div>
                            @endif

                            <!-- Footer -->
                            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 bg-gray-50 rounded-lg flex items-center justify-center">
                                        <i class="fi fi-sr-users text-gray-500 text-xs"></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">{{ $sectionSubject->students()->count() }} students enrolled</span>
                                </div>

                            </div>
                        </div>
                    @empty
                        <div class="text-center py-16">
                            <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <i class="fi fi-sr-book text-gray-400 text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">No subjects assigned</h3>
                            <p class="text-sm text-gray-500">Add subjects to this section to get started</p>
                        </div>
                    @endforelse
                @else
                    {{-- Show only subjects taught by this teacher when not the adviser --}}
                    @forelse($section->sectionSubjects->where('teacher_id', $teacher->id) as $sectionSubject)
                        <div class="group bg-white border border-gray-200 rounded-xl p-5 hover:shadow-lg hover:border-gray-300 transition-all duration-300">
                            <!-- Header -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start gap-3 mb-2">
                                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <i class="fi fi-sr-book text-blue-600 text-lg"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-lg font-semibold text-gray-900 line-clamp-2 leading-tight">{{ $sectionSubject->subject->name }}</h3>
                                            <p class="text-sm text-gray-500 mt-1">{{ $sectionSubject->subject->category ?? 'General Subject' }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 flex-shrink-0 ml-3">
                                    <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded-full whitespace-nowrap">
                                        Your Subject
                                    </span>
                                </div>
                            </div>

                            <!-- Details Grid -->
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center">
                                        <i class="fi fi-sr-user text-blue-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Instructor</p>
                                        <p class="text-sm font-semibold text-blue-700">
                                            {{ $sectionSubject->teacher ? $sectionSubject->teacher->getFullNameAttribute() : 'Not assigned' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center">
                                        <i class="fi fi-sr-home text-blue-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Room</p>
                                        <p class="text-sm font-semibold text-gray-900">{{ $sectionSubject->room ?? 'Not assigned' }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Schedule -->
                            @if ($sectionSubject->days_of_week || $sectionSubject->start_time)
                                <div class="flex items-start gap-3 mb-4">
                                    <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class="fi fi-sr-clock text-blue-600 text-sm"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Schedule</p>
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
                                </div>
                            @endif

                            <!-- Footer -->
                            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 bg-blue-50 rounded-lg flex items-center justify-center">
                                        <i class="fi fi-sr-users text-blue-600 text-xs"></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">{{ $sectionSubject->students()->count() }} students enrolled</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 bg-blue-400 rounded-full"></span>
                                    <span class="text-xs font-medium text-gray-500">Your Class</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-16">
                            <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <i class="fi fi-sr-book text-gray-400 text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">No subjects assigned to you</h3>
                            <p class="text-sm text-gray-500">You are not teaching any subjects in this section</p>
                        </div>
                    @endforelse
                @endif
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endpush

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
            showLoader,
            hideLoader
        } from "/js/loader.js";
        import {
            initCustomDataTable
        } from "/js/initTable.js";

        let table1;
        window.selectedGrade = '';
        window.selectedProgram = '';
        window.selectedGender = '';
        window.selectedPageLength = 10;

        let sectionId = @json($section->id);

        document.addEventListener("DOMContentLoaded", function() {

            let studentCount = document.querySelector('#studentCount');
            let sectionName = document.querySelector('#section_name');
            let sectionRoom = document.querySelector('#section_room');

            // Initialize DataTable using the clean component
            table1 = initCustomDataTable(
                'sections',
                `/getStudents/${sectionId}`,
                [{
                        data: 'index'
                    },
                    {
                        data: 'lrn'
                    },
                    {
                        data: 'full_name'
                    },
                    {
                        data: 'age'
                    },
                    {
                        data: 'gender'
                    },
                    {
                        data: 'id',
                        render: function(data, type, row) {
                            return `
                                <div class='flex flex-row justify-center items-center gap-1'>
                                    <a href='/student/${data}' class="px-2 py-1 text-xs font-medium text-blue-600 bg-blue-100 rounded hover:bg-blue-200 transition duration-150">
                                        <i class="fi fi-rr-eye text-xs"></i>
                                    </a>
                            </div>
                            `;
                        },
                        orderable: false,
                        searchable: false
                    }
                ],
                [
                    [0, 'asc']
                ],
                'myCustomSearch',
                [{
                        width: '5%',
                        targets: 0,
                        className: 'text-center'
                    },
                    {
                        width: '18%',
                        targets: 1
                    },
                    {
                        width: '30%',
                        targets: 2
                    },
                    {
                        width: '15%',
                        targets: 3,
                        className: 'text-center'
                    },
                    {
                        width: '15%',
                        targets: 4,
                        className: 'text-center'
                    },
                    {
                        width: '20%',
                        targets: 5,
                        className: 'text-center'
                    }
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

            // Initialize page
            window.onload = function() {
                pageLengthSelection.selectedIndex = 0;
                genderSelection.selectedIndex = 0;
            }
        });
    </script>
@endpush
