@extends('layouts.admin')

@section('header')
    <div class="flex flex-col justify-center items-start text-start px-[14px] py-2">
        <h1 class="text-[20px] font-black">Section List</h1>
        <p class="text-[14px]  text-gray-900/60">View and manage section list.
        </p>
    </div>
@endsection
@section('modal')
    <x-modal modal_id="create-section-modal" modal_name="Create Section" close_btn_id="create-section-modal-close-btn"
        modal_container_id="modal-container-1">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-progress-upload flex justify-center items-center '></i>

        </x-slot>

        <div class="max-h-[70vh] overflow-y-auto">
            <form id="create-section-form" class="p-6">
                @csrf
                <div class="space-y-6">
                    <!-- Program Selection -->
                    <div>
                        <label for="program_id" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fi fi-rr-graduation-cap mr-2"></i>
                            Program <span class="text-red-500">*</span>
                        </label>
                        <select name="program_id" id="program_id" required
                            class="w-full border-2 border-gray-300 bg-gray-100 rounded-lg px-3 py-2 outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 transition duration-200 placeholder:italic placeholder:text-[14px] text-[14px]">
                            <option value="">Select Program</option>
                            @foreach ($programs as $program)
                                <option value="{{ $program->id }}" {{ $program->id == $program->id ? 'selected' : '' }}>
                                    {{ $program->name }} ({{ $program->code }})
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
                            class="w-full border-2 border-gray-300 bg-gray-100 rounded-lg px-3 py-2 outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 transition duration-200 placeholder:italic placeholder:text-[14px] text-[14px]">
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
                        <input type="text" name="section_code" id="section_code" placeholder="e.g., 11-HUMSS-A" required
                            class="w-full border-2 border-gray-300 bg-gray-100 rounded-lg px-3 py-2 outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 transition duration-200 placeholder:italic placeholder:text-[14px] text-[14px]">
                    </div>

                    <!-- Room -->
                    <div>
                        <label for="room" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fi fi-rr-home mr-2"></i>
                            Room Assignment
                        </label>
                        <input type="text" name="room" id="room" placeholder="e.g., Room 101, Lab 2"
                            class="w-full border-2 border-gray-300 bg-gray-100 rounded-lg px-3 py-2 outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 transition duration-200 placeholder:italic placeholder:text-[14px] text-[14px]">
                    </div>

                    <!-- Adviser Selection -->
                    <div>
                        <label for="adviser_id" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fi fi-rr-user-tie mr-2"></i>
                            Assign Adviser
                        </label>
                        <select name="adviser_id" id="adviser_id"
                            class="w-full border-2 border-gray-300 bg-gray-100 rounded-lg px-3 py-2 outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 transition duration-200 placeholder:italic placeholder:text-[14px] text-[14px]">
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

                <div id="subjects-container"
                    class="mt-6 max-h-64 overflow-y-auto border border-gray-200 rounded-lg p-3 bg-gray-50 hidden">
                </div> <!-- subjects will be inserted here -->

            </form>
        </div>

        <x-slot name="modal_info">

        </x-slot>

        <x-slot name="modal_buttons">
            <button id="create-section-cancel-btn"
                class="bg-gray-50 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-100 hover:ring hover:ring-gray-200 transition duration-150">
                Cancel
            </button>
            {{-- This button will acts as the submit button --}}
            <button type="submit" form="create-section-form" name="action" value="create-section"
                class="self-end flex flex-row justify-center items-center bg-[#199BCF] py-2 px-3 rounded-xl text-[14px] font-semibold gap-2 text-white hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                Continue
            </button>
        </x-slot>

    </x-modal>
