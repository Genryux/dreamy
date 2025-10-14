@extends('layouts.admin')
@section('breadcrumbs')
    <nav aria-label="Breadcrumb" class="mb-4 mt-2">
        <ol class="flex items-center gap-1 text-sm text-gray-700">
            <li class="rtl:rotate-180 border border-gray-300 bg-gray-100 p-2 rounded-lg mr-1">
                <a href="/applications/pending" class="block transition-colors hover:text-gray-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 rotate-180" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                </a>
            </li>
            <li>
                <a href="/applications/pending" class="block transition-colors hover:text-gray-500 text-gray-400">
                    Applications
                </a>
            </li>

            <li class="rtl:rotate-180">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 opacity-60" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
            </li>

            <li>
                <a class="block transition-colors hover:text-gray-500 text-gray-500"> Pending Applications
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
                <a href="#" class="block transition-colors hover:text-gray-900"> Application Form Details </a>
            </li>
        </ol>
    </nav>
@endsection
@section('modal')
    <x-modal modal_id="accept-application-modal" modal_name="Accept & Schedule"
        close_btn_id="accept-application-modal-close-btn" modal_container_id="modal-container-1">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-edit flex justify-center items-center '></i>
        </x-slot>


        <form id="admission-form" class="p-6">
            @csrf
            <input type="hidden" name="action" value="accept-with-schedule">
            <div class="space-y-6">
                <!-- Date and Time Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Date -->
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fi fi-rr-calendar mr-2"></i>
                            Admission Exam Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="date" id="date" required
                            class="w-full border-2 border-gray-300 bg-gray-100 rounded-lg px-3 py-2 outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 transition duration-200 placeholder:italic placeholder:text-[14px] text-[14px]">
                    </div>

                    <!-- Time -->
                    <div>
                        <label for="time" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fi fi-rr-clock mr-2"></i>
                            Admission Exam Time <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="time" id="time" required
                            class="w-full border-2 border-gray-300 bg-gray-100 rounded-lg px-3 py-2 outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 transition duration-200 placeholder:italic placeholder:text-[14px] text-[14px]">
                    </div>
                </div>

                <!-- Location -->
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-marker mr-2"></i>
                        Admission Location <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="location" id="location" required
                        placeholder="e.g., Main Office, Conference Room A"
                        class="w-full border-2 border-gray-300 bg-gray-100 rounded-lg px-3 py-2 outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 transition duration-200 placeholder:italic placeholder:text-[14px] text-[14px]">
                </div>

                <!-- Person to look for -->
                <div>
                    <label for="contact_person" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-user mr-2"></i>
                        Contact Person
                    </label>
                    <select name="contact_person" id="contact_person"
                        class="w-full border-2 border-gray-300 bg-gray-100 rounded-lg px-3 py-2 outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 transition duration-200 placeholder:italic placeholder:text-[14px] text-[14px]">
                        <option value="" disabled>Select contact person (optional)</option>
                        @forelse (\App\Models\Teacher::with(['user', 'program'])->where('status', 'active')->get() as $teacher)
                            <option value="{{ $teacher->id }}">
                                {{ $teacher->getFullNameAttribute() }}
                                @if ($teacher->program)
                                    - {{ $teacher->program->name }}
                                @endif
                            </option>
                        @empty
                            <option value="" disabled>Not teacher was found.</option>
                        @endforelse
                    </select>
                </div>

                <!-- Additional Information -->
                <div>
                    <label for="add_info" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-info mr-2"></i>
                        Additional Information
                    </label>
                    <textarea name="add_info" id="add_info" rows="4"
                        placeholder="Any additional notes or instructions for the admission exam..."
                        class="w-full border-2 border-gray-300 bg-gray-100 rounded-lg px-3 py-2 outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 transition duration-200 placeholder:italic placeholder:text-[14px] text-[14px] resize-none"></textarea>
                </div>
            </div>
        </form>


        <x-slot name="modal_buttons">
            <button id="cancel-btn"
                class="bg-gray-50 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-100 hover:ring hover:ring-gray-200 transition duration-150">
                Cancel
            </button>
            {{-- This button will acts as the submit button --}}
            <button type="submit" form="admission-form"
                class="self-end flex flex-row justify-center items-center bg-[#199BCF] py-2 px-3 rounded-xl text-[14px] font-semibold gap-2 text-white hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                Schedule Admission Exam
            </button>
        </x-slot>

    </x-modal>

    {{-- Reject Application Modal --}}
    <x-modal modal_id="reject-application-modal" modal_name="Reject Application"
        close_btn_id="reject-application-modal-close-btn" modal_container_id="modal-container-2">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-cross flex justify-center items-center text-red-500'></i>
        </x-slot>

        <form id="reject-form" class="p-6">
            @csrf
            <div class="space-y-6">
                <!-- Rejection Reason -->
                <div>
                    <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-exclamation mr-2"></i>
                        Rejection Reason <span class="text-red-500">*</span>
                    </label>
                    <select name="reason" id="reason" required
                        class="w-full border-2 border-gray-300 bg-gray-100 rounded-lg px-3 py-2 outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 transition duration-200 placeholder:italic placeholder:text-[14px] text-[14px]">
                        <option value="" disabled>Select rejection reason</option>
                        <option value="Incomplete Documents">Incomplete Documents</option>
                        <option value="Does not meet requirements">Does not meet requirements</option>
                        <option value="Failed admission exam">Failed admission exam</option>
                        <option value="No available slots">No available slots</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <!-- Additional Remarks -->
                <div>
                    <label for="remarks" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-comment mr-2"></i>
                        Additional Remarks
                    </label>
                    <textarea name="remarks" id="remarks" rows="4"
                        placeholder="Provide additional details about the rejection (optional)..."
                        class="w-full border-2 border-gray-300 bg-gray-100 rounded-lg px-3 py-2 outline-none focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF]/60 hover:ring hover:ring-[#199BCF]/20 transition duration-200 placeholder:italic placeholder:text-[14px] text-[14px] resize-none"></textarea>
                </div>
            </div>
        </form>

        <x-slot name="modal_buttons">
            <button id="cancel-reject-btn"
                class="bg-gray-50 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-100 hover:ring hover:ring-gray-200 transition duration-150">
                Cancel
            </button>
            {{-- This button will act as the submit button --}}
            <button type="submit" form="reject-form"
                class="self-end flex flex-row justify-center items-center bg-red-500 py-2 px-3 rounded-xl text-[14px] font-semibold gap-2 text-white hover:bg-red-600 hover:scale-95 transition duration-200 shadow-red-500/20 hover:shadow-red-600/20 shadow-lg truncate">
                Reject Application
            </button>
        </x-slot>

    </x-modal>

    {{-- Accept Only Modal --}}
    <x-modal modal_id="accept-only-modal" modal_name="Accept Application" close_btn_id="accept-only-modal-close-btn"
        modal_container_id="modal-container-3">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-check flex justify-center items-center text-green-500'></i>
        </x-slot>

        <form id="accept-only-form" class="p-6">
            @csrf
            <input type="hidden" name="action" value="accept-only">

            <div class="space-y-6">
                <!-- Confirmation Message -->
                <div class="text-center">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Accept Application</h3>
                    <p class="text-sm text-gray-500 mb-4">
                        Are you sure you want to accept this application? The applicant will be moved to the selected status
                        without scheduling an admission exam.
                    </p>
                </div>
            </div>
        </form>

        <x-slot name="modal_buttons">
            <button id="cancel-accept-only-btn"
                class="bg-gray-50 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-100 hover:ring hover:ring-gray-200 transition duration-150">
                Cancel
            </button>
            {{-- This button will act as the submit button --}}
            <button type="submit" form="accept-only-form"
                class="self-end flex flex-row justify-center items-center bg-green-500 py-2 px-3 rounded-xl text-[14px] font-semibold gap-2 text-white hover:bg-green-600 hover:scale-95 transition duration-200 shadow-green-500/20 hover:shadow-green-600/20 shadow-lg truncate">
                Accept Application
            </button>
        </x-slot>

    </x-modal>
