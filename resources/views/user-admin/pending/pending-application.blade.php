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
                <a href="/pending-applications" class="block transition-colors hover:text-gray-900"> Pending Applications
                </a>
            </li>

        </ol>
    </nav>
@endsection

@section('header')
    <div class="flex flex-col justify-center items-start text-start px-[14px] py-2">
        <h1 class="text-[20px] font-black">Pending Applications</h1>
        <p class="text-[14px]  text-gray-900/60">List of pending applicants awaiting review
        </p>
    </div>
@endsection

@section('content')
    <div class="flex flex-col bg-[#f8f8f8] rounded-xl shadow-md border border-[#1e1e1e]/10">

        <div class="flex flex-col items-center flex-grow p-6 space-y-2">
            <label for="myCustomSearch"
                class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 self-start rounded-xl py-1 px-2 gap-2 w-[40%] hover:ring hover:ring-blue-200 focus-within:ring focus-within:ring-blue-100 focus-within:border-blue-500 transition duration-150">
                <i class="fi fi-rs-search flex justify-center items-center text-[#1e1e1e]/60 text-[16px]"></i>
                <input type="search" name="" id="myCustomSearch"
                    class="my-custom-search bg-transparent outline-none text-[14px] w-full peer"
                    placeholder="Search by applicant id, name, interviewer">
                <button id="clear-btn"
                    class="clear-btn flex justify-center items-center peer-placeholder-shown:hidden peer-not-placeholder-shown:block">
                    <i class="fi fi-rs-cross-small text-[18px] flex justify-center items-center"></i>
                </button>
            </label>

            <div class="w-full">
                <table id="pendingTable" class="w-full table-fixed">
                    <thead class="text-[14px]">
                        <tr>
                            <th
                                class="w-1/7 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 rounded-tl-[9px] px-4 py-2">
                                <span class="mr-2">Applicant Id</span>
                                <i class="fi fi-ss-sort text-[12px] cursor-pointer opacity-60"></i>
                            </th>
                            <th class="w-1/7 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                <span class="mr-2">Full Name</span>
                                <i class="fi fi-ss-sort text-[12px] cursor-pointer opacity-60"></i>
                            </th>
                            <th class="w-1/7 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                <span class="mr-2">Grade Level</span>
                                <i class="fi fi-ss-sort text-[12px] cursor-pointer opacity-60"></i>
                            </th>
                            <th class="w-1/7 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                <span class="mr-2">Program</span>
                                <i class="fi fi-ss-sort text-[12px] cursor-pointer opacity-60"></i>
                            </th>
                            <th class="w-1/7 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2 cursor-pointer ">
                                <span class="mr-2">Created at</span>
                                <i class="fi fi-ss-sort text-[12px] opacity-60"></i>
                            </th>
                            <th
                                class="w-1/7 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 rounded-tr-[9px] px-4 py-2">
                                Actions</th>
                        </tr>
                    </thead>


                    <tbody>

                        @foreach ($pending_applicants as $pending_applicant)
                            <tr class="border-t-[1px] border-[#1e1e1e]/15 w-full rounded-md">
                                <td
                                    class="w-1/8 text-start font-regular py-[8px] text-[14px] opacity-80 px-4 py-2 truncate">
                                    {{ $pending_applicant->applicationForm->lrn }}</td>
                                <td
                                    class="w-1/8 text-start font-regular py-[8px] text-[14px] opacity-80 px-4 py-2 truncate">
                                    {{ $pending_applicant->applicationForm->full_name }}</td>
                                <td
                                    class="w-1/8 text-start font-regular py-[8px] text-[14px] opacity-80 px-4 py-2 truncate">
                                    {{ $pending_applicant->applicationForm->grade_level }}</td>
                                <td
                                    class="w-1/8 text-start font-regular py-[8px] text-[14px] opacity-80 px-4 py-2 truncate">
                                    {{ $pending_applicant->applicationForm->desired_program }}</td>

                                <td
                                    class="w-1/8 text-start font-regular py-[8px] text-[14px] opacity-80 px-4 py-2 truncate">
                                    {{ \Carbon\Carbon::parse($pending_applicant->applicationForm->created_at)->timezone('Asia/Manila')->format('M. d - g:i A') }}
                                </td>

                                <td
                                    class="w-1/8 text-start font-regular py-[8px] text-[14px] opacity-80 px-4 py-2 truncate">
                                    <a
                                        href="/pending-application/form-details/{{ $pending_applicant->applicationForm->id }}">View</a>
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
            clearSearch
        } from "/js/clearSearch.js"
        let table;

        document.addEventListener("DOMContentLoaded", function() {

            const customSearch = document.getElementById("myCustomSearch");

            table = new DataTable('#pendingTable', {
                paging: true,
                pageLength: 20,
                searching: true,
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
                    topEnd: null,
                    bottomStart: 'info',
                    bottomEnd: 'paging',
                }
            });

            customSearch.addEventListener("input", function() {
                table.search(this.value).draw();
            });

            table.on('draw', function() {
                let newRow = document.querySelector('#pendingTable tbody tr:first-child');

                // Select all td elements within the new row
                let cells = newRow.querySelectorAll('td');

                cells.forEach(function(cell) {
                    cell.classList.add(
                        'px-4', // Horizontal padding
                        'py-2', // Vertical padding
                        'text-center', // Align text to the start (left)
                        'font-regular',
                        'text-[14px]',
                        'opacity-80',
                        'truncate'
                    );
                });

            });

            table.on("init", function() {
                const defaultSearch = document.querySelector("#dt-search-0");
                if (defaultSearch) {
                    defaultSearch.remove();
                }

                customSearch1.addEventListener("input", function() {
                    table1.search(this.value).draw();
                });
            });

            clearSearch('clear-btn', 'myCustomSearch', table)

        });
    </script>
@endpush
