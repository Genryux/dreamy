@extends('layouts.admin', ['title' => 'Students'])

{{-- @section('skeleton')
    <div id="skeleton" class="fixed inset-0 bg-white flex flex-col justify-center items-center z-50">
        <!-- Example skeleton shapes -->
        <div class="h-8 w-1/3 bg-gray-300 rounded mb-4 animate-pulse"></div>
        <div class="h-6 w-1/2 bg-gray-300 rounded mb-2 animate-pulse"></div>
        <div class="h-6 w-2/3 bg-gray-300 rounded mb-2 animate-pulse"></div>
        <div class="h-6 w-1/4 bg-gray-300 rounded animate-pulse"></div>
    </div>
@endsection --}}

@section('modal')
    <x-modal modal_id="import-modal" modal_name="Import Students" close_btn_id="import-modal-close-btn"
        modal_container_id="modal-container-1">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-progress-upload flex justify-center items-center '></i>

        </x-slot>

        <form enctype="multipart/form-data" id="import-form" class="p-6">
            @csrf
            <label for="fileInput" id="fileInputLabel"
                class="flex flex-col items-center justify-center w-full border-2 border-[#199BCF]/60 border-dashed rounded-lg bg-[#E7F0FD] hover:bg-blue-100 cursor-pointer cursor-not-allowed select-none transition duration-150">

                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                    <svg class="w-8 h-8 mb-4 text-[#199BCF]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 20 16">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                    </svg>
                    <p class="mb-2 text-sm text-[#0f111c]/80"><span class="font-semibold">Choose files to
                            upload</span></p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Supported Formats: .xlsx, .xls, .csv</p>
                </div>
                <span
                    class="self-center flex flex-row justify-center items-center bg-[#199BCF] py-2 px-3 rounded-xl text-[16px] font-semibold gap-2 text-white hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate mb-2">Choose
                    Files</span>

                <input type="file" id="fileInput" name="file" class="hidden" accept=".xlsx,.xls,.csv" required>
                <span id="fileName" class="text-gray-500 italic my-2">No file chosen</span>
            </label>
        </form>

        <x-slot name="modal_info">
            <i class="fi fi-bs-download flex justify-center items-center"></i>
            <a href="{{ asset('templates/Officially_Enrolled_Students_Import_Template.xlsx') }}" download>Click here to
                download the
                template</a>
        </x-slot>

        <x-slot name="modal_buttons">
            <button id="cancel-btn"
                class="bg-gray-50 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-100 hover:ring hover:ring-gray-200 transition duration-150">
                Cancel
            </button>
            {{-- This button will acts as the submit button --}}
            <button type="submit" form="import-form" name="action" value="verify"
                class="self-center flex flex-row justify-center items-center bg-[#199BCF] py-2 px-3 rounded-xl text-[16px] font-semibold gap-2 text-white hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                Import
            </button>
        </x-slot>

    </x-modal>
@endsection

@section('header')
    <div class="flex flex-row justify-between items-center px-[14px] py-2">
        <div class="flex flex-col justify-center items-start text-start">
            <h1 class="text-[20px] font-black">Officially Enrolled Students</h1>
            <p class="text-[14px]  text-gray-900/60">Manage list and records of officially enrolled students.
            </p>
        </div>
        <div class="flex items-center">
            <x-term-selector />
        </div>
    </div>
@endsection

