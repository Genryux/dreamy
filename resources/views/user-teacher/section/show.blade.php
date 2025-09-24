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
                <a href="{{ route('teacher.dashboard') }}" class="block transition-colors hover:text-gray-900">Teacher Dashboard</a>
            </li>
            <li class="rtl:rotate-180">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
            </li>
            <li>
                <span class="block text-gray-900">{{ $section->program->name }}</span>
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
                    <p class="text-[14px] text-gray-600 mt-1">Subjects assigned to this section</p>
                </div>
            </div>

            <!-- Subjects Grid -->
            <div class="space-y-4">
                @forelse($section->sectionSubjects as $sectionSubject)
                    <div
                        class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-200 p-4 hover:shadow-md transition duration-200">
                        <div class="flex flex-row justify-between items-start mb-3">
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-[#1A3165]">{{ $sectionSubject->subject->name }}</h3>
                                <p class="text-sm text-gray-600">{{ $sectionSubject->subject->category ?? 'General' }}</p>
                            </div>
                            <div class="flex flex-col items-end">
                                <span
                                    class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">{{ $sectionSubject->subject->year_level ?? $section->year_level }}</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-3">
                            <div class="flex flex-row items-center gap-2">
                                <i class="fi fi-sr-user text-[#1A3165] text-sm"></i>
                                <div class="flex flex-col">
                                    <span class="text-xs text-gray-500">Teacher</span>
                                    <span
                                        class="text-sm font-medium">{{ $sectionSubject->teacher->first_name . ' ' . $sectionSubject->teacher->last_name  ?? 'Not assigned' }}</span>
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

                        @if ($sectionSubject->days_of_week || $sectionSubject->start_time)
                            <div class="flex flex-row items-center gap-2 mb-3">
                                <i class="fi fi-sr-clock text-[#1A3165] text-sm"></i>
                                <div class="flex flex-col">
                                    <span class="text-xs text-gray-500">Schedule</span>
                                    <span class="text-sm font-medium">
                                        @if ($sectionSubject->days_of_week)
                                            {{ implode(', ', $sectionSubject->days_of_week) }}
                                        @endif
                                        @if ($sectionSubject->start_time && $sectionSubject->end_time)
                                            • {{ $sectionSubject->start_time }} - {{ $sectionSubject->end_time }}
                                        @endif
                                    </span>
                                </div>
                            </div>
                        @endif

                        <div class="flex flex-row justify-between items-center pt-2 border-t border-blue-200">
                            <div class="flex flex-row items-center gap-2">
                                <i class="fi fi-sr-users text-[#1A3165] text-sm"></i>
                                <span class="text-sm text-gray-600">{{ $sectionSubject->students()->count() }}
                                    enrolled</span>
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
