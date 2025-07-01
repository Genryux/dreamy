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
    <x-modal modal_id="verify-doc-modal" modal_name="Verification confirmation" close_btn_id="verify-doc-close-btn">

        <form action="" method="POST" id="verify-doc-form">
            @csrf
            @method('PATCH')
            {{-- <input type="hidden" name="doc_id" id="hidden-doc-id"> --}}
            {{-- This button will serve as an opener of modal when clicked --}}

        </form>

        <p class="py-8 px-6 space-y-2 font-regular text-[14px]">Are you sure you want to verify this document? This action
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
@endsection

@section('header')
    <div class="flex flex-row justify-start items-center space-x-2 text-start px-[14px] py-2">
        <h1 class="text-[20px] font-semibold text-gray-900/60">Document submission details: </h1>
        <span class="text-[20px] font-bold">aasdas</span>
    </div>
@endsection

@section('content')
    <div class="flex flex-row pl-[14px] py-[16px] text-[14px]">
        <div class="flex flex-col flex-1 space-y-4">
            <span>
                <p class="opacity-80">Grade</p>
                <p class="font-bold"></p>
            </span>
            <span>
                <p class="opacity-80">Track</p>
                <p class="font-bold"></p>
            </span>
            <span>
                <p class="opacity-80">Contact</p>
                <p class="font-bold"></p>
            </span>
        </div>
        <div class="flex flex-col flex-1 space-y-4">
            <span>
                <p class="opacity-80">Interview Date</p>
                <p class="font-bold"></p>
            </span>
            <span>
                <p class="opacity-80">Interview Time</p>
                <p class="font-bold"></p>
            </span>
            <span>
                <p class="opacity-80">Location</p>
                <p class="font-bold"></p>
            </span>
        </div>
        <div class="flex flex-col flex-1 space-y-4">
            <span>
                <p class="opacity-80">Interviewer</p>
                <p class="font-bold"></p>
            </span>
            <span>
                <p class="opacity-80">Status</p>

            </span>
        </div>
        <div class="flex flex-col flex-1 space-y-4">
            <span>
                <p class="opacity-80">Remarks</p>
                <p class="font-bold"></p>
            </span>
        </div>
    </div>
@endsection

@section('docs_submission_progress')
    <div class="flex flex-col py-[16px] text-[14px]">
        <div class="flex flex-row justify-start items-center space-x-2 text-start px-[14px] py-2">
            <i class="fi fi-rs-documents text-[#0f111c]"></i>
            <p class="text-[18px] font-semibold">Documents Submission Progress</p>
        </div>
        <div class="bg-[#f8f8f8] flex flex-col rounded-md border shadow-sm border-[#1e1e1e]/15 p-4">
            <div class="w-full">
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
                            <th
                                class="w-1/7 text-center bg-[#E3ECFF] border-b border-[#1e1e1e]/15 rounded-tr-[9px] px-4 py-2">
                                Actions
                            </th>
                        </tr>
                    </thead>


                    <tbody>
                        @foreach ($required_docs as $doc)
                            @php
                                $submission = $submissions[$doc->id] ?? null;

                            @endphp
                            <tr class="border-t-[1px] border-[#1e1e1e]/15 w-full rounded-md">
                                <td class="w-1/8 text-start font-medium py-[8px] text-[14px] opacity-80 px-4 py-2 truncate">
                                    {{ $doc->type }}
                                </td>
                                <td
                                    class="w-1/8 text-center font-medium py-[8px] text-[14px] opacity-80 px-4 py-2 truncate">

                                    @if (!is_null($submission))
                                        @if ($submission->status == 'Pending')
                                            <span class="bg-[#FFF4E5] text-[#FBBC04] px-2 py-1 rounded-md font-medium">
                                                Pending
                                            </span>
                                        @elseif ($submission->status == 'Verified')
                                            <span class="bg-[#E6F4EA] text-[#34A853] px-2 py-1 rounded-md font-medium">
                                                Verified
                                            </span>
                                        @elseif ($submission->status == 'rejected')
                                            <span class="bg-[#FCE8E6] text-[#EA4335] px-2 py-1 rounded-md font-medium">
                                                Rejected
                                            </span>
                                        @endif
                                    @else
                                        <span class="bg-[#E8EAED] text-[#5F6368] px-2 py-1 rounded-md font-medium">
                                            Not submitted
                                        </span>
                                    @endif


                                </td>
                                <td
                                    class="w-1/8 text-center font-medium py-[8px] text-[14px] opacity-80 px-4 py-2 truncate">
                                    asd
                                </td>
                                <td
                                    class="w-1/8 text-center font-medium py-[8px] text-[14px] opacity-100 px-4 py-2 truncate">
                                    <div class="flex flex-row justify-center items-center gap-2">
                                        <x-nav-link href="/required-docs/edit/"
                                            class="flex flex-row gap-2 justify-center items-center text-[14px] py-2 px-3 rounded-md bg-[#1A73E8] text-white font-medium transition-colors duration-200"
                                            title="View document">
                                            <i class="fi fi-rs-eye text-[16px] flex justify-center items-center"></i>
                                            View
                                        </x-nav-link>
                                        {{-- Are you sure you want to reject this document? This action will mark the document as invalid and notify the relevant parties. --}}


                                        <button type="button" id="open-verify-modal-btn"
                                            onclick="openVerifyModal({{ $doc->id }})"
                                            class="flex flex-row gap-2 justify-center items-center text-[14px] text-white py-2 px-3 rounded-md bg-[#34A853] hover:ring-1 ring-[#34A853]/60 font-medium"
                                            title="Accept document">
                                            <i class="fi fi-rs-check text-[16px] flex justify-center items-center"></i>
                                            Verify
                                        </button>


                                        <form action="/asdsada" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                class="flex flex-row gap-1 justify-center items-center text-[14px] text-white py-2 px-3 rounded-md bg-[#EA4335] hover:ring-1 ring-[#EA4335]/60 font-semibold"
                                                title="Reject document">
                                                <i
                                                    class="fi fi-rs-cross-small text-[16px] flex justify-center items-center"></i>
                                                Reject
                                            </button>
                                        </form>
                                    </div>
                                </td>
                                {{-- @dd($pending_applicant->applicationForm->id) --}}
                            </tr>
                        @endforeach
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

        let table;
        let pendingApplications = document.querySelector('#pending-application');

        document.addEventListener("DOMContentLoaded", function() {

            function openVerifyModal(docId) {
                document.getElementById('verify-doc-form').action = `/submit-document/${docId}`;
                
                // Your modal opening logic
                //document.getElementById('verify-doc-modal').style.display = 'block';
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




            initModal('verify-doc-modal', 'open-verify-modal-btn', 'verify-doc-close-btn', 'cancel-btn');







            initModal('record-interview-modal', 'record-interview-btn', 'record-interview-close-btn', 'cancel-btn');
            initModal('sched-interview-modal', 'record-btn', 'sched-interview-close-btn', 'cancel-btn');



        });
    </script>
@endpush
