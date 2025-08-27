@extends('layouts.admin')
@section('header')
    <div class="flex flex-row justify-between items-start text-start px-[14px] py-2">
        <div>
            <h1 class="text-[20px] font-black">Academic Programs</h1>
            <p class="text-[14px]  text-gray-900/60">View and manage program details and associated sections.
            </p>
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
@endsection
@section('content')
    <x-alert />



    <div
        class="px-5 text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:text-gray-400 dark:border-gray-700">
        <ul class="flex flex-wrap -mb-px">
            <li class="me-2">
                <a href="#"
                    class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">Sections</a>
            </li>
            <li class="me-2">
                <a href="#"
                    class="inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500"
                    aria-current="page">Subjects</a>
            </li>

        </ul>
    </div>


    <div class="flex flex-row justify-center items-start gap-4">
        <div
            class="flex flex-col justify-start items-center flex-grow p-5 space-y-4 bg-[#f8f8f8] rounded-xl shadow-md border border-[#1e1e1e]/10 w-[40%]">
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
                        <button id="add-student-modal-btn"
                            class="bg-[#1A3165] p-2 rounded-lg text-[14px] font-semibold flex justify-center items-center gap-2 text-white">
                            <i class="fi fi-rr-plus flex justify-center items-center "></i>
                            Create Program
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
                                <span class="mr-2 font-medium opacity-60 cursor-pointer">Program Code</span>
                                <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                            </th>

                            <th class="w-[45%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                <span class="mr-2 font-medium opacity-60 cursor-pointer">Name</span>
                                <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                            </th>

                            <th class="w-[15%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                <span class="mr-2 font-medium opacity-60 cursor-pointer">Created At</span>
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

        let table1;
        let selectedGrade = '';
        let selectedProgram = '';
        let selectedPageLength = '';




        document.addEventListener("DOMContentLoaded", function() {

            initModal('import-modal', 'import-modal-btn', 'import-modal-close-btn', 'cancel-btn',
                'modal-container-1');
            initModal('add-student-modal', 'add-student-modal-btn', 'add-student-modal-close-btn',
                'add-student-cancel-btn',
                'modal-container-2');
            initModal('edit-section-modal', 'edit-section-modal-btn', 'edit-section-close-btn',
                'edit-section-cancel-btn',
                'modal-container-3');

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

            table1 = new DataTable('#sections', {
                paging: true,
                searching: true,
                autoWidth: false,
                serverSide: true,
                processing: true,
                ajax: {
                    url: `/getPrograms`,
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
                        width: '15%',
                        targets: 1
                    }, // code
                    {
                        width: '50%',
                        targets: 2
                    }, // name
                    {
                        width: '20%',
                        targets: 3
                    }, // created at
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
                        data: 'code'
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'created_at'
                    },
                    {
                        data: 'id', // pass ID for rendering the link
                        render: function(data, type, row) {
                            return `
                            <div class='flex flex-row justify-center items-center opacity-100'>

                                <a href="/program/${data}" class="group relative inline-flex items-center gap-2 bg-blue-100 text-blue-500 font-semibold px-3 py-1 rounded-xl hover:bg-blue-500 hover:ring hover:ring-blue-200 hover:text-white transition duration-150 ">

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



            // document.getElementById('edit-section-form').addEventListener('submit', function(e) {
            //     e.preventDefault();

            //     closeModal();

            //     let form = e.target;
            //     let formData = new FormData(form);

            //     // Show loader
            //     showLoader("Edit...");

            //     fetch(`/section/${sectionId}`, {
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

            //                 sectionName.innerHTML = data.newData['newSectionName'];
            //                 sectionRoom.innerHTML = data.newData['newRoom'] || 'Not assigned yet';
            //                 closeModal('edit-section-modal', 'modal-container-3');
            //                 showAlert('success', data.success);
            //                 table1.draw();

            //             } else if (data.error) {

            //                 closeModal('edit-section-modal', 'modal-container-3');
            //                 showAlert('error', data.error);
            //             }
            //         })
            //         .catch(err => {
            //             hideLoader();

            //             closeModal('add-student-modal', 'modal-container-2');
            //             showAlert('error', 'Something went wrong');
            //         });
            // });


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
