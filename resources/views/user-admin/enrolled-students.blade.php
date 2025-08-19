@extends('layouts.admin')

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
                class="flex flex-col items-center justify-center w-full border-2 border-[#1A73E8]/60 border-dashed rounded-lg bg-[#E7F0FD] hover:bg-blue-100 cursor-pointer cursor-not-allowed select-none transition duration-150">

                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                    <svg class="w-8 h-8 mb-4 text-[#1A73E8]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 20 16">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                    </svg>
                    <p class="mb-2 text-sm text-[#0f111c]/80"><span class="font-semibold">Choose files to
                            upload</span></p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Supported Formats: .xlsx, .xls, .csv</p>
                </div>
                <span
                    class="bg-blue-500 px-4 py-2 rounded-lg text-white mb-4 hover:bg-blue-600 transition duration-200">Choose
                    Files</span>

                <input type="file" id="fileInput" name="file" class="hidden" accept=".xlsx,.xls,.csv" required>
                <span id="fileName" class="text-gray-500 italic">No file chosen</span>
            </label>
        </form>

        <x-slot name="modal_info">
            <i class="fi fi-bs-download flex justify-center items-center"></i>
            <a href="{{ asset('templates/Officially_Enrolled_Students_Import_Template.xlsx') }}" download>Click here to download the
                template</a>
        </x-slot>

        <x-slot name="modal_buttons">
            <button id="cancel-btn"
                class="bg-gray-100 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-md text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-200 hover:ring hover:ring-gray-200 transition duration-150">
                Cancel
            </button>
            {{-- This button will acts as the submit button --}}
            <button type="submit" form="import-form" name="action" value="verify"
                class="bg-blue-500 text-[14px] px-3 py-2 rounded-md text-[#f8f8f8] font-bold hover:ring hover:ring-blue-200 hover:bg-blue-400 transition duration-150 shadow-sm">
                Import
            </button>
        </x-slot>

    </x-modal>
@endsection

@section('header')
    <div class="flex flex-col justify-center items-start text-start px-[14px] py-2">
        <h1 class="text-[20px] font-black">Officially Enrolled Students</h1>
        <p class="text-[14px]  text-gray-900/60">Manage list and records of officially enrolled students.
        </p>
    </div>
@endsection

