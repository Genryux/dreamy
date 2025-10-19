@extends('layouts.admin')

@section('header')
    <div class="flex flex-col justify-center items-start text-start px-[14px] py-2">
        <h1 class="text-[20px] font-black">Subject List</h1>
        <p class="text-[14px] text-gray-900/60">View and manage subject list.
        </p>
    </div>
@endsection
@section('modal')
    <x-modal modal_id="create-subject-modal" modal_name="Create Subject" close_btn_id="create-subject-modal-close-btn"
        modal_container_id="modal-container-1">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-book flex justify-center items-center'></i>
        </x-slot>

        <div class="max-h-[70vh] overflow-y-auto">
            <form id="create-subject-form" class="p-6">
                @csrf
                <div class="space-y-6">
                    <!-- Subject Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fi fi-rr-book mr-2"></i>
                            Subject Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" placeholder="e.g., Mathematics, English" required
                            class="w-full border-2 border-gray-300 bg-gray-100 rounded-lg px-3 py-2 outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 transition duration-200 placeholder:italic placeholder:text-[14px] text-[14px]">
                    </div>

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
                                <option value="{{ $program->id }}">
                                    {{ $program->name }} ({{ $program->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Grade Level -->
                    <div>
                        <label for="grade_level" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fi fi-rr-calendar mr-2"></i>
                            Grade Level <span class="text-red-500">*</span>
                        </label>
                        <select name="grade_level" id="grade_level" required
                            class="w-full border-2 border-gray-300 bg-gray-100 rounded-lg px-3 py-2 outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 transition duration-200 placeholder:italic placeholder:text-[14px] text-[14px]">
                            <option value="">Select Grade Level</option>
                            <option value="Grade 11">Grade 11</option>
                            <option value="Grade 12">Grade 12</option>
                        </select>
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fi fi-rr-tag mr-2"></i>
                            Category <span class="text-red-500">*</span>
                        </label>
                        <select name="category" id="category" required
                            class="w-full border-2 border-gray-300 bg-gray-100 rounded-lg px-3 py-2 outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 transition duration-200 placeholder:italic placeholder:text-[14px] text-[14px]">
                            <option value="">Select Category</option>
                            <option value="core">Core</option>
                            <option value="applied">Applied</option>
                            <option value="specialized">Specialized</option>
                        </select>
                    </div>

                    <!-- Semester -->
                    <div>
                        <label for="semester" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fi fi-rr-clock mr-2"></i>
                            Semester <span class="text-red-500">*</span>
                        </label>
                        <select name="semester" id="semester" required
                            class="w-full border-2 border-gray-300 bg-gray-100 rounded-lg px-3 py-2 outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 transition duration-200 placeholder:italic placeholder:text-[14px] text-[14px]">
                            <option value="">Select Semester</option>
                            <option value="1st Semester">1st Semester</option>
                            <option value="2nd Semester">2nd Semester</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>

        <x-slot name="modal_buttons">
            <button id="create-subject-cancel-btn"
                class="bg-gray-50 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-100 hover:ring hover:ring-gray-200 transition duration-150">
                Cancel
            </button>
            <button type="submit" form="create-subject-form" name="action" value="create-subject"
                class="self-end flex flex-row justify-center items-center bg-[#199BCF] py-2 px-3 rounded-xl text-[14px] font-semibold gap-2 text-white hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                Create Subject
            </button>
        </x-slot>

    </x-modal>

    {{-- edit subject --}}
    <x-modal modal_id="edit-subject-modal" modal_name="Edit Subject" close_btn_id="edit-subject-modal-close-btn"
        modal_container_id="modal-container-2">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-book flex justify-center items-center'></i>
        </x-slot>

        <form id="edit-subject-modal-form" class="p-6">
            @csrf
            <div class="space-y-6">
                <!-- Subject Name -->
                <div>
                    <label for="edit_name" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-book mr-2"></i>
                        Subject Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="edit_name" placeholder="e.g., Mathematics, English" required
                        class="w-full border-2 border-gray-300 bg-gray-100 rounded-lg px-3 py-2 outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 transition duration-200 placeholder:italic placeholder:text-[14px] text-[14px]">
                </div>

                <!-- Program Selection -->
                <div>
                    <label for="edit_program_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-graduation-cap mr-2"></i>
                        Program <span class="text-red-500">*</span>
                    </label>
                    <select name="program_id" id="edit_program_id" required
                        class="w-full border-2 border-gray-300 bg-gray-100 rounded-lg px-3 py-2 outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 transition duration-200 placeholder:italic placeholder:text-[14px] text-[14px]">
                        <option value="">Select Program</option>
                        @foreach ($programs as $program)
                            <option value="{{ $program->id }}">
                                {{ $program->name }} ({{ $program->code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Grade Level -->
                <div>
                    <label for="edit_grade_level" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-calendar mr-2"></i>
                        Grade Level <span class="text-red-500">*</span>
                    </label>
                    <select name="grade_level" id="edit_grade_level" required
                        class="w-full border-2 border-gray-300 bg-gray-100 rounded-lg px-3 py-2 outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 transition duration-200 placeholder:italic placeholder:text-[14px] text-[14px]">
                        <option value="">Select Grade Level</option>
                        <option value="Grade 11">Grade 11</option>
                        <option value="Grade 12">Grade 12</option>
                    </select>
                </div>

                <!-- Category -->
                <div>
                    <label for="edit_category" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-tag mr-2"></i>
                        Category <span class="text-red-500">*</span>
                    </label>
                    <select name="category" id="edit_category" required
                        class="w-full border-2 border-gray-300 bg-gray-100 rounded-lg px-3 py-2 outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 transition duration-200 placeholder:italic placeholder:text-[14px] text-[14px]">
                        <option value="">Select Category</option>
                        <option value="core">Core</option>
                        <option value="applied">Applied</option>
                        <option value="specialized">Specialized</option>
                    </select>
                </div>

                <!-- Semester -->
                <div>
                    <label for="edit_semester" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-clock mr-2"></i>
                        Semester <span class="text-red-500">*</span>
                    </label>
                    <select name="semester" id="edit_semester" required
                        class="w-full border-2 border-gray-300 bg-gray-100 rounded-lg px-3 py-2 outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 transition duration-200 placeholder:italic placeholder:text-[14px] text-[14px]">
                        <option value="">Select Semester</option>
                        <option value="1st Semester">1st Semester</option>
                        <option value="2nd Semester">2nd Semester</option>
                    </select>
                </div>
            </div>
        </form>

        <x-slot name="modal_buttons">
            <button id="edit-subject-cancel-btn"
                class="bg-gray-50 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-100 hover:ring hover:ring-gray-200 transition duration-150">
                Cancel
            </button>
            <button type="submit" form="edit-subject-modal-form" id="edit-subject-submit-btn"
                class="bg-[#199BCF] py-2 px-3 rounded-xl text-[14px] font-semibold gap-2 text-white hover:ring hover:ring-[#C8A165]/20 hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                Update Subject
            </button>
        </x-slot>

    </x-modal>

    {{-- delete subject --}}
    <x-modal modal_id="delete-subject-modal" modal_name="Delete Subject" close_btn_id="delete-subject-close-btn"
        modal_container_id="modal-container-delete-subject">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-trash flex justify-center items-center text-red-500'></i>
        </x-slot>

        <div class="p-6">
            <div class="flex flex-col items-center space-y-4">
                <div class="text-center">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Confirm Deletion</h3>
                    <p class="text-gray-600">Are you sure you want to delete this subject? This action cannot be undone.
                    </p>
                </div>
            </div>
        </div>

        <x-slot name="modal_buttons">
            <button id="delete-subject-cancel-btn"
                class="bg-gray-50 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-100 hover:ring hover:ring-gray-200 transition duration-150">
                Cancel
            </button>
            <form id="delete-subject-form" class="inline">
                @csrf
                <button type="submit" id="delete-subject-submit-btn"
                    class="bg-red-500 text-[14px] px-3 py-2 rounded-xl text-white font-bold hover:ring hover:ring-red-200 hover:bg-red-400 transition duration-150 shadow-sm hover:scale-95">
                    Delete Subject
                </button>
            </form>
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
                    Subjects
                </span>
                <span class="font-medium text-gray-400 text-[14px]">
                    Subject list across different programs and grade levels
                </span>
            </div>
            <div class="flex flex-row justify-between items-center w-full h-full py-2">

                <div class="flex flex-row justify-between w-3/4 items-center gap-4">

                    <label for="myCustomSearch"
                        class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-2 gap-2 w-[40%] hover:ring hover:ring-blue-200 focus-within:ring focus-within:ring-blue-100 focus-within:border-blue-500 transition duration-150 shadow-sm">
                        <i class="fi fi-rs-search flex justify-center items-center text-[#1e1e1e]/60 text-[16px]"></i>
                        <input type="search" name="" id="myCustomSearch"
                            class="my-custom-search bg-transparent outline-none text-[14px] w-full peer"
                            placeholder="Search by subject name, category, program, etc.">
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
                            <i id="clear-entries-filter-btn"
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

                        <div
                            class="flex flex-row justify-between items-center rounded-lg border border-[#1e1e1e]/10 bg-gray-100 px-3 py-2 gap-2 hover:bg-gray-200 hover:border-[#1e1e1e]/15 transition-all ease-in-out duration-150 shadow-sm">
                            <select name="category_selection" id="category_selection"
                                class="appearance-none bg-transparent text-[14px] font-medium text-gray-700 h-full w-full cursor-pointer">
                                <option value="" disabled selected>Category</option>
                                <option value="" data-category="core">Core</option>
                                <option value="" data-category="applied">Applied</option>
                                <option value="" data-category="specialized">Specialized</option>
                            </select>
                            <i id="clear-category-filter-btn"
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


                    </div>
                </div>

                @can('create subject')
                    <button id="create-subject-modal-btn"
                        class="self-end flex flex-row justify-center items-center bg-[#199BCF] py-2 px-3 rounded-xl text-[16px] font-semibold gap-2 text-white hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                        <i class="fi fi-sr-square-plus opacity-70 flex justify-center items-center text-[18px]"></i>
                        New Subject
                    </button>
                @endcan
            </div>

            <div class="w-full">
                <table id="subjects" class="w-full table-fixed">
                    <thead class="text-[14px]">
                        <tr>
                            <th class="text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                <span class="mr-2 font-medium opacity-60 cursor-pointer">#</span>
                            </th>
                            <th class="w-1/6 text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                <span class="mr-2 font-medium opacity-60 cursor-pointer">Subject Name</span>
                                <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                            </th>
                            <th class="w-1/6 text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                <span class="mr-2 font-medium opacity-60 cursor-pointer">Category</span>
                                <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                            </th>
                            <th class="w-1/6 text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                <span class="mr-2 font-medium opacity-60 cursor-pointer">Program</span>
                                <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                            </th>
                            <th class="w-1/6 text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                <span class="mr-2 font-medium opacity-60 cursor-pointer">Grade Level</span>
                                <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                            </th>
                            <th class="w-1/6 text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                <span class="mr-2 font-medium opacity-60 cursor-pointer">Semester</span>
                                <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                            </th>
                            <th class="w-1/6 text-center bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10  px-4 py-2">
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

        let subjectsTable;
        let selectedGrade = '';
        let selectedProgram = '';
        let selectedCategory = '';
        let selectedPageLength = '';

        document.addEventListener("DOMContentLoaded", function() {

            initModal('create-subject-modal', 'create-subject-modal-btn', 'create-subject-modal-close-btn',
                'create-subject-cancel-btn',
                'modal-container-1');

            // const fileInput = document.getElementById('fileInput');
            // const fileName = document.getElementById('fileName');

            // fileInput.addEventListener('change', function() {
            //     fileName.textContent = this.files.length > 0 ? this.files[0].name : 'No file chosen';
            // });

            //Overriding default search input
            let subjectsTable = initCustomDataTable(
                'subjects',
                `/getAllSubjects`,
                [{
                        data: 'index'
                    },
                    {
                        data: 'name',
                        render: DataTable.render.text()
                    },
                    {
                        data: 'category',
                        render: DataTable.render.text()
                    },
                    {
                        data: 'program',
                        render: DataTable.render.text()
                    },
                    {
                        data: 'grade_level',
                        render: DataTable.render.text()
                    },
                    {
                        data: 'semester',
                        render: DataTable.render.text()
                    },
                    {
                        data: 'id',
                        render: function(data, type, row) {
                            return `
                            <div class='flex flex-row justify-center items-center gap-2'>
                                <button type="button" id="open-edit-subject-btn-${data}"
                                    data-subject-id="${data}"
                                    class="edit-subject-btn group relative inline-flex items-center gap-2 bg-blue-100 text-blue-500 font-semibold px-3 py-2 rounded-xl hover:bg-blue-500 hover:ring hover:ring-blue-200 hover:text-white transition duration-150">
                                    <i class="fi fi-rr-edit text-[16px] flex justify-center items-center"></i>
                                    Edit
                                </button>
                                <button type="button" id="open-delete-modal-btn-${data}"
                                    data-subject-id="${data}"
                                    class="delete-subject-btn group relative inline-flex items-center gap-2 bg-red-100 text-red-500 font-semibold px-3 py-2 rounded-xl hover:bg-red-500 hover:ring hover:ring-red-200 hover:text-white transition duration-150">
                                    <i class="fi fi-rr-trash text-[16px] flex justify-center items-center"></i>
                                    Delete
                                </button>
                            </div>`;
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
                        width: '20%',
                        targets: 1
                    },
                    {
                        width: '15%',
                        targets: 2
                    },
                    {
                        width: '15%',
                        targets: 3
                    },
                    {
                        width: '15%',
                        targets: 4
                    },
                    {
                        width: '15%',
                        targets: 5
                    },
                    {
                        width: '15%',
                        targets: 6,
                        className: 'text-center'
                    }
                ]
            );

            clearSearch('clear-btn', 'myCustomSearch', subjectsTable)

            // Initialize edit and delete modals dynamically
            initializeEditSubjectModals();
            initializeDeleteSubjectModals();

            // Reinitialize modals after table draw
            subjectsTable.on('draw', function() {
                initializeEditSubjectModals();
                initializeDeleteSubjectModals();
            });

            document.getElementById('create-subject-form').addEventListener('submit', function(e) {
                e.preventDefault();

                let form = e.target;
                let formData = new FormData(form);

                showLoader();

                fetch(`/subjects`, {
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
                            closeModal('create-subject-modal',
                                'modal-container-1');
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        } else {
                            showAlert('error', data.message || 'Unknown error');
                            closeModal('create-subject-modal',
                                'modal-container-1');
                        }
                    })
                    .catch(error => {
                        hideLoader();
                        console.error('Error:', error);
                        showAlert('error', 'An error occurred while creating the subject');
                    });
            });

            // Edit Subject Form Submission
            document.getElementById('edit-subject-modal-form').addEventListener('submit', function(e) {
                e.preventDefault();

                let form = e.target;
                let formData = new FormData(form);
                const subjectId = formData.get('subject_id');

                if (!subjectId) {
                    showAlert('error', 'Subject ID not found');
                    return;
                }

                // Add the subject ID to the form data
                formData.append('_method', 'PUT');

                // Show loader
                showLoader("Updating subject...");

                fetch(`/subjects/${subjectId}`, {
                        method: "POST",
                        body: formData,
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Accept": "application/json"
                        }
                    })
                    .then(response => {
                        console.log('Response status:', response.status);
                        return response.json();
                    })
                    .then(data => {
                        hideLoader();

                        console.log('Response data:', data);

                        if (data.success) {
                            // Reset form
                            form.reset();

                            // Remove hidden input
                            const hiddenInput = document.getElementById('edit_subject_id');
                            if (hiddenInput) {
                                hiddenInput.remove();
                            }

                            // Close modal
                            closeModal('edit-subject-modal', 'modal-container-2');

                            // Show success alert
                            showAlert('success', 'Subject updated successfully!');

                            // Refresh table
                            if (typeof subjectsTable !== 'undefined') {
                                subjectsTable.draw();
                            }

                        } else if (data.error) {
                            closeModal('edit-subject-modal', 'modal-container-2');
                            showAlert('error', data.error);
                        } else if (data.message) {
                            closeModal('edit-subject-modal', 'modal-container-2');
                            showAlert('error', data.message);
                        }
                    })
                    .catch(err => {
                        hideLoader();
                        console.error('Error:', err);
                        closeModal('edit-subject-modal', 'modal-container-2');
                        showAlert('error', 'Something went wrong while updating the subject');
                    });
            });

            // Delete Subject Form Submission
            const deleteSubjectForm = document.getElementById('delete-subject-form');
            if (deleteSubjectForm) {
                deleteSubjectForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(this);
                    const subjectId = formData.get('subject_id');

                    showLoader();
                    fetch(`/subjects/${subjectId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            hideLoader();
                            if (data.success === false && data.has_section_subjects === true) {
                                showAlert('error', data.error);
                                closeModal('delete-subject-modal', 'modal-container-delete-subject');
                            } else if (data.success === true) {
                                showAlert('success', data.message);
                                subjectsTable.draw(); // Refresh the table

                                // Close modal
                                document.getElementById('delete-subject-close-btn').click();
                            } else {
                                showAlert('error', data.message);
                            }
                        })
                        .catch(error => {
                            hideLoader();
                            console.error('Error:', error);
                            showAlert('error', 'An error occurred while deleting the subject');
                        });
                });
            }

            let programSelection = document.querySelector('#program_selection');
            let gradeSelection = document.querySelector('#grade_selection');
            let categorySelection = document.querySelector('#category_selection');
            let pageLengthSelection = document.querySelector('#page-length-selection');

            let clearGradeFilterBtn = document.querySelector('#clear-grade-filter-btn');
            let gradeContainer = document.querySelector('#grade_selection_container');
            let clearProgramFilterBtn = document.querySelector('#clear-program-filter-btn');
            let clearCategoryFilterBtn = document.querySelector('#clear-category-filter-btn');
            let categoryContainer = document.querySelector('#category_selection').closest('.flex');

            programSelection.addEventListener('change', (e) => {

                let selectedOption = e.target.selectedOptions[0];
                let id = selectedOption.getAttribute('data-id');

                selectedProgram = id;
                window.selectedProgram = id; // Set global variable for DataTable
                subjectsTable.draw();

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
                subjectsTable.page.len(selectedPageLength).draw();

                //console.log(id);
            })

            gradeSelection.addEventListener('change', (e) => {

                let selectedOption = e.target.selectedOptions[0];
                let grade = selectedOption.getAttribute('data-grade');

                selectedGrade = grade;
                window.selectedGrade = grade; // Set global variable for DataTable
                subjectsTable.draw();

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

            categorySelection.addEventListener('change', (e) => {

                let selectedOption = e.target.selectedOptions[0];
                let category = selectedOption.getAttribute('data-category');

                selectedCategory = category;
                window.selectedCategory = category; // Set global variable for DataTable
                subjectsTable.draw();

                let clearCategoryFilterRem = ['text-gray-500', 'fi-rr-caret-down'];
                let clearCategoryFilterAdd = ['fi-bs-cross-small', 'cursor-pointer', 'text-gray-700'];
                let categorySelectionRem = ['border-[#1e1e1e]/10', 'text-gray-700'];
                let categorySelectionAdd = ['text-gray-900'];
                let categoryContainerRem = ['bg-gray-100'];
                let categoryContainerAdd = ['bg-gray-200', 'border-gray-400', 'hover:bg-gray-300'];

                clearCategoryFilterBtn.classList.remove(...clearCategoryFilterRem);
                clearCategoryFilterBtn.classList.add(...clearCategoryFilterAdd);
                categorySelection.classList.remove(...categorySelectionRem);
                categorySelection.classList.add(...categorySelectionAdd);
                categoryContainer.classList.remove(...categoryContainerRem);
                categoryContainer.classList.add(...categoryContainerAdd);

                handleClearCategoryFilter(selectedOption)
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
                    subjectsTable.draw();
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
                    subjectsTable.draw();
                });
            }

            function handleClearCategoryFilter(selectedOption) {

                clearCategoryFilterBtn.addEventListener('click', () => {

                    categoryContainer.classList.remove('bg-gray-200', 'border-gray-400', 'hover:bg-gray-300')
                    clearCategoryFilterBtn.classList.remove('fi-bs-cross-small');

                    clearCategoryFilterBtn.classList.add('fi-rr-caret-down');
                    categoryContainer.classList.add('bg-gray-100', 'border-[#1e1e1e]/10')
                    categorySelection.classList.remove('text-gray-900')
                    categorySelection.classList.add('text-gray-700')
                    clearCategoryFilterBtn.classList.remove('text-gray-700')
                    clearCategoryFilterBtn.classList.add('text-gray-500')


                    categorySelection.selectedIndex = 0
                    selectedCategory = '';
                    window.selectedCategory = ''; // Clear global variable for DataTable
                    subjectsTable.draw();
                })

            }

            window.onload = function() {
                gradeSelection.selectedIndex = 0
                programSelection.selectedIndex = 0
                categorySelection.selectedIndex = 0
                pageLengthSelection.selectedIndex = 0
            }

            // Initialize edit subject modals dynamically
            function initializeEditSubjectModals() {
                document.querySelectorAll('.edit-subject-btn').forEach((button) => {
                    let subjectId = button.getAttribute('data-subject-id');
                    let buttonId = `open-edit-subject-btn-${subjectId}`;

                    // Initialize modal for this specific button
                    initModal('edit-subject-modal', buttonId, 'edit-subject-modal-close-btn',
                        'edit-subject-cancel-btn', 'modal-container-2');

                    button.addEventListener('click', () => {
                        // Clear any existing hidden inputs first
                        let form = document.getElementById('edit-subject-modal-form');
                        let existingInputs = form.querySelectorAll('input[name="subject_id"]');
                        existingInputs.forEach(input => input.remove());

                        // Add subject ID as hidden input
                        let subjectIdInput = document.createElement('input');
                        subjectIdInput.type = 'hidden';
                        subjectIdInput.value = subjectId;
                        subjectIdInput.name = "subject_id";
                        subjectIdInput.id = "edit_subject_id";
                        form.appendChild(subjectIdInput);

                        // Fetch subject data and populate the form
                        showLoader();
                        fetch(`/subjects/${subjectId}`)
                            .then(response => response.json())
                            .then(data => {
                                hideLoader();
                                if (data.success && data.subject) {
                                    const subject = data.subject;

                                    // Populate form fields
                                    document.getElementById('edit-subject-modal-form').querySelector(
                                        'input[name="name"]').value = subject.name || '';
                                    document.getElementById('edit-subject-modal-form').querySelector(
                                        'select[name="program_id"]').value = subject.program_id || '';
                                    document.getElementById('edit-subject-modal-form').querySelector(
                                        'select[name="grade_level"]').value = subject.grade_level || '';
                                    document.getElementById('edit-subject-modal-form').querySelector(
                                        'select[name="category"]').value = subject.category || '';
                                    document.getElementById('edit-subject-modal-form').querySelector(
                                        'select[name="semester"]').value = subject.semester || '';

                                    console.log('Edit modal opened for subject ID:', subjectId);
                                } else {
                                    showAlert('error', 'Error loading subject: ' + data.error);
                                }
                            })
                            .catch(error => {
                                hideLoader();
                                console.error('Error:', error);
                                showAlert('error', 'An error occurred while loading the subject');
                            });
                    });
                });
            }

            // Initialize delete subject modals dynamically
            function initializeDeleteSubjectModals() {
                document.querySelectorAll('.delete-subject-btn').forEach((button) => {
                    let subjectId = button.getAttribute('data-subject-id');
                    let buttonId = `open-delete-modal-btn-${subjectId}`;

                    // Initialize modal for this specific button
                    initModal('delete-subject-modal', buttonId, 'delete-subject-close-btn',
                        'delete-subject-cancel-btn', 'modal-container-delete-subject');

                    button.addEventListener('click', () => {
                        // Clear any existing hidden inputs first
                        let form = document.getElementById('delete-subject-form');
                        let existingInputs = form.querySelectorAll('input[name="subject_id"]');
                        existingInputs.forEach(input => input.remove());

                        // Set the form action dynamically
                        form.action = `/subjects/${subjectId}`;

                        // Add subject ID as hidden input
                        let subjectIdInput = document.createElement('input');
                        subjectIdInput.type = 'hidden';
                        subjectIdInput.name = 'subject_id';
                        subjectIdInput.value = subjectId;
                        form.appendChild(subjectIdInput);

                        console.log('Delete modal opened for subject ID:', subjectId);
                    });
                });
            }


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
