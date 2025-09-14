@extends('layouts.admin')

@section('breadcrumbs')
    <nav aria-label="Breadcrumb" class="mb-4 mt-2">
        <ol class="flex items-center gap-1 text-sm text-gray-700">
            <li>
                <a href="#" class="block transition-colors hover:text-gray-900"> Applications </a>
            </li>

            <li class="rtl:rotate-180">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
            </li>

            <li>
                <a href="/pending-documents" class="block transition-colors hover:text-gray-900"> Pending Documents
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
                <a href="#" class="block transition-colors hover:text-gray-900"> Documents Details </a>
            </li>
        </ol>
    </nav>
@endsection

@section('modal')
    <x-modal modal_id="verify-doc-modal" modal_name="Verification confirmation" close_btn_id="verify-doc-close-btn"
        modal_container_id='modal-container-1'>
        <x-slot name="modal_icon">
            <i class='fi fi-ss-exclamation flex justify-center items-center text-yellow-500'></i>
        </x-slot>

        <form action="/submit-document/{{ $applicant->id }}" method="POST" id="verify-doc-form">
            @csrf
            @method('PATCH')
            {{-- <input type="hidden" name="doc_id" id="hidden-doc-id"> --}}
            {{-- This button will serve as an opener of modal when clicked --}}

        </form>

        <p class="py-8 px-6 space-y-2 font-regular text-[14px]">Are you sure you want
            to verify this document? This action
            will mark the document as <strong>valid</strong> and <strong>approved</strong>.</p>

        <x-slot name="modal_buttons">
            <button id="cancel-btn"
                class="border border-[#1e1e1e]/15 text-[14px] px-2 py-1 rounded-md text-[#0f111c]/80 font-bold">
                Cancel
            </button>
            {{-- This button will acts as the submit button --}}
            <button type="submit" form="verify-doc-form" name="action" value="verify"
                class="bg-[#199BCF] text-[14px] px-2 py-1 rounded-md text-[#f8f8f8] font-bold">
                Confirm
            </button>
        </x-slot>

    </x-modal>
    {{-- Reject document modal --}}
    <x-modal modal_id="reject-doc-modal" modal_name="Rejection confirmation" close_btn_id="reject-doc-close-btn"
        modal_container_id='modal-container-4'>
        <x-slot name="modal_icon">
            <i class='fi fi-ss-exclamation flex justify-center items-center text-red-500'></i>
        </x-slot>

        <form action="/submit-document/{{ $applicant->id }}" method="POST" id="reject-doc-form">
            @csrf
            @method('PATCH')
            {{-- <input type="hidden" name="doc_id" id="hidden-doc-id"> --}}
            {{-- This button will serve as an opener of modal when clicked --}}

        </form>

        <p class="py-8 px-6 space-y-2 font-regular text-[14px]">Are you sure you want
            to reject this document? This action
            will mark the document as <strong>invalid</strong> and <strong>rejected</strong>.</p>

        <x-slot name="modal_buttons">
            <button id="reject-cancel-btn"
                class="border border-[#1e1e1e]/15 text-[14px] px-2 py-1 rounded-md text-[#0f111c]/80 font-bold">
                Cancel
            </button>
            {{-- This button will acts as the submit button --}}
            <button type="submit" form="reject-doc-form" name="action" value="reject"
                class="bg-[#EA4335] text-[14px] px-2 py-1 rounded-md text-[#f8f8f8] font-bold">
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
                    class="flex items-center gap-2 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors disabled:bg-gray-300 disabled:cursor-not-allowed">
                    <i class="fi fi-rs-angle-left"></i>
                    Previous
                </button>
                <span id="page-info"
                    class="px-4 py-2 bg-white border border-gray-300 rounded-lg font-medium text-gray-700">Page 1 of
                    1</span>
                <button id="next-page-btn"
                    class="flex items-center gap-2 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors disabled:bg-gray-300 disabled:cursor-not-allowed">
                    Next
                    <i class="fi fi-rs-angle-right"></i>
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
                class="flex items-center gap-2 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                <i class="fi fi-rs-external-link"></i>
                Open in New Tab
            </button>
            <button id="close-viewer-btn"
                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                Close
            </button>
        </x-slot>
    </x-modal>
    {{-- Enroll student modal --}}
    <x-modal modal_id="enroll-student-modal" modal_name="Enrollment confirmation" close_btn_id="enroll-student-close-btn"
        modal_container_id='modal-container-2'>
        <x-slot name="modal_icon">
            <i class='fi fi-ss-exclamation flex justify-center items-center text-yellow-500'></i>
        </x-slot>

        <form action="/applicants/{{ $applicant->id }}" method="POST" id="enroll-student-form">
            @csrf
            @method('PATCH')

            <input type="hidden" name="status" value="Officially Enrolled">

        </form>

        <p id="enroll-msg" class="py-8 px-6 space-y-2 font-regular text-[14px]"></p>

        <x-slot name="modal_buttons">
            <button id="putanginamo_cancel-btn"
                class="border border-[#1e1e1e]/15 text-[14px] px-2 py-1 rounded-md text-[#0f111c]/80 font-bold">
                Cancel
            </button>
            {{-- This button will acts as the submit button --}}
            <button type="submit" form="enroll-student-form" name="action" value="enroll-student"
                class="bg-[#199BCF] text-[14px] px-2 py-1 rounded-md text-[#f8f8f8] font-bold">
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
    <div class="flex flex-col p-6 text-[14px] gap-4 bg-[#f8f8f8] rounded-xl border shadow-sm border-[#1e1e1e]/10">
        <div class="flex flex-row justify-between items-center">
            <div class="flex flex-row gap-2 justify-center items-center">
                <div class="rounded-full overflow-hidden bg-gray-200 ">
                    <img src="{{ asset('images/business-man.png') }}" alt="user-icon" class="size-16 user-select-none">
                </div>
                <div>
                    <p class="font-bold text-[18px]">{{ $applicant->getFullNameAttribute() }}</p>
                    <div class="flex flex-row items-center justify-start gap-1">
                        <p class="text-[16px] opacity-70 font-medium">Applicant ID: </p>
                        <span class="text-[16px] font-black">{{ $applicant->applicant_id }}</span>
                    </div>

                </div>
            </div>
            <div>

                @if ($applicant->application_status === 'Officially Enrolled')
                    <button type="button" id="open-enroll-student-modal-btn" disabled
                        class="py-2 px-4 bg-gray-300 text-gray-400 rounded-xl font-bold transition duration-200 cursor-not-allowed">
                        Enroll applicant
                    </button>
                @else
                    <button type="button" id="open-enroll-student-modal-btn"
                        class="py-2 px-4 bg-blue-500 text-white rounded-xl font-bold hover:ring hover:ring-blue-200 hover:bg-blue-400 transition duration-200">
                        Enroll applicant
                    </button>
                @endif

            </div>

        </div>
        <x-divider color="#1e1e1e" opacity="0.10"></x-divider>
        <div class="flex flex-row">
            <div class="flex flex-col flex-1 space-y-4">
                <span>
                    <p class="opacity-80">Grade</p>
                    <p class="font-bold">Grade 11</p>
                </span>
                <span>
                    <p class="opacity-80">Track</p>
                    <p class="font-bold">HUMSS</p>
                </span>

            </div>
            <div class="flex flex-col flex-1 space-y-4">
                <span>
                    <p class="opacity-80">Contact</p>
                    <p class="font-bold">091234789</p>
                </span>
                <span>
                    <p class="opacity-80">Interview Date</p>
                    <p class="font-bold">June 21, 2025</p>
                </span>

                </span>
            </div>
            <div class="flex flex-col flex-1 space-y-4">
                <span>
                    <p class="opacity-80">Interview Time</p>
                    <p class="font-bold">10:30 AM</p>
                </span>
                <span>
                    <p class="opacity-80">Location</p>
                    <p class="font-bold">First floor, Room 301</p>
            </div>
            <div class="flex flex-col flex-1 space-y-4">
                <span>
                    <p class="opacity-80">Interviewer</p>
                    <p class="font-bold">Peter Dela Cruz</p>
                </span>
                <span>
                    <p class="opacity-80">Status</p>
                    <p class="font-bold">{{ $applicant->application_status }}</p>
                </span>
            </div>
        </div>

    </div>