@endsection
@section('content')
    <x-alert />

    <div class="flex flex-row justify-center items-start gap-4">
        <div
            class="flex flex-col justify-start items-center flex-grow p-5 space-y-4 bg-[#f8f8f8] rounded-xl shadow-md border border-[#1e1e1e]/10 w-[40%]">
            <div class="flex flex-col my-2 justify-center items-center w-full">
                <span class="font-semibold text-[18px]">
                    Sections
                </span>
                <span class="font-medium text-gray-400 text-[14px]">
                    Student's invoice list across different academic terms
                </span>
            </div>
            <div class="flex flex-row justify-between items-center w-full h-full py-2">

                <div class="flex flex-row justify-between w-3/4 items-center gap-4">

                    <label for="myCustomSearch"
                        class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-2 gap-2 w-[40%] hover:ring hover:ring-blue-200 focus-within:ring focus-within:ring-blue-100 focus-within:border-blue-500 transition duration-150 shadow-sm">
                        <i class="fi fi-rs-search flex justify-center items-center text-[#1e1e1e]/60 text-[16px]"></i>
                        <input type="search" name="" id="myCustomSearch"
                            class="my-custom-search bg-transparent outline-none text-[14px] w-full peer"
                            placeholder="Search by name, program, grade level, etc.">
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
                                <option value="" selected disabled>Program</option>
                                @foreach ($programs as $program)
                                    <option value="" data-id="{{ $program->code }}">{{ $program->code }}</option>
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
                                <option value="" data-putanginamo="Grade 7">Grade 7</option>
                                <option value="" data-putanginamo="Grade 8">Grade 8</option>
                                <option value="" data-putanginamo="Grade 9">Grade 9</option>
                                <option value="" data-putanginamo="Grade 10">Grade 10</option>
                                <option value="" data-putanginamo="Grade 11">Grade 11</option>
                                <option value="" data-putanginamo="Grade 12">Grade 12</option>
                            </select>
                            <i id="clear-grade-filter-btn"
                                class="fi fi-rr-caret-down text-gray-500 flex justify-center items-center"></i>

                        </div>


                    </div>
                </div>

                @can('create section')
                    <button id="create-section-modal-btn"
                        class="self-end flex flex-row justify-center items-center bg-[#199BCF] py-2 px-3 rounded-xl text-[16px] font-semibold gap-2 text-white hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                        <i class="fi fi-sr-square-plus opacity-70 flex justify-center items-center text-[18px]"></i>
                        New Section
                    </button>
                @endcan
            </div>

            <div class="w-full">
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
                                <span class="mr-2 font-medium opacity-60 cursor-pointer">Program</span>
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
        import {
            initCustomDataTable
        } from "/js/initTable.js";

        let table1;
        let selectedGrade = '';
        let selectedProgram = '';
        let selectedPageLength = '';

        document.addEventListener("DOMContentLoaded", function() {

            let assignCheckbox = document.getElementById('auto_assign');

            initModal('create-section-modal', 'create-section-modal-btn', 'create-section-modal-close-btn',
                'create-section-cancel-btn',
                'modal-container-1');

            // const fileInput = document.getElementById('fileInput');
            // const fileName = document.getElementById('fileName');

            // fileInput.addEventListener('change', function() {
            //     fileName.textContent = this.files.length > 0 ? this.files[0].name : 'No file chosen';
            // });

            //Overriding default search input
            let sectionsTable = initCustomDataTable(
                'sections',
                `/getAllSections`,
                [{
                        data: 'index'
                    },
                    {
                        data: 'name',
                        render: DataTable.render.text()
                    },
                    {
                        data: 'program_code',
                        render: DataTable.render.text()
                    },
                    {
                        data: 'adviser',
                        render: DataTable.render.text()
                    },
                    {
                        data: 'year_level',
                        render: DataTable.render.text()
                    },
                    {
                        data: 'room',
                        render: DataTable.render.text()
                    },
                    {
                        data: 'total_enrolled_students',
                        render: DataTable.render.text()
                    },
                    {
                        data: 'id',
                        render: function(data, type, row) {
                            return `
                            <a href='/section/${data}' class='flex flex-row justify-center items-center gap-2'>
                                <button type="button" id="open-edit-modal-btn-${data}"
                                    data-section-id="${data}"
                                    class="edit-section-btn group relative inline-flex items-center gap-2 bg-blue-100 text-blue-500 font-semibold px-3 py-2 rounded-xl hover:bg-blue-500 hover:ring hover:ring-blue-200 hover:text-white transition duration-150">
                                    <i class="fi fi-rr-eye text-[16px] flex justify-center items-center"></i>
                                    View
                                </button>
                            </a>`;
                        },
                        orderable: false,
                        searchable: false
                    }
                ],
                [
                    [0, 'desc']
                ],
                'myCustomSearch',
                [{
                        width: '5%',
                        targets: 0,
                        className: 'text-center'
                    },
                    {
                        width: '16%',
                        targets: 1
                    },
                    {
                        width: '12%',
                        targets: 2
                    },
                    {
                        width: '18%',
                        targets: 3
                    },
                    {
                        width: '12%',
                        targets: 4
                    },
                    {
                        width: '12%',
                        targets: 5
                    },
                    {
                        width: '13%',
                        targets: 6,
                        className: 'text-center'
                    },
                    {
                        width: '10%',
                        targets: 7,
                        className: 'text-center'
                    }
                ]
            );

            clearSearch('clear-btn', 'myCustomSearch', sectionsTable)

            if (assignCheckbox) {
                assignCheckbox.checked = false;

                // Function to load subjects for auto-assign
                function loadAutoAssignSubjects() {
                    if (!assignCheckbox.checked) return;

                    const programId = document.getElementById('program_id').value;
                    const yearLevel = document.getElementById('year_level').value;
                    const container = document.getElementById('subjects-container');

                    if (!programId || !yearLevel) {
                        showAlert('error', "Please select a program and year level first.");
                        assignCheckbox.checked = false;
                        container.classList.add('hidden');
                        container.innerHTML = "";
                        return;
                    }

                    // Show container and add loading state
                    container.classList.remove('hidden');
                    container.innerHTML =
                        '<div class="flex items-center justify-center py-4"><div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div><span class="ml-2 text-sm text-gray-600">Loading subjects...</span></div>';

                    fetch(`/subjects/auto-assign?program_id=${programId}&year_level=${yearLevel}`, {
                            headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.subjects && data.subjects.length > 0) {
                                container.innerHTML = '<div class="space-y-2">';

                                // Group subjects by category
                                const coreSubjects = data.subjects.filter(subj => subj.category ===
                                    'core');
                                const appliedSubjects = data.subjects.filter(subj => subj
                                    .category === 'applied');
                                const specializedSubjects = data.subjects.filter(subj => subj
                                    .category === 'specialized');

                                // Add core subjects section
                                if (coreSubjects.length > 0) {
                                    const coreHeader = document.createElement('div');
                                    coreHeader.className =
                                        'text-xs font-semibold text-blue-600 uppercase tracking-wide mb-2 mt-4 first:mt-0';
                                    coreHeader.textContent = 'Core Subjects';
                                    container.appendChild(coreHeader);

                                    coreSubjects.forEach(subj => {
                                        const div = document.createElement('div');
                                        div.className =
                                            'flex items-center space-x-2 p-2 hover:bg-gray-100 rounded';
                                        div.innerHTML = `
                                             <input type="checkbox" name="subjects[]" value="${subj.id}" checked class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                             <label class="text-sm text-gray-700 cursor-pointer">${subj.name}</label>
                                         `;
                                        container.appendChild(div);
                                    });
                                }

                                // Add applied subjects section
                                if (appliedSubjects.length > 0) {
                                    const appliedHeader = document.createElement('div');
                                    appliedHeader.className =
                                        'text-xs font-semibold text-green-600 uppercase tracking-wide mb-2 mt-4';
                                    appliedHeader.textContent = 'Applied Subjects';
                                    container.appendChild(appliedHeader);

                                    appliedSubjects.forEach(subj => {
                                        const div = document.createElement('div');
                                        div.className =
                                            'flex items-center space-x-2 p-2 hover:bg-gray-100 rounded';
                                        div.innerHTML = `
                                             <input type="checkbox" name="subjects[]" value="${subj.id}" checked class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                             <label class="text-sm text-gray-700 cursor-pointer">${subj.name}</label>
                                         `;
                                        container.appendChild(div);
                                    });
                                }

                                // Add specialized subjects section
                                if (specializedSubjects.length > 0) {
                                    const specializedHeader = document.createElement('div');
                                    specializedHeader.className =
                                        'text-xs font-semibold text-purple-600 uppercase tracking-wide mb-2 mt-4';
                                    specializedHeader.textContent = 'Specialized Subjects';
                                    container.appendChild(specializedHeader);

                                    specializedSubjects.forEach(subj => {
                                        const div = document.createElement('div');
                                        div.className =
                                            'flex items-center space-x-2 p-2 hover:bg-gray-100 rounded';
                                        div.innerHTML = `
                                             <input type="checkbox" name="subjects[]" value="${subj.id}" checked class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                             <label class="text-sm text-gray-700 cursor-pointer">${subj.name}</label>
                                         `;
                                        container.appendChild(div);
                                    });
                                }

                                container.innerHTML += '</div>';
                            } else {
                                container.innerHTML =
                                    '<div class="text-center py-4 text-gray-500"><p class="text-sm">No subjects found for this selection.</p></div>';
                            }
                        })
                        .catch(err => {
                            console.error("Error fetching subjects:", err);
                            container.innerHTML =
                                '<div class="text-center py-4 text-red-500"><p class="text-sm">Failed to load subjects.</p></div>';
                        });
                }

                assignCheckbox.addEventListener('change', function(e) {
                    const isChecked = e.target.checked;
                    const container = document.getElementById('subjects-container');

                    if (isChecked) {
                        loadAutoAssignSubjects();
                    } else {
                        // Hide container when unchecked
                        container.classList.add('hidden');
                        container.innerHTML = "";
                    }
                });

                // Add event listeners to program and year level dropdowns for auto-refresh
                const programSelect = document.getElementById('program_id');
                const yearLevelSelect = document.getElementById('year_level');

                if (programSelect) {
                    programSelect.addEventListener('change', function() {
                        // Auto-refresh subjects if auto-assign is checked
                        if (assignCheckbox.checked) {
                            loadAutoAssignSubjects();
                        }
                    });
                }

                if (yearLevelSelect) {
                    yearLevelSelect.addEventListener('change', function() {
                        // Auto-refresh subjects if auto-assign is checked
                        if (assignCheckbox.checked) {
                            loadAutoAssignSubjects();
                        }
                    });
                }
            }

            document.getElementById('create-section-form').addEventListener('submit', function(e) {
                e.preventDefault();

                let form = e.target;
                let formData = new FormData(form);

                showLoader();

                fetch(`/section`, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        hideLoader();
                        if (data.success) {
                            showAlert('success', data.message);
                            closeModal('create-section-modal',
                                'modal-container-1');
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        } else {
                            showAlert('error', data.message || 'Unknown error');
                            closeModal('create-section-modal',
                                'modal-container-1');
                        }
                    })
                    .catch(error => {
                        hideLoader();
                        console.error('Error:', error);
                        showAlert('error', 'An error occurred while deleting the school fee');
                    });
            });

            let programSelection = document.querySelector('#program_selection');
            let gradeSelection = document.querySelector('#grade_selection');
            let pageLengthSelection = document.querySelector('#page-length-selection');

            let clearGradeFilterBtn = document.querySelector('#clear-grade-filter-btn');
            let gradeContainer = document.querySelector('#grade_selection_container');
            let clearProgramFilterBtn = document.querySelector('#clear-program-filter-btn');

            programSelection.addEventListener('change', (e) => {

                let selectedOption = e.target.selectedOptions[0];
                let id = selectedOption.getAttribute('data-id');

                selectedProgram = id;
                window.selectedProgram = id; // Set global variable for DataTable
                sectionsTable.draw();

                // Add visual feedback for selected program
                if (id) {
                    let programContainer = programSelection.closest('.flex');
                    programContainer.classList.remove('bg-gray-100', 'border-[#1e1e1e]/10');
                    programContainer.classList.add('bg-gray-200', 'border-gray-400');
                    programSelection.classList.remove('text-gray-700');
                    programSelection.classList.add('text-gray-900');

                    // Change clear button icon
                    clearProgramFilterBtn.classList.remove('fi-rr-caret-down', 'text-gray-500');
                    clearProgramFilterBtn.classList.add('fi-bs-cross-small', 'cursor-pointer',
                        'text-gray-700');

                    handleClearProgramFilter();
                }

                //console.log(id);
            })

            pageLengthSelection.addEventListener('change', (e) => {

                let selectedPageLength = parseInt(e.target.value, 10);

                window.selectedPageLength = selectedPageLength; // Set global variable for DataTable
                sectionsTable.page.len(selectedPageLength).draw();

                //console.log(id);
            })

            gradeSelection.addEventListener('change', (e) => {

                let selectedOption = e.target.selectedOptions[0];
                let email = selectedOption.getAttribute('data-putanginamo');

                selectedGrade = email;
                window.selectedGrade = email; // Set global variable for DataTable
                sectionsTable.draw();

                let clearGradeFilterRem = ['text-gray-500', 'fi-rr-caret-down'];
                let clearGradeFilterAdd = ['fi-bs-cross-small', 'cursor-pointer', 'text-gray-700'];
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

            function handleClearGradeFilter(selectedOption) {

                clearGradeFilterBtn.addEventListener('click', () => {

                    gradeContainer.classList.remove('bg-gray-200', 'border-gray-400', 'hover:bg-gray-300')
                    clearGradeFilterBtn.classList.remove('fi-bs-cross-small');

                    clearGradeFilterBtn.classList.add('fi-rr-caret-down');
                    gradeContainer.classList.add('bg-gray-100', 'border-[#1e1e1e]/10')
                    gradeSelection.classList.remove('text-gray-900')
                    gradeSelection.classList.add('text-gray-700')
                    clearGradeFilterBtn.classList.remove('text-gray-700')
                    clearGradeFilterBtn.classList.add('text-gray-500')


                    gradeSelection.selectedIndex = 0
                    selectedGrade = '';
                    window.selectedGrade = ''; // Clear global variable for DataTable
                    sectionsTable.draw();
                })

            }

            function handleClearProgramFilter() {
                clearProgramFilterBtn.addEventListener('click', () => {
                    let programContainer = programSelection.closest('.flex');

                    programContainer.classList.remove('bg-gray-200', 'border-gray-400');
                    programContainer.classList.add('bg-gray-100', 'border-[#1e1e1e]/10');
                    programSelection.classList.remove('text-gray-900');
                    programSelection.classList.add('text-gray-700');
                    clearProgramFilterBtn.classList.remove('fi-bs-cross-small', 'cursor-pointer',
                        'text-gray-700');
                    clearProgramFilterBtn.classList.add('fi-rr-caret-down', 'text-gray-500');

                    programSelection.selectedIndex = 0;
                    selectedProgram = '';
                    window.selectedProgram = ''; // Clear global variable for DataTable
                    sectionsTable.draw();
                });
            }

            window.onload = function() {
                gradeSelection.selectedIndex = 0
                programSelection.selectedIndex = 0
                pageLengthSelection.selectedIndex = 0
            }


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
