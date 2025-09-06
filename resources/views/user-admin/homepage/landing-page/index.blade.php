@extends('layouts.admin')

@section('header')
    <div class="flex flex-row justify-between items-start text-start px-[14px] py-2">
        <div>
            <h1 class="text-[20px] font-black">Manage Invoices</h1>
            <p class="text-[14px]  text-gray-900/60">View and manage program list and associated sections and subjects.
            </p>
        </div>
    </div>
@endsection


@section('content')
    <x-alert />


    <div class="flex flex-row justify-center items-start gap-4">
        <div
            class="flex flex-col justify-start items-start flex-grow p-5 space-y-4 bg-[#f8f8f8] rounded-xl shadow-md border border-[#1e1e1e]/10 w-[40%]">

            <form action="/upload-background" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="background" required>
                <button type="submit" class="">Upload</button>
            </form>

            <p class="[text-shadow:2px_2px_8px_rgba(0,0,0,0.5)]">asjldkjalskdjksal</p>

        </div>

    </div>
@endsection

@push('scripts')
    {{-- <script type="module">
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

            initModal('create-school-fee-modal', 'create-school-fee-modal-btn', 'create-school-fee-modal-close-btn',
                'create-school-fee-modal-cancel-btn',
                'modal-container-1');
            initModal('create-invoice-modal', 'create-invoice-modal-btn', 'create-invoice-modal-close-btn',
                'create-invoice-modal-cancel-btn',
                'modal-container-2');
            initModal('edit-section-modal', 'edit-section-modal-btn', 'edit-section-close-btn',
                'edit-section-cancel-btn',
                'modal-container-3');

            let studentSeach = document.getElementById('studentSearch');
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

            studentSeach.addEventListener('input', function(e) {
                e.preventDefault();

                let lrn = document.querySelector('#lrn');
                let program = document.querySelector('#program');
                let level = document.querySelector('#level');
                let feesContainer = document.getElementById('fees-container');
                let feesmsg = document.getElementById('fees-msg');
                feesContainer.innerHTML = ''; // clear old fees
                let studentId = document.getElementById('student_id')

                let searchTerm = e.target.value.trim();
                if (searchTerm.length < 2) {
                    studentSeach.classList.remove('ring', 'ring-red-500', 'ring-green-500');

                    lrn.innerHTML = '-';
                    program.innerHTML = '-';
                    level.innerHTML = '-';

                    feesmsg.innerHTML =
                        'Applicable fees will show up, once the student is found';
                    return; // wait until user types at least 2 chars
                }

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
                        if (data.success) {
                            console.log(data)
                            if (data.data === null) {
                                studentSeach.classList.remove('ring-green-500');
                                studentSeach.classList.add('ring', 'ring-red-500');
                                lrn.innerHTML = '-';
                                program.innerHTML = '-';
                                level.innerHTML = '-';

                                feesmsg.innerHTML =
                                    'Applicable fees will show up, once the student is found';

                            } else if (data.data !== null && data.hasInvoice === false) {

                                lrn.innerHTML = data.data.lrn || '-';
                                program.innerHTML = data.data.program || '-';
                                level.innerHTML = data.data.grade_level || '-';

                                studentSeach.classList.remove('ring-red-500');
                                studentSeach.classList.add('ring', 'ring-green-500');

                                studentId.value = data.data.id;

                                // Render school fees checkboxe
                                feesmsg.innerHTML = '';
                                if (data.fees && data.fees.length > 0) {
                                    data.fees.forEach(fee => {
                                        let div = document.createElement('div');
                                        div.classList.add('flex', 'items-center', 'gap-2',
                                            'mb-2');

                                        let checkbox = document.createElement('input');
                                        checkbox.type = 'checkbox';
                                        checkbox.name = 'school_fees[]';
                                        checkbox.value = fee.id;

                                        let hidden = document.createElement('input');
                                        hidden.type = 'hidden';
                                        hidden.name =
                                            `school_fee_amounts[${fee.id}]`; // key by fee id
                                        hidden.value = fee.amount;

                                        let label = document.createElement('label');
                                        label.textContent = `${fee.name} - ₱${fee.amount}`;

                                        div.appendChild(checkbox);
                                        div.appendChild(label);
                                        div.appendChild(hidden);
                                        feesContainer.appendChild(div);
                                    });
                                } else {
                                    feesmsg.innerHTML = 'No applicable fees found';
                                }

                            } else {
                                feesmsg.innerHTML =
                                    'Student has already have an invoice assigned.';
                            }
                        } else {
                            studentSeach.classList.remove('ring-green-500');
                            studentSeach.classList.add('ring', 'ring-red-500');
                            lrn.innerHTML = '-';
                            program.innerHTML = '-';
                            level.innerHTML = '-';

                            feesmsg.innerHTML =
                                'Applicable fees will show up, once the student is found';
                        }
                    })
                    .catch(err => {
                        studentSeach.classList.remove('ring-green-500');
                        studentSeach.classList.add('ring', 'ring-red-500');

                        lrn.innerHTML = '-';
                        program.innerHTML = '-';
                        level.innerHTML = '-';
                        feesmsg.innerHTML =
                            'Applicable fees will show up, once the student is found';
                        console.error(err);
                    });
            });


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
    </script> --}}
@endpush
