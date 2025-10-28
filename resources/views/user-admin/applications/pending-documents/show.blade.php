@extends('layouts.admin', ['title' => 'Applications'])

@section('breadcrumbs')
    <nav aria-label="Breadcrumb" class="mb-4 mt-2">
        <ol class="flex items-center gap-1 text-sm text-gray-700">
            <li class="rtl:rotate-180 border border-gray-300 bg-gray-100 p-2 rounded-lg mr-1">
                <a href="/applications/pending-documents" class="block transition-colors hover:text-gray-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 rotate-180" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                </a>
            </li>
            <li>
                <a href="/applications/pending-documents" class="block transition-colors hover:text-gray-500 text-gray-400">
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
                <a class="block transition-colors hover:text-gray-500 text-gray-500"> Pending Documents
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
                <a href="#" class="block transition-colors hover:text-gray-900"> Document Submission Details </a>
            </li>
        </ol>
    </nav>
@endsection

@section('modal')
    <x-modal modal_id="verify-doc-modal" modal_name="Verification Confirmation" close_btn_id="verify-doc-close-btn"
        modal_container_id='modal-container-1'>
        <x-slot name="modal_icon">
        </x-slot>

        <form action="/submit-document/{{ $applicant->id }}" method="POST" id="verify-doc-form">
            @csrf
            @method('PATCH')
            <input type="hidden" name="document_id" id="hidden-doc-id">
            <input type="hidden" name="action" value="verify">
            {{-- This button will serve as an opener of modal when clicked --}}

        </form>


        <div class="flex flex-col justify-center items-center py-8 px-6 font-regular text-[14px] text-center">
            <div class="flex justify-center items-center w-auto p-6 bg-yellow-100 w-[300px] rounded-full">
                <i class='fi fi-ss-exclamation flex justify-center text-[52px] items-center text-yellow-500'></i>
            </div>
            <div class="py-8 px-6 space-y-2 font-regular text-[14px] text-center">
                <p class="text-gray-700 text-[16px] font-semibold">
                    Are you sure you want to verify this document?
                </p>
                <p class="text-gray-500">
                    This action will mark the document as <strong>valid</strong> and <strong>approved</strong>.
                </p>
            </div>

        </div>

        <x-slot name="modal_buttons">
            <button id="cancel-btn"
                class="bg-gray-50 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-100 hover:ring hover:ring-gray-200 transition duration-150">
                Cancel
            </button>
            {{-- This button will acts as the submit button --}}
            <button type="submit" form="verify-doc-form" name="action" value="verify"
                class="bg-[#199BCF] py-2 px-3 rounded-xl text-[14px] font-semibold gap-2 text-white hover:ring hover:ring-[#C8A165]/20 hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                Confirm
            </button>
        </x-slot>

    </x-modal>
    {{-- Reject document modal --}}
    <x-modal modal_id="reject-doc-modal" modal_name="Rejection Confirmation" close_btn_id="reject-doc-close-btn"
        modal_container_id='modal-container-4'>

        <form action="/submit-document/{{ $applicant->id }}" method="POST" id="reject-doc-form">
            @csrf
            @method('PATCH')
            <input type="hidden" name="document_id" id="hidden-reject-doc-id">
            <input type="hidden" name="action" value="reject">
            {{-- This button will serve as an opener of modal when clicked --}}

        </form>

        <div class="flex flex-col justify-center items-center py-8 px-6 font-regular text-[14px] text-center">
            <div class="flex justify-center items-center w-auto p-6 bg-red-100 w-[300px] rounded-full">
                <i class='fi fi-ss-exclamation flex justify-center text-[52px] items-center text-red-500'></i>
            </div>
            <div class="py-8 px-6 space-y-2 font-regular text-[14px] text-center">
                <p class="text-gray-700 text-[16px] font-semibold">
                    Are you sure you want to verify this document?
                </p>
                <p class="text-gray-500">
                    This action will mark the document as <strong>invalid</strong> and <strong>rejected</strong>.
                </p>
            </div>

        </div>

        <x-slot name="modal_buttons">
            <button id="reject-cancel-btn"
                class="bg-gray-50 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-100 hover:ring hover:ring-gray-200 transition duration-150">
                Cancel
            </button>
            {{-- This button will acts as the submit button --}}
            <button type="submit" form="reject-doc-form" name="action" value="reject"
                class="bg-red-500 text-[14px] px-3 py-2 rounded-xl text-white font-bold hover:ring hover:ring-red-200 hover:bg-red-400 transition duration-150 shadow-sm hover:scale-95">
                Reject
            </button>
        </x-slot>

    </x-modal>
    {{-- View document modal --}}
    <x-modal modal_id="view-doc-modal" modal_name="Document Viewer" close_btn_id="view-doc-close-btn"
        modal_container_id='modal-container-3'>
        <x-slot name="modal_icon">
            <i class='fi fi-rs-eye flex justify-center items-center text-blue-500'></i>
        </x-slot>

        <!-- PDF Viewer Container -->
        <div id="pdf-viewer-container" class="hidden flex flex-col h-[80vh]">
            <!-- PDF Navigation Header -->
            <div id="pdf-navigation" class="flex items-center justify-between p-4 bg-gray-50 border-b border-gray-200">
                <button id="prev-page-btn"
                    class="flex flex-row justify-center items-center bg-[#199BCF] py-2 px-3 rounded-xl text-[14px] font-semibold gap-2 text-white hover:ring hover:ring-[#C8A165]/20 hover:bg-[#C8A165] hover:-translate-x-1 transition duration-200 truncate disabled:bg-gray-300 disabled:cursor-not-allowed">
                    <i class="fi fi-rs-angle-left items-center text-[14px]"></i>
                    Previous
                </button>
                <span id="page-info"
                    class="px-4 py-2 bg-white border border-gray-300 rounded-lg font-medium text-gray-700">Page 1 of
                    1</span>
                <button id="next-page-btn"
                    class="flex flex-row justify-center items-center bg-[#199BCF] py-2 px-3 rounded-xl text-[14px] font-semibold gap-2 text-white hover:ring hover:ring-[#C8A165]/20 hover:bg-[#C8A165] hover:translate-x-1 transition duration-200 truncate disabled:bg-gray-300 disabled:cursor-not-allowed">
                    Next
                    <i class="fi fi-rs-angle-right flex justify-center items-center text-[14px]"></i>
                </button>
            </div>

            <!-- PDF Canvas Container -->
            <div class="flex-1 flex items-center justify-center p-4 bg-gray-100 overflow-auto">
                <canvas id="pdfViewerCanvas"
                    class="max-w-full max-h-full border border-gray-300 rounded-lg shadow-lg bg-white"></canvas>
            </div>
        </div>

        <!-- Image Viewer Container -->
        <div id="image-viewer-container"
            class="hidden flex flex-col items-center justify-center p-6 min-h-[400px] max-h-[600px] overflow-hidden">
            <img id="imageViewer" class="max-w-full max-h-full object-contain border border-gray-300 rounded-lg shadow-lg"
                alt="Document preview">
        </div>

        <!-- Loading indicator -->
        <div id="loading-indicator" class="flex flex-col items-center justify-center p-6 min-h-[400px] text-gray-500">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500 mb-2"></div>
            <p>Loading document...</p>
        </div>

        <!-- Error message -->
        <div id="error-message" class="hidden flex flex-col items-center justify-center p-6 min-h-[400px] text-red-500">
            <i class="fi fi-rs-exclamation-triangle text-4xl mb-2"></i>
            <p>Unable to load document</p>
        </div>

        <x-slot name="modal_buttons">
            <button id="external-view-btn"
                class="flex flex-row justify-center items-center bg-[#199BCF] py-2 px-3 rounded-xl text-[14px] font-semibold gap-2 text-white hover:ring hover:ring-[#C8A165]/20 hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                <i class="fi fi-rr-share-square flex justify-center items-center"></i>
                Open in New Tab
            </button>
            <button id="close-viewer-btn"
                class="px-4 py-2 bg-gray-50 border border-gray-300 text-[14px] text-gray-700 rounded-xl hover:bg-gray-200 transition-colors">
                Close
            </button>
        </x-slot>
    </x-modal>
    {{-- Enroll student modal --}}
    <x-modal modal_id="enroll-student-modal" modal_name="Enrollment Confirmation" close_btn_id="enroll-student-close-btn"
        modal_container_id='modal-container-2'>

        <form id="enroll-student-form">
            @csrf
            <input type="hidden" name="action" value="enroll-student">
        </form>

        <div class="flex flex-col justify-center items-center py-8 px-6 font-regular text-[14px] text-center">
            <div class="flex justify-center items-center w-auto p-6 bg-yellow-100 w-[300px] rounded-full">
                <i class='fi fi-ss-exclamation flex justify-center text-[52px] items-center text-yellow-500'></i>
            </div>
            <div class="py-8 px-6 space-y-2 font-regular text-[14px] text-center">
                <p class="text-gray-700 text-[16px] font-semibold">
                    Are you sure you want to enroll this applicant?
                </p>
                <p id="enroll-msg" class="text-gray-500 text-[14px]"></p>
            </div>



            <div class="flex flex-col justify-center items-center gap-2">
                <div>
                    <input id="auto-assign" type="checkbox" value=""
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="auto-assign" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-500">
                        Auto-assign relevant school fees (based on grade level and program)
                    </label>
                </div>
                @if ($schoolFees === null && $schoolSettings === null)
                    {{-- Scenario 1: No school fees & No downpayment --}}
                    <div id="warning-message-both"
                        class="hidden bg-red-50 border border-red-300 text-red-500 rounded-xl py-2.5">
                        <p>No school fees have been set up yet and no down payment is configured. Please create a school fee record and set up down payment to avoid incorrect invoice totals.</p>
                    </div>
                @elseif ($schoolFees === null && $schoolSettings !== null)
                    {{-- Scenario 2: No school fee, with downpayment --}}
                    <div id="warning-message-fees"
                        class="hidden bg-red-50 border border-red-300 text-red-500 rounded-xl py-2.5">
                        <p>No school fees have been set up yet. Please create a school fee record first; otherwise, the system can't assign the right fees to students.</p>
                    </div>
                @elseif ($schoolFees !== null && $schoolSettings === null)
                    {{-- Scenario 3: With school fee, no downpayment --}}
                    <div id="warning-message-settings"
                        class="hidden bg-red-50 border border-red-300 text-red-500 rounded-xl py-2.5">
                        <p>School fees found, but no down payment is configured. Please set it up to avoid incorrect invoice totals.</p>
                    </div>
                @else
                    {{-- Scenario 4: With school fee, with downpayment --}}
                    {{-- Show nothing --}}
                @endif

            </div>

        </div>


        <x-slot name="modal_buttons">
            <button id="putanginamo_cancel-btn"
                class="px-4 py-2 bg-gray-50 border border-gray-300 text-[14px] text-gray-700 rounded-xl hover:bg-gray-200 transition-colors">
                Cancel
            </button>
            {{-- This button will acts as the submit button --}}
            <button type="button" id="enroll-student-submit-btn"
                class="flex flex-row justify-center items-center bg-[#199BCF] py-2 px-3 rounded-xl text-[14px] font-semibold gap-2 text-white hover:ring hover:ring-[#C8A165]/20 hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                Confirm
            </button>
        </x-slot>

    </x-modal>