@section('stat')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 items-start">
        <div class="flex flex-col bg-[#f8f8f8] rounded-xl shadow-md border border-[#1e1e1e]/10 p-6 h-fit">
            <div class="flex flex-col justify-center items-start space-y-3">
                <div class="flex flex-row justify-between items-center w-full">
                    <div class="flex flex-row justify-center items-center gap-2">
                        <div class="bg-gray-200 flex justify-center items-center p-1 rounded-lg">
                            <i class="fi fi-rr-chart-simple flex justify-center items-center text-[16px] text-gray-500"></i>
                        </div>
                        <span class="font-medium text-gray-700 text-[16px]">Total</span>
                    </div>
                    <div class="flex flex-row justify-center items-center">
                        <i class="fi fi-rs-info flex justify-center items-center text-[16px] text-gray-500"
                            title="tabgubano tabgubanotabgubanotabgubanotabgubano tabgubano"></i>
                    </div>
                </div>
                {{-- chart --}}
                <div class="relative w-[180px] h-[180px] self-center">
                    <canvas id="total_chart" width="180" height="180" class="absolute inset-0"></canvas>
                    <div id="total_chart_empty"
                        class="absolute inset-0 flex flex-col justify-center items-center text-center opacity-0 pointer-events-none">
                        <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center mb-3">
                            <i class="fi fi-rr-chart-simple text-gray-400 text-xl"></i>
                        </div>
                        <p class="text-gray-400 text-sm font-medium">No data available</p>
                        <p class="text-gray-300 text-xs mt-1">No enrollment data to display</p>
                    </div>
                </div>

                <div class="flex flex-row justify-between items-center w-full" id="total-legend">
                    <div class="flex flex-col" id="total-labels">
                        <div class="flex items-center gap-2 mb-1">
                            <div class="w-3 h-3 rounded-full bg-green-500"></div>
                            <span class="font-medium text-gray-700 text-sm">Enrolled</span>
                        </div>
                        <div class="flex items-center gap-2 mb-1">
                            <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                            <span class="font-medium text-gray-700 text-sm">Pending</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full bg-gray-500"></div>
                            <span class="font-medium text-gray-700 text-sm">Total</span>
                        </div>
                    </div>
                    <div class="flex flex-col" id="total-counts">
                        <div class="text-right mb-1">
                            <div id="enrolled-count" class="font-bold text-sm" style="color: #10B981">-</div>
                            <div id="enrolled-percentage" class="font-medium text-xs" style="color: #10B981; opacity: 0.7">-
                            </div>
                        </div>
                        <div class="text-right mb-1">
                            <div id="pending-count" class="font-bold text-sm" style="color: #F59E0B">-</div>
                            <div id="pending-percentage" class="font-medium text-xs" style="color: #F59E0B; opacity: 0.7">-
                            </div>
                        </div>
                        <div class="text-right">
                            <div id="total-count" class="font-bold text-sm" style="color: #6B7280">-</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="flex flex-col bg-[#f8f8f8] rounded-xl shadow-md border border-[#1e1e1e]/10 p-6 h-fit">
            <div class="flex flex-col justify-center items-start space-y-3">
                <div class="flex flex-row justify-between items-center w-full">
                    <div class="flex flex-row justify-center items-center gap-2">
                        <div class="bg-gray-200 flex justify-center items-center p-1 rounded-lg">
                            <i
                                class="fi fi-rr-graduation-cap flex justify-center items-center text-[16px] text-gray-500"></i>
                        </div>
                        <span class="font-medium text-gray-700 text-[16px]">By Grade Level</span>
                    </div>
                    <div class="flex flex-row justify-center items-center"
                        title="Total number of enrolled students per grade level">
                        <i class="fi fi-rs-info flex justify-center items-center text-[18px] text-gray-500"></i>
                    </div>
                </div>
                {{-- chart --}}
                <div class="relative w-[180px] h-[180px] self-center">
                    <canvas id="grade_level_chart" width="180" height="180" class="absolute inset-0"></canvas>
                    <div id="grade_level_chart_empty"
                        class="absolute inset-0 flex flex-col justify-center items-center text-center opacity-0 pointer-events-none">
                        <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center mb-3">
                            <i class="fi fi-rr-graduation-cap text-gray-400 text-xl"></i>
                        </div>
                        <p class="text-gray-400 text-sm font-medium">No data available</p>
                        <p class="text-gray-300 text-xs mt-1">No grade level data to display</p>
                    </div>
                </div>

                <div class="flex flex-row justify-between items-center w-full" id="grade-level-legend">
                    <div class="flex flex-col" id="grade-level-labels">
                        <!-- Grade level labels will be populated dynamically -->
                    </div>
                    <div class="flex flex-col" id="grade-level-counts">
                        <!-- Grade level counts will be populated dynamically -->
                    </div>
                </div>
            </div>
        </div>
        <div class="flex flex-col bg-[#f8f8f8] rounded-xl shadow-md border border-[#1e1e1e]/10 p-6 h-fit">
            <div class="flex flex-col justify-center items-start space-y-3">
                <div class="flex flex-row justify-between items-center w-full">
                    <div class="flex flex-row justify-center items-center gap-2">
                        <div class="bg-gray-200 flex justify-center items-center p-1 rounded-lg">
                            <i
                                class="fi fi-rr-book-bookmark flex justify-center items-center text-[16px] text-gray-500"></i>
                        </div>
                        <span class="font-medium text-gray-700 text-[16px]">By Program</span>
                    </div>
                    <div class="flex flex-row justify-center items-center">
                        <i class="fi fi-rs-info flex justify-center items-center text-[18px] text-gray-500"
                            title="tabgubano tabgubanotabgubanotabgubanotabgubano tabgubano"></i>
                    </div>
                </div>
                {{-- chart --}}
                <div class="relative w-[180px] h-[180px] self-center">
                    <canvas id="program_chart" width="180" height="180" class="absolute inset-0"></canvas>
                    <div id="program_chart_empty"
                        class="absolute inset-0 flex flex-col justify-center items-center text-center opacity-0 pointer-events-none">
                        <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center mb-3">
                            <i class="fi fi-rr-book-bookmark text-gray-400 text-xl"></i>
                        </div>
                        <p class="text-gray-400 text-sm font-medium">No data available</p>
                        <p class="text-gray-300 text-xs mt-1">No program data to display</p>
                    </div>
                </div>
                <div class="flex flex-row justify-between items-center w-full" id="program-legend">
                    <div class="flex flex-col" id="program-labels">
                        <!-- Program labels will be populated dynamically -->
                    </div>
                    <div class="flex flex-col" id="program-counts">
                        <!-- Program counts will be populated dynamically -->
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('content')
    <x-alert />

    <div class="flex flex-row justify-center items-start gap-4">
        <div
            class="flex flex-col justify-start items-center flex-grow p-5 space-y-4 bg-[#f8f8f8] rounded-xl shadow-md border border-[#1e1e1e]/10 w-[40%]">
            <div class="flex flex-row justify-between items-center w-full h-full py-2">

                <div class="flex flex-row justify-start w-[90%] items-center gap-4">

                    <label for="myCustomSearch"
                        class="flex flex-row justify-end items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-2 gap-2 w-[30%] hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 focus-within:shadow-xl">
                        <i class="fi fi-rs-search flex justify-center items-center text-[#1e1e1e]/60 text-[16px]"></i>
                        <input type="search" name="" id="myCustomSearch"
                            class="my-custom-search bg-transparent outline-none text-[14px] w-full peer"
                            placeholder="Search by lrn, name, grade level, etc.">
                        <button id="clear-btn"
                            class="clear-btn flex justify-center items-center peer-placeholder-shown:hidden peer-not-placeholder-shown:block">
                            <i class="fi fi-rs-cross-small text-[18px] flex justify-center items-center"></i>
                        </button>
                    </label>

                    <!-- Status Filter Buttons -->
                    <div class="flex flex-row justify-start items-center gap-2">
                        <button id="status-all"
                            class="status-filter-btn px-3 py-2 bg-[#199BCF] text-white rounded-lg hover:bg-[#33ACD6] transition duration-150 text-[14px] font-medium">
                            All Students
                        </button>
                        <button id="status-enrolled"
                            class="status-filter-btn px-3 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition duration-150 text-[14px] font-medium">
                            Enrolled
                        </button>
                        <button id="status-pending"
                            class="status-filter-btn px-3 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition duration-150 text-[14px] font-medium">
                            Pending
                        </button>
                    </div>

                    <div class="flex flex-row justify-end items-center w-auto gap-2">
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
                                    <option value="{{ $program->id }}" data-id="{{ $program->id }}">{{ $program->code }}
                                    </option>
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
                                <option value="" data-putanginamo="Grade 11">Grade 11</option>
                                <option value="" data-putanginamo="Grade 12">Grade 12</option>
                            </select>
                            <i id="clear-grade-filter-btn"
                                class="fi fi-rr-caret-down text-gray-500 flex justify-center items-center"></i>

                        </div>


                    </div>
                </div>


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
                    </div>

                </div>
            </div>

            <div class="w-full">
                <table id="enrolledStudents" class="w-full table-fixed">
                    <thead class="text-[14px]">
                        <tr>
                            <th class="text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                <span class="mr-2 font-medium opacity-60 cursor-pointer">#</span>
                            </th>
                            <th class="w-1/7 text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                <span class="mr-2 font-medium opacity-60 cursor-pointer">LRN</span>
                                <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                            </th>
                            <th class="w-1/7 text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                <span class="mr-2 font-medium opacity-60 cursor-pointer">Full Name</span>
                                <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                            </th>
                            <th class="w-1/7 text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                <span class="mr-2 font-medium opacity-60 cursor-pointer">Grade Level</span>
                                <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                            </th>
                            <th class="w-1/7 text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                <span class="mr-2 font-medium opacity-60 cursor-pointer">Program</span>
                                <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                            </th>
                            <th class="w-1/7 text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                <span class="mr-2 font-medium opacity-60 cursor-pointer">Academic Status</span>
                                <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                            </th>
                            <th class="w-1/7 text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                <span class="mr-2 font-medium opacity-60 cursor-pointer">Student Status</span>
                                <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                            </th>
                            <th class="w-1/7 text-center bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                <span class="mr-2 font-medium opacity-60 select-none">Enrollment Status</span>
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
        </div>

    </div>
