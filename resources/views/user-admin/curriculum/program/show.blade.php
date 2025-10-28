@extends('layouts.admin', ['title' => 'Curriculum'])
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
                <a href="/tracks" class="block transition-colors hover:text-gray-900"> Tracks </a>
            </li>

            <li class="rtl:rotate-180">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
            </li>

            <li>
                <a href="#" class="block transition-colors hover:text-gray-900">
                    {{ $program->code . ' - ' . $program->name }}
                </a>
            </li>

        </ol>
        {{-- <div class="flex flex-row justify-center items-center h-full">
            <div id="dropdown_btn"
                class="relative space-y-10 h-full flex flex-col justify-center items-center gap-4 cursor-pointer">

                <div
                    class="group relative inline-flex items-center gap-2 border border-[#1e1e1e]/0 text-gray-700 font-semibold py-2 px-3 rounded-lg hover:shadow-sm hover:bg-gray-100 hover:border-[#1e1e1e]/15 transition ease-out duration-300">
                    <i class="fi fi-br-menu-dots flex justify-center items-center"></i>
                </div>

                <div id="dropdown_selection"
                    class="absolute top-0 right-0 z-10 bg-[#f8f8f8] flex-col justify-center items-center gap-4 rounded-lg shadow-md border border-[#1e1e1e]/15 py-2 px-1 opacity-0 scale-95 pointer-events-none transition-all duration-200 ease-out translate-y-1">
                    <button id="edit-section-modal-btn"
                        class="flex-1 flex justify-start items-center px-8 py-2 gap-2 text-[14px] font-medium opacity-80 w-full border-b border-[#1e1e1e]/15 hover:bg-gray-200 truncate">
                        <i class="fi fi-rr-pen-clip text-[16px] flex justify-center item-center"></i>Edit Section
                    </button>
                    <x-nav-link href="/students/export/excel"
                        class="flex-1 flex justify-start items-center px-8 py-2 gap-2 text-[14px] font-medium opacity-80 w-full border-b border-[#1e1e1e]/15 hover:bg-red-200 hover:text-red-500 truncate">
                        <i class="fi fi-rr-remove-user text-[16px] flex justify-center item-center"></i>Remove Student
                    </x-nav-link>
                    <button
                        class="flex-1 flex justify-start items-center px-8 py-2 gap-2 text-[14px] font-medium opacity-80 w-full border-b border-[#1e1e1e]/15 hover:bg-gray-200 truncate">
                        <i class="fi fi-rr-box text-[16px] flex justify-center item-center"></i>Archive Section
                    </button>
                    <button
                        class="flex-1 flex justify-start items-center px-8 py-2 gap-2 text-[14px] font-medium opacity-80 w-full hover:bg-gray-200 truncate">
                        <i class="fi fi-rr-trash text-[16px] flex justify-center item-center"></i>Delete Section
                    </button>
                </div>

            </div>
        </div> --}}
    </nav>
