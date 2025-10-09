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
                <a href="/tracks" class="block transition-colors hover:text-gray-900">Tracks</a>
            </li>
            <li class="rtl:rotate-180">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
            </li>
            <li>
                <a href="{{ route('program.sections', $section->program->id) }}"
                    class="block transition-colors hover:text-gray-900">
                    {{ $section->program->code }}
                </a>
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

@section('modal')
    <!-- Edit Section Modal -->
    <x-modal modal_id="edit-section-modal" modal_name="Edit Section" close_btn_id="edit-section-close-btn"
        modal_container_id="modal-container-3">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-edit flex justify-center items-center'></i>
        </x-slot>
        <form enctype="multipart/form-data" id="edit-section-form" class="p-6">
            @csrf
            <div class="space-y-4">
                <div class="flex flex-row gap-4">
                    <div class="flex-1 flex flex-col">
                        <label for="name" class="text-sm font-medium text-gray-700 mb-2">Section Name</label>
                        <input type="text" name="name" id="name" placeholder="{{ $section->name }}"
                            class="w-full border-2 border-gray-300 bg-gray-100 rounded-lg px-3 py-2 outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 transition duration-200 placeholder:italic placeholder:text-[14px] text-[14px]">
                    </div>
                    <div class="flex-1 flex flex-col">
                        <label for="room" class="text-sm font-medium text-gray-700 mb-2">Room</label>
                        <input type="text" name="room" id="room"
                            placeholder="{{ $section->room ?? 'Not Assigned Yet' }}"
                            class="w-full border-2 border-gray-300 bg-gray-100 rounded-lg px-3 py-2 outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 transition duration-200 placeholder:italic placeholder:text-[14px] text-[14px]">
                    </div>
                </div>

                <div class="flex flex-col">
                    <label for="teacher_id" class="text-sm font-medium text-gray-700 mb-2">Adviser/Teacher</label>
                    <select name="teacher_id" id="teacher_id"
                        class="w-full border-2 border-gray-300 bg-gray-100 rounded-lg px-3 py-2 outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 transition duration-200 text-[14px]">
                        <option value="" selected>Select a teacher</option>
                        <!-- Options will be populated dynamically -->
                    </select>
                </div>
            </div>
        </form>
        <x-slot name="modal_buttons">
            <button id="edit-section-cancel-btn"
                class="bg-gray-50 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-100 hover:ring hover:ring-gray-200 transition duration-150">
                Cancel
            </button>
            <button type="submit" form="edit-section-form" name="action" value="verify"
                class="self-end flex flex-row justify-center items-center bg-[#199BCF] py-2 px-3 rounded-xl text-[14px] font-semibold gap-2 text-white hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                Update
            </button>
        </x-slot>
    </x-modal>

    <!-- Add Student Modal -->
    <x-modal modal_id="add-student-modal" modal_name="Add Students" close_btn_id="add-student-modal-close-btn"
        modal_container_id="modal-container-2">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-student flex justify-center items-center'></i>
        </x-slot>
        <form enctype="multipart/form-data" id="add-student-form" class="p-6">
            @csrf
            <div class="mb-4">
                <p class="text-sm text-gray-600 mb-2">Below is the list of {{ $section->year_level }}
                    {{ $section->program->code }} students who are currently unassigned to a section.</p>
            </div>
            <div
                class="relative flex flex-col justify-start items-center overflow-y-auto max-h-[400px] border border-gray-200 rounded-lg">
                <div
                    class="sticky top-0 flex flex-row justify-between items-center bg-[#f8f8f8] w-full p-3 border-b border-gray-200 font-medium text-sm text-gray-700">
                    <div class="w-8">#</div>
                    <div class="flex-1 text-left ml-4">Full Name</div>
                </div>
                @forelse ($students as $index => $student)
                    <div
                        class="flex flex-row justify-between items-center gap-2 w-full p-3 hover:bg-gray-50 transition duration-150">
                        <div class="w-8 text-sm text-gray-500">{{ $index + 1 }}</div>
                        <input type="checkbox" name="student[]" id="lrn-{{ $student->lrn }}" value="{{ $student->id }}"
                            class="peer sr-only" />
                        <label for="lrn-{{ $student->lrn }}"
                            class="flex-1 bg-gray-100 peer-checked:bg-green-100 peer-checked:border-green-300 inline-block px-3 py-2 rounded border border-transparent cursor-pointer transition duration-150">
                            <span class="text-sm">{{ $student->user->last_name }}, {{ $student->user->first_name }}</span>
                        </label>
                    </div>
                @empty
                    <div class="py-8 text-center text-gray-500">
                        <i class="fi fi-sr-user-slash text-2xl mb-2"></i>
                        <p>No students available to assign.</p>
                    </div>
                @endforelse
            </div>
        </form>
        <x-slot name="modal_buttons">
            <button id="add-student-cancel-btn"
                class="bg-gray-50 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-100 hover:ring hover:ring-gray-200 transition duration-150">
                Cancel
            </button>
            <button type="submit" form="add-student-form" name="action" value="verify"
                class="self-end flex flex-row justify-center items-center bg-[#199BCF] py-2 px-3 rounded-xl text-[16px] font-semibold gap-2 text-white hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                Continue
            </button>
        </x-slot>
    </x-modal>

    <!-- Add Subject Modal -->
    <x-modal modal_id="add-subject-modal" modal_name="Add Subject" close_btn_id="add-subject-modal-close-btn"
        modal_container_id="modal-container-4">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-book flex justify-center items-center'></i>
        </x-slot>
        <div id="modal-content"
            class="max-h-96 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
            <form id="add-subject-form" class="p-6">
                @csrf
                <div class="space-y-4">
                    <!-- Subject Selection -->
                    <div class="flex flex-col">
                        <label for="subject_id" class="text-sm font-medium text-gray-700 mb-2">Subject</label>
                        <select name="subject_id" id="subject_id" required
                            class="w-full border-2 border-gray-300 bg-gray-100 rounded-lg px-3 py-2 outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 transition duration-200 placeholder:italic placeholder:text-[14px] text-[14px]">
                            <option value="" disabled selected>Select a subject</option>
                            <!-- Options will be populated dynamically -->
                        </select>
                    </div>

                    <!-- Teacher Selection -->
                    <div class="flex flex-col">
                        <label for="teacher_id" class="text-sm font-medium text-gray-700 mb-2">Teacher</label>
                        <select name="teacher_id" id="teacher_id"
                            class="w-full border-2 border-gray-300 bg-gray-100 rounded-lg px-3 py-2 outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 transition duration-200 placeholder:italic placeholder:text-[14px] text-[14px]">
                            <option value="" selected>Select a teacher</option>
                            <!-- Options will be populated dynamically -->
                        </select>
                    </div>

                    <!-- Room -->
                    <div class="flex flex-col">
                        <label for="room" class="text-sm font-medium text-gray-700 mb-2">Room</label>
                        <input type="text" name="room" id="room" placeholder="Enter room number"
                            class="w-full border-2 border-gray-300 bg-gray-100 rounded-lg px-3 py-2 outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 transition duration-200 placeholder:italic placeholder:text-[14px] text-[14px]">
                    </div>

                    <!-- Days of Week -->
                    <div class="flex flex-col">
                        <label class="text-sm font-medium text-gray-700 mb-2">Days of Week</label>
                        <div class="grid grid-cols-2 gap-2">
                            <label class="flex items-center">
                                <input type="checkbox" name="days_of_week[]" value="Monday" class="mr-2">
                                <span class="text-sm">Monday</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="days_of_week[]" value="Tuesday" class="mr-2">
                                <span class="text-sm">Tuesday</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="days_of_week[]" value="Wednesday" class="mr-2">
                                <span class="text-sm">Wednesday</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="days_of_week[]" value="Thursday" class="mr-2">
                                <span class="text-sm">Thursday</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="days_of_week[]" value="Friday" class="mr-2">
                                <span class="text-sm">Friday</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="days_of_week[]" value="Saturday" class="mr-2">
                                <span class="text-sm">Saturday</span>
                            </label>
                        </div>
                    </div>

                    <!-- Time Schedule -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex flex-col">
                            <label for="start_time" class="text-sm font-medium text-gray-700 mb-2">Start Time</label>
                            <input type="time" name="start_time" id="start_time"
                                class="w-full border-2 border-gray-300 bg-gray-100 rounded-lg px-3 py-2 outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 transition duration-200 placeholder:italic placeholder:text-[14px] text-[14px]">
                        </div>
                        <div class="flex flex-col">
                            <label for="end_time" class="text-sm font-medium text-gray-700 mb-2">End Time</label>
                            <input type="time" name="end_time" id="end_time"
                                class="w-full border-2 border-gray-300 bg-gray-100 rounded-lg px-3 py-2 outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 transition duration-200 placeholder:italic placeholder:text-[14px] text-[14px]">
                        </div>
                    </div>

                    <!-- Schedule Conflict Warning -->
                    <div id="schedule-conflict-warning" class="hidden p-3 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-center">
                            <i class="fi fi-sr-exclamation-triangle text-red-500 mr-2"></i>
                            <span class="text-sm text-red-700 font-medium">Schedule Conflict Detected!</span>
                        </div>
                        <p id="conflict-details" class="text-xs text-red-600 mt-1"></p>
                    </div>

                    <!-- Schedule Suggestions -->
                    <div id="schedule-suggestions" class="hidden p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-center mb-2">
                            <i class="fi fi-sr-lightbulb text-blue-500 mr-2"></i>
                            <span class="text-sm text-blue-700 font-medium">Available Time Slots</span>
                        </div>
                        <p class="text-xs text-blue-600 mb-2">Click on any suggestion to auto-fill the form:</p>
                        <div id="suggestions-list"
                            class="max-h-32 overflow-y-auto scrollbar-thin scrollbar-thumb-blue-300 scrollbar-track-blue-100 grid grid-cols-2 gap-1 text-xs">
                            <!-- Suggestions will be populated here -->
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <x-slot name="modal_buttons">
            <button id="add-subject-cancel-btn"
                class="bg-gray-50 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-100 hover:ring hover:ring-gray-200 transition duration-150">
                Cancel
            </button>
            <button type="submit" form="add-subject-form" id="add-subject-submit-btn" name="action"
                value="add-subject"
                class="self-end flex flex-row justify-center items-center bg-[#199BCF] py-2 px-3 rounded-xl text-[16px] font-semibold gap-2 text-white hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                Add Subject
            </button>
        </x-slot>
    </x-modal>

    {{-- Edit Subject Modal --}}
    <x-modal modal_id="edit-subject-modal" modal_name="Edit Subject" close_btn_id="edit-subject-modal-close-btn"
        modal_container_id="modal-container-5">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-book flex justify-center items-center'></i>
        </x-slot>
        <div id="modal-content"
            class="max-h-96 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
            <form id="edit-subject-form" class="p-6">
                @csrf
                <!-- Hidden field to identify which section subject to update -->
                <input type="hidden" name="section_subject_id" id="edit-section-subject-id">

                <div class="space-y-4">
                    <!-- Subject Display -->
                    <div class="flex flex-col">
                        <label for="edit-subject_name" class="text-sm font-medium text-gray-700 mb-2">Subject</label>
                        <input type="text" name="subject_name" id="edit-subject_name" readonly
                            class="px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed"
                            placeholder="Subject name will appear here">
                    </div>

                    <!-- Teacher Selection -->
                    <div class="flex flex-col">
                        <label for="edit-teacher_id" class="text-sm font-medium text-gray-700 mb-2">Teacher</label>
                        <select name="teacher_id" id="edit-teacher_id"
                            class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                            <option value="" selected>Select a teacher (optional)</option>
                            <!-- Options will be populated dynamically -->
                        </select>
                    </div>

                    <!-- Room -->
                    <div class="flex flex-col">
                        <label for="edit-room" class="text-sm font-medium text-gray-700 mb-2">Room</label>
                        <input type="text" name="room" id="edit-room" placeholder="Enter room number"
                            class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                    </div>

                    <!-- Days of Week -->
                    <div class="flex flex-col">
                        <label class="text-sm font-medium text-gray-700 mb-2">Days of Week</label>
                        <div class="grid grid-cols-2 gap-2">
                            <label class="flex items-center">
                                <input type="checkbox" name="days_of_week[]" value="Monday" id="edit-day-monday"
                                    class="mr-2">
                                <span class="text-sm">Monday</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="days_of_week[]" value="Tuesday" id="edit-day-tuesday"
                                    class="mr-2">
                                <span class="text-sm">Tuesday</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="days_of_week[]" value="Wednesday" id="edit-day-wednesday"
                                    class="mr-2">
                                <span class="text-sm">Wednesday</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="days_of_week[]" value="Thursday" id="edit-day-thursday"
                                    class="mr-2">
                                <span class="text-sm">Thursday</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="days_of_week[]" value="Friday" id="edit-day-friday"
                                    class="mr-2">
                                <span class="text-sm">Friday</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="days_of_week[]" value="Saturday" id="edit-day-saturday"
                                    class="mr-2">
                                <span class="text-sm">Saturday</span>
                            </label>
                        </div>
                    </div>

                    <!-- Time Schedule -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex flex-col">
                            <label for="edit-start_time" class="text-sm font-medium text-gray-700 mb-2">Start Time</label>
                            <input type="time" name="start_time" id="edit-start_time"
                                class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                        </div>
                        <div class="flex flex-col">
                            <label for="edit-end_time" class="text-sm font-medium text-gray-700 mb-2">End Time</label>
                            <input type="time" name="end_time" id="edit-end_time"
                                class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                        </div>
                    </div>

                    <!-- Schedule Conflict Warning -->
                    <div id="edit-schedule-conflict-warning"
                        class="hidden p-3 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-center">
                            <i class="fi fi-sr-exclamation-triangle text-red-500 mr-2"></i>
                            <span class="text-sm text-red-700 font-medium">Schedule Conflict Detected!</span>
                        </div>
                        <p id="edit-conflict-details" class="text-xs text-red-600 mt-1"></p>
                    </div>

                    <!-- Schedule Suggestions -->
                    <div id="edit-schedule-suggestions" class="hidden p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-center mb-2">
                            <i class="fi fi-sr-lightbulb text-blue-500 mr-2"></i>
                            <span class="text-sm text-blue-700 font-medium">Available Time Slots</span>
                        </div>
                        <p class="text-xs text-blue-600 mb-2">Click on any suggestion to auto-fill the form:</p>
                        <div id="edit-suggestions-list"
                            class="max-h-32 overflow-y-auto scrollbar-thin scrollbar-thumb-blue-300 scrollbar-track-blue-100 grid grid-cols-2 gap-1 text-xs">
                            <!-- Suggestions will be populated here -->
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <x-slot name="modal_buttons">
            <button id="edit-subject-cancel-btn"
                class="bg-gray-50 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-100 hover:ring hover:ring-gray-200 transition duration-150">
                Cancel
            </button>
            <button type="submit" form="edit-subject-form" id="edit-subject-submit-btn" name="action"
                value="edit-subject"
                class="self-end flex flex-row justify-center items-center bg-[#199BCF] py-2 px-3 rounded-xl text-[16px] font-semibold gap-2 text-white hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                Update
            </button>
        </x-slot>
    </x-modal>

    <!-- Delete Subject Modal -->
    <x-modal modal_id="delete-subject-modal" modal_name="Delete Subject" close_btn_id="delete-subject-close-btn"
        modal_container_id="modal-container-delete-subject">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-trash flex justify-center items-center text-red-500'></i>
        </x-slot>

        <div class="p-6">
            <div class="flex flex-col items-center space-y-4">
                <div class="text-center">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Confirm Deletion</h3>
                    <p class="text-gray-600">Are you sure you want to remove this subject from the section? This action
                        cannot be undone.</p>
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
                    Remove Subject
                </button>
            </form>
        </x-slot>
    </x-modal>

    <!-- Delete Student Modal -->
    <x-modal modal_id="delete-student-modal" modal_name="Delete Student" close_btn_id="delete-student-close-btn"
        modal_container_id="modal-container-delete-student">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-trash flex justify-center items-center text-red-500'></i>
        </x-slot>

        <div class="p-6">
            <div class="flex flex-col items-center space-y-4">
                <div class="text-center">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Confirm Deletion</h3>
                    <p class="text-gray-600">Are you sure you want to remove this student from the section? This action
                        cannot be undone.</p>
                </div>
            </div>
        </div>

        <x-slot name="modal_buttons">
            <button id="delete-student-cancel-btn"
                class="bg-gray-50 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-100 hover:ring hover:ring-gray-200 transition duration-150">
                Cancel
            </button>
            <form id="delete-student-form" class="inline">
                @csrf
                <button type="submit" id="delete-student-submit-btn"
                    class="bg-red-500 text-[14px] px-3 py-2 rounded-xl text-white font-bold hover:ring hover:ring-red-200 hover:bg-red-400 transition duration-150 shadow-sm hover:scale-95">
                    Remove Student
                </button>
            </form>
        </x-slot>
    </x-modal>

    <!-- Delete Section Modal -->
    <x-modal modal_id="delete-section-modal" modal_name="Delete Section" close_btn_id="delete-section-close-btn"
        modal_container_id="modal-container-delete-section">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-trash flex justify-center items-center text-red-500'></i>
        </x-slot>

        <div class="p-6">
            <div class="flex flex-col items-center space-y-4">
                <div class="text-center">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Confirm Section Deletion</h3>
                    <p class="text-gray-600 mb-4">Are you sure you want to delete this section? This action cannot be
                        undone.</p>

                    <!-- Student Warning -->
                    <div id="student-warning" class="hidden w-full p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-center">
                            <i class="fi fi-sr-exclamation-triangle text-red-500 mr-2"></i>
                            <span class="text-sm text-red-700 font-medium">Warning: Section has enrolled students</span>
                        </div>
                        <p class="text-xs text-red-600 mt-1">
                            This section currently has <span id="student-count-warning" class="font-semibold">0</span>
                            enrolled students.
                            Deleting this section will remove all students from it and may affect their academic records.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <x-slot name="modal_buttons">
            <button id="delete-section-cancel-btn"
                class="bg-gray-50 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-100 hover:ring hover:ring-gray-200 transition duration-150">
                Cancel
            </button>
            <form id="delete-section-form" class="inline">
                @csrf
                <button type="submit" id="delete-section-submit-btn"
                    class="bg-red-500 text-[14px] px-3 py-2 rounded-xl text-white font-bold hover:ring hover:ring-red-200 hover:bg-red-400 transition duration-150 shadow-sm hover:scale-95">
                    Delete Section
                </button>
            </form>
        </x-slot>
    </x-modal>
