@extends('layouts.admin')

@section('modal')
    <x-modal modal_id="create-document" modal_name="Create Document" close_btn_id="create-docs-close-btn">
        <form action="/required-docs" method="POST" id="create-docs-form" class="pt-2 pb-4 px-4 space-y-2">
            @csrf
            <div class="flex flex-row gap-2">
                <div class="flex-1 space-y-1 ">
                    <label for="doc-type" class="text-[14px] font-bold opacity-90">Name/Type</label>
                    <div
                        class="flex items-center px-2 py-2 rounded-md bg-[#E3ECFF] focus-within:ring-2 focus-within:ring-[#199BCF]/40 space-x-2">
                        <i class="fi fi-rs-calendar-day flex items-center opacity-60"></i>
                        <input type="text" name="doc-type" id="doc-type" placeholder="Document Name/Type"
                            class="appearance-none     
                        [&::-webkit-outer-spin-button]:appearance-none
                        [&::-webkit-inner-spin-button]:appearance-none
                        [-moz-appearance:textfield] bg-transparent outline-none font-medium text-[14px] w-full">
                    </div>
                </div>
                <div class="flex-1 space-y-1">
                    <label for="file-size" class="text-[14px] font-bold opacity-90">Max file size (in KB) </label>
                    <div
                        class="flex items-center px-2 py-2 rounded-md bg-[#E3ECFF] focus-within:ring-2 focus-within:ring-[#199BCF]/40 space-x-2">
                        <i class="fi fi-rs-calendar-day flex items-center opacity-60"></i>
                        <input type="text" name="file-size" id="file-size" placeholder="1024"
                            class="appearance-none     
                    [&::-webkit-outer-spin-button]:appearance-none
                    [&::-webkit-inner-spin-button]:appearance-none
                    [-moz-appearance:textfield] bg-transparent outline-none font-medium text-[14px] w-full">

                    </div>
                </div>
            </div>
            <div class="flex-1 space-y-1">
                <div class="flex flex-row">
                    <p class="flex-1 text-[14px] font-bold opacity-90">File type restrictions</p>
                    <p class="flex-1 text-[12px] font-medium opacity-60">Estimated size in MB: <span id="estimated" class="font-bold"></span></p>
                </div>
                <div class="flex flex-row justify-start items-center ml-2 rounded-md space-x-2">
                    <input type="checkbox" name="file-type-option[]" value="pdf" id="PDF"
                        class="bg-red-500 size-[16px] cursor-pointer">

                    <label for="PDF" class="text-[14px] font-bold opacity-90 cursor-pointer select-none">PDF</label>
                </div>
                <div class="flex flex-row justify-start items-center ml-2 rounded-md space-x-2">
                    <input type="checkbox" name="file-type-option[]" value="jpeg" id="JPEG"
                        class="bg-red-500 size-[16px] cursor-pointer">

                    <label for="JPEG" class="text-[14px] font-bold opacity-90 cursor-pointer select-none">JPEG</label>
                </div>
                <div class="flex flex-row justify-start items-center ml-2 rounded-md space-x-2">
                    <input type="checkbox" name="file-type-option[]" value="png" id="PNG"
                        class="bg-red-500 size-[16px] cursor-pointer">

                    <label for="PNG" class="text-[14px] font-bold opacity-90 cursor-pointer select-none">PNG</label>
                </div>

            </div>
            <div class="flex-1 space-y-1">
                <label for="description" class="text-[14px] font-bold">
                    Description/Instruction
                </label>
                <div
                    class="flex items-center px-2 py-2 rounded-md bg-[#E3ECFF] focus-within:ring-2 focus-within:ring-[#199BCF]/40 space-x-2">
                    <i class="fi fi-rs-info flex items-center opacity-60"></i>
                    <textarea name="description" id="description" cols="10" rows="10"
                        class="bg-transparent outline-none font-medium text-[14px] w-full resize-none h-[100px]"></textarea>
                </div>
            </div>

        </form>

        <x-slot name="modal_buttons">
            <button id="create-docs-cancel-btn"
                class="border border-[#1e1e1e]/15 text-[14px] px-2 py-1 rounded-md text-[#0f111c]/80 font-bold">Cancel</button>
            <button form="create-docs-form" id="create-docs-period-confirmation" data-id=""
                class="bg-[#F97316] text-[14px] px-2 py-1 rounded-md text-[#f8f8f8] font-bold">Confirm</button>
        </x-slot>
    </x-modal>
@endsection

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

            <li class="rtl:rotate-180">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
            </li>

            <li>
                <a href="#" class="block transition-colors hover:text-gray-900"> Document List </a>
            </li>
        </ol>
    </nav>
@endsection