@endsection

@section('docs_submission_progress')
    <div class="flex flex-col text-[14px] shadow-sm">

        <div class="bg-[#f8f8f8] flex flex-col rounded-xl border shadow-sm border-[#1e1e1e]/10 p-6 gap-2">
            <div>
                <div class="flex flex-row justify-start items-center text-start pb-2">
                    <p class="text-[18px] font-semibold">Documents Submission Progress</p>
                </div>
            </div>

            <div class="w-full ">
                <table id="docs-table" class="w-full table-fixed">
                    <thead class="text-[14px]">
                        <tr>
                            <th
                                class="w-1/7 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2 cursor-pointer ">
                                <span class="mr-2">Document</span>
                                <i class="fi fi-ss-sort text-[12px] opacity-60"></i>
                            </th>
                            <th class="w-1/7 text-center bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                <span class="mr-2">Status</span>
                                <i class="fi fi-ss-sort text-[12px] cursor-pointer opacity-60"></i>
                            </th>
                            <th class="w-1/7 text-center bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                <span class="mr-2">Date Submitted</span>
                                <i class="fi fi-ss-sort text-[12px] cursor-pointer opacity-60"></i>
                            </th>

                            <th class="w-1/7 text-center bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                Actions
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @if ($assignedDocuments)
                            @foreach ($assignedDocuments as $index => $doc)
                                <tr class="border-t-[1px] border-[#1e1e1e]/15 w-full rounded-md">
                                    <td
                                        class="w-1/8 text-start font-medium py-[8px] text-[14px] opacity-80 px-4 py-2 truncate">
                                        {{ $doc->documents->type }}
                                    </td>
                                    <td class="w-1/8 text-center font-medium py-[8px] text-[14px] px-4 py-2 truncate">
                                        @if ($doc->status == 'not-submitted')
                                            <span class="bg-gray-200 text-gray-500 px-2 py-1 rounded-md font-semibold">
                                                Not Submitted
                                            </span>
                                        @elseif ($doc->status == 'submitted')
                                            <span class="bg-yellow-100 text-yellow-500 px-2 py-1 rounded-md font-semibold">
                                                Submitted-Pending
                                            </span>
                                        @elseif ($doc->status == 'verified')
                                            <span class="bg-[#E6F4EA] text-[#34A853] px-2 py-1 rounded-md font-semibold">
                                                Verified
                                            </span>
                                        @elseif ($doc->status == 'rejected')
                                            <span class="bg-[#FCE8E6] text-[#EA4335] px-2 py-1 rounded-md font-semibold">
                                                Rejected
                                            </span>
                                        @endif

                                    </td>

                                    <td
                                        class="w-1/8 text-center font-medium py-[8px] text-[14px] opacity-80 px-4 py-2 truncate">
                                        @forelse ($doc->submissions as $submission)
                                            {{ $submission->submitted_at->timezone('Asia/Manila')->format('M. d - g:i A') }}<br>
                                        @empty
                                            -
                                        @endforelse
                                    </td>

                                    <td class="w-1/8 text-center font-medium text-[14px] opacity-100 px-4 py-1 truncate">

                                        <div class="flex flex-row justify-center items-center gap-2">
                                            @forelse ($doc->submissions as $submission)
                                                {{-- <x-nav-link href="{{ asset('storage/' . $submission->file_path) }}"
                                                target="_blank"
                                                class="flex flex-row gap-2 justify-center items-center text-[14px] py-2 px-3 rounded-md bg-[#1A73E8] text-white font-medium transition-colors duration-200"
                                                title="View document">
                                                <i
                                                    class="fi fi-rs-eye text-[16px] flex justify-center items-center"></i>
                                                View
                                            </x-nav-link> --}}
                                                <button id="open-view-modal-btn-{{ $index }}"
                                                    data-file-url="{{ asset('storage/' . $submission->file_path) }}"
                                                    data-file-type="{{ pathinfo($submission->file_path, PATHINFO_EXTENSION) }}"
                                                    class="view-document-btn flex flex-row gap-2 justify-center items-center text-[14px] py-2 px-3 rounded-xl bg-[#1A73E8]/10 hover:ring hover:ring-[#1A73E8]/20 hover:bg-[#1A73E8] hover:text-white text-[#1A73E8] font-bold transition duration-200"
                                                    title="View document">
                                                    <i
                                                        class="fi fi-rs-eye text-[16px] flex justify-center items-center"></i>
                                                    View
                                                </button>

                                                @if ($doc->status !== 'verified')
                                                    <button type="button" id="open-verify-modal-btn-{{ $doc->id }}"
                                                        data-document-id="{{ $doc->id }}"
                                                        class="verify-document-btn flex flex-row gap-2 justify-center items-center text-[14px] text-[#34A853] py-2 px-3 rounded-xl bg-[#34A853]/10 hover:ring hover:ring-[#34A853]/20 hover:bg-[#34A853] hover:text-white font-bold transition duration-200"
                                                        title="Accept document">
                                                        <i
                                                            class="fi fi-rs-check text-[16px] flex justify-center items-center"></i>
                                                        Verify
                                                    </button>
                                                    <button type="button" id="open-reject-modal-btn-{{ $doc->id }}"
                                                        data-document-id="{{ $doc->id }}"
                                                        class="reject-document-btn flex flex-row gap-1 justify-center items-center text-[14px] text-[#EA4335] py-2 px-4 rounded-xl bg-[#EA4335]/10 hover:bg-[#EA4335] hover:text-white hover:ring hover:ring-[#EA4335]/20 font-bold transition duration-200"
                                                        title="Reject document">
                                                        <i
                                                            class="fi fi-rs-cross-small text-[16px] flex justify-center items-center"></i>
                                                        Reject
                                                    </button>
                                                @endif
                                                {{-- <x-nav-link href="{{ asset('storage/' . $submission->file_path) }}"
                                                target="_blank"
                                                class="flex flex-row gap-2 justify-center items-center text-[14px] py-2 px-3 rounded-md bg-[#1A73E8] text-white font-medium transition-colors duration-200"
                                                title="View document">
                                                <i
                                                    class="fi fi-rs-eye text-[16px] flex justify-center items-center"></i>

                                            </x-nav-link> --}}
                                                {{-- Are you sure you want to reject this document? This action will mark the document as invalid and notify the relevant parties. --}}



                                            @empty
                                                <button
                                                    class="flex justify-center items-center gap-2 bg-orange-200 text-orange-500 font-bold py-2 px-20 rounded-xl hover:bg-orange-400 hover:ring hover:ring-orange-200 hover:text-white transition duration-150">
                                                    <i class="fi fi-rs-bell flex justify-center items-center"></i>
                                                    Send reminder
                                                </button>
                                            @endforelse


                                        </div>

                                    </td>

                                </tr>
                            @endforeach
                        @else
                            @foreach ($assignedDocuments as $index => $doc)
                            @endforeach
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

        initModal('enroll-student-modal', 'open-enroll-student-modal-btn', 'enroll-student-close-btn',
            'putanginamo_cancel-btn', 'modal-container-2');

        let table;
        let pendingApplications = document.querySelector('#pending-application');

        document.addEventListener("DOMContentLoaded", function() {

            table = new DataTable('#docs-table', {
                paging: true,
                pageLength: 20,
                searching: false,
                autoWidth: false,
                order: [
                    [6, 'desc']
                ],
                columnDefs: [{
                    width: '16.66%',
                    targets: '_all'
                }],
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

            const approvedCount = submittedDocsArr.filter(doc => doc.status === "verified").length;

            let enrollMsg = document.querySelector('#enroll-msg')

            document.querySelector('#open-enroll-student-modal-btn').addEventListener('click', () => {

                if (approvedCount.length < requiredDocs.length) {
                    enrollMsg.innerHTML = `The applicant has <strong>not</strong> yet <strong>completed</strong> the required document submission.
                    Are you sure you want to proceed with officially enrolling this applicant?`
                } else if (submittedDocs.length === requiredDocs.length && approvedCount.length <
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

                    console.log('Verify modal opened for document ID:', documentId);
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

                    console.log('Reject modal opened for document ID:', documentId);
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
                initModal('view-doc-modal', `open-view-modal-btn-${index}`, 'view-doc-close-btn',
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
                    console.error('Error loading document:', error);
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
                    // Check if PDF.js is available (it should be from app.js)
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
                        console.error('Error loading PDF:', error);
                        loadingIndicator.classList.add('hidden');
                        document.getElementById('error-message').classList.remove('hidden');
                    });
                } catch (error) {
                    console.error('Error initializing PDF viewer:', error);
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



        });
    </script>
@endpush
