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
                <a href="/pending-documents" class="block transition-colors hover:text-gray-900"> Pending Documents </a>
            </li>
        </ol>
    </nav>
@endsection

@section('header')
    <div class="flex flex-row justify-between items-center space-x-2 text-start px-[14px] py-2">
        <h1 class="text-[22px] font-bold text-gray-900">Pending documents</h1>
        <x-nav-link href="/pending-documents/document-list"
            class="text-[16px] px-2 py-1 rounded-md bg-[#1A73E8] text-[#f8f8f8] font-semibold flex flex-row items-center justify-center gap-2">
            View Document List
        </x-nav-link>
    </div>
@endsection

@section('content')
    <div class="flex flex-col bg-[#f8f8f8] rounded-xl border shadow-sm border-[#1e1e1e]/10">

        <div class="flex flex-col items-center flex-grow px-[14px] py-[10px] space-y-2">
            <div class="border border-[#1e1e1e]/15 self-start my-custom-search">
                <i class="fi fi-rs-search text-[#0f111c]"></i>
                <input type="search" name="" id="myCustomSearch" class="bg-transparent" placeholder="Search...">
            </div>

            <div class="w-full">
                <table id="pendingTable" class="w-full table-fixed">
                    <thead class="text-[14px]">
                        <tr>
                            <th
                                class="w-1/7 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 rounded-tl-[9px] px-4 py-2">
                                <span class="mr-2">Full Name</span>
                                <i class="fi fi-ss-sort text-[12px] cursor-pointer opacity-60"></i>
                            </th>
                            <th class="w-1/7 text-center bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                <span class="mr-2">Grade</span>
                                <i class="fi fi-ss-sort text-[12px] cursor-pointer opacity-60"></i>
                            </th>
                            <th class="w-1/7 text-center bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                <span class="mr-2">Program</span>
                                <i class="fi fi-ss-sort text-[12px] cursor-pointer opacity-60"></i>
                            </th>
                            <th class="w-1/7 text-center bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                <span class="mr-2">Contact</span>
                                <i class="fi fi-ss-sort text-[12px] cursor-pointer opacity-60"></i>
                            </th>
                            <th class="w-1/7 text-center bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                <div class="flex items-center justify-center">
                                    <span class="mr-2">Interview Date</span>
                                    <i class="fi fi-ss-sort text-[12px] cursor-pointer opacity-60"></i>
                                </div>
                            </th>
                            <th class="w-1/7 text-center bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                <div class="flex items-center justify-center">
                                    <span class="mr-2">Document Status</span>
                                    <i class="fi fi-ss-sort text-[12px] cursor-pointer opacity-60"></i>
                                </div>
                            </th>
                            <th class="w-1/7 text-center bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                <span class="mr-2">Deadline</span>
                                <i class="fi fi-ss-sort text-[12px] cursor-pointer opacity-60"></i>
                            </th>
                            <th class="w-1/7 text-center bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                <span class="mr-2">Status </span>
                                <i class="fi fi-ss-sort text-[12px] cursor-pointer opacity-60"></i>
                            </th>
                            <th
                                class="w-1/7 text-center bg-[#E3ECFF] border-b border-[#1e1e1e]/15 rounded-tr-[9px] px-4 py-2">
                                Actions</th>
                        </tr>
                    </thead>


                    <tbody>

                        @foreach ($pending_documents as $pending_document)
                            <tr class="border-t-[1px] border-[#1e1e1e]/15 w-full rounded-md">
                                <td
                                    class="w-1/8 text-start font-regular py-[8px] text-[14px] opacity-80 px-4 py-2 truncate">
                                    {{ $pending_document->applicationForm->lrn }}</td>
                                <td
                                    class="w-1/8 text-center font-regular py-[8px] text-[14px] opacity-80 px-4 py-2 truncate">
                                    {{ $pending_document->applicationForm->full_name }}</td>
                                <td
                                    class="w-1/8 text-center font-regular py-[8px] text-[14px] opacity-80 px-4 py-2 truncate">
                                    {{ $pending_document->applicationForm->age }}</td>
                                <td
                                    class="w-1/8 text-center font-regular py-[8px] text-[14px] opacity-80 px-4 py-2 truncate">
                                    {{ $pending_document->applicationForm->birthdate }}</td>
                                <td
                                    class="w-1/8 text-center font-regular py-[8px] text-[14px] opacity-80 px-4 py-2 truncate">
                                    {{ \Carbon\Carbon::parse($pending_document->interview->date)->timezone('Asia/Manila')->format('M. d') }}
                                </td>
                                <td
                                    class="w-1/8 text-center font-regular py-[8px] text-[14px] opacity-80 px-4 py-2 truncate">
                                    {{ $pending_document->applicationForm->grade_level }}</td>
                                <td
                                    class="w-1/8 text-center font-regular py-[8px] text-[14px] opacity-80 px-4 py-2 truncate">
                                    {{ $pending_document->interview->status }}</td>
                                <td
                                    class="w-1/8 text-center font-regular py-[8px] text-[14px] opacity-80 px-4 py-2 truncate">
                                    {{ $pending_document->interview->status }}</td>

                                <td
                                    class="w-1/8 text-center font-regular py-[8px] text-[14px] opacity-80 px-4 py-2 truncate">
                                    <a
                                        href="/pending-documents/document-details/{{ $pending_document->id }}">View</a>
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
    <script>
        let table;
        let pendingApplications = document.querySelector('#pending-application');

        document.addEventListener("DOMContentLoaded", function() {

            table = new DataTable('#pendingTable', {
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
        });
    </script>
@endpush