@endsection
@section('header')
    <div class="flex flex-row justify-between items-start text-start px-[14px] py-2">
        <div>
            <h1 class="text-[20px] font-black">Form Details</h1>
            <p class="text-[14px]  text-gray-900/60">View and manage strand details, sections, subjects, and student.
            </p>
        </div>
    </div>
@endsection
@section('stat')
    <x-header-container>
        <div
            class="flex flex-col justify-center items-center flex-grow p-8 bg-gradient-to-br from-[#199BCF] to-[#1A3165] rounded-xl shadow-[#199BCF]/30 shadow-xl gap-4 text-white">

            <div class="flex flex-row items-center space-x-2">
                <i class="fi fi-rs-member-list flex"></i>
                <h2 class="text-[20px]"> <span class="font-medium text-[20px]">Applicant:</span><span
                        class="opacity-100 font-medium  font-bold">
                        {{ $applicant->applicationForm->last_name . ', ' . $applicant->applicationForm->first_name }}
                    </span>
                </h2>
            </div>
            <div class="flex flex-row items-center space-x-1">
                @if ($applicant->applicationForm->applicant->application_status === 'Pending')
                    <button id="open-accept-only-btn"
                        class="self-end flex flex-row justify-center items-center bg-[#199BCF] py-2 px-3 rounded-xl text-[16px] font-semibold gap-2 text-white hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/30 hover:shadow-[#C8A165]/40 shadow-lg truncate">Accept
                        Only</button>
                    <button id="open-accept-application-btn"
                        class="self-end flex flex-row justify-center items-center bg-[#199BCF] py-2 px-3 rounded-xl text-[16px] font-semibold gap-2 text-white hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/30 hover:shadow-[#C8A165]/40 shadow-lg truncate">Accept
                        & Schedule</button>
                    <button id="open-reject-application-btn"
                        class="self-end flex flex-row justify-center items-center bg-white/20 py-2 px-3 rounded-xl text-[16px] font-semibold gap-2 text-white hover:bg-red-100 hover:scale-95 transition duration-200 shadow-white/20 hover:shadow-red-300/40 hover:text-red-500 shadow-lg truncate">Reject</button>
                @else
                    <div class="flex flex-row items-center space-x-2 text-white/60">
                        <i class="fi fi-rr-info text-lg flex justify-center items-center"></i>
                        <span class="text-sm">Application already processed</span>
                    </div>
                @endif
            </div>

        </div>
    </x-header-container>
