@extends('layouts.admin')

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
            <div class="flex flex-col justify-center items-start space-y-4">
                <div class="flex flex-row justify-between items-center w-full">
                    <div class="flex flex-row justify-center items-center gap-2">
                        <div class="bg-gray-200 flex justify-center items-center p-1 rounded-lg">
                            <i
                                class="fi fi-rr-chart-simple flex justify-center items-center text-[18px] text-gray-500"></i>
                        </div>
                        <span class="font-medium text-gray-700 text-[18px]">Total</span>
                    </div>
                    <div class="flex flex-row justify-center items-center">
                        <i class="fi fi-rs-info flex justify-center items-center text-[16px] text-gray-500"
                            title="tabgubano tabgubanotabgubanotabgubanotabgubano tabgubano"></i>
                    </div>
                </div>
                <div class="flex flex-row justify-center items-center gap-4">
                    <span class="font-semibold text-[32px] text-gray-700">200</span>
                    <div class="bg-green-100 px-3 py-1 rounded-lg ">
                        <span class="text-green-500 text-[12px] flex justify-center items-center gap-1">42 New enrollees <i
                                class="fi fi-bs-arrow-up-right text-[10px] flex justify-center items-center"></i></span>
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
                    <div class="flex flex-row justify-center items-center" title="Total number of enrolled students per grade level">
                        <i class="fi fi-rs-info flex justify-center items-center text-[18px] text-gray-500"
                            ></i>
                    </div>
                </div>

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
    <div class="flex flex-row gap-4">
        <div
            class="flex flex-col items-center flex-grow p-5 space-y-4 bg-[#f8f8f8] rounded-xl shadow-sm border border-[#1e1e1e]/10 w-[40%]">
            <div class="flex flex-row justify-between items-center w-full">

                <div class="w-full flex flex-row justify-between items-center gap-4">

                    <label for="myCustomSearch"
                        class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-1 px-2 gap-2 w-[40%] hover:ring hover:ring-blue-200 focus-within:ring focus-within:ring-blue-100 focus-within:border-blue-500 transition duration-150 shadow-sm">
                        <i class="fi fi-rs-search flex justify-center items-center text-[#1e1e1e]/60 text-[16px]"></i>
                        <input type="search" name="" id="myCustomSearch"
                            class="my-custom-search bg-transparent outline-none text-[14px] w-full peer"
                            placeholder="Search by applicant id, name, interviewer">
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
                                <option value="" data-id="ar">HUMSS</option>
                                <option value="" data-id="2">ABM</option>
                            </select>
                            <i id="clear-program-filter-btn"
                                class="fi fi-rr-caret-down text-gray-500 flex justify-center items-center"></i>
                        </div>


                        <div id="grade_selection_container"
                            class="flex flex-row justify-between items-center rounded-lg border border-[#1e1e1e]/10 bg-gray-100 px-3 py-1 gap-2 hover:bg-gray-200 hover:border-[#1e1e1e]/15 transition-all ease-in-out duration-150 shadow-sm">

                            <select name="grade_selection" id="grade_selection"
                                class="appearance-none bg-transparent text-[14px] font-medium text-gray-700 h-full w-full cursor-pointer">
                                <option value="" disabled selected>Grade</option>
                                <option value="" data-putanginamo="emoore@example.net">Grade 11</option>
                                <option value="" data-putanginamo="example.com">Grade 12</option>
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


                <button
                    class="group relative inline-flex items-center gap-2 bg-blue-500 text-white font-semibold px-4 py-1 rounded-xl hover:bg-blue-400 hover:ring hover:ring-blue-200 transition duration-150">

                    <span class="relative w-4 h-4">
                        <i
                            class="fi fi-rr-user-add flex justify-center items-center absolute inset-0 group-hover:opacity-0 transition-opacity text-[14px]"></i>
                        <i
                            class="fi fi-sr-user-add flex justify-center items-center absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity text-[14px]"></i>
                    </span>

                    Import
                </button>
            </div>

            <div class="w-full ">
                <table id="enrolledStudents" class="w-full table-fixed ">
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
                                <span class="mr-2 font-medium opacity-60 cursor-pointer">Last Name</span>
                                <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                            </th>
                            <th class="w-1/7 text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                <span class="mr-2 font-medium opacity-60 cursor-pointer">Email</span>
                                <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                            </th>
                            <th class="w-1/7 text-center bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10  px-4 py-2">
                                <span class="mr-2 font-medium opacity-60 cursor-pointer">Actions</span>
                                <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                            </th>

                        </tr>
                    </thead>
                    <tbody>
                        {{-- <tr class="border-t-[1px] border-[#1e1e1e]/15 w-full rounded-md"></tr> --}}
                    </tbody>
                </table>
            </div>
        </div>
        <div class="flex flex-col gap-4 justify-start items-center w-[25%] ">
            <div class="flex flex-col bg-[#f8f8f8] rounded-xl shadow-sm border border-[#1e1e1e]/10 p-6 w-full">
                <div class="flex flex-col justify-center items-start space-y-2">
                    <div class="flex flex-row justify-between items-center w-full">
                        <div class="flex flex-row justify-center items-center gap-2">
                            <div class="bg-gray-200 flex justify-center items-center p-1 rounded-lg">
                                <i
                                    class="fi fi-rr-graduation-cap flex justify-center items-center text-[16px] text-gray-500"></i>
                            </div>
                            <span class="font-medium text-gray-700 text-[16px]">Program Overview</span>
                        </div>
                        <div class="flex flex-row justify-center items-center">
                            <i class="fi fi-rs-info flex justify-center items-center text-[18px] text-gray-500"
                                title="tabgubano tabgubanotabgubanotabgubanotabgubano tabgubano"></i>
                        </div>
                    </div>

                    <canvas id="myChart" width="200" height="200" class="self-center"></canvas>

                </div>
            </div>
            <div
                class="flex flex-col justify-center items-center bg-[#f8f8f8] rounded-xl shadow-sm border border-[#1e1e1e]/10 p-6 w-full">
                <div class="flex flex-col justify-center items-start space-y-2 h-full w-full">
                    <div class="flex flex-row justify-between items-center w-full">
                        <div class="flex flex-row justify-center items-center gap-2">
                            <div class="bg-gray-200 flex justify-center items-center p-1 rounded-lg">
                                <i
                                    class="fi fi-rr-book-bookmark flex justify-center items-center text-[16px] text-gray-500"></i>
                            </div>
                            <span class="font-medium text-gray-700 text-[16px]">Grade Level Overview</span>
                        </div>
                        <div class="flex flex-row justify-center items-center">
                            <i class="fi fi-rs-info flex justify-center items-center text-[18px] text-gray-500"
                                title="tabgubano tabgubanotabgubanotabgubanotabgubano tabgubano"></i>
                        </div>
                    </div>

                    <canvas id="myChart2" width="200" height="200" class="self-center"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="module">
        import {
            clearSearch
        } from "/js/clearSearch.js"

        let table1;
        let selectedGrade = '';
        let selectedProgram = '';
        let selectedGender = '';



        document.addEventListener("DOMContentLoaded", function() {

            //Overriding default search input
            const customSearch1 = document.getElementById("myCustomSearch");

            table1 = new DataTable('#enrolledStudents', {
                paging: true,
                pageLength: 20,
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
                        data: 'id'
                    },
                    {
                        data: 'first_name'
                    },
                    {
                        data: 'last_name'
                    },
                    {
                        data: 'email'
                    },
                    {
                        data: 'id', // pass ID for rendering the link
                        render: function(data, type, row) {
                            return `
                            <div class='flex flex-row justify-center items-center'>

                                <a href="/users/${data}" class="group relative inline-flex items-center gap-2 bg-blue-100 text-blue-500 font-semibold px-3 py-1 rounded-xl hover:bg-blue-500 hover:ring hover:ring-blue-200 hover:text-white transition duration-150">

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
                let clearGradeFilterAdd = ['fi-bs-cross-small', 'cursor-pointer', 'text-[#1A73E8]'];
                let gradeSelectionRem = ['border-[#1e1e1e]/10', 'text-gray-700'];
                let gradeSelectionAdd = ['text-[#1A73E8]'];
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
                    gradeSelection.classList.remove('text-blue-900')
                    gradeSelection.classList.add('text-gray-700')
                    clearGradeFilterBtn.classList.remove('text-blue-900')
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



            const ctx = document.getElementById('myChart').getContext('2d');
            const ctx2 = document.getElementById('myChart2').getContext('2d');

            const myChart = new Chart(ctx, {
                type: 'pie', // change to 'pie' for pie chart
                data: {
                    labels: ['HUMSS', 'ABM'],
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

            const myChart2 = new Chart(ctx2, {
                type: 'pie', // change to 'pie' for pie chart
                data: {
                    labels: ['G-11', 'G-12'],
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


        });
    </script>
@endpush