@endsection

@push('scripts')
    <script type="module">
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
            showLoader,
            hideLoader
        } from "{{ asset('js/loader.js') }}";

        let table1;
        let selectedGrade = '';
        let selectedProgram = '';
        let selectedPageLength = '';
        let selectedStatusFilter = ''; // All students by default


        // Function to load enrollment statistics
        function loadEnrollmentStats() {
            const urlParams = new URLSearchParams(window.location.search);
            const termId = urlParams.get('term_id');

            let url = '/enrollment-stats';
            if (termId) {
                url += `?term_id=${termId}`;
            }

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const enrolled = data.enrolled || 0;
                    const pending = data.pending || 0;
                    const total = data.total || 0;

                    // Update counts with null checks
                    const enrolledCountEl = document.getElementById('enrolled-count');
                    const pendingCountEl = document.getElementById('pending-count');
                    const totalCountEl = document.getElementById('total-count');

                    if (enrolledCountEl) enrolledCountEl.textContent = enrolled;
                    if (pendingCountEl) pendingCountEl.textContent = pending;
                    if (totalCountEl) totalCountEl.textContent = total;

                    // Update percentages with null checks
                    const enrolledPercentageEl = document.getElementById('enrolled-percentage');
                    const pendingPercentageEl = document.getElementById('pending-percentage');

                    if (enrolledPercentageEl || pendingPercentageEl) {
                        const enrolledPercentage = total > 0 ? ((enrolled / total) * 100).toFixed(1) : 0;
                        const pendingPercentage = total > 0 ? ((pending / total) * 100).toFixed(1) : 0;

                        if (enrolledPercentageEl) enrolledPercentageEl.textContent = `${enrolledPercentage}%`;
                        if (pendingPercentageEl) pendingPercentageEl.textContent = `${pendingPercentage}%`;
                    }

                    // Update the chart and legend
                    updateEnrollmentChart(enrolled, pending);
                })
                .catch(error => {
                    console.error('Error loading enrollment stats');
                });
        }

        // Function to load enrollment analytics for charts
        function loadEnrollmentAnalytics() {
            const urlParams = new URLSearchParams(window.location.search);
            const termId = urlParams.get('term_id');

            let url = '/enrollment-analytics';
            if (termId) {
                url += `?term_id=${termId}`;
            }

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateProgramChart(data.data.programs);
                        updateGradeLevelChart(data.data.grade_levels);
                    } else {
                        // Handle no academic term gracefully - show empty states
                        if (data.message && data.message.includes('No academic term found')) {
                            // Show empty states for all charts when no academic term is found
                            updateProgramChart([]);
                            updateGradeLevelChart([]);
                        } else {
                            // Only log actual errors, not expected states
                            console.error('Error loading analytics:', data.message);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error loading enrollment analytics');
                });
        }

        // Function to update the enrollment chart with smooth animation
        function updateEnrollmentChart(enrolled, pending) {
            const total = enrolled + pending;
            const emptyState = document.getElementById('total_chart_empty');

            if (typeof window.totalChart !== 'undefined' && window.totalChart) {
                if (total === 0) {
                    // Show empty state
                    if (emptyState) {
                        emptyState.classList.remove('opacity-0', 'pointer-events-none');
                        emptyState.classList.add('opacity-100');
                    }
                    window.totalChart.data.datasets[0].data = [0, 0];
                } else {
                    // Hide empty state and show chart
                    if (emptyState) {
                        emptyState.classList.add('opacity-0', 'pointer-events-none');
                        emptyState.classList.remove('opacity-100');
                    }
                    window.totalChart.data.datasets[0].data = [enrolled, pending];
                }
                window.totalChart.update('active'); // Use 'active' animation for smooth transitions

                // Update the total legend
                updateTotalLegend(enrolled, pending, total);
            }
        }

        // Function to update total legend
        function updateTotalLegend(enrolled, pending, total) {
            const labelsDiv = document.getElementById('total-labels');
            const countsDiv = document.getElementById('total-counts');

            if (labelsDiv && countsDiv) {
                if (total === 0) {
                    // Show empty state
                    labelsDiv.innerHTML = '<span class="font-regular text-gray-400 text-sm">No data</span>';
                    countsDiv.innerHTML = '<span class="font-bold text-gray-400 text-sm">0</span>';
                } else {
                    // Show normal legend
                    labelsDiv.innerHTML = `
                        <div class="flex items-center gap-2 mb-1">
                            <div class="w-3 h-3 rounded-full bg-green-500"></div>
                            <span class="font-medium text-gray-700 text-sm">Enrolled</span>
                        </div>
                        <div class="flex items-center gap-2 mb-1">
                            <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                            <span class="font-medium text-gray-700 text-sm">Pending</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full bg-gray-500"></div>
                            <span class="font-medium text-gray-700 text-sm">Total</span>
                        </div>
                    `;

                    const enrolledPercentage = total > 0 ? ((enrolled / total) * 100).toFixed(1) : 0;
                    const pendingPercentage = total > 0 ? ((pending / total) * 100).toFixed(1) : 0;

                    countsDiv.innerHTML = `
                        <div class="text-right mb-1">
                            <div class="font-bold text-sm" style="color: #10B981">${enrolled}</div>
                            <div class="font-medium text-xs" style="color: #10B981; opacity: 0.7">${enrolledPercentage}%</div>
                        </div>
                        <div class="text-right mb-1">
                            <div class="font-bold text-sm" style="color: #F59E0B">${pending}</div>
                            <div class="font-medium text-xs" style="color: #F59E0B; opacity: 0.7">${pendingPercentage}%</div>
                        </div>
                        <div class="text-right">
                            <div class="font-bold text-sm" style="color: #6B7280">${total}</div>
                        </div>
                    `;
                }
            }
        }

        // Function to update program chart with dynamic data
        function updateProgramChart(programData) {
            const emptyState = document.getElementById('program_chart_empty');

            if (typeof window.programChart !== 'undefined' && window.programChart) {
                if (!programData || programData.length === 0 || programData.every(item => item.count === 0)) {
                    // Show empty state
                    if (emptyState) {
                        emptyState.classList.remove('opacity-0', 'pointer-events-none');
                        emptyState.classList.add('opacity-100');
                    }
                    window.programChart.data.labels = [];
                    window.programChart.data.datasets[0].data = [];
                    window.programChart.data.datasets[0].backgroundColor = [];
                } else {
                    // Hide empty state and show chart
                    if (emptyState) {
                        emptyState.classList.add('opacity-0', 'pointer-events-none');
                        emptyState.classList.remove('opacity-100');
                    }

                    const labels = programData.map(item => item.code);
                    const data = programData.map(item => item.count);

                    // Generate colors dynamically
                    const colors = generateColors(programData.length);

                    window.programChart.data.labels = labels;
                    window.programChart.data.datasets[0].data = data;
                    window.programChart.data.datasets[0].backgroundColor = colors;
                }
                window.programChart.update('active');

                // Update the program legend
                updateProgramLegend(programData);
            }
        }

        // Function to update grade level chart with dynamic data
        function updateGradeLevelChart(gradeData) {
            const emptyState = document.getElementById('grade_level_chart_empty');

            if (typeof window.gradeLevelChart !== 'undefined' && window.gradeLevelChart) {
                if (!gradeData || gradeData.length === 0 || gradeData.every(item => item.count === 0)) {
                    // Show empty state
                    if (emptyState) {
                        emptyState.classList.remove('opacity-0', 'pointer-events-none');
                        emptyState.classList.add('opacity-100');
                    }
                    window.gradeLevelChart.data.labels = [];
                    window.gradeLevelChart.data.datasets[0].data = [];
                    window.gradeLevelChart.data.datasets[0].backgroundColor = [];
                } else {
                    // Hide empty state and show chart
                    if (emptyState) {
                        emptyState.classList.add('opacity-0', 'pointer-events-none');
                        emptyState.classList.remove('opacity-100');
                    }

                    const labels = gradeData.map(item => item.grade_level);
                    const data = gradeData.map(item => item.count);

                    // Generate colors dynamically
                    const colors = generateColors(gradeData.length);

                    window.gradeLevelChart.data.labels = labels;
                    window.gradeLevelChart.data.datasets[0].data = data;
                    window.gradeLevelChart.data.datasets[0].backgroundColor = colors;
                }
                window.gradeLevelChart.update('active');

                // Update the grade level legend
                updateGradeLevelLegend(gradeData);
            }
        }

        // Function to generate colors for charts
        function generateColors(count) {
            // Professional color palette with better contrast and visual appeal
            const baseColors = [
                '#3B82F6', // Blue
                '#10B981', // Emerald
                '#F59E0B', // Amber
                '#EF4444', // Red
                '#8B5CF6', // Violet
                '#06B6D4', // Cyan
                '#84CC16', // Lime
                '#F97316', // Orange
                '#EC4899', // Pink
                '#6B7280' // Gray
            ];
            const colors = [];

            for (let i = 0; i < count; i++) {
                colors.push(baseColors[i % baseColors.length]);
            }

            return colors;
        }

        // Function to update program legend
        function updateProgramLegend(programData) {
            const labelsDiv = document.getElementById('program-labels');
            const countsDiv = document.getElementById('program-counts');

            if (labelsDiv && countsDiv && programData && programData.length > 0) {
                const total = programData.reduce((sum, item) => sum + (item.count || 0), 0);
                const colors = generateColors(programData.length);

                // Use compact layout for programs (many items)
                if (programData.length > 3) {
                    labelsDiv.innerHTML = `
                        <div class="grid grid-cols-2 gap-1">
                            ${programData.map((item, index) => `
                                                                                    <div class="flex items-center gap-1">
                                                                                        <div class="w-2 h-2 rounded-full" style="background-color: ${colors[index]}"></div>
                                                                                        <span class="font-medium text-gray-700 text-xs">${item.code || 'Unknown'}</span>
                                                                                    </div>
                                                                                `).join('')}
                        </div>
                    `;

                    countsDiv.innerHTML = `
                        <div class="grid grid-cols-2 gap-1">
                            ${programData.map((item, index) => {
                                const percentage = total > 0 ? ((item.count / total) * 100).toFixed(1) : 0;
                                return `
                                                                                        <div class="text-right">
                                                                                            <div class="font-bold text-xs" style="color: ${colors[index]}">${item.count || 0}</div>
                                                                                            <div class="font-medium text-xs" style="color: ${colors[index]}; opacity: 0.7">${percentage}%</div>
                                                                                        </div>
                                                                                    `;
                            }).join('')}
                        </div>
                    `;
                } else {
                    // Use vertical layout for fewer items
                    labelsDiv.innerHTML = programData.map((item, index) => {
                        const percentage = total > 0 ? ((item.count / total) * 100).toFixed(1) : 0;
                        return `
                            <div class="flex items-center gap-2 mb-1">
                                <div class="w-3 h-3 rounded-full" style="background-color: ${colors[index]}"></div>
                                <span class="font-medium text-gray-700 text-sm">${item.code || 'Unknown'}</span>
                            </div>
                        `;
                    }).join('');

                    countsDiv.innerHTML = programData.map((item, index) => {
                        const percentage = total > 0 ? ((item.count / total) * 100).toFixed(1) : 0;
                        return `
                            <div class="text-right mb-1">
                                <div class="font-bold text-sm" style="color: ${colors[index]}">${item.count || 0}</div>
                                <div class="font-medium text-xs" style="color: ${colors[index]}; opacity: 0.7">${percentage}%</div>
                            </div>
                        `;
                    }).join('');
                }

            } else if (labelsDiv && countsDiv) {
                // Show empty state
                labelsDiv.innerHTML = '<span class="font-regular text-gray-400 text-sm">No data</span>';
                countsDiv.innerHTML = '<span class="font-bold text-gray-400 text-sm">0</span>';
            }
        }

        // Function to update grade level legend
        function updateGradeLevelLegend(gradeData) {
            const labelsDiv = document.getElementById('grade-level-labels');
            const countsDiv = document.getElementById('grade-level-counts');

            if (labelsDiv && countsDiv && gradeData && gradeData.length > 0) {
                const total = gradeData.reduce((sum, item) => sum + (item.count || 0), 0);
                const colors = generateColors(gradeData.length);

                // Use compact layout for grade levels (many items)
                if (gradeData.length > 4) {
                    labelsDiv.innerHTML = `
                        <div class="grid grid-cols-2 gap-1">
                            ${gradeData.map((item, index) => `
                                                                                    <div class="flex items-center gap-1">
                                                                                        <div class="w-2 h-2 rounded-full" style="background-color: ${colors[index]}"></div>
                                                                                        <span class="font-medium text-gray-700 text-xs">${item.grade_level || 'Unknown'}</span>
                                                                                    </div>
                                                                                `).join('')}
                        </div>
                    `;

                    countsDiv.innerHTML = `
                        <div class="grid grid-cols-2 gap-1">
                            ${gradeData.map((item, index) => {
                                const percentage = total > 0 ? ((item.count / total) * 100).toFixed(1) : 0;
                                return `
                                                                                        <div class="text-right">
                                                                                            <div class="font-bold text-xs" style="color: ${colors[index]}">${item.count || 0}</div>
                                                                                            <div class="font-medium text-xs" style="color: ${colors[index]}; opacity: 0.7">${percentage}%</div>
                                                                                        </div>
                                                                                    `;
                            }).join('')}
                        </div>
                    `;
                } else {
                    // Use vertical layout for fewer items
                    labelsDiv.innerHTML = gradeData.map((item, index) => {
                        const percentage = total > 0 ? ((item.count / total) * 100).toFixed(1) : 0;
                        return `
                            <div class="flex items-center gap-2 mb-1">
                                <div class="w-3 h-3 rounded-full" style="background-color: ${colors[index]}"></div>
                                <span class="font-medium text-gray-700 text-sm">${item.grade_level || 'Unknown'}</span>
                            </div>
                        `;
                    }).join('');

                    countsDiv.innerHTML = gradeData.map((item, index) => {
                        const percentage = total > 0 ? ((item.count / total) * 100).toFixed(1) : 0;
                        return `
                            <div class="text-right mb-1">
                                <div class="font-bold text-sm" style="color: ${colors[index]}">${item.count || 0}</div>
                                <div class="font-medium text-xs" style="color: ${colors[index]}; opacity: 0.7">${percentage}%</div>
                            </div>
                        `;
                    }).join('');
                }

            } else if (labelsDiv && countsDiv) {
                // Show empty state
                labelsDiv.innerHTML = '<span class="font-regular text-gray-400 text-sm">No data</span>';
                countsDiv.innerHTML = '<span class="font-bold text-gray-400 text-sm">0</span>';
            }
        }

        // Function to initialize empty states
        function initializeEmptyStates() {
            // Initially show empty states for all charts
            const totalEmptyState = document.getElementById('total_chart_empty');
            const gradeLevelEmptyState = document.getElementById('grade_level_chart_empty');
            const programEmptyState = document.getElementById('program_chart_empty');

            if (totalEmptyState) {
                totalEmptyState.classList.add('opacity-100');
                totalEmptyState.classList.remove('opacity-0', 'pointer-events-none');
            }

            if (gradeLevelEmptyState) {
                gradeLevelEmptyState.classList.add('opacity-100');
                gradeLevelEmptyState.classList.remove('opacity-0', 'pointer-events-none');
            }

            if (programEmptyState) {
                programEmptyState.classList.add('opacity-100');
                programEmptyState.classList.remove('opacity-0', 'pointer-events-none');
            }
        }

        document.addEventListener("DOMContentLoaded", function() {


            @if ($errors->any())
                showAlert('error', '{{ $errors->first() }}');
            @endif

            @if (session('error'))
                showAlert('error', '{{ session('error') }}');
            @endif

            @if (session('success'))
                showAlert('success', '{{ session('success') }}');
            @endif


            initModal('import-modal', 'import-modal-btn', 'import-modal-close-btn', 'cancel-btn',
                'modal-container-1');

            const fileInput = document.getElementById('fileInput');
            const fileName = document.getElementById('fileName');

            fileInput.addEventListener('change', function() {
                fileName.textContent = this.files.length > 0 ? this.files[0].name : 'No file chosen';
            });

            //Overriding default search input
            const customSearch1 = document.getElementById("myCustomSearch");

            // Initialize empty states
            initializeEmptyStates();

            // Load initial enrollment statistics
            loadEnrollmentStats();

            table1 = new DataTable('#enrolledStudents', {
                paging: true,
                searching: true,
                autoWidth: false,
                serverSide: true,
                processing: true,
                ajax: {
                    url: '/users',
                    data: function(d) {
                        d.grade_filter = selectedGrade;
                        d.program_filter = selectedProgram;
                        d.pageLength = selectedPageLength;

                        // Pass term_id from URL parameter
                        const urlParams = new URLSearchParams(window.location.search);
                        const termId = urlParams.get('term_id');
                        if (termId) {
                            d.term_id = termId;
                        }

                        // Pass status filter
                        if (selectedStatusFilter) {
                            d.status_filter = selectedStatusFilter;
                        }
                    }
                },
                order: [
                    [6, 'desc']
                ],
                columnDefs: [{
                        width: '3.5%',
                        targets: 0,
                        className: 'text-center'
                    }, // Index column
                    {
                        width: '13.5%',
                        targets: 1
                    }, // LRN
                    {
                        width: '13.5%',
                        targets: 2
                    }, // Full Name
                    {
                        width: '13.5%',
                        targets: 3
                    }, // Grade Level
                    {
                        width: '10%',
                        targets: 4
                    }, // Program
                    {
                        width: '15%',
                        targets: 5,
                    }, // Contact
                    {
                        width: '15%',
                        targets: 6
                    }, // Email
                    {
                        width: '15%',
                        targets: 7,
                        className: 'text-center'
                    }, // Status
                    {
                        width: '10%',
                        targets: 8,
                        className: 'text-center'
                    } // Actions
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
                        data: 'lrn'
                    },
                    {
                        data: 'full_name'
                    },
                    {
                        data: 'grade_level'
                    },
                    {
                        data: 'program'
                    },
                    {
                        data: 'contact',
                        render: function(data, type, row) {

                            let badgeClass = '';
                            let badgeText = '';

                            switch (data) {
                                case 'Passed':
                                    badgeClass = 'bg-green-100 text-green-800';
                                    badgeText = 'Passed';
                                    break;
                                case 'Failed':
                                    badgeClass = 'bg-red-100 text-red-800';
                                    badgeText = 'Failed';
                                    break;
                                case 'Completed':
                                    badgeClass = 'bg-blue-100 text-blue-800';
                                    badgeText = 'Completed';
                                    break;
                                default:
                                    badgeClass = 'bg-gray-200 text-gray-800';
                                    badgeText = 'Not Evaluated';
                                    break;
                            }
                            return `<span class="px-2 py-1 rounded-full text-xs font-medium ${badgeClass}">${badgeText}</span>`;

                        },
                    },
                    {
                        data: 'email',
                        render: function(data, type, row) {

                            let badgeClass = '';
                            let badgeText = '';

                            switch (data) {
                                case 'Officially Enrolled':
                                    badgeClass = 'bg-blue-100 text-blue-800';
                                    badgeText = 'Officially Enrolled';
                                    break;
                                case 'Graduated':
                                    badgeClass = 'bg-green-100 text-green-800';
                                    badgeText = 'Graduated';
                                    break;
                                case 'Dropped':
                                    badgeClass = 'bg-red-100 text-red-800';
                                    badgeText = 'Dropped';
                                    break;
                                case 'Transferred':
                                    badgeClass = 'bg-gray-100 text-gray-800';
                                    badgeText = 'Transferred';
                                    break;
                                default:
                                    badgeClass = 'bg-gray-200 text-gray-800';
                                    badgeText = 'N/A';
                                    break;
                            }
                            return `<span class="px-2 py-1 rounded-full text-xs font-medium ${badgeClass}">${badgeText}</span>`;

                        },
                    },
                    {
                        data: 'status',
                        // render: function(data, type, row) {
                        //     if (row.status_raw === 'enrolled') {
                        //         return `<span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                        //             ${data}</span>`;
                        //     } else if (row.status_raw === 'pending_confirmation') {
                        //         return `<span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">
                        //             ${data}</span>`;
                        //     } else {
                        //         return `<span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">
                        //             ${data}</span>`;
                        //     }
                        // },
                        render: function(data, type, row) {

                            let badgeClass = '';
                            let badgeText = '';

                            switch (data) {
                                case 'enrolled':
                                    badgeClass = 'bg-green-100 text-green-800';
                                    badgeText = 'Enrolled';
                                    break;
                                case 'pending_confirmation':
                                    badgeClass = 'bg-yellow-100 text-yellow-800';
                                    badgeText = 'Pending Confirmation';
                                    break;
                                default:
                                    badgeClass = 'bg-gray-200 text-gray-800';
                                    badgeText = 'N/A';
                                    break;
                            }
                            return `<span class="px-2 py-1 rounded-full text-xs font-medium ${badgeClass}">${badgeText}</span>`;

                        },
                        orderable: false
                    },
                    {
                        data: 'id', // pass ID for rendering the link
                        render: function(data, type, row) {
                            return `
                            <div class='flex flex-row justify-center items-center opacity-100'>

                                <a href="/student/${data}" class="group relative inline-flex items-center gap-2 bg-blue-100 text-blue-500 font-semibold px-3 py-1 rounded-xl hover:bg-blue-500 hover:ring hover:ring-blue-200 hover:text-white transition duration-150 ">

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
                let newRow = document.querySelector('#enrolledStudents tbody');

                // Select all td elements within the new row
                let cells = newRow.querySelectorAll('td');

                cells.forEach(function(cell) {
                    cell.classList.add(
                        'px-4', // Horizontal padding
                        'py-1', // Vertical padding
                        'text-start', // Align text to the start (left)
                        'font-regular',
                        'text-[14px]',
                        'opacity-70',
                        'truncate',
                        'border-t',
                        'border-[#1e1e1e]/10',
                        'font-semibold'
                    );

                });

                // Refresh stats and charts after table renders for real-time feel
                loadEnrollmentStats();
                loadEnrollmentAnalytics();

            });

            table1.on("init", function() {
                const defaultSearch = document.querySelector("#dt-search-0");
                if (defaultSearch) {
                    defaultSearch.remove();
                }

            });

            clearSearch('clear-btn', 'myCustomSearch', table1)

            // Status filter buttons
            document.querySelectorAll('.status-filter-btn').forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active class from all buttons
                    document.querySelectorAll('.status-filter-btn').forEach(btn => {
                        btn.classList.remove('bg-[#199BCF]', 'text-white',
                            'hover:bg-[#33ACD6]');
                        btn.classList.add('bg-gray-200', 'text-gray-700',
                            'hover:bg-gray-300');
                    });

                    // Add active class to clicked button
                    this.classList.remove('bg-gray-200', 'text-gray-700');
                    this.classList.add('bg-[#199BCF]', 'text-white', 'hover:bg-[#33ACD6]');

                    // Set filter value
                    if (this.id === 'status-all') {
                        selectedStatusFilter = '';
                    } else if (this.id === 'status-enrolled') {
                        selectedStatusFilter = 'enrolled';
                    } else if (this.id === 'status-pending') {
                        selectedStatusFilter = 'pending_confirmation';
                    }

                    // Reload table
                    table1.ajax.reload();
                });
            });

            let programSelection = document.querySelector('#program_selection');
            let gradeSelection = document.querySelector('#grade_selection');
            let pageLengthSelection = document.querySelector('#page-length-selection');

            let clearProgramFilterBtn = document.querySelector('#clear-program-filter-btn');
            let clearGradeFilterBtn = document.querySelector('#clear-grade-filter-btn');
            let gradeContainer = document.querySelector('#grade_selection_container');

            programSelection.addEventListener('change', (e) => {

                let selectedOption = e.target.selectedOptions[0];
                let programId = selectedOption.getAttribute('data-id');

                // Only proceed if a valid program is selected (not the default option)
                if (programId && programId !== '') {
                    selectedProgram = programId;
                    table1.draw();

                    // Update program filter UI to show active state
                    let clearProgramFilterRem = ['text-gray-500', 'fi-rr-caret-down'];
                    let clearProgramFilterAdd = ['fi-bs-cross-small', 'cursor-pointer', 'text-gray-600'];
                    let programSelectionRem = ['border-[#1e1e1e]/10', 'text-gray-700'];
                    let programSelectionAdd = ['text-gray-600'];
                    let programContainerRem = ['bg-gray-100'];
                    let programContainerAdd = ['bg-gray-200', 'border-gray-300', 'hover:bg-gray-300'];

                    clearProgramFilterBtn.classList.remove(...clearProgramFilterRem);
                    clearProgramFilterBtn.classList.add(...clearProgramFilterAdd);
                    programSelection.classList.remove(...programSelectionRem);
                    programSelection.classList.add(...programSelectionAdd);

                    // Update the program container styling
                    let programContainer = clearProgramFilterBtn.closest('.flex');
                    programContainer.classList.remove(...programContainerRem);
                    programContainer.classList.add(...programContainerAdd);

                    handleClearProgramFilter(selectedOption);
                }
            })

            pageLengthSelection.addEventListener('change', (e) => {

                let selectedPageLength = parseInt(e.target.value, 10);

                table1.page.len(selectedPageLength).draw();

            })

            gradeSelection.addEventListener('change', (e) => {

                let selectedOption = e.target.selectedOptions[0];
                let email = selectedOption.getAttribute('data-putanginamo');

                selectedGrade = email;
                table1.draw();

                // Update grade filter UI to show active state with neutral colors
                let clearGradeFilterRem = ['text-gray-500', 'fi-rr-caret-down'];
                let clearGradeFilterAdd = ['fi-bs-cross-small', 'cursor-pointer', 'text-gray-600'];
                let gradeSelectionRem = ['border-[#1e1e1e]/10', 'text-gray-700'];
                let gradeSelectionAdd = ['text-gray-600'];
                let gradeContainerRem = ['bg-gray-100'];
                let gradeContainerAdd = ['bg-gray-200', 'border-gray-300', 'hover:bg-gray-300'];

                clearGradeFilterBtn.classList.remove(...clearGradeFilterRem);
                clearGradeFilterBtn.classList.add(...clearGradeFilterAdd);
                gradeSelection.classList.remove(...gradeSelectionRem);
                gradeSelection.classList.add(...gradeSelectionAdd);
                gradeContainer.classList.remove(...gradeContainerRem);
                gradeContainer.classList.add(...gradeContainerAdd);

                handleClearGradeFilter(selectedOption);
            })

            function handleClearProgramFilter(selectedOption) {
                clearProgramFilterBtn.addEventListener('click', () => {
                    // Reset program filter UI
                    let programContainer = clearProgramFilterBtn.closest('.flex');

                    programContainer.classList.remove('bg-gray-200', 'border-gray-300',
                        'hover:bg-gray-300');
                    programContainer.classList.add('bg-gray-100');

                    clearProgramFilterBtn.classList.remove('fi-bs-cross-small', 'cursor-pointer',
                        'text-gray-600');
                    clearProgramFilterBtn.classList.add('fi-rr-caret-down', 'text-gray-500');

                    programSelection.classList.remove('text-gray-600');
                    programSelection.classList.add('text-gray-700');

                    // Reset filter value
                    programSelection.selectedIndex = 0;
                    selectedProgram = '';
                    table1.draw();
                });
            }

            function handleClearGradeFilter(selectedOption) {
                clearGradeFilterBtn.addEventListener('click', () => {
                    // Reset grade filter UI with neutral colors
                    gradeContainer.classList.remove('bg-gray-200', 'border-gray-300', 'hover:bg-gray-300');
                    gradeContainer.classList.add('bg-gray-100');

                    clearGradeFilterBtn.classList.remove('fi-bs-cross-small', 'cursor-pointer',
                        'text-gray-600');
                    clearGradeFilterBtn.classList.add('fi-rr-caret-down', 'text-gray-500');

                    gradeSelection.classList.remove('text-gray-600');
                    gradeSelection.classList.add('text-gray-700');

                    // Reset filter value
                    gradeSelection.selectedIndex = 0;
                    selectedGrade = '';
                    table1.draw();
                });
            }

            window.onload = function() {
                gradeSelection.selectedIndex = 0
                programSelection.selectedIndex = 0
                pageLengthSelection.selectedIndex = 0
            }

            let dropDownBtn = document.querySelector('#dropdown_btn');
            let dropdownselection = document.querySelector('#dropdown_selection');

            dropDownBtn.addEventListener('click', () => {
                dropdownselection.classList.toggle('opacity-0');
                dropdownselection.classList.toggle('scale-95');
                dropdownselection.classList.toggle('pointer-events-none');
                dropdownselection.classList.toggle('translate-y-1');
            })


            document.getElementById('import-form').addEventListener('submit', function(e) {
                e.preventDefault();

                closeModal();

                let form = e.target;
                let formData = new FormData(form);

                // Show loader
                showLoader("Importing...");

                fetch("/students/import", {
                        method: "POST",
                        body: formData,
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        hideLoader();

                        if (data.success) {

                            showAlert('success', data.success);
                            table1.draw();

                            // Refresh stats and charts after successful import for real-time feel
                            setTimeout(() => {
                                loadEnrollmentStats();
                                loadEnrollmentAnalytics();
                            }, 500); // Small delay to ensure data is processed

                        } else if (data.error) {
                            showAlert('error', data.error);
                        }
                    })
                    .catch(err => {
                        hideLoader();
                        showAlert('error', 'Something went wrong');
                    });
            });


            // Wait for Chart library to load
            window.loadChartLibrary().then(() => {
                const totalChartCtx = document.getElementById('total_chart').getContext('2d');
                const gradeLevelChartCtx = document.getElementById('grade_level_chart').getContext('2d');
                const programChartCtx = document.getElementById('program_chart').getContext('2d');

                window.gradeLevelChart = new Chart(gradeLevelChartCtx, {
                type: 'doughnut',
                data: {
                    labels: [], // Will be populated dynamically
                    datasets: [{
                        label: 'Students',
                        data: [], // Will be populated dynamically
                        backgroundColor: [], // Will be populated dynamically
                        borderWidth: 3,
                        borderColor: '#ffffff',
                        hoverBorderWidth: 4,
                        hoverBorderColor: '#ffffff',
                    }]
                },
                options: {
                    responsive: false,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: {
                        legend: {
                            display: false // We'll use custom legend
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#ffffff',
                            bodyColor: '#ffffff',
                            borderColor: '#ffffff',
                            borderWidth: 1,
                            cornerRadius: 8,
                            displayColors: true,
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    return `${label}: ${value} students (${percentage}%)`;
                                }
                            }
                        }
                    },
                    animation: {
                        animateRotate: true,
                        animateScale: true,
                        duration: 1000,
                        easing: 'easeInOutQuart'
                    },
                    interaction: {
                        intersect: false
                    }
                }
                });

                window.programChart = new Chart(programChartCtx, {
                type: 'doughnut',
                data: {
                    labels: [], // Will be populated dynamically
                    datasets: [{
                        label: 'Students',
                        data: [], // Will be populated dynamically
                        backgroundColor: [], // Will be populated dynamically
                        borderWidth: 3,
                        borderColor: '#ffffff',
                        hoverBorderWidth: 4,
                        hoverBorderColor: '#ffffff',
                    }]
                },
                options: {
                    responsive: false,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: {
                        legend: {
                            display: false // We'll use custom legend
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#ffffff',
                            bodyColor: '#ffffff',
                            borderColor: '#ffffff',
                            borderWidth: 1,
                            cornerRadius: 8,
                            displayColors: true,
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    return `${label}: ${value} students (${percentage}%)`;
                                }
                            }
                        }
                    },
                    animation: {
                        animateRotate: true,
                        animateScale: true,
                        duration: 1000,
                        easing: 'easeInOutQuart'
                    },
                    interaction: {
                        intersect: false
                    }
                }
                });

                window.totalChart = new Chart(totalChartCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Enrolled', 'Pending'],
                    datasets: [{
                        label: 'Students',
                        data: [0, 0], // Will be updated by loadEnrollmentStats()
                        backgroundColor: ['#10B981',
                            '#F59E0B'
                        ], // Green for enrolled, yellow for pending
                        borderWidth: 3,
                        borderColor: '#ffffff',
                        hoverBorderWidth: 4,
                        hoverBorderColor: '#ffffff',
                    }]
                },
                options: {
                    responsive: false,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: {
                        legend: {
                            display: false // We'll use custom legend
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#ffffff',
                            bodyColor: '#ffffff',
                            borderColor: '#ffffff',
                            borderWidth: 1,
                            cornerRadius: 8,
                            displayColors: true,
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? ((value / total) * 100).toFixed(1) :
                                        0;
                                    return `${label}: ${value} students (${percentage}%)`;
                                }
                            }
                        }
                    },
                    animation: {
                        animateRotate: true,
                        animateScale: true,
                        duration: 1000,
                        easing: 'easeInOutQuart'
                    },
                    interaction: {
                        intersect: false
                    }
                }
            });

                // Load analytics after charts are initialized
                loadEnrollmentAnalytics();
            });

            function closeModal() {

                let modal = document.querySelector('#import-modal')
                let body = document.querySelector('#modal-container-1');

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