@section('header')
    <div class="flex flex-row justify-between items-center space-x-2 text-start px-[14px] py-2">
        <h1 class="text-[22px] font-bold text-gray-900">Document List</h1>
        <button id="create-docs-btn"
            class="text-[16px] px-2 py-1 rounded-md bg-[#1A73E8] text-[#f8f8f8] font-semibold flex flex-row items-center justify-center gap-2">
            <i class="fi fi-rs-add-document text-[18px] flex justify-center items-center"></i> Add Document
        </button>
    </div>
@endsection

@section('content')
    <div class="flex flex-col">

        <div class="flex flex-col items-center flex-grow px-[14px] py-[10px] space-y-2">
            <div class="border border-[#1e1e1e]/15 self-start my-custom-search">
                <i class="fi fi-rs-search text-[#0f111c]"></i>
                <input type="search" name="" id="myCustomSearch" class="bg-transparent"
                    placeholder="Search...">
            </div>

            <div class="w-full">
                <table id="docs-table" class="w-full table-fixed">
                    <thead class="text-[14px]">
                        <tr>
                            <th class="w-1/7 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                <span class="mr-2">Name/Type</span>
                                <i class="fi fi-ss-sort text-[12px] cursor-pointer opacity-60"></i>
                            </th>
                            <th class="w-1/7 text-center bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                <span class="mr-2">Description/Instruction</span>
                                <i class="fi fi-ss-sort text-[12px] cursor-pointer opacity-60"></i>
                            </th>
                            <th class="w-1/7 text-center bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                <span class="mr-2">File type restrictions</span>
                                <i class="fi fi-ss-sort text-[12px] cursor-pointer opacity-60"></i>
                            </th>
                            <th class="w-1/7 text-center bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                <span class="mr-2">Max file size</span>
                                <i class="fi fi-ss-sort text-[12px] cursor-pointer opacity-60"></i>
                            </th>
                            <th
                                class="w-1/7 text-center bg-[#E3ECFF] border-b border-[#1e1e1e]/15 rounded-tr-[9px] px-4 py-2">
                                Actions
                            </th>
                        </tr>
                    </thead>


                    <tbody>

                        @foreach ($required_docs as $document)
                            <tr class="border-t-[1px] border-[#1e1e1e]/15 w-full rounded-md">
                                <td
                                    class="w-1/8 text-start font-medium py-[8px] text-[14px] opacity-80 px-4 py-2 truncate">
                                    {{ $document->type }}
                                </td>
                                <td
                                    class="w-1/8 text-center font-medium py-[8px] text-[14px] opacity-80 px-4 py-2 truncate">
                                    {{ $document->description ?? '-' }}
                                </td>
                                <td
                                    class="w-1/8 text-center font-medium py-[8px] text-[14px] opacity-80 px-4 py-2 truncate">
                                    {{ implode(', ', $document->file_type_restriction) }}
                                </td>
                                <td
                                    class="w-1/8 text-center font-medium py-[8px] text-[14px] opacity-80 px-4 py-2 truncate">
                                    {{ $document->max_file_size }} KB
                                </td>

                                <td
                                    class="w-1/8 text-center font-medium py-[8px] text-[14px] opacity-80 px-4 py-2 truncate">
                                    <div class="flex flex-row justify-center items-center gap-2">
                                        <button
                                            class="flex flex-row gap-1 justify-center items-center text-[14px] px-2 py-1 rounded-md bg-[#E7F0FD] border border-[#1A73E8]/60 font-semibold">
                                            <i class="fi fi-rs-edit text-[16px] text-[#1A73E8] flex justify-center items-center"></i> Edit
                                        </button>
                                        <form action="/required-docs/{{ $document->id }}" method="POST"
                                            class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="flex flex-row gap-1 justify-center items-center text-[14px] px-2 py-1 rounded-md bg-[#FCE8E6] border border-[#EA4335]/60 font-semibold">
                                                <i class="fi fi-rs-trash text-[16px] text-[#EA4335] flex justify-center items-center"></i>
                                                Delete
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
            table = new DataTable('#docs-table', {
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

            //Overriding default search input
            const customSearch = document.getElementById("myCustomSearch");
            const defaultSearch = document.querySelector(".dt-search");

            defaultSearch.remove();
            customSearch.addEventListener("input", function(e) {
                table.search(this.value).draw();
            });


            let sizeInput = document.getElementById("file-size");
            let estimatedSize = document.getElementById("estimated");

            sizeInput.addEventListener("input", function() {
                let size = parseInt(this.value);
                if (!isNaN(size)) {
                    estimatedSize.textContent = (size / 1024).toFixed(2) + " MB";
                } else {
                    estimatedSize.textContent = "Invalid size";
                }
            });


        });


        // Initialize the modal
        initModal("create-document", "create-docs-btn", "create-docs-close-btn", "create-docs-cancel-btn");
    </script>
@endpush