@endsection

@section('header')
    <div class="flex flex-col justify-center items-start text-start px-[14px] py-2">
        <h1 class="text-[20px] font-black">Document Review</h1>
        <p class="text-[14px]  text-gray-900/60">Review submitted documents here and choose to approve or decline based on
            their accuracy.</p>
    </div>
@endsection

@section('content')
    <x-alert />
    <div class="flex flex-col bg-[#f8f8f8] rounded-xl shadow-sm border border-[#1e1e1e]/10 p-2 text-[14px]">


        <div class="overflow-hidden rounded-xl">
            <!-- Header Section -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-8 py-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <!-- Applicant Profile -->
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <div
                                class="w-20 h-20 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                                <span class="text-2xl font-bold text-white">
                                    {{ strtoupper(substr($applicant->first_name, 0, 1) . substr($applicant->last_name, 0, 1)) }}
                                </span>
                            </div>
                            <!-- Status Indicator -->
                            <div
                                class="absolute -bottom-1 -right-1 w-6 h-6 rounded-full border-2 border-white flex items-center justify-center
                                @if ($applicant->document_status === 'No Requirements') bg-gray-500
                                @elseif($applicant->document_status === 'Overdue') bg-red-500
                                @elseif($applicant->document_status === 'Completed') bg-green-500
                                @else bg-yellow-500 @endif">
                                <div class="w-2 h-2 bg-white rounded-full"></div>
                            </div>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-1">{{ $applicant->getFullNameAttribute() }}</h2>
                            <div class="flex items-center space-x-1">
                                <span class="text-sm text-gray-500">Applicant ID:</span>
                                <span
                                    class="text-sm font-mono font-semibold text-gray-700 bg-gray-100 px-1 py-1 rounded">{{ $applicant->applicant_id }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center space-x-3">

                        @if ($applicant->application_status === 'Officially Enrolled')
                            <button type="button" id="open-enroll-student-modal-btn" disabled
                                class="py-2 px-4 bg-gray-300 text-gray-400 rounded-xl font-bold transition duration-200 cursor-not-allowed">
                                Enroll applicant
                            </button>
                        @else
                            <button type="button" id="open-enroll-student-modal-btn"
                                class="flex flex-row justify-center items-center bg-[#199BCF] py-2.5 px-4 rounded-xl text-[16px] font-semibold gap-2 text-white hover:ring hover:ring-[#C8A165]/20 hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                                Enroll applicant
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Information Grid -->
            <div class="p-4 space-y-2">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- Academic Information -->
                    <div class="space-y-4 p-6 hover:shadow-xl hover:-translate-y-1 transition duration-200 rounded-xl">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">Academic Information
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Grade Level</p>
                                <p class="text-[16px] font-semibold text-gray-900">
                                    {{ $applicant->applicationForm->grade_level ?? 'Not specified' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Primary Track</p>
                                <p class="text-[16px] font-semibold text-gray-900">
                                    {{ $applicant->track->name ?? 'Not specified' }}</p>
                            </div>
                            @if ($applicant->program->code)
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Secondary Track</p>
                                    <p class="text-[16px] font-semibold text-gray-900">
                                        {{ $applicant->program->code ?? '-' }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="space-y-4 p-6 hover:shadow-xl hover:-translate-y-1 transition duration-200 rounded-xl">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">Contact Information
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Phone Number</p>
                                <p class="text-[16px] font-semibold text-gray-900">
                                    {{ $applicant->applicationForm->contact_number ?? 'Not provided' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Email</p>
                                <p class="text-[16px] font-semibold text-gray-900">
                                    {{ $applicant->user->email ?? 'Not provided' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Application Date</p>
                                <p class="text-[16px] font-semibold text-gray-900">
                                    {{ $applicant->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Document Submission Progress -->
                    <div class="space-y-4 p-6 hover:shadow-xl hover:-translate-y-1 transition duration-200 rounded-xl">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">Document Submission
                            Progress
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Total Required Documents</p>
                                <p class="text-[16px] font-semibold text-gray-900">
                                    {{ $assignedDocuments->count() ?? 0 }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Documents Submitted</p>
                                <p class="text-[16px] font-semibold text-gray-900">
                                    {{ $assignedDocuments->where('status', '!=', 'Pending')->count() ?? 0 }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Documents Verified</p>
                                <p class="text-[16px] font-semibold text-gray-900">
                                    {{ $assignedDocuments->where('status', 'Verified')->count() ?? 0 }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Document Status Overview -->
                    <div class="space-y-4 p-6 hover:shadow-xl hover:-translate-y-1 transition duration-200 rounded-xl">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">Document Status
                            Overview
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Overall Status</p>
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @if ($applicant->getDocumentStatusAttribute() === 'Complete') bg-green-100 text-green-800
                                    @elseif($applicant->getDocumentStatusAttribute() === 'Overdue') bg-red-100 text-red-800
                                    @elseif($applicant->getDocumentStatusAttribute() === 'No Requirements') bg-gray-100 text-gray-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ $applicant->getDocumentStatusAttribute() }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Pending Review</p>
                                <p class="text-[16px] font-semibold text-gray-900">
                                    {{ $assignedDocuments->where('status', 'Submitted')->count() ?? 0 }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Rejected Documents</p>
                                <p class="text-[16px] font-semibold text-gray-900">
                                    {{ $assignedDocuments->where('status', 'Rejected')->count() ?? 0 }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>



            </div>
        </div>
    </div>
@endsection

@section('docs_submission_progress')
    <div class="flex flex-col text-[15px] shadow-lg">

        <div class="bg-white flex flex-col rounded-2xl border border-gray-200 shadow-lg p-8 gap-4">
            <div>
                <div class="flex flex-row justify-start items-center text-start pb-2">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                            <i class="fi fi-rs-document text-blue-600 text-lg"></i>
                        </div>
                        <div>
                            <h2 class="text-[20px] font-bold text-gray-900">Documents Submission Progress</h2>
                            <p class="text-[14px] text-gray-500 mt-1">Review and manage submitted documents</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-full overflow-hidden border border-gray-100">
                <table id="docs-table" class="w-full table-fixed">
                    <thead class="text-[15px]">
                        <tr class="bg-gradient-to-r from-blue-50 to-indigo-50">
                            <th
                                class="w-1/8 text-start bg-transparent border-b border-gray-200 px-6 py-2 cursor-pointer font-semibold text-gray-700">
                                <span class="mr-2">Document Type</span>
                                <i class="fi fi-ss-sort text-[13px] opacity-60"></i>
                            </th>
                            <th
                                class="w-1/8 text-center bg-transparent border-b border-gray-200 px-6 py-2 font-semibold text-gray-700">
                                <span class="mr-2">Status</span>
                                <i class="fi fi-ss-sort text-[13px] cursor-pointer opacity-60"></i>
                            </th>
                            <th
                                class="w-1/8 text-center bg-transparent border-b border-gray-200 px-6 py-2 font-semibold text-gray-700">
                                <span class="mr-2">Submit Before</span>
                                <i class="fi fi-ss-sort text-[13px] cursor-pointer opacity-60"></i>
                            </th>
                            <th
                                class="w-1/8 text-center bg-transparent border-b border-gray-200 px-6 py-2 font-semibold text-gray-700">
                                <span class="mr-2">Date Submitted</span>
                                <i class="fi fi-ss-sort text-[13px] cursor-pointer opacity-60"></i>
                            </th>
                            <th
                                class="w-1/8 text-center bg-transparent border-b border-gray-200 px-6 py-2 font-semibold text-gray-700">
                                Actions
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @if ($assignedDocuments)
                            @foreach ($assignedDocuments as $index => $doc)
                                <tr class="border-t border-gray-100 hover:bg-gray-50 transition-colors duration-150">
                                    <td
                                        class="w-1/8 text-start font-semibold py-2 text-[15px] text-gray-800 px-6 truncate">
                                        <div class="flex items-center gap-2">
                                            <i class="fi fi-rs-file text-gray-400 text-sm"></i>
                                            {{ $doc->documents->type }}
                                        </div>
                                    </td>
                                    <td class="w-1/8 text-center font-medium py-2 text-[15px] px-6 truncate">
                                        @if ($doc->status == 'Pending')
                                            <span
                                                class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-gray-100 text-gray-600">
                                                <i class="fi fi-rs-clock mr-1.5 text-xs"></i>
                                                Not Submitted
                                            </span>
                                        @elseif ($doc->status == 'Submitted')
                                            <span
                                                class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-700">
                                                <i class="fi fi-rs-hourglass mr-1.5 text-xs"></i>
                                                Pending Review
                                            </span>
                                        @elseif ($doc->status == 'Verified')
                                            <span
                                                class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-green-100 text-green-700">
                                                <i class="fi fi-rs-check mr-1.5 text-xs"></i>
                                                Verified
                                            </span>
                                        @elseif ($doc->status == 'Rejected')
                                            <span
                                                class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-red-100 text-red-700">
                                                <i class="fi fi-rs-cross mr-1.5 text-xs"></i>
                                                Rejected
                                            </span>
                                        @endif
                                    </td>

                                    <td class="w-1/8 text-center font-medium py-2 text-[15px] text-gray-600 px-6 truncate">
                                        @if ($doc->submit_before)
                                            <div class="flex flex-col items-center">
                                                <span class="text-sm font-semibold">
                                                    {{ \Carbon\Carbon::parse($doc->submit_before)->format('M d, Y') }}
                                                </span>
                                            </div>
                                        @else
                                            <span class="text-gray-400 text-sm">-</span>
                                        @endif
                                    </td>

                                    <td class="w-1/8 text-center font-medium py-2 text-[15px] text-gray-600 px-6 truncate">
                                        @forelse ($doc->submissions as $submission)
                                            <div class="flex flex-col items-center">
                                                <span class="text-sm font-semibold">
                                                    {{ $submission->submitted_at->timezone('Asia/Manila')->format('M d, Y') }}
                                                </span>
                                                <span class="text-xs text-gray-500">
                                                    {{ $submission->submitted_at->timezone('Asia/Manila')->format('g:i A') }}
                                                </span>
                                            </div>
                                        @empty
                                            <span class="text-gray-400 text-sm">-</span>
                                        @endforelse
                                    </td>

                                    <td class="w-1/8 text-center font-medium py-2 px-6">
                                        <div class="flex flex-row justify-center items-center gap-2">
                                            @forelse ($doc->submissions as $submission)
                                                <button id="open-view-modal-btn-{{ $doc->id }}"
                                                    data-doc-id="{{ $doc->id }}"
                                                    data-file-url="{{ asset('storage/' . $submission->file_path) }}"
                                                    data-file-type="{{ pathinfo($submission->file_path, PATHINFO_EXTENSION) }}"
                                                    class="view-document-btn inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-xl bg-blue-50 text-blue-700 hover:bg-blue-100  hover:ring-2 hover:ring-blue-200 transition-all duration-200"
                                                    title="View document">
                                                    <i class="fi fi-rs-eye text-sm flex justify-center items-center"></i>
                                                    View
                                                </button>

                                                @if ($doc->status !== 'Verified' && $doc->status !== 'Rejected')
                                                    <button type="button" id="open-verify-modal-btn-{{ $doc->id }}"
                                                        data-document-id="{{ $doc->id }}"
                                                        class="verify-document-btn inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-xl bg-green-50 text-green-700 hover:bg-green-100 hover:ring-2 hover:ring-green-200 transition-all duration-200"
                                                        title="Verify document">
                                                        <i class="fi fi-rs-check text-sm"></i>
                                                        Verify
                                                    </button>
                                                    <button type="button" id="open-reject-modal-btn-{{ $doc->id }}"
                                                        data-document-id="{{ $doc->id }}"
                                                        class="reject-document-btn inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-xl bg-red-50 text-red-700 hover:bg-red-100 hover:ring-2 hover:ring-red-200 transition-all duration-200"
                                                        title="Reject document">
                                                        <i
                                                            class="fi fi-rs-cross text-[9px] text-sm flex justify-center items-center"></i>
                                                        Reject
                                                    </button>
                                                @endif
                                            @empty
                                                -
                                            @endforelse
                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="text-center py-12 text-gray-500">
                                    <div class="flex flex-col items-center gap-3">
                                        <i class="fi fi-rs-document text-4xl text-gray-300"></i>
                                        <p class="text-lg font-medium">No documents assigned</p>
                                        <p class="text-sm">This applicant has no document requirements.</p>
                                    </div>
                                </td>
                            </tr>
                        @endif

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="module">
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

        initModal('enroll-student-modal', 'open-enroll-student-modal-btn', 'enroll-student-close-btn',
            'putanginamo_cancel-btn', 'modal-container-2');

        let table;
        let pendingApplications = document.querySelector('#pending-application');

        document.addEventListener("DOMContentLoaded", function() {

            // Handle flash messages from server
            @if (session('status'))
                showAlert('success', '{{ session('status') }}');
            @endif

            @if ($errors->any())
                showAlert('error', '{{ $errors->first() }}');
            @endif

            // Handle checkbox and warning messages
            const autoAssignCheckbox = document.getElementById('auto-assign');
            const warningMessages = document.querySelectorAll('[id^="warning-message"]');

            // Uncheck checkbox on page load/refresh
            if (autoAssignCheckbox) {
                autoAssignCheckbox.checked = false;
            }

            // Hide/show warning messages based on checkbox state
            if (autoAssignCheckbox && warningMessages.length > 0) {
                autoAssignCheckbox.addEventListener('change', function() {
                    warningMessages.forEach(warningMessage => {
                        if (this.checked) {
                            // Show warning when checkbox is checked (user wants to auto-assign)
                            warningMessage.classList.remove('hidden');
                            warningMessage.style.display = 'block';
                        } else {
                            // Hide warning when checkbox is unchecked (user doesn't want to auto-assign)
                            warningMessage.classList.add('hidden');
                            warningMessage.style.display = 'none';
                        }
                    });
                });
            }

            table = new DataTable('#docs-table', {
                paging: true,
                pageLength: 20,
                searching: false,
                autoWidth: false,
                order: [
                    [6, 'desc']
                ],
                columnDefs: [{
                        width: '15%',
                        targets: 0
                    },
                    {
                        width: '12%',
                        targets: 1
                    },
                    {
                        width: '12%',
                        targets: 2
                    },
                    {
                        width: '12%',
                        targets: 3
                    },
                    {
                        width: '20%',
                        targets: 4
                    }
                ],
                layout: {
                    topStart: null,
                    bottomStart: 'info',
                    bottomEnd: 'paging',
                }
            });

            table.on('draw', function() {
                let newRow = document.querySelector('#myTable tbody tr:first-child');

                // Select all td elements within the new row
                let cells = newRow.querySelectorAll('td');

                cells.forEach(function(cell) {
                    cell.classList.add(
                        'px-4', // Horizontal padding
                        'py-2', // Vertical padding
                        'text-start', // Align text to the start (left)
                        'font-regular',
                        'text-[14px]',
                        'opacity-80',
                        'truncate'
                    );
                });

            });

            let requiredDocs = @json($assignedDocuments);
            let submittedDocs = @json($submittedDocuments);
            let submittedDocsArr = Object.values(requiredDocs)

            const approvedCount = submittedDocsArr.filter(doc => doc.status === "Verified").length;

            let enrollMsg = document.querySelector('#enroll-msg')

            document.querySelector('#open-enroll-student-modal-btn').addEventListener('click', () => {

                if (approvedCount < requiredDocs.length) {
                    enrollMsg.innerHTML = `The applicant has <strong>not</strong> yet <strong>completed</strong> the required document submission.
                    Are you sure you want to proceed with officially enrolling this applicant?`
                } else if (submittedDocs.length === requiredDocs.length && approvedCount <
                    requiredDocs.length) {
                    enrollMsg.innerHTML = `The applicant's submitted documents have <strong>not</strong> been <strong>fully verified</strong>.
                    Are you sure you want to proceed with officially enrolling this applicant?`
                } else {
                    enrollMsg.innerHTML =
                        `Are you sure you want to promote this applicant? This action is irreversible and cannot be undone.`
                }

            })



            // Verify Document
            document.querySelectorAll('.verify-document-btn').forEach((button, index) => {

                let id = button.getAttribute('data-document-id');
                initModal('verify-doc-modal', `open-verify-modal-btn-${id}`, 'verify-doc-close-btn',
                    'cancel-btn', 'modal-container-1');

                button.addEventListener('click', () => {
                    // Clear any existing hidden inputs first
                    let form = document.getElementById('verify-doc-form');
                    let existingInputs = form.querySelectorAll('input[name="document_id"]');
                    existingInputs.forEach(input => input.remove());

                    let documentId = button.getAttribute('data-document-id');

                    let input = document.createElement('input');
                    input.type = 'hidden';
                    input.value = documentId;
                    input.name = "document_id";
                    form.appendChild(input);

                })

            })

            // Reject Document
            document.querySelectorAll('.reject-document-btn').forEach((button, index) => {

                let id = button.getAttribute('data-document-id');
                initModal('reject-doc-modal', `open-reject-modal-btn-${id}`, 'reject-doc-close-btn',
                    'reject-cancel-btn', 'modal-container-4');

                button.addEventListener('click', () => {
                    // Clear any existing hidden inputs first
                    let form = document.getElementById('reject-doc-form');
                    let existingInputs = form.querySelectorAll('input[name="document_id"]');
                    existingInputs.forEach(input => input.remove());

                    let documentId = button.getAttribute('data-document-id');

                    let input = document.createElement('input');
                    input.type = 'hidden';
                    input.value = documentId;
                    input.name = "document_id";
                    form.appendChild(input);

                })

            })

            // Document viewer functionality
            let currentPdf = null;
            let currentPage = 1;
            let totalPages = 1;
            let currentFileUrl = '';
            let currentFileType = '';

            // Initialize document viewer modal
            document.querySelectorAll('.view-document-btn').forEach((button, index) => {

                let docId = button.getAttribute('data-doc-id');

                initModal('view-doc-modal', `open-view-modal-btn-${docId}`, 'view-doc-close-btn',
                    'close-viewer-btn', 'modal-container-3');

                button.addEventListener('click', async () => {
                    const fileUrl = button.getAttribute('data-file-url');
                    const fileType = button.getAttribute('data-file-type').toLowerCase();

                    currentFileUrl = fileUrl;
                    currentFileType = fileType;

                    await loadDocument(fileUrl, fileType);
                });
            });

            // Load document function
            async function loadDocument(fileUrl, fileType) {
                const pdfViewerContainer = document.getElementById('pdf-viewer-container');
                const imageViewerContainer = document.getElementById('image-viewer-container');
                const loadingIndicator = document.getElementById('loading-indicator');
                const errorMessage = document.getElementById('error-message');
                const externalViewBtn = document.getElementById('external-view-btn');

                // Reset UI - hide all containers
                pdfViewerContainer.classList.add('hidden');
                imageViewerContainer.classList.add('hidden');
                errorMessage.classList.add('hidden');
                loadingIndicator.classList.remove('hidden');

                // Set external view button
                externalViewBtn.onclick = () => window.open(fileUrl, '_blank');

                try {
                    if (fileType === 'pdf') {
                        await loadPDF(fileUrl);
                    } else if (['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'].includes(fileType)) {
                        await loadImage(fileUrl);
                    } else {
                        throw new Error('Unsupported file type');
                    }
                } catch (error) {
                    loadingIndicator.classList.add('hidden');
                    errorMessage.classList.remove('hidden');
                }
            }

            // Load PDF function
            async function loadPDF(fileUrl) {
                const pdfViewerContainer = document.getElementById('pdf-viewer-container');
                const canvas = document.getElementById('pdfViewerCanvas');
                const loadingIndicator = document.getElementById('loading-indicator');
                const pageInfo = document.getElementById('page-info');
                const prevBtn = document.getElementById('prev-page-btn');
                const nextBtn = document.getElementById('next-page-btn');

                try {
                    // Lazy load PDF.js library
                    await window.loadPDFLibrary();
                    
                    // Check if PDF.js is available
                    if (typeof window.pdfjsLib === 'undefined') {
                        throw new Error('PDF.js library not loaded');
                    }

                    // Set worker source for local PDF.js
                    window.pdfjsLib.GlobalWorkerOptions.workerSrc = '/js/pdf.worker.min.js';

                    const loadingTask = window.pdfjsLib.getDocument(fileUrl);
                    loadingTask.promise.then(function(pdf) {
                        currentPdf = pdf;
                        totalPages = pdf.numPages;
                        currentPage = 1;

                        loadingIndicator.classList.add('hidden');
                        pdfViewerContainer.classList.remove('hidden');

                        updatePageInfo();
                        renderPage(1);

                        // Navigation event listeners
                        prevBtn.onclick = () => {
                            if (currentPage > 1) {
                                currentPage--;
                                renderPage(currentPage);
                                updatePageInfo();
                            }
                        };

                        nextBtn.onclick = () => {
                            if (currentPage < totalPages) {
                                currentPage++;
                                renderPage(currentPage);
                                updatePageInfo();
                            }
                        };
                    }).catch(function(error) {
                        loadingIndicator.classList.add('hidden');
                        document.getElementById('error-message').classList.remove('hidden');
                    });
                } catch (error) {
                    loadingIndicator.classList.add('hidden');
                    document.getElementById('error-message').classList.remove('hidden');
                }
            }

            // Render PDF page
            async function renderPage(pageNum) {
                if (!currentPdf) return;

                const canvas = document.getElementById('pdfViewerCanvas');
                const context = canvas.getContext('2d');
                const container = canvas.parentElement;

                try {
                    const page = await currentPdf.getPage(pageNum);

                    // Calculate scale to fit the container while maintaining aspect ratio
                    const containerWidth = container.clientWidth - 32; // Account for padding
                    const containerHeight = container.clientHeight - 32;

                    const viewport = page.getViewport({
                        scale: 1.0
                    });
                    const scaleX = containerWidth / viewport.width;
                    const scaleY = containerHeight / viewport.height;
                    const scale = Math.min(scaleX, scaleY, 2.0); // Max scale of 2.0 for readability

                    const scaledViewport = page.getViewport({
                        scale: scale
                    });

                    // Set canvas size to match the scaled viewport
                    canvas.width = scaledViewport.width;
                    canvas.height = scaledViewport.height;

                    const renderContext = {
                        canvasContext: context,
                        viewport: scaledViewport
                    };

                    await page.render(renderContext).promise;
                } catch (error) {
                    console.error('Error rendering page:', error);
                }
            }

            // Update page info
            function updatePageInfo() {
                const pageInfo = document.getElementById('page-info');
                const prevBtn = document.getElementById('prev-page-btn');
                const nextBtn = document.getElementById('next-page-btn');

                pageInfo.textContent = `Page ${currentPage} of ${totalPages}`;

                prevBtn.disabled = currentPage <= 1;
                nextBtn.disabled = currentPage >= totalPages;

                if (currentPage <= 1) {
                    prevBtn.classList.add('opacity-50', 'cursor-not-allowed');
                } else {
                    prevBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                }

                if (currentPage >= totalPages) {
                    nextBtn.classList.add('opacity-50', 'cursor-not-allowed');
                } else {
                    nextBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            }

            // Load image function
            async function loadImage(fileUrl) {
                const imageViewerContainer = document.getElementById('image-viewer-container');
                const imageViewer = document.getElementById('imageViewer');
                const loadingIndicator = document.getElementById('loading-indicator');

                return new Promise((resolve, reject) => {
                    imageViewer.onload = () => {
                        loadingIndicator.classList.add('hidden');
                        imageViewerContainer.classList.remove('hidden');
                        resolve();
                    };

                    imageViewer.onerror = () => {
                        reject(new Error('Failed to load image'));
                    };

                    imageViewer.src = fileUrl;
                });
            }

            //initModal('verify-doc-modal', 'open-verify-modal-btn', 'verify-doc-close-btn', 'cancel-btn');




            initModal('record-interview-modal', 'record-interview-btn', 'record-interview-close-btn', 'cancel-btn');
            initModal('sched-interview-modal', 'record-btn', 'sched-interview-close-btn', 'cancel-btn');

            // Handle enrollment form submission
            document.getElementById('enroll-student-submit-btn')?.addEventListener('click', async function() {
                const form = document.getElementById('enroll-student-form');
                const formData = new FormData(form);
                const autoAssign = document.getElementById('auto-assign')?.checked;

                // Add auto-assign checkbox value to form data
                formData.append('auto_assign', autoAssign ? '1' : '0');

                // Show appropriate loader message based on checkbox state
                const loaderMessage = autoAssign ? 'Enrolling student with auto-assigned fees...' :
                    'Enrolling student...';

                try {
                    // Show loader
                    showLoader(loaderMessage);

                    // Submit via AJAX
                    const response = await fetch(`/students/{{ $applicant->id }}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    const data = await response.json();

                    // Hide loader
                    hideLoader();

                    if (data.success) {
                        // Show success message
                        showAlert('success', data.message || 'Student enrolled successfully!');

                        // Close modal
                        const modal = document.getElementById('enroll-student-modal');
                        const modalContainer = document.getElementById('modal-container-2');
                        if (modal && modalContainer) {
                            modal.classList.add('hidden');
                            modalContainer.classList.add('hidden');
                        }

                        // Reload page to show updated data
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);

                    } else {
                        // Show error message
                        showAlert('error', data.error || 'Failed to enroll student. Please try again.');
                    }

                } catch (error) {
                    // Hide loader
                    hideLoader();

                    showAlert('error',
                        'An error occurred while enrolling the student. Please try again.');
                }
            });

        });
    </script>
@endpush