@endsection
@section('modal')
    <x-modal modal_id="edit-program-modal" modal_name="Edit Program" close_btn_id="edit-program-modal-close-btn"
        modal_container_id="modal-container-2">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-edit flex justify-center items-center '></i>
        </x-slot>

        <form id="edit-program-form" class="p-6">
            @csrf
            <div class="space-y-6">
                <!-- Program Code -->
                <div>
                    <label for="program_code" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-tags mr-2"></i>
                        Program Code <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="code" id="program_code" required placeholder="e.g., STEM, ABM, HUMSS"
                        class="w-full border-2 border-gray-300 bg-gray-100 rounded-lg px-3 py-2 outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 transition duration-200 placeholder:italic placeholder:text-[14px] text-[14px]">
                </div>

                <!-- Program Name/Description -->
                <div>
                    <label for="program_name" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-graduation-cap mr-2"></i>
                        Program Description <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="program_name" required
                        placeholder="e.g., Science, Technology, Engineering and Mathematics"
                        class="w-full border-2 border-gray-300 bg-gray-100 rounded-lg px-3 py-2 outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 transition duration-200 placeholder:italic placeholder:text-[14px] text-[14px]">
                </div>

                <!-- Program Track -->
                <div>
                    <label for="program_track" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-school mr-2"></i>
                        Program Track
                    </label>
                    <input type="text" name="track" id="program_track"
                        placeholder="e.g., Academic, Technical-Vocational, Sports, Arts and Design"
                        class="w-full border-2 border-gray-300 bg-gray-100 rounded-lg px-3 py-2 outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 transition duration-200 placeholder:italic placeholder:text-[14px] text-[14px]">
                </div>

                <!-- Program Status -->
                <div>
                    <label for="program_status" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-check-circle mr-2"></i>
                        Status
                    </label>
                    <select name="status" id="program_status"
                        class="w-full border-2 border-gray-300 bg-gray-100 rounded-lg px-3 py-2 outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 transition duration-200 placeholder:italic placeholder:text-[14px] text-[14px]">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
        </form>

        <x-slot name="modal_buttons">
            <button id="cancel-btn"
                class="bg-gray-50 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-100 hover:ring hover:ring-gray-200 transition duration-150">
                Cancel
            </button>
            {{-- This button will acts as the submit button --}}
            <button type="submit" form="edit-program-form"
                class="self-end flex flex-row justify-center items-center bg-[#199BCF] py-2 px-3 rounded-xl text-[14px] font-semibold gap-2 text-white hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                Update
            </button>
        </x-slot>

    </x-modal>
    <x-modal modal_id="delete-program-modal" modal_name="Delete Strand" close_btn_id="delete-program-close-btn"
        modal_container_id="modal-container-delete-program">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-trash flex justify-center items-center text-red-500'></i>
        </x-slot>

        <div class="p-6">
            <div class="flex flex-col items-center space-y-4">
                <div class="text-center">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Confirm Deletion</h3>
                    <p class="text-gray-600">Are you sure you want to delete this strand? This action cannot be undone.
                    </p>
                </div>
            </div>
        </div>

        <x-slot name="modal_buttons">
            <button id="delete-program-cancel-btn"
                class="bg-gray-50 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-100 hover:ring hover:ring-gray-200 transition duration-150">
                Cancel
            </button>
            <form id="delete-program-form" class="inline">
                @csrf
                <button type="submit" id="delete-program-submit-btn"
                    class="bg-red-500 text-[14px] px-3 py-2 rounded-xl text-white font-bold hover:ring hover:ring-red-200 hover:bg-red-400 transition duration-150 shadow-sm hover:scale-95">
                    Delete Strand
                </button>
            </form>
        </x-slot>

    </x-modal>
    <x-modal modal_id="create-section-modal" modal_name="Create Section" close_btn_id="create-section-modal-close-btn"
        modal_container_id="modal-container-1">

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
                            @foreach ($programs as $prog)
                                <option value="{{ $prog->id }}" {{ $prog->id == $program->id ? 'selected' : '' }}>
                                    {{ $prog->name }} ({{ $prog->code }})
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
                        <input type="text" name="section_code" id="section_code" placeholder="e.g., 11-HUMSS-A"
                            required
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
    <x-modal modal_id="create-subject-modal" modal_name="Create Subject" close_btn_id="create-subject-modal-close-btn"
        modal_container_id="modal-container-subject">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-book flex justify-center items-center '></i>
        </x-slot>

        <div class="max-h-[70vh] overflow-y-auto">
            <form id="create-subject-form" class="p-6">
                @csrf
                <div class="space-y-6">
                    <!-- Subject Name -->
                    <div>
                        <label for="subject_name" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fi fi-rr-book mr-2"></i>
                            Subject Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="subject_name" required
                            placeholder="e.g., Mathematics, English, Science"
                            class="w-full border-2 border-gray-300 bg-gray-100 rounded-lg px-3 py-2 outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 transition duration-200 placeholder:italic placeholder:text-[14px] text-[14px]">
                    </div>

                    <!-- Program Selection -->
                    <div>
                        <label for="subject_program_id" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fi fi-rr-graduation-cap mr-2"></i>
                            Program <span class="text-red-500">*</span>
                        </label>
                        <select name="program_id" id="subject_program_id" required
                            class="w-full border-2 border-gray-300 bg-gray-100 rounded-lg px-3 py-2 outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 transition duration-200 placeholder:italic placeholder:text-[14px] text-[14px]">
                            <option value="">Select Program</option>
                            @foreach ($programs as $prog)
                                <option value="{{ $prog->id }}" {{ $prog->id == $program->id ? 'selected' : '' }}>
                                    {{ $prog->name }} ({{ $prog->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Grade Level -->
                    <div>
                        <label for="subject_grade_level" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fi fi-rr-calendar mr-2"></i>
                            Grade Level <span class="text-red-500">*</span>
                        </label>
                        <select name="grade_level" id="subject_grade_level" required
                            class="w-full border-2 border-gray-300 bg-gray-100 rounded-lg px-3 py-2 outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 transition duration-200 placeholder:italic placeholder:text-[14px] text-[14px]">
                            <option value="">Select Grade Level</option>
                            <option value="Grade 11">Grade 11</option>
                            <option value="Grade 12">Grade 12</option>
                        </select>
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="subject_category" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fi fi-rr-tags mr-2"></i>
                            Category <span class="text-red-500">*</span>
                        </label>
                        <select name="category" id="subject_category" required
                            class="w-full border-2 border-gray-300 bg-gray-100 rounded-lg px-3 py-2 outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 transition duration-200 placeholder:italic placeholder:text-[14px] text-[14px]">
                            <option value="">Select Category</option>
                            <option value="core">Core</option>
                            <option value="applied">Applied</option>
                            <option value="specialized">Specialized</option>
                        </select>
                    </div>

                    <!-- Semester -->
                    <div>
                        <label for="subject_semester" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fi fi-rr-calendar mr-2"></i>
                            Semester <span class="text-red-500">*</span>
                        </label>
                        <select name="semester" id="subject_semester" required
                            class="w-full border-2 border-gray-300 bg-gray-100 rounded-lg px-3 py-2 outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 transition duration-200 placeholder:italic placeholder:text-[14px] text-[14px]">
                            <option value="">Select Semester</option>
                            <option value="1st Semester">1st Semester</option>
                            <option value="2nd Semester">2nd Semester</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>

        <x-slot name="modal_info">
            <p class="text-[12px] text-gray-500 mt-2">
                <i class="fi fi-rr-info mr-1"></i>
                Core subjects are required for all programs, Applied subjects are program-specific, and Specialized subjects
                are track-specific.
            </p>
        </x-slot>

        <x-slot name="modal_buttons">
            <button id="create-subject-cancel-btn"
                class="bg-gray-50 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-100 hover:ring hover:ring-gray-200 transition duration-150">
                Cancel
            </button>
            {{-- This button will acts as the submit button --}}
            <button type="submit" form="create-subject-form" name="action" value="create-subject"
                class="self-end flex flex-row justify-center items-center bg-[#199BCF] py-2 px-3 rounded-xl text-[14px] font-semibold gap-2 text-white hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                Continue
            </button>
        </x-slot>

    </x-modal>
    <x-modal modal_id="edit-subject-modal" modal_name="Edit Subject" close_btn_id="edit-subject-modal-close-btn"
        modal_container_id="modal-container-edit-subject">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-edit flex justify-center items-center '></i>
        </x-slot>

        <div class="max-h-[70vh] overflow-y-auto">
            <form id="edit-subject-modal-form" class="p-6">
                @csrf
                <div class="space-y-6">
                    <!-- Subject Name -->
                    <div>
                        <label for="edit_subject_name" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fi fi-rr-book mr-2"></i>
                            Subject Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="edit_subject_name" required
                            placeholder="e.g., Mathematics, English, Science"
                            class="w-full border-2 border-gray-300 bg-gray-100 rounded-lg px-3 py-2 outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 transition duration-200 placeholder:italic placeholder:text-[14px] text-[14px]">
                    </div>

                    <!-- Program Selection -->
                    <div>
                        <label for="edit_subject_program_id" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fi fi-rr-graduation-cap mr-2"></i>
                            Program <span class="text-red-500">*</span>
                        </label>
                        <select name="program_id" id="edit_subject_program_id" required
                            class="w-full border-2 border-gray-300 bg-gray-100 rounded-lg px-3 py-2 outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 transition duration-200 placeholder:italic placeholder:text-[14px] text-[14px]">
                            <option value="">Select Program</option>
                            @foreach ($programs as $prog)
                                <option value="{{ $prog->id }}">
                                    {{ $prog->name }} ({{ $prog->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Grade Level -->
                    <div>
                        <label for="edit_subject_grade_level" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fi fi-rr-calendar mr-2"></i>
                            Grade Level <span class="text-red-500">*</span>
                        </label>
                        <select name="grade_level" id="edit_subject_grade_level" required
                            class="w-full border-2 border-gray-300 bg-gray-100 rounded-lg px-3 py-2 outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 transition duration-200 placeholder:italic placeholder:text-[14px] text-[14px]">
                            <option value="">Select Grade Level</option>
                            <option value="Grade 11">Grade 11</option>
                            <option value="Grade 12">Grade 12</option>
                        </select>
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="edit_subject_category" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fi fi-rr-tags mr-2"></i>
                            Category <span class="text-red-500">*</span>
                        </label>
                        <select name="category" id="edit_subject_category" required
                            class="w-full border-2 border-gray-300 bg-gray-100 rounded-lg px-3 py-2 outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 transition duration-200 placeholder:italic placeholder:text-[14px] text-[14px]">
                            <option value="">Select Category</option>
                            <option value="core">Core</option>
                            <option value="applied">Applied</option>
                            <option value="specialized">Specialized</option>
                        </select>
                    </div>

                    <!-- Semester -->
                    <div>
                        <label for="edit_subject_semester" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fi fi-rr-calendar mr-2"></i>
                            Semester <span class="text-red-500">*</span>
                        </label>
                        <select name="semester" id="edit_subject_semester" required
                            class="w-full border-2 border-gray-300 bg-gray-100 rounded-lg px-3 py-2 outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 transition duration-200 placeholder:italic placeholder:text-[14px] text-[14px]">
                            <option value="">Select Semester</option>
                            <option value="1st Semester">1st Semester</option>
                            <option value="2nd Semester">2nd Semester</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>

        <x-slot name="modal_info">
            <p class="text-[12px] text-gray-500 mt-2">
                <i class="fi fi-rr-info mr-1"></i>
                Core subjects are required for all programs, Applied subjects are program-specific, and Specialized subjects
                are track-specific.
            </p>
        </x-slot>

        <x-slot name="modal_buttons">
            <button id="edit-subject-cancel-btn"
                class="bg-gray-50 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-100 hover:ring hover:ring-gray-200 transition duration-150">
                Cancel
            </button>
            {{-- This button will acts as the submit button --}}
            <button type="submit" form="edit-subject-modal-form" name="action" value="edit-subject"
                class="self-end flex flex-row justify-center items-center bg-[#199BCF] py-2 px-3 rounded-xl text-[14px] font-semibold gap-2 text-white hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                Update
            </button>
        </x-slot>

    </x-modal>
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
@section('header')
    <div class="flex flex-row justify-between items-start text-start px-[14px] py-2">
        <div>
            <h1 class="text-[20px] font-black">Strand Details</h1>
            <p class="text-[14px]  text-gray-900/60">View and manage strand details, sections, subjects, and student
                enrollment for {{ $program->name }}.
            </p>
        </div>

        <div id="dropdown"
            class="relative space-y-10 h-full flex flex-col justify-start items-center gap-4 cursor-pointer">

            <div
                class="group relative inline-flex items-center gap-2 bg-gray-100 border border-[#1e1e1e]/10 text-gray-700 font-semibold py-2 px-3 rounded-lg shadow-sm hover:bg-gray-200 hover:border-[#1e1e1e]/15 transition duration-150">
                <i class="fi fi-br-menu-dots flex justify-center items-center text-[18px]"></i>
            </div>

            <div id="dropdown_selection"
                class="absolute top-0 right-0 z-10 bg-[#f8f8f8] flex-col justify-center items-center gap-1 rounded-lg shadow-md border border-[#1e1e1e]/15 py-2 px-1 opacity-0 scale-95 pointer-events-none transition-all duration-200 ease-out translate-y-1">
                <button id="edit-program-modal-btn"
                    class="flex-1 flex justify-start items-center px-8 py-2 gap-2 text-[14px] font-medium opacity-80 w-full border-b border-[#1e1e1e]/15 hover:bg-blue-50 hover:text-blue-400 truncate">
                    <i class="fi fi-rr-edit flex justify-center items-center text-[16px]"></i>Edit Program
                </button>
                <button id="delete-program-btn"
                    class="flex-1 flex justify-start items-center px-8 py-2 gap-2 text-[14px] font-medium opacity-80 w-full hover:bg-red-50 hover:text-red-400 truncate">
                    <i class="fi fi-rr-trash flex justify-center items-center text-[16px]"></i>Delete Program
                </button>
            </div>
        </div>

    </div>
@endsection
@section('stat')
    <div class="flex justify-center items-center">
        <div
            class="flex flex-col justify-center items-center flex-grow px-10 pb-10 pt-2 bg-gradient-to-br from-[#199BCF] to-[#1A3165] rounded-xl shadow-[#199BCF]/30 shadow-xl gap-2 text-white">

            <div class="flex flex-row items-center justify-between w-full gap-4 py-2 rounded-lg ">

                <div class="flex flex-col items-start justify-end gap-2 pt-4">
                    <h1 class="text-[40px] font-black" id="section_name">{{ $program->code }}</h1>
                    <p class="text-[16px]  text-white/60">{{ $program->name }}
                    </p>
                </div>

                <div class="flex flex-col items-end justify-center">
                    <p class="text-[50px] font-bold">{{ $program->getTotalSections() }}</p>
                    <div class="flex flex-row justify-center items-center opacity-70 gap-2 text-[14px]">
                        <p class="text-[16px]">Total Sections</p>
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
                    <p class="font-bold text-[24px]">{{ $totalStudents }}</p>
                    <p class="text-[12px] truncate text-gray-300">Total students enrolled in this program</p>
                </div>

                <div
                    class="flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-8 py-6 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-300">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-rr-user-plus flex flex-row justify-center items-center"></i>
                        <p class="text-[14px]">Unassigned Students</p>
                    </div>
                    <p class="font-bold text-[24px]">
                        {{ \App\Models\Student::where('program_id', $program->id)->whereNull('section_id')->count() }}</p>
                    <p class="text-[12px] truncate text-gray-300">Students without section assignment</p>
                </div>

                <div
                    class="flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-8 py-6 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-300">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-rr-school flex justify-center items-center"></i>
                        <p class="text-[14px]">Total Teachers</p>
                    </div>
                    <p class="font-bold text-[24px]">{{ $program->totalTeachers() }}</p>
                    <p class="text-[12px] truncate text-gray-300">Teachers assigned to this program</p>
                </div>

                <div
                    class="flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-8 py-6 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-300">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2 ">
                        <i class="fi fi-rr-book flex justify-center items-center"></i>
                        <p class="text-[14px] truncate">Total Subjects</p>
                    </div>
                    <p class="font-bold text-[24px]" id="totalSubjectsDisplay">{{ $program->getTotalSubjects() }}</p>
                    <p class="text-[12px] truncate text-gray-300">Subjects in this program</p>
                </div>
            </div>



        </div>
    </div>
@endsection
@section('content')
    <x-alert />

    <div
        class="px-5 text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:text-gray-400 dark:border-gray-300">
        <ul class="flex flex-wrap -mb-px">
            <li class="me-2">
                <a href="{{ route('program.sections', $program->id) }}"
                    class="inline-block p-4 border-b-2 rounded-t-lg 
              {{ Route::is('program.sections') ? 'text-blue-600 border-blue-600 dark:text-blue-500 dark:border-blue-500' : 'border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300' }}">
                    Sections
                </a>
            </li>

            <li class="me-2">
                <a href="{{ route('program.subjects', $program->id) }}"
                    class="inline-block p-4 border-b-2 rounded-t-lg 
              {{ Route::is('program.subjects') ? 'text-blue-600 border-blue-600 dark:text-blue-500 dark:border-blue-500' : 'border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300' }}">
                    Subjects
                </a>
            </li>

        </ul>
    </div>

    @if (Route::is('program.sections'))
        <div class="flex flex-row justify-center items-start gap-4">
            <div
                class="flex flex-col justify-start items-start flex-grow p-5 space-y-2 bg-[#f8f8f8] rounded-xl shadow-md border border-[#1e1e1e]/10 w-[40%]">
                <div class="flex flex-col my-2 justify-center items-center w-full">
                    <span class="font-semibold text-[18px]">
                        Sections
                    </span>
                    <span class="font-medium text-gray-400 text-[14px]">
                        Class sections and student enrollment for this program
                    </span>
                </div>
                <div class="flex flex-row justify-between items-center w-full h-full py-2">

                    <div class="flex flex-row justify-between w-3/4 items-center gap-4">

                        <label for="myCustomSearch"
                            class="flex flex-row justify-start items-center border-2 border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-2 gap-2 w-full outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 focus-within:shadow-lg transition duration-150 shadow-sm">
                            <i class="fi fi-rs-search flex justify-center items-center text-[#1e1e1e]/60 text-[16px]"></i>
                            <input type="search" name="" id="myCustomSearch"
                                class="my-custom-search bg-transparent outline-none text-[14px] w-full peer"
                                placeholder="Search by name, grade level, room, etc.">
                            <button id="clear-btn"
                                class="clear-btn flex justify-center items-center peer-placeholder-shown:hidden peer-not-placeholder-shown:block">
                                <i class="fi fi-rs-cross-small text-[18px] flex justify-center items-center"></i>
                            </button>
                        </label>
                        <div class="flex flex-row justify-start items-center w-full gap-2">
                            <div
                                class="flex flex-row justify-between items-center rounded-lg border-2 border-[#1e1e1e]/10 bg-gray-100 px-3 py-2 gap-2 outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 transition-all ease-in-out duration-150 shadow-sm">
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
                                <i id="clear-page-length-filter-btn"
                                    class="fi fi-rr-caret-down text-gray-500 flex justify-center items-center"></i>
                            </div>

                            <div id="grade_selection_container"
                                class="flex flex-row justify-between items-center rounded-lg border-2 border-[#1e1e1e]/10 bg-gray-100 px-3 py-2 gap-2 outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 transition-all ease-in-out duration-150 shadow-sm">

                                <select name="grade_selection" id="grade_selection"
                                    class="appearance-none bg-transparent text-[14px] font-medium text-gray-700 h-full w-full cursor-pointer">
                                    <option value="" disabled selected>Grade</option>
                                    <option value="" data-putanginamo="Grade 11">Grade 11</option>
                                    <option value="" data-putanginamo="Grade 12">Grade 12</option>
                                </select>
                                <i id="clear-grade-filter-btn"
                                    class="fi fi-rr-caret-down text-gray-500 flex justify-center items-center"></i>

                            </div>

                            <!-- Layout Toggle Button -->
                            <div id="layout_toggle_container"
                                class="flex flex-row justify-center items-center rounded-lg border-2 border-[#1e1e1e]/10 bg-gray-100 px-3 py-2 gap-2 hover:bg-gray-200 hover:border-[#1e1e1e]/15 transition-all ease-in-out duration-150 shadow-sm">
                                <button id="layout-toggle-btn"
                                    class="flex flex-row justify-center items-center gap-2 text-[14px] font-medium text-gray-700 hover:text-[#1A3165] transition-colors duration-150">
                                    <i id="layout-toggle-icon"
                                        class="fi fi-sr-apps flex justify-center items-center text-[14px]"></i>
                                    <span id="layout-toggle-text">Cards</span>
                                </button>
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

                <!-- Table Layout Container -->
                <div id="table-layout-container" class="w-full hidden">
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
    @endif
    @if (Route::is('program.subjects'))
        <div class="flex flex-row justify-center items-start gap-4">
            <div
                class="flex flex-col justify-start items-start flex-grow p-5 space-y-4 bg-[#f8f8f8] rounded-xl shadow-md border border-[#1e1e1e]/10 w-[40%]">
                <div class="flex flex-col my-2 justify-center items-center w-full">
                    <span class="font-semibold text-[18px]">
                        Subjects
                    </span>
                    <span class="font-medium text-gray-400 text-[14px]">
                        Course subjects and curriculum for this program
                    </span>
                </div>
                <div class="flex flex-row justify-between items-center w-full h-full py-2">

                    <div class="flex flex-row justify-between w-3/4 items-center gap-4">

                        <label for="myCustomSearch"
                            class="flex flex-row justify-start items-center border-2 border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-2 gap-2 w-[70%] outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 focus-within:shadow-lg transition duration-150 shadow-sm">
                            <i class="fi fi-rs-search flex justify-center items-center text-[#1e1e1e]/60 text-[16px]"></i>
                            <input type="search" name="" id="myCustomSearch"
                                class="my-custom-search bg-transparent outline-none text-[14px] w-full peer"
                                placeholder="Search by name, category, year level, etc.">
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
                                    <option value="" selected disabled>Category</option>
                                    <option value="" data-id="Core">Core</option>
                                    <option value="" data-id="Applied">Applied</option>
                                    <option value="" data-id="Specialized">Specialized</option>

                                </select>
                                <i id="clear-program-filter-btn"
                                    class="fi fi-rr-caret-down text-gray-500 flex justify-center items-center"></i>
                            </div>

                            <div id="grade_selection_container"
                                class="flex flex-row justify-between items-center rounded-lg border border-[#1e1e1e]/10 bg-gray-100 px-3 py-2 gap-2 hover:bg-gray-200 hover:border-[#1e1e1e]/15 transition-all ease-in-out duration-150 shadow-sm">

                                <select name="grade_selection" id="grade_selection"
                                    class="appearance-none bg-transparent text-[14px] font-medium text-gray-700 h-full w-full cursor-pointer">
                                    <option value="" disabled selected>Year Level</option>
                                    <option value="" data-putanginamo="Grade 11">Grade 11</option>
                                    <option value="" data-putanginamo="Grade 12">Grade 12</option>
                                </select>
                                <i id="clear-grade-filter-btn"
                                    class="fi fi-rr-caret-down text-gray-500 flex justify-center items-center"></i>

                            </div>
                            <div id="semester_selection_container"
                                class="flex flex-row justify-between items-center rounded-lg border border-[#1e1e1e]/10 bg-gray-100 px-3 py-2 gap-2 hover:bg-gray-200 hover:border-[#1e1e1e]/15 transition-all ease-in-out duration-150 shadow-sm">

                                <select name="semester_selection" id="semester_selection"
                                    class="appearance-none bg-transparent text-[14px] font-medium text-gray-700 h-full w-full cursor-pointer">
                                    <option value="" disabled selected>Semester</option>
                                    <option value="" data-sem="1st Semester">1st Semester</option>
                                    <option value="" data-sem="2nd Semester">2nd Semester</option>
                                </select>
                                <i id="clear-semester-filter-btn"
                                    class="fi fi-rr-caret-down text-gray-500 flex justify-center items-center"></i>

                            </div>


                        </div>
                    </div>

                    <button id="add-student-modal-btn"
                        class="self-end flex flex-row justify-center items-center bg-[#199BCF] py-2 px-3 rounded-xl text-[16px] font-semibold gap-2 text-white hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                        <i class="fi fi-sr-square-plus opacity-70 flex justify-center items-center text-[18px]"></i>
                        New Subject
                    </button>


                </div>

                <div class="w-full">
                    <table id="subjects" class="w-full table-fixed">
                        <thead class="text-[14px]">
                            <tr>
                                <th class="w-[3%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-2 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">#</span>
                                </th>

                                <th class="w-[10%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Subject Name</span>
                                    <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                                </th>

                                <th class="w-[45%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Category</span>
                                    <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                                </th>

                                <th class="w-[15%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Year Level</span>
                                    <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                                </th>
                                <th class="w-[15%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Semester</span>
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
    @endif
    @if (Route::is('program.faculty'))
        <div class="flex flex-row justify-center items-start gap-4">
            <div
                class="flex flex-col justify-start items-start flex-grow p-5 space-y-4 bg-[#f8f8f8] rounded-xl shadow-md border border-[#1e1e1e]/10 w-[40%]">
                <div class="flex flex-col my-2 justify-center items-center w-full">
                    <span class="font-semibold text-[18px]">
                        Faculty
                    </span>
                    <span class="font-medium text-gray-400 text-[14px]">
                        Course subjects and curriculum for this program
                    </span>
                </div>
                <div class="flex flex-row justify-between items-center w-full h-full py-2">

                    <div class="flex flex-row justify-between w-3/4 items-center gap-4">

                        <label for="myCustomSearch"
                            class="flex flex-row justify-start items-center border-2 border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-2 gap-2 w-[70%] outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 focus-within:shadow-lg transition duration-150 shadow-sm">
                            <i class="fi fi-rs-search flex justify-center items-center text-[#1e1e1e]/60 text-[16px]"></i>
                            <input type="search" name="" id="myCustomSearch"
                                class="my-custom-search bg-transparent outline-none text-[14px] w-full peer"
                                placeholder="Search by name, category, year level, etc.">
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
                                    <option value="" selected disabled>Category</option>
                                    <option value="" data-id="Core">Core</option>
                                    <option value="" data-id="Applied">Applied</option>
                                    <option value="" data-id="Specialized">Specialized</option>

                                </select>
                                <i id="clear-program-filter-btn"
                                    class="fi fi-rr-caret-down text-gray-500 flex justify-center items-center"></i>
                            </div>

                            <div id="grade_selection_container"
                                class="flex flex-row justify-between items-center rounded-lg border border-[#1e1e1e]/10 bg-gray-100 px-3 py-2 gap-2 hover:bg-gray-200 hover:border-[#1e1e1e]/15 transition-all ease-in-out duration-150 shadow-sm">

                                <select name="grade_selection" id="grade_selection"
                                    class="appearance-none bg-transparent text-[14px] font-medium text-gray-700 h-full w-full cursor-pointer">
                                    <option value="" disabled selected>Year Level</option>
                                    <option value="" data-putanginamo="Grade 11">Grade 11</option>
                                    <option value="" data-putanginamo="Grade 12">Grade 12</option>
                                </select>
                                <i id="clear-grade-filter-btn"
                                    class="fi fi-rr-caret-down text-gray-500 flex justify-center items-center"></i>

                            </div>
                            <div id="semester_selection_container"
                                class="flex flex-row justify-between items-center rounded-lg border border-[#1e1e1e]/10 bg-gray-100 px-3 py-2 gap-2 hover:bg-gray-200 hover:border-[#1e1e1e]/15 transition-all ease-in-out duration-150 shadow-sm">

                                <select name="semester_selection" id="semester_selection"
                                    class="appearance-none bg-transparent text-[14px] font-medium text-gray-700 h-full w-full cursor-pointer">
                                    <option value="" disabled selected>Semester</option>
                                    <option value="" data-sem="1st Semester">1st Semester</option>
                                    <option value="" data-sem="2nd Semester">2nd Semester</option>
                                </select>
                                <i id="clear-semester-filter-btn"
                                    class="fi fi-rr-caret-down text-gray-500 flex justify-center items-center"></i>

                            </div>


                        </div>
                    </div>

                    <button id="add-student-modal-btn"
                        class="self-end flex flex-row justify-center items-center bg-[#199BCF] py-2 px-3 rounded-xl text-[16px] font-semibold gap-2 text-white hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                        <i class="fi fi-sr-square-plus opacity-70 flex justify-center items-center text-[18px]"></i>
                        New Subject
                    </button>


                </div>

                <div class="w-full">
                    <table id="faculty" class="w-full table-fixed">
                        <thead class="text-[14px]">
                            <tr>
                                <th class="w-[3%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-2 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">#</span>
                                </th>

                                <th class="w-[10%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Full Name</span>
                                    <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                                </th>

                                <th class="w-[45%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Category</span>
                                    <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                                </th>

                                <th class="w-[15%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Year Level</span>
                                    <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                                </th>
                                <th class="w-[15%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Semester</span>
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
    @endif
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
            initCustomDataTable
        } from "/js/initTable.js";

        import {
            showLoader,
            hideLoader
        } from "/js/loader.js";

        let table1;
        window.selectedGrade = '';
        window.selectedProgram = '';
        window.selectedCategory = '';
        window.selectedSemester = '';
        window.selectedPageLength = 10;
        window.currentLayout = 'cards'; // 'table' or 'cards'
        window.currentPage = 1;
        window.totalPages = 1;
        window.sectionsData = [];

        const programId = @json($program->id);

        document.addEventListener("DOMContentLoaded", function() {

            const currentPath = window.location.pathname;

            if (currentPath === `/program/${programId}/sections`) {
                initializeSectionTab();
            } else if (currentPath === `/program/${programId}/subjects`) {
                initializeSubjectTab();
            } else if (currentPath === '/school-fees/payments') {
                initializePaymentHistoryTab();
            }


            function initializeSectionTab() {
                let assignCheckbox = document.getElementById('auto_assign');
                let sectionName = document.querySelector('#section_name');


                initModal('create-section-modal', 'create-section-modal-btn', 'create-section-modal-close-btn',
                    'create-section-cancel-btn',
                    'modal-container-1');
                initModal('edit-program-modal', 'edit-program-modal-btn', 'edit-program-modal-close-btn',
                    'edit-program-cancel-btn',
                    'modal-container-2');
                initModal('delete-program-modal', 'delete-program-btn', 'delete-program-close-btn',
                    'delete-program-cancel-btn',
                    'modal-container-delete-program');


                let sectionTable = initCustomDataTable(
                    'sections',
                    `/getSections/${programId}`,
                    [{
                            data: 'index',
                            width: '3%',
                            searchable: true
                        },
                        {
                            data: 'name',
                            width: '15%'
                        },
                        {
                            data: 'adviser',
                            width: '15%'
                        },
                        {
                            data: 'year_level',
                            width: '15%'
                        },
                        {
                            data: 'room',
                            width: '15%'
                        },
                        {
                            data: 'total_enrolled_students',
                            width: '15%'
                        },
                        {
                            data: 'id',
                            className: 'text-center',
                            width: '15%',
                            render: function(data, type, row) {
                                return `
                            <div class='flex flex-row justify-center items-center opacity-100'>

                                <a href="/section/${data}" class="group relative inline-flex items-center gap-2 bg-blue-100 text-blue-500 font-semibold px-3 py-1 rounded-xl hover:bg-blue-500 hover:ring hover:ring-blue-200 hover:text-white transition duration-150 ">

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
                        sectionTable.search(this.value).draw();
                    } else {
                        // For card layout, fetch with search
                        fetchSectionsForCards(1);
                    }
                });


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
                            <p class="text-lg font-medium">No sections found</p>
                            <p class="text-sm">Try adjusting your search or filters</p>
                        </div>
                    `;
                        paginationContainer.innerHTML = '';
                        return;
                    }

                    // Render cards
                    cardsGrid.innerHTML = data.map(section => `
                    <div class="bg-white rounded-xl shadow-md border border-[#1e1e1e]/10 hover:shadow-lg hover:-translate-y-1 hover:border-[#199BCF]/30 hover:text-[#199BCF] transition-all duration-200 p-6">
                        <div class="flex flex-col space-y-4">
                            <!-- Header -->
                            <div class="flex flex-row justify-between items-start">
                                <div class="flex flex-col">
                                    <h3 class="text-lg font-bold">${section.name}</h3>
                                    <p class="text-sm text-gray-600">${section.year_level}</p>
                                </div>
                                <div class="flex flex-col items-end">
                                    <span class="text-xs text-gray-500">#${section.index}</span>
                                </div>
                            </div>

                            <!-- Details -->
                            <div class="space-y-3">
                                <div class="flex flex-row justify-start items-center gap-3">
                                    <div class="flex justify-center items-center bg-gray-200 rounded-full w-8 h-8 p-1 flex-shrink-0">
                                       <i class="fi fi-sr-user text-gray-700 text-sm"></i>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-xs text-gray-500">Adviser</span>
                                        <span class="text-sm font-medium">${section.adviser}</span>
                                    </div>
                                </div>
                                
                                <div class="flex flex-row items-center gap-3">
                                    <div class="flex justify-center items-center bg-gray-200 rounded-full w-8 h-8 p-1 flex-shrink-0">
                                        <i class="fi fi-sr-home text-gray-700 text-sm"></i>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-xs text-gray-500">Room</span>
                                        <span class="text-sm font-medium">${section.room}</span>
                                    </div>
                                </div>
                                
                                <div class="flex flex-row items-center gap-3">
                                    <div class="flex justify-center items-center bg-gray-200 rounded-full w-8 h-8 p-1 flex-shrink-0">
                                        <i class="fi fi-sr-users text-gray-700 text-sm"></i>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-xs text-gray-500">Total Students</span>
                                        <span class="text-sm font-medium">${section.total_enrolled_students}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <div class="pt-2 border-t border-gray-100">
                                <a href="/section/${section.id}" 
                                   class="w-full flex justify-center items-center gap-2 bg-gray-50 border border-gray-300 text-gray-600 px-4 py-2 rounded-xl text-sm font-medium hover:bg-gray-200 transition-colors duration-150">
                                    <i class="fi fi-rs-eye flex justify-center items-center text-sm"></i>
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
                            `/getSections/${programId}?start=${(page - 1) * window.selectedPageLength}&length=${window.selectedPageLength}&grade_filter=${window.selectedGrade}&program_filter=${window.selectedProgram}&search[value]=${document.getElementById('myCustomSearch').value}`
                        );
                        const data = await response.json();

                        window.sectionsData = data.data;
                        window.currentPage = page;
                        window.totalPages = Math.ceil(data.recordsTotal / window.selectedPageLength);

                        renderCards(data.data, page, window.totalPages);
                    } catch (error) {
                        console.error('Error fetching sections:', error);
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

                        layoutToggleIcon.className = 'fi fi-sr-list text-[16px]';
                        layoutToggleText.textContent = 'Table';

                        // Refresh table
                        sectionTable.draw();
                    }
                });

                clearSearch('clear-btn', 'myCustomSearch', sectionTable)

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


                let gradeSelection = document.querySelector('#grade_selection');
                let pageLengthSelection = document.querySelector('#page-length-selection');

                let clearGradeFilterBtn = document.querySelector('#clear-grade-filter-btn');
                let gradeContainer = document.querySelector('#grade_selection_container');

                // Helper function for filter changes (neutral color scheme)
                function handleFilterChange(filterType, dataAttribute, globalVariable, table, selectElement,
                    containerElement, clearButton) {
                    return function(e) {
                        let selectedOption = e.target.selectedOptions[0];
                        let value = selectedOption.getAttribute(dataAttribute);

                        // Update global variable
                        window[globalVariable] = value;

                        // Update table
                        table.draw();

                        // If in card layout, refresh cards
                        if (window.currentLayout === 'cards') {
                            fetchSectionsForCards(1);
                        }

                        // Update UI styling with neutral colors
                        if (containerElement) {
                            containerElement.classList.remove('bg-gray-100');
                            containerElement.classList.add('bg-gray-200', 'border-gray-400',
                                'hover:bg-gray-300');
                        }

                        if (clearButton) {
                            clearButton.classList.remove('text-gray-500', 'fi-rr-caret-down');
                            clearButton.classList.add('fi-bs-cross-small', 'cursor-pointer', 'text-gray-700');
                        }

                        selectElement.classList.remove('text-gray-700');
                        selectElement.classList.add('text-gray-900');
                    };
                }

                // Helper function for clear filter handlers (neutral color scheme)
                function createClearFilterHandler(selectElement, containerElement, clearButton, globalVariable,
                    table, filterType) {
                    clearButton.addEventListener('click', () => {
                        // Reset global variable
                        window[globalVariable] = '';

                        // Reset select element
                        selectElement.selectedIndex = 0;

                        // Reset UI styling with neutral colors
                        if (containerElement) {
                            containerElement.classList.remove('bg-gray-200', 'border-gray-400',
                                'hover:bg-gray-300');
                            containerElement.classList.add('bg-gray-100');
                        }

                        clearButton.classList.remove('fi-bs-cross-small', 'cursor-pointer',
                            'text-gray-700');
                        clearButton.classList.add('fi-rr-caret-down', 'text-gray-500');

                        selectElement.classList.remove('text-gray-900');
                        selectElement.classList.add('text-gray-700');

                        // Update table
                        table.draw();

                        // If in card layout, refresh cards
                        if (window.currentLayout === 'cards') {
                            fetchSectionsForCards(1);
                        }
                    });
                }

                pageLengthSelection.addEventListener('change', (e) => {

                    let selectedPageLength = parseInt(e.target.value, 10);
                    window.selectedPageLength = selectedPageLength;
                    sectionTable.page.len(selectedPageLength).draw();

                    // If in card layout, refresh cards
                    if (window.currentLayout === 'cards') {
                        fetchSectionsForCards(1);
                    }

                })

                // Apply filter handlers using the helper functions
                if (gradeSelection) {
                    gradeSelection.addEventListener('change', handleFilterChange('grade', 'data-putanginamo',
                        'selectedGrade',
                        sectionTable, gradeSelection, gradeContainer, clearGradeFilterBtn));
                }

                // Apply clear handlers to all filters
                if (gradeSelection) {
                    createClearFilterHandler(gradeSelection, gradeContainer, clearGradeFilterBtn, 'selectedGrade',
                        sectionTable, 'grade');
                }

                // Initialize filter states
                if (gradeSelection) gradeSelection.selectedIndex = 0;
                if (pageLengthSelection) pageLengthSelection.selectedIndex = 0;

                // Populate edit form when edit button is clicked
                document.getElementById('edit-program-modal-btn').addEventListener('click', function() {
                    // Fetch current program data
                    fetch(`{{ url('/program') }}/${programId}`)
                        .then(response => response.json())
                        .then(data => {
                            // Populate form fields
                            document.getElementById('program_code').value = data.code || '';
                            document.getElementById('program_name').value = data.name || '';
                            document.getElementById('program_track').value = data.track || '';
                            document.getElementById('program_status').value = data.status || 'active';
                        })
                        .catch(err => {
                            showAlert('error', 'Failed to load program data');
                        });
                });

                document.getElementById('edit-program-form').addEventListener('submit', function(e) {
                    e.preventDefault();

                    let form = e.target;
                    let formData = new FormData(form);

                    // Show loader
                    showLoader("Editing program...");

                    fetch(`/updateProgram/${programId}`, {
                            method: "POST",
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                                "Accept": "application/json"
                            },
                            body: formData
                        })
                        .then(response => {
                            return response.json();
                        })
                        .then(data => {
                            hideLoader();

                            if (data.success) {
                                // Reset form
                                form.reset();

                                // Close modal
                                closeModal('edit-program-modal', 'modal-container-2');

                                // Show success alert
                                showAlert('success', data.message);

                                // Reload page to show updated data
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1000);

                            } else {
                                closeModal('edit-program-modal', 'modal-container-2');
                                showAlert('error', `${data.message}: ${data.errors}`);
                            }
                        })
                        .catch(err => {
                            hideLoader();
                            closeModal('edit-program-modal', 'modal-container-2');
                            showAlert('error', 'Something went wrong while updating the program');
                        });
                });

                document.getElementById('delete-program-form').addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(this);
                    const schoolFeeId = formData.get('school_fee_id');

                    showLoader();
                    fetch(`/program/${programId}`, {
                            method: 'DELETE',
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
                                closeModal('delete-program-modal',
                                    'modal-container-delete-program');
                                setTimeout(() => {
                                    window.location.href = '/tracks'
                                }, 1500);
                            } else if (data.success) {
                                showAlert('success', data.message);
                                // Close modal
                                document.getElementById('delete-program-close-btn').click();
                            } else {
                                showAlert('error', data.message || 'Unknown error');
                            }
                        })
                        .catch(error => {
                            hideLoader();
                            showAlert('error', 'An error occurred while deleting the school fee');
                        });
                });

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
                            showAlert('error', 'An error occurred while deleting the school fee');
                        });
                });

                // Program-based adviser filtering
                const programSelect = document.getElementById('program_id');
                const adviserSelect = document.getElementById('adviser_id');

                if (programSelect && adviserSelect) {
                    programSelect.addEventListener('change', function() {
                        const selectedProgramId = this.value;
                        const adviserOptions = adviserSelect.querySelectorAll('option[data-program-id]');

                        adviserOptions.forEach(option => {
                            if (selectedProgramId === '' || option.getAttribute(
                                    'data-program-id') ===
                                selectedProgramId) {
                                option.style.display = 'block';
                            } else {
                                option.style.display = 'none';
                            }
                        });

                        // Reset adviser selection if current selection is not valid for new program
                        if (adviserSelect.value && adviserSelect.querySelector(
                                `option[value="${adviserSelect.value}"]`).style.display === 'none') {
                            adviserSelect.value = '';
                        }
                    });
                }

                window.onload = function() {
                    gradeSelection.selectedIndex = 0
                    pageLengthSelection.selectedIndex = 0

                    // Initialize with cards layout (default)
                    fetchSectionsForCards(1);
                }
            }

            function initializeSubjectTab() {
                // Initialize subject creation modal
                initModal('create-subject-modal', 'add-student-modal-btn', 'create-subject-modal-close-btn',
                    'create-subject-cancel-btn', 'modal-container-subject');

                let subjectTable = initCustomDataTable(
                    'subjects',
                    `/getSubjects/${programId}`,
                    [{
                            data: 'index',
                            width: '3%',

                        },
                        {
                            data: 'name',
                            width: '30%',
                            searchable: true,
                            orderable: true
                        },
                        {
                            data: 'category',
                            width: '10%',
                            searchable: true,
                            orderable: true
                        },
                        {
                            data: 'year_level',
                            width: '10%',
                            searchable: true,
                            orderable: true
                        },
                        {
                            data: 'semester',
                            width: '10%',
                            searchable: true,
                            orderable: true
                        },
                        {
                            data: 'id',
                            className: 'text-center',
                            width: '15%',
                            render: function(data, type, row) {
                                return `
                            <div class='flex flex-row justify-center items-center gap-2'>
                                <button type="button" id="open-edit-subject-modal-btn-${data}"
                                    data-subject-id="${data}"
                                    class="edit-subject-btn group relative inline-flex items-center gap-2 bg-blue-100 text-blue-500 font-semibold p-2 rounded-xl hover:bg-blue-500 hover:ring hover:ring-blue-200 hover:text-white transition duration-150">
                                    <i class="fi fi-rr-edit text-[16px] flex justify-center items-center"></i>
                                </button>
                                <button type="button" id="open-delete-subject-modal-btn-${data}"
                                    data-subject-id="${data}"
                                    class="delete-subject-btn group relative inline-flex items-center gap-2 bg-red-100 text-red-500 font-semibold p-2 rounded-xl hover:bg-red-500 hover:ring hover:ring-red-200 hover:text-white transition duration-150">
                                    <i class="fi fi-rr-trash text-[16px] flex justify-center items-center"></i>
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
                    'myCustomSearch', {
                        grade_filter: selectedGrade,
                        category_filter: selectedCategory,
                        semester_filter: selectedSemester,
                        pageLength: selectedPageLength
                    }

                )

                clearSearch('clear-btn', 'myCustomSearch', subjectTable)

                // Add filter event listeners for subjects (using invoice tab pattern)
                let categorySelection = document.querySelector('#program_selection');
                let semesterSelection = document.querySelector('#semester_selection');
                let gradeSelection = document.querySelector('#grade_selection');
                let pageLengthSelection = document.querySelector('#page-length-selection');

                // Apply filter handlers using the helper functions
                if (categorySelection) {
                    categorySelection.addEventListener('change', handleFilterChange('category', 'data-id',
                        'selectedCategory',
                        subjectTable, categorySelection, document.querySelector('#program_selection')
                        .parentElement, document.querySelector('#clear-program-filter-btn')));
                }

                if (semesterSelection) {
                    semesterSelection.addEventListener('change', handleFilterChange('semester', 'data-sem',
                        'selectedSemester',
                        subjectTable, semesterSelection, document.querySelector(
                            '#semester_selection_container'), document.querySelector(
                            '#clear-semester-filter-btn')));
                }

                if (gradeSelection) {
                    gradeSelection.addEventListener('change', handleFilterChange('grade', 'data-putanginamo',
                        'selectedGrade',
                        subjectTable, gradeSelection, document.querySelector('#grade_selection_container'),
                        document.querySelector('#clear-grade-filter-btn')));
                }

                // Special handler for page length (different behavior)
                if (pageLengthSelection) {
                    pageLengthSelection.addEventListener('change', (e) => {
                        let selectedPageLength = parseInt(e.target.value, 10);
                        selectedPageLength = selectedPageLength;
                        subjectTable.page.len(selectedPageLength).draw();
                    });
                }

                // Apply clear handlers to all filters
                if (categorySelection) {
                    createClearFilterHandler(categorySelection, document.querySelector('#program_selection')
                        .parentElement,
                        document.querySelector('#clear-program-filter-btn'), 'selectedCategory', subjectTable,
                        'category');
                }

                if (semesterSelection) {
                    createClearFilterHandler(semesterSelection, document.querySelector(
                            '#semester_selection_container'),
                        document.querySelector('#clear-semester-filter-btn'), 'selectedSemester', subjectTable,
                        'semester');
                }

                if (gradeSelection) {
                    createClearFilterHandler(gradeSelection, document.querySelector('#grade_selection_container'),
                        document.querySelector('#clear-grade-filter-btn'), 'selectedGrade', subjectTable,
                        'grade');
                }

                // Initialize filter states
                window.onload = function() {
                    if (categorySelection) categorySelection.selectedIndex = 0;
                    if (semesterSelection) semesterSelection.selectedIndex = 0;
                    if (gradeSelection) gradeSelection.selectedIndex = 0;
                    if (pageLengthSelection) pageLengthSelection.selectedIndex = 0;
                }

                // Subject creation form submission handler
                document.getElementById('create-subject-form').addEventListener('submit', function(e) {
                    e.preventDefault();

                    let form = e.target;
                    let formData = new FormData(form);

                    showLoader("Creating subject...");

                    fetch('/subjects', {
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
                                closeModal('create-subject-modal', 'modal-container-subject');

                                // Reset form
                                form.reset();

                                // Update total subjects display
                                if (data.totalSubjects !== undefined) {
                                    document.getElementById('totalSubjectsDisplay').textContent = data
                                        .totalSubjects;
                                }

                                // Refresh the subjects table
                                subjectTable.draw();

                            } else {
                                showAlert('error', data.message || 'Unknown error');
                                closeModal('create-subject-modal', 'modal-container-subject');
                            }
                        })
                        .catch(error => {
                            hideLoader();
                            showAlert('error', 'An error occurred while creating the subject');
                            closeModal('create-subject-modal', 'modal-container-subject');
                        });
                });

                // Initialize edit and delete modals dynamically
                initializeEditSubjectModals();
                initializeDeleteSubjectModals();

                // Reinitialize modals after table draw
                subjectTable.on('draw', function() {
                    initializeEditSubjectModals();
                    initializeDeleteSubjectModals();
                });

                // Edit subject form submission handler
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
                        .then(response => response.json())
                        .then(data => {
                            hideLoader();

                            if (data.success) {
                                // Reset form
                                form.reset();

                                // Remove hidden input
                                const hiddenInput = document.getElementById('edit_subject_id');
                                if (hiddenInput) {
                                    hiddenInput.remove();
                                }

                                // Close modal
                                closeModal('edit-subject-modal', 'modal-container-edit-subject');

                                // Show success alert
                                showAlert('success', 'Subject updated successfully!');

                                // Update total subjects display
                                if (data.totalSubjects !== undefined) {
                                    document.getElementById('totalSubjectsDisplay').textContent = data
                                        .totalSubjects;
                                }

                                // Refresh table
                                if (typeof subjectTable !== 'undefined') {
                                    subjectTable.draw();
                                }

                            } else if (data.error) {
                                closeModal('edit-subject-modal', 'modal-container-edit-subject');
                                showAlert('error', data.error);
                            } else if (data.message) {
                                closeModal('edit-subject-modal', 'modal-container-edit-subject');
                                showAlert('error', data.message);
                            }
                        })
                        .catch(err => {
                            hideLoader();
                            closeModal('edit-subject-modal', 'modal-container-edit-subject');
                            showAlert('error', 'Something went wrong while updating the subject');
                        });
                });

                // Delete subject form submission handler
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
                                if (data.success === false && data.dependencies) {
                                    showAlert('error', data.message);
                                    closeModal('delete-subject-modal',
                                        'modal-container-delete-subject');
                                } else if (data.success === true) {
                                    showAlert('success', data.message);
                                    subjectTable.draw(); // Refresh the table

                                    // Update total subjects display
                                    if (data.totalSubjects !== undefined) {
                                        document.getElementById('totalSubjectsDisplay').textContent =
                                            data.totalSubjects;
                                    }

                                    // Close modal
                                    document.getElementById('delete-subject-close-btn').click();
                                } else {
                                    showAlert('error', data.message);
                                }
                            })
                            .catch(error => {
                                hideLoader();
                                showAlert('error', 'An error occurred while deleting the subject');
                            });
                    });
                }
            }


            // Initialize edit subject modals dynamically
            function initializeEditSubjectModals() {
                document.querySelectorAll('.edit-subject-btn').forEach((button) => {
                    let subjectId = button.getAttribute('data-subject-id');
                    let buttonId = `open-edit-subject-modal-btn-${subjectId}`;

                    // Initialize modal for this specific button
                    initModal('edit-subject-modal', buttonId, 'edit-subject-modal-close-btn',
                        'edit-subject-cancel-btn', 'modal-container-edit-subject');

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
                                    document.getElementById('edit_subject_name').value = subject
                                        .name || '';
                                    document.getElementById('edit_subject_program_id').value =
                                        subject.program_id || '';
                                    document.getElementById('edit_subject_grade_level').value =
                                        subject.grade_level || '';
                                    document.getElementById('edit_subject_category').value =
                                        subject.category || '';
                                    document.getElementById('edit_subject_semester').value =
                                        subject.semester || '';

                                } else {
                                    showAlert('error', 'Error loading subject: ' + data
                                        .message);
                                }
                            })
                            .catch(error => {
                                hideLoader();
                                showAlert('error',
                                    'An error occurred while loading the subject');
                            });
                    });
                });
            }

            // Initialize delete subject modals dynamically
            function initializeDeleteSubjectModals() {
                document.querySelectorAll('.delete-subject-btn').forEach((button) => {
                    let subjectId = button.getAttribute('data-subject-id');
                    let buttonId = `open-delete-subject-modal-btn-${subjectId}`;

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

                    });
                });
            }

            dropDown('dropdown', 'dropdown_selection');

            // Helper function for filter changes (from invoice tab pattern)
            function handleFilterChange(filterType, dataAttribute, globalVariable, table, selectElement,
                containerElement, clearButton) {
                return function(e) {
                    let selectedOption = e.target.selectedOptions[0];
                    let value = selectedOption.getAttribute(dataAttribute);

                    // Update global variable
                    window[globalVariable] = value;

                    // Update table
                    table.draw();

                    // Update UI styling with neutral colors
                    if (containerElement) {
                        containerElement.classList.remove('bg-gray-100');
                        containerElement.classList.add('bg-gray-200', 'border-gray-400', 'hover:bg-gray-300');
                    }

                    if (clearButton) {
                        clearButton.classList.remove('text-gray-500', 'fi-rr-caret-down');
                        clearButton.classList.add('fi-bs-cross-small', 'cursor-pointer', 'text-gray-700');
                    }

                    selectElement.classList.remove('text-gray-700');
                    selectElement.classList.add('text-gray-900');
                };
            }

            // Helper function for clear filter handlers (from invoice tab pattern)
            function createClearFilterHandler(selectElement, containerElement, clearButton, globalVariable, table,
                filterType) {
                clearButton.addEventListener('click', () => {
                    // Reset global variable
                    window[globalVariable] = '';

                    // Reset select element
                    selectElement.selectedIndex = 0;

                    // Reset UI styling with neutral colors
                    if (containerElement) {
                        containerElement.classList.remove('bg-gray-200', 'border-gray-400',
                            'hover:bg-gray-300');
                        containerElement.classList.add('bg-gray-100');
                    }

                    clearButton.classList.remove('fi-bs-cross-small', 'cursor-pointer', 'text-gray-700');
                    clearButton.classList.add('fi-rr-caret-down', 'text-gray-500');

                    selectElement.classList.remove('text-gray-900');
                    selectElement.classList.add('text-gray-700');

                    // Update table
                    table.draw();
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