@endsection

@section('content')
    <x-alert />

    {{-- Backup check: Show warning if application is no longer pending --}}
    @if ($applicant->application_status !== 'Pending')
        <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
            <div class="flex items-center">
                <i class="fi fi-rr-exclamation text-yellow-600 text-xl mr-3"></i>
                <div>
                    <h3 class="text-sm font-medium text-yellow-800">Application Status Changed</h3>
                    <p class="text-sm text-yellow-700 mt-1">
                        This application is no longer pending. Current status:
                        <strong>{{ $applicant->application_status ?? 'Unknown' }}</strong>
                    </p>
                </div>
            </div>
        </div>
    @endif

    <div class="space-y-3 bg-[#f8f8f8] rounded-xl p-8 shadow-md">
        <div
            class=" border border-[#1e1e1e]/15 rounded-[8px] hover:shadow-xl hover:border-[#199BCF]/50 transition duration-200">
            <table class="text-[#0f111c] w-full">
                <thead class="">
                    <tr class="">
                        <th class="px-6 py-2 bg-[#E3ECFF] text-start rounded-tl-[8px]">Learner Information</th>
                        <th class="bg-[#E3ECFF] text-start rounded-tr-[8px]"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b border-t border-[#1e1e1e]/15 opacity-[0.87]">
                        <td class="px-6 py-2 text-[14px]">Returning (Balik-Aral):</td>
                    </tr>
                    <tr class="opacity-[0.87]">
                        <td class="px-6 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2 text-bold">With
                            LRN:<span class="font-bold"> Yes</span></td>
                        <td class="px-6 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2 text-bold">LRN: <span
                                class="font-bold">{{ $applicant->applicationForm->lrn ?? '-' }}</span></td>
                    </tr>
                    <tr class="opacity-[0.87]">
                        <td class="px-6 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Grade Level to
                            Enroll:<span class="font-bold"> {{ $applicant->applicationForm->grade_level ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Semester:<span
                                class="font-bold"> {{ $applicant->applicationForm->semester_applied ?? '-' }}</span></td>
                    </tr>
                    <tr class="opacity-[0.87]">
                        <td class="px-6 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Primary Track:<span
                                class="font-bold"> {{ $applicant->track->name ?? '-' }}</span></td>
                        <td class="px-6 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Secondary Track:<span
                                class="font-bold">
                                {{ $applicant->program->code . ' - ' . $applicant->program->name ?? '-' }}</span></td>
                    </tr>
                    <tr class="opacity-[0.87]">
                        <td class="px-6 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Last Name:<span
                                class="font-bold"> {{ $applicant->applicationForm->first_name ?? '-' }}</span></td>
                        <td class="px-6 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">First Name:<span
                                class="font-bold"> {{ $applicant->applicationForm->last_name ?? '-' }}</span></td>
                    </tr>
                    <tr class="opacity-[0.87]">
                        <td class="px-6 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Middle Name:<span
                                class="font-bold"> {{ $applicant->applicationForm->middle_name ?? '-' }}</span></td>
                        <td class="px-6 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Extension Name:<span
                                class="font-bold"> {{ $applicant->applicationForm->extension_name ?? '-' }}</span></td>
                    </tr>
                    <tr class="opacity-[0.87]">
                        <td class="px-6 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Birthdate:<span
                                class="font-bold">
                                {{ \Carbon\Carbon::parse($applicant->applicationForm->birthdate)->timezone('Asia/Manila')->format('M. d, Y') ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Age:<span class="font-bold">
                                {{ $applicant->applicationForm->age ?? '-' }}</span></td>
                    </tr>
                    <tr class="opacity-[0.87]">
                        <td class="px-6 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Place of Birth:<span
                                class="font-bold"> {{ $applicant->applicationForm->place_of_birth ?? '-' }}</span></td>
                        <td class="px-6 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Mother Tongue:<span
                                class="font-bold"> {{ $applicant->applicationForm->mother_tongue ?? '-' }}</span></td>
                    </tr>
                    <tr class="opacity-[0.87]">
                        <td class="px-6 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Belong to any IP
                            community:<span class="font-bold">
                                {{ $applicant->applicationForm->belongs_to_ip === 1 ? 'Yes' : 'No' }}</span></td>
                        <td class="px-6 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Beneficiary of 4Ps:<span
                                class="font-bold">
                                {{ $applicant->applicationForm->is_4ps_beneficiary === 1 ? 'Yes' : 'No' }}</span>
                        </td>
                    </tr>
                    <tr class="opacity-[0.87]">
                        <td class="px-6 py-2 text-[14px] border-r border-[#1e1e1e]/15">Learner with disability: <span
                                class="font-bold">
                                {{ $applicant->applicationForm->has_special_needs === 1 ? 'Yes' : 'No' }}</span></td>
                        <td class="px-6 py-2 text-[14px]">Special needs: <span class="font-bold">
                                {{ implode(', ', $applicant->applicationForm->special_needs ?? []) }}</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div
            class=" border border-[#1e1e1e]/15 rounded-[8px] hover:shadow-xl hover:border-[#199BCF]/50 transition duration-200">
            <table class="text-[#0f111c] w-full">
                <thead class="">
                    <tr class="">
                        <th
                            class="border-r border-[#1e1e1e]/15 px-6 py-2 bg-[#E3ECFF] text-start rounded-tl-[8px] text-[16px]">
                            Current Address</th>
                        <th class="px-6 py-2 bg-[#E3ECFF] text-start rounded-tr-[8px] text-[16px]">Permanent Address</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="opacity-[0.87]">
                        <td class="px-6 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2 text-bold">House
                            No:<span class="font-bold"> {{ $applicant->applicationForm->cur_house_no ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">House No:
                            <span class="font-bold"> {{ $applicant->applicationForm->perm_house_no ?? '-' }}</span>
                        </td>
                    </tr>
                    <tr class="opacity-[0.87]">
                        <td class="px-6 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Sitio/Street Name:
                            <span class="font-bold"> {{ $applicant->applicationForm->cur_street ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Sitio/Street
                            Name:<span class="font-bold"> {{ $applicant->applicationForm->perm_street ?? '-' }}</span>
                        </td>
                    </tr>
                    <tr class="opacity-[0.87]">
                        <td class="px-6 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Barangay:<span
                                class="font-bold"> {{ $applicant->applicationForm->cur_barangay ?? '-' }}</span></td>
                        <td class="px-6 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Barangay: <span
                                class="font-bold"> {{ $applicant->applicationForm->perm_barangay ?? '-' }}</span></td>
                    </tr>
                    <tr class="opacity-[0.87]">
                        <td class="px-6 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">
                            Municipality/City:<span class="font-bold">
                                {{ $applicant->applicationForm->cur_city ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Municipality/City:<span
                                class="font-bold"> {{ $applicant->applicationForm->perm_city ?? '-' }}</span></td>
                    </tr>
                    <tr class="opacity-[0.87]">
                        <td class="px-6 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Country:<span
                                class="font-bold"> {{ $applicant->applicationForm->cur_country ?? '-' }}</span></td>
                        <td class="px-6 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Country:<span
                                class="font-bold"> {{ $applicant->applicationForm->perm_country ?? '-' }}</span></td>
                    </tr>
                    <tr class="opacity-[0.87]">
                        <td class="px-6 py-2 text-[14px] border-r border-[#1e1e1e]/15 w-1/2">Zip Code: <span
                                class="font-bold"> {{ $applicant->applicationForm->cur_zip_code ?? '-' }}</span></td>
                        <td class="px-6 py-2 text-[14px] w-1/2">Zip Code: <span class="font-bold">
                                {{ $applicant->applicationForm->perm_zip_code ?? '-' }}</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div
            class=" border border-[#1e1e1e]/15 rounded-[8px] hover:shadow-xl hover:border-[#199BCF]/50 transition duration-200">
            <table class="text-[#0f111c] w-full table-fixed">
                <thead class="">
                    <tr class="">
                        <th
                            class="border-b border-[#1e1e1e]/15 px-6 py-2 bg-[#E3ECFF] text-start rounded-tl-[8px] text-[16px]">
                            Parent/Guardian's Information</th>
                        <th class="border-b border-[#1e1e1e]/15 px-6 py-2 bg-[#E3ECFF] text-start text-[16px]"></th>
                        <th
                            class="border-b border-[#1e1e1e]/15 px-6 py-2 bg-[#E3ECFF] text-start rounded-tr-[8px] text-[16px]">
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="opacity-[0.87]">
                        <td class="px-6 py-2 text-[16px] border-b border-r border-[#1e1e1e]/15 font-bold">Mother's
                            Information:</td>
                        <td class="px-6 py-2 text-[16px] border-b border-r border-[#1e1e1e]/15 font-bold">Father's
                            Information:<span class="font-bold"></span></td>
                        <td class="px-6 py-2 text-[16px] border-b border-[#1e1e1e]/15 font-bold">Guardian's
                            Information:<span class="font-bold"></span></td>
                    </tr>
                    <tr class="opacity-[0.87]">
                        <td class="px-6 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Last Name:<span
                                class="font-bold"> {{ $applicant->applicationForm->mother_last_name ?? '-' }}</span></td>
                        <td class="px-6 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Last Name:<span
                                class="font-bold"> {{ $applicant->applicationForm->father_last_name ?? '-' }}</span></td>
                        <td class="px-6 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Last Name:<span
                                class="font-bold"> {{ $applicant->applicationForm->guardian_last_name ?? '-' }}</span>
                        </td>
                    </tr>
                    <tr class="opacity-[0.87]">
                        <td class="px-6 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">First Name:<span
                                class="font-bold"> {{ $applicant->applicationForm->mother_first_name ?? '-' }}</span></td>
                        <td class="px-6 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">First Name:<span
                                class="font-bold"> {{ $applicant->applicationForm->father_first_name ?? '-' }}</span></td>
                        <td class="px-6 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">First Name:<span
                                class="font-bold"> {{ $applicant->applicationForm->guardian_first_name ?? '-' }}</span>
                        </td>
                    </tr>
                    <tr class="opacity-[0.87]">
                        <td class="px-6 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Middle Name:<span
                                class="font-bold"> {{ $applicant->applicationForm->mother_middle_name ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Middle Name:<span
                                class="font-bold"> {{ $applicant->applicationForm->father_middle_name ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Middle Name:<span
                                class="font-bold"> {{ $applicant->applicationForm->guardian_middle_name ?? '-' }}</span>
                        </td>
                    </tr>
                    <tr class="opacity-[0.87]">
                        <td class="px-6 py-2 text-[14px] border-r border-[#1e1e1e]/15 w-1/2">Contact Number:<span
                                class="font-bold"> {{ $applicant->applicationForm->mother_contact_number ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-2 text-[14px] border-r border-[#1e1e1e]/15 w-1/2">Contact Number:<span
                                class="font-bold"> {{ $applicant->applicationForm->father_contact_number ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-2 text-[14px] w-1/2">Contact Number:<span class="font-bold">
                                {{ $applicant->applicationForm->guardian_contact_number ?? '-' }}</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div
            class=" border border-[#1e1e1e]/15 rounded-[8px] hover:shadow-xl hover:border-[#199BCF]/50 transition duration-200">
            <table class="text-[#0f111c] w-full">
                <thead class="">
                    <tr class="">
                        <th
                            class="border-b border-[#1e1e1e]/15 px-6 py-2 bg-[#E3ECFF] text-start rounded-tl-[8px] text-[16px]">
                            Other Informations </th>
                        <th
                            class="border-b border-[#1e1e1e]/15 px-6 py-2 bg-[#E3ECFF] text-start rounded-tr-[8px] text-[16px]">
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="opacity-[0.87]">
                        <td class="px-6 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2 text-bold">Preferred Class
                            Schedule:<span class="font-bold">
                                {{ $applicant->applicationForm->preferred_sched ?? '-' }}</span></td>
                        <td class="px-6 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2 text-bold"></td>
                    </tr>
                    <tr class="opacity-[0.87]">
                        <td class="px-6 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2 text-bold">Last Grade Level
                            Completed:<span class="font-bold">
                                {{ $applicant->applicationForm->last_grade_level_completed ?? '-' }}</span></td>
                        <td class="px-6 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2 text-bold"></td>
                    </tr>
                    <tr class="opacity-[0.87]">
                        <td class="px-6 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2 text-bold">Lat School
                            Attended:<span class="font-bold">
                                {{ $applicant->applicationForm->last_school_attended ?? '-' }}</span></td>
                        <td class="px-6 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2 text-bold"></td>
                    </tr>
                    <tr class="opacity-[0.87]">
                        <td class="px-6 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2 text-bold">Last School Year
                            Completed:<span class="font-bold">
                                {{ \Carbon\Carbon::parse($applicant->applicationForm->last_school_year_completed)->timezone('Asia/Manila')->format('M. d, Y') ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2 text-bold"></td>
                    </tr>
                    <tr class="opacity-[0.87]">
                        <td class="px-6 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2 text-bold">School Id:<span
                                class="font-bold">
                                {{ $applicant->applicationForm->school_id ?? '-' }}</span></td>
                        <td class="px-6 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2 text-bold"></td>
                    </tr>
                    <tr class="opacity-[0.87]">
                        <td class="px-6 py-2 text-[14px] w-1/2">Date Applied:<span class="font-bold">
                                {{ \Carbon\Carbon::parse($applicant->applicationForm->admission_date)->timezone('Asia/Manila')->format('M. d, Y — g:i A') ?? '-' }}</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="module">
        import {
            initModal
        } from '/js/modal.js';
        import {
            showAlert
        } from "/js/alert.js";
        import {
            showLoader,
            hideLoader
        } from "/js/loader.js";

        const applicantId = @json($applicant->id)

        document.addEventListener("DOMContentLoaded", function() {
            // Initialize modals
            initModal('accept-application-modal', 'open-accept-application-btn',
                'accept-application-modal-close-btn', 'cancel-btn', 'modal-container-1')

            initModal('reject-application-modal', 'open-reject-application-btn',
                'reject-application-modal-close-btn', 'cancel-reject-btn', 'modal-container-2')

            initModal('accept-only-modal', 'open-accept-only-btn',
                'accept-only-modal-close-btn', 'cancel-accept-only-btn', 'modal-container-3')

            // Form submission handler for admission exam
            document.getElementById('admission-form').addEventListener('submit', function(e) {
                e.preventDefault();

                let form = e.target;
                let formData = new FormData(form);

                // Show loader
                showLoader("Scheduling admission exam...");

                fetch(`/schedule-admission/${applicantId}`, {
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        hideLoader();

                        if (data.success) {
                            // Reset form
                            form.reset();

                            // Close modal
                            closeModal('accept-application-modal', 'modal-container-1');

                            // Show success alert
                            showAlert('success', data.message);

                            // Reload page to show updated data
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);

                        } else {
                            console.error('Error:', data.message);
                            closeModal('accept-application-modal', 'modal-container-1');
                            showAlert('error', data.message);
                        }
                    })
                    .catch(err => {
                        hideLoader();
                        console.error('Error:', err);
                        closeModal('accept-application-modal', 'modal-container-1');
                        showAlert('error', 'Something went wrong while scheduling the admission exam');
                    });
            });

            // Form submission handler for reject application
            document.getElementById('reject-form').addEventListener('submit', function(e) {
                e.preventDefault();

                let form = e.target;
                let formData = new FormData(form);

                // Show loader
                showLoader("Rejecting application...");

                fetch(`/reject-application/${applicantId}`, {
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        hideLoader();

                        if (data.success) {
                            // Reset form
                            form.reset();

                            // Close modal
                            closeModal('reject-application-modal', 'modal-container-2');

                            // Show success alert
                            showAlert('success', data.message);

                            // Reload page to show updated data
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);

                        } else {
                            closeModal('reject-application-modal', 'modal-container-2');
                            showAlert('error', data.message);
                        }
                    })
                    .catch(err => {
                        hideLoader();
                        console.error('Error:', err);
                        closeModal('reject-application-modal', 'modal-container-2');
                        showAlert('error', err);
                    });
            });

            // Form submission handler for accept only
            document.getElementById('accept-only-form').addEventListener('submit', function(e) {
                e.preventDefault();

                let form = e.target;
                let formData = new FormData(form);

                // Show loader
                showLoader("Accepting application...");

                fetch(`/schedule-admission/${applicantId}`, {
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        hideLoader();

                        if (data.success) {
                            // Reset form
                            form.reset();

                            // Close modal
                            closeModal('accept-only-modal', 'modal-container-3');

                            // Show success alert
                            showAlert('success', data.message || 'Application accepted successfully!');

                            // Reload page to show updated data
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);

                        } else {
                            console.error('Error:', data.message);
                            closeModal('accept-only-modal', 'modal-container-3');
                            showAlert('error', data.message);
                        }
                    })
                    .catch(err => {
                        hideLoader();
                        console.error('Error:', err);
                        closeModal('accept-only-modal', 'modal-container-3');
                        showAlert('error', 'Something went wrong while accepting the application');
                    });
            });

            // Helper function to close modal
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
        })
    </script>
@endpush
