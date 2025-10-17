@extends('layouts.admin')

@section('modal')
    {{-- Create Document Modal --}}
    <x-modal modal_id="create-document-modal" modal_name="Create Document" close_btn_id="create-document-modal-close-btn"
        modal_container_id="modal-container-1">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-document flex justify-center items-center '></i>
        </x-slot>

        <form id="create-document-modal-form" class="p-6">
            @csrf
            <div class="flex flex-col justify-center items-center space-y-4">

                <div class="w-full">
                    <label for="doc-type" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-tags mr-2"></i>
                        Document Name/Type <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="doc-type" id="doc-type" required
                        placeholder="e.g., Birth Certificate, Transcript of Records"
                        class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]">
                    </div>

                <div class="w-full">
                    <label for="file-size" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-file-size mr-2"></i>
                        Max File Size (KB) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="file-size" id="file-size" required min="1" max="10000"
                        placeholder="1024"
                        class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]">
                    <p class="text-[12px] mt-1 text-gray-600">Estimated size in MB: <span id="estimated" class="font-bold"></span></p>
                </div>

                <div class="w-full">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-file-type mr-2"></i>
                        File Type Restrictions <span class="text-red-500">*</span>
                    </label>
                    <div class="space-y-2">
                        <div class="flex items-center">
                            <input type="checkbox" name="file-type-option[]" value="pdf" id="PDF"
                                class="w-4 h-4 text-[#199BCF] border-[#199BCF]/30 rounded focus:ring-[#199BCF]/20">
                            <label for="PDF" class="ml-2 text-sm font-medium text-gray-700 cursor-pointer">PDF</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="file-type-option[]" value="jpeg" id="JPEG"
                                class="w-4 h-4 text-[#199BCF] border-[#199BCF]/30 rounded focus:ring-[#199BCF]/20">
                            <label for="JPEG" class="ml-2 text-sm font-medium text-gray-700 cursor-pointer">JPEG</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="file-type-option[]" value="png" id="PNG"
                                class="w-4 h-4 text-[#199BCF] border-[#199BCF]/30 rounded focus:ring-[#199BCF]/20">
                            <label for="PNG" class="ml-2 text-sm font-medium text-gray-700 cursor-pointer">PNG</label>
                    </div>
                </div>
            </div>

                <div class="w-full">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-document mr-2"></i>
                        Description/Instruction
                    </label>
                    <textarea name="description" id="description" rows="4" placeholder="Enter document description or instructions..."
                        class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px] resize-none"></textarea>
                </div>
                </div>
        </form>

        <x-slot name="modal_buttons">
            <button id="create-document-modal-cancel-btn"
                class="bg-gray-50 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-100 hover:ring hover:ring-gray-200 transition duration-150">
                Cancel
            </button>
            <button type="submit" form="create-document-modal-form" id="create-document-submit-btn"
                class="bg-[#199BCF] py-2 px-3 rounded-xl text-[14px] font-semibold gap-2 text-white hover:ring hover:ring-[#C8A165]/20 hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                Create Document
            </button>
        </x-slot>
    </x-modal>

    {{-- Edit Document Modal --}}
    <x-modal modal_id="edit-document-modal" modal_name="Edit Document" close_btn_id="edit-document-modal-close-btn"
        modal_container_id="modal-container-2">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-edit flex justify-center items-center '></i>
        </x-slot>

        <form id="edit-document-modal-form" class="p-6">
            @csrf
            <input type="hidden" name="_method" value="PUT">
            <div class="flex flex-col justify-center items-center space-y-4">

                <div class="w-full">
                    <label for="edit_doc-type" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-tags mr-2"></i>
                        Document Name/Type <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="doc-type" id="edit_doc-type" required
                        placeholder="e.g., Birth Certificate, Transcript of Records"
                        class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]">
                </div>

                <div class="w-full">
                    <label for="edit_file-size" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-file-size mr-2"></i>
                        Max File Size (KB) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="file-size" id="edit_file-size" required min="1" max="10000"
                        placeholder="1024"
                        class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]">
                    <p class="text-[12px] mt-1 text-gray-600">Estimated size in MB: <span id="edit_estimated" class="font-bold"></span></p>
                </div>

                <div class="w-full">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-file-type mr-2"></i>
                        File Type Restrictions <span class="text-red-500">*</span>
                    </label>
                    <div class="space-y-2">
                        <div class="flex items-center">
                            <input type="checkbox" name="file-type-option[]" value="pdf" id="edit_PDF"
                                class="w-4 h-4 text-[#199BCF] border-[#199BCF]/30 rounded focus:ring-[#199BCF]/20">
                            <label for="edit_PDF" class="ml-2 text-sm font-medium text-gray-700 cursor-pointer">PDF</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="file-type-option[]" value="jpeg" id="edit_JPEG"
                                class="w-4 h-4 text-[#199BCF] border-[#199BCF]/30 rounded focus:ring-[#199BCF]/20">
                            <label for="edit_JPEG" class="ml-2 text-sm font-medium text-gray-700 cursor-pointer">JPEG</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="file-type-option[]" value="png" id="edit_PNG"
                                class="w-4 h-4 text-[#199BCF] border-[#199BCF]/30 rounded focus:ring-[#199BCF]/20">
                            <label for="edit_PNG" class="ml-2 text-sm font-medium text-gray-700 cursor-pointer">PNG</label>
            </div>
                </div>
            </div>

                <div class="w-full">
                    <label for="edit_description" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-document mr-2"></i>
                        Description/Instruction
                    </label>
                    <textarea name="description" id="edit_description" rows="4" placeholder="Enter document description or instructions..."
                        class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px] resize-none"></textarea>
                </div>
            </div>
        </form>

        <x-slot name="modal_buttons">
            <button id="edit-document-modal-cancel-btn"
                class="bg-gray-50 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-100 hover:ring hover:ring-gray-200 transition duration-150">
                Cancel
            </button>
            <button type="submit" form="edit-document-modal-form" id="edit-document-submit-btn"
                class="bg-[#199BCF] py-2 px-3 rounded-xl text-[14px] font-semibold gap-2 text-white hover:ring hover:ring-[#C8A165]/20 hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                Update Document
            </button>
        </x-slot>
    </x-modal>

    {{-- Delete Document Modal --}}
    <x-modal modal_id="delete-document-modal" modal_name="Delete Document" close_btn_id="delete-document-close-btn"
        modal_container_id="modal-container-delete-document">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-trash flex justify-center items-center text-red-500'></i>
        </x-slot>

        <div class="p-6">
            <div class="flex flex-col items-center space-y-4">
                <div class="text-center">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Confirm Deletion</h3>
                    <p class="text-gray-600">Are you sure you want to delete this document? This action cannot be undone.</p>
                </div>
            </div>
        </div>

        <x-slot name="modal_buttons">
            <button id="delete-document-cancel-btn"
                class="bg-gray-50 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-100 hover:ring hover:ring-gray-200 transition duration-150">
                Cancel
            </button>
            <form id="delete-document-form" class="inline">
                @csrf
                <button type="submit" id="delete-document-submit-btn"
                    class="bg-red-500 text-[14px] px-3 py-2 rounded-xl text-white font-bold hover:ring hover:ring-red-200 hover:bg-red-400 transition duration-150 shadow-sm hover:scale-95">
                    Delete Document
                </button>
            </form>
        </x-slot>
    </x-modal>