@endsection
@section('header')
    <div class="flex flex-row justify-between items-start text-start px-[14px] py-2">
        <div>
            <h1 class="text-[24px] font-black text-gray-900">Section Details</h1>
            <p class="text-[14px] text-gray-600 mt-1">{{ $section->program->name }} • {{ $section->year_level }}</p>
        </div>
        <div class="flex flex-row justify-center items-center h-full">
            <div id="dropdown_btn"
                class="relative space-y-14 h-full flex flex-col justify-center items-center gap-4 cursor-pointer">
                <div
                    class="group relative inline-flex items-center gap-2 bg-gray-100 border border-[#1e1e1e]/10 text-gray-700 font-semibold py-2 px-3 rounded-lg shadow-sm hover:bg-gray-200 hover:border-[#1e1e1e]/15 transition duration-150">
                    <i class="fi fi-br-menu-dots flex justify-center items-center"></i>
                </div>
                <div id="dropdown_selection"
                    class="absolute top-0 right-0 z-10 bg-[#f8f8f8] flex-col justify-center items-center gap-1 rounded-lg shadow-md border border-[#1e1e1e]/15 py-2 px-1 opacity-0 scale-95 pointer-events-none transition-all duration-200 ease-out translate-y-1">
                    <button id="edit-section-modal-btn"
                        class="flex-1 flex justify-start items-center px-8 py-2 gap-2 text-[14px] font-medium opacity-80 w-full border-b border-[#1e1e1e]/15 hover:bg-blue-100 hover:text-blue-600 truncate">
                        <i class="fi fi-rr-edit text-[16px] flex justify-center item-center"></i>Edit Section
                    </button>
                    <button id="delete-section-modal-btn"
                        class="flex-1 flex justify-start items-center px-8 py-2 gap-2 text-[14px] font-medium opacity-80 w-full hover:bg-red-100 hover:text-red-500 truncate">
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
            class="flex flex-col justify-center items-center flex-grow px-10 pb-10 pt-2 bg-gradient-to-br from-[#199BCF] to-[#1A3165] rounded-xl shadow-[#199BCF]/30 shadow-xl gap-2 text-white">
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
                    <p class="text-[12px] text-white/50">Academic Grade</p>
                </div>
                <div
                    class="flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-6 py-4 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-300">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-sr-school flex flex-row justify-center items-center"></i>
                        <p class="text-[14px]">Strand</p>
                    </div>
                    <p class="font-bold text-[20px]">{{ $section->program->code }}</p>
                    <p class="text-[12px] text-white/50">Course Code</p>
                </div>
                <div
                    class="flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-6 py-4 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-300">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-sr-home flex justify-center items-center"></i>
                        <p class="text-[14px]">Room</p>
                    </div>
                    <p class="font-bold text-[20px]" id="section_room">{{ $section->room ?? 'Not assigned' }}</p>
                    <p class="text-[12px] text-white/50">Classroom Location</p>
                </div>
                <div
                    class="flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-6 py-4 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-300">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-sr-user flex justify-center items-center"></i>
                        <p class="text-[14px]">Adviser</p>
                    </div>
                    <p class="font-bold text-[20px]" id="section_teacher">{{ $section->teacher->name ?? 'Not assigned' }}
                    </p>
                    <p class="text-[12px] text-white/50">Section Teacher</p>
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
                @role(['super_admin', 'registrar', 'head_teacher'])
                    <button id="add-student-modal-btn"
                        class="self-end flex flex-row justify-center items-center bg-[#199BCF] py-2 px-3 rounded-xl text-[16px] font-semibold gap-2 text-white hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                        <i class="fi fi-sr-square-plus opacity-70 flex justify-center items-center text-[18px]"></i>
                        Add Student
                    </button>
                @endrole
            </div>

            <!-- Search and Filters -->
            <div class="flex flex-row justify-between items-center mb-4 gap-4">
                <label for="myCustomSearch"
                    class="flex flex-row justify-start items-center border-2 border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-2 gap-2 w-[70%] outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 focus-within:shadow-lg transition duration-150 shadow-sm">
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
                @role(['super_admin', 'head_teacher'])
                    <button id="add-subject-modal-btn"
                        class="self-end flex flex-row justify-center items-center bg-[#199BCF] py-2 px-3 rounded-xl text-[16px] font-semibold gap-2 text-white hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                        <i class="fi fi-sr-square-plus opacity-70 flex justify-center items-center text-[18px]"></i>

                        Subject
                    </button>
                @endrole
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
                                        class="text-sm font-medium">{{ $sectionSubject->teacher->name ?? 'Not assigned' }}</span>
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
                            <div class="flex flex-row gap-2">
                                <button data-section-subject-id="{{ $sectionSubject->id }}"
                                    id="edit-subject-modal-btn-{{ $sectionSubject->id }}"
                                    class="edit-btns px-3 py-1 text-xs font-medium text-blue-600 bg-blue-100 rounded hover:bg-blue-200 transition duration-150">
                                    <i class="fi fi-rr-edit text-xs mr-1"></i>Edit
                                </button>
                                <button data-section-subject-id="{{ $sectionSubject->id }}"
                                    id="open-delete-subject-modal-btn-{{ $sectionSubject->id }}"
                                    class="delete-subject-btn px-3 py-1 text-xs font-medium text-red-600 bg-red-100 rounded hover:bg-red-200 transition duration-150">
                                    <i class="fi fi-rr-trash text-xs mr-1"></i>Remove
                                </button>
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
            // Initialize modals
            initModal('import-modal', 'import-modal-btn', 'import-modal-close-btn', 'cancel-btn',
                'modal-container-1');
            // Only initialize add-student modal if button exists (role check passed)
            const addStudentBtn = document.getElementById('add-student-modal-btn');
            if (addStudentBtn) {
                initModal('add-student-modal', 'add-student-modal-btn', 'add-student-modal-close-btn',
                    'add-student-cancel-btn', 'modal-container-2');
            }
            initModal('edit-section-modal', 'edit-section-modal-btn', 'edit-section-close-btn',
                'edit-section-cancel-btn', 'modal-container-3');
            initModal('delete-section-modal', 'delete-section-modal-btn', 'delete-section-close-btn',
                'delete-section-cancel-btn', 'modal-container-delete-section');

            // Only initialize add-subject modal if button exists (role check passed)
            const addSubjectBtn = document.getElementById('add-subject-modal-btn');
            if (addSubjectBtn) {
                initModal('add-subject-modal', 'add-subject-modal-btn', 'add-subject-modal-close-btn',
                    'add-subject-cancel-btn', 'modal-container-4');
            }

            // Refresh student list when modal is opened (only if button exists)
            if (addStudentBtn) {
                addStudentBtn.addEventListener('click', function() {
                    refreshStudentList();
                });
            }

            // Add event listener for Add Subject modal button (only if button exists)
            if (addSubjectBtn) {
                addSubjectBtn.addEventListener('click', function() {
                    clearAddSubjectForm();
                    loadSubjectsAndTeachers();
                });
            }

            // Clear form when modal is closed (only if modal exists)
            const addSubjectCloseBtn = document.getElementById('add-subject-modal-close-btn');
            if (addSubjectCloseBtn) {
                addSubjectCloseBtn.addEventListener('click', function() {
                    clearAddSubjectForm();
                });
            }

            // Clear form when cancel button is clicked (only if button exists)
            const addSubjectCancelBtn = document.getElementById('add-subject-cancel-btn');
            if (addSubjectCancelBtn) {
                addSubjectCancelBtn.addEventListener('click', function() {
                    clearAddSubjectForm();
                });
            }

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
                                    <button data-student-id='${data}' id="open-delete-modal-btn-${data}" class="delete-student-btn px-2 py-1 text-xs font-medium text-red-600 bg-red-100 rounded hover:bg-red-200 transition duration-150">
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

            // Add event listener for edit section modal button to load teachers
            const editSectionBtn = document.getElementById('edit-section-modal-btn');
            if (editSectionBtn) {
                editSectionBtn.addEventListener('click', function() {
                    loadTeachersForEditSection();
                    populateEditSectionForm();
                });
            }

            // Function to load teachers for edit section form
            async function loadTeachersForEditSection() {
                try {
                    const response = await fetch('/getTeachers');
                    const data = await response.json();

                    const teacherSelect = document.getElementById('teacher_id');
                    teacherSelect.innerHTML = '<option value="" selected>Select a teacher (optional)</option>';

                    if (data.teachers && data.teachers.length > 0) {
                        data.teachers.forEach(teacher => {
                            const option = document.createElement('option');
                            option.value = teacher.id;
                            option.textContent = `${teacher.first_name} ${teacher.last_name}`;
                            teacherSelect.appendChild(option);
                        });
                    }
                } catch (error) {
                    console.error('Error loading teachers for edit section:', error);
                }
            }

            // Function to populate edit section form with current data
            function populateEditSectionForm() {
                // Set current teacher if exists
                const currentTeacherId = @json($section->teacher_id);
                if (currentTeacherId) {
                    setTimeout(() => {
                        document.getElementById('teacher_id').value = currentTeacherId;
                    }, 100);
                }
            }

            // Form submissions
            document.getElementById('edit-section-form').addEventListener('submit', function(e) {
                e.preventDefault();
                closeModal();
                let form = e.target;
                let formData = new FormData(form);
                showLoader("Updating...");

                fetch(`/section/${sectionId}`, {
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
                            sectionName.innerHTML = data.newData['newSectionName'];
                            sectionRoom.innerHTML = data.newData['newRoom'] || 'Not assigned';

                            // Update teacher display in the stats section
                            const teacherDisplay = document.getElementById('section_teacher');
                            if (teacherDisplay) {
                                teacherDisplay.innerHTML = data.newData['newTeacher'];
                            }

                            closeModal('edit-section-modal', 'modal-container-3');
                            showAlert('success', data.success);
                        } else if (data.error) {
                            closeModal('edit-section-modal', 'modal-container-3');
                            showAlert('error', data.error);
                        }
                    })
                    .catch(err => {
                        hideLoader();
                        closeModal('edit-section-modal', 'modal-container-3');
                        showAlert('error', 'Something went wrong');
                    });
            });

            // Function to refresh the student list in the modal
            async function refreshStudentList() {
                try {
                    const response = await fetch(`/getAvailableStudents/${sectionId}`);
                    const data = await response.json();

                    const studentListContainer = document.querySelector(
                        '#add-student-form .relative.flex.flex-col');

                    // Clear ALL existing content except the header
                    const headerRow = studentListContainer.querySelector('.sticky.top-0');
                    studentListContainer.innerHTML = '';
                    if (headerRow) {
                        studentListContainer.appendChild(headerRow);
                    }

                    // Add new student rows
                    if (data.students && data.students.length > 0) {
                        data.students.forEach((student, index) => {
                            const studentRow = document.createElement('div');
                            studentRow.className =
                                'flex flex-row justify-between items-center gap-2 w-full p-3 hover:bg-gray-50 transition duration-150';
                            studentRow.innerHTML = `
                                <div class="w-8 text-sm text-gray-500">${index + 1}</div>
                                <input type="checkbox" name="student[]" id="lrn-${student.lrn}" value="${student.id}" class="peer sr-only" />
                                <label for="lrn-${student.lrn}" class="flex-1 bg-gray-100 peer-checked:bg-green-100 peer-checked:border-green-300 inline-block px-3 py-2 rounded border border-transparent cursor-pointer transition duration-150">
                                    <span class="text-sm">${student.user.last_name}, ${student.user.first_name}</span>
                                </label>
                            `;
                            studentListContainer.appendChild(studentRow);
                        });
                    } else {
                        // Show empty state
                        const emptyState = document.createElement('div');
                        emptyState.className = 'py-8 text-center text-gray-500';
                        emptyState.innerHTML = `
                            <i class="fi fi-sr-user-slash text-2xl mb-2"></i>
                            <p>No students available to assign.</p>
                        `;
                        studentListContainer.appendChild(emptyState);
                    }
                } catch (error) {
                    console.error('Error refreshing student list:', error);
                }
            }

            document.getElementById('add-student-form').addEventListener('submit', function(e) {
                e.preventDefault();
                closeModal();
                let form = e.target;
                let formData = new FormData(form);
                showLoader("Adding...");

                fetch(`/assign-section/${sectionId}`, {
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
                            studentCount.innerHTML = data.count;
                            closeModal('add-student-modal', 'modal-container-2');
                            showAlert('success', data.success);
                            table1.draw();

                            // Refresh the student list in the modal
                            refreshStudentList();

                            // Clear all checkboxes
                            const checkboxes = form.querySelectorAll('input[type="checkbox"]');
                            checkboxes.forEach(checkbox => {
                                checkbox.checked = false;
                            });
                        } else if (data.error) {
                            closeModal('add-student-modal', 'modal-container-2');
                            showAlert('error', data.error);
                        }
                    })
                    .catch(err => {
                        hideLoader();
                        closeModal('add-student-modal', 'modal-container-2');
                        showAlert('error', 'Something went wrong');
                    });
            });

            function closeModal(modalId, modalContainerId) {
                let modal = document.querySelector(`#${modalId}`);
                let body = document.querySelector(`#${modalContainerId}`);
                if (modal && body) {
                    modal.classList.remove('opacity-100', 'scale-100');
                    modal.classList.add('opacity-0', 'pointer-events-none', 'scale-95');
                    body.classList.remove('opacity-100');
                    body.classList.add('opacity-0', 'pointer-events-none');
                }
            }

            // Function to clear the Add Subject form
            function clearAddSubjectForm() {
                // Clear form inputs
                document.getElementById('subject_id').selectedIndex = 0;
                document.getElementById('teacher_id').selectedIndex = 0;
                document.getElementById('room').value = '';
                document.getElementById('start_time').value = '';
                document.getElementById('end_time').value = '';

                // Clear day checkboxes
                const dayCheckboxes = document.querySelectorAll('input[name="days_of_week[]"]');
                dayCheckboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });

                // Hide conflict warning and suggestions
                document.getElementById('schedule-conflict-warning').classList.add('hidden');
                document.getElementById('schedule-suggestions').classList.add('hidden');

                // Reset modal height to default
                document.getElementById('modal-content').classList.remove('max-h-[28rem]', 'max-h-[32rem]');
                document.getElementById('modal-content').classList.add('max-h-96');

                // Enable submit button
                const submitBtn = document.getElementById('add-subject-submit-btn');
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }

            // Function to load subjects and teachers for the modal
            async function loadSubjectsAndTeachers() {
                try {
                    // Load subjects
                    const subjectsResponse = await fetch(`/getAvailableSubjects/${sectionId}`);
                    const subjectsData = await subjectsResponse.json();

                    const subjectSelect = document.getElementById('subject_id');
                    subjectSelect.innerHTML = '<option value="" disabled selected>Select a subject</option>';

                    if (subjectsData.subjects && subjectsData.subjects.length > 0) {
                        subjectsData.subjects.forEach(subject => {
                            const option = document.createElement('option');
                            option.value = subject.id;
                            option.textContent = `${subject.name} (${subject.category})`;
                            subjectSelect.appendChild(option);
                        });
                    } else {
                        const option = document.createElement('option');
                        option.value = '';
                        option.textContent = 'No subjects available';
                        option.disabled = true;
                        subjectSelect.appendChild(option);
                    }

                    // Load teachers
                    const teachersResponse = await fetch('/getTeachers');
                    const teachersData = await teachersResponse.json();

                    const teacherSelect = document.getElementById('teacher_id');
                    teacherSelect.innerHTML = '<option value="" selected>Select a teacher (optional)</option>';

                    if (teachersData.teachers && teachersData.teachers.length > 0) {
                        teachersData.teachers.forEach(teacher => {
                            const option = document.createElement('option');
                            option.value = teacher.id;
                            option.textContent = `${teacher.first_name} ${teacher.last_name}`;
                            teacherSelect.appendChild(option);
                        });
                    }
                } catch (error) {
                    console.error('Error loading subjects and teachers:', error);
                }
            }

            // Function to check schedule conflicts
            async function checkScheduleConflict() {
                const formData = new FormData(document.getElementById('add-subject-form'));
                const data = Object.fromEntries(formData.entries());

                // Convert days_of_week array properly
                const daysOfWeek = [];
                document.querySelectorAll('input[name="days_of_week[]"]:checked').forEach(checkbox => {
                    daysOfWeek.push(checkbox.value);
                });
                data.days_of_week = daysOfWeek;

                try {
                    const response = await fetch(`/checkScheduleConflict/${sectionId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: JSON.stringify(data)
                    });

                    const result = await response.json();
                    const warningDiv = document.getElementById('schedule-conflict-warning');
                    const detailsDiv = document.getElementById('conflict-details');
                    const suggestionsDiv = document.getElementById('schedule-suggestions');
                    const suggestionsList = document.getElementById('suggestions-list');
                    const submitBtn = document.getElementById('add-subject-submit-btn');

                    if (result.has_conflicts) {
                        warningDiv.classList.remove('hidden');
                        detailsDiv.innerHTML = result.conflicts.map(conflict => conflict.message).join('<br>');
                        submitBtn.disabled = true;
                        submitBtn.classList.add('opacity-50', 'cursor-not-allowed');

                        // Show suggestions container if there are suggestions OR no available days
                        if ((result.suggestions && result.suggestions.length > 0) || (result
                                .no_available_days && result.no_available_days.length > 0)) {
                            suggestionsDiv.classList.remove('hidden');
                            suggestionsList.innerHTML = '';

                            // Show available time suggestions
                            if (result.suggestions && result.suggestions.length > 0) {
                                result.suggestions.forEach(suggestion => {
                                    const suggestionBtn = document.createElement('button');
                                    suggestionBtn.type = 'button';
                                    suggestionBtn.className =
                                        'px-2 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded text-xs transition duration-150';
                                    suggestionBtn.textContent = suggestion.display;
                                    suggestionBtn.onclick = () => applySuggestion(suggestion);
                                    suggestionsList.appendChild(suggestionBtn);
                                });
                            }

                            // Show "No available time" message if there are days with no available times
                            if (result.no_available_days && result.no_available_days.length > 0) {
                                const noAvailableDiv = document.createElement('div');
                                noAvailableDiv.className =
                                    'mt-2 p-2 bg-yellow-50 border border-yellow-200 rounded text-xs text-yellow-700';
                                noAvailableDiv.innerHTML = `
                                    <i class="fi fi-sr-exclamation-triangle mr-1"></i>
                                    ${result.no_available_message}
                                `;
                                suggestionsList.appendChild(noAvailableDiv);
                            }

                            // Increase modal height when suggestions are shown
                            document.getElementById('modal-content').classList.remove('max-h-96');
                            document.getElementById('modal-content').classList.add('max-h-[32rem]');
                        } else {
                            suggestionsDiv.classList.add('hidden');
                            // Increase modal height when only conflicts are shown
                            document.getElementById('modal-content').classList.remove('max-h-96');
                            document.getElementById('modal-content').classList.add('max-h-[28rem]');
                        }
                    } else {
                        warningDiv.classList.add('hidden');
                        suggestionsDiv.classList.add('hidden');
                        submitBtn.disabled = false;
                        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');

                        // Reset to default height when no conflicts/suggestions
                        document.getElementById('modal-content').classList.remove('max-h-[28rem]',
                            'max-h-[32rem]');
                        document.getElementById('modal-content').classList.add('max-h-96');
                    }
                } catch (error) {
                    console.error('Error checking schedule conflict:', error);
                }
            }

            // Function to apply a schedule suggestion
            function applySuggestion(suggestion) {
                // Set the start and end times
                document.getElementById('start_time').value = suggestion.start_time;
                document.getElementById('end_time').value = suggestion.end_time;

                // Clear all day checkboxes first
                document.querySelectorAll('input[name="days_of_week[]"]').forEach(checkbox => {
                    checkbox.checked = false;
                });

                // Check the specific day
                const dayCheckbox = document.querySelector(
                    `input[name="days_of_week[]"][value="${suggestion.day}"]`);
                if (dayCheckbox) {
                    dayCheckbox.checked = true;
                }

                // Hide suggestions and re-check for conflicts
                document.getElementById('schedule-suggestions').classList.add('hidden');

                // Reset modal height
                document.getElementById('modal-content').classList.remove('max-h-[28rem]', 'max-h-[32rem]');
                document.getElementById('modal-content').classList.add('max-h-96');

                // Trigger conflict check to update the UI
                setTimeout(() => {
                    checkScheduleConflict();
                }, 100);
            }

            // Add event listeners for live conflict checking
            document.addEventListener('DOMContentLoaded', function() {
                const conflictInputs = ['teacher_id', 'room', 'start_time', 'end_time'];
                conflictInputs.forEach(inputId => {
                    const input = document.getElementById(inputId);
                    if (input) {
                        input.addEventListener('change', checkScheduleConflict);
                    }
                });

                // Add listeners for day checkboxes
                document.querySelectorAll('input[name="days_of_week[]"]').forEach(checkbox => {
                    checkbox.addEventListener('change', checkScheduleConflict);
                });
            });

            // Handle form submission
            document.getElementById('add-subject-form').addEventListener('submit', async function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const data = Object.fromEntries(formData.entries());

                // Convert days_of_week array properly
                const daysOfWeek = [];
                document.querySelectorAll('input[name="days_of_week[]"]:checked').forEach(checkbox => {
                    daysOfWeek.push(checkbox.value);
                });
                data.days_of_week = daysOfWeek;

                // First check for conflicts before submitting
                try {
                    const conflictResponse = await fetch(`/checkScheduleConflict/${sectionId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: JSON.stringify(data)
                    });

                    const conflictResult = await conflictResponse.json();

                    if (conflictResult.has_conflicts) {
                        // Show conflict warning and prevent submission
                        const warningDiv = document.getElementById('schedule-conflict-warning');
                        const detailsDiv = document.getElementById('conflict-details');
                        const suggestionsDiv = document.getElementById('schedule-suggestions');
                        const suggestionsList = document.getElementById('suggestions-list');

                        warningDiv.classList.remove('hidden');
                        detailsDiv.innerHTML = conflictResult.conflicts.map(conflict => conflict
                            .message).join('<br>');

                        // Show suggestions container if there are suggestions OR no available days
                        if ((conflictResult.suggestions && conflictResult.suggestions.length > 0) || (
                                conflictResult.no_available_days && conflictResult.no_available_days
                                .length > 0)) {
                            suggestionsDiv.classList.remove('hidden');
                            suggestionsList.innerHTML = '';

                            // Show available time suggestions
                            if (conflictResult.suggestions && conflictResult.suggestions.length > 0) {
                                conflictResult.suggestions.forEach(suggestion => {
                                    const suggestionBtn = document.createElement('button');
                                    suggestionBtn.type = 'button';
                                    suggestionBtn.className =
                                        'px-2 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded text-xs transition duration-150';
                                    suggestionBtn.textContent = suggestion.display;
                                    suggestionBtn.onclick = () => applySuggestion(suggestion);
                                    suggestionsList.appendChild(suggestionBtn);
                                });
                            }

                            // Show "No available time" message if there are days with no available times
                            if (conflictResult.no_available_days && conflictResult.no_available_days
                                .length > 0) {
                                const noAvailableDiv = document.createElement('div');
                                noAvailableDiv.className =
                                    'mt-2 p-2 bg-yellow-50 border border-yellow-200 rounded text-xs text-yellow-700';
                                noAvailableDiv.innerHTML = `
                                    <i class="fi fi-sr-exclamation-triangle mr-1"></i>
                                    ${conflictResult.no_available_message}
                                `;
                                suggestionsList.appendChild(noAvailableDiv);
                            }

                            // Increase modal height when suggestions are shown
                            document.getElementById('modal-content').classList.remove('max-h-96');
                            document.getElementById('modal-content').classList.add('max-h-[32rem]');
                        } else {
                            suggestionsDiv.classList.add('hidden');
                            // Increase modal height when only conflicts are shown
                            document.getElementById('modal-content').classList.remove('max-h-96');
                            document.getElementById('modal-content').classList.add('max-h-[28rem]');
                        }

                        showAlert('error',
                            'Schedule conflicts detected. Please resolve conflicts before submitting.'
                        );
                        return; // Stop submission
                    }

                    // No conflicts, proceed with submission
                    const response = await fetch(`/assignSubject/${sectionId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: JSON.stringify(data)
                    });

                    const result = await response.json();

                    if (result.success) {
                        closeModal('add-subject-modal', 'modal-container-4');
                        showAlert('success', result.success);

                        // Refresh the page to show the new subject
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        showAlert('error', result.error || 'Failed to assign subject');
                    }
                } catch (error) {
                    console.error('Error assigning subject:', error);
                    showAlert('error', 'Something went wrong');
                }
            });

            // Initialize each edit modal of subjects
            document.querySelectorAll('.edit-btns').forEach(btns => {
                let id = btns.getAttribute('data-section-subject-id');
                initModal('edit-subject-modal', `edit-subject-modal-btn-${id}`,
                    'edit-subject-modal-close-btn',
                    'edit-subject-cancel-btn', 'modal-container-5');

                // Add click handler to populate edit form
                btns.addEventListener('click', function() {
                    populateEditForm(id);
                });
            });

            // Initialize delete student modals dynamically
            initializeDeleteStudentModals();

            // Reinitialize delete modals after table draw
            table1.on('draw', function() {
                initializeDeleteStudentModals();
            });

            // Initialize delete subject modals dynamically
            initializeDeleteSubjectModals();

            // Function to populate edit form with existing data
            async function populateEditForm(sectionSubjectId) {
                try {
                    // Load teachers for the edit form
                    await loadTeachersForEdit();

                    // Get the section subject data from the server
                    const response = await fetch(`/getSectionSubject/${sectionSubjectId}`);

                    if (!response.ok) {
                        throw new Error(`Failed to fetch subject data: ${response.status}`);
                    }

                    const sectionSubject = await response.json();

                    if (sectionSubject) {
                        // Populate form fields with CURRENT data
                        document.getElementById('edit-subject_name').value = sectionSubject.subject_name || '';
                        document.getElementById('edit-teacher_id').value = sectionSubject.teacher_id || '';
                        document.getElementById('edit-room').value = sectionSubject.room || '';

                        // Format time to remove seconds for HTML time input (HH:MM:SS -> HH:MM)
                        document.getElementById('edit-start_time').value = sectionSubject.start_time ?
                            sectionSubject.start_time.substring(0, 5) : '';
                        document.getElementById('edit-end_time').value = sectionSubject.end_time ?
                            sectionSubject.end_time.substring(0, 5) : '';

                        // Populate days of week checkboxes
                        document.querySelectorAll('#edit-subject-form input[name="days_of_week[]"]').forEach(
                            checkbox => {
                                checkbox.checked = sectionSubject.days_of_week && sectionSubject
                                    .days_of_week.includes(checkbox.value);
                            });

                        // Set the section subject ID
                        document.getElementById('edit-section-subject-id').value = sectionSubjectId;

                        console.log('Edit form populated with current data:', sectionSubject);
                    } else {
                        throw new Error('No subject data received from server');
                    }
                } catch (error) {
                    console.error('Error populating edit form:', error);
                    showAlert('error', 'Failed to load subject data. Please try again.');
                }
            }


            // Function to load teachers for edit form
            async function loadTeachersForEdit() {
                try {
                    const response = await fetch('/getTeachers');
                    const data = await response.json();

                    const teacherSelect = document.getElementById('edit-teacher_id');
                    teacherSelect.innerHTML = '<option value="" selected>Select a teacher (optional)</option>';

                    data.teachers.forEach(teacher => {
                        const option = document.createElement('option');
                        option.value = teacher.id;
                        option.textContent = `${teacher.first_name} ${teacher.last_name}`;
                        teacherSelect.appendChild(option);
                    });
                } catch (error) {
                    console.error('Error loading teachers for edit:', error);
                }
            }

            // Function to check schedule conflicts for edit form
            async function checkEditScheduleConflict() {
                const formData = new FormData(document.getElementById('edit-subject-form'));
                const data = Object.fromEntries(formData.entries());

                // Convert days_of_week array properly
                const daysOfWeek = [];
                document.querySelectorAll('#edit-subject-form input[name="days_of_week[]"]:checked').forEach(
                    checkbox => {
                        daysOfWeek.push(checkbox.value);
                    });
                data.days_of_week = daysOfWeek;

                // Format time to remove seconds (HH:MM:SS -> HH:MM)
                if (data.start_time) {
                    data.start_time = data.start_time.substring(0, 5);
                }
                if (data.end_time) {
                    data.end_time = data.end_time.substring(0, 5);
                }

                // Add section_subject_id to exclude current record from conflict detection
                const sectionSubjectId = document.getElementById('edit-section-subject-id').value;
                if (sectionSubjectId) {
                    data.section_subject_id = parseInt(sectionSubjectId);
                }

                try {
                    const response = await fetch(`/checkScheduleConflict/${sectionId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: JSON.stringify(data)
                    });

                    const result = await response.json();
                    const warningDiv = document.getElementById('edit-schedule-conflict-warning');
                    const detailsDiv = document.getElementById('edit-conflict-details');
                    const suggestionsDiv = document.getElementById('edit-schedule-suggestions');
                    const suggestionsList = document.getElementById('edit-suggestions-list');
                    const submitBtn = document.getElementById('edit-subject-submit-btn');

                    if (result.has_conflicts) {
                        warningDiv.classList.remove('hidden');
                        detailsDiv.innerHTML = result.conflicts.map(conflict => conflict.message).join('<br>');
                        submitBtn.disabled = true;
                        submitBtn.classList.add('opacity-50', 'cursor-not-allowed');

                        // Show suggestions if available
                        if ((result.suggestions && result.suggestions.length > 0) || (result
                                .no_available_days && result.no_available_days.length > 0)) {
                            suggestionsDiv.classList.remove('hidden');
                            suggestionsList.innerHTML = '';

                            if (result.suggestions && result.suggestions.length > 0) {
                                result.suggestions.forEach(suggestion => {
                                    const suggestionBtn = document.createElement('button');
                                    suggestionBtn.type = 'button';
                                    suggestionBtn.className =
                                        'px-2 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded text-xs transition duration-150';
                                    suggestionBtn.textContent = suggestion.display;
                                    suggestionBtn.onclick = () => applyEditSuggestion(suggestion);
                                    suggestionsList.appendChild(suggestionBtn);
                                });
                            }

                            if (result.no_available_days && result.no_available_days.length > 0) {
                                const noAvailableDiv = document.createElement('div');
                                noAvailableDiv.className =
                                    'mt-2 p-2 bg-yellow-50 border border-yellow-200 rounded text-xs text-yellow-700';
                                noAvailableDiv.innerHTML = `
                                    <i class="fi fi-sr-exclamation-triangle mr-1"></i>
                                    ${result.no_available_message}
                                `;
                                suggestionsList.appendChild(noAvailableDiv);
                            }

                            document.getElementById('modal-content').classList.remove('max-h-96');
                            document.getElementById('modal-content').classList.add('max-h-[32rem]');
                        } else {
                            suggestionsDiv.classList.add('hidden');
                            document.getElementById('modal-content').classList.remove('max-h-96');
                            document.getElementById('modal-content').classList.add('max-h-[28rem]');
                        }
                    } else {
                        warningDiv.classList.add('hidden');
                        suggestionsDiv.classList.add('hidden');
                        submitBtn.disabled = false;
                        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');

                        document.getElementById('modal-content').classList.remove('max-h-[28rem]',
                            'max-h-[32rem]');
                        document.getElementById('modal-content').classList.add('max-h-96');
                    }
                } catch (error) {
                    console.error('Error checking edit schedule conflict:', error);
                }
            }

            // Function to apply suggestion to edit form
            function applyEditSuggestion(suggestion) {
                if (suggestion.teacher_id) {
                    document.getElementById('edit-teacher_id').value = suggestion.teacher_id;
                }
                if (suggestion.room) {
                    document.getElementById('edit-room').value = suggestion.room;
                }
                if (suggestion.start_time) {
                    // Format time to remove seconds for HTML time input (HH:MM:SS -> HH:MM)
                    document.getElementById('edit-start_time').value = suggestion.start_time.substring(0, 5);
                }
                if (suggestion.end_time) {
                    // Format time to remove seconds for HTML time input (HH:MM:SS -> HH:MM)
                    document.getElementById('edit-end_time').value = suggestion.end_time.substring(0, 5);
                }
                if (suggestion.days_of_week) {
                    // Clear existing checkboxes
                    document.querySelectorAll('#edit-subject-form input[name="days_of_week[]"]').forEach(
                        checkbox => {
                            checkbox.checked = false;
                        });
                    // Check suggested days
                    suggestion.days_of_week.forEach(day => {
                        const checkbox = document.getElementById(`edit-day-${day.toLowerCase()}`);
                        if (checkbox) checkbox.checked = true;
                    });
                }

                // Re-check conflicts after applying suggestion
                setTimeout(() => {
                    checkEditScheduleConflict();
                }, 100);
            }

            // Add event listeners for live conflict checking in edit form
            document.addEventListener('DOMContentLoaded', function() {
                const editConflictInputs = ['edit-teacher_id', 'edit-room', 'edit-start_time',
                    'edit-end_time'
                ];
                editConflictInputs.forEach(inputId => {
                    const input = document.getElementById(inputId);
                    if (input) {
                        input.addEventListener('change', checkEditScheduleConflict);
                    }
                });

                // Add listeners for edit day checkboxes
                document.querySelectorAll('#edit-subject-form input[name="days_of_week[]"]').forEach(
                    checkbox => {
                        checkbox.addEventListener('change', checkEditScheduleConflict);
                    });
            });

            // Handle edit form submission
            document.getElementById('edit-subject-form').addEventListener('submit', async function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const data = Object.fromEntries(formData.entries());

                // Convert days_of_week array properly
                const daysOfWeek = [];
                document.querySelectorAll('#edit-subject-form input[name="days_of_week[]"]:checked')
                    .forEach(checkbox => {
                        daysOfWeek.push(checkbox.value);
                    });
                data.days_of_week = daysOfWeek;

                // Format time to remove seconds (HH:MM:SS -> HH:MM)
                if (data.start_time) {
                    data.start_time = data.start_time.substring(0, 5);
                }
                if (data.end_time) {
                    data.end_time = data.end_time.substring(0, 5);
                }

                // Add section_subject_id to exclude current record from conflict detection
                const sectionSubjectId = document.getElementById('edit-section-subject-id').value;
                if (sectionSubjectId) {
                    data.section_subject_id = parseInt(sectionSubjectId);
                }

                // First check for conflicts before submitting
                try {
                    const conflictResponse = await fetch(`/checkScheduleConflict/${sectionId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: JSON.stringify(data)
                    });

                    const conflictResult = await conflictResponse.json();

                    if (conflictResult.has_conflicts) {
                        // Show conflict warning and prevent submission
                        const warningDiv = document.getElementById('edit-schedule-conflict-warning');
                        const detailsDiv = document.getElementById('edit-conflict-details');
                        const suggestionsDiv = document.getElementById('edit-schedule-suggestions');
                        const suggestionsList = document.getElementById('edit-suggestions-list');

                        warningDiv.classList.remove('hidden');
                        detailsDiv.innerHTML = conflictResult.conflicts.map(conflict => conflict
                            .message).join('<br>');

                        if ((conflictResult.suggestions && conflictResult.suggestions.length > 0) || (
                                conflictResult.no_available_days && conflictResult.no_available_days
                                .length > 0)) {
                            suggestionsDiv.classList.remove('hidden');
                            suggestionsList.innerHTML = '';

                            if (conflictResult.suggestions && conflictResult.suggestions.length > 0) {
                                conflictResult.suggestions.forEach(suggestion => {
                                    const suggestionBtn = document.createElement('button');
                                    suggestionBtn.type = 'button';
                                    suggestionBtn.className =
                                        'px-2 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded text-xs transition duration-150';
                                    suggestionBtn.textContent = suggestion.display;
                                    suggestionBtn.onclick = () => applyEditSuggestion(
                                        suggestion);
                                    suggestionsList.appendChild(suggestionBtn);
                                });
                            }

                            if (conflictResult.no_available_days && conflictResult.no_available_days
                                .length > 0) {
                                const noAvailableDiv = document.createElement('div');
                                noAvailableDiv.className =
                                    'mt-2 p-2 bg-yellow-50 border border-yellow-200 rounded text-xs text-yellow-700';
                                noAvailableDiv.innerHTML = `
                                    <i class="fi fi-sr-exclamation-triangle mr-1"></i>
                                    ${conflictResult.no_available_message}
                                `;
                                suggestionsList.appendChild(noAvailableDiv);
                            }
                        }

                        showAlert('error', 'Please resolve schedule conflicts before updating');
                        return;
                    }
                } catch (error) {
                    console.error('Error checking conflicts:', error);
                    showAlert('error', 'Failed to check for conflicts');
                    return;
                }

                // If no conflicts, proceed with update
                try {
                    showLoader();

                    const response = await fetch(`/updateSubject/${sectionId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: JSON.stringify(data)
                    });

                    const result = await response.json();
                    hideLoader();

                    if (response.ok) {
                        showAlert('success', result.success || 'Subject updated successfully');
                        // Close modal
                        document.getElementById('edit-subject-modal-close-btn').click();
                        // Reload page to show updated data
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        showAlert('error', result.error || 'Failed to update subject');
                    }
                } catch (error) {
                    hideLoader();
                    console.error('Error updating subject:', error);
                    showAlert('error', 'Something went wrong');
                }
            });

            // Initialize delete student modals dynamically
            function initializeDeleteStudentModals() {
                document.querySelectorAll('.delete-student-btn').forEach((button) => {
                    let studentId = button.getAttribute('data-student-id');
                    let buttonId = `open-delete-modal-btn-${studentId}`;

                    // Initialize modal for this specific button
                    initModal('delete-student-modal', buttonId, 'delete-student-close-btn',
                        'delete-student-cancel-btn', 'modal-container-delete-student');

                    button.addEventListener('click', () => {
                        // Clear any existing hidden inputs first
                        let form = document.getElementById('delete-student-form');
                        let existingInputs = form.querySelectorAll('input[name="student_id"]');
                        existingInputs.forEach(input => input.remove());

                        // Set the form action dynamically
                        form.action = `/removeStudentFromSection/${sectionId}`;

                        // Add student ID as hidden input
                        let studentIdInput = document.createElement('input');
                        studentIdInput.type = 'hidden';
                        studentIdInput.name = 'student_id';
                        studentIdInput.value = studentId;
                        form.appendChild(studentIdInput);

                        console.log('Delete modal opened for student ID:', studentId);
                    });
                });
            }

            // Initialize delete subject modals dynamically
            function initializeDeleteSubjectModals() {
                document.querySelectorAll('.delete-subject-btn').forEach((button) => {
                    let sectionSubjectId = button.getAttribute('data-section-subject-id');
                    let buttonId = `open-delete-subject-modal-btn-${sectionSubjectId}`;

                    // Initialize modal for this specific button
                    initModal('delete-subject-modal', buttonId, 'delete-subject-close-btn',
                        'delete-subject-cancel-btn', 'modal-container-delete-subject');

                    button.addEventListener('click', () => {
                        // Clear any existing hidden inputs first
                        let form = document.getElementById('delete-subject-form');
                        let existingInputs = form.querySelectorAll(
                            'input[name="section_subject_id"]');
                        existingInputs.forEach(input => input.remove());

                        // Set the form action dynamically
                        form.action = `/removeSubjectFromSection/${sectionId}`;

                        // Add section subject ID as hidden input
                        let sectionSubjectIdInput = document.createElement('input');
                        sectionSubjectIdInput.type = 'hidden';
                        sectionSubjectIdInput.name = 'section_subject_id';
                        sectionSubjectIdInput.value = sectionSubjectId;
                        form.appendChild(sectionSubjectIdInput);

                        console.log('Delete modal opened for section subject ID:',
                            sectionSubjectId);
                    });
                });
            }

            // Delete subject form submission
            const deleteSubjectForm = document.getElementById('delete-subject-form');
            if (deleteSubjectForm) {
                deleteSubjectForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(this);
                    const sectionSubjectId = formData.get('section_subject_id');

                    showLoader("Removing subject...");
                    fetch(`/removeSubjectFromSection/${sectionId}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                section_subject_id: sectionSubjectId
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            hideLoader();
                            if (data.success === true) {
                                showAlert('success', data.message);

                                // Close modal
                                document.getElementById('delete-subject-close-btn').click();

                                // Reload page to show updated subjects
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1000);
                            } else {
                                showAlert('error', data.message || 'Failed to remove subject');
                            }
                        })
                        .catch(error => {
                            hideLoader();
                            console.error('Error:', error);
                            showAlert('error', 'An error occurred while removing the subject');
                        });
                });
            }

            // Delete student form submission
            const deleteStudentForm = document.getElementById('delete-student-form');
            if (deleteStudentForm) {
                deleteStudentForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(this);
                    const studentId = formData.get('student_id');

                    showLoader("Removing student...");
                    fetch(`/removeStudentFromSection/${sectionId}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                student_id: studentId
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            hideLoader();
                            if (data.success === true) {
                                showAlert('success', data.message);
                                table1.draw(); // Refresh the table

                                // Update student count
                                if (data.studentCount !== undefined) {
                                    studentCount.innerHTML = data.studentCount;
                                }

                                // Close modal
                                document.getElementById('delete-student-close-btn').click();
                            } else {
                                showAlert('error', data.message || 'Failed to remove student');
                            }
                        })
                        .catch(error => {
                            hideLoader();
                            console.error('Error:', error);
                            showAlert('error', 'An error occurred while removing the student');
                        });
                });
            }

            // Delete section form submission
            const deleteSectionForm = document.getElementById('delete-section-form');
            if (deleteSectionForm) {
                deleteSectionForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    showLoader("Deleting section...");
                    fetch(`/sections/${sectionId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            hideLoader();
                            if (data.success === true) {
                                showAlert('success', data.message);

                                // Close modal
                                document.getElementById('delete-section-close-btn').click();

                                // Redirect to the URL provided by the backend (or default to tracks)
                                setTimeout(() => {
                                    window.location.href = data.redirect_url || '/tracks';
                                }, 1500);
                            } else {
                                showAlert('error', data.message || 'Failed to delete section');
                            }
                        })
                        .catch(error => {
                            hideLoader();
                            console.error('Error:', error);
                            showAlert('error', 'An error occurred while deleting the section');
                        });
                });
            }

            // Add event listener for delete section modal button to check student count
            const deleteSectionBtn = document.getElementById('delete-section-modal-btn');
            if (deleteSectionBtn) {
                deleteSectionBtn.addEventListener('click', function() {
                    // Get current student count
                    const currentStudentCount = parseInt(studentCount.textContent) || 0;

                    // Show/hide warning based on student count
                    const warningDiv = document.getElementById('student-warning');
                    const studentCountSpan = document.getElementById('student-count-warning');

                    if (currentStudentCount > 0) {
                        warningDiv.classList.remove('hidden');
                        studentCountSpan.textContent = currentStudentCount;
                    } else {
                        warningDiv.classList.add('hidden');
                    }
                });
            }

            // Initialize page
            window.onload = function() {
                pageLengthSelection.selectedIndex = 0;
                genderSelection.selectedIndex = 0;
            }
        });
    </script>
@endpush