@section('stat')
    <div class="flex flex-row gap-4">
        <div class="flex-1 flex flex-col bg-[#f8f8f8] rounded-xl shadow-sm border border-[#1e1e1e]/10 p-6">
            <div class="flex flex-col justify-center items-start space-y-2">
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
                <canvas id="total_chart" width="180" height="180" class="self-center"></canvas>

                <div class="flex flex-row justify-between items-center w-full">
                    <div class="flex flex-col">
                        <span class="font-regular text-gray-600 text-[14px]">New Students</span>
                        <span class="font-regular text-gray-600 text-[14px]">Old Students</span>
                        <span class="font-regular text-gray-600 text-[14px]">Total</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-bold text-gray-600 text-[14px]">100</span>
                        <span class="font-bold text-gray-600 text-[14px]">100</span>
                        <span class="font-bold text-gray-600 text-[14px]">200</span>
                    </div>
                </div>

            </div>
        </div>
        <div class="flex-1 flex flex-col bg-[#f8f8f8] rounded-xl shadow-sm border border-[#1e1e1e]/10 p-6">
            <div class="flex flex-col justify-center items-start space-y-4">
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
                <canvas id="grade_level_chart" width="180" height="180" class="self-center"></canvas>

                <div class="flex flex-row justify-between items-center w-full">
                    <div class="flex flex-col">
                        <span class="font-regular text-gray-600">Grade 11</span>
                        <span class="font-regular text-gray-600">Grade 12</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-bold text-gray-600">100</span>
                        <span class="font-bold text-gray-600">100</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex-1 flex flex-col bg-[#f8f8f8] rounded-xl shadow-sm border border-[#1e1e1e]/10 p-6">
            <div class="flex flex-col justify-center items-start space-y-4">
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
                <canvas id="program_chart" width="180" height="180" class="self-center"></canvas>
                <div class="flex flex-row justify-between items-center w-full">
                    <div class="flex flex-col">
                        <span class="font-regular text-gray-600">HUMSS</span>
                        <span class="font-regular text-gray-600">ABM</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-bold text-gray-600">100</span>
                        <span class="font-bold text-gray-600">100</span>
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
            class="flex flex-col justify-start items-center flex-grow p-5 space-y-4 bg-[#f8f8f8] rounded-xl shadow-sm border border-[#1e1e1e]/10 w-[40%]">
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
                        <div
                            class="flex flex-row justify-between items-center rounded-lg border border-[#1e1e1e]/10 bg-gray-100 px-3 py-1 gap-2 hover:bg-gray-200 hover:border-[#1e1e1e]/15 transition-all ease-in-out duration-150 shadow-sm">
                            <select name="" id=""
                                class="appearance-none bg-transparent text-[14px] font-medium text-gray-700 h-full w-full cursor-pointer">
                                <option value="" disabled selected>Gender</option>
                                <option value="">Male</option>
                                <option value="">Female</option>
                            </select>
                            <i id="clear-gender-filter-btn"
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
                        <button
                            class="flex-1 flex justify-start items-center px-8 py-2 gap-2 text-[14px] font-medium opacity-80 w-full hover:bg-gray-200 truncate">
                            <i class="fi fi-sr-file-pdf text-[16px]"></i>Export As .pdf
                        </button>
                    </div>

                </div>
            </div>

            <div class="w-full">
                <table id="enrolledStudents" class="w-full table-fixed">
                    <thead class="text-[14px]">
                        <tr>
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
                                <span class="mr-2 font-medium opacity-60 cursor-pointer">Contact No.</span>
                                <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                            </th>
                            <th class="w-1/7 text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                <span class="mr-2 font-medium opacity-60 cursor-pointer">Email Address</span>
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
        </div>

    </div>
@endsection

@push('scripts')
    <script type="module">
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

        let table1;
        let selectedGrade = '';
        let selectedProgram = '';
        let selectedGender = '';

        document.addEventListener("DOMContentLoaded", function() {

            initModal('import-modal', 'import-modal-btn', 'import-modal-close-btn', 'cancel-btn',
                'modal-container-1');

            const fileInput = document.getElementById('fileInput');
            const fileName = document.getElementById('fileName');

            fileInput.addEventListener('change', function() {
                fileName.textContent = this.files.length > 0 ? this.files[0].name : 'No file chosen';
            });

            //Overriding default search input
            const customSearch1 = document.getElementById("myCustomSearch");

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
                        d.gender_filter = selectedGender;
                    }
                },
                order: [
                    [6, 'desc']
                ],
                columnDefs: [{
                    width: '16.66%',
                    targets: '_all',
                }],
                layout: {
                    topStart: null,
                    topEnd: null,
                    bottomStart: 'info',
                    bottomEnd: 'paging',
                },
                columns: [{
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
                        data: 'contact'
                    },
                    {
                        data: 'email'
                    },
                    {
                        data: 'id', // pass ID for rendering the link
                        render: function(data, type, row) {
                            return `
                            <div class='flex flex-row justify-center items-center opacity-100'>

                                <a href="/users/${data}" class="group relative inline-flex items-center gap-2 bg-blue-100 text-blue-500 font-semibold px-3 py-1 rounded-xl hover:bg-blue-500 hover:ring hover:ring-blue-200 hover:text-white transition duration-150 ">

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

            let clearGradeFilterBtn = document.querySelector('#clear-grade-filter-btn');
            let gradeContainer = document.querySelector('#grade_selection_container');

            programSelection.addEventListener('change', (e) => {

                let selectedOption = e.target.selectedOptions[0];
                let id = selectedOption.getAttribute('data-id');

                selectedProgram = id;
                table1.draw();

                console.log(id);
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
                console.log(email);
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

                        console.log(data)

                        if (data.success) {

                            showAlert('success', data.success);
                            table1.draw();

                        } else if (data.error) {

                            showAlert('error', data.error);
                        }
                    })
                    .catch(err => {
                        hideLoader();
                        showAlert('error', 'Something went wrong');
                    });
            });


            const totalChartCtx = document.getElementById('total_chart').getContext('2d');
            const gradeLevelChartCtx = document.getElementById('grade_level_chart').getContext('2d');
            const programChartCtx = document.getElementById('program_chart').getContext('2d');

            const gradeLevelChart = new Chart(gradeLevelChartCtx, {
                type: 'pie', // change to 'pie' for pie chart
                data: {

                    datasets: [{
                        label: 'Students',
                        data: [19, 3],
                        backgroundColor: ['#199BCF', '#1A3165'],
                    }]
                },
                options: {
                    responsive: false,
                    maintainAspectRatio: false
                    // makes it a donut; remove for a pie chart
                }
            });

            const programChart = new Chart(programChartCtx, {
                type: 'pie', // change to 'pie' for pie chart
                data: {

                    datasets: [{
                        label: 'Students',
                        data: [20, 265],
                        backgroundColor: ['#199BCF', '#1A3165'],
                    }]
                },
                options: {
                    responsive: false,
                    maintainAspectRatio: false,
                    cutout: '70%'
                    // makes it a donut; remove for a pie chart
                }
            });

            const totalChart = new Chart(totalChartCtx, {
                type: 'pie', // change to 'pie' for pie chart
                data: {

                    datasets: [{
                        label: 'Students',
                        data: [20, 265],
                        backgroundColor: ['#199BCF', '#1A3165'],
                    }]
                },
                options: {
                    responsive: false,
                    maintainAspectRatio: false,
                    cutout: '70%'
                    // makes it a donut; remove for a pie chart
                }
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