@endsection

@section('header')
    <div class="flex flex-row justify-between items-center space-x-2 text-start px-[14px] py-2">
        <div>
            <h1 class="text-[20px] font-black">Document Management</h1>
            <p class="text-[14px] text-gray-900/60">View and manage required documents for applications.</p>
        </div>
        <button id="create-document-modal-btn"
            class="text-[16px] px-3 py-2 rounded-xl bg-[#199BCF] text-[#f8f8f8] font-semibold flex flex-row items-center justify-center gap-2 hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg">
            <i class="fi fi-sr-square-plus opacity-70 flex justify-center items-center text-[18px]"></i> Add Document
        </button>
    </div>
@endsection

@section('content')
    <x-alert />

    <div class="flex flex-col justify-center items-start gap-4">
        <div
            class="flex flex-col justify-start items-start flex-grow p-6 space-y-4 bg-[#f8f8f8] rounded-xl shadow-md border border-[#1e1e1e]/10 w-full">
            <div class="flex flex-row justify-between items-center w-full">
                <div>
                    <span class="font-semibold text-[18px]">
                        Required Documents
                    </span>
                    <p class="text-[14px] text-gray-500">Manage document requirements for applications</p>
                </div>
            </div>
            
            <div class="flex flex-row justify-between items-center w-full h-full py-2">
                <div class="flex flex-row justify-between w-2/3 items-center gap-4">
                    <label for="document-search"
                        class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-2 gap-2 w-[40%] hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm">
                        <i class="fi fi-rs-search flex justify-center items-center text-[#1e1e1e]/60 text-[16px]"></i>
                        <input type="search" name="" id="document-search"
                            class="my-custom-search bg-transparent outline-none text-[14px] w-full peer"
                            placeholder="Search by name and description">
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
                            </select>
                            <i id="clear-page-length-btn"
                                class="fi fi-rr-caret-down text-gray-500 flex justify-center items-center"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-full">
                <table id="documents-table" class="w-full table-fixed">
                    <thead class="text-[14px]">
                        <tr>
                            <th class="w-[3%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-2 py-2">
                                <span class="mr-2 font-medium opacity-60 cursor-pointer">#</span>
                            </th>
                            <th class="w-[25%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                <span class="mr-2 font-medium opacity-60 cursor-pointer">Name/Type</span>
                                <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                            </th>
                            <th class="w-[35%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                <span class="mr-2 font-medium opacity-60 cursor-pointer">Description</span>
                                <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                            </th>
                            <th class="w-[15%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                <span class="mr-2 font-medium opacity-60 cursor-pointer">File Types</span>
                                <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                            </th>
                            <th class="w-[12%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                <span class="mr-2 font-medium opacity-60 cursor-pointer">Max Size</span>
                                <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                            </th>
                            <th class="w-[10%] text-center bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                <span class="mr-2 font-medium opacity-60 select-none">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Data will be populated via AJAX --}}
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
            initCustomDataTable
        } from "/js/initTable.js";
        import {
            showLoader,
            hideLoader
        } from "/js/loader.js";
        import {
            clearSearch
        } from "/js/clearSearch.js";

        let documentsTable;
        let selectedPageLength = '';

        // Set global variables for initTable.js
        window.selectedPageLength = selectedPageLength;

        document.addEventListener("DOMContentLoaded", function() {
            // Initialize modals
            initModal('create-document-modal', 'create-document-modal-btn', 'create-document-modal-close-btn',
                'create-document-modal-cancel-btn', 'modal-container-1');
            initModal('edit-document-modal', 'edit-document-modal-btn', 'edit-document-modal-close-btn',
                'edit-document-modal-cancel-btn', 'modal-container-2');
            initModal('delete-document-modal', 'delete-document-modal-btn', 'delete-document-close-btn',
                'delete-document-cancel-btn', 'modal-container-delete-document');

            // Initialize DataTable
            documentsTable = initCustomDataTable(
                'documents-table',
                `/getDocuments`,
                [{
                        data: 'index'
                    },
                    {
                        data: 'type',
                        render: DataTable.render.text()
                    },
                    {
                        data: 'description',
                        render: DataTable.render.text()
                    },
                    {
                        data: 'file_type_restriction',
                        render: DataTable.render.text()
                    },
                    {
                        data: 'max_file_size',
                        render: DataTable.render.text()
                    },
                    {
                        data: 'id',
                        render: function(data, type, row) {
                            return `
                            <div class='flex flex-row justify-center items-center gap-2'>
                                <button type="button" id="open-edit-modal-btn-${data}"
                                    data-document-id="${data}"
                                    class="edit-document-btn group relative inline-flex items-center gap-2 bg-blue-100 text-blue-500 font-semibold p-2 rounded-xl hover:bg-blue-500 hover:ring hover:ring-blue-200 hover:text-white transition duration-150">
                                    <i class="fi fi-rr-edit text-[16px] flex justify-center items-center"></i>
                                </button>
                                <button type="button" id="open-delete-modal-btn-${data}"
                                    data-document-id="${data}"
                                    class="delete-document-btn group relative inline-flex items-center gap-2 bg-red-100 text-red-500 font-semibold p-2 rounded-xl hover:bg-red-500 hover:ring hover:ring-red-200 hover:text-white transition duration-150">
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
                'document-search',
                [{
                        width: '3%',
                        targets: 0,
                        className: 'text-center'
                    },
                    {
                        width: '25%',
                        targets: 1
                    },
                    {
                        width: '35%',
                        targets: 2
                    },
                    {
                        width: '15%',
                        targets: 3
                    },
                    {
                        width: '12%',
                        targets: 4
                    },
                    {
                        width: '10%',
                        targets: 5,
                        className: 'text-center'
                    }
                ]
            );

            clearSearch('clear-btn', 'document-search', documentsTable);

            let pageLengthSelection = document.querySelector('#page-length-selection');

            // Special handler for page length
            pageLengthSelection.addEventListener('change', (e) => {
                let selectedPageLength = parseInt(e.target.value, 10);
                documentsTable.page.len(selectedPageLength).draw();
            });

            // Initialize edit and delete modals dynamically
            initializeEditDocumentModals();
            initializeDeleteDocumentModals();

            // Reinitialize modals after table draw
            documentsTable.on('draw', function() {
                initializeEditDocumentModals();
                initializeDeleteDocumentModals();
            });

            // File size estimation for create modal
            let sizeInput = document.getElementById("file-size");
            let estimatedSize = document.getElementById("estimated");

            if (sizeInput && estimatedSize) {
            sizeInput.addEventListener("input", function() {
                let size = parseInt(this.value);
                if (!isNaN(size)) {
                    estimatedSize.textContent = (size / 1024).toFixed(2) + " MB";
                } else {
                    estimatedSize.textContent = "Invalid size";
                }
            });
            }

            // File size estimation for edit modal
            let editSizeInput = document.getElementById("edit_file-size");
            let editEstimatedSize = document.getElementById("edit_estimated");

            if (editSizeInput && editEstimatedSize) {
                editSizeInput.addEventListener("input", function() {
                    let size = parseInt(this.value);
                    if (!isNaN(size)) {
                        editEstimatedSize.textContent = (size / 1024).toFixed(2) + " MB";
                    } else {
                        editEstimatedSize.textContent = "Invalid size";
                    }
                });
            }

            // Create Document Form Submission
            document.getElementById('create-document-modal-form').addEventListener('submit', function(e) {
                e.preventDefault();

                let form = e.target;
                let formData = new FormData(form);

                // Show loader
                showLoader("Creating document...");

                fetch('/required-docs', {
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

                            // Close modal
                            closeModal('create-document-modal', 'modal-container-1');

                            // Show success alert
                            showAlert('success', 'Document created successfully!');

                            // Refresh table
                            if (typeof documentsTable !== 'undefined') {
                                documentsTable.draw();
                            }
                        } else {
                            showAlert('error', data.error || 'Failed to create document');
                        }
                    })
                    .catch(err => {
                        hideLoader();
                        console.error('Error:', err);
                        closeModal('create-document-modal', 'modal-container-1');
                        showAlert('error', 'Something went wrong while creating the document');
                    });
            });

            // Edit Document Form Submission
            document.getElementById('edit-document-modal-form').addEventListener('submit', function(e) {
                e.preventDefault();

                let form = e.target;
                let formData = new FormData(form);
                const documentId = formData.get('document_id');

                if (!documentId) {
                    showAlert('error', 'Document ID not found');
                    return;
                }

                // Add the document ID to the form data
                formData.append('_method', 'PUT');

                // Show loader
                showLoader("Updating document...");

                fetch(`/required-docs/${documentId}`, {
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
                            const hiddenInput = document.getElementById('edit_document_id');
                            if (hiddenInput) {
                                hiddenInput.remove();
                            }

                            // Close modal
                            closeModal('edit-document-modal', 'modal-container-2');

                            // Show success alert
                            showAlert('success', 'Document updated successfully!');

                            // Refresh table
                            if (typeof documentsTable !== 'undefined') {
                                documentsTable.draw();
                            }
                        } else {
                            showAlert('error', data.error || 'Failed to update document');
                        }
                    })
                    .catch(err => {
                        hideLoader();
                        console.error('Error:', err);
                        closeModal('edit-document-modal', 'modal-container-2');
                        showAlert('error', 'Something went wrong while updating the document');
                    });
            });

            // Delete Document Form Submission
            const deleteDocumentForm = document.getElementById('delete-document-form');
            if (deleteDocumentForm) {
                deleteDocumentForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(this);
                    const documentId = formData.get('document_id');

                    showLoader();
                    fetch(`/required-docs/${documentId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            hideLoader();
                            if (data.success === false && data.has_applicant_documents === true) {
                                showAlert('error', data.error);
                                closeModal('delete-document-modal', 'modal-container-delete-document');
                            } else if (data.success === true) {
                                showAlert('success', data.message);
                                documentsTable.draw(); // Refresh the table
                                // Close modal
                                document.getElementById('delete-document-close-btn').click();
                            } else {
                                showAlert('error', data.message);
                            }
                        })
                        .catch(error => {
                            hideLoader();
                            console.error('Error:', error);
                            showAlert('error', 'An error occurred while deleting the document');
                        });
                });
            }
        });

        // Initialize edit document modals dynamically
        function initializeEditDocumentModals() {
            document.querySelectorAll('.edit-document-btn').forEach((button) => {
                let documentId = button.getAttribute('data-document-id');
                let buttonId = `open-edit-modal-btn-${documentId}`;

                // Initialize modal for this specific button
                initModal('edit-document-modal', buttonId, 'edit-document-modal-close-btn',
                    'edit-document-modal-cancel-btn', 'modal-container-2');

                button.addEventListener('click', () => {
                    // Clear any existing hidden inputs first
                    let form = document.getElementById('edit-document-modal-form');
                    let existingInputs = form.querySelectorAll('input[name="document_id"]');
                    existingInputs.forEach(input => input.remove());

                    // Add document ID as hidden input
                    let documentIdInput = document.createElement('input');
                    documentIdInput.type = 'hidden';
                    documentIdInput.value = documentId;
                    documentIdInput.name = "document_id";
                    documentIdInput.id = "edit_document_id";
                    form.appendChild(documentIdInput);

                     // Fetch document data and populate the form
                     showLoader();
                     fetch(`/required-docs/${documentId}`)
                         .then(response => response.json())
                         .then(data => {
                             hideLoader();
                             if (data.success && data.document) {
                                 const docData = data.document;

                                 // Populate form fields
                                 document.getElementById('edit_doc-type').value = docData.type || '';
                                 document.getElementById('edit_description').value = docData.description || '';
                                 document.getElementById('edit_file-size').value = docData.max_file_size || '';

                                 // Update estimated size
                                 let size = parseInt(docData.max_file_size);
                                 if (!isNaN(size)) {
                                     document.getElementById('edit_estimated').textContent = (size / 1024).toFixed(2) + " MB";
                                 }

                                 // Clear and set file type checkboxes
                                 ['PDF', 'JPEG', 'PNG'].forEach(type => {
                                     document.getElementById(`edit_${type}`).checked = false;
                                 });

                                 if (docData.file_type_restriction) {
                                     docData.file_type_restriction.forEach(type => {
                                         const checkbox = document.getElementById(`edit_${type.toUpperCase()}`);
                                         if (checkbox) {
                                             checkbox.checked = true;
                                         }
                                     });
                                 }

                                 console.log('Edit modal opened for document ID:', documentId);
                             } else {
                                 showAlert('error', 'Error loading document: ' + data.error);
                             }
                         })
                        .catch(error => {
                            hideLoader();
                            console.error('Error:', error);
                            showAlert('error', 'An error occurred while loading the document');
                        });
                });
            });
        }

        // Initialize delete document modals dynamically
        function initializeDeleteDocumentModals() {
            document.querySelectorAll('.delete-document-btn').forEach((button) => {
                let documentId = button.getAttribute('data-document-id');
                let buttonId = `open-delete-modal-btn-${documentId}`;

                // Initialize modal for this specific button
                initModal('delete-document-modal', buttonId, 'delete-document-close-btn',
                    'delete-document-cancel-btn', 'modal-container-delete-document');

                button.addEventListener('click', () => {
                    // Clear any existing hidden inputs first
                    let form = document.getElementById('delete-document-form');
                    let existingInputs = form.querySelectorAll('input[name="document_id"]');
                    existingInputs.forEach(input => input.remove());

                    // Set the form action dynamically
                    form.action = `/required-docs/${documentId}`;

                    // Add document ID as hidden input
                    let documentIdInput = document.createElement('input');
                    documentIdInput.type = 'hidden';
                    documentIdInput.name = 'document_id';
                    documentIdInput.value = documentId;
                    form.appendChild(documentIdInput);

                    console.log('Delete modal opened for document ID:', documentId);
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
    </script>
@endpush
